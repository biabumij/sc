<!doctype html>
<html lang="en" class="fixed">

<?php include 'lib.php'; ?>

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
		
		.mytable tfoot th {
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
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a href="<?= base_url("admin/rap/") ?>">Komposisi</a></li>
                        <li><a>Detail Komposisi</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Komposisi</h3>
                                </div>
                        </div>
                        <div class="panel-content">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="200px">Mutu Beton / Slump</th>
                                    <td>: <?= $agregat["mutu_beton"] = $this->crud_global->GetField('produk',array('id'=>$agregat['mutu_beton']),'nama_produk');?></td>
                                </tr>
                                <tr>
                                    <th width="200px">Volume</th>
                                    <td>: <?= $agregat["volume"]; ?></td>
                                </tr>
                                <tr>
                                    <th width="200px">Satuan</th>
                                    <td>: <?= $agregat["measure"] = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure']),'measure_name');?></td>
                                </tr>
                                <tr>
                                    <th width="200px">Judul</th>
                                    <td>: <?= $agregat["jobs_type"]; ?></td>
                                </tr>
								<tr>
                                    <th >Tanggal</th>
                                     <td>: <?= convertDateDBtoIndo($agregat["date_agregat"]); ?></td>								
                                </tr>
                                <tr>
                                    <th >Lamanya Tes</th>
                                    <td>: <?= $agregat["tes"]; ?> Hari</td>
                                </tr>
                                <tr>
                                    <th width="100px">Lampiran</th>
                                    <td>:  
                                        <?php foreach($lampiran as $l) : ?>                                    
                                        <a href="<?= base_url("uploads/agregat/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                        <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: <?= $agregat["memo"] ?></td>
                                </tr>
                            </table>
                            
                            <table class="mytable table-bordered table-hover table-striped" width="100%">
                                <thead>
                                    <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="20%">Uraian</th>
                                    <th class="text-center" width="15%">Satuan</th>
                                    <th class="text-center" width="20%">Komposisi</th>
                                    <th class="text-center" width="20%">Harga Satuan</th>
                                    <th class="text-center" width="20%">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
									$total_a = 0;
									$total_b = 0;
									$total_c = 0;
									$total_d = 0;
									$total_volume = 0;
                                    ?>
									<?php
									$total = $agregat['total_a'] + $agregat['total_b'] + $agregat['total_c'] + $agregat['total_d'];
									?>
                                        <tr>
                                            <td class="text-center">1.</td>
											<td class="text-left"><?= $agregat["produk_a"] = $this->crud_global->GetField('produk',array('id'=>$agregat['produk_a']),'nama_produk'); ?></td>
											<td class="text-center"><?= $agregat["measure_a"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure_a']),'measure_name'); ?></td>
											<td class="text-center"><?= $agregat["presentase_a"]; ?></td>
                                            <td class="text-right"><?php echo number_format($agregat["price_a"],0,',','.');?></td>
                                            <td class="text-right"><?php echo number_format($agregat["total_a"],0,',','.');?></td>
                                        </tr>
										<tr>
                                            <td class="text-center">2.</td>
											<td class="text-left"><?= $agregat["produk_b"] = $this->crud_global->GetField('produk',array('id'=>$agregat['produk_b']),'nama_produk'); ?></td>
											<td class="text-center"><?= $agregat["measure_b"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure_b']),'measure_name'); ?></td>
											<td class="text-center"><?= $agregat["presentase_b"]; ?></td>
                                            <td class="text-right"><?php echo number_format($agregat["price_b"],0,',','.');?></td>
                                            <td class="text-right"><?php echo number_format($agregat["total_b"],0,',','.');?></td>
                                        </tr>
										<tr>
                                            <td class="text-center">3.</td>
											<td class="text-left"><?= $agregat["produk_c"] = $this->crud_global->GetField('produk',array('id'=>$agregat['produk_c']),'nama_produk'); ?></td>
											<td class="text-center"><?= $agregat["measure_c"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure_c']),'measure_name'); ?></td>
											<td class="text-center"><?= $agregat["presentase_c"]; ?></td>
                                            <td class="text-right"><?php echo number_format($agregat["price_c"],0,',','.');?></td>
                                            <td class="text-right"><?php echo number_format($agregat["total_c"],0,',','.');?></td>
                                        </tr>
										<tr>
                                            <td class="text-center">4.</td>
											<td class="text-left"><?= $agregat["produk_d"] = $this->crud_global->GetField('produk',array('id'=>$agregat['produk_d']),'nama_produk'); ?></td>
											<td class="text-center"><?= $agregat["measure_d"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure_d']),'measure_name'); ?></td>
											<td class="text-center"><?= $agregat["presentase_d"]; ?></td>
                                            <td class="text-right"><?php echo number_format($agregat["price_d"],0,',','.');?></td>
                                            <td class="text-right"><?php echo number_format($agregat["total_d"],0,',','.');?></td>
                                        </tr>
										<tr>
											<td class="text-right" colspan="5"><b>TOTAL</b></td>
											<td class="text-right"><?php echo number_format($total,0,',','.');?></td>
										</tr>
                                </tbody>
                                <tfoot>
														
                                </tfoot>
                            </table>
                            <br />
							<br />
                            <div class="text-right">
                                <a href="<?= base_url("admin/rap/") ?>" target="" class="btn btn-info"><i class="fa fa-mail-reply"></i> Kembali</a>
                                <a href="<?= base_url("rap/cetak_komposisi/".$agregat["id"]) ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Cetak</a>
                                <?php
                                if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4){
                                ?>
                                <a class="btn btn-warning" href="<?= base_url('rap/sunting_komposisi/' . $agregat["id"]) ?>"><i class="fa fa-edit"></i> Edit</a>
								<!--<a class="btn btn-danger" onclick="DeleteData('<?= site_url('rap/hapus_komposisi_agregat/'.$agregat['id']);?>')"><i class="fa fa-close"></i> Hapus</a>-->
                                <br />
                                <form class="form-approval" action="<?= base_url("rap/closed_komposisi/".$agregat["id"]) ?>">
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-envelope-o"></i> Closed</button>
                                <?php
                                }
                                ?>
                                </form>	
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
