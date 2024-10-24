@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';

?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Admins</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Admins</li>
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
                  <h3 class="page-title">Admins</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.admins.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>
                    
                    <th >#ID</th>
                    <th >Name</th>
                    <th >UserName</th>
                    <th >Email</th>
                    <th >Role</th>
                    <th >Approved/UnAPproved</th>
                    <th >Status</th>
                    <th >Date Created</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($admins)){

              $i = 1;
              foreach($admins as $admin){
                ?>
                  <tr>
                 
                  <td>{{$i++}}</td>
                  <td>{{$admin->name ?? ''}}</td>
                  <td>{{$admin->username ?? ''}}</td>
                  <td>{{$admin->email ?? ''}}</td>
                  <td>
                    <select id='change_admins_role{{$admin->id}}' class="form-control" onchange='change_admins_role({{$admin->id}})'>
                      <option value="" selected disabled>Select Role</option>
                      <?php 
                      $roles = \App\Models\Role::where('status',1)->get();
                      if(!empty($roles)){
                        foreach($roles as $role){
                          ?>
                          <option value='{{$role->id}}' <?php if($admin->role_id == $role->id) echo 'selected'?>>{{$role->name}}</option>
                        <?php  }
                      }
                      ?>
                    </select>


                  </td>

                  <td>
                    <select id='change_admins_approve{{$admin->id}}' class="form-control" onchange='change_admins_approve({{$admin->id}})'>

                      <option value='1'<?php if($admin->is_approve == 1) echo 'selected'?>>Approved</option>
                      <option value='0'<?php if($admin->is_approve == 0) echo 'selected'?>>Not Approved</option>
                    </select>


                  </td>


                  <td>
                    <select id='change_admins_status{{$admin->id}}' class="form-control" onchange='change_admins_status({{$admin->id}})'>
                      <option value='1'<?php if($admin->status == 1) echo 'selected'?>>Active</option>
                      <option value='0'<?php if($admin->status == 0) echo 'selected'?>>InActive</option>
                    </select>


                  </td>

                  <td>{{date('d M Y',strtotime($admin->created_at))}}</td>


                  <td class="text-end">
                    <div class="actions ">
                      <a href="{{ route($routeName.'.admins.edit',['id'=>$admin->id,'back_url'=>$BackUrl]) }}" class="btn btn-sm bg-success-light me-2 ">
                        <i class="feather-edit"></i>
                      </a>
                      <a href="{{ route($routeName.'.admins.delete',['id'=>$admin->id,'back_url'=>$BackUrl]) }}" class="btn btn-sm bg-danger-light">
                        <i class="feather-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php }}?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

@endsection

<script>

  function change_admins_status(admin_id){
    var status = $('#change_admins_status'+admin_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.admins.change_admins_status') }}",
      type: "POST",
      data: {admin_id:admin_id, status:status},
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


  function change_admins_approve(admin_id){
    var approve = $('#change_admins_approve'+admin_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.admins.change_admins_approve') }}",
      type: "POST",
      data: {admin_id:admin_id, approve:approve},
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

  function change_admins_role(admin_id){
    var role_id = $('#change_admins_role'+admin_id).val();

    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.admins.change_admins_role') }}",
      type: "POST",
      data: {admin_id:admin_id, role_id:role_id},
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