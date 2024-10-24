@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'subcategory/';
// $types = \App\Models\Types::where('status',1)->where('is_delete',0)->get();
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">SubCategories</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All SubCategories</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post">
      @csrf
    <div class="student-group-form">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search  ...">
          </div>
        </div>
       <!--  <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Name ...">
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Phone ...">
          </div>
        </div> -->
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
                  <h3 class="page-title">SubCategories</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.subcategories.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                    <th >SNo.
                    <th >Category Name</th>
                    <th >Name</th>
                    <th >Image</th>
                    <th >Status</th>
                    <th >Date Created</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($subcategories)){

                    $i = 1;
                    foreach($subcategories as $sub_cat){
                 
                      ?>
                      <tr>

                        <td>{{$i++}}</td>
                        <td>{{CustomHelper::getCategoryName($sub_cat->category_id)}}</td>
                        <td>{{$sub_cat->name ?? ''}}</td>
                      
                          <td> <?php
                          if(!empty($sub_cat->image)){
                            $image_name = $sub_cat->image;
                              ?>
                              <div class=" image_box" style="display: inline-block">
                                <a href="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" target="_blank">
                                  <img src="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" style="width:70px;">
                                </a>
                              </div>
                              <?php
                          }else{?>

                            <?php  }?></td>
                            <td>
                              <select id='change_subcategories_status{{$sub_cat->id}}' class="form-control" onchange='change_subcategories_status({{$sub_cat->id}})'>
                                <option value='1'<?php if($sub_cat->status == 1) echo 'selected'?>>Active</option>
                                <option value='0'<?php if($sub_cat->status == 0) echo 'selected'?>>InActive</option>
                              </select>


                            </td>

                            <td>{{date('d M Y',strtotime($sub_cat->created_at))}}</td>


                            <td class="text-end">
                              <div class="actions ">
                                <a href="{{ route($routeName.'.subcategories.edit', $sub_cat->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                                  <i class="feather-edit"></i>
                                </a>
                                <a href="{{ route($routeName.'.subcategories.delete', $sub_cat->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                                  <i class="feather-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php }}?>
                      </tbody>
                    </table>
                    {{ $subcategories->appends(request()->input())->links('admin.pagination') }}

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      @endsection

      <script>

        function change_subcategories_status(id){
          var status = $('#change_subcategories_status'+id).val();


          var _token = '{{ csrf_token() }}';

          $.ajax({
            url: "{{ route($routeName.'.subcategories.change_subcategories_status') }}",
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