<!doctype html>
<html lang="en" class="fixed">
<head>
<?php echo $this->Templates->Header();?>
</head>
<style type="text/css">
    .chart-container{
        position: relative; max-width: 100%; height:350px; background: #fff;
    }
    .highcharts-figure,
    .highcharts-data-table table {
    min-width: 65%;
    max-width: 100%;
    }

    .highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
    }

    .highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
    }

    .highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
    padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
    background: #f1f7ff;
    }
</style>
<body>
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>
    
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

                    <?php include_once("script_dashboard.php"); ?>

                    <div class="col-sm-12">
                        <figure class="highcharts-figure">
                            <div id="container_laba_rugi"></div>
                            
                        </figure>
                        <br />
                    </div>

                    <!-- RAP -->
                    <div class="col-sm-8">			
                        <div role="tabpanel" class="tab-pane" id="rap">
                            <div class="col-sm-15">
                            <div class="panel">
                                    <div class="panel-heading">
                                        <center><h3 class="panel-title">RAP</h3></center>
                                    </div>
                                    <div style="margin: 20px">
                                        <div id="wait" style=" text-align: center; align-content: center; display: none;">	
                                            <div>Please Wait</div>
                                            <div class="fa-3x">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </div>				
                                        <div class="table-responsive" id="box-new-rap">
        
                                        </div>
                                    </div>
                            </div>
                            
                            </div>
                        </div>
                    </div>

                    <!--<div class="col-sm-8">
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
                    </div>-->

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
                    <!--<div class="col-sm-8">
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
                                                <th class="text-left" style='background-color:#5B5B5B; color:white; text-transform:uppercase;' colspan="4">HARGA JUAL (TERMASUK LABA 10% DARI HPP DASAR) -  
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

                                                    ?>)</th>
                                            </tr> 
                                            <tr>
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 0,0 - 0,5</th>
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 0,5 - 10</th> 
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 10 - 20</th> 
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 20 - 30</th>  
                                            </tr> 
                                            <tr>
                                                <th class="text-right"><?php echo number_format($harga_jual_abubatu,0,',','.');?></th>
                                                <th class="text-right"><?php echo number_format($harga_jual_batu0510,0,',','.');?></th> 
                                                <th class="text-right"><?php echo number_format($harga_jual_batu1020,0,',','.');?></th> 
                                                <th class="text-right"><?php echo number_format($harga_jual_batu2030,0,',','.');?></th>  
                                            </tr> 
                                            <tr>
                                                <th class="text-left" style='background-color:#5B5B5B; color:white; text-transform:uppercase;' colspan="4">HPP DASAR -  
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

                                                    ?>)
                                            </th>
                                            </tr> 
                                            <tr>
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 0,0 - 0,5</th>
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 0,5 - 10</th> 
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 10 - 20</th> 
                                                <th class="text-center" style='background-color:rgb(188,188,188); color:black'>Batu Split 20 - 30</th>  
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
                    </div>-->

                    <!-- Pergerakan Bahan Jadi (Penyesuaian Stok) -->
                    <!--<div class="col-sm-8">
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
                    </div>-->

                    <!-- Nilai Persediaan Bahan Baku -->
                    <!--<div class="col-sm-8">			
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
                    </div>-->

                </div>  
            </div>
        
    </div>
</div>

<?php echo $this->Templates->Footer();?>
<script src="<?php echo base_url();?>assets/back/theme/vendor/toastr/toastr.min.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/chart-js/chart.min.js"></script>
<script src="<?php echo base_url();?>assets/back/theme/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

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

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


    <script type="text/javascript">
        $(function () {
            var chart;
            $(document).ready(function() {
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'container_laba_rugi',
                        type: 'line',
                        marginRight: 130,
                        marginBottom: 75,
                        backgroundColor: {
                            //linearGradient: [500, 0, 0, 700],
                            linearGradient: [0, 0, 700, 500],
                            stops: [
                                [0, 'rgb(210,235,153)'],
                                [1, 'rgb(150,194,131)']
                            ]
                        },
                    },
                    title: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '14px',
                            fontFamily: 'helvetica'
                        },
                        text: 'LABA RUGI',
                        x: -20 //center            
                    },
                    subtitle: {
                        style: {
                            color: '#000000',
                            fontWeight: 'bold',
                            fontSize: '14px',
                            fontFamily: 'helvetica'
                        },
                        text: 'PT. BIA BUMI JAYENDRA - SC (<?php echo $date_now = date('Y', strtotime($date_now));?>)',
                        x: -20
                    },
                    xAxis: { //X axis menampilkan data bulan
                        labels: {
                            style: {
                                color: '#000000',
                                fontWeight: 'bold',
                                fontSize: '10px',
                                fontFamily: 'helvetica'
                            }
                        },
                        categories: ['Jan 23','Feb 23','Mar 23','Apr 23','Mei 23','Jun 23','Jul 23','Agu 23','Sep 23','Okt 23','Nov 23','Des 23','Akumulasi']
                    },
                    yAxis: {
                        //title: {  //label yAxis
                            //text: 'RAP <br /><?php echo number_format(0,0,',','.'); ?>'
                            //text: 'Presentase'
                        //},
                        title: {
                            style: {
                                color: '#000000',
                                fontWeight: 'bold',
                                fontSize: '10px',
                                fontFamily: 'helvetica'
                            },
                            text: 'Presentase'           
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080' //warna dari grafik line
                        }],
                        labels: {
                            style: {
                                color: '#000000',
                                fontWeight: 'bold',
                                fontSize: '10px',
                                fontFamily: 'helvetica'
                            },
                            format: '{value} %'
                        },
                        min: -200,
                        max: 200,
                        tickInterval: 20,
                    },
                    tooltip: { 
                    //fungsi tooltip, ini opsional, kegunaan dari fungsi ini 
                    //akan menampikan data di titik tertentu di grafik saat mouseover
                        formatter: function() {
                                return '<b>'+ this.series.name +'</b><br/>'+ 
                                ''+ 'Presentase' +': '+ this.y + '%<br/>';
                                //''+ 'Vol' +': '+ this.x + '';

                                //'<b>'+ 'Presentase' +': '+ this.y +'%'</b><br/>'+ 
                                //'<b>'+ 'Penjualan' +': '+ this.y +'</b><br/>';
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: -10,
                        y: 100,
                        borderWidth: 0
                    },

                    plotOptions: {
                        spline: {
                            lineWidth: 4,
                            states: {
                                hover: {
                                    lineWidth: 5
                                }
                            },
                            marker: {
                                enabled: true
                            }
                        }
                    },
            
                    series: [{  
                        name: 'Target %',  
                        
                        data: [0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00],

                        color: '#000000',
                        fontWeight: 'bold',
                        fontSize: '10px',
                        fontFamily: 'helvetica'
                    },
                    {  
                        name: 'Laba Rugi %',  
                        
                        data: [ <?php echo number_format($persentase_jan_fix,0,',','.');?>,<?php echo number_format($persentase_feb_fix,0,',','.');?>,<?php echo number_format($persentase_mar_fix,0,',','.');?>,<?php echo number_format($persentase_apr_fix,0,',','.');?>,<?php echo number_format($persentase_mei_fix,0,',','.');?>,<?php echo number_format($persentase_jun_fix,0,',','.');?>,<?php echo number_format($persentase_jul_fix,0,',','.');?>,<?php echo number_format($persentase_agu_fix,0,',','.');?>,<?php echo number_format($persentase_sep_fix,0,',','.');?>,<?php echo number_format($persentase_okt_fix,0,',','.');?>,<?php echo number_format($persentase_nov_fix,0,',','.');?>,<?php echo number_format($persentase_des_fix,0,',','.');?>,<?php echo number_format($persentase_aku_fix,0,',','.');?>],

                        color: '#FF0000',
                        fontWeight: 'bold',
                        fontSize: '10px',
                        fontFamily: 'helvetica',

                        zones: [{
                            
                        }, {
                            dashStyle: 'dot'
                        }]
                    }
                    ]
                });
            });
            
        });
    </script>

    <!-- Script RAP -->
    <script type="text/javascript">
        $('#filter_date_new_rap').daterangepicker({
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

        $('#filter_date_new_rap').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            TableNewRAP();
        });
        
        function TableNewRAP()
        {
            $('#wait').fadeIn('fast');   
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/reports/new_rap_dashboard'); ?>/"+Math.random(),
                dataType : 'html',
                data: {
                    filter_date : $('#filter_date_new_rap').val(),
                },
                success : function(result){
                    $('#box-new-rap').html(result);
                    $('#wait').fadeOut('fast');
                }
            });
        }

        TableNewRAP();
        
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

    <!-- Script Laba Rugi Old -->
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

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/64e6c76694cf5d49dc6c2cd7/1h8inlra1';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->

</body>
</html>
