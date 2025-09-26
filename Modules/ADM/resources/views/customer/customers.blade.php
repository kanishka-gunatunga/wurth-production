@include('adm::layouts.header')
<?php
use App\Models\Customers;
?>
<div class="content px-0">
            <div class="px-0 mb-3">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" style="width: 50vw;" id="pills-payment-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-payment" type="button" role="tab"
                            aria-controls="pills-payment" aria-selected="true">Customers</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" style="width: 50vw;" id="pills-system-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-system" type="button" role="tab" aria-controls="pills-system"
                            aria-selected="false">Temp.Customers</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-payment" role="tabpanel"
                        aria-labelledby="pills-payment-tab">
                        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3"
                            style="border: solid 1px #9D9D9D;">
                            <div class="col-12 d-flex flex-column py-2 text-center">
                                <p class="gray-small-title mb-1">Total Customers</p>
                                <p class="black-large-text mb-1">{{number_format($customers_count)}}</p>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="mb-3 ">
                                <div class="container">
                                    <div class="search-container">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="bi bi-search"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 search-input"
                                                id="searchInput1" placeholder="Search ...">
                                        </div>
                                        <div class="search-dropdown" id="searchDropdown1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Customer ID</th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Mobile Number</th>
                                        </tr>
                                    </thead>
                                    <tbody id="customer-data">
                                    <?php
                                     foreach($customers as $customer){
                                     ?>
                                        
                                    <tr>
                                            <td>{{$customer->customer_id ?? '-'}}</td>
                                            <td>{{$customer->name ?? '-'}}</td>
                                            <td>{{$customer->mobile_number ?? '-'}}</td>
                                     </tr>
                                    <?php } ?> 
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 d-flex justify-content-center laravel-pagination">
                                {{ $customers->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-system" role="tabpanel" aria-labelledby="pills-system-tab">
                        <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3"
                            style="border: solid 1px #9D9D9D;">
                            <div class="col-12 d-flex flex-column py-2 text-center">
                                <p class="gray-small-title mb-1">Total Temp. Customers</p>
                                <p class="black-large-text mb-1">{{number_format($temp_customers_count)}}</p>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="mb-3">
                                <div class="container">
                                    <div class="search-container mt-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="bi bi-search"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 search-input"
                                                id="searchInput2" placeholder="Search ..." >
                                        </div>
                                        <div class="search-dropdown" id="searchDropdown2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="table dashboard-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Customer ID</th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Mobile Number</th>
                                        </tr>
                                    </thead>
                                    <tbody id="temp-customer-data">
                                    <?php
                                     foreach($temp_customers as $temp_customer){
                                     ?>
                                        
                                    <tr>
                                            <td>{{$temp_customer->customer_id ?? '-'}}</td>
                                            <td>{{$temp_customer->name ?? '-'}}</td>
                                            <td>{{$temp_customer->mobile_number ?? '-'}}</td>
                                     </tr>
                                    <?php } ?> 
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 d-flex justify-content-center laravel-pagination">
                                {{ $temp_customers->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('adm::layouts.footer')
<script>
    $(document).ready(function() {
        $('#searchInput1').on('input', function() {
            let query = $(this).val();

            $.ajax({
                url: "{{ url('adm/search-customers') }}", 
                method: "GET",
                data: { query: query },
                success: function(response) {
                    $('#customer-data').html(response);
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        });
        $('#searchInput2').on('input', function() {
            let query = $(this).val();

            $.ajax({
                url: "{{ url('adm/search-temp-customers') }}", 
                method: "GET",
                data: { query: query },
                success: function(response) {
                    $('#temp-customer-data').html(response);
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        });
    });
</script>