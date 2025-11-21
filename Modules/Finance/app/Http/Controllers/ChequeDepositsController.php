<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use Illuminate\Support\Facades\Storage;

class ChequeDepositsController extends Controller
{
    /**
     * Show the cheque deposits page with data.
     */
    public function index()
    {
        // Fetch cheque deposits with pagination (10 per page)
        $deposits = Deposits::where('type', 'cheque')
            ->orderByDesc('created_at')
            ->paginate(10);

        // Transform results before sending to view
        $deposits->getCollection()->transform(function ($deposit) {
            $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();

            // Convert pending → Deposited
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') {
                $status = 'deposited';
            }

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $userDetail?->adm_number ?? 'N/A',
                'adm_name' => $userDetail?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        return view('finance::cheque_deposits.cheque_deposits', [
            'data' => $deposits,
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
        $status = strtolower($deposit->status ?? '');
        if ($status === 'pending') {
            $status = 'deposited';
        }

        $depositData = [
            'id' => $deposit->id,
            'adm_name' => $userDetail?->name ?? 'N/A',
            'adm_number' => $userDetail?->adm_number ?? 'N/A',
            'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
            'bank_name' => $deposit->bank_name,
            'branch_name' => $deposit->branch_name,
            'amount' => $deposit->amount ?? 0,
            'status' => ucfirst($status ?: 'Deposited'),
            'attachment_path' => $deposit->attachment_path ?? null,
        ];

        return view('finance::cheque_deposits.cheque_deposit_details', [
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

        $path = $deposit->attachment_path;

        if (!$path) {
            return back()->with('error', 'No file found for this record.');
        }

        // Check if file exists in "storage/app" (default local disk)
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }

        // Check if file exists in "public" folder
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
        }

        // Fallback: check absolute path (if you store in public folder manually)
        $absolutePath = public_path($path);
        if (file_exists($absolutePath)) {
            return response()->download($absolutePath);
        }

        return back()->with('error', 'No file found for this record.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $deposit = Deposits::findOrFail($id);

        // ✅ Update deposit status
        $deposit->status = strtolower($request->status);
        $deposit->save();

        // ✅ Update all related invoice payments too
        $decodedReceipts = $deposit->reciepts ?? [];
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        if (!empty($receiptIds)) {
            \App\Models\InvoicePayments::whereIn('id', $receiptIds)
                ->update(['status' => strtolower($request->status)]);
        }

        return response()->json(['success' => true]);
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

        return view('finance::cheque_deposits.cheque_deposits', [
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

        return view('finance::cheque_deposits.cheque_deposits', [
            'data' => $deposits,
            'filters' => $request->all()
        ]);
    }
}
