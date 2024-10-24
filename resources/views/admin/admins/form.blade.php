@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$admins_id = (isset($admins->id))?$admins->id:'';
$name = (isset($admins->name))?$admins->name:'';
$description = (isset($admins->description))?$admins->description:'';
$location = (isset($admins->location))?$admins->location:'';
$state_id = (isset($admins->state_id))?$admins->state_id:'';
$city_id = (isset($admins->city_id))?$admins->city_id:'';
$username = (isset($admins->username))?$admins->username:'';
$phone = (isset($admins->phone))?$admins->phone:'';
$address = (isset($admins->address))?$admins->address:'';
$society_id = (isset($admins->society_id))?$admins->society_id:'';
$is_approve = (isset($admins->is_approve))?$admins->is_approve:'';
$role_id = (isset($admins->role_id))?$admins->role_id:'';
$email = (isset($admins->email))?$admins->email:'';




$status = (isset($admins->status))?$admins->status:'';






?>


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">{{ $page_heading }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">{{ $page_heading }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                     <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{$admins_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Admin Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label for="userName">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                 <label for="userName">UserName<span class="text-danger">*</span></label>
                                 <input type="text" name="username" value="{{ old('username', $username) }}" id="username" class="form-control"  maxlength="255" placeholder="Enter Username For Login" />

                                 @include('snippets.errors_first', ['param' => 'username'])
                             </div>
                         </div>
                         <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                              <label for="userName">Email<span class="text-danger">*</span></label>
                              <input type="text" name="email" value="{{ old('email', $email) }}" id="email" class="form-control"  maxlength="255" placeholder="Enter Email" />

                              @include('snippets.errors_first', ['param' => 'email'])
                          </div>
                      </div>
                      <div class="col-12 col-sm-4">
                        <div class="form-group local-forms ">
                          <label for="userName">Role<span class="text-danger">*</span></label>
                          <select name="role_id" class="form-control">
                            <option value="" selected disabled>Select Role</option>
                            <?php if(!empty($roles)){
                                foreach($roles as $role){
                                    ?>
                                    <option value="{{$role->id}}" <?php if($role->id == $role_id) echo "selected"?>>{{$role->name}}</option>
                                <?php }}?>
                            </select>

                            @include('snippets.errors_first', ['param' => 'role_id'])
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <label for="userName">Phone<span class="text-danger">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $phone) }}" id="phone" class="form-control" placeholder="Enter Phone"  maxlength="255" />


                            @include('snippets.errors_first', ['param' => 'phone'])
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <label for="userName">Address<span class="text-danger">*</span></label>
                        <input type="text" name="address" value="{{ old('address', $address) }}" id="address" class="form-control" placeholder="Enter Address"  maxlength="255" />


                        @include('snippets.errors_first', ['param' => 'phone'])
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <label for="userName">State<span class="text-danger">*</span></label>
                        <select name="state_id" class="form-control select2" id="state_id">
                            <option value="" selected disabled>Select State</option>
                            <?php if(!empty($states)){
                                foreach($states as $state){
                                    ?>
                                    <option value="{{$state->id}}" <?php if($state->id == $state_id) echo "selected"?>>{{$state->name}}</option>
                                <?php }}?>
                            </select>

                            @include('snippets.errors_first', ['param' => 'state_id'])
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                           <label for="userName">City<span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-control select2">
                                <option value="" selected disabled>Select City</option>
                                <?php if(!empty($cities)){
                                    foreach($cities as $city){
                                        ?>
                                        <option value="{{$city->id}}" <?php if($city->id == $city_id) echo "selected"?>>{{$city->name}}</option>
                                    <?php }}?>
                                </select>

                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                             <label for="userName">Password<span class="text-danger">*</span></label>
                                <input type="password" name="password" value="" id="password" class="form-control" placeholder="Enter Password"  maxlength="255" />


                                @include('snippets.errors_first', ['param' => 'password'])
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        
                            <label>Approve Status</label>
                                <div>
                                   Approved: <input type="radio" name="is_approve" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                   &nbsp;
                                   Not Approved: <input type="radio" name="is_approve" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                   @include('snippets.errors_first', ['param' => 'is_approve'])
                               </div>
                       
                    </div>
                    <div class="col-12 col-sm-4">
                        <label>Status</label>
                            <div>
                               Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                               &nbsp;
                               Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                               @include('snippets.errors_first', ['param' => 'status'])
                           </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="student-submit">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>

@endsection
<script>
    CKEDITOR.replace( 'description' );
</script>
