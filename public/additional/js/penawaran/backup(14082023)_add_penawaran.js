var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () { 
	
	tinymce.init({
		selector: '#penawaran_header',
		placeholder: "Kepada Yth. ",
		forced_root_block : 'div',
		setup: function (editor) {
			editor.on('change', function () {
				tinymce.triggerSave();
			});
		},		
	});
	tinymce.init({  
		selector: '#penawaran_content',
		placeholder: "Demikian penawaran ini kami sampaikan, semoga dapat memenuhi kebutuhan anda dan kami sangat senang untuk membahas secara rinci jika ada pertanyaan. Terima kasih banyak atas perhatian Anda. ",
		forced_root_block : 'div',
		setup: function (editor) {
			editor.on('change', function () {
				tinymce.triggerSave();
			});
		}
	});
    
    totalDebetKredit();    
	addRow();        
	
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

function getval2(sel){                
    var selected = sel.value;  
  
	$('#pejabat').val( $("#pejabat_id option:selected").attr("nama"));  	                    
  
}

function refresh() {
	
	var idJb = $('#id_jb').val();		
	var kode = $('#bagian').val();
	$('#search').attr('readOnly', true);
	$('#method_field').val("PUT");    
	
	$.ajax({
		type: 'post',
		url: "" + base_url + "/search/penawaranDet",
		dataType: 'JSON',
		data: {
			_token: CSRF_TOKEN,
			kode: kode,
			idJb: idJb
		},
		beforeSend: function(){
			BeforeSend();
		},
		complete: function(){
			AfterSend();
		},
		success: function (data) {
			if (data.status == 'oke') {
				
				$('#no_bukti').val(data.penawaranNo);
				$('#id_jb').val(data.penawaranId);
				$('#tgl').val(data.penawaranTgl);
				$('#company_id').val(data.idCustomer).trigger('change'); 
				$('#company_id').select2({ width: "100%" });
				$('#pejabat_id').val(data.idPejabat).trigger('change'); 
				$('#pejabat_id').select2({ width: "100%" });
				$('#company').val(data.penawaranCompany);
				$('#perihal').val(data.penawaranHal);
				$('#penawaran_header').val(data.penawaranHeader);
				$('#penawaran_content').val(data.penawaranContent);
				$('#penawaran_ttd').val(data.penawaranTtd);
				$('#pejabat').val(data.penawaranPejabat);
				$('#telp').val(data.penawaranHp);
				$('#pajak_global').val(data.penawaranPajak);
				$('#jumlahData').val(data.jumlahData);				

				var totDetData = data.jumlahData;
				var b;
				var content;
				for (b = 0; b < totDetData; b++) {						                        

					content += "<tr class='body' id='row_"+b+"'>"
					content += "<td >"
					content += "<input type='text' class='form-control form-control-sm' id='deskripsi_"+b+"' value='"+data.data[b].penawaran_deskripsi+"' name='deskripsi[]'>"						
					content += "</td>"
					content += "<td class='text-left'>"
					content += "<input type='hidden' class='form-control form-control-sm' id='kode_perkiraan"+b+"' value='"+(data.data[b].kode_perkiraan)+"' name='nama_perkiraan[]' readonly>"
                    content += "<input type='hidden' class='form-control form-control-sm' id='id_perkiraan_"+b+"' value='"+(data.data[b].id_perkiraan)+"' name='id_perkiraan[]' readonly>"
					content += "<input type='text' class='form-control form-control-sm' id='qty_"+b+"' value='"+data.data[b].qty+"' name='qty[]' onkeyup='qtyTotal("+b+", this)'>"                        
					content += "<input type='hidden' class='form-control form-control-sm' id='jurbag_det_id_"+b+"' value='"+data.data[b].penawaran_detail_id+"' name='jurbag_det_id[]' readonly>"
					content += "<input type='hidden' class='form-control form-control-sm' id='method_det_"+b+"' value='PUT' name='method_det[]' readonly>"
					content += "</td>"
					content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='harga_"+b+"' value='"+(data.data[b].harga)+"' onkeyup='format_rupiah("+b+", this)' name='harga[]'></td>"
//					content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='pajak_"+b+"' value='"+convertToRupiahNoRp(data.data[b].pajak_nominal)+"' name='pajak[]' readOnly></td>"						
					content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='total_"+b+"' value='"+convertToRupiahNoRp(data.data[b].total)+"' name='total[]' readOnly></td>"
					content += "<td >"
					content += "<div class='ms-auto d-flex align-items-center'>"
					content += "<button type='button' class='btn btn-success  px-2 ms-2' onclick='saveDet("+b+")'><i class='bx bxs-save me-0'></i></button>"
					content += "<button type='button' class='btn btn-danger  px-2 ms-2' onclick='delDet("+b+")' ><i class='bx bxs-trash me-0'></i></button>"
					content += "</div>"
					content += "</td>"
					content += "</tr>";
				}
				 $("#myTable > tbody").append(content);
				 totalDebetKredit();
			
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
    totalDebetKredit();	    
    $('#myTable tr.body').remove();
    $('#method_field').val("POST");  
    $('#search').attr('readOnly', true);       
    $('#searchGrup').hide("slow"); 
    $('#no_bukti').attr('readOnly', true); 
	//$('#addSub').attr('disabled','disabled');	
    
});


function totalDebetKredit(){
    var id = document.getElementById("id_jb").value;
    
    if(id!=""){
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/total/penawaranDetail/" + id,
            dataType: 'JSON',
            // beforeSend: function () {
            //     sweetAlertLoading('Memproses');
            // },
            success: function (data) {
                if (data.status == 'oke') {
                    //sweetAlertDefault('<b>Data Berhasil Terhapus</b>', 'success', 2000 );                                        
                    $('#tot_db').val(convertToRupiahNoRp(data.totDebet));
                    $('#tot_kr').val(convertToRupiahNoRp(data.totKredit));                                        
//					$('#tot_pajak').val(convertToRupiahNoRp(data.totPajak));                                        
					$('#jumlahData').val(data.jumlahData);                                        
                    
                } else {
                    //error_noti('Data Tidak Tersedia');     
                }
            },
    
            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
            }
        });
    } else {
        $('#tot_db').val("");
        $('#tot_kr').val("");
    }       
}

function format_rupiah(b, y){
    var query = y.value;
    var qty = $('#qty_'+b).val();
	var pajak = $('#pajak_global').val();

	var total = (qty * parseInt(query));
	var pajak_nominal = (total * pajak) / 100;
	
	$('#total_'+b).val(convertToRupiahNoRp(total));   
	$('#pajak_'+b).val(convertToRupiahNoRp(pajak_nominal));   
	
}

function qtyTotal(b, y){
    var query = y.value;
    var harga = $('#harga_'+b).val();
	var pajak = $('#pajak_global').val();
	
	var total = (query * parseInt(harga));
	var pajak_nominal = (total * pajak) / 100;
	
	$('#total_'+b).val(convertToRupiahNoRp(total));   
	$('#pajak_'+b).val(convertToRupiahNoRp(pajak_nominal));   
}

function autoComplete(b, y){
    var query = y.value;   
	
    $( "#deskripsi_"+b ).autocomplete({
        source: function( request, response ) {
            // Fetch data
            $.ajax({
            url: "" + base_url + "/getIdPerCatering",
            type: 'post',
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,                
                search: query
            },
            success: function( data ) {
                response( data );
            }
            });
        },
        select: function (event, ui) {           
            if(ui.item.value == "0"){
                error_noti('Kode Tidak Ditemukan'); 
                
            } else {
                var value = ui.item.label;
                var splitValue = value.split("-");               
                $('#deskripsi_'+b).val(splitValue[1]); // display the selected text
                $('#kode_perkiraan'+b).val(splitValue[0]); // display the selected text
                $('#id_perkiraan_'+b).val(ui.item.value); // save selected id to input
                return false;
            }               
        }
    });
}    

function addRow(){	
    var a = -1;
    var b = a++;
    
    var content = "<table id='myTable' border='1' class='classTable'>"
        content += "<thead>"
        content += "<tr>"
        content += "<th width='33%' class='text-center'><b>Deskripsi</b></th>"
        content += "<th width='8%' class='text-center'><b>Qty</b></th>"
        content += "<th width='17%' class='text-center'><b>Harga</b></th>"
//		content += "<th width='15%' class='text-center'><b>Pajak</b></th>"				
        content += "<th width='17%' class='text-center'><b>Total Harga</b></th>"
        content += "<th width='10%' class='text-center'><button type='button' class='btn btn-light px-2 ms-2' id='addSub' ><i class='bx bxs-message-square-add me-0'></i></button></th>"
        content += "</tr>"
        content += "</thead>"
        content += "<tbody>"        
        content += "</tbody>"
        content += "<tfoot>"
        content += "<tr>"        
        content += "<td colspan='2'><b>TOTAL</b></td>"                
        content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='tot_db' value='' name='tot_db' readonly></td>"                
//		content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='tot_pajak' value='' name='tot_pajak' readonly></td>"                
        content += "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='tot_kr' value='' name='tot_kr' readonly></td>"                
        content += "<td >"        
        content += "</td>"                
        content += "</tr>"        
        content += "</tfoot>";
    content += "</table>"
    $('#show_table').append(content);
            
    document.onkeydown = function(){
        if(window.event && window.event.keyCode == 113) {                                
			var jumlahData = $('#jumlahData').val();			
			var b = (jumlahData !== "") ? jumlahData++ : a++ ;            

            $("#myTable > tbody").append(                    
                "<tr class='body' id='row_"+b+"'>"+
                    "<td >"+
                    "<input type='text' class='form-control form-control-sm' id='deskripsi_"+b+"' onkeyup='autoComplete("+b+", this)' value='' name='deskripsi[]'>"+                        
                    "</td>"+
                    "<td class='text-left'>"+
					"<input type='hidden' class='form-control form-control-sm' id='kode_perkiraan"+b+"' value='' name='nama_perkiraan[]' readonly>"+
                    "<input type='hidden' class='form-control form-control-sm' id='id_perkiraan_"+b+"' value='' name='id_perkiraan[]' readonly>"+
                    "<input type='text' class='form-control form-control-sm' id='qty_"+b+"' value='' name='qty[]' onkeyup='qtyTotal("+b+", this)'>"+                    
                    "<input type='hidden' class='form-control form-control-sm' id='jurbag_det_id_"+b+"' value='' name='jurbag_det_id[]' readonly>"+
					"<input type='hidden' class='form-control form-control-sm' id='method_det_"+b+"' value='POST' name='method_det[]' readonly>"+
                    "</td>"+
                    "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='harga_"+b+"' onkeyup='format_rupiah("+b+", this)' value='' name='harga[]'></td>"+
//                    "<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='pajak_"+b+"' readonly value='' name='pajak[]'></td>"+					
					"<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='total_"+b+"' readonly value='' name='total[]'></td>"+
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
				"<input type='text' class='form-control form-control-sm' id='deskripsi_"+b+"' onkeyup='autoComplete("+b+", this)' value='' name='deskripsi[]'>"+                        
				"</td>"+
				"<td class='text-left'>"+
				"<input type='hidden' class='form-control form-control-sm' id='kode_perkiraan"+b+"' value='' name='nama_perkiraan[]' readonly>"+
				"<input type='hidden' class='form-control form-control-sm' id='id_perkiraan_"+b+"' value='' name='id_perkiraan[]' readonly>"+
				"<input type='text' class='form-control form-control-sm' id='qty_"+b+"' value='' name='qty[]'>"+				
				"<input type='hidden' class='form-control form-control-sm' id='jurbag_det_id_"+b+"' value='' name='jurbag_det_id[]' readonly>"+
				"<input type='hidden' class='form-control form-control-sm' id='method_det_"+b+"' value='POST' name='method_det[]' readonly>"+
				"</td>"+
				"<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='harga_"+b+"' onkeyup='format_rupiah("+b+", this)' value='' name='harga[]'></td>"+
//				"<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='pajak_"+b+"' readonly value='' name='pajak[]'></td>"+				
				"<td ><input type='text' class='form-control form-control-sm' style='text-align:right' id='total_"+b+"' readonly value='' name='total[]'></td>"+
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
	var action_url = "" + base_url + "/addPenawaran";
	var action_type = "Tambah";
	if (method === "PUT") {
		action_url = "" + base_url + "/addPenawaran/" + $('#id_jb').val();
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
                    $('#id_jb').val(data.id);
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
        company: {required: true},                                              
		perihal: {required: true},                                              
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

    var idPenawaran = document.getElementById("id_jb").value;    
    var deskripsi = document.getElementById("deskripsi_"+b).value;                
	var idPenawaranDet = document.getElementById("jurbag_det_id_"+b).value;
	var qty = document.getElementById("qty_"+b).value;                
    var harga = document.getElementById("harga_"+b).value;   
	var idPerkiraan = document.getElementById("id_perkiraan_"+b).value;   
    var total = document.getElementById("total_"+b).value;
	//var pajak = document.getElementById("pajak_global").value;	
	var pajak = 0;	
    
        
    if(idPenawaran==""){
        info_noti('Anda Belum Membuat No. Bukti');	                
        
    } else {
		
		var method = $('#method_det_'+b).val();
		var action_url = "" + base_url + "/penawaranDet";  
        var action_type = "Tambah";   
		if (method === "PUT") {			
			action_url = "" + base_url + "/penawaranDet/" + idPenawaranDet;
			action_type = "Ubah";
		}

        $.ajax({
            type: method,
            url: action_url,
            dataType: 'JSON',
            data: {_token: CSRF_TOKEN, idJB:idPenawaran, pajak:pajak, id_perkiraan:idPerkiraan, qty:qty, harga:harga, total:total, deskripsi:deskripsi},            
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
                    $('#jurbag_det_id_'+b).val(data.id);
                    totalDebetKredit();
                                                                            
                } else if (data.status == 'insert_failed') {
                    
                    error_noti('Gagal ' + action_type + ' Data, '+data.msg);                     
                    totalDebetKredit();

                } else {
                    error_noti('Gagal ' + action_type + ' (Kesalahan Sistem)');
                    totalDebetKredit();
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, '+message);
            }

        });            
 
    }  
} 

function delDet(b){

    var idJurnalBagian = document.getElementById("id_jb").value;
    var idJurnalBagianDet = document.getElementById("jurbag_det_id_"+b).value;          
    var deskripsi = document.getElementById("deskripsi_"+b).value;          

    if(idJurnalBagianDet==""){        
        $('#row_'+b).remove();
    } else {
        Lobibox.confirm({
            iconClass: true,
            title: 'Delete Data',                        
            msg: 'Yakin Hapus Transaksi: "' + deskripsi + '" ?',            
            callback: function ($this, type, ev) {
                if(type=='yes'){
                    deleteProsesDet(idJurnalBagianDet, b, idJurnalBagian);   
                }        
            }
        });       
    }
} 

function deleteProsesDet(id, b, idJB) {
    $.ajax({
        type: 'GET',
        url: "" + base_url + "/delete/penawaranDetail/" + id+"/" + idJB,
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
