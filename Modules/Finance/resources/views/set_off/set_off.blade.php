@include('finance::layouts.header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .form-check-input {
        width: 22px;
        height: 22px;
        border: 1px solid #D2D5DA;
        border-radius: 4px;
        background-color: #fff;
        appearance: none;
        /* hide default OS checkbox */
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    /* Hover effect */
    .form-check-input:hover {
        background-color: #f0f0f0;
    }

    /* Checked state (red fill + tick icon) */
    .form-check-input:checked {
        background-color: #CC0000 !important;
        border-color: #CC0000 !important;
        background-image: url("https://img.icons8.com/?size=100&id=82769&format=png&color=FFFFFF");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 14px 14px;
    }
</style>

<div class="main-wrapper">
    <div class="d-flex justify-content-between">
        <div class="col-lg-4 col-12">
            <h1 class="header-title">Set - Off</h1>
        </div>

        <div class="col-6">
            <!-- Search + Dropdown -->
            <div class="d-flex search-div align-items-center">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M16.893 16.92L19.973 20M19 11.5C19 13.4891 18.2098 15.3968 16.8033 16.8033C15.3968 18.2098 13.4891 19 11.5 19C9.51088 19 7.60322 18.2098 6.1967 16.8033C4.79018 15.3968 4 13.4891 4 11.5C4 9.51088 4.79018 7.60322 6.1967 6.1967C7.60322 4.79018 9.51088 4 11.5 4C13.4891 4 15.3968 4.79018 16.8033 6.1967C18.2098 7.60322 19 9.51088 19 11.5Z"
                        stroke="#AAB6C1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="w-100 mx-3">
                    <div class="dropdown w-100">
                        <!-- Input acts as both search + dropdown trigger -->
                        <input type="text" class="form-control w-100" style="border-color: white;"
                            id="invoiceDropdownSearch" placeholder="Customer ID or Name" data-bs-toggle="dropdown"
                            aria-expanded="false" onkeyup="filterDropdown()">

                        <!-- Dropdown menu -->
                        <ul class="dropdown-menu w-100 p-2" aria-labelledby="invoiceDropdownSearch"
                            style="max-height: 400px; overflow-y: auto; min-width: 400px;">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title ps-3 m-0">Select Customers</h5>
                                <button class="red-edit-button-sm">

                                    Done
                                </button>
                            </div>

                            <!-- Table -->
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr class="searchable-table-header">

                                        <th scope="col column-title">Full Name</th>
                                        <th scope="col column-title">Customer ID</th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceDropdownOptions">
                                    @foreach($customers as $customer)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="me-2" value="{{ $customer->customer_id }}">
                                            {{ $customer->name }}
                                        </td>
                                        <td>{{ $customer->customer_id }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="row g-5">
            <div class="col-md-6">
                <div>
                    <p class="card-section-title">Invoice no. or Return Cheque No.</p>

                    <div class="d-flex search-div align-items-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.893 16.92L19.973 20M19 11.5C19 13.4891 18.2098 15.3968 16.8033 16.8033C15.3968 18.2098 13.4891 19 11.5 19C9.51088 19 7.60322 18.2098 6.1967 16.8033C4.79018 15.3968 4 13.4891 4 11.5C4 9.51088 4.79018 7.60322 6.1967 6.1967C7.60322 4.79018 9.51088 4 11.5 4C13.4891 4 15.3968 4.79018 16.8033 6.1967C18.2098 7.60322 19 9.51088 19 11.5Z"
                                stroke="#AAB6C1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="w-100 mx-3">
                            <input type="text" id="search-table1" onkeyup="filterTable('table1', this.value)"
                                class="search-invoices-input" placeholder="Search Invoice no. or Return Cheque No.">
                        </span>

                    </div>


                    <div class="mt-4">
                        <div class="card customer-payment-card">

                            <div class="card-body d-flex flex-column invoices-card">


                                <table class="table" id="table1">
                                    <thead>
                                        <tr class="searchable-table-header">

                                            <th scope="col column-title">Invoice No. / Return No.</th>
                                            <th scope="col column-title">Amount</th>
                                            <th scope="col column-title">Balance Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div>
                    <p class="card-section-title">Extra Payment Id or Credit Note Id</p>

                    <div class="d-flex search-div align-items-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.893 16.92L19.973 20M19 11.5C19 13.4891 18.2098 15.3968 16.8033 16.8033C15.3968 18.2098 13.4891 19 11.5 19C9.51088 19 7.60322 18.2098 6.1967 16.8033C4.79018 15.3968 4 13.4891 4 11.5C4 9.51088 4.79018 7.60322 6.1967 6.1967C7.60322 4.79018 9.51088 4 11.5 4C13.4891 4 15.3968 4.79018 16.8033 6.1967C18.2098 7.60322 19 9.51088 19 11.5Z"
                                stroke="#AAB6C1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="w-100 mx-3">
                            <input type="text" id="search-table2" onkeyup="filterTable('table2', this.value)"
                                class="search-invoices-input" placeholder="Search Extra Payment Id or Credit Note Id">
                        </span>
                    </div>


                    <div class="mt-4">
                        <div class="card customer-payment-card">

                            <div class="card-body d-flex flex-column invoices-card">
                                <table class="table" id="table2">
                                    <thead>
                                        <tr class="searchable-table-header">

                                            <th scope="col column-title">Extra Pay. Id / Credit Note Id</th>
                                            <th scope="col column-title">Amount</th>
                                            <th scope="col column-title">Balance Amount</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="styled-tab-sub p-4 mt-5" style="border-radius: 8px;">
        <div class="w-100">
            <p class="mb-2 red-bold-text">Final Set-Off amount : Rs. 0.00</p>
            <textarea class="additional-notes" rows="3" placeholder="Enter Set-Off Reason Here"></textarea>
        </div>
    </div>
</div>

@section('footer-buttons')
<div class="d-flex justify-content-end mt-4 gap-3">
    <a href="{{ route('set_off.main') }}" class="black-action-btn-lg" style="text-decoration: none;">Cancel</a>
    <button type="button" class="red-action-btn-lg submit-setoff-btn">Submit</button>
</div>
@endsection

<!-- Toast message -->
<div id="user-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
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
            Set-Off added successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    /*
  Consolidated script to:
  - render invoice / credit rows
  - expand rows to accept manual amount or full payment
  - maintain invoiceState / creditState
  - calculate final set-off
  - fetch invoices/credit-notes for selected customers
  - submit (requires at least 1 invoice OR 1 credit note)
*/

    $(function() {
        // States
        let invoiceState = {}; // { id: { selected, fullPayment, manualAmount, fullAmount } }
        let creditState = {};

        // Helpers
        function formatCurrency(n) {
            return Number(n || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function safeParseFloat(s) {
            const v = parseFloat(String(s).replace(/,/g, ''));
            return isNaN(v) ? 0 : v;
        }

        // Render table (type = 'invoice' | 'credit')
        function renderTable(tableId, dataArray, type) {
            const $tbody = $(`#${tableId} tbody`);
            $tbody.empty();

            dataArray.forEach(item => {
                const idVal = String(item.fullName);
                const fullAmount = safeParseFloat(item.amount);
                const balanceAmount = safeParseFloat(item.balance ?? item.amount);

                const state = (type === 'invoice' ? invoiceState : creditState);
                if (!state[idVal]) {
                    state[idVal] = {
                        selected: false,
                        fullPayment: false,
                        manualAmount: 0,
                        fullAmount: fullAmount,
                        currentBalance: balanceAmount
                    };
                } else {
                    state[idVal].fullAmount = fullAmount;
                    state[idVal].currentBalance = balanceAmount;
                }

                const $tr = $(`
            <tr class="checkbox-item" data-id="${idVal}" data-type="${type}">
                <td>
                    <div class="form-check d-flex align-items-center">
                        <input type="checkbox" class="form-check-input row-select me-2" ${state[idVal].selected ? 'checked' : ''}>
                        <label class="form-check-label row-label m-0">${idVal}</label>
                    </div>
                </td>
                <td class="row-amount">${formatCurrency(fullAmount)}</td>
                <td class="row-balance">${formatCurrency(balanceAmount)}</td>
            </tr>
        `);

                $tbody.append($tr);
            });

            updateFinalSetOff();
        }




        // Expand row builder
        function buildExpandedRow(idVal, type) {
            const state = (type === 'invoice' ? invoiceState : creditState)[idVal];
            const manualValue = (!state.fullPayment && state.manualAmount) ? state.manualAmount : (state.fullPayment ? state.fullAmount : '');

            return $(`
        <tr class="expanded-row">
            <td colspan="3">
                <div class="d-flex align-items-center justify-content-start py-3 gap-3">
                    <input type="text" class="form-control manual-input" placeholder="Set-Off Amount"
                        style="min-width:140px; max-width:180px;" value="${manualValue}">
                    <div class="form-check">
                        <input class="form-check-input full-pay" type="checkbox" ${state.fullPayment ? 'checked' : ''}>
                        <label class="form-check-label ms-2">Full Payment</label>
                    </div>
                </div>
            </td>
        </tr>
    `);
        }


        // Update total based Only on sum invoices
        function updateFinalSetOff() {
            let total = 0;
            Object.values(invoiceState).forEach(s => {
                if (!s.selected) return;
                total += s.fullPayment ? s.fullAmount : (s.manualAmount || 0);
            });

            $('.red-bold-text').text("Final Set-Off amount : Rs. " + formatCurrency(total));
        }


        // Fetch initial tableData variables if available (server may inject)
        if (window.table1Data && Array.isArray(window.table1Data)) {
            renderTable('table1', window.table1Data, 'invoice');
        }
        if (window.table2Data && Array.isArray(window.table2Data)) {
            renderTable('table2', window.table2Data, 'credit');
        }

        /* ---------------------------
           Delegated handlers
           ---------------------------*/

        // Row click (expand/collapse) - delegate to tbody
        $(document).on('click', 'table tbody tr.checkbox-item', function(e) {
            // don't expand if clicked the checkbox itself
            if ($(e.target).is('input[type="checkbox"]') || $(e.target).closest('input[type="checkbox"]').length) return;

            const $tr = $(this);
            const idVal = $tr.attr('data-id');
            const type = $tr.attr('data-type');
            const $tbody = $tr.closest('tbody');

            // toggle existing expanded row
            const $next = $tr.next();
            if ($next.hasClass('expanded-row')) {
                $next.remove();
                return;
            }

            // remove any other expanded rows in this table for clarity
            $tbody.find('.expanded-row').remove();

            // append expanded row
            const $expanded = buildExpandedRow(idVal, type);
            $tr.after($expanded);

            // wire up expanded controls
            const state = (type === 'invoice' ? invoiceState : creditState)[idVal];
            const $manualInput = $expanded.find('.manual-input');
            const $fullPay = $expanded.find('.full-pay');
            const $rowCheckbox = $tr.find('.row-select');

            // restore states on controls
            $fullPay.prop('checked', !!state.fullPayment);
            if (state.fullPayment) {
                $manualInput.prop('disabled', true).val(state.fullAmount);
            } else {
                $manualInput.prop('disabled', false).val(state.manualAmount ? state.manualAmount : '');
            }
            $rowCheckbox.prop('checked', !!state.selected);

            // manual input handler
            $manualInput.on('input', function() {
                let v = safeParseFloat($(this).val());

                // Validation: block typing more than fullAmount
                if (v > state.fullAmount) {
                    $(this).val(state.fullAmount.toFixed(2));
                    v = state.fullAmount;
                }

                // Validation: block negative or non-numeric
                if (v < 0 || isNaN(v)) {
                    $(this).val('');
                    v = 0;
                }

                state.manualAmount = v;

                if (v > 0) {
                    state.fullPayment = false;
                    $fullPay.prop('checked', false);
                    state.selected = true;
                    $rowCheckbox.prop('checked', true);
                } else {
                    state.selected = false;
                    $rowCheckbox.prop('checked', false);
                }

                // Update “Balance Amount” (new 3rd column)
                const remaining = Math.max(0, state.fullAmount - v);
                const $mainRow = $tr.closest('tbody').find(`tr[data-id="${idVal}"][data-type="${type}"]`);
                $mainRow.find('.row-balance').text(formatCurrency(remaining));

                updateFinalSetOff();
            });

            // full pay handler
            $fullPay.on('change', function() {
                const $mainRow = $tr.closest('tbody').find(`tr[data-id="${idVal}"][data-type="${type}"]`);
                if ($(this).is(':checked')) {
                    state.fullPayment = true;
                    state.manualAmount = state.fullAmount;
                    $manualInput.val(state.fullAmount).prop('disabled', true);
                    state.selected = true;
                    $rowCheckbox.prop('checked', true);

                    // Full payment → remaining becomes 0
                    $mainRow.find('.row-balance').text(formatCurrency(0));
                } else {
                    state.fullPayment = false;
                    $manualInput.prop('disabled', false);
                    $mainRow.find('.row-balance').text(formatCurrency(state.fullAmount));

                    // Reset displayed amount to original full amount if unchecked
                    $mainRow.find('.row-amount').text(formatCurrency(state.fullAmount));

                    if (!state.manualAmount) {
                        state.selected = false;
                        $rowCheckbox.prop('checked', false);
                    }
                }

                updateFinalSetOff();
            });


        });

        // Row-select (outer checkbox) change (delegate)
        $(document).on('change', '.row-select', function() {
            const $tr = $(this).closest('tr.checkbox-item');
            const idVal = $tr.attr('data-id');
            const type = $tr.attr('data-type');
            const checked = $(this).is(':checked');
            const state = (type === 'invoice' ? invoiceState : creditState)[idVal];
            const $balanceCell = $tr.find('.row-balance');

            state.selected = checked;

            if (checked) {
                // If unchecked before, now mark full amount as set-off
                if (!state.manualAmount && !state.fullPayment) {
                    state.manualAmount = state.fullAmount;
                }
                // Since checked = set off full amount → balance 0
                $balanceCell.text(formatCurrency(0));
            } else {
                // When unchecked, reset manual and balance
                state.manualAmount = 0;
                state.fullPayment = false;
                $balanceCell.text(formatCurrency(state.fullAmount));
            }

            updateFinalSetOff();
        });


        // Also keep updateFinalSetOff reacting to direct manual inputs inserted earlier (in case older code left inputs)
        $(document).on('input', 'table tbody .manual-input', function() {
            // handled when expanded created
            updateFinalSetOff();
        });

        /* ---------------------------
           Dropdown show/hide & filtering
           (keeps your existing behaviour)
           ---------------------------*/
        $('#invoiceDropdownSearch').on('focus', function() {
            $(this).next('.dropdown-menu').addClass('show');
        });
        $('.dropdown-menu').on('mousedown', e => e.preventDefault());
        $(document).on('click', e => {
            if (!$(e.target).closest('.dropdown-menu, #invoiceDropdownSearch').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
        window.filterDropdown = function() {
            const input = $('#invoiceDropdownSearch').val().toLowerCase();
            $('#invoiceDropdownOptions tr').each(function() {
                const name = $(this).find('td:first').text().toLowerCase();
                const id = $(this).find('td:nth-child(2)').text().toLowerCase();
                $(this).toggle(name.indexOf(input) !== -1 || id.indexOf(input) !== -1);
            });
        };

        /* ---------------------------
           Fetch invoices & credit notes for selected customers
           ---------------------------*/
        $('.red-edit-button-sm').on('click', function() {
            let selectedCustomers = [];
            let selectedCustomerNames = [];

            $('#invoiceDropdownOptions input[type="checkbox"]:checked').each(function() {
                selectedCustomers.push($(this).val());
                const name = $(this).closest('tr').find('td:first').text().trim();
                selectedCustomerNames.push(name);
            });

            if (selectedCustomers.length === 0) {
                alert("Select at least one customer");
                return;
            }

            // Close dropdown
            $('.dropdown-menu').removeClass('show');

            // --- NEW: Show selected customer(s) in the search field ---
            $('#invoiceDropdownSearch').val(selectedCustomerNames.join(', '));

            // invoices
            $.ajax({
                url: "{{ route('set_off.invoices') }}",
                method: "POST",
                data: {
                    customer_ids: selectedCustomers,
                    _token: "{{ csrf_token() }}"
                },
                success: function(invoices) {
                    // Backend now returns only the balance (amount - paid_amount)
                    const table1Data = (invoices || []).map(inv => ({
                        fullName: inv.invoice_or_cheque_no,
                        amount: safeParseFloat(inv.balance), // Use balance as main amount
                        balance: safeParseFloat(inv.balance) // Same for dynamic updates
                    }));
                    renderTable('table1', table1Data, 'invoice');
                    updateFinalSetOff();
                },
                error: function(xhr) {
                    console.error('Failed to fetch invoices', xhr);
                    alert('Failed to fetch invoices');
                }
            });

            // credit notes + extra payments (combined)
            $.when(
                $.ajax({
                    url: "{{ route('set_off.credit_notes') }}",
                    method: "POST",
                    data: {
                        customer_ids: selectedCustomers,
                        _token: "{{ csrf_token() }}"
                    }
                }),
                $.ajax({
                    url: "{{ route('set_off.extra_payments') }}",
                    method: "POST",
                    data: {
                        customer_ids: selectedCustomers,
                        _token: "{{ csrf_token() }}"
                    }
                })
            ).done(function(creditNotes, extraPayments) {
                // creditNotes and extraPayments are arrays wrapped by jQuery -> [ data, statusText, jqXHR ]
                const combined = [
                    ...(creditNotes[0] || []).map(c => ({
                        fullName: c.credit_note_id,
                        amount: safeParseFloat(c.amount),
                        balance: safeParseFloat(c.amount)
                    })),
                    ...(extraPayments[0] || []).map(e => ({
                        fullName: e.extra_payment_id,
                        amount: safeParseFloat(e.amount),
                        balance: safeParseFloat(e.amount)
                    }))
                ];

                renderTable('table2', combined, 'credit');
                updateFinalSetOff();
            }).fail(function() {
                alert('Failed to fetch credit notes or extra payments');
            });
        });

        /* ---------------------------
           Submit handler
           - Validates at least one invoice OR one credit note selected
           ---------------------------*/
        $(document).on('click', '.submit-setoff-btn', function() {
            // Build maps
            const setOffInvoices = {};
            const setOffCreditNotes = {};
            let finalAmount = 0;

            Object.keys(invoiceState).forEach(k => {
                const s = invoiceState[k];
                if (s.selected) {
                    const amount = s.fullPayment ? s.fullAmount : (s.manualAmount || 0);
                    setOffInvoices[k] = amount;
                    finalAmount += amount;
                }
            });

            Object.keys(creditState).forEach(k => {
                const s = creditState[k];
                if (s.selected) {
                    const amount = s.fullPayment ? s.fullAmount : (s.manualAmount || 0);
                    setOffCreditNotes[k] = amount;
                    finalAmount -= amount;
                }
            });

            // Validate at least one invoice or credit note
            if (Object.keys(setOffInvoices).length === 0 && Object.keys(setOffCreditNotes).length === 0) {
                alert('Please select at least one invoice or credit note.');
                return;
            }

            const reason = $('.additional-notes').val() || '';

            // Submit
            $.ajax({
                url: "{{ route('set_off.submit') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    set_off_invoices: setOffInvoices,
                    set_off_credit_notes: setOffCreditNotes,
                    final_amount: finalAmount.toFixed(2), // send as proper decimal
                    reason: reason
                },
                success: function(res) {
                    if (res && res.success) {
                        if ($('#user-toast').length) {
                            $('#user-toast').show();
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            alert(res.message || 'Saved');
                            location.reload();
                        }
                    } else {
                        alert(res.message || 'Failed to save Set-Off.');
                    }
                },
                error: function(xhr) {
                    console.error('Server error while saving Set-Off', xhr);
                    alert('Server error while saving Set-Off.');
                }
            });
        });


    });
</script>

<script>
    // Generic table filter
    function filterTable(tableId, searchValue) {
        const filter = searchValue.toLowerCase();
        const table = document.getElementById(tableId);
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) { // start at 1 to skip the header row
            const tds = tr[i].getElementsByTagName("td");
            let match = false;

            for (let j = 0; j < tds.length; j++) {
                const td = tds[j];
                if (td) {
                    const text = td.textContent || td.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }

            tr[i].style.display = match ? "" : "none";
        }
    }
</script>

@include('finance::layouts.footer2')