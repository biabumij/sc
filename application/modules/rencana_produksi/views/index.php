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
                        <li><a><?php echo $row[0]->menu_name;?></a></li>
                    </ul>
                </div>
            </div>

            <?php

                $this->db->select('id,no_spo,schedule_name');
                $this->db->where_in('status',array('WAITING','APPROVED'));
                $arr_schedule = $this->db->get('pmm_schedule')->result_array();
                $suppliers= $this->db->order_by('nama','asc')->get_where('penerima',array('status'=>'PUBLISH','rekanan'=>1))->result_array();
                ?>

            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">
                                <?php echo $row[0]->menu_name;?>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i> Buat Baru <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);" onclick="OpenForm()">Rencana Produksi Baru</a></li>
                                      </ul>
                                </div>        
                            </h3>

                        </div>
                        <div class="panel-content">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Rencana Produksi</a></li>
                            </ul>
                         
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <br />
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="guest-table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th class="text-center">Bulan</th>
                                                    <th class="text-center">Tanggal</th>
                                                    <th>Minggu 1</th>
                                                    <th>Minggu 2</th>
                                                    <th>Minggu 3</th>
                                                    <th>Minggu 4</th>
                                                    <th>Status</th>
                                                    <th>Tindakan</th>
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

<div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Rencana Produksi</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Tanggal *</label>
                            <input type="text" id="schedule_date" name="schedule_date" class="form-control dtpicker" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Bulan *</label>
                            <input type="text" id="no_spo" name="no_spo" class="form-control" required="" autocomplete="off"  />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="btn-form"><i class="fa fa-send"></i> Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalWeek" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Edit Rencana Produksi</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="schedule_id" id="schedule_id">
                        <input type="hidden" name="week" id="week">
                        <div class="table-responsive">
                            <table id="table-week" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>    
                        </div>
                        

                        <div class="text-right">
                            <button type="submit" name="submit" id="btn-w-submit" class="btn btn-primary" style="display:none;">SUBMIT</button>
                            <a href="javascript:void(0);" id="btn-w-approve" class="btn btn-success" style="display:none;">APPROVE</a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


	<?php echo $this->Templates->Footer();?>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">

    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootstrap_date-picker/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/bootstrap_date-picker/css/bootstrap-datepicker3.min.css">



    <script type="text/javascript">
        $('input.numberformat').number( true, 4,',','.' );
        $('.dtpicker').daterangepicker({
            autoUpdateInput: false
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
              table.ajax.reload();
        });

        $('.dtpicker-single').daterangepicker({
                singleDatePicker: true,
                locale: {
              format: 'DD-MM-YYYY'
            }
            });
        $('.dtpicker-single').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY'));
              // table.ajax.reload();
        });

        $("#no_spo").datepicker( {
            format: "mm-yyyy",
            viewMode: "months", 
            minViewMode: "months"
        });
      
        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/production_planning/table');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "schedule_name" },
                { "data": "schedule_date" },
                { "data": "week_1" },
                { "data": "week_2" },
                { "data": "week_3" },
                { "data": "week_4" },
                { "data": "status" },
                { "data": "actions" },
            ],
            "columnDefs": [
                {
                    "targets": [0, 3, 4, 5, 6, 7, 8],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });



        function OpenForm(id='')
        {   
            
            $('#modalForm').modal('show');
            // table_detail.ajax.reload();
            $('#id').val('');
            $("#modalForm form").trigger("reset");
            if(id !== ''){
                $('#id').val(id);
                getData(id);
            }
        }

        function EditWeek(id,week)
        {   
            
            $('#modalWeek').modal('show');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/production_planning/get_by_week'); ?>/"+Math.random(),
                dataType : 'json',
                data: {id:id,week:week},
                success : function(result){
                    $('#table-week thead tr').html('<th>Date</th>');
                    $('#table-week tbody').html('');
                    $('#btn-w-approve').hide();
                    $('#btn-w-submit').hide();
                    $('#schedule_id').val(id);
                    $('#week').val(week);
                    if(result.output){
                        $.each(result.data.products,function(key,val){
                            $('#table-week thead tr').append('<th>'+val.product+'</th>');
                        });
                        var date_pro = {}
                        $.each(result.data.date,function(key,val){
                            $('#table-week tbody').append('<tr id="'+key+'"><td>'+val.date+'</td><tr>');
                            for (var i = 1; i <= result.data.products.length; i++) {
                                $.each(val[i],function(a,b){
                                    $('#table-week tbody tr#'+key).append('<td><input type="number" name="'+a+'_'+val.date_val+'" class="form-control text-center" value="'+b+'" /></td>');
                                });
                                
                            }
                            
                        });

                        if(result.status == 'WAITING'){
                            $('#btn-w-approve').show();
                        }else if(result.status == 'DRAFT'){
                            $('#btn-w-submit').show();
                        }


                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        $('#modalForm form').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/production_planning/form_process'); ?>/"+Math.random(),
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

        $('#modalWeek form').submit(function(event){
            $('#btn-w-submit').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/production_planning/edit_week_process'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-w-submit').button('reset');
                    if(result.output){
                        table.ajax.reload();
                        $('#modalWeek').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        $('#btn-w-approve').click(function(){
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/production_planning/approve_week'); ?>/"+Math.random(),
                dataType : 'json',
                data: {schedule_id:$('#schedule_id').val(),week:$('#week').val()},
                success : function(result){
                    if(result.output){
                        table.ajax.reload();
                        $('#modalWeek').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        });

        function getData(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/production_planning/get_data'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#id').val(result.output.id);
                        $('#no_spo').val(result.output.no_spo);
                        $('#client_id').val(result.output.client_id);
                        $('#schedule_name').val(result.output.schedule_name);
                        $('#schedule_date').val(result.output.schedule_date);

                        // $('#status').val(result.output.status);
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
                        url     : "<?php echo site_url('pmm/production_planning/delete'); ?>",
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
        
    </script>

</body>
</html>
