<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>
	<style type="text/css">
		.mytable thead th {
		  background-color:	#e69500;
		  color: #000000;
		  text-align: center;
		  vertical-align: middle;
		  padding: 5px;
		}
		
		.mytable tbody td {
		  padding: 5px;
		}
		
		.mytable tfoot td {
		  background-color:	#e69500;
		  color: #000000;
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
                                    						
									<!-- Laporan Penjualan -->
									
                                    <div role="tabpanel" class="tab-pane active" id="penjualan">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Pengiriman Penjualan</h5></p>
														<p>Menampilkan laporan pengiriman penjualan yang dicatat terkirim untuk transaksi penjualan dalam suatu periode.</p>
                                                        <a href="#laporan_pengiriman_penjualan" aria-controls="laporan_pengiriman_penjualan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>									
                                                    </div>
                                                    <div class="col-sm-5">
														<p><h5>Laporan Pengiriman Penjualan Produk</h5></p>
														<p>Menampilkan laporan pengiriman penjualan produk yang dicatat terkirim untuk transaksi penjualan dalam suatu periode.</p>
                                                        <a href="#laporan_pengiriman_penjualan_produk" aria-controls="laporan_pengiriman_penjualan_produk" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>									
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Laporan Sales Order</h5></p>
														<p>Menampilkan semua produk yang diorder dalam suatu periode, dikelompok per supplier.</p>
                                                        <a href="#laporan_sales_order" aria-controls="laporan_sales_order" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>										
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Laporan Penjualan Per Produk</h5></p>
														<p>Menampilkan daftar kuantitas penjualan per produk yang dicatat terkirim untuk transaksi penjualan dalam suatu periode.</p>
                                                        <a href="#laporan_penjualan_produk" aria-controls="laporan_penjualan_produk" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Daftar Tagihan</h5></p>
														<p>Menampilkan jumlah nilai tagihan Anda pada setiap Pelanggan.</p>
                                                        <a href="#laporan_daftar_tagihan_penjualan" aria-controls="laporan_daftar_tagihan_penjualan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Laporan Piutang</h5></p>
														<p>Menampilkan jumlah nilai piutang Anda pada setiap Pelanggan.</p>
                                                        <a href="#laporan_piutang" aria-controls="laporan_piutang" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Laporan Umur Piutang</h5></p>
														<p>Menampilkan umur piutang Anda pada setiap Pelanggan.</p>
                                                        <a href="#laporan_umur_piutang" aria-controls="laporan_umur_piutang" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Laporan Penerimaan</h5></p>
														<p>Menampilkan laporan penerimaan Anda pada setiap Pelanggan.</p>
                                                        <a href="#laporan_penerimaan" aria-controls="laporan_penerimaan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Penyelesaian Penjualan</h5></p>
														<p>Menampilkan ringkasan dari proses bisnis Anda, dari penawaran, pemesanan, pengiriman, penagihan, dan pembayaran per proses, agar Anda dapat melihat penawaran/pemesanan mana yang berlanjut ke penagihan.</p>
                                                        <a href="#laporan_penyelesaian_penjualan" aria-controls="laporan_penyelesaian_penjualan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<!-- End Penjualan -->
								
									
									<!-- Laporan Pengiriman Penjualan -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_pengiriman_penjualan">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Laporan Pengiriman Penjualan</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <?php
                                                    $product = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
                                                    $client = $this->db->order_by('nama', 'asc')->get_where('penerima', array('status' => 'PUBLISH', 'pelanggan' => 1))->result_array();
                                                    ?>
                                                    <!--<div class="row">
                                                        <div class="col-sm-3">
                                                            <a href="<?php echo site_url('pmm/productions/add'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Pengiriman Penjualan</a>
                                                        </div>
                                                    </div>-->
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_pengiriman_penjualan'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_a" name="filter_date" class="form-control dtpicker" value="" autocomplete="off" placeholder="Filter By Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_product_a" class="form-control select2" name="filter_product">
                                                                    <option value="">Pilih Produk</option>
                                                                    <?php
                                                                    foreach ($product as $key => $pro) {
                                                                    ?>
                                                                        <option value="<?php echo $pro['id']; ?>"><?php echo $pro['nama_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_client_id_a" class="form-control select2" name="filter_client_id">
                                                                    <option value="">Pilih Pelanggan</option>
                                                                    <?php
                                                                    foreach ($client as $key => $cl) {
                                                                    ?>
                                                                        <option value="<?php echo $cl['id']; ?>"><?php echo $cl['nama']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-1 text-right">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-penjualan" style="display:none;">
                                                            <thead>
                                                                <th class="text-center">NO.</th>
                                                                <th class="text-center">PELANGGAN</th>
                                                                <th class="text-center">PRODUK</th>
																<th class="text-center">SATUAN</th>
                                                                <th class="text-center">VOLUME</th>
																<th class="text-center">HARGA SATUAN</th>
                                                                <th class="text-center">NILAI</th>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                            <tfoot>
                                                           
                                                            </tfoot>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- End Laporan Pengiriman Penjualan -->

                                    <!-- Laporan Pengiriman Penjualan Produk -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_pengiriman_penjualan_produk">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Laporan Pengiriman Penjualan Produk</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/cetak_pengiriman_penjualan_produk');?>" target="_blank">
															<div class="col-sm-3">
																<input type="text" id="filter_date_penjualan" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
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
									
									<!-- Laporan Sales Order -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_sales_order">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">
                                                    <h3 class="panel-title">Laporan Sales Order</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_sales_order'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_l" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date10" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr> 
                                                                <th width="5%" class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">PELANGGAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">NO. KONTRAK</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PRODUK</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">DPP</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">PPN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">JUMLAH</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">TGL. KONTRAK</th>
                                                            </tr>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	                                    
									<!-- End Sales Order -->
									
									<!-- Laporan Penjualan Per Produk -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_penjualan_produk">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">
                                                    <h3 class="panel-title">Laporan Penjualan Per Produk</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penjualan_per_produk'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_m" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date11" style="display:none" width="100%";>
                                                            <thead>
																<tr>
																	<th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
																	<th class="text-center">PRODUK</th>
																	<th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
																	<th class="text-center" colspan="3">KUANTITAS</th>
																	<th class="text-center" colspan="3">RUPIAH</th>
																</tr>
																<tr>
                                                                    <th class="text-center">PELANGGAN</th>
																	<th class="text-center">TERKIRIM</th>
																	<th class="text-center">DIKEMBALIKAN</th>
																	<th class="text-center">TERJUAL</th>
																	<th class="text-center">TERKIRIM</th>
																	<th class="text-center">DIKEMBALIKAN</th>
																	<th class="text-center">TERJUAL</th>
																</tr>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	  
                                                                    
									<!-- End Penjualan Per Produk -->
									
									<!-- Laporan Daftar Tagihan Penjualan -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_daftar_tagihan_penjualan">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
												<div class="panel-heading">
                                                    <h3 class="panel-title">Daftar Tagihan</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_daftar_tagihan'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_n" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date12" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr> 
																<th align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
																<th align="center">PELANGGAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO. INVOICE</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">MEMO</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">DPP</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">PPN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">TOTAL</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">TGL. INVOICE</th>
                                                            </tr>
									
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	                                    
									<!-- End Laporan Daftar Tagihan Penjualan-->
									
									<!-- Laporan Piutang -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_piutang">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">  
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Piutang</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_piutang'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_o" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date13" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
																<th align="center">PELANGGAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO. TAGIHAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">KETERANGAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">TAGIHAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">PENERIMAAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">SISA PIUTANG</th>
                                                            </tr>
                                                            <tr>
																<th align="center">TGL. INVOICE</th>
                                                            </tr>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	                                    
									<!-- End Laporan Piutang -->
									
									<!-- Laporan Umur Piutang -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_umur_piutang">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">      
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Umur Piutang</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_umur_piutang'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_p" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date14" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
																<th align="center">PELANGGAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">TOTAL</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">1-30 HARI</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">31-60 HARI</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">61-90 HARI</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">>90 HARI</th>
                                                                </tr>
                                                            <tr>
                                                                <th class="text-center">NO. TAGIHAN</th>
                                                            </tr>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	 
                                                                     
									<!-- End Umur Piutang -->
									
									<!-- Laporan Penerimaan -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_penerimaan">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">      
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Penerimaan</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penerimaan'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_q" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date15" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
																<th align="center">PELANGGAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO. TRANSAKSI</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO. TAGIHAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">PENERIMAAN</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">TGL. BAYAR</th>
                                                            </tr>
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	 
                                                                     
									<!-- End Penerimaan -->
									
									<!-- Laporan Penyelesaian Penjualan -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_penyelesaian_penjualan">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Penyelesaian Penjualan</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penyelesaian_penjualan'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_r" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table-bordered table-hover table-center table-condensed" id="table-date16" style="display:none" width="100%";>
                                                            <thead>
															<tr>
																<th align="center" rowspan="2">NO.</th>
																<th align="center">PELANGGAN</th>
																<th align="center" rowspan="2">NO. ORDER</th>
																<th align="center" colspan="2">PEMESANAN</th>
																<th align="center" colspan="2">PENGIRIMAN</th>
																<th align="center" colspan="2">TAGIHAN</th>
                                                                <th align="center" colspan="2">PENERIMAAN</th>
																<th align="center" colspan="2">HUTANG BRUTO</th>
																<th align="center" colspan="2">PIUTANG TERHADAP TAGIHAN</th>
                                                                <th align="center" colspan="2">TOTAL</th>
															</tr>
															<tr>
                                                                <th align="center">TGL. ORDER</th>
																<th align="center">VOL.</th>
																<th align="center">RP.</th>
																<th align="center">VOL.</th>
																<th align="center">RP.</th>
                                                                <th align="center">VOL.</th>
																<th align="center">RP.</th>
                                                                <th align="center">VOL.</th>
																<th align="center">RP.</th>
                                                                <th align="center">VOL.</th>
																<th align="center">RP.</th>
                                                                <th align="center">VOL.</th>
																<th align="center">RP.</th>
                                                                <th align="center">VOL.</th>
																<th align="center">RP.</th>
															</tr>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	 
                                                                     
									<!-- End Penyelesaian Penjualan -->
									
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

        <!-- Script Penjualan -->

        <script type="text/javascript">
            $('#filter_date_a').daterangepicker({
                autoUpdateInput: false,
				showDropdowns: true,
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
            $('#filter_date_a').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TablePenjualan();
            });

            function TablePenjualan() {
                $('#table-penjualan').show();
                $('#loader-table').fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date_lap_penjualan'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_client_id: $('#filter_client_id_a').val(),
                        filter_date: $('#filter_date_a').val(),
                        filter_product: $('#filter_product_a').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-penjualan tbody').html('');
                              if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-penjualan tbody').append('<tr onclick="NextShow(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"background-color:#FF0000""><td class="text-center">' + val.no + '</td><td class="text-left" colspan="3">' + val.name + '</td><td class="text-right">' + val.real + '</td><td class="text-right"></td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-penjualan tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.salesPo_id + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.real + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });

                                });
                                $('#table-penjualan tbody').append('<tr><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total_volume + '</b></td><td class="text-right" ></td><td class="text-right" ><b>' + result.total_nilai + '</b></td></tr>');
                            } else {
                                $('#table-penjualan tbody').append('<tr><td class="text-center" colspan="7"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }


            // TablePenjualan();

            $('#filter_product_a').change(function() {
                TablePenjualan();
            });

            $('#filter_client_id_a').change(function() {
                TablePenjualan();
            });

             function NextShow(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }
        </script>

        <!-- End Script Penjualan -->

        <!-- Script Laporan Pengiriman Penjualan Produk -->
			
        <script type="text/javascript">
			$('#filter_date_penjualan').daterangepicker({
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

			$('#filter_date_penjualan').on('apply.daterangepicker', function(ev, picker) {
				  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
				  TablePergerakanBahanJadi();
			});
			
			function TablePergerakanBahanJadi()
			{
				$('#wait').fadeIn('fast');   
				$.ajax({
					type    : "POST",
					url     : "<?php echo site_url('pmm/productions/laporan_pengiriman_penjualan_produk'); ?>/"+Math.random(),
					dataType : 'html',
					data: {
						filter_date : $('#filter_date_penjualan').val(),
					},
					success : function(result){
						$('#box-ajax-6').html(result);
						$('#wait').fadeOut('fast');
					}
				});
			}

			//TablePergerakanBahanJadi();
			
            </script>

		<!-- End Script Laporan Pengiriman Penjualan Produk -->

		<!-- Script Sales Order -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_l').daterangepicker({
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

            $('#filter_date_l').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate10();
            });

            function TableDate10() {
                $('#table-date10').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date10 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date10'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_l').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date10 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date10 tbody').append('<tr onclick="NextShowSalesOrder(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="9">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date10 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.contract_date + '</td><td class="text-left">' + row.contract_number + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.qty + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.dpp + '</td><td class="text-right">' + row.tax + '</td><td class="text-right">' + row.total + '</td></tr>');
                                    });
                                    $('#table-date10 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="9"><b>JUMLAH</b></td><td class="text-right""><b>' + val.jumlah + '</b></td></tr>');
                                });
                                $('#table-date10 tbody').append('<tr><td class="text-right" colspan="9"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date10 tbody').append('<tr><td class="text-center" colspan="10"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowSalesOrder(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Sales Order -->
		
		<!-- Script Penjualan Per Produk -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_m').daterangepicker({
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

            $('#filter_date_m').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate11();
            });

            function TableDate11() {
                $('#table-date11').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date11 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date11'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_m').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date11 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date11 tbody').append('<tr onclick="NextShowPenjualanProduk(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="10">' + val.nama_produk + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date11 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.nama + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.terkirim + '</td><td class="text-right">' + row.dikembalikan + '</td><td class="text-right">' + row.terjual + '</td><td class="text-right">' + row.terkirim_rp + '</td><td class="text-right">' + row.dikembalikan_rp + '</td><td class="text-right">' + row.terjual_rp + '</td></tr>');
                                    });
                                });
                                $('#table-date11 tbody').append('<tr><td class="text-right" colspan="8"><b>TOTAL</b></td><td class="text-right" ><b>' + result.all_total + '</b></td></tr>');
                            } else {
                                $('#table-date11 tbody').append('<tr><td class="text-center" colspan="10"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPenjualanProduk(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Penjualan Per Produk-->
		
		<!-- Script Daftar Tagihan  Penjualan -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_n').daterangepicker({
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

            $('#filter_date_n').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate12();
            });

            function TableDate12() {
                $('#table-date12').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date12 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date12'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_n').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date12 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date12 tbody').append('<tr onclick="NextShowDaftarTagihanPenjualan(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="8">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date12 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_invoice + '</td><td class="text-left">' + row.nomor_invoice + '</td><td class="text-left">' + row.memo + '</td><td class="text-right">' + row.qty + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.jumlah + '</td><td class="text-right">' + row.ppn + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });
                                    $('#table-date12 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="8"><b>JUMLAH</b></td><td class="text-right"><b>' + val.jumlah + '</b></td></tr>');
                                });
                                $('#table-date12 tbody').append('<tr><td class="text-right" colspan="8"><b>TOTAL</b></td><td class="text-right"><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date12 tbody').append('<tr><td class="text-center" colspan="9"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowDaftarTagihanPenjualan(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Daftar Tagihan Penjualan -->
		
		<!-- Script Piutang -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_o').daterangepicker({
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

            $('#filter_date_o').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate13();
            });

            function TableDate13() {
                $('#table-date13').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date5 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date13'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_o').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date13 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date13 tbody').append('<tr onclick="NextShowLaporanPiutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="7">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date13 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_invoice + '</td><td class="text-center">' + row.nomor_invoice + '</td><td class="text-left">' + row.memo + '</td><td class="text-right">' + row.tagihan + '</td><td class="text-right">' + row.pembayaran + '</td><td class="text-right">' + row.piutang + '</td></tr>');
                                    });
									$('#table-date13 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="4"><b>JUMLAH</b></td><td class="text-right""><b>' + val.total_tagihan + '</b></td><td class="text-right""><b>' + val.total_penerimaan + '</b></td><td class="text-right""><b>' + val.total_piutang + '</b></td></tr>');
                                });
                                $('#table-date13 tbody').append('<tr><td class="text-right" colspan="6"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date13 tbody').append('<tr><td class="text-center" colspan="7"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowLaporanPiutang(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Piutang -->
		
		<!-- Script Umur Piutang -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_p').daterangepicker({
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

            $('#filter_date_p').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate14();
            });

            function TableDate14() {
                $('#table-date14').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date14 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date14'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_p').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date14 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date14 tbody').append('<tr onclick="NextShowUmurPiutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.nama + '</td><td class="text-right">' + val.total_piutang + '</td><td></td><td></td><td></td><td></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        console.log(val);
                                        console.log(row);
                                        var a_no = a + 1;
                                        if (val.syarat_pembayaran >= 1 && val.syarat_pembayaran <= 30){
                                            $('#table-date14 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.nomor_invoice + '</td><td></td><td class="text-right">' + row.sisa_piutang + '</td><td></td><td></td><td></td></tr>');
                                        } else if (val.syarat_pembayaran > 31 && val.syarat_pembayaran <= 60){
                                            $('#table-date14 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.nomor_invoice + '</td><td></td><td></td><td class="text-right">' + row.sisa_piutang + '</td><td></td><td></td></tr>');
                                        } else if (val.syarat_pembayaran > 61 && val.syarat_pembayaran <= 90){
                                            $('#table-date14 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.nomor_invoice + '</td><td></td><td></td><td></td><td class="text-right">' + row.sisa_piutang + '</td><td></td></tr>');
                                        } else if (val.syarat_pembayaran > 90){
                                            $('#table-date14 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.nomor_invoice + '</td><td></td><td></td><td></td><td></td><td class="text-right">' + row.sisa_piutang + '</td></tr>');
                                        }
                                    });
                                });
                                $('#table-date14 tbody').append('<tr><td class="text-right" colspan="2"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td><td></td><td></td><td></td><td></td></tr>');
                            } else {
                                $('#table-date14 tbody').append('<tr><td class="text-center" colspan="7"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowUmurPiutang(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Umur Piutang -->
		
		<!-- Script Penerimaan -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_q').daterangepicker({
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

            $('#filter_date_q').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate15();
            });

            function TableDate15() {
                $('#table-date15').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date15 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date15'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_q').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date15 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date15 tbody').append('<tr onclick="NextShowPenerimaan(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.nama + '</td><td></td><td></td><td></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date15 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_pembayaran + '</td><td class="text-center">' + row.nomor_transaksi + '</td></td><td class="text-center">' + row.nomor_invoice + '</td><td class="text-right">' + row.penerimaan + '</td></tr>');
                                    });
                                });
                                $('#table-date15 tbody').append('<tr><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date15 tbody').append('<tr><td class="text-center" colspan="5"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPenerimaan(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Penerimaan -->
		
		<!-- Script Penyelesaian Penjualan -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_r').daterangepicker({
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

            $('#filter_date_r').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate16();
            });

            function TableDate16() {
                $('#table-date16').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date16 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/table_date16'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_r').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date16 tbody').html('');

                            if (result.data.length > 0) {

                                $.each(result.data, function(i, val) {

                                    window.vol_pemesanan = 0;
                                    window.pemesanan = 0;
                                    window.vol_pengiriman = 0;
                                    window.pengiriman = 0;
                                    window.vol_tagihan = 0;
                                    window.tagihan = 0;
                                    window.vol_pembayaran = 0;
                                    window.pembayaran = 0;
                                    window.vol_piutang_pengiriman = 0;
                                    window.piutang_pengiriman = 0;
                                    window.vol_sisa_tagihan = 0;
                                    window.sisa_tagihan = 0;
                                    window.vol_akhir = 0;
                                    window.akhir = 0;

                                    $('#table-date16 tbody').append('<tr onclick="NextShowPenyelesaianPenjualan(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="16">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date16 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.contract_date + '</td><td class="text-center">' + row.contract_number + '</td><td class="text-right">' + row.vol_pemesanan + '</td><td class="text-right">' + row.pemesanan + '</td><td class="text-right">' + row.vol_pengiriman + '</td><td class="text-right">' + row.pengiriman + '</td><td class="text-right">' + row.vol_tagihan + '</td><td class="text-right">' + row.tagihan + '</td><td class="text-right">' + row.vol_pembayaran + '</td><td class="text-right">' + row.pembayaran + '</td><td class="text-right">' + row.vol_piutang_pengiriman + '</td><td class="text-right">' + row.piutang_pengiriman + '</td><td class="text-right">' + row.vol_sisa_tagihan + '</td><td class="text-right">' + row.sisa_tagihan + '</td><td class="text-right">' + row.vol_akhir + '</td><td class="text-right">' + row.akhir + '</td></tr>');
                                        
                                        window.vol_pemesanan += parseFloat(row.vol_pemesanan.replace(/\./g,'').replace(',', '.'));
                                        window.pemesanan += parseFloat(row.pemesanan.replace(/\./g,'').replace(',', '.'));
                                        window.vol_pengiriman += parseFloat(row.vol_pengiriman.replace(/\./g,'').replace(',', '.'));
                                        window.pengiriman += parseFloat(row.pengiriman.replace(/\./g,'').replace(',', '.'));
                                        window.vol_tagihan += parseFloat(row.vol_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.tagihan += parseFloat(row.tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.vol_pembayaran += parseFloat(row.vol_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.pembayaran += parseFloat(row.pembayaran.replace(/\./g,'').replace(',', '.'));
                                        console.log(pembayaran);
                                        window.vol_piutang_pengiriman += parseFloat(row.vol_piutang_pengiriman.replace(/\./g,'').replace(',', '.'));
                                        window.piutang_pengiriman += parseFloat(row.piutang_pengiriman.replace(/\./g,'').replace(',', '.'));
                                        window.vol_sisa_tagihan += parseFloat(row.vol_sisa_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.sisa_tagihan += parseFloat(row.tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.vol_akhir += parseFloat(row.vol_akhir.replace(/\./g,'').replace(',', '.'));
                                        window.akhir += parseFloat(row.akhir.replace(/\./g,'').replace(',', '.'));
                                       

                                    });
									$('#table-date16 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="3"><b>JUMLAH</b></td><td class="text-right"><b>' + formatter.format(window.vol_pemesanan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.pemesanan) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_pengiriman) + '</b></td><td class="text-right"><b>' + formatter2.format(window.pengiriman) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_pembayaran) + '</b></td><td class="text-right""><b>' + formatter2.format(window.pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_piutang_pengiriman) + '</b></td><td class="text-right"><b>' + formatter2.format(window.piutang_pengiriman) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_sisa_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.sisa_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_akhir) + '</b></td><td class="text-right"><b>' + formatter2.format(window.akhir) + '</b></td></tr>');
                                });
                                $('#table-date16 tbody').append('<tr><td class="text-right" colspan="3"><b>TOTAL</b></td><td class="text-right" ><b>' + result.grand_total_vol_pemesanan + '</b></td><td class="text-right" ><b>' + result.grand_total_pemesanan + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_pengiriman + '</b></td><td class="text-right" ><b>' + result.grand_total_pengiriman + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_pembayaran + '</b></td><td class="text-right" ><b>' + result.grand_total_pembayaran + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_piutang_pengiriman + '</b></td><td class="text-right" ><b>' + result.grand_total_piutang_pengiriman + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_sisa_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_sisa_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_akhir + '</b></td><td class="text-right" ><b>' + result.grand_total_akhir + '</b></td></tr>');
                            } else {
                                $('#table-date16 tbody').append('<tr><td class="text-center" colspan="17"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPenyelesaianPenjualan(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }
			
			window.formatter = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '2'
            });

            window.formatter2 = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '0'
            });
			

        </script>
		
		<!-- End Penyelesaian Penjualan -->

</body>

</html>