@include('layouts.simple-header')
<body>
    <div class="d-flex align-items-center justify-content-center login-div">
        <div class="card login-card d-flex align-items-center">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex justify-content-center mb-4">
                        <img src="{{ asset('assets/images/wruth-logo.png') }}" alt="Logo" class="logo">
                    </div>
                    <div>

                        <form class="" action="" method="post">
                        @csrf
                            <p class="login-title text-center">PASSWORD RESET</p>
                            
                            <div class="my-5 input-div">
                            <img src="{{ asset('assets/images/Email.png') }}" alt="" class="input-icon envelop">
                                <input type="email" class="form-control login-input " name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter The Email to Get Password Reset Link">
                            </div>
                            @if($errors->has("email")) <div class="alert alert-danger ">{{ $errors->first('email') }}</div>@endif


                            <button type="submit" class="btn login-btn">Submit</button>
                           
                          </form>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    $(document).ready(function() {
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('fail'))
        toastr.error("{{ Session::get('fail') }}");
        @endif
    });
</script>