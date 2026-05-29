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
			
			
    function loadData() {
		
            data_table = $('#example2').DataTable({
            processing: false,
            lengthChange: true,			
            /* initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'], */
            ajax: {
                "url": "" + base_url + '/getDataJson/dokPenawaranInvoice',
                'type': 'GET',
                'dataType': 'JSON',
                'error': function (xhr, textStatus, ThrownException) {
                    error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);                        
                }
            },
            columns: [
            {
                title: "Generate Pembayaran",
                data: "penawaran_id",
                visible: true,
                sortable: false,
                class: "text-center",
                render: function (data, type, row, meta) {
                    var result = '';
                    result += '<td class="text-center">';
					if(row.total > 0){
						result +=
							'<button class="btn btn-primary  btn-sm btn-search" title="Cetak Penawaran"><i class="bx bx-search me-0"></i></button>&nbsp;';					                   
					} else {
						result +=
							'<button class="btn btn-success btn-sm btn-lunas" title="Generate Invoice Dari Penawaran"><i class="bx bx-cog me-0"></i>Lunas</button>&nbsp;';
						/* result +=
							'<button class="btn btn-primary btn-sm btn-dp" title="Generate Invoice Dari Penawaran"><i class="bx bx-cog me-0"></i>DP</button>&nbsp;'; */
						result +=
							'<button class="btn btn-warning btn-sm btn-blm-lunas" title="Generate Invoice Dari Penawaran"><i class="bx bx-cog me-0"></i>Belum Dibayar</button>&nbsp;';
						
					}
                    result += '</td>';
                    return result;
                }
            },{
                title: "Nomor Penawaran ",
                data: "penawaran_nomor",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Perusahaan",
                data: "penawaran_company",                
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Perihal",
                data: "penawaran_hal",
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Pembayaran",                
                visible: true,
				data: "tipe_pembayaran",
                sortable: true,
                class: "",
				render: function (data, type, row, meta) {
                    var result = '';
					if(data==1){
						result += '<span class="badge bg-success">Lunas</span>';
					} else if(data==2){
						result += '<span class="badge bg-primary">DP</span>';
					} else if(data==3){
						result += '<span class="badge bg-danger">Belum Dibayar</span>';
					} else {
						result += '<span class="badge bg-secondary">Belum Digenerate</span>';
					}
                    return result;
                }
            },{
                title: "Tanggal Penawaran",
                data: "penawaran_tgl",
                visible: true,
                sortable: true,
                class: ""
            }],

            "drawCallback": function (settings) {				
				
                $('.btn-search').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();                    
					var kode = $('#kode').val();
					var urlx = "/listInvoice/"+kode+"/"+data.penawaran_id;

					otherPage(urlx)					
                                    
                                                                 
                });
				
				/* $('.btn-generate').on('click', function () {
					
					var data = data_table.row($(this).parents('tr')).data(); 	
					Lobibox.confirm({
						iconClass: true,
						title: 'Generate Invoice',                        
						msg: 'Generate Invoice '+data.penawaran_company+' dari Penawaran "' + data.penawaran_nomor + '"?',
						callback: function ($this, type, ev) {
							if(type=='yes'){
								generateInvoice(data.penawaran_id);
							}        
						}
					}); 
                                                                 
                }); */

				$('.btn-lunas').on('click', function () {

					var data = data_table.row($(this).parents('tr')).data();					
                    generateInvoice(data.penawaran_id,"1-0");                                            
                });

				$('.btn-dp').on('click', function () {

					var data = data_table.row($(this).parents('tr')).data(); 						
					Lobibox.prompt('text', //Any input type will be valid
                    {
                        title: 'DP Pembayaran',                        
                        attrs: { 
                            placeholder: "Entry DP Pembayaran",
                            type: 'number',
                        },
                        callback: function ($this, type, ev) {
                            if(type=='ok'){
                                if($this.getValue()){					
									var dp = "2-"+$this.getValue();
                                    generateInvoice(data.penawaran_id,dp);   
                                } else {                                    
                                    error_noti('Password Salah, Transaksi Batal');                                    
                                }        
                            }        
                        }
                    });                                             					
                                                                 
                });

				$('.btn-blm-lunas').on('click', function () {

					var data = data_table.row($(this).parents('tr')).data();
					generateInvoice(data.penawaran_id,"3-0");                                            					
                                                                 
                });
                
            }
		});            
    }
    
	function generateInvoice(id,tipe) {
		
        $.ajax({
            type: 'GET',
            url: "" + base_url + "/generate/invoice/" + id+"/"+tipe,
            dataType: 'JSON',
            success: function (data) {
	            if (data.status == 'insert_successful') {

                    success_noti('Data Berhasil Digenerate');
	                data_table.ajax.reload(null, false);

	            } else if (data.status == 'insert_failed') {

                    error_noti(data.msg);
	                
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