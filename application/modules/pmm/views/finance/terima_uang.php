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
                                <a href="<?php echo site_url('admin/kas_&_bank');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Kas & Bank</a></li>
                            <li><a>Terima Uang</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="text-right">
                                    <h3 class="pull-left">Terima Uang</h3>
                                </div>
                            </div>
                            <br />
                            <br />
                            <div class="panel-content">
                            <form method="POST" action="<?php echo site_url('pmm/finance/submit_terima_uang');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Terima Dari</label>
                                        <select  class="form-control form-select2" name="terima_dari"  required="">
                                            <option value="">Pilih Terima</option>
                                            <?php
                                            if(!empty($akun)){
                                                foreach ($akun as $key => $mat) {
                                                    ?>
                                                    <option value="<?= $mat['id'];?>"><?= $mat['coa'];?> (<?= $mat['coa_number'] ?>)</option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Setor Ke</label>
                                        <select  class="form-control form-select2" name="setor_ke"  required="">
                                            <option value="">Pilih Setor</option>
                                            <?php
                                            if(!empty($akun)){
                                                foreach ($akun as $key => $mat) {
                                                    ?>
                                                    <option value="<?= $mat['id'];?>"><?= $mat['coa'];?> (<?= $mat['coa_number'] ?>)</option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Jumlah</label>
                                        <input type="text" class="form-control numberformat" name="jumlah" placeholder="Masukkan jumlah">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Nomor Transaksi</label>
                                        <input type="text" class="form-control" name="nomor_transaksi">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Tanggal Transaksi</label>
                                        <input type="date" class="form-control" name="tanggal_transaksi">
                                    </div>
                                </div>
                                <br>
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
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <a href="<?= site_url('admin/kas_&_bank');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                        <button type="submit" class="btn btn-success"><i class="fa fa-send"></i>  Kirim</button>
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