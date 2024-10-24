@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">News Management</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item active">All News</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <form action="{{ url('admin/news') }}" method="get">
          @csrf
          <div class="student-group-form ">
            <div class="row">
              <div class="col-lg-5 col-md-5">
                <div class="form-group">
                  <input type="text" name="search" class="form-control" Placeholder="Search by title" @if(isset($_GET['search'])) value="{{ $_GET['search']}}" @endif>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="search-student-btn">
                  <button type="submit" class="btn btn-primary">Search</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card card-table comman-shadow">
          <div class="card-body">
            <div class="page-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="page-title">News</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.news.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary" title="Add new event"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>
                    <th>SNo.
                    <th>Title</th>
                    <th>Url</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($news)) {
                    $i = 1;
                    foreach ($news as $new) {
                      $split = str_split($new->description, 60);
                      $desc = isset($split[0]) ? $split[0] . '...' : '...';

                      $split2 = str_split($new->url, 20);
                      $url = isset($split2[0]) ? $split2[0] . '...' : '...';

                  ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$new->title ?? 'Na'}}</td>
                        <td title="{{ $new->url }}"><a href="{{ $new->url }}" target="_blank" class="text-info">{{ $url ?? 'Na'}}</a></td>
                        <td title="{{ $new->description }}">{!! $desc ?? 'Na'!!}</td>
                        <td>
                          <select id='change_blog_status{{$new->id}}' class="form-control" onchange='change_blog_status({{$new->id}})'>
                            <option value='1' <?php if ($new->status == 1) echo 'selected' ?>>Active</option>
                            <option value='0' <?php if ($new->status == 0) echo 'selected' ?>>InActive</option>
                          </select>
                        </td>
                        <td class="text-end">
                          <div class="actions ">
                            <a href="{{ route($routeName.'.news.edit', $new->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                              <i class="feather-edit"></i>
                            </a>
                            <a href="{{ route($routeName.'.news.delete', $new->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are you sure want to delete this?')" class="btn btn-sm bg-danger-light">
                              <i class="feather-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
              {{ $news->appends(request()->input())->links('admin.pagination') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection