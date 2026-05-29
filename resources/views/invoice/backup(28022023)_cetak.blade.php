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
							<div class="text-gray-light">INVOICE TO:</div>
							<h5 class="invoice-id text-primary">{{$data[0]->penawaran_company}}</h5>
							<div class="address">No. PO : {{$data[0]->invoice_po_nomor}}</div>																
						</div>
						<div class="col invoice-details">
							<div class="text-gray-light">INVOICE NO:</div>
							<h5 class="invoice-id">{{$data[0]->invoice_nomor}}</h5>
							<div class="date">Date of Invoice: {{date('d-m-Y', strtotime($data[0]->invoce_tgl))}}</div>
							<div class="date">Due Date: {{date('d-m-Y', strtotime($data[0]->invoice_due_date))}}</div>
						</div>
					</div>
					<table class="styled-table">
						<tr>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">No</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Deskripsi</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Jumlah</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Harga</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Pajak</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Total</td>
						</tr>
						@php $no = 1; @endphp
						@foreach($data as $datax)
							<tr>
								<td style="background-color:white;" align="center">{{$no++}}</td>
								<td style="background-color:white;" >{{$datax->invoice_deskripsi}}</td>
								<td style="background-color:white;" align="center">{{$datax->qty}}</td>
								<td style="background-color:white;" align="right">{{number_format($datax->harga)}}</td>
								<td style="background-color:white;" align="right">{{number_format($datax->pajak_nominal)}}</td>
								<td style="background-color:white;" align="right">{{number_format($datax->total + $datax->pajak_nominal)}}</td>
							</tr>
						@endforeach
						<tr>
							<td colspan="3" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Total</td>							
							<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">{{number_format($data[0]->total_harga)}}</td>
							<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">{{number_format($data[0]->total_pajak)}}</td>
							<td align="right" class="bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">{{number_format($data[0]->grand_total + $data[0]->total_pajak)}}</td>
						</tr>
					</table>
					<br>
					<div class="notices">
						<div><b>Notes:</b></div>
						<div class="notice">
							<ol>
								<li class="f-10">Pembayaran dianggap lunas jika uang sudah masuk di rekening kami</li>
								<li class="f-10">Pembayaran via Bank Transfer melalui:
								
									<ul>
										<li>No Rek Bank Mandiri (Kode Bank 008) : 142-00-1979820-5 <b>(An. CV. Simarfian Jaya Abadi)</b></li>
										<li>No Rek Bank Syariah Indonesia (Kode Bank 451) : 314-41-33140 <b>(An. CV. Simarfian Jaya Abadi)</b></li>	
									</ul>
								</li>
							</ol>
						</div>
					</div>
					<br><br>
					<table class="border-0">
						<tr>
							<td class="border-0" style="background-color:white;" width="70%"></td>
							<td class="border-0 text-center" style="background-color:white;" width="30%">Hormat Kami</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->invoice_ttd}}</td>
						</tr>
						<tr height="100px" >
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >
								<img src="{{asset($data[0]->pejabat_path.$data[0]->pejabat_name) }}" style="width:170px; height:115px;"/></a>
							</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->invoice_pejabat}}</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->invoice_tlp}}</td>
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