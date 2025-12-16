@include('layouts.dashboard-header')

<style>
    .import {
        background-color: white;
    }

    .import .main-card {
        border-radius: 0 !important;
        border: none !important;
    }

    .import .card-title {
        font-family: "Poppins", sans-serif;
        font-size: 18px;
        font-weight: 600;

    }

    .import p {
        font-family: "Poppins", sans-serif;
        font-size: 10px;
        font-weight: 400;
        color: #00000080;
    }

    .import .dotted-card {
        border-style: dashed;
        border-color: #CC0000;
        border-radius: 20px;
        width: auto;
        height: 240px;
    }


    .file-upload .title,
    .file-upload .info {
        font-family: "Poppins", sans-serif;
        font-size: 10px;
        font-weight: 400;
        color: #000000;
        display: flex;
        justify-content: center;
        margin-bottom: 5px;

    }

    .file-upload .info {
        color: #00000080;
    }

    .file-name {
        display: flex;
        justify-content: center;


    }

    .upload-circle {
        background-color: #771d1d0d;
        padding: 30px;
    }

    /* Below 1384px → keep width same, allow scrolling */
    @media (max-width: 1384px) {
        .table-responsive {
            overflow-x: auto;
            /* enable horizontal scroll */
        }

        .table-responsive table {
            min-width: 35vw;
            /* force same width as 1385px */
        }
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .icon {
        width: 56px;
        height: 56px;
        background: #CC0000;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .icon svg {
        width: 28px;
        height: 28px;
    }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between ms-3">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Report Generator</h1>
        </div>
    </div>

    <hr class="red-line mt-0">

    <div class="row d-flex gap-4 ms-3">

        <div class="styled-tab-sub p-4" style="border-radius: 8px;">

            <div class="container">
                <div class="header">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M15 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V7L15 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M14 2V6C14 6.53043 14.2107 7.03914 14.5858 7.41421C14.9609 7.78929 15.4696 8 16 8H20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10 9H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M16 13H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M16 17H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <label for="head-of-division-select" class="form-label custom-input-label">
                        Select Report Type
                    </label>
                </div>

                <div class="dropdown w-100">
                    <button class="btn custom-dropdown w-100 text-start" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        -- Select a Report --
                        <span class="custom-arrow"></span>
                    </button>
                    <ul class="dropdown-menu custom-dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        <li class="dropdown-item fw-bold text-dark">
                            ORMR – Outstanding Receivable Management Reports
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="ARA">ARA - Accounts Receivable Aging (Dynamic Age Brackets)</a></li>
                        <li><a class="dropdown-item" href="#" data-report="YOO">YOO - Year-on-Year Outstanding</a></li>
                        <li><a class="dropdown-item" href="#" data-report="MOM">MOM - Month-on-Month Outstanding</a></li>
                        <li><a class="dropdown-item" href="#" data-report="ODB">ODB - Outstanding Days Breakdown (Age Analysis)</a></li>

                        <li class="dropdown-item fw-bold text-dark">
                            RPI – Receivable Performance Indicators
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="DSO">DSO - Days Sales Outstanding</a></li>
                        <li><a class="dropdown-item" href="#" data-report="CCD">CCD - Collection Cycle Days (ADM)</a></li>
                        <li><a class="dropdown-item" href="#" data-report="TVC">TvC-AREC - Turnover vs Collection – AR Exposure Control</a></li>

                        <li class="dropdown-item fw-bold text-dark">
                            CMR - Collection Management Report
                        </li>
                        <li><a class="dropdown-item" href="#" data-report="DCT">DCT - Daily Collection Tracker (ADM)</a></li>
                        <li><a class="dropdown-item" href="#" data-report="PBD">PBD - Pending Bank Deposit</a></li>
                        <li><a class="dropdown-item" href="#" data-report="DMDR">DMDR - Deposit Mismatch & Decline Register</a></li>
                        <li><a class="dropdown-item" href="#" data-report="PDCT">PDCT - Post-Dated Cheques Tracker</a></li>
                        <li><a class="dropdown-item" href="#" data-report="RCS">RCS - Returned Cheque Summary</a></li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="ARA" style="border-radius: 8px;">
            <div class="row d-flex justify-content-between">
                <div class="mb-4 col-12 col-lg-6">
                    <p class="filter-title">Date Range</p>
                    <input type="text" id="filter-date" name="date_range" class="form-control"
                        placeholder="DD/MM/YYYY"
                        value="{{ $filters['date_range'] ?? '' }}" />
                </div>

                <div class="col-12 col-lg-6 mb-4">
                    <label class="form-label custom-input-label">Division</label>
                    <div class="multiselect-wrapper" data-name="division">
                        <div class="multiselect-trigger">
                            <div class="multiselect-content">
                                <span class="multiselect-placeholder">Select Division</span>
                            </div>
                            <span class="multiselect-arrow"></span>
                        </div>
                        <div class="multiselect-dropdown">
                            <div class="multiselect-search">
                                <input type="text" placeholder="Search...">
                            </div>
                            <div class="multiselect-actions">
                                <button class="select-all">Select All</button>
                                <button class="clear-all">Clear All</button>
                            </div>
                            <div class="multiselect-options">
                                <div class="multiselect-option">
                                    <input type="checkbox" id="div1" value="Automotive Division">
                                    <label for="div1">Automotive Division</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="div2" value="Construction Division">
                                    <label for="div2">Construction Division</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="div3" value="Division 1">
                                    <label for="div3">Division 1</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="div4" value="Division 2">
                                    <label for="div4">Division 2</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="div5" value="Division 3">
                                    <label for="div5">Division 3</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="div6" value="Division 4">
                                    <label for="div6">Division 4</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="div7" value="Division 5">
                                    <label for="div7">Division 5</label>
                                </div>
                            </div>
                            <div class="multiselect-footer">
                                <span class="count-text">0 of 7 selected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mb-4">
                    <label class="form-label custom-input-label">ADM</label>
                    <div class="multiselect-wrapper" data-name="adm">
                        <div class="multiselect-trigger">
                            <div class="multiselect-content">
                                <span class="multiselect-placeholder">Select ADM</span>
                            </div>
                            <span class="multiselect-arrow"></span>
                        </div>
                        <div class="multiselect-dropdown">
                            <div class="multiselect-search">
                                <input type="text" placeholder="Search...">
                            </div>
                            <div class="multiselect-actions">
                                <button class="select-all">Select All</button>
                                <button class="clear-all">Clear All</button>
                            </div>
                            <div class="multiselect-options">
                                <div class="multiselect-option">
                                    <input type="checkbox" id="adm1" value="ADM 1">
                                    <label for="adm1">ADM 1</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="adm2" value="ADM 2">
                                    <label for="adm2">ADM 2</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="adm3" value="ADM 3">
                                    <label for="adm3">ADM 3</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="adm4" value="ADM 4">
                                    <label for="adm4">ADM 4</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="adm5" value="ADM 5">
                                    <label for="adm5">ADM 5</label>
                                </div>
                            </div>
                            <div class="multiselect-footer">
                                <span class="count-text">0 of 5 selected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mb-4">
                    <label class="form-label custom-input-label">Customer</label>
                    <div class="multiselect-wrapper" data-name="customer">
                        <div class="multiselect-trigger">
                            <div class="multiselect-content">
                                <span class="multiselect-placeholder">Select Customer</span>
                            </div>
                            <span class="multiselect-arrow"></span>
                        </div>
                        <div class="multiselect-dropdown">
                            <div class="multiselect-search">
                                <input type="text" placeholder="Search...">
                            </div>
                            <div class="multiselect-actions">
                                <button class="select-all">Select All</button>
                                <button class="clear-all">Clear All</button>
                            </div>
                            <div class="multiselect-options">
                                <div class="multiselect-option">
                                    <input type="checkbox" id="cust1" value="Customer 1">
                                    <label for="cust1">Customer 1</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="cust2" value="Customer 2">
                                    <label for="cust2">Customer 2</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="cust3" value="Customer 3">
                                    <label for="cust3">Customer 3</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="cust4" value="Customer 4">
                                    <label for="cust4">Customer 4</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="cust5" value="Customer 5">
                                    <label for="cust5">Customer 5</label>
                                </div>
                            </div>
                            <div class="multiselect-footer">
                                <span class="count-text">0 of 5 selected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mb-4">
                    <label class="form-label custom-input-label">Team Leader</label>
                    <div class="multiselect-wrapper" data-name="teamleader">
                        <div class="multiselect-trigger">
                            <div class="multiselect-content">
                                <span class="multiselect-placeholder">Select Team Leader</span>
                            </div>
                            <span class="multiselect-arrow"></span>
                        </div>
                        <div class="multiselect-dropdown">
                            <div class="multiselect-search">
                                <input type="text" placeholder="Search...">
                            </div>
                            <div class="multiselect-actions">
                                <button class="select-all">Select All</button>
                                <button class="clear-all">Clear All</button>
                            </div>
                            <div class="multiselect-options">
                                <div class="multiselect-option">
                                    <input type="checkbox" id="tl1" value="Team Leader 1">
                                    <label for="tl1">Team Leader 1</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="tl2" value="Team Leader 2">
                                    <label for="tl2">Team Leader 2</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="tl3" value="Team Leader 3">
                                    <label for="tl3">Team Leader 3</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="tl4" value="Team Leader 4">
                                    <label for="tl4">Team Leader 4</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="tl5" value="Team Leader 5">
                                    <label for="tl5">Team Leader 5</label>
                                </div>
                            </div>
                            <div class="multiselect-footer">
                                <span class="count-text">0 of 5 selected</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mb-4">
                    <label class="form-label custom-input-label">Supervisor</label>
                    <div class="multiselect-wrapper" data-name="supervisor">
                        <div class="multiselect-trigger">
                            <div class="multiselect-content">
                                <span class="multiselect-placeholder">Select Supervisor</span>
                            </div>
                            <span class="multiselect-arrow"></span>
                        </div>
                        <div class="multiselect-dropdown">
                            <div class="multiselect-search">
                                <input type="text" placeholder="Search...">
                            </div>
                            <div class="multiselect-actions">
                                <button class="select-all">Select All</button>
                                <button class="clear-all">Clear All</button>
                            </div>
                            <div class="multiselect-options">
                                <div class="multiselect-option">
                                    <input type="checkbox" id="sup1" value="Supervisor 1">
                                    <label for="sup1">Supervisor 1</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="sup2" value="Supervisor 2">
                                    <label for="sup2">Supervisor 2</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="sup3" value="Supervisor 3">
                                    <label for="sup3">Supervisor 3</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="sup4" value="Supervisor 4">
                                    <label for="sup4">Supervisor 4</label>
                                </div>
                                <div class="multiselect-option">
                                    <input type="checkbox" id="sup5" value="Supervisor 5">
                                    <label for="sup5">Supervisor 5</label>
                                </div>
                            </div>
                            <div class="multiselect-footer">
                                <span class="count-text">0 of 5 selected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-button-lg-row">
                <button class="red-action-btn-lg mb-3 submit">
                    Generate Report
                </button>
            </div>
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="DSO">
            <!-- DSO-specific filters -->
        </div>

        <div class="styled-tab-sub p-4 report-filters d-none" data-report="MOM">
            <!-- MOM-specific filters -->
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
            Report downloaded successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>


<!-- Report dropdown → show filters -->
<script>
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        const button = dropdown.querySelector('.custom-dropdown');
        const items = dropdown.querySelectorAll('.dropdown-item[data-report]');
        const allFilterSections = document.querySelectorAll('.report-filters');

        items.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const selectedText = this.textContent.trim();
                const reportKey = this.getAttribute('data-report');

                // Update dropdown button text
                button.innerHTML = selectedText + '<span class="custom-arrow"></span>';

                // Hide all filter sections
                allFilterSections.forEach(section => {
                    section.classList.add('d-none');
                });

                // Show only matching filter section
                const activeSection = document.querySelector(
                    `.report-filters[data-report="${reportKey}"]`
                );

                if (activeSection) {
                    activeSection.classList.remove('d-none');
                }
            });
        });
    });
</script>

<!-- Toast message on submit -->
<script>
    document.querySelector('.submit').addEventListener('click', function(e) {
        e.preventDefault();
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    });
</script>

<!-- Multiselect dropdown logic -->
<script>
    // Initialize all multiselect dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        const multiselectWrappers = document.querySelectorAll('.multiselect-wrapper');

        multiselectWrappers.forEach(wrapper => {
            const trigger = wrapper.querySelector('.multiselect-trigger');
            const dropdown = wrapper.querySelector('.multiselect-dropdown');
            const content = wrapper.querySelector('.multiselect-content');
            const searchInput = wrapper.querySelector('.multiselect-search input');
            const selectAllBtn = wrapper.querySelector('.select-all');
            const clearAllBtn = wrapper.querySelector('.clear-all');
            const options = wrapper.querySelectorAll('.multiselect-option');
            const checkboxes = wrapper.querySelectorAll('input[type="checkbox"]');
            const countText = wrapper.querySelector('.count-text');

            // Toggle dropdown
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const isActive = dropdown.classList.contains('show');

                // Close all other dropdowns
                document.querySelectorAll('.multiselect-dropdown').forEach(dd => {
                    dd.classList.remove('show');
                });
                document.querySelectorAll('.multiselect-trigger').forEach(t => {
                    t.classList.remove('active');
                });

                if (!isActive) {
                    dropdown.classList.add('show');
                    trigger.classList.add('active');
                }
            });

            // Update display
            function updateDisplay() {
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                content.innerHTML = '';

                if (selected.length === 0) {
                    content.innerHTML = `<span class="multiselect-placeholder">${trigger.getAttribute('data-placeholder') || 'Select options'}</span>`;
                } else {
                    selected.forEach(value => {
                        const tag = document.createElement('span');
                        tag.className = 'multiselect-tag';
                        tag.innerHTML = `
                                ${value}
                                <span class="tag-close" data-value="${value}">×</span>
                            `;
                        content.appendChild(tag);
                    });
                }

                // Update count
                const total = checkboxes.length;
                countText.textContent = `${selected.length} of ${total} selected`;

                // Add event listeners to tag close buttons
                content.querySelectorAll('.tag-close').forEach(closeBtn => {
                    closeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.getAttribute('data-value');
                        const checkbox = Array.from(checkboxes).find(cb => cb.value === value);
                        if (checkbox) {
                            checkbox.checked = false;
                            updateDisplay();
                        }
                    });
                });
            }

            // Store original placeholder
            trigger.setAttribute('data-placeholder', content.querySelector('.multiselect-placeholder').textContent);

            // Checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateDisplay);
            });

            // Select all
            selectAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const visibleCheckboxes = Array.from(checkboxes).filter(cb =>
                    cb.closest('.multiselect-option').style.display !== 'none'
                );
                visibleCheckboxes.forEach(cb => cb.checked = true);
                updateDisplay();
            });

            // Clear all
            clearAllBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                checkboxes.forEach(cb => cb.checked = false);
                updateDisplay();
            });

            // Search functionality
            searchInput.addEventListener('input', function(e) {
                e.stopPropagation();
                const searchTerm = this.value.toLowerCase();

                options.forEach(option => {
                    const label = option.querySelector('label').textContent.toLowerCase();
                    if (label.includes(searchTerm)) {
                        option.style.display = 'flex';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });

            // Prevent dropdown close on click inside
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Initialize display
            updateDisplay();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.multiselect-dropdown').forEach(dd => {
                dd.classList.remove('show');
            });
            document.querySelectorAll('.multiselect-trigger').forEach(t => {
                t.classList.remove('active');
            });
        });
    });
</script>

@include('layouts.footer2')