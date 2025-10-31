@include('adm::layouts.header')
<?php
use App\Models\Customers;
?>
<div class="content px-0">
            <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
                <h3 class="page-title">Collections</h3>
                <a href="{{url('adm/bulk-payment')}}" class="my-3 small-button">
                    Bulk Payment
                </a>
            </div>
            <!-- <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" style="width: 50vw;" id="pills-payment-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-payment" type="button" role="tab"
                            aria-controls="pills-payment" aria-selected="true">Single Payment</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" style="width: 50vw;" id="pills-system-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-system" type="button" role="tab" aria-controls="pills-system"
                            aria-selected="false">Bulk Payment</button>
                    </li>
            </ul> -->
            <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">

        <!-- Summary Section -->
        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3" style="border: solid 1px #9D9D9D;">
            <div class="col-6 d-flex flex-column py-2 text-center">
                <p class="gray-small-title mb-1">Total Invoices</p>
                <p class="black-large-text mb-1">{{ number_format($all_invoices->count()) }}</p>
            </div>
            <div class="col-6 d-flex flex-column py-2 text-center" style="border-left: solid 1px #9D9D9D;">
                <p class="gray-small-title mb-1">Total Outstanding Amount</p>
                @php
                    $outstanding_amount = $all_invoices->sum(fn($invoice) => $invoice->amount - $invoice->paid_amount);
                @endphp
                <p class="black-large-text mb-1">Rs. {{ number_format($outstanding_amount) }}</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="d-flex flex-column">
            <div class="mb-3">
                <div class="container">
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Search here...">
                        </div>
                        <div class="search-dropdown" id="searchDropdown"></div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="table dashboard-table">
                    <thead>
                        <tr>
                            <th scope="col">In.No / Che.No</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Invoice Date</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-data">
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ url('adm/view-invoice/' . $invoice->id) }}">
                                        {{ $invoice->invoice_or_cheque_no }}
                                    </a>
                                </td>
                                <td>{{ $invoice->customer->name ?? '-' }}</td>
                                <td>{{ $invoice->invoice_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="col-12 d-flex justify-content-center laravel-pagination">
                {{ $invoices->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
</div>

           
        </div>
        @include('adm::layouts.footer')
<script>
    $(document).ready(function() {
        $('#searchInput').on('input', function() {
            let query = $(this).val();

            $.ajax({
                url: "{{ url('adm/search-invoices') }}", 
                method: "GET",
                data: { query: query },
                success: function(response) {
                    $('#invoice-data').html(response);
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        });

    });
</script>
<script>
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function () {
                const icon = this.querySelector('.collapse-icon');
                icon.classList.toggle('bi-chevron-down');
                icon.classList.toggle('bi-chevron-up');
            });
        });
    </script>

    <script>
        const customSelect = document.getElementById('customSelect');
        const customDropdown = document.getElementById('customDropdown');
        const searchInput = document.getElementById('searchInput2');
        const dropdownItems = document.getElementById('dropdownItems');
        const chevron = customSelect.querySelector('.chevron');
        const dropdownOverlay = document.getElementById('dropdownOverlay');
        const customSelectWrapper = document.getElementById('customSelectWrapper');

        customSelect.addEventListener('click', () => {
            customDropdown.classList.toggle('show');
            customSelectWrapper.classList.toggle('open');
            dropdownOverlay.style.display = customDropdown.classList.contains('show') ? 'block' : 'none';
        });

        document.addEventListener('click', (event) => {
            if (!customSelect.contains(event.target) && !customDropdown.contains(event.target)) {
                customDropdown.classList.remove('show');
                customSelectWrapper.classList.remove('open');
                dropdownOverlay.style.display = 'none';
            }
        });

        searchInput.addEventListener('input', () => {

            const filter = searchInput.value.toLowerCase();
            const rows = dropdownItems.querySelectorAll('tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
<script>
    const selectedTagsContainer = document.getElementById('selectedTagsContainer');
    const selectedCustomers = new Set();

    function sendSelectedCustomers() {
        // console.log(selectedCustomers);
        // fetch('/update-selected-customers', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     },
        //     body: JSON.stringify({ selected_customers: Array.from(selectedCustomers) })
        // })
        // .then(response => response.json())
        // .then(data => {
        //     console.log('Updated selected customers:', data);
        // })
        // .catch(error => console.error('Error updating selected customers:', error));
        
        $.ajax({
            url: "{{ url('adm/search-bulk-payment') }}", 
            method: "GET",
            data: { selected_customers: Array.from(selectedCustomers) },
            success: function(response) {
                $('#bulk-invoices-data').html(response);
            },
            error: function(xhr) {
                console.error('An error occurred:', xhr.responseText);
            }
        });
    }

    dropdownItems.addEventListener('change', function (event) {
        if (event.target.classList.contains('customer-checkbox')) {
            const checkbox = event.target;
            const customerId = checkbox.dataset.id;
            const customerName = checkbox.dataset.name;

            if (checkbox.checked) {
                if (!selectedCustomers.has(customerId)) {
                    selectedCustomers.add(customerId);

                    const tag = document.createElement('div');
                    tag.className = 'alert alert-danger alert-dismissible fade show rounded-pill py-2 pill-tag-styles';
                    tag.id = 'tag-' + customerId;
                    tag.role = 'alert';
                    tag.innerHTML = `
                        ${customerName}
                        <button type="button" class="btn-close p-2" data-id="${customerId}" aria-label="Close"></button>
                    `;
                    selectedTagsContainer.appendChild(tag);
                }
            } else {
                selectedCustomers.delete(customerId);
                const tag = document.getElementById('tag-' + customerId);
                if (tag) tag.remove();
            }

            sendSelectedCustomers();
        }
    });

    selectedTagsContainer.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-close')) {
            const customerId = event.target.dataset.id;

            selectedCustomers.delete(customerId);
            const tag = document.getElementById('tag-' + customerId);
            if (tag) tag.remove();

            const checkbox = dropdownItems.querySelector(`input[data-id="${customerId}"]`);
            if (checkbox) checkbox.checked = false;

            sendSelectedCustomers();
        }
    });
</script>