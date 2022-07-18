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
                        <li><a>Laboratorium</a></li>
						<li><a>Job Mix Design</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Job Mix Design <?php echo $this->pmm_model->GetStatus4($jmd['status']);?></h3>
                                </div>
                        </div>
                        <div class="panel-content">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th >Tanggal JMD </th>
                                     <td>: <?= convertDateDBtoIndo($jmd["tanggal_jmd"]); ?></td>								
                                </tr>
								<tr>
                                    <th >Mutu Beton</th>
                                    <td>: <?= $jmd["mutu_beton"] = $this->crud_global->GetField('produk',array('id'=>$jmd['mutu_beton']),'nama_produk'); ?></td>
                                </tr>
								<tr>
                                    <th >Slump</th>
                                    <td>: <?= $jmd["slump"]; ?></td>
                                </tr>
								<tr>
                                    <th >Nama Komposisi</th>
                                    <td>: <?= $jmd["nama_komposisi"]; ?></td>
                                </tr>
                                <tr>
                                    <th >Nomor Komposisi</th>
                                    <td>: <?= $jmd["nomor_komposisi"]; ?></td>
                                </tr>
                                <tr>
                                    <th width="100px">Lampiran</th>
                                    <td>:  
                                        <?php foreach($lampiran as $l) : ?>                                    
                                        <a href="<?= base_url("uploads/jmd/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                        <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: <?= $jmd["memo"] ?></td>
                                </tr>
                            </table>
                            
                            <table class="mytable table-bordered table-hover table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="50px">No</th>
                                        <th class="text-center" >Produk</th>
                                        <th class="text-center" >Kode</th>
										<th class="text-center" >Volume</th>
                                        <th class="text-center" >Satuan</th>

                                    </tr>
                                </thead>
                                <tbody>
									<tr>
										<td class="text-center" colspan="5"><h4>Berat Jenis Isi</h4></td>
									</tr>
									<tr>
										<td class="text-center">1.</td>
										<td class="text-center"><?= $jmd["beton_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['beton_1']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_1"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_1']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_1"]; ?></td>
										<td class="text-center"><?= $jmd["measure_1"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_1']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">2.</td>
										<td class="text-center"><?= $jmd["semen_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['semen_1']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_2"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_2']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_2"]; ?></td>
										<td class="text-center"><?= $jmd["measure_2"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_2']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">3.</td>
										<td class="text-center"><?= $jmd["pasir_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['pasir_1']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_3"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_3']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_3"]; ?></td>
										<td class="text-center"><?= $jmd["measure_3"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_3']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">4.</td>
										<td class="text-center"><?= $jmd["aggregat_kasar_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['aggregat_kasar_1']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_4"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_4']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_4"]; ?></td>
										<td class="text-center"><?= $jmd["measure_4"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_4']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">5.</td>
										<td class="text-center"><?= $jmd["faktor_kehilangan_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['faktor_kehilangan_1']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_5"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_5']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_5"]; ?></td>
										<td class="text-center"><?= $jmd["measure_5"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_5']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center" colspan="5"><h4>Berat Isi Material</h4></td>
									</tr>
									<tr>
										<td class="text-center">1.</td>
										<td class="text-center"><?= $jmd["pasir_2"] = $this->crud_global->GetField('produk',array('id'=>$jmd['pasir_2']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_6"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_6']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_6"]; ?></td>
										<td class="text-center"><?= $jmd["measure_6"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_6']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">2.</td>
										<td class="text-center"><?= $jmd["aggregat_kasar_2"] = $this->crud_global->GetField('produk',array('id'=>$jmd['aggregat_kasar_2']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_7"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_7']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_7"]; ?></td>
										<td class="text-center"><?= $jmd["measure_7"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_7']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center" colspan="5"><h4>Material</h4></td>
									</tr>
									<tr>
										<td class="text-center">1.</td>
										<td class="text-center"><?= $jmd["semen_2"] = $this->crud_global->GetField('produk',array('id'=>$jmd['semen_2']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_8"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_8']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_8"]; ?></td>
										<td class="text-center"><?= $jmd["measure_8"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_8']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">2.</td>
										<td class="text-center"><?= $jmd["pasir_3"] = $this->crud_global->GetField('produk',array('id'=>$jmd['pasir_3']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_9"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_9']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_9"]; ?></td>
										<td class="text-center"><?= $jmd["measure_9"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_9']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">3.</td>
										<td class="text-center"><?= $jmd["batu_split_12"] = $this->crud_global->GetField('produk',array('id'=>$jmd['batu_split_12']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_10"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_10']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_10"]; ?></td>
										<td class="text-center"><?= $jmd["measure_10"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_10']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">4.</td>
										<td class="text-center"><?= $jmd["batu_split_23"] = $this->crud_global->GetField('produk',array('id'=>$jmd['batu_split_23']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_11"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_11']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_11"]; ?></td>
										<td class="text-center"><?= $jmd["measure_11"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_11']),'measure_name'); ?></td>
									</tr>
									<tr>
										<td class="text-center">5.</td>
										<td class="text-center"><?= $jmd["additon"] = $this->crud_global->GetField('produk',array('id'=>$jmd['additon']),'nama_produk'); ?></td>
										<td class="text-center"><?= $jmd["kode_12"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_12']),'kode'); ?></td>
										<td class="text-center"><?= $jmd["volume_12"]; ?></td>
										<td class="text-center"><?= $jmd["measure_12"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_12']),'measure_name'); ?></td>
									</tr>
                                </tbody>
                                <tfoot>
									
                                </tfoot>
                            </table>
                            <br />
							<br />
                            
                            <div class="text-right">
								<a href="<?= base_url("admin/laboratorium/") ?>" target="" class="btn btn-info"><i class="fa fa-mail-reply"></i> Kembali</a>
								<a class="btn btn-danger" onclick="DeleteData('<?= site_url('laboratorium/hapus_jmd/'.$jmd['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
								<a href="<?= base_url("laboratorium/cetak_jmd/".$jmd["id"]) ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Cetak PDF</a>
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
