@include('layouts.dashboard-header')

<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">Advance Payment - {{ $payment->id }}</h1>
        @if($payment->attachment)
        <a href="{{ asset('storage/attachments/' . $payment->attachment) }}" download>
            <button class="black-action-btn-lg submit">
                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.0938 16L7.09375 11L8.49375 9.55L11.0938 12.15V4H13.0938V12.15L15.6938 9.55L17.0938 11L12.0938 16ZM6.09375 20C5.54375 20 5.07308 19.8043 4.68175 19.413C4.29042 19.0217 4.09442 18.5507 4.09375 18V15H6.09375V18H18.0938V15H20.0938V18C20.0938 18.55 19.8981 19.021 19.5068 19.413C19.1154 19.805 18.6444 20.0007 18.0938 20H6.09375Z" fill="white" />
                </svg>
                Receipt Download
            </button>
        </a>
        @endif
    </div>




    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="slip-details">
            <p>
                <span class="bold-text">ADM Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->admDetails?->name ?? $payment->admUser?->name ?? 'N/A' }}</span>
            </p>
            <p>
                <span class="bold-text">ADM No. :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->adm_id ?? 'N/A' }}</span>
            </p>
            <p>
                <span class="bold-text">Date :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->created_at->format('Y-m-d') ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Payment Amount :</span>
                <span class="slip-detail-text">&nbsp;Rs. {{ number_format($payment->payment_amount, 2) }}</span>
            </p>

            <p>
                <span class="bold-text">Customer Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->customerData?->name ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Customer ID :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->customerData?->customer_id ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Mobile Number :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->mobile_no ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Reason :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->reason ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Status :</span>
                @php
                $statusClass = match(strtolower($payment->status)) {
                'approved' => 'success-status-btn',
                'rejected' => 'danger-status-btn',
                default => 'grey-status-btn'
                };
                @endphp

                <span class="slip-detail-text">
                    <button id="status-display-btn" class="{{ $statusClass }}" style="cursor: default;">
                        {{ ucfirst($payment->status) }}
                    </button>
                </span>
            </p>

            <p>
                <span class="bold-text">Attachment Download:</span>

                @if ($payment->attachment)
                <a href="{{ route('advanced_payments.download', $payment->id) }}" download>
                    <button class="black-action-btn">Download</button>
                </a>
                @else
                <button class="black-action-btn" disabled>No File</button>
                @endif
            </p>

        </div>

        <div class="header-and-content-gap-lg"></div>

        <nav class="d-flex justify-content-center mt-5">
            <ul id="paymentSlipsPagination" class="pagination"></ul>
        </nav>
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

<!-- Confirmation Modal -->
<div id="confirm-status-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2); text-align:center;">

        <!-- Close button -->
        <button id="confirm-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:1rem 0; font-weight:600; color:#000;">Are you sure?</h4>

        <p style="margin:1rem 0; color:#6c757d;">Do you want to change the status to <span id="confirm-status-text" style="font-weight:600;"></span>?</p>

        <!-- Action buttons -->
        <div style="display:flex; justify-content:center; gap:1rem; margin-top:2rem;">
            <button id="confirm-no-btn" style="padding:0.5rem 1rem; border-radius:12px; border:1px solid #ccc; background:#fff; cursor:pointer;">No</button>
            <button id="confirm-yes-btn" style="padding:0.5rem 1rem; border-radius:12px; border:none; background:#2E7D32; color:#fff; cursor:pointer;">Yes</button>
        </div>
    </div>
</div>


<div class="action-button-lg-row">
    <a href="{{ url('advanced-payments') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>

    @if(strtolower($payment->status) !== 'approved')
    <!-- Show buttons only if status is NOT Approved -->
    <button class="red-action-btn-lg update-status-btn" data-id="{{ $payment->id }}" data-status="rejected">Reject</button>
    <button class="success-action-btn-lg update-status-btn" data-id="{{ $payment->id }}" data-status="approved">Approve</button>
    @endif
</div>


<!-- toast message -->
<script>
    document.addEventListener('click', function(e) {
        // Detect click on buttons with class "submit"
        if (e.target.classList.contains('submit') || e.target.closest('.submit')) {
            e.stopPropagation();

            // Show toast
            const toast = document.getElementById('user-toast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }
    });
</script>

<!-- for approve/reject buttons -->
<script>
    let selectedId = null;
    let selectedStatus = null;

    document.querySelectorAll('.update-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedId = this.getAttribute('data-id');
            selectedStatus = this.getAttribute('data-status');

            document.getElementById('confirm-status-text').innerText = selectedStatus;
            document.getElementById('confirm-status-modal').style.display = 'block';
        });
    });

    document.getElementById('confirm-modal-close').onclick = () => {
        document.getElementById('confirm-status-modal').style.display = 'none';
    };

    document.getElementById('confirm-no-btn').onclick = () => {
        document.getElementById('confirm-status-modal').style.display = 'none';
    };

    document.getElementById('confirm-yes-btn').onclick = function() {

        fetch("{{ route('advanced_payments.update_status') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id: selectedId,
                    status: selectedStatus
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {

                    // Update status button
                    const statusBtn = document.getElementById('status-display-btn');
                    statusBtn.innerText = data.new_status;
                    statusBtn.className = data.css_class;

                    // Hide ONLY if approved
                    if (selectedStatus === "approved") {
                        document.querySelectorAll('.update-status-btn').forEach(btn => {
                            btn.style.display = 'none';
                        });
                    }

                    // Close modal
                    document.getElementById('confirm-status-modal').style.display = 'none';
                }
            });
    };
</script>