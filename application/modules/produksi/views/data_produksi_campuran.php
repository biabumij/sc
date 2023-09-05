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
		  border: solid 1px #000000;
		  color: #000000;
		  /*padding: 30px;*/
		  text-align: center;
		  vertical-align: middle;
		  padding : 3px;
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
                        <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a href="<?php echo site_url('admin/produksi'); ?>"> Produksi</a></li>
                        <li><a>Detail Produksi Campuran</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Produksi Campuran</h3>
                                </div>
                        </div>
                        <div class="panel-content">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="25%">Tanggal Produksi Campuran </th>
                                    <td width="75%">: <?= convertDateDBtoIndo($prod["date_prod"]); ?></td>
                                </tr>
                                <tr>
                                    <th >Nomor Produksi Campuran</th>
                                    <td>: <?= $prod["no_prod"]; ?></td>
                                </tr>
                                <tr>
                                    <th width="100px">Lampiran</th>
                                    <td>:  
                                        <?php foreach($lampiran as $l) : ?>                                    
                                        <a href="<?= base_url("uploads/produksi_campuran/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
                                        <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>: <?= $prod["memo"] ?></td>
                                </tr>
                            </table>
                            
                            <table class="mytable table-bordered table-hover table-striped" align="center" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center" width="25%">Nama Komposisi</th>
										<th class="text-center" width="20%">Uraian</th>
                                        <th class="text-center" width="10%">Volume</th>
                                        <th class="text-center" width="10%">Satuan</th>
										<th class="text-center" width="10%">Convert</th>
										<th class="text-center" width="10%">Volume Convert</th>
										<th class="text-center" width="10%">Satuan Convert</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									$subtotal_volume = 0;
                                    $subtotal_volume_convert = 0;
                                    ?>
                                    <?php foreach($details as $no => $d) : ?>
                                        <?php
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no + 1;?></td>
                                            <td class="text-center"><?= $d["product_id"] = $this->crud_global->GetField('pmm_agregat',array('id'=>$d['product_id']),'jobs_type') ?></td>								
                                            <td class="text-center"><?= $d["uraian"]; ?></td>
											<td class="text-center"><?= $d["volume"]; ?></td>
											<td class="text-center"><?= $d["measure"]; ?></td>
                                            <td class="text-center"><?= $d["convert"]; ?></td>
											<td class="text-center"><?= $d["volume_convert"]; ?></td>
											<td class="text-center"><?= $d["measure_convert"]; ?></td>
                                        </tr>

										<?php
										$subtotal_volume += $d['volume'];
                                        $subtotal_volume_convert += $d['volume_convert'];
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                        <th colspan="3" class="text-center">TOTAL</th>
                                        <th  class="text-center"><?= number_format($subtotal_volume,2,',','.');?></th>
										<th  class="text-center"><?= $d["measure"]; ?></th>
										<th  class="text-center"></th>
										<th  class="text-center"><?= number_format($subtotal_volume_convert,2,',','.');?></th>
										<th  class="text-center"><?= $d["measure_convert"]; ?></th>											
                                    </tr>
                                </tfoot>
                            </table>
							<br />
							<br />
                            
                            
                            <div class="text-right">
                                <a href="<?= base_url("admin/produksi/") ?>" target="" class="btn btn-info" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                <a href="<?= base_url("produksi/cetak_produksi_campuran/".$prod["id"]) ?>" target="_blank" class="btn btn-default" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-print"></i> Cetak PDF</a>
                                <?php
                                if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                ?>
								<a class="btn btn-danger" onclick="DeleteData('<?= site_url('produksi/hapus_produksi_campuran/'.$prod['id']);?>')" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Hapus</a>
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
    

    
    <script type="text/javascript">
        var form_control = '';
    </script>
    
	<?php echo $this->Templates->Footer();?>
	

	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script type="text/javascript">
		$('.timepicker').timepicker({
			timeFormat: 'HH:mm',
			interval: 60,
			//defaultTime: '08',
			use24hours: true,
			dynamic: false,
			dropdown: true,
			scrollbar: true
		});
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
