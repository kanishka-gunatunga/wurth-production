@include('adm::layouts.header')
<link rel="stylesheet" href="{{ asset('adm_assets/css/signature_pad.css') }}">
<?php
use App\Models\Customers;
?>
 <div class="content px-0">
            <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
                <h3 class="page-title">{{$customer_details->name}}</h3>
                <button  class="my-3 small-button" style="background-color: #000 !important;" data-bs-toggle="modal" data-bs-target="#paymentHistory">
                    Payment History
                </button>
                <div class="modal fade" id="paymentHistory" tabindex="-1" aria-labelledby="paymentHistoryLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                       
                        <div class="modal-body">
                          <div class="row border-bottom mb-4">
                            <div class="col-md-6 border-end p-3 text-center">
                                <p class="gray-small-title mb-1">Total Payment Made</p>
                                <h3 class="black-large-text mb-1">{{number_format($payments->count())}}</h3>
                            </div>
                            <div class="col-md-6 p-3 text-center">
                                <p class="gray-small-title mb-1">Total Paid Amount</p>
                                <?php 
                                $payment_total = 0;
                                foreach($payments as $payment){
                                    $payment_total = $payment_total + $payment->amount;
                                } ?>
                                <h3 class="black-large-text mb-1">Rs.{{number_format($payment_total)}}</h3>
                            </div>
                          </div>

                        <?php foreach($payments as $payment){ 
                        if($payment->type == 'cash'){
                            $payment_name = 'Cash Payment';
                        }
                        if($payment->type == 'fund-transfer'){
                            $payment_name = 'Fund Transfer';
                        }
                        if($payment->type == 'cheque'){
                            $payment_name = 'Cheque Payment';
                        }
                        if($payment->type == 'card'){
                            $payment_name = 'Card Payment';
                        }
                        ?>
                          <div>
                          <div class="row border-bottom">
                            <div class="col-md-8  p-3 text-start">
                                <h4 class="black-title mb-0">{{$payment_name}} - #{{$payment->id}}</h4>
                            </div>
                            <div class="col-md-4 p-3 text-end">
                                <a href="{{url('adm/resend-receipt/'.$payment->id)}}" class="my-3 small-button">
                                Resend Receipt
                                </a>
                            </div>
                          </div>
                          <div class="d-flex flex-column px-0 py-3">
                            <label class="form-check-label d-flex flex-column" for="flexCheckDefault">
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Payment Method :</span>
                                    <span class="label-value">{{$payment_name}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Paid Amount :</span>
                                    <span class="label-value">Rs.{{number_format($payment->amount)}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Paid Date : </span>
                                    <span class="label-value">{{ $payment->created_at->format('d.m.Y') }}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Receipt No : </span>
                                    <span class="label-value">#{{$payment->id}}</span>
                                </div> 
                               
                            </label>
                        </div>
                        </div>
                    <?php } ?>
                       


                        </div>
                       
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3"
                style="border: solid 1px #9D9D9D;">
                <div class="col-6 d-flex flex-column py-3 text-center">
                    <p class="gray-small-title mb-1">Invoice Number</p>
                    <p class="black-large-text mb-1">{{$invoice_details->invoice_or_cheque_no}}</p>
                </div>
                <div class="col-6 d-flex flex-column py-3 text-center" style="border-left: solid 1px #9D9D9D;">
                    <p class="gray-small-title mb-1">Total Invoice Amount</p>
                    <p class="black-large-text mb-1">Rs. {{number_format($invoice_details->amount)}}</p>
                </div>
            </div>
            <div class="d-flex flex-column">
                <div class="d-flex w-100 flex-column px-3 mb-3 mt-3">
                    <div class="card-view px-0 ">
                        <div class="d-flex flex-row justify-content-between align-items-center px-3 mb-2">
                            <h4 class="black-title mb-0">Payment Details </h4>
                            <a href="" class="my-3 small-button">
                                <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1.90356 8.20166H2.61607L7.50357 3.31416L6.79107 2.60166L1.90356 7.48916V8.20166ZM0.903564 9.20166V7.07666L7.50357 0.48916C7.60357 0.397493 7.71407 0.32666 7.83507 0.27666C7.95607 0.22666 8.08307 0.20166 8.21607 0.20166C8.34907 0.20166 8.47823 0.22666 8.60357 0.27666C8.7289 0.32666 8.83723 0.40166 8.92857 0.50166L9.61607 1.20166C9.71607 1.29333 9.78907 1.40166 9.83507 1.52666C9.88107 1.65166 9.9039 1.77666 9.90357 1.90166C9.90357 2.03499 9.88073 2.16216 9.83507 2.28316C9.7894 2.40416 9.7164 2.51449 9.61607 2.61416L3.02857 9.20166H0.903564ZM7.14107 2.96416L6.79107 2.60166L7.50357 3.31416L7.14107 2.96416Z"
                                        fill="white" />
                                </svg>

                                Edit
                            </a>
                        </div>

                        <div class="d-flex flex-column px-3">
                            <label class="form-check-label d-flex flex-column" for="flexCheckDefault">
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Customer’s Mobile No. :</span>
                                    <span class="label-value"> {{$customer_details->mobile_number ?? '-'}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Customer’s Email :</span>
                                    <span class="label-value"> {{$customer_details->mobile_number ?? '-'}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Customer’s Address : </span>
                                    <span class="label-value"> {{$customer_details->address ?? '-'}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Invoice Date : </span>
                                    <span class="label-value">{{ $invoice_details->created_at->format('d.m.Y') }}</span>
                                </div> 
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Total Invoice Amount :</span>
                                    <span class="label-value">Rs. {{number_format($invoice_details->amount)}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Outstanding Balance : </span>
                                    <span class="label-value">Rs. {{number_format($invoice_details->amount - $invoice_details->paid_amount)}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Total Paid Amount : </span>
                                    <span class="label-value">Rs. {{number_format($invoice_details->paid_amount)}}</span>
                                </div>
                                <div class="d-flex flex-row mb-1">
                                    <span class="label-name">Final Payable Amount : </span>
                                    <span class="label-value" style="font-weight: 700;">Rs. {{number_format($invoice_details->amount - $invoice_details->paid_amount)}}</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex flex-column collapse-wrapper w-100 px-0 mt-4">
                        <div class="d-flex flex-column collapse-wrapper w-100 px-0 mt-4">
                            <div class="accordion" id="paymentAccordion">
                                <!-- Cash Payment Accordion Item -->
                                <div class="accordion-item shadow-border mb-3" style="border-radius: 8px;">
                                    <p class="accordion-header" id="cashPaymentHeading">
                                        <button class="accordion-button d-flex justify-content-between" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#cash_payment1"
                                            aria-expanded="false" aria-controls="cash_payment1">
                                            <svg class="me-2" width="30" height="22" viewBox="0 0 30 22" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2 9.37441L5.64909 5.27031C6.13695 4.7213 6.73558 4.28187 7.40555 3.98094C8.07552 3.68001 8.80164 3.52442 9.53609 3.52441H9.79999M2 20.4244H9.14999L14.35 16.5244C14.35 16.5244 15.403 15.8133 16.95 14.5744C20.2 11.9744 16.95 7.85861 13.7 10.0244C11.0532 11.7885 8.49999 13.2744 8.49999 13.2744"
                                                    stroke="black" stroke-width="2.26667" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.7998 12.6262V4.17617C9.7998 3.48661 10.0737 2.82529 10.5613 2.33769C11.0489 1.8501 11.7102 1.57617 12.3998 1.57617H25.3998C26.0893 1.57617 26.7507 1.8501 27.2383 2.33769C27.7258 2.82529 27.9998 3.48661 27.9998 4.17617V11.9762C27.9998 12.6657 27.7258 13.327 27.2383 13.8146C26.7507 14.3022 26.0893 14.5762 25.3998 14.5762H16.9498"
                                                    stroke="black" stroke-width="2.26667" />
                                                <path
                                                    d="M24.7498 8.08663L24.7628 8.07233M13.0498 8.08663L13.0628 8.07233M18.8998 10.6736C18.2102 10.6736 17.5489 10.3997 17.0613 9.9121C16.5737 9.42451 16.2998 8.76319 16.2998 8.07363C16.2998 7.38407 16.5737 6.72275 17.0613 6.23515C17.5489 5.74756 18.2102 5.47363 18.8998 5.47363C19.5894 5.47363 20.2507 5.74756 20.7383 6.23515C21.2259 6.72275 21.4998 7.38407 21.4998 8.07363C21.4998 8.76319 21.2259 9.42451 20.7383 9.9121C20.2507 10.3997 19.5894 10.6736 18.8998 10.6736Z"
                                                    stroke="black" stroke-width="2.26667" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            Cash Payment
                                        </button>
                                    </p>
                                    <div id="cash_payment1" class="accordion-collapse collapse"
                                        aria-labelledby="cashPaymentHeading" data-bs-parent="#paymentAccordion"
                                        style="border-top: 1px solid #dee2e6 !important;">
                                        <div class="accordion-body card card-body border-0">
                                            <div class="card card-body border-0">
                                                <form id="CashPaymentForm" class="CashPaymentForm content needs-validation p-0 border-0 px-1" novalidate>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="amount">Amount</label>
                                                        <input type="number" class="form-control"
                                                            placeholder="Enter Amount" name="cash_amount" required />
                                                        <div class="invalid-feedback">
                                                            Amount is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="discount">Discount <span
                                                                style="font-size: 12px !important; color:#868686">Max
                                                                Discounts:
                                                                5% (7 Days), 3% (30 Days)</span></label>
                                                        <input type="number" class="form-control" placeholder="3%"
                                                            name="cash_discount" id="cash_discount" required />
                                                        <div class="invalid-feedback">
                                                            Discount is required
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                                                        style=" border-radius: 8px;">
                                                        <div class="col-12 d-flex flex-column py-4 text-center">
                                                            <p class="gray-small-title mb-1"
                                                                style="color: #595959; font-weight: 500;">Total Amount
                                                            </p>
                                                            <p class="black-large-text mb-1" style="color:#CC0000" id="cash_total">Rs. 0</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100 justify-content-end align-items-center">
                                                        <button class="styled-button-red px-5"
                                                            style="font-size: 15px; min-height: 42px"
                                                            type="submit">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fund Transfer Accordion Item -->
                                <div id="fundAccordion" class="accordion">
                                    <div id="fundAccordionItem1" class="accordion-item shadow-border mb-3" style="border-radius: 8px;">
                                        <p class="accordion-header" id="fundTransferHeading1">
                                            <button class="accordion-button d-flex justify-content-between" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#cash_payment2_1"
                                                aria-expanded="false" aria-controls="cash_payment2_1">
                                                <svg class="me-2" width="22" height="20" viewBox="0 0 22 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.38 7.92C11.2603 7.97291 11.1309 8.00023 11 8.00023C10.8691 8.00023 10.7397 7.97291 10.62 7.92H11.38ZM11.38 7.92C11.5028 7.87241 11.6149 7.80104 11.71 7.71L11.38 7.92ZM10.6402 7.87427L10.6402 7.87422L10.6381 7.87338C10.5216 7.82821 10.4151 7.76049 10.3248 7.67411C10.3247 7.67403 10.3247 7.67396 10.3246 7.67388L8.03536 5.42464C8.03531 5.4246 8.03526 5.42455 8.03521 5.4245C7.85107 5.24028 7.74762 4.99047 7.74762 4.73C7.74762 4.46946 7.85112 4.21959 8.03536 4.03536C8.21959 3.85112 8.46946 3.74762 8.73 3.74762C8.99018 3.74762 9.23973 3.85084 9.42388 4.03459L9.96312 4.62376L10.05 4.71869V4.59V1C10.05 0.748044 10.1501 0.506408 10.3282 0.328249C10.5064 0.150089 10.748 0.05 11 0.05C11.252 0.05 11.4936 0.150089 11.6718 0.328249C11.8499 0.506408 11.95 0.748044 11.95 1V4.59V4.71869L12.0369 4.62376L12.5761 4.03459C12.7603 3.85084 13.0098 3.74762 13.27 3.74762C13.5305 3.74762 13.7804 3.85112 13.9646 4.03536C14.1489 4.21959 14.2524 4.46946 14.2524 4.73C14.2524 4.99047 14.1489 5.24028 13.9648 5.4245C13.9647 5.42455 13.9647 5.4246 13.9646 5.42464L11.6754 7.67388C11.6753 7.67396 11.6753 7.67403 11.6752 7.67411C11.5849 7.76049 11.4784 7.82821 11.3619 7.87338L11.3619 7.87333L11.3598 7.87427C11.2465 7.92436 11.1239 7.95023 11 7.95023C10.8761 7.95023 10.7535 7.92436 10.6402 7.87427ZM9.36107 10.5472C9.84619 10.223 10.4165 10.05 11 10.05C11.7824 10.05 12.5327 10.3608 13.086 10.914C13.6392 11.4673 13.95 12.2176 13.95 13C13.95 13.5835 13.777 14.1538 13.4528 14.6389C13.1287 15.1241 12.668 15.5022 12.1289 15.7254C11.5899 15.9487 10.9967 16.0071 10.4245 15.8933C9.85224 15.7795 9.3266 15.4985 8.91403 15.086C8.50147 14.6734 8.22051 14.1478 8.10668 13.5755C7.99286 13.0033 8.05128 12.4101 8.27456 11.8711C8.49783 11.332 8.87594 10.8713 9.36107 10.5472ZM10.4167 13.873C10.5893 13.9884 10.7923 14.05 11 14.05C11.2785 14.05 11.5455 13.9394 11.7425 13.7425C11.9394 13.5455 12.05 13.2785 12.05 13C12.05 12.7923 11.9884 12.5893 11.873 12.4167C11.7577 12.244 11.5937 12.1094 11.4018 12.0299C11.21 11.9505 10.9988 11.9297 10.7952 11.9702C10.5915 12.0107 10.4044 12.1107 10.2575 12.2575C10.1107 12.4044 10.0107 12.5915 9.97018 12.7952C9.92966 12.9988 9.95045 13.21 10.0299 13.4018C10.1094 13.5937 10.244 13.7577 10.4167 13.873ZM17.7899 12.4722C17.8943 12.6284 17.95 12.8121 17.95 13C17.95 13.252 17.8499 13.4936 17.6718 13.6718C17.4936 13.8499 17.252 13.95 17 13.95C16.8121 13.95 16.6284 13.8943 16.4722 13.7899C16.316 13.6855 16.1942 13.5371 16.1223 13.3635C16.0504 13.19 16.0316 12.9989 16.0683 12.8147C16.1049 12.6304 16.1954 12.4611 16.3282 12.3282C16.4611 12.1954 16.6304 12.1049 16.8147 12.0683C16.9989 12.0316 17.19 12.0504 17.3635 12.1223C17.5371 12.1942 17.6855 12.316 17.7899 12.4722ZM16 6.05H19C19.7824 6.05 20.5327 6.3608 21.086 6.91403C21.6392 7.46727 21.95 8.21761 21.95 9V17C21.95 17.7824 21.6392 18.5327 21.086 19.086C20.5327 19.6392 19.7824 19.95 19 19.95H3C2.21761 19.95 1.46727 19.6392 0.914035 19.086C0.360803 18.5327 0.05 17.7824 0.05 17V9C0.05 8.21761 0.360803 7.46727 0.914035 6.91403C1.46727 6.3608 2.21761 6.05 3 6.05H6C6.25196 6.05 6.49359 6.15009 6.67175 6.32825C6.84991 6.50641 6.95 6.74804 6.95 7C6.95 7.25196 6.84991 7.49359 6.67175 7.67175C6.49359 7.84991 6.25196 7.95 6 7.95H3C2.72152 7.95 2.45445 8.06063 2.25754 8.25754C2.06062 8.45445 1.95 8.72152 1.95 9V17C1.95 17.2785 2.06062 17.5455 2.25754 17.7425C2.45445 17.9394 2.72152 18.05 3 18.05H19C19.2785 18.05 19.5455 17.9394 19.7425 17.7425C19.9394 17.5455 20.05 17.2785 20.05 17V9C20.05 8.72152 19.9394 8.45445 19.7425 8.25754C19.5455 8.06063 19.2785 7.95 19 7.95H16C15.748 7.95 15.5064 7.84991 15.3282 7.67175C15.1501 7.49359 15.05 7.25196 15.05 7C15.05 6.74804 15.1501 6.50641 15.3282 6.32825C15.5064 6.15009 15.748 6.05 16 6.05ZM4.2101 13.5278C4.10572 13.3716 4.05 13.1879 4.05 13C4.05 12.748 4.15009 12.5064 4.32825 12.3282C4.50641 12.1501 4.74804 12.05 5 12.05C5.18789 12.05 5.37156 12.1057 5.52779 12.2101C5.68402 12.3145 5.80578 12.4629 5.87769 12.6365C5.94959 12.81 5.9684 13.0011 5.93175 13.1853C5.89509 13.3696 5.80461 13.5389 5.67175 13.6718C5.53889 13.8046 5.36962 13.8951 5.18534 13.9317C5.00105 13.9684 4.81004 13.9496 4.63645 13.8777C4.46286 13.8058 4.31449 13.684 4.2101 13.5278Z"
                                                    fill="black" stroke="white" stroke-width="0.1" />
                                            </svg>

                                                Fund Transfer
                                            </button>
                                        </p>
                                        <div id="cash_payment2_1" class="accordion-collapse collapse"
                                            aria-labelledby="fundTransferHeading1" data-bs-parent="#fundAccordion"
                                            style="border-top: 1px solid #dee2e6 !important;">
                                            <div class="accordion-body card card-body border-0">
                                                <form id="FundTransferForm1" class="content needs-validation p-0 border-0 px-1" enctype="multipart/form-data" novalidate>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="amount1">Amount</label>
                                                        <input type="number" class="form-control amount" placeholder="Enter Amount" name="amount" required />
                                                        <div class="invalid-feedback">
                                                            Amount is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="transfer_date1">Transfer Date</label>
                                                        <input type="date" class="form-control" id="transfer_date1"
                                                            placeholder="dd/mm/yyyy" name="transfer_date" required />
                                                        <div class="invalid-feedback">
                                                            Transfer Date is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="transfer_reference_number1">Transfer Reference Number</label>
                                                        <input type="number" class="form-control"
                                                            id="transfer_reference_number1"
                                                            placeholder="Enter Transfer Reference Number"
                                                            name="transfer_reference_number" required />
                                                        <div class="invalid-feedback">
                                                            Transfer Reference Number is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="discount1">Discount <span
                                                                style="font-size: 12px !important; color:#868686">Max
                                                                Discounts:
                                                                5% (7 Days), 3% (30 Days)</span></label>
                                                        <input type="number" class="form-control discount" placeholder="3%"
                                                            name="discount" id="discount1" required />
                                                        <div class="invalid-feedback">
                                                            Discount is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="screenshot" class="mb-1">Upload Transfer
                                                            Screenshot</label>
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
                                                                    <label for="screenshot">Click to upload</label>
                                                                </div>
                                                                <input type="file" id="screenshot"
                                                                    class="form-control position-absolute screenshot-input"
                                                                    name="screenshot" multiple style="opacity: 0;" />
                                                                <div class="invalid-feedback">Upload Transfer Screenshot
                                                                    is
                                                                    required</div>
                                                                <ul id="file-preview"
                                                                    class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                                                        style="border-radius: 8px;">
                                                        <div class="col-12 d-flex flex-column py-4 text-center">
                                                            <p class="gray-small-title mb-1"
                                                                style="color: #595959;  font-weight: 500">Total Amount</p>
                                                            <p class="black-large-text mb-1 total-amount" style="color:#CC0000">Rs. 0.00</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100 justify-content-end align-items-center">
                                                        <button class="styled-button-red px-5 submit-fund-transfer" type="submit">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary my-2 add-another-button" id="addFundTransfer">+ Add Another Fund Transfer</button>

                                <!-- Cheque Payment Accordion Item -->
                            <div id="chequeAccordion" class="accordion">
                                <div id="chequeAccordionItem" class="accordion-item shadow-border mb-3" style="border-radius: 8px;">
                                    <p class="accordion-header" id="chequePaymentHeading">
                                        <button class="accordion-button d-flex justify-content-between" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#cheque_payment1"
                                            aria-expanded="false" aria-controls="cheque_payment1">
                                            <svg class="me-2" width="23" height="18" viewBox="0 0 23 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M5.875 11.25H9.25M5.875 6.75H17.125M1.375 1.125H21.625V14.625C21.625 15.2217 21.3879 15.794 20.966 16.216C20.544 16.6379 19.9717 16.875 19.375 16.875H3.625C3.02826 16.875 2.45597 16.6379 2.03401 16.216C1.61205 15.794 1.375 15.2217 1.375 14.625V1.125Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            Cheque Payment
                                        </button>
                                    </p>
                                    <div id="cheque_payment1" class="accordion-collapse collapse"
                                        aria-labelledby="chequePaymentHeading" data-bs-parent="#paymentAccordion"
                                        style="border-top: 1px solid #dee2e6 !important;">
                                        <div class="accordion-body card card-body border-0">
                                            <div class="card card-body border-0">
                                                <form id="ChequePaymentForm"
                                                    class="content needs-validation p-0 border-0 px-1" novalidate>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="cheque_number">Cheque Number</label>
                                                        <input type="number" class="form-control" id="cheque_number"
                                                            placeholder="Enter Cheque Number" name="cheque_number"
                                                            required />
                                                        <div class="invalid-feedback">
                                                            Cheque Number is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="cheque_date">Cheque Date</label>
                                                        <input type="date" class="form-control" id="cheque_date"
                                                            placeholder="dd/mm/yyyy" name="cheque_date" required />
                                                        <div class="invalid-feedback">
                                                            Cheque Date is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="cheque_amount">Cheque Amount</label>
                                                        <input type="number" class="form-control amount" id="cheque_amount"
                                                            placeholder="Enter Cheque Amount" name="cheque_amount"
                                                            required />
                                                        <div class="invalid-feedback">
                                                            Cheque Amount is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="bank_name">Bank Name</label>
                                                        <select class="form-select form-control"
                                                            aria-label="Default select example" id="bank_name"
                                                            name="bank_name">
                                                            <option selected value="0">Bank Of Ceylon</option>
                                                            <option value="1">One</option>
                                                            <option value="2">Two</option>
                                                            <option value="3">Three</option>
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Bank Name is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="branch_name">Branch Name</label>
                                                        <input type="text" class="form-control" id="branch_name"
                                                            placeholder="Enter Branch Name" name="branch_name"
                                                            required />
                                                        <div class="invalid-feedback">
                                                            Branch Name is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="discount">Discount <span
                                                                style="font-size: 12px !important; color:#868686">Max
                                                                Discounts:
                                                                5% (7 Days), 3% (30 Days)</span></label>
                                                        <input type="number" class="form-control discount" placeholder="3%"
                                                            name="discount" id="discount" required />
                                                        <div class="invalid-feedback">
                                                            Discount is required
                                                        </div>
                                                    </div>

                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <div class="form-check d-flex flex-row align-items-center">
                                                            <input class="form-check-input m-0 me-2 ms-0"
                                                                type="checkbox" id="date_cheque_tick"
                                                                style="margin-left: 0px !important;" name="post_dated">
                                                            <label class="form-check-label" for="date_cheque_tick">
                                                                Post - Dated Cheque
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="cheque_image" class="mb-1">Upload Cheque image
                                                        </label>
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
                                                                    <label for="screenshot">Click to upload</label>
                                                                </div>
                                                                <input type="file" id="cheque_image"
                                                                    class="form-control position-absolute screenshot-input"
                                                                    name="cheque_image" multiple style="opacity: 0;" />
                                                                <div class="invalid-feedback">Upload Transfer Screenshot
                                                                    is
                                                                    required</div>
                                                                <ul id="file-preview-cheque"
                                                                    class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                                                        style=" border-radius: 8px;">
                                                        <div class="col-12 d-flex flex-column py-4 text-center">
                                                            <p class="gray-small-title mb-1"
                                                                style="color: #595959;  font-weight: 500">Total Amount
                                                            </p>
                                                            <p class="black-large-text mb-1 total-amount" style="color:#CC0000">Rs.
                                                                0</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100 justify-content-end align-items-center">
                                                        <button class="styled-button-red px-5"
                                                            style="font-size: 15px; min-height: 42px"
                                                            type="submit">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                </div>
                                <button class="btn btn-primary my-2 add-another-button" id="addChequePayment">+ Add
                                    Another Cheque
                                    Payment</button>
                                    
                                <!-- Card Payment Accordion Item -->
                                <div id="cardAccordion" class="accordion"> 
                                <div id="cardAccordionItem" class="accordion-item shadow-border mb-3" style="border-radius: 8px;">
                                    <p class="accordion-header" id="cardPaymentHeading">
                                        <button class="accordion-button d-flex justify-content-between" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#card_payment1"
                                            aria-expanded="false" aria-controls="card_payment1">
                                            <svg class="me-2" width="22" height="18" viewBox="0 0 22 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21 9C21 5.229 21 3.343 19.828 2.172C18.656 1.001 16.771 1 13 1H9C5.229 1 3.343 1 2.172 2.172C1.001 3.344 1 5.229 1 9C1 12.771 1 14.657 2.172 15.828C3.344 16.999 5.229 17 9 17H13C16.771 17 18.657 17 19.828 15.828C20.482 15.175 20.771 14.3 20.898 13M9 13H5M13 13H11.5M1 7H6M21 7H10"
                                                    stroke="black" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>

                                            Card Payment
                                        </button>
                                    </p>
                                    <div id="card_payment1" class="accordion-collapse collapse"
                                        aria-labelledby="cardPaymentHeading" data-bs-parent="#paymentAccordion"
                                        style="border-top: 1px solid #dee2e6 !important;">
                                        <div class="accordion-body card card-body border-0">
                                            <div class="card card-body border-0">
                                                <form id="CardPaymentForm"
                                                    class="content needs-validation p-0 border-0 px-1" novalidate>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_amount">Amount</label>
                                                        <input type="number" class="form-control amount" id="card_amount"
                                                            placeholder="Enter Amount" name="card_amount" required />
                                                        <div class="invalid-feedback">
                                                            Amount is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_transfer_date">Transfer Date</label>
                                                        <input type="date" class="form-control" id="card_transfer_date"
                                                            placeholder="dd/mm/yyyy" name="card_transfer_date"
                                                            required />
                                                        <div class="invalid-feedback">
                                                            Transfer Date is required
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_discount">Discount <span
                                                                style="font-size: 12px !important; color:#868686">Max
                                                                Discounts:
                                                                5% (7 Days), 3% (30 Days)</span></label>
                                                        <input type="number" class="form-control discount" placeholder="3%"
                                                            name="card_discount" id="card_discount" required />
                                                        <div class="invalid-feedback">
                                                            Discount is required
                                                        </div>
                                                    </div>

                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_screenshot" class="mb-1">Upload Transfer
                                                            Screenshot</label>
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
                                                                    <label for="card_screenshot">Click to upload</label>
                                                                </div>
                                                                <input type="file" id="card_screenshot"
                                                                    class="form-control position-absolute screenshot-input"
                                                                    name="card_screenshot" multiple
                                                                    style="opacity: 0;" />
                                                                <div class="invalid-feedback">Upload Transfer Screenshot
                                                                    is
                                                                    required</div>
                                                                <ul id="file-preview-card-payment"
                                                                    class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                                                        style=" border-radius: 8px;">
                                                        <div class="col-12 d-flex flex-column py-4 text-center">
                                                            <p class="gray-small-title mb-1"
                                                                style="color: #595959;  font-weight: 500">Total Amount
                                                            </p>
                                                            <p class="black-large-text mb-1 total-amount" style="color:#CC0000">Rs. 0</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100 justify-content-end align-items-center">
                                                        <button class="styled-button-red px-5"
                                                            style="font-size: 15px; min-height: 42px"
                                                            type="submit">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <button class="btn btn-primary my-2 add-another-button" id="addCardPayment">+ Add
                                    Another Card
                                    Payment</button>
                            </div>
                        </div>

                    </div>

                    <!-- payment summery section -->
                    <div class="d-flex flex-column w-100 shadow-border mb-3 py-3 pb-2 px-3 "
                        style="border-radius: 8px;">
                        <span class="payment-summery-text-collection mb-2">Payment Summery </span>
                        
                        <div id="payment-summery">
                        
                        </div>

                        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-2 shadow-border"
                            style=" border-radius: 8px;">
                            <div class="col-12 d-flex flex-column py-4 text-center">
                                <p class="gray-small-title mb-1" style="color: #595959; font-weight: 500;">Payment
                                    Amount</p>
                                <p class="black-large-text mb-1" style="color:#CC0000" >Rs. <span id="payment-amount">0</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- ADM signature section -->
                    <div class="flex-row">
                        <div class="wrapper signature_pad d-flex flex-column">
                            <h3 class="mb-0">E-signature</h3>
                            <p>Sign to confirm payment.</p>
                            <canvas id="signature-pad-adm" width="300" height="250"></canvas>
                            <input type="hidden" name="adm_signature" id="adm_signature_input">
                            <div class="d-flex flex-row my-2">
                                <div class="clear-btn styled-button-red me-2">
                                    <button id="clear-admin"><span> Clear </span></button>
                                </div>
                                <!-- <div class="save-btn styled-button-red">
                                    <button id="save-admin"><span> Save </span></button>
                                </div> -->
                            </div>
                        </div>

                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" name="temp_receipt" id="temp_receipt">
                        <label class="form-check-label ckeck-label-collection-inner ms-3" for="temp_receipt">
                            Temporary Receipt
                        </label>
                    </div>
                    <!-- customer signature -->
                    <div class=" customer-e-signature">
                        <div class="wrapper signature_pad d-flex flex-column">
                            <h3 class="mb-0">Customer's E-signature</h3>
                            <p>Sign to confirm payment.</p>
                            <canvas id="signature-pad-customer" width="300" height="250"></canvas>
                            <input type="hidden" name="customer_signature" id="customer_signature_input">
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
                    <div class="mt-2 temp-receipt-reason" style="display:none;">
                        <div class="wrapper signature_pad d-flex flex-column">
                            <h3 class="mb-0">Temporary Receipt</h3>
                            <p>Reason for Temporary Receipt</p>
                            <textarea  class="form-control " rows="4"  name="reason_for_temp" id="reason_for_temp" ></textarea>
                           
                        </div>

                    </div>

                    <div class="d-flex w-100 justify-content-center align-items-center pt-3">
                        <button class="styled-button-normal w-100 px-5"
                            style="width: 100% !important; font-size: 14px !important; font-weight: 600; height: 40px !important; min-height: 40px !important"
                            type="button" id="submit-payment">Submit Payment</button>
                    </div>
                </div>
            </div>
        </div>

<input type="hidden"   id="payment_batch_id" name="payment_batch_id" value="" />
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

        var adminSignaturePad = initializeSignaturePad('signature-pad-adm', 'adm_signature_input');
        var customerSignaturePad = initializeSignaturePad('signature-pad-customer', 'customer_signature_input');

        document.getElementById("clear-admin").addEventListener('click', function () {
            adminSignaturePad.clear();
            document.getElementById('adm_signature_input').value = '';
        });

        document.getElementById("clear-customer").addEventListener('click', function () {
            customerSignaturePad.clear();
            document.getElementById('customer_signature_input').value = '';
        });

        document.getElementById("save-admin").addEventListener('click', function () {
            if (adminSignaturePad.isEmpty()) {
                alert("Admin signature is empty.");
            } else {
                var dataURL = adminSignaturePad.toDataURL();
                console.log("Admin signature saved:", dataURL);
            }
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
     let preloader;
    $(document).ready(function() {
        preloader = document.getElementById('wurth-preloader');
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
    $(document).on('submit', '.CashPaymentForm', function(e) {
        e.preventDefault(); 
        preloader.style.display = 'flex';
        var form = $(this);
        var formData = form.serialize();

        var batchId = $('#payment_batch_id').val();
        formData += '&payment_batch_id=' + encodeURIComponent(batchId);

        console.log(formData);
        $.ajax({
            url: '{{ url('adm/add-cash-payment') }}/{{$invoice_details->id}}',
            method: 'POST',
            data: formData,
            success: function(response) {
                preloader.style.display = 'none';
                console.log('Saved successfully:', response);
                toastr.success(response.message);
                form.trigger('reset');

                $('#payment_batch_id').val(response.payment_batch_id);

                let paymentType = 'Cash Payment';
                let paid_amount = response.amount || 0;
                let discount = response.discount || 0;

                appendPaymentSummery(paymentType,paid_amount,discount);
            },
            error: function(xhr, status, error) {
            console.error('Error saving:', error);
            preloader.style.display = 'none';
            let errorMessage = 'An unexpected error occurred';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.responseJSON.error) {
                    errorMessage += ' - ' + xhr.responseJSON.error;
                }
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }

            toastr.error(errorMessage);
        }
        });
    });
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
document.addEventListener('DOMContentLoaded', function () {
    const amountInput = document.querySelector('input[name="cash_amount"]');
    const discountInput = document.querySelector('input[name="cash_discount"]');
    const totalAmountDisplay = document.querySelector('#cash_total');

    function updateTotalAmount() {
        let amount = parseFloat(amountInput.value) || 0;
        let discount = parseFloat(discountInput.value) || 0;

        if (discount > 100) {
            discount = 100; 
        }

        let discountedAmount = amount - (amount * (discount / 100));
        discountedAmount = discountedAmount < 0 ? 0 : discountedAmount;

        totalAmountDisplay.textContent = `Rs. ${discountedAmount.toFixed(2)}`;
    }

    amountInput.addEventListener('input', updateTotalAmount);
    discountInput.addEventListener('input', updateTotalAmount);
});


function calculateTotalAmount(accordionId) {
    let amount = parseFloat($(accordionId + ' .amount').val()) || 0;
    let discount = parseFloat($(accordionId + ' .discount').val()) || 0;

    let totalAmount = amount - (amount * discount / 100);
    totalAmount = totalAmount.toFixed(2);

    $(accordionId + ' .total-amount').text('Rs. ' + totalAmount);
}


$('#addFundTransfer').on('click', function () {

    let newItemNumber = $('#fundAccordion .accordion-item').length + 1;

    let newAccordionItem = `
        <div id="fundAccordionItem${newItemNumber}" class="accordion-item shadow-border mb-3" style="border-radius: 8px;">
            <p class="accordion-header" id="fundTransferHeading${newItemNumber}">
                <button class="accordion-button d-flex justify-content-between" type="button"
                    data-bs-toggle="collapse" data-bs-target="#cash_payment2_${newItemNumber}"
                    aria-expanded="false" aria-controls="cash_payment2_${newItemNumber}">
                    <svg class="me-2" width="22" height="20" viewBox="0 0 22 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.38 7.92C11.2603 7.97291 11.1309 8.00023 11 8.00023C10.8691 8.00023 10.7397 7.97291 10.62 7.92H11.38ZM11.38 7.92C11.5028 7.87241 11.6149 7.80104 11.71 7.71L11.38 7.92ZM10.6402 7.87427L10.6402 7.87422L10.6381 7.87338C10.5216 7.82821 10.4151 7.76049 10.3248 7.67411C10.3247 7.67403 10.3247 7.67396 10.3246 7.67388L8.03536 5.42464C8.03531 5.4246 8.03526 5.42455 8.03521 5.4245C7.85107 5.24028 7.74762 4.99047 7.74762 4.73C7.74762 4.46946 7.85112 4.21959 8.03536 4.03536C8.21959 3.85112 8.46946 3.74762 8.73 3.74762C8.99018 3.74762 9.23973 3.85084 9.42388 4.03459L9.96312 4.62376L10.05 4.71869V4.59V1C10.05 0.748044 10.1501 0.506408 10.3282 0.328249C10.5064 0.150089 10.748 0.05 11 0.05C11.252 0.05 11.4936 0.150089 11.6718 0.328249C11.8499 0.506408 11.95 0.748044 11.95 1V4.59V4.71869L12.0369 4.62376L12.5761 4.03459C12.7603 3.85084 13.0098 3.74762 13.27 3.74762C13.5305 3.74762 13.7804 3.85112 13.9646 4.03536C14.1489 4.21959 14.2524 4.46946 14.2524 4.73C14.2524 4.99047 14.1489 5.24028 13.9648 5.4245C13.9647 5.42455 13.9647 5.4246 13.9646 5.42464L11.6754 7.67388C11.6753 7.67396 11.6753 7.67403 11.6752 7.67411C11.5849 7.76049 11.4784 7.82821 11.3619 7.87338L11.3619 7.87333L11.3598 7.87427C11.2465 7.92436 11.1239 7.95023 11 7.95023C10.8761 7.95023 10.7535 7.92436 10.6402 7.87427ZM9.36107 10.5472C9.84619 10.223 10.4165 10.05 11 10.05C11.7824 10.05 12.5327 10.3608 13.086 10.914C13.6392 11.4673 13.95 12.2176 13.95 13C13.95 13.5835 13.777 14.1538 13.4528 14.6389C13.1287 15.1241 12.668 15.5022 12.1289 15.7254C11.5899 15.9487 10.9967 16.0071 10.4245 15.8933C9.85224 15.7795 9.3266 15.4985 8.91403 15.086C8.50147 14.6734 8.22051 14.1478 8.10668 13.5755C7.99286 13.0033 8.05128 12.4101 8.27456 11.8711C8.49783 11.332 8.87594 10.8713 9.36107 10.5472ZM10.4167 13.873C10.5893 13.9884 10.7923 14.05 11 14.05C11.2785 14.05 11.5455 13.9394 11.7425 13.7425C11.9394 13.5455 12.05 13.2785 12.05 13C12.05 12.7923 11.9884 12.5893 11.873 12.4167C11.7577 12.244 11.5937 12.1094 11.4018 12.0299C11.21 11.9505 10.9988 11.9297 10.7952 11.9702C10.5915 12.0107 10.4044 12.1107 10.2575 12.2575C10.1107 12.4044 10.0107 12.5915 9.97018 12.7952C9.92966 12.9988 9.95045 13.21 10.0299 13.4018C10.1094 13.5937 10.244 13.7577 10.4167 13.873ZM17.7899 12.4722C17.8943 12.6284 17.95 12.8121 17.95 13C17.95 13.252 17.8499 13.4936 17.6718 13.6718C17.4936 13.8499 17.252 13.95 17 13.95C16.8121 13.95 16.6284 13.8943 16.4722 13.7899C16.316 13.6855 16.1942 13.5371 16.1223 13.3635C16.0504 13.19 16.0316 12.9989 16.0683 12.8147C16.1049 12.6304 16.1954 12.4611 16.3282 12.3282C16.4611 12.1954 16.6304 12.1049 16.8147 12.0683C16.9989 12.0316 17.19 12.0504 17.3635 12.1223C17.5371 12.1942 17.6855 12.316 17.7899 12.4722ZM16 6.05H19C19.7824 6.05 20.5327 6.3608 21.086 6.91403C21.6392 7.46727 21.95 8.21761 21.95 9V17C21.95 17.7824 21.6392 18.5327 21.086 19.086C20.5327 19.6392 19.7824 19.95 19 19.95H3C2.21761 19.95 1.46727 19.6392 0.914035 19.086C0.360803 18.5327 0.05 17.7824 0.05 17V9C0.05 8.21761 0.360803 7.46727 0.914035 6.91403C1.46727 6.3608 2.21761 6.05 3 6.05H6C6.25196 6.05 6.49359 6.15009 6.67175 6.32825C6.84991 6.50641 6.95 6.74804 6.95 7C6.95 7.25196 6.84991 7.49359 6.67175 7.67175C6.49359 7.84991 6.25196 7.95 6 7.95H3C2.72152 7.95 2.45445 8.06063 2.25754 8.25754C2.06062 8.45445 1.95 8.72152 1.95 9V17C1.95 17.2785 2.06062 17.5455 2.25754 17.7425C2.45445 17.9394 2.72152 18.05 3 18.05H19C19.2785 18.05 19.5455 17.9394 19.7425 17.7425C19.9394 17.5455 20.05 17.2785 20.05 17V9C20.05 8.72152 19.9394 8.45445 19.7425 8.25754C19.5455 8.06063 19.2785 7.95 19 7.95H16C15.748 7.95 15.5064 7.84991 15.3282 7.67175C15.1501 7.49359 15.05 7.25196 15.05 7C15.05 6.74804 15.1501 6.50641 15.3282 6.32825C15.5064 6.15009 15.748 6.05 16 6.05ZM4.2101 13.5278C4.10572 13.3716 4.05 13.1879 4.05 13C4.05 12.748 4.15009 12.5064 4.32825 12.3282C4.50641 12.1501 4.74804 12.05 5 12.05C5.18789 12.05 5.37156 12.1057 5.52779 12.2101C5.68402 12.3145 5.80578 12.4629 5.87769 12.6365C5.94959 12.81 5.9684 13.0011 5.93175 13.1853C5.89509 13.3696 5.80461 13.5389 5.67175 13.6718C5.53889 13.8046 5.36962 13.8951 5.18534 13.9317C5.00105 13.9684 4.81004 13.9496 4.63645 13.8777C4.46286 13.8058 4.31449 13.684 4.2101 13.5278Z"
                                                    fill="black" stroke="white" stroke-width="0.1" />
                                            </svg>
                    Fund Transfer
                </button>
            </p>
            <div id="cash_payment2_${newItemNumber}" class="accordion-collapse collapse"
                aria-labelledby="fundTransferHeading${newItemNumber}" data-bs-parent="#fundAccordion"
                style="border-top: 1px solid #dee2e6 !important;">
                <div class="accordion-body card card-body border-0">
                    <form id="FundTransferForm${newItemNumber}" class="content needs-validation p-0 border-0 px-1" novalidate enctype="multipart/form-data">
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="amount${newItemNumber}">Amount</label>
                            <input type="number" class="form-control amount" placeholder="Enter Amount" name="amount" required />
                            <div class="invalid-feedback">
                                Amount is required
                            </div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="transfer_date${newItemNumber}">Transfer Date</label>
                            <input type="date" class="form-control" id="transfer_date${newItemNumber}"
                                placeholder="dd/mm/yyyy" name="transfer_date" required />
                            <div class="invalid-feedback">
                                Transfer Date is required
                            </div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="transfer_reference_number${newItemNumber}">Transfer Reference Number</label>
                            <input type="number" class="form-control"
                                id="transfer_reference_number${newItemNumber}"
                                placeholder="Enter Transfer Reference Number"
                                name="transfer_reference_number" required />
                            <div class="invalid-feedback">
                                Transfer Reference Number is required
                            </div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="screenshot" class="mb-1">Upload Transfer
                                Screenshot</label>
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
                                        <label for="screenshot">Click to upload</label>
                                    </div>
                                    <input type="file" id="screenshot"
                                        class="form-control position-absolute screenshot-input"
                                        name="screenshot" multiple style="opacity: 0;" />
                                    <div class="invalid-feedback">Upload Transfer Screenshot
                                        is
                                        required</div>
                                    <ul id="file-preview"
                                        class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="discount${newItemNumber}">Discount <span
                                    style="font-size: 12px !important; color:#868686">Max
                                    Discounts:
                                    5% (7 Days), 3% (30 Days)</span></label>
                            <input type="number" class="form-control discount" placeholder="3%"
                                name="discount" id="discount${newItemNumber}" required />
                            <div class="invalid-feedback">
                                Discount is required
                            </div>
                        </div>

                        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                            style="border-radius: 8px;">
                            <div class="col-12 d-flex flex-column py-4 text-center">
                                <p class="gray-small-title mb-1"
                                    style="color: #595959;  font-weight: 500">Total Amount</p>
                                <p class="black-large-text mb-1 total-amount" style="color:#CC0000">Rs. 0.00</p>
                            </div>
                        </div>
                        <div class="d-flex w-100 justify-content-end align-items-center">
                            <button class="styled-button-red px-5 submit-fund-transfer" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    $('#fundAccordion').append(newAccordionItem);

    $(`#fundAccordionItem${newItemNumber} .amount, #fundAccordionItem${newItemNumber} .discount`).on('input', function () {
        calculateTotalAmount(`#fundAccordionItem${newItemNumber}`);
    });

    calculateTotalAmount(`#fundAccordionItem${newItemNumber}`);
});

function appendPaymentSummery(paymentType, paid_amount, discount) {
    $('#payment-summery').append(`
        <div class="d-flex flex-column mb-2">
            <label class="form-check-label d-flex flex-column mb-2 pb-2"
                style="border-bottom: solid 1px #EAECF0">
                <span class="label-value-title">${paymentType}</span>
            </label>
            <label class="form-check-label d-flex flex-column">
                <div class="d-flex flex-row mb-1">
                    <span class="label-name">Expect to pay :</span>
                    <span class="label-value">Rs. ${parseFloat(paid_amount).toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                </div>
                <div class="d-flex flex-row mb-3">
                    <span class="label-name">Discount : </span>
                    <span class="label-value">${discount}%</span>
                </div>
            </label>
        </div>
    `);

    let currentPaymentAmount = parseFloat($('#payment-amount').text().replace(/,/g, '')) || 0;
    let updatedAmount = currentPaymentAmount + parseFloat(paid_amount);
    $('#payment-amount').text(updatedAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
}

$(document).on('submit', '[id^="FundTransferForm"]', function (e) {
    e.preventDefault();
    preloader.style.display = 'flex';
    let form = $(this); 
    let formData = new FormData(form[0]);  

    let batchId = $('#payment_batch_id').val();
    formData.append('payment_batch_id', batchId);
    console.log('FormData contents:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }

    $.ajax({
        url: '{{ url('adm/add-fund-transfer') }}/{{$invoice_details->id}}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            preloader.style.display = 'none';
            console.log('Saved successfully:', response);
            toastr.success(response.message);
            form.trigger('reset');

            $('#payment_batch_id').val(response.payment_batch_id);

            let paymentType = 'Fund Transfer';
            let paid_amount = response.amount || 0;
            let discount = response.discount || 0;

            appendPaymentSummery(paymentType,paid_amount,discount);

        },
        error: function(xhr, status, error) {
            preloader.style.display = 'none';
            console.error('Error saving:', error);
            
            let errorMessage = 'An unexpected error occurred';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.responseJSON.error) {
                    errorMessage += ' - ' + xhr.responseJSON.error;
                }
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }

            toastr.error(errorMessage);
        }
    });
});



function calculateTotalAmount2(accordionId) {
    let amount = parseFloat($(accordionId + ' .amount').val()) || 0;
    let discount = parseFloat($(accordionId + ' .discount').val()) || 0;

    let totalAmount = amount - (amount * discount / 100);
    totalAmount = totalAmount.toFixed(2);

    $(accordionId + ' .total-amount').text('Rs. ' + totalAmount);
}


$('#addChequePayment').on('click', function () {

    const uniqueId = 'cheque_payment_' + new Date().getTime(); 

    const newAccordionItem = `
        <div id="chequeAccordionItem${uniqueId}" class="accordion-item shadow-border mb-3" style="border-radius: 8px;">
            <p class="accordion-header" id="chequePaymentHeading${uniqueId}">
                <button class="accordion-button d-flex justify-content-between" type="button"
                    data-bs-toggle="collapse" data-bs-target="#cheque_payment1_${uniqueId}"
                    aria-expanded="false" aria-controls="cheque_payment1_${uniqueId}">
                    <svg class="me-2" width="23" height="18" viewBox="0 0 23 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M5.875 11.25H9.25M5.875 6.75H17.125M1.375 1.125H21.625V14.625C21.625 15.2217 21.3879 15.794 20.966 16.216C20.544 16.6379 19.9717 16.875 19.375 16.875H3.625C3.02826 16.875 2.45597 16.6379 2.03401 16.216C1.61205 15.794 1.375 15.2217 1.375 14.625V1.125Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                    Cheque Payment
                </button>
            </p>
            <div id="cheque_payment1_${uniqueId}" class="accordion-collapse collapse"
                aria-labelledby="chequePaymentHeading${uniqueId}" data-bs-parent="#paymentAccordion"
                style="border-top: 1px solid #dee2e6 !important;">
                <div class="accordion-body card card-body border-0">
                    <form id="ChequePaymentForm_${uniqueId}" class="content needs-validation p-0 border-0 px-1" novalidate>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="cheque_number_${uniqueId}">Cheque Number</label>
                            <input type="number" class="form-control" id="cheque_number_${uniqueId}"
                                placeholder="Enter Cheque Number" name="cheque_number" required />
                            <div class="invalid-feedback">Cheque Number is required</div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="cheque_date_${uniqueId}">Cheque Date</label>
                            <input type="date" class="form-control" id="cheque_date_${uniqueId}"
                                placeholder="dd/mm/yyyy" name="cheque_date" required />
                            <div class="invalid-feedback">Cheque Date is required</div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="cheque_amount_${uniqueId}">Cheque Amount</label>
                            <input type="number" class="form-control amount" id="cheque_amount_${uniqueId}"
                                placeholder="Enter Cheque Amount" name="cheque_amount" required />
                            <div class="invalid-feedback">Cheque Amount is required</div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="bank_name_${uniqueId}">Bank Name</label>
                            <select class="form-select form-control" id="bank_name_${uniqueId}" name="bank_name">
                                <option selected value="0">Bank Of Ceylon</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <div class="invalid-feedback">Bank Name is required</div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="branch_name_${uniqueId}">Branch Name</label>
                            <input type="text" class="form-control" id="branch_name_${uniqueId}"
                                placeholder="Enter Branch Name" name="branch_name" required />
                            <div class="invalid-feedback">Branch Name is required</div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="discount_${uniqueId}">Discount <span style="font-size: 12px !important; color:#868686">Max
                                Discounts: 5% (7 Days), 3% (30 Days)</span></label>
                            <input type="number" class="form-control discount" placeholder="3%" name="discount" id="discount_${uniqueId}" required />
                            <div class="invalid-feedback">Discount is required</div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <div class="form-check d-flex flex-row align-items-center">
                                <input class="form-check-input m-0 me-2 ms-0" type="checkbox" name="post_dated" id="date_cheque_tick_${uniqueId}"
                                    style="margin-left: 0px !important;">
                                <label class="form-check-label" for="date_cheque_tick_${uniqueId}">
                                    Post - Dated Cheque
                                </label>
                            </div>
                        </div>
                        <div class="input-group-collection-inner d-flex flex-column mb-3">
                            <label for="cheque_image_${uniqueId}" class="mb-1">Upload Cheque image</label>
                            <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                                style="border-radius: 8px;">
                                <div class="col-12 d-flex flex-column pt-4 pb-3 text-center position-relative justify-content-center align-items-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
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
                                        <label for="screenshot_${uniqueId}">Click to upload</label>
                                    </div>
                                    <input type="file" id="cheque_image_${uniqueId}"
                                        class="form-control position-absolute screenshot-input"
                                        name="cheque_image" multiple style="opacity: 0;" />
                                    <div class="invalid-feedback">Upload Transfer Screenshot is required</div>
                                    <ul id="file-preview-cheque_${uniqueId}" class="mt-1 d-flex flex-column text-start ps-0 mb-0"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                            style=" border-radius: 8px;">
                            <div class="col-12 d-flex flex-column py-4 text-center">
                                <p class="gray-small-title mb-1" style="color: #595959; font-weight: 500">Total Amount</p>
                                <p class="black-large-text mb-1 total-amount" style="color:#CC0000">Rs. 0</p>
                            </div>
                        </div>
                        <div class="d-flex w-100 justify-content-end align-items-center">
                            <button class="styled-button-red px-5" style="font-size: 15px; min-height: 42px"
                                type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    $('#chequeAccordion').append(newAccordionItem);

    $(`#chequeAccordionItem${uniqueId} .amount, #chequeAccordionItem${uniqueId} .discount`).on('input', function () {
        calculateTotalAmount2(`#chequeAccordionItem${uniqueId}`);
    });

    calculateTotalAmount2(`#chequeAccordionItem${uniqueId}`);
});


$(document).on('submit', '[id^="ChequePaymentForm"]', function (e) {
    e.preventDefault();
    preloader.style.display = 'flex';
    let form = $(this); 
    let formData = new FormData(form[0]);  
    console.log(formData);

    let batchId = $('#payment_batch_id').val();
    formData.append('payment_batch_id', batchId);

    $.ajax({
        url: '{{ url('adm/add-cheque-payment') }}/{{$invoice_details->id}}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            preloader.style.display = 'none';
            console.log('Saved successfully:', response);
            toastr.success(response.message);
            form.trigger('reset');

            $('#payment_batch_id').val(response.payment_batch_id);

            let paymentType = 'Cheque Payment';
            let paid_amount = response.amount || 0;
            let discount = response.discount || 0;

            appendPaymentSummery(paymentType,paid_amount,discount);

        },
        error: function(xhr, status, error) {
            preloader.style.display = 'none';
            console.error('Error saving:', error);
            
            let errorMessage = 'An unexpected error occurred';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.responseJSON.error) {
                    errorMessage += ' - ' + xhr.responseJSON.error;
                }
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }

            toastr.error(errorMessage);
        }
    });
});


function calculateTotalAmount3(accordionId) {
    let amount = parseFloat($(accordionId + ' .amount').val()) || 0;
    let discount = parseFloat($(accordionId + ' .discount').val()) || 0;

    let totalAmount = amount - (amount * discount / 100);
    totalAmount = totalAmount.toFixed(2);

    $(accordionId + ' .total-amount').text('Rs. ' + totalAmount);
}


$('#addCardPayment').on('click', function () {

const uniqueId = 'card_' + new Date().getTime(); 

const newAccordionItem = `
    <div id="cardAccordionItem${uniqueId}" class="accordion-item shadow-border mb-3" style="border-radius: 8px;">
                                    <p class="accordion-header" id="cardPaymentHeading${uniqueId}">
                                        <button class="accordion-button d-flex justify-content-between" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#card_payment1${uniqueId}"
                                            aria-expanded="false" aria-controls="card_payment1${uniqueId}">
                                            <svg class="me-2" width="22" height="18" viewBox="0 0 22 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21 9C21 5.229 21 3.343 19.828 2.172C18.656 1.001 16.771 1 13 1H9C5.229 1 3.343 1 2.172 2.172C1.001 3.344 1 5.229 1 9C1 12.771 1 14.657 2.172 15.828C3.344 16.999 5.229 17 9 17H13C16.771 17 18.657 17 19.828 15.828C20.482 15.175 20.771 14.3 20.898 13M9 13H5M13 13H11.5M1 7H6M21 7H10"
                                                    stroke="black" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>

                                            Card Payment
                                        </button>
                                    </p>
                                    <div id="card_payment1${uniqueId}" class="accordion-collapse collapse"
                                        aria-labelledby="cardPaymentHeading${uniqueId}" data-bs-parent="#paymentAccordion"
                                        style="border-top: 1px solid #dee2e6 !important;">
                                        <div class="accordion-body card card-body border-0">
                                            <div class="card card-body border-0">
                                                <form id="CardPaymentForm${uniqueId}"
                                                    class="content needs-validation p-0 border-0 px-1" novalidate>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_amount${uniqueId}">Amount</label>
                                                        <input type="number" class="form-control amount" id="card_amount${uniqueId}"
                                                            placeholder="Enter Amount" name="card_amount" required />
                                                        <div class="invalid-feedback">
                                                            Amount is required
                                                        </div>
                                                    </div>
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_transfer_date${uniqueId}">Transfer Date</label>
                                                        <input type="date" class="form-control" id="card_transfer_date${uniqueId}"
                                                            placeholder="dd/mm/yyyy" name="card_transfer_date"
                                                            required />
                                                        <div class="invalid-feedback">
                                                            Transfer Date is required
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_discount${uniqueId}">Discount <span
                                                                style="font-size: 12px !important; color:#868686">Max
                                                                Discounts:
                                                                5% (7 Days), 3% (30 Days)</span></label>
                                                        <input type="number" class="form-control discount" placeholder="3%"
                                                            name="card_discount" id="card_discount${uniqueId}" required />
                                                        <div class="invalid-feedback">
                                                            Discount is required
                                                        </div>
                                                    </div>

                                                    <div class="input-group-collection-inner d-flex flex-column mb-3">
                                                        <label for="card_screenshot${uniqueId}" class="mb-1">Upload Transfer
                                                            Screenshot</label>
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
                                                                    <label for="card_screenshot">Click to upload</label>
                                                                </div>
                                                                <input type="file" id="card_screenshot${uniqueId}"
                                                                    class="form-control position-absolute screenshot-input"
                                                                    name="card_screenshot" multiple
                                                                    style="opacity: 0;" />
                                                                <div class="invalid-feedback">Upload Transfer Screenshot
                                                                    is
                                                                    required</div>
                                                                <ul id="file-preview-card-payment"
                                                                    class="mt-1 d-flex flex-column text-start ps-0 mb-0">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3 shadow-border"
                                                        style=" border-radius: 8px;">
                                                        <div class="col-12 d-flex flex-column py-4 text-center">
                                                            <p class="gray-small-title mb-1"
                                                                style="color: #595959;  font-weight: 500">Total Amount
                                                            </p>
                                                            <p class="black-large-text mb-1 total-amount" style="color:#CC0000">Rs.
                                                                0</p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex w-100 justify-content-end align-items-center">
                                                        <button class="styled-button-red px-5"
                                                            style="font-size: 15px; min-height: 42px"
                                                            type="submit">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
`;

$('#cardAccordion').append(newAccordionItem);

$(`#cardAccordionItem${uniqueId} .amount, #cardAccordionItem${uniqueId} .discount`).on('input', function () {
    calculateTotalAmount3(`#cardAccordionItem${uniqueId}`);
});

calculateTotalAmount3(`#cardAccordionItem${uniqueId}`);
});

$(document).on('submit', '[id^="CardPaymentForm"]', function (e) {
    e.preventDefault();
    preloader.style.display = 'flex';
    let form = $(this); 
    let formData = new FormData(form[0]);  

    let batchId = $('#payment_batch_id').val();
    formData.append('payment_batch_id', batchId);

    console.log(formData);
    $.ajax({
        url: '{{ url('adm/add-card-payment') }}/{{$invoice_details->id}}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            preloader.style.display = 'none';
            console.log('Saved successfully:', response);
            toastr.success(response.message);
            form.trigger('reset');

            $('#payment_batch_id').val(response.payment_batch_id);

            let paymentType = 'Card Payment';
            let paid_amount = response.amount || 0;
            let discount = response.discount || 0;

            appendPaymentSummery(paymentType,paid_amount,discount);
        },
        error: function(xhr, status, error) {
            preloader.style.display = 'none';
            console.error('Error saving:', error);
            
            let errorMessage = 'An unexpected error occurred';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.responseJSON.error) {
                    errorMessage += ' - ' + xhr.responseJSON.error;
                }
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }

            toastr.error(errorMessage);
        }

    });
});

$(document).on('change', '#temp_receipt', function () {
    if ($(this).is(':checked')) {
        $('.temp-receipt-reason').show();
        $('.customer-e-signature').hide();
    } else {
        $('.temp-receipt-reason').hide();
        $('.customer-e-signature').show();
    }
});
$(document).on('click', '#submit-payment', function(e) {
        e.preventDefault(); 
        preloader.style.display = 'flex';

        var isTempReceiptChecked = $('#temp_receipt').is(':checked');

        var formData = {
        temp_receipt: isTempReceiptChecked ? 1 : 0,
        adm_signature: $('#adm_signature_input').val(),
        customer_signature: $('#customer_signature_input').val(),
        reason_for_temp: $('#reason_for_temp').val(),
        payment_batch_id: $('#payment_batch_id').val()
        };
        console.log(formData);
        $.ajax({
            url: '{{ url('adm/save-invoice') }}/{{$invoice_details->id}}',
            method: 'POST',
            data: formData,
            success: function(response) {
                preloader.style.display = 'none';
                console.log('Saved successfully:', response);
                toastr.success(response.message);
                setTimeout(function() {
                    location.reload();
                }, 2000);

            },
            error: function(xhr, status, error) {
            preloader.style.display = 'none';
            console.error('Error saving:', error);
            
            let errorMessage = 'An unexpected error occurred';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.responseJSON.error) {
                    errorMessage += ' - ' + xhr.responseJSON.error;
                }
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }

            toastr.error(errorMessage);
        }
        });
    });
</script>

