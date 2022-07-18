<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
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
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a >Kas & Bank</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">
                                Kas & Bank
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i> Buat Tranksaksi <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu text-right">
                                        <li><a href="<?= site_url("pmm/finance/transfer_uang") ?>">Transfer Uang</a></li>
                                        <li><a href="<?php echo site_url('pmm/finance/terima_uang');?>">Terima Uang</a></li>
                                    </ul>
                                </div>
                            </h3>
                            
                        </div>
                        <div class="panel-content">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Daftar Akun</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Transfer Uang</a></li>
                                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Diterima Uang</a></li>
                            </ul>
                         
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="guest-table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Akun</th>
                                                    <th>Nama</th>
                                                    <th>Saldo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                            
                                </div>

                                <div role="tabpanel" class="tab-pane" id="profile">
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-transfer" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor Transaksi</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal Transaksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="messages">
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-terima" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor Transaksi</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal Transaksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            
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

    

    <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Client Form</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Nama *</label>
                            <input type="text" id="coa" name="coa" class="form-control" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Contract </label>
                            <input type="text" id="contract" name="contract" class="form-control"  autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select id="status" name="status" class="form-control" required="">
                                <option value="PUBLISH">PUBLISH</option>
                                <option value="UNPUBLISH">UNPUBLISH</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="btn-form"><i class="fa fa-send"></i> Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    
    <script type="text/javascript">
        $('input#contract').number( true, 2,',','.' );
        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_cash_bank');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "coa_number" },
                { "data": "coa" },
                { "data": "saldo" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
            searching: true,
        });

        var transfer = $('#table-transfer').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_transfer');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "nomor" },
                { "data": "total" },
                { "data": "tanggal_transaksi" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
            searching: true,
        });

        var terima = $('#table-terima').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_terima');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "nomor" },
                { "data": "total" },
                { "data": "tanggal_transaksi" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
            searching: true,
        });


        function OpenForm(id='')
        {   
            
            $('#modalForm').modal('show');
            $('#id').val('');
            // table_detail.ajax.reload();
            if(id !== ''){
                $('#id').val(id);
                getData(id);
            }
        }

        $('#modalForm form').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/form_client'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $("#modalForm form").trigger("reset");
                        table.ajax.reload();
                        $('#modalForm').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        function getData(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/get_client'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#id').val(result.output.id);
                        $('#client_name').val(result.output.client_name);
                        $('#contract').val(result.output.contract);
                        $('#status').val(result.output.status);
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }


        function DeleteData(id)
        {
            bootbox.confirm("Are you sure to delete this data ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.ajax({
                        type    : "POST",
                        url     : "<?php echo site_url('pmm/delete_client'); ?>",
                        dataType : 'json',
                        data: {id:id},
                        success : function(result){
                            if(result.output){
                                table.ajax.reload();
                                bootbox.alert('Berhasil menghapus!!');
                            }else if(result.err){
                                bootbox.alert(result.err);
                            }
                        }
                    });
                }
            });
        }


    </script>

</body>
</html>