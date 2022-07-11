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
									
									<!-- Laporan Laba Rugi -->
                                    <div role="tabpanel" class="tab-pane active" id="laba_rugi">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Laporan Produksi Harian</h5></p>
														<p>Menampilkan laporan produksi harian yang dicatat dalam suatu periode.</p>
                                                        <a href="#laporan_produksi" aria-controls="laporan_produksi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>										
                                                    </div>
                                                    <div class="col-sm-5">
														<p><h5>Laporan Produksi Campuran</h5></p>
														<p>Menampilkan laporan produksi campuran yang dicatat dalam suatu periode.</p>
                                                        <a href="#laporan_produksi_campuran" aria-controls="laporan_produksi_campuran" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>										
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Laporan Evaluasi Kapasitas Produksi</h5></p>
														<p>Menampilkan laporan evaluasi produksi yang dicatat dalam suatu periode.</p>
                                                        <a href="#laporan_evaluasi_produksi" aria-controls="laporan_evaluasi_produksi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Rekapitulasi Laporan Produksi</h5></p>
														<p>Menampilkan rekapitulasi laporan produksi yang dicatat dalam suatu periode.</p>
                                                        <a href="#rekapitulasi_laporan_produksi" aria-controls="rekapitulasi_laporan_produksi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Pergerakan Bahan Baku</h5></p>
														<p>Menampilkan pergerakan bahan baku dalam suatu periode.</p>
                                                        <a href="#pergerakan_bahan_baku" aria-controls="pergerakan_bahan_baku" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Pergerakan Bahan Jadi</h5></p>
														<p>Menampilkan pergerakan bahan jadi dalam suatu periode.</p>
                                                        <a href="#pergerakan_bahan_jadi" aria-controls="pergerakan_bahan_jadi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Pergerakan Bahan Jadi (Stok)</h5></p>
														<p>Menampilkan pergerakan bahan jadi (stok) dalam suatu periode.</p>
                                                        <a href="#pergerakan_bahan_jadi_stok" aria-controls="pergerakan_bahan_jadi_stok" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Pergerakan Bahan Jadi (Penyesuaian Stok)</h5></p>
														<p>Menampilkan pergerakan bahan jadi (Penyesuaian Stok) dalam suatu periode.</p>
                                                        <a href="#pergerakan_bahan_jadi_penyusuaian" aria-controls="pergerakan_bahan_jadi_penyusuaian" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Evaluasi Nilai Persediaan</h5></p>
														<p>Menampilkan evaluasi nilai persediaan dalam suatu periode.</p>
                                                        <a href="#evaluasi_nilai_persediaan" aria-controls="evaluasi_nilai_persediaan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Nilai Persediaan Barang</h5></p>
														<p>Menampilkan nilai persediaan barang dalam suatu periode.</p>
                                                        <a href="#nilai_persediaan_barang" aria-controls="nilai_persediaan_barang" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>                                          
                                                    <div class="col-sm-5">
														<p><h5>Beban Pokok Produksi</h5></p>
														<p>Menampilkan beban pokok produksi dalam suatu periode.</p>
                                                        <a href="#beban_pokok_produksi" aria-controls="beban_pokok_produksi" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>										
                                                    </div>														
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
                                                                <button class="btn btn-info" type="submit" id="btn-print"><i class="fa fa-print"></i> Print</button>
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
																<th align="center">Fraksi / Aggregat</th>
																<th align="center">Presentase</th>
																<th align="center">Satuan</th>
																<th align="center">Bahan Jadi</th>
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
                                                                <button class="btn btn-info" type="submit" id="btn-print"><i class="fa fa-print"></i> Print</button>
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
									
									<!-- Laporan Evaluasi Produksi -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_evaluasi_produksi">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Evaluasi Kapasitas Produksi</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/laporan_evaluasi_produksi_print'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_evaluasi" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>                                                           
                                                            <div class="col-sm-3">
                                                                <button class="btn btn-info" type="submit" id="btn-print"><i class="fa fa-print"></i> Print</button>
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
                                                        <table class="mytable table-hover table-center table-condensed" id="table-date8" style="display:none" width="100%";>
                                                            <thead>
																<th align="center" rowspan="2">No</th>
																<th align="center">Tanggal</th>
																<th align="center">Nomor Produksi / Tanggal Produksi</th>
																<th align="center">Durasi Produksi (Jam)</th>
																<th align="center">Pemakaian Bahan Baku (Ton)</th>
																<th align="center">Kapasitas Produksi (Ton/Jam)</th>
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
                                                                <button class="btn btn-info" type="submit" id="btn-print"><i class="fa fa-print"></i> Print</button>
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
									
									<!-- Pergerakan Bahan Baku-->
									
                                    <div role="tabpanel" class="tab-pane" id="pergerakan_bahan_baku">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Baku</h3>
													<a href="laporan_produksi">Kembali</a>
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
									
									<!-- Pergerakan Bahan Jadi -->
									
									<div role="tabpanel" class="tab-pane" id="pergerakan_bahan_jadi">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Jadi</h3>
													<a href="laporan_produksi">Kembali</a>
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

                                    <!-- Pergerakan Bahan Jadi (Stok) -->
									
									<div role="tabpanel" class="tab-pane" id="pergerakan_bahan_jadi_stok">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Jadi (Stok)</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/pergerakan_bahan_jadi_stok_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_bahan_jadi_stok" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
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
													<div class="table-responsive" id="box-ajax-6a">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>

                                    <!-- Pergerakan Bahan Jadi (Penyesuaian Stok) -->
									
									<div role="tabpanel" class="tab-pane" id="pergerakan_bahan_jadi_penyusuaian">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Pergerakan Bahan Jadi (Penyesuaian Stok)</h3>
													<a href="laporan_produksi">Kembali</a>
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
									
									<!-- Nilai Persediaan Barang -->
									
                                    <div role="tabpanel" class="tab-pane" id="nilai_persediaan_barang">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Nilai Persediaan Barang</h3>
													<a href="laporan_produksi">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/nilai_persediaan_barang_print');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_nilai" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
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
													<div class="table-responsive" id="box-ajax-3">													
													
                    
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
													<a href="laporan_produksi">Kembali</a>
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
									
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
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
                                $('#table-date8a tbody').append('<tr><td class="text-center" colspan="8"><b>No Data</b></td></tr>');
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
                                $('#table-date-campuran tbody').append('<tr><td class="text-center" colspan="7"><b>No Data</b></td></tr>');
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
                                $('#table-date8 tbody').append('<tr><td class="text-center" colspan="5"><b>No Data</b></td></tr>');
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
                                $('#table-date8b tbody').append('<tr><td class="text-center" colspan="8"><b>No Data</b></td></tr>');
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

            <!-- Script Pergerakan Bahan Jadi (Stok) -->
			
            <script type="text/javascript">
			$('#filter_date_bahan_jadi_stok').daterangepicker({
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

			$('#filter_date_bahan_jadi_stok').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  TablePergerakanBahanJadiStok();
			});
			
			function TablePergerakanBahanJadiStok()
			{
				$('#wait').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/reports/pergerakan_bahan_jadi_stok'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_bahan_jadi_stok').val(),
					},
					success : function(result){
						$('#box-ajax-6a').html(result);
						$('#wait').fadeOut('fast');
					}
				});
			}

			//TablePergerakanBahanJadiStok();
			
            </script>

             <!-- Script Pergerakan Bahan Jadi (Penyesuaian Stok) -->
			
             <script type="text/javascript">
			$('#filter_date_bahan_jadi_penyesuaian').daterangepicker({
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
					  TableNilaiPersediaanBarang();
				});


				function TableNilaiPersediaanBarang()
				{
					$('#wait').fadeIn('fast');   
					$.ajax({
						type    : "POST",
						url     : "<?php echo site_url('pmm/reports/nilai_persediaan_barang'); ?>/"+Math.random(),
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

				//TableNilaiPersediaanBarang();
			
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

</body>

</html>