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
                            <a href="<?php echo site_url('admin/productions');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Kontak</a></li>
                        <li><a>Kontak Form</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header"> 
                            <div class="">
                                <h3 >Kontak</h3>
                            </div>
                        </div>
                        <div class="panel-content">
                            
                            <form class="form-horizontal form-new" action="<?= site_url('kontak/submit_form');?>" method="POST">
                                <input type="hidden" name="id" value="<?= (isset($edit)) ? $edit['id'] : '' ;?>">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h5>Info Kontak</h5>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Tipe Kontak *</label>
                                            <div class="col-sm-2">
                                              <input type="checkbox" name="pelanggan" id="pelanggan" value="1" <?= (isset($edit) && $edit['pelanggan'] == 1) ? 'checked' : '' ;?> > Pelanggan
                                            </div>
                                            <div class="col-sm-2">
                                              <input type="checkbox" name="rekanan" id="rekanan" value="1" <?= (isset($edit) && $edit['rekanan'] == 1) ? 'checked' : '' ;?> > Rekanan
                                            </div>
                                            <div class="col-sm-2">
                                              <input type="checkbox" name="karyawan" id="karyawan" value="1" <?= (isset($edit) && $edit['karyawan'] == 1) ? 'checked' : '' ;?> > Karyawan
                                            </div>
                                            <div class="col-sm-2">
                                              <input type="checkbox" name="lain" id="lain" value="1" <?= (isset($edit) && $edit['lain'] == 1) ? 'checked' : '' ;?> > Lain-lain
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nama *</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="nama" value="<?= (isset($edit)) ? $edit['nama'] : '' ;?>" placeholder="Isikan Nama Kontak / Nama Perusahaan" required=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">No. Telp</label>
                                            <div class="col-sm-10">
                                              <input type="number" class="form-control input-sm" name="telepon" value="<?= (isset($edit)) ? $edit['telepon'] : '' ;?>" placeholder="Isikan No. Telp / No. Handphone"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Alamat</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="alamat" value="<?= (isset($edit)) ? $edit['alamat'] : '' ;?>" placeholder="Isikan Alamat Kontak / Alamat Perusahaan"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                              <input type="email" class="form-control input-sm" name="email" value="<?= (isset($edit)) ? $edit['email'] : '' ;?>" placeholder="Isikan Email Kontak / Email Perusahaan"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Fax</label>
                                            <div class="col-sm-10">
                                              <input type="number" class="form-control input-sm" name="fax" value="<?= (isset($edit)) ? $edit['fax'] : '' ;?>" placeholder="Fax"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">NPWP</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="npwp" value="<?= (isset($edit)) ? $edit['npwp'] : '' ;?>" placeholder="NPWP"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Alamat Penagihan</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="alamat_penagihan" value="<?= (isset($edit)) ? $edit['alamat_penagihan'] : '' ;?>" placeholder="Alamat Penagihan"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h5>Informasi Umum</h5>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nama Kontak</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="nama_kontak" value="<?= (isset($edit)) ? $edit['nama_kontak'] : '' ;?>" placeholder="Isikan Nama Kontak, Jika Nama Pada Info Kontak Adalah Perusahaan"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Identitas</label>
                                            <div class="col-sm-3">
                                              <select id="tipe_identitas" class="form-control form-select2" name="tipe_identitas">
                                                    <option value="">Pilih Satuan</option>
                                                    <option value="KTP" <?= (isset($edit) && $edit['tipe_identitas'] == 'KTP') ? 'selected' : '' ;?> >KTP</option>
                                                    <option value="PASSPORT" <?= (isset($edit) && $edit['tipe_identitas'] == 'PASSPORT') ? 'selected' : '' ;?> >PASSPORT</option>
                                                    <option value="SIM" <?= (isset($edit) && $edit['tipe_identitas'] == 'SIM') ? 'selected' : '' ;?> >SIM</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control input-sm" name="no_identitas" value="<?= (isset($edit)) ? $edit['no_identitas'] : '' ;?>" placeholder="No. Identitas"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nama Perusahaan</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="nama_perusahaan" value="<?= (isset($edit)) ? $edit['nama_perusahaan'] : '' ;?>" placeholder="Perusahaan Asal Nama Kontak (Informasi Umum)"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Posisi</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="posisi" value="<?= (isset($edit)) ? $edit['posisi'] : '' ;?>" placeholder="Posisi / Jabatan Nama Kontak (Informasi Umum)"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nama Kontak Logistik</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control input-sm" name="nama_kontak_logistik" value="<?= (isset($edit)) ? $edit['nama_kontak_logistik'] : '' ;?>" placeholder="Isikan Nama Kontak Logistik, Jika Nama Pada Info Kontak Adalah Perusahaan"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h5>Akun Mapping</h5>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Akun Masuk</label>
                                            <div class="col-sm-10">
                                              <select id="akun_masuk" class="form-control form-select2" name="akun_masuk">
                                                    <option value="">Pilih Akun</option>
                                                    <?php
                                                    if($akun){
                                                        foreach ($akun as $key => $ak) {
                                                            $selected = false;
                                                            if(isset($edit) && $edit['akun_masuk'] == $ak['id']){
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
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Akun Keluar</label>
                                            <div class="col-sm-10">
                                              <select id="akun_keluar" class="form-control form-select2" name="akun_keluar">
                                                    <option value="">Pilih Akun</option>
                                                    <?php
                                                    if($akun){
                                                        foreach ($akun as $key => $ak) {
                                                            $selected = false;
                                                            if(isset($edit) && $edit['akun_keluar'] == $ak['id']){
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
                                        </div>

                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-8 text-right">
                                        <a href="<?= site_url('admin/kontak');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
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
