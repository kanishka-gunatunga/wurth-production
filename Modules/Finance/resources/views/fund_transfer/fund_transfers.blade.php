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
            <h1 class="header-title">Fund Transfers</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <div id="search-box-wrapper" class="collapsed">
                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                <input type="text" class="search-input" placeholder="Search customer ID, Name or ADM ID, Name" />
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
                        <th>Date</th>
                        <th>ADM Number</th>
                        <th>ADM Name</th>
                        <th>Transfer Ref. No.</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="sticky-column">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($fundTransfers as $payment)
                    @php
                    $statusClass = match (strtolower($payment['status'])) {
                    'approved' => 'success-status-btn',
                    'deposited' => 'blue-status-btn',
                    'rejected' => 'danger-status-btn',
                    default => 'grey-status-btn'
                    };
                    @endphp
                    <tr onclick="window.location='{{ route('fund_transfers.show', $payment->id) }}'"
                        style="cursor: pointer;">
                        <td>{{ $payment->transfer_date }}</td>

                        <td>{{ $payment->invoice->customer->adm ?? '-' }}</td>

                        <td>{{ $payment->invoice->customer->admDetails->name ?? '-' }}</td>

                        <td>{{ $payment->transfer_reference_number }}</td>

                        <td>{{ number_format($payment->final_payment, 2) }}</td>

                        <td><button class="{{ $statusClass }}">{{ ucfirst($payment['status']) }}</button></td>

                        <td class="sticky-column">
                            <button class="success-action-btn" data-id="{{ $payment['id'] }}" data-status="Approved">Approve</button>
                            <button class="red-action-btn" data-id="{{ $payment['id'] }}" data-status="Rejected">Reject</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $fundTransfers->appends(request()->query())->links('pagination::bootstrap-5') }}
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
                </ul>
            </div>
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


<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

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

<!-- expand search bar and search functionality -->
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
            filterTable(this.value);
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