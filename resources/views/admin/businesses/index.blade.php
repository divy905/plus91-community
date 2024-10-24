@extends('admin.layouts.layouts')

<?php

$BackUrl = CustomHelper::BackUrl();

$routeName = CustomHelper::getAdminRouteName();

$storage = Storage::disk('public');

$path = 'business_gallery/';

$search = $_GET['search']??'';

$category_id = $_GET['category_id']??'';

$type = $_GET['type']??'';

$start_date = $_GET['start_date']??'';

$end_date = $_GET['end_date']??'';

$categories = \App\Models\Category::where('status',1)->where('is_delete',0)->get();

$cities = DB::table('cities')->select('id','name')->get();

$city_id = $_GET['city_id']??'';

                // prd($local_data);



?>

@section('content')





<div class="page-wrapper">

  <div class="content container-fluid">



    <div class="page-header">

      <div class="row">

        <div class="col-sm-12">

          <div class="page-sub-header">

            <h3 class="page-title">Businesses</h3>

            <ul class="breadcrumb">

              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->

              <li class="breadcrumb-item active">All Businesses</li>

            </ul>

          </div>

        </div>

      </div>

    </div>



    <form action="" method="get">



      <div class="student-group-form">

        <div class="row">

          <div class="col-lg-3 col-md-3">

            <div class="form-group">

              <input type="text" name="search" value="{{$search??''}}" class="form-control" placeholder="Search  ...">

            </div>

          </div>



          <div class="col-lg-3 col-md-3">

            <div class="form-group">

              <select class="form-control select2" name="category_ids">

                <option value="" selected disabled>Select Category</option>

                <?php if(!empty($categories)){

                  foreach($categories as $cat){

                    ?>

                    <option value="{{$cat->id}}" <?php if($cat->id ==$category_id) echo "selected"?>>{{$cat->name}}</option>

                  <?php }}?>

                </select>

              </div>

            </div>



            <div class="col-lg-3 col-md-3">

              <div class="form-group">

                <select class="form-control select2" name="city_id">

                  <option value="" selected disabled>Select City</option>

                  <?php if(!empty($cities)){

                    foreach($cities as $cat){

                      ?>

                      <option value="{{$cat->id}}" <?php if($cat->id ==$city_id) echo "selected"?>>{{$cat->name}}</option>

                    <?php }}?>

                  </select>

                </div>

              </div>





              <div class="col-lg-3 col-md-3">

                <div class="form-group">

                  <select class="form-control select2" name="business_type">

                    <option value="" selected disabled>Select Category</option>

                    <option value="shop" <?php if($type == 'shop') echo "selected";?>>Shop</option>

                    <option value="service" <?php if($type == 'service') echo "selected";?>>Service</option>

                    <option value="others" <?php if($type == 'others') echo "selected";?>>Custom Location</option>

                  </select>

                </div>

              </div>





              <div class="col-lg-3 col-md-3">

                <div class="form-group">

                  <input type="date" name="start_date" value="{{$start_date}}" class="form-control">

                </div>

              </div>



              <div class="col-lg-3 col-md-3">

                <div class="form-group">

                  <input type="date" name="end_date" value="{{$end_date}}" class="form-control">

                </div>

              </div>



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

                      <h3 class="page-title">Businesses</h3>

                    </div>

                    <div class="col-auto text-end float-end ms-auto download-grp">

                      <!-- <a href="{{ route($routeName.'.businesses.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a> -->

                    </div>

                  </div>

                </div>



                <div class="table-responsive">

                  <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">

                    <thead class="student-thread">

                      <tr>



                        <th >SNo.

                        <th >Business Name</th>

                        <th >Type</th>

                        <th >Category</th>

                        <th >Image</th>

                        <th >Date Created</th>

                        <th class="text-end">Action</th>

                      </tr>

                    </thead>

                    <tbody>

                      <?php if(!empty($businesses)){



                        $i = 1;

                        foreach($businesses as $bus){

                      // $bus->image = '';

                          ?>

                          <tr>



                            <td>{{$i++}}</td>



                            <td>

                              <strong>

                                <p data-toggle="tooltip" data-placement="top" title="{{$bus->business_name}}">{{mb_strlen(strip_tags($bus->business_name),'utf-8') > 30 ? mb_substr(strip_tags($bus->business_name),0,30,'utf-8').'...' : strip_tags($bus->business_name)}}</p>



                              </strong><br>

                              <p data-toggle="tooltip" data-placement="top" title="{{$bus->address}}">{{mb_strlen(strip_tags($bus->address),'utf-8') > 30 ? mb_substr(strip_tags($bus->address),0,30,'utf-8').'...' : strip_tags($bus->address)}}</p><br>



                            </td>

                            <td>{{$bus->business_type ?? ''}}</td>

                            <td><ul data-role="treeview">

                              <?php

                              $categories = \App\Models\BusinessCategory::where('business_id',$bus->id)->get();

                              if(!empty($categories)){

                                foreach ($categories as $key) {

                                  $category = \App\Models\Category::where('id',$key->cat_id)->first();

                                  if(!empty($category)){

                                    ?>

                                    <li>{{$category->name??''}}</li>

                                    <?php if(!empty($key->sub_cat_id)){

                                      $sub_cat_id = explode(",", $key->sub_cat_id);

                                      if(!empty($sub_cat_id)){

                                        foreach ($sub_cat_id as $key=>$value){

                                          $sub_category = DB::table('sub_categories')->where('id',$value)->first();

                                          if(!empty($sub_category)){

                                            ?>

                                            <ul>

                                              <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$sub_category->name ??'' }}</li>

                                            </ul>

                                          <?php } }}}}

                                          ?>

                                        <?php }}?>

                                      </ul>

                                    </td>









                                    <td> <?php

                                    $image_name = $bus->image;



                          // if(!empty($bus->image)){

                          //   $image_name = $bus->image;

                          //   if($storage->exists($path.$image_name)){

                                    ?>

                             <!--  <div class=" image_box" style="display: inline-block">

                                <a href="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" target="_blank">

                                  <img src="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" style="width:70px;">

                                </a>

                              </div> -->

                              <?php //}}else{?>

                                <?if(!empty($bus->image)){?>

                                  <div class=" image_box" style="display: inline-block">

                                    <a href="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$bus->image}}" target="_blank">

                                      <img src="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$bus->image}}" style="width:70px; height: 94px;">

                                    </a>

                                  </div>

                                <?php }else{?>

                                  <div class=" image_box" style="display: inline-block">

                                    <a href="{{env('IMAGE_URL')}}/business_gallery/default-image.png" target="_blank">

                                      <img src="{{env('IMAGE_URL')}}/business_gallery/default-image.png" style="width:70px; height: 94px;">

                                    </a>

                                  </div>

                                <?php }?> 

                                <?php  //}?></td>



                                <td>{{date('d M Y',strtotime($bus->created_at))}}</td>





                                <td class="text-end">

                                  <div class="actions ">

                                    <a href="{{ route($routeName.'.businesses.edit', $bus->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">

                                      <i class="feather-edit"></i>

                                    </a>

                                   <!--  <a href="{{ route($routeName.'.businesses.delete', $bus->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">

                                      <i class="feather-trash"></i>

                                    </a> -->

                                  </div>

                                </td>

                              </tr>

                            <?php }}?>

                          </tbody>

                        </table>

                        {{ $businesses->appends(request()->input())->links('admin.pagination') }}



                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>



          </div>









          @endsection



          <script>



            function change_blog_status(id){

              var status = $('#change_blog_status'+id).val();





              var _token = '{{ csrf_token() }}';



              $.ajax({

                url: "{{ route($routeName.'.businesses.change_blog_status') }}",

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