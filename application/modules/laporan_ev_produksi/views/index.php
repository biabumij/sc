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
														<p><h5>Evaluasi Nilai Persediaan</h5></p>
                                                        <a href="#evaluasi_nilai_persediaan" aria-controls="evaluasi_nilai_persediaan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>        													
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Evaluasi Nilai Persediaan -->
									
									<div role="tabpanel" class="tab-pane" id="evaluasi_nilai_persediaan">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Evaluasi Nilai Persediaan</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/evaluasi_nilai_persediaan_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_evaluasi_nilai_persediaan" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
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
													<div class="table-responsive" id="box-ajax-6b">													
													
                    
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
            </script>

            <!-- Script Evaluasi Nilai Persediaan -->
			
            <script type="text/javascript">
			$('#filter_date_evaluasi_nilai_persediaan').daterangepicker({
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

			$('#filter_date_evaluasi_nilai_persediaan').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  TableEvaluasiNilaiPersediaan();
			});
			
			function TableEvaluasiNilaiPersediaan()
			{
				$('#wait').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/evaluasi_nilai_persediaan'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_evaluasi_nilai_persediaan').val(),
					},
					success : function(result){
						$('#box-ajax-6b').html(result);
						$('#wait').fadeOut('fast');
					}
				});
			}

			//TableEvaluasiNilaiPersediaan();
			
            </script>			

</body>

</html>