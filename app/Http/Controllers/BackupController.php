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
use Spatie\Backup\Tasks\Backup\BackupJob;
use Illuminate\Support\Facades\Artisan;

use App\Models\User;
use App\Models\UserDetails;
use App\Models\Divisions;
use App\Services\ActivitLogService;
use App\Models\ActivtiyLog;
use App\Models\Backups;

use File;
use Mail;
use Image;
use PDF;

class BackupController extends Controller
{

public function backup(Request $request)
{
    if ($request->isMethod('get')) {

        $latestBackup = Backups::orderBy('id', 'DESC')->first();

        return view('backup.backup', compact('latestBackup'));
    }

    if ($request->isMethod('post')) {

        $backup = Backups::create([
            'status' => 'pending',
            'created_at' => now(),
        ]);

        try {
            // Run backup
            Artisan::call('backup:run');

            // Get latest file
            $disk = Storage::disk('local');
            $files = $disk->files('Laravel');

            if (empty($files)) {
                throw new \Exception("No backup file generated");
            }

            $latestFile = end($files);
            $fileSize   = $disk->size($latestFile);

            // Update DB
            $backup->update([
                'file_name' => basename($latestFile),
                'path'      => $latestFile,
                'disk'      => 'local',
                'status'    => 'success',
                'size'      => $fileSize,
                'size'      => $fileSize,
            ]);

            ActivitLogService::log('backup', "System backup created successfully. File: " . basename($latestFile));

            return response()->json([
                'success' => true,
                'message' => 'Backup completed successfully!',
                'size'    => $fileSize,
            ]);

        } catch (\Exception $e) {

            $backup->update(['status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Backup failed',
                'error'   => $e->getMessage(),
            ]);
        }
    }
}


}
