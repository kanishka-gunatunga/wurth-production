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
            <h1 class="header-title">Advance Payments</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <form id="searchForm" method="POST" action="{{ url('advanced-payments/search') }}">
                @csrf
                <div id="search-box-wrapper" class="collapsed">
                    <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                    <input
                        type="text"
                        name="search"
                        class="search-input"
                        placeholder="Search ADM Number or Name, Customer Name"
                        value="{{ $filters['search'] ?? '' }}" />
                </div>
            </form>

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
                    <tr class="clickable-row" data-href="{{ url('advance-payments-details/'.$payment->id) }}">
                        <td>{{ $payment->date }}</td>
                        <td>{{ $payment->adm_id }}</td>
                        <td>{{ $payment->admDetails?->name ?? 'N/A' }}</td>
                        <td>{{ $payment->customerData?->name ?? 'N/A' }}</td>
                        <td>{{ number_format($payment->payment_amount, 2) }}</td>
                        <td class="sticky-column">
                            <button class="success-action-btn" data-href="{{ url('advance-payments-details/'.$payment->id) }}">
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




<form id="filterForm" method="POST" action="{{ url('advanced-payments/search') }}">
    @csrf

    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="searchByFilter">
        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title">Search</span>
                <span class="title-rest">&nbsp;by Filter</span>
            </div>

            <div>
                <button type="button" class="btn rounded-phill" id="clear-filters">Clear All</button>
            </div>
        </div>

        <div class="offcanvas-body">
            <!-- ADM Name -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM Name</p>
                <select id="filter-adm-name" name="adm_names[]" class="form-control select2" multiple>
                    @foreach ($payments->pluck('admDetails.name')->unique() as $admName)
                    @if($admName)
                    <option value="{{ $admName }}"
                        {{ !empty($filters['adm_names']) && in_array($admName, $filters['adm_names']) ? 'selected' : '' }}>
                        {{ $admName }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <!-- ADM ID -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select id="filter-adm-id" name="adm_ids[]" class="form-control select2" multiple>
                    @foreach ($payments->pluck('adm_id')->unique() as $admId)
                    <option value="{{ $admId }}"
                        {{ !empty($filters['adm_ids']) && in_array($admId, $filters['adm_ids']) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Customers -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select id="filter-customer" name="customers[]" class="form-control select2" multiple>
                    @foreach ($payments->pluck('customerData.name')->unique() as $customer)
                    @if($customer)
                    <option value="{{ $customer }}"
                        {{ !empty($filters['customers']) && in_array($customer, $filters['customers']) ? 'selected' : '' }}>
                        {{ $customer }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" id="filter-date" name="date_range" class="form-control"
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


<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button') && !e.target.closest('a')) {
            window.location.href = row.getAttribute('data-href');
        }

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

<!-- search form submit on enter key -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");
        const searchForm = document.getElementById("searchForm");

        // If user presses Enter, submit the form
        searchInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                searchForm.submit();
            }
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


<!-- clear all button functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const clearBtn = document.getElementById('clear-filters');
        clearBtn.addEventListener('click', function() {
            $('#filter-adm-name, #filter-adm-id, #filter-customer').val(null).trigger('change');
            document.getElementById('filter-date').value = '';
            setTimeout(() => document.getElementById('filterForm').submit(), 200);
        });
    });
</script>