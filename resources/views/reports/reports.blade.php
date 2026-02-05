@include('layouts.dashboard-header')
<?php
use Carbon\Carbon;
$currentYear = Carbon::now()->year;
$years = range($currentYear, $currentYear - 10);
?>
<style>
    .import {
        background-color: white;
    }

    .import .main-card {
        border-radius: 0 !important;
        border: none !important;
    }

    .import .card-title {
        font-family: "Poppins", sans-serif;
        font-size: 18px;
        font-weight: 600;

    }

    .import p {
        font-family: "Poppins", sans-serif;
        font-size: 10px;
        font-weight: 400;
        color: #00000080;
    }

    .import .dotted-card {
        border-style: dashed;
        border-color: #CC0000;
        border-radius: 20px;
        width: auto;
        height: 240px;
    }


    .file-upload .title,
    .file-upload .info {
        font-family: "Poppins", sans-serif;
        font-size: 10px;
        font-weight: 400;
        color: #000000;
        display: flex;
        justify-content: center;
        margin-bottom: 5px;

    }

    .file-upload .info {
        color: #00000080;
    }

    .file-name {
        display: flex;
        justify-content: center;


    }

    .upload-circle {
        background-color: #771d1d0d;
        padding: 30px;
    }

    /* Below 1384px → keep width same, allow scrolling */
    @media (max-width: 1384px) {
        .table-responsive {
            overflow-x: auto;
            /* enable horizontal scroll */
        }

        .table-responsive table {
            min-width: 35vw;
            /* force same width as 1385px */
        }
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .icon {
        width: 56px;
        height: 56px;
        background: #CC0000;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .icon svg {
        width: 28px;
        height: 28px;
    }

    /* new styles */
    .filter-section {
        background: white;
        border: 2px solid #E1E1E1;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 20px;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .section-title-wrapper {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .section-number {
        width: 32px;
        height: 32px;
        background: #CC0000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: 400;
        color: #000000;
        margin: 0;
    }

    .required-asterisk {
        color: #CC0000;
        margin-left: 4px;
    }

    .disabled-badge {
        color: #CC0000;
        font-size: 16px;
        font-weight: 600;
    }

    .section-subtitle {
        font-family: "Poppins", sans-serif;
        color: #AAB6C1;
        font-size: 12px;
        margin-bottom: 24px;
        margin-top: 8px;
        font-weight: 400;
    }

    .custom-input-label {
        font-size: 14px;
        font-weight: 400;
        color: #AAB6C1;
        margin-bottom: 10px;
        display: block;
    }

    /* Date inputs */
    .date-input-wrapper {
        position: relative;
    }

    .date-input {
        width: 100%;
        padding: 14px 16px;
        padding-right: 45px;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
        outline: none;
        transition: border-color 0.2s;
    }

    .date-input:focus {
        border-color: #CC0000;
    }

    .date-input::placeholder {
        color: #c0c0c0;
    }

    .calendar-icon {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #999;
    }

    .btn-reset {
        padding: 14px 32px;
        background: white;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        transition: all 0.2s;
    }
    .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d0d0d0;
    border-radius: 8px;
    height:unset!important;
}
.select2-container .select2-selection--single .select2-selection__rendered{
    padding: 14px 16px !important;
    padding-right: 45px  !important;
    font-size: 14px !important;
    color: #333 !important;
    outline: none !important;
    line-height:unset!important;
}
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between ms-3">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Report Generator</h1>
        </div>
    </div>

    <hr class="red-line mt-0">

    <div class="row d-flex gap-4 ms-3">

        <div class="styled-tab-sub p-4" style="border-radius: 8px;">

            <div class="container">
                <div class="header">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M15 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V7L15 2Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M14 2V6C14 6.53043 14.2107 7.03914 14.5858 7.41421C14.9609 7.78929 15.4696 8 16 8H20"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10 9H8" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M16 13H8" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M16 17H8" stroke="white" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h2 class="section-title">Select Report Type</h2>
                </div>

                <div class="dropdown w-100">
                    <button class="btn custom-dropdown w-100 text-start" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        -- Select a Report --
                        <span class="custom-arrow"></span>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        <li class="dropdown-item fw-bold text-dark">
                            ORMR – Outstanding Receivable Management Reports
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="ARA">ARA - Accounts Receivable Aging
                                (Dynamic Age Brackets)</a></li>
                        <li><a class="dropdown-item" href="#" data-report="YOO">YOO - Year-on-Year Outstanding</a>
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="MOM">MOM - Month-on-Month
                                Outstanding</a></li>
                        <li><a class="dropdown-item" href="#" data-report="ODB">ODB - Outstanding Days Breakdown
                                (Age Analysis)</a></li>

                        <li class="dropdown-item fw-bold text-dark">
                            RPI – Receivable Performance Indicators
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="DSO">DSO - Days Sales Outstanding</a>
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="CCD">CCD - Collection Cycle Days
                                (ADM)</a></li>
                        <li><a class="dropdown-item" href="#" data-report="TVC">TvC-AREC - Turnover vs Collection
                                – AR Exposure Control</a></li>

                        <li class="dropdown-item fw-bold text-dark">
                            CMR - Collection Management Report
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="DCT">DCT - Daily Collection Tracker
                                (ADM)</a></li>
                        <li><a class="dropdown-item" href="#" data-report="PBD">PBD - Pending Bank Deposit</a>
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="DMDR">DMDR - Deposit Mismatch & Decline
                                Register</a></li>
                        <li><a class="dropdown-item" href="#" data-report="PDCT">PDCT - Post-Dated Cheques
                                Tracker</a></li>
                        <li><a class="dropdown-item" href="#" data-report="RCS">RCS - Returned Cheque Summary</a>
                        </li>
                         <li class="dropdown-item fw-bold text-dark">
                            Other Reports
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="WO">Write-off</a></li>
                        <li><a class="dropdown-item" href="#" data-report="SO">Set-off</a></li>
                        <li><a class="dropdown-item" href="#" data-report="AR">AR Report</a></li>
                        <li><a class="dropdown-item" href="#" data-report="CRC">Collection Report for Commission</a></li>
    
                    </ul>
                </div>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="ARA" style="border-radius: 8px;">
            <form action="{{url('download-report/ara')}}" method="POST">
            @csrf

            <!-- Section 1: Date Range --> 
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2025-12-28" name="from">
                 
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Customer Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one or more customers directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customers</label>
                        <div class="multiselect-wrapper" data-name="customer">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select customers...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($customers as $customer){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="cust{{$customer->id}}" value="{{$customer->customer_id}}" name="customers[]">
                                        <label for="cust{{$customer->id}}">{{$customer->name}}</label>
                                    </div>
                                    <?php } ?>

                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">{{count($customers)}} of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
           </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="YOO" style="border-radius: 8px;">
             <form action="{{url('download-report/yoo')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Year<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Select Year</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select years...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown"> 
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   @foreach($years as $index => $year)
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="year{{ $index }}" name="years[]" value="{{ $year }}">
                                            <label for="year{{ $index }}">{{ $year }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($years )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="yoodiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="yoodiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="yoorsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="yoorsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="yooasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="yooasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="yootl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="yootl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="yooadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="yooadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="MOM" style="border-radius: 8px;">
             <form action="{{url('download-report/mom')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Year<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Select Year</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select years...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown"> 
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   @foreach($years as $index => $year)
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="momyear{{ $index }}" name="years[]" value="{{ $year }}">
                                            <label for="momyear{{ $index }}">{{ $year }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($years )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="momdiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="momdiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="momrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="momrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="momasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="momasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="momtl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="momtl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="momadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="momadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="ODB" style="border-radius: 8px;">
             <form action="{{url('download-report/odb')}}" method="POST">
            @csrf
            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="odbdiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="odbdiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="odbrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="odbrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="odbasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="odbasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="odbtl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="odbtl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="odbadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="odbadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Customer Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one or more customers directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customers</label>
                        <div class="multiselect-wrapper" data-name="customer">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select customers...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php foreach($customers as $customer){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="odbcust{{$customer->id}}" value="{{$customer->customer_id}}" name="customers[]">
                                        <label for="odbcust{{$customer->id}}">{{$customer->name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($customers)}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
                                    </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="DSO" style="border-radius: 8px;">
              <form action="{{url('download-report/dso')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" name="from" value="2025-12-28">
                          
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" name="to" value="2026-01-10">
                        
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Customer Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one customer directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customer</label>
                        <select id="filter-adm-id" name="customers[]" class="form-control select2-filter" >
                           <?php foreach($customers as $customer){ ?>
                                    <option value="{{$customer->customer_id}}" >
                                       {{$customer->name}}
                                    </option>
                            <?php } ?>
                        </select>
                       
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
</form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="CCD" style="border-radius: 8px;">
              <form action="{{url('download-report/ccd')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2025-12-28" name="from">
          
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10" name="to">
  
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <div class="row">
                     <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <select id="filter-adm-id" name="adm" class="form-control select2-filter" >
                           
                            <?php foreach($users as $user){ 
                            if($user->user_role == 6){
                            ?>
                                <option value="{{$user->id}}" >
                                    {{$user->userDetails->name}}
                                </option>
                               
                            <?php }} ?>
                        </select>
                       
                    </div>
                    <!-- ADM -->
                   
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="TVC" style="border-radius: 8px;">
             <form action="{{url('download-report/tvc')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="month" class="date-input" value="2025-12-28" name="from">
                         
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="month" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                 <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcdiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="tvcdiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="tvcrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="tvcasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvctl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="tvctl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="tvcadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Customer Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">3</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one or more customers directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customers</label>
                        <div class="multiselect-wrapper" data-name="customer">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select customers...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                      <?php foreach($customers as $customer){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvccust{{$customer->id}}" value="{{$customer->customer_id}}" name="customers[]">
                                        <label for="tvccust{{$customer->id}}">{{$customer->name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($customers)}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="DCT" style="border-radius: 8px;">
             <form action="{{url('download-report/dct')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2025-12-28" name="from">
                          
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

               <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcdiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="tvcdiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="tvcrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="tvcasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvctl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="tvctl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="tvcadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="tvcadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="PBD" style="border-radius: 8px;">
             <form action="{{url('download-report/pbd')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2025-12-28" name="from">
                         
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbddiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="pbddiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="pbdrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="pbdasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdtl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="pbdtl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="pbdadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="DMDR" style="border-radius: 8px;">
              <form action="{{url('download-report/dmdr')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2025-12-28" name="from">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10" name="to">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbddiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="pbddiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="pbdrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="pbdasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdtl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="pbdtl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pbdadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="pbdadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="PDCT" style="border-radius: 8px;">
              <form action="{{url('download-report/pdct')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="month" class="date-input" value="2025-12-28" name="from">
                          
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="month" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

               <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pdctdiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="pdctdiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pdctrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="pdctrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pdctasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="pdctasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pdcttl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="pdcttl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="pdctadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="pdctadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">3</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one or more customers directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customers</label>
                        <div class="multiselect-wrapper" data-name="customer">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select customers...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php foreach($customers as $customer){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="odbcust{{$customer->id}}" value="{{$customer->customer_id}}" name="customers[]">
                                        <label for="odbcust{{$customer->id}}">{{$customer->name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($customers)}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="RCS" style="border-radius: 8px;">
           <form action="{{url('download-report/rcs')}}" method="POST">
            @csrf
            <!-- Section 1: Date Range -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="month" class="date-input" value="2025-12-28" name="from">
                          
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="month" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

               <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="rcsdiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="rcsdiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="rcsrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="rcsrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="rcsasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="rcsasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="rcstl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="rcstl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="rcsadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="rcsadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">3</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one or more customers directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customers</label>
                        <div class="multiselect-wrapper" data-name="customer">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select customers...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php foreach($customers as $customer){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="rcscust{{$customer->id}}" value="{{$customer->customer_id}}" name="customers[]">
                                        <label for="rcscust{{$customer->id}}">{{$customer->name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($customers)}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
            </form>
        </div>

  
            <div class="styled-tab-sub p-4 report-filters d-none" data-report="WO" style="border-radius: 8px;">
                      <form method="POST" action="{{ route('reports.download') }}">
            @csrf
            <input type="hidden" name="type" value="WO">
                <!-- Section 1: Date Range -->
                <div class="filter-section">
                    <div class="section-header">
                        <div class="section-title-wrapper">
                            <div class="section-number">1</div>
                            <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6 mb-3">
                            <label class="custom-input-label">From Date</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="from_date" class="date-input" >
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <label class="custom-input-label">To Date</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="to_date" class="date-input" >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-button-lg-row">
                    <button type="reset" class="btn-reset" onclick="resetAllFilters()">Reset All
                        Filters</button>
                    <button type="submit" class="red-action-btn-lg">
                        Generate Report
                    </button>
                </div>
                 </form>
            </div>
       


            <div class="styled-tab-sub p-4 report-filters d-none" data-report="SO" style="border-radius: 8px;">
                        <form method="POST" action="{{ route('reports.download') }}">
            @csrf
            <input type="hidden" name="type" value="SO">
                <!-- Section 1: Date Range -->
                <div class="filter-section">
                    <div class="section-header">
                        <div class="section-title-wrapper">
                            <div class="section-number">1</div>
                            <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6 mb-3">
                            <label class="custom-input-label">From Date</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="from_date" class="date-input" >
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <label class="custom-input-label">To Date</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="to_date" class="date-input" >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-button-lg-row">
                    <button type="reset" class="btn-reset" onclick="resetAllFilters()">Reset All
                        Filters</button>
                    <button type="submit" class="red-action-btn-lg">
                        Generate Report
                    </button>
                </div>
                 </form>
            </div>
       

             <div class="styled-tab-sub p-4 report-filters d-none" data-report="CRC" style="border-radius: 8px;">
             <form action="{{url('download-report/crc')}}" method="POST">
            @csrf
            <!-- Section 2: Hierarchy Wise Filtering -->

            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2025-12-28" name="from">
                 
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="crcdiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="crcdiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="crcrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="crcrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="crcasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="crcasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="crctl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="crctl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="crcadm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="crcadm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Customer Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">3</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one or more customers directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customers</label>
                        <div class="multiselect-wrapper" data-name="customer">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select customers...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php foreach($customers as $customer){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="crccust{{$customer->id}}" value="{{$customer->customer_id}}" name="customers[]">
                                        <label for="crccust{{$customer->id}}">{{$customer->name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($customers)}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
                                    </form>
        </div>

         <div class="styled-tab-sub p-4 report-filters d-none" data-report="AR" style="border-radius: 8px;">
             <form action="{{url('download-report/ar')}}" method="POST">
            @csrf

            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Date Range<span class="required-asterisk">*</span></h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2025-12-28" name="from">
                 
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10" name="to">
                           
                        </div>
                    </div>
                </div>
            </div>
            <!-- Section 2: Hierarchy Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Hierarchy Wise Filtering</h2>
                    </div>
                    <!-- <span class="disabled-badge">Disabled</span> -->
                </div>

                <p class="section-subtitle">Select one or more hierarchy levels to filter the report</p>

                <div class="row">
                    <!-- Division -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Division</label>
                        <div class="multiselect-wrapper" data-name="division">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select divisions...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php foreach($divisions as $division){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="ardiv{{$division->id}}" value="{{$division->id}}" name="divisions[]">
                                        <label for="ardiv{{$division->id}}">{{$division->division_name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($divisions )}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Regional Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="rsm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select RSMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php 
                                    $rsmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 3){
                                        $rsmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="arrsm{{$user->id}}" value="{{$user->id}}" name="rsms[]">
                                        <label for="arrsm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$rsmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Sales Manager -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Area Sales Manager</label>
                        <div class="multiselect-wrapper" data-name="asm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ASMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $asmsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 4){
                                        $asmsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="arasm{{$user->id}}" value="{{$user->id}}" name="asms[]">
                                        <label for="arasm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$asmsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Leader -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Team Leader</label>
                        <div class="multiselect-wrapper" data-name="teamleader">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select team leaders...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <?php 
                                    $tlsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 5){
                                        $tlsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="artl{{$user->id}}" value="{{$user->id}}" name="tls[]">
                                        <label for="artl{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$tlsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="multiselect-wrapper" data-name="adm">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select ADMs...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                     <?php 
                                    $admsCount = 0;
                                    foreach($users as $user){ 
                                    if($user->user_role == 6){
                                        $admsCount++;
                                    ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="aradm{{$user->id}}" value="{{$user->id}}" name="adms[]">
                                        <label for="aradm{{$user->id}}">{{$user->userDetails->name}}</label>
                                    </div>
                                    <?php }} ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{$admsCount}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Customer Wise Filtering -->
            <div class="filter-section">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="section-number">3</div>
                        <h2 class="section-title">Customer Wise Filtering</h2>
                    </div>
                </div>

                <p class="section-subtitle">Select one or more customers directly</p>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">Customers</label>
                        <div class="multiselect-wrapper" data-name="customer">
                            <div class="multiselect-trigger">
                                <div class="multiselect-content">
                                    <span class="multiselect-placeholder">Select customers...</span>
                                </div>
                                <span class="multiselect-arrow"></span>
                            </div>
                            <div class="multiselect-dropdown">
                                <div class="multiselect-search">
                                    <input type="text" placeholder="Search...">
                                </div>
                                <div class="multiselect-actions">
                                    <button class="select-all" type="button">Select All</button>
                                    <button class="clear-all" type="button">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                   <?php foreach($customers as $customer){ ?>
                                        <div class="multiselect-option">
                                            <input type="checkbox" id="arcust{{$customer->id}}" value="{{$customer->customer_id}}" name="customers[]">
                                        <label for="arcust{{$customer->id}}">{{$customer->name}}</label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of {{count($customers)}} selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" type="button" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
                                    </form>
        </div>



    </div>
</div>


<!-- Toast message -->
<div id="user-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
    role="alert" aria-live="assertive" aria-atomic="true"
    style="z-index: 9999; display: none; min-width: 320px;">
    <div class="d-flex align-items-center">
        <span class="toast-icon-circle d-flex align-items-center justify-content-center me-3">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#fff" />
                <path d="M7 12.5l3 3 7-7" stroke="#28a745" stroke-width="2" fill="none"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
        <div class="toast-body flex-grow-1">
            Report downloaded successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>

@include('layouts.footer2')
<!-- Report dropdown → show filters -->
<script>
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        const button = dropdown.querySelector('.custom-dropdown');
        const items = dropdown.querySelectorAll('.dropdown-item[data-report]');
        const allFilterSections = document.querySelectorAll('.report-filters');

        items.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const selectedText = this.textContent.trim();
                const reportKey = this.getAttribute('data-report');

                // Update dropdown button text
                button.innerHTML = selectedText + '<span class="custom-arrow"></span>';

                // Hide all filter sections
                allFilterSections.forEach(section => {
                    section.classList.add('d-none');
                });

                // Show only matching filter section
                const activeSection = document.querySelector(
                    `.report-filters[data-report="${reportKey}"]`
                );

                if (activeSection) {
                    activeSection.classList.remove('d-none');
                }
            });
        });
    });
</script>

<!-- Toast message on submit -->
{{-- <script>
    document.querySelector('.submit').addEventListener('click', function(e) {
        e.preventDefault();
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    });
</script> --}}

<!-- Multiselect dropdown logic -->
<script>
    // Initialize all multiselect dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        const multiselectWrappers = document.querySelectorAll('.multiselect-wrapper');

        multiselectWrappers.forEach(wrapper => {
            const trigger = wrapper.querySelector('.multiselect-trigger');
            const dropdown = wrapper.querySelector('.multiselect-dropdown');
            const content = wrapper.querySelector('.multiselect-content');
            const searchInput = wrapper.querySelector('.multiselect-search input');
            const selectAllBtn = wrapper.querySelector('.select-all');
            const clearAllBtn = wrapper.querySelector('.clear-all');
            const options = wrapper.querySelectorAll('.multiselect-option');
            const checkboxes = wrapper.querySelectorAll('input[type="checkbox"]');
            const countText = wrapper.querySelector('.count-text');

            // Toggle dropdown
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const isActive = dropdown.classList.contains('show');

                // Close all other dropdowns
                document.querySelectorAll('.multiselect-dropdown').forEach(dd => {
                    dd.classList.remove('show');
                });
                document.querySelectorAll('.multiselect-trigger').forEach(t => {
                    t.classList.remove('active');
                });

                if (!isActive) {
                    dropdown.classList.add('show');
                    trigger.classList.add('active');
                }
            });

            // Update display
            function updateDisplay() {
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                content.innerHTML = '';

                if (selected.length === 0) {
                    content.innerHTML =
                        `<span class="multiselect-placeholder">${trigger.getAttribute('data-placeholder') || 'Select options'}</span>`;
                } else {
                    selected.forEach(value => {
                        const tag = document.createElement('span');
                        tag.className = 'multiselect-tag';
                        tag.innerHTML = `
                                ${value}
                                <span class="tag-close" data-value="${value}">×</span>
                            `;
                        content.appendChild(tag);
                    });
                }

                // Update count
                const total = checkboxes.length;
                countText.textContent = `${selected.length} of ${total} selected`;

                // Add event listeners to tag close buttons
                content.querySelectorAll('.tag-close').forEach(closeBtn => {
                    closeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.getAttribute('data-value');
                        const checkbox = Array.from(checkboxes).find(cb => cb.value ===
                            value);
                        if (checkbox) {
                            checkbox.checked = false;
                            updateDisplay();
                        }
                    });
                });
            }

            // Store original placeholder
            trigger.setAttribute('data-placeholder', content.querySelector('.multiselect-placeholder')
                .textContent);

            // Checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateDisplay);
            });

            // Select all
            selectAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const visibleCheckboxes = Array.from(checkboxes).filter(cb =>
                    cb.closest('.multiselect-option').style.display !== 'none'
                );
                visibleCheckboxes.forEach(cb => cb.checked = true);
                updateDisplay();
            });

            // Clear all
            clearAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                checkboxes.forEach(cb => cb.checked = false);
                updateDisplay();
            });

            // Search functionality
            searchInput.addEventListener('input', function(e) {
                e.stopPropagation();
                const searchTerm = this.value.toLowerCase();

                options.forEach(option => {
                    const label = option.querySelector('label').textContent
                    .toLowerCase();
                    if (label.includes(searchTerm)) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });

            // Prevent dropdown close on click inside
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Initialize display
            updateDisplay();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.multiselect-dropdown').forEach(dd => {
                dd.classList.remove('show');
            });
            document.querySelectorAll('.multiselect-trigger').forEach(t => {
                t.classList.remove('active');
            });
        });
    });

    // Reset all filters function
    function resetAllFilters() {
        // Reset all checkboxes
        document.querySelectorAll('.multiselect-wrapper input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });

        // Reset date inputs
        document.querySelectorAll('.date-input').forEach(input => {
            input.value = '';
        });

        // Reinitialize all displays
        document.querySelectorAll('.multiselect-wrapper').forEach(wrapper => {
            const content = wrapper.querySelector('.multiselect-content');
            const trigger = wrapper.querySelector('.multiselect-trigger');
            const placeholder = trigger.getAttribute('data-placeholder');
            content.innerHTML = `<span class="multiselect-placeholder">${placeholder}</span>`;

            const countText = wrapper.querySelector('.count-text');
            const total = wrapper.querySelectorAll('input[type="checkbox"]').length;
            countText.textContent = `0 of ${total} selected`;
        });

        console.log('All filters reset');
    }

    // Generate report function
    function generateReport() {
        // Collect all filter values
        const filters = {
            dateRange: {
                from: document.querySelectorAll('.date-input')[0].value,
                to: document.querySelectorAll('.date-input')[1].value
            },
            hierarchy: {},
            customers: []
        };

        // Collect hierarchy filters
        document.querySelectorAll('.multiselect-wrapper').forEach(wrapper => {
            const name = wrapper.getAttribute('data-name');
            const selected = Array.from(wrapper.querySelectorAll('input[type="checkbox"]:checked'))
                .map(cb => cb.value);

            if (name === 'customer') {
                filters.customers = selected;
            } else {
                filters.hierarchy[name] = selected;
            }
        });

        console.log('Generating report with filters:', filters);
        alert('Report generation started! Check console for filter details.');
    }
</script>

<script>
$(document).ready(function () {
    $('.select2-filter').select2({
        width: '100%',
    });
});
</script>