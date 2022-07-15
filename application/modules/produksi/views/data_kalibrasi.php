<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .form-approval {
            display: inline-block;
        }
		
		.mytable thead th {
		  background-color: #D3D3D3;
		  /*border: solid 1px #000000;*/
		  color: #000000;
		  text-align: center;
		  vertical-align: middle;
		  padding : 10px;
		}
		
		.mytable tbody td {
		  padding: 5px;
		}
		
		.mytable tfoot tr {
          background-color: #D3D3D3;
          font-weight: bold;
          padding: 5px;
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
                        <li><a href="<?php echo site_url('admin/produksi'); ?>"> Produksi</a></li>
                        <li><a>Detail Kalibrasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Kalibrasi</h3>
                                </div>
                        </div>
                        <div class="panel-content">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="25%">Judul / Data Kalibrasi </th>
                                    <td width="75%">: <?= $kalibrasi["jobs_type"]; ?></td>
                                </tr>
                                <tr>
                                    <th >Tanggal Kalibrasi </th>
                                     <td>: <?= date('d F Y',strtotime($kalibrasi["date_kalibrasi"])); ?></td>								
                                </tr>
                                <tr>
                                    <th >Nomor Kalibrasi</th>
                                    <td>: <?= $kalibrasi["no_kalibrasi"]; ?></td>
                                </tr>
                                <tr>
                                    <th width="100px">Lampiran</th>
                                    <td>:  
                                        <?php foreach($lampiran as $l) : ?>                                    
                                        <a href="<?= base_url("uploads/kalibrasi/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                        <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: <?= $kalibrasi["memo"] ?></td>
                                </tr>
                            </table>
                            
                            <table class="mytable table-bordered table-hover table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50px">No</th>
                                        <th class="text-center" >Produksi / Aggregat</th>
                                        <th class="text-center" width="100px">Satuan</th>
                                        <th class="text-center" >Presentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    $total = $kalibrasi["presentase_a"] + $kalibrasi["presentase_b"] + $kalibrasi["presentase_c"] + $kalibrasi["presentase_d"] + $kalibrasi["presentase_e"];
                                    ?>
                                        <tr>
                                            <td class="text-center" width="5%">1.</td>
                                            <td class="text-left" width="50%"><?= $kalibrasi["produk_a"] = $this->crud_global->GetField('produk',array('id'=>$kalibrasi['produk_a']),'nama_produk'); ?></td>
                                            <td class="text-center" width="20%"><?= $kalibrasi["measure_a"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$kalibrasi['measure_a']),'measure_name'); ?></td>
											<td class="text-center" width="25%"><?= $kalibrasi["presentase_a"]; ?> %</td>
                                        </tr>
										<tr>
                                            <td class="text-center">2.</td>
                                            <td class="text-left"><?= $kalibrasi["produk_b"] = $this->crud_global->GetField('produk',array('id'=>$kalibrasi['produk_b']),'nama_produk'); ?></td>
                                            <td class="text-center"><?= $kalibrasi["measure_b"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$kalibrasi['measure_b']),'measure_name'); ?></td>
											<td class="text-center"><?= $kalibrasi["presentase_b"]; ?> %</td>
                                        </tr>
										<tr>
                                            <td class="text-center">3.</td>
                                            <td class="text-left"><?= $kalibrasi["produk_c"] = $this->crud_global->GetField('produk',array('id'=>$kalibrasi['produk_c']),'nama_produk'); ?></td>
                                            <td class="text-center"><?= $kalibrasi["measure_c"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$kalibrasi['measure_c']),'measure_name'); ?></td>
											<td class="text-center"><?= $kalibrasi["presentase_c"]; ?> %</td>
                                        </tr>
										<tr>
                                            <td class="text-center">4.</td>
                                            <td class="text-left"><?= $kalibrasi["produk_d"] = $this->crud_global->GetField('produk',array('id'=>$kalibrasi['produk_d']),'nama_produk'); ?></td>
                                            <td class="text-center"><?= $kalibrasi["measure_d"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$kalibrasi['measure_d']),'measure_name'); ?></td>
											<td class="text-center"><?= $kalibrasi["presentase_d"]; ?> %</td>
                                        </tr>
										<tr>
                                            <td class="text-center">5.</td>
                                            <td class="text-left"><?= $kalibrasi["produk_e"] = $this->crud_global->GetField('produk',array('id'=>$kalibrasi['produk_e']),'nama_produk'); ?></td>
                                            <td class="text-center"><?= $kalibrasi["measure_e"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$kalibrasi['measure_e']),'measure_name'); ?></td>
											<td class="text-center"><?= $kalibrasi["presentase_e"]; ?> %</td>
                                        </tr>
                                </tbody>
                                <tfoot class="mytable">
                                    <tr>
                                        <td class="mytable text-center" colspan="3">TOTAL</td>
                                        <td class="text-center"><?php echo number_format($total,2,',','.');?> %</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <br />
							<br />
                            
                            <div class="text-right">
                                <?php if($kalibrasi["status"] === "DRAFT") : ?>
                                    <form class="form-approval" action="<?= base_url("penjualan/approvalSalesPO/".$kalibrasi["id"]) ?>">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Approved</button>        
                                    </form>
                                    <form class="form-approval" action="<?= base_url("penjualan/rejectedSalesPO/".$kalibrasi["id"]) ?>">
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Rejected</button>        
                                    </form>
                                
                                <?php endif; ?>
                                <?php if($kalibrasi["status"] === "PUBLISH") : ?>
                                <a href="<?= base_url("admin/produksi/") ?>" target="" class="btn btn-info"><i class="fa fa-mail-reply"></i> Kembali</a>
                                <?php endif; ?>
								<a class="btn btn-danger" onclick="DeleteData('<?= site_url('produksi/hapus_kalibrasi/'.$kalibrasi['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
								<a href="<?= base_url("produksi/cetak_kalibrasi/".$kalibrasi["id"]) ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Cetak PDF</a>
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
