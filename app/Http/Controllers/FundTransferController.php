<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvoicePayments;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\Customers;
use App\Models\User;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;
use App\Services\ActivitLogService;
use App\Services\SystemNotificationService;
use App\Services\MobitelInstantSmsService;
use Illuminate\Support\Facades\Log;
class FundTransferController extends Controller
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
        $query = Deposits::where('type', 'fund-transfer');

        // Apply ADM Name filter
        if ($request->filled('adm_names')) {
            $query->whereIn('adm_id', $request->adm_names);
        }

        // Apply ADM Number filter
        if ($request->filled('adm_ids')) {
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

        // Apply Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            // Find matching ADM IDs
            $admIds = UserDetails::where('name', 'like', "%$search%")
                ->orWhere('adm_number', 'like', "%$search%")
                ->pluck('user_id')
                ->toArray();
            
            $query->where(function($q) use ($admIds, $search) {
                $q->whereIn('adm_id', $admIds);
                 // Check Customer (through receipts → invoice_payments → invoices → customers)
                 // This part is complex for a direct query, so we might need to filter after fetch if we want to search customer names thoroughly via relation, 
                 // OR we can use whereHas if we set up the relationships correctly in the model. 
                 // Since Deposits has 'reciepts' as JSON, we can't easily SQL join. 
                 // CashDepositsController does this by filtering the collection.
                 // However, for the initial query, we can't easily do it. 
                 // Let's stick to CashDepositsController approach: fetch then filter OR simple ADM search first.
                 // CashDepositsController actually does filtering on the COLLECTION in 'search' method but on QUERY in 'index' for ADM only.
                 // Let's follow CashDepositsController's 'index' which only filters ADM by default on search? 
                 // Actually CashDepositsController 'index' handles 'search' by just ADM IDs.
            });
             if(!empty($admIds)){
                 $query->orWhereIn('adm_id', $admIds);
             }
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
        
        // Let's structure it like CashDepositsController's `index`.
        
        $fundTransfers = $query->orderByDesc('created_at')->paginate(10);

        // Transform collection
        $fundTransfers->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
            
            // Need to find the transfer reference number from the receipt
            // Since it's a fund transfer, it likely has one receipt?
            $receipts = $deposit->reciepts ?? []; 
            // Handle if string
            if(is_string($receipts)) $receipts = json_decode($receipts, true);
            
            $firstReceiptId = $receipts[0]['reciept_id'] ?? null;
            $transferRef = '-';
            
            if($firstReceiptId){
                 $invPayment = InvoicePayments::find($firstReceiptId);
                 if($invPayment) {
                     $transferRef = $invPayment->transfer_reference_number;
                 }
            }

            return [
                'id' => $deposit->id,
                'transfer_date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A', // For view to display ADM Number
                'adm_name' => $admDetails->name ?? 'N/A', // For view
                'transfer_reference_number' => $transferRef,
                'final_payment' => $deposit->amount ?? 0,
                'status' => $deposit->status, // Raw status
                'invoice' => (object)['customer' => (object)['adm' => $admDetails->adm_number ?? '-', 'admDetails' => $admDetails]], // Mocking structure if view uses it, but I plan to change view.
            ];
        });

        $filters = $request->all();
        return view('fund_transfer.fund_transfers', compact('fundTransfers', 'filters', 'customers', 'adms'));
    }

    public function show($id)
    {
        $deposit = Deposits::findOrFail($id);
        
        $receipts = $deposit->reciepts ?? [];
        if(is_string($receipts)) $receipts = json_decode($receipts, true);
        
        $payment = null;
        if(!empty($receipts) && isset($receipts[0]['reciept_id'])){
             $payment = InvoicePayments::with(['invoice.customer.admDetails'])->find($receipts[0]['reciept_id']);
        }
        
        return view('fund_transfer.fund_transfer_details', compact('deposit', 'payment'));
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
        } elseif ($request->status === 'approved') {
            $deposit->final_approve_date = now();
        }

        $deposit->save();

        // Update related receipts (InvoicePayments)
        $receiptIds = collect($deposit->reciepts ?? [])->pluck('reciept_id')->toArray();
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
        SystemNotificationService::log('deposit', $id, "Your deposit($id) status changed to {$request->status}", $deposit->adm_id);

        return response()->json(['success' => true, 'status' => ucfirst($request->status)]);
    }

    public function export(Request $request)
    {
        $query = Deposits::where('type', 'fund-transfer');

        // Apply filters (SAME AS INDEX)
         if ($request->filled('adm_names')) {
            $query->whereIn('adm_id', $request->adm_names);
        }

        if ($request->filled('adm_ids')) {
            $admUserIds = UserDetails::whereIn('adm_number', $request->adm_ids)->pluck('user_id')->toArray();
            $query->whereIn('adm_id', $admUserIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $data = $query->get()->map(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();
             $receipts = $deposit->reciepts ?? [];
             if(is_string($receipts)) $receipts = json_decode($receipts, true);
             $firstReceiptId = $receipts[0]['reciept_id'] ?? null;
             $transferRef = '-';
             if($firstReceiptId){
                 $invPayment = InvoicePayments::find($firstReceiptId);
                 if($invPayment) $transferRef = $invPayment->transfer_reference_number;
             }
            
            return [
                'Date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'ADM Number' => $admDetails->adm_number ?? '-',
                'ADM Name' => $admDetails->name ?? '-',
                'Transfer Ref. No.' => $transferRef,
                'Amount' => number_format($deposit->amount, 2),
                'Status' => ucfirst($deposit->status),
            ];
        })->toArray();

        $headers = ['Date', 'ADM Number', 'ADM Name', 'Transfer Ref. No.', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($data, $headers), 'fund_transfers.xlsx');
    }
}
