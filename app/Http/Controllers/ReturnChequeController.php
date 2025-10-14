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
        // Fetch return cheques with related ADM details and paginate (10 per page)
        $returnCheques = ReturnCheque::with(['adm.userDetails'])
            ->orderBy('returned_date', 'desc')
            ->paginate(10);

        return view('return_cheques.return_cheques', compact('returnCheques'));
    }

    public function show($id)
    {
        $returnCheque = ReturnCheque::with(['adm.userDetails'])->findOrFail($id);

        return view('return_cheques.return_cheque_details', compact('returnCheque'));
    }
}
