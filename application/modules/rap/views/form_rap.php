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
                            
                            <li><a>RAP</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">RAP</h3>                                
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rap/submit_agregat');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
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
										<!--<br />
										<br />
										<div class="col-sm-2">
                                            <label>Volume</label>
                                        </div>
										<div class="col-sm-2">
										<input type="text" id="volume" name="volume" class="form-control numberformat text-left" required="">
                                        </div>-->
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
                                    </div>
									<br />
										<div class="table-responsive">
											<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
												<thead>
													<tr class="text-center">
														<th width="5%">NO.</th>
														<th width="15%">KEBUTUHAN BAHAN</th>
														<th width="10%">VOLUME</th>
														<th width="40%">PENAWARAN</th>
														<th width="30%">HARGA SATUAN</th>                                 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="text-center">1.</td>
														<td>Boulder</td>
														<td></td>
														<td class="text-center"><select id="penawaran_boulder" class="form-control">
															<option value="">Pilih Penawaran</option>
															<?php

															foreach ($boulder as $key => $sm) {
																?>
																<option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-measure="<?php echo $sm['measure'];?>" data-price="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-id_penawaran="<?php echo $sm['id_penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?></option>
																<?php
															}
															?>
														</select>
														</td>
														<td>
															<input type="text" id="price_boulder" name="price_boulder" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="measure_boulder" name="measure_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="tax_id_boulder" name="tax_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="pajak_id_boulder" name="pajak_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="supplier_id_boulder" name="supplier_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="penawaran_id_boulder" name="penawaran_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
														</td>
													</tr>		
												</tbody>
											</table>    
										</div>

										<div class="table-responsive">
											<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
												<thead>
													<tr class="text-center">
														<th width="5%">NO.</th>
														<th width="15%">KEBUTUHAN ALAT</th>
														<th width="10%">VOLUME</th>
														<th width="40%">PENAWARAN</th>
														<th width="30%">HARGA SATUAN</th>                                 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="text-center">1.</td>
														<td>Boulder</td>
														<td></td>
														<td class="text-center"><select id="penawaran_boulder" class="form-control">
															<option value="">Pilih Penawaran</option>
															<?php

															foreach ($boulder as $key => $sm) {
																?>
																<option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-measure="<?php echo $sm['measure'];?>" data-price="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-id_penawaran="<?php echo $sm['id_penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?></option>
																<?php
															}
															?>
														</select>
														</td>
														<td>
															<input type="text" id="price_boulder" name="price_boulder" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="measure_boulder" name="measure_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="tax_id_boulder" name="tax_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="pajak_id_boulder" name="pajak_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="supplier_id_boulder" name="supplier_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
															<input type="hidden" id="penawaran_id_boulder" name="penawaran_id_boulder" class="form-control text-right" value=""  readonly="" autocomplete="off">
														</td>
													</tr>		
												</tbody>
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
