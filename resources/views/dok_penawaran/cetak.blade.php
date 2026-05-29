<link href="{{asset('bank_stiep/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/app.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/icons.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('bank_stiep/css/custom_sumi.css') }}" />
                

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
				
					<div class="row contacts">
						<div class="col invoice-to">								
							<table class="border-0">
								<tr >
									<td class="border-0" width="20%">Nomor</td>
									<td class="border-0" width="2%">:</td>
									<td class="border-0" width="78%">{{$data[0]->penawaran_nomor}}</td>
								</tr>
								<tr>
									<td class="border-0" style="background-color:white;">Perihal</td>
									<td class="border-0" style="background-color:white;">:</td>
									<td class="border-0" style="background-color:white;">{{$data[0]->penawaran_hal}}</td>
								</tr>
							</table>
						</div>
						<div class="col invoice-details">								
							<div class="f-10">Surabaya, {{ date('d-m-Y', strtotime($data[0]->penawaran_tgl)) }}</div>								
						</div>
					</div>
					
					<div class="row contacts">
						<div class="f-12">{!! html_entity_decode($data[0]->penawaran_header, ENT_QUOTES, 'UTF-8') !!}</div>
					</div>
					
					<table class="styled-table">
						<tr>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">No</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Deskripsi</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Jumlah</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Harga</td>
							<!--<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Pajak</td>-->
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Total</td>
						</tr>
						@php $no = 1; @endphp
						@foreach($data as $datax)
							<tr>
								<td style="background-color:white;" align="center">{{$no++}}</td>
								<td style="background-color:white;" >{{$datax->penawaran_deskripsi}}</td>
								<td style="background-color:white;" align="center">{{$datax->qty}}</td>
								<td style="background-color:white;" align="right">{{number_format($datax->harga)}}</td>
								<!--<td style="background-color:white;" align="right">{{number_format($datax->pajak_nominal)}}</td>-->
								<td style="background-color:white;" align="right">{{number_format($datax->harga * $datax->qty)}}</td>								
							</tr>
						@endforeach
						<tr>
							<td colspan="3" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Sub Total</td>							
							<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;"></td>
							<!--<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">{{number_format($data[0]->total_pajak)}}</td>-->
							<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">{{number_format($data[0]->grand_total)}}</td>
						</tr>
						<tr>
							<td colspan="4" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">PPN {{$data[0]->penawaran_pajak}} %</td>														
							<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">{{number_format($data[0]->total_pajak)}}</td>
						</tr>
						<tr>
							<td colspan="4" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Total</td>														
							<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">{{number_format($data[0]->grand_total + $data[0]->total_pajak)}}</td>
						</tr>
					</table>
					
					<div class="row contacts">
						<div class="f-12">{!! html_entity_decode($data[0]->penawaran_content, ENT_QUOTES, 'UTF-8') !!}</div>
					</div>
					
					<table class="border-0">
						<tr>
							<td class="border-0" style="background-color:white;" width="50%"></td>
							<td class="border-0 text-center" style="background-color:white;" width="50%">Hormat Kami</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->penawaran_ttd}}</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >
								<img src="{{asset($data[0]->pejabat_path.$data[0]->pejabat_name) }}" style="width:170px; height:115px;"/></a>
							</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->penawaran_pejabat}}</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->penawaran_hp}}</td>
						</tr>
					<table>						
				
							
			</div>
		</div>
	</div>
</div>

    

<script src="{{asset('bank_stiep/js/jquery.min.js') }}"></script>
<script>
	$(document).ready(function () {
		window.print();
	});
</script>