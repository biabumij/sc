<!DOCTYPE html>
<html>
	<head>
	  <title>REKAPITULASI LAPORAN PRODUKSI</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 11px;">REKAPITULASI LAPORAN PRODUKSI</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="3" width="98%" border="0">
			<tr class="table-judul">
				<th align="center" width="5%">No</th>
				<th align="center" width="35%">Uraian</th>
				<th align="center" width="20%">Satuan</th>
				<th align="center" width="20%">Presentase</th>
				<th align="center" width="20%">Volume</th>
            </tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $sups) {
            		?>
					<?php
					$subtotal_presentase = 0;
					$subtotal_volume = 0;
					?>
					<?php
					$subtotal_presentase = $sups['presentase_a'] + $sups['presentase_b'] + $sups['presentase_c'] + $sups['presentase_d'] + $sups['presentase_e'];
					$subtotal_volume = str_replace(['.', ','], ['', '.'], $sups['jumlah_pemakaian_a']) + str_replace(['.', ','], ['', '.'], $sups['jumlah_pemakaian_b']) + str_replace(['.', ','], ['', '.'], $sups['jumlah_pemakaian_c']) + str_replace(['.', ','], ['', '.'], $sups['jumlah_pemakaian_d']) + str_replace(['.', ','], ['', '.'], $sups['jumlah_pemakaian_e']);
					?>
            		<tr class="table-baris1">
            			<td align="center"><?php echo $key + 1;?></td>
						<td align="left"><?php echo $sups['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_a']),'nama_produk'); ?></td>
						<td align="center"><?php echo $sups['measure_a'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_a']),'measure_name'); ?></td>
						<td align="center"><?php echo $sups['presentase_a'];?> %</td>
						<td align="right"><?php echo $sups['jumlah_pemakaian_a'];?></td>
            		</tr>
					<tr class="table-baris1">
						<td align="center"><?php echo $key + 2;?></td>						
						<td align="left"><?php echo $sups['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_b']),'nama_produk'); ?></td>
						<td align="center"><?php echo $sups['measure_b'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_b']),'measure_name'); ?></td>
						<td align="center"><?php echo $sups['presentase_b'];?> %</td>
						<td align="right"><?php echo $sups['jumlah_pemakaian_b'];?></td>
					</tr>
					<tr class="table-baris1">
						<td align="center"><?php echo $key + 3;?></td>						
						<td align="left"><?php echo $sups['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_c']),'nama_produk'); ?></td>
						<td align="center"><?php echo $sups['measure_c'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_c']),'measure_name'); ?></td>
						<td align="center"><?php echo $sups['presentase_c'];?> %</td>
						<td align="right"><?php echo $sups['jumlah_pemakaian_c'];?></td>
					</tr>
					<tr class="table-baris1">
						<td align="center"><?php echo $key + 4;?></td>					
						<td align="left"><?php echo $sups['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_d']),'nama_produk'); ?></td>
						<td align="center"><?php echo $sups['measure_d'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_d']),'measure_name'); ?></td>
						<td align="center"><?php echo $sups['presentase_d'];?> %</td>
						<td align="right"><?php echo $sups['jumlah_pemakaian_d'];?></td>
					</tr>
					<tr class="table-baris1">
						<td align="center"><?php echo $key + 5;?></td>			
						<td align="left"><?php echo $sups['produk_e'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_e']),'nama_produk'); ?></td>
						<td align="center"><?php echo $sups['measure_e'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_e']),'measure_name'); ?></td>
						<td align="center"><?php echo $sups['presentase_e'];?> %</td>
						<td align="right"><?php echo $sups['jumlah_pemakaian_e'];?></td>
					</tr>
            			<?php
            	}
            }
            ?>
            <tr class="table-total">
            	<th width="40%" align="center" colspan="2"><b>TOTAL</b></th>
				<th width="20%" align="center"><b>Ton</b></th>
				<th width="20%" align="center"><?php echo number_format($subtotal_presentase,2,',','.');?></th>
            	<th width="20%" align="right"><?php echo number_format($subtotal_volume,2,',','.');?></th>
            </tr>
			
		</table>
		<br />
		<br />
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
							->from('akumulasi')
							->where("(date_akumulasi = '$end_date')")
							->order_by('id','desc')->limit(1)
							->get()->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['unit_head']);
							$unit_head = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['logistik']);
							$logistik = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['admin']);
							$admin = $this->db->get('tbl_admin a')->row_array();
						?>
						<tr class="">
							<td align="center" height="70px">
								<!--<img src="<?= $unit_head['admin_ttd']?>" width="70px">-->
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
								<b><u></u><br />
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