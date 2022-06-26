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
                            <a href="<?php echo site_url('admin/productions');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Produk</a></li>
                        <li><a>Produk Detail</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header"> 
                            <div class="">
                                <h3 ><?= $row['nama_produk'];?></h3>
                                <h5 ><?= $row['kode_produk'];?></h5>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-8">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="30%">
                                                Satuan
                                            </th>        
                                            <td>
                                                <?= $this->crud_global->GetField('pmm_measures',array('id'=>$row['satuan']),'measure_name');?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Deskripsi
                                            </th>        
                                            <td>
                                                <?= $row['deskripsi'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Tipe Produk
                                            </th>        
                                            <td>
                                                <?= $row['tipe_produk'];?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-4">
                                    <?php

                                    if(!empty($row['jual'])){
                                        $coa_jual = $this->db->get_where('pmm_coa',array('id'=>$row['akun_jual']))->row_array();
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Jual</h3>
                                            </div>
                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <p class="list-group-item-text">
                                                        Harga Jual
                                                    </p>
                                                    <h4 class="list-group-item-heading">
                                                        Rp. <?= $this->filter->Rupiah($row['harga_jual']);?>
                                                    </h4>
                                                </div>
                                                <div class="list-group-item">
                                                    <p class="list-group-item-text">
                                                        Akun Jual
                                                    </p>
                                                    <h4 class="list-group-item-heading">
                                                        <?= '('.$coa_jual['coa_number'].') '.$coa_jual['coa'];?>
                                                    </h4>
                                                </div>
                                                <div class="list-group-item">
                                                    <p class="list-group-item-text">
                                                        Pajak Jual
                                                    </p>
                                                    <h4 class="list-group-item-heading">
                                                        <?= $this->crud_global->GetField('pmm_taxs',array('id'=>$row['pajak_jual']),'tax_name');?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    if(!empty($row['beli'])){
                                        
                                        $coa_beli = $this->db->get_where('pmm_coa',array('id'=>$row['akun_beli']))->row_array();
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Beli</h3>
                                            </div>
                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <p class="list-group-item-text">
                                                        Harga Beli
                                                    </p>
                                                    <h4 class="list-group-item-heading">
                                                        Rp. <?= $this->filter->Rupiah($row['harga_beli']);?>
                                                    </h4>
                                                </div>
                                                <div class="list-group-item">
                                                    <p class="list-group-item-text">
                                                        Akun Beli
                                                    </p>
                                                    <h4 class="list-group-item-heading">
                                                        <?= '('.$coa_beli['coa_number'].') '.$coa_beli['coa'];?>
                                                    </h4>
                                                </div>
                                                <div class="list-group-item">
                                                    <p class="list-group-item-text">
                                                        Pajak Beli
                                                    </p>
                                                    <h4 class="list-group-item-heading">
                                                        <?= $this->crud_global->GetField('pmm_taxs',array('id'=>$row['pajak_beli']),'tax_name');?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    
                                </div>
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('admin/produk') ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Kembali</a>
                                    <?php
                                    if($this->session->userdata('admin_group_id') == 1){
                                        ?>
                                        <a class="btn btn-danger" onclick="DeleteData('<?= site_url('produk/hapus/'.$row['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                        <a  href="<?= base_url('produk/buat_baru/'.$row['id']) ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>
                                        <?php
                                    }
                                    ?>
                                    
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

    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

     <script type="text/javascript">
        
        $('.form-select2').select2();

        $('input.numberformat').number( true, 2,',','.' );
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
