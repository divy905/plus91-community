@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';
$groups = DB::table('all_categories')->orderBy('id', 'DESC')->get();
?>
@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Delete and Modifed Users List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">All Users</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <form action="{{ url('admin/user') }}" method="post">
                @csrf
                <div class="student-group-form">
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" placeholder="Name/Mobile/MemberId">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="group_id">
                                    <option value="" selected>-- By Group Name --</option>
                                    @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="search-student-btn">
                                <button type="btn" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Users</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route($routeName.'.user.index', ['back_url' => $BackUrl]) }}" class="btn btn-primary" title="Back to Active Users list"><i class="fas fa-arrow-left"></i></a>
                                </div>
                            </div>
                        </div>
                        @include('snippets.flash')
                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>

                                        <th>SNo.
                                        <th>MEMBER#ID</th>
                                        <th>Name</th>
                                        <th>HOF</th>
                                        <th>Group Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Date Created</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)) {

                                        $i = 1;
                                        foreach ($users as $user) {
                                            $groupName =  DB::table('all_categories')->where('id', $user->group_id)->first();
                                            $headOfFamilyName = DB::table('users')->where('id', $user->head_of_family)->first();
                                    ?>
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$user->member_id ?? 'Na'}}</td>
                                                <td>{{$user->name ?? 'Na'}}</td>
                                                <td>{{$headOfFamilyName->name ?? 'Na'}}</td>
                                                <td>{{$groupName->name ?? 'Na'}}</td>
                                                <td>{{$user->email ?? 'Na'}}</td>
                                                <td>{{$user->phone ?? 'Na'}}</td>
                                                <td>{{date('d M Y',strtotime($user->created_at))}}</td>
                                                <td>
                                                    <select id='change_users_status{{$user->id}}' class="form-control" onchange='change_users_status({{$user->id}})'>
                                                        <option value='1' <?php if ($user->status == 1) echo 'selected' ?>>Approved</option>
                                                        <option value='0' <?php if ($user->status == 0) echo 'selected' ?>>Disapproved</option>
                                                        <option value='2' <?php if ($user->status == 2) echo 'selected' ?>>New</option>
                                                    </select>
                                                </td>
                                                <td class="text-end">
                                                    <div class="actions ">
                                                        <a href="{{ route($routeName.'.user.profile',['id'=>$user->id,'back_url'=>$BackUrl]) }}" title="View user details" class="btn btn-sm bg-danger-light">
                                                            <i class="feather-eye"></i>
                                                        </a>

                                                        <a href="{{ route($routeName.'.user.delete',['id'=>$user->id,'back_url'=>$BackUrl]) }}" class="btn btn-sm bg-danger-light btn-danger">
                                                            <i class="feather-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                            {{ $users->appends(request()->input())->links('admin.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection