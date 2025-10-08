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

    .custom-dropdown-menu li {
        list-style: none !important;
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
                    <tr class="clickable-row"
                        data-href="{{ route('inquiry.details', $inquiry->id) }}"
                        data-adm="{{ $inquiry->adm_id }}"
                        data-customer="{{ $inquiry->customer }}"
                        data-type="{{ $inquiry->type }}"
                        data-status="{{ $inquiry->status }}"
                        data-date="{{ $inquiry->created_at }}">


                        <td>{{ $inquiry->id }}</td>
                        <td>{{ $inquiry->created_at ? $inquiry->created_at->format('Y.m.d') : 'N/A' }}</td>
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
                            @php
                            $status = strtolower(trim($inquiry->status ?? ''));
                            @endphp

                            @if(in_array($status, ['pending', 'deposited']))
                            <button class="success-action-btn">Approve</button>
                            <button class="red-action-btn">Reject</button>
                            @endif

                            @if($inquiry->attachement)
                            <a href="{{ asset('storage/'.$inquiry->attachement) }}"
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
                    @endforeach
                </tbody>

            </table>

        </div>
        <div class="col-12 d-flex justify-content-center laravel-pagination mt-4">
            {{ $inquiries->links('pagination::bootstrap-5') }}
        </div>

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
            <button id="clear-filters" class="btn rounded-phill">Clear All</button>
        </div>
    </div>
    <div class="offcanvas-body">
        <!-- <div class="row">
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
        </div> -->

        <!-- ADM Number Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM ID</p>
            <select id="filter-adm" class="form-control select2" multiple="multiple">
                @foreach ($inquiries->pluck('adm_id')->unique() as $admId)
                <option>{{ $admId }}</option>
                @endforeach
            </select>
        </div>


        <!-- Customers Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Customers</p>
            <select id="filter-customer" class="form-control select2" multiple="multiple">
                @foreach ($inquiries->pluck('customer')->unique() as $customer)
                <option>{{ $customer }}</option>
                @endforeach
            </select>
        </div>


        <div class="mt-5 filter-categories">
            <p class="filter-title">Inquiry type</p>
            <select id="filter-type" class="form-control select2" multiple="multiple">
                @foreach ($inquiries->pluck('type')->unique() as $type)
                <option>{{ $type }}</option>
                @endforeach
            </select>
        </div>


        <!-- Styled Status Dropdown -->
        <div class="mt-5 filter-categories">
            <p class="filter-title">Status</p>
            <div class="custom-dropdown-container" style="position: relative; min-width: 200px;">
                <button type="button" id="custom-status-btn" class="btn custom-dropdown text-start" style="width:100%;">
                    Choose Status
                </button>
                <ul id="custom-status-menu" class="custom-dropdown-menu"
                    style="display:none; position:absolute; top:100%; left:0; background:#fff; border:1px solid #ddd; width:100%; z-index:999;">
                    @foreach ($inquiries->pluck('status')->unique() as $status)
                    <li><a href="#" class="dropdown-item" data-value="{{ $status }}">{{ $status }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>


        <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="filter-date" class="form-control" placeholder="Select date range" />
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
        if (row && !e.target.closest('button') && !e.target.closest('a')) {
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
        const tableRows = document.querySelectorAll(".custom-table-locked tbody tr");

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

        // Filter table rows by Inquiry Ref. No
        searchInput.addEventListener("input", function() {
            const query = this.value.trim().toLowerCase();

            tableRows.forEach(row => {
                const inquiryRefNo = row.querySelector("td").textContent.trim().toLowerCase();
                if (inquiryRefNo.includes(query)) {
                    row.style.display = ""; // show
                } else {
                    row.style.display = "none"; // hide
                }
            });

            startIdleTimer();
        });

        searchInput.addEventListener("keydown", startIdleTimer);
    });
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
    function showDownloadToast(event) {
        // Prevent row click or any other propagation
        event.stopPropagation();

        // Show the toast
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';

        // Hide after 3 seconds
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    // Attach to all download buttons
    document.querySelectorAll('.submit').forEach(btn => {
        btn.addEventListener('click', showDownloadToast);
    });
</script>

<!-- for change status -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('tr.clickable-row');
        if (!row) return;

        const inquiryId = row.dataset.href.split('/').pop(); // get id from URL

        // Approve button click
        if (e.target.classList.contains('success-action-btn')) {
            e.preventDefault();
            fetch(`/finance/inquiries/approve/${inquiryId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const btn = row.querySelector('td button');
                        btn.textContent = data.status;
                        btn.className = 'success-status-btn';

                        // ✅ Hide Approve/Reject buttons
                        row.querySelectorAll('.success-action-btn, .red-action-btn').forEach(b => b.style.display = 'none');
                    }
                });
        }

        // Reject button click
        if (e.target.classList.contains('red-action-btn')) {
            e.preventDefault();
            fetch(`/finance/inquiries/reject/${inquiryId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const btn = row.querySelector('td button');
                        btn.textContent = data.status;
                        btn.className = 'danger-status-btn';

                        // ✅ Hide Approve/Reject buttons
                        row.querySelectorAll('.success-action-btn, .red-action-btn').forEach(b => b.style.display = 'none');
                    }
                });
        }
    });
</script>

<!-- for dropdown -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn = document.getElementById("custom-status-btn");
        const menu = document.getElementById("custom-status-menu");

        btn.addEventListener("click", () => {
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });

        menu.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", (e) => {
                e.preventDefault();
                btn.textContent = e.target.textContent;
                btn.setAttribute("data-value", e.target.dataset.value);
                menu.style.display = "none";
            });
        });

        // Close if clicked outside
        document.addEventListener("click", (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    });
</script>

<!-- filtering script & close all button functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rows = document.querySelectorAll(".custom-table-locked tbody tr");

        const admFilter = document.getElementById("filter-adm");
        const customerFilter = document.getElementById("filter-customer");
        const typeFilter = document.getElementById("filter-type");
        const statusBtn = document.getElementById("custom-status-btn");
        const dateFilter = document.getElementById("filter-date");

        function applyFilters() {
            const selectedAdms = $(admFilter).val() || [];
            const selectedCustomers = $(customerFilter).val() || [];
            const selectedTypes = $(typeFilter).val() || [];
            const selectedStatus = statusBtn.getAttribute("data-value") || "";
            const dateRange = dateFilter.value.split(" to ");

            rows.forEach(row => {
                const rowAdm = row.dataset.adm;
                const rowCustomer = row.dataset.customer;
                const rowType = row.dataset.type;
                const rowStatus = row.dataset.status;
                const rowDate = row.dataset.date;

                let visible = true;

                if (selectedAdms.length && !selectedAdms.includes(rowAdm)) visible = false;
                if (selectedCustomers.length && !selectedCustomers.includes(rowCustomer)) visible = false;
                if (selectedTypes.length && !selectedTypes.includes(rowType)) visible = false;
                if (selectedStatus && rowStatus !== selectedStatus) visible = false;

                // Date range filter
                if (dateRange.length === 2 && dateRange[0] && dateRange[1]) {
                    const rowD = new Date(rowDate);
                    const from = new Date(dateRange[0]);
                    const to = new Date(dateRange[1]);
                    if (rowD < from || rowD > to) visible = false;
                }

                row.style.display = visible ? "" : "none";
            });
        }

        // Hook into change events
        $(admFilter).on("change", applyFilters);
        $(customerFilter).on("change", applyFilters);
        $(typeFilter).on("change", applyFilters);
        dateFilter.addEventListener("change", applyFilters);

        // Custom status dropdown
        document.querySelectorAll("#custom-status-menu .dropdown-item").forEach(item => {
            item.addEventListener("click", e => {
                e.preventDefault();
                statusBtn.textContent = e.target.textContent;
                statusBtn.setAttribute("data-value", e.target.dataset.value);
                document.getElementById("custom-status-menu").style.display = "none";
                applyFilters();
            });
        });

        // ✅ Clear All button (now inside)
        document.getElementById("clear-filters").addEventListener("click", function() {
            // Reset Select2 dropdowns
            $(admFilter).val(null).trigger("change");
            $(customerFilter).val(null).trigger("change");
            $(typeFilter).val(null).trigger("change");

            // Reset status button
            statusBtn.textContent = "Choose Status";
            statusBtn.removeAttribute("data-value");

            // Reset date
            dateFilter.value = "";

            // Show all rows
            rows.forEach(row => row.style.display = "");
        });
    });
</script>




@include('finance::layouts.footer')