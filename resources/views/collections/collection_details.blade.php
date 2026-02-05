@include('layouts.dashboard-header')
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">Collection ID - {{ $batch->id }}</h1>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>

        <!-- Invoices/Return Cheque Table -->
        <div class="header-and-content-gap-lg"></div>
        <div class="table-responsive">
            <table class="table unlock-column-table">
                <thead>
                    <tr>
                        <th>Receipt No.</th>
                        <th>Customer Name</th>
                        <th>Invoice no.</th>
                        <th>Payment method</th>
                        <th>Status</th>
                        <th class="sticky-column">Reciept Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment['receipt_no'] }}</td>
                        <td>{{ $payment['customer_name'] }}</td>
                        <td>{{ $payment['invoice_no'] }}</td>
                        <td>{{ ucfirst($payment['payment_method']) }}</td>
                        <td>
                            <button class="
                                    @if(strtolower($payment['status']) === 'approved') success-status-btn
                                    @elseif(strtolower($payment['status']) === 'deposited') blue-status-btn
                                    @elseif(strtolower($payment['status']) === 'voided') danger-status-btn
                                    @else grey-status-btn @endif">
                                {{ ucfirst($payment['status']) }}
                            </button>
                        </td>
                        <td class="sticky-column">{{ $payment['amount'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No payments found for this collection.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="d-flex justify-content-center mt-5">
            <ul id="paymentSlipsInvoicesPagination" class="pagination"></ul>
        </nav>
    </div>
</div>
@include('layouts.footer2')
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


<div class="action-button-lg-row">
    <a href="{{ url('all-collections') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>
</div>

<script>
    // Sample data for Payment Slips Invoices
    const paymentSlipsInvoicesData = [{
            receiptNo: 'RCPT001',
            customerName: 'John Doe',
            invoiceNo: 'INV1001',
            paymentMethod: 'Credit Card',
            status: 'Pending',
            amount: 1500.00
        },
        {
            receiptNo: 'RCPT002',
            customerName: 'Jane Smith',
            invoiceNo: 'INV1002',
            paymentMethod: 'PayPal',
            status: 'Deposited',
            amount: 2000.00
        },
        {
            receiptNo: 'RCPT003',
            customerName: 'Bob Johnson',
            invoiceNo: 'INV1003',
            paymentMethod: 'Bank Transfer',
            status: 'Approved',
            amount: 2500.00
        },
        {
            receiptNo: 'RCPT004',
            customerName: 'Alice Brown',
            invoiceNo: 'INV1004',
            paymentMethod: 'Credit Card',
            status: 'Rejected',
            amount: 3000.00
        }
    ];

    const rowsPerPage = 5;
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', function() {
        function renderPaymentSlipsInvoicesTable() {
            const tableBody = document.getElementById('paymentSlipsInvoices');
            tableBody.innerHTML = '';

            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, paymentSlipsInvoicesData.length);

            for (let i = startIndex; i < endIndex; i++) {
                let statusClass = '';
                switch (paymentSlipsInvoicesData[i].status) {
                    case 'Approved':
                    case 'approved':
                        statusClass = 'success-status-btn';
                        break;
                    case 'Deposited':
                    case 'deposited':
                        statusClass = 'blue-status-btn';
                        break;
                    case 'Voided':
                    case 'voided':
                        statusClass = 'danger-status-btn';
                        break;
                    default:
                        statusClass = 'grey-status-btn';
                }
                const row = `
                <tr>
                    <td>${paymentSlipsInvoicesData[i].receiptNo}</td>
                    <td>${paymentSlipsInvoicesData[i].customerName}</td>
                    <td>${paymentSlipsInvoicesData[i].invoiceNo}</td>   
                    <td>${paymentSlipsInvoicesData[i].paymentMethod}</td>
                    <td><button class="${statusClass}">${paymentSlipsInvoicesData[i].status}</button></td>
                    <td class="sticky-column">${paymentSlipsInvoicesData[i].amount.toFixed(2)}</td>
                </tr>
            `;
                tableBody.insertAdjacentHTML('beforeend', row);
            }
        }

        function renderPagination() {
            const pagination = document.getElementById('paymentSlipsInvoicesPagination');
            pagination.innerHTML = '';

            const totalPages = Math.ceil(paymentSlipsInvoicesData.length / rowsPerPage);
            for (let i = 1; i <= totalPages; i++) {
                const pageItem = document.createElement('li');
                pageItem.className = 'page-item';
                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = i;
                    renderPaymentSlipsInvoicesTable();
                    renderPagination();
                });
                pagination.appendChild(pageItem);
            }
        }

        // Initial render
        renderPaymentSlipsInvoicesTable();
        renderPagination();
    });
</script>

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