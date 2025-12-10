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

    /* Force 2-column layout everywhere */
    .grid-two-col {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    /* Stack only inside the left/right groups in Inquiry Info */
    .stack-left,
    .stack-right {
        display: flex;
        flex-direction: column;
        gap: 16px;
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

    .field-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 16px;
        /* vertical gap between each set */
    }

    .reason-text {
        font-size: 14px;
        color: #333;
        line-height: 1.6;
    }

    .attachment-box {
        background: #F9FAFB;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .attachment-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .attachment-icon {
        width: 40px;
        height: 40px;
        background: #dbeafe;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .attachment-icon svg {
        width: 20px;
        height: 20px;
        color: #2563eb;
    }

    .attachment-title {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 2px;
    }

    .attachment-subtitle {
        font-family: Poppins;
        font-size: 12px;
        color: #6A7282;
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
                        <div class="mt-2">

                            <div class="field-group">
                                <div class="field-label">From</div>
                                <span class="slip-detail-text">{{ $senderName }}</span>
                            </div>

                            <div class="field-group">
                                <div class="field-label">To</div>
                                <span class="slip-detail-text">{{ $receiverName }}</span>
                            </div>

                            <div class="field-group">
                                <div class="field-label">Title</div>
                                <span class="slip-detail-text">{{ $reminder->reminder_title }}</span>
                            </div>

                            <div class="field-group">
                                <div class="field-label">Message</div>
                                <span class="slip-detail-text">{{ $reminder->reason }}</span>
                            </div>

                            <div class="field-group">
                                <div class="field-label">Trigger Date</div>
                                <span class="slip-detail-text">
                                    {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('Y-m-d') }}
                                </span>
                            </div>

                            <!-- <div class="field-group">
                                <div class="field-label">Reminder Type</div>
                                <span class="slip-detail-text">{{ $reminder->reminder_type }}</span>
                            </div> -->
                        </div>
                    </div>
                    <a href="{{ url('adm/notifications-and-reminders') }}" class="black-action-btn-lg" style="text-decoration: none;">Close</a>
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