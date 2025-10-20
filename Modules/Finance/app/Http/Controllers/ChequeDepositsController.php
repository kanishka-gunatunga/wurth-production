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
        $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];

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
        $decodedReceipts = json_decode($deposit->reciepts, true) ?? [];
        $receiptIds = collect($decodedReceipts)->pluck('reciept_id')->toArray();

        if (!empty($receiptIds)) {
            \App\Models\InvoicePayments::whereIn('id', $receiptIds)
                ->update(['status' => strtolower($request->status)]);
        }

        return response()->json(['success' => true]);
    }
}
