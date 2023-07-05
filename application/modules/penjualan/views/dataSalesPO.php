<!doctype html>
<html lang="en" class="fixed">

<?php include 'lib.php'; ?>

<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
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
                        <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a href="<?php echo site_url('admin/penjualan');?>"> Penjualan</a></li>
                        <li><a href="<?php echo site_url('admin/penjualan');?>"> Sales Order</a></li>
                        <li><a href="">Detail Sales Order</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Sales Order <?php echo $this->pmm_model->GetStatus2($sales_po['status']);?></h3>
                                </div>
                        </div>
                        <div class="panel-content">
                            <table class="table table-striped table-bordered" width="100%">
                                <tr>
                                    <th width="20%" align="left">Rekanan</th>
                                    <th width="80%" align="left"><label class="label label-default" style="font-size:14px;"><?= $client["nama"] ?></label></th>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <th><textarea class="form-control" rows="5" readonly=""><?= $sales_po["client_address"] ?></textarea></th>
                                </tr>
                                <tr>
                                    <th>No. Penawaran</th>
                                    <th><a target="_blank" href="<?= base_url("penjualan/detailPenawaran/".$sales_po_detail['penawaran_id']) ?>"><?php echo $this->crud_global->GetField('pmm_penawaran_penjualan',array('id'=>$sales_po_detail['penawaran_id']),'nomor');?></a></th>   
                                </tr>
                            </table>
                            <table class="table table-striped table-bordered" width="100%">
                                <tr>
                                    <th width="20%" align="left">No. Sales Order</th>
                                    <th width="80%" align="left"><label class="label label-info" style="font-size:14px;"><?= $sales_po["contract_number"]; ?></label></th>
                                </tr>
                                <tr>
                                    <th>Jenis Pekerjaan</th>
                                    <th><?= $sales_po["jobs_type"]; ?></th>
                                </tr>
                                <tr>
                                    <th>Tanggal Kontrak</th>
                                    <th><?= $sales_po["contract_number"]; ?></th>
                                </tr>
                                <tr>
                                    <th>Memo</th>
                                    <th class="text-left" colspan="6"><?= $sales_po["memo"] ?></th>
                                </tr>
                                <tr>
                                    <th>Lampiran</th>
                                    <th>
                                        <?php foreach($lampiran as $l) : ?>                                    
                                        <a href="<?= base_url("uploads/sales_po/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                        <?php endforeach; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Dibuat Oleh</th>
                                    <th><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sales_po['created_by']),'admin_name');?></th>
                                </tr>
                                <tr>
                                    <th>Dibuat Tanggal</th>
                                    <th><?= date('d/m/Y H:i:s',strtotime($sales_po['created_on']));?></th>
                                </tr>
                            </table>    
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50px">No</th>
                                        <th class="text-center" >Produk</th>
                                        <th class="text-center" >Volume</th>
                                        <th class="text-center" width="100px">Satuan</th>
                                        <th class="text-center" >Harga Satuan</th>
                                        <th class="text-center" >Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subtotal = 0;
                                    $tax_pph = 0;
                                    $tax_ppn = 0;
                                    $tax_ppn11 = 0;
                                    $tax_0 = false;
                                    $total = 0;

                                    ?>
                                    <?php foreach($details as $no => $d) : ?>
                                        <?php
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no + 1;?></td>
                                            <td class="text-left"><?= $d["nama_produk"] ?></td>
                                            <td class="text-center"><?= $d["qty"]; ?></td>
                                            <td class="text-center"><?= $d["measure"]; ?></td>
                                            <td class="text-right"><?= number_format($d['price'],0,',','.'); ?></td>
                                            <td class="text-right"><?= number_format($d['total'],0,',','.'); ?></td>
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
                                        if($d['tax_id'] == 6){
                                            $tax_ppn11 += $d['tax'];
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-right">Sub Total</th>
                                        <th  class="text-right"><?= number_format($subtotal,0,',','.'); ?></th>
                                    </tr>
                                    <?php
                                    if($tax_ppn > 0){
                                        ?>
                                        <tr>
                                            <th colspan="5" class="text-right">Pajak (PPN 10%)</th>
                                            <th  class="text-right"><?= number_format($tax_ppn,0,',','.'); ?></th>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($tax_0){
                                        ?>
                                        <tr>
                                            <th colspan="5" class="text-right">Pajak (PPN 0%)</th>
                                            <th  class="text-right"><?= number_format(0,0,',','.'); ?></th>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($tax_pph > 0){
                                        ?>
                                        <tr>
                                            <th colspan="5" class="text-right">Pajak (PPh 23)</th>
                                            <th  class="text-right"><?= number_format($tax_pph,0,',','.'); ?></th>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($tax_ppn11 > 0){
                                        ?>
                                        <tr>
                                            <th colspan="5" class="text-right">Pajak (PPN 11%)</th>
                                            <th  class="text-right"><?= number_format($tax_ppn11,0,',','.'); ?></th>
                                        </tr>
                                        <?php
                                    }
                                    $total = $subtotal + $tax_ppn - $tax_pph + $tax_ppn11;
                                    ?>
                                    
                                    <tr>
                                        <th colspan="5" class="text-right">TOTAL</th>
                                        <th  class="text-right"><?= number_format($total,0,',','.'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                            
                            <div class="text-right">
                                <form>
                                    <a href="<?php echo site_url('admin/penjualan#profile'); ?>" class="btn btn-info" style="margin-top: 10px; width:200px; font-weight:bold;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                </form>
                                <?php if($sales_po["status"] === "DRAFT") : ?>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11){
                                    ?>
                                        <form class="form-approval" action="<?= base_url("penjualan/approvalSalesPO/".$sales_po["id"]) ?>">
                                            <button type="submit" class="btn btn-success" style="width:200px; font-weight:bold;"><i class="fa fa-check"></i> Setujui</button>        
                                        </form>
                                        <form class="form-approval" action="<?= base_url("penjualan/rejectedSalesPO/".$sales_po["id"]) ?>">
                                            <button type="submit" class="btn btn-danger" style="width:200px; font-weight:bold;"><i class="fa fa-close"></i> Tolak</button>        
                                        </form>
                                    <?php
                                    }
                                    ?>
                                <?php endif; ?>
                            
                                <?php if($sales_po["status"] === "OPEN") : ?>
                                <a href="<?= base_url("penjualan/cetak_sales_order/".$sales_po["id"]) ?>" target="_blank" class="btn btn-default" style="width:200px; font-weight:bold;"><i class="fa fa-print"></i> Cetak PDF</a>
                                <a href="<?= base_url("pmm/productions/add?po_id=".$sales_po["id"]) ?>"  class="btn btn-success" style="width:200px; font-weight:bold;"><i class="fa fa-truck"></i> Kirim Produk</a>
								<a href="<?= base_url("pmm/productions/add_retur?po_id=".$sales_po["id"]) ?>"  class="btn btn-primary" style="width:200px; font-weight:bold;"><i class="fa fa-truck"></i> Retur</a>
                                
                                <?php
                                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11){
                                    ?>
                                    <br />
                                    <form class="form-approval" action="<?= base_url("penjualan/closed_sales_order/".$sales_po["id"]) ?>">
                                        <button type="submit" class="btn btn-danger" style="width:200px; font-weight:bold;"><i class="fa fa-briefcase"></i> Closed</button>        
                                    </form>					
                                    <?php
                                    }
                                    ?>
                                    <?php endif;
                                ?>
                           
                                <?php if($sales_po["status"] === "CLOSED") : ?>
                                <a href="<?= base_url("penjualan/cetak_sales_order/".$sales_po["id"]) ?>" target="_blank" class="btn btn-default" style="width:200px; font-weight:bold;"><i class="fa fa-print"></i> Cetak PDF</a>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1){
                                    ?>
                                    <a class="btn btn-danger" style="width:200px; font-weight:bold;" onclick="DeleteData('<?= site_url('penjualan/hapus_sales_po/'.$sales_po['id']);?>')"><i class="fa fa-close"></i> Hapus</a>            
                                    <?php
                                    }
                                    ?>
                                    <?php endif;
                                ?>
                    
                                <?php if($sales_po["status"] === "REJECT") : ?>
                                <?php
                                    if($this->session->userdata('admin_group_id') == 1){
                                    ?>
                                    <a class="btn btn-danger" style="width:200px; font-weight:bold;" onclick="DeleteData('<?= site_url('penjualan/hapus_sales_po/'.$sales_po['id']);?>')"><i class="fa fa-close"></i> Hapus</a>			
                                    <?php
                                    }
                                    ?>
                                    <?php endif;
                                ?>
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
