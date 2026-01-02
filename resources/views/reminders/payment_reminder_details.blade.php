@include('layouts.dashboard-header')

<div class="container-fluid">
    <div class="main-wrapper">
        <div class="p-4 pt-0">
            <div class="d-flex justify-content-between align-items-center header-with-button">
                <h1 class="header-title">{{ $reminder->reminder_title ?? 'N/A' }}</h1>
                <!-- <button class="black-action-btn-lg submit">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12.0938 16L7.09375 11L8.49375 9.55L11.0938 12.15V4H13.0938V12.15L15.6938 9.55L17.0938 11L12.0938 16ZM6.09375 20C5.54375 20 5.07308 19.8043 4.68175 19.413C4.29042 19.0217 4.09442 18.5507 4.09375 18V15H6.09375V18H18.0938V15H20.0938V18C20.0938 18.55 19.8981 19.021 19.5068 19.413C19.1154 19.805 18.6444 20.0007 18.0938 20H6.09375Z"
                    fill="white" />
            </svg>
            Receipt Download
        </button> -->
            </div>

            <div class="styled-tab-main">
                <div class="header-and-content-gap-md"></div>
                <div class="slip-details">
                    <p>
                        <span class="bold-text">From :</span>
                        <span class="slip-detail-text">&nbsp;{{ $sender->userDetails->name ?? 'N/A' }}</span>
                    </p>

                    <p>
                        <span class="bold-text">To :</span>
                        <span class="slip-detail-text">&nbsp;{{ $receiver->userDetails->name ?? 'N/A' }}</span>
                    </p>


                    <p>
                        <span class="bold-text">Message :</span>
                        <span class="slip-detail-text">&nbsp;{{ $reminder->reason ?? 'N/A' }}</span>
                    </p>

                    <p>
                        <span class="bold-text">Trigger Date :</span>
                        <span class="slip-detail-text">&nbsp;{{ $reminder->reminder_date ?? 'N/A' }}</span>
                    </p>
                </div>


                <div class="header-and-content-gap-lg"></div>

                <nav class="d-flex justify-content-center mt-5">
                    <ul id="paymentSlipsPagination" class="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

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
                Downloaded successfully
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
                onclick="document.getElementById('user-toast').style.display='none';"></button>
        </div>
    </div>
</div>

<div class="col-12 d-flex justify-content-end division-action-btn gap-3">
    <a href="{{ url('reminders') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>
</div>

@include('layouts.footer2')
<!-- toast message -->
<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('submit')) {
            e.preventDefault();
            const toast = document.getElementById('user-toast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    });
</script>

<!-- for reject modal pop-up -->
<script>
    document.addEventListener('click', function(e) {
        // Approve button click
        if (e.target.classList.contains('success-action-btn-lg')) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('approve-modal').style.display = 'block';
            document.getElementById('approve-modal-input').value = '';
        }
        // Approve modal tick
        if (e.target.id === 'approve-modal-tick' || e.target.closest('#approve-modal-tick')) {
            document.getElementById('approve-modal').style.display = 'none';
        }
        // Approve modal close
        if (e.target.id === 'approve-modal-close') {
            document.getElementById('approve-modal').style.display = 'none';
        }

        // Reject button click
        if (e.target.classList.contains('red-action-btn-lg')) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('reject-modal').style.display = 'block';
            // Optionally clear input fields here if needed
            var inputs = document.querySelectorAll('#reject-modal input');
            inputs.forEach(function(input) {
                input.value = '';
            });
        }
        // Reject modal tick
        if (e.target.id === 'reject-modal-tick' || e.target.closest('#reject-modal-tick')) {
            document.getElementById('reject-modal').style.display = 'none';
        }
        // Reject modal close
        if (e.target.id === 'reject-modal-close') {
            document.getElementById('reject-modal').style.display = 'none';
        }
    });
</script>

</div>
@include('finance::layouts.footer2')