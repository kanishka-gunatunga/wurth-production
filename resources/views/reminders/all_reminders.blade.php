@include('layouts.dashboard-header')

<div class="container-fluid">
    <style>
        /* Search box styles */
        .search-box-wrapper {
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
        }

        .search-box-wrapper.collapsed {
            width: 0;
            padding: 0;
            margin: 0;
            border: 1px solid transparent;
            background-color: transparent;
        }

        .search-box-wrapper.expanded {
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
            padding-left: 30px;
            /* space for icon */
        }

        .search-input::placeholder {
            color: #888;
        }

        .search-icon-inside {
            position: absolute;
            left: 10px;
            color: #888;
        }

        /* Optional: Adjust button alignment if needed */
        .col-12.d-flex.justify-content-lg-end {
            align-items: center;
        }
    </style>


    <div class="main-wrapper">

        <div class="p-4 pt-0">
            <div class="row d-flex justify-content-between">
                <div class="col-lg-6 col-12">
                    <h1 class="header-title">Reminders</h1>
                </div>
            </div>


            <div class="styled-tab-main">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item mb-3" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#payment" role="tab" aria-controls="payment"
                            aria-selected="true">
                            Payment Reminders
                        </a>
                    </li>

                    <li class="nav-item mb-3" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#system" role="tab" aria-controls="system"
                            aria-selected="false">
                            System Reminders
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Payment Notifications Tab Pane -->
                    <div id="payment" class="tab-pane fade show active" role="tabpanel" aria-labelledby="payment-tab">
                        <div class="row">
                            <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                                <!-- search form (GET) -->
                                <form id="payment-search-form" method="GET" action="{{ route('reminders.index') }}" class="d-flex align-items-center">
                                    <div id="payment-search-box-wrapper" class="search-box-wrapper collapsed">
                                        <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                                        <input type="text" name="q" class="search-input" placeholder="Search title"
                                            value="{{ request('q') ?? '' }}" autocomplete="off" />
                                    </div>
                                </form>

                                <button class="header-btn" id="payment-search-toggle-button">
                                    <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                                </button>
                                <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter"
                                    aria-controls="offcanvasRight">
                                    <i class="fa-solid fa-filter fa-xl"></i>
                                </button>
                            </div>
                        </div>

                        <ul class="list-group mt-3" id="paymentNotifications">
                            @forelse($reminders as $reminder)
                            <li class="list-group-item d-flex justify-content-between align-items-start notification-row"
                                style="cursor:pointer;"
                                onclick="window.location.href='{{ url('reminders/'.$reminder->id) }}'">

                                <div>
                                    <div class="fw-bold">{{ Str::limit($reminder->reason, 120) }}</div>
                                    <small class="text-muted">{{ $reminder->reminder_title }}</small>
                                    <!-- <div class="small text-muted mt-1">{{ Str::limit($reminder->reason, 120) }}</div> -->
                                </div>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('Y-m-d') }}
                                </small>
                            </li>
                            @empty
                            <li class="list-group-item text-center">No payment reminders found.</li>
                            @endforelse
                        </ul>

                        <nav class="d-flex justify-content-center mt-3">
                            {{ $reminders->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>


                    <!-- System Notifications Tab Pane -->
                    <div id="system" class="tab-pane fade" role="tabpanel" aria-labelledby="system-tab">
                        <div class="row">
                            <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                                <div id="system-search-box-wrapper" class="search-box-wrapper collapsed">
                                    <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                                    <input type="text" class="search-input" placeholder="Search title" />
                                </div>
                                <button class="header-btn" id="system-search-toggle-button">
                                    <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                                </button>
                                <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#systemFilter"
                                    aria-controls="systemFilter">
                                    <i class="fa-solid fa-filter fa-xl"></i>
                                </button>

                            </div>
                        </div>
                        <ul class="list-group" id="temporaryNotifications"></ul>
                        <nav class="d-flex justify-content-center mt-3">
                            <ul id="temporaryNotificationsPagination" class="pagination"></ul>
                        </nav>
                    </div>



                </div>
            </div>
        </div>



    </div>


    <!-- Payment Notifications Filter Offcanvas -->
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

            <div class="mt-5 filter-categories">
                <p class="filter-title">From</p>
                <select class="form-control select2" multiple="multiple">
                    <option>John Doe</option>
                    <option>Jane Smith</option>
                    <option>Robert Lee</option>
                    <option>Emily Johnson</option>
                    <option>Michael Brown</option>
                </select>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">To</p>
                <select class="form-control select2" multiple="multiple">
                    <option>John Doe</option>
                    <option>Jane Smith</option>
                    <option>Robert Lee</option>
                    <option>Emily Johnson</option>
                    <option>Michael Brown</option>
                </select>
            </div>

            <div class="mt-5 filter-categories">
            <p class="filter-title">Date</p>
            <input type="text" id="filter-date" class="form-control" placeholder="Select date range" />
        </div>
        </div>
    </div>

    <!-- System Notifications Filter Offcanvas -->
    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="systemFilter"
        aria-labelledby="systemFilterLabel">
        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title" id="systemFilterLabel">Search </span> <span class="title-rest"> &nbsp;by Filter</span>
            </div>
            <div class="col-6">
                <button class="btn rounded-phill" id="systemFilterClear">Clear All</button>
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

            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
            </div>

        </div>
    </div>



    <script>
        const notificationsData = {
            paymentNotifications: [{
                    sender: "Customer payment reminder",
                    title: "Your payment has been received successfully",
                    date: "9/16/2025"
                },
                {
                    sender: "Customer payment reminder",
                    title: "Return cheque uploaded to your account",
                    date: "9/16/2025"
                },
                {
                    sender: "Customer payment reminder",
                    title: "Please update your profile information",
                    date: "9/16/2025"
                },
                {
                    sender: "Customer payment reminder",
                    title: "Monthly statement is ready",
                    date: "9/16/2025"
                },
                {
                    sender: "Customer payment reminder",
                    title: "Account synced successfully",
                    date: "9/16/2025"
                }
            ],
            temporaryNotifications: [{
                    title: "System reminder",
                    description: "A new temporary customer has been added",
                    date: "9/16/2025"
                },
                {
                    title: "System reminder",
                    description: "Temporary customer requires approval",
                    date: "9/16/2025"
                },
                {
                    title: "System reminder",
                    description: "Temporary customer account auto-expired",
                    date: "9/16/2025"
                },
                {
                    title: "System reminder",
                    description: "Temporary record updated",
                    date: "9/16/2025"
                }
            ]
        };

        function renderNotifications(listId, page = 1, itemsPerPage = 10, customData = null) {
            const list = document.getElementById(listId);
            const pagination = document.getElementById(listId + "Pagination");
            if (!list || !pagination) return;

            const data = customData || notificationsData[listId]; // ✅ use filtered data if provided
            const totalPages = Math.ceil(data.length / itemsPerPage);

            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedData = data.slice(start, end);

            // determine the static link for each list
            const rowLink = listId === "paymentNotifications" ?
                "/payment-notifications-details-admin" :
                "/system-notifications-details-admin";

            // render notifications
            list.innerHTML = "";
            paginatedData.forEach(n => {
                let content = "";

                if (listId === "paymentNotifications") {
                    content = `
                  <div>
                    <div class="fw-bold">${n.title}</div>
                    <small class="text-muted">${n.sender}</small>
                  </div>
                  <small class="text-muted">${n.date}</small>
                `;
                } else if (listId === "temporaryNotifications") {
                    content = `
                  <div>
                    <div class="fw-bold">${n.title}</div>
                    <small class="text-muted">${n.description}</small>
                  </div>
                  <small class="text-muted">${n.date}</small>
                `;
                }

                list.innerHTML += `
              <li class="list-group-item d-flex justify-content-between align-items-start notification-row"
                  style="cursor:pointer;" onclick="window.location.href='${rowLink}'">
                ${content}
              </li>
            `;
            });

            // render pagination
            pagination.innerHTML = "";
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `
                <li class="page-item ${i === page ? "active" : ""}">
                  <a class="page-link" href="#"
                     onclick="renderNotifications('${listId}', ${i}, ${itemsPerPage}); return false;">
                    ${i}
                  </a>
                </li>
            `;
            }
        }

        window.onload = function() {
            renderNotifications("paymentNotifications");
            renderNotifications("temporaryNotifications");
        }
    </script>

    <style>
        /* Hover effect for notifications */
        .notification-row:hover {
            background-color: #f1f1f1;
            transition: background-color 0.2s;
        }
    </style>



    <!-- expand search bar  -->
    <!-- <script>
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
</script> -->

    <!-- Search functionality -->
    <!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("#search-box-wrapper .search-input");

        searchInput.addEventListener("input", function() {
            const query = this.value.trim().toLowerCase();

            // filter by title instead of sender
            const filteredPayment = notificationsData.paymentNotifications.filter(n =>
                n.title.toLowerCase().includes(query)
            );
            const filteredTemporary = notificationsData.temporaryNotifications.filter(n =>
                n.title.toLowerCase().includes(query)
            );

            // temporarily swap data, render, then restore
            const originalPayment = notificationsData.paymentNotifications;
            const originalTemporary = notificationsData.temporaryNotifications;

            notificationsData.paymentNotifications = filteredPayment;
            notificationsData.temporaryNotifications = filteredTemporary;

            renderNotifications("paymentNotifications");
            renderNotifications("temporaryNotifications");

            // restore originals so pagination always works correctly
            notificationsData.paymentNotifications = originalPayment;
            notificationsData.temporaryNotifications = originalTemporary;
        });
    });
</script> -->

    <!-- for search bar in each tab -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function setupSearch(searchWrapperId, toggleButtonId, listId, dataKey) {
                const searchWrapper = document.getElementById(searchWrapperId);
                const toggleButton = document.getElementById(toggleButtonId);
                const searchInput = searchWrapper.querySelector(".search-input");

                // Keep a copy of the original data for restoration
                const originalData = [...notificationsData[dataKey]];

                let idleTimeout;
                const idleTime = 5000;

                function collapseSearch() {
                    searchWrapper.classList.remove("expanded");
                    searchWrapper.classList.add("collapsed");
                    toggleButton.classList.remove("d-none");
                    clearTimeout(idleTimeout);
                }

                function startIdleTimer() {
                    clearTimeout(idleTimeout);
                    idleTimeout = setTimeout(() => {
                        if (!searchInput.value) collapseSearch();
                    }, idleTime);
                }

                toggleButton.addEventListener("click", function() {
                    if (searchWrapper.classList.contains("collapsed")) {
                        searchWrapper.classList.remove("collapsed");
                        searchWrapper.classList.add("expanded");
                        toggleButton.classList.add("d-none");
                        searchInput.focus();
                        startIdleTimer();
                    } else {
                        collapseSearch();
                    }
                });

                searchInput.addEventListener("keydown", startIdleTimer);

                searchInput.addEventListener("input", function() {
                    const query = this.value.trim().toLowerCase();

                    let filteredData;
                    if (query) {
                        filteredData = originalData.filter(n => n.title.toLowerCase().includes(query));
                    } else {
                        filteredData = originalData; // restore original data when input is cleared
                    }

                    renderNotifications(listId, 1, 10, filteredData);

                });
            }

            // Initialize search bars for both tabs
            setupSearch(
                "payment-search-box-wrapper",
                "payment-search-toggle-button",
                "paymentNotifications",
                "paymentNotifications"
            );

            setupSearch(
                "system-search-box-wrapper",
                "system-search-toggle-button",
                "temporaryNotifications",
                "temporaryNotifications"
            );

        });
    </script>


    <script>
        document.querySelectorAll('.selectable-filter').forEach(function(tag) {
            tag.addEventListener('click', function() {
                tag.classList.toggle('selected');
            });
        });
    </script>
</div>