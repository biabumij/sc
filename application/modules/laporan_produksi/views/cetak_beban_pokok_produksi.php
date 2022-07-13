<!DOCTYPE html>
<html>
	<head>
	  <title>BEBAN POKOK PRODUKSI</title>
	  
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
			font-weight: bold;
		}
			
		table tr.table-active2{
			font-size: 9px;
		}
			
		table tr.table-active3{
			font-size: 9px;
			font-weight: bold;
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
					<div style="display: block;font-weight: bold;font-size: 12px;">BEBAN POKOK PRODUKSI</div>
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
		<br />
		<br />
		<br />
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
		
			<!-- Pergerakan Bahan Baku -->
			
			<!--- OPENING BALANCE --->
			
			<?php
			
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			
			$pergerakan_bahan_baku_ago = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
			->where("prm.material_id = 15")
			->group_by('prm.material_id')
			->get()->row_array();
			
			//file_put_contents("D:\\pergerakan_bahan_baku_ago.txt", $this->db->last_query());

			$total_volume_pembelian_ago = $pergerakan_bahan_baku_ago['volume'];
			$total_volume_pembelian_akhir_ago  = $total_volume_pembelian_ago;
			
			$produksi_harian_ago = $this->db->select('sum(pphd.use) as used')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->where("(pph.date_prod between '$date1_ago' and '$date2_ago')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();
			
			//file_put_contents("D:\\produksi_harian_ago.txt", $this->db->last_query());

			$total_volume_produksi_ago = $produksi_harian_ago['used'];
			$total_volume_produksi_akhir_ago = $total_volume_pembelian_akhir_ago - $total_volume_produksi_ago;

			$harga_satuan_ago = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date3_ago' and '$date2_ago'")
			->where("prm.material_id = 15")
			->group_by('prm.material_id')
			->get()->row_array();
			
			//file_put_contents("D:\\harga_satuan_ago.txt", $this->db->last_query());

			$nilai_harga_satuan_ago = ($harga_satuan_ago['volume']!=0)?($harga_satuan_ago['nilai'] / $harga_satuan_ago['volume'])  * 1:0;

			$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
			->from('hpp_bahan_baku pp')
			->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
			->get()->row_array();
			
			//file_put_contents("D:\\harga_hpp_bahan_baku.txt", $this->db->last_query());

			$total_volume_produksi_akhir_ago_fix = round($total_volume_produksi_akhir_ago,2);

			$volume_opening_balance = $total_volume_produksi_akhir_ago;
			$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
			$nilai_opening_balance = $total_volume_produksi_akhir_ago_fix * $harga_opening_balance;

			$pergerakan_bahan_baku_ago_solar = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
			->where("prm.material_id = 13")
			->group_by('prm.material_id')
			->get()->row_array();

			$volume_pergerakan_bahan_baku_ago_solar = $pergerakan_bahan_baku_ago_solar['volume'];
			
			//file_put_contents("D:\\pergerakan_bahan_baku_ago_solar.txt", $this->db->last_query());

			$stock_opname_solar_ago = $this->db->select('`prm`.`volume` as volume, `prm`.`total` as total')
			->from('pmm_remaining_materials_cat prm ')
			->where("prm.material_id = 13")
			->where("(prm.date < '$date1')")
			->where("status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_solar_ago.txt", $this->db->last_query());

			$volume_stock_opname_solar_ago = $stock_opname_solar_ago['volume'];

			$volume_opening_balance_solar = $volume_stock_opname_solar_ago;
			$volume_opening_balance_solar_fix = round($volume_opening_balance_solar,2);

			$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
			$nilai_opening_balance_solar = $volume_opening_balance_solar_fix * $harga_opening_balance_solar;

			?>

			<!--- NOW --->

			<?php
			
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
			
			//file_put_contents("D:\\pergerakan_bahan_baku.txt", $this->db->last_query());
			
			$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
			$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
			$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

			$total_volume_pembelian_akhir  = $total_volume_produksi_akhir_ago + $total_volume_pembelian;
			$total_harga_pembelian_akhir = ($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir;
			$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			
			
			$produksi_harian = $this->db->select('sum(pphd.use) as used')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->where("(pph.date_prod between '$date1' and '$date2')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();

			//file_put_contents("D:\\produksi_harian.txt", $this->db->last_query());

			$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->result_array();

			$total_akumulasi_bahan_baku = 0;
			$total_akumulasi_bahan_baku_2 = 0;

			foreach ($akumulasi_bahan_baku as $b){
				$total_akumulasi_bahan_baku += $b['total_nilai_keluar'];
				$total_akumulasi_bahan_baku_2 += $b['total_nilai_keluar_2'];
			}

			$akumulasi_nilai_bahan_baku = $total_akumulasi_bahan_baku;
			$akumulasi_nilai_bahan_baku_2 = $total_akumulasi_bahan_baku_2;
			
			//file_put_contents("D:\\akumulasi_bahan_baku.txt", $this->db->last_query());

			
			$total_volume_produksi = $produksi_harian['used'];
			$total_nilai_produksi = $akumulasi_nilai_bahan_baku;
			$total_harga_produksi = ($total_volume_produksi!=0)?($total_nilai_produksi / $total_volume_produksi)  * 1:0;
			
			
			$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
			$total_harga_produksi_akhir = $total_harga_produksi;
			$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

			//BBM
			$pergerakan_bahan_baku_solar = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			(prm.display_price / prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 13")
			->group_by('prm.material_id')
			->get()->row_array();
			
			//file_put_contents("D:\\pergerakan_bahan_baku_solar.txt", $this->db->last_query());

			$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
			$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
			$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

			$total_volume_pembelian_akhir_solar  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
			$total_harga_pembelian_akhir_solar = ($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar;
			$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

			$stock_opname_solar = $this->db->select('(prm.volume) as volume, (prm.total) as total')
			->from('pmm_remaining_materials_cat prm ')
			->where("prm.material_id = 13")
			->where("prm.date between '$date1' and '$date2'")
			->where("status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_solar.txt", $this->db->last_query());

			$volume_stock_opname_solar = $stock_opname_solar['volume'];
			
			$total_volume_produksi_akhir_solar = $volume_stock_opname_solar;
			$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
			$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

			$total_volume_produksi_solar = $total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar;
			$total_harga_produksi_solar =  $total_harga_pembelian_akhir_solar;
			$total_nilai_produksi_solar = $total_volume_produksi_solar * $total_harga_produksi_akhir_solar;

			//TOTAL
			$total_nilai_masuk = $total_nilai_pembelian + $total_nilai_pembelian_solar;
			$total_nilai_keluar = $total_nilai_produksi + $total_nilai_produksi_solar;
			$total_nilai_akhir = $total_nilai_produksi_akhir + $total_nilai_produksi_akhir_solar;

	        ?>
			
			<!-- End Pergerakan Bahan Baku -->
			
			<?php
			
			$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
			->where("(pph.date_prod between '$date1' and '$date2')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();

			//file_put_contents("D:\\abu_batu.txt", $this->db->last_query());
			
			$total_abu_batu = 0;
			$nilai_abu_batu1 = 0;
			$nilai_abu_batu2 = 0;
			$nilai_abu_batu3 = 0;
			$nilai_abu_batu4 = 0;
			$nilai_abu_batu5 = 0;
			$nilai_abu_batu_all = 0;

			$total_abu_batu = $abu_batu['jumlah_pemakaian_a'] + $abu_batu['jumlah_pemakaian_b'] + $abu_batu['jumlah_pemakaian_c'] + $abu_batu['jumlah_pemakaian_d'] + $abu_batu['jumlah_pemakaian_e'];
			$nilai_abu_batu1 = $abu_batu['jumlah_pemakaian_a'] * $total_harga_produksi_akhir;
			$nilai_abu_batu2 = $abu_batu['jumlah_pemakaian_b'] * $total_harga_produksi_akhir;
			$nilai_abu_batu3 = $abu_batu['jumlah_pemakaian_c'] * $total_harga_produksi_akhir;
			$nilai_abu_batu4 = $abu_batu['jumlah_pemakaian_d'] * $total_harga_produksi_akhir;
			$nilai_abu_batu5 = $abu_batu['jumlah_pemakaian_e'] * $total_harga_produksi_akhir;
			$nilai_abu_batu_all = $nilai_abu_batu1 + $nilai_abu_batu2 + $nilai_abu_batu3 + $nilai_abu_batu4 + $nilai_abu_batu5;

			$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;
			
			$stone_crusher_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 101")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			//file_put_contents("D:\\stone_crusher_biaya.txt", $this->db->last_query());

			$stone_crusher_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 101")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$stone_crusher = $stone_crusher_biaya['total'] + $stone_crusher_jurnal['total'];
			
			$whell_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 104")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$whell_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 104")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$whell_loader = $whell_loader_biaya['total'] + $whell_loader_jurnal['total'];
			
			$excavator = $this->db->select('sum(prm.display_price) as price')
			->from('pmm_receipt_material prm ')
			->where("prm.material_id = 18")
			->where("(prm.date_receipt between '$date1' and '$date2')")
			->get()->row_array();
			
			$genset_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 197")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$genset_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 197")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$genset = $genset_biaya['total'] + $genset_jurnal['total'];
			
			$timbangan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 198")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$timbangan_biaya_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 198")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$timbangan = $timbangan_biaya['total'] + $timbangan_biaya_jurnal['total'];
			
			$tangki_solar_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 207")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$tangki_solar_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 207")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$tangki_solar = $tangki_solar_biaya['total'] + $tangki_solar_jurnal['total'];
			
			$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar;
			$hpp_peralatan = ($total_abu_batu!=0)?($total_biaya_peralatan / $total_abu_batu)  * 1:0;
			
			$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun in (199,200)")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun in (199,200)")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];
			
			$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 201")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 201")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];
			
			$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 202")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 202")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];
			
			$perbaikan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 203")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$perbaikan_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 203")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			
			$perbaikan = $perbaikan_biaya['total'] + $perbaikan_jurnal['total'];

			$akomodasi_tamu_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 204")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$akomodasi_tamu_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 204")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$akomodasi_tamu = $akomodasi_tamu_biaya['total'] + $akomodasi_tamu_jurnal['total'];
			
			$pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 205")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 205")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$pengujian = $pengujian_biaya['total'] + $pengujian_jurnal['total'];
			
			$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 206")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 206")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			
			$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

			$total_operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;
			$hpp_operasional = ($total_abu_batu!=0)?($total_operasional / $total_abu_batu)  * 1:0;
			$total_bpp = $total_nilai_produksi + $total_nilai_produksi_solar + $total_biaya_peralatan + $total_operasional;
			$harga_bpp = ($total_abu_batu!=0)?($total_bpp / $total_abu_batu)  * 1:0;
			
			$harga_pemakaian_a = 0;
			$harga_pemakaian_b = 0;
			$harga_pemakaian_c = 0;
			$harga_pemakaian_d = 0;
			$total_harga_pemakaian = 0;
			
			$harga_pemakaian_a = $harga_bpp * $abu_batu['jumlah_pemakaian_a'];
			$harga_pemakaian_b = $harga_bpp * $abu_batu['jumlah_pemakaian_b'];
			$harga_pemakaian_c = $harga_bpp * $abu_batu['jumlah_pemakaian_c'];
			$harga_pemakaian_d = $harga_bpp * $abu_batu['jumlah_pemakaian_d'];
			
			$total_harga_pemakaian = $harga_pemakaian_a + $harga_pemakaian_b + $harga_pemakaian_c + $harga_pemakaian_d;
			
	        ?>

			<tr class="table-active3">
				<th width="5%" align="center">No.</th>
				<th width="35%" align="left">Uraian</th>
				<th width="15%" align="center">Satuan</th>
				<th width="15%" align="center">Volume</th>
	            <th width="15%" align="center">Harga Satuan</th>
				<th width="15%" align="center">Total</th>
	        </tr>
			<hr width="98%">
			<tr class="table-active3">
	            <th align="center">1.</th>
				<th>Bahan Baku</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
	            <th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Total Pemakaian Bahan Baku</th>
				<th align="center">Ton</th>
	            <th align="center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
				<th align="center"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
	            <th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Total Pemakaian BBM Solar</th>
				<th align="center">Liter</th>
	            <th align="center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
				<th align="center"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th align="center">2.</th>
				<th>Peralatan</th>
				<th></th>
				<th></th>
				<th></th>
	            <th></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Stone Crusher</th>
				<th align="center">Ls</th>
				<th></th>
				<th></th>
				<th align="right"><?php echo number_format($stone_crusher,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
	            <th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Whell Loader</th>
				<th align="center">Ls</th>
	            <th></th>
				<th></th>
				<th align="right"><?php echo number_format($whell_loader,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Excavator</th>
				<th align="center">Ls</th>
				<th></th>
				<th></th>
				<th align="right"><?php echo number_format($excavator['price'],0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Genset</th>
				<th align="center">Ls</th>
				<th></th>
				<th></th>
				<th align="right"><?php echo number_format($genset,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Timbangan</th>
				<th align="center">Ls</th>
				<th></th>
				<th></th>
				<th align="right"><?php echo number_format($timbangan,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Tangki Solar</th>
				<th align="center">Ls</th>
				<th></th>
				<th></th>
				<th align="right"><?php echo number_format($tangki_solar,0,',','.');?></th>
	        </tr>
			<hr width="98%">
			<tr class="table-active3">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Total Biaya Peralatan</th>
				<th></th>
				<th></th>
	            <th align="center"></th>
				<th align="right"><?php echo number_format($total_biaya_peralatan,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;HPP Peralatan</th>
				<th align="center">/Ton</th>
	            <th></th>
				<th></th>
				<th align="right"><?php echo number_format($hpp_peralatan,0,',','.');?></th>
	        </tr>
			<tr>
				<th align="left" colspan="5"></th>
			</tr>
			
			<!-- Operasional -->
			
			<tr class="table-active3">
	            <th align="center">3.</th>
				<th>Operasional</th>
				<th></th>
				<th></th>
				<th></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
	            <th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Gaji/Upah</th>
				<th align="center">Ls</th>
	            <th></th>
				<th></th>
				<th align="right"><?php echo number_format($gaji_upah,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Konsumsi</th>
				<th align="center">Ls</th>
				<th></th>
	            <th></th>
				<th align="right"><?php echo number_format($konsumsi,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;THR & Bonus</th>
				<th align="center">Ls</th>
				<th></th>
	            <th></th>
				<th align="right"><?php echo number_format($thr_bonus,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Perbaikan dan Pemeliharaan</th>
				<th align="center">Ls</th>
	            <th></th>
				<th></th>
				<th align="right"><?php echo number_format($perbaikan,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Akomodasi Tamu</th>
				<th align="center">Ls</th>
	            <th></th>
				<th></th>
				<th align="right"><?php echo number_format($akomodasi_tamu,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Pengujian Material dan Laboratorium</th>
				<th align="center">Ls</th>
	            <th></th>
				<th></th>
				<th align="right"><?php echo number_format($pengujian,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Listrik & Internet</th>
				<th align="center">Ls</th>
	            <th></th>
				<th></th>
				<th align="right"><?php echo number_format($listrik_internet,0,',','.');?></th>
	        </tr>
			<hr width="98%">
			<tr class="table-active3">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;Total Biaya Operasional</th>
				<th align="center">Ls</th>
				<th></th>
	            <th></th>
				<th align="right"><?php echo number_format($total_operasional,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th></th>
				<th align="left">&nbsp;&nbsp;&nbsp;&nbsp;HPP Operasional</th>
				<th align="center">Ls</th>
				<th align="center"></th>
	            <th align="center"></th>
				<th align="right"><?php echo number_format($hpp_operasional,0,',','.');?></th>
	        </tr>
			<tr>
				<th align="left" colspan="5"></th>
			</tr>
			<hr width="98%">
			<tr class="table-active">
				<th></th>
	            <th>&nbsp;&nbsp;&nbsp;&nbsp;HPP/Total Beban Pokok Produksi</th>
				<th align="center">Ls</th>
				<th></th>
				<th align="right">Rp.</th>
	            <th align="right"><?php echo number_format($total_bpp,0,',','.');?></th>
			</tr>
			<hr width="98%">
			<tr class="table-active">
				<th></th>
	            <th>&nbsp;&nbsp;&nbsp;&nbsp;Harga Pokok Penjualan</th>
				<th align="center">Ls</th>
				<th></th>
				<th align="right">Rp.</th>
	            <th align="right"><?php echo number_format($harga_bpp,0,',','.');?></th>
			</tr>
			<hr width="98%">

	    </table>
		<table>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table>
		<table width="98%" border="0" cellpadding="0">
			<tr >
				<td width="10%"></td>
				<td width="80%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="left" >
								Disetujui Oleh
							</td>
							<td align="center" colspan="2">
								Diperiksa Oleh
							</td>
							<td align="left">
								Dibuat Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center" height="35px">
							
							</td>
							<td align="center">
							
							</td>
							<td align="center">
							
							</td>
							<td align="center">
							
							</td>
						</tr>
						<tr>
							<td align="left">
								Nama : Hadi Sucipto<br />
								<b>Jabatan : Ka. Plant</b>
							</td>
							<td align="left">
								Nama : <br />
								<b>Jabatan : Keuangan</b>
							</td>
							<td align="left">
								Nama : <br />
								<b>Jabatan : Ka. Produksi</b>
							</td>
							<td align="left" >
								Nama : Vicky Irwana Yudha<br />
								<b>Jabatan : Ka. Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="10%"></td>
			</tr>
		</table>
	</body>
</html>