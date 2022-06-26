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
                                <a href="<?php echo site_url('admin/productions');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> PO Penjualan</a></li>
                            <li><a>Penagihan Penjualan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="text-right">
                                    <h3 class="pull-left">Penagihan Penjualan</h3>
                                    <a href="<?php echo site_url('admin/penjualan');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                            </div>
                            <form id="form-po" action="<?= base_url("pmm/finance/submit_penagihan_penjualan") ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="surat_jalan" value="<?= $id; ?>">
                            <input type="hidden" name="sales_po_id" value="<?= $sales['id'];?>">
                            <div class="panel-content">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Pelanggan</label>
                                            <input type="text" class="form-control" name="pelanggan" value="<?= $clients["client_name"] ?>" required="" />
                                            <input type="hidden" name="client_id" value="<?= $query['client_id'];?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Tanggal Kontrak</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_kontrak" required="" />
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Tanggal Invoice</label>
                                            <input type="text" class="form-control dtpicker"  name="tanggal_invoice" required="" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Nomor Invoice</label>
                                            <input type="text" class="form-control" value="<?= $noInvoice;?>" name="nomor_invoice" required="" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Alamat Pelanggan</label>
                                            <textarea class="form-control" name="alamat_pelanggan" required=""><?= $sales['client_address'];?></textarea>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Nomor Kontrak</label>
                                            <input type="text" class="form-control" value="<?= $sales['contract_number'];?>" name="nomor_kontrak" required="" />
                                        </div>
                                        
                                        <div class="col-sm-2">
                                            <label>Syarat Pembayaran</label>
                                            <select name="syarat_pembayaran" class="form-control" required="">
                                                <option value="">Pilih Pembayaran</option>
                                                <option value="14 hari">14 hari</option>
                                                <option value="40 hari">40 hari</option>
                                                <option value="60 hari">60 hari</option>
                                                <option value="90 hari">90 hari</option>
                                                <option value="120 hari">120 hari</option>
                                            </select>
                                            <!-- <input type="text" class="form-control" name="syarat_pembayaran" required="" /> -->
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Tanggal Jatuh Tempo</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_jatuh_tempo" required="" />
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Jenis Pekerjaan</label>
                                            <input type="text" class="form-control" value="<?= $sales['jobs_type'];?>" name="jenis_pekerjaan" required="" />
                                        </div>
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="27%">Produk</th>
                                                    <th width="10%">Volume</th>
                                                    <th width="7%">Satuan</th>
                                                    <th width="15%">Harga Satuan</th>
                                                    <th width="10%">Pajak</th>
                                                    <th width="20%">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($cekHarga as $key => $row) { ?>
                                                <input type="hidden" name="surat_jalan<?= $key+1 ?>" value="<?= $row["no_production"] ?>">
                                                <input type="hidden" name="production_id_<?= $key+1 ?>" value="<?= $row["idProduction"] ?>">
                                                <input type="hidden" name="product_id_<?= $key+1 ?>" value="<?= $row["product_id"] ?>">
                                                <tr>
                                                    <td><?= $key+1 ?>.</td>
                                                    <td>
                                                        
                                                        <input type="text" name="product_<?= $key+1; ?>" id="product-<?= $key ?>" class="form-control dataProduct" data-id="<?= $key; ?>" data-price="<?= $row["hargaProduk"] ?>" value="<?= $row["nameProduk"] ?>" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" name="qty_<?= $key+1; ?>" id="qty-<?= $key; ?>" value="<?= $row['volume'];?>" class="form-control input-sm text-center"  data-price="<?= $row["hargaProduk"] ?>" required="" readonly />
                                                    </td>
                                                    <td>
                                                        <input type="text" name="measure_<?= $key+1; ?>" id="measure-<?= $key; ?>" class="form-control text-center input-sm" readonly=""  />
                                                    </td>
                                                    <td>
                                                        <input type="text" name="price_<?= $key+1; ?>" id="price-<?= $key; ?>"  class="form-control numberformat tex-left input-sm text-right" readonly/>
                                                    </td>
                                                    <td>
                                                        <select id="tax-<?= $key; ?>" class="form-control form-select2" name="tax_<?= $key+1; ?>" onchange="changeData(<?= $key; ?>)">
                                                            <option value="">.. Pilih Pajak ..</option>
                                                            <?php
                                                            if(!empty($taxs)){
                                                                foreach ($taxs as $rows) {
                                                                    ?>
                                                                    <option value="<?php echo $rows['id'];?>"><?php echo $rows['tax_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total_<?= $key+1; ?>" id="total-<?= $key; ?>" class="form-control numberformat tex-left input-sm text-right" readonly="" />
                                                    </td>
                                                   
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>    
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Memo</label>
                                                <textarea class="form-control" name="memo" rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]"  multiple="" />
                                            </div>
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
                                            <input type="hidden" name="total_product" id="total-product" value="<?= $key+1 ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="#" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Buat Penagihan Penjualan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </form>
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


        $( ".dataProduct" ).each(function() {
            let id = $(this).attr("data-id");
            var product = $('#product-'+id).val();
            var product_price = $(this).attr('data-price');
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
        });


        function changeData(id)
        {
            var product = $('#product-'+id).val();
            var product_price = $('#product-'+id).attr('data-price');
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
            // alert('áaaa');

            var total_product = $('#total-product').val();
            $('#sub-total-val').val(0);
            $('#tax-val-3').val(0);
            $('#tax-val-4').val(0);
            $('#tax-val-5').val(0);
            var sub_total = $('#sub-total-val').val();
            var tax_3 = $('#tax-val-3').val();
            var tax_4 = $('#tax-val-4').val();
            var tax_5 = $('#tax-val-5').val();
            var total_total = $('#total-val').val();
            
            for (var i = 0; i <= total_product; i++) {
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
