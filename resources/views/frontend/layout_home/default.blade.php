<?php //date_default_timezone_set("America/Los_Angeles");;?>
<!doctype html>

<html lang="en" >

<head>
	@include('frontend.layout_home.parts_home._head')
</head>

<!--<body oncontextmenu='return false;' style='-moz-user-select: none; cursor: default;' >-->
<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			@include('frontend.layout_home.parts_home._sidebar')
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			@include('frontend.layout_home.parts_home._topnav')
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content isiContent">
				@yield('content')
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">..:: Bunda Tasty Catering ::..</p>
			<!--<marquee width="100%" direction="left">
				<button type="button" class="btn btn-primary btn-sm"><span id="kelas"></span></button>
				<button type="button" class="btn btn-dark btn-sm"><span id="kurs_1"></span></button>
				<button type="button" class="btn btn-success btn-sm"><span id="kurs_2"></span></button>
				<button type="button" class="btn btn-danger btn-sm"><span id="kurs_0"></span></button>
			</marquee>-->
			
		</footer>
	</div>
	<!--end wrapper-->
	<!--start switcher-->
	<div class="switcher-wrapper">
		@include('frontend.layout_home.parts_home._setting')	
	</div>
	<!--end switcher-->
	@include('frontend.layout_home.parts_home._scripts')
	<script>
	
	</script>
</body>

</html>
