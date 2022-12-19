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
                                    <div role="tabpanel" class="tab-pane active" id="penjualan">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Pengiriman Penjualan</h5></p>
                                                        <a href="#laporan_pengiriman_penjualan" aria-controls="laporan_pengiriman_penjualan" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>									
                                                    </div>
                                                    <div class="col-sm-5">
														<p><h5>Laporan Piutang</h5></p>
                                                        <a href="#laporan_piutang" aria-controls="laporan_piutang" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="laporan-piutang" style="display:none" width="100%";>
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">NO.</th>
                                                                <th class="text-center">REKANAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PENJUALAN</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">TAGIHAN</th>
																<th class="text-center" rowspan="2" style="vertical-align:middle;">TAGIHAN BRUTO</th>
                                                                <th class="text-center" rowspan="2" style="vertical-align:middle;">PEMBAYARAN</th>
                                                                <th class="text-center"colspan="2">SISA HUTANG</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center">NO. PESANAN PEMBELIAN</th>
                                                                <th class="text-center">PENERIMAAN</th>
                                                                <th class="text-center">INVOICE</th>
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

                                    $('#laporan-piutang tbody').append('<tr onclick="NextShowHutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left">' + val.name + '</td><td class="text-right"><b>' + formatter.format(window.jumlah_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan_bruto) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_piutang_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_piutang_tagihan) + '</b></td></tr>');
                                    //$('#laporan-piutang tbody').append('<tr onclick="NextShowHutang(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;background-color:#FF0000"><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-right">' + val.total_penerimaan + '</td><td class="text-right">' + val.total_tagihan + '</td><td class="text-right"></td><td class="text-right"></td><td class="text-right"></td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#laporan-piutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.salesPo_id + '</td><td class="text-right">' + row.penerimaan + '</td><td class="text-right">' + row.tagihan + '</td><td class="text-right">' + row.tagihan_bruto + '</td><td class="text-right">' + row.pembayaran + '</td><td class="text-right">' + row.sisa_piutang_penerimaan + '</td><td class="text-right">' + row.sisa_piutang_tagihan + '</td></tr>');   
                                    });
                                    $('#laporan-piutang tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-right" colspan="2"><b>JUMLAH</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_penerimaan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_tagihan_bruto) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_pembayaran) + '</b></td><td class="text-right"><b>' + formatter.format(window.jumlah_sisa_piutang_penerimaan) + '</b></td><td class="text-right"><b></b></td></tr>');
                                });
                                $('#laporan-piutang tbody').append('<tr><td class="text-right" colspan="2"><b>TOTAL</b></td><td class="text-right"><b>' + result.total_penerimaan + '</b></td><td class="text-right"><b>' + result.total_tagihan + '</b></td><td class="text-right"><b>' + result.total_tagihan_bruto + '</b></td><td class="text-right"><b>' + result.total_pembayaran + '</b></td><td class="text-right"><b>' + result.total_sisa_piutang_penerimaan + '</b></td><td class="text-right"><b>' + result.total_sisa_piutang_tagihan + '</b></td></tr>');
                            } else {
                                $('#laporan-piutang tbody').append('<tr><td class="text-center" colspan="8"><b>NO DATA</b></td></tr>');
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

            window.formatter = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '0'
            });

        </script>

</body>

</html>