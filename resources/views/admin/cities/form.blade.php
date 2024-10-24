@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$id = isset($cities->id) ? $cities->id : '';
$name = (isset($cities->name))?$cities->name:'';
$country_id=(isset($cities->country_id))?$cities->country_id:'';
$state_id=(isset($cities->state_id))?$cities->state_id:'';

$status = (isset($cities->status))?$cities->status:1;
$icon = (isset($cities->icon))?$cities->icon:'';
$storage = Storage::disk('public');

    //pr($storage);

$path = 'cities/';
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

                        <input type="hidden" id="id" value="{{$id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">City Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>



                            <div class="col-12 col-sm-4">
                                <div class="form-group students-up-files">
                                   <label for="exampleInputEmail1" class="form-label">Country Name</label>
                                   <select class="form-control select2-single" name="country_id" id="country_id">
                                     <option value="" selected disabled>Select Country Name</option>
                                     <?php 


                                     if(!empty($countries)){
                                        foreach($countries as $c) 
                                        {

                                            ?>
                                            <option value="{{$c->id}}" <?php if($country_id == $c->id) echo 'selected'; ?>>{{$c->name}}</option>
                                        <?php  } }  ?>
                                    </select>
                                    @include('snippets.errors_first', ['param' => 'country_id'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group students-up-files">
                                   <label for="exampleInputEmail1" class="form-label">State Name</label>

                                    <select class="form-control select2-single" name="state_id" id="state_id">
                                     <option value="" selected disabled>Select State Name</option>
                                     <?php 

                                     if(!empty($states)){
                                        foreach($states as $state) 
                                          {?>
                                            <option value="{{$state->id}}" <?php if($state_id == $state->id) echo 'selected'; ?>>{{$state->name}}</option>
                                        <?php  } }  ?>
                                    </select>
                                    @include('snippets.errors_first', ['param' => 'state_id'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group students-up-files">
                                   <label for="exampleInputEmail1" class="form-label">City Name</label>
                                   <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter City Name" name="name" value="{{ old('name', $name) }}">
                                   @include('snippets.errors_first', ['param' => 'name'])
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