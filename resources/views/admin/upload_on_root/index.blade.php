@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'blogs/';
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Upload On Root</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Blogs</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
      @csrf
    <div class="student-group-form">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="form-group">
            <input type="file" name="file" class="form-control" placeholder="Search  ...">
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
                  <h3 class="page-title">Upload On Root</h3>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>
                    <th >SNo.
                    <th >File</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($files)){

                    $i = 1;
                    foreach($files as $new){

                      ?>
                      <tr>

                        <td>{{$i++}}</td>
                        
                        <td><a href="{{url('/'.$new->name)}}" target="_blank">File</a></td>
                          
                          </tr>
                        <?php }}?>
                      </tbody>
                    </table>
                    {{ $files->appends(request()->input())->links('admin.pagination') }}

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      @endsection
