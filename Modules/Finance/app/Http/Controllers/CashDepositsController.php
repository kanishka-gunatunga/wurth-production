<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use App\Models\Invoices;
use App\Models\Customers;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrayExport;

class CashDepositsController extends Controller
{
    public function index(Request $request)
    {
        // Fetch only cash deposits
        $cashDeposits = Deposits::where('type', 'cash')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Transform the results before sending to the view
        $cashDeposits->getCollection()->transform(function ($deposit) {
            // Get ADM details from user_details table using adm_id
            $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

            // Format status
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

        return view('finance::cash_deposits.cash_deposits', compact('cashDeposits'));
    }

    public function show($id)
    {
        // Get deposit record
        $deposit = Deposits::findOrFail($id);

        // Get ADM details
        $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

        // Decode receipts JSON properly
        $decodedReceipts = $deposit->reciepts ?? [];

        // Extract receipt IDs safely
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        // Fetch all matching invoice payments
        $invoicePayments = InvoicePayments::whereIn('id', $receiptIds)->paginate(10);

        // Prepare detailed receipt data
        $receiptDetails = $invoicePayments->map(function ($payment) {
            $invoice = Invoices::find($payment->invoice_id);
            $customer = $invoice ? Customers::where('customer_id', $invoice->customer_id)->first() : null;

            return [
                'receipt_number' => $payment->id,
                'customer_name' => $customer->name ?? 'N/A',
                'customer_id' => $invoice->customer_id ?? 'N/A',
                'paid_date' => $payment->created_at
                    ? date('Y-m-d', strtotime($payment->created_at))
                    : 'N/A',
                'paid_amount' => $payment->final_payment ?? 0,
            ];
        });

        // Define display info
        $admName = $admDetails->name ?? 'N/A';
        $admNumber = $admDetails->adm_number ?? 'N/A';
        $depositDate = $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A';
        $totalAmount = $deposit->amount ?? 0;

        // 🟢 Format status
        $status = strtolower($deposit->status ?? '');
        if ($status === 'pending') {
            $status = 'deposited';
        }
        $status = ucfirst($status ?: 'Deposited');

        return view('finance::cash_deposits.payment_slip', compact(
            'deposit',
            'admName',
            'admNumber',
            'depositDate',
            'totalAmount',
            'receiptDetails',
            'invoicePayments',
            'status' // ✅ pass status to view
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
            'status' => 'required|in:approved,rejected',
        ]);


        $deposit = Deposits::findOrFail($id);

        // Update deposit status
        $deposit->status = $request->status;
        $deposit->save();

        // Update related receipts status
        $receiptIds = collect($deposit->reciepts)
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

        // Get all cash deposits
        $cashDeposits = Deposits::where('type', 'cash')
            ->orderByDesc('created_at')
            ->get();

        // Filter manually
        $filtered = $cashDeposits->filter(function ($deposit) use ($search) {
            // Check ADM
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

        // Paginate manually
        $page = request('page', 1);
        $perPage = 10;
        $cashDeposits = new \Illuminate\Pagination\LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Transform for view
        $cashDeposits->getCollection()->transform(function ($deposit) {
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

        return view('finance::cash_deposits.cash_deposits', compact('cashDeposits', 'filters'));
    }

    public function filter(Request $request)
    {
        $query = Deposits::where('type', 'cash');

        // Apply filters one by one if they are set
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
            // Filter through receipts → invoice_payments → invoices → customers
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
                $query->whereBetween('date_time', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', ucfirst(strtolower($request->status)));
        }

        $cashDeposits = $query->orderByDesc('created_at')->paginate(10);

        // Transform for the view
        $cashDeposits->getCollection()->transform(function ($deposit) {
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

        return view('finance::cash_deposits.cash_deposits', compact('cashDeposits', 'filters'));
    }

    public function export(Request $request)
    {
        // Build query similar to filter method
        $query = Deposits::where('type', 'cash');

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
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'Date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'ADM Number' => $admDetails->adm_number ?? 'N/A',
                'ADM Name' => $admDetails->name ?? 'N/A',
                'Amount' => $deposit->amount ?? 0,
                'Status' => ucfirst($status ?: 'Deposited')
            ];
        })->toArray();

        $headers = ['Date', 'ADM Number', 'ADM Name', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($data, $headers), 'cash_deposits.xlsx');
    }
}
