<div class="row align-items-center">		
	<div class="col-md-12">
		<div class="row align-items-center shadow-none bg-transparent border-bottom border-2">
			<div class="col-md-6">
				<h4 class="mb-3 mb-md-0">{{$data['title']}}</h4>
			</div>
			<div class="col-md-6">
				<button id="tambah" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button><br><br>
					<div class="row row-cols-md-auto">							
						<label for="inputToDate" class="col-md-6 col-form-label text-md-end">Sosial Media</label>
						<div class="col-md-6">
							<select class="{{$data['classFormSelect']}} clearDisable" name="jenis_sosmed" id="jenis_sosmed" >									
								@foreach($Sosmed as $datax)
								<option value="{{ $datax->sosmed_id }}" >{{ ucfirst(trans($datax->sosmed_jenis)) }}</option>
								@endforeach                                                                                                    
							</select>
						</div>
					</div>
				
			</div>
		</div>
		<input type="hidden" class="form-control" id="open_id" name="open_id" value="<?php echo base64_encode(Session::get('openId'))?>" />
	</div>
</div><br>

<div id="badan"></div>

<!-- Modal Upload Video Tiktok -->
<div class="modal fade modal-form" id="exampleLargeModal" tabindex="-1" aria-hidden="true" >
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-gradient-deepblue">
				<h5 class="modal-title text-white">Modal title</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form_modal" method="post" enctype="multipart/form-data">
				<div class="modal-body">				
					<input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />
					<input type="hidden" class="form-control" id="openId" name="openId" value="" readonly/>
					<input type="hidden" class="form-control" id="idSosmed" name="idSosmed" value="" readonly/>
					@csrf
						<div class="col-md-12">
							<label for="inputPassword" class="form-label">Upload Video</label>
							<input class="form-control" type="file" name="file" id="file" accept="video/mp4,video/x-m4v,video/*">
						</div>
						<div class="col-md-12">
							<label for="inputLastName" class="form-label">Deskripsi & Hashtag</label>
							<textarea class="form-control" id="desc" name="desc" placeholder="Deskripsi Idea" rows="3"></textarea>						
						</div>
										
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-sm">Upload Draft Tiktok</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="myModalYoutube" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm">
	  <div class="modal-content">        
		<div class="modal-body">
			<button type="button" class="close" data-dismiss="modal" onclick="closeModalx()">&times;</button>
			<div class="embed-responsive embed-responsive-16by9">
				<iframe id="cartoonVideo" class="embed-responsive-item"  width="250" height="500" allow="autoplay *;" allowfullscreen></iframe>
				
			</div>
			
		</div>       
	  </div>
	  
	</div>
</div>


<script src="{{ asset('additional/js/dashboard_sosmed/dashboard_sosmed.js') }}"></script>
