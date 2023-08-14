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
                        <li><a>Detail Produksi Harian</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Produksi Harian</h3>
                                </div>
                        </div>
                        <div class="panel-content">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="25%">Tanggal Produksi Harian </th>
                                    <td width="75%">: <?= convertDateDBtoIndo($prod["date_prod"]); ?></td>
                                </tr>
                                <tr>
                                    <th >Nomor Produksi Harian</th>
                                    <td>: <?= $prod["no_prod"]; ?></td>
                                </tr>
                                <tr>
                                    <th width="100px">Lampiran</th>
                                    <td>:  
                                        <?php foreach($lampiran as $l) : ?>                                    
                                        <a href="<?= base_url("uploads/produksi_harian/".$l["lampiran"]) ?>" target="_blank">Lihat bukti  <?= $l["lampiran"] ?> <br></a></td>
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
                                        <th class="text-center" width="25%">Nomor Kalibrasi</th>
                                        <th class="text-center" width="10%">Start</th>
										<th class="text-center" width="10%">End</th>
										<th class="text-center" width="10%">Durasi produksi (Jam)</th>
                                        <th class="text-center" width="10%">Jumlah Bucket</th>
                                        <th class="text-center" width="10%">Rata-rata Kapasitas Bucket (Ton)</th>
										<th class="text-center" width="10%">Pemakaian Bahan Baku (Ton)</th>
										<th class="text-center" width="10%">Kapasitas Produksi Ton/Jam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									$subtotal_duration = 0;
                                    $subtotal_hours = 0;
									$subtotal_day = 0;
									$subtotal_use = 0;
									$subtotal_capacity = 0;
                                    $tax_pph = 0;
                                    $tax_ppn = 0;
                                    $tax_0 = false;
                                    $total = 0;

                                    ?>
                                    <?php foreach($details as $no => $d) : ?>
                                        <?php
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no + 1;?></td>
                                            <td class="text-center"><?= $d["product_id"] = $this->crud_global->GetField('pmm_kalibrasi',array('id'=>$d['product_id']),'no_kalibrasi') ?></td>								
                                            <td class="text-center"><?= $d["start"]; ?></td>
											<td class="text-center"><?= $d["end"]; ?></td>
											<td class="text-center"><?= $d["duration"]; ?></td>
                                            <td class="text-center"><?= $d["hours"]; ?></td>
											<td class="text-center"><?= $d["day"]; ?></td>
											<td class="text-center"><?= $d["use"]; ?></td>
											<td class="text-center"><?= $d["capacity"]; ?></td>
                                        </tr>

										<?php
										$subtotal_duration += $d['duration'];
                                        $subtotal_hours += $d['hours'];
										$subtotal_day += $d['day'];
										$day = array($d['day']);
										$total_day = count($day);
										$sum_day = array_sum($day);
										$subtotal_avg_day = $sum_day / $total_day;
										$subtotal_use += $d['use'];
										$subtotal_capacity = ($subtotal_duration!=0)?($subtotal_use / $subtotal_duration)  * 1:1;
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                        <th colspan="4" class="text-right">Total</th>
										<th  class="text-center"><?= number_format($subtotal_duration,2,',','.');?></th>
                                        <th  class="text-center"><?= number_format($subtotal_hours,2,',','.');?></th>
										<th  class="text-center"><?= number_format($subtotal_avg_day,2,',','.');?></th>
										<th  class="text-center"><?= number_format($subtotal_use,2,',','.');?></th>
										<th  class="text-center"><?= number_format($subtotal_capacity,2,',','.');?></th>											
                                    </tr>
                                </tfoot>
                            </table>
							<br />
							<br />
                            
                            
                            <div class="text-right">
                                <a href="<?= base_url("admin/produksi/") ?>" target="" class="btn btn-info" style="width:15%; font-weight:bold;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                <a href="<?= base_url("produksi/cetak_produksi_harian/".$prod["id"]) ?>" target="_blank" class="btn btn-default" style="width:15%; font-weight:bold;"><i class="fa fa-print"></i> Cetak PDF</a>
                                
                                <?php
                                if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11 || $this->session->userdata('admin_group_id') == 15){
                                ?>
								<a class="btn btn-danger" onclick="DeleteData('<?= site_url('produksi/hapus_produksi_harian/'.$prod['id']);?>')" style="width:15%; font-weight:bold;"><i class="fa fa-close"></i> Hapus</a>
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
