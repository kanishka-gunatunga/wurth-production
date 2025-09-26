@include('layouts.dashboard-header')
<?php
use App\Models\Divisions;
?>
<div class="container-fluid">
            <div class="main-wrapper">

                <div class="row d-flex justify-content-between">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">User Management</h1>
                    </div>
                    <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 pe-5">
                        <button class="header-btn"><i class="fa-solid fa-magnifying-glass fa-xl"></i></button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#searchByFilter" aria-controls="offcanvasRight"><i
                                class="fa-solid fa-filter fa-xl"></i></button>
                    </div>
                </div>


                <div class="styled-tab-main">
                    <ul class="nav">
                        <li class="nav-item mb-3">
                            <a class="nav-link active" aria-current="page" href="#">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M20.6094 4H4.60938C4.34416 4 4.0898 4.10536 3.90227 4.29289C3.71473 4.48043 3.60938 4.73478 3.60938 5V19C3.60938 19.2652 3.71473 19.5196 3.90227 19.7071C4.0898 19.8946 4.34416 20 4.60938 20H20.6094C20.8746 20 21.1289 19.8946 21.3165 19.7071C21.504 19.5196 21.6094 19.2652 21.6094 19V5C21.6094 4.73478 21.504 4.48043 21.3165 4.29289C21.1289 4.10536 20.8746 4 20.6094 4ZM4.60938 2C3.81373 2 3.05066 2.31607 2.48805 2.87868C1.92545 3.44129 1.60938 4.20435 1.60938 5V19C1.60938 19.7956 1.92545 20.5587 2.48805 21.1213C3.05066 21.6839 3.81373 22 4.60938 22H20.6094C21.405 22 22.1681 21.6839 22.7307 21.1213C23.2933 20.5587 23.6094 19.7956 23.6094 19V5C23.6094 4.20435 23.2933 3.44129 22.7307 2.87868C22.1681 2.31607 21.405 2 20.6094 2H4.60938ZM6.60938 7H8.60938V9H6.60938V7ZM11.6094 7C11.3442 7 11.0898 7.10536 10.9023 7.29289C10.7147 7.48043 10.6094 7.73478 10.6094 8C10.6094 8.26522 10.7147 8.51957 10.9023 8.70711C11.0898 8.89464 11.3442 9 11.6094 9H17.6094C17.8746 9 18.1289 8.89464 18.3165 8.70711C18.504 8.51957 18.6094 8.26522 18.6094 8C18.6094 7.73478 18.504 7.48043 18.3165 7.29289C18.1289 7.10536 17.8746 7 17.6094 7H11.6094ZM8.60938 11H6.60938V13H8.60938V11ZM10.6094 12C10.6094 11.7348 10.7147 11.4804 10.9023 11.2929C11.0898 11.1054 11.3442 11 11.6094 11H17.6094C17.8746 11 18.1289 11.1054 18.3165 11.2929C18.504 11.4804 18.6094 11.7348 18.6094 12C18.6094 12.2652 18.504 12.5196 18.3165 12.7071C18.1289 12.8946 17.8746 13 17.6094 13H11.6094C11.3442 13 11.0898 12.8946 10.9023 12.7071C10.7147 12.5196 10.6094 12.2652 10.6094 12ZM8.60938 15H6.60938V17H8.60938V15ZM10.6094 16C10.6094 15.7348 10.7147 15.4804 10.9023 15.2929C11.0898 15.1054 11.3442 15 11.6094 15H17.6094C17.8746 15 18.1289 15.1054 18.3165 15.2929C18.504 15.4804 18.6094 15.7348 18.6094 16C18.6094 16.2652 18.504 16.5196 18.3165 16.7071C18.1289 16.8946 17.8746 17 17.6094 17H11.6094C11.3442 17 11.0898 16.8946 10.9023 16.7071C10.7147 16.5196 10.6094 16.2652 10.6094 16Z"
                                        fill="#ED2128" />
                                </svg>

                                Users List
                            </a>
                        </li>
                    </ul>
                    <div class="col-12 d-flex justify-content-end pe-5 mb-3">
                        <a href="{{url('add-new-user')}}">
                            <button class="add-new-division-btn">+ Add New User</button>
                        </a>
                    </div>
                    @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                    @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif
                    <div class="table-responsive division-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">ADM Number</th>
                                    <th scope="col">User Role</th>
                                    <th scope="col">Division</th>
                                    <th scope="col">Mobile Number</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user){ 
                                 if($user->user_role == '1'){
                                    $role = 'System Administrator';
                                 }   
                                 if($user->user_role == '2'){
                                    $role = 'Head of Division';
                                 } 
                                 if($user->user_role == '3'){
                                    $role = 'Regional Sales Manager';
                                 } 
                                 if($user->user_role == '4'){
                                    $role = 'Area Sales Manager';
                                 } 
                                 if($user->user_role == '5'){
                                    $role = 'Team Leader';
                                 } 
                                 if($user->user_role == '6'){
                                    $role = 'ADM (Sales Rep)';
                                 } 
                                 if($user->user_role == '7'){
                                    $role = 'Finance Manager';
                                 } 
                                 $division = Divisions::where('id', $user->userDetails->division)->value('division_name');
                                ?> 
                                <tr>
                                    <td>{{ $user->userDetails->name ?? '-' }}</td>
                                    <td>{{ $user->userDetails->adm_number ?? '-' }}</td>
                                    <td>{{$role}}</td>
                                    <td>{{ $division ?? '-' }}</td>
                                    <td>{{ $user->userDetails->phone_number ?? '-' }}</td>
                                    <td>
                                        <a href="{{url('edit-user/'.$user->id.'')}}"><button class="action-btn">View more</button></a>
                                        <a href="{{url('activate-user/'.$user->id.'')}}"><button class="action-btn">Activate</button></a>
                                        <a href="{{url('deactivate-user/'.$user->id.'')}}"><button class="action-btn">Deactivate</button></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-12 d-flex justify-content-center laravel-pagination">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
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
                <div class="col-4 filter-tag d-flex align-items-center justify-content-between">
                    <span>ADMs</span>
                    <button class="btn btn-sm p-0"><i class="fa-solid fa-xmark fa-lg"></i></button>
                </div>

                <div class="col-4 filter-tag d-flex align-items-center justify-content-between">
                    <span>Marketing</span>
                    <button class="btn btn-sm p-0"><i class="fa-solid fa-xmark fa-lg"></i></button>
                </div>

                <div class="col-4 filter-tag d-flex align-items-center justify-content-between">
                    <span>Admin</span>
                    <button class="btn btn-sm p-0"><i class="fa-solid fa-xmark fa-lg"></i></button>
                </div>

                <div class="col-4 filter-tag d-flex align-items-center justify-content-between">
                    <span>Finance</span>
                    <button class="btn btn-sm p-0"><i class="fa-solid fa-xmark fa-lg"></i></button>
                </div>

                <div class="col-4 filter-tag d-flex align-items-center justify-content-between">
                    <span>Team Leaders</span>
                    <button class="btn btn-sm p-0"><i class="fa-solid fa-xmark fa-lg"></i></button>
                </div>

                <div class="col-4 filter-tag d-flex align-items-center justify-content-between">
                    <span>Head of Division</span>
                    <button class="btn btn-sm p-0"><i class="fa-solid fa-xmark fa-lg"></i></button>
                </div>
            </div>


            <div class="mt-5 filter-categories">
                <p class="filter-title">AMD Number</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        5643678
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        5643678
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        5643678
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        5643678
                    </label>
                </div>


                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        5643678
                    </label>
                </div>
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
                <p class="filter-title">User Role</p>
                

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        All
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        System Administration
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Head of Division
                    </label>
                </div>


                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Regional Sales Managers
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Area Sales Managers
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Team  Leaders
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        ADMs
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Finance Department Managers
                    </label>
                </div>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Today
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Yesterday
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        This Week
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        This Month
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        This Year
                    </label>
                </div>
            </div>


            <div class="mt-5 filter-categories">
                <p class="filter-title">Time</p>
                

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                       09:00 AM - 1:00 PM
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                       09:00 AM - 1:00 PM
                    </label>
                </div>


                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                       09:00 AM - 1:00 PM
                    </label>
                </div>


                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                       09:00 AM - 1:00 PM
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                       09:00 AM - 1:00 PM
                    </label>
                </div>
            </div>
</body>

</html>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');

        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    });
</script>