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
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><a href="<?php echo site_url('admin/form#perubahan_sistem');?>">Form</a></li>
                            <li><a>Buat Form Perubahan Sistem</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Perubahan Sistem</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('form/submit_perubahan_sistem');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Nomor</label>
                                            <input type="text" class="form-control" name="nomor" required="" value="<?= $this->pmm_model->GetNoPerubahanSistem();?>">
                                        </div>
                                        <br /><br /><br /><br />
                                        <div class="col-sm-6">
                                            <label>Yang Mengajukan</label>
                                            <select name="nama" class="form-control input-sm" required="">
                                                <option value="">Pilih Nama</option>
                                                <?php
                                                if (!empty($nama)) {
                                                    foreach ($nama as $row) {
                                                ?>
                                                        <option value="<?php echo $row['admin_id']; ?>"><?php echo $row['admin_name']; ?> (<?php echo $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$row['admin_group_id']),'admin_group_name');?>)</option>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <br /><br /><br /><br />
										<div class="col-sm-6">
                                            <label>Tanggal</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal" required="" value="" placeholder="Tanggal">
                                        </div>
                                        <br /><br /><br /><br />
                                        <div class="col-sm-12">
                                            <label>Sifat Permintaan</label><br />
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="sangat_penting" value="1"> Sangat Penting
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="penting" value="1"> Penting
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="cukup_penting" value="1"> Cukup Penting
                                            </div>
                                        </div>
                                        <br /><br /><br /><br />
                                        <div class="col-sm-12">
                                            <label>Jenis Permintaan</label><br />
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="perbaikan" value="1"> Perbaikan
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="penambahan_fitur_baru" value="1"> Penambahan Fitur Baru
                                            </div>
                                            <br /><br />
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="pengurangan_fitur_lama" value="1"> Pengurangan Fitur Lama
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="master_data_baru" value="1"> Master Data Baru
                                            </div>
                                            <br /><br />
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="penambahan_data" value="1"> Penambahan Data
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="checkbox" name="lain_lain" value="1"> Lain - Lain : Perubahan Data
                                            </div>
                                            <br /><br />
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <label>Deskripsi Permintaan</label>
                                            <textarea class="form-control" name="deskripsi" data-required="false" id="about_text">

                                            </textarea>
                                            <br /><br />
                                        </div>
                                        <div class="col-sm-12">
                                            <label>Catatan</label>
                                            <textarea class="form-control" name="memo" data-required="false" id="about_text">

                                            </textarea>
                                        </div>
									</div>
                                    <br /><br />
                                    <div class="text-center">
                                        <a href="<?= site_url('admin/form');?>" class="btn btn-danger" style="margin-bottom:0px; width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Batal</a>
                                        <button type="submit" class="btn btn-success" style="width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button> 
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

        tinymce.init({
          selector: 'textarea#about_text',
          height: 200,
          menubar: false,
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
