<?php

namespace Modules\ADM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\SendReceiptMail;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\InvoicePayments;
use App\Models\InvoicePaymentBatches;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Deposits;
use File;
use Mail;
use Image;
use PDF;
class DepositeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function daily_deposit(Request $request)
{ 
    if ($request->isMethod('get')) {
        $banks = Bank::all();
         if(Auth::user()->user_role == 6 ){
        $deposites = Deposits::where('adm_id', auth()->id())
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();
         } else{
            $deposites = Deposits::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();
         }
        $cashTotal = $deposites->filter(function($d) {
            return in_array(strtolower($d->type), ['cash', 'finance - cash']);
        })->sum('amount');

        $chequeTotal = $deposites->filter(function($d) {
            return in_array(strtolower($d->type), ['cheque', 'finance - cheque']);
        })->sum('amount');

        return view('adm::deposite.daily_deposit', [
            'banks' => $banks,
            'cashTotal' => $cashTotal,
            'chequeTotal' => $chequeTotal
        ]);
    }

    if ($request->isMethod('post')) {
        $request->validate([
            'deposit_type' => 'required|string',
            'deposit_total' => 'required|numeric|min:0.01',
            'selected_receipts' => 'required|string',
            'screenshot' => 'required',
            'screenshot.*' => 'file',
        ]);

        $depositType = strtolower($request->deposit_type);

        // Handle multiple file uploads
        $attachmentPaths = [];
        if ($request->hasFile('screenshot')) {
            foreach ($request->file('screenshot') as $file) {
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $filePath = 'db_files/attachments/deposits/';
                $file->move(public_path($filePath), $fileName);
                $attachmentPaths[] = $filePath . $fileName;
            }
        }

        // Decode selected receipts
        $receiptIds = json_decode($request->selected_receipts, true);
        if (!is_array($receiptIds) || empty($receiptIds)) {
            return redirect()->back()->with('error', 'Invalid receipts selected.');
        }
        $depositAdmId = null;
        if(Auth::user()->user_role == 6) {
            $depositAdmId = auth()->id();
        } else {
            $lastReceipt = InvoicePayments::with('invoice.customer')->find(end($receiptIds));
            if($lastReceipt && $lastReceipt->invoice && $lastReceipt->invoice->customer){
                $customer = $lastReceipt->invoice->customer;
                $admNumber = !empty($customer->secondary_adm) ? $customer->secondary_adm : $customer->adm;
                $admDetails = UserDetails::where('adm_number', $admNumber)->first();
                $depositAdmId = $admDetails ? $admDetails->user_id : auth()->id();
            } else {
                $depositAdmId = auth()->id(); 
            }
        }
        // 🧩 Handle "finance-cheque" differently
        if ($depositType === 'finance - cheque' || $depositType === 'finance-cheque') {
            foreach ($receiptIds as $receiptId) {
                $receipt = InvoicePayments::find($receiptId);
                if (!$receipt) continue;

                $deposit = new Deposits();
                $deposit->type = $depositType;
                $deposit->date_time = now();
                $deposit->amount = $receipt->final_payment ?? 0; // Use individual receipt amount
                $deposit->reciepts = json_encode([['reciept_id' => $receiptId]]);
                $deposit->adm_id = $depositAdmId;
                $deposit->status = 'pending';
                $deposit->bank_name = $request->cheque_bank;
                $deposit->branch_name = $request->cheque_branch;
                $deposit->attachment_path = json_encode($attachmentPaths);
                $deposit->save();

                // Update receipt status
                $receipt->update(['status' => 'deposited']);
            }
        } else {
            // 🧩 All other deposit types can group receipts together
            $receipts = collect($receiptIds)->map(fn($id) => ['reciept_id' => $id])->values();

            $deposit = new Deposits();
            $deposit->type = $depositType;
            $deposit->date_time = now();
            $deposit->amount = $request->deposit_total;
            $deposit->reciepts = $receipts->toJson();
            $deposit->adm_id = $depositAdmId;
            $deposit->status = 'pending';
            $deposit->bank_name = $request->cheque_bank;
            $deposit->branch_name = $request->cheque_branch;
            $deposit->attachment_path = json_encode($attachmentPaths);
            $deposit->save();

            foreach ($receiptIds as $receiptId) {
                InvoicePayments::where('id', $receiptId)->update(['status' => 'deposited']);
            }
        }

        return redirect()->back()->with('success', 'Deposit submitted successfully!');
    }
}

public function get_receipts(Request $request)
{
    if(Auth::user()->user_role == 6) {
    $paymentsQuery = InvoicePayments::with(['invoice.customer', 'batch'])
        ->where('status', 'pending')
        ->where('adm_id', Auth::id())
        ->whereHas('batch', function ($q) {
            $q->where('temp_receipt', 0);
        });
    }else{
         $paymentsQuery = InvoicePayments::with(['invoice.customer', 'batch'])
        ->where('status', 'pending')
        ->whereHas('batch', function ($q) {
            $q->where('temp_receipt', 0);
        });
    }
    // ✅ Filter by deposit type (based on InvoicePayments.type)
    if ($request->filled('deposit_type')) {
        $depositType = strtolower($request->deposit_type);

        if (in_array($depositType, ['cash', 'finance-cash'])) {
            $paymentsQuery->where('type', 'cash');
        } elseif (in_array($depositType, ['cheque', 'finance-cheque'])) {
            $paymentsQuery->where('type', 'cheque');
        }
    }

    // 🔍 Search filter (customer name or invoice number)
    if ($request->filled('search')) {
        $search = $request->search;
        $paymentsQuery->where(function ($q) use ($search) {
            $q->whereHas('invoice.customer', function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%");
            })->orWhereHas('invoice', function ($sub) use ($search) {
                $sub->where('invoice_or_cheque_no', 'like', "%{$search}%");
            });
        });
    }

    return response()->json($paymentsQuery->get());
}



}
