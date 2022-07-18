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
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Team Tournament</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Team Tournament</h3>
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
                                            <th>Nickname</th>
                                            <th>Logo</th>
                                            <th>Type</th>
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
        <div class="modal-dialog modal-lg" role="document" style="width:90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <form id="formPlayer" action="<?php echo site_url('best_admin/form_player');?>" method="POST" enctype="multipart/form-data">
                                 <input type="hidden" id="team_id" name="team_id" />
                                <div class="form-group">
                                    <label class="control-label">Name</label>
                                    <input type="text" name="name" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nickname</label>
                                    <input type="text" name="nickname" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Photo</label>
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <input type="file" name="photo" class="form-control" required="">        
                                        </div>
                                        <div class="col-sm-5">
                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">SAVE</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-9">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed" id="detail-table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Team</th>
                                            <th>Name</th>
                                            <th>Nickname</th>
                                            <th>Photo</th>
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
                    <form id="formRegister" action="<?php echo site_url('best_admin/form_team_tournament');?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" name="name" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nickname</label>
                            <input type="text" name="nickname" class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="MEN">MEN</option>
                                <option value="WOMEN">WOMEN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Logo</label>
                            <div class="row">
                                <div class="col-sm-7">
                                    <input type="file" name="logo" class="form-control" required="">        
                                </div>
                                <div class="col-sm-5">
                                    
                                </div>
                            </div>
                            
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
                url: '<?php echo site_url('best_admin/teams_table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.date = $('#date').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "name" },
                { "data": "nickname" },
                { "data": "logo" },
                { "data": "type" },
                { "data": "actions" }
            ],
            columnDefs : [
                {
                    targets : [0],
                    className : 'text-center',
                    width : '60px'
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
                url: '<?php echo site_url('best_admin/players_table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.team_id = $('#team_id').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "team_id" },
                { "data": "name" },
                { "data": "nickname" },
                { "data": "photo" },
            ],
            columnDefs : [
                {
                    targets : [0],
                    className : 'text-center',
                    width : '60px'
                }
            ],
        });

        function openForm()
        {
            $('#modalForm').modal('show');
        }

        function players(id)
        {
            $('#team_id').val(id);
            $('#modalDetail').modal('show');
            table_detail.ajax.reload();
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
                        $("#formPlayer").trigger("reset");
                        // $('#modalForm').modal('hide');
                        table_detail.ajax.reload();
                        // bootbox.alert("THANK YOU, WE’LL NOTIFY YOU AS SOON AS POSSIBLE");
                    }
                }
            });
            

            event.preventDefault();
        });



    </script>

</body>
</html>
