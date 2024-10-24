@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'founders/';
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Trustee Management</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Trustee</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <form action="{{ url('admin/trustee') }}" method="get">
          @csrf
          <div class="student-group-form ">
            <div class="row">
              <!-- <div class="col-lg-5 col-md-5">
                <div class="form-group">
                  <select class="form-control" name="group_name">
                    <option value="" selected>--By Trustee/Commitee--</option>
                    <option value="Trustee">Trustee</option>
                    <option value="Commitee">Commitee</option>
                  </select>
                </div>
              </div> -->
              <div class="col-lg-5 col-md-5">
                <div class="form-group">
                  <input type="text" name="search" class="form-control" placeholder="Search...">
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
                  <h3 class="page-title">Trustee</h3>
                </div>
              </div>
            </div>
            @include('snippets.flash')
            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>
                    <th>SNo.
                    <th>Name</th>
                    <th>Image</th>
                    <!-- <th>Status</th> -->
                    <th>Date Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($data)) {
                    $i = 1;
                    foreach ($data as $new) {
                  ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$new->name ?? ''}}</td>
                        <td> <?php
                              if (!empty($new->image)) {
                                $image_name = $new->image;
                                if ($image_name) {
                              ?>
                              <div class=" image_box" style="display: inline-block">
                                <img src="{{ url('public/storage/trusty_n_comity/'.$image_name) }}" style="width:70px;">
                              </div>
                            <?php
                                }
                              } else { ?>
                            <img src="{{ url('public/assets/img/userimg.jpg') }}" style="width:70px; border-radius: 50%">
                          <?php  } ?>
                        </td>
                        <td>{{date('d M Y',strtotime($new->created_at))}}</td>
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