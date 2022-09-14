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
					<div style="display: block;font-weight: bold;font-size: 12px;">LAPORAN LABA RUGI<br/>
					DIVISI BETON PROYEK BENDUNGAN TEMEF<br/>
					PT. BIA BUMI JAYENDRA<br/>
					<div style="text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<table width="98%" border="0" cellpadding="3">
			<tr class="table-active" style="">
				<td width="50%">
					<div style="display: block;font-weight: bold;font-size: 10px;">Periode</div>
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
		
		<?php

		$penjualan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
		->from('pmm_productions pp')
		->join('penerima p', 'pp.client_id = p.id','left')
		->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.status = 'PUBLISH'")
		->where("ppo.status in ('OPEN','CLOSED')")
		->group_by("pp.client_id")
		->get()->result_array();
		
		$total_penjualan = 0;
		$total_volume = 0;

		foreach ($penjualan as $x){
			$total_penjualan += $x['price'];
			$total_volume += $x['volume'];
		}

		$total_penjualan_all = 0;
		$total_penjualan_all = $total_penjualan;

		//BAHAN		
		$akumulasi = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar')
		->from('akumulasi pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->result_array();

		$total_akumulasi = 0;

		foreach ($akumulasi as $a){
			$total_akumulasi += $a['total_nilai_keluar'];
		}

		$total_nilai = $total_akumulasi;
		//END BAHAN

		//ALAT
		$nilai_alat = $this->db->select('SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id in (12,13,14,15,16,23,24)")
		->where("po.status in ('PUBLISH','CLOSED')")
		->get()->row_array();

		$akumulasi_bbm = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
		->from('akumulasi pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->result_array();

		$total_akumulasi_bbm = 0;

		foreach ($akumulasi_bbm as $b){
			$total_akumulasi_bbm += $b['total_nilai_keluar_2'];
		}

		$total_nilai_bbm = $total_akumulasi_bbm;

		$total_insentif_tm = 0;

		$insentif_tm = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 220")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$total_insentif_tm = $insentif_tm['total'];

		$alat = $nilai_alat['nilai'] + $total_akumulasi_bbm + $total_insentif_tm;
		//END ALAT

		//OVERHEAD
		$overhead_15 = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',15)
		->where("c.id <> 220 ")
		->where("c.id <> 168 ")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$overhead_jurnal_15 = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',15)
		->where("c.id <> 220 ")
		->where("c.id <> 168 ")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$overhead_16 = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',16)
		->where("c.id <> 220 ")
		->where("c.id <> 168 ")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$overhead_jurnal_16 = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',16)
		->where("c.id <> 220 ")
		->where("c.id <> 168 ")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$overhead_17 = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',17)
		->where("c.id <> 220 ")
		->where("c.id <> 168 ")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$overhead_jurnal_17 = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where('c.coa_category',17)
		->where("c.id <> 220 ")
		->where("c.id <> 168 ")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$overhead =  $overhead_15['total'] + $overhead_jurnal_15['total'] + $overhead_16['total'] + $overhead_jurnal_16['total'] + $overhead_17['total'] + $overhead_jurnal_17['total'];
		//END OVERHEAD

		//DISKONTO
		$diskonto = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->join('pmm_coa c','pdb.akun = c.id','left')
		->where("pdb.akun = 168")
		->where("pb.status = 'PAID'")
		->where("(pb.tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$diskonto = $diskonto['total'];
		//END DISKONTO

		$bahan = $total_nilai;
		$alat = $alat;
		$overhead = $overhead;
		$diskonto = $diskonto;

		$total_biaya_operasional = $bahan + $alat + $overhead + $diskonto;

		$laba_kotor = $total_penjualan_all - $total_biaya_operasional;

		$laba_sebelum_pajak = $laba_kotor;

		$persentase_laba_sebelum_pajak = ($total_penjualan_all!=0)?($laba_sebelum_pajak / $total_penjualan_all)  * 100:0;

		?>

			<hr width="98%">
			<tr class="table-active4">
				<th width="100%" align="left"><b>Pendapatan Penjualan</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center"></th>
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
									<span><b>Rp.</b></span>
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
				<th width="100%" align="left"><b>Beban Pokok Penjualan</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center"></th>
				<th width="60%" align="left">Beban Pokok Penjualan</th>
	            <th width="30%" align="center">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span>Rp.</span>
								</th>
								<th align="right" width="90%">
									<span><?php echo number_format($total_biaya_operasional,0,',','.');?></span>
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
	            <th width="70%" align="left"><b>Biaya Umum & Administratif</b></th>
	            <th width="30%" align="right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="10%">
									<span><b>Rp.</b></span>
								</th>
								<th align="right" width="90%">
									<span><b>-</b></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th width="100%" align="left"></th>
	        </tr>
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
								<b><u>Gervasius K. Limahekin</u><br />
								Ka. Plant</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
		
	</body>
</html>