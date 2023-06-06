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
									
                                    <div role="tabpanel" class="tab-pane active">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">
													<div class="col-sm-5">
														<p><h5>Beban Pokok Produksi</h5></p>
                                                        <a href="#beban_pokok_produksi" aria-controls="beban_pokok_produksi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>										
                                                    </div>
													<!--<div class="col-sm-5">
														<p><h5>Pergerakan Bahan Baku (Rumus)</h5></p>
														<p style='color:red; font-weight:bold;'><blink>Periode Februari 2021 sd. Mei 2022</blink></p>
                                                        <a href="#pergerakan_bahan_baku" aria-controls="pergerakan_bahan_baku" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>-->
                                                    <div class="col-sm-5">
														<p><h5>Pergerakan Bahan Baku</h5></p>
														<p style='color:red; font-weight:bold;'><blink>Periode Juni 2022 sd. sekarang</blink></p>
                                                        <a href="#pergerakan_bahan_baku_penyesuaian" aria-controls="pergerakan_bahan_baku_penyesuaian" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Pergerakan Bahan Jadi (Rumus)</h5></p>
														<p style='color:red; font-weight:bold;'><blink>Periode Februari 2021 sd. Mei 2022</blink></p>
                                                        <a href="#pergerakan_bahan_jadi" aria-controls="pergerakan_bahan_jadi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Pergerakan Bahan Jadi</h5></p>
														<p style='color:red; font-weight:bold;'><blink>Periode Juni 2022 sd. sekarang</blink></p>
                                                        <a href="#pergerakan_bahan_jadi_penyesuaian" aria-controls="pergerakan_bahan_jadi_penyesuaian" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>   													
                                                </div>
                                            </div>
                                        </div>
                                    </div>

									<!-- Beban Pokok Produksi -->
									
									<div role="tabpanel" class="tab-pane" id="beban_pokok_produksi">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Beban Pokok Produksi</h3>
													<a href="beban_pokok_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/beban_pokok_produksi_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_bpp" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
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
													<div class="table-responsive" id="box-ajax-4">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

									<!-- Pergerakan Bahan Baku -->
									
                                    <div role="tabpanel" class="tab-pane" id="pergerakan_bahan_baku">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Baku</h3>
													<a href="beban_pokok_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/pergerakan_bahan_baku_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_bahan_baku" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
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
													<div class="table-responsive" id="box-ajax-5">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

                                    <!-- Pergerakan Bahan Baku Penyesuaian -->
									
                                    <div role="tabpanel" class="tab-pane" id="pergerakan_bahan_baku_penyesuaian">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Baku</h3>
													<a href="beban_pokok_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/pergerakan_bahan_baku_penyesuaian_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_bahan_baku_penyesuaian" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
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
													<div class="table-responsive" id="box-ajax-5a">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

									<!-- Pergerakan Bahan Jadi -->
									
									<div role="tabpanel" class="tab-pane" id="pergerakan_bahan_jadi">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Jadi</h3>
													<a href="beban_pokok_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/pergerakan_bahan_jadi_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_bahan_jadi" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
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
													<div class="table-responsive" id="box-ajax-6">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

                                    <!-- Pergerakan Bahan Jadi (Penyesuaian Stok) -->
									
									<div role="tabpanel" class="tab-pane" id="pergerakan_bahan_jadi_penyesuaian">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Jadi</h3>
													<a href="beban_pokok_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/pergerakan_bahan_jadi_penyesuaian_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_bahan_jadi_penyesuaian" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
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
													<div class="table-responsive" id="box-ajax-6c">													
													
                    
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


		<!-- Script Beban Pokok Produksi -->

		<script type="text/javascript">	
		$('#filter_date_bpp').daterangepicker({
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

		$('#filter_date_bpp').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				TableBebanPokokProduksi();
		});


		function TableBebanPokokProduksi()
		{
			$('#wait').fadeIn('fast');   
			$.ajax({
				type    : "POST",
				url     : "<?php echo site_url('pmm/reports/beban_pokok_produksi'); ?>/"+Math.random(),
				dataType : 'html',
				data: {
					filter_date : $('#filter_date_bpp').val(),
				},
				success : function(result){
					$('#box-ajax-4').html(result);
					$('#wait').fadeOut('fast');
				}
			});
		}

		//TableBebanPokokProduksi();
		
		</script>
		
		<!-- Script Pergerakan Bahan Baku -->

		<script type="text/javascript">
		$('#filter_date_bahan_baku').daterangepicker({
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

		$("#filter_date_bahan_baku").daterangepicker({
			autoUpdateInput : false,
			showDropdowns: true,
			locale: {
				format: 'DD-MM-YYYY'
			},
			minDate: new Date(2021, 01, 27), 
			maxDate: new Date(2022, 04, 31)		
		});

		$('#filter_date_bahan_baku').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				TablePergerakanBahanBaku();
		});


		function TablePergerakanBahanBaku()
		{
			$('#wait').fadeIn('fast');   
			$.ajax({
				type    : "POST",
				url     : "<?php echo site_url('pmm/reports/pergerakan_bahan_baku'); ?>/"+Math.random(),
				dataType : 'html',
				data: {
					filter_date : $('#filter_date_bahan_baku').val(),
				},
				success : function(result){
					$('#box-ajax-5').html(result);
					$('#wait').fadeOut('fast');
				}
			});
		}

		//TablePergerakanBahanBaku();

		</script>

		<!-- Script Pergerakan Bahan Baku Penyesuaian -->

		<script type="text/javascript">
		$('#filter_date_bahan_baku_penyesuaian').daterangepicker({
		autoUpdateInput : false,
		showDropdowns: true,
		locale: {
			format: 'DD-MM-YYYY'
		},
		minDate: new Date(2022, 05, 01),
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(30, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
		});

		$('#filter_date_bahan_baku_penyesuaian').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				TablePergerakanBahanBakuPenyesuaian();
		});


		function TablePergerakanBahanBakuPenyesuaian()
		{
			$('#wait').fadeIn('fast');   
			$.ajax({
				type    : "POST",
				url     : "<?php echo site_url('pmm/reports/pergerakan_bahan_baku_penyesuaian'); ?>/"+Math.random(),
				dataType : 'html',
				data: {
					filter_date : $('#filter_date_bahan_baku_penyesuaian').val(),
				},
				success : function(result){
					$('#box-ajax-5a').html(result);
					$('#wait').fadeOut('fast');
				}
			});
		}

		//TablePergerakanBahanBakuPenyesuaian();

		</script>

		<!-- Script Pergerakan Bahan Jadi -->
			
		<script type="text/javascript">
		$('#filter_date_bahan_jadi').daterangepicker({
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

		$("#filter_date_bahan_jadi").daterangepicker({
			autoUpdateInput : false,
			showDropdowns: true,
			locale: {
				format: 'DD-MM-YYYY'
			},
			minDate: new Date(2021, 01, 27), 
			maxDate: new Date(2022, 04, 31)		
		});

		$('#filter_date_bahan_jadi').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				TablePergerakanBahanJadi();
		});
		
		function TablePergerakanBahanJadi()
		{
			$('#wait').fadeIn('fast');   
			$.ajax({
				type    : "POST",
				url     : "<?php echo site_url('pmm/reports/pergerakan_bahan_jadi'); ?>/"+Math.random(),
				dataType : 'html',
				data: {
					filter_date : $('#filter_date_bahan_jadi').val(),
				},
				success : function(result){
					$('#box-ajax-6').html(result);
					$('#wait').fadeOut('fast');
				}
			});
		}

		//TablePergerakanBahanJadi();
		
		</script>

		<!-- Script Pergerakan Bahan Jadi (Penyesuaian Stok) -->
		
		<script type="text/javascript">
		$('#filter_date_bahan_jadi_penyesuaian').daterangepicker({
			autoUpdateInput : false,
			showDropdowns: true,
			locale: {
				format: 'DD-MM-YYYY'
			},
			minDate: new Date(2022, 05, 01),
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(30, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		});

		$('#filter_date_bahan_jadi_penyesuaian').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				TablePergerakanBahanJadiPenyesuaian();
		});
		
		function TablePergerakanBahanJadiPenyesuaian()
		{
			$('#wait').fadeIn('fast');   
			$.ajax({
				type    : "POST",
				url     : "<?php echo site_url('pmm/reports/pergerakan_bahan_jadi_penyesuaian'); ?>/"+Math.random(),
				dataType : 'html',
				data: {
					filter_date : $('#filter_date_bahan_jadi_penyesuaian').val(),
				},
				success : function(result){
					$('#box-ajax-6c').html(result);
					$('#wait').fadeOut('fast');
				}
			});
		}

		//TablePergerakanBahanJadiPenyesuaian();
		
		</script>



</body>

</html>