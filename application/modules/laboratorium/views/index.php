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
											<li><a href="<?= site_url('laboratorium/form_jmd'); ?>">Job Mix Design (JMD)</a></li>
                                        </ul>
                                    </div>
                                </h3>

                            </div>
                            <div class="panel-content">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#jmd" aria-controls="jmd" role="tab" data-toggle="tab">Job Mix Design (JMD)</a></li>
                                </ul>

                                <div class="tab-content">
															
									<!-- Table Job Mix Design -->
									
                                    <div role="tabpanel" class="tab-pane active" id="jmd">
										<?php
										$products = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH','betonreadymix'=>1))->result_array();
										?>
										<div class="col-sm-3">
											<select id="filter_product" class="form-control select2" name="filter_product">
												<option value="">Pilih Mutu Beton</option>
												<?php
													if(!empty($products)){
														foreach ($products as $row) {
															?>
															<option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
															<?php
														}
													}
													?>
											</select>
										</div>									
										<br />
										<br />										
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_jmd" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="10%">No</th>
                                                        <th class="text-center" width="10%">Tanggal</th>
														<th class="text-center" width="10%">Mutu Beton</th>
														<th class="text-center" width="10%">Slump</th>
														<th class="text-center" width="10%">Nama Komposisi</th>
														<th class="text-center" width="10%">Nomor Komposisi</th>
														<th class="text-center" width="10%">Lampiran</th>
														<th class="text-center" width="10%">Keterangan</th>	
														<th class="text-center" width="10%">Status</th>														
													</tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                   
                                                </tfoot>
                                            </table>
                                        </div>
									</div> 
										
										<!-- End Table Job Mix Design -->
								
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
		
		var table_jmd = $('#table_jmd').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('laboratorium/table_jmd'); ?>',
                type: 'POST',
                data: function(d) {
					d.filter_product = $('#filter_product').val();
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
                    "data": "tanggal_jmd"
                },
				{
                    "data": "mutu_beton"
                },
				{
                    "data": "slump"
                },
				{
                    "data": "nama_komposisi"
                },
				{
                    "data": "nomor_komposisi"
                },
				{
                    "data": "lampiran"
                },
				{
                    "data": "memo"
                },
				{
                    "data": "status"
                }
            ],
            "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4, 8],
                    "className": 'text-center',
                }
            ],
        });

		$('#filter_product').change(function() {
                table_jmd.ajax.reload();
        });
	
    </script>

</body>

</html>