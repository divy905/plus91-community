@extends('admin.layouts.layouts')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Community Info Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Community Info</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @include('snippets.errors')
        @include('snippets.flash')
        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.community_info.add') }}" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}

                            <input type="hidden" id="id" name="id" value="{{$data->id}}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">Community Info<span><?php if (request()->has('back_url')) {
                                                                                                        $back_url = request('back_url');  ?>
                                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            <?php } ?></span></h5>
                                </div>
                                <!-- <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Title</label>
                                        <input type="text" class="form-control mb-3" name="title" id="title" placeholder="Enter Title" value="{{ old('title',$data->title) }}">
                                    </div>
                                </div> -->
                                <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Description</label>
                                        <textarea name="description" class="form-control" id="" cols="112" rows="5">{{ $data->description }}</textarea>
                                    </div>
                                </div>
                               
                                <!-- <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label>Upload Image (Choose 778px * 338px)</label>
                                        <div class="uplod">
                                            <label class="file-upload image-upbtn mb-0">
                                                Choose File <input type="file" name="image">
                                            </label>
                                        </div>
                                        <img src="{{ env('AWS_STORAGE_URL') . '/' . $data->image }}" width="80px" alt="Image">

                                    </div>
                                </div> -->
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
    CKEDITOR.replace('description');
</script>