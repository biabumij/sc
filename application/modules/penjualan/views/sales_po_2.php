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
                            <li><a href="<?php echo site_url('admin/penjualan');?>"> Penjualan</a></li>
                            <li><a href="<?php echo site_url('admin/penjualan');?>"> Sales Order</a></li>
                            <li><a>Sales Order Baru</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Sales Order Baru</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('penjualan/submit_sales_po');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Pelanggan</label>
                                            <select id="client" disabled="true" class="form-control form-select2" name="client_id" required="">
                                                <option value="" ></option>
                                                <?php
                                                if(!empty($clients)){
                                                    foreach ($clients as $row) {
                                                        ?>
                                                        <option value="<?php echo $row['id'];?>"><?php echo $row['nama'];?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" id="client" value="<?= $client_id; ?>" />
                                            <input type="hidden" name="client_id" value="<?= $client_id; ?>" />
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Tanggal Kontrak</label>
                                            <input type="text" class="form-control dtpicker" name="contract_date" required="" value="" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Nomor Kontrak</label>
                                            <input type="text" class="form-control" name="contract_number" required="" />
                                        </div>
										<div class="col-sm-10" style="padding-top:10px">
											<label>Alamat Pelanggan</label>
											<textarea id="client_address" class="form-control" rows="4" name="client_address" readonly=""></textarea>
										</div>
										<div class="col-sm-10" style="padding-top:10px">
											<label>Jenis Pekerjaan</label>
											<input type="text" class="form-control" name="jobs_type" required="" />
										</div>									
                                    </div>
                                    <div class="table-responsive" style="padding-top:10px">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
													<th width="30%">Penawaran</th>														
                                                    <th width="15%">Produk</th>
                                                    <th width="10%">Volume</th>
                                                    <th width="10%">Satuan</th>
                                                    <th width="15%">Harga Satuan</th>
                                                    <th width="15%">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>
                                                        <select name="penawaran_1" id="penawaran-1" class="form-control form-control form-select2" onchange="changeData(1)" required="">
                                                            <option value="">Pilih Penawaran</option>
                                                            <?php
                                                            if(!empty($penawaran)){
                                                                foreach ($penawaran as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['penawaran_id'];?>" data-product="<?= $row['product_id'];?>" data-satuan="<?= $row['satuan'];?>" data-harga="<?php echo $row['harga'];?>" data-tax="<?php echo $row['tax'];?>" data-nama_produk="<?= $row['nama_produk'];?>" data-satuan="<?= $row['satuan'];?>"><?php echo $row['nomor'];?> (<?php echo number_format($row['harga'],0,',','.');?>)</option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
														<input type="hidden" name="product_1" id="product-1" class="form-control input-sm text-center" onchange="changeData(1)" readonly=""/>
													<td>
                                                        <input type="text" name="nama_produk_1" id="nama_produk-1" class="form-control input-sm text-center" onchange="changeData(1)" readonly=""/>
                                                    </td> 
                                                    <td>
                                                        <input type="text" name="qty_1" id="qty-1" class="form-control numberformat input-sm text-center" onchange="changeData(1)" required="" />
                                                    </td>                                                   
                                                    <td>
                                                        <input type="text" name="measure_1" id="measure-1" class="form-control input-sm text-center" onchange="changeData(1)" required="" readonly=""/>
                                                    </td> 
													<td>
                                                        <input type="text" min="0" name="price_1" id="price-1" class="form-control numberformat tex-left input-sm text-right" onchange="changeData(1)" required="" readonly=""/>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total_1" id="total-1" class="form-control numberformat tex-left input-sm text-right" readonly="" />
                                                    </td>	
													<input type="hidden" name="tax_1" id="tax-1" class="form-control tex-left input-sm text-right" onchange="changeData(1)" readonly="" />   
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
										
										<div class="col-sm-12" style="padding-top:10px">
											<label>Memo</label>
											<textarea class="form-control" name="memo" data-required="false" id="about_text">

											</textarea>
										</div>                                      
										
                                        <div class="col-sm-4" style="padding-top:10px">
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]"  multiple="" />
                                            </div>
                                        </div>
                                        
										<!-- TOTAL -->
										
										<input type="hidden" id="sub-total" value="0">
										<input type="hidden" id="sub-total-val" name="sub_total" value="0">
											
										<?php
										if(!empty($taxs)){
											foreach ($taxs as $row) {
												?>                                                   
												<input type="hidden" id="tax-total-<?php echo $row['id'];?>" value="0">
												<input type="hidden" id="tax-val-<?php echo $row['id'];?>" name="tax_val_<?php echo $row['id'];?>" value="0">
												<?php
											}
										}
										?>
										
										<input type="hidden" id="total" value="0">
										<input type="hidden" id="total-val" name="total" value="0">
										<input type="hidden" name="total_product" id="total-product" value="1">
										
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/penjualan');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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

        $(document).ready(function(){
            $('#client').val(<?= $data['client_id'];?>).trigger('change');
        });

        function tambahData()
        {
            var number = parseInt($('#total-product').val()) + 1;
            var client_id = $("#client").val();

            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('penjualan/add_product_po'); ?>/"+Math.random(),
                data: {no:number, client:client_id},
                success : function(result){
                    $('#table-product tbody').append(result);
                    $('#total-product').val(parseInt(number));
                }
            });
        }

        $('#client').change(function(){
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('penjualan/get_client_address'); ?>/"+Math.random(),
                data: {id:$(this).val()},
                success : function(result){
                    $('#client_address').val(result);
                }
            });
        });

        function changeData(id)
        {
            var penawaran = $('#penawaran-'+id).val();
            var product_price = $('#penawaran-'+id+' option:selected').attr('data-price');
			
			var product = $('#penawaran-'+id+' option:selected').attr('data-product');
			var nama_produk = $('#penawaran-'+id+' option:selected').attr('data-nama_produk');
            var satuan = $('#penawaran-'+id+' option:selected').attr('data-satuan');
			var price = $('#penawaran-'+id+' option:selected').attr('data-harga');
			var tax = $('#penawaran-'+id+' option:selected').attr('data-tax');
            var qty = $('#qty-'+id).val();
            var total = $('#total-'+id).val();
            
            $('.tax-group').hide();
			
			$('#product-'+id).val(product);
			$('#nama_produk-'+id).val(nama_produk);
            $('#measure-'+id).val(satuan);
			$('#price-'+id).val(price);
			$('#tax-'+id).val(tax);
            
            if(penawaran == ''){
                alert('Pilih Penawaran Terlebih dahulu');
            }else {
                if(qty == '' || qty == 0){
                    $('#qty-'+id).val(1);
                    qty = $('#qty-'+id).val();
                }

                

                // $('#price-'+id).val(product_price);
                total = ( qty * price);
                $('#total-'+id).val(total);
                getTotal();

            }
        }

        function getTotal()
        {
            var total_product = $('#total-product').val();
            var tax_total = $('#tax-val').val();
            $('#sub-total-val').val(0);
            $('#tax-val-3').val(0);
            $('#tax-val-4').val(0);
            $('#tax-val-5').val(0);
            $('#tax-val-6').val(0);
            var sub_total = $('#sub-total-val').val();
            var tax_3 = $('#tax-val-3').val();
            var tax_4 = $('#tax-val-4').val();
            var tax_5 = $('#tax-val-5').val();
            var tax_6 = $('#tax-val-6').val();
            var total_total = $('#total-val').val();
            
            for (var i = 1; i <= total_product; i++) {
                
                // console.log()
                // console.log($('#total-'+i).val());
                var tax = $('#tax-'+i).val();
                if($('#total-'+i).val() > 0){
                    sub_total = parseInt(sub_total) + parseInt($('#total-'+i).val());
                }

                if(tax == 3){
                    $('#tax-total-3').show();
                    tax_3 = parseInt(tax_3) + (parseInt($('#total-'+i).val()) * 10) / 100 ;
                }
                if(tax == 4){
                    $('#tax-total-4').show();
                    tax_4 = parseInt(tax_4) + (parseInt($('#total-'+i).val()) * 0) / 100 ;
                }
                if(tax == 5){
                    $('#tax-total-5').show();
                    tax_5 = parseInt(tax_5) + (parseInt($('#total-'+i).val()) * 2) / 100 ;
                }
                if(tax == 6){
                    $('#tax-total-6').show();
                    tax_6 = parseInt(tax_6) + (parseInt($('#total-'+i).val()) * 11) / 100 ;
                }
                
            }
            $('#sub-total-val').val(sub_total);
            $('#sub-total').text($.number( sub_total, 2,',','.' ));


            $('#tax-val-3').val(tax_3);
            $('#tax-total-3 h5').text($.number( tax_3, 2,',','.' ));

            $('#tax-val-4').val(tax_4);
            $('#tax-total-4 h5').text($.number( tax_4, 2,',','.' ));

            $('#tax-val-5').val(tax_5);
            $('#tax-total-5 h5').text($.number( tax_5, 2,',','.' ));

            $('#tax-val-6').val(tax_6);
            $('#tax-total-6 h5').text($.number( tax_6, 2,',','.' ));

            total_total = parseInt(sub_total) + parseInt(tax_3) - parseInt(tax_4) - parseInt(tax_5) + parseInt(tax_6);
            $('#total-val').val(total_total);
            $('#total').text($.number( total_total, 2,',','.' ));
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
		
    </script>


</body>
</html>
