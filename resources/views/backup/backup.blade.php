@include('layouts.dashboard-header')

<style>
    /* .header-btn {
        height: 50px;
        width: 50px;
        border: none;
        background-color: #FFF5F6;
    }

    .header-btn .fa-solid {
        color: #000000;
        height: 30px;
        width: 30px;
    } */

    .backup-description {
        font-family: "Poppins", sans-serif;
        font-size: 18px;
        font-weight: 400;
    }

    .stat-item,
    .stat {
        font-family: "Poppins", sans-serif;
        font-size: 18px;
        font-weight: 500;
    }


    .stat {
        font-weight: 400;
    }

    .completed {
        color: #12c912;
    }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Backup</h1>
        </div>

    </div>

    <hr class="red-line mt-1">

    <div class="my-5">
        <p class="backup-description">
            Welcome to the backup management section of Würth. Here, you can create backups that securely
            store all critical categories, including user details, finance records, and operational
            configurations. With a single click, ensure your data is safe and always recoverable.
        </p>
    </div>

    <div>
        <p class="stat-item mb-3">Backup Size : <span class="stat">1.2GB</span></p>
        <p class="stat-item mb-3">Backup Location : <span class="stat">Stored in AWS S3</span></p>
        <p class="stat-item mb-3">Last Backup : <span class="stat">Dec 11, 2024, 11:30 AM</span></p>
        <p class="stat-item mb-3">Last Backup Status : <span class="stat completed">Completed</span></p>
    </div>


    <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
        <button class="btn btn-danger submit">Backup Now</button>
    </div>
</div>

@include('layouts.footer2')

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
            Backup create successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>


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
