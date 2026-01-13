<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use Illuminate\Support\Facades\Storage;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\User;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ActivitLogService;
use App\Services\SystemNotificationService;

class FinanceChequeController extends Controller
{
  public function index(Request $request)
{
    $adms = User::where('user_role', 6)->with('userDetails')->get();
    $customers = Customers::where('is_temp', 0)->get();

    // base query
    $query = Deposits::where('type', 'finance-cheque');

    /* -------------------- SEARCH BOX -------------------- */
    if ($request->filled('search')) {
        $search = trim($request->search);

        // ADM user IDs by name or adm_number
        $admUserIds = UserDetails::where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%$search%")
              ->orWhere('adm_number', 'LIKE', "%$search%");
        })->pluck('user_id');

        // customer IDs by name or id
        $customerIds = Customers::where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%$search%")
              ->orWhere('customer_id', 'LIKE', "%$search%");
        })->pluck('customer_id');

        $query->where(function ($q) use ($admUserIds, $customerIds) {
            // match ADM
            $q->whereIn('adm_id', $admUserIds)

            // OR match customer through receipts → invoice payments
            ->orWhereHas('reciepts.invoice.customer', function ($c) use ($customerIds) {
                $c->whereIn('customer_id', $customerIds);
            });
        });
    }

    /* -------------------- ADM NAME FILTER -------------------- */
    if ($request->filled('adm_names')) {
        $query->whereIn('adm_id', $request->adm_names);
    }

    /* -------------------- ADM NUMBER FILTER -------------------- */
    if ($request->filled('adm_ids')) {
        $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
            ->pluck('user_id');

        $query->whereIn('adm_id', $admUserIds);
    }

    /* -------------------- CUSTOMER FILTER -------------------- */
    if ($request->filled('customers')) {
        $query->whereHas('reciepts.invoice.customer', function ($q) use ($request) {
            $q->whereIn('customer_id', $request->customers);
        });
    }

    /* -------------------- DATE RANGE FILTER -------------------- */
    if ($request->filled('date_range')) {

        $range = trim($request->date_range);

        if (str_contains($range, 'to')) {
            [$start, $end] = array_map('trim', explode('to', $range));
        } elseif (str_contains($range, '-')) {
            [$start, $end] = array_map('trim', explode('-', $range));
        } else {
            $start = $end = $range;
        }

        if (!empty($start) && !empty($end)) {
            $query->whereBetween('date_time', [
                date('Y-m-d 00:00:00', strtotime($start)),
                date('Y-m-d 23:59:59', strtotime($end)),
            ]);
        }
    }

    /* -------------------- STATUS FILTER -------------------- */
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    /* -------------------- RESULT + TRANSFORM -------------------- */
    $deposits = $query->orderByDesc('created_at')->paginate(10);

    $deposits->getCollection()->transform(function ($deposit) {
        $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();

        return [
            'id'        => $deposit->id,
            'date'      => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
            'adm_number'=> $userDetail?->adm_number ?? 'N/A',
            'adm_name'  => $userDetail?->name ?? 'N/A',
            'bank_name' => $deposit->bank_name ?? 'N/A',
            'branch_name'=> $deposit->branch_name ?? 'N/A',
            'amount'    => $deposit->amount ?? 0,
            'status'    => $deposit->status,
            'attachment_path' => $deposit->attachment_path ?? null,
        ];
    });

    return view('finance_cheque.finance_cheque', [
        'data' => $deposits,
        'adms' => $adms,
        'customers' => $customers,
        'filters' => $request->all(),
    ]);
}


    public function show($id)
    {
        $deposit = Deposits::findOrFail($id);
        $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();

        $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)
            ->with(['invoice.customer'])
            ->paginate(10);

        $status = $deposit->status;
        // if ($status === 'pending') $status = 'deposited';

        $depositData = [
            'id' => $deposit->id,
            'adm_name' => $userDetail?->name ?? 'N/A',
            'adm_number' => $userDetail?->adm_number ?? 'N/A',
            'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
            'bank_name' => $deposit->bank_name?? 'N/A',
            'branch_name' => $deposit->branch_name?? 'N/A',
            'amount' => $deposit->amount ?? 0,
            'status' => $status,
            'attachment_path' => $deposit->attachment_path ?? null,
        ];

        return view('finance_cheque.finance_cheque_details', [
            'deposit' => $depositData,
            'payments' => $invoicePayments,
        ]);
    }

    public function downloadAttachment($id)
{
    $deposit = Deposits::findOrFail($id);

    if (!$deposit->attachment_path) {
        return back()->with('fail', 'No files found for this record.');
    }

    // Decode JSON if multiple files stored
    $attachments = json_decode($deposit->attachment_path, true);
    if (!$attachments || count($attachments) === 0) {
        return back()->with('fail', 'No valid files found.');
    }

    $zipFileName = 'deposit_'.$deposit->id.'_attachments.zip';
    $zip = new \ZipArchive;
    $zipPath = public_path('temp/'.$zipFileName); // temporary location

    // Make sure temp folder exists
    if (!file_exists(public_path('temp'))) {
        mkdir(public_path('temp'), 0755, true);
    }

    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
        foreach ($attachments as $filePath) {
            $fullPath = public_path($filePath);
            if (file_exists($fullPath)) {
                // Add file to ZIP with just the filename
                $zip->addFile($fullPath, basename($fullPath));
            }
        }
        $zip->close();
    } else {
        return back()->with('fail', 'Failed to create ZIP file.');
    }

    // Return ZIP as download and delete after sending
    return response()->download($zipPath)->deleteFileAfterSend(true);
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined,over_to_finance',
        ]);

        $deposit = Deposits::findOrFail($id);
        $deposit->status = strtolower($request->status);
        $deposit->save();

        if (in_array($request->status, ['accepted', 'declined', 'over_to_finance'])) {
            $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];
            $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

            if (!empty($receiptIds)) {
               
                if(strtolower($request->status) == 'declined'){
                    InvoicePayments::whereIn('id', $receiptIds)
                    ->update(['status' => 'pending']);
                }
                else{
                     InvoicePayments::whereIn('id', $receiptIds)
                    ->update(['status' => strtolower($request->status)]);
                }
            }
        }

        ActivitLogService::log('deposit', 'deposit ('.$id.') status has been changed to '.$request->status);
        SystemNotificationService::log('deposit',$id , 'Your deposit('.$id.') status has been changed to '.$request->status, $deposit->adm_id);
        
        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $deposits = Deposits::where('type', 'finance-cheque')
            ->orderByDesc('created_at')
            ->get();

        $filtered = $deposits->filter(function ($deposit) use ($search) {
            $adm = UserDetails::where('user_id', $deposit->adm_id)->first();
            $admMatch = false;

            if ($adm) {
                $admMatch = str_contains(strtolower($adm->name), strtolower($search)) ||
                    str_contains(strtolower($adm->adm_number), strtolower($search));
            }

            $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];
            $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();
            $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->get();

            $customerMatch = false;
            foreach ($invoicePayments as $payment) {
                $invoice = Invoices::find($payment->invoice_id);
                $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

                if ($customer && (
                    str_contains(strtolower($customer->name), strtolower($search)) ||
                    str_contains(strtolower($customer->customer_id), strtolower($search))
                )) {
                    $customerMatch = true;
                    break;
                }
            }

            return $admMatch || $customerMatch;
        });

        $page = request('page', 1);
        $perPage = 10;
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $paginated->getCollection()->transform(function ($deposit) {
            $adm = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = $deposit->status;
            // if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $adm?->adm_number ?? 'N/A',
                'adm_name' => $adm?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount ?? 0,
                'status' => $status,
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = ['search' => $search];

        return view('finance_cheque.finance_cheque', [
            'data' => $paginated,
            'filters' => $filters,
        ]);
    }

    public function filter(Request $request)
    {
        $query = Deposits::where('type', 'finance-cheque');

        if ($request->filled('adm_names')) {
            $admIds = UserDetails::whereIn('name', $request->adm_names)->pluck('user_id');
            $query->whereIn('adm_id', $admIds);
        }

        if ($request->filled('adm_ids')) {
            $admIds = UserDetails::whereIn('adm_number', $request->adm_ids)->pluck('user_id');
            $query->whereIn('adm_id', $admIds);
        }

        if ($request->filled('customers')) {
            $query->get()->filter(function ($deposit) use ($request) {
                $decoded = json_decode($deposit->reciepts, true) ?? [];
                $receiptIds = collect($decoded)->pluck('reciept_id')->toArray();
                $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->get();

                foreach ($invoicePayments as $payment) {
                    $invoice = Invoices::find($payment->invoice_id);
                    $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

                    if ($customer && in_array($customer->name, $request->customers)) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($request->filled('date_range')) {
            $range = trim($request->date_range);
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range));
            } else {
                $start = $end = $range;
            }

            if (!empty($start) && !empty($end)) {
                $query->whereBetween('date_time', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', ucfirst(strtolower($request->status)));
        }

        $deposits = $query->orderByDesc('created_at')->paginate(10);

        $deposits->getCollection()->transform(function ($deposit) {
            $adm = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = $deposit->status;
            // if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $adm?->adm_number ?? 'N/A',
                'adm_name' => $adm?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount ?? 0,
                'status' => $status,
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = $request->all();

        return view('finance_cheque.finance_cheque', [
            'data' => $deposits,
            'filters' => $filters,
        ]);
    }

    public function export(Request $request)
    {
        $query = Deposits::where('type', 'finance-cheque');

        // Apply the same filters as in filter() method
        if ($request->filled('adm_names')) {
            $admIds = UserDetails::whereIn('name', $request->adm_names)->pluck('user_id');
            $query->whereIn('adm_id', $admIds);
        }

        if ($request->filled('adm_ids')) {
            $admIds = UserDetails::whereIn('adm_number', $request->adm_ids)->pluck('user_id');
            $query->whereIn('adm_id', $admIds);
        }

        if ($request->filled('customers')) {
            $query->get()->filter(function ($deposit) use ($request) {
                $decoded = json_decode($deposit->reciepts, true) ?? [];
                $receiptIds = collect($decoded)->pluck('reciept_id')->toArray();
                $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->get();

                foreach ($invoicePayments as $payment) {
                    $invoice = Invoices::find($payment->invoice_id);
                    $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

                    if ($customer && in_array($customer->name, $request->customers)) {
                        return true;
                    }
                }
                return false;
            });
        }

        if ($request->filled('date_range')) {
            $range = trim($request->date_range);
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range));
            } else {
                $start = $end = $range;
            }

            if (!empty($start) && !empty($end)) {
                $query->whereBetween('date_time', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', ucfirst(strtolower($request->status)));
        }

        $deposits = $query->get();

        // Transform data
        $dataArray = $deposits->map(function ($deposit) {
            $adm = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = $deposit->status;
            // if ($status === 'pending') $status = 'deposited';

            return [
                'Date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'ADM Number' => $adm?->adm_number ?? 'N/A',
                'ADM Name' => $adm?->name ?? 'N/A',
                'Bank Name' => $deposit->bank_name,
                'Branch Name' => $deposit->branch_name,
                'Cheque No' => $deposit->id,
                'Amount' => $deposit->amount ?? 0,
                'Status' => $status,
            ];
        })->toArray();

        $headers = ['Date', 'ADM Number', 'ADM Name', 'Bank Name', 'Branch Name', 'Cheque No', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($dataArray, $headers), 'finance_cheque.xlsx');
    }
}
