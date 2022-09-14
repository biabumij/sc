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
                                <a href="<?php echo site_url('admin/kas_&_bank');?>"> Kas & Bank</a></li>
                            <li><a>Terima Uang</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Terima Uang</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tr>
                                                <th width="30%">Terima Dari</th>
                                                <th width="2%">:</th>
                                                <td width="68%"><?= $this->crud_global->GetField('pmm_coa',array('id'=>$detail["terima_dari"]),'coa')?></td>
                                            </tr>
                                            <tr>
                                                <th>Setor Ke</th>
                                                <th>:</th>
                                                <td><?= $this->crud_global->GetField('pmm_coa',array('id'=>$detail["setor_ke"]),'coa')?></td>
                                            </tr>
                                            <tr>
                                                <th>Nomor Transaksi</th>
                                                <th>:</th>
                                                <td><?= $detail["nomor_transaksi"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Transaksi</th>
                                                <th>:</th>
                                                <td> <?= date('d F Y',strtotime($detail["tanggal_transaksi"])) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah</th>
                                                <th>:</th>
                                                <td> Rp. <?= $this->filter->Rupiah($detail['jumlah']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Memo</th>
                                                <th>:</th>
                                                <td> <?= $detail['memo'];?></td>
                                            </tr>
                                            <tr>
                                                <th>Lampiran</th>
                                                <th>:</th>
                                                <td> 
                                                    <?php
                                                    if(!empty($dataLampiran)){
                                                        foreach ($dataLampiran as $key => $lampiran) {
                                                            ?>
                                                            <a href=""><?= $lampiran['lampiran'];?></a><br />
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 text-right">
                                        <a href="<?= base_url('admin/kas_&_bank') ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Kembali</a>
                                        <a target="_blank" href="<?= base_url('pmm/finance/cetakTerimaCoa/'.$detail["id"]) ?>" class="btn btn-success"><i class="fa fa-print"></i> Cetak</a>
                                        <?php
                                        if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13 || $this->session->userdata('admin_group_id') == 14 || $this->session->userdata('admin_group_id') == 10){
                                            ?>
                                            <a class="btn btn-danger" onclick="DeleteData('<?= site_url('pmm/finance/deleteTerimaCoa/'.$detail['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                            <!-- <a  class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a> -->
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