<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdvancedPayment;

class AdvancedPaymentsController extends Controller
{
    public function index()
    {
        // Fetch data with pagination (10 per page)
        $payments = \App\Models\AdvancedPayment::with(['admDetails', 'customerData'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 👈 Laravel handles pagination automatically

        return view('advanced_payments.index', compact('payments'));
    }

    public function show($id)
    {
        // Find the payment by its ID
        $payment = \App\Models\AdvancedPayment::with(['admDetails', 'customerData'])
            ->findOrFail($id);

        // Pass it to a details view
        return view('advanced_payments.details', compact('payment'));
    }

    public function search(Request $request)
    {
        $query = AdvancedPayment::with(['admDetails', 'customerData']);

        // 🔎 Search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('adm_id', 'like', "%{$search}%")
                    ->orWhereHas('admDetails', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customerData', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 🎯 ADM Name filter
        if ($request->filled('adm_names')) {
            $query->whereHas('admDetails', function ($q) use ($request) {
                $q->whereIn('name', $request->adm_names);
            });
        }

        // 🎯 ADM ID filter
        if ($request->filled('adm_ids')) {
            $query->whereIn('adm_id', $request->adm_ids);
        }

        // 🎯 Customer filter
        if ($request->filled('customers')) {
            $query->whereHas('customerData', function ($q) use ($request) {
                $q->whereIn('name', $request->customers);
            });
        }

        // 🎯 Date range filter (format: YYYY-MM-DD to YYYY-MM-DD)
        if ($request->filled('date_range')) {
            $dates = explode('to', $request->date_range);
            if (count($dates) == 2) {
                $start = trim($dates[0]);
                $end = trim($dates[1]);
                $query->whereBetween('date', [$start, $end]);
            }
        }

        // Return paginated results
        $payments = $query->orderBy('created_at', 'desc')->paginate(10);

        // Keep filter data for dropdowns
        return view('advanced_payments.index', compact('payments'))
            ->with('filters', $request->all());
    }
}
