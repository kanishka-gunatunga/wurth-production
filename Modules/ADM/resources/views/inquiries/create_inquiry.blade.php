@include('adm::layouts.header')

<div class="d-flex flex-row px-3 mt-4 justify-content-between align-items-center w-100 text-start pt-2 mb-0">
    <h3 class="page-title">Submit a Inquiry</h3>
</div>
<!-- body content -->
<form id="profileForm" class="content  needs-validation p-2" novalidate action="" method="post"
    enctype="multipart/form-data">
    @csrf
    <!-- row 2 -->
    <div class=" scrollable-section">
        <div class="d-flex flex-column justify-content-center align-items-center p-2">
            <div class="mb-3 w-100 ">
                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="reminder_date">Inquiry Type</label>
                    <select class="select2-with-search" name="inquiry_type">
                        <option></option>
                        <option value="None">None</option>
                        <option value="Cash Deposit">Cash Deposit</option>
                        <option value="Cheque Deposit">Cheque Deposit</option>
                    </select>
                    @if ($errors->has('inquiry_type'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('inquiry_type') }}</div>
                    @endif
                </div>


                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="adm_number">ADM Number</label>
                    <input type="number" class="form-control" value="{{ $user->userDetails->adm_number }}" readonly
                        name="adm_number" id="adm_number" required="">

                </div>

                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="name">ADM Name</label>
                    <input type="text" class="form-control" value="{{ $user->userDetails->name }}" name="name"
                        required="" readonly>

                </div>

                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="reminder_date">Select Customer Name</label>
                    <select class="select2-no-search" name="customer">
                        <option></option>
                        <?php foreach($customers as $customer){ ?>
                        <option value="{{ $customer->customer_id }}">{{ $customer->name }}</option>
                        <?php  } ?>
                    </select>
                    @if ($errors->has('customer'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('customer') }}</div>
                    @endif
                </div>
                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="reminder_date">Select Invoice Number</label>
                    <select class="select2-with-search" name="invoice">
                        <option></option>
                    </select>
                    @if ($errors->has('invoice'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('invoice') }}</div>
                    @endif
                </div>



                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="name">Reason</label>
                    <textarea type="text" style="border-radius: 8px !important;" class="form-control" rows="6"
                        placeholder="Enter the reason" name="reason" required=""></textarea>
                    @if ($errors->has('reason'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('reason') }}</div>
                    @endif
                </div>

                <div class="input-group-collection-inner d-flex flex-column mb-3">
                    <label for="attachment" class="mb-1">Attachment</label>
                    <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                        style="border-radius: 8px;">
                        <div
                            class="col-12 d-flex flex-column pt-4 pb-3 text-center position-relative justify-content-center align-items-center">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="68" height="68"
                                    viewBox="0 0 68 68" fill="none">
                                    <circle cx="34" cy="34" r="34" fill="#CC0000" fill-opacity="0.1" />
                                    <circle cx="34.3621" cy="34.0002" r="22.4255" fill="#CC0000"
                                        fill-opacity="0.13" />
                                    <g filter="url(#filter0_d_2457_12091)">
                                        <circle cx="34.3617" cy="33.9999" r="17.3617" fill="white" />
                                    </g>
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M25.5365 25.8978H24.2344V24.5957H25.5365V25.8978ZM30.745 25.8978H29.4429V24.5957H30.745V25.8978ZM35.9535 25.8978H34.6514V24.5957H35.9535V25.8978ZM28.1408 28.5021H42.4642V33.7106H41.162V29.8042H29.4429V41.5234H33.3493V42.8255H28.1408V28.5021ZM25.5365 31.1063H24.2344V29.8042H25.5365V31.1063ZM33.5394 33.9007C33.6215 33.8186 33.7241 33.76 33.8365 33.7309C33.9489 33.7019 34.0671 33.7034 34.1787 33.7353L43.2936 36.3396C43.4228 36.3766 43.5373 36.4526 43.6216 36.5572C43.7058 36.6618 43.7556 36.79 43.7642 36.924C43.7728 37.0581 43.7396 37.1915 43.6694 37.306C43.5991 37.4205 43.4951 37.5105 43.3717 37.5636L39.0539 39.4152L37.2036 43.7331C37.1505 43.8565 37.0605 43.9604 36.946 44.0307C36.8315 44.101 36.6981 44.1341 36.564 44.1255C36.43 44.117 36.3018 44.0672 36.1972 43.9829C36.0925 43.8986 36.0165 43.7841 35.9796 43.6549L33.3753 34.54C33.3434 34.4287 33.3418 34.3109 33.3707 34.1987C33.3995 34.0866 33.4578 33.9829 33.5394 33.9007ZM34.9483 35.3109L36.727 41.5377L37.9588 38.6639C38.0247 38.5099 38.1473 38.3873 38.3013 38.3214L41.1751 37.0896L34.9483 35.3109ZM25.5365 36.3148H24.2344V35.0127H25.5365V36.3148Z"
                                        fill="#CC0000" />
                                    <defs>
                                        <filter id="filter0_d_2457_12091" x="16.2766" y="15.9148" width="36.1704"
                                            height="36.1704" filterUnits="userSpaceOnUse"
                                            color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                            <feOffset />
                                            <feGaussianBlur stdDeviation="0.361702" />
                                            <feComposite in2="hardAlpha" operator="out" />
                                            <feColorMatrix type="matrix"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                result="effect1_dropShadow_2457_12091" />
                                            <feBlend mode="normal" in="SourceGraphic"
                                                in2="effect1_dropShadow_2457_12091" result="shape" />
                                        </filter>
                                    </defs>
                                </svg>
                                <label for="attachment">Select Attachment</label>
                                <p class="gray-tiny-title">Upload files from your library (max size of 5MB)</p>
                            </div>
                            <input type="file" id="attachment"
                                class="form-control position-absolute attachment-input" name="attachment"
                                accept=".pdf,image/*" style="opacity: 0;" />

                            @if ($errors->has('attachment'))
                                <div class="alert alert-danger mt-2">
                                    {{ $errors->first('attachment') }}
                                </div>
                            @endif


                            <ul id="file-preview" class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="d-flex w-100 justify-content-center align-items-center pt-3">
                    <button class="styled-button-normal w-100 px-5"
                        style="width: 100% !important; font-size: 14px !important; font-weight: 600; height: 40px !important; min-height: 40px !important"
                        type="submit">Submit Inquiry</button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('adm::layouts.footer')

<script>
    $(document).ready(function() {
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if (Session::has('fail'))
            toastr.error("{{ Session::get('fail') }}");
        @endif
    });
</script>

<script>
    $(document).ready(function() {
        $('select[name="customer"]').on('change', function() {
            var customerId = $(this).val();

            if (customerId) {
                $.ajax({
                    url: '{{ url('adm/get-customer-invoices') }}/' + customerId,
                    type: 'GET',
                    success: function(data) {
                        var invoiceSelect = $('select[name="invoice"]');
                        invoiceSelect.empty();
                        invoiceSelect.append('<option value="">Select Invoice</option>');

                        $.each(data, function(key, invoice) {
                            invoiceSelect.append('<option value="' + invoice
                                .invoice_or_cheque_no + '">' + invoice
                                .invoice_or_cheque_no + '</option>');
                        });
                    },
                    error: function() {
                        alert('Error retrieving invoices');
                    }
                });
            } else {
                $('select[name="invoice"]').empty().append('<option value="">Select Invoice</option>');
            }
        });
    });
</script>

{{-- Show uploaded file name --}}
<script>
    document.getElementById('attachment').addEventListener('change', function() {
        const preview = document.getElementById('file-preview');
        preview.innerHTML = '';

        if (this.files.length > 0) {
            const file = this.files[0];

            const li = document.createElement('li');
            li.style.listStyle = 'none';
            li.style.fontSize = '14px';
            li.innerHTML = `
            <strong>Selected file:</strong> ${file.name}
        `;

            preview.appendChild(li);
        }
    });
</script>
