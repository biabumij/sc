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
                        <li><a >Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">
                            	Kontak
                            	<div class="pull-right">
                            		<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius:10px; font-weight:bold;">
                                        <i class="fa fa-plus"></i> Buat Baru <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url('kontak/form'); ?>">Kontak</a></li>
                                      </ul>
                            	</div>
                        	</h3>
                        </div>
                        <div class="panel-content">

                        	<ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px; font-weight:bold;">Pelanggan</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px; font-weight:bold;">Rekanan</a></li>
                                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px; font-weight:bold;">Karyawan</a></li>
                                <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px; font-weight:bold;">Lain-Lain</a></li>
                            </ul>
                         
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                	<br />
                                	<div class="table-responsive">
		                                <table class="table table-striped table-hover table-center" id="table-pelanggan" style="width: 100%">
		                                    <thead>
		                                        <tr>
		                                            <th class="text-center">No.</th>
		                                            <th class="text-center">Nama</th>
		                                            <th class="text-center">Alamat</th>
		                                            <th class="text-center">Email</th>
		                                            <th class="text-center">Telp.</th>
                                                    <th class="text-center">Dibuat Oleh</th>
                                                    <th class="text-center">Dibuat Tanggal</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                       
		                                    </tbody>
		                                </table>
		                            </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="profile">
                                	<br />
                                	<div class="table-responsive">
		                                <table class="table table-striped table-hover table-center" id="table-rekanan" style="width: 100%">
		                                    <thead>
		                                        <tr>
                                                    <th class="text-center">No.</th>
		                                            <th class="text-center">Nama</th>
		                                            <th class="text-center">Alamat</th>
		                                            <th class="text-center">Email</th>
		                                            <th class="text-center">Telp.</th>
                                                    <th class="text-center">Dibuat Oleh</th>
                                                    <th class="text-center">Dibuat Tanggal</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                       
		                                    </tbody>
		                                </table>
		                            </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="messages">
                                	<br />
                                	<div class="table-responsive">
		                                <table class="table table-striped table-hover table-center" id="table-karyawan"  style="width: 100%">
		                                    <thead>
		                                        <tr>
                                                    <th class="text-center">No.</th>
		                                            <th class="text-center">Nama</th>
		                                            <th class="text-center">Alamat</th>
		                                            <th class="text-center">Email</th>
		                                            <th class="text-center">Telp.</th>
                                                    <th class="text-center">Dibuat Oleh</th>
                                                    <th class="text-center">Dibuat Tanggal</th>
		                                        </tr>
		                                    </thead>
		                                    <tbody>
		                                       
		                                    </tbody>
		                                </table>
		                            </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="settings">
                                	<br />
                                	<div class="table-responsive">
		                                <table class="table table-striped table-hover table-center" id="teble-lain" style="width: 100%">
		                                    <thead>
		                                        <tr>
                                                    <th class="text-center">No.</th>
		                                            <th class="text-center">Nama</th>
		                                            <th class="text-center">Alamat</th>
		                                            <th class="text-center">Email</th>
		                                            <th class="text-center">Telp.</th>
                                                    <th class="text-center">Dibuat Oleh</th>
                                                    <th class="text-center">Dibuat Tanggal</th>
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

    	

	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script type="text/javascript">
        $('input.numberformat').number( true, 4,',','.' );
        $('input#contract_price, input#price_value, .total').number( true, 2,',','.' );
        // $('input#contract_price').number( true, 2,',','.' );
      
        var table_pelanggan = $('#table-pelanggan').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('kontak/table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 1
                }
            },
            columns: [
                { "data": "no" },
                { "data": "nama"},
                { "data": "alamat"},
                { "data": "email"},
                { "data": "telepon"},
                { "data": "admin_name"},
                { "data": "created_on"}
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
                { "width": "30%", "targets": 1, "className": 'text-left'},
                { "width": "25%", "targets": 2, "className": 'text-left'},
                { "width": "10%", "targets": [3, 4], "className": 'text-left'},
                { "width": "10%", "targets": [5, 6], "className": 'text-center' }
            ],
            responsive: true,
        });

        var table_rekanan = $('#table-rekanan').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('kontak/table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 2
                }
            },
            columns: [
                { "data": "no"},
                { "data": "nama"},
                { "data": "alamat"},
                { "data": "email"},
                { "data": "telepon"},
                { "data": "admin_name"},
                { "data": "created_on"}
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
                { "width": "30%", "targets": 1, "className": 'text-left'},
                { "width": "25%", "targets": 2, "className": 'text-left'},
                { "width": "10%", "targets": [3, 4], "className": 'text-left'},
                { "width": "10%", "targets": [5, 6], "className": 'text-center' }
            ],
            responsive: true,
        });

        var table_karyawan = $('#table-karyawan').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('kontak/table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 3
                }
            },
            columns: [
                { "data": "no"},
                { "data": "nama"},
                { "data": "alamat"},
                { "data": "email"},
                { "data": "telepon"},
                { "data": "admin_name"},
                { "data": "created_on"}
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
                { "width": "30%", "targets": 1, "className": 'text-left'},
                { "width": "25%", "targets": 2, "className": 'text-left'},
                { "width": "10%", "targets": [3, 4], "className": 'text-left'},
                { "width": "10%", "targets": [5, 6], "className": 'text-center' }
            ],
            responsive: true,
        });

        var table_lain = $('#teble-lain').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('kontak/table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 4
                }
            },
            columns: [
                { "data": "no"},
                { "data": "nama"},
                { "data": "alamat"},
                { "data": "email"},
                { "data": "telepon"},
                { "data": "admin_name"},
                { "data": "created_on"}
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
                { "width": "30%", "targets": 1, "className": 'text-left'},
                { "width": "25%", "targets": 2, "className": 'text-left'},
                { "width": "10%", "targets": [3, 4], "className": 'text-left'},
                { "width": "10%", "targets": [5, 6], "className": 'text-center' }
            ],
            responsive: true,
        });

    </script>

</body>
</html>
