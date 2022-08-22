<!DOCTYPE html>
<html>
	<head>
	  <title>PERGERAKAN BAHAN BAKU (PENYESUAIAN)</title>
	  
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
		<table width="98%" cellpadding="30">
			<tr>
				<td align="center"  width="100%">
					<div style="display: block;font-weight: bold;font-size: 12px;">PERGERAKAN BAHAN BAKU (PENYESUAIAN)</div>
					<div style="display: block;font-weight: bold;font-size: 12px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
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
		
		<table width="98%" border="0" cellpadding="3" border="0">
		
		<?php

		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_batu_boulder_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu_boulder_ago_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 15")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->get()->row_array();

		$volume_opening_balance = $stock_opname_batu_boulder_ago['volume'] + $stock_opname_batu_boulder_ago_2['volume'];
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $volume_opening_balance * $harga_opening_balance;

		$stock_opname_solar_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_solar_ago_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 13")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance_solar = $stock_opname_solar_ago['volume'] + $stock_opname_solar_ago_2['volume'];	
		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
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
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = round($volume_opening_balance + $total_volume_pembelian,2);
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			
		
		$stock_opname_batu_boulder = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu_boulder_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();
		
		$total_volume_produksi_akhir = $stock_opname_batu_boulder['volume'] + $stock_opname_batu_boulder_2['volume'];
		$total_harga_produksi_akhir = round($total_harga_pembelian_akhir,0);
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		$total_volume_produksi = round($total_volume_pembelian_akhir - $total_volume_produksi_akhir,2);
		$total_harga_produksi = $total_harga_produksi_akhir;
		$total_nilai_produksi = $total_volume_produksi * $total_harga_produksi;

		//BBM Solar
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
		
		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = round($volume_opening_balance_solar + $total_volume_pembelian_solar,2);
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_solar_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir_solar = $stock_opname_solar['volume'] + $stock_opname_solar_2['volume'];
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = round($total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar,2);
		$total_harga_produksi_solar =  $total_harga_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $total_volume_produksi_solar * $total_harga_produksi_solar;

		//Total Opening Balance
		$opening_balance_bahan_baku = $nilai_opening_balance + $nilai_opening_balance_solar;

		//Total
		$total_nilai_masuk = $total_nilai_pembelian + $total_nilai_pembelian_solar;
		$total_nilai_keluar = $total_nilai_produksi + $total_nilai_produksi_solar;
		$total_nilai_akhir = $total_nilai_produksi_akhir + $total_nilai_produksi_akhir_solar;

		?>
			
		<tr class="table-judul">
			<th width="11%" align="center" rowspan="2">&nbsp;<br>URAIAN</th>
			<th width="8%" align="center" rowspan="2">&nbsp;<br>SATUAN</th>
			<th width="27%" align="center" colspan="3">MASUK</th>
			<th width="27%" align="center" colspan="3">KELUAR</th>
			<th width="27%" align="center" colspan="3">AKHIR</th>
		</tr>
		<tr class="table-judul">
			<th align="center" width="8%">VOLUME</th>
			<th align="center" width="8%">HARGA</th>
			<th align="center" width="11%">NILAI</th>
			<th align="center" width="8%">VOLUME</th>
			<th align="center" width="8%">HARGA</th>
			<th align="center" width="11%">NILAI</th>
			<th align="center" width="8%">VOLUME</th>
			<th align="center" width="8%">HARGA</th>
			<th align="center" width="11%">NILAI</th>
		</tr>
		<tr class="table-baris1">
			<th align="left" colspan="11"><b>BAHAN BAKU</b></th>
		</tr>
		<tr class="table-baris1">
			<th align="center"style="vertical-align:middle"><?php echo $date2_ago;?></th>			
			<th align="left" colspan="9"><i>Opening Balance</i></th>
			<th align="right"><?php echo number_format($opening_balance_bahan_baku,0,',','.');?></th>
		</tr>
		<tr class="table-baris1">
			<th align="left"><i>Batu Boulder</i></th>
			<th align="center">Ton</th>
			<th align="center"><?php echo number_format($pergerakan_bahan_baku['volume'],2,',','.');?></th>
			<th align="right"><?php echo number_format($total_harga_pembelian,0,',','.');?></th>
			<th align="right"><?php echo number_format($pergerakan_bahan_baku['nilai'],0,',','.');?></th>
			<th align="center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
			<th align="right"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
			<th align="right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
			<th align="center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
			<th align="right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></blink></th>
			<th align="right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>		
		</tr>
		<tr class="table-baris1">
			<th align="left"><i>BBM Solar</i></th>
			<th align="center">Liter</th>
			<th align="center"><?php echo number_format($pergerakan_bahan_baku_solar['volume'],2,',','.');?></th>
			<th align="right"><?php echo number_format($total_harga_pembelian_solar,0,',','.');?></th>
			<th align="right"><?php echo number_format($pergerakan_bahan_baku_solar['nilai'],0,',','.');?></th>
			<th align="center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
			<th align="right"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
			<th align="right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
			<th align="center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
			<th align="right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></blink></th>
			<th align="right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>		
		</tr>
		<tr class="table-total">
			<th align="center" colspan="2">TOTAL</th>
			<th align="center"></th>
			<th align="right"></th>
			<th align="right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
			<th align="center"></th>
			<th align="right"></th>
			<th align="right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
			<th align="center"></th>
			<th align="right"></th>
			<th align="right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
		</tr>
	</table>
	<br />
	<br />
	<table width="98%" border="0" cellpadding="30">
		<tr >
			<td width="5%"></td>
			<td width="90%">
				<table width="100%" border="0" cellpadding="2">
					<tr>
						<td align="center" >
							Disetujui Oleh
						</td>
						<td align="center" colspan="2">
							Diperiksa Oleh
						</td>
						<td align="center">
							Dibuat Oleh
						</td>
					</tr>
					<tr class="">
						<td align="center" height="40px">
						
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
							<b><u>Hadi Sucipto</u><br />
							Ka. Unit Bisnis</b>
						</td>
						<td align="center">
							<b><br />
							Keuangan</b>
						</td>
						<td align="center">
							<b><br />
							Ka. Produksi</b>
						</td>
						<td align="center" >
							<b><u>Vicky Irwana Yudha</u><br />
							Ka. Logistik</b>
						</td>
					</tr>
				</table>
			</td>
			<td width="5%"></td>
		</tr>
	</table>
	</body>
</html>