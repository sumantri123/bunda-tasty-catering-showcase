var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () { 	
	
	var status = $('#status').val();	
	
	if(status=='edit'){		
		refresh();		
	} else {				
		disableEntry();
	}
			
});    

function getval(sel){                

    var selected = sel.value;  
  
	$('#company').val( $("#company_id option:selected").attr("nama"));
  
}


function refresh() {
	
	var idKw = $('#idKw').val();		
	var kode = $('#bagian').val();
	$('#search').attr('readOnly', true);
	$('#method_field').val("PUT");    
	
	$.ajax({
		type: 'post',
		url: "" + base_url + "/search/kw",
		dataType: 'JSON',
		data: {
			_token: CSRF_TOKEN,
			kode: kode,
			idKw: idKw
		},
		beforeSend: function(){
			BeforeSend();
		},
		complete: function(){
			AfterSend();
		},
		success: function (data) {
			if (data.status == 'oke') {
				
				$('#no_bukti').val(data.kwNo);
				$('#idKw').val(data.kwId);
				$('#tgl').val(data.kwTgl);
				$('#company_id').val(data.idCustomer).trigger('change'); 
				$('#company_id').select2({ width: "100%" });
				$('#company').val(data.kwCompany);	
				$('#nominal').val(data.kwNominal);	
				$('#terbilang').val(data.kwTerbilang);	
				$('#pajak_persen').val(data.kwPajakPersen);	
				$('#pajak_rp').val(data.kwPajakRp); 
				$('#ttd').val(data.kwTtd);
				$('#deskripsi').val(data.kwDeskripsi);				

			} else {
				error_noti('Data Tidak Tersedia');                     
			}
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
		}
	});
}	

$('button#btn_new').on('click', function () {  
	
    enableEntry();  
	
    $('#method_field').val("POST");  	    
    $('#no_bukti').attr('readOnly', true); 
	$('#pajak_rp').attr('readOnly', true); 
    
});


function formatRupiah(y){
	
	var query = y.value;    
	var pajak = $('#pajak_persen').val();
	
	var pajak_nominal = (query * pajak) / 100;
	
	$('#pajak_rp').val(convertToRupiahNoRp(pajak_nominal));   	
	
}

function formatPajak(y){
	
	var query = y.value;    
	var pajak = $('#nominal').val();
	
	var pajak_nominal = (query * pajak) / 100;
	
	$('#pajak_rp').val(convertToRupiahNoRp(pajak_nominal));   	
	
}


$('#formEntry').submit(function(e) {
	e.preventDefault();
	
	var method = $('#method_field').val();
	var action_url = "" + base_url + "/addKwNonInv";
	var action_type = "Tambah";
	if (method === "PUT") {
		action_url = "" + base_url + "/addKwNonInv/" + $('#idKw').val();
		action_type = "Ubah";
	}
		
	var formData = new FormData(this);        
	var form = $('#formEntry');
	
	if (form.valid() == true) {    
					
		$.ajax({
			type:'POST',
			url: action_url,
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			beforeSend: function(){
				BeforeSend();
			},
			complete: function(){
				AfterSend();
			},
			success: function (data) {                    
                if (data.status == 'insert_successful') {                        
                    success_noti('Berhasil ' + action_type + ' Data');
                    $('#idKw').val(data.id);					
                    $('#method_field').val("PUT");					
					refresh();
                                                                            
                } else if (data.status == 'insert_failed') {
                    
                    error_noti('Gagal ' + action_type + ' Data'+ data.msg);                    
                    var errors = data.error;
                    errorValidationLaravel(errors, '#error-validation');


                } else {
                    error_noti('Gagal ' + action_type + ' (Kesalahan Sistem)');
                }
            },
			error: function (error) {
				//error_noti(error.responseJSON.errors.file);
				error_noti("Data Gagal Diupload");
			}
		});
	} else {
		
		error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
	}    
});


var validator = $('#formEntry').validate({

    rules: {                
        tgl: {required: true},        				
		company: {required: true},
		nominal: {required: true},
		terbilang: {required: true},
		ttd: {required: true},
		deskripsi: {required: true},
    },

    highlight: function (element, errorClass, validClass, error) {

        $(element.form).find("[id=" + element.id + "]").addClass('is-invalid');
        $(element.form).find("[id=" + element.id + "]").addClass('is-invalid');
        $(element.form).find("[id=" + element.id + "]").removeClass('is-valid');

    },

    unhighlight: function (element, errorClass, validClass) {
        $(element.form).find("[id=" + element.id + "]").removeClass('is-invalid');
        $(element.form).find("[id=" + element.id + "]").addClass('is-valid');
    }
});

//--------------------- Setup DatePicker ---------------------
$('.single-select').select2({
    theme: 'bootstrap4',		
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    allowClear: Boolean($(this).data('allow-clear')),
});

$('.single-select2').select2({
    theme: 'bootstrap4',		
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    allowClear: Boolean($(this).data('allow-clear')),
});

$('.datepicker').pickadate({			
        selectMonths: true,
        selectYears: true
    }),		

$('.timepicker').pickatime()

$(function() {
    $(".knob").knob();
});

$(function () {
    $('#date-time').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD HH:mm'
    });
    $('#date').bootstrapMaterialDatePicker({
        time: false
    });
    $('#time').bootstrapMaterialDatePicker({
        date: false,
        format: 'HH:mm'
    });
});
