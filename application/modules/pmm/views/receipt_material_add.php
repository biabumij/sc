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
                        <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                        <li><a href="<?php echo site_url('admin/pembelian');?>"> Pembelian</a></li>
                        <li><a href="<?php echo site_url('admin/pembelian'); ?>"> Pesanan Pembelian</a></li>
                        <li><a>Surat Jalan Penerimaan Pembelian</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h4 class="section-subtitle">
                               Surat Jalan Penerimaan Pembelian
                                
                            </h4>
                        </div>
                        <div class="panel-content">
                            <table class="table">
                                <tr>
                                    <th>Rekanan<span class="required" aria-required="true">*</span></th>
                                    <th>:</th>
                                    <td>
                                        <select id="supplier_id" class="form-control select2">
                                            <option value="">Pilih Rekanan</option>
                                            <?php
                                            foreach ($suppliers as $key => $supplier) {
                                                $selected = false;
                                                ?>
                                                <option value="<?php echo $supplier['id'];?>" <?= $selected;?>><?php echo $supplier['nama'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="200px">No. Pesanan Pembelian<span class="required" aria-required="true">*</span></th>
                                    <th width="20px">:</th>
                                    <td >
                                        <select id="purchase_order" class="form-control select2">
                                            <option value="">Pilih No.Pesanan Pembelian</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
  
                            <div id="alert-receipt-material" class="row">
                                
                            </div>
                            <form id="form-product" class="form-horizontal" action="<?php echo site_url('pmm/receipt_material/process'); ?>"  enctype="multipart/form-data" onsubmit="setTimeout(function () { window.location.reload(); }, 3000)">
                                <input type="hidden" name="purchase_order_id" id="purchase_order_id" value="">
                                <input type="hidden" id="date_receipt_val" name="date_receipt_val" value="<?php echo date('d-m-Y');?>">
                                <input type="hidden" name="receipt_material_id" id="receipt_material_id" value="">
                                <input type="hidden" name="supplier_id" id="form_supplier_id" value="">
                                <input type="hidden" name="select_operation" id="select_operation" value="*">
                                <input type="hidden" id="tax_id" name="tax_id" value="*">
                                <input type="hidden" id="pajak_id" name="pajak_id" value="*">
                                <div class="row">
									<div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Tanggal<span class="required" aria-required="true">*</span></label>
                                        <input type="text" id="date_receipt" name="date_receipt" class="form-control dtpicker" required="" autocomplete="off" placeholder="Tanggal Penerimaan" value="<?php echo date('d-m-Y');?>"" />
                                    </div>
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">No. Surat Jalan<span class="required" aria-required="true">*</span></label>
                                        <input type="text" id="surat_jalan" name="surat_jalan" class="form-control" required="" autocomplete="off" placeholder="No. Surat Jalan" />
                                    </div>
                                </div>
                                <div class="row">
									<div class="col-sm-6">
										<label for="inputEmail3" class="control-label">No. Kendaraan<span class="required" aria-required="true">*</span></label>
                                        <input type="text" id="no_kendaraan" name="no_kendaraan" class="form-control" required="" autocomplete="off" placeholder="No. Kendaraan"/>
                                    </div>
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Supir<span class="required" aria-required="true">*</span></label>
                                        <input type="text" id="driver" name="driver" class="form-control" required="" autocomplete="off" placeholder="Supir" />
                                    </div>
                                </div>
                                <br /><br />    
                                <div class="row">
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Pilih Produk<span class="required" aria-required="true">*</span></label>
                                        <select id="material_id" name="material_id" class="form-control" required="" >
                                            <option value="">Pilih Produk</option>
                                            
                                        </select>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Volume<span class="required" aria-required="true">*</span></label>
										<input type="text" id="volume" name="volume" class="form-control numberformat" value="" placeholder="Volume" required="" autocomplete="off">
                                        </div>
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Satuan<span class="required" aria-required="true">*</span></label>
                                        <select id="measure_id" name="measure_id" class="form-control" readonly="" required="">
                                            <option value="">Pilih Satuan</option>
                                            <?php
                                            $arr_mes = $this->db->get_where('pmm_measures',array('status'=>'PUBLISH'))->result_array();
                                            foreach ($arr_mes as $key => $mes) {
                                                ?>
                                                <option value="<?php echo $mes['measure_name'];?>"><?php echo $mes['measure_name'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Konversi<span class="required" aria-required="true">*</span></label>
										<input type="text" id="berat_isi" name="berat_isi" class="form-control numberformat" value="1" placeholder="Konversi" required="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Volume Konversi * </label>
										<input type="text" id="display_volume" name="display_volume" class="form-control numberformat" value="" placeholder="Volume Konversi" required="" autocomplete="off">
                                        </div>
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Satuan Konversi<span class="required" aria-required="true">*</span></label>
                                        <select id="display_measure" name="display_measure" class="form-control" required="">
                                            <option value="">Pilih Satuan</option>
                                            <?php
                                            $arr_mes = $this->db->get_where('pmm_measures',array('status'=>'PUBLISH'))->result_array();
                                            foreach ($arr_mes as $key => $mes) {
                                                ?>
                                                <option value="<?php echo $mes['measure_name'];?>"><?php echo $mes['measure_name'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <br /><br />  
                                <div class="row">
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Upload Surat Jalan </label>
                                        <input type="file" id="surat_jalan_file" name="surat_jalan_file" class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
										<label for="inputEmail3" class="control-label">Memo </label>
                                        <input type="text" id="memo" name="memo" class="form-control" autocomplete="off" placeholder="Memo" />
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?php echo site_url('admin/pembelian#profile'); ?>" class="btn btn-info" style="margin-top:10px; width:100px; font-weight:bold;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                            <button type="submit" name="submit" class="btn btn-success" style="width:100px; font-weight:bold;"><i class="fa fa-send"></i> Kirim</button>
                                        </div>
                                </div>
                            </form>
                            <br />
                            <div class="text-right">
                                <button class="btn btn-danger" id="btn-view" style="width:100px; font-weight:bold;"><i class="fa fa-search"></i> Lihat Data</button>
                            </div>
                            <div id="box-view" style="display:none;">
                                <div class="row">
                                    <form action="<?php echo site_url('pmm/receipt_material/print_pdf');?>" method="GET" target="_blank">
                                        <input type="hidden" name="supplier_id" id="print_supplier_id">
                                        <input type="hidden" name="purchase_order_id" id="print_purchase_id">
                                        <div class="col-sm-3">
                                            <input type="text" id="filter_date"  name="filter_date" class="form-control filterdate" placeholder="Filter By Date" autocomplete="off">
                                        </div>
                                        <div class="col-sm-3">
                                            <select id="filter_material" name="filter_material" class="form-control">
                                                <option value="">Pilih Produk</option>
                                                
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-default" style="width:200px; font-weight:bold;"><i class="fa fa-print"></i> Cetak</button>
                                        </div>
                                    </form>
                                    
                                </div>
                                <br />
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered table-condensed" id="guest-table" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Rekanan</th>
												<th>No. Pesanan Pembelian</th>
                                                <th>No. Surat Jalan</th>
                                                <th>No. Kendaraan</th>
                                                <th>Supir</th>
                                                <th>File</th>
                                                <th>Memo</th>
                                                <th>Produk</th>
                                                <th>Volume</th>
                                                <th>Satuan</th>
												<th>Harga Satuan</th>
												<th>Nilai</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

    <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Ubah Penerimaan</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 20px 0 20px;" method="POST" action="<?php echo site_url('pmm/receipt_material/edit_process');?>"  enctype="multipart/form-data" >
                        <input type="hidden" name="id_edit" id="id_edit">
                        <input type="hidden" name="edit_po_val" id="edit_po_val">
                        <input type="hidden" name="edit_material_val" id="edit_material_val">
						<input type="hidden" name="edit_select_operation" id="edit_select_operation" value="*">
                        <div class="form-group">
                            <label>Rekanan</label>
                            <input type="text" id="edit_rekanan" name="edit_rekanan" class="form-control" required="" autocomplete="off" readonly="" />
                        </div>
                       <div class="form-group">
                            <label>Pesanan Pembelian</label>
                            <input type="text" id="edit_no_po" name="edit_no_po" class="form-control" required="" autocomplete="off" readonly="" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" id="edit_date" name="edit_date" class="form-control" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>No. Surat Jalan</label>
                            <input type="text" id="edit_surat_jalan" name="edit_surat_jalan" class="form-control" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Produk</label>
                            <input type="text" id="edit_material" name="edit_material" class="form-control" required="" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label>Volume</label>
                            <input type="text" id="edit_volume" name="edit_volume" class="form-control numberformat" required="" autocomplete="off" />
                        </div>
						<div class="form-group">
                            <label>Satuan</label>
                            <input type="text" id="edit_measure" name="edit_measure" class="form-control" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Konversi</label>
                            <input type="text" id="edit_convert_value" name="edit_convert_value" class="form-control" required="" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label>Volume Konversi</label>
                            <input type="text" id="edit_display_volume" name="edit_display_volume" class="form-control numberformat" required="" autocomplete="off" />
                        </div>
						<div class="form-group">
                            <label>Satuan Konversi</label>
                            <input type="text" id="edit_display_measure" name="edit_display_measure" class="form-control" required="" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label>No. Kendaraan</label>
                            <input type="text" id="edit_no_kendaraan" name="edit_no_kendaraan" class="form-control" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Supir</label>
                            <input type="text" id="edit_driver" name="edit_driver" class="form-control" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Memo</label>
                            <input type="text" id="edit_memo" name="edit_memo" class="form-control" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Upload Jalan File</label>
                            <input type="file" id="edit_surat_jalan_file" name="edit_surat_jalan_file" class="form-control"  />
                            <input type="hidden" name="edit_surat_jalan_file_val" id="edit_surat_jalan_file_val">
                            <a href="" id="edit-surat-jalan-text"></a>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="btn-form"><i class="fa fa-send"></i> Kirim</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="modalMat" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Akumulasi</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" id="filter_date_acc" class="form-control filterdate" placeholder="Filter By Date" autocomplete="off">
                        </div>
                    </div>
                    <br />
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-center table-bordered table-condensed" id="table-acc" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    
    
    <script type="text/javascript">
        var form_control = '';
        var base_url = '<?php echo base_url();?>';
    </script>
	<?php echo $this->Templates->Footer();?>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script type="text/javascript">
        
        $('input.numberformat').number( true, 2,',','.' );
        $('.dtpicker').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
            //minDate: new Date()+0,
			//maxDate: new Date()+1,
            //minDate: moment().add(-10, 'd').toDate(),
			//maxDate: moment().add(+0, 'd').toDate(),
            //minDate: moment().add(-40, 'd').toDate(),
            minDate: moment().startOf(-1, 'month').toDate(),
            //minDate: moment().startOf('month').toDate(),
			maxDate: moment().endOf('month').toDate(),
        });


        $(document).ready(function(){
            $('#supplier_id').val(<?= $data['supplier_id'];?>).trigger('change');
            $('#purchase_order').val(<?= $data['id'];?>).trigger('change');
        });
        

        $('#date_receipt').on('apply.daterangepicker', function(ev, picker) {
              $('#date_receipt_val').val(picker.startDate.format('DD-MM-YYYY'));
              // table.ajax.reload();
        });

        $('#btn-view').click(function(){
            $('#box-view').show();
        });

        $('.filterdate').daterangepicker({
            autoUpdateInput: false,
            showDropdowns: true,
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
            }
        });

        $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
              table.ajax.reload();
        });
         $('#filter_date_acc').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
              table_acc.ajax.reload();
        });

        $("#berat_isi, #volume, #select_operation").change(function(){
            
            getTotalDisplay();
        });
		$("#edit_convert_value, #edit_volume, #select_operation").change(function(){
            
            getTotalDisplayEdit();
        });

        function getTotalDisplay()
        {
            var volume = $('#volume').val();
            var select_operation = $('#select_operation').val();
            var val = $('#berat_isi').val();
            if(select_operation === '' && volume === ''){
                alert('Check Operation First or Volume');
            }else {
                
                if(select_operation == '*'){
                    var display_volume = volume * val;
                }else {
                    var display_volume = volume / val;
                }
                $('#display_volume').val(display_volume);
                // console.log(volume+'='+jumlah_berat_isi);
            }
        }
		
		function getTotalDisplayEdit()
        {
            var edit_volume = $('#edit_volume').val();
            var select_operation = $('#select_operation').val();
            var edit_val = $('#edit_convert_value').val();
            if(select_operation === '' && volume === ''){
                alert('Check Operation First or Volume');
            }else {
                
                if(select_operation == '*'){
                    var edit_display_volume = edit_volume * edit_val;
                }else {
                    var edit_display_volume = edit_volume / edit_val;
                }
                $('#edit_display_volume').val(edit_display_volume);
                // console.log(volume+'='+edit_display_volume);
            }
        }

        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/receipt_material/table_receipt');?>',
                type : 'POST',
                data: function ( d ) {
                    d.purchase_order_id = $('#purchase_order_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.filter_date = $('#filter_date').val();
                    d.material_id = $('#filter_material').val();

                }
            },
            "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
            columns: [
                { "data": "no" },
                { "data": "date_receipt" },
                { "data": "supplier_name" },
				{ "data": "no_po" },
                { "data": "surat_jalan" },
                { "data": "no_kendaraan" },
                { "data": "driver" },
                { "data": "memo" },
                { "data": "surat_jalan_file" },
                { "data": "material_name" },
                { "data": "volume" },
                { "data": "measure" },
                { "data": "harga_satuan" },
				{ "data": "price" },
                { "data": "actions" }
            ],
            responsive: true,
            "columnDefs": [
                {
                    "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    "className": 'text-center',
                }
            ],
        });

        var table_acc = $('#table-acc').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/receipt_material/table_acc');?>',
                type : 'POST',
                data: function ( d ) {
                    d.purchase_order_id = $('#purchase_order_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.filter_date = $('#filter_date_acc').val();
                }
            },
            "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
            columns: [
                { "data": "no" },
                { "data": "material_name" },
                { "data": "volume" },
                { "data": "measure" }
            ],
            responsive: true,
            "columnDefs": [
                {
                    "targets": [0, 1, 2, 3],
                    "className": 'text-center',
                }
            ]
        });


        $('#btn-acc').click(function(){
            $('#modalMat').modal('show');
        });

        function GetPO()
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/receipt_material/get_po_by_supp'); ?>/"+Math.random(),
                dataType : 'json',
                data: {
                    supplier_id : $('#supplier_id').val(),
                },
                success : function(result){
                    if(result.data){
                        $('#purchase_order').empty();
                        $('#purchase_order').select2({data:result.data});
                        //$('#purchase_order').val(result.last_po).trigger('change');
                        $('#purchase_order').val(<?= $data['id'];?>).trigger('change');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        function SelectMatByPo()
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/receipt_material/get_mat_by_po'); ?>/"+Math.random(),
                dataType : 'json',
                data: {
                    supplier_id : $('#supplier_id').val(),
                    purchase_order_id : $('#purchase_order').val(),
                },
                success : function(result){
                    if(result.data){
                        $('#material_id').html('');
                        $('#filter_material').html('');
                        $('#alert-receipt-material').html('');
                        var no_alert = 1;
                        $.each(result.data,function(key,val){
                            $('#material_id').append('<option value="'+val.id+'" data-measure="'+val.measure+'" data-display-measure="'+val.display_measure+'" data-tax_id="'+val.tax_id+'" data-pajak_id="'+val.pajak_id+'">'+val.text+'</option>');
                            $('#filter_material').append('<option value="'+val.id+'" >'+val.text+'</option>');

                            if(key > 0){
                                $('#alert-receipt-material').append('<div class="col-sm-3">'
                                    +'<div class="alert alert-danger text-center">'
                                        +'<h5><strong>'+val.text+'</strong></h5>'
                                        +'<div class="text-right"><b>PO : '+val.total_po+'  <br /></b>'
                                        +'<b>Penerimaan : '+val.receipt_material
                                    +'</div></div></b>'
                                +'</div>');
                                if(no_alert % 4 == 0){
                                    $('#alert-receipt-material').append('</div><div class="row">');
                                }
                                no_alert++;
                            }
                        });

                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }
        
        $('#supplier_id').change(function(){
            GetPO();
            table.ajax.reload();
            table_acc.ajax.reload();
            $('#print_supplier_id').val($(this).val());
            $('#form_supplier_id').val($(this).val());
        });

        $('#purchase_order').change(function(){

            
            $('#purchase_order_id').val($(this).val());
            $('#print_purchase_id').val($(this).val());
            // if($(this).val() > 0){
                table.ajax.reload();
                table_acc.ajax.reload();
                SelectMatByPo();
            // }
            
        });
        $('#filter_material').change(function(){
            table.ajax.reload();
        });

        
        $('#material_id').change(function(){
            var measure = $(this).find(':selected').data('measure');
            $('#measure_id').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id').val(pajak_id);
        });


        $('#form-product').submit(function(event){
            $('#btn-form').button('loading');
            var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }

            $.ajax({
                type    : "POST",
                url     : $(this).attr('action')+"/"+Math.random(),
                dataType : 'json',
                data: formdata ? formdata : form.serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
					$.toast({
                            heading: 'Success',
                            text: 'Berhasil menambahkan data penerimaan',
                            showHideTransition: 'fade',
                            icon: 'success',
                            position: 'top-right',
                        });
                    if(result.output){
                        $("#form-product").trigger("reset");
                        table.ajax.reload();
                        SelectMatByPo();
                        $('#material_id').focus();
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            event.preventDefault();
            
        });

        

       
        function DeleteData(id)
        {
            bootbox.confirm("Apakah anda yakin menghapus data ini ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.ajax({
                        type    : "POST",
                        url     : "<?php echo site_url('pmm/receipt_material/delete_detail'); ?>",
                        dataType : 'json',
                        data: {id:id},
                        success : function(result){
                            if(result.output){
                                table.ajax.reload();
                                bootbox.alert('Berhasil Menghapus Data Surat Jalan !!');
                            }else if(result.err){
                                bootbox.alert(result.err);
                            }
                        }
                    });
                }
            });
        }

        $('#edit_po').change(function(){
            $('#edit_po_val').val($(this).val());
        });

        $('#modalForm form').submit(function(event){
            $('#btn-form').button('loading');
            var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }
            $.ajax({
                type    : "POST",
                url     : $(this).attr('action')+"/"+Math.random(),
                dataType : 'json',
                data: formdata ? formdata : form.serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        table.ajax.reload();
                        $("#modalForm").modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            event.preventDefault();
            
        });

        function EditData(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/receipt_material/edit_data_detail'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.data){
                        $("#modalForm").modal('show');
                        var data = result.data;
                        if(data.surat_jalan_file != null){
                            $('#edit-surat-jalan-text').attr('href',base_url+'uploads/surat_jalan_penerimaan/'+data.surat_jalan_file);  
                            $('#edit-surat-jalan-text').text(data.surat_jalan_file);    
                            $('#edit-surat-jalan-text').attr('target','_blank');    
                        }
						
                        $('#edit_volume').val(data.volume);
						$('#edit_no_po').val(data.no_po);
						$('#edit_rekanan').val(data.rekanan);
                        $('#edit_surat_jalan').val(data.surat_jalan);
                        $('#edit_no_kendaraan').val(data.no_kendaraan);
                        $('#edit_driver').val(data.driver);
                        $('#edit_memo').val(data.memo);
						$('#edit_measure').val(data.measure);
						$('#edit_display_measure').val(data.display_measure);
						$('#edit_convert_value').val(data.convert_value);
						$('#edit_display_volume').val(data.display_volume);
                        $('#id_edit').val(data.id);
                        $('#edit_date').val(data.date_receipt);
                        $('#edit_po_val').val(data.purchase_order_id);
                        $('#edit_material_val').val(data.material_id);
                        $('#edit_material').val(data.material);
                        $('#edit_material_val').val(data.material_id);
                        $('#edit_surat_jalan_file_val').val(data.surat_jalan_file);
                       
                        $('#edit_po').html('');
                        $.each(result.po,function(key,val){
                            $('#edit_po').append('<option value="'+val.id+'">'+val.no_po+'</option>');
                        });
                        $('#edit_po').val(data.purchase_order_id).trigger('change');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
            
        }
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#display_measure').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });

    </script>
</body>
</html>
