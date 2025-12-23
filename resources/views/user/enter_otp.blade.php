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
                            <p class="login-title text-center">ENTER OTP</p>
                            <div class="mb-3 w-100">
                                <div class="row row-cols-4 d-flex flex-row">
                                <div class="otp-inputs col">
                                    <input type="text" class="form-control otp-input" maxlength="1" name="no_1" oninput="handleInput(this)" />
                                </div>
                                <div class="otp-inputs col">
                                    <input type="text" class="form-control otp-input" maxlength="1" name="no_2" oninput="handleInput(this)" />
                                </div>
                                <div class="otp-inputs col">
                                    <input type="text" class="form-control otp-input" maxlength="1" name="no_3" oninput="handleInput(this)" />
                                </div>
                                <div class="otp-inputs col">
                                    <input type="text" class="form-control otp-input" maxlength="1" name="no_4" oninput="handleInput(this)" />
                                </div>
                                </div>
                            </div>

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
        function handleInput(element) {
          element.value = element.value.replace(/[^0-9]/g, "");
      
          const parentDiv = element.parentElement;
          const nextDiv = parentDiv.nextElementSibling;
          if (nextDiv && element.value !== "") {
            const nextInput = nextDiv.querySelector("input");
            if (nextInput) {
              nextInput.focus();
            }
          }
        }
      
        document.querySelectorAll(".otp-input").forEach((input) => {
          input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value) {
              const parentDiv = input.parentElement;
              const prevDiv = parentDiv.previousElementSibling;
              if (prevDiv) {
                const prevInput = prevDiv.querySelector("input");
                if (prevInput) {
                  prevInput.focus();
                }
              }
            }
          });
        });
      </script>
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