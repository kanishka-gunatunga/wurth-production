@include('layouts.dashboard-header')

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
                        <li><a class="dropdown-item" href="#" data-report="WO">Write-off</a></li>
                        <li><a class="dropdown-item" href="#" data-report="SO">Set-off</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="ARA" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust1" value="ABC Corporation">
                                        <label for="cust1">ABC Corporation</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust2" value="XYZ Industries">
                                        <label for="cust2">XYZ Industries</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust3" value="Global Trading Co">
                                        <label for="cust3">Global Trading Co</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust4" value="Tech Solutions Ltd">
                                        <label for="cust4">Tech Solutions Ltd</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust5" value="Prime Enterprises">
                                        <label for="cust5">Prime Enterprises</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="YOO" style="border-radius: 8px;">
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="2000">
                                        <label for="div1">2000</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="2001">
                                        <label for="div2">2001</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="2002">
                                        <label for="div3">2002</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="2003">
                                        <label for="div4">2003</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="MOM" style="border-radius: 8px;">
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="2000">
                                        <label for="div1">2000</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="2001">
                                        <label for="div2">2001</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="2002">
                                        <label for="div3">2002</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="2003">
                                        <label for="div4">2003</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="ODB" style="border-radius: 8px;">
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust1" value="ABC Corporation">
                                        <label for="cust1">ABC Corporation</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust2" value="XYZ Industries">
                                        <label for="cust2">XYZ Industries</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust3" value="Global Trading Co">
                                        <label for="cust3">Global Trading Co</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust4" value="Tech Solutions Ltd">
                                        <label for="cust4">Tech Solutions Ltd</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust5" value="Prime Enterprises">
                                        <label for="cust5">Prime Enterprises</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="DSO" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                        <label class="custom-input-label">Customers</label>
                        <div class="dropdown w-100">
                            <button class="btn custom-dropdown w-100 text-start" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Select Customer
                                <span class="custom-arrow"></span>
                            </button>
                            <ul class="dropdown-menu custom-dropdown-menu w-100"
                                aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">Customer
                                        1</a></li>
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">Customer
                                        2</a></li>
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">Customer
                                        3</a></li>
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">Customer
                                        4</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="CCD" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                    <!-- ADM -->
                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">ADM</label>
                        <div class="dropdown w-100">
                            <button class="btn custom-dropdown w-100 text-start" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Select ADM
                                <span class="custom-arrow"></span>
                            </button>
                            <ul class="dropdown-menu custom-dropdown-menu w-100"
                                aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">ADM 1</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">ADM 2</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">ADM 3</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-value="return_cheque">ADM 4</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="TVC" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust1" value="ABC Corporation">
                                        <label for="cust1">ABC Corporation</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust2" value="XYZ Industries">
                                        <label for="cust2">XYZ Industries</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust3" value="Global Trading Co">
                                        <label for="cust3">Global Trading Co</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust4" value="Tech Solutions Ltd">
                                        <label for="cust4">Tech Solutions Ltd</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="cust5" value="Prime Enterprises">
                                        <label for="cust5">Prime Enterprises</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="DCT" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="PBD" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="DMDR" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="PDCT" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="RCS" style="border-radius: 8px;">
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
                            <input type="date" class="date-input" value="2025-12-28">
                            <!-- <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M12.75 10.5C12.9489 10.5 13.1397 10.421 13.2803 10.2803C13.421 10.1397 13.5 9.94891 13.5 9.75C13.5 9.55109 13.421 9.36032 13.2803 9.21967C13.1397 9.07902 12.9489 9 12.75 9C12.5511 9 12.3603 9.07902 12.2197 9.21967C12.079 9.36032 12 9.55109 12 9.75C12 9.94891 12.079 10.1397 12.2197 10.2803C12.3603 10.421 12.5511 10.5 12.75 10.5ZM12.75 13.5C12.9489 13.5 13.1397 13.421 13.2803 13.2803C13.421 13.1397 13.5 12.9489 13.5 12.75C13.5 12.5511 13.421 12.3603 13.2803 12.2197C13.1397 12.079 12.9489 12 12.75 12C12.5511 12 12.3603 12.079 12.2197 12.2197C12.079 12.3603 12 12.5511 12 12.75C12 12.9489 12.079 13.1397 12.2197 13.2803C12.3603 13.421 12.5511 13.5 12.75 13.5ZM9.75 9.75C9.75 9.94891 9.67098 10.1397 9.53033 10.2803C9.38968 10.421 9.19891 10.5 9 10.5C8.80109 10.5 8.61032 10.421 8.46967 10.2803C8.32902 10.1397 8.25 9.94891 8.25 9.75C8.25 9.55109 8.32902 9.36032 8.46967 9.21967C8.61032 9.07902 8.80109 9 9 9C9.19891 9 9.38968 9.07902 9.53033 9.21967C9.67098 9.36032 9.75 9.55109 9.75 9.75ZM9.75 12.75C9.75 12.9489 9.67098 13.1397 9.53033 13.2803C9.38968 13.421 9.19891 13.5 9 13.5C8.80109 13.5 8.61032 13.421 8.46967 13.2803C8.32902 13.1397 8.25 12.9489 8.25 12.75C8.25 12.5511 8.32902 12.3603 8.46967 12.2197C8.61032 12.079 8.80109 12 9 12C9.19891 12 9.38968 12.079 9.53033 12.2197C9.67098 12.3603 9.75 12.5511 9.75 12.75ZM5.25 10.5C5.44891 10.5 5.63968 10.421 5.78033 10.2803C5.92098 10.1397 6 9.94891 6 9.75C6 9.55109 5.92098 9.36032 5.78033 9.21967C5.63968 9.07902 5.44891 9 5.25 9C5.05109 9 4.86032 9.07902 4.71967 9.21967C4.57902 9.36032 4.5 9.55109 4.5 9.75C4.5 9.94891 4.57902 10.1397 4.71967 10.2803C4.86032 10.421 5.05109 10.5 5.25 10.5ZM5.25 13.5C5.44891 13.5 5.63968 13.421 5.78033 13.2803C5.92098 13.1397 6 12.9489 6 12.75C6 12.5511 5.92098 12.3603 5.78033 12.2197C5.63968 12.079 5.44891 12 5.25 12C5.05109 12 4.86032 12.079 4.71967 12.2197C4.57902 12.3603 4.5 12.5511 4.5 12.75C4.5 12.9489 4.57902 13.1397 4.71967 13.2803C4.86032 13.421 5.05109 13.5 5.25 13.5Z" fill="#353535" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.24925 1.3125C5.39843 1.3125 5.54151 1.37176 5.647 1.47725C5.75249 1.58274 5.81175 1.72582 5.81175 1.875V2.44725C6.30825 2.4375 6.855 2.4375 7.4565 2.4375H10.5412C11.1435 2.4375 11.6902 2.4375 12.1867 2.44725V1.875C12.1867 1.72582 12.246 1.58274 12.3515 1.47725C12.457 1.37176 12.6001 1.3125 12.7492 1.3125C12.8984 1.3125 13.0415 1.37176 13.147 1.47725C13.2525 1.58274 13.3117 1.72582 13.3117 1.875V2.49525C13.5067 2.51025 13.6915 2.52925 13.866 2.55225C14.745 2.67075 15.4567 2.91975 16.0185 3.48075C16.5795 4.0425 16.8285 4.75425 16.947 5.63325C17.0617 6.48825 17.0617 7.5795 17.0617 8.958V10.542C17.0617 11.9205 17.0617 13.0125 16.947 13.8668C16.8285 14.7458 16.5795 15.4575 16.0185 16.0192C15.4567 16.5802 14.745 16.8293 13.866 16.9478C13.011 17.0625 11.9197 17.0625 10.5412 17.0625H7.458C6.0795 17.0625 4.9875 17.0625 4.13325 16.9478C3.25425 16.8293 2.5425 16.5802 1.98075 16.0192C1.41975 15.4575 1.17075 14.7458 1.05225 13.8668C0.9375 13.0118 0.9375 11.9205 0.9375 10.542V8.958C0.9375 7.5795 0.9375 6.4875 1.05225 5.63325C1.17075 4.75425 1.41975 4.0425 1.98075 3.48075C2.5425 2.91975 3.25425 2.67075 4.13325 2.55225C4.30825 2.52925 4.493 2.51025 4.6875 2.49525V1.875C4.6875 1.72595 4.74666 1.58299 4.85199 1.47752C4.95731 1.37205 5.1002 1.3127 5.24925 1.3125ZM4.28175 3.6675C3.528 3.76875 3.093 3.95925 2.77575 4.2765C2.4585 4.59375 2.268 5.02875 2.16675 5.7825C2.14975 5.91 2.13525 6.04475 2.12325 6.18675H15.8752C15.8632 6.04475 15.8488 5.90975 15.8317 5.78175C15.7305 5.028 15.54 4.593 15.2227 4.27575C14.9055 3.9585 14.4705 3.768 13.716 3.66675C12.9457 3.56325 11.9295 3.56175 10.4992 3.56175H7.49925C6.069 3.56175 5.0535 3.564 4.28175 3.6675ZM2.06175 9C2.06175 8.3595 2.06175 7.80225 2.0715 7.3125H15.927C15.9367 7.80225 15.9367 8.3595 15.9367 9V10.5C15.9367 11.9303 15.9352 12.9465 15.8317 13.7175C15.7305 14.4713 15.54 14.9062 15.2227 15.2235C14.9055 15.5408 14.4705 15.7313 13.716 15.8325C12.9457 15.936 11.9295 15.9375 10.4992 15.9375H7.49925C6.069 15.9375 5.0535 15.936 4.28175 15.8325C3.528 15.7313 3.093 15.5408 2.77575 15.2235C2.4585 14.9062 2.268 14.4712 2.16675 13.7167C2.06325 12.9465 2.06175 11.9303 2.06175 10.5V9Z" fill="#353535" />
                            </svg> -->
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <label class="custom-input-label">To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" class="date-input" value="2026-01-10">
                            <!-- <svg class="calendar-icon " width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="6" width="18" height="15" rx="2" stroke="currentColor" stroke-width="2" />
                                <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                <path d="M8 3v4M16 3v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg> -->
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div1" value="Automotive Division">
                                        <label for="div1">Automotive Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div2" value="Construction Division">
                                        <label for="div2">Construction Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div3" value="Industrial Division">
                                        <label for="div3">Industrial Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div4" value="Marine Division">
                                        <label for="div4">Marine Division</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="div5" value="Power Division">
                                        <label for="div5">Power Division</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm1" value="John Anderson">
                                        <label for="rsm1">John Anderson</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm2" value="Sarah Mitchell">
                                        <label for="rsm2">Sarah Mitchell</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm3" value="Michael Chen">
                                        <label for="rsm3">Michael Chen</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm4" value="Emily Davis">
                                        <label for="rsm4">Emily Davis</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="rsm5" value="Robert Wilson">
                                        <label for="rsm5">Robert Wilson</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm1" value="David Brown">
                                        <label for="asm1">David Brown</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm2" value="Lisa Garcia">
                                        <label for="asm2">Lisa Garcia</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm3" value="James Taylor">
                                        <label for="asm3">James Taylor</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm4" value="Jennifer Lee">
                                        <label for="asm4">Jennifer Lee</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="asm5" value="William Martinez">
                                        <label for="asm5">William Martinez</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl1" value="Daniel Rodriguez">
                                        <label for="tl1">Daniel Rodriguez</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl2" value="Patricia White">
                                        <label for="tl2">Patricia White</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl3" value="Christopher Hall">
                                        <label for="tl3">Christopher Hall</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl4" value="Amanda Clark">
                                        <label for="tl4">Amanda Clark</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="tl5" value="Matthew Lewis">
                                        <label for="tl5">Matthew Lewis</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
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
                                    <button class="select-all">Select All</button>
                                    <button class="clear-all">Clear All</button>
                                </div>
                                <div class="multiselect-options">
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm1" value="Kevin Walker">
                                        <label for="adm1">Kevin Walker</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm2" value="Sandra Young">
                                        <label for="adm2">Sandra Young</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm3" value="Brian King">
                                        <label for="adm3">Brian King</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm4" value="Michelle Wright">
                                        <label for="adm4">Michelle Wright</label>
                                    </div>
                                    <div class="multiselect-option">
                                        <input type="checkbox" id="adm5" value="Steven Green">
                                        <label for="adm5">Steven Green</label>
                                    </div>
                                </div>
                                <div class="multiselect-footer">
                                    <span class="count-text">0 of 5 selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="btn-reset" onclick="resetAllFilters()">Reset All Filters</button>
                <button class="red-action-btn-lg submit">
                    Generate Report
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('reports.download') }}">
            @csrf
            <input type="hidden" name="type" value="WO">
            <div class="styled-tab-sub p-4 report-filters d-none" data-report="WO" style="border-radius: 8px;">
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
                                <input type="date" name="from_date" class="date-input" required>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <label class="custom-input-label">To Date</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="to_date" class="date-input" required>
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
            </div>
        </form>

        <form method="POST" action="{{ route('reports.download') }}">
            @csrf
            <input type="hidden" name="type" value="SO">
            <div class="styled-tab-sub p-4 report-filters d-none" data-report="SO" style="border-radius: 8px;">
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
                                <input type="date" name="from_date" class="date-input" required>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 mb-3">
                            <label class="custom-input-label">To Date</label>
                            <div class="date-input-wrapper">
                                <input type="date" name="to_date" class="date-input" required>
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
            </div>
        </form>

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
