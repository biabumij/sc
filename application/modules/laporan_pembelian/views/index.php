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
								
								<!-- Laporan Pembelian -->
                                    <div role="tabpanel" class="tab-pane active" id="pembelian">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Penerimaan Pembelian (Bahan Baku)</h5></p>
														<p>Menampilkan produk bahan baku yang dicatat terkirim untuk transaksi pembelian dalam suatu periode.</p>
                                                        <a href="#laporan_penerimaan_pembelian" aria-controls="laporan_penerimaan_pembelian" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Penerimaan Pembelian (Bahan Baku) Per Hari</h5></p>
														<p>Menampilkan produk bahan baku per hari yang dicatat terkirim untuk transaksi pembelian dalam suatu periode.</p>
                                                        <a href="#laporan_penerimaan_pembelian_hari" aria-controls="laporan_penerimaan_pembelian_hari" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
													<div class="col-sm-5">
														<p><h5>Penerimaan Pembelian (Sewa Alat)</h5></p>
														<p>Menampilkan sewa alat yang dicatat terkirim untuk transaksi pembelian dalam suatu periode.</p>
                                                        <a href="#laporan_penerimaan_pembelian_sewa_alat" aria-controls="laporan_penerimaan_pembelian_sewa_alat" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Penerimaan Pembelian (Jasa Angkut)</h5></p>
														<p>Menampilkan jasa angkut yang dicatat terkirim untuk transaksi pembelian dalam suatu periode.</p>
                                                        <a href="#laporan_penerimaan_pembelian_jasa_angkut" aria-controls="laporan_penerimaan_pembelian_jasa_angkut" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>												
													<div class="col-sm-5">
														<p><h5>Laporan Pesanan Pembelian</h5></p>
														<p>Menampilkan semua produk yang dipesan dalam suatu periode, dikelompok per supplier.</p>
                                                        <a href="#laporan_pesanan_pembelian" aria-controls="laporan_pesanan_pembelian" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Laporan Pembelian Per Produk</h5></p>
														<p>Menampilkan daftar kuantitas pembelian per produk dalam suatu periode.</p>
                                                        <a href="#laporan_pembelian_produk" aria-controls="laporan_pembelian_produk" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Daftar Tagihan</h5></p>
														<p>Menampilkan jumlah nilai tagihan Anda pada setiap Rekanan dalam suatu periode.</p>
                                                        <a href="#laporan_daftar_tagihan" aria-controls="laporan_daftar_tagihan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Hutang</h5></p>
														<p>Menampilkan jumlah nilai hutang Anda pada setiap Rekanan dalam suatu periode.</p>
                                                        <a href="#laporan_hutang" aria-controls="laporan_hutang" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Umur Hutang</h5></p>
														<p>Menampilkan umur hutang Anda pada setiap Rekanan dalam suatu periode.</p>
                                                        <a href="#laporan_umur_hutang" aria-controls="laporan_umur_hutang" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Daftar Pembayaran</h5></p>
														<p>Menampilkan jumlah pembayaran Anda pada setiap setiap Rekanan dalam suatu periode.</p>
                                                        <a href="#laporan_daftar_pembayaran" aria-controls="laporan_daftar_pembayaran" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
													<div class="col-sm-5">
														<p><h5>Penyelesaian Pembelian</h5></p>
														<p>Menampilkan ringkasan dari proses bisnis Anda, dari penawaran, pemesanan, pengiriman, penagihan, dan pembayaran per proses, agar Anda dapat melihat penawaran/pemesanan mana yang berlanjut ke penagihan.</p>
                                                        <a href="#laporan_penyelesaian_pembelian" aria-controls="laporan_penyelesaian_pembelian" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<!-- End Pembelian -->

                                    <!-- Laporan Penerimaan Pembelian -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_penerimaan_pembelian">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Penerimaan Pembelian</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <?php
                                                    $arr_po = $this->db->order_by('id', ' no_po', 'supplier_id', 'asc')->get_where('pmm_purchase_order', array('status' => 'PUBLISH'))->result_array();
                                                    $suppliers  = $this->db->order_by('nama', 'asc')->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => 1))->result_array();
                                                    $materials = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH', 'bahanbaku' => 1))->result_array();
                                                    ?>
                                                    <!--<div class="row">
                                                        <div class="col-sm-3">
                                                            <a href="<?php echo site_url('pmm/receipt_material/manage'); ?>" class="btn btn-primary">Tambah Penerimaan Pembelian</a>
                                                        </div>
                                                    </div>-->
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penerimaan_pembelian'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_b" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_material_b" name="filter_material" class="form-control select2">
                                                                    <option value="">Pilih Produk</option>
                                                                    <?php
                                                                    foreach ($materials as $key => $mats) {
                                                                    ?>
                                                                        <option value="<?php echo $mats['id']; ?>"><?php echo $mats['nama_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_supplier_id_b" name="supplier_id" class="form-control select2">
                                                                    <option value="">Pilih Rekanan</option>
                                                                    <?php
                                                                    foreach ($suppliers as $key => $supplier) {
                                                                    ?>
                                                                        <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['nama']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!--<div class="col-sm-3">
                                                                <select id="filter_po_id_b" name="purchase_order_no" class="form-control select2">
                                                                    <option value="">Pilih PO</option>
                                                                </select>
                                                            </div>-->
                                                            <div class="col-sm-9 text-right">
                                                                <br />
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date" style="display:none;">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PRODUK</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NILAI</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. PESANAN PEMBELIAN</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>					                                    
									<!-- End Penerimaan Pembelian -->

                                    <!-- Laporan Penerimaan Pembelian Per Hari -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_penerimaan_pembelian_hari">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Penerimaan Pembelian Per Hari</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <?php
                                                    $arr_po = $this->db->order_by('id', ' no_po', 'supplier_id', 'asc')->get_where('pmm_purchase_order', array('status' => 'PUBLISH'))->result_array();
                                                    $suppliers  = $this->db->order_by('nama', 'asc')->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => 1))->result_array();
                                                    $materials = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH', 'bahanbaku' => 1))->result_array();
                                                    ?>
                                                    <!--<div class="row">
                                                        <div class="col-sm-3">
                                                            <a href="<?php echo site_url('pmm/receipt_material/manage'); ?>" class="btn btn-primary">Tambah Penerimaan Pembelian</a>
                                                        </div>
                                                    </div>-->
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penerimaan_pembelian_per_hari'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_b_hari" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_material_b_hari" name="filter_material" class="form-control select2">
                                                                    <option value="">Pilih Produk</option>
                                                                    <?php
                                                                    foreach ($materials as $key => $mats) {
                                                                    ?>
                                                                        <option value="<?php echo $mats['id']; ?>"><?php echo $mats['nama_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_supplier_id_b_hari" name="supplier_id" class="form-control select2">
                                                                    <option value="">Pilih Rekanan</option>
                                                                    <?php
                                                                    foreach ($suppliers as $key => $supplier) {
                                                                    ?>
                                                                        <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['nama']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!--<div class="col-sm-3">
                                                                <select id="filter_po_id_b_hari" name="purchase_order_no" class="form-control select2">
                                                                    <option value="">Pilih PO</option>
                                                                </select>
                                                            </div>-->
                                                            <div class="col-sm-9 text-right">
                                                                <br />
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date-hari" style="display:none;">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">TANGGAL</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PRODUK</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NILAI</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. PESANAN PEMBELIAN</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>					                                    
									<!-- End Penerimaan Pembelian Per Hari -->
									
									<!-- Laporan Penerimaan Pembelian (Sewa ALat) -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_penerimaan_pembelian_sewa_alat">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Penerimaan Pembelian (Sewa Alat)</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <?php
                                                    $arr_po = $this->db->order_by('id', ' no_po', 'supplier_id', 'asc')->get_where('pmm_purchase_order', array('status' => 'PUBLISH'))->result_array();
                                                    $suppliers  = $this->db->order_by('nama', 'asc')->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => 1))->result_array();
                                                    $materials = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH', 'peralatan' => 1))->result_array();
                                                    ?>
                                                    <!--<div class="row">
                                                        <div class="col-sm-3">
                                                            <a href="<?php echo site_url('pmm/receipt_material/manage'); ?>" class="btn btn-primary">Tambah Penerimaan Pembelian</a>
                                                        </div>
                                                    </div>-->
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penerimaan_pembelian_sewa_alat'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_b1" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_material_b1" name="filter_material" class="form-control select2">
                                                                    <option value="">Pilih Produk</option>
                                                                    <?php
                                                                    foreach ($materials as $key => $mats) {
                                                                    ?>
                                                                        <option value="<?php echo $mats['id']; ?>"><?php echo $mats['nama_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_supplier_id_b1" name="supplier_id" class="form-control select2">
                                                                    <option value="">Pilih Rekanan</option>
                                                                    <?php
                                                                    foreach ($suppliers as $key => $supplier) {
                                                                    ?>
                                                                        <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['nama']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!--<div class="col-sm-3">
                                                                <select id="filter_po_id_b1" name="purchase_order_no" class="form-control select2">
                                                                    <option value="">Pilih PO</option>
                                                                </select>
                                                            </div>-->
                                                            <div class="col-sm-9 text-right">
                                                                <br />
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date-sewa-alat" style="display:none;">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PRODUK</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NILAI</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. PESANAN PEMBELIAN</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>					                                    
									<!-- End Penerimaan Pembelian (Sewa Alat) -->

                                    <!-- Laporan Penerimaan Pembelian (Jasa Angkut) -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_penerimaan_pembelian_jasa_angkut">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Penerimaan Pembelian (Jasa Angkut)</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <?php
                                                    $arr_po = $this->db->order_by('id', ' no_po', 'supplier_id', 'asc')->get_where('pmm_purchase_order', array('status' => 'PUBLISH'))->result_array();
                                                    $suppliers  = $this->db->order_by('nama', 'asc')->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => 1))->result_array();
                                                    $materials = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH', 'jasa' => 1))->result_array();
                                                    ?>
                                                    <!--<div class="row">
                                                        <div class="col-sm-3">
                                                            <a href="<?php echo site_url('pmm/receipt_material/manage'); ?>" class="btn btn-primary">Tambah Penerimaan Pembelian</a>
                                                        </div>
                                                    </div>-->
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penerimaan_pembelian_jasa_angkut'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_jasa_angkut" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_material_jasa_angkut" name="filter_material" class="form-control select2">
                                                                    <option value="">Pilih Produk</option>
                                                                    <?php
                                                                    foreach ($materials as $key => $mats) {
                                                                    ?>
                                                                        <option value="<?php echo $mats['id']; ?>"><?php echo $mats['nama_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_supplier_id_jasa_angkut" name="supplier_id" class="form-control select2">
                                                                    <option value="">Pilih Rekanan</option>
                                                                    <?php
                                                                    foreach ($suppliers as $key => $supplier) {
                                                                    ?>
                                                                        <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['nama']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!--<div class="col-sm-3">
                                                                <select id="filter_po_id_jasa_angkut" name="purchase_order_no" class="form-control select2">
                                                                    <option value="">Pilih PO</option>
                                                                </select>
                                                            </div>-->
                                                            <div class="col-sm-9 text-right">
                                                                <br />
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date-jasa-angkut" style="display:none;">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PRODUK</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NILAI</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. PESANAN PEMBELIAN</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>					                                    
									<!-- End Penerimaan Pembelian (Jasa Angkut) -->
									
									<!-- Laporan Pesanan Pembelian -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_pesanan_pembelian">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default"> 
												<div class="panel-heading">
                                                    <h3 class="panel-title">Laporan Pesanan Pembelian</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_pesanan_pembelian'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_d" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date2" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">NO. PO</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PRODUK</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">DPP</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">PPN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">JUMLAH</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">TGL. PO</th>
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
									
									<!-- End Laporan Pesanan Pembelian  -->
									
									<!-- Laporan Pembelian Per Produk -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_pembelian_produk">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">  
												<div class="panel-heading">
                                                    <h3 class="panel-title">Laporan Pembelian Per Produk</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_pembelian_per_produk'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_e" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date3" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">PRODUK</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">TOTAL</th>
                                                                </tr>
                                                            <tr>
                                                                <th class="text-center">REKANAN</th>
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
									<!-- End Laporan Pembelian Per Produk -->
									
									<!-- Laporan Daftar Tagihan -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_daftar_tagihan">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
												<div class="panel-heading">
                                                    <h3 class="panel-title">Daftar Tagihan</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_daftar_tagihan_pembelian'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_f" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date4" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
																<th align="center">REKANAN</th>
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
									<!-- End Laporan Daftar Tagihan -->
									
									<!-- Laporan Hutang -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_hutang">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">  
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Hutang</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_hutang'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_g" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date5" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
																<th align="center">REKANAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO. TAGIHAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">KETERANGAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">TAGIHAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">PEMBAYARAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">HUTANG</th>
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
									<!-- End Laporan Hutang -->
									
									<!-- Laporan Umur Hutang -->

                                    <div role="tabpanel" class="tab-pane" id="laporan_umur_hutang">
                                        <div class="col-sm-15">
										<div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Laporan Umur Hutang</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
												<div style="margin: 20px">
													<div class="row">
														<form action="<?php echo site_url('laporan/cetak_umur_hutang');?>" target="_blank">
															<!--<div class="col-sm-3">
																<input type="text" id="filter_date_h" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
															</div>
															<div class="col-sm-3">
																<button type="submit" class="btn btn-info"><i class="fa fa-print"></i>  Print</button>
															</div>-->
														</form>
														
													</div>
													<br />
													<div id="wait" style=" text-align: center; align-content: center; display: none;">	
														<div>Please Wait</div>
														<div class="fa-3x">
														  <i class="fa fa-spinner fa-spin"></i>
														</div>
													</div>				
													<div class="table-responsive" id="table-date6">													
													
                    
													</div>
												</div>
										</div>
										
										</div>
                                    </div>
                                                                     
									<!-- End Umur Hutang -->
									
									<!-- Laporan Daftar Pembayaran -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_daftar_pembayaran">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">  
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Daftar Pembayaran</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_daftar_pembayaran'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_i" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="table-date7" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
																<th align="center">REKANAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO. PEMBAYARAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">TANGGAL TAGIHAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">NO. TAGIHAN</th>
																<th align="center" rowspan="2" style="vertical-align:middle;">PEMBAYARAN</th>
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
                                                                     
									<!-- End Daftar Pembayaran -->
									
									<!-- Laporan Penyelesaian Pembelian -->
									
									<div role="tabpanel" class="tab-pane" id="laporan_penyelesaian_pembelian">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Penyelesaian Pembelian</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penyelesaian_pembelian'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_k" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table-bordered table-hover table-center table-condensed" id="table-date9" style="display:none" width="100%";>
                                                            <thead>
															<tr>
                                                                <th align="center" rowspan="2">NO.</th>
																<th align="center">REKANAN</th>
																<th align="center" rowspan="2">NO. PEMESANAN</th>
																<th align="center" colspan="2">PEMESANAN</th>
																<th align="center" colspan="2">PENERIMAAN</th>
																<th align="center" colspan="2">TAGIHAN</th>
                                                                <th align="center" colspan="2">PEMBAYARAN</th>
																<th align="center" colspan="2">HUTANG BRUTO</th>
																<th align="center" colspan="2">HUTANG TERHADAP TAGIHAN</th>
                                                                <th align="center" colspan="2">TOTAL</th>
															</tr>
															<tr>
                                                                <th align="center">TGL. PESAN</th>
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
															</thead>
                                                            <tbody></tbody>
															<tfoot class="mytable table-hover table-center table-bordered table-condensed"></tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>	 
                                                                     
									<!-- End Penyelesaian Pembelian -->
									
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
		
		<!-- Script Pembelian -->
		
        <script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_b').daterangepicker({
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

            $('#filter_date_b').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate();
            });

            function TableDate() {
                $('#table-date').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        purchase_order_no: $('#filter_po_id_b').val(),
                        supplier_id: $('#filter_supplier_id_b').val(),
                        filter_date: $('#filter_date_b').val(),
                        filter_material: $('#filter_material_b').val(),
                    },
                     success: function(result) {
                        if (result.data) {
                            $('#table-date tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date tbody').append('<tr onclick="NextShowPembelian(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"background-color:#FF0000""><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-center">' + val.measure + '</td><td class="text-right">' + val.volume + '</td><td class="text-right"></td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.purchase_order_id + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });

                                });
                                $('#table-date tbody').append('<tr><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total_volume + '</b></td><td class="text-right" ></td><td class="text-right" ><b>' + result.total_nilai + '</b></td></tr>');
                            } else {
                                $('#table-date tbody').append('<tr><td class="text-center" colspan="7"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPembelian(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            // TableDate();

            function GetPO() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/get_po_by_supp'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        supplier_id: $('#filter_supplier_id_b').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#filter_po_id_b').empty();
                            $('#filter_po_id_b').select2({
                                data: result.data
                            });
                            $('#filter_po_id_b').trigger('change');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            $('#filter_supplier_id_b').change(function() {
                TableDate();
                GetPO();
            });

            $('#filter_po_id_b').change(function() {
                TableDate();
            });

            $('#filter_material_b').change(function() {
                TableDate();
            });
        </script>
        <!-- End Script Pembelian -->

        <!-- Script Pembelian Per Hari -->
		
        <script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_b_hari').daterangepicker({
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

            $('#filter_date_b_hari').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDateHari();
            });

            function TableDateHari() {
                $('#table-date-hari').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date-hari tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date_hari'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        purchase_order_no: $('#filter_po_id_b_hari').val(),
                        supplier_id: $('#filter_supplier_id_b_hari').val(),
                        filter_date: $('#filter_date_b_hari').val(),
                        filter_material: $('#filter_material_b_hari').val(),
                    },
                     success: function(result) {
                        if (result.data) {
                            $('#table-date-hari tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date-hari tbody').append('<tr onclick="NextShowPembelianHari(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"background-color:#FF0000""><td class="text-center">' + val.no + '</td><td class="text-left" colspan="3">' + val.name + '</td><td class="text-center">' + val.measure + '</td><td class="text-right">' + val.volume + '</td><td class="text-right"></td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date-hari tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.purchase_order_id + '</td><td class="text-left">' + row.date_receipt + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });

                                });
                                $('#table-date-hari tbody').append('<tr><td class="text-right" colspan="5"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total_volume + '</b></td><td class="text-right" ></td><td class="text-right" ><b>' + result.total_nilai + '</b></td></tr>');
                            } else {
                                $('#table-date-hari tbody').append('<tr><td class="text-center" colspan="8"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPembelianHari(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            // TableDateHari();

            function GetPO() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/get_po_by_supp'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        supplier_id: $('#filter_supplier_id_b_hari').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#filter_po_id_b_hari').empty();
                            $('#filter_po_id_b_hari').select2({
                                data: result.data
                            });
                            $('#filter_po_id_b_hari').trigger('change');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            $('#filter_supplier_id_b_hari').change(function() {
                TableDateHari();
                GetPO();
            });

            $('#filter_po_id_b_hari').change(function() {
                TableDateHari();
            });

            $('#filter_material_b_hari').change(function() {
                TableDateHari();
            });
        </script>
        <!-- End Script Pembelian Per Hari -->
		
		<!-- Script Penerimaan Pembelian (Sewa Alat) -->
		
        <script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_b1').daterangepicker({
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

            $('#filter_date_b1').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDateSewaAlat();
            });

            function TableDateSewaAlat() {
                $('#table-date-sewa-alat').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date-sewa-alat tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date_sewa_alat'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        purchase_order_no: $('#filter_po_id_1b').val(),
                        supplier_id: $('#filter_supplier_id_b1').val(),
                        filter_date: $('#filter_date_b1').val(),
                        filter_material: $('#filter_material_b1').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date-sewa-alat tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date-sewa-alat tbody').append('<tr onclick="NextShowPembelianSewaAlat(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"background-color:#FF0000""><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-center">' + val.measure + '</td><td class="text-right">' + val.volume + '</td><td class="text-right"></td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date-sewa-alat tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.purchase_order_id + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });

                                });
                                $('#table-date tbody').append('<tr><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total_volume + '</b></td><td class="text-right" ></td><td class="text-right" ><b>' + result.total_nilai + '</b></td></tr>');
                            } else {
                                $('#table-date-sewa-alat tbody').append('<tr><td class="text-center" colspan="7"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPembelianSewaAlat(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            // TableDate();

            function GetPOAlat() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/get_po_by_supp_alat'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        supplier_id: $('#filter_supplier_id_b1').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#filter_po_id_b1').empty();
                            $('#filter_po_id_b1').select2({
                                data: result.data
                            });
                            $('#filter_po_id_b1').trigger('change');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            $('#filter_supplier_id_b1').change(function() {
                TableDateSewaAlat();
                GetPOAlat();
            });

            $('#filter_po_id_b1').change(function() {
                TableDateSewaAlat();
            });

            $('#filter_material_b1').change(function() {
                TableDateSewaAlat();
            });
        </script>
		
        <!-- End Script Penerimaan Pembelian (Sewa Alat) -->

        <!-- Script Penerimaan Pembelian (Jasa Angkut) -->
		
        <script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_jasa_angkut').daterangepicker({
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

            $('#filter_date_jasa_angkut').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDateJasaAngkut();
            });

            function TableDateJasaAngkut() {
                $('#table-date-jasa-angkut').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date-jasa-angkut tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date_jasa_angkut'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        purchase_order_no: $('#filter_po_id_jasa_angkut').val(),
                        supplier_id: $('#filter_supplier_id_jasa_angkut').val(),
                        filter_date: $('#filter_date_jasa_angkut').val(),
                        filter_material: $('#filter_material_jasa_angkut').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date-jasa-angkut tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date-jasa-angkut tbody').append('<tr onclick="NextShowPembelianJasaAngkut(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"background-color:#FF0000""><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-center">' + val.measure + '</td><td class="text-right">' + val.volume + '</td><td class="text-right"></td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date-jasa-angkut tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.purchase_order_id + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });

                                });
                                $('#table-date tbody').append('<tr><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total_volume + '</b></td><td class="text-right" ></td><td class="text-right" ><b>' + result.total_nilai + '</b></td></tr>');
                            } else {
                                $('#table-date-jasa-angkut tbody').append('<tr><td class="text-center" colspan="7"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPembelianJasaAngkut(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            // TableDate();

            function GetPOJasa() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/get_po_by_supp_jasa'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        supplier_id: $('#filter_supplier_id_jasa_angkut').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#filter_po_id_jasa_angkut').empty();
                            $('#filter_po_id_jasa_angkut').select2({
                                data: result.data
                            });
                            $('#filter_po_id_jasa_angkut').trigger('change');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            $('#filter_supplier_id_jasa_angkut').change(function() {
                TableDateJasaAngkut();
                GetPOJasa();
            });

            $('#filter_po_id_jasa_angkut').change(function() {
                TableDateJasaAngkut();
            });

            $('#filter_material_jasa_angkut').change(function() {
                TableDateJasaAngkut();
            });
        </script>
		
        <!-- End Script Penerimaan Pembelian (Jasa Angkut) -->
	
		<!-- Script Pesanan Pembelian -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_d').daterangepicker({
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

            $('#filter_date_d').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate2();
            });

            function TableDate2() {
                $('#table-date2').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date2 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date2'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_d').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date2 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date2 tbody').append('<tr onclick="NextShowPesananPembelian(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="9">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date2 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.date_po + '</td><td class="text-left">' + row.no_po + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.jumlah + '</td><td class="text-right">' + row.ppn + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });
									 $('#table-date2 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="9"><b>JUMLAH</b></td><td class="text-right"><b>' + val.jumlah + '</b></td></tr>');
                                });
                                $('#table-date2 tbody').append('<tr><td class="text-right" colspan="9"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date2 tbody').append('<tr><td class="text-center" colspan="10"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPesananPembelian(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Pesanan Pembelian -->
		
		<!-- Script Pembelian Per Produk -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_e').daterangepicker({
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

            $('#filter_date_e').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate3();
            });

            function TableDate3() {
                $('#table-date3').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date3 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date3'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_e').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date3 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date3 tbody').append('<tr onclick="NextShowPesananPembelianProduk(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.nama_produk + '</td><td class="text-center">' + val.satuan + '</td><td class="text-right">' + val.volume + '</td><td class="text-right">' + val.harga_satuan + '</td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date3 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.nama + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });
                                });
                                $('#table-date3 tbody').append('<tr><td class="text-right" colspan="5"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date3 tbody').append('<tr><td class="text-center" colspan="6"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPesananPembelianProduk(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Pembelian Per Produk -->
		
		<!-- Script Daftar Tagihan -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_f').daterangepicker({
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

            $('#filter_date_f').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate4();
            });

            function TableDate4() {
                $('#table-date4').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date4 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date4'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_f').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date4 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date4 tbody').append('<tr onclick="NextShowDaftarTagihan(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="9">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date4 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_invoice + '</td><td class="text-left">' + row.nomor_invoice + '</td><td class="text-left">' + row.memo + '</td><td class="text-right">' + row.volume + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.jumlah + '</td><td class="text-right">' + row.ppn + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });
									$('#table-date4 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="8"><b>JUMLAH</b></td><td class="text-right"><b>' + val.jumlah + '</b></td></tr>');
                                });
                                $('#table-date4 tbody').append('<tr><td class="text-right" colspan="8"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date4 tbody').append('<tr><td class="text-center" colspan="9"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowDaftarTagihan(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Daftar Tagihan -->
		
		<!-- Script Hutang -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_g').daterangepicker({
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

            $('#filter_date_g').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate5();
            });

            function TableDate5() {
                $('#table-date5').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date5 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date5'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_g').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date5 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date5 tbody').append('<tr onclick="NextShowDaftarTagihan(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="6">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date5 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_invoice + '</td><td class="text-center">' + row.nomor_invoice + '</td><td class="text-left">' + row.memo + '</td><td class="text-right">' + row.tagihan + '</td><td class="text-right">' + row.pembayaran + '</td><td class="text-right">' + row.hutang + '</td></tr>');
                                    });
									$('#table-date5 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="4"><b>JUMLAH</b></td><td class="text-right""><b>' + val.total_tagihan + '</b></td><td class="text-right""><b>' + val.total_pembayaran + '</b></td><td class="text-right""><b>' + val.total_hutang + '</b></td></tr>');
                                });
                                $('#table-date5 tbody').append('<tr><td class="text-right" colspan="6"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date5 tbody').append('<tr><td class="text-center" colspan="7"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowDaftarTagihan(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

        </script>
		
		<!-- End Hutang -->
		
		<!-- Script Umur Hutang -->

        <script type="text/javascript">
			$('#filter_date_h').daterangepicker({
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

				$('#filter_date_h').on('apply.daterangepicker', function(ev, picker) {
					  $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
					  TableDate6();
				});


				function TableDate6()
				{
					$('#wait').fadeIn('fast');   
					$.ajax({
						type    : "POST",
						url     : "<?php echo site_url('pmm/receipt_material/umur_hutang'); ?>/"+Math.random(),
						dataType : 'html',
						data: {
							filter_date : $('#filter_date_h').val(),
						},
						success : function(result){
							$('#table-date6').html(result);
							$('#wait').fadeOut('fast');
						}
					});
				}

				TableDate6();
			
            </script>
		
		<!-- End Umur Hutang -->
		
		<!-- Script Daftar Pembayaran -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_i').daterangepicker({
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

            $('#filter_date_i').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate7();
            });

            function TableDate7() {
                $('#table-date7').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date7 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date7'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_i').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date7 tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date7 tbody').append('<tr onclick="NextShowDaftarPembayaran(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="5">' + val.supplier_name + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date7 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_pembayaran + '</td><td class="text-center">' + row.nomor_transaksi + '</td><td class="text-center">' + row.tanggal_invoice + '</td><td class="text-center">' + row.nomor_invoice + '</td><td class="text-right">' + row.pembayaran + '</td></tr>');
                                    });
									$('#table-date7 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="5"><b>JUMLAH</b></td><td class="text-right"">' + val.total_bayar + '</td></tr>');
                                });
                                $('#table-date7 tbody').append('<tr><td class="text-right" colspan="5"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-date7 tbody').append('<tr><td class="text-center" colspan="6"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowDaftarPembayaran(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }
			

        </script>
		
		<!-- End Daftar Pembayaran -->
		
		<!-- Script Penyelesaian Pembelian -->
		
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_k').daterangepicker({
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

            $('#filter_date_k').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDate9();
            });

            function TableDate9() {
                $('#table-date9').show();
                $('#loader-table').fadeIn('fast');
                $('#table-date9 tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_date9'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_k').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-date9 tbody').html('');

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
                                    window.vol_hutang_penerimaan = 0;
                                    window.hutang_penerimaan = 0;
                                    window.vol_sisa_tagihan = 0;
                                    window.sisa_tagihan = 0;
                                    window.vol_akhir = 0;
                                    window.akhir = 0;

                                    $('#table-date9 tbody').append('<tr onclick="NextShowPenyelesaianPembelian(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="16">' + val.nama + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date9 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.date_po + '</td><td class="text-center">' + row.no_po + '</td><td class="text-right">' + row.vol_pemesanan + '</td><td class="text-right">' + row.pemesanan + '</td><td class="text-right">' + row.vol_pengiriman + '</td><td class="text-right">' + row.pengiriman + '</td><td class="text-right">' + row.vol_tagihan + '</td><td class="text-right">' + row.tagihan + '</td><td class="text-right">' + row.vol_pembayaran + '</td><td class="text-right">' + row.pembayaran + '</td><td class="text-right">' + row.vol_hutang_penerimaan + '</td><td class="text-right">' + row.hutang_penerimaan + '</td><td class="text-right">' + row.vol_sisa_tagihan + '</td><td class="text-right">' + row.sisa_tagihan + '</td><td class="text-right">' + row.vol_akhir + '</td><td class="text-right">' + row.akhir + '</td></tr>');
                                        
                                        window.vol_pemesanan += parseFloat(row.vol_pemesanan.replace(/\./g,'').replace(',', '.'));
                                        window.pemesanan += parseFloat(row.pemesanan.replace(/\./g,'').replace(',', '.'));
                                        window.vol_pengiriman += parseFloat(row.vol_pengiriman.replace(/\./g,'').replace(',', '.'));
                                        window.pengiriman += parseFloat(row.pengiriman.replace(/\./g,'').replace(',', '.'));
                                        window.vol_tagihan += parseFloat(row.vol_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.tagihan += parseFloat(row.tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.vol_pembayaran += parseFloat(row.vol_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.pembayaran += parseFloat(row.pembayaran.replace(/\./g,'').replace(',', '.'));
                                        console.log(pembayaran);
                                        window.vol_hutang_penerimaan += parseFloat(row.vol_hutang_penerimaan.replace(/\./g,'').replace(',', '.'));
                                        window.hutang_penerimaan += parseFloat(row.hutang_penerimaan.replace(/\./g,'').replace(',', '.'));
                                        window.vol_sisa_tagihan += parseFloat(row.vol_sisa_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.sisa_tagihan += parseFloat(row.sisa_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.vol_akhir += parseFloat(row.vol_akhir.replace(/\./g,'').replace(',', '.'));
                                        window.akhir += parseFloat(row.akhir.replace(/\./g,'').replace(',', '.'));

                                    });
									$('#table-date9 tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="3"><b>JUMLAH</b></td><td class="text-right"><b>' + formatter.format(window.vol_pemesanan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.pemesanan) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_pengiriman) + '</b></td><td class="text-right"><b>' + formatter2.format(window.pengiriman) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_pembayaran) + '</b></td><td class="text-right""><b>' + formatter2.format(window.pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_hutang_penerimaan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.hutang_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_sisa_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.sisa_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.vol_akhir) + '</b></td><td class="text-right"><b>' + formatter2.format(window.akhir) + '</b></td></tr>');
                                });
                                $('#table-date9 tbody').append('<tr><td class="text-right" colspan="3"><b>TOTAL</b></td><td class="text-right" ><b>' + result.grand_total_vol_pemesanan + '</b></td><td class="text-right" ><b>' + result.grand_total_pemesanan + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_pengiriman + '</b></td><td class="text-right" ><b>' + result.grand_total_pengiriman + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_pembayaran + '</b></td><td class="text-right" ><b>' + result.grand_total_pembayaran + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_hutang_penerimaan + '</b></td><td class="text-right" ><b>' + result.grand_total_hutang_penerimaan + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_sisa_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_sisa_tagihan + '</b></td><td class="text-right" ><b>' + result.grand_total_vol_akhir + '</b></td><td class="text-right" ><b>' + result.grand_total_akhir + '</b></td></tr>');
                            } else {
                                $('#table-date9 tbody').append('<tr><td class="text-center" colspan="17"><b>NO DATA</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPenyelesaianPembelian(id) {
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
		
		<!-- End Penyelesaian  Pembelian -->

</body>

</html>