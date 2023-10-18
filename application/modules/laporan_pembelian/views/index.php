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
                                    <div role="tabpanel" class="tab-pane active" id="pembelian">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Penerimaan Pembelian</h5></p>
                                                        <a href="#laporan_penerimaan_pembelian" aria-controls="laporan_penerimaan_pembelian" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Laporan Hutang</h5></p>
                                                        <a href="#laporan_hutang" aria-controls="laporan_hutang" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
                                                    </div>
                                                    <div class="col-sm-5">
														<p><h5>Monitoring Hutang</h5></p>
                                                        <a href="#monitoring_hutang" aria-controls="monitoring_hutang" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
                                                    </div>
                                                    <div class="col-sm-5">
														<p><h5>Daftar Pembayaran</h5></p>
                                                        <a href="#daftar_pembayaran" aria-controls="daftar_pembayaran" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                    $materials = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
                                                    $kategori = $this->db->order_by('nama_kategori_produk', 'asc')->get_where('kategori_produk', array('status' => 'PUBLISH'))->result_array();
                                                    $status = $this->db->group_by('status', 'asc')->get_where('pmm_penagihan_pembelian')->result_array();
                                                    ?>
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penerimaan_pembelian'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_penerimaan_pembelian" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_kategori_b" name="filter_kategori" class="form-control select2">
                                                                    <option value="">Pilih Kategori</option>
                                                                    <?php
                                                                    foreach ($kategori as $key => $kat) {
                                                                    ?>
                                                                        <option value="<?php echo $kat['id']; ?>"><?php echo $kat['nama_kategori_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>      
                                                            <div class="col-sm-3">
                                                                <select id="filter_material_penerimaan_pembelian" name="filter_material" class="form-control select2">
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
                                                                <select id="filter_supplier_penerimaan_pembelian" name="supplier_id" class="form-control select2">
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
                                                            <br /><br />
                                                            <div class="col-sm-9 text-left">
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
                                                        <table class="mytable table table-hover table-center table-bordered" id="penerimaan-pembelian" style="display:none;">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">VOLUME</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">HARGA SATUAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NILAI</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">PRODUK</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</div>

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
                                                        <form action="<?php echo site_url('laporan/cetak_laporan_hutang'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_hutang" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_kategori_hutang" name="filter_kategori" class="form-control select2">
                                                                    <option value="">Pilih Kategori</option>
                                                                    <?php
                                                                    foreach ($kategori as $key => $kat) {
                                                                    ?>
                                                                        <option value="<?php echo $kat['id']; ?>"><?php echo $kat['nama_kategori_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_supplier_hutang" name="supplier_id" class="form-control select2">
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
                                                        <table class="mytable table table-hover table-center table-bordered" id="laporan-hutang" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PENERIMAAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">TAGIHAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">TAGIHAN BRUTO</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PEMBAYARAN</th>
                                                                <th class="text-center"colspan="2">SISA HUTANG</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. PESANAN PEMBELIAN</th>
                                                                <th class="text-center">PENERIMAAN</th>
                                                                <th class="text-center">TAGIHAN</th>
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

                                    <!-- Laporan Monitoring Hutang -->
                                    <div role="tabpanel" class="tab-pane" id="monitoring_hutang">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">  
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Monitoring Hutang</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_monitoring_hutang'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_monitoring_hutang" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_kategori_monitoring_hutang" name="filter_kategori" class="form-control select2">
                                                                    <option value="">Pilih Kategori</option>
                                                                    <?php
                                                                    foreach ($kategori as $key => $kat) {
                                                                    ?>
                                                                        <option value="<?php echo $kat['id']; ?>"><?php echo $kat['nama_kategori_produk']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_status_monitoring_hutang" name="filter_status" class="form-control select2">
                                                                    <option value="">Pilih Status</option>
                                                                    <?php
                                                                    foreach ($status as $key => $st) {
                                                                    ?>
                                                                        <option value="<?php echo $st['status']; ?>"><?php echo $st['status']; ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div> 
                                                            <div class="col-sm-3">
                                                                <select id="filter_supplier_monitoring_hutang" name="supplier_id" class="form-control select2">
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
                                                            <br /><br />                                           
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
                                                        <table class="mytable table table-hover table-center table-bordered" id="monitoring-hutang" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center">TANGGAL</th>
                                                                <th class="text-center">JENIS</th>
																<th class="text-center">TANGGAL</th>
                                                                <th class="text-center">SYARAT</th>
                                                                <th class="text-center" colspan="3">TAGIHAN</th>
                                                                <th class="text-center" colspan="4">PEMBAYARAN</th>
                                                                <th class="text-center" colspan="3">SISA HUTANG</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">STATUS</th>
                                                                <th class="text-center">TANGGAL</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. TAGIHAN</th>
                                                                <th class="text-center">TAGIHAN</th>
                                                                <th class="text-center">PEMBELIAN</th>
                                                                <th class="text-center">VERIFIKASI</th>
                                                                <th class="text-center">PEMBAYARAN</th>
                                                                <th class="text-center">DPP</th>
                                                                <th class="text-center">PPN</th>
                                                                <th class="text-center">JUMLAH</th>
                                                                <th class="text-center">DPP</th>
                                                                <th class="text-center">PPN</th>
                                                                <th class="text-center">PPH</th>
                                                                <th class="text-center">JUMLAH</th>
                                                                <th class="text-center">DPP</th>
                                                                <th class="text-center">PPN</th>
                                                                <th class="text-center">JUMLAH</th>
                                                                <th class="text-center">JATUH TEMPO</th>
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

                                    <!-- Daftar Pembayaran -->
									<div role="tabpanel" class="tab-pane" id="daftar_pembayaran">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">  
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Daftar Pembayaran</h3>
													<a href="laporan_pembelian">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_daftar_pembayaran'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_pembayaran" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-hover table-center table-bordered" id="table-daftar-pembayaran" style="display:none" width="100%";>
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
            $('#filter_date_penerimaan_pembelian').daterangepicker({
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

            $('#filter_date_penerimaan_pembelian').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                PenerimaanPembelian();
            });

            function PenerimaanPembelian() {
                $('#penerimaan-pembelian').show();
                $('#loader-table').fadeIn('fast');
                $('#penerimaan-pembelian tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/penerimaan_pembelian'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        purchase_order_no: $('#filter_po_id_penerimaan_pembelian').val(),
                        supplier_id: $('#filter_supplier_penerimaan_pembelian').val(),
                        filter_date: $('#filter_date_penerimaan_pembelian').val(),
                        filter_material: $('#filter_material_penerimaan_pembelian').val(),
                        filter_kategori: $('#filter_kategori_b').val(),
                    },
                     success: function(result) {
                        if (result.data) {
                            $('#penerimaan-pembelian tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#penerimaan-pembelian tbody').append('<tr onclick="NextShowPembelian(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.name + '</td><td class="text-center">' + val.measure + '</td><td class="text-right">' + val.volume + '</td><td class="text-right"></td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#penerimaan-pembelian tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">&nbsp;&nbsp;&nbsp;' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
                                    });

                                });
                                $('#penerimaan-pembelian tbody').append('<tr style="background-color:#cccccc;"><td class="text-right" colspan="3"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total_volume + '</b></td><td class="text-right" ></td><td class="text-right" ><b>' + result.total_nilai + '</b></td></tr>');
                            } else {
                                $('#penerimaan-pembelian tbody').append('<tr><td class="text-center" colspan="6"><b>Tidak Ada Data</b></td></tr>');
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

            // PenerimaanPembelian();

            function GetPO() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/get_po_by_supp'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        supplier_id: $('#filter_supplier_penerimaan_pembelian').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#filter_po_id_penerimaan_pembelian').empty();
                            $('#filter_po_id_penerimaan_pembelian').select2({
                                data: result.data
                            });
                            $('#filter_po_id_penerimaan_pembelian').trigger('change');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            $('#filter_supplier_penerimaan_pembelian').change(function() {
                PenerimaanPembelian();
                GetPO();
            });

            $('#filter_po_id_penerimaan_pembelian').change(function() {
                PenerimaanPembelian();
            });

            $('#filter_material_penerimaan_pembelian').change(function() {
                PenerimaanPembelian();
            });

            $('#filter_kategori_b').change(function() {
                PenerimaanPembelian();
            });

        </script>

        <!-- Script Hutang -->
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_hutang').daterangepicker({
                autoUpdateInput: false,
				showDropdowns : true,
                singleDatePicker: true,
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

            $('#filter_date_hutang').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('01-01-2021') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                LaporanHutang();
            });

            function LaporanHutang() {
                $('#laporan-hutang').show();
                $('#loader-table').fadeIn('fast');
                $('#laporan-hutang tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/laporan_hutang'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_hutang').val(),
                        filter_kategori: $('#filter_kategori_hutang').val(),
                        supplier_id: $('#filter_supplier_hutang').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#laporan-hutang tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    
                                    window.jumlah_penerimaan = 0;
                                    window.jumlah_tagihan = 0;
                                    window.jumlah_tagihan_bruto = 0;
                                    window.jumlah_pembayaran = 0;
                                    window.jumlah_sisa_hutang_penerimaan = 0;
                                    window.jumlah_sisa_hutang_tagihan = 0;

                                    $.each(val.mats, function(a, row) {
                                        window.jumlah_penerimaan += parseFloat(row.penerimaan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_tagihan += parseFloat(row.tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_tagihan_bruto += parseFloat(row.tagihan_bruto.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_pembayaran += parseFloat(row.pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_sisa_hutang_penerimaan += parseFloat(row.sisa_hutang_penerimaan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_sisa_hutang_tagihan += parseFloat(row.sisa_hutang_tagihan.replace(/\./g,'').replace(',', '.'));
                                    });

                                    $('#laporan-hutang tbody').append('<tr onclick="NextShowHutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.name + '</td><td class="text-right"><b>' + formatter.format(window.jumlah_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan_bruto) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_hutang_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_hutang_tagihan) + '</b></td></tr>');
                                    //$('#laporan-hutang tbody').append('<tr onclick="NextShowHutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-right">' + val.total_penerimaan + '</td><td class="text-right">' + val.total_tagihan + '</td><td class="text-right"></td><td class="text-right"></td><td class="text-right"></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#laporan-hutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.purchase_order_id + '</td><td class="text-right">' + row.penerimaan + '</td><td class="text-right">' + row.tagihan + '</td><td class="text-right">' + row.tagihan_bruto + '</td><td class="text-right">' + row.pembayaran + '</td><td class="text-right">' + row.sisa_hutang_penerimaan + '</td><td class="text-right">' + row.sisa_hutang_tagihan + '</td></tr>');   
                                    });
                                    $('#laporan-hutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="2"><b>JUMLAH</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan_bruto) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_hutang_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_hutang_tagihan) + '</b></td></tr>');
                                });
                                $('#laporan-hutang tbody').append('<tr style="background-color:#cccccc;"><td class="text-right" colspan="2"><b>TOTAL</b></td><td class="text-right"><b>' + result.total_penerimaan + '</b></td><td class="text-right"><b>' + result.total_tagihan + '</b></td><td class="text-right"><b>' + result.total_tagihan_bruto + '</b></td><td class="text-right"><b>' + result.total_pembayaran + '</b></td><td class="text-right"><b>' + result.total_sisa_hutang_penerimaan + '</b></td><td class="text-right"><b>' + result.total_sisa_hutang_tagihan + '</b></td></tr>');
                                $('#laporan-hutang tbody').append('<tr><td class="text-center" style="background-color:red;color:white;" colspan="8"><blink><b>* Exclude PPN & PPH</b></blink></td></tr>');
                            } else {
                                $('#laporan-hutang tbody').append('<tr><td class="text-center" colspan="8"><b>Tidak Ada Data</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowHutang(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            $('#filter_kategori_hutang').change(function() {
                LaporanHutang();
            });

            $('#filter_supplier_hutang').change(function() {
                LaporanHutang();
            });

            window.formatter = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '0'
            });

        </script>

        <!-- Script Monitoring Hutang -->
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_monitoring_hutang').daterangepicker({
                autoUpdateInput: false,
				showDropdowns : true,
                singleDatePicker: true,
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

            $('#filter_date_monitoring_hutang').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('01-01-2021') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                LaporanMonitoringHutang();
            });

            function LaporanMonitoringHutang() {
                $('#monitoring-hutang').show();
                $('#loader-table').fadeIn('fast');
                $('#monitoring-hutang tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/monitoring_hutang'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_monitoring_hutang').val(),
                        filter_kategori: $('#filter_kategori_monitoring_hutang').val(),
                        filter_status: $('#filter_status_monitoring_hutang').val(),
                        supplier_id: $('#filter_supplier_monitoring_hutang').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#monitoring-hutang tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    
                                    window.jumlah_dpp_tagihan = 0;
                                    window.jumlah_ppn_tagihan = 0;
                                    window.jumlah_jumlah_tagihan = 0;
                                    window.jumlah_dpp_pembayaran = 0;
                                    window.jumlah_ppn_pembayaran = 0;
                                    window.jumlah_pph_pembayaran = 0;
                                    window.jumlah_jumlah_pembayaran = 0;
                                    window.jumlah_dpp_sisa_hutang = 0;
                                    window.jumlah_ppn_sisa_hutang = 0;
                                    window.jumlah_jumlah_sisa_hutang = 0;

                                    $.each(val.mats, function(a, row) {
                                        window.jumlah_dpp_tagihan += parseFloat(row.dpp_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_ppn_tagihan += parseFloat(row.ppn_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_jumlah_tagihan += parseFloat(row.jumlah_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_dpp_pembayaran += parseFloat(row.dpp_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_ppn_pembayaran += parseFloat(row.ppn_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_pph_pembayaran += parseFloat(row.pph_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_jumlah_pembayaran += parseFloat(row.jumlah_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_dpp_sisa_hutang += parseFloat(row.dpp_sisa_hutang.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_ppn_sisa_hutang += parseFloat(row.ppn_sisa_hutang.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_jumlah_sisa_hutang += parseFloat(row.jumlah_sisa_hutang.replace(/\./g,'').replace(',', '.'));
                                    });

                                    $('#monitoring-hutang tbody').append('<tr onclick="NextShowMonitoringHutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="5">' + val.name + '</td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_pph_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_sisa_hutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_sisa_hutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_sisa_hutang) + '</b></td><td class="text-right"></td></td><td class="text-right"></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#monitoring-hutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.nomor_invoice + '</td><td class="text-left">' + row.tanggal_invoice + '</td><td class="text-right">' + row.subject + '</td><td class="text-right">' + row.tanggal_lolos_verifikasi + '</td><td class="text-right">' + row.syarat_pembayaran + '</td><td class="text-right">' + row.dpp_tagihan + '</td><td class="text-right">' + row.ppn_tagihan + '</td><td class="text-right">' + row.jumlah_tagihan + '</td><td class="text-right">' + row.dpp_pembayaran + '</td><td class="text-right">' + row.ppn_pembayaran + '</td><td class="text-right">' + row.pph_pembayaran + '</td><td class="text-right">' + row.jumlah_pembayaran + '</td><td class="text-right">' + row.dpp_sisa_hutang + '</td><td class="text-right">' + row.ppn_sisa_hutang + '</td><td class="text-right">' + row.jumlah_sisa_hutang + '</td><td class="text-right">' + row.status + '</td><td class="text-center">' + row.jatuh_tempo + '</td></tr>');   
                                    });
                                    $('#monitoring-hutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="6"><b>JUMLAH</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_pph_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_sisa_hutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_sisa_hutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_sisa_hutang) + '</b></td></td><td class="text-right"></td>td class="text-right"></td></tr>');
                                });
                                $('#monitoring-hutang tbody').append('<tr style="background-color:#cccccc;"><td class="text-right" colspan="6"><b>TOTAL</b></td><td class="text-right"><b>' + result.total_dpp_tagihan + '</b></td><td class="text-right"><b>' + result.total_ppn_tagihan + '</b></td><td class="text-right"><b>' + result.total_jumlah_tagihan + '</b></td><td class="text-right"><b>' + result.total_dpp_pembayaran + '</b></td><td class="text-right"><b>' + result.total_ppn_pembayaran + '</b></td><td class="text-right"><b>' + result.total_pph_pembayaran + '</b></td><td class="text-right"><b>' + result.total_jumlah_pembayaran + '</b></td><td class="text-right"><b>' + result.total_dpp_sisa_hutang + '</b></td><td class="text-right"><b>' + result.total_ppn_sisa_hutang + '</b></td><td class="text-right"><b>' + result.total_jumlah_sisa_hutang + '</b></td></td><td class="text-right"></td></td><td class="text-right"></td></tr>');
                            } else {
                                $('#monitoring-hutang tbody').append('<tr><td class="text-center" colspan="18"><b>Tidak Ada Data</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowMonitoringHutang(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            $('#filter_kategori_monitoring_hutang').change(function() {
                LaporanMonitoringHutang();
            });

            $('#filter_status_monitoring_hutang').change(function() {
                LaporanMonitoringHutang();
            });

            $('#filter_supplier_monitoring_hutang').change(function() {
                LaporanMonitoringHutang();
            });

            window.formatter2 = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '0'
            });

        </script>

        <!-- Script Daftar Pembayaran -->
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_pembayaran').daterangepicker({
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

            $('#filter_date_pembayaran').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableDaftarPembayaran();
            });

            function TableDaftarPembayaran() {
                $('#table-daftar-pembayaran').show();
                $('#loader-table').fadeIn('fast');
                $('#table-daftar-pembayaran tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/receipt_material/table_daftar_pembayaran'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_pembayaran').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#table-daftar-pembayaran tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-daftar-pembayaran tbody').append('<tr onclick="NextShowDaftarPembayaran(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="5">' + val.supplier_name + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-daftar-pembayaran tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_pembayaran + '</td><td class="text-left">' + row.nomor_transaksi + '</td><td class="text-center">' + row.tanggal_invoice + '</td><td class="text-center">' + row.nomor_invoice + '</td><td class="text-right">' + row.pembayaran + '</td></tr>');
                                    });
                                    $('#table-daftar-pembayaran tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="5"><b>JUMLAH</b></td><td class="text-right"">' + val.total_bayar + '</td></tr>');
                                });
                                $('#table-daftar-pembayaran tbody').append('<tr><td class="text-right" colspan="5"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#table-daftar-pembayaran tbody').append('<tr><td class="text-center" colspan="6"><b>NO DATA</b></td></tr>');
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

</body>
</html>