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
				
								
                                    <div role="tabpanel" class="tab-pane active" id="bisnis">
                                        
										<div role="tabpanel" class="tab-pane active" id="pembelian">
                                        <br />
											<div class="row">
												<div width="100%">
													<div class="panel panel-default">
													<div class="col-sm-5">
                                                            <p><h5>Rencana Kerja</h5></p>
                                                            <a href="#rencana_kerja" aria-controls="rencana_kerja" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
														</div>	                                       
                                                        <div class="col-sm-5">
                                                            <p><h5>Prognosa Produksi</h5></p>
                                                            <a href="#prognosa_produksi" aria-controls="prognosa_produksi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
														</div>											
													</div>
												</div>
											</div>
										</div>
                                    </div>

									<!-- Rencana Kerja -->
                                    <div role="tabpanel" class="tab-pane" id="rencana_kerja">
                                        <div class="col-sm-15">
											<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Rencana Kerja</h3>
													<a href="laporan_rencana_kerja">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/cetak_rencana_kerja');?>" target="_blank">
															<!--<div class="col-sm-3">
																<input type="text" id="filter_date_rencana_kerja" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>-->
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
															</div>
														</form>
														
													</div>
													<br />
													<div id="wait-rencana-kerja" style=" text-align: center; align-content: center; display: none;">	
														<div>Please Wait</div>
														<div class="fa-3x">
														  <i class="fa fa-spinner fa-spin"></i>
														</div>
													</div>				
													<div class="table-responsive" id="rencana-kerja">
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

                                    <!-- Prognosa Produksi -->
                                    <div role="tabpanel" class="tab-pane" id="prognosa_produksi">
                                        <div class="col-sm-15">
											<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Prognosa Produksi</h3>
													<a href="laporan_rencana_kerja">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/cetak_prognosa_produksi');?>" target="_blank">
															<!--<div class="col-sm-3">
																<input type="text" id="filter_date_prognosa_produksi" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>-->
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
															</div>
														</form>
														
													</div>
													<br />
													<div id="wait-prognosa-produksi" style=" text-align: center; align-content: center; display: none;">	
														<div>Please Wait</div>
														<div class="fa-3x">
														  <i class="fa fa-spinner fa-spin"></i>
														</div>
													</div>				
													<div class="table-responsive" id="prognosa-produksi">
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
	</div>

        <?php echo $this->Templates->Footer(); ?>

        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>
        <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
        
		<!-- Script Rencana Kerja -->
		<script type="text/javascript">
			$('#filter_date_rencana_kerja').daterangepicker({
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

			$('#filter_date_rencana_kerja').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  RencanaKerja();
			});


			function RencanaKerja()
			{
				$('#wait-rencana-kerja').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/rencana_kerja'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_rencana_kerja').val(),
					},
					success : function(result){
						$('#rencana-kerja').html(result);
						$('#wait-rencana-kerja').fadeOut('fast');
					}
				});
			}

			RencanaKerja();

        </script>
		
		<!-- Script Prognosa Produksi -->
		<script type="text/javascript">
			$('#filter_date_prognosa_produksi').daterangepicker({
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

			$('#filter_date_prognosa_produksi').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  PrognosaProduksi();
			});


			function PrognosaProduksi()
			{
				$('#wait-prognosa-produksi').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/prognosa_produksi'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_prognosa_produksi').val(),
					},
					success : function(result){
						$('#prognosa-produksi').html(result);
						$('#wait-prognosa-produksi').fadeOut('fast');
					}
				});
			}

			PrognosaProduksi();

        </script>

</body>
</html>