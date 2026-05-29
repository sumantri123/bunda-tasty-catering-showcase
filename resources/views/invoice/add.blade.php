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
										<input type="hidden" class="{{$data['classFormControl']}}" id="id_invoice" value="{{$data['idInvoice'] ?? ""}}" name="id_invoice">
										<input type="hidden" class="{{$data['classFormControl']}}" id="ke" value="" name="ke">    
                                        <label for="no_bukti" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Invoice Date</label>
                                        <input type="date" class="{{$data['classFormControl']}}" id="tgl" value="" name="tgl">
                                        <label for="tgl" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Due Date</label>
                                        <input type="date" class="{{$data['classFormControl']}}" id="due_tgl" value="" name="due_tgl">
                                        <label for="due_tgl" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
									<div class="col-md-3">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">NO. PO</label>
                                        <input type="text" class="{{$data['classFormControl']}}" id="no_po" value="" name="no_po">
                                        <label for="no_po" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
									                              								
									<div class="col-md-3">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Tertanda</label>
										<input type="text" class="{{$data['classFormControl']}}" id="ttd" placeholder="IT Manager" value="IT Manager" onkeydown="upperCaseF(this)" name="ttd">    									
										<label for="ttd" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>
									<div class="col-md-3">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Pejabat Nama</label>
										@if (($pejabat)->isEmpty())
                                        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                            <div class="text-white">Data Pejabat Tidak Ada</div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                        @else
                                            <select class="single-select clearDisable" onchange="getval2(this);" name="pejabat_id" id="pejabat_id" >
                                                <option value=""></option>
                                                @foreach($pejabat as $data)
                                                <option value="{{ $data->pejabat_id }}" nama="{{ $data->pejabat_nama }}" >{{ ucfirst(trans($data->pejabat_nama)) }}</option>
                                                @endforeach                                                                                                    
                                            </select>                                            
										<input type="hidden" class="form-control form-control-sm" id="pejabat" value="" name="pejabat">
                                        @endif										
										<label for="pejabat" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>
									<div class="col-md-3">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">No. Telp</label>
										<input type="text" class="form-control form-control-sm" id="no_telp" value="" name="no_telp">    									
										<input type="hidden" id="jumlahData" name="jumlahData" class="{{$data['classFormControl']}}" readonly>
										<label for="no_telp" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>									
									<div class="col-md-3">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Pajak (%)</label>
										<div class="input-group input-group-sm mb-3">
											<input type="number" id="pajak_global" name="pajak_global" class="form-control form-control-sm" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">											
											<span class="input-group-text" id="inputGroup-sizing-sm">%</span>
										</div>
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

<script src="{{ asset('additional/js/invoice/add_invoice.js') }}"></script>
