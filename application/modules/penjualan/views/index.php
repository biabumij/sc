<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
    <style>
		.tab-pane {
            padding-top: 10px;
        }
        .select2-container--default .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
</head>


<body>
    <div class="wrap">

        <?php echo $this->Templates->PageHeader(); ?>
        <?php include 'lib.php'; ?>


        <div class="page-body">
            <?php echo $this->Templates->LeftBar(); ?>
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url(); ?>">Dashboard</a></li>
                            <li><a>Penjualan</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <h3 class="section-subtitle">
                                    Penjualan
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-plus"></i> Penjualan Baru <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?= site_url("penjualan/penawaran_penjualan") ?>">Penawaran Penjualan</a></li>
                                            <li><a href="<?php echo site_url('penjualan/sales_po'); ?>">Sales Order</a></li>
                                        </ul>
                                    </div>
                                </h3>
                            </div>
                            <div class="panel-content">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Penawaran Penjualan</a></li>
                                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Sales Order</a></li>
                                    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Pengiriman Penjualan</a></li>
                                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Tagihan Penjualan</a></li>
                                </ul>

                                <div class="tab-content">

                                    <!-- Penawaran Penjualan -->

                                    <div role="tabpanel" class="tab-pane active" id="home">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_penawaran" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
														<th class="text-center">Tanggal Penawaran</th>
														<th class="text-center">Nomor Penawaran</th>
                                                        <th class="text-center">Pelanggan</th>          
                                                        <th class="text-center">Total</th>
                                                        <th class="text-center">Status Penawaran</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- End Penawaran Penjualan -->

                                    <!-- Sales Order -->

                                    <div role="tabpanel" class="tab-pane" id="profile">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="guest-table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th class="text-center">Tanggal</th>
                                                        <th class="text-center">Nomor</th>
                                                        <th class="text-center">Pelanggan</th>
                                                        <th class="text-center">Jenis Pekerjaan</th>
														<th class="text-center">Vol. Sales Order</th>
														<th class="text-center">Kirim</th>
														<th class="text-center">Total Sales Order</th>
														<th class="text-center">Total Kirim</th>
                                                        <!--<th class="text-center">Nilai Pekerjaan</th>
                                                        <th class="text-center">PPN</th>
                                                        <th class="text-center">Total</th>-->
                                                        <th class="text-center">Status Sales Order</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Sales Order -->

                                    <!-- Pengiriman Penjualan -->

                                    <div role="tabpanel" class="tab-pane" id="messages">
                                        <?php
                                        $sales_po = $this->db->select('id,contract_number,client_id')->get_where('pmm_sales_po')->result_array();
                                        $suppliers = $this->db->order_by('id,nama')->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'pelanggan' => 1))->result_array();
                                        ?>
                                        <form id="form_production">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <input type="text" id="filter_date" name="filter_date" class="form-control dtpicker input-sm" value="" placeholder="Filter by Date" autocomplete="off">
                                                </div>
                                                <div class="col-sm-3">
                                                    <select id="filter_supplier_id" name="supplier_id" class="form-control select2">
                                                        <option value="">Pilih Pelanggan</option>
                                                        <?php
                                                        foreach ($suppliers as $key => $supplier) {
                                                        ?>
                                                            <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['nama']; ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <select id="sales_po_id" class="form-control select2" name="sales_po_id">
                                                        <option value="">Pilih PO</option>
                                                        <?php
                                                        if (!empty($sales_po)) {
                                                            foreach ($sales_po as $key => $po) {
                                                        ?>
                                                                <option value="<?= $po['id']; ?>" data-client-id="<?= $po['client_id'] ?>" disabled><?= $po['contract_number']; ?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="text-right">
                                                        <button type="button" id="btn_production" class="btn btn-success">Penagihan Penjualan</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" id="table-production" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>No</th>
                                                            <th class="text-center">Tanggal</th>
                                                            <th class="text-center">Nomor Produksi</th>
                                                            <th class="text-center">Nomor Sales Order</th>
                                                            <th class="text-center">Produk</th>
                                                            <th class="text-center">Pelanggan</th>
                                                            <th class="text-center">Volume</th>
															<th class="text-center">Satuan</th>
                                                            <th class="text-center">Status Pembayaran</th>
														</tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
													<tfoot>
													<th colspan="6" style="text-align:right">TOTAL</th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													</tfoot>
                                                </table>
                                            </div>

                                        </form>

                                    </div>

                                    <!-- End Pengiriman Penjualan -->

                                    <!-- Tagihan Penjualan -->

                                    <div role="tabpanel" class="tab-pane" id="settings">
										<div class="col-sm-3">
											<input type="text" id="filter_date_tagihan" name="filter_date" class="form-control dtpicker input-sm" value="" placeholder="Filter by Date" autocomplete="off">
                                        </div>
										<br />
										<br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table-penagihan" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th class="text-center">Tanggal Invoice</th>
                                                        <th class="text-center">Nomor</th>
                                                        <th class="text-center">Pelanggan</th>
                                                        <th class="text-center">Tanggal Sales Order</th>
                                                        <th class="text-center">Jenis Pekerjaan</th>
                                                        <th class="text-center">Total</th>
                                                        <th class="text-center">Pembayaran</th>
                                                        <th class="text-center">Sisa Tagihan</th>
                                                        <th class="text-center">Status Tagihan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- End Tagihan Penjualan -->

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

    <?php echo $this->Templates->Footer(); ?>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script type="text/javascript">
        $('input#contract').number(true, 2, ',', '.');

        var table_penawaran = $('#table_penawaran').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('penjualan/table_penawaran'); ?>',
                type: 'POST',
                data: function(d) {
                    d.supplier_id = $('#filter_supplier_id_x').val();
                }
            },
            columns: [{
                    "data": "no"
                },
				{
                    "data": "tanggal"
                },
				{
                    "data": "nomor"
                },
                {
                    "data": "nama"
                },
                {
                    "data": "total"
                },
                {
                    "data": "status"
                },
            ],
            "columnDefs": [
				{
                "targets": [0, 1, 5],
                "className": 'text-center',
				},
				{
                "targets": [4],
                "className": 'text-right',
				}
			],
            responsive: true,
        });
        $('#filter_supplier_id_x').on('select2:select', function(e) {
            var data = e.params.data;
            console.log(data);
            table_penawaran.ajax.reload
        });

        var table_po = $('#guest-table').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('penjualan/table_sales_po'); ?>',
                type: 'POST',
            },
            columns: [{
                    "data": "no"
                },
                {
                    "data": "contract_date"
                },
                {
                    "data": "nomor_link"
                },
                {
                    "data": "client_name"
                },
                {
                    "data": "jobs_type"
                },
				{
                    "data": "qty"
                },
				{
                    "data": "receipt"
                },
				{
                    "data": "jumlah_total"
                },
				{
                    "data": "total_receipt"
                },
                {
                    "data": "status"
                },
            ],
            "columnDefs": [
				{
                    "targets": [0, 1, 9],
                    "className": 'text-center',
                },{
                    "targets": [5, 6, 7, 8],
                    "className": 'text-right',
                }
            ],
            responsive: true,
        });

        var tableProduction = $('#table-production').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('penjualan/table_productions'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date').val();
                    d.supplier_id = $('#filter_supplier_id').val();
                    d.sales_po_id = $('#sales_po_id').val();
                }
            },
            columns: [{
                    "data": "checkbox"
                },
                {
                    "data": "no"
                },
                {
                    "data": "date_production"
                },
                {
                    "data": "no_production"
                },
                {
                    "data": "contract_number"
                },
                {
                    "data": "product"
                },
                {
                    "data": "client"
                },
				{
                    "data": "volume"
                },
                {
                    "data": "measure"
                },		
                {
                    "data": "status_payment"
                }
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
                {
                    "targets": [1, 2, 8, 9],
                    "orderable": false,
                    "className": 'text-center',
                },
                {
                    "targets": [7],
                    "orderable": false,
                    "className": 'text-right',
                }
            ],
			"footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                total = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 7 ).footer() ).html($.number( total));
            }
			
        });

        var table = $('#table-penagihan').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('penjualan/table_penagihan'); ?>',
                type: 'POST',
				data: function(d) {
                    d.filter_date = $('#filter_date_tagihan').val();
                }
            },
            columns: [{
                    "data": "no"
                },
                {
                    "data": "tanggal_invoice"
                },
                {
                    "data": "nomor_invoice"
                },
                {
                    "data": "nama_pelanggan"
                },
                {
                    "data": "tanggal_kontrak"
                },
                {
                    "data": "jenis_pekerjaan"
                },
                {
                    "data": "total_biaya"
                },
                {
                    "data": "pembayaran"
                },
                {
                    "data": "sisa_tagihan"
                },
                {
                    "data": "status"
                },

            ],
            "columnDefs": [{
                    "targets": [0, 1, 4, 5, 9],
                    "className": 'text-center',
                },
                {
                    "targets": [ 6, 7, 8],
                    "className": 'text-right',
                },
            ],
            responsive: true,
        });

        $('#filter_supplier_id').on('select2:select', function(e) {
            var data = e.params.data;
            console.log(data);
            tableProduction.ajax.reload();

            $('#sales_po_id option[data-client-id]').prop('disabled', true);
            $('#sales_po_id option[data-client-id="' + data.id + '"]').prop('disabled', false);
            $('#sales_po_id').select2('destroy');
            $('#sales_po_id').select2();
        });

        $('.dtpicker').daterangepicker({
            autoUpdateInput: false,
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
		$('#filter_date_tagihan').daterangepicker({
            autoUpdateInput: false,
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
		$('#filter_date_tagihan').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            table.ajax.reload();
        });

        $('#sales_po_id').change(function() {
            tableProduction.ajax.reload();
        });

        $('#btn_production').click(function() {
            var data_receipt = tableProduction.rows({
                selected: true
            }).data();
            var send_data = '';
            if (data_receipt.length > 0) {
                bootbox.confirm("Apakah anda yakin untuk proses data ini ?", function(result) {
                    // console.log('This was logged in the callback: ' + result); 
                    if (result) {
                        $.each(data_receipt, function(i, val) {
                            send_data += val.id + ',';
                        });

                        window.location.href = '<?php echo site_url('penjualan/penagihan_penjualan/'); ?>' + send_data;
                    }
                });
            } else {
                bootbox.alert('Tolong Pilih Terlebih dahulu');
            }
        });
    </script>

</body>

</html>