var data_table;
var data_table2;
$(document).ready(function () {	
	
	// set tanggal hari ini
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth().toString().padStart(2, "0");
	var y = date.getFullYear();
	const month = date.toLocaleString('default', { month: 'long' });
	var tgl = d+" "+month+" "+y;
	$('#bulanDashboard').val(tgl);
	
	$('button#tambah').on('click', function () {        
		clearModal();         		
		$('#method_field').val("POST");
		$(".modal-form").modal('show');
    });

	$('button#tambahIdea').on('click', function () {        
		clearModal();         		
		$('#method_field').val("POST");
		$(".modal-form1").modal('show');
    });
	
	$('button#btn_simpan').on('click', function () {        
        insertUpdateProses();
    }); 

	$('button#btn_simpan2').on('click', function () {        
        insertUpdateProses2();
    }); 
	
	loadTglPenting();
	loadGrafik();
	loadContentIdea();
		
});

function loadGrafik(){
	
	var tgl2 = new Date($('#bulanDashboard').val());
	var month = tgl2.toLocaleString('default', { month: '2-digit' });
	var monthName = tgl2.toLocaleString('default', { month: 'long'});
	var year = tgl2.toLocaleString('default', { year: 'numeric' });
	var sosmed = $('#jenis_sosmed').val();
	var param = month+"-"+sosmed+"-"+year;
	

	$.ajax({
		type: 'GET',
		url: "" + base_url + "/getDataJson/dataGrafik/" + param,
		dataType: 'JSON',            

		success: function (data) {
			
			$('#txtJumlahContent').text(data.jumlahContent+" Content");
			$('#txtFollower').text(data.follower);
			$('#txtPeriode').text(" Bulan "+monthName+" "+year);
			$('#txtJumlahIdea').text(data.jumlahIdea+" Idea");
			$('#txtPeriode2').text(" Bulan "+monthName+" "+year);
			$('#gfkContentCalender_0').text("Kategori "+data.dataNameKat[0]);
			$('#gfkContentCalender_1').text("Kategori "+data.dataNameKat[1]);
			$('#gfkContentCalender_2').text("Kategori "+data.dataNameKat[2]);
			$('#gfkPersenCalender_0').text(data.dataPersen[0]+" %");
			$('#gfkPersenCalender_1').text(data.dataPersen[1]+" %");
			$('#gfkPersenCalender_2').text(data.dataPersen[2]+" %");
			$('#gfkTahun_0').text("Tahun "+year);
			$('#gfkTahun_1').text("Tahun "+year);
			$('#gfkTahun_2').text("Tahun "+year);
			


			$(function() {
				"use strict";
				var e = {
					series: [{
						name: "Sessions",
						data: [14, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5]
					}],
					chart: {
						foreColor: "#9ba7b2",
						height: 310,
						type: "area",
						zoom: {
							enabled: !1
						},
						toolbar: {
							show: !0
						},
						dropShadow: {
							enabled: !0,
							top: 3,
							left: 14,
							blur: 4,
							opacity: .1
						}
					},
					stroke: {
						width: 5,
						curve: "smooth"
					},
					xaxis: {
						type: "datetime",
						categories: ["1/11/2000", "2/11/2000", "3/11/2000", "4/11/2000", "5/11/2000", "6/11/2000", "7/11/2000", "8/11/2000", "9/11/2000", "10/11/2000", "11/11/2000", "12/11/2000"]
					},
					title: {
						text: "Sessions",
						align: "left",
						style: {
							fontSize: "16px",
							color: "#666"
						}
					},
					fill: {
						type: "gradient",
						gradient: {
							shade: "light",
							gradientToColors: ["#0d6efd"],
							shadeIntensity: 1,
							type: "vertical",
							opacityFrom: .7,
							opacityTo: .2,
							stops: [0, 100, 100, 100]
						}
					},
					markers: {
						size: 5,
						colors: ["#0d6efd"],
						strokeColors: "#fff",
						strokeWidth: 2,
						hover: {
							size: 7
						}
					},
					dataLabels: {
						enabled: !1
					},
					colors: ["#0d6efd"],
					yaxis: {
						title: {
							text: "Sessions"
						}
					}
				};
				
				new ApexCharts(document.querySelector("#chart5"), e).render(), Highcharts.chart("chart7", {
					chart: {
						height: 400,
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: !1,
						type: "pie",
						styledMode: !0
					},
					credits: {
						enabled: !1
					},
					title: {
						text: "Top 5 Categories Of The Month"
					},
					
					tooltip: {
						pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
					},
					accessibility: {
						point: {
							valueSuffix: "%"
						}
					},
					plotOptions: {
						pie: {
							allowPointSelect: !0,
							cursor: "pointer",
							innerSize: 120,
							dataLabels: {
								enabled: !0,
								format: "<b>{point.name}</b>: {point.percentage:.1f} %"
							},
							showInLegend: !0
						}
					},
					series: [{
						name: "Users",
						colorByPoint: !0,
						data: data.data
					}],
					responsive: {
						rules: [{
							condition: {
								maxWidth: 500
							},
							chartOptions: {
								plotOptions: {
									pie: {
										innerSize: 100,
										dataLabels: {
											enabled: !1
										}
									}
								}
							}
						}]
					}
				}),Highcharts.chart("chart6", {
					chart: {
						height: 380,
						type: "column",
						styledMode: !0
					},
					credits: {
						enabled: !1
					},
					title: {
						text: 'Jumlah Content Planner Tahun '+year,
					},
					accessibility: {
						announceNewData: {
							enabled: !0
						}
					},
					xAxis: {						
						categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
					},
					yAxis: {
						title: {
							text: "Jumlah"
						}
					},
					legend: {
						enabled: !1
					},
					plotOptions: {
						series: {
							borderWidth: 0,
							dataLabels: {
								enabled: !0,
								format: "{point.y:.1f}"
							}
						}
					},
					tooltip: {
						headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
						pointFormat: '<span style="color:{point.color}">{point.name}</span><b>{point.y:.2f}</b> Content<br/>'
					},
					series: [{
						name: "",
						colorByPoint: !0,
						data: data.data2				
					}],
					drilldown: {
						series: [{
							name: "Chrome",
							id: "Chrome",
							data: [
								["v65.0", .1],
								["v64.0", 1.3],
								["v63.0", 53.02],
								["v62.0", 1.4],
								["v61.0", .88],
								["v60.0", .56],
								["v59.0", .45],
								["v58.0", .49],
								["v57.0", .32],
								["v56.0", .29],
								["v55.0", .79],
								["v54.0", .18],
								["v51.0", .13],
								["v49.0", 2.16],
								["v48.0", .13],
								["v47.0", .11],
								["v43.0", .17],
								["v29.0", .26]
							]
						}, {
							name: "Firefox",
							id: "Firefox",
							data: [
								["v58.0", 1.02],
								["v57.0", 7.36],
								["v56.0", .35],
								["v55.0", .11],
								["v54.0", .1],
								["v52.0", .95],
								["v51.0", .15],
								["v50.0", .1],
								["v48.0", .31],
								["v47.0", .12]
							]
						}, {
							name: "Internet Explorer",
							id: "Internet Explorer",
							data: [
								["v11.0", 6.2],
								["v10.0", .29],
								["v9.0", .27],
								["v8.0", .47]
							]
						}, {
							name: "Safari",
							id: "Safari",
							data: [
								["v11.0", 3.39],
								["v10.1", .96],
								["v10.0", .36],
								["v9.1", .54],
								["v9.0", .13],
								["v5.1", .2]
							]
						}, {
							name: "Edge",
							id: "Edge",
							data: [
								["v16", 2.6],
								["v15", .92],
								["v14", .4],
								["v13", .1]
							]
						}, {
							name: "Opera",
							id: "Opera",
							data: [
								["v50.0", .96],
								["v49.0", .82],
								["v12.1", .14]
							]
						}]
					}
				});

				var e = {
					series: [{
						name: "Revenue",
						data: data.data3[0]
					}],
					chart: {
						type: "line",
						height: 65,
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
						data: data.data3[1]
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
							color: "#f41127"
						},
						sparkline: {
							enabled: !0
						}
					},
					markers: {
						size: 0,
						colors: ["#f41127"],
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
					colors: ["#f41127"],
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
						data: data.data3[2]
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
							color: "#0dcaf0"
						},
						sparkline: {
							enabled: !0
						}
					},
					markers: {
						size: 0,
						colors: ["#0dcaf0"],
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
					colors: ["#0dcaf0"],
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
			
			/* $(function() {
				"use strict";
												
			}); */
		},

		error: function (xmlhttprequest, textstatus, message) {
			error_noti('Koneksi Ke Server Gagal, Mohon Refresh Halaman')
		}
	});
	
}

function loadTglPenting(){
		
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
}

function loadContentIdea(){
		
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
}


/* $("#datepicker").datepicker({
    onSelect: function(dateText) {
        alert("Selected date: " + dateText + "; input's current value: " + this.value);
    }
}); */

function insertUpdateProses(){
	
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
		//sweetAlertLoading('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah',1000);
	} else {                        
		error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
	}
}

function insertUpdateProses2(){
	
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
		//sweetAlertLoading('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah',1000);
	} else {                        
		error_noti('Mohon Isi Form Dengan Lengkap, Cek Input Form Yang Berwarna Merah');
	}
}

function deleteProses(url,jenis) {
	
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
}

	
$('#jenis_sosmed').change(function(){
	loadGrafik();
    loadTglPenting();
	loadContentIdea();
});

$('#bulanDashboard').change(function(){
	loadGrafik();
    loadTglPenting();
	loadContentIdea();
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

// Script untuk full calendar
	/* var SITEURL = "{{ url('/') }}";
  
	$.ajaxSetup({
		headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
  
	var calendar = $('#calendar').fullCalendar({
		editable: true,
		events: SITEURL + "/fullcalender",
		displayEventTime: false,
		editable: true,
		eventRender: function (event, element, view) {
			if (event.allDay === 'true') {
					event.allDay = true;
			} else {
					event.allDay = false;
			}
		},
		selectable: true,
		selectHelper: true,
		select: function (start, end, allDay) {
			var title = prompt('Event Title:');
			if (title) {
				var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
				var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
				$.ajax({
					url: SITEURL + "/fullcalenderAjax",
					data: {
						title: title,
						start: start,
						end: end,
						type: 'add'
					},
					type: "POST",
					success: function (data) {
						displayMessage("Event Created Successfully");

						calendar.fullCalendar('renderEvent',
							{
								id: data.id,
								title: title,
								start: start,
								end: end,
								allDay: allDay
							},true);

						calendar.fullCalendar('unselect');
					}
				});
			}
		},
		eventDrop: function (event, delta) {
			var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
			var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

			$.ajax({
				url: SITEURL + '/fullcalenderAjax',
				data: {
					title: event.title,
					start: start,
					end: end,
					id: event.id,
					type: 'update'
				},
				type: "POST",
				success: function (response) {
					displayMessage("Event Updated Successfully");
				}
			});
		},
		eventClick: function (event) {
			var deleteMsg = confirm("Do you really want to delete?");
			if (deleteMsg) {
				$.ajax({
					type: "POST",
					url: SITEURL + '/fullcalenderAjax',
					data: {
							id: event.id,
							type: 'delete'
					},
					success: function (response) {
						calendar.fullCalendar('removeEvents', event.id);
						displayMessage("Event Deleted Successfully");
					}
				});
			}
		}	
 
	}); */