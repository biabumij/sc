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
			font-weight: bold;
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
					<div style="display: block;font-weight: bold;font-size: 12px;">LAPORAN LABA RUGI<br/>
					<div style="text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<?php
		$data = array();
		
		$arr_date = $this->input->get('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date3 = '';
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date3 	= date('2021-01-01',strtotime($date3));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table width="98%" border="0" cellpadding="3">
		
			<?php
			//AKUMULASI
			$akumulasi = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, SUM(pp.total_nilai_keluar_2) as total_2, SUM(pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi_hpp = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->row_array();
			
			//PENJUALAN
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
			
			$total_penjualan = 0;
			$total_volume = 0;
			$measure = 0;

			foreach ($penjualan as $x){
				$total_penjualan += $x['price'];
				$total_volume += $x['volume'];
			}

			$total_penjualan_all = 0;
			$total_penjualan_all = $total_penjualan + $total_penjualan_limbah;
			
			$biaya_umum_administratif_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif = $biaya_umum_administratif_biaya['total'] + $biaya_umum_administratif_jurnal['total'];
			$biaya_lainnya = $biaya_lainnya_biaya['total'] + $biaya_lainnya_jurnal['total'];

			$total_harga_pokok_pendapatan = $akumulasi_hpp['total'];
			$laba_kotor = $total_penjualan_all - $total_harga_pokok_pendapatan;
			$total_biaya = $biaya_umum_administratif + $biaya_lainnya;
			$laba_usaha = $laba_kotor - $total_biaya;
			$nilai_persediaan_bahan_baku = $akumulasi['total_akhir'];
			$nilai_persediaan_barang_jadi = $akumulasi2['total_akhir'];
			$total = $laba_usaha + $nilai_persediaan_bahan_baku + $nilai_persediaan_barang_jadi;
			$persentase = ($total_penjualan_all!=0)?($total / $total_penjualan_all)  * 100:0;

			//AKUMULASI 2
			$akumulasi_2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, SUM(pp.total_nilai_keluar_2) as total_2, SUM(pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi2_2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi_hpp_2 = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date3' and '$date2')")
			->get()->row_array();

			//AKUMULASI PENJUALAN
			$penjualan_limbah_2 = $this->db->select('SUM(pp.display_price) as price')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date3' and '$date2'")
			->where("pp.product_id = 9 ")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();

			$total_penjualan_limbah_2 = 0;
			foreach ($penjualan_limbah_2 as $y){
				$total_penjualan_limbah_2 += $y['price'];
			}

			$penjualan_2 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date3' and '$date2'")
			->where("pp.product_id in (3,4,7,8,14,24)")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();
			
			$total_penjualan_2 = 0;
			$total_volume_2 = 0;

			foreach ($penjualan_2 as $x){
				$total_penjualan_2 += $x['price'];
				$total_volume_2 += $x['volume'];
			}

			$total_penjualan_all_2 = 0;
			$total_penjualan_all_2 = $total_penjualan_2 + $total_penjualan_limbah_2;

			$biaya_umum_administratif_biaya_2 = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_jurnal_2 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_biaya_2 = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_jurnal_2 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_2 = $biaya_umum_administratif_biaya_2['total'] + $biaya_umum_administratif_jurnal_2['total'];
			$biaya_lainnya_2 = $biaya_lainnya_biaya_2['total'] + $biaya_lainnya_jurnal_2['total'];
		
			$total_harga_pokok_pendapatan_2 = $akumulasi_hpp_2['total'];
			$laba_kotor_2 = $total_penjualan_all_2 - $total_harga_pokok_pendapatan_2;
			$total_biaya_2 = $biaya_umum_administratif_2 + $biaya_lainnya_2;
			$laba_usaha_2 = $laba_kotor_2 - $total_biaya_2;
			$nilai_persediaan_bahan_baku_2 = $akumulasi_2['total_akhir'];
			$nilai_persediaan_barang_jadi_2 = $akumulasi2_2['total_akhir'];
			$total_2 = $laba_usaha_2 + $nilai_persediaan_bahan_baku_2 + $nilai_persediaan_barang_jadi_2;
			$persentase_2 = ($total_penjualan_all_2!=0)?($total_2 / $total_penjualan_all_2)  * 100:0;
	        ?>

			<table width="98%" border="0" cellpadding="3">
				<tr class="table-active" style="">
					<td width="50%">
						<div style="display: block;font-weight: bold;font-size: 10px;">Periode</div>
					</td>
					<td align="right" width="25%">
						<div align="center" style="display: block;font-weight: bold;font-size: 10px;"><?php echo $filter_date;?></div>
					</td>
					<td align="right" width="25%">
						<div align="center" style="display: block;font-weight: bold;font-size: 10px;">SD. <?php echo date('d/m/Y',strtotime($arr_filter_date[1]));?></div>
					</td>
				</tr>
			</table>
			<hr width="98%">
			<tr class="table-active4">
				<th width="100%" align="left"><b>Pendapatan Penjualan</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center"></th>
				<th width="40%" align="left">Pendapatan</th>
	            <th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($total_penjualan_all,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($total_penjualan_all_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<hr width="98%">
			<tr class="table-active2">
				<th width="50%" align="left"><b>Total Pendapatan</b></th>
	            <th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($total_penjualan_all,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($total_penjualan_all_2,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<tr class="table-active4">
				<th width="100%" align="left"><b>Beban Pokok Penjualan</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center"></th>
				<th width="40%" align="left">Beban Pokok Penjualan</th>
	            <th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($total_harga_pokok_pendapatan_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<hr width="98%">
			<tr class="table-active2">
				<th width="50%" align="left"><b>Total Harga Pokok Pendapatan</b></th>
	            <th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($total_harga_pokok_pendapatan_2,0,',','.');?></b></span>
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
				$styleColor2 = $laba_kotor_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active2">
	            <th width="50%" align="left"><b>Laba / Rugi Kotor</b></th>
	            <th width="25%" align="right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo $laba_kotor < 0 ? "(".number_format(-$laba_kotor,0,',','.').")" : number_format($laba_kotor,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo $laba_kotor_2 < 0 ? "(".number_format(-$laba_kotor_2,0,',','.').")" : number_format($laba_kotor_2,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<tr class="table-active4">
				<th width="50%" colspan="3" align="left"><b>Biaya Umum & Administratif</b></th>
				<th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($biaya_umum_administratif,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($biaya_umum_administratif_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active4">
				<th width="50%" colspan="3" align="left"><b>Biaya Lainnya</b></th>
				<th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($biaya_lainnya,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span>Rp.</span>
								</th>
								<th align="center" width="80%">
									<span><?php echo number_format($biaya_lainnya_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<?php
				$styleColor = $laba_usaha < 0 ? 'color:red' : 'color:black';
				$styleColorw = $laba_usaha_2 < 0 ? 'color:red' : 'color:black';
			?>	
			<tr class="table-active3">
	            <th width="50%" align="left"><b>Laba / Rugi Usaha</b></th>
	            <th width="25%" align="right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo $laba_usaha < 0 ? "(".number_format(-$laba_usaha,0,',','.').")" : number_format($laba_usaha,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo $laba_usaha_2 < 0 ? "(".number_format(-$laba_usaha_2,0,',','.').")" : number_format($laba_usaha_2,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<tr class="table-active4">
	            <th width="50%" align="left"><b>Nilai Persediaan Bahan Baku</b></th>
	            <th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($nilai_persediaan_bahan_baku,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($nilai_persediaan_bahan_baku_2,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active4">
	            <th width="50%" align="left"><b>Nilai Persediaan Barang Jadi</b></th>
	            <th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($nilai_persediaan_barang_jadi,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo number_format($nilai_persediaan_barang_jadi_2,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
			<?php
				$styleColor = $total < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $total_2 < 0 ? 'color:red' : 'color:black';
			?>	
			<tr class="table-active3">
	            <th width="50%" align="left"><b>Total</b></th>
	            <th width="25%" align="right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo $total < 0 ? "(".number_format(-$total,0,',','.').")" : number_format($total,0,',','.');?></b></span>
								</th>
							</tr>
					</table>
				</th>
				<th width="25%" align="right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="20%">
									<span><b>Rp.</b></span>
								</th>
								<th align="center" width="80%">
									<span><b><?php echo $total_2 < 0 ? "(".number_format(-$total_2,0,',','.').")" : number_format($total_2,0,',','.');?></b></span>
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
								Dibuat Oleh
							</td>
						</tr>
						<?php
							$create = $this->db->select('id, unit_head, logistik, admin')
							->from('akumulasi_bahan_baku')
							->where("(date_akumulasi <= '$end_date')")
							->order_by('id','desc')->limit(1)
							->get()->row_array();

							$this->db->select('a.admin_name, g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['unit_head']);
							$unit_head = $this->db->get('tbl_admin a')->row_array();

							$dirut = $this->pmm_model->GetNameGroup(6);
						?>
						<tr class="">
							<td align="center" height="70px">
							
							</td>
							<td align="center">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u><?= $dirut['admin_name'];?></u><br />
								<?=  $dirut['admin_group_name'];?></b>
							</td>
							<td align="center">
								<b><u><?= $unit_head['admin_name'];?></u><br />
								Kepala Unit Bisnis</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
		
	</body>
</html>