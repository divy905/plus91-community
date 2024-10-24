@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$gallery_id = (isset($gallery->id)) ? $gallery->id : '';
$title = (isset($gallery->title)) ? $gallery->title : '';
$status = (isset($gallery->status)) ? $gallery->status : '';;
$image = (isset($gallery->image)) ? $gallery->image : '';;
$images = (isset($gallery->images)) ? $gallery->images : '';;
$slug = (isset($gallery->slug)) ? $gallery->slug : '';


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

                            @php
                            $idSegment = intval(request()->segment(count(request()->segments())));
                            $data = DB::table('galleries')->where('id', $idSegment !== 0 ? $idSegment : '')->first();
                            @endphp

                            <input type="hidden" name="id" value="{{ $idSegment !== 0 ? $idSegment : '' }}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">Gallery <span><?php if (request()->has('back_url')) {                                                         $back_url = request('back_url');  ?><a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            <?php } ?></span></h5>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="userName">Title<span class="text-danger">*</span></label>
                                        <input type="text" name="title" value=" {{ $data->title ?? '' }}" id="title" class="form-control" maxlength="255"/>
                                        <span class="text-danger"> @include('snippets.errors_first', ['param' => 'title'])</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="userName">Main Image<span class="text-danger">*</span></label>
                                        <input type="file" name="image" value=" {{ $data->image ?? '' }}" id="image" class="form-control" maxlength="255" placeholder="Enter  image" onchange="previewImage(this)" accept=".jpg, .jpeg, .png, .gif" />
                                        <span class="text-danger"> @include('snippets.errors_first', ['param' => 'image'])</span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <!-- <img src="{{ url('public/storage/teams/'.$image) }}" style="width:100px;"> -->
                                    <div id="imagePreview" class="mt-2">
                                        @if($data?->image)
                                        <img src="{{ env('AWS_STORAGE_URL') . '/' . $data->image }}" width="80px" alt="Image">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="userName">Gallery Imgages Upload<span class="text-danger"> * (The file must be a file of type: jpg, jpeg, png, gif, pdf. are allowed only.)</span></label>
                                        <input type="file" name="images[]" value="{{ old('images', $images) }}" id="images" class="form-control" maxlength="255" multiple accept=".jpg, .jpeg, .png, .gif" />

                                        @include('snippets.errors_first', ['param' => 'images'])
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="col-12 col-sm-6">
                                        @foreach(explode(',', $data->images ?? '') as $rowimg)
                                        <img src="{{ env('AWS_STORAGE_URL') . '/' . $rowimg }}" width="80px" alt="Image">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-6">
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
</div>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            console.log("launch i");
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<a href="#" id="removeImage" class="remove-icon"><i class="fas fa-times"></i></a><br><img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-width: 100px;height:80px;">');
                $('#removeImage').on('click', function(e) {
                    e.preventDefault();
                    $('#imagePreview').empty();
                    $('#image').val('');
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script type="text/javascript">
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



    $("#title").keyup(function() {
        var title = $('#title').val();
        var _token = '{{ csrf_token() }}';
        var table = 'outlet';

        $.ajax({
            url: "{{ route('generate_slug') }}",
            type: "POST",
            data: {
                title: title,
                table: table
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': _token
            },
            cache: false,
            success: function(resp) {
                if (resp.success) {
                    $('#slug').val(resp.slug)
                } else {}

            }
        });
    });

    CKEDITOR.on('dialogDefinition', function(ev) {
        var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;
        if (dialogName == 'image') {
            //console.log(dialogDefinition);
            dialogDefinition.contents[2].elements[1].label = 'Continue';
        }
    });
</script>
@endsection