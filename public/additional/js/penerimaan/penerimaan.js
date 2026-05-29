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
            processing: true,
            lengthChange: true,
            /* initComplete: function() {
                data_table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
                $("#example2").show();
            },
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'], */
            ajax: {
                "url": "" + base_url + '/getDataJson/dokPenerimaan',
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
                        '<button class="btn btn-primary  btn-sm btn-search" title="Cetak Penawaran"><i class="bx bx-search me-0"></i></button>&nbsp;';					                   
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
                title: "Tanggal Pesan",
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
            }],

            "drawCallback": function (settings) {
				
                $('.btn-search').on('click', function () {
                    var data = data_table.row($(this).parents('tr')).data();                    
					var kode = $('#kode').val();
					var urlx = "/listTerima/"+kode+"/"+data.pesan_id;

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