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
                        <li><a href="<?= base_url("admin/komposisi/") ?>">Komposisi</a></li>
                        <li><a href="<?= base_url('komposisi/data_komposisi/' . $agregat["id"]) ?>">Data Komposisi</a></li>
                        <li><a>Edit Komposisi Agregat</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                                <div class="">
                                    <h3 class="">Detail Komposisi Agregat</h3>
                                </div>
                        </div>
                        <div class="panel-content">
                            <form method="POST" action="<?php echo site_url('rap/submit_sunting_agregat');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="id" value="<?= $agregat["id"] ?>">
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
											<td class="text-center"><input type="text" id="presentase_a" name="presentase_a" class="form-control text-center" value="<?= $agregat['presentase_a'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="price_a" name="price_a" class="form-control rupiahformat text-right" value="<?= $agregat['price_a'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="total_a" name="total_a" class="form-control rupiahformat text-right" value="<?= $agregat['total_a'] ?>" onkeyup="sum();" required="" autocomplete="off"></td>
                                        </tr>
										<tr>
                                            <td class="text-center">2.</td>
											<td class="text-left"><?= $agregat["produk_b"] = $this->crud_global->GetField('produk',array('id'=>$agregat['produk_b']),'nama_produk'); ?></td>
											<td class="text-center"><?= $agregat["measure_b"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure_b']),'measure_name'); ?></td>
											<td class="text-center"><input type="text" id="presentase_b" name="presentase_b" class="form-control text-center" value="<?= $agregat['presentase_b'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="price_b" name="price_b" class="form-control rupiahformat text-right" value="<?= $agregat['price_b'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="total_b" name="total_b" class="form-control rupiahformat text-right" value="<?= $agregat['total_b'] ?>" onkeyup="sum();" required="" autocomplete="off"></td>
                                        </tr>
										<tr>
                                            <td class="text-center">3.</td>
											<td class="text-left"><?= $agregat["produk_c"] = $this->crud_global->GetField('produk',array('id'=>$agregat['produk_c']),'nama_produk'); ?></td>
											<td class="text-center"><?= $agregat["measure_c"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure_c']),'measure_name'); ?></td>
											<td class="text-center"><input type="text" id="presentase_c" name="presentase_c" class="form-control text-center" value="<?= $agregat['presentase_c'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="price_c" name="price_c" class="form-control rupiahformat text-right" value="<?= $agregat['price_c'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="total_c" name="total_c" class="form-control rupiahformat text-right" value="<?= $agregat['total_c'] ?>" onkeyup="sum();" required="" autocomplete="off"></td>
                                        </tr>
										<tr>
                                            <td class="text-center">4.</td>
											<td class="text-left"><?= $agregat["produk_d"] = $this->crud_global->GetField('produk',array('id'=>$agregat['produk_d']),'nama_produk'); ?></td>
											<td class="text-center"><?= $agregat["measure_d"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$agregat['measure_d']),'measure_name'); ?></td>
											<td class="text-center"><input type="text" id="presentase_d" name="presentase_d" class="form-control text-center" value="<?= $agregat['presentase_d'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="price_d" name="price_d" class="form-control rupiahformat text-right" value="<?= $agregat['price_d'] ?>" onchange="changeData(1)" required="" autocomplete="off"></td>
                                            <td class="text-right"><input type="text" id="total_d" name="total_d" class="form-control rupiahformat text-right" value="<?= $agregat['total_d'] ?>" onkeyup="sum();" required="" autocomplete="off"></td>
                                        </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right">GRAND TOTAL&nbsp;</td>
                                        <td>
                                        <input type="text" id="sub-total-val" name="sub_total" value="" class="form-control rupiahformat tex-left text-right" readonly="">
                                        </td>
                                    </tr> 
                                </tfoot>
                            </table>
                            <br />
							<br />
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="<?= base_url('rap/data_komposisi/' . $agregat["id"]) ?>" class="btn btn-info" style="margin-bottom:0;"><i class="fa fa-mail-reply"></i> Kembali</a>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Kirim</button>
                                </div>
                            </div>
                            </form>
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
        $('.form-select2').select2();

        $('input.numberformat').number( true, 4,',','.' );
		$('input.rupiahformat').number( true, 0,',','.' );

        $('#form-po').submit(function(e){
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

		function changeData(id)
        {
			var presentase_a = $('#presentase_a').val();
			var presentase_b = $('#presentase_b').val();
			var presentase_c = $('#presentase_c').val();
			var presentase_d = $('#presentase_d').val();

			var price_a = $('#price_a').val();
			var price_b = $('#price_b').val();
			var price_c = $('#price_c').val();
			var price_d = $('#price_d').val();
            				
			total_a = ( presentase_a * price_a );
			$('#total_a').val(total_a);
			total_b = ( presentase_b * price_b );
			$('#total_b').val(total_b);
			total_c = ( presentase_c * price_c );
			$('#total_c').val(total_c);
			total_d = ( presentase_d * price_d );
			$('#total_d').val(total_d);
            getTotal();

        }

        function getTotal()
        {
            var sub_total = $('#sub-total-val').val();

            sub_total = parseInt($('#total_a').val()) + parseInt($('#total_b').val()) + parseInt($('#total_c').val()) + parseInt($('#total_d').val());
            
            $('#sub-total-val').val(sub_total);
            $('#sub-total').text($.number( sub_total, 0,',','.' ));

            total_total = parseInt(sub_total);
            $('#total-val').val(total_total);
            $('#total').text($.number( total_total, total_d,',','.' ));
        }

    </script>
    

</body>
</html>
