<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>Laporan Produksi Stone Crusher</title>
	  
	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
		  text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 1px solid #000000;
		  padding: 5px 4px;
		}
		table.minimalistBlack tr td {
		  /*font-size: 13px;*/
		  text-align:center;
		}
		table.minimalistBlack tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  text-align: center;
		  padding: 10px;
		}
		table tr.table-active{
            background-color: #b5b5b5;
        }
        table tr.table-active2{
            background-color: #cac8c8;
        }
		table tr.table-active3{
            background-color: ;
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
					<div style="display: block;font-weight: bold;font-size: 12px;">LAPORAN PRODUKSI CAMPURAN</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">STONE CRUSHER</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">PT. BIA BUMI JAYENDRA</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<th width="20%">Tanggal</th>
				<th width="2%">:</th>
				<th align="left"><?= convertDateDBtoIndo($row["date_prod"]); ?></th>
			</tr>
			<tr>
				<th>Nomor Produksi</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $row['no_prod'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th align="center" width="5%">No</th>
				<th align="center" width="25%">Nama Komposisi</th>
				<th align="center" width="20%">Uraian</th>
				<th align="center" width="10%">Volume</th>
				<th align="center" width="10%">Satuan</th>
				<th align="center" width="10%">Convert</th>
				<th align="center" width="10%">Volume Convert</th>
				<th align="center" width="10%">Satuan Convert</th>
            </tr>
            <?php
			$subtotal_volume = 0;
			$subtotal_volume_convert = 0;
			?>
			<?php foreach($details as $no => $d) : ?>
				<?php
				?>
				<tr>
					<td class="text-center"><?= $no + 1;?></td>
					<td class="text-center"><?= $d["product_id"] = $this->crud_global->GetField('pmm_agregat',array('id'=>$d['product_id']),'jobs_type') ?></td>								
					<td class="text-center"><?= $d["uraian"]; ?></td>
					<td class="text-center"><?= $d["volume"]; ?></td>
					<td class="text-center"><?= $d["measure"]; ?></td>
					<td class="text-center"><?= $d["convert"]; ?></td>
					<td class="text-center"><?= $d["volume_convert"]; ?></td>
					<td class="text-center"><?= $d["measure_convert"]; ?></td>
				</tr>

				<?php
				$subtotal_volume += $d['volume'];
				$subtotal_volume_convert += $d['volume_convert'];
				?>
			<?php endforeach; ?>
				<tr>
					<th colspan="3" class="text-center">TOTAL</th>
					<th  class="text-center"><?= number_format($subtotal_volume,2,',','.');?></th>
					<th  class="text-center"><?= $d["measure"]; ?></th>
					<th  class="text-center"></th>
					<th  class="text-center"><?= number_format($subtotal_volume_convert,2,',','.');?></th>
					<th  class="text-center"><?= $d["measure_convert"]; ?></th>											
				</tr>
		</table>
		
		<br />
		
	    <p><b>Keterangan</b> :</p>
		<p><?= $row["memo"] ?></p>

		<table width="98%" border="0" cellpadding="0">
            <tr >
                <td width="5%"></td>
                <td width="90%">
                    <table width="100%" border="1" cellpadding="2">
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