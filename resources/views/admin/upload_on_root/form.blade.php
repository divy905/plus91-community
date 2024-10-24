@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$blogs_id = (isset($blogs->id))?$blogs->id:'';
$title = (isset($blogs->title))?$blogs->title:'';
$image = (isset($blogs->image))?$blogs->image:'';
$short_description = (isset($blogs->short_description))?$blogs->short_description:'';
$type = (isset($blogs->type))?$blogs->type:'';
$category_id = (isset($blogs->category_id))?$blogs->type:'';

$status = (isset($blogs->status))?$blogs->status:'';
$long_description = (isset($blogs->long_description))?$blogs->long_description:'';
$slug = (isset($blogs->slug))?$blogs->slug:'';
$canonical = (isset($blogs->canonical))?$blogs->canonical:'';
$keywords = (isset($blogs->keywords))?$blogs->keywords:'';
$robots = (isset($blogs->robots))?$blogs->robots:'';





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
@include('snippets.errors')
    @include('snippets.flash')
        <div class="row">
            <div class="col-sm-12">
                
                <div class="card comman-shadow">
                    <div class="card-body">
                     <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{$blogs_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">blogs Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>

                                 
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label for="userName">Title<span class="text-danger">*</span></label>
                                            <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255" placeholder="Enter  title" />

                                            @include('snippets.errors_first', ['param' => 'title'])
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label for="userName">Slug<span class="text-danger">*</span></label>
                                            <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" placeholder="Enter  slug" />

                                            @include('snippets.errors_first', ['param' => 'slug'])
                                        </div>
                                    </div>



                                    <div class="col-12 col-sm-12">
                                        <div class="form-group local-forms">
                                            <label for="userName">Short Description<span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="short_description" id="summernote">{{old('short_description',$short_description)}}</textarea>


                                            @include('snippets.errors_first', ['param' => 'description'])
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-12">
                                        <div class="form-group local-forms">
                                            <label for="userName">Long Description<span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="long_description" id="summernote1">{{old('long_description',$long_description)}}</textarea>


                                            @include('snippets.errors_first', ['param' => 'description'])
                                        </div>
                                    </div>



                       
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label for="userName"> Canonical</label>
                                <input type="text" name="canonical" value="{{ old('canonical', $canonical) }}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />

                                @include('snippets.errors_first', ['param' => 'canonical'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label for="userName"> Keywords</label>
                                <input type="text" name="keywords" value="{{ old('keywords', $keywords) }}" id="keywords" class="form-control"  maxlength="255" placeholder="Enter keywords" />

                                @include('snippets.errors_first', ['param' => 'keywords'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <label for="userName">Robots</label>
                                <input type="text" name="robots" value="{{ old('robots', $robots) }}" id="robots" class="form-control"  maxlength="255" placeholder="Enter robots" />

                                @include('snippets.errors_first', ['param' => 'robots'])
                            </div>
                        </div>





                                    <div class="col-12 col-sm-4">
                                        <div class="form-group students-up-files">
                                            <label>Upload  Photo </label>
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
<script>
    CKEDITOR.replace( 'summernote' );
    CKEDITOR.replace( 'summernote1' );
</script>
@endsection


