<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
</head>
<!-- <link type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css" rel="stylesheet" /> -->
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />

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
                        <li><a>Jurnal Umum</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Jurnal Umum</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <a href="<?= base_url("pmm/jurnal_umum/tambah_jurnal") ?>" class="btn btn-info" style="border-radius:10px; font-weight:bold;"><i class="fa fa-plus"></i> Buat Jurnal Umum</a>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab" style="border-radius:10px 0px 10px 0px; font-weight:bold;">Jurnal Umum</a></li>
                            </ul>
                         
                            <div class="tab-content">
								<br />
								<div class="col-sm-4">
									<input type="text" id="filter_date_jurnal" name="filter_date" class="form-control dtpickerange" autocomplete="off" placeholder="Filter By Date">
								</div>
								<br />
								<br />
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <h3 class="text-center"></h3>
                                    <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="table_jurnal" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">Tanggal</th>
                                                        <th class="text-center">Nomor</th>
                                                        <th class="text-center">Debit</th>
                                                        <th class="text-center">Kredit</th>
                                                        <th class="text-center">Dibuat Oleh</th>
                                                        <th class="text-center">Dibuat Tanggal</th>
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
    

    
    <script type="text/javascript">
        var form_control = '';
    </script>
    
	<?php echo $this->Templates->Footer(); ?>

    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/jquery.number.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
	
    <script>
    let po = [];
    let dataPo = 0;
    function cekPO(th){
        let lengthDataChecked = $('input:checkbox:checked').length;
        if(lengthDataChecked == 0){
            po = [];
        }
        if($(th).is(":checked")){
            po.push($(th).attr('data-po'));
            if($(th).attr('data-po') != po[0] ){
                $(th).prop("checked", false);
                alert("Nomor PO yang anda pilih tidak sama !");
            }
        }
        else if($(th).is(":not(:checked)")){
                
        }
    }
    // }, 1000);
    </script>
    <script type="text/javascript">

        $('input#contract').number( true, 2,',','.' );
        $('td.numberformat').number( true, 2,',','.' );
		
		$('.dtpicker').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
		});
		$('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('DD-MM-YYYY'));
			// table.ajax.reload();
		});

		$('.dtpickerange').daterangepicker({
			autoUpdateInput: false,
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
			},
            showDropdowns: true,
		});	
		
        var table_jurnal = $('#table_jurnal').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/jurnal_umum/table_jurnal');?>',
                type : 'POST',
				data: function(d) {
                    d.filter_date = $('#filter_date_jurnal').val();
                }
            },
            columns: [
                { "data": "no" },
                { "data": "tanggal"},
                { "data": "nomor" },
                { "data": "total_debit"},
                { "data": "total_kredit"},
                { "data": "admin_name"},
                { "data": "created_on"}
            ],
            "columnDefs": [
                {
                    "targets": [0, 1, 5, 6],
                    "className": 'text-center',
                },
                {
                    "targets": [3, 4],
                    "className": 'text-right',
                }
            ],
            responsive: true,
        });

		$('#filter_date_jurnal').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        table_jurnal.ajax.reload();
		});

        $(function (){
            $("#btn_production").click(function(e){
                var checked = $("#table-production input[type=checkbox]:checked").length;
                
                if (checked > 0) {
                    let data = "";
                    $('input[type=checkbox]').each(function () {
                        data += (this.checked ? ($(this).val()+",") : "");
                    });
                    cekLenth = data.length-1;
                    data = data.substring(0,cekLenth);
                    window.location = "<?= base_url('pmm/finance/penagihan_penjualan/') ?>"+data;
                } else {
                    alert("Anda harus memilih data terlebih dahulu");
                    return false;
                }
               
            });
        });

        // alert("oke");
        


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
                url     : "<?php echo site_url('pmm/form_client'); ?>/"+Math.random(),
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
                url     : "<?php echo site_url('pmm/get_client'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#id').val(result.output.id);
                        $('#client_name').val(result.output.client_name);
                        $('#contract').val(result.output.contract);
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
                        url     : "<?php echo site_url('pmm/delete_client'); ?>",
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
