@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$pages_id = (isset($pages->id))?$pages->id:'';
$name = (isset($pages->name))?$pages->name:'';

$status = (isset($pages->status))?$pages->status:'';
$slug = (isset($pages->slug))?$pages->slug:'';
$content = (isset($pages->content))?$pages->content:'';
$title = (isset($pages->title))?$pages->title:'';
$meta_title = (isset($pages->meta_title))?$pages->meta_title:'';
$meta_description = (isset($pages->meta_description))?$pages->meta_description:'';
$canonical = (isset($pages->canonical))?$pages->canonical:'';
$keywords = (isset($pages->keywords))?$pages->keywords:'';
$robots = (isset($pages->robots))?$pages->robots:'';




?>

<style type="text/css">
    textarea.form-control {
    resize: vertical;
    min-height: 77px;
}
</style>
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

                        <input type="hidden" name="id" value="{{$pages_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Page Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>

                            
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="userName">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter  name" />

                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="userName">Slug<span class="text-danger">*</span></label>
                                    <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" placeholder="Enter  slug" />

                                    @include('snippets.errors_first', ['param' => 'slug'])
                                </div>
                            </div>



                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    <label for="userName">Content<span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="content" id="content">{{old('content',$content)}}</textarea>


                                    @include('snippets.errors_first', ['param' => 'content'])
                                </div>
                            </div>


                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="userName"> Title</label>
                                    <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255" placeholder="Enter Title" />

                                    @include('snippets.errors_first', ['param' => 'title'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="userName"> Canonical</label>
                                    <input type="text" name="canonical" value="{{ old('canonical', $canonical) }}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />

                                    @include('snippets.errors_first', ['param' => 'canonical'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="userName"> Keywords</label>
                                    <input type="text" name="keywords" value="{{ old('keywords', $keywords) }}" id="keywords" class="form-control"  maxlength="255" placeholder="Enter keywords" />

                                    @include('snippets.errors_first', ['param' => 'keywords'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label for="userName">Robots</label>
                                    <input type="text" name="robots" value="{{ old('robots', $robots) }}" id="robots" class="form-control"  maxlength="255" placeholder="Enter robots" />

                                    @include('snippets.errors_first', ['param' => 'robots'])
                                </div>
                            </div>


                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    <label for="userName">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" placeholder="Meta Description" rows="4" cols="50">{{ old('meta_description', $meta_description) }}</textarea>
                                    @include('snippets.errors_first', ['param' => 'meta_description'])
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
    CKEDITOR.replace( 'content' );

// $(document).ready(function() {
//   $('#content').summernote({
//         placeholder: '',
//         tabsize: 2,
//         height:300
//       });
// });

</script>

<script type="text/javascript">
    $("#name").keyup(function(){
        var title = $('#name').val();
        var _token = '{{ csrf_token() }}';
        var table = 'pages';

        $.ajax({
            url: "{{ route('generate_slug') }}",
            type: "POST",
            data: {title:title,table:table},
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){
                if(resp.success){
                    $('#slug').val(resp.slug)
                }
                else{
                }

            }
        });
    });
</script>
@endsection


