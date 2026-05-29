
<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
            <button id="tambah" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button><br><br>
            <table id="example2" class="table table-striped table-bordered" border="2">
                
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-form" id="exampleExtraLargeModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="modal_label">Form Usulan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal form-label-left" id="form_crud" method="post">
                <div class="modal-body">                
                    @csrf   
                    <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" /> 
                    <input type="hidden" class="form-control" id="id" value="" name="id">                
                    <div id="error-validation"></div>
                    <div class="row">     
                        <div class="col-12">
                            <label for="inputEmailAddress" class="form-label"><b>Nama</b></label>                            
                            <input type="text" class="{{$data['classFormControl']}}" id="name" name="name" >
                            <label for="name" generated="true" class="error"></label>
                            <label id="validationError"></label>
                        </div>              
                        <div style="margin-left:0px; margin-right:10px;">
                            <div class="border border-primary p-3 rounded">    
                                <div id="invoice">            
                                    <div class="invoice">	 		
                                        <div class="row form_custom">																			
                                            <div class="col-6">
                                                <label for="inputEmailAddress" class="form-label"><b>Username</b></label>                            
                                                <input type="text" class="form-control form-control-sm" id="username" name="username" >
                                                <label for="username" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-6">
                                                <label for="inputEmailAddress" class="form-label"><b>Password</b></label>                            
                                                <input type="password" class="form-control form-control-sm" id="password" name="password" >
                                                <label for="password" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-6">
                                                <label for="inputEmailAddress" class="form-label"><b>Username Tiktok</b></label>                            
                                                <input type="text" class="form-control form-control-sm" id="username_tiktok" name="username_tiktok" >
                                                <label for="username_tiktok" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>
                                            <div class="col-6">
                                                <label for="inputEmailAddress" class="form-label"><b>Email</b>&nbsp;</label>                            
                                                <input type="text" class="form-control form-control-sm" id="email" name="email" >
                                                <label for="email" generated="true" class="error"></label>
                                                <label id="validationError"></label>
                                            </div>	
											<div class="col-12">
                                                <label for="inputEmailAddress" class="form-label"><b>Grup Bisnis</b></label>                            
                                                @if (($kelas)->isEmpty())
												<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
													<div class="text-white">Grup Bisnis Belum Disetup</div>
													<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
												</div>
												@else
													<select class="{{$data['classFormSelect2']}} clearDisable" name="kelas" id="kelas" >
														<option value=""></option>
														@foreach($kelas as $data)
														<option value="{{ $data->id }}" >{{ ucfirst(trans($data->name)) }}</option>
														@endforeach                                                                                                    
													</select>                                            
												@endif
                                            </div>  												
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="modal-footer">                    
                    <button type="button" id="btn_simpan" class="btn btn-primary btn-sm"><i class="bx bx-save mr-1"></i>Simpan</button>
                    <button type="button" id="btn_back" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bx bx-arrow-to-left mr-1"></i>Kembali</button>                    
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('additional/js/manajemen_user/manajemen_user.js') }}"></script>


