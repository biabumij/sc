<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
        }
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
                            <li>
                                <a href="<?php echo site_url('admin/pembelian');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Pembelian</a></li>
                            <li><a>Penawaran Pembelian Baru</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="text-right">
                                    <h3 class="pull-left">Penawaran Pembelian</h3>
                                    <a href="<?php echo site_url('admin/pembelian');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                            </div>
                            <div class="panel-content">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Supplier</label>
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
                                        <div class="col-sm-3">
                                            <label>Jenis Pembelian</label>
                                            <input type="text" class="form-control" value="<?= $row['jenis_pembelian'];?>" readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label >Alamat Supplier</label>
                                            <textarea class="form-control" name="alamat_supplier" id="alamat_supplier" required="" readonly=""><?= $row['client_address'];?></textarea>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Nomor Penawaran</label>
                                            <input type="text" class="form-control" value="<?= $row['nomor_penawaran'];?>" readonly>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Syarat Penawaran</label>
                                            <input type="text" class="form-control" value="<?= $row['syarat_penawaran'];?>" readonly>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="22%">Material</th>
                                                    <th width="12%">Qty</th>
                                                    <th width="10%">Satuan</th>
                                                    <th width="15%">Harga Satuan</th>
                                                    <th width="10%">Pajak</th>
                                                    <th width="20%">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sub_total = 0;
                                                $tax_pph = 0;
                                                $tax_ppn = 0;
                                                $tax_0 = false;
                                                $total = 0;
                                                $details = $this->db->get_where('pmm_penawaran_pembelian_detail',array('penawaran_pembelian_id'=>$row['id']))->result_array();
                                                ?>
                                                <?php foreach($details as $key => $dt) { ?>
                                                <?php 
                                                    $material = $this->crud_global->GetField('pmm_materials',array('id'=>$dt['material_id']),'material_name');
                                                    $measure = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure']),'measure_name');
                                                ?>
                                                <tr>
                                                    <td><?= $key+1 ?>.</td>
                                                    <td>
                                                        <?= $material;?>
                                                    </td>
                                                   <td><?= $dt['qty'];?></td>
                                                   <td><?= $measure;?></td>
                                                   <td><?= $this->filter->Rupiah($dt['price']);?></td>
                                                   <td><?= $this->filter->Rupiah($dt['tax']);?></td>
                                                   <td style="text-align: right !important;"><?= $this->filter->Rupiah($dt['total']);?></td>
                                                </tr>
                                                <?php
                                                    $sub_total += $dt['total'];
                                                    if($dt['tax_id'] == 4){
                                                        $tax_0 = true;
                                                    }
                                                    if($dt['tax_id'] == 3){
                                                        $tax_ppn += $dt['tax'];
                                                    }
                                                    if($dt['tax_id'] == 5){
                                                        $tax_pph += $dt['tax'];
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
                                                <textarea class="form-control" name="memo" rows="3" value="" readonly ><?= $row["memo"]; ?></textarea>
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
                                        <div class="col-sm-8 form-horizontal">
                                            <div class="row">
                                                <label class="col-sm-7 control-label">Sub Total</label>
                                                <div class="col-sm-5 text-right">
                                                    <h5 id="sub-total" ><?= $this->filter->Rupiah($sub_total);?></h5>
                                                </div>
                                            </div>
                                            <?php
                                            if($tax_ppn > 0){
                                                ?>
                                                <div class="row">
                                                    <label class="col-sm-7 control-label">Pajak Ppn</label>
                                                    <div class="col-sm-5 text-right">
                                                        <h5 id="sub-total" ><?= $this->filter->Rupiah($tax_ppn);?></h5>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if($tax_0){
                                                ?>
                                                <div class="row">
                                                    <label class="col-sm-7 control-label">Pajak 0%</label>
                                                    <div class="col-sm-5 text-right">
                                                        <h5 id="sub-total" ><?= $this->filter->Rupiah(0);?></h5>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if($tax_pph > 0){
                                                ?>
                                                <div class="row">
                                                    <label class="col-sm-7 control-label">Pajak Pph</label>
                                                    <div class="col-sm-5 text-right">
                                                        <h5 id="sub-total" ><?= $this->filter->Rupiah($tax_pph);?></h5>
                                                    </div>
                                                </div>
                                                <?php
                                            }

                                            $total = $sub_total - $tax_ppn + $tax_pph;
                                            ?>
                                            
                                            <div class="row">
                                                <h4 class="col-sm-7 control-label">Total</h4>
                                                <div class="col-sm-5 text-right">
                                                    <h4 id="total" ><?= $this->filter->Rupiah($row['total']);?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <?php if($row["status"] === "DRAFT") : ?>
                                                <form class="form-approval" action="<?= base_url("pmm/pembelian/approve_penawaran_pembelian/".$row["id"]) ?>">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> APPROVE</button>        
                                            </form>
                                            <form class="form-approval" action="<?= base_url("pmm/pembelian/reject_penawaran_pembelian/".$row["id"]) ?>">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> REJECT</button>        
                                            </form>
                                            
                                            <?php endif; ?>

                                                <form class="form-approval" action="<?= base_url("pmm/pembelian/closed_penawaran_pembelian/".$row["id"]) ?>">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-close"></i> CLOSED</button>        
                                                </form>
                                            
                                        </div>
                                    </div>
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
