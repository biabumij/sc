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
                            		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i> Buat Baru <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url('kontak/form'); ?>">Kontak Baru</a></li>
                                      </ul>
                            	</div>
                        	</h3>
                        </div>
                        <div class="panel-content">

                        	<ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Pelanggan</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Rekanan</a></li>
                                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Karyawan</a></li>
                                <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Lain-Lain</a></li>
                            </ul>
                         
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                	<br />
                                	<div class="table-responsive">
		                                <table class="table table-striped table-hover table-center" id="table-pelanggan" style="width: 100%">
		                                    <thead>
		                                        <tr>
		                                            <th>No</th>
		                                            <th>Nama</th>
		                                            <th>Alamat</th>
		                                            <th>Email</th>
		                                            <th>Telepon</th>
		                                            <th>Balance</th>
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
		                                            <th>No</th>
		                                            <th>Nama</th>
		                                            <th>Alamat</th>
		                                            <th>Email</th>
		                                            <th>Telepon</th>
		                                            <th>Balance</th>
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
		                                            <th>No</th>
		                                            <th>Nama</th>
		                                            <th>Alamat</th>
		                                            <th>Email</th>
		                                            <th>Telepon</th>
		                                            <th>Balance</th>
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
		                                            <th>No</th>
		                                            <th>Nama</th>
		                                            <th>Alamat</th>
		                                            <th>Email</th>
		                                            <th>Telepon</th>
		                                            <th>Balance</th>
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

    	

	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script type="text/javascript">
        $('input.numberformat').number( true, 4,',','.' );
        $('input#contract_price, input#price_value, .total').number( true, 2,',','.' );
        // $('input#contract_price').number( true, 2,',','.' );
      
        var table_pelanggan = $('#table-pelanggan').DataTable( {
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
                { "data": "nama" },
                { "data": "alamat" },
                { "data": "email" },
                { "data": "telepon" },
                { "data": "balance" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                },
                {
                    "targets": [-1],
                    "className": 'text-center',
                }
            ],
        });

        var table_rekanan = $('#table-rekanan').DataTable( {
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
                { "data": "no" },
                { "data": "nama" },
                { "data": "alamat" },
                { "data": "email" },
                { "data": "telepon" },
                { "data": "balance" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                },
                {
                    "targets": [-1],
                    "className": 'text-center',
                }
            ],
        });

        var table_karyawan = $('#table-karyawan').DataTable( {
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
                { "data": "no" },
                { "data": "nama" },
                { "data": "alamat" },
                { "data": "email" },
                { "data": "telepon" },
                { "data": "balance" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                },
                {
                    "targets": [-1],
                    "className": 'text-center',
                }
            ],
        });

        var table_lain = $('#teble-lain').DataTable( {
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
                { "data": "no" },
                { "data": "nama" },
                { "data": "alamat" },
                { "data": "email" },
                { "data": "telepon" },
                { "data": "balance" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                },
                {
                    "targets": [-1],
                    "className": 'text-center',
                }
            ],
        });

       


    </script>

</body>
</html>
