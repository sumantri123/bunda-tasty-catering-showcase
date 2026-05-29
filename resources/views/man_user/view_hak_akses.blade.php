<div id="transContent">
    <h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
    <hr/>
    <div class="card border-top border-0 border-4 border-primary">		
        <div class="card-body">
            <div class="border border-primary p-3 rounded">
                <div id="invoice">            
                    <div class="invoice">	 							
                        <div class="table-responsive"> 
							<input type="hidden" class="form-control" id="id_user" value="{{$data['id']}}" name="id_user">
                           <!-- <button id="tambah" data-bs-toggle="modal" data-bs-target="#exampleExtraLargeModal" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button><br><br>-->
                            <table id="example2" class="table table-striped table-bordered" style="width:100%"></table>					    
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>    
</div>
<script src="{{ asset('additional/js/manajemen_user/hak_akses.js') }}"></script>
