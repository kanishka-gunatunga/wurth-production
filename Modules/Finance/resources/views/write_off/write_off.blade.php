@include('finance::layouts.header')
<div class="main-wrapper">
    <div class="d-flex justify-content-between">
        <div class="col-lg-4 col-12">
            <h1 class="header-title">Write - Off</h1>
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
            <p class="mb-2 red-bold-text">Final Write-off amount : Rs. 235,000.00</p>
            <textarea class="additional-notes" rows="3" placeholder="Enter Write-Off Reason Here"></textarea>
        </div>
    </div>
</div>

@section('footer-buttons')
<div class="d-flex justify-content-end mt-4 gap-3">
   
    <button type="button" class="red-action-btn-lg submit-writeoff-btn">Submit</button>
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
            Write-off added successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#multi-select-dropdown').select2({
            placeholder: "Select Customer",
            allowClear: true
        });
    });
</script>

<script>
    function filterTable(tableId, searchValue) {
        const filter = searchValue.toLowerCase();
        const table = document.getElementById(tableId);
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach(row => {
            // The Invoice No. / Extra Payment Id is inside <span class="ms-2">
            const span = row.querySelector("td span.ms-2");

            if (span) {
                const text = span.textContent.toLowerCase();

                // Show or hide row
                if (text.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });
    }

    function filterCheckboxes() {
        const input = document.getElementById("search-input").value.toLowerCase();

        // Both tables
        const tables = [document.getElementById("table1"), document.getElementById("table2")];

        tables.forEach(table => {
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach(row => {
                // Target the span that actually holds the Invoice No. / Return No.
                const invoiceSpan = row.querySelector("td span.ms-2");

                if (invoiceSpan) {
                    const invoiceNo = invoiceSpan.textContent.toLowerCase();

                    if (invoiceNo.includes(input)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        });
    }


    // Function to get table data
    function populateTable(tableId, data) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        tbody.innerHTML = ""; // Clear existing rows

        data.forEach((item, index) => {
            const tr = document.createElement("tr");
            tr.classList.add("checkbox-item");

            // Name / ID + Checkbox
            const tdName = document.createElement("td");
            tdName.innerHTML = `
            <label class="checkbox-item-wrapper" style="margin-bottom:0;">
                <input type="checkbox" id="${tableId}-item${index + 1}" ${item.checked ? "checked" : ""}>
                <span class="checkmark"></span>
                <span class="ms-2">${item.fullName}</span>
            </label>
        `;

            // Amount
            const tdAmount = document.createElement("td");
            tdAmount.textContent = item.invoiceNumber;

            tr.appendChild(tdName);
            tr.appendChild(tdAmount);

            tbody.appendChild(tr);
        });
    }


    // Populate both tables
    populateTable("table1", table1Data);
    populateTable("table2", table2Data);




    console.log("Table 1:", table1Data);
    console.log("Table 2:", table2Data);





    function populateTable(tableId, data) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        tbody.innerHTML = ""; // Clear existing rows

        data.forEach((item, index) => {
            const tr = document.createElement("tr");
            tr.classList.add("checkbox-item");
            tr.style.cursor = "pointer";
            tr.setAttribute("data-row-index", index);

            // Custom checkbox with checkmark for main row
            const tdName = document.createElement("td");
            tdName.innerHTML = `
            <label class="checkbox-item-wrapper" style="margin-bottom:0;">
                <input type="checkbox" id="${tableId}-item${index + 1}" ${item.checked ? "checked" : ""}>
                <span class="checkmark"></span>
                <span class="ms-2">${item.fullName}</span>
            </label>
        `;

            // Invoice Number cell
            const tdInvoice = document.createElement("td");
            tdInvoice.textContent = item.invoiceNumber;

            tr.appendChild(tdName);
            tr.appendChild(tdInvoice);

            // Checkbox event: update data array when user ticks/unticks
            tdName.querySelector('input[type="checkbox"]').addEventListener('change', function(e) {
                item.checked = this.checked;
            });

            // Expand/collapse on row click (but NOT when clicking the checkbox)
            tr.addEventListener("click", function(e) {
                if (e.target.closest('input[type="checkbox"]')) return;

                // Collapse if already expanded
                if (tr.nextSibling && tr.nextSibling.classList && tr.nextSibling.classList.contains(
                        "expanded-row")) {
                    tr.nextSibling.remove();
                    return;
                }

                // Expand: insert the expandable row after this row
                const expandTr = document.createElement("tr");
                expandTr.className = "expanded-row";
                const expandTd = document.createElement("td");
                expandTd.colSpan = 2;

                expandTd.innerHTML = `
    <div class="d-flex align-items-center justify-content-center py-3">
        <input type="text" class="form-control" placeholder="Write-off Amount" style="min-width:140px; max-width:180px; margin-right: 6rem;">
        <label class="checkbox-item-wrapper mb-0" style="margin-bottom:0;">
            <input class="form-check-input" type="checkbox" id="fullPaymentCheck${tableId}${index}">
            <span class="checkmark"></span>
            <span class="ms-2">Full Payment</span>
        </label>
    </div>
`;

                expandTr.appendChild(expandTd);
                tr.parentNode.insertBefore(expandTr, tr.nextSibling);
            });

            tbody.appendChild(tr);
        });
    }
</script>

<script>
    function filterDropdown() {
        const input = document.getElementById("invoiceDropdownSearch").value.toLowerCase();
        const rows = document.querySelectorAll("#invoiceDropdownOptions tr");

        rows.forEach(row => {
            const name = row.querySelector("td").innerText.toLowerCase();
            const invoice = row.querySelectorAll("td")[1].innerText.toLowerCase();

            if (name.includes(input) || invoice.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
</script>

<script>
    // Cancel button redirect
    document.querySelector('.cancel').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'write-off-main';
    });
</script>

<script>
    document.getElementById('invoiceDropdownSearch').addEventListener('focus', function() {
        const dropdownMenu = this.nextElementSibling; // the <ul class="dropdown-menu">
        dropdownMenu.classList.add('show');
    });

    const dropdownInput = document.getElementById('invoiceDropdownSearch');
    const dropdownMenu = dropdownInput.nextElementSibling;

    // Show dropdown on focus
    dropdownInput.addEventListener('focus', () => {
        dropdownMenu.classList.add('show');
    });

    // Keep dropdown open if clicking inside
    dropdownMenu.addEventListener('mousedown', (e) => {
        e.preventDefault(); // prevents input blur
    });

    // Hide dropdown on clicking outside
    document.addEventListener('click', (e) => {
        if (!dropdownMenu.contains(e.target) && e.target !== dropdownInput) {
            dropdownMenu.classList.remove('show');
        }
    });
</script>

<script>
    // Show toast on submit
    document.querySelector('.submit').addEventListener('click', function(e) {
        e.preventDefault();
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    });
</script>

<script>
    $('.red-edit-button-sm').on('click', function() {
        // Get selected customer IDs
        let selectedCustomers = [];
        $('#invoiceDropdownOptions input[type="checkbox"]:checked').each(function() {
            selectedCustomers.push($(this).val());
        });

        if (selectedCustomers.length === 0) {
            alert("Select at least one customer");
            return;
        }

        // Fetch invoices
        $.ajax({
            url: "{{ route('write_off.invoices') }}",
            method: "POST",
            data: {
                customer_ids: selectedCustomers,
                _token: "{{ csrf_token() }}"
            },
            success: function(invoices) {
                const table1Data = invoices.map(inv => ({
                    fullName: inv.invoice_or_cheque_no,
                    invoiceNumber: inv.amount,
                    checked: false
                }));
                populateTable('table1', table1Data);
            }
        });

        // Fetch credit notes
        $.ajax({
            url: "{{ route('write_off.credit_notes') }}",
            method: "POST",
            data: {
                customer_ids: selectedCustomers,
                _token: "{{ csrf_token() }}"
            },
            success: function(creditNotes) {
                const table2Data = creditNotes.map(cn => ({
                    fullName: cn.credit_note_id,
                    invoiceNumber: cn.amount,
                    checked: false
                }));
                populateTable('table2', table2Data);
            }
        });
    });
</script>

@include('finance::layouts.footer2')