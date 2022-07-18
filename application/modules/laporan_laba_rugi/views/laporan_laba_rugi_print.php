<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN LABA RUGI</title>
	  
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
	  	table tr.table-active{
            background-color: #e69500;
			font-size: 9px;
		}
			
		table tr.table-active2{
			font-size: 9px;
		}
			
		table tr.table-active3{
			font-size: 9px;
		}
			
		table tr.table-active4{
			background-color: #D0D0D0;
			font-size: 9px;
		}
		tr.border-bottom td {
        border-bottom: 1pt solid #ff000d;
      }
	  </style>

	</head>
	<body>
		<br />
		<br />
		<table width="98%" cellpadding="3">
			<tr>
				<td align="center"  width="100%">
					<div style="display: block;font-weight: bold;font-size: 12px;">LAPORAN LABA RUGI</div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<table width="98%" border="0" cellpadding="3">
			<tr class="table-active" style="">
				<td width="50%">
					<div style="display: block;font-weight: bold;font-size: 10px;">PERIODE</div>
				</td>
				<td align="right" width="50%">
					<div style="display: block;font-weight: bold;font-size: 10px;"><?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<?php
		$data = array();
		
		$arr_date = $this->input->get('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table width="98%" border="0" cellpadding="3">
		
		<!-- Akumulasi --->

		<?php

		$akumulasi = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
		->from('akumulasi pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->row_array();

		//file_put_contents("D:\\akumulasi.txt", $this->db->last_query());

		//BPP
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?($total_nilai_pembelian / $total_volume_pembelian)  * 1:0;

		$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;
		//END BPP

		$penjualan_limbah = $this->db->select('SUM(pp.display_price) as price')
		->from('pmm_productions pp')
		->join('penerima p', 'pp.client_id = p.id','left')
		->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 9 ")
		->where("pp.status = 'PUBLISH'")
		->where("ppo.status in ('OPEN','CLOSED')")
		->group_by("pp.client_id")
		->get()->result_array();

		$total_penjualan_limbah = 0;
		foreach ($penjualan_limbah as $y){
			$total_penjualan_limbah += $y['price'];
		}

		//file_put_contents("D:\\penjualan_limbah.txt", $this->db->last_query());

		$penjualan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
		->from('pmm_productions pp')
		->join('penerima p', 'pp.client_id = p.id','left')
		->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id in (3,4,7,8,14,24)")
		->where("pp.status = 'PUBLISH'")
		->where("ppo.status in ('OPEN','CLOSED')")
		->group_by("pp.client_id")
		->get()->result_array();

		//file_put_contents("D:\\penjualan.txt", $this->db->last_query());

		$total_penjualan = 0;
		$total_volume = 0;
		$measure = 0;

		foreach ($penjualan as $x){
			$total_penjualan += $x['price'];
			$total_volume += $x['volume'];
		}

		$total_penjualan_all = 0;
		$total_penjualan_all = $total_penjualan + $total_penjualan_limbah;

		$akumulasi_biaya = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_biaya) as total')
		->from('akumulasi_biaya pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->row_array();

		//file_put_contents("D:\\akumulasi_biaya.txt", $this->db->last_query());

		$biaya_umum_administratif_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',16)
		->where("pb.status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		//file_put_contents("D:\\biaya_umum_administratif.txt", $this->db->last_query());

		$biaya_umum_administratif_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',16)
		->where("pb.status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		//file_put_contents("D:\\biaya_umum_administratif_jurnal.txt", $this->db->last_query());

		$biaya_lainnya_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',17)
		->where("pb.status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		//file_put_contents("D:\\biaya_lainnya_biaya.txt", $this->db->last_query());

		$biaya_lainnya_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',17)
		->where("pb.status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		//file_put_contents("D:\\biaya_lainnya_jurnal.txt", $this->db->last_query());

		$biaya_overhead_produksi = $akumulasi_biaya['total'];

		$biaya_umum_administratif = $biaya_umum_administratif_biaya['total'] + $biaya_umum_administratif_jurnal['total'];

		$biaya_lainnya = $biaya_lainnya_biaya['total'] + $biaya_lainnya_jurnal['total'];

		$total_harga_pokok_pendapatan = $akumulasi['total'];

		$laba_kotor = $total_penjualan_all - $total_harga_pokok_pendapatan;

		$total_biaya = $biaya_overhead_produksi +$biaya_umum_administratif + $biaya_lainnya;

		$laba_sebelum_pajak = $laba_kotor - $total_biaya;

		$persentase_laba_sebelum_pajak = ($total_penjualan_all!=0)?($laba_sebelum_pajak / $total_penjualan_all)  * 100:0;

		?>

			<hr width="98%">
			<tr class="table-active4">
				<th width="100%" align="left"><b>PENDAPATAN ATAS PENJUALAN</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center">4-40000</th>
				<th width="60%" align="left">Pendapatan</th>
	            <th width="30%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span>Rp.</span>
								</th>
								<th align="right" width="90%">
									<span><?php echo number_format($total_penjualan_all,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<hr width="98%">
			<tr class="table-active2">
				<th width="70%" align="left"><b>Total Pendapatan</b></th>
	            <th width="30%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span>Rp.</span>
								</th>
								<th align="right" width="90%">
									<span><b><?php echo number_format($total_penjualan_all,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<tr class="table-active4">
				<th width="100%" align="left"><b>BEBAN POKOK PENJUALAN</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center">5-50000</th>
				<th width="60%" align="left">Beban Pokok Penjualan</th>
	            <th width="30%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span>Rp.</span>
								</th>
								<th align="right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<hr width="98%">
			<tr class="table-active2">
				<th width="70%" align="left"><b>Total Harga Pokok Pendapatan</b></th>
	            <th width="30%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span><b>Rp.</b></span>
								</th>
								<th align="right" width="90%">
									<span><b><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<?php
				$styleColor = $laba_kotor < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active2">
	            <th width="70%" align="left"><b>Laba Kotor</b></th>
	            <th width="30%" align="right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span><b>Rp.</b></span>
								</th>
								<th align="right" width="90%">
									<span><b><?php echo number_format($laba_kotor,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<tr class="table-active4">
				<th width="100%" align="left"><b>BIAYA OPERASIONAL</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center"></th>
				<th width="60%" align="left">Biaya Overhead Produksi</th>
	            <th width="30%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span>Rp.</span>
								</th>
								<th align="right" width="90%">
									<span><?php echo number_format($biaya_overhead_produksi,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center">6-60100</th>
				<th width="60%" align="left">Biaya Umum & Administratif</th>
	            <th width="30%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span>Rp.</span>
								</th>
								<th align="right" width="90%">
									<span><?php echo number_format($biaya_umum_administratif,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center">8-80100</th>
				<th width="60%" align="left">Biaya Lainnya</th>
	            <th width="30%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span>Rp.</span>
								</th>
								<th align="right" width="90%">
									<span><?php echo number_format($biaya_lainnya,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<hr width="98%">
			<tr class="table-active2">
				<th width="70%" align="left"><b>Total Biaya</b></th>
	            <th width="30%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span><b>Rp.</b></span>
								</th>
								<th align="right" width="90%">
									<span><b><?php echo number_format($total_biaya,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<?php
				$styleColor = $laba_sebelum_pajak < 0 ? 'color:red' : 'color:black';
			?>	
			<tr class="table-active3">
	            <th width="70%" align="left"><b>Laba Sebelum Pajak</b></th>
	            <th width="30%" align="right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span><b>Rp.</b></span>
								</th>
								<th align="right" width="90%">
									<span><b><?php echo number_format($laba_sebelum_pajak,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
	    </table>

		<table width="98%" border="0" cellpadding="30">
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
						<tr class="">
							<td align="center" height="55px">
							
							</td>
							<td align="center">
							
							</td>
							<td align="center">
							
							</td>
							<td align="center">
							
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u>Deddy Sarwobiso</u><br />
								Dir. Produksi, HSE & Sistem</b>
							</td>
							<td align="center">
								<b><u>Endah Purnama S.</u><br />
								Staff. Produksi & HSE</b>
							</td>
							<td align="center">
							<b><u>Hadi Sucipto</u><br />
								Ka. Unit Bisnis</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
		
	</body>
</html>