@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$types_id = (isset($types->id))?$types->id:'';
$name = (isset($types->name))?$types->name:'';
$status = (isset($types->status))?$types->status:'';

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

                        <input type="hidden" name="id" value="{{$types_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Type Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>

                           
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="userName">Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter  Name" />

                                        @include('snippets.errors_first', ['param' => 'name'])
                                    </div>
                                </div>



                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label>Upload  Icon </label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" name="image">
                                            </label>
                                        </div>
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

<!--  -->