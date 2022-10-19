<!doctype html>
<html lang="en" class="fixed">
<head>
<?php echo $this->Templates->Header();?>
</head>
<style type="text/css">
    .chart-container{
        position: relative; width:100%;height:350px;background: #fff;
    }
    .loading-chart{
        text-align: center;
        align-content: center;
        display: none;
    }
</style>
<body>
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>
    
    <?php
    $get_date = $this->input->get('dt');
    if(!empty($get_date)){
        $arr_date = $get_date;
    }else {
         // gmdate('F j, Y', strtotime('first day of january this year'));;
        $arr_date = date("d-m-Y", strtotime('first day of january this year')).' - '.date("d-m-Y", strtotime('last day of december this year'));
    }

    ?>
    <div class="page-body">
        <?php echo $this->Templates->LeftBar();?>
        <div class="content">
            <div class="content-header">
                <div class="leftside-content-header">
                    <ul class="breadcrumbs">
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Dashboard</a></li>
                    </ul>
                </div>
            </div>
            <div class="content-body">
                <div class="row animated fadeInUp">
                    <div class="col-sm-8">
                        <div class="panel panel-default">
                            <div class="panel-header">
                                
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4>Laba Rugi</h4>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="" id="filter_lost_profit" class="form-control dtpicker" placeholder="Filter">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="wait-1" class="loading-chart">
                                    <div>Please Wait</div>
                                    <div class="fa-3x">
                                      <i class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding:0;">
                                    <div id="parent-lost-profit" class="chart-container">
                                        <canvas id="canvas"></canvas>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Harga Jual - Bahan Jadi -->
                    <?php
                        $hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
                        ->from('hpp pp')
                        ->order_by('date_hpp','desc')->limit(1)
                        ->get()->row_array();

                        $harga_jual_abubatu = 0;
                        $harga_jual_batu0510 = 0;
                        $harga_jual_batu1020 = 0;
                        $harga_jual_batu2030 = 0;

                        $harga_jual_abubatu = $hpp['abubatu'] + ($hpp['abubatu'] * 10) / 100;
                        $harga_jual_batu0510 = $hpp['batu0510'] + ($hpp['batu0510'] * 10) / 100;
                        $harga_jual_batu1020 = $hpp['batu1020'] + ($hpp['batu1020'] * 10) / 100;
                        $harga_jual_batu2030 = $hpp['batu2030'] + ($hpp['batu2030'] * 10) / 100;
                    ?>
                    <div class="col-sm-8">
                        <div class="panel panel-default">
                            <div class="panel-header">
                                <div class="row">
                                    <div class="col-sm-12 text-left">
                                        <h4>Harga Jual - Bahan Jadi</h4>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-left" style='background-color:#ffb732; color:black; text-transform:uppercase;' colspan="4"><marquee>HARGA JUAL (TERMASUK LABA 10% DARI HPP DASAR) -  
                                                Harga Update (<?php
                                                    $search = array(
                                                    'January',
                                                    'February',
                                                    'March',
                                                    'April',
                                                    'May',
                                                    'June',
                                                    'July',
                                                    'August',
                                                    'September',
                                                    'October',
                                                    'November',
                                                    'December'
                                                    );
                                                    
                                                    $replace = array(
                                                    'Januari',
                                                    'Februari',
                                                    'Maret',
                                                    'April',
                                                    'Mei',
                                                    'Juni',
                                                    'Juli',
                                                    'Agustus',
                                                    'September',
                                                    'Oktober',
                                                    'November',
                                                    'Desember'
                                                    );
                                                    
		                                            $date2_konversi = date('d F Y', strtotime($hpp['date_hpp']));
                                                    $subject = "$date2_konversi";

                                                    echo str_replace($search, $replace, $subject);

                                                    ?>)</marquee></th>
                                            </tr> 
                                            <tr>
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 0,0 - 0,5</th>
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 0,5 - 10</th> 
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 10 - 20</th> 
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 20 - 30</th>  
                                            </tr> 
                                            <tr>
                                                <th class="text-right"><?php echo number_format($harga_jual_abubatu,0,',','.');?></th>
                                                <th class="text-right"><?php echo number_format($harga_jual_batu0510,0,',','.');?></th> 
                                                <th class="text-right"><?php echo number_format($harga_jual_batu1020,0,',','.');?></th> 
                                                <th class="text-right"><?php echo number_format($harga_jual_batu2030,0,',','.');?></th>  
                                            </tr> 
                                            <tr>
                                                <th class="text-left" style='background-color:#ffb732; color:black; text-transform:uppercase;' colspan="4"><marquee>HPP DASAR -  
                                                Harga Update (<?php
                                                    $search = array(
                                                    'January',
                                                    'February',
                                                    'March',
                                                    'April',
                                                    'May',
                                                    'June',
                                                    'July',
                                                    'August',
                                                    'September',
                                                    'October',
                                                    'November',
                                                    'December'
                                                    );
                                                    
                                                    $replace = array(
                                                    'Januari',
                                                    'Februari',
                                                    'Maret',
                                                    'April',
                                                    'Mei',
                                                    'Juni',
                                                    'Juli',
                                                    'Agustus',
                                                    'September',
                                                    'Oktober',
                                                    'November',
                                                    'Desember'
                                                    );
                                                    
		                                            $date2_konversi = date('d F Y', strtotime($hpp['date_hpp']));
                                                    $subject = "$date2_konversi";

                                                    echo str_replace($search, $replace, $subject);

                                                    ?>)</marquee>
                                            </th>
                                            </tr> 
                                            <tr>
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 0,0 - 0,5</th>
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 0,5 - 10</th> 
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 10 - 20</th> 
                                                <th class="text-center" style='background-color:rgb(0,206,209); color:black'>Batu Split 20 - 30</th>  
                                            </tr> 
                                            <tr>
                                                <th class="text-right"><?php echo number_format($hpp['abubatu'],0,',','.');?></th>
                                                <th class="text-right"><?php echo number_format($hpp['batu0510'],0,',','.');?></th> 
                                                <th class="text-right"><?php echo number_format($hpp['batu1020'],0,',','.');?></th> 
                                                <th class="text-right"><?php echo number_format($hpp['batu2030'],0,',','.');?></th>  
                                            </tr> 
                                            
                                        </table>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pergerakan Bahan Jadi (Penyesuaian Stok) -->
                    <div class="col-sm-8">
                        <div role="tabpanel" class="tab-pane" id="pergerakan_bahan_jadi_penyesuaian">
                            <div class="col-sm-15">
                            <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Nilai Persediaan Bahan Jadi</h3>
                                    </div>
                                    <div style="margin: 20px">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                        <input type="text" id="filter_date_bahan_jadi_penyesuaian" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
                                                    </div>
                                                
                                            </div>
                                        <br />
                                        <div id="wait" style=" text-align: center; align-content: center; display: none;">	
                                            <div>Please Wait</div>
                                            <div class="fa-3x">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </div>				
                                        <div class="table-responsive" id="box-ajax-6c">													
                                        
        
                                        </div>
                                    </div>
                            </div>
                            
                            </div>
                        </div>
                    </div>

                    <!-- Nilai Persediaan Bahan Baku -->
                    <div class="col-sm-8">			
                        <div role="tabpanel" class="tab-pane" id="nilai_persediaan_bahan_baku">
                            <div class="col-sm-15">
                            <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Nilai Persediaan Bahan Baku</h3>
                                    </div>
                                    <div style="margin: 20px">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="text" id="filter_date_nilai" name="filter_date" class="form-control dtpicker"  autocomplete="off" placeholder="Filter By Date">
                                            </div>
                                        </div>
                                        <br />
                                        <div id="wait" style=" text-align: center; align-content: center; display: none;">	
                                            <div>Please Wait</div>
                                            <div class="fa-3x">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </div>				
                                        <div class="table-responsive" id="box-ajax-3">													
                                        
        
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
<script src="<?php echo base_url();?>assets/back/theme/vendor/number_format.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/chart-js/chart.min.js"></script>
    <script type="text/javascript">
        
        $('.dtpicker').daterangepicker({
            autoUpdateInput : false,
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

        function LostProfit(CharData)
        {
            var ctx = document.getElementById('canvas').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: CharData,
                options: {
                    title: {
                        display: true,
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true
                            
                        }],
                        yAxes: [{
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                //min: -1500,
                                //max: 1500
                            },
                        }]
                    },
                    legend: {
                        display: true,
                        position : 'bottom'
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    hoverMode: 'index',
                    tooltips: {
                        callbacks: {
                        title: function(tooltipItem, data) {
                            return data['labels'][tooltipItem[0]['index']];
                        },
                        beforeLabel : function(tooltipItem, data) {
                            //return 'Pendapatan + Persediaan = '+data['datasets'][0]['data_revenue'][tooltipItem['index']]+ ' + '+data['datasets'][0]['data_revenuestok'][tooltipItem['index']]+'';
                            return 'Pendapatan = '+data['datasets'][0]['data_revenue'][tooltipItem['index']];
                        },
                        label: function(tooltipItem, data) {
                            return 'Biaya = '+data['datasets'][0]['data_revenuecost'][tooltipItem['index']];
                        },
                        afterLabel : function(tooltipItem, data) {
                            return 'Laba Rugi = '+data['datasets'][0]['data_laba'][tooltipItem['index']]+ ' ('+data['datasets'][0]['data'][tooltipItem['index']]+'%)';
                        },
                        },
                    }
                }
            });

        }


        function getLostProfit()
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo base_url();?>pmm/db_lost_profit/"+Math.random(),
                dataType : 'json',
                data: {arr_date : $('#filter_lost_profit').val()},
                beforeSend : function(){
                    $('#wait-1').show();
                },
                success : function(result){
                    $('#canvas').remove();
                    $('#parent-lost-profit').append('<canvas id="canvas"></canvas>');
                    LostProfit(result);
                    $('#wait-1').hide();
                }
            });
        }
        getLostProfit();
        $('#filter_lost_profit').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                getLostProfit();
        });
        
    </script>

    <!-- Script Pergerakan Bahan Jadi (Penyesuaian Stok) -->
    <script type="text/javascript">
        $('#filter_date_bahan_jadi_penyesuaian').daterangepicker({
            autoUpdateInput : false,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('#filter_date_bahan_jadi_penyesuaian').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TablePergerakanBahanJadiPenyesuaian();
        });
        
        function TablePergerakanBahanJadiPenyesuaian()
        {
            $('#wait').fadeIn('fast');   
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/reports/nilai_persediaan_bahan_jadi_dashboard'); ?>/"+Math.random(),
                dataType : 'html',
                data: {
                    filter_date : $('#filter_date_bahan_jadi_penyesuaian').val(),
                },
                success : function(result){
                    $('#box-ajax-6c').html(result);
                    $('#wait').fadeOut('fast');
                }
            });
        }

        TablePergerakanBahanJadiPenyesuaian();
        
    </script>

    <!-- Script Nilai Persediaan Bahan Baku -->
    <script type="text/javascript">
    $('#filter_date_nilai').daterangepicker({
    autoUpdateInput : false,
    showDropdowns: true,
    locale: {
        format: 'DD-MM-YYYY'
    },
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(30, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('#filter_date_nilai').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
                TableNilaiPersediaanBarang();
        });


        function TableNilaiPersediaanBarang()
        {
            $('#wait').fadeIn('fast');   
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/reports/nilai_persediaan_bahan_baku_dashboard'); ?>/"+Math.random(),
                dataType : 'html',
                data: {
                    filter_date : $('#filter_date_nilai').val(),
                },
                success : function(result){
                    $('#box-ajax-3').html(result);
                    $('#wait').fadeOut('fast');
                }
            });
        }

        TableNilaiPersediaanBarang();
    
    </script>

</body>
</html>
