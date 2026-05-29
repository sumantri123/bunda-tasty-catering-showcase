var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () { 
	
	tinymce.init({
		selector: '#do_header',
		placeholder: "Kepada Yth. ",
		forced_root_block : 'div',
		setup: function (editor) {
			editor.on('change', function () {
				tinymce.triggerSave();
			});
		},		
	});	
       
	addRow();        
	
	var status = $('#status').val();	
	
	if(status=='edit'){		
		refresh();		
	} else {				
		disableEntry();
	}
			
});    

function refresh() {
	
	var idDO = $('#id_do').val();		
	var kode = $('#bagian').val();
	$('#search').attr('readOnly', true);
	$('#method_field').val("PUT");    
	
	$.ajax({
		type: 'post',
		url: "" + base_url + "/search/DO",
		dataType: 'JSON',
		data: {
			_token: CSRF_TOKEN,
			kode: kode,
			idDO: idDO
		},
		beforeSend: function(){
			BeforeSend();
		},
		complete: function(){
			AfterSend();
		},
		success: function (data) {
			if (data.status == 'oke') {
				
				$('#no_bukti').val(data.doNo);
				$('#id_jb').val(data.idPenawaran);
				$('#id_do').val(data.doId);
				$('#tgl').val(data.doTgl);				
				$("#jenis").val(data.doJenis).trigger('change');                    
                $("#jenis").select2({ width: "100%" }); 
				$('#do_header').val(data.doHeader);				
				$('#ttd').val(data.doTtd);
				$('#pejabat').val(data.doPejabat);				
				$('#jumlahData').val(data.jumlahData);

				var totDetData = data.jumlahData;
				var b;
				var content;
				for (b = 0; b < totDetData; b++) {						                        

					content += "<tr class='body' id='row_"+b+"'>"
					content += "<td >"
					content += "<input type='text' class='form-control form-control-sm' id='no_"+b+"' value="+(parseInt(b)+1)+" name='no[]' readonly>"						
					content += "</td>"
					content += "<td >"
					content += "<input type='text' class='form-control form-control-sm' id='deskripsi_"+b+"' value='"+data.data[b].do_deskripsi+"' name='deskripsi[]'>"						
					content += "</td>"
					content += "<td class='text-left'>"
					content += "<input type='number' class='form-control form-control-sm' id='qty_"+b+"' value='"+data.data[b].qty+"' name='qty[]'>"                        
					content += "<input type='hidden' class='form-control form-control-sm' id='do_det_id_"+b+"' value='"+data.data[b].do_det_id+"' name='do_det_id_[]' readonly>"
					content += "<input type='hidden' class='form-control form-control-sm' id='method_det_"+b+"' value='PUT' name='method_det[]' readonly>"
					content += "</td>"
					content += "<td ><input type='text' class='form-control form-control-sm' id='satuan_"+b+"' value='"+(data.data[b].do_satuan)+"' name='satuan[]'></td>"
					content += "<td ><input type='text' class='form-control form-control-sm' id='keterangan_"+b+"' value='"+(data.data[b].do_keterangan)+"' name='keterangan[]'></td>"											
					content += "<td >"
					content += "<div class='ms-auto d-flex align-items-center'>"
					content += "<button type='button' class='btn btn-success  px-2 ms-2' onclick='saveDet("+b+")'><i class='bx bxs-save me-0'></i></button>"
					content += "<button type='button' class='btn btn-danger  px-2 ms-2' onclick='delDet("+b+")' ><i class='bx bxs-trash me-0'></i></button>"
					content += "</div>"
					content += "</td>"
					content += "</tr>";
				}
				 $("#myTable > tbody").append(content);				 
			
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

	var idPenawaran = $('#id_jb').val();
    enableEntry();      
    $('#myTable tr.body').remove();
    $('#method_field').val("POST");  
	$('#id_jb').val(idPenawaran);
    /* $('#search').attr('readOnly', true);       
    $('#searchGrup').hide("slow");  */
    $('#no_bukti').attr('readOnly', true); 
	//$('#addSub').attr('disabled','disabled');	
    
});


function addRow(){	
    var a = -1;
    var b = a++;
    
    var content = "<table id='myTable' border='1' class='classTable'>"
        content += "<thead>"
        content += "<tr>"
        content += "<th width='5%' class='text-center'><b>No</b></th>"
        content += "<th width='35%' class='text-center'><b>Jenis Barang</b></th>"
        content += "<th width='10%' class='text-center'><b>Qty</b></th>"
		content += "<th width='10%' class='text-center'><b>Satuan</b></th>"				
        content += "<th width='30%' class='text-center'><b>Keterangan</b></th>"
        content += "<th width='10%' class='text-center'><button type='button' class='btn btn-light px-2 ms-2' id='addSub' ><i class='bx bxs-message-square-add me-0'></i></button></th>"
        content += "</tr>"
        content += "</thead>"
        content += "<tbody>"        
        content += "</tbody>";
    content += "</table>"
    $('#show_table').append(content);
            
    document.onkeydown = function(){
        if(window.event && window.event.keyCode == 113) {                                
			var jumlahData = $('#jumlahData').val();			
			var b = (jumlahData !== "") ? jumlahData++ : a++ ;            

            $("#myTable > tbody").append(                    
                "<tr class='body' id='row_"+b+"'>"+
					"<td >"+
                    "<input type='text' class='form-control form-control-sm' id='no_"+b+"' value="+(parseInt(b)+1)+" name='no[]' readonly>"+                        
                    "</td>"+
                    "<td >"+
                    "<input type='text' class='form-control form-control-sm' id='deskripsi_"+b+"' value='' name='deskripsi[]'>"+                        
                    "</td>"+
                    "<td class='text-left'>"+
                    "<input type='number' class='form-control form-control-sm' id='qty_"+b+"' value='' name='qty[]'>"+                    
                    "<input type='hidden' class='form-control form-control-sm' id='do_det_id_"+b+"' value='' name='do_det_id[]' readonly>"+
					"<input type='hidden' class='form-control form-control-sm' id='method_det_"+b+"' value='POST' name='method_det[]' readonly>"+
                    "</td>"+
                    "<td ><input type='text' class='form-control form-control-sm' id='satuan_"+b+"' value='' name='satuan[]'></td>"+
                    "<td ><input type='text' class='form-control form-control-sm' id='keterangan_"+b+"' value='' name='keterangan[]'></td>"+										
                    "<td >"+
                    "<div class='ms-auto d-flex align-items-center'>"+
                    "<button type='button' class='btn btn-success px-2 ms-2' id='saveId_"+b+"' onclick='saveDet("+b+")'><i class='bx bxs-save me-0'></i></button>"+
                    "<button type='button' class='btn btn-danger px-2 ms-2' onclick='delDet("+b+")'><i class='bx bxs-trash me-0'></i></button>"+
                    "</div>"+
                    "</td>"+
                "</tr>"
            );                
            document.getElementById("deskripsi_"+b).focus();                
            
        } 
		
    }
    
	$('#addSub').click(function(){
		
		//var b = a++;  
		var jumlahData = $('#jumlahData').val();					
		var b = (jumlahData !== "") ? jumlahData++ : a++ ;

		$("#myTable > tbody").append(                    
			"<tr class='body' id='row_"+b+"'>"+
				"<td >"+
				"<input type='text' class='form-control form-control-sm' id='no_"+b+"' value="+(parseInt(b)+1)+" name='no[]' readonly>"+                        
				"</td>"+
				"<td >"+
				"<input type='text' class='form-control form-control-sm' id='deskripsi_"+b+"' value='' name='deskripsi[]'>"+                        
				"</td>"+
				"<td class='text-left'>"+
				"<input type='number' class='form-control form-control-sm' id='qty_"+b+"' value='' name='qty[]'>"+				
				"<input type='hidden' class='form-control form-control-sm' id='do_det_id_"+b+"' value='' name='do_det_id_[]'>"+
				"<input type='hidden' class='form-control form-control-sm' id='method_det_"+b+"' value='POST' name='method_det[]' readonly>"+
				"</td>"+
				"<td ><input type='text' class='form-control form-control-sm' id='satuan_"+b+"' value='' name='satuan[]'></td>"+
				"<td ><input type='text' class='form-control form-control-sm' id='keterangan_"+b+"' value='' name='keterangan[]'></td>"+								
				"<td >"+
				"<div class='ms-auto d-flex align-items-center'>"+
				"<button type='button' class='btn btn-success px-2 ms-2' id='saveId_"+b+"' onclick='saveDet("+b+")'><i class='bx bxs-save me-0'></i></button>"+
				"<button type='button' class='btn btn-danger px-2 ms-2' onclick='delDet("+b+")'><i class='bx bxs-trash me-0'></i></button>"+
				"</div>"+
				"</td>"+
			"</tr>"
		);                
		document.getElementById("deskripsi_"+b).focus();    
			
	});
}    

$('#formEntry').submit(function(e) {
	e.preventDefault();
	
	var method = $('#method_field').val();
	var action_url = "" + base_url + "/addDO";
	var action_type = "Tambah";
	if (method === "PUT") {
		action_url = "" + base_url + "/addDO/" + $('#id_do').val();
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
                    $('#id_do').val(data.id);
					//$('#no_bukti').val(data.no);                     
                    $('#method_field').val("PUT");                  
					$('#myTable tr.body').remove();
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
        do_header: {required: true},                                              
		jenis: {required: true},                                              
		pejabat: {required: true},
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

// Untuk Simpan Jurnal Detail

function saveDet(b){

    var id_do = document.getElementById("id_do").value;    
    var deskripsi = document.getElementById("deskripsi_"+b).value;                
	var idDoDet = document.getElementById("do_det_id_"+b).value;
	var qty = document.getElementById("qty_"+b).value;                
    var satuan = document.getElementById("satuan_"+b).value;   
    var keterangan = document.getElementById("keterangan_"+b).value;	
    
        
    if(id_do==""){
        info_noti('Anda Belum Membuat No. Bukti');	                
        
    } else {
		
		var method = $('#method_det_'+b).val();
		var action_url = "" + base_url + "/doDet";  
        var action_type = "Tambah";   
		if (method === "PUT") {			
			action_url = "" + base_url + "/doDet/" + idDoDet;
			action_type = "Ubah";
		}

        $.ajax({
            type: method,
            url: action_url,
            dataType: 'JSON',
            data: {_token: CSRF_TOKEN, idDO:id_do, qty:qty, satuan:satuan, keterangan:keterangan, deskripsi:deskripsi},            
            beforeSend: function(){
                BeforeSend();
            },
            complete: function(){
                AfterSend();
            },
            success: function (data) {                    
                if (data.status == 'insert_successful') {                        
                    success_noti('Berhasil ' + action_type + ' Data'); 
					$('#method_det_'+b).val("PUT"); 										
                    $('#do_det_id_'+b).val(data.id);
                    
                                                                            
                } else if (data.status == 'insert_failed') {
                    
                    error_noti('Gagal ' + action_type + ' Data, '+data.msg);                     
                    

                } else {
                    error_noti('Gagal ' + action_type + ' (Kesalahan Sistem)');
                    
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, '+message);
            }

        });            
 
    }  
} 

function delDet(b){

    var idDo = document.getElementById("id_do").value;
    var idDoDet = document.getElementById("do_det_id_"+b).value;          
    var deskripsi = document.getElementById("deskripsi_"+b).value;          

    if(idDoDet==""){        
        $('#row_'+b).remove();
    } else {
        Lobibox.confirm({
            iconClass: true,
            title: 'Delete Data',                        
            msg: 'Yakin Hapus Transaksi: "' + deskripsi + '" ?',            
            callback: function ($this, type, ev) {
                if(type=='yes'){
                    deleteProsesDet(idDoDet, b, idDo);   
                }        
            }
        });       
    }
} 

function deleteProsesDet(id, b, idJB) {
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/doDetail/" + id+"/" + idJB,
        dataType: 'JSON',
        beforeSend: function(){
            BeforeSend();
        },
        complete: function(){
            AfterSend();
        },
        success: function (data) {
            if (data.status == 'delete_successful') {
                success_noti('Data Berhasil Terhapus');
                $('#myTable tr.body').remove();
                refresh();                
            } else if (data.status == 'delete_successfulx') {
                success_noti('Data Berhasil Terhapus');
                $('#myTable tr.body').remove();
                disableClearEntry();
                refresh();
            } else if (data.status == 'delete_failed') {
                error_noti('Data Gagal Dihapus');
                refresh();
            } else {
                error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
                refresh();
            }
        },

        error: function (xmlhttprequest, textstatus, message) {
            error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
        }
    });
}

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
