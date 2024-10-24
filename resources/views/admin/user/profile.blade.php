@extends('admin.layouts.layouts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';



$image = CustomHelper::getImageUrl('userImg',$users->image);
$seed_license = CustomHelper::getImageUrl('seedLicenseImg',$users->seed_license);
$pesticide_license = CustomHelper::getImageUrl('pesticideLicenseImg',$users->pesticide_license);
$fertilizer_license = CustomHelper::getImageUrl('fertilizerLicense',$users->fertilizer_license);
$pan_front_image = CustomHelper::getImageUrl('panImg',$users->pan_front_image);
$adhar_back_image = CustomHelper::getImageUrl('adharBackImg',$users->adhar_back_image);
$adhar_front_image = CustomHelper::getImageUrl('adharFrontImg',$users->adhar_front_image);
$blank_cheque = CustomHelper::getImageUrl('blankCheque',$users->blank_cheque);
$shopVideo = CustomHelper::getImageUrl('shopVideo',$users->shopVideo);
if(empty($image)){
  $image = url('public/storage/settings/favicon.png');
}
$groupName = DB::table('all_categories')->where('id', $users->group_id)->first();
$goatraName = DB::table('goatra')->where('id', $users->gotra_id)->first();
$headOfFamilyName = DB::table('users')->where('id', $users->head_of_family)->first();

$subsCourseIds = '';
// $subsCourseIds = \App\Models\SubscriptionHistory::where('type','course')->where('user_id',$users->id)->where('end_date','>=',date('Y-m-d'))->pluck('type_id')->toArray();
// $courses = \App\Models\Course::whereNotIn('id',$subsCourseIds)->where('status',1)->where('is_delete',0)->get();
$courses = '';

?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col">
          <h3 class="page-title">Profile</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item active">Profile</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="profile-header">
          <div class="row align-items-center">
            <div class="col-auto profile-image">
              <a href="{{ url('public\assets\img\userimg.jpg')}}" target="_blank">
                <img class="rounded-circle" alt="User Image" src="{{ url('public\assets\img\userimg.jpg')}}">
              </a>
            </div>
            <div class="col ms-md-n2 profile-user-info">
              <h4 class="user-name mb-0">{{$users->name??''}}</h4>
              <h5 class="user-name mb-0">{{$users->email??''}}</h5>
              <h5 class="user-name mb-0">+91 {{$users->phone??''}}</h5>

              <!-- <div class="user-Location"><i class="fas fa-map-marker-alt"></i> {{$users->address??''}}</div> -->
              <!-- <div class="about-text">Lorem ipsum dolor sit amet.</div> -->
            </div>
            
        </div>
      </div>
      <div class="profile-menu">
        <ul class="nav nav-tabs nav-tabs-solid">
          <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">User Profile</a>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#subscriptions">Order History</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#transactions">Transactions</a>
          </li> -->


         <!--  <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Password</a>
          </li> -->
        </ul>
      </div>
      <div class="tab-content profile-tab-cont">
        <div class="tab-pane fade show active" id="per_details_tab">

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title d-flex justify-content-between">
                    <span>Personal Details</span>
                    <!-- <a class="edit-link" data-bs-toggle="modal"  data-bs-target="#edit_personal_details" href="#"><i class="far fa-edit me-1"></i>Edit</a> -->
                  </h5>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name:</p>
                    <p class="col-sm-9">{{$users->name??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email ID:</p>
                    <p class="col-sm-9">{{$users->email??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile:</p>
                    <p class="col-sm-9">{{$users->phone??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Head Of Family:</p>
                    @if(!empty($headOfFamilyName))
                    <p class="col-sm-9">{{$headOfFamilyName->name??''}}</p>
                    @else
                    <p class="col-sm-9">Self</p>
                    @endif
                  </div>
                  @if(!empty($headOfFamilyName))
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Relation With Head:</p>
                    <p class="col-sm-9">{{$users->relation_with_head??''}}</p>
                  </div>
                  @endif
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Date Of Birth:</p>
                    <p class="col-sm-9">{{$users->dob??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Native Village:</p>
                    <p class="col-sm-9">{{$users->ntv_vlg??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Blood Group:</p>
                    <p class="col-sm-9">{{$users->bld_group??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Marital Status:</p>
                    <p class="col-sm-9">{{$users->maritl_status??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Education:</p>
                    <p class="col-sm-9">{{$users->education??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Gotra:</p>
                    <p class="col-sm-9">{{$goatraName->name??''}}</p>
                  </div>
                  <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Group Name:</p>
                    <p class="col-sm-9">{{$groupName->name??''}}</p>
                  </div>
                  <!-- <div class="row">
                    <p class="col-sm-3 text-muted text-sm-end mb-0">Documents:</p>
                    <div class="col-auto profile-image" >
                    <a href="{{$image}}" target="_blank">
                      <img class="" width="80px" alt="User Image" src="{{$image}}">
                    </a>
                  </div> -->
                  <!-- <div class="col-auto profile-image" >
                    <a href="{{$seed_license}}" target="_blank">
                      <img class="" width="80px" alt="Seed License" src="{{$seed_license}}">
                    </a>
                  </div>
                  <div class="col-auto profile-image" >
                    <a href="{{$pesticide_license}}" target="_blank">
                      <img class="" width="80px" alt="User Image" src="{{$pesticide_license}}">
                    </a>
                  </div>
                  <div class="col-auto profile-image" >
                    <a href="{{$fertilizer_license}}" target="_blank">
                      <img class="" width="80px" alt="User Image" src="{{$fertilizer_license}}">
                    </a>
                  </div>
                  <div class="col-auto profile-image" >
                    <a href="{{$pan_front_image}}" target="_blank">
                      <img class="" width="80px" alt="User Image" src="{{$pan_front_image}}">
                    </a>
                  </div>
                  <div class="col-auto profile-image" >
                    <a href="{{$adhar_back_image}}" target="_blank">
                      <img class="" width="80px" alt="User Image" src="{{$adhar_back_image}}">
                    </a>
                  </div>
                  <div class="col-auto profile-image" >
                    <a href="{{$adhar_front_image}}" target="_blank">
                      <img class="" width="80px" alt="User Image" src="{{$adhar_front_image}}">
                    </a>
                  </div>
                  <div class="col-auto profile-image" >
                    <a href="{{$blank_cheque}}" target="_blank">
                      <img class="" width="80px" alt="User Image" src="{{$blank_cheque}}">
                    </a>
                  </div>
                  <div class="col-auto profile-image" >
                    <a href="{{$shopVideo}}" target="_blank">
                      <video width="80px" alt="User Image" src="{{$shopVideo}}">
                    </a>
                  </div>
                  </div> -->
                </div>
              </div>
            </div>
            <!-- <div class="col-lg-3">

              <div class="card">
                <div class="card-body">
                  <h5 class="card-title d-flex justify-content-between">
                    <span>Credit Limit</span>
                    <a class="edit-link" href="#" data-bs-toggle="modal" data-bs-target="#credit_modal"><i class="far fa-edit me-1"></i> Edit</a>

                  </h5>
                  <button class="btn btn-primary" type="button"><i class="fe fe-check-verified"></i>₹ <span id="amount">{{$users->credit_limit??0}}</span></button>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title d-flex justify-content-between">
                    <span>Account Status</span>
                    <a class="edit-link" href="#" data-bs-toggle="modal" data-bs-target="#account_status"><i class="far fa-edit me-1"></i> Edit</a>
                  </h5>
                  <button class="btn @if($users->status == 1) btn-primary @else btn-danger  @endif" type="button"><i class="fe fe-check-verified"></i>@if($users->status == 1) Active @else Inactive @endif</button>
                </div>
              </div>


            </div> -->
          </div>
        </div>
        <div id="subscriptions" class="tab-pane fade">
          <div class="card">

            <div class="card-body">
             <div class="col-auto text-end float-end ms-auto download-grp">
              <!-- <a data-bs-toggle="modal"  data-bs-target="#subscription_modal" class="btn btn-primary"><i class="fas fa-plus"></i></a> -->
            </div>
            <h5 class="card-title">Order History</h5>

            <div class="row mt-4">
              <div class="col-md-10 col-lg-12">
                <div class="table-responsive">
                  <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                    <thead class="student-thread">
                      <tr>
                        <th>SNo.
                        <th>#TXN No</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($subscription_history)){
                        $i = 1;
                        foreach($subscription_history as $subscription){
                          ?>
                          <tr>
                            <td>{{$i++}}</td>
                            <td>{{$subscription->txn_no??''}}</td>
                            <td>{{ucfirst($subscription->type)??''}}</td>
                            <?php if($subscription->type == 'course'){?>
                              <td>{{CustomHelper::getCourseName($subscription->type_id)}}</td>
                            <?php }else{?>
                              <td></td>
                            <?php }?>
                            <td>{{ucfirst($subscription->start_date)??''}}</td>
                            <td>{{ucfirst($subscription->end_date)??''}}</td>
                            <td>{{$subscription->amount??''}}</td>
                            <td>{{$subscription->payment_cause??''}}</td>
                            <td>{{date('d M Y h:i A',strtotime($subscription->created_at))??''}}</td>
                            <td>
                                <div class="actions ">
                              <a data-bs-toggle="modal"  data-bs-target="#subscription_update_modal{{$subscription->id}}" class="btn btn-sm bg-success-light me-2 "><i class="feather-edit"></i></a>
                            </div>

                             


                            </td>

                          </tr>


                          <!-- //////////////subscription_update_modal////////////////////////////////////////////////// -->

                          <div id="subscription_update_modal{{$subscription->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="standard-modalLabel">{{$users->name??''}}</h4>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route($routeName.'.user.update_subs_enddate') }}" method="post" >
                                  @csrf
                                  <input type="hidden" name="subscription_id" value="{{$subscription->id}}">
                                  <div class="modal-body">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <label>Course Name</label>
                                        <h6 class="text-wrap">{{CustomHelper::getCourseName($subscription->type_id)}}</h6>
                                      </div>

                                      <div class="col-md-12 mt-2">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" value="{{$subscription->end_date}}" class="form-control" >
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button  class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button  type="submit" class="btn btn-primary">Save changes</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>



                          <!-- ///////////////////////////////////////////////// -->











                        <?php }}?>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div id="transactions" class="tab-pane fade">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Transaction History</h5>
              <div class="row">
                <div class="col-md-12 col-lg-12">
                  <div class="table-responsive">
                    <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                      <thead class="student-thread">
                        <tr>
                          <th >SNo.
                          <th >TXN No</th>
                          <th >Remarks</th>
                          <th >Type</th>
                          <th >Amount</th>
                          <th >Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($transactions)){
                          $i = 1;
                          foreach($transactions as $transaction){
                            ?>
                            <tr>
                              <td>{{$i++}}</td>
                              <td>{{$transaction->txn_no??''}}</td>
                              <td>{{$transaction->reason??''}}</td>
                              <td>{{ucfirst($transaction->type)??''}}</td>
                              <td>{{$transaction->amount??''}}</td>
                              <td>{{date('d M Y h:i A',strtotime($transaction->created_at))??''}}</td>
                            </tr>

                          <?php }}?>
                        </tbody>
                      </table>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- <div id="password_tab" class="tab-pane fade">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Change Password</h5>
                <div class="row">
                  <div class="col-md-10 col-lg-6">
                    <form>
                      <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control">
                      </div>
                      <button class="btn btn-primary" type="submit">Save Changes</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div> -->

        </div>
      </div>
    </div>
  </div>
</div>


<!-- ////////////////////////////////////////////////////////////////////////////////// -->
<!-- account_status Modal -->
<div id="account_status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="standard-modalLabel">{{$users->name??''}} <button class="btn @if($users->status == 1) btn-primary @else btn-danger  @endif" type="button"><i class="fe fe-check-verified"></i>@if($users->status == 1) Active @else Inactive @endif</button></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="status_form" >

        <input type="hidden" name="id" value="{{$users->id}}">
        <div class="modal-body">
          <div class="row">
            

            <div class="col-md-12">
              <label>Account Status</label>
              <select class="form-control" name="status">
                <option @if($users->status == 1) selected @endif value="1">Active</option>
                <option @if($users->status == 0) selected @endif value="0">Inactive</option>
              </select>
            </div>

            

          </div>
        </div>
        <div class="modal-footer">
          <button  class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button  type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- /////////////////////////////////////////////////////////////////////// -->


<!-- ////////////////////////////////////////////////////////////////////////////////// -->
<!-- wallet Modal -->
<div id="credit_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="standard-modalLabel">{{$users->name??''}} - {{$users->credit_limit??0}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="wallet_modal_form" >

        <input type="hidden" name="user_id" value="{{$users->id}}">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label>Amount</label>
              <input type="number" name="amount" value="" class="form-control" placeholder="Enter Amount">
            </div>

            <div class="col-md-12">
              <label>Type</label>
              <select class="form-control" name="type">
                <option value="credit">Credit</option>
              </select>
            </div>

            <div class="col-md-12">
              <label>Remarks</label>
              <textarea class="form-control" name="remarks" placeholder="Enter Remarks"></textarea>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button  class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button  type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- /////////////////////////////////////////////////////////////////////// -->


<!-- ////////////////////////////////////////////////////////////////////////////////// -->
<!-- subscription_modal Modal -->
<div id="subscription_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="standard-modalLabel">Subscription History - {{$users->name??''}}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="subscription_modal_form"  >

        <input type="hidden" name="user_id" value="{{$users->id}}">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label>Choose Course</label>
              <select class="form-control" name="course_id">
                <option value="" selected>Select Course</option>
                <?php if(!empty($courses)){
                  foreach($courses as $course){
                    ?>
                    <option value="{{$course->id}}">{{$course->name??''}}</option>
                  <?php }}?>
                </select>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button  class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <button  type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>



  <!-- /////////////////////////////////////////////////////////////////////// -->





  <!-- ////////////////////////////////////////////////////////////////////////////////// -->
  <!-- wallet Modal -->
  <div id="edit_personal_details" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="standard-modalLabel">Update Profile - {{$users->name??''}} </h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="update_profile_modal_form" enctype="multipart/form-data">

          <input type="hidden" name="user_id" value="{{$users->id}}">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 mb-1">
                <label>Name</label>
                <input type="text" name="name" value="{{$users->name??''}}" class="form-control" placeholder="Enter Name">
              </div>


              <div class="col-md-12 mb-1">
                <label>Email</label>
                <input type="text" name="email" value="{{$users->email??''}}" class="form-control" placeholder="Enter Email">
              </div>


              <div class="col-md-12 mb-1">
                <label>Phone</label>
                <input type="text" name="mobile_number" value="{{$users->mobile_number??''}}" class="form-control" placeholder="Enter Phone">
              </div>

              <div class="col-md-12 mb-1">
                <label>DOB</label>
                <input type="date" name="dob" value="{{$users->dob??''}}" class="form-control" placeholder="Enter Phone">
              </div>


              <div class="col-md-12 mb-1">
                <label>Profile Image</label>
                <input type="file" name="image" value="" class="form-control" placeholder="Enter Phone">
              </div>



            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>



  <!-- /////////////////////////////////////////////////////////////////////// -->



  @endsection

  <script>


    function change_users_status(user_id){
      var status = $('#change_users_status'+user_id).val();
      var _token = '{{ csrf_token() }}';
      $.ajax({
        url: "{{ route($routeName.'.user.change_users_status') }}",
        type: "POST",
        data: {id:user_id, status:status},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){
          if(resp.success){
            alert(resp.message);
          }else{
            alert(resp.message);

          }
        }
      });


    }



  </script>

  <script type="text/javascript">
  //////////Subscription
   $(document).ready(function() {
    $('#subscription_modal_form').on('submit', function(event) {
      event.preventDefault();
      var _token = '{{ csrf_token() }}';
      let subsInfo = $(this).serializeArray();
      let subsdata = {};
      subsInfo.forEach((value) => {
        subsdata[value.name] = value.value;
      });
      let url = "{{ route($routeName.'.user.free_subscription') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: subsdata,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          console.log(resp.message);
          if(resp.status){
            location.reload();
            // $('#amount').html(resp.amount);
            
            // $('#wallet_modal').modal('hide');
            // alert(resp.message);
          }else{
           var messagees = resp.message;
           var errormessage = '';
           for ( var i = 0; i < messagees.length; i++ ) {
            var m = messagees[i];
            if(m !=''){
              errormessage = errormessage+'\n' +m
            }
          }
          if(errormessage !=''){
            alert(errormessage);
          }

        }
      }
    })
    });
  });






   $(document).ready(function() {
    $('#wallet_modal_form').on('submit', function(event) {
      event.preventDefault();
      var _token = '{{ csrf_token() }}';
      let walletInfo = $(this).serializeArray();
      let walletdata = {};
      walletInfo.forEach((value) => {
        walletdata[value.name] = value.value;
      });
      let url = "{{ route($routeName.'.user.credit_update') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: walletdata,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          if(resp.status){

            $('#amount').html(resp.amount);
            
            $('#credit_modal').modal('hide');
            // alert(resp.message);
          }else{
           var messagees = resp.message;
           var errormessage = '';
           for ( var i = 0; i < messagees.length; i++ ) {
            var m = messagees[i];
            if(m !=''){
              errormessage = errormessage+'\n' +m
            }
          }
          if(errormessage !=''){
            alert(errormessage);
          }

        }
      }
    })
    });
  });


  $(document).ready(function() {
    $('#status_form').on('submit', function(event) {
      event.preventDefault();
      var _token = '{{ csrf_token() }}';
      let status = $(this).serializeArray();
      let walletdata = {};
      status.forEach((value) => {
        status[value.name] = value.value;
      });
      let url = "{{ route($routeName.'.user.change_users_status') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: status,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          if(resp.success){

            location.reload();
            // alert(resp.message);
          }else{
           var messagees = resp.message;
           var errormessage = '';
           for ( var i = 0; i < messagees.length; i++ ) {
            var m = messagees[i];
            if(m !=''){
              errormessage = errormessage+'\n' +m
            }
          }
          if(errormessage !=''){
            alert(errormessage);
          }

        }
      }
    })
    });
  });






  ///////////////////////////////////////////////////////////////////



   $(document).ready(function() {
    $('#update_profile_modal_form').on('submit', function(event) {
      event.preventDefault();
      var _token = '{{ csrf_token() }}';
      let userInfo = $(this).serialize();


      console.log(userInfo);
      let userdata = {};
      // userInfo.forEach((value) => {
      //   userdata[value.name] = value.value;
      // });

      var form = $('#update_profile_modal_form')[0];
      var formData = new FormData(form);
      let url = "{{ route($routeName.'.user.update_profile') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: formData,
        processData: false,
        contentType: false,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          console.log(resp.message);
          if(resp.status){

            $('#edit_personal_details').modal('hide');
            location.reload();
            
          }else{
           var messagees = resp.message;
           var errormessage = '';
           for ( var i = 0; i < messagees.length; i++ ) {
            var m = messagees[i];
            if(m !=''){
              errormessage = errormessage+'\n' +m
            }
          }
          if(errormessage !=''){
            alert(errormessage);
          }

        }
      }
    })
    });
  });
</script>