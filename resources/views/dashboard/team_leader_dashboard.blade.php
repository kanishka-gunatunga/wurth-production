@include('layouts.dashboard-header')
<style>
    .date-range {
        text-align: right;
        color: #7D8DA6;
        font-weight: 400;
        font-size: 18px;
        margin-bottom: 20px;
    }

    /* Monthly Target Progress */
    .progress-section {
        background: white;
        padding: 25px;
        box-shadow: 1px 1px 5px 0px #0000001A;
        border-top: 1px solid #EEEEEE;
        border-radius: 8px;
        opacity: 1;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        flex-shrink: 0;
    }

    .section-icon svg {
        width: 100%;
        height: 100%;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #000;
        margin: 0;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .days-remaining {
        font-weight: 400;
        font-size: 14px;
        color: #666666;
    }

    .progress-values {
        font-weight: 400;
        font-size: 14px;
        color: #666666;
    }

    .progress-bar-container {
        height: 24px;
        background-color: #f5f5f5;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(180deg, #CC0000 0%, #FF3333 100%);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px;
        color: white;
        font-size: 13px;
        font-weight: 600;
        transition: width 0.3s ease;
    }

    .progress-status {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        font-weight: 500;
        color: #4CAF50;
    }

    /* Top Performers */
    .performers-section {
        background: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 1px 1px 5px 0px #0000001A;
        opacity: 1;
        border-top: 1px solid #EEEEEE;
    }

    .performer-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .performer-item:last-child {
        border-bottom: none;
    }

    .performer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .performer-rank {
        font-size: 20px;
    }

    .performer-details h4 {
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        font-weight: 500;
        color: #000;
        margin-bottom: 3px;
    }

    .performer-details p {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-size: 14px;
        color: #666666;
    }

    .performer-amount {
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #CC0000;
    }

    /* Pending Customers */
    .customers-section {
        background: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 1px 1px 5px 0px #0000001A;
        opacity: 1;
        border-top: 1px solid #EEEEEE;
    }

    .customers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        border: none;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-primary {
        background-color: #CC0000;
        border-radius: 4px;
        color: white;
        font-family: "Poppins", sans-serif;
        font-size: 12px;
    }

    .btn-secondary {
        background-color: #F5F5F5;
        border-radius: 4px;
        color: #666666;
        font-family: "Poppins", sans-serif;
        font-size: 12px;
    }

    .customer-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .customer-item:last-child {
        border-bottom: none;
    }

    .customer-info h4 {
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        font-weight: 500;
        color: #000;
        margin-bottom: 3px;
    }

    .customer-info p {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-size: 14px;
        color: #666666;
    }

    .customer-amount {
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #CC0000;
    }

    /* Notifications */
    .notifications-section {
        background: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 1px 1px 5px 0px #0000001A;
        opacity: 1;
        border-top: 1px solid #EEEEEE;
    }

    .notification-tabs {
        display: flex;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 20px;
    }

    .notification-tab {
        flex: 1;
        padding: 12px;
        text-align: center;
        color: #666666;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        font-size: 16px;
        font-weight: 500;
        font-family: "Poppins", sans-serif;
    }

    .notification-tab.active {
        background-color: #FFF5F6;
        color: #CC0000;
        border-bottom-color: #CC0000;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #f0fdf4;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #E8F5E9;
        font-size: 16px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
    }

    .notification-content p {
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        font-size: 14px;
        color: #000000;
        margin-bottom: 4px;
    }

    .notification-content span {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-size: 14px;
        color: #999999;
    }

    .notification-list {
        display: block;
    }

    .notification-list.d-none {
        display: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-wrapper {
            padding: 15px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .header-title {
            font-size: 20px;
        }

        .customers-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .action-buttons {
            width: 100%;
        }

        .btn {
            flex: 1;
        }

        .stat-card {
            padding: 15px;
        }

        .progress-section,
        .performers-section,
        .customers-section,
        .notifications-section {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {

        .performer-amount,
        .customer-amount {
            font-size: 13px;
        }

        .performer-info {
            gap: 8px;
        }

        .stat-value {
            font-size: 18px;
        }

        .notification-tabs {
            font-size: 13px;
        }
    }

    .stat-card {
        display: inline-flex;
        align-items: center;
        gap: 16px;
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        width: fit-content;
        max-width: 100%;
    }

    .stat-number {
        white-space: nowrap;
    }
</style>

<div class="main-wrapper">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="header-title">Dashboard</h1>

        <div class="date-range" style="display: flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                <path d="M10.8487 7.49219C10.7117 7.49219 10.5803 7.54662 10.4834 7.6435C10.3865 7.74038 10.3321 7.87178 10.3321 8.0088C10.3321 8.14581 10.3865 8.27721 10.4834 8.37409C10.5803 8.47098 10.7117 8.5254 10.8487 8.5254H13.9483C14.0853 8.5254 14.2167 8.47098 14.3136 8.37409C14.4105 8.27721 14.4649 8.14581 14.4649 8.0088C14.4649 7.87178 14.4105 7.74038 14.3136 7.6435C14.2167 7.54662 14.0853 7.49219 13.9483 7.49219H10.8487ZM9.81546 13.1749C9.81546 13.4489 9.7066 13.7117 9.51284 13.9055C9.31907 14.0992 9.05627 14.2081 8.78224 14.2081C8.50821 14.2081 8.24541 14.0992 8.05165 13.9055C7.85788 13.7117 7.74902 13.4489 7.74902 13.1749C7.74902 12.9009 7.85788 12.6381 8.05165 12.4443C8.24541 12.2505 8.50821 12.1417 8.78224 12.1417C9.05627 12.1417 9.31907 12.2505 9.51284 12.4443C9.7066 12.6381 9.81546 12.9009 9.81546 13.1749ZM9.81546 16.7911C9.81546 17.0652 9.7066 17.328 9.51284 17.5217C9.31907 17.7155 9.05627 17.8244 8.78224 17.8244C8.50821 17.8244 8.24541 17.7155 8.05165 17.5217C7.85788 17.328 7.74902 17.0652 7.74902 16.7911C7.74902 16.5171 7.85788 16.2543 8.05165 16.0605C8.24541 15.8668 8.50821 15.7579 8.78224 15.7579C9.05627 15.7579 9.31907 15.8668 9.51284 16.0605C9.7066 16.2543 9.81546 16.5171 9.81546 16.7911ZM12.3985 14.2081C12.6725 14.2081 12.9353 14.0992 13.1291 13.9055C13.3229 13.7117 13.4317 13.4489 13.4317 13.1749C13.4317 12.9009 13.3229 12.6381 13.1291 12.4443C12.9353 12.2505 12.6725 12.1417 12.3985 12.1417C12.1245 12.1417 11.8617 12.2505 11.6679 12.4443C11.4741 12.6381 11.3653 12.9009 11.3653 13.1749C11.3653 13.4489 11.4741 13.7117 11.6679 13.9055C11.8617 14.0992 12.1245 14.2081 12.3985 14.2081ZM13.4317 16.7911C13.4317 17.0652 13.3229 17.328 13.1291 17.5217C12.9353 17.7155 12.6725 17.8244 12.3985 17.8244C12.1245 17.8244 11.8617 17.7155 11.6679 17.5217C11.4741 17.328 11.3653 17.0652 11.3653 16.7911C11.3653 16.5171 11.4741 16.2543 11.6679 16.0605C11.8617 15.8668 12.1245 15.7579 12.3985 15.7579C12.6725 15.7579 12.9353 15.8668 13.1291 16.0605C13.3229 16.2543 13.4317 16.5171 13.4317 16.7911ZM16.0148 14.2081C16.2888 14.2081 16.5516 14.0992 16.7454 13.9055C16.9391 13.7117 17.048 13.4489 17.048 13.1749C17.048 12.9009 16.9391 12.6381 16.7454 12.4443C16.5516 12.2505 16.2888 12.1417 16.0148 12.1417C15.7407 12.1417 15.4779 12.2505 15.2842 12.4443C15.0904 12.6381 14.9815 12.9009 14.9815 13.1749C14.9815 13.4489 15.0904 13.7117 15.2842 13.9055C15.4779 14.0992 15.7407 14.2081 16.0148 14.2081Z" fill="#7D8DA6" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.26567 3.61719C8.40269 3.61719 8.53409 3.67162 8.63097 3.7685C8.72786 3.86538 8.78228 3.99678 8.78228 4.1338V5.16701H16.0148V4.1338C16.0148 3.99678 16.0692 3.86538 16.1661 3.7685C16.263 3.67162 16.3944 3.61719 16.5314 3.61719C16.6684 3.61719 16.7998 3.67162 16.8967 3.7685C16.9936 3.86538 17.048 3.99678 17.048 4.1338V5.17011C17.3008 5.17218 17.5261 5.18113 17.7237 5.19698C18.1009 5.22797 18.4315 5.29307 18.7373 5.44805C19.2232 5.69591 19.6182 6.09123 19.8656 6.57735C20.0216 6.88319 20.0867 7.21382 20.1177 7.58991C20.1477 7.9567 20.1477 8.40822 20.1477 8.96822V16.8641C20.1477 17.4241 20.1477 17.8766 20.1177 18.2413C20.0867 18.6185 20.0216 18.9491 19.8656 19.2549C19.618 19.7407 19.2231 20.1356 18.7373 20.3832C18.4315 20.5392 18.1009 20.6043 17.7248 20.6353C17.358 20.6653 16.9065 20.6653 16.3475 20.6653H8.45062C7.89062 20.6653 7.43807 20.6653 7.07334 20.6353C6.69622 20.6043 6.36559 20.5392 6.05976 20.3832C5.57363 20.1358 5.17831 19.7408 4.93045 19.2549C4.77547 18.9491 4.71037 18.6185 4.67938 18.2424C4.64941 17.8756 4.64941 17.423 4.64941 16.863V8.96925C4.64941 8.47848 4.64941 8.07242 4.67008 7.73249L4.67938 7.59197C4.71037 7.21485 4.77547 6.88422 4.93045 6.57839C5.17814 6.09211 5.57348 5.69677 6.05976 5.44908C6.36559 5.2941 6.69622 5.22901 7.07231 5.19801C7.27137 5.18217 7.49696 5.17321 7.74907 5.17115V4.1338C7.74907 3.99678 7.80349 3.86538 7.90038 3.7685C7.99726 3.67162 8.12866 3.61719 8.26567 3.61719ZM7.74907 6.71684V6.20333C7.55152 6.20496 7.35407 6.21289 7.15703 6.22709C6.845 6.25189 6.66522 6.29942 6.52884 6.36865C6.23696 6.51728 5.99968 6.75456 5.85105 7.04644C5.78182 7.18282 5.73429 7.3626 5.7095 7.67463C5.68263 7.9939 5.68263 8.40305 5.68263 8.98992V9.55819H19.1145V8.98992C19.1145 8.40305 19.1145 7.9939 19.0876 7.67463C19.0628 7.3626 19.0153 7.18282 18.946 7.04644C18.7974 6.75456 18.5601 6.51728 18.2683 6.36865C18.1319 6.29942 17.9521 6.25189 17.6401 6.22709C17.443 6.21289 17.2456 6.20496 17.048 6.20333V6.71684C17.048 6.85385 16.9936 6.98525 16.8967 7.08214C16.7998 7.17902 16.6684 7.23345 16.5314 7.23345C16.3944 7.23345 16.263 7.17902 16.1661 7.08214C16.0692 6.98525 16.0148 6.85385 16.0148 6.71684V6.20023H8.78228V6.71684C8.78228 6.85385 8.72786 6.98525 8.63097 7.08214C8.53409 7.17902 8.40269 7.23345 8.26567 7.23345C8.12866 7.23345 7.99726 7.17902 7.90038 7.08214C7.80349 6.98525 7.74907 6.85385 7.74907 6.71684ZM19.1145 10.5914H5.68263V16.8424C5.68263 17.4292 5.68263 17.8394 5.7095 18.1577C5.73429 18.4697 5.78182 18.6495 5.85105 18.7859C5.99968 19.0777 6.23696 19.315 6.52884 19.4636C6.66522 19.5329 6.845 19.5804 7.15703 19.6052C7.4763 19.6321 7.88545 19.6321 8.47232 19.6321H16.3248C16.9116 19.6321 17.3218 19.6321 17.6401 19.6052C17.9521 19.5804 18.1319 19.5329 18.2683 19.4636C18.5601 19.315 18.7974 19.0777 18.946 18.7859C19.0153 18.6495 19.0628 18.4697 19.0876 18.1577C19.1145 17.8394 19.1145 17.4292 19.1145 16.8424V10.5914Z" fill="#7D8DA6" />
            </svg>
            <span>27/02/2025 - 27/03/2025</span>
        </div>
    </div>

    <div class="dashboard-main-container">
        <!-- Stats Grid -->
        <div class="row gx-4 gy-3 dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Team Count</p>
                    <h3 class="stat-number">12 Members</h3>
                </div>

            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M21.3335 9.33398H29.3335V17.334" stroke="#CC0000" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M29.3332 9.33398L17.9998 20.6673L11.3332 14.0007L2.6665 22.6673" stroke="#CC0000" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Team Monthly Collection</p>
                    <h3 class="stat-number">LKR 456,750.00</h3>
                </div>

            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M15.9998 29.3327C23.3636 29.3327 29.3332 23.3631 29.3332 15.9993C29.3332 8.63555 23.3636 2.66602 15.9998 2.66602C8.63604 2.66602 2.6665 8.63555 2.6665 15.9993C2.6665 23.3631 8.63604 29.3327 15.9998 29.3327Z" stroke="#CC0000" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 24C20.4183 24 24 20.4183 24 16C24 11.5817 20.4183 8 16 8C11.5817 8 8 11.5817 8 16C8 20.4183 11.5817 24 16 24Z" stroke="#CC0000" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16.0002 18.6673C17.4729 18.6673 18.6668 17.4734 18.6668 16.0007C18.6668 14.5279 17.4729 13.334 16.0002 13.334C14.5274 13.334 13.3335 14.5279 13.3335 16.0007C13.3335 17.4734 14.5274 18.6673 16.0002 18.6673Z" stroke="#CC0000" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Achieved Collection %</p>
                    <h3 class="stat-number">78.5%</h3>
                </div>

            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M16 8V16L21.3333 18.6667" stroke="#FF9800" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15.9998 29.3327C23.3636 29.3327 29.3332 23.3631 29.3332 15.9993C29.3332 8.63555 23.3636 2.66602 15.9998 2.66602C8.63604 2.66602 2.6665 8.63555 2.6665 15.9993C2.6665 23.3631 8.63604 29.3327 15.9998 29.3327Z" stroke="#FF9800" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Pending Collections</p>
                    <h3 class="stat-number">LKR 125,000.00</h3>
                </div>

            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M29.0681 13.3333C29.6771 16.3217 29.2431 19.4286 27.8386 22.1357C26.4341 24.8429 24.144 26.9867 21.3502 28.2097C18.5563 29.4328 15.4276 29.661 12.4859 28.8565C9.5441 28.0519 6.96705 26.2632 5.1845 23.7885C3.40195 21.3139 2.52163 18.303 2.69035 15.2578C2.85907 12.2127 4.06664 9.31744 6.11167 7.05488C8.1567 4.79232 10.9156 3.29923 13.9282 2.82459C16.9409 2.34995 20.0252 2.92247 22.6668 4.44665" stroke="#4CAF50" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 14.6673L16 18.6673L29.3333 5.33398" stroke="#4CAF50" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Today's Collections</p>
                    <h3 class="stat-number">LKR 32,500.00</h3>
                </div>

            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M15.9998 29.3327C23.3636 29.3327 29.3332 23.3631 29.3332 15.9993C29.3332 8.63555 23.3636 2.66602 15.9998 2.66602C8.63604 2.66602 2.6665 8.63555 2.6665 15.9993C2.6665 23.3631 8.63604 29.3327 15.9998 29.3327Z" stroke="#FF4444" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 10.666V15.9993" stroke="#FF4444" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 21.334H16.0133" stroke="#FF4444" stroke-width="2.66667" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Outstanding Customers</p>
                    <h3 class="stat-number">8 Customers</h3>
                </div>

            </div>
        </div>

        <!-- Monthly Target Progress -->
        <div class="progress-section mt-4">
            <div class="section-header">
                <span class="section-icon">
                    <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                        <path d="M15 27.5C21.9036 27.5 27.5 21.9036 27.5 15C27.5 8.09644 21.9036 2.5 15 2.5C8.09644 2.5 2.5 8.09644 2.5 15C2.5 21.9036 8.09644 27.5 15 27.5Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15 22.5C19.1421 22.5 22.5 19.1421 22.5 15C22.5 10.8579 19.1421 7.5 15 7.5C10.8579 7.5 7.5 10.8579 7.5 15C7.5 19.1421 10.8579 22.5 15 22.5Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15 17.5C16.3807 17.5 17.5 16.3807 17.5 15C17.5 13.6193 16.3807 12.5 15 12.5C13.6193 12.5 12.5 13.6193 12.5 15C12.5 16.3807 13.6193 17.5 15 17.5Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <h2 class="section-title">Monthly Target Progress</h2>
            </div>
            <div class="progress-info">
                <span class="days-remaining">28 days remaining</span>
                <span class="progress-values">LKR 456,750 / LKR 580,000</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: 78.5%;">78.5%</div>
            </div>
            <div class="progress-status">
                <span>On track to meet target 🎯</span>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="performers-section mt-4">
            <div class="section-header">
                <span class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                        <path d="M19.3464 16.1133L21.2402 26.7708C21.2614 26.8963 21.2438 27.0253 21.1897 27.1405C21.1356 27.2557 21.0477 27.3517 20.9376 27.4155C20.8275 27.4794 20.7005 27.5081 20.5737 27.4979C20.4468 27.4877 20.3261 27.439 20.2277 27.3583L15.7527 23.9995C15.5366 23.8381 15.2742 23.7509 15.0046 23.7509C14.7349 23.7509 14.4725 23.8381 14.2564 23.9995L9.77393 27.357C9.67558 27.4376 9.55501 27.4862 9.4283 27.4965C9.30159 27.5067 9.17477 27.4781 9.06476 27.4144C8.95474 27.3507 8.86677 27.2549 8.81258 27.14C8.75838 27.025 8.74054 26.8962 8.76143 26.7708L10.6539 16.1133" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M15 17.5C19.1421 17.5 22.5 14.1421 22.5 10C22.5 5.85786 19.1421 2.5 15 2.5C10.8579 2.5 7.5 5.85786 7.5 10C7.5 14.1421 10.8579 17.5 15 17.5Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <h2 class="section-title">Top Performers This Month</h2>
            </div>

            <div class="performer-item">
                <div class="performer-info">
                    <span class="performer-rank">🥇</span>
                    <div class="performer-details">
                        <h4>H.K Perera</h4>
                        <p>Rank #1</p>
                    </div>
                </div>
                <div class="performer-amount">LKR 85,500.00</div>
            </div>

            <div class="performer-item">
                <div class="performer-info">
                    <span class="performer-rank">🥈</span>
                    <div class="performer-details">
                        <h4>Tahan Perera</h4>
                        <p>Rank #2</p>
                    </div>
                </div>
                <div class="performer-amount">LKR 72,300.00</div>
            </div>

            <div class="performer-item">
                <div class="performer-info">
                    <span class="performer-rank">🥉</span>
                    <div class="performer-details">
                        <h4>Pasan Randula</h4>
                        <p>Rank #3</p>
                    </div>
                </div>
                <div class="performer-amount">LKR 68,900.00</div>
            </div>

            <div class="performer-item">
                <div class="performer-info">
                    <span class="performer-rank">🏅</span>
                    <div class="performer-details">
                        <h4>Irosh Yasas</h4>
                        <p>Rank #4</p>
                    </div>
                </div>
                <div class="performer-amount">LKR 65,200.00</div>
            </div>

            <div class="performer-item">
                <div class="performer-info">
                    <span class="performer-rank">🏅</span>
                    <div class="performer-details">
                        <h4>Harshana Madushan</h4>
                        <p>Rank #5</p>
                    </div>
                </div>
                <div class="performer-amount">LKR 61,850.00</div>
            </div>
        </div>

        <!-- Pending Outstanding Customers -->
        <div class="customers-section mt-4">
            <div class="customers-header">
                <div class="section-header">
                    <span class="section-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                            <path d="M15 7.5V15L20 17.5" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M15 27.5C21.9036 27.5 27.5 21.9036 27.5 15C27.5 8.09644 21.9036 2.5 15 2.5C8.09644 2.5 2.5 8.09644 2.5 15C2.5 21.9036 8.09644 27.5 15 27.5Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h2 class="section-title">Pending Outstanding Customers</h2>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M7 2.91602V11.0827" stroke="white" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M11.0832 7L6.99984 11.0833L2.9165 7" stroke="white" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round" />
                        </svg> Highest First</button>
                    <button class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M2.9165 6.99935L6.99984 2.91602L11.0832 6.99935" stroke="#666666" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7 11.0827V2.91602" stroke="#666666" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Lowest First</button>
                </div>
            </div>

            <div class="customer-item">
                <div class="customer-info">
                    <h4>Toyota Lanka Navinaa</h4>
                    <p>H.K Perera</p>
                </div>
                <div class="customer-amount">LKR 45,000.00</div>
            </div>

            <div class="customer-item">
                <div class="customer-info">
                    <h4>ABC Traders</h4>
                    <p>Ashan Bandara</p>
                </div>
                <div class="customer-amount">LKR 28,500.00</div>
            </div>

            <div class="customer-item">
                <div class="customer-info">
                    <h4>Dimo Lanka Bambalapitiya</h4>
                    <p>Pasan Randula</p>
                </div>
                <div class="customer-amount">LKR 32,000.00</div>
            </div>

            <div class="customer-item">
                <div class="customer-info">
                    <h4>Dimo Lanka Galle</h4>
                    <p>Harshana Madushan</p>
                </div>
                <div class="customer-amount">LKR 19,750.00</div>
            </div>

            <div class="customer-item">
                <div class="customer-info">
                    <h4>Toyota Lanka Kaduwela</h4>
                    <p>Harshana Madushan</p>
                </div>
                <div class="customer-amount">LKR 15,200.00</div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="notifications-section mt-4">
            <div class="section-header">
                <span class="section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                        <path d="M12.835 26.25C13.0544 26.63 13.37 26.9456 13.75 27.165C14.1301 27.3844 14.5611 27.4999 15 27.4999C15.4388 27.4999 15.8699 27.3844 16.2499 27.165C16.6299 26.9456 16.9455 26.63 17.165 26.25" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.07756 19.1575C3.91427 19.3365 3.80651 19.5591 3.76738 19.7981C3.72826 20.0372 3.75946 20.2826 3.8572 20.5042C3.95493 20.7259 4.11498 20.9144 4.31788 21.0468C4.52078 21.1792 4.75779 21.2498 5.00006 21.25H25.0001C25.2423 21.2501 25.4794 21.1798 25.6824 21.0476C25.8854 20.9155 26.0457 20.7272 26.1437 20.5056C26.2417 20.2841 26.2732 20.0389 26.2344 19.7997C26.1956 19.5606 26.0881 19.3379 25.9251 19.1587C24.2626 17.445 22.5001 15.6238 22.5001 10C22.5001 8.01088 21.7099 6.10322 20.3034 4.6967C18.8968 3.29018 16.9892 2.5 15.0001 2.5C13.0109 2.5 11.1033 3.29018 9.69676 4.6967C8.29024 6.10322 7.50006 8.01088 7.50006 10C7.50006 15.6238 5.73631 17.445 4.07756 19.1575Z" stroke="#CC0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <h2 class="section-title">Notifications</h2>
            </div>

            <div class="notification-tabs">
                <div class="notification-tab active" data-tab="payment">Payment Notifications</div>
                <div class="notification-tab" data-tab="system">System Notifications</div>
            </div>

            <!-- Payment Notifications -->
            <div class="notification-list" data-content="payment">
                <div class="notification-item">
                    <div class="notification-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_4749_15306)">
                                <path d="M14.5341 6.66764C14.8385 8.16184 14.6215 9.71525 13.9193 11.0688C13.2171 12.4224 12.072 13.4943 10.6751 14.1058C9.27816 14.7174 7.71382 14.8315 6.24293 14.4292C4.77205 14.0269 3.48353 13.1326 2.59225 11.8953C1.70097 10.6579 1.26081 9.15246 1.34518 7.62989C1.42954 6.10733 2.03332 4.6597 3.05583 3.52842C4.07835 2.39714 5.45779 1.65059 6.96411 1.41327C8.47043 1.17595 10.0126 1.46221 11.3334 2.2243" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6 7.33268L8 9.33268L14.6667 2.66602" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_4749_15306">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg></div>
                    <div class="notification-content">
                        <p>Payment received from John Mitchell - LKR 25,000</p>
                        <span>18 mins ago</span>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_4749_15306)">
                                <path d="M14.5341 6.66764C14.8385 8.16184 14.6215 9.71525 13.9193 11.0688C13.2171 12.4224 12.072 13.4943 10.6751 14.1058C9.27816 14.7174 7.71382 14.8315 6.24293 14.4292C4.77205 14.0269 3.48353 13.1326 2.59225 11.8953C1.70097 10.6579 1.26081 9.15246 1.34518 7.62989C1.42954 6.10733 2.03332 4.6597 3.05583 3.52842C4.07835 2.39714 5.45779 1.65059 6.96411 1.41327C8.47043 1.17595 10.0126 1.46221 11.3334 2.2243" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6 7.33268L8 9.33268L14.6667 2.66602" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_4749_15306">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="notification-content">
                        <p>Payment received from Sarah Williams - LKR 18,500</p>
                        <span>25 mins ago</span>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_4749_15306)">
                                <path d="M14.5341 6.66764C14.8385 8.16184 14.6215 9.71525 13.9193 11.0688C13.2171 12.4224 12.072 13.4943 10.6751 14.1058C9.27816 14.7174 7.71382 14.8315 6.24293 14.4292C4.77205 14.0269 3.48353 13.1326 2.59225 11.8953C1.70097 10.6579 1.26081 9.15246 1.34518 7.62989C1.42954 6.10733 2.03332 4.6597 3.05583 3.52842C4.07835 2.39714 5.45779 1.65059 6.96411 1.41327C8.47043 1.17595 10.0126 1.46221 11.3334 2.2243" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6 7.33268L8 9.33268L14.6667 2.66602" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_4749_15306">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="notification-content">
                        <p>Payment received from David Thompson - LKR 32,000</p>
                        <span>1 hour ago</span>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_4749_15306)">
                                <path d="M14.5341 6.66764C14.8385 8.16184 14.6215 9.71525 13.9193 11.0688C13.2171 12.4224 12.072 13.4943 10.6751 14.1058C9.27816 14.7174 7.71382 14.8315 6.24293 14.4292C4.77205 14.0269 3.48353 13.1326 2.59225 11.8953C1.70097 10.6579 1.26081 9.15246 1.34518 7.62989C1.42954 6.10733 2.03332 4.6597 3.05583 3.52842C4.07835 2.39714 5.45779 1.65059 6.96411 1.41327C8.47043 1.17595 10.0126 1.46221 11.3334 2.2243" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6 7.33268L8 9.33268L14.6667 2.66602" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_4749_15306">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="notification-content">
                        <p>Payment received from Emily Davis - LKR 15,750</p>
                        <span>2 hours ago</span>
                    </div>
                </div>
            </div>

            <!-- System Notifications -->
            <div class="notification-list d-none" data-content="system">
                <div class="notification-item">
                    <div class="notification-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_4749_15306)">
                                <path d="M14.5341 6.66764C14.8385 8.16184 14.6215 9.71525 13.9193 11.0688C13.2171 12.4224 12.072 13.4943 10.6751 14.1058C9.27816 14.7174 7.71382 14.8315 6.24293 14.4292C4.77205 14.0269 3.48353 13.1326 2.59225 11.8953C1.70097 10.6579 1.26081 9.15246 1.34518 7.62989C1.42954 6.10733 2.03332 4.6597 3.05583 3.52842C4.07835 2.39714 5.45779 1.65059 6.96411 1.41327C8.47043 1.17595 10.0126 1.46221 11.3334 2.2243" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6 7.33268L8 9.33268L14.6667 2.66602" stroke="#4CAF50" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_4749_15306">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg></div>
                    <div class="notification-content">
                        <p>System maintenance scheduled at 10:00 PM</p>
                        <span>1 hour ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const tabs = document.querySelectorAll('.notification-tab');
    const lists = document.querySelectorAll('.notification-list');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {

            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));

            // Hide all notification lists
            lists.forEach(list => list.classList.add('d-none'));

            // Activate clicked tab
            tab.classList.add('active');

            // Show related notification list
            const target = tab.getAttribute('data-tab');
            document
                .querySelector(`.notification-list[data-content="${target}"]`)
                .classList.remove('d-none');
        });
    });
</script>

@include('layouts.footer2')