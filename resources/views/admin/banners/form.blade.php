@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$banner_id = isset($banners->id) ? $banners->id : '';

$image = isset($banners->image) ? $banners->image : '';
$status = isset($banners->status) ? $banners->status : '';
$status = isset($banners->status) ? $banners->status : '';
$type = isset($banners->type) ? $banners->type : '';
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

                            <input type="hidden" id="id" value="{{$banner_id}}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">Banner Information <span><?php if (request()->has('back_url')) {
                                                                                                        $back_url = request('back_url');  ?>
                                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            <?php } ?></span></h5>
                                </div>

                                <!-- <div class="col-12 col-sm-4">
                                    <label>Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" id="">
                                        <option value="">-- Select Banner Type --</option>
                                        <option value="1" {{ $type==1 ? 'selected' : '' }}>Header Banner</option>
                                        <option value="2" {{ $type==2 ? 'selected' : '' }}>Footer Banner</option>
                                    </select>
                                    @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> -->
                                <div class="col-12 col-sm-3">
                                    <div class="form-group students-up-files">
                                        <label>Upload Photo <span class="text-danger">*</span></label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" id="photo" name="image" accept=".jpg, .jpeg, .png, .gif" onchange="previewImage(this)">
                                            </label>
                                        </div>
                                        @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3">
                                    <div id="imagePreview" class="mt-2">
                                        @if($image)
                                        <img src="{{ env('AWS_STORAGE_URL') . '/' . $image }}" width="80px" alt="Image">
                                        @endif
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
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            console.log("launch i");
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<a href="#" id="removeImage" class="remove-icon"><i class="fas fa-times"></i></a><br><img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-width: 100%;">');
                $('#removeImage').on('click', function(e) {
                    e.preventDefault();
                    $('#imagePreview').empty();
                    $('input[type="file"]').val('');
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(() => {

        $('#imgPreview').attr('src', '{{url("/public/assets/noimg.png")}}');


        $('#photo').change(function() {
            const file = this.files[0];
            console.log(file);
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    console.log(event.target.result);
                    $('#imgPreview').attr('src', event.target.result);
                    // $('#image_show').show();
                }
                reader.readAsDataURL(file);
            }
        });
    });

    CKEDITOR.replace('description');
</script>

<!--  -->