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
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/productions');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Produk</a></li>
                            <li><a>Buat Produk</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 >Produk</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                
                                <form class="form-horizontal form-new" action="<?= site_url('produk/form_produk');?>" method="POST">
                                    <input type="hidden" name="id" value="<?= (isset($edit)) ? $edit['id'] : '' ;?>">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h5>Info Produk</h5>
                                            <hr />
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nama<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-10">
                                                <input type="text" class="form-control input-sm" name="nama_produk" value="<?= (isset($edit)) ? $edit['nama_produk'] : '' ;?>" />
                                                </div>
                                            </div>
                                            <!--<div class="form-group">
                                                <label class="col-sm-2 control-label">Code / SKU</label>
                                                <div class="col-sm-10">
                                                <input type="text" class="form-control input-sm" name="kode_produk" value="<?= (isset($edit)) ? $edit['kode_produk'] : '' ;?>" />
                                                </div>
                                            </div>-->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Kategori<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-10">
                                                    <select id="kategori" class="form-control form-select2" name="kategori_produk">
                                                        <option>Pilih Kategori</option>
                                                        <?php
                                                        if($kategori){
                                                            foreach ($kategori as $key => $kat) {
                                                                $selected = false;
                                                                if(isset($edit) && $edit['kategori_produk'] == $kat['id']){
                                                                    $selected = 'selected';
                                                                }
                                                                ?>
                                                                <option value="<?= $kat['id'];?>" <?= $selected;?> ><?= $kat['nama_kategori_produk'];?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Tipe Produk<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="bahanbaku" id="bahanbaku" value="1" <?= (isset($edit) && $edit['bahanbaku'] == 1) ? 'checked' : '' ;?> > Bahan Baku
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="betonreadymix" id="betonreadymix" value="1" <?= (isset($edit) && $edit['betonreadymix'] == 1) ? 'checked' : '' ;?> > Beton Ready Mix
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="aggregat" id="aggregat" value="1" <?= (isset($edit) && $edit['aggregat'] == 1) ? 'checked' : '' ;?> > Aggregat
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="jasa" id="jasa" value="1" <?= (isset($edit) && $edit['jasa'] == 1) ? 'checked' : '' ;?> > Jasa
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="peralatan" id="peralatan" value="1" <?= (isset($edit) && $edit['peralatan'] == 1) ? 'checked' : '' ;?> > Peralatan
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="bahanbakar" id="bahanbakar" value="1" <?= (isset($edit) && $edit['bahanbakar'] == 1) ? 'checked' : '' ;?> > Bahan Bakar
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="laboratorium" id="laboratorium" value="1" <?= (isset($edit) && $edit['laboratorium'] == 1) ? 'checked' : '' ;?> > Laboratorium
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="checkbox" name="asset" id="asset" value="1" <?= (isset($edit) && $edit['asset'] == 1) ? 'checked' : '' ;?> > Asset
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Unit/Satuan<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-10">
                                                    <select id="satuan" class="form-control form-select2" name="satuan">
                                                        <option>Pilih Satuan</option>
                                                        <?php
                                                        if($satuan){
                                                            foreach ($satuan as $key => $sat) {
                                                                $selected = false;
                                                                if(isset($edit) && $edit['satuan'] == $sat['id']){
                                                                    $selected = 'selected';
                                                                }
                                                                ?>
                                                                <option value="<?= $sat['id'];?>" <?= $selected;?> ><?= $sat['measure_name'];?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--<div class="form-group">
                                                <label class="col-sm-2 control-label">Deskripsi</label>
                                                <div class="col-sm-10">
                                                <input type="text" class="form-control input-sm" name="deskripsi"  value="<?= (isset($edit)) ? $edit['deskripsi'] : '' ;?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Tipe Produk</label>
                                                <div class="col-sm-10">
                                                    <select id="tipe_produk" class="form-control form-select2" name="tipe_produk">
                                                        <option value="SINGLE" <?= (isset($edit) && $edit['tipe_produk'] == 'SINGLE') ? 'selected' : '' ;?> >SINGLE</option>
                                                        <option value="BUNDLE" <?= (isset($edit) && $edit['tipe_produk'] == 'BUNDLE') ? 'selected' : '' ;?>>BUNDLE</option>
                                                    </select>
                                                </div>
                                            </div>-->
                                        </div>
                                    </div>
                                    <br />
                                    <!--<div class="row">
                                        <div class="col-sm-8">
                                            <h5>Harga & Pengaturan</h5>
                                            <hr />
                                            <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                <input type="checkbox" name="jual" id="jual" value="1" <?= (isset($edit) && $edit['jual'] == 1) ? 'checked' : '' ;?> > Jual
                                                </h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Harga Jual</label>
                                                        <input type="text" name="harga_jual" id="harga_jual" class="form-control input-sm numberformat" value="<?= (isset($edit)) ? $this->filter->Rupiah($edit['harga_jual']) : '' ;?>"  <?= (isset($edit) && $edit['jual'] == 1) ? '' : 'disabled' ;?> />
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Akun Jual</label>
                                                        <select id="akun_jual" class="form-control form-select2" name="akun_jual" <?= (isset($edit) && $edit['jual'] == 1) ? '' : 'disabled' ;?>>
                                                            <option>Pilih Akun</option>
                                                            <?php
                                                            if($akun){
                                                                foreach ($akun as $key => $ak) {
                                                                    $selected = false;
                                                                    if(isset($edit) && $edit['akun_jual'] == $ak['id']){
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="<?= $ak['id'];?>" <?= $selected;?>><?= '('.$ak['coa_number'].') '.$ak['coa'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Pajak</label>
                                                        <select id="pajak_jual" class="form-control form-select2" name="pajak_jual" <?= (isset($edit) && $edit['jual'] == 1) ? '' : 'disabled' ;?>>
                                                            <option>Pilih Pajak</option>
                                                            <?php
                                                            if($taxs){
                                                                foreach ($taxs as $key => $tax) {
                                                                    $selected = false;
                                                                    if(isset($edit) && $edit['pajak_jual'] == $tax['id']){
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="<?= $tax['id'];?>" <?= $selected;?> ><?= $tax['tax_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                <input type="checkbox" name="beli" id="beli" value="1" <?= (isset($edit) && $edit['beli'] == 1) ? 'checked' : '' ;?>> Beli
                                                </h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Harga Beli</label>
                                                        <input type="text" id="harga_beli" class="form-control input-sm numberformat" name="harga_beli" value="<?= (isset($edit)) ? $this->filter->Rupiah($edit['harga_beli']) : '' ;?>"  <?= (isset($edit) && $edit['beli'] == 1) ? '' : 'disabled' ;?> />
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Akun Beli</label>
                                                        <select id="akun_beli" class="form-control form-select2" name="akun_beli" disabled="">
                                                            <option>Pilih Akun</option>
                                                            <?php
                                                            if($akun){
                                                                foreach ($akun as $key => $ak) {
                                                                    $selected = false;
                                                                    if(isset($edit) && $edit['akun_beli'] == $ak['id']){
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="<?= $ak['id'];?>" <?= $selected;?>><?= '('.$ak['coa_number'].') '.$ak['coa'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label">Pajak</label>
                                                        <select id="pajak_beli" class="form-control form-select2" name="pajak_beli" disabled="">
                                                            <option>Pilih Pajak</option>
                                                            <?php
                                                            if($taxs){
                                                                foreach ($taxs as $key => $tax) {

                                                                    $selected = false;
                                                                    if(isset($edit) && $edit['pajak_beli'] == $tax['id']){
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="<?= $tax['id'];?>" <?= $selected;?>><?= $tax['tax_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/produk');?>" class="btn btn-danger" style="margin-bottom:0; width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
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
    

    <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Tambah Kategori</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-kategori-produk" class="form-horizontal" action="<?= site_url('produk/tambah_kategori_produk');?>" >
                        <div class="form-group">
                            <label class="col-sm-2">Nama</label>
                            <div class="col-sm-10">
                              <input type="text" name="nama_kategori_produk" class="form-control input-sm" required="" >
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-success btn-sm" id="btn-form"><i class="fa fa-check"></i> Tambah</button>
                            </div>  
                        </div>
                    </form>
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


        function TambahKategori()
        {
            $('#modalForm').modal('show');
        }

        $('#form-kategori-produk').submit(function(event){
            $.ajax({
                type    : "POST",
                url     : $(this).attr('action')+"/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    if(result.output){
                        $("#form-kategori-produk").trigger("reset");
                        $('#kategori').empty();
                        $('#kategori').select2({data:result.data});
                        $('#modalForm').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        $('#jual').click(function(){
            if($(this).prop("checked") == true){
                $("#harga_jual").prop('disabled',false);
                $("#akun_jual").prop('disabled',false);
                $("#pajak_jual").prop('disabled',false);
            }
            else if($(this).prop("checked") == false){
                $("#harga_jual").prop('disabled',true);
                $("#akun_jual").prop('disabled',true);
                $("#pajak_jual").prop('disabled',true);
            }
        });

        $('#beli').click(function(){
            if($(this).prop("checked") == true){
                $("#harga_beli").prop('disabled',false);
                $("#akun_beli").prop('disabled',false);
                $("#pajak_beli").prop('disabled',false);
            }
            else if($(this).prop("checked") == false){
                $("#harga_beli").prop('disabled',true);
                $("#akun_beli").prop('disabled',true);
                $("#pajak_beli").prop('disabled',true);
            }
        });
    </script>

</body>
</html>
