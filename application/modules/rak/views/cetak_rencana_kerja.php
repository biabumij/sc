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
		<?php
		$tanggal = date('F Y', strtotime($rak['tanggal_rencana_kerja']));
		?>
			
		<div align="center" style="display: block;font-weight: bold;font-size: 12px;text-transform:uppercase;">RENCANA KERJA<br/>
		PROYEK BENDUNGAN TEMEF<br/>
		PT. BIA BUMI JAYENDRA<br/>
		BULAN <?php echo $tanggal;?></div>
				
		<br />
		<br />

		<div align="left" style="display: block;font-weight: bold;font-size: 9px;text-transform:uppercase;">1. RENCANA PRODUKSI</div>
		<br />
		<table cellpadding="3" width="98%">
			<tr class="table-judul">
				<?php
					$total = 0;
					?>
					<?php
					$total = $rak['vol_produk_a'] + $rak['vol_produk_b'] + $rak['vol_produk_c'] + $rak['vol_produk_d'];
				?>
                <th width="5%" align="center" class="table-border-pojok-kiri">NO.</th>
                <th width="20%" align="center" class="table-border-pojok-tengah">URAIAN</th>
				<th width="20%" align="center" class="table-border-pojok-tengah">VOLUME</th>
				<th width="15%" align="center" class="table-border-pojok-tengah">SATUAN</th>
				<th width="20%" align="center" class="table-border-pojok-tengah">HARGA SATUAN</th>
				<th width="20%" align="center" class="table-border-pojok-kanan">TOTAL</th>
            </tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">1.</td>
				<td align="left" class="table-border-pojok-tengah">Beton K 125 (10±2)</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['vol_produk_a'],2,',','.'); ?></td>
				<td align="center" class="table-border-pojok-tengah">M3</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['price_a'],2,',','.'); ?></td>
				<?php
				$a1 = round($rak['vol_produk_a'],2);
				$a2 = round($rak['price_a'],0);
				$a3 = $a1 * $a2;
				?>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($a3,0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">2.</td>
				<td align="left" class="table-border-pojok-tengah">Beton K 225 (10±2)</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['vol_produk_b'],2,',','.'); ?></td>
				<td align="center" class="table-border-pojok-tengah">M3</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['price_b'],2,',','.'); ?></td>
				<?php
				$b1 = round($rak['vol_produk_b'],2);
				$b2 = round($rak['price_b'],0);
				$b3 = $b1 * $b2;
				?>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($b3,0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">3.</td>
				<td align="left" class="table-border-pojok-tengah">Beton K 250 (10±2)</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['vol_produk_c'],2,',','.'); ?></td>
				<td align="center" class="table-border-pojok-tengah">M3</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['price_c'],2,',','.'); ?></td>
				<?php
				$c1 = round($rak['vol_produk_c'],2);
				$c2 = round($rak['price_c'],0);
				$c3 = $c1 * $c2;
				?>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($c3,0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center" class="table-border-pojok-kiri">4.</td>
				<td align="left" class="table-border-pojok-tengah">Beton K 250 (18±2)</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['vol_produk_d'],2,',','.'); ?></td>
				<td align="center" class="table-border-pojok-tengah">M3</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($rak['price_d'],2,',','.'); ?></td>
				<?php
				$d1 = round($rak['vol_produk_d'],2);
				$d2 = round($rak['price_d'],0);
				$d3 = $d1 * $d2;
				$total_pendapatan = $a3 + $b3 + $c3 + $d3;
				?>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($d3,0,',','.'); ?></td>
			</tr>
			<tr class="table-total">
				<td align="right" colspan="2" class="table-border-pojok-kiri">RENCANA PRODUKSI</td>
				<td align="right" class="table-border-pojok-tengah"><?= number_format($total,2,',','.'); ?></td>
				<td align="center" class="table-border-pojok-tengah">M3</td>
				<td align="center" class="table-border-pojok-tengah"></td>
				<td align="right" class="table-border-pojok-kanan"><?= number_format($total_pendapatan,0,',','.'); ?></td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<div align="left" style="display: block;font-weight: bold;font-size: 9px;text-transform:uppercase;">2. BIAYA</div>
		<br />
		<table cellpadding="3" width="98%">
			<tr class="table-judul">
				<th width="5%" align="center" class="table-border-pojok-kiri">NO.</th>
				<th width="25%" align="center" class="table-border-pojok-tengah">URAIAN</th>
				<th width="15%" align="center" class="table-border-pojok-tengah">VOLUME</th>
				<th width="15%" align="center" class="table-border-pojok-tengah">SATUAN</th>
				<th width="20%" align="center" class="table-border-pojok-tengah">HARGA SATUAN</th>
				<th width="20%" align="center" class="table-border-pojok-kanan">TOTAL</th>
			</tr>
			<?php

			$tanggal_rencana_kerja = date('Y-m-d', strtotime($rak['tanggal_rencana_kerja']));
	
			$komposisi_125 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->get()->result_array();

			$total_volume_semen_125 = 0;
			$total_volume_pasir_125 = 0;
			$total_volume_batu1020_125 = 0;
			$total_volume_batu2030_125 = 0;

			foreach ($komposisi_125 as $x){
				$total_volume_semen_125 = $x['komposisi_semen_125'];
				$total_volume_pasir_125 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->get()->result_array();

			$total_volume_semen_225 = 0;
			$total_volume_pasir_225 = 0;
			$total_volume_batu1020_225 = 0;
			$total_volume_batu2030_225 = 0;

			foreach ($komposisi_225 as $x){
				$total_volume_semen_225 = $x['komposisi_semen_225'];
				$total_volume_pasir_225 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->get()->result_array();

			$total_volume_semen_250 = 0;
			$total_volume_pasir_250 = 0;
			$total_volume_batu1020_250 = 0;
			$total_volume_batu2030_250 = 0;

			foreach ($komposisi_250 as $x){
				$total_volume_semen_250 = $x['komposisi_semen_250'];
				$total_volume_pasir_250 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->get()->result_array();

			$total_volume_semen_250_2 = 0;
			$total_volume_pasir_250_2 = 0;
			$total_volume_batu1020_250_2 = 0;
			$total_volume_batu2030_250_2 = 0;

			foreach ($komposisi_250_2 as $x){
				$total_volume_semen_250_2 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen = $total_volume_semen_125 + $total_volume_semen_225 + $total_volume_semen_250 + $total_volume_semen_250_2;
			$total_volume_pasir = $total_volume_pasir_125 + $total_volume_pasir_225 + $total_volume_pasir_250 + $total_volume_pasir_250_2;
			$total_volume_batu1020 = $total_volume_batu1020_125 + $total_volume_batu1020_225 + $total_volume_batu1020_250 + $total_volume_batu1020_250_2;
			$total_volume_batu2030 = $total_volume_batu2030_125 + $total_volume_batu2030_225 + $total_volume_batu2030_250 + $total_volume_batu2030_250_2;

			$volume_produksi = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d')
			->from('rak r')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->get()->row_array();

			$volume_produksi_produk_a = $volume_produksi['vol_produk_a'];
			$volume_produksi_produk_b = $volume_produksi['vol_produk_b'];
			$volume_produksi_produk_c = $volume_produksi['vol_produk_c'];
			$volume_produksi_produk_d = $volume_produksi['vol_produk_d'];
			
			$total_volume_solar = $volume_produksi['vol_bbm_solar'];

			?>
			<tr>
				<th align="center" class="table-border-spesial" colspan="6">
					<div align="left" style="display: block;font-weight: bold;font-size: 9px;text-transform:uppercase;">2.1. BAHAN</div>
				</th>	
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">1.</th>	
				<th align="left" class="table-border-pojok-tengah">Semen</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_volume_semen,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah">Ton</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rak['harga_semen'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_volume_semen * $rak['harga_semen'],0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">2.</th>	
				<th align="left" class="table-border-pojok-tengah">Pasir</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_volume_pasir,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah">M3</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rak['harga_pasir'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_volume_pasir * $rak['harga_pasir'],0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">3.</th>	
				<th align="left" class="table-border-pojok-tengah">Batu Split 10-20</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_volume_batu1020,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah">M3</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rak['harga_batu1020'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_volume_batu1020 * $rak['harga_batu1020'],0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">4.</th>	
				<th align="left" class="table-border-pojok-tengah">Batu Split 20-30</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_volume_batu2030,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah">M3</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rak['harga_batu2030'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_volume_batu2030 * $rak['harga_batu2030'],0,',','.');?></th>
	        </tr>
			<?php
			$total = ($total_volume_semen * $rak['harga_semen']) + ($total_volume_pasir * $rak['harga_pasir']) + ($total_volume_batu1020 * $rak['harga_batu1020']) + ($total_volume_batu2030 * $rak['harga_batu2030']);
			?>
			<tr class="table-total2">	
				<th align="right" colspan="5" class="table-border-spesial-kiri">TOTAL KEBUTUHAN BIAYA BAHAN</th>
				<th align="right" class="table-border-spesial-kanan"><?php echo number_format($total,0,',','.');?></th>
	        </tr>
			<?php
			$rak_alat = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->get()->row_array();

			$rak_alat_bp = $rak_alat['penawaran_id_bp'];
			$rak_alat_bp_2 = $rak_alat['penawaran_id_bp_2'];
			$rak_alat_bp_3 = $rak_alat['penawaran_id_bp_3'];

			$rak_alat_tm = $rak_alat['penawaran_id_tm'];
			$rak_alat_tm_2 = $rak_alat['penawaran_id_tm_2'];
			$rak_alat_tm_3 = $rak_alat['penawaran_id_tm_3'];
			$rak_alat_tm_4 = $rak_alat['penawaran_id_tm_4'];

			$rak_alat_wl = $rak_alat['penawaran_id_wl'];
			$rak_alat_wl_2 = $rak_alat['penawaran_id_wl_2'];
			$rak_alat_wl_3 = $rak_alat['penawaran_id_wl_3'];

			$rak_alat_tr = $rak_alat['penawaran_id_tr'];
			$rak_alat_tr_2 = $rak_alat['penawaran_id_tr_2'];
			$rak_alat_tr_3 = $rak_alat['penawaran_id_tr_3'];

			$rak_alat_exc = $rak_alat['penawaran_id_exc'];
			$rak_alat_dmp_4m3 = $rak_alat['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3 = $rak_alat['penawaran_id_dmp_10m3'];
			$rak_alat_sc = $rak_alat['penawaran_id_sc'];
			$rak_alat_gns = $rak_alat['penawaran_id_gns'];
			$rak_alat_wl_sc = $rak_alat['penawaran_id_wl_sc'];

			$produk_bp = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, (vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->where("ppp.id = '$rak_alat_bp'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();


			$total_price_bp = 0;
			foreach ($produk_bp as $x){
				$total_price_bp += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->where("ppp.id = '$rak_alat_bp_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2 = 0;
			foreach ($produk_bp_2 as $x){
				$total_price_bp_2 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja = '$tanggal_rencana_kerja'")
			->where("ppp.id = '$rak_alat_bp_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3 = 0;
			foreach ($produk_bp_3 as $x){
				$total_price_bp_3 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm = 0;
			foreach ($produk_tm as $x){
				$total_price_tm += $x['qty'] * $x['price'];
			}

			$produk_tm_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2 = 0;
			foreach ($produk_tm_2 as $x){
				$total_price_tm_2 += $x['qty'] * $x['price'];
			}

			$produk_tm_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3 = 0;
			foreach ($produk_tm_3 as $x){
				$total_price_tm_3 += $x['qty'] * $x['price'];
			}

			$produk_tm_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4 = 0;
			foreach ($produk_tm_4 as $x){
				$total_price_tm_4 += $x['qty'] * $x['price'];
			}

			$produk_wl = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl = 0;
			foreach ($produk_wl as $x){
				$total_price_wl += $x['qty'] * $x['price'];
			}

			$produk_wl_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2 = 0;
			foreach ($produk_wl_2 as $x){
				$total_price_wl_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3 = 0;
			foreach ($produk_wl_3 as $x){
				$total_price_wl_3 += $x['qty'] * $x['price'];
			}

			$produk_tr = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr = 0;
			foreach ($produk_tr as $x){
				$total_price_tr += $x['qty'] * $x['price'];
			}

			$produk_tr_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2 = 0;
			foreach ($produk_tr_2 as $x){
				$total_price_tr_2 += $x['qty'] * $x['price'];
			}

			$produk_tr_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3 = 0;
			foreach ($produk_tr_3 as $x){
				$total_price_tr_3 += $x['qty'] * $x['price'];
			}

			$produk_exc = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc = 0;
			foreach ($produk_exc as $x){
				$total_price_exc += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3 = 0;
			foreach ($produk_dmp_4m3 as $x){
				$total_price_dmp_4m3 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3 = 0;
			foreach ($produk_dmp_10m3 as $x){
				$total_price_dmp_10m3 += $x['qty'] * $x['price'];
			}

			$produk_sc = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc = 0;
			foreach ($produk_sc as $x){
				$total_price_sc += $x['qty'] * $x['price'];
			}

			$produk_gns = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns = 0;
			foreach ($produk_gns as $x){
				$total_price_gns += $x['qty'] * $x['price'];
			}

			$produk_wl_sc = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc = 0;
			foreach ($produk_wl_sc as $x){
				$total_price_wl_sc += $x['qty'] * $x['price'];
			}

			?>

			<tr>
				<th align="center" class="table-border-spesial" colspan="6">
				<div align="left" style="display: block;font-weight: bold;font-size: 9px;text-transform:uppercase;">2.2. ALAT</div>
				</th>	
			</tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">1.</th>	
				<th align="left" class="table-border-pojok-tengah">Batching Plant</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_bp as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['total_vol_produksi'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['total_vol_produksi'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_bp_2 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['total_vol_produksi'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['total_vol_produksi'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_bp_3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['total_vol_produksi'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['total_vol_produksi'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">2.</th>	
				<th align="left" class="table-border-pojok-tengah">Truck Mixer</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_tm as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_tm_2 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_tm_3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_tm_4 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">3.</th>	
				<th align="left" class="table-border-pojok-tengah">Wheel Loader</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_wl as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_wl_2 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_wl_3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">4.</th>	
				<th align="left" class="table-border-pojok-tengah">Transfer Semen</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_tr as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_tr_2 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_tr_3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">5.</th>	
				<th align="left" class="table-border-pojok-tengah">BBM Solar</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($total_volume_solar,2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah">Liter</th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($rak['harga_solar'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($total_volume_solar * $rak['harga_solar'],0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">6.</th>	
				<th align="left" class="table-border-pojok-tengah">Insentif Operator</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($rak_alat['insentif'],0,',','.');?></th>
	        </tr>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri">7.</th>	
				<th align="left" class="table-border-pojok-tengah">Sewa Alat (SC)</th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="center" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-tengah"></th>
				<th align="right" class="table-border-pojok-kanan"></th>
	        </tr>
			<?php foreach ($produk_exc as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_dmp_4m3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_dmp_10m3 as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_sc as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_gns as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php foreach ($produk_wl_sc as $x): ?>
			<tr class="table-baris1">
				<th align="center" class="table-border-pojok-kiri"></th>
				<th align="right" class="table-border-pojok-tengah"><?= $x['nama_produk'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['qty'],2,',','.');?></th>
				<th align="center" class="table-border-pojok-tengah"><?= $x['measure_name'] ?></th>
				<th align="right" class="table-border-pojok-tengah"><?php echo number_format($x['price'],0,',','.');?></th>
				<th align="right" class="table-border-pojok-kanan"><?php echo number_format($x['qty'] * $x['price'],0,',','.');?></th>
	        </tr>
			<?php endforeach; ?>
			<?php
			$total_rak_alat =  ($total_price_bp + $total_price_bp_2 + $total_price_bp_3) + ($total_price_tm + $total_price_tm_2 + $total_price_tm_3 + $total_price_tm_4) + ($total_price_wl + $total_price_wl_2 + $total_price_wl_3) + ($total_price_tr + $total_price_tr_2 + $total_price_tr_3) + ($total_volume_solar * $rak['harga_solar']) + $rak_alat['insentif'] + ($total_price_exc + $total_price_dmp_4m3 + $total_price_dmp_10m3 + $total_price_sc + $total_price_gns + $total_price_wl_sc);
			?>
			<tr class="table-total2">	
				<th align="right" colspan="5" class="table-border-spesial-kiri">TOTAL KEBUTUHAN BIAYA ALAT</th>
				<th align="right" class="table-border-spesial-kanan"><?php echo number_format($total_rak_alat,0,',','.');?></th>
	        </tr>
			<tr>
				<th align="center" class="table-border-spesial" colspan="6">
					<div align="left" style="display: block;font-weight: bold;font-size: 9px;text-transform:uppercase;">2.3. BIAYA UMUM & ADMINISTRATIF</div>
				</th>	
			</tr>
			<tr class="table-total">	
				<th align="right" colspan="5" class="table-border-spesial-kiri">TOTAL BIAYA UMUM & ADMINISTRATIF</th>
				<th align="right" class="table-border-spesial-kanan"><?php echo number_format($rak_alat['overhead'],0,',','.');?></th>
	        </tr>
			<tr>
				<th class="table-border-spesial" colspan="6"></th>
			</tr>
			<tr class="table-total">	
				<th align="right" colspan="5" class="table-border-spesial-kiri">SUBTOTAL KEBUTUHAN BIAYA (2.1 + 2.2 + 2.3)</th>
				<th align="right" class="table-border-spesial-kanan"><?php echo number_format($total + $total_rak_alat + $rak_alat['overhead'],0,',','.');?></th>
	        </tr>
			<tr>
				<th class="table-border-spesial" colspan="6"></th>
			</tr>
			<tr class="table-judul">	
				<th align="right" colspan="5" class="table-border-spesial-kiri">LABA RUGI (1-2)</th>
				<th align="right" class="table-border-spesial-kanan"><?php echo number_format($total_pendapatan - ($total + $total_rak_alat + $rak_alat['overhead']),0,',','.');?></th>
	        </tr>
		</table>
	</body>
</html>