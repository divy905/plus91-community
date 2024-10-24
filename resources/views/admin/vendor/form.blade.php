@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$image = (isset($businesses->image)) ? $businesses->image : '';
$status = (isset($businesses->status)) ? $businesses->status : '';
$alt = (isset($businesses->alt)) ? $businesses->alt : '';
$og_title = (isset($businesses->og_title)) ? $businesses->og_title : '';
$og_description = (isset($businesses->og_description)) ? $businesses->og_description : '';
$og_image = (isset($businesses->og_image)) ? $businesses->og_image : '';
$meta_title = (isset($businesses->meta_title)) ? $businesses->meta_title : '';
$meta_description = (isset($businesses->meta_description)) ? $businesses->meta_description : '';

$canonical = (isset($businesses->canonical)) ? $businesses->canonical : '';
$title = (isset($businesses->title)) ? $businesses->title : '';
$keywords = (isset($businesses->keywords)) ? $businesses->keywords : '';
$robots = (isset($businesses->robots)) ? $businesses->robots : '';



$image_name = substr($image, 0, strpos($image, "."));
$storage = Storage::disk('public');
$path = 'business_gallery/';
?>
<style>
    .row-margin {
        margin-bottom: 15px;
    }

    div.gallery {
        border: 1px solid #ccc;
    }

    div.gallery:hover {
        border: 1px solid #777;
    }

    div.gallery img {
        width: 100%;
        height: auto;
    }

    div.desc {
        padding: 15px;
        text-align: center;
    }

    * {
        box-sizing: border-box;
    }

    .responsive {
        padding: 0 6px;
        float: left;
        width: 24.99999%;
    }

    @media only screen and (max-width: 700px) {
        .responsive {
            width: 49.99999%;
            margin: 6px 0;
        }
    }

    @media only screen and (max-width: 500px) {
        .responsive {
            width: 100%;
        }
    }

    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

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
        @include('snippets.errors')
        @include('snippets.flash')
        <div class="row">
            <div class="col-sm-12">

                <div class="card comman-shadow">
                    <div class="card-body">
                        <form method="POST" action="{{url('admin/vendor_management/save-vendor')}}" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">Vendor Information <span><?php if (request()->has('back_url')) {
                                                                                                        $back_url = request('back_url');  ?>
                                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            <?php } ?></span></h5>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Mobile No.</label>
                                        <input type="number" name="phone" value="" id="phoneNo" class="form-control" maxlength="10" placeholder="Enter Mobile Number" />

                                        @include('snippets.errors_first', ['param' => 'phone'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Business Name</label>
                                        <input type="text" name="business_name" value="" id="businessName" class="form-control" maxlength="255" placeholder="Enter  Business Name" />

                                        @include('snippets.errors_first', ['param' => 'business_name'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Primary Business Category</label>
                                        <select name="primary_bus_cat" ,id="primary_bus_cat" class="selectpicker form-control" multiple data-live-search="true">
                                            @foreach($categories as $cat)
                                            <option value="{{$cat->id}}">{{$cat->service_cat}}</option>
                                            @endforeach

                                        </select>

                                        @include('snippets.errors_first', ['param' => 'bus_cat'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Primary Business Sub Category</label>
                                        <select name="primary_bus_sub_cat" ,id="primary_bus_sub_cat" class="selectpicker form-control" multiple data-live-search="true">
                                            @foreach($sub_categories as $subCat)
                                            <option value="{{$subCat->id}}">{{$subCat->sub_cat}}</option>
                                            @endforeach
                                        </select>

                                        @include('snippets.errors_first', ['param' => 'bus_cat_id1'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Secondary Business Category</label>
                                        <select name="sec_bus_cat" ,id="sec_bus_cat" class="selectpicker form-control" multiple data-live-search="true">
                                            @foreach($categories as $cat)
                                            <option value="{{$cat->id}}">{{$cat->service_cat}}</option>
                                            @endforeach
                                        </select>

                                        @include('snippets.errors_first', ['param' => 'bus_cat'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Secondary Business Sub Category</label>
                                        <select name="sec_bus_sub_cat" ,id="sec_bus_sub_cat" class="selectpicker form-control" multiple data-live-search="true">
                                            @foreach($sub_categories as $subCat)
                                            <option value="{{$subCat->id}}">{{$subCat->sub_cat}}</option>
                                            @endforeach
                                        </select>

                                        @include('snippets.errors_first', ['param' => 'bus_cat_id2'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Business Type</label>
                                        <select name="bus_type" id='business_type' class="form-control">s
                                            <option value="Shop">Shop</option>
                                            <option value="Service">Service</option>
                                        </select>

                                        @include('snippets.errors_first', ['param' => 'bus_type'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Business Image</label>
                                        <input type="file" name="bus_image" class="form-control" />
                                        <br>
                                            <a style="display:none" id="imgHref" href="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$image}}" target='_blank'><img id="imgSrc" src="{{env('IMAGE_URL')}}/business_gallery/thumb/{{$image}}" style='width:50px;height:50px;'></a>
                                        
                                        @include('snippets.errors_first', ['param' => 'image'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Owner Name</label>
                                        <input type="text" name="owner_name" value="" id="owner_name" class="form-control" maxlength="255" placeholder="Enter Owner Name" />

                                        @include('snippets.errors_first', ['param' => 'owner_name'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">State</label>
                                        <input type="text" name="state" id="state" class="form-control" placeholder="Enter State Name">

                                        @include('snippets.errors_first', ['param' => 'state'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">City</label>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="Enter City Name">

                                        @include('snippets.errors_first', ['param' => 'city'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Address</label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="Enter Address Name">

                                        @include('snippets.errors_first', ['param' => 'address'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Locality</label>
                                        <input type="text" name="locality" id="locality" class="form-control" placeholder="Enter Locality Name">

                                        @include('snippets.errors_first', ['param' => 'locality'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Landmark</label>
                                        <input type="text" name="landmark" id="landmark" class="form-control" placeholder="Enter Landmark Name">

                                        @include('snippets.errors_first', ['param' => 'landmark'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Service Area</label>
                                        <select name="service_area[]" ,id="service_area" class="selectpicker form-control" multiple data-live-search="true">
                                            @foreach($service_area as $val)
                                            <option value="{{$val->id}}">{{$val->locality}}</option>
                                            @endforeach
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'service_area'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Total Experience</label>
                                        <select name="vendorExp" id="vendorExp" class="form-control">
                                            <option value="">Please Select Year</option>
                                            <option value="1">1 year</option>
                                            <option value="2">2 year</option>
                                            <option value="3">3 year</option>
                                            <option value="4">4 year</option>
                                            <option value="5">5 year</option>
                                            <option value="6">6 year</option>
                                            <option value="7">7 year</option>
                                            <option value="8">8 year</option>
                                            <option value="9">9 year</option>
                                            <option value="10">10 year</option>
                                            <option value="10+">10+ year</option>
                                        </select>

                                        @include('snippets.errors_first', ['param' => 'vendorExp'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Availaibility(24X7)</label>
                                        <select name="24X7_availaibility" id="24X7_availaibility" class="form-control">
                                            <option value="">Please Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'paidLeads'])
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName"> Interested In Paid Leads</label>
                                        <select name="paid_leads" id="paid_leads" class="form-control">
                                            <option value="">please select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'paidLeads'])
                                    </div>
                                </div>

                                <div id="dynamic_field">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="userName">Services Provided</label>
                                                <input type="text" name="serviceName[]" class="form-control" placeholder="Enter Service Name">
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="userName">Service Price</label>
                                                <input type="text" name="servicePrice[]" class="form-control" placeholder="Enter Service Price">
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="userName">Add More Services</label>
                                                <input type="button" class="btn btn-primary form-control" id="add" value="Add More">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter Bank Name">

                                        @include('snippets.errors_first', ['param' => 'bank_name'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Account Holder Name</label>
                                        <input type="text" name="account_name" id="account_name" class="form-control" placeholder="Enter Bank Account Holder Name">

                                        @include('snippets.errors_first', ['param' => 'account_name'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Account No</label>
                                        <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Enter Bank Account Number">

                                        @include('snippets.errors_first', ['param' => 'account_no'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">IFSC Code</label>
                                        <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="Enter IFSC Code">

                                        @include('snippets.errors_first', ['param' => 'ifsc_code'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName"> Spoken Language</label>
                                        <select name="language" id="language" class="form-control">
                                            <option value="">Please Select</option>
                                            <option value="Hindi">Hindi</option>
                                            <option value="English">English</option>
                                            <option value="Both">Both</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'language'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">Do You Have Any Shop</label>
                                        <select name="shop" id="shop" class="form-control">
                                            <option value="">Please Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'language'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label for="userName">KYC</label>
                                        <select name="kyc" id="kycId" class="form-control">
                                            <option value="">please select </option>
                                            <option value="Online">Online</option>
                                            <option value="Offline">Offline</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4" id="enterOnlineAadhar" style="display:none">
                                    <div class="form-group">
                                        <label for="userName">Enter Aadhar No</label>
                                        <input type="text" name="aadhar" id="aadharNo" class="form-control" placeholder="Enter Aadhar No">
                                        <span style="color: red;" id="aadharError"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4" id="sendOTP" style="display:none">
                                    <div class="form-group">
                                        <label for="userName">Send Aadhar OTP</label>
                                        <input type="button" class="btn btn-primary form-control" id="sendAadharOTP" value="Send OTP">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4" id="enterAadhar" style="display:none">
                                    <div class="form-group">
                                        <label for="userName">Enter Aadhar No</label>
                                        <input name="aadhar" type="text" class="form-control" id="aadhar" placeholder="Enter Aadhar No.">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4" id="AadharFront" style="display:none">
                                    <div class="form-group">
                                        <label for="userName">Upload Aadhar Front Image</label>
                                        <input name="aadharFront" type="file" class="form-control" id="aadhar">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4" id="AadharBack" style="display:none">
                                    <div class="form-group">
                                        <label for="userName">Upload Aadhar Back Image</label>
                                        <input name="aadharBack" type="file" class="form-control" id="aadhar">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4" id="AadharBack">
                                    <div class="form-group">
                                        <label for="userName">Upload Work/Shop Images(max 10)</label>
                                        <input name="workImages[]" multiple type="file" class="form-control upload__inputfile" id="">
                                    </div>
                                </div>

                                <label for="userName">Image Gallery</label>

                                <div class="upload__img-wrap"></div>

                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_forest.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Forest" width="600" height="400">
                                        </a>
                                    </div>
                                </div>

                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_lights.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Northern Lights" width="600" height="400">
                                        </a>
                                    </div>
                                </div>

                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_mountains.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Mountains" width="600" height="400">
                                        </a>
                                    </div>
                                </div>
                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_mountains.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Mountains" width="600" height="400">
                                        </a>
                                    </div>
                                </div>
                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_mountains.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Mountains" width="600" height="400">
                                        </a>
                                    </div>
                                </div>
                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_mountains.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Mountains" width="600" height="400">
                                        </a>
                                    </div>
                                </div>
                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_mountains.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Mountains" width="600" height="400">
                                        </a>
                                    </div>
                                </div>
                                <div class="responsive">
                                    <div class="gallery">
                                        <a target="_blank" href="img_mountains.jpg">
                                            <img src="https://www.w3schools.com/css/img_5terre.jpg" alt="Mountains" width="600" height="400">
                                        </a>
                                    </div>
                                </div>






                                <input type="hidden" name="user_id" id=usrId>
                                <input type="hidden" name="bus_id" id=busId>
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


<!-- Modal -->
<div class="modal fade" id="otpModel" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">

            </div>
            <div class="modal-body">
                <div class="mb-3" id="enterOnlineAadhar">
                    <label for="exampleInputPassword1" class="form-label">Enter Aadhar OTP</label>
                    <div class="row row-margin">
                        <div class="col md-4">
                            <input type="hidden" name="ref_id" id="ref_id">
                            <input type="number" name="aadharOTP" id="aadharOTP" class="form-control" placeholder="Enter OTP">
                            <span id="otpError" style="color:red"></span>
                        </div>
                        <div class="col md-4">
                            <button type="button" class="btn btn-primary" id="verifyAadharOTP">Verify OTP</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>



<script>
    CKEDITOR.replace('description');
    $('select').selectpicker();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        var i = 1;
        var length;
        //var addamount = 0;

        $("#add").click(function() {


            i++;
            // $('#dynamic_field').append('<div class="row row-margin" id="row' + i + '"><div class="col md-3"><input type="text" class="form-control" name="serviceName[]" placeholder="Service Name"></div><div class="col md-4"><input type="text" name="servicePrice[]" class="form-control" placeholder="Price"></div><div class="col md-4"><button type="button" id="' + i + '" class="btn btn-danger btn_remove">remove</button></div></div>');
            $('#dynamic_field').append('<div class="row row-margin" id="row' + i + '"><div class="col-12 col-sm-4"> <div class="form-group"> <input type="text" name="serviceName[]" class="form-control" placeholder="Enter Service Name"> </div> </div> <div class="col-12 col-sm-4"> <div class="form-group"> <input type="text" name="servicePrice[]" class="form-control" placeholder="Enter Service Price"> </div> </div> <div class="col-12 col-sm-4"> <div class="form-group"> <input type="button" id="' + i + '" class="btn btn-danger form-control btn_remove" value="Remove"> </div> </div></div>');
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });



    });

    $("#phoneNo").on('change', function(event) {
        var phoneNo = $(this).val();

        $.ajax({
            url: "{{url('admin/vendor_management/fetchBusiness')}}",
            type: "GET",
            data: 'phoneNo=' + phoneNo,
            cache: false,
            success: function(result) {
                console.log('result', result)
                $("#businessName").val(result.businessData.business_name);
                // if (result.businessData.business_type == 'shop') {
                //     var busType = '<option Selected value="shop">Shop</option> <option value="service">Service</option>';
                // } else {
                //     var busType = '<option value="shop">Shop</option> <option Selected value="service">Service</option>';
                // }
                // $("#business_type").html(busType);imgHref
                $("#owner_name").val(result.businessData.owner_name);
                $("#address").val(result.businessData.address);
                $("#locality").val(result.businessData.locality);
                $("#businessName").val(result.businessData.business_name);
                $("#busId").val(result.businessData.id);
                $("#usrId").val(result.businessData.parent);
                $("#state").val(result.businessData.stateName);
                $("#city").val(result.businessData.cityName);
                let imgUrl = "{{env('IMAGE_URL')}}/business_gallery/thumb/"+result.businessData.image;
                $("#imgHref").attr("href", imgUrl);
                $("#imgSrc").attr("src", imgUrl);
                $("#imgHref").css("display", "block");

            }
        });

    });


    $("#kycId").on('change', function(event) {
        var kyc = $(this).val();
        if (kyc == 'Online') {
            $('#enterOnlineAadhar').css('display', 'block');
            $('#sendOTP').css('display', 'block');
            $('#AadharFront').css('display', 'none');
            $('#AadharBack').css('display', 'none');
            $('#enterAadhar').css('display', 'none');

        } else if (kyc == 'Offline') {
            $('#enterOnlineAadhar').css('display', 'none');
            $('#sendOTP').css('display', 'none');
            $('#enterAadhar').css('display', 'block');
            $('#AadharFront').css('display', 'block');
            $('#AadharBack').css('display', 'block');
        } else {
            $('#enterOnlineAadhar').css('display', 'none');
            $('#sendOTP').css('display', 'none');
            $('#enterAadhar').css('display', 'none');
            $('#AadharFront').css('display', 'none');
            $('#AadharBack').css('display', 'none');
        }
    });

    $("#sendAadharOTP").on('click', function(event) {
        var aadhaar_number = $('#aadharNo').val();
        var user_id = $('#usrId').val();
        if (aadhaar_number == '') {
            $('#aadharError').text('Please enter valid Aadhar No');
            return false;
        }
        $('#aadharError').text('');
        $('#sendAadharOTP').val('Loading...');




        $.ajax({
            url: "{{url('admin/vendor_management/sendAadharOTP')}}",
            type: "GET",
            data: 'aadhaar_number=' + aadhaar_number + '&user_id=' + user_id,
            cache: false,
            success: function(res) {
                if (res.result == true) {
                    $('#ref_id').val(res.ref_id);
                    $('#otpModel').modal('show');
                    $('#sendAadharOTP').val('Send OTP');

                } else {
                    alert(res.message);
                    $('#aadharNo').val('')
                    $('#sendAadharOTP').val('Send OTP');
                }


            }
        });

    });


    $("#verifyAadharOTP").on('click', function(event) {
        var otp = $('#aadharOTP').val();
        var user_id = $('#usrId').val();
        var ref_id = $('#ref_id').val();
        if (otp.length != 6) {
            $('#otpError').text('Please enter valid OTP');
            return false;
        }


        $.ajax({
            url: "{{url('admin/vendor_management/verifyAadharOTP')}}",
            type: "GET",
            data: 'otp=' + otp + '&user_id=' + user_id + '&ref_id=' + ref_id,
            cache: false,
            success: function(res) {
                if (res.result == true) {
                    console.log('ss');
                    $('#otpModel').modal('hide');
                } else {
                    alert(res.message);
                }


            }
        });

    });
</script>
@endsection