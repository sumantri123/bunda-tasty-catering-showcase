<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
            <input type="hidden" class="form-control" id="pass" value="{{$data['pass']}}" name="pass">
			<input type="hidden" class="form-control" id="kode" value="{{$data['kode']}}" name="kode">									
			<button type="button" id="tambah" class="{{$data['btnClass']}}">{{$data['btnAdd']}}</button>			
			<br><br>
            <table id="example2" class="table table-striped table-bordered" border="2">
                
            </table>
        </div>
    </div>
</div>


<script src="{{ asset('additional/js/kwitansi/kwt.js') }}"></script>
