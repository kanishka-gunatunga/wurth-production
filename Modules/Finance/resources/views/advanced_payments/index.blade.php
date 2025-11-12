@include('finance::layouts.header')

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
            <h1 class="header-title">Advance Payments</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <div id="search-box-wrapper" class="collapsed">
                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                <input type="text" class="search-input" placeholder="Search ADM Number or Name, Customer Name" />
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
                        <th>Date</th>
                        <th>ADM Number</th>
                        <th>ADM Name</th>
                        <th>Customer Name</th>
                        <th>Payment Amount</th>
                        <th class="sticky-column">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                    <tr class="clickable-row" data-href="{{ route('advanced_payments.show', $payment->id) }}">
                        <td>{{ $payment->date }}</td>
                        <td>{{ $payment->adm_id }}</td>
                        <td>{{ $payment->admDetails?->name ?? 'N/A' }}</td>
                        <td>{{ $payment->customerData?->name ?? 'N/A' }}</td>
                        <td>{{ number_format($payment->payment_amount, 2) }}</td>
                        <td class="sticky-column">
                            <button class="success-action-btn" data-href="{{ route('advanced_payments.show', $payment->id) }}">
                                View More
                            </button>
                            @if($payment->attachment)
                            <a href="{{ asset('storage/'.$payment->attachment) }}"
                                class="black-action-btn submit"
                                style="text-decoration: none;"
                                download
                                onclick="showDownloadToast(event)">
                                Download
                            </a>
                            @else
                            <button class="black-action-btn" disabled>No File</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No advance payments found.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
        <nav class="d-flex justify-content-center mt-5">
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links('pagination::bootstrap-5') }}
            </div>

        </nav>
    </div>
</div>




<div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="searchByFilter"
    aria-labelledby="offcanvasRightLabel">
    <div class="row d-flex justify-content-end">
        <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-header d-flex justify-content-between">
        <div class="col-6">
            <span class="offcanvas-title" id="offcanvasRightLabel">Search </span> <span class="title-rest"> &nbsp;by
                Filter
            </span>
        </div class="col-6">

        <div>
            <button class="btn rounded-phill" id="clear-filters">Clear All</button>
        </div>
    </div>
    <div class="offcanvas-body">

        <!-- ADM Name Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM Name</p>
            <select id="filter-adm-name" class="form-control select2" multiple="multiple">
                @foreach ($payments->pluck('adm.userDetails.name')->unique() as $admName)
                @if($admName)
                <option>{{ $admName }}</option>
                @endif
                @endforeach
            </select>
        </div>

        <!-- ADM ID Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM ID</p>
            <select id="filter-adm-id" class="form-control select2" multiple="multiple">
                @foreach ($payments->pluck('adm_id')->unique() as $admId)
                <option>{{ $admId }}</option>
                @endforeach
            </select>
        </div>

        <!-- Customers Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Customers</p>
            <select id="filter-customer" class="form-control select2" multiple="multiple">
                @foreach ($payments->pluck('customerData.name')->unique() as $customer)
                @if($customer)
                <option>{{ $customer }}</option>
                @endif
                @endforeach
            </select>
        </div>

        <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="filter-date" class="form-control" placeholder="Select date range" />
        </div>
    </div>
</div>



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
            Downloaded successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>


<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button') && !e.target.closest('a')) {
            window.location.href = row.getAttribute('data-href');
        }

    });
</script>

<!-- Dynamic Search for ADM Number, ADM Name, and Customer Name -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");
        const rows = document.querySelectorAll(".custom-table-locked tbody tr.clickable-row");

        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase().trim();

            rows.forEach(row => {
                const admNumber = row.children[1].innerText.toLowerCase();
                const admName = row.children[2].innerText.toLowerCase();
                const customerName = row.children[3].innerText.toLowerCase();

                // Show row only if query matches any of these fields
                if (
                    admNumber.includes(query) ||
                    admName.includes(query) ||
                    customerName.includes(query)
                ) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
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
</script>

<!-- for toast message + view more button -->
<script>
    document.addEventListener('click', function(e) {
        // Handle "View More" button
        if (e.target.classList.contains('success-action-btn')) {
            const href = e.target.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        }

        // Handle "Download" button (toast + prevent redirect)
        if (e.target.classList.contains('submit')) {
            e.stopPropagation(); // prevent row redirect

            // Show toast
            const toast = document.getElementById('user-toast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    });
</script>


<!-- filtering script & close all button functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rows = document.querySelectorAll(".custom-table-locked tbody tr.clickable-row");

        // Filter elements
        const admNameFilter = $('#filter-adm-name');
        const admIdFilter = $('#filter-adm-id');
        const customerFilter = $('#filter-customer');
        const dateFilter = document.getElementById('filter-date');
        const clearAllBtn = document.getElementById('clear-filters'); // make sure button has id="clear-filters"

        // Helper: get selected values (Select2 safe)
        function getSelectedValues(selectEl) {
            return $(selectEl).val() ? $(selectEl).val().map(v => v.toLowerCase()) : [];
        }

        // Main filtering function
        function applyFilters() {
            const selectedAdms = getSelectedValues(admNameFilter);
            const selectedAdmIds = getSelectedValues(admIdFilter);
            const selectedCustomers = getSelectedValues(customerFilter);
            const dateRange = dateFilter.value.trim(); // format: "YYYY-MM-DD to YYYY-MM-DD"

            rows.forEach(row => {
                const cells = row.children;
                const rowDate = new Date(cells[0].innerText);
                const admId = cells[1].innerText.toLowerCase();
                const admName = cells[2].innerText.toLowerCase();
                const customerName = cells[3].innerText.toLowerCase();

                let visible = true;

                if (selectedAdms.length && !selectedAdms.includes(admName)) visible = false;
                if (selectedAdmIds.length && !selectedAdmIds.includes(admId)) visible = false;
                if (selectedCustomers.length && !selectedCustomers.includes(customerName)) visible = false;

                // ✅ Date range filter (same logic as second code)
                if (dateRange.includes("to")) {
                    const [startStr, endStr] = dateRange.split("to").map(d => d.trim());
                    const startDate = new Date(startStr);
                    const endDate = new Date(endStr);
                    if (rowDate < startDate || rowDate > endDate) visible = false;
                }

                row.style.display = visible ? "" : "none";
            });
        }

        // Hook into changes
        [admNameFilter, admIdFilter, customerFilter].forEach(select => {
            select.on('change', applyFilters);
        });
        dateFilter.addEventListener('change', applyFilters);

        // ✅ Clear All button
        clearAllBtn.addEventListener('click', function() {
            [admNameFilter, admIdFilter, customerFilter].forEach(select => {
                $(select).val(null).trigger('change');
            });
            dateFilter.value = '';
            rows.forEach(row => row.style.display = '');
        });
    });
</script>


@include('finance::layouts.footer')