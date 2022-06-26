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
                                <a href="<?php echo site_url('admin/penjualan');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Penjualan</a></li>
                            <li><a>Penawaran Penjualan Baru</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Penawaran Penjualan</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('penjualan/submit_penawaran_penjualan');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Pelanggan</label>
											<?php
											$product = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
											?>
                                            <select class="form-control form-select2" name="client_id" id="client_id" required="">
                                                <option value="">Pilih Pelanggan</option>
                                                <?php
                                                if(!empty($supplier)){
                                                    foreach ($supplier as $row) {
                                                        ?>
                                                        <option value="<?php echo $row['id'];?>" data-address="<?= $row["alamat"] ?>" data-idSupplier="<?= $row["id"] ?>"><?php echo $row['nama'];?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label text>Tanggal</label>
                                            <input type="date" class="form-control" name="tanggal" required="">
                                        </div>
                                        <div class="col-sm-10">
                                            <label >Alamat Pelanggan</label>
                                            <textarea class="form-control text" rows="4" name="alamat_client" id="alamat_client" required="" readonly=""></textarea>
                                        </div>
										 <div class="col-sm-10">
                                            <label>Nomor</label>
                                            <input type="text" class="form-control" name="nomor" required="">
                                        </div>
										<div class="col-sm-3">
                                            <label>Syarat Pembayaran</label>
                                            <select name="syarat_pembayaran" class="form-control form-select2" required="">
                                                <option value="">Pilih Pembayaran</option>
                                                <option value="14">14 hari</option>
                                                <option value="30">30 hari</option>
                                                <option value="60">60 hari</option>
                                                <option value="90">90 hari</option>
                                                <option value="120">120 hari</option>
                                                <option value="150">150 hari</option>
                                                <option value="180">180 hari</option>
                                            </select>
                                        </div>
										<div class="col-sm-7">
                                            <label>Perihal</label>
                                            <input type="text" class="form-control" name="perihal" required="">
                                        </div>
                                    </div>
									<br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed text-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="35%">Produk</th>
                                                    <th width="15%">Volume</th>
                                                    <th width="15%">Satuan</th>
                                                    <th width="15%">Harga Satuan</th>
													<th width="15%">Pajak</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>
                                                        <select id="product-1" class="form-control form-select2" name="product_1" onchange="changeData(1)" required="">
                                                            <option value="">Pilih Produk</option>
                                                            <?php
                                                            if(!empty($products)){
                                                                foreach ($products as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>" data-price="<?php echo $row['harga_jual'];?>"><?php echo $row['nama_produk'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" name="qty_1" id="qty-1" class="form-control input-sm text-center" onchange="changeData(1)" required="" />
                                                    </td>
                                                    <td>
                                                    <select id="measure-1" class="form-control form-select2" name="measure_1" required="">
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $meas) {
                                                                    ?>
                                                                    <option value="<?php echo $meas['id'];?>"><?php echo $meas['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="price_1" id="price-1"  class="form-control numberformat tex-left input-sm text-right" onchange="changeData(1)"/>
                                                    </td>
													<td>
                                                        <select id="tax-1" class="form-control form-select2" name="tax_1" onchange="changeData(1)" >
                                                            <option value="">Pilih Pajak</option>
                                                            <?php
                                                            if(!empty($taxs)){
                                                                foreach ($taxs as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['tax_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                        <input type="hidden" name="total_1" id="total-1" class="form-control numberformat"/>
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
									
									<!-- PERHITUNGAN TOTAL HARGA -->
									
									
									<input type="hidden" id="sub-total-val" name="sub_total" value="0">
                                         
									<?php
									if(!empty($taxs)){
										foreach ($taxs as $row) {
											?>
											
											<input type="hidden" id="tax-val-<?php echo $row['id'];?>" name="tax_val_<?php echo $row['id'];?>" value="0">
											<?php
										}
									}
									?>

                            
									
									<input type="hidden" id="total-val" name="total" value="0">
                                    <input type="hidden" name="total_product" id="total-product" value="1">
                                       
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Persyaratan Harga</label>
                                                <textarea class="form-control" name="site_description" data-required="false" id="about_text">

                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]"  multiple="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?php echo site_url('admin/penjualan');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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

    <script type="text/javascript">

        $('.form-select2').select2();

        $('input.numberformat').number( true, 0,',','.' );
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

        $('#client_id').on('change', function() {
            var value = $(this).find(':selected').attr('data-address')
            var idSupplier = $(this).find(':selected').attr('data-idSupplier')
            $("#alamat_client").val(value);


        });

        function tambahData()
        {
            var number = parseInt($('#total-product').val()) + 1;
            var namaSupplier = $("#supplier_id").val();

            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('penjualan/add_product_penjualan'); ?>/"+Math.random(),
                data: {no:number, nama:namaSupplier},
                success : function(result){
                    $('#table-product tbody').append(result);
                    $('#total-product').val(parseInt(number));
                }
            });
        }


        function changeData(id)
        {
            var product = $('#product-'+id).val();
            var product_price = $('#price-'+id).val();
            // var measure_id = $('#product-'+id+' option:selected').attr('data-measure-id');
            var qty = $('#qty-'+id).val();
            var price = $('#price-'+id).val();
            var tax = $('#tax-'+id).val();
            var total = $('#total-'+id).val();

            // console.log(measure_id);
            $('.tax-group').hide();

            if(product == ''){
                alert('Pilih Produk Terlebih dahulu');
            }else {
                if(qty == '' || qty == 0){
                    $('#qty-'+id).val(1);
                    qty = $('#qty-'+id).val();
                }

                // $('#price-'+id).val(product_price);
                total = ( qty * product_price);
                $('#total-'+id).val(total);
                // $('#measure-'+id).val(measure_id);
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
            $('#sub-total').text($.number( sub_total, 0,',','.' ));


            $('#tax-val-3').val(tax_3);
            $('#tax-total-3 h5').text($.number( tax_3, 0,',','.' ));

            $('#tax-val-4').val(tax_4);
            $('#tax-total-4 h5').text($.number( tax_4, 0,',','.' ));

            $('#tax-val-5').val(tax_5);
            $('#tax-total-5 h5').text($.number( tax_5, 0,',','.' ));

            $('#tax-val-6').val(tax_6);
            $('#tax-total-6 h5').text($.number( tax_6, 0,',','.' ));

            total_total = parseInt(sub_total) + parseInt(tax_3) - parseInt(tax_4) - parseInt(tax_5) + parseInt(tax_6);
            $('#total-val').val(total_total);
            $('#total').text($.number( total_total, 0,',','.' ));
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