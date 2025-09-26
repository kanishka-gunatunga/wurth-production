@include('layouts.dashboard-header')
<div class="container-fluid">
            <div class="main-wrapper">
            @if(Session::has('success')) <div class="alert alert-success mt-2 mb-2">{{ Session::get('success') }}</div>@endif
                    @if(Session::has('fail')) <div class="alert alert-danger mt-2 mb-2">{{ Session::get('fail') }}</div>@endif

                <form class="" action="" method="post">
                @csrf
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-6 col-12">
                        <h1 class="header-title">Access Control</h1>
                    </div>
                   
                </div>

                    <div class="mb-4 col-12 col-lg-6">
                        <label for="head-of-division-select" class="form-label custom-input-label">User
                            Level
                        </label>
                        <select class="form-select custom-input" aria-label="Default select example"
                            id="head-of-division-select" name="user_role">
                            <option value="1">System Administrator</option>
                            <option value="2">Head of Division</option>
                            <option value="3">Regional Sales Manager</option>
                            <option value="4">Area Sales Manager</option>
                            <option value="5">Team Leader</option>
                            <option value="6">ADM (Sales Rep)</option>
                            <option value="7">Finance Manager</option>
                        </select>
                    </div>


                    <div class="row" id="role_permissions">
                    </div>
               


                <div class="col-12 d-flex justify-content-end division-action-btn gap-3">
                    <button class="btn btn-danger submit">Update User</button>
                </div>
            </div>
            </form>
        </div>



</body>

</html>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchPermissions();

        const userRoleSelect = document.getElementById('head-of-division-select');
        userRoleSelect.addEventListener('change', function () {
            fetchPermissions();
        });

        function fetchPermissions() {
            const userRole = document.getElementById('head-of-division-select').value;

            $.ajax({
            url: '{{url('get-role-permissions')}}',
            type: 'POST',
            data: {
                user_role: userRole,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
            $('#role_permissions').html(response);
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
            });
        }
    });
</script>
