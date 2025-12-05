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
use App\Models\RolePermissions;
use App\Models\ActivtiyLog;
use App\Services\ActivitLogService;

use File;
use Mail;
use Image;
use PDF;

class AccessController extends Controller
{
   
    public function access_control(Request $request)
    {
        if($request->isMethod('get')){
        return view('access.access_control');
        }
        if($request->isMethod('post')){
            if($request->user_role == '1'){
                $role = 'System Administrator';
                }   
                if($request->user_role == '2'){
                $role = 'Head of Division';
                } 
                if($request->user_role == '3'){
                $role = 'Regional Sales Manager';
                } 
                if($request->user_role == '4'){
                $role = 'Area Sales Manager';
                } 
                if($request->user_role == '5'){
                $role = 'Team Leader';
                } 
                if($request->user_role == '6'){
                $role = 'ADM (Sales Rep)';
                } 
                if($request->user_role == '7'){
                $role = 'Finance Manager';
                } 
                if($request->user_role == '8'){
                $role = 'Recovery Manager';
                } 

            if(RolePermissions::where("user_role", $request->user_role)->exists()){
                $permission =  RolePermissions::where("user_role", $request->user_role)->first();
                $permission->permissions = $request->permissions;
                $permission->update();

               ActivitLogService::log('permission', $role . ' permissions updated');
            }
            else{
                $permission = new RolePermissions();
                $permission->user_role = $request->user_role;
                $permission->permissions = $request->permissions;
                $permission->save();

                ActivitLogService::log('permission', $role . ' permissions added');
            }

            

            return back()->with('success', 'Role Permissions Updated');
        }
    }

public function get_role_permissions(Request $request)
{

    $request->validate([
        'user_role' => 'required|integer',
    ]);

    $user_role = $request->input('user_role');

    $rolePermissions = RolePermissions::where('user_role', $user_role)->first();

    $permissions = $rolePermissions ? $rolePermissions->permissions : [];

    return response()->json($permissions);
}
}
