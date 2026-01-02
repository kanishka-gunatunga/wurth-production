<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Invoices; // ✅ NEW (we now store in invoices table)
use App\Models\Customers; // ✅ For fetching customer IDs
use Carbon\Carbon;
use App\Services\ActivitLogService;

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

        return redirect('return-cheques')
            ->with('success', 'Return cheque created successfully!');
    }

    /**
     * Show list of return cheques
     */
    public function index(Request $request)
    {
        $query = Invoices::where('type', 'return_cheque')
            ->with(['customer.admDetails']); // Eager load customer & ADM

        // ----------------------
        // SEARCH
        // ----------------------
        if ($request->search) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($customer) use ($search) {
                    $customer->where('adm', 'like', "%$search%")
                        ->orWhere('customer_id', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                });

                $q->orWhereHas('customer.admDetails', function ($adm) use ($search) {
                    $adm->where('name', 'like', "%$search%");
                });

                $q->orWhere('invoice_or_cheque_no', 'like', "%$search%");
            });
        }

        // ----------------------
        // FILTERS
        // ----------------------
        if ($request->filled('adm_ids')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->whereIn('adm', $request->adm_ids);
            });
        }

        if ($request->filled('adm_names')) {
            $query->whereHas('customer.admDetails', function ($q) use ($request) {
                $q->whereIn('name', $request->adm_names);
            });
        }

        if ($request->filled('return_type')) {
            $query->whereIn('return_type', $request->return_type);
        }

        if ($request->filled('date_range')) {
            $range = trim($request->date_range);

            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range));
            } else {
                $start = $end = $range;
            }

            if (!empty($start) && !empty($end)) {
                $query->whereBetween('returned_date', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        // ----------------------
        // PAGINATION
        // ----------------------
        $returnCheques = $query->orderByDesc('created_at')->paginate(10);
        $filters = $request->all();

        return view('return_cheques.return_cheques', compact('returnCheques', 'filters'));
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
        $fileName = $file->getClientOriginalName();
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) <= 1) {
            return back()->with('fail', 'Excel file is empty!');
        }

        // Header row mapping
        $header = array_map('strtolower', $rows[0]);
        $requiredColumns = [
            'date',
            'amount',
            'document number',
            'reference',
            'cx number', 
            'cx name',
            'adm code',
            'remark',
        ];

        if (count(array_diff($requiredColumns, $header)) > 0) {
            return back()->with('fail', 'Invalid Excel format. Missing required columns.');
        }

        $successCount = 0;
        $duplicateCheques = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = array_combine($header, $rows[$i]);

            if (empty($row['document number']) || empty($row['cx number'])) continue;

            // Skip duplicate cheque numbers
            if (Invoices::where('invoice_or_cheque_no', $row['document number'])->exists()) {
                $duplicateCheques[] = $row['document number'];
                continue;
            }

            // Find or create customer
            $customer = Customers::where('customer_id', $row['cx number'])->first();

            if (!$customer) {
                $customer = new Customers();
                $customer->customer_id = $row['cx number'];
                $customer->name = $row['cx name'] ?? null;
                $customer->adm = $row['adm code'] ?? null;
                $customer->is_temp = 1; 
                $customer->status = 'active';
                $customer->save();
            }

            $amount = $row['amount'] ?? 0;
            $amount = str_replace(',', '', $amount);
            $amount = str_replace(['(', ')'], '', $amount);
            $amount = floatval($amount);
            $returnedDate = null;
            if (!empty($row['date'])) {
                try {
                    $returnedDate = Carbon::createFromFormat('d/m/y', $row['date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $returnedDate = null;
                }
            }
            Invoices::create([
                'type' => 'return_cheque',
                'invoice_or_cheque_no' => $row['document number'],
                'customer_id' => $customer->customer_id,
                'amount' =>$amount,
                'returned_date' => $returnedDate,
                'bank' => $row['bank'] ?? null,
                'branch' => $row['branch'] ?? null,
                'return_type' => $row['return_type'] ?? null,
                'reason' => $row['remark'] ?? null,
                'reference' => $row['reference'] ?? null,
            ]);

            $successCount++;
        }

        $msg = "{$successCount} records successfully inserted.";
        if (!empty($duplicateCheques)) {
            $msg .= " Skipped duplicates: " . implode(', ', $duplicateCheques);
        }

        ActivitLogService::log('import', 'return cheques imported from file - ' . $fileName);

        return back()->with('success', $msg);

    } catch (\Exception $e) {
        return back()->with('fail', 'Error during upload. Please try again! ' . $e->getMessage());
    }
}

}
