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

use File;
use Mail;
use Image;
use PDF;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dashboard()
    {
        // Fetch the 5 most recent cash deposits
        $recentCashDeposits = \App\Models\Deposits::where('type', 'cash')
            ->orderByDesc('date_time')
            ->take(5)
            ->get()
            ->map(function ($deposit) {
                $admDetails = \App\Models\UserDetails::where('user_id', $deposit->adm_id)->first();

                return [
                    'date' => $deposit->date_time
                        ? date('d M Y', strtotime($deposit->date_time))
                        : 'N/A',
                    'adm_name' => $admDetails->name ?? 'N/A',
                    'adm_number' => $admDetails->adm_number ?? 'N/A',
                    'amount' => number_format($deposit->amount ?? 0, 2),
                ];
            });

        // You can later make the cheque table dynamic too if needed
        return view('finance::dashboard.index', compact('recentCashDeposits'));
    }


    function logout()
    {
        Auth::logout();
        return redirect('/');
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
