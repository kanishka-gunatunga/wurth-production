<?php

namespace Modules\Finance\Http\Controllers;

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

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\InvoicePayments;
use App\Models\InvoicePaymentBatches;
use App\Models\AdvancedPayment;

use File;
use Mail;
use Image;
use PDF;

class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function all_receipts(Request $request)
    {
        $activeTab = request('active_tab', 'final');

        // Final Receipts Search
        $regularQuery = InvoicePayments::with(['invoice.customer.admDetails', 'batch'])
            ->whereHas('batch', fn($q) => $q->where('temp_receipt', 0));

        if ($request->filled('final_search')) {
            $search = $request->final_search;
            $regularQuery->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas(
                        'invoice.customer',
                        fn($q2) =>
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('adm', 'like', "%{$search}%")
                    );
            });
        }

        // Final Receipts Filters
        if ($request->filled('final_adm_names')) {
            $regularQuery->whereHas('invoice.customer.admDetails', function ($q) use ($request) {
                $q->whereIn('name', $request->final_adm_names);
            });
        }

        if ($request->filled('final_adm_ids')) {
            $regularQuery->whereHas('invoice.customer.admDetails', function ($q) use ($request) {
                $q->whereIn('adm_number', $request->final_adm_ids);
            });
        }

        if ($request->filled('final_customers')) {
            $regularQuery->whereHas('invoice.customer', function ($q) use ($request) {
                $q->whereIn('name', $request->final_customers);
            });
        }

        if ($request->filled('final_status')) {
            $regularQuery->where('status', $request->final_status);
        }

        if ($request->filled('final_date_range')) {
            $range = trim($request->final_date_range);

            // Support both "YYYY-MM-DD to YYYY-MM-DD" and "YYYY-MM-DD - YYYY-MM-DD"
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range));
            } else {
                $start = $end = $range;
            }

            // Make sure both dates are valid
            if (!empty($start) && !empty($end)) {
                $regularQuery->whereBetween('created_at', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        $regular_receipts = $regularQuery->paginate(15, ['*'], 'regular_page');

        // Temporary Receipts Search
        $tempQuery = InvoicePayments::with(['invoice.customer.admDetails', 'batch'])
            ->whereHas('batch', fn($q) => $q->where('temp_receipt', '!=', 0));

        if ($request->filled('temp_search')) {
            $search = $request->temp_search;
            $tempQuery->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas(
                        'invoice.customer',
                        fn($q2) =>
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('adm', 'like', "%{$search}%")
                    );
            });
        }

        // Temp Receipts Filters
        if ($request->filled('temp_adm_names')) {
            $tempQuery->whereHas('invoice.customer.admDetails', function ($q) use ($request) {
                $q->whereIn('name', $request->temp_adm_names);
            });
        }

        if ($request->filled('temp_adm_ids')) {
            $tempQuery->whereHas('invoice.customer.admDetails', function ($q) use ($request) {
                $q->whereIn('adm_number', $request->temp_adm_ids);
            });
        }

        if ($request->filled('temp_customers')) {
            $tempQuery->whereHas('invoice.customer', function ($q) use ($request) {
                $q->whereIn('name', $request->temp_customers);
            });
        }

        if ($request->filled('temp_status')) {
            $tempQuery->where('status', $request->temp_status);
        }

        if ($request->filled('temp_date_range')) {
            $range = trim($request->temp_date_range);

            // Support both "YYYY-MM-DD to YYYY-MM-DD" and "YYYY-MM-DD - YYYY-MM-DD"
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range));
            } else {
                $start = $end = $range;
            }

            // Make sure both dates are valid
            if (!empty($start) && !empty($end)) {
                $tempQuery->whereBetween('created_at', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        $temp_receipts = $tempQuery->paginate(5, ['*'], 'temp_page');

        // Advance Payments Search
        $advanceQuery = AdvancedPayment::with(['customerData', 'adm.userDetails']);
        if ($request->filled('advance_search')) {
            $search = $request->advance_search;
            $advanceQuery->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas(
                        'customerData',
                        fn($q2) =>
                        $q2->where('name', 'like', "%{$search}%")
                    )
                    ->orWhereHas(
                        'adm.userDetails',
                        fn($q3) =>
                        $q3->where('name', 'like', "%{$search}%")
                            ->orWhere('adm_number', 'like', "%{$search}%")
                    );
            });
        }

        // Advance Payments Filters
        if ($request->filled('advance_adm_names')) {
            $advanceQuery->whereHas('adm.userDetails', function ($q) use ($request) {
                $q->whereIn('name', $request->advance_adm_names);
            });
        }

        if ($request->filled('advance_adm_ids')) {
            $advanceQuery->whereHas('adm.userDetails', function ($q) use ($request) {
                $q->whereIn('adm_number', $request->advance_adm_ids);
            });
        }

        if ($request->filled('advance_customers')) {
            $advanceQuery->whereHas('customerData', function ($q) use ($request) {
                $q->whereIn('name', $request->advance_customers);
            });
        }

        if ($request->filled('advance_status')) {
            $advanceQuery->where('status', $request->advance_status);
        }

        if ($request->filled('advance_date_range')) {
            $range = trim($request->advance_date_range);

            // Support both "YYYY-MM-DD to YYYY-MM-DD" and "YYYY-MM-DD - YYYY-MM-DD"
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range));
            } else {
                $start = $end = $range;
            }

            // Make sure both dates are valid
            if (!empty($start) && !empty($end)) {
                $advanceQuery->whereBetween('created_at', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        $advanced_payments = $advanceQuery->paginate(15, ['*'], 'advance_page');

        // Separate dropdown data for each tab (your existing code remains the same)
        $finalAdmNames = InvoicePayments::whereHas('batch', fn($q) => $q->where('temp_receipt', 0))
            ->with('invoice.customer.admDetails')
            ->get()
            ->pluck('invoice.customer.admDetails.name')
            ->filter()
            ->unique()
            ->values();

        $finalAdmIds = InvoicePayments::whereHas('batch', fn($q) => $q->where('temp_receipt', 0))
            ->with('invoice.customer.admDetails')
            ->get()
            ->pluck('invoice.customer.admDetails.adm_number')
            ->filter()
            ->unique()
            ->values();

        $finalCustomers = InvoicePayments::whereHas('batch', fn($q) => $q->where('temp_receipt', 0))
            ->with('invoice.customer')
            ->get()
            ->pluck('invoice.customer.name')
            ->filter()
            ->unique()
            ->values();

        $tempAdmNames = InvoicePayments::whereHas('batch', fn($q) => $q->where('temp_receipt', '!=', 0))
            ->with('invoice.customer.admDetails')
            ->get()
            ->pluck('invoice.customer.admDetails.name')
            ->filter()
            ->unique()
            ->values();

        $tempAdmIds = InvoicePayments::whereHas('batch', fn($q) => $q->where('temp_receipt', '!=', 0))
            ->with('invoice.customer.admDetails')
            ->get()
            ->pluck('invoice.customer.admDetails.adm_number')
            ->filter()
            ->unique()
            ->values();

        $tempCustomers = InvoicePayments::whereHas('batch', fn($q) => $q->where('temp_receipt', '!=', 0))
            ->with('invoice.customer')
            ->get()
            ->pluck('invoice.customer.name')
            ->filter()
            ->unique()
            ->values();

        $advanceAdmNames = AdvancedPayment::with('adm.userDetails')
            ->get()
            ->pluck('adm.userDetails.name')
            ->filter()
            ->unique()
            ->values();

        $advanceAdmIds = AdvancedPayment::with('adm.userDetails')
            ->get()
            ->pluck('adm.userDetails.adm_number')
            ->filter()
            ->unique()
            ->values();

        $advanceCustomers = AdvancedPayment::with('customerData')
            ->get()
            ->pluck('customerData.name')
            ->filter()
            ->unique()
            ->values();

        return view('finance::collections.all_receipts', compact(
            'regular_receipts',
            'temp_receipts',
            'advanced_payments',
            'finalAdmNames',
            'finalAdmIds',
            'finalCustomers',
            'tempAdmNames',
            'tempAdmIds',
            'tempCustomers',
            'advanceAdmNames',
            'advanceAdmIds',
            'advanceCustomers',
            'activeTab'
        ));
    }

    public function resend_receipt()
    {
        $request->validate([
            'receipt_id' => 'required|exists:invoice_payments,id',
            'mobile' => 'nullable|string',
            'optional_number' => 'nullable|string',
        ]);

        $id = $request->receipt_id;
        $mobile = $request->optional_number ?: $request->mobile;

        if (!$mobile) {
            return back()->with('error', 'No mobile number provided.');
        }

        $payment = InvoicePayments::findOrFail($id);
        $invoice = Invoices::findOrFail($payment->invoice_id);
        $customer = Customers::where('customer_id', $invoice->customer_id)->firstOrFail();
        $adm = UserDetails::where('adm_number', $customer->adm)->first();

        // Folder for saving duplicate receipts
        $folderPath = public_path('uploads/adm/collections/receipts/duplicates');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        // Check if duplicate already exists
        if ($payment->duplicate_pdf && File::exists(public_path($payment->duplicate_pdf))) {
            // Use existing file
            $filePath = public_path($payment->duplicate_pdf);
        } else {
            // Generate new duplicate PDF
            $pdf_name = 'duplicate_receipt_' . $payment->id . '_' . time() . '.pdf';
            $filePath = $folderPath . '/' . $pdf_name;

            // Select correct receipt view by payment type
            switch ($payment->type) {
                case 'cash':
                    $view = 'pdfs.collections.receipts.cash';
                    break;
                case 'fund-transfer':
                    $view = 'pdfs.collections.receipts.fund-transfer';
                    break;
                case 'cheque':
                    $view = 'pdfs.collections.receipts.cheque';
                    break;
                case 'card':
                    $view = 'pdfs.collections.receipts.card';
                    break;
                default:
                    return back()->with('error', 'Invalid payment type');
            }

            // Generate and save PDF
            $pdf = PDF::loadView($view, [
                'is_duplicate' => 1,
                'payment' => $payment,
                'invoice' => $invoice,
                'customer' => $customer,
                'adm' => $adm
            ])->setPaper('a4', 'portrait');

            $pdf->save($filePath);

            $payment->duplicate_pdf = 'uploads/adm/collections/receipts/duplicates/' . $pdf_name;
            $payment->save();
        }

        // Send email with the duplicate PDF attachment
        if ($customer->email) {
            Mail::to($customer->email)->send(new SendReceiptMail($payment, $filePath));
        }

        return back()->with('success', 'Receipt resent successfully to the customer.');
    }

    public function remove_advanced_payment($id)
    {
        AdvancedPayment::where('id', $id)->delete();
        return back()->with('success', 'Advanced Payment Removed');
    }

    public function all_collections()
    {
        // Use paginate instead of get()
        $collections = InvoicePaymentBatches::with(['payments', 'admDetails'])
            ->orderBy('created_at', 'desc')
            ->paginate(10) // 10 per page, adjust as needed
            ->through(function ($batch) {
                return [
                    'collection_id' => $batch->id,
                    'collection_date' => $batch->created_at->format('Y-m-d'),
                    'adm_number' => $batch->admDetails->adm_number ?? 'N/A',
                    'adm_name' => $batch->admDetails->name ?? 'N/A',
                    'division' => $batch->division ?? null,
                    'customers' => $batch->payments
                        ->pluck('invoice.customer.name')
                        ->filter()
                        ->unique()
                        ->values()
                        ->toArray(),
                    'total_collected_amount' => $batch->payments->sum('final_payment'),
                ];
            });

        // Initialize filters
        $filters = [
            'search' => '',
            'adm_names' => [],
            'adm_ids' => [],
            'customers' => [],
            'divisions' => [],
            'date_range' => '',
        ];

        return view('finance::collections.all_collections', compact('collections', 'filters'));
    }

    public function collection_details($id)
    {
        // Validate and fetch the batch (collection)
        $batch = InvoicePaymentBatches::with([
            'payments.invoice.customer',
        ])->findOrFail($id);

        // Transform data for view
        $payments = $batch->payments->map(function ($payment) {
            return [
                'receipt_no' => $payment->id, // Receipt number = id from invoice_payments
                'customer_name' => optional($payment->invoice->customer)->name ?? 'N/A',
                'invoice_no' => $payment->invoice_id,
                'status' => $payment->status ?? 'N/A',
                'payment_method' => $payment->type ?? 'N/A',
                'amount' => number_format($payment->final_payment ?? 0, 2),
            ];
        });

        return view('finance::collections.collection_details', [
            'batch' => $batch,
            'payments' => $payments,
        ]);
    }

    public function search_collections(Request $request)
    {
        $search = $request->input('search');

        // Load all collections
        $allCollections = InvoicePaymentBatches::with(['payments.invoice.customer', 'admDetails'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Manual filtering (same pattern as Cash Deposits)
        $filtered = $allCollections->filter(function ($batch) use ($search) {

            $search = strtolower($search);

            // Check Collection ID
            if (str_contains((string)$batch->id, $search)) return true;

            // Check ADM details
            if ($batch->admDetails) {
                if (
                    str_contains(strtolower($batch->admDetails->adm_number), $search) ||
                    str_contains(strtolower($batch->admDetails->name), $search)
                ) return true;
            }

            // Check invoice number & customer name
            foreach ($batch->payments as $payment) {

                // invoice number
                if (str_contains(strtolower((string)$payment->invoice_id), $search)) {
                    return true;
                }

                // customer
                $customer = $payment->invoice->customer ?? null;

                if ($customer) {
                    if (str_contains(strtolower($customer->name), $search)) {
                        return true;
                    }
                }
            }

            return false;
        });

        // Manual pagination (same as cash deposits)
        $page = request('page', 1);
        $perPage = 10;

        $collections = new \Illuminate\Pagination\LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Transform dataset for Blade
        $collections->getCollection()->transform(function ($batch) {
            return [
                'collection_id' => $batch->id,
                'collection_date' => $batch->created_at->format('Y-m-d'),
                'adm_number' => $batch->admDetails->adm_number ?? 'N/A',
                'adm_name' => $batch->admDetails->name ?? 'N/A',
                'division' => $batch->division,
                'customers' => $batch->payments
                    ->pluck('invoice.customer.name')
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray(),
                'total_collected_amount' => $batch->payments->sum('final_payment'),
            ];
        });

        // Keep search value for Blade
        $filters = ['search' => $search];

        return view('finance::collections.all_collections', compact('collections', 'filters'));
    }

    public function filter_collections(Request $request)
    {
        $query = InvoicePaymentBatches::with(['payments.invoice.customer', 'admDetails'])
            ->orderBy('created_at', 'desc');

        // Filter by ADM Names
        if ($request->filled('adm_names')) {
            $query->whereHas('admDetails', function ($q) use ($request) {
                $q->whereIn('name', $request->adm_names);
            });
        }

        // Filter by ADM Numbers / IDs
        if ($request->filled('adm_ids')) {
            $query->whereHas('admDetails', function ($q) use ($request) {
                $q->whereIn('adm_number', $request->adm_ids);
            });
        }

        // Filter by Customers
        if ($request->filled('customers')) {
            $query->whereHas('payments.invoice.customer', function ($q) use ($request) {
                $q->whereIn('name', $request->customers);
            });
        }

        // Filter by Divisions
        if ($request->filled('divisions')) {
            $query->whereHas('admDetails', function ($q) use ($request) {
                $q->whereIn('division', $request->divisions);
            });
        }

        // Filter by Date Range (your working code)
        if ($request->filled('date_range')) {
            $range = trim($request->date_range);

            // Support both "YYYY-MM-DD to YYYY-MM-DD" and "YYYY-MM-DD - YYYY-MM-DD"
            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } elseif (str_contains($range, '-')) {
                [$start, $end] = array_map('trim', explode('-', $range, 2)); // limit to 2 parts
            } else {
                $start = $end = $range;
            }

            if (!empty($start) && !empty($end)) {
                $query->whereBetween('created_at', [
                    date('Y-m-d 00:00:00', strtotime($start)),
                    date('Y-m-d 23:59:59', strtotime($end)),
                ]);
            }
        }

        // Pagination + transform
        $collections = $query->paginate(10)
            ->through(function ($batch) {
                return [
                    'collection_id' => $batch->id,
                    'collection_date' => $batch->created_at->format('Y-m-d'),
                    'adm_number' => $batch->admDetails->adm_number ?? 'N/A',
                    'adm_name' => $batch->admDetails->name ?? 'N/A',
                    'division' => $batch->division ?? null,
                    'customers' => $batch->payments
                        ->pluck('invoice.customer.name')
                        ->filter()
                        ->unique()
                        ->values()
                        ->toArray(),
                    'total_collected_amount' => $batch->payments->sum('final_payment'),
                ];
            });

        // Pass back filters to preserve in Blade
        $filters = $request->only(['adm_names', 'adm_ids', 'customers', 'divisions', 'date_range']);

        return view('finance::collections.all_collections', compact('collections', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
