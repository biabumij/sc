<!doctype html>
<html lang="en" class="fixed">
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
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a>Detail Data PO</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="text-right">
                                    <h3 class="pull-left">Detail PO Penjualan</h3>
                                    <a href="<?php echo site_url('admin/penjualan');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                        </div>
                        <div class="panel-content">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="200px">Nama Client </th>
                                    <td>: <?= $client["client_name"] ?></td>
                                </tr>
                                <tr>
                                    <th >Alamat </th>
                                    <td>: <?= $sales_po["client_address"] ?></td>
                                </tr>
                                <tr>
                                    <th >Tanggal Kontrak</th>
                                    <td>: <?= date("d-m-Y",strtotime($sales_po["contract_date"])); ?></td>
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
                                    <th >Memo </th>
                                    <td>: <?= $sales_po["memo"]; ?></td>
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
                                    <?php foreach($lampiran as $l) : ?>
                                    <td>:  <a href="<?= base_url("uploads/sales_po/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                    <?php endforeach; ?>
                                </tr>
                            </table>
                            
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th  class="text-center" width="50px">No</th>
                                        <th class="text-center" >Product</th>
                                        <th class="text-center" >Volume</th>
                                        <th  class="text-center" width="100px">Measure</th>
                                        <th class="text-center" >Price</th>
                                        <th class="text-center" >Tax</th>
                                        <th class="text-center" >Total</th>
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
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no + 1;?></td>
                                            <td><?= $d["product"] ?></td>
                                            <td><?= $d["qty"]; ?></td>
                                            <td><?= $d["measure"]; ?></td>
                                            <td class="text-right"><?= $this->filter->Rupiah($d['price']) ?></td>
                                            <td class="text-right"><?= $this->filter->Rupiah($d['tax']) ?></td>
                                            <td class="text-right"><?= $this->filter->Rupiah($d['total']) ?></td>
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
                                    <tr>
                                        <th colspan="6" class="text-right">SUBTOTAL</th>
                                        <th  class="text-right"><?= $this->filter->Rupiah($subtotal);?></th>
                                    </tr>
                                    <?php
                                    if($tax_ppn > 0){
                                        ?>
                                        <tr>
                                            <th colspan="6" class="text-right">Pajak Ppn</th>
                                            <th  class="text-right"><?= $this->filter->Rupiah($tax_ppn);?></th>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($tax_0){
                                        ?>
                                        <tr>
                                            <th colspan="6" class="text-right">Pajak 0%</th>
                                            <th  class="text-right"><?= $this->filter->Rupiah(0);?></th>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if($tax_pph > 0){
                                        ?>
                                        <tr>
                                            <th colspan="6" class="text-right">Pajak Pph</th>
                                            <th  class="text-right"><?= $this->filter->Rupiah($tax_pph);?></th>
                                        </tr>
                                        <?php
                                    }

                                    $total = $subtotal + $tax_ppn - $tax_pph;
                                    ?>
                                    
                                    <tr>
                                        <th colspan="6" class="text-right">TOTAL</th>
                                        <th  class="text-right"><?= $this->filter->Rupiah($total);?></th>
                                    </tr>
                                </tfoot>
                            </table>
                            
                            
                            <br><br>
                            <?php if($sales_po["status"] === "DRAFT") : ?>
                                <form class="form-approval" action="<?= base_url("pmm/finance/approvalSalesPO/".$sales_po["id"]) ?>">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Approved</button>        
                                </form>
                                <form class="form-approval" action="<?= base_url("pmm/finance/rejectedSalesPO/".$sales_po["id"]) ?>">
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Rejected</button>        
                                </form>
                            
                            <?php endif; ?>
                            <?php if($sales_po["status"] === "OPEN") : ?>
                            <a href="<?= base_url("pmm/reports/cetakSalesPo/".$sales_po["id"]) ?>" target="_blank" class="btn btn-info">Cetak PDF</a>
                            <?php endif; ?>
                            
                            
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
    </script>
    

</body>
</html>
