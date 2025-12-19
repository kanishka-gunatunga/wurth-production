@include('layouts.dashboard-header')
<style>
    /* Search box styles */
    #search-box-wrapper {
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

    #search-box-wrapper.collapsed {
        width: 0;
        padding: 0;
        margin: 0;
        border: 1px solid transparent;
        background-color: transparent;
    }

    #search-box-wrapper.expanded {
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
        /* Add padding to make space for the icon */
        padding-left: 30px;
    }

    .search-input::placeholder {
        color: #888;
    }

    .search-icon-inside {
        position: absolute;
        left: 10px;
        /* Adjust as needed */
        color: #888;
    }

    /* Optional: Adjust button alignment if needed */
    .col-12.d-flex.justify-content-lg-end {
        align-items: center;
    }
</style>

<div class="container-fluid">
    <div class="main-wrapper">

        <div class="p-4 pt-0">
            <div class="row d-flex justify-content-between">
                <div class="col-lg-6 col-12">
                    <h1 class="header-title">Return Cheque</h1>
                </div>
                <div class="col-lg-6 col-12 d-flex justify-content-lg-end gap-3 ">
                    <div id="search-box-wrapper" class="collapsed">
                        <i class="fa-solid fa-magnifying-glass fa-xl search-icon-inside"></i>
                        <input type="text" class="search-input" id="backendSearchInput" value="{{ request('search') }}" placeholder="Search ADM No. or Name, Return Cheque Number">
                    </div>
                    <button class="header-btn" id="search-toggle-button"><i
                            class="fa-solid fa-magnifying-glass fa-xl"></i></button>
                    <button class="header-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchByFilter"
                        aria-controls="offcanvasRight"><i class="fa-solid fa-filter fa-xl"></i></button>
                </div>
            </div>

            <hr class="red-line mt-0">



            <div class="col-12 d-flex justify-content-end pe-5 mb-3 gap-3">
                @if(in_array('return-cheques-import', session('permissions', [])))
                <button class="add-new-division-btn"
                    style="background-color: black; color: white; display: flex; align-items: center; gap: 6px; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer;"
                    data-bs-toggle="modal" data-bs-target="#importChequeModal">

                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1784_47778)">
                            <path
                                d="M3.23663 1.24316H12.5471L16.7366 5.43266V17.7432H9.98663V16.2432H15.2366V7.24316H10.7366V2.74316H4.73663V10.2432H3.23663V1.24316ZM12.2366 3.05366V5.74316H14.9261L12.2366 3.05366ZM6.27413 11.0592L9.91763 14.7447L6.27413 18.4302L5.20762 17.3757L7.06763 15.4947H1.73438V13.9947H7.06688L5.20688 12.1137L6.27413 11.0592Z"
                                fill="white" />
                        </g>
                        <defs>
                            <clipPath id="clip0_1784_47778">
                                <rect width="18" height="18" fill="white"
                                    transform="translate(0.986328 0.493164)" />
                            </clipPath>
                        </defs>
                    </svg>

                    <span>Import Return Cheques</span>
                </button>
                @endif
                @if(in_array('return-cheques-import', session('permissions', [])))
                <a href="{{ url('create-return-cheque') }}">
                    <button class="add-new-division-btn">+ Add New Return Cheque</button>
                </a>
                @endif
            </div>

            <div class="table-responsive division-table">
                <table class="table" style="min-width: 1800px;">
                    <thead>
                        <tr>
                            <th>ADM No.</th>
                            <th>ADM Name</th>
                            <th>Customer ID</th>
                            <th>Return Cheque Number</th>
                            <th>Cheque Amount</th>
                            <th>Returned Date</th>
                            <th>Bank Name</th>
                            <th>Branch</th>
                            <th>Return Type</th>
                            <th class="sticky-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returnCheques as $cheque)
                        <tr>
                            {{-- ADM No --}}
                            <td>{{ $cheque->customer->adm ?? 'N/A' }}</td>

                            {{-- ADM Name --}}
                            <td>{{ $cheque->customer->admDetails->name ?? 'N/A' }}</td>

                            {{-- Customer ID --}}
                            <td>{{ $cheque->customer_id ?? 'N/A' }}</td>

                            {{-- Return Cheque Number --}}
                            <td>{{ $cheque->invoice_or_cheque_no }}</td>

                            {{-- Cheque Amount --}}
                            <td>Rs. {{ number_format($cheque->amount, 2) }}</td>

                            {{-- Returned Date --}}
                            <td>{{ \Carbon\Carbon::parse($cheque->returned_date)->format('Y-m-d') }}</td>

                            {{-- Bank --}}
                            <td>{{ $cheque->bank }}</td>

                            {{-- Branch --}}
                            <td>{{ $cheque->branch }}</td>

                            {{-- Return Type --}}
                            <td>{{ $cheque->return_type }}</td>

                            {{-- Actions --}}
                            <td class="sticky-column">
                                @if(in_array('return-cheques-view', session('permissions', [])))
                                <a href="{{ url('return-cheques', $cheque->id) }}" style="text-decoration: none;">
                                    <button class="action-btn btn-sm btn-dark">View More</button>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No return cheques found.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>


            <div class="d-flex justify-content-center mt-4">
                {{ $returnCheques->links('pagination::bootstrap-5') }}
            </div>

        </div>




    </div>
</div>

<!-- Import Return Cheque Modal -->
<div class="modal fade" id="importChequeModal" tabindex="-1" aria-labelledby="importChequeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="importChequeModalLabel">Import Return Cheques</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Drag & Drop Upload Area -->
                <div class="card dotted-card" id="dropZone"
                    style="border: 2px dashed #cc0000; border-radius: 12px; background-color: #fafafa;">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column py-5">

                        <input type="file" class="d-none" id="fileInput" />

                        <div class="upload-circle rounded-circle mb-3"
                            style="width: 70px; height: 70px; background-color: #f8d7da; display: flex; align-items: center; justify-content: center;">
                            <svg width="27" height="28" viewBox="0 0 27 28" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M1.8 1.81465H0V0.0146484H1.8V1.81465ZM9 1.81465H7.2V0.0146484H9V1.81465ZM16.2 1.81465H14.4V0.0146484H16.2V1.81465ZM5.4 5.41465H25.2V12.6146H23.4V7.21465H7.2V23.4146H12.6V25.2146H5.4V5.41465ZM1.8 9.01465H0V7.21465H1.8V9.01465ZM12.8628 12.8774C12.9763 12.764 13.1182 12.683 13.2736 12.6428C13.429 12.6026 13.5923 12.6047 13.7466 12.6488L26.3466 16.2488C26.5251 16.2999 26.6835 16.405 26.7999 16.5497C26.9164 16.6943 26.9853 16.8714 26.9971 17.0568C27.0089 17.2421 26.9631 17.4265 26.866 17.5848C26.7689 17.7431 26.6252 17.8675 26.4546 17.9408L20.4858 20.5004L17.928 26.4692C17.8546 26.6398 17.7303 26.7835 17.572 26.8807C17.4137 26.9778 17.2292 27.0236 17.0439 27.0118C16.8586 26.9999 16.6815 26.9311 16.5368 26.8146C16.3922 26.6981 16.2871 26.5398 16.236 26.3612L12.636 13.7612C12.5919 13.6073 12.5897 13.4445 12.6296 13.2894C12.6695 13.1344 12.75 12.991 12.8628 12.8774ZM14.8104 14.8268L17.2692 23.4344L18.972 19.4618C19.063 19.249 19.2326 19.0795 19.4454 18.9884L23.418 17.2856L14.8104 14.8268ZM1.8 16.2146H0V14.4146H1.8V16.2146Z"
                                    fill="#CC0000" />
                            </svg>
                        </div>

                        <p class="title mb-1" style="font-weight: 600; color: #333;">Drag files here</p>
                        <p class="info text-muted">or click to upload (Max size: 10MB)</p>
                        <p id="fileName" class="mt-2 fw-bold text-secondary"></p>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: black;">Cancel</button>
                <button type="button" class="btn btn-danger" id="uploadBtn">Submit</button>
            </div>
        </div>
    </div>
</div>

<form id="filterForm" method="GET" action="{{ url('return-cheques') }}">
    <div class="offcanvas offcanvas-end offcanvas-filter" tabindex="-1" id="searchByFilter"
        aria-labelledby="offcanvasRightLabel">
        <div class="row d-flex justify-content-end">
            <button type="button" class="btn-close rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-header d-flex justify-content-between">
            <div class="col-6">
                <span class="offcanvas-title" id="offcanvasRightLabel">Search </span> <span class="title-rest"> &nbsp;by
                    Filter
                </span>
            </div class="col-6">

            <div>
                <button type="button" class="btn rounded-phill" id="clear-filters">Clear All</button>
            </div>
        </div>
        <div class="offcanvas-body">
            <!-- <div class="row">
            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>ADMs</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Marketing</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Admin</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Finance</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Team Leaders</span>

            </div>

            <div class="col-4 filter-tag d-flex align-items-center justify-content-between selectable-filter">
                <span>Head of Division</span>

            </div>
        </div> -->

            <!-- ADM Name Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM Name</p>
                <select id="filter-adm-name" name="adm_names[]" class="form-control select2" multiple>
                    @foreach ($returnCheques->pluck('customer.admDetails.name')->unique() as $admName)
                    @if($admName)
                    <option value="{{ $admName }}"
                        {{ !empty($filters['adm_names']) && in_array($admName, $filters['adm_names']) ? 'selected' : '' }}>
                        {{ $admName }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <!-- ADM ID Dropdown -->
            <div class="mt-5 filter-categories">
                <p class="filter-title">ADM ID</p>
                <select id="filter-adm-id" name="adm_ids[]" class="form-control select2" multiple>
                    @foreach ($returnCheques->pluck('customer.adm')->unique() as $admId)
                    @if($admId)
                    <option value="{{ $admId }}"
                        {{ !empty($filters['adm_ids']) && in_array($admId, $filters['adm_ids']) ? 'selected' : '' }}>
                        {{ $admId }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">Return Type</p>
                <select id="filter-return-type" name="return_type[]" class="form-control select2" multiple>
                    @foreach ($returnCheques->pluck('return_type')->unique() as $type)
                    @if($type)
                    <option value="{{ $type }}"
                        {{ !empty($filters['return_type']) && in_array($type, $filters['return_type']) ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <div class="mt-5 filter-categories">
                <p class="filter-title">Returned Date</p>
                <input type="text" id="filter-date" name="date_range" class="form-control"
                    placeholder="Select date range"
                    value="{{ $filters['date_range'] ?? '' }}" />
            </div>

            <div class="mt-4 d-flex justify-content-start">
                <button type="submit" class="red-action-btn-lg">Apply Filters</button>
            </div>
        </div>
    </div>
    </div>
</form>


<!-- expand search bar  -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchWrapper = document.getElementById("search-box-wrapper");
        const searchToggleButton = document.getElementById("search-toggle-button");
        const searchInput = searchWrapper.querySelector(".search-input");

        let idleTimeout;
        const idleTime = 5000; // 5 seconds (5000 milliseconds)

        function collapseSearch() {
            searchWrapper.classList.remove("expanded");
            searchWrapper.classList.add("collapsed");
            searchToggleButton.classList.remove("d-none"); // Show the button
            clearTimeout(idleTimeout); // Clear any existing timer
        }

        function startIdleTimer() {
            clearTimeout(idleTimeout); // Clear previous timer
            idleTimeout = setTimeout(() => {
                if (!searchInput.value) { // Only collapse if input is empty
                    collapseSearch();
                }
            }, idleTime);
        }

        searchToggleButton.addEventListener("click", function() {
            if (searchWrapper.classList.contains("collapsed")) {
                searchWrapper.classList.remove("collapsed");
                searchWrapper.classList.add("expanded");
                searchToggleButton.classList.add("d-none"); // Hide the button
                searchInput.focus();
                startIdleTimer();
            } else {
                collapseSearch();
            }
        });

        searchInput.addEventListener("keydown", function() {
            startIdleTimer(); // Reset the timer on any keypress
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropZone = document.getElementById("dropZone");
        const fileInput = document.getElementById("fileInput");
        const fileNameDisplay = document.getElementById("fileName");
        const uploadBtn = document.getElementById("uploadBtn");

        // When clicking the drop area → open file chooser
        dropZone.addEventListener("click", () => fileInput.click());

        // When a file is selected manually
        fileInput.addEventListener("change", () => {
            const file = fileInput.files[0];
            if (file) fileNameDisplay.textContent = `Selected: ${file.name}`;
        });

        // Handle drag events
        dropZone.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = "#fff5f5";
            dropZone.style.borderColor = "#a10000";
        });

        dropZone.addEventListener("dragleave", () => {
            dropZone.style.backgroundColor = "#fafafa";
            dropZone.style.borderColor = "#cc0000";
        });

        // Handle dropped file
        dropZone.addEventListener("drop", (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = "#fafafa";
            dropZone.style.borderColor = "#cc0000";

            const file = e.dataTransfer.files[0];
            if (file) {
                fileInput.files = e.dataTransfer.files;
                fileNameDisplay.textContent = `Selected: ${file.name}`;
            }
        });


        uploadBtn.addEventListener("click", () => {
            if (!fileInput.files.length) {
                alert("Please select a file first!"); 
                return;
            }

            const formData = new FormData();
            formData.append("file", fileInput.files[0]);

            uploadBtn.disabled = true;
            uploadBtn.textContent = "Uploading...";

            fetch("{{ url('return-cheques/import') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = "Submit";
                    location.reload();
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error during upload. Please try again!");
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = "Submit";
                });
        });


    });
</script>

<!-- search on enter key press -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const input = document.getElementById("backendSearchInput");

        input.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                const value = input.value.trim();

                // Redirect to backend route with ?search= query
                const url = new URL(window.location.href);
                url.searchParams.set("search", value);

                window.location.href = url.toString();
            }
        });

    });
</script>

<!-- clear all button functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const clearBtn = document.getElementById('clear-filters');
        clearBtn.addEventListener('click', function() {
            // Clear multi-select dropdowns (ADM Name, ADM ID, Return Type)
            $('#filter-adm-name, #filter-adm-id, #filter-return-type').val(null).trigger('change');

            // Clear date range input
            document.getElementById('filter-date').value = '';

            // Clear search input if needed
            // document.getElementById('backendSearchInput').value = '';

            // Submit the form to reload page without filters
            document.getElementById('filterForm').submit();
        });
    });
</script>