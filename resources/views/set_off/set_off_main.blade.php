@include('layouts.dashboard-header')

<style>
    /* Search box styles */
    #search-box-wrapper {
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #fff;
        transition: width 0.3s ease;
        border-radius: 30px;
        height: 45px;
        width: 45px;
        border: 1px solid transparent;
        position: relative;
        width: 0;
    }

    #search-box-wrapper.collapsed {
        width: 0;
        padding: 0;
        margin: 0;
        border: 1px solid transparent;
        background-color: transparent;
    }

    #search-box-wrapper.expanded {
        width: 450px;
        padding: 0 15px;
    }

    .search-input {
        flex-grow: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 16px;
        color: #333;
        width: 100%;
        padding-left: 30px;
    }

    .search-input::placeholder {
        color: #888;
    }

    .search-icon-inside {
        position: absolute;
        left: 10px;
        color: #888;
    }

    .col-12.d-flex.justify-content-lg-end {
        align-items: center;
    }
</style>

<div class="main-wrapper">

    <div class="row d-flex justify-content-between">
        <div class="col-lg-6 col-12">
            <h1 class="header-title">Set - off</h1>
        </div>
    </div>

    <div class="styled-tab-main">
        <div class="header-and-content-gap-lg"></div>
        @if(in_array('setoff-add', session('permissions', [])))
        <div class="col-12 d-flex justify-content-end mb-3">
            <a href="{{ url('/set-off') }}">
                <button class="red-action-btn-lg add-new-payment-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.50726 10.5634H4.85938V9.0141H9.50726V4.36621H11.0566V9.0141H15.7044V10.5634H11.0566V15.2113H9.50726V10.5634Z"
                            fill="white" />
                    </svg>
                    Add
                </button>
            </a>
        </div>
        @endif
        <div class="table-responsive">
            <table class="table custom-table-locked" style="min-width: 900px;">
                <thead>
                    <tr>
                        <th>Set-off ID</th>
                        <th>Date</th>
                        <th>Final set-off amount</th>
                        <th class="sticky-column">Actions</th>
                    </tr>
                </thead>
               <tbody id="cashDepositeTableBody">
                    @forelse ($setOffs as $setOff)
                        @php
                            $canView = in_array('setoff-view', session('permissions', []));
                        @endphp

                        <tr
                            @if($canView)
                                class="clickable-row"
                                data-href="{{ url('set-off-details', $setOff->id) }}"
                                style="cursor:pointer;"
                            @endif
                        >
                            <td>{{ $setOff->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($setOff->created_at)->format('Y-m-d') }}</td>
                            <td>{{ number_format($setOff->final_amount, 2) }}</td>
                            <td class="sticky-column">
                                @if(in_array('setoff-download', session('permissions', [])))
                                    <a href="{{ url('set-off/download', $setOff->id) }}"
                                    class="black-action-btn submit"
                                    style="text-decoration: none;"
                                    onclick="event.stopPropagation()">
                                        Download
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No set-off records found.</td>
                        </tr>
                    @endforelse
                    </tbody>

            </table>

        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $setOffs->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

<!-- Toast message -->
<div id="user-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-4"
    role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999; display: none; min-width: 320px;">
    <div class="d-flex align-items-center">
        <span class="toast-icon-circle d-flex align-items-center justify-content-center me-3">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="12" fill="#fff" />
                <path d="M7 12.5l3 3 7-7" stroke="#28a745" stroke-width="2" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </span>
        <div class="toast-body flex-grow-1">
            Downloaded successfully
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
            onclick="document.getElementById('user-toast').style.display='none';"></button>
    </div>
</div>

<!-- link entire row of table -->
<script>
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.clickable-row');
        if (row && !e.target.closest('button')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
</script>

<!-- for toast message -->
<script>
    document.querySelectorAll('.submit').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const toast = document.getElementById('user-toast');
            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);

            // Simulate download action
            setTimeout(() => {
                window.location.href = this.getAttribute('href');
            }, 500);
        });
    });
</script>