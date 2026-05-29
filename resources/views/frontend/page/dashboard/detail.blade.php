<link href="{{asset('bank_stiep/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet"/>
<link href="{{asset('bank_stiep/plugins/notifications/css/lobibox.min.css') }}" rel="stylesheet"/>
<link href="{{asset('bank_stiep/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />	

<link href="{{asset('bank_stiep/plugins/jquery-ui-1.12.1/jquery-ui.min.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/plugins/jquery-ui-1.12.1/jquery-ui.css') }}" rel="stylesheet" />

<!-- Date -->
<link href="{{asset('bank_stiep/plugins/datetimepicker/css/classic.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/plugins/datetimepicker/css/classic.time.css') }}" rel="stylesheet" />
<link href="{{asset('bank_stiep/plugins/datetimepicker/css/classic.date.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('bank_stiep/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<!-- Table -->
<link href="{{asset('bank_stiep/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />

<!-- loader-->
<link href="{{asset('bank_stiep/css/pace.min.css') }}" rel="stylesheet" />	
<!-- Bootstrap CSS -->
<link href="{{asset('bank_stiep/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/app.css') }}" rel="stylesheet">
<link href="{{asset('bank_stiep/css/icons.css') }}" rel="stylesheet">
<!-- Theme Style CSS -->
<link rel="stylesheet" href="{{asset('bank_stiep/css/dark-theme.css') }}" />
<link rel="stylesheet" href="{{asset('bank_stiep/css/semi-dark.css') }}" />
<link rel="stylesheet" href="{{asset('bank_stiep/css/header-colors.css') }}" />

<link href="{{asset('bank_stiep/plugins/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">	

<link href="{{asset('bank_stiep/plugins/highcharts/css/highcharts.css') }}" rel="stylesheet" />
<div class="card-body cetak_custom">
	<div id="invoice">				
		<div class="invoice overflow-auto">	
			<div class="container py-2">
				<h4 class="font-weight-light text-center text-primary py-3"><b>Timeline {{$dataPenawaran[0]->customer_nama}}</b></h4>
				
				<!-- timeline item 1 -->
				<div class="row">
					<!-- timeline item 1 left dot -->
					<div class="col-auto text-center flex-column d-none d-sm-flex">
						<div class="row h-50">
							<div class="col">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
						<h5 class="m-2">
						<span class="badge rounded-pill bg-primary border">&nbsp;</span>
					</h5>
						<div class="row h-50">
							<div class="col border-end">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
					</div>
					<!-- timeline item 1 event content -->
					<div class="col py-2">
						<div class="card border-top border-0 border-4 border-primary radius-15">
							<div class="card-body">							
								<h6 class="card-title text-primary"><b>{{$dataPenawaran[0]->penawaran_hal}}</b></h6><br>
								<div class="card-text"><b>No. Penawaran : {{$dataPenawaran[0]->penawaran_nomor}}</b></div>
								<div class="card-text">Tgl Penawaran : {{date('d M Y',strtotime($dataPenawaran[0]->penawaran_tgl))}}</div>
								<div class="card-text">Total Penawaran : Rp. {{number_format($dataPenawaran[0]->total_penawaran)}}</div>
								<div class="card-text">Total Pajak : Rp. {{number_format($dataPenawaran[0]->total_pajak)}}</div>
								<div class="card-text">Pajak (%) : {{$dataPenawaran[0]->penawaran_pajak}} %</div><br>
									Purchase Order : <br>
								
									<?php if(isset($po[0]->po_id)) { 
										for($a=0; $a<count($po); $a++){
									?>																	
										<div class="badge rounded-pill bg-success" style="font-size: 11px" onclick="view_file('{{$po[$a]->file_path}}','{{$po[$a]->file_name}}')"><i class="bx bx-search me-0"></i>&nbsp;{{$po[$a]->file_name_ori}}</div>
									<?php }} else { ?>
										<div class="badge rounded-pill bg-danger" style="font-size: 11px">Belum Upload</div>
									<?php } ?>								
								
							</div>
						</div>
					</div>
				</div>
				<!--/row-->
				<!-- timeline item 2 -->
				<div class="row">
					<div class="col-auto text-center flex-column d-none d-sm-flex">
						<div class="row h-50">
							<div class="col border-end">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
						<h5 class="m-2">
						<span class="badge rounded-pill {{isset($do[0]->do_id) ? 'bg-primary' : 'bg-light'}} border">&nbsp;</span>
					</h5>
						<div class="row h-50">
							<div class="col border-end">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
					</div>
					<div class="col py-2">
						<div class="card border-top border-0 border-4 border-primary radius-15">
							<div class="card-body">							
								<h6 class="card-title text-primary"><b>Delivery Order (DO)</b></h6><br>
									<?php if(isset($do[0]->do_id)) { 
										for($a=0; $a<count($do); $a++){
									?>				
										<div class="card-text"><b>No. Penawaran : {{$do[$a]->do_nomor}}</b></div>
										<div class="card-text">Tgl Delivery Order : {{date('d M Y',strtotime($do[$a]->do_tgl))}}</div>
										<div class="badge rounded-pill bg-warning" style="font-size: 11px" onclick="print('cetakDO',{{$do[$a]->do_id}})"><i class="bx bx-printer me-0"></i>&nbsp;Cetak</div><br><br>
									<?php }} ?>
							</div>
						</div>
					</div>
				</div>
				<!--/row-->
				<!-- timeline item 3 -->
				<div class="row">
					<div class="col-auto text-center flex-column d-none d-sm-flex">
						<div class="row h-50">
							<div class="col border-end">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
						<h5 class="m-2">
						<span class="badge rounded-pill {{isset($invoice[0]->invoice_id) ? 'bg-primary' : 'bg-light'}} border">&nbsp;</span>
					</h5>
						<div class="row h-50">
							<div class="col border-end">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
					</div>
					<div class="col py-2">
						<div class="card border-top border-0 border-4 border-primary radius-15">
							<div class="card-body">
								<h6 class="card-title text-primary"><b>Invoice Dan Kwitansi</b></h6><br>
								<?php if(isset($invoice[0]->invoice_id)) { 
										for($a=0; $a<count($invoice); $a++){
								?>				
									<div class="card-text"><b>No. Invoice : {{$invoice[$a]->invoice_nomor}}</b></div>
									<div class="card-text">Tgl Invoice : {{date('d M Y',strtotime($invoice[$a]->invoce_tgl))}}</div>								
									<div class="card-text">Total Invoice : Rp. {{number_format($invoice[$a]->total_invoice)}}</div>
									<div class="card-text">Total Pajak : Rp. {{number_format($invoice[$a]->total_pajak)}}</div>
									<div class="card-text">Pajak (%) : {{$invoice[$a]->invoice_pajak_persen}} %</div><br>
									Cetak Invoice : <br>
									<div class="badge rounded-pill bg-warning" style="font-size: 11px" onclick="print('cetakInvoice',{{$invoice[$a]->invoice_id}})"><i class="bx bx-printer me-0"></i>&nbsp;Cetak</div><br><br>
									Faktur Pajak : <br>
								
									<?php if($invoice[$a]->invoice_pajak_persen==0 ||$invoice[$a]->invoice_pajak_persen==null) { 
											echo '<div class="badge rounded-pill bg-danger" style="font-size: 11px">Penawaran / Invoice Tidak Ada Pajak</div>';

										} else if(isset($faktur) && (count($faktur[$a]) > 0)){
											for($x=0; $x<count($faktur[$a]); $x++){
									?>																	
										<div class="badge rounded-pill bg-success" style="font-size: 11px" onclick="view_file('{{$faktur[$a][$x]->file_path}}','{{$faktur[$a][$x]->file_name}}')"><i class="bx bx-search me-0"></i>&nbsp;{{$faktur[$a][$x]->file_name_ori}}</div>
									<?php }} else { ?>
										<div class="badge rounded-pill bg-danger" style="font-size: 11px">Belum Upload</div>
									<?php } ?>	<br><br>
									Cetak Kwitansi : <br>
									<?php if(isset($kw[$a][0]->kw_id)){ ?>
										<div class="badge rounded-pill bg-warning" style="font-size: 11px" onclick="print('cetakKw',{{$kw[$a][0]->kw_id}})"><i class="bx bx-printer me-0"></i>&nbsp;Cetak</div>
									<?php } else {
										echo '<div class="badge rounded-pill bg-danger" style="font-size: 11px">Belum Create Kwitansi</div>';
									}?>

									<br><br>Bukti Transfer : <br>
									<?php if(isset($bt[$a][0]->po_id)) { 
										for($c=0; $c<count($bt[$a]); $c++){
									?>																	
										<div class="badge rounded-pill bg-success" style="font-size: 11px" onclick="view_file('{{$bt[$a][$c]->file_path}}','{{$bt[$a][$c]->file_name}}')"><i class="bx bx-search me-0"></i>&nbsp;{{$bt[$a][$c]->file_name_ori}}</div>
									<?php }} else { ?>
										<div class="badge rounded-pill bg-danger" style="font-size: 11px">Belum Upload</div>
									<?php } ?>
									<hr>
								<?php }} ?>
								
									
							</div>
						</div>
					</div>
				</div>
				<!--/row-->
				<!-- timeline item 4 -->
				<div class="row">
					<div class="col-auto text-center flex-column d-none d-sm-flex">
						<div class="row h-50">
							<div class="col border-end">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
						<h5 class="m-2">
						<span class="badge rounded-pill {{isset($supplier[0]->pesan_id) ? 'bg-primary' : 'bg-light'}} border">&nbsp;</span>
					</h5>
						<div class="row h-50">
							<div class="col">&nbsp;</div>
							<div class="col">&nbsp;</div>
						</div>
					</div>
					<div class="col py-2">
						<div class="card border-top border-0 border-4 border-success radius-15">
							<div class="card-body">							
								<h6 class="card-title text-success"><b>Supplier</b></h6><br>							
									<?php if(isset($supplier[0]->pesan_id)) { 
										for($a=0; $a<count($supplier); $a++){
									?>				
										<div class="card-text text-primary"><b>Nama Supplier : {{$supplier[$a]->supplier_nama}}</b></div>
										<div class="card-text">No. Pemesanan : {{$supplier[$a]->pesan_nomor}}</div>
										<div class="card-text">Tgl Pemesanan : {{date('d M Y',strtotime($supplier[$a]->pesan_tgl))}}</div>
										<div class="card-text">Total Pesanan : {{number_format($supplier[$a]->total_pesanan)}}</div>
										<div class="card-text">Total Pajak : {{number_format($supplier[$a]->total_pajak)}}</div>
										<div class="card-text">Pajak (%): {{($supplier[$a]->pesan_pajak) ?? 0}} %</div>
										<br><br>
										Bukti Pendukung : <br>

										<?php if(isset($dp[$a][0]->po_id)) { 
											for($y=0; $y<count($dp[$a]); $y++){
										?>																	
											<div class="badge rounded-pill bg-success" style="font-size: 11px" onclick="view_file('{{$dp[$a][$y]->file_path}}','{{$dp[$a][$y]->file_name}}')"><i class="bx bx-search me-0"></i>&nbsp;{{$dp[$a][$y]->jenis_file_nama}}_{{$dp[$a][$y]->file_name_ori}}</div>
										<?php }} else { ?>
											<div class="badge rounded-pill bg-danger" style="font-size: 11px">Belum Upload</div>
										<?php } ?>

										<hr>
									<?php }} ?>
							</div>
						</div>
					</div>
				</div>
				<!--/row-->
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-file" tabindex="-1" id="exampleFileModal" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">            
			<div class="modal-body">
				<embed src="#" id="lihat_file" frameborder="0" width="100%" height="525px">					
			</div>			
		</div>
	</div>
</div>

<script src="{{asset('bank_stiep/js/jquery.min.js') }}"></script>
<script src="{{asset('bank_stiep/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{asset('bank_stiep/js/app.js') }}"></script>
<script src="{{asset('bank_stiep/js/plugins.js') }}"></script>

<script>
	function view_file(lokasi,file) {				
		var base_url = window.location.origin;

		var embed1 = document.getElementById('lihat_file');
		$(".modal-file").modal('show');	
		embed1.src = base_url+"/"+lokasi+""+file; 
		
	}

	function print(url,id){
		var base_url = window.location.origin;
		window.open(base_url+"/"+url+"/"+id, '_blank', 'left=0,top=0,width=1000,height=700,status=0');
	}
</script>