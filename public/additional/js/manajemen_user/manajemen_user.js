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
			lengthMenu: [
				[25, 50, 150, -1],
				[25, 50, 150, 'All']
			],
            /* initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'], */
            ajax: {
                "url": "" + base_url + '/getDataJson/manajemenUser',
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {                    
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                }
            },

            columns: [
            {
                title: "Aksi",
                data: "user_id",
                width: "10%",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, row, meta) {
                    var result = '';

                    result += '<td class="text-center">';
					result += '<a data-href="/viewAkses/'+btoa(data)+'" title="Detail Akses" id="btnLihat" class="btn btn-primary btn-sm btn-search action"><i class="bx bx-search me-0"></i></a>&nbsp;';
                    result += '<button class="btn btn-warning btn-sm btn-edit"> <i class="bx bx-edit me-0"></i> </button>&nbsp;';
					result += '<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-trash me-0"></i> </button>';					

                    result += '</td>';
                    return result;
                }
            },{
                title: "Nama",
                data: "name",
                width: "15%",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Grup",
                data: "grup_kelas",
                width: "15%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Username Tiktok",                
                width: "15%",
				data: "username_tiktok",
                visible: true,
                sortable: true,
                class: "",				
            }, {
                title: "publish",                
                width: "15%",
				data: "user_status",
                visible: true,
                sortable: true,
                class: "",
				render: function (data, type, row, meta) {
                    var result = '';
					if(data == 'y'){
						result += '<div class="form-check form-switch">';
						result += '<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="nonAktif('+row.user_id+')" checked>';
						result += '<label class="form-check-label" for="flexSwitchCheckChecked">Aktif</label>';
						result += '</div>';
					} else {
						result += '<div class="form-check form-switch">';
						result += '<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onclick="Aktif('+row.user_id+')">';
						result += '	<label class="form-check-label" for="flexSwitchCheckChecked">Non Aktif</label>';
						result += '</div>';
					}

                    return result;
                }
            }],

            "drawCallback": function (settings) {
				
				$('.btn-search').on('click', function () {											
					var urlx = $(this).attr('data-href');		      
										
					$.ajax({
						url: "" + base_url + urlx,
						type: 'get',
						cache: false,
						data: {
							_token: CSRF_TOKEN,                
						},									
						success: function (data) {																				
							$('.isiContent').html(data);
						},
				
						error: function (xhr, status, error, xmlhttprequest, textstatus, message) {						
							alert(xmlhttprequest+"/"+textstatus+"/"+message);																						
						}
					});
				}); 
			
                $('.btn-edit').on('click', function () {
                    clearModal();
                    var data = data_table.row($(this).parents('tr')).data();
                    $('#id').val(data.user_id);
                    $('#name').val(data.name);
					$('#username').val(data.username);					
                    $('#password').val(data.password);						
					$('#username_tiktok').val(data.username_tiktok);						
					$('#email').val(data.email);	
					$("#kelas").val(data.kelas_id).trigger('change');					

                    $('#modal_label').text('Form Ubah');
                    $('#method_field').val("PUT");
                    $(".modal-form").modal('show');
                });

                $('.btn-delete').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();
                    Lobibox.confirm({
                        iconClass: true,
                        title: 'Delete Data',                        
                        msg: 'Yakin Hapus Data "' + data.name + '"?',
                        callback: function ($this, type, ev) {
                            if(type=='yes'){
                                deleteProses(data.user_id);
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
            url: "" + base_url + "/delete/manajemenUser/" + id,
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
            var action_url = "" + base_url + "/manajemenUser";            
            var action_type = "Tambah";
            if (method === "PUT") {
                action_url = "" + base_url + "/manajemenUser/" + $('#id').val();
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
			url: "" + base_url + "/update/NonAktifUser",
			dataType: 'JSON',
			data: {
				_token: CSRF_TOKEN,
				'id': id
			},
			success: function (data) {
				
				if (data.status == 'insert_successful') {					
					success_noti('Data berhasil diupdate');
					data_table.ajax.reload(null, false);
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
			url: "" + base_url + "/update/AktifUser",
			dataType: 'JSON',
			data: {
				_token: CSRF_TOKEN,
				'id': id
			},
			success: function (data) {
				
				if (data.status == 'insert_successful') {
					success_noti('Data berhasil diupdate');
					data_table.ajax.reload(null, false);
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