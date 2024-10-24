@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$name = isset($user->name) ? $user->name : '';
$email = isset($user->email) ? $user->email : '';
$phone = isset($user->phone) ? $user->phone : '';
$image = isset($user->image) ? $user->image : '';
$education = isset($user->education) ? $user->education : '';
$total_exp = isset($user->total_exp) ? $user->total_exp : '';
$speciality = isset($user->speciality) ? $user->speciality : '';
$status = isset($user->status) ? $user->status : '1';
$is_approve = isset($user->is_approve) ? $user->is_approve : '0';
$about = isset($user->about) ? $user->about : '';
$username = isset($user->username) ? $user->username : '';
$address = isset($user->address) ? $user->address : '';



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

        @include('snippets.errors')
                        @include('snippets.flash')

        <div class="row">
            <div class="col-sm-6">
                <div class="card comman-shadow">
                    <div class="card-body">
                       <form method="POST" action="{{route('admin.profile')}}" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-6">
                                <h5 class="form-title student-info">Profile Information <span></span></h5>
                            </div>
                           
                            <div class="col-12 col-sm-12">
                                <div class="form-group local-forms">
                                    <label for="userName">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                                </div>
                            </div>


                             <div class="col-12 col-sm-12">
                                <div class="form-group local-forms">
                                    <label for="userName">Contact No<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control mb-3" name="phone" id="exampleInputNumber"  value="{{ old('phone',$phone) }}" placeholder="Enter Name" aria-label="default input example">

                                   
                                </div>
                            </div>




                            <div class="col-12 col-sm-12">
                                <div class="form-group local-forms">
                                    <label for="userName">Email<span class="text-danger">*</span></label>
                                   <input type="email" class="form-control mb-3" name="email" id="email"  value="{{ old('email',$email) }}" placeholder="Enter Email" aria-label="default input example">

                                    
                                </div>
                            </div>

                            <div class="col-12 col-sm-12">
                                <div class="form-group local-forms">
                                    <label for="userName">User Name<span class="text-danger">*</span></label>
                                    <input type="text" name="username" value="{{ old('username', $username) }}" id="username" class="form-control"  maxlength="255" placeholder="Enter username" />

                                    
                                </div>
                            </div> 

                            <div class="col-12 col-sm-12">
                                <div class="form-group local-forms">
                                    <label for="userName">Address<span class="text-danger">*</span></label>
                                    <input type="text" name="address" value="{{ old('address', $address) }}" id="address" class="form-control"  maxlength="255" placeholder="Enter address" />

                                    
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


    <div class="col-sm-6">
        <div class="card comman-shadow">
            <div class="card-body">
               <form method="POST" action="{{route('admin.change_password')}}" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-6">
                        <h5 class="form-title student-info">Change Password <span></span></h5>
                    </div>
                     
                    <div class="col-12 col-sm-12">
                        <div class="form-group local-forms">
                            <label for="userName">Old Password<span class="text-danger">*</span></label>
                            <input type="text" name="old_password" value="" id="old_password" class="form-control"  maxlength="255" placeholder="Old Password" />

                            @include('snippets.errors_first', ['param' => 'old_password'])
                        </div>
                    </div>

                    <div class="col-12 col-sm-12">
                        <div class="form-group local-forms">
                            <label for="userName">New Password<span class="text-danger">*</span></label>
                            <input type="text" name="new_password" value="" id="new_password" class="form-control"  maxlength="255" placeholder="New Password" />

                            @include('snippets.errors_first', ['param' => 'new_password'])
                        </div>
                    </div>

                    <div class="col-12 col-sm-12">
                        <div class="form-group local-forms">
                            <label for="userName">Confirm Password<span class="text-danger">*</span></label>
                            <input type="text" name="confirm_password" value="" id="confirm_password" class="form-control"  maxlength="255" placeholder="Confirm Password" />

                            @include('snippets.errors_first', ['param' => 'confirm_password'])
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
