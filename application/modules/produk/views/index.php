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
                            	<!--<div class="pull-right">
                            		<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-weight:bold;">
                                        <i class="fa fa-plus"></i> Buat <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url('produk/buat_baru'); ?>">Produk</a></li>
                                      </ul>
                            	</div>-->
                        	</h3>
                        </div>
                        <div class="panel-content">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#bahanbaku" aria-controls="bahanbaku" role="tab" data-toggle="tab">Bahan Baku</a></li>
                                <!--<li role="presentation"><a href="#betonreadymix" aria-controls="betonreadymix" role="tab" data-toggle="tab">Beton Ready Mix</a></li>-->
                                <li role="presentation"><a href="#aggregat" aria-controls="agreaggregatgat" role="tab" data-toggle="tab" style="font-weight:bold;">Agregat</a></li>
                                <li role="presentation"><a href="#jasa" aria-controls="jasa" role="tab" data-toggle="tab" style="font-weight:bold;">Jasa</a></li>
                                <li role="presentation"><a href="#peralatan" aria-controls="peralatan" role="tab" data-toggle="tab" style="font-weight:bold;">Peralatan</a></li>
                                <li role="presentation"><a href="#bahanbakar" aria-controls="bahanbakar" role="tab" data-toggle="tab" style="font-weight:bold;">Bahan Bakar</a></li>
                                <li role="presentation"><a href="#laboratorium" aria-controls="laboratorium" role="tab" data-toggle="tab" style="font-weight:bold;">Laboratorium</a></li>
                                <li role="presentation"><a href="#asset" aria-controls="asset" role="tab" data-toggle="tab" style="font-weight:bold;">Asset</a></li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="bahanbaku">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-bahanbaku" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
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
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
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
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
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
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
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
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
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
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
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
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane" id="asset">
                                	<br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-asset" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Satuan</th>
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
      
        var table_bahanbaku = $('#table-bahanbaku').DataTable( {"bAutoWidth": false,
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
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        var table_betonreadymix = $('#table-betonreadymix').DataTable( {"bAutoWidth": false,
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
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        var table_aggregat = $('#table-aggregat').DataTable( {"bAutoWidth": false,
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
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        var table_jasa = $('#table-jasa').DataTable( {"bAutoWidth": false,
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
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        
        var table_peralatan = $('#table-peralatan').DataTable( {"bAutoWidth": false,
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
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        var table_bahanbakar = $('#table-bahanbakar').DataTable( {"bAutoWidth": false,
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
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });
        
        var table_laboratorium = $('#table-laboratorium').DataTable( {"bAutoWidth": false,
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
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });

        var table_asset = $('#table-asset').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('produk/table_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.tipe = 8
                }
            },
            columns: [
                { "data": "no" },
                { "data": "nama_produk" },
                { "data": "satuan" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });

    </script>

</body>
</html>
