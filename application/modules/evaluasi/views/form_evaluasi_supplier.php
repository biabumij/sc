<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

	<style type="text/css">
        .table-center th{
            text-align:center;
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
                            <li><a href="<?php echo site_url('admin/evaluasi#evaluasi_supplier');?>">RAP</a></li>
                            <li><a>Buat Evaluasi Supplier</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Evaluasi Supplier</h3>                                
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('evaluasi/submit_evaluasi_supplier');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-2">
                                            <label>Tanggal<span class="required" aria-required="true">*</span></label>
                                        </div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control dtpicker" name="tanggal" required="" value=""/>
                                        </div>
										<br /><br />
										<div class="col-sm-2">
                                            <label>Nama Supplier<span class="required" aria-required="true">*</span></label>
                                        </div>
										<div class="col-sm-6">
											<select id="supplier_id" class="form-control form-select2" name="supplier_id" required="" >
												<option value="">Pilih Rekanan</option>
												<?php
												if(!empty($supplier)){
													foreach ($supplier as $row) {
														?>
														<option value="<?php echo $row['id'];?>" data-address="<?= $row["alamat"] ?>" data-idSupplier="<?= $row["id"] ?>" data-kontak="<?= $row["nama_kontak"] ?>" data-telepon="<?= $row["telepon"] ?>" data-email="<?= $row["email"] ?>" ><?php echo $row['nama'];?></option>
														<?php
													}
												}
												?>
											</select>
                                        </div>
										<br /><br />
										<div class="col-sm-2">
                                            <label>Bidang Usaha<span class="required" aria-required="true">*</span></label>
                                        </div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control" name="bidang_usaha" required="" />
                                        </div>
										<br /><br />
										<div class="col-sm-2">
                                            <label>Alamat<span class="required" aria-required="true">*</span></label>
                                        </div>
										<div class="col-sm-6">
											<textarea class="form-control" rows="4" name="alamat_supplier" id="alamat_supplier" readonly="" ></textarea>
                                        </div>
										<br /><br /><br /><br /><br />
										<div class="col-sm-2">
                                            <label>Nama Kontak<span class="required" aria-required="true">*</span></label>
                                        </div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="nama_kontak"  id="nama_kontak" readonly="" />
                                        </div>
										<br /><br />
										<div class="col-sm-2">
                                            <label>Nomor Kontak<span class="required" aria-required="true">*</span></label>
                                        </div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="nomor_kontak"  id="nomor_kontak" readonly="" />
                                        </div>
										<br /><br />
										<div class="col-sm-2">
                                            <label>Email<span class="required" aria-required="true">*</span></label>
                                        </div>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="email"  id="email" readonly="" />
                                        </div>                 
                                    </div>
									<br /><br />
									<div class="table-responsive">
										<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
											<thead>
												<tr>
													<th width="5%" rowspan="2" style="vertical-align:middle;">No</th>
													<th width="35%" rowspan="2" style="vertical-align:middle;">Kriteria Evaluasi</th>
													<th width="8%" style="background-color:#6aa84f;">Puas</th>
													<th width="8%" style="background-color:#93c47d;">Baik</th>
													<th width="8%" style="background-color:#ffd966;">Cukup</th>
													<th width="8%" style="background-color:#f1c232;">Kurang</th>
													<th width="8%" style="background-color:#e06666;">Buruk</th>
													<th width="25%" rowspan="2" style="vertical-align:middle;">Catatan</th>
												</tr>
												<tr>
													<th style="background-color:#6aa84f;">5</th>
													<th style="background-color:#93c47d;">4</th>
													<th style="background-color:#ffd966;">3</th>
													<th style="background-color:#f1c232;">2</th>
													<th style="background-color:#e06666;">1</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1.</td>
													<td align="left">Kualitas barang atau jasa</td>
													<td align="center"><input type="checkbox" name="puas_1" id="puas_1" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_1" id="baik_1" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_1" id="cukup_1" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_1" id="kurang_1" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_1" id="buruk_1" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_1" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>2.</td>
													<td align="left">Ketepatan waktu</td>
													<td align="center"><input type="checkbox" name="puas_2" id="puas_2" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_2" id="baik_2" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_2" id="cukup_2" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_2" id="kurang_2" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_2" id="buruk_2" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_2" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>3.</td>
													<td align="left">Harga dibandingkan kualitas</td>
													<td align="center"><input type="checkbox" name="puas_3" id="puas_3" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_3" id="baik_3" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_3" id="cukup_3" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_3" id="kurang_3" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_3" id="buruk_3" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_3" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>4.</td>
													<td align="left">Kesadaran terhadap keselamatan dan kesehatan kerja</td>
													<td align="center"><input type="checkbox" name="puas_4" id="puas_4" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_4" id="baik_4" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_4" id="cukup_4" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_4" id="kurang_4" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_4" id="buruk_4" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_4" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>5.</td>
													<td align="left">Kesadaran terhadap lingkungan</td>
													<td align="center"><input type="checkbox" name="puas_5" id="puas_5" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_5" id="baik_5" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_5" id="cukup_5" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_5" id="kurang_5" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_5" id="buruk_5" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_5" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>6.</td>
													<td align="left">Kompetensi tenaga kerja</td>
													<td align="center"><input type="checkbox" name="puas_6" id="puas_6" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_6" id="baik_6" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_6" id="cukup_6" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_6" id="kurang_6" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_6" id="buruk_6" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_6" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>7.</td>
													<td align="left">Kemampuan menangani keluhan</td>
													<td align="center"><input type="checkbox" name="puas_7" id="puas_7" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_7" id="baik_7" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_7" id="cukup_7" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_7" id="kurang_7" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_7" id="buruk_7" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_7" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>8.</td>
													<td align="left">Layanan purnajual</td>
													<td align="center"><input type="checkbox" name="puas_8" id="puas_8" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_8" id="baik_8" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_8" id="cukup_8" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_8" id="kurang_8" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_8" id="buruk_8" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_8" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>9.</td>
													<td align="left">Kelengkapan dokumen</td>
													<td align="center"><input type="checkbox" name="puas_9" id="puas_9" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_9" id="baik_9" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_9" id="cukup_9" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_9" id="kurang_9" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_9" id="buruk_9" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_9" placeholder="Catatan"/></td>
												</tr>
												<tr>
													<td>9.</td>
													<td align="left">Kinerja sebelumnya</td>
													<td align="center"><input type="checkbox" name="puas_10" id="puas_10" value="5"></td>
													<td align="center"><input type="checkbox" name="baik_10" id="baik_10" value="4"></td>
													<td align="center"><input type="checkbox" name="cukup_10" id="cukup_10" value="3"></td>
													<td align="center"><input type="checkbox" name="kurang_10" id="kurang_10" value="2"></td>
													<td align="center"><input type="checkbox" name="buruk_10" id="buruk_10" value="1"></td>
													<td align="center"><input type="text" class="form-control" name="catatan_10" placeholder="Catatan"/></td>
												</tr>
											</tbody>
											<tfoot>
											</tfoot>
										</table>
										<br />
										<div class="col-sm-12">
											<div class="form-group">
												<label>Catatan</label>
												<textarea class="form-control" name="memo" data-required="false" id="about_text">

												</textarea>
											</div>
											<div class="table-responsive">
												<table class="table">
													<thead>
														
													</thead>
													<tbody>
														<tr>
															<td width="30%"></td>
															<td width="10%"></td>
															<td width="10%"><a href="<?= site_url('admin/evaluasi#evaluasi_supplier');?>" class="btn btn-info" style="width:100%; font-weight:bold;"><i class="fa fa-arrow-left"></i> Kembali</a></td>
															<td width="10%"><button type="submit" class="btn btn-success" style="width:100%; font-weight:bold;"><i class="fa fa-send"></i> Kirim</button></td>
															<td width="10%"></td>
															<td width="30%"></td>
														</tr>
													</tbody>
												</table>
											</div>
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

    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
   
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    

    <script type="text/javascript">
        
        $('.form-select2').select2();

        $('input.numberformat').number( true, 4,',','.' );
		$('input.rupiahformat').number( true, 0,',','.' );

        tinymce.init({
          selector: 'textarea#about_text',
          height: 200,
          menubar: false,
        });
        $('.dtpicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns : true,
            locale: {
              format: 'DD-MM-YYYY'
            }
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY'));
              // table.ajax.reload();
        });



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
	
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_a').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_b').prop('selectedIndex', 2).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_c').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_d').prop('selectedIndex', 4).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_a').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_b').prop('selectedIndex', 4).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_c').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_d').prop('selectedIndex', 1).trigger('change');
            }, 1000);
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

	<script>
	$('#supplier_id').on('change', function() {
		var address = $(this).find(':selected').attr('data-address')
		var idSupplier = $(this).find(':selected').attr('data-idSupplier')
		var kontak = $(this).find(':selected').attr('data-kontak')
		var telepon = $(this).find(':selected').attr('data-telepon')
		var email = $(this).find(':selected').attr('data-email')
		$("#alamat_supplier").val(address);
		$("#nama_kontak").val(kontak);
		$("#nomor_kontak").val(telepon);
		$("#email").val(email);
	});
	
	</script>


</body>
</html>
