<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>
	<style type="text/css">
		.mytable thead th {
		  background-color:	#e69500;
		  vertical-align: middle;
          color: black;
		}
		
		.mytable tbody td {
            vertical-align: middle;
            color: black;
		}
		
		.mytable tfoot td {
            vertical-align: middle;
            color: black;
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
														<p><h5>Laporan Evaluasi Kapasitas Produksi</h5></p>
                                                        <a href="#laporan_evaluasi_produksi" aria-controls="laporan_evaluasi_produksi" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Evaluasi Nilai Persediaan</h5></p>
                                                        <a href="#evaluasi_nilai_persediaan" aria-controls="evaluasi_nilai_persediaan" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
													</div>        													
                                                </div>
                                            </div>
                                        </div>
                                    </div>

									<!-- Laporan Evaluasi Produksi -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_evaluasi_produksi">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Evaluasi Kapasitas Produksi</h3>
													<a href="laporan_ev._produksi">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/laporan_evaluasi_produksi_print'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_evaluasi" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table-hover table-center table-condensed" id="table-evaluasi-produksi" style="display:none" width="100%";>
                                                            <thead>
																<th align="center" rowspan="2">NO.</th>
																<th align="center">TANGGAL</th>
																<th align="center">NOMOR PRODUKSI</th>
																<th align="center">DURASI PRODUKSI (JAM)</th>
																<th align="center">PEMAKAIAN BAHAN BAKU (TON)</th>
																<th align="center">KAPASITAS PRODUKSI (TON/JAM)</th>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-condensed"></tfoot>
                                                        </table>
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
													<a href="laporan_ev._produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/evaluasi_nilai_persediaan_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_evaluasi_nilai_persediaan" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-default" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i>  Print</button>
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


		<!-- Script Laporan Evaluasi -->
			
		<script type="text/javascript">
		$('input.numberformat').number(true, 4, ',', '.');
		$('#filter_date_evaluasi').daterangepicker({
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

		$('#filter_date_evaluasi').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
			TableEvaluasiProduksi();
		});

		function TableEvaluasiProduksi() {
			$('#table-evaluasi-produksi').show();
			$('#loader-table').fadeIn('fast');
			$('#table-evaluasi-produksi tbody').html('');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('pmm/receipt_material/table_date8'); ?>/" + Math.random(),
				dataType: 'json',
				data: {
					filter_date: $('#filter_date_evaluasi').val(),
				},
				success: function(result) {
					if (result.data) {
						$('#table-evaluasi-produksi tbody').html('');

						if (result.data.length > 0) {
							$.each(result.data, function(i, val) {
								$('#table-evaluasi-produksi tbody').append('<tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.date_prod + '</td><td class="text-left">' + val.no_prod + '</td><td class="text-center""><b>' + val.jumlah_duration + '</b></td><td class="text-center"><b>' + val.jumlah_used + '</b></td><td class="text-center"><b>' + val.jumlah_capacity + '</b></td></tr>');
								$.each(val.mats, function(a, row) {
									var a_no = a + 1;
									$('#table-evaluasi-produksi tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center" rowspan=""></td><td class="text-center">' + row.date_prod + '</td><td class="text-center">' + row.duration + '</td><td class="text-center">' + row.used + '</td><td class="text-center">' + row.capacity + '</td></tr>');
								});
							});
						} else {
							$('#table-evaluasi-produksi tbody').append('<tr><td class="text-center" colspan="5"><b>Tidak Ada Data</b></td></tr>');
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