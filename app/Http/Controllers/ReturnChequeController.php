<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
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

    public function importReturnCheques(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (count($rows) <= 1) {
                return response()->json(['message' => 'Excel file is empty!'], 422);
            }

            // Header row mapping
            $header = array_map('strtolower', $rows[0]);
            $requiredColumns = [
                'customer_id',
                'customer_name',
                'adm_id',
                'cheque_number',
                'cheque_amount',
                'returned_date',
                'bank',
                'branch',
                'return_type',
                'reason'
            ];

            if (count(array_diff($requiredColumns, $header)) > 0) {
                return response()->json(['message' => 'Invalid Excel format. Missing required columns.'], 422);
            }

            $successCount = 0;
            $duplicateCheques = [];

            for ($i = 1; $i < count($rows); $i++) {
                $row = array_combine($header, $rows[$i]);

                if (empty($row['cheque_number']) || empty($row['customer_id'])) continue;

                // ✅ Skip duplicate cheque numbers
                if (Invoices::where('invoice_or_cheque_no', $row['cheque_number'])->exists()) {
                    $duplicateCheques[] = $row['cheque_number'];
                    continue;
                }

                // ✅ Find or create customer
                $customer = Customers::where('customer_id', $row['customer_id'])->first();

                if (!$customer) {
                    $customer = new Customers();
                    $customer->customer_id = $row['customer_id'];
                    $customer->name = $row['customer_name'] ?? 'Unknown';
                    $customer->adm = $row['adm_id'] ?? null;
                    $customer->is_temp = 1; // mark as temporary
                    $customer->status = 'active';
                    $customer->save();
                }

                // ✅ Insert return cheque into invoices
                Invoices::create([
                    'type' => 'return_cheque',
                    'invoice_or_cheque_no' => $row['cheque_number'],
                    'customer_id' => $customer->customer_id,
                    'amount' => $row['cheque_amount'] ?? 0,
                    'returned_date' => !empty($row['returned_date'])
                        ? Carbon::parse($row['returned_date'])->format('Y-m-d')
                        : null,
                    'bank' => $row['bank'] ?? null,
                    'branch' => $row['branch'] ?? null,
                    'return_type' => $row['return_type'] ?? null,
                    'reason' => $row['reason'] ?? null,
                ]);

                $successCount++;
            }

            $msg = "{$successCount} records successfully inserted.";
            if (!empty($duplicateCheques)) {
                $msg .= " Skipped duplicates: " . implode(', ', $duplicateCheques);
            }

            return response()->json(['message' => $msg]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during upload. Please try again!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
