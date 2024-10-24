@extends('admin.layouts.layouts')
<?php
$settings = \App\Models\Settings::first();
$users = DB::table('users')->count();
$usersActv = DB::table('users')->where('status', 1)->count();
$usersInActv = DB::table('users')->where('status', 0)->count();
$isTrustee = DB::table('users')->where('status', 1)->where('is_trustee', 1)->count();
$isCommitee = DB::table('users')->where('status', 1)->where('is_commitee', 1)->count();
$groups = DB::table('all_categories')->where('status', 1)->count();
$events = DB::table('events')->where('status', 1)->count();
$eventAmt = DB::table('transactions')->where('status', 1)->sum('amount');

?>


@section('content')

<div class="page-wrapper">
	<div class="content container-fluid">

		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<div class="page-sub-header">
						<h3 class="page-title">Welcome {{Auth::guard('admin')->user()->name??''}}!</h3>
						<ul class="breadcrumb">
							<li class="breadcrumb-item active">Home</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- 
		
		<form action="" method="post">
			@csrf
			<div class="student-group-form">
				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="form-group">
							<input type="date" name="start_date" value="{{$start_date??''}}" class="form-control" placeholder="Search by ID ...">
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="form-group">
							<input type="date" name="end_date" value="{{$end_date??''}}" class="form-control" placeholder="Search by Name ...">
						</div>
					</div> 
					<div class="col-lg-4">
						<div class="search-student-btn">
							<button type="btn" class="btn btn-primary">Search</button>
						</div>
					</div>
				</div>
			</div>
		</form> -->

		<div class="row mt-4">
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total Users</h6>
									<h3>{{$users??0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-01.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total Active Users</h6>
									<h3>{{$usersActv??0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-01.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total In-active Users</h6>
									<h3>{{$usersInActv ?? 0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-01.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total Groups</h6>
									<h3>{{$groups ?? 0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-01.svg')}}" alt="Dashboard Icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-01.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total Trustee</h6>
									<h3>{{$isTrustee??0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-01.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total Commitee</h6>
									<h3>{{$isCommitee??0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-02.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total Events</h6>
									<h3>{{$events ?? 0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-03.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a>
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Total Events Amt</h6>
									<h3>₹{{ number_format($eventAmt) ?? 0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-04.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<!-- <div class="row">
			<div class="col-md-12 col-lg-12">

				<div class="card card-chart">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-12">
								<h5 class="card-title">Student & Subscription</h5>
							</div>
							<div class="col-12">
								<ul class="chart-list-out">

									<li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div id="apexcharts-area"></div>
					</div>
				</div>

			</div>
					 --> <!-- <div class="col-md-12 col-lg-6">

							<div class="card card-chart">
								<div class="card-header">
									<div class="row align-items-center">
										<div class="col-6">
											<h5 class="card-title">Number of Students</h5>
										</div>
										<div class="col-6">
											<ul class="chart-list-out">
											<li><span class="circle-blue"></span>Girls</li>
												<li><span class="circle-green"></span>Boys</li> 
												<li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div id="bar"></div>
								</div>
							</div>

						</div> -->
	</div>


</div>
</div>


@endsection