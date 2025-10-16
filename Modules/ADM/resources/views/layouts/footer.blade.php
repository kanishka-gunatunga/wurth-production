<!-- offcanvas - profile -->
 <?php
 use App\Models\UserDetails;
 $other_details = UserDetails::where('user_id',Auth::user()->id)->first();
 ?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header p-0 m-0">
                <div class="d-flex w-100 p-3 flex-column justify-content-center align-items-center">
                    <?php if($other_details->profile_picture == ''){ ?>
                        <img id="profilePic" src="{{ asset('adm_assets/assests/profile-pic.jpg') }}" alt="Logo" class="img-fluid profile-pic-offcanvas-header mb-3">
                    <?php } else { ?>
                        <img id="profilePic" src="{{ asset('db_files/user_profile_images/'.$other_details->profile_picture.'') }}" alt="Logo" class="img-fluid profile-pic-offcanvas-header mb-3">
                    <?php } ?>
                    <p class="red-title-22 mb-1">{{$other_details->name}}</p>
                    <p class="gray-text-13 mb-1">Sales Representative</p>
                    <p class="gray-text-13">{{$other_details->adm_number}}</p>
                    <div class="line" style="width: 70%; height: 1px; background-color: #000;"></div>
                </div>
            </div>
            <div class="offcanvas-body">
                <div class="scrollable-content">
                    <div class="d-flex flex-column justify-content-center align-items-center ">
                        <div class="d-flex flex-row w-100 mb-3 justify-content-start align-items-center">
                            <a href="{{url('adm')}}"
                                class="active w-100 d-flex flex-row" style="text-decoration: none !important;"
                                class="d-flex flex-row align-items-center">
                                <svg width="17" height="18" viewBox="0 0 17 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.02116 1.29199C3.5971 1.29199 3.17719 1.37552 2.78541 1.5378C2.39363 1.70008 2.03765 1.93794 1.73779 2.23779C1.43794 2.53765 1.20008 2.89363 1.0378 3.28541C0.875517 3.67719 0.791992 4.0971 0.791992 4.52116C0.791992 4.94522 0.875517 5.36513 1.0378 5.75691C1.20008 6.14869 1.43794 6.50467 1.73779 6.80452C2.03765 7.10438 2.39363 7.34224 2.78541 7.50452C3.17719 7.6668 3.5971 7.75033 4.02116 7.75033C4.87759 7.75033 5.69894 7.41011 6.30452 6.80452C6.91011 6.19894 7.25033 5.37759 7.25033 4.52116C7.25033 3.66473 6.91011 2.84338 6.30452 2.23779C5.69894 1.63221 4.87759 1.29199 4.02116 1.29199ZM12.9795 1.29199C12.5554 1.29199 12.1355 1.37552 11.7437 1.5378C11.352 1.70008 10.996 1.93794 10.6961 2.23779C10.3963 2.53765 10.1584 2.89363 9.99613 3.28541C9.83385 3.67719 9.75033 4.0971 9.75033 4.52116C9.75033 4.94522 9.83385 5.36513 9.99613 5.75691C10.1584 6.14869 10.3963 6.50467 10.6961 6.80452C10.996 7.10438 11.352 7.34224 11.7437 7.50452C12.1355 7.6668 12.5554 7.75033 12.9795 7.75033C13.8359 7.75033 14.6573 7.41011 15.2629 6.80452C15.8684 6.19894 16.2087 5.37759 16.2087 4.52116C16.2087 3.66473 15.8684 2.84338 15.2629 2.23779C14.6573 1.63221 13.8359 1.29199 12.9795 1.29199ZM4.02116 10.2503C3.5971 10.2503 3.17719 10.3339 2.78541 10.4961C2.39363 10.6584 2.03765 10.8963 1.73779 11.1961C1.43794 11.496 1.20008 11.852 1.0378 12.2437C0.875517 12.6355 0.791992 13.0554 0.791992 13.4795C0.791992 13.9036 0.875517 14.3235 1.0378 14.7152C1.20008 15.107 1.43794 15.463 1.73779 15.7629C2.03765 16.0627 2.39363 16.3006 2.78541 16.4629C3.17719 16.6251 3.5971 16.7087 4.02116 16.7087C4.87759 16.7087 5.69894 16.3684 6.30452 15.7629C6.91011 15.1573 7.25033 14.3359 7.25033 13.4795C7.25033 12.6231 6.91011 11.8017 6.30452 11.1961C5.69894 10.5905 4.87759 10.2503 4.02116 10.2503ZM12.9795 10.2503C12.5554 10.2503 12.1355 10.3339 11.7437 10.4961C11.352 10.6584 10.996 10.8963 10.6961 11.1961C10.3963 11.496 10.1584 11.852 9.99613 12.2437C9.83385 12.6355 9.75033 13.0554 9.75033 13.4795C9.75033 13.9036 9.83385 14.3235 9.99613 14.7152C10.1584 15.107 10.3963 15.463 10.6961 15.7629C10.996 16.0627 11.352 16.3006 11.7437 16.4629C12.1355 16.6251 12.5554 16.7087 12.9795 16.7087C13.8359 16.7087 14.6573 16.3684 15.2629 15.7629C15.8684 15.1573 16.2087 14.3359 16.2087 13.4795C16.2087 12.6231 15.8684 11.8017 15.2629 11.1961C14.6573 10.5905 13.8359 10.2503 12.9795 10.2503Z"
                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <p class="gray-text-13 mb-0 ms-4">Dashboard</p>
                            </a>
                        </div>
                        <div class="d-flex flex-column w-100 mb-3 justify-content-start">
                            <button class="btn btn-link d-flex justify-content-between align-items-center p-0"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample"
                                style="text-decoration: none; box-shadow: none; outline: none;">
                                <div class="d-flex flex-row justify-content-center align-items-center">
                                    <svg width="21" height="19" viewBox="0 0 21 19" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.625 4.375C3.45924 4.375 3.30027 4.30915 3.18306 4.19194C3.06585 4.07473 3 3.91576 3 3.75C3 3.58424 3.06585 3.42527 3.18306 3.30806C3.30027 3.19085 3.45924 3.125 3.625 3.125H17.375C17.5408 3.125 17.6997 3.19085 17.8169 3.30806C17.9342 3.42527 18 3.58424 18 3.75C18 3.91576 17.9342 4.07473 17.8169 4.19194C17.6997 4.30915 17.5408 4.375 17.375 4.375H3.625ZM6.125 1.875C5.95924 1.875 5.80027 1.80915 5.68306 1.69194C5.56585 1.57473 5.5 1.41576 5.5 1.25C5.5 1.08424 5.56585 0.925268 5.68306 0.808058C5.80027 0.690848 5.95924 0.625 6.125 0.625H14.875C15.0408 0.625 15.1997 0.690848 15.3169 0.808058C15.4342 0.925268 15.5 1.08424 15.5 1.25C15.5 1.41576 15.4342 1.57473 15.3169 1.69194C15.1997 1.80915 15.0408 1.875 14.875 1.875H6.125ZM0.5 16.25C0.5 16.7473 0.697544 17.2242 1.04917 17.5758C1.40081 17.9275 1.87772 18.125 2.375 18.125H18.625C19.1223 18.125 19.5992 17.9275 19.9508 17.5758C20.3025 17.2242 20.5 16.7473 20.5 16.25V7.5C20.5 7.00272 20.3025 6.52581 19.9508 6.17417C19.5992 5.82254 19.1223 5.625 18.625 5.625H2.375C1.87772 5.625 1.40081 5.82254 1.04917 6.17417C0.697544 6.52581 0.5 7.00272 0.5 7.5L0.5 16.25ZM2.375 16.875C2.20924 16.875 2.05027 16.8092 1.93306 16.6919C1.81585 16.5747 1.75 16.4158 1.75 16.25V7.5C1.75 7.33424 1.81585 7.17527 1.93306 7.05806C2.05027 6.94085 2.20924 6.875 2.375 6.875H18.625C18.7908 6.875 18.9497 6.94085 19.0669 7.05806C19.1842 7.17527 19.25 7.33424 19.25 7.5V16.25C19.25 16.4158 19.1842 16.5747 19.0669 16.6919C18.9497 16.8092 18.7908 16.875 18.625 16.875H2.375Z"
                                            fill="black" />
                                    </svg>
                                    <p class="gray-text-13 mb-0 ms-4">Collections</p>
                                </div>
                                <i class="bi bi-chevron-down collapse-icon" style="color: #000;"></i>
                            </button>
                            <div class="collapse" id="collapseExample">
                                <a href="#" class="w-100 d-flex flex-row mb-3 mt-2"
                                    style="text-decoration: none !important;"
                                    class="d-flex flex-row align-items-center mt-2">
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <svg width="21" height="19" viewBox="0 0 21 19" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.625 4.375C3.45924 4.375 3.30027 4.30915 3.18306 4.19194C3.06585 4.07473 3 3.91576 3 3.75C3 3.58424 3.06585 3.42527 3.18306 3.30806C3.30027 3.19085 3.45924 3.125 3.625 3.125H17.375C17.5408 3.125 17.6997 3.19085 17.8169 3.30806C17.9342 3.42527 18 3.58424 18 3.75C18 3.91576 17.9342 4.07473 17.8169 4.19194C17.6997 4.30915 17.5408 4.375 17.375 4.375H3.625ZM6.125 1.875C5.95924 1.875 5.80027 1.80915 5.68306 1.69194C5.56585 1.57473 5.5 1.41576 5.5 1.25C5.5 1.08424 5.56585 0.925268 5.68306 0.808058C5.80027 0.690848 5.95924 0.625 6.125 0.625H14.875C15.0408 0.625 15.1997 0.690848 15.3169 0.808058C15.4342 0.925268 15.5 1.08424 15.5 1.25C15.5 1.41576 15.4342 1.57473 15.3169 1.69194C15.1997 1.80915 15.0408 1.875 14.875 1.875H6.125ZM0.5 16.25C0.5 16.7473 0.697544 17.2242 1.04917 17.5758C1.40081 17.9275 1.87772 18.125 2.375 18.125H18.625C19.1223 18.125 19.5992 17.9275 19.9508 17.5758C20.3025 17.2242 20.5 16.7473 20.5 16.25V7.5C20.5 7.00272 20.3025 6.52581 19.9508 6.17417C19.5992 5.82254 19.1223 5.625 18.625 5.625H2.375C1.87772 5.625 1.40081 5.82254 1.04917 6.17417C0.697544 6.52581 0.5 7.00272 0.5 7.5L0.5 16.25ZM2.375 16.875C2.20924 16.875 2.05027 16.8092 1.93306 16.6919C1.81585 16.5747 1.75 16.4158 1.75 16.25V7.5C1.75 7.33424 1.81585 7.17527 1.93306 7.05806C2.05027 6.94085 2.20924 6.875 2.375 6.875H18.625C18.7908 6.875 18.9497 6.94085 19.0669 7.05806C19.1842 7.17527 19.25 7.33424 19.25 7.5V16.25C19.25 16.4158 19.1842 16.5747 19.0669 16.6919C18.9497 16.8092 18.7908 16.875 18.625 16.875H2.375Z"
                                                fill="black" />
                                        </svg>
                                        <p class="gray-text-13 mb-0 ms-4">All Invoices</p>
                                    </div>
                                </a>
                                <a href="#" class="w-100 d-flex flex-row" style="text-decoration: none !important;"
                                    class="d-flex flex-row align-items-center mt-2">
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <svg width="21" height="19" viewBox="0 0 21 19" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.625 4.375C3.45924 4.375 3.30027 4.30915 3.18306 4.19194C3.06585 4.07473 3 3.91576 3 3.75C3 3.58424 3.06585 3.42527 3.18306 3.30806C3.30027 3.19085 3.45924 3.125 3.625 3.125H17.375C17.5408 3.125 17.6997 3.19085 17.8169 3.30806C17.9342 3.42527 18 3.58424 18 3.75C18 3.91576 17.9342 4.07473 17.8169 4.19194C17.6997 4.30915 17.5408 4.375 17.375 4.375H3.625ZM6.125 1.875C5.95924 1.875 5.80027 1.80915 5.68306 1.69194C5.56585 1.57473 5.5 1.41576 5.5 1.25C5.5 1.08424 5.56585 0.925268 5.68306 0.808058C5.80027 0.690848 5.95924 0.625 6.125 0.625H14.875C15.0408 0.625 15.1997 0.690848 15.3169 0.808058C15.4342 0.925268 15.5 1.08424 15.5 1.25C15.5 1.41576 15.4342 1.57473 15.3169 1.69194C15.1997 1.80915 15.0408 1.875 14.875 1.875H6.125ZM0.5 16.25C0.5 16.7473 0.697544 17.2242 1.04917 17.5758C1.40081 17.9275 1.87772 18.125 2.375 18.125H18.625C19.1223 18.125 19.5992 17.9275 19.9508 17.5758C20.3025 17.2242 20.5 16.7473 20.5 16.25V7.5C20.5 7.00272 20.3025 6.52581 19.9508 6.17417C19.5992 5.82254 19.1223 5.625 18.625 5.625H2.375C1.87772 5.625 1.40081 5.82254 1.04917 6.17417C0.697544 6.52581 0.5 7.00272 0.5 7.5L0.5 16.25ZM2.375 16.875C2.20924 16.875 2.05027 16.8092 1.93306 16.6919C1.81585 16.5747 1.75 16.4158 1.75 16.25V7.5C1.75 7.33424 1.81585 7.17527 1.93306 7.05806C2.05027 6.94085 2.20924 6.875 2.375 6.875H18.625C18.7908 6.875 18.9497 6.94085 19.0669 7.05806C19.1842 7.17527 19.25 7.33424 19.25 7.5V16.25C19.25 16.4158 19.1842 16.5747 19.0669 16.6919C18.9497 16.8092 18.7908 16.875 18.625 16.875H2.375Z"
                                                fill="black" />
                                        </svg>
                                        <p class="gray-text-13 mb-0 ms-4">All Receipts</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="d-flex flex-row w-100 mb-3 justify-content-start align-items-center">
                            <a href="{{url('adm/customers')}}" class="w-100 d-flex flex-row" style="text-decoration: none !important;"
                                class="d-flex flex-row align-items-center">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.4704 14.44C18.8404 14.67 20.3504 14.43 21.4104 13.72C22.8204 12.78 22.8204 11.24 21.4104 10.3C20.3404 9.59001 18.8104 9.35 17.4404 9.59M7.50043 14.44C6.13043 14.67 4.62043 14.43 3.56043 13.72C2.15043 12.78 2.15043 11.24 3.56043 10.3C4.63043 9.59001 6.16043 9.35 7.53043 9.59M18.5004 7.16C18.4404 7.15 18.3704 7.15 18.3104 7.16C16.9304 7.11 15.8304 5.98 15.8304 4.58C15.8304 3.15 16.9804 2 18.4104 2C19.8404 2 20.9904 3.16 20.9904 4.58C20.9804 5.98 19.8804 7.11 18.5004 7.16ZM6.47043 7.16C6.53043 7.15 6.60043 7.15 6.66043 7.16C8.04043 7.11 9.14043 5.98 9.14043 4.58C9.14043 3.15 7.99043 2 6.56043 2C5.13043 2 3.98043 3.16 3.98043 4.58C3.99043 5.98 5.09043 7.11 6.47043 7.16ZM12.5004 14.63C12.4404 14.62 12.3704 14.62 12.3104 14.63C10.9304 14.58 9.83043 13.45 9.83043 12.05C9.83043 10.62 10.9804 9.47 12.4104 9.47C13.8404 9.47 14.9904 10.63 14.9904 12.05C14.9804 13.45 13.8804 14.59 12.5004 14.63ZM9.59043 17.78C8.18043 18.72 8.18043 20.26 9.59043 21.2C11.1904 22.27 13.8104 22.27 15.4104 21.2C16.8204 20.26 16.8204 18.72 15.4104 17.78C13.8204 16.72 11.1904 16.72 9.59043 17.78Z"
                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <p class="gray-text-13 mb-0 ms-4">Customers</p>
                            </a>
                        </div>
                        <div class="d-flex flex-column w-100 mb-3 justify-content-start">
                            <button class="btn btn-link d-flex justify-content-between align-items-center p-0"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseReminder"
                                aria-expanded="false" aria-controls="collapseExample"
                                style="text-decoration: none; box-shadow: none; outline: none;">
                                <div class="d-flex flex-row justify-content-center align-items-center">
                                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.7497 4.66667H9.49967V8.83333L13.0663 10.95L13.6663 9.94167L10.7497 8.20833V4.66667ZM10.333 0.5C8.34388 0.5 6.43623 1.29018 5.02971 2.6967C3.62318 4.10322 2.83301 6.01088 2.83301 8H0.333008L3.63301 11.3583L6.99967 8H4.49967C4.49967 6.4529 5.11426 4.96917 6.20822 3.87521C7.30218 2.78125 8.78591 2.16667 10.333 2.16667C11.8801 2.16667 13.3638 2.78125 14.4578 3.87521C15.5518 4.96917 16.1663 6.4529 16.1663 8C16.1663 9.5471 15.5518 11.0308 14.4578 12.1248C13.3638 13.2188 11.8801 13.8333 10.333 13.8333C8.72467 13.8333 7.26634 13.175 6.21634 12.1167L5.03301 13.3C5.72601 14.0004 6.55162 14.5556 7.46161 14.9334C8.3716 15.3111 9.34774 15.5037 10.333 15.5C12.3221 15.5 14.2298 14.7098 15.6363 13.3033C17.0428 11.8968 17.833 9.98912 17.833 8C17.833 6.01088 17.0428 4.10322 15.6363 2.6967C14.2298 1.29018 12.3221 0.5 10.333 0.5Z"
                                            fill="black" />
                                    </svg>
                                    <p class="gray-text-13 mb-0 ms-4">Reminders</p>
                                </div>
                                <i class="bi bi-chevron-down collapse-icon" style="color: #000;"></i>
                            </button>
                            <div class="collapse" id="collapseReminder">
                                <a href="#" class="w-100 d-flex flex-row mb-3 mt-2"
                                    style="text-decoration: none !important;"
                                    class="d-flex flex-row align-items-center mt-2">
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.7497 4.66667H9.49967V8.83333L13.0663 10.95L13.6663 9.94167L10.7497 8.20833V4.66667ZM10.333 0.5C8.34388 0.5 6.43623 1.29018 5.02971 2.6967C3.62318 4.10322 2.83301 6.01088 2.83301 8H0.333008L3.63301 11.3583L6.99967 8H4.49967C4.49967 6.4529 5.11426 4.96917 6.20822 3.87521C7.30218 2.78125 8.78591 2.16667 10.333 2.16667C11.8801 2.16667 13.3638 2.78125 14.4578 3.87521C15.5518 4.96917 16.1663 6.4529 16.1663 8C16.1663 9.5471 15.5518 11.0308 14.4578 12.1248C13.3638 13.2188 11.8801 13.8333 10.333 13.8333C8.72467 13.8333 7.26634 13.175 6.21634 12.1167L5.03301 13.3C5.72601 14.0004 6.55162 14.5556 7.46161 14.9334C8.3716 15.3111 9.34774 15.5037 10.333 15.5C12.3221 15.5 14.2298 14.7098 15.6363 13.3033C17.0428 11.8968 17.833 9.98912 17.833 8C17.833 6.01088 17.0428 4.10322 15.6363 2.6967C14.2298 1.29018 12.3221 0.5 10.333 0.5Z"
                                                fill="black" />
                                        </svg>
                                        <p class="gray-text-13 mb-0 ms-4">Payment Reminders</p>
                                    </div>
                                </a>
                                <a href="#" class="w-100 d-flex flex-row mb-3" style="text-decoration: none !important;"
                                    class="d-flex flex-row align-items-center mt-2">
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.7497 4.66667H9.49967V8.83333L13.0663 10.95L13.6663 9.94167L10.7497 8.20833V4.66667ZM10.333 0.5C8.34388 0.5 6.43623 1.29018 5.02971 2.6967C3.62318 4.10322 2.83301 6.01088 2.83301 8H0.333008L3.63301 11.3583L6.99967 8H4.49967C4.49967 6.4529 5.11426 4.96917 6.20822 3.87521C7.30218 2.78125 8.78591 2.16667 10.333 2.16667C11.8801 2.16667 13.3638 2.78125 14.4578 3.87521C15.5518 4.96917 16.1663 6.4529 16.1663 8C16.1663 9.5471 15.5518 11.0308 14.4578 12.1248C13.3638 13.2188 11.8801 13.8333 10.333 13.8333C8.72467 13.8333 7.26634 13.175 6.21634 12.1167L5.03301 13.3C5.72601 14.0004 6.55162 14.5556 7.46161 14.9334C8.3716 15.3111 9.34774 15.5037 10.333 15.5C12.3221 15.5 14.2298 14.7098 15.6363 13.3033C17.0428 11.8968 17.833 9.98912 17.833 8C17.833 6.01088 17.0428 4.10322 15.6363 2.6967C14.2298 1.29018 12.3221 0.5 10.333 0.5Z"
                                                fill="black" />
                                        </svg>
                                        <p class="gray-text-13 mb-0 ms-4">Return Cheques</p>
                                    </div>
                                </a>
                                <a href="#" class="w-100 d-flex flex-row" style="text-decoration: none !important;"
                                    class="d-flex flex-row align-items-center mt-2">
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.7497 4.66667H9.49967V8.83333L13.0663 10.95L13.6663 9.94167L10.7497 8.20833V4.66667ZM10.333 0.5C8.34388 0.5 6.43623 1.29018 5.02971 2.6967C3.62318 4.10322 2.83301 6.01088 2.83301 8H0.333008L3.63301 11.3583L6.99967 8H4.49967C4.49967 6.4529 5.11426 4.96917 6.20822 3.87521C7.30218 2.78125 8.78591 2.16667 10.333 2.16667C11.8801 2.16667 13.3638 2.78125 14.4578 3.87521C15.5518 4.96917 16.1663 6.4529 16.1663 8C16.1663 9.5471 15.5518 11.0308 14.4578 12.1248C13.3638 13.2188 11.8801 13.8333 10.333 13.8333C8.72467 13.8333 7.26634 13.175 6.21634 12.1167L5.03301 13.3C5.72601 14.0004 6.55162 14.5556 7.46161 14.9334C8.3716 15.3111 9.34774 15.5037 10.333 15.5C12.3221 15.5 14.2298 14.7098 15.6363 13.3033C17.0428 11.8968 17.833 9.98912 17.833 8C17.833 6.01088 17.0428 4.10322 15.6363 2.6967C14.2298 1.29018 12.3221 0.5 10.333 0.5Z"
                                                fill="black" />
                                        </svg>
                                        <p class="gray-text-13 mb-0 ms-4">System Reminders</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="d-flex flex-row w-100 mb-3 justify-content-start align-items-center">
                            <a href="{{url('adm/my-profile')}}"
                                class="w-100 d-flex flex-row" style="text-decoration: none !important;"
                                class="d-flex flex-row align-items-center">
                                <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1 13.75C1 12.8217 1.36875 11.9315 2.02513 11.2751C2.6815 10.6187 3.57174 10.25 4.5 10.25H11.5C12.4283 10.25 13.3185 10.6187 13.9749 11.2751C14.6313 11.9315 15 12.8217 15 13.75C15 14.2141 14.8156 14.6592 14.4874 14.9874C14.1592 15.3156 13.7141 15.5 13.25 15.5H2.75C2.28587 15.5 1.84075 15.3156 1.51256 14.9874C1.18437 14.6592 1 14.2141 1 13.75Z"
                                        stroke="black" stroke-width="1.5" stroke-linejoin="round" />
                                    <path
                                        d="M8 6.75C9.44975 6.75 10.625 5.57475 10.625 4.125C10.625 2.67525 9.44975 1.5 8 1.5C6.55025 1.5 5.375 2.67525 5.375 4.125C5.375 5.57475 6.55025 6.75 8 6.75Z"
                                        stroke="black" stroke-width="1.5" />
                                </svg>
                                <p class="gray-text-13 mb-0 ms-4">My Profile</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-footer p-0 m-0">
                <div class="d-flex flex-row w-100 p-3 justify-content-start align-items-center">
                    <a href="{{url('adm/logout')}}" style="text-decoration: none !important;" class="d-flex flex-row align-items-center">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4.16679 2.6L9.9 2.6V4.06667H4.16667H4.06667V4.16667V15.8333V15.9333H4.16667H9.9V17.4H4.16667C3.73504 17.4 3.3682 17.2476 3.06071 16.9401C2.75322 16.6326 2.60052 16.2655 2.6 15.8332L2.6 4.16667C2.6 3.7351 2.75262 3.36827 3.06065 3.06077C3.36877 2.75317 3.73571 2.60052 4.16679 2.6ZM14.3125 10.7333H7.6V9.26667H14.3125H14.5539L14.3832 9.09596L12.3271 7.03981L13.3352 5.97666L17.3586 10L13.3352 14.0233L12.3271 12.9602L14.3832 10.904L14.5539 10.7333H14.3125Z"
                                fill="black" stroke="white" stroke-width="0.2" />
                        </svg>
                        <p class="gray-text-13 mb-0 ms-4">Log out</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- footer -->
        <div class="footer d-flex justify-content-center align-items-center">
            <div class="row row-cols-5 w-100">
                <div class="col p-2 d-flex justify-content-center align-items-center">
                    <a href="{{url('adm')}}" class="active">
                        <div class="d-flex flex-column justify-content-center align-items-center text-center">
                            <div class="svg-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5"
                                        d="M8.557 2.75H4.682A1.93 1.93 0 0 0 2.75 4.682v3.875a1.94 1.94 0 0 0 1.932 1.942h3.875a1.94 1.94 0 0 0 1.942-1.942V4.682A1.94 1.94 0 0 0 8.557 2.75m10.761 0h-3.875a1.94 1.94 0 0 0-1.942 1.932v3.875a1.943 1.943 0 0 0 1.942 1.942h3.875a1.94 1.94 0 0 0 1.932-1.942V4.682a1.93 1.93 0 0 0-1.932-1.932m0 10.75h-3.875a1.94 1.94 0 0 0-1.942 1.933v3.875a1.94 1.94 0 0 0 1.942 1.942h3.875a1.94 1.94 0 0 0 1.932-1.942v-3.875a1.93 1.93 0 0 0-1.932-1.932M8.557 13.5H4.682a1.943 1.943 0 0 0-1.932 1.943v3.875a1.93 1.93 0 0 0 1.932 1.932h3.875a1.94 1.94 0 0 0 1.942-1.932v-3.875a1.94 1.94 0 0 0-1.942-1.942" />
                                </svg>
                            </div>
                            <p>Dashboard</p>
                        </div>
                    </a>
                </div>
                <div class="col p-2 d-flex justify-content-center align-items-center">
                    <a href="{{url('adm/collections')}}">
                        <div class="d-flex flex-column justify-content-center align-items-center text-center">
                            <div class="svg-wrapper">
                                <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.5 9H3.5M17.5 9C18.0304 9 18.5391 9.21071 18.9142 9.58579C19.2893 9.96086 19.5 10.4696 19.5 11V17C19.5 17.5304 19.2893 18.0391 18.9142 18.4142C18.5391 18.7893 18.0304 19 17.5 19H3.5C2.96957 19 2.46086 18.7893 2.08579 18.4142C1.71071 18.0391 1.5 17.5304 1.5 17V11C1.5 10.4696 1.71071 9.96086 2.08579 9.58579C2.46086 9.21071 2.96957 9 3.5 9M17.5 9V7C17.5 6.46957 17.2893 5.96086 16.9142 5.58579C16.5391 5.21071 16.0304 5 15.5 5M3.5 9V7C3.5 6.46957 3.71071 5.96086 4.08579 5.58579C4.46086 5.21071 4.96957 5 5.5 5M15.5 5V3C15.5 2.46957 15.2893 1.96086 14.9142 1.58579C14.5391 1.21071 14.0304 1 13.5 1H7.5C6.96957 1 6.46086 1.21071 6.08579 1.58579C5.71071 1.96086 5.5 2.46957 5.5 3V5M15.5 5H5.5"
                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p>Collections</p>
                        </div>
                    </a>
                </div>
                <div class="col p-2 d-flex justify-content-center align-items-center">
                    <a href="{{url('adm/customers')}}">
                        <div class="d-flex flex-column justify-content-center align-items-center text-center">
                            <div class="svg-wrapper">
                                <svg width="23" height="22" viewBox="0 0 23 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.4699 13.44C17.8399 13.67 19.3499 13.43 20.4099 12.72C21.8199 11.78 21.8199 10.24 20.4099 9.30001C19.3399 8.59001 17.8099 8.35 16.4399 8.59M6.49994 13.44C5.12994 13.67 3.61994 13.43 2.55994 12.72C1.14994 11.78 1.14994 10.24 2.55994 9.30001C3.62994 8.59001 5.15994 8.35 6.52994 8.59M17.4999 6.16C17.4399 6.15 17.3699 6.15 17.3099 6.16C15.9299 6.11 14.8299 4.98 14.8299 3.58C14.8299 2.15 15.9799 1 17.4099 1C18.8399 1 19.9899 2.16 19.9899 3.58C19.9799 4.98 18.8799 6.11 17.4999 6.16ZM5.46994 6.16C5.52994 6.15 5.59994 6.15 5.65994 6.16C7.03994 6.11 8.13994 4.98 8.13994 3.58C8.13994 2.15 6.98994 1 5.55994 1C4.12994 1 2.97994 2.16 2.97994 3.58C2.98994 4.98 4.08994 6.11 5.46994 6.16ZM11.4999 13.63C11.4399 13.62 11.3699 13.62 11.3099 13.63C9.92994 13.58 8.82994 12.45 8.82994 11.05C8.82994 9.62 9.97994 8.47 11.4099 8.47C12.8399 8.47 13.9899 9.63 13.9899 11.05C13.9799 12.45 12.8799 13.59 11.4999 13.63ZM8.58994 16.78C7.17994 17.72 7.17994 19.26 8.58994 20.2C10.1899 21.27 12.8099 21.27 14.4099 20.2C15.8199 19.26 15.8199 17.72 14.4099 16.78C12.8199 15.72 10.1899 15.72 8.58994 16.78Z"
                                        stroke="black" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p>Customers</p>
                        </div>
                    </a>
                </div>
                <div class="col p-2 d-flex justify-content-center align-items-center">
                    <a href="{{url('adm/notifications-and-reminders')}}">
                        <div class="d-flex flex-column justify-content-center align-items-center text-center">
                            <div class="svg-wrapper">
                                <svg width="21" height="22" viewBox="0 0 21 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.0649 5.39844H9.93753C9.83441 5.39844 9.75003 5.48282 9.75003 5.58594V12.0383C9.75003 12.0992 9.77816 12.1555 9.82738 12.1906L13.7016 15.0195C13.786 15.0805 13.9032 15.0641 13.9641 14.9797L14.6344 14.0656C14.6977 13.9789 14.6789 13.8617 14.5946 13.8031L11.2524 11.3867V5.58594C11.2524 5.48282 11.168 5.39844 11.0649 5.39844ZM16.2164 7.16094L19.8914 8.0586C20.0086 8.08672 20.1235 7.99766 20.1235 7.87813L20.1422 4.09297C20.1422 3.93594 19.9618 3.84688 19.8399 3.94532L16.1461 6.83047C16.1182 6.85207 16.097 6.8811 16.0848 6.91424C16.0727 6.94738 16.0701 6.98327 16.0775 7.0178C16.0848 7.05232 16.1017 7.08407 16.1263 7.10939C16.1509 7.13471 16.1821 7.15258 16.2164 7.16094ZM20.1469 14.218L18.818 13.7609C18.7717 13.7451 18.721 13.7479 18.6767 13.769C18.6325 13.79 18.5982 13.8275 18.5813 13.8734C18.5367 13.993 18.4899 14.1102 18.4407 14.2273C18.0235 15.2141 17.4258 16.1023 16.6618 16.8641C15.9062 17.622 15.0107 18.2261 14.025 18.643C13.0039 19.0747 11.9063 19.2963 10.7977 19.2945C9.67738 19.2945 8.59222 19.0766 7.57034 18.643C6.58466 18.2261 5.6892 17.622 4.93363 16.8641C4.17191 16.1023 3.57425 15.2141 3.15472 14.2273C2.72536 13.2057 2.50539 12.1082 2.50784 11C2.50784 9.87969 2.72581 8.79219 3.15941 7.77032C3.57659 6.7836 4.17425 5.89532 4.93831 5.1336C5.69389 4.37567 6.58934 3.77154 7.57503 3.35469C8.59222 2.9211 9.67972 2.70313 10.8 2.70313C11.9203 2.70313 13.0055 2.9211 14.0274 3.35469C15.0131 3.77154 15.9085 4.37567 16.6641 5.1336C16.9032 5.375 17.1282 5.62578 17.3344 5.89063L18.736 4.79375C16.8914 2.43594 14.0203 0.919534 10.7953 0.921878C5.17972 0.924221 0.670345 5.48516 0.726595 11.1031C0.782845 16.6227 5.27113 21.0781 10.8 21.0781C15.1477 21.0781 18.8508 18.3219 20.2617 14.4617C20.2969 14.3633 20.2453 14.2531 20.1469 14.218Z"
                                        fill="black" />
                                </svg>
                            </div>
                            <p>Reminder</p>
                        </div>
                    </a>
                </div>
                <div class="col p-2 d-flex justify-content-center align-items-center">
                    <a href="{{url('adm/daily-deposit')}}">
                        <div class="d-flex flex-column justify-content-center align-items-center text-center">
                            <div class="svg-wrapper">
                                <svg width="18" height="24" viewBox="0 0 18 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.5 3.31041V12.0001C7.5 12.199 7.42098 12.3898 7.28033 12.5304C7.13968 12.6711 6.94891 12.7501 6.75 12.7501C6.55109 12.7501 6.36032 12.6711 6.21967 12.5304C6.07902 12.3898 6 12.199 6 12.0001V3.31041L4.28063 5.03073C4.13989 5.17146 3.94902 5.25052 3.75 5.25052C3.55098 5.25052 3.36011 5.17146 3.21937 5.03073C3.07864 4.89 2.99958 4.69912 2.99958 4.5001C2.99958 4.30108 3.07864 4.11021 3.21937 3.96948L6.21937 0.969477C6.28903 0.899744 6.37175 0.844425 6.46279 0.806682C6.55384 0.768939 6.65144 0.749512 6.75 0.749512C6.84856 0.749512 6.94616 0.768939 7.03721 0.806682C7.12825 0.844425 7.21097 0.899744 7.28063 0.969477L10.2806 3.96948C10.4214 4.11021 10.5004 4.30108 10.5004 4.5001C10.5004 4.69912 10.4214 4.89 10.2806 5.03073C10.1399 5.17146 9.94902 5.25052 9.75 5.25052C9.55098 5.25052 9.36011 5.17146 9.21937 5.03073L7.5 3.31041ZM13.5 11.5895V9.0001C13.5 8.60228 13.342 8.22075 13.0607 7.93944C12.7794 7.65814 12.3978 7.5001 12 7.5001H10.5C10.3011 7.5001 10.1103 7.57912 9.96967 7.71977C9.82902 7.86042 9.75 8.05119 9.75 8.2501C9.75 8.44901 9.82902 8.63978 9.96967 8.78043C10.1103 8.92108 10.3011 9.0001 10.5 9.0001H12V16.5376C11.5527 16.0807 10.9541 15.8027 10.3164 15.7559C9.67877 15.7091 9.04594 15.8966 8.53672 16.2833C8.02751 16.6699 7.67692 17.2291 7.55077 17.8559C7.42462 18.4828 7.53157 19.1341 7.85156 19.6876L7.87406 19.7232L9.96094 22.9107C10.0698 23.0772 10.2404 23.1936 10.4351 23.2343C10.6298 23.275 10.8328 23.2367 10.9992 23.1278C11.1657 23.0189 11.2821 22.8483 11.3228 22.6536C11.3635 22.4588 11.3252 22.2559 11.2162 22.0895L9.14156 18.9217C8.99449 18.662 8.95661 18.3545 9.03626 18.0668C9.1159 17.7792 9.30654 17.535 9.56625 17.3879C9.82595 17.2408 10.1334 17.203 10.4211 17.2826C10.7087 17.3623 10.9529 17.5529 11.1 17.8126C11.1066 17.8248 11.1141 17.837 11.1216 17.8482L12.1228 19.3773C12.2113 19.5123 12.341 19.6151 12.4926 19.6705C12.6442 19.7259 12.8096 19.7309 12.9643 19.6848C13.119 19.6386 13.2546 19.5439 13.3512 19.4145C13.4477 19.2851 13.4999 19.1281 13.5 18.9667V13.5001C14.2069 14.1545 14.7715 14.9474 15.1586 15.8295C15.5457 16.7116 15.747 17.664 15.75 18.6273V22.5001C15.75 22.699 15.829 22.8898 15.9697 23.0304C16.1103 23.1711 16.3011 23.2501 16.5 23.2501C16.6989 23.2501 16.8897 23.1711 17.0303 23.0304C17.171 22.8898 17.25 22.699 17.25 22.5001V18.6235C17.2458 17.2342 16.9018 15.867 16.2482 14.641C15.5946 13.415 14.6512 12.3674 13.5 11.5895ZM3 7.5001H1.5C1.10218 7.5001 0.720644 7.65814 0.43934 7.93944C0.158035 8.22075 0 8.60228 0 9.0001V18.7501C0 18.949 0.0790178 19.1398 0.21967 19.2804C0.360322 19.4211 0.551088 19.5001 0.75 19.5001C0.948912 19.5001 1.13968 19.4211 1.28033 19.2804C1.42098 19.1398 1.5 18.949 1.5 18.7501V9.0001H3C3.19891 9.0001 3.38968 8.92108 3.53033 8.78043C3.67098 8.63978 3.75 8.44901 3.75 8.2501C3.75 8.05119 3.67098 7.86042 3.53033 7.71977C3.38968 7.57912 3.19891 7.5001 3 7.5001Z"
                                        fill="black" />
                                </svg>

                            </div>
                            <p>Deposit</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iconify/2.0.0/iconify.min.js"
        integrity="sha512-lYMiwcB608+RcqJmP93CMe7b4i9G9QK1RbixsNu4PzMRJMsqr/bUrkXUuFzCNsRUo3IXNUr5hz98lINURv5CNA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.dataTables.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function () {
                const icon = this.querySelector('.collapse-icon');
                icon.classList.toggle('bi-chevron-down');
                icon.classList.toggle('bi-chevron-up');
            });
        });

        $(document).ready(function() {
            $('.select2-with-search').select2({
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });

            $('.select2-no-search').select2({
                minimumResultsForSearch: -1,
                placeholder: "Select an option",
                width: '100%'
            });
            $('.select2-with-tags').select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: "Select an option",
                width: '100%'
            });
            $('.select2-multiple').select2({
                width: '100%',
                placeholder: "Select options",
                placeholder: "Select an option",
                allowClear: true
            });
        });

    </script>
    
</body>

</html>


   
<style>

.full-page-preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: #fff; 
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
}


.fancy-spinner {
  position: relative;
  width: 5rem;
  height: 5rem;
  display: flex;
  justify-content: center;
  align-items: center;
}
.fancy-spinner div {
  position: absolute;
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
}
.fancy-spinner div.ring {
  border-width: 0.5rem;
  border-style: solid;
  border-color: transparent;
  animation: 2s fancy infinite alternate;
}
.fancy-spinner div.ring:nth-child(1) {
  border-left-color: #ED2027;
  border-right-color: #ED2027;
}
.fancy-spinner div.ring:nth-child(2) {
  border-top-color: #ED2027;
  border-bottom-color: #ED2027;
  animation-delay: 1s;
}
.fancy-spinner div.dot {
  width: 1rem;
  height: 1rem;
  background: #ED2027;
}

@keyframes fancy {
  to {
    transform: rotate(360deg) scale(0.5);
  }
}
</style>

<div class="full-page-preloader" id="wurth-preloader">
  <div class="fancy-spinner">
    <div class="ring"></div>
    <div class="ring"></div>
    <div class="dot"></div>
  </div>
</div>

<script>
  window.addEventListener("load", function () {
    const preloader = document.getElementById("wurth-preloader");
    preloader.style.display = "none"; 
  });
</script>