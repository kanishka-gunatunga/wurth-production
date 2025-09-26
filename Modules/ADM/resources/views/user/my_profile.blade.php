@include('adm::layouts.header')
<div class="content">
            <div class="d-flex w-100 text-start justify-content-between align-items-center mb-3">
                <h3 class="page-title mb-0">My Profile</h3>
                <a href="{{url('adm/edit-profile')}}" class="my-3 small-button">
                    Edit profile
                </a>
            </div>
            <!-- row 2 -->
            <div class="d-flex flex-column justify-content-center align-items-center">
                <?php if($other_details->profile_picture == ''){ ?>
                        <img id="profilePic" src="{{ asset('adm_assets/assests/profile-pic.jpg') }}" alt="Logo" class="img-fluid profile-pic-offcanvas mb-3">
                    <?php } else { ?>
                        <img id="profilePic" src="{{ asset('db_files/user_profile_images/'.$other_details->profile_picture.'') }}" alt="Logo" class="img-fluid profile-pic-offcanvas mb-3">
                    <?php } ?>
                <p class="red-title-22 mb-1">{{$other_details->name}}</p>
                <p class="gray-text-13 mb-1">Sales Representative</p>
                <p class="gray-text-13 mb-5">{{$other_details->adm_number}}</p>

                <div class="d-flex flex-row w-100">
                    <div class="col-6 d-flex flex-column justify-content-center align-items-center text-center">
                        <p class="red-title-22 mb-1">{{$all_invoices->count()}}</p>
                        <p class="gray-text-13 mb-4">No. of Invoices</p>
                    </div>
                    <div class="col-6 d-flex flex-column justify-content-center align-items-center text-center">
                        <p class="red-title-22 mb-1">{{$customers->count()}}</p>
                        <p class="gray-text-13 mb-4">Total Customers</p>
                    </div>
                </div>
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <p class="red-title-22 mb-1">Rs 587,232</p>
                    <p class="gray-text-13 mb-4">Total Outstanding Balance</p>

                    <p class="red-title-22 mb-1">Rs 93,500</p>
                    <p class="gray-text-13 mb-4">Pending Cash Deposits</p>

                    <p class="red-title-22 mb-1">Rs 120,000</p>
                    <p class="gray-text-13">Pending Cheque Deposits</p>
                </div>
            </div>
        </div>
        @include('adm::layouts.footer')
