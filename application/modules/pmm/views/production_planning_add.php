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
                        <li>
                            <a href="<?php echo site_url('admin/production_planning');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Jadwal Produksi</a></li>
                        <li><a>Edit Jadwal Produksi</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h4 class="section-subtitle">
                                <?php echo $data['schedule_name'];?>
                            </h4>
                            <div class="">
                                <h3 class=""><?php echo $this->pmm_model->GetStatus($data['status']);?></h3>
                            </div>
                            
                        </div>
                        <div class="panel-content">
                            <h5>Setting Week</h5>
                            <hr />
                            <form id="form-schedule" class="form-horizontal" action="<?php echo site_url('pmm/production_planning/week_process'); ?>" >
                                <input type="hidden" id="schedule_id" name="schedule_id" value="<?php echo $id;?>">
                                <div class="row">
                                    <div class="col-sm-3">
                                            <input type="text" id="week_1" name="week_1" class="form-control dtpicker" required="" autocomplete="off" placeholder="Week 1" value="<?php if(!empty($data['week_1'])) echo $this->pmm_model->ConvertDateSchedule($data['week_1']);?>" />
                                    </div>
                                    <div class="col-sm-3">
                                            <input type="text" id="week_2" name="week_2" class="form-control dtpicker" required="" autocomplete="off" placeholder="Week 2" value="<?php if(!empty($data['week_2'])) echo $this->pmm_model->ConvertDateSchedule($data['week_2']);?>" />
                                    </div>
                                    <div class="col-sm-3">
                                            <input type="text" id="week_3" name="week_3" class="form-control dtpicker" required="" autocomplete="off" placeholder="Week 3" value="<?php if(!empty($data['week_3'])) echo $this->pmm_model->ConvertDateSchedule($data['week_3']);?>" />
                                    </div>
                                    <div class="col-sm-3">
                                            <input type="text" id="week_4" name="week_4" class="form-control dtpicker" required="" autocomplete="off" placeholder="Week 4" value="<?php if(!empty($data['week_4'])) echo $this->pmm_model->ConvertDateSchedule($data['week_4']);?>" />
                                    </div>
                                </div>
                                <br />
                                <?php
                                if($data['status'] == 'DRAFT'){
                                    ?>
                                    <div class="row">
                                        <div class="col-md-offset-9 col-sm-3 text-right">
                                            <button type="submit" class="btn btn-block btn-primary" id="btn-sche"><i class="fa fa-save"></i> Set</button>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                
                            </form>
                            <br />
                            <h5>Setting Product</h5>
                            <hr />
                            <?php
                            if($data['status'] == 'DRAFT'){
                                ?>
                                <form id="form-product" class="form-horizontal" action="<?php echo site_url('pmm/production_planning/product_process'); ?>" >
                                    <input type="hidden" name="schedule_id" value="<?php echo $id;?>">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <select id="product_id" name="product_id" class="form-control" >
                                                <option value="">Pilih Produk</option>
                                                <?php
                                                foreach ($products as $key => $product) {
                                                    ?>
                                                    <option value="<?php echo $product['id'];?>"><?php echo $product['nama_produk'].' ('.$product['nama_kategori_produk'].')';?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                                <button type="submit" class="btn btn-primary btn-block" id="btn-form"><i class="fa fa-send"></i> Submit</button>
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }
                            ?>
                            
                            <br />
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-center text-center" id="guest-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Produk</th>
                                            <th class="text-center">Volume (Ton)</th>
                                            <th class="text-center">Dibuat</th>
                                            <th class="text-center">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right">
                                <a href="<?php echo site_url('admin/rencana_produksi');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Kembali</a>
                                <?php
                                if($data['status'] == 'DRAFT'){
                                    ?>
                                    <a onclick="ProcessForm('<?php echo site_url('pmm/production_planning/schedule_process/'.$id.'/3');?>')" class="btn btn-warning check-btn"><i class="fa fa-send"></i> Kirim Jadwal Produksi</a>
                                    <?php
                                }else if($data['status'] == 'WAITING'){


                                    ?>
                                    <?php
                                    if($this->session->userdata() != 7 || $this->session->userdata() != 8){
                                        ?>
                                        <a onclick="ProcessForm('<?php echo site_url('pmm/production_planning/schedule_process/'.$id.'/1');?>')" class="btn btn-success"><i class="fa fa-check"></i> Setujui</a>
                                        <a onclick="ProcessForm('<?php echo site_url('pmm/production_planning/schedule_process/'.$id.'/2');?>')" class="btn btn-danger check-btn"><i class="fa fa-close"></i> Tolak</a>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
</div>


    <div class="modal fade bd-example-modal-lg" id="modalDetail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Manage Schedule Product Detail</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="title-detail"></h5>
                    <hr />
                    <form action="<?php echo site_url('pmm/production_planning/product_detail_form');?>" method="POST" id="form-man">
                        <input type="hidden" name="schedule_product_id" id="schedule_product_id">
                        <div class="table-responsive">
                            <table id="box-detail" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Week 1</th>
                                        <th class="text-center">Week 2</th>
                                        <th class="text-center">Week 3</th>
                                        <th class="text-center">Week 4</th>
                                    </tr>    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                             <div id="box-week-1"></div>
                                        </td>
                                        <td>
                                             <div id="box-week-2"></div>
                                        </td>
                                        <td>
                                             <div id="box-week-3"></div>
                                        </td>
                                        <td>
                                             <div id="box-week-4"></div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th id="total-week-1" class="text-center">0</th>
                                        <th id="total-week-2" class="text-center">0</th>
                                        <th id="total-week-3" class="text-center">0</th>
                                        <th id="total-week-4" class="text-center">0</th>
                                    </tr>
                                </tfoot>
                            </table>    
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary" id="btn-detail"><i class="fa fa-send"></i> Save</button>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalDetailMaterial" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span id="title-material" class="modal-title">Detail Materials</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="box-detail-materials" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Material</th>
                                <th class="text-center">Koef</th>
                                <th class="text-center">Week 1 (M3)</th>
                                <th class="text-center">Week 2 (M3)</th>
                                <th class="text-center">Week 3 (M3)</th>
                                <th class="text-center">Week 4 (M3)</th>
                                <th class="text-center" colspan="2">Total Materials</th>
                            </tr>    
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var form_control = '';
    </script>

	<?php echo $this->Templates->Footer();?>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">

    <script type="text/javascript">
         $('.dtpicker').daterangepicker({
            autoUpdateInput: false,
            locale: {
              format: 'DD-MM-YYYY'
            },
            minDate : '<?php echo date('d-m-Y',strtotime($schedule_date[0]));?>',
            maxDate : '<?php echo date('d-m-Y',strtotime($schedule_date[1]));?>'
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        });

        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/production_planning/table_schedule_product');?>',
                type : 'POST',
                data: function ( d ) {
                    d.schedule_id = $('#schedule_id').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "product" },
                { "data": "total" },
                { "data": "created_on" },
                { "data": "actions" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                    "width" : '50px'
                },
                {
                    "targets": [1],
                    "className": 'text-left',
                    "width" : '350px'
                }
            ],
        });

        $('#form-schedule').submit(function(event){
            bootbox.confirm("Are you sure ?? Setting Product will be reset", function(result){ 
                if(result){
                    $('#btn-sche').button('loading');
                        $.ajax({
                            type    : "POST",
                            url     : $('#form-schedule').attr('action')+"/"+Math.random(),
                            dataType : 'json',
                            data: $('#form-schedule').serialize(),
                            success : function(result){
                                $('#btn-sche').button('reset');
                                if(result.output){
                                    // $("#form-schedule").trigger("reset");
                                    table.ajax.reload();
                                    bootbox.alert('Succesfully Set Week');
                                }else if(result.err){
                                    bootbox.alert(result.err);
                                }
                            }
                        });
                }
            });
            event.preventDefault();
            
        });

        function ProcessForm(url){
            bootbox.confirm("Are you sure ??", function(result){ 
                if(result){
                    
                    window.location.href = url;
                    // alert($(this).attr('href'));
                    // return true;
                    
                }
            });
        }
            

        $('#form-product').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : $(this).attr('action')+"/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        // $("#form-product").trigger("reset");
                        $('#product_id').val('');
                        $('#activity').val('');
                        table.ajax.reload();
                        // bootbox.alert('Succesfully Set Product !!!');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        

        function FormDetail(id,name)
        {   
            $('#schedule_product_id').val(id);
            $("#modalDetail form").trigger("reset");
            $('#modalDetail').modal('show');
            $('#title-detail').text(name);
            // table_detail.ajax.reload();  
            getDetail(id);
        }

        function DetailMaterial(id,name)
        {   
            $('#modalDetailMaterial').modal('show');
            $('#title-material').text(name);
            // table_detail.ajax.reload();  
            getMaterials(id);
        }

        function getDetail(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/production_planning/get_detail');?>/"+Math.random(),
                dataType : 'json',
                data: {id:id},
                success : function(result){

                    if(result.data){
                        $.each(result.data,function(key,val){
                            $('#box-week-'+val.week).html('');
                            $.each(val.data,function(key_2,d){
                                $('#box-week-'+val.week).append('<p class="text-center">'+d.date_txt+'</p><input type="number" class="form-control text-center" name="date_'+d.id+'" value="'+d.koef+'"  min="0" />');
                            });
                            $('#total-week-'+val.week).text(val.total);
                        });
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        function getMaterials(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/production_planning/get_materials');?>/"+Math.random(),
                dataType : 'json',
                data: {id:id},
                success : function(result){

                    if(result.data){
                        $('#box-detail-materials tbody').html('');
                        if(result.data == ''){
                            $('#box-detail-materials tbody').append('<tr class="text-center"><th colspan="8" class="text-center">-- Tidak Ada Bahan --</th></tr>');
                        }else {


                            $.each(result.data,function(key,val){
                                $('#box-detail-materials tbody').append('<tr class="text-center"><td>'+val.material_name+'</td><td>'+val.measure+'</td><td>'+val.koef+'</td><td>'+val.week_1+'</td><td>'+val.week_2+'</td><td>'+val.week_3+'</td><td>'+val.week_4+'</td><th class="text-center">'+val.total+'</th></tr>');
                                $('#total-week-'+val.week).text(val.total);
                            });
                        }
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        $('#form-man').submit(function(event){
            $('#btn-detail').button('loading');
            $.ajax({
                type    : "POST",
                url     : $(this).attr('action')+"/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-detail').button('reset');
                    if(result.output){
                        table.ajax.reload();
                        $('#modalDetail').modal('hide');
                        // bootbox.alert('Succesfully Set Product !!!');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });


        function DeleteData(id)
        {
            bootbox.confirm("Are you sure to delete this data ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.ajax({
                        type    : "POST",
                        url     : "<?php echo site_url('pmm/production_planning/delete_detail'); ?>",
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
