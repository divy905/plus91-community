@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$businesses_id = (isset($businesses->id))?$businesses->id:'';
$business_name = (isset($businesses->business_name))?$businesses->business_name:'';
$image = (isset($businesses->image))?$businesses->image:'';
$status = (isset($businesses->status))?$businesses->status:'';
$slug = (isset($businesses->slug))?$businesses->slug:'';
$alt = (isset($businesses->alt))?$businesses->alt:'';
$og_title = (isset($businesses->og_title))?$businesses->og_title:'';
$og_description = (isset($businesses->og_description))?$businesses->og_description:'';
$og_image = (isset($businesses->og_image))?$businesses->og_image:'';
$meta_title = (isset($businesses->meta_title))?$businesses->meta_title:'';
$meta_description = (isset($businesses->meta_description))?$businesses->meta_description:'';

$canonical = (isset($businesses->canonical))?$businesses->canonical:'';
$title = (isset($businesses->title))?$businesses->title:'';
$keywords = (isset($businesses->keywords))?$businesses->keywords:'';
$robots = (isset($businesses->robots))?$businesses->robots:'';



$image_name = substr($image, 0, strpos($image, "."));
$storage = Storage::disk('public');
$path = 'business_gallery/';
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

                        <input type="hidden" name="id" value="{{$businesses_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Business Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>


                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Business Name</label>
                                    <input type="text" name="business_name" value="{{ old('business_name', $business_name) }}" id="business_name" disabled class="form-control"  maxlength="255" placeholder="Enter  Business Name" />

                                    @include('snippets.errors_first', ['param' => 'business_name'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Slug</label>
                                    <input type="text" name="slug" value="{{ old('slug', $slug) }}" id="slug" class="form-control"  maxlength="255" placeholder="Enter  slug" />

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

                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Image</label>
                                    <input type="file" name="image"  class="form-control"   />
                                    <br>
                                    <?php 

                                    if(!empty($image)){?>
                                      <a href="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$image}}" target='_blank'><img src="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$image}}" style='width:50px;heith:50px;'></a>
                                  <?php } ?>
                                  @include('snippets.errors_first', ['param' => 'image'])
                              </div>
                          </div>

                          <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">Image Name</label>
                                <input type="text" name="image_name" value="{{ old('image_name', $image_name) }}" id="image_name" class="form-control"  maxlength="255" placeholder="Enter Image Name" />

                                @include('snippets.errors_first', ['param' => 'image'])
                            </div>
                        </div>


                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">OG Image</label>
                                <input type="file" name="og_image" class="form-control">

                                @include('snippets.errors_first', ['param' => 'alt'])
                            </div>
                        </div>


                        <?php if(!empty($business_gallery)){
                            foreach ($business_gallery as $key){?>
                                <input type="hidden" name="galleryIds[]" value="{{$key->id}}">
                               <div class="col-12 col-sm-4">
                                   <div class="form-group">
                                       <a href="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$key->file}}" target='_blank'><img src="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$key->file}}" style='width:100px;heith:100px;'></a>
                                   </div>
                               </div>
                               <div class="col-12 col-sm-4">
                                   <div class="form-group">
                                       <label for="userName">Title</label>
                                       <input type="text" name="titleArr[]" value="{{$key->title??''}}" class="form-control">
                                   </div>
                               </div> 

                               <div class="col-12 col-sm-4">
                                   <div class="form-group">
                                       <label for="userName">Alt Tag</label>
                                       <input type="text" name="alt_tag[]" value="{{$key->alt_tag??''}}" class="form-control">
                                   </div>
                               </div>   
                           <?php }}?>





                           <div class="col-12 col-sm-3">
                            <div class="form-group">
                                <label for="userName"> Title</label>
                                <input type="text" name="title" value="{{ old('title', $title) }}" id="meta_title" onkeyup="get_metatitle_length(this.value)"class="form-control"  maxlength="255" placeholder="Enter Title" />
                                <span id="count_metatitle">0</span> Characters
                                @include('snippets.errors_first', ['param' => 'title'])
                            </div>
                        </div>

                        <div class="col-12 col-sm-3">
                            <div class="form-group">
                                <label for="userName"> Canonical</label>
                                <input type="text" name="canonical" value="{{ old('canonical', $canonical) }}" id="canonical" class="form-control"   maxlength="255" placeholder="Enter canonical" />

                                @include('snippets.errors_first', ['param' => 'canonical'])
                            </div>
                        </div>

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




                        <div class="col-12 col-sm-12">
                            <div class="form-group">
                                <label for="userName">Meta Description</label>
                                <textarea name="meta_description" class="form-control" placeholder="Meta Description" id="meta_description"  onkeyup="get_metadescription_length(this.value)">{{ old('meta_description', $meta_description) }}</textarea>
                                <span id="count_metadescription">0</span> Characters
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
</div>
</div>
<script>
    CKEDITOR.replace( 'description' );
</script>
@endsection


