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
                                <a href="<?php echo site_url('admin/pembelian');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Pembelian</a></li>
                            <li><a>Tagihan Pembelian Baru</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="text-right">
                                    <h3 class="pull-left">Tagihan Pembelian</h3>
                                    <a href="<?php echo site_url('admin/pembelian');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('pmm/finance/submit_sales_po');?>" id="form-po">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Supplier</label>
                                            <select class="form-control form-select2" name="supplier_id" id="supplier_id" required="">
                                                <option value="">.. Pilih Supplier ..</option>
                                                <?php
                                                if(!empty($supplier)){
                                                    foreach ($supplier as $row) {
                                                        ?>
                                                        <option value="<?php echo $row['id'];?>" data-address="<?= $row["address"] ?>" ><?php echo $row['name'];?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Nomor Purchase Pembelian</label>
                                            <input type="text" class="form-control" name="nomor_pembelian">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Tanggal Invoice</label>
                                            <input type="date" class="form-control" name="tanggal_invoice">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Syarat Pembayaran</label>
                                            <select name="syarat_pembayaran" class="form-control" required="">
                                                <option selected disabled>Pilih Pembayaran</option>
                                                <option value="14 hari">14 hari</option>
                                                <option value="40 hari">40 hari</option>
                                                <option value="60 hari">60 hari</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Alamat Supplier</label>
                                            <input type="text" class="form-control" name="alamat_supplier" id="alamat_supplier">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Jenis</label>
                                            <input type="text" class="form-control" name="jenis">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Nomor Invoice</label>
                                            <input type="text" class="form-control" name="nomor_invoice">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Tgl Jatuh Tempo</label>
                                            <input type="date" class="form-control" name="tgl_jatuh_tempo">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Nomor Surat Jalan</label>
                                            <input type="text" class="form-control" name="nomor_jalan">
                                        </div>
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="22%">Produk</th>
                                                    <th width="7%">Qty</th>
                                                    <th width="10%">Satuan</th>
                                                    <th width="15%">Harga Satuan</th>
                                                    <th width="10%">Pajak</th>
                                                    <th width="20%">Jumlah</th>
                                                    <th width="5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>
                                                        <select id="product-1" class="form-control form-select2" name="product_1" onchange="changeData(1)" required="">
                                                            <option value="">.. Pilih Produk ..</option>
                                                            <?php
                                                            if(!empty($products)){
                                                                foreach ($products as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>" data-price="<?php echo $row['contract_price'];?>"><?php echo $row['product'];?></option>
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
                                                        <input type="text" name="measure_1" id="measure-1" class="form-control text-center input-sm" readonly="" />
                                                    </td>
                                                    <td>
                                                        <input type="text" name="price_1" id="price-1"  class="form-control numberformat tex-left input-sm text-right" onchange="changeData(1)"/>
                                                    </td>
                                                    <td>
                                                        <select id="tax-1" class="form-control form-select2" name="tax_1" onchange="changeData(1)" required="">
                                                            <option value="">.. Pilih Pajak ..</option>
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
                                                    <td>
                                                        <input type="text" name="total_1" id="total-1" class="form-control numberformat tex-left input-sm text-right" readonly="" />
                                                    </td>
                                                    <td><button id="delete-1" class="btn btn-xs btn-danger"><i class="fa fa-close"></i></button></td>
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
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Memo</label>
                                                <textarea class="form-control" name="memo" rows="3"></textarea>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name=""  multiple="" />
                                            </div> -->
                                        </div>
                                        <div class="col-sm-8 form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-7 control-label">Sub Total</label>
                                                <div class="col-sm-5 text-right">
                                                    <h5 id="sub-total" >0,00</h5>
                                                    <input type="hidden" id="sub-total-val" name="sub_total" value="0">
                                                </div>
                                            </div>
                                            <?php
                                            if(!empty($taxs)){
                                                foreach ($taxs as $row) {
                                                    ?>
                                                    <div id="tax-total-<?php echo $row['id'];?>" class="form-group tax-group" style="display: none;">
                                                        <label class="col-sm-7 control-label"><?php echo $row['tax_name'];?></label>
                                                        <div class="col-sm-5 text-right">
                                                            <h5 >0,00</h5>
                                                            <input type="hidden" id="tax-val-<?php echo $row['id'];?>" name="tax_val_<?php echo $row['id'];?>" value="0">
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            
                                            <div class="form-group">
                                                <h4 class="col-sm-7 control-label">Total</h4>
                                                <div class="col-sm-5 text-right">
                                                    <h4 id="total" >0,00</h4>
                                                    <input type="hidden" id="total-val" name="total" value="0">
                                                </div>
                                            </div>
                                            <input type="hidden" name="total_product" id="total-product" value="1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="#" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success">Buat Purchase Penjualan</button>
                                        </div>
                                    </div>
                                </form>

                                <br><br><br><br>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#menu1" aria-controls="menu1" role="tab" data-toggle="tab">Daftar Surat Jalan</a></li>
                                    <li role="presentation"><a href="#menu2" aria-controls="menu2" role="tab" data-toggle="tab">Daftar Pembayaran</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane" id="menu1"></div>
                                    <div role="tabpanel" class="tab-pane" id="menu2">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center" id="table-pembayaran" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Bayar Dari</th>
                                                        <th>Jumlah</th>
                                                        <th>Status Pembayaran</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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

        $('input.numberformat').number( true, 2,',','.' );
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

        $('#supplier_id').on('change', function() {
            
            var value = $(this).find(':selected').attr('data-address')
            $("#alamat_supplier").val(value);
        });

        function tambahData()
        {
            var number = parseInt($('#total-product').val()) + 1;

            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/finance/add_product_po'); ?>/"+Math.random(),
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
            var product_price = $('#product-'+id+' option:selected').attr('data-price');
            var qty = $('#qty-'+id).val();
            var price = $('#price-'+id).val();
            var tax = $('#tax-'+id).val();
            var total = $('#total-'+id).val();
            
            $('.tax-group').hide();
            
            if(product == ''){
                alert('Pilih Product Terlebih dahulu');
            }else {
                if(qty == '' || qty == 0){
                    $('#qty-'+id).val(1);
                    qty = $('#qty-'+id).val();
                }

                $('#price-'+id).val(product_price);
                total = ( qty * product_price);
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
            var sub_total = $('#sub-total-val').val();
            var tax_3 = $('#tax-val-3').val();
            var tax_4 = $('#tax-val-4').val();
            var tax_5 = $('#tax-val-5').val();
            var total_total = $('#total-val').val();
            
            for (var i = 1; i <= total_product; i++) {
                $('#measure-'+i).val('M3');
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
                
            }
            $('#sub-total-val').val(sub_total);
            $('#sub-total').text($.number( sub_total, 2,',','.' ));


            $('#tax-val-3').val(tax_3);
            $('#tax-total-3 h5').text($.number( tax_3, 2,',','.' ));

            $('#tax-val-4').val(tax_4);
            $('#tax-total-4 h5').text($.number( tax_4, 2,',','.' ));

            $('#tax-val-5').val(tax_5);
            $('#tax-total-5 h5').text($.number( tax_5, 2,',','.' ));

            total_total = parseInt(sub_total) + parseInt(tax_3) - parseInt(tax_4) - parseInt(tax_5);
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
