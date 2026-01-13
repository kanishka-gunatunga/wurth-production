@include('layouts.dashboard-header')

<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">Cheque No. - {{ $deposit['id'] }}</h1>
        <button class="black-action-btn-lg submit">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12.0938 16L7.09375 11L8.49375 9.55L11.0938 12.15V4H13.0938V12.15L15.6938 9.55L17.0938 11L12.0938 16ZM6.09375 20C5.54375 20 5.07308 19.8043 4.68175 19.413C4.29042 19.0217 4.09442 18.5507 4.09375 18V15H6.09375V18H18.0938V15H20.0938V18C20.0938 18.55 19.8981 19.021 19.5068 19.413C19.1154 19.805 18.6444 20.0007 18.0938 20H6.09375Z"
                    fill="white" />
            </svg>
            Download
        </button>
    </div>




    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="slip-details">
            <p><span class="bold-text">ADM Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $deposit['adm_name'] }}</span>
            </p>
            <p><span class="bold-text">ADM No. :</span>
                <span class="slip-detail-text">&nbsp;{{ $deposit['adm_number'] }}</span>
            </p>
            <p><span class="bold-text">Deposit Date :</span>
                <span class="slip-detail-text">&nbsp;{{ $deposit['date'] }}</span>

            </p>
            <p><span class="bold-text">Bank Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $deposit['bank_name'] }}</span>
            </p>
            <p><span class="bold-text">Branch Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $deposit['branch_name'] }}</span>
            </p>
            <p><span class="bold-text">Total Amount :</span>
                <span class="slip-detail-text">&nbsp;Rs. {{ number_format($deposit['amount'], 2) }}</span>
            </p>

            <p>
                <span class="bold-text">Status :</span>
                <span class="slip-detail-text">
                    @php
                    $statusClass = match($deposit['status']) {
                    'accepted' => 'success-status-btn',
                    'pending' => 'blue-status-btn',
                    'rejected' => 'danger-status-btn',
                    'declined' => 'danger-status-btn',
                    default => 'grey-status-btn',
                    };
                    @endphp
                    <button class="{{ $statusClass }}">{{ ucfirst($deposit['status']) }}</button>
                </span>
            </p>
  @if(in_array('deposits-cheque-download', session('permissions', [])))  
            <p>
                <span class="bold-text">Attachment Download :</span>
                @if($deposit['attachment_path'])
                <a href="{{ url('cheque-deposits/download', $deposit['id']) }}" class="black-action-btn" style="text-decoration: none;" download>
                    Download
                </a>
                @else
                <button class="black-action-btn" disabled>No File</button>
                @endif

            </p>
@endif
        </div>


        <div class="header-and-content-gap-lg"></div>
        <div class="table-responsive">
            <table class="table unlock-column-table">
                <thead>
                    <tr>
                        <th>Receipt Number</th>
                        <th>Customer Name</th>
                        <th>Customer ID</th>
                        <th>Bank Name</th>
                        <th>Branch Name</th>
                        <th>Customer Paid Date</th>
                        <th>Customer Paid Amount</th>


                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    @php
                    $invoice = $payment->invoice;
                    $customer = $invoice?->customer;
                    @endphp
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $customer?->name ?? 'N/A' }}</td>
                        <td>{{ $invoice?->customer_id ?? 'N/A' }}</td>
                        <td>{{ $payment->bank_name ?? 'N/A' }}</td>
                        <td>{{ $payment->branch_name ?? 'N/A' }}</td>
                        <td>
                            {{ optional($payment)->created_at
                                    ? \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d')
                                    : 'N/A' }}
                        </td>
                        <td>{{ number_format($payment->final_payment, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
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

<!-- Confirmation Modal -->
<div id="confirm-status-modal" class="modal" tabindex="-1" style="display:none; position:fixed; z-index:1050; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3);">
    <div style="background:#fff; border-radius:12px; max-width:460px; margin:10% auto; padding:2rem; position:relative; box-shadow:0 2px 16px rgba(0,0,0,0.2); text-align:center;">

        <!-- Close button -->
        <button id="confirm-modal-close" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:1.5rem; color:#555; cursor:pointer;">&times;</button>

        <!-- Title -->
        <h4 style="margin:1rem 0; font-weight:600; color:#000;">Are you sure?</h4>

        <p style="margin:1rem 0; color:#6c757d;">Do you want to change the status to <span id="confirm-status-text" style="font-weight:600;"></span>?</p>

        <!-- Action buttons -->
        <div style="display:flex; justify-content:center; gap:1rem; margin-top:2rem;">
            <button id="confirm-no-btn" style="padding:0.5rem 1rem; border-radius:12px; border:1px solid #ccc; background:#fff; cursor:pointer;">No</button>
            <button id="confirm-yes-btn" style="padding:0.5rem 1rem; border-radius:12px; border:none; background:#2E7D32; color:#fff; cursor:pointer;">Yes</button>
        </div>
    </div>
</div>



<div class="action-button-lg-row">
    <a href="{{ url('cheque-deposits') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>

    @php
    $currentStatus = strtolower($deposit['status']);
    @endphp
     @if(in_array('deposits-cheque-status', session('permissions', [])))                            
    @if ($currentStatus !== 'accepted')
    <button class="red-action-btn-lg update-status-btn"
        data-id="{{ $deposit['id'] }}"
        data-status="declined">
        Declined
    </button>
    <button class="success-action-btn-lg update-status-btn"
        data-id="{{ $deposit['id'] }}"
        data-status="accepted">
        Accept
    </button>
    @endif
     @endif
</div>

@include('layouts.footer2')

<script>
    // Payment slips data
    const paymentSlipsData = [{
            recieptNumber: "125684588",
            customerName: "Dimo Lanka - Navinna",
            customerId: "1547854445",
            customerPaidDate: "2024.12.26",
            customerPaidAmount: 100000.00,
        },
        {
            recieptNumber: "125684589",
            customerName: "Auto World - Colombo",
            customerId: "1547854446",
            customerPaidDate: "2024.12.25",
            customerPaidAmount: 75000.00,
        },
        {
            recieptNumber: "125684590",
            customerName: "Lanka Motors",
            customerId: "1547854447",
            customerPaidDate: "2024.12.24",
            customerPaidAmount: 120000.00,
        },
        {
            recieptNumber: "125684591",
            customerName: "Super Tyres",
            customerId: "1547854448",
            customerPaidDate: "2024.12.23",
            customerPaidAmount: 95000.00,
        },
        {
            recieptNumber: "125684592",
            customerName: "Speed Auto",
            customerId: "1547854449",
            customerPaidDate: "2024.12.22",
            customerPaidAmount: 110000.00,
        },
        {
            recieptNumber: "125684593",
            customerName: "Car Care Center",
            customerId: "1547854450",
            customerPaidDate: "2024.12.21",
            customerPaidAmount: 80000.00,
        },
        {
            recieptNumber: "125684594",
            customerName: "Auto Parts Hub",
            customerId: "1547854451",
            customerPaidDate: "2024.12.20",
            customerPaidAmount: 105000.00,
        },
        {
            recieptNumber: "125684595",
            customerName: "Lanka Traders",
            customerId: "1547854452",
            customerPaidDate: "2024.12.19",
            customerPaidAmount: 90000.00,
        },
        {
            recieptNumber: "125684596",
            customerName: "Motor City",
            customerId: "1547854453",
            customerPaidDate: "2024.12.18",
            customerPaidAmount: 115000.00,
        },
        {
            recieptNumber: "125684597",
            customerName: "Auto Zone",
            customerId: "1547854454",
            customerPaidDate: "2024.12.17",
            customerPaidAmount: 98000.00,
        },
        {
            recieptNumber: "125684598",
            customerName: "Car Experts",
            customerId: "1547854455",
            customerPaidDate: "2024.12.16",
            customerPaidAmount: 102000.00,
        },
        {
            recieptNumber: "125684599",
            customerName: "Lanka Wheels",
            customerId: "1547854456",
            customerPaidDate: "2024.12.15",
            customerPaidAmount: 87000.00,
        },
        {
            recieptNumber: "125684600",
            customerName: "Auto Solutions",
            customerId: "1547854457",
            customerPaidDate: "2024.12.14",
            customerPaidAmount: 95000.00,
        },
        {
            recieptNumber: "125684601",
            customerName: "Motor Masters",
            customerId: "1547854458",
            customerPaidDate: "2024.12.13",
            customerPaidAmount: 108000.00,
        },
        {
            recieptNumber: "125684602",
            customerName: "Car Point",
            customerId: "1547854459",
            customerPaidDate: "2024.12.12",
            customerPaidAmount: 99000.00,
        },
        {
            recieptNumber: "125684603",
            customerName: "Auto Garage",
            customerId: "1547854460",
            customerPaidDate: "2024.12.11",
            customerPaidAmount: 103000.00,
        },



    ];

    const rowsPerPage = 10;
    const currentPages = {
        paymentSlips: 1
    }; // track pages separately

    // Table render function
    function renderTable(tableId, data, page) {
        const tableBody = document.getElementById(`paymentSlips`);
        tableBody.innerHTML = '';

        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = Math.min(startIndex + rowsPerPage, data.length);

        for (let i = startIndex; i < endIndex; i++) {
            const row = `
                <tr>
                    <td>${data[i].recieptNumber}</td>
                    <td>${data[i].customerName}</td>
                    <td>${data[i].customerId}</td>
                    <td>${data[i].customerPaidDate}</td>
                    <td>${data[i].customerPaidAmount.toFixed(2)}</td>
                    
                </tr>
            `;
            tableBody.innerHTML += row;
        }
    }

    // Pagination render
    function renderPagination(tableId, data) {
        const pagination = document.getElementById(`paymentSlipsPagination`);
        pagination.innerHTML = '';

        const totalPages = Math.ceil(data.length / rowsPerPage);
        const currentPage = currentPages[tableId];

        // Prev button
        pagination.innerHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage('${tableId}', ${currentPage - 1})">Prev</a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage('${tableId}', ${i})">${i}</a>
                </li>
            `;
        }

        // Next button
        pagination.innerHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage('${tableId}', ${currentPage + 1})">Next</a>
            </li>
        `;
    }

    // Page change
    function changePage(tableId, page) {
        const data = getTableData(tableId);
        const totalPages = Math.ceil(data.length / rowsPerPage);

        if (page < 1 || page > totalPages) return;
        currentPages[tableId] = page;

        renderTable(tableId, data, page);
        renderPagination(tableId, data);
    }

    // Helper to get data by tableId
    function getTableData(tableId) {
        if (tableId === 'paymentSlips') return paymentSlipsData;
        return [];
    }

    // Initial load after page ready
    window.onload = function() {
        changePage('paymentSlips', 1);
    };
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

<!-- for approve/reject buttons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const approveBtn = document.querySelector('.success-action-btn-lg');
        const rejectBtn = document.querySelector('.red-action-btn-lg');
        const confirmModal = document.getElementById('confirm-status-modal');
        const confirmText = document.getElementById('confirm-status-text');
        const closeBtn = document.getElementById('confirm-modal-close');
        const noBtn = document.getElementById('confirm-no-btn');
        const yesBtn = document.getElementById('confirm-yes-btn');
        const depositId = "{{ $deposit['id'] }}";
        const statusButton = document.querySelector('.slip-detail-text button');

        let selectedStatus = '';

        // ✅ Open modal when clicking approve/reject
        [approveBtn, rejectBtn].forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                selectedStatus = this.dataset.status;
                confirmText.textContent = selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1);
                confirmModal.style.display = 'block';
            });
        });

        // ✅ Close modal
        [closeBtn, noBtn].forEach(btn => {
            btn.addEventListener('click', function() {
                confirmModal.style.display = 'none';
            });
        });

        // ✅ Confirm (Yes) button
        yesBtn.addEventListener('click', async function() {
            confirmModal.style.display = 'none';

            try {
                const response = await fetch(`{{ url('/cheque-deposits/update-status') }}/${depositId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        status: selectedStatus
                    })
                });

                if (response.ok) {
                    // Update visual status instantly
                    statusButton.textContent = selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1);

                    if (selectedStatus === 'accepted') {
                        statusButton.className = 'success-status-btn';
                        approveBtn.style.display = 'none';
                        rejectBtn.style.display = 'none';
                    } else if (selectedStatus === 'declined') {
                        statusButton.className = 'danger-status-btn';
                        approveBtn.style.display = 'inline-block';
                        rejectBtn.style.display = 'inline-block';
                    }
                } else {
                    alert('Failed to update status. Please try again.');
                }
            } catch (error) {
                console.error(error);
                alert('Error occurred while updating status.');
            }
        });
    });
</script>