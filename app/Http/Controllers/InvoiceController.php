<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Invoices; // ✅ NEW (we now store in invoices table)
use App\Models\Customers; // ✅ For fetching customer IDs
use Carbon\Carbon;
use App\Services\ActivitLogService;
class InvoiceController extends Controller
{
   
   public function importInvoices(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:10240',
    ]);

    try {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) <= 1) {
            return back()->with('fail', 'Excel file is empty!');
        }

        // Header row mapping
        $header = array_map('strtolower', $rows[0]);
        $requiredColumns = [
            'customer',
            'customer name',
            'sales territory',
            '(a-cu) date last invoice',
            'billing document',
            'order number customer',
            'lkr',
        ];

        if (count(array_diff($requiredColumns, $header)) > 0) {
            return back()->with('fail', 'Invalid Excel format. Missing required columns.');
        }

        $successCount = 0;
        $duplicateInvoices = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = array_combine($header, $rows[$i]);

            if (empty($row['customer']) || empty($row['billing document'])) continue;

            // Skip duplicate cheque numbers
            if (Invoices::where('invoice_or_cheque_no', $row['billing document'])->exists()) {
                $duplicateInvoices[] = $row['billing document'];
                continue;
            }

            // Find or create customer
            $customer = Customers::where('customer_id', $row['customer'])->first();

            if (!$customer) {
                $customer = new Customers();
                $customer->customer_id = $row['customer'];
                $customer->name = $row['customer name'] ?? null;
                $customer->adm = $row['sales territory'] ?? null;
                $customer->is_temp = 1; 
                $customer->status = 'active';
                $customer->save();
            }

            $rawAmount = $row['lkr'] ?? 0;
            $rawAmount = (string) $rawAmount;
            $rawAmount = str_replace(',', '', $rawAmount);
            $rawAmount = str_replace('-', '', $rawAmount);
            $amount = (float) $rawAmount;

           $invoiceDate = null;
            if (!empty($row['(a-cu) date last invoice'])) {
                try {
                    $invoiceDate = Carbon::createFromFormat(
                        'm/d/Y',
                        trim($row['(a-cu) date last invoice'])
                    )->format('Y-m-d');
                } catch (\Exception $e) {
                    $invoiceDate = null;
                }
            }
            Invoices::create([
                'type' => 'invoice',
                'invoice_or_cheque_no' => $row['billing document'],
                'customer_id' => $customer->customer_id,
                'amount' =>$amount,
                'invoice_date' => $invoiceDate,
                'reference' => $row['order number customer'] ?? null,
            ]);

            $successCount++;
        }

        $msg = "{$successCount} records successfully inserted.";
        if (!empty($duplicateInvoices)) {
            $msg .= " Skipped duplicates: " . implode(', ', $duplicateInvoices);
        }

        ActivitLogService::log('import', 'invoices imported from file - ' . $fileName);

        return back()->with('success', $msg);

    } catch (\Exception $e) {
        return back()->with('fail', 'Error during upload. Please try again! ' . $e->getMessage());
    }
}

}
