//var data_table;
//var data_table2;
$(document).ready(function () {	
	
	loadDataSosmed();
	
	$('button#tambah').on('click', function () {        
         clearModal();         
         $('#modal_label').text('Form Tambah Data');
         $('#method_field').val("POST");
         $(".modal-form").modal('show');
    });
	
	$('INPUT[type="file"]').change(function () {
	
		var ext = this.value.match(/\.(.+)$/)[1];
		

		if(this.files[0].size > 50000000) {            

			error_noti('Please upload file less than 50MB. Thanks!!');            
			$(this).val('');

		} else {

			switch (ext) {
				case 'MP4':
				case 'mp4':        				
					$('#btnSimpan').attr('disabled', false);
					break;
				default:
					error_noti('File Yang Diperbolehkan Hanya Extension mp4 (File Anda Berformat '+ext+')');            
					this.value = '';
			}
			
		  }    
	});	

});

	/* var $videoSrc;  
	$('.video-btn').click(function() {
		alert("tes"); */
		//$videoSrc = $(this).data( "src" );
//	});
	
	// when the modal is opened autoplay it  
//	$('#myModal').on('shown.bs.modal', function (e) {		
		// set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
//		$("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");

//	})
	  
	// stop playing the youtube video when I close the modal
//	$('#myModal').on('hide.bs.modal', function (e) {
		// a poor man's stop video
//		$("#video").attr('src',$videoSrc); 
//	}) 
	
function showVideo(a){
		
	$('#myModalYoutube').on('shown.bs.modal', function () { // on opening the modal
		
		$("#myModalYoutube iframe").attr("src", a + "?autoplay=1");
	  
	}).modal('show');
}

function closeModalx(){
		
	var videoSrcx = $("#myModalYoutube iframe").attr("src");	
	$("#myModalYoutube iframe").attr("src", null);
	$('#myModalYoutube').modal('hide');
}

function addVideo(){
	
	clearModal();         		
	var openId = $('#open_id').val();
	var idSosmed = $('#jenis_sosmed').val();
	$('#method_field').val("POST");
	$(".modal-form").modal('show');
	$('#openId').val(openId);
	$('#idSosmed').val(idSosmed);
}

$('#form_modal').submit(function(e) {	
	e.preventDefault();
		

	var method = $('#method_field').val();
	var action_url = "" + base_url + "/uploadVideo";
	var action_type = "Tambah";	
		
	var formData = new FormData(this);        
	var form = $('#form_modal');
	
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
			},
			error: function (error) {				
				error_noti("Data Gagal Diupload");
			}
		});

	} else {
		
		error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
	}  
});

function loadDataSosmed(){
	
	var sosmed = $('#jenis_sosmed').val();
	
	if(sosmed == 4) {
		var urlx = "" + base_url + '/getDataJson/dbtiktok/'+sosmed;
	} else {
		var urlx = "";
	}
	
	if(urlx == "") {
		info_noti('Cek Url Pengambilan Data / Cek Master Sosial Media');
		
	} else {
		
		$.ajax({
			type: 'GET',
			url: urlx,
			dataType: 'JSON',            
			success: function (data) {				
				$('#badan').html(data.data);

				if(sosmed == 4) {
					loadGrafik();
				} 
				
			},

			error: function (xmlhttprequest, textstatus, message) {
				error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman')
			}
		});
	}
}

function loadGrafik(){
	
	/* var tgl2 = new Date($('#bulanDashboard').val());
	var month = tgl2.toLocaleString('default', { month: '2-digit' });
	var monthName = tgl2.toLocaleString('default', { month: 'long'});
	var year = tgl2.toLocaleString('default', { year: 'numeric' }); */
	//var sosmed = $('#jenis_sosmed').val();
	var param = 4;
	

	$.ajax({
		type: 'GET',
		url: "" + base_url + "/getDataJson/dataGrafikTiktok/" + param,
		dataType: 'JSON',            

		success: function (data) {
			
			$('#gfkContentCalender_0').text("FOLLOWER GROWTH");
			$('#gfkPersenCalender_0').text(data.follower +" New Follower In This Month");

			$('#gfkContentCalender_1').text("CONTENT GROWTH");
			$('#gfkPersenCalender_1').text(data.content +" New Content In This Month");
			
			$('#gfkContentCalender_2').text("LIKES GROWTH");
			$('#gfkPersenCalender_2').text(data.like +" New Likes In This Month");	

			$(function() {
				"use strict";			
				var e = {
					series: [{
						name: "Revenue",
						data: data.data0
					}],
					chart: {
						type: "area",
						height: 100,
						toolbar: {
							show: !1
						},
						zoom: {
							enabled: !1
						},
						dropShadow: {
							enabled: !0,
							top: 3,
							left: 14,
							blur: 4,
							opacity: .12,
							color: "#17a00e"
						},
						sparkline: {
							enabled: !0
						}
					},
					markers: {
						size: 0,
						colors: ["#17a00e"],
						strokeColors: "#fff",
						strokeWidth: 2,
						hover: {
							size: 7
						}
					},
					dataLabels: {
						enabled: !1
					},
					stroke: {
						show: !0,
						width: 3,
						curve: "smooth"
					},
					colors: ["#17a00e"],
					xaxis: {
						categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
					},
					fill: {
						opacity: 1
					},
					tooltip: {
						theme: "dark",
						fixed: {
							enabled: !1
						},
						x: {
							show: !1
						},
						y: {
							title: {
								formatter: function(e) {
									return ""
								}
							}
						},
						marker: {
							show: !1
						}
					}
				};				
				new ApexCharts(document.querySelector("#chart12"), e).render();

				e = {
					series: [{
						name: "Pageviews",
						data: data.data1
					}],
					chart: {
						type: "area",
						height: 100,
						toolbar: {
							show: !1
						},
						zoom: {
							enabled: !1
						},
						dropShadow: {
							enabled: !1,
							top: 3,
							left: 14,
							blur: 4,
							opacity: .12,
							color: "#1133f4"
						},
						sparkline: {
							enabled: !0
						}
					},
					markers: {
						size: 0,
						colors: ["#1133f4"],
						strokeColors: "#fff",
						strokeWidth: 2,
						hover: {
							size: 7
						}
					},
					dataLabels: {
						enabled: !1
					},
					stroke: {
						show: !0,
						width: 2,
						curve: "smooth"
					},
					colors: ["#1133f4"],
					xaxis: {
						categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
					},
					fill: {
						opacity: 1
					},
					tooltip: {
						theme: "dark",
						fixed: {
							enabled: !1
						},
						x: {
							show: !1
						},
						y: {
							title: {
								formatter: function(e) {
									return ""
								}
							}
						},
						marker: {
							show: !1
						}
					}
				};
				new ApexCharts(document.querySelector("#chart13"), e).render();

				e = {
					series: [{
						name: "New Sessions",
						data: data.data2
					}],
					chart: {
						type: "area",
						height: 100,
						toolbar: {
							show: !1
						},
						zoom: {
							enabled: !1
						},
						dropShadow: {
							enabled: !1,
							top: 3,
							left: 14,
							blur: 4,
							opacity: .12,
							color: "#f411f4"
						},
						sparkline: {
							enabled: !0
						}
					},
					markers: {
						size: 0,
						colors: ["#f411f4"],
						strokeColors: "#fff",
						strokeWidth: 2,
						hover: {
							size: 7
						}
					},
					dataLabels: {
						enabled: !1
					},
					stroke: {
						show: !0,
						width: 2,
						curve: "smooth"
					},
					colors: ["#f411f4"],
					xaxis: {
						categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
					},
					fill: {
						opacity: 1
					},
					tooltip: {
						theme: "dark",
						fixed: {
							enabled: !1
						},
						x: {
							show: !1
						},
						y: {
							title: {
								formatter: function(e) {
									return ""
								}
							}
						},
						marker: {
							show: !1
						}
					}
				};
				new ApexCharts(document.querySelector("#chart14"), e).render();	
			});
					
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman')
		}
	});
	
}

/* function loadTglPenting(){
		
	var tgl2 = new Date($('#bulanDashboard').val());
	var month = tgl2.toLocaleString('default', { month: '2-digit' });
	var year = tgl2.toLocaleString('default', { year: 'numeric' });
	var tgl = tgl2.toLocaleString('default', { day: '2-digit' });

	var sosmed = $('#jenis_sosmed').val();
	var param = month+"-"+sosmed+"-"+year+"-"+tgl;

	
	data_table  = $('#Transaction-History').DataTable({     
		destroy: true,		
		processing: false,			
		lengthChange: true,
		lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All']],
		ajax: {
			"url": "" + base_url + '/getDataJson/dataCalendar/'+param,
			'type': 'GET',
			'dataType': 'JSON',
			'beforeSend': function(){
				BeforeSend();
			},
			'complete': function(){
				AfterSend();
			},	
			'error': function (xhr, textStatus, ThrownException) {                    
				error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
			}
		},

		columns: [
		{
			title: "Aksi",
			data: "t_cplanner_id",
			width: "15%",
			visible: true,
			sortable: false,
			class: "text-center",
			render: function (data, type, full, meta) {
				var result = '';
				result += '<td class="text-center">';
				result +=
					'<button class="btn btn-danger btn-sm btn-delete"> <i class="fadeIn animated bx bx-trash me-0"></i> </button>&nbsp;';
				result +=
					'<button class="btn btn-warning btn-sm btn-edit"> <i class="fadeIn animated bx bx-edit me-0"></i> </button>&nbsp;';
				result += '</td>';
				return result;
			}
		},{
			title: "Tanggal",  
			data: "datestart",				
			visible: true,
			sortable: true,
			width: "15%",
			class: "",                
		},{
			title: "Kategori",
			data: "m_cplanner_nama",
			width: "15%",
			visible: true,
			sortable: true,
			render: function (data, type, row, meta) {
				var result = '';
				result += '<span class="'+row.badge_class+'">'+data+'</span>';			
				return result;
			}
		}, {
			title: "Sosmed",
			data: "sosmed_jenis",
			width: "15%",
			visible: true,
			sortable: true,			
		},{
			title: "Detail",
			data: "detail",
			width: "40%",
			visible: true,
			sortable: true,
			class: ""
		}],

		"drawCallback": function (settings) {
			$('.btn-edit').on('click', function () {
				clearModal();
				var data = data_table.row($(this).parents('tr')).data();
				
				$('#id').val(data.t_cplanner_id);
				$('#event_nama').val(data.detail);
				$('#tgl_event').val(data.datestart);					
				$('#time').val(data.jamstart);					
				$("#kat").val(data.id_m_kat_cplanner).trigger('change');
				$("#sosmed").val(data.id_sosmed); 
				$('#modal_label').text('Form Ubah');
				$('#method_field').val("PUT");
				$(".modal-form").modal('show');
			});
			
			$('.btn-delete').on('click', function () {
				var data = data_table.row($(this).parents('tr')).data();
				var param = data.t_cplanner_id+"-"+1;
				var url = "" + base_url + "/delete/contentPlanner/" + param;
				Lobibox.confirm({
					iconClass: true,
					title: 'Delete Data',                        
					msg: 'Yakin Hapus Data "' + data.detail + '"?',
					callback: function ($this, type, ev) {
						if(type=='yes'){
							deleteProses(url,1);							
						}        
					}
				});                   
            });
			
		}
	});
} */

/* function loadContentIdea(){
		
	var tgl2 = new Date($('#bulanDashboard').val());
	var month = tgl2.toLocaleString('default', { month: '2-digit' });
	var year = tgl2.toLocaleString('default', { year: 'numeric' });
	var tgl = tgl2.toLocaleString('default', { day: '2-digit' });

	var sosmed = $('#jenis_sosmed').val();
	var param = month+"-"+sosmed+"-"+year+"-"+tgl;

	
	data_table2  = $('#tableIdea').DataTable({     
		destroy: true,		
		processing: false,			
		lengthChange: true,
		lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All']],
		ajax: {
			"url": "" + base_url + '/getDataJson/dataIdea/'+param,
			'type': 'GET',
			'dataType': 'JSON',
			'beforeSend': function(){
				BeforeSend();
			},
			'complete': function(){
				AfterSend();
			},	
			'error': function (xhr, textStatus, ThrownException) {                    
				error_noti('Error loading data. Exception: ' + ThrownException + "\n" + textStatus);
			}
		},

		columns: [
		{
			title: "Aksi",
			data: "t_cidea_id",
			width: "15%",
			visible: true,
			sortable: false,
			class: "text-center",
			render: function (data, type, full, meta) {
				var result = '';
				result += '<td class="text-center">';
				result +=
					'<button class="btn btn-danger btn-sm btn-delete"> <i class="fadeIn animated bx bx-trash me-0"></i> </button>&nbsp;';
				result +=
					'<button class="btn btn-warning btn-sm btn-edit"> <i class="fadeIn animated bx bx-edit me-0"></i> </button>&nbsp;';
				result += '</td>';
				return result;
			}
		},{
			title: "Tenggat Waktu",  
			data: "tenggat_waktu",				
			visible: true,
			sortable: true,
			width: "15%",
			class: "",                
		},{
			title: "Deskripsi",
			data: "deskripsi",
			width: "30%",
			visible: true,
			sortable: true,
			render: function (data, type, row, meta) {
				var result = '';
				result += '<span class="'+row.badge_class+'">'+data+'</span>';			
				return result;
			}
		}, {
			title: "Url<br>Inspirasi",
			data: "url_inspirasi",
			width: "10%",
			visible: true,
			sortable: true,
			class: "text-center",  
			render: function (data, type, full, meta) {
				var result = '';				
				result +=
					'<a href="'+data+'" target="_blank" class="btn btn-primary btn-sm btn-link1"> <i class="fadeIn animated bx bx-globe me-0"></i> </a>&nbsp;';								
				return result;
			}			
		},{
			title: "Url<br>File",
			data: "url_file",
			width: "10%",
			visible: true,
			sortable: true,
			class: "text-center",  
			render: function (data, type, full, meta) {
				var result = '';				
				result +=
					'<a href="'+data+'" target="_blank" class="btn btn-primary btn-sm btn-link2"> <i class="fadeIn animated bx bx-globe me-0"></i> </a>&nbsp;';								
				return result;
			}
		},{
			title: "Status",
			data: "status",
			width: "10%",
			visible: true,
			sortable: true,
			class: "",
			render: function (data, type, full, meta) {
				var result = '';				
				if(data == 1){
					result +=
						'<div class="d-flex align-items-center text-success"><i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i><span>Completed</span></div>';
				} else {
					result +=
						'<div class="d-flex align-items-center text-danger"><i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i><span>On Progress</span></div>';
				}
				return result;
			}
		}],

		"drawCallback": function (settings) {
			$('.btn-edit').on('click', function () {
				clearModal();
				var data = data_table2.row($(this).parents('tr')).data();
				
				$('#id2').val(data.t_cidea_id);
				$('#idea').val(data.deskripsi);
				$('#tenggat_waktu').val(data.tenggat_waktu);					
				$('#pic').val(data.pic);					
				$("#status2").val(data.status); 
				$("#sosmed2").val(data.id_sosmed); 
				$("#url_inspirasi").val(data.url_inspirasi); 
				$("#url_file").val(data.url_file); 
				$('#modal_label').text('Form Ubah');
				$('#method_field2').val("PUT");
				$(".modal-form1").modal('show'); 
			});
			
			$('.btn-delete').on('click', function () {
				var data = data_table2.row($(this).parents('tr')).data();
				var param = data.t_cidea_id+"-"+2;
				var url = "" + base_url + "/delete/contentIdea/" + param;				

				Lobibox.confirm({
					iconClass: true,
					title: 'Delete Data',                        
					msg: 'Yakin Hapus Data "' + data.deskripsi + '"?',
					callback: function ($this, type, ev) {
						if(type=='yes'){
							deleteProses(url,2);							
						}        
					}
				}); 
            });

			
		}
	});
} */


/* $("#datepicker").datepicker({
    onSelect: function(dateText) {
        alert("Selected date: " + dateText + "; input's current value: " + this.value);
    }
}); */

/* function insertUpdateProses(){
	
	var form = $('#form_crud');
	if (form.valid() == true) {
		
		var method = $('#method_field').val();
		var action_url = "" + base_url + "/contentPlanner";            
		var action_type = "Tambah";
		if (method === "PUT") {
			action_url = "" + base_url + "/contentPlanner/" + $('#id').val();
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
					loadGrafik();
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

/* function insertUpdateProses2(){
	
	var form = $('#form_crud2');
	if (form.valid() == true) {
		
		var method = $('#method_field2').val();
		var action_url = "" + base_url + "/contentIdea";            
		var action_type = "Tambah";
		if (method === "PUT") {
			action_url = "" + base_url + "/contentIdea/" + $('#id2').val();
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
					$('.modal-form1').modal('toggle');
					data_table2.ajax.reload(null, false);
					loadGrafik();
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

/* function deleteProses(url,jenis) {
	
	$.ajax({
		type: 'GET',		
		url: url,
		dataType: 'JSON',            

		success: function (data) {
			if (data.status == 'delete_successful') {
				success_noti('Data Berhasil Terhapus');

				if(jenis==1){
					data_table.ajax.reload(null, false);
				} else {
					data_table2.ajax.reload(null, false);
				}

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
} */

	
$('#jenis_sosmed').change(function(){
	loadDataSosmed();    
});


$('.single-select').select2({
    theme: 'bootstrap4',		
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    allowClear: Boolean($(this).data('allow-clear')),
});

/* $('.single-select2').select2({
    theme: 'bootstrap4',		
    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    placeholder: $(this).data('placeholder'),
    allowClear: Boolean($(this).data('allow-clear')),
});
 */
$('.datepicker').pickadate({
	selectMonths: true,
	selectYears: true
}),

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

 var validator = $('#form_crud').validate({

	rules: {
		
		kat: {
			required: true
		},
		
		event_nama: {
			required: true
		},

		tgl_event: {
			required: true,
		},	

		time: {
			required: true,
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

 var validator = $('#form_crud2').validate({

	rules: {
		
		sosmed2: {
			required: true
		},
		
		idea: {
			required: true
		},

		status2: {
			required: true,
		},	

		tenggat_waktu: {
			required: true,
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

