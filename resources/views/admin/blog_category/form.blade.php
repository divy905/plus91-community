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
$category_id = (isset($blogs->category_id))?$blogs->category_id:'';

$status = (isset($blogs->status))?$blogs->status:'';
$long_description = (isset($blogs->long_description))?$blogs->long_description:'';
$slug = (isset($blogs->slug))?$blogs->slug:'';
$canonical = (isset($blogs->canonical))?$blogs->canonical:'';
$keywords = (isset($blogs->keywords))?$blogs->keywords:'';
$robots = (isset($blogs->robots))?$blogs->robots:'';
$category_name = (isset($blogs->category_name))?$blogs->category_name:'';
$type = (isset($blogs->type))?$blogs->type:'';





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
                                <h5 class="form-title student-info">Blogs Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>


                            <div class="row">
                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="userName">Title<span class="text-danger">*</span></label>
                                        <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255" placeholder="Enter  title" />

                                        @include('snippets.errors_first', ['param' => 'title'])
                                    </div>
                                </div>
                                 <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="userName">Title<span class="text-danger">*</span></label>
                                       <select name="type" class="form-control">
                                           <option value="english" <?php if($type == 'english') echo "selected";?>>English</option>
                                           <option value="hindi" <?php if($type == 'hindi') echo "selected";?>>Hindi</option>
                                       </select>

                                        @include('snippets.errors_first', ['param' => 'type'])
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="userName">Slug<span class="text-danger">*</span></label>
                                        <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" placeholder="Enter  slug" />

                                        @include('snippets.errors_first', ['param' => 'slug'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6" >
                                </div>
                                 <div class="col-12 col-sm-6" id="image_show">

                                   <div class="holder">
                                    <img id="imgPreview" src="#" alt="pic" height="300px" width="600px" />
                                </div>
                                
                            </div>

                       


                        <div class="row">


                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="userName">Category<span class="text-danger">*</span></label>
                                    <select class="form-control " name="category_id">
                                        <option value="" selected>Select Category</option>
                                        <?php if(!empty($categories)){
                                            foreach($categories as $cat){
                                                ?>
                                                <option value="{{$cat->id}}" <?php if($category_id == $cat->id) echo "selected"?>>{{$cat->name??''}}</option>
                                            <?php }}?>
                                        </select>

                                        @include('snippets.errors_first', ['param' => 'title'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="userName"> Canonical</label>
                                        <input type="text" name="canonical" value="{{ old('canonical', $canonical) }}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />

                                        @include('snippets.errors_first', ['param' => 'canonical'])
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="userName"> Keywords</label>
                                        <input type="text" name="keywords" value="{{ old('keywords', $keywords) }}" id="keywords" class="form-control"  maxlength="255" placeholder="Enter keywords" />

                                        @include('snippets.errors_first', ['param' => 'keywords'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <div class="form-group">
                                        <label for="userName">Robots</label>
                                        <select name="robots" class="form-control">
                                            <option value="index" <?php if($robots == 'index')echo "selected";?>>Index</option>
                                            <option value="follow" <?php if($robots == 'follow')echo "selected";?>>Follow</option>
                                            <option value="index, follow" <?php if($robots == 'index, follow')echo "selected";?>>Both</option>
                                            <option value="noindex, nofollow" <?php if($robots == 'noindex, nofollow') echo "selected";?>>No Index , No Follow</option>
                                        </select>

                                        @include('snippets.errors_first', ['param' => 'robots'])
                                    </div>
                                </div>



                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-3">
                                    <div class="form-group students-up-files">
                                        <label>Upload  Photo </label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" id="photo" name="image">
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12 col-sm-3">
                                    <label>Status</label>
                                    <div>
                                       Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                       &nbsp;
                                       Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                       @include('snippets.errors_first', ['param' => 'status'])
                                   </div>
                               </div>

                           </div>




                           <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label for="userName">Short Description<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="short_description" id="summernote" cols="" >{{old('short_description',$short_description)}}</textarea>


                                @include('snippets.errors_first', ['param' => 'description'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label for="userName">Long Description<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="long_description" id="summernote1">{{old('long_description',$long_description)}}</textarea>


                                @include('snippets.errors_first', ['param' => 'description'])
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
<div class="row">
    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">
             <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.blogs.add_tags') }}" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <input type="hidden" name="blog_id" value="{{$blogs_id}}">
                <div class="row">
                    <div class="col-12">
                        <h5 class="form-title student-info">Blogs Tags </h5>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="userName">Tag Name</label>
                            <input type="text" name="tag_name"  id="tag_name" class="form-control" maxlength="255" placeholder="Enter Tag Name" />

                            @include('snippets.errors_first', ['param' => 'tag_name'])
                        </div>
                    </div>




                    <div class="col-12 col-sm-6">
                        <div class="student-submit">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">

                <?php 
                $tags = DB::table('blog_tags')->where('blog_id',$blogs_id)->get();
                if(!empty($tags)){
                    foreach($tags as $key => $tag){?>
                        <div class="col-12 col-sm-6 d-flex">
                            <h5 class="form-title student-info">{{$key+1}}. {{$tag->tag??''}}</h5>
                            <div class="actions ">
                                <a href="{{ route($ADMIN_ROUTE_NAME.'.blogs.delete_tags', $tag->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are you Want To Delete?')" class="btn btn-sm bg-danger-light">
                                    <i class="feather-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php }
                }

                ?>


            </div>

        </div>
    </div>
</div>
</div> 
</div>



















   




<script type="text/javascript">


    $(document).ready(()=>{

        $('#imgPreview').attr('src', '{{url("/public/assets/noimg.png")}}');


        $('#photo').change(function(){
            const file = this.files[0];
            console.log(file);
            if (file){
              let reader = new FileReader();
              reader.onload = function(event){
                console.log(event.target.result);
                $('#imgPreview').attr('src', event.target.result);
            // $('#image_show').show();
            }
            reader.readAsDataURL(file);
        }
    });
    });



    $("#title").keyup(function(){
        var title = $('#title').val();
        var _token = '{{ csrf_token() }}';
        var table = 'blogs';

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


