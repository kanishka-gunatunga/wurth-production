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
use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\Reminders;
use App\Models\Inquiries;
use App\Models\AdvancedPayment;
use File;
use Mail;
use Image;
use PDF;

class AdvancedPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function create_advanced_payment(Request $request)
    {
        if ($request->isMethod('get')) {

            $user = User::where('id', Auth::user()->id)->with('userDetails')->first();
            $customers = Customers::all();

            return view('adm::advanced_payments.create_advanced_payment', [
                'user' => $user,
                'customers' => $customers
            ]);
        }

        if ($request->isMethod('post')) {

            $request->validate([
                'date'            => 'required|date',
                'customer'        => 'required|string',
                'mobile_no'       => 'required',
                'payment_amount'  => 'required|numeric|min:1',
                'reason'          => 'required',
            ]);

            DB::beginTransaction();

            try {

                /* ----------------------------------------
             | 1. FIND OR CREATE TEMP CUSTOMER
             ---------------------------------------- */
                $customerInput = trim($request->customer);

                // Try to find existing customer
                $customer = Customers::where('customer_id', $customerInput)->first();

                if (!$customer) {

                    // Must be in: CUSTOMER_ID | CUSTOMER_NAME format
                    if (!str_contains($customerInput, '|')) {
                        return back()->withErrors([
                            'customer' => 'New customer format should be: CUSTOMER_ID | CUSTOMER_NAME'
                        ]);
                    }

                    [$customerId, $customerName] = array_map(
                        'trim',
                        explode('|', $customerInput, 2)
                    );

                    $customer = Customers::create([
                        'is_temp'     => 1,
                        'customer_id' => $customerId,
                        'name'        => $customerName,
                        'adm'         => Auth::user()->userDetails->adm_number,
                        'status'      => 'active',
                    ]);
                }

                /* ----------------------------------------
             | 2. SAVE ATTACHMENT
             ---------------------------------------- */
                $attachment_name = null;
                if ($request->hasFile('attachment')) {
                    $attachment_name = time() . '-' . Str::uuid() . '.' . $request->attachment->extension();
                    $request->attachment->move(
                        public_path('uploads/adm/advanced_payments/attachments/'),
                        $attachment_name
                    );
                }

                /* ----------------------------------------
             | 3. SAVE CUSTOMER SIGNATURE
             ---------------------------------------- */
                $signature_name = null;
                if ($request->filled('customer_signature')) {

                    $signatureData = str_replace(
                        ['data:image/png;base64,', ' '],
                        ['', '+'],
                        $request->customer_signature
                    );

                    $signature_name = time() . '-' . Str::uuid() . '.png';
                    $filePath = public_path('uploads/adm/advanced_payments/signatures/' . $signature_name);

                    if (!File::isDirectory(dirname($filePath))) {
                        File::makeDirectory(dirname($filePath), 0775, true);
                    }

                    File::put($filePath, base64_decode($signatureData));
                }

                /* ----------------------------------------
             | 4. SAVE ADVANCED PAYMENT
             ---------------------------------------- */
                $advancedPayment = new AdvancedPayment();
                $advancedPayment->adm_id          = Auth::user()->id;
                $advancedPayment->date            = $request->date;
                $advancedPayment->customer        = $customer->customer_id;
                $advancedPayment->mobile_no       = $request->mobile_no;
                $advancedPayment->payment_amount  = $request->payment_amount;
                $advancedPayment->reason          = $request->reason;
                $advancedPayment->attachment      = $attachment_name;
                $advancedPayment->customer_signature = $signature_name;
                $advancedPayment->status = 'pending';
                $advancedPayment->save();

                DB::commit();

                return back()->with('success', 'Advanced Payment Successfully Added');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('fail', 'Something went wrong while saving data.');
            }
        }
    }

    public function get_customer_details($customer_id)
    {
        $customer = Customers::where('customer_id', $customer_id)->first();

        // Return as JSON
        return response()->json($customer);
    }

    public function advance_payments_list(Request $request)
    {
        $userId = Auth::user()->id;

        $payments = AdvancedPayment::with(['customerData'])
            ->where('adm_id', $userId)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('adm::advanced_payments.advance_payment', [
            'payments' => $payments
        ]);
    }

    public function download_attachment($id)
    {
        $payment = AdvancedPayment::findOrFail($id);

        $file = public_path('uploads/adm/advanced_payments/attachments/' . $payment->attachment);

        if (!file_exists($file)) {
            return back()->with('error', 'Attachment not found!');
        }

        return response()->download($file);
    }

    public function advanced_payment_details($id)
    {
        $payment = AdvancedPayment::with('customerData')->findOrFail($id);

        return view('adm::advanced_payments.details', [
            'payment' => $payment
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }
}
