@include('adm::layouts.header')
<style>
    .container {
        max-width: 960px;
        margin: 0 auto;
    }

    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 24px;
        margin-bottom: 16px;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: transparent !important;
        border-bottom: none !important;
    }

    .card-header svg {
        width: 20px;
        height: 20px;
        color: #666;
    }

    .field-label {
        font-family: Poppins;
        font-weight: 400;
        font-size: 14px;
        color: #666;
        margin-bottom: 4px;
    }

    .field-value {
        font-size: 15px;
        color: #1a1a1a;
        font-weight: 500;
    }

    .reason-text {
        font-size: 14px;
        color: #333;
        line-height: 1.6;
    }

    .btn-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 16px;
    }
</style>
<div class="content">
    <div class="main-wrapper">
        <div class="d-flex justify-content-between align-items-center header-with-button">
            <div class="left-title-group">
                <h1 class="header-title">
                    Reminder Details
                </h1>
            </div>
        </div>

        <hr class="red-line mt-2">

        <div class="styled-tab-main">
            <div class="header-and-content-gap-md"></div>
            <div class="slip-details">
                <div class="container">
                    <!-- Reason & Description -->
                    <div class="card">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_4858_14212)">
                                    <path d="M9.99935 18.3337C14.6017 18.3337 18.3327 14.6027 18.3327 10.0003C18.3327 5.39795 14.6017 1.66699 9.99935 1.66699C5.39698 1.66699 1.66602 5.39795 1.66602 10.0003C1.66602 14.6027 5.39698 18.3337 9.99935 18.3337Z" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 6.66699V10.0003" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10 13.333H10.0083" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_4858_14212">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span class="bold-text">Reason & Description</span>
                        </div>

                        <div class="mt-2">
                            <div class="field-label">From</div>
                            <span class="slip-detail-text">
                                &nbsp;ADM
                            </span>

                            <div class="field-label">To</div>
                            <span class="slip-detail-text">
                                &nbsp;Admin account
                            </span>

                            <div class="field-label">Message</div>
                            <span class="slip-detail-text">
                                &nbsp;message .......
                            </span>

                            <div class="field-label">Trigger Date</div>
                            <span class="slip-detail-text">
                                &nbsp;2025-11-17
                            </span>
                        </div>
                    </div>
                    <a href="{{ url('adm/inquiries') }}" class="black-action-btn-lg" style="text-decoration: none;">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- dropdown script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const button = dropdown.querySelector('.custom-dropdown');
            const items = dropdown.querySelectorAll('.dropdown-item');

            items.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault(); // stop page jump
                    const selectedText = this.getAttribute("data-value") || this.textContent.trim();
                    button.innerHTML = selectedText + '<span class="custom-arrow"></span>';
                });
            });
        });
    });
</script>

@include('adm::layouts.footer')