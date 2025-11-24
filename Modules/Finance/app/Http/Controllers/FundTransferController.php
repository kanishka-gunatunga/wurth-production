<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvoicePayments;
use Illuminate\Http\Request;
use App\Models\Deposits;
use App\Models\UserDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FundTransferController extends Controller
{
    public function index(Request $request)
    {
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

        $fundTransfers = $query->paginate(10);

        return view('finance::fund_transfer.fund_transfers', compact('fundTransfers'));
    }

    public function show($id)
    {
        $deposit = InvoicePayments::with([
            'invoice.customer.admDetails'
        ])->findOrFail($id);

        return view('finance::fund_transfer.fund_transfer_details', compact('deposit'));
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
            $payment->status = $newStatus;
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

            return response()->json(['success' => true, 'status' => ucfirst($payment->status)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
