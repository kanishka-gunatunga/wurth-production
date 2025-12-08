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
            <h1 class="header-title">Inquiries</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <form id="searchForm" action="{{ url('/inquiries/search') }}" method="POST">
                @csrf
                <div id="search-box-wrapper" class="collapsed">
                    <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                    <input
                        type="text"
                        name="query"
                        class="search-input"
                        placeholder="Search Inquiry no."
                        value="{{ $searchQuery ?? '' }}" />
                </div>
            </form>
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
                    @forelse ($inquiries as $inquiry)
                    <tr class="clickable-row"
                        data-href="{{ url('/inquiry-details', $inquiry->id) }}"
                        data-adm="{{ $inquiry->adm_id }}"
                        data-customer="{{ $inquiry->customer }}"
                        data-type="{{ $inquiry->type }}"
                        data-status="{{ $inquiry->status }}"
                        data-date="{{ $inquiry->created_at }}">

                        <td>{{ $inquiry->id }}</td>
                        <td>{{ $inquiry->created_at ? $inquiry->created_at->format('Y.m.d') : 'N/A' }}</td>
                        <td>{{ $inquiry->type }}</td>
                        <td> {{ $inquiry->admin?->userDetails?->adm_number ?? 'N/A' }} </td>
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

                            @if($status === 'pending')
                            <button class="success-action-btn">Approve</button>
                            <button class="red-action-btn">Reject</button>
                            @elseif($status === 'sorted')
                            <button class="red-action-btn">Reject</button>
                            @elseif($status === 'rejected')
                            <button class="success-action-btn">Approve</button>
                            @endif

                            @if($inquiry->attachement)
                            <a href="{{ route('inquiries.download', $inquiry->id) }}"
                                class="black-action-btn submit"
                                style="text-decoration: none;"
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
                        <td colspan="6" class="text-center text-muted">No inquiries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="col-12 d-flex justify-content-center laravel-pagination mt-4">
            {{ $inquiries->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
</div>
</div>
</div>

<form action="{{ url('/inquiries/filter') }}" method="POST">
    @csrf
    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="searchByFilter"
        aria-labelledby="offcanvasRightLabel">

        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title" id="offcanvasRightLabel">Search </span>
                <span class="title-rest">&nbsp;by Filter</span>
            </div>
            <div>
                <button type="button" class="btn rounded-phill" id="clear-filters">Clear All</button>
            </div>
        </div>

        <div class="offcanvas-body">
            <!-- ADM ID -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select name="adm_ids[]" id="filter-adm" class="form-control select2" multiple="multiple">
                    @foreach ($inquiries->pluck('adm_id')->unique() as $admId)
                    <option value="{{ $admId }}"
                        {{ isset($filters['adm_ids']) && in_array($admId, $filters['adm_ids']) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endforeach
                </select>

            </div>

            <!-- Customers -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select name="customers[]" id="filter-customer" class="form-control select2" multiple="multiple">
                    @foreach ($inquiries->pluck('customer')->unique() as $customer)
                    <option value="{{ $customer }}"
                        {{ isset($filters['customers']) && in_array($customer, $filters['customers']) ? 'selected' : '' }}>
                        {{ $customer }}
                    </option>
                    @endforeach
                </select>

            </div>

            <!-- Inquiry Type -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Inquiry type</p>
                <select name="types[]" id="filter-type" class="form-control select2" multiple="multiple">
                    @foreach ($inquiries->pluck('type')->unique() as $type)
                    <option value="{{ $type }}"
                        {{ isset($filters['types']) && in_array($type, $filters['types']) ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                    @endforeach
                </select>

            </div>

            <!-- Status -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Status</p>
                <input type="hidden" name="status" id="status-input" value="{{ $filters['status'] ?? '' }}">
                <div class="custom-dropdown-container" style="position: relative; min-width: 200px;">
                    <button type="button" id="custom-status-btn" class="btn custom-dropdown text-start" style="width:100%;">
                        {{ $filters['status'] ?? 'Choose Status' }}
                    </button>
                    <ul id="custom-status-menu" class="custom-dropdown-menu"
                        style="display:none; position:absolute; top:100%; left:0; background:#fff; border:1px solid #ddd; width:100%; z-index:999;">
                        @foreach ($inquiries->pluck('status')->unique() as $status)
                        <li><a href="#" class="dropdown-item" data-value="{{ $status }}">{{ $status }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Date Range -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" name="date_range" id="filter-date" class="form-control"
                    placeholder="Select date range"
                    value="{{ $filters['date_range'] ?? '' }}" />
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
        <button id="confirm-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>
        <h4 style="margin:1rem 0; font-weight:600; color:#000;">Are you sure?</h4>
        <p style="margin:1rem 0; color:#6c757d;">Do you want to change the status to <span id="confirm-status-text" style="font-weight:600;"></span>?</p>
        <div style="display:flex; justify-content:center; gap:1rem; margin-top:2rem;">
            <button id="confirm-no-btn" style="padding:0.5rem 1rem; border-radius:12px; border:1px solid #ccc; background:#fff; cursor:pointer;">No</button>
            <button id="confirm-yes-btn" style="padding:0.5rem 1rem; border-radius:12px; border:none; background:#2E7D32; color:#fff; cursor:pointer;">Yes</button>
        </div>
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

        searchInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                document.getElementById("searchForm").submit();
            }
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
    document.addEventListener('DOMContentLoaded', function() {
        let selectedAction = null;
        let selectedRow = null;
        const modal = document.getElementById('confirm-status-modal');
        const statusText = document.getElementById('confirm-status-text');
        const yesBtn = document.getElementById('confirm-yes-btn');
        const noBtn = document.getElementById('confirm-no-btn');
        const closeBtn = document.getElementById('confirm-modal-close');

        // Function to show modal
        function showModal(action, row) {
            selectedAction = action; // 'approve' or 'reject'
            selectedRow = row;
            statusText.textContent = action === 'approve' ? 'Sorted' : 'Rejected';
            modal.style.display = 'block';
        }

        // Function to hide modal
        function hideModal() {
            modal.style.display = 'none';
            selectedAction = null;
            selectedRow = null;
        }

        // Close modal buttons
        noBtn.addEventListener('click', hideModal);
        closeBtn.addEventListener('click', hideModal);

        // Handle approve/reject button click
        document.querySelectorAll('tr.clickable-row').forEach(row => {
            const approveBtn = row.querySelector('.success-action-btn');
            const rejectBtn = row.querySelector('.red-action-btn');

            if (approveBtn) {
                approveBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    showModal('approve', row);
                });
            }

            if (rejectBtn) {
                rejectBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    showModal('reject', row);
                });
            }
        });

        // Confirm action
        yesBtn.addEventListener('click', function() {
            if (!selectedAction || !selectedRow) return;

            const inquiryId = selectedRow.dataset.href.split('/').pop();
            const url = `/inquiries/${selectedAction}/${inquiryId}`;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update status button
                        const statusBtn = selectedRow.querySelector('td button');
                        statusBtn.textContent = data.status;
                        statusBtn.className =
                            data.status.toLowerCase() === 'sorted' ? 'success-status-btn' :
                            data.status.toLowerCase() === 'rejected' ? 'danger-status-btn' :
                            'grey-status-btn';

                        // Clear existing action buttons
                        const actionsCell = selectedRow.querySelector('td.sticky-column');
                        actionsCell.querySelectorAll('.success-action-btn, .red-action-btn').forEach(b => b.remove());

                        // Render buttons according to new status
                        const newStatus = data.status.toLowerCase();
                        if (newStatus === 'pending') {
                            actionsCell.insertAdjacentHTML('afterbegin', `
                    <button class="success-action-btn">Approve</button>
                    <button class="red-action-btn">Reject</button>
                `);
                        } else if (newStatus === 'sorted') {
                            actionsCell.insertAdjacentHTML('afterbegin', `
                    <button class="red-action-btn">Reject</button>
                `);
                        } else if (newStatus === 'rejected') {
                            actionsCell.insertAdjacentHTML('afterbegin', `
                    <button class="success-action-btn">Approve</button>
                `);
                        }

                        // Rebind click events for the new buttons
                        const approveBtn = actionsCell.querySelector('.success-action-btn');
                        const rejectBtn = actionsCell.querySelector('.red-action-btn');

                        if (approveBtn) {
                            approveBtn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                showModal('approve', selectedRow);
                            });
                        }

                        if (rejectBtn) {
                            rejectBtn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                showModal('reject', selectedRow);
                            });
                        }
                    }
                    hideModal();
                })
                .catch(err => {
                    console.error(err);
                    hideModal();
                });
        })
    });
</script>

<!-- for dropdown -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn = document.getElementById("custom-status-btn");
        const menu = document.getElementById("custom-status-menu");
        const statusInput = document.getElementById("status-input");

        btn.addEventListener("click", () => {
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });

        menu.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", (e) => {
                e.preventDefault();
                const value = e.target.dataset.value;
                btn.textContent = e.target.textContent;
                statusInput.value = value;
                menu.style.display = "none";
            });
        });
    });
</script>

<!-- clear all button functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const clearBtn = document.getElementById('clear-filters');
        const form = clearBtn.closest('form');

        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Clear Select2 dropdowns
            $('#filter-adm').val(null).trigger('change');
            $('#filter-customer').val(null).trigger('change');
            $('#filter-type').val(null).trigger('change');

            // Clear custom status dropdown
            document.getElementById('status-input').value = '';
            document.getElementById('custom-status-btn').textContent = 'Choose Status';

            // Clear date range
            document.getElementById('filter-date').value = '';

            // Submit form to refresh the view
            form.submit();
        });
    });
</script>