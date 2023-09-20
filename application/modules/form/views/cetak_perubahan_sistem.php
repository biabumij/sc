<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>

	  <style type="text/css">
		 body {
			font-family: helvetica;
			font-size: 8px;
		}
	  </style>

	</head>
	<body>
		<div align="center" style="display: block;font-weight: bold;font-size: 12px; text-decoration:underline;">FORM PERMINTAAN PERUBAHAN SISTEM</div>
		<div align="center" style="display: block;font-weight: normal;font-size: 10px;">(<?= $row['nomor'];?>)</div>
		<br /><br /><br />
		<table width="98%" border="0" cellpadding="4">
			<tr>
				<th width="18%">1. Nama</th>
				<th width="2%">:</th>
				<th width="80%" align="left"><?php echo $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['nama']),'admin_name');?></th>
			</tr>
			<tr>
				<th>2. Departemen / Divisi</th>
				<th>:</th>
				<th>Divisi Stone Crusher - Tulungagung</th>
			</tr>
			<tr>
				<th>3. Aplikasi / Website</th>
				<th>:</th>
				<th>https://cuttingstone.biabumijayendra.com</th>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="4">
			<tr>
				<th width="18%">4. Sifat Permintaan</th>
				<th width="3%">:</th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['sangat_penting']);?></th>
				<th width="15%">Sangat Penting</th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['penting']);?></th>
				<th width="15%">Penting</th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['cukup_penting']);?></th>
				<th width="15%">Cukup Penting</th>
				<th width="23%"></th>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="4">
			<tr>
				<th width="18%">5. Jenis Permintaan</th>
				<th width="3%">:</th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['perbaikan']);?></th>
				<th width="20%">Perbaikan</th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['master_data_baru']);?></th>
				<th width="20%">Master Data Baru</th>
				<th width="31%"></th>
			</tr>
			<br />
			<tr>
				<th></th>
				<th></th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['penambahan_fitur_baru']);?></th>
				<th>Penambahan Fitur Baru</th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['penambahan_data']);?></th>
				<th>Penambahan Datan</th>
				<th></th>
			</tr>
			<br />
			<tr>
				<th></th>
				<th></th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['pengurangan_fitur_lama']);?></th>
				<th>Pengurangan Fitur Lama</th>
				<th align="center" width="4%" style="border:1px solid black;"> <?= $this->pmm_finance->CheckorNoNew($row['lain_lain']);?></th>
				<th>Lain - Lain : Perubahan Data</th>
				<th></th>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="4">
			<tr>
				<th width="100%">6. Deskripsi Permintaan : <i>(Wajib diisi oleh pemohon)</i></th>
			</tr>
			<tr>
				<th width="100%" style="border:1px solid black;"><?= $row['deskripsi'];?></th>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="4">
			<tr>
				<th width="100%">7. Catatan</th>
			</tr>
			<tr>
				<th width="100%" style="border:1px solid black;"><?= $row['memo'];?></th>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="10">
            <tr>
                <?php
					$this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
					$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
					$this->db->where('a.admin_id',$row['ti_sistem']);
					$ti_sistem = $this->db->get('tbl_admin a')->row_array();

                    $this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
                    $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                    $this->db->where('a.admin_id',$row['unit_head']);
                    $unit_head = $this->db->get('tbl_admin a')->row_array();

					$this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
                    $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                    $this->db->where('a.admin_id',$row['nama']);
                    $created = $this->db->get('tbl_admin a')->row_array();

                ?>
                <td width="100%">
                    <table width="100%" border="0" cellpadding="0">
                        <tr class="">
							<td align="left">
								Dieksekusi Oleh
                            </td>
                            <td align="left">
								Diketahui Oleh
                            </td>
                            <td align="left">
								Diperiksa & Disetujui Oleh
                            </td>
                            <td align="left">
								Diajukan Oleh
                            </td>
                        </tr>
                        <tr class="">
							<td align="left" height="100px">
                                <img src="<?= $ti_sistem['admin_ttd']?>" width="100px">  
                            </td>
                            <td align="left">
								<img src="<?= $unit_head['admin_ttd']?>" width="100px">
                            </td>
                            <td align="left">
								<img src="<?= $created['admin_ttd']?>" width="100px">   
                            </td>
                            <td align="left">
								<img src="<?= $created['admin_ttd']?>" width="100px">   
                            </td>
                        </tr>
                        <tr class="">
                            <td align="left">
								Nama : Ginanjar Bayu B.
                            </td>
                            <td align="left">
								Nama : <?= $unit_head['admin_name'];?>
                            </td>
                            <td align="left">
								Nama : <?= $created['admin_name'];?>
                            </td>
							<td align="left">
								Nama : <?= $created['admin_name'];?>
                            </td>
                        </tr>
                        <tr class="">
							<td align="left">
								Jabatan : TI & Sistem
                            </td>
                            <td align="left">
								Jabatan : <?= $unit_head['admin_group_name'];?>
                            </td>
                            <td align="left">
								Jabatan : <?= $created['admin_group_name'];?>
                            </td>
                            <td align="left">
								Jabatan : <?= $created['admin_group_name'];?>
                            </td>
                        </tr>
						<tr class="">
							<td align="left">
								Tgl. : <?= date('d/m/y',strtotime($row['tanggal_ti_sistem']));?>
                            </td>
                            <td align="left">
								Tgl. : <?= date('d/m/y',strtotime($row['created_on']));?>
                            </td>
                            <td align="left">
								Tgl. : <?= date('d/m/y',strtotime($row['created_on']));?>
                            </td>
                            <td align="left">
								Tgl. : <?= date('d/m/y',strtotime($row['created_on']));?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
	</body>
</html>