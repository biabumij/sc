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
                                <a href="<?php echo site_url('admin/biaya');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Biaya</a></li>
                            <li><a>Detail Biaya</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <h3 >Detail Biaya</h3>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table class="table table-bordered table-striped table-condensed">
                                            <tr>
                                                <th width="30%">Dibayar Kepada</th>
                                                <th width="2%">:</th>
                                                <td width="68%"> <?= $row["penerima"] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nomor Transaksi</th>
                                                <th>:</th>
                                                <td> <?= $row['nomor_transaksi'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Transaksi</th>
                                                <th>:</th>
                                                <td> <?= date('d F Y',strtotime($row["tanggal_transaksi"])) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Akun Penarikan</th>
                                                <th>:</th>
                                                <td> <?= $this->crud_global->GetField('pmm_coa',array('id'=>$row["bayar_dari"]),'coa'); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Memo</th>
                                                <th>:</th>
                                                <td> <?= $row['memo'];?></td>
                                            </tr>
                                            <tr>
                                                <th>Lampiran</th>
                                                <th>:</th>
                                                <td> 
                                                    <?php
                                                    $lampiran = $this->db->get_where('pmm_lampiran_biaya',array('biaya_id'=>$row['id']))->result_array();
                                                    if(!empty($lampiran)){
                                                        foreach ($lampiran as $key => $lam) {
                                                            ?>
                                                            <a href="<?= base_url().'uploads/biaya/'.$lam['lampiran'];?>" target="_blank"><?= $lam['lampiran'];?></a><br />
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <table id="table-product" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr >
                                            <th width="15%" class="text-center">Kode Akun</th>
                                            <th width="30%" class="text-center">Nama Akun</th>
                                            <th width="30%" class="text-center">Deskripsi</th>
                                            <th width="25%"  class="text-center">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        if(!empty($detail)){
                                            foreach ($detail as $key => $dt) {
                                                ?>
                                                <tr>
                                                    <td><?= $dt['kode_akun'];?></td>
                                                    <td><?= $dt['akun'];?></td>
                                                    <td><?= $dt['deskripsi'];?></td>
                                                    <td class=" text-right">Rp. <?= $this->filter->Rupiah($dt['jumlah']);?></td>    
                                                </tr>
                                                <?php
                                                $total += $dt['jumlah'];
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">TOTAL</th>
                                            <th class="text-right">Rp. <?= $this->filter->Rupiah($total);?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <a href="<?= base_url('admin/biaya') ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Kembali</a>
										<?php
											if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 15){
                                        ?>
										<?php if($row["status"] === "UNPAID") : ?>
											<a href="<?= base_url("pmm/biaya/approvalBiaya/".$row["id"]) ?>" class="btn btn-success"><i class="fa fa-check"></i> Approve</a>
											<a href="<?= base_url("pmm/biaya/rejectedBiaya/".$row["id"]) ?>"class="btn btn-primary"><i class="fa fa-close"></i> Reject</a>
										<?php endif; ?>
										<?php
                                        }
                                        ?>
										
                                        <?php if($row["status"] === "PAID") : ?>
                                            <a target="_blank" href="<?= base_url('pmm/biaya/cetakBiaya/'.$row["id"]) ?>" class="btn btn-success"><i class="fa fa-print"></i> Cetak</a>
                                            <?php
                                            if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13|| $this->session->userdata('admin_group_id') == 14 || $this->session->userdata('admin_group_id') == 19){
                                            ?>
                                            <a class="btn btn-danger" onclick="DeleteData('<?= site_url('pmm/biaya/delete/'.$row['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                            <a  href="<?= base_url('pmm/biaya/form/'.$row['id']) ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
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
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script type="text/javascript">
        
        $('.numberformat').number( true, 2,',','.' );
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
