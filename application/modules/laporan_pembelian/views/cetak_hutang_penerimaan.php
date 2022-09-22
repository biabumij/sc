<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN HUTANG</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN HUTANG</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI BETON  PROYEK BENDUNGAN TEMEF</div>
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
				<th align="center" width="12%">REKANAN</th>
				<th align="center" width="30%" rowspan="2">&nbsp; <br />NO. PESANAN PEMBELIAN</th>
				<th align="center" width="17%" rowspan="2">&nbsp; <br />KETERANGAN</th>
				<th align="center" width="12%" rowspan="2">&nbsp; <br />PENERIMAAN</th>
				<th align="center" width="12%" rowspan="2">&nbsp; <br />PEMBAYARAN</th>
				<th align="center" width="12%" rowspan="2">&nbsp; <br />HUTANG</th>
            </tr>
			<tr class="table-judul">
				<th align="center">TGL. PESANAN PEMBELIAN</th>
			</tr>
            <?php
			$jumlah_tagihan = 0;
			$jumlah_pembayaran = 0;
			$jumlah_hutang = 0;
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
						<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="6"><b><?php echo $row['name'];?></b></td>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
							<td align="center"></td>
	            			<td align="center"><?php echo $mat['date_po'];?></td>
							<td align="left"><?php echo $mat['no_po'];?></td>
							<td align="left"><?php echo $mat['memo'];?></td>            			
							<td align="right"><?php echo $mat['total_price'];?></td>
							<td align="right"><?php echo $mat['pembayaran'];?></td>
							<td align="right"><?php echo $mat['hutang'];?></td>
	            		</tr>
            			<?php
					}
					?>
					<?php

					$jumlah_tagihan += str_replace(['.', ','], ['', '.'], $mat['total_price']);
					$jumlah_pembayaran += str_replace(['.', ','], ['', '.'], $mat['pembayaran']);
					$jumlah_hutang += str_replace(['.', ','], ['', '.'], $mat['hutang']);
					?>
					<tr class="table-baris2-bold">
            			<td align="right" colspan="4"><b>JUMLAH</b></td>
						<td align="right"><b><?php echo $jumlah_tagihan;?></b></td>
						<td align="right"><b><?php echo $jumlah_pembayaran;?></b></td>
						<td align="right"><b><?php echo $jumlah_hutang;?></b></td>
            		</tr>
					<?php
            		}	
            	
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="6" align="center">NO DATA</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
            	<th align="right" width="64%"><b>TOTAL</b></th>
				<th align="right" width="12%"><b><?php echo number_format($grand_total_tagihan,0,',','.');?></b></th>
				<th align="right" width="12%"><b><?php echo number_format($grand_total_pembayaran,0,',','.');?></b></th>
            	<th align="right" width="12%"><b><?php echo number_format($grand_total_hutang,0,',','.');?></b></th>
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
								<b><u>Gervasius K. Limahekin</u><br />
								Ka. Plant</b>
							</td>
							<td align="center" >
								<b><u>Debi Khania</u><br />
								Pj. Keuangan & SDM</b>
							</td>
							<td align="center" >
								<b><u>Debi Khania</u><br />
								Kasir</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>