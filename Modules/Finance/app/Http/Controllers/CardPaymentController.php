<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\UserDetails;
use App\Models\Deposits;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class CardPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = InvoicePayments::with([
            'invoice.customer.userDetail' // Keep relation for ADM Name
        ])->where('type', 'card'); // Only card payments

        // ----------------------
        // SEARCH
        // ----------------------
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
                $q->orWhereHas('invoice.customer.userDetail', function ($adm) use ($search) {
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
            $query->whereHas('invoice.customer.userDetail', function ($q) use ($request) {
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
                $query->whereBetween('card_transfer_date', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        // ----------------------
        // PAGINATION
        // ----------------------
        $cardPayments = $query->orderBy('card_transfer_date', 'desc')->paginate(10);
        $filters = $request->all();

        return view('finance::card_payment.card_payments', compact('cardPayments', 'filters'));
    }

    public function show($id)
    {
        // Fetch single card payment with related invoice, customer, and user detail
        $cardPayment = InvoicePayments::with([
            'invoice.customer.userDetail'  // invoice -> customer -> user_detail
        ])->findOrFail($id);

        return view('finance::card_payment.card_payment_details', compact('cardPayment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $payment = InvoicePayments::with(['invoice.customer.userDetail'])->findOrFail($id);

        $newStatus = strtolower($request->input('status'));

        if (!in_array($newStatus, ['approved', 'rejected'])) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
        }

        DB::beginTransaction();

        try {
            // Update payment status
            $payment->status = $newStatus;
            $payment->save();

            // Check for existing deposit
            $deposit = Deposits::whereJsonContains('reciepts', [['reciept_id' => (string)$payment->id]])->first();

            if (!$deposit) {
                $deposit = new Deposits();
                $deposit->type = 'card';
                $deposit->date_time = $payment->card_transfer_date;
                $deposit->amount = $payment->final_payment;
                $deposit->reciepts = [
                    ['reciept_id' => (string)$payment->id]
                ];
                $deposit->adm_id = $payment->invoice->customer->userDetail->id ?? null;
                $deposit->status = $newStatus;
                $deposit->attachment_path = $payment->screenshot;
            } else {
                $deposit->status = $newStatus;
            }

            $deposit->save();

            DB::commit();

            return response()->json(['success' => true, 'status' => ucfirst($payment->status)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function export(Request $request)
    {
        $query = InvoicePayments::with([
            'invoice.customer.userDetail'
        ])->where('type', 'card');

        // --- SAME FILTERS AS INDEX() ---
        if ($request->search) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('invoice.customer', function ($customer) use ($search) {
                    $customer->where('customer_id', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('adm', 'like', "%$search%");
                });

                $q->orWhereHas('invoice.customer.userDetail', function ($adm) use ($search) {
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
            $query->whereHas('invoice.customer.userDetail', function ($q) use ($request) {
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
                $query->whereBetween('card_transfer_date', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        if ($request->filled('status')) {
            $status = strtolower($request->status);
            $query->whereRaw('LOWER(status) = ?', [$status]);
        }

        // Get all filtered results
        $payments = $query->get();

        // Prepare Excel data
        $data = $payments->map(function ($p) {
            return [
                'Date'      => $p->card_transfer_date ?? '-',
                'ADM ID'    => $p->invoice->customer->adm ?? '-',
                'ADM Name'  => $p->invoice->customer->userDetail->name ?? '-',
                'Amount'    => $p->final_payment,
                'Status'    => ucfirst($p->status),
            ];
        })->toArray();

        $headers = ['Date', 'ADM ID', 'ADM Name', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($data, $headers), 'card_payments.xlsx');
    }
}
