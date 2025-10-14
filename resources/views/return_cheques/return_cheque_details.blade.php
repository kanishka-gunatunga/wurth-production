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
            </div>

            <hr class="red-line mt-0">

            <div class="styled-tab-sub p-4">
                <div class="tab-content">
                    <div id="customer-details" class="tab-pane fade show active" role="tabpanel"
                        aria-labelledby="customer-list-tab">
                        <div class="row d-flex justify-content-between mt-2">
                            <!-- <h2 class="section-title mb-4">Customer Details</h2> -->

                            <div class="detail-row">
                                <span class="detail-label">ADM No. :</span>
                                <span class="detail-value">{{ $returnCheque->adm->id ?? 'N/A' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">ADM Name :</span>
                                <span class="detail-value">{{ $returnCheque->adm->userDetails->name ?? 'N/A' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Return Cheque Number :</span>
                                <span class="detail-value">{{ $returnCheque->cheque_number }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Cheque Amount :</span>
                                <span class="detail-value">Rs.{{ number_format($returnCheque->cheque_amount, 2) }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Returned Date :</span>
                                <span class="detail-value">{{ \Carbon\Carbon::parse($returnCheque->returned_date)->format('d.m.Y') }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Bank Name :</span>
                                <span class="detail-value">{{ $returnCheque->bank_id ?? 'N/A' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Branch :</span>
                                <span class="detail-value">{{ $returnCheque->branch_id ?? 'N/A' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Return Type :</span>
                                <span class="detail-value">{{ $returnCheque->return_type }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Reason :</span>
                                <span class="detail-value">{{ $returnCheque->reason ?? 'N/A' }}</span>
                            </div>




                        </div>

                    </div>
                </div>
            </div>

            <div class="py-3">
                <div class="action-button-lg-row">
                    <a href="{{ url('return-cheques') }}" class="grey-action-btn-lg" style="text-decoration: none;">
                        Back
                    </a>
                </div>
            </div>
        </div>


    </div>
</div>







<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const toggleButton = document.getElementById("sidebarToggle");
        const sidebarLinks = document.querySelectorAll(".sidebar-list li a");

        // Toggle sidebar visibility
        if (toggleButton) {
            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");
            });
        }

        // Set active class on navigation
        const currentPath = window.location.pathname;

        sidebarLinks.forEach((link) => {
            const href = link.getAttribute("href");

            if (href && currentPath.endsWith(href.replace("../", ""))) {
                link.parentElement.classList.add("active");
            } else {
                link.parentElement.classList.remove("active");
            }
        });

        // Handle dropdown functionality
        const dropdownItems = document.querySelectorAll(
            ".sidebar-list li a .dropdown-arrow"
        );
        dropdownItems.forEach((arrow) => {
            arrow.parentElement.addEventListener("click", function(e) {
                e.preventDefault();
                const listItem = this.parentElement;
                listItem.classList.toggle("expanded");
            });
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

<script>
    document.querySelectorAll('.selectable-filter').forEach(function(tag) {
        tag.addEventListener('click', function() {
            tag.classList.toggle('selected');
        });
    });
</script>