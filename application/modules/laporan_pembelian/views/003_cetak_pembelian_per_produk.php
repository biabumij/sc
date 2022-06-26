<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PEMBELIAN PER PRODUK</title>
	  
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
		<table width="98%">
			<tr>
				<td width="100%" align="center" cellpadding="0">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PEMBELIAN PER PRODUK</div>
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
				<th align="center" width="5%">NO.</th>
                <th align="center" width="30%">PRODUK</th>
                <th align="center" width="15%">SATUAN</th>
				<th align="center" width="15%">VOLUME</th>
                <th align="center" width="15%">HARGA SATUAN</th>
				<th align="center" width="20%">TOTAL</th>
            </tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1">
						<td align="cengter"><?php echo $key + 1;?></td>
						<td align="left"><?php echo $row['nama_produk'];?></td>
						<td align="center"><?php echo $row['satuan'];?></td>
						<td align="right"><?php echo $row['volume'];?></td>
						<td align="right"><?php echo $row['harga_satuan'];?></td>
						<td align="right"><?php echo $row['total_price'];?></td>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<?php
            		}	
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
            	<th align="right" width="85%"><b>TOTAL</b></th>
            	<th align="right" width="15%"><b><?php echo number_format($total,0,',','.');?></b></th>
            </tr>
			
		</table>
	</body>
</html>