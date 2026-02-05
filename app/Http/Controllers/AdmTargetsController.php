<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Invoices; 
use App\Models\ADMTargets; 
use Carbon\Carbon;
use App\Models\CreditNote;
use App\Services\ActivitLogService;
class AdmTargetsController extends Controller
{
   
   public function importAdmTargets(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:10240',
    ]);

    try {
        $file = $request->file('file');
         $fileName = $file->getClientOriginalName();
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) <= 1) {
            return back()->with('fail', 'Excel file is empty!');
        }

        // Header row mapping
        $header = array_map('strtolower', $rows[0]);
        $requiredColumns = [
            'adm no',
            'target',
        ];

        if (count(array_diff($requiredColumns, $header)) > 0) {
            return back()->with('fail', 'Invalid Excel format. Missing required columns.');
        }

        $successCount = 0;
        $duplicateCreditNotes = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = array_combine($header, $rows[$i]);

            // Find or create customer
            $target = ADMTargets::where('adm_no', $row['adm no'])->where('year_and_month', date('Y-m'))->first();

            if (!$target) {
                $target = new ADMTargets();
                $target->adm_no = $row['adm no'];
                $target->target = $row['target'] ?? 0;
                $target->year_and_month = date('Y-m');
                $target->save();
            }

            
            $successCount++;
        }

        $msg = "{$successCount} records successfully inserted.";

        ActivitLogService::log('import', 'adm targets imported from file - ' . $fileName);

        return back()->with('success', $msg);

    } catch (\Exception $e) {
        return back()->with('fail', 'Error during upload. Please try again! ' . $e->getMessage());
    }
}

}
