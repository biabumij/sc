<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th{
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
                            <li><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Rencana Produksi</li>
                            <li><a>Rencana Cash Flow</a></li>
                            <li><a>Rencana Kerja (Cash Flow)</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Rencana Kerja (Cash Flow)</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rak/submit_rencana_cash_flow');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-3">
                                            <label>Tanggal</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_rencana_kerja" required="" value="" />
                                        </div>
									</div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="5%">NO.</th>
                                                    <th width="50%">URAIAN</th>
                                                    <th width="45%">NILAI</th>                               
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1.</td>
                                                    <td>Biaya Bahan</td>
													<td>
                                                    <input type="text" id="biaya_bahan" name="biaya_bahan" class="form-control rupiahformat text-right" value="0" required="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">2.</td>
                                                    <td>Biaya Alat</td>
													<td>
                                                    <input type="text" id="biaya_alat" name="biaya_alat" class="form-control rupiahformat text-right" value="0" required="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">3.</td>
                                                    <td>Biaya Bank</td>
													<td>
                                                    <input type="text" id="biaya_bank" name="biaya_bank" class="form-control rupiahformat text-right" value="0" required="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">4.</td>
                                                    <td>Overhead</td>
													<td>
                                                    <input type="text" id="overhead" name="overhead" class="form-control rupiahformat text-right" value="0" required="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">5.</td>
                                                    <td>Termin</td>
													<td>
                                                    <input type="text" id="termin" name="termin" class="form-control rupiahformat text-right" value="0" required="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">6.</td>
                                                    <td>Biaya Persiapan</td>
													<td>
                                                    <input type="text" id="biaya_persiapan" name="biaya_persiapan" class="form-control rupiahformat text-right" value="0" required="" autocomplete="off">
                                                    </td>
                                                </tr>
                                            </tbody>
                                
                                        </table>    
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]"  multiple="" />
                                            </div>
                                        </div>
                                    </div>
									<br />
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/rencana_cash_flow');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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

        $('input.numberformat').number(true, 2,',','.' );
        $('input.rupiahformat').number(true, 0,',','.' );

        $('.dtpicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns : true,
            locale: {
              format: 'DD-MM-YYYY'
            }
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY'));
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
