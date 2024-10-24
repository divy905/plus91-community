@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$upcomingEvents_id = (isset($upcomingEvents->id)) ? $upcomingEvents->id : '';
$event_type = (isset($upcomingEvents->event_type)) ? $upcomingEvents->event_type : '';
$title = (isset($upcomingEvents->title)) ? $upcomingEvents->title : '';
$description = (isset($upcomingEvents->description)) ? $upcomingEvents->description : '';
$address = (isset($upcomingEvents->address)) ? $upcomingEvents->address : '';
$allowed_people_no = (isset($upcomingEvents->allowed_people_no)) ? $upcomingEvents->allowed_people_no : '';
$amount = (isset($upcomingEvents->amount)) ? $upcomingEvents->amount : '';
$image = (isset($upcomingEvents->image)) ? $upcomingEvents->image : '';
$status = (isset($upcomingEvents->status)) ? $upcomingEvents->status : '';
$event_date = (isset($upcomingEvents->event_date)) ? $upcomingEvents->event_date : '';
$event_time = (isset($upcomingEvents->event_time)) ? $upcomingEvents->event_time : '';

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
        <!-- @include('snippets.flash') -->
        <div class="row">
            <div class="col-sm-12">

                <div class="card comman-shadow">
                    <div class="card-body">
                        <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{ $upcomingEvents_id }}">
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
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control" maxlength="255" placeholder="Enter title" />
                                            <span class="text-danger">@include('snippets.errors_first', ['param' => 'title'])</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="address">Event Address <span class="text-danger">*</span></label>
                                            <input type="text" name="address" value="{{ old('address', $address) }}" id="address" class="form-control" maxlength="255" placeholder="Enter Event Address" />
                                            <span class="text-danger">@include('snippets.errors_first', ['param' => 'address'])</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="allowed_people_no">No. Of People Allowed <span class="text-danger">*</span> </label>
                                            <input type="number" name="allowed_people_no" value="{{ old('allowed_people_no', $allowed_people_no) }}" id="allowed_people_no" class="form-control" maxlength="255" placeholder="Enter No. Of People Allowed" />
                                            <span class="text-danger">@include('snippets.errors_first', ['param' => 'allowed_people_no'])</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="event_date">Event Date<span class="text-danger">*</span></label>
                                            <input type="date" name="event_date" value="{{ old('event_date', $event_date) }}" id="event_date" class="form-control" maxlength="255" placeholder="Enter event_date" />
                                            <span class="text-danger">@include('snippets.errors_first', ['param' => 'event_date'])</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="event_time">Event Time<span class="text-danger">*</span></label>
                                            <input type="time" name="event_time" value="{{ old('event_time', $event_time) }}" id="event_time" class="form-control" maxlength="255" placeholder="Enter event time Like: 2pm to 4pm" />
                                            <span class="text-danger">@include('snippets.errors_first', ['param' => 'event_time'])</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="display: none;">
                                    <div class="col-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description" id="summernote1">{{ old('description', $description) }}</textarea>
                                        </div>
                                    </div>
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
                                            <label for="payment_type">Payment Type<span class="text-danger">*</span></label>
                                            <select name="payment_type" id="payment_type" class="form-control" onchange="toggleAmountField(this.value)">
                                                <option value="Free" {{ old('payment_type', $amount) == '' ? 'selected' : '' }}>Free</option>
                                                <option value="Paid" {{ old('payment_type', $amount) != '' ? 'selected' : '' }}>Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6" id="amountField" style="display:none;">
                                        <div class="form-group">
                                            <label for="amount">Amount(₹) <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" value="{{ old('amount', $amount) }}" id="amount" class="form-control" maxlength="255" placeholder="Enter Amount" />
                                            <small id="amountError" class="form-text text-danger" style="display:none;">Please enter the amount for paid payment type.</small>
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
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="image">Upload Photo</label>
                                            <div class="uplod">
                                                <label class="file-upload image-upbtn mb-0">
                                                    Choose File <input type="file" id="photo" name="image" onchange="previewImage(this)">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-3">
                                        <div id="imagePreview" class="mt-2">
                                            @if($image)
                                            <img src="{{ env('AWS_STORAGE_URL') . '/' . $image }}" width="80px" alt="Image">
                                            @endif
                                        </div>
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
</div>


<script>
    // Function to toggle the visibility of amount field based on payment type selection
    function toggleAmountField(paymentType) {
        var amountField = document.getElementById('amountField');
        if (paymentType === 'Paid') {
            amountField.style.display = '';
        } else {
            amountField.style.display = 'none';
        }
    }

    // Initialize the visibility of amount field based on initial payment type selection
    window.onload = function() {
        var initialPaymentType = document.getElementById('payment_type').value;
        toggleAmountField(initialPaymentType);
    };
</script>

<script>
    // Function to toggle the visibility of amount field based on payment type selection
    function toggleAmountField(paymentType) {
        var amountField = document.getElementById('amountField');
        if (paymentType === 'Paid') {
            amountField.style.display = '';
        } else {
            amountField.style.display = 'none';
        }
    }

    // Initialize the visibility of amount field based on initial payment type selection
    window.onload = function() {
        var initialPaymentType = document.getElementById('payment_type').value;
        toggleAmountField(initialPaymentType);
    };
</script>

<script>
    // Function to preview image before uploading
    function previewImage(input) {
        if (input.files && input.files[0]) {
            console.log("launch i");
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-width: 100%;">');
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
        var table = 'blogs';

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the amount field visibility based on the initial value
        toggleAmountField(document.getElementById('payment_type').value);
    });

    function toggleAmountField(value) {
        var amountField = document.getElementById('amountField');
        var amountInput = document.getElementById('amount');
        var amountError = document.getElementById('amountError');

        if (value === 'Paid') {
            amountField.style.display = 'block';
            amountInput.setAttribute('required', 'required');
        } else {
            amountField.style.display = 'none';
            amountInput.removeAttribute('required');
            amountError.style.display = 'none';
        }
    }

    // Add form validation
    document.querySelector('form').addEventListener('submit', function(event) {
        var paymentType = document.getElementById('payment_type').value;
        var amount = document.getElementById('amount').value;
        var amountError = document.getElementById('amountError');

        if (paymentType === 'Paid' && !amount) {
            event.preventDefault();
            amountError.style.display = 'block';
        } else {
            amountError.style.display = 'none';
        }
    });
</script>
@endsection