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
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Shoutcaster Competition</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Shoutcaster Registration</h3>
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
                                            <th>Nickname</th>
                                            <th>Gender</th>
                                            <th>DOB</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>NIK</th>
                                            <th>Link Video</th>
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
        <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
</div>
    


	<?php echo $this->Templates->Footer();?>

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
                url: '<?php echo site_url('best_admin/shoutcaster_table');?>',
                type : 'POST',
                data: function ( d ) {
                    d.date = $('#date').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "name" },
                { "data": "nickname" },
                { "data": "gender" },
                { "data": "dob" },
                { "data": "phone" },
                { "data": "email" },
                { "data": "nik" },
                { "data": "link_video" },
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

    </script>

</body>
</html>
