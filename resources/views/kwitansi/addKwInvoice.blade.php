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
								<input type="hidden" class="form-control" id="idKw" name="idKw" value="{{$data['idKwitansi'] ?? ''}}" />
								<input type="hidden" class="form-control" id="idInvoice" name="idInvoice" value="{{$data['idInvoice'] ?? ''}}" />
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
                                        <label for="no_bukti" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Kwitansi Date</label>
                                        <input type="date" class="{{$data['classFormControl']}}" id="tgl" value="" name="tgl">
                                        <label for="tgl" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Nominal</label>
                                        <input type="text" class="form-control form-control-sm" readonly value="{{$data['totalInvoice']}}" id="nominal" value="" name="nominal">
                                        <label for="nominal" generated="true" class="error"></label>
                                        <label id="validationError"></label>
                                    </div>									
									<div class="col-md-4">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Pajak (%)</label>
										<div class="input-group input-group-sm mb-3">
											<input type="text" id="pajak_persen" name="pajak_persen" readonly value="{{$data['invoicePajakPersen']}}" class="form-control form-control-sm" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">											
											<span class="input-group-text" id="inputGroup-sizing-sm">%</span>
										</div>
									</div>
									<div class="col-md-4">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Pajak (Rp)</label>
										<div class="input-group input-group-sm mb-3">
											<input type="text" id="pajak_rp" name="pajak_rp" readonly class="form-control form-control-sm" value="{{$data['totalPajak']}}" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">											
											<span class="input-group-text" id="inputGroup-sizing-sm">Rp</span>
										</div>
									</div>									
									<div class="col-md-4">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Tertanda</label>
										<input type="text" class="form-control form-control-sm" id="ttd" name="ttd">    									
										<label for="ttd" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>
									<div class="col-md-12">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Terbilang</label>
										<input type="text" class="form-control form-control-sm" readonly value="{{$data['terbilang']}}" id="terbilang" name="terbilang">    									
										<label for="terbilang" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>
									<div class="col-md-12">
										<label for="inputCity" class="form-label" style="color:blue; font-weight:bold">Deskripsi</label>
										<textarea class="form-control form-control-sm" id="deskripsi" name="deskripsi"></textarea>										
										<label for="deskripsi" generated="true" class="error"></label>
										<label id="validationError"></label>
									</div>
                                </div>  
								
                            </form>
                        </main>
                        
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('additional/js/kwitansi/add_kw_inv.js') }}"></script>
