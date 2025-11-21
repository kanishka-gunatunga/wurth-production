<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;

class FinanceCashController extends Controller
{
    public function index(Request $request)
    {
        // Fetch only finance_cash deposits
        $financeCashDeposits = Deposits::where('type', 'finance_cash')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Transform the results
        $financeCashDeposits->getCollection()->transform(function ($deposit) {
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') {
                $status = 'deposited';
            }

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A',
                'adm_name' => $admDetails->name ?? 'N/A',
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        return view('finance::finance_cash.finance_cash', compact('financeCashDeposits'));
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

        $status = strtolower($deposit->status ?? '');
        if ($status === 'pending') {
            $status = 'deposited';
        }
        $status = ucfirst($status ?: 'Deposited');

        return view('finance::finance_cash.payment_slip', compact(
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

        if (!$deposit->attachment_path || !file_exists(public_path($deposit->attachment_path))) {
            return back()->with('error', 'No file found for this record.');
        }

        return response()->download(public_path($deposit->attachment_path));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->merge(['status' => ucfirst(strtolower($request->status))]);

        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $deposit = Deposits::findOrFail($id);

        $deposit->status = $request->status;
        $deposit->save();

        $receiptIds = collect($deposit->reciepts ?? [])
            ->pluck('reciept_id')
            ->toArray();

        if (!empty($receiptIds)) {
            InvoicePayments::whereIn('id', $receiptIds)
                ->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'status' => $request->status,
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $financeCashDeposits = Deposits::where('type', 'finance_cash')
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
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A',
                'adm_name' => $admDetails->name ?? 'N/A',
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = ['search' => $search];

        return view('finance::finance_cash.finance_cash', compact('financeCashDeposits', 'filters'));
    }

    public function filter(Request $request)
    {
        $query = Deposits::where('type', 'finance_cash');

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
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $admDetails->adm_number ?? 'N/A',
                'adm_name' => $admDetails->name ?? 'N/A',
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = $request->all();

        return view('finance::finance_cash.finance_cash', compact('financeCashDeposits', 'filters'));
    }
}
