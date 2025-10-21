<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoices; // ✅ NEW (we now store in invoices table)
use App\Models\Customers; // ✅ For fetching customer IDs
use Carbon\Carbon;

class ReturnChequeController extends Controller
{
    /**
     * Show create return cheque form
     */
    public function create()
    {
        // ✅ Fetch all customers (for dropdown)
        $customers = Customers::select('customer_id')->get();

        // Dummy banks for now
        $banks = [
            'Bank of Ceylon',
            'People’s Bank',
            'Commercial Bank',
            'Hatton National Bank',
            'Sampath Bank',
        ];

        $branches = [
            'Colombo',
            'Kandy',
            'Galle',
            'Negombo',
            'Kurunegala',
        ];

        return view('return_cheques.create_return_cheque', compact('customers', 'banks', 'branches'));
    }

    /**
     * Store return cheque details in invoices table
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'       => 'required|string|exists:customers,customer_id',
            'cheque_number'     => 'required|string|max:255',
            'cheque_amount'     => 'required|numeric',
            'returned_date'     => 'required|date',
            'bank_id'           => 'required|string',
            'branch_id'         => 'required|string',
            'return_type'       => 'required|string|max:255',
            'reason'            => 'nullable|string|max:255',
        ]);

        // ✅ Create new record in invoices table
        Invoices::create([
            'type'              => 'return_cheque', // fixed type
            'invoice_or_cheque_no' => $validated['cheque_number'],
            'customer_id'       => $validated['customer_id'],
            'amount'            => $validated['cheque_amount'],
            'returned_date'     => Carbon::parse($validated['returned_date'])->format('Y-m-d'),
            'bank'              => $validated['bank_id'],
            'branch'            => $validated['branch_id'],
            'return_type'       => $validated['return_type'],
            'reason'            => $validated['reason'],
        ]);

        return redirect()->route('returncheques.index')->with('success', 'Return cheque created successfully!');
    }

    /**
     * Show list of return cheques
     */
    public function index()
    {
        $returnCheques = Invoices::where('type', 'return_cheque')
            ->with(['customer.admDetails']) // Eager load customer & ADM
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('return_cheques.return_cheques', compact('returnCheques'));
    }


    /**
     * Show single return cheque
     */
    public function show($id)
    {
        $returnCheque = Invoices::where('type', 'return_cheque')
            ->with(['customer.admDetails'])
            ->findOrFail($id);

        return view('return_cheques.return_cheque_details', compact('returnCheque'));
    }
}
