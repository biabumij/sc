<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PIUTANG</title>
	  
	  <?php
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
		
		$subject = "$filter_date";

		echo str_replace($search, $replace, $subject);

	  ?>
	  
	  <style type="text/css">
		table tr.table-judul{
			background-color: #e69500;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: #F0F0F0;
			font-size: 8px;
		}

		table tr.table-baris1-bold{
			background-color: #F0F0F0;
			font-size: 8px;
			font-weight: bold;
		}
			
		table tr.table-baris2{
			font-size: 8px;
			background-color: #E8E8E8;
		}

		table tr.table-baris2-bold{
			font-size: 8px;
			background-color: #E8E8E8;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<table width="98%">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PIUTANG</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI STONE CRUSHER</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="3" width="98%">
			<tr class="table-judul">
				<th align="center" width="5%" rowspan="2">&nbsp; <br />NO.</th>
				<th align="center" width="17%">PELANGGAN</th>
				<th align="center" width="17%" rowspan="2">&nbsp; <br />NO. TAGIHAN</th>
				<th align="center" width="25%" rowspan="2">&nbsp; <br />KETERANGAN</th>
				<th align="center" width="12%" rowspan="2">&nbsp; <br />TAGIHAN</th>
				<th align="center" width="12%" rowspan="2">&nbsp; <br />PENERIMAAN</th>
				<th align="center" width="12%" rowspan="2">&nbsp; <br />SISA PIUTANG</th>
            </tr>
			<tr class="table-judul">
				<th align="center">TGL. INVOICE</th>
			</tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
					
            		<tr class="table-baris1-bold">
						<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="6"><b><?php echo $row['nama'];?></b></td>
            		</tr>
            		<?php
					$jumlah_tagihan = 0;
					$jumlah_penerimaan = 0;
					$jumlah_piutang = 0;
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
							<td align="center"></td>
	            			<td align="center"><?php echo $mat['tanggal_invoice'];?></td>
							<td align="left"><?php echo $mat['nomor_invoice'];?></td>
							<td align="left"><?php echo $mat['memo'];?></td>            			
							<td align="right"><?php echo $mat['tagihan'];?></td>
							<td align="right"><?php echo $mat['pembayaran'];?></td>
							<td align="right"><?php echo $mat['piutang'];?></td>
	            		</tr>
            			<?php
						$jumlah_tagihan += str_replace(['.', ','], ['', '.'], $mat['tagihan']);
						$jumlah_penerimaan += str_replace(['.', ','], ['', '.'], $mat['pembayaran']);
						$jumlah_piutang += str_replace(['.', ','], ['', '.'], $mat['piutang']);
					}
					?>
					<tr class="table-baris2-bold">
            			<td align="right" colspan="4">JUMLAH</td>
						<td align="right"><b><?php echo number_format($jumlah_tagihan,0,',','.');?></b></td>
						<td align="right"><b><?php echo number_format($jumlah_penerimaan,0,',','.');?></b></td>
						<td align="right"><b><?php echo number_format($jumlah_piutang,0,',','.');?></b></td>
            		</tr>
					<?php
            		}	
            	
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="7" align="center">NO DATA</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
            	<th align="right" width="88%">TOTAL</th>
            	<th align="right" width="12%"><?php echo number_format($total,0,',','.');?></th>
            </tr>
		</table>
		<br />
		<br />
		<table width="98%" border="0" cellpadding="15">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center" >
								Diperiksa Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>
						</tr>
						<tr>
							<td align="center" height="40px">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								
							</td>
						</tr>
						<tr>
							<td align="center" >
								<b><u>Hadi Sucipto</u><br />
								Ka. Unit Bisnis</b>
							</td>
							<td align="center" >
								<b><u>Rifka Dian B.</u><br />
								Pj. Keuangan & SDM</b>
							</td>
							<td align="center" >
								<b><u>Dian Melinda S.</u><br />
								Staff Keuangan & SDM</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>