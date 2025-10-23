<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use App\Models\InvoicePayments;
use Illuminate\Support\Facades\Storage;

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
            'status' => 'required|in:approved,rejected',
        ]);

        $deposit = Deposits::findOrFail($id);
        $deposit->status = strtolower($request->status);
        $deposit->save();

        $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        if (!empty($receiptIds)) {
            InvoicePayments::whereIn('id', $receiptIds)
                ->update(['status' => strtolower($request->status)]);
        }

        return response()->json(['success' => true]);
    }
}
