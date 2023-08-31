<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>EVALUASI SUPPLIER</title>
	  
	  <style type="text/css">
		body {
			font-size: 8.5px;
			font-family: "Open Sans", Arial, sans-serif;
		}
	  </style>

	</head>
	<body>
	<br />
		<table width="98%" border="1" cellpadding="3">
			<tr>
				<td align="center" rowspan="4" width="70%" style="font-weight:bold; font-size:14px;">&nbsp; <br />EVELUASI SUPPLIER</td>
				<td align="left" width="15%">Nama Dok.</td>
				<td align="left" width="15%">FM-ES</td>
			</tr>
			<tr>
				<td align="left">No. Dokumen</td>
				<td align="left">-</td>
			</tr>
			<tr>
				<td align="left">No. Revisi</td>
				<td align="left">0.1</td>
			</tr>
			<tr>
				<td align="left">Tanggal</td>
				<td align="left"><?= date('d/m/Y',strtotime($row['tanggal']));?></td>
			</tr>
		</table>
		<br />
		<br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td align="center" width="3%">1.</td>
				<td align="left" width="15%">Nama Supplier</td>
				<td align="center" width="3%">:</td>
				<td align="left" width="25%"><?php echo $this->crud_global->GetField('penerima',array('id'=>$row['supplier_id']),'nama');?></td>
				<td align="left" width="3%">4.</td>
				<td align="left" width="15%">Nama Kontak</td>
				<td align="left" width="3%">:</td>
				<td align="left" width="25%"><?= $row['nama_kontak']?></td>
			</tr>
			<tr>
				<td align="center" width="3%">2.</td>
				<td align="left" width="15%">Bidang Usaha</td>
				<td align="center" width="3%">:</td>
				<td align="left" width="25%"><?= $row['bidang_usaha']?></td>
				<td align="left" width="3%">5.</td>
				<td align="left" width="15%">Nomor Kontak</td>
				<td align="left" width="3%">:</td>
				<td align="left" width="25%"><?= $row['nomor_kontak']?></td>
			</tr>
			<tr>
				<td align="center" width="3%">3.</td>
				<td align="left" width="15%">Alamat</td>
				<td align="center" width="3%">:</td>
				<td align="left" width="25%"><?= $row['alamat_supplier']?></td>
				<td align="left" width="3%">5.</td>
				<td align="left" width="15%">Email</td>
				<td align="left" width="3%">:</td>
				<td align="left" width="25%"><?= $row['email']?></td>
			</tr>
		</table>
		<br />
		<br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td align="left" style="font-weight:bold;">Berikan tanda centang (V) pada kolom dibawah.</td>
			</tr>
		</table>	
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<th align="center" width="5%" rowspan="2" style="font-weight:bold; background-color:#eeeeee; border:1px solid black;">&nbsp; <br />No.</th>
				<th align="center" width="40%" rowspan="2" style="font-weight:bold; background-color:#eeeeee; border-top:1px solid black; border-bottom:1px solid black;  border-right:1px solid black;">&nbsp; <br />Kriteria Evaluasi</th>
				<th align="center" width="8%" style="font-weight:bold; background-color:#6aa84f; border-top:1px solid black; border-bottom:1px solid black;  border-right:1px solid black;">Puas</th>
				<th align="center" width="8%" style="font-weight:bold; background-color:#93c47d; border-top:1px solid black; border-bottom:1px solid black;  border-right:1px solid black;">Baik</th>
				<th align="center" width="8%" style="font-weight:bold; background-color:#ffd966; border-top:1px solid black; border-bottom:1px solid black;  border-right:1px solid black;">Cukup</th>
				<th align="center" width="8%" style="font-weight:bold; background-color:#f1c232; border-top:1px solid black; border-bottom:1px solid black;  border-right:1px solid black;">Kurang</th>
				<th align="center" width="8%" style="font-weight:bold; background-color:#e06666; border-top:1px solid black; border-right:1px solid black; border-bottom:1px solid black;">Buruk</th>
				<th align="center" width="15%" rowspan="2" style="font-weight:bold; background-color:#eeeeee; border:1px solid black;">Catatan</th>
            </tr>
			<tr>
				<td align="center" style="font-weight:bold; background-color:#6aa84f; border-bottom:1px solid black;  border-right:1px solid black;">5</td>
				<td align="center" style="font-weight:bold; background-color:#93c47d; border-bottom:1px solid black;  border-right:1px solid black;">4</td>
				<td align="center" style="font-weight:bold; background-color:#ffd966; border-bottom:1px solid black;  border-right:1px solid black;">3</td>
				<td align="center" style="font-weight:bold; background-color:#f1c232; border-bottom:1px solid black;  border-right:1px solid black;">2</td>
				<td align="center" style="font-weight:bold; background-color:#e06666; border-bottom:1px solid black;  border-right:1px solid black;">1</td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">1.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Kualtias barang atau jasa</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_1']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_1']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_1']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_1']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_1']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_1'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">2.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Ketepatan waktu</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_2']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_2']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_2']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_2']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_2']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_2'] ?></td>
			</tr>	
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">3.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Harga dibandingkan kualitas</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_3']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_3']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_3']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_3']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_3']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_3'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">4.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Kesadaran terhadap keselamatan dan kesehatan kerja</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_4']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_4']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_4']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_4']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_4']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_4'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">5.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Kesadaran terhadap lingkungan</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_5']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_5']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_5']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_5']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_5']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_5'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">6.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Kompetensi tenaga kerja</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_6']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_6']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_6']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_6']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_6']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_6'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">7.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Kemampuan menangani keluhan</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_7']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_7']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_7']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_7']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_7']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_7'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">8.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Layanan purnajual</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_8']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_8']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_8']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_8']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_8']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_8'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">9.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Kelengkapan dokumen</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_9']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_9']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_9']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_9']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_9']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_9'] ?></td>
			</tr>
			<tr>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">10.</td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;">Kinerja sebelumnya</td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['puas_10']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['baik_10']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['cukup_10']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['kurang_10']);?></td>
				<td align="center" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $this->pmm_finance->CheckorNoNew($row['buruk_10']);?></td>
				<td align="left" style="border-left:1px solid black; border-bottom:1px solid black; border-right:1px solid black;"><?= $row['catatan_10'] ?></td>
			</tr>
		</table>
		<br /><br />
		<?php
		$total = $row['puas_1'] + $row['puas_2'] + $row['puas_3'] + $row['puas_4'] + $row['puas_5'] + $row['puas_6'] + $row['puas_7'] + $row['puas_8'] + $row['puas_9'] + $row['puas_10'] + $row['baik_1'] + $row['baik_2'] + $row['baik_3'] + $row['baik_4'] + $row['baik_5'] + $row['baik_6'] + $row['baik_7'] + $row['baik_8'] + $row['baik_9'] + $row['baik_10'] + $row['cukup_1'] + $row['cukup_2'] + $row['cukup_3'] + $row['cukup_4'] + $row['cukup_5'] + $row['cukup_6'] + $row['cukup_7'] + $row['cukup_8'] + $row['cukup_9'] + $row['cukup_10'] + $row['kurang_1'] + $row['kurang_2'] + $row['kurang_3'] + $row['kurang_4'] + $row['kurang_5'] + $row['kurang_6'] + $row['kurang_7'] + $row['kurang_8'] + $row['kurang_9'] + $row['kurang_10'] + $row['buruk_1'] + $row['buruk_2'] + $row['buruk_3'] + $row['buruk_4'] + $row['buruk_5'] + $row['buruk_6'] + $row['buruk_7'] + $row['buruk_8'] + $row['buruk_9'] + $row['buruk_10'];
		?>
		<table width="98%" border="0" cellpadding="1">
			<tr>
				<td width="20%" rowspan="2">Perhitungan nilai evaluasi:</td>
				<td width="80%"><u><?php echo number_format($total,0,',','.');?> x 100</u> = <?php echo number_format(($total * 100) / 50,0,',','.');?></td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;50</td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td align="center" style="border:1px solid black;" width="3%"><?= $this->pmm_finance->CheckorNoNew2(($total * 100) / 50);?></td>
				<td width="97%">> 65 Memenuhi syarat</td>
			</tr>
		</table>
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td align="center" style="border:1px solid black;" width="3%"><?= $this->pmm_finance->CheckorNoNew3(($total * 100) / 50);?></td>
				<td width="97%">> 56 - 65 Memenuhi syarat dengan catatan perbaikan:<?php echo number_format($total,0,',','.');?></td>
			</tr>
			<tr>
				<td align="left" width="3%"></td>
				<td align="left"><u><?= $row['memo'] ?></u></td>
			</tr>
		</table>
		<table width="98%" border="0" cellpadding="3">	
			<tr>
				<td align="center" style="border:1px solid black;" width="3%"><?= $this->pmm_finance->CheckorNoNew4(($total * 100) / 50);?></td>
				<td width="97%">< 55 Tidak memenuhi syarat</td>
			</tr>
		</table>
		<br /><br />
		<?php
			$this->db->select('a.admin_name, g.admin_group_name, a.admin_ttd');
			$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
			$this->db->where('a.admin_id',$row['created_by']);
			$created = $this->db->get('tbl_admin a')->row_array();
		?>
		<table width="98%" border="1" cellpadding="3">	
			<tr>
				<td align="left" width="25%">Lokasi <br /><br /> Menara Bidakara 1 Lt.2, Jl. Jend. Gatot Subrot0 Kav 71-73, Tebet, Jakarta Selatan</td>
				<td align="left" width="25%">Disetujui: <br /><br /><br />
				Nama:<br />
				Jabatan:</td>
				<td align="left" width="25%">Diperiksa: <br /><br /><br />
				Nama:<br />
				Jabatan:</td>
				<td align="left" width="25%">Dibuat: <br />
				<img src="<?= $created['admin_ttd']?>" width="70px"><br />
				Nama: <?= $created['admin_name']?><br />
				Jabatan: <?= $created['admin_group_name']?></td>
			</tr>
		</table>
	</body>
</html>