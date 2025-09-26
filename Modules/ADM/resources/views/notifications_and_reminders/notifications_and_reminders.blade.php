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
                    <div class="tab-pane fade show active" id="pills-payment" role="tabpanel"
                        aria-labelledby="pills-payment-tab">
                        <?php foreach($reminders as $reminder){ ?> 
                        <!-- 1 -->
                        <div class="d-flex flex-row px-2 mb-3">
                            <div class="col-1">
                                <span>
                                    <img src="assests/history-icon.svg" alt="Logo" class="img-fluid history-icon">  
                                </span>
                            </div>
                            <div class="col-9 px-2">
                                <p class="reminder-title mb-1">{{$reminder->title}}</p>
                                <p class="reminder-desc mb-0">Lorem ipsum dolor sit amet consectetur.</p>
                            </div>
                            <div class="col-2">
                                <span class="reminder-time">Just now</span>
                            </div>
                        </div>
                        <!-- 2 -->
                        <?php } ?> 
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
        <circle cx="39.3086" cy="39.3086" r="28.3086" fill="#CC0000"/>
        </g>
        <path d="M38.8594 52.3395C38.118 52.3395 37.4835 52.0757 36.956 51.5482C36.4285 51.0207 36.1643 50.3857 36.1634 49.6434H41.5555C41.5555 50.3848 41.2917 51.0198 40.7642 51.5482C40.2367 52.0766 39.6017 52.3404 38.8594 52.3395ZM46.9476 40.2072V36.1631H42.9035V33.4671H46.9476V29.423H49.6437V33.4671H53.6877V36.1631H49.6437V40.2072H46.9476ZM28.0752 48.2954V45.5993H30.7713V36.1631C30.7713 34.2984 31.3329 32.6416 32.4563 31.193C33.5796 29.7443 35.04 28.7948 36.8374 28.3446V27.401C36.8374 26.8393 37.0342 26.3621 37.4278 25.9693C37.8214 25.5766 38.2986 25.3798 38.8594 25.3789C39.4202 25.378 39.8979 25.5748 40.2924 25.9693C40.6869 26.3639 40.8833 26.8411 40.8815 27.401V28.3446C41.196 28.4344 41.5052 28.5302 41.8089 28.6317C42.1127 28.7333 42.3989 28.8622 42.6676 29.0186C42.3306 29.3331 42.0273 29.676 41.7577 30.0471C41.4881 30.4183 41.2522 30.8169 41.05 31.2428C40.713 31.0856 40.3589 30.9678 39.9877 30.8896C39.6166 30.8115 39.2405 30.7719 38.8594 30.771C37.3766 30.771 36.1072 31.299 35.0512 32.355C33.9953 33.4109 33.4673 34.6803 33.4673 36.1631V45.5993H44.2515V41.8249C44.6559 42.072 45.0828 42.2742 45.5322 42.4315C45.9815 42.5887 46.4533 42.7123 46.9476 42.8022V45.5993H49.6437V48.2954H28.0752Z" fill="white"/>
        <defs>
        <filter id="filter0_di_969_18155" x="0" y="0" width="78.6172" height="78.6172" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
        <feMorphology radius="1" operator="dilate" in="SourceAlpha" result="effect1_dropShadow_969_18155"/>
        <feOffset/>
        <feGaussianBlur stdDeviation="5"/>
        <feComposite in2="hardAlpha" operator="out"/>
        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_969_18155"/>
        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_969_18155" result="shape"/>
        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
        <feOffset dx="-4"/>
        <feGaussianBlur stdDeviation="1.65"/>
        <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
        <feColorMatrix type="matrix" values="0 0 0 0 0.00833333 0 0 0 0 0.00833333 0 0 0 0 0.00833333 0 0 0 0.15 0"/>
        <feBlend mode="normal" in2="shape" result="effect2_innerShadow_969_18155"/>
        </filter>
        </defs>
        </svg>
        
    </a>
        @include('adm::layouts.footer')
