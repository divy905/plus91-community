@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'course/';
$role_id = $_GET['role_id']??'';

// $sectionArr = config('modules.allowedwithval');
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

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
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
            <h3 class="page-title">Permission</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item active">All Permission</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="student-group-form">
      <form action="" method="">
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="form-group">
              <select class="form-control" name="role_id">
                <option value="" selected disabled>Select Role</option>
                <?php if(!empty($roles)){
                  foreach($roles as $role){
                    ?>
                    <option value="{{$role->id}}" <?php if($role_id == $role->id) echo "selected";?>>{{$role->name}}</option>
                  <?php }}?>
                </select>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="search-student-btn">
                <button type="btn" class="btn btn-primary">Search</button>
              </div>
            </div>
          </div>
        </form>

      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-table comman-shadow">
            <div class="card-body">

              <div class="page-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="page-title">Permission  {{$singlerole->name??''}}</h3>
                  </div>
                  <div class="col-auto text-end float-end ms-auto download-grp">

                  </div>
                </div>
              </div>

              <div class="table-responsive">
                <?php if(!empty($sectionArr)){?>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-4">
                              <h6 class="mb-3 header-title">Modules</h6>
                            </div>
                            <div class="col-md-2">
                              <h6 class="mb-3 header-title">Permission</h6>
                            </div>
                             <div class="col-md-4">
                              <h6 class="mb-3 header-title">Modules</h6>
                            </div>
                            <div class="col-md-2">
                              <h6 class="mb-3 header-title">Permission</h6>
                            </div>
                           <!--  <div class="col-md-2">
                              <h6 class="mb-3 header-title">Add</h6>
                            </div>
                            <div class="col-md-2">
                              <h6 class="mb-3 header-title">Update</h6>
                            </div>
                            <div class="col-md-2">
                              <h6 class="mb-3 header-title">Delete</h6>
                            </div>  -->

                            <div class="row mt-4">

                              <?php 
                              foreach ($sectionArr as $key => $value) {
                                $add = '';
                                $edit = '';
                                $list = '';
                                $delete = '';

                                $exist = \App\Models\Permission::where('role_id',$role_id)->where('section',$key)->first();
                                if(!empty($exist)){
                                  if($exist->add == 1){
                                    $add = 'checked';
                                  }
                                  if($exist->list == 1){
                                    $list = 'checked';
                                  }
                                  if($exist->edit == 1){
                                    $edit = 'checked';
                                  }
                                  if($exist->delete == 1){
                                    $delete = 'checked';
                                  }



                                }


                                ?>
                                <div class="col-md-4">
                                  <div class="mb-3">
                                    <h6>{{$value}}</h6>
                                  </div>
                                </div>

                                <div class="col-md-2">
                                  <div class="mb-3">
                                    <label class="switch">
                                      <input type="checkbox" {{$list}} onclick="update_permission('{{$key}}','{{$role_id}}','list',this)" id="checkboxlist{{$key}}">
                                      <span class="slider round"></span>
                                    </label>
                                  </div>
                                </div>

                              <!--   <div class="col-md-2">
                                  <div class="mb-3">
                                    <label class="switch">
                                      <input type="checkbox" {{$add}} onclick="update_permission('{{$key}}','{{$role_id}}','add',this)">
                                      <span class="slider round"></span>
                                    </label>
                                  </div>
                                </div> -->

                               <!--  <div class="col-md-2">
                                  <div class="mb-3">
                                    <label class="switch">
                                      <input type="checkbox" {{$edit}} onclick="update_permission('{{$key}}','{{$role_id}}','edit',this)">
                                      <span class="slider round"></span>
                                    </label>
                                  </div>
                                </div> -->
                               <!--  <div class="col-md-2">
                                  <div class="mb-3">
                                    <label class="switch">
                                      <input type="checkbox" {{$delete}} onclick="update_permission('{{$key}}','{{$role_id}}','delete',this)">
                                      <span class="slider round"></span>
                                    </label>
                                  </div>
                                </div>  -->

                              <?php }?>
                            </div>



                          </div> <!-- end card-body-->
                        </div> <!-- end card-->
                      </div>
                      <!-- end col -->

                    </div>

                  <?php }?>



                </div>






              </div>
            </div>
          </div>
        </div>
      </div>

    </div>



    <script type="text/javascript">
      function update_permission(key,role_id,section,permission) {
        if(permission.checked){
          permission = 1;
        }
        else{
          permission = 0;
        }

        var _token = '{{ csrf_token() }}';

        $.ajax({
          url: "{{ route($routeName.'.permission.update_permission') }}",
          type: "POST",
          data: {key:key,section:section,permission:permission,role_id:role_id},
          dataType:"JSON",
          headers:{'X-CSRF-TOKEN': _token},
          cache: false,
          success: function(resp){
          }
        });
      }
    </script>

    @endsection
