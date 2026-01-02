@include('adm::layouts.header')
<?php
use App\Models\Customers;
?>
<div class="content px-0">
            <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
                <h3 class="page-title">Temporary Receipts</h3>
              
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
                      
                        <div class="d-flex flex-column">
                           
                            <div class="table-container">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Collection ID</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Amount (LKR)</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice-data">
                                    @foreach ($collections as $collection)
                                    <tr>
                                        <td>{{ $collection['collection_id'] ?? 'N/A' }}</td>
                                        <td>{{ $collection['collection_date'] ?? 'N/A' }}</td>
                                        <td>{{ number_format($collection['total_collected_amount'], 2) }}</td>
                                        <td>
                                            <a href="{{ url('adm/view-temp-receipt/'.$collection['collection_id']) }}">
                                                <button class="small-button" style="background-color:#000">
                                                    Attach Customer Signature
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 d-flex justify-content-center laravel-pagination">
                                {{ $collections->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                </div>

                
            </div>
           
        </div>
        @include('adm::layouts.footer')
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