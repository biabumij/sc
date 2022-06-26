<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

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
                            <li><a>Produksi Campuran Baru</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Produksi Campuran</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('produksi/submit_produksi_campuran');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Tanggal Produksi Campuran :</label>
                                        </div>
										<div class="col-sm-3">
                                            <input type="text" class="form-control dtpicker" name="date_prod" required="" value="" />
                                        </div>
										<br />
										<br />
										<div class="col-sm-3">
                                            <label>Nomor Produksi Campuran :</label>
                                        </div>
										<div class="col-sm-6">
                                            <input type="text" class="form-control" name="no_prod" required="" value="<?= $this->pmm_model->GetNoProdCampuran();?>">
                                        </div>										
                                    <br />                               
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="25%">Nama Komposisi</th>										
													<th width="20%">Uraian</th>
                                                    <th width="10%">Volume</th>
													<th width="10%">Satuan</th>
													<th width="10%">Convert</th>
													<th width="10%">Volume Convert</th>
													<th width="10%">Satuan Convert</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>
                                                        <select id="product-1" class="form-control form-select2" name="product_1" onchange="changeData(1)" required="">
                                                            <option value="">Pilih Komposisi Agregat</option>
															<?php
															foreach ($komposisi_agregat as $key => $ka) {
															?>
																<option value="<?php echo $ka['id']; ?>"><?php echo $ka['jobs_type']; ?></option>
															<?php
															}
															?>
                                                        </select>
                                                    </td>
													<td>
                                                         <input type="text" name="uraian_1" id="uraian-1" class="form-control  input-sm text-center"  required=""/>
                                                    </td>
                                                    <td>
                                                        <input type="number" step=".01" min="0" name="volume_1" id="volume-1" class="form-control input-sm text-center numberformat" onchange="changeData(1)" required="" />
                                                    </td>
													<td>
                                                        <select id="measure-1" class="form-control form-select2" name="measure_1" required="" >
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $ms) {
                                                                    ?>
                                                                    <option value="<?php echo $ms['measure_name'];?>"><?php echo $ms['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="convert_1" id="convert-1" class="form-control input-sm text-center numberformat" onchange="changeData(1)" required="" />
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_convert_1" id="volume_convert-1" class="form-control input-sm text-center numberformat" onchange="changeData(1)" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_convert-1" class="form-control form-select2" name="measure_convert_1" required="" >
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $ms) {
                                                                    ?>
                                                                    <option value="<?php echo $ms['measure_name'];?>"><?php echo $ms['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>													
                                                </tr>
                                            </tbody>
                                        </table>    
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-primary" onclick="tambahData()">
                                                <i class="fa fa-plus"></i> Tambah Data
                                            </button>
                                        </div>
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
                                        <div class="col-sm-8 form-horizontal">
                                            <input type="hidden" name="total_product" id="total-product" value="1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/produksi');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Kirim</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
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
	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>    

    <script type="text/javascript">
		window.timepickerChange = function(){

            console.log('timepickerChange');

            $('#table-product tbody tr').each(function(e){
                let start = $(this).find('td:nth-child(3) input').val();
                let end = $(this).find('td:nth-child(4) input').val();

                // console.log(start);
                // console.log(end);

                if (start.trim().length == 0 || end.trim().length == 0){
                    return;
                }

                let startTemp = start.split(':');
                let endTemp = end.split(':');

                if (parseInt(startTemp[0]) > parseInt(endTemp[0])){
                    alert('Waktu mulai tidak boleh lebih besar dari waktu selesai');
                    return;
                }

                let jam = parseInt(endTemp[0]) - parseInt(startTemp[0]);

                let menit = Math.abs(parseInt(startTemp[1]) - parseInt(endTemp[1])) / 60;

                if (parseInt(startTemp[1]) > parseInt(endTemp[1])){
                    jam--;
                }

                $(this).find('td:nth-child(5) input').val(jam + menit);
            })

        };
        
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
	
		$(document).ready(function(){
            // $('input.timepicker').timepicker({});

            $('.timepicker').timepicker({
                timeFormat: 'HH:mm',
                interval: 30,
                //defaultTime: '08',
                use24hours: true,
                dynamic: true,
                dropdown: true,
                scrollbar: true,
                change: timepickerChange
            });
		});

        function tambahData()
        {
            var number = parseInt($('#total-product').val()) + 1;

            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('produksi/add_produksi_campuran'); ?>/"+Math.random(),
                data: {no:number},
                success : function(result){
                    $('#table-product tbody').append(result);
                    $('#total-product').val(parseInt(number));
                }
            });
        }

        function changeData(id)
        {
            var product = $('#product-'+id).val();
            var volume = $('#volume-'+id).val();
			var convert = $('#convert-'+id).val();
			var volume_convert = $('#volume_convert-'+id).val();				
			
            if(product == ''){
                alert('Pilih Komposisi/Aggregat Terlebih dahulu');
            }else {
                if(volume == '' || volume == 0){
                    $('#volume-'+id).val(0);
                    volume = $('#volume-'+id).val();
                }
				if(convert == '' || convert == 0){
                    $('#convert-'+id).val(1);
                    convert = $('#convert-'+id).val();
                }
				if(volume_convert == '' || volume_convert == 0){
                    $('#volume_convert-'+id).val(0);
                    volume_convert = $('#volume_convert-'+id).val();
                }
				             
				volume_convert = ( volume * convert );
                $('#volume_convert-'+id).val(volume_convert);
				
				timepickerChange();

            }
        }


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
                $('#product-1').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure-1').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_convert-1').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
    </script>



</body>
</html>
