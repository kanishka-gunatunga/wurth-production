@include('finance::layouts.header')
<div class="main-wrapper">

    <div class="d-flex justify-content-between align-items-center header-with-button">
        <h1 class="header-title">Write-off ID - WO{{ $writeOff->id }}</h1>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="slip-details">
            <p>
                <span class="bold-text">Date :</span><span class="slip-detail-text">&nbsp;{{ $writeOff->created_at->format('Y-m-d') }}</span>
            </p>
            <p>
                <span class="bold-text">Write-off reason :</span><span class="slip-detail-text">&nbsp;{{ $writeOff->reason ?? '-' }}</span>
            </p>
            <p>
                <span class="bold-text">Final Write-off Amount :</span><span class="slip-detail-text">&nbsp;Rs. {{ number_format($writeOff->final_amount, 2) }}</span>
            </p>
        </div>

        <!-- Invoices/Return Cheque Table -->
        <div class="header-and-content-gap-lg"></div>
        <p class="bold-text">Invoices/Return Cheque</p>
        <div class="table-responsive">
            <table class="table unlock-column-table">
                <thead>
                    <tr>
                        <th>Invoice/Return cheque no.</th>
                        <th>Customer Name</th>
                        <th>Customer ID</th>
                        <th>ADM no.</th>
                        <th>Write-off Amount</th>
                    </tr>
                </thead>
                <tbody id="paymentSlipsInvoices">
                    @foreach ($invoicesData as $invoice)
                    <tr>
                        <td>{{ $invoice['invoiceNo'] }}</td>
                        <td>{{ $invoice['customerName'] }}</td>
                        <td>{{ $invoice['customerId'] }}</td>
                        <td>{{ $invoice['admNo'] }}</td>
                        <td>{{ number_format($invoice['writeOffAmount'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav class="d-flex justify-content-center mt-5">
            <ul id="paymentSlipsInvoicesPagination" class="pagination"></ul>
        </nav>

        <!-- Extra Payment/Credit Note Table -->
        <div class="header-and-content-gap-lg"></div>
        <p class="bold-text">Extra Payment/Credit Note</p>
        <div class="table-responsive">
            <table class="table unlock-column-table">
                <thead>
                    <tr>
                        <th>Extra Payment/Credit Note no.</th>
                        <th>Customer Name</th>
                        <th>Customer ID</th>
                        <th>ADM no.</th>
                        <th>Write-off Amount</th>
                    </tr>
                </thead>
                <tbody id="paymentSlipsExtra">
                    @foreach ($creditNotesData as $credit)
                    <tr>
                        <td>{{ $credit['extraPaymentNo'] }}</td>
                        <td>{{ $credit['customerName'] }}</td>
                        <td>{{ $credit['customerId'] }}</td>
                        <td>{{ $credit['admNo'] }}</td>
                        <td>{{ number_format($credit['writeOffAmount'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav class="d-flex justify-content-center mt-5">
            <ul id="paymentSlipsExtraPagination" class="pagination"></ul>
        </nav>

    </div>
</div>

@section('footer-buttons')
<div class="d-flex justify-content-end mt-4 gap-3">
    <a href="{{ route('write_off.main') }}" class="grey-action-btn-lg" style="text-decoration: none;">Back</a>
</div>
@endsection



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

@include('finance::layouts.footer2')