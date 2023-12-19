<!doctype html>
<html lang="en" class="fixed">
    
<?php include 'lib.php'; ?>

<head>
    <?php echo $this->Templates->Header();?>
    
    <style type="text/css">
        .form-check {
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
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/penjualan');?>"> Penjualan</a></li>
                            <li><a href="<?php echo site_url('admin/penjualan');?>"> Penawaran Penjualan</a></li>
                            <li><a href="">Detail Penawaran Penjualan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                    <div class="">
                                        <h3 class="">Detail Penawaran Penjualan <?php echo $this->pmm_model->GetStatus2($penawaran['status']);?></h3>
                                    </div>
                            </div>
                            <div class="panel-content">
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="15%" align="left">Rekanan</th>
                                        <th width="85%" align="left"><label class="label label-default" style="font-size:14px;"><?= $penawaran["nama"] ?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Alamat Rekanan</th>
                                        <th><textarea class="form-control" rows="5" readonly=""><?= $penawaran["client_address"] ?></textarea></th>
                                    </tr>
                                </table>
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="15%" align="left">Nomor Penawaran</th>
                                        <th width="85%" align="left"><label class="label label-info" style="font-size:14px;"><?= $penawaran["nomor"] ?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Perihal</th>
                                        <th><?= $penawaran["perihal"]; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Penawaran</th>
                                        <th><?= date('d/m/Y',strtotime($penawaran["tanggal"]));?></th>
                                    </tr>
                                    <tr>
                                        <th>Syarat Pembayaran</th>
                                        <th><?= $penawaran['syarat_pembayaran'];?> Hari</th>
                                    </tr>
                                    <tr>
                                        <th>Persyaratan Harga</th>
                                        <th><?= $penawaran["persyaratan_harga"];?></th>
                                    </tr>
                                    <tr>
                                        <th>Lampiran</th>
                                        <th>
                                            <?php foreach($lampiran as $l) : ?>
                                            <a href="<?= base_url("uploads/penawaran_penjualan/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                            <?php endforeach; ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Oleh</th>
                                        <th><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$penawaran['created_by']),'admin_name');?></th>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Tanggal</th>
                                        <th><?= date('d/m/Y H:i:s',strtotime($penawaran['created_on']));?></th>
                                    </tr>
                                </table>

                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="20%">Produk</th>
                                            <th class="text-center" width="15%">Volume</th>
                                            <th class="text-center" width="10%">Satuan</th>
                                            <th class="text-center" width="20%">Harga Satuan</th>
                                            <th class="text-center" width="20%">Nilai</th>
                                            <th class="text-center" width="10%">Pajak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $subtotal = 0;
                                        $tax_pph = 0;
                                        $tax_ppn = 0;
                                        $tax_0 = false;
                                        $total = 0;

                                        ?>
                                        <?php foreach($details as $no => $d) : ?>
                                            <?php
                                            $tax = $this->crud_global->GetField('pmm_taxs',array('id'=>$d['tax_id']),'tax_name');
                                            $measure = $this->crud_global->GetField('pmm_measures',array('id'=>$d['measure']),'measure_name');
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $no + 1;?></td>
                                                <td class="text-left"><?= $d["nama_produk"] ?></td>
                                                <td class="text-center"><?= $d["qty"]; ?></td>
                                                <td class="text-center"><?= $measure; ?></td>
                                                <td class="text-right"><?= number_format($d['price'],0,',','.'); ?></td>
                                                <td class="text-right"><?= number_format($d['total'],0,',','.'); ?></td>
                                                <td class="text-center"><?= $tax; ?></td>
                                            </tr>
                                            <?php
                                            $subtotal += $d['total'];
                                            if($d['tax_id'] == 4){
                                                $tax_0 = true;
                                            }
                                            if($d['tax_id'] == 3){
                                                $tax_ppn += $d['tax'];
                                            }
                                            if($d['tax_id'] == 5){
                                                $tax_pph += $d['tax'];
                                            }
                                            ?>
                                            <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                                        
                                <div class="text-center">
                                    <br /><br /><br />
                                    <?php if($penawaran["status"] === "DRAFT") : ?>
                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                            ?>
                                                <a href="<?= site_url('penjualan/approvalPenawaran/' . $penawaran['id']); ?>" class="btn btn-success" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-check"></i> Setujui</a>
                                                <a href="<?= site_url('penjualan/rejectedPenawaran/' . $penawaran['id']); ?>" class="btn btn-danger" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Tolak</a>
                                            <?php
                                        }
                                        ?>
                                    <?php endif; ?>

                                    <?php if($penawaran["status"] === "OPEN") : ?>
                                        <a href="<?= base_url("penjualan/cetak_penawaran_penjualan/".$penawaran["id"]) ?>" target="_blank" class="btn btn-default" style="margin-top:10px; width:150px; font-weight:bold; border-radius:10px;"><i class="fa fa-print"></i> Print</a>
                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                            ?>
                                            <a href="<?= base_url("penjualan/closed_penawaran_penjualan/".$penawaran["id"]) ?>" class="btn btn-danger" style="margin-top:10px; width:150px; font-weight:bold; border-radius:10px;"><i class="fa fa-briefcase"></i> Closed</a>			
                                            <?php
                                        }
                                        ?>
                                        <form class="form-check" action="<?= base_url("penjualan/rejectedPenawaran/".$penawaran["id"]) ?>">
                                            <button type="submit" class="btn btn-warning" style="width:150px; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Reject</button>        
                                        </form>
                                    <?php endif; ?>

                                    <?php if($penawaran["status"] === "CLOSED") : ?>
                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                        ?>
                                        <a href="<?= base_url("penjualan/open_penawaran_penjualan/".$penawaran["id"]) ?>" class="btn btn-success" style="margin-top:10px; width:150px; font-weight:bold; border-radius:10px;"><i class="fa fa-briefcase"></i> Open</a>	
                                        <?php
                                        }
                                        ?>

                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1){
                                        ?>
                                        <a class="btn btn-danger" style="margin-top:10px; width:150px; font-weight:bold; border-radius:10px;" onclick="DeleteData('<?= site_url('penjualan/hapusPenawaranPenjualan/' . $penawaran['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>	
                                        <?php
                                        }
                                        ?>
                                    <?php endif; ?>

                                    <?php if($penawaran["status"] === "REJECT") : ?>
                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1){
                                            ?>
                                            <a class="btn btn-danger" style="margin-top:10px; width:150px; font-weight:bold; border-radius:10px;" onclick="DeleteData('<?= site_url('penjualan/hapusPenawaranPenjualan/' . $penawaran['id']); ?>')"><i class="fa fa-close"></i> Hapus</a>		
                                            <?php
                                        }
                                        ?>
                                    <?php endif; ?>

                                    <br /><br /><br />
                                    <a href="<?php echo site_url('admin/penjualan');?>" class="btn btn-info" style="margin-top:10px; width:150px; font-weight:bold; border-radius:10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
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

	<?php echo $this->Templates->Footer();?>
	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script type="text/javascript">

        $('.form-check').click(function(e){
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
                callback: function (result) {
                    if(result){
                        currentForm.submit();
                    }

                }
            });

        });

        function DeleteData(href)
        {
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
                callback: function (result) {
                    if(result){
                        window.location.href = href;
                    }
                    
                }
            });
        }

    </script>

</body>
</html>