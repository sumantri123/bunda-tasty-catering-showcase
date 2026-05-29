<html lang="en">

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--favicon-->
		<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
		<!--plugins-->
		<link href="{{asset('bank_stiep/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
		<link href="{{asset('bank_stiep/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
		<link href="{{asset('bank_stiep/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
		<!-- loader-->
		<link href="{{asset('bank_stiep/css/pace.min.css') }}" rel="stylesheet" />
		<script src="{{asset('bank_stiep/js/pace.min.js') }}"></script>
		<!-- Bootstrap CSS -->
		<link href="{{asset('bank_stiep/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{asset('bank_stiep/css/app.css') }}" rel="stylesheet">
		<link href="{{asset('bank_stiep/css/icons.css') }}" rel="stylesheet">
		<!-- Theme Style CSS -->
		<link rel="stylesheet" href="{{asset('bank_stiep/css/dark-theme.css') }}" />
		<link rel="stylesheet" href="{{asset('bank_stiep/css/semi-dark.css') }}" />
		<link rel="stylesheet" href="{{asset('bank_stiep/css/header-colors.css') }}" />
		<title>Siricing</title>
	</head>

	<body>
		<!--wrapper-->
		<div class="wrapper">
			<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
				<div class="container-fluid">
					<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
						<div class="col mx-auto">
							<div class="card bg-dark">
								<div class="card-body">
									<div class="border p-4 rounded">
										<div class="text-center">
											<h5 class="text-white">Authorize Siricing to Access your Tiktok account</h5>
										</div><br>
										<table width="100%">
											<tr>
												<td align="right">
													<img class="me-2" src="{{asset('img/logo_bundatasty.png') }}" width="50px" alt="Image Description">
												</td>
												<td width="5%">
													<img class="me-2" src="{{asset('img/two-arrows.png') }}" width="30px" alt="Image Description">
												</td>
												<td>
													<img class="me-2" src="{{asset('bank_stiep/images/icons/tiktok_white2.png') }}" alt="Image Description">
												</td>
											</tr>
										</table>
										<div class="login-separater text-center mb-4"> <span>Integrated</span>
											<hr/>
										</div>
										<div class="alert alert-secondary border-0 bg-secondary">
											
											<span class="text-white">
												<b>Siricing Web would like to :</b><br>
												<ul>
													<li>Read your profile info (avatar, display name)</li>
													<li>Read yout public videos on Tiktok</li>
												</ul>
												<small>You can manage this setting anytime via "Edit Access", To Revoke access, go to "Security and login" > "manage app permissions".</small>
											</span><br><br>												
											<div class="login-separater text-center mb-4"> <span class="text-primary"><b><a href="javascript:void(0)">Edit Access ></a></b></span>
												<hr/>
											</div>											
										</div>
										<span class="text-white"><small>By tapping "Authorize", you agree to the Siricing Web 
											<a target="_blank" href="/privacy_policy">Privacy Policy</a> 
										and <a target="_blank" href="/term_condision">Terms &amp; Service</a></small></span><br><br>
										<div class="d-grid">
											<a href="javascript:;" class="btn btn-primary">Authorize</a>
											<button class="btn my-4 shadow-sm btn-white" onclick="location.replace(document.referrer);">Cancel</button> 											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end row-->
				</div>
			</div>
		</div>
		<!--end wrapper-->
		
		<script src="{{asset('bank_stiep/js/bootstrap.bundle.min.js') }}"></script>
		<!--plugins-->
		<script src="{{asset('bank_stiep/js/jquery.min.js') }}"></script>
		<script src="{{asset('bank_stiep/plugins/simplebar/js/simplebar.min.js') }}"></script>
		<script src="{{asset('bank_stiep/plugins/metismenu/js/metisMenu.min.js') }}"></script>		
		<script src="{{asset('bank_stiep/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
		<!--Express-->
		<script src="{{asset('bank_stiep/plugins/express/lib/express.js') }}"></script>
		<script src="{{asset('bank_stiep/plugins/require_js/node_modules/requirejs/require.js') }}"></script>		
		<!--app JS-->
		<script src="{{asset('bank_stiep/js/app.js') }}"></script>
		
	</body>
</html>