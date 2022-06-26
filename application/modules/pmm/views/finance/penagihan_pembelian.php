<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
        }
        .form-group{
            margin-bottom: 10px;
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
                                <a href="<?php echo site_url('admin/productions');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Penagihan Pembelian</a></li>
                            <li><a>Penagihan Pembelian</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="text-right">
                                    <h3 class="pull-left">Penagihan Pembelian</h3>
                                    <a href="<?php echo site_url('admin/penjualan');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                            </div>
                            <form id="form-po" action="<?= base_url("pmm/pembelian/submit_penagihan_pembelian") ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" name="surat_jalan" value="<?= $id;?>">
                            <div class="panel-content">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Supplier</label>
                                            <input type="text" class="form-control" name="supplier_name" value="<?= $po["supplier_name"] ?>" required="" readonly="" />
                                            <input type="hidden" name="supplier_id" value="<?= $po['supplier_id'];?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Nomor Purchase Pembelian</label>
                                            <input type="text" class="form-control" name="no_po" value="<?= $po['no_po'];?>" required="" readonly="" />
                                            <input type="hidden" name="purchase_order_id" value="<?= $po['id'];?>">

                                        </div>
                                        <div class="col-sm-3">
                                            <label>Tanggal Invoice</label>
                                            <input type="text" class="form-control dtpicker"  name="tanggal_invoice" required="" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Syarat Pembayaran</label>
                                            <select name="syarat_pembayaran" class="form-control" required="">
                                                <option value="">Pilih Pembayaran</option>
                                                <option value="14 hari">14 hari</option>
                                                <option value="40 hari">40 hari</option>
                                                <option value="60 hari">60 hari</option>
                                                <option value="90 hari">90 hari</option>
                                                <option value="120 hari">120 hari</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Alamat Supplier</label>
                                            <textarea class="form-control" name="alamat_pelanggan" required="" readonly=""><?= $po['supplier_address'];?></textarea>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Jenis</label>
                                            <input type="text" class="form-control" name="jenis" required="" />
                                        </div>
                                        
                                        <div class="col-sm-3">
                                            <label>Nomor Invoice</label>
                                            <input type="text" class="form-control" name="nomor_invoice" required="" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Tanggal Jatuh Tempo</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_jatuh_tempo" required="" />
                                        </div>
                                        
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="20%">Material</th>
                                                    <th width="10%">Volume</th>
                                                    <th width="10%">Satuan</th>
                                                    <th width="15%">Harga Satuan</th>
                                                    <th width="10%">Pajak</th>
                                                    <th width="25%">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sub_total = 0;
                                                    ?>
                                                <?php foreach($details as $key => $row) { ?>
                                                    <input type="hidden" name="receipt_material_id_<?= $key+1; ?>"   value="<?= $row['id'];?>" />

                                                    <input type="hidden" name="material_id_<?= $key+1; ?>" value="<?= $row['material_id'];?>"  />
                                                <tr>
                                                    <td><?= $key+1 ?>.</td>
                                                    <td>
                                                        <?= $row["material_name"]; ?>
                                                    </td>
                                                    <td>
                                                        <?= $this->filter->Rupiah($row["volume"]); ?>
                                                        <input type="hidden" min="0" name="qty_<?= $key+1; ?>" id="qty-<?= $key; ?>" value="<?= $row['volume'];?>" class="form-control input-sm text-center" required="" readonly />
                                                    </td>
                                                    <td>
                                                        <?= $row["measure"]; ?>
                                                        <input type="hidden" name="measure_<?= $key+1; ?>" id="measure-<?= $key; ?>" class="form-control text-center input-sm" value="<?= $row['measure'];?>" readonly=""  />
                                                    </td>
                                                    <td>
                                                        <?= $this->filter->Rupiah($row["price"]); ?>
                                                        <input type="hidden" name="price_<?= $key+1; ?>" id="price-<?= $key; ?>"  class="form-control tex-left input-sm text-right" value="<?= $row['price'];?>" readonly/>
                                                    </td>
                                                    <td>
                                                        <select id="tax-<?= $key; ?>" class="form-control form-select2" name="tax_<?= $key+1; ?>" onchange="changeData(<?= $key; ?>)" >
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
                                                        <input type="text" name="total_<?= $key+1; ?>" id="total-<?= $key; ?>" class="form-control numberformat text-right input-sm" value="<?= $this->filter->Rupiah($row["price"] * $row['volume']); ?>" readonly="" />
                                                    </td>
                                                   
                                                </tr>
                                                <?php
                                                    $sub_total += ($row["price"] * $row['volume']);
                                                } 
                                                ?>
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
                                                    <label id="sub-total" ><?= $this->filter->Rupiah($sub_total);?></label>
                                                    <input type="hidden" id="sub-total-val" name="sub_total" value="<?= $sub_total;?>">
                                                </div>
                                            </div>
                                            <?php
                                            if(!empty($taxs)){
                                                foreach ($taxs as $row) {
                                                    ?>
                                                    <div id="tax-total-<?php echo $row['id'];?>" class="form-group tax-group" style="display: none;">
                                                        <label class="col-sm-7 control-label"><?php echo $row['tax_name'];?></label>
                                                        <div class="col-sm-5 text-right">
                                                            <label class="label-show" ></label>
                                                            <input type="hidden" id="tax-val-<?php echo $row['id'];?>" name="tax_val_<?php echo $row['id'];?>" class="form-control numberformat text-right" value="0">
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }

                                            ?>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-7 control-label">Total</label>
                                                <div class="col-sm-5 text-right">
                                                    <label id="total" ><?= $this->filter->Rupiah($sub_total);?></label>
                                                    <input type="hidden" id="total-val" name="total" value="<?= $sub_total;?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-7 control-label">DP/ Muka</label>
                                                <div class="col-sm-5 text-right">
                                                    <input type="text" id="uang-muka" class="form-control numberformat text-right" name="uang_muka" >
                                                    <div id="dp-form" style="margin-top:10px;display: none;">
                                                        <select id="bayar_dari_dp" name="bayar_dari_dp" class="form-control" style="margin-bottom: 10px" >
                                                            <option value="">.. Bayar Dari ..</option>
                                                            <?php
                                                            if(!empty($setor_bank)){
                                                                foreach ($setor_bank as  $sb) {
                                                                    ?>
                                                                    <option value="<?= $sb['id'];?>"><?= $sb['coa'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>  
                                                        </select>
                                                        <input type="text" id="nomor_transaksi_dp" class="form-control" name="nomor_transaksi_dp" placeholder="Nomor Transaksi" >
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-7 control-label">Sisa Tagihan</label>
                                                <div class="col-sm-5 text-right">
                                                    <input type="text" id="total-tagihan" class="form-control numberformat text-right" name="total_tagihan" >
                                                </div>
                                            </div>
                                            <input type="hidden" name="total_product" id="total-product" value="<?= $key+1 ?>">
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/pembelian');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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

        function changeData(id)
        {
            var product = $('#product-'+id).val();
            // var product_price = $('#product-'+id).attr('data-price');
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

                // $('#price-'+id).val(price);
                total = ( qty * price);
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
                // $('#measure-'+i).val('M3');
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
            $('#tax-total-3  label.label-show').text($.number( tax_3, 2,',','.' ));

            $('#tax-val-4').val(tax_4);
            $('#tax-total-4 label.label-show').text($.number( tax_4, 2,',','.' ));

            $('#tax-val-5').val(tax_5);
            $('#tax-total-5 label.label-show').text($.number( tax_5, 2,',','.' ));

            total_total = parseInt(sub_total) + parseInt(tax_3) - parseInt(tax_4) - parseInt(tax_5);
            $('#total-val').val(total_total);
            $('#total').text($.number( total_total, 2,',','.' ));

            var uang_muka = $('#uang-muka').val();
            var total_tagihan = total_total - uang_muka;
            $("#total-tagihan").val(total_tagihan);
        }

        $('#uang-muka').keyup(function(){
            var val = $(this).val();
            var total = $('#total-val').val();

            var total_tagihan = total - val;

            $("#total-tagihan").val(total_tagihan);
            if(val > 0){
                $('#dp-form').show();
                $('#nomor_transaksi_dp').attr('required',true);
                $('#bayar_dari_dp').attr('required',true);
            }
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
    </script>


</body>
</html>
