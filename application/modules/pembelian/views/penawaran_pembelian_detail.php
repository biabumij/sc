<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .form-approval{
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="wrap">
        
        <?php echo $this->Templates->PageHeader();?>

        <div class="page-body">
            <?php echo $this->Templates->LeftBar();?>
            <div class="content" style="padding:0;">
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
                                    <h3 class="">Penawaran Pembelian <?php echo $this->pmm_model->GetStatus2($row['status']);?></h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Rekanan</label>
                                            <input type="text" class="form-control" value="<?= $row['supplier'];?>" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Tanggal Penawaran</label>
                                            <input type="text" class="form-control" value="<?= date('d/m/Y',strtotime($row['tanggal_penawaran']));?>" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Berlaku Hingga</label>
                                            <input type="text" class="form-control" value="<?= date('d/m/Y',strtotime($row['berlaku_hingga']));?>" readonly>
                                        </div>
                                        <div class="col-sm-10">
                                            <label >Alamat Rekanan</label>
                                            <textarea class="form-control" rows="4" name="alamat_supplier" id="alamat_supplier" required="" readonly=""><?= $row['client_address'];?></textarea>
                                        </div>
                                        <div class="col-sm-10">
                                            <label>Nomor Penawaran</label>
                                            <input type="text" class="form-control" value="<?= $row['nomor_penawaran'];?>" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Syarat Pembayaran</label>
                                            <input type="text" class="form-control" value="<?= $row['syarat_pembayaran'];?> Hari" readonly>
                                        </div>
                                        <div class="col-sm-7">
                                            <label>Jenis Pembelian</label>
                                            <input type="text" class="form-control" value="<?= $row['jenis_pembelian'];?>" readonly>
                                        </div>
                                    </div>
                                    <br />
                                    
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" width="5%">No</th>
                                                    <th class="text-center" width="22%">Material</th>
                                                    <th class="text-center" width="12%">Volume</th>
                                                    <th class="text-center" width="10%">Satuan</th>
                                                    <th class="text-center" width="15%">Harga Satuan</th>
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
                                    $tax_ppn11 = 0;
                                    $total = 0;
									$details = $this->db->get_where('pmm_penawaran_pembelian_detail',array('penawaran_pembelian_id'=>$row['id']))->result_array();
									?>
									<?php foreach($details as $key => $dt) { ?>
									<?php 
										$material = $this->crud_global->GetField('produk',array('id'=>$dt['material_id']),'nama_produk');
										$measure = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure']),'measure_name');
										$tax = $this->crud_global->GetField('pmm_taxs',array('id'=>$dt['tax_id']),'tax_name');
									?>
                                        <tr>
                                            <td class="text-center"><?= $key + 1;?></td>
                                            <td class="text-left"><?= $material ?></td>
                                            <td class="text-center"><?= $dt["qty"]; ?></td>
                                            <td class="text-center"><?= $measure; ?></td>
                                            <td class="text-right"><?= number_format($dt['price'],0,',','.'); ?></td>
                                            <td class="text-right"><?= number_format($dt['total'],0,',','.'); ?></td>
											<td class="text-center"><?= $tax ?></td>
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
										
                                        }
                                        ?>
                                </tbody>
								</table>    
                                    
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Memo :</label>
                                                <td><?= $row["memo"]; ?></td>
                                            </div>
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <?php
                                                $dataLampiran = $this->db->get_where('pmm_lampiran_penawaran_pembelian',array('penawaran_pembelian_id'=>$row['id']))->result_array();
                                                if(!empty($dataLampiran)){
                                                    foreach ($dataLampiran as $key => $lampiran) {
                                                        ?>
                                                        <div><a href="<?= base_url().'uploads/penawaran_pembelian/'.$lampiran['lampiran'];?>" target="_blank"><?= $lampiran['lampiran'];?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">

                                            <a href="<?php echo site_url('admin/pembelian');?>" class="btn btn-info" style="margin-top: 10px;"><i class="fa fa-mail-reply"></i> Kembali</a>
                                            <?php if($row["status"] === "DRAFT") : ?>
                                                <?php
                                                if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                                ?>
                                                    <form class="form-approval" action="<?= base_url("pembelian/approve_penawaran_pembelian/".$row["id"]) ?>">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Setujui</button>        
                                                </form>
                                                <form class="form-approval" action="<?= base_url("pembelian/reject_penawaran_pembelian/".$row["id"]) ?>">
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Tolak</button>        
                                                </form>
                                                <?php
                                                }
                                                ?>
                                            <?php endif; ?>

                                            <?php if($row["status"] === "OPEN") : ?>
                                                <?php
                                                if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                                ?>
                                                <form class="form-approval" action="<?= base_url("pembelian/closed_penawaran_pembelian/".$row["id"]) ?>">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Closed</button>      
                                                </form>		
                                                <?php
                                                }
                                                ?>
                                            <?php endif; ?>
                                            <?php if($row["status"] === "CLOSED") : ?>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1){
                                                ?>
                                                <form class="form-approval" action="<?= base_url("pembelian/hapus_penawaran_pembelian/".$row["id"]) ?>">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>        
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

    <script type="text/javascript">
        $('.form-approval').submit(function(e){
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
    </script>

</body>
</html>
