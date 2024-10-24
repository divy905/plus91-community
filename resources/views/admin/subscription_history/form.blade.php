@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


 $banner_id = isset($banners->id) ? $banners->id : '';

 $image = isset($banners->image) ? $banners->image : '';
 $category_id = isset($banners->category_id) ? $banners->category_id : '';
 $link = isset($banners->link) ? $banners->link : '';
 $type = isset($banners->type) ? $banners->type : '';
 $status = isset($banners->status) ? $banners->status : '';

 $categories = \App\Models\ExamCategory::where('status',1)->get();
?>


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">{{ $page_Heading }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">{{ $page_Heading }}</li>
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

                                 <input type="hidden" id="id" value="{{$banner_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Banner Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>

                            <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                         <label for="email" class="form-label">Category</label>
                                        <select class="form-control" name="category_id">
                                            <option value="" selected>Select Category</option>
                                            <?php 
                                            if(!empty($categories)){
                                                foreach($categories as $cat){
                                            
                                            ?>
                                            <option value="{{$cat->id}}" <?php if($category_id == $cat->id) echo "selected";?>>{{$cat->name??''}}</option>
                                        <?php }}?>
                                        </select>
                                    </div>
                                </div>
                             


                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label>Upload  Image (Choose 778px * 338px)</label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" name="image">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group students-up-files">
                                             <label for="email" class="form-label">Link</label>
                                                <input type="text" class="form-control mb-3" name="link" id="link" placeholder="Enter Link" value="{{ old('link',$link) }}">
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
<script>
    CKEDITOR.replace( 'description' );
</script>

<!--  -->