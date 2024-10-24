@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$courses_id = (isset($courses->id))?$courses->id:'';
$category_id = (isset($courses->category_id))?$courses->category_id:'';
$subcategory_id = (isset($courses->subcategory_id))?$courses->subcategory_id:'';
$name = (isset($courses->name))?$courses->name:'';
$description = (isset($courses->description))?$courses->description:'';
$status = (isset($courses->status))?$courses->status:'';
$description = (isset($courses->description))?$courses->description:'';
$price = (isset($courses->price))?$courses->price:'';
$mrp = (isset($courses->mrp))?$courses->mrp:'';
$start_date = (isset($courses->start_date))?$courses->start_date:'';
$end_date = (isset($courses->end_date))?$courses->end_date:'';
$type = (isset($courses->type))?$courses->type:'';
$duration = (isset($courses->duration))?$courses->duration:'';
$priority = (isset($courses->priority))?$courses->priority:'';

$categories = \App\Models\ExamCategory::where('status',1)->where('is_delete',0)->get();


$category_ids = [];
$subcategory_ids = [];
$multiple = 'multiple';
if(empty($id)){
    $multiple = 'multiple';
}
if(!empty($category_id)){
    $category_ids = explode(",", $category_id);
}
if(!empty($subcategory_id)){
    $subcategory_ids = explode(",", $subcategory_id);
}



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

                        <input type="hidden" name="id" value="{{$courses_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Course Information <span>
                                    <?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?>
                            </span></h5>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="userName">Choose Course Category<span class="text-danger">*</span></label>
                                <select class="form-control select2" name="category_id[]" id="category_id" {{$multiple}}>
                                    <?php if(!empty($categories)){
                                        foreach($categories as $cat){
                                            ?>
                                            <option value="{{$cat->id}}" <?php if(in_array($cat->id,$category_ids)) echo "selected"?>>{{$cat->name??''}}</option>
                                        <?php }}?>
                                    </select>


                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>
                            </div>


                            <div class="col-12 col-sm-4">
                                <div class="form-group">
                                    <label for="userName">Choose  SubCategory<span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="subcategory_id[]" id="subcategory_id" {{$multiple}}>
                                        <?php if(!empty($subcategories)){
                                            foreach($subcategories as $cat){
                                                ?>
                                                <option value="{{$cat->id}}" <?php if(in_array($cat->id,$subcategory_ids)) echo "selected"?>>{{$cat->name??''}}</option>
                                            <?php }}?>
                                        </select>


                                        @include('snippets.errors_first', ['param' => 'subcategory_id'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Course Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter Course Name" />

                                        @include('snippets.errors_first', ['param' => 'name'])
                                    </div>
                                </div>



                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="userName">Description<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" id="description">{{old('description',$description)}}</textarea>


                                        @include('snippets.errors_first', ['param' => 'description'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="userName">Course Price<span class="text-danger">*</span></label>
                                        <input type="text" name="mrp" value="{{ old('mrp', $mrp) }}" id="mrp" class="form-control"  maxlength="255" placeholder="Enter Course Price" />

                                        @include('snippets.errors_first', ['param' => 'mrp'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="userName">Offered Price<span class="text-danger">*</span></label>
                                        <input type="text" name="price" value="{{ old('price', $price) }}" id="price" class="form-control"  maxlength="255" placeholder="Enter  Offered Price" />

                                        @include('snippets.errors_first', ['param' => 'price'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="userName">Start Date<span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" value="{{ old('start_date', $start_date) }}" id="start_date" class="form-control"  maxlength="255" placeholder="Enter start_date" />

                                        @include('snippets.errors_first', ['param' => 'start_date'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="userName">End Date<span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" value="{{ old('end_date', $end_date) }}" id="end_date" class="form-control"  maxlength="255" placeholder="Enter end_date" />

                                        @include('snippets.errors_first', ['param' => 'end_date'])
                                    </div>
                                </div>


                                 <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="userName">Type<span class="text-danger">*</span></label>
                                        <select class="form-control" name="type">
                                            <option value="" selected disabled>Select Type</option>
                                            <option value="day" <?php if($type == 'day') echo "selected";?>>Day</option>
                                            <option value="month" <?php if($type == 'month') echo "selected";?>>Month</option>
                                            <option value="year" <?php if($type == 'year') echo "selected";?>>Year</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'start_date'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="userName">Duration<span class="text-danger">*</span></label>
                                        <input type="text" name="duration" value="{{ old('duration', $duration) }}" id="duration" class="form-control"  maxlength="255" placeholder="Enter duration" />

                                        @include('snippets.errors_first', ['param' => 'duration'])
                                    </div>
                                </div>


 <div class="col-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="userName">Priority<span class="text-danger">*</span></label>
                                        <input type="text" name="priority" value="{{ old('priority', $priority) }}" id="priority" class="form-control"  maxlength="255" placeholder="Enter priority" />

                                        @include('snippets.errors_first', ['param' => 'priority'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label>Upload  Thumbnail </label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" name="thumbnail">
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label>Upload  Photo </label>
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


<!--  -->