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
                                    <div role="tabpanel" class="tab-pane active" id="pembelian">
                                        <br />
                                        <div class="row">
                                            <div width="100%">
                                                <div class="panel panel-default">                                            
                                                    <div class="col-sm-5">
														<p><h5>Penerimaan Pembelian</h5></p>
                                                        <a href="#laporan_penerimaan_pembelian" aria-controls="laporan_penerimaan_pembelian" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
													</div>
                                                    <div class="col-sm-5">
														<p><h5>Laporan Hutang</h5></p>
                                                        <a href="#laporan_hutang" aria-controls="laporan_hutang" role="tab" data-toggle="tab" class="btn btn-primary">Lihat Laporan</a>
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
                                                    ?>
                                                    <div class="row">
                                                        <form action="<?php echo site_url('laporan/cetak_penerimaan_pembelian'); ?>" target="_blank">
                                                            <div class="col-sm-3">
                                                                <input type="text" id="filter_date_b" name="filter_date" class="form-control dtpicker" autocomplete="off" placeholder="Filter by Date">
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
                                                        <table class="mytable table table-striped table-hover table-center table-bordered table-condensed" id="laporan-hutang" style="display:none" width="100%";>
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
                        filter_kategori: $('#filter_kategori_b').val(),
                    },
                     success: function(result) {
                        if (result.data) {
                            $('#table-date tbody').html('');

                            if (result.data.length > 0) {
                                $.each(result.data, function(i, val) {
                                    $('#table-date tbody').append('<tr onclick="NextShowPembelian(' + val.no + ')" class="active" style="font-weight:bold;cursor:pointer;"background-color:#FF0000""><td class="text-center">' + val.no + '</td><td class="text-left" colspan="2">' + val.name + '</td><td class="text-center">' + val.measure + '</td><td class="text-right">' + val.volume + '</td><td class="text-right"></td><td class="text-right">' + val.total_price + '</td></tr>');
                                    $.each(val.mats, function(a, row) {
                                        var a_no = a + 1;
                                        $('#table-date tbody').append('<tr style="display:none;" class="mats-' + val.no + '"><td class="text-center"></td><td class="text-left">' + row.purchase_order_id + '</td><td class="text-left">' + row.nama_produk + '</td><td class="text-center">' + row.measure + '</td><td class="text-right">' + row.volume + '</td><td class="text-right">' + row.price + '</td><td class="text-right">' + row.total_price + '</td></tr>');
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

            $('#filter_kategori_b').change(function() {
                TableDate();
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
                                $('#laporan-hutang tbody').append('<tr><td class="text-right" colspan="2"><b>TOTAL</b></td><td class="text-right"><b>' + result.total_penerimaan + '</b></td><td class="text-right"><b>' + result.total_tagihan + '</b></td><td class="text-right"><b>' + result.total_tagihan_bruto + '</b></td><td class="text-right"><b>' + result.total_pembayaran + '</b></td><td class="text-right"><b>' + result.total_sisa_hutang_penerimaan + '</b></td><td class="text-right"><b>' + result.total_sisa_hutang_tagihan + '</b></td></tr>');
                                $('#laporan-hutang tbody').append('<tr><td class="text-center" style="background-color:red;color:white;"colspan="8"><blink><b>* Exclude PPN</b></blink></td></tr>');
                            } else {
                                $('#laporan-hutang tbody').append('<tr><td class="text-center" colspan="8"><b>NO DATA</b></td></tr>');
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

            window.formatter = new Intl.NumberFormat('id-ID', {
                style: 'decimal',
                currency: 'IDR',
                symbol: 'none',
				minimumFractionDigits : '0'
            });

        </script>


</body>
</html>