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
</style>
<div class="container-fluid">
    <div class="main-wrapper">

        <div class="row d-flex justify-content-between">
            <div class="col-lg-6 col-12">
                <h1 class="header-title">Customers</h1>
            </div>
          
        </div>


        <div class="styled-tab-main">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item mb-3" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#customer-list" role="tab"
                        aria-controls="customer-list" aria-selected="true">
                        <svg width="23" height="21" viewBox="0 0 23 21" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M19.5 2.01465H3.5C3.23478 2.01465 2.98043 2.12001 2.79289 2.30754C2.60536 2.49508 2.5 2.74943 2.5 3.01465V17.0146C2.5 17.2799 2.60536 17.5342 2.79289 17.7218C2.98043 17.9093 3.23478 18.0146 3.5 18.0146H19.5C19.7652 18.0146 20.0196 17.9093 20.2071 17.7218C20.3946 17.5342 20.5 17.2799 20.5 17.0146V3.01465C20.5 2.74943 20.3946 2.49508 20.2071 2.30754C20.0196 2.12001 19.7652 2.01465 19.5 2.01465ZM3.5 0.0146484C2.70435 0.0146484 1.94129 0.330719 1.37868 0.893328C0.81607 1.45594 0.5 2.219 0.5 3.01465V17.0146C0.5 17.8103 0.81607 18.5734 1.37868 19.136C1.94129 19.6986 2.70435 20.0146 3.5 20.0146H19.5C20.2956 20.0146 21.0587 19.6986 21.6213 19.136C22.1839 18.5734 22.5 17.8103 22.5 17.0146V3.01465C22.5 2.219 22.1839 1.45594 21.6213 0.893328C21.0587 0.330719 20.2956 0.0146484 19.5 0.0146484H3.5ZM5.5 5.01465H7.5V7.01465H5.5V5.01465ZM10.5 5.01465C10.2348 5.01465 9.98043 5.12001 9.79289 5.30754C9.60536 5.49508 9.5 5.74943 9.5 6.01465C9.5 6.27986 9.60536 6.53422 9.79289 6.72176C9.98043 6.90929 10.2348 7.01465 10.5 7.01465H16.5C16.7652 7.01465 17.0196 6.90929 17.2071 6.72176C17.3946 6.53422 17.5 6.27986 17.5 6.01465C17.5 5.74943 17.3946 5.49508 17.2071 5.30754C17.0196 5.12001 16.7652 5.01465 16.5 5.01465H10.5ZM7.5 9.01465H5.5V11.0146H7.5V9.01465ZM9.5 10.0146C9.5 9.74943 9.60536 9.49508 9.79289 9.30754C9.98043 9.12001 10.2348 9.01465 10.5 9.01465H16.5C16.7652 9.01465 17.0196 9.12001 17.2071 9.30754C17.3946 9.49508 17.5 9.74943 17.5 10.0146C17.5 10.2799 17.3946 10.5342 17.2071 10.7218C17.0196 10.9093 16.7652 11.0146 16.5 11.0146H10.5C10.2348 11.0146 9.98043 10.9093 9.79289 10.7218C9.60536 10.5342 9.5 10.2799 9.5 10.0146ZM7.5 13.0146H5.5V15.0146H7.5V13.0146ZM9.5 14.0146C9.5 13.7494 9.60536 13.4951 9.79289 13.3075C9.98043 13.12 10.2348 13.0146 10.5 13.0146H16.5C16.7652 13.0146 17.0196 13.12 17.2071 13.3075C17.3946 13.4951 17.5 13.7494 17.5 14.0146C17.5 14.2799 17.3946 14.5342 17.2071 14.7218C17.0196 14.9093 16.7652 15.0146 16.5 15.0146H10.5C10.2348 15.0146 9.98043 14.9093 9.79289 14.7218C9.60536 14.5342 9.5 14.2799 9.5 14.0146Z"
                                fill="#CC0000" />
                        </svg>

                        Customers List
                    </a>
                </li>

                <li class="nav-item mb-3" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#temporary" role="tab"
                        aria-controls="temporary" aria-selected="false">
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18 12.0146C18.8281 12.0146 19.6055 12.1709 20.332 12.4834C21.0586 12.7959 21.6953 13.2217 22.2422 13.7607C22.7891 14.2998 23.2188 14.9365 23.5312 15.6709C23.8438 16.4053 24 17.1865 24 18.0146C24 18.8428 23.8438 19.6201 23.5312 20.3467C23.2188 21.0732 22.793 21.71 22.2539 22.2568C21.7148 22.8037 21.0781 23.2334 20.3438 23.5459C19.6094 23.8584 18.8281 24.0146 18 24.0146C17.1719 24.0146 16.3945 23.8584 15.668 23.5459C14.9414 23.2334 14.3047 22.8076 13.7578 22.2686C13.2109 21.7295 12.7812 21.0928 12.4688 20.3584C12.1562 19.624 12 18.8428 12 18.0146C12 17.1865 12.1562 16.4092 12.4688 15.6826C12.7812 14.9561 13.207 14.3193 13.7461 13.7725C14.2852 13.2256 14.9219 12.7959 15.6562 12.4834C16.3906 12.1709 17.1719 12.0146 18 12.0146ZM18 22.5146C18.6172 22.5146 19.1992 22.3975 19.7461 22.1631C20.293 21.9287 20.7695 21.6084 21.1758 21.2021C21.582 20.7959 21.9062 20.3193 22.1484 19.7725C22.3906 19.2256 22.5078 18.6396 22.5 18.0146C22.5 17.3975 22.3828 16.8154 22.1484 16.2686C21.9141 15.7217 21.5938 15.2451 21.1875 14.8389C20.7812 14.4326 20.3008 14.1084 19.7461 13.8662C19.1914 13.624 18.6094 13.5068 18 13.5146C17.375 13.5146 16.793 13.6318 16.2539 13.8662C15.7148 14.1006 15.2383 14.4209 14.8242 14.8271C14.4102 15.2334 14.0859 15.7139 13.8516 16.2686C13.6172 16.8232 13.5 17.4053 13.5 18.0146C13.5 18.6396 13.6172 19.2217 13.8516 19.7607C14.0859 20.2998 14.4062 20.7764 14.8125 21.1904C15.2188 21.6045 15.6953 21.9287 16.2422 22.1631C16.7891 22.3975 17.375 22.5146 18 22.5146ZM18 18.0146H20.25V19.5146H16.5V15.0146H18V18.0146ZM13.7109 11.874C13.4922 12.0303 13.2852 12.1943 13.0898 12.3662C12.8945 12.5381 12.707 12.7217 12.5273 12.917C11.9883 12.6279 11.4219 12.4053 10.8281 12.249C10.2344 12.0928 9.625 12.0146 9 12.0146C8.3125 12.0146 7.64844 12.1045 7.00781 12.2842C6.36719 12.4639 5.76953 12.7139 5.21484 13.0342C4.66016 13.3545 4.15625 13.7451 3.70312 14.2061C3.25 14.667 2.85938 15.1748 2.53125 15.7295C2.20312 16.2842 1.94922 16.8818 1.76953 17.5225C1.58984 18.1631 1.5 18.8271 1.5 19.5146H0C0 18.5771 0.136719 17.6748 0.410156 16.8076C0.683594 15.9404 1.07812 15.1396 1.59375 14.4053C2.10938 13.6709 2.71875 13.0186 3.42188 12.4482C4.125 11.8779 4.92188 11.4287 5.8125 11.1006C4.92969 10.5225 4.24219 9.7959 3.75 8.9209C3.25781 8.0459 3.00781 7.07715 3 6.01465C3 5.18652 3.15625 4.40918 3.46875 3.68262C3.78125 2.95605 4.20703 2.31934 4.74609 1.77246C5.28516 1.22559 5.92188 0.795898 6.65625 0.483398C7.39062 0.170898 8.17188 0.0146484 9 0.0146484C9.82812 0.0146484 10.6055 0.170898 11.332 0.483398C12.0586 0.795898 12.6953 1.22168 13.2422 1.76074C13.7891 2.2998 14.2188 2.93652 14.5312 3.6709C14.8438 4.40527 15 5.18652 15 6.01465C15 6.53027 14.9375 7.03418 14.8125 7.52637C14.6875 8.01855 14.5 8.4834 14.25 8.9209C14 9.3584 13.7031 9.76465 13.3594 10.1396C13.0156 10.5146 12.6211 10.835 12.1758 11.1006C12.7227 11.3193 13.2344 11.5771 13.7109 11.874ZM4.5 6.01465C4.5 6.63965 4.61719 7.22168 4.85156 7.76074C5.08594 8.2998 5.40625 8.77637 5.8125 9.19043C6.21875 9.60449 6.69531 9.92871 7.24219 10.1631C7.78906 10.3975 8.375 10.5146 9 10.5146C9.61719 10.5146 10.1992 10.3975 10.7461 10.1631C11.293 9.92871 11.7695 9.6084 12.1758 9.20215C12.582 8.7959 12.9062 8.31934 13.1484 7.77246C13.3906 7.22559 13.5078 6.63965 13.5 6.01465C13.5 5.39746 13.3828 4.81543 13.1484 4.26855C12.9141 3.72168 12.5938 3.24512 12.1875 2.83887C11.7812 2.43262 11.3008 2.1084 10.7461 1.86621C10.1914 1.62402 9.60938 1.50684 9 1.51465C8.375 1.51465 7.79297 1.63184 7.25391 1.86621C6.71484 2.10059 6.23828 2.4209 5.82422 2.82715C5.41016 3.2334 5.08594 3.71387 4.85156 4.26855C4.61719 4.82324 4.5 5.40527 4.5 6.01465Z"
                                fill="#CC0000" />
                        </svg>

                        Temporary Customers
                    </a>
                </li>
            </ul>



            <div class="tab-content">
                 
                <!-- Customers List Tab Pane -->
                <div id="customer-list" class="tab-pane fade show active" role="tabpanel"
                    aria-labelledby="customer-list-tab">
                     <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <div id="tr-search-box-wrapper" class="search-box-wrapper collapsed">
                            <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                            <form method="GET" action="{{ url('customers') }}" id="mainSearchForm">
                                 <input type="hidden" name="active_tab" value="customer-list">
                                <input 
                                    type="text" 
                                    class="search-input" 
                                    name="search"
                                    placeholder="Search Customer ID, Name, Email, Mobile Number"
                                    value="{{ request('search') }}"
                                />
                            </form>
                        </div>
                        <button class="header-btn" id="tr-search-toggle-button">
                            <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                        </button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#cusomerFilter">
                            <i class="fa-solid fa-filter fa-xl"></i>
                        </button>
                    </div>
                </div>    
                    <div class="col-12 d-flex justify-content-end pe-5 mb-5">
                        <a href="{{url('add-new-customer')}}">
                            <button class="add-new-division-btn">+ Add New Customer</button>
                        </a>
                    </div>
                    @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                    @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif
                    <div class="table-responsive division-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Customer ID</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Mobile Number</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($customers as $customer) {
                                ?>

                                    <tr>
                                        <td>{{$customer->customer_id}}</th>
                                        <td>{{$customer->name}}</td>
                                        <td>{{$customer->address}}</td>
                                        <td>{{$customer->mobile_number}}</td>
                                        <td>{{$customer->email}}</td>
                                        <td>
                                            <a href="{{url('view-customer/'.$customer->id.'')}}"><button class="action-btn">View More</button></a>
                                          
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 d-flex justify-content-center laravel-pagination">
                       {{ $customers->appends(['active_tab' => 'customer-list'])->links('pagination::bootstrap-5') }}
                    </div>
                </div>

                <!-- Temporary Customers Tab Pane -->
                <div id="temporary" class="tab-pane fade" role="tabpanel" aria-labelledby="temporary-tab">
<div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <div id="final-search-box-wrapper" class="search-box-wrapper collapsed">
                            <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                           <form method="GET" action="{{ url('customers') }}" id="mainSearchForm2">
                            <input type="hidden" name="active_tab" value="temporary">
                                <input 
                                    type="text" 
                                    class="search-input" 
                                    name="temp_search"
                                    placeholder="Search Customer ID, Name, Email, Mobile Number"
                                    value="{{ request('temp_search') }}"
                                />
                            </form>
                        </div>
                        <button class="header-btn" id="final-search-toggle-button">
                            <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                        </button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#tempCusomerFilter">
                            <i class="fa-solid fa-filter fa-xl"></i>
                        </button>
                    </div>
                </div>
                
                    <div class="d-flex justify-content-center">
                        <div class="col-7 mt-5">
                            <div class="table-responsive division-table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">ADM Name</th>
                                            <th scope="col">ADM Number</th>
                                            <th scope="col">Customer ID</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($temp_customers as $temp_customer) {

                                        ?>
                                            <tr>
                                                <td>{{UserDetails::where('adm_number', $temp_customer->adm)->value('name')}}</th>
                                                <td>{{$temp_customer->adm}}</th>
                                                <td>{{$temp_customer->customer_id}}</th>
                                                <td>
                                                    <a href="{{url('edit-customer/'.$temp_customer->id.'')}}"><button class="action-btn">Edit</button></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 d-flex justify-content-center laravel-pagination">
                               {{ $temp_customers->appends(['active_tab' => 'temporary'])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>



    </div>


</div>

</div>


<div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="cusomerFilter"
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
          <a href="{{url('customers')}}"><button class="btn rounded-phill">Clear All</button></a>
        </div>
    </div>
     <form method="GET" action="{{ url('customers') }}" id="filterForm">
        <input type="hidden" name="active_tab" value="customer-list">
    <div class="offcanvas-body">
      


        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM Number</p>
            @foreach($adms as $adm)
                <div class="form-check custom-circle-checkbox">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="adm{{ $adm->id }}" 
                        name="adm[]" 
                        value="{{ $adm->userDetails->adm_number }}"
                        {{ in_array( $adm->userDetails->adm_number, $selectedAdms ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="adm{{ $adm->id }}">
                        {{ $adm->userDetails->adm_number }}
                    </label>
                </div>
            @endforeach

            <button type="submit" class="red-action-btn-lg mt-4">Apply Filter</button>
        </div>
        </form>
    </div>
 </div>


<div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="tempCusomerFilter"
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
          <a href="{{url('customers')}}"><button class="btn rounded-phill">Clear All</button></a>
        </div>
    </div>
    <form method="GET" action="{{ url('customers') }}" id="tempFilterForm">
        <input type="hidden" name="active_tab" value="temporary">
    <div class="offcanvas-body">
      


        <div class="mt-5 filter-categories">
            <p class="filter-title">ADM Number</p>
            @foreach($adms as $adm)
                <div class="form-check custom-circle-checkbox">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="adm{{ $adm->id }}" 
                        name="adm[]" 
                        value="{{ $adm->userDetails->adm_number }}"
                        {{ in_array( $adm->userDetails->adm_number, $selectedAdms ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="adm{{ $adm->id }}">
                        {{ $adm->userDetails->adm_number }}
                    </label>
                </div>
            @endforeach

            <button type="submit" class="red-action-btn-lg mt-4">Apply Filter</button>
        </div>
        </form>
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