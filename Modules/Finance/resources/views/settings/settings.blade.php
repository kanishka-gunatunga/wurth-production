@include('finance::layouts.header')
<div class="main-wrapper d-flex flex-column align-items-center py-5">

    <!-- Profile Avatar -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/profile-icon.svg') }}"
            alt="Profile Avatar"
            class="rounded-circle"
            style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #cc0000;">
    </div>

    <!-- Name & Designation -->
    <div class="text-center mb-5">
        <h2 style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 28px; font-weight: 600;">
            Sofia Emilia
        </h2>
        <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 22px; font-weight: 500;">
            Admin
        </p>
    </div>

    <!-- Profile Information -->
    <div class="w-75">
        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    Name:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    Sofia Emilia
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    User Role:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    Admin
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    Phone Number:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    +94 77 123 4567
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    Email:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    sofia.emilia@example.com
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    ADM Number:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    ADM1001
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    Supervisor:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    John Doe
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    2nd Level Supervisor:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    John Doe
                </p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <p style="font-family: 'Poppins', sans-serif; color: #cc0000; font-size: 25px; font-weight: 600;">
                    Division:
                </p>
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Poppins', sans-serif; color: #484848; font-size: 25px; font-weight: 600;">
                    Division 1
                </p>
            </div>
        </div>
    </div>

</div>

@section('footer-buttons')
<a href="{{ url('finance') }}" class="black-action-btn-lg" style="text-decoration: none;">Back</a>
<button class="red-action-btn-lg submit">
    Submit
</button>
@endsection

<!-- Toast message -->
<div id="user-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
    role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999; display: none; min-width: 320px;">
    <div class="d-flex align-items-center">
        <span class="toast-icon-circle d-flex align-items-center justify-content-center me-3">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#fff" />
                <path d="M7 12.5l3 3 7-7" stroke="#28a745" stroke-width="2" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
        <div class="toast-body flex-grow-1">
            Profile updated successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>



<script>
    // Cancel button redirect
    document.querySelector('.cancel').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '/admin-dashboard';
    });
</script>

<script>
    // Show toast on submit
    document.querySelector('.submit').addEventListener('click', function(e) {
        e.preventDefault();
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    });
</script>

@include('finance::layouts.footer2')