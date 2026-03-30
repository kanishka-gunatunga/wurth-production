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
        border: 1px solid #ddd;
        position: relative;
    }

    #search-box-wrapper.collapsed {
        width: 0;
        padding: 0;
        margin: 0;
        border: 1px solid transparent;
        background-color: transparent;
    }

    #search-box-wrapper.expanded {
        width: 300px;
        padding: 0 15px;
        border: 1px solid #ddd;
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
    }

    .search-input::placeholder {
        color: #888;
    }

    .search-icon-inside {
        position: absolute;
        left: 10px;
        color: #888;
    }

    .header-action-icons {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-action-icons i {
        font-size: 24px;
        cursor: pointer;
        color: #333;
    }

    /* Custom Pagination Styles */
    .pagination .page-link {
        border: none;
        color: #333;
        margin: 0 5px;
        border-radius: 5px !important;
    }

    .pagination .page-item.active .page-link {
        background-color: #CC0000;
        color: #fff;
    }

    .pagination .page-item.disabled .page-link {
        color: #ccc;
    }

    /* Hide scrollbar while allowing scroll */
    .table-responsive::-webkit-scrollbar {
        display: none;
    }
    .table-responsive {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
        overflow-x: auto;
    }

    /* Sticky column for Payment Status */
    .sticky-column {
        position: sticky;
        right: 0;
        background-color: #fff;
        z-index: 10;
        /* box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05); */
    }

    #collectionTable thead th.sticky-column {
        background-color: #fff;
    }

    .status-paid { color: #16A34A; font-weight: 500; }
    .status-pending { color: #EA580C; font-weight: 500; }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Collections</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-end align-items-center">
            <div class="header-action-icons">
                <div id="search-box-wrapper" class="collapsed">
                    <i class="fa-solid fa-magnifying-glass search-icon-inside"></i>
                    <input type="text" id="tableSearch" class="search-input" placeholder="Search collections...">
                </div>
                <i class="fa-solid fa-magnifying-glass" id="searchTrigger"></i>
                <i class="fa-solid fa-filter"></i>
            </div>
        </div>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>
        <div class="col-12 d-flex justify-content-end mb-3">
            <a href="{{ route('add_new_payment') }}" style="text-decoration: none;">
                <button class="red-action-btn-lg add-new-payment-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.50726 10.5634H4.85938V9.0141H9.50726V4.36621H11.0566V9.0141H15.7044V10.5634H11.0566V15.2113H9.50726V10.5634Z"
                            fill="white" />
                    </svg>
                    Add New Payment
                </button>
            </a>
        </div>


        <div class="table-responsive">
            <table class="table custom-table-locked" id="collectionTable" style="min-width: 1000px;">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Address <i class="fa-solid fa-chevron-down ms-1" style="font-size: 12px;"></i></th>
                        <th id="dateHeader">Date</th>
                        <th>Amount</th>
                        <th class="sticky-column">Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($depositedInvoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->name }}</td>
                            <td>{{ $invoice->mobile_number }}</td>
                            <td>{{ $invoice->address }}</td>
                            <td>{{ $invoice->updated_at->format('Y-m-d') }}</td>
                            <td>Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="sticky-column">
                                @php
                                    $paymentStatus = $invoice->payment->status ?? 'pending';
                                    $statusClass = 'status-' . strtolower($paymentStatus);
                                @endphp
                                <span class="{{ $statusClass }}">{{ ucfirst($paymentStatus) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $depositedInvoices->links() }}
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchTrigger = document.getElementById('searchTrigger');
        const searchBox = document.getElementById('search-box-wrapper');
        const tableSearch = document.getElementById('tableSearch');

        // Toggle search box visible
        searchTrigger.addEventListener('click', function() {
            searchBox.classList.toggle('collapsed');
            searchBox.classList.toggle('expanded');
            if (searchBox.classList.contains('expanded')) {
                tableSearch.focus();
            }
        });

        // Search functionality
        tableSearch.addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('#collectionTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    });
</script>

@include('layouts.footer2')
