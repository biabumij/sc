<!DOCTYPE html>
<html>
	<head>
	  <title>DAFTAR PENERIMAAN</title>
	  
	  <?php
		$search = array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'Juei',
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
		<table width="98%" cellpadding="15">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">DAFTAR PENERIMAAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI STONE CRUSHER</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<table cellpadding="3" width="98%">
			<tr class="table-judul">
				<th align="center" width="5%">NO.</th>
				<th align="center" width="20%">PELANGGAN / TGL. BAYAR</th>
				<th align="center" width="20%">NO. TRANSAKSI</th>
				<th align="center" width="20%">TGL. TAGIHAN</th>
				<th align="center" width="20%">NO. TAGIHAN</th>
				<th align="center" width="15%">PENERIMAAN</th>
            </tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
					<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="6"><?php echo $row['nama'];?></td>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
							<td align="center"></td>
	            			<td align="center"><?php echo $mat['tanggal_pembayaran'];?></td>
							<td align="left"><?php echo $mat['nomor_transaksi'];?></td>
							<td align="center"><?php echo $mat['tanggal_invoice'];?></td>
							<td align="left"><?php echo $mat['nomor_invoice'];?></td>            			
							<td align="right"><?php echo $mat['penerimaan'];?></td>
	            		</tr>
            			<?php
            		}	
            	}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="5" align="center">Tidak Ada Data</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
            	<th width="80%" align="right" colspan="4"><b>TOTAL</b></th>
            	<th align="right" width="20%"><?php echo number_format($total,0,',','.');?></th>
            </tr>
			
		</table>
	</body>
</html>