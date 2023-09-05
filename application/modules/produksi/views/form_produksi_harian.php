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
                            <li><a>Produksi Harian Baru</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Produksi Harian</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('produksi/submit_produksi_harian');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <label>Tanggal Produksi Harian</label>
                                            <input type="text" class="form-control dtpicker" name="date_prod" required="" value="" />
                                        </div>
										<div class="col-sm-6">
                                            <label>Nomor Produksi Harian</label>
                                            <input type="text" class="form-control" name="no_prod" required="" value="<?= $this->pmm_model->GetNoProd();?>">
                                        </div>   
                                    <br />                               
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="25%">Nomor Kalibrasi</th>
                                                    <th width="20%" colspan="2">Jam Produksi</th>											
													<th width="10%">Durasi Produksi (Jam)</th>
                                                    <th width="10%">Jumlah Bucket</th>
													<th width="10%">Rata-rata Kapasitas Bucket (Ton)</th>
													<th width="10%">Pemakaian Bahan Baku (Ton)</th>
													<th width="10%">Kapasitas Produksi (Ton/Jam)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>
                                                        <select id="product-1" class="form-control form-select2" name="product_1" onchange="changeData(1)" required="">
                                                            <option value="">Pilih Kalibrasi</option>
															<?php
															foreach ($kalibrasi as $key => $kal) {
															?>
																<option value="<?php echo $kal['id']; ?>"><?php echo $kal['no_kalibrasi']; ?></option>
															<?php
															}
															?>
                                                        </select>
                                                    </td>
                                                    <td>
														<input type="timepicker" name="start_1" id="start-1" class="form-control timepicker text-center" required="" />
													<td>
                                                        <input type="timepicker" name="end_1" id="end-2" class="form-control timepicker text-center" required="" />
                                                    </td>
													<td>
                                                         <input type="number" step=".01" min="0" name="duration_1" id="duration-1" class="form-control  input-sm text-center numberformat"  required="" readonly="" />
                                                    </td>
                                                     <td>
                                                        <input type="number" step=".01" min="0" name="hours_1" id="hours-1" class="form-control input-sm text-center numberformat" onchange="changeData(1)" required="" />
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="day_1" id="day-1" class="form-control input-sm text-center numberformat" onchange="changeData(1)" required="" />
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="use_1" id="use-1" class="form-control input-sm text-center numberformat" readonly="" />
                                                    </td>
                                                    <td>
                                                        <input type="number" step=".01" min="0" name="capacity_1" id="capacity-1" class="form-control input-sm text-center numberformat" readonly="" />
                                                    </td>													
                                                </tr>
                                            </tbody>
                                        </table>    
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-primary" onclick="tambahData()" style="font-weight:bold; border-radius:10px;">
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
                url     : "<?php echo site_url('produksi/add_produksi_harian'); ?>/"+Math.random(),
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
            //var product_price = $('#product-'+id+' option:selected').attr('data-price');

            var satuan = $('#product-'+id+' option:selected').attr('data-satuan');
			
            var hours = $('#hours-'+id).val();
			var day = $('#day-'+id).val();
			//var total = $('#total-'+id).val();
			var use = $('#use-'+id).val();
			var capacity = $('#capacity-'+id).val(); 
			var duration = $('#duration-'+id).val();				
						
            
            //$('.tax-group').hide();

            $('#measure-'+id).val(satuan);
            
            if(product == ''){
                alert('Pilih Fraksi/Aggregat Terlebih dahulu');
            }else {
                if(hours == '' || hours == 0){
                    $('#hours-'+id).val(0);
                    hours = $('#hours-'+id).val();
                }
				if(day == '' || day == 0){
                    $('#day-'+id).val(0);
                    day = $('#day-'+id).val();
                }
				if(duration == '' || duration == 0){
                    $('#duration-'+id).val(0);
                    duration = $('#duration-'+id).val();
                }
				             
				use = ( hours * day );
                $('#use-'+id).val(use);

				capacity = ( use / duration );
                $('#capacity-'+id).val(capacity);

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
    </script>



</body>
</html>
