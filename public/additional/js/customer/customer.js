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

	$('button#tes').on('click', function () {        
        
		$.ajax({
            type: 'get',
            url: "" + base_url + "/send/customer/1",
            dataType: 'JSON',            

            success: function (data) {
	            success_noti('Data Berhasil Dikirim');
            },

            error: function (xmlhttprequest, textstatus, message) {
                error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman')
            }
        });
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
            ajax: {
                "url": "" + base_url + '/getDataJson/customer',
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
					if(row.id_customer == null || row.id_customer == "null" || row.id_customer == ""){
						result +=
							'<button class="btn btn-danger  btn-sm btn-delete"> <i class="bx bx-trash"></i> </button>';
					} 

                    result += '</td>';
                    return result;
                }
            },
			{
                title: "Customer ",
                data: "customer_nama",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Alamat",
                data: "customer_alamat",
                width: "25%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Telp",
                data: "customer_telp",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Owner",
                data: "customer_pejabat",
                width: "20%",
                visible: true,
                sortable: true,
                class: ""
            }],

            "drawCallback": function (settings) {
                $('.btn-edit').on('click', function () {
                    clearModal();
                    var data = data_table.row($(this).parents('tr')).data();
                    $('#id').val(data.customer_id);
                    $('#nama').val(data.customer_nama);
					$('#alamat').val(data.customer_alamat);					
                    $('#telp').val(data.customer_telp);	
					$('#owner').val(data.customer_pejabat);				

                    $('#modal_label').text('Form Ubah');
                    $('#method_field').val("PUT");
                    $(".modal-form").modal('show');
                });

                $('.btn-delete').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();
                    Lobibox.confirm({
                        iconClass: true,
                        title: 'Delete Data',                        
                        msg: 'Yakin Hapus Data "' + data.customer_nama + '"?',
                        callback: function ($this, type, ev) {
                            if(type=='yes'){
                                deleteProses(data.customer_id);
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
            url: "" + base_url + "/delete/customer/" + id,
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

        var form = $('#form_edit_perkiraan');
        if (form.valid() == true) {
            
            var method = $('#method_field').val();
            var action_url = "" + base_url + "/customer";            
            var action_type = "Tambah";
            if (method === "PUT") {
                action_url = "" + base_url + "/customer/" + $('#id').val();
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



    var validator = $('#form_edit_perkiraan').validate({

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