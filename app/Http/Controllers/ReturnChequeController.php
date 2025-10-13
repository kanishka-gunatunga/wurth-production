<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnCheque;
use App\Models\User;
use App\Models\UserDetails;

class ReturnChequeController extends Controller
{
    public function create()
    {
        // Get ADMs (user_role = 6)
        $adms = User::where('user_role', 6)->with('userDetails')->get();

        // (You’ll add bank data fetching later when you share table info)
        $banks = []; // placeholder

        return view('return_cheques.create_return_cheque', compact('adms', 'banks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'adm_id' => 'required|exists:users,id',
            'cheque_number' => 'required|string|max:255',
            'cheque_amount' => 'required|numeric',
            'returned_date' => 'required|date',
            'bank_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'return_type' => 'required|string|max:255',
            'reason' => 'nullable|string',
        ]);

        ReturnCheque::create($validated);

        return redirect()->back()->with('success', 'Return cheque created successfully!');
    }

    public function index()
    {
        // Fetch all return cheques with related ADM (user) details
        $returnCheques = ReturnCheque::with(['adm.userDetails'])->get();

        return view('return_cheques.return_cheques', compact('returnCheques'));
    }
}
