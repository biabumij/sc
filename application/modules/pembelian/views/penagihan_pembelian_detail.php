<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>

    <style type="text/css">
        .table-center th,
        .table-center td {
            text-align: center;
        }

        .form-approval {
            display: inline-block;
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
            <div class="content" style="padding:0;">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Pembelian</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Tagihan Pembelian</a></li>
                            <li><a>Detail Tagihan Pembelian</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="">
                                    <h3 class="">
                                        Detail Tagihan Pembelian <?php echo $this->pmm_model->GetStatus3($row['status']);?>
                                    </h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="25%" align="left">Rekanan</th>
                                        <th width="75%" align="left"><label class="label label-default" style="font-size:14px;"><?= $row['supplier']; ?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <th><textarea class="form-control" name="alamat_supplier" id="alamat_supplier"  rows="5" readonly=""><?= $row['supplier_address']; ?></textarea></th>
                                    </tr>
                                    <tr>
                                        <th>No. Pesanan Pembelian</th>
                                        <th><a target="_blank" href="<?= base_url("pmm/purchase_order/manage/".$row['purchase_order_id']) ?>"><?php echo $this->crud_global->GetField('pmm_purchase_order',array('id'=>$row['purchase_order_id']),'no_po');?></a></th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Pesanan Pembelian</th>
                                        <th><?php echo date('d/m/Y',strtotime($row['tanggal_po']));?></th>
                                    </tr>
                                </table>
                                <br />
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="25%" align="left">Nomor Invoice</th>
                                        <th width="75%" align="left"><label class="label label-info" style="font-size:14px;"><?= $row['nomor_invoice']; ?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Invoice</th>
                                        <th><?= date('d/m/Y', strtotime($row['tanggal_invoice'])); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Syarat Pembayaran</th>
                                        <th><?= $row['syarat_pembayaran']; ?> Hari</th>
                                    </tr>
                                    <!--<tr>
                                        <th>Tanggal Jatuh Tempo</th>
                                        <th><?= date('d/m/Y', strtotime($row['tanggal_jatuh_tempo'])); ?></th>
                                    </tr>-->
                                    <tr>
                                        <th>Dibuat Oleh</th>
                                        <th><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');?></th>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Tanggal</th>
                                        <th><?= date('d/m/Y H:i:s',strtotime($row['created_on']));?></th>
                                    </tr>
                                </table>

                            <div class="table-responsive">
                                <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="25%">Produk</th>
                                            <th width="10%">Volume</th>
                                            <th width="10%">Satuan</th>
                                            <th width="10%">Harga Satuan</th>
                                            <th width="10%">Pajak</th>
                                            <th width="10%">Pajak (2)</th>
                                            <th width="20%">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sub_total = 0;
                                        $tax_pph = 0;
                                        $tax_ppn = 0;
                                        $tax_ppn11 = 0;
                                        $tax_0 = false;
                                        $pajak_pph = 0;
                                        $pajak_ppn = 0;
                                        $pajak_ppn11 = 0;
                                        $pajak_0 = false;
                                        $total = 0;
                                        $details = $this->db->get_where('pmm_penagihan_pembelian_detail', array('penagihan_pembelian_id' => $row['id']))->result_array();
                                        ?>
                                        <?php foreach ($details as $key => $dt) { ?>
                                            <?php
                                            $material = $this->crud_global->GetField('produk', array('id' => $dt['material_id']), 'nama_produk');
                                            $tax = $this->crud_global->GetField('pmm_taxs', array('id' => $dt['tax_id']), 'tax_name');
                                            $pajak = $this->crud_global->GetField('pmm_taxs', array('id' => $dt['pajak_id']), 'tax_name');
                                            ?>
                                            <tr>
                                                <td><?= $key + 1 ?>.</td>
                                                <td style="text-align: left !important;">
                                                    <?= $material; ?>
                                                </td>
                                                <td><?= $dt['volume']; ?></td>
                                                <td><?= $dt['measure']; ?></td>
                                                <td style="text-align: right !important;"><?= number_format($dt['price'],0,',','.'); ?></td>
                                                <td> <?= $tax; ?></td>
                                                <input type="hidden" value="<?= $this->filter->Rupiah($dt['tax_id']); ?>">
                                                <td> <?= $pajak; ?></td>
                                                <input type="hidden" value="<?= $this->filter->Rupiah($dt['pajak_id']); ?>">
                                                <td style="text-align: right !important;"><?= number_format($dt['total'],0,',','.'); ?></td>
                                            </tr>
                                        <?php
                                            $sub_total += $dt['total'];
                                            if ($dt['tax_id'] == 4) {
                                                $tax_0 = true;
                                            }
                                            if ($dt['tax_id'] == 3) {
                                                $tax_ppn += $dt['tax'];
                                            }
                                            if ($dt['tax_id'] == 5) {
                                                $tax_pph += $dt['tax'];
                                            }
                                            if ($dt['tax_id'] == 6) {
                                                $tax_ppn11 += $dt['tax'];
                                            }
                                            if ($dt['pajak_id'] == 4) {
                                                $pajak_0 = true;
                                            }
                                            if ($dt['pajak_id'] == 3) {
                                                $pajak_ppn += $dt['pajak'];
                                            }
                                            if ($dt['pajak_id'] == 5) {
                                                $pajak_pph += $dt['pajak'];
                                            }
                                            if ($dt['pajak_id'] == 6) {
                                                $pajak_ppn11 += $dt['pajak'];
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Memo</label>
                                        <textarea class="form-control" name="memo" rows="3" value="" readonly><?= $row["memo"]; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Lampiran</label>
                                        <?php
                                        $dataLampiran = $this->db->get_where('pmm_lampiran_penagihan_pembelian', array('penagihan_pembelian_id' => $row['id']))->result_array();
                                        if (!empty($dataLampiran)) {
                                            foreach ($dataLampiran as $key => $lampiran) {
                                        ?>
                                                <div><a href="<?= base_url() . 'uploads/penagihan_pembelian/' . $lampiran['lampiran']; ?>" target="_blank"><?= $lampiran['lampiran']; ?></a></div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-horizontal">
                                    <div class="row">
                                        <label class="col-sm-7 control-label">Sub Total</label>
                                        <div class="col-sm-5 text-right">
                                            <h5 id="sub-total"><?= number_format($sub_total,0,',','.'); ?></h5>
                                        </div>
                                    </div>
                                    <?php
                                    if ($tax_ppn > 0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPN 10%)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format($tax_ppn,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($tax_0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPN 0%)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format(0,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($tax_pph > 0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPh 23)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format($tax_pph,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($tax_ppn11 > 0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPN 11%)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format($tax_ppn11,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($pajak_ppn > 0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPN 10%)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format($pajak_ppn,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($pajak_0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPN 0%)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format(0,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($pajak_pph > 0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPh 23)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format($pajak_pph,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($pajak_ppn11 > 0) {
                                    ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Pajak (PPN 11%)</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total"><?= number_format($pajak_ppn11,0,',','.'); ?></h5>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    $total = $sub_total + ($tax_ppn - $tax_pph + $tax_ppn11) + ($pajak_ppn - $pajak_pph + $pajak_ppn11);
                                    $sisa_tagihan = $this->pmm_finance->getTotalPembayaranPenagihanPembelian($row['id']);
                                    ?>
                                    <div class="row">
                                        <h4 class="col-sm-7 control-label">Total</h4>
                                        <div class="col-sm-5 text-right">
                                            <h4 id="total"><?= number_format($total,0,',','.'); ?></h4>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <label class="col-sm-7 control-label">Uang Muka</label>
                                        <div class="col-sm-5 text-right">
                                            <h5 id="sub-total"><?= number_format($row['uang_muka'],0,',','.'); ?></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h4 class="col-sm-7 control-label">Sisa Tagihan</h4>
                                        <div class="col-sm-5 text-right">
                                            <h4 id="total"><?= number_format($total - $row['uang_muka']); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="text-center">
                                <div class="col-sm-12 text-right">
                                    <?php if ($row["status"] === "DRAFT") : ?>
                                        <form class="form-approval" action="<?= base_url("pembelian/approve_payment/" . $row["id"]) ?>">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Setujui</button></blink>
                                        </form>
                                        <form class="form-approval" action="<?= base_url("pembelian/reject_penawaran_pembelian/" . $row["id"]) ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Tolak</button>
                                        </form>

                                    <?php endif; ?>
                                </div>
                                <?php
                                if ($row['verifikasi_dok'] == 'BELUM') { ?>
                                    <blink><p style='color:red; font-weight:bold;'> Verifikasi Dokumen Tagihan Terlebih Dahulu !!</p></blink>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13 || $this->session->userdata('admin_group_id') == 14){
                                    ?>
                                    <a class="btn btn-danger" onclick="DeleteData('<?= site_url('pembelian/delete_penagihan_pembelian/' . $row['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>
                                    <?php
                                    }
                                }
                                ?>
                                <br />
                                <?php
                                if ($row['verifikasi_dok'] == 'SUDAH') { ?>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13 || $this->session->userdata('admin_group_id') == 14){
                                    ?>
                                        <a href="<?= site_url('pembelian/pembayaran_panagihan/' . $row['id']); ?>" class="btn btn-warning"><i class="fa fa-money"></i> Kirim Pembayaran</a>
                                        <a href="<?= site_url('pembelian/closed_pembayaran_penagihan/' . $row['id']); ?>" class="btn btn-success"><i class="fa fa-check"></i> Pembayaran Lunas</a>
                                        <!--<a href="<?= base_url('pembelian/sunting_tagihan/' . $row["id"]) ?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>-->
                                        <a class="btn btn-danger" onclick="DeleteData('<?= site_url('pembelian/delete_penagihan_pembelian/' . $row['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>
                                    <?php
                                    }
                                }
                                ?>

                                <?php
                                if ($row['verifikasi_dok'] == 'LENGKAP') { ?>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13 || $this->session->userdata('admin_group_id') == 14){
                                    ?>
                                    <a class="btn btn-danger" onclick="DeleteData('<?= site_url('pembelian/delete_penagihan_pembelian/' . $row['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>
                                    <a href="<?= site_url('pembelian/open_penagihan/' . $row['id']); ?>" class="btn btn-warning"><i class="fa fa-warning"></i> Pembayaran Belum Lunas</a>
                                    <!--<a href="<?= base_url('pembelian/sunting_tagihan/' . $row["id"]) ?>" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>-->
                                    <?php
                                    }
                                }
                                ?>

                            </div>
                            <div class="text-center">
                                <a href="<?php echo site_url('admin/pembelian#settings'); ?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Kembali</a>
                            </div>
                            <div class="container-fluid">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#menu1" aria-controls="menu2" role="tab" data-toggle="tab">Daftar Surat Jalan</a></li>
                                    <li role="presentation"><a href="#menu2" aria-controls="menu2" role="tab" data-toggle="tab">Daftar Pembayaran</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="menu1">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center table-bordered" id="table-surat-jalan" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Produk</th>
                                                        <th>No. Kendaraan</th>
                                                        <th>Supir</th>
                                                        <th>Volume</th>
                                                        <th>Satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $surat_jalan = explode(',', $row['surat_jalan']);
                                                    $this->db->select('prm.*,ppo.no_po, p.nama_produk');
                                                    $this->db->join('pmm_purchase_order ppo', 'prm.purchase_order_id = ppo.id', 'left');
                                                    $this->db->join('produk p', 'prm.material_id = p.id', 'left');
                                                    $this->db->where_in('prm.id', $surat_jalan);
                                                    $table_surat_jalan = $this->db->get('pmm_receipt_material prm')->result_array();
                                                    if (!empty($table_surat_jalan)) {
                                                        foreach ($table_surat_jalan as $sj) {
                                                    ?>
                                                            <tr>
                                                                <td><?= date('d/m/Y', strtotime($sj['date_receipt'])); ?></td>
                                                                <td><?= $sj['surat_jalan']; ?></td>
                                                                <td><?= $sj['nama_produk']; ?></td>
                                                                <td><?= $sj['no_kendaraan']; ?></td>
                                                                <td><?= $sj['driver']; ?></td>
                                                                <td><?= $this->filter->Rupiah($sj['volume']); ?></td>
                                                                <td><?= $sj['measure']; ?></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="menu2">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table-pembayaran" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Bayar Dari</th>
                                                        <th class="text-center">Jumlah</th>
                                                        <th>Status</th>
                                                        <th>Tindakan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center">

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

    <script type="text/javascript">
        var form_control = '';
    </script>
    <?php echo $this->Templates->Footer(); ?>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script type="text/javascript">
        $('.form-approval').submit(function(e) {
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

        var table = $('#table-pembayaran').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pembelian/table_pembayaran_penagihan_pembelian/' . $row["id"]); ?>',
                type: 'POST',
            },
            columns: [{
                    "data": "tanggal_pembayaran"
                },
                {
                    "data": "nomor_transaksi",
                    "render": function(data, type, row, meta) {
                        console.log(row);
                        if (type === 'display') {
                            data = '<a href="<?php echo base_url() . 'pembelian/view_pembayaran_pembelian/' ?>' + row.id + '">' + data + '</a>';
                        }

                        return data;
                    }
                },
                {
                    "data": "bayar_dari"
                },
                {
                    "data": "total_pembayaran"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                }
            ],
            "columnDefs": [
                {
                "targets": [0, 1, 2, 4, 5],
                "className": 'text-center',
                },
                {
                "targets": [3],
                "className": 'text-right',
                }
            ],
            responsive: true,
        });

        var table_surat_jalan = $('#table-surat-jalan').DataTable();

        function ApprovePayment(id) {
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
                        $.post('<?= base_url() ?>pembelian/approve_payment', {
                            id: id
                        }, function(response) {
                            console.log(response);
                            location.reload();
                        });
                    }

                }
            });
        }

        function DeleteData(href) {
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
                        window.location.href = href;
                    }

                }
            });
        }
    </script>


</body>

</html>