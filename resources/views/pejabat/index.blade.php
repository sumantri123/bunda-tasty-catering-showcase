
<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
			<input type="hidden" class="form-control" id="pass" value="{{$data['pass']}}" name="pass">
            <button id="tambah" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button><br><br>
            <table id="example2" class="table table-striped table-bordered" border="2">
                
            </table>
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
                    <input type="hidden" class="form-control" id="id" value="" name="id">
                    <div id="error-validation"></div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="inputPhoneNo" class="form-label"><b>Nama</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-plus-square' ></i></span>
                                <input type="text" class="form-control border-start-0" id="nama" name="nama" placeholder="Supplier Nama" />
                            </div>
                            <label for="nama" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>
                        <div class="col-6">
                            <label for="inputEmailAddress" class="form-label"><b>Alamat</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
                                <input type="text" class="form-control border-start-0" id="alamat" name="alamat" placeholder="Supplier Alamat" />
                            </div>
                            <label for="alamat" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>
                        <div class="col-6">
                            <label for="inputEmailAddress" class="form-label"><b>Telp</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
                                <input type="text" class="form-control border-start-0" id="telp" name="telp" placeholder="Telp" />                                
                            </div>
                            <label for="telp" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>
						<div class="col-6">
                            <label for="inputEmailAddress" class="form-label"><b>Jabatan</b></label>
                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
                                <input type="text" class="form-control border-start-0" id="owner" name="owner" placeholder="Owner" />                                
                            </div>
                            <label for="owner" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>
						<div class="col-12">
                            <label for="inputPhoneNo" class="form-label"><b>File</b></label>
                            <div id="new_upload" class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-plus-square' ></i></span>
                                <input type="file" class="form-control border-start-0" id="file" name="file" accept="image/png"  />
                            </div>
							
							<div class="input-group" id="edit_upload">								
								<img id="my_image" src="" style="width:300px; height:220px;"/></a>																	
								<i class="bx bx-message-square-x bx-md text-danger" id="delete_image"></i>									
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

<script src="{{ asset('additional/js/pejabat/pejabat.js?v=1.01') }}"></script>


