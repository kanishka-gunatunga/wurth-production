<?php

namespace App\Exports;

use App\Models\InvoicePayments;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinalReceiptsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = InvoicePayments::with(['invoice.customer.admDetails', 'batch'])
            ->whereHas('batch', fn ($q) => $q->where('temp_receipt', 0));

        /* 🔍 SAME FILTERS AS FINAL TAB */

        if ($this->request->filled('final_search')) {
            $search = $this->request->final_search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('invoice.customer', fn ($q2) =>
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('adm', 'like', "%{$search}%")
                  );
            });
        }

        if ($this->request->filled('final_status')) {
            $query->where('status', $this->request->final_status);
        }

        if ($this->request->filled('final_date_range')) {
            $range = trim($this->request->final_date_range);

            if (str_contains($range, 'to')) {
                [$start, $end] = array_map('trim', explode('to', $range));
            } else {
                [$start, $end] = array_map('trim', explode('-', $range));
            }

            $query->whereBetween('created_at', [
                date('Y-m-d 00:00:00', strtotime($start)),
                date('Y-m-d 23:59:59', strtotime($end)),
            ]);
        }

        return $query->get()->map(function ($p) {
            return [
                'Customer Name' => $p->invoice->customer->name ?? 'N/A',
                'ADM Name'      => $p->invoice->customer->admDetails->name ?? 'N/A',
                'ADM Number'    => $p->invoice->customer->adm ?? 'N/A',
                'Receipt No'    => $p->id,
                'Issue Date'    => $p->created_at->format('Y-m-d'),
                'Amount'        => number_format($p->amount, 2),
                'Status'        => ucfirst($p->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'ADM Name',
            'ADM Number',
            'Receipt Number',
            'Issue Date',
            'Amount',
            'Status',
        ];
    }
}
