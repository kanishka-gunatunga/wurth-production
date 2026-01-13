<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SetOffs;
use App\Models\WriteOffs;
use App\Models\Invoices;
use App\Models\Customers;
use App\Models\CreditNote;
use App\Models\ExtraPayment;
use App\Models\UserDetails;
use App\Models\Divisions;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $customers = Customers::get();
        $divisions = Divisions::get();
        $users =User::with('userDetails')->get();
        return view('reports.reports', compact('customers','divisions','users'));
    }
    public function download_report($type, Request $request)
    {
        if($type == 'ara'){
            return $this->downloadARAReport($request);
        }
         if($type == 'yoo'){
            return $this->downloadYOOReport($request);
        }
        if($type == 'mom'){ 
            return $this->downloadMOMReport($request);
        }
        if($type == 'odb'){
            return $this->downloadODBReport($request);
        }
        if($type == 'dso'){
            return $this->downloadDSOReport($request);
        }
    }
   private function downloadARAReport(Request $request)
{
    $fromDate   = $request->from;
    $toDate     = $request->to;
    $customers  = $request->customers;

    $invoices = Invoices::with(['customer.admDetails', 'customer.secondaryAdm', 'payments'])
        ->whereBetween('invoice_date', [$fromDate, $toDate])
        ->when(!empty($customers), function ($q) use ($customers) {
            $q->whereIn('customer_id', (array) $customers);
        })
        ->get();

    $customerNos = [];
    $customerNames = [];
    foreach ($invoices as $invoice) {    
    if ($invoice->customer) {
            $customerNos[] = $invoice->customer->customer_id;
            $customerNames[] = $invoice->customer->name;
        }
    }
    $customerNoStr = implode(', ', $customerNos);
    $customerNameStr = implode(', ', $customerNames);

    $data = [];

    // ================== ARA HEADER LINES ==================
    $data[] = ['Accounts Receivable Aging (ARA)'];
    $data[] = ['Customer Name:', $customerNoStr]; // you can fill later
    $data[] = ['Customer No:', $customerNameStr];
    $data[] = ['Outstanding Statement as at:', now()->format('Y-m-d')];
    $data[] = []; // blank row

    // ============= TABLE HEADERS MATCHING YOUR REQUIRED FORMAT =============
    $data[] = [
        'ADM No',
        'ADM Name',
        'Document Date',
        'Reference',
        'Document Amount',
        'Outstanding Amount',
        'Days Arrears',
        '0–30',
        '31–60',
        '61–90',
        '91–120',
        '121+',
        'Paid & PDC / Not Deposited Bank',
        'Chq No / Rcp No / Deposit Date',
        'Comments / Notification'
    ];

    // ================== YOUR EXISTING DATA LOOP (KEPT) ==================
    $today = now();
    $totalInvoiceAmount = 0;
    $totalOutstanding   = 0;

    foreach ($invoices as $invoice) {

    $invoiceDate = \Carbon\Carbon::parse($invoice->invoice_date);
    $age = $invoiceDate->diffInDays($today);

    $outstanding_amount = $invoice->amount - ($invoice->paid_amount ?? 0);

    $totalInvoiceAmount += $invoice->amount;
    $totalOutstanding   += $outstanding_amount;

    // Filter pending payments & post-dated cheques
    $pendingPayments = $invoice->payments
        ->filter(function ($payment) {
            return in_array($payment->status, ['pending', 'over_to_finance']) 
                || ($payment->type === 'cheque' && $payment->post_dated);
        });

    // Total amount for Paid & PDC / Not Deposited Bank column
    $pendingAmount = $pendingPayments->sum(function ($payment) {
        if ($payment->type === 'cheque' && $payment->cheque_amount) {
            return $payment->cheque_amount;
        }
        return $payment->final_payment ?? 0;
    });

    // Build Chq No / Rcp No / Deposit Date column
    $paymentDetails = $pendingPayments->map(function ($payment) {
        $details = $payment->invoice_id . ' / ' . \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d');
        if ($payment->type === 'cheque') {
            $details .= ' / ' . ($payment->cheque_number ?? '-') . ' / ' . ($payment->cheque_date ?? '-');
        }
        return $details;
    })->implode(', ');

    // aging bucket columns
    $b0_30 = $b31_60 = $b61_90 = $b91_120 = $b121 = '';

    if ($age <= 30) $b0_30 = $outstanding_amount;
    elseif ($age <= 60) $b31_60 = $outstanding_amount;
    elseif ($age <= 90) $b61_90 = $outstanding_amount;
    elseif ($age <= 120) $b91_120 = $outstanding_amount;
    else $b121 = $outstanding_amount;

    $data[] = [
        $invoice->customer->admDetails->adm_number ?? '-',
        $invoice->customer->admDetails->name ?? '-',
        $invoiceDate->format('Y-m-d'),
        $invoice->invoice_or_cheque_no,
        $invoice->amount,
        $outstanding_amount,
        number_format($age),
        $b0_30,
        $b31_60,
        $b61_90,
        $b91_120,
        $b121,
        $pendingAmount,       
        $paymentDetails,      
        '',                   
    ];
}

    $data[] = [
        'TOTAL',
        '',
        '',
        '',
        $totalInvoiceAmount,
        $totalOutstanding,
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
    ];

    // ================= ANONYMOUS EXPORT WITH TITLE =================
    $export = new class($data) implements FromArray, WithTitle, WithHeadings {

        protected $data;

        public function __construct($data)
        {
            $this->data = $data;
        }

        // only table headings row, Maatwebsite requirement
        public function headings(): array
        {
            return [];
        }

        public function title(): string
        {
            return 'Accounts Receivable Aging (ARA)';
        }

        public function array(): array
        {
            return $this->data;
        }
    };

    return Excel::download(
        $export,
        'ARA_Report_' . now()->format('Ymd_His') . '.xlsx'
    );
}
public function downloadYOOReport(Request $request)
{
    $years      = $request->years ?? [];
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->tls ?? [];
    $adms       = $request->adms ?? [];
    // dd($adms);
    if(empty($years)){
        return back()->with('fail', 'Please select at least one year');
    }

    /**
     * ------------------------------------------------------------------
     * 1) Resolve which ADMs should be included based on hierarchy
     * ------------------------------------------------------------------
     */

    // --- CASE 1: ADMs directly selected ---
    if(!empty($adms)){
        $admIds = $adms;
    }

    // --- CASE 2: Team leaders selected -> find ADMs under them ---
    elseif(!empty($tls)){
        $admIds = UserDetails::whereIn('supervisor', $tls)
            ->orWhereIn('second_supervisor', $tls)
            ->pluck('user_id');
    }

    // --- CASE 3: ASMs selected ---
    elseif(!empty($asms)){
        // find team leaders under ASM (supervisor/2nd)
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
            ->orWhereIn('second_supervisor', $asms)
            ->pluck('user_id');

        // find ADMs under those TLs
        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    }

    // --- CASE 4: RSMs selected ---
    elseif(!empty($rsms)){
        // ASMs under RSM
        $asmIds = UserDetails::whereIn('supervisor', $rsms)
            ->orWhereIn('second_supervisor', $rsms)
            ->pluck('user_id');

        // TLs under ASM
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
            ->orWhereIn('second_supervisor', $asmIds)
            ->pluck('user_id');

        // ADMs under TLs
        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    }

    // --- CASE 5: Division selected ---
    elseif(!empty($divisions)){
        // RSMs under division
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->pluck('user_id');

        // ASMs under RSM
        $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
            ->orWhereIn('second_supervisor', $rsmIds)
            ->pluck('user_id');

        // TLs under ASM
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
            ->orWhereIn('second_supervisor', $asmIds)
            ->pluck('user_id');

        // ADMs under TLs
        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    }

    else{
        // nothing selected -> ALL ADMs
        $admIds = UserDetails::pluck('user_id');
    }

    /**
     * ------------------------------------------------------------------
     * 2) Get invoices for those ADMs + selected years
     * ------------------------------------------------------------------
     */

    $admNumbers = UserDetails::whereIn('user_id', $admIds)
    ->pluck('adm_number') // this is what is mapped in customers
    ->filter() 
    ->toArray();

   $invoices = Invoices::with(['customer.admDetails'])
    ->whereHas('customer', function ($q) use ($admNumbers) {
        $q->whereIn('adm', $admNumbers)
          ->orWhereIn('secondary_adm', $admNumbers);
    })
    ->when(!empty($years), function ($q) use ($years) {
        $q->whereIn(DB::raw('YEAR(invoice_date)'), $years);
    })
    ->get();

    /**
     * ------------------------------------------------------------------
     * 3) Build ADM x Year pivot
     * ------------------------------------------------------------------
     */

    $pivot = [];
    $allYears = collect($years)->sort()->values()->all();

    foreach ($invoices as $inv){

        $year = \Carbon\Carbon::parse($inv->invoice_date)->format('Y');

        $admName = $inv->customer->admDetails->name ?? 'UNKNOWN';
        $admNo   = $inv->customer->admDetails->adm_number ?? '';

        $outstanding = $inv->amount - ($inv->paid_amount ?? 0);

        if (!isset($pivot[$admNo])) {
            $pivot[$admNo] = array_fill_keys($allYears, 0);
            $pivot[$admNo]['name'] = $admName;
            $pivot[$admNo]['grand_total'] = 0;
            $pivot[$admNo]['over_90'] = 0;
        }

        $pivot[$admNo][$year] += $outstanding;
        $pivot[$admNo]['grand_total'] += $outstanding;

        // aging > 90 days
        $age = \Carbon\Carbon::parse($inv->invoice_date)->diffInDays(now());
        if($age > 90){
            $pivot[$admNo]['over_90'] += $outstanding;
        }
    }

    /**
     * ------------------------------------------------------------------
     * 4) Export formatting
     * ------------------------------------------------------------------
     */

    $data = [];

    $data[] = ['Year-on-Year Outstanding (YOO)'];
    $data[] = [];
    $data[] = [];

    $header = ['ADM No','ADM Name'];
    foreach ($allYears as $y) $header[] = $y;
    $header[] = 'Grand Total';
    $header[] = 'Total Over 90 Days';

    $data[] = $header;

    foreach ($pivot as $admNo => $row){

        $excelRow = [$admNo, $row['name']];

        foreach ($allYears as $y){
            $excelRow[] = round($row[$y], 2);
        }

        $excelRow[] = round($row['grand_total'], 2);
        $excelRow[] = round($row['over_90'], 2);

        $data[] = $excelRow;
    }

    $grandTotalsByYear = array_fill_keys($allYears, 0);
    $grandTotalAll = 0;
    $grandTotalOver90 = 0;

    foreach ($pivot as $admNo => $row) {
        foreach ($allYears as $y) {
            $grandTotalsByYear[$y] += $row[$y];
        }
        $grandTotalAll += $row['grand_total'];
        $grandTotalOver90 += $row['over_90'];
    }

    // Build the total row
    $totalRow = ['Grand Total', ''];
    foreach ($allYears as $y) {
        $totalRow[] = round($grandTotalsByYear[$y], 2);
    }
    $totalRow[] = round($grandTotalAll, 2);
    $totalRow[] = round($grandTotalOver90, 2);

    // Append to your Excel data
    $data[] = $totalRow;
    return Excel::download(
        new class($data) implements FromArray, WithTitle {
            public function __construct(public $data){}
            public function array(): array { return $this->data; }
            public function title(): string { return 'YOO'; }
        },
        'YOO_Report_'.now()->format('Ymd_His').'.xlsx'
    );
}

public function downloadMOMReport(Request $request)
{
    $years      = $request->years ?? [];
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->teamleaders ?? [];
    $adms       = $request->adms ?? [];

    if(empty($years)){
        return back()->with('fail', 'Please select at least one year');
    }

    // ------------------ Get ADM numbers based on filters ------------------
    $admQuery = UserDetails::query();

    if(!empty($adms)){
        $admQuery->whereIn('user_id', $adms);
    } elseif(!empty($tls)) {
        // get ADMs under selected TLs
        $admQuery->where(function($q) use ($tls) {
            $q->whereIn('supervisor', $tls)->orWhereIn('second_supervisor', $tls);
        });
    } elseif(!empty($asms)) {
        // get TLs under ASM, then ADMs under TLs
        $tlIds = UserDetails::where(function($q) use ($asms){
            $q->whereIn('supervisor', $asms)->orWhereIn('second_supervisor', $asms);
        })->pluck('user_id')->toArray();

        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)->orWhereIn('second_supervisor', $tlIds);
        });
    } elseif(!empty($rsms)) {
        // get ASMs under RSMs, then TLs under ASMs, then ADMs under TLs
        $asmIds = UserDetails::where(function($q) use ($rsms){
            $q->whereIn('supervisor', $rsms)->orWhereIn('second_supervisor', $rsms);
        })->pluck('user_id')->toArray();

        $tlIds = UserDetails::where(function($q) use ($asmIds){
            $q->whereIn('supervisor', $asmIds)->orWhereIn('second_supervisor', $asmIds);
        })->pluck('user_id')->toArray();

        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)->orWhereIn('second_supervisor', $tlIds);
        });
    } elseif(!empty($divisions)) {
        // get RSMs under Division, then ASMs → TLs → ADMs
        $rsmIds = UserDetails::whereIn('division', $divisions)->pluck('user_id')->toArray();

        $asmIds = UserDetails::where(function($q) use ($rsmIds){
            $q->whereIn('supervisor', $rsmIds)->orWhereIn('second_supervisor', $rsmIds);
        })->pluck('user_id')->toArray();

        $tlIds = UserDetails::where(function($q) use ($asmIds){
            $q->whereIn('supervisor', $asmIds)->orWhereIn('second_supervisor', $asmIds);
        })->pluck('user_id')->toArray();

        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)->orWhereIn('second_supervisor', $tlIds);
        });
    }

    $admNumbers = $admQuery->pluck('adm_number')->filter()->toArray();

    // ------------------ Fetch invoices for these ADMs ------------------
    $invoices = Invoices::with(['customer'])
        ->whereHas('customer', function ($q) use ($admNumbers) {
            $q->whereIn('adm', $admNumbers)
              ->orWhereIn('secondary_adm', $admNumbers);
        })
        ->when(!empty($years), function ($q) use ($years) {
            $q->whereIn(DB::raw('YEAR(invoice_date)'), $years);
        })
        ->get();

    // ------------------ Build pivot: Month x Year ------------------
    $allYears = collect($years)->sort()->values()->all();

    // Initialize pivot: [month][year] = 0
    $pivot = [];
    foreach(range(1,12) as $m){
        foreach($allYears as $y){
            $pivot[$m][$y] = 0;
        }
        $pivot[$m]['grand_total'] = 0;
    }

    foreach($invoices as $inv){
        $year = \Carbon\Carbon::parse($inv->invoice_date)->format('Y');
        $month = (int)\Carbon\Carbon::parse($inv->invoice_date)->format('n'); // 1-12

        $outstanding = $inv->amount - ($inv->paid_amount ?? 0);

        $pivot[$month][$year] += $outstanding;
        $pivot[$month]['grand_total'] += $outstanding;
    }

    // ------------------ Build Excel Data ------------------
    $data = [];
    $data[] = ['Month-on-Month Outstanding (MOM)'];
    $data[] = [];
    $data[] = [];

    // Header
    $header = ['Month'];
    foreach($allYears as $y) $header[] = $y;
    $header[] = 'Grand Total';
    $data[] = $header;

    $grandTotalsByYear = array_fill_keys($allYears, 0);
    $grandTotalAll = 0;

    foreach($pivot as $monthNum => $row){
        $monthName = \Carbon\Carbon::create()->month($monthNum)->format('F');

        $excelRow = [$monthName];
        foreach($allYears as $y){
            $excelRow[] = round($row[$y],2);
            $grandTotalsByYear[$y] += $row[$y];
        }
        $excelRow[] = round($row['grand_total'],2);
        $grandTotalAll += $row['grand_total'];

        $data[] = $excelRow;
    }

    // ------------------ Grand Total Row ------------------
    $totalRow = ['Grand Total'];
    foreach($allYears as $y){
        $totalRow[] = round($grandTotalsByYear[$y],2);
    }
    $totalRow[] = round($grandTotalAll,2);
    $data[] = $totalRow;

    // ------------------ Export ------------------
    $export = new class($data) implements FromArray, WithTitle, WithHeadings {
        protected $data;
        public function __construct($data){ $this->data = $data; }
        public function headings(): array{ return []; }
        public function title(): string{ return 'Month-on-Month Outstanding (MOM)'; }
        public function array(): array{ return $this->data; }
    };

    return Excel::download(
        $export,
        'MOM_Report_' . now()->format('Ymd_His') . '.xlsx'
    );
}
public function downloadODBReport(Request $request)
{
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->teamleaders ?? [];
    $adms       = $request->adms ?? [];
    $customers  = $request->customers ?? [];


    // ------------------ Determine ADMs based on hierarchy ------------------
    $admQuery = UserDetails::query();

    if (!empty($adms)) {
        $admQuery->whereIn('user_id', $adms);
    } elseif (!empty($tls)) {
        $admQuery->where(function($q) use ($tls) {
            $q->whereIn('supervisor', $tls)->orWhereIn('second_supervisor', $tls);
        });
    } elseif (!empty($asms)) {
        $tlIds = UserDetails::where(function($q) use ($asms){
            $q->whereIn('supervisor', $asms)->orWhereIn('second_supervisor', $asms);
        })->pluck('user_id')->toArray();

        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)->orWhereIn('second_supervisor', $tlIds);
        });
    } elseif (!empty($rsms)) {
        $asmIds = UserDetails::where(function($q) use ($rsms){
            $q->whereIn('supervisor', $rsms)->orWhereIn('second_supervisor', $rsms);
        })->pluck('user_id')->toArray();

        $tlIds = UserDetails::where(function($q) use ($asmIds){
            $q->whereIn('supervisor', $asmIds)->orWhereIn('second_supervisor', $asmIds);
        })->pluck('user_id')->toArray();

        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)->orWhereIn('second_supervisor', $tlIds);
        });
    } elseif (!empty($divisions)) {
        $rsmIds = UserDetails::whereIn('division', $divisions)->pluck('user_id')->toArray();
        $asmIds = UserDetails::where(function($q) use ($rsmIds){
            $q->whereIn('supervisor', $rsmIds)->orWhereIn('second_supervisor', $rsmIds);
        })->pluck('user_id')->toArray();

        $tlIds = UserDetails::where(function($q) use ($asmIds){
            $q->whereIn('supervisor', $asmIds)->orWhereIn('second_supervisor', $asmIds);
        })->pluck('user_id')->toArray();

        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)->orWhereIn('second_supervisor', $tlIds);
        });
    }

    $admNumbers = $admQuery->pluck('adm_number')->filter()->toArray();

    // ------------------ Fetch customers ------------------
    if (!empty($customers)) {
        $customerQuery = Customers::whereIn('customer_id', $customers);
    } else {
        $customerQuery = Customers::whereIn('adm', $admNumbers)
                                 ->orWhereIn('secondary_adm', $admNumbers);
    }

    $customersList = $customerQuery->get();

    $customerIds = $customersList->pluck('customer_id')->toArray();

    // ------------------ Fetch invoices ------------------
    $invoices = Invoices::with(['customer', 'payments'])
        ->whereIn('customer_id', $customerIds)
        ->orderBy('customer_id')
        ->orderBy('invoice_date')
        ->get();

    // ------------------ Build Excel Data ------------------
    $data = [];
    $data[] = ['Outstanding Days Breakdown (ODB)'];
    $data[] = []; // blank row

    $data[] = [
        'Customer No',
        'Customer Name',
        'ADM No',
        'ADM Name',
        'Document Date',
        'Reference',
        'Document Amount',
        'Outstanding Amount',
        'Days Arrears',
        '0–30',
        '31–60',
        '61–90',
        '91–120',
        '121+',
        'Paid & Post-Dated Cheque / Not Deposited Bank',
        'Chq No / Rcp No / Deposit Date',
        'Comments / Notification'
    ];

    $today = now();
    $grandTotalAmount = 0;
    $grandTotalOutstanding = 0;
    $grandTotalPending = 0;

    $groupedInvoices = $invoices->groupBy('customer_id');

    foreach ($groupedInvoices as $customerId => $customerInvoices) {
        foreach ($customerInvoices as $invoice) {
            $invoiceDate = \Carbon\Carbon::parse($invoice->invoice_date);
            $age = $invoiceDate->diffInDays($today);
            $outstanding_amount = $invoice->amount - ($invoice->paid_amount ?? 0);

            $grandTotalAmount += $invoice->amount;
            $grandTotalOutstanding += $outstanding_amount;

            // Pending payments & PDC
           // ------------------ Separate payments ------------------
                $acceptedPayments = $invoice->payments
                    ->filter(fn($p) => $p->status === 'accepted');

                $pendingPayments = $invoice->payments
                    ->filter(fn($p) => $p->status !== 'accepted'); // all non-accepted payments

                // ------------------ Amounts ------------------
                $acceptedAmount = $acceptedPayments->sum(function ($payment) {
                    if ($payment->type === 'cheque' && $payment->cheque_amount) return $payment->cheque_amount;
                    return $payment->final_payment ?? 0;
                });

                $pendingAmount = $pendingPayments->sum(function ($payment) {
                    if ($payment->type === 'cheque' && $payment->cheque_amount) return $payment->cheque_amount;
                    return $payment->final_payment ?? 0;
                });

                // ------------------ Outstanding Amount ------------------
                $outstanding_amount = $invoice->amount - $acceptedAmount;

                // ------------------ Payment details ------------------
                // $acceptedPaymentDetails = $acceptedPayments->map(function ($payment) {
                //     $details = $payment->invoice_id . ' / ' . \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d');
                //     if ($payment->type === 'cheque') {
                //         $details .= ' / ' . ($payment->cheque_number ?? '-') . ' / ' . ($payment->cheque_date ?? '-');
                //     }
                //     return $details;
                // })->implode(', ');

                $pendingPaymentDetails = $pendingPayments->map(function ($payment) {
                    $details = $payment->invoice_id . ' / ' . \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d');
                    if ($payment->type === 'cheque') {
                        $details .= ' / ' . ($payment->cheque_number ?? '-') . ' / ' . ($payment->cheque_date ?? '-');
                    }
                    return $details;
                })->implode(', ');

            // Aging buckets
            $b0_30 = $b31_60 = $b61_90 = $b91_120 = $b121 = '';
            if ($age <= 30) $b0_30 = $outstanding_amount;
            elseif ($age <= 60) $b31_60 = $outstanding_amount;
            elseif ($age <= 90) $b61_90 = $outstanding_amount;
            elseif ($age <= 120) $b91_120 = $outstanding_amount;
            else $b121 = $outstanding_amount;

            $data[] = [
                $invoice->customer->customer_id ?? '-',
                $invoice->customer->name ?? '-',
                $invoice->customer->admDetails->adm_number ?? '-',
                $invoice->customer->admDetails->name ?? '-',
                $invoiceDate->format('Y-m-d'),
                $invoice->invoice_or_cheque_no,
                $invoice->amount,
                $outstanding_amount,
                $age,
                $b0_30,
                $b31_60,
                $b61_90,
                $b91_120,
                $b121,
                $pendingAmount,
                $pendingPaymentDetails,
                $invoice->comments ?? ''
            ];
        }

        // Optional: Add subtotal row per customer
        $customerTotalAmount = $customerInvoices->sum('amount');
        $customerTotalOutstanding = $customerInvoices->sum(function($inv){
            return $inv->amount - ($inv->paid_amount ?? 0);
        });
        $grandTotalPending += $pendingAmount;
        $data[] = [
            'TOTAL ' . $customerInvoices->first()->customer->name,
            '',
            '',
            '',
            '',
            '',
            $customerTotalAmount,
            $customerTotalOutstanding,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
    }

    // ------------------ Grand Total ------------------
    $data[] = [
        'GRAND TOTAL',
        '',
        '',
        '',
        '',
        '',
        $grandTotalAmount,
        $grandTotalOutstanding,
        '',
        '',
        '',
        '',
        '',
        '',
        $grandTotalPending,
        '',
        ''
    ];

    // ------------------ Export ------------------
    $export = new class($data) implements FromArray, WithTitle, WithHeadings {
        protected $data;
        public function __construct($data){ $this->data = $data; }
        public function headings(): array{ return []; }
        public function title(): string{ return 'Outstanding Days Breakdown (ODB)'; }
        public function array(): array{ return $this->data; }
    };

    return Excel::download(
        $export,
        'ODB_Report_' . now()->format('Ymd_His') . '.xlsx'
    );
}
private function downloadDSOReport(Request $request)
{
    $fromDate   = $request->from;
    $toDate     = $request->to;
    $customers  = $request->customers;

    // Fetch invoices with payments
    $invoices = Invoices::with(['customer', 'payments'])
        ->whereBetween('invoice_date', [$fromDate, $toDate])
        ->when(!empty($customers), function ($q) use ($customers) {
            $q->whereIn('customer_id', (array) $customers);
        })
        ->get();

    $data = [];

    // ================== HEADER ==================
    $customerNameStr = $invoices->pluck('customer.name')->unique()->implode(', ');
    $data[] = ['Weighted Average Days Sales Outstanding - Wa DSO'];
    $data[] = ['Customer', $customerNameStr];
    $data[] = []; // blank row

    // ============ TABLE HEADERS =================
    $data[] = [
        'Inv No',
        'Invoice Date',
        'Document Amount (RS)',
        'Amount (Rs)',
        'Payment Date',
        'Days Taken to pay',
        'Add all Amount X Days'
    ];

    $totalAmount = 0;
    $totalAmountXDays = 0;

    foreach ($invoices as $invoice) {
        $invoiceDate = Carbon::parse($invoice->invoice_date);

        $acceptedPayments = $invoice->payments->where('status', 'accepted');

        if ($acceptedPayments->isEmpty()) continue;

        // Last accepted payment
        $lastPayment = $acceptedPayments->sortByDesc('created_at')->first();
        $paymentDate = Carbon::parse($lastPayment->created_at);

        $daysTaken = $invoiceDate->diffInDays($paymentDate);

        $documentAmount = $invoice->amount;
        $amountPaid     = $acceptedPayments->sum(fn($p) => $p->final_payment ?? 0);

        $amountXDays = $amountPaid * $daysTaken;

        $totalAmount += $amountPaid;
        $totalAmountXDays += $amountXDays;

        $data[] = [
            $invoice->invoice_or_cheque_no,
            $invoiceDate->format('d-M-y'),
            number_format($documentAmount, 2, '.', ','),
            number_format($amountPaid, 2, '.', ','),
            $paymentDate->format('d-M-y'),
            number_format($daysTaken),
            number_format($amountXDays, 2, '.', ',')
        ];
    }


    // ================== TOTALS ==================
    $data[] = [
        '',
        '',
        '', 
        number_format($totalAmount, 2, '.', ','), 
        '', 
        '', 
        number_format($totalAmountXDays, 2, '.', ',')
    ];

    // ================== DSO CALCULATION ==================
    $dso = $totalAmount > 0 ? $totalAmountXDays / $totalAmount : 0;
    $data[] = [];
    $data[] = ['DSO', number_format($dso, 2), 'Days'];

    // ================= ANONYMOUS EXPORT =================
    $export = new class($data) implements FromArray, WithTitle, WithHeadings {

        protected $data;

        public function __construct($data)
        {
            $this->data = $data;
        }

        public function headings(): array
        {
            return [];
        }

        public function title(): string
        {
            return 'DSO Report';
        }

        public function array(): array
        {
            return $this->data;
        }
    };

    return Excel::download(
        $export,
        'DSO_Report_' . now()->format('Ymd_His') . '.xlsx'
    );
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
