<link href="{{asset('bank_stiep/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/app.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/icons.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('bank_stiep/css/custom_sumi.css') }}" />
                

<!--end breadcrumb-->

<div class="card-body cetak_custom">
	<div id="invoice">			
		<div class="invoice overflow-auto">
			<div style="min-width: 600px">
				<header>
					<div class="row">
						<div class="col">
							<a href="javascript:;">
								<img src="{{ URL::asset(session('logoHeaderTransaksi')) }}" width="110px" alt="" />  
							</a>
						</div>
						<div class="col company-details">
							<h2 class="name">
								<a target="_blank" href="javascript:;">
								{{ strtoupper(Session::get('subtitle')) }}
								</a>
							</h2>
							<div class="f-10">{{ Session::get('alamat') }}</div>
							<div class="f-10">{{ Session::get('telp') }}</div>								
						</div>
					</div>
				</header>
				<main>
					<div class="row contacts">
						<div class="col invoice-to">							
							<div class="address"><b>No : {{$data[0]->kw_nomor}}</b></div>																
						</div>						
					</div>
					<table class="border-0">
						<tr><td class="border-0" colspan="3" align="center"><h4>KWITANSI</h4></td></tr>												
						<tr height="40px">
							<td class="border-0" width="30%" style="-webkit-print-color-adjust: exact;">
								Telah Diterima Dari
							</td>
							<td class="border-0" width="3%" style="-webkit-print-color-adjust: exact;">:</td>
							<td class="border-0" width="67%" style="-webkit-print-color-adjust: exact;">{{ strtoupper($data[0]->kw_company) }}</td>							
						</tr>
						<tr height="40px">
							<td class="border-0" style="-webkit-print-color-adjust: exact;">
								Nominal
							</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">:</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">Rp. {{ number_format($data[0]->kw_nominal) }}</td>							
						</tr>
						<tr height="40px">
							<td class="border-0" style="-webkit-print-color-adjust: exact;">
								Pajak
							</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">:</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">Rp. {{ number_format($data[0]->kw_pajak_nominal) }} ( {{ number_format($data[0]->kw_pajak_persen) }}%)</td>							
						</tr>
						<tr height="40px">
							<td class="border-0" style="-webkit-print-color-adjust: exact;">
								Total
							</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">:</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">Rp. {{ number_format($data[0]->kw_pajak_nominal + $data[0]->kw_nominal) }} </td>							
						</tr>
						<tr height="40px">
							<td class="border-0" style="-webkit-print-color-adjust: exact;">
								Terbilang
							</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">:</td>
							<td class="border-0" class="text-center" style="-webkit-print-color-adjust: exact;">{{ ucwords(strtolower($data[0]->kw_terbilang)) }}</td>							
						</tr>
						<tr height="40px">
							<td class="border-0" style="-webkit-print-color-adjust: exact;">
								Untuk Pembayaran
							</td>
							<td class="border-0" style="-webkit-print-color-adjust: exact;">:</td>
							<td class="border-0" style="-webkit-print-color-adjust: exact;">{!! html_entity_decode($data[0]->kw_deskripsi, ENT_QUOTES, 'UTF-8') !!}</td>							
						</tr>						
					</table>					
					<table class="border-0">
						<tr>
							<td class="border-0" style="background-color:white;" width="70%"></td>
							<td class="border-0 text-center" style="background-color:white;" width="30%">Surabaya, {{date('d-m-Y', strtotime($data[0]->kw_tgl))}}</td>
						</tr>						
						<tr height="100px" >
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" ></td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" ><b>{{$data[0]->kw_ttd}}</b></td>
						</tr>						
					<table>	
				</main>					
			</div>
			<!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
			<div></div>
		</div>
	</div>
</div>

<script src="{{asset('bank_stiep/js/jquery.min.js') }}"></script>
<script>
	$(document).ready(function () {
		window.print();
	});
</script>