@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';
$type = $_GET['type']??'';
?>
@section('content')
<style>
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  .switch input { 
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title"> Locality</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All  Locality</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <form action="" method="get">
      <div class="student-group-form ">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" name="search" class="form-control" placeholder="Search  ..." value="{{$_GET['search'] ??''}}">
            </div>
          </div>
          
          <div class="col-lg-2">
            <div class="search-student-btn">
              <button type="btn" class="btn btn-primary">Locality</button>
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
                  <h3 class="page-title"> Categories</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.locality.add',['back_url'=>$BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                    <th >SNo.
                    <th class="">State </th>
                    <th class="">City Name </th>
                    <th >Name</th>
                    <th >Slug</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($locality)){

                    $i = 1;
                    foreach($locality as $local){
                      $cityState = (isset($local->cityState))?$local->cityState:'';
                      $stateName = (isset($cityState->name))?$cityState->name:'';
                      $cityName = \App\Models\City::where('id',$local->city_id)->first()->name??'';
                      ?>
                      <tr>

                        <td>{{$i++}}</td>
                        <td>{{$stateName??''}}</td>
                        <td>{{$cityName??''}}</td>
                        <td>{{$local->locality ?? ''}}</td>
                        <td>{{$local->slug ?? ''}}</td>
                        <td>
                      <a class="btn btn-success" href="{{route($routeName.'.locality.edit', ['id'=>$local->id,'back_url'=>$BackUrl])}}" title="Edit"><i class="fas fa-edit"></i></a>
                    </td>
                        
                      </tr>
                    <?php }}?>
                  </tbody>
                </table>
                {{ $locality->appends(request()->input())->links('admin.pagination') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>


  @endsection
