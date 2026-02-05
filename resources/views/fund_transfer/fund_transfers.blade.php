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

    .custom-dropdown-menu li {
        list-style: none !important;
    }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Fund Transfers</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <form id="searchForm" method="get" action="{{ url('fund-transfers') }}">
                @csrf
                <div id="search-box-wrapper" class="collapsed">
                    <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                    <input
                        type="text"
                        name="search"
                        class="search-input"
                        placeholder="Search ADM ID or Name"
                        value="{{ $filters['search'] ?? '' }}" />
                </div>
            </form>
            <button class="header-btn" id="search-toggle-button"><i class="fa-solid fa-magnifying-glass fa-xl"></i></button>
            <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter" aria-controls="offcanvasRight"><i class="fa-solid fa-filter fa-xl"></i></button>
        </div>
    </div>


    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>
        @if(!empty($filters))
        <form method="POST" action="{{ url('fund-transfers/export') }}">
            @csrf

            @foreach($filters as $key => $value)
            @if(is_array($value))
            @foreach($value as $v) 
            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
            @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
            @endforeach

            <div class="col-12 d-flex justify-content-end pe-5 mb-3 gap-3">
                <button type="submit" class="add-new-division-btn">Export</button>
            </div>
        </form>
        @endif
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
                    @forelse ($fundTransfers as $payment)
                    @php
                    $statusClass = match (strtolower($payment['status'])) {
                    'approved' => 'success-status-btn',
                    'deposited' => 'blue-status-btn',
                    'voided' => 'danger-status-btn',
                    'rejected' => 'danger-status-btn',
                    default => 'grey-status-btn'
                    };
                    @endphp
                    @php
                        $canView = in_array('deposits-fund-transfer-view', session('permissions', []));
                    @endphp
                    <tr 
                    @if($canView)
                            class="clickable-row"
                            data-href="{{ url('fund-transfers/' . $payment['id']) }}"
                            style="cursor:pointer;"
                        @endif

                    >
                        <td>{{ $payment['transfer_date'] }}</td>

                        <td>{{ $payment['adm_number'] }}</td>

                        <td>{{ $payment['adm_name'] }}</td>

                        <td>{{ $payment['transfer_reference_number'] }}</td>

                        <td>{{ number_format($payment['final_payment'], 2) }}</td>

                        <td><button class="{{ $statusClass }}">{{ ucfirst($payment['status']) }}</button></td>

                        <td class="sticky-column">
                            @if(strtolower($payment['status']) === 'pending')
                             @if(in_array('deposits-fund-transfer-status', session('permissions', [])))
                            <button class="success-action-btn" data-id="{{ $payment['id'] }}" data-status="approved">Approve</button>
                            <button class="red-action-btn" data-id="{{ $payment['id'] }}" data-status="rejected">Reject</button>
                            @endif
                            @endif
                             @if(in_array('deposits-fund-transfer-view', session('permissions', [])))
                            <a href="{{ url('fund-transfers/' . $payment['id']) }}"
                                class="black-action-btn"
                                onclick="event.stopPropagation()" style="text-decoration: none;">
                                View More
                            </a>
                             @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No fund transfers found.</td>
                    </tr>
                    @endforelse
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

<form id="filterForm" method="GET" action="{{ url('fund-transfers') }}">
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
                <a href="{{ url('fund-transfers') }}"><button type="button" class="btn rounded-phill" id="clear-filters">Clear All</button></a> 
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

            <!-- ADM Name Dropdown -->
           <div class="mt-5 filter-categories">
                <p class="filter-title">ADM Name</p>
                <select id="filter-adm-name" name="adm_names[]" class="form-control select2-filter" multiple  >
                    @foreach ($adms as $adm)
                    <option value="{{ $adm->id }}" {{ !empty($filters['adm_names']) && in_array($adm->id, $filters['adm_names']) ? 'selected' : '' }}>
                        {{ $adm->userDetails->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- ADM ID Dropdown -->
           <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
               <select id="filter-adm-id" name="adm_ids[]" class="form-control select2-filter" multiple>
                @foreach($adms as $adm)
                    @if(!empty($adm->userDetails->adm_number))
                        <option value="{{ $adm->userDetails->adm_number }}"  {{ !empty($filters['adm_ids']) && in_array($adm->userDetails->adm_number, $filters['adm_ids']) ? 'selected' : '' }}>
                            {{ $adm->userDetails->adm_number }}
                        </option>
                    @endif
                @endforeach
            </select>
            </div>

            <!-- Customers Dropdown -->
             <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select id="filter-customer" name="customers[]" class="form-control select2-filter" multiple >
                    @foreach ($customers as $customer)
                    <option value="{{ $customer->customer_id }}" {{ !empty($filters['customers']) && in_array($customer->customer_id, $filters['customers']) ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                
                    @endforeach
                </select>
            </div>


           <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" id="filter-date" name="date_range" class="form-control"
                    placeholder="Select date range"
                    value="{{ $filters['date_range'] ?? '' }}" />
            </div>
            
             <div class="mt-5 filter-categories">
                <p class="filter-title">Status</p>
                <select id="filter-status" name="status" class="form-control select2-filter">
                    @foreach ($fundTransfers->pluck('status')->unique() as $status)
                        <option value="{{ $status }}"  {{ ($filters['status'] ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 d-flex justify-content-start">
                <button type="submit" class="red-action-btn-lg">Apply Filters</button>
            </div>
        </div>
    </div>
</form>



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

<!-- Confirmation Modal -->
<div id="confirm-status-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2); text-align:center;">

        <!-- Close button -->
        <button id="confirm-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:1rem 0; font-weight:600; color:#000;">Are you sure?</h4>

        <p style="margin:1rem 0; color:#6c757d;">Do you want to change the status to <span id="confirm-status-text" style="font-weight:600;"></span>?</p>
 <div id="remark-box" style="display:none; margin-top:1rem; text-align:left;">
    <label style="font-weight:600;">Remark (required when declining)</label>
    <textarea id="decline-remark"
        style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;"
        rows="3"
        placeholder="Enter decline remark"></textarea>
</div>
        <!-- Action buttons -->
        <div style="display:flex; justify-content:center; gap:1rem; margin-top:2rem;">
            <button id="confirm-no-btn" style="padding:0.5rem 1rem; border-radius:12px; border:1px solid #ccc; background:#fff; cursor:pointer;">No</button>
            <button id="confirm-yes-btn" style="padding:0.5rem 1rem; border-radius:12px; border:none; background:#2E7D32; color:#fff; cursor:pointer;">Yes</button>
        </div>
    </div>
</div>
@include('layouts.footer2')

<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

<!-- for dropdown -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn = document.getElementById("custom-status-btn");
        const menu = document.getElementById("custom-status-menu");
        const hiddenInput = document.getElementById('filter-status-input');

        // 1️⃣ Set button text from hidden input on page load
        if (hiddenInput.value) {
            btn.textContent = hiddenInput.value;
            btn.setAttribute("data-value", hiddenInput.value);
        }

        // 2️⃣ Toggle menu
        btn.addEventListener("click", () => {
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });

        // 3️⃣ Click item
        menu.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", (e) => {
                e.preventDefault();
                btn.textContent = e.target.textContent;
                btn.setAttribute("data-value", e.target.dataset.value);

                // Update hidden input
                hiddenInput.value = e.target.dataset.value;

                menu.style.display = "none";
            });
        });

        // 4️⃣ Close if clicked outside
        document.addEventListener("click", (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    });
</script>

<!-- for approve/reject buttons -->
<script>
    let currentStatusButton = null;
    let newStatus = '';

    document.addEventListener('click', function(e) {

        // Approve / Reject buttons
        if (e.target.classList.contains('success-action-btn') || e.target.classList.contains('red-action-btn') ||
            e.target.classList.contains('success-action-btn-lg')) {

            e.preventDefault();
            e.stopPropagation();

            currentStatusButton = e.target; // Save clicked button reference
            newStatus = currentStatusButton.dataset.status || (currentStatusButton.classList.contains('success-action-btn') || currentStatusButton.classList.contains('success-action-btn-lg') ? 'approved' : 'rejected');

            // Show modal
            document.getElementById('confirm-status-text').innerText = newStatus;
            document.getElementById('confirm-status-modal').style.display = 'block';
        }
        if (newStatus === 'rejected') {
            document.getElementById('remark-box').style.display = 'block';
        } else {
            document.getElementById('remark-box').style.display = 'none';
            document.getElementById('decline-remark').value = '';
        }
        // Close modal
        if (e.target.id === 'confirm-modal-close' || e.target.id === 'confirm-no-btn') {
            document.getElementById('confirm-status-modal').style.display = 'none';
        }

        // Yes button
        if (e.target.id === 'confirm-yes-btn') {
            document.getElementById('confirm-status-modal').style.display = 'none';

            if (currentStatusButton) {
                const depositId = currentStatusButton.dataset.id;

                fetch(`{{ url('/fund-transfers/update-status') }}/${depositId}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            status: newStatus,remark: document.getElementById('decline-remark').value || null
                        })
                    })
                    .then(async (res) => {
                        const data = await res.json().catch(() => null);

                        if (!res.ok || !data || !data.success) {
                            throw new Error(data?.message || "Request failed");
                        }

                        // SUCCESS — update UI
                        let row = currentStatusButton.closest("tr");
                        let statusCell = row.querySelector("td:nth-child(6) button");

                        statusCell.innerText = data.status;
                        statusCell.className =
                            data.status.toLowerCase() === "approved" ?
                            "success-status-btn" :
                            "danger-status-btn";

                        row.querySelectorAll(".success-action-btn, .red-action-btn")
                            .forEach(btn => btn.style.display = "none");
                    })
                    .catch((err) => {
                        console.error(err);
                        alert("Failed to update status:\n" + err.message);
                    });

            }
        }

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

<!-- search on enter key press -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const input = document.getElementById("backendSearchInput");

        input.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                const value = input.value.trim();

                // Redirect to backend route with ?search= query
                const url = new URL(window.location.href);
                url.searchParams.set("search", value);

                window.location.href = url.toString();
            }
        });

    });
</script>

<!-- clear all button functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const clearBtn = document.getElementById('clear-filters');
        clearBtn.addEventListener('click', function() {
            // Clear multi-select dropdowns
            $('#filter-adm-name, #filter-adm-id, #filter-customer').val(null).trigger('change');

            // Clear date input
            document.getElementById('filter-date').value = '';

            // Clear custom status dropdown
            const statusBtn = document.getElementById('custom-status-btn');
            const statusInput = document.getElementById('filter-status-input');
            statusBtn.textContent = 'Choose Status';
            statusInput.value = '';

            // Submit the form after a short delay
            setTimeout(() => document.getElementById('filterForm').submit(), 200);
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");
        const searchForm = document.getElementById("searchForm");

        // Submit form when Enter is pressed
        searchInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                searchForm.submit();
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('fail'))
        toastr.error("{{ Session::get('fail') }}");
        @endif
    });

    $(document).ready(function () {
    // Initialize all normal select2
    $('.select2-filter').select2({
        width: '100%',
        dropdownParent: $('#searchByFilter')  // IMPORTANT for offcanvas
    });

    
});

</script>
