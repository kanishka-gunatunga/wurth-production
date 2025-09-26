@include('layouts.dashboard-header')
<?php
use App\Models\UserDetails;
?>
<div class="container-fluid">
            <div class="main-wrapper">
                <div class="p-4 pt-0">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">Division Management</h1>
                    </div>
                    @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                    @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif

                    <form class="" action="" method="post">
                    @csrf
                        
                        <div class="row d-flex justify-content-between">
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="division-input" class="form-label custom-input-label">
                                Division Name</label>
                                <input type="text" class="form-control custom-input" id="division-input" placeholder="Division Name" name="division_name" value="{{old('division_name')}}">
                                @if($errors->has("division_name")) <div class="alert alert-danger mt-2">{{ $errors->first('division_name') }}</div>@endif
                            </div>
    
                            <div class="mb-4 col-12 col-lg-6">
                                <label for="head-of-division-select" class="form-label custom-input-label">User
                                Head of Division
                                </label>
                                <select class="form-select custom-input" aria-label="Default select example"
                                    id="head-of-division-select" name="head_of_division">
                                    <?php if(old('head_of_division')){ ?> 
                                        <option selected hidden value="{{old('head_of_division')}}">{{UserDetails::where('user_id', old('head_of_division'))->value('name')}}</option>
                                    <?php } ?>
                                   <?php foreach($division_heads as $division_head){ ?> 
                                        <option value="{{$division_head->id}}">{{$division_head->userDetails->name}}</option>
                                    <?php } ?>
                                </select>
                                @if($errors->has("head_of_division")) <div class="alert alert-danger mt-2">{{ $errors->first('head_of_division') }}</div>@endif
                            </div>

                            <div class="mb-4 col-12 col-lg-12">
                                <label for="division-input" class="form-label custom-input-label">Division Description</label>
                                <textarea class="form-control division-description" placeholder="Division Description" style="height: 152px" name="division_description">{{old('division_description')}}</textarea>
                                @if($errors->has("division_description")) <div class="alert alert-danger mt-2">{{ $errors->first('division_description') }}</div>@endif
                            </div>

                            

                        </div>


                        <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                            <a href="{{url('division-managment')}}"><button type="button" class="btn btn-dark cancel">Cancel</button></a>
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


   