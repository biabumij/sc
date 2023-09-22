<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
</head>

<body>
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>
    
    <?php
    $coa_category = $this->db->get_where('pmm_coa_category',array('status'=>'PUBLISH'))->result_array();
    ?>

    <div class="page-body">
        <?php echo $this->Templates->LeftBar();?>
        <div class="content">
            <div class="content-header">
                <div class="leftside-content-header">
                    <ul class="breadcrumbs">
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a >Daftar Akun</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Daftar Akun</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-2">
                                    <a href="javascript:void(0);" onclick="OpenForm()" class="btn btn-info" style="border-radius:10px; font-weight:bold;"><i class="fa fa-plus"></i> Buat Daftar Akun</a>
                                </div>
                                <form method="GET" target="_blank" action="<?php echo site_url('pmm/reports/client_print');?>">
                                    <div class="col-sm-3">
                                        <select id="filter_category" name="filter_category" class="form-control select2" required="">
                                            <option value="">Pilih Kategori</option>
                                            <?php
                                            if(!empty($coa_category)){
                                                foreach ($coa_category as $key => $coa_c) {
                                                    ?>
                                                    <option value="<?= $coa_c['id'];?>"><?= $coa_c['coa_category'];?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-center" id="guest-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Kode Akun</th>
                                            <th>Kategori Akun</th>
                                            <th>Edit</th>
                                            <th>Hapus</th>
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

    

    <div class="modal fade bd-example-modal-lg" id="modalForm"  role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Buat Daftar Akun</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label>Kategori *</label>
                            <select id="coa_category" name="coa_category" class="form-control select2" required="">
                                <option value="">Pilih Kategori</option>
                                <?php
                                if(!empty($coa_category)){
                                    foreach ($coa_category as $key => $coa_c) {
                                        ?>
                                        <option value="<?= $coa_c['id'];?>"><?= $coa_c['coa_category'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Akun *</label>
                            <input type="text" id="coa" name="coa" class="form-control" required="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Parent</label>
                            <select id="coa_parent" class="form-control select2" name="coa_parent">
                                <option value="">Pilih Parent</option>

                            </select>
                            <input type="hidden" id="coa_parent_val">
                        </div>
                        <div class="form-group">
                            <label>Kode Akun * </label>
                            <input type="text" id="coa_number" name="coa_number" class="form-control"  autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="btn-form" style="font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Kirim</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-weight:bold; border-radius:10px;">Close</button>
                </div>
            </div>
        </div>
    </div>

	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script type="text/javascript">
        $('.select2').select2();
        $('input#contract').number( true, 2,',','.' );
        var table = $('#guest-table').DataTable( {"bAutoWidth": false,
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/finance/table_coa');?>',
                type : 'POST',
                data: function ( d ) {
                    d.filter_category = $('#filter_category').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "coa" },
                { "data": "coa_number" },
                { "data": "coa_category" },
                { "data": "edit" },
                { "data": "delete" },
            ],
            "columnDefs": [
                { "width": "5%", "targets": 0, "className": 'text-center'},
            ],
            responsive: true,
        });


        $('#coa_category').change(function(){
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/finance/get_parent_coa'); ?>/"+Math.random(),
                dataType : 'json',
                data: {id:$(this).val()},
                success : function(result){

                    if(result.output){
                        $('#coa_parent').empty();
                        $('#coa_parent').select2({data:result.output});


                        $('#coa_parent').val($('#coa_parent_val').val()).trigger('change');
                        table.ajax.reload();
                    }else if(result.err){

                    }
                }
            });
        });

        $('#filter_category').change(function(){
            table.ajax.reload();
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
                url     : "<?php echo site_url('pmm/finance/form_coa'); ?>/"+Math.random(),
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
                url     : "<?php echo site_url('pmm/finance/get_coa'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#id').val(result.output.id);
                        $('#coa_category').val(result.output.coa_category).trigger('change');
                        $('#coa_parent_val').val(result.output.coa_parent);
                        $('#coa').val(result.output.coa);
                        $('#coa_number').val(result.output.coa_number);
                        
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }


        function DeleteData(id) {
            bootbox.confirm("Are you sure to delete this data ?", function(result) {
                // console.log('This was logged in the callback: ' + result); 
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('pmm/finance/delete_akun'); ?>",
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        success: function(result) {
                            if (result.output) {
                                table.ajax.reload();
                                bootbox.alert('Berhasil menghapus!!');
                            } else if (result.err) {
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
