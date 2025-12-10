@include('adm::layouts.header')
<?php

use App\Models\Customers;
?>
<div class="content px-0">
    <div class="px-0 mb-3">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" style="width: 50vw;" id="pills-payment-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-payment" type="button" role="tab" aria-controls="pills-payment"
                    aria-selected="true">Payment Notifications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" style="width: 50vw;" id="pills-system-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-system" type="button" role="tab" aria-controls="pills-system"
                    aria-selected="false">System Notifications</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-payment" role="tabpanel" aria-labelledby="pills-payment-tab">
                @forelse($reminders as $reminder)
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none">
                            <circle cx="15.5" cy="15.5" r="15.5" fill="#EFEFEF" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6044 10.95C10.9131 11.631 10.3847 12.4595 10.0587 13.3734C9.73261 14.2874 9.6173 15.2632 9.72136 16.228C9.73104 16.3576 9.68989 16.4858 9.60662 16.5856C9.52335 16.6854 9.40451 16.7488 9.27527 16.7625C9.14604 16.7761 9.01656 16.7389 8.91428 16.6587C8.81201 16.5786 8.74496 16.4617 8.72736 16.333C8.60734 15.2187 8.74061 14.0916 9.11723 13.0361C9.49385 11.9805 10.1041 11.0236 10.9024 10.237C13.8394 7.33996 18.5774 7.38696 21.4844 10.335C24.3914 13.283 24.3724 18.02 21.4344 20.917C20.0742 22.26 18.2486 23.0272 16.3374 23.059C15.6147 23.0722 14.8939 22.9809 14.1974 22.788C14.0695 22.7527 13.9609 22.6681 13.8955 22.5527C13.83 22.4374 13.8131 22.3008 13.8484 22.173C13.8836 22.0451 13.9682 21.9365 14.0836 21.8711C14.1989 21.8056 14.3355 21.7887 14.4634 21.824C15.0675 21.9914 15.6926 22.0705 16.3194 22.059C17.9744 22.0329 19.5554 21.3687 20.7324 20.205C23.2734 17.699 23.2944 13.595 20.7724 11.037C18.2504 8.47896 14.1454 8.44396 11.6044 10.95Z" fill="black" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.59445 16.8628C9.55072 16.9118 9.49777 16.9517 9.43862 16.9802C9.37947 17.0087 9.31529 17.0253 9.24973 17.029C9.18417 17.0328 9.11852 17.0235 9.05653 17.0019C8.99454 16.9802 8.93743 16.9466 8.88845 16.9028L7.16845 15.3728C7.11557 15.3304 7.07188 15.2776 7.04003 15.2178C7.00819 15.1579 6.98885 15.0922 6.98319 15.0246C6.97753 14.9571 6.98568 14.8891 7.00712 14.8247C7.02857 14.7604 7.06288 14.7011 7.10796 14.6505C7.15304 14.5998 7.20795 14.5589 7.26935 14.5301C7.33075 14.5014 7.39736 14.4854 7.46513 14.4832C7.53289 14.481 7.6004 14.4926 7.66355 14.5173C7.72669 14.5419 7.78416 14.5792 7.83245 14.6268L9.55245 16.1568C9.60157 16.2004 9.64163 16.2533 9.67033 16.3123C9.69903 16.3714 9.7158 16.4356 9.7197 16.5011C9.7236 16.5667 9.71455 16.6324 9.69306 16.6944C9.67157 16.7565 9.63806 16.8137 9.59445 16.8628Z" fill="black" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82071 16.8C8.8601 16.8525 8.90946 16.8968 8.96596 16.9302C9.02246 16.9637 9.08499 16.9857 9.14999 16.995C9.215 17.0043 9.28119 17.0007 9.3448 16.9844C9.4084 16.9681 9.46818 16.9394 9.52071 16.9L11.5207 15.4C11.6268 15.3204 11.6969 15.202 11.7157 15.0707C11.7344 14.9394 11.7003 14.8061 11.6207 14.7C11.5411 14.5939 11.4227 14.5238 11.2914 14.505C11.1601 14.4863 11.0268 14.5204 10.9207 14.6L8.92071 16.1C8.81462 16.1796 8.74448 16.298 8.72573 16.4293C8.70698 16.5606 8.74114 16.6939 8.82071 16.8ZM16.0007 12C16.1333 12 16.2605 12.0527 16.3543 12.1464C16.448 12.2402 16.5007 12.3674 16.5007 12.5V16C16.5007 16.1326 16.448 16.2598 16.3543 16.3536C16.2605 16.4473 16.1333 16.5 16.0007 16.5C15.8681 16.5 15.7409 16.4473 15.6472 16.3536C15.5534 16.2598 15.5007 16.1326 15.5007 16V12.5C15.5007 12.3674 15.5534 12.2402 15.6472 12.1464C15.7409 12.0527 15.8681 12 16.0007 12Z" fill="black" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 16C19.5 16.1326 19.4473 16.2598 19.3536 16.3536C19.2598 16.4473 19.1326 16.5 19 16.5H16C15.8674 16.5 15.7402 16.4473 15.6464 16.3536C15.5527 16.2598 15.5 16.1326 15.5 16C15.5 15.8674 15.5527 15.7402 15.6464 15.6464C15.7402 15.5527 15.8674 15.5 16 15.5H19C19.1326 15.5 19.2598 15.5527 19.3536 15.6464C19.4473 15.7402 19.5 15.8674 19.5 16Z" fill="black" />
                        </svg>
                    </div>
                    <div class="col-9 px-2">
                        <p class="reminder-title mb-1">
                            {{ $reminder->reminder_title }}
                        </p>
                        <p class="reminder-desc mb-0">{{ $reminder->reason }}</p>
                    </div>
                    <div class="col-2 d-flex flex-column">
                        <span class="reminder-time mb-1">
                            {{ \Carbon\Carbon::parse($reminder->reminder_date)->format('Y-m-d') }}
                        </span>

                        <div class="d-flex" style="gap: 4px;">
                            @if($reminder->is_direct)
                            <span class="badge" style="background-color:#007bff;">Direct</span>
                            @endif

                            @if(!$reminder->is_read)
                            <span class="badge" style="background-color:#CC0000;">New</span>
                            @endif
                        </div>
                    </div>

                </div>
                @empty
                <p>No reminders found.</p>
                @endforelse
            </div>


            <div class="tab-pane fade" id="pills-system" role="tabpanel" aria-labelledby="pills-system-tab">
                <!-- 1 -->
                <div class="d-flex flex-row px-2 mb-3">
                    <div class="col-1">
                        <span>
                            <img src="assests/history-icon.svg" alt="Logo" class="img-fluid history-icon">
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
                            <img src="assests/history-icon.svg" alt="Logo" class="img-fluid history-icon">
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
                            <img src="assests/history-icon.svg" alt="Logo" class="img-fluid history-icon">
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
                            <img src="assests/history-icon.svg" alt="Logo" class="img-fluid history-icon">
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
<a href="{{url('adm/create-reminder')}}" class="notification-link">
    <svg width="79" height="79" viewBox="0 0 79 79" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g filter="url(#filter0_di_969_18155)">
            <circle cx="39.3086" cy="39.3086" r="28.3086" fill="#CC0000" />
        </g>
        <path d="M38.8594 52.3395C38.118 52.3395 37.4835 52.0757 36.956 51.5482C36.4285 51.0207 36.1643 50.3857 36.1634 49.6434H41.5555C41.5555 50.3848 41.2917 51.0198 40.7642 51.5482C40.2367 52.0766 39.6017 52.3404 38.8594 52.3395ZM46.9476 40.2072V36.1631H42.9035V33.4671H46.9476V29.423H49.6437V33.4671H53.6877V36.1631H49.6437V40.2072H46.9476ZM28.0752 48.2954V45.5993H30.7713V36.1631C30.7713 34.2984 31.3329 32.6416 32.4563 31.193C33.5796 29.7443 35.04 28.7948 36.8374 28.3446V27.401C36.8374 26.8393 37.0342 26.3621 37.4278 25.9693C37.8214 25.5766 38.2986 25.3798 38.8594 25.3789C39.4202 25.378 39.8979 25.5748 40.2924 25.9693C40.6869 26.3639 40.8833 26.8411 40.8815 27.401V28.3446C41.196 28.4344 41.5052 28.5302 41.8089 28.6317C42.1127 28.7333 42.3989 28.8622 42.6676 29.0186C42.3306 29.3331 42.0273 29.676 41.7577 30.0471C41.4881 30.4183 41.2522 30.8169 41.05 31.2428C40.713 31.0856 40.3589 30.9678 39.9877 30.8896C39.6166 30.8115 39.2405 30.7719 38.8594 30.771C37.3766 30.771 36.1072 31.299 35.0512 32.355C33.9953 33.4109 33.4673 34.6803 33.4673 36.1631V45.5993H44.2515V41.8249C44.6559 42.072 45.0828 42.2742 45.5322 42.4315C45.9815 42.5887 46.4533 42.7123 46.9476 42.8022V45.5993H49.6437V48.2954H28.0752Z" fill="white" />
        <defs>
            <filter id="filter0_di_969_18155" x="0" y="0" width="78.6172" height="78.6172" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                <feMorphology radius="1" operator="dilate" in="SourceAlpha" result="effect1_dropShadow_969_18155" />
                <feOffset />
                <feGaussianBlur stdDeviation="5" />
                <feComposite in2="hardAlpha" operator="out" />
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_969_18155" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_969_18155" result="shape" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                <feOffset dx="-4" />
                <feGaussianBlur stdDeviation="1.65" />
                <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1" />
                <feColorMatrix type="matrix" values="0 0 0 0 0.00833333 0 0 0 0 0.00833333 0 0 0 0 0.00833333 0 0 0 0.15 0" />
                <feBlend mode="normal" in2="shape" result="effect2_innerShadow_969_18155" />
            </filter>
        </defs>
    </svg>

</a>
@include('adm::layouts.footer')