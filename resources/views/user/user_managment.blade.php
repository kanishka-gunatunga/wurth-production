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
                            <!-- <li class="nav-item mb-3">
                                <a class="nav-link" data-bs-toggle="tab" href="#promote-user" role="tab" aria-controls="promote-user" aria-selected="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g clip-path="url(#clip0_4885_9726)">
                                    <path d="M18.954 7.5H14.817C14.7095 7.50052 14.6045 7.53277 14.5153 7.59271C14.426 7.65264 14.3564 7.7376 14.3153 7.83692C14.2741 7.93624 14.2632 8.0455 14.2838 8.15101C14.3045 8.25652 14.3559 8.35357 14.4315 8.43L15.969 9.969L12.75 13.1895L10.281 10.719C10.2113 10.6492 10.1286 10.5937 10.0375 10.5559C9.94633 10.5181 9.84865 10.4987 9.75 10.4987C9.65135 10.4987 9.55367 10.5181 9.46255 10.5559C9.37143 10.5937 9.28867 10.6492 9.219 10.719L4.719 15.219C4.64927 15.2887 4.59395 15.3715 4.55622 15.4626C4.51848 15.5537 4.49905 15.6514 4.49905 15.75C4.49905 15.8486 4.51848 15.9463 4.55622 16.0374C4.59395 16.1285 4.64927 16.2113 4.719 16.281C4.78873 16.3507 4.87152 16.406 4.96263 16.4438C5.05373 16.4815 5.15138 16.5009 5.25 16.5009C5.34862 16.5009 5.44627 16.4815 5.53737 16.4438C5.62848 16.406 5.71127 16.3507 5.781 16.281L9.75 12.3105L12.219 14.781C12.2887 14.8508 12.3714 14.9063 12.4626 14.9441C12.5537 14.9819 12.6513 15.0013 12.75 15.0013C12.8487 15.0013 12.9463 14.9819 13.0374 14.9441C13.1286 14.9063 13.2113 14.8508 13.281 14.781L17.031 11.031L18.5685 12.5685C18.9135 12.9135 19.5 12.669 19.5 12.183V8.046C19.5 7.9743 19.4859 7.9033 19.4584 7.83706C19.431 7.77081 19.3908 7.71062 19.3401 7.65992C19.2894 7.60922 19.2292 7.569 19.1629 7.54156C19.0967 7.51412 19.0257 7.5 18.954 7.5ZM0 1.5C0 1.10218 0.158035 0.720644 0.43934 0.43934C0.720644 0.158035 1.10218 0 1.5 0L22.5 0C22.8978 0 23.2794 0.158035 23.5607 0.43934C23.842 0.720644 24 1.10218 24 1.5V22.5C24 22.8978 23.842 23.2794 23.5607 23.5607C23.2794 23.842 22.8978 24 22.5 24H1.5C1.10218 24 0.720644 23.842 0.43934 23.5607C0.158035 23.2794 0 22.8978 0 22.5V1.5ZM1.5 1.5V22.5H22.5V1.5H1.5Z" fill="#CC0000"/>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_4885_9726">
                                    <rect width="24" height="24" fill="white"/>
                                    </clipPath>
                                    </defs>
                                    </svg>
                                   Promote Users
                                </a>
                            </li> -->
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
                                    <form method="GET" action="{{ url('user-managment') }}" id="mainSearchForm" class="w-100">
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
                            @if(in_array('add-user', session('permissions', [])))
                            <div class="col-12 d-flex justify-content-end mb-3">
                                <a href="{{ url('/add-new-user') }}">
                                    <button class="red-action-btn-lg add-new-payment-btn">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.50726 10.5634H4.85938V9.0141H9.50726V4.36621H11.0566V9.0141H15.7044V10.5634H11.0566V15.2113H9.50726V10.5634Z"
                                                fill="white"></path>
                                        </svg>
                                        Add New User
                                    </button>
                                </a>
                            </div>
                            @endif

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
                                    @if($users->count() > 0)
                                        @foreach($users as $user)
                                            <?php
                                                if($user->user_role == '1'){ $role = 'System Administrator'; }
                                                if($user->user_role == '2'){ $role = 'Head of Division'; }
                                                if($user->user_role == '3'){ $role = 'Regional Sales Manager'; }
                                                if($user->user_role == '4'){ $role = 'Area Sales Manager'; }
                                                if($user->user_role == '5'){ $role = 'Team Leader'; }
                                                if($user->user_role == '6'){ $role = 'ADM (Sales Rep)'; }
                                                if($user->user_role == '7'){ $role = 'Finance Manager'; }
                                                if($user->user_role == '8'){ $role = 'Recovery Manager'; }

                                                $division = Divisions::where('id', $user->userDetails->division)->value('division_name');
                                            ?>
                                            <tr>
                                                <td>{{ $user->userDetails->name ?? '-' }}</td>
                                                <td>{{ $user->id ?? '-' }}</td>
                                                <td>{{ $role }}</td>
                                                <td>{{ $division ?? '-' }}</td>
                                                <td>{{ $user->userDetails->phone_number ?? '-' }}</td>
                                                <td>{{ $user->email ?? '-' }}</td>
                                                <td class="sticky-column">
                                                    @if(in_array('edit-user', session('permissions', [])))
                                                        <a href="{{ url('edit-user/'.$user->id) }}"><button class="action-btn">View more</button></a>
                                                    @endif
                                                    @if(in_array('status-change-user', session('permissions', [])))
                                                        <a href="{{ url('activate-user/'.$user->id) }}"><button class="action-btn">Activate</button></a>
                                                        <a href="{{ url('deactivate-user/'.$user->id) }}"><button class="action-btn">Deactivate</button></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                No results found
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>

                            </table>
                        </div>

                        <nav class="d-flex justify-content-center mt-5">
                             {{ $users->links('pagination::bootstrap-5') }}
                        </nav>
                        </div>

                        <div id="switch-user" class="tab-pane fade" role="tabpanel" aria-labelledby="switch-user">
                            <form class="" action="{{url('switch-user')}}" method="post">
                            @csrf
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
                 
                                    <select class="form-select custom-input" aria-label="Default select example" id="user_id" name="user_id" required>
                                        <option selected hidden disabled>Select User</option>
                                        <?php foreach($switch_users as $switch_user){ ?>
                                        <option value="{{$switch_user->id}}">{{$switch_user->id}} ({{$switch_user->userDetails->name}})</option>
                                         <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                    <path d="M3.09766 9.80872H21.6825L15.4875 3.61377M22.095 14.9712H3.51014L9.70508 21.1661" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="col-md-5">
                                    <label for="division-input" class="form-label custom-input-label">Switch User ID</label>
                        
                                    <select class="form-select custom-input" aria-label="Default select example" id="switch_user_id" name="switch_user_id" required>
                                       
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mt-4">
                                <div class="col-md-5">
                                    <div class="card main-card left-card">
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
                                    <div class="card main-card right-card">
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
                            <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                            <button type="submit" class="btn btn-danger submit">Save</button>
                        </div>
                        </form>
                        </div> 
                        <div id="replace-user" class="tab-pane fade" role="tabpanel" aria-labelledby="replace-user">
                            <form class="" action="{{url('replace-user')}}" method="post">
                            @csrf
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
                 
                                    <select class="form-select custom-input" aria-label="Default select example" id="repalce_user_id" name="repalce_user_id" required>
                                        <option selected hidden disabled>Select User</option>
                                        <?php foreach($switch_users as $switch_user){ ?>
                                        <option value="{{$switch_user->id}}">{{$switch_user->id}} ({{$switch_user->userDetails->name}})</option>
                                         <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                    <path d="M3.09766 9.80872H21.6825L15.4875 3.61377M22.095 14.9712H3.51014L9.70508 21.1661" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="col-md-5">
                                    <label for="division-input" class="form-label custom-input-label">Replace User ID</label>
                        
                                    <select class="form-select custom-input" aria-label="Default select example" id="replace_switch_user_id" name="replace_switch_user_id" required>
                                       
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mt-4">
                                <div class="col-md-5">
                                    <div class="card main-card left-card-replace">
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
                                    <div class="card main-card right-card-replace">
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
                           
                              <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                            <button type="submit" class="btn btn-danger submit">Save</button>
                        </div>
                         </form>
                        </div> 


                        <div id="promote-user" class="tab-pane fade" role="tabpanel" aria-labelledby="promote-user">
                            <form class="" action="{{url('promote-user')}}" method="post">
                            @csrf
                            <div class="col-lg-6 col-12">
                                <h1 class="header-title">Promote User</h1>
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
                                        <h2 class="section-title mb-1 text-color-red">About Promote Users Function</h2>
                                        <p>The Promote Users function allows you to elevate a user's role and responsibilities within the organization. Enter the User ID to promote and specify their new supervisor. Review the user details and confirm to update their position, reporting structure, and access permissions accordingly.</p>
                                    </div>
                                    
                                </div>
                             </div>
                            <div class="row  mt-4">
                               <div class="col-md-6">
                                    <label for="division-input" class="form-label custom-input-label">User ID</label>
                 
                                    <select class="form-select custom-input" aria-label="Default select example" id="promote_user_id" name="promote_user_id" required>
                                        <option selected hidden disabled>Select User</option>
                                        <?php foreach($switch_users as $switch_user){ ?>
                                        <option value="{{$switch_user->id}}">{{$switch_user->id}} ({{$switch_user->userDetails->name}})</option>
                                         <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="division-input" class="form-label custom-input-label">Prmote Role</label>
                                    <select class="form-select custom-input" aria-label="Default select example" id="promote_role" name="promote_role" required>
                                       
                                    </select>

                                    <label for="division-input" class="form-label custom-input-label mt-4">Assign current user fucntions to</label>
                        
                                    <select class="form-select custom-input" aria-label="Default select example" id="promote_switch_user_id" name="promote_switch_user_id" required>
                                       
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mt-4">
                                <div class="col-md-6">
                                    <div class="card main-card left-card-promote">
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

                               
                                <!-- <div class="col-md-6">
                                    <div class="card main-card right-card-replace">
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
                                </div> -->
                            </div>
                            </form>
                             <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                            <button type="submit" class="btn btn-danger submit">Save</button>
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
                <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter {{ in_array(8, $selectedRoles ?? []) ? 'active' : '' }}" data-role="8">
                    <span>Recovery Manager</span>
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

@include('layouts.footer2')


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

<script>
    $(document).ready(function () {


    $('#user_id').on('change', function () {
        let userId = $(this).val();
 
        $.ajax({
            url: "{{ url('get-user-details-divison-role') }}",
            type: "POST",
            data: {
                user_id: userId,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.status) {

                
                    fillCard('.left-card', res);

            
                    $('#switch_user_id').empty().append('<option disabled selected>Select User</option>');
                    $.each(res.filtered_users, function (index, user) {
                        $('#switch_user_id').append(
                            `<option value="${user.id}">${user.id} (${user.user_details.name})</option>`
                        );
                    });
                }
            }
        });
    });

    $('#switch_user_id').on('change', function () {
        let userId = $(this).val();

        $.ajax({
            url: "{{ url('get-user-details-divison-role') }}",
            type: "POST",
            data: {
                user_id: userId,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.status) {
                    fillCard('.right-card', res);
                }
            }
        });
    });


    $('#repalce_user_id').on('change', function () {
        let userId = $(this).val();
 
        $.ajax({
            url: "{{ url('get-user-details-divison-role') }}",
            type: "POST",
            data: {
                user_id: userId,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.status) {

                
                    fillCard('.left-card-replace', res);

            
                    $('#replace_switch_user_id').empty().append('<option disabled selected>Select User</option>');
                    $.each(res.filtered_users, function (index, user) {
                        $('#replace_switch_user_id').append(
                            `<option value="${user.id}">${user.id} (${user.user_details.name})</option>`
                        );
                    });
                }
            }
        });
    });

    $('#replace_switch_user_id').on('change', function () {
        let userId = $(this).val();

        $.ajax({
            url: "{{ url('get-user-details-divison-role') }}",
            type: "POST",
            data: {
                user_id: userId,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.status) {
                    fillCard('.right-card-replace', res);
                }
            }
        });
    });


    $('#promote_user_id').on('change', function () {
    let userId = $(this).val();

    $.ajax({
        url: "{{ url('get-user-details-divison-role-with-roles') }}",
        type: "POST",
        data: {
            user_id: userId,
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.status) {

                // Fill card (if you have this function)
                fillCard('.left-card-promote', res);

                // Populate switch user dropdown (users from same division)
                $('#promote_switch_user_id').empty().append('<option disabled selected>Select User</option>');
                $.each(res.filtered_users, function (index, user) {
                    let userName = user.user_details?.name || 'Unknown';
                    $('#promote_switch_user_id').append(
                        `<option value="${user.id}">${user.id} (${userName})</option>`
                    );
                });

                // Populate promote role dropdown (higher roles)
                $('#promote_role').empty().append('<option disabled selected>Select Role</option>');
                $.each(res.roles, function (index, role) {
                    $('#promote_role').append(
                        `<option value="${role.value}">${role.label}</option>`
                    );
                });
            }
        }
    });
});


    function fillCard(card, res) {
        $(`${card} .detail-row:eq(0) .detail-value`).text(res.user.user_details.name);
        $(`${card} .detail-row:eq(1) .detail-value`).text(res.user.email);
        $(`${card} .detail-row:eq(2) .detail-value`).text(res.user.user_details.phone_number);
        $(`${card} .detail-row:eq(3) .detail-value`).text(res.division_name);
        $(`${card} .detail-row:eq(4) .detail-value`).text(res.role_name);
    }
});

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