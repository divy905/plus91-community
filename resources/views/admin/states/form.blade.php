@extends('admin.layouts.layouts')
@section('content')
<?php
$back_url = (request()->has('back_url'))?request()->input('back_url'):'';
if(empty($back_url)){
    $back_url = 'admin/states';
}

$name = (isset($states->name))?$states->name:'';
$country_id = (isset($states->country_id))?$states->country_id:'';

$id = (isset($states->id))?$states->id:'';

$status = (isset($states->status))?$states->status:1;

$storage = Storage::disk('public');

    //pr($storage);

$path = 'states/';
?>


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">{{ $page_Heading }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">{{ $page_Heading }}</li>
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

                                 <input type="hidden" id="id" value="{{$id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">State Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>

                           
                             

                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label for="exampleInputEmail1" class="form-label">Country Name</label>
                                     <select name="country_id" id="country_id" class="form-control select2-single">                                        
                                         <?php                                   
                                            foreach($countries as $country)
                                            {
                                         ?>
                                         <option value="{{$country->id}}" <?php if($country_id == $country->id) echo 'selected'; ?>>{{$country->name}}</option>
                                     <?php } ?>
                                     </select>
                                    </div>
                                </div>

 <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                         <label for="exampleInputEmail1" class="form-label">State Name</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter State Name" name="name" value="{{ old('name', $name) }}">
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