@include('finance::layouts.header')

<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">Advance Payment - {{ $payment->id }}</h1>
        @if($payment->attachment)
        <a href="{{ asset('storage/attachments/' . $payment->attachment) }}" download>
            <button class="black-action-btn-lg submit">
                <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.0938 16L7.09375 11L8.49375 9.55L11.0938 12.15V4H13.0938V12.15L15.6938 9.55L17.0938 11L12.0938 16ZM6.09375 20C5.54375 20 5.07308 19.8043 4.68175 19.413C4.29042 19.0217 4.09442 18.5507 4.09375 18V15H6.09375V18H18.0938V15H20.0938V18C20.0938 18.55 19.8981 19.021 19.5068 19.413C19.1154 19.805 18.6444 20.0007 18.0938 20H6.09375Z" fill="white" />
                </svg>
                Receipt Download
            </button>
        </a>
        @endif
    </div>




    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="slip-details">
            <p>
                <span class="bold-text">ADM Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->admin?->userDetails?->name ?? 'N/A' }}</span>
            </p>
            <p>
                <span class="bold-text">ADM No. :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->adm_id ?? 'N/A' }}</span>
            </p>
            <p>
                <span class="bold-text">Date :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->created_at->format('Y-m-d') ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Payment Amount :</span>
                <span class="slip-detail-text">&nbsp;Rs. {{ number_format($payment->payment_amount, 2) }}</span>
            </p>

            <p>
                <span class="bold-text">Customer Name :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->customerData?->name ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Customer ID :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->customerData?->customer_id ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Mobile Number :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->mobile_no ?? 'N/A' }}</span>
            </p>

            <p>
                <span class="bold-text">Reason :</span>
                <span class="slip-detail-text">&nbsp;{{ $payment->reason ?? 'N/A' }}</span>
            </p>

            @if($payment->attachment)
            <p>
                <span class="bold-text">Attachment Download :</span>
                <a href="{{ asset('storage/attachments/' . $payment->attachment) }}" download>
                    <button class="black-action-btn">Download</button>
                </a>
            </p>
            @endif
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


@section('footer-buttons')
<a href="{{ route('advanced_payments.index') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>
@endsection




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

<!-- for reject modal pop-up -->
<script>
    document.addEventListener('click', function(e) {
        // Approve button click
        if (e.target.classList.contains('success-action-btn-lg')) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('approve-modal').style.display = 'block';
            document.getElementById('approve-modal-input').value = '';
        }
        // Approve modal tick
        if (e.target.id === 'approve-modal-tick' || e.target.closest('#approve-modal-tick')) {
            document.getElementById('approve-modal').style.display = 'none';
        }
        // Approve modal close
        if (e.target.id === 'approve-modal-close') {
            document.getElementById('approve-modal').style.display = 'none';
        }

        // Reject button click
        if (e.target.classList.contains('red-action-btn-lg')) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('reject-modal').style.display = 'block';
            // Optionally clear input fields here if needed
            var inputs = document.querySelectorAll('#reject-modal input');
            inputs.forEach(function(input) {
                input.value = '';
            });
        }
        // Reject modal tick
        if (e.target.id === 'reject-modal-tick' || e.target.closest('#reject-modal-tick')) {
            document.getElementById('reject-modal').style.display = 'none';
        }
        // Reject modal close
        if (e.target.id === 'reject-modal-close') {
            document.getElementById('reject-modal').style.display = 'none';
        }
    });
</script>

@include('finance::layouts.footer2')