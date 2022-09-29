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
                            <li><a href="<?php echo site_url('admin/penjualan'); ?>"> Penjualan</a></li>
                            <li><a href="<?php echo site_url('admin/penjualan'); ?>"> Tagihan Penjualan</a></li>
                            <li><a href="<?= base_url('penjualan/detailPenagihan/' . $bayar["penagihan_id"]) ?>"> Penerimaan Penjualan</a></li>
                            <li><a>Edit Penerimaan Penjualan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="text-right">
                                    <h3 class="pull-left">Edit Penerimaan Penjualan</h3>
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
                                            <input type="text" class="form-control text-center" name="nama_pelanggan" required="" value="<?= $bayar['nama_pelanggan'] ?>" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Setor Ke</label>
                                            <select class="form-control text-center" name="setor_ke" required="">
                                                <option value="">Setor Ke</option>
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
                                            <select class="form-control text-center" name="cara_pembayaran" required="">
                                            <option value="<?= $bayar['cara_pembayaran'] ?>"><?= $bayar['cara_pembayaran'] ?></option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Cek Giro">Cek Giro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <label>Tanggal Pembayaran</label>
                                            <input type="text" class="form-control dtpicker text-center" name="tanggal_pembayaran" required="" value="<?= date('d-m-Y', strtotime($bayar["tanggal_pembayaran"])) ?>" />
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Nomor Transaksi</label>
                                            <input type="text" class="form-control text-center" name="nomor_transaksi" required="" value="<?= $bayar['nomor_transaksi'] ?>" />
                                        </div>
                                    </div>
                                    <br />
                                    <br />
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
                                                    <th width="25%">Pembayaran saat ini</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center"><?= date('d-m-Y', strtotime($pembayaran["tanggal_invoice"])) ?></td>
                                                    <td class="text-center"><?= $pembayaran["nomor_invoice"] ?></td>
                                                    <td class="text-right"><?= number_format($pembayaran['total'],0,',','.'); ?></td>
                                                    <td class="text-right"><?= number_format($sisa_tagihan,0,',','.'); ?></td>
                                                    <td><input type="text" name="pembayaran" id="pembayaran" class="form-control numberformat text-right" value="<?= intval($bayar['pembayaran']) ?>"></td>
                                                </tr>
                                            </tbody>
                                            <tfoot style="font-size:15px;">
                                                <th colspan="4" class="text-right">TOTAL</th>
                                                <th id="total-bayar" class="text-right"></th>
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
                                                <input type="file" class="form-control" name="files[]" multiple="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= base_url('penjualan/detailPenagihan/' . $bayar["penagihan_id"]) ?>" class="btn btn-info" style="margin-bottom:0;"><i class="fa fa-times"></i> Kembali</a>
                                            <button type="button" id="tombol_hapus" class="btn btn-danger"><i class="fa fa-times"></i> Hapus</button>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Kirim</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
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
        $('.form-select2').select2();

        $('input.numberformat').number(true, 0, ',', '.');
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

        $('#pembayaran').keyup(function() {
            console.log($(this).val());
            $('#total-bayar').text($.number($(this).val(), 0, ',', '.'));
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


        $('#tombol_hapus').click(function() {
            bootbox.confirm({
                message: "Apakah anda yakin untuk menghapus data ini?",
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
                        $.post('<?= base_url() . 'penjualan/hapus_pembayaran/' . $bayar['id'] ?>', {}, function($response) {
                            top.location.href = '<?= base_url() . 'penjualan/detailPenagihan/' . $bayar['penagihan_id'] ?>';
                        });
                    }

                }
            });
        });

        $(document).ready(function() {
            $('#pembayaran').keyup();
        });
    </script>


</body>

</html>