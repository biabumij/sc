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
                                <a href="<?php echo site_url('admin/produksi');?>"> <i class="fa fa-sitemap" aria-hidden="true"></i> Produksi</a></li>
                            <li><a>Komposisi Agregat</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Komposisi Agregat Baru</h3>                                
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('produksi/submit_agregat');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
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
										<br />
										<br />
                                        <div class="col-sm-2">
                                            <label>Nomor Komposisi</label>
                                        </div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control" name="no_komposisi" required="" value="<?= $this->pmm_model->GetNoAgregat();?>">
                                        </div> 	                       
                                    </div>
										<br />
											<div class="table-responsive">
												<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
													<thead>
														<tr>
															<th width="5%">No</th>
															<th width="35%">Agregat</th>
															<th width="20%">Satuan</th>
															<th width="30%">Presentase</th>
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
																<input type="number" step=".01" min="0" name="presentase_a" id="presentase_a" class="form-control input-sm text-center numberformat" onkeyup="sum();" required="" />
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
																<input type="number" step=".01" min="0" name="presentase_b" id="presentase_b" class="form-control input-sm text-center numberformat" onkeyup="sum();" required="" />
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
																<input type="number" step=".01" min="0" name="presentase_c" id="presentase_c" class="form-control input-sm text-center numberformat" onkeyup="sum();" required="" />
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
																<input type="number" step=".01" min="0" name="presentase_d" id="presentase_d" class="form-control input-sm text-center numberformat" onkeyup="sum();" required="" />
															</td>
															<tr>
														<td colspan="3"><b>TOTAL</b></td>														                                               
														<td>
															<input type="number" step=".01" min="0" name="total_presentase" id="total_presentase" class="form-control input-sm text-center numberformat" readonly=""/>
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
													<a href="<?= site_url('admin/produksi');?>" class="btn btn-danger" style="margin-bottom:0; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Batal</a>
													<button type="submit" class="btn btn-success" style="font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
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

        $('input.numberformat').number( true, 2,',','.' );
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
                $('#agregat_kelas').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });		
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_a').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_b').prop('selectedIndex', 4).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_c').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#produk_d').prop('selectedIndex', 2).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_a').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_b').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_c').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_d').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		
		function sum() {
		var txtFirstNumberValue = document.getElementById('presentase_a').value;
		var txtSecondNumberValue = document.getElementById('presentase_b').value;
		var txtThirdNumberValue = document.getElementById('presentase_c').value;
		var txtFourthNumberValue = document.getElementById('presentase_d').value;
		var result = parseInt(txtFourthNumberValue) + parseInt(txtSecondNumberValue) + parseInt(txtThirdNumberValue) + parseInt(txtFirstNumberValue);
		if (!isNaN(result)) {
		 document.getElementById('total_presentase').value = result;
			}
		}
		
    </script>


</body>
</html>
