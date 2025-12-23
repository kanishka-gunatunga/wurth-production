<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wurth Lanka | Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('adm_assets/css/adm_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('adm_assets/css/calander.css') }}">
    <link rel="stylesheet" href="{{ asset('adm_assets/css/custom.css') }}">
</head>
<?php
use App\Models\Divisions;
use App\Models\UserDetails;
?>
<body>
<div class="mobile-wrapper">


        <!-- body content -->
        <form id="profileForm" class="content  needs-validation " novalidate action="" method="post"  enctype="multipart/form-data">
        @csrf
            <div class="d-flex w-100 text-start justify-content-center align-items-center fixed-header mb-3">
                <a href="{{url('adm/my-profile')}}" class="my-3 position-absolute"
                    style="left: 20px;">
                    <svg width="15" height="26" viewBox="0 0 15 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 25.5L0 13L12.5 0.5L14.7188 2.71875L4.4375 13L14.7188 23.2813L12.5 25.5Z"
                            fill="black" />
                    </svg>

                </a>
                <h3 class="page-title mb-0" style="color: #000 !important;">Edit Profile</h3>
                
            </div>

            <div class="d-flex justify-content-center align-items-center mb-5 fixed-header">
                <div class="profile-pic-container">
                    <?php if($other_details->profile_picture == ''){ ?>
                        <img id="profilePic" src="{{ asset('adm_assets/assests/profile-pic.jpg') }}" alt="Profile Picture" class="img-fluid profile-pic-offcanvas mb-3">
                    <?php } else { ?>
                        <img id="profilePic" src="{{ asset('db_files/user_profile_images/'.$other_details->profile_picture.'') }}" alt="Profile Picture" class="img-fluid profile-pic-offcanvas mb-3">
                    <?php } ?>
                    <label for="imageUpload" class="upload-button">
                        <svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.6665 1.33333C8.6665 0.597333 9.21717 0 9.89717 0H18.1025C18.7825 0 19.3332 0.597333 19.3332 1.33333C19.3332 2.06933 18.7825 2.66667 18.1025 2.66667H9.89717C9.21717 2.66667 8.6665 2.06933 8.6665 1.33333ZM11.0372 24H16.9625C21.1238 24 23.2052 24 24.6998 23.0373C25.3429 22.6239 25.8983 22.0879 26.3345 21.46C27.3332 20.02 27.3332 18.0133 27.3332 14C27.3332 9.98667 27.3332 7.98133 26.3332 6.54C25.8975 5.91215 25.3425 5.37619 24.6998 4.96267C23.2052 4 21.1238 4 16.9625 4H11.0372C6.87584 4 4.7945 4 3.29984 4.96267C2.6572 5.37622 2.10222 5.91218 1.6665 6.54C0.666504 7.98 0.666504 9.98667 0.666504 13.9973V14C0.666504 18.0133 0.666504 20.0187 1.66517 21.46C2.09717 22.084 2.65184 22.62 3.29984 23.0373C4.7945 24 6.87584 24 11.0372 24ZM8.44384 14C8.44384 11.04 10.9318 8.64267 13.9998 8.64267C17.0678 8.64267 19.5558 11.0413 19.5558 14C19.5558 16.9587 17.0665 19.3573 13.9998 19.3573C10.9318 19.3573 8.44384 16.9573 8.44384 14ZM10.6665 14C10.6665 12.224 12.1598 10.7867 13.9998 10.7867C15.8398 10.7867 17.3332 12.2253 17.3332 14C17.3332 15.7747 15.8398 17.2133 13.9998 17.2133C12.1598 17.2133 10.6665 15.7747 10.6665 14ZM22.1478 8.64267C21.5345 8.64267 21.0372 9.12267 21.0372 9.71467C21.0372 10.3053 21.5345 10.7853 22.1478 10.7853H22.8892C23.5025 10.7853 23.9998 10.3053 23.9998 9.71467C23.9998 9.12267 23.5025 8.64267 22.8892 8.64267H22.1478Z"
                                fill="#CC0000" fill-opacity="0.8" />
                        </svg>
    
                    </label>
                    <input type="file" id="imageUpload" name="profile_picture" accept="image/*" style="display: none;">
                </div>
            </div>
            <!-- row 2 -->
            <div class=" scrollable-section">
                <div class="d-flex flex-column justify-content-center align-items-center p-2" >
                    <div class="mb-3 w-100 " >
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" placeholder="" name="name" value="{{$other_details->name}}" required/>
                            <div class="invalid-feedback">
                                Name is required
                            </div>
                            @if($errors->has("name")) <div class="alert alert-danger mt-2">{{ $errors->first('name') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="adm_number">ADM Number</label>
                            <input type="number" class="form-control" placeholder="" name="adm_number" id="adm_number" value="{{$other_details->adm_number}}" disabled/>
                            <div class="invalid-feedback">
                                ADM Number is required
                            </div>
                            @if($errors->has("adm_number")) <div class="alert alert-danger mt-2">{{ $errors->first('adm_number') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" class="form-control" placeholder="" name="phone_number" id="phone_number" value="{{$other_details->phone_number}}" required/>
                            <div class="invalid-feedback">Phone Number must be exactly 10 digits</div>
                            @if($errors->has("phone_number")) <div class="alert alert-danger mt-2">{{ $errors->first('phone_number') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="" name="email" id="email" value="{{$login_details->email}}" required/>
                            <div class="invalid-feedback">Valid email is required</div>
                            @if($errors->has("email")) <div class="alert alert-danger mt-2">{{ $errors->first('email') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="superviser">Supervisor</label>
                            <input type="text" class="form-control" placeholder="" name="superviser" id="superviser" value="{{UserDetails::where('user_id', $other_details->supervisor)->value('name')}}" disabled/>
                            <div class="invalid-feedback">
                                Supervisor is required
                            </div>
                            @if($errors->has("superviser")) <div class="alert alert-danger mt-2">{{ $errors->first('superviser') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="divition">Divition</label>
                            <input type="text" class="form-control" placeholder="" name="divition" id="divition" value="{{Divisions::where('id', $other_details->division)->value('division_name')}}" disabled/>
                            <div class="invalid-feedback">
                                Divition is required
                            </div>
                            @if($errors->has("divition")) <div class="alert alert-danger mt-2">{{ $errors->first('divition') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="current_password">Password</label>
                            <input type="password" class="form-control" placeholder="Enter Current Password"
                                name="current_password" id="current_password"  />
                                <div class="invalid-feedback">
                                    Password is required
                                </div>
                                @if($errors->has("current_password")) <div class="alert alert-danger mt-2">{{ $errors->first('current_password') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" placeholder="Enter New Password" name="password"
                                id="password" />
                                <div class="invalid-feedback">
                                    New Password is required
                                </div>
                                @if($errors->has("password")) <div class="alert alert-danger mt-2">{{ $errors->first('password') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="confirm_new_password">Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="Confirm New Password"
                                name="password_confirmation" id="password_confirmation" />
                                <div class="invalid-feedback">
                                    Confirm New Password is required
                                </div>
                                @if($errors->has("password_confirmation")) <div class="alert alert-danger mt-2">{{ $errors->first('password_confirmation') }}</div>@endif
                        </div>
                        
                        <div class="d-flex w-100 justify-content-center align-items-center">
                            <button class="styled-button-normal px-5"  type="submit">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iconify/2.0.0/iconify.min.js"
        integrity="sha512-lYMiwcB608+RcqJmP93CMe7b4i9G9QK1RbixsNu4PzMRJMsqr/bUrkXUuFzCNsRUo3IXNUr5hz98lINURv5CNA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.getElementById('imageUpload').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const profilePic = document.getElementById('profilePic');
                    profilePic.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });

    </script>
    <script>
        (function () {
            const form = document.querySelector('#profileForm');
    
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
    
                const newPassword = document.getElementById('password').value;
                const confirmNewPassword = document.getElementById('password_confirmation').value;
    
                if (newPassword !== confirmNewPassword) {
                    event.preventDefault();
                    event.stopPropagation();
    
                    const confirmField = document.getElementById('password_confirmation');
                    confirmField.setCustomValidity('Passwords do not match');
                    confirmField.reportValidity();
                } else {
                    document.getElementById('password_confirmation').setCustomValidity('');
                }
    
                form.classList.add('was-validated');
            });
        })();
    </script>
</body>

</html>
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