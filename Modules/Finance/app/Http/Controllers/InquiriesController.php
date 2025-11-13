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
use App\Models\Customers;
use App\Models\Invoices;
use App\Models\Inquiries;

use File;
use Mail;
use Image;
use PDF;

class InquiriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function inquiries()
    {
        $inquiries = Inquiries::with([
            'invoice',
            'customer',
            'user.userDetails'
        ])
            ->orderBy('created_at', 'desc') // newest first
            ->paginate(10); // show 10 per page

        return view('finance::inquiries.index', compact('inquiries'));
    }


    public function details($id)
    {
        $inquiry = Inquiries::with([
            'invoice',
            'customer',
            'user.userDetails'
        ])->findOrFail($id);

        return view('finance::inquiries.details', compact('inquiry'));
    }

    public function approve($id)
    {
        $inquiry = Inquiries::findOrFail($id);
        $inquiry->status = 'Sorted';
        $inquiry->save();

        return response()->json(['success' => true, 'status' => $inquiry->status]);
    }

    public function reject($id)
    {
        $inquiry = Inquiries::findOrFail($id);
        $inquiry->status = 'Rejected';
        $inquiry->save();

        return response()->json(['success' => true, 'status' => $inquiry->status]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $inquiries = Inquiries::with(['invoice', 'customer', 'user.userDetails'])
            ->when($query, function ($q) use ($query) {
                $q->where('id', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('finance::inquiries.index', compact('inquiries'))
            ->with('searchQuery', $query);
    }

    public function filter(Request $request)
    {
        $admIds = $request->input('adm_ids', []);
        $customers = $request->input('customers', []);
        $types = $request->input('types', []);
        $status = $request->input('status');
        $dateRange = $request->input('date_range'); // e.g. "2025-01-01 to 2025-01-31"

        $inquiries = Inquiries::with(['invoice', 'customer', 'user.userDetails'])
            ->when(!empty($admIds), function ($q) use ($admIds) {
                $q->whereIn('adm_id', $admIds);
            })
            ->when(!empty($customers), function ($q) use ($customers) {
                $q->whereIn('customer', $customers);
            })
            ->when(!empty($types), function ($q) use ($types) {
                $q->whereIn('type', $types);
            })
            ->when(!empty($status), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when(!empty($dateRange), function ($q) use ($dateRange) {
                $dates = explode(' to ', $dateRange);
                if (count($dates) === 2) {
                    $from = trim($dates[0]);
                    $to = trim($dates[1]);
                    $q->whereBetween('created_at', [$from, $to]);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('finance::inquiries.index', compact('inquiries'))
            ->with([
                'filters' => [
                    'adm_ids' => $admIds,
                    'customers' => $customers,
                    'types' => $types,
                    'status' => $status,
                    'date_range' => $dateRange
                ]
            ]);
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
