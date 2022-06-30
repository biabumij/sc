<!DOCTYPE html>
<html>
	<head>
	  <title>PERGERAKAN BAHAN BAKU</title>
	  
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
		<br />
		<br />
		<table width="98%" cellpadding="3">
			<tr>
				<td align="center"  width="100%">
					<div style="display: block;font-weight: bold;font-size: 12px;">PERGERAKAN BAHAN BAKU</div>
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

			//TOTAL BAHAN BAKU
			$opening_balance_bahan_baku = $nilai_opening_balance + $nilai_opening_balance_solar;

			//TOTAL
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
				<th align="center"><?php echo number_format($opening_balance_bahan_baku,0,',','.');?></th>
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
								Ka. Plant</b>
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