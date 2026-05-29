var data_table;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function () {    

    loadData();        

    $('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    });
    
});    

function insertUpdateProses() {

    var form = $('#form');
    if (form.valid() == true) {
        
        var method = $('#method_field').val();
       // var action_url = "" + base_url + "/kelas";            
        var action_type = "Tambah";
        if (method === "PUT") {
            action_url = "" + base_url + "/reset_password/" + $('#id_user').val();
            action_type = "Ubah";
        }

        $.ajax({
            type: 'POST',
            url: action_url,
            dataType: 'JSON',
            data: form.serialize(),
            
            success: function (data) {
                if (data.status == 'insert_successful') {
                    success_noti('Berhasil ' + action_type + ' Data');
                    $('.modal-form').modal('toggle');            
                    clearModal();                                  
                    data_table.ajax.reload(null, false);
                } else if (data.status == 'insert_failed') {
                    
                    error_noti('Gagal ' + action_type + ' Data, ' +data.msg);                    
                    var errors = data.error;                                                                                       
                    errorValidationLaravel(errors, '#error-validation');


                } else {
                    error_noti('Gagal ' + action_type + ' (Kesalahan Sistem)');
                }
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, '+message);
            }

        });            
        
    } else {                        
        error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
    }
}

function loadData() {
    
        var idUser = document.getElementById("id_user").value;            
        
        data_table = $('#example2').DataTable({
        processing: false,
		destroy: true,
        lengthMenu: [ [200, 250, 300, -1], [200, 250, 300, "All"] ],
        ajax: {
            "url": "" + base_url + '/getDataJson/data_hak_akses/'+idUser,
            'type': 'GET',			
            'dataType': 'JSON',
            'error': function (xhr, textStatus, ThrownException) {
                error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
            }
        },
        columns: [        
		{
            title: "Akses Status",
            visible: true,
			width: "10%",
            sortable: true,
            class: "",
            render: function (data, type, row) {
				var result = '';				
				if (row.submenu_det_id === "null" || row.submenu_det_id === "" || row.submenu_det_id === null) {
					result +=
						'<button class="btn btn-outline-danger btn-sm px-2 ms-2 btn-akses" > <i class="fa fa-times-circle"></i> No Akses</button>&nbsp;';
				} else {
					result +=
						'<button class="btn btn-outline-success btn-sm px-2 ms-2 btn-noakses"> <i class="fa fa-check-circle"></i> Akses</button>&nbsp;';
				}
				
				return result; 
            } 
        },
        {
            title: "Nama Menu",            
            visible: true,
            sortable: true,
            class: "",
			render: function (data, type, row) {
				return row.submenu_nama_alias;                    
			}
        }],

        "drawCallback": function (settings) {
			$('.btn-akses').on('click', function () {                    
				var data = data_table.row($(this).parents('tr')).data();
				updateStatus(btoa(data.submenu_id+"-"+(idUser)),'y','/addMenuUser');					
			});
			
			 $('.btn-noakses').on('click', function () {                    
				var data = data_table.row($(this).parents('tr')).data();
				
				deleteProses(btoa(data.submenu_det_id),'/deleteMenuUser/');					
			});			
                 
        }
	});            
}    


function deleteProses(id,route) {
		
	var urlx = "" + base_url + route + id;
	
	$.ajax({
		type: 'GET',
		url: urlx,
		dataType: 'JSON',            

		success: function (data) {
			if (data.status == 'delete_successful') {
				success_noti('Data Berhasil Terhapus');
				data_table.ajax.reload(null, false);
			} else if (data.status == 'delete_failed') {
				error_noti('Data Gagal Dihapus');
			} else {
				error_noti('Data Gagal Dihapus (Kesalahan Sistem)');
			}
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman')
		}
	});
}

function updateStatus(id,status,route) {			
	var action_url = "" + base_url + route +"/"+ id+"/"+status;
	
	$.ajax({
		type:'GET',
		url: action_url,
		dataType: 'JSON',            
		success: function (data) {								
			data_table.ajax.reload(null, false);				
			if(status==='y'){
				success_noti(data.msg);   										
			} else {
				warning_noti(data.msg);
			}
		}, 
		error: function (jqXHR, exception) {
			var msg = '';
			if (jqXHR.status === 0) {
				msg = 'Not connect.\n Verify Network.';
			} else if (jqXHR.status == 404) {
				msg = 'Requested page not found. [404]';
			} else if (jqXHR.status == 500) {
				msg = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msg = 'Requested JSON parse failed.';
			} else if (exception === 'timeout') {
				msg = 'Time out error.';
			} else if (exception === 'abort') {
				msg = 'Ajax request aborted.';
			} else {
				msg = 'Uncaught Error.\n' + jqXHR.responseText;
			}
			error_noti(msg);
		}
	});
}


var validator = $('#form').validate({

    rules: {
        nama_lembaga: {required: true}, 
        alamat: {required: true},
        domain: {required: true},        
        nama_bank: {required: true},
        pass_admin: {required: true} 
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
