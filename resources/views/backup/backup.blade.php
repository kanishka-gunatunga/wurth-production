@include('layouts.dashboard-header')

<style>
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

    .failed {
        color: red;
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
            Welcome to the backup management section.
        </p>
    </div>

    <div>
        <p class="stat-item mb-3">
            Backup Size :
            <span class="stat">
                @if($latestBackup)
                    {{ number_format($latestBackup->size / 1048576, 2) }} MB
                @else
                    No backup found
                @endif
            </span>
        </p>

        <p class="stat-item mb-3">
            Backup Location :
            <span class="stat">
                Local Storage (storage/app)
            </span>
        </p>

        <p class="stat-item mb-3">
            Last Backup :
            <span class="stat">
                @if($latestBackup)
                    {{ $latestBackup->created_at->format('M d, Y, h:i A') }}
                @else
                    No backup yet
                @endif
            </span>
        </p>

        <p class="stat-item mb-3">
            Last Backup Status :
            <span class="stat 
                @if($latestBackup && $latestBackup->status === 'success') completed 
                @else failed 
                @endif">
                @if($latestBackup)
                    {{ ucfirst($latestBackup->status) }}
                @else
                    ---
                @endif
            </span>
        </p>
    </div>

    <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
        <button class="btn btn-danger submit" id="backupBtn">Backup Now</button>
    </div>
</div>

@include('layouts.footer2')

<!-- Toast -->
<div id="user-toast"
     class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
     role="alert" aria-live="assertive" aria-atomic="true"
     style="z-index: 9999; display: none; min-width: 320px;">
    <div class="d-flex align-items-center">
        <span class="toast-icon-circle d-flex align-items-center justify-content-center me-3">
            ✔
        </span>
        <div class="toast-body flex-grow-1">Backup created successfully</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>

<script>
document.getElementById('backupBtn').addEventListener('click', function () {

    this.disabled = true;
    this.innerText = "Backing up...";

    fetch("{{ url('backup') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {

        document.getElementById('backupBtn').disabled = false;
        document.getElementById('backupBtn').innerText = "Backup Now";

        if (data.success) {
            const toast = document.getElementById('user-toast');
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 3000);

            window.location.reload(); // Refresh to update backup info
        } else {
            alert("Backup failed: " + data.error);
        }
    });
});
</script>
