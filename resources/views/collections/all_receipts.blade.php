@include('layouts.dashboard-header')
@php
$activeTab = request('active_tab', 'final');
@endphp


<style>
    /* Search box styles */
    .search-box-wrapper {
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #fff;
        transition: width 0.3s ease;
        border-radius: 30px;
        height: 45px;
        width: 45px;
        border: 1px solid transparent;
        position: relative;
        width: 0;
    }

    .search-box-wrapper.collapsed {
        width: 0;
        padding: 0;
        margin: 0;
        border: 1px solid transparent;
        background-color: transparent;
    }

    .search-box-wrapper.expanded {
        width: 450px;
        padding: 0 15px;
    }

    .search-input {
        flex-grow: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 16px;
        color: #333;
        width: 100%;
        padding-left: 30px;
        /* space for icon */
    }

    .search-input::placeholder {
        color: #888;
    }

    .search-icon-inside {
        position: absolute;
        left: 10px;
        color: #888;
    }

    /* Optional: Adjust button alignment if needed */
    .col-12.d-flex.justify-content-lg-end {
        align-items: center;
    }

    /* Checkbox styling (for advance payment tab) */
    .form-check-input {
        height: 20px;
        width: 20px;
        border-color: #D2D5DA;
        margin-right: 15px;
    }

    .form-check-input:focus {
        border-color: #dc3545 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2.1px #dc354533 !important;
    }

    .form-check-input:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .form-check-label {
        font-family: "Inter", sans-serif;
        font-size: 20px;
        font-weight: 400;
    }

    .custom-dropdown-menu li {
        list-style: none !important;
    }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">All Receipt</h1>
        </div>

    </div>


    <div class="styled-tab-main">
        <ul class="nav nav-tabs">
            <li class="nav-item mb-3">
                <a class="nav-link {{ $activeTab == 'final' ? 'active' : '' }}" aria-current="page" href="#" id="final-reciepts-invoices"
                    data-bs-toggle="tab" data-bs-target="#final-reciepts-invoices-pane" type="button"
                    role="tab" aria-controls="final-reciepts-invoices-pane" aria-selected="true">

                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.5 7H16.5M8.5 11H16.5M8.5 15H12.5M20.5 21V5C20.5 4.46957 20.2893 3.96086 19.9142 3.58579C19.5391 3.21071 19.0304 3 18.5 3H6.5C5.96957 3 5.46086 3.21071 5.08579 3.58579C4.71071 3.96086 4.5 4.46957 4.5 5V21L7 19L10 21L12.5 19L15 21L18 19L20.5 21Z"
                            stroke="#CC0000" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>

                    Final Receipts - Invoices
                </a>
            </li>



            <li class="nav-item mb-3">
                <a class="nav-link {{ $activeTab == 'temporary' ? 'active' : '' }}" aria-current="page" href="#" id="temporary-receipts-invoices"
                    data-bs-toggle="tab" data-bs-target="#temporary-receipts-invoices-pane" type="button"
                    role="tab" aria-controls="temporary-receipts-invoices-pane" aria-selected="false">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 3.5L18.5 2L17 3.5L15.5 2L14 3.5L12.5 2L11 3.5L9.5 2L8 3.5L6.5 2L5 3.5L3.5 2V22L5 20.5L6.5 22L8 20.5L9.5 22L11.08 20.42C11.22 20.61 11.38 20.78 11.55 20.95C12.8619 22.2423 14.6302 22.9657 16.4717 22.9634C18.3133 22.9611 20.0797 22.2334 21.3885 20.9378C22.6972 19.6423 23.4427 17.8832 23.4636 16.0418C23.4845 14.2004 22.779 12.4249 21.5 11.1V2L20 3.5ZM19.5 9.68C18.57 9.24 17.55 9 16.5 9C12.64 9 9.5 12.13 9.5 16C9.5 17.05 9.74 18.07 10.18 19H5.5V5H19.5V9.68ZM21.35 16C21.35 16.64 21.23 17.27 21 17.86C20.74 18.44 20.38 19 19.93 19.43C19.5 19.88 18.94 20.24 18.36 20.5C17.77 20.73 17.14 20.85 16.5 20.85C13.82 20.85 11.65 18.68 11.65 16C11.65 14.71 12.16 13.5 13.07 12.57C14 11.66 15.21 11.15 16.5 11.15C19.17 11.15 21.35 13.32 21.35 16ZM15.5 16.69V13H17V15.82L19.44 17.23L18.69 18.53L15.5 16.69Z"
                            fill="#CC0000" />
                    </svg>


                    Temporary Receipts - Invoices
                </a>
            </li>


            <li class="nav-item mb-3">
                <a class="nav-link {{ $activeTab == 'advance' ? 'active' : '' }}" aria-current="page" href="#" id="temporary-receipts-advance-payment"
                    data-bs-toggle="tab" data-bs-target="#temporary-receipts-advance-payment-pane"
                    type="button" role="tab" aria-controls="temporary-receipts-advance-payment-pane"
                    aria-selected="false">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M19.5 3.5L18 2L16.5 3.5L15 2L13.5 3.5L12 2L10.5 3.5L9 2L7.5 3.5L6 2L4.5 3.5L3 2V22L4.5 20.5L6 22L7.5 20.5L9 22L10.58 20.42C10.72 20.61 10.88 20.78 11.05 20.95C12.3619 22.2423 14.1302 22.9657 15.9717 22.9634C17.8133 22.9611 19.5797 22.2334 20.8885 20.9378C22.1972 19.6423 22.9427 17.8832 22.9636 16.0418C22.9845 14.2004 22.279 12.4249 21 11.1V2L19.5 3.5ZM19 9.68C18.07 9.24 17.05 9 16 9C12.14 9 9 12.13 9 16C9 17.05 9.24 18.07 9.68 19H5V5H19V9.68ZM20.85 16C20.85 16.64 20.73 17.27 20.5 17.86C20.24 18.44 19.88 19 19.43 19.43C19 19.88 18.44 20.24 17.86 20.5C17.27 20.73 16.64 20.85 16 20.85C13.32 20.85 11.15 18.68 11.15 16C11.15 14.71 11.66 13.5 12.57 12.57C13.5 11.66 14.71 11.15 16 11.15C18.67 11.15 20.85 13.32 20.85 16ZM15 16.69V13H16.5V15.82L18.94 17.23L18.19 18.53L15 16.69Z"
                            fill="#CC0000" />
                    </svg>



                    Temporary Receipts - Advance Payment
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade {{ $activeTab == 'final' ? 'show active' : '' }}" id="final-reciepts-invoices-pane" role="tabpanel"
                aria-labelledby="final-reciepts-invoices" tabindex="0">
                <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <form method="GET" action="{{ url('/all-receipts') }}">
                              <input type="hidden" name="active_tab" value="final">
                            <div id="final-search-box-wrapper" class="search-box-wrapper collapsed">
                                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                                <input type="text" name="final_search" class="search-input" placeholder="Search Receipt, Invoice, ADM or Customer" value="{{ request('final_search') }}" />
                            </div>
                        </form>
                        <button class="header-btn" id="final-search-toggle-button">
                            <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                        </button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#finalFilter">
                            <i class="fa-solid fa-filter fa-xl"></i>
                        </button>
                    </div>
                </div>
                 @if(in_array('all-receipts-final-export', session('permissions', [])))   
                <div class="col-12 d-flex justify-content-end pe-0 mb-3 gap-3">
           
                    <a href="{{ route('final.receipts.export', request()->query()) }}"
                        class="add-new-division-btn mb-3 submit">
                            Export
                        </a>

                </div>
                @endif
                <div class="table-responsive">
                    <table class="table custom-table-locked" style="min-width: 1300px;">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>ADM Name</th>
                                <th>ADM Number</th>
                                <th>Receipt Number</th>
                                <th>Issue Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="sticky-column">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($regular_receipts as $payment)
                            <tr>
                                <td>{{ $payment->invoice->customer->name ?? 'N/A' }}</td>
                                <td>{{ $payment->invoice->customer->admDetails->name ?? 'N/A' }}</td>
                                <td>{{ $payment->invoice->customer->adm ?? 'N/A' }}</td>
                                <td>{{ $payment->id ?? 'N/A' }}</td>
                                <td>{{ $payment->created_at ?? 'N/A' }}</td>
                                <td>{{ number_format($payment->amount, 2) ?? '0.00' }}</td>
                                <td>@if($payment->status == 'pending')
                                    <button class="grey-status-btn"> Pending</button>
                                    @endif
                                    @if($payment->status == 'deposited')
                                    <button class="blue-status-btn"> Deposited</button>
                                    @endif
                                    @if($payment->status == 'exported')
                                    <button class="yellow-status-btn"> Exported</button>
                                    @endif
                                    @if($payment->status == 'approved')
                                    <button class="success-status-btn"> Approved</button>
                                    @endif
                                    @if($payment->status == 'voided')
                                    <button class="danger-status-btn"> Voided</button>
                                    @endif

                                </td>

                                <!-- Actions -->
                                <td class="sticky-column">
                                    <div class="sticky-actions">
                                        @if(in_array('all-receipts-final-sms', session('permissions', [])))
                                        <button class="red-action-btn resend-sms-btn"
                                            data-receipt-id="{{ $payment->id }}"
                                            data-primary="{{ $payment->invoice->customer->mobile_number ?? '' }}"
                                            data-secondary="{{ $payment->invoice->customer->secondary_mobile ?? '' }}">
                                            Resend SMS
                                        </button>
                                        @endif 
                                        @if(in_array('all-receipts-final-download', session('permissions', [])))
                                        <a href="{{ $payment->pdf_path ? asset($payment->pdf_path) : '#' }}">
                                            <button class="black-action-btn">Download</button>
                                        </a>
                                        @endif
                                        @if(in_array('all-receipts-final-edit', session('permissions', [])))
                                        <!-- <a href="{{ url('/edit-receipt/'.$payment->id) }}"><button class="success-action-btn">Edit</button></a> -->
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No reciepts found.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
                <nav class="d-flex justify-content-center mt-5">
                    {{ $regular_receipts->appends(['final_search' => request('final_search'), 'active_tab' => 'final'])->links('pagination::bootstrap-5') }}
                </nav>
            </div>

            <div class="tab-pane fade {{ $activeTab == 'temporary' ? 'show active' : '' }}" id="temporary-receipts-invoices-pane" role="tabpanel"
                aria-labelledby="temporary-receipts-invoices" tabindex="0">
                <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <form method="GET" action="{{ url('/all-receipts') }}">
                             <input type="hidden" name="active_tab" value="temporary">
                            <div id="tr-search-box-wrapper" class="search-box-wrapper collapsed">
                                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                                <input type="text" name="temp_search" class="search-input" placeholder="Search Receipt, ADM or Customer" value="{{ request('temp_search') }}" />
                            </div>
                        </form>
                        <button class="header-btn" id="tr-search-toggle-button">
                            <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                        </button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#trFilter">
                            <i class="fa-solid fa-filter fa-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table-locked" style="min-width: 1300px;">
                        <thead>
                            <tr>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Receipt Number</th>
                                <th scope="col">Issue Date</th>
                                <th scope="col">Amount</th>
                                <th scope="col">ADM Number</th>
                                <th scope="col">ADM Name</th>
                                <th class="sticky-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($temp_receipts as $temp_receipt)
                            <tr>
                                <td>{{ $temp_receipt->invoice->customer->name ?? 'N/A' }}</td>
                                <td>{{ $temp_receipt->invoice->customer->admDetails->name ?? 'N/A' }}</td>
                                <td>{{ $temp_receipt->invoice->customer->adm ?? 'N/A' }}</td>
                                <td>{{ $temp_receipt->id ?? 'N/A' }}</td>
                                <td>{{ $temp_receipt->created_at ?? 'N/A' }}</td>
                                <td>{{ number_format($temp_receipt->amount, 2) ?? '0.00' }}</td>


                                <!-- Actions -->
                                <td class="sticky-column">
                                    <div class="sticky-actions">
                                         @if(in_array('all-receipts-temporary-sms', session('permissions', [])))
                                        <button class="red-action-btn resend-sms-btn"
                                            data-receipt-id="{{ $temp_receipt->id }}"
                                            data-primary="{{ $temp_receipt->invoice->customer->mobile_number ?? '' }}"
                                            data-secondary="{{ $temp_receipt->invoice->customer->secondary_mobile ?? '' }}">
                                            Resend SMS
                                        </button>
                                        @endif
                                         @if(in_array('all-receipts-temporary-download', session('permissions', [])))
                                        <a href="{{ $temp_receipt->pdf_path ? asset($temp_receipt->pdf_path) : '#' }}">
                                            <button class="black-action-btn">Download</button>
                                        </a>
                                        @endif
                                         @if(in_array('all-receipts-temporary-edit', session('permissions', [])))
                                        <!-- <a href="{{ url('/edit-receipt/'.$temp_receipt->id) }}"><button class="success-action-btn">Edit</button></a> -->
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No reciepts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
                <nav class="d-flex justify-content-center mt-5">
                    {{ $temp_receipts->appends(['temp_search' => request('temp_search'), 'active_tab' => 'temporary'])->links('pagination::bootstrap-5') }}
                </nav>
            </div>


            <div class="tab-pane fade {{ $activeTab == 'advance' ? 'show active' : '' }}" id="temporary-receipts-advance-payment-pane" role="tabpanel"
                aria-labelledby="temporary-receipts-advance-payment" tabindex="0">
                <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <form method="GET" action="{{ url('/all-receipts') }}">
                             <input type="hidden" name="active_tab" value="advance">
                            <div id="receipts-search-box-wrapper" class="search-box-wrapper collapsed">
                                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                                <input type="text" name="advance_search" class="search-input" placeholder="Search Receipt, ADM or Customer" value="{{ request('advance_search') }}" />
                            </div>
                        </form>
                        <button class="header-btn" id="receipts-search-toggle-button">
                            <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                        </button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#receiptsFilter">
                            <i class="fa-solid fa-filter fa-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end pe-0 mb-3 gap-3">
                    <tr class="checkbox-item" data-name="Apple">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="item1" name="item1">
                                <label class="form-check-label ms-2" for="item1">
                                    Show hides
                                </label>
                            </div>
                        </td>
                    </tr>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table-locked" style="min-width: 1300px;">
                        <thead>
                            <tr>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Receipt Number</th>
                                <th scope="col">Issue Date</th>
                                <th scope="col">Amount</th>
                                <th scope="col">ADM Number</th>
                                <th scope="col">ADM Name</th>
                                <th class="sticky-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($advanced_payments as $advanced_payment)
                            <tr>
                                <td>{{ $advanced_payment->customerData->name ?? 'N/A' }}</td>
                                <td>{{ $advanced_payment->id ?? 'N/A' }}</td>
                                <td>{{ $advanced_payment->created_at ?? 'N/A' }}</td>
                                <td>{{ number_format($advanced_payment->payment_amount, 2) ?? '0.00' }}</td>
                                <td>{{ $advanced_payment->adm->userDetails->adm_number ?? 'N/A' }}</td>
                                <td>{{ $advanced_payment->adm->userDetails->name ?? 'N/A' }}</td>
                                <!-- Actions -->
                                <td class="sticky-column">
                                    <div class="sticky-actions">
                                         @if(in_array('all-receipts-advanced-sms', session('permissions', [])))
                                        <button class="red-action-btn resend-sms-btn"
                                            data-receipt-id="{{ $advanced_payment->id }}"
                                            data-primary="{{ $advanced_payment->customerData->mobile_number ?? '' }}"
                                            data-secondary="{{ $advanced_payment->customerData->secondary_mobile ?? '' }}">
                                            Resend SMS
                                        </button>
                                        @endif
                                         @if(in_array('all-receipts-advanced-download', session('permissions', [])))
                                        <a href="{{asset('uploads/adm/advanced_payments/attachments/'.$advanced_payment->attachment.'')}}" download>
                                            <button class="black-action-btn">Download</button>
                                        </a>
                                        @endif
                                         @if(in_array('all-receipts-advanced-edit', session('permissions', [])))
                                        <!-- <a href="{{ url('/edit-advanced-payment/'.$advanced_payment->id) }}"><button class="success-action-btn">Edit</button></a> -->
                                        @endif
                                         @if(in_array('all-receipts-advanced-remove', session('permissions', [])))
                                        <a href="{{ url('/remove-advanced-payment/'.$advanced_payment->id) }}"><button class="red-action-btn">Remove</button></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No reciepts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
                <nav class="d-flex justify-content-center mt-5">
                    {{ $advanced_payments->appends(['advance_search' => request('advance_search'), 'active_tab' => 'advance'])->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
</div>


<!-- Final reciepts Filter Offcanvas -->
<form method="GET" action="{{ url('/all-receipts') }}">
    @csrf
    <input type="hidden" name="active_tab" value="final">
    <input type="hidden" name="final_status" id="final-status-value" value="{{ request('final_status') }}">

    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="finalFilter" aria-labelledby="finalFilterLabel">
        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title" id="offcanvasRightLabel">Search </span>
                <span class="title-rest"> &nbsp;by Filter</span>
            </div>
            <div>
                <button type="button" class="btn rounded-phill" onclick="clearFinalFiltersAndSubmit()">Clear All</button>
            </div>
        </div>

        <div class="offcanvas-body">
            <!-- ADM Name Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM Name</p>
                <select id="final-filter-adm-name" name="final_adm_names[]" class="form-control select2" multiple>
                    @foreach ($finalAdmNames as $admName)
                    <option value="{{ $admName }}" {{ in_array($admName, request('final_adm_names', [])) ? 'selected' : '' }}>
                        {{ $admName }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- ADM ID Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select id="final-filter-adm-id" name="final_adm_ids[]" class="form-control select2" multiple>
                    @foreach ($finalAdmIds as $admId)
                    <option value="{{ $admId }}" {{ in_array($admId, request('final_adm_ids', [])) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Customers Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select id="final-filter-customer" name="final_customers[]" class="form-control select2" multiple>
                    @foreach ($finalCustomers as $customer)
                    <option value="{{ $customer }}" {{ in_array($customer, request('final_customers', [])) ? 'selected' : '' }}>
                        {{ $customer }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Styled Status Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Status</p>
                <div class="custom-dropdown-container" style="position: relative; min-width: 200px;">
                    <button type="button" id="final-custom-status-btn" class="btn custom-dropdown text-start" style="width:100%;">
                        {{ request('final_status') ? ucfirst(request('final_status')) : 'Choose Status' }}
                    </button>
                    <ul id="final-custom-status-menu" class="custom-dropdown-menu" style="display:none; position:absolute; top:100%; left:0; background:#fff; border:1px solid #ddd; width:100%; z-index:999;">
                        @foreach ($regular_receipts->pluck('status')->unique() as $status)
                        <li><a href="#" class="dropdown-item" data-value="{{ $status }}">{{ ucfirst($status) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" id="final-filter-date" name="final_date_range" class="form-control"
                    placeholder="Select date range" value="{{ request('final_date_range') }}" />
            </div>

            <div class="mt-4 d-flex justify-content-start">
                <button type="submit" class="red-action-btn-lg">Apply Filters</button>
            </div>
        </div>
    </div>
</form>

<!-- temp reciepts - invoices Filter Offcanvas -->
<form method="GET" action="{{ url('/all-receipts') }}">
    @csrf
    <input type="hidden" name="active_tab" value="temporary">
    <input type="hidden" name="temp_status" id="temp-status-value" value="{{ request('temp_status') }}">

    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="trFilter" aria-labelledby="trFilterLabel">
        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title" id="offcanvasRightLabel">Search </span> <span class="title-rest"> &nbsp;by
                    Filter
                </span>
            </div class="col-6">

            <div>
                <button type="button" class="btn rounded-phill" onclick="clearTempFiltersAndSubmit()">Clear All</button>
            </div>
        </div>

        <div class="offcanvas-body">
            <!-- ADM Name Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM Name</p>
                <select id="temp-filter-adm-name" name="temp_adm_names[]" class="form-control select2" multiple>
                    @foreach ($tempAdmNames as $admName)
                    <option value="{{ $admName }}" {{ in_array($admName, request('temp_adm_names', [])) ? 'selected' : '' }}>
                        {{ $admName }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- ADM ID Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select id="temp-filter-adm-id" name="temp_adm_ids[]" class="form-control select2" multiple>
                    @foreach ($tempAdmIds as $admId)
                    <option value="{{ $admId }}" {{ in_array($admId, request('temp_adm_ids', [])) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Customers Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select id="temp-filter-customer" name="temp_customers[]" class="form-control select2" multiple>
                    @foreach ($tempCustomers as $customer)
                    <option value="{{ $customer }}" {{ in_array($customer, request('temp_customers', [])) ? 'selected' : '' }}>
                        {{ $customer }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Styled Status Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Status</p>
                <div class="custom-dropdown-container" style="position: relative; min-width: 200px;">
                    <button type="button" id="temp-custom-status-btn" class="btn custom-dropdown text-start" style="width:100%;">
                        {{ request('temp_status') ? ucfirst(request('temp_status')) : 'Choose Status' }}
                    </button>
                    <ul id="temp-custom-status-menu" class="custom-dropdown-menu" style="display:none; position:absolute; top:100%; left:0; background:#fff; border:1px solid #ddd; width:100%; z-index:999;">
                        @foreach ($temp_receipts->pluck('status')->unique() as $status)
                        <li><a href="#" class="dropdown-item" data-value="{{ $status }}">{{ ucfirst($status) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" id="temp-filter-date" name="temp_date_range" class="form-control"
                    placeholder="Select date range" value="{{ request('temp_date_range') }}" />
            </div>

            <div class="mt-4 d-flex justify-content-start">
                <button type="submit" class="red-action-btn-lg">Apply Filters</button>
            </div>
        </div>
    </div>
</form>

<!-- temp reciepts - advance payment Filter Offcanvas -->
<form method="GET" action="{{ url('/all-receipts') }}">
    @csrf
    <input type="hidden" name="active_tab" value="advance">
    <input type="hidden" name="advance_status" id="advance-status-value" value="{{ request('advance_status') }}">

    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="receiptsFilter" aria-labelledby="receiptsFilterLabel">
        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title" id="offcanvasRightLabel">Search </span> <span class="title-rest"> &nbsp;by
                    Filter
                </span>
            </div class="col-6">

            <div>
                <button type="button" class="btn rounded-phill" onclick="clearAdvanceFiltersAndSubmit()">Clear All</button>
            </div>
        </div>

        <div class="offcanvas-body">
            <!-- ADM Name Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM Name</p>
                <select id="advance-filter-adm-name" name="advance_adm_names[]" class="form-control select2" multiple>
                    @foreach ($advanceAdmNames as $admName)
                    <option value="{{ $admName }}" {{ in_array($admName, request('advance_adm_names', [])) ? 'selected' : '' }}>
                        {{ $admName }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- ADM ID Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select id="advance-filter-adm-id" name="advance_adm_ids[]" class="form-control select2" multiple>
                    @foreach ($advanceAdmIds as $admId)
                    <option value="{{ $admId }}" {{ in_array($admId, request('advance_adm_ids', [])) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Customers Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select id="advance-filter-customer" name="advance_customers[]" class="form-control select2" multiple>
                    @foreach ($advanceCustomers as $customer)
                    <option value="{{ $customer }}" {{ in_array($customer, request('advance_customers', [])) ? 'selected' : '' }}>
                        {{ $customer }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Styled Status Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Status</p>
                <div class="custom-dropdown-container" style="position: relative; min-width: 200px;">
                    <button type="button" id="advance-custom-status-btn" class="btn custom-dropdown text-start" style="width:100%;">
                        {{ request('advance_status') ? ucfirst(request('advance_status')) : 'Choose Status' }}
                    </button>
                    <ul id="advance-custom-status-menu" class="custom-dropdown-menu" style="display:none; position:absolute; top:100%; left:0; background:#fff; border:1px solid #ddd; width:100%; z-index:999;">
                        @foreach ($advanced_payments->pluck('status')->unique() as $status)
                        <li><a href="#" class="dropdown-item" data-value="{{ $status }}">{{ ucfirst($status) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" id="advance-filter-date" name="advance_date_range" class="form-control"
                    placeholder="Select date range" value="{{ request('advance_date_range') }}" />
            </div>

            <div class="mt-4 d-flex justify-content-start">
                <button type="submit" class="red-action-btn-lg">Apply Filters</button>
            </div>
        </div>
    </div>
</form>

</div>

<!-- Resend SMS Modal -->
<div id="resend-sms-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:400px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2);">
        <form id="resend-sms-form" method="POST" action="{{ url('/resend-receipt') }}">
            @csrf
            <input type="hidden" name="receipt_id" id="sms-receipt-id">

            <h5 style="margin-bottom:1.5rem; font-size:1.25rem; font-weight:600;">Resend SMS</h5>

            <label style="font-weight:600; margin-bottom:0.5rem; display:block;">Select Mobile Number</label>
            <select id="mobile-number" name="mobile" class="form-control" style="width:100%; padding:0.6rem; border-radius:8px; border:1px solid #ddd; margin-bottom:1rem;">
                <option value="">-- Select Number --</option>
            </select>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="optional-checkbox">
                <label for="optional-checkbox" style="font-weight:600;">Optional Number</label>
            </div>
            <input type="text" id="optional-number" class="form-control" name="optional_number" placeholder="Enter Optional number" style="width:100%; padding:0.6rem; border-radius:8px; border:1px solid #ddd; margin-bottom:1.5rem;" disabled>

            <button type="submit" id="resend-sms-btn" style="background:#CC0000; color:#fff; border:none; border-radius:8px; width:100%; padding:0.75rem; font-weight:600; cursor:pointer;">
                Resend SMS
            </button>

            <button type="button" id="resend-sms-close" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </form>
    </div>
</div>


@include('layouts.footer2')

<!-- for dropdown -->
<script>
    // Initialize Select2 and set initial values when offcanvas opens
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for all dropdowns
        $('.select2').select2({
            placeholder: "Select options",
            allowClear: true
        });

        // Initialize status dropdowns with current values
        initializeStatusDropdownWithCurrentValue('final-custom-status-btn', 'final-custom-status-menu', 'final-status-value', "{{ request('final_status') }}");
        initializeStatusDropdownWithCurrentValue('temp-custom-status-btn', 'temp-custom-status-menu', 'temp-status-value', "{{ request('temp_status') }}");
        initializeStatusDropdownWithCurrentValue('advance-custom-status-btn', 'advance-custom-status-menu', 'advance-status-value', "{{ request('advance_status') }}");
    });

    // Enhanced status dropdown initialization with current values
    function initializeStatusDropdownWithCurrentValue(btnId, menuId, hiddenInputId, currentValue) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        const hiddenInput = document.getElementById(hiddenInputId);

        if (!btn || !menu || !hiddenInput) {
            console.log('Element not found:', btnId, menuId, hiddenInputId);
            return;
        }

        // Set current value if exists
        if (currentValue && currentValue !== '') {
            const menuItem = menu.querySelector(`[data-value="${currentValue}"]`);
            if (menuItem) {
                btn.textContent = menuItem.textContent;
                btn.setAttribute('data-value', currentValue);
                hiddenInput.value = currentValue;
            }
        }

        btn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });

        menu.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", (e) => {
                e.preventDefault();
                const value = e.target.dataset.value;
                const text = e.target.textContent;

                btn.textContent = text;
                btn.setAttribute("data-value", value);
                hiddenInput.value = value;
                menu.style.display = "none";
            });
        });

        // Close if clicked outside
        document.addEventListener("click", (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    }

    // Clear Final Receipts Filters and Submit
    function clearFinalFiltersAndSubmit() {
        // Clear all Select2 dropdowns
        $('#final-filter-adm-name').val(null).trigger('change');
        $('#final-filter-adm-id').val(null).trigger('change');
        $('#final-filter-customer').val(null).trigger('change');

        // Clear date range
        document.getElementById('final-filter-date').value = '';

        // Clear status
        document.getElementById('final-status-value').value = '';
        document.getElementById('final-custom-status-btn').innerText = 'Choose Status';

        // Redirect to same page with only active_tab=final
        const url = new URL(window.location.href);
        url.search = ''; // remove all query params
        url.searchParams.set('active_tab', 'final');

        window.location.href = url.toString();
    }

    // Clear Temporary Receipts Filters and Submit
    function clearTempFiltersAndSubmit() {
    // Clear Select2 dropdowns
    $('#temp-filter-adm-name').val(null).trigger('change');
    $('#temp-filter-adm-id').val(null).trigger('change');
    $('#temp-filter-customer').val(null).trigger('change');

    // Clear date
    document.getElementById('temp-filter-date').value = '';

    // Clear status
    document.getElementById('temp-status-value').value = '';
    document.getElementById('temp-custom-status-btn').innerText = 'Choose Status';

    // Redirect to same page with only active_tab=temporary
    const url = new URL(window.location.href);
    url.search = '';
    url.searchParams.set('active_tab', 'temporary');

    window.location.href = url.toString();
}

    // Clear Advance Payments Filters and Submit
    function clearAdvanceFiltersAndSubmit() {
        // Clear Select2 dropdowns
        $('#advance-filter-adm-name').val(null).trigger('change');
        $('#advance-filter-adm-id').val(null).trigger('change');
        $('#advance-filter-customer').val(null).trigger('change');

        // Clear date field
        const advanceDateInput = document.getElementById('advance-filter-date');
        if (advanceDateInput) {
            advanceDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const advanceStatusBtn = document.getElementById('advance-custom-status-btn');
        const advanceStatusInput = document.getElementById('advance-status-value');
        if (advanceStatusBtn) {
            advanceStatusBtn.textContent = 'Choose Status';
            advanceStatusBtn.removeAttribute('data-value');
        }
        if (advanceStatusInput) {
            advanceStatusInput.value = '';
        }

        // Hide status menu if open
        const advanceStatusMenu = document.getElementById('advance-custom-status-menu');
        if (advanceStatusMenu) {
            advanceStatusMenu.style.display = 'none';
        }

        console.log('Advance filters cleared');

        // Submit the form to apply cleared filters
        setTimeout(() => {
            const form = document.querySelector('#receiptsFilter form');
            if (form) {
                form.submit();
            }
        }, 300);
    }
</script>

<script>
    // Enable/Disable Optional Input
    document.getElementById("optional-checkbox").addEventListener("change", function() {
        document.getElementById("optional-number").disabled = !this.checked;
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function setupSearch(wrapperId, toggleId) {
            const searchWrapper = document.getElementById(wrapperId);
            const searchToggleButton = document.getElementById(toggleId);
            const searchInput = searchWrapper.querySelector(".search-input");

            let idleTimeout;
            const idleTime = 5000;

            function collapseSearch() {
                searchWrapper.classList.remove("expanded");
                searchWrapper.classList.add("collapsed");
                searchToggleButton.classList.remove("d-none");
                clearTimeout(idleTimeout);
            }

            function startIdleTimer() {
                clearTimeout(idleTimeout);
                idleTimeout = setTimeout(() => {
                    if (!searchInput.value) collapseSearch();
                }, idleTime);
            }

            searchToggleButton.addEventListener("click", function() {
                if (searchWrapper.classList.contains("collapsed")) {
                    searchWrapper.classList.remove("collapsed");
                    searchWrapper.classList.add("expanded");
                    searchToggleButton.classList.add("d-none");
                    searchInput.focus();
                    startIdleTimer();
                } else {
                    collapseSearch();
                }
            });

            searchInput.addEventListener("keydown", function() {
                startIdleTimer();
            });
        }

        // Apply for each tab
        setupSearch("final-search-box-wrapper", "final-search-toggle-button");
        setupSearch("tr-search-box-wrapper", "tr-search-toggle-button");
        setupSearch("receipts-search-box-wrapper", "receipts-search-toggle-button");
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Final receipts search
        document.querySelector("#final-search-box-wrapper .search-input")
            .addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                const filtered = FinalRecieptInvoices.filter(item =>
                    item.receipt.toLowerCase().includes(query) ||
                    (item.invoiceNumber && item.invoiceNumber.toLowerCase().includes(query)) ||
                    (item.ADMName && item.ADMName.toLowerCase().includes(query)) ||
                    (item.customer && item.customer.toLowerCase().includes(query))
                );
                renderTable("final", filtered, 1);
                renderPagination("final", filtered);
            });

        // Temporary receipts invoices search → updates receiptsBody
        document.querySelector("#tr-search-box-wrapper .search-input")
            .addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                const filtered = TRInvoices.filter(item =>
                    item.receiptNumber.toLowerCase().includes(query) ||
                    (item.admNumber && item.admNumber.toLowerCase().includes(query)) ||
                    (item.admName && item.admName.toLowerCase().includes(query)) ||
                    (item.customer && item.customer.toLowerCase().includes(query))
                );
                renderTable("receipts", filtered, 1); // ✅ target receiptsBody
                renderPagination("receipts", filtered); // ✅ target receiptsPagination
            });

        // Temporary receipts advance payment search → updates trBody
        document.querySelector("#receipts-search-box-wrapper .search-input")
            .addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                const filtered = temporaryReceiptsAdvancePaymentTableBody.filter(item =>
                    item.receiptNumber.toLowerCase().includes(query) ||
                    (item.admNumber && item.admNumber.toLowerCase().includes(query)) ||
                    (item.admName && item.admName.toLowerCase().includes(query)) ||
                    (item.customer && item.customer.toLowerCase().includes(query))
                );
                renderTable("tr", filtered, 1); // ✅ target trBody
                renderPagination("tr", filtered); // ✅ target trPagination
            });

    });
</script>

<script>
    document.querySelectorAll('.selectable-filter').forEach(function(tag) {
        tag.addEventListener('click', function() {
            tag.classList.toggle('selected');
        });
    });
</script>

<!-- for toast message + view more button -->
<script>
    document.addEventListener('click', function(e) {
        // ✅ Toast message trigger
        // if (e.target.classList.contains('submit')) {
        //     e.preventDefault();
        //     e.stopPropagation(); // Prevent row click
        //     const toast = document.getElementById('user-toast');
        //     toast.style.display = 'block';
        //     setTimeout(() => {
        //         toast.style.display = 'none';
        //     }, 3000);
        // }

        // ✅ View More button redirect
        if (
            (e.target.classList.contains('black-action-btn') || e.target.classList.contains('success-action-btn')) &&
            e.target.hasAttribute('data-href')
        ) {
            e.preventDefault();
            window.location.href = e.target.getAttribute('data-href');
        }
    });
</script>


<!-- pop-up resend SMS modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('resend-sms-modal');
        const closeBtn = document.getElementById('resend-sms-close');
        const mobileSelect = document.getElementById('mobile-number');
        const optionalCheckbox = document.getElementById('optional-checkbox');
        const optionalNumber = document.getElementById('optional-number');
        const receiptInput = document.getElementById('sms-receipt-id');

        // Open modal when "Resend SMS" is clicked
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('resend-sms-btn')) {
                e.preventDefault();

                const primary = e.target.getAttribute('data-primary');
                const secondary = e.target.getAttribute('data-secondary');
                const receiptId = e.target.getAttribute('data-receipt-id');
                console.log(primary);
                // Reset modal fields
                mobileSelect.innerHTML = '<option value="">-- Select Number --</option>';
                if (primary) mobileSelect.innerHTML += `<option value="${primary}">${primary} - Primary</option>`;
                if (secondary) mobileSelect.innerHTML += `<option value="${secondary}">${secondary} - Secondary</option>`;
                optionalNumber.value = '';
                optionalCheckbox.checked = false;
                optionalNumber.disabled = true;
                receiptInput.value = receiptId;

                modal.style.display = 'block';
            }
        });

        // Close modal
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Enable/disable optional number input
        optionalCheckbox.addEventListener('change', function() {
            optionalNumber.disabled = !this.checked;
            if (!this.checked) optionalNumber.value = '';
        });

        // Close modal if clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    });
</script>

<!-- active tab persistence script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('active_tab');

        if (activeTab) {
            const tabMap = {
                final: 'final-reciepts-invoices-pane',
                temporary: 'temporary-receipts-invoices-pane',
                advance: 'temporary-receipts-advance-payment-pane'
            };

            const tabId = tabMap[activeTab];
            if (tabId) {
                const tabTrigger = document.querySelector(`[data-bs-target="#${tabId}"]`);
                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
        }

        // Update pagination links to keep the current tab active
        const allTabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        allTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const targetId = e.target.getAttribute('data-bs-target').replace('#', '');
                const links = document.querySelectorAll('.pagination a');
                links.forEach(link => {
                    const url = new URL(link.href);
                    // reverse map tab ID to active_tab value
                    const activeValue = Object.keys(tabMap).find(key => tabMap[key] === targetId);
                    if (activeValue) url.searchParams.set('active_tab', activeValue);
                    link.href = url.toString();
                });
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the active_tab value from the query string
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('active_tab');

        // If a tab was active before reload, open it again
        if (activeTab) {
            // Try matching either "-pane" or full ID
            const tabTrigger = document.querySelector(`[data-bs-target="#${activeTab}-pane"]`) ||
                document.querySelector(`[data-bs-target="#${activeTab}"]`);
            const tabPane = document.querySelector(`#${activeTab}-pane`) ||
                document.querySelector(`#${activeTab}`);

            if (tabTrigger && tabPane) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // Update pagination links to keep the current tab active
        const allTabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        allTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const targetId = e.target.getAttribute('data-bs-target').replace('#', '');
                const links = document.querySelectorAll('.pagination a');
                links.forEach(link => {
                    const url = new URL(link.href);
                    url.searchParams.set('active_tab', targetId.replace('-pane', ''));
                    link.href = url.toString();
                });
            });
        });

        // Also ensure pagination links always include the active tab when first loaded
        const links = document.querySelectorAll('.pagination a');
        const currentTab = document.querySelector('.nav-link.active');
        if (currentTab) {
            const targetId = currentTab.getAttribute('data-bs-target').replace('#', '').replace('-pane', '');
            links.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('active_tab', targetId);
                link.href = url.toString();
            });
        }
    });
</script>

<!-- clear all button functionality -->
<script>
    // Clear Final Receipts Filters
    function clearFinalFilters() {
        // Clear Select2 dropdowns
        $('#final-filter-adm-name').val(null).trigger('change');
        $('#final-filter-adm-id').val(null).trigger('change');
        $('#final-filter-customer').val(null).trigger('change');

        // Clear date field
        const finalDateInput = document.getElementById('final-filter-date');
        if (finalDateInput) {
            finalDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const finalStatusBtn = document.getElementById('final-custom-status-btn');
        const finalStatusInput = document.getElementById('final-status-value');
        if (finalStatusBtn) {
            finalStatusBtn.textContent = 'Choose Status';
            finalStatusBtn.removeAttribute('data-value');
        }
        if (finalStatusInput) {
            finalStatusInput.value = '';
        }

        // Hide status menu if open
        const finalStatusMenu = document.getElementById('final-custom-status-menu');
        if (finalStatusMenu) {
            finalStatusMenu.style.display = 'none';
        }

        console.log('Final filters cleared');
    }

    // Clear Temporary Receipts Filters
    function clearTempFilters() {
        // Clear Select2 dropdowns
        $('#temp-filter-adm-name').val(null).trigger('change');
        $('#temp-filter-adm-id').val(null).trigger('change');
        $('#temp-filter-customer').val(null).trigger('change');

        // Clear date field
        const tempDateInput = document.getElementById('temp-filter-date');
        if (tempDateInput) {
            tempDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const tempStatusBtn = document.getElementById('temp-custom-status-btn');
        const tempStatusInput = document.getElementById('temp-status-value');
        if (tempStatusBtn) {
            tempStatusBtn.textContent = 'Choose Status';
            tempStatusBtn.removeAttribute('data-value');
        }
        if (tempStatusInput) {
            tempStatusInput.value = '';
        }

        // Hide status menu if open
        const tempStatusMenu = document.getElementById('temp-custom-status-menu');
        if (tempStatusMenu) {
            tempStatusMenu.style.display = 'none';
        }

        console.log('Temp filters cleared');
    }

    // Clear Advance Payments Filters
    function clearAdvanceFiltersAndSubmit() {
    // Clear Select2 dropdowns
    $('#advance-filter-adm-name').val(null).trigger('change');
    $('#advance-filter-adm-id').val(null).trigger('change');
    $('#advance-filter-customer').val(null).trigger('change');

    // Clear date
    document.getElementById('advance-filter-date').value = '';

    // Clear status
    document.getElementById('advance-status-value').value = '';
    document.getElementById('advance-custom-status-btn').innerText = 'Choose Status';

    // Redirect to same page with only active_tab=advance
    const url = new URL(window.location.href);
    url.search = '';
    url.searchParams.set('active_tab', 'advance');

    window.location.href = url.toString();
}

   
</script>