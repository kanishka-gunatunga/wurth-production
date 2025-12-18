@include('adm::layouts.header')
<style>
    .container {
        max-width: 960px;
        margin: 0 auto;
    }

    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 24px;
        margin-bottom: 16px;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: transparent !important;
        border-bottom: none !important;
    }

    .card-header svg {
        width: 20px;
        height: 20px;
        color: #666;
    }

    /* Force 2-column layout everywhere */
    .grid-two-col {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    /* Stack only inside the left/right groups in Inquiry Info */
    .stack-left,
    .stack-right {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .field-label {
        font-family: Poppins;
        font-weight: 400;
        font-size: 14px;
        color: #666;
        margin-bottom: 4px;
    }

    .field-value {
        font-size: 15px;
        color: #1a1a1a;
        font-weight: 500;
    }

    .reason-text {
        font-size: 14px;
        color: #333;
        line-height: 1.6;
    }

    .attachment-box {
        background: #F9FAFB;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .attachment-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .attachment-icon {
        width: 40px;
        height: 40px;
        background: #dbeafe;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .attachment-icon svg {
        width: 20px;
        height: 20px;
        color: #2563eb;
    }

    .attachment-title {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 2px;
    }

    .attachment-subtitle {
        font-family: Poppins;
        font-size: 12px;
        color: #6A7282;
    }

    .btn-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
    }
</style>
<div class="content">
    <div class="main-wrapper">
        <div class="d-flex justify-content-between align-items-center header-with-button">
            <div class="left-title-group">
                <h1 class="header-title">
                    Advance Payment Details
                </h1>
            </div>

            <span class="slip-detail-text">
                @php
                $status = strtolower(trim($payment->status));
                @endphp

                @if($status === 'pending')
                <span class="badge bg-warning">Pending</span>
                @elseif($status === 'approved')
                <span class="badge bg-success">Approved</span>
                @elseif($status === 'rejected')
                <span class="badge bg-danger">Rejected</span>
                @else
                <span class="badge bg-secondary">Unknown</span>
                @endif
            </span>
        </div>

        <hr class="red-line mt-2">

        <div class="styled-tab-main">
            <div class="header-and-content-gap-md"></div>
            <div class="slip-details">
                <div class="container">
                    <!-- Advance payment Information -->
                    <div class="card">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M12.5007 1.66699H5.00065C4.55862 1.66699 4.1347 1.84259 3.82214 2.15515C3.50958 2.46771 3.33398 2.89163 3.33398 3.33366V16.667C3.33398 17.109 3.50958 17.5329 3.82214 17.8455C4.1347 18.1581 4.55862 18.3337 5.00065 18.3337H15.0007C15.4427 18.3337 15.8666 18.1581 16.1792 17.8455C16.4917 17.5329 16.6673 17.109 16.6673 16.667V5.83366L12.5007 1.66699Z" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M11.666 1.66699V5.00033C11.666 5.44235 11.8416 5.86628 12.1542 6.17884C12.4667 6.4914 12.8907 6.66699 13.3327 6.66699H16.666" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.33268 7.5H6.66602" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.3327 10.833H6.66602" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13.3327 14.167H6.66602" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="bold-text">Advance Payment Information</span>
                        </div>

                        <div class="grid-two-col mt-2">

                            <!-- LEFT -->
                            <div class="stack-left">
                                <div>
                                    <div class="field-label">Date</div>
                                    <span class="slip-detail-text">&nbsp;{{ $payment->date }}</span>
                                </div>

                                <div>
                                    <div class="field-label">Customer Name</div>
                                    <span class="slip-detail-text">&nbsp;{{ $payment->customerData->name ?? 'N/A' }}</span>
                                </div>

                                <div>
                                    <div class="field-label">Payment Amount</div>
                                    <span class="slip-detail-text">&nbsp;Rs. {{ number_format($payment->payment_amount, 2) }}</span>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="stack-right">

                                <div>
                                    <div class="field-label">Mobile Number</div>
                                    <span class="slip-detail-text">&nbsp;{{ $payment->mobile_no }}</span>
                                </div>

                                <div>
                                    <div class="field-label">E-Signature</div>

                                    @if($payment->customer_signature)
                                    <img src="{{ asset('uploads/adm/advanced_payments/signatures/' . $payment->customer_signature) }}"
                                        alt="Signature"
                                        style="width:150px; border:1px solid #ccc; border-radius:4px;">
                                    @else
                                    <span class="slip-detail-text">N/A</span>
                                    @endif
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Reason & Description -->
                    <div class="card">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_4858_14212)">
                                    <path d="M9.99935 18.3337C14.6017 18.3337 18.3327 14.6027 18.3327 10.0003C18.3327 5.39795 14.6017 1.66699 9.99935 1.66699C5.39698 1.66699 1.66602 5.39795 1.66602 10.0003C1.66602 14.6027 5.39698 18.3337 9.99935 18.3337Z" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 6.66699V10.0003" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 13.333H10.0083" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_4858_14212">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span class="bold-text">Reason & Description</span>
                        </div>

                        <div class="mt-2">
                            <div class="field-label">Reason</div>
                            <span class="slip-detail-text">
                                &nbsp;{{ $payment->reason }}
                            </span>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="card">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M12.5007 1.66699H5.00065C4.55862 1.66699 4.1347 1.84259 3.82214 2.15515C3.50958 2.46771 3.33398 2.89163 3.33398 3.33366V16.667C3.33398 17.109 3.50958 17.5329 3.82214 17.8455C4.1347 18.1581 4.55862 18.3337 5.00065 18.3337H15.0007C15.4427 18.3337 15.8666 18.1581 16.1792 17.8455C16.4917 17.5329 16.6673 17.109 16.6673 16.667V5.83366L12.5007 1.66699Z" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M11.666 1.66699V5.00033C11.666 5.44235 11.8416 5.86628 12.1542 6.17884C12.4667 6.4914 12.8907 6.66699 13.3327 6.66699H16.666" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10 15V10" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7.5 12.5L10 15L12.5 12.5" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="bold-text">Attachments</span>
                        </div>

                        <div class="attachment-box mt-2">
                            <div class="attachment-info">
                                <div class="attachment-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M12.5007 1.66699H5.00065C4.55862 1.66699 4.1347 1.84259 3.82214 2.15515C3.50958 2.46771 3.33398 2.89163 3.33398 3.33366V16.667C3.33398 17.109 3.50958 17.5329 3.82214 17.8455C4.1347 18.1581 4.55862 18.3337 5.00065 18.3337H15.0007C15.4427 18.3337 15.8666 18.1581 16.1792 17.8455C16.4917 17.5329 16.6673 17.109 16.6673 16.667V5.83366L12.5007 1.66699Z" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M11.666 1.66699V5.00033C11.666 5.44235 11.8416 5.86628 12.1542 6.17884C12.4667 6.4914 12.8907 6.66699 13.3327 6.66699H16.666" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.33268 7.5H6.66602" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.3327 10.833H6.66602" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.3327 14.167H6.66602" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <div>
                                    <span class="slip-detail-text">Advance Payment Document</span>
                                    <div class="attachment-subtitle">Click to download attachment</div>
                                </div>
                            </div>

                            @if ($payment->attachment)
                            <a href="{{ route('advance_payment.download', $payment->id) }}">
                                <button class="black-action-btn">
                                    Download
                                </button>
                            </a>
                            @else
                            <button class="black-action-btn" disabled>No File</button>
                            @endif
                        </div>

                    </div>

                    <a href="{{ url('adm/advance-payments') }}" class="black-action-btn-lg" style="text-decoration: none;">Close</a>
                </div>
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

@include('adm::layouts.footer')