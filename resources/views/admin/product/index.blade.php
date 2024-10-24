@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'product/';

?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Products</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Products</li>
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
                  <h3 class="page-title">Products</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.products.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                    <th>S.No.</th>
                    <th>Category Name</th>
                    <th>Product Name</th>                                                 
                    <th>Quantity</th>                                                 
                    <th>Image</th>                
                    <th>Status</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                 <?php if(!empty($product)){

                    $i = 1;
                    foreach($product as $val){
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{CustomHelper::getCategoryName($val->catId)}}</td>
                        <td>{{$val->prd_name}}</td>
                        <td>{{$val->prd_qty}}</td>
                        <td>
                            <?php
                               $image = isset($val->prd_images) ? $val->prd_images : '';
                                $storage = Storage::disk('public');
                                $path = 'products';
                                if(!empty($image))
                                {
                            ?>

                                <a href="{{ url('public/storage/'.$path.'/'.$image) }}" target='_blank'><img src="{{ url('public/storage/'.$path.'/'.$image) }}" style='width:100px;heith:100px;'></a>


                          <?php } ?>
                        </td>                 

                        <td>
                          <select id='change_product_status{{$val->id}}' onchange='change_product_status({{$val->id}})' class="form-control">
                            <option value='1' <?php if($val->status ==1)echo "selected";?> >Active</option>
                            <option value='0' <?php if($val->status ==0)echo "selected";?>>InActive</option>
                          </select> 


                        </td>

                       <td class="text-end">
                          <div class="actions ">
                            <a href="{{ route($routeName.'.products.edit', $val->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                              <i class="feather-edit"></i>
                            </a>
                            <a href="{{ route($routeName.'.products.delete', $val->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                              <i class="feather-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                    <?php }}?>
                  </tbody>
                </table>
              {{ $product->appends(request()->input())->links('admin.pagination') }}

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @endsection

  <script>

    function change_product_status(id){
      var status = $('#change_product_status'+id).val();


      var _token = '{{ csrf_token() }}';

      $.ajax({
        url: "{{ route($routeName.'.products.change_product_status') }}",
        type: "POST",
        data: {id:id, status:status},
        dataType:"JSON",
        headers:{'X-CSRF-TOKEN': _token},
        cache: false,
        success: function(resp){
          if(resp.success){
            alert(resp.message);
          }else{
            alert(resp.message);

          }
        }
      });


    }



  </script>