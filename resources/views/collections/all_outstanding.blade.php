@include('layouts.dashboard-header')


<style>
    /* Search box styles */
    #search-box-wrapper {
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

    #search-box-wrapper.collapsed {
        width: 0;
        padding: 0;
        margin: 0;
        border: 1px solid transparent;
        background-color: transparent;
    }

    #search-box-wrapper.expanded {
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
        /* Add padding to make space for the icon */
        padding-left: 30px;
    }

    .search-input::placeholder {
        color: #888;
    }

    .search-icon-inside {
        position: absolute;
        left: 10px;
        /* Adjust as needed */
        color: #888;
    }

    /* Optional: Adjust button alignment if needed */
    .col-12.d-flex.justify-content-lg-end {
        align-items: center;
    }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">All Outstanding</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <div id="search-box-wrapper" class="collapsed">
                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>

                <form method="GET" action="{{ url('all-outstanding') }}" id="mainSearchForm">
                                <input 
                                    type="text" 
                                    class="search-input" 
                                    name="search"
                                    placeholder="Search Invoice Number, Customer Name, ADM Number, ADM Name"
                                    value="{{ request('search') }}"
                                />
                            </form>
            </div>
            <button class="header-btn" id="search-toggle-button"><i class="fa-solid fa-magnifying-glass fa-xl"></i></button>
            <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter" aria-controls="offcanvasRight"><i class="fa-solid fa-filter fa-xl"></i></button>
        </div>
    </div>


    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>
        <div class="table-responsive">
            <table class="table custom-table-locked" style="min-width: 1600px;">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Customer Name</th>
                        <th>Invoice Date</th>
                        <th>ADM Number</th>
                        <th>ADM Name</th>
                        <th>Total Invoice Amount</th>
                        <th>Outstanding Balance</th>
                        <th class="sticky-column">Outstanding Days</th>

                    </tr>
                </thead>
                <tbody >
                           @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_or_cheque_no ?? 'N/A' }}</td>
                                    <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $invoice->invoice_date ?? 'N/A' }}</td>

                                    @php
                                        $primaryAdm = $invoice->customer->admDetails;
                                        $secondaryAdm = $invoice->customer->secondaryAdm;
                                    @endphp

                                    <td>
                                        @if($primaryAdm)
                                            {{ $primaryAdm->adm_number }}
                                        @endif

                                        @if($secondaryAdm)
                                            <br><small>({{ $secondaryAdm->adm_number }} - Secondary)</small>
                                        @endif
                                    </td>

                                    <td>
                                        @if($primaryAdm)
                                            {{ $primaryAdm->name }}
                                        @endif

                                        @if($secondaryAdm)
                                            <br><small>({{ $secondaryAdm->name }} - Secondary)</small>
                                        @endif
                                    </td>

                                    <td>{{ number_format($invoice->amount ?? 0, 2) }}</td>
                                    <td>{{ number_format(($invoice->amount ?? 0) - ($invoice->paid_amount ?? 0), 2) }}</td>

                                    <td class="sticky-column">
                                        @php
                                            $invoiceDate = \Carbon\Carbon::parse($invoice->invoice_date);
                                            $now = \Carbon\Carbon::now();
                                            $daysDifference = $invoiceDate->diffInDays($now, false);
                                            $displayDays = $daysDifference >= 0
                                                ? number_format($daysDifference) . ' days'
                                                : number_format(abs($daysDifference)) . ' days (Upcoming)';
                                        @endphp
                                        {{ $displayDays }}
                                    </td>
                                </tr>
                                @endforeach

                        </tbody>
            </table>

        </div>
        <nav class="d-flex justify-content-center mt-5">
              {{ $invoices->links('pagination::bootstrap-5') }}
        </nav>
    </div>





</div>


<div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="searchByFilter"
    aria-labelledby="offcanvasRightLabel">
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
          <a href="{{url('customers')}}"><button class="btn rounded-phill">Clear All</button></a>
        </div>
    </div>
    <form method="GET" action="{{ url('all-outstanding') }}" id="tempFilterForm">
    <div class="offcanvas-body">
        <div class="mt-5 filter-categories">
            <p class="filter-title">Outstanding Dates</p>

            @php
                $selected = request('adoutstanding_dates', []);
            @endphp

            <div class="form-check custom-circle-checkbox">
                <input class="form-check-input" type="checkbox" id="outstanding1" name="adoutstanding_dates[]" value="0-30"
                    {{ in_array('0-30', $selected) ? 'checked' : '' }}>
                <label class="form-check-label" for="outstanding1">0–30 Days</label>
            </div>

            <div class="form-check custom-circle-checkbox">
                <input class="form-check-input" type="checkbox" id="outstanding2" name="adoutstanding_dates[]" value="31-60"
                    {{ in_array('31-60', $selected) ? 'checked' : '' }}>
                <label class="form-check-label" for="outstanding2">31–60 Days</label>
            </div>

            <div class="form-check custom-circle-checkbox">
                <input class="form-check-input" type="checkbox" id="outstanding3" name="adoutstanding_dates[]" value="61-90"
                    {{ in_array('61-90', $selected) ? 'checked' : '' }}>
                <label class="form-check-label" for="outstanding3">61–90 Days</label>
            </div>

            <div class="form-check custom-circle-checkbox">
                <input class="form-check-input" type="checkbox" id="outstanding4" name="adoutstanding_dates[]" value="91-120"
                    {{ in_array('91-120', $selected) ? 'checked' : '' }}>
                <label class="form-check-label" for="outstanding4">91–120 Days</label>
            </div>

            <div class="form-check custom-circle-checkbox">
                <input class="form-check-input" type="checkbox" id="outstanding5" name="adoutstanding_dates[]" value="120-plus"
                    {{ in_array('120-plus', $selected) ? 'checked' : '' }}>
                <label class="form-check-label" for="outstanding5">120+ Days</label>
            </div>

            <button type="submit" class="red-action-btn-lg mt-4">Apply Filter</button>
        </div>
    </div>
</form>

    </div>
 </div>



@include('layouts.footer2')




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
    // final receipts invoices
    const outstandingTableData = [{
            invoice: "INV001",
            customer: "John Doe",
            date: "2024-06-01",
            admNumber: "ADM1001",
            admName: "Alice Smith",
            totalAmount: 1500.00,
            outstandingBalance: 500.00,
            outstandingDays: 15
        },
        {
            invoice: "INV002",
            customer: "Jane Williams",
            date: "2024-06-03",
            admNumber: "ADM1002",
            admName: "Bob Johnson",
            totalAmount: 2000.00,
            outstandingBalance: 1200.00,
            outstandingDays: 30
        },
        {
            invoice: "INV003",
            customer: "Acme Corp",
            date: "2024-05-28",
            admNumber: "ADM1003",
            admName: "Charlie Brown",
            totalAmount: 3500.00,
            outstandingBalance: 3500.00,
            outstandingDays: 45
        },
        {
            invoice: "INV004",
            customer: "Global Tech",
            date: "2024-06-05",
            admNumber: "ADM1004",
            admName: "Diana Prince",
            totalAmount: 5000.00,
            outstandingBalance: 2500.00,
            outstandingDays: 10
        },
        {
            invoice: "INV005",
            customer: "Beta Solutions",
            date: "2024-06-07",
            admNumber: "ADM1005",
            admName: "Edward Nigma",
            totalAmount: 1200.00,
            outstandingBalance: 0.00,
            outstandingDays: 0
        }
    ];

    const rowsPerPage = 10;
    const currentPages = {
        outstanding: 1
    }; // keep track of current page for each table

    // Table render function
    function renderTable(tableId, data, page) {
        const tableBody = document.getElementById(`${tableId}TableBody`);
        tableBody.innerHTML = '';

        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, data.length);

        for (let i = startIndex; i < endIndex; i++) {
            const row = `
                <tr class="clickable-row" data-href="/outstanding-details">
                    <td>${data[i].invoice}</td>
                    <td>${data[i].customer}</td>
                    <td>${data[i].date}</td>
                    <td>${data[i].admNumber}</td>
                    <td>${data[i].admName}</td>
                    <td>${data[i].totalAmount.toFixed(2)}</td>
                    <td>${data[i].outstandingBalance.toFixed(2)}</td>
                    <td class="sticky-column">${data[i].outstandingDays}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        }
    }

    // Pagination render
    function renderPagination(tableId, data) {
        const pagination = document.getElementById(`${tableId}Pagination`);
        pagination.innerHTML = '';

        const totalPages = Math.ceil(data.length / rowsPerPage);
        const currentPage = currentPages[tableId];

        // Prev button
        pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage('${tableId}', ${currentPage - 1})">Prev</a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage('${tableId}', ${i})">${i}</a>
                </li>
            `;
        }

        // Next button
        pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage('${tableId}', ${currentPage + 1})">Next</a>
            </li>
        `;
    }

    // Page change
    function changePage(tableId, page) {
        const data = getTableData(tableId);
        const totalPages = Math.ceil(data.length / rowsPerPage);

        if (page < 1 || page > totalPages) return;
        currentPages[tableId] = page;

        renderTable(tableId, data, page);
        renderPagination(tableId, data);
    }

    // Helper to get data by tableId
    function getTableData(tableId) {
        if (tableId === 'outstanding') return outstandingTableData;
        return [];
    }

    // Initial load after page ready
    window.onload = function() {
        changePage('outstanding', 1);
    };
</script>

<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

<!-- search function -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");

        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase();

            // Filter the outstanding data
            const filteredData = outstandingTableData.filter(item =>
                item.invoice.toLowerCase().includes(query) ||
                item.customer.toLowerCase().includes(query) ||
                item.admNumber.toLowerCase().includes(query) ||
                item.admName.toLowerCase().includes(query)
            );

            // Reset current page for filtered results
            currentPages.outstanding = 1;

            renderOutstandingTable(filteredData);
            renderOutstandingPagination(filteredData);
        });

        function renderOutstandingTable(data) {
            const tableBody = document.getElementById("outstandingTableBody");
            tableBody.innerHTML = "";

            const startIndex = (currentPages.outstanding - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, data.length);

            for (let i = startIndex; i < endIndex; i++) {
                const row = `
                <tr>
                    <td>${data[i].invoice}</td>
                    <td>${data[i].customer}</td>
                    <td>${data[i].date}</td>
                    <td>${data[i].admNumber}</td>
                    <td>${data[i].admName}</td>
                    <td>${data[i].totalAmount.toFixed(2)}</td>
                    <td>${data[i].outstandingBalance.toFixed(2)}</td>
                    <td class="sticky-column">${data[i].outstandingDays}</td>
                </tr>
            `;
                tableBody.innerHTML += row;
            }
        }

        function renderOutstandingPagination(data) {
            const pagination = document.getElementById("outstandingPagination");
            pagination.innerHTML = "";

            const totalPages = Math.ceil(data.length / rowsPerPage);
            const currentPage = currentPages.outstanding;

            // Prev button
            pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeOutstandingPage(${currentPage - 1}, data)">Prev</a>
            </li>
        `;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changeOutstandingPage(${i}, data)">${i}</a>
                </li>
            `;
            }

            // Next button
            pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeOutstandingPage(${currentPage + 1}, data)">Next</a>
            </li>
        `;
        }

        // Page change for filtered data
        window.changeOutstandingPage = function(page, data) {
            const totalPages = Math.ceil(data.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;

            currentPages.outstanding = page;
            renderOutstandingTable(data);
            renderOutstandingPagination(data);
        };
    });
</script>


<!-- expand search bar  -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchWrapper = document.getElementById("search-box-wrapper");
        const searchToggleButton = document.getElementById("search-toggle-button");
        const searchInput = searchWrapper.querySelector(".search-input");

        let idleTimeout;
        const idleTime = 5000; // 5 seconds (5000 milliseconds)

        function collapseSearch() {
            searchWrapper.classList.remove("expanded");
            searchWrapper.classList.add("collapsed");
            searchToggleButton.classList.remove("d-none"); // Show the button
            clearTimeout(idleTimeout); // Clear any existing timer
        }

        function startIdleTimer() {
            clearTimeout(idleTimeout); // Clear previous timer
            idleTimeout = setTimeout(() => {
                if (!searchInput.value) { // Only collapse if input is empty
                    collapseSearch();
                }
            }, idleTime);
        }

        searchToggleButton.addEventListener("click", function() {
            if (searchWrapper.classList.contains("collapsed")) {
                searchWrapper.classList.remove("collapsed");
                searchWrapper.classList.add("expanded");
                searchToggleButton.classList.add("d-none"); // Hide the button
                searchInput.focus();
                startIdleTimer();
            } else {
                collapseSearch();
            }
        });

        searchInput.addEventListener("keydown", function() {
            startIdleTimer(); // Reset the timer on any keypress
        });
    });
</script>

<script>
    document.querySelectorAll('.selectable-filter').forEach(function(tag) {
        tag.addEventListener('click', function() {
            tag.classList.toggle('selected');
        });
    });
    function searchUsers(val) {
    if (event.key === "Enter") {
        document.getElementById("mainSearchForm").submit();
    }
}
</script>

