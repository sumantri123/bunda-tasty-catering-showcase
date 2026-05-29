@extends('frontend.layout_home.default')

@push('style')

      <!-- Aditional Style CSS Here -->

@endpush


@section('content')

<?php 
	$barChart = "";	
	$bgcolor = array('bg-primary','bg-danger','bg-info','bg-warning','bg-success');
	$textcolor = array('text-primary','text-danger','text-info','text-warning','text-success');	
	
	for($b=0; $b<count($data['dataPenawaran']); $b++){
		
		$barChart .= "{ name: '" .$data['dataPenawaran'][$b]->penawaran_tahun. "', y: ".$data['dataPenawaran'][$b]->total.", drilldown: " .$data['dataPenawaran'][$b]->penawaran_tahun." },";		
	}
?>

<h6 class="mb-0 text-uppercase">{{$data['title']}}</h6>
<hr/>
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
	<div class="col">
		<div class="card radius-10 bg-gradient-deepblue">
		 <div class="card-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-white">{{ $data['totalPenawaran'] }}</h5>
				<div class="ms-auto">
					<i class='bx bx-cart fs-3 text-white'></i>
				</div>
			</div>
			
			<div class="d-flex align-items-center text-white">
				<p class="mb-0">Total Penawaran</p>				
			</div>
		</div>
	  </div>
	</div>
	<div class="col">
		<div class="card radius-10 bg-gradient-orange">
		<div class="card-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-white">{{ $data['totalInvoice'] }}</h5>
				<div class="ms-auto">
					<i class='bx bx-notepad fs-3 text-white'></i>
				</div>
			</div>
			
			<div class="d-flex align-items-center text-white">
				<p class="mb-0">Total Invoice</p>				
			</div>
		</div>
	  </div>
	</div>
	<div class="col">
		<div class="card radius-10 bg-gradient-ohhappiness">
		<div class="card-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-white">{{ $data['totalKwitansi'] }}</h5>
				<div class="ms-auto">
					<i class='bx bx-credit-card-front fs-3 text-white'></i>
				</div>
			</div>
			
			<div class="d-flex align-items-center text-white">
				<p class="mb-0">Total Kwitansi</p>				
			</div>
		</div>
	</div>
	</div>
	<div class="col">
		<div class="card radius-10 bg-gradient-ibiza">
		 <div class="card-body">
			<div class="d-flex align-items-center">
				<h5 class="mb-0 text-white">{{ $data['totalPesanan'] }}</h5>
				<div class="ms-auto">
					<i class='bx bx-wallet-alt fs-3 text-white'></i>
				</div>
			</div>
			
			<div class="d-flex align-items-center text-white">
				<p class="mb-0">Pesanan Ke Vendor</p>				
			</div>
		</div>
	 </div>
	</div>
</div><!--end row-->

<!--end row-->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<div id="chart6"></div>
			</div>
		</div>
	</div>
	<div class="col">
		<div class="card radius-10">
			<div class="card-body">
				<div id="chart7"></div>
			</div>
		</div>
	</div>
</div>
<!--end row-->

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
	<div class="col-md-12 d-flex">
		<div class="card radius-10 w-100">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 font-weight-bold">Top Customer {{$data['year']}}</h5>
					<p class="mb-0 ms-auto"><i class="bx bx-dots-horizontal-rounded float-right font-22"></i>
					</p>
				</div>
				<div class="d-flex mt-2 mb-4">
					<h2 class="mb-0 font-weight-bold">{{$data['totalPenawaran']}}</h2>
					<p class="mb-0 ms-1 font-14 align-self-end text-secondary">Total Customers</p>
				</div>
				<div class="progress radius-10" style="height: 10px">
					<?php 
						for($a=0;$a<count($data['dataCustomer']);$a++){
						
							$width[$a]= $data['dataCustomer'][$a]->total / $data['totalPenawaran'] * 100;
						
							echo '<div class="progress-bar '.$bgcolor[$a].'" role="progressbar" style="width:'.$width[$a].'%"></div>';
						}
					?>
					
				</div>
				<div class="table-responsive mt-4">
					<table class="table mb-0">
						<tbody>
							@for($i=0;$i<count($data['dataCustomer']);$i++)
								<tr>
									<td class="px-0">
										<div class="d-flex align-items-center">
											<div><i class="bx bxs-checkbox me-2 font-22 {{$textcolor[$i]}}"></i>
											</div>
											{{ $data['dataCustomer'][$i]->customer_nama }}
										</div>
									</td>
									<td>{{ $data['dataCustomer'][$i]->total }} Orders</td>									
								</tr>	
							@endfor  	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col d-flex">
		<div class="card radius-10 w-100">
			<div class="card-body">
				<div id="chart8"></div>
			</div>
		</div>
	</div>	
	<div class="col-md-12 d-flex">
		<div class="card radius-10 w-100">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<h5 class="mb-0 font-weight-bold">Top Supplier</h5>
					<p class="mb-0 ms-auto"><i class="bx bx-dots-horizontal-rounded float-right font-22"></i>
					</p>
				</div>
				<div class="d-flex mt-2 mb-4">
					<h2 class="mb-0 font-weight-bold">{{$data['totalPesanan']}}</h2>
					<p class="mb-0 ms-1 font-14 align-self-end text-secondary">Total Orders</p>
				</div>
				<div class="progress radius-10" style="height: 10px">
					<?php 
						for($a=0;$a<count($data['dataSupplier']);$a++){
						
							$width[$a]= $data['dataSupplier'][$a]->total / $data['totalPesanan'] * 100;
						
							echo '<div class="progress-bar '.$bgcolor[$a].'" role="progressbar" style="width:'.$width[$a].'%"></div>';
						}
					?>
				</div>
				<div class="table-responsive mt-4">
					<table class="table mb-0">
						<tbody>
							@for($i=0;$i<count($data['dataSupplier']);$i++)
								<tr>
									<td class="px-0">
										<div class="d-flex align-items-center">
											<div><i class="bx bxs-checkbox me-2 font-22 {{$textcolor[$i]}}"></i>
											</div>
											{{ $data['dataSupplier'][$i]->supplier_nama }}
										</div>
									</td>
									<td>{{ $data['dataSupplier'][$i]->total }} Orders</td>									
								</tr>	
							@endfor  													
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card radius-10">
	<div class="card-body">
		<div class="d-flex align-items-center">
			<div>
				<h5 class="mb-0">Orders Summary</h5>
			</div>
			<div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>
			</div>
		</div>
		<hr>
		<div class="table-responsive">
			<table class="table align-middle mb-0 table-hover" id="Transaction-History">
				<thead class="table-light">
					<tr>
						<th>No Penawaran</th>
						<th>Customer</th>
						<th>Perihal</th>
						<th>Tanggal</th>												
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php for($c=0;$c<count($data['dataSummary']);$c++){ ?>
						<tr>
							<td width="10%"><?= $data['dataSummary'][$c]->penawaran_nomor?></td>
							<td width="10%"><?= $data['dataSummary'][$c]->customer_nama?></td>
							<td style="word-break:break-all;" width="45%"><?= $data['dataSummary'][$c]->penawaran_hal?></td>
							<td width="10%"><?= date("d M Y", strtotime($data['dataSummary'][$c]->penawaran_tgl))?></td>													
							<td width="10%">
								<div class="d-flex order-actions">	
									<a href="javascript:;" title="Detail" class="bg-primary text-white" onclick="searchDetail({{$data['dataSummary'][$c]->penawaran_id}})"><i class="bx bx-search"></i></a>&nbsp;
									<a href="javascript:;" title="Cetak Penawaran" class="bg-success text-white" onclick="print({{$data['dataSummary'][$c]->penawaran_id}})"><i class="bx bx-printer"></i></a>								
								</div>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection

@push('scripts')	
	<script>
		$(document).ready(function() {
			$('#Transaction-History').DataTable({
				lengthMenu: [[6, 10, 20, -1], [6, 10, 20, 'Todos']]
			});
		} );
		  
		function print(id){
			window.open(base_url+"/cetakPenawaran/"+id, '_blank', 'left=0,top=0,width=1000,height=700,status=0');
		}

		function searchDetail(id){			
			window.open(base_url+"/detailProject/"+id, '_blank', 'left=0,top=0,width=900,height=700,status=0');
		}		

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
		
		new ApexCharts(document.querySelector("#chart5"), e).render(), Highcharts.chart("chart6", {
			chart: {
				height: 350,
				type: "column",
				styledMode: !0
			},
			credits: {
				enabled: !1
			},
			title: {
				text: "Grafik Penawaran 4 Tahun Terakhir"
			},
			accessibility: {
				announceNewData: {
					enabled: !0
				}
			},
			xAxis: {
				type: "category"
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
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
			},
			series: [{
				name: "",
				colorByPoint: !0,
				data: [<?php echo $barChart?>]				
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
		}), Highcharts.chart("chart7", {
			chart: {
				height: 350,
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
				text: "Sessions Device"
			},
			subtitle: {
				text: "Ratio of devices use by users"
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
				data: [{
					name: "Desktop",
					y: 56
				}, {
					name: "Mobile",
					y: 30
				}, {
					name: "Tablet",
					y: 14
				}]
			}],
			responsive: {
				rules: [{
					condition: {
						maxWidth: 500
					},
					chartOptions: {
						plotOptions: {
							pie: {
								innerSize: 140,
								dataLabels: {
									enabled: !1
								}
							}
						}
					}
				}]
			}
		}), Highcharts.chart("chart8", {
			chart: {
				type: "bar",
				styledMode: !0
			},
			credits: {
				enabled: !1
			},
			exporting: {
				buttons: {
					contextButton: {
						enabled: !1
					}
				}
			},
			title: {
				text: "Visitor by Gender"
			},
			xAxis: {
				categories: ["Jan", "Feb", "Mar", "Apr", "May"]
			},
			yAxis: {
				min: 0,
				title: {
					text: "Visitor by Genders",
					style: {
						display: "none"
					}
				}
			},
			legend: {
				reversed: !1
			},
			plotOptions: {
				series: {
					stacking: "normal"
				}
			},
			series: [{
				name: "Male",
				data: [5, 3, 4, 7, 2]
			}, {
				name: "Female",
				data: [2, 2, 3, 2, 1]
			}, {
				name: "Others",
				data: [3, 4, 4, 2, 5]
			}]
		});
		e = {
			series: [42, 47, 52, 58, 65],
			chart: {
				height: 340,
				type: "polarArea"
			},
			labels: ["Chrome", "Firefox", "Edge", "Opera", "Safari"],
			fill: {
				opacity: 1
			},
			stroke: {
				width: 1,
				colors: void 0
			},
			colors: ["#17a00e", "#0dcaf0", "#f41127", "#ffc107", "#0d6efd"],
			yaxis: {
				show: !1
			},
			dataLabels: {
				enabled: !1
			},
			legend: {
				show: !1,
				position: "bottom"
			},
			plotOptions: {
				polarArea: {
					rings: {
						strokeWidth: 0
					}
				}
			}
		};
		
	});
	</script>
@endpush
