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
                    
                    <!-- Laba Rugi -->
                    <?php include_once("script_dashboard.php"); ?>
                    <div class="col-sm-12">
                        <figure class="highcharts-figure">
                            <div id="container_laba_rugi"></div>
                            
                        </figure>
                        <br />
                    </div>
                    
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
                                [0, 'rgb(204,204,204)'],
                                [1, 'rgb(204,204,204)']
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
                        categories: ['Jan 23','Feb 23','Mar 23','Apr 23','Mei 23','Jun 23','Jul 23','Akumulasi']
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
                        min: -100,
                        max: 100,
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
                        
                        data: [0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00],

                        color: '#000000',
                        fontWeight: 'bold',
                        fontSize: '10px',
                        fontFamily: 'helvetica'
                    },
                    {  
                        name: 'Laba Rugi %',  
                        
                        data: [ <?php echo number_format($persentase_jan_fix,0,',','.');?>,<?php echo number_format($persentase_feb_fix,0,',','.');?>,<?php echo number_format($persentase_mar_fix,0,',','.');?>,<?php echo number_format($persentase_apr_fix,0,',','.');?>,<?php echo number_format($persentase_mei_fix,0,',','.');?>,<?php echo number_format($persentase_jun_fix,0,',','.');?>,<?php echo number_format($persentase_jul_fix,0,',','.');?>,<?php echo number_format($persentase_aku_fix,0,',','.');?>],

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
