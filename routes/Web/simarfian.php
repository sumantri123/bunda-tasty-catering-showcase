<?php
	
    // Dokumen Penawaran   
	Route::get('/dokPenawaran/{kode}','DokPenawaranController@index');    	
	Route::get('/getDataJson/dokPenawaran', 'DokPenawaranController@getData');
	Route::get('/getDataJson/dokPO', 'DokPenawaranController@getDataPO');
	Route::get('/addPenawaran/{kode}','DokPenawaranController@add');
	Route::get('/addPenawaran/{kode}/{id}','DokPenawaranController@edit');    
	Route::get('/addDokPO/{kode}/{id}','DokPenawaranController@upload');    
	Route::put('/addPenawaran/{id}','DokPenawaranController@update');
	Route::post('/addPenawaran','DokPenawaranController@store');
	Route::post('/penawaranDet','DokPenawaranController@storeDet');
	Route::post('/savePO','DokPenawaranController@storePO');  
	Route::put('/penawaranDet/{id}','DokPenawaranController@updateDet');
	Route::get('/delete/addPenawaran/{id}','DokPenawaranController@destroy');    
	Route::get('/delete/penawaranDetail/{id}/{id2}','DokPenawaranController@destroyDet');      
	Route::get('/delete/dokPo/{id}','DokPenawaranController@destroyPO');    
	Route::post('/search/penawaranDet','DokPenawaranController@search');
	Route::get('/total/penawaranDetail/{id}','DokPenawaranController@totalDet');
	Route::get('/cetakPenawaran/{id}','DokPenawaranController@cetak');
	Route::post('/getIdPerCatering','DokPenawaranController@getIdPerkiraan');    	

	// Dashboard
	Route::get('/detailProject/{id}','DashboardController@detail');    	
	
	// Delivery Order
	Route::get('/do/{kode}','DeliveryOrderController@index');    	
	Route::get('/listDo/{kode}/{id}','DeliveryOrderController@list');
	Route::get('/getDataJson/dokPenawaranDO', 'DeliveryOrderController@getData');
	Route::get('/getDataJson/dokDO', 'DeliveryOrderController@getDataDO');
	Route::get('/addDO/{kode}/{id}','DeliveryOrderController@add');    
	Route::get('/editDO/{kode}/{id}/{id2}','DeliveryOrderController@edit');    
	Route::post('/addDO','DeliveryOrderController@store');
	Route::put('/addDO/{id}','DeliveryOrderController@update');
	Route::post('/search/DO','DeliveryOrderController@search');
	Route::get('/total/DO/{id}','DeliveryOrderController@totalDet');
	Route::get('/delete/do/{id}','DeliveryOrderController@destroy');
	Route::post('/doDet','DeliveryOrderController@storeDet');
	Route::put('/doDet/{id}','DeliveryOrderController@updateDet');
	Route::get('/delete/doDetail/{id}/{id2}','DeliveryOrderController@destroyDet');
	Route::get('/cetakDO/{id}','DeliveryOrderController@cetak');       
	
	// Invoice
	Route::get('/invoice/{kode}','InvoiceController@index');    		
	Route::get('/getDataJson/dokPenawaranInvoice', 'InvoiceController@getData');
	Route::get('/listInvoice/{kode}/{id}','InvoiceController@list');
	Route::get('/getDataJson/dokList/{id}', 'InvoiceController@getDataList');
	Route::get('/addInvoice/{kode}/{id}','InvoiceController@add');
	Route::post('/search/invoice','InvoiceController@search');
	Route::get('/generate/invoice/{id}/{id2}','InvoiceController@generate');
	Route::get('/editInvoice/{kode}/{id}/{id2}','InvoiceController@edit');    	
	Route::get('/delete/invoice/{id}','InvoiceController@destroy');
	Route::post('/addInvoice','InvoiceController@store');
	Route::post('/updateDiscInvoice','InvoiceController@storeDisc');    	
	Route::put('/addInvoice/{id}','InvoiceController@update');
	Route::post('/invoiceDet','InvoiceController@storeDet');
	Route::put('/invoiceDet/{id}','InvoiceController@updateDet');
	Route::get('/total/invoiceDetail/{id}','InvoiceController@totalDet');
	Route::get('/addDokFaktur/{kode}/{id}/{id2}','InvoiceController@upload');    
	Route::post('/saveFaktur','InvoiceController@storePO');  
	Route::get('/delete/faktur/{id}','InvoiceController@destroyFaktur');    
	Route::get('/getDataJson/dokFaktur/{id}', 'InvoiceController@getDataFaktur');
	Route::get('/cetakInvoice/{id}','InvoiceController@cetak');   

	// Kwitansi
	Route::get('/kw/{kode}','KwitansiController@index');    	
	Route::get('/getDataJson/dokListInvoice', 'KwitansiController@getDataList');
	Route::get('/editKwi/{kode}/{id}','KwitansiController@editKwi');    	
	Route::get('/addKwi/{kode}/{id}','KwitansiController@addKwi');
	Route::post('/addKwInv','KwitansiController@storeKw');
	Route::put('/addKwInv/{id}','KwitansiController@updateKw');
	Route::get('/cetakKw/{id}','KwitansiController@cetak');
	Route::get('/addBuktiKwitansi/{kode}/{id}','KwitansiController@upload');       	
	Route::get('/getDataJson/dokBT/{id}', 'KwitansiController@getDataBT');
	Route::post('/saveBT','KwitansiController@storeBT');  

	// Kwitansi tanpa Invoice
	Route::get('/kwt/{kode}','KwitansiController@kwtoninvoice');    	
	Route::get('/getDataJson/dokKwNonInvoice', 'KwitansiController@getDataKW');
	Route::get('/delete/kw/{id}','KwitansiController@destroy');
	Route::get('/addKwNonInvoice/{kode}','KwitansiController@addKWNonInvoice');
	Route::post('/addKwNonInv','KwitansiController@store');
	Route::put('/addKwNonInv/{id}','KwitansiController@update');
	Route::get('/cetakKwNonInv/{id}','KwitansiController@cetakTanpaInvoice');
	Route::post('/search/kw','KwitansiController@search');
	Route::get('/editKw/{kode}/{id}','KwitansiController@edit');  

	// Supplier
    Route::get('/Supplier','SupplierController@index');    
    Route::get('/getDataJson/supplier','SupplierController@getData');
    Route::get('/delete/supplier/{id}','SupplierController@destroy');
    Route::post('/supplier','SupplierController@store');
    Route::put('/supplier/{id}','SupplierController@update');  

	// Customer
    Route::get('/Customer','CustomerController@index');    
    Route::get('/getDataJson/customer','CustomerController@getData');
    Route::get('/delete/customer/{id}','CustomerController@destroy');
    Route::post('/customer','CustomerController@store');
    Route::put('/customer/{id}','CustomerController@update'); 
	Route::get('/send/customer/{id}','CustomerController@send');

	// Pejabat
    Route::get('/pejabat','PejabatController@index');    
    Route::get('/getDataJson/pejabat','PejabatController@getData');
    Route::get('/delete/pejabat/{id}','PejabatController@destroy');
    Route::post('/pejabat','PejabatController@store');
    Route::put('/pejabat/{id}','PejabatController@update');  	

	// Pemesanan
	Route::get('/Pemesanan/{kode}','PemesananController@index');  	
	Route::get('/getDataJson/Pemesanan/{id}', 'PemesananController@getData');
	Route::get('/delete/Pemesanan/{id}','PemesananController@destroy'); 
	Route::get('/addPemesanan/{kode}/{id}','PemesananController@add');
	Route::get('/addPemesanan/{kode}/{id}/{id2}/{id3}','PemesananController@edit');
	Route::put('/addPemesanan/{id}','PemesananController@update');
	Route::post('/addPemesanan','PemesananController@store');
	Route::post('/search/pemesananDet','PemesananController@search');
	Route::get('/total/pemesananDetail/{id}','PemesananController@totalDet');
	Route::post('/pemesananDet','PemesananController@storeDet');
	Route::put('/pemesananDet/{id}','PemesananController@updateDet');
	Route::get('/delete/pemesananDetail/{id}/{id2}','PemesananController@destroyDet');  
	Route::get('/listPesan/{kode}/{id}','PemesananController@list');
	Route::get('/addDokPendukung/{kode}/{id}','PemesananController@upload'); 
	Route::get('/getDataJson/dokBP/{id}', 'PemesananController@getDataBP');
	Route::post('/saveBP','PemesananController@storeBP');  
	Route::post('/getIdBarangKeluar','PemesananController@getIdPerkiraan');    	

	// Penerimaan
	Route::get('/Penerimaan/{kode}','PenerimaanController@index'); 
	Route::get('/getDataJson/dokPenerimaan', 'PenerimaanController@getData');
	Route::get('/listTerima/{kode}/{id}','PenerimaanController@list');
	Route::get('/getDataJson/dokTerima', 'PenerimaanController@getDataTerima');
	Route::get('/delete/dokTerima/{id}','PenerimaanController@destroyDok');
	Route::post('/saveTerima','PenerimaanController@storeTerima');  

	// Master Sosial Media
    Route::get('/masterSosmed','SosmedController@index'); 
	Route::get('/getDataJson/sosmed','SosmedController@getData');
    Route::get('/delete/sosmed/{id}','SosmedController@destroy');
    Route::post('/sosmed','SosmedController@store');
    Route::put('/sosmed/{id}','SosmedController@update'); 
	
	// Master Menu dan Harga
    Route::get('/daftarMenu','MenuController@index'); 
	Route::get('/getDataJson/menu','MenuController@getData');
    Route::get('/delete/menu/{id}','MenuController@destroy');
    Route::post('/menu','MenuController@store');
    Route::put('/menu/{id}','MenuController@update'); 
	Route::post('/update/NonAktifMenu', 'MenuController@NonAktifMenu');
	Route::post('/update/AktifMenu', 'MenuController@AktifMenu');
	
	// Master Pembelian Barang / Bahan Baku
    Route::get('/pembelianBarang','BeliBarangController@index'); 
	Route::get('/getDataJson/beliBarang','BeliBarangController@getData');
    Route::get('/delete/beliBarang/{id}','BeliBarangController@destroy');
    Route::post('/beliBarang','BeliBarangController@store');
    Route::put('/beliBarang/{id}','BeliBarangController@update'); 
	

	// ContentPlanner
    Route::get('/contentPlanner','ContentPlannerController@index'); 
	Route::get('/getDataJson/dataCalendar/{id}', 'ContentPlannerController@getData');
	Route::get('/getDataJson/dataIdea/{id}', 'ContentPlannerController@getDataIdea');
	Route::get('/getDataJson/dataGrafik/{id}', 'ContentPlannerController@getDataGrafik');
    Route::post('/contentPlanner','ContentPlannerController@store');
	Route::put('/contentPlanner/{id}','ContentPlannerController@update');
	Route::post('/contentIdea','ContentPlannerController@store2');
	Route::put('/contentIdea/{id}','ContentPlannerController@update2');
	Route::get('/delete/contentPlanner/{id}','ContentPlannerController@destroy');
	Route::get('/delete/contentIdea/{id}','ContentPlannerController@destroy');

	// Dashboard Sosial Media
    Route::get('/dashboardSosmed','DashboardSosmedController@index'); 
	Route::get('/getDataJson/dbtiktok/{id}', 'DashboardSosmedController@getDataTiktok');	
	Route::get('/getDataJson/dataGrafikTiktok/{id}', 'DashboardSosmedController@getDataGrafikTiktok');
	Route::post('/uploadVideo','DashboardSosmedController@upload');  
		
	// Master Manajemen User
    Route::get('/manajemenUser','ManajemenUserController@index'); 
	Route::get('/getDataJson/manajemenUser','ManajemenUserController@getData');
    Route::get('/delete/manajemenUser/{id}','ManajemenUserController@destroy');
    Route::post('/manajemenUser','ManajemenUserController@store');
    Route::put('/manajemenUser/{id}','ManajemenUserController@update');
	Route::post('/update/NonAktifUser', 'ManajemenUserController@NonAktifUser');
	Route::post('/update/AktifUser', 'ManajemenUserController@AktifUser');
	Route::get('/viewAkses/{id}', 'ManajemenUserController@viewAkses');
	Route::get('/getDataJson/data_hak_akses','ManajemenUserController@getDataAkses');
	