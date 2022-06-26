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
                                <a href="<?php echo site_url('admin/produksi');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Produksi Jadi</a></li>
                            <li><a>Produksi Jadi</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Produksi Jadi</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('produksi/submit_produksi_jadi');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" name="id" value="<?= (isset($edit)) ? $edit['id'] : '' ;?>">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Nomor Produksi jadi</label>
                                            <input type="text" class="form-control" name="no_produksi_jadi" required="" value="<?= $no_produksi_jadi;?>" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Tanggal</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal" required="" value="<?= (isset($edit)) ? $edit['tanggal'] : '' ;?>" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Produk</label>
                                            <select id="produk" class="form-control form-select2" name="produk" required="">
                                                <option value="">Pilih Produk</option>
                                                <?php
                                                if(!empty($produk)){
                                                    foreach ($produk as $row) {
                                                        $selected = false;
                                                        if(isset($edit) && $edit['produk'] == $row['id']){
                                                            $selected = 'selected';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $row['id'];?>" data-satuan="<?= $row['satuan'];?>" <?= $selected;?>><?php echo $row['nama_produk'].' ('.$row['nama_kategori_produk'].')';?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Quantity</label>
                                            <input type="text" class="form-control numberformat" name="qty" required="" value="<?= (isset($edit)) ? $this->filter->Rupiah($edit['qty']) : '' ;?>" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Satuan</label>
                                            <select id="satuan" class="form-control form-select2" name="satuan" required="">
                                                <option value="">Pilih Satuan</option>
                                                <?php
                                                if(!empty($satuan)){
                                                    foreach ($satuan as $sat) {
                                                        $selected = false;
                                                        if(isset($edit) && $edit['satuan'] == $sat['id']){
                                                            $selected = 'selected';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $sat['id'];?>" <?= $selected;?>><?php echo $sat['measure_name'];?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Berat Isi</label>
                                            <input type="text" class="form-control numberformat" name="berat_isi" required="" value="<?= (isset($edit)) ? $this->filter->Rupiah($edit['berat_isi']) : '' ;?>" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Convert Berat Isi</label>
                                            <input type="text" class="form-control numberformat" name="convert_berat_isi" value="<?= (isset($edit)) ? $this->filter->Rupiah($edit['convert_berat_isi']) : '' ;?>" required="" />
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Memo</label>
                                            <textarea class="form-control" name="memo" rows="3"><?= (isset($edit)) ? $edit['memo'] : '' ;?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]"  multiple="" />
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/produksi');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Kirim</button>
                                        </div>
                                    </div>
                                </form>
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

        $('input.numberformat').number( true, 2,',','.' );
        $('#produk').change(function(){
            var satuan = $('#produk option:selected').attr('data-satuan');
            $('#satuan').val(satuan).trigger('change');
        });
    </script>


</body>
</html>
