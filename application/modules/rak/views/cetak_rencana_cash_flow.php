<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>

	  <style type="text/css">
		 body {
			font-family: helvetica;
		}

		table.table-border-pojok-kiri, th.table-border-pojok-kiri, td.table-border-pojok-kiri {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid #cccccc;
			border-left: 1px solid black;
		}

		table.table-border-pojok-tengah, th.table-border-pojok-tengah, td.table-border-pojok-tengah {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid #cccccc;
		}

		table.table-border-pojok-kanan, th.table-border-pojok-kanan, td.table-border-pojok-kanan {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
			border-right: 1px solid black;
		}

		table.table-border-spesial, th.table-border-spesial, td.table-border-spesial {
			border-left: 1px solid black;
			border-right: 1px solid black;
		}

		table.table-border-spesial-kiri, th.table-border-spesial-kiri, td.table-border-spesial-kiri {
			border-left: 1px solid black;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table.table-border-spesial-tengah, th.table-border-spesial-tengah, td.table-border-spesial-tengah {
			border-left: 1px solid #cccccc;
			border-right: 1px solid #cccccc;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table.table-border-spesial-kanan, th.table-border-spesial-kanan, td.table-border-spesial-kanan {
			border-left: 1px solid #cccccc;
			border-right: 1px solid black;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}

		table tr.table-judul{
			border: 1px solid;
			background-color: #e69500;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
			font-size: 7px;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-size: 7px;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #FFFF00;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}

		table tr.table-total2{
			background-color: #eeeeee;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 12px;">RENCANA CASH FLOW<br/>
		PROYEK BENDUNGAN TEMEF<br/>
		PT. BIA BUMI JAYENDRA<br/></div>
		<br /><br /><br />
		<table width="100%" border="0">
			<?php
			$tanggal = $rak['tanggal_rencana_kerja'];
			$date = date('Y-m-d',strtotime($tanggal));
			?>
			<?php
			function tgl_indo($date){
				$bulan = array (
					1 =>   'Januari',
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
				$pecahkan = explode('-', $date);
				
				// variabel pecahkan 0 = tanggal
				// variabel pecahkan 1 = bulan
				// variabel pecahkan 2 = tahun
			
				return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
				
			}
			?>
			<tr>
				<th width="20%">Tanggal</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?= tgl_indo(date($date)); ?></th>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="5" width="98%">
			<tr class="table-judul">
                <th width="5%" align="center" class="table-border-pojok-kiri">NO.</th>
                <th width="35%" align="center" class="table-border-pojok-tengah">URAIAN</th>
				<th width="60%" align="right" class="table-border-pojok-kanan">NILAI</th>
            </tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">1.</td>
				<td align="left" class="table-border-pojok-tengah">Biaya Bahan</td>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($rak['biaya_bahan'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">2.</td>
				<td align="left" class="table-border-pojok-tengah">Biaya Alat</td>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($rak['biaya_alat'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">3.</td>
				<td align="left" class="table-border-pojok-tengah">Biaya Bank</td>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($rak['biaya_bank'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">4.</td>
				<td align="left" class="table-border-pojok-tengah">Overhead</td>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($rak['overhead'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">5.</td>
				<td align="left" class="table-border-pojok-tengah">Termin</td>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($rak['termin'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">6.</td>
				<td align="left" class="table-border-pojok-tengah">Biaya Persiapan</td>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($rak['biaya_persiapan'],0,',','.'); ?></td>
			</tr>
		</table>
	</body>
</html>