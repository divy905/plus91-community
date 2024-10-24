@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
$product_id = isset($products->id) ? $products->id : '';
$image = isset($products->prd_images) ? $products->prd_images : '';
$category_id = isset($products->catId) ? $products->catId : '';
$prd_desc = isset($products->prd_desc) ? $products->prd_desc : '';
$prd_qty = isset($products->prd_qty) ? $products->prd_qty : '';
$prd_name = isset($products->prd_name) ? $products->prd_name : '';
$status = isset($products->status) ? $products->status : '';
$qty = isset($products->qty) ? explode(',', $products->qty) : '';
$msr_unit = isset($products->msr_unit) ? explode(',', $products->msr_unit) : '';
$price = isset($products->price) ? explode(',', $products->price) : '';
$state_id = isset($products->state_id) ? $products->state_id : '';
$discounted_price = isset($products->discounted_price) ? explode(',', $products->discounted_price) : '';
$categories = \App\Models\Category::where('status', 1)->get();
$states =  DB::table('states')->get();
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

                            <input type="hidden" id="id" value="{{$product_id}}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">Product Information <span><?php if (request()->has('back_url')) {
                                                                                                        $back_url = request('back_url');  ?>
                                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            <?php } ?></span></h5>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Category</label>
                                        <select class="form-control" onchange="fetchSubCat()" name="catId" id="catId">
                                            <option value="">Select Category</option>
                                            <?php
                                            if (!empty($categories)) {
                                                foreach ($categories as $cat) {

                                            ?>
                                                    <option value="{{$cat->id}}" <?php if ($category_id == $cat->id) echo "selected"; ?>>{{$cat->name??''}}</option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Sub-Category</label>
                                        <select class="form-control" name="subCatId" id="subCatId">
                                            <option value="" selected>Select Sub-Category</option>
                                            <?php
                                            if (!empty($categories)) {
                                                foreach ($categories as $cat) {

                                            ?>
                                                    <option value="{{$cat->id}}" <?php if ($category_id == $cat->id) echo "selected"; ?>>{{$cat->name??''}}</option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">State</label>
                                        <select class="form-control"  name="state_id" id="catId">
                                            <option value="">Select States</option>
                                            <?php
                                            if (!empty($states)) {
                                                foreach ($states as $val) {

                                            ?>
                                                    <option value="{{$val->id}}" <?php if ($state_id == $val->id) echo "selected"; ?>>{{$val->states??''}}</option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Product Name</label>
                                        <input type="text" class="form-control mb-3" name="prd_name" id="prd_name" placeholder="Enter Product Name" value="{{ old('prd_name',$prd_name) }}">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Product Qty Availaible</label>
                                        <input type="text" class="form-control mb-3" name="prd_qty" id="prd_qty" placeholder="Enter Product Name" value="{{ old('prd_qty',$prd_qty) }}">
                                    </div>
                                </div>




                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label>Upload Product Main Image (Choose 778px * 338px)</label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" name="prd_images">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group students-up-files">
                                        <label>Upload Other Product's Images (Choose 778px * 338px up to 5)</label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" name="other_images[]" multiple>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="dynamic_field">
                                    @if($qty == '')
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="userName">Item Size</label>
                                                <input type="text" name="qty[]" class="form-control" placeholder="Enter Qty">
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="userName">Item meausre in unit</label>
                                                <input type="text" name="msr_unit[]" class="form-control" placeholder="Enter Measure Unit">
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="userName">Item Price</label>
                                                <input type="text" name="price[]" class="form-control" placeholder="Enter Item Price">
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="userName">Item Discount Price</label>
                                                <input type="text" name="discounted_price[]" class="form-control" placeholder="Enter Item Discounted Price">
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="userName">Add More</label>
                                                <input type="button" class="btn btn-primary form-control" id="add" value="Add More">
                                            </div>
                                        </div>
                                        @else
                                        @foreach($qty as $key => $val)
                                        <div class="row row-margin" id="row{{$key}}">
                                            <div class="col-12 col-sm-6 default_cursor_land">
                                                <div class="form-group default_cursor_land"> <input type="text" value="{{$val}}" name="qty[]" class="form-control" placeholder="Enter Qty"> </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group"> <input type="text" name="msr_unit[]" value="{{$msr_unit[$key]}}" class="form-control" placeholder="Enter Measure Unit"> </div>
                                            </div>
                                            <div class="col-12 col-sm-6 default_cursor_land">
                                                <div class="form-group"> <input type="text" name="price[]" value="{{$price[$key]}}" class="form-control" placeholder="Enter Item Price"> </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group"> <input type="text" value="{{$discounted_price[$key]}}" name="discounted_price[]" class="form-control" placeholder="Enter Item Discounted Price"> </div>
                                            </div>
                                            <div class="col-12 col-sm-6 default_cursor_land">
                                                <div class="form-group"> <input type="button" id="{{$key}}" class="btn btn-danger form-control btn_remove default_pointer_land" value="Remove"> </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>

                                </div>

                                <div class="col-12 col-sm-12">
                                    <div class="form-group local-forms">
                                        <label for="userName">Description<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="prd_desc" id="description">{{old('prd_desc',$prd_desc)}}</textarea>


                                        @include('snippets.errors_first', ['param' => 'prd_desc'])
                                    </div>
                                </div>






                                <div class="col-12 col-sm-4">
                                    <label>Status</label>
                                    <div>
                                        Active: <input type="radio" name="status" value="1" <?php echo ($status == '1') ? 'checked' : ''; ?> checked>
                                        &nbsp;
                                        Inactive: <input type="radio" name="status" value="0" <?php echo (strlen($status) > 0 && $status == '0') ? 'checked' : ''; ?>>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        var i = 1;
        var length;
        //var addamount = 0;

        $("#add").click(function() {


            i++;
            // $('#dynamic_field').append('<div class="row row-margin" id="row' + i + '"><div class="col md-3"><input type="text" class="form-control" name="serviceName[]" placeholder="Service Name"></div><div class="col md-4"><input type="text" name="servicePrice[]" class="form-control" placeholder="Price"></div><div class="col md-4"><button type="button" id="' + i + '" class="btn btn-danger btn_remove">remove</button></div></div>');
            $('#dynamic_field').append('<div class="row row-margin" id="row' + i + '"><div class="col-12 col-sm-6"> <div class="form-group"> <input type="text" name="qty[]" class="form-control" placeholder="Enter Qty"> </div> </div> <div class="col-12 col-sm-6"> <div class="form-group"> <input type="text" name="msr_unit[]" class="form-control" placeholder="Enter Measure Unit"> </div> </div> <div class="col-12 col-sm-6"> <div class="form-group"> <input type="text" name="price[]" class="form-control" placeholder="Enter Item Price"> </div> </div> <div class="col-12 col-sm-6"> <div class="form-group"> <input type="text" name="discounted_price[]" class="form-control" placeholder="Enter Item Discounted Price"> </div> </div> <div class="col-12 col-sm-6"> <div class="form-group"> <input type="button" id="' + i + '" class="btn btn-danger form-control btn_remove" value="Remove"> </div> </div></div>');
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });



    });

    function fetchSubCat() {
        var id = $('#catId option:selected').val();

        $.ajax({
            type: "GET",
            url: "{{url('admin/products/fetchSubCat')}}",
            data: {
                'catId': id
            },
            cache: false,
            success: function(result) {
                if (result.success) {
                    $('#subCatId').html(result.data)
                } else {
                    alert('something went wrong');
                }
            }

        })
    }
</script>




<!--  -->