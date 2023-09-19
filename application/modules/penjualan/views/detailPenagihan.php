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
    </style>
</head>

<body>
    <div class="wrap">
        <?php echo $this->Templates->PageHeader();?>
        <div class="page-body">
            <?php echo $this->Templates->LeftBar();?>
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/penjualan'); ?>"> Penjualan</a></li>
                            <li><a href="<?php echo site_url('admin/penjualan'); ?>"> Tagihan Penjualan</a></li>
                            <li><a href="">Detail Tagihan Penjualan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Tagihan Penjualan <?php echo $this->pmm_model->GetStatus2($penagihan['status']);?></h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="20%" align="left">Pelanggan</th>
                                        <th width="80%" align="left"><label class="label label-default" style="font-size:14px;"><?= $penagihan["nama_pelanggan"] ?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Alamat Pelanggan</th>
                                        <th><textarea class="form-control" name="alamat_pelanggan" rows="5" readonly=""><?= $penagihan['alamat_pelanggan']; ?></textarea></th>
                                    </tr>
                                    <tr>
                                        <th>Nomor Sales Order</th>
                                        <th><a target="_blank" href="<?= base_url("penjualan/dataSalesPO/".$penagihan['sales_po_id']) ?>"><?php echo $this->crud_global->GetField('pmm_sales_po',array('id'=>$penagihan['sales_po_id']),'contract_number');?></a></th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Sales Order</th>
                                        <th><?= date('d/m/Y', strtotime($penagihan["tanggal_kontrak"])) ?></th>
                                    </tr>
                                </table>
                                <br />
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="20%" align="left">Nomor Invoice</th>
                                        <th width="80%" align="left"><label class="label label-info" style="font-size:14px;"><?= $penagihan["nomor_invoice"]; ?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Invoice</th>
                                        <th><?= date('d/m/Y', strtotime($penagihan['tanggal_invoice'])); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Syarat Pembayaran</th>
                                        <th><?= $penagihan['syarat_pembayaran']; ?> Hari</th>
                                    </tr>
                                    <tr>
                                        <th>Memo</th>
                                        <th><?= $penagihan["memo"]; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Lampiran</th>
                                        <th>
                                            <?php
                                            if (!empty($dataLampiran)) {
                                                foreach ($dataLampiran as $key => $lampiran) {
                                            ?>
                                                    <div><a href="<?= base_url() . 'uploads/penagihan/' . $lampiran['lampiran']; ?>" target="_blank"><?= $lampiran['lampiran']; ?></a></div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Oleh</th>
                                        <th><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$penagihan['created_by']),'admin_name');?></th>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Tanggal</th>
                                        <th><?= date('d/m/Y H:i:s',strtotime($penagihan['created_on']));?></th>
                                    </tr>
                                </table>
                                <br /><br />
                                <div class="table-responsive">
                                    <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Produk</th>
                                                <th width="7%">Volume</th>
                                                <th>Satuan</th>
                                                <th>Harga Satuan</th>
                                                <th width="10%">Pajak</th>
                                                <th width="20%">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sub_total = 0;
                                            $tax_pph = 0;
                                            $tax_ppn = 0;
                                            $tax_ppn11 = 0;
                                            $tax_0 = false;
                                            $total = 0;
                                            ?>
                                            <?php foreach ($cekHarga as $key => $row) { ?>
                                                <?php
                                                $product = $this->crud_global->GetField('produk', array('id' => $row['product_id']), 'nama_produk');
												$taxs = $this->crud_global->GetField('pmm_taxs', array('id' => $row['tax_id']), 'Tax_name');
                                                ?>
                                                <tr>
                                                    <td><?= $key + 1 ?>.</td>
                                                    <td style="text-align: left !important;"><?= $product; ?></td>
                                                    <td><?= $row['qty']; ?></td>
                                                    <td><?= $row['measure']; ?></td>
                                                    <td style="text-align: right !important;"><?= number_format($row['price'],0,',','.'); ?></td>
                                                    <td><?= $taxs; ?></td>
                                                    <td style="text-align: right !important;"><?= number_format($row['total'],0,',','.'); ?></td>
                                                </tr>
                                                <?php
													$sub_total += ($row['price'] * $row['qty']);
													$tax_id = $row['tax_id'];
													
													if($row['tax_id'] == 4){
														$tax_0 = true;
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
												}
                                                ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <label></label>
                                            
                                        </div>
                                        <div class="row">
                                            <label></label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-8 form-horizontal">
                                        <div class="row">
                                            <label class="col-sm-7 control-label">SUB TOTAL</label>
                                                <div class="col-sm-5 text-right">
                                                    <label><?= number_format($sub_total,0,',','.'); ?></label>													
                                                        <input type="hidden" name="total_1" value="<?= $sub_total;?>">
                                                </div>
                                        </div>
                                        <?php
                                            if($tax_ppn > 0){
                                                ?>
                                                <div class="row">                                                   
                                                    <label class="col-sm-7 control-label">PAJAK (PPN 10%)</label>
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
                                                <div class="row">                                                   
                                                    <label class="col-sm-7 control-label">PAJAK (PPN 0%)</label>
                                                        <div class="col-sm-5 text-right">
                                                            <label><?= number_format(0,0,',','.'); ?></label>
                                                                <input type="hidden" id="tax_1" name="tax_1" value="<?= $tax_0;?>">
                                                        </div>
                                                </div>                                                  
                                                <?php
                                            }
                                        ?>
                                        <?php
                                            if($tax_pph > 0){
                                                ?>
                                                <div class="row">                                                   
                                                    <label class="col-sm-7 control-label">PAJAK (PPh 23)</label>
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
                                                <div class="row">                                                   
                                                    <label class="col-sm-7 control-label">PAJAK (PPN 11%)</label>
                                                        <div class="col-sm-5 text-right">															
                                                            <label><?= number_format($tax_ppn11,0,',','.'); ?></label>
                                                                <input type="hidden" id="tax_1" name="tax_1" value="<?= $tax_ppn11;?>">
                                                        </div>
                                                </div>
                                            
                                        <?php
                                        }
                                        $total = $sub_total + $tax_ppn - $tax_pph + $tax_ppn11;
                                        $sisa_tagihan = $this->pmm_finance->getTotalPembayaranPenagihanPenjualan($penagihan['id']);
                                        ?>

                                        <div class="row">
                                            <label class="col-sm-7 control-label">TOTAL</label>
                                            <div class="col-sm-5 text-right">
                                                <label id="total"><?= number_format($total,0,',','.'); ?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">PEMBAYARAN</label>
                                            <div class="col-sm-5 text-right">
                                                <label id="sub-total"><?= number_format($penagihan['pembayaran'],0,',','.'); ?></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">SISA TAGIHAN</label>
                                            <div class="col-sm-5 text-right">
                                                <label id="total"><?= number_format($total - $sisa_tagihan - $penagihan['pembayaran']); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br /><br />
                                <div class="text-center">
                                    <div class="col-sm-12 text-center">
                                        <?php if ($penagihan["status"] === "DRAFT") : ?>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 15){
                                                ?>
                                                <form class="form-approval" action="<?= base_url("penjualan/approvePenagihan/" . $penagihan["id"]) ?>">
                                                    <button type="submit" class="btn btn-success btn-sm" style="width:200px; font-weight:bold; border-radius:10px;"><i class="fa fa-check"></i> Setujui</button>
                                                </form>
                                                <form class="form-approval" action="<?= base_url("penjualan/rejectPenagihan/" . $penagihan["id"]) ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" style="width:200px; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Tolak</button>
                                                </form>
                                            <?php
                                            }
                                            ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <?php if ($penagihan["status"] === "OPEN") : ?>
                                        <a href="<?= base_url("penjualan/cetak_penagihan_penjualan/".$penagihan["id"]) ?>" target="_blank" class="btn btn-default" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-print"></i> Print</a>
                                        <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13 || $this->session->userdata('admin_group_id') == 14 || $this->session->userdata('admin_group_id') == 15){
                                            ?>
                                            <a class="btn btn-warning" style="width:15%; font-weight:bold; border-radius:10px;" href="<?= base_url("penjualan/halaman_pembayaran/" . $penagihan["id"]) ?>"><i class="fa fa-money"></i> Terima Pembayaran</a>
                                            <a class="btn btn-success" style="width:15%; font-weight:bold; border-radius:10px;" href="<?= site_url('penjualan/closed_pembayaran_penagihan/' . $penagihan['id']); ?>"><i class="fa fa-check"></i> Pembayaran Lunas</a>
                                            <?php
                                            }
                                            ?>
                                            
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 15){
                                            ?>
                                            <a class="btn btn-default" style="width:15%; font-weight:bold; border-radius:10px;" href="<?= base_url('penjualan/sunting_tagihan/' . $penagihan["id"]) ?>"><i class="fa fa-edit"></i> Edit</a>	
                                            <a class="btn btn-danger" style="width:15%; font-weight:bold; border-radius:10px;" onclick="DeleteData('<?= site_url('penjualan/delete_penagihan_penjualan/' . $penagihan['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>
                                            <?php
                                            }
                                            ?>
                                            <?php endif;
                                        ?>
                                </div>
                                <div class="text-center">
                                    <?php if ($penagihan["status"] === "CLOSED") : ?>
                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13 || $this->session->userdata('admin_group_id') == 14 || $this->session->userdata('admin_group_id') == 15){
                                        ?>
                                        <?php
                                        ?>
                                        <a href="<?= site_url('penjualan/open_penagihan/' . $penagihan['id']); ?>" class="btn btn-success" style="width:20%; font-weight:bold; border-radius:10px;"><i class="fa fa-folder-open-o"></i> Pembayaran Belum Lunas</a>
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 15){
                                        ?>
                                         <a class="btn btn-danger"  style="width:15%; font-weight:bold; border-radius:10px;" onclick="DeleteData('<?= site_url('penjualan/delete_penagihan_penjualan/' . $penagihan['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>
                                        <?php
                                        }
                                        ?>
                                        <?php endif; ?>
                                </div>
                                <div class="text-center">
                                    <?php if ($penagihan["status"] === "REJECT") : ?>
                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 15){
                                        ?>
                                        <a class="btn btn-danger" style="width:15%; font-weight:bold; border-radius:10px;" onclick="DeleteData('<?= site_url('penjualan/delete_penagihan_penjualan/' . $penagihan['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>	
                                        <?php
                                        }
                                        ?>
                                        <?php endif; ?>
                                </div>
                                <br /><br /><br />
                                <div class="text-center">
                                    <a href="<?php echo site_url('admin/penjualan#settings'); ?>" class="btn btn-info" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                </div>
                                <br />
                                <br />
                            </div>
                            <div class="container-fluid">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#menu1" aria-controls="menu2" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px;">Daftar Surat Jalan</a></li>
                                    <li role="presentation"><a href="#menu2" aria-controls="menu2" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px;">Daftar Penerimaan</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="menu1">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center table-bordered" id="table-surat-jalan" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Produk</th>
                                                        <th>No. Kendaraan</th>
                                                        <th>Supir</th>
                                                        <th class="text-right">Volume</th>
                                                        <th>Satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $surat_jalan = explode(',', $penagihan['surat_jalan']);
                                                    $this->db->select('pp.*,p.nama_produk as product, c.nama as client_name, pp.measure as measure');
                                                    $this->db->join('produk p', 'pp.product_id = p.id', 'left');
                                                    $this->db->join('penerima c', 'pp.client_id = c.id', 'left');
                                                    $this->db->where_in('pp.id', $surat_jalan);
                                                    $table_surat_jalan = $this->db->get('pmm_productions pp')->result_array();
                                                    if (!empty($table_surat_jalan)) {
                                                        foreach ($table_surat_jalan as $sj) {
                                                    ?>
                                                            <tr>
                                                                <td><?= date('d/m/Y', strtotime($sj['date_production'])); ?></td>
                                                                <td><?= $sj['no_production']; ?></td>
                                                                <td><?= $sj['product']; ?></td>
                                                                <td><?= $sj['nopol_truck']; ?></td>
                                                                <td><?= $sj['driver']; ?></td>
                                                                <td style="text-align: right !important;"><?= number_format($sj['volume'],2,',','.'); ?></td>
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
                                            <table class="table table-striped table-hover table-bordered" id="table-pembayaran" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Setor Ke</th>
                                                        <th class="text-right">Jumlah</th>
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

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
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
    </script>

    <script type="text/javascript">
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
        });

        var table = $('#table-pembayaran').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('penjualan/table_pembayaran/' . $penagihan["id"]); ?>',
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
                            data = '<a href="<?php echo base_url() . 'penjualan/view_pembayaran/' ?>' + row.id + '">' + data + '</a>';
                        }

                        return data;
                    }
                },
                {
                    "data": "setor_ke"
                },
                {
                    "data": "total"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                },
            ],
            "columnDefs": [
                { "width": "5%", "targets": [0, 5], "className": 'text-center'},
                { "targets": 3, "className": 'text-right'},
            ],
            responsive: true,
        });

        var table_surat_jalan = $('#table-surat-jalan').DataTable( {"bAutoWidth": false,
            "pageLength": 5,
        });

        function ApprovePayment(href) {
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

        function DeleteData(href) {
            bootbox.confirm({
                message: "Apakah anda yakin untuk menghapus tagihan ini ? <br /> * Jika ada data pembayaran, maka akan terhapus.<br /> * Status surat jalan akan kembali 'UNCREATED'.",
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