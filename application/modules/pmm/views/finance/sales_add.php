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
                            <a href="<?php echo site_url('admin/productions');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Penjualan</a></li>
                        <li><a>Penjualan Baru</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header"> 
                            <div class="text-right">
                                <h3 class="pull-left">Pesanan Penjualan</h3>
                                <a href="<?php echo site_url('admin/productions');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Pelanggan</label>
                                    <select class="form-control">
                                        <option value="">.. Pilih Pelanggan ..</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Tgl Transaksi</label>
                                    <input type="text" class="form-control" name="" />
                                </div>
                                <div class="col-sm-3">
                                    <label>Tgl Jatuh Tempo</label>
                                    <input type="text" class="form-control" name="" />
                                </div>
                                <div class="col-sm-3">
                                    <label>No Transaksi</label>
                                    <input type="text" class="form-control" name="" />
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Alamat Pelanggan</label>
                                    <input type="text" class="form-control" name="" />
                                </div>
                                <div class="col-sm-3">
                                    <label>Tgl Kontrak</label>
                                    <input type="text" class="form-control" name="" />
                                </div>
                                <div class="col-sm-3">
                                    <label>Nomor Kontrak</label>
                                    <input type="text" class="form-control" name="" />
                                </div>
                                <div class="col-sm-3">
                                    <label>Jenis Pekerjaan</label>
                                    <input type="text" class="form-control" name="" />
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















</body>
</html>
