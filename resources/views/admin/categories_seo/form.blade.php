@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();

$categories_id = (isset($categories->id))?$categories->id:'';
$name = (isset($categories->name))?$categories->name:'';
$meta_title = (isset($categories->meta_title))?$categories->meta_title:'';
$meta_description = (isset($categories->meta_description))?$categories->meta_description:'';
$slug = (isset($categories->slug))?$categories->slug:'';
$alt = (isset($categories->alt))?$categories->alt:'';
$og_title = (isset($categories->og_title))?$categories->og_title:'';
$og_description = (isset($categories->og_description))?$categories->og_description:'';
$og_image = (isset($categories->og_image))?$categories->og_image:'';
$image = (isset($categories->image))?$categories->image:'';
$main_image = (isset($categories->main_image))?$categories->main_image:'';
$canonical = (isset($categories->canonical))?$categories->canonical:'';
$title = (isset($categories->title))?$categories->title:'';
$keywords = (isset($categories->keywords))?$categories->keywords:'';
$robots = (isset($categories->robots))?$categories->robots:'';
$meta_keyword = (isset($categories->meta_keyword))?$categories->meta_keyword:'';




$storage = Storage::disk('public');
$path = 'category/';
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

                        <input type="hidden" name="id" value="{{$categories_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Category Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Name</label>
                                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control" disabled maxlength="255" placeholder="Enter Name" />

                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Url</label>
                                    <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" placeholder="Enter slug" />

                                    @include('snippets.errors_first', ['param' => 'slug'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Alt Tag</label>
                                    <input type="text" name="alt" value="{{ old('alt', $alt) }}" id="alt" class="form-control"  maxlength="255" placeholder="Enter alt" />

                                    @include('snippets.errors_first', ['param' => 'alt'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="userName">Image</label>
                                    <input type="file" name="image"  class="form-control"   />
                                    <br>
                                    <?php 

                                    if(!empty($image)){?>
                                      <a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" style='width:50px;heith:50px;'></a>
                                  <?php } ?>
                                  @include('snippets.errors_first', ['param' => 'image'])
                              </div>
                          </div>

                          <div class="col-12 col-sm-3">
                            <div class="form-group">
                                <label for="userName">Image Name</label>
                                <input type="text" name="image_name" value="{{ old('image', $image) }}" id="image" class="form-control"  maxlength="255" placeholder="Enter Image Name" />

                                @include('snippets.errors_first', ['param' => 'image'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-3">
                            <div class="form-group">
                                <label for="userName">Main Image</label>
                                <input type="file" name="main_image"  class="form-control"   />
                                <br>

                                <?php 
                            
                                    if(!empty($main_image)){?>
                                      <a href="{{ url('public/storage/'.$path.'/'.$main_image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$main_image) }}" style='width:50px;heith:50px;'></a>
                                  <?php } ?>

                                @include('snippets.errors_first', ['param' => 'main_image'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-3">
                            <div class="form-group">
                                <label for="userName">Main Image Name</label>
                                <input type="text" name="mainimage_name" value="{{ old('main_image', $main_image) }}" id="main_image" class="form-control"  maxlength="255" placeholder="Enter Main Image Name Title" />

                                @include('snippets.errors_first', ['param' => 'main_image'])
                            </div>
                        </div>


                       <!--  <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">OG Title</label>
                                <input type="text" name="og_title" value="{{ old('og_title', $og_title) }}" id="og_title" class="form-control"  maxlength="255" placeholder="Enter Title" />

                                @include('snippets.errors_first', ['param' => 'og_title'])
                            </div>
                        </div>
 -->



<!-- 
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">OG Description</label>
                                <input type="text" name="og_description" value="{{ old('og_description', $og_description) }}" id="og_description" class="form-control"  maxlength="255" placeholder="Enter OG Description" />

                                @include('snippets.errors_first', ['param' => 'og_description'])
                            </div>
                        </div>
 -->
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">OG Image</label>
                                <input type="file" name="og_image" class="form-control">
                                <?php 
                            
                                    if(!empty($og_image)){?>
                                      <a href="{{ url('public/storage/'.$path.'/'.$og_image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$og_image) }}" style='width:50px;heith:50px;'></a>
                                  <?php } ?>
                                @include('snippets.errors_first', ['param' => 'alt'])
                            </div>
                        </div>



<!-- 
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName"> Title</label>
                                <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255" placeholder="Enter Title" />

                                @include('snippets.errors_first', ['param' => 'title'])
                            </div>
                        </div> -->

                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName"> Canonical</label>
                                <input type="text" name="canonical" value="{{ old('canonical', $canonical) }}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />

                                @include('snippets.errors_first', ['param' => 'canonical'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName"> Keywords</label>
                                <input type="text" name="keywords" value="{{ old('keywords', $keywords) }}" id="keywords" class="form-control"  maxlength="255" placeholder="Enter keywords" />

                                @include('snippets.errors_first', ['param' => 'keywords'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">Robots</label>
                                <select name="robots" class="form-control">
                                            <option value="index" <?php if($robots == 'index') echo "selected";?>>Index</option>
                                            <option value="follow" <?php if($robots == 'follow') echo "selected";?>>Follow</option>
                                            <option value="index, follow" <?php if($robots == 'index, follow') echo "selected";?>>Both</option>
                                            <option value="noindex, nofollow" <?php if($robots == 'noindex, nofollow') echo "selected";?>>No Index , No Follow</option>
                                        </select>

                                @include('snippets.errors_first', ['param' => 'robots'])
                            </div>
                        </div>




                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label for="userName">Meta Title</label>
                               <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $meta_title) }}">
                                @include('snippets.errors_first', ['param' => 'meta_title'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label for="userName">Meta Description</label>
                                <textarea name="meta_description" class="form-control" placeholder="Meta Description">{{ old('meta_description', $meta_description) }}</textarea>
                                @include('snippets.errors_first', ['param' => 'meta_description'])
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

<div class="row">
    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">
             <form method="POST" action="{{ route($ADMIN_ROUTE_NAME.'.categories.add_tags') }}" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}

                <input type="hidden" name="category_id" value="{{$categories_id}}">
                <div class="row">
                    <div class="col-12">
                        <h5 class="form-title student-info">Category Tags </h5>
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
                $tags = DB::table('category_tags')->where('category_id',$categories_id)->get();
                if(!empty($tags)){
                    foreach($tags as $tag){?>
                        <div class="col-12 col-sm-6 d-flex">
                            <h5 class="form-title student-info">{{$tag->tag??''}}</h5>
                            <div class="actions ">
                                <a href="{{ route($routeName.'.categories.delete_tags', $tag->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are you Want To Delete?')" class="btn btn-sm bg-danger-light">
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
</div>

@endsection
<script>
    CKEDITOR.replace( 'description' );
</script>

<!--  -->