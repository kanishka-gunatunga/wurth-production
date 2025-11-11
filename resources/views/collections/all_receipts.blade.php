@include('layouts.dashboard-header')


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
                <a class="nav-link active" aria-current="page" href="#" id="final-reciepts-invoices"
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
                <a class="nav-link" aria-current="page" href="#" id="temporary-receipts-invoices"
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
                <a class="nav-link" aria-current="page" href="#" id="temporary-receipts-advance-payment"
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
            <div class="tab-pane fade show active" id="final-reciepts-invoices-pane" role="tabpanel"
                aria-labelledby="final-reciepts-invoices" tabindex="0">
                <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <div id="final-search-box-wrapper" class="search-box-wrapper collapsed">
                            <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                            <input type="text" class="search-input" placeholder="Search Receipt, Invoice, ADM or Customer" />
                        </div>
                        <button class="header-btn" id="final-search-toggle-button">
                            <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                        </button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#finalFilter">
                            <i class="fa-solid fa-filter fa-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end pe-0 mb-3 gap-3">
                    <button class="add-new-division-btn mb-3 submit">
                        Export
                    </button>
                </div>

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
                            @foreach($regular_receipts as $payment)
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
                                    @if($payment->status == 'rejected')
                                    <button class="danger-status-btn"> Rejected</button>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="sticky-column">
                                    <div class="sticky-actions">
                                         <button class="red-action-btn resend-sms-btn"
                                            data-receipt-id="{{ $payment->id }}"
                                            data-primary="{{ $payment->invoice->customer->mobile_number ?? '' }}"
                                            data-secondary="{{ $payment->invoice->customer->secondary_mobile ?? '' }}"
                                            >
                                            Resend SMS
                                        </button>
                                       <a href="{{ $payment->original_pdf ? asset($payment->original_pdf) : '#' }}" >
                                                <button class="black-action-btn">Download</button>
                                            </a>

                                        <a href="{{ url('finace/edit-receipt/'.$payment->id) }}"><button class="success-action-btn">Edit</button></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>

                    </table>

                </div>
                <nav class="d-flex justify-content-center mt-5">

                    {{ $regular_receipts->appends(['active_tab' => 'final'])->links('pagination::bootstrap-5') }}
                </nav>
            </div>

            <div class="tab-pane fade" id="temporary-receipts-invoices-pane" role="tabpanel"
                aria-labelledby="temporary-receipts-invoices" tabindex="0">
                <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <div id="tr-search-box-wrapper" class="search-box-wrapper collapsed">
                            <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                            <input type="text" class="search-input" placeholder="Search Receipt, ADM or Customer" />
                        </div>
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
                        <tbody >
                            @foreach($temp_receipts as $temp_receipt)
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
                                         <button class="red-action-btn resend-sms-btn"
                                            data-receipt-id="{{ $temp_receipt->id }}"
                                            data-primary="{{ $temp_receipt->invoice->customer->mobile_number ?? '' }}"
                                            data-secondary="{{ $temp_receipt->invoice->customer->secondary_mobile ?? '' }}"
                                            >
                                            Resend SMS
                                        </button>
                                       <a href="{{ $temp_receipt->original_pdf ? asset($temp_receipt->original_pdf) : '#' }}" >
                                                <button class="black-action-btn">Download</button>
                                            </a>

                                        <a href="{{ url('finace/edit-receipt/'.$temp_receipt->id) }}"><button class="success-action-btn">Edit</button></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
               <nav class="d-flex justify-content-center mt-5">
                    {{ $temp_receipts->appends(['active_tab' => 'temporary'])->links('pagination::bootstrap-5') }}
                </nav>
            </div>


            <div class="tab-pane fade" id="temporary-receipts-advance-payment-pane" role="tabpanel"
                aria-labelledby="temporary-receipts-advance-payment" tabindex="0">
                <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <div id="receipts-search-box-wrapper" class="search-box-wrapper collapsed">
                            <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                            <input type="text" class="search-input" placeholder="Search Receipt, ADM or Customer" />
                        </div>
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
                        <tbody >
                             @foreach($advanced_payments as $advanced_payment)
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
                                         <button class="red-action-btn resend-sms-btn"
                                            data-receipt-id="{{ $advanced_payment->id }}"
                                            data-primary="{{ $advanced_payment->customerData->mobile_number ?? '' }}"
                                            data-secondary="{{ $advanced_payment->customerData->secondary_mobile ?? '' }}"
                                            >
                                            Resend SMS
                                        </button>
                                       <a href="{{asset('uploads/adm/advanced_payments/attachments/'.$advanced_payment->attachment.'')}}" download>
                                                <button class="black-action-btn">Download</button>
                                            </a>

                                        <a href="{{ url('finace/edit-advanced-payment/'.$advanced_payment->id) }}"><button class="success-action-btn">Edit</button></a>
                                        <a href="{{ url('finace/remove-advanced-payment/'.$advanced_payment->id) }}"><button class="red-action-btn">Remove</button></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <nav class="d-flex justify-content-center mt-5">
                     {{ $advanced_payments->appends(['active_tab' => 'advance'])->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>

    </div>


</div>



<!-- Final reciepts Filter Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="finalFilter" aria-labelledby="finalFilterLabel">
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
            <button class="btn rounded-phill">Clear All</button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>ADMs</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Marketing</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Admin</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Finance</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Team Leaders</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Head of Division</span>

            </div>
        </div>

        <!-- ADM Name Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM Name</p>
            <select class="form-control select2" multiple="multiple">
                <option>John Doe</option>
                <option>Jane Smith</option>
                <option>Robert Lee</option>
                <option>Emily Johnson</option>
                <option>Michael Brown</option>
            </select>
        </div>

        <!-- ADM ID Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM ID</p>
            <select class="form-control select2" multiple="multiple">
                <option>ADM-1001</option>
                <option>ADM-1002</option>
                <option>ADM-1003</option>
                <option>ADM-1004</option>
                <option>ADM-1005</option>
            </select>
        </div>

        <!-- Customers Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Customers</p>
            <select class="form-control select2" multiple="multiple">
                <option>H. K Perera</option>
                <option>Pasan Randula</option>
                <option>Jane Williams</option>
                <option>Acme Corp</option>
            </select>
        </div>

        <!-- Styled Status Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Status</p>
            <div class="dropdown">
                <button class="btn custom-dropdown text-start" type="button" id="status-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 200px;">
                    Choose Status
                    <span class="custom-arrow"></span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="status-dropdown">
                    <li><a class="dropdown-item" href="#" data-value="Paid">Paid</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Deposited">Deposited</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Approved">Approved</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Rejected">Rejected</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Exported">Exported</a></li>
                </ul>
            </div>
        </div>



        <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
        </div>

    </div>
</div>

<!-- temp reciepts - invoices Filter Offcanvas -->
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
            <button class="btn rounded-phill">Clear All</button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>ADMs</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Marketing</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Admin</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Finance</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Team Leaders</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Head of Division</span>

            </div>
        </div>

        <!-- ADM Name Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM Name</p>
            <select class="form-control select2" multiple="multiple">
                <option>John Doe</option>
                <option>Jane Smith</option>
                <option>Robert Lee</option>
                <option>Emily Johnson</option>
                <option>Michael Brown</option>
            </select>
        </div>

        <!-- ADM ID Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM ID</p>
            <select class="form-control select2" multiple="multiple">
                <option>ADM-1001</option>
                <option>ADM-1002</option>
                <option>ADM-1003</option>
                <option>ADM-1004</option>
                <option>ADM-1005</option>
            </select>
        </div>

        <!-- Customers Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Customers</p>
            <select class="form-control select2" multiple="multiple">
                <option>H. K Perera</option>
                <option>Pasan Randula</option>
                <option>Jane Williams</option>
                <option>Acme Corp</option>
            </select>
        </div>

        <!-- Styled Status Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Status</p>
            <div class="dropdown">
                <button class="btn custom-dropdown text-start" type="button" id="status-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 200px;">
                    Choose Status
                    <span class="custom-arrow"></span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="status-dropdown">
                    <li><a class="dropdown-item" href="#" data-value="Paid">Paid</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Deposited">Deposited</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Approved">Approved</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Rejected">Rejected</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Exported">Exported</a></li>
                </ul>
            </div>
        </div>



        <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
        </div>

    </div>
</div>

<!-- temp reciepts - advance payment Filter Offcanvas -->
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
            <button class="btn rounded-phill">Clear All</button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>ADMs</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Marketing</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Admin</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Finance</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Team Leaders</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Head of Division</span>

            </div>
        </div>

        <!-- ADM Name Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM Name</p>
            <select class="form-control select2" multiple="multiple">
                <option>John Doe</option>
                <option>Jane Smith</option>
                <option>Robert Lee</option>
                <option>Emily Johnson</option>
                <option>Michael Brown</option>
            </select>
        </div>

        <!-- ADM ID Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM ID</p>
            <select class="form-control select2" multiple="multiple">
                <option>ADM-1001</option>
                <option>ADM-1002</option>
                <option>ADM-1003</option>
                <option>ADM-1004</option>
                <option>ADM-1005</option>
            </select>
        </div>

        <!-- Customers Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Customers</p>
            <select class="form-control select2" multiple="multiple">
                <option>H. K Perera</option>
                <option>Pasan Randula</option>
                <option>Jane Williams</option>
                <option>Acme Corp</option>
            </select>
        </div>

        <!-- Styled Status Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Status</p>
            <div class="dropdown">
                <button class="btn custom-dropdown text-start" type="button" id="status-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 200px;">
                    Choose Status
                    <span class="custom-arrow"></span>
                </button>
                <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="status-dropdown">
                    <li><a class="dropdown-item" href="#" data-value="Paid">Paid</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Deposited">Deposited</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Approved">Approved</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Rejected">Rejected</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Exported">Exported</a></li>
                </ul>
            </div>
        </div>



        <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
        </div>

    </div>
</div>

</div>


<!-- Toast message -->
<!-- <div id="user-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
    role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999; display: none; min-width: 320px;">
    <div class="d-flex align-items-center">
        <span class="toast-icon-circle d-flex align-items-center justify-content-center me-3">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#fff" />
                <path d="M7 12.5l3 3 7-7" stroke="#28a745" stroke-width="2" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
        <div class="toast-body flex-grow-1">
            Downloaded successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div> -->

<!-- Resend SMS Modal -->
<div id="resend-sms-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:400px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2);">
        <form id="resend-sms-form" method="POST" action="{{ url('finance/resend-receipt') }}">
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


@include('finance::layouts.footer')

<!-- dropdown selector -->
<script>
    document.querySelectorAll('#status-dropdown + .dropdown-menu .dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const value = this.getAttribute('data-value');
            const button = document.getElementById('status-dropdown');
            button.innerHTML = value + ' <span class="custom-arrow"></span>';
        });
    });
</script>


<script>
    // Enable/Disable Optional Input
    document.getElementById("optional-checkbox").addEventListener("change", function() {
        document.getElementById("optional-number").disabled = !this.checked;
    });
</script>






<script>
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');

    const items = ['Apple', 'Banana', 'Cherry', 'Date', 'Grape', 'Mango', 'Orange', 'Pineapple', 'Strawberry'];

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        searchDropdown.innerHTML = '';

        if (query) {
            const filteredItems = items.filter(item => item.toLowerCase().includes(query));
            if (filteredItems.length > 0) {
                filteredItems.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'search-item';
                    div.textContent = item;
                    div.addEventListener('click', function() {
                        searchInput.value = item;
                        searchDropdown.classList.remove('show');
                    });
                    searchDropdown.appendChild(div);
                });
                searchDropdown.classList.add('show');
            } else {
                searchDropdown.classList.remove('show');
            }
        } else {
            searchDropdown.classList.remove('show');
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchDropdown.contains(e.target) && e.target !== searchInput) {
            searchDropdown.classList.remove('show');
        }
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the active_tab value from the query string
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('active_tab');

    // If a tab was active before reload, open it again
    if (activeTab) {
        // Try matching either "-pane" or full ID
        const tabTrigger = document.querySelector(`[data-bs-target="#${activeTab}-pane"]`) 
                        || document.querySelector(`[data-bs-target="#${activeTab}"]`);
        const tabPane = document.querySelector(`#${activeTab}-pane`) 
                     || document.querySelector(`#${activeTab}`);

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
@include('layouts.footer2')