@include('layouts.dashboard-header')
<?php
use App\Models\Divisions;
use App\Models\UserDetails;
?>
<div class="container-fluid">
            <div class="main-wrapper">
                <div class="p-4 pt-0">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">Edit User</h1>
                    </div>
     
                    <form class="" action="" method="post">
                    @csrf
                        
                        <div class="row d-flex justify-content-between">
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">
                                    Name</label><span class="outside-label"> (First Name & Second Name)</span>
                                <input type="text" class="form-control custom-input" id="division-input" placeholder="Name" name="name" value="{{$other_details->name}}">
                                @if($errors->has("name")) <div class="alert alert-danger mt-2">{{ $errors->first('name') }}</div>@endif
                            </div>
    
    
    
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="head-of-division-select" class="form-label custom-input-label">User
                                    Role
                                </label>
                                <select class="form-select custom-input" aria-label="Default select example"
                                    id="head-of-division-select" name="user_role">
                                    <?php
                                    if($login_details->user_role == '1'){
                                        $role = 'System Administrator';
                                     }   
                                     if($login_details->user_role == '2'){
                                        $role = 'Head of Division';
                                     } 
                                     if($login_details->user_role == '3'){
                                        $role = 'Regional Sales Manager';
                                     } 
                                     if($login_details->user_role == '4'){
                                        $role = 'Area Sales Manager';
                                     } 
                                     if($login_details->user_role == '5'){
                                        $role = 'Team Leader';
                                     } 
                                     if($login_details->user_role == '6'){
                                        $role = 'ADM (Sales Rep)';
                                     } 
                                     if($login_details->user_role == '7'){
                                        $role = 'Finance Manager';
                                     } 
                                     if($login_details->user_role == '8'){
                                        $role = 'Recovery Manager';
                                     } 
                                    ?>
                                    <option selected hidden value="{{$login_details->user_role}}">{{$role}}</option>
                                    <!-- <option value="1">System Administrator</option>
                                    <option value="2">Head of Division</option>
                                    <option value="3">Regional Sales Manager</option>
                                    <option value="4">Area Sales Manager</option>
                                    <option value="5">Team Leader</option>
                                    <option value="6">ADM (Sales Rep)</option>
                                    <option value="7">Finance Manager</option>
                                     <option value="8">Recovery Manager</option> -->
                                </select>
                                @if($errors->has("user_role")) <div class="alert alert-danger mt-2">{{ $errors->first('user_role') }}</div>@endif
                            </div>
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="head-of-division-select" class="form-label custom-input-label">Division</label>
                                <select class="form-select custom-input" aria-label="Default select example"
                                    id="head-of-division-select" name="division">
                                    <option selected hidden value="{{$other_details->division}}">{{Divisions::where('id', $other_details->division)->value('division_name')}}</option>
                                    <!-- <?php foreach($divisions as $division){  ?> 
                                        <option value="{{$division->id}}">{{$division->division_name}}</option>
                                    <?php } ?> -->
                                </select>
                                @if($errors->has("division")) <div class="alert alert-danger mt-2">{{ $errors->first('division') }}</div>@endif
                            </div>
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Phone Number</label>
                                <input type="tel" class="form-control custom-input" id="division-input" placeholder="Phone Number"  name="phone_number" value="{{$other_details->phone_number}}">
                                @if($errors->has("phone_number")) <div class="alert alert-danger mt-2">{{ $errors->first('phone_number') }}</div>@endif
                            </div>

                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Email</label>
                                <input type="email" class="form-control custom-input" id="division-input" placeholder="Email" name="email" value="{{$login_details->email}}">
                                @if($errors->has("email")) <div class="alert alert-danger mt-2">{{ $errors->first('email') }}</div>@endif
                            </div>


                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">ADM Number</label>
                                <input type="text" class="form-control custom-input" id="division-input" placeholder="ADM Number" name="adm_number" value="{{$other_details->adm_number}}">
                                @if($errors->has("adm_number")) <div class="alert alert-danger mt-2">{{ $errors->first('adm_number') }}</div>@endif
                            </div>


                            <div class="mb-4 col-12 col-lg-6">
                                <label for="head-of-division-select" class="form-label custom-input-label">Supervisor</label>
                                <select class="form-select custom-input" aria-label="Default select example"
                                    id="head-of-division-select" name="supervisor">
                                    <option selected hidden value="{{$other_details->supervisor}}">{{UserDetails::where('user_id', $other_details->supervisor)->value('name')}}</option>
                                    <?php foreach($supervisors as $supervisor){  ?> 
                                        <option value="{{$supervisor->id}}">{{$supervisor->userDetails->name}}</option>
                                    <?php } ?>
                                </select>
                                @if($errors->has("supervisor")) <div class="alert alert-danger mt-2">{{ $errors->first('supervisor') }}</div>@endif
                            </div>

                              <div class="mb-4 col-12 col-lg-6">
                                <label for="head-of-division-select" class="form-label custom-input-label">2nd Level Supervisor</label>
                                <select class="form-select custom-input" aria-label="Default select example"
                                    id="head-of-division-select" name="second_supervisor">
                                    <?php if(old('second_supervisor')) {  ?>
                                        <option selected hidden value="{{old('second_supervisor')}}">{{UserDetails::where('user_id', old('second_supervisor'))->value('name')}}</option>
                                    <?php } ?>   
                                </select>
                                @if($errors->has("second_supervisor")) <div class="alert alert-danger mt-2">{{ $errors->first('second_supervisor') }}</div>@endif
                            </div>          
                            

                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Password</label>
                                <input type="password" class="form-control custom-input" id="division-input" placeholder="Password" name="password">
                                @if($errors->has("password")) <div class="alert alert-danger mt-2">{{ $errors->first('password') }}</div>@endif
                            </div>

                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Confirm Password</label>
                                <input type="password" class="form-control custom-input" id="division-input" placeholder="Confirm Password" name="password_confirmation">
                                @if($errors->has("password_confirmation")) <div class="alert alert-danger mt-2">{{ $errors->first('password_confirmation') }}</div>@endif
                            </div>

                        </div>

                        <input type="hidden" id="current_supervisor" value="{{ $other_details->supervisor }}">
                        <input type="hidden" id="current_second_supervisor" value="{{ $other_details->second_supervisor }}">

                        <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                            <a href="{{url('user-managment')}}"><button type="button" class="btn btn-dark cancel">Cancel</button></a>
                            <button type="submit" class="btn btn-danger submit">Submit</button>
                        </div>

                </div>

                </form>
            </div>

        </div>
    </div>
    </div>
</body>

</html>

@include('layouts.footer2')


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>


    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');
        const userRoleSelect = document.getElementById('head-of-division-select');
        const supervisorField = document.querySelector('select[name="supervisor"]').parentElement;
         const secondSupervisorField = document.querySelector('select[name="second_supervisor"]').parentElement;
        const divisionField = document.querySelector('select[name="division"]').parentElement;
        const admField = document.querySelector('input[name="adm_number"]').parentElement;

         const rolesToHide = ["1", "2", "7"];

        function toggleFields() {
            const selectedRole = userRoleSelect.value;
            if (rolesToHide.includes(selectedRole)) {
                supervisorField.style.display = 'none';
                secondSupervisorField.style.display = 'none';
                divisionField.style.display = 'none';
            } else {
                supervisorField.style.display = 'block';
                secondSupervisorField.style.display = 'block';
                divisionField.style.display = 'block';
            }
            if(selectedRole == '2'){
                divisionField.style.display = 'block';
            }
            if(selectedRole == '7'){
                divisionField.style.display = 'block';
            }
            if(selectedRole == '6'){
                admField.style.display = 'block';
            }
            else{
                admField.style.display = 'none';
            }
        }

        toggleFields();

        userRoleSelect.addEventListener('change', toggleFields);

        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    });

//     $(document).ready(function () {
//     $('select[name="user_role"]').on('change', function () {
//         var user_role = $(this).val();
//         var supervisor = $('select[name="supervisor"]');
//         var second_supervisor = $('select[name="second_supervisor"]');

//         // Clear existing leave types
//         supervisor.empty();
//         second_supervisor.empty();

//         if (user_role) {
//             $.ajax({
//                 url: '/get-supervisors/' + user_role,
//                 type: 'GET',
//                 dataType: 'json',
//                 success: function (data) {
//                     console.log(data);
//                     supervisor.append('<option value="">Select Supervisor</option>');

//                     $.each(data, function (key, value) {
//                         supervisor.append('<option value="' + value.id + '">' + value.user_details.name + '</option>');
//                     });

//                     second_supervisor.append('<option value="">Select Supervisor</option>');

//                     $.each(data, function (key, value) {
//                         second_supervisor.append('<option value="' + value.id + '">' + value.user_details.name + '</option>');
//                     });
//                 },
//                 error: function (xhr, status, error) {
//                     console.error('Error fetching leave types:', error);
//                 }
//             });
//         } else {
//             supervisor.append('<option value="">No Supervisor Available</option>');
//         }
//     });
// });
 function getSupervisors() {

    var user_role = $('select[name="user_role"]').val();
    var division  = $('select[name="division"]').val();

    var supervisor = $('select[name="supervisor"]');
    var second_supervisor = $('select[name="second_supervisor"]');

    var currentSupervisor = $('#current_supervisor').val();
    var currentSecondSupervisor = $('#current_second_supervisor').val();

    supervisor.empty();
    second_supervisor.empty();

    if (user_role == '1' || user_role == '7') {
        supervisor.append('<option value="">No Supervisor Required</option>');
        second_supervisor.append('<option value="">No Supervisor Required</option>');
        return;
    }

    if (!division) {
        supervisor.append('<option value="">Select Division First</option>');
        second_supervisor.append('<option value="">Select Division First</option>');
        return;
    }

    if (user_role) {
        $.ajax({
            url: '{{ url('get-supervisors') }}',
            type: 'GET',
            data: {
                role: user_role,
                division: division
            },
            dataType: 'json',
            success: function (data) {

                supervisor.append('<option value="">Select Supervisor</option>');
                second_supervisor.append('<option value="">Select Supervisor</option>');

                $.each(data, function (key, value) {
                    let selectedSupervisor = (value.id == currentSupervisor) ? 'selected' : '';
                    let selectedSecondSupervisor = (value.id == currentSecondSupervisor) ? 'selected' : '';

                    supervisor.append(
                        '<option value="' + value.id + '" ' + selectedSupervisor + '>' +
                        value.user_details.name +
                        '</option>'
                    );

                    second_supervisor.append(
                        '<option value="' + value.id + '" ' + selectedSecondSupervisor + '>' +
                        value.user_details.name +
                        '</option>'
                    );
                });
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }
}
$(document).ready(function () {
    // Load supervisors on page load
    getSupervisors();

    // Reload when role or division changes
    $('select[name="user_role"], select[name="division"]').on('change', function () {
        getSupervisors();
    });
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