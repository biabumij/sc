<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
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
                                <a href="<?php echo site_url('admin/pembelian');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Pesanan Pembelian</a></li>
                            <li><a>Detail Pesanan Pembelian</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <h3 >Detail Pesanan Pembelian</h3>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tr style='background-color:#cccccc; font-weight:bold;'>
                                                <th class="text-center" width="40%">Rekanan</th>
                                                <td class="text-center" width="40%">No. Pesanan Pembelian</td>
                                                <td class="text-center" width="20%">Terima</td>
                                            </tr>
                                            <?php
                                            $total_volume = 0;
                                            foreach ($row as $dt) {
                                            ?> 
                                            <tr>
                                                <th><?php echo $this->crud_global->GetField('penerima',array('id'=>$dt['supplier_id']),'nama');?></th>
                                                <td class="text-left"><a target="_blank" href="<?= base_url("pmm/purchase_order/manage/".$dt['id']) ?>"><?php echo $this->crud_global->GetField('pmm_purchase_order',array('id'=>$dt['id']),'no_po');?></a></td>
                                                <td class="text-right"><?php echo number_format($dt['volume'],2,',','.');?></td>
                                            </tr>
                                            <?php
                                            $total_volume += $dt['volume'];
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-right" colspan="2"><b>TOTAL</b></td>
                                                <td class="text-right"><b><?php echo number_format($total_volume,2,',','.');?></b></td>
                                            </tr>
                                        </table>


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
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    
</body>
</html>
