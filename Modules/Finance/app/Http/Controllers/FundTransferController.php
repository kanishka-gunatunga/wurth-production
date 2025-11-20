<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvoicePayments;
use Illuminate\Http\Request;

class FundTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = InvoicePayments::with([
            'invoice.customer.admDetails'
        ])
            ->where('type', 'fund-transfer');

        // Search
        if ($request->search) {
            $search = $request->search;

            $query->whereHas('invoice.customer', function ($q) use ($search) {
                $q->where('customer_id', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('adm', 'like', "%$search%");
            });

            $query->orWhereHas('invoice.customer.admDetails', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $fundTransfers = $query->paginate(10);

        return view('finance::fund_transfer.fund_transfers', compact('fundTransfers'));
    }

    public function show($id)
    {
        $deposit = InvoicePayments::with([
            'invoice.customer.admDetails'
        ])->findOrFail($id);

        return view('finance::fund_transfer.fund_transfer_details', compact('deposit'));
    }
}
