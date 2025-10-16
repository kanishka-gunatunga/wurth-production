@include('adm::layouts.header')
<div class="content px-0">
            <div class="d-flex flex-row px-3 justify-content-center align-items-center w-100 text-start mb-3" style="border: solid 1px #9D9D9D;">
                <div class="col-6 d-flex flex-column py-2 text-center">
                    <p class="gray-small-title mb-1">Daily Cash Deposit</p>
                    <p class="black-large-text mb-1">Rs. 120,000</p>
                </div>
                <div class="col-6 d-flex flex-column py-2 text-center" style="border-left: solid 1px #9D9D9D;">
                    <p class="gray-small-title mb-1">Daily Cheque Deposit</p>
                    <p class="black-large-text mb-1">Rs. 325,000</p>
                </div>
            </div>
            <div class="d-flex flex-row px-3 justify-content-between align-items-center w-100 text-start pt-2 mb-3">
                <h3 class="page-title">Daily Deposit</h3>
            </div>

            <div class="container px-3 pb-2">
                <div class="dropdown-overlay" id="dropdownOverlay" style="display: none;"></div>
                <h4 class="black-title mb-0">Select Deposit Method</h4>
                <div class="custom-select-wrapper" id="customSelectWrapper">
                    <div class="custom-select" id="customSelect">
                        <span>Select Deposit Method</span>
                        <i class="bi bi-chevron-down chevron"></i>
                    </div>
                    <div class="custom-dropdown" id="customDropdown">
                        <div class="custom-dropdown-item" data-value="1">Cash Deposit</div>
                        <div class="custom-dropdown-item" data-value="2">Cheque Deposit</div>
                    </div>
                </div>
            </div>

            <div class="d-flex w-100 flex-column px-3 mb-3 mt-3">
                <div class="card-view px-0 ">
                    <div class="d-flex flex-row justify-content-between align-items-center px-3 mb-2">
                        <h4 class="black-title mb-3">Deposit Summery </h4>
                    </div>
                    <div class="px-3">
                        <div class="input-group mb-3" style="border: solid 0.5px #9D9D9D !important; border-radius: 8px !important;">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search" style="color: #9D9D9D !important"></i></span>
                            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1" style="border-radius: 8px !important;">
                        </div>
                    </div>
                    <div class="d-flex flex-row justify-content-between align-items-center px-3 pb-2 mb-2" style="border-bottom: 1px solid #D0D5DDAB;">
                        <h4 class="black-title mb-0">Cash Payment Paid Customers</h4>
                    </div>
                    <div class="scrollable-section-deposit">
                        <div class="d-flex flex-column pe-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label d-flex flex-column" for="flexCheckDefault" style="border-bottom: 1px solid #D0D5DDAB;">
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Customers Name : </span>
                                        <span class="label-value">Dimo Lanka - Negombo</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Invoice Numbers : </span>
                                        <span class="label-value">1256845, 1256854</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Collected Cash Amount : </span>
                                        <span class="label-value">Rs. 170,000.00</span>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label d-flex flex-column" for="flexCheckDefault" style="border-bottom: 1px solid #D0D5DDAB;">
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Customers Name : </span>
                                        <span class="label-value">Dimo Lanka - Negombo</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Invoice Numbers : </span>
                                        <span class="label-value">1256845, 1256854</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Collected Cash Amount : </span>
                                        <span class="label-value">Rs. 170,000.00</span>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label d-flex flex-column" for="flexCheckDefault" style="border-bottom: 1px solid #D0D5DDAB;">
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Customers Name : </span>
                                        <span class="label-value">Dimo Lanka - Negombo</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Invoice Numbers : </span>
                                        <span class="label-value">1256845, 1256854</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Collected Cash Amount : </span>
                                        <span class="label-value">Rs. 170,000.00</span>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label d-flex flex-column" for="flexCheckDefault" style="border-bottom: 1px solid #D0D5DDAB;">
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Customers Name : </span>
                                        <span class="label-value">Dimo Lanka - Negombo</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Invoice Numbers : </span>
                                        <span class="label-value">1256845, 1256854</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Collected Cash Amount : </span>
                                        <span class="label-value">Rs. 170,000.00</span>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label d-flex flex-column" for="flexCheckDefault" style="border-bottom: 1px solid #D0D5DDAB;">
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Customers Name : </span>
                                        <span class="label-value">Dimo Lanka - Negombo</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Invoice Numbers : </span>
                                        <span class="label-value">1256845, 1256854</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Collected Cash Amount : </span>
                                        <span class="label-value">Rs. 170,000.00</span>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label d-flex flex-column" for="flexCheckDefault" style="border-bottom: 1px solid #D0D5DDAB;">
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Customers Name : </span>
                                        <span class="label-value">Dimo Lanka - Negombo</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Invoice Numbers : </span>
                                        <span class="label-value">1256845, 1256854</span>
                                    </div>
                                    <div class="d-flex flex-row mb-2">
                                        <span class="label-name">Collected Cash Amount : </span>
                                        <span class="label-value">Rs. 170,000.00</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="px-3">
                        <div class="d-flex flex-row px-3 mt-2 justify-content-center align-items-center w-100 text-start mb-3 shadow-border" style=" border-radius: 8px;">
                            <div class="col-12 d-flex flex-column py-4 text-center">
                                <p class="gray-small-title mb-1" style="color: #595959;  font-weight: 500">Total Amount
                                </p>
                                <p class="black-large-text mb-1" style="color:#CC0000">Rs.
                                    124,000.00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="input-group-collection-inner d-flex flex-column mb-3 px-3 pt-3">
                <label for="screenshot" class="mb-1">Upload Transfer
                    Screenshot</label>
                <div class="d-flex flex-row mt-2 px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border" style="border-radius: 8px;">
                    <div class="col-12 d-flex flex-column pt-4 pb-3 text-center position-relative justify-content-center align-items-center">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <svg width="47" height="46" viewBox="0 0 47 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3.5" y="3" width="40" height="40" rx="20" fill="#F2F4F7"></rect>
                                <rect x="3.5" y="3" width="40" height="40" rx="20" stroke="#F9FAFB" stroke-width="6"></rect>
                                <g clip-path="url(#clip0_798_4571)">
                                    <path d="M26.8335 26.3332L23.5002 22.9999M23.5002 22.9999L20.1669 26.3332M23.5002 22.9999V30.4999M30.4919 28.3249C31.3047 27.8818 31.9467 27.1806 32.3168 26.3321C32.6868 25.4835 32.7637 24.5359 32.5354 23.6388C32.307 22.7417 31.7865 21.9462 31.0558 21.3778C30.3251 20.8094 29.4259 20.5005 28.5002 20.4999H27.4502C27.198 19.5243 26.7278 18.6185 26.0752 17.8507C25.4225 17.0829 24.6042 16.4731 23.682 16.0671C22.7597 15.661 21.7573 15.4694 20.7503 15.5065C19.7433 15.5436 18.7578 15.8085 17.8679 16.2813C16.9779 16.7541 16.2068 17.4225 15.6124 18.2362C15.018 19.05 14.6158 19.9879 14.436 20.9794C14.2563 21.9709 14.3036 22.9903 14.5746 23.961C14.8455 24.9316 15.3329 25.8281 16.0002 26.5832" stroke="#475467" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                                <defs>
                                    <clipPath id="clip0_798_4571">
                                        <rect width="20" height="20" fill="white" transform="translate(13.5 13)"></rect>
                                    </clipPath>
                                </defs>
                            </svg>
                            <label for="screenshot">Click to upload</label>
                        </div>
                        <input type="file" id="screenshot" class="form-control position-absolute screenshot-input" name="screenshot" multiple="" style="opacity: 0;">
                        <div class="invalid-feedback">Upload Transfer Screenshot is
                            required</div>
                        <ul id="file-preview" class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="d-flex w-100 justify-content-center align-items-center px-3">
                <button class="styled-button-normal px-5" style="font-size: 14px !important; width: 100% !important;" type="submit">Submit Payment</button>
            </div>
        </div>
@include('adm::layouts.footer')
