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

class CardPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Fetch card payments with relations
        $cardPayments = InvoicePayments::with([
            'invoice.customer',      // invoice -> customer
            'invoice.customer.userDetail'  // customer -> user_detail
        ])
            ->where('type', 'card')
            ->orderBy('card_transfer_date', 'desc')
            ->paginate(10); // Laravel pagination

        return view('finance::card_payment.card_payments', compact('cardPayments'));
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
}
