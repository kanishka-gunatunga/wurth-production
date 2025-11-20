@include('finance::layouts.header')
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">
            Fund Transfer No. - {{ $deposit->transfer_reference_number }}
        </h1>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="slip-details">
            <p>
                <span class="bold-text">Date :</span>
                <span class="slip-detail-text">&nbsp;{{ $deposit->transfer_date }}</span>
            </p>
            <p>
                <span class="bold-text">ADM No. :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $deposit->invoice->customer->adm ?? '-' }}
                </span>
            </p>
            <p>
                <span class="bold-text">ADM Name :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $deposit->invoice->customer->admDetails->name ?? '-' }}
                </span>
            </p>

            <p>
                <span class="bold-text">Customer ID :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $deposit->invoice->customer->customer_id ?? '-' }}
                </span>
            </p>
            <p>
                <span class="bold-text">Customer Name :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $deposit->invoice->customer->name ?? '-' }}
                </span>
            </p>
            <p>
                <span class="bold-text">Transfer Reference Number :</span>
                <span class="slip-detail-text">&nbsp;{{ $deposit->transfer_reference_number }}</span>
            </p>

            <p>
                <span class="bold-text">Total Amount :</span>
                <span class="slip-detail-text">&nbsp;Rs. {{ number_format($deposit->final_payment, 2) }}</span>
            </p>

            <p>
                <span class="bold-text">Status :</span>
                <span class="slip-detail-text">&nbsp;
                    @php
                    $status = strtolower($deposit->status ?? '');
                    $label = $status === 'pending' ? 'Pending' : ucfirst($status);

                    $statusClass = match($label) {
                    'Approved' => 'success-status-btn',
                    'Pending' => 'grey-status-btn',
                    'Rejected' => 'danger-status-btn',
                    default => 'grey-status-btn',
                    };
                    @endphp

                    <button class="{{ $statusClass }}">{{ $label }}</button>
                </span>
            </p>

            <p>
                <span class="bold-text">Attachment Download :</span>
                @if($deposit->screenshot)
                <a href="{{ asset('uploads/payments/' . $deposit->screenshot) }}">
                    <button class="black-action-btn">Download</button>
                </a>
                @else
                <button class="black-action-btn" disabled>No File</button>
                @endif
            </p>

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

<!-- Approve Modal -->
<div id="approve-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2);">

        <!-- Close button -->
        <button id="approve-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:0 0 0.5rem 0; font-weight:600; color:#000;">Payment Approval</h4>

        <!-- Subtitle -->
        <p style="margin:0 0 1.5rem 0; color:#6c757d; font-size:0.95rem; line-height:1.4;">
            You're about to confirm this payment. Please provide a reason for approval.
        </p>

        <!-- Textarea with button inside -->
        <div style="position:relative;">
            <textarea id="approve-modal-input" rows="3" placeholder="Enter your reason here...."
                style="width:100%; border:1px solid #ddd; border-radius:12px; padding:0.75rem 3rem 0.75rem 1rem; font-size:0.95rem; resize:none; outline:none;"></textarea>

            <!-- Green tick button -->
            <button id="approve-modal-tick" style="position:absolute; bottom:10px; right:10px; background:#2E7D32; border:none; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M7 12.5l3 3 7-7" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2);">

        <!-- Close button -->
        <button id="reject-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:0 0 0.5rem 0; font-weight:600; color:#000;">Payment Rejection</h4>

        <!-- Subtitle -->
        <p style="margin:0 0 1.5rem 0; color:#6c757d; font-size:0.95rem; line-height:1.4;">
            You're about to reject this payment. Please provide a reason for rejection.
        </p>

        <!-- Input fields -->
        <div style="display:flex; flex-direction:column; gap:1rem; margin-bottom:1rem;">
            <input type="text" placeholder="Header"
                style="width:100%; border:1px solid #ddd; border-radius:20px; padding:0.6rem 1rem; font-size:0.95rem; outline:none;">
            <input type="text" placeholder="GL"
                style="width:100%; border:1px solid #ddd; border-radius:20px; padding:0.6rem 1rem; font-size:0.95rem; outline:none;">
        </div>

        <!-- Red tick button -->
        <div style="display:flex; justify-content:flex-end;">
            <button id="reject-modal-tick" style="background:#CC0000; border:none; border-radius:50%; width:40px; height:40px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M7 12.5l3 3 7-7" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>
</div>


@section('footer-buttons')
<a href="{{ route('fund_transfers.index') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>
<button class="red-action-btn-lg update-status-btn" data-id="{{ $deposit->id }}" data-status="rejected">Reject</button>
<button class="success-action-btn-lg update-status-btn" data-id="{{ $deposit->id }}" data-status="approved">Approve</button>
@endsection

<!-- dropdown script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const button = dropdown.querySelector('.custom-dropdown');
            const items = dropdown.querySelectorAll('.dropdown-item');

            items.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault(); // stop page jump
                    const selectedText = this.getAttribute("data-value") || this.textContent.trim();
                    button.innerHTML = selectedText + '<span class="custom-arrow"></span>';
                });
            });
        });
    });
</script>

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

@include('finance::layouts.footer2')