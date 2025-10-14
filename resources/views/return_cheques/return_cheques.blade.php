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

<div class="container-fluid">
    <div class="main-wrapper">

        <div class="p-4 pt-0">
            <div class="row d-flex justify-content-between">
                <div class="col-lg-6 col-12">
                    <h1 class="header-title">Return Cheque</h1>
                </div>
                <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 ">
                    <div id="search-box-wrapper" class="collapsed">
                        <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                        <input type="text" class="search-input" placeholder="Search ADM No. or Name, Return Cheque Number" />
                    </div>
                    <button class="header-btn" id="search-toggle-button"><i
                            class="fa-solid fa-magnifying-glass fa-xl"></i></button>
                    <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter"
                        aria-controls="offcanvasRight"><i class="fa-solid fa-filter fa-xl"></i></button>
                </div>
            </div>

            <hr class="red-line mt-0">



            <div class="col-12 d-flex justify-content-end pe-5 mb-3 gap-3">
                <a href="{{url('upload')}}" style="text-decoration: none;">
                    <button class="add-new-division-btn"
                        style="background-color: black; color: white; display: flex; align-items: center; gap: 6px; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer;">
                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_1784_47778)">
                                <path
                                    d="M3.23663 1.24316H12.5471L16.7366 5.43266V17.7432H9.98663V16.2432H15.2366V7.24316H10.7366V2.74316H4.73663V10.2432H3.23663V1.24316ZM12.2366 3.05366V5.74316H14.9261L12.2366 3.05366ZM6.27413 11.0592L9.91763 14.7447L6.27413 18.4302L5.20762 17.3757L7.06763 15.4947H1.73438V13.9947H7.06688L5.20688 12.1137L6.27413 11.0592Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_1784_47778">
                                    <rect width="18" height="18" fill="white"
                                        transform="translate(0.986328 0.493164)" />
                                </clipPath>
                            </defs>
                        </svg>

                        <span>Import Return Cheques</span>
                    </button>
                </a>

                <a href="{{ url('create-return-cheque') }}">
                    <button class="add-new-division-btn">+ Add New Return Cheque</button>
                </a>
            </div>

            <div class="table-responsive division-table">
                <table class="table" style="min-width: 1800px;">
                    <thead>
                        <tr>
                            <th>ADM No.</th>
                            <th>ADM Name</th>
                            <th>Return Cheque Number</th>
                            <th>Cheque Amount</th>
                            <th>Returned Date</th>
                            <th>Bank Name</th>
                            <th>Branch</th>
                            <th>Return Type</th>
                            <th class="sticky-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returnCheques as $cheque)
                        <tr>
                            <td>{{ $cheque->adm->id ?? 'N/A' }}</td>
                            <td>{{ $cheque->adm->userDetails->name ?? 'N/A' }}</td>
                            <td>{{ $cheque->cheque_number }}</td>
                            <td>Rs.{{ number_format($cheque->cheque_amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($cheque->returned_date)->format('Y-m-d') }}</td>
                            <td>{{ $cheque->bank_id }}</td>
                            <td>{{ $cheque->branch_id }}</td>
                            <td>{{ $cheque->return_type }}</td>
                            <td class="sticky-column">
                                <a href="{{ route('returncheques.show', $cheque->id) }}" style="text-decoration: none;">
                                    <button class="action-btn btn-sm btn-dark">View More</button>
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No return cheques found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <div class="d-flex justify-content-center mt-4">
                {{ $returnCheques->links('pagination::bootstrap-5') }}
            </div>

        </div>




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

        <div class="mt-5 filter-categories">
            <p class="filter-title">Return Type</p>
            <select class="form-control select2" multiple="multiple">
                <option>Invalid amount</option>
                <option>Invalid number</option>
                <option>Invalid date</option>
            </select>
        </div>

        <div class="mt-5 filter-categories">
            <p class="filter-title">Returned Date</p>
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
        </div>
    </div>
</div>
</div>





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

<!-- search functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");

        searchInput.addEventListener("input", function() {
            const query = this.value.toLowerCase();

            // Filter the returnChequeData
            const filteredData = returnChequeData.filter(item =>
                item.admNo.toLowerCase().includes(query) ||
                item.admName.toLowerCase().includes(query) ||
                item.chequeNo.toLowerCase().includes(query)
            );

            // Render table with filtered results
            renderFilteredReturnChequeTable(filteredData);
        });
    });

    // New function to render filtered data without breaking pagination
    function renderFilteredReturnChequeTable(data) {
        const tableBody = document.getElementById("returnChequeTableBody");
        tableBody.innerHTML = "";

        data.forEach(item => {
            const row = `<tr>
                <td>${item.admNo}</td>
                <td>${item.admName}</td>
                <td>${item.chequeNo}</td>
                <td>${item.amount}</td>
                <td>${item.returnedDate}</td>
                <td>${item.bank}</td>
                <td>${item.branch}</td>
                <td>${item.type}</td>
                <td class="sticky-column">
                    <a href="{{url('return-cheque-details')}}" style="text-decoration: none;">
                        <button class="action-btn btn-sm btn-dark">View More</button>
                    </a>
                </td>
            </tr>`;
            tableBody.innerHTML += row;
        });
    }
</script>


<script>
    document.querySelectorAll('.selectable-filter').forEach(function(tag) {
        tag.addEventListener('click', function() {
            tag.classList.toggle('selected');
        });
    });
</script>