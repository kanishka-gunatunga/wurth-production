<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use Illuminate\Support\Facades\Storage;
use App\Models\Invoices;
use App\Models\Customers;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;

class FinanceChequeController extends Controller
{
    public function index()
    {
        $deposits = Deposits::where('type', 'finance_cheque')
            ->orderByDesc('created_at')
            ->paginate(10);

        $deposits->getCollection()->transform(function ($deposit) {
            $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();

            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

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

        return view('finance::finance_cheque.finance_cheque', [
            'data' => $deposits,
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

        $status = strtolower($deposit->status ?? '');
        if ($status === 'pending') $status = 'deposited';

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

        return view('finance::finance_cheque.finance_cheque_details', [
            'deposit' => $depositData,
            'payments' => $invoicePayments,
        ]);
    }

    public function downloadAttachment($id)
    {
        $deposit = Deposits::findOrFail($id);
        $path = $deposit->attachment_path;

        if (!$path) return back()->with('error', 'No file found for this record.');

        if (Storage::disk('local')->exists($path)) return Storage::disk('local')->download($path);
        if (Storage::disk('public')->exists($path)) return Storage::disk('public')->download($path);

        $absolutePath = public_path($path);
        if (file_exists($absolutePath)) return response()->download($absolutePath);

        return back()->with('error', 'No file found for this record.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,over_to_finance',
        ]);

        $deposit = Deposits::findOrFail($id);
        $deposit->status = strtolower($request->status);
        $deposit->save();

        // update invoice payment only when approved or rejected
        if (in_array($request->status, ['approved', 'rejected'])) {
            $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];
            $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

            if (!empty($receiptIds)) {
                InvoicePayments::whereIn('id', $receiptIds)
                    ->update(['status' => strtolower($request->status)]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $deposits = Deposits::where('type', 'finance_cheque')
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
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $adm?->adm_number ?? 'N/A',
                'adm_name' => $adm?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = ['search' => $search];

        return view('finance::finance_cheque.finance_cheque', [
            'data' => $paginated,
            'filters' => $filters,
        ]);
    }

    public function filter(Request $request)
    {
        $query = Deposits::where('type', 'finance_cheque');

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
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'adm_number' => $adm?->adm_number ?? 'N/A',
                'adm_name' => $adm?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount ?? 0,
                'status' => ucfirst($status ?: 'Deposited'),
                'attachment_path' => $deposit->attachment_path ?? null,
            ];
        });

        $filters = $request->all();

        return view('finance::finance_cheque.finance_cheque', [
            'data' => $deposits,
            'filters' => $filters,
        ]);
    }

    public function export(Request $request)
    {
        $query = Deposits::where('type', 'finance_cheque');

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
            $status = strtolower($deposit->status ?? '');
            if ($status === 'pending') $status = 'deposited';

            return [
                'Date' => $deposit->date_time ? date('Y-m-d', strtotime($deposit->date_time)) : 'N/A',
                'ADM Number' => $adm?->adm_number ?? 'N/A',
                'ADM Name' => $adm?->name ?? 'N/A',
                'Bank Name' => $deposit->bank_name,
                'Branch Name' => $deposit->branch_name,
                'Cheque No' => $deposit->id,
                'Amount' => $deposit->amount ?? 0,
                'Status' => ucfirst($status ?: 'Deposited'),
            ];
        })->toArray();

        $headers = ['Date', 'ADM Number', 'ADM Name', 'Bank Name', 'Branch Name', 'Cheque No', 'Amount', 'Status'];

        return Excel::download(new ArrayExport($dataArray, $headers), 'finance_cheque.xlsx');
    }
}
