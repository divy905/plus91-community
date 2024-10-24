@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$chapter_id = (isset($chapters->id))?$chapters->id:'';
$course_id = (isset($chapters->course_id))?$chapters->course_id:'';

$chapter_name = (isset($chapters->chapter_name))?$chapters->chapter_name:'';
$status = (isset($chapters->status))?$chapters->status:'';

$courses = DB::table('courses')->where(['status'=>1,'is_delete'=>0])->get();

?>


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">{{ $page_heading }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">{{ $page_heading }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                       <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{$chapter_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Course Information <span>
                                    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?>
                            </span></h5>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">Choose Course <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="course_id" id="course_id" >
                                    <option value="" disabled selected>Select Course</option>
                                    <?php if(!empty($courses)){
                                        foreach($courses as $course){
                                            ?>
                                            <option value="{{$course->id}}" <?php if($course_id == $course->id) { echo "selected"; }?>>{{$course->name??''}}</option>
                                        <?php }}?>
                                    </select>


                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>
                            </div>




                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Chapter Name<span class="text-danger">*</span></label>
                                        <input type="text" name="chapter_name" value="{{ old('chapter_name', $chapter_name) }}" id="chapter_name" class="form-control"  maxlength="255" placeholder="Enter Chapter Name" />

                                        @include('snippets.errors_first', ['param' => 'chapter_name'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label>Upload  Image </label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" name="image">
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <label>Status</label>
                                    <div>
                                     Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                     &nbsp;
                                     Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                     @include('snippets.errors_first', ['param' => 'status'])
                                 </div>
                             </div>

                             <div class="col-12 mt-3">
                                <div class="student-submit">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection


<!--  -->