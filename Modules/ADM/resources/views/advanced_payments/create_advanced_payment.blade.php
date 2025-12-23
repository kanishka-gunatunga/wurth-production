@include('adm::layouts.header')
<link rel="stylesheet" href="{{ asset('adm_assets/css/signature_pad.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="d-flex flex-row px-3 mt-4 justify-content-between align-items-center w-100 text-start pt-2 mb-0">
    <h3 class="page-title">Advanced Payment</h3>
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
                    <label for="adm_number">Date</label>
                    <input type="date" class="form-control" placeholder="" name="date" id="date"
                        required="">
                    @if ($errors->has('date'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('date') }}</div>
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
                    <label for="customer">Customer ID, Customer Name or Add New Customer Name</label>

                    <select class="form-control select2-customer" name="customer" id="customer">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->customer_id }}">
                                {{ $customer->customer_id }} - {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('customer'))
                        <div class="alert alert-danger mt-2">{{ $errors->first('customer') }}</div>
                    @endif
                </div>

                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="name">Mobile Number</label>
                    <input type="text" class="form-control" name="mobile_no" required="">

                </div>
                <div class="input-group-profile d-flex flex-column mb-3">
                    <label for="name">Payment Amount</label>
                    <input type="number" class="form-control" name="payment_amount" required="">

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
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                result="hardAlpha" />
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
                                class="form-control position-absolute attachment-input" name="attachment" multiple
                                style="opacity: 0;" />

                            <ul id="file-preview" class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class=" customer-e-signature">
                    <div class="wrapper signature_pad d-flex flex-column">
                        <h3 class="mb-0">Customer's E-signature</h3>
                        <p>Sign to confirm payment.</p>
                        <canvas id="signature-pad-customer" width="300" height="250"></canvas>
                        <input type="hidden" name="customer_signature" id="customer_signature_input">
                        <div class="d-flex flex-row my-2">
                            <div class="clear-btn styled-button-red me-2">
                                <button id="clear-customer" type="button"><span> Clear </span></button>
                            </div>
                            <!-- <div class="save-btn styled-button-red">
                                    <button  id="save-customer"><span> Save </span></button>
                                </div> -->
                        </div>
                    </div>

                </div>
                <div class="d-flex w-100 justify-content-center align-items-center pt-3">
                    <button class="styled-button-normal w-100 px-5"
                        style="width: 100% !important; font-size: 14px !important; font-weight: 600; height: 40px !important; min-height: 40px !important"
                        type="submit">Submit Payment</button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('adm::layouts.footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js"
    integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        $('#customer').select2({
            placeholder: 'Type Customer ID | Name or Select Existing',
            tags: true,
            width: '100%',
            allowClear: true,
            createTag: function(params) {

                let term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });

    });
</script>

<script>
    function initializeSignaturePad(canvasId, hiddenInputId) {
        var canvas = document.getElementById(canvasId);
        var hiddenInput = document.getElementById(hiddenInputId);

        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(250,250,250)'
        });

        signaturePad.onEnd = function() {
            if (!signaturePad.isEmpty()) {
                var dataURL = signaturePad.toDataURL();
                hiddenInput.value = dataURL;
            }
        };

        return signaturePad;
    }


    var customerSignaturePad = initializeSignaturePad('signature-pad-customer', 'customer_signature_input');


    document.getElementById("clear-customer").addEventListener('click', function() {
        customerSignaturePad.clear();
        document.getElementById('customer_signature_input').value = '';
    });
</script>

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

        $('#customer').on('change', function() {
            let customerId = $(this).val();

            // Ignore temp customers (typed ones)
            if (!customerId || customerId.includes('|')) {
                return;
            }

            $.ajax({
                url: "{{ url('adm/get-customer-details') }}/" + customerId,
                type: "GET",
                success: function(data) {
                    if (data) {
                        $('input[name="mobile_no"]').val(data.mobile_number);
                    }
                }
            });
        });

    });
</script>

<script>
    document.getElementById('attachment').addEventListener('change', function() {
        const preview = document.getElementById('file-preview');
        preview.innerHTML = '';

        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        let validFiles = true;

        Array.from(this.files).forEach(file => {
            if (file.size > maxSize) {
                toastr.error(`"${file.name}" exceeds the 5MB limit`);
                validFiles = false;
            }
        });

        if (!validFiles) {
            this.value = ''; // Reset file input
            return;
        }

        Array.from(this.files).forEach(file => {
            const li = document.createElement('li');
            li.classList.add('d-flex', 'align-items-center', 'mb-1');
            li.innerHTML = `<strong>Selected file:</strong> ${file.name}`;
            preview.appendChild(li);
        });
    });
</script>