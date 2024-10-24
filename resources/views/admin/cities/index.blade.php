@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'course/';

?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">City</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All City</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="student-group-form d-none">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by ID ...">
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Name ...">
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Phone ...">
          </div>
        </div>
        <div class="col-lg-2">
          <div class="search-student-btn">
            <button type="btn" class="btn btn-primary">Search</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card card-table comman-shadow">
          <div class="card-body">

            <div class="page-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="page-title">City</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.cities.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                     <th>S.No.</th>
                    <th class="">Country</th>                     
                 <th class="">State </th>
                 <th class="">City Name </th>
                 <th class="">Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php if(!empty($cities) && $cities->count() > 0){
                $i = 1;
                foreach ($cities as $city){

                 $countryname = $city->cityCountry->name;

                 $cityState = (isset($city->cityState))?$city->cityState:'';
                 $stateName = (isset($cityState->name))?$cityState->name:'';

                 ?>
                      <tr>
                         <td>{{$i++}}</td>
                  <td>{{$countryname}}</td>
                  <td>{{$stateName}}</td>
                  <td>{{$city->name}}</td>
                   <td><?php  echo ($city->status==1)?'Active':'Inactive';  ?></td>

                       <td class="text-end">
                          <div class="actions ">
                            <a href="{{ route($routeName.'.cities.edit', $city->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                              <i class="feather-edit"></i>
                            </a>
                            <a href="{{ route($routeName.'.cities.delete', $city->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                              <i class="feather-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>
                </table>
              {{ $cities->appends(request()->input())->links('admin.pagination') }}

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @endsection
