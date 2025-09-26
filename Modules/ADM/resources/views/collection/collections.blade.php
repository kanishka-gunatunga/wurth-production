@include('adm::layouts.header')
<?php
use App\Models\Customers;
?>
<div class="content px-0">
            <!-- <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
                <h3 class="page-title">Collections</h3>
                <a href="" class="my-3 small-button">
                    Bulk Payment
                </a>
            </div> -->
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
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
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
                        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3"
                            style="border: solid 1px #9D9D9D;">
                            <div class="col-6 d-flex flex-column py-2 text-center">
                                <p class="gray-small-title mb-1">Total Invoices</p>
                                <p class="black-large-text mb-1">{{number_format($all_invoices->count())}}</p>
                            </div>
                            <div class="col-6 d-flex flex-column py-2 text-center" style="border-left: solid 1px #9D9D9D;">
                                <p class="gray-small-title mb-1">Total Outstanding Amount</p>
                                <?php
                                $outstanding_amount = 0;
                                foreach($all_invoices as $all_invoice){
                                    $amount_diff = $all_invoice->amount-$all_invoice->paid_amount;
                                    $outstanding_amount = $outstanding_amount+ $amount_diff;
                                } ?>
                                <p class="black-large-text mb-1">Rs. {{number_format($outstanding_amount)}}</p>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="mb-3 ">
                                <div class="container">
                                    <div class="search-container">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="bi bi-search"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" id="searchInput"
                                                placeholder="Search here...">
                                        </div>
                                        <div class="search-dropdown" id="searchDropdown">
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <?php
                                    foreach($invoices as $invoice){
                                    
                                    ?>
                                        <tr>
                                            <td>
                                            <a href="{{url('adm/view-invoice/'.$invoice->id.'')}}">
                                                {{$invoice->invoice_or_cheque_no}}
                                            </a>
                                            </th>
                                            <td>{{Customers::where('customer_id', $invoice->customer_id)->value('name')}}</th>
                                            <td>{{$invoice->invoice_date}}</th>
                                        
                                        </tr>
                                    <?php } ?>  
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 d-flex justify-content-center laravel-pagination">
                                {{ $invoices->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                </div>

                <div class="tab-pane fade" id="pills-system" role="tabpanel" aria-labelledby="pills-system-tab">
                    <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
                        <h3 class="page-title">Bulk Payment</h3>
                    </div>

                    <!-- dropdown section -->
                    <div class="container px-3">
                        <div class="dropdown-overlay" id="dropdownOverlay"></div>
                        <div class="custom-select-wrapper" id="customSelectWrapper">
                            <div class="custom-select" id="customSelect" style="padding: 5px 10px !important;">
                                <div class="d-flex flex-row align-items-center w-100">
                                    <i class="bi bi-search me-2"></i>
                                    <input type="text" id="searchInput2" class="form-control mb-0 p-0 dropdown-search"
                                        placeholder="Search Customers">
                                </div>
                                <i class="bi bi-chevron-down chevron"></i>
                            </div>
                            <div class="custom-dropdown" id="customDropdown">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Customer ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dropdownItems">
                                        <?php foreach($customers as $customer){ ?>
                                        <tr class="custom-dropdown-item">
                                            <td>
                                                <input class="form-check-input form-check-input-table ms-0 customer-checkbox"
                                                    type="checkbox"
                                                    data-id="{{$customer->customer_id}}"
                                                    data-name="{{$customer->name}}"
                                                >
                                            </td>
                                            <td style="line-height: 25px;">{{$customer->name}}</td>
                                            <td style="line-height: 25px;">{{$customer->customer_id}}</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- tags -->
                    <div class="d-flex selected-items px-3 py-3 d-flex flex-wrap gap-2" id="selectedTagsContainer"></div>

                    <form  action="{{url('adm/bulk-payment')}}" method="get"  enctype="multipart/form-data">
                  
                    <div class="d-flex w-100 flex-column px-3 mb-0 mt-3">
                        <div class="card-view px-0 pt-0 pb-0">
                            <div class="d-flex flex-row justify-content-between align-items-center px-3 mb-0">
                                <h4 class="black-title mb-0">Select Invoices</h4>
                                <button type="submit" class="my-3 small-button">
                                    Done
                                </button>
                            </div>
                            <div class="scrollable-section-deposit" style="height: calc(100vh - 410px)">
                                <div class="d-flex flex-column">
                                    <table class="table dashboard-table">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">Full Name</th>
                                                <th scope="col">Invoice Number</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bulk-invoices-data">
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
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