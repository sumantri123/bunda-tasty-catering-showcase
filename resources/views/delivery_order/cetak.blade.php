<link href="{{asset('bank_stiep/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/app.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/icons.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('bank_stiep/css/custom_sumi.css') }}" />
                

<div class="card-body cetak_custom">
	
		<div class="invoice overflow-auto">
			
				<header>
					<div class="row">
						<div class="col">
							<a href="javascript:;">
								<img src="{{ URL::asset(session('logoHeaderTransaksi')) }}" width="110px" alt="" />  
							</a>
						</div>
						<div class="col company-details">							
							<table class="border-0">
								<tr >
									<td colspan="3" class="border-0" width="20%"><h3 class="name"><a target="_blank" href="javascript:;">SURAT JALAN</a></h3></td>									
								</tr>
								<tr >
									<td class="border-0" style="background-color:white;" width="20%">Nomor</td>
									<td class="border-0" style="background-color:white;" width="2%">:</td>
									<td class="border-0" style="background-color:white;" width="78%">{{$data[0]->do_nomor}}</td>
								</tr>
								<tr>
									<td class="border-0" style="background-color:white;">Tangal</td>
									<td class="border-0" style="background-color:white;">:</td>
									<td class="border-0" style="background-color:white;">{{$data[0]->do_tgl}}</td>
								</tr>
								<tr>
									<td class="border-0" style="background-color:white;">No. PO</td>
									<td class="border-0" style="background-color:white;">:</td>
									<td class="border-0" style="background-color:white;">{{$data[0]->do_po_nomor}}</td>
								</tr>
							</table>							
						</div>
					</div>
				</header>
				
					<div class="row contacts">
						<div class="col invoice-to">								
							<table class="border-0">
								<tr style="background-color:white;">
									<td width="70%"  rowspan="3">{!! html_entity_decode($data[0]->do_header, ENT_QUOTES, 'UTF-8') !!}</td>									
									<td width="30%" >
										@if($data[0]->do_jenis == 'Untuk Dibeli')  
											<i class="bx bx-message-square-check bx-sm text-primary"></i>
										@endif
										Untuk Dibeli
									</td>
								</tr>
								<tr style="background-color:white;">																		
									<td >
										@if($data[0]->do_jenis == 'Untuk Dipinjamkan')  
											<i class="bx bx-message-square-check bx-sm text-primary"></i>
										@endif
										Untuk Dipinjamkan
									</td>
								</tr>
								<tr style="background-color:white;">									
									
									<td>
										@if($data[0]->do_jenis == 'Lain - Lain')  
											<i class="bx bx-message-square-check bx-sm text-primary"></i>
										@endif
										Lain-Lain
									</td>
								</tr>
							</table>
						</div>						
					</div>
					
					<table class="styled-table">
						<tr>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">No</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Deskripsi</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Jumlah</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Satuan</td>
							<td class="text-center bg-primary" style="-webkit-print-color-adjust: exact; color:white;font-weight:bold;">Keterangan</td>							
						</tr>
						@php $no = 1; @endphp
						@foreach($data as $datax)
							<tr>
								<td style="background-color:white;" align="center">{{$no++}}</td>
								<td style="background-color:white;" >{{$datax->do_deskripsi}}</td>
								<td style="background-color:white;" align="center">{{$datax->qty}}</td>
								<td style="background-color:white;" align="center">{{$datax->do_satuan}}</td>
								<td style="background-color:white;">{{$datax->do_keterangan}}</td>								
							</tr>
						@endforeach						
					</table>
					<br>				
					<table class="border-0">
						<tr>
							<td class="border-0" style="background-color:white;" width="50%"></td>
							<td class="border-0 text-center" style="background-color:white;" width="50%">Hormat Kami</td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->do_ttd}}</td>
						</tr>
						<tr height="100px" >
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" ></td>
						</tr>
						<tr>
							<td class="border-0" style="background-color:white;" ></td>
							<td class="border-0 text-center" style="background-color:white;" >{{$data[0]->do_pejabat}}</td>
						</tr>						
					<table>						
				
				
			
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