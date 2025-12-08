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

                

                 <div class="styled-tab-main">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item mb-3">
                                <a class="nav-link active" data-bs-toggle="tab" href="#user-list" role="tab" aria-controls="user-list" aria-selected="true">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.6094 4H4.60938C4.34416 4 4.0898 4.10536 3.90227 4.29289C3.71473 4.48043 3.60938 4.73478 3.60938 5V19C3.60938 19.2652 3.71473 19.5196 3.90227 19.7071C4.0898 19.8946 4.34416 20 4.60938 20H20.6094C20.8746 20 21.1289 19.8946 21.3165 19.7071C21.504 19.5196 21.6094 19.2652 21.6094 19V5C21.6094 4.73478 21.504 4.48043 21.3165 4.29289C21.1289 4.10536 20.8746 4 20.6094 4ZM4.60938 2C3.81373 2 3.05066 2.31607 2.48805 2.87868C1.92545 3.44129 1.60938 4.20435 1.60938 5V19C1.60938 19.7956 1.92545 20.5587 2.48805 21.1213C3.05066 21.6839 3.81373 22 4.60938 22H20.6094C21.405 22 22.1681 21.6839 22.7307 21.1213C23.2933 20.5587 23.6094 19.7956 23.6094 19V5C23.6094 4.20435 23.2933 3.44129 22.7307 2.87868C22.1681 2.31607 21.405 2 20.6094 2H4.60938ZM6.60938 7H8.60938V9H6.60938V7ZM11.6094 7C11.3442 7 11.0898 7.10536 10.9023 7.29289C10.7147 7.48043 10.6094 7.73478 10.6094 8C10.6094 8.26522 10.7147 8.51957 10.9023 8.70711C11.0898 8.89464 11.3442 9 11.6094 9H17.6094C17.8746 9 18.1289 8.89464 18.3165 8.70711C18.504 8.51957 18.6094 8.26522 18.6094 8C18.6094 7.73478 18.504 7.48043 18.3165 7.29289C18.1289 7.10536 17.8746 7 17.6094 7H11.6094ZM8.60938 11H6.60938V13H8.60938V11ZM10.6094 12C10.6094 11.7348 10.7147 11.4804 10.9023 11.2929C11.0898 11.1054 11.3442 11 11.6094 11H17.6094C17.8746 11 18.1289 11.1054 18.3165 11.2929C18.504 11.4804 18.6094 11.7348 18.6094 12C18.6094 12.2652 18.504 12.5196 18.3165 12.7071C18.1289 12.8946 17.8746 13 17.6094 13H11.6094C11.3442 13 11.0898 12.8946 10.9023 12.7071C10.7147 12.5196 10.6094 12.2652 10.6094 12ZM8.60938 15H6.60938V17H8.60938V15ZM10.6094 16C10.6094 15.7348 10.7147 15.4804 10.9023 15.2929C11.0898 15.1054 11.3442 15 11.6094 15H17.6094C17.8746 15 18.1289 15.1054 18.3165 15.2929C18.504 15.4804 18.6094 15.7348 18.6094 16C18.6094 16.2652 18.504 16.5196 18.3165 16.7071C18.1289 16.8946 17.8746 17 17.6094 17H11.6094C11.3442 17 11.0898 16.8946 10.9023 16.7071C10.7147 16.5196 10.6094 16.2652 10.6094 16Z" fill="#CC0000"></path>
                                    </svg>

                                    Users List
                                </a>
                            </li>
                            <li class="nav-item mb-3">
                                <a class="nav-link" data-bs-toggle="tab" href="#switch-user" role="tab" aria-controls="switch-user" aria-selected="true">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M17 17C16.337 17 15.7011 16.7366 15.2322 16.2678C14.7634 15.7989 14.5 15.163 14.5 14.5C14.5 13.837 14.7634 13.2011 15.2322 12.7322C15.7011 12.2634 16.337 12 17 12C17.663 12 18.2989 12.2634 18.7678 12.7322C19.2366 13.2011 19.5 13.837 19.5 14.5C19.5 15.163 19.2366 15.7989 18.7678 16.2678C18.2989 16.7366 17.663 17 17 17ZM17 17C18.1935 17 19.3381 17.4741 20.182 18.318C21.0259 19.1619 21.5 20.3065 21.5 21.5M17 17C15.8065 17 14.6619 17.4741 13.818 18.318C12.9741 19.1619 12.5 20.3065 12.5 21.5M7 7.5C6.33696 7.5 5.70107 7.23661 5.23223 6.76777C4.76339 6.29893 4.5 5.66304 4.5 5C4.5 4.33696 4.76339 3.70107 5.23223 3.23223C5.70107 2.76339 6.33696 2.5 7 2.5C7.66304 2.5 8.29893 2.76339 8.76777 3.23223C9.23661 3.70107 9.5 4.33696 9.5 5C9.5 5.66304 9.23661 6.29893 8.76777 6.76777C8.29893 7.23661 7.66304 7.5 7 7.5ZM7 7.5C8.19347 7.5 9.33807 7.97411 10.182 8.81802C11.0259 9.66193 11.5 10.8065 11.5 12M7 7.5C5.80653 7.5 4.66193 7.97411 3.81802 8.81802C2.97411 9.66193 2.5 10.8065 2.5 12M3.5 15.5C3.5 18.264 5.736 20.5 8.5 20.5L8 18.5M18.5 8.5C18.5 5.736 16.264 3.5 13.5 3.5L14 5.5" stroke="#CC0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                   Switch Users
                                </a>
                            </li>
                            <li class="nav-item mb-3">
                                <a class="nav-link" data-bs-toggle="tab" href="#replace-user" role="tab" aria-controls="replace-user" aria-selected="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M14 4C14 3.73478 14.1054 3.48043 14.2929 3.29289C14.4804 3.10536 14.7348 3 15 3M15 10C14.7348 10 14.4804 9.89464 14.2929 9.70711C14.1054 9.51957 14 9.26522 14 9M21 4C21 3.73478 20.8946 3.48043 20.7071 3.29289C20.5196 3.10536 20.2652 3 20 3M21 9C21 9.26522 20.8946 9.51957 20.7071 9.70711C20.5196 9.89464 20.2652 10 20 10M3 7L6 10L9 7" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6 10V5C6 4.46957 6.21071 3.96086 6.58579 3.58579C6.96086 3.21071 7.46957 3 8 3H10" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 14H4C3.44772 14 3 14.4477 3 15V20C3 20.5523 3.44772 21 4 21H9C9.55228 21 10 20.5523 10 20V15C10 14.4477 9.55228 14 9 14Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                   Replace Users
                                </a>
                            </li>

                        </ul>

                        <div class="tab-content">
                        <div id="user-list" class="tab-pane fade show active" role="tabpanel" aria-labelledby="customer-list-tab">
                            <div class="row d-flex justify-content-between">
                                <div class="col-lg-6 col-12">
                                    <h1 class="header-title">User Management</h1>
                                </div>
                                <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 ">
                                    <div id="search-box-wrapper" class="collapsed">
                                        <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                                    <form method="GET" action="{{ url('user-managment') }}" id="mainSearchForm">
                                            <input 
                                                type="text" 
                                                class="search-input" 
                                                name="search"
                                                placeholder="Search User ID, Name, Email"
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
                            <div class="col-12 d-flex justify-content-end mb-3">
                            <a href="{{url('/add-new-user')}}">
                                
                                <button class="red-action-btn-lg add-new-payment-btn">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.50726 10.5634H4.85938V9.0141H9.50726V4.36621H11.0566V9.0141H15.7044V10.5634H11.0566V15.2113H9.50726V10.5634Z" fill="white"></path>
                                    </svg>

                                    Add New User
                                </button>
                            </a>
                            </div>
                            <div class="table-responsive">
                            <table class="table custom-table-locked">
                                <thead>
                                     <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">User ID</th>
                                    <th scope="col">User Role</th>
                                    <th scope="col">Division</th>
                                    <th scope="col">Mobile Number</th>
                                    <th scope="col">Email</th>
                                    <th scope="col" class="sticky-column">Actions</th>
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
                                 if($user->user_role == '8'){
                                    $role = 'Recovery Manager';
                                 } 
                                 $division = Divisions::where('id', $user->userDetails->division)->value('division_name');
                                ?> 
                                <tr>
                                    <td>{{ $user->userDetails->name ?? '-' }}</td>
                                    <td>{{ $user->id ?? '-' }}</td>
                                    <td>{{$role}}</td>
                                    <td>{{ $division ?? '-' }}</td>
                                    <td>{{ $user->userDetails->phone_number ?? '-' }}</td>
                                     <td>{{ $user->email ?? '-' }}</td>
                                    <td class="sticky-column">
                                        <a href="{{url('edit-user/'.$user->id.'')}}"><button class="action-btn">View more</button></a>
                                        <a href="{{url('activate-user/'.$user->id.'')}}"><button class="action-btn">Activate</button></a>
                                        <a href="{{url('deactivate-user/'.$user->id.'')}}"><button class="action-btn">Deactivate</button></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            </table>
                        </div>

                        <nav class="d-flex justify-content-center mt-5">
                             {{ $users->links('pagination::bootstrap-5') }}
                        </nav>
                        </div>

                        <div id="switch-user" class="tab-pane fade" role="tabpanel" aria-labelledby="switch-user">
                            <div class="col-lg-6 col-12">
                                <h1 class="header-title">Switch User</h1>
                            </div>
                            <div class="card main-card red-card">
                                <div class="card-body ps-4 d-flex">   
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#CC0000" stroke-width="2"/>
                                        <path d="M12 16V12M12 8H12.01" stroke="#CC0000" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="px-2">
                                        <h2 class="section-title mb-1 text-color-red">About Switch Users Function</h2>
                                        <p>The Switch Users function allows you to exchange user information between two existing users. Enter the current User ID and the target Switch User ID then review the user details before confirming the switch. This operation will swap all associated users data, permissions, and allocated users between the selected users.</p>
                                    </div>
                                    
                                </div>
                             </div>
                            <div class="row align-items-center mt-4">
                                <div class="col-md-5">
                                    <label for="division-input" class="form-label custom-input-label">User ID</label>
                                    <input type="text" class="form-control custom-input" id="user_id" placeholder="User ID" name="user_id" >

                                </div>
                                <div class="col-md-2 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                    <path d="M3.09766 9.80872H21.6825L15.4875 3.61377M22.095 14.9712H3.51014L9.70508 21.1661" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="col-md-5">
                                    <label for="division-input" class="form-label custom-input-label">Switch User ID</label>
                                    <input type="text" class="form-control custom-input" id="switch_user_id" placeholder="Switch User ID" name="switch_user_id" >
                                </div>
                            </div>

                            <div class="row align-items-center mt-4">
                                <div class="col-md-5">
                                    <div class="card main-card">
                                    <div class="card-body ps-4">
                                        <div class="detail-row">
                                            <span class="detail-label">Name :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Email :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Mobile Number :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Division :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">User Role :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-md-2 text-center">
                                    
                                </div>
                                
                                <div class="col-md-5">
                                    <div class="card main-card">
                                    <div class="card-body ps-4">
                                        <div class="detail-row">
                                            <span class="detail-label">Name :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Email :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Mobile Number :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Division :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">User Role :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div id="replace-user" class="tab-pane fade" role="tabpanel" aria-labelledby="replace-user">
                            <div class="col-lg-6 col-12">
                                <h1 class="header-title">Replace User</h1>
                            </div>
                            <div class="card main-card red-card">
                                <div class="card-body ps-4 d-flex">   
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#CC0000" stroke-width="2"/>
                                        <path d="M12 16V12M12 8H12.01" stroke="#CC0000" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="px-2">
                                        <h2 class="section-title mb-1 text-color-red">About Replace Users Function</h2>
                                        <p>The Replace Users function allows you to permanently replace one user with another in the system. Enter the current User ID and the replacement User ID, then review the user details before confirming. This operation will transfer all data, permissions, and allocations from the original user to the replacement user.</p>
                                    </div>
                                    
                                </div>
                             </div>
                            <div class="row align-items-center mt-4">
                                <div class="col-md-5">
                                    <label for="division-input" class="form-label custom-input-label">User ID</label>
                                    <input type="text" class="form-control custom-input" id="user_id" placeholder="User ID" name="user_id" >

                                </div>
                                <div class="col-md-2 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                    <path d="M3.09766 9.80872H21.6825L15.4875 3.61377M22.095 14.9712H3.51014L9.70508 21.1661" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="col-md-5">
                                    <label for="division-input" class="form-label custom-input-label">Switch User ID</label>
                                    <input type="text" class="form-control custom-input" id="switch_user_id" placeholder="Switch User ID" name="switch_user_id" >
                                </div>
                            </div>

                            <div class="row align-items-center mt-4">
                                <div class="col-md-5">
                                    <div class="card main-card">
                                    <div class="card-body ps-4">
                                        <div class="detail-row">
                                            <span class="detail-label">Name :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Email :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Mobile Number :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Division :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">User Role :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-md-2 text-center">
                                    
                                </div>
                                
                                <div class="col-md-5">
                                    <div class="card main-card">
                                    <div class="card-body ps-4">
                                        <div class="detail-row">
                                            <span class="detail-label">Name :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Email :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Mobile Number :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Division :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">User Role :</span>
                                            <span class="detail-value"></span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
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
            <a href="{{url('user-managment')}}"><button class="btn rounded-phill">Clear All</button></a>
        </div>
    </div>
   <form action="" method="GET" id="filterForm">
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

        <!-- Hidden input that stores selected role IDs -->
        <input type="hidden" name="roles" id="selectedRolesInput" value="{{ implode(',', $selectedRoles ?? []) }}">

        <div class="mt-5 filter-categories">
            <p class="filter-title">Divisions</p>
            @foreach($divisions as $division)
                <div class="form-check custom-circle-checkbox">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="division{{ $division->id }}" 
                        name="division[]" 
                        value="{{ $division->id }}"
                        {{ in_array($division->id, $selectedDivisions ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="division{{ $division->id }}">
                        {{ $division->division_name }}
                    </label>
                </div>
            @endforeach

            <button type="submit" class="red-action-btn-lg mt-4">Apply Filter</button>
        </div>
        </form>
    </div>




</body>

</html>




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

    // Convert hidden input string back to array
    let selectedRoles = hiddenInput.value ? hiddenInput.value.split(",").map(Number) : [];

    // Click handler for role tags
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

            // Update hidden input value
            hiddenInput.value = selectedRoles.join(",");
        });
    });
}); 
function searchUsers(val) {
    if (event.key === "Enter") {
        document.getElementById("mainSearchForm").submit();
    }
}
</script>
