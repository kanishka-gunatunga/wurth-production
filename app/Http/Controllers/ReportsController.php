<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SetOffs;
use App\Models\WriteOffs;
use App\Models\Invoices;
use App\Models\InvoicePayments;
use App\Models\Customers;
use App\Models\CreditNote;
use App\Models\ExtraPayment;
use App\Models\UserDetails;
use App\Models\Divisions;
use App\Models\Deposits;
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
        if($type == 'tvc'){
            return $this->downloadTVCReport($request);
        }
        if($type == 'pdct'){
            return $this->downloadPDCTReport($request);
        }
        if($type == 'rcs'){ 
            return $this->downloadRCSReport($request);
        }
        if($type == 'pbd'){
            return $this->downloadPBDReport($request);
        }
        if($type == 'dmdr'){
            return $this->downloadDMDRReport($request);
        }
        if($type == 'dct'){
            return $this->downloadDCTReport($request);
        }
        if($type == 'ccd'){
            return $this->downloadCCDReport($request);
        }
         if($type == 'crc'){
            return $this->downloadCollectionReport($request);
        }
        if($type == 'ar'){
            return $this->downloadARReport($request);
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

            // dd("adm - ".$admIds);
    }

    // --- CASE 2: Team leaders selected -> find ADMs under them ---
    elseif(!empty($tls)){
        $admIds = UserDetails::whereIn('supervisor', $tls)
            ->orWhereIn('second_supervisor', $tls)
            ->pluck('user_id');
        // dd("tladm - ".$admIds);
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

        // dd("asmadm - ".$admIds);
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

        // dd("rsmadm - ".$admIds);
    }

    // --- CASE 5: Division selected ---
    elseif(!empty($divisions)){
        // RSMs under division
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->whereHas('user', function($q){
                $q->where('user_role', 3);
            })
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

        // dd("divadm - ".$admIds);
        }

    else{
        // nothing selected -> ALL ADMs
        $admIds = [];
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
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->whereHas('user', function($q){
                $q->where('user_role', 3);
            })
            ->pluck('user_id')
            ->toArray();

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
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->whereHas('user', function($q){
                $q->where('user_role', 3);
            })
            ->pluck('user_id')
            ->toArray();
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
                    ->filter(fn($p) => $p->status === 'approved');

                $pendingPayments = $invoice->payments
                    ->filter(fn($p) => $p->status !== 'approved'); // all non-approved payments

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

        $acceptedPayments = $invoice->payments->where('status', 'approved');

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
public function downloadTVCReport(Request $request)
{
    // ------------------ Dates ------------------
    $fromMonth = $request->from; // format: YYYY-MM
    $toMonth   = $request->to;   // format: YYYY-MM

    $fromDate = Carbon::parse($fromMonth . '-01')->startOfMonth();
    $toDate   = Carbon::parse($toMonth . '-01')->endOfMonth();

    // ------------------ Filters ------------------
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->tls ?? [];
    $adms       = $request->adms ?? [];
    $customers  = $request->customers ?? [];

    // ------------------ Determine ADMs based on hierarchy ------------------
    $admQuery = UserDetails::query();

    if (!empty($adms)) {
        $admQuery->whereIn('user_id', $adms);
    } elseif (!empty($tls)) {
        $admQuery->where(function($q) use ($tls){
            $q->whereIn('supervisor', $tls)
              ->orWhereIn('second_supervisor', $tls);
        });
    } elseif (!empty($asms)) {
        $tlIds = UserDetails::where(function($q) use ($asms){
            $q->whereIn('supervisor', $asms)
              ->orWhereIn('second_supervisor', $asms);
        })->pluck('user_id')->toArray();
        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)
              ->orWhereIn('second_supervisor', $tlIds);
        });
    } elseif (!empty($rsms)) {
        $asmIds = UserDetails::where(function($q) use ($rsms){
            $q->whereIn('supervisor', $rsms)
              ->orWhereIn('second_supervisor', $rsms);
        })->pluck('user_id')->toArray();

        $tlIds = UserDetails::where(function($q) use ($asmIds){
            $q->whereIn('supervisor', $asmIds)
              ->orWhereIn('second_supervisor', $asmIds);
        })->pluck('user_id')->toArray();

        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)
              ->orWhereIn('second_supervisor', $tlIds);
        });
    } elseif (!empty($divisions)) {
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->whereHas('user', function($q){
                $q->where('user_role', 3);
            })
            ->pluck('user_id')
            ->toArray();
        $asmIds = UserDetails::where(function($q) use ($rsmIds){
            $q->whereIn('supervisor', $rsmIds)
              ->orWhereIn('second_supervisor', $rsmIds);
        })->pluck('user_id')->toArray();
        $tlIds = UserDetails::where(function($q) use ($asmIds){
            $q->whereIn('supervisor', $asmIds)
              ->orWhereIn('second_supervisor', $asmIds);
        })->pluck('user_id')->toArray();
        $admQuery->where(function($q) use ($tlIds){
            $q->whereIn('supervisor', $tlIds)
              ->orWhereIn('second_supervisor', $tlIds);
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

    // ------------------ Prepare months range ------------------
    $months = [];
    $current = $fromDate->copy();
    while ($current <= $toDate) {
        $months[] = $current->copy();
        $current->addMonth();
    }

    // ------------------ Build Excel data ------------------
    $data = [];
    $data[] = ['Turnover vs Collection – AR Exposure (TVC - ARX)'];
    $data[] = ['Date: ' . $fromDate->format('M-Y') . ' to ' . $toDate->format('M-Y')];
    $data[] = [];
    $data[] = ['Month', 'Turnover', 'Collection', 'Surplus/ (Deficit)', 'Outstanding'];

    $grandTurnover = $grandCollection = $grandDeficit = $grandOutstanding = 0;

    foreach ($months as $month) {
        $monthStart = $month->copy()->startOfMonth();
        $monthEnd   = $month->copy()->endOfMonth();

        // ------------------ Turnover ------------------
        $turnover = Invoices::whereIn('customer_id', $customerIds)
            ->whereBetween('invoice_date', [$monthStart, $monthEnd])
            ->sum('amount');

        // ------------------ Collection ------------------
        $collection = InvoicePayments::where('status', 'approved')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->whereHas('invoice', function($q) use ($customerIds){
                $q->whereIn('customer_id', $customerIds);
            })
            ->sum('final_payment');

        // ------------------ Surplus / Deficit ------------------
        $deficit = $turnover - $collection;

        // ------------------ Outstanding ------------------
        $outstanding = Invoices::whereIn('customer_id', $customerIds)
            ->where('invoice_date', '<=', $monthEnd)
            ->get()
            ->sum(function($invoice){
                $paid = $invoice->payments()->where('status', 'approved')->sum('final_payment');
                return $invoice->amount - $paid;
            });

        $grandTurnover += $turnover;
        $grandCollection += $collection;
        $grandDeficit += $deficit;
        $grandOutstanding += $outstanding;

        $data[] = [
            $month->format('M-Y'),
            number_format($turnover, 2, '.', ','),
            number_format($collection, 2, '.', ','),
            number_format($deficit, 2, '.', ','),
            number_format($outstanding, 2, '.', ','),
        ];
    }

    // ------------------ Grand Total ------------------
    $monthsCount = count($months);
    $data[] = [
        'Grand Total',
        number_format($grandTurnover, 2, '.', ','),
        number_format($grandCollection, 2, '.', ','),
        number_format($grandDeficit, 2, '.', ','),
        number_format($grandOutstanding, 2, '.', ','),
    ];

    // ------------------ Average / Month ------------------
    $data[] = [
        'Average/Month',
        number_format($grandTurnover / $monthsCount, 2, '.', ','),
        number_format($grandCollection / $monthsCount, 2, '.', ','),
        number_format($grandDeficit / $monthsCount, 2, '.', ','),
        number_format($grandOutstanding / $monthsCount, 2, '.', ','),
    ];

    // ------------------ Export ------------------
    $export = new class($data) implements FromArray, WithTitle, WithHeadings {
        protected $data;
        public function __construct($data){ $this->data = $data; }
        public function headings(): array{ return []; }
        public function title(): string{ return 'TVC_Report'; }
        public function array(): array{ return $this->data; }
    };

    return Excel::download($export, 'TVC_Report_' . now()->format('Ymd_His') . '.xlsx');
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
public function downloadPDCTReport(Request $request)
{
    $fromMonth = $request->from; // e.g., "2025-12"
    $toMonth   = $request->to;   // e.g., "2026-01"
    $divisions = $request->divisions ?? [];
    $rsms      = $request->rsms ?? [];
    $asms      = $request->asms ?? [];
    $tls       = $request->tls ?? [];
    $adms      = $request->adms ?? [];
    $customers = $request->customers ?? [];

    /**
     * ------------------------------------------------------------------
     * Convert month input to full date range
     * ------------------------------------------------------------------
     */
    if ($fromMonth && $toMonth) {
        $fromDate = \Carbon\Carbon::createFromFormat('Y-m', $fromMonth)->startOfMonth()->format('Y-m-d');
        $toDate   = \Carbon\Carbon::createFromFormat('Y-m', $toMonth)->endOfMonth()->format('Y-m-d');
    } else {
        $fromDate = now()->startOfMonth()->format('Y-m-d');
        $toDate   = now()->endOfMonth()->format('Y-m-d');
    }

    /**
     * ------------------------------------------------------------------
     * Determine ADM IDs to filter by (if no customer selected)
     * ------------------------------------------------------------------
     */
    if (empty($customers)) {
        if (!empty($adms)) {
            $admIds = $adms;
        } elseif (!empty($tls)) {
            $admIds = UserDetails::whereIn('supervisor', $tls)
                        ->orWhereIn('second_supervisor', $tls)
                        ->pluck('user_id');
        } elseif (!empty($asms)) {
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
                                ->orWhereIn('second_supervisor', $asms)
                                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                        ->orWhereIn('second_supervisor', $teamLeaderIds)
                        ->pluck('user_id');
        } elseif (!empty($rsms)) {
            $asmIds = UserDetails::whereIn('supervisor', $rsms)
                        ->orWhereIn('second_supervisor', $rsms)
                        ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                                ->orWhereIn('second_supervisor', $asmIds)
                                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                        ->orWhereIn('second_supervisor', $teamLeaderIds)
                        ->pluck('user_id');
        } elseif (!empty($divisions)) {
            $rsmIds = UserDetails::whereIn('division', $divisions)
                ->whereHas('user', function($q){
                    $q->where('user_role', 3);
                })
                ->pluck('user_id');
            $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
                        ->orWhereIn('second_supervisor', $rsmIds)
                        ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                                ->orWhereIn('second_supervisor', $asmIds)
                                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                        ->orWhereIn('second_supervisor', $teamLeaderIds)
                        ->pluck('user_id');
        } else {
            $admIds = UserDetails::pluck('user_id');
        }
    }

    /**
     * ------------------------------------------------------------------
     * Get cheque payments
     * ------------------------------------------------------------------
     */
    $chequePayments = InvoicePayments::with(['invoice.customer', 'adm.userDetails'])->where('status', '!=','voided');
    
    // Filter by customers if selected
    if (!empty($customers)) {
        $chequePayments = $chequePayments->whereHas('invoice', function($q) use ($customers) {
            $q->whereIn('customer_id', $customers);
        });
    } else {
        // Otherwise filter by ADM IDs (structure)
        $chequePayments = $chequePayments->whereIn('adm_id', $admIds);
    }

    // Post-dated cheques filter
    $chequePayments = $chequePayments
        ->where('cheque_date', '>', now())
        ->whereBetween('cheque_date', [$fromDate, $toDate])
        ->orderBy('cheque_date', 'asc')
        ->get();

    /**
     * ------------------------------------------------------------------
     * Build Excel data
     * ------------------------------------------------------------------
     */
    $data = [];
    $data[] = ['PDCT - Post-Dated Cheques Tracker'];
    $data[] = [];

    $header = [
        'ADM Number', 'ADM Name', 'Customer Name',
        'Receipt Date', 'Cheque Number', 'Bank', 'Deposit Date', 'Cheque Amount'
    ];
    $data[] = $header;

    $totalAmount = 0;
    // dd($chequePayments);
    foreach ($chequePayments as $p) {
        $admDetails = $p->adm?->userDetails;
        $customer   = $p->invoice->customer;

        $row = [
            $admDetails->adm_number ?? '',
            $admDetails->name ?? '',
            $customer->name ?? '',
            $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('Y-m-d') : '',
            $p->cheque_number ?? '',
            $p->bank_name ?? '',
            $p->cheque_date ? \Carbon\Carbon::parse($p->cheque_date)->format('Y-m-d') : '',
            round($p->cheque_amount ?? $p->amount, 2)
        ];

        $totalAmount += $p->cheque_amount ?? $p->amount;
        $data[] = $row;
    }

    // Total row
    $data[] = [];
    $data[] = ['Total', '', '', '', '', '', '', round($totalAmount, 2)];

    /**
     * ------------------------------------------------------------------
     * Export to Excel
     * ------------------------------------------------------------------
     */
    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
            public function title(): string { return 'PDCT'; }
        },
        'PDCT_Report_'.now()->format('Ymd_His').'.xlsx'
    );
}

public function downloadRCSReport(Request $request)
{
    $fromMonth = $request->from;
    $toMonth   = $request->to;
    $divisions = $request->divisions ?? [];
    $rsms      = $request->rsms ?? [];
    $asms      = $request->asms ?? [];
    $tls       = $request->tls ?? [];
    $adms      = $request->adms ?? [];
    $customers = $request->customers ?? [];

    /**
     * ------------------------------------------------------------------
     * Convert month input to full date range
     * ------------------------------------------------------------------
     */
    $fromDate = $fromMonth ? \Carbon\Carbon::createFromFormat('Y-m', $fromMonth)->startOfMonth()->format('Y-m-d') : now()->startOfMonth()->format('Y-m-d');
    $toDate   = $toMonth   ? \Carbon\Carbon::createFromFormat('Y-m', $toMonth)->endOfMonth()->format('Y-m-d') : now()->endOfMonth()->format('Y-m-d');

    /**
     * ------------------------------------------------------------------
     * Get ADM numbers if structure filter applied (form passes user_ids)
     * ------------------------------------------------------------------
     */
    if (empty($customers)) {

        if (!empty($adms)) {
            $admNumbers = UserDetails::whereIn('user_id', $adms)->pluck('adm_number');
        } elseif (!empty($tls)) {
            $admNumbers = UserDetails::whereIn('supervisor', $tls)
                            ->orWhereIn('second_supervisor', $tls)
                            ->pluck('adm_number');
        } elseif (!empty($asms)) {
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
                                ->orWhereIn('second_supervisor', $asms)
                                ->pluck('user_id');
            $admNumbers = UserDetails::whereIn('supervisor', $teamLeaderIds)
                            ->orWhereIn('second_supervisor', $teamLeaderIds)
                            ->pluck('adm_number');
        } elseif (!empty($rsms)) {
            $asmIds = UserDetails::whereIn('supervisor', $rsms)
                            ->orWhereIn('second_supervisor', $rsms)
                            ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                                ->orWhereIn('second_supervisor', $asmIds)
                                ->pluck('user_id');
            $admNumbers = UserDetails::whereIn('supervisor', $teamLeaderIds)
                            ->orWhereIn('second_supervisor', $teamLeaderIds)
                            ->pluck('adm_number');
        } elseif (!empty($divisions)) {
            $rsmIds = UserDetails::whereIn('division', $divisions)
                ->whereHas('user', function($q){
                    $q->where('user_role', 3);
                })
                ->pluck('user_id');
            $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
                        ->orWhereIn('second_supervisor', $rsmIds)
                        ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                                ->orWhereIn('second_supervisor', $asmIds)
                                ->pluck('user_id');
            $admNumbers = UserDetails::whereIn('supervisor', $teamLeaderIds)
                            ->orWhereIn('second_supervisor', $teamLeaderIds)
                            ->pluck('adm_number');
        } else {
            $admNumbers = UserDetails::pluck('adm_number'); // all ADM numbers
        }

        // Get customers assigned to these ADM numbers
        $customers = Customers::whereIn('adm', $admNumbers)
                        ->orWhereIn('secondary_adm', $admNumbers)
                        ->pluck('customer_id')
                        ->toArray();
    }

    /**
     * ------------------------------------------------------------------
     * Get invoices with type 'return_cheque' for selected customers
     * ------------------------------------------------------------------
     */
    $returnCheques = Invoices::with(['customer.admDetails', 'customer.secondaryAdm'])
        ->whereIn('customer_id', $customers)
        ->where('type', 'return_cheque')
        ->whereBetween('returned_date', [$fromDate, $toDate])
        ->orderBy('returned_date', 'asc')
        ->get();

    /**
     * ------------------------------------------------------------------
     * Build Excel data
     * ------------------------------------------------------------------
     */
    $data = [];
    $data[] = ['Returned Cheque Summary (RCS)'];
    $data[] = [];
    $data[] = ['Period', $fromMonth.' to '.$toMonth, '', '', ''];

    $header = [
        'ADM Number', 'ADM Name', 'Customer Name', 'Cheque Number',
        'Bank', 'Returned on', 'Cheque Amount', 'Reason for returned', 'Days arrears'
    ];
    $data[] = $header;

    $totalAmount = 0;

    foreach ($returnCheques as $p) {
        $admDetails = $p->customer->admDetails ?? $p->customer->secondaryAdm;
        $customer   = $p->customer;

        $daysArrears = $p->returned_date
        ? \Carbon\Carbon::parse($p->returned_date)
            ->diffInDays(\Carbon\Carbon::now(), false) // signed difference
        : null;

        $row = [
            $admDetails->adm_number ?? '',
            $admDetails->name ?? '',
            $customer->name ?? '',
            $p->invoice_or_cheque_no ?? '',
            $p->bank ?? '',
            $p->returned_date ? \Carbon\Carbon::parse($p->returned_date)->format('Y-m-d') : '',
            round($p->amount ?? 0, 2),
            $p->reason ?? '',
            $daysArrears !== null ? (int) $daysArrears : null
        ];

        $totalAmount += $p->amount ?? 0;
        $data[] = $row;
    }

    // Total row
    $data[] = [];
    $data[] = ['Total', '', '', '', '', '', round($totalAmount, 2)];

    /**
     * ------------------------------------------------------------------
     * Export to Excel
     * ------------------------------------------------------------------
     */
    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
            public function title(): string { return 'RCS'; }
        },
        'RCS_Report_'.now()->format('Ymd_His').'.xlsx'
    );
}
public function downloadPBDReport(Request $request)
{
    $fromDate   = $request->from;
    $toDate     = $request->to;  
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->tls ?? [];
    $adms       = $request->adms ?? [];

    // Determine ADM IDs based on hierarchy
    if (!empty($adms)) {
        $admIds = $adms;
    } elseif (!empty($tls)) {
        $admIds = UserDetails::whereIn('supervisor', $tls)
                    ->orWhereIn('second_supervisor', $tls)
                    ->pluck('user_id');
    } elseif (!empty($asms)) {
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
                            ->orWhereIn('second_supervisor', $asms)
                            ->pluck('user_id');
        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                    ->orWhereIn('second_supervisor', $teamLeaderIds)
                    ->pluck('user_id');
    } elseif (!empty($rsms)) {
        $asmIds = UserDetails::whereIn('supervisor', $rsms)
                    ->orWhereIn('second_supervisor', $rsms)
                    ->pluck('user_id');
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                            ->orWhereIn('second_supervisor', $asmIds)
                            ->pluck('user_id');
        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                    ->orWhereIn('second_supervisor', $teamLeaderIds)
                    ->pluck('user_id');
    } elseif (!empty($divisions)) {
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->whereHas('user', function($q){
                $q->where('user_role', 3);
            })
            ->pluck('user_id');
        $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
                    ->orWhereIn('second_supervisor', $rsmIds)
                    ->pluck('user_id');
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                            ->orWhereIn('second_supervisor', $asmIds)
                            ->pluck('user_id');
        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                    ->orWhereIn('second_supervisor', $teamLeaderIds)
                    ->pluck('user_id');
    } else {
        $admIds = UserDetails::pluck('user_id');
    }

    /**
     * ------------------------------------------------------------------
     * Get Cash or Cheque payments with status != 'accepted'
     * ------------------------------------------------------------------
     */
    $payments = InvoicePayments::with(['invoice.customer', 'adm'])
        ->whereIn('adm_id', $admIds)
        ->whereIn('type', ['cash', 'cheque'])
        ->where('status', '!=', 'approved')
        ->whereBetween('created_at', [$fromDate, $toDate])
        ->orderBy('created_at', 'asc')
        ->get();

    /**
     * ------------------------------------------------------------------
     * Build Excel data
     * ------------------------------------------------------------------
     */
    $data = [];
    $data[] = ['Pending Bank Deposit (PBD)'];
    $data[] = ['Date: '. now()->format('Y-m-d')];
    $data[] = []; // Empty row

    // Header row
    $header = [
        'ADM No', 'ADM Name', 'Customer No', 'Customer Name',
        'Receipt Type (Cash/Cheque)', 'Cheque No (if applicable)', 'Cheque Date',
        'Amount (Rs.)', 'Expected Deposit Date', 'Actual Deposit Date', 'Days Delayed', 'Remarks'
    ];
    $data[] = $header;

    foreach ($payments as $p) {
        $admDetails = $p->adm?->userDetails;
        $customer   = $p->invoice?->customer;

        // Expected deposit date is the payment creation date
        $expectedDeposit = $p->created_at ? \Carbon\Carbon::parse($p->created_at) : null;

        // Find actual deposit from Deposits table
        $deposit = Deposits::whereJsonContains('reciepts', ['reciept_id' => (string) $p->id])->first();
        $actualDeposit = $deposit ? \Carbon\Carbon::parse($deposit->date_time) : null;

        // Days delayed calculation
        $daysDelayed = '';
       if ($expectedDeposit) {
            if ($actualDeposit) {
                $daysDelayedNumber = $expectedDeposit->diffInDays($actualDeposit, false);
            } else {
                $daysDelayedNumber = $expectedDeposit->diffInDays(now(), false);
            }

            $daysDelayedNumber = max(0, $daysDelayedNumber);
            $daysDelayed = number_format($daysDelayedNumber) . ' Days';
        } else {
            $daysDelayed = '';
        }

        // Remarks = invoice payment status
        $remarks = $p->status ?? '';

        $row = [
            $admDetails->adm_number ?? '',
            $admDetails->name ?? '',
            $customer->customer_id ?? '',
            $customer->name ?? '',
            ucfirst($p->type),
            $p->type === 'cheque' ? $p->cheque_number : '—',
            $p->type === 'cheque' && $p->cheque_date ? \Carbon\Carbon::parse($p->cheque_date)->format('d/m/Y') : '—',
            number_format($p->amount, 2),
            $expectedDeposit ? $expectedDeposit->format('d/m/Y') : '—',
            $actualDeposit ? $actualDeposit->format('d/m/Y') : 'Not deposited',
           $daysDelayed,
            $remarks
        ];

        $data[] = $row;
    }

    /**
     * ------------------------------------------------------------------
     * Export to Excel
     * ------------------------------------------------------------------
     */
    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
            public function title(): string { return 'PBD'; }
        },
        'PBD_Report_'.now()->format('Ymd_His').'.xlsx'
    );
}
public function downloadDMDRReport(Request $request)
{
    $fromDate   = $request->from;
    $toDate     = $request->to;  
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->tls ?? [];
    $adms       = $request->adms ?? [];

    /* -------------------------
       Resolve ADM IDs
    --------------------------*/
    if (!empty($adms)) {
        $admIds = $adms;
    } elseif (!empty($tls)) {
        $admIds = UserDetails::whereIn('supervisor', $tls)
            ->orWhereIn('second_supervisor', $tls)
            ->pluck('user_id');
    } elseif (!empty($asms)) {
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
            ->orWhereIn('second_supervisor', $asms)
            ->pluck('user_id');

        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    } elseif (!empty($rsms)) {
        $asmIds = UserDetails::whereIn('supervisor', $rsms)
            ->orWhereIn('second_supervisor', $rsms)
            ->pluck('user_id');

        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
            ->orWhereIn('second_supervisor', $asmIds)
            ->pluck('user_id');

        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    } elseif (!empty($divisions)) {
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->whereHas('user', function($q){
                $q->where('user_role', 3);
            })
            ->pluck('user_id');

        $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
            ->orWhereIn('second_supervisor', $rsmIds)
            ->pluck('user_id');

        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
            ->orWhereIn('second_supervisor', $asmIds)
            ->pluck('user_id');

        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    } else {
        $admIds = UserDetails::pluck('user_id');
    }

    /* -------------------------
       Get DECLINED Deposits
    --------------------------*/
    $deposits = Deposits::with('adm.userDetails')
        ->whereIn('adm_id', $admIds)
        ->where('status', 'rejected')
        ->whereBetween('date_time', [$fromDate, $toDate])
        ->orderBy('date_time', 'asc')
        ->get();

    $data = [];
    $data[] = ['Rejected Monthly Deposit Report (DMDR)'];
    $data[] = ['Date: '. now()->format('Y-m-d')];
    $data[] = [];

    $data[] = [ 
        'ADM No','ADM Name','Deposit ID','Deposit Date',
        'Payment ID','Customer','Payment Type',
        'Cheque No','Cheque Date','Amount','Reason for Decline'
    ];

    foreach ($deposits as $deposit) {

        $admDetails = $deposit->adm?->userDetails;

        $receipts = collect($deposit->reciepts ?? []);

        foreach ($receipts as $r) {

            $payment = InvoicePayments::with('invoice.customer')
                ->find($r['reciept_id'] ?? null);

            if (!$payment) continue;

            $row = [
                $admDetails->adm_number ?? '',
                $admDetails->name ?? '',
                $deposit->id,
                \Carbon\Carbon::parse($deposit->date_time)->format('d/m/Y'),

                $payment->id,
                $payment->invoice?->customer?->name ?? '',

                ucfirst($payment->type),
                $payment->cheque_number ?? '—',
                $payment->cheque_date
                    ? \Carbon\Carbon::parse($payment->cheque_date)->format('d/m/Y')
                    : '—',

                number_format($payment->amount, 2),

                $deposit->decline_reason ?? $deposit->status
            ];

            $data[] = $row;
        }
    }

    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray,\Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
            public function title(): string { return 'DMDR'; }
        },
        'DMDR_Report_'.now()->format('Ymd_His').'.xlsx'
    );
}
public function downloadDCTReport(Request $request)
{
    $fromDate   = $request->from;
    $toDate     = $request->to;  
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->tls ?? [];
    $adms       = $request->adms ?? [];

    // ------------------------------
    // Determine ADM IDs based on hierarchy (same as before)
    // ------------------------------
    if (!empty($adms)) {
        $admIds = $adms;
    } elseif (!empty($tls)) {
        $admIds = UserDetails::whereIn('supervisor', $tls)
            ->orWhereIn('second_supervisor', $tls)
            ->pluck('user_id');
    } elseif (!empty($asms)) {
        $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
            ->orWhereIn('second_supervisor', $asms)
            ->pluck('user_id');

        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    } elseif (!empty($rsms)) {
        $asmIds = UserDetails::whereIn('supervisor', $rsms)
            ->orWhereIn('second_supervisor', $rsms)
            ->pluck('user_id');

        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
            ->orWhereIn('second_supervisor', $asmIds)
            ->pluck('user_id');

        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    } elseif (!empty($divisions)) {
        $rsmIds = UserDetails::whereIn('division', $divisions)
            ->whereHas('user', function($q){
                $q->where('user_role', 3);
            })
            ->pluck('user_id');

        $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
            ->orWhereIn('second_supervisor', $rsmIds)
            ->pluck('user_id');

        $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
            ->orWhereIn('second_supervisor', $asmIds)
            ->pluck('user_id');

        $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
            ->orWhereIn('second_supervisor', $teamLeaderIds)
            ->pluck('user_id');
    } else {
        $admIds = UserDetails::pluck('user_id');
    }

    // ------------------------------
    // Build date period
    // ------------------------------
    $period = \Carbon\CarbonPeriod::create($fromDate, $toDate);
    $dateList = collect($period)->map(fn($d) => $d->format('Y-m-d'));

    // ------------------------------
    // Get ALL payments in range for those ADMs
    // ------------------------------
    $payments = InvoicePayments::with(['adm.userDetails'])
        ->where('status', '!=','voided')
        ->whereIn('adm_id', $admIds)
        ->whereBetween('created_at', [$fromDate, $toDate])
        ->get();

    // ------------------------------
    // Group by ADM and date
    // ------------------------------
    $grouped = $payments->groupBy('adm_id');

    // ------------------------------
    // Prepare Excel data
    // ------------------------------
    $data = [];
    $data[] = ['Daily Collections Tracker - DCT (ADM)'];
    $data[] = ['Period', "$fromDate to $toDate", '', 'Date', now()->format('Y-m-d')];
    $data[] = [];

    // Header Row
    $header = ['ADM No', 'ADM Name'];

    $counter = 1;
    foreach ($dateList as $date) {

        // sequential counter (1st, 2nd, 3rd…)
        $suffix = $counter . match (true) {
            str_ends_with($counter, '1') && !str_ends_with($counter, '11') => 'st',
            str_ends_with($counter, '2') && !str_ends_with($counter, '12') => 'nd',
            str_ends_with($counter, '3') && !str_ends_with($counter, '13') => 'rd',
            default => 'th'
        };

        // actual date inside brackets
        $actualDate = \Carbon\Carbon::parse($date)->format('d/m');

        $header[] = "{$suffix} ({$actualDate})";

        $counter++;
    }

    $header[] = 'Total Days';
    $header[] = 'Total Receipts';
    $header[] = 'Total';

    $data[] = $header;

    // ------------------------------
    // Build rows per ADM
    // ------------------------------
    foreach ($admIds as $admId) {

        $admDetails = UserDetails::where('user_id', $admId)->first();

        $row = [
            $admDetails->adm_number ?? '',
            $admDetails->name ?? '',
        ];

        $totalDays = 0;
        $totalReceipts = 0;
        $totalAmount = 0;

        foreach ($dateList as $date) {

            $daily = $payments->where('adm_id', $admId)
                ->filter(fn($p) => $p->created_at->format('Y-m-d') === $date);

            $amount = $daily->sum('amount');
            $count  = $daily->count();

            if ($amount > 0) {
                $totalDays++;
            }

            $totalReceipts += $count;
            $totalAmount += $amount;

            $cell = $amount > 0
                ? number_format($amount, 2) . " ({$count} collections)"
                : '0';

            $row[] = $cell;
        }

        $row[] = $totalDays;
        $row[] = $totalReceipts;
        $row[] = number_format($totalAmount, 2);

        $data[] = $row;
    }

    // ------------------------------
    // Export to Excel
    // ------------------------------
    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
            public function title(): string { return 'DCT'; }
        },
        'DCT_Report_'.now()->format('Ymd_His').'.xlsx'
    );
}
public function downloadCCDReport(Request $request)
{
    $fromDate = $request->from;
    $toDate   = $request->to;
    $admId    = $request->adm; // <-- This is user_id

    // ----------------------------
    // Get ADM details
    // ----------------------------
    $admDetails = UserDetails::where('user_id', $admId)->first();

    // ----------------------------
    // Get accepted deposits for this ADM & period
    // ----------------------------
    $deposits = Deposits::where('adm_id', $admId)
        ->whereBetween('final_approve_date', [$fromDate, $toDate])
        ->where('status', 'approved')
        ->get();

    $rows = [];
    $totalInvoiceAmount = 0;
    $totalCollectedAmount = 0;
    $cycleDaysList = [];

    foreach ($deposits as $deposit) {
        $receiptIds = collect($deposit->reciepts)->pluck('reciept_id')->toArray();

        $payments = InvoicePayments::whereIn('id', $receiptIds)
            ->with('invoice.customer')
            ->get();

        foreach ($payments as $payment) {
            $invoice = $payment->invoice;
            if (!$invoice) continue;

            $customer = $invoice->customer;

            $invoiceDate = \Carbon\Carbon::parse($invoice->invoice_date);
            $collectionDate = \Carbon\Carbon::parse($deposit->final_approve_date);

            $cycleDays = $invoiceDate->diffInDays($collectionDate);

            $totalInvoiceAmount += $invoice->amount;
            $totalCollectedAmount += $deposit->amount;

            $cycleDaysList[] = $cycleDays;

            $rows[] = [
                'invoice_no'       => $invoice->invoice_or_cheque_no,
                'customer_no'      => $customer->customer_id ?? '',
                'customer_name'    => $customer->name ?? '',
                'invoice_date'     => $invoiceDate->format('d/m/Y'),
                'invoice_amount'   => number_format($invoice->amount, 2),
                'collection_date'  => $collectionDate->format('d/m/Y'),
                'payment_mode'     => $deposit->type,
                'collected_amount' => number_format($deposit->amount, 2),
                'cycle_days'       => $cycleDays,
            ];
        }
    }

    // ----------------------------
    // Summary calculations
    // ----------------------------
    $totalInvoicesCollected = count($rows);
    $averageCycleDays = count($cycleDaysList) ? round(array_sum($cycleDaysList) / count($cycleDaysList), 1) : 0;
    $fastestCycle = count($cycleDaysList) ? min($cycleDaysList) : 0;
    $slowestCycle = count($cycleDaysList) ? max($cycleDaysList) : 0;

    // ----------------------------
    // Build Excel
    // ----------------------------
    $data = [];

    $data[] = ['Collection Cycle Days Report (Specimen)'];
    $data[] = [];
    $data[] = ['Period:', \Carbon\Carbon::parse($fromDate)->format('F Y')];
    $data[] = ['ADM No:', $admDetails->adm_number ?? ''];
    $data[] = ['ADM Name:', $admDetails->name ?? ''];
    $data[] = [];

    $data[] = [
        'Invoice No','Customer No','Customer Name','Invoice Date',
        'Invoice Amount (Rs)','Collection Date','Payment Mode',
        'Collected Amount (Rs)','Cycle Days'
    ];

    foreach ($rows as $r) {
        $data[] = array_values($r);
    }

    $data[] = [
        'Total','','','',
        number_format($totalInvoiceAmount, 2),'',
        '',
        number_format($totalCollectedAmount, 2),''
    ];

    $data[] = [];
    $data[] = ['Total Invoices Collected:', $totalInvoicesCollected];
    $data[] = ['Total Realised Collection: Rs', number_format($totalCollectedAmount, 2)];
    $data[] = ['Average Cycle Days: Days', $averageCycleDays];
    $data[] = ['Fastest Cycle: Days', $fastestCycle];
    $data[] = ['Slowest Cycle: Days', $slowestCycle];

    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
            public function title(): string { return 'CCD'; }
        },
        'CCD_Report_'.now()->format('Ymd_His').'.xlsx'
    );
}
public function downloadARReport(Request $request)
{
    $fromDate  = $request->from ?? now()->startOfMonth()->format('Y-m-d');
    $toDate    = $request->to ?? now()->endOfMonth()->format('Y-m-d');
    $divisions = $request->divisions ?? [];
    $rsms      = $request->rsms ?? [];
    $asms      = $request->asms ?? [];
    $tls       = $request->tls ?? [];
    $adms      = $request->adms ?? [];
    $customers = $request->customers ?? [];

    // ---------------------------------------------------------
    // Resolve ADM IDs
    // ---------------------------------------------------------
    if (empty($customers)) {
        if (!empty($adms)) {
            $admIds = $adms;
        } elseif (!empty($tls)) {
            $admIds = UserDetails::whereIn('supervisor', $tls)
                        ->orWhereIn('second_supervisor', $tls)
                        ->pluck('user_id');
        } elseif (!empty($asms)) {
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
                                ->orWhereIn('second_supervisor', $asms)
                                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                        ->orWhereIn('second_supervisor', $teamLeaderIds)
                        ->pluck('user_id');
        } elseif (!empty($rsms)) {
            $asmIds = UserDetails::whereIn('supervisor', $rsms)
                        ->orWhereIn('second_supervisor', $rsms)
                        ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                                ->orWhereIn('second_supervisor', $asmIds)
                                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                        ->orWhereIn('second_supervisor', $teamLeaderIds)
                        ->pluck('user_id');
        } elseif (!empty($divisions)) {
            $rsmIds = UserDetails::whereIn('division', $divisions)
                ->whereHas('user', fn($q) => $q->where('user_role', 3))
                ->pluck('user_id');

            $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
                        ->orWhereIn('second_supervisor', $rsmIds)
                        ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                                ->orWhereIn('second_supervisor', $asmIds)
                                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                        ->orWhereIn('second_supervisor', $teamLeaderIds)
                        ->pluck('user_id');
        } else {
            $admIds = UserDetails::pluck('user_id');
        }
    }

    // ---------------------------------------------------------
    // Get invoices
    // ---------------------------------------------------------
    $query = Invoices::with([
        'customer',
        'admDetails.supervisorDetails',
        'admDetails.divisionData',
        'payments' => fn($q) => $q->where('status', 'approved')
    ]);

    $query->whereBetween('invoice_date', [$fromDate, $toDate]);

    if (!empty($customers)) {
        $query->whereIn('customer_id', $customers);
    } else {
        $query->whereHas('customer', fn($q) =>
            $q->whereIn('adm', UserDetails::whereIn('user_id', $admIds)->pluck('adm_number'))
        );
    }

    $invoices = $query->orderBy('customer_id')->orderBy('invoice_date')->get();

    // ---------------------------------------------------------
    // Excel Header
    // ---------------------------------------------------------
    $data = [];
    $data[] = ['AR - Accounts Receivable Aging Report'];
    $data[] = [];
    $header = [
        'Customer Name', 'Customer', 'Sales Rep.', 'Team', 'Division',
        'Document Number', 'Reference', 'Document Date',
        'Arrears after net due date', 'Local Crcy Doc Amount', 'Total of Intervals',
        'From: 0 To: 29', 'From: 30 To: 59', 'From: 60 To: 89',
        'From: 90 To: 119', 'From: 120 To: 179',
        'From: 180 To: 359', 'From: 360 To: 9999', 'Text'
    ];
    $data[] = $header;

    // ---------------------------------------------------------
    // Initialize totals
    // ---------------------------------------------------------
    $grandTotals = [
        'docAmount' => 0,
        'intervalTotal' => 0,
        'buckets' => array_fill(0, 7, 0)
    ];
    $currentCustomer = null;
    $customerTotals = [
        'docAmount' => 0,
        'intervalTotal' => 0,
        'buckets' => array_fill(0, 7, 0)
    ];

    // ---------------------------------------------------------
    // Process invoices
    // ---------------------------------------------------------
    foreach ($invoices as $invoice) {
        $customer = $invoice->customer;
        $adm = $invoice->admDetails;
        if (!$adm) continue;

        $docDate = \Carbon\Carbon::parse($invoice->invoice_date);
        $arrears = max(0, $docDate->diffInDays(now()));

        $docAmount = (float) ($invoice->amount ?? 0);
        $totalPaid = (float) $invoice->payments->sum('amount');
        $totalOfIntervals = $docAmount - $totalPaid;
        if ($totalOfIntervals <= 0) continue;

        // Aging buckets
        $buckets = array_fill(0, 7, 0);
        if ($arrears <= 29) $buckets[0] = $totalOfIntervals;
        elseif ($arrears <= 59) $buckets[1] = $totalOfIntervals;
        elseif ($arrears <= 89) $buckets[2] = $totalOfIntervals;
        elseif ($arrears <= 119) $buckets[3] = $totalOfIntervals;
        elseif ($arrears <= 179) $buckets[4] = $totalOfIntervals;
        elseif ($arrears <= 359) $buckets[5] = $totalOfIntervals;
        else $buckets[6] = $totalOfIntervals;

        // Customer subtotal row
        if ($currentCustomer && $currentCustomer != $customer->customer_id) {
            $data[] = $this->generateCustomerSubtotalRow($prevCustomerId, $prevSalesRep, $prevTeam, $prevDivision, $customerTotals);
            $customerTotals = [
                'docAmount' => 0,
                'intervalTotal' => 0,
                'buckets' => array_fill(0, 7, 0)
            ];
        }

        $currentCustomer = $customer->customer_id;
        $prevCustomerId = $customer->customer_id;
        $prevSalesRep = $adm->adm_number ?? '';
        $prevTeam = $adm->supervisorDetails->name ?? '';
        $prevDivision = $adm->divisionData->division_name ?? '';

        // Invoice row
        $row = [
            $customer->name ?? '',
            $customer->customer_id ?? '',
            $adm->adm_number ?? '',
            $prevTeam,
            $prevDivision,
            $invoice->invoice_or_cheque_no ?? '',
            $invoice->id ?? '',
            $docDate->format('n/j/Y'),
            $arrears,
            $docAmount,
            $totalOfIntervals
        ];
        foreach ($buckets as $b) {
            $row[] = $b;
        }
        $row[] = $invoice->reason ?? '';
        while (count($row) < 19) $row[] = '';
        // Format numbers only when pushing to Excel
        $row = array_map(fn($v) => is_numeric($v) ? number_format($v, 2) : $v, $row);
        $data[] = $row;

        // Update totals
        $customerTotals['docAmount'] += $docAmount;
        $customerTotals['intervalTotal'] += $totalOfIntervals;
        $grandTotals['docAmount'] += $docAmount;
        $grandTotals['intervalTotal'] += $totalOfIntervals;
        for ($i = 0; $i < 7; $i++) {
            $customerTotals['buckets'][$i] += $buckets[$i];
            $grandTotals['buckets'][$i] += $buckets[$i];
        }
    }

    // Final customer subtotal
    if ($currentCustomer) {
        $data[] = $this->generateCustomerSubtotalRow($prevCustomerId, $prevSalesRep, $prevTeam, $prevDivision, $customerTotals);
    }

    // ---------------------------------------------------------
    // Grand total row
    // ---------------------------------------------------------
    $grandRow = array_fill(0, 9, '');
    $grandRow[] = number_format($grandTotals['docAmount'], 2);       // Local Crcy Doc Amount
    $grandRow[] = number_format($grandTotals['intervalTotal'], 2);   // Total of Intervals
    foreach ($grandTotals['buckets'] as $b) {
        $grandRow[] = $b > 0 ? number_format($b, 2) : ' - ';
    }
    $grandRow[] = '';
    $data[] = [];
    $data[] = $grandRow;

    // ---------------------------------------------------------
    // Export Excel
    // ---------------------------------------------------------
    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
            public function title(): string { return 'AR'; }
        },
        'AR_Report_' . now()->format('Ymd_His') . '.xlsx'
    );
}

// ---------------------------------------------------------
// Customer subtotal helper
// ---------------------------------------------------------
private function generateCustomerSubtotalRow($customerId, $salesRep, $team, $division, $totals)
{
    $row = [
        '', $customerId, $salesRep, $team, $division,
        '', '', '', '', number_format($totals['docAmount'], 2), // Local Crcy Doc Amount
        number_format($totals['intervalTotal'], 2)
    ];
    foreach ($totals['buckets'] as $b) {
        $row[] = $b > 0 ? number_format($b, 2) : ' - ';
    }
    $row[] = '';
    return $row;
}



public function downloadCollectionReport(Request $request)
{
    $fromDate   = $request->from;
    $toDate     = $request->to;
    $divisions  = $request->divisions ?? [];
    $rsms       = $request->rsms ?? [];
    $asms       = $request->asms ?? [];
    $tls        = $request->tls ?? [];
    $adms       = $request->adms ?? [];
    $customers  = $request->customers ?? [];

    // -------------------------------------------------------------
    // 1. Resolve ADM IDs
    // -------------------------------------------------------------
    if (!empty($customers)) {
        $customerRecords = Customers::whereIn('customer_id', $customers)->get();
        $admNumbers = $customerRecords->pluck('adm')
            ->merge($customerRecords->pluck('secondary_adm'))
            ->filter()
            ->unique();
        $admIds = UserDetails::whereIn('adm_number', $admNumbers)->pluck('user_id');
    } else {
        if (!empty($adms)) {
            $admIds = $adms;
        } elseif (!empty($tls)) {
            $admIds = UserDetails::whereIn('supervisor', $tls)
                ->orWhereIn('second_supervisor', $tls)
                ->pluck('user_id');
        } elseif (!empty($asms)) {
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asms)
                ->orWhereIn('second_supervisor', $asms)
                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                ->orWhereIn('second_supervisor', $teamLeaderIds)
                ->pluck('user_id');
        } elseif (!empty($rsms)) {
            $asmIds = UserDetails::whereIn('supervisor', $rsms)
                ->orWhereIn('second_supervisor', $rsms)
                ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                ->orWhereIn('second_supervisor', $asmIds)
                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                ->orWhereIn('second_supervisor', $teamLeaderIds)
                ->pluck('user_id');
        } elseif (!empty($divisions)) {
            $rsmIds = UserDetails::whereIn('division', $divisions)
                ->whereHas('user', function ($q) {
                    $q->where('user_role', 3);
                })
                ->pluck('user_id');
            $asmIds = UserDetails::whereIn('supervisor', $rsmIds)
                ->orWhereIn('second_supervisor', $rsmIds)
                ->pluck('user_id');
            $teamLeaderIds = UserDetails::whereIn('supervisor', $asmIds)
                ->orWhereIn('second_supervisor', $asmIds)
                ->pluck('user_id');
            $admIds = UserDetails::whereIn('supervisor', $teamLeaderIds)
                ->orWhereIn('second_supervisor', $teamLeaderIds)
                ->pluck('user_id');
        } else {
            $admIds = UserDetails::pluck('user_id');
        }
    }

    // -------------------------------------------------------------
    // 2. Fetch Deposits (Accepted only) — no date filter here
    // -------------------------------------------------------------
    $deposits = Deposits::with(['adm.userDetails.supervisor', 'adm.userDetails.divisionData'])
        ->whereIn('adm_id', $admIds)
        ->where('status', 'approved')
        ->get();

    // -------------------------------------------------------------
    // 3. Collect Receipt IDs from Deposits
    // -------------------------------------------------------------
    $allReceiptIds = [];
    foreach ($deposits as $deposit) {
    $receipts = $deposit->reciepts;

    if (is_string($receipts)) {
        $receipts = json_decode($receipts, true) ?? [];
    }

    foreach ($receipts as $r) {
        if (!empty($r['reciept_id'])) {
            $allReceiptIds[] = $r['reciept_id'];
        }
    }
}

    $allReceiptIds = array_unique($allReceiptIds);

    // -------------------------------------------------------------
    // 4. Fetch Invoice Payments
    // -------------------------------------------------------------
    $allPayments = InvoicePayments::with('invoice.customer')
        ->whereIn('id', $allReceiptIds)
        ->get()
        ->keyBy('id');

    // -------------------------------------------------------------
    // 5. Build Report Data
    // -------------------------------------------------------------
    $data = [];
    $data[] = [
        'DIVISION', 'TEAM', 'Sales Rep.', 'Sales Rep. Name',
        'Customer Name', 'Customer', 'Invoice Number', 'Amount',
        '0 - 30', '31 - 60', '61 - 90', '91 - 120', '121-180', '181-360', '360<'
    ];

    $groupedDeposits = $deposits->groupBy('adm_id');

    foreach ($groupedDeposits as $admId => $admDepositsList) {

        $firstDeposit = $admDepositsList->first();
        $admDetails   = $firstDeposit->adm->userDetails ?? null;
        if (!$admDetails) continue;

        $divisionName = $admDetails->divisionData->division_name ?? '';
        $teamName     = $admDetails->supervisor->name ?? '';
        $admNumber    = $admDetails->adm_number ?? '';
        $admName      = $admDetails->name ?? '';

        $admTotal     = 0;
        $bucketTotals = [0, 0, 0, 0, 0, 0, 0];
        $hasRows      = false;

        foreach ($admDepositsList as $deposit) {

            $depositDate = \Carbon\Carbon::parse($deposit->date_time)->format('Y-m-d');

            $receipts = $deposit->reciepts ?? [];
            foreach ($receipts as $r) {

                $rId = $r['reciept_id'] ?? null;
                if (!$rId || !isset($allPayments[$rId])) continue;

                $payment = $allPayments[$rId];
                $isFinance = in_array($payment->type, ['finance-cheque', 'finance-cash']);

                // -------------------------------------------------
                // DATE FILTER LOGIC
                // -------------------------------------------------
                if ($isFinance) {
                    if (!$payment->final_approve_date) continue;

                    $effectiveDate = \Carbon\Carbon::parse($payment->final_approve_date)->format('Y-m-d');
                    if ($effectiveDate < $fromDate || $effectiveDate > $toDate) continue;
                } else {
                    // Other types → use deposit date_time
                    if ($depositDate < $fromDate || $depositDate > $toDate) continue;
                    $effectiveDate = $deposit->date_time;
                }

                // Customer filter (if selected)
                if (!empty($customers)) {
                    if (!in_array($payment->invoice->customer_id ?? '', $customers)) continue;
                }

                // -------------------------------------------------
                // PROCESS ROW
                // -------------------------------------------------
                $hasRows = true;
                $amount  = $payment->amount;
                $invoice = $payment->invoice;
                $invDate = $invoice->invoice_date ?? null;

                // Aging Calculation
                $bucketIndex = -1;
                if ($invDate && $effectiveDate) {
                    $days = \Carbon\Carbon::parse($invDate)
                        ->diffInDays(\Carbon\Carbon::parse($effectiveDate), false);

                    if ($days <= 30)       $bucketIndex = 0;
                    elseif ($days <= 60)  $bucketIndex = 1;
                    elseif ($days <= 90)  $bucketIndex = 2;
                    elseif ($days <= 120) $bucketIndex = 3;
                    elseif ($days <= 180) $bucketIndex = 4;
                    elseif ($days <= 360) $bucketIndex = 5;
                    else                  $bucketIndex = 6;
                }

                $row = [
                    $divisionName,
                    $teamName,
                    $admNumber,
                    $admName,
                    $invoice->customer->name ?? '',
                    $invoice->customer->customer_id ?? '',
                    $invoice->invoice_or_cheque_no ?? '',
                    " " . number_format($amount, 2) . " ",
                ];

                for ($i = 0; $i < 7; $i++) {
                    if ($i === $bucketIndex) {
                        $row[] = " " . number_format($amount, 2) . " ";
                        $bucketTotals[$i] += $amount;
                    } else {
                        $row[] = ' - ';
                    }
                }

                $data[] = $row;
                $admTotal += $amount;
            }
        }

        if ($hasRows) {
            $summaryRow = [
                $divisionName, $teamName, $admNumber, '',
                '', '', '', " " . number_format($admTotal, 2) . " "
            ];
            foreach ($bucketTotals as $bt) {
                $summaryRow[] = $bt > 0 ? " " . number_format($bt, 2) . " " : ' - ';
            }
            $data[] = $summaryRow;
            $data[] = [];
        }
    }

    return Excel::download(
        new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
            public function __construct(public $data) {}
            public function array(): array { return $this->data; }
        },
        'Collection_Report_' . now()->format('Ymd_His') . '.xlsx'
    );
}

}
