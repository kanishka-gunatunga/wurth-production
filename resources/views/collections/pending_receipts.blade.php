@include('layouts.dashboard-header')


<style>
    /* Search box styles */
    .search-box-wrapper {
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #fff;
        transition: width 0.3s ease;
        border-radius: 30px;
        height: 45px;
        width: 45px;
        border: 1px solid transparent;
        position: relative;
        width: 0;
    }

    .search-box-wrapper.collapsed {
        width: 0;
        padding: 0;
        margin: 0;
        border: 1px solid transparent;
        background-color: transparent;
    }

    .search-box-wrapper.expanded {
        width: 450px;
        padding: 0 15px;
    }

    .search-input {
        flex-grow: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 16px;
        color: #333;
        width: 100%;
        padding-left: 30px;
        /* space for icon */
    }

    .search-input::placeholder {
        color: #888;
    }

    .search-icon-inside {
        position: absolute;
        left: 10px;
        color: #888;
    }

    /* Optional: Adjust button alignment if needed */
    .col-12.d-flex.justify-content-lg-end {
        align-items: center;
    }

    /* Checkbox styling (for advance payment tab) */
    .form-check-input {
        height: 20px;
        width: 20px;
        border-color: #D2D5DA;
        margin-right: 15px;
    }

    .form-check-input:focus {
        border-color: #dc3545 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2.1px #dc354533 !important;
    }

    .form-check-input:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .form-check-label {
        font-family: "Inter", sans-serif;
        font-size: 20px;
        font-weight: 400;
    }

    .custom-dropdown-menu li {
        list-style: none !important;
    }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Pending Receipt</h1>
        </div>

    </div>


    <div class="styled-tab-main">
       


                <div class="row mb-3">
                    <div class="col-lg-6 col-12 ms-auto d-flex justify-content-end gap-3">
                        <form method="GET" action="{{ url('/pending-receipts') }}">
                            <div id="final-search-box-wrapper" class="search-box-wrapper collapsed">
                                <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                                <input type="text" name="final_search" class="search-input" placeholder="Search Receipt, Invoice, ADM or Customer" value="{{ request('final_search') }}" />
                            </div>
                        </form>
                        <button class="header-btn" id="final-search-toggle-button">
                            <i class="fa-solid fa-magnifying-glass fa-xl"></i>
                        </button>
                        <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#finalFilter">
                            <i class="fa-solid fa-filter fa-xl"></i>
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table custom-table-locked" style="min-width: 1300px;">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>ADM Name</th>
                                <th>ADM Number</th>
                                <th>Receipt Number</th>
                                <th>Issue Date</th>
                                <th>Amount</th>
                                <th class="sticky-column">Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($regular_receipts as $payment)
                            <tr>
                                <td>{{ $payment->invoice->customer->name ?? 'N/A' }}</td>
                                <td>{{ $payment->invoice->customer->admDetails->name ?? 'N/A' }}</td>
                                <td>{{ $payment->invoice->customer->adm ?? 'N/A' }}</td>
                                <td>{{ $payment->id ?? 'N/A' }}</td>
                                <td>{{ $payment->created_at ?? 'N/A' }}</td>
                                <td>{{ number_format($payment->amount, 2) ?? '0.00' }}</td>
                               
                                <!-- Actions -->
                                <td class="sticky-column">
                                    <div class="sticky-actions">
                                        <a href="{{ url('/reject-receipt/'.$payment->id) }}"><button class="red-action-btn ">Reject</button></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No reciepts found.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>
                <nav class="d-flex justify-content-center mt-5">
                    {{ $regular_receipts->appends(['final_search' => request('final_search'), 'active_tab' => 'final'])->links('pagination::bootstrap-5') }}
                </nav>
            </div>

        </div>
    </div>
</div>


<!-- Final reciepts Filter Offcanvas -->
<form method="GET" action="{{ url('/pending-receipts') }}">
    @csrf

    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="finalFilter" aria-labelledby="finalFilterLabel">
        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title" id="offcanvasRightLabel">Search </span>
                <span class="title-rest"> &nbsp;by Filter</span>
            </div>
            <div>
                <button type="button" class="btn rounded-phill" onclick="clearFinalFiltersAndSubmit()">Clear All</button>
            </div>
        </div>

        <div class="offcanvas-body">
            <!-- ADM Name Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM Name</p>
                <select id="final-filter-adm-name" name="final_adm_names[]" class="form-control select2" multiple>
                    @foreach ($finalAdmNames as $admName)
                    <option value="{{ $admName }}" {{ in_array($admName, request('final_adm_names', [])) ? 'selected' : '' }}>
                        {{ $admName }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- ADM ID Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select id="final-filter-adm-id" name="final_adm_ids[]" class="form-control select2" multiple>
                    @foreach ($finalAdmIds as $admId)
                    <option value="{{ $admId }}" {{ in_array($admId, request('final_adm_ids', [])) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Customers Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Customers</p>
                <select id="final-filter-customer" name="final_customers[]" class="form-control select2" multiple>
                    @foreach ($finalCustomers as $customer)
                    <option value="{{ $customer }}" {{ in_array($customer, request('final_customers', [])) ? 'selected' : '' }}>
                        {{ $customer }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Styled Status Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">Status</p>
                <div class="custom-dropdown-container" style="position: relative; min-width: 200px;">
                    <button type="button" id="final-custom-status-btn" class="btn custom-dropdown text-start" style="width:100%;">
                        {{ request('final_status') ? ucfirst(request('final_status')) : 'Choose Status' }}
                    </button>
                    <ul id="final-custom-status-menu" class="custom-dropdown-menu" style="display:none; position:absolute; top:100%; left:0; background:#fff; border:1px solid #ddd; width:100%; z-index:999;">
                        @foreach ($regular_receipts->pluck('status')->unique() as $status)
                        <li><a href="#" class="dropdown-item" data-value="{{ $status }}">{{ ucfirst($status) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">Date</p>
                <input type="text" id="final-filter-date" name="final_date_range" class="form-control"
                    placeholder="Select date range" value="{{ request('final_date_range') }}" />
            </div>

            <div class="mt-4 d-flex justify-content-start">
                <button type="submit" class="red-action-btn-lg">Apply Filters</button>
            </div>
        </div>
    </div>
</form>

</div>



@include('layouts.footer2')

<!-- for dropdown -->
<script>
    // Initialize Select2 and set initial values when offcanvas opens
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for all dropdowns
        $('.select2').select2({
            placeholder: "Select options",
            allowClear: true
        });

        // Initialize status dropdowns with current values
        initializeStatusDropdownWithCurrentValue('final-custom-status-btn', 'final-custom-status-menu', 'final-status-value', "{{ request('final_status') }}");
        initializeStatusDropdownWithCurrentValue('temp-custom-status-btn', 'temp-custom-status-menu', 'temp-status-value', "{{ request('temp_status') }}");
        initializeStatusDropdownWithCurrentValue('advance-custom-status-btn', 'advance-custom-status-menu', 'advance-status-value', "{{ request('advance_status') }}");
    });

    // Enhanced status dropdown initialization with current values
    function initializeStatusDropdownWithCurrentValue(btnId, menuId, hiddenInputId, currentValue) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        const hiddenInput = document.getElementById(hiddenInputId);

        if (!btn || !menu || !hiddenInput) {
            console.log('Element not found:', btnId, menuId, hiddenInputId);
            return;
        }

        // Set current value if exists
        if (currentValue && currentValue !== '') {
            const menuItem = menu.querySelector(`[data-value="${currentValue}"]`);
            if (menuItem) {
                btn.textContent = menuItem.textContent;
                btn.setAttribute('data-value', currentValue);
                hiddenInput.value = currentValue;
            }
        }

        btn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });

        menu.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", (e) => {
                e.preventDefault();
                const value = e.target.dataset.value;
                const text = e.target.textContent;

                btn.textContent = text;
                btn.setAttribute("data-value", value);
                hiddenInput.value = value;
                menu.style.display = "none";
            });
        });

        // Close if clicked outside
        document.addEventListener("click", (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    }

    // Clear Final Receipts Filters and Submit
    function clearFinalFiltersAndSubmit() {
        // Clear Select2 dropdowns
        $('#final-filter-adm-name').val(null).trigger('change');
        $('#final-filter-adm-id').val(null).trigger('change');
        $('#final-filter-customer').val(null).trigger('change');

        // Clear date field
        const finalDateInput = document.getElementById('final-filter-date');
        if (finalDateInput) {
            finalDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const finalStatusBtn = document.getElementById('final-custom-status-btn');
        const finalStatusInput = document.getElementById('final-status-value');
        if (finalStatusBtn) {
            finalStatusBtn.textContent = 'Choose Status';
            finalStatusBtn.removeAttribute('data-value');
        }
        if (finalStatusInput) {
            finalStatusInput.value = '';
        }

        // Hide status menu if open
        const finalStatusMenu = document.getElementById('final-custom-status-menu');
        if (finalStatusMenu) {
            finalStatusMenu.style.display = 'none';
        }

        console.log('Final filters cleared');

        // Submit the form to apply cleared filters
        setTimeout(() => {
            const form = document.querySelector('#finalFilter form');
            if (form) {
                form.submit();
            }
        }, 300);
    }

    // Clear Temporary Receipts Filters and Submit
    function clearTempFiltersAndSubmit() {
        // Clear Select2 dropdowns
        $('#temp-filter-adm-name').val(null).trigger('change');
        $('#temp-filter-adm-id').val(null).trigger('change');
        $('#temp-filter-customer').val(null).trigger('change');

        // Clear date field
        const tempDateInput = document.getElementById('temp-filter-date');
        if (tempDateInput) {
            tempDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const tempStatusBtn = document.getElementById('temp-custom-status-btn');
        const tempStatusInput = document.getElementById('temp-status-value');
        if (tempStatusBtn) {
            tempStatusBtn.textContent = 'Choose Status';
            tempStatusBtn.removeAttribute('data-value');
        }
        if (tempStatusInput) {
            tempStatusInput.value = '';
        }

        // Hide status menu if open
        const tempStatusMenu = document.getElementById('temp-custom-status-menu');
        if (tempStatusMenu) {
            tempStatusMenu.style.display = 'none';
        }

        console.log('Temp filters cleared');

        // Submit the form to apply cleared filters
        setTimeout(() => {
            const form = document.querySelector('#trFilter form');
            if (form) {
                form.submit();
            }
        }, 300);
    }

    // Clear Advance Payments Filters and Submit
    function clearAdvanceFiltersAndSubmit() {
        // Clear Select2 dropdowns
        $('#advance-filter-adm-name').val(null).trigger('change');
        $('#advance-filter-adm-id').val(null).trigger('change');
        $('#advance-filter-customer').val(null).trigger('change');

        // Clear date field
        const advanceDateInput = document.getElementById('advance-filter-date');
        if (advanceDateInput) {
            advanceDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const advanceStatusBtn = document.getElementById('advance-custom-status-btn');
        const advanceStatusInput = document.getElementById('advance-status-value');
        if (advanceStatusBtn) {
            advanceStatusBtn.textContent = 'Choose Status';
            advanceStatusBtn.removeAttribute('data-value');
        }
        if (advanceStatusInput) {
            advanceStatusInput.value = '';
        }

        // Hide status menu if open
        const advanceStatusMenu = document.getElementById('advance-custom-status-menu');
        if (advanceStatusMenu) {
            advanceStatusMenu.style.display = 'none';
        }

        console.log('Advance filters cleared');

        // Submit the form to apply cleared filters
        setTimeout(() => {
            const form = document.querySelector('#receiptsFilter form');
            if (form) {
                form.submit();
            }
        }, 300);
    }
</script>

<script>
    // Enable/Disable Optional Input
    document.getElementById("optional-checkbox").addEventListener("change", function() {
        document.getElementById("optional-number").disabled = !this.checked;
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function setupSearch(wrapperId, toggleId) {
            const searchWrapper = document.getElementById(wrapperId);
            const searchToggleButton = document.getElementById(toggleId);
            const searchInput = searchWrapper.querySelector(".search-input");

            let idleTimeout;
            const idleTime = 5000;

            function collapseSearch() {
                searchWrapper.classList.remove("expanded");
                searchWrapper.classList.add("collapsed");
                searchToggleButton.classList.remove("d-none");
                clearTimeout(idleTimeout);
            }

            function startIdleTimer() {
                clearTimeout(idleTimeout);
                idleTimeout = setTimeout(() => {
                    if (!searchInput.value) collapseSearch();
                }, idleTime);
            }

            searchToggleButton.addEventListener("click", function() {
                if (searchWrapper.classList.contains("collapsed")) {
                    searchWrapper.classList.remove("collapsed");
                    searchWrapper.classList.add("expanded");
                    searchToggleButton.classList.add("d-none");
                    searchInput.focus();
                    startIdleTimer();
                } else {
                    collapseSearch();
                }
            });

            searchInput.addEventListener("keydown", function() {
                startIdleTimer();
            });
        }

        // Apply for each tab
        setupSearch("final-search-box-wrapper", "final-search-toggle-button");
        setupSearch("tr-search-box-wrapper", "tr-search-toggle-button");
        setupSearch("receipts-search-box-wrapper", "receipts-search-toggle-button");
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Final receipts search
        document.querySelector("#final-search-box-wrapper .search-input")
            .addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                const filtered = FinalRecieptInvoices.filter(item =>
                    item.receipt.toLowerCase().includes(query) ||
                    (item.invoiceNumber && item.invoiceNumber.toLowerCase().includes(query)) ||
                    (item.ADMName && item.ADMName.toLowerCase().includes(query)) ||
                    (item.customer && item.customer.toLowerCase().includes(query))
                );
                renderTable("final", filtered, 1);
                renderPagination("final", filtered);
            });

        // Temporary receipts invoices search → updates receiptsBody
        document.querySelector("#tr-search-box-wrapper .search-input")
            .addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                const filtered = TRInvoices.filter(item =>
                    item.receiptNumber.toLowerCase().includes(query) ||
                    (item.admNumber && item.admNumber.toLowerCase().includes(query)) ||
                    (item.admName && item.admName.toLowerCase().includes(query)) ||
                    (item.customer && item.customer.toLowerCase().includes(query))
                );
                renderTable("receipts", filtered, 1); // ✅ target receiptsBody
                renderPagination("receipts", filtered); // ✅ target receiptsPagination
            });

        // Temporary receipts advance payment search → updates trBody
        document.querySelector("#receipts-search-box-wrapper .search-input")
            .addEventListener("input", function() {
                const query = this.value.trim().toLowerCase();
                const filtered = temporaryReceiptsAdvancePaymentTableBody.filter(item =>
                    item.receiptNumber.toLowerCase().includes(query) ||
                    (item.admNumber && item.admNumber.toLowerCase().includes(query)) ||
                    (item.admName && item.admName.toLowerCase().includes(query)) ||
                    (item.customer && item.customer.toLowerCase().includes(query))
                );
                renderTable("tr", filtered, 1); // ✅ target trBody
                renderPagination("tr", filtered); // ✅ target trPagination
            });

    });
</script>

<script>
    document.querySelectorAll('.selectable-filter').forEach(function(tag) {
        tag.addEventListener('click', function() {
            tag.classList.toggle('selected');
        });
    });
</script>

<!-- for toast message + view more button -->
<script>
    document.addEventListener('click', function(e) {
        // ✅ Toast message trigger
        // if (e.target.classList.contains('submit')) {
        //     e.preventDefault();
        //     e.stopPropagation(); // Prevent row click
        //     const toast = document.getElementById('user-toast');
        //     toast.style.display = 'block';
        //     setTimeout(() => {
        //         toast.style.display = 'none';
        //     }, 3000);
        // }

        // ✅ View More button redirect
        if (
            (e.target.classList.contains('black-action-btn') || e.target.classList.contains('success-action-btn')) &&
            e.target.hasAttribute('data-href')
        ) {
            e.preventDefault();
            window.location.href = e.target.getAttribute('data-href');
        }
    });
</script>


<!-- pop-up resend SMS modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('resend-sms-modal');
        const closeBtn = document.getElementById('resend-sms-close');
        const mobileSelect = document.getElementById('mobile-number');
        const optionalCheckbox = document.getElementById('optional-checkbox');
        const optionalNumber = document.getElementById('optional-number');
        const receiptInput = document.getElementById('sms-receipt-id');

        // Open modal when "Resend SMS" is clicked
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('resend-sms-btn')) {
                e.preventDefault();

                const primary = e.target.getAttribute('data-primary');
                const secondary = e.target.getAttribute('data-secondary');
                const receiptId = e.target.getAttribute('data-receipt-id');
                console.log(primary);
                // Reset modal fields
                mobileSelect.innerHTML = '<option value="">-- Select Number --</option>';
                if (primary) mobileSelect.innerHTML += `<option value="${primary}">${primary} - Primary</option>`;
                if (secondary) mobileSelect.innerHTML += `<option value="${secondary}">${secondary} - Secondary</option>`;
                optionalNumber.value = '';
                optionalCheckbox.checked = false;
                optionalNumber.disabled = true;
                receiptInput.value = receiptId;

                modal.style.display = 'block';
            }
        });

        // Close modal
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Enable/disable optional number input
        optionalCheckbox.addEventListener('change', function() {
            optionalNumber.disabled = !this.checked;
            if (!this.checked) optionalNumber.value = '';
        });

        // Close modal if clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    });
</script>

<!-- active tab persistence script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('active_tab');

        if (activeTab) {
            const tabMap = {
                final: 'final-reciepts-invoices-pane',
                temporary: 'temporary-receipts-invoices-pane',
                advance: 'temporary-receipts-advance-payment-pane'
            };

            const tabId = tabMap[activeTab];
            if (tabId) {
                const tabTrigger = document.querySelector(`[data-bs-target="#${tabId}"]`);
                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
        }

        // Update pagination links to keep the current tab active
        const allTabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        allTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const targetId = e.target.getAttribute('data-bs-target').replace('#', '');
                const links = document.querySelectorAll('.pagination a');
                links.forEach(link => {
                    const url = new URL(link.href);
                    // reverse map tab ID to active_tab value
                    const activeValue = Object.keys(tabMap).find(key => tabMap[key] === targetId);
                    if (activeValue) url.searchParams.set('active_tab', activeValue);
                    link.href = url.toString();
                });
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the active_tab value from the query string
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('active_tab');

        // If a tab was active before reload, open it again
        if (activeTab) {
            // Try matching either "-pane" or full ID
            const tabTrigger = document.querySelector(`[data-bs-target="#${activeTab}-pane"]`) ||
                document.querySelector(`[data-bs-target="#${activeTab}"]`);
            const tabPane = document.querySelector(`#${activeTab}-pane`) ||
                document.querySelector(`#${activeTab}`);

            if (tabTrigger && tabPane) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // Update pagination links to keep the current tab active
        const allTabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        allTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const targetId = e.target.getAttribute('data-bs-target').replace('#', '');
                const links = document.querySelectorAll('.pagination a');
                links.forEach(link => {
                    const url = new URL(link.href);
                    url.searchParams.set('active_tab', targetId.replace('-pane', ''));
                    link.href = url.toString();
                });
            });
        });

        // Also ensure pagination links always include the active tab when first loaded
        const links = document.querySelectorAll('.pagination a');
        const currentTab = document.querySelector('.nav-link.active');
        if (currentTab) {
            const targetId = currentTab.getAttribute('data-bs-target').replace('#', '').replace('-pane', '');
            links.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('active_tab', targetId);
                link.href = url.toString();
            });
        }
    });
</script>

<!-- clear all button functionality -->
<script>
    // Clear Final Receipts Filters
    function clearFinalFilters() {
        // Clear Select2 dropdowns
        $('#final-filter-adm-name').val(null).trigger('change');
        $('#final-filter-adm-id').val(null).trigger('change');
        $('#final-filter-customer').val(null).trigger('change');

        // Clear date field
        const finalDateInput = document.getElementById('final-filter-date');
        if (finalDateInput) {
            finalDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const finalStatusBtn = document.getElementById('final-custom-status-btn');
        const finalStatusInput = document.getElementById('final-status-value');
        if (finalStatusBtn) {
            finalStatusBtn.textContent = 'Choose Status';
            finalStatusBtn.removeAttribute('data-value');
        }
        if (finalStatusInput) {
            finalStatusInput.value = '';
        }

        // Hide status menu if open
        const finalStatusMenu = document.getElementById('final-custom-status-menu');
        if (finalStatusMenu) {
            finalStatusMenu.style.display = 'none';
        }

        console.log('Final filters cleared');
    }

    // Clear Temporary Receipts Filters
    function clearTempFilters() {
        // Clear Select2 dropdowns
        $('#temp-filter-adm-name').val(null).trigger('change');
        $('#temp-filter-adm-id').val(null).trigger('change');
        $('#temp-filter-customer').val(null).trigger('change');

        // Clear date field
        const tempDateInput = document.getElementById('temp-filter-date');
        if (tempDateInput) {
            tempDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const tempStatusBtn = document.getElementById('temp-custom-status-btn');
        const tempStatusInput = document.getElementById('temp-status-value');
        if (tempStatusBtn) {
            tempStatusBtn.textContent = 'Choose Status';
            tempStatusBtn.removeAttribute('data-value');
        }
        if (tempStatusInput) {
            tempStatusInput.value = '';
        }

        // Hide status menu if open
        const tempStatusMenu = document.getElementById('temp-custom-status-menu');
        if (tempStatusMenu) {
            tempStatusMenu.style.display = 'none';
        }

        console.log('Temp filters cleared');
    }

    // Clear Advance Payments Filters
    function clearAdvanceFilters() {
        // Clear Select2 dropdowns
        $('#advance-filter-adm-name').val(null).trigger('change');
        $('#advance-filter-adm-id').val(null).trigger('change');
        $('#advance-filter-customer').val(null).trigger('change');

        // Clear date field
        const advanceDateInput = document.getElementById('advance-filter-date');
        if (advanceDateInput) {
            advanceDateInput.value = '';
        }

        // Clear status dropdown and hidden input
        const advanceStatusBtn = document.getElementById('advance-custom-status-btn');
        const advanceStatusInput = document.getElementById('advance-status-value');
        if (advanceStatusBtn) {
            advanceStatusBtn.textContent = 'Choose Status';
            advanceStatusBtn.removeAttribute('data-value');
        }
        if (advanceStatusInput) {
            advanceStatusInput.value = '';
        }

        // Hide status menu if open
        const advanceStatusMenu = document.getElementById('advance-custom-status-menu');
        if (advanceStatusMenu) {
            advanceStatusMenu.style.display = 'none';
        }

        console.log('Advance filters cleared');
    }

    // Auto-submit version of clear filters
    function clearFinalFiltersAndSubmit() {
        clearFinalFilters();
        // Submit the form after a short delay to ensure fields are cleared
        setTimeout(() => {
            document.querySelector('#finalFilter form').submit();
        }, 100);
    }

    function clearTempFiltersAndSubmit() {
        clearTempFilters();
        setTimeout(() => {
            document.querySelector('#trFilter form').submit();
        }, 100);
    }

    function clearAdvanceFiltersAndSubmit() {
        clearAdvanceFilters();
        setTimeout(() => {
            document.querySelector('#receiptsFilter form').submit();
        }, 100);
    }
</script>
   <script>
    $(document).ready(function() {
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('fail'))
        toastr.error("{{ Session::get('fail') }}");
        @endif
    });
</script>