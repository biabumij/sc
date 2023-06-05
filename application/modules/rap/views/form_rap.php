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
                            
                            <li><a>Analisa Harga Satuan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Analisa Harga Satuan</h3>                                
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rap/submit_rap');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-2">
                                            <label>Jenis Pekerjaan</label>
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
                                            <input type="text" class="form-control dtpicker" name="tanggal_rap" required="" value=""/>
                                        </div>
										<br />
										<br />         
                                    </div>
									<br />
										<div class="table-responsive">
											<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
												<thead>
													<tr class="text-center">
														<th width="5%">NO.</th>
														<th width="15%">KEBUTUHAN BAHAN</th>
														<th width="30%">PENAWARAN</th>
														<th width="20%">PERKIRAAN KUANTITAS (M3)</th>
														<th width="30%">HARGA SATUAN</th>                                 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="text-center">1.</td>
														<td>Boulder</td>
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
															<input type="text" id="vol_boulder" name="vol_boulder" class="form-control numberformat text-right" value="" autocomplete="off">
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
													<tr>
														<td class="text-center">2.</td>
														<td>Berat Isi Boulder</td>
														<td><input type="text" id="berat_isi_boulder" name="berat_isi_boulder" class="form-control numberformat text-right" value=""  autocomplete="off"></td>
														<td></td>
														<td></td>
													</tr>			
												</tbody>
											</table>    
										</div>

										<div class="table-responsive">
											<table id="table-product" class="table table-bordered table-striped table-condensed table-center">
												<thead>
													<tr class="text-center">
														<th width="5%">NO.</th>
														<th width="45%">URAIAN</th>
														<th width="50%">NILAI</th>                                 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="text-center" rowspan="3" style="vertical-align:middle;">1.</td>
														<td style="text-align: left !important;">Kapasitas Alat (Pemecah Batu) - Stone Crusher</td>
														<td colspan="2">
															<input type="text" id="kapasitas_alat_sc" name="kapasitas_alat_sc" class="form-control numberformat text-right" value=""  autocomplete="off">
														</td>
													</tr>
													<tr>
														<td style="text-align: left !important;">Faktor Efisiensi Alat (Pemecah Batu) - Stone Crusher</td>
														<td colspan="2">
															<input type="text" id="efisiensi_alat_sc" name="efisiensi_alat_sc" class="form-control numberformat text-right" value=""  autocomplete="off">
														</td>
													</tr>
													<tr>
														<td style="text-align: left !important;">Berat Isi - Batu Pecah</td>
														<td colspan="2">
															<input type="text" id="berat_isi_batu_pecah" name="berat_isi_batu_pecah" class="form-control numberformat text-right" value=""  autocomplete="off">
														</td>
													</tr>
													
													<tr>
														<td class="text-center" rowspan="3" style="vertical-align:middle;">2.</td>
														<td style="text-align: left !important;">Kapasitas Alat - Wheel Loader</td>
														<td colspan="2">
															<input type="text" id="kapasitas_alat_wl" name="kapasitas_alat_wl" class="form-control numberformat text-right" value=""  autocomplete="off">
														</td>
													</tr>
													<tr>
														<td style="text-align: left !important;">Faktor Efisiensi Alat - Wheel Loader</td>
														<td colspan="2">
															<input type="text" id="efisiensi_alat_wl" name="efisiensi_alat_wl" class="form-control numberformat text-right" value=""  autocomplete="off">
														</td>
													</tr>
													<tr>
														<td style="text-align: left !important;">Waktu Siklus (Muat, Tuang, Tunggu, dll)</td>
														<td colspan="2">
															<input type="text" id="waktu_siklus" name="waktu_siklus" class="form-control numberformat text-right" value=""  autocomplete="off">
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
														<th width="45%">URAIAN</th>
														<th width="50%">NILAI</th>                                 
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="text-center">1.</td>
														<td style="text-align: left !important;">Overhead</td>
														<td colspan="2">
															<input type="text" id="overhead" name="overhead" class="form-control rupiahformat text-right" value=""  autocomplete="off">
														</td>
													</tr>		
												</tbody>
											</table>    
										</div>
										<br />
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