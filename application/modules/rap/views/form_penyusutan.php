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
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-money" aria-hidden="true"></i>RAP</li>
                            
                            <li><a>Penyusutan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Penyusutan</h3>                                
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rap/submit_penyusutan');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-2">
                                            <label>Tanggal Perolehan</label>
                                        </div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control dtpicker" name="tanggal_penyusutan" required="" value=""/>
                                        </div>        
                                    </div>
									<br />
									<div class="table-responsive">
										<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
											<thead>
												<tr class="text-center">
													<th width="5%">NO.</th>
													<th width="50%">PRODUK</th>
													<th width="45%">HARGA PEROLEHAN</th>                           
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="text-center">1.</td>
													<td style="text-align: left !important;">
													<select name="produk" class="form-control form-select2" required="">
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
													</td>
													<td>
														<input type="text" name="nilai_penyusutan" class="form-control rupiahformat text-right" value=""  autocomplete="off">
													</td>
													<td>
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
										<div class="col-sm-12 text-center">
											<a href="<?= site_url('admin/rap');?>" class="btn btn-danger" style="margin-bottom:0; width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Batal</a>
											<button type="submit" class="btn btn-success" style="width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
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

		$('#penawaran_boulder').change(function(){
			var penawaran_id = $(this).find(':selected').data('penawaran_id');
			$('#penawaran_boulder').val(penawaran_id);
			var price = $(this).find(':selected').data('price');
			$('#price_boulder').val(price);
			var supplier_id = $(this).find(':selected').data('supplier_id');
			$('#supplier_id_boulder').val(supplier_id);
			var measure = $(this).find(':selected').data('measure');
			$('#measure_boulder').val(measure);
			var tax_id = $(this).find(':selected').data('tax_id');
			$('#tax_id_boulder').val(tax_id);
			var pajak_id = $(this).find(':selected').data('pajak_id');
			$('#pajak_id_boulder').val(pajak_id);
			var id_penawaran = $(this).find(':selected').data('id_penawaran');
			$('#penawaran_id_boulder').val(penawaran_id);
		});

    </script>


</body>
</html>