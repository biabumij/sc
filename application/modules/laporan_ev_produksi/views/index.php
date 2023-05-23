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
									
                                    <div role="tabpanel" class="tab-pane active" id="laba_rugi">
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
                                    $('#table-date8a tbody').append('<tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-center"><b>' + val.date_prod + '</b></td><td class="text-center"><b>' + val.jumlah_duration + '</b></td><td class="text-center"><b>' + val.jumlah_used + '</b></td><td class="text-left">' + val.produk_a + '</td><td class="text-center">' + val.presentase_a + '</td><td class="text-center">' + val.measure_a + '</td><td class="text-center">' + val.jumlah_pemakaian_a + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_b + '</td><td class="text-center">' + val.presentase_b + '</td><td class="text-center">' + val.measure_b + '</td><td class="text-center">' + val.jumlah_pemakaian_b + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_c + '</td><td class="text-center">' + val.presentase_c + '</td><td class="text-center">' + val.measure_c + '</td><td class="text-center">' + val.jumlah_pemakaian_c + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_d + '</td><td class="text-center">' + val.presentase_d + '</td><td class="text-center">' + val.measure_d + '</td><td class="text-center">' + val.jumlah_pemakaian_d + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="4"></td><td class="text-left">' + val.produk_e + '</td><td class="text-center">' + val.presentase_e + '</td><td class="text-center">' + val.measure_e + '</td><td class="text-center">' + val.jumlah_pemakaian_e + '</td></tr><tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center" colspan="6">' + 'TOTAL' + '</td><td class="text-center">' + val.measure_e + '</td><td class="text-center">' + val.jumlah_used + '</td></tr>');
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
                TableDate8();
            });

            function TableDate8() {
                $('#table-date8').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date8 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date8'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_evaluasi').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date8 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date8 tbody').append('<tr onclick="NextShowLaporanProduksi(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.date_prod + '</td><td class="text-center">' + val.no_prod + '</td><td class="text-center""><b>' + val.jumlah_duration + '</b></td><td class="text-center"><b>' + val.jumlah_used + '</b></td><td class="text-center"><b>' + val.jumlah_capacity + '</b></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date8 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center" rowspan=""></td><td class="text-center">' + row.date_prod + '</td><td class="text-center">' + row.duration + '</td><td class="text-center">' + row.used + '</td><td class="text-center">' + row.capacity + '</td></tr>');
                                    });
                                });
                            } else {
                                $('#table-date8 tbody').append('<tr><td class="text-center" colspan="5"><b>Tidak Ada Data</b></td></tr>');
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