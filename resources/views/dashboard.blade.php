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
                <h3 class="stat-number">LKR 12,500.00</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">On-Hand Collection</p>
                <h3 class="stat-number">LKR 34,500.00</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">Current Month Collection</p>
                <h3 class="stat-number">LKR 92,500.00</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">Current Month Cheque Collection</p>
                <h3 class="stat-number">LKR 120,500.00</h3>
              </div>
            </div>

            <div class="col-md-3">
              <div class="stat-card p-3">
                <p class="stat-label">Cash on Hand</p>
                <h3 class="stat-number">LKR 13,500.00</h3>
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
                      <tr>
                        <td>12 Dec 2024</td>
                        <td>H.K Perera</td>
                        <td>254565214</td>
                        <td>
                          <button class="btn unlock-btn">Unlock</button>
                        </td>
                      </tr>
                      <tr>
                        <td>12 Dec 2024</td>
                        <td>Pasan Randula</td>
                        <td>254565214</td>
                        <td>
                          <button class="btn unlock-btn">Unlock</button>
                        </td>
                      </tr>
                      <tr>
                        <td>12 Dec 2024</td>
                        <td>H.K Perera</td>
                        <td>254565214</td>
                        <td>
                          <button class="btn unlock-btn">Unlock</button>
                        </td>
                      </tr>
                      <tr>
                        <td>12 Dec 2024</td>
                        <td>Pasan Randula</td>
                        <td>254565214</td>
                        <td>
                          <button class="btn unlock-btn">Unlock</button>
                        </td>
                      </tr>
                      <tr>
                        <td>12 Dec 2024</td>
                        <td>H.K Perera</td>
                        <td>254565214</td>
                        <td>
                          <button class="btn unlock-btn">Unlock</button>
                        </td>
                      </tr>
                      <tr>
                        <td>12 Dec 2024</td>
                        <td>Pasan Randula</td>
                        <td>254565214</td>
                        <td>
                          <button class="btn unlock-btn">Unlock</button>
                        </td>
                      </tr>
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
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> User level
                      <span class="highlight-red">Finance Manager</span>
                      change access controls
                    </div>
                    <div class="activity-time">30 min ago</div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> Update
                      <span class="highlight-red">1256543</span> user's email
                      address & phone number
                    </div>
                    <div class="activity-time">35 min ago</div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> Update
                      <span class="highlight-red">1256543</span> user's email
                      address & phone number
                    </div>
                    <div class="activity-time">35 min ago</div>
                  </div>

                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> Update
                      <span class="highlight-red">1256543</span> user's email
                      address & phone number
                    </div>
                    <div class="activity-time">35 min ago</div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> Update
                      <span class="highlight-red">1256543</span> user's email
                      address & phone number
                    </div>
                    <div class="activity-time">35 min ago</div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> Update
                      <span class="highlight-red">1256543</span> user's email
                      address & phone number
                    </div>
                    <div class="activity-time">35 min ago</div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> Update
                      <span class="highlight-red">1256543</span> user's email
                      address & phone number
                    </div>
                    <div class="activity-time">35 min ago</div>
                  </div>
                  <div class="activity-item">
                    <div class="activity-text">
                      <strong>25651487 -</strong> Update
                      <span class="highlight-red">1256543</span> user's email
                      address & phone number
                    </div>
                    <div class="activity-time">35 min ago</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    
@include('layouts.footer2')

   