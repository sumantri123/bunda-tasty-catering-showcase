var data_table;

$(document).ready(function () {
	
    loadData();    
	
});    
	
	$('button#tambah').on('click', function () {
		var idPesan = $('#idPesan').val();

		clearModal();         
		$('#modal_label').text('Form Tambah Data');
		$('#method_field').val("POST");
		$('#id').val(idPesan);		
		$(".modal-form").modal('show');
		
    }); 
			
	$('INPUT[type="file"]').change(function () {
		
		var ext = this.value.match(/\.(.+)$/)[1];

		if(this.files[0].size > 5000000) {            

			error_noti('Please upload file less than 5MB. Thanks!!');            
			$(this).val('');

		} else {

			switch (ext) {
				case 'pdf':        				
					$('#btn_simpan').attr('disabled', false);
					break;
				default:
					error_noti('File Yang Diperbolehkan Hanya Extension pdf');            
					this.value = '';
			}
			
		  }    
	});
	
	$('#data_form').submit(function(e) {
		e.preventDefault();
		
		var method = $('#method_field').val();
		var action_url = "" + base_url + "/saveBP";
		var action_type = "Tambah";		
			
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
			
			var id = $('#idPesan').val();
            data_table = $('#example2').DataTable({
            processing: true,
            lengthChange: true,
            /* initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'], */
            ajax: {
                "url": "" + base_url + '/getDataJson/dokBP/'+id,
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);                        
                }
            },
            columns: [
            {
                title: "Aksi",
                data: "	po_id",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, full, meta) {
                    var result = '';
                    result += '<td class="text-center">';					
					result +=
                        '<button class="btn btn-primary  btn-sm btn-upload" title="Upload Bukti Pembayaran"><i class="bx bx-search me-0"></i></button>&nbsp;';                    
                    result +=
                        '<button class="btn btn-danger  btn-sm btn-delete" title="Hapus Data"><i class="bx bx-trash me-0"></i></button>';
                    result += '</td>';
                    return result;
                }
            },{
                title: "File Name",
                data: "file_name_ori",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Size",
                data: "file_size",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Tgl Upload",
                data: "dt_record",                
                visible: true,
                sortable: true,
                class: ""
            }],

            "drawCallback": function (settings) {
				$('.btn-upload').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();					
					viewFile(data.file_path, data.file_name, data.file_exe);
                                    
                });
				
               
                $('.btn-delete').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data(); 					
                    Lobibox.confirm({
						iconClass: true,
						title: 'Delete Data',                        
						msg: 'Yakin Hapus Dokumen"' + data.file_name_ori + '"?',
						callback: function ($this, type, ev) {
							if(type=='yes'){
								deleteProses(data.po_id);
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
            url: "" + base_url + "/delete/faktur/" + id,
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