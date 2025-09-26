@include('layouts.dashboard-header')
<?php
use App\Models\UserDetails;
?>
<div class="container-fluid">
            <div class="main-wrapper">
                <div class="p-4 pt-0">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">Add Customer</h1>
                    </div>
                    @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                    @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif

                    <form class="" action="" method="post">
                    @csrf
                        
                    <div class="row d-flex justify-content-between">
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Customer ID</label>
                                <input type="text" class="form-control custom-input" id="division-input" placeholder="Customer ID"  name="customer_id" value="{{old('customer_id')}}">
                                @if($errors->has("customer_id")) <div class="alert alert-danger mt-2">{{ $errors->first('customer_id') }}</div>@endif
                            </div>
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Full Name</label>
                                <input type="text" class="form-control custom-input" id="division-input" placeholder="Full Name" name="name" value="{{old('name')}}">
                                @if($errors->has("name")) <div class="alert alert-danger mt-2">{{ $errors->first('name') }}</div>@endif
                            </div>
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Address</label>
                                <input type="text" class="form-control custom-input" id="division-input" placeholder="Address" name="address" value="{{old('address')}}">
                                @if($errors->has("address")) <div class="alert alert-danger mt-2">{{ $errors->first('address') }}</div>@endif
                            </div>
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Mobile Number</label>
                                <input type="tel" class="form-control custom-input" id="division-input" placeholder="Mobile Number" name="mobile_number" value="{{old('mobile_number')}}">
                                @if($errors->has("mobile_number")) <div class="alert alert-danger mt-2">{{ $errors->first('mobile_number') }}</div>@endif
                            </div>
                            
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">Email</label>
                                <input type="email" class="form-control custom-input" id="division-input" placeholder="Email" name="email" value="{{old('email')}}">
                                @if($errors->has("email")) <div class="alert alert-danger mt-2">{{ $errors->first('email') }}</div>@endif
                            </div>

                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">WhatsApp Number</label>
                                <input type="tel" class="form-control custom-input" id="division-input" placeholder="WhatsApp Number" name="whatsapp_number" value="{{old('whatsapp_number')}}">
                                @if($errors->has("whatsapp_number")) <div class="alert alert-danger mt-2">{{ $errors->first('whatsapp_number') }}</div>@endif
                            </div>
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="head-of-division-select" class="form-label custom-input-label">Select ADM
                                </label>
                                <select class="form-select custom-input" aria-label="Default select example"
                                    id="head-of-division-select" name="adm">
                                    <?php if(old('adm')){ ?> 
                                        <option selected hidden value="{{old('adm')}}">{{UserDetails::where('adm_number', old('adm'))->value('name')}}</option>
                                    <?php } ?>
                                   <?php foreach($adms as $adm){ ?> 
                                        <option value="{{$adm->userDetails->adm_number}}">{{$adm->userDetails->name}}</option>
                                    <?php } ?>
                                </select>
                                @if($errors->has("adm")) <div class="alert alert-danger mt-2">{{ $errors->first('adm') }}</div>@endif
                            </div>

                            <div class="mb-4 col-12 col-lg-6">
                                <label for="head-of-division-select" class="form-label custom-input-label">Available Time for Contact
                                </label>
                                <input type="time" class="form-control custom-input" id="division-input"  name="avilable_time" value="{{old('avilable_time')}}">
                                @if($errors->has("avilable_time")) <div class="alert alert-danger mt-2">{{ $errors->first('avilable_time') }}</div>@endif
                            </div>

                            
                        </div>


                        <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                            <a href="{{url('customers')}}"><button type="button" class="btn btn-dark cancel">Cancel</button></a>
                            <button type="submit" class="btn btn-danger submit">Submit</button>
                        </div>

                </div>

                </form>
            </div>

        </div>
    </div>
    </div>
</body>

</html>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>


   