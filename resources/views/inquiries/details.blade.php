@include('layouts.dashboard-header')
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">
            Inquiry Ref No. - {{ $inquiry->id ?? 'N/A' }}
        </h1>
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
                <span class="bold-text">Inquiry Type :</span>
                <span class="slip-detail-text">&nbsp;{{ $inquiry->type ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Date :</span>
                <span class="slip-detail-text">&nbsp;{{ $inquiry->created_at ? $inquiry->created_at->format('Y.m.d') : 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">ADM No. :</span>
                <span class="slip-detail-text">&nbsp;{{ $inquiry->adm_id ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">ADM Name :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $inquiry->admin?->userDetails?->name ?? 'N/A' }}
                </span>
            </p>

            <p>
                <span class="bold-text">Customer Name :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $inquiry->customer ?? 'N/A' }}
                </span>
            </p>

            <p>
                <span class="bold-text">Invoice Number :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $inquiry->invoice_number ?? 'N/A' }}
                </span>
            </p>

            <p>
                <span class="bold-text">Reason :</span>
                <span class="slip-detail-text">
                    &nbsp;{{ $inquiry->reason ?? 'N/A' }}
                </span>
            </p>

            <p>
                <span class="bold-text">Status :</span>
                <span class="slip-detail-text">
                    &nbsp;@php
                    $statusClass = match($inquiry->status) {
                    'Sorted' => 'success-status-btn',
                    'Deposited' => 'blue-status-btn',
                    'Rejected' => 'danger-status-btn',
                    default => 'grey-status-btn',
                    };
                    @endphp
                    <button class="{{ $statusClass }}">{{ $inquiry->status }}</button>
                </span>
            </p>

            <p>
                <span class="bold-text">Attachment Download :</span>
                @if($inquiry->attachement)
                <a href="{{ asset('storage/'.$inquiry->attachement) }}" download>
                    <button class="black-action-btn">Download</button>
                </a>
                @else
                <span class="slip-detail-text">No attachment</span>
                @endif
            </p>

        </div>

        <div class="header-and-content-gap-lg"></div>
        <nav class="d-flex justify-content-center mt-5">
            <ul id="paymentSlipsPagination" class="pagination"></ul>
        </nav>
        <div class="action-button-lg-row">
            <a href="{{ url('inquiries') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>

            @php
            $status = strtolower(trim($inquiry->status ?? ''));
            @endphp

            @if(in_array($status, ['pending', 'deposited']))
            <button class="red-action-btn-lg update-status-btn" data-id="{{ $inquiry->id }}" data-status="rejected">Reject</button>
            <button class="success-action-btn-lg update-status-btn" data-id="{{ $inquiry->id }}" data-status="sorted">Approve</button>
            @endif
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










<script>
    const searchInput = document.getElementById('searchInput');
    const searchDropdown = document.getElementById('searchDropdown');

    const items = ['Apple', 'Banana', 'Cherry', 'Date', 'Grape', 'Mango', 'Orange', 'Pineapple', 'Strawberry'];

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        searchDropdown.innerHTML = '';

        if (query) {
            const filteredItems = items.filter(item => item.toLowerCase().includes(query));
            if (filteredItems.length > 0) {
                filteredItems.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'search-item';
                    div.textContent = item;
                    div.addEventListener('click', function() {
                        searchInput.value = item;
                        searchDropdown.classList.remove('show');
                    });
                    searchDropdown.appendChild(div);
                });
                searchDropdown.classList.add('show');
            } else {
                searchDropdown.classList.remove('show');
            }
        } else {
            searchDropdown.classList.remove('show');
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchDropdown.contains(e.target) && e.target !== searchInput) {
            searchDropdown.classList.remove('show');
        }
    });
</script>

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

<!-- for change status -->
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.update-status-btn');
        if (!btn) return; // Ignore clicks outside buttons

        e.preventDefault();

        const inquiryId = btn.dataset.id;
        const status = btn.dataset.status;

        // Determine URL based on status
        let url = '';
        if (status === 'sorted') {
            url = `/inquiries/approve/${inquiryId}`;
        } else if (status === 'rejected') {
            url = `/inquiries/reject/${inquiryId}`;
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update the Status button immediately
                    const slipDetails = document.querySelector('.slip-details');
                    if (slipDetails) {
                        const statusButton = slipDetails.querySelector('p span.slip-detail-text > button');
                        if (statusButton) {
                            statusButton.textContent = data.status;
                            statusButton.className =
                                data.status.toLowerCase() === 'sorted' ? 'success-status-btn' :
                                data.status.toLowerCase() === 'rejected' ? 'danger-status-btn' :
                                'grey-status-btn';
                        }
                    }

                    // Hide all approve/reject footer buttons immediately
                    document.querySelectorAll('.update-status-btn').forEach(b => b.style.display = 'none');

                }
            })
            .catch(err => console.error(err));
    });
</script>