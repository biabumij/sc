<!DOCTYPE html>
<html>
	<head>
	  <title>DAFTAR PEMBAYARAN PEMBELIAN</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 11px;">DAFTAR PEMBAYARAN PEMBELIAN</div>
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
				<th align="center" width="15%">REKANAN</th>
				<th align="center" width="20%" rowspan="2">&nbsp; <br />NO. PEMBAYARAN</th>
				<th align="center" width="20%" rowspan="2">&nbsp; <br />TANGGAL TAGIHAN</th>
				<th align="center" width="20%" rowspan="2">&nbsp; <br />NO. TAGIHAN</th>
				<th align="center" width="20%" rowspan="2">&nbsp; <br />PEMBAYARAN</th>
            </tr>
			<tr class="table-judul">
				<th align="center">TGL. BAYAR</th>
			</tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
						<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="5"><b><?php echo $row['supplier_name'];?></b></td>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
							<td align="center"></td>
	            			<td align="center"><?php echo $mat['tanggal_pembayaran'];?></td>
							<td align="center"><?php echo $mat['nomor_transaksi'];?></td>
							<td align="center"><?php echo $mat['tanggal_invoice'];?></td>
							<td align="center"><?php echo $mat['nomor_invoice'];?></td>            			
							<td align="right"><?php echo $mat['pembayaran'];?></td>
	            		</tr>
            			<?php
            		}	
            	}
            }else {
            	?>
            	<tr>
            		<td width="98%" colspan="6" align="center">NO DATA</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
            	<th width="80%" align="right" colspan="5"><b>TOTAL</b></th>
            	<th align="right" width="20%"><b><?php echo number_format($total,0,',','.');?></b></th>
            </tr>
			
		</table>
		<br />
		<br />
		<table width="98%" border="0" cellpadding="15">
			<tr>
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center">
								Diperiksa Oleh
							</td>
							<td align="center">
								Disetujui Oleh
							</td>
							<td align="center">
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
							<td align="center">
								<b><u>Hadi Sucipto</u><br />
								Ka. Plant</b>
							</td>
							<td align="center">
								<b><br />
								Keuangan Proyek</b>
							</td>
							<td align="center">
								<b><br />
								Admin Keuangan Proyek</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>