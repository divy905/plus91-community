@extends('admin.layouts.layouts')
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Contetent Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Content</li>
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
                        <form method="POST" action="{{ route('admin.settings.add') }}" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}

                            <input type="hidden" id="id" name="id" value="{{$data->id ?? ''}}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">Content <span><?php if (request()->has('back_url')) {
                                                                                                        $back_url = request('back_url');  ?>
                                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            <?php } ?></span></h5>
                                </div>
                                
                                <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">About Us</label>
                                        <textarea name="about_us" class="form-control" id="" cols="112" rows="5">{{ $data->about_us ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Privacy Policy</label>
                                        <textarea name="privacypolicy" class="form-control" id="" cols="112" rows="5">{{ $data->privacypolicy ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Terms & Conditions</label>
                                        <textarea name="terms" class="form-control" id="" cols="112" rows="5">{{ $data->terms ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Footer Title</label>
                                        <input type="text" class="form-control mb-3" name="footer_title" id="footer_title" placeholder="Enter Footer Title" value="{{ old('footer_title',($data->footer_title ?? '')) }}">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="form-group students-up-files">
                                        <label for="email" class="form-label">Footer Content</label>
                                        <textarea name="footer_desc" class="form-control" id="" cols="112" rows="5">{{ $data->footer_desc ?? '' }}</textarea>
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
    CKEDITOR.replace('description');
</script>