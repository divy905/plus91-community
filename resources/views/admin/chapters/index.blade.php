@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'chapters/';
$types = \App\Models\Types::where('status',1)->where('is_delete',0)->get();
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Chapters</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Chapters</li>
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
                  <h3 class="page-title">Chapters</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.chapters.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                    <th >SNo.
                    <th >Course Name</th>
                    <th >Chapter</th>                   
                    <th >Image</th>                 
                    <th >Status</th>                  
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($chapters)){

                    $i = 1;
                    foreach($chapters as $chapter){
                    //  $type_ids = CustomHelper::getTypeIdsCourse($course->id);
                      $course_name = DB::table('courses')->select('name')->where('id',$chapter->course_id)->first();
                      ?>
                      <tr>

                        <td>{{$i++}}</td>
                        <td class="text-wrap">{{$course_name->name ?? ''}}</td>
                        <td>{{$chapter->chapter_name}}</td>   
                        <td>

                           <?php
                          if(!empty($chapter->image)){
                            $image_name = $chapter->image;
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
                             <div class=" image_box" style="display: inline-block">
                                <a href="{{ url('public/storage/'.$path.'thumb/default_chapter.png') }}" target="_blank">
                                  <img src="{{ url('public/storage/'.$path.'thumb/default_chapter.png') }}" style="width:70px;">
                                </a>
                              </div>

                            <?php  }?></td>
                          
                            <td>
                              <select id='change_chapter_status{{$chapter->id}}' class="form-control" onchange='change_chapter_status({{$chapter->id}})'>
                                <option value='1'<?php if($chapter->status == 1) echo 'selected'?>>Active</option>
                                <option value='0'<?php if($chapter->status == 0) echo 'selected'?>>InActive</option>
                              </select>


                            </td>

                            <td class="text-end">
                              <div class="actions ">                              

                                 <a href="{{ route($routeName.'.chapters.edit', $chapter->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                                  <i class="feather-edit"></i>
                                </a>
                                <a href="{{ route($routeName.'.chapters.delete', $chapter->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                                  <i class="feather-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php }}?>
                      </tbody>
                    </table>
                    {{ $chapters->appends(request()->input())->links('admin.pagination') }}

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      @endsection

      <script>

        function change_chapter_status(id){
          var status = $('#change_chapter_status'+id).val();


          var _token = '{{ csrf_token() }}';

          $.ajax({
            url: "{{ route($routeName.'.chapters.change_chapter_status') }}",
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