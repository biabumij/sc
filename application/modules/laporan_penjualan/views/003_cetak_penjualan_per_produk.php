<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PENJUALAN PER PRODUK</title>
	  
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
	<table width="98%" border="0" cellpadding="3">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PENJUALAN PER PRODUK</div>
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
                <th align="center" width="21%" rowspan="2">&nbsp; <br />PRODUK</th>
                <th align="center" width="8%" rowspan="2">&nbsp; <br />SATUAN</th>
				<th align="center" width="33%" colspan="2">KUANTITAS</th>
                <th align="center" width="33%"colspan="2">RUPIAH</th>
            </tr>
			<tr class="table-judul">
				<th align="center" width="10%">TERKIRIM</th>
                <th align="center" width="13%">DIKEMBALIKAN</th>
				<th align="center" width="10%">TERJUAL</th>
				<th align="center" width="10%">TERKIRIM</th>
                <th align="center" width="13%">DIKEMBALIKAN</th>
				<th align="center" width="10%">TERJUAL</th>
            </tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
						<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="8"><b><?php echo $row['nama_produk'];?></b></td>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
							<td align="center"></td>
	            			<td align="left"><?php echo $mat['nama'];?></td>
	            			<td align="center"><?php echo $mat['measure'];?></td>
							<td align="right"><?php echo $mat['terkirim'];?></td>
	            			<td align="right"><?php echo $mat['dikembalikan'];?></td>
							<td align="right"><?php echo $mat['terjual'];?></td>
							<td align="right"><?php echo $mat['terkirim_rp'];?></td>
							<td align="right"><?php echo $mat['dikembalikan_rp'];?></td>
							<td align="right"><?php echo $mat['terjual_rp'];?></td>
	            		</tr>
            			<?php
            		}	
            	}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="9" align="center">NO DATA</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
            	<th align="right" width="88%"><b>TOTAL</b></th>
            	<th align="right" width="12%"><b><?php echo number_format($total,0,',','.');?></b></th>
            </tr>		
		</table>
	</body>
</html>