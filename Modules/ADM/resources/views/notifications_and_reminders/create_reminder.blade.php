@include('adm::layouts.header')
<?php

use App\Models\UserDetails;

$name = UserDetails::where('user_id', Auth::user()->id)->value('name');
?>
<div class="d-flex flex-row px-3 mt-4 justify-content-between align-items-center w-100 text-start pt-2 mb-0">
    <h3 class="page-title">Create Reminder</h3>
</div>
<!-- body content -->
<form id="profileForm" class="content  needs-validation p-2" novalidate action="{{ url('adm/create-reminder') }}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- row 2 -->
    <div class=" scrollable-section">
        <div class="d-flex flex-column justify-content-center align-items-center p-2">
            <div class="mb-3 w-100 ">

                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="send_from">Send From</label>
                    <input type="text" class="form-control" placeholder="" name="send_from" id="send_from"
                        required value="{{$name}}" readonly />
                    @if($errors->has("send_from")) <div class="alert alert-danger mt-2">{{ $errors->first('send_from') }}</div>@endif
                </div>

                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="send_from">Reminder Title</label>
                    <input type="text" class="form-control" placeholder="" name="reminder_title" id="reminder_title"
                        required />
                    @if($errors->has("reminder_title")) <div class="alert alert-danger mt-2">{{ $errors->first('reminder_title') }}</div>@endif
                </div>

                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="reminder_date">Reminder Type</label>
                    <select class="select2-no-search" name="reminder_type">
                        <option></option>
                        <option value="Self">Self</option>
                        <option value="Other">Other</option>
                    </select>
                    @if($errors->has("reminder_type")) <div class="alert alert-danger mt-2">{{ $errors->first('reminder_type') }}</div>@endif
                </div>

                <!-- Send To (User Level) -->
                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="user_level">Send To (User Level)</label>

                    @php
                    $roleNames = [
                    
                    ];
                    @endphp

                    <select class="form-control" id="user_level" name="user_level">
                        <option value="">Select User Level</option>
                        @foreach($roles as $role)
                        <option value="{{ $role }}">{{ $roleNames[$role] ?? $role }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Send To (User) -->
                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="send_to">Send To (User)</label>
                    <select class="form-control select2" id="send_to" name="send_to[]" multiple disabled>
                        <option value="">Select User</option>
                    </select>
                </div>


                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="reminder_date">Reminder Date</label>
                    <input type="date" class="form-control" placeholder="" name="reminder_date" required />
                    <div class="invalid-feedback">
                        Reminder Date is required
                    </div>
                </div>

                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="name">Reason</label>
                    <textarea type="text" style="border-radius: 8px !important;" class="form-control" rows="6"
                        placeholder="Enter the reason" name="reason" required></textarea>
                    <div class="invalid-feedback">
                        Reason is required
                    </div>
                </div>

                <div class="d-flex w-100 justify-content-center align-items-center pt-3">
                    <button class="styled-button-normal w-100 px-5"
                        style="width: 100% !important; font-size: 14px !important; font-weight: 600; height: 40px !important; min-height: 40px !important"
                        type="submit">Send Reminder</button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('adm::layouts.footer')

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

<script>
    document.getElementById('user_level').addEventListener('change', function() {
        let selectedLevel = this.value;
        let userDropdown = $('#send_to'); // Use jQuery for Select2

        userDropdown.prop('disabled', true); // Disable before fetching
        userDropdown.empty().append('<option value="">Select User</option>'); // Clear old options

        if (!selectedLevel) {
            return;
        }

        fetch(`{{ url('adm/get-users-by-level') }}/${selectedLevel}`)
            .then(response => response.json())
            .then(data => {
                let userDropdown = $('#send_to');
                userDropdown.empty().append('<option value="">Select User</option>');
                data.forEach(user => {
                    let username = user.user_details?.name ?? user.email;
                    userDropdown.append(new Option(username, user.id));
                });
                userDropdown.prop('disabled', false);
                userDropdown.trigger('change'); // refresh Select2
            })
            .catch(error => console.error('Error:', error));
    });
</script>

<script>
    $(document).ready(function() {
        const reminderTypeSelect = $('select[name="reminder_type"]');
        const userLevelDiv = $('#user_level').closest('.input-group-profile');
        const sendToDiv = $('#send_to').closest('.input-group-profile');

        function toggleUserFields() {
            if (reminderTypeSelect.val() === 'Self') {
                userLevelDiv.hide();
                sendToDiv.hide();
                $('#user_level').prop('disabled', true);
                $('#send_to').prop('disabled', true);
            } else {
                userLevelDiv.show();
                sendToDiv.show();
                $('#user_level').prop('disabled', false);
                $('#send_to').prop('disabled', false);
            }
        }

        // Run on page load
        toggleUserFields();

        // Run on change
        reminderTypeSelect.on('change', toggleUserFields);

        // Existing user_level -> send_to fetch
        $('#user_level').on('change', function() {
            let selectedLevel = this.value;
            let userDropdown = $('#send_to');

            userDropdown.prop('disabled', true);
            userDropdown.empty().append('<option value="">Select User</option>');

            if (!selectedLevel) return;
            fetch(`{{ url('adm/get-users-by-level') }}/${selectedLevel}`)
                .then(response => response.json())
                .then(data => {
                    userDropdown.empty().append('<option value="">Select User</option>');
                    data.forEach(user => {
                        let username = user.user_details?.name ?? user.email;
                        userDropdown.append(new Option(username, user.id));
                    });
                    userDropdown.prop('disabled', false);
                    userDropdown.trigger('change'); // refresh Select2
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>