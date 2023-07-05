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
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Pembelian</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Penawaran Pembelian</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Detail Penawaran Pembelian</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Penawaran Pembelian <?php echo $this->pmm_model->GetStatus2($row['status']);?></h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="15%" align="left">Rekanan</th>
                                        <th width="85%" align="left"><label class="label label-default" style="font-size:14px;"><?php echo $this->crud_global->GetField('penerima',array('id'=>$row['supplier_id']),'nama');?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Alamat Rekanan</th>
                                        <th><textarea class="form-control" name="alamat_supplier" id="alamat_supplier" rows="5" readonly=""><?= $row['client_address'];?></textarea></th>
                                    </tr>
                                </table>
                                <table class="table table-striped table-bordered" width="100%">
                                    <tr>
                                        <th width="15%" align="left">Nomor Penawaran</th>
                                        <th width="85%" align="left"><label class="label label-info" style="font-size:14px;"><?= $row['nomor_penawaran'];?></label></th>
                                    </tr>
                                    <tr>
                                        <th>Perihal</th>
                                        <th><?= $row['jenis_pembelian'];?></th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Penawaran</th>
                                        <th><?= date('d/m/Y',strtotime($row['tanggal_penawaran']));?></th>
                                    </tr>
                                    <tr>
                                        <th>Berlaku Hingga</th>
                                        <th><?= date('d/m/Y',strtotime($row['berlaku_hingga']));?></th>
                                    </tr>
                                    <tr>
                                        <th>Syarat Pembayaran</th>
                                        <th><?= $row['syarat_pembayaran'];?> Hari</th>
                                    </tr>
                                    <tr>
                                        <th>Metode Pembayaran</th>
                                        <th><?= $row['metode_pembayaran'];?></th>
                                    </tr>
                                    <tr>
                                        <th>Memo</th>
                                        <th><?= $row["memo"]; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Lampiran</th>
                                        <th><?php
                                                $dataLampiran = $this->db->get_where('pmm_lampiran_penawaran_pembelian',array('penawaran_pembelian_id'=>$row['id']))->result_array();
                                                if(!empty($dataLampiran)){
                                                    foreach ($dataLampiran as $key => $lampiran) {
                                                        ?>
                                                        <div><a href="<?= base_url().'uploads/penawaran_pembelian/'.$lampiran['lampiran'];?>" target="_blank"><?= $lampiran['lampiran'];?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Oleh</th>
                                        <th><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');?></th>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Tanggal</th>
                                        <th><?= date('d/m/Y H:i:s',strtotime($row['created_on']));?></th>
                                    </tr>
                                </table>
                                <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="25%">Produk</th>
                                            <th class="text-center" width="10%">Volume</th>
                                            <th class="text-center" width="10%">Satuan</th>
                                            <th class="text-center" width="10%">Harga Satuan</th>
                                            <th class="text-center" width="20%">Nilai</th>
                                            <th class="text-center" width="10%">Pajak</th>
                                            <th class="text-center" width="10%">Pajak (2)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $subtotal = 0;
                                    $tax_pph = 0;
                                    $tax_ppn = 0;
                                    $tax_0 = false;
                                    $tax_ppn11 = 0;
                                    $pajak_pph = 0;
                                    $pajak_ppn = 0;
                                    $pajak_0 = false;
                                    $pajak_ppn11 = 0;
                                    $total = 0;
									$details = $this->db->get_where('pmm_penawaran_pembelian_detail',array('penawaran_pembelian_id'=>$row['id']))->result_array();
									?>
									<?php foreach($details as $key => $dt) { ?>
									<?php 
										$produk = $this->crud_global->GetField('produk',array('id'=>$dt['material_id']),'nama_produk');
										$measure = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure']),'measure_name');
										$tax = $this->crud_global->GetField('pmm_taxs',array('id'=>$dt['tax_id']),'tax_name');
                                        $pajak = $this->crud_global->GetField('pmm_taxs',array('id'=>$dt['pajak_id']),'tax_name');
									?>
                                        <tr>
                                            <td class="text-center"><?= $key + 1;?></td>
                                            <td class="text-left"><?= $produk ?></td>
                                            <td class="text-center"><?= $dt["qty"]; ?></td>
                                            <td class="text-center"><?= $measure; ?></td>
                                            <td class="text-right"><?= number_format($dt['price'],0,',','.'); ?></td>
                                            <td class="text-right"><?= number_format($dt['total'],0,',','.'); ?></td>
											<td class="text-center"><?= $tax ?></td>
                                            <td class="text-center"><?= $pajak ?></td>
                                        </tr>

                                        <?php
                                        $subtotal += $dt['total'];
                                        if($dt['tax_id'] == 4){
                                            $tax_0 = true;
                                        }
                                        if($dt['tax_id'] == 3){
                                            $tax_ppn += $dt['tax'];
                                        }
                                        if($dt['tax_id'] == 5){
                                            $tax_pph += $dt['tax'];
                                        }
                                        if($dt['tax_id'] == 6){
                                            $tax_ppn11 += $dt['tax'];
                                        }
                                        if($dt['pajak_id'] == 4){
                                            $pajak_0 = true;
                                        }
                                        if($dt['pajak_id'] == 3){
                                            $pajak_ppn += $dt['tax'];
                                        }
                                        if($dt['pajak_id'] == 5){
                                            $pajak_pph += $dt['tax'];
                                        }
                                        if($dt['pajak_id'] == 6){
                                            $pajak_ppn11 += $dt['tax'];
                                        }
                                        }
                                        ?>
                                </tbody>
								</table>    
                                    
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <a href="<?php echo site_url('admin/pembelian');?>" class="btn btn-info" style="margin-top: 10px; width:200px; font-weight:bold;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                        <?php if($row["status"] === "DRAFT") : ?>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11){
                                            ?>
                                                <form class="form-approval" action="<?= base_url("pembelian/approve_penawaran_pembelian/".$row["id"]) ?>">
                                                    <button type="submit" class="btn btn-success" style="width:200px; font-weight:bold;"><i class="fa fa-check"></i> Setujui</button>        
                                                </form>
                                                <form class="form-approval" action="<?= base_url("pembelian/reject_penawaran_pembelian/".$row["id"]) ?>">
                                                    <button type="submit" class="btn btn-danger" style="width:200px; font-weight:bold;"><i class="fa fa-close"></i> Tolak</button>        
                                                </form>
                                            <?php
                                            }
                                            ?>
                                        <?php endif; ?>

                                        <?php if($row["status"] === "OPEN") : ?>
                                        <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11){
                                            ?>
                                                <form class="form-approval" action="<?= base_url("pembelian/closed_penawaran_pembelian/".$row["id"]) ?>">
                                                <button type="submit" class="btn btn-danger" style="width:200px; font-weight:bold;"><i class="fa fa-briefcase"></i> Closed</button>      
                                                </form>		
                                            <?php
                                            }
                                            ?>
                                        <?php endif; ?>

                                        <?php if($row["status"] === "CLOSED") : ?>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11){
                                            ?>
                                                <form class="form-approval" action="<?= base_url("pembelian/open_penawaran_pembelian/".$row["id"]) ?>">
                                                    <button type="submit" class="btn btn-success" style="width:200px; font-weight:bold;"><i class="fa fa-folder-open-o"></i> Open</button>        
                                                </form>	
                                            <?php
                                            }
                                            ?>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1){
                                            ?>
                                                <form class="form-approval" action="<?= base_url("pembelian/hapus_penawaran_pembelian/".$row["id"]) ?>">
                                                    <button type="submit" class="btn btn-danger" style="width:200px; font-weight:bold;"><i class="fa fa-trash"></i> Hapus</button>
                                                </form>
                                            <?php
                                            }
                                            ?>
                                        <?php endif; ?>

                                        <?php if($row["status"] === "REJECT") : ?>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1){
                                            ?>
                                                <form class="form-approval" action="<?= base_url("pembelian/hapus_penawaran_pembelian/".$row["id"]) ?>">
                                                <button type="submit" class="btn btn-danger" style="width:200px; font-weight:bold;"><i class="fa fa-trash"></i> Hapus</button>        
                                                </form>	
                                            <?php
                                            }
                                            ?>
                                        <?php endif; ?>
                                        
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