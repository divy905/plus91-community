@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$content_id = isset($contents->id) ? $contents->id : '';

$hls = isset($contents->hls) ? $contents->hls : '';
$course_id = isset($contents->course_id) ? $contents->course_id : '';
$subject_id = isset($contents->subject_id) ? $contents->subject_id : '';
$topic_id = isset($contents->topic_id) ? $contents->topic_id : '';
$hls_type = isset($contents->hls_type) ? $contents->hls_type : '';
$type = isset($contents->type) ? $contents->type : '';
$title = isset($contents->title) ? $contents->title : '';
$description = isset($contents->description) ? $contents->description : '';
$status = isset($contents->status) ? $contents->status : '';
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

                        <input type="hidden" id="id" value="{{$content_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Content Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>


                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label for="userName">Title<span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="" id="title" class="form-control" value="{{old('title',$title)}}" maxlength="255" placeholder="Enter  Title">

                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label for="userName">HLS Type<span class="text-danger">*</span></label>
                                    <select class="form-control mb-3" name="hls_type" id="hls_type" onchange="get_hls_type_value(this.value)">
                                        <option value="" selected>Select HLS Type</option> 
                                        <option value="videos" <?php if($hls_type == 'videos'){echo "selected";}?>>Videos</option>
                                        <option value="notes" <?php if($hls_type == 'notes'){echo "selected";}?>>Notes</option>

                                    </select>

                                </div>
                            </div>

                            <div class="col-12 col-sm-4" id="video_field">
                                <div class="form-group local-forms">
                                    <label for="userName">HLS<span class="text-danger">*</span></label>
                                    <input type="text" name="hls" value="" id="hls" class="form-control" value="{{old('hls',$hls)}}" maxlength="255" placeholder="Enter HLS">

                                </div>
                            </div>

                            <div class="col-12 col-sm-4" id="notes_field">
                                <div class="form-group local-forms">
                                    <label for="userName">Upload PDF<span class="text-danger">*</span></label>
                                    <input type="file" name="" value="" class="form-control" value="{{old('hls',$hls)}}" maxlength="255" placeholder="Enter HLS">

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


<script type="text/javascript">
    $(document).ready(function(){
        $('#video_field').hide();
        $('#notes_field').hide();

        var hls_type = $('#hls_type').val();
        if(hls_type == 'videos'){
            $('#notes_field').hide();
            $('#video_field').show();

        }
        if(hls_type == 'notes'){
            $('#video_field').hide();
            $('#notes_field').show();

        }
    });

    function get_hls_type_value(value){
        
         if(value == 'videos'){

            
            $('#notes_field').hide();
            $('#video_field').show();

        }
        if(value == 'notes'){
           $('#video_field').hide();
            $('#notes_field').show();

        }
    }


</script>

@endsection
