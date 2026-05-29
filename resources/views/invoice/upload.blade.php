<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">            
			<input type="hidden" class="form-control" id="kode" value="{{$data['kode']}}" name="kode">
			<input type="hidden" class="form-control" id="idInvoice" value="{{$data['idInvoice']}}" name="idInvoice">			
			<input type="hidden" class="form-control" id="idPenawaran" value="{{$data['idPenawaran']}}" name="idPenawaran">			
			<!--<a class="{{$data['btnClass']}} action" data-href="/addPenawaran/{{$data['kode']}}">{{$data['btnAdd']}}</a>-->
			<button type="button" id="tambah" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button>			
			<br><br>
            <table id="example2" class="table table-striped table-bordered" border="2">
                
            </table>
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
	
<!-- Modal -->
<div class="modal fade modal-form" tabindex="-1" id="exampleLargeModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_label">Form Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal form-label-left" id="data_form" method="post" enctype="multipart/form-data">
                <div class="modal-body">                
                    @csrf
                    <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />
                    <input type="hidden" class="form-control" id="id" value="{{$data['idInvoice']}}" name="id">
					<input type="hidden" class="form-control" id="id_penawaran" value="{{$data['idPenawaran']}}" name="id_penawaran">
                    <div id="error-validation"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="inputPhoneNo" class="form-label"><b>File</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-plus-square' ></i></span>
                                <input type="file" class="form-control border-start-0" id="file" name="file" accept="application/pdf"  />
                            </div>
                            <label for="kode_perkiraan" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>                                         
                    </div>
                    
                </div>
                <div class="modal-footer">                    
                    <button type="submit" id="btn_simpan" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('additional/js/invoice/list_faktur.js') }}"></script>
