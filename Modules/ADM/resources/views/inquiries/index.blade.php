@include('adm::layouts.header')
<?php

use App\Models\Customers;
?>
<div class="content px-0">
    <div class="d-flex flex-row px-4 justify-content-between align-items-center w-100 text-start  mb-3">
        <h3 class="page-title">Inquiries</h3>
        <a href="{{url('adm/create-inquiry')}}" class="my-3 small-button">
            Create Inquiry
        </a>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
            <div class="d-flex flex-row px-4 justify-content-center align-items-center w-100 text-start mb-3"
                style="border: solid 1px #9D9D9D;">
                <div class="col-6 d-flex flex-column py-2 text-center">
                    <p class="gray-small-title mb-1">Total Inquiries</p>
                    <p class="black-large-text mb-1">{{number_format($inquiries->count())}}</p>
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
                                <input type="text" class="form-control border-start-0" id="searchInput"
                                    placeholder="Search">
                            </div>
                            <div class="search-dropdown" id="searchDropdown">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table dashboard-table">
                        <thead>
                            <tr>
                                <th scope="col">Inquiry Type</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Invoice No</th>
                                <th scope="col">Attachment</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-data">

                            @foreach($inquiries as $inquiry)
                            <tr>

                                <td>{{ $inquiry->type ?? 'N/A' }}</td>
                                <td>{{ $inquiry->customer ?? 'N/A' }}</td>
                                <td>{{ $inquiry->invoice?->invoice_or_cheque_no ?? 'N/A' }}</td>
                                <td>{{ $inquiry->attachement ?? 'N/A' }}</td>
                                <td>
                                    @php
                                    $status = strtolower(trim($inquiry->status));
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
                                <td><button class="black-action-btn" data-href="">
                                        View
                                    </button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-12 d-flex justify-content-center laravel-pagination">
                    {{ $inquiries->links('pagination::bootstrap-5') }}
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