@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';
$type = $_GET['type'] ?? '';
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

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked+.slider:before {
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
            <h3 class="page-title"> Group Management</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Groups</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- <form action="" method="get">
      <div class="student-group-form">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" name="search" class="form-control" placeholder="Search  ...">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control" name="type">
                <option value="" selected>Select Type</option>
                <option value="shop" <?php if ($type == 'shop') echo "selected"; ?>>Shop</option>
                <option value="service" <?php if ($type == 'service') echo "selected"; ?>>Service</option>
                <option value="others" <?php if ($type == 'others') echo "selected"; ?>>Others</option>
              </select>
            </div>
          </div>
          <div class="col-lg-2">
            <div class="search-student-btn">
              <button type="btn" class="btn btn-primary">Search</button>
            </div>
          </div>
        </div>
      </div>
    </form> -->
    <div class="row">
      <div class="col-sm-12">
        <div class="card card-table comman-shadow">
          <div class="card-body">

            <div class="page-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="page-title"> Groups</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.groups.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>
            @include('snippets.flash')
            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>
                    <th>SNo.
                    <th>Image</th>
                    <th>Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($categories)) {

                    $i = 1;
                    foreach ($categories as $catgory) {
                  ?>
                      <tr>

                        <td>{{$i++}}</td>
                        <td>
                          <?php
                          $image = isset($catgory->image) ? $catgory->image : '';
                          $path = 'banners';
                          if (!empty($image)) {
                          ?>
                            <a href="{{ env('AWS_STORAGE_URL') . '/' . $image }}" target='_blank'><img src="{{ env('AWS_STORAGE_URL') . '/' . $image }}" width="80px" alt="Image"></a>
                          <?php } ?>
                        </td>
                        <td>{{$catgory->name ?? ''}}</td>
                        <td>
                          <div>
                            <a href="{{ route($routeName.'.groups.edit', $catgory->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                              <i class="feather-edit"></i>
                            </a>
                            <a href="{{ route($routeName.'.groups.delete', $catgory->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are you sure want to delete this?');" class="btn btn-sm bg-danger-light">
                              <i class="feather-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
              {{ $categories->appends(request()->input())->links('admin.pagination') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>


<script type="text/javascript">
  function update_popular(cat_id, is_popular) {
    if (is_popular.checked) {
      is_popular = 1;
    } else {
      is_popular = 0;
    }

    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{route('admin.groups.update_popular')}}",
      type: "POST",
      data: {
        cat_id: cat_id,
        is_popular: is_popular
      },
      dataType: "JSON",
      headers: {
        'X-CSRF-TOKEN': _token
      },
      cache: false,
      success: function(resp) {}
    });
  }
</script>

@endsection