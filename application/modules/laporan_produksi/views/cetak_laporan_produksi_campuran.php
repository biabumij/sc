<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PRODUKSI CAMPURAN</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PRODUKSI CAMPURAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="3" width="98%" border="0">
			<tr class="table-judul">
				<th align="center" width="5%">NO.</th>
				<th align="center" width="10%">TANGGAL</th>
				<th align="center" width="20%">AGREGAT</th>
				<th align="center" width="10%">SATUAN</th>
				<th align="center" width="10%">VOLUME</th>
				<th align="center" width="25%">FRAKSI</th>
				<th align="center" width="10%">KOMPOSISI</th>
				<th align="center" width="10%">VOLUME</th>
            </tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
					<?php
					$barang_jadi_a = 0;
					?>
					<?php
					$volume = str_replace(['.', ','], ['', '.'], $row['volume']);
					$barang_jadi_a = ($volume * $row['presentase_a']) / 100;
					$barang_jadi_b = ($volume * $row['presentase_b']) / 100;
					$barang_jadi_c = ($volume * $row['presentase_c']) / 100;
					$barang_jadi_d = ($volume * $row['presentase_d']) / 100;
					$sub_total = $barang_jadi_a + $barang_jadi_b + $barang_jadi_c + $barang_jadi_d;
					?>
            		<tr class="table-baris1">
            			<td align="center"><?php echo $key + 1;?></td>
						<td align="center"><?php echo $row['date_prod'] = date('d/m/Y',strtotime($row['date_prod']));?></td>
						<td align="center"><?php echo $row['agregat'];?></td>
						<td align="center"><?php echo $row['satuan'];?></td>
						<td align="center"><?php echo $row['volume'];?></td>
						<td align="left"><?php echo $row['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_a']),'nama_produk'); ?></td>
						<td align="center"><?php echo $row['presentase_a'];?> %</td>
						<td align="right"><?php echo number_format($barang_jadi_a,2,',','.');?></td>
            		</tr>
					<tr class="table-baris1">
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>						
						<td align="left"><?php echo $row['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_b']),'nama_produk'); ?></td>
						<td align="center"><?php echo $row['presentase_b'];?> %</td>
						<td align="right"><?php echo number_format($barang_jadi_b,2,',','.');?></td>
					</tr>
					<tr class="table-baris1">
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>					
						<td align="left"><?php echo $row['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_c']),'nama_produk'); ?></td>
						<td align="center"><?php echo $row['presentase_c'];?> %</td>
						<td align="right"><?php echo number_format($barang_jadi_c,2,',','.');?></td>
					</tr>
					<tr class="table-baris1">
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>					
						<td align="left"><?php echo $row['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_d']),'nama_produk'); ?></td>
						<td align="center"><?php echo $row['presentase_d'];?> %</td>
						<td align="right"><?php echo number_format($barang_jadi_d,2,',','.');?></td>
					</tr>
					<tr class="table-baris2-bold">
						<td align="right" colspan="7"><b>TOTAL</b></td>
						<td align="right"><?php echo number_format($sub_total,2,',','.');?></td>
						
					</tr>
            			<?php
            	}
            }
            ?>
            <tr class="table-total">
            	<th align="right" colspan="7"><b>TOTAL</b></th>
            	<th align="right"><?php echo number_format($total,2,',','.');?></th>
            </tr>
			
		</table>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<table width="98%">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center">
								Diperiksa Oleh
							</td>
							<td align="center">
								Dibuat Oleh
							</td>
						</tr>
						<?php
							$create = $this->db->select('*')
							->from('akumulasi_bahan_baku')
							->where("(date_akumulasi <= '$end_date')")
							->order_by('id','desc')->limit(1)
							->get()->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['unit_head']);
							$unit_head = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['logistik']);
							$logistik = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd, a.admin_name');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['admin']);
							$admin = $this->db->get('tbl_admin a')->row_array();
						?>
						<tr class="">
							<td align="center" height="70px">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $logistik['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $admin['admin_ttd']?>" width="70px">
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u><?= $unit_head['admin_name']?></u><br />
								Ka. Unit Bisnis</b>
							</td>
							<td align="center">
								<b><u>Vicky Irwana Yudha</u><br />
								Pj. Produksi dan HSE</b>
							</td>
							<td align="center">
								<b><u>Vicky Irwana Yudha</u><br />
								Produksi</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>