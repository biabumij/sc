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
                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius:10px; font-weight:bold;">
                                            <i class="fa fa-plus"></i> Buat Baru <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
											<li><a href="<?= site_url('form/form_perubahan_sistem'); ?>">Perubahan Sistem</a></li>
                                        </ul>
                                    </div>
                                </h3>

                            </div>
                            <div class="panel-content">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#perubahan_sistem" aria-controls="perubahan_sistem" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px; font-weight:bold;">Perubahan Sistem</a></li>
                                </ul>

                                <div class="tab-content">
								
                                    <div role="tabpanel" class="tab-pane active" id="perubahan_sistem">									
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_perubahan_sistem" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">No.</th>
														<th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Dibuat Oleh</th>
                                                        <th>Dibuat Tanggal</th>
                                                        <th>Lampiran</th>
                                                        <th>Status Permintaan</th>
                                                        <th>Status Approval</th>
                                                        <th class="text-center" width="5%">Cetak</th>
                                                        <th class="text-center" width="5%">Upload</th>
														<th class="text-center" width="5%">Hapus</th>
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

                            <div class="modal fade bd-example-modal-lg" id="modalDoc" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <span class="modal-title">Upload Document Perubahan Sistem</span>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-horizontal" enctype="multipart/form-data" method="POST" style="padding: 0 10px 0 20px;">
                                                <input type="hidden" name="id" id="id_doc">
                                                <div class="form-group">
                                                    <label>Upload Document</label>
                                                    <input type="file" id="file" name="file" class="form-control" required="" />
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success" id="btn-form-doc" style="font-weight:bold; width;10%; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-weight:bold; width;10%; border-radius:10px;">Close</button>
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
		
		var table_perubahan_sistem = $('#table_perubahan_sistem').DataTable({
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('form/table_perubahan_sistem'); ?>',
                type: 'POST',
                data: function(d) {
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
                    "data": "tanggal"
                },
                {
                    "data": "nomor"
                },
                {
					"data": "created_by"
				},
				{
					"data": "created_on"
				},
                {
					"data": "lampiran"
				},
                {
					"data": "status_permintaan"
				},
                {
					"data": "approve_ti_sistem"
				},
                {
					"data": "print"
				},
                {
                    "data": "document_perubahan_sistem"
                },
				{
					"data": "actions"
				},
            ],
            "columnDefs": [{
                    "targets": [0, 8, 9, 10],
                    "className": 'text-center',
                }
            ],
        });

        function ApprovePerubahanSistem(id) {
        bootbox.confirm("Apakah anda yakin untuk proses data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('form/approve_ti_sistem'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_perubahan_sistem.ajax.reload();
                            bootbox.alert('Berhasil menyetujui Perubahan Sistem');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
        });
        }

        function PerubahanSistemSelesai(id) {
        bootbox.confirm("Apakah anda yakin untuk proses data ini ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('form/perubahan_sistem_selesai'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_perubahan_sistem.ajax.reload();
                            bootbox.alert('Permintaan Selesai');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
        });
        }
	
		function DeleteData(id) {
        bootbox.confirm("Are you sure to delete this data ?", function(result) {
            // console.log('This was logged in the callback: ' + result); 
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('form/delete_perubahan_sistem'); ?>",
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        if (result.output) {
                            table_perubahan_sistem.ajax.reload();
                            bootbox.alert('Berhasil menghapus data !!');
                        } else if (result.err) {
                            bootbox.alert(result.err);
                        }
                    }
                });
            }
        });
        }

        function UploadDoc(id) {

        $('#modalDoc').modal('show');
        $('#id_doc').val(id);
        }

        $('#modalDoc form').submit(function(event) {
            $('#btn-form-doc').button('loading');

            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('form/form_document'); ?>/" + Math.random(),
                dataType: 'json',
                data: formdata ? formdata : form.serialize(),
                success: function(result) {
                    $('#btn-form-doc').button('reset');
                    if (result.output) {
                        $("#modalDoc form").trigger("reset");
                        table_perubahan_sistem.ajax.reload();

                        $('#modalDoc').modal('hide');
                    } else if (result.err) {
                        bootbox.alert(result.err);
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            event.preventDefault();

        });

    </script>

</body>

</html>