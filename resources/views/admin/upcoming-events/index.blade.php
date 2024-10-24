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
            <h3 class="page-title">Events Management</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item active">All Events</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <form action="{{ url('admin/events') }}" method="get">
          @csrf
          <div class="student-group-form ">
            <div class="row">
              <div class="col-lg-5 col-md-5">
                <div class="form-group">
                  <input type="text" name="search" class="form-control" Placeholder="Search by name" @if(isset($_GET['search'])) value="{{ $_GET['search']}}" @endif>
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
                  <h3 class="page-title">Upcoming Events</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ url('admin/booking_event_list') }}" title="Booking Event List" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.events.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary" title="Add new event"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>
            @include('snippets.flash')
            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>
                    <th>SNo.
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Event Time</th>
                    <th>Free/Paid-Amt(₹)</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($upcomingEvents)) {
                    $i = 1;
                    foreach ($upcomingEvents as $new) {
                  ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$new->title ?? 'Na'}}</td>
                        <td>{{$new->event_date ?? 'Na'}}</td>
                        <td>{{$new->event_time ?? 'Na'}}</td>
                        <td>
                          @if($new->amount == '')
                          Free
                          @else
                          {{ $new->amount ?? '' }}
                          @endif
                        </td>
                        <td> <?php
                              if (!empty($new->image)) {
                                $image_name = $new->image;
                                if ($image_name) {
                              ?>
                              <div class=" image_box" style="display: inline-block">
                                <a href="{{ env('AWS_STORAGE_URL') . '/' . $image_name }}" target='_blank'><img src="{{ env('AWS_STORAGE_URL') . '/' . $image_name }}" width="80px" alt="Image"></a>
                              </div>
                            <?php
                                }
                              } else { ?>
                          <?php  } ?>
                        </td>
                        <td>
                          <select id='change_blog_status{{$new->id}}' class="form-control" onchange='change_blog_status({{$new->id}})'>
                            <option value='1' <?php if ($new->status == 1) echo 'selected' ?>>Active</option>
                            <option value='0' <?php if ($new->status == 0) echo 'selected' ?>>InActive</option>
                          </select>
                        </td>
                        <td class="text-end">
                          <div class="actions ">
                            <a href="{{ route($routeName.'.events.edit', $new->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                              <i class="feather-edit"></i>
                            </a>
                            <a href="{{ route($routeName.'.events.delete', $new->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                              <i class="feather-trash"></i>

                              <a href="{{ route($routeName.'.events.details', $new->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-danger-light">
                              <i class="feather-eye"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
              {{ $upcomingEvents->appends(request()->input())->links('admin.pagination') }}
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