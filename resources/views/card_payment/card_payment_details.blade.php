@include('layouts.dashboard-header')
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">Card Payment No. - {{ $deposit->id }}</h1>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="slip-details">
            <p>
                <span class="bold-text">Date :</span>
                <span class="slip-detail-text">&nbsp;{{ $depositDate }}</span>
            </p>
            <p>
                <span class="bold-text">ADM No. :</span>
                <span class="slip-detail-text">&nbsp;{{ $admNumber }}</span>
            </p>
            <p>
                <span class="bold-text">ADM Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $admName }}</span>
            </p>
            @foreach($invoicePayments as $invoicePayment)
            <div class="mb-2">
                <p>
                    <span class="bold-text">Customer ID :</span>
                    <span class="slip-detail-text">&nbsp;{{ $invoicePayment->invoice?->customer?->customer_id ?? 'N/A' }}</span>
                </p>
                <p>
                    <span class="bold-text">Customer Name :</span>
                    <span class="slip-detail-text">&nbsp;{{ $invoicePayment->invoice?->customer?->name ?? 'N/A' }}</span>
                </p>
            </div>
            @endforeach
            <p>
                <span class="bold-text"> Total Amount :</span>
                <span class="slip-detail-text">&nbsp;Rs. {{ number_format($totalAmount, 2) }}</span>
            </p>

            <p>
                <span class="bold-text">Status :</span>
                @php
                $statusVal = strtolower($status ?? '');

                $statusClass = match($statusVal) {
                'approved' => 'success-status-btn',
                'pending' => 'grey-status-btn',
                'voided' => 'danger-status-btn',
                'rejected' => 'danger-status-btn',
                default => 'grey-status-btn',
                };

                 $statusLabel = str_replace('_', ' ', $status);
                    $statusLabel = ucfirst($statusLabel); // Capitalize first letter
                @endphp
                <span class="slip-detail-text">
                    <button class="{{ $statusClass }}">{{ $statusLabel }}</button>
                </span>
            </p>
@if(in_array('deposits-card-payment-download', session('permissions', [])))
            <p>
                <span class="bold-text">Attachment Download :</span>
                @if($deposit->attachment_path)
                <a href="{{ url('card-payments/download', $deposit->id) }}">
                    <button class="black-action-btn">Download</button>
                </a>
                @else
                <button class="black-action-btn" disabled>No File</button>
                @endif
            </p>
@endif
        </div>
    </div>


     <div class="header-and-content-gap-lg"></div>
        <div class="table-responsive">
            <table class="table unlock-column-table">
                <thead>
                    <tr>
                        <th>Receipt Number</th>
                        <th>Customer Name</th>
                        <th>Customer ID</th>
                        <th>Customer Paid Date</th>
                        <th>Customer Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                  @forelse($invoicePayments as $invoicePayment)
                        <tr>
                            <td>{{ $invoicePayment->id }}</td>
                            <td>{{ $invoicePayment->invoice?->customer?->name ?? 'N/A' }}</td>
                            <td>{{ $invoicePayment->invoice?->customer?->customer_id ?? 'N/A' }}</td>
                            <td>{{ $invoicePayment->created_at?->format('Y-m-d') ?? 'N/A' }}</td>
                            <td>{{ $invoicePayment->final_payment ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No records found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $invoicePayments->links() }}
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

<!-- Confirmation Modal -->
<div id="confirm-status-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2); text-align:center;">

        <!-- Close button -->
        <button id="confirm-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:1rem 0; font-weight:600; color:#000;">Are you sure?</h4>

        <p style="margin:1rem 0; color:#6c757d;">Do you want to change the status to <span id="confirm-status-text" style="font-weight:600;"></span>?</p>
         <div id="remark-box" style="display:none; margin-top:1rem; text-align:left;">
    <label style="font-weight:600;">Remark (required when declining)</label>
    <textarea id="decline-remark"
        style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;"
        rows="3"
        placeholder="Enter decline remark"></textarea>
</div>
        <!-- Action buttons -->
        <div style="display:flex; justify-content:center; gap:1rem; margin-top:2rem;">
            <button id="confirm-no-btn" style="padding:0.5rem 1rem; border-radius:12px; border:1px solid #ccc; background:#fff; cursor:pointer;">No</button>
            <button id="confirm-yes-btn" style="padding:0.5rem 1rem; border-radius:12px; border:none; background:#2E7D32; color:#fff; cursor:pointer;">Yes</button>
        </div>
    </div>
</div>


<div class="action-button-lg-row">
    <a href="{{ url('card-payments') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>
    @if(strtolower($status) !== 'approved')
    <!-- Show buttons only if status is NOT Approved -->
      @if(in_array('deposits-card-payment-status', session('permissions', [])))
    <button class="red-action-btn-lg update-status-btn" data-id="{{ $deposit->id }}" data-status="rejected">Reject</button>
    <button class="success-action-btn-lg update-status-btn" data-id="{{ $deposit->id }}" data-status="approved">Approve</button>
    @endif
    @endif
</div>
@include('layouts.footer2')
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

<!-- for approve/reject buttons -->
<script>
    let currentStatusButton = null;
    let newStatus = '';

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('update-status-btn')) {
            e.preventDefault();

            const button = e.target;
            const depositId = button.dataset.id;
            const newStatus = button.dataset.status;

            // Show confirmation modal
            document.getElementById('confirm-status-text').innerText = newStatus;
            if (newStatus.toLowerCase() === 'rejected' || newStatus.toLowerCase() === 'voided') {
                document.getElementById('remark-box').style.display = 'block';
            } else {
                document.getElementById('remark-box').style.display = 'none';
            }
            const modal = document.getElementById('confirm-status-modal');
            modal.style.display = 'block';

            const yesBtn = document.getElementById('confirm-yes-btn');
            const noBtn = document.getElementById('confirm-no-btn');
            const closeBtn = document.getElementById('confirm-modal-close');

            function closeModal() {
                modal.style.display = 'none';
            }
            noBtn.onclick = closeModal;
            closeBtn.onclick = closeModal;

            yesBtn.onclick = function() {
                modal.style.display = 'none';
                let remark = document.getElementById('decline-remark').value.trim();
                // Send AJAX request to update status
                fetch(`{{ url('/card-payments/update-status') }}/${depositId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: newStatus,remark: remark
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Update status button visually
                            const statusBtn = document.querySelector('.slip-detail-text button');
                            statusBtn.innerText = data.status;
                            statusBtn.className = data.status.toLowerCase() === 'approved' ? 'success-status-btn' : 'danger-status-btn';

                            // Hide buttons if approved, else keep them visible
                            if (data.status.toLowerCase() === 'approved') {
                                document.querySelectorAll('.update-status-btn').forEach(btn => btn.style.display = 'none');
                            }
                        } else {
                            alert('Failed to update status.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error updating status.');
                    });
            };
        }
    });
</script>

@include('finance::layouts.footer2')