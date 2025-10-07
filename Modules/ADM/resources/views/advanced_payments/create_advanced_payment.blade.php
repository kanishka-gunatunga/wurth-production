@include('adm::layouts.header')
<link rel="stylesheet" href="{{ asset('adm_assets/css/signature_pad.css') }}">
 <div class="d-flex flex-row px-3 mt-4 justify-content-between align-items-center w-100 text-start pt-2 mb-0">
            <h3 class="page-title">Create Advanced Payment</h3>
        </div>
        <!-- body content -->
        <form id="profileForm" class="content  needs-validation p-2" novalidate action="" method="post"  enctype="multipart/form-data">
        @csrf
            <!-- row 2 -->
            <div class=" scrollable-section">
                <div class="d-flex flex-column justify-content-center align-items-center p-2">
                    <div class="mb-3 w-100 ">
                       <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="adm_number">Date</label>
                            <input type="date" class="form-control" placeholder="" name="date" id="date" required="">
                             @if($errors->has("date")) <div class="alert alert-danger mt-2">{{ $errors->first('date') }}</div>@endif
                        </div>

                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="adm_number">ADM Number</label>
                            <input type="number" class="form-control" value="{{$user->userDetails->adm_number}}" readonly name="adm_number" id="adm_number" required="">
                          
                        </div>

                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="name">ADM Name</label>
                            <input type="text" class="form-control" value="{{$user->userDetails->name}}" name="name" required="" readonly>
                          
                        </div>

                        <div class="input-group-profile d-flex flex-column mb-3">
                        <label for="reminder_date">Select Customer Name</label>
                        <select class="select2-no-search" name="customer">
                        <option ></option>
                        <?php foreach($customers as $customer){ ?>
                        <option value="{{$customer->customer_id}}">{{$customer->name}}</option>
                         <?php  } ?>
                        </select>
                            @if($errors->has("customer")) <div class="alert alert-danger mt-2">{{ $errors->first('customer') }}</div>@endif
                        </div>
                         <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="name">Mobile Number</label>
                            <input type="text" class="form-control"  name="mobile_no" required="" >
                          
                        </div>
                         <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="name">Payment Amount</label>
                            <input type="number" class="form-control"  name="payment_amount" required="" >
                          
                        </div>   
                        

                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="name">Reason</label>
                            <textarea type="text" style="border-radius: 8px !important;" class="form-control" rows="6" placeholder="Enter the reason" name="reason" required=""></textarea>
                            @if($errors->has("reason")) <div class="alert alert-danger mt-2">{{ $errors->first('reason') }}</div>@endif
                        </div>

                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="attachment" class="mb-1">Attachment</label>
                                                        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                                                            style="border-radius: 8px;">
                                                            <div
                                                                class="col-12 d-flex flex-column pt-4 pb-3 text-center position-relative justify-content-center align-items-center">
                                                                <div
                                                                    class="d-flex flex-column justify-content-center align-items-center">
                                                                    <svg width="47" height="46" viewBox="0 0 47 46"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect x="3.5" y="3" width="40" height="40"
                                                                            rx="20" fill="#F2F4F7" />
                                                                        <rect x="3.5" y="3" width="40" height="40"
                                                                            rx="20" stroke="#F9FAFB" stroke-width="6" />
                                                                        <g clip-path="url(#clip0_798_4571)">
                                                                            <path
                                                                                d="M26.8335 26.3332L23.5002 22.9999M23.5002 22.9999L20.1669 26.3332M23.5002 22.9999V30.4999M30.4919 28.3249C31.3047 27.8818 31.9467 27.1806 32.3168 26.3321C32.6868 25.4835 32.7637 24.5359 32.5354 23.6388C32.307 22.7417 31.7865 21.9462 31.0558 21.3778C30.3251 20.8094 29.4259 20.5005 28.5002 20.4999H27.4502C27.198 19.5243 26.7278 18.6185 26.0752 17.8507C25.4225 17.0829 24.6042 16.4731 23.682 16.0671C22.7597 15.661 21.7573 15.4694 20.7503 15.5065C19.7433 15.5436 18.7578 15.8085 17.8679 16.2813C16.9779 16.7541 16.2068 17.4225 15.6124 18.2362C15.018 19.05 14.6158 19.9879 14.436 20.9794C14.2563 21.9709 14.3036 22.9903 14.5746 23.961C14.8455 24.9316 15.3329 25.8281 16.0002 26.5832"
                                                                                stroke="#475467" stroke-width="1.66667"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </g>
                                                                        <defs>
                                                                            <clipPath id="clip0_798_4571">
                                                                                <rect width="20" height="20"
                                                                                    fill="white"
                                                                                    transform="translate(13.5 13)" />
                                                                            </clipPath>
                                                                        </defs>
                                                                    </svg>
                                                                    <label for="attachment">Click to upload</label>
                                                                </div>
                                                                <input type="file" id="attachment"
                                                                    class="form-control position-absolute attachment-input"
                                                                    name="attachment" multiple style="opacity: 0;" />
                                                               
                                                                <ul id="file-preview"
                                                                    class="mt-1 d-flex flex-column text-start ps-0 mb-0">
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
                            <button class="styled-button-normal w-100 px-5" style="width: 100% !important; font-size: 14px !important; font-weight: 600; height: 40px !important; min-height: 40px !important" type="submit">Submit Payment</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    @include('adm::layouts.footer')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js"
        integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

            signaturePad.onEnd = function () {
            if (!signaturePad.isEmpty()) {
                var dataURL = signaturePad.toDataURL();
                hiddenInput.value = dataURL;
            }
        };

            return signaturePad;
        }


        var customerSignaturePad = initializeSignaturePad('signature-pad-customer', 'customer_signature_input');


        document.getElementById("clear-customer").addEventListener('click', function () {
            customerSignaturePad.clear();
            document.getElementById('customer_signature_input').value = '';
        });


        document.getElementById("save-customer").addEventListener('click', function () {
            if (customerSignaturePad.isEmpty()) {
                alert("Customer signature is empty."); 
            } else {
                var dataURL = customerSignaturePad.toDataURL();
                console.log("Customer signature saved:", dataURL);
            }
        });
    </script> 
    
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
<script>
$(document).ready(function () {
    $('select[name="customer"]').on('change', function () {
        var customerId = $(this).val();

        if (customerId) {
            $.ajax({
                url: '{{url('adm/get-customer-details')}}/' + customerId,
                type: 'GET',
                success: function (data) {
                    // console.log(data);
                  $('input[name="mobile_no"]').val(data.mobile_number);
                },
                error: function () {
                    alert('Error retrieving data');
                }
            });
        } else {
           
        }
    });
});
</script>
