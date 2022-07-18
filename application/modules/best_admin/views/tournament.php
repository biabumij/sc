<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .modal-title{
            float: left;
        }
    </style>
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
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Tournament</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Tournament</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-1">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="openForm()"><i class="fa fa-plus"></i> Add New</a>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="" id="date" class="form-control" autocomplete="off" placeholder="Filter by Date">
                                </div>
                            </div>
                            <br />
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-center" id="guest-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Prize</th>
                                            <th>Status</th>
                                            <th>Actions</th>
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
    


	<?php echo $this->Templates->Footer();?>

   

    <div class="modal fade bd-example-modal-lg" id="modalDetail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <form id="formPlayer" action="<?php echo site_url('best_admin/form_tournament_team');?>" method="POST" enctype="multipart/form-data">

                                <input type="hidden" id="id" name="id" />
                                <div class="form-group">
                                    <label class="control-label">Type</label>
                                    <select name="type" class="form-control" id="gender">
                                        <option value="">.. Select Category ..</option>
                                        <option value="MEN">MEN</option>
                                        <option value="WOMEN">WOMEN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Team</label>
                                    <select class="form-control" name="team_id" id="team_id">
                                        
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">SAVE</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-7">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed" id="detail-table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Category</th>
                                            <th>Team</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


     <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width:90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formRegister" action="<?php echo site_url('best_admin/form_tournament');?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="q_id" id="q_id">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" name="name" class="form-control" id="form-name" required="">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Date</label>
                            <input type="text" name="date" class="form-control" id="form-date" required="">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Prize</label>
                            <input type="text" name="prize" class="form-control" id="form-prize" required="">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <select name="status" class="form-control" id="form-status">
                                <option value="COMING SOON">COMING SOON</option>
                                <option value="OPEN">OPEN</option>
                                <option value="ONGOING">ONGOING</option>
                                <option value="CLOSED">CLOSED</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">SAVE</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade bd-example-modal-lg" id="modalPoint" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="width:60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Team Points</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formPoints" action="<?php echo site_url('best_admin/form_points');?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-2">
                                <select name="type" class="form-control" id="cat-point">
                                    <option value="MEN">MEN</option>
                                    <option value="WOMEN">WOMEN</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div id="content-points" style="overflow:auto;max-height:500px;">
                            
                        </div>
                        <br />
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">SAVE</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

	<script src="<?php echo base_url();?>assets/back/theme/vendor/toastr/toastr.min.js"></script>
	<script src="<?php echo base_url();?>assets/back/theme/vendor/chart-js/chart.min.js"></script>
	<script src="<?php echo base_url();?>assets/back/theme/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
	<!-- <script src="<?php echo base_url();?>assets/back/theme/javascripts/examples/dashboard.js"></script> -->

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>



    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">

    <script type="text/javascript">
      

        $('#date').daterangepicker({
            autoUpdateInput: false
        });
        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('best_admin/tournament_table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.date = $('#date').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "name" },
                { "data": "date" },
                { "data": "prize" },
                { "data": "status" },
                { "data": "actions" },
            ],
            columnDefs : [
                {
                    targets : [0],
                    className : 'text-center',
                    width : '5%'
                }
            ],
            dom: 'Bfrtip',
            buttons: [
                 'csv', 'excel', 'pdf', 'print'
            ]
        });

        $('#date').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
              table.ajax.reload();
        });


        var table_detail = $('#detail-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('best_admin/detail_tournament');?>',
                type : 'POST',
                data: function ( d ) {
                    d.id = $('#id').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "gender" },
                { "data": "team_id" },

            ],
            columnDefs : [
                {
                    targets : [0],
                    className : 'text-center',
                    width : '40px'
                }
            ],
        });



        function openForm()
        {
            $("#formRegister").trigger("reset");
            $('#q_id').val('');
            $('#modalForm').modal('show');
        }

        function players(id)
        {
            $('#id').val(id);
            $('#gender').val('');
            $('#team_id').html('');
            $('#modalDetail').modal('show');
            table_detail.ajax.reload();
            // get_teams(id);
        }

        function editForm(id)
        {
            $('#modalForm').modal('show');
            $.ajax({
                url         : '<?php echo site_url('best_admin/get_edit');?>',
                data        : {id:id},
                type        : 'POST',
                dataType    : 'json',
                success     : function(data){
                    // Callback code
                    if(data.data){
                        $('#q_id').val(data.data.id);
                        $('#form-name').val(data.data.name);
                        $('#form-date').val(data.data.date);
                        $('#form-prize').val(data.data.prize);
                        $('#form-status').val(data.data.status);

                    }
                }
            });
        }

        function points(id)
        {
            $('#id').val(id)
            $('#modalPoint').modal('show');
            generateTablePoints(id,$('#cat-point').val());
        }

        $('#cat-point').change(function(){
            generateTablePoints($('#id').val(),$(this).val()); 
        });

        function generateTablePoints(id,cat)
        {
            $.ajax({
                url         : '<?php echo site_url('best_admin/get_team_points');?>',
                data        : {id:id,cat:cat},
                type        : 'POST',
                dataType    : 'json',
                success     : function(data){
                    // Callback code
                    if(data.data){
                        $('#content-points').html('<input type="hidden" name="team_total" value="'+data.data.length+'" />');
                        $.each( data.data, function( key, value ) {
                            var no = key +1;
                            $('#content-points').append('<div class="row"><input type="hidden" name="id_'+no+'" value="'+value.id+'"/><div class="col-sm-3"><strong>'+value.team_id+'</strong></div><div class="col-sm-2"><input type="number" value="'+value.wwcd+'" class="form-control" name="wwcd_'+no+'" /></div><div class="col-sm-2"><input type="number" value="'+value.pp+'" class="form-control" name="pp_'+no+'" /></div><div class="col-sm-2"><input type="number" value="'+value.kp+'" class="form-control" name="kp_'+no+'" /></div><div class="col-sm-2"><input type="number" value="'+value.tp+'" class="form-control" name="tp_'+no+'" /></div></div><br />');
                        });
                    }
                }
            });
        }


        $('#gender').change(function(){
            get_teams($(this).val());            
        });

        function get_teams(id)
        {
            $.ajax({
                url         : '<?php echo site_url('best_admin/get_teams');?>',
                data        : {id:id},
                type        : 'POST',
                dataType    : 'json',
                success     : function(data){
                    // Callback code
                    if(data.data){
                        $('#team_id').html('');
                        $.each( data.data, function( key, value ) {
                            $('#team_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                }
            });
        }

        $('#formRegister').on('submit',function(event){
            var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }

            var formAction = form.attr('action');
            $.ajax({
                url         : formAction,
                data        : formdata ? formdata : form.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                dataType    : 'json',
                success     : function(data){
                    $('#loader').hide();
                    // Callback code
                    if(data.output){
                        $("#formRegister").trigger("reset");
                        $('#modalForm').modal('hide');
                        table.ajax.reload();
                        // bootbox.alert("THANK YOU, WE’LL NOTIFY YOU AS SOON AS POSSIBLE");
                    }
                }
            });
            

            event.preventDefault();
        });

        $('#formPlayer').on('submit',function(event){
            var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }

            var formAction = form.attr('action');
            $.ajax({
                url         : formAction,
                data        : formdata ? formdata : form.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                dataType    : 'json',
                success     : function(data){
                    // Callback code
                    if(data.output){
                        if(data.output == 'full'){
                            swal('Teams has been added');
                        }else {
                            $("#formPlayer").trigger("reset");
                            // $('#modalForm').modal('hide');
                            table_detail.ajax.reload();
                            // bootbox.alert("THANK YOU, WE’LL NOTIFY YOU AS SOON AS POSSIBLE");   
                        }
                        
                    }
                }
            });
            

            event.preventDefault();
        });

        $('#formPoints').on('submit',function(event){
            var form = $(this);
            var formdata = false;
            if (window.FormData){
                formdata = new FormData(form[0]);
            }

            var formAction = form.attr('action');
            $.ajax({
                url         : formAction,
                data        : formdata ? formdata : form.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                dataType    : 'json',
                success     : function(data){
                    // Callback code
                    if(data.output){
                        if(data.output == 'full'){
                            swal('Teams has been added');
                        }else {
                            // $("#formPlayer").trigger("reset");
                             swal('Succesfull');
                            $('#modalPoint').modal('hide');
                        }
                        
                    }
                }
            });
            

            event.preventDefault();
        });



    </script>

</body>
</html>
