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
		'Juei',
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
		
		<!-- PERGERAKAN BAHAN JADI -->

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
			
			$total_volume_produksi = $produksi_harian['used'];
			$total_harga_produksi =  round($total_harga_pembelian_akhir,0);
			$total_nilai_produksi = $total_volume_produksi * $total_harga_produksi;
			
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

			$stock_opname_solar = $this->db->select('SUM(prm.volume) as volume, SUM(prm.total) as total')
			->from('pmm_remaining_materials_cat prm ')
			->where("prm.material_id = 13")
			->where("prm.date between '$date1' and '$date2'")
			->where("status = 'PUBLISH'")
			->order_by('date','desc')
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
			
			<!-- LAPORAN BEBAN POKOK PRODUKSI -->

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
			
			$total_volume_produksi = $produksi_harian['used'];
			$total_harga_produksi =  round($total_harga_pembelian_akhir,0);
			$total_nilai_produksi = $total_volume_produksi * $total_harga_produksi;
			
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

			$stock_opname_solar = $this->db->select('SUM(prm.volume) as volume, SUM(prm.total) as total')
			->from('pmm_remaining_materials_cat prm ')
			->where("prm.material_id = 13")
			->where("prm.date between '$date1' and '$date2'")
			->where("status = 'PUBLISH'")
			->order_by('date','desc')
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
			
			$bbm_solar = $total_nilai_produksi_solar;
			
			$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar + $bbm_solar;
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
			$total_bpp = $total_nilai_produksi + $total_biaya_peralatan + $total_operasional;
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

			<!--- END LAPORAN BEBAN POKOK PRODUKSI --->

			<!--- Opening Balance --->

			<?php

			$tanggal_awal = date('2020-01-01');
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$produksi_harian_bulan_lalu = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
			->where("(pph.date_prod between '$tanggal_awal' and '$tanggal_opening_balance')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();
			
			//file_put_contents("D:\\produksi_harian_bulan_lalu.txt", $this->db->last_query());

			$volume_produksi_harian_abubatu_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_a'];
			$volume_produksi_harian_batu0510_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_b'];
			$volume_produksi_harian_batu1020_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_c'];
			$volume_produksi_harian_batu2030_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_d'];

			$penjualan_abubatu_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
			->where("pp.product_id = 7")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_abubatu_bulan_lalu.txt", $this->db->last_query());

			$volume_penjualan_abubatu_bulan_lalu = $penjualan_abubatu_bulan_lalu['volume'];

			$penjualan_batu0510_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
			->where("pp.product_id = 8")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_batu0510_bulan_lalu.txt", $this->db->last_query());

			$volume_penjualan_batu0510_bulan_lalu = $penjualan_batu0510_bulan_lalu['volume'];

			$penjualan_batu1020_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
			->where("pp.product_id = 3")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_batu1020_bulan_lalu.txt", $this->db->last_query());

			$volume_penjualan_batu1020_bulan_lalu = $penjualan_batu1020_bulan_lalu['volume'];

			$penjualan_batu2030_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
			->where("pp.product_id = 4")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_batu2030_bulan_lalu.txt", $this->db->last_query());

			$volume_penjualan_batu2030_bulan_lalu = $penjualan_batu2030_bulan_lalu['volume'];

			//Agregat Bulan Lalu
			$agregat_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
			->where("pp.product_id = 24")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\agregat_bulan_lalu.txt", $this->db->last_query());

			$volume_agregat_abubatu_bulan_lalu = $agregat_bulan_lalu['volume_agregat_a'];
			$volume_agregat_batu0510_bulan_lalu = $agregat_bulan_lalu['volume_agregat_b'];
			$volume_agregat_batu1020_bulan_lalu = $agregat_bulan_lalu['volume_agregat_c'];
			$volume_agregat_batu2030_bulan_lalu = $agregat_bulan_lalu['volume_agregat_d'];

			$agregat_bulan_lalu_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
			->where("pp.product_id = 14")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\agregat_bulan_lalu_2.txt", $this->db->last_query());

			$volume_agregat_abubatu_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_a'];
			$volume_agregat_batu0510_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_b'];
			$volume_agregat_batu1020_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_c'];
			$volume_agregat_batu2030_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_d'];

			//End Agregat

			//Opening Balance
			$volume_opening_balance_abubatu_bulan_lalu = $volume_produksi_harian_abubatu_bulan_lalu - $volume_penjualan_abubatu_bulan_lalu - $volume_agregat_abubatu_bulan_lalu - $volume_agregat_abubatu_bulan_lalu_2;

			$volume_opening_balance_batu0510_bulan_lalu = $volume_produksi_harian_batu0510_bulan_lalu - $volume_penjualan_batu0510_bulan_lalu - $volume_agregat_batu0510_bulan_lalu - $volume_agregat_batu0510_bulan_lalu_2;

			$volume_opening_balance_batu1020_bulan_lalu = $volume_produksi_harian_batu1020_bulan_lalu - $volume_penjualan_batu1020_bulan_lalu - $volume_agregat_batu1020_bulan_lalu - $volume_agregat_batu1020_bulan_lalu_2;

			$volume_opening_balance_batu2030_bulan_lalu = $volume_produksi_harian_batu2030_bulan_lalu - $volume_penjualan_batu2030_bulan_lalu - $volume_agregat_batu2030_bulan_lalu - $volume_agregat_batu2030_bulan_lalu_2;

			//RUMUS HARGA OPENING BALANCE

			//Dua Bulan Lalu
			$tanggal_opening_balance_2 = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			//Satu Bulan Lalu
			$tanggal_opening_balance_3 = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			
			$harga_hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
			->from('hpp pp')
			->where("(pp.date_hpp between '$tanggal_opening_balance_2' and '$tanggal_opening_balance_3')")
			->get()->row_array();
			
			//file_put_contents("D:\\harga_hpp.txt", $this->db->last_query());

			$harga_opening_balance_abubatu_bulan_lalu = $harga_hpp['abubatu'];
			$harga_opening_balance_batu0510_bulan_lalu =  $harga_hpp['batu0510'];
			$harga_opening_balance_batu1020_bulan_lalu =  $harga_hpp['batu1020'];
			$harga_opening_balance_batu2030_bulan_lalu =  $harga_hpp['batu2030'];

			$vol_1 = round($volume_opening_balance_abubatu_bulan_lalu,2);
			$vol_2 = round($volume_opening_balance_batu0510_bulan_lalu,2);
			$vol_3 = round($volume_opening_balance_batu1020_bulan_lalu,2);
			$vol_4 = round($volume_opening_balance_batu2030_bulan_lalu,2);

			$nilai_opening_balance_abubatu_bulan_lalu = $vol_1 * $harga_opening_balance_abubatu_bulan_lalu;
			$nilai_opening_balance_batu0510_bulan_lalu = $vol_2 * $harga_opening_balance_batu0510_bulan_lalu;
			$nilai_opening_balance_batu1020_bulan_lalu = $vol_3 * $harga_opening_balance_batu1020_bulan_lalu;
			$nilai_opening_balance_batu2030_bulan_lalu = $vol_4 * $harga_opening_balance_batu2030_bulan_lalu;

			?>

			<!--- End Opening Balance --->

			<?php
			
			$produksi_harian_bulan_ini = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a as presentase_a, pk.presentase_b as presentase_b, pk.presentase_c as presentase_c, pk.presentase_d as presentase_d')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
			->where("(pph.date_prod between '$date1' and '$date2')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();
			
			//file_put_contents("D:\\produksi_harian_bulan_ini.txt", $this->db->last_query());

			$volume_produksi_harian_abubatu_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_a'];
			$volume_produksi_harian_batu0510_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_b'];
			$volume_produksi_harian_batu1020_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_c'];
			$volume_produksi_harian_batu2030_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_d'];
			
			$round_nilai_produksi_harian_abubatu_bulan_ini = $total_bpp * $produksi_harian_bulan_ini['presentase_a'] / 100;
			$round_nilai_produksi_harian_batu0510_bulan_ini = $total_bpp * $produksi_harian_bulan_ini['presentase_b'] / 100;
			$round_nilai_produksi_harian_batu1020_bulan_ini = $total_bpp * $produksi_harian_bulan_ini['presentase_c'] / 100;
			$round_nilai_produksi_harian_batu2030_bulan_ini = $total_bpp * $produksi_harian_bulan_ini['presentase_d'] / 100;

			$nilai_produksi_harian_abubatu_bulan_ini = round($round_nilai_produksi_harian_abubatu_bulan_ini,0);
			$nilai_produksi_harian_batu0510_bulan_ini = round($round_nilai_produksi_harian_batu0510_bulan_ini,0);
			$nilai_produksi_harian_batu1020_bulan_ini = round($round_nilai_produksi_harian_batu1020_bulan_ini,0);
			$nilai_produksi_harian_batu2030_bulan_ini = round($round_nilai_produksi_harian_batu2030_bulan_ini,0);

			$harga_produksi_harian_abubatu_bulan_ini = ($volume_produksi_harian_abubatu_bulan_ini!=0)?($nilai_produksi_harian_abubatu_bulan_ini / $volume_produksi_harian_abubatu_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu0510_bulan_ini = ($volume_produksi_harian_batu0510_bulan_ini!=0)?($nilai_produksi_harian_batu0510_bulan_ini / $volume_produksi_harian_batu0510_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu1020_bulan_ini = ($volume_produksi_harian_batu1020_bulan_ini!=0)?($nilai_produksi_harian_batu1020_bulan_ini / $volume_produksi_harian_batu1020_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu2030_bulan_ini = ($volume_produksi_harian_batu2030_bulan_ini!=0)?($nilai_produksi_harian_batu2030_bulan_ini / $volume_produksi_harian_batu2030_bulan_ini)  * 1:0;

			

			$volume_akhir_produksi_harian_abubatu_bulan_ini = $volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini;
			
			$harga_akhir_produksi_harian_abubatu_bulan_ini = ($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini;
			$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

			$volume_akhir_produksi_harian_batu0510_bulan_ini = $volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini;

			$harga_akhir_produksi_harian_batu0510_bulan_ini = ($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini;
			$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

			$volume_akhir_produksi_harian_batu1020_bulan_ini = $volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini;

			$harga_akhir_produksi_harian_batu1020_bulan_ini = ($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini;
			$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

			$volume_akhir_produksi_harian_batu2030_bulan_ini = $volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini;
			$harga_akhir_produksi_harian_batu2030_bulan_ini = ($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini;
			$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;
		
			//ABU BATU
			$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 7")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_abubatu_bulan_ini.txt", $this->db->last_query());

			$volume_penjualan_abubatu_bulan_ini = $penjualan_abubatu_bulan_ini['volume'];
			$harga_penjualan_abubatu_bulan_ini = $harga_akhir_produksi_harian_abubatu_bulan_ini;

			$harga_abubatu_fix = round($harga_penjualan_abubatu_bulan_ini,0);
			$nilai_penjualan_abubatu_bulan_ini = $volume_penjualan_abubatu_bulan_ini * $harga_abubatu_fix;

			$volume_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini - $volume_penjualan_abubatu_bulan_ini;
			$volume_akhir_penjualan_abubatu_bulan_ini_fix = round($volume_akhir_penjualan_abubatu_bulan_ini,2);
			$harga_akhir_penjualan_abubatu_bulan_ini = $harga_abubatu_fix;
			$nilai_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini_fix * $harga_akhir_penjualan_abubatu_bulan_ini;

			//BATU 0,5 - 10
			$penjualan_batu0510_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 8")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_batu0510_bulan_ini.txt", $this->db->last_query());

			$volume_penjualan_batu0510_bulan_ini = $penjualan_batu0510_bulan_ini['volume'];
			$harga_penjualan_batu0510_bulan_ini = $harga_akhir_produksi_harian_batu0510_bulan_ini;

			$harga_batu0510_fix = round($harga_penjualan_batu0510_bulan_ini,0);
			$nilai_penjualan_batu0510_bulan_ini = $volume_penjualan_batu0510_bulan_ini * $harga_batu0510_fix;

			$volume_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini - $volume_penjualan_batu0510_bulan_ini;
			$volume_akhir_penjualan_batu0510_bulan_ini_fix = round($volume_akhir_penjualan_batu0510_bulan_ini,2);
			$harga_akhir_penjualan_batu0510_bulan_ini =  $harga_batu0510_fix;
			$nilai_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini_fix * $harga_akhir_penjualan_batu0510_bulan_ini;

			//BATU 10 - 20
			$penjualan_batu1020_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 3")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_batu0510_bulan_ini.txt", $this->db->last_query());

			$volume_penjualan_batu1020_bulan_ini = $penjualan_batu1020_bulan_ini['volume'];
			$harga_penjualan_batu1020_bulan_ini = $harga_akhir_produksi_harian_batu1020_bulan_ini;
			
			$harga_batu1020_fix = round($harga_penjualan_batu1020_bulan_ini,0);
			$nilai_penjualan_batu1020_bulan_ini = $volume_penjualan_batu1020_bulan_ini * $harga_batu1020_fix;

			$volume_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini - $volume_penjualan_batu1020_bulan_ini;
			$volume_akhir_penjualan_batu1020_bulan_ini_fix = round($volume_akhir_penjualan_batu1020_bulan_ini,2);
			$harga_akhir_penjualan_batu1020_bulan_ini = $harga_batu1020_fix;
			$nilai_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini_fix * $harga_akhir_penjualan_batu1020_bulan_ini;

			//BATU 20 - 30
			$penjualan_batu2030_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 4")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\penjualan_batu2030_bulan_ini.txt", $this->db->last_query());

			$volume_penjualan_batu2030_bulan_ini = $penjualan_batu2030_bulan_ini['volume'];
			$harga_penjualan_batu2030_bulan_ini = $harga_akhir_produksi_harian_batu2030_bulan_ini;
			
			$harga_batu2030_fix = round($harga_penjualan_batu2030_bulan_ini,0);
			$nilai_penjualan_batu2030_bulan_ini = $volume_penjualan_batu2030_bulan_ini * $harga_batu2030_fix;

			$volume_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini - $volume_penjualan_batu2030_bulan_ini;
			$volume_akhir_penjualan_batu2030_bulan_ini_fix = round($volume_akhir_penjualan_batu2030_bulan_ini,2);
			$harga_akhir_penjualan_batu2030_bulan_ini = $harga_batu2030_fix;
			$nilai_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini_fix * $harga_akhir_penjualan_batu2030_bulan_ini;

			?>

			<!--- Aggregat  --->

			<?php
			
			$agregat_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 24")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\agregat_bulan_ini.txt", $this->db->last_query());

			$volume_agregat_abubatu_bulan_ini = $agregat_bulan_ini['volume_agregat_a'];
			$volume_agregat_batu0510_bulan_ini = $agregat_bulan_ini['volume_agregat_b'];
			$volume_agregat_batu1020_bulan_ini = $agregat_bulan_ini['volume_agregat_c'];
			$volume_agregat_batu2030_bulan_ini = $agregat_bulan_ini['volume_agregat_d'];

			$volume_agregat_abubatu_bulan_ini_fix = round($volume_agregat_abubatu_bulan_ini,2);
			$volume_agregat_batu0510_bulan_ini_fix = round($volume_agregat_batu0510_bulan_ini,2);
			$volume_agregat_batu1020_bulan_ini_fix = round($volume_agregat_batu1020_bulan_ini,2);
			$volume_agregat_batu2030_bulan_ini_fix = round($volume_agregat_batu2030_bulan_ini,2);

			$harga_agregat_abubatu_bulan_ini = $harga_abubatu_fix;
			$harga_agregat_batu0510_bulan_ini = $harga_batu0510_fix;
			$harga_agregat_batu1020_bulan_ini = $harga_batu1020_fix;
			$harga_agregat_batu2030_bulan_ini = $harga_batu2030_fix;

			$nilai_agregat_abubatu_bulan_ini = $volume_agregat_abubatu_bulan_ini_fix * $harga_agregat_abubatu_bulan_ini;
			$nilai_agregat_batu0510_bulan_ini = $volume_agregat_batu0510_bulan_ini_fix * $harga_agregat_batu0510_bulan_ini;
			$nilai_agregat_batu1020_bulan_ini = $volume_agregat_batu1020_bulan_ini_fix * $harga_agregat_batu1020_bulan_ini;
			$nilai_agregat_batu2030_bulan_ini = $volume_agregat_batu2030_bulan_ini_fix * $harga_agregat_batu2030_bulan_ini;

			$volume_akhir_agregat_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini;
			$volume_akhir_agregat_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini;
			$volume_akhir_agregat_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini;
			$volume_akhir_agregat_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini;

			$volume_akhir_agregat_abubatu_bulan_ini_fix = round($volume_akhir_agregat_abubatu_bulan_ini,2);
			$volume_akhir_agregat_batu0510_bulan_ini_fix = round($volume_akhir_agregat_batu0510_bulan_ini,2);
			$volume_akhir_agregat_batu1020_bulan_ini_fix = round($volume_akhir_agregat_batu1020_bulan_ini,2);
			$volume_akhir_agregat_batu2030_bulan_ini_fix = round($volume_akhir_agregat_batu2030_bulan_ini,2);

			$harga_akhir_agregat_abubatu_bulan_ini = $harga_agregat_abubatu_bulan_ini;
			$harga_akhir_agregat_batu0510_bulan_ini = $harga_agregat_batu0510_bulan_ini;
			$harga_akhir_agregat_batu1020_bulan_ini = $harga_agregat_batu1020_bulan_ini;
			$harga_akhir_agregat_batu2030_bulan_ini = $harga_agregat_batu2030_bulan_ini;

			$nilai_akhir_agregat_abubatu_bulan_ini = $volume_akhir_agregat_abubatu_bulan_ini_fix * $harga_akhir_agregat_abubatu_bulan_ini;
			$nilai_akhir_agregat_batu0510_bulan_ini = $volume_akhir_agregat_batu0510_bulan_ini_fix * $harga_akhir_agregat_batu0510_bulan_ini;
			$nilai_akhir_agregat_batu1020_bulan_ini = $volume_akhir_agregat_batu1020_bulan_ini_fix * $harga_akhir_agregat_batu1020_bulan_ini;
			$nilai_akhir_agregat_batu2030_bulan_ini = $volume_akhir_agregat_batu2030_bulan_ini_fix * $harga_akhir_agregat_batu2030_bulan_ini;

			$agregat_bulan_ini_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 14")
			->where("pp.status = 'PUBLISH'")
			//->where("po.status = 'OPEN'")
			->group_by('pp.product_id')
			->get()->row_array();
			
			//file_put_contents("D:\\aggregat_kelas_b.txt", $this->db->last_query());

			$volume_agregat_abubatu_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_a'];
			$volume_agregat_batu0510_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_b'];
			$volume_agregat_batu1020_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_c'];
			$volume_agregat_batu2030_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_d'];

			$volume_agregat_abubatu_bulan_ini_2_fix = round($volume_agregat_abubatu_bulan_ini_2,2);
			$volume_agregat_batu0510_bulan_ini_2_fix = round($volume_agregat_batu0510_bulan_ini_2,2);
			$volume_agregat_batu1020_bulan_ini_2_fix = round($volume_agregat_batu1020_bulan_ini_2,2);
			$volume_agregat_batu2030_bulan_ini_2_fix = round($volume_agregat_batu2030_bulan_ini_2,2);


			$harga_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini;
			$harga_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini;
			$harga_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini;
			$harga_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini;

			$nilai_agregat_abubatu_bulan_ini_2 = $volume_agregat_abubatu_bulan_ini_2_fix * $harga_agregat_abubatu_bulan_ini_2;
			$nilai_agregat_batu0510_bulan_ini_2 = $volume_agregat_batu0510_bulan_ini_2_fix * $harga_agregat_batu0510_bulan_ini_2;
			$nilai_agregat_batu1020_bulan_ini_2 = $volume_agregat_batu1020_bulan_ini_2_fix * $harga_agregat_batu1020_bulan_ini_2;
			$nilai_agregat_batu2030_bulan_ini_2 = $volume_agregat_batu2030_bulan_ini_2_fix * $harga_agregat_batu2030_bulan_ini_2;

			$volume_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini_2;
			$volume_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini_2;
			$volume_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini_2;
			$volume_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini_2;

			$harga_akhir_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini_2;
			$harga_akhir_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini_2;
			$harga_akhir_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini_2;
			$harga_akhir_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini_2;

			$volume_akhir_agregat_abubatu_bulan_ini_2_fix = round($volume_akhir_agregat_abubatu_bulan_ini_2,2);
			$volume_akhir_agregat_batu0510_bulan_ini_2_fix = round($volume_akhir_agregat_batu0510_bulan_ini_2,2);
			$volume_akhir_agregat_batu1020_bulan_ini_2_fix = round($volume_akhir_agregat_batu1020_bulan_ini_2,2);
			$volume_akhir_agregat_batu2030_bulan_ini_2_fix = round($volume_akhir_agregat_batu2030_bulan_ini_2,2);

			$nilai_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini_2_fix * $harga_akhir_agregat_abubatu_bulan_ini_2;
			$nilai_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini_2_fix * $harga_akhir_agregat_batu0510_bulan_ini_2;
			$nilai_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini_2_fix * $harga_akhir_agregat_batu1020_bulan_ini_2;
			$nilai_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini_2_fix * $harga_akhir_agregat_batu2030_bulan_ini_2;

			//TOTAL
			$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
			$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

			$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
			$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
			
			$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
			$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;
			
			?>

			<!--- End Agregat --->
			
			<!--- End Pergerakan Bahan Jadi -->


			<!-- Akumulasi --->
			<?php

			$akumulasi = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->result_array();

			$total_akumulasi = 0;

			foreach ($akumulasi as $a){
				$total_akumulasi += $a['total_nilai_keluar'];
			}

			$akumulasi_nilai = $total_akumulasi;
			
			//file_put_contents("D:\\akumulasi.txt", $this->db->last_query());

			?>

			<?php

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

			$biaya_umum_administratif = $biaya_umum_administratif_biaya['total'] + $biaya_umum_administratif_jurnal['total'];

			$biaya_lainnya = $biaya_lainnya_biaya['total'] + $biaya_lainnya_jurnal['total'];
			
			$alat = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar + $bbm_solar;

			$operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;

			$total_harga_pokok_pendapatan = $akumulasi_nilai;

			$laba_kotor = $total_penjualan_all - $total_harga_pokok_pendapatan;

			$total_biaya = $biaya_umum_administratif + $biaya_lainnya;

			$laba_sebelum_pajak = $laba_kotor - $total_biaya;

			//$persentase_laba_sebelum_pajak = ($total_penjualan_all!=0)?($laba_sebelum_pajak / $total_penjualan_all)  * 100:0;
			
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
				<th width="100%" align="left"><b>HARGA POKOK PENDAPATAN</b></th>
	        </tr>
			<tr class="table-active2">
				<th width="10%" align="center">5-50000</th>
				<th width="60%" align="left">Harga Pokok Pendapatan</th>
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
				<th width="100%" align="left"><b>BIAYA OPERASIONAL</b></th>
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
							<td align="center" colspan="2">
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
								Direktur</b>
							</td>
							<td align="center">
								<b><u>Erika Sinaga</u><br />
								Dir. Keuangan & SDM</b>
							</td>
							<td align="center">
								<b><u>Annisa Putri</u><br />
								Dir. Pemasaran & Pengembangan</b>
							</td>
							<td align="center" >
							<b><u>Hadi Sucipto</u><br />
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