<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PENERIMAAN PEMBELIAN</title>
	  
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
		<table width="98%" border="0" cellpadding="15">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PENERIMAAN PEMBELIAN</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">DIVISI BETON  PROYEK BENDUNGAN TEMEF</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>	
		<table cellpadding="2" width="98%">
			<tr class="table-judul">
                <th align="center" width="5%" rowspan="2">&nbsp; <br />NO.</th>
                <th align="center" width="30%">URAIAN</th>
                <th align="center" width="10%" rowspan="2">&nbsp; <br />SATUAN</th>
                <th align="center" width="15%" rowspan="2">&nbsp; <br />VOLUME</th>
				<th align="center" width="20%" rowspan="2">&nbsp; <br />HARGA SATUAN</th>
                <th align="center" width="20%" rowspan="2">&nbsp; <br />NILAI</th>
            </tr>
			<tr class="table-judul">
				<th align="center">PRODUK</th>
			</tr>
            <?php
			$total_vol = 0;
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
            			<td align="center"><?php echo $key + 1;?></td>
            			<td align="left"><?php echo $row['name'];?></td>
            			<td align="center"><?php echo $row['measure'];?></td>
            			<td align="right"><?php echo $row['volume'];?></td>
						<td align="right"></td>
            			<td align="right">
            				<table cellpadding="0" width="100%" border="0">
		    					<tr>
		    						<td width="20%" align="left">Rp.</td>
		    						<td width="80%" align="right"><?php echo $row['total_price'];?></td>
		    					</tr>
		    				</table>
            			</td>
						<?php
						$total_vol += str_replace(['.', ','], ['', '.'], $row['volume']);
						?>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
	            			<td align="center"></td>
	            			<td align="left">&nbsp;&nbsp;&nbsp;<?php echo $mat['nama_produk'];?></td>
	            			<td align="center"><?php echo $mat['measure'];?></td>
	            			<td align="right"><?php echo $mat['volume'];?></td>
							<td align="right">
	            				<table cellpadding="0" width="100%" border="0">
			    					<tr>
			    						<td width="20%" align="left">Rp.</td>
			    						<td width="80%" align="right"><?php echo $mat['price'];?></td>
			    					</tr>
			    				</table>
	            			</td>
	            			<td align="right">
	            				<table cellpadding="0" width="100%" border="0">
			    					<tr>
			    						<td width="20%" align="left">Rp.</td>
			    						<td width="80%" align="right"><?php echo $mat['total_price'];?></td>
			    					</tr>
			    				</table>
	            			</td>
	            		</tr>
            			<?php
            		}
            		
            		
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
				<th align="right" colspan="3">TOTAL</th>
				<th align="right"><?php echo number_format($total_vol,2,',','.');?></th>
				<th align="right"></th>
            	<th align="right">
            		<table cellpadding="0" width="100%" border="0">
    					<tr>
    						<td width="20%" align="left">Rp.</td>
    						<td width="80%" align="right"><?php echo number_format($total,0,',','.');?></td>
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
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" colspan="2">
								Disetujui Oleh
							</td>
							<td align="center" colspan="2">
								Diperiksa Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>	
						</tr>
						<tr class="">
							<td align="center" height="55px">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								
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
								<b><br />
								Ass. Ka. Plant</b>
							</td>
							<td align="center">
								<b><br />
								M. Keu & SDM</b>
							</td>
							<td align="center">
								<b><br />
								M. Teknik</b>
							</td>
							<td align="center">
								<b><u>Agustinus Pakaenoni</u><br />
								Pj. Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>