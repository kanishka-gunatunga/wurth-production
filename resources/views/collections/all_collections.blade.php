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
            <h1 class="header-title">All Collection</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <form id="searchForm" method="POST" action="{{ url('all-collections/search') }}">
                @csrf
                <div id="search-box-wrapper" class="collapsed">
                    <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                    <input
                        type="text"
                        name="search"
                        class="search-input"
                        placeholder="Search Invoice Number, Customer Name, ADM Number, ADM Name"
                        value="{{ $filters['search'] ?? '' }}" />
                </div>
            </form>
            <button class="header-btn" id="search-toggle-button"><i class="fa-solid fa-magnifying-glass fa-xl"></i></button>
            <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter" aria-controls="offcanvasRight"><i class="fa-solid fa-filter fa-xl"></i></button>
        </div>
    </div>


    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>
        @if(!empty($filters) && collect($filters)->filter(function($value) {
        return !empty($value);
        })->isNotEmpty())
        <form method="POST" action="{{ url('collections/export') }}">
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
            <table class="table custom-table-locked" style="min-width: 1600px;">
                <thead>
                    <tr>
                        <th>Collection ID</th>
                        <th>Collected Date</th>
                        <th>ADM Number</th>
                        <th>ADM Name</th>
                        <th class="sticky-column">Total Collected Amount</th>
                    </tr>
                </thead>
                <tbody id="outstandingTableBody">
                    @forelse($collections as $collection)
                    <tr onclick="window.location='{{ url('collection-details', $collection['collection_id']) }}'" style="cursor:pointer;">
                        <td>{{ $collection['collection_id'] }}</td>
                        <td>{{ $collection['collection_date'] }}</td>
                        <td>{{ $collection['adm_number'] }}</td>
                        <td>{{ $collection['adm_name'] }}</td>
                        <td class="sticky-column">{{ number_format($collection['total_collected_amount'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No collections found.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $collections->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<form id="filterForm" method="GET" action="{{ url('collections/filter') }}">
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
                <button type="button" class="btn rounded-phill" id="clear-filters">Clear All</button>
            </div>
        </div>
        <div class="offcanvas-body">
            <!-- <div class="row">
            <p class="filter-title">User roles</p>
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
                <select id="filter-adm-name" name="adm_names[]" class="form-control select2" multiple>
                    @foreach ($collections->pluck('adm_name')->unique() as $admName)
                    @if($admName)
                    <option value="{{ $admName }}"
                        {{ !empty($filters['adm_names']) && in_array($admName, $filters['adm_names']) ? 'selected' : '' }}>
                        {{ $admName }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <!-- ADM ID Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select id="filter-adm-id" name="adm_ids[]" class="form-control select2" multiple>
                    @foreach ($collections->pluck('adm_number')->unique() as $admId)
                    <option value="{{ $admId }}"
                        {{ !empty($filters['adm_ids']) && in_array($admId, $filters['adm_ids']) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Customers Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select id="filter-customer" name="customers[]" class="form-control select2" multiple>
                    @foreach ($collections->getCollection()->flatMap->customers->filter()->unique() as $customer)
                    @if($customer)
                    <option value="{{ $customer }}"
                        {{ !empty($filters['customers']) && in_array($customer, $filters['customers']) ? 'selected' : '' }}>
                        {{ $customer }}
                    </option>
                    @endif
                    @endforeach
                </select>

            </div>

            <!-- Divisions -->
            <div class="mt-5 radio-selection filter-categories">
                <p class="filter-title">Divisions</p>
                @foreach ($collections->pluck('division')->filter()->unique() as $division)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="divisions[]" value="{{ $division }}">
                    <label class="form-check-label">{{ $division }}</label>
                </div>
                @endforeach
            </div>

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



<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

<!-- search on enter -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");
        const searchForm = document.getElementById("searchForm");

        searchInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                searchForm.submit();
            }
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

<script>
    document.querySelectorAll('.selectable-filter').forEach(function(tag) {
        tag.addEventListener('click', function() {
            tag.classList.toggle('selected');
        });
    });
</script>
@include('layouts.footer2')