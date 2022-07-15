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
                                        <div class="col-sm-3">
                                            <label>Rekanan</label>
                                            <input type="text" class="form-control" value="<?= $pembayaran["supplier_name"] ?>" name="supplier_name" required="" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Bayar Dari</label>
                                            <select class="form-control" name="bayar_dari" required="">
                                                <option value="">Bayar Dari</option>
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
                                        <div class="col-sm-3">
                                            <label>Tanggal Pembayaran</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_pembayaran" required="" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Nomor Transaksi</label>
                                            <input type="text" class="form-control" name="nomor_transaksi" required="" />
                                        </div>
                                    </div>
                                    <br />
                                    <br>
                                    <?php 
                                    $sisa_tagihan = $pembayaran['total'] - $total_bayar['total'];
                                    // echo $sisa_tagihan;

                                     ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal Invoice</th>
                                                    <th>Nomor Invoice</th>
                                                    <th>Total Invoice</th>
                                                    <th>Sisa Tagihan</th>
                                                    <th width="25%">Pembayaran saat ini</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?= date('d/m/Y',strtotime($pembayaran["tanggal_invoice"])) ?></td>
                                                    <td><?= $pembayaran["nomor_invoice"] ?></td>
                                                    <td style="text-align: right !important;"><?= number_format($pembayaran['total'],0,',','.'); ?></td>
                                                    <td style="text-align: right !important;"><?= number_format($sisa_tagihan,0,',','.'); ?></td>
                                                    <td style="text-align: right !important;"><input type="text" name="pembayaran" id="pembayaran" class="form-control numberformat text-center" ></td>
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
            }
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY'));
              // table.ajax.reload();
        });

        $('#pembayaran').keyup(function(){
            console.log($(this).val());
            $('#total-bayar').text($.number($(this).val(),2,',','.'));
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
