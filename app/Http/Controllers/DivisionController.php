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
    $query = Divisions::with('userDetails');

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
            'head_of_division'   => 'required',
        ]);

           $division = new Divisions();
           $division->division_name = $request->division_name;
           $division->head_of_division = $request->head_of_division;
           $division->division_description = $request->division_description;
           $division->registered_date = date("Y-m-d");
           $division->status = 'active';
           $division->save();

        return back()->with('success', 'Division Successfully Added');

    }

    }

    public function deactivate_division($id){
        $division = Divisions::find($id);
        $division->status = "inactive";
        $division->update();
        return back()->with('success', 'Division Deactivated');

    }

    public function activate_division($id){
        $division = Divisions::find($id);
        $division->status = "active";
        $division->update();
        return back()->with('success', 'Division Activated');

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
            'head_of_division'   => 'required',
        ]);
   
        $division =  Divisions::where('id', '=', $id)->first();;
        $division->division_name = $request->division_name;
        $division->head_of_division = $request->head_of_division;
        $division->division_description = $request->division_description;
        $division->update();
        return back()->with('success', 'Division Details Successfully  Updated');
    }

    }


}
