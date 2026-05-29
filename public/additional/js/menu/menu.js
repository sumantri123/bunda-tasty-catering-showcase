var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var data_table;

$(document).ready(function () {
    
    loadData();

    $('button#tambah').on('click', function () {        
         clearModal();         
         $('#modal_label').text('Form Tambah Data');
         $('#method_field').val("POST");
         $(".modal-form").modal('show');
    });

    $('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    });    
        
});

    function loadData() {

        data_table  = $('#example2').DataTable({            
            processing: true,
            lengthChange: true,
            /* initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'], */
			pageLength: 25,		
            ajax: {
                "url": "" + base_url + '/getDataJson/menu',
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {                    
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                }
            },

            columns: [
            {
                title: "Aksi",
                data: "menu_id",
                width: "10%",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, row, meta) {
                    var result = '';
                    result += '<td class="text-center">';
                    result +=
                        '<button class="btn btn-warning btn-sm btn-edit"> <i class="bx bx-edit me-0"></i> </button>&nbsp;';
					result +=
						'<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-trash me-0"></i> </button>';
					/* if(row.id_customer == null || row.id_customer == "null" || row.id_customer == ""){
						result +=
							'<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-trash"></i> </button>';
					} */ 

                    result += '</td>';
                    return result;
                }
            },
			{
                title: "Nama Menu ",
                data: "menu_nama",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Kategori ",
                data: "nama_perkiraan",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Harga",
                data: "menu_harga",
                width: "25%",
                visible: true,
                sortable: true,
                class: "text-end",
				render: function (data, type, row, meta) {
                    var result = '';
					if(data == null){
						result += 0;                     
					} else {
						result += convertToRupiahNoRp(data);                     
					}
                    return result;
                }
            }, {
                title: "status",
                data: "menu_status",
                width: "15%",
                visible: true,
                sortable: true,
                class: "",
				render: function (data, type, row, meta) {
                    var result = '';
					if(data == 'y'){
						result += '<div class="form-check form-switch">';
						result += '<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="nonAktif('+row.menu_id+')" checked>';
						result += '<label class="form-check-label" for="flexSwitchCheckChecked">Aktif</label>';
						result += '</div>';
					} else {
						result += '<div class="form-check form-switch">';
						result += '<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="Aktif('+row.menu_id+')">';
						result += '	<label class="form-check-label" for="flexSwitchCheckChecked">Non Aktif</label>';
						result += '</div>';
					}

                    return result;
                }
            }],

            "drawCallback": function (settings) {
                $('.btn-edit').on('click', function () {
                    clearModal();
                    var data = data_table.row($(this).parents('tr')).data();
                    $('#id').val(data.menu_id);
                    $('#status').val(data.menu_status);
					$('#nama').val(data.menu_nama);					
                    $('#harga').val(data.menu_harga);						
					$('#kat_menu').val(data.id_perkiraan);						

                    $('#modal_label').text('Form Ubah');
                    $('#method_field').val("PUT");
                    $(".modal-form").modal('show');
                });

                $('.btn-delete').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();
                    Lobibox.confirm({
                        iconClass: true,
                        title: 'Delete Data',                        
                        msg: 'Yakin Hapus Data "' + data.menu_nama + '"?',
                        callback: function ($this, type, ev) {
                            if(type=='yes'){
                                deleteProses(data.menu_id);
                            }        
                        }
                    }); 
                   
                });
            }
		});            
    }

    function deleteProses(id) {
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/delete/menu/" + id,
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

    function insertUpdateProses() {

        var form = $('#form_crud');
        if (form.valid() == true) {
            
            var method = $('#method_field').val();
            var action_url = "" + base_url + "/menu";            
            var action_type = "Tambah";
            if (method === "PUT") {
                action_url = "" + base_url + "/menu/" + $('#id').val();
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
                        data_table.ajax.reload(null, false);
                    } else if (data.status == 'insert_failed') {
                        error_noti('Gagal ' + action_type + ' Data'); 

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
            //sweetAlertLoading('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah',1000);
        } else {                        
            error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
        }
    }

	function nonAktif(id){
	
		$.ajax({
			type: 'post',
			url: "" + base_url + "/update/NonAktifMenu",
			dataType: 'JSON',
			data: {
				_token: CSRF_TOKEN,
				'id': id
			},
			success: function (data) {
				
				if (data.status == 'insert_successful') {					
					success_noti('Data berhasil diupdate');
				}else if(data.status == 'insert_failed'){
					error_noti('update Failed');
				}else {
					error_noti('Error Koneksi');                     
				}
			},

			error: function (xmlhttprequest, textstatus, message) {
				error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
			}
		});
	}


	function Aktif(id){
		
		$.ajax({
			type: 'post',
			url: "" + base_url + "/update/AktifMenu",
			dataType: 'JSON',
			data: {
				_token: CSRF_TOKEN,
				'id': id
			},
			success: function (data) {
				
				if (data.status == 'insert_successful') {
					success_noti('Data berhasil diupdate');
				}else if(data.status == 'insert_failed'){
					error_noti('update Failed');
				}else {
					error_noti('Error Koneksi');                     
				}
			},

			error: function (xmlhttprequest, textstatus, message) {
				error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman');
			}
		});
	}

    var validator = $('#form_crud').validate({

        rules: {
            jenis: {
                required: true
            },
			akun: {
                required: true
            },
        },

        highlight: function (element, errorClass, validClass, error) {
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