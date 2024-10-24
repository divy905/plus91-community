@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$notifications_id = (isset($notifications->id)) ? $notifications->id : '';
$title = (isset($notifications->title)) ? $notifications->title : '';
$description = (isset($notifications->description)) ? $notifications->description : '';
$status = (isset($notifications->status)) ? $notifications->status : '';
$date = (isset($notifications->date)) ? $notifications->date : '';
$time = (isset($notifications->time)) ? $notifications->time : '';

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
        <!-- @include('snippets.errors') -->
        @include('snippets.flash')
        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $notifications_id }}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">Upcoming Information
                                        <span>
                                            @if (request()->has('back_url'))
                                            <?php $back_url = request('back_url'); ?>
                                            <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            @endif
                                        </span>
                                    </h5>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control" maxlength="255" placeholder="Enter title" />
                                            <span class="text-danger">@include('snippets.errors_first', ['param' => 'title'])</span>
                                        </div>
                                    </div>
                                    <!-- <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="date">Date<span class="text-danger">*</span></label>
                                            <input type="date" name="date" value="{{ old('date', $date) }}" id="date" class="form-control" maxlength="255" placeholder="Enter date" />
                                            <span class="text-danger">@include('snippets.errors_first', ['param' => 'date'])</span>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description" id="summernote1">{{ old('description', $description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <div>
                                                Active: <input type="radio" name="status" value="1" {{ $status == '1' ? 'checked' : '' }} checked>
                                                &nbsp;
                                                Inactive: <input type="radio" name="status" value="0" {{ $status == '0' ? 'checked' : '' }}>
                                            </div>
                                            @include('snippets.errors_first', ['param' => 'status'])
                                        </div>
                                    </div>

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

@endsection