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

<div class="main-wrapper">

    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Locked Users</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
            <div id="search-box-wrapper" class="collapsed">
                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                <form method="GET" action="{{ url('locked-users') }}" id="mainSearchForm" class="w-100">
                <input 
                    type="text" 
                    class="search-input" 
                    name="search"
                    placeholder="Search Full Name, User ID"
                    value="{{ request('search') }}"
                    onkeydown="searchUsers(event)"
                />
            </form>
            </div>
            <button class="header-btn" id="search-toggle-button"><i
                    class="fa-solid fa-magnifying-glass fa-xl"></i></button>
        </div>
    </div>

    <hr class="red-line mt-0">

    <div class="section-card locked-users-card">

        <div class="table-responsive division-table-sub">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date <i class="sort-icon">▼</i></th>
                        <th>Full Name</th>
                        <th>User ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
               <tbody>
                @forelse ($locked_users as $user)
                    <tr>
                        <td>{{ $user->updated_at->format('d M Y') }}</td>
                        <td>{{ $user->userDetails->name }}</td>
                        <td>{{ $user->id }}</td>
                        <td>
                             @if(in_array('security-locked-unlock', session('permissions', [])))
                                <a href="{{ url('unlock-user/'.$user->id) }}"><button class="btn unlock-btn">Unlock</button></a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No locked users found.</td>
                    </tr>
                @endforelse
            </tbody>

            </table>
        </div>
        <nav class="d-flex justify-content-center mt-5">
                             {{ $locked_users->links('pagination::bootstrap-5') }}
                        </nav>

    </div>
</div>

@include('layouts.footer2')
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
            function searchUsers(event) {
    if (event.key === "Enter") {
        document.getElementById("mainSearchForm").submit();
    }
}
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
</script>