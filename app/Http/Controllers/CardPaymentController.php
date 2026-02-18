<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\User;
use App\Models\Customers;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;
use App\Services\ActivitLogService;
use App\Services\SystemNotificationService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Services\MobitelInstantSmsService;
use Illuminate\Support\Facades\Log;
class CardPaymentController extends Controller
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
        $query = Deposits::where('type', 'card');

        // Apply ADM Name filter
        if ($request->filled('adm_names')) {
            $query->whereIn('adm_id', $request->adm_names);
        }

        // Apply ADM Number filter
        if ($request->filled('adm_ids')) {
            // Convert adm_number → user_id
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
                ->pluck('user_id')
                ->toArray();

            $query->whereIn('adm_id', $admUserIds);
        }

        // Apply Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply Date Range filter
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

        if ($request->filled('search')) {
            $search = $request->search;

            // Find matching ADM IDs
            $admIds = UserDetails::where('name', 'like', "%$search%")
                ->orWhere('adm_number', 'like', "%$search%")
                ->pluck('user_id')
                ->toArray();

            $query->whereIn('adm_id', $admIds);
        }

        // Apply Customer filter
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

        // Paginate results
        $cardPayments = $query->orderByDesc('created_at')->paginate(10);

        // Transform for view
        $cardPayments->getCollection()->transform(function ($deposit) {
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

        $filters = $request->all();

        return view('card_payment.card_payments', compact('cardPayments', 'filters', 'adms', 'customers'));
    }

    public function show($id)
    {
        // Get deposit record
        $deposit = Deposits::findOrFail($id);

        // Get ADM details
        $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

        // Decode receipts JSON properly
       $decodedReceipts = $deposit->reciepts;

        // Extract receipt IDs safely
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        // Fetch all matching invoice payments
        $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->with('invoice.customer')->paginate(10);
        // dd($decodedReceipts);
        // Define display info
        $admName = $admDetails->name ?? 'N/A';
        $admNumber = $admDetails->adm_number ?? 'N/A';
        $depositDate = $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A';
        $totalAmount = $deposit->amount ?? 0;
        $status = $deposit->status;
    
        // Reuse the view or create a new one? usage suggests 'card_payment.card_payment_details'
        // But the data passed here matches 'cash_deposits.payment_slip' structure.
        // I should check if I need to update 'card_payment_details' to accept these variables.
        // For now, I will pass the variables to 'card_payment.card_payment_details' and then I will update that view.
        
        return view('card_payment.card_payment_details', compact(
            'deposit',
            'admName',
            'admNumber',
            'depositDate',
            'totalAmount',
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

        // It might be a single file string in legacy card payments or a JSON array in new deposits
        // CashDepositsController assumes JSON. I'll handle both just in case, but prioritize JSON as per CashDeposits logic.
        $attachments = json_decode($deposit->attachment_path, true);
        
        // If not valid JSON, it might be a direct string path (legacy support if needed, but CashDeposits uses array)
        if (!is_array($attachments)) {
             if (is_string($deposit->attachment_path) && file_exists(public_path($deposit->attachment_path))) {
                 return response()->download(public_path($deposit->attachment_path));
             }
             return back()->with('fail', 'No valid files found.');
        }

        if (count($attachments) === 0) {
            return back()->with('fail', 'No valid files found.');
        }

        $zipFileName = 'deposit_'.$deposit->id.'_attachments.zip';
        $zip = new \ZipArchive;
        $zipPath = public_path('temp/'.$zipFileName);

        if (!file_exists(public_path('temp'))) {
            mkdir(public_path('temp'), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($attachments as $filePath) {
                $fullPath = public_path($filePath);
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, basename($fullPath));
                }
            }
            $zip->close();
        } else {
            return back()->with('fail', 'Failed to create ZIP file.');
        }

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

                $toNumber = preg_replace('/^0/', '94', $deposit->adm->userDetails->phone_number ?? '');
                if ($toNumber) {
                    $smsMessage  = "Your deposit has been rejected.\n";
                    $smsMessage .= "Deposit ID: {$deposit->id}.\n";
                    $smsMessage .= "Deposit Type: {$deposit->type}.\n";
                    $smsMessage .= "Deposit Amount: {$deposit->amount}.\n";
                    $smsMessage .= "Reason: {$request->remark}";

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
                                . $deposit->id . ': ' . $e->getMessage()
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

        $cashDeposits = Deposits::where('type', 'card')
            ->orderByDesc('created_at')
            ->get();

        $filtered = $cashDeposits->filter(function ($deposit) use ($search) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            $admMatch = false;
            if ($admDetails) {
                $admMatch = str_contains(strtolower($admDetails->name), strtolower($search)) ||
                    str_contains(strtolower($admDetails->adm_number), strtolower($search));
            }

            $decodedReceipts = $deposit->reciepts ?? [];
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
        $cardPayments = new \Illuminate\Pagination\LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $cardPayments->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A',
                'adm_name' => $admDetails->name ?? 'N/A',
                'amount' => $deposit->amount ?? 0,
                'status' =>  $deposit->status,
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = ['search' => $search];

        return view('card_payment.card_payments', compact('cardPayments', 'filters', 'adms', 'customers'));
    }

     public function filter(Request $request)
    {
        $query = Deposits::where('type', 'card');

        if ($request->filled('adm_names')) {
            $admUserIds = UserDetails::whereIn('name', $request->adm_names)
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('adm_ids')) {
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
                ->pluck('user_id')
                ->toArray();
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

        $cardPayments = $query->orderByDesc('created_at')->paginate(10);

        $cardPayments->getCollection()->transform(function ($deposit) {
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

        $filters = $request->all();

        return view('card_payment.card_payments', compact('cardPayments', 'filters', 'adms', 'customers'));
    }

    public function export(Request $request)
    {
        $query = Deposits::where('type', 'card');

        if ($request->filled('adm_names')) {
            $admUserIds = UserDetails::whereIn('name', $request->adm_names)
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('adm_ids')) {
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)
                ->pluck('user_id')
                ->toArray();
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
            $query->where('status', ucfirst(strtolower($request->status)));
        }

        $data = $query->get()->map(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            return [
                'Date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'ADM Number' => $admDetails->adm_number ?? 'N/A',
                'ADM Name' => $admDetails->name ?? 'N/A',
                'Amount' => $deposit->amount ?? 0,
                'Status' => $deposit->status
            ];
        })->toArray();

        $headers = ['Date', 'ADM Number', 'ADM Name', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($data, $headers), 'card_payments.xlsx');
    }
}
