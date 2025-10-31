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
            <h1 class="header-title">All Collection</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <div id="search-box-wrapper" class="collapsed">
                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                <input type="text" class="search-input" placeholder="Search Invoice Number, Customer Name, ADM Number, ADM Name" />
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
                        <th>Collection ID</th>
                        <th>Collected Date</th>
                        <th>ADM Number</th>
                        <th>ADM Name</th>
                        <th class="sticky-column">Total Collected Amount</th>
                    </tr>
                </thead>
                <tbody id="outstandingTableBody">
                    @foreach($collections as $collection)
                    <tr>
                        <td>{{ $collection['collection_id'] }}</td>
                        <td>{{ $collection['collection_date'] }}</td>
                        <td>{{ $collection['adm_number'] }}</td>
                        <td>{{ $collection['adm_name'] }}</td>
                        <td class="sticky-column">{{ number_format($collection['total_collected_amount'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <nav class="d-flex justify-content-center mt-5">
            <ul id="finalPagination" class="pagination"></ul>
        </nav>
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

        <!-- Divisions -->
        <div class="mt-5 radio-selection filter-categories">
            <p class="filter-title">Divisions</p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                    Division 1
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                    Division 2
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                    Division 3
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                    Division 4
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                    Division 5
                </label>
            </div>
        </div>

        <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
        </div>

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

<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

<!-- search function -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");

        searchInput.addEventListener("input", function() {
            const query = searchInput.value.toLowerCase();

            // Filter the outstanding data
            const filteredData = outstandingTableData.filter(item =>
                item.invoice.toLowerCase().includes(query) ||
                item.customer.toLowerCase().includes(query) ||
                item.admNumber.toLowerCase().includes(query) ||
                item.admName.toLowerCase().includes(query)
            );

            // Reset current page for filtered results
            currentPages.outstanding = 1;

            renderOutstandingTable(filteredData);
            renderOutstandingPagination(filteredData);
        });

        function renderOutstandingTable(data) {
            const tableBody = document.getElementById("outstandingTableBody");
            tableBody.innerHTML = "";

            const startIndex = (currentPages.outstanding - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, data.length);

            for (let i = startIndex; i < endIndex; i++) {
                const row = `
                <tr>
                    <td>${data[i].invoice}</td>
                    <td>${data[i].customer}</td>
                    <td>${data[i].date}</td>
                    <td>${data[i].admNumber}</td>
                    <td>${data[i].admName}</td>
                    <td>${data[i].totalAmount.toFixed(2)}</td>
                    <td>${data[i].outstandingBalance.toFixed(2)}</td>
                    <td class="sticky-column">${data[i].outstandingDays}</td>
                </tr>
            `;
                tableBody.innerHTML += row;
            }
        }

        function renderOutstandingPagination(data) {
            const pagination = document.getElementById("outstandingPagination");
            pagination.innerHTML = "";

            const totalPages = Math.ceil(data.length / rowsPerPage);
            const currentPage = currentPages.outstanding;

            // Prev button
            pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeOutstandingPage(${currentPage - 1}, data)">Prev</a>
            </li>
        `;

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changeOutstandingPage(${i}, data)">${i}</a>
                </li>
            `;
            }

            // Next button
            pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changeOutstandingPage(${currentPage + 1}, data)">Next</a>
            </li>
        `;
        }

        // Page change for filtered data
        window.changeOutstandingPage = function(page, data) {
            const totalPages = Math.ceil(data.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;

            currentPages.outstanding = page;
            renderOutstandingTable(data);
            renderOutstandingPagination(data);
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
@include('finance::layouts.footer')