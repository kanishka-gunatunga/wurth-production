@include('layouts.dashboard-header')

<div class="container-fluid">
    <div class="main-wrapper">
        <div class="p-4 pt-0">
            <div class="col-lg-6 col-12">
                <h1 class="header-title">Create Notification</h1>
            </div>
            <hr class="red-line">

            <!-- ✅ Connected Form -->
            <form action="{{ route('reminders.store') }}" method="POST">
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
                            <label for="reminder_type" class="form-label custom-input-label">Send to (User Level)</label>
                            <select name="reminder_type" id="reminder_type" class="form-control custom-input">
                                <option value="">Select User Level</option>
                                <option value="Head of Division" {{ old('reminder_type') == 'Head of Division' ? 'selected' : '' }}>Head of Division</option>
                                <option value="Finance Manager" {{ old('reminder_type') == 'Finance Manager' ? 'selected' : '' }}>Finance Manager</option>
                                <option value="Recovery Manager" {{ old('reminder_type') == 'Recovery Manager' ? 'selected' : '' }}>Recovery Manager</option>
                                <option value="System Administrator" {{ old('reminder_type') == 'System Administrator' ? 'selected' : '' }}>System Administrator</option>
                                <option value="Regional Sales Manager" {{ old('reminder_type') == 'Regional Sales Manager' ? 'selected' : '' }}>Regional Sales Manager</option>
                                <option value="ADM" {{ old('reminder_type') == 'ADM' ? 'selected' : '' }}>ADM</option>
                                <option value="Area Sales Manager" {{ old('reminder_type') == 'Area Sales Manager' ? 'selected' : '' }}>Area Sales Manager</option>
                                <option value="Team Leader" {{ old('reminder_type') == 'Team Leader' ? 'selected' : '' }}>Team Leader</option>
                            </select>
                            @if($errors->has('reminder_type'))
                            <div class="alert alert-danger mt-2">{{ $errors->first('reminder_type') }}</div>
                            @endif
                        </div>

                        <!-- Send To (User) -->
                        <div class="mb-4">
                            <label for="send_to" class="form-label custom-input-label">Send to (User)</label>
                            <select name="send_to" id="send_to" class="form-control custom-input">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('send_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->userDetails->name ?? $user->email }}
                                </option>
                                @endforeach
                            </select>
                            @if($errors->has('send_to'))
                            <div class="alert alert-danger mt-2">{{ $errors->first('send_to') }}</div>
                            @endif
                        </div>

                        <!-- Reminder Date -->
                        <div class="mb-4">
                            <label for="reminder_date" class="form-label custom-input-label">Trigger Date</label>
                            <input type="date" class="form-control custom-input" id="reminder_date"
                                name="reminder_date" value="{{ old('reminder_date') }}" required>
                            @if($errors->has('reminder_date'))
                            <div class="alert alert-danger mt-2">{{ $errors->first('reminder_date') }}</div>
                            @endif
                        </div>
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
                    <a href="{{ url('notifications') }}" class="btn btn-dark cancel">Cancel</a>
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

</div>





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
    // Cancel button redirect
    document.querySelector('.cancel').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'notifications';
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


</div>