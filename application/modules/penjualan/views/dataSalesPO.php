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
                        <li><a>Detail Sales Order</a></li>
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
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="200px">Pelanggan </th>
                                    <td>: <?= $client["nama"] ?></td>
                                </tr>
                                <tr>
                                    <th >Alamat </th>
                                    <td>: <?= $sales_po["client_address"] ?></td>
                                </tr>
                                <tr>
                                    <th >Tanggal Kontrak</th>
                                    <td>: <?= convertDateDBtoIndo($sales_po["contract_date"]); ?></td>
                                </tr>
                                <tr>
                                    <th >Nomor Kontrak </th>
                                    <td>: <?= $sales_po["contract_number"]; ?></td>
                                </tr>
                                <tr>
                                    <th >Jenis Pekerjaan </th>
                                    <td>: <?= $sales_po["jobs_type"]; ?></td>
                                </tr>
                                <tr>
                                    <th >Total </th>
                                    <td>: Rp.<?= $this->filter->Rupiah($sales_po["total"]); ?></td>
                                </tr>
                                <tr>
                                    <th >Status</th>
                                    <td>: <?= $sales_po["status"]; ?></td>
                                </tr>
                                <tr>
                                    <th width="100px">Lampiran</th>
                                    <td>:  
                                        <?php foreach($lampiran as $l) : ?>                                    
                                        <a href="<?= base_url("uploads/sales_po/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                        <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <th>Memo</th>
                                    <td>: <?= $sales_po["memo"] ?></td>
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

                                <?php if($sales_po["status"] === "DRAFT") : ?>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                    ?>
                                        <form class="form-approval" action="<?= base_url("penjualan/approvalSalesPO/".$sales_po["id"]) ?>">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Setujui</button>        
                                        </form>
                                        <form class="form-approval" action="<?= base_url("penjualan/rejectedSalesPO/".$sales_po["id"]) ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Tolak</button>        
                                        </form>
                                    <?php
                                    }
                                    ?>
                                <?php endif; ?>
                            
                                <?php if($sales_po["status"] === "OPEN") : ?>
                                <a href="<?= base_url("penjualan/cetak_sales_order/".$sales_po["id"]) ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Cetak PDF</a>
                                <a href="<?= base_url("pmm/productions/add?po_id=".$sales_po["id"]) ?>"  class="btn btn-success"><i class="fa fa-truck"></i> Surat Jalan Pengiriman Penjualan</a>
								<a href="<?= base_url("pmm/productions/add_retur?po_id=".$sales_po["id"]) ?>"  class="btn btn-primary"><i class="fa fa-truck"></i> Retur</a>
                                <br />
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                    ?>
                                      
                                        <form class="form-approval" action="<?= base_url("penjualan/closed_sales_order/".$sales_po["id"]) ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Closed</button>        
                                        </form>					
                                    <?php
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>  
                            <br />
                            <div class="text-right">
                                <?php if($sales_po["status"] === "CLOSED") : ?>
                                <a href="<?= base_url("penjualan/cetak_sales_order/".$sales_po["id"]) ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Cetak PDF</a>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1){
                                    ?>
                                        <a class="btn btn-danger" onclick="DeleteData('<?= site_url('penjualan/hapus_sales_po/'.$sales_po['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                                        
                                    <?php
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>
                            <br />
                            <div class="text-right">
                                <?php if($sales_po["status"] === "REJECT") : ?>
                                <a href="<?php echo site_url('admin/penjualan#profile'); ?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Kembali</a>
                                <a href="<?= base_url("penjualan/cetak_sales_order/".$sales_po["id"]) ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Cetak PDF</a>
                                <?php
                                if($this->session->userdata('admin_group_id') == 1){
                                    ?>
                                    <a class="btn btn-danger" onclick="DeleteData('<?= site_url('penjualan/hapus_sales_po/'.$sales_po['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                    				
                                    <?php
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="text-right">
                                <a href="<?php echo site_url('admin/penjualan#profile'); ?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Kembali</a>
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
