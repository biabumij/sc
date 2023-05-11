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
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Pembelian</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Tagihan Pembelian</a></li>
                            <li><a>Pembayaran Pembelian</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Pembayaran Pembelian</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('pembelian/submit_pembayaran_penagihan_pembelian');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" name="penagihan_pembelian_id" value="<?= $pembayaran["id"] ?>">
                                    <input type="hidden" name="supplier_id" value="<?= $pembayaran['supplier_name'];?>">
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-2"><label>Pembayaran Melalui</label><span class="required" aria-required="true">*</span></div>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="bayar_dari" required="">
                                                <option value="">Pembayaran Melalui</option>
                                                <?php
                                                if(!empty($setor_bank)){
                                                    foreach ($setor_bank as $key => $sb) {
                                                        ?>
                                                        <option value="<?= $sb['id'];?>"><?= $sb['coa'];?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>    
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-2"><label>Penerima</label><span class="required" aria-required="true">*</span></div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" value="<?= $pembayaran["supplier_name"] ?>" name="supplier_name" readonly=""/>
                                        </div>
                                        <div class="col-sm-2"><label>Nomor Transaksi</label><span class="required" aria-required="true">*</span></div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="nomor_transaksi" required=""/>
                                        </div>
                                        
                                    </div>
                                    <br />
                                    <div class="row">
                                     <div class="col-sm-2"><label></label></div>
                                        <div class="col-sm-3">
                                            <label></label>
                                            
                                        </div>
                                        <div class="col-sm-2"><label>Cek Nomor</label></div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="cek_nomor"/>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-2"><label></label></div>
                                        <div class="col-sm-3">
                                            <label></label>
                                            
                                        </div>
                                        <div class="col-sm-2"><label>Tanggal Pembayaran</label><span class="required" aria-required="true">*</span></div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control dtpicker" name="tanggal_pembayaran" required="" />
                                        </div>
                                    </div>
                                    </br />
                                    <div class="row">
                                        <div class="col-sm-2"><label>Nomor Invoice</label><span class="required" aria-required="true">*</span></div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" value="<?= $pembayaran["nomor_invoice"] ?>" readonly=""/>
                                        </div>
                                    </div>
                                    </br />
                                    <div class="row">
                                        <div class="col-sm-2"><label></label></div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" value="<?= date('d/m/Y',strtotime($pembayaran["tanggal_invoice"])) ?>" readonly=""/>
                                        </div>
                                    </div>
                                    <br />
                                    <?php 
                                    $total_invoice = $dpp['total'] + $tax['total'];
                                    $sisa_tagihan = ($dpp['total'] + $tax['total']) - $total_bayar['total'] - $pembayaran['uang-muka'];
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th>Nilai Invoice</th>
                                                    <th>Pembayaran s/d. lalu</th>
                                                    <th>Pembayaran Saat Ini</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Sisa Invoice</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: right !important;"><input type="text" id="total_invoice" class="form-control numberformat text-center" onchange="changeData(1)" value="<?= intval($total_invoice) ?>" readonly=""></td>
                                                    <td style="text-align: right !important;"><input type="text" id="total_bayar" class="form-control numberformat text-center" onchange="changeData(1)" value="<?= intval($total_bayar['total']) ?>" readonly=""></td>
                                                    <td style="text-align: right !important;"><input type="text" name="pembayaran" id="pembayaran" class="form-control numberformat text-center" onchange="changeData(1)" value="<?= intval($sisa_tagihan) ?>"></td>
                                                    <td style="text-align: right !important;"><input type="text" id="total_bayar_all" class="form-control numberformat text-center" value="" readonly=""></td>
                                                    <td style="text-align: right !important;"><input type="text" id="sisa_invoice" class="form-control numberformat text-center" value="" readonly=""></td>
                                                    
                                                </tr>
                                            </tbody>
                                            <tfoot style="font-size:15px;">
                                                
                                            </tfoot>
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
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('pembelian/penagihan_pembelian_detail/'.$pembayaran["id"]);?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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
