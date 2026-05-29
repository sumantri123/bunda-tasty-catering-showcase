<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
    <div class="border p-1 rounded">
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
                            <div class="accordion" id="accordionExample">
							    <div class="accordion-item">
								    <h2 class="accordion-header" id="headingOne">
						                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							                <b class="text-primary">Petunjuk Upload Data Awal</b>
						                </button>
						            </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">	
                                            <div class="text-primary">1. Pastikan File Yang diupload dengan Extension .xls atau .xlsx</div>
                                            <div class="text-primary">2. Maksimal File Yang Bisa diupload sebesar 2MB</div>
                                            <div class="text-primary">3. Data Yang di Import Akan Secara Otomatis Menambah Di :</div>
                                            <div class="text-primary">&emsp;- Master Nilai Tukar</div> 
                                            <div class="text-primary">&emsp;- Data CIF</div> 
                                            <div class="text-primary">4. Klik Tombol Untuk Create Saldo Awal Rekening</div>
                                            <div class="text-primary">&emsp;- Data Rekening Tabungan Dengan Saldo Awal 10 Jt</div> 
                                            <div class="text-primary">&emsp;- Data Rekening Giro Dengan Saldo Awal 50 Jt</div> 
                                            <div class="text-primary">&emsp;- Data Rekening Deposito 1 Bulan Dengan Saldo Awal 10 Jt</div> 
                                            <div class="text-primary">&emsp;- Data Rekening Pinjaman Reguler Sebesar 50 Jt</div> 
                                            <div class="text-primary">&emsp;- Data Rekening Pinjaman Installment (Pinjaman Motor) Sebesar 50 Jt</div> 
                                            <div class="text-primary">&emsp;- Data Rekening Pinjaman Installment (Pinjaman KPR) Sebesar 100 Jt</div> 
                                            <div class="text-primary">5. Nasabah dengan Nama Yang Sama dalam 1 Kelas Yang Sama Tidak Dapat diupload 2x</div>
                                        </div>
                                    </div>
                                </div>
							</div>
                            <center>                                    
                                <div id='loading' style='display: none;'>                                    
                                    <button class="btn btn-warning" type="button" disabled> <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                            </center><br>							
                            <form class="form-horizontal form-label-left" id="form_upload" method="post" enctype="multipart/form-data">            
                                @csrf
                                <input type="hidden" class="form-control" id="method_field" name="_method" value="POST" />
                                <input type="hidden" class="form-control" id="id" value="" name="id">
                                <div id="error-validation"></div>                                                                                   
                                <div class="card-body">                                    
                                    <div class="col-12">                                                                                                                            
                                        <div class="ms-12">
                                            <b><h6 class="mb-0 text-uppercase">{{$data['title']}}</h6></b>
                                            <hr/> 
                                            <div class="input-group mb-3">                                                            
                                                <input type="file" name="file" class="form-control form-control-sm" id="inputGroupFile01">
                                                <button class="btn btn-primary btn-sm" type="submit" id="inputGroupFileAddon03"><i class="bx bx-cloud-upload me-0"></i>&nbsp;Upload</button>                                 
                                                <button class="btn btn-success btn-sm btn-download-template" type="button" id="download_template"><i class="bx bx-cloud-download me-0"></i>&nbsp;Download Template</button>                                 
                                            </div>      
                                        </div>                                                                                        
                                    </div>                                                                                                                                
                                    <!-- <div class="card-title d-flex align-items-center">                                    	
                                        <div class="input-group mb-3">                                       
                                            <button class="btn btn-primary btn-sm" type="submit" id="inputGroupFileAddon03">Upload</button>                                 
                                            <input type="file" name="file" class="form-control form-control-sm" id="inputGroupFile01">
                                        </div>		                                    												
                                    </div>	                                     -->
                                </div>
                            </form>
                            <div class="table-responsive">                                
                                <table id="example2" class="table table-striped table-bordered" border="2"></table>
                            </div>
                        </main>
                        
                    </div>                    
                </div>
            </div>
        </div>        
    </div>
</div>

<script src="{{ asset('additional/js/data_awal.js') }}"></script>
