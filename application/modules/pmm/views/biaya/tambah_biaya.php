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
                                <a href="<?php echo site_url('admin/biaya');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Biaya</a></li>
                            <li><a>Tambah Biaya</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Tambah Biaya</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('pmm/biaya/submit_biaya');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>Bayar Dari</label>
                                            <select  class="form-control form-select2"  name="bayar_dari" required="">
                                                <option value="">Pilih Bayar Dari</option>
                                                <?php
                                                if(!empty($akun)){
                                                    foreach ($akun as $row) {
                                                        ?>
                                                        <option value="<?php echo $row['id'];?>"><?php echo $row['coa']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label>
                                                Penerima
                                                <button type="button" class="btn btn-xs btn-info" onclick="TambahPenerima()"><i class="fa fa-plus"></i> Tambah Penerima</button>
                                            </label>
                                            <select id="penerima"  class="form-control form-select2"  name="penerima" required="">
                                                <option value="">Pilih Penerima</option>
                                                <?php
                                                if(!empty($penerima)){
                                                    foreach ($penerima as $p) {
                                                        ?>
                                                        <option value="<?php echo $p['id'];?>"><?php echo $p['nama']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Tanggal Transaksi</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_transaksi" required="">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Nomor Transaksi</label>
                                            <input type="text" class="form-control" name="nomor_transaksi" required="">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Cara Pembayaran</label>
                                            <?php $pembayaran = ["Transfer","Cash","Cek&Giro"]; ?>
                                            <select name="cara_pembayaran" class="form-control">
                                                <option value="">Pilih Cara Pembayaran</option>
                                                <?php foreach($pembayaran as $p) : ?>
                                                <option value="<?= $p; ?>"><?= $p; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Akun Biaya</th>
                                                    <th>Deskripsi</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>
                                                        <select  class="form-control form-select2"  name="product_1" required="">
                                                            <option value="">Pilih Akun</option>
                                                            <?php
                                                            if(!empty($akun_biaya)){
                                                                foreach ($akun_biaya as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['coa_number'].' - '.$row['coa']; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="deskripsi_1">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control numberformat jumlah jumlah_input" onKeyup="getJumlah(this)" name="jumlah_1" id="jumlah_1">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>    
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-primary" onclick="tambahData()">
                                                <i class="fa fa-plus"></i> Tambah Data
                                            </button>
                                        </div>
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
                                        <div class="col-sm-8 form-horizontal">
                                            <div class="form-group">
                                                <h4 class="col-sm-7 control-label">Total</h4>
                                                <div class="col-sm-5 text-right">
                                                    <h4 class="numberformat" id="total_id" >0,00</h4>
                                                    <input type="hidden" id="total-val" name="total" value="0">
                                                    <input type="hidden" id="total_product" name="jumlah_biaya">
                                                </div>
                                            </div>
                                            <input type="hidden" name="total_product" id="total-product" value="1">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/biaya');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i>  Kirim</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>-->
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Tambah Penerima</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-penerima" class="form-horizontal" action="<?= site_url('pmm/biaya/tambah_penerima');?>" >
                        <div class="form-group">
                            <label class="col-sm-2">Nama</label>
                            <div class="col-sm-10">
                              <input type="text" name="nama" class="form-control input-sm" required="" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2">Tipe</label>
                            <div class="col-sm-3">
                              <input type="checkbox" name="pelanggan" id="pelanggan" value="1" > Pelanggan
                            </div>
                            <div class="col-sm-3">
                              <input type="checkbox" name="rekanan" id="rekanan" value="1"> Rekanan
                            </div>
                            <div class="col-sm-3">
                              <input type="checkbox" name="karyawan" id="karyawan" value="1" > Karyawan
                            </div>
                            <div class="col-sm-3">
                              <input type="checkbox" name="lain" id="lain" value="1" > Lain-lain
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2">Email</label>
                            <div class="col-sm-10">
                              <input type="email" name="email" class="form-control input-sm" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2">Alamat</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="alamat" rows="4"></textarea>
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

        function tambahData()
        {
            var number = parseInt($('#total-product').val()) + 1;

            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/biaya/add_akun'); ?>/"+Math.random(),
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


        function getJumlah(th){
            let input_jumlah = 0;
            $( ".jumlah_input" ).each(function() {
                input_jumlah += parseInt( $(this).val());
                $('#total_id').html(input_jumlah).number( true, 2,',','.' );
                $('#total_product').val(input_jumlah);
            });
        }

        function TambahPenerima()
        {
            $('#modalForm').modal('show');
        }

        $('#form-penerima').submit(function(event){
            $.ajax({
                type    : "POST",
                url     : $(this).attr('action')+"/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    if(result.output){
                        $("#form-penerima").trigger("reset");
                        $('#penerima').empty();
                        $('#penerima').select2({data:result.data});
                        $('#modalForm').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });
    </script>


</body>
</html>
