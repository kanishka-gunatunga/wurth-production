@include('layouts.dashboard-header')

<style>
    .deposit-container {
        padding: 20px;
    }

    .summary-card {
        background: #FFFFFF;
        border: 1px solid #9D9D9D;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        flex: 1;
    }

    .summary-card-title {
        font-size: 13px;
        color: #AAB6C1;
        margin-bottom: 8px;
    }

    .summary-card-amount {
        font-size: 20px;
        font-weight: 600;
        color: #000000;
    }

    .section-subtitle {
        color: #CC0000;
        font-weight: 600;
        font-size: 20px;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .form-group-label {
        font-weight: 500;
        font-size: 14px;
        color: #000000;
        margin-bottom: 8px;
        display: block;
    }

    .deposit-dropdown {
        width: 100%;
        padding: 12px;
        border: 1px solid #D0D5DD;
        border-radius: 8px;
        appearance: none;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") no-repeat right 12px center;
        background-size: 16px;
        background-color: #fff;
    }

    .deposit-summary-box {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        height: 100%;
    }

    .search-wrapper {
        position: relative;
        margin-bottom: 20px;
    }

    .search-input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
    }

    .customer-item {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid #EEEEEE;
    }

    .customer-item:last-child {
        border-bottom: none;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        margin-right: 15px;
        margin-top: 4px;
    }

    .customer-details p {
        margin: 0;
        font-size: 13px;
        color: #000000;
        line-height: 1.6;
    }

    .customer-details strong {
        color: #000000;
        font-weight: 500;
    }

    .upload-area {
        background: #FFFFFF;
        border: 1px solid #EAECF0;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .upload-icon {
        width: 48px;
        height: 48px;
        background: #F3F4F6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .upload-text {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .add-another-slip {
        color: #CC0000;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 25px;
    }

    .btn-submit-payment {
        background: #CC0000;
        color: #fff;
        border: none;
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
    }

    .upload-item {
        position: relative;
    }

    .remove-upload-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #FEE2E2;
        color: #DC2626;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        z-index: 10;
        font-size: 14px;
        transition: background 0.2s;
    }

    .remove-upload-btn:hover {
        background: #FECACA;
    }

    .main-wrapper {
        /* background-color: #fff; */
        padding: 30px;
        border-radius: 12px;
    }
</style>

<div class="main-wrapper">
    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-12">
            <h1 class="header-title">Deposit</h1>
        </div>
    </div>

    <div class="styled-tab-main">
        <div class="deposit-container">

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="summary-card">
                        <p class="summary-card-title">Daily Cash Deposit</p>
                        <p class="summary-card-amount">Rs. 120,000</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="summary-card">
                        <p class="summary-card-title">Daily Hand over to Finance</p>
                        <p class="summary-card-amount">Rs. 560,000</p>
                    </div>
                </div>
            </div>

            <h2 class="section-subtitle">Daily Deposit</h2>

            <div class="row g-5">

                <div class="col-md-7">
                    <div class="mb-4">
                        <label class="form-group-label">Select Deposit Method</label>
                        <select class="deposit-dropdown">
                            <option>Cash Deposit</option>
                            <option>Hand Over to Finance - Cash</option>
                        </select>
                    </div>

                    <div class="deposit-summary-box">
                        <p class="form-group-label">Deposit Summary</p>
                        <div class="search-wrapper">
                            <span class="search-icon"><i class="fa fa-search"></i></span>
                            <input type="text" class="search-input" placeholder="Search">
                        </div>

                        <p class="form-group-label" style="border-bottom: 1px solid #EAECF0; padding-bottom: 15px;">Cash
                            Payment Paid
                            Customers</p>
                        <div class="customer-list mt-3">

                            <div class="customer-item">
                                <input class="form-check-input" type="checkbox">
                                <div class="customer-details">
                                    <p><strong>Customers Name :</strong> Dimo Lanka - Negombo</p>
                                    <p><strong>Receipt Number :</strong> 25648575623</p>
                                    <p><strong>Invoice Numbers :</strong> 1256845, 1256854</p>
                                    <p><strong>Collected Cash Amount :</strong> Rs. 170,000.00</p>
                                </div>
                            </div>

                            <div class="customer-item">
                                <input class="form-check-input" type="checkbox">
                                <div class="customer-details">
                                    <p><strong>Customers Name :</strong> Dimo Lanka - Galle</p>
                                    <p><strong>Receipt Number :</strong> 25648575623</p>
                                    <p><strong>Invoice Numbers :</strong> 1256845, 1256854</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <p class="form-group-label">Upload Bank Slip</p>
                    <div id="upload-areas-container">
                        <div class="upload-item mb-3">
                            <div class="upload-area" onclick="this.querySelector('input[type=file]').click()">
                                <div class="upload-icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_5510_20873)">
                                            <path
                                                d="M13.3375 13.7231L10.0041 9.80159M10.0041 9.80159L6.67078 13.7231M10.0041 9.80159V18.6251M16.9958 16.0663C17.8086 15.545 18.4507 14.7201 18.8207 13.7218C19.1907 12.7235 19.2677 11.6087 19.0393 10.5532C18.811 9.49782 18.2904 8.56189 17.5597 7.89321C16.829 7.22451 15.9299 6.86114 15.0041 6.86042H13.9541C13.7019 5.71261 13.2318 4.64702 12.5791 3.74374C11.9264 2.84047 11.1082 2.12301 10.1859 1.64532C9.26357 1.16763 8.26124 0.942137 7.25421 0.985787C6.24718 1.02944 5.26167 1.3411 4.37176 1.89734C3.48185 2.45358 2.71071 3.23992 2.1163 4.19725C1.52189 5.15459 1.11969 6.25799 0.939933 7.42452C0.760175 8.59105 0.807538 9.79035 1.07846 10.9322C1.34938 12.0741 1.83681 13.1289 2.50411 14.0173"
                                                stroke="#475467" stroke-width="1.80776" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_5510_20873">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </div>
                                <p class="upload-text">Click to upload</p>
                                <input type="file" name="bank_slips[]" class="bank-slip-upload" hidden
                                    onchange="handleFileUpload(this)">
                            </div>
                        </div>
                    </div>
                    <a href="#" class="add-another-slip" id="add-bank-slip-btn">+ Add Another Bank Slip</a>

                    <button class="btn-submit-payment">Submit Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="user-toast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive"
    aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;">
    <div class="d-flex">
        <div class="toast-body">
            <i class="fa-solid fa-check-circle me-2"></i>
            <span id="toast-message">Payment Submitted Successfully!</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"
            onclick="closeToast()"></button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Search functionality
        const searchInput = document.querySelector('.search-input');
        const customerItems = document.querySelectorAll('.customer-item');

        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const value = this.value.toLowerCase();
                customerItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(value) ? '' : 'none';
                });
            });
        }

        window.handleFileUpload = function (input) {
            const uploadArea = input.closest('.upload-area');
            const uploadText = uploadArea.querySelector('.upload-text');

            if (input.files && input.files.length > 0) {
                uploadText.textContent = input.files[0].name;
                uploadText.style.color = '#CC0000';
            } else {
                uploadText.textContent = 'Click to upload';
                uploadText.style.color = '#111827';
            }
        };

        const addBankSlipBtn = document.getElementById('add-bank-slip-btn');
        const uploadAreasContainer = document.getElementById('upload-areas-container');

        if (addBankSlipBtn && uploadAreasContainer) {
            addBankSlipBtn.addEventListener('click', function (e) {
                e.preventDefault();

                const newItem = document.createElement('div');
                newItem.className = 'upload-item mb-3';
                newItem.innerHTML = `
                    <button type="button" class="remove-upload-btn" onclick="this.closest('.upload-item').remove()" title="Remove">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="upload-area" onclick="this.querySelector('input[type=file]').click()">
                        <div class="upload-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_5510_20873)">
<path d="M13.3375 13.7231L10.0041 9.80159M10.0041 9.80159L6.67078 13.7231M10.0041 9.80159V18.6251M16.9958 16.0663C17.8086 15.545 18.4507 14.7201 18.8207 13.7218C19.1907 12.7235 19.2677 11.6087 19.0393 10.5532C18.811 9.49782 18.2904 8.56189 17.5597 7.89321C16.829 7.22451 15.9299 6.86114 15.0041 6.86042H13.9541C13.7019 5.71261 13.2318 4.64702 12.5791 3.74374C11.9264 2.84047 11.1082 2.12301 10.1859 1.64532C9.26357 1.16763 8.26124 0.942137 7.25421 0.985787C6.24718 1.02944 5.26167 1.3411 4.37176 1.89734C3.48185 2.45358 2.71071 3.23992 2.1163 4.19725C1.52189 5.15459 1.11969 6.25799 0.939933 7.42452C0.760175 8.59105 0.807538 9.79035 1.07846 10.9322C1.34938 12.0741 1.83681 13.1289 2.50411 14.0173" stroke="#475467" stroke-width="1.80776" stroke-linecap="round" stroke-linejoin="round"/>
</g>
<defs>
<clipPath id="clip0_5510_20873">
<rect width="20" height="20" fill="white"/>
</clipPath>
</defs>
</svg>
                        </div>
                        <p class="upload-text">Click to upload</p>
                        <input type="file" name="bank_slips[]" class="bank-slip-upload" hidden onchange="handleFileUpload(this)">
                    </div>
                `;
                uploadAreasContainer.appendChild(newItem);
            });
        }

        const submitBtn = document.querySelector('.btn-submit-payment');
        if (submitBtn) {
            submitBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const toast = document.getElementById('user-toast');
                toast.style.display = 'block';

                setTimeout(() => {
                    toast.style.display = 'none';
                    // window.location.href = '/collections';
                }, 3000);
            });
        }
    });

    function closeToast() {
        document.getElementById('user-toast').style.display = 'none';
    }
</script>

@include('layouts.footer2')