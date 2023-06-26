<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>
    <style type="text/css">
        .tab-pane {
            padding-top: 20px;
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
</head>
<body>
    <div class="wrap">

        <?php echo $this->Templates->PageHeader(); ?>

        <div class="page-body">
            <?php echo $this->Templates->LeftBar(); ?>
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin'); ?>">Dashboard</a></li>
                            <li><a><?php echo $row[0]->menu_name; ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <h3 class="section-subtitle">
                                    <?php echo $row[0]->menu_name; ?>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-plus"></i> Buat Baru <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?= site_url('rap/form_rap'); ?>">Analisa Harga Satuan</a></li>
                                            <li><a href="<?= site_url('rap/form_penyusutan'); ?>">Penyusutan</a></li>
                                        </ul>
                                    </div>
                                </h3>

                            </div>
                            <div class="panel-content">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#rap" aria-controls="rap" role="tab" data-toggle="tab">Analisa Harga Satuan</a></li>
                                    <li role="presentation"><a href="#penyusutan" aria-controls="penyusutan" role="tab" data-toggle="tab">Penyusutan</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="rap">
                                        <div class="col-sm-4">
											<input type="text" id="filter_date_rap" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_rap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" class="text-center">No</th>
														<th class="text-center">Tanggal</th>
                                                        <th class="text-center">Dibuat Oleh</th>
                                                        <th class="text-center">Dibuat Tanggal</th>
                                                        <th width="5%" class="text-center">Cetak</th>
                                                        <th width="5%" class="text-center">Edit</th>
                                                        <th width="5%" class="text-center">Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
                                    
                                    <div role="tabpanel" class="tab-pane" id="penyusutan">
                                        <form action="<?php echo site_url('laporan/cetak_penyusutan_rekap');?>" method="GET" target="_blank">
                                            <div class="col-sm-4">
                                                <input type="text" id="filter_date_penyusutan" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="text-left">
                                                    <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                                                </div>
                                            </div>
                                            <br />
                                            <br />
                                        </form>   								
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_penyusutan" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" class="text-center">No</th>
														<th class="text-center">Tanggal</th>
                                                        <th class="text-center">Produk</th>
                                                        <th class="text-center">Harga Perolehan</th>
                                                        <th class="text-center">Dibuat Oleh</th>
                                                        <th class="text-center">Dibuat Tanggal</th>
                                                        <th width="5%" class="text-center">Cetak</th>
                                                        <th width="5%" class="text-center">Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    
                                                </tfoot>
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

    <?php echo $this->Templates->Footer(); ?>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    
    <script type="text/javascript">
	$('input.numberformat').number( true, 2,',','.' );
        $('.dtpicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY'));
        });

        $('.dtpickerange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'DD-MM-YYYY'
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
		
		var table_rap = $('#table_rap').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('rap/table_rap'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_rap').val();
                }
            },
            responsive: true,
            paging : false,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "tanggal_rap"
                },
                {
					"data": "created_by"
				},
				{
					"data": "created_on"
				},
                {
                    "data": "print"
                },
                {
                    "data": "edit"
                },
                {
                    "data": "delete"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4, 5],
                    "className": 'text-center',
                }
            ],
        });

        $('.dtpickerange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_rap.ajax.reload();
        });

        function DeleteRAP(id) {
        bootbox.confirm("Anda yakin akan menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('rap/delete_rap'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_rap.ajax.reload();
                            bootbox.alert('Berhasil Menghapus RAP !!');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
            });
        }

        var table_penyusutan = $('#table_penyusutan').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('rap/table_penyusutan'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_penyusutan').val();
                }
            },
            responsive: true,
            paging : false,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "tanggal_penyusutan"
                },
                {
                    "data": "produk"
                },
                {
                    "data": "nilai_penyusutan"
                },
                {
					"data": "created_by"
				},
				{
					"data": "created_on"
				},
                {
                    "data": "print"
                },
                {
                    "data": "delete"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 4, 5, 6, 7],
                    "className": 'text-center',
                },
                {
                    "targets": [3],
                    "className": 'text-right',
                }
            ],
        });

        $('.dtpickerange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_penyusutan.ajax.reload();
        });

        function DeletePenyusutan(id) {
        bootbox.confirm("Anda yakin akan menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('rap/delete_penyusutan'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_penyusutan.ajax.reload();
                            bootbox.alert('Berhasil Menghapus Penyusutan !!');
                        } else if (result.err) {
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