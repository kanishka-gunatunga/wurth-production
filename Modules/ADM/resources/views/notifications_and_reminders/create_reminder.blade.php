@include('adm::layouts.header')
<?php
use App\Models\UserDetails;
$name = UserDetails::where('user_id',Auth::user()->id)->value('name');
?>
 <div class="d-flex flex-row px-3 mt-4 justify-content-between align-items-center w-100 text-start pt-2 mb-0">
            <h3 class="page-title">Create Reminder</h3>
        </div>
        <!-- body content -->
        <form id="profileForm" class="content  needs-validation p-2" novalidate action="" method="post"  enctype="multipart/form-data">
        @csrf
            <!-- row 2 -->
            <div class=" scrollable-section">
                <div class="d-flex flex-column justify-content-center align-items-center p-2">
                    <div class="mb-3 w-100 ">


                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="send_from">Send From</label>
                            <input type="text" class="form-control" placeholder="" name="send_from" id="send_from"
                                required value="{{$name}}" />
                                @if($errors->has("send_from")) <div class="alert alert-danger mt-2">{{ $errors->first('send_from') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="send_from">Reminder Title</label>
                            <input type="text" class="form-control" placeholder="" name="reminder_title" id="reminder_title"
                                required  />
                                @if($errors->has("reminder_title")) <div class="alert alert-danger mt-2">{{ $errors->first('reminder_title') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                        <label for="reminder_date">Reminder Type</label>
                        <select class="select2-no-search" name="reminder_type">
                        <option ></option>
                        <option value="Self">Self</option>
                        <option value="Other">Other</option>
                        </select>
                            @if($errors->has("reminder_type")) <div class="alert alert-danger mt-2">{{ $errors->first('reminder_type') }}</div>@endif
                        </div>
                        <div class="input-group-profile d-flex flex-column mb-3">
                        <label for="reminder_date">Send To</label>
                        <select class="select2-with-search" name="send_to">
                        <option ></option>
                        <?php foreach($users as $user){ 
                        if( $user->id != Auth::user()->id){
                        ?> 
                         <option value="{{$user->userDetails->user_id}}">{{$user->userDetails->name}}</option>
                        <?php  }} ?>
                        </select>
                            @if($errors->has("send_to")) <div class="alert alert-danger mt-2">{{ $errors->first('send_to') }}</div>@endif
                        </div>
                        
                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="reminder_date">Reminder Date</label>
                            <input type="date" class="form-control" placeholder="" name="reminder_date" required />
                            <div class="invalid-feedback">
                                Reminder Date is required
                            </div>
                        </div>

                        <div class="input-group-profile d-flex flex-column mb-3">
                            <label for="name">Reason</label>
                            <textarea type="text" style="border-radius: 8px !important;" class="form-control" rows="6"
                                placeholder="Enter the reason" name="reason" required></textarea>
                            <div class="invalid-feedback">
                                Reason is required
                            </div>
                        </div>

                        <div class="d-flex w-100 justify-content-center align-items-center pt-3">
                            <button class="styled-button-normal w-100 px-5"
                                style="width: 100% !important; font-size: 14px !important; font-weight: 600; height: 40px !important; min-height: 40px !important"
                                type="submit">Send Reminder</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    @include('adm::layouts.footer')
     
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