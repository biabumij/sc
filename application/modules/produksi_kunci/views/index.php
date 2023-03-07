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
                                            <li><a href="<?= site_url('produksi/form_hpp_bahan_baku'); ?>">HPP Pergerakan Bahan Baku</a></li>
                                            <li><a href="<?= site_url('produksi/form_hpp'); ?>">HPP Pergerakan Bahan Jadi</a></li>
                                            <li><a href="<?= site_url('produksi/form_akumulasi_bahan_baku'); ?>">Akumulasi Pergerakan Bahan Baku</a></li>
                                            <li><a href="<?= site_url('produksi/form_akumulasi'); ?>">Akumulasi Pergerakan Bahan Jadi</a></li>
                                        </ul>
                                    </div>
                                </h3>

                            </div>
                            <div class="panel-content">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#hpp_bahan_baku" aria-controls="hpp_bahan_baku" role="tab" data-toggle="tab">HPP Pergerakan Bahan Baku</a>
                                    <li role="presentation"><a href="#hpp" aria-controls="hpp" role="tab" data-toggle="tab">HPP Pergerakan Bahan Jadi</a></li>
                                    <li role="presentation"><a href="#akumulasi_bahan_baku" aria-controls="akumulasi_bahan_baku" role="tab" data-toggle="tab">Akumulasi Pergerakan Bahan Baku</a>
                                    <li role="presentation"><a href="#akumulasi" aria-controls="akumulasi" role="tab" data-toggle="tab">Akumulasi Pergerakan Bahan Jadi</a>
                                </ul>

                                <div class="tab-content">

                                    <!-- Table HPP Bahan Baku -->
									
                                    <div role="tabpanel" class="tab-pane active" class="tab-pane" id="hpp_bahan_baku">
										<div class="col-sm-4">
											<input type="text" id="filter_date_hpp_bahan_baku" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_hpp_bahan_baku" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
														<th>Tanggal</th>
														<th>Boulder</th>
                                                        <th>BBM</th>
														<th>Status</th>
                                                        <th>Dibuat Oleh</th>
                                                        <th>Dibuat Tanggal</th>
                                                        <th>Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Table HPP Bahan Baku -->

                                    <!-- Table HPP Pergerakan Bahan Jadi -->
									
                                    <div role="tabpanel" class="tab-pane" id="hpp">
										<div class="col-sm-4">
											<input type="text" id="filter_date_hpp" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_hpp" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
														<th>Tanggal</th>
														<th>Abu Batu</th>
                                                        <th>Batu 0,5 - 10</th>
														<th>Batu 10 - 20</th>
                                                        <th>Batu 20 - 30</th>
														<th>Status</th>
                                                        <th>Dibuat Oleh</th>
                                                        <th>Dibuat Tanggal</th>
                                                        <th>Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Table HPP Pergerakan Bahan Jadi -->

                                    <!-- Akumulasi Bahan Baku -->
									
                                    <div role="tabpanel" class="tab-pane" id="akumulasi_bahan_baku">
										<div class="col-sm-4">
											<input type="text" id="filter_date_akumulasi_bahan_baku" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_akumulasi_bahan_baku" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
														<th>Tanggal</th>
														<th>Total Nilai Keluar Boulder</th>
                                                        <th>Total Nilai Keluar Solar</th>
                                                        <th>Total Nilai Persediaan Akhir</th>
														<th>Status</th>
                                                        <th>Dibuat Oleh</th>
                                                        <th>Dibuat Tanggal</th>
                                                        <th>Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Akumulasi Bahan Baku -->

                                    <!-- Akumulasi -->
									
                                    <div role="tabpanel" class="tab-pane" id="akumulasi">
										<div class="col-sm-4">
											<input type="text" id="filter_date_akumulasi" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_akumulasi" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
														<th>Tanggal</th>
														<th>Total Nilai Keluar</th>
                                                        <th>Total Nilai Akhir</th>
														<th>Status</th>
                                                        <th>Dibuat Oleh</th>
                                                        <th>Dibuat Tanggal</th>
                                                        <th>Hapus</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Akumulasi -->
										           
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
	$('#dtpickerange').daterangepicker({
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

        var table_hpp_bahan_baku = $('#table_hpp_bahan_baku').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_hpp_bahan_baku'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_hpp_bahan_baku').val();
                }
            },
            responsive: true,
            paging: false,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "date_hpp"
                },
				{
                    "data": "boulder"
                },
				{
                    "data": "bbm"
                },
                {
                    "data": "status"
                },
                {
                    "data": "admin_name"
                },
                {
                    "data": "created_on"
                },
                {
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 2, 3, 4, 5, 6, 7],
                    "className": 'text-center',
                }
            ],
        });
		
		
		$('#filter_date_hpp_bahan_baku').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_hpp_bahan_baku.ajax.reload();
		});

        function DeleteDataHppBahanBaku(id) {
        bootbox.confirm("Anda yakin akan menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('produksi/delete_hpp_bahan_baku'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_hpp_bahan_baku.ajax.reload();
                            bootbox.alert('Berhasil Menghapus HPP Pergerakan Bahan Baku !!');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
            });
        }

        var table_hpp = $('#table_hpp').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_hpp'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_hpp').val();
                }
            },
            responsive: true,
            paging: false,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "date_hpp"
                },
				{
                    "data": "abubatu"
                },
				{
                    "data": "batu0510"
                },
				{
                    "data": "batu1020"
                },
                {
                    "data": "batu2030"
                },
                {
                    "data": "status"
                },
                {
                    "data": "admin_name"
                },
                {
                    "data": "created_on"
                },
                {
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 6, 7, 8, 9],
                    "className": 'text-center',
                },
                {
                    "targets": [2, 3, 4, 5],
                    "className": 'text-right',
                }
            ],
        });
		
		$('#filter_date_hpp').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_hpp.ajax.reload();
		});

        function DeleteDataHpp(id) {
        bootbox.confirm("Anda yakin akan menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('produksi/delete_hpp'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_hpp.ajax.reload();
                            bootbox.alert('Berhasil Menghapus HPP Pergerakan Bahan Jadi !!');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
            });
        }

        var table_akumulasi_bahan_baku = $('#table_akumulasi_bahan_baku').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_akumulasi_bahan_baku'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_akumulasi_bahan_baku').val();
                }
            },
            responsive: true,
            paging: false,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "date_akumulasi"
                },
				{
                    "data": "total_nilai_keluar"
                },
                {
                    "data": "total_nilai_keluar_2"
                },
                {
                    "data": "total_nilai_akhir"
                },
                {
                    "data": "status"
                },
                {
                    "data": "admin_name"
                },
                {
                    "data": "created_on"
                },
                {
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 5, 6, 7, 8],
                    "className": 'text-center',
                },
                {
                    "targets": [2, 3, 4],
                    "className": 'text-center',
                }
            ],
        });
		
		
		$('#filter_date_akumulasi_bahan_baku').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_akumulasi_bahan_baku.ajax.reload();
		});

        function DeleteDataAkumulasiBahanBaku(id) {
        bootbox.confirm("Anda yakin akan menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('produksi/delete_akumulasi_bahan_baku'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_akumulasi_bahan_baku.ajax.reload();
                            bootbox.alert('Berhasil Menghapus Akumulasi Pergerakan Bahan Baku !!');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
            });
        }

        var table_akumulasi = $('#table_akumulasi').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_akumulasi'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_akumulasi').val();
                }
            },
            responsive: true,
            paging: false,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "date_akumulasi"
                },
				{
                    "data": "total_nilai_keluar"
                },
                {
                    "data": "total_nilai_akhir"
                },
                {
                    "data": "status"
                },
                {
                    "data": "admin_name"
                },
                {
                    "data": "created_on"
                },
                {
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 4, 5, 6, 7],
                    "className": 'text-center',
                },
                {
                    "targets": [2, 3],
                    "className": 'text-right',
                }
            ],
        });
		
		$('#filter_date_akumulasi').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_akumulasi.ajax.reload();
		});

        function DeleteDataAkumulasi(id) {
        bootbox.confirm("Anda yakin akan menghapus data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('produksi/delete_akumulasi'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_akumulasi.ajax.reload();
                            bootbox.alert('Berhasil Menghapus Akumulasi Pergerakan Bahan Jadi !!');
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