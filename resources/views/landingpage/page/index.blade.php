@extends('landingpage.layout_home.default')

@push('style')

@endpush


@section('content')

	<!-- Home Banner Section -->
	<section class="cat-banner-wrapper">
		<div class="cat-banner-social">
			<ul>
				@foreach($data['sosmed'] as $sosmed)
					<li><a target="_blank" href="{{$sosmed->sosmed_link}}">{{$sosmed->sosmed_jenis}}</a></li>
				@endforeach 
			</ul>
		</div>
		<div class="container">
			<div class="cat-banner-section">
				<div class="row align-items-center">
					<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
						<div class="cat-banner-text">
							<div class="cat-banner-text-inner">
								<h4><span>Perencanaan Luar Biasa</span></h4>
								<h2 class="cat-banner-title">Pesan Dikami untuk Acara Impian Anda</h2>
								<p>Menciptakan Keajaiban Kuliner Satu Acara pada Satu Waktu, Rasakan Perbedaannya bersama Bunda Tasty Catering</p>
								<div class="cat-banner-btn-wrap">
									<a href="https://api.whatsapp.com/send/?phone=085230930393&text&type=phone_number&app_absent=0" target="_blank" class="cat-btn bookNow">
										Pesan Sekarang
									 </a>									
								</div>

							</div>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
						<div class="banner-img">
							<img src="{{asset('landingpage/images/main/story.png') }}" alt="">
						</div>

					</div>
				</div>
			</div>
		</div>
		<a href="#scroll-down-section" class="scroll-down-section">
			<span></span>
		</a>
		<a href="javascript:void(0);" class="cat-banner-contact">
			<i class="fa fa-phone" aria-hidden="true"></i>
			<span>
				
				{{$data['lembaga'][0]->telp_lembaga}}
			</span>
		</a>
	</section>
	
	<!-- Service Section -->
	<section class="cat-service-wrapper cat-section-spacer-equal has-bg relative">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="cat-heading-wrapper">
						<h4>
							Layanan Kami
						</h4>
						<!--<h2>
							What We Offer
						</h2>-->
					</div>
				</div>
				<?php 
					$image = array(
								'wedding.svg',
								'corporate.svg',
								'cocktail.svg',
								'bento.svg',
								'buffet.svg',
								'sit-down.svg',
								'pub.svg',
								'home.svg'
							);
					$title = array(
								'Acara Pernikahan',
								'Acara Kantor',
								'Acara Ulang Tahun',
								'Acara Syukuran',
								'Acara Seminar',
								'Acara Rapat',
								'Acara Rekreasi',
								'Acara Lainnya',
							);
				?>
				<?php for($a=0; $a<count($title); $a++){?>
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
						<div class="cat-service-section">
							<div class="cat-service-inner">
								<div class="cat-service-img">
									<img src="{{asset('landingpage/images/main/service/'.$image[$a]) }}" alt="">
								</div>
								<div class="cat-service-info">
									<h4><?php echo $title[$a]?></h4>
									<!--<p>
										Contrary to popular belief, ipsum is not simply random.
									</p>-->								
								</div>
							</div>
						</div>
					</div>
				<?php } ?>				
			</div>
		</div>
	</section>

	<!-- Video -->
	<section class="cat-video-wrapper has-bg">
		<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-0">
					<div class="cat-story-video cat-main-video">
						<img class="video-img" src="{{asset('landingpage/images/main/banner-bg.jpg') }}" alt="">
						<div class="cat-play-btn">
							<a class="popup-youtube" rel="external" href="https://www.youtube.com/watch?v=jKabrDJW2EU">
								<i class="fa fa-play" aria-hidden="true"></i>
							</a>
							<div class="btn-wave"></div>
						</div>
					</div>
				</div>				
			</div>
		</div>
	</section>

	<!-- Story -->
	<section class="cat-about-wrapper cat-section-spacer">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
					<div class="cat-story-img">
						<img src="{{asset('landingpage/images/main/story.png') }}" alt="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
					<div class="cat-story-info">
						<div class="cat-heading-wrapper">
							<h4>
								Tentang Kami
							</h4>							
						</div>
						<p class="mb-3">
							<b>Bunda Tasty Catering</b> mampu menyediakan berbagai macam pilihan menu, 
							dengan jumlah atau porsi besar maupun kecil. 
							Bunda Tasty Catering juga mampu melayani berbagai macam segmen, 
							Bunda Tasty Catering menyediakan berbagai macam pilihan menu yang dapat dinikmati oleh berbagai macam kalangan, 
							dan dapat dipesan sesuai dengan budget yang dimiliki. 
							Menu yang disediakan mulai dari menu masakan Jawa sampai dengan menu modern. 
							Hal ini didukung juga oleh koki-koki berpengalaman yang bekerja di balik layar 
							untuk menyediakan menu yang maksimal dari Bunda Tasty Catering.
						</p>
						<ul class="cat-story-list">		
							<?php 
								$paket = array(
									'Paket Tasty Prasmanan',
									'Paket Tasty Tradisional (Prasmanan)',
									'Paket Tasty Box',
									'Paket Tasty Coffee Break',
									'Paket Tasty Tumpeng',
									'Paket Tasty Aqiqah',
									'Tasty Joglo',
									'Aneka Menu Pilihan',
								);

								for($a=0; $a<count($paket); $a++){
							?>
								<li>
									<span>
										<svg enable-background="new 0 0 512 512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><path d="m271.176 121.396c-150.205 7.822-271 132.495-271 284.604v106l37.925-88.29c44.854-89.692 133.847-147.041 233.075-152.314v121.318l240.648-196.714-240.648-196z"/></g></svg>
									</span> {{$paket[$a]}}
								</li>
							<?php } ?>
														
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Menus -->
	<!--<section class="cat-menu-wrapper cat-section-spacer has-bg">
		<div class="container">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="cat-heading-wrapper">
						<h4>
							Our Menu
						</h4>
						<h2>
							Most Popular Food in the World
						</h2>
					</div>
				</div>
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="cat-menu-item-section">
						<div class="cat-menu-wrapper cat-nav-tabs">
							<ul class="nav nav-pills" id="pills-tab" role="tablist">
								<li class="nav-item" role="presentation">
									<button class="nav-link active" id="pills-tabA-tab" data-bs-toggle="pill" data-bs-target="#pills-tabA" type="button" role="tab" aria-controls="pills-tabA" aria-selected="true">Starter</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link" id="pills-tabB-tab" data-bs-toggle="pill" data-bs-target="#pills-tabB" type="button" role="tab" aria-controls="pills-tabB" aria-selected="false">Main Course </button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link" id="pills-tabC-tab" data-bs-toggle="pill" data-bs-target="#pills-tabC" type="button" role="tab" aria-controls="pills-tabC" aria-selected="false">Drinks</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link" id="pills-tabD-tab" data-bs-toggle="pill" data-bs-target="#pills-tabD" type="button" role="tab" aria-controls="pills-tabD" aria-selected="false">Offers</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link" id="pills-tabD-tab" data-bs-toggle="pill" data-bs-target="#pills-tabD" type="button" role="tab" aria-controls="pills-tabD" aria-selected="false">Our Special</button>
								</li>
							</ul>
							<div class="tab-content" id="pills-tabContent">
								<div class="tab-pane fade show active" id="pills-tabA" role="tabpanel" aria-labelledby="pills-tabA-tab">
									<div class="cat-menu-holder">
										<div class="row">
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/01.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Paneer</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/02.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet Potato</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/03.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sabudana Tikki</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/04.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Crispy</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/05.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Pizza</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/06.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Bacon</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/07.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Chicken</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/08.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Blooming</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/09.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/10.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Argentinian</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="pills-tabB" role="tabpanel" aria-labelledby="pills-tabB-tab">
									<div class="cat-menu-holder">
										<div class="row">
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/01.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Paneer</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/02.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet Potato</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/03.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sabudana Tikki</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/04.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Crispy</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/05.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Pizza</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/06.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Bacon</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/07.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Chicken</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/08.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Blooming</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/09.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/main-course/10.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Argentinian</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="pills-tabC" role="tabpanel" aria-labelledby="pills-tabC-tab">
									<div class="cat-menu-holder">
										<div class="row">
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/01.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Lemon</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/02.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Water Drink</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/03.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Salty lemon</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/04.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Crispy water</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/05.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Juice</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/06.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Orange</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/07.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Apple Juice</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/08.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Banana</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/09.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet Water</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/drink/10.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Hot Coffee</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="pills-tabD" role="tabpanel" aria-labelledby="pills-tabD-tab">
									<div class="cat-menu-holder">
										<div class="row">
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/01.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Paneer</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/02.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet Potato</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/03.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sabudana Tikki</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/04.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Crispy</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/05.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Pizza</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/06.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Bacon</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/07.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Chicken</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/08.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Blooming</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/09.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/10.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Argentinian</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="pills-tabE" role="tabpanel" aria-labelledby="pills-tabE-tab">
									<div class="cat-menu-holder">
										<div class="row">
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/01.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Paneer</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/02.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet Potato</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/03.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sabudana Tikki</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/04.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Crispy</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/05.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Pizza</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
											<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
												<div class="cat-menu-section">
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/06.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Bacon</h4>
																<span class="cat-price">$70</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/07.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Chicken</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/08.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Blooming</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/09.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Sweet</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
													<div class="cat-pricing-list">
														<span class="cat-pri-icon">
															<img src="{{asset('landingpage/images/main/menu/10.png') }}" alt="">
														</span>
														<div class="cat-price-info">
															<div class="cat-pricing-title">
																<h4>Argentinian</h4>
																<span class="cat-price">$20</span>
															</div>
															<p>Consectetur adipiscing elit sed dwso eiusmod tempor incididunt ut labore.</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>-->

	<!-- Events -->
	<section class="cat-event-wrapper cat-section-spacer has-bg">
		<div class="container">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="cat-heading-wrapper">
						<h4>
							Gallery
						</h4>
						<!--<h2>
							Our Social &amp; Professional Events Gallery
						</h2>-->
					</div>
				</div>
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<!--<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs">
							<div class="cat-filter-menu">
								<button class="filter" data-filter="all">All Events</button>
								<button class="filter" data-filter=".wedding">Wedding</button>
								<button class="filter" data-filter=".corporate">Corporate</button>
								<button class="filter" data-filter=".cocktail">Cocktail</button>
								<button class="filter" data-filter=".buffet">Buffet</button>
							</div>
						</div>
					</div>-->
					<div class="cat-filter">
						<div class="row">
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix wedding">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c1.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c1.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Wedding</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix corporate">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c2.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c2.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Corporate</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix wedding">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c3.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c3.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Tumpeng</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix wedding corporate cocktail">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c4.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c4.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Aneka Soup</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix wedding">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c5.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c5.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Wedding</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix wedding buffet">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c6.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c6.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Aneka Soup</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix wedding">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c7.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c7.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Menu Aqiqah</h4>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mix wedding buffet">
								<div class="cat-porfolio-section">
									<img src="{{asset('landingpage/images/main/menu/c8.jpg') }}" alt="">
									<div class="cat-overlay">
										<a class="popup-gallery" href="{{asset('landingpage/images/main/menu/c8.jpg') }}" title=""><span class="fa fa-search-plus" aria-hidden="true"></span></a>
										<div class="cat-overlay-text">
											<h4>Tasty Box</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Testimonials  -->
	<section class="cat-testimonials-wrapper cat-section-spacer has-bg">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="cat-heading-wrapper">
						<h4>
							Testimoni
						</h4>
						<!--<h2>
							What Our Customers says!
						</h2>-->
					</div>
				</div>
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<div class="row">
						<div class="testimonial-swiper-wrapper">
							<div class="swiper-container testimonial-slider">
								<div class="swiper-wrapper">
									<div class="swiper-slide">
										<div class="mlf-testimonials-section">
											<div class="mlf-testimonials-inner">
												<div class="mlf-testimonials-info">
													<div class="mlf-testimonials">
														<img src="{{asset('landingpage/images/main/menu/icon1.jpg') }}" alt="" />
														<div>
															<h4>
																Devina P
															</h4>
															<p>Customer</p>
														</div>
													</div>

													<p>
														Pelayanan oke..tampilan oke apalagi rasanya...mantappp..ga pernah failed pesen disini..malah ketagihan terus...pengen coba dan coba lagi...👍👍👍👍😍😍😍😍
													</p>
													<div class="cat-rating">
														<ul>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="swiper-slide">
										<div class="mlf-testimonials-section">
											<div class="mlf-testimonials-inner">
												<div class="mlf-testimonials-info">
													<div class="mlf-testimonials">
														<img src="{{asset('landingpage/images/main/menu/icon1.jpg') }}" alt="" />
														<div>
															<h4>
																Early S
															</h4>
															<p>Customer</p>
														</div>
													</div>

													<p>
														Okelah
													</p>
													<div class="cat-rating">
														<ul>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="swiper-slide">
										<div class="mlf-testimonials-section">
											<div class="mlf-testimonials-inner">
												<div class="mlf-testimonials-info">
													<div class="mlf-testimonials">
														<img src="{{asset('landingpage/images/main/menu/icon1.jpg') }}" alt="" />
														<div>
															<h4>
																Tata T
															</h4>
															<p>Customer</p>
														</div>
													</div>

													<p>
														Menunya enak banget.. Masakannya enak, pas banget bumbunya dan penyajiannya bagus sekali rapi dan bersih. Rekomen buat anda2 yg butuh menu nasi kotak utk acara2 tertentu.. banyak teman kantor saya yg suka masakannya dan mau ikut pesan di catering Bunda Tasty :)
													</p>
													<div class="cat-rating">
														<ul>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="swiper-slide">
										<div class="mlf-testimonials-section">
											<div class="mlf-testimonials-inner">
												<div class="mlf-testimonials-info">
													<div class="mlf-testimonials">
														<img src="{{asset('landingpage/images/main/menu/icon1.jpg') }}" alt="" />
														<div>
															<h4>
																Farida Faradila
															</h4>
															<p>Customer</p>
														</div>
													</div>

													<p>
														Menunya enak banget, bumbu masakannya juga enak. Selalu berlangganan ketika ada acara penting. Selalu puas.
													</p>
													<div class="cat-rating">
														<ul>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="swiper-slide">
										<div class="mlf-testimonials-section">
											<div class="mlf-testimonials-inner">
												<div class="mlf-testimonials-info">
													<div class="mlf-testimonials">
														<img src="{{asset('landingpage/images/main/menu/icon1.jpg') }}" alt="" />
														<div>
															<h4>
																I Nyoman Aditya
															</h4>
															<p>Customer</p>
														</div>
													</div>

													<p>
														Pelayanan memuaskan dan cita rasa lezat Selain itu, harganya juga sangat terjangkau. Saya puas dengan layanannya.
													</p>
													<div class="cat-rating">
														<ul>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
															<li>
																<i class="fa fa-star" aria-hidden="true"></i>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-pagination pagination-testimonial-swiper"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection

@push('scripts')	
	
@endpush
