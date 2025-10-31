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
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;

use App\Models\Bank;
use App\Models\Branch;

use File;
use Mail;
use Image;

use Illuminate\Support\Facades\Log;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  
public function get_branches(Request $request)
{
    $bankId = $request->bank_id;
    $branches = Branch::where('BankID', $bankId)->get(['BranchCode', 'BranchName']);
    return response()->json($branches);
}

}
