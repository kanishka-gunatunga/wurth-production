@include('layouts.dashboard-header')

<div class="container-fluid">
    <div class="main-wrapper">
        <div class="p-4 pt-0">
            <div class="col-lg-6 col-12">
                <h1 class="header-title">Return Cheque</h1>
            </div>

            <hr class="red-line">

            <form action="{{ url('create-return-cheque') }}" method="POST">
                @csrf

                <div class="row d-flex justify-content-between">

                    <!-- ✅ Select Customer ID -->
                    <div class="mb-4 col-12 col-lg-6">
                        <label for="customer_id" class="form-label custom-input-label">Select Customer ID</label>
                        <select name="customer_id" id="customer_id" class="form-select custom-input">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->customer_id }}">{{ $customer->customer_id }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ✅ Return Cheque Number -->
                    <div class="mb-4 col-12 col-lg-6">
                        <label for="cheque_number" class="form-label custom-input-label">Return Cheque Number</label>
                        <input type="text" name="cheque_number" id="cheque_number"
                            class="form-control custom-input" placeholder="Enter Cheque Number"
                            value="{{ old('cheque_number') }}">
                        @error('cheque_number')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ✅ Cheque Amount -->
                    <div class="mb-4 col-12 col-lg-6">
                        <label for="cheque_amount" class="form-label custom-input-label">Cheque Amount</label>
                        <input type="text" name="cheque_amount" id="cheque_amount"
                            class="form-control custom-input" placeholder="Enter Cheque Amount"
                            value="{{ old('cheque_amount') }}">
                        @error('cheque_amount')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ✅ Returned Date -->
                    <div class="mb-4 col-12 col-lg-6 position-relative">
                        <label for="returned_date" class="form-label custom-input-label">Returned Date</label>
                        <input type="date" name="returned_date" id="returned_date"
                            class="form-control custom-input" value="{{ old('returned_date') }}">
                        @error('returned_date')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ✅ Bank Name -->
                    <div class="mb-4 col-12 col-lg-6">
                        <label for="bank_id" class="form-label custom-input-label">Bank Name</label>
                        <select name="bank_id" id="bank_id" class="form-select custom-input">
                            <option value="">Select Bank</option>
                            @foreach($banks as $bank)
                            <option value="{{ $bank }}">{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_id')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ✅ Branch -->
                    <div class="mb-4 col-12 col-lg-6">
                        <label for="branch_id" class="form-label custom-input-label">Branch</label>
                        <select name="branch_id" id="branch_id" class="form-select custom-input">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch }}">{{ $branch }}</option>
                            @endforeach
                        </select>
                        @error('branch_id')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ✅ Return Type -->
                    <div class="mb-4 col-12 col-lg-6">
                        <label for="return_type" class="form-label custom-input-label">Select Return Type</label>
                        <select name="return_type" id="return_type" class="form-select custom-input">
                            <option value="">Select Return Type</option>
                            <option value="Type 1">Type 1</option>
                            <option value="Type 2">Type 2</option>
                            <option value="Type 3">Type 3</option>
                        </select>
                        @error('return_type')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ✅ Reason -->
                    <div class="mb-4 col-12 col-lg-6 position-relative">
                        <label for="reason" class="form-label custom-input-label">Reason</label>
                        <input type="text" name="reason" id="reason"
                            class="form-control custom-input" placeholder="Enter Reason"
                            value="{{ old('reason') }}">
                        @error('reason')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="action-button-lg-row">
                    <a href="{{ url('return-cheques') }}" style="text-decoration: none;">
                        <button type="button" class="black-action-btn-lg mb-3 cancel">Cancel</button>
                    </a>
                    <button type="submit" class="red-action-btn-lg mb-3 submit">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Toast -->
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
            Submitted successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>

<script>
    // Cancel button redirect
    document.querySelector('.cancel').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '{{ url("return-cheques") }}';
    });

    // Show toast on submit (optional visual)
    document.querySelector('.submit').addEventListener('click', function() {
        const toast = document.getElementById('user-toast');
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    });
</script>