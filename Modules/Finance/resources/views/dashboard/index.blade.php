@include('finance::layouts.header')
<div class="main-wrapper">
    <h1 class="header-title">Dashboard</h1>

    <div class="dashboard-main-container">
        <div class="row gx-4 gy-3 dashboard-stats">


            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">Total No of Receipts</p>
                    <h3 class="stat-number">1245</h3>
                </div>
            </div>

            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">No of Invoices</p>
                    <h3 class="stat-number">45</h3>
                </div>
            </div>

            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">No of Pending</p>
                    <h3 class="stat-number">38</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card p-3">
                    <p class="stat-label">Invoiced Amount</p>
                    <h3 class="stat-number">Rs. 1,500,000</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card p-3">
                    <p class="stat-label">Collected Amount</p>
                    <h3 class="stat-number">Rs. 1,200,000</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card p-3">
                    <p class="stat-label">Monthly Deposit Amount</p>
                    <h3 class="stat-number">Rs. 1,100,000</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card p-3">
                    <p class="stat-label">Pending Deposit Amount</p>
                    <h3 class="stat-number">Rs. 100,000</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card p-3">
                    <p class="stat-label">Outstanding Amount</p>
                    <h3 class="stat-number">Rs. 300,000</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card p-3">
                    <p class="stat-label">Collection Target</p>
                    <h3 class="stat-number">Rs. 2,700,000</h3>
                </div>
            </div>

        </div>




        <div class="row mt-4">
            <!-- Locked Users Section -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="section-card">
                    <div class="card-body">
                        <h3 class="table-title">Recent Cash Deposits</h3>
                        <div class="table-responsive">
                            <table class="table unlock-column-table ">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ADM Name</th>
                                        <th>ADM Number</th>
                                        <th>Amount</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentCashDeposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit['date'] }}</td>
                                        <td>{{ $deposit['adm_name'] }}</td>
                                        <td>{{ $deposit['adm_number'] }}</td>
                                        <td>Rs. {{ $deposit['amount'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No recent cash deposits found</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                            </table>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-4">
                <div class="section-card">
                    <div class="card-body">
                        <h3 class="table-title">Recent Cheque Deposits</h3>
                        <div class="table-responsive">
                            <table class="table unlock-column-table ">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>ADM Name</th>
                                        <th>ADM Number</th>
                                        <th>Amount</th>
                                        <th>Payment Slip</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentChequeDeposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit['date'] }}</td>
                                        <td>{{ $deposit['adm_name'] }}</td>
                                        <td>{{ $deposit['adm_number'] }}</td>
                                        <td>Rs. {{ $deposit['amount'] }}</td>
                                        <td>{{ $deposit['payment_slip'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No recent cheque deposits found</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('finance::layouts.footer')