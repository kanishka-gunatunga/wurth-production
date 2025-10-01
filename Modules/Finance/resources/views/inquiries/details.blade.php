@include('finance::layouts.header')
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">Inquiry Ref No. - REF-1001</h1>
        <!-- <button class="black-action-btn-lg submit">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12.0938 16L7.09375 11L8.49375 9.55L11.0938 12.15V4H13.0938V12.15L15.6938 9.55L17.0938 11L12.0938 16ZM6.09375 20C5.54375 20 5.07308 19.8043 4.68175 19.413C4.29042 19.0217 4.09442 18.5507 4.09375 18V15H6.09375V18H18.0938V15H20.0938V18C20.0938 18.55 19.8981 19.021 19.5068 19.413C19.1154 19.805 18.6444 20.0007 18.0938 20H6.09375Z"
                    fill="white" />
            </svg>
            Download
        </button> -->
    </div>




    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="slip-details">
            <p>
                <span class="bold-text">Inquiry Type :</span><span class="slip-detail-text">&nbsp;Payment issue</span>
            </p>
            
            <p>
                <span class="bold-text">Date :</span><span class="slip-detail-text">&nbsp;2024.12.26</span>
            </p>

            <p>
                <span class="bold-text">ADM No. :</span><span class="slip-detail-text">&nbsp;4585689557</span>
            </p>

            <p>
                <span class="bold-text">ADM Name :</span><span class="slip-detail-text">&nbsp;L.K
                    Perera</span>
            </p>

            <p>
                <span class="bold-text"> Customer Name :</span><span class="slip-detail-text">&nbsp;Alice Smith</span>
            </p>

            <p>
                <span class="bold-text"> Invoice Number :</span><span class="slip-detail-text">&nbsp;INV-1001</span>
            </p>

            <p>
                <span class="bold-text"> Reason :</span><span class="slip-detail-text">&nbsp;Incorrect amount</span>
            </p>

            <div class="mb-4 d-flex align-items-center">
                <label for="status-dropdown" class="form-label custom-input-label me-3 mb-0">
                    Status :
                </label>
                <div class="dropdown">
                    <button class="btn custom-dropdown text-start" type="button" id="status-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 200px;">
                        Choose Status
                        <span class="custom-arrow"></span>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="status-dropdown">
                        <li><a class="dropdown-item" href="#" data-value="Pending">Pending</a></li>
                        <li><a class="dropdown-item" href="#" data-value="Sorted">Sorted</a></li>
                    </ul>
                </div>
            </div>

            <p>
                <span class="bold-text">Attachment Download :</span>
                <a href="invoice_1001.htm" download>
                    <button class="black-action-btn">Download</button>
                </a>
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

<!-- @section('bottom-bar') -->
<div class="py-3">
    <div class="action-button-lg-row">
        <a href="{{ url('inquiries') }}" class="grey-action-btn-lg" style="text-decoration: none;">
            Back
        </a>



        <button class="red-action-btn-lg">

            Reject
        </button>

        <button class="success-action-btn-lg">

            Approve
        </button>
    </div>
</div>

<!-- @endsection -->

@include('finance::layouts.footer')