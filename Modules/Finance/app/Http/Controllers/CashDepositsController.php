<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;

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
        // Fetch the specific deposit by ID
        $deposit = Deposits::findOrFail($id);

        // Fetch ADM details
        $admDetails = UserDetails::where('user_id', $deposit->adm_id)->first();

        return view('finance::cash_deposits.payment_slip', compact('deposit', 'admDetails'));
    }

    public function downloadAttachment($id)
    {
        $deposit = Deposits::findOrFail($id);

        if (!$deposit->attachment_path || !file_exists(public_path($deposit->attachment_path))) {
            return back()->with('error', 'No file found for this record.');
        }

        return response()->download(public_path($deposit->attachment_path));
    }
}
