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
                            <li>
                                <a href="<?php echo site_url('admin/penjualan'); ?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Penerimaan Penjualan</a>
                            </li>
                            <li><a>Pembayaran</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="text-right">
                                    <h3 class="pull-left">Penerimaan Penjualan</h3>
                                    <a href="<?php echo site_url('admin/penjualan'); ?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('penjualan/simpan_pembayaran'); ?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" name="id" value="<?= $bayar["id"] ?>">
                                    <input type="hidden" name="id_penagihan" value="<?= $bayar["penagihan_id"] ?>">
                                    <input type="hidden" name="client_id" value="<?= $bayar['client_id']; ?>">
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Pelanggan</label>
                                            <input type="text" class="form-control" name="nama_pelanggan" required="" readonly value="<?= $bayar['nama_pelanggan'] ?>" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Setor Ke</label>
                                            <select disabled class="form-control" name="setor_ke" required="">
                                                <option selected readonly value="">Setor Ke</option>
                                                <?php
                                                if (!empty($setor_bank)) {
                                                    foreach ($setor_bank as $key => $sb) {
                                                ?>
                                                        <option value="<?= $sb['id']; ?>" <?= ($sb['id'] == $bayar['setor_ke']) ? 'selected' : '' ?>><?= $sb['coa']; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>

                                        </div>
                                        <div class="col-sm-2">
                                            <label>Cara Pembayaran</label>
                                            <input type="text" class="form-control" name="cara_pembayaran" required="" readonly value="<?= $bayar['cara_pembayaran'] ?>" />
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Tanggal Pembayaran</label>
                                            <input type="text" disabled class="form-control dtpicker" name="tanggal_pembayaran" required="" readonly value="<?= $bayar['tanggal_pembayaran'] ?>" />
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Nomor Transaksi</label>
                                            <input type="text" class="form-control" name="nomor_transaksi" required="" readonly value="<?= $bayar['nomor_transaksi'] ?>" />
                                        </div>
                                    </div>
                                    <br />
                                    <br>
                                    <?php
                                    $sisa_tagihan = $pembayaran['total'] - $total_bayar['total'];
                                    // echo $sisa_tagihan;

                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Tanggal Invoice</th>
                                                    <th class="text-center">Nomor Invoice</th>
                                                    <th class="text-center">Total Invoice</th>
                                                    <th class="text-center">Sisa Tagihan</th>
                                                    <th class="text-center" width="25%">Pembayaran Saat Ini</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center"><?= date('d-m-Y', strtotime($pembayaran["tanggal_invoice"])) ?></td>
                                                    <td class="text-center"><?= $pembayaran["nomor_invoice"] ?></td>
                                                    <td class="text-right"><?= number_format($pembayaran['total'],0,',','.'); ?></td>
                                                    <td class="text-right"><?= number_format($sisa_tagihan,0,',','.'); ?></td>
                                                    <td class="text-right"><?= number_format($bayar['pembayaran'],0,',','.'); ?></td>
                                                </tr>
                                            </tbody>
                                            <tfoot style="font-size:15px;">
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Memo</label>
                                                <textarea class="form-control" name="memo" rows="3" value="<?= $bayar['memo'] ?>"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <?php
                                                if (!empty($dataLampiran)) {
                                                    foreach ($dataLampiran as $key => $lampiran) {
                                                ?>
                                                        <div><a href="<?= base_url() . 'uploads/pembayaran/' . $lampiran['lampiran']; ?>" target="_blank"><?= $lampiran['lampiran']; ?></a></div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= base_url('penjualan/detailPenagihan/' . $bayar["penagihan_id"]) ?>" class="btn btn-info" style="margin-bottom:0;"><i class="fa fa-mail-replay"></i> Kembali</a>
                                            <a href="<?= base_url('penjualan/cetak_pembayaran/' . $bayar["id"]) ?>" target="_blank" class="btn btn-info" style="margin-bottom:0;"><i class="fa fa-print"></i> Cetak PDF</a>
                                            <a href="<?= base_url('penjualan/sunting_pembayaran/' . $bayar["id"]) ?>" class="btn btn-warning" style="margin-bottom:0;"><i class="fa fa-check"></i> Edit</a>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
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

</body>

</html>