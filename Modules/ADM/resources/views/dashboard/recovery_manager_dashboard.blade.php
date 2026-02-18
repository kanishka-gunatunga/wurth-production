@include('adm::layouts.header')
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .date-range {
        text-align: right;
        color: #7D8DA6;
        font-weight: 400;
        font-size: 18px;
        margin-bottom: 20px;
    }
</style>

<!-- body content -->
<div class="content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 class="page-title">Dashboard</h3>

        <div class="date-range" style="display: flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                <path
                    d="M10.8487 7.49219C10.7117 7.49219 10.5803 7.54662 10.4834 7.6435C10.3865 7.74038 10.3321 7.87178 10.3321 8.0088C10.3321 8.14581 10.3865 8.27721 10.4834 8.37409C10.5803 8.47098 10.7117 8.5254 10.8487 8.5254H13.9483C14.0853 8.5254 14.2167 8.47098 14.3136 8.37409C14.4105 8.27721 14.4649 8.14581 14.4649 8.0088C14.4649 7.87178 14.4105 7.74038 14.3136 7.6435C14.2167 7.54662 14.0853 7.49219 13.9483 7.49219H10.8487ZM9.81546 13.1749C9.81546 13.4489 9.7066 13.7117 9.51284 13.9055C9.31907 14.0992 9.05627 14.2081 8.78224 14.2081C8.50821 14.2081 8.24541 14.0992 8.05165 13.9055C7.85788 13.7117 7.74902 13.4489 7.74902 13.1749C7.74902 12.9009 7.85788 12.6381 8.05165 12.4443C8.24541 12.2505 8.50821 12.1417 8.78224 12.1417C9.05627 12.1417 9.31907 12.2505 9.51284 12.4443C9.7066 12.6381 9.81546 12.9009 9.81546 13.1749ZM9.81546 16.7911C9.81546 17.0652 9.7066 17.328 9.51284 17.5217C9.31907 17.7155 9.05627 17.8244 8.78224 17.8244C8.50821 17.8244 8.24541 17.7155 8.05165 17.5217C7.85788 17.328 7.74902 17.0652 7.74902 16.7911C7.74902 16.5171 7.85788 16.2543 8.05165 16.0605C8.24541 15.8668 8.50821 15.7579 8.78224 15.7579C9.05627 15.7579 9.31907 15.8668 9.51284 16.0605C9.7066 16.2543 9.81546 16.5171 9.81546 16.7911ZM12.3985 14.2081C12.6725 14.2081 12.9353 14.0992 13.1291 13.9055C13.3229 13.7117 13.4317 13.4489 13.4317 13.1749C13.4317 12.9009 13.3229 12.6381 13.1291 12.4443C12.9353 12.2505 12.6725 12.1417 12.3985 12.1417C12.1245 12.1417 11.8617 12.2505 11.6679 12.4443C11.4741 12.6381 11.3653 12.9009 11.3653 13.1749C11.3653 13.4489 11.4741 13.7117 11.6679 13.9055C11.8617 14.0992 12.1245 14.2081 12.3985 14.2081ZM13.4317 16.7911C13.4317 17.0652 13.3229 17.328 13.1291 17.5217C12.9353 17.7155 12.6725 17.8244 12.3985 17.8244C12.1245 17.8244 11.8617 17.7155 11.6679 17.5217C11.4741 17.328 11.3653 17.0652 11.3653 16.7911C11.3653 16.5171 11.4741 16.2543 11.6679 16.0605C11.8617 15.8668 12.1245 15.7579 12.3985 15.7579C12.6725 15.7579 12.9353 15.8668 13.1291 16.0605C13.3229 16.2543 13.4317 16.5171 13.4317 16.7911ZM16.0148 14.2081C16.2888 14.2081 16.5516 14.0992 16.7454 13.9055C16.9391 13.7117 17.048 13.4489 17.048 13.1749C17.048 12.9009 16.9391 12.6381 16.7454 12.4443C16.5516 12.2505 16.2888 12.1417 16.0148 12.1417C15.7407 12.1417 15.4779 12.2505 15.2842 12.4443C15.0904 12.6381 14.9815 12.9009 14.9815 13.1749C14.9815 13.4489 15.0904 13.7117 15.2842 13.9055C15.4779 14.0992 15.7407 14.2081 16.0148 14.2081Z"
                    fill="#7D8DA6" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.26567 3.61719C8.40269 3.61719 8.53409 3.67162 8.63097 3.7685C8.72786 3.86538 8.78228 3.99678 8.78228 4.1338V5.16701H16.0148V4.1338C16.0148 3.99678 16.0692 3.86538 16.1661 3.7685C16.263 3.67162 16.3944 3.61719 16.5314 3.61719C16.6684 3.61719 16.7998 3.67162 16.8967 3.7685C16.9936 3.86538 17.048 3.99678 17.048 4.1338V5.17011C17.3008 5.17218 17.5261 5.18113 17.7237 5.19698C18.1009 5.22797 18.4315 5.29307 18.7373 5.44805C19.2232 5.69591 19.6182 6.09123 19.8656 6.57735C20.0216 6.88319 20.0867 7.21382 20.1177 7.58991C20.1477 7.9567 20.1477 8.40822 20.1477 8.96822V16.8641C20.1477 17.4241 20.1477 17.8766 20.1177 18.2413C20.0867 18.6185 20.0216 18.9491 19.8656 19.2549C19.618 19.7407 19.2231 20.1356 18.7373 20.3832C18.4315 20.5392 18.1009 20.6043 17.7248 20.6353C17.358 20.6653 16.9065 20.6653 16.3475 20.6653H8.45062C7.89062 20.6653 7.43807 20.6653 7.07334 20.6353C6.69622 20.6043 6.36559 20.5392 6.05976 20.3832C5.57363 20.1358 5.17831 19.7408 4.93045 19.2549C4.77547 18.9491 4.71037 18.6185 4.67938 18.2424C4.64941 17.8756 4.64941 17.423 4.64941 16.863V8.96925C4.64941 8.47848 4.64941 8.07242 4.67008 7.73249L4.67938 7.59197C4.71037 7.21485 4.77547 6.88422 4.93045 6.57839C5.17814 6.09211 5.57348 5.69677 6.05976 5.44908C6.36559 5.2941 6.69622 5.22901 7.07231 5.19801C7.27137 5.18217 7.49696 5.17321 7.74907 5.17115V4.1338C7.74907 3.99678 7.80349 3.86538 7.90038 3.7685C7.99726 3.67162 8.12866 3.61719 8.26567 3.61719ZM7.74907 6.71684V6.20333C7.55152 6.20496 7.35407 6.21289 7.15703 6.22709C6.845 6.25189 6.66522 6.29942 6.52884 6.36865C6.23696 6.51728 5.99968 6.75456 5.85105 7.04644C5.78182 7.18282 5.73429 7.3626 5.7095 7.67463C5.68263 7.9939 5.68263 8.40305 5.68263 8.98992V9.55819H19.1145V8.98992C19.1145 8.40305 19.1145 7.9939 19.0876 7.67463C19.0628 7.3626 19.0153 7.18282 18.946 7.04644C18.7974 6.75456 18.5601 6.51728 18.2683 6.36865C18.1319 6.29942 17.9521 6.25189 17.6401 6.22709C17.443 6.21289 17.2456 6.20496 17.048 6.20333V6.71684C17.048 6.85385 16.9936 6.98525 16.8967 7.08214C16.7998 7.17902 16.6684 7.23345 16.5314 7.23345C16.3944 7.23345 16.263 7.17902 16.1661 7.08214C16.0692 6.98525 16.0148 6.85385 16.0148 6.71684V6.20023H8.78228V6.71684C8.78228 6.85385 8.72786 6.98525 8.63097 7.08214C8.53409 7.17902 8.40269 7.23345 8.26567 7.23345C8.12866 7.23345 7.99726 7.17902 7.90038 7.08214C7.80349 6.98525 7.74907 6.85385 7.74907 6.71684ZM19.1145 10.5914H5.68263V16.8424C5.68263 17.4292 5.68263 17.8394 5.7095 18.1577C5.73429 18.4697 5.78182 18.6495 5.85105 18.7859C5.99968 19.0777 6.23696 19.315 6.52884 19.4636C6.66522 19.5329 6.845 19.5804 7.15703 19.6052C7.4763 19.6321 7.88545 19.6321 8.47232 19.6321H16.3248C16.9116 19.6321 17.3218 19.6321 17.6401 19.6052C17.9521 19.5804 18.1319 19.5329 18.2683 19.4636C18.5601 19.315 18.7974 19.0777 18.946 18.7859C19.0153 18.6495 19.0628 18.4697 19.0876 18.1577C19.1145 17.8394 19.1145 17.4292 19.1145 16.8424V10.5914Z"
                    fill="#7D8DA6" />
            </svg>
            <span>27/02/2025 - 27/03/2025</span>
        </div>
    </div>

    <!-- row 2 -->
    <div class="card-view card-box text-center d-flex flex-column justify-content-center align-items-center py-3 mb-3">
        <p class="gray-text mb-1">Cash on hand</p>
        <p class="red-title mb-0">Rs. 93,500.00</p>
    </div>

    <div class="card-view card-box text-center d-flex flex-column justify-content-center align-items-center py-3 mb-3">
        <p class="gray-text mb-1">Cheque on hand</p>
        <p class="red-title mb-0">Rs. 120,000.00 </p>
    </div>

    <div class="card-view card-box text-center d-flex flex-column justify-content-center align-items-center py-3 mb-3">
        <p class="gray-text mb-1">Todays cash deposits</p>
        <p class="red-title mb-0">Rs. 120,000.00 </p>
    </div>

    <div class="card-view card-box text-center d-flex flex-column justify-content-center align-items-center py-3 mb-3">
        <p class="gray-text mb-1">Todays cheque deposits</p>
        <p class="red-title mb-0">Rs. 120,000.00 </p>
    </div>

    <!-- row-3 -->
    <div class="card-view px-0 mb-3">
        <div class="d-flex flex-row justify-content-between align-items-center px-3 mb-2">
            <h4 class="black-title mb-0">Reminders</h4>
            <a href="/sales/reminders.html" class="card-link">View all</a>
        </div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item w-100" role="presentation">
                <button class="nav-link active" id="pills-payment-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-payment" type="button" role="tab" aria-controls="pills-payment"
                    aria-selected="true">Payment Reminders</button>
            </li>
            <li class="nav-item w-100" role="presentation">
                <button class="nav-link" id="pills-system-tab" data-bs-toggle="pill" data-bs-target="#pills-system"
                    type="button" role="tab" aria-controls="pills-system" aria-selected="false">System
                    Reminders</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-payment" role="tabpanel"
                aria-labelledby="pills-payment-tab">
                <!-- 1 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
                            <img src="{{ asset('adm_assets/assests/history-icon.svg') }}" alt="Logo"
                                class="img-fluid history-icon">
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
</div>


@include('adm::layouts.footer')
<script src="{{ asset('adm_assets/js/time-script.js') }}"></script>
<script src="{{ asset('adm_assets/js/calander.js') }}"></script>
