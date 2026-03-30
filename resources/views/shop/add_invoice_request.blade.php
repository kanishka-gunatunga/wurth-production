@include('layouts.dashboard-header')

<style>
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control {
        height: 45px;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding-left: 15px;
        color: #666;
    }

    .form-control::placeholder {
        color: #ccc;
    }

    .form-control:focus {
        border-color: #CC0000;
        box-shadow: 0 0 0 0.2rem rgba(204, 0, 0, 0.1);
    }

    .btn-cancel {
        background-color: #000;
        color: #fff;
        border: none;
        padding: 10px 40px;
        border-radius: 5px;
        font-weight: 600;
        margin-right: 15px;
    }

    .btn-submit {
        background-color: #EF4444;
        color: #fff;
        border: none;
        padding: 10px 40px;
        border-radius: 5px;
        font-weight: 600;
    }

    .error-text {
        color: #DC2626;
        font-size: 12px;
        margin-top: 4px;
        display: none;
    }

    .is-invalid {
        border-color: #DC2626 !important;
    }
</style>

<div class="main-wrapper">
    <div class="row d-flex justify-content-between align-items-center mb-4">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Add a Invoice Request</h1>
        </div>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>

        <form id="invoiceRequestForm" action="{{ route('store_invoice_request') }}" method="POST">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                    <div id="nameError" class="error-text">Please enter a valid name.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mobile_number" class="form-label">Mobile Number</label>
                    <input type="text" id="mobile_number" name="mobile_number" class="form-control" placeholder="Mobile Number" value="{{ old('mobile_number') }}">
                    <div id="mobileError" class="error-text">Please enter a valid mobile number.</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="Address" value="{{ old('address') }}">
                    <div id="addressError" class="error-text">Please enter a valid address.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="invoice_no" class="form-label">Invoice No</label>
                    <input type="text" id="invoice_no" name="invoice_no" class="form-control" placeholder="Invoice No" value="{{ old('invoice_no') }}">
                    <div id="invoiceNoError" class="error-text">Please enter a valid invoice number.</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="invoice_date" class="form-label">Invoice Date</label>
                    <div class="position-relative">
                        <input type="date" id="invoice_date" name="invoice_date" class="form-control" placeholder="Invoice Date" value="{{ old('invoice_date') }}">
                    </div>
                    <div id="dateError" class="error-text">Please select a valid date.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="total_amount" class="form-label">Total Invoice Amount</label>
                    <input type="text" id="total_amount" name="total_amount" class="form-control" placeholder="Total Invoice Amount" value="{{ old('total_amount') }}">
                    <div id="amountError" class="error-text">Please enter a valid amount.</div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-5">
                <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('invoice_request') }}'">Cancel</button>
                <button type="submit" class="btn-submit">Submit</button>
            </div>
        </form>
    </div>
</div>


<script>
    document.getElementById('invoiceRequestForm').addEventListener('submit', function(e) {
        let isValid = true;

        const name = document.getElementById('name');
        const mobile = document.getElementById('mobile_number');
        const address = document.getElementById('address');
        const invoiceNo = document.getElementById('invoice_no');
        const date = document.getElementById('invoice_date');
        const amount = document.getElementById('total_amount');

        // Reset errors
        document.querySelectorAll('.error-text').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));

        if (!name.value.trim()) {
            document.getElementById('nameError').style.display = 'block';
            name.classList.add('is-invalid');
            isValid = false;
        }

        if (!mobile.value.trim() || !/^\d+$/.test(mobile.value.trim().replace(/\s/g, ''))) {
            document.getElementById('mobileError').style.display = 'block';
            mobile.classList.add('is-invalid');
            isValid = false;
        }

        if (!address.value.trim()) {
            document.getElementById('addressError').style.display = 'block';
            address.classList.add('is-invalid');
            isValid = false;
        }

        if (!invoiceNo.value.trim()) {
            document.getElementById('invoiceNoError').style.display = 'block';
            invoiceNo.classList.add('is-invalid');
            isValid = false;
        }

        if (!date.value) {
            document.getElementById('dateError').style.display = 'block';
            date.classList.add('is-invalid');
            isValid = false;
        }

        if (!amount.value.trim() || isNaN(amount.value.trim().replace(/[Rs.,]/g, ''))) {
            document.getElementById('amountError').style.display = 'block';
            amount.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    
</script>

@include('layouts.footer2')
   <script>
    $(document).ready(function() {
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('fail'))
        toastr.error("{{ Session::get('fail') }}");
        @endif
    });
</script>