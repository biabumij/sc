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
                        <li><a >Produk</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">
                            	Produk
                            	<div class="pull-right">
                            		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i> Buat Baru <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url('produk/buat_baru'); ?>">Produk Baru</a></li>
                                      </ul>
                            	</div>
                        	</h3>
                        </div>
                        <div class="panel-content">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#bahanbaku" aria-controls="bahanbaku" role="tab" data-toggle="tab">Bahan Baku</a></li>
                                <!--<li role="presentation"><a href="#betonreadymix" aria-controls="betonreadymix" role="tab" data-toggle="tab">Beton Ready Mix</a></li>-->
                                <li role="presentation"><a href="#aggregat" aria-controls="agreaggregatgat" role="tab" data-toggle="tab">Agregat</a></li>
                                <li role="presentation"><a href="#jasa" aria-controls="jasa" role="tab" data-toggle="tab">Jasa</a></li>
                                <li role="presentation"><a href="#peralatan" aria-controls="peralatan" role="tab" data-toggle="tab">Peralatan</a></li>
                                <li role="presentation"><a href="#bahanbakar" aria-controls="bahanbakar" role="tab" data-toggle="tab">Bahan Bakar</a></li>
                                <li role="presentation"><a href="#laboratorium" aria-controls="laboratorium" role="tab" data-toggle="tab">Laboratorium</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="bahanbaku">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-bahanbaku" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th class="text-center">Nama</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Jual</th>
                                                    <th>Harga Beli</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            
                                
                                <div role="tabpanel" class="tab-pane" id="betonreadymix">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-betonreadymix" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th class="text-center">Nama</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Jual</th>
                                                    <th>Harga Beli</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div role="tabpanel" class="tab-pane" id="aggregat">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-aggregat" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th class="text-center">Nama</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Jual</th>
                                                    <th>Harga Beli</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div role="tabpanel" class="tab-pane" id="jasa">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-jasa" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th class="text-center">Nama</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Jual</th>
                                                    <th>Harga Beli</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div role="tabpanel" class="tab-pane" id="peralatan">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-peralatan" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th class="text-center">Nama</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Jual</th>
                                                    <th>Harga Beli</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div role="tabpanel" class="tab-pane" id="bahanbakar">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-bahanbakar" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th class="text-center">Nama</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Jual</th>
                                                    <th>Harga Beli</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div role="tabpanel" class="tab-pane" id="laboratorium">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-laboratorium" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th class="text-center">Nama</th>
                                                    <th>Satuan</th>
                                                    <th>Harga Jual</th>
                                                    <th>Harga Beli</th>
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
      
        var table_bahanbaku = $('#table-bahanbaku').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 1
                }
            },
            columns: [
                { "data": "no" },
                { "data": "kode_produk" },
                { "data": "nama_produk" },
                { "data": "satuan" },
                { "data": "harga_jual" },
                { "data": "harga_beli" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0,1,3,4,5],
                    "className": 'text-center',
                }
            ],
        });
        
        var table_betonreadymix = $('#table-betonreadymix').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 2
                }
            },
            columns: [
                { "data": "no" },
                { "data": "kode_produk" },
                { "data": "nama_produk" },
                { "data": "satuan" },
                { "data": "harga_jual" },
                { "data": "harga_beli" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0,1,3,4,5],
                    "className": 'text-center',
                }
            ],
        });
        
        var table_aggregat = $('#table-aggregat').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 3
                }
            },
            columns: [
                { "data": "no" },
                { "data": "kode_produk" },
                { "data": "nama_produk" },
                { "data": "satuan" },
                { "data": "harga_jual" },
                { "data": "harga_beli" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0,1,3,4,5],
                    "className": 'text-center',
                }
            ],
        });
        
        var table_jasa = $('#table-jasa').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 4
                }
            },
            columns: [
                { "data": "no" },
                { "data": "kode_produk" },
                { "data": "nama_produk" },
                { "data": "satuan" },
                { "data": "harga_jual" },
                { "data": "harga_beli" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0,1,3,4,5],
                    "className": 'text-center',
                }
            ],
        });
        
        
        var table_peralatan = $('#table-peralatan').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 5
                }
            },
            columns: [
                { "data": "no" },
                { "data": "kode_produk" },
                { "data": "nama_produk" },
                { "data": "satuan" },
                { "data": "harga_jual" },
                { "data": "harga_beli" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0,1,3,4,5],
                    "className": 'text-center',
                }
            ],
        });
        
        var table_bahanbakar = $('#table-bahanbakar').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 6
                }
            },
            columns: [
                { "data": "no" },
                { "data": "kode_produk" },
                { "data": "nama_produk" },
                { "data": "satuan" },
                { "data": "harga_jual" },
                { "data": "harga_beli" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0,1,3,4,5],
                    "className": 'text-center',
                }
            ],
        });
        
        var table_laboratorium = $('#table-laboratorium').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 7
                }
            },
            columns: [
                { "data": "no" },
                { "data": "kode_produk" },
                { "data": "nama_produk" },
                { "data": "satuan" },
                { "data": "harga_jual" },
                { "data": "harga_beli" },
            ],
            responsive: true,
            pageLength : 25,
            "columnDefs": [
                {
                    "targets": [0,1,3,4,5],
                    "className": 'text-center',
                }
            ],
        });

    </script>

</body>
</html>
