<!DOCTYPE html>
<html>
    <head>
      <title>LAPORAN PEMAKAIAN MATERIAL</title>
      
      <style type="text/css">
        body{
            font-family: "Open Sans", Arial, sans-serif;
        }
        table.minimalistBlack {
          border: 0px solid #000000;
          width: 98%;
          text-align: left;
        }
        table.minimalistBlack td, table.minimalistBlack th {
          border: 1px solid #000000;
          /*padding: 10px 4px;*/
        }
        table.minimalistBlack tr th {
          /*font-size: 14px;*/
          font-weight: bold;
          color: #000000;
          text-align: center;
        }
        table tr.table-active{
            background-color: #b5b5b5;
        }
        table tr.table-active2{
            background-color: #cac8c8;
        }
        table tr.table-active3{
            background-color: #eee;
        }
        
        hr{
            margin-top:0;
            margin-bottom:30px;
        }
        h3{
            margin-top:0;
        }
      </style>

    </head>
    <body>
        <table width="98%" border="0" cellpadding="3">
            <tr>
                <td align="center">
                    <div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PEMAKAIAN MATERIAL</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI CUTTING STONE</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
                </td>
            </tr>
        </table>
        <br />
        <br />
        <table width="98%" border="0" cellpadding="3">
            <tr class="table-active" style="">
                <td width="50%">
                    <div style="display: block;font-weight: bold;font-size: 10px;">Periode</div>
                </td>
                <td align="right" width="50%">
                    <div style="display: block;font-weight: bold;font-size: 10px;"><?php echo $filter_date;?></div>
                </td>
            </tr>
        </table>
        <br />
        <br />
        <table class="minimalistBlack" cellpadding="2" width="98%">
            <tr class="table-active">
                <th width="5%">No</th>
                <th width="35%">Bahan</th>
                <!--<th width="25%">Supplier</th>-->
                <th width="10%">Satuan</th>
                <th width="20%">Volume</th>
                <th width="30%">Total</th>
            </tr>
            <?php
           
            if(!empty($data)){
                foreach ($data as $key => $row) {
                    ?>
                    <tr class="table-active3">
                        <td align="center"><?php echo $key + 1;?></td>
                        <td align="left"><?php echo $row['tag_name'];?></td>
                        <td align="center"><?php echo $row['measure'];?></td>
                        <td align="center"><?php echo $row['volume'];?></td>
                        <td align="right">
                            <table cellpadding="0" width="100%" border="0">
                                <tr>
                                    <td width="20%" align="left">Rp.</td>
                                    <td width="80%" align="right"><?php echo $row['total'];?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
                    if(!empty($row['supps'])){
                        foreach ($row['supps'] as $sup) {
                            ?>
                            <!--<tr >
                                <td align="center"><?php echo $row['no'].'.'.$sup['no'];?></td>
                                <td></td>
                                <td align="left"><?php echo $sup['supplier'];?></td>
                                <td align="center"><?php echo $row['measure'];?></td>
                                <td align="center"><?php echo $sup['volume'];?></td>
                                <td align="right">
                                    <table cellpadding="0" width="100%" border="0">
                                        <tr>
                                            <td width="20%" align="left">Rp.</td>
                                            <td width="80%" align="right"><?php echo $sup['total'];?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>-->
                            <?php
                        }
                    }
                    
                    
                    
                    
                }
            }else {
                ?>
                <tr>
                    <td width="100%" colspan="6" align="center">No Data</td>
                </tr>
                <?php
            }
            ?>  
            <tr class="table-active3">
                <th width="70%" align="right" colspan="4">TOTAL</th>
                <th align="right" width="30%">
                    <table cellpadding="0" width="100%" border="0">
                        <tr>
                            <td width="20%" align="left">Rp.</td>
                            <td width="80%" align="right"><?php echo number_format($total,2,',','.');?></td>
                        </tr>
                    </table>
                </th>
            </tr>
           
          
        </table>
        <br />
        <br />

        <table width="98%" border="0" cellpadding="0">
            <tr >
                <td width="5%"></td>
                <td width="90%">
                    <table width="100%" border="1" cellpadding="2">
                        <tr class="table-active3">
                            <td align="center" colspan="2">
                                <?php
                                if(!empty($custom_date)){
                                    echo date('d F Y',strtotime($custom_date));
                                }else {
                                    echo date('d F Y');
                                }
                                ?>
                            </td>
                        </tr>
                        <tr class="table-active3">
                            <td align="center" >
                                Dibuat Oleh
                            </td>
                            <td align="center" >
                                Diperiksa Oleh
                            </td>
                        </tr>
                        <tr class="">
                            <td align="center" height="75px">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                        </tr>
                        <tr class="table-active3">
                            <td align="center" >
                                
                            </td>
                            <td align="center" >
                                
                            </td>
                        </tr>
                        <tr class="table-active3">
                            <td align="center" >
                                <b>Adm. Logistik</b>
                            </td>
                            <td align="center" >
                                <b>Ka. Logistik</b>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="5%"></td>
            </tr>
        </table>
        <br />
        <br />
        <br />
        <br />
        <table width="98%" border="0" cellpadding="0">
            <tr >
                <td width="5%"></td>
                <td width="90%">
                    <table width="100%" border="1" cellpadding="2">
                        <tr class="table-active3">
                            <td align="center" >
                                Menyetujui
                            </td>
                            <td align="center" >
                                Mengetahui
                            </td>
                        </tr>
                        <tr class="">
                            <td align="center" height="75px">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                        </tr>
                        <tr class="table-active3">
                            <td align="center" >
                                
                            </td>
                            <td align="center" >
                                Annisa Putri
                            </td>
                        </tr>
                        <tr class="table-active3">
                            <td align="center" >
                                <b>Ka. Plant</b>
                            </td>
                            <td align="center" >
                                <b>Dir. Produksi & Pengembangan</b>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="5%"></td>
            </tr>
        </table>

        

        
    </body>
</html>