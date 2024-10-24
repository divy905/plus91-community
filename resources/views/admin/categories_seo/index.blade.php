@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';
$type = $_GET['type']??'';
?>
@section('content')


<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title"> Categories SEO</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All  Categories SEO</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <form action="" method="get">
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
                <option value="shop" <?php if($type == 'shop') echo "selected";?>>Shop</option>
                <option value="service" <?php if($type == 'service') echo "selected";?>>Service</option>
                <option value="others" <?php if($type == 'others') echo "selected";?>>Others</option>
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
                  <a data-bs-toggle="modal" data-bs-target="#importCatSEO" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>



            <div id="importCatSEO" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-modal="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Import</h4>
                    <div class="col-auto text-end float-end ms-auto download-grp">
                    <a href="{{url('public/uploads/category_seo_import.xlsx')}}" class="text-end"><i class="fa fa-download"></i>Sample</a>
                      
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{route('admin.categories_seo.import')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                      <div class="row">
                       <div class="col-md-12 mb-1">
                        <label>Upload FIle</label>
                        <input type="file" name="importfile" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>











          <div class="table-responsive">
            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
              <thead class="student-thread">
                <tr>

                  <th >SNo.
                  <th >Name</th>
                  <th >Type</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($categories)){

                  $i = 1;
                  foreach($categories as $catgory){
                    ?>
                    <tr>

                      <td>{{$i++}}</td>
                      <td>{{$catgory->name ?? ''}}</td>
                      <td>{{$catgory->type ?? ''}}</td>
                      <td class="text-end">
                        <div class="actions ">
                          <a href="{{ route($routeName.'.categories_seo.view', $catgory->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                            <i class="feather-eye"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php }}?>
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
  function update_popular(cat_id,is_popular) {
    if(is_popular.checked){
      is_popular = 1;
    }
    else{
      is_popular = 0;
    }

    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{route('admin.categories.update_popular')}}",
      type: "POST",
      data: {cat_id:cat_id,is_popular:is_popular},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
      }
    });
  }
</script>

@endsection
