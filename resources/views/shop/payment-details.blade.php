@include('layouts.dashboard-header')

<style>
    .payment-summary-container {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #F3F4F6;
    }

    .section-title {
        font-weight: 600;
        font-size: 18px;
        color: #111827;
        margin-bottom: 25px;
    }

    .customer-name {
        font-weight: 600;
        font-size: 20px;
        color: #374151;
        margin-bottom: 20px;
    }

    /* Checkbox & List Styles */
    .invoice-list-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #E5E7EB;
    }

    .form-check-input {
        width: 22px;
        height: 22px;
        border: 2px solid #D1D5DB;
        border-radius: 4px;
        margin-right: 15px;
    }

    .form-check-input:checked {
        background-color: #EF4444;
        border-color: #EF4444;
    }

    .invoice-no {
        font-size: 18px;
        font-weight: 500;
        color: #111827;
    }

    /* Summary Box Styles */
    .summary-box {
        /* background: #F9FAFB; */
        border: 1px solid #00000026;
        border-radius: 12px;
        padding: 24px;
    }

    .summary-category {
        margin-bottom: 24px;
        border-bottom: 1px solid #E5E7EB;
        padding-bottom: 15px;
    }

    .summary-category:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .category-title {
        font-weight: 600;
        font-size: 16px;
        color: #111827;
        margin-bottom: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: flex-start;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .summary-label {
        font-weight: 600;
        color: #374151;
        width: 100px;
    }

    .summary-value {
        color: #CC0000;
        font-weight: 500;
    }

    .summary-invoices {
        color: #4B5563;
        font-weight: 400;
    }

    /* Final Amount Card */
    .final-amount-card {
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 30px;
        margin-top: 30px;
        text-align: right;
    }

    .final-label {
        font-size: 14px;
        color: #6B7280;
        margin-bottom: 5px;
    }

    .final-value {
        font-size: 28px;
        font-weight: 700;
        color: #CC0000;
    }

    /* Footer Buttons */
    .btn-cancel {
        background: #000;
        color: #fff;
        border: none;
        padding: 12px 60px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
    }

    .btn-submit {
        background: #EF4444;
        color: #fff;
        border: none;
        padding: 12px 60px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
    }
</style>

<div class="main-wrapper">
    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Collections</h1>
        </div>
    </div>

    <div class="styled-tab-main"></div>
    <!-- Divider line matching Shop theme -->
    <div class="header-and-content-gap-lg"></div>

    <div class="payment-summary-container">
        <div class="row g-5" style="border-bottom: 1px solid #D3D3D3; padding-bottom: 20px;">
            <!-- Left Side: Selected Customers -->
            <div class="col-md-5">
                <p class="section-title">Selected Customers</p>

                <div class="customer-section mb-5">
                    <h2 class="customer-name">Ranuka Danushaka</h2>

                    <div class="invoice-list">
                        <div class="invoice-list-item">
                            <input class="form-check-input" type="checkbox" id="inv-001" checked>
                            <label class="invoice-no" for="inv-001">INV-001</label>
                        </div>
                        <div class="invoice-list-item">
                            <input class="form-check-input" type="checkbox" id="inv-002" checked>
                            <label class="invoice-no" for="inv-002">INV-002</label>
                        </div>
                        <div class="invoice-list-item">
                            <input class="form-check-input" type="checkbox" id="inv-003" checked>
                            <label class="invoice-no" for="inv-003">INV-003</label>
                        </div>
                        <div class="invoice-list-item">
                            <input class="form-check-input" type="checkbox" id="inv-004" checked>
                            <label class="invoice-no" for="inv-004">INV-004</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Payment Summary -->
            <div class="col-md-7">
                <div class="summary-box h-100">
                    <p class="category-title" style="font-size: 18px;">Payment Summary</p>

                    <!-- Cash Payment Section -->
                    <div class="summary-category">
                        <p class="category-title">Cash Payment</p>
                        <div class="summary-row">
                            <span class="summary-label">Expect to pay</span>
                            <span class="summary-value">: Rs. 100,000.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Invoice No</span>
                            <span class="summary-invoices">: INV-001, INV-002</span>
                        </div>
                    </div>

                    <!-- Fund Transfer Section -->
                    <div class="summary-category">
                        <p class="category-title">Fund Transfer</p>
                        <div class="summary-row">
                            <span class="summary-label">Expect to pay</span>
                            <span class="summary-value">: Rs. 400,000.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Invoice No</span>
                            <span class="summary-invoices">: INV-003, INV-004</span>
                        </div>
                    </div>

                    <!-- Card Payment Section -->
                    <div class="summary-category">
                        <p class="category-title">Card Payment</p>
                        <div class="summary-row">
                            <span class="summary-label">Expect to pay</span>
                            <span class="summary-value">: Rs. 100,000.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Invoice No</span>
                            <span class="summary-invoices">: INV-005</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 justify-content-end">
            <div class="col-6">
                <div class="final-amount-card" style="justify-items: center">
                    <p class="final-label">Final Payable Amount</p>
                    <p class="final-value">Rs. 600,000.00</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="d-flex justify-content-end gap-3 mt-5">
    <button class="btn-cancel" onclick="window.location.href='{{ route('add_new_payment') }}'">Cancel</button>
    <button class="btn-submit" onclick="finalSubmit()">Submit</button>
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
            Payment confirmed successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>

<script>
    function finalSubmit() {
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        
        setTimeout(() => {
            window.location.href = "{{ route('collections') }}";
        }, 3000);
    }
</script>

@include('layouts.footer2')