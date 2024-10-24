@extends('admin.layouts.layouts')

<?php

$BackUrl = CustomHelper::BackUrl();

$routeName = CustomHelper::getAdminRouteName();

$path = 'influencer/thumb/';

$type = $_GET['type']??'';

$locality = [];

$city_id = old('city_id') ??'';

if(!empty($city_id)){

  $locality = \App\Models\Locality::where('city_id',$city_id)->get();
  echo $locality;

}

?>

@section('content')

<div class="page-wrapper">

  <div class="content container-fluid">

    <div class="page-header">

      <div class="row">

        <div class="col-sm-12">

          <div class="page-sub-header">

            <h3 class="page-title">Categories SEO - {{$category->name??''}}</h3>

            <ul class="breadcrumb">

              <li class="breadcrumb-item active">Categories SEO - {{$category->name??''}}</li>

            </ul>

          </div>

        </div>

      </div>

    </div>

    @include('snippets.errors')

    @include('snippets.flash')

    <form action="{{route('admin.categories_seo.save_seo_data')}}" enctype="multipart/form-data" method="post">

      @csrf

      <input type="hidden" name="category_id" value="{{$category->slug}}">

      <div class="student-group-form">

        <div class="row">

          <div class="col-md-6">

            <div class="form-group">

              <select class="form-control" name="city_id" id="city_id">

                <option value="" selected>Select City</option>

                <?php if(!empty($cities)){

                  foreach($cities as $city){

                    ?>

                    <option value="{{$city->id}}" <?php if(old('city_id') == $city->id) echo "selected"?>>{{$city->name??''}}</option>

                  <?php }}?>

                </select>

              </div>

            </div>

            <div class="col-md-6">

              <div class="form-group">

                <select class="form-control" name="locality" id="locality">

                  <option value="" selected>Select Locality</option>

                  <?php if(!empty($locality)){

                    foreach($locality as $local){

                      ?>

                      <option value="{{$local->slug}}" <?php if(old('locality') == $local->slug) echo "selected"?>>{{$local->locality??''}}</option>

                    <?php }}?>

                  </select>

                </div>

              </div>

              <div class="col-12 col-sm-6">

                <div class="form-group">

                  <label for="userName"> Canonical</label>

                  <input type="text" name="canonical" value="{{ old('canonical') }}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />

                </div>

              </div>

              <div class="col-12 col-sm-6">

                <div class="form-group">

                  <label for="userName">Robots</label>

                  <select name="robots" class="form-control">

                    <option value="index" >Index</option>

                    <option value="follow" >Follow</option>

                    <option value="index, follow" >Both</option>

                    <option value="noindex, nofollow" >No Index , No Follow</option>

                  </select>

                </div>

              </div>
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


              <div class="col-md-12">

                <div class="form-group">

                  <label>Meta KeyWord</label>

                  <textarea class="form-control" name="meta_keyword" placeholder="Meta KeyWord">{{old('meta_keyword')}}</textarea>

                </div>

              </div>

              <div class="col-md-12">

                <div class="form-group">

                  <label>Meta Title</label>

                  <textarea class="form-control" name="meta_title" id="meta_title" onkeyup="get_metatitle_length(this.value)" placeholder="Meta Title">{{old('meta_title')}}</textarea>

                  <span id="count_metatitle">0</span> Characters

                </div>

              </div>

              <div class="col-md-12">

                <div class="form-group">

                  <label>Meta Description</label>

                  <textarea class="form-control" name="meta_description" id="meta_description" onkeyup="get_metadescription_length(this.value)" placeholder="Meta Description">{{old('meta_description')}}</textarea>

                  <span id="count_metadescription">0</span> Characters

                </div>

              </div>

              <div class="col-12 col-sm-12">

                <div class="form-group">

                  <label for="userName">About</label>

                  <textarea name="about" id="about" class="form-control" placeholder="About">{{ old('about') }}</textarea>

                </div>

              </div>

              <div class="col-lg-2">

                <div class="search-student-btn">

                  <button type="btn" class="btn btn-primary">Submit</button>

                </div>

              </div>

            </div>

          </div>

        </form>

        <div class="row mt-3">

          <div class="col-sm-12">

            <div class="card card-table comman-shadow">

              <div class="card-body">

                <div class="page-header">

                  <div class="row align-items-center">

                    <div class="col">

                      <h3 class="page-title">{{$category->name??''}}</h3>

                    </div>

                    <div class="row">

                      <div class="col-md-12">

                        <form action="" method="get">

                          <div class="student-group-form">

                            <div class="row">

                              <div class="col-md-6">

                                <div class="form-group">

                                  <input type="text" name="search" class="form-control" placeholder="Search By Locality  ..." value="{{$_GET['search'] ??''}}">

                                </div>

                              </div>

                              <!-- city -->
                              <div class="col-md-6">

                                  <div class="form-group">

                                    <select class="form-control" name="city_id" id="city_id" required>

                                      <option value="" selected disabled>Select City</option>

                                      <?php if(!empty($cities)){

                                        foreach($cities as $city){

                                          ?>

                                          <option value="{{$city->id}}" <?php 
                                          if(isset($_GET['city_id']) && $_GET['city_id'] == $city->id){
                                            echo "selected";
                                          }
                                          ?>>{{$city->name??''}}</option>

                                        <?php }}?>

                                      </select>

                                    </div>

                                  </div>
                              <!-- end here -->

                              <div class="col-lg-2">

                                <div class="search-student-btn">

                                  <button type="btn" class="btn btn-primary">Search</button>

                                </div>

                              </div>

                            </div>

                          </div>

                        </form>

                      </div>

                    </div>

                    <div class="col-auto text-end float-end ms-auto download-grp">

                    </div>

                  </div>

                </div>

                <div class="table-responsive">

                  <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">

                    <thead class="student-thread">

                      <tr>

                        <th >SNo.

                        <th >Locality Name</th>

                        <th >Meta Title</th>

                        <th >Meta Description</th>

                        <th class="text-end">Action</th>

                      </tr>

                    </thead>

                    <tbody>

                     <?php if(!empty($categories_seos)){

                      $i = 1;

                      foreach($categories_seos as $catgory){

                        ?>

                        <tr>

                          <td>{{$i++}}</td>

                          <td>{{$catgory->locality_id}}</td>

                          <td>{{mb_strlen(strip_tags($catgory->meta_title),'utf-8') > 18 ? mb_substr(strip_tags($catgory->meta_title),0,18,'utf-8').'...' : strip_tags($catgory->meta_title)}}

                          </td>

                          <td>{{mb_strlen(strip_tags($catgory->meta_description),'utf-8') > 18 ? mb_substr(strip_tags($catgory->meta_description),0,18,'utf-8').'...' : strip_tags($catgory->meta_description)}}

                          </td>

                          <td>

                            <a data-bs-toggle="modal" data-bs-target="#updateCategorySEO{{$catgory->id}}" class="btn btn-primary"><i class="fas fa-edit"></i></a>

                              <a href="{{route($routeName.'.categories_seo.delete', ['id'=>$catgory->id,'back_url'=>$BackUrl])}}" title="Delete" class="btn btn-danger" onclick="return confirm('Are You Want To Delete ?')"><i class="fas fa-trash"></i></a>

                          </td>

                          <div id="updateCategorySEO{{$catgory->id}}" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-modal="true">

                            <div class="modal-dialog">

                              <div class="modal-content" style="width:147%">

                                <div class="modal-header">

                                  <h4 class="modal-title" id="standard-modalLabel">{{$category->name??''}}</h4>

                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                </div>

                                <form action="{{route('admin.categories_seo.update_category_seo')}}" method="post" enctype="multipart/form-data">

                                  @csrf

                                  <input type="hidden" name="id" value="{{$catgory->id}}">

                                  <div class="modal-body">

                                    <div class="row">

                                      <div class="col-12">

                                        <div class="form-group">

                                          <label for="userName"> Canonical</label>

                                          <input type="text" name="canonical" value="{{$catgory->canonical??''}}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />

                                        </div>

                                      </div>

                                      <div class="col-12">

                                        <div class="form-group">

                                          <label for="userName">Robots</label>

                                          <select name="robots" class="form-control">

                                            <option value="index" <?php if($catgory->canonical == 'index') echo "selected";?>>Index</option>

                                            <option value="follow" <?php if($catgory->canonical == 'follow') echo "selected";?>>Follow</option>

                                            <option value="index, follow" <?php if($catgory->canonical == 'index, follow') echo "selected";?>>Both</option>

                                            <option value="noindex, nofollow" <?php if($catgory->canonical == 'noindex, nofollow') echo "selected";?>>No Index , No Follow</option>

                                          </select>

                                        </div>

                                      </div>
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

                                      <div class="col-12 col-sm-12">

                                        <div class="form-group">

                                          <label for="userName">About</label>

                                          <textarea name="about" id="aboutall" class="form-control aboutall_cat_seo" placeholder="About">{{ $catgory->about ??''}}</textarea>

                                        </div>

                                      </div>

                                      <div class="col-md-12">

                                        <div class="form-group">

                                          <label>Meta KeyWord</label>

                                          <textarea class="form-control" name="meta_keyword" placeholder="Meta KeyWord">{{$catgory->meta_keyword??''}}</textarea>

                                        </div>

                                      </div>

                                      <div class="col-md-12">

                                        <div class="form-group">

                                          <label>Meta Title</label>

                                          <textarea class="form-control" name="meta_title" placeholder="Meta Title">{{$catgory->meta_title??''}}</textarea>

                                        </div>

                                      </div>

                                      <div class="col-md-12">

                                        <div class="form-group">

                                          <label>Meta Description</label>

                                          <textarea class="form-control" name="meta_description" placeholder="Meta Description">{{$catgory->meta_description??''}}</textarea>

                                        </div>

                                      </div>

                                    </div>

                                  </div>

                                  <div class="modal-footer">

                                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>

                                    <button type="submit" class="btn btn-primary">Save</button>

                                  </div>

                                </form>

                              </div>

                            </div>

                          </div>

                        </tr>

                      <?php }}?>

                    </tbody>

                  </table>

                  {{ $categories_seos->appends(request()->input())->links('admin.pagination') }}

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

    <script type="text/javascript">

      // CKEDITOR.replace('about');

      // CKEDITOR.replace('aboutall');
      $(document).ready(function() {
        $('.aboutall_cat_seo').summernote({
          height: 250,
        });


        $('#about').summernote({
          height: 450,
        });
     });

    </script>

    <script type="text/javascript">

      function update_popular(cat_id,is_popular) {

        if(is_popular.checked){

          is_popular = 1;

        }

        else{

          is_popular = 0;

        }

        var _token = '{{ csrf_token() }}';

        $.ajax({

          url: "{{route('admin.categories.update_popular')}}",

          type: "POST",

          data: {cat_id:cat_id,is_popular:is_popular},

          dataType:"JSON",

          headers:{'X-CSRF-TOKEN': _token},

          cache: false,

          success: function(resp){

          }

        });

      }

    </script>

    @endsection

