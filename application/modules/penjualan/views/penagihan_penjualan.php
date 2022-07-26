<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>

    <style type="text/css">
        .table-center th,
        .table-center td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <?php echo $this->Templates->PageHeader(); ?>

        <div class="page-body">
            <?php echo $this->Templates->LeftBar(); ?>
            <div class="content" style="padding:0;">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/penjualan');?>"> Penjualan</a></li>
                            <li><a>Tagihan Penjualan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="text-right">
                                    <h3 class="pull-left">Penagihan Penjualan</h3>
                                    <a href="<?php echo site_url('admin/penjualan'); ?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                            </div>
                            <form id="form-po" action="<?= base_url('penjualan/submit_penagihan_penjualan') ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" name="surat_jalan" value="<?= $id; ?>">
                                <input type="hidden" name="sales_po_id" value="<?= $sales['id']; ?>">
                                <div class="panel-content">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Pelanggan</label>
                                            <input type="text" class="form-control" name="pelanggan" value="<?= $clients['nama'] ?>" required="" readonly="" />
                                            <input type="hidden" name="client_id" value="<?= $query['client_id']; ?>">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Tanggal Kontrak</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_kontrak" required="" value="<?= date("d-m-Y", strtotime($sales['contract_date'])) ?>" readonly="" />
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Tanggal Invoice *</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_invoice" id="tanggal_invoice" required="" />
                                        </div>
                                        <div class="col-sm-5">
                                            <label>Nomor Invoice  *</label>
                                            <input type="text" class="form-control" value="" name="nomor_invoice" required="" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <label>Alamat Pelanggan</label>
                                            <textarea class="form-control" name="alamat_pelanggan" rows="4" required="" readonly=""><?= $clients['alamat']; ?></textarea>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <label>Nomor Kontrak</label>
                                            <input type="text" class="form-control" value="<?= $sales['contract_number']; ?>" name="nomor_kontrak" required="" readonly="" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
										<div class="col-sm-3">
                                            <label>Syarat Pembayaran (Hari)  *</label>
                                            <input type="text" class="form-control" name="syarat_pembayaran" id="syarat_pembayaran" value="<?= $syarat_pembayaran['syarat_pembayaran'];?>" required=""/>
                                        </div>
                                        <!--<div class="col-sm-3">
                                            <label>Tanggal Jatuh Tempo</label>
                                            <input type="text" class="form-control" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" required="" readonly />
                                        </div> -->                                     
                                        <div class="col-sm-6">
                                            <label>Jenis Pekerjaan</label>
                                            <input type="text" class="form-control" value="<?= $sales['jobs_type']; ?>" name="jenis_pekerjaan" required="" readonly="" />
                                        </div>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed text-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="27%">Produk</th>
                                                    <th width="15%">Volume</th>
                                                    <th width="7%">Satuan</th>
                                                    <th width="20%">Harga Satuan</th>
                                                    <th width="20%">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
												$sub_total = 0;
												$tax = 0;
												$tax_pph = 0;
												$tax_ppn = 0;
                                                $tax_ppn11 = 0;
												$tax_0 = false;
												$total = 0;
												?>
                                                <?php foreach ($cekHarga as $key => $row) { ?>
                                                    <input type="hidden" name="surat_jalan<?= $key + 1 ?>" value="<?= $row["no_production"] ?>">
                                                    <input type="hidden" name="production_id_<?= $key + 1 ?>" value="<?= $row["idProduction"] ?>">
                                                    <input type="hidden" name="product_id_<?= $key + 1 ?>" value="<?= $row["product_id"] ?>">
                                                    <tr>
                                                        <td><?= $key + 1 ?>.</td>
                                                        <td>
															<?= $row['nameProduk'] ?>
                                                        </td>
                                                        <td class="text-center">
															<?= $this->filter->Rupiah($row['volume']); ?>
															<input type="hidden" min="0" name="qty_<?= $key+1; ?>" id="qty-<?= $key; ?>" value="<?= $row['volume'];?>" class="form-control input-sm text-center" required="" readonly />
														</td>
                                                        <td class="text-center">
															<?= $row['measure']; ?>
															<input type="hidden" name="measure_<?= $key+1; ?>" id="measure-<?= $key; ?>" class="form-control input-sm text-center" value="<?= $row['measure'];?>" readonly=""  />
														</td>
                                                        <td class="text-right">
                                                            <?= number_format($row['hargaProduk'],0,',','.'); ?>
															<input type="hidden" name="price_<?= $key+1; ?>" id="price-<?= $key; ?>"  class="form-control input-sm text-center" value="<?= $row['hargaProduk'];?>" readonly =""/>
														</td>                                                 
                                                        <td class="text-right">
                                                            <?= number_format($row['hargaProduk'] * $row['volume'],0,',','.'); ?>
                                                            <input type="hidden" name="total_<?= $key + 1; ?>" id="total-<?= $key; ?>" class="form-control numberformat text-right" readonly="" />
                                                        </td>
														<input type="hidden" name="tax_id_<?= $key + 1; ?>" id="tax-id-<?= $key; ?>" class="form-control" value="<?= $row['tax_id'];?>" readonly =""/>
                                                    </tr>
                                                <?php 
													$sub_total += ($row['hargaProduk'] * $row['volume']);
													$tax_id = $row['tax_id'];
													//$tax = $row['tax'];
													
													if($row['tax_id'] == 4){
														$tax_0 = false;
													}
													if($row['tax_id'] == 3){
														$tax_ppn = $sub_total * 10 / 100;
													}
													if($row['tax_id'] == 5){
														$tax_pph = $sub_total * 2 / 100;
													}
                                                    if($row['tax_id'] == 6){
														$tax_ppn11 = $sub_total * 11 / 100;
													}
													
													$total = $sub_total + $tax_ppn - $tax_pph + $tax_ppn11;
												} ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Memo</label>
                                                <textarea class="form-control" name="memo" rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]" multiple="" />
                                            </div>
                                        </div>
                                        <div class="col-sm-8 form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-7 control-label">Sub Total</label>
													<div class="col-sm-5 text-right">
														<label><?= number_format($sub_total,0,',','.'); ?></label>													
															<input type="hidden" name="total_1" value="<?= $sub_total;?>">
													</div>
                                            </div>
                                            <?php
												if($tax_ppn > 0){
													?>
													<div class="form-group">                                                   
                                                        <label class="col-sm-7 control-label">Pajak (PPN 10%)</label>
															<div class="col-sm-5 text-right">
																<label><?= number_format($tax_ppn,0,',','.'); ?></label>
																	<input type="hidden" id="tax_1" name="tax_1" value="<?= $tax_ppn;?>">
															</div>
													</div>
                                                    <?php
												}
											?>
											<?php
												if($tax_0 > 0){
													?>
													<div class="form-group">                                                   
                                                        <label class="col-sm-7 control-label">Pajak (PPN 0%)</label>
															<div class="col-sm-5 text-right">
																<label><?= number_format($tax_0,0,',','.'); ?></label>
																	<input type="hidden" id="tax_1" name="tax_1" value="<?= $tax_0;?>">
															</div>
													</div>                                                  
                                                    <?php
												}
											?>
											<?php
												if($tax_pph > 0){
													?>
													<div class="form-group">                                                   
                                                        <label class="col-sm-7 control-label">Pajak (PPh 23)</label>
															<div class="col-sm-5 text-right">															
																<label><?= number_format($tax_pph,0,',','.'); ?></label>
																	<input type="hidden" id="tax_1" name="tax_1" value="<?= $tax_pph;?>">
															</div>
													</div>
                                                    <?php
												}
											?>
                                            <?php
												if($tax_ppn11 > 0){
													?>
													<div class="form-group">                                                   
                                                        <label class="col-sm-7 control-label">Pajak (PPN 11%)</label>
															<div class="col-sm-5 text-right">															
																<label><?= number_format($tax_ppn11,0,',','.'); ?></label>
																	<input type="hidden" id="tax_1" name="tax_1" value="<?= $tax_ppn11;?>">
															</div>
													</div>
                                                    <?php
												}
											?>
                                            <div class="form-group">
                                                <label class="col-sm-7 control-label">Total</label>
													<div class="col-sm-5 text-right">
														<label id="total" ><?= number_format($total,0,',','.'); ?></label>
															<input type="hidden" id="total-val" name="total" value="<?= $total;?>">
													</div>
                                            </div>
                                            <input type="hidden" name="total_product" id="total-product" value="<?= $key + 1 ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?php echo site_url('admin/penjualan');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Kirim</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    </div>

    <script type="text/javascript">
        var form_control = '';
    </script>
    <?php echo $this->Templates->Footer(); ?>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script type="text/javascript">
        Date.prototype.addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        };

        $('.form-select2').select2();

        $('input.numberformat').number(true, 2, ',', '.');
        $('.dtpicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY'));
            // table.ajax.reload();
        });

        $('#form-po').submit(function(e) {
            e.preventDefault();
            var currentForm = this;
            bootbox.confirm({
                message: "Apakah anda yakin untuk proses data ini ?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function(result) {
                    if (result) {
                        currentForm.submit();
                    }

                }
            });

        });

        $(document).ready(function(e) {
            $('#syarat_pembayaran').change(function(e) {
                let hari = $(this).val();

                if (hari.trim().length == 0) {
                    $('#tanggal_jatuh_tempo').val('');
                } else {
                    let invoiceDate = $('#tanggal_invoice').val();
                    let temp = invoiceDate.split("-");

                    let d = new Date();
                    d.setDate(temp[0]);
                    d.setMonth(temp[1]);
                    d.setFullYear(temp[2]);
                    d.setDate(d.getDate() + parseInt(hari));

                    $('#tanggal_jatuh_tempo').val(d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear());
                }
            });
        });
    </script>


</body>

</html>