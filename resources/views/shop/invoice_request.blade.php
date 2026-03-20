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

    th.sortable {
        cursor: pointer;
    }

    th.sortable:hover {
        background-color: #f8f9fa;
    }

    /* Hide scrollbar while allowing scroll */
    .table-responsive::-webkit-scrollbar {
        display: none;
    }
    .table-responsive {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
        overflow-x: auto; /* Ensure horizontal scroll is enabled */
    }

    /* Sticky column for Approval Status */
    .sticky-column {
        position: sticky;
        right: 0;
        background-color: #fff;
        z-index: 10;
        /* box-shadow: -2px 0 5px rgba(0, 0, 0, 0.05); */
    }

    #invoiceTable thead th.sticky-column {
        background-color: #fff; /* Match header color */
    }

    .status-approved { color: #16A34A; font-weight: 500; }
    .status-pending { color: #EA580C; font-weight: 500; }
    .status-rejected { color: #DC2626; font-weight: 500; }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Invoice Request</h1>
        </div>
        <div class="col-lg-6 col-12 d-flex justify-content-end align-items-center">
            <div class="header-action-icons">
                <div id="search-box-wrapper" class="collapsed">
                    <i class="fa-solid fa-magnifying-glass search-icon-inside"></i>
                    <input type="text" id="tableSearch" class="search-input" placeholder="Search invoices...">
                </div>
                <i class="fa-solid fa-magnifying-glass" id="searchTrigger"></i>
                <i class="fa-solid fa-filter"></i>
            </div>
        </div>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>
        <div class="col-12 d-flex justify-content-end mb-3">
            <a href="{{ route('add_invoice_request') }}" style="text-decoration: none;">
                <button class="red-action-btn-lg add-new-payment-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.50726 10.5634H4.85938V9.0141H9.50726V4.36621H11.0566V9.0141H15.7044V10.5634H11.0566V15.2113H9.50726V10.5634Z"
                            fill="white" />
                    </svg>
                    Invoice
                </button>
            </a>
        </div>


        <div class="table-responsive">
            <table class="table custom-table-locked" id="invoiceTable" style="min-width: 1000px;">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Address <i class="fa-solid fa-chevron-down ms-1" style="font-size: 12px;"></i></th>
                        <th id="dateHeader">Date</th>
                        <th>Amount</th>
                        <th class="sticky-column">Approval Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $dummyData = [
                            ['no' => 'INV-001', 'name' => 'H.K Perera', 'mobile' => '075 2385859', 'address' => 'No.451, Colombo 01', 'date' => '2026-03-10', 'amount' => 'Rs.120,000.00', 'status' => 'Approved'],
                            ['no' => 'INV-001', 'name' => 'Pasan Randula', 'mobile' => '075 2385859', 'address' => 'No.451, Colombo 01', 'date' => '2026-03-05', 'amount' => 'Rs.120,000.00', 'status' => 'Pending'],
                            ['no' => 'INV-001', 'name' => 'H.K Perera', 'mobile' => '075 2385859', 'address' => 'No.451, Colombo 01', 'date' => '2026-03-12', 'amount' => 'Rs.120,000.00', 'status' => 'Approved'],
                            ['no' => 'INV-001', 'name' => 'Pasan Randula', 'mobile' => '075 2385859', 'address' => 'No.451, Colombo 01', 'date' => '2026-03-08', 'amount' => 'Rs.120,000.00', 'status' => 'Rejected'],
                            ['no' => 'INV-001', 'name' => 'H.K Perera', 'mobile' => '075 2385859', 'address' => 'No.451, Colombo 01', 'date' => '2026-03-15', 'amount' => 'Rs.120,000.00', 'status' => 'Approved'],
                            ['no' => 'INV-001', 'name' => 'Pasan Randula', 'mobile' => '075 2385859', 'address' => 'No.451, Colombo 01', 'date' => '2026-03-01', 'amount' => 'Rs.20,000.00', 'status' => 'Approved'],
                        ];
                    @endphp

                    @foreach ($dummyData as $data)
                        <tr>
                            <td>{{ $data['no'] }}</td>
                            <td>{{ $data['name'] }}</td>
                            <td>{{ $data['mobile'] }}</td>
                            <td>{{ $data['address'] }}</td>
                            <td>{{ $data['date'] }}</td>
                            <td>{{ $data['amount'] }}</td>
                            <td class="sticky-column">
                                @php
                                    $statusClass = 'status-' . strtolower($data['status']);
                                @endphp
                                <span class="{{ $statusClass }}">{{ $data['status'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#" style="color: #888;">Prev</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    <li class="page-item"><a class="page-link" href="#">10</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
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
            const rows = document.querySelectorAll('#invoiceTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    });
</script>

@include('layouts.footer2')
