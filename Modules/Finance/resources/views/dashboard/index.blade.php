@include('finance::layouts.header')

<style>
    .stat-card {
        text-align: center;
        /* centers EVERYTHING inside */
    }

    .stat-label {
        text-align: center;
    }

    .stat-extra {
        font-family: Poppins;
        font-weight: 600;
        font-style: normal;
        /* Semibold handled by weight */
        font-size: 10px;
        line-height: 100%;
        letter-spacing: 0;
        color: #8a8a8a;
        display: block;
        margin-top: 4px;
        text-align: center;
    }
</style>

<div class="main-wrapper">
    <h1 class="header-title">Dashboard</h1>

    <div class="dashboard-main-container">
        <div class="row gx-4 gy-0 dashboard-stats">

            <!-- FIRST ROW (1 CARD ONLY) -->
            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">Collection Target</p>
                    <h3 class="stat-number">LKR 545,500</h3>
                </div>
            </div>

            <!-- Force row break -->
            <div class="w-100"></div>

            <!-- SECOND ROW (4 CARDS) -->
            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">Verified deposit</p>
                    <h3 class="stat-number">LKR 1,100,000</h3>
                    <small class="stat-extra">342 Receipts</small>
                </div>
            </div>

            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">Pending deposit Verification</p>
                    <h3 class="stat-number">LKR 560,000</h3>
                    <small class="stat-extra">342 Receipts</small>
                </div>
            </div>

            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">On hand cash</p>
                    <h3 class="stat-number">LKR 389,500</h3>
                    <small class="stat-extra">342 Receipts</small>
                </div>
            </div>

            <div class="col-md-2">
                <div class="stat-card p-3">
                    <p class="stat-label">On hand cheques</p>
                    <h3 class="stat-number">LKR 143,000</h3>
                    <small class="stat-extra">342 Receipts</small>
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