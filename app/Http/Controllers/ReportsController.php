<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetOffs;
use App\Models\WriteOffs;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\CreditNote;
use App\Models\ExtraPayment;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.reports');
    }

    private function formatGlBreakdown($glBreakdown)
    {
        if (empty($glBreakdown) || !is_array($glBreakdown)) {
            return '';
        }

        $lines = [];

        foreach ($glBreakdown as $gl) {
            if (!empty($gl['name']) && isset($gl['amount'])) {
                $lines[] = "{$gl['name']}: {$gl['amount']}";
            }
        }

        // NEW LINE per GL
        return implode("\n", $lines);
    }

    public function download(Request $request)
    {
        $request->validate([
            'type'      => 'required|in:SO,WO',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        return $request->type === 'SO'
            ? $this->downloadSetOffs($request)
            : $this->downloadWriteOffs($request);
    }

    private function downloadSetOffs($request)
    {
        $setOffs = SetOffs::whereBetween('created_at', [
            $request->from_date,
            $request->to_date
        ])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // Headers
        $headers = [
            'SO ID',
            'SO Date',
            'SO Amount',
            'Customer Name',
            'Customer Number',
            'GL Breakdown',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $row = 2;

        foreach ($setOffs as $setOff) {

            $customerNames = [];
            $customerIds   = [];

            // Invoices
            foreach ($setOff->invoice_or_cheque_no ?? [] as $item) {
                $invoice = Invoices::where('invoice_or_cheque_no', $item['invoice'])->first();
                if ($invoice) {
                    $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                    if ($customer) {
                        $customerNames[$customer->customer_id] = $customer->name;
                        $customerIds[$customer->customer_id]   = $customer->customer_id;
                    }
                }
            }

            // Credit Notes & Extra Payments
            foreach ($setOff->extraPayment_or_creditNote_no ?? [] as $item) {

                if ($credit = CreditNote::where('credit_note_id', $item['id'])->first()) {
                    $customerNames[$credit->customer_id] = $credit->customer_name;
                    $customerIds[$credit->customer_id]   = $credit->customer_id;
                }

                if ($extra = ExtraPayment::where('extra_payment_id', $item['id'])->first()) {
                    $customerNames[$extra->customer_id] = $extra->customer_name;
                    $customerIds[$extra->customer_id]   = $extra->customer_id;
                }
            }

            $sheet->setCellValue("A{$row}", $setOff->id);
            $sheet->setCellValue("B{$row}", $setOff->created_at->format('Y-m-d'));
            $sheet->setCellValue("C{$row}", $setOff->final_amount);
            $sheet->setCellValue("D{$row}", implode(', ', $customerNames));
            $sheet->setCellValue("E{$row}", implode(', ', $customerIds));
            $sheet->setCellValue(
                "F{$row}",
                $this->formatGlBreakdown($setOff->gl_breakdown)
            );

            $sheet->getStyle("F{$row}")
      ->getAlignment()
      ->setWrapText(true);

            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, "set_off_report_{$request->from_date}_to_{$request->to_date}.xlsx");
    }

    private function downloadWriteOffs($request)
    {
        $writeOffs = WriteOffs::whereBetween('created_at', [
            $request->from_date,
            $request->to_date
        ])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        $headers = [
            'WO ID',
            'WO Date',
            'WO Amount',
            'Customer Name',
            'Customer Number',
            'GL Breakdown',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $row = 2;

        foreach ($writeOffs as $writeOff) {

            $customerNames = [];
            $customerIds   = [];

            foreach ($writeOff->invoice_or_cheque_no ?? [] as $item) {
                $invoice = Invoices::where('invoice_or_cheque_no', $item['invoice'])->first();
                if ($invoice) {
                    $customer = Customers::where('customer_id', $invoice->customer_id)->first();
                    if ($customer) {
                        $customerNames[$customer->customer_id] = $customer->name;
                        $customerIds[$customer->customer_id]   = $customer->customer_id;
                    }
                }
            }

            foreach ($writeOff->extraPayment_or_creditNote_no ?? [] as $item) {

                if ($credit = CreditNote::where('credit_note_id', $item['id'])->first()) {
                    $customerNames[$credit->customer_id] = $credit->customer_name;
                    $customerIds[$credit->customer_id]   = $credit->customer_id;
                }

                if ($extra = ExtraPayment::where('extra_payment_id', $item['id'])->first()) {
                    $customerNames[$extra->customer_id] = $extra->customer_name;
                    $customerIds[$extra->customer_id]   = $extra->customer_id;
                }
            }

            $sheet->setCellValue("A{$row}", $writeOff->id);
            $sheet->setCellValue("B{$row}", $writeOff->created_at->format('Y-m-d'));
            $sheet->setCellValue("C{$row}", $writeOff->final_amount);
            $sheet->setCellValue("D{$row}", implode(', ', $customerNames));
            $sheet->setCellValue("E{$row}", implode(', ', $customerIds));
            $sheet->setCellValue(
                "F{$row}",
                $this->formatGlBreakdown($writeOff->gl_breakdown)
            );

            $sheet->getStyle("F{$row}")
      ->getAlignment()
      ->setWrapText(true);

            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, "write_off_report_{$request->from_date}_to_{$request->to_date}.xlsx");
    }
}
