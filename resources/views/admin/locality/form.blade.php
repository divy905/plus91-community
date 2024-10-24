@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$routeName = CustomHelper::getAdminRouteName();

$id = isset($locality->id) ? $locality->id : '';
$name = (isset($locality->name))?$locality->name:'';
$country_id=(isset($locality->country_id))?$locality->country_id:'';
$state_id=(isset($locality->state_id))?$locality->state_id:'';
$city_id=(isset($locality->city_id))?$locality->city_id:'';
$locality=(isset($locality->locality))?$locality->locality:'';
$status=(isset($locality->status))?$locality->status:'1';




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

                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Locality Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Country Name</label>
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

                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group">
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
                                    @include('snippets.errors_first', ['param' => 'slug'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                  <label for="exampleInputEmail1" class="form-label">City Name</label>
                                  <select class="form-control select2-single" name="city_id" id="city_id_new">
                                     <option value="" selected disabled>Select City Name</option>
                                     <?php 

                                     if(!empty($cities)){
                                        foreach($cities as $city) 
                                          {?>
                                            <option value="{{$city->id}}" <?php if($city_id == $city->id) echo 'selected'; ?>>{{$city->name}}</option>
                                        <?php  } }  ?>
                                    </select>

                                    @include('snippets.errors_first', ['param' => 'alt'])
                                </div>
                            </div>

                            <div class="col-12 col-sm-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">Locality</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Locality" name="locality" value="{{ old('locality', $locality) }}">
                                    @include('snippets.errors_first', ['param' => 'image'])
                                </div>
                            </div>



                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1" class="form-label">Status</label>
                                    <br>
                                    Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                    &nbsp;
                                    Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

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

  $('#country_id').change( function()
 {

    var _token = '{{ csrf_token() }}';
    var country_id = $('#country_id').val();
    $.ajax({
      url: "{{ route('admin.get_state') }}",
      type: "POST",
      data: {country_id:country_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
         $('#state_id').html(resp);
     }
 });
});

  
$('#state_id').change( function()
 {

    var _token = '{{ csrf_token() }}';
    var state_id = $('#state_id').val();
    $.ajax({
      url: "{{ route('admin.get_city') }}",
      type: "POST",
      data: {state_id:state_id},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
         $('#city_id_new').html(resp);
     }
 });
});



</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.select2-single').select2();
  });
</script>



@endsection
<script>
    CKEDITOR.replace( 'description' );
</script>
