<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use Illuminate\Support\Facades\Storage;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ActivitLogService;
use App\Services\SystemNotificationService;
use App\Models\Invoices;
use App\Models\User;
use App\Models\Customers;
use Illuminate\Support\Facades\Response;
use App\Services\MobitelInstantSmsService;
use Illuminate\Support\Facades\Log;
class ChequeDepositsController extends Controller
{
    protected $smsService;

    public function __construct(MobitelInstantSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show the cheque deposits page with data.
     */ 
    public function index(Request $request)
{
    $adms = User::where('user_role', 6)->with('userDetails')->get();
    $customers = Customers::where('is_temp', 0)->get();

    // base query
    $query = Deposits::where('type', 'cheque');

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

    if ($request->filled('adm_names')) {
        $query->whereIn('adm_id', $request->adm_names);
    }

    if ($request->filled('adm_ids')) {
        $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
            ->pluck('user_id');

        $query->whereIn('adm_id', $admUserIds);
    }

   if ($request->filled('customers')) {
             $query->get()->filter(function ($deposit) use ($request) {
                $decodedReceipts = $deposit->reciepts ?? [];
                $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();
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
        $query->where('status', $request->status);
    }

    $deposits = $query->orderByDesc('created_at')->paginate(10);

    $deposits->getCollection()->transform(function ($deposit) {
        $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();

        return [
            'id' => $deposit->id,
            'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
            'adm_number' => $userDetail?->adm_number ?? 'N/A',
            'adm_name' => $userDetail?->name ?? 'N/A',
            'bank_name' => $deposit->bank_name ?? 'N/A',
            'branch_name' => $deposit->branch_name ?? 'N/A',
            'amount' => $deposit->amount ?? 0,
            'status' => $deposit->status,
            'attachment_path' => $deposit->attachment_path ?? null,
        ];
    });

    return view('cheque_deposits.cheque_deposits', [
        'data' => $deposits,
        'adms' => $adms,
        'customers' => $customers,
        'filters' => $request->all(),
    ]);
}

    /**
     * Show a single cheque deposit details.
     */
    public function show($id)
    {
        $deposit = Deposits::findOrFail($id);
        $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();

        // ✅ Decode the JSON field properly (correct key: reciepts)
        $decodedReceipts = $deposit->reciepts ?? [];

        // ✅ Extract reciept IDs safely
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        // ✅ Fetch related invoice payments
        $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)
            ->with(['invoice.customer'])
            ->paginate(10); 

        // ✅ Convert pending → Deposited in show page too
        // $status = strtolower($deposit->status ?? '');
        // if ($status === 'pending') {
        //     $status = 'deposited';
        // }

        $depositData = [
            'id' => $deposit->id,
            'adm_name' => $userDetail?->name ?? 'N/A',
            'adm_number' => $userDetail?->adm_number ?? 'N/A',
            'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
            'bank_name' => $deposit->bank_name,
            'branch_name' => $deposit->branch_name,
            'amount' => $deposit->amount ?? 0,
            'status' => $deposit->status,
            'attachment_path' => $deposit->attachment_path ?? null,
        ];

        return view('cheque_deposits.cheque_deposit_details', [
            'deposit' => $depositData,
            'payments' => $invoicePayments,
        ]);
    }

    /**
     * Download attachment file if available.
     */
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
        'status' => 'required|in:approved,rejected',
        'remark' => 'nullable|string|max:500'
    ]);

    $deposit = Deposits::with('adm.userDetails')->findOrFail($id);

    $deposit->status = $request->status;

    if ($request->status === 'rejected') {
        $deposit->remarks = $request->remark;
        $deposit->declined_date = now();
    }

    if ($request->status === 'approved') {
        $deposit->final_approve_date = now();
    }

    $deposit->save();

    // Update related receipts
    $receiptIds = collect($deposit->reciepts ?? [])
        ->pluck('reciept_id')
        ->toArray();

    if (!empty($receiptIds)) {
        if ($request->status === 'rejected') {
            InvoicePayments::whereIn('id', $receiptIds)
                ->update(['status' => 'pending']);
                $toNumber = preg_replace('/^0/','94',$deposit->adm->userDetails->phone_number ?? '');
                if ($toNumber) {
                    $smsMessage  = "Your deposit has been rejected.\n";
                    $smsMessage .= "Deposit ID: {$deposit->id}.\n";
                    $smsMessage .= "Deposit Type: {$deposit->type}.\n";
                    $smsMessage .= "Deposit Amount: {$deposit->amount}.\n";
                    $smsMessage .= "Reason: {$request->remark}.\n";
                    
                    try {
                        $this->smsService->sendInstantSms(
                            [(string) $toNumber],
                            $smsMessage,
                            "Deposit"
                        );

                        Log::info(
                            'SMS sent to ' . $toNumber
                            . ' (Deposit ID: ' . $deposit->id . ')'
                        );

                    } catch (\Exception $e) {
                        Log::error(
                            'SMS sending failed for Deposit ID '
                            . $payment->id . ': ' . $e->getMessage()
                        );
                    }
                }
                
        } else {
            InvoicePayments::whereIn('id', $receiptIds)
                ->update(['status' => 'approved']);
        }
    }

    ActivitLogService::log('deposit', "Deposit ($id) status changed to {$request->status}");
    SystemNotificationService::log(
        'deposit',
        $id,
        "Your deposit($id) status changed to {$request->status}",
        $deposit->adm_id
    );

    return response()->json([
        'success' => true,
        'status' => $request->status
    ]);
}

    public function search(Request $request)
    {
        $search = $request->input('search');

        // Get all cheque deposits
        $deposits = Deposits::where('type', 'cheque')
            ->orderByDesc('created_at')
            ->get();

        // Filter manually
        $filtered = $deposits->filter(function ($deposit) use ($search) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $admMatch = false;

            if ($admDetails) {
                $admMatch = str_contains(strtolower($admDetails->name), strtolower($search)) ||
                    str_contains(strtolower($admDetails->adm_number), strtolower($search));
            }

            // Check Customer (through receipts → invoice_payments → invoices → customers)
            $decodedReceipts = $deposit->reciepts ?? [];
            $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();
            $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->get();

            $customerMatch = false;
            foreach ($invoicePayments as $payment) {
                $invoice = $payment->invoice ?? null; // if relationship not defined, fallback to find
                if (!$invoice) {
                    $invoice = \App\Models\Invoices::find($payment->invoice_id);
                }

                $customer = $invoice ? $invoice->customer ?? \App\Models\Customers::where('customer_id', $invoice->customer_id)->first() : null;

                if ($customer && (str_contains(strtolower($customer->name), strtolower($search)) ||
                    str_contains(strtolower($customer->customer_id), strtolower($search)))) {
                    $customerMatch = true;
                    break;
                }
            }

            return $admMatch || $customerMatch;
        });

        // Paginate manually
        $page = request('page', 1);
        $perPage = 10;
        $deposits = new \Illuminate\Pagination\LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Transform for view
        $deposits->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails?->adm_number ?? 'N/A',
                'adm_name' => $admDetails?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = ['search' => $search];

        return view('cheque_deposits.cheque_deposits', [
            'data' => $deposits,
            'filters' => $filters
        ]);
    }

    public function filter(Request $request)
    {
        $query = Deposits::where('type', 'cheque');

        // ADM Names
        if ($request->filled('adm_names')) {
            $admUserIds = UserDetails::whereIn('name', $request->adm_names)
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        // ADM Numbers
        if ($request->filled('adm_ids')) {
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        // Customers
        if ($request->filled('customers')) {
            $query->get()->filter(function ($deposit) use ($request) {
                $decodedReceipts = $deposit->reciepts ?? [];
                $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();
                $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->get();

                foreach ($invoicePayments as $payment) {
                    $invoice = $payment->invoice ?? null;
                    if (!$invoice) $invoice = \App\Models\Invoices::find($payment->invoice_id);
                    $customer = $invoice ? $invoice->customer ?? \App\Models\Customers::where('customer_id', $invoice->customer_id)->first() : null;

                    if ($customer && in_array($customer->name, $request->customers)) {
                        return true;
                    }
                }

                return false;
            });
        }

        // Date range
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

        // Status
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }

        $deposits = $query->orderByDesc('created_at')->paginate(10);

        // Transform for view
        $deposits->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails?->adm_number ?? 'N/A',
                'adm_name' => $admDetails?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        return view('cheque_deposits.cheque_deposits', [
            'data' => $deposits,
            'filters' => $request->all()
        ]);
    }

    public function export(Request $request)
    {
        $query = Deposits::where('type', 'cheque');

        // Apply filters same as in filter() method
        if ($request->filled('adm_names')) {
            $admUserIds = UserDetails::whereIn('name', $request->adm_names)->pluck('user_id')->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('adm_ids')) {
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)->pluck('user_id')->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }

        if ($request->filled('date_range')) {
            $range = trim($request->date_range);
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } else {
                $start = $end = $range;
            }
            $query->whereBetween('date_time', [
                date('Y-m-d 00:00:00', strtotime($start)),
                date('Y-m-d 23:59:59', strtotime($end)),
            ]);
        }

        $deposits = $query->orderByDesc('created_at')->get();

        $data = $deposits->map(function ($deposit) {
            $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = $deposit->status;
            // if ($status === 'pending') $status = 'deposited';

            return [
                'Date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'ADM Number' => $userDetail?->adm_number ?? 'N/A',
                'ADM Name' => $userDetail?->name ?? 'N/A',
                'Bank Name' => $deposit->bank_name,
                'Branch Name' => $deposit->branch_name,
                'Amount' => $deposit->amount ?? 0,
                'Status' => $status,
            ];
        })->toArray();

        $headers = ['Date', 'ADM Number', 'ADM Name', 'Bank Name', 'Branch Name', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($data, $headers), 'cheque_deposits.xlsx');
    }
}
