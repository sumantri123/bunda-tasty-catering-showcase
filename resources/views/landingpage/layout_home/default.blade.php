<?php //date_default_timezone_set("America/Los_Angeles");;?>
<!doctype html>

<html lang="en" >

	<head>
		@include('landingpage.layout_home.parts_home._head')
	</head>

	<!--<body oncontextmenu='return false;' style='-moz-user-select: none; cursor: default;' >-->
	<body>

		<!-- Preloader Start -->
		<div class="cat-preloader">
			<div class="cat-preloader-inner">
				<img src="{{asset('landingpage/images/main/bundatastygif.gif') }}" alt="loader">
			</div>
		</div>

		<!-- Search Box -->
		<div class="search-box">
			<div class="search-box-container">
				<a href="javascript:void(0);" class="close-btn">
					<svg viewBox="0 0 413.348 413.348" xmlns="http://www.w3.org/2000/svg"><path d="m413.348 24.354-24.354-24.354-182.32 182.32-182.32-182.32-24.354 24.354 182.32 182.32-182.32 182.32 24.354 24.354 182.32-182.32 182.32 182.32 24.354-24.354-182.32-182.32z"/></svg>
				</a>
				<div class="search-bar-inner">
					<input type="text" placeholder="Search here..." />
					<button type="submit"><i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		
		<header>
			@include('landingpage.layout_home.parts_home._topnav')
		</header>
		
		<div class="cat-main-wrapper">
			@yield('content')

			@include('landingpage.layout_home.parts_home._footer')	
		</div>
		
		<div class="floating-container">
			<div class="floating-button"><i class="fa-solid fa-comments"></i></div>
			<div class="element-container">				
				<span class="float-element tooltip-left">
					<i class="material-icons">phone</i>				
				</span>
				<span class="float-element">
					<i class="material-icons">email</i>
				</span>				
				<span class="float-element">
					<a target="_blank" href="https://api.whatsapp.com/send/?phone=085230930393&text&type=phone_number&app_absent=0">
						<i class="fa-brands fa-whatsapp text-white"></i>
					</a>
				</span>				
			</div>
		</div>
		
		@include('landingpage.layout_home.parts_home._scripts')
		
	</body>

</html>
