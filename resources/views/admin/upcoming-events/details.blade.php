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
                        <h3 class="page-title">Event Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">All Event Details</li>
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
                                    <h3 class="page-title">Event Details</h3>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>

                                        <th>SNo.
                                        <th>Name</th>
                                        <th>Event Name</th>
                                        <th>Transaction Id</th>
                                        <th>Event Date</th>
                                        <th>Payment Status</th>
                                        <th>Amount</th>
                                        <th>Booking Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data)) {

                                        $i = 1;
                                        foreach ($data as $row) {
                                    ?>
                                            <tr>

                                                <td>{{$i++}}</td>
                                                <td>
                                                    {{$row->userName ?? ''}} <br>
                                                    {{$row->email ?? ''}} <br>
                                                    {{$row->phone ?? ''}}
                                                </td>
                                                <td>{{$row->eventName ?? ''}}</td>
                                                <td>{{$row->razorpay_order_id ?? 'Na'}}</td>
                                                <td>{{$row->event_date}}</td>
                                                <td>{{$row->status}}</td>
                                                <td>₹{{$row->amount}}</td>
                                                <td>{{date('d M Y',strtotime($row->created_at))}}</td>
                                            </tr>
                                    <?php }
                                    } ?>
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
@endsection