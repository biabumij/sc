<!DOCTYPE html>
<html>
	<head>
	  <title>DAFTAR TAGIHAN PEMBELIAN</title>
	  
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
		body {
			font-family: helvetica;
		}

		table.table-border-atas-kiri, th.table-border-atas-kiri, td.table-border-atas-kiri {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-atas-tengah, th.table-border-atas-tengah, td.table-border-atas-tengah {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-atas-kanan, th.table-border-atas-kanan, td.table-border-atas-kanan {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-pojok-kiri, th.table-border-pojok-kiri, td.table-border-pojok-kiri {
			border-top: 1px solid black;
		}

		table.table-border-pojok-tengah, th.table-border-pojok-tengah, td.table-border-pojok-tengah {
			border-top: 1px solid black;
		}

		table.table-border-pojok-kanan, th.table-border-pojok-kanan, td.table-border-pojok-kanan {
			border-top: 1px solid black;
		}

		table tr.table-judul{
			border: 1px solid;
			background-color: #e69500;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
			font-size: 8px;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-size: 8px;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #FFFF00;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}

		table tr.table-total2{
			background-color: #eeeeee;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">DAFTAR TAGIHAN PEMBELIAN</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">DIVISI STONE CRUSHER</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
		<div align="center" style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
		<br /><br /><br />
		<table cellpadding="3" width="98%">
			<tr class="table-total">
				<th align="center" width="5%" class="table-border-atas-kiri">NO.</th>
                <th align="center" width="25%" class="table-border-atas-tengah">REKANAN / NOMOR INVOICE</th>
				<th align="center" width="14%" class="table-border-atas-tengah">TGL. INVOICE</th>
				<th align="center" width="6%" class="table-border-atas-tengah">VOLUME</th>
				<th align="center" width="10%" class="table-border-atas-tengah">SATUAN</th>
				<th align="center" width="10%" class="table-border-atas-tengah">HARGA SATUAN</th>
				<th align="center" width="10%" class="table-border-atas-tengah">DPP</th>
				<th align="center" width="10%" class="table-border-atas-tengah">PPN</th>
				<th align="center" width="10%" class="table-border-atas-kanan">TOTAL</th>
            </tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
						<td align="center"><?php echo $key + 1;?></td>
            			<td align="left"><?php echo $row['nama'];?></td>
            		</tr>
            		<?php
					$jumlah_dpp = 0;
					$jumlah_ppn = 0;
					$jumlah_pph = 0;
					$jumlah_total = 0;
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
							<td align="center"></td>
							<td align="left"><?php echo $mat['nomor_invoice'];?></td>
	            			<td align="center"><?php echo $mat['tanggal_invoice'];?></td>
							<td align="right"><?php echo $mat['volume'];?></td>
							<td align="center"><?php echo $mat['measure'];?></td>
							<td align="right"><?php echo $mat['harsat'];?></td>
							<td align="right"><?php echo $mat['dpp'];?></td>
							<td align="right"><?php echo $mat['tax_ppn'];?></td>
							<td align="right"><?php echo $mat['total'];?></td>
	            		</tr>
            			<?php
						$jumlah_dpp += str_replace(['.', ','], ['', '.'], $mat['dpp']);
						$jumlah_ppn += str_replace(['.', ','], ['', '.'], $mat['tax_ppn']);
						$jumlah_total += str_replace(['.', ','], ['', '.'], $mat['total']);
					}
					?>
					<tr class="table-baris1-bold">
            			<td align="right" colspan="6" class="table-border-pojok-kiri">JUMLAH</td>
						<td align="right" class="table-border-pojok-tengah"><?php echo number_format($jumlah_dpp,0,',','.');?></td>
						<td align="right" class="table-border-pojok-tengah"><?php echo number_format($jumlah_ppn,0,',','.');?></td>
						<td align="right" class="table-border-pojok-kanan"><?php echo number_format($jumlah_total,0,',','.');?></td>
            		</tr>
					<?php
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
            	<th align="right" colspan="6" class="table-border-atas-kiri">TOTAL</th>
            	<th align="right" class="table-border-atas-tengah"><?php echo number_format($total,0,',','.');?></th>
				<th align="right" class="table-border-atas-tengah"><?php echo number_format($total_2,0,',','.');?></th>
				<th align="right" class="table-border-atas-tengah"><?php echo number_format($total_4,0,',','.');?></th>
            </tr>
			
		</table>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<table width="98%">
			<tr >
				<td width="10%"></td>
				<td width="80%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center">
								Disetujui Oleh
							</td>
							<td align="center">
								Diperiksa Oleh
							</td>
							<td align="center">
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
						</tr>
						<tr>
							<td align="center" >
								<b><u>Hadi Sucipto</u><br />
								Kepala Unit Proyek</b>
							</td>
							<td align="center">
								<b><u></u><br />
								M. Keuangan & SDM</b>
							</td>
							<td align="center">
								<b><u></u><br />
								Staff Keuangan & SDM</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="10%"></td>
			</tr>
		</table>
	</body>
</html>