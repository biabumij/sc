<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PESANAN PEMBELIAN</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PESANAN PEMBELIAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI STONE CRUSHER</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<table cellpadding="3" width="98%">
			<tr class="table-judul">
                <th align="center" width="5%" rowspan="2">&nbsp; <br />NO.</th>
                <th align="center" width="20%">REKANAN</th>
				<th align="center" width="15%" rowspan="2">&nbsp; <br />NO. PEMESANAN</th>
                <th align="center" width="10%" rowspan="2">&nbsp; <br />PRODUK</th>
                <th align="center" width="5%" rowspan="2">&nbsp; <br />SATUAN</th>
				<th align="center" width="10%" rowspan="2">&nbsp; <br />HARGA SATUAN</th>
                <th align="center" width="8%" rowspan="2">&nbsp; <br />VOLUME</th>
				<th align="center" width="10%" rowspan="2">&nbsp; <br />PPN</th>
                <th align="center" width="10%" rowspan="2">&nbsp; <br />TOTAL</th>
				<th align="center" width="7%" rowspan="2">&nbsp; <br />STATUS</th>
            </tr>
			<tr class="table-judul">
				<th align="center">TGL. PESAN</th>
			</tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
            			<td align="center"><b><?php echo $key + 1;?></b></td>
            			<td align="left" colspan="10"><b><?php echo $row['nama'];?></b></td>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
	            			<td align="center"></td>
							<td align="left"><?php echo $mat['date_po'];?></td>
							<td align="left"><?php echo $mat['no_po'];?></td>
	            			<td align="left"><?php echo $mat['nama_produk'];?></td>
	            			<td align="center"><?php echo $mat['measure'];?></td>
							<td align="right"><?php echo $mat['price'];?></td>
	            			<td align="right"><?php echo $mat['volume'];?></td>
							<td align="right"><?php echo $mat['ppn'];?></td>
							<td align="right"><?php echo $mat['total_price'];?></td>
							<td align="right"><b><?php echo $mat['status'];?></b></td>
	            		</tr>					
            			<?php						
            		}
					?>
					<tr class="table-baris2-bold">
            			<td align="right" colspan="8">JUMLAH</td>
						<td align="right"><?php echo $row['jumlah'];?></td>
						<th align="center"></th>
            		</tr>				
				<?php					
            	}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="11" align="center">NO DATA</td>
            	</tr>
            	<?php
            }
            ?>
           <tr  class="table-total">
            	<th align="right "colspan="8">TOTAL</th>
            	<th align="right"><?php echo number_format($total,0,',','.');?></th>
				<th align="center"></th>
            </tr>
			
		</table>
		<table width="98%" border="0" cellpadding="15">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center">
								Diperiksa oleh
							</td>
							<td align="center" >
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
							<td align="center" >
								<b><br />
								Logistik</b>
							</td>
							<td align="center" >
								<b><u>Hadi Sucipto</u><br />
								Ka. Plant</b>
							</td>
							<td align="center" >
								<b><br />
								Admin Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>