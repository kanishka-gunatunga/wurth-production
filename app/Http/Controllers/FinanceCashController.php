<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;
use App\Services\ActivitLogService;
use App\Services\SystemNotificationService;
use App\Services\MobitelInstantSmsService;
use Illuminate\Support\Facades\Log;
class FinanceCashController extends Controller
{
    protected $smsService;

    public function __construct(MobitelInstantSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

   public function index(Request $request)
{
    $adms = User::where('user_role', 6)->with('userDetails')->get();
    $customers = Customers::where('is_temp', 0)->get();

    // base query - ONLY finance cash deposits
    $query = Deposits::where('type', 'finance-cash');

    /* -------------------- ADM NAME FILTER -------------------- */
    if ($request->filled('adm_names')) {
        $query->whereIn('adm_id', $request->adm_names);
    }

    /* -------------------- ADM NUMBER FILTER -------------------- */
    if ($request->filled('adm_ids')) {
        // Convert adm_number → user_id
        $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
            ->pluck('user_id')
            ->toArray();

        $query->whereIn('adm_id', $admUserIds);
    }

    /* -------------------- STATUS FILTER -------------------- */
    if ($request->filled('status')) {
        $query->where('status', $request->status);
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

    /* -------------------- GLOBAL SEARCH BOX -------------------- */
    if ($request->filled('search')) {

        $search = trim($request->search);

        // Find matching ADM IDs by name or ADM number
        $admIds = UserDetails::where('name', 'like', "%$search%")
            ->orWhere('adm_number', 'like', "%$search%")
            ->pluck('user_id')
            ->toArray();

        $query->whereIn('adm_id', $admIds);
    }

    /* -------------------- CUSTOMER FILTER -------------------- */
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

    /* -------------------- PAGINATION -------------------- */
    $financeCashDeposits = $query
        ->orderByDesc('created_at')
        ->paginate(10);

    /* -------------------- TRANSFORM FOR VIEW -------------------- */
    $financeCashDeposits->getCollection()->transform(function ($deposit) {

        $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

        return [
            'id' => $deposit->id,
            'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
            'adm_number' => $admDetails->adm_number ?? 'N/A',
            'adm_name' => $admDetails->name ?? 'N/A',
            'amount' => $deposit->amount ?? 0,
            'status' => $deposit->status,
            'attachment_path' => $deposit->attachment_path ?? null,
        ];
    });

    // keep filter values in UI
    $filters = $request->all();

    return view('finance_cash.finance_cash', compact(
        'financeCashDeposits',
        'filters',
        'adms',
        'customers'
    ));
}

    public function show($id)
    {
        $deposit = Deposits::findOrFail($id);
        $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

        $decodedReceipts = $deposit->reciepts ?? [] ?? [];
        $receiptIds = collect($deposit->reciepts ?? [])->pluck('reciept_id')->toArray();
        $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->paginate(10);

        $receiptDetails = $invoicePayments->map(function ($payment) {
            $invoice = Invoices::find($payment->invoice_id);
            $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

            return [
                'receipt_number' => $payment->id,
                'customer_name' => $customer->name ?? 'N/A',
                'customer_id' => $invoice->customer_id ?? 'N/A',
                'paid_date' => $payment->created_at ? date('Y-m-d', strtotime($payment->created_at)) : 'N/A',
                'paid_amount' => $payment->final_payment ?? 0,
            ];
        });

        $admName = $admDetails->name ?? 'N/A';
        $admNumber = $admDetails->adm_number ?? 'N/A';
        $depositDate = $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A';
        $totalAmount = $deposit->amount ?? 0;

        $status = $deposit->status;
        // if ($status === 'pending') {
        //     $status = 'deposited';
        // }
        // $status = ucfirst($status ?: 'Deposited');

        return view('finance_cash.payment_slip', compact(
            'deposit',
            'admName',
            'admNumber',
            'depositDate',
            'totalAmount',
            'receiptDetails',
            'invoicePayments',
            'status'
        ));
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
        'status' => 'required|in:approved,rejected,over_to_finance',
        'remark' => 'nullable|string|max:500'
    ]);

    // if declined → remark required
    if ($request->status === 'rejected') {
        $request->validate([
            'remark' => 'required|string|max:500'
        ]);
    }

    $deposit = Deposits::with('adm.userDetails')->findOrFail($id);

    $deposit->status = $request->status;

    // Save remark on decline
    if ($request->status === 'rejected') {
        $deposit->remarks = $request->remark;
        $deposit->declined_date = now();
    }

    // Finance first approve (over_to_finance)
    if ($request->status === 'over_to_finance') {
        $deposit->first_approve_date = now();
    }

    // Final accept
    if ($request->status === 'approved') {
        $deposit->final_approve_date = now();
    }

    $deposit->save();

    $receiptIds = collect($deposit->reciepts ?? [])
        ->pluck('reciept_id')
        ->toArray();

    if (!empty($receiptIds)) {
        if ($request->status == 'rejected') {
            DB::transaction(function () use ($receiptIds, $deposit, $request) {
                    $payments = InvoicePayments::whereIn('id', $receiptIds)->get();
                    foreach ($payments as $payment) {
                        if ($payment->status !== 'rejected') {
                            $invoice = Invoices::find($payment->invoice_id);
                            if ($invoice) {
                                $invoice->paid_amount -= $payment->amount;
                                $invoice->save();
                            }
                        }
                        $payment->status = 'pending';
                        $payment->save();
                    }
                });
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
                ->update(['status' => $request->status]);
        }
    }

    ActivitLogService::log('deposit', 'deposit (' . $id . ') status has been changed to ' . $request->status);
    SystemNotificationService::log('deposit', $id, 'Your deposit(' . $id . ') status has been changed to ' . $request->status, $deposit->adm_id);

    return response()->json([
        'success' => true,
        'status' => $request->status,
    ]);
}


    public function search(Request $request)
    {
        $search = $request->input('search');

        $financeCashDeposits = Deposits::where('type', 'finance-cash')
            ->orderByDesc('created_at')
            ->get();

        $filtered = $financeCashDeposits->filter(function ($deposit) use ($search) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $admMatch = false;
            if ($admDetails) {
                $admMatch = str_contains(strtolower($admDetails->name), strtolower($search)) ||
                    str_contains(strtolower($admDetails->adm_number), strtolower($search));
            }

            $decodedReceipts = $deposit->reciepts ?? [] ?? [];
            $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();
            $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->get();

            $customerMatch = false;
            foreach ($invoicePayments as $payment) {
                $invoice = Invoices::find($payment->invoice_id);
                $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

                if ($customer && (str_contains(strtolower($customer->name), strtolower($search)) ||
                    str_contains(strtolower($customer->customer_id), strtolower($search)))) {
                    $customerMatch = true;
                    break;
                }
            }

            return $admMatch || $customerMatch;
        });

        $page = request('page', 1);
        $perPage = 10;

        $financeCashDeposits = new \Illuminate\Pagination\LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $financeCashDeposits->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = $deposit->status;
            // if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A',
                'adm_name' => $admDetails->name ?? 'N/A',
                'amount' => $deposit->amount ?? 0,
                'status' =>$status,
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = ['search' => $search];

        return view('finance_cash.finance_cash', compact('financeCashDeposits', 'filters'));
    }

    public function filter(Request $request)
    {
        $query = Deposits::where('type', 'finance-cash');

        if ($request->filled('adm_names')) {
            $admUserIds = UserDetails::whereIn('name', $request->adm_names)
                ->pluck('user_id')->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('adm_ids')) {
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
                ->pluck('user_id')->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('customers')) {
            $query->get()->filter(function ($deposit) use ($request) {
                $decodedReceipts = $deposit->reciepts ?? [] ?? [];
                $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();
                $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->get();

                foreach ($invoicePayments as $payment) {
                    $invoice = Invoices::find($payment->invoice_id);
                    $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;
                    if ($customer && in_array($customer->name, $request->customers)) return true;
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
                    date('Y-m-d 23:59:59', strtotime($end))
                ]);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', ucfirst(strtolower($request->status)));
        }

        $financeCashDeposits = $query->orderByDesc('created_at')->paginate(10);

        $financeCashDeposits->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = $deposit->status;
            // if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A',
                'adm_name' => $admDetails->name ?? 'N/A',
                'amount' => $deposit->amount ?? 0,
                'status' => $status ,
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = $request->all();

        return view('finance_cash.finance_cash', compact('financeCashDeposits', 'filters'));
    }

    public function exportFiltered(Request $request)
    {
        $query = Deposits::where('type', 'finance-cash');

        if ($request->filled('adm_names')) {
            $admUserIds = UserDetails::whereIn('name', $request->adm_names)->pluck('user_id')->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('adm_ids')) {
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)->pluck('user_id')->toArray();
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
                    if ($customer && in_array($customer->name, $request->customers)) return true;
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
                    date('Y-m-d 23:59:59', strtotime($end))
                ]);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', ucfirst(strtolower($request->status)));
        }

        $deposits = $query->get();

        $data = $deposits->map(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $status = $deposit->status;
            // if ($status === 'pending') $status = 'deposited';

            return [
                'Date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'ADM Number' => $admDetails->adm_number ?? 'N/A',
                'ADM Name' => $admDetails->name ?? 'N/A',
                'Amount' => $deposit->amount ?? 0,
                'Status' => $status,
            ];
        })->toArray();

        $headers = ['Date', 'ADM Number', 'ADM Name', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($data, $headers), 'finance_cash.xlsx');
    }
}
