@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'course/';

$courses = CustomHelper::getCourses();

?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Subscription History</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Subscription History</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post">
      @csrf
      <div class="student-group-form ">
      <div class="row">

        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <select class="form-control" name="course_id">
              <option value=""selected>Select Course</option>
              <?php if(!empty($courses)){
                foreach($courses as $course){
                ?>
                <option value="{{$course->id??''}}" <?php if($course->id == $course_id) echo "selected";?>>{{$course->name??''}}</option>

              <?php }}?>
            </select>
          </div>
        </div>

        <div class="col-lg-6 col-md-6">
          <div class="form-group">
            <input type="text" name="search" value="{{$search??''}}" class="form-control" placeholder="Search  ...">
          </div>
        </div>
        
          <!--  <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Phone ...">
          </div>
        </div> -->
        <div class="col-lg-2">
          <div class="search-student-btn">
            <button type="submit"  class="btn btn-primary">Search</button>
          </div>
        </div>
      </div>
    </div>
    </form>
    <div class="row">
      <div class="col-sm-12">
        <div class="card card-table comman-shadow">
          <div class="card-body">

            <div class="page-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="page-title">Subscription History</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
             
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                      <th>SNo.
                        <th>User Details</th>
                        <th>Course / Book Details</th>
                        <th>#TXN No</th>
                        <th>Type</th>
                        <th>Course / Book </th>
                        <th>Start Date</th>
                        <th>End Date</th>
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
                            if($subscription->type == 'course'){
                              $course = CustomHelper::getCourseDetails($subscription->type_id);
                            }
                          ?>
                          <tr>
                            <td>{{$i++}}</td>
                            <td>{{$subscription->name??''}} <br>{{$subscription->mobile_number??''}} <br> {{$subscription->email??''}}</td>

                            <td>{{$course->name??''}} </td>

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
              {{ $subscription_history->appends(request()->input())->links('admin.pagination') }}

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @endsection
