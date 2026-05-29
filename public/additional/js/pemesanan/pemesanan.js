var data_table;

$(document).ready(function () {
	
    loadData();    
	
});    
	
	function otherPage(urlx){
		
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		
		$.ajax({
			url: "" + base_url + urlx,
			type: 'get',
			cache: false,
			data: {
				_token: CSRF_TOKEN,                
			},
			beforeSend: function (){
				localStorage.removeItem("menu");
			},				
			success: function (data) {
				$('#example2').DataTable().destroy();								
				$('.isiContent').html(data.html);
				localStorage.setItem("menu", urlx);					
			},
	
			error: function (xhr, status, error, xmlhttprequest, textstatus, message) {	
				error_noti(xmlhttprequest+"/"+textstatus+"/"+message);
			}
		});		
	}
	
	$('button#tambah').on('click', function () {
		
		var kode = $('#kode').val();
		var id = $('#idPesan').val();
		
		var urlx = "/addPemesanan/"+kode+"/"+id;
		otherPage(urlx)
		
    }); 
			
			
    function loadData() {
			
			var id = $('#idPesan').val();
			//var id = 0;

            data_table = $('#example2').DataTable({
            processing: false,
            lengthChange: true,
            /* initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'], */
            ajax: {
                "url": "" + base_url + '/getDataJson/Pemesanan/'+id,
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);                        
                }
            },
            columns: [
            {
                title: "Aksi",
                data: "pesan_id",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, full, meta) {
                    var result = '';
                    result += '<td class="text-center">';
					result +=
						'<button class="btn btn-primary  btn-sm btn-upload" title="Upload Bukti Pembayaran"><i class="bx bx-upload me-0"></i></button>&nbsp;';										
                    result +=
                        '<button class="btn btn-warning btn-sm btn-edit" title="Edit Data"><i class="bx bx-edit me-0"></i></button>&nbsp;';                    
                    result +=
                        '<button class="btn btn-danger  btn-sm btn-delete" title="Hapus Data"><i class="bx bx-trash me-0"></i></button>';
                    result += '</td>';
                    return result;
                }
            },{
                title: "Supplier ",
                data: "supplier_nama",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Tanggal",
                data: "pesan_tgl",                
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Perihal",
                data: "pesan_hal",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Qty",
                data: "total_qty",
                visible: true,
                sortable: true,
                class: ""				
            }, {
                title: "Harga",
                data: "total_harga",
                visible: true,
                sortable: true,
                class: "text-end",
				render: function (data, type, full, meta) {
                    //var result = convertToRupiahNoRp(data);                    
					var result = data;                    
                    return result;
                }
            }, {
                title: "Total",
                data: "grand_total",
                visible: true,
                sortable: true,
                class: "text-end",
				render: function (data, type, full, meta) {
                    //var result = convertToRupiahNoRp(data);                    
					var result = data;                    
                    return result;
                }
            }],

            "drawCallback": function (settings) {
				
				$('.btn-upload').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();					
					var kode = $('#kode').val();
					var urlx = "/addDokPendukung/"+kode+"/"+data.pesan_id;
					
					otherPage(urlx)					
                                    
                });

                $('.btn-edit').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();                    
					var kode = $('#kode').val();
					var urlx = "/addPemesanan/"+kode+"/"+data.pesan_id+"/"+data.id_penawaran+"/"+data.jurnal_bagian_id;

					otherPage(urlx)					
                                    
                                                                 
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
                                        msg: 'Yakin Hapus Pemesanan"' + data.pesan_hal + '"?',
                                        callback: function ($this, type, ev) {
                                            if(type=='yes'){
                                                deleteProses(data.pesan_id);
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
            url: "" + base_url + "/delete/Pemesanan/" + id,
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