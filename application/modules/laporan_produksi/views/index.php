<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>
	<style type="text/css">
		.mytable thead th {
		  background-color:	#e69500;
		  color: #ffffff;
		  text-align: center;
		  vertical-align: middle;
		  padding: 5px;
		}
		
		.mytable tbody td {
		  padding: 5px;
		}
		
		.mytable tfoot td {
		  background-color:	#e69500;
		  color: #FFFFFF;
		  padding: 5px;
		}
		blink {
		-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
		animation: 2s linear infinite kedip;
		}
		/* for Safari 4.0 - 8.0 */
		@-webkit-keyframes kedip { 
		0% {
			visibility: hidden;
		}
		50% {
			visibility: hidden;
		}
		100% {
			visibility: visible;
		}
		}
		@keyframes kedip {
		0% {
			visibility: hidden;
		}
		50% {
			visibility: hidden;
		}
		100% {
			visibility: visible;
		}
		}
    </style>
</head>

<body>
    <div class="wrap">

        <?php echo $this->Templates->PageHeader(); ?>

        <div class="page-body">
            <?php echo $this->Templates->LeftBar(); ?>
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-bar-chart" aria-hidden="true"></i>Laporan</li>
                            <li><a><?php echo $row[0]->menu_name; ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-content">
								<div class="panel-header">
									<h3 class="section-subtitle"><?php echo $row[0]->menu_name; ?></h3>
								</div>
                                <div class="tab-content">
									
									<!-- Laporan Laba Rugi -->
                                    <div role="tabpanel" class="tab-pane active" id="laba_rugi">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Laporan Produksi Harian</h5></p>
                                                        <a href="#laporan_produksi" aria-controls="laporan_produksi" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>										
                                                    </div>
                                                    <div class="col-sm-5">
														<p><h5>Laporan Produksi Campuran</h5></p>
                                                        <a href="#laporan_produksi_campuran" aria-controls="laporan_produksi_campuran" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>										
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Rekapitulasi Laporan Produksi</h5></p>
                                                        <a href="#rekapitulasi_laporan_produksi" aria-controls="rekapitulasi_laporan_produksi" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
													</div>
													<!--<div class="col-sm-5">
														<p><h5>Nilai Persediaan Bahan Baku</h5></p>
                                                        <a href="#nilai_persediaan_bahan_baku" aria-controls="nilai_persediaan_bahan_baku" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Nilai Persediaan Bahan Jadi</h5></p>
                                                        <a href="#nilai_persediaan_bahan_jadi" aria-controls="nilai_persediaan_bahan_jadi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>-->      													
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									
                                    <!-- Laporan Produksi -->

									<div role="tabpanel" class="tab-pane" id="laporan_produksi">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Produksi Harian</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/laporan_produksi_harian_print'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>                                                           
                                                            <div class="col-sm-3">
                                                                <button class="btn btn-default" type="submit" id="btn-print" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i> Print</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <br />
                                                    <div id="box-print" class="table-responsive">
                                                        <div id="loader-table" class="text-center" style="display:none">
                                                            <img src="<?php echo base_url(); ?>assets/back/theme/images/loader.gif">
                                                            <div>
                                                                Please Wait
                                                            </div>
                                                        </div>
                                                        <table class="mytable table-hover table-center table-condensed" id="table-date8a" style="display:none" width="100%";>
                                                            <thead>
																<th align="center">No</th>
																<th align="center">Tanggal</th>
																<th align="center">Durasi Produksi (Jam)</th>
																<th align="center">Pemakaian Bahan (Ton)</th>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>

                                    <!-- Laporan Campuran -->
                                    
									<div role="tabpanel" class="tab-pane" id="laporan_produksi_campuran">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Produksi Campuran</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/laporan_produksi_campuran_print'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_campuran" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>                                                           
                                                            <div class="col-sm-3">
                                                                <button class="btn btn-default" type="submit" id="btn-print" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i> Print</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <br />
                                                    <div id="box-print" class="table-responsive">
                                                        <div id="loader-table" class="text-center" style="display:none">
                                                            <img src="<?php echo base_url(); ?>assets/back/theme/images/loader.gif">
                                                            <div>
                                                                Please Wait
                                                            </div>
                                                        </div>
                                                        <table class="mytable table-hover table-center table-condensed" id="table-date-campuran" style="display:none" width="100%";>
                                                            <thead>
																<th align="center">No</th>
																<th align="center">Tanggal</th>
                                                                <th align="center">Produksi Campuran</th>
                                                                <th align="center">Satuan</th>
																<th align="center">Volume</th>
																<th align="center">Fraksi</th>
																<th align="center">Komposisi</th>
																<th align="center">Volume</th>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>
									
									<!-- Rekaputulasi -->
									
									<div role="tabpanel" class="tab-pane" id="rekapitulasi_laporan_produksi">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Rekapitulasi Laporan Produksi</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/rekapitulasi_laporan_produksi_print'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_rekapitulasi" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>                                                           
                                                            <div class="col-sm-3">
                                                                <button class="btn btn-default" type="submit" id="btn-print" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i> Print</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <br />
                                                    <div id="box-print" class="table-responsive">
                                                        <div id="loader-table" class="text-center" style="display:none">
                                                            <img src="<?php echo base_url(); ?>assets/back/theme/images/loader.gif">
                                                            <div>
                                                                Please Wait
                                                            </div>
                                                        </div>
                                                        <table class="mytable table-hover table-center table-condensed" id="table-date8b" style="display:none" width="100%";>
                                                            <thead>
																<th align="center">No</th>
																<th align="center">Uraian</th>
																<th align="center">Satuan</th>
																<th align="center">Presentase</th>
																<th align="center">Volume</th>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>

									<!-- Nilai Persediaan Barang -->
									
                                    <div role="tabpanel" class="tab-pane" id="nilai_persediaan_bahan_baku">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Nilai Persediaan Bahan Baku</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/nilai_persediaan_bahan_baku_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_nilai" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-default"><i class="fa fa-print"></i>  Print</button>
															</div>
														</form>
														
													</div>
													<br />
													<div id="wait" style=" text-align: center; align-content: center; display: none;">	
														<div>Please Wait</div>
														<div class="fa-3x">
														  <i class="fa fa-spinner fa-spin"></i>
														</div>
													</div>				
													<div class="table-responsive" id="box-ajax-3">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

									<!-- Nilai Persediaan Bahan Jadi -->
									
									<div role="tabpanel" class="tab-pane" id="nilai_persediaan_bahan_jadi">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Nilai Persediaan Bahan Jadi</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/nilai_persediaan_bahan_jadi_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_nilai_bahan_jadi" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-default"><i class="fa fa-print"></i>  Print</button>
															</div>
														</form>
														
													</div>
													<br />
													<div id="wait" style=" text-align: center; align-content: center; display: none;">	
														<div>Please Wait</div>
														<div class="fa-3x">
														  <i class="fa fa-spinner fa-spin"></i>
														</div>
													</div>				
													<div class="table-responsive" id="box-ajax-6d">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>
									
									
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <?php echo $this->Templates->Footer(); ?>

        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>
        <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date').daterangepicker({
                autoUpdateInput: false,
				showDropdowns : true,
                locale: {
                    format: 'DD-MM-YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate8a();
            });

            function TableDate8a() {
                $('#table-date8a').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date8a tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date8a'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date8a tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date8a tbody').append('<tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-center"><b>' + val.date_prod + '</b></td><td class="text-center"><b>' + val.jumlah_duration + '</b></td><td class="text-center"><b>' + val.jumlah_used + '</b></td></tr>');
                                    //$('#table-date8a tbody').append('<tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-center"><b>' + val.date_prod + '</b></td><td class="text-center"><b>' + val.jumlah_duration + '</b></td><td class="text-center"><b>' + val.jumlah_used + '</b></td><td class="text-left">' + val.produk_a + '</td><td class="text-center">' + val.presentase_a + '</td><td class="text-center">' + val.measure_a + '</td><td class="text-center">' + val.jumlah_pemakaian_a + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_b + '</td><td class="text-center">' + val.presentase_b + '</td><td class="text-center">' + val.measure_b + '</td><td class="text-center">' + val.jumlah_pemakaian_b + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_c + '</td><td class="text-center">' + val.presentase_c + '</td><td class="text-center">' + val.measure_c + '</td><td class="text-center">' + val.jumlah_pemakaian_c + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_d + '</td><td class="text-center">' + val.presentase_d + '</td><td class="text-center">' + val.measure_d + '</td><td class="text-center">' + val.jumlah_pemakaian_d + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_e + '</td><td class="text-center">' + val.presentase_e + '</td><td class="text-center">' + val.measure_e + '</td><td class="text-center">' + val.jumlah_pemakaian_e + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="6">' + 'TOTAL' + '</td><td class="text-center">' + val.measure_e + '</td><td class="text-center">' + val.jumlah_used + '</td></tr>');
                                });
                            } else {
                                $('#table-date8a tbody').append('<tr><td class="text-center" colspan="8"><b>Tidak Ada Data</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowLaporanProduksi(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            </script>

            <!-- Script Laporan Produksi Campuran -->

            <script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_campuran').daterangepicker({
                autoUpdateInput: false,
				showDropdowns : true,
                locale: {
                    format: 'DD-MM-YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $('#filter_date_campuran').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDateCampuran();
            });

            function TableDateCampuran() {
                $('#table-date-campuran').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date-campuran tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date_campuran'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_campuran').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date-campuran tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date-campuran tbody').append('<tr onclick="NextShowLaporanProduksiCampuran(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-center"><b>' + val.date_prod + '</b></td><td class="text-center"><b>' + val.agregat + '</b></td><td class="text-center"><b>' + val.satuan + '</b></td><td class="text-center"><b>' + val.volume + '</b></td><td class="text-left">' + val.produk_a + '</td><td class="text-center">' + val.presentase_a + ' %</td><td class="text-center">' + val.jumlah_pemakaian_a + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="5"></td><td class="text-left">' + val.produk_b + '</td><td class="text-center">' + val.presentase_b + ' %</td><td class="text-center">' + val.jumlah_pemakaian_b + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="5"></td><td class="text-left">' + val.produk_c + '</td><td class="text-center">' + val.presentase_c + ' %</td><td class="text-center">' + val.jumlah_pemakaian_c + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="5"></td><td class="text-left">' + val.produk_d + '</td><td class="text-center">' + val.presentase_d + ' %</td><td class="text-center">' + val.jumlah_pemakaian_d + '</td></tr>');
                                });
                            } else {
                                $('#table-date-campuran tbody').append('<tr><td class="text-center" colspan="7"><b>Tidak Ada Data</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowLaporanProduksiCampuran(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }
            </script>

			<!-- Script Rekepitulasi -->
			
            <script type="text/javascript">
			$('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_rekapitulasi').daterangepicker({
                autoUpdateInput: false,
				showDropdowns : true,
                locale: {
                    format: 'DD-MM-YYYY'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $('#filter_date_rekapitulasi').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate8b();
            });

            function TableDate8b() {
                $('#table-date8b').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date8b tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date8b'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_rekapitulasi').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date8b tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date8b tbody').append('<tr onclick="NextShowRekapitulasiLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + 1 + '</td><td class="text-left">' + val.produk_a + '</td><td class="text-center">' + val.measure_a + '</td><td class="text-center">' + val.presentase_a + ' %</td><td class="text-center">' + val.jumlah_pemakaian_a + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + 2 + '</td><td class="text-left">' + val.produk_b + '</td><td class="text-center">' + val.measure_b + '</td><td class="text-center">' + val.presentase_b + ' %</td><td class="text-center">' + val.jumlah_pemakaian_b + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + 3 + '</td><td class="text-left">' + val.produk_c + '</td><td class="text-center">' + val.measure_c + '</td><td class="text-center">' + val.presentase_c + ' %</td><td class="text-center">' + val.jumlah_pemakaian_c + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + 4 + '</td><td class="text-left">' + val.produk_d + '</td><td class="text-center">' + val.measure_d + '</td><td class="text-center">' + val.presentase_d + ' %</td><td class="text-center">' + val.jumlah_pemakaian_d + '</td><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + 5 + '</td><td class="text-left">' + val.produk_e + '</td><td class="text-center">' + val.measure_e + '</td><td class="text-center">' + val.presentase_e + ' %</td><td class="text-center">' + val.jumlah_pemakaian_e + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="2">' + 'TOTAL' + '</td><td class="text-center">' + val.measure_a + '</td><td class="text-center">' + val.jumlah_presentase + ' %</td><td class="text-center">' + result.total + '</td></tr>');                                
                                });
                            } else {
                                $('#table-date8b tbody').append('<tr><td class="text-center" colspan="8"><b>Tidak Ada Data</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowRekapitulasiLaporanProduksi(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }
            </script>

			<!-- Script Nilai Persediaan Barang -->

			<script type="text/javascript">
			$('#filter_date_nilai').daterangepicker({
            autoUpdateInput : false,
			showDropdowns: true,
            locale: {
              format: 'DD-MM-YYYY'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(30, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					}
				});

				$('#filter_date_nilai').on('apply.daterangepicker', function(ev, picker) {
					  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
					  TableNilaiPersediaanBahanBaku();
				});


				function TableNilaiPersediaanBahanBaku()
				{
					$('#wait').fadeIn('fast');   
					$.ajax({
						type    : "POST",
						url     : "<?php echo site_url('pmm/reports/nilai_persediaan_bahan_baku'); ?>/"+Math.random(),
						dataType : 'html',
						data: {
							filter_date : $('#filter_date_nilai').val(),
						},
						success : function(result){
							$('#box-ajax-3').html(result);
							$('#wait').fadeOut('fast');
						}
					});
				}

				//TableNilaiPersediaanBahanBaku();
			
            </script>

			<!-- Script Nilai Persediaan Bahan Jadi -->
			
            <script type="text/javascript">
			$('#filter_date_nilai_bahan_jadi').daterangepicker({
				autoUpdateInput : false,
				showDropdowns: true,
				locale: {
				  format: 'DD-MM-YYYY'
				},
				ranges: {
				   'Today': [moment(), moment()],
				   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				   'Last 30 Days': [moment().subtract(30, 'days'), moment()],
				   'This Month': [moment().startOf('month'), moment().endOf('month')],
				   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
			});

			$('#filter_date_nilai_bahan_jadi').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  TableNilaiPersediaanBahanJadi();
			});
			
			function TableNilaiPersediaanBahanJadi()
			{
				$('#wait').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/nilai_persediaan_bahan_jadi'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_nilai_bahan_jadi').val(),
					},
					success : function(result){
						$('#box-ajax-6d').html(result);
						$('#wait').fadeOut('fast');
					}
				});
			}

			//TableNilaiPersediaanBahanJadi();
			
            </script>			

</body>

</html>