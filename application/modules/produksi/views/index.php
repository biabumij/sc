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
                                            <li><a href="<?= site_url('produksi/form_kalibrasi'); ?>">Kalibrasi</a></li>
											<li><a href="<?= site_url('produksi/form_agregat'); ?>">Komposisi Agregat</a></li>
											<li><a href="<?= site_url('produksi/form_produksi_harian'); ?>">Produksi Harian</a></li>
											<li><a href="<?= site_url('produksi/form_produksi_campuran'); ?>">Produksi Campuran</a></li>
                                            <li><a href="javascript:void(0);" onclick="OpenForm()">Stock Opname</a></li>
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
									<li role="presentation"  class="active"><a href="#kalibrasi" aria-controls="kalibrasi" role="tab" data-toggle="tab">Kalibrasi</a></li>
									<li role="presentation"><a href="#komposisi_agregat" aria-controls="komposisi_agregat" role="tab" data-toggle="tab">Komposisi Agregat</a></li>
									<li role="presentation"><a href="#produksi_harian" aria-controls="produksi_harian" role="tab" data-toggle="tab">Produksi Harian</a></li>
									<li role="presentation"><a href="#produksi_campuran" aria-controls="produksi_campuran" role="tab" data-toggle="tab">Produksi Campuran</a></li>
                                    <li role="presentation"><a href="#material_on_site" aria-controls="material_on_site" role="tab" data-toggle="tab">Stock Opname</a></li>
                                    <li role="presentation"><a href="#hpp_bahan_baku" aria-controls="hpp_bahan_baku" role="tab" data-toggle="tab">HPP Pergerakan Bahan Baku</a>
                                    <li role="presentation"><a href="#hpp" aria-controls="hpp" role="tab" data-toggle="tab">HPP Pergerakan Bahan Jadi</a></li>
                                    <li role="presentation"><a href="#akumulasi_bahan_baku" aria-controls="akumulasi_bahan_baku" role="tab" data-toggle="tab">Akumulasi Pergerakan Bahan Baku</a>
                                    <li role="presentation"><a href="#akumulasi" aria-controls="akumulasi" role="tab" data-toggle="tab">Akumulasi Pergerakan Bahan Jadi</a>
                                </ul>

                                <div class="tab-content">
									
									<div role="tabpanel" class="tab-pane" id="material_on_site">
                                        <?php include_once "material_on_site.php"; ?>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="material_usage">
                                        <?php include_once "material_usage.php"; ?>
                                    </div>
									
										
									<!-- Table Kalibrasi -->
									
									<?php			
										$judul = $this->db->order_by('id', 'asc')->get_where('pmm_kalibrasi', array('status' => 'PUBLISH'))->result_array();
									?>
									
                                    <div role="tabpanel" class="tab-pane active" id="kalibrasi">
										<div class="col-sm-4">
											<input type="text" id="filter_date_kalibrasi" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<div class="col-sm-4">
											<select id="jobs_type" name="jobs_type" class="form-control select2">
												<option value="">Pilih Judul</option>
												<?php
												foreach ($judul as $key => $jd) {
												?>
													<option value="<?php echo $jd['jobs_type']; ?>"><?php echo $jd['jobs_type']; ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_kalibrasi" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">No</th>
														<th width="20%">Tanggal</th>
														<th width="30%">Nomor Kalibrasi</th>
                                                        <th width="20%">Judul</th>
														<th width="20%">Lampiran</th>
														
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Table Kalibrasi -->
										
									<!-- Table Produksi Harian -->
										
									<div role="tabpanel" class="tab-pane" id="produksi_harian">
										<div class="col-sm-4">
											<input type="text" id="filter_date_produksi_harian" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<br />
										<br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_produksi_harian" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">No</th>	
                                                        <th width="10%">Tanggal</th>
														<th width="30%">Nomor Produksi Harian</th>	
														<th width="10%">Durasi Produksi (Jam)</th>
														<th width="10%">Pemakaian Bahan Baku (Ton)</th>
														<th width="10%">Kapasitas Produksi (Ton/Jam)</th>
														<th width="10%">Keterangan</th>													
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Table Produksi Harian -->
									
									<!-- Table Produksi Campuran -->
									
									<?php			
										$no_prod = $this->db->order_by('id', 'asc')->get_where('pmm_produksi_campuran', array('status' => 'PUBLISH'))->result_array();
									?>
										
									<div role="tabpanel" class="tab-pane" id="produksi_campuran">
										<div class="col-sm-4">
											<input type="text" id="filter_date_produksi_campuran" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<div class="col-sm-4">
											<select id="no_prod" name="no_prod" class="form-control select2">
												<option value="">Pilih Nomor Produksi Campuran</option>
												<?php
												foreach ($no_prod as $key => $prod) {
												?>
													<option value="<?php echo $prod['no_prod']; ?>"><?php echo $prod['no_prod']; ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<br />
										<br />
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_produksi_campuran" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">No</th>	
                                                        <th width="10%">Tanggal</th>
														<th width="20%" class="text-center">Nomor Produksi Campuran</th>	
														<th width="22%">Uraian</th>
														<th width="8%">Satuan</th>
														<th width="10%">Volume</th>
														<th width="20%">Keterangan</th>													
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Table Produksi Campuran -->
									
									<!-- Table Komposisi Agregat -->
									
                                    <div role="tabpanel" class="tab-pane" id="komposisi_agregat">
										<div class="col-sm-4">
											<input type="text" id="filter_date_agregat" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
										</div>
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_agregat" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
														<th width="25%">Tanggal</th>
														<th width="25%">Nomor Komposisi</th>
                                                        <th width="25%">Judul</th>
														<th width="20%">Lampiran</th>
														
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div>
										
									<!-- End Table Komposisi Agregat -->

                                    <!-- Table HPP Bahan Baku -->
									
                                    <div role="tabpanel" class="tab-pane" id="hpp_bahan_baku">
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
                                                        <th>Tindakan</th>
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
                                                        <th>Tindakan</th>
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
														<th>Status</th>
                                                        <th>Tindakan</th>
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
														<th>Status</th>
                                                        <th>Tindakan</th>
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
            <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
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
		
		var table_kalibrasi = $('#table_kalibrasi').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_kalibrasi'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_kalibrasi').val();
					d.jobs_type = $('#jobs_type').val();
                }
            },
            responsive: true,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "tanggal_kalibrasi"
                },
				{
                    "data": "no_kalibrasi"
                },
				{
                    "data": "jobs_type"
                },
				{
                    "data": "lampiran"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3],
                    "className": 'text-center',
                }
            ],
        });
		
		$('#jobs_type').change(function() {
        table_kalibrasi.ajax.reload();
		});
		
		$('#filter_date_kalibrasi').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_kalibrasi.ajax.reload();
		});

        var table_agregat = $('#table_agregat').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_agregat'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_agregat').val();
                }
            },
            responsive: true,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "date_agregat"
                },
				{
                    "data": "no_komposisi"
                },
				{
                    "data": "jobs_type"
                },
				{
                    "data": "lampiran"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3],
                    "className": 'text-center',
                }
            ],
        });
		
		$('#filter_date_agregat').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_agregat.ajax.reload();
		});
		
		var table_produksi_harian = $('#table_produksi_harian').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_produksi_harian'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_produksi_harian').val();
                }
            },
            responsive: true,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "date_prod"
                },
				{
                    "data": "no_prod"
                },
				{
                    "data": "duration"
                },
				{
                    "data": "used"
                },
				{
                    "data": "capacity"
                },
				{
                    "data": "memo"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 3, 4, 5],
                    "className": 'text-center',
                }
            ],
        });
		
		$('#filter_date_produksi_harian').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_produksi_harian.ajax.reload();
		});
		
		var table_produksi_campuran = $('#table_produksi_campuran').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_produksi_campuran'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_produksi_campuran').val();
					d.no_prod = $('#no_prod').val();
                }
            },
            responsive: true,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "date_prod"
                },
				{
                    "data": "no_prod"
                },
				{
                    "data": "uraian"
                },
				{
                    "data": "measure_convert"
                },
				{
                    "data": "volume_convert"
                },
				{
                    "data": "memo"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 3, 4, 5, 6],
                    "className": 'text-center',
                }
            ],
        });
		
		$('#filter_date_produksi_campuran').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_produksi_campuran.ajax.reload();
		});
		
		$('#no_prod').change(function() {
        table_produksi_campuran.ajax.reload();
		});
		
		
		var table_laporan_produksi_harian = $('#table_laporan_produksi_harian').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produksi/table_laporan_produksi_harian'); ?>',
                type: 'POST',
                data: function(d) {
                    d.filter_date = $('#filter_date_lph').val();
                }
            },
            responsive: true,
            "deferRender": true,
            "language": {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
            },
            columns: [
				{
                    "data": "no"
                },
				{
                    "data": "no_kalibrasi"
                },
				{
                    "data": "date_prod"
                },
				{
                    "data": "no_prod"
                },
				{
                    "data": "duration"
                },
				{
                    "data": "capacity"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 4, 5],
                    "className": 'text-center',
                }
            ],
        });
		
		$('#filter_date_lph').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_laporan_produksi_harian.ajax.reload();
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
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 2, 3, 4, 5],
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
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 2, 3, 4, 5, 6, 7],
                    "className": 'text-center',
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
                    "data": "status"
                },
                {
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 2, 3, 4, 5],
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
                    "data": "status"
                },
                {
                    "data": "actions"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 2, 3, 4],
                    "className": 'text-center',
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

    <?php include_once("script_material_on_site.php"); ?>
    
    <?php include_once("script_material_usage.php"); ?>

</body>

</html>