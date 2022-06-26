<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
</head>
<!-- <link type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css" rel="stylesheet" /> -->
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />

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
                        <li><a >Penjualan</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Penjualan</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i> Penjualan Baru <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?= site_url("pmm/finance/penawaran_penjualan") ?>">Penawaran Penjualan</a></li>
                                        <li><a href="<?php echo site_url('pmm/finance/sales_po');?>">Purchase Penjualan</a></li>
                                        <!-- <li><a href="#">Tagihan Penjualan</a></li> -->
                                      </ul>
                                </div>
                                <form method="GET" target="_blank" action="<?php echo site_url('pmm/reports/client_print');?>">
                                    <div class="col-sm-2">
                                        <!-- <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button> -->
                                    </div>  
                                </form>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Penawaran Penjualan</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Purchase Order Penjualan</a></li>
                                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Pengiriman Penjualan</a></li>
                                <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Tagihan Penjualan</a></li>
                            </ul>
                         
                                <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center" id="table_penawaran" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Client</th>
                                                        <th>Nomor</th>
                                                        <th>Tanggal</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
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
                                            <table class="table table-striped table-hover table-center" id="guest-table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Pelanggan</th>
                                                        <th>Jenis Pekerjaan</th>
                                                        <th>Nilai Pekerjaan</th>
                                                        <th>PPN</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="messages">
                                <br>
                                <?php
                                $sales_po = $this->db->select('id,contract_number')->get_where('pmm_sales_po')->result_array();
                                ?>
                                <form id="form_production">
                                   <div class="row">
                                       <div class="col-sm-3">
                                            <input type="text" id="filter_date" name="filter_date" class="form-control dtpicker" value=""  placeholder="Filter by Date" autocomplete="off">
                                        </div>
                                        <div class="col-sm-3">
                                            <select id="sales_po_id" class="form-control select2" name="sales_po_id">
                                                <option value="">.. Select PO ..</option>
                                                <?php
                                                if(!empty($sales_po)){
                                                    foreach ($sales_po as $key => $po) {
                                                        ?>
                                                        <option value="<?= $po['id'];?>"><?= $po['contract_number'];?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <button type="button" id="btn_production" class="btn btn-success">Penagihan Penjualan</button>
                                            </div>
                                        </div>
                                    </div> 
                                    <br>
                                    <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center" id="table-production" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Nomor Production</th>
                                                        <th>Nomor PO Penjualan</th>
                                                        <th>Product</th>
                                                        <th>Client</th>
                                                        <th>Volume</th>
                                                        <th>Status Payment</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                </tbody>
                                            </table>
                                    </div>
                                  
                                </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="settings">
                                <br>
                                <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center" id="table-penagihan" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor</th>
                                                        <th>Nama Pelanggan</th>
                                                        <th>Total</th>
                                                        <th>Pembayaran</th>
                                                        <th>Sisa Tagihan</th>
                                                        <th>Tanggal Jatuh Tempo</th>
                                                        <th>Status</th>

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
        <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
</div>
    

    
    <script type="text/javascript">
        var form_control = '';
    </script>
    
	<?php echo $this->Templates->Footer();?>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script type="text/javascript">
        // $('.checkbox').click(function(){
        //     console.log("sipp");
        //     if($(this).is(":checked")){
        //         alert("Checkbox is checked.");
        //     }
        //     else if($(this).is(":not(:checked)")){
        //         alert("Checkbox is unchecked.");
        //     }
        // });
        

        $('input#contract').number( true, 2,',','.' );

        var table_penawaran = $('#table_penawaran').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_penawaran');?>',
                type : 'POST',

            },
            columns: [
                { "data": "no" },
                { "data": "client_name" },
                { "data": "nomor" },
                { "data": "tanggal" },
                { "data": "total" },
                { "data": "status" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });
        var table = $('#table-penagihan').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_penagihan');?>',
                type : 'POST',
                
            },
            columns: [
                { "data": "no" },
                { "data": "nomor_invoice" },
                { "data": "nama_pelanggan" },
                { "data": "total_biaya" },
                { "data": "pembayaran" },
                { "data": "sisa_tagihan" },
                { "data": "tanggal_tempo" },
                { "data": "status" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });

        var table_po = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_sales_po');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "contract_date" },
                { "data": "nomor_link" },
                { "data": "client_name" },
                { "data": "jobs_type" },
                { "data": "jumlah_total" },
                { "data": "ppn" },
                { "data": "total" },
                { "data": "status" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });

        var tableProduction = $('#table-production').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_productions');?>',
                type : 'POST',
                data: function ( d ) {
                    d.filter_date = $('#filter_date').val();
                    d.sales_po_id = $('#sales_po_id').val();
                }
            },
            columns: [
                { "data": "checkbox"},
                { "data": "no" },
                { "data": "date_production" },
                { "data": "no_production" },
                { "data": "contract_number" },
                { "data": "product" },
                { "data": "client" },
                { "data": "volume" },
                { "data": "status_payment" }
            ],
            select: {
                style: 'multi'
            },
            responsive: true,
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                    "className": 'select-checkbox',
                },
            ],
        });

        $('.dtpicker').daterangepicker({
            autoUpdateInput : false,
            locale: {
              format: 'DD/MM/YYYY'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            showDropdowns: true,
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            tableProduction.ajax.reload();             
        });

        $('#sales_po_id').change(function(){
            tableProduction.ajax.reload();
        });


        $('#btn_production').click(function(){
            var data_receipt = tableProduction.rows({ selected: true } ).data();
            var send_data = '';
            if(data_receipt.length > 0){
                bootbox.confirm("Are you sure to process this data ?", function(result){ 
                    // console.log('This was logged in the callback: ' + result); 
                    if(result){
                        $.each(data_receipt,function(i,val){
                            send_data += val.id+',';
                        });

                        window.location.href = '<?php echo site_url('pmm/finance/penagihan_penjualan/');?>'+send_data;
                    }
                });
            }else {
                bootbox.alert('Tolong Pilih Terlebih dahulu');
            }
            
            
        });

        // alert("oke");
        


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
