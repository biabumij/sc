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
                            <li><i class="fa fa-money" aria-hidden="true"></i>RAP</li>
                            
                            <li><a>RAP Bahan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">RAP Bahan</h3>                                
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rap/submit_agregat');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-2">
                                            <label>Mutu Beton / Slump</label>
                                        </div>
										<div class="col-sm-6">
											<select id="jobs_type" class="form-control form-select2" name="mutu_beton" required="" >
												<option value="">Pilih Produk</option>
												<?php
												if(!empty($mutu_beton)){
													foreach ($mutu_beton as $row) {
														?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
														<?php
													}
												}
												?>
											</select>
                                        </div>
										<br />
										<br />
										<div class="col-sm-2">
                                            <label>Volume</label>
                                        </div>
										<div class="col-sm-2">
										<input type="text" id="volume" name="volume" class="form-control numberformat text-left" required="">
                                        </div>
										<br />
										<br />
										<div class="col-sm-2">
                                            <label>Satuan</label>
                                        </div>
										<div class="col-sm-2">
											<select id="measure" class="form-control form-select2" name="measure" required="" >
												<option value="">Pilih Satuan</option>
												<?php
												if(!empty($measures)){
													foreach ($measures as $row) {
														?>
														<option value="<?php echo $row['id'];?>"><?php echo $row['measure_name'];?></option>
														<?php
													}
												}
												?>
											</select>
                                        </div>
										<br />
										<br />
										<div class="col-sm-2">
                                            <label>Judul</label>
                                        </div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control" name="jobs_type" required="" />
                                        </div>
										<br />
										<br />
										<div class="col-sm-2">
                                            <label>Tanggal</label>
                                        </div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control dtpicker" name="date_agregat" required="" value=""/>
                                        </div>
										<br />
										<br />
                                        <div class="col-sm-2">
                                            <label>Lamanya Tes</label>
                                        </div>
										<div class="col-sm-2">
                                            <input type="text" class="form-control" name="tes" required="" placeholder="Hari"/> 
                                        </div>
										<br />
										<br />                     
                                    </div>
										<br />
											<div class="table-responsive">
												<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
													<thead>
														<tr>
															<th width="5%">No</th>
															<th width="20%">Uraian</th>
															<th width="15%">Satuan</th>
															<th width="20%">Komposisi</th>
															<th width="20%">Harga Satuan</th>
															<th width="20%">Nilai</th>
														</tr>
													</thead>
													<tbody>
														 <tr>
															<td>1.</td>
															<td>														
																<select id="produk_a" class="form-control form-select2" name="produk_a" required="" >
																	<option value="">Pilih Produk</option>
																	<?php
																	if(!empty($products)){
																		foreach ($products as $row) {
																			?>
																			<option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<select id="measure_a" class="form-control form-select2" name="measure_a" required="" >
																	<option value="">Pilih Satuan</option>
																	<?php
																	if(!empty($measures)){
																		foreach ($measures as $ms) {
																			?>
																			<option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="text" id="presentase_a" name="presentase_a" class="form-control numberformat text-center" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="price_a" name="price_a" class="form-control rupiahformat text-right" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="total_a" name="total_a" class="form-control rupiahformat text-right" value="" required="" readonly="" autocomplete="off">
															</td>
														</tr>
														<tr>
															<td>2.</td>												
															<td>														
																<select id="produk_b" class="form-control form-select2" name="produk_b" required="" >
																	<option value="">Pilih Produk</option>
																	<?php
																	if(!empty($products)){
																		foreach ($products as $row) {
																			?>
																			<option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<select id="measure_b" class="form-control form-select2" name="measure_b" required="" >
																	<option value="">Pilih Satuan</option>
																	<?php
																	if(!empty($measures)){
																		foreach ($measures as $ms) {
																			?>
																			<option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="text" id="presentase_b" name="presentase_b" class="form-control numberformat text-center" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="price_b" name="price_b" class="form-control rupiahformat text-right" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="total_b" name="total_b" class="form-control rupiahformat text-right" value="" required="" readonly="" autocomplete="off">
															</td>
														</tr>
														<tr>
															<td>3.</td>
															<td>														
																<select id="produk_c" class="form-control form-select2" name="produk_c" required="" >
																	<option value="">Pilih Produk</option>
																	<?php
																	if(!empty($products)){
																		foreach ($products as $row) {
																			?>
																			<option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<select id="measure_c" class="form-control form-select2" name="measure_c" required="" >
																	<option value="">Pilih Satuan</option>
																	<?php
																	if(!empty($measures)){
																		foreach ($measures as $ms) {
																			?>
																			<option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="text" id="presentase_c" name="presentase_c" class="form-control numberformat text-center" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="price_c" name="price_c" class="form-control rupiahformat text-right" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="total_c" name="total_c" class="form-control rupiahformat text-right" value="" required="" readonly="" autocomplete="off">
															</td>
														</tr>
														<tr>
															<td>4.</td>
															<td>														
																<select id="produk_d" class="form-control form-select2" name="produk_d" required="" >
																	<option value="">Pilih Produk</option>
																	<?php
																	if(!empty($products)){
																		foreach ($products as $row) {
																			?>
																			<option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<select id="measure_d" class="form-control form-select2" name="measure_d" required="" >
																	<option value="">Pilih Satuan</option>
																	<?php
																	if(!empty($measures)){
																		foreach ($measures as $ms) {
																			?>
																			<option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</td>
															<td>
																<input type="text" id="presentase_d" name="presentase_d" class="form-control numberformat text-center" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="price_d" name="price_d" class="form-control rupiahformat text-right" value="" onchange="changeData(1)" required="" autocomplete="off">
															</td>
															<td>
																<input type="text" id="total_d" name="total_d" class="form-control rupiahformat text-right" value="" required="" readonly="" autocomplete="off">
															</td>
															<tr>
														</tr>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="5" class="text-right">GRAND TOTAL</td>
															<td>
															<input type="text" id="sub-total-val" name="sub_total" value="" class="form-control rupiahformat tex-left text-right" readonly="">
															</td>
														</tr> 
													</tfoot>
												</table>    
											</div>
											<br />
											<div class="col-sm-12">
													<div class="form-group">
														<label>Keterangan</label>
														<textarea class="form-control" name="memo" data-required="false" id="about_text">

														</textarea>
													</div>
											</div>
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<label>Lampiran</label>
														<input type="file" class="form-control" name="files[]"  multiple="" />
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12 text-right">
													<a href="<?= site_url('admin/rap');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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


</body>
</html>
