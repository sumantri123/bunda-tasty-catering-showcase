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
                "url": "" + base_url + '/getDataJson/dokListInvoice',
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
                render: function (data, type, row, meta) {
                    var result = '';
                    result += '<td class="text-center">';
					
					if(row.kw_id > 0){	
						result +=
							'<button class="btn btn-success  btn-sm btn-print" title="Cetak Invoice"><i class="bx bx-printer me-0"></i></button>&nbsp;';					
						result +=
							'<button class="btn btn-primary  btn-sm btn-upload" title="Upload Bukti Pembayaran"><i class="bx bx-upload me-0"></i></button>&nbsp;';												
					}
					result +=
                        '<button class="btn btn-warning  btn-sm btn-edit" title="Edit Data"><i class="bx bx-edit me-0"></i></button>&nbsp;';					                   					
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
                title: "Nomor Kwitansi",
                data: "kw_nomor",                
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Tanggal",
                data: "kw_tgl",                
                visible: true,
                sortable: true,
                class: ""
            },{
                title: "Perusahaan",
                data: "kw_company",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Deskripsi",
                data: "kw_deskripsi",
                visible: true,
                sortable: true,
                class: ""
            }, {
                title: "Nominal",                
                visible: true,
                sortable: true,
                class: "",
				render: function (data, type, row, meta) {
					if(row.kw_id > 0){						
						var result = convertToRupiahNoRp(row.kw_nominal);                    
					} else {
						var result = "";                    
					}
                    return result;
                }
            },{
                title: "Pajak (%)",                
                visible: true,
				data: "kw_pajak_persen",
                sortable: true,
                class: ""				
            },{
                title: "Pajak (Rp)",                
                visible: true,				
                sortable: true,
                class: "text-right",
				render: function (data, type, row, meta) {
					if(row.kw_id > 0){						
						var result = convertToRupiahNoRp(row.kw_pajak_nominal);                    
					} else {
						var result = "";                    
					}
                    return result;
                }
            }],

            "drawCallback": function (settings) {				
				
				$('.btn-upload').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();					
					var kode = $('#kode').val();
					var urlx = "/addBuktiKwitansi/"+kode+"/"+data.kw_id;
					
					otherPage(urlx)					
                                    
                });
				

				$('.btn-print').on('click', function () {                      
					var data = data_table.row($(this).parents('tr')).data();	
					window.open(base_url+"/cetakKw/"+data.kw_id, '_blank', 'left=0,top=0,width=1000,height=700,status=0');					
					
				});

				$('.btn-edit').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();                    
					var kode = $('#kode').val();
					
					if(data.kw_id > 0){
						var urlx = "/editKwi/"+kode+"/"+data.kw_id;
					} else {
						var urlx = "/addKwi/"+kode+"/"+data.invoice_id;
					}

					otherPage(urlx)					
                                    
                                                                 
                });
                
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