<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvoicePayments;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\Customers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;
use App\Services\ActivitLogService;
use App\Services\SystemNotificationService;

class FundTransferController extends Controller
{
    public function index(Request $request)
    {
        $adms = User::where('user_role', 6)->with('userDetails')->get();
        $customers = Customers::where('is_temp', 0)->get();
        $query = InvoicePayments::with([
            'invoice.customer.admDetails'
        ])
            ->where('type', 'fund-transfer');

        if ($request->search) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                // Search Customer (ID / Name / ADM ID)
                $q->whereHas('invoice.customer', function ($customer) use ($search) {
                    $customer->where('customer_id', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('adm', 'like', "%$search%");
                });

                // Search ADM Name
                $q->orWhereHas('invoice.customer.admDetails', function ($adm) use ($search) {
                    $adm->where('name', 'like', "%$search%");
                });
            });
        }

        // ---------------------- 
        // APPLY FILTERS
        // ----------------------
        if ($request->filled('adm_ids')) {
            $query->whereHas('invoice.customer', function ($q) use ($request) {
                $q->whereIn('adm', $request->adm_ids);
            });
        }

       if ($request->filled('adm_names')) {
            $query->whereHas('invoice.customer.admDetails', function ($q) use ($request) {
                $q->whereIn('user_id', $request->adm_names);
            });
        }

        if ($request->filled('customers')) {
            $query->whereHas('invoice.customer', function ($q) use ($request) {
                $q->whereIn('customer_id', $request->customers);
            });
        }


        if ($request->filled('date_range')) {
            // Normalize the input format
            $range = trim($request->date_range);

            // Support both "YYYY-MM-DD to YYYY-MM-DD" and "YYYY-MM-DD - YYYY-MM-DD"
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range));
            } else {
                $start = $end = $range;
            }

            // Make sure both dates are valid
            if (!empty($start) && !empty($end)) {
                $query->whereBetween('transfer_date', [ // <-- FIXED: removed extra backtick
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        $fundTransfers = $query->paginate(10);
        $filters = $request->all();

        return view('fund_transfer.fund_transfers', compact('fundTransfers', 'filters', 'customers', 'adms'));
    }

    public function show($id)
    {
        $deposit = InvoicePayments::with([
            'invoice.customer.admDetails'
        ])->findOrFail($id);

        return view('fund_transfer.fund_transfer_details', compact('deposit'));
    }

    public function updateStatus(Request $request, $id)
    {
        $payment = InvoicePayments::with(['invoice.customer.admDetails'])->findOrFail($id);

        $newStatus = strtolower($request->input('status'));

        if (!in_array($newStatus, ['approved', 'rejected'])) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }

        // Begin transaction to ensure both updates succeed
        DB::beginTransaction();

        try {
            // Update the status in invoice_payments
            if($newStatus == 'rejected'){
                $payment->status = 'pending';
            }
            else{
                $payment->status = $newStatus;
            }
            $payment->save();

            // Check if deposit already exists for this payment
            $deposit = Deposits::whereJsonContains('reciepts', [['reciept_id' => (string)$payment->id]])->first();


            // If deposit doesn't exist, create a new one
            if (!$deposit) {
                $deposit = new Deposits();
                $deposit->type = 'fund-transfer';
                $deposit->date_time = $payment->transfer_date;
                $deposit->amount = $payment->final_payment;
                $deposit->reciepts = [
                    ['reciept_id' => (string)$payment->id]
                ];
                $deposit->adm_id = $payment->invoice->customer->admDetails->id ?? null;
                $deposit->status = $newStatus;
                $deposit->attachment_path = $payment->screenshot;
            } else {
                $deposit->status = $newStatus;
            }

            $deposit->save();

            DB::commit();

            ActivitLogService::log('deposit', 'deposit ('.$deposit->id.') status has been changed to '.$newStatus);
            SystemNotificationService::log('deposit',$deposit->id , 'Your deposit('.$deposit->id.') status has been changed to '.$newStatus, $deposit->adm_id);

            return response()->json(['success' => true, 'status' => ucfirst($payment->status)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function export(Request $request)
    {
        $query = InvoicePayments::with([
            'invoice.customer.admDetails'
        ])
            ->where('type', 'fund-transfer');

        // --- SAME FILTERS AS INDEX ---
        if ($request->search) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('invoice.customer', function ($customer) use ($search) {
                    $customer->where('customer_id', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('adm', 'like', "%$search%");
                });

                $q->orWhereHas('invoice.customer.admDetails', function ($adm) use ($search) {
                    $adm->where('name', 'like', "%$search%");
                });
            });
        }

        if ($request->filled('adm_ids')) {
            $query->whereHas('invoice.customer', function ($q) use ($request) {
                $q->whereIn('adm', $request->adm_ids);
            });
        }

        if ($request->filled('adm_names')) {
            $query->whereHas('invoice.customer.admDetails', function ($q) use ($request) {
                $q->whereIn('name', $request->adm_names);
            });
        }

        if ($request->filled('customers')) {
            $query->whereHas('invoice.customer', function ($q) use ($request) {
                $q->whereIn('name', $request->customers);
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
                $query->whereBetween('transfer_date', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        $data = $query->get()->map(function ($deposit) {
            return [
                'Date' => $deposit->transfer_date,
                'ADM Number' => $deposit->invoice->customer->adm ?? '-',
                'ADM Name' => $deposit->invoice->customer->admDetails->name ?? '-',
                'Transfer Ref. No.' => $deposit->transfer_reference_number,
                'Amount' => number_format($deposit->final_payment, 2),
                'Status' => ucfirst($deposit->status),
            ];
        })->toArray();

        // Build Excel data
        $headers = [
            'Date',
            'ADM Number',
            'ADM Name',
            'Transfer Ref. No.',
            'Amount',
            'Status'
        ];

        return Excel::download(new ArrayExport($data, $headers), 'fund_transfers.xlsx');
    }
}
