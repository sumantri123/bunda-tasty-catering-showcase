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
		var idPenawaran = $('#idPenawaran').val();
		var urlx = "/addInvoice/"+kode+"/"+idPenawaran;
		otherPage(urlx)
		
    }); 
	
    function loadData() {
			var id = $('#idPenawaran').val();
            data_table = $('#example2').DataTable({
            processing: false,
            lengthChange: true,
            /* initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'], */
            ajax: {
                "url": "" + base_url + '/getDataJson/dokList/'+id,
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);                        
                }
            },
            columns: [
            {
                title: "Aksi",                
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, full, meta) {
                    var result = '';
                    result += '<td class="text-center">';
					result +=
                        '<button class="btn btn-success  btn-sm btn-print" title="Cetak Invoice"><i class="bx bx-printer me-0"></i></button>&nbsp;';
					result +=
                        '<button class="btn btn-primary  btn-sm btn-upload" title="Upload Faktur"><i class="bx bx-upload me-0"></i></button>&nbsp;';
					result +=
                        '<button class="btn btn-warning  btn-sm btn-edit" title="Edit Data"><i class="bx bx-edit me-0"></i></button>&nbsp;';					                   
					result +=
                        '<button class="btn btn-danger  btn-sm btn-delete" title="Hapus Data"><i class="bx bx-trash me-0"></i></button>';
                    result += '</td>';
                    return result;
                }
            },{
                title: "Nomor Invoice",
                data: "invoice_nomor",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Tanggal",
                data: "invoce_tgl",                
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Due Date",
                data: "invoice_due_date",                
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "PO Nomor",
                data: "invoice_po_nomor",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Pajak (%)",
                data: "invoice_pajak_persen",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Total Sblm Pajak",                
                visible: true,
				data: "total",
                sortable: true,
                class: "text-right",
				render: function (data, type, row, meta) {
					
					if((data==null) || (data==0)) {
						var result = 0;                    
					} else {
						
						var result = convertToRupiahNoRp(data);                    
					}
                    
                    return result;
                }
            },{
                title: "Pajak",                
                visible: true,
				data: "pajak",
                sortable: true,
                class: "text-right",
				render: function (data, type, full, meta) {
                    if((data==null) || (data==0)) {
						var result = 0;                    
					} else {
						
						var result = convertToRupiahNoRp(data);                    
					}                    
                    return result;
                }
            },{
                title: "Discount",                
                visible: true,
				data: "invoice_nominal",
                sortable: true,
                class: "text-right",
				render: function (data, type, full, meta) {
                    if((data==null) || (data==0)) {
						var result = 0;                    
					} else {
						
						var result = convertToRupiahNoRp(data);                    
					}                    
                    return result;
                }
            },{
                title: "Total",                
                visible: true,				
                sortable: true,
                class: "text-right",
				render: function (data, type, row, meta) {

					var result = convertToRupiahNoRp(row.total - row.invoice_nominal + row.pajak);

                    return result;
                }
            }],

            "drawCallback": function (settings) {				
				
				$('.btn-print').on('click', function () {                      
					var data = data_table.row($(this).parents('tr')).data();	
					window.open(base_url+"/cetakInvoice/"+data.invoice_id, '_blank', 'left=0,top=0,width=1000,height=700,status=0');					
					
				});

				$('.btn-upload').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();					
					var kode = $('#kode').val();
					var urlx = "/addDokFaktur/"+kode+"/"+data.invoice_id+"/"+data.id_penawaran;
					
					otherPage(urlx)					
                                    
                });
				

                $('.btn-edit').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();                    
					var kode = $('#kode').val();
					var urlx = "/editInvoice/"+kode+"/"+data.invoice_id+"/"+data.id_penawaran;

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
										msg: 'Yakin Hapus Dokumen"' + data.invoice_nomor + '"?',
										callback: function ($this, type, ev) {
											if(type=='yes'){
												deleteProses(data.invoice_id);
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
            url: "" + base_url + "/delete/invoice/" + id,
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