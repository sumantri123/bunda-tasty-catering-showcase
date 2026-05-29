	<div class="row align-items-center">		
		<div class="col-md-12">
			<div class="row align-items-center shadow-none bg-transparent border-bottom border-2">
				<div class="col-md-3">
					<h4 class="mb-3 mb-md-0">Content Planner</h4>
				</div>
				<div class="col-md-9">
					<form class="float-md-end">
						<div class="row row-cols-md-auto g-lg-3">
							<label for="inputFromDate" class="col-md-2 col-form-label text-md-end">Periode</label>
							<div class="col-md-4">
								<input type="text" class="form-control form-control-sm datepicker" id="bulanDashboard"/>	
							</div>
							<label for="inputToDate" class="col-md-3 col-form-label text-md-end">Sosial Media</label>
							<div class="col-md-3">
								<select class="{{$data['classFormSelect']}} clearDisable" name="jenis_sosmed" id="jenis_sosmed" >									
									@foreach($Sosmed as $datax)
									<option value="{{ $datax->sosmed_id }}" >{{ ucfirst(trans($datax->sosmed_jenis)) }}</option>
									@endforeach                                                                                                    
								</select>
							</div>
						</div>
					</form>
				</div>
			</div>
			
		</div>
	</div><br>
	<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
		<div class="col">
			<div class="card radius-10 bg-success">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-white">Total Content Planner</p>
							<h4 class="my-1 text-white"><span id="txtJumlahContent"></span></h4>
							<p class="mb-0 font-13 text-white"><i class="bx bx-calendar-heart align-middle"></i><span id="txtPeriode"></span></p>
						</div>
						<div class="widgets-icons bg-white text-success ms-auto"><i class="bx bx-video"></i></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10 bg-info">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-dark">Total Content Idea</p>
							<h4 class="my-1 text-dark"><span id="txtJumlahIdea"></span></h4>
							<p class="mb-0 font-13 text-dark"><i class="bx bx-calendar-heart align-middle"></i><span id="txtPeriode2"></span></p>
						</div>
						<div class="widgets-icons bg-white text-dark ms-auto"><i class="bx bx-aperture"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="card radius-10 bg-warning">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<p class="mb-0 text-dark">Total Follower</p>
							<h4 class="my-1 text-dark"><span id="txtFollower"></span> Follower</h4>
							<p class="mb-0 font-13 text-dark"><i class="bx bxs-up-arrow align-middle"></i> From Sosial Media</p>
						</div>
						<div class="widgets-icons bg-white text-dark ms-auto"><i class="bx bxs-group"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end row-->
	
	<div class="row">
		<div class="col-xl-8 d-flex">
			<div class="card radius-10 w-100">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div>
							<h5 class="mb-1">Next Content Planner</h5>
							<p class="mb-0 font-13 text-secondary"><i class='bx bxs-calendar'></i>Hanya Agenda Akan Datang Yang Ditampilkan</p>
							<br>
							<button type="button" class="btn btn-primary btn-sm" id="tambah">Add Event</button>						
						</div>
					</div>
					<div class="table-responsive mt-4">
						<table id="Transaction-History" class="table table-striped table-bordered" border="2">                
						</table>					
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4">
			<?php for($a=12, $b=0; $a<15; $a++, $b++) { ?>
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="">
								<p class="mb-1 text-secondary"><span id="gfkContentCalender_<?php echo $b?>"></span></p>
								<h4 class="mb-0"><span id="gfkPersenCalender_<?php echo $b?>"></span></h4>
							</div>
							<div class="ms-auto">
								<p class="mb-0 font-13 text-success"><span id="gfkTahun_<?php echo $b?>"></span></p>								
							</div>
						</div>
					</div>
					<div class="chartContent" id="chart<?php echo $a?>"></div>
				</div>
			<?php } ?>
		</div>
	</div>
	<!--end row-->
	<div class="row row-cols-1 row-cols-lg-3">
		<div class="col-xl-4 d-flex">
			<div class="card radius-10 w-100">
				<div class="card-body">					
					<div id="chart7"></div>
					
				</div>				
			</div>
		</div>
		<div class="col-xl-8 d-flex">
			<div class="card radius-10 w-100">
				<div class="card-body">
					<div id="chart6"></div>
				</div>
			</div>
		</div>		
	</div>
	<!--end row-->
	
	<div class="card radius-10">
		<div class="card-body">
			<div class="d-flex align-items-center">
				<div>
					<h5 class="mb-1">Content Idea</h5>					
					<br>
					<button type="button" class="btn btn-primary btn-sm" id="tambahIdea">Add Idea</button>						
				</div>
				
			</div>
			<hr/>
			
			<div class="table-responsive mt-4">
				<table id="tableIdea" class="table table-striped table-bordered" border="2">                
				</table>					
			</div>
			
		</div>
	</div>


<div class="modal fade modal-form" id="event_entry_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title text-white" id="exampleModalLabel">Add Event</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>			
			<div class="modal-body">
				
				<form class="form-horizontal form-label-left row g-3" id="form_crud" method="post">
				<input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />                            
				<input type="hidden" class="form-control" id="id" name="id" value="" />
				@csrf
					<div class="col-md-12">
						<label for="inputFirstName" class="form-label">Jenis Sosial Media</label>						
						<select class="{{$data['classFormSelect']}} clearDisable" name="sosmed" id="sosmed" >
							<option value=""></option>
							@foreach($Sosmed as $datax)
							<option value="{{ $datax->sosmed_id }}" >{{ ucfirst(trans($datax->sosmed_jenis)) }}</option>
							@endforeach                                                                                                    
						</select>                                            
						
					</div>
					<div class="col-md-12">
						<label for="inputFirstName" class="form-label">Kategori Event</label>
						@if (($MCPlanner)->isEmpty())
						<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
							<div class="text-white">Kategori Event Belum Disetup</div>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						@else
							<select class="{{$data['classFormSelect2']}} clearDisable" name="kat" id="kat" >
								<option value=""></option>
								@foreach($MCPlanner as $data)
								<option value="{{ $data->m_cplanner_id }}" >{{ ucfirst(trans($data->m_cplanner_nama)) }}</option>
								@endforeach                                                                                                    
							</select>                                            
						@endif
					</div>
					<div class="col-md-12">
						<label for="inputLastName" class="form-label">Event Nama</label>
						<input type="text" class="form-control" id="event_nama" name="event_nama">
					</div>
					<div class="col-md-6">
						<label for="inputEmail" class="form-label">Tanggal</label>
						<input type="text" class="form-control datepicker" name="tgl_event" id="tgl_event">
					</div>
					<div class="col-md-6">
						<label for="inputPassword" class="form-label">Jam</label>
						<input class="result form-control" type="text" name="time" id="time">
					</div>					
					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" id="btn_simpan">Save Event</button>
			</div>
			
			</form>
		</div>
	</div>
</div>
<!-- End popup dialog box -->

<div class="modal fade modal-form1" id="entry_idea" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title text-white" id="exampleModalLabel">Add Event</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>			
			<div class="modal-body">
				
				<form class="form-horizontal form-label-left row g-3" id="form_crud2" method="post">
				<input type="hidden" class="form-control" id="method_field2" name="_method" value="POST" />                            
				<input type="hidden" class="form-control" id="id2" name="id2" value="" />
				@csrf
					<div class="col-md-12">
						<label for="inputFirstName" class="form-label">Jenis Sosial Media</label>						
						<select class="form-select form-select-sm clearDisable" name="sosmed2" id="sosmed2" >
							<option value=""></option>
							@foreach($Sosmed as $datax)
							<option value="{{ $datax->sosmed_id }}" >{{ ucfirst(trans($datax->sosmed_jenis)) }}</option>
							@endforeach                                                                                                    
						</select>                                            
						
					</div>					
					<div class="col-md-12">
						<label for="inputLastName" class="form-label">Deskripsi</label>
						<textarea class="form-control" id="idea" name="idea" placeholder="Deskripsi Idea" rows="3"></textarea>						
					</div>
					<div class="col-md-12">
						<label for="inputPassword" class="form-label">PIC</label>
						<input class="form-control" type="text" name="pic" id="pic">
					</div>
					<div class="col-md-6">
						<label for="inputFirstName" class="form-label">Status</label>						
						<select class="form-select form-select-sm clearDisable" name="status2" id="status2" >
							<option value="2">On Progress</option>
							<option value="1">Complete</option>
						</select>                                            
					</div>														
					<div class="col-md-6">
						<label for="inputEmail" class="form-label">Tenggat Waktu</label>
						<input type="text" class="form-control datepicker" name="tenggat_waktu" id="tenggat_waktu">
					</div>
					<div class="col-md-6">
						<label for="inputFirstName" class="form-label">Url Inspirasi</label>						
						<input class="form-control" type="text" name="url_inspirasi" id="url_inspirasi">                                        
					</div>														
					<div class="col-md-6">
						<label for="inputFirstName" class="form-label">Url File</label>						
						<input class="form-control" type="text" name="url_file" id="url_file">    
					</div>														
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" id="btn_simpan2">Save Event</button>
			</div>
			
			</form>
		</div>
	</div>
</div>
<!-- End popup dialog box -->

<script src="{{ asset('additional/js/content_planner/content_planner.js') }}"></script>

<script>

	
</script>
<!--<script src="{{asset('bank_stiep/plugins/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{asset('bank_stiep/plugins/fullcalendar/js/calendar/cal-init.js') }}"></script>-->





