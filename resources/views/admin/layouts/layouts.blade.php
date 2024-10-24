<?php

$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$url = url()->current();

$baseurl = url('/');
$roleId = Auth::guard('admin')->user()->role_id;
$storage = Storage::disk('public');

$settings = \App\Models\Settings::first();
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();

$logo = config('custom.NO_IMG');

$storage = Storage::disk('public');
$path = 'settings/';

$image_name = $settings->logo ?? '';
if (!empty($image_name)) {
	if ($storage->exists($path . $image_name)) {
		$logo =  url('public/storage/' . $path . '/' . $image_name);
	}
}


?>

<style>
	.new-user-menus.active .dropdown-menu {
		visibility: visible;
		opacity: 1;

	}
</style>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>CMS {{$settings->app_name??''}} - Dashboard</title>

	<link rel="shortcut icon" href="{{url('/public/storage/settings/favicon.png')}}">

	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&amp;display=swap" rel="stylesheet">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap/css/bootstrap.min.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/feather/feather.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/icons/flags/flags.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/plugins/fontawesome/css/all.min.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/css/style.css')}}">
	<script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
</head>
<style type="text/css">
	.pager {
		padding-left: 0;
		margin: 20px 0;
		text-align: center;
		list-style: none;
	}

	.pager li {
		display: inline;
	}

	.pager li>a,
	.pager li>span {
		display: inline-block;
		padding: 5px 14px;
		background-color: #fff;
		border: 1px solid #ddd;
		border-radius: 15px;
	}

	.multipleselectdropdown {
		border: 1px solid #ddd;
		box-shadow: none;
		color: #333;
		font-size: 15px;
		height: 93px;
		width: 100%;
	}
</style>

<body>
	<div class="main-wrapper">
		<div class="header">
			<div class="header-left">
				<!-- <a href="{{url('/admin')}}" class="logo"> -->
				<span style="font-size:20px;margin: 20px;font-weight: 500;">SSOJS(R)</span>
				</a>
				<a href="{{url('/admin')}}" class="logo logo-small">
					<img src="{{url('/public/storage/settings/favicon.png')}}" alt="Logo" width="30" height="30">
				</a>
			</div>
			<div class="menu-toggle">
				<a href="javascript:void(0);" id="toggle_btn">
					<i class="fas fa-bars"></i>
				</a>
			</div>
			<div class="top-nav-search">
				<!-- <form>
						<input type="text" class="form-control" placeholder="Search here">
						<button class="btn" type="submit"><i class="fas fa-search"></i></button>
					</form> -->
			</div>
			<a class="mobile_btn" id="mobile_btn">
				<i class="fas fa-bars"></i>
			</a>
			<ul class="nav user-menu">
				<!-- <li class="nav-item zoom-screen me-2">
						<a href="#" class="nav-link header-nav-list win-maximize">
							<img src="{{asset('public/assets/img/icons/header-icon-04.svg')}}" alt="">
						</a>
					</li>
				-->
				<li class="nav-item dropdown has-arrow new-user-menus">
					<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
						<span class="user-img">
							<img class="rounded-circle" src="{{url('public\assets\img\userimg.jpg')}}" width="31" alt="{{Auth::guard('admin')->user()->name??''}}">
							<div class="user-text">
								<h6>{{Auth::guard('admin')->user()->name??''}}</h6>
								<!-- <p class="text-muted mb-0">Administrator</p> -->
							</div>
						</span>
					</a>
					<div class="dropdown-menu">
						<div class="user-header">
							<div class="avatar avatar-sm">
								<img src="{{url('public\assets\img\userimg.jpg')}}" alt="User Image" class="avatar-img rounded-circle">
							</div>
							<div class="user-text">
								<h6>{{Auth::guard('admin')->user()->name??''}}</h6>
								<!-- <p class="text-muted mb-0">Administrator</p> -->
							</div>
						</div>
						<a class="dropdown-item" href="{{route('admin.profile')}}">My Profile</a>
						<!-- <a class="dropdown-item" href="inbox.html">Inbox</a> -->
						<a class="dropdown-item logoutBtn" href="{{url('admin/logout')}}">Logout</a>

					</div>
				</li>

			</ul>

		</div>


		<div class="sidebar" id="sidebar">
			<div class="sidebar-inner slimscroll">
				<div id="sidebar-menu" class="sidebar-menu">
					<ul>


						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME) echo "active" ?>">
							<a href="{{url('/admin')}}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME) echo "active" ?>"><i class="feather-grid"></i> <span>Dashboard</span></a>
						</li>


						@if(CustomHelper::isAllowedModule('roles') && CustomHelper::isAllowedSection('roles' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/roles') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.roles.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/roles') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Admin Roles Management</span></a>
						</li>
						@endif
						@if(CustomHelper::isAllowedModule('user') && CustomHelper::isAllowedSection('user' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/user') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.user.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/user') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>User Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('events') && CustomHelper::isAllowedSection('events' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/events') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.events.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/events') echo "active" ?>"><i class="fas fa-map-marker"></i> <span>Events Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('permission') && CustomHelper::isAllowedSection('permission' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/permission') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.permission.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/permission') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Access Permissions</span></a>
						</li>
						@endif


						@if(CustomHelper::isAllowedModule('banners') && CustomHelper::isAllowedSection('banners' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/banners') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.banners.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/banners') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Banners Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('notifications') && CustomHelper::isAllowedSection('notifications' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/notifications') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.notifications.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/notifications') echo "active" ?>"><i class="fas fa-map-marker"></i> <span>Notification Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('news') && CustomHelper::isAllowedSection('news' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/news') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.news.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/news') echo "active" ?>"><i class="fas fa-map-marker"></i> <span>News Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('groups') && CustomHelper::isAllowedSection('groups' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/groups') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.groups.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/groups') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Group Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('subcategories') && CustomHelper::isAllowedSection('subcategories' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/subcategories') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.subcategories.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/subcategories') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Sub-Categories</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('products') && CustomHelper::isAllowedSection('products' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/products') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.products.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/products') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Products</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('settings') && CustomHelper::isAllowedSection('settings' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/settings') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.settings.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/settings') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Content Management</span></a>
						</li>
						@endif


						@if(CustomHelper::isAllowedModule('transactions') && CustomHelper::isAllowedSection('transactions' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/transactions') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.transactions.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/transactions') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Transaction List</span></a>
						</li>
						@endif


						@if(CustomHelper::isAllowedModule('categories_seo') && CustomHelper::isAllowedSection('categories_seo' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/categories_seo') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.categories_seo.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/categories_seo') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Categories SEO</span></a>
						</li>
						@endif



						@if(CustomHelper::isAllowedModule('locality') && CustomHelper::isAllowedSection('locality' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/locality') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.locality.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/locality') echo "active" ?>"><i class="fas fa-map-marker"></i> <span>Locality</span></a>
						</li>

						@endif

						

						@if(CustomHelper::isAllowedModule('contact_us') && CustomHelper::isAllowedSection('contact_us' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/contact_us') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.contact_us.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/contact_us') echo "active" ?>"><i class="fas fa-map-marker"></i> <span>Contact Management</span></a>
						</li>
						@endif

						<!-- @if(CustomHelper::isAllowedModule('booking_event_list') && CustomHelper::isAllowedSection('booking_event_list' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/booking_event_list') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.booking_event_list.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/booking_event_list') echo "active" ?>"><i class="fas fa-map-marker"></i> <span>Events Booking List</span></a>
						</li>
						@endif -->

						@if(CustomHelper::isAllowedModule('community_info') && CustomHelper::isAllowedSection('community_info' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/community_info') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.community_info.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/community_info') echo "active" ?>"><i class="fa fa-list-alt"></i> <span>Community Info Manage..</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('blog_category') && CustomHelper::isAllowedSection('blog_category' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/blog_category') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.blog_category.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/blog_category') echo "active" ?>"><i class="fas fa-blog"></i> <span>Blog Category</span></a>
						</li>

						@endif

						@if(CustomHelper::isAllowedModule('gallery') && CustomHelper::isAllowedSection('gallery' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/gallery') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.gallery.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/gallery') echo "active" ?>"><i class="fas fa-blog"></i> <span>Gallery Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('blogs') && CustomHelper::isAllowedSection('blogs' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/blogs') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.blogs.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/blogs') echo "active" ?>"><i class="fas fa-blog"></i> <span>Blogs</span></a>
						</li>

						@endif

						@if(CustomHelper::isAllowedModule('trustee') && CustomHelper::isAllowedSection('trustee' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/trustee') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.trustee.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/trustee') echo "active" ?>"><i class="fas fa-blog"></i> <span>Trustee Management</span></a>
						</li>
						@endif

						@if(CustomHelper::isAllowedModule('commitee') && CustomHelper::isAllowedSection('commitee' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/commitee') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.commitee.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/commitee') echo "active" ?>"><i class="fas fa-blog"></i> <span>Commitee Management</span></a>
						</li>
						@endif



						@if(CustomHelper::isAllowedModule('businesses') && CustomHelper::isAllowedSection('businesses' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/businesses') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.businesses.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/businesses') echo "active" ?>"><i class="fa fa-home"></i> <span>Business</span></a>
						</li>

						@endif

						@if(CustomHelper::isAllowedModule('vendor_management') && CustomHelper::isAllowedSection('vendor_management' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/businesses') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.vendor_management.add') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/businesses') echo "active" ?>"><i class="fa fa-home"></i> <span>Vendor Management</span></a>
						</li>

						@endif


						@if(CustomHelper::isAllowedModule('pages') && CustomHelper::isAllowedSection('pages' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/pages') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.pages.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/pages') echo "active" ?>"><i class="fa fa-file"></i> <span>Pages</span></a>
						</li>

						@endif


						@if(CustomHelper::isAllowedModule('upload_on_root') && CustomHelper::isAllowedSection('upload_on_root' , 'list'))
						<li class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/upload_on_root') echo "active" ?>">
							<a href="{{ route($ADMIN_ROUTE_NAME.'.upload_on_root.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/upload_on_root') echo "active" ?>"><i class="fa fa-file"></i> <span>Upload On Root</span></a>
						</li>

						@endif

						@if(CustomHelper::isAllowedModule('countries') && CustomHelper::isAllowedSection('countries' , 'list') || CustomHelper::isAllowedModule('states') && CustomHelper::isAllowedSection('states' , 'list') || CustomHelper::isAllowedModule('cities') && CustomHelper::isAllowedSection('cities' , 'list'))

						<li class="submenu">
							<a href="#"><i class="fa fa-map-marker"></i> <span>Locations</span> <span class="menu-arrow"></span></a>
							<ul>
								@if(CustomHelper::isAllowedModule('countries'))
								@if(CustomHelper::isAllowedSection('countries' , 'list'))
								<li><a href="{{ route($ADMIN_ROUTE_NAME.'.countries.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/' . 'countries') echo "active" ?>">Country List</a></li>
								@endif
								@endif

								@if(CustomHelper::isAllowedModule('states'))
								@if(CustomHelper::isAllowedSection('states' , 'list'))
								<li><a href="{{ route($ADMIN_ROUTE_NAME.'.states.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/' . 'states') echo "active" ?>">State List</a></li>
								@endif
								@endif

								@if(CustomHelper::isAllowedModule('cities'))
								@if(CustomHelper::isAllowedSection('cities' , 'list'))
								<li><a href="{{ route($ADMIN_ROUTE_NAME.'.cities.index') }}" class="<?php if ($url == $baseurl . '/' . $ADMIN_ROUTE_NAME . '/' . 'cities') echo "active" ?>">City List</a></li>
								@endif
								@endif
							</ul>
						</li>

						@endif



						<li class="">
							<a href="{{url('/admin/logout')}}" class="logoutBtn"><i class="fa fa-sign-out"></i> <span>Logout</span></a>
						</li>

					</ul>
				</div>
			</div>
		</div>

		@yield('content')
		<footer>
			<p>Copyright © 2024 {{$settings->app_name ??'Plus Nine One'}}.</p>
		</footer>

	</div>


	<!-- <script src="{{asset('public/assets/js/jquery-3.6.0.min.js')}}"></script>

	<script src="{{asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

	<script src="{{asset('public/assets/js/feather.min.js')}}"></script>

	<script src="{{asset('public/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

	<script src="{{asset('public/assets/plugins/apexchart/apexcharts.min.js')}}"></script>
	<script src="{{asset('public/assets/plugins/apexchart/chart-data.js')}}"></script>

	<script src="{{asset('public/assets/js/script.js')}}"></script>

	
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
	 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script> -->

	<script src="{{asset('public/assets/js/jquery-3.6.0.min.js')}}"></script>
	<script src="{{asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('public/assets/js/feather.min.js')}}"></script>
	<script src="{{asset('public/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
	<script src="{{asset('public/assets/plugins/apexchart/apexcharts.min.js')}}"></script>
	<script src="{{asset('public/assets/plugins/apexchart/chart-data.js')}}"></script>
	<script src="{{asset('public/assets/js/script.js')}}"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>


</body>

</html>


<script type="text/javascript">
	$(document).ready(function() {
		$('.select2').select2();
		$('#locality').select2();
	});
</script>

<script>
	$(document).ready(function() {
		$(".logoutBtn").click(function() {
			return confirm('Are you sure you want to logout?');
		});
	});
</script>

<script>
	// CKEDITOR.replace( 'summernote' );
	CKEDITOR.replace('summernote1', {
		height: 300,
		// filebrowserUploadUrl: "{{route('image_upload')}}",
		extraPlugins: 'filebrowser',
		filebrowserBrowseUrl: 'browser.php?type=Images',
		filebrowserUploadMethod: "form",
		filebrowserUploadUrl: "{{url('/')}}/ckeditor/upload.php"
	});

	//  $(document).ready(function() {
	//       $('#summernote1').summernote({
	//               height: 450,
	//           });
	// });
</script>

<script type="text/javascript">
	$('#state_id').on('change', function() {

		var _token = '{{ csrf_token() }}';
		var state_id = $('#state_id').val();
		$.ajax({
			url: "{{ route('get_city') }}",
			type: "POST",
			data: {
				state_id: state_id
			},
			dataType: "HTML",
			headers: {
				'X-CSRF-TOKEN': _token
			},
			cache: false,
			success: function(resp) {
				$('#city_id').html(resp);
			}
		});
	});

	$('#country_id').change(function() {

		var _token = '{{ csrf_token() }}';
		var country_id = $('#country_id').val();
		$.ajax({
			url: "{{ route('get_state') }}",
			type: "POST",
			data: {
				country_id: country_id
			},
			dataType: "HTML",
			headers: {
				'X-CSRF-TOKEN': _token
			},
			cache: false,
			success: function(resp) {
				$('#state_id').html(resp);
			}
		});
	});


	$('#city_id').change(function() {

		var _token = '{{ csrf_token() }}';
		var city_id = $('#city_id').val();
		$.ajax({
			url: "{{ route('get_locality') }}",
			type: "POST",
			data: {
				city_id: city_id
			},
			dataType: "HTML",
			headers: {
				'X-CSRF-TOKEN': _token
			},
			cache: false,
			success: function(resp) {
				$('#locality').html(resp);
			}
		});
	});
</script>

<script type="text/javascript">
	function set_tab_in_session(key) {
		var _token = '{{ csrf_token() }}';
		$.ajax({
			url: "{{ route('admin.set_tab_in_session') }}",
			type: "POST",
			data: {
				key: key
			},
			dataType: "HTML",
			headers: {
				'X-CSRF-TOKEN': _token
			},
			cache: false,
			success: function(resp) {

			}
		});
	}
</script>

<script>
	$('#category_id').on('change', function() {
		// var category_id = this.value;
		category_id = $('#category_id option:selected').toArray().map(item => item.value).join();

		var _token = '{{ csrf_token() }}';

		$.ajax({
			url: "{{ route($ADMIN_ROUTE_NAME.'.get_sub_category') }}",
			type: "POST",
			data: {
				category_id: category_id
			},
			dataType: "HTML",
			headers: {
				'X-CSRF-TOKEN': _token
			},
			cache: false,
			success: function(resp) {
				$('#subcategory_id').html(resp);
			}
		});
	});

	CKEDITOR.replace('description');
</script>

<script type="text/javascript">
	$(document).ready(function() {
		var meta_title = $('#meta_title').val();
		var meta_description = $('#meta_description').val();
		var meta_titlelength = meta_title.length;
		$('#count_metatitle').html(meta_titlelength);
		var meta_descriptionlength = meta_description.length;
		$('#count_metadescription').html(meta_descriptionlength);
	});




	function get_metatitle_length(val) {
		var length = val.length;
		$('#count_metatitle').html(length);
	}

	function get_metadescription_length(val) {
		var length = val.length;
		$('#count_metadescription').html(length);
	}

	$('.new-user-menus').click(function() {
		$(this).toggleClass('active');
	})
</script>