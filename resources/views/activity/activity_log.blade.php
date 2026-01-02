@include('layouts.dashboard-header')
<?php
use App\Models\Divisions;
?>

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

                <div class="row d-flex justify-content-between">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">Activity Log</h1>
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 ">
                        <div id="search-box-wrapper" class="collapsed">
                            <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                           <form method="GET" action="{{ url('activity-log') }}" id="mainSearchForm">
                                <input 
                                    type="text" 
                                    class="search-input" 
                                    name="search"
                                    placeholder="Search ADM Number, User Name, Activity Type"
                                    value="{{ request('search') }}"
                                />
                            </form>
                        </div>
                        <button class="header-btn" id="search-toggle-button"><i
                                class="fa-solid fa-magnifying-glass fa-xl"></i></button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter"
                            aria-controls="offcanvasRight"><i class="fa-solid fa-filter fa-xl"></i></button>
                    </div>
                </div>
         <hr class="red-line mt-0">
                <div class="table-responsive division-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">ADM Number</th>
                                <th scope="col">User Name</th>
                                <th scope="col">Activity Type</th>
                                <th scope="col">Changes</th>
                            </tr>
                        </thead>
                        <tbody >
                          @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('Y-m-d') }}</td>
                                <td>{{ $log->created_at->format('H:i:s') }}</td>
                                <td>{{ $log->userData->userDetails->adm_number ?? '-' }}</td>
                                <td>{{ $log->userData->userDetails->name ?? '-' }}</td>
                                <td>{{ $log->activity_type }}</td>
                                <td>{{ $log->changes }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                        <nav class="d-flex justify-content-center mt-5">
                             {{ $logs->links('pagination::bootstrap-5') }}
                        </nav>

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
            <a href="{{url('user-managment')}}"><button class="btn rounded-phill">Clear All</button></a>
        </div>
    </div>
   <form action="{{ url('activity-log') }}" method="GET" id="filterForm">
    <div class="offcanvas-body">
        <p class="filter-title">User Roles</p>

        <div class="row" id="roleFilterContainer">
            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(1, $selectedRoles ?? []) ? 'active' : '' }}" data-role="1">
                <span>System Administrator</span>
            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(2, $selectedRoles ?? []) ? 'active' : '' }}" data-role="2">
                <span>Head of Division</span>
            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(3, $selectedRoles ?? []) ? 'active' : '' }}" data-role="3">
                <span>Regional Sales Manager</span>
            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(4, $selectedRoles ?? []) ? 'active' : '' }}" data-role="4">
                <span>Area Sales Manager</span>
            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(5, $selectedRoles ?? []) ? 'active' : '' }}" data-role="5">
                <span>Team Leader</span>
            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(6, $selectedRoles ?? []) ? 'active' : '' }}" data-role="6">
                <span>ADM (Sales Rep)</span>
            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(7, $selectedRoles ?? []) ? 'active' : '' }}" data-role="7">
                <span>Finance Manager</span>
            </div>
        </div>

       {{-- 🗓️ Date Filters --}}
        <p class="filter-title mt-4">Date</p>

        @php
            $selectedDates = request()->input('date_period', []);
            $selectedTimes = request()->input('time_period', []);
        @endphp

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="date1"
                name="date_period[]" value="today"
                {{ in_array('today', $selectedDates) ? 'checked' : '' }}>
            <label class="form-check-label" for="date1">Today</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="date2"
                name="date_period[]" value="yesterday"
                {{ in_array('yesterday', $selectedDates) ? 'checked' : '' }}>
            <label class="form-check-label" for="date2">Yesterday</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="date3"
                name="date_period[]" value="this_week"
                {{ in_array('this_week', $selectedDates) ? 'checked' : '' }}>
            <label class="form-check-label" for="date3">This Week</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="date4"
                name="date_period[]" value="this_month"
                {{ in_array('this_month', $selectedDates) ? 'checked' : '' }}>
            <label class="form-check-label" for="date4">This Month</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="date5"
                name="date_period[]" value="this_year"
                {{ in_array('this_year', $selectedDates) ? 'checked' : '' }}>
            <label class="form-check-label" for="date5">This Year</label>
        </div>


        {{-- ⏰ Time Filters --}}
        <p class="filter-title mt-4">Time</p>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="time1"
                name="time_period[]" value="9am-1pm"
                {{ in_array('9am-1pm', $selectedTimes) ? 'checked' : '' }}>
            <label class="form-check-label" for="time1">9:00 AM - 1:00 PM</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="time2"
                name="time_period[]" value="1pm-5pm"
                {{ in_array('1pm-5pm', $selectedTimes) ? 'checked' : '' }}>
            <label class="form-check-label" for="time2">1:00 PM - 5:00 PM</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="time3"
                name="time_period[]" value="5pm-9pm"
                {{ in_array('5pm-9pm', $selectedTimes) ? 'checked' : '' }}>
            <label class="form-check-label" for="time3">5:00 PM - 9:00 PM</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="time4"
                name="time_period[]" value="9pm-1am"
                {{ in_array('9pm-1am', $selectedTimes) ? 'checked' : '' }}>
            <label class="form-check-label" for="time4">9:00 PM - 1:00 AM</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="time5"
                name="time_period[]" value="1am-5am"
                {{ in_array('1am-5am', $selectedTimes) ? 'checked' : '' }}>
            <label class="form-check-label" for="time5">1:00 AM - 5:00 AM</label>
        </div>

        <div class="form-check custom-circle-checkbox">
            <input class="form-check-input" type="checkbox" id="time6"
                name="time_period[]" value="5am-9am"
                {{ in_array('5am-9am', $selectedTimes) ? 'checked' : '' }}>
            <label class="form-check-label" for="time6">5:00 AM - 9:00 AM</label>
        </div>


        <input type="hidden" name="roles" id="selectedRolesInput" value="{{ implode(',', $selectedRoles ?? []) }}">


            <button type="submit" class="red-action-btn-lg mt-4">Apply Filter</button>
      
        </form>
    </div>

</body>

</html>

@include('layouts.footer2')


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




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
document.addEventListener("DOMContentLoaded", function() {
    const roleTags = document.querySelectorAll(".selectable-filter");
    const hiddenInput = document.getElementById("selectedRolesInput");
    const filterForm = document.getElementById("filterForm");
    let selectedRoles = hiddenInput.value ? hiddenInput.value.split(",").map(Number) : [];

    roleTags.forEach(tag => {
        tag.addEventListener("click", function() {
            const roleId = parseInt(this.dataset.role);

            if (selectedRoles.includes(roleId)) {
                selectedRoles = selectedRoles.filter(id => id !== roleId);
                this.classList.remove("active");
            } else {
                selectedRoles.push(roleId);
                this.classList.add("active");
            }

            hiddenInput.value = selectedRoles.join(",");
        });
    });

    // Submit form when "Apply Filter" is clicked
    filterForm.addEventListener("submit", function(e) {
        // Remove any empty hidden values before submitting
        if (!hiddenInput.value) hiddenInput.remove();
    });
});

// Search submit on Enter
function searchUsers(event) {
    if (event.key === "Enter") {
        document.getElementById("mainSearchForm").submit();
    }
}
</script>

