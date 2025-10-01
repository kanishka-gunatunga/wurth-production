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
            <h1 class="header-title">Inquiries</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <div id="search-box-wrapper" class="collapsed">
                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                <input type="text" class="search-input" placeholder="Search Inquiry no." />
            </div>
            <button class="header-btn" id="search-toggle-button"><i class="fa-solid fa-magnifying-glass fa-xl"></i></button>
            <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter" aria-controls="offcanvasRight"><i class="fa-solid fa-filter fa-xl"></i></button>
        </div>
    </div>


    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>
        <div class="table-responsive">
            <table class="table custom-table-locked">
                <thead>
                    <tr>
                        <th>Inquiry Ref. No.</th>
                        <th>Date</th>
                        <th>Inquiry Type</th>
                        <th>ADM Number</th>
                        <th>ADM Name</th>
                        <th>Customer Name</th>
                        <th>Status</th>
                        <th class="sticky-column">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($inquiries as $inquiry)
                    <tr class="clickable-row" data-href="{{ route('inquiry.details', $inquiry->id) }}">

                        <td>{{ $inquiry->id }}</td>
                        <td>{{ $inquiry->inquiry_date }}</td>
                        <td>{{ $inquiry->type }}</td>
                        <td>{{ $inquiry->adm_id }}</td>
                        <td>{{ $inquiry->admin?->userDetails?->name ?? 'N/A' }}</td>
                        <td>{{ $inquiry->customer }}</td>
                        <td>
                            @php
                            $statusClass = match($inquiry->status) {
                            'Sorted' => 'success-status-btn',
                            'Deposited' => 'blue-status-btn',
                            'Rejected' => 'danger-status-btn',
                            default => 'grey-status-btn',
                            };
                            @endphp
                            <button class="{{ $statusClass }}">{{ $inquiry->status }}</button>
                        </td>
                        <td class="sticky-column">
                            <button class="success-action-btn">Approve</button>
                            <button class="red-action-btn">Reject</button>
                            <button class="black-action-btn submit">Download</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
        <nav class="d-flex justify-content-center mt-5">
            <ul id="cashDepositePagination" class="pagination"></ul>
        </nav>
    </div>





</div>


</div>

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
            <p class="filter-title">Inquiry type</p>
            <select class="form-control select2" multiple="multiple">
                <option>Payment issue</option>
                <option>Refund request</option>
                <option>Invoice correction</option>
                <option>Duplicate payment</option>
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
                    <li><a class="dropdown-item" href="#" data-value="Paid">Pending</a></li>
                    <li><a class="dropdown-item" href="#" data-value="Deposited">Sorted</a></li>
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

<!-- Approve Modal -->
<div id="approve-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2);">

        <!-- Close button -->
        <button id="approve-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:0 0 0.5rem 0; font-weight:600; color:#000;">Payment Approval</h4>

        <!-- Subtitle -->
        <p style="margin:0 0 1.5rem 0; color:#6c757d; font-size:0.95rem; line-height:1.4;">
            You're about to confirm this payment. Please provide a reason for approval.
        </p>

        <!-- Textarea with button inside -->
        <div style="position:relative;">
            <textarea id="approve-modal-input" rows="3" placeholder="Enter your reason here...."
                style="width:100%; border:1px solid #ddd; border-radius:12px; padding:0.75rem 3rem 0.75rem 1rem; font-size:0.95rem; resize:none; outline:none;"></textarea>

            <!-- Green tick button -->
            <button id="approve-modal-tick" style="position:absolute; bottom:10px; right:10px; background:#2E7D32; border:none; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M7 12.5l3 3 7-7" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>


<!-- Reject Modal -->
<div id="reject-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2);">

        <!-- Close button -->
        <button id="reject-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:0 0 0.5rem 0; font-weight:600; color:#000;">Payment Rejection</h4>

        <!-- Subtitle -->
        <p style="margin:0 0 1.5rem 0; color:#6c757d; font-size:0.95rem; line-height:1.4;">
            You're about to reject this payment. Please provide a reason for rejection.
        </p>

        <!-- Input fields -->
        <div style="display:flex; flex-direction:column; gap:1rem; margin-bottom:1rem;">
            <input type="text" placeholder="Header"
                style="width:100%; border:1px solid #ddd; border-radius:20px; padding:0.6rem 1rem; font-size:0.95rem; outline:none;">
            <input type="text" placeholder="GL"
                style="width:100%; border:1px solid #ddd; border-radius:20px; padding:0.6rem 1rem; font-size:0.95rem; outline:none;">
        </div>

        <!-- Red tick button -->
        <div style="display:flex; justify-content:flex-end;">
            <button id="reject-modal-tick" style="background:#CC0000; border:none; border-radius:50%; width:40px; height:40px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M7 12.5l3 3 7-7" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
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

<!-- <script>
    // Cash deposit data
    const cashDepositeTableData = [{
            inquiryRefNo: "REF-1001",
            inquiryDate: "2023-01-15",
            inquiryType: "Payment Issue",
            admNumber: "ADM-1001",
            admName: "Admin One",
            customerName: "Alice Smith",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1002",
            inquiryDate: "2023-01-16",
            inquiryType: "Refund Request",
            admNumber: "ADM-1002",
            admName: "Admin Two",
            customerName: "Bob Johnson",
            status: "Sorted"
        },
        {
            inquiryRefNo: "REF-1003",
            inquiryDate: "2023-01-17",
            inquiryType: "Invoice Correction",
            admNumber: "ADM-1003",
            admName: "Admin Three",
            customerName: "Charlie Brown",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1004",
            inquiryDate: "2023-01-18",
            inquiryType: "Duplicate Payment",
            admNumber: "ADM-1004",
            admName: "Admin Four",
            customerName: "Diana Prince",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1005",
            inquiryDate: "2023-01-19",
            inquiryType: "Missing Invoice",
            admNumber: "ADM-1005",
            admName: "Admin Five",
            customerName: "Edward Nigma",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1006",
            inquiryDate: "2023-01-20",
            inquiryType: "Payment Issue",
            admNumber: "ADM-1006",
            admName: "Admin Six",
            customerName: "Frank Castle",
            status: "Sorted"
        },
        {
            inquiryRefNo: "REF-1007",
            inquiryDate: "2023-01-21",
            inquiryType: "Refund Request",
            admNumber: "ADM-1007",
            admName: "Admin Seven",
            customerName: "Grace Hopper",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1008",
            inquiryDate: "2023-01-22",
            inquiryType: "Invoice Correction",
            admNumber: "ADM-1008",
            admName: "Admin Eight",
            customerName: "Helen Parr",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1009",
            inquiryDate: "2023-01-23",
            inquiryType: "Duplicate Payment",
            admNumber: "ADM-1009",
            admName: "Admin Nine",
            customerName: "Ian Malcolm",
            status: "Sorted"
        },
        {
            inquiryRefNo: "REF-1010",
            inquiryDate: "2023-01-24",
            inquiryType: "Missing Invoice",
            admNumber: "ADM-1010",
            admName: "Admin Ten",
            customerName: "Jane Foster",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1011",
            inquiryDate: "2023-01-25",
            inquiryType: "Payment Issue",
            admNumber: "ADM-1011",
            admName: "Admin Eleven",
            customerName: "Kyle Reese",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1012",
            inquiryDate: "2023-01-26",
            inquiryType: "Refund Request",
            admNumber: "ADM-1012",
            admName: "Admin Twelve",
            customerName: "Laura Palmer",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1013",
            inquiryDate: "2023-01-27",
            inquiryType: "Invoice Correction",
            admNumber: "ADM-1013",
            admName: "Admin Thirteen",
            customerName: "Mike Ross",
            status: "Sorted"
        },
        {
            inquiryRefNo: "REF-1014",
            inquiryDate: "2023-01-28",
            inquiryType: "Duplicate Payment",
            admNumber: "ADM-1014",
            admName: "Admin Fourteen",
            customerName: "Nancy Drew",
            status: "Pending"
        },
        {
            inquiryRefNo: "REF-1015",
            inquiryDate: "2023-01-29",
            inquiryType: "Missing Invoice",
            admNumber: "ADM-1015",
            admName: "Admin Fifteen",
            customerName: "Oscar Wilde",
            status: "Pending"
        }
    ];

    const rowsPerPage = 10;
    const currentPages = {
        cashDeposite: 1
    }; // track pages separately

    // Table render function
    function renderTable(tableId, data, page) {
        const tableBody = document.getElementById(`${tableId}TableBody`);
        tableBody.innerHTML = '';

        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, data.length);

        for (let i = startIndex; i < endIndex; i++) {
            let statusClass = '';
            switch (data[i].status) {
                case 'Sorted':
                case 'Sorted':
                    statusClass = 'success-status-btn';
                    break;
                case 'Deposited':
                case 'deposited':
                    statusClass = 'blue-status-btn';
                    break;
                case 'Rejected':
                case 'rejected':
                    statusClass = 'danger-status-btn';
                    break;
                default:
                    statusClass = 'grey-status-btn';
            }
            const row = `
                <tr class="clickable-row" data-href="/inquiry-details">
                    <td>${data[i].inquiryRefNo}</td>
                    <td>${data[i].inquiryDate}</td>
                    <td>${data[i].inquiryType}</td>
                    <td>${data[i].admNumber}</td>
                    <td>${data[i].admName}</td>
                    <td>${data[i].customerName}</td>
                    <td><button class="${statusClass}"> ${data[i].status}</button></td>
                    <td class="sticky-column">
                        <button class="success-action-btn">Approve</button>
                        <button class="red-action-btn">Reject</button>
                        <button class="black-action-btn submit">Download</button>
                    </td>
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
        if (tableId === 'cashDeposite') return cashDepositeTableData;
        return [];
    }

    // Initial load after page ready
    window.onload = function() {
        changePage('cashDeposite', 1);
    };
</script> -->

<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

<!-- expand search bar and search function -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchWrapper = document.getElementById("search-box-wrapper");
        const searchToggleButton = document.getElementById("search-toggle-button");
        const searchInput = searchWrapper.querySelector(".search-input");

        let idleTimeout;
        const idleTime = 5000; // 5 seconds

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

        searchInput.addEventListener("input", function() {
            filterInquiries(this.value);
            startIdleTimer();
        });

        searchInput.addEventListener("keydown", startIdleTimer);
    });

    // Filter inquiries only by Inquiry Ref. No
    function filterInquiries(query) {
        const searchQuery = query.toLowerCase();

        // Filter data strictly by inquiryRefNo
        const filteredData = cashDepositeTableData.filter(item =>
            item.inquiryRefNo.toLowerCase().includes(searchQuery)
        );

        // Reset to first page when searching
        currentPages['cashDeposite'] = 1;

        // Re-render table and pagination
        renderTable('cashDeposite', filteredData, 1);
        renderPagination('cashDeposite', filteredData);
    }
</script>


<script>
    document.querySelectorAll('.selectable-filter').forEach(function(tag) {
        tag.addEventListener('click', function() {
            tag.classList.toggle('selected');
        });
    });
</script>

<!-- for toast message -->
<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('submit')) {
            e.preventDefault();
            e.stopPropagation(); // Prevent row click
            const toast = document.getElementById('user-toast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    });
</script>

<!-- for reject modal pop-up -->
<script>
    document.addEventListener('click', function(e) {
        // Approve button click
        if (e.target.classList.contains('success-action-btn')) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('approve-modal').style.display = 'block';
            document.getElementById('approve-modal-input').value = '';
        }
        // Approve modal tick
        if (e.target.id === 'approve-modal-tick' || e.target.closest('#approve-modal-tick')) {
            document.getElementById('approve-modal').style.display = 'none';
        }
        // Approve modal close
        if (e.target.id === 'approve-modal-close') {
            document.getElementById('approve-modal').style.display = 'none';
        }

        // Reject button click
        if (e.target.classList.contains('red-action-btn')) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('reject-modal').style.display = 'block';
            // Optionally clear input fields here if needed
            var inputs = document.querySelectorAll('#reject-modal input');
            inputs.forEach(function(input) {
                input.value = '';
            });
        }
        // Reject modal tick
        if (e.target.id === 'reject-modal-tick' || e.target.closest('#reject-modal-tick')) {
            document.getElementById('reject-modal').style.display = 'none';
        }
        // Reject modal close
        if (e.target.id === 'reject-modal-close') {
            document.getElementById('reject-modal').style.display = 'none';
        }
    });
</script>

@include('finance::layouts.footer')