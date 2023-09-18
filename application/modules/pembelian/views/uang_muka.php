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
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Pembelian</a></li>
                            <li><a>Uang Muka</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Uang Muka</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('pembelian/submit_uang_muka');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <input type="text" name="purchase_order_id" value="<?= $row["id"] ?>">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="50%">Deskripsi</th>
                                                    <th width="50%">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: right !important;">Uang Muka</td>
                                                    <td style="text-align: right !important;"><input type="text" name="uang_muka" class="form-control numberformat text-right" value=""></td>
                                                    
                                                </tr>
                                            </tbody>
                                            <tfoot style="font-size:15px;">
                                                
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="text-center">
                                        <a href="<?= site_url('pmm/purchase_order/manage/'.$row["id"]);?>" class="btn btn-danger" style="margin-bottom:0px; width:100px; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Batal</a>
                                        <button type="submit" class="btn btn-success" style="width:100px; font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
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
    
    <script src="https://momentjs.com/downloads/moment.js"></script>

    <script type="text/javascript">
        
        $('.form-select2').select2();

        $('input.numberformat').number( true, 0,',','.' );
        $('.dtpicker').daterangepicker({
            //minDate: moment().add('d', 0).toDate(),
            singleDatePicker: true,
            showDropdowns : false,
            locale: {
              format: 'DD-MM-YYYY'
            },
            minDate: new Date()+0, 
			maxDate: new Date()+1
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

        function changeData(id)
        {
            var $total_invoice = $('#total_invoice').val();
			var total_bayar = $('#total_bayar').val();
            var pembayaran = $('#pembayaran').val();

            total_bayar_all = parseFloat($('#total_bayar').val()) + parseFloat($('#pembayaran').val());

            $('#total_bayar_all').val(total_bayar_all);
            $('#total_bayar_all').text($.number(total_bayar_all, 0,',','.' ));

            sisa_invoice = parseFloat($('#total_invoice').val()) - parseFloat($('#pembayaran').val()) -  parseFloat($('#total_bayar').val());

            $('#sisa_invoice').val(sisa_invoice);
            $('#sisa_invoice').text($.number(sisa_invoice, 0,',','.' ));
        }
    </script>


</body>
</html>
