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
                                    <div role="tabpanel" class="tab-pane active" id="penjualan">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Pengiriman Penjualan</h5></p>
                                                        <a href="#laporan_pengiriman_penjualan" aria-controls="laporan_pengiriman_penjualan" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>									
                                                    </div>
                                                    <div class="col-sm-5">
														<p><h5>Laporan Piutang</h5></p>
                                                        <a href="#laporan_piutang" aria-controls="laporan_piutang" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Monitoring Piutang</h5></p>
                                                        <a href="#monitoring_piutang" aria-controls="monitoring_piutang" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Daftar Penerimaan</h5></p>
                                                        <a href="#daftar_penerimaan" aria-controls="daftar_penerimaan" role="tab" data-toggle="tab" class="btn btn-primary" style="border-radius:10px; font-weight:bold;">Lihat Laporan</a>
													</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_pengiriman_penjualan'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_a" name="filter_date" class="form-control dtpicker" value="" autocomplete="off" placeholder="Filter By Date">
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
                                                            <div class="col-sm-1 text-right">
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
                                                        <table class="mytable table table-hover table-center table-bordered" id="table-penjualan" style="display:none;">
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
                                                        <form action="<?php echo site_url('laporan/cetak_laporan_piutang'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_piutang" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-hover table-center table-bordered" id="laporan-piutang" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PENJUALAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">TAGIHAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">TAGIHAN BRUTO</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PENERIMAAN PEMBAYARAN</th>
                                                                <th class="text-center"colspan="2">SISA PIUTANG</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. PESANAN PEMBELIAN</th>
                                                                <th class="text-center">PENJUALAN</th>
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

                                    <!-- Laporan Monitoring Piutang -->
                                    <div role="tabpanel" class="tab-pane" id="monitoring_piutang">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">  
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Laporan Monitoring Piutang</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <?php
                                                $kategori = $this->db->order_by('nama_kategori_produk', 'asc')->get_where('kategori_produk', array('status' => 'PUBLISH'))->result_array();
                                                $status_pembayaran = $this->db->group_by('status_pembayaran', 'asc')->get_where('pmm_penagihan_penjualan')->result_array();
                                                ?>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_monitoring_piutang'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_monitoring_piutang" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select id="filter_kategori_monitoring_piutang" name="filter_kategori" class="form-control select2">
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
                                                                <select id="filter_status_monitoring_piutang" name="filter_status" class="form-control select2">
                                                                    <option value="">Pilih Status</option>
                                                                    <?php
                                                                    foreach ($status_pembayaran as $key => $st) {
                                                                    ?>
                                                                        <option value="<?php echo $st['status_pembayaran']; ?>"><?php echo $st['status_pembayaran']; ?></option>
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
                                                        <table class="mytable table table-hover table-center table-bordered" id="monitoring-piutang" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO. INV</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">TGL. INV</th>
                                                                <th class="text-center" colspan="3">TAGIHAN</th>
                                                                <th class="text-center" colspan="3">PENERIMAAN</th>
                                                                <th class="text-center" colspan="3">SISA PIUTANG</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">STATUS</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">UMUR</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">KETERANGAN</th>
                                                                <th class="text-center">DPP</th>
                                                                <th class="text-center">PPN</th>
                                                                <th class="text-center">JUMLAH</th>
                                                                <th class="text-center">DPP</th>
                                                                <th class="text-center">PPN</th>
                                                                <th class="text-center">JUMLAH</th>
                                                                <th class="text-center">DPP</th>
                                                                <th class="text-center">PPN</th>
                                                                <th class="text-center">JUMLAH</th>
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

                                    <!-- Daftar Penerimaan -->
									<div role="tabpanel" class="tab-pane" id="daftar_penerimaan">
                                        <div class="col-sm-15">
                                            <div class="panel panel-default">      
												<div class="panel-heading">												
                                                    <h3 class="panel-title">Daftar Penerimaan</h3>
													<a href="laporan_penjualan">Kembali</a>
                                                </div>
                                                <div style="margin: 20px">
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_daftar_penerimaan'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_penerimaan" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
                                                            </div>                                                           
                                                            <div class="col-sm-3">
                                                                <button class="btn btn-info" type="submit" id="btn-print" style="border-radius:10px; font-weight:bold;"><i class="fa fa-print"></i> Print</button>
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="daftar-penerimaan" style="display:none" width="100%";>
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
                                $('#table-penjualan tbody').append('<tr style="background-color:#cccccc;"><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total_volume + '</b></td><td class="text-right" ></td><td class="text-right" ><b>' + result.total_nilai + '</b></td></tr>');
                            } else {
                                $('#table-penjualan tbody').append('<tr><td class="text-center" colspan="7"><b>Tidak Ada Data</b></td></tr>');
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

        <!-- Script Piutang -->
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_piutang').daterangepicker({
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

            $('#filter_date_piutang').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('01-01-2021') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                LaporanPiutang();
            });

            function LaporanPiutang() {
                $('#laporan-piutang').show();
                $('#loader-table').fadeIn('fast');
                $('#laporan-piutang tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/laporan_piutang'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_piutang').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#laporan-piutang tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    
                                    window.jumlah_penerimaan = 0;
                                    window.jumlah_tagihan = 0;
                                    window.jumlah_tagihan_bruto = 0;
                                    window.jumlah_pembayaran = 0;
                                    window.jumlah_sisa_piutang_penerimaan = 0;
                                    window.jumlah_sisa_piutang_tagihan = 0;

                                    $.each(val.mats, function(a, row) {
                                        window.jumlah_penerimaan += parseFloat(row.penerimaan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_tagihan += parseFloat(row.tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_tagihan_bruto += parseFloat(row.tagihan_bruto.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_pembayaran += parseFloat(row.pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_sisa_piutang_penerimaan += parseFloat(row.sisa_piutang_penerimaan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_sisa_piutang_tagihan += parseFloat(row.sisa_piutang_tagihan.replace(/\./g,'').replace(',', '.'));
                                    });

                                    $('#laporan-piutang tbody').append('<tr onclick="NextShowPiutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.name + '</td><td class="text-right"><b>' + formatter.format(window.jumlah_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan_bruto) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_piutang_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_piutang_tagihan) + '</b></td></tr>');
                                    //$('#laporan-piutang tbody').append('<tr onclick="NextShowPiutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-right">' + val.total_penerimaan + '</td><td class="text-right">' + val.total_tagihan + '</td><td class="text-right"></td><td class="text-right"></td><td class="text-right"></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#laporan-piutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.salesPo_id + '</td><td class="text-right">' + row.penerimaan + '</td><td class="text-right">' + row.tagihan + '</td><td class="text-right">' + row.tagihan_bruto + '</td><td class="text-right">' + row.pembayaran + '</td><td class="text-right">' + row.sisa_piutang_penerimaan + '</td><td class="text-right">' + row.sisa_piutang_tagihan + '</td></tr>');   
                                    });
                                    $('#laporan-piutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="2"><b>JUMLAH</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan_bruto) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_piutang_penerimaan) + '</b></td><td class="text-right"><b></b></td></tr>');
                                });
                                $('#laporan-piutang tbody').append('<tr style="background-color:#cccccc;"><td class="text-right" colspan="2"><b>TOTAL</b></td><td class="text-right"><b>' + result.total_penerimaan + '</b></td><td class="text-right"><b>' + result.total_tagihan + '</b></td><td class="text-right"><b>' + result.total_tagihan_bruto + '</b></td><td class="text-right"><b>' + result.total_pembayaran + '</b></td><td class="text-right"><b>' + result.total_sisa_piutang_penerimaan + '</b></td><td class="text-right"><b>' + result.total_sisa_piutang_tagihan + '</b></td></tr>');
                            } else {
                                $('#laporan-piutang tbody').append('<tr><td class="text-center" colspan="8"><b>Tidak Ada Data</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowPiutang(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            window.formatter = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '0'
            });

        </script>

        <!-- Script Monitoring Piutang -->
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_monitoring_piutang').daterangepicker({
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

            $('#filter_date_monitoring_piutang').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('01-01-2021') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                LaporanMonitoringPiutang();
            });

            function LaporanMonitoringPiutang() {
                $('#monitoring-piutang').show();
                $('#loader-table').fadeIn('fast');
                $('#monitoring-piutang tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/monitoring_piutang'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_monitoring_piutang').val(),
                        filter_kategori: $('#filter_kategori_monitoring_piutang').val(),
                        filter_status: $('#filter_status_monitoring_piutang').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#monitoring-piutang tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    
                                    window.jumlah_dpp_tagihan = 0;
                                    window.jumlah_ppn_tagihan = 0;
                                    window.jumlah_jumlah_tagihan = 0;
                                    window.jumlah_dpp_pembayaran = 0;
                                    window.jumlah_ppn_pembayaran = 0;
                                    window.jumlah_jumlah_pembayaran = 0;
                                    window.jumlah_dpp_sisa_piutang = 0;
                                    window.jumlah_ppn_sisa_piutang = 0;
                                    window.jumlah_jumlah_sisa_piutang = 0;

                                    $.each(val.mats, function(a, row) {
                                        window.jumlah_dpp_tagihan += parseFloat(row.dpp_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_ppn_tagihan += parseFloat(row.ppn_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_jumlah_tagihan += parseFloat(row.jumlah_tagihan.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_dpp_pembayaran += parseFloat(row.dpp_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_ppn_pembayaran += parseFloat(row.ppn_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_jumlah_pembayaran += parseFloat(row.jumlah_pembayaran.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_dpp_sisa_piutang += parseFloat(row.dpp_sisa_piutang.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_ppn_sisa_piutang += parseFloat(row.ppn_sisa_piutang.replace(/\./g,'').replace(',', '.'));
                                        window.jumlah_jumlah_sisa_piutang += parseFloat(row.jumlah_sisa_piutang.replace(/\./g,'').replace(',', '.'));
                                    });

                                    $('#monitoring-piutang tbody').append('<tr onclick="NextShowMonitoringPiutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="3">' + val.name + '</td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_sisa_piutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_sisa_piutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_sisa_piutang) + '</b></td><td class="text-right"></td></td><td class="text-right"></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#monitoring-piutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.subject + '</td><td class="text-left">' + row.nomor_invoice + '</td><td class="text-right">' + row.tanggal_invoice + '</td><td class="text-right">' + row.dpp_tagihan + '</td><td class="text-right">' + row.ppn_tagihan + '</td><td class="text-right">' + row.jumlah_tagihan + '</td><td class="text-right">' + row.dpp_pembayaran + '</td><td class="text-right">' + row.ppn_pembayaran + '</td><td class="text-right">' + row.jumlah_pembayaran + '</td><td class="text-right">' + row.dpp_sisa_piutang + '</td><td class="text-right">' + row.ppn_sisa_piutang + '</td><td class="text-right">' + row.jumlah_sisa_piutang + '</td><td class="text-right">' + row.status_pembayaran + '</td><td class="text-center">' + row.syarat_pembayaran + '</td></tr>');   
                                    });
                                    $('#monitoring-piutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="4"><b>JUMLAH</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_dpp_sisa_piutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_ppn_sisa_piutang) + '</b></td><td class="text-right"><b>' + formatter2.format(window.jumlah_jumlah_sisa_piutang) + '</b></td><td class="text-right"></td></td><td class="text-right"></td></tr>');
                                });
                                $('#monitoring-piutang tbody').append('<tr style="background-color:#cccccc;"><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right"><b>' + result.total_dpp_tagihan + '</b></td><td class="text-right"><b>' + result.total_ppn_tagihan + '</b></td><td class="text-right"><b>' + result.total_jumlah_tagihan + '</b></td><td class="text-right"><b>' + result.total_dpp_pembayaran + '</b></td><td class="text-right"><b>' + result.total_ppn_pembayaran + '</b></td><td class="text-right"><b>' + result.total_jumlah_pembayaran + '</b></td><td class="text-right"><b>' + result.total_dpp_sisa_piutang + '</b></td><td class="text-right"><b>' + result.total_ppn_sisa_piutang + '</b></td><td class="text-right"><b>' + result.total_jumlah_sisa_piutang + '</b></td></td><td class="text-right"></td></td><td class="text-right"></td></tr>');
                            } else {
                                $('#monitoring-piutang tbody').append('<tr><td class="text-center" colspan="13"><b>Tidak Ada Data</b></td></tr>');
                            }
                            $('#loader-table').fadeOut('fast');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }

            function NextShowMonitoringPiutang(id) {
                console.log('.mats-' + id);
                $('.mats-' + id).slideToggle();
            }

            $('#filter_kategori_monitoring_piutang').change(function() {
                LaporanMonitoringPiutang();
            });

            $('#filter_status_monitoring_piutang').change(function() {
                LaporanMonitoringPiutang();
            });

            window.formatter2 = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '0'
            });

        </script>

        <!-- Script Penerimaan -->
		<script type="text/javascript">
            $('input.numberformat').number(true, 4, ',', '.');
            $('#filter_date_penerimaan').daterangepicker({
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

            $('#filter_date_penerimaan').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                DaftarPenerimaan();
            });

            function DaftarPenerimaan() {
                $('#daftar-penerimaan').show();
                $('#loader-table').fadeIn('fast');
                $('#daftar-penerimaan tbody').html('');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('pmm/productions/daftar_penerimaan'); ?>/" + Math.random(),
                    dataType: 'json',
                    data: {
                        filter_date: $('#filter_date_penerimaan').val(),
                    },
                    success: function(result) {
                        if (result.data) {
                            $('#daftar-penerimaan tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#daftar-penerimaan tbody').append('<tr onclick="NextShowPenerimaan(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.nama + '</td><td></td><td></td><td></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#daftar-penerimaan tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-center">' + row.tanggal_pembayaran + '</td><td class="text-center">' + row.nomor_transaksi + '</td></td><td class="text-center">' + row.nomor_invoice + '</td><td class="text-right">' + row.penerimaan + '</td></tr>');
                                    });
                                });
                                $('#daftar-penerimaan tbody').append('<tr><td class="text-right" colspan="4"><b>TOTAL</b></td><td class="text-right" ><b>' + result.total + '</b></td></tr>');
                            } else {
                                $('#daftar-penerimaan tbody').append('<tr><td class="text-center" colspan="5"><b>NO DATA</b></td></tr>');
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

</body>

</html>