@extends('admin.layouts.layouts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';

$storage = Storage::disk('public');


$image = CustomHelper::getImageUrl('course',$course->image);
if(empty($image)){
  $image = url('public/storage/settings/favicon.png');
}


$sessions = session()->all();
$key = $sessions['key']??'';

$live_class_types = CustomHelper::getLiveClassTypes();
$faculties = CustomHelper::getFaculties();


?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col">
          <h3 class="page-title">Contents - {{$course->name??''}}</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item active">Contents</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="profile-header">
          <div class="row align-items-center">
            <div class="col-auto profile-image">
              <a href="{{$image}}" target="_blank">
                <img class="rounded-circle" alt="User Image" src="{{$image}}">
              </a>
            </div>
            <div class="col ms-md-n2 profile-user-info">
              <h4 class="user-name mb-0">{{$course->name??''}}</h4>
              <h4 class="user-name mb-0">Video : {{$videos->total()}}</h4>
              <h4 class="user-name mb-0">Notes : {{$notes->total()}}</h4>
            </div>
            <div class="col-auto profile-btn">
            </div>
          </div>
        </div>
        <div class="profile-menu">
          <ul class="nav nav-tabs nav-tabs-solid">
            <li class="nav-item">
              <a class="nav-link <?php if($key == 'video') echo "active"?>" data-bs-toggle="tab" href="#videos" onclick="set_tab_in_session('video')">Videos</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?php if($key == 'notes') echo "active"?>" data-bs-toggle="tab" href="#notes" onclick="set_tab_in_session('notes')">PDFs</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?php if($key == 'live_class') echo "active"?>" data-bs-toggle="tab" href="#live_class" onclick="set_tab_in_session('live_class')">Live Class</a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?php if($key == 'coupons') echo "active"?>" data-bs-toggle="tab" href="#coupons" onclick="set_tab_in_session('coupons')">Coupons</a>
            </li>



          </ul>
        </div>
        <div class="tab-content profile-tab-cont">

          <div id="videos" class="tab-pane fade show <?php if($key == 'video') echo "active"?>">
            <div class="card">

              <div class="card-body">
               <div class="col-auto text-end float-end ms-auto download-grp">
                <a data-bs-toggle="modal"  data-bs-target="#addVideoModal" class="btn btn-primary"><i class="fas fa-plus"></i></a>
              </div>
              <h5 class="card-title">Videos</h5>

              <div class="row mt-4">
                <div class="col-md-10 col-lg-12">
                  <div class="table-responsive">
                    <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                      <thead class="student-thread">
                        <tr>
                          <th>SNo.
                          <th>Title</th>
                          <th>HLS Type</th>
                          <th>HLS </th>
                          <th class="text-end">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($videos)){
                          $i = 1;
                          foreach($videos as $video){
                            ?>
                            <tr>
                              <td>{{$i++}}</td>
                              <td>{{$video->title??''}}</td>
                              <td>{{$video->hls_type??''}}</td>
                              <td>{{$video->hls??''}}</td>

                              <td>
                                <div class="actions ">
                                  <a data-bs-toggle="modal"  data-bs-target="#video_update_modal{{$video->id}}" class="btn btn-sm bg-success-light me-2 "><i class="feather-edit"></i></a>
                                  <a class="btn btn-sm bg-success-light me-2 " onclick="return confirm('Are You Want To Delete!!')" href=""><i class="feather-trash"></i></a>
                                </div>
                              </td>

                            </tr>
                            <!-- //////////////note_update_modal////////////////////////////////////////////////// -->

                            <div id="video_update_modal{{$video->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h4 class="modal-title" id="standard-modalLabel">Update - {{$video->title??''}}</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form onsubmit="return false;" id="updatevideos_modal_form{{$video->id}}" action="#" method="post" enctype="multipart/form-data">
                                    @csrf


                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="id" value="{{$video->id}}">
                                    <div class="modal-body">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <label>Course Name</label>
                                          <h6 class="text-wrap">{{$course->name??''}}</h6>
                                        </div>

                                        <div class="col-md-12">
                                          <label>Title</label>
                                          <input type="text" value="{{$video->title??''}}" placeholder="Enter Title" name="title" class="form-control">
                                        </div>
                                        <input type="hidden" name="hls_type" value="video">
                                        <input type="hidden" name="type" value="youtube">
                                        <div class="col-md-12 mb-1">
                                          <label>Video ID</label>
                                          <input type="text" name="hls" value="{{$video->hls??''}}" class="form-control" placeholder="Enter Video ID">
                                        </div>

                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button  class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                      <button onclick="submit_updatevideo_modal_form('{{$video->id}}')" class="btn btn-primary">Save changes</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>



                            <!-- ///////////////////////////////////////////////// -->












                          <?php }}?>
                        </tbody>
                      </table>
                      {{ $videos->appends(request()->input())->links('admin.pagination') }}

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div id="notes" class="tab-pane fade show <?php if($key == 'notes') echo "active"?>" >
            <div class="card">

              <div class="card-body">
               <div class="col-auto text-end float-end ms-auto download-grp">
                <a data-bs-toggle="modal"  data-bs-target="#addNotesModal" class="btn btn-primary"><i class="fas fa-plus"></i></a>
              </div>
              <h5 class="card-title">Notes</h5>

              <div class="row mt-4">
                <div class="col-md-10 col-lg-12">
                  <div class="table-responsive">
                    <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                     <thead class="student-thread">
                      <tr>
                        <th>SNo.
                        <th>Title</th>
                        <th>HLS Type</th>
                        <th>HLS </th>
                        <th class="text-end">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($notes)){
                        $i = 1;
                        foreach($notes as $note){

                          ?>
                          <tr>
                            <td>{{$i++}}</td>
                            <td>{{$note->title??''}}</td>
                            <td>{{$note->hls_type??''}}</td>
                            <td><a href="{{CustomHelper::getImageUrl('contents',$note->hls)}}" target="_blank">View PDF</a></td>

                            <td>
                              <div class="actions">
                                <a data-bs-toggle="modal"  data-bs-target="#note_update_modal{{$note->id}}" class="btn btn-sm bg-success-light me-2 "><i class="feather-edit"></i></a>

                                <a onclick="return confirm('Are You Want To Delete!!')" href="" class="btn btn-sm bg-success-light me-2 "><i class="feather-trash"></i></a>
                              </div>
                            </td>

                          </tr>

                          <!-- //////////////note_update_modal////////////////////////////////////////////////// -->

                          <div id="note_update_modal{{$note->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="standard-modalLabel">Update - {{$note->title??''}}</h4>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form onsubmit="return false;" id="updatenotes_modal_form{{$note->id}}" action="#" method="post" enctype="multipart/form-data">
                                  @csrf


                                  <input type="hidden" name="course_id" value="{{$course->id}}">
                                  <input type="hidden" name="id" value="{{$note->id}}">
                                  <div class="modal-body">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <label>Course Name</label>
                                        <h6 class="text-wrap">{{$course->name??''}}</h6>
                                      </div>

                                      <div class="col-md-12">
                                        <label>Title</label>
                                        <input type="text" value="{{$note->title??''}}" placeholder="Enter Title" name="title" class="form-control">
                                      </div>


                                      <input type="hidden" name="hls_type" value="notes">
                                      <input type="hidden" name="type" value="local">
                                      <div class="col-md-12 mb-1">
                                        <label>Upload File</label>
                                        <input type="file" name="hls" value="" class="form-control" placeholder="Enter Phone">
                                      </div>

                                      <?php 
                                      if(!empty(CustomHelper::getImageUrl('contents',$note->hls))){?>
                                        <a href="{{CustomHelper::getImageUrl('contents',$note->hls)}}" target="_blank">View PDF</a>
                                      <?php }
                                      ?>

                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button  class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button onclick="submit_updatenotes_modal_form('{{$note->id}}')" class="btn btn-primary">Save changes</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>



                          <!-- ///////////////////////////////////////////////// -->











                        <?php }}?>
                      </tbody>
                    </table>
                    {{ $notes->appends(request()->input())->links('admin.pagination') }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>






        <div id="live_class" class="tab-pane fade show <?php if($key == 'live_class') echo "active"?>" >
          <div class="card">

            <div class="card-body">
             <div class="col-auto text-end float-end ms-auto download-grp">
              <a data-bs-toggle="modal"  data-bs-target="#addLiveClassModal" class="btn btn-primary"><i class="fas fa-plus"></i></a>
            </div>
            <h5 class="card-title">Live Classes</h5>

            <div class="row mt-4">
              <div class="col-md-10 col-lg-12">
                <div class="table-responsive">
                  <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                   <thead class="student-thread">
                    <tr>
                      <th>SNo.
                      <th>Type</th>
                      <th>Title</th>
                      <th>Image</th>
                      <th>Course Name</th>
                      <th>Faculty Name</th>
                      <th>Start Date & Time</th>
                      <th>End Date & Time</th>
                      <th>Status</th>

                      <th class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($live_classes)){
                      $i = 1;
                      foreach($live_classes as $live){
                        if($live->type == 'youtube'){
                          $start_url = 'https://www.youtube.com/embed/'.$live->youtube_link;
                        }else{
                          $start_url = $live->start_url;
                        }


                        $faculty = \App\Models\Admin::where('id',$live->faculty_id)->first();
                        $liveclass = 0;
                        $limeImg = url('public/storage/live_class/live.gif');
                        if(date('Y-m-d') >= $live->start_date && date('Y-m-d') <= $live->end_date){
                          if(date('H:i') >=$live->start_time && date('H:i') <=$live->end_time){
                            $liveclass = 1;
                          }
                        }

                        if(date('Y-m-d') < $live->start_date && date('Y-m-d') <= $live->end_date){
                          if(date('H:i') >=$live->start_time && date('H:i') <=$live->end_time){
                            $liveclass = 2;
                          }
                        }
                        ?>
                        <tr>
                          <td>{{$i++}}</td>
                          <td>
                            <?php if($live->type == "youtube"){?>
                              <span>YOUTUBE</span>
                            <?php }else if($live->type == "zoom"){ ?>
                             <span>ZOOM</span>
                           <?php } ?>
                         </td>
                         <td>{{$live->title}}
                          <?php if($liveclass == 1){
                            ?>
                            <a href="{{$start_url ?? ''}}" target="_blank"><img src="{{$limeImg}}" height="70px" width="100px"></a>
                          <?php }?>
                        </td>
                        <td>
                         <?php 
                         $image = CustomHelper::getImageUrl('live_class',$live->image);
                         if(!empty($image)){?>
                          <a href="{{$image}}" target="_blank"><img src="{{$image}}" height="70px" width="70px"></a>
                        <?php }
                        ?>
                      </td>
                      <td>{{CustomHelper::getCourseName($live->course_id)}}</td>
                      <td>{{CustomHelper::getAdminName($live->faculty_id)}}</td>

                      <td>{{date('d M Y',strtotime($live->start_date))}} {{date('h:i A',strtotime($live->start_time))}}</td>
                      <td>{{date('d M Y',strtotime($live->end_date))}} {{date('h:i A',strtotime($live->end_time))}}</td>
                      <td>
                        <select id='change_liveclass_status{{$live->id}}' onchange='change_liveclass_status({{$live->id}})' class="form-control">
                          <option value='1' <?php if($live->status ==1)echo "selected";?> >Active</option>
                          <option value='0' <?php if($live->status ==0)echo "selected";?>>InActive</option>
                        </select>
                      </td> 
                      <td class="text-end">
                       <div class="actions">
                        <a data-bs-toggle="modal"  data-bs-target="#live_class_update_modal{{$live->id}}" class="btn btn-sm bg-success-light me-2 "><i class="feather-edit"></i></a>

                        <a onclick="return confirm('Are You Want To Delete!!')" href="" class="btn btn-sm bg-success-light me-2 "><i class="feather-trash"></i></a>
                      </div>
                    </td>
                  </tr>



                  <!-- //////////////live_class_update_modal////////////////////////////////////////////////// -->

                  <div id="live_class_update_modal{{$live->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title text-wrap" id="standard-modalLabel">Update - {{$live->title??''}}</h4>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form onsubmit="return false;" id="updateliveclass_modal_form{{$live->id}}" action="#" method="post" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" name="course_id" value="{{$course->id}}">
                          <input type="hidden" name="id" value="{{$live->id}}">
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-md-12 mb-1">
                                <label>Type</label>
                                <select class="form-control" name="type">
                                  <option value="" selected>Select Type</option>
                                  <?php if(!empty($live_class_types)){
                                    foreach ($live_class_types as $key => $value) {?>
                                      <option value="{{$key}}" <?php if($key == $live->type) echo "selected";?>>{{$value}}</option>
                                    <?php }}
                                    ?>
                                  </select>
                                </div>
                                <div class="col-md-12 mb-1">
                                  <label>Title</label>
                                  <input type="text" name="title" value="{{$live->title??''}}" placeholder="Enter Title" class="form-control">
                                </div>

                                <div class="col-md-12 mb-1">
                                  <label>Youtube ID :</label>
                                  <input type="text" name="youtube_link" placeholder="Enter Youtube ID" value="{{$live->youtube_link??''}}" class="form-control">
                                </div>
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                                <div class="col-md-12 mb-1">
                                  <label>Faculty</label>
                                  <select class="form-control" name="faculty_id">
                                    <option value="" selected>Select Faculty</option>
                                    <?php if(!empty($faculties)){
                                      foreach ($faculties as  $facul) {?>
                                        <option value="{{$facul->id}}" <?php if($live->faculty_id == $facul->id) echo "selected"?>>{{$facul->name??''}}</option>
                                      <?php }}
                                      ?>
                                    </select>
                                  </div>


                                  <div class="col-md-6 mb-1">
                                    <label>Start Date :</label>
                                    <input type="date" name="start_date" placeholder="Enter Start Date" value="{{$live->start_date??''}}" class="form-control">
                                  </div>


                                  <div class="col-md-6 mb-1">
                                    <label>Start Time :</label>
                                    <input type="time" name="start_time" placeholder="Enter Start Time" value="{{$live->start_time??''}}" class="form-control">
                                  </div>


                                  <div class="col-md-6 mb-1">
                                    <label>End Date :</label>
                                    <input type="date" name="end_date" placeholder="Enter End Date" value="{{$live->end_date??''}}" class="form-control">
                                  </div>


                                  <div class="col-md-6 mb-1">
                                    <label>End Time :</label>
                                    <input type="time" name="end_time" placeholder="Enter End Time" value="{{$live->end_time??''}}" class="form-control">
                                  </div>




                                  <div class="col-md-12 mb-1">
                                    <label>Upload Image</label>
                                    <input type="file" name="image" value="" class="form-control" placeholder="Enter Phone">
                                  </div>

                                </div>
                              </div>
                              <div class="modal-footer">
                                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button  onclick="submit_updateliveclass_modal_form('{{$live->id}}')" class="btn btn-primary">Save changes</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>



                      <!-- ///////////////////////////////////////////////// -->




                    <?php }}?>
                  </tbody>
                </table>
                {{ $live_classes->appends(request()->input())->links('admin.pagination') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



  </div>
</div>
</div>
</div>
</div>


<!-- ////////////////////////////////////////////////////////////////////////////////// -->



<!-- ////////////////////////////////////////////////////////////////////////////////// -->
<!-- addVideoModal Modal -->
<div id="addVideoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="standard-modalLabel">Add Video - {{$course->name??''}} </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addvideo_modal_form" enctype="multipart/form-data">
        <input type="hidden" name="course_id" value="{{$course->id}}">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-1">
              <label>Title</label>
              <input type="text" name="title" value="" class="form-control" placeholder="Enter Title">
            </div>
            <input type="hidden" name="hls_type" value="video">
            <input type="hidden" name="type" value="youtube">

            <div class="col-md-12 mb-1">
              <label>Video ID</label>
              <input type="text" name="hls" value="" class="form-control" placeholder="Enter Video ID">
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





<!-- ////////////////////////////////////////////////////////////////////////////////// -->
<!-- addNotesModal Modal -->
<div id="addNotesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="standard-modalLabel">Add Notes - {{$course->name??''}} </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addnotes_modal_form" enctype="multipart/form-data">
        <input type="hidden" name="course_id" value="{{$course->id}}">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-1">
              <label>Title</label>
              <input type="text" name="title" value="" class="form-control" placeholder="Enter Title">
            </div>
            <input type="hidden" name="hls_type" value="notes">
            <input type="hidden" name="type" value="local">


            <div class="col-md-12 mb-1">
              <label>Upload File</label>
              <input type="file" name="hls" value="" class="form-control" placeholder="Enter Phone">
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





<!-- ////////////////////////////////////////////////////////////////////////////////// -->
<!-- addNotesModal Modal -->
<div id="addLiveClassModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="standard-modalLabel">Add Live Class - {{$course->name??''}} </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addliveclass_modal_form" enctype="multipart/form-data">
        <input type="hidden" name="course_id" value="{{$course->id}}">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-1">
              <label>Type</label>
              <select class="form-control" name="type">
                <option value="" selected>Select Type</option>
                <?php if(!empty($live_class_types)){
                  foreach ($live_class_types as $key => $value) {?>
                    <option value="{{$key}}">{{$value}}</option>
                  <?php }}
                  ?>
                </select>
              </div>
              <div class="col-md-12 mb-1">
                <label>Title</label>
                <input type="text" name="title" value="" placeholder="Enter Title" class="form-control">
              </div>

              <div class="col-md-12 mb-1">
                <label>Youtube ID :</label>
                <input type="text" name="youtube_link" placeholder="Enter Youtube ID" value="" class="form-control">
              </div>
              <input type="hidden" name="course_id" value="{{$course->id}}">
              <div class="col-md-12 mb-1">
                <label>Faculty</label>
                <select class="form-control" name="faculty_id">
                  <option value="" selected>Select Faculty</option>
                  <?php if(!empty($faculties)){
                    foreach ($faculties as  $facul) {?>
                      <option value="{{$facul->id}}">{{$facul->name??''}}</option>
                    <?php }}
                    ?>
                  </select>
                </div>


                <div class="col-md-6 mb-1">
                  <label>Start Date :</label>
                  <input type="date" name="start_date" placeholder="Enter Start Date" value="" class="form-control">
                </div>


                <div class="col-md-6 mb-1">
                  <label>Start Time :</label>
                  <input type="time" name="start_time" placeholder="Enter Start Time" value="" class="form-control">
                </div>


                <div class="col-md-6 mb-1">
                  <label>End Date :</label>
                  <input type="date" name="end_date" placeholder="Enter End Date" value="" class="form-control">
                </div>


                <div class="col-md-6 mb-1">
                  <label>End Time :</label>
                  <input type="time" name="end_time" placeholder="Enter End Time" value="" class="form-control">
                </div>




                <div class="col-md-12 mb-1">
                  <label>Upload Image</label>
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

    <script type="text/javascript">
  //////////Subscription
     $(document).ready(function() {
      $('#addvideo_modal_form').on('submit', function(event) {
        event.preventDefault();
        var _token = '{{ csrf_token() }}';
        let subsInfo = $(this).serializeArray();
        let subsdata = {};
        subsInfo.forEach((value) => {
          subsdata[value.name] = value.value;
        });
        let url = "{{ route($routeName.'.courses.upload_content') }}";
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



     function submit_updatevideo_modal_form(content_id){
      var _token = '{{ csrf_token() }}';
      var form = $('#updatevideos_modal_form'+content_id)[0];
      var formData = new FormData(form);
      let url = "{{ route($routeName.'.courses.upload_content') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: formData,
        processData: false,
        contentType: false,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          if(resp.status){
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
    }






  ///////////////////////////////////////////////////////////////////
////////////////////////LiveClass/////////////////////////////////


    $(document).ready(function() {
      $('#addliveclass_modal_form').on('submit', function(event) {
        event.preventDefault();
        var _token = '{{ csrf_token() }}';
        let userInfo = $(this).serialize();


        console.log(userInfo);
        let userdata = {};
      // userInfo.forEach((value) => {
      //   userdata[value.name] = value.value;
      // });

        var form = $('#addliveclass_modal_form')[0];
        var formData = new FormData(form);
        let url = "{{ route($routeName.'.courses.update_live_class') }}";
        $.ajax({
          method: "POST",
          url: url,
          dataType:"JSON",
          data: formData,
          processData: false,
          contentType: false,
          headers:{'X-CSRF-TOKEN': _token},
          success: function(resp){
          // console.log(resp.message);
            if(resp.status){

            // $('#edit_personal_details').modal('hide');
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


    function submit_updateliveclass_modal_form(live_id){
      var _token = '{{ csrf_token() }}';
      var form = $('#updateliveclass_modal_form'+live_id)[0];
      var formData = new FormData(form);
      let url = "{{ route($routeName.'.courses.update_live_class') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: formData,
        processData: false,
        contentType: false,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          if(resp.status){
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
    }



 //////////////////////////////////////Notes//////////////////
    function submit_updatenotes_modal_form(content_id){
      var _token = '{{ csrf_token() }}';
      var form = $('#updatenotes_modal_form'+content_id)[0];
      var formData = new FormData(form);
      let url = "{{ route($routeName.'.courses.upload_content') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: formData,
        processData: false,
        contentType: false,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          if(resp.status){
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
    }


    $(document).ready(function() {
      $('#addnotes_modal_form').on('submit', function(event) {
        event.preventDefault();
        var _token = '{{ csrf_token() }}';
        let userInfo = $(this).serialize();


        console.log(userInfo);
        let userdata = {};
      // userInfo.forEach((value) => {
      //   userdata[value.name] = value.value;
      // });

        var form = $('#addnotes_modal_form')[0];
        var formData = new FormData(form);
        let url = "{{ route($routeName.'.courses.upload_content') }}";
        $.ajax({
          method: "POST",
          url: url,
          dataType:"JSON",
          data: formData,
          processData: false,
          contentType: false,
          headers:{'X-CSRF-TOKEN': _token},
          success: function(resp){
            if(resp.status){
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