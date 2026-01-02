@include('adm::layouts.header')
<link rel="stylesheet" href="{{ asset('adm_assets/css/signature_pad.css') }}">
<?php
use App\Models\Customers;
?>
<div class="content px-0">
            <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
                <h3 class="page-title">View Temporary Receipt</h3>
              
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
                      
                        <div class="d-flex flex-column">
                           
                            <div class="table-container">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th>Receipt No.</th>
                                            <th>Customer Name</th>
                                            <th>Invoice no.</th>
                                            <th>Payment method</th>
                                            <th class="sticky-column">Reciept Amount</th>
                                        </tr>
                                    </thead>
                                  @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment['receipt_no'] }}</td>
                                        <td>{{ $payment['customer_name'] }}</td>
                                        <td>{{ $payment['invoice_no'] }}</td>
                                        <td>{{ ucfirst($payment['payment_method']) }}</td>
                                       
                                        <td class="sticky-column">{{ $payment['amount'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No payments found for this collection.</td>
                                    </tr>
                                    @endforelse

                                    </tbody>
                                </table>
                            </div>
                            <form action="" class="content  p-2" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class=" customer-e-signature">
                                <div class="wrapper signature_pad d-flex flex-column">
                                    <h3 class="mb-0">Customer's E-signature</h3>
                                    <p>Sign to confirm payment.</p>
                                    <canvas id="signature-pad-customer" width="300" height="250"></canvas>
                                     <input type="hidden" name="batch_id" id="batch_id" value="{{$batch->id}}">
                                    <input type="hidden" name="customer_signature" id="customer_signature_input">
                                     @if($errors->has("customer_signature")) <div class="alert alert-danger mt-2">{{ $errors->first('customer_signature') }}</div>@endif
                                    <div class="d-flex flex-row my-2">
                                        <div class="clear-btn styled-button-red me-2">
                                            <button id="clear-customer"><span> Clear </span></button>
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
                                    type="submit">Submit Signature</button>
                            </div>
                            </form>
                        </div>
                </div>

                
            </div>
           
        </div>
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