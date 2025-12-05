<?php

namespace App\Http\Controllers;

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
use Carbon\Carbon;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Divisions;
use App\Services\ActivitLogService;

use File;
use Mail;
use Image;
use PDF;

class DivisionController extends Controller
{
   
    public function division_managment(Request $request)
{
    $search = $request->input('search');

    // Base query with relations
    $query = Divisions::with(['userDetails.user', 'head.user']);

    // Apply search filter if keyword is given
    if (!empty($search)) {
        $query->where('division_name', 'like', "%{$search}%")
              ->orWhereHas('userDetails', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
    }

    // Paginate final results
    $divisions = $query->paginate(15);

    // Keep search term in pagination links
    $divisions->appends(['search' => $search]);

    return view('division.division_managment', [
        'divisions' => $divisions,
    ]);
}



    public function add_new_division(Request $request)
    { if($request->isMethod('get')){
        $division_heads = User::where('user_role', '2')->with('userDetails')->get();
        return view('division.add_new_division',['division_heads' => $division_heads]);
    }
    if($request->isMethod('post')){
        
        $request->validate([
            'division_name'   => 'required',
        ]);

           $division = new Divisions();
           $division->division_name = $request->division_name;
           $division->head_of_division = $request->head_of_division;
           $division->division_description = $request->division_description;
           $division->registered_date = date("Y-m-d");
           $division->status = 'active';
           $division->save();

        ActivitLogService::log('division', $request->division_name . ' - new division added');

        return back()->with('success', 'Division Successfully Added');

    }

    }
public function deactivate_division($id)
{
    $division = Divisions::find($id);

    if (!$division) {
        return back()->with('error', 'Division not found.');
    }

    DB::beginTransaction();
    try {
        // Step 1: Deactivate division
        $division->status = "inactive";
        $division->update();

        // Step 2: Get all users in this division
        $userDetails = UserDetails::where('division', $division->id)->get();

        foreach ($userDetails as $detail) {
            // Deactivate the user in users table
            User::where('id', $detail->user_id)->update(['status' => 'inactive']);
        }

        // Log activity
        ActivitLogService::log(
            'division', 
            $division->division_name . ' - division deactivated & users deactivated'
        );

        DB::commit();
        return back()->with('success', 'Division and all users inside it have been deactivated.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('fail', 'Something went wrong: ' . $e->getMessage());
    }
}

public function activate_division($id)
{
    $division = Divisions::find($id);

    if (!$division) {
        return back()->with('error', 'Division not found.');
    }

    DB::beginTransaction();
    try {
        // Step 1: Deactivate division
        $division->status = "active";
        $division->update();

        // Step 2: Get all users in this division
        $userDetails = UserDetails::where('division', $division->id)->get();

        foreach ($userDetails as $detail) {
            // Deactivate the user in users table
            User::where('id', $detail->user_id)->update(['status' => 'active']);
        }

        // Log activity
        ActivitLogService::log(
            'division', 
            $division->division_name . ' - division deactivated & users deactivated'
        );

        DB::commit();
        return back()->with('success', 'Division and all users inside it have been deactivated.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('fail', 'Something went wrong: ' . $e->getMessage());
    }
}
public function delete_division($id)
{
    $division = Divisions::find($id);

    if (!$division) {
        return back()->with('error', 'Division not found.');
    }

    DB::beginTransaction();

    try {

        // Soft delete division
        $division->delete();

        // Deactivate users inside this division
        foreach ($division->userDetails as $detail) {
            User::where('id', $detail->user_id)
                ->update(['status' => 'inactive']);
        }

        ActivitLogService::log(
            'division',
            $division->division_name . ' - division deleted & users deactivated'
        );

        DB::commit();

        return back()->with('success', 'Division  deleted and users deactivated.');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

    public function edit_division($id,Request $request)
    {
    if($request->isMethod('get')){
    $division_details = Divisions::where('id',$id)->first();
    $division_heads = User::where('user_role', '2')->with('userDetails')->get();
    return view('division.edit_division', ['division_details' => $division_details,'division_heads' => $division_heads]);
    }
    if($request->isMethod('post')){
        $request->validate([
            'division_name'   => 'required',
        ]);
   
        $division =  Divisions::where('id', '=', $id)->first();;
        $division->division_name = $request->division_name;
        // $division->head_of_division = $request->head_of_division;
        $division->division_description = $request->division_description;
        $division->update();

         ActivitLogService::log('division', $request->division_name . ' - division details updated');

        return back()->with('success', 'Division Details Successfully  Updated');
    }

    }


}
