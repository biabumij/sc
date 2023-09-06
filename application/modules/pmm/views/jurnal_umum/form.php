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
                                <a href="<?php echo site_url('admin/biaya');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Jurnal Umum</a></li>
                            <li><a>Edit Jurnal Umum</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Edit Jurnal Umum</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered" id="main-table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Nomor Transaksi</th>
                                                <th>Tanggal Transaksi</th>
                                                <th>Total</th>
                                                <th>Total Debit</th>
                                                <th>Total Kredit</th>
                                                <th>Memo</th>
                                                <th>Lampiran</th>
                                                <th>Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                        </tbody>
                                        
                                    </table>
                                </div>   
                            </div>
                            <div class="panel-content">
                                <input type="hidden" id="id" name="id" value="<?= $data['id'] ?>">
                                <form id="form-product" class="form-horizontal" action="<?php echo site_url('pmm/jurnal_umum/product_process'); ?>" >
                                    <input type="hidden" id="jurnal_id" name="jurnal_id" value="<?= $data['id'] ?>">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <select  class="form-control form-select2"  name="product" required="">
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
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" name="deskripsi" placeholder="Deskripsi">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control numberformat" name="debit" placeholder="Debit">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control numberformat" name="kredit" placeholder="Kredit">
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-warning" id="btn-form" style="font-weight:bold; border-radius:10px;"><i class="fa fa-plus"></i> Tambah</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered" id="guest-table" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Akun Biaya</th>
                                                <th>Deskripsi</th>
                                                <th>Debit</th>
                                                <th>Kredit</th>
                                                <th>Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="<?= base_url('admin/jurnal_umum') ?>" class="btn btn-info" style="width:10%; font-weight:bold; border-radius:10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalFormDetail"  role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Form Edit Detail Jurnal Umum</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="jurnal_id" id="jurnal_id" value="<?= $data['id'] ?>">
                        <input type="hidden" id="form_id_jurnal" name="form_id_jurnal" class="form-control" required="" autocomplete="off" />
                        <div class="form-group">
                            <label>Akun</label>
                            <select id="akun" name="akun" class="form-control select2" required="">
                                <option value="">Pilih Akun</option>
                                <?php
                                    if(!empty($akun)){
                                        foreach ($akun as $row) {
                                            ?>
                                            <option value="<?php echo $row['id'];?>"><?php echo $row['coa_number'].' - '.$row['coa']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <input type="text" id="deskripsi" name="deskripsi" class="form-control" placeholder="Deskripsi" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Debit</label>
                            <input type="text" id="debit" name="debit" class="form-control numberformat" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Kredit</label>
                            <input type="text" id="kredit" name="kredit" class="form-control numberformat" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="btn-form" style="font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-weight:bold; border-radius:10px;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalFormMain"  role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Form Edit Jurnal Umum</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="jurnal_umum" id="id" value="<?= $data['id'] ?>">
                        <input type="hidden" id="form_id_jurnal_main" name="form_id_jurnal_main" class="form-control" required="" autocomplete="off" />
                        <div class="form-group">
                            <label>Nomor Transaksi</label>
                            <input type="text" id="nomor_transaksi" name="nomor_transaksi" class="form-control" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Transaksi</label>
                            <input type="text" id="tanggal_transaksi" name="tanggal_transaksi" class="form-control dtpicker" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Total</label>
                            <input type="text" id="total" name="total" class="form-control numberformat" required="" autocomplete="off" />
                        </div>
                       <!--<div class="form-group">
                            <label>Memo</label>
                            <input type="text" id="memo" name="memo" class="form-control" autocomplete="off" />
                        </div>-->
                        <div class="form-group">
                            <label>Total Debit</label>
                            <input type="text" id="total_debit" name="total_debit" class="form-control numberformat" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Total Kredit</label>
                            <input type="text" id="total_kredit" name="total_kredit" class="form-control numberformat" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="btn-form" style="font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Update Jurnal Umum</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-weight:bold; border-radius:10px;">Close</button>
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
        $('input.numberformat').number( true, 0,',','.' );
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

        var table = $('#main-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/jurnal_umum/main_table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.id = $('#id').val();
                }
            },
            columns: [
                { "data": "nomor_transaksi" },
                { "data": "tanggal_transaksi" },
                { "data": "total" },
                { "data": "total_debit" },
                { "data": "total_kredit" },
				{ "data": "memo" },
                { "data": "lampiran" },
                { "data": "actions" },
            ],
            responsive: true,
            searching: false,
            lengthChange: false,
            "columnDefs": [
                {
                    "targets": [0, 1, 3, 4, 5],
                    "className": 'text-center',
                },
                {
                    "targets": [2],
                    "className": 'text-right',
                }
            ],
        });

        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/jurnal_umum/table_detail');?>',
                type : 'POST',
                data: function ( d ) {
                    d.id = $('#id').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "akun" },
                { "data": "deskripsi" },
				{ "data": "debit" },
                { "data": "kredit" },
                { "data": "actions" },
            ],
            responsive: true,
            "columnDefs": [
                {
                    "targets": [0, 5],
                    "className": 'text-center',
                },
                {
                    "targets": [3, 4],
                    "className": 'text-right',
                }
            ],
        });

        function DeleteData(id)
        {
            bootbox.confirm("Apakah anda yakin untuk proses data ini ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.ajax({
                        type    : "POST",
                        url     : "<?php echo site_url('pmm/jurnal_umum/delete_detail'); ?>",
                        dataType : 'json',
                        data: {id:id},
                        success : function(result){
                            if(result.output){
                                table.ajax.reload();
                                bootbox.alert('Berhasil Menghapus!!');
                            }else if(result.err){
                                bootbox.alert(result.err);
                            }
                        }
                    });
                }
            });
        }

        $('#form-product').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : $(this).attr('action')+"/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $('#product').val('');
                        $('#deskripsi').val('');
                        $('#debit').val('');
                        $('#kredit').val('');
                        table.ajax.reload();
                        $('#product').focus();
                        // bootbox.alert('Succesfully!!!');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        function OpenFormMain(id='')
        {   
            
            $('#modalFormMain').modal('show');
            $('#id').val('');
            // table_detail.ajax.reload();
            if(id !== ''){
                $('#id').val(id);
                getDataMain(id);
            }
        }

        function getDataMain(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/jurnal_umum/get_jurnal_main'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#form_id_jurnal_main').val(result.output.id).trigger('change');
                        $('#nomor_transaksi').val(result.output.nomor_transaksi);
                        $('#tanggal_transaksi').val(result.output.tanggal_transaksi);
                        $('#total').val(result.output.total);
                        $('#total_debit').val(result.output.total_debit);
                        $('#total_kredit').val(result.output.total_kredit);
                        $('#memo').val(result.output.memo);
                        
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        $('#modalFormMain form').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/jurnal_umum/form_jurnal_main'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $("#modalFormMain form").trigger("reset");
                        $('#modalFormMain').modal('hide');
                        window.location.reload('');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        function OpenForm(id='')
        {   
            
            $('#modalFormDetail').modal('show');
            $('#id').val('');
            // table_detail.ajax.reload();
            if(id !== ''){
                $('#id').val(id);
                getData(id);
            }
        }

        function getData(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/jurnal_umum/get_jurnal'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#form_id_jurnal').val(result.output.id).trigger('change');
                        $('#akun').val(result.output.akun).trigger('change');
                        $('#deskripsi').val(result.output.deskripsi);
                        $('#debit').val(result.output.debit);
                        $('#kredit').val(result.output.kredit);
                        
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        $('#modalFormDetail form').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/jurnal_umum/form_jurnal'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $("#modalFormDetail form").trigger("reset");
                        $('#modalFormDetail').modal('hide');
                        window.location.reload('');
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
