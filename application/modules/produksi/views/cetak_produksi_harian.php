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
					<div style="display: block;font-weight: bold;font-size: 12px;">LAPORAN PRODUKSI</div>
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
                <th width="5%">No</th>
                <th width="35%">Nomor Kalibrasi</th>
                <th width="30%">Durasi Produksi</th>
                <th width="30%">Kapasitas Produksi (Ton/Jam)</th>
            </tr>
            <?php
           	$no = 1;
			$total_duration = 0;
			$total_use = 0;
			$total_capacity = 0;
           		foreach ($data as $no => $dt) {
				$total_duration += $dt['duration'];
				$total_use += $dt['use'];
				$total_capacity = ($total_duration!=0)?($total_use / $total_duration)  * 1:0;	
               ?>  
               <tr>
                   <td align="center"><?php echo $no + 1;?></td>
                   <td align="center"><?= $dt["no_kalibrasi"] ?></td>
	               <td align="center"><?= $dt["duration"]; ?></td>
	               <td align="center"><?= $dt["capacity"]; ?></td>
               </tr>
			   <?php	
			}
			    ?> 
				<tr>
					<td align="center" colspan="2"><b>TOTAL</b></td>
					<td align="center"><b><?php echo number_format($total_duration,2,',','.');?></b></td>
					<td align="center"><b><?php echo number_format($total_capacity,2,',','.');?></b></td>
			   </tr>
		</table>
		
		<br />
		
	    <p><b>Keterangan</b> :</p>
		<p><?= $row["memo"] ?></p>

        <table width="98%" border="0" cellpadding="0">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
                                Dibuat Oleh
							</td>
							<td align="center">
								Diperiksa Oleh
							</td>
							<td align="center">
                                Menyetujui
							</td>
                            <td align="center">
                                Mengetahui
							</td>
						</tr>
						<tr class="">
							<td align="center" height="55px">
							
							</td>
							<td align="center">
							
							</td>
							<td align="center">
							
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u></u><br />
								Adm. Logistik</b>
							</td>
							<td align="center">
								<b><u>Vicky Irwana Yudha</u><br />
								Ka. Logistik</b>
							</td>
							<td align="center" >
								<b><u>Hadi Sucipto</u><br />
								Ka. Unit Bisnis</b>
							</td>
                            <td align="center" >
								<b><u>Annisa Putri</u><br />
								Dir. Produksi & Pengembangan</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>

	</body>
</html>