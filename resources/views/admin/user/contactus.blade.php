@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';
?>

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Contact Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">All Contacts</li>
                        </ul>
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
                                    <h3 class="page-title">Contacts</h3>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>SNo.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Date Created</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($data))
                                        @php $i = 1; @endphp
                                        @foreach ($data as $user)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $user->name ?? '' }}</td>
                                                <td><a href="mailto:{{ $user->email }}" class="text-info">{{ $user->email }}</a></td>
                                                <td>{{ $user->phone ?? '' }}</td>
                                                <td>{{ date('d M Y', strtotime($user->created_at)) }}</td>
                                                <td>
                                                    <select id='change_contact_status{{ $user->id }}' class="form-control" onchange='change_contact_status({{ $user->id }})'>
                                                        <option value='1' @if ($user->status == 1) selected @endif>Resolved</option>
                                                        <option value='0' @if ($user->status == 0) selected @endif>Pending</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success" onclick="showDetails({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->message }}')" title="View Query Details"><i class="fas fa-eye"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            {{ $data->appends(request()->input())->links('admin.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="detailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Contact Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="handleClose()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                <p><strong>Email:</strong> <span id="modal-email"></span></p>
                <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
                <p><strong>Query/Message:</strong> <span id="modal-message"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="handleClose()">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection


<script>
    function change_contact_status(id) {
        var status = $('#change_contact_status' + id).val();
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ route($routeName.'.contact_us.change_contact_status') }}",
            type: "POST",
            data: {
                id: id,
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

    function showDetails(id, name, email, phone, message) {
        $('#modal-name').text(name);
        $('#modal-email').text(email);
        $('#modal-phone').text(phone);
        $('#modal-message').text(message);
        $('#detailsModal').modal('show');
    }

    function handleClose() {
        $('#detailsModal').modal('hide');
        // Perform any additional actions you need here
    }
</script>
