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
                                <h3 ><?= $row['nama'];?></h3>
                                <?php
                                $rekanan = '';
                                $karyawan = '';
                                $pelanggan = '';
                                $lain = '';
                                if($row['pelanggan'] == 1){
                                	$pelanggan = 'Pelanggan';
                                }
                                if($row['rekanan'] == 1){
                                	$rekanan = 'Rekanan';
                                }
                                if($row['karyawan'] == 1){
                                	$karyawan = 'Karyawan';
                                }
                                
                                if($row['lain'] == 1){
                                	$lain = 'Lain-lain';
                                }
                                ?>
                                <h5 ><?= $pelanggan.$rekanan.$karyawan.$lain;?></h5>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-8">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th width="30%">
                                                Email
                                            </th>        
                                            <td>
                                                <?= $row['email'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Nama Kontak
                                            </th>        
                                            <td>
                                                <?= $row['nama_kontak'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Identitas
                                            </th>        
                                            <td>
                                                <?= $row['tipe_identitas'].' - '.$row['no_identitas'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Telepon
                                            </th>        
                                            <td>
                                                <?= $row['telepon'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Nama Perusahaan
                                            </th>        
                                            <td>
                                                <?= $row['nama_perusahaan'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Fax
                                            </th>        
                                            <td>
                                                <?= $row['fax'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                NPWP
                                            </th>        
                                            <td>
                                                <?= $row['npwp'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Posisi
                                            </th>        
                                            <td>
                                                <?= $row['posisi'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Alamat Penagihan
                                            </th>        
                                            <td>
                                                <?= $row['alamat_penagihan'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Alamat
                                            </th>        
                                            <td>
                                                <?= $row['alamat'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Akun Keluar
                                            </th>        
                                            <td>
                                                <?= $this->crud_global->GetField('pmm_coa',array('id'=>$row['akun_keluar']),'coa');?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Akun Masuk
                                            </th>        
                                            <td>
                                                <?= $this->crud_global->GetField('pmm_coa',array('id'=>$row['akun_masuk']),'coa');?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('admin/kontak') ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Kembali</a>
                                    <!-- <a target="_blank" href="<?= base_url('pmm/biaya/cetakBiaya/'.$row["id"]) ?>" class="btn btn-success"><i class="fa fa-print"></i> Cetak</a> -->
                                    <?php
                                    // if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5){
                                        ?>
                                        <a class="btn btn-danger" onclick="DeleteData('<?= site_url('kontak/hapus/'.$row['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                        <a  href="<?= base_url('kontak/form/'.$row['id']) ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>
                                        <?php
                                    // }
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
