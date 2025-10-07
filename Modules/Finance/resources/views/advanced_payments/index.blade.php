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
                <input type="text" class="search-input" placeholder="Search ADM Number or Name, Customer Name, Reason" />
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
                        <td>{{ $payment->admin?->userDetails?->name ?? 'N/A' }}</td>
                        <td>{{ $payment->customerData?->name ?? 'N/A' }}</td>
                        <td>{{ number_format($payment->payment_amount, 2) }}</td>
                        <td class="sticky-column">
                            <button class="success-action-btn" data-href="{{ route('advanced_payments.show', $payment->id) }}">
                                View More
                            </button>
                            <button class="black-action-btn submit">Download</button>
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

        <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
        </div>
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

<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

<!-- Search functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");

        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase();

            // Filter cash deposit data
            const filteredData = cashDepositeTableData.filter(item =>
                item.admNumber.toLowerCase().includes(query) ||
                item.admName.toLowerCase().includes(query) ||
                item.customerName.toLowerCase().includes(query) ||
                item.reason.toLowerCase().includes(query)
            );

            // Reset current page for filtered results
            currentPages.cashDeposite = 1;

            renderCashDepositeTable(filteredData);
            renderCashDepositePagination(filteredData);
        });

        function renderCashDepositeTable(data) {
            const tableBody = document.getElementById("cashDepositeTableBody");
            tableBody.innerHTML = "";

            const startIndex = (currentPages.cashDeposite - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, data.length);

            for (let i = startIndex; i < endIndex; i++) {
                const row = `
        <tr>
            <td>${data[i].date}</td>
            <td>${data[i].admNumber}</td>
            <td>${data[i].admName}</td>
            <td>${data[i].customerName}</td>
            <td>${data[i].paymentAmount.toFixed(2)}</td>
            <td class="sticky-column">
                <button class="success-action-btn" data-href="/advance-payments-details">View More</button>
                <button class="black-action-btn submit">Download</button>
            </td>
        </tr>
    `;
                tableBody.innerHTML += row;
            }
        }

        function renderCashDepositePagination(data) {
            const pagination = document.getElementById("cashDepositePagination");
            pagination.innerHTML = "";

            const totalPages = Math.ceil(data.length / rowsPerPage);
            const currentPage = currentPages.cashDeposite;

            // Prev button
            pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeCashDepositePage(${currentPage - 1}, data)">Prev</a>
            </li>
        `;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changeCashDepositePage(${i}, data)">${i}</a>
                </li>
            `;
            }

            // Next button
            pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeCashDepositePage(${currentPage + 1}, data)">Next</a>
            </li>
        `;
        }

        // Page change for filtered data
        window.changeCashDepositePage = function(page, data) {
            const totalPages = Math.ceil(data.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;

            currentPages.cashDeposite = page;
            renderCashDepositeTable(data);
            renderCashDepositePagination(data);
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
</script>

<!-- for toast message + view more button -->
<script>
    document.addEventListener('click', function(e) {
        // View More button
        if (e.target.classList.contains('success-action-btn')) {
            window.location.href = e.target.getAttribute('data-href');
        }
        // Download button (toast message, keep as is)
        if (e.target.classList.contains('submit')) {
            e.preventDefault();
            e.stopPropagation();
            const toast = document.getElementById('user-toast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    });
</script>

@include('finance::layouts.footer')