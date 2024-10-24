@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'course/';
$types = \App\Models\Types::where('status',1)->where('is_delete',0)->get();
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Courses</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Courses</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post">
      @csrf
    <div class="student-group-form">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search  ...">
          </div>
        </div>
       <!--  <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Name ...">
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Phone ...">
          </div>
        </div> -->
        <div class="col-lg-2">
          <div class="search-student-btn">
            <button type="btn" class="btn btn-primary">Search</button>
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
                  <h3 class="page-title">Courses</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.courses.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                    <th >SNo.
                    <th >Exam</th>
                    <th >Exam Category</th>
                    <th >Course</th>
                    <th >Module</th>
                    <!-- <th >Image</th> -->
                    <th >Offered Price</th>
                    <th >Status</th>
                    <th >Date Created</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($courses)){

                    $i = 1;
                    foreach($courses as $course){
                      $type_ids = CustomHelper::getTypeIdsCourse($course->id);
                      ?>
                      <tr>

                        <td>{{$i++}}</td>
                        <td>
                          <?php if(!empty($course->category_id)){
                            $category_ids = explode(",", $course->category_id);
                            if(!empty($category_ids)){
                              foreach ($category_ids as $key => $value) {?>
                                {{CustomHelper::getCategoryName($value)}}<br>
                          <?php }}}?>

                        </td>
                        <td>
                          <?php if(!empty($course->subcategory_id)){
                            $subcategory_ids = explode(",", $course->subcategory_id);
                            if(!empty($subcategory_ids)){
                              foreach ($subcategory_ids as $key => $value) {?>
                                {{CustomHelper::getSubCategoryName($value)}}<br>
                          <?php }}}?>
                        </td>
                        <td>
                          {{mb_strlen(strip_tags($course->name),'utf-8') > 15 ? mb_substr(strip_tags($course->name),0,15,'utf-8').'...' : strip_tags($course->name)}}
                        </td>
                        <td>{!!CustomHelper::getTypesOfCourse($course->id)!!}
                          <br>
                          <a data-bs-toggle="modal" data-bs-target="#type_modal{{$course->id}}" class="btn btn-primary" ><i style="    color: white;" class="fas fa-plus"></i></a>


                          <div id="type_modal{{$course->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="standard-modalLabel">{{mb_strlen(strip_tags($course->name),'utf-8') > 15 ? mb_substr(strip_tags($course->name),0,15,'utf-8').'...' : strip_tags($course->name)}}</h4>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route($routeName.'.courses.assign_types') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="course_id" value="{{$course->id}}">
                                  <div class="modal-body">
                                    <select class="multipleselectdropdown" name="type_ids[]" multiple>
                                      <?php if(!empty($types)){
                                        foreach($types as $type){
                                          ?>
                                          <option value="{{$type->id}}" <?php if(in_array($type->id,$type_ids)) echo "selected";?>>{{$type->name??''}}</option>

                                        <?php }}?>
                                      </select>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>

                          </td>
                          <td class="d-none">


                           <?php
                          if(!empty($course->image)){
                            $image_name = $course->image;
                            if($storage->exists($path.$image_name)){
                              ?>
                              <div class=" image_box" style="display: inline-block">
                                <a href="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" target="_blank">
                                  <img src="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" style="width:70px;">
                                </a>
                              </div>
                              <?php
                            }
                          }else{?>

                            <?php  }?></td>
                            <td>{{$course->price??''}}</td>
                            <td>
                              <select id='change_course_status{{$course->id}}' class="form-control" onchange='change_course_status({{$course->id}})'>
                                <option value='1'<?php if($course->status == 1) echo 'selected'?>>Active</option>
                                <option value='0'<?php if($course->status == 0) echo 'selected'?>>InActive</option>
                              </select>


                            </td>

                            <td>{{date('d M Y',strtotime($course->created_at))}}</td>


                            <td class="text-end">
                              <div class="actions ">
                                <a href="{{ route($routeName.'.courses.contents', $course->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                                  <i class="feather-eye"></i>
                                </a>

                                 <a href="{{ route($routeName.'.courses.edit', $course->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                                  <i class="feather-edit"></i>
                                </a>
                                <a href="{{ route($routeName.'.courses.delete', $course->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                                  <i class="feather-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php }}?>
                      </tbody>
                    </table>
                    {{ $courses->appends(request()->input())->links('admin.pagination') }}

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      @endsection

      <script>

        function change_course_status(id){
          var status = $('#change_course_status'+id).val();


          var _token = '{{ csrf_token() }}';

          $.ajax({
            url: "{{ route($routeName.'.courses.change_course_status') }}",
            type: "POST",
            data: {id:id, status:status},
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