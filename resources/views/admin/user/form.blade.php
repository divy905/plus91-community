@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$users_id = (isset($users->id)) ? $users->id : '';
$name = (isset($users->name)) ? $users->name : '';
$dob = (isset($users->dob)) ? $users->dob : '';
$phone = (isset($users->phone)) ? $users->phone : '';
$email = (isset($users->email)) ? $users->email : '';
$cmpny_name  = (isset($users->cmpny_name)) ? $users->cmpny_name : '';
$industry  = (isset($users->industry)) ? $users->industry : '';
$status = (isset($users->status)) ? $users->status : '';
$head_of_family = (isset($users->head_of_family)) ? $users->head_of_family : '';
$relation_with_head = (isset($users->relation_with_head)) ? $users->relation_with_head : '';
$native_village_id = (isset($users->native_village_id)) ? $users->native_village_id : '';
$bld_group = (isset($users->bld_group)) ? $users->bld_group : '';
$maritl_status = (isset($users->maritl_status)) ? $users->maritl_status : '';
$education = (isset($users->education)) ? $users->education : '';
$gotra_id = (isset($users->gotra_id)) ? $users->gotra_id : '';
$sasural_gotra_id = (isset($users->sasural_gotra_id)) ? $users->sasural_gotra_id : '';
$group_id = (isset($users->group_id)) ? $users->group_id : '';
$is_commitee = (isset($users->is_commitee)) ? $users->is_commitee : '';
$is_trustee = (isset($users->is_trustee)) ? $users->is_trustee : '';
$member_id = (isset($users->member_id)) ? $users->member_id : '';
$native_full_address = (isset($users->native_full_address)) ? $users->native_full_address : '';
$firm_address = (isset($users->firm_address)) ? $users->firm_address : '';
$residence_address = (isset($users->residence_address)) ? $users->residence_address : '';
// Example gotra and group arrays
$gotras = DB::table('goatra')->orderBy('id', 'DESC')->get();
$nativeVillage = DB::table('native_villags')->orderBy('id', 'DESC')->get();

$gender = (isset($users->gender)) ? $users->gender : '';
$designation = (isset($users->designation)) ? $users->designation : '';
$groups = DB::table('all_categories')->orderBy('id', 'DESC')->get();
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
                            <input type="hidden" name="is_registered" value="1">
                            <input type="hidden" name="id" value="{{$users_id}}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">User Information <span><?php if (request()->has('back_url')) {
                                                                                                    $back_url = request('back_url');  ?>
                                                <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                            <?php } ?></span></h5>
                                </div>
                                <div class="col-4 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="userEmail">Member #ID</label>
                                        <input type="text" name="member_id" value="{{ old('member_id', $member_id) }}" id="member_id" class="form-control" maxlength="255" placeholder="Enter Member #ID Like: MBRID#6435" />
                                        <span class="text-danger"> @include('snippets.errors_first', ['param' => 'member_id'])</span>
                                    </div>
                                </div>
                                <div class="col-4 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="industry">Industry</label>
                                        <input type="text" name="industry" value="{{ old('industry', $industry) }}" id="industry" class="form-control" maxlength="255" placeholder="Enter Industry" />
                                    </div>
                                </div>

                                <div class="col-4 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="industry">Company Name</label>
                                        <input type="text" name="cmpny_name" value="{{ old('cmpny_name', $cmpny_name) }}" id="cmpny_name" class="form-control" maxlength="255" placeholder="Enter Company Name" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="userName">Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control" maxlength="255" placeholder="Enter Name" />
                                        <span class="text-danger">@include('snippets.errors_first', ['param' => 'name'])</span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="userEmail">Email</label>
                                        <input type="text" name="email" value="{{ old('email', $email) }}" id="email" class="form-control" maxlength="255" placeholder="Enter Email">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="userPhone">Phone</label>
                                        <input type="text" name="phone" value="{{ old('phone', $phone) }}" id="phone" class="form-control" placeholder="Enter Phone" maxlength="255" />

                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="userDob">DOB</label>
                                        <input type="date" name="dob" value="{{ old('dob', $dob) }}" id="dob" class="form-control" placeholder="Enter DOB" maxlength="255" />
                                        @include('snippets.errors_first', ['param' => 'dob'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="head_of_family">Head of Family<span class="text-danger">*</span></label>
                                        <input type="text" name="head_of_family" value="{{ old('head_of_family', $head_of_family) }}" id="head_of_family" class="form-control" maxlength="255" placeholder="Enter Head of Family" />
                                        <span class="text-danger">@include('snippets.errors_first', ['param' => 'head_of_family'])</span>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="relation_with_head">Relation with Head</label>
                                        <input type="text" name="relation_with_head" value="{{ old('relation_with_head', $relation_with_head) }}" id="relation_with_head" class="form-control" maxlength="255" placeholder="Enter Relation with Head" />
                                        @include('snippets.errors_first', ['param' => 'relation_with_head'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="ntv_vlg">Native Village</label>
                                        <select name="native_village_id" id="native_village_id" class="form-control">
                                            <option value="">-- Select Native Village --</option>
                                            @foreach($nativeVillage as $village)
                                            <option value="{{ $village->id }}" {{ $native_village_id == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                                            @endforeach


                                        </select>
                                        @include('snippets.errors_first', ['param' => 'ntv_vlg'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="bld_group">Blood Group</label>
                                        <select name="bld_group" id="bld_group" class="form-control">
                                            <option value="" disabled {{ old('bld_group', $bld_group) ? '' : 'selected' }}>Select Blood Group</option>
                                            <option value="A+" {{ old('bld_group', $bld_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ old('bld_group', $bld_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ old('bld_group', $bld_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ old('bld_group', $bld_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="AB+" {{ old('bld_group', $bld_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ old('bld_group', $bld_group) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            <option value="O+" {{ old('bld_group', $bld_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ old('bld_group', $bld_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'bld_group'])
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="0" {{ old('gender', $gender) == '0' ? 'selected' : '' }}>Male</option>
                                            <option value="1" {{ old('gender', $gender) == '1' ? 'selected' : '' }}>Female</option>
                                            <option value="2" {{ old('gender', $gender) == '2' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'gender'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="maritl_status">Marital Status</label>
                                        <select name="maritl_status" id="maritl_status" class="form-control">
                                            <option value="Married" {{ old('maritl_status', $maritl_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Single" {{ old('maritl_status', $maritl_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Divorced" {{ old('maritl_status', $maritl_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Widow" {{ old('maritl_status', $maritl_status) == 'Widow' ? 'selected' : '' }}>Widow</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'maritl_status'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="maritl_status">Profession List</label>
                                        <select name="designation" id="designation" class="form-control">
                                            <option value="Doctors/Medical Professionals" {{ old('designation', $designation) == 'Doctors/Medical Professionals' ? 'selected' : '' }}>Doctors/Medical Professionals</option>
                                            <option value="Engineers" {{ old('designation', $designation) == 'Engineers' ? 'selected' : '' }}>Engineers</option>
                                            <option value="Lawyers/Legal Professionals" {{ old('designation', $designation) == 'Lawyers/Legal Professionals' ? 'selected' : '' }}>Lawyers/Legal Professionals</option>
                                            <option value="Chartered Accountants (CAs)" {{ old('designation', $designation) == 'Chartered Accountants (CAs)' ? 'selected' : '' }}>Chartered Accountants (CAs)</option>
                                            <option value="Teachers/Educators" {{ old('designation', $designation) == 'Teachers/Educators' ? 'selected' : '' }}>Teachers/Educators</option>
                                            <option value="Civil Servants (IAS, IPS, IFS, etc.)" {{ old('designation', $designation) == 'Civil Servants (IAS, IPS, IFS, etc.)' ? 'selected' : '' }}>Civil Servants (IAS, IPS, IFS, etc.)</option>
                                            <option value="Scientists/Researchers" {{ old('designation', $designation) == 'Scientists/Researchers' ? 'selected' : '' }}>Scientists/Researchers</option>
                                            <option value="Architects" {{ old('designation', $designation) == 'Architects' ? 'selected' : '' }}>Architects</option>
                                            <option value="Entrepreneurs" {{ old('designation', $designation) == 'Entrepreneurs' ? 'selected' : '' }}>Entrepreneurs</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'designation'])
                                    </div>
                                </div>



                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="education">Education</label>
                                        <select required name="education" id="education" class="form-control">
                                            <option value="Undergraduate" {{ old('education', $education) == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                            <option value="Greduate" {{ old('education', $education) == 'Greduate' ? 'selected' : '' }}>Greduate</option>
                                            <option value="Post Graduate" {{ old('education', $education) == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                            <option value="PHD" {{ old('education', $education) == 'PHD' ? 'selected' : '' }}>PHD</option>
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'education'])
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="native_full_address">Native Full Address</label>
                                        <input type="text" name="native_full_address" value="{{ old('native_full_address', $native_full_address) }}" id="native_full_address" class="form-control" maxlength="255" placeholder="Enter native address" />
                                        @include('snippets.errors_first', ['param' => 'native_full_address'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="firm_address">Firm Address</label>
                                        <input type="text" name="firm_address" value="{{ old('firm_address', $firm_address) }}" id="firm_address" class="form-control" maxlength="255" placeholder="Enter firm address" />
                                        @include('snippets.errors_first', ['param' => 'firm_address'])
                                    </div>
                                </div>


                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="residence_address">residence_address</label>
                                        <input type="text" name="residence_address" value="{{ old('residence_address', $residence_address) }}" id="residence_address" class="form-control" maxlength="255" placeholder="Enter residence address" />
                                        @include('snippets.errors_first', ['param' => 'residence_address'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="gotra_id">Gotra</label>
                                        <select name="gotra_id" id="gotra_id" class="form-control">
                                            <option value="">-- Select Gotra --</option>
                                            @foreach($gotras as $gotra)
                                            <option value="{{ $gotra->id }}" {{ old('gotra_id', $gotra_id) == $gotra->id ? 'selected' : '' }}>{{ $gotra->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'gotra_id'])
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="sasural_gotra_id">Sasural Paksh Gotra</label>
                                        <select name="sasural_gotra_id" id="sasural_gotra_id" class="form-control">
                                            <option value="">-- Select Gotra --</option>
                                            @foreach($gotras as $gotra)
                                            <option value="{{ $gotra->id }}" {{ old('sasural_gotra_id', $sasural_gotra_id) == $gotra->id ? 'selected' : '' }}>{{ $gotra->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'sasural_gotra_id'])
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label for="group_id">Group</label>
                                        <select name="group_id" id="group_id" class="form-control">
                                            <option value="">-- Select Group --</option>
                                            @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ old('group_id', $group_id) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                        @include('snippets.errors_first', ['param' => 'group_id'])
                                    </div>
                                </div>

                                <!-- <div class="col-12 col-sm-4">
                                    <label class="form-check-label" for="is_commitee">Is Committee</label>
                                    <div class="form-group local-forms form-switch">
                                        <input type="checkbox" name="is_commitee" value="1" {{ old('is_commitee', $is_commitee) ? 'checked' : '' }} id="is_commitee" class="form-check-input" />
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4">
                                    <label class="form-check-label" for="is_trustee">Is Trustee</label>
                                    <div class="form-group local-forms form-switch">
                                        <input type="checkbox" name="is_trustee" value="1" {{ old('is_trustee', $is_trustee) ? 'checked' : '' }} id="is_trustee" class="form-check-input" />
                                    </div>
                                </div> -->
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
    CKEDITOR.replace('description');
</script>