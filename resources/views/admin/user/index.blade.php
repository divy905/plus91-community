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
            <h3 class="page-title">Users Management</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
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
          <!-- <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Name ...">
          </div>
        </div> -->
          <!-- <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Phone ...">
          </div>
        </div> -->
          <div class="col-lg-2">
            <div class="search-student-btn">
              <button type="btn" class="btn btn-primary">Search</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <a href="{{url('admin/user/export')}}"><button class="btn btn-success">Export</button></a>
    <button type="button" class="btn btn-primary mx-2" data-toggle="modal" data-target="#importuser" style="height: 40px;">
      Import
    </button>
    <!-- The Modal -->
    <div class="modal fade" id="importuser">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Import User</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal Body -->
          <div class="modal-body">
            <form action="{{ url('admin/user/import') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="student-group-form">
                <div class="row">
                  <a href="{{url('public/assets/import.xlsx')}}">Format</a>
                  <div class="col-lg-8 col-md-8">
                    <div class="form-group">
                      <input type="file" name="import" class="form-control">
                    </div>
                  </div>
                  
                 
                  <div class="col-lg-2">
                    <div class="search-student-btn">
                      <button type="btn" class="btn btn-primary">Import</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>

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
                  <a href="{{ route($routeName.'.user.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
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
                  ?>
                      <tr>

                        <td>{{$i++}}</td>
                        <td>{{$user->member_id ?? 'Na'}}</td>
                        <td>{{$user->name ?? 'Na'}}</td>
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

                            <a href="{{ route($routeName.'.user.edit',['id'=>$user->id,'back_url'=>$BackUrl]) }}" title="Update user details" class="btn btn-sm bg-success-light me-2 ">
                              <i class="feather-edit"></i>
                            </a>

                            
                       <a href="{{ route($routeName.'.user.delete',['id'=>$user->id,'back_url'=>$BackUrl]) }}" class="btn btn-sm bg-danger-light btn-danger">
                        <i class="feather-trash"></i>
                      </a>
                            <a href="javascript:void(0);" class="btn btn-sm bg-primary-light me-2" data-bs-toggle="modal" data-bs-target="#updateGroupNameModal" title="Update Group" onclick="setUserGroup({{ $user->id }}, {{ $user->group_id }})">
                              <i class="feather-edit"></i>
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

<!-- Update Group Name Modal -->
<!-- Update Group Name Modal -->
<div class="modal fade" id="updateGroupNameModal" tabindex="-1" role="dialog" aria-labelledby="updateGroupNameModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateGroupNameModalLabel">Update Group Name</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="updateGroupNameForm" method="post" action="{{ route($routeName.'.user.updateGroupName') }}">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="groupName">Group Name</label>
            <select name="group_id" id="groupName" class="form-control">
              @foreach($groups as $row)
              <option value="{{ $row->id }}">{{ $row->name }}</option>
              @endforeach
            </select>
          </div>
          <input type="hidden" id="userId" name="user_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection
<script>
  function setUserGroup(userId, groupId) {
    document.getElementById('userId').value = userId;
    document.getElementById('groupName').value = groupId;
  }
</script>


<script>
  function change_users_status(user_id) {
    var status = $('#change_users_status' + user_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.user.change_users_status') }}",
      type: "POST",
      data: {
        id: user_id,
        status: status
      },
      dataType: "JSON",
      headers: {
        'X-CSRF-TOKEN': _token
      },
      cache: false,
      success: function(resp) {
        if (resp.success) {
          alert(resp.message);
        } else {
          alert(resp.message);

        }
      }
    });


  }
</script>
<!-- Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
