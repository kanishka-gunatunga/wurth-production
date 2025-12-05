@include('layouts.dashboard-header')
<?php

use App\Models\UserDetails;
?>

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
        width: 0;
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

    /* Checkbox styling (for advance payment tab) */
    .form-check-input {
        height: 20px;
        width: 20px;
        border-color: #D2D5DA;
        margin-right: 15px;
    }

    .form-check-input:focus {
        border-color: #dc3545 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2.1px #dc354533 !important;
    }

    .form-check-input:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .form-check-label {
        font-family: "Inter", sans-serif;
        font-size: 20px;
        font-weight: 400;
    }

    .profile-section {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .upload-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .profile-wrapper {
        position: relative;
        width: 90px;
        height: 90px;
    }

    .profile-pic {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #D0D0D0;
    }

    .delete-icon {
        position: absolute;
        bottom: 0px;
        right: -3px;
        padding: 4px;
        cursor: pointer;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .upload-btn {
        background-color: #b91c1c;
        transition: background-color 0.2s;
    }

    .upload-btn:hover {
        background-color: #991b1b;
    }

    .outside-label {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }
</style>

<div class="main-wrapper">
    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Settings</h1>
        </div>
    </div>

    <div class="styled-tab-main">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item mb-3" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#customer-list" role="tab"
                    aria-controls="customer-list" aria-selected="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                        <path d="M13.646 10.7155C14.6264 9.94415 15.342 8.88642 15.6933 7.68944C16.0445 6.49246 16.014 5.21576 15.6058 4.03696C15.1977 2.85817 14.4323 1.83589 13.4161 1.11235C12.3999 0.388815 11.1835 0 9.93603 0C8.68858 0 7.47215 0.388815 6.45596 1.11235C5.43978 1.83589 4.67438 2.85817 4.26624 4.03696C3.85811 5.21576 3.82754 6.49246 4.17879 7.68944C4.53004 8.88642 5.24564 9.94415 6.22603 10.7155C4.54611 11.3885 3.08032 12.5048 1.98492 13.9454C0.88953 15.386 0.205595 17.0968 0.00603184 18.8955C-0.00841357 19.0268 0.00314838 19.1597 0.0400573 19.2866C0.0769662 19.4134 0.138499 19.5317 0.221143 19.6348C0.388051 19.843 0.630815 19.9763 0.896032 20.0055C1.16125 20.0347 1.42719 19.9573 1.63536 19.7904C1.84352 19.6235 1.97686 19.3807 2.00603 19.1155C2.22562 17.1607 3.15772 15.3553 4.62425 14.0443C6.09078 12.7333 7.98893 12.0085 9.95603 12.0085C11.9231 12.0085 13.8213 12.7333 15.2878 14.0443C16.7543 15.3553 17.6864 17.1607 17.906 19.1155C17.9332 19.3612 18.0505 19.5882 18.2351 19.7525C18.4198 19.9169 18.6588 20.007 18.906 20.0055H19.016C19.2782 19.9753 19.5178 19.8428 19.6826 19.6367C19.8474 19.4307 19.9241 19.1679 19.896 18.9055C19.6955 17.1017 19.0079 15.3865 17.9069 13.9437C16.8059 12.5009 15.3329 11.385 13.646 10.7155ZM9.93603 10.0055C9.14491 10.0055 8.37155 9.7709 7.71375 9.33137C7.05595 8.89185 6.54326 8.26713 6.24051 7.53623C5.93776 6.80533 5.85855 6.00106 6.01289 5.22513C6.16723 4.44921 6.54819 3.73648 7.1076 3.17707C7.66701 2.61766 8.37975 2.2367 9.15567 2.08235C9.93159 1.92801 10.7359 2.00723 11.4668 2.30998C12.1977 2.61273 12.8224 3.12542 13.2619 3.78321C13.7014 4.44101 13.936 5.21437 13.936 6.0055C13.936 7.06636 13.5146 8.08378 12.7645 8.83392C12.0143 9.58407 10.9969 10.0055 9.93603 10.0055Z" fill="#CC0000" />
                    </svg>
                    User Profile
                </a>
            </li>

            <li class="nav-item mb-3" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#temporary" role="tab"
                    aria-controls="temporary" aria-selected="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="21" viewBox="0 0 16 21" fill="none">
                        <path d="M8 16C7.46957 16 6.96086 15.7893 6.58579 15.4142C6.21071 15.0391 6 14.5304 6 14C6 12.89 6.89 12 8 12C8.53043 12 9.03914 12.2107 9.41421 12.5858C9.78929 12.9609 10 13.4696 10 14C10 14.5304 9.78929 15.0391 9.41421 15.4142C9.03914 15.7893 8.53043 16 8 16ZM14 19V9H2V19H14ZM14 7C14.5304 7 15.0391 7.21071 15.4142 7.58579C15.7893 7.96086 16 8.46957 16 9V19C16 19.5304 15.7893 20.0391 15.4142 20.4142C15.0391 20.7893 14.5304 21 14 21H2C1.46957 21 0.960859 20.7893 0.585786 20.4142C0.210714 20.0391 0 19.5304 0 19V9C0 7.89 0.89 7 2 7H3V5C3 3.67392 3.52678 2.40215 4.46447 1.46447C5.40215 0.526784 6.67392 0 8 0C8.65661 0 9.30679 0.129329 9.91342 0.380602C10.52 0.631876 11.0712 1.00017 11.5355 1.46447C11.9998 1.92876 12.3681 2.47995 12.6194 3.08658C12.8707 3.69321 13 4.34339 13 5V7H14ZM8 2C7.20435 2 6.44129 2.31607 5.87868 2.87868C5.31607 3.44129 5 4.20435 5 5V7H11V5C11 4.20435 10.6839 3.44129 10.1213 2.87868C9.55871 2.31607 8.79565 2 8 2Z" fill="#CC0000" />
                    </svg>
                    Password Reset
                </a>
            </li>
        </ul>


        <div class="tab-content">
            <!-- Customers List Tab Pane -->
            <div id="customer-list" class="tab-pane fade show active" role="tabpanel"
                aria-labelledby="customer-list-tab">
                @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif

                <div class="mb-8">
                    <label for="division-input" class="form-label custom-input-label">
                        Profile Picture
                    </label>
                    <div class="profile-section">
                        <div class="profile-wrapper">
                            <img src="{{ $user->userDetails->profile_picture 
                ? asset('db_files/user_profile_images/' . $user->userDetails->profile_picture) 
                : asset('new-assets/images/upload.jpg') }}"
                                alt="Profile Picture"
                                class="profile-pic">
                            <div class="delete-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M16.1105 5.6875L15.4096 17.6024C15.3835 18.0481 15.188 18.467 14.8632 18.7732C14.5383 19.0795 14.1087 19.2501 13.6623 19.25H7.33775C6.89128 19.2501 6.46166 19.0795 6.13682 18.7732C5.81198 18.467 5.61649 18.0481 5.59037 17.6024L4.89125 5.6875H3.0625V4.8125C3.0625 4.69647 3.10859 4.58519 3.19064 4.50314C3.27269 4.42109 3.38397 4.375 3.5 4.375H17.5C17.616 4.375 17.7273 4.42109 17.8094 4.50314C17.8914 4.58519 17.9375 4.69647 17.9375 4.8125V5.6875H16.1105ZM8.75 2.1875H12.25C12.366 2.1875 12.4773 2.23359 12.5594 2.31564C12.6414 2.39769 12.6875 2.50897 12.6875 2.625V3.5H8.3125V2.625C8.3125 2.50897 8.35859 2.39769 8.44064 2.31564C8.52269 2.23359 8.63397 2.1875 8.75 2.1875ZM7.875 7.875L8.3125 15.75H9.625L9.275 7.875H7.875ZM11.8125 7.875L11.375 15.75H12.6875L13.125 7.875H11.8125Z"
                                        fill="#CC0000" />
                                </svg>
                            </div>
                        </div>

                        <div class="upload-container">
                            <button type="submit" class="btn btn-danger submit">Upload Photo</button>
                            <p class="outside-label">JPG, PNG or GIF (Max 5MB)</p>
                        </div>
                    </div>
                </div>


                <form action="{{ url('/settings') }}" method="post">
                    @csrf
                    <div class="row d-flex justify-content-between mt-4">
                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">
                                Full Name</label>
                            <input type="text" class="form-control custom-input" id="division-input" placeholder="Name" name="name" value="{{$user->userDetails->name}}">
                            @if($errors->has("name")) <div class="alert alert-danger mt-2">{{ $errors->first('name') }}</div>@endif
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">Email Address</label>
                            <input type="email" class="form-control custom-input" id="division-input" placeholder="Email" name="email" value="{{$user->email}}">
                            @if($errors->has("email")) <div class="alert alert-danger mt-2">{{ $errors->first('email') }}</div>@endif
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">Employee Number</label>
                            <input type="tel" class="form-control custom-input" id="division-input" placeholder="Employee Number" name="employee_number" value="{{$user->userDetails->user_id}}">
                            @if($errors->has("employee_number")) <div class="alert alert-danger mt-2">{{ $errors->first('user_id') }}</div>@endif
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">Mobile Number</label>
                            <input type="text" class="form-control custom-input" id="division-input" placeholder="Phone Number" name="phone_number" value="{{$user->userDetails->phone_number}}">
                            @if($errors->has("phone_number")) <div class="alert alert-danger mt-2">{{ $errors->first('phone_number') }}</div>@endif
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">Division</label>
                            <input type="text" class="form-control custom-input" id="division-input" placeholder="Division" name="division" value="{{$user->userDetails->division}}">
                            @if($errors->has("division")) <div class="alert alert-danger mt-2">{{ $errors->first('division') }}</div>@endif
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">Supervisor</label>
                            <input type="text" class="form-control custom-input" id="division-input" placeholder="Supervisor" name="supervisor" value="{{$user->userDetails->supervisor}}">
                            @if($errors->has("supervisor")) <div class="alert alert-danger mt-2">{{ $errors->first('supervisor') }}</div>@endif
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                        <button type="submit" class="btn btn-danger submit">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Temporary Customers Tab Pane -->
            <div id="temporary" class="tab-pane fade" role="tabpanel" aria-labelledby="temporary-tab">
                @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif

                <label for="division-input" class="form-label custom-input-label" style="font-weight: 600px;">
                    Reset Password
                </label>

                <form class="" action="" method="post">
                    @csrf
                    <div class="row d-flex justify-content-between mt-3">
                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">Current Password</label>
                            <input type="current_password" class="form-control custom-input" id="division-input" placeholder="Current Password" name="current_password">
                            @if($errors->has("password")) <div class="alert alert-danger mt-2">{{ $errors->first('current_password') }}</div>@endif
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">New Password</label>
                            <input type="password" class="form-control custom-input" id="division-input" placeholder="New Password" name="password">
                            @if($errors->has("password")) <div class="alert alert-danger mt-2">{{ $errors->first('password') }}</div>@endif
                            <p class="outside-label">Password must be at least 8 characters long</p>
                        </div>

                        <div class="mb-4 col-12 col-lg-6">
                            <label for="division-input" class="form-label custom-input-label">Confirm New Password</label>
                            <input type="password" class="form-control custom-input" id="division-input" placeholder="Confirm New Password" name="password_confirmation">
                            @if($errors->has("password_confirmation")) <div class="alert alert-danger mt-2">{{ $errors->first('password_confirmation') }}</div>@endif
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                        <button type="submit" class="btn btn-danger submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</body>

</html>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');

        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get active tab from URL
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('active_tab');

        if (activeTab) {
            // Activate that tab
            const triggerEl = document.querySelector(`a[href="#${activeTab}"]`);
            if (triggerEl) {
                const tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }

        // Save tab state when user clicks tabs
        const tabEls = document.querySelectorAll('a[data-bs-toggle="tab"]');
        tabEls.forEach((el) => {
            el.addEventListener('shown.bs.tab', function(event) {
                const newTab = event.target.getAttribute('href').substring(1);
                const url = new URL(window.location);
                url.searchParams.set('active_tab', newTab);
                history.replaceState(null, '', url);
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function setupSearch(wrapperId, toggleId) {
            const searchWrapper = document.getElementById(wrapperId);
            const searchToggleButton = document.getElementById(toggleId);
            const searchInput = searchWrapper.querySelector(".search-input");

            let idleTimeout;
            const idleTime = 5000;

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

            searchInput.addEventListener("keydown", function() {
                startIdleTimer();
            });
        }

        // Apply for each tab
        setupSearch("final-search-box-wrapper", "final-search-toggle-button");
        setupSearch("tr-search-box-wrapper", "tr-search-toggle-button");
        setupSearch("receipts-search-box-wrapper", "receipts-search-toggle-button");
    });

    function searchCustomers(val) {
        if (event.key === "Enter") {
            document.getElementById("mainSearchForm").submit();
        }
    }

    function searchTempCustomers(val) {
        if (event.key === "Enter") {
            document.getElementById("mainSearchForm2").submit();
        }
    }
</script>