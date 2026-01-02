@include('layouts.dashboard-header')
<div class="main-wrapper">
    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Direct Payments</h1>
        </div>
        <hr class="red-line mt-0">
    </div>

    <div class="vertical-scrollable-wrapper bg-white p-4">
        <div class="header-and-content-gap-md"></div>
        <div
            class="col-12 d-block d-lg-flex d-md-flex justify-content-between gap-5 align-items-center selected-customers">
            <p class="card-section-title">Selected Customers</p>
            <div type="button" class="text-muted uncheck-btn" onclick="uncheckAll()">
                Uncheck all
            </div>
        </div>
        <div class="header-and-content-gap-md"></div>
        <div class="col-12 d-flex gap-4 invoices-list-row" id="dynamic-column-container">
        </div>

        <div class="bg-white">
            <div class="row flex-wrap g-3 d-flex justify-content-between align-items-center mt-5">
                <!-- <hr class="mb-0" style="color: #D3D3D3; border-width: 2px;"> -->
                <div class="col-6">
                    <textarea class="additional-notes" name="" id="" rows="3" placeholder="Additional Notes"></textarea>
                </div>
                <div class="col-5">
                    <p class="red-bold-text">Final Payable Amount: <span class="red-unblod-text">&nbsp; RS.
                            3000000.00 </span></p>
                </div>
            </div>
        </div>
    </div>
</div>


<div
    class="action-button-lg-row mt-3">
    <a href="{{ url('/collections/add')}}">
        <button class="black-action-btn-lg mb-3 ">
            Cancel
        </button>
    </a>
    <button class="red-action-btn-lg mb-3 submit">
        Submit
    </button>
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
            Division added successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>
@include('layouts.footer2')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#multi-select-dropdown').select2({
            placeholder: "Select Customer",
            allowClear: true
        });
    });
</script>

<script>
    // Show toast on submit
    document.querySelector('.submit').addEventListener('click', function(e) {
        e.preventDefault();
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    });
</script>

<script>
    function filterCheckboxes() {
        // Get the search input value and convert to lowercase for case-insensitive matching
        const searchInput = document.getElementById('search-input').value.toLowerCase();

        // Find the closest .checkbox-items container to the search input
        const searchBox = document.getElementById('search-input');
        const checkboxContainer = searchBox.closest('.checkbox-items');

        // If not found, fallback to searching all
        const checkboxItems = checkboxContainer ?
            checkboxContainer.querySelectorAll('.checkbox-item') :
            document.querySelectorAll('.checkbox-item');

        // Loop through each item to check if it matches the search query
        checkboxItems.forEach(item => {
            const itemName = (item.getAttribute('data-name') || item.textContent).toLowerCase();
            if (itemName.includes(searchInput)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
            const accordionToggle = event.target.closest('[data-bs-toggle="collapse"]');


            if (accordionToggle && accordionToggle.classList.contains('checkbox-item')) {


                setTimeout(() => {
                    const isExpanded = accordionToggle.getAttribute('aria-expanded') === 'true';


                    const parentRow = accordionToggle.closest('.d-flex');

                    if (parentRow) {

                        const redPlusdiv = parentRow.querySelector('.red-plus-icon');

                        if (redPlusdiv) {
                            if (isExpanded) {
                                redPlusdiv.classList.remove('d-none');
                            } else {
                                redPlusdiv.classList.add('d-none');
                            }
                        }
                    }
                }, 150);
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- CONFIGURATION ---
        const mainContainer = document.getElementById('dynamic-column-container');

        // An array to define your columns. Add more objects to create more columns.
        const columnsData = [{
                title: 'Dimo Lanka',
                itemCount: 5
            },
            {
                title: 'Singer PLC',
                itemCount: 4
            },
            {
                title: 'Abans Group',
                itemCount: 6
            },
            {
                title: 'Abans Group',
                itemCount: 3
            }
        ];

        // --- DYNAMIC CREATION LOGIC ---
        columnsData.forEach((columnData, colIndex) => {
            const columnWrapper = document.createElement('div');
            columnWrapper.className = 'col-md-4 d-flex flex-column';

            const itemsContainer = document.createElement('div');
            itemsContainer.className = 'checkbox-items';

            for (let i = 1; i <= columnData.itemCount; i++) {
                const uniquePrefix = `col${colIndex}-item${i}`;
                const checkboxId = `item-${uniquePrefix}`;
                const redPlusId = `redPlus-${uniquePrefix}`;
                const outerCollapseId = `collapseOne-${uniquePrefix}`;
                const innerCollapseId = `collapseThree-${uniquePrefix}`;
                const innerAccordionId = `accordionExample-${uniquePrefix}`;
                const paymentSummaryCardId = `paymentSummary-${uniquePrefix}`;
                const newPaymentId = `newPayment-${uniquePrefix}`;
                const paymentMethodBtnId = `paymentMethodBtn-${uniquePrefix}`;

                const itemHtml = `
                <div class="checkbox-item-container">
                    <div class="d-flex align-items-center justify-content-between border-bottom-1">
                        <label class="checkbox-item-wrapper">
                            <input type="checkbox" id="${checkboxId}" name="${checkboxId}">
                            <span class="checkmark"></span>
                            <span class="checkbox-item ms-4" type="button" data-bs-toggle="collapse"
                                data-bs-target="#${outerCollapseId}" aria-expanded="false"
                                aria-controls="${outerCollapseId}">Item ${i}</span>
                        </label>
                        <div type="button" class="border-0 text-muted d-none red-plus-icon" 
                            id="${redPlusId}" data-target="${newPaymentId}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.477 2 2 6.477 2 12C2 17.523 6.477 22 12 22C17.523 22 22 17.523 22 12C22 6.477 17.523 2 12 2ZM17 13H13V17H11V13H7V11H11V7H13V11H17V13Z" fill="#CC0000"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div id="${outerCollapseId}" class="accordion-collapse collapse p-3 inner-accordion">
                            <div class="accordion-body">
                                <div class="accordion" id="${innerAccordionId}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button 
                                                class="accordion-button select-payment-method-btn collapsed"
                                                type="button" 
                                                data-bs-toggle="collapse"
                                                data-bs-target="#${innerCollapseId}" 
                                                aria-expanded="true"
                                                aria-controls="${innerCollapseId}"
                                                id="${paymentMethodBtnId}"
                                                >
                                                <span class="payment-method-text">Select Payment Method</span>
                                                <span class="selected-method-indicator" style="display: none; margin-left: 10px; color: #CC0000; font-weight: bold;"></span>
                                            </button>
                                        </h2>
                                        <div id="${innerCollapseId}" class="accordion-collapse collapse"
                                            data-bs-parent="#${innerAccordionId}">
                                            <div class="accordion-body ps-0 pb-0">
                                                <ul class="payment-method-list">
                                                    <li type="button" data-name="cash">Cash Payment</li>
                                                    <li type="button" data-name="fund_transfer">Fund Transfer</li>
                                                    <li type="button" data-name="cheque">Cheque Payment</li>
                                                    <li type="button" data-name="card">Card Payment</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="payment-content mt-2"></div>
                                        <div class="new-payment mt-2" id="${newPaymentId}"></div>
                                        <div class="card payment-summary-card" id="${paymentSummaryCardId}">
                                            <div class="card-body">
                                                <p class="card-item-title">Payment Summary</p>
                                                <div class="mt-4">
                                                    <p class="card-item-section-title">Cash Payment</p>
                                                    <div class="pt-2">
                                                        <p class="card-item-p">Expect to Pay:<span
                                                                class="unbold">&nbsp; Rs. 100000.00</span>
                                                        </p>
                                                        <p class="card-item-p">Discount:<span
                                                                class="unbold">&nbsp; 3%</span></p>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <p class="card-item-section-title">Fund Transfer</p>
                                                    <div class="pt-2">
                                                        <p class="card-item-p">Expect to Pay:<span
                                                                class="unbold">&nbsp; Rs. 1200000.00</span>
                                                        </p>
                                                        <p class="card-item-p">Discount:<span
                                                                class="unbold">&nbsp; 5%</span></p>
                                                    </div>
                                                </div>
                                                <div class="card d-flex justify-content-center align-items-center Summary-card-bottom">
                                                    <p class="gray-text-sm">Payment Amount</p>
                                                    <p class="red-amount-lg">Rs. 5000000.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                itemsContainer.innerHTML += itemHtml;
            }

            columnWrapper.innerHTML = `
            <div>
                <p class="invoces-list-section-title">${columnData.title}</p>
            </div>`;
            columnWrapper.appendChild(itemsContainer);

            mainContainer.appendChild(columnWrapper);
        });

        // --- EVENT HANDLER: Add new accordion section when red plus clicked ---
        document.addEventListener('click', (e) => {
            if (e.target.closest('.red-plus-icon')) {
                const redPlusDiv = e.target.closest('.red-plus-icon');
                const targetId = redPlusDiv.getAttribute('data-target');
                const targetContainer = document.getElementById(targetId);

                if (targetContainer) {
                    const uniqueId = `${targetId}-${Date.now()}`;
                    const innerAccordionId = `innerAcc-${uniqueId}`;
                    const innerCollapseId = `innerCollapse-${uniqueId}`;

                    const innerAccordionHtml = `
                    <div class="accordion" id="${innerAccordionId}">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button select-payment-method-btn collapsed"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#${innerCollapseId}" aria-expanded="false"
                                    aria-controls="${innerCollapseId}">
                                    Select Payment Method
                                </button>
                            </h2>
                            <div id="${innerCollapseId}" class="accordion-collapse collapse"
                                data-bs-parent="#${innerAccordionId}">
                                <div class="accordion-body ps-0 pb-0">
                                    <ul class="payment-method-list">
                                        <li type="button" data-name="cash">Cash Payment</li>
                                        <li type="button" data-name="fund_transfer">Fund Transfer</li>
                                        <li type="button" data-name="cheque">Cheque Payment</li>
                                        <li type="button" data-name="card">Card Payment</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="payment-content mt-2"></div>
                        </div>
                    </div>`;
                    targetContainer.insertAdjacentHTML('beforeend', innerAccordionHtml);
                }
            }
        });

        // --- IMPROVED EVENT DELEGATION: Handle payment method selection for all accordions (including dynamic ones) ---
        document.addEventListener("click", function(e) {
            // Check if clicked element is a payment method list item
            if (e.target.matches('.payment-method-list li')) {
                const clickedItem = e.target;
                const method = clickedItem.getAttribute("data-name");

                // Find the next payment-content div (works for both original and dynamically added accordions)
                const accordionItem = clickedItem.closest('.accordion-item');
                const contentDiv = accordionItem ? accordionItem.querySelector('.payment-content') :
                    null;

                if (contentDiv) {
                    // Generate unique ID for this specific payment form
                    const uniqueId =
                        `${method}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

                    const content = generatePaymentContent(method, uniqueId);

                    // Insert content
                    contentDiv.innerHTML = content;

                    // Add event listeners for file uploads and other interactive elements
                    addEventListenersForDynamicElements(uniqueId);

                    // Collapse the accordion after selection
                    const collapseEl = clickedItem.closest(".accordion-collapse");
                    if (collapseEl && window.bootstrap) {
                        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl);
                        bsCollapse.hide();
                    }
                }
            }
        });

        // Hide big Payment Summary Card when selecting a payment method and show only the small Total Amount card.
        document.addEventListener("click", function(e) {
            if (e.target.matches('.payment-method-list li')) {

                const accordionItem = e.target.closest('.accordion-item');

                // Hide the big Payment Summary Card
                const paymentSummaryCard = accordionItem.parentElement.querySelector('.payment-summary-card');
                if (paymentSummaryCard) {
                    paymentSummaryCard.style.display = "none";
                }

                // Show the small Total Amount card (if exists)
                const totalCard = accordionItem.querySelector('.form-input-file');
                if (totalCard) {
                    totalCard.style.display = "block";
                }
            }
        });

        // --- PAYMENT CONTENT GENERATION FUNCTION ---
        function generatePaymentContent(method, uniqueId) {

            // const methodButton = accordionItem.querySelector('.accordion-button');
            // const methodText = methodButton.querySelector('.payment-method-text');
            // const selectedIndicator = methodButton.querySelector('.selected-method-indicator');

            // console.log(methodButton);
            // console.log(methodText);
            // console.log(selectedIndicator);
            switch (method) {
                case "cash":
                    return `
                <div class="mb-3">
                    <label for="amountInput_${uniqueId}" class="form-label form-label-md">Amount</label>
                    <input type="text" class="form-control form-input-md" id="amountInput_${uniqueId}" placeholder="Enter Amount">
                </div>
                <div class="mb-3">
                    <label for="discountInput_${uniqueId}" class="form-label form-label-md">Discount</label>
                    <input type="number" class="form-control form-input-md" id="discountInput_${uniqueId}" placeholder="3%">
                </div>
                <div class="mb-3">
                    <label for="paymentDate_${uniqueId}" class="form-label form-label-md">Payment Date: <span class="unbold">2020.10.10</span></label>
                </div>
                <div class="card d-flex justify-content-center align-items-center Summary-card-bottom">
                    <p class="gray-text-sm">Total Amount</p>
                    <p class="red-amount-lg">Rs. 5000000.00</p>
                </div>
                <div class="d-flex justify-content-end align-items-center mt-3">
                    <button class="red-action-btn-md" onclick="saveCashPayment('${uniqueId}')">Save</button>
                </div>`;

                case "fund_transfer":
                    return `
                <div class="mb-3">
                    <label for="amountInput_${uniqueId}" class="form-label form-label-md">Amount</label>
                    <input type="text" class="form-control form-input-md" id="amountInput_${uniqueId}" placeholder="Enter Amount">
                </div>
                <div class="mb-3">
                    <label for="transferReferenceInput_${uniqueId}" class="form-label form-label-md">Transfer Reference Number</label>
                    <input type="text" class="form-control form-input-md" id="transferReferenceInput_${uniqueId}" placeholder="Enter Reference Number">
                </div>
                <div class="mb-3">
                    <label for="discountInput_${uniqueId}" class="form-label form-label-md">Discount <span class="input-text-gray">  Max Discounts: 5% (7 Days), 3% (30 Days)</span></label>
                    <input type="number" class="form-control form-input-md" id="discountInput_${uniqueId}" placeholder="3%">
                </div>
                <div class="mb-3">
                    <label for="uploadTransferScreenshotInput_${uniqueId}" class="form-label form-label-md">Upload Transfer Screenshot</label>
                    <div class="form-input-file inputFilesDiv" id="inputFilesDiv_${uniqueId}">
                        <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="40" height="40" rx="20" fill="#F2F4F7"/>
                            <rect x="3" y="3" width="40" height="40" rx="20" stroke="#F9FAFB" stroke-width="6"/>
                            <g clip-path="url(#clip0_3428_3987)">
                            <path d="M26.3335 26.3332L23.0002 22.9999M23.0002 22.9999L19.6669 26.3332M23.0002 22.9999V30.4999M29.9919 28.3249C30.8047 27.8818 31.4467 27.1806 31.8168 26.3321C32.1868 25.4835 32.2637 24.5359 32.0354 23.6388C31.807 22.7417 31.2865 21.9462 30.5558 21.3778C29.8251 20.8094 28.9259 20.5005 28.0002 20.4999H26.9502C26.698 19.5243 26.2278 18.6185 25.5752 17.8507C24.9225 17.0829 24.1042 16.4731 23.182 16.0671C22.2597 15.661 21.2573 15.4694 20.2503 15.5065C19.2433 15.5436 18.2578 15.8085 17.3679 16.2813C16.4779 16.7541 15.7068 17.4225 15.1124 18.2362C14.518 19.05 14.1158 19.9879 13.936 20.9794C13.7563 21.9709 13.8036 22.9903 14.0746 23.961C14.3455 24.9316 14.8329 25.8281 15.5002 26.5832" stroke="#475467" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_3428_3987">
                            <rect width="20" height="20" fill="white" transform="translate(13 13)"/>
                            </clipPath>
                            </defs>
                        </svg>
                        <p class="form-label-md mt-2">Click to upload</p>
                    </div>
                    <input class="uploadTransferScreenshotInput" type="file" id="uploadTransferScreenshotInput_${uniqueId}" hidden>
                    <button class="text-red-upload" id="addAnotherScreenshotBtn_${uniqueId}">+ Add Another Screenshot</button>
                    <div class="uploadedFilesContainer" id="uploadedFilesContainer_${uniqueId}" style="display: none;">
                        <div class="uploadedFilesList" id="uploadedFilesList_${uniqueId}"></div>
                    </div>
                </div>
                <div class="card d-flex justify-content-center align-items-center form-input-file inputFilesDiv mt-3">
                    <p class="gray-text-sm">Total Amount</p>
                    <p class="red-amount-lg">Rs. 500,0000.00</p>
                </div>
                <div class="d-flex justify-content-end align-items-center mt-3">
                    <button class="red-action-btn-md" onclick="saveFundTransfer('${uniqueId}')">Save</button>
                </div>`;

                case "cheque":
                    return `<div id="chequeContent_${uniqueId}">
                    <div class="card card-body border-0">
                        <form id="ChequePaymentForm_${uniqueId}" class="content needs-validation p-0 border-0 px-1" novalidate>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label class="form-label form-label-md" for="cheque_number_${uniqueId}">Cheque Number</label>
                                <input type="number" class="form-control form-input-md" id="cheque_number_${uniqueId}"
                                    placeholder="Enter Cheque Number" name="cheque_number" required />
                                <div class="invalid-feedback">Cheque Number is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label class="form-label form-label-md" for="cheque_date_${uniqueId}">Cheque Date</label>
                                <input type="date" class="form-control form-input-md" id="cheque_date_${uniqueId}"
                                    placeholder="dd/mm/yyyy" name="cheque_date" required />
                                <div class="invalid-feedback">Cheque Date is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label class="form-label form-label-md" for="cheque_amount_${uniqueId}">Cheque Amount</label>
                                <input type="number" class="form-control form-input-md" id="cheque_amount_${uniqueId}"
                                    placeholder="Enter Cheque Amount" name="cheque_amount" required />
                                <div class="invalid-feedback">Cheque Amount is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label class="form-label form-label-md" for="bank_name_${uniqueId}">Bank Name</label>
                                <select class="form-select form-control form-input-md" id="bank_name_${uniqueId}" name="bank_name">
                                    <option selected value="0">Bank Of Ceylon</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                                <div class="invalid-feedback">Bank Name is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label class="form-label form-label-md" for="branch_name_${uniqueId}">Branch Name</label>
                                <input type="text" class="form-control form-input-md" id="branch_name_${uniqueId}"
                                    placeholder="Enter Branch Name" name="branch_name" required />
                                <div class="invalid-feedback">Branch Name is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label class="form-label form-label-md" for="discount_${uniqueId}">Discount <span
                                        style="font-size: 12px !important; color:#868686">Max Discounts: 5% (7 Days), 3% (30 Days)</span></label>
                                <input type="number" class="form-control form-input-md" placeholder="3%"
                                    name="discount" id="discount_${uniqueId}" required />
                                <div class="invalid-feedback">Discount is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <div class="form-check d-flex flex-row align-items-center">
                                    <input class="form-check-input m-0 me-2 ms-0" type="checkbox" value="" 
                                        id="date_cheque_tick_${uniqueId}" style="margin-left: 0px !important;">
                                    <label class="form-label form-label-md" for="date_cheque_tick_${uniqueId}">
                                        Post - Dated Cheque
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="uploadTransferScreenshotInput_${uniqueId}" class="form-label form-label-md">Upload Cheque Image</label>
                                <div class="form-input-file inputFilesDiv" id="inputFilesDiv_${uniqueId}">
                                    <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="3" y="3" width="40" height="40" rx="20" fill="#F2F4F7"/>
                                        <rect x="3" y="3" width="40" height="40" rx="20" stroke="#F9FAFB" stroke-width="6"/>
                                        <g clip-path="url(#clip0_3428_3987)">
                                        <path d="M26.3335 26.3332L23.0002 22.9999M23.0002 22.9999L19.6669 26.3332M23.0002 22.9999V30.4999M29.9919 28.3249C30.8047 27.8818 31.4467 27.1806 31.8168 26.3321C32.1868 25.4835 32.2637 24.5359 32.0354 23.6388C31.807 22.7417 31.2865 21.9462 30.5558 21.3778C29.8251 20.8094 28.9259 20.5005 28.0002 20.4999H26.9502C26.698 19.5243 26.2278 18.6185 25.5752 17.8507C24.9225 17.0829 24.1042 16.4731 23.182 16.0671C22.2597 15.661 21.2573 15.4694 20.2503 15.5065C19.2433 15.5436 18.2578 15.8085 17.3679 16.2813C16.4779 16.7541 15.7068 17.4225 15.1124 18.2362C14.518 19.05 14.1158 19.9879 13.936 20.9794C13.7563 21.9709 13.8036 22.9903 14.0746 23.961C14.3455 24.9316 14.8329 25.8281 15.5002 26.5832" stroke="#475467" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_3428_3987">
                                        <rect width="20" height="20" fill="white" transform="translate(13 13)"/>
                                        </clipPath>
                                        </defs>
                                    </svg>
                                    <p class="form-label-md mt-2">Click to upload</p>
                                </div>
                                <input class="uploadTransferScreenshotInput" type="file" id="uploadTransferScreenshotInput_${uniqueId}" hidden>
                            </div>
                            <div class="card d-flex justify-content-center align-items-center Summary-card-bottom">
                                <p class="gray-text-sm">Total Amount</p>
                                <p class="red-amount-lg">Rs. 5000000.00</p>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-3">
                                <button type="button" class="red-action-btn-md" onclick="saveChequePayment('${uniqueId}')">Save</button>
                            </div>
                        </form>
                    </div>    
                </div>`;

                case "card":
                    return `<div id="cardContent_${uniqueId}">
                    <div class="card card-body border-0">
                        <form id="CardPaymentForm_${uniqueId}" class="content needs-validation p-0 border-0 px-1" novalidate>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label for="card_amount_${uniqueId}" class="form-label-md">Amount</label>
                                <input type="number" class="form-control form-input-md" id="card_amount_${uniqueId}"
                                    placeholder="Enter Amount" name="card_amount" required />
                                <div class="invalid-feedback">Amount is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label for="card_transfer_date_${uniqueId}" class="form-label-md">Transfer Date</label>
                                <input type="date" class="form-control form-input-md" id="card_transfer_date_${uniqueId}"
                                    placeholder="dd/mm/yyyy" name="card_transfer_date" required />
                                <div class="invalid-feedback">Transfer Date is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label for="transfer_reference_number_${uniqueId}" class="form-label-md">Transfer Reference Number</label>
                                <input type="text" class="form-control form-input-md" id="transfer_reference_number_${uniqueId}"
                                    placeholder="Enter Transfer Reference Number" name="transfer_reference_number" required />
                                <div class="invalid-feedback">Transfer Reference Number is required</div>
                            </div>
                            <div class="input-group-collection-inner d-flex flex-column mb-3">
                                <label for="card_discount_${uniqueId}" class="form-label-md">Discount <span
                                        style="font-size: 12px !important; color:#868686">Max Discounts: 5% (7 Days), 3% (30 Days)</span></label>
                                <input type="number" class="form-control form-input-md" placeholder="3%"
                                    name="card_discount" id="card_discount_${uniqueId}" required />
                                <div class="invalid-feedback">Discount is required</div>
                            </div>
                            <div class="mb-3">
                                <label for="uploadTransferScreenshotInput_${uniqueId}" class="form-label form-label-md">Upload Payment Slip</label>
                                <div class="form-input-file inputFilesDiv" id="inputFilesDiv_${uniqueId}">
                                    <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="3" y="3" width="40" height="40" rx="20" fill="#F2F4F7"/>
                                        <rect x="3" y="3" width="40" height="40" rx="20" stroke="#F9FAFB" stroke-width="6"/>
                                        <g clip-path="url(#clip0_3428_3987)">
                                        <path d="M26.3335 26.3332L23.0002 22.9999M23.0002 22.9999L19.6669 26.3332M23.0002 22.9999V30.4999M29.9919 28.3249C30.8047 27.8818 31.4467 27.1806 31.8168 26.3321C32.1868 25.4835 32.2637 24.5359 32.0354 23.6388C31.807 22.7417 31.2865 21.9462 30.5558 21.3778C29.8251 20.8094 28.9259 20.5005 28.0002 20.4999H26.9502C26.698 19.5243 26.2278 18.6185 25.5752 17.8507C24.9225 17.0829 24.1042 16.4731 23.182 16.0671C22.2597 15.661 21.2573 15.4694 20.2503 15.5065C19.2433 15.5436 18.2578 15.8085 17.3679 16.2813C16.4779 16.7541 15.7068 17.4225 15.1124 18.2362C14.518 19.05 14.1158 19.9879 13.936 20.9794C13.7563 21.9709 13.8036 22.9903 14.0746 23.961C14.3455 24.9316 14.8329 25.8281 15.5002 26.5832" stroke="#475467" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_3428_3987">
                                        <rect width="20" height="20" fill="white" transform="translate(13 13)"/>
                                        </clipPath>
                                        </defs>
                                    </svg>
                                    <p class="form-label-md mt-2">Click to upload</p>
                                </div>
                                <input class="uploadTransferScreenshotInput" type="file" id="uploadTransferScreenshotInput_${uniqueId}" hidden>
                            </div>
                            <div class="card d-flex justify-content-center align-items-center form-input-file inputFilesDiv mt-3">
                                <p class="gray-text-sm">Total Amount</p>
                                <p class="red-amount-lg">Rs. 5000000.00</p>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-3">
                                <button type="button" class="red-action-btn-md" onclick="saveCardPayment('${uniqueId}')">Save</button>
                            </div>
                        </form>
                    </div>    
                </div>`;

                default:
                    return '<p>Please select a valid payment method.</p>';
            }
        }

        document.addEventListener("click", function(e) {

            if (e.target.matches('.payment-method-list li')) {

                const clickedItem = e.target;
                const selectedMethodName = clickedItem.textContent.trim(); // Get method label

                // Find the accordion button above
                const accordionItem = clickedItem.closest('.accordion-item');
                const methodButton = accordionItem.querySelector('.accordion-button');
                const methodTextSpan = methodButton.querySelector('.payment-method-text');

                // Replace the text inside the button dropdown
                if (methodTextSpan) {
                    methodTextSpan.textContent = selectedMethodName;
                }

                // Close the dropdown
                const collapseEl = clickedItem.closest(".accordion-collapse");
                if (collapseEl && window.bootstrap) {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl);
                    bsCollapse.hide();
                }
            }
        });

        // --- HELPER FUNCTIONS ---

        // ✅ Global function for adding uploaded files
        window.addUploadedFile = function(uniqueId, file) {
            const uploadedFilesContainer = document.getElementById(`uploadedFilesContainer_${uniqueId}`);
            const uploadedFilesList = document.getElementById(`uploadedFilesList_${uniqueId}`);

            if (uploadedFilesContainer && uploadedFilesList) {
                uploadedFilesContainer.style.display = 'block';

                const fileItemDiv = document.createElement('div');
                fileItemDiv.style.cssText = `
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 12px;
                background: none;
                border: none;
                margin-bottom: 6px;
                font-size: 10px;
            `;

                const fileNameSpan = document.createElement('span');
                fileNameSpan.textContent = file.name;

                const closeButton = document.createElement('button');
                closeButton.innerHTML = '&times;';
                closeButton.style.cssText = `
                background-color: #dc3545;
                color: white;
                border: none;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            `;
                closeButton.onclick = function() {
                    fileItemDiv.remove();
                    if (uploadedFilesList.children.length === 0) {
                        uploadedFilesContainer.style.display = 'none';
                    }
                };

                fileItemDiv.appendChild(fileNameSpan);
                fileItemDiv.appendChild(closeButton);
                uploadedFilesList.appendChild(fileItemDiv);

                const fileInput = document.getElementById(`uploadTransferScreenshotInput_${uniqueId}`);
                if (fileInput) fileInput.value = '';
            }
        };

        // Function to attach events to dynamic elements
        function addEventListenersForDynamicElements(uniqueId) {
            const fileUploadBtn = document.getElementById(`inputFilesDiv_${uniqueId}`);
            const fileInput = document.getElementById(`uploadTransferScreenshotInput_${uniqueId}`);
            const addScreenshotBtn = document.getElementById(`addAnotherScreenshotBtn_${uniqueId}`);

            if (fileUploadBtn && fileInput) {
                fileUploadBtn.addEventListener('click', function() {
                    fileInput.click();
                });

                fileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        const file = e.target.files[0];
                        window.addUploadedFile(uniqueId, file);
                    }
                });
            }

            if (addScreenshotBtn) {
                addScreenshotBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (fileInput) fileInput.click();
                });
            }
        }

        // Global save functions for different payment types
        window.saveCashPayment = function(uniqueId) {
            const amount = document.getElementById(`amountInput_${uniqueId}`)?.value;
            const discount = document.getElementById(`discountInput_${uniqueId}`)?.value;
            console.log('Saving cash payment:', {
                uniqueId,
                amount,
                discount
            });
            // Add your save logic here
        };

        window.saveFundTransfer = function(uniqueId) {
            const amount = document.getElementById(`amountInput_${uniqueId}`)?.value;
            const reference = document.getElementById(`transferReferenceInput_${uniqueId}`)?.value;
            const discount = document.getElementById(`discountInput_${uniqueId}`)?.value;
            console.log('Saving fund transfer:', {
                uniqueId,
                amount,
                reference,
                discount
            });
            // Add your save logic here
        };

        window.saveChequePayment = function(uniqueId) {
            const formData = new FormData(document.getElementById(`ChequePaymentForm_${uniqueId}`));
            console.log('Saving cheque payment:', {
                uniqueId,
                formData
            });
            // Add your save logic here
        };

        window.saveCardPayment = function(uniqueId) {
            const formData = new FormData(document.getElementById(`CardPaymentForm_${uniqueId}`));
            console.log('Saving card payment:', {
                uniqueId,
                formData
            });
            // Add your save logic here
        };

        // Global function to uncheck all checkboxes
        window.uncheckAll = function() {
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
        };
    });
</script>