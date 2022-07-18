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
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Men Competition</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Men Registration</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
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
                                            <th>Manager</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Created at</th>
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

    <input type="hidden" id="team_id" />

    <div class="modal fade bd-example-modal-lg" id="modalDetail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document" style="max-width:90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed" id="detail-table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Team</th>
                                    <th>Name</th>
                                    <th>DOB</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>NIK</th>
                                    <th>Game Username</th>
                                    <th>Game ID</th>
                                    <th>KTP</th>
                                    <th>Surat Izin</th>
                                    <th>Created at</th>
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
      

        $('#date,#date-w').daterangepicker({
            autoUpdateInput: false
        });
        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('best_admin/guest_table/1');?>',
                type : 'POST',
                data: function ( d ) {
                    d.date = $('#date').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "name" },
                { "data": "manager" },
                { "data": "phone" },
                { "data": "email" },
                { "data": "created_at" }
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
                url: '<?php echo site_url('best_admin/detail_table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.team_id = $('#team_id').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "team_name" },
                { "data": "name" },
                { "data": "dob" },
                { "data": "phone" },
                { "data": "email" },
                { "data": "nik" },
                { "data": "gameUsername" },
                { "data": "gameId" },
                { "data": "foto_ktp" },
                { "data": "suratIzin" },
                { "data": "created_at" }
            ],
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5'
            ]
        });
    

        function detailModal(teamId)
            {   
                $('#team_id').val(teamId);
                $('#modalDetail').modal('show');
                table_detail.ajax.reload();
            }
    </script>

</body>
</html>
