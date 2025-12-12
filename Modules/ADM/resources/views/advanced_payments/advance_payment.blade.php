@include('adm::layouts.header')
<?php

use App\Models\Customers;
?>
<div class="content px-0">
    <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
        <h3 class="page-title">Advance Payments</h3>
        <a href="{{url('adm/create-advanced-payment')}}" class="my-3 small-button">
            Create Advance Payments
        </a>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
            <div class="d-flex flex-column">
                <div class="mb-3 ">
                    <!-- <div class="container">
                        <div class="search-container">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchInput"
                                    placeholder="Search">
                            </div>
                            <div class="search-dropdown" id="searchDropdown">
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="table-container">
                    <table class="table dashboard-table">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Payment Amount</th>
                                <th scope="col">Mobile Number</th>
                                <th scope="col">E-signature</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr onclick="window.location='{{ route('advance_payment.details', $payment->id) }}'"
                                style="cursor: pointer;">
                                <td>{{ $payment->date }}</td>

                                <td>{{ $payment->customerData->name ?? 'N/A' }}</td>

                                <td>{{ number_format($payment->payment_amount, 2) }}</td>

                                <td>{{ $payment->mobile_no }}</td>

                                <td>
                                    @if($payment->customer_signature)
                                    <img src="{{ asset('uploads/adm/advanced_payments/signatures/' . $payment->customer_signature) }}"
                                        alt="Signature"
                                        style="width: 120px; height: 60px; object-fit: contain;">

                                    @else
                                    N/A
                                    @endif
                                </td>

                                <td>
                                    @php
                                    $status = strtolower(trim($payment->status));
                                    @endphp

                                    @if($status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                    @elseif($status === 'sorted')
                                    <span class="badge bg-info">Sorted</span>
                                    @elseif($status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    @else
                                    <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                </td>

                                <td>
                                    @if($payment->attachment)
                                    <a href="{{ route('advance_payment.download', $payment->id) }}"
                                        class="black-action-btn"
                                        style="text-decoration:none;">Download</a>
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-12 d-flex justify-content-center laravel-pagination">
                    {{ $payments->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>


    </div>

</div>
@include('adm::layouts.footer')
<script>
    $(document).ready(function() {
        $('#searchInput').on('input', function() {
            let query = $(this).val();

            $.ajax({
                url: "{{ url('adm/search-inquiries') }}",
                method: "GET",
                data: {
                    query: query
                },
                success: function(response) {
                    $('#invoice-data').html(response);
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        });

    });
</script>