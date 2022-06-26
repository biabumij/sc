<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
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
                        <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                        <li><a>Produksi jadi</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">
                                <?= $row['no_produksi_jadi'];?> 
                            </h3>

                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-6">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tr>
                                            <th width="30%">Produk</th>
                                            <th width="2%">:</th>
                                            <td width="68%"> <?= $this->crud_global->GetField('produk',array('id'=>$row["produk"]),'nama_produk') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>:</th>
                                            <td> <?= date('d F Y',strtotime($row["tanggal"])) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Quantity</th>
                                            <th>:</th>
                                            <td> <?= $this->filter->Rupiah($row['qty']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Satuan</th>
                                            <th>:</th>
                                            <td><?= $this->crud_global->GetField('pmm_measures',array('id'=>$row["satuan"]),'measure_name') ?></td>
                                        </tr>
                                        <tr>
                                            <th>Berat Isi</th>
                                            <th>:</th>
                                            <td> <?= $this->filter->Rupiah($row['berat_isi']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Convert Berat Isi</th>
                                            <th>:</th>
                                            <td> <?= $this->filter->Rupiah($row['convert_berat_isi']);?></td>
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
                                                $lampiran = $this->db->get_where('lampiran_produksi_jadi',array('produk_jadi'))->result_array();
                                                if(!empty($lampiran)){
                                                    foreach ($lampiran as $key => $lam) {
                                                        ?>
                                                        <a href=""><?= $lam['lampiran'];?></a><br />
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="<?= base_url('admin/produksi') ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Kembali</a>
                                <a  href="<?= base_url('produksi/form_produksi_jadi/'.$row["id"]) ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>
                                <?php
                                // if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5){
                                    ?>
                                    <a class="btn btn-danger" onclick="DeleteData('<?= site_url('produksi/delete_produksi_jadi/'.$row['id']);?>')"><i class="fa fa-trash"></i> Hapus</a>
                                    <!-- <a  class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a> -->
                                    <?php
                                // }
                                ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
</div>

	<?php echo $this->Templates->Footer();?>

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
