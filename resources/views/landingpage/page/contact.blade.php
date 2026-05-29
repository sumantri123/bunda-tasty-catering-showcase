@extends('landingpage.layout_home.default')

@push('style')

@endpush


@section('content')
	<!-- Page Title Section -->
	<section class="cat-page-title-section">
		<div class="container">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="cat-page-title">
						<h1>Contact Us</h1>
						<ul>
							<li>
								<a href="index.html">Home</a>
							</li>
							<li>
								Contact Us
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Contact Info Section -->
	<section class="cat-service-wrapper cat-section-spacer has-bg relative">
		<div class="container">
			<div class="row justify-content-center">

				<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
					<div class="cat-service-section">
						<div class="cat-service-inner">
							<div class="cat-service-img">
								<img src="{{asset('landingpage/images/main/call.svg') }}" alt="">
							</div>
							<div class="cat-service-info">
								<h4>Call Us</h4>
								<p>
									{{$data['lembaga'][0]->telp_lembaga}}
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
					<div class="cat-service-section">
						<div class="cat-service-inner">
							<div class="cat-service-img">
								<img src="{{asset('landingpage/images/main/mail.svg') }}" alt="">
							</div>
							<div class="cat-service-info">
								<h4>Mail Us</h4>
								<p>
									{{$data['lembaga'][0]->email_lembaga}}
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
					<div class="cat-service-section">
						<div class="cat-service-inner">
							<div class="cat-service-img">
								<img src="{{asset('landingpage/images/main/location.svg') }}" alt="">
							</div>
							<div class="cat-service-info">
								<h4>Location</h4>
								<p>
									{{$data['lembaga'][0]->alamat_lembaga}}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Google Map Section  -->
	<section class="cat-map-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="cat-map-section">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.267302561077!2d112.78236307543678!3d-7.323843672023935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7faeaeac43df9%3A0x3b2da4207c75e307!2sBunda%20Tasty%20Catering!5e0!3m2!1sid!2sus!4v1694053082569!5m2!1sid!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
				</div>
			</div>
		</div>
	</section>

@endsection

@push('scripts')	
	
@endpush
