var data_table;

$(document).ready(function () {
    
    loadData();

    $('button#tambah').on('click', function () {        
         clearModal();         
         $('#modal_label').text('Form Tambah Data');
         $('#method_field').val("POST");
         $(".modal-form").modal('show');
		 $('#edit_upload').hide();				
    });

    /* $('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    });  */   
        
});
	
	$('#delete_image').on('click', function () {		
	
		$( "#edit_upload" ).hide();
		$( "#new_upload" ).show();
		
    });
	
	$('INPUT[type="file"]').change(function () {
		
		var ext = this.value.match(/\.(.+)$/)[1];

		if(this.files[0].size > 5000000) {            

			error_noti('Please upload file less than 5MB. Thanks!!');            
			$(this).val('');

		} else {

			switch (ext) {
				case 'png':        				
					$('#btn_simpan').attr('disabled', false);
					break;
				default:
					error_noti('File Yang Diperbolehkan Hanya Extension png');            
					this.value = '';
			}
			
		  }    
	});
	
	$('#data_form').submit(function(e) {
		e.preventDefault();
		
		var method = $('#method_field').val();
		var action_url = "" + base_url + "/pejabat";
		var action_type = "Tambah";		
		if (method === "PUT") {
			action_url = "" + base_url + "/pejabat/" + $('#id').val();
			action_type = "Ubah";
		}
			
		var formData = new FormData(this);        
		var form = $('#data_form');
		
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
				success: (data) => {										
					success_noti('Data Berhasil Diupload');   										
					$('.modal-form').modal('toggle');
                    data_table.ajax.reload(null, false);
				},
				error: function (error) {					
					error_noti("Data Gagal Diupload");
				}
			});
		} else {
			
			error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
		}    
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
            ajax: {
                "url": "" + base_url + '/getDataJson/pejabat',
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {                    
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
                }
            },

            columns: [
            {
                title: "Aksi",
                data: "id",
                width: "15%",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, row, meta) {
                    var result = '';
                    result += '<td class="text-center">';
                    result +=
                        '<button class="btn btn-warning btn-sm btn-edit"> <i class="bx bx-edit"></i> </button>&nbsp;';
					if((row.pejabat_invoice == null || row.pejabat_invoice == "null" || row.pejabat_invoice == "") && (row.pejabat_penawaran == null || row.pejabat_penawaran == "null" || row.pejabat_penawaran == "")){
						result +=
							'<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-trash"></i> </button>';
					} 

                    result += '</td>';
                    return result;
                }
            },
			{
                title: "Nama Pejabat ",
                data: "pejabat_nama",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Jabatan",
                data: "pejabat_jabatan",
                width: "25%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Alamat",
                data: "pejabat_alamat",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Telp",
                data: "pejabat_telp",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            }],

            "drawCallback": function (settings) {
                $('.btn-edit').on('click', function () {
                    clearModal();
                    var data = data_table.row($(this).parents('tr')).data();
                    $('#id').val(data.pejabat_id);
                    $('#nama').val(data.pejabat_nama);
					$('#alamat').val(data.pejabat_alamat);					
                    $('#telp').val(data.pejabat_telp);	
					$('#owner').val(data.pejabat_jabatan);				
					$('#new_upload').hide();				
					$('#edit_upload').show();				
					$("#my_image").attr("src",base_url+"/"+data.pejabat_path+data.pejabat_name);

                    $('#modal_label').text('Form Ubah');
                    $('#method_field').val("PUT");
                    $(".modal-form").modal('show');
                });

                $('.btn-delete').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();
					var pass = $('#pass').val();
					Lobibox.prompt('text', //Any input type will be valid
                    {
                        title: 'Password Kewenangan',                        
                        attrs: { 
                            placeholder: "password",
                            type: 'password',
                        },
                        callback: function ($this, type, ev) {
                            if(type=='ok'){
                                if($this.getValue()===pass){
                                    Lobibox.confirm({
										iconClass: true,
										title: 'Delete Data',                        
										msg: 'Yakin Hapus Data "' + data.pejabat_nama + '"?',
										callback: function ($this, type, ev) {
											if(type=='yes'){
												deleteProses(data.pejabat_id);
											}        
										}
									});   
                                } else {                                    
                                    error_noti('Password Salah, Transaksi Batal');                                    
                                }        
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
            url: "" + base_url + "/delete/pejabat/" + id,
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

    /* function insertUpdateProses() {

        var form = $('#form_edit_perkiraan');
        if (form.valid() == true) {
            
            var method = $('#method_field').val();
            var action_url = "" + base_url + "/pejabat";            
            var action_type = "Tambah";
            if (method === "PUT") {
                action_url = "" + base_url + "/pejabat/" + $('#id').val();
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
            
        } else {                        
            error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
        }
    } */



    var validator = $('#data_form').validate({

        rules: {
            nama: {
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