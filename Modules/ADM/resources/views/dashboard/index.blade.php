@include('adm::layouts.header')
<!-- body content -->
<div class="content">
    <div class="d-flex w-100 text-start  mb-3">
        <h3 class="page-title">Dashboard</h3>
    </div>
    <div class="card-view mb-3">
        <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center">
            <div class="col-12 col-sm-6 d-flex justify-content-center align-items-center my-3 my-sm-0">
                <div class="time-container">
                    <div class="time-box" id="hour1">1</div>
                    <div class="time-box" id="hour2">1</div>
                    <div class="separator">:</div>
                    <div class="time-box" id="minute1">3</div>
                    <div class="time-box" id="minute2">4</div>
                    <div class="separator">:</div>
                    <div class="time-box" id="second1">4</div>
                    <div class="time-box" id="second2">6</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 d-flex justify-content-center align-items-center">
                <div class="calendar">
                    <div class="calendar-header">
                        <h2 id="month-year"></h2>
                        <button id="prev-month"><i class="bi bi-chevron-left"></i></button>
                        <button id="next-month"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="calendar-body">
                        <div class="calendar-days">
                            <div class="days-title">Sun</div>
                            <div class="days-title">Mon</div>
                            <div class="days-title">Tue</div>
                            <div class="days-title">Wed</div>
                            <div class="days-title">Thu</div>
                            <div class="days-title">Fri</div>
                            <div class="days-title">Sat</div>
                        </div>
                        <div id="calendar-dates" class="calendar-dates"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row 2 -->
    <div class="d-flex flex-row mb-3">
        <div class="col-6 pe-2">
            <div
                class="card-view card-box text-center d-flex flex-column justify-content-center align-items-center py-3">
                <p class="gray-text mb-1">Daily Cash collected</p>
                <p class="red-title mb-0">Rs. 93,500.00</p>
            </div>
        </div>
        <div class="col-6  ps-2">
            <div
                class="card-view card-box text-center d-flex flex-column justify-content-center align-items-center py-3">
                <p class="gray-text mb-1">Today’s Cheque Deposit</p>
                <p class="red-title mb-0">Rs. 120,000.00 </p>
            </div>
        </div>
    </div>
    <!-- row-3 -->
    <div class="card-view px-0 mb-3">
        <div class="d-flex flex-row justify-content-between align-items-center px-3 mb-2">
            <h4 class="black-title mb-0">Reminders</h4>
            <a href="/sales/reminders.html" class="card-link">View all</a>
        </div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-payment-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-payment" type="button" role="tab" aria-controls="pills-payment"
                    aria-selected="true">Payment Reminders</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-system-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-system" type="button" role="tab" aria-controls="pills-system"
                    aria-selected="false">System Reminders</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-payment" role="tabpanel"
                aria-labelledby="pills-payment-tab">
                <!-- 1 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">Customer Payment Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
                <!-- 2 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">Customer Payment Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
                <!-- 3 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">Customer Payment Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
                <!-- 4 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">Customer Payment Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-system" role="tabpanel" aria-labelledby="pills-system-tab">
                <!-- 1 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">System Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
                <!-- 2 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">System Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
                <!-- 3 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">System Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
                <!-- 4 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo" class="img-fluid history-icon">
                        </span>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">System Reminder</p>
                        <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                    </div>
                    <div class="col-2">
                        <span class="reminder-time">Just now</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row-4 -->
    <div class="card-view px-0 mb-3">
        <div class="d-flex flex-row justify-content-between align-items-center px-3 mb-2">
            <h4 class="black-title mb-0">Recently Allocated Customers</h4>
        </div>
        <table class="table dashboard-table">
            <thead>
                <tr>
                    <th scope="col">Customer ID</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Mobile No.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>54235625</td>
                    <td>H.K Perera</td>
                    <td>075 5423514</td>
                </tr>
                <tr>
                    <td>54235625</td>
                    <td>H.K Perera</td>
                    <td>075 5423514</td>
                </tr>
                <tr>
                    <td>54235625</td>
                    <td>H.K Perera</td>
                    <td>075 5423514</td>
                </tr>
                <tr>
                    <td>54235625</td>
                    <td>H.K Perera</td>
                    <td>075 5423514</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@include('adm::layouts.footer')
<script src="{{ asset('adm_assets/js/time-script.js') }}"></script>
<script src="{{ asset('adm_assets/js/calander.js') }}"></script>