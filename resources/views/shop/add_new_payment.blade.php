@include('layouts.dashboard-header')

<style>
    .form-check-input {
        height: 20px;
        width: 20px;
        border-color: #D2D5DA;
        margin-right: 15px;
    }

    .form-check-input:focus {
        border-color: #CC0000 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2.1px rgba(204, 0, 0, 0.2) !important;
    }

    .form-check-input:checked {
        background-color: #CC0000 !important;
        border-color: #CC0000 !important;
    }

    .search-div {
        background: #FFFFFF;
        border: 1px solid #D2D5DA;
        border-radius: 8px;
        padding: 8px 15px;
        height: 45px;
    }

    .search-invoices-input {
        border: none;
        outline: none;
        width: 100%;
        color: #333;
    }

    .customer-payment-card {
        background: #FFFFFF;
        border: 1px solid #F3F4F6;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .card-section-title {
        font-weight: 600;
        font-size: 18px;
        color: #111827;
        margin-bottom: 20px;
    }

    .details-card {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .bold-text-sm {
        font-weight: 600;
        color: #374151;
    }

    .value {
        color: #4B5563;
    }

    .red-edit-button-sm {
        background: #CC0000;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 4px 10px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .grey-remove-button-sm {
        background: #D1D5DB;
        color: #333;
        border: none;
        border-radius: 4px;
        padding: 4px 8px;
    }

    .invoices-table th {
        background: #F9FAFB;
        color: #6B7280;
        font-weight: 500;
        font-size: 14px;
        padding: 12px;
    }

    .invoices-table td {
        padding: 12px;
        vertical-align: middle;
        font-size: 14px;
    }

    .btn-cancel {
        background: #000;
        color: #fff;
        border: none;
        padding: 10px 40px;
        border-radius: 6px;
        font-weight: 600;
    }

    .btn-submit {
        background: #EF4444;
        color: #fff;
        border: none;
        padding: 10px 40px;
        border-radius: 6px;
        font-weight: 600;
    }

    .select2-container--default .select2-selection--single {
        height: 45px !important;
        border: 1px solid #D2D5DA !important;
        border-radius: 8px !important;
        display: flex;
        align-items: center;
        position: relative;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-left: 40px !important;
        padding-right: 30px !important;
        color: #9CA3AF !important;
        /* Placeholder color */
        line-height: 44px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px !important;
        right: 10px !important;
        display: flex;
        align-items: center;
    }

    .select2-search-icon-overlay {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #AAB6C1;
        z-index: 10;
        pointer-events: none;
    }
</style>

<div class="main-wrapper">
    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Collections</h1>
        </div>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>

        <div class="row g-5">
            <div class="col-md-6">
                <p class="card-section-title">Customer Name or Mobile No</p>

                <div class="position-relative mb-4">
                    <i class="fa-solid fa-magnifying-glass select2-search-icon-overlay"></i>
                    <select id="customerSelect" class="form-control select2" placeholder="Customer Name or Mobile No">
                        <option value="">Customer Name or Mobile No</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->mobile_number }}" data-name="{{ $customer->name }}" data-mobile="{{ $customer->mobile_number }}"
                                data-address="{{ $customer->address }}">{{ $customer->name }} - {{ $customer->mobile_number }}</option>
                        @endforeach
                    </select>
                </div>



                <div class="customer-payment-card d-none" id="customerDetailsCard">
                    <p class="bold-text-sm mb-3">Customers Details</p>
                    <div class="details-card">
                        <div>
                            <p><span class="bold-text-sm">Customer Name :</span> <span class="value"
                                    id="disp-name"></span></p>
                            <p><span class="bold-text-sm">Customer's Mobile No. :</span> <span class="value"
                                    id="disp-mobile"></span></p>
                            <p><span class="bold-text-sm">Customer's Address :</span> <span class="value"
                                    id="disp-address"></span></p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="red-edit-button-sm">
                                <i class="fa-solid fa-pen" style="font-size: 10px;"></i> Edit
                            </button>
                            <button class="grey-remove-button-sm" id="removeCustomer">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <p class="card-section-title">Search Invoice</p>

                <div class="search-div d-flex align-items-center mb-4">
                    <i class="fa-solid fa-magnifying-glass me-3" style="color: #AAB6C1;"></i>
                    <input type="text" id="invoiceSearch" class="search-invoices-input" placeholder="Select Invoice">
                </div>

                <div class="customer-payment-card">
                    <p class="bold-text-sm mb-3">Choose Invoices</p>
                    <div class="table-responsive">
                        <table class="table invoices-table">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Invoice Number</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceList">
                                @foreach($invoiceRequests as $request)
                                <tr class="invoice-row" data-name="{{ $request->name }}" data-no="{{ $request->invoice_no }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="inv{{ $request->id }}" value="{{ $request->id }}">
                                            <label class="form-check-label" for="inv{{ $request->id }}">{{ $request->name }}</label>
                                        </div>
                                    </td>
                                    <td>{{ $request->invoice_no }}</td>
                                    <td>Rs. {{ number_format($request->total_amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 mt-5">
            <button class="btn-cancel" onclick="window.location.href='{{ route('collections') }}'">Cancel</button>
            <button class="btn-submit" onclick="submitRequest()">Submit</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        $('#customerSelect').select2({
            width: '100%',
            placeholder: 'Customer Name or Mobile No',
            allowClear: true
        });

        // customer Selection Change

        $('#customerSelect').on('change', function () {
            const selected = $(this).find(':selected');
            if (selected.val()) {
                $('#disp-name').text(selected.data('name'));
                $('#disp-mobile').text(selected.data('mobile'));
                $('#disp-address').text(selected.data('address'));
                $('#customerDetailsCard').removeClass('d-none');


                filterInvoicesByCustomer(selected.data('name'));
            } else {
                $('#customerDetailsCard').addClass('d-none');
                $('.invoice-row').show();
            }
        });

        // Remove Customer
        $('#removeCustomer').on('click', function () {
            $('#customerSelect').val('').trigger('change');
        });

        // Invoice Search
        $('#invoiceSearch').on('keyup', function () {
            const val = $(this).val().toLowerCase();
            $('.invoice-row').each(function () {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(val));
            });
        });
    });

    function filterInvoicesByCustomer(name) {
        $('.invoice-row').each(function () {
            const rowName = $(this).data('name');
            $(this).toggle(rowName === name);
        });
    }

    function submitRequest() {
        const selectedIds = [];
        $('.form-check-input:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('Please select at least one invoice.');
            return;
        }

        // Create a hidden form to submit via POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('process_payment_selection') }}";
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = "{{ csrf_token() }}";
        form.appendChild(csrfToken);

        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invoice_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

</script>

@include('layouts.footer2')
