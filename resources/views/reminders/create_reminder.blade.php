@include('layouts.dashboard-header')
<div class="main-wrapper">
    <div class="p-4 pt-0">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Create Reminder</h1>
        </div>
        <hr class="red-line">

        <!-- ✅ Connected Form -->
        <form action="{{ url('/create-reminder') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-12 col-md-6">

                    <!-- Send From (auto-filled) -->
                    <div class="mb-4">
                        <label for="send_from" class="form-label custom-input-label">Send From</label>
                        <input type="text" class="form-control custom-input" id="send_from"
                            name="send_from" value="{{ $name ?? '' }}" readonly>
                        @if($errors->has('send_from'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('send_from') }}</div>
                        @endif
                    </div>
                   
                    <!-- Send To (User Level) -->
                    <div class="mb-4">
                        <label for="user_level" class="form-label custom-input-label">Send to (User Level)</label>
                        <select name="user_level" id="user_level" class="form-control custom-input">
                            <option value="">Select User Level</option>

                            @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('user_level') == $role ? 'selected' : '' }}>
                                @switch($role)
                                @case(1) System Administrator @break
                                @case(2) Head of Division @break
                                @case(3) Regional Sales Manager @break
                                @case(4) Area Sales Manager @break
                                @case(5) Team Leader @break
                                @case(6) ADM (Sales Rep) @break
                                @case(7) Finance Manager @break
                                @case(8) Recovery Manager @break
                                @default Unknown
                                @endswitch
                            </option>
                            @endforeach
                        </select>

                        @if($errors->has('user_level'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('user_level') }}</div>
                        @endif
                    </div>
                      <div class="mb-4" id="division-container" style="display: none;">
                        <label for="division" class="form-label custom-input-label">Division</label>
                        <select name="division" id="division" class="form-control custom-input" >
                            <option value="">All Divisions</option>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Send To (User) -->
                    <div class="mb-4">
                        <label for="send_to" class="form-label custom-input-label">Send to (User)</label>
                        <select name="send_to[]" id="send_to" class="form-control custom-input select2" multiple>
                            <option value="">Select User</option>
                        </select>

                        @error('send_to')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Reminder Date -->
                   
                </div>

                <div class="col-12 col-md-6">
                    
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="reminder_title" class="form-label custom-input-label">Title</label>
                        <input type="text" class="form-control custom-input" id="reminder_title"
                            name="reminder_title" placeholder="Title" value="{{ old('reminder_title') }}" required>
                        @if($errors->has('reminder_title'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('reminder_title') }}</div>
                        @endif
                    </div>
                     <div class="mb-4">
                        <label for="reminder_date" class="form-label custom-input-label">Trigger Date</label>
                        <input type="date" class="form-control custom-input" id="reminder_date"
                            name="reminder_date" value="{{ old('reminder_date') }}" required>
                        @if($errors->has('reminder_date'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('reminder_date') }}</div>
                        @endif
                    </div>
                    <!-- Reason -->
                    <div class="mb-4">
                        <label for="reason" class="form-label custom-input-label">Message</label>
                        <textarea class="form-control custom-input" id="reason" name="reason" rows="10"
                            placeholder="Enter the message" required>{{ old('reason') }}</textarea>
                        @if($errors->has('reason'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('reason') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                <a href="{{ url('reminders') }}" class="btn btn-dark cancel">Cancel</a>
                <button type="submit" class="btn btn-danger submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast message -->
<div id="user-toast"
    class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
    role="alert" aria-live="assertive" aria-atomic="true"
    style="z-index: 9999; display: none; min-width: 320px;">
    <div class="d-flex align-items-center">
        <span class="toast-icon-circle d-flex align-items-center justify-content-center me-3">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#fff" />
                <path d="M7 12.5l3 3 7-7" stroke="#28a745" stroke-width="2" fill="none"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
        <div class="toast-body flex-grow-1">
            Reminder added successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
            aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';">
        </button>
    </div>
</div>
@include('layouts.footer2')

<script>
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        const button = dropdown.querySelector('.custom-dropdown');
        const items = dropdown.querySelectorAll('.dropdown-item');

        items.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                button.innerHTML = this.textContent + '<span class="custom-arrow"></span>';
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('toast'))
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
        @endif
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentUserRole = {{ Auth::user()->user_role }}; // pass current role from backend

    const divisionContainer = document.getElementById('division-container');
    const divisionSelect = document.getElementById('division');
    const userDropdown = document.getElementById('send_to');
    const userLevelSelect = document.getElementById('user_level');

    // Initially show division only if logged-in user is Admin(1) or Finance(7)
    if (currentUserRole === 1 || currentUserRole === 7) {
        divisionContainer.style.display = 'block';
    } else {
        divisionContainer.style.display = 'none';
    }

    function loadUsers() {
        let selectedLevel = parseInt(userLevelSelect.value);

        // Reset users dropdown
        userDropdown.innerHTML = '<option value="">Select User</option>';

        if (!selectedLevel) {
            userDropdown.disabled = true;
            return;
        }

        // Determine if division filter should be sent
        let divisionValue = (divisionContainer.style.display === 'block') ? divisionSelect.value : null;

        // Fetch users
        fetch(`{{ url('/get-users-by-level') }}/${selectedLevel}?division=${divisionValue}`)
            .then(response => response.json())
            .then(data => {
                userDropdown.disabled = false;

                data.forEach(user => {
                    let username = user.user_details?.name ?? user.email;
                    let option = `<option value="${user.id}">${username}</option>`;
                    userDropdown.insertAdjacentHTML('beforeend', option);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // When user level changes
    userLevelSelect.addEventListener('change', function() {
        let selectedLevel = parseInt(this.value);

        // Show division only for Admin/Finance and if target level is NOT 1 or 7
        if (currentUserRole === 1 || currentUserRole === 7) {
            if (selectedLevel === 1 || selectedLevel === 7) {
                divisionContainer.style.display = 'none';
            } else {
                divisionContainer.style.display = 'block';
            }
        } else {
            divisionContainer.style.display = 'none';
        }

        loadUsers();
    });

    // When division changes (only for Admin/Finance)
    if (currentUserRole === 1 || currentUserRole === 7) {
        divisionSelect.addEventListener('change', function() {
            loadUsers();
        });
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