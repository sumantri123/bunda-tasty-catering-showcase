<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="border border-secondary p-2 rounded">
            <div id="invoice">            
                <div class="invoice overflow-auto">
                    <div id="transContent" style="min-width: 600px">
                        <header>
                            <div class="row">
								<div class="d-flex align-items-center">  
									<div class="col-sm-1" align="center">                                    
										<img src="{{ URL::asset(session("logoHeaderTransaksi")) }}" alt="" />                
									</div>
									<div class="col-sm-11" style="margin-left:30px">
										<h4 class="name"><a href="javascript:;"><strong>{{$data['title']}}</strong></a></h4>
										<h6 class="name font-16"><a href="javascript:;">{{$data['subtitle']}}</a></h6>
									</div>                                                                            
								</div>
							</div>
                        </header>
                        <main>
                            <form class="form-horizontal form-label-left" id="formEntry" method="post">    
                                @csrf                                                    
                                <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />                            
                                <input type="hidden" class="form-control" id="bagian" name="bagian" value="{{$data['kode']}}" />
								<input type="hidden" class="form-control" id="status" name="status" value="{{$data['status']}}" />
                                <div class="row ">
                                    <nav class="navbar navbar-expand-sm navbar-dark bg-secondary rounded">
                                        <div class="container-fluid"> 
                                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent5" aria-controls="navbarSupportedContent5" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span></button>
                                            <div class="collapse navbar-collapse" id="navbarSupportedContent5">                                                                                            
                                                <div class="col-md-12" align="right">													
                                                    <button type="button" id="btn_new" class="btn btn-primary btn-sm btn-action"><i class='bx bx-file mr-1'></i>Transaksi Baru</button>
                                                    <button type="submit" id="btn_simpan" class="btn btn-success btn-sm btn-action"><i class='bx bx-save mr-1'></i>Simpan</button>                                                    													
                                                </div>
                                            </div>
                                        </div>
                                    </nav>   
                                </div><br>                                                 
                                <div class="row form_custom">                                     
                                    <div class="col-md-3">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">No. Bukti</label>
                                        <input type="text" class="{{$data['classFormControl']}}" id="no_bukti" value="" onkeydown="upperCaseF(this)" name="no_bukti" readonly>    
                                        <input type="hidden" class="{{$data['classFormControl']}}" id="id_jb" value="{{$data['idJb']}}" name="id_jb">    
										<input type="hidden" class="{{$data['classFormControl']}}" id="id_do" value="{{$data['idDO'] ?? ""}}" name="id_do">    
                                        <label for="no_bukti" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Tanggal</label>
                                        <input type="date" class="{{$data['classFormControl']}}" id="tgl" value="" name="tgl">
                                        <label for="tgl" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Jenis</label>
                                        <select name="jenis" class="{{$data['classFormSelect2']}}" id="jenis">
											<option value="">Select Jenis</option>
											<option value="Untuk Dibeli" @if (old('jenis') == "Untuk Dibeli") {{ 'selected' }} @endif>Untuk Dibeli</option>
											<option value="Untuk Dipinjamkan" @if (old('jenis') == "Untuk Dipinjamkan") {{ 'selected' }} @endif>Untuk Dipinjamkan</option>
											<option value="Lain - Lain" @if (old('jenis') == "Lain - Lain") {{ 'selected' }} @endif>Lain - Lain</option>
										</select>
                                        <label for="jenis" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>                                									
									<div class="col-md-12">                                        
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Header</label>
                                        <textarea id="do_header" name="do_header"></textarea>
                                        <label for="do_header" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>                                									
									<div class="col-md-6">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Tertanda</label>
										<input type="text" class="{{$data['classFormControl']}}" id="ttd" value="IT Solution Simarfian" onkeydown="upperCaseF(this)" name="ttd">    									
										<label for="ttd" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>
									<div class="col-md-3">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Pejabat Pengirim</label>
										<input type="text" class="{{$data['classFormControl']}}" id="pejabat" value="" onkeydown="upperCaseF(this)" name="pejabat">    									
										<input type="hidden" id="jumlahData" name="jumlahData" class="{{$data['classFormControl']}}" readonly>
										<label for="pejabat" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>	
									<div class="col-md-3">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">No. PO</label>
										<input type="text" class="{{$data['classFormControl']}}" id="no_po" value="" name="no_po">    																			
										<label for="no_po" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>									
                                </div>  
								
								                                                                                                        
                                <hr>                                
                                <div class="form_custom1" id="show_table"></div>

                                <div class="alert alert-primary border-0 bg-primary alert-dismissible fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-white"><i class='bx bx-message-square-add'></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-white">Note :</h6>
                                            <div class="text-white">Tekan F2 Untuk Menambah Data</div>
                                        </div>
                                    </div>                            
                                </div>
								
                            </form>
                        </main>
                        <footer></footer>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<script src='https://cdn.tiny.cloud/1/tmbtaitrytbic853h3ju45dz5xqgt1jhcw2zis0t065shhmv/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script>
<script src="{{ asset('additional/js/delivery/add_do.js') }}"></script>
