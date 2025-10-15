<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturnCheque;
use App\Models\User;
use App\Models\UserDetails;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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

    public function importReturnCheques(Request $request)
    {
        // ✅ 1. Validate uploaded file type & size
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:10240', // 10MB
        ]);

        // ✅ 2. Read the uploaded Excel file directly (not saving)
        $file = $request->file('file');
        $data = Excel::toArray([], $file); // Read Excel into an array

        // ✅ 3. Get the first sheet
        $rows = $data[0];

        // ✅ 4. Extract headers (first row)
        $header = array_map('trim', array_shift($rows)); // example: ['adm_id', 'cheque_number', ...]

        $inserted = 0;

        // ✅ 5. Loop through each row and insert into DB
        foreach ($rows as $row) {
            $record = array_combine($header, $row);

            // Skip empty rows
            if (empty($record['adm_id']) || empty($record['cheque_number'])) {
                continue;
            }

            // Prevent duplicates by cheque number
            if (ReturnCheque::where('cheque_number', $record['cheque_number'])->exists()) {
                continue;
            }

            // Insert new record
            ReturnCheque::create([
                'adm_id'        => $record['adm_id'],
                'cheque_number' => $record['cheque_number'],
                'cheque_amount' => $record['cheque_amount'] ?? 0,
                'returned_date' => isset($record['returned_date'])
                    ? Carbon::parse($record['returned_date'])->format('Y-m-d')
                    : now(),
                'bank_id'       => $record['bank_id'] ?? null,
                'branch_id'     => $record['branch_id'] ?? null,
                'return_type'   => $record['return_type'] ?? null,
                'reason'        => $record['reason'] ?? null,
            ]);

            $inserted++;
        }

        // ✅ 6. Return JSON response (no file saved)
        return response()->json([
            'message' => "$inserted return cheque(s) imported successfully!",
        ]);
    }
}
