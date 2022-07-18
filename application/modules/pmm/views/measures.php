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
                        <li><a >Measures</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Satuan</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-2">
                                    <a href="javascript:void(0);" onclick="OpenForm()" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Satuan</a>
                                </div>
                                <form method="GET" target="_blank" action="<?php echo site_url('pmm/reports/measures_print');?>">
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                                    </div>  
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-center" id="guest-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
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
    

    
    <script type="text/javascript">
        var form_control = '';
    </script>
	<?php echo $this->Templates->Footer();?>

    

    <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Satuan</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Nama Satuan *</label>
                            <input type="text" id="measure_name" name="measure_name" class="form-control" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select id="status" name="status" class="form-control" required="">
                                <option value="PUBLISH">PUBLISH</option>
                                <option value="UNPUBLISH">UNPUBLISH</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="btn-form"><i class="fa fa-send"></i> Kirim</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalDetail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Tool Detail</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo site_url('pmm/measure_convert_form');?>" method="POST" id="form-man">
                        <input type="hidden" name="sub_detail_id" id="sub_detail_id">
                        <input type="hidden" id="detail_id" name="detail_id">
                        <div class="row">
                            <div class="col-sm-3">
                                <select id="measure_to" name="measure_to" class="form-control">
                                    <option value="">.. Select Measures ..</option>
                                    <?php
                                    $measures = $this->db->get_where('pmm_measures',array('status !='=>'DELETED'))->result_array();
                                    foreach ($measures as $key => $to) {
                                        ?>
                                        <option value="<?php echo $to['id'];?>"><?php echo $to['measure_name'];?></option>
                                        <?php
                                    }
                                    ?>  
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text"  id="value" name="value" class="form-control numberformat" autocomplete="off" placeholder="Value Convert" required="">
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" name="submit" id="btn-man" class="btn btn-warning"><i class="fa fa-save"></i> Simpan</button>
                                <button type="button" id="btn-unedit" class="btn btn-info"><i class="fa fa-undo"></i></button>
                            </div>
                        </div>
                    </form>
                    <br />
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-center" id="detail-table" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Measure</th>
                                    <th>Value</th>
                                    <th>Actions</th>
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

	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script type="text/javascript">
        $('input.numberformat').number( true, 6,',','.' );
        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/table_measure');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "measure_name" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });

        var table_detail = $('#detail-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/table_measure_convert');?>',
                type : 'POST',
                data: function ( d ) {
                    d.detail_id = $('#detail_id').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "name" },
                { "data": "value" },
                { "data": "actions" }
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                    "width" : '10px'
                },
                {
                    "targets": [-1],
                    "className": 'text-center',
                    "width" : '150px'
                }
            ],
        });

        function OpenForm(id='')
        {   
            
            $('#modalForm').modal('show');
            $('#id').val('');
            // table_detail.ajax.reload();
            if(id !== ''){
                $('#id').val(id);
                getData(id);
            }
        }

        $('#modalForm form').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/form_measure'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $("#modalForm form").trigger("reset");
                        table.ajax.reload();
                        $('#modalForm').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        function getData(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/get_measure'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#id').val(result.output.id);
                        $('#measure_name').val(result.output.measure_name);
                        $('#status').val(result.output.status);
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }


        function DeleteData(id)
        {
            bootbox.confirm("Are you sure to delete this data ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.ajax({
                        type    : "POST",
                        url     : "<?php echo site_url('pmm/delete_measure'); ?>",
                        dataType : 'json',
                        data: {id:id},
                        success : function(result){
                            if(result.output){
                                table.ajax.reload();
                                bootbox.alert('Berhasil menghapus!!');
                            }else if(result.err){
                                bootbox.alert(result.err);
                            }
                        }
                    });
                }
            });
        }

        function FormDetail(id,name)
        {   
            $('#detail_id').val(id);
            $('#detail_product_id').val('');
            $("#modalDetail form").trigger("reset");
            $('#modalDetail').modal('show');
            $('#modalDetail .modal-title').text(name);
            table_detail.ajax.reload();
            
        }

        $('#form-man').on('submit',function(event){
            var form = $(this);

            var formAction = form.attr('action');
            $('#btn-man').button('loading');
            $.ajax({
                url         : formAction,
                data        : form.serialize(),
                type        : 'POST',
                dataType    : 'json',
                success     : function(data){
                    // $('#loader').hide();
                    $('#btn-man').button('reset');
                    // Callback code
                    if(data.output){
                        $("#form-man").trigger("reset");
                        table_detail.ajax.reload();
                        table.ajax.reload();
                        $('#detail_product_id').val('');
                        $('#btn-unedit').hide();
                    }else if(data.err){
                        bootbox.alert(data.err);
                    }
                }
            });
            

            event.preventDefault();
        });

        function EditDetail(id,measure_to,value)
        {
           $('#sub_detail_id').val(id);
           $('#measure_to').val(measure_to);
           $('#value').val(value);
           $('#btn-unedit').show();

        }

        $('#btn-unedit').click(function(){
            $("#form-man").trigger("reset");
            $('#sub_detail_id').val('');
            $(this).hide();
        });

        function DeleteDataDetail(id)
        {
            bootbox.confirm("Are you sure to delete this data ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.ajax({
                        type    : "POST",
                        url     : "<?php echo site_url('pmm/delete_measure_convert'); ?>",
                        dataType : 'json',
                        data: {id:id},
                        success : function(result){
                            if(result.output){
                                table_detail.ajax.reload();
                                bootbox.alert('Berhasil menghapus!!');
                            }else if(result.err){
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
