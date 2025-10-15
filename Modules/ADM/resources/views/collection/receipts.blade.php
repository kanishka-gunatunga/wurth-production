@include('adm::layouts.header')
<?php
use App\Models\Customers;
?>
<div class="content px-0">
            <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
                <h3 class="page-title">All Receipts</h3>
              
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
                      
                        <div class="d-flex flex-column">
                           
                            <div class="table-container">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Customer ID</th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice-data">
                                    <?php
                                    foreach($receipts as $receipt){
                                    ?>
                                     <tr>
                                           <td>{{ $receipt->invoice->customer->customer_id ?? 'N/A' }}</td>
                                            <td>{{ $receipt->invoice->customer->name ?? 'N/A' }}</td>
                                            <td><a href="{{url('resend-receipt/'.$receipt->id.'')}}"><button class=" small-button" style="background-color: #000 !important;">Resend SMS</button></a></td>
                                        </tr>
                                      
                                    <?php } ?>  
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 d-flex justify-content-center laravel-pagination">
                                {{ $receipts->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                </div>

                
            </div>
           
        </div>
        @include('adm::layouts.footer')
