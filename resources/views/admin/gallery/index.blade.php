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
            <h3 class="page-title">Gallery Management</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Gallery</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <form action="{{ url('admin/gallery') }}" method="get">
          @csrf
          <div class="student-group-form ">
            <div class="row">
              <div class="col-lg-10 col-md-10">
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
                  <h3 class="page-title">Gallery</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.gallery.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>
            @include('snippets.flash')
            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                    <th>SNo.
                    <th>Title</th>
                    <th>Image</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($data)) {

                    $i = 1;
                    foreach ($data as $new) {
                  ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$new->title ?? 'Gallery Images:'}}</td>
                        <!-- @foreach(explode(',', $new->images) as $key=>$img)
                        <td><img src="{{ url('public/storage/galleries',$img) }}" width="100px" alt="" srcset=""></td>
                        @if($key==0)
                        @break
                        @endif
                        @endforeach -->
                        <td><img src="{{ env('AWS_STORAGE_URL') . '/' . $new->image }}" width="80px" alt="Image"></td>
                        <td class="text-end">
                          <div class="actions ">
                            <a href="{{ route($routeName.'.gallery.edit', $new->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                              <i class="feather-edit"></i>
                            </a>
                            <a href="{{ route($routeName.'.gallery.delete', $new->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                              <i class="feather-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
              {{ $data->appends(request()->input())->links('admin.pagination') }}

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection

<script>
  function change_blog_status(id) {
    var status = $('#change_blog_status' + id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.blogs.change_blog_status') }}",
      type: "POST",
      data: {
        id: id,
        status: status
      },
      dataType: "JSON",
      headers: {
        'X-CSRF-TOKEN': _token
      },
      cache: false,
      success: function(resp) {
        if (resp.success) {
          alert(resp.message);
        } else {
          alert(resp.message);

        }
      }
    });


  }
</script>