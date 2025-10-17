<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            ->paginate(10); // ✅ Laravel pagination

        // Map paginated data using transform()
        $deposits->getCollection()->transform(function ($deposit) {
            $userDetail = UserDetails::where('user_id', $deposit->adm_id)->first();

            return [
                'id' => $deposit->id,
                'date' => $deposit->date_time,
                'adm_number' => $deposit->adm_id,
                'adm_name' => $userDetail?->name ?? 'N/A',
                'bank_name' => $deposit->bank_name,
                'branch_name' => $deposit->branch_name,
                'amount' => $deposit->amount,
                'status' => $this->getStatusLabel($deposit->status),
                'attachment_path' => $deposit->attachment_path,
            ];
        });

        return view('finance::cheque_deposits.cheque_deposits', [
            'data' => $deposits,
        ]);
    }


    /**
     * Download attachment file if available.
     */
    public function downloadAttachment($id)
    {
        $deposit = Deposits::findOrFail($id);

        if (!$deposit->attachment_path || !Storage::exists($deposit->attachment_path)) {
            return back()->with('error', 'No file found for this record.');
        }

        return Storage::download($deposit->attachment_path);
    }

    /**
     * Helper to translate status.
     */
    private function getStatusLabel($status)
    {
        if (strtolower($status) === 'pending') {
            return 'Deposited';
        } elseif (strtolower($status) === 'rejected') {
            return 'Rejected';
        }
        return ucfirst($status);
    }
}
