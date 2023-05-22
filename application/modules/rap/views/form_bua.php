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
                            <li><i class="fa fa-money" aria-hidden="true"></i>RAP</li>
                            
                            <li><a>RAP BUA</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">RAP BUA</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rap/submit_rap_bua');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-3">
                                            <label>Tanggal</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_rap_bua" required="" value="" />
                                        </div>
										<div class="col-sm-6">
                                            <label>Nomor RAP</label>
                                            <input type="text" class="form-control" name="nomor_rap_bua" required="" value="<?= $this->pmm_model->GetNoRapBUA();?>">
                                        </div> 
                                        <div class="col-sm-3">
                                            <label>Masa Kontrak</label>
                                            <input type="text" class="form-control" name="masa_kontrak" required="" value="">
                                        </div> 
									</div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="40%">Akun</th>
                                                    <th width="10%">Volume</th>
                                                    <th width="10%">Satuan</th>
                                                    <th width="20%">Harga Satuan</th>
                                                    <th width="20%">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>1.</td>
                                                <td>
                                                    <select id="coa-1" class="form-control form-control form-select2" name="coa_1" onchange="changeData(1)" required="">
                                                        <option value="">Pilih Akun</option>
                                                        <?php
                                                        if(!empty($coa)){
                                                            foreach ($coa as $row) {
                                                                ?>
                                                                <option value="<?php echo $row['id'];?>"><?php echo $row['coa'];?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="qty_1" id="qty-1" class="form-control input-sm numberformat text-center" onchange="changeData(1)"/>
                                                </td>
                                                <td>
                                                <select id="satuan-1" class="form-control form-select2" name="satuan_1">
                                                        <option value="">Pilih Satuan</option>
                                                        <?php
                                                        if(!empty($satuan)){
                                                            foreach ($satuan as $sat) {
                                                                ?>
                                                                <option value="<?php echo $sat['measure_name'];?>"><?php echo $sat['measure_name'];?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="harga_satuan_1" id="harga_satuan-1" class="form-control rupiahformat tex-left input-sm text-right" onchange="changeData(1)"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="jumlah_1" id="jumlah-1" class="form-control rupiahformat tex-left input-sm text-right"/>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <!--<tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-right">GRAND TOTAL</td>
                                                    <td>
                                                    <input type="text" id="sub-total-val" name="sub_total" value="0" class="form-control rupiahformat tex-left input-sm text-right" readonly="">
                                                    </td>
                                                </tr> 
                                            </tfoot>-->
                                        </table>    
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary" onclick="tambahData()">
                                            <i class="fa fa-plus"></i> Tambah Data
                                        </button>
                                    </div>
                                    
                                        <!-- TOTAL -->
                                        <input type="hidden" id="sub-total" value="0">
										<input type="hidden" id="total" value="0">
										<input type="hidden" id="total-val" name="total" value="0">
										<input type="hidden" name="total_product" id="total-product" value="1">
                                         <!-- TOTAL -->

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
                                            <a href="<?= site_url('admin/rap');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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
		$('input.rupiahformat').number( true, 0,',','.' );
        
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

		function tambahData()
        {
            var number = parseInt($('#total-product').val()) + 1;

            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('rap/add_coa'); ?>/"+Math.random(),
                data: {no:number},
                success : function(result){
                    $('#table-product tbody').append(result);
                    $('#total-product').val(parseInt(number));
                }
            });
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
		
		function changeData(id)
        {
			var qty = $('#qty-'+id).val();
			var harga_satuan = $('#harga_satuan-'+id).val();
            				
			jumlah = ( qty * harga_satuan );
            $('#jumlah-'+id).val(jumlah);
            getTotal();
        }

        function getTotal()
        {
            var total_product = $('#total-product').val();
            $('#sub-total-val').val(0);
            var sub_total = $('#sub-total-val').val();
            var total_total = $('#total-val').val();
            
            for (var i = 1; i <= total_product; i++) {
                if($('#jumlah-'+i).val() > 0){
                    sub_total = parseInt(sub_total) + parseInt($('#jumlah-'+i).val());
                }
            }
            $('#sub-total-val').val(sub_total);
            $('#sub-total').text($.number( sub_total, 0,',','.' ));

            total_total = parseInt(sub_total);
            $('#total-val').val(total_total);
            $('#total').text($.number( total_total, 0,',','.' ));
        }

        $(document).ready(function() {
            setTimeout(function(){
                $('#satuan-1').prop('selectedIndex', 6).trigger('change');
            }, 1000);
        });

    </script>


</body>
</html>
