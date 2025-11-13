@include('layouts.dashboard-header')
<?php
use App\Models\UserDetails;
?>
<div class="main-wrapper">
        <h1 class="header-title">Dashboard</h1>

        <div class="dashboard-main-container">
          

          <div class="row gx-4 gy-3 dashboard-stats">
            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">Current Month Actual Deposits</p>
                <h3 class="stat-number">Rs. {{ number_format($currentMonthDeposits, 2) }}</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">On-Hand Collection</p>
                <h3 class="stat-number">Rs. {{ number_format($onHandCollections, 2) }}</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">Current Month Collection</p>
                <h3 class="stat-number">Rs. {{ number_format($monthCollections, 2) }}</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">Current Month Cheque Collection</p>
                <h3 class="stat-number">Rs. {{ number_format($monthChequeCollections, 2) }}</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">Cash on Hand</p>
                <h3 class="stat-number">Rs. {{ number_format($monthCashOnHand, 2) }}</h3>
              </div>
            </div>

        

          </div>

          <div class="row mt-4">
            <!-- Locked Users Section -->
            <div class="col-lg-8 col-md-12 mb-4">
              <div class="section-card locked-users-card">
                <h3 class="page-title">Locked Users</h3>
             

                <div class="table-responsive division-table-sub">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Date <i class="sort-icon">▼</i></th>
                        <th>Full Name</th>
                        <th>User ID</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($locked_users as $user)
                          <tr>
                              <td>{{ $user->updated_at->format('d M Y') }}</td>
                              <td>{{ $user->userDetails->name }}</td>
                              <td>{{ $user->id }}</td>
                              <td>
                                      <a href="{{ url('unlock-user/'.$user->id) }}"><button class="btn unlock-btn">Unlock</button></a>

                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="4" class="text-center">No locked users found.</td>
                          </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Recent Activities Section -->
            <div class="col-lg-4 col-md-12 mb-4">
              <div class="section-card activities-card">
                <h3 class="page-title">Recent Activities</h3>
                <div class="activities-list">
                @foreach ($logs as $log)
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong> {{ $log->userData->userDetails->user_id ?? '-' }} -</strong> 
                      <span class="highlight-red">{{ $log->userData->userDetails->name ?? '-' }}</span>
                      - {{ $log->changes }}
                    </div>
                    <div class="activity-time">30 min ago</div>
                  </div>
                 @endforeach  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    
@include('layouts.footer2')

   