@include('finance::layouts.header')

<style>
    .form-check-input {
        height: 20px;
        width: 20px;
        border-color: #D2D5DA;
        margin-right: 15px;
    }

    .form-check-input:focus {
        border-color: #dc3545 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 2.1px #dc354533 !important;
    }

    .form-check-input:checked {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .form-check-label {
        font-family: "Inter", sans-serif;
        font-size: 20px;
        font-weight: 400;
    }
</style>

<div class="main-wrapper">
    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Direct Payments</h1>
        </div>

    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-md"></div>
        <div class="row g-5">
            <div class="col-md-6">
                <div>
                    <p class="card-section-title">Customer Name</p>

                    <div class="mb-4 w-100">
                        <select id="customerSelect" class="form-control select2" multiple>
                            @foreach ($customers as $cust)
                            <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <div class="card customer-payment-card">

                            <div class="card-body d-flex flex-column gap-4">
                                <h5 class="card-title">Selected Customers Details</h5>
                                <div id="customerDetailsList"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div>
                    <p class="card-section-title">Search Invoice</p>

                    <div class="d-flex search-div align-items-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16.893 16.92L19.973 20M19 11.5C19 13.4891 18.2098 15.3968 16.8033 16.8033C15.3968 18.2098 13.4891 19 11.5 19C9.51088 19 7.60322 18.2098 6.1967 16.8033C4.79018 15.3968 4 13.4891 4 11.5C4 9.51088 4.79018 7.60322 6.1967 6.1967C7.60322 4.79018 9.51088 4 11.5 4C13.4891 4 15.3968 4.79018 16.8033 6.1967C18.2098 7.60322 19 9.51088 19 11.5Z"
                                stroke="#AAB6C1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="w-100 mx-3">
                            <input type="text" id="search-input" onkeyup="filterCheckboxes()"
                                class="search-invoices-input" placeholder="Select Invoice">
                        </span>

                    </div>


                    <div class="mt-4">
                        <div class="card customer-payment-card">

                            <div class="card-body d-flex flex-column invoices-card">
                                <h5 class="card-title ps-3 m-0">Choose Invoices</h5>

                                <table class="table">
                                    <thead>
                                        <tr class="searchable-table-header">

                                            <th scope="col column-title">Full Name</th>
                                            <th scope="col column-title">Invoice Number</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceList"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('footer-buttons')
<div class="action-button-lg-row ">
    <a href="{{ url('dashboard')}}">
        <button class="black-action-btn-lg mb-3 ">
            Cancel
        </button>
    </a>

    <a href="{{ url('invoices-inner') }}">
        <button class="red-action-btn-lg mb-3">
            Submit
        </button>
    </a>

</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#multi-select-dropdown').select2({
            placeholder: "Select Customer",
            allowClear: true
        });
    });
</script>

<!-- customer name select with SVG in placeholder -->
<script>
    $(document).ready(function() {
        const svgIcon = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg" class="me-2">
            <path
                d="M16.893 16.92L19.973 20M19 11.5C19 13.4891 18.2098 15.3968 16.8033 16.8033C15.3968 18.2098 13.4891 19 11.5 19C9.51088 19 7.60322 18.2098 6.1967 16.8033C4.79018 15.3968 4 13.4891 4 11.5C4 9.51088 4.79018 7.60322 6.1967 6.1967C7.60322 4.79018 9.51088 4 11.5 4C13.4891 4 15.3968 4.79018 16.8033 6.1967C18.2098 7.60322 19 9.51088 19 11.5Z"
                stroke="#AAB6C1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>`;

        $('#customerSelect').select2({
            placeholder: 'Select Customer', // placeholder text
            allowClear: true,
            width: '100%',
            escapeMarkup: function(m) {
                return m;
            }, // allow HTML
            templateSelection: function(data) {
                if (data.id === '') {
                    return svgIcon + 'Select Customer'; // placeholder with SVG
                }
                return data.text; // selected items
            },
            templateResult: function(data) {
                if (!data.id) return data.text; // placeholder
                return '<span class="custom-dropdown-item">' + data.text + '</span>'; // dropdown items
            }
        });
    });
</script>

<!-- dynamically load customer details -->
<script>
    $(document).ready(function() {

        let selectedCustomers = [];

        // When user selects a customer
        $('#customerSelect').on('select2:select', function(e) {
            let id = e.params.data.id;
            if (!selectedCustomers.includes(id)) {
                selectedCustomers.push(id);
                loadCustomerDetails(id);
                loadInvoices();
            }
        });

        // When user unselects a customer
        $('#customerSelect').on('select2:unselect', function(e) {
    let id = e.params.data.id;

    selectedCustomers = selectedCustomers.filter(x => x != id);

    $("#customer-card-" + id).remove();

    loadInvoices(); 
});

        // Load customer details on the left
        function loadCustomerDetails(id) {
            $.ajax({
                url: "{{ url('finance/collections/customer/details') }}/" + id,
                type: 'GET',
                success: function(data) {
                    if (data) {
                        let cardHtml = `
<div class="details-card mb-3" id="customer-card-${data.id}">
    <div class="card-content">
        <p><span class="bold-text-sm">Customer Name :</span>
            <span class="slip-detail-text-sm value" data-field="name">&nbsp;${data.name}</span></p>
        <p><span class="bold-text-sm">Mobile No. :</span>
            <span class="slip-detail-text-sm value" data-field="mobile">&nbsp;${data.mobile_number ?? 'N/A'}</span></p>
        <p><span class="bold-text-sm">Email :</span>
            <span class="slip-detail-text-sm value" data-field="email">&nbsp;${data.email ?? 'N/A'}</span></p>
        <p><span class="bold-text-sm">Address :</span>
            <span class="slip-detail-text-sm value" data-field="address">&nbsp;${data.address ?? 'N/A'}</span></p>
    </div>

<div class="d-block">
        <div class="d-flex gap-2 mb-3">
            <button class="red-edit-button-sm" onclick="toggleEdit(this)">
                <svg width="13" height="12" viewBox="0 0 13 12" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 9.5H3.7125L8.6 4.6125L7.8875 3.9L3 8.7875V9.5ZM2 10.5V8.375L8.6 1.7875C8.7 1.69583 8.8105 1.625 8.9315 1.575C9.0525 1.525 9.1795 1.5 9.3125 1.5C9.4455 1.5 9.57467 1.525 9.7 1.575C9.82534 1.625 9.93367 1.7 10.025 1.8L10.7125 2.5C10.8125 2.59167 10.8855 2.7 10.9315 2.825C10.9775 2.95 11.0003 3.075 11 3.2C11 3.33333 10.9772 3.4605 10.9315 3.5815C10.8858 3.7025 10.8128 3.81283 10.7125 3.9125L4.125 10.5H2ZM8.2375 4.2625L7.8875 3.9L8.6 4.6125L8.2375 4.2625Z" fill="white"/>
                </svg>
                Edit
            </button>
            <button class="grey-undo-button-sm" onclick="removeDynamicCard(${data.id})">
                <svg width="14" height="2" viewBox="0 0 14 2" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 2C0.71667 2 0.479337 1.904 0.288004 1.712C0.0966702 1.52 0.000670115 1.28267 3.44827e-06 1C-0.000663218 0.717333 0.0953369 0.48 0.288004 0.288C0.48067 0.0960001 0.718003 0 1 0H13C13.2833 0 13.521 0.0960001 13.713 0.288C13.905 0.48 14.0007 0.717333 14 1C13.9993 1.28267 13.9033 1.52033 13.712 1.713C13.5207 1.90567 13.2833 2.00133 13 2H1Z" fill="#8C8C8C"/>
                </svg>
            </button>
        </div>
    </div>
</div>`;
                        $("#customerDetailsList").append(cardHtml);
                    }
                },
                error: function(err) {
                    console.error("Error fetching customer:", err);
                }
            });
        }

        // Load invoices for selected customers
        function loadInvoices() {
            // Clear existing invoices
            $("#invoiceList").empty();

            if (selectedCustomers.length === 0) return;

            selectedCustomers.forEach(id => {
                $.ajax({
                    url: "{{ url('finance/collections/customer/invoices') }}/" + id,
                    type: 'GET',
                    success: function(invoices) {
                        let tbody = $(".invoices-card tbody");
                        invoices.forEach(inv => {
                            tbody.append(`
<tr class="checkbox-item" data-customer-id="${id}">
    <td>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="invoice-${inv.id}" name="invoice[]" value="${inv.id}">
            <label class="form-check-label ms-2" for="invoice-${inv.id}">
                ${inv.customer_name}
            </label>
        </div>
    </td>
    <td>${inv.invoice_or_cheque_no}</td>
</tr>
`);
                        });
                    },
                    error: function(err) {
                        console.error("Error fetching invoices:", err);
                    }
                });
            });
        }

    });

    function removeDynamicCard(id) {
        // Remove the customer card
        $("#customer-card-" + id).remove();

        // Deselect from Select2
        let val = $('#customerSelect').val().filter(v => v != id.toString());
        $('#customerSelect').val(val).trigger('change');

        // Remove invoices belonging to this customer
        $("#invoiceList tr[data-customer-id='" + id + "']").remove();
    }
</script>

<!-- details-card edit and remove functionality -->
<script>
    function toggleEdit(button) {
        const card = button.closest(".details-card");
        const values = card.querySelectorAll(".value");

        if (button.dataset.mode !== "edit") {
            // Switch to edit mode
            values.forEach(span => {
                const text = span.innerText.trim();
                span.innerHTML = `<input type="text" value="${text}" class="edit-input"/>`;
            });

            // Change button to "Save"
            button.innerHTML = `Save`;
            button.dataset.mode = "edit";
        } else {
            // Save mode
            values.forEach(span => {
                const input = span.querySelector("input");
                if (input) {
                    span.innerHTML = "&nbsp;" + input.value;
                }
            });

            // Restore original "Edit" button with SVG
            button.innerHTML = `
      <svg width="13" height="12" viewBox="0 0 13 12" fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path
              d="M3 9.5H3.7125L8.6 4.6125L7.8875 3.9L3 8.7875V9.5ZM2 10.5V8.375L8.6 1.7875C8.7 1.69583 8.8105 1.625 8.9315 1.575C9.0525 1.525 9.1795 1.5 9.3125 1.5C9.4455 1.5 9.57467 1.525 9.7 1.575C9.82534 1.625 9.93367 1.7 10.025 1.8L10.7125 2.5C10.8125 2.59167 10.8855 2.7 10.9315 2.825C10.9775 2.95 11.0003 3.075 11 3.2C11 3.33333 10.9772 3.4605 10.9315 3.5815C10.8858 3.7025 10.8128 3.81283 10.7125 3.9125L4.125 10.5H2ZM8.2375 4.2625L7.8875 3.9L8.6 4.6125L8.2375 4.2625Z"
              fill="white" />
      </svg>
      Edit
    `;
            button.dataset.mode = "view";
        }
    }

    function removeCard(button) {
        const card = button.closest(".details-card");
        card.remove(); // completely removes the card
    }
</script>

<script>
    function filterCheckboxes() {
        const searchInput = document.getElementById('search-input').value.toLowerCase();
        const checkboxItems = document.querySelectorAll('.checkbox-item');

        checkboxItems.forEach(item => {
            const fullName = item.querySelector('td:first-child label').textContent.toLowerCase();
            const invoiceNumber = item.querySelector('td:nth-child(2)').textContent.toLowerCase();

            // Show the item if either Full Name or Invoice Number matches
            if (fullName.includes(searchInput) || invoiceNumber.includes(searchInput)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }
</script>

@include('finance::layouts.footer2')