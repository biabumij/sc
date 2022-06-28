<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model','admin/Templates'));
        $this->load->model('pmm_reports');
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		$this->m_admin->check_login();
	}
	
	public function pergerakan_bahan_baku($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #E8E8E8;
				color: red;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
		 </style>
	        <tr class="table-active2">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="9"><?php echo $filter_date;?></th>
	        </tr>
			
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
			
			<tr class="table-active4">
				<th width="30%" class="text-center" rowspan="2" style="vertical-align:middle">TANGGAL</th>
				<th width="20%" class="text-center" rowspan="2" style="vertical-align:middle">URAIAN</th>
				<th width="10%" class="text-center" rowspan="2" style="vertical-align:middle">SATUAN</th>
				<th width="20%" class="text-center" colspan="3">MASUK</th>
				<th width="20%" class="text-center" colspan="3">KELUAR</th>
				<th width="20%" class="text-center" colspan="3">AKHIR</th>
	        </tr>
			<tr class="table-active4">
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BATU BOULDER</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Pembelian</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku['nilai'],0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_pembelian_akhir,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_akhir,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_pembelian_akhir,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BBM SOLAR</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Pembelian</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku_solar['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku_solar['nilai'],0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_pembelian_akhir_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_akhir_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_pembelian_akhir_solar,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BAHAN BAKU</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($opening_balance_bahan_baku,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu Boulder</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku['nilai'],0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>BBM Solar</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku_solar['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku_solar['nilai'],0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>		
	        </tr>
			<tr class="table-active5">
	            <th class="text-center" colspan="3">TOTAL</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
	        </tr>
	    </table>
		<?php
	}
	
	public function nilai_persediaan_barang($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}

	    ?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #E8E8E8;
				font-weight: bold;
				font-size: 12px;
				color: red;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
		 </style>

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

		<tr class="table-active2">
			<th colspan="3">Periode</th>
			<th class="text-center" colspan="9"><?php echo $filter_date;?></th>
		</tr>
		<tr class="table-active4">
			<th width="10%" class="text-center" >TANGGAL</th>
			<th width="20%" class="text-center" >URAIAN</th>
			<th width="30%" class="text-center" >STOK BARANG</th>
			<th width="20%" class="text-center" >HARGA SATUAN</th>
			<th width="20%" class="text-center" >NILAI</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"><?php echo date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left">BATU BOULDER</th>
			<th class="text-center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"><?php echo date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left">BBM SOLAR</th>
			<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-right" colspan="4">TOTAL NILAI PERSEDIAAN</th>
			<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
		</tr>
	</table>
	<?php
	}
	
	public function pergerakan_bahan_jadi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #E8E8E8;
				color: red;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}

		 </style>
	        <tr class="table-active2">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="9"><?php echo $filter_date;?></th>
	        </tr>
		
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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

			$volume_produksi_harian_abubatu_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_a'],2);
			$volume_produksi_harian_batu0510_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_b'],2);
			$volume_produksi_harian_batu1020_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_c'],2);
			$volume_produksi_harian_batu2030_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_d'],2);
			
			$tidak_ada_produksi = $this->db->select('pp.date_akumulasi, pp.tidak_ada_produksi as total')
			->from('akumulasi_biaya pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->row_array();
			
			//file_put_contents("D:\\tidak_ada_produksi.txt", $this->db->last_query());

			$round_nilai_produksi_harian_abubatu_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_a'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu0510_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_b'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu1020_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_c'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu2030_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_d'] / 100) * $tidak_ada_produksi['total'];

			$nilai_produksi_harian_abubatu_bulan_ini = round($round_nilai_produksi_harian_abubatu_bulan_ini,0);
			$nilai_produksi_harian_batu0510_bulan_ini = round($round_nilai_produksi_harian_batu0510_bulan_ini,0);
			$nilai_produksi_harian_batu1020_bulan_ini = round($round_nilai_produksi_harian_batu1020_bulan_ini,0);
			$nilai_produksi_harian_batu2030_bulan_ini = round($round_nilai_produksi_harian_batu2030_bulan_ini,0);

			$harga_produksi_harian_abubatu_bulan_ini = ($volume_produksi_harian_abubatu_bulan_ini!=0)?($nilai_produksi_harian_abubatu_bulan_ini / $volume_produksi_harian_abubatu_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu0510_bulan_ini = ($volume_produksi_harian_batu0510_bulan_ini!=0)?($nilai_produksi_harian_batu0510_bulan_ini / $volume_produksi_harian_batu0510_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu1020_bulan_ini = ($volume_produksi_harian_batu1020_bulan_ini!=0)?($nilai_produksi_harian_batu1020_bulan_ini / $volume_produksi_harian_batu1020_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu2030_bulan_ini = ($volume_produksi_harian_batu2030_bulan_ini!=0)?($nilai_produksi_harian_batu2030_bulan_ini / $volume_produksi_harian_batu2030_bulan_ini)  * 1:0;

			$volume_akhir_produksi_harian_abubatu_bulan_ini = round($volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini,2);
			$harga_akhir_produksi_harian_abubatu_bulan_ini = ($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini;
			$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

			$volume_akhir_produksi_harian_batu0510_bulan_ini = round($volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini,2);
			$harga_akhir_produksi_harian_batu0510_bulan_ini = ($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini;
			$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

			$volume_akhir_produksi_harian_batu1020_bulan_ini = round($volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini,2);
			$harga_akhir_produksi_harian_batu1020_bulan_ini = ($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini;
			$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

			$volume_akhir_produksi_harian_batu2030_bulan_ini = round($volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini,2);
			$harga_akhir_produksi_harian_batu2030_bulan_ini = ($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini;
			$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;
		
			//ABU BATU
			$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 7")
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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

			//TOTAL BAHAN BAKU
			$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

			$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
			$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
			$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

			$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
			$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
			$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

			$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2;
			$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
			$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

			$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
			$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
			$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

			//TOTAL
			$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
			$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

			$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
			$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
			
			$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
			$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;
			
			?>

			<!--- End Agregat --->

			<tr class="table-active4">
				<th width="30%" class="text-center" rowspan="2" style="vertical-align:middle">TANGGAL</th>
				<th width="20%" class="text-center" rowspan="2" style="vertical-align:middle">URAIAN</th>
				<th width="10%" class="text-center" rowspan="2" style="vertical-align:middle">SATUAN</th>
				<th width="20%" class="text-center" colspan="3">MASUK</th>
				<th width="20%" class="text-center" colspan="3">KELUAR</th>
				<th width="20%" class="text-center" colspan="3">AKHIR</th>
	        </tr>
			<tr class="table-active4">
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 0,0 - 0,5</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_abubatu_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 0,5 - 10</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_batu0510_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 10 - 20</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_batu1020_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 20 - 30</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_batu2030_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BAHAN JADI</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($nilai_opening_bahan_jadi,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 0,0 - 0,5</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_abubatu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_abubatu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 0,5 - 10</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu0510,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu0510,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 10 - 20</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu1020,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu1020,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 20 - 30</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu2030,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu2030,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active5">
				<th class="text-center" colspan="3">TOTAL</th>
				<th class="text-center"><?php echo number_format($total_volume_masuk,2,',','.');?></th>
				<th class="text-right">-</th>
				<th class="text-right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_keluar,2,',','.');?></th>
				<th class="text-right">-</th>
				<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
				<th class="text-right">-</th>
				<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
			</tr>
	    </table>
		
		<?php
	}

	public function pergerakan_bahan_jadi_stok($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #E8E8E8;
				color: red;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}

		 </style>
	        <tr class="table-active2">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="9"><?php echo $filter_date;?></th>
	        </tr>
		
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

			$stock_opname_abubatu_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 7")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu0510_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 8")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu1020_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 3")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu2030_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 4")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//Opening Balance
			$volume_opening_balance_abubatu_bulan_lalu = $stock_opname_abubatu_ago['volume'];

			$volume_opening_balance_batu0510_bulan_lalu = $stock_opname_batu0510_ago['volume'];

			$volume_opening_balance_batu1020_bulan_lalu = $stock_opname_batu1020_ago['volume'];

			$volume_opening_balance_batu2030_bulan_lalu = $stock_opname_batu2030_ago['volume'];

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

			$volume_produksi_harian_abubatu_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_a'],2);
			$volume_produksi_harian_batu0510_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_b'],2);
			$volume_produksi_harian_batu1020_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_c'],2);
			$volume_produksi_harian_batu2030_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_d'],2);

			$tidak_ada_produksi = $this->db->select('pp.date_akumulasi, pp.tidak_ada_produksi as total')
			->from('akumulasi_biaya pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->row_array();
			
			//file_put_contents("D:\\tidak_ada_produksi.txt", $this->db->last_query());
			
			$round_nilai_produksi_harian_abubatu_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_a'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu0510_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_b'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu1020_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_c'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu2030_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_d'] / 100) * $tidak_ada_produksi['total'];

			$nilai_produksi_harian_abubatu_bulan_ini = round($round_nilai_produksi_harian_abubatu_bulan_ini,0);
			$nilai_produksi_harian_batu0510_bulan_ini = round($round_nilai_produksi_harian_batu0510_bulan_ini,0);
			$nilai_produksi_harian_batu1020_bulan_ini = round($round_nilai_produksi_harian_batu1020_bulan_ini,0);
			$nilai_produksi_harian_batu2030_bulan_ini = round($round_nilai_produksi_harian_batu2030_bulan_ini,0);

			$harga_produksi_harian_abubatu_bulan_ini = ($volume_produksi_harian_abubatu_bulan_ini!=0)?($nilai_produksi_harian_abubatu_bulan_ini / $volume_produksi_harian_abubatu_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu0510_bulan_ini = ($volume_produksi_harian_batu0510_bulan_ini!=0)?($nilai_produksi_harian_batu0510_bulan_ini / $volume_produksi_harian_batu0510_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu1020_bulan_ini = ($volume_produksi_harian_batu1020_bulan_ini!=0)?($nilai_produksi_harian_batu1020_bulan_ini / $volume_produksi_harian_batu1020_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu2030_bulan_ini = ($volume_produksi_harian_batu2030_bulan_ini!=0)?($nilai_produksi_harian_batu2030_bulan_ini / $volume_produksi_harian_batu2030_bulan_ini)  * 1:0;

			$volume_akhir_produksi_harian_abubatu_bulan_ini = round($volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini,2);
			$harga_akhir_produksi_harian_abubatu_bulan_ini = ($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini;
			$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

			$volume_akhir_produksi_harian_batu0510_bulan_ini = round($volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini,2);
			$harga_akhir_produksi_harian_batu0510_bulan_ini = ($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini;
			$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

			$volume_akhir_produksi_harian_batu1020_bulan_ini = round($volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini,2);
			$harga_akhir_produksi_harian_batu1020_bulan_ini = ($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini;
			$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

			$volume_akhir_produksi_harian_batu2030_bulan_ini = round($volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini,2);
			$harga_akhir_produksi_harian_batu2030_bulan_ini = ($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini;
			$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;
		
			//ABU BATU
			$stock_opname_abu_batu = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 7")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_abu_batu.txt", $this->db->last_query());
		
			$harga_abubatu_fix = round($harga_akhir_produksi_harian_abubatu_bulan_ini,0);

			$volume_akhir_penjualan_abubatu_bulan_ini = $stock_opname_abu_batu['volume'];
			$harga_akhir_penjualan_abubatu_bulan_ini = $harga_abubatu_fix;
			$nilai_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini *$harga_akhir_penjualan_abubatu_bulan_ini;

			$volume_penjualan_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini - $volume_akhir_penjualan_abubatu_bulan_ini;
			$harga_penjualan_abubatu_bulan_ini = $harga_akhir_penjualan_abubatu_bulan_ini;
			$nilai_penjualan_abubatu_bulan_ini = $volume_penjualan_abubatu_bulan_ini * $harga_penjualan_abubatu_bulan_ini;

			//BATU 0,5 - 10
			$stock_opname_batu0510 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 8")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_batu0510.txt", $this->db->last_query());
		
			$harga_batu0510_fix = round($harga_akhir_produksi_harian_batu0510_bulan_ini,0);

			$volume_akhir_penjualan_batu0510_bulan_ini = $stock_opname_batu0510['volume'];
			$harga_akhir_penjualan_batu0510_bulan_ini = $harga_batu0510_fix;
			$nilai_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini *$harga_akhir_penjualan_batu0510_bulan_ini;

			$volume_penjualan_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini - $volume_akhir_penjualan_batu0510_bulan_ini;
			$harga_penjualan_batu0510_bulan_ini = $harga_akhir_penjualan_batu0510_bulan_ini;
			$nilai_penjualan_batu0510_bulan_ini = $volume_penjualan_batu0510_bulan_ini * $harga_penjualan_batu0510_bulan_ini;

			//BATU 10 - 20
			$stock_opname_batu1020 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 3")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_batu1020.txt", $this->db->last_query());
		
			$harga_batu1020_fix = round($harga_akhir_produksi_harian_batu1020_bulan_ini,0);

			$volume_akhir_penjualan_batu1020_bulan_ini = $stock_opname_batu1020['volume'];
			$harga_akhir_penjualan_batu1020_bulan_ini = $harga_batu1020_fix;
			$nilai_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini *$harga_akhir_penjualan_batu1020_bulan_ini;

			$volume_penjualan_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini - $volume_akhir_penjualan_batu1020_bulan_ini;
			$harga_penjualan_batu1020_bulan_ini = $harga_akhir_penjualan_batu1020_bulan_ini;
			$nilai_penjualan_batu1020_bulan_ini = $volume_penjualan_batu1020_bulan_ini * $harga_penjualan_batu1020_bulan_ini;

			//BATU 20 - 30
			$stock_opname_batu2030 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 4")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_batu2030.txt", $this->db->last_query());
		
			$harga_batu2030_fix = round($harga_akhir_produksi_harian_batu2030_bulan_ini,0);

			$volume_akhir_penjualan_batu2030_bulan_ini = $stock_opname_batu2030['volume'];
			$harga_akhir_penjualan_batu2030_bulan_ini = $harga_batu2030_fix;
			$nilai_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini *$harga_akhir_penjualan_batu2030_bulan_ini;

			$volume_penjualan_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini - $volume_akhir_penjualan_batu2030_bulan_ini;
			$harga_penjualan_batu2030_bulan_ini = $harga_akhir_penjualan_batu2030_bulan_ini;
			$nilai_penjualan_batu2030_bulan_ini = $volume_penjualan_batu2030_bulan_ini * $harga_penjualan_batu2030_bulan_ini;

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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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

			//TOTAL BAHAN BAKU
			$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

			$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
			$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
			$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

			$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
			$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
			$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

			$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2;
			$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
			$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

			$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
			$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
			$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

			//TOTAL
			$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
			$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

			$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
			$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
			
			$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
			$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;
			
			?>

			<!--- End Agregat --->

			<tr class="table-active4">
				<th width="30%" class="text-center" rowspan="2" style="vertical-align:middle">TANGGAL</th>
				<th width="20%" class="text-center" rowspan="2" style="vertical-align:middle">URAIAN</th>
				<th width="10%" class="text-center" rowspan="2" style="vertical-align:middle">SATUAN</th>
				<th width="20%" class="text-center" colspan="3">MASUK</th>
				<th width="20%" class="text-center" colspan="3">KELUAR</th>
				<th width="20%" class="text-center" colspan="3">AKHIR</th>
	        </tr>
			<tr class="table-active4">
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 0,0 - 0,5</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_abubatu_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 0,5 - 10</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_batu0510_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 10 - 20</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_batu1020_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BATU 20 - 30</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_batu2030_bulan_lalu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat A)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Penjualan (Agregat B)</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center">-</th>
				<th class="text-right">-</th>
				<th class="text-right">-</th>
				<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
				<th class="text-center" colspan="12">BAHAN JADI</th>
			</tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
				<th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($nilai_opening_bahan_jadi,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 0,0 - 0,5</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_abubatu,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_abubatu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 0,5 - 10</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu0510,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu0510,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 10 - 20</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu1020,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu1020,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu 20 - 30</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu2030,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu2030,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
	        </tr>
			<tr class="table-active5">
				<th class="text-center" colspan="3">TOTAL</th>
				<th class="text-center"><?php echo number_format($total_volume_masuk,2,',','.');?></th>
				<th class="text-right">-</th>
				<th class="text-right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_keluar,2,',','.');?></th>
				<th class="text-right">-</th>
				<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
				<th class="text-right">-</th>
				<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
			</tr>
	    </table>
		
		<?php
	}

	public function evaluasi_pergerakan_bahan_jadi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #E8E8E8;
				color: red;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}

		 </style>
	        <tr class="table-active2">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="9"><?php echo $filter_date;?></th>
	        </tr>
		
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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

			$volume_produksi_harian_abubatu_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_a'],2);
			$volume_produksi_harian_batu0510_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_b'],2);
			$volume_produksi_harian_batu1020_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_c'],2);
			$volume_produksi_harian_batu2030_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_d'],2);
			
			$tidak_ada_produksi = $this->db->select('pp.date_akumulasi, pp.tidak_ada_produksi as total')
			->from('akumulasi_biaya pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->row_array();
			
			//file_put_contents("D:\\tidak_ada_produksi.txt", $this->db->last_query());
			
			$round_nilai_produksi_harian_abubatu_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_a'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu0510_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_b'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu1020_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_c'] / 100) * $tidak_ada_produksi['total'];
			$round_nilai_produksi_harian_batu2030_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_d'] / 100) * $tidak_ada_produksi['total'];

			$nilai_produksi_harian_abubatu_bulan_ini = round($round_nilai_produksi_harian_abubatu_bulan_ini,0);
			$nilai_produksi_harian_batu0510_bulan_ini = round($round_nilai_produksi_harian_batu0510_bulan_ini,0);
			$nilai_produksi_harian_batu1020_bulan_ini = round($round_nilai_produksi_harian_batu1020_bulan_ini,0);
			$nilai_produksi_harian_batu2030_bulan_ini = round($round_nilai_produksi_harian_batu2030_bulan_ini,0);

			$harga_produksi_harian_abubatu_bulan_ini = ($volume_produksi_harian_abubatu_bulan_ini!=0)?($nilai_produksi_harian_abubatu_bulan_ini / $volume_produksi_harian_abubatu_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu0510_bulan_ini = ($volume_produksi_harian_batu0510_bulan_ini!=0)?($nilai_produksi_harian_batu0510_bulan_ini / $volume_produksi_harian_batu0510_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu1020_bulan_ini = ($volume_produksi_harian_batu1020_bulan_ini!=0)?($nilai_produksi_harian_batu1020_bulan_ini / $volume_produksi_harian_batu1020_bulan_ini)  * 1:0;
			$harga_produksi_harian_batu2030_bulan_ini = ($volume_produksi_harian_batu2030_bulan_ini!=0)?($nilai_produksi_harian_batu2030_bulan_ini / $volume_produksi_harian_batu2030_bulan_ini)  * 1:0;

			$volume_akhir_produksi_harian_abubatu_bulan_ini = round($volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini,2);
			$harga_akhir_produksi_harian_abubatu_bulan_ini = ($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini;
			$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

			$volume_akhir_produksi_harian_batu0510_bulan_ini = round($volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini,2);
			$harga_akhir_produksi_harian_batu0510_bulan_ini = ($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini;
			$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

			$volume_akhir_produksi_harian_batu1020_bulan_ini = round($volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini,2);
			$harga_akhir_produksi_harian_batu1020_bulan_ini = ($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini;
			$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

			$volume_akhir_produksi_harian_batu2030_bulan_ini = round($volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini,2);
			$harga_akhir_produksi_harian_batu2030_bulan_ini = ($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini;
			$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;
		
			//ABU BATU
			$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
			->from('pmm_productions pp')
			->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
			->join('produk p', 'pp.product_id = p.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 7")
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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
			->where("po.status in ('OPEN','CLOSED')")
			->where("pp.status = 'PUBLISH'")
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

			//TOTAL BAHAN BAKU
			$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

			$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
			$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
			$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

			$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
			$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
			$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

			$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2;
			$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
			$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

			$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
			$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
			$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

			//TOTAL
			$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
			$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

			$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
			$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
			
			$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
			$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;
			
			?>

			<!--- End Agregat --->

			<!-- PERGERAKAN BAHAN JADI STOK -->

			<!--- Opening Balance --->

			<?php

			$stock_opname_abubatu_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 7")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu0510_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 8")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu1020_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 3")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu2030_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date1')")
			->where("cat.material_id = 4")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();
			


			//Opening Balance
			$stok_volume_opening_balance_abubatu_bulan_lalu = $stock_opname_abubatu_ago['volume'];

			$stok_volume_opening_balance_batu0510_bulan_lalu = $stock_opname_batu0510_ago['volume'];

			$stok_volume_opening_balance_batu1020_bulan_lalu = $stock_opname_batu1020_ago['volume'];

			$stok_volume_opening_balance_batu2030_bulan_lalu = $stock_opname_batu2030_ago['volume'];


			$stok_vol_1 = round($stok_volume_opening_balance_abubatu_bulan_lalu,2);
			$stok_vol_2 = round($stok_volume_opening_balance_batu0510_bulan_lalu,2);
			$stok_vol_3 = round($stok_volume_opening_balance_batu1020_bulan_lalu,2);
			$stok_vol_4 = round($stok_volume_opening_balance_batu2030_bulan_lalu,2);

			$stok_nilai_opening_balance_abubatu_bulan_lalu = $stok_vol_1 * $harga_opening_balance_abubatu_bulan_lalu;
			$stok_nilai_opening_balance_batu0510_bulan_lalu = $stok_vol_2 * $harga_opening_balance_batu0510_bulan_lalu;
			$stok_nilai_opening_balance_batu1020_bulan_lalu = $stok_vol_3 * $harga_opening_balance_batu1020_bulan_lalu;
			$stok_nilai_opening_balance_batu2030_bulan_lalu = $stok_vol_4 * $harga_opening_balance_batu2030_bulan_lalu;

			?>

			<!--- End Opening Balance --->

			<?php

			$stok_volume_produksi_harian_abubatu_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_a'],2);
			$stok_volume_produksi_harian_batu0510_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_b'],2);
			$stok_volume_produksi_harian_batu1020_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_c'],2);
			$stok_volume_produksi_harian_batu2030_bulan_ini = round($produksi_harian_bulan_ini['jumlah_pemakaian_d'],2);
			
			$stok_round_nilai_produksi_harian_abubatu_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_a'] / 100) * $tidak_ada_produksi['total'];
			$stok_round_nilai_produksi_harian_batu0510_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_b'] / 100) * $tidak_ada_produksi['total'];
			$stok_round_nilai_produksi_harian_batu1020_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_c'] / 100) * $tidak_ada_produksi['total'];
			$stok_round_nilai_produksi_harian_batu2030_bulan_ini = ($total_bpp * $produksi_harian_bulan_ini['presentase_d'] / 100) * $tidak_ada_produksi['total'];

			$stok_nilai_produksi_harian_abubatu_bulan_ini = round($stok_round_nilai_produksi_harian_abubatu_bulan_ini,0);
			$stok_nilai_produksi_harian_batu0510_bulan_ini = round($stok_round_nilai_produksi_harian_batu0510_bulan_ini,0);
			$stok_nilai_produksi_harian_batu1020_bulan_ini = round($stok_round_nilai_produksi_harian_batu1020_bulan_ini,0);
			$stok_nilai_produksi_harian_batu2030_bulan_ini = round($stok_round_nilai_produksi_harian_batu2030_bulan_ini,0);

			$stok_harga_produksi_harian_abubatu_bulan_ini = ($stok_volume_produksi_harian_abubatu_bulan_ini!=0)?($stok_nilai_produksi_harian_abubatu_bulan_ini / $stok_volume_produksi_harian_abubatu_bulan_ini)  * 1:0;
			$stok_harga_produksi_harian_batu0510_bulan_ini = ($stok_volume_produksi_harian_batu0510_bulan_ini!=0)?($stok_nilai_produksi_harian_batu0510_bulan_ini / $stok_volume_produksi_harian_batu0510_bulan_ini)  * 1:0;
			$stok_harga_produksi_harian_batu1020_bulan_ini = ($stok_volume_produksi_harian_batu1020_bulan_ini!=0)?($stok_nilai_produksi_harian_batu1020_bulan_ini / $stok_volume_produksi_harian_batu1020_bulan_ini)  * 1:0;
			$stok_harga_produksi_harian_batu2030_bulan_ini = ($stok_volume_produksi_harian_batu2030_bulan_ini!=0)?($stok_nilai_produksi_harian_batu2030_bulan_ini / $stok_volume_produksi_harian_batu2030_bulan_ini)  * 1:0;	

			$stok_volume_akhir_produksi_harian_abubatu_bulan_ini = round($stok_volume_opening_balance_abubatu_bulan_lalu + $stok_volume_produksi_harian_abubatu_bulan_ini,2);
			$stok_harga_akhir_produksi_harian_abubatu_bulan_ini = ($stok_nilai_opening_balance_abubatu_bulan_lalu + $stok_nilai_produksi_harian_abubatu_bulan_ini) / $stok_volume_akhir_produksi_harian_abubatu_bulan_ini;
			$stok_nilai_akhir_produksi_harian_abubatu_bulan_ini = $stok_volume_akhir_produksi_harian_abubatu_bulan_ini * $stok_harga_akhir_produksi_harian_abubatu_bulan_ini;

			$stok_volume_akhir_produksi_harian_batu0510_bulan_ini = round($stok_volume_opening_balance_batu0510_bulan_lalu + $stok_volume_produksi_harian_batu0510_bulan_ini,2);
			$stok_harga_akhir_produksi_harian_batu0510_bulan_ini = ($stok_nilai_opening_balance_batu0510_bulan_lalu + $stok_nilai_produksi_harian_batu0510_bulan_ini) / $stok_volume_akhir_produksi_harian_batu0510_bulan_ini;
			$stok_nilai_akhir_produksi_harian_batu0510_bulan_ini = $stok_volume_akhir_produksi_harian_batu0510_bulan_ini * $stok_harga_akhir_produksi_harian_batu0510_bulan_ini;

			$stok_volume_akhir_produksi_harian_batu1020_bulan_ini = round($stok_volume_opening_balance_batu1020_bulan_lalu + $stok_volume_produksi_harian_batu1020_bulan_ini,2);
			$stok_harga_akhir_produksi_harian_batu1020_bulan_ini = ($stok_nilai_opening_balance_batu1020_bulan_lalu + $stok_nilai_produksi_harian_batu1020_bulan_ini) / $stok_volume_akhir_produksi_harian_batu1020_bulan_ini;
			$stok_nilai_akhir_produksi_harian_batu1020_bulan_ini = $stok_volume_akhir_produksi_harian_batu1020_bulan_ini * $stok_harga_akhir_produksi_harian_batu1020_bulan_ini;

			$stok_volume_akhir_produksi_harian_batu2030_bulan_ini = round($stok_volume_opening_balance_batu2030_bulan_lalu + $stok_volume_produksi_harian_batu2030_bulan_ini,2);
			$stok_harga_akhir_produksi_harian_batu2030_bulan_ini = ($stok_nilai_opening_balance_batu2030_bulan_lalu + $stok_nilai_produksi_harian_batu2030_bulan_ini) / $stok_volume_akhir_produksi_harian_batu2030_bulan_ini;
			$stok_nilai_akhir_produksi_harian_batu2030_bulan_ini = $stok_volume_akhir_produksi_harian_batu2030_bulan_ini * $stok_harga_akhir_produksi_harian_batu2030_bulan_ini;
		
			//ABU BATU
			$stock_opname_abu_batu = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 7")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_abu_batu.txt", $this->db->last_query());
		
			$stok_harga_abubatu_fix = round($stok_harga_akhir_produksi_harian_abubatu_bulan_ini,0);

			$stok_volume_akhir_penjualan_abubatu_bulan_ini = $stock_opname_abu_batu['volume'];
			$stok_harga_akhir_penjualan_abubatu_bulan_ini = $stok_harga_abubatu_fix;
			$stok_nilai_akhir_penjualan_abubatu_bulan_ini = $stok_volume_akhir_penjualan_abubatu_bulan_ini * $stok_harga_akhir_penjualan_abubatu_bulan_ini;

			$stok_volume_penjualan_abubatu_bulan_ini = $stok_volume_akhir_produksi_harian_abubatu_bulan_ini - $stok_volume_akhir_penjualan_abubatu_bulan_ini;
			$stok_harga_penjualan_abubatu_bulan_ini = $stok_harga_akhir_penjualan_abubatu_bulan_ini;
			$stok_nilai_penjualan_abubatu_bulan_ini = $stok_volume_penjualan_abubatu_bulan_ini * $stok_harga_penjualan_abubatu_bulan_ini;

			//BATU 0,5 - 10
			$stock_opname_batu0510 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 8")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_batu0510.txt", $this->db->last_query());
		
			$stok_harga_batu0510_fix = round($stok_harga_akhir_produksi_harian_batu0510_bulan_ini,0);

			$stok_volume_akhir_penjualan_batu0510_bulan_ini = $stock_opname_batu0510['volume'];
			$stok_harga_akhir_penjualan_batu0510_bulan_ini = $stok_harga_batu0510_fix;
			$stok_nilai_akhir_penjualan_batu0510_bulan_ini = $stok_volume_akhir_penjualan_batu0510_bulan_ini *$stok_harga_akhir_penjualan_batu0510_bulan_ini;

			$stok_volume_penjualan_batu0510_bulan_ini = $stok_volume_akhir_produksi_harian_batu0510_bulan_ini - $stok_volume_akhir_penjualan_batu0510_bulan_ini;
			$stok_harga_penjualan_batu0510_bulan_ini = $stok_harga_akhir_penjualan_batu0510_bulan_ini;
			$stok_nilai_penjualan_batu0510_bulan_ini = $stok_volume_penjualan_batu0510_bulan_ini * $stok_harga_penjualan_batu0510_bulan_ini;

			//BATU 10 - 20
			$stock_opname_batu1020 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 3")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_batu1020.txt", $this->db->last_query());
		
			$stok_harga_batu1020_fix = round($stok_harga_akhir_produksi_harian_batu1020_bulan_ini,0);

			$stok_volume_akhir_penjualan_batu1020_bulan_ini = $stock_opname_batu1020['volume'];
			$stok_harga_akhir_penjualan_batu1020_bulan_ini = $stok_harga_batu1020_fix;
			$stok_nilai_akhir_penjualan_batu1020_bulan_ini = $stok_volume_akhir_penjualan_batu1020_bulan_ini * $stok_harga_akhir_penjualan_batu1020_bulan_ini;

			$stok_volume_penjualan_batu1020_bulan_ini = $stok_volume_akhir_produksi_harian_batu1020_bulan_ini - $stok_volume_akhir_penjualan_batu1020_bulan_ini;
			$stok_harga_penjualan_batu1020_bulan_ini = $stok_harga_akhir_penjualan_batu1020_bulan_ini;
			$stok_nilai_penjualan_batu1020_bulan_ini = $stok_volume_penjualan_batu1020_bulan_ini * $stok_harga_penjualan_batu1020_bulan_ini;

			//BATU 20 - 30
			$stock_opname_batu2030 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("cat.date between '$date1' and '$date2'")
			->where("cat.material_id = 4")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			//file_put_contents("D:\\stock_opname_batu2030.txt", $this->db->last_query());
		
			$stok_harga_batu2030_fix = round($stok_harga_akhir_produksi_harian_batu2030_bulan_ini,0);

			$stok_volume_akhir_penjualan_batu2030_bulan_ini = $stock_opname_batu2030['volume'];
			$stok_harga_akhir_penjualan_batu2030_bulan_ini = $stok_harga_batu2030_fix;
			$stok_nilai_akhir_penjualan_batu2030_bulan_ini = $stok_volume_akhir_penjualan_batu2030_bulan_ini * $stok_harga_akhir_penjualan_batu2030_bulan_ini;

			$stok_volume_penjualan_batu2030_bulan_ini = $stok_volume_akhir_produksi_harian_batu2030_bulan_ini - $stok_volume_akhir_penjualan_batu2030_bulan_ini;
			$stok_harga_penjualan_batu2030_bulan_ini = $stok_harga_akhir_penjualan_batu2030_bulan_ini;
			$stok_nilai_penjualan_batu2030_bulan_ini = $stok_volume_penjualan_batu2030_bulan_ini * $stok_harga_penjualan_batu2030_bulan_ini;

			?>

			<!--- Aggregat  --->

			<?php

			$stok_volume_agregat_abubatu_bulan_ini = $agregat_bulan_ini['volume_agregat_a'];
			$stok_volume_agregat_batu0510_bulan_ini = $agregat_bulan_ini['volume_agregat_b'];
			$stok_volume_agregat_batu1020_bulan_ini = $agregat_bulan_ini['volume_agregat_c'];
			$stok_volume_agregat_batu2030_bulan_ini = $agregat_bulan_ini['volume_agregat_d'];

			$stok_volume_agregat_abubatu_bulan_ini_fix = round($stok_volume_agregat_abubatu_bulan_ini,2);
			$stok_volume_agregat_batu0510_bulan_ini_fix = round($stok_volume_agregat_batu0510_bulan_ini,2);
			$stok_volume_agregat_batu1020_bulan_ini_fix = round($stok_volume_agregat_batu1020_bulan_ini,2);
			$stok_volume_agregat_batu2030_bulan_ini_fix = round($stok_volume_agregat_batu2030_bulan_ini,2);

			$stok_harga_agregat_abubatu_bulan_ini = $stok_harga_abubatu_fix;
			$stok_harga_agregat_batu0510_bulan_ini = $stok_harga_batu0510_fix;
			$stok_harga_agregat_batu1020_bulan_ini = $stok_harga_batu1020_fix;
			$stok_harga_agregat_batu2030_bulan_ini = $stok_harga_batu2030_fix;

			$stok_nilai_agregat_abubatu_bulan_ini = $stok_volume_agregat_abubatu_bulan_ini_fix * $stok_harga_agregat_abubatu_bulan_ini;
			$stok_nilai_agregat_batu0510_bulan_ini = $stok_volume_agregat_batu0510_bulan_ini_fix * $stok_harga_agregat_batu0510_bulan_ini;
			$stok_nilai_agregat_batu1020_bulan_ini = $stok_volume_agregat_batu1020_bulan_ini_fix * $stok_harga_agregat_batu1020_bulan_ini;
			$stok_nilai_agregat_batu2030_bulan_ini = $stok_volume_agregat_batu2030_bulan_ini_fix * $stok_harga_agregat_batu2030_bulan_ini;

			$stok_volume_akhir_agregat_abubatu_bulan_ini = $stok_volume_akhir_penjualan_abubatu_bulan_ini - $stok_volume_agregat_abubatu_bulan_ini;
			$stok_volume_akhir_agregat_batu0510_bulan_ini = $stok_volume_akhir_penjualan_batu0510_bulan_ini - $stok_volume_agregat_batu0510_bulan_ini;
			$stok_volume_akhir_agregat_batu1020_bulan_ini = $stok_volume_akhir_penjualan_batu1020_bulan_ini - $stok_volume_agregat_batu1020_bulan_ini;
			$stok_volume_akhir_agregat_batu2030_bulan_ini = $stok_volume_akhir_penjualan_batu2030_bulan_ini - $stok_volume_agregat_batu2030_bulan_ini;

			$stok_volume_akhir_agregat_abubatu_bulan_ini_fix = round($stok_volume_akhir_agregat_abubatu_bulan_ini,2);
			$stok_volume_akhir_agregat_batu0510_bulan_ini_fix = round($stok_volume_akhir_agregat_batu0510_bulan_ini,2);
			$stok_volume_akhir_agregat_batu1020_bulan_ini_fix = round($stok_volume_akhir_agregat_batu1020_bulan_ini,2);
			$stok_volume_akhir_agregat_batu2030_bulan_ini_fix = round($stok_volume_akhir_agregat_batu2030_bulan_ini,2);

			$stok_harga_akhir_agregat_abubatu_bulan_ini = $stok_harga_agregat_abubatu_bulan_ini;
			$stok_harga_akhir_agregat_batu0510_bulan_ini = $stok_harga_agregat_batu0510_bulan_ini;
			$stok_harga_akhir_agregat_batu1020_bulan_ini = $stok_harga_agregat_batu1020_bulan_ini;
			$stok_harga_akhir_agregat_batu2030_bulan_ini = $stok_harga_agregat_batu2030_bulan_ini;

			$stok_nilai_akhir_agregat_abubatu_bulan_ini = $stok_volume_akhir_agregat_abubatu_bulan_ini_fix * $stok_harga_akhir_agregat_abubatu_bulan_ini;
			$stok_nilai_akhir_agregat_batu0510_bulan_ini = $stok_volume_akhir_agregat_batu0510_bulan_ini_fix * $stok_harga_akhir_agregat_batu0510_bulan_ini;
			$stok_nilai_akhir_agregat_batu1020_bulan_ini = $stok_volume_akhir_agregat_batu1020_bulan_ini_fix * $stok_harga_akhir_agregat_batu1020_bulan_ini;
			$stok_nilai_akhir_agregat_batu2030_bulan_ini = $stok_volume_akhir_agregat_batu2030_bulan_ini_fix * $stok_harga_akhir_agregat_batu2030_bulan_ini;

			$stok_volume_agregat_abubatu_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_a'];
			$stok_volume_agregat_batu0510_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_b'];
			$stok_volume_agregat_batu1020_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_c'];
			$stok_volume_agregat_batu2030_bulan_ini_2 = $agregat_bulan_ini_2['volume_agregat_d'];

			$stok_volume_agregat_abubatu_bulan_ini_2_fix = round($stok_volume_agregat_abubatu_bulan_ini_2,2);
			$stok_volume_agregat_batu0510_bulan_ini_2_fix = round($stok_volume_agregat_batu0510_bulan_ini_2,2);
			$stok_volume_agregat_batu1020_bulan_ini_2_fix = round($stok_volume_agregat_batu1020_bulan_ini_2,2);
			$stok_volume_agregat_batu2030_bulan_ini_2_fix = round($stok_volume_agregat_batu2030_bulan_ini_2,2);

			$stok_harga_agregat_abubatu_bulan_ini_2 = $stok_harga_agregat_abubatu_bulan_ini;
			$stok_harga_agregat_batu0510_bulan_ini_2 = $stok_harga_agregat_batu0510_bulan_ini;
			$stok_harga_agregat_batu1020_bulan_ini_2 = $stok_harga_agregat_batu1020_bulan_ini;
			$stok_harga_agregat_batu2030_bulan_ini_2 = $stok_harga_agregat_batu2030_bulan_ini;

			$stok_nilai_agregat_abubatu_bulan_ini_2 = $stok_volume_agregat_abubatu_bulan_ini_2_fix * $stok_harga_agregat_abubatu_bulan_ini_2;
			$stok_nilai_agregat_batu0510_bulan_ini_2 = $stok_volume_agregat_batu0510_bulan_ini_2_fix * $stok_harga_agregat_batu0510_bulan_ini_2;
			$stok_nilai_agregat_batu1020_bulan_ini_2 = $stok_volume_agregat_batu1020_bulan_ini_2_fix * $stok_harga_agregat_batu1020_bulan_ini_2;
			$stok_nilai_agregat_batu2030_bulan_ini_2 = $stok_volume_agregat_batu2030_bulan_ini_2_fix * $stok_harga_agregat_batu2030_bulan_ini_2;

			$stok_volume_akhir_agregat_abubatu_bulan_ini_2 = $stok_volume_akhir_agregat_abubatu_bulan_ini - $stok_volume_agregat_abubatu_bulan_ini_2;
			$stok_volume_akhir_agregat_batu0510_bulan_ini_2 = $stok_volume_akhir_agregat_batu0510_bulan_ini - $stok_volume_agregat_batu0510_bulan_ini_2;
			$stok_volume_akhir_agregat_batu1020_bulan_ini_2 = $stok_volume_akhir_agregat_batu1020_bulan_ini - $stok_volume_agregat_batu1020_bulan_ini_2;
			$stok_volume_akhir_agregat_batu2030_bulan_ini_2 = $stok_volume_akhir_agregat_batu2030_bulan_ini - $stok_volume_agregat_batu2030_bulan_ini_2;

			$stok_harga_akhir_agregat_abubatu_bulan_ini_2 = $stok_harga_agregat_abubatu_bulan_ini_2;
			$stok_harga_akhir_agregat_batu0510_bulan_ini_2 = $stok_harga_agregat_batu0510_bulan_ini_2;
			$stok_harga_akhir_agregat_batu1020_bulan_ini_2 = $stok_harga_agregat_batu1020_bulan_ini_2;
			$stok_harga_akhir_agregat_batu2030_bulan_ini_2 = $stok_harga_agregat_batu2030_bulan_ini_2;

			$stok_volume_akhir_agregat_abubatu_bulan_ini_2_fix = round($stok_volume_akhir_agregat_abubatu_bulan_ini_2,2);
			$stok_volume_akhir_agregat_batu0510_bulan_ini_2_fix = round($stok_volume_akhir_agregat_batu0510_bulan_ini_2,2);
			$stok_volume_akhir_agregat_batu1020_bulan_ini_2_fix = round($stok_volume_akhir_agregat_batu1020_bulan_ini_2,2);
			$stok_volume_akhir_agregat_batu2030_bulan_ini_2_fix = round($stok_volume_akhir_agregat_batu2030_bulan_ini_2,2);

			$stok_nilai_akhir_agregat_abubatu_bulan_ini_2 = $stok_volume_akhir_agregat_abubatu_bulan_ini_2_fix * $stok_harga_akhir_agregat_abubatu_bulan_ini_2;
			$stok_nilai_akhir_agregat_batu0510_bulan_ini_2 = $stok_volume_akhir_agregat_batu0510_bulan_ini_2_fix * $stok_harga_akhir_agregat_batu0510_bulan_ini_2;
			$stok_nilai_akhir_agregat_batu1020_bulan_ini_2 = $stok_volume_akhir_agregat_batu1020_bulan_ini_2_fix * $stok_harga_akhir_agregat_batu1020_bulan_ini_2;
			$stok_nilai_akhir_agregat_batu2030_bulan_ini_2 = $stok_volume_akhir_agregat_batu2030_bulan_ini_2_fix * $stok_harga_akhir_agregat_batu2030_bulan_ini_2;

			//TOTAL BAHAN BAKU
			$stok_nilai_opening_bahan_jadi = $stok_nilai_opening_balance_abubatu_bulan_lalu + $stok_nilai_opening_balance_batu0510_bulan_lalu + $stok_nilai_opening_balance_batu1020_bulan_lalu + $stok_nilai_opening_balance_batu2030_bulan_lalu;

			$stok_volume_penjualan_abubatu = $stok_volume_penjualan_abubatu_bulan_ini + $stok_volume_agregat_abubatu_bulan_ini + $stok_volume_agregat_abubatu_bulan_ini_2;
			$stok_nilai_penjualan_abubatu = $stok_nilai_penjualan_abubatu_bulan_ini + $stok_nilai_agregat_abubatu_bulan_ini + $stok_nilai_agregat_abubatu_bulan_ini_2;
			$stok_harga_penjualan_abubatu = ($stok_volume_penjualan_abubatu!=0)?($stok_nilai_penjualan_abubatu / $stok_volume_penjualan_abubatu)  * 1:0;

			$stok_volume_penjualan_batu0510 = $stok_volume_penjualan_batu0510_bulan_ini + $stok_volume_agregat_batu0510_bulan_ini + $stok_volume_agregat_batu0510_bulan_ini_2;
			$stok_nilai_penjualan_batu0510 = $stok_nilai_penjualan_batu0510_bulan_ini + $stok_nilai_agregat_batu0510_bulan_ini + $stok_nilai_agregat_batu0510_bulan_ini_2;
			$stok_harga_penjualan_batu0510 = ($stok_volume_penjualan_batu0510!=0)?($stok_nilai_penjualan_batu0510 / $stok_volume_penjualan_batu0510)  * 1:0;

			$stok_volume_penjualan_batu1020 = $stok_volume_penjualan_batu1020_bulan_ini + $stok_volume_agregat_batu1020_bulan_ini + $stok_volume_agregat_batu1020_bulan_ini_2;
			$stok_nilai_penjualan_batu1020 = $stok_nilai_penjualan_batu1020_bulan_ini + $stok_nilai_agregat_batu1020_bulan_ini + $stok_nilai_agregat_batu1020_bulan_ini_2;
			$stok_harga_penjualan_batu1020 = ($stok_volume_penjualan_batu1020!=0)?($stok_nilai_penjualan_batu1020 / $stok_volume_penjualan_batu1020)  * 1:0;

			$stok_volume_penjualan_batu2030 = $stok_volume_penjualan_batu2030_bulan_ini + $stok_volume_agregat_batu2030_bulan_ini + $stok_volume_agregat_batu2030_bulan_ini_2;
			$stok_nilai_penjualan_batu2030 = $stok_nilai_penjualan_batu2030_bulan_ini + $stok_nilai_agregat_batu2030_bulan_ini + $stok_nilai_agregat_batu2030_bulan_ini_2;
			$stok_harga_penjualan_batu2030 = ($stok_volume_penjualan_batu2030!=0)?($stok_nilai_penjualan_batu2030 / $stok_volume_penjualan_batu2030)  * 1:0;

			//TOTAL
			$stok_total_volume_masuk = $stok_volume_produksi_harian_abubatu_bulan_ini + $stok_volume_produksi_harian_batu0510_bulan_ini + $stok_volume_produksi_harian_batu1020_bulan_ini + $stok_volume_produksi_harian_batu2030_bulan_ini;
			$stok_total_nilai_masuk = $stok_nilai_produksi_harian_abubatu_bulan_ini + $stok_nilai_produksi_harian_batu0510_bulan_ini + $stok_nilai_produksi_harian_batu1020_bulan_ini + $stok_nilai_produksi_harian_batu2030_bulan_ini;

			$stok_total_volume_keluar = $stok_volume_penjualan_abubatu_bulan_ini + $stok_volume_agregat_abubatu_bulan_ini + $stok_volume_agregat_abubatu_bulan_ini_2 + $stok_volume_penjualan_batu0510_bulan_ini + $stok_volume_agregat_batu0510_bulan_ini + $stok_volume_agregat_batu0510_bulan_ini_2 + $stok_volume_penjualan_batu1020_bulan_ini + $stok_volume_agregat_batu1020_bulan_ini + $stok_volume_agregat_batu1020_bulan_ini_2 + $stok_volume_penjualan_batu2030_bulan_ini + $stok_volume_agregat_batu2030_bulan_ini + $stok_volume_agregat_batu2030_bulan_ini_2;
			$stok_total_nilai_keluar = $stok_nilai_penjualan_abubatu_bulan_ini + $stok_nilai_agregat_abubatu_bulan_ini + $stok_nilai_agregat_abubatu_bulan_ini_2 +  $stok_nilai_penjualan_batu0510_bulan_ini + $stok_nilai_agregat_batu0510_bulan_ini + $stok_nilai_agregat_batu0510_bulan_ini_2 + $stok_nilai_penjualan_batu1020_bulan_ini + $stok_nilai_agregat_batu1020_bulan_ini + $stok_nilai_agregat_batu1020_bulan_ini_2 + $stok_nilai_penjualan_batu2030_bulan_ini + $stok_nilai_agregat_batu2030_bulan_ini + $stok_nilai_agregat_batu2030_bulan_ini_2;
			
			$stok_total_volume_akhir = $stok_volume_akhir_agregat_abubatu_bulan_ini_2 + $stok_volume_akhir_agregat_batu0510_bulan_ini_2 + $stok_volume_akhir_agregat_batu1020_bulan_ini_2 + $stok_volume_akhir_agregat_batu2030_bulan_ini_2;
			$stok_total_nilai_akhir = $stok_nilai_akhir_agregat_abubatu_bulan_ini_2 + $stok_nilai_akhir_agregat_batu0510_bulan_ini_2 + $stok_nilai_akhir_agregat_batu1020_bulan_ini_2 + $stok_nilai_akhir_agregat_batu2030_bulan_ini_2;
			
			?>

			<!--- End Agregat --->
			<?php

			$evaluasi_volume_abubatu = $volume_akhir_agregat_abubatu_bulan_ini_2 - $stok_volume_akhir_agregat_abubatu_bulan_ini_2;
			$evaluasi_harga_abubatu = $stok_harga_akhir_agregat_abubatu_bulan_ini_2;
			$evaluasi_nilai_abubatu = $nilai_akhir_agregat_abubatu_bulan_ini_2 - $stok_nilai_akhir_agregat_abubatu_bulan_ini_2;
			
			$evaluasi_volume_batu0510 = $volume_akhir_agregat_batu0510_bulan_ini_2 - $stok_volume_akhir_agregat_batu0510_bulan_ini_2;
			$evaluasi_harga_batu0510 = $stok_harga_akhir_agregat_batu0510_bulan_ini_2;
			$evaluasi_nilai_batu0510 = $nilai_akhir_agregat_batu0510_bulan_ini_2 - $stok_nilai_akhir_agregat_batu0510_bulan_ini_2;

			$evaluasi_volume_batu1020 = $volume_akhir_agregat_batu1020_bulan_ini_2 - $stok_volume_akhir_agregat_batu1020_bulan_ini_2;
			$evaluasi_harga_batu1020 = $stok_harga_akhir_agregat_batu1020_bulan_ini_2;
			$evaluasi_nilai_batu1020 = $nilai_akhir_agregat_batu1020_bulan_ini_2 - $stok_nilai_akhir_agregat_batu1020_bulan_ini_2;

			$evaluasi_volume_batu2030 = $volume_akhir_agregat_batu2030_bulan_ini_2 - $stok_volume_akhir_agregat_batu2030_bulan_ini_2;
			$evaluasi_harga_batu2030 = $stok_harga_akhir_agregat_batu2030_bulan_ini_2;
			$evaluasi_nilai_batu2030 = $nilai_akhir_agregat_batu2030_bulan_ini_2 - $stok_nilai_akhir_agregat_batu2030_bulan_ini_2;

			$stok_evaluasi_total_volume_akhir = $evaluasi_volume_abubatu + $evaluasi_volume_batu0510 + $evaluasi_volume_batu1020 + $evaluasi_volume_batu2030;
			$stok_evaluasi_total_nilai_akhir = $evaluasi_nilai_abubatu + $evaluasi_nilai_batu0510 + $evaluasi_nilai_batu1020 + $evaluasi_nilai_batu2030;
			
			?>

			<!-- Evaluasi -->

			<tr class="table-active4">
				<th class="text-center" rowspan="2">&nbsp;<br>URAIAN</th>
				<th class="text-center" rowspan="2">&nbsp;<br>SATUAN</th>
				<th class="text-center" colspan="3">RUMUS</th>
				<th class="text-center" colspan="3">STOK</th>
				<th class="text-center" colspan="3">EVALUASI</th>
			</tr>
			<tr class="table-active4">
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
			</tr>
			<tr class="table-active3">		
				<th class = "text-left"><i>Batu 0,0 - 0,5</i></th>
				<th class = "text-center">Ton</th>
				<th class = "text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($stok_nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($evaluasi_volume_abubatu,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_harga_abubatu,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_nilai_abubatu,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class = "text-left"><i>Batu 0,5 - 10</i></th>
				<th class = "text-center">Ton</th>
				<th class = "text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($evaluasi_volume_batu0510,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_harga_batu0510,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_nilai_batu0510,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class = "text-left"><i>Batu 10 - 20</i></th>
				<th class = "text-center">Ton</th>
				<th class = "text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($stok_nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($evaluasi_volume_batu1020,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_harga_batu1020,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_nilai_batu1020,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">		
				<th class = "text-left"><i>Batu 20 - 30</i></th>
				<th class = "text-center">Ton</th>
				<th class = "text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($stok_nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($evaluasi_volume_batu2030,2,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_harga_batu2030,0,',','.');?></th>
				<th class = "text-right"><?php echo number_format($evaluasi_nilai_batu2030,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class = "text-center" colspan="2">TOTAL</th>
				<th class = "text-center"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
				<th class = "text-right">-</th>
				<th class = "text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($stok_total_volume_akhir,2,',','.');?></th>
				<th class = "text-right">-</th>
				<th class = "text-right"><?php echo number_format($stok_total_nilai_akhir,0,',','.');?></th>
				<th class = "text-center"><?php echo number_format($stok_evaluasi_total_volume_akhir,2,',','.');?></th>
				<th class = "text-right">-</th>
				<th class = "text-right"><?php echo number_format($stok_evaluasi_total_nilai_akhir,0,',','.');?></th>
			</tr>
	    </table>
		
		<?php
	}
	
	public function beban_pokok_produksi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #A9A9A9;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 11px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #E8E8E8;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
		 </style>
	        <tr class="table-active4">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="3"><?php echo $filter_date;?></th>
	        </tr>
			
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
			
			$bbm_solar = $akumulasi_nilai_bahan_baku_2;
			
			
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
			
			<tr class="table-active">
	            <th width="5%" class="text-center">No.</th>
				<th width="35%" class="text-center">Uraian</th>
				<th width="15%" class="text-center">Satuan</th>
	            <th width="15%" class="text-center">Volume</th>
				<th width="15%" class="text-center">Harga Satuan</th>
				<th width="15%" class="text-center">Total</th>
	        </tr>
			<tr class="table-active">
	            <th class="text-center">1.</th>
				<th class="text-left" colspan="5">Bahan Baku</th>
	        </tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Total Pemakaian Bahan Baku</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
	            <th class="text-center"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
			</tr>
			<tr class="table-active">
	            <th class="text-center">2.</th>
				<th class="text-left" colspan="5">Peralatan</th>
	        </tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Stone Crusher</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($stone_crusher,2,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Whell Loader</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($whell_loader,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Excavator</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($excavator['price'],0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Genset</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($genset,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Timbangan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($timbangan,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Tangki Solar</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($tangki_solar,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>BBM Solar</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Litter</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($bbm_solar,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Total Biaya Peralatan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center"></th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_biaya_peralatan,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
				<th>
				<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>HPP Peralatan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">/Ton</th>
				<th colspan="2"></th>
				<th class="text-right"><?php echo number_format($hpp_peralatan,0,',','.');?></th>
			</tr>
			<tr class="table-active">
	            <th class="text-center">3.</th>
				<th class="text-left" colspan="5">Operasional</th>
	        </tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Gaji/Upah</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($gaji_upah,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Konsumsi</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($konsumsi,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>THR & Bonus</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($thr_bonus,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Perbaikan dan Pemeliharaan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($perbaikan,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Akomodasi Tamu</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($akomodasi_tamu,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Pengujian Material dan Laboratorium</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($pengujian,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Listrik & Internet</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($listrik_internet,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Total Biaya Operasional</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_operasional,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
				<th>
				<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>HPP Operasional</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">/Ton</th>
				<th colspan="2"></th>
				<th class="text-right"><?php echo number_format($hpp_operasional,0,',','.');?></th>
			</tr>
			<tr class="table-active4">
	            <th colspan="5">HPP/Total Beban Pokok Produksi</th>
				<th class="text-right"><?php echo number_format($total_bpp,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th colspan="5">Harga Pokok Produksi</th>
				<th class="text-right"><?php echo number_format($harga_bpp,0,',','.');?></th>
	        </tr>
	    </table>
		<?php
	}


	public function report_production($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active2{
				background-color: #A9A9A9;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
			}
				
			table tr.table-active4{
				background-color: #D3D3D3;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
		 </style>
	        <tr class="table-active4">
	            <th colspan="2">PERIODE</th>
				<th class="text-center"></th>
				<th class="text-center"></th>
	            <th class="text-center"><?php echo $filter_date;?></th>
	        </tr>

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

			<tr class="table-active">
	            <th width="100%" class="text-left" colspan="5">PENDAPATAN ATAS PENJUALAN</th>
	        </tr>
			<tr class="table-active3">
	            <th width="10%" class="text-center">4-40000</th>
				<th width="90%" class="text-left" colspan="4">Pendapatan</th>
	        </tr>
			<?php foreach ($penjualan as $x): ?>
			<tr class="table-active3">
	            <th width="10%"></th>
				<th width="40%"><?= $x['nama'] ?></th>
				<th width="10%" class="text-right"><?php echo number_format($x['volume'],2,',','.');?></th>
				<th width="10%" class="text-center"><?= $x['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$x['measure']),'measure_name')?></th>
	            <th width="30%" class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($x['price'],0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<?php endforeach; ?>
			<tr class="table-active3">
				<th class="text-left" colspan="2">Total Pendapatan</th>
				<th class="text-right"><?php echo number_format($total_volume,2,',','.');?></th>
				<th class="text-center">Ton</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_penjualan_all,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
				<th class="text-left" colspan="5">HARGA POKOK PENDAPATAN</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center">5-50000</th>
				<th class="text-left" colspan="3">Harga Pokok Pendapatan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></span>
								</th>
							</tr>
					</table></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left" colspan="4">Total Harga Pokok Pendapatan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $laba_kotor < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
				<th class="text-left" colspan="4">LABA KOTOR</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($laba_kotor,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
	            <th class="text-left" colspan="5">BIAYA OPERASIONAL</th>
	        </tr>
			<tr class="table-active3">
				<th></th>
				<th class="text-left" colspan="3">Biaya Overhead Produksi</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($biaya_overhead_produksi,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
			</tr>
			<tr class="table-active3">
				<th>6-60100</th>
				<th class="text-left" colspan="3">Biaya Umum & Administratif</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($biaya_umum_administratif,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
			</tr>
			<tr class="table-active3">
				<th>8-80100</th>
				<th class="text-left" colspan="3">Biaya Lainnya</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($biaya_lainnya,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
			</tr>
			<tr class="table-active3">
	            <th class="text-left" colspan="4">Total Biaya</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_biaya,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $laba_sebelum_pajak < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
	            <th colspan="4" class="text-left">Laba Sebelum Pajak</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($laba_sebelum_pajak,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
	            <th colspan="4" class="text-left">Presentase</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($persentase_laba_sebelum_pajak,0,',','.');?> %</span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
	    </table>
		<?php
	}


	public function revenues()
	{

		
		$arr_date = $this->input->post('filter_date');
		if(empty($filter_date)){
			$filter_date = '-';
		}else {
			$filter_date = $arr_date;
		}
		$alphas = range('A', 'Z');
		$data['alphas'] = $alphas;
		$data['clients'] = $this->db->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
		$data['arr_date'] = $arr_date;
		$this->load->view('pmm/ajax/reports/revenues',$data);
	}

	public function receipt_materials()
	{
		
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($arr_date);
		$this->load->view('pmm/ajax/reports/receipt_materials',$data);

	}

	public function receipt_material_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		$type = $this->input->post('type');
		$data['type'] = $type;
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($id,$arr_date);
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/receipt_materials_detail',$data);
	}


	public function material_usage()
	{

		$product_id = $this->input->post('product_id');
		$arr_date = $this->input->post('filter_date');

		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';

    		$arr_date_2 = $start_date.' - '.$end_date;

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date_2);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date_2,true);
    	}else {



    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date,true);
    	} 

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		if(!empty($product_id)){
			$data['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'product');
			$data['total_production'] = $this->pmm_reports->TotalProductions($product_id,$arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompoProduct($arr_date,$product_id);
			$this->load->view('pmm/ajax/reports/material_usage_product',$data);
		}else {

			$data['arr'] =  $this->pmm_reports->MaterialUsageReal($arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompo($arr_date);
			$data['total_revenue_now'] = $total_revenue_now;
			$data['total_revenue_before'] =  $total_revenue_before;
			$this->load->view('pmm/ajax/reports/material_usage',$data);	
		}
		
	}

	public function material_usage_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

		$type = $this->input->post('type');
		$product_id = $this->input->post('product_id');
		$data['type'] = $type;
		if($type == 'compo' || $type == 'compo_cost' || $type == 'compo_now'){
			$data['arr'] =  $this->pmm_reports->MaterialUsageCompoDetails($id,$arr_date,$product_id);
		}else {
			$data['arr'] =  $this->pmm_reports->MaterialUsageDetails($id,$arr_date);	
		}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['product_id'] = $product_id;
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/material_usage_detail',$data);
	}

	public function material_remaining()
	{
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

    	$date = array($start_date,$end_date);
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		$data['arr'] =  $this->pmm_reports->MaterialRemainingReal($date);
		$data['arr_compo'] = $this->pmm_reports->MaterialRemainingCompo($date);
		$this->load->view('pmm/ajax/reports/material_remaining',$data);	
		

	}

	public function material_remaining_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 
    	$date = array($start_date,$end_date);
		$type = $this->input->post('type');
		$data['type'] = $type;
		if($type == 'compo'){
			$data['arr'] =  $this->pmm_reports->MaterialRemainingCompoDetails($id,$date);
		}else {
			$data['arr'] =  $this->pmm_reports->MaterialRemainingDetails($id,$arr_date);	
		}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/material_remaining_detail',$data);
	}

	public function equipments()
	{
		$arr_date = $this->input->post('filter_date');
		$supplier_id = $this->input->post('supplier_id');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$date = array($start_date,$end_date);
    	$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->EquipmentProd($date);
		$data['equipments'] =  $this->pmm_reports->EquipmentReports($date,$supplier_id);
		$data['solar'] =  $this->pmm_reports->EquipmentUsageReal($date,true);
		$this->load->view('pmm/ajax/reports/equipments',$data);

	}

	public function equipments_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}
    	$date = array($start_date,$end_date);
		$supplier_id = $this->input->post('supplier_id');;
		$data['equipments'] = $this->pmm_reports->EquipmentReportsDetails($id,$date,$supplier_id);
		$data['name'] = $this->input->post('name');
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$this->load->view('pmm/ajax/reports/equipments_detail',$data);
	}


	public function equipments_data_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$date = $this->input->get('filter_date');
		$supplier_id = $this->input->get('supplier_id');
		$tool_id = $this->input->get('tool_id');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));

			$filter_date = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
			$data['filter_date'] = $filter_date;
			$date = explode(' - ',$start_date.' - '.$end_date);
			$arr_data = $this->pmm_reports->EquipmentsData($date,$supplier_id,$tool_id);

			$data['data'] = $arr_data;
			$data['solar'] =  $this->pmm_reports->EquipmentUsageReal($date);
	        $html = $this->load->view('pmm/equipments_data_print',$data,TRUE);

	        
	        $pdf->SetTitle('Data Alat');
	        $pdf->nsi_html($html);
	        $pdf->Output('Data-Alat.pdf', 'I');

		}else {
			echo 'Please Filter Date First';
		}
		

		
	
	}

	public function revenues_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$alphas = range('A', 'Z');
		$data['alphas'] = $alphas;
		$data['arr_date'] = $arr_date;
		$data['clients'] = $this->db->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
        $html = $this->load->view('pmm/revenues_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PENDAPATAN USAHA');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PENDAPATAN-USAHA.pdf', 'I');
	
	}
	
	public function monitoring_receipt_materials_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($arr_date);
        $html = $this->load->view('pmm/monitoring_receipt_materials_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PENERIMAAN BAHAN');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PENERIMAAN-BAHAN.pdf', 'I');
	
	}

	public function material_usage_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$product_id = $this->input->get('product_id');
		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';

    		$arr_date_2 = $start_date.' - '.$end_date;

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date_2);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date_2,true);

    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date,true);
    	}
    	
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		if(empty($product_id)){
			$data['arr'] =  $this->pmm_reports->MaterialUsageReal($arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompo($arr_date);
			$data['total_revenue_now'] = $total_revenue_now;
			$data['total_revenue_before'] =  $total_revenue_before;
	        $html = $this->load->view('pmm/material_usage_print',$data,TRUE);
		}else {
			$data['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'product');
			$data['total_production'] = $this->pmm_reports->TotalProductions($product_id,$arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompoProduct($arr_date,$product_id);

			

	        $html = $this->load->view('pmm/material_usage_product_print',$data,TRUE);
		}
		 
        $pdf->SetTitle('LAPORAN PEMAKAIAN MATERIAL');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PEMAKAIAN-MATERIAL.pdf', 'I');
	
	}

	public function material_remaining_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

    	$date = array($start_date,$end_date);
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		$data['arr'] =  $this->pmm_reports->MaterialRemainingReal($date);
		$data['arr_compo'] = $this->pmm_reports->MaterialRemainingCompo($date);

        $html = $this->load->view('pmm/material_remaining_print',$data,TRUE);

        
        $pdf->SetTitle('Materials Remaining');
        $pdf->nsi_html($html);
        $pdf->Output('Materials-Remaining.pdf', 'I');
	
	}


	public function monitoring_equipments_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		$supplier_id = $this->input->get('supplier_id');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$date = array($start_date,$end_date);
    	$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->EquipmentProd($date);
		$data['equipments'] =  $this->pmm_reports->EquipmentReports($date,$supplier_id);
		$data['supplier'] = $this->crud_global->GetField('pmm_supplier',array('id'=>$supplier_id),'name');

        $html = $this->load->view('pmm/monitoring_equipments_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PEMAKAIAN ALAT');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PEMAKAIAN-ALAT.pdf', 'I');
	
	}

	public function general_cost_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->SetTopMargin(0);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		$filter_type = $this->input->get('filter_type');
		if(empty($arr_date)){
    		$data['filter_date'] = '-';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
    	} 

		


		if(!empty($arr_date)){
			$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    		$this->db->where('date >=',$start_date);
    		$this->db->where('date <=',$end_date);	
		}
		if(!empty($filter_type)){
			$this->db->where('type',$filter_type);
		}
		$this->db->order_by('date','desc');
		$this->db->where('status !=','DELETED');
		$arr = $this->db->get_where('pmm_general_cost');
		$data['arr'] =  $arr->result_array();

        $html = $this->load->view('pmm/general_cost_print',$data,TRUE);

        
        $pdf->SetTitle('General Cost');
        $pdf->nsi_html($html);
        $pdf->Output('General-Cost.pdf', 'I');
	
	}


	public function purchase_order_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['w_date'] = $arr_date;
		$data['status'] = $this->input->post('status');
		$data['supplier_id'] = $this->input->post('supplier_id');
		$this->db->select('supplier_id');
		$this->db->where('status !=','DELETED');
		if(!empty($data['status'])){
			$this->db->where('supplier_id',$data['status']);
		}
		$this->db->group_by('supplier_id');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_purchase_order');

		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/purchase_order_print',$data,TRUE);

        
        $pdf->SetTitle('Purchase Order');
        $pdf->nsi_html($html);
        $pdf->Output('Purchase-Order.pdf', 'I');
	
	}


	public function product_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$tag_id = $this->input->get('product_id');

		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);	
		}
		$this->db->where('status !=','DELETED');
		$this->db->order_by('product','asc');
		$query = $this->db->get('pmm_product');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$name = "'".$row['product']."'";
				$row['no'] = $key+1;
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$contract_price = $this->pmm_model->GetContractPrice($row['contract_price']);
				$row['contract_price'] = number_format($contract_price,2,',','.');
				$row['riel_price'] = number_format($this->pmm_model->GetRielPrice($row['id']),2,',','.');
				$row['composition'] = $this->crud_global->GetField('pmm_composition',array('id'=>$row['composition_id']),'composition_name');
				$row['tag_name'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
				$arr_data[] = $row;
			}

		}

		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/product_print',$data,TRUE);

        
        $pdf->SetTitle('Product');
        $pdf->nsi_html($html);
        $pdf->Output('Product.pdf', 'I');
	
	}

	public function product_hpp_print()
	{
		$id = $this->input->get('id');
		$name = $this->input->get('name');
		if(!empty($id)){
			$this->load->library('pdf');
		

			$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
	        $pdf->setPrintHeader(true);
	        $pdf->SetFont('helvetica','',7); 
	        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
			$pdf->setHtmlVSpace($tagvs);
			        $pdf->AddPage('P');

			$arr_data = array();

			$output = $this->pmm_model->GetRielPriceDetail($id);

			$data['data'] = $output;
			$data['name'] = $name;
	        $html = $this->load->view('pmm/product_hpp_print',$data,TRUE);

	        
	        $pdf->SetTitle('Product-HPP');
	        $pdf->nsi_html($html);
	        $pdf->Output('Product-HPP-'.$name.'.pdf', 'I');
		}else {
			echo "Product Not Found";
		}
		
	
	}

	public function materials_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		
		$pdf->AddPage('P');

		$arr_data = array();
		$tag_id = $this->input->get('tag_id');

		$this->db->where('status !=','DELETED');
		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);
		}
		$this->db->order_by('material_name','asc');
		$query = $this->db->get('pmm_materials');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['price'] = number_format($row['price'],2,',','.');
				$row['cost'] = number_format($row['cost'],2,',','.');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
 				$row['tag_name'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
				$arr_data[] = $row;
			}

		}

		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/materials_print',$data,TRUE);

        
        $pdf->SetTitle('Materials');
        $pdf->nsi_html($html);
        $pdf->Output('Materials.pdf', 'I');
	
	}

	public function tools_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('tool','asc');
		$query = $this->db->get('pmm_tools');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$name = "'".$row['tool']."'";
				$total_cost = $this->db->select('SUM(cost) as total')->get_where('pmm_tool_detail',array('status'=>'PUBLISH','tool_id'=>$row['id']))->row_array();
				$row['total_cost'] = number_format($total_cost['total'],2,',','.');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_id']),'measure_name');
				$row['tag'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['actions'] = '<a href="javascript:void(0);" onclick="FormDetail('.$row['id'].','.$name.')" class="btn btn-info"><i class="fa fa-search"></i> Detail</a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/tools_print',$data,TRUE);

        
        $pdf->SetTitle('Tools');
        $pdf->nsi_html($html);
        $pdf->Output('Tools.pdf', 'I');
	
	}

	public function measures_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_measures');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/measures_print',$data,TRUE);

        
        $pdf->SetTitle('Measures');
        $pdf->nsi_html($html);
        $pdf->Output('Measures.pdf', 'I');
	
	}

	public function composition_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$tag_id = $this->input->get('filter_product');
		$arr_tag = array();
		if(!empty($tag_id)){
			$query_tag = $this->db->select('id')->get_where('pmm_product',array('status'=>'PUBLISH','tag_id'=>$tag_id))->result_array();
			foreach ($query_tag as $pid) {
				$arr_tag[] = $pid['id'];
			}
		}
		$this->db->select('pc.*, pp.product');
		$this->db->where('pc.status !=','DELETED');
		if(!empty($tag_id)){
			$this->db->where_in('product_id',$arr_tag);
		}
		$this->db->join('pmm_product pp','pc.product_id = pp.id','left');
		$this->db->order_by('pc.created_on','desc');
		$query = $this->db->get('pmm_composition pc');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/composition_print',$data,TRUE);

        
        $pdf->SetTitle('Composition');
        $pdf->nsi_html($html);
        $pdf->Output('Composition.pdf', 'I');
	
	}

	public function supplier_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('name','asc');
		$query = $this->db->get('pmm_supplier');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/supplier_print',$data,TRUE);

        
        $pdf->SetTitle('Supplier');
        $pdf->nsi_html($html);
        $pdf->Output('Supplier.pdf', 'I');
	
	}

	public function client_print()
	{
		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('client_name','asc');
		$query = $this->db->get('pmm_client');
		$data['data'] = $query->result_array();	
	
		$this->load->library('pdf');
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-client.pdf";
		$this->pdf->load_view('pmm/client_print', $data);
	
	}
	
	public function slump_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_slump');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/slump_print',$data,TRUE);

        
        $pdf->SetTitle('Slump');
        $pdf->nsi_html($html);
        $pdf->Output('Slump.pdf', 'I');
	
	}

	public function tags_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$type = $this->input->get('type');
		$this->db->where('status !=','DELETED');
		if(!empty($type)){
			$this->db->where('tag_type',$type);
		}
		$this->db->order_by('tag_name','asc');
		$query = $this->db->get('pmm_tags');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$price = 0;
				if($row['tag_type'] == 'MATERIAL'){
					$get_price = $this->db->select('AVG(cost) as cost')->get_where('pmm_materials',array('status'=>'PUBLISH','tag_id'=>$row['id']))->row_array();
					if(!empty($get_price)){
						$price = $get_price['cost'];
					}
				}
				$row['price'] = number_format($price,2,',','.');
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/tags_print',$data,TRUE);

        
        $pdf->SetTitle('Tags');
        $pdf->nsi_html($html);
        $pdf->Output('Tags.pdf', 'I');
	
	}

	public function production_planning_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_schedule');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$arr_date = explode(' - ', $row['schedule_date']);
				$row['schedule_name'] = $row['schedule_name'];
				$row['client_name'] = $this->crud_global->GetField('pmm_client',array('id'=>$row['client_id']),'client_name');
				$row['schedule_date'] = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['week_1'] = $this->pmm_model->TotalSPOWeek($row['id'],1);
				$row['week_2'] = $this->pmm_model->TotalSPOWeek($row['id'],2);
				$row['week_3'] = $this->pmm_model->TotalSPOWeek($row['id'],3);
				$row['week_4'] = $this->pmm_model->TotalSPOWeek($row['id'],4);
				$row['status'] = $this->pmm_model->GetStatus($row['status']);
				
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/production_planning_print',$data,TRUE);

        
        $pdf->SetTitle('cetak_poduction_planning');
        $pdf->nsi_html($html);
        $pdf->Output('production_planning.pdf', 'I');
	
	}
	
	public function receipt_matuse_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

			$arr_filter_mats = array();

			$no = 1;
			$this->db->select('ppo.supplier_id,prm.measure,ps.name,SUM(prm.volume) as total, SUM((prm.cost / prm.convert_value) * prm.display_volume) as total_price, prm.convert_value, SUM(prm.volume * prm.convert_value) as total_convert');
			if(!empty($start_date) && !empty($end_date)){
	            $this->db->where('prm.date_receipt >=',$start_date);
	            $this->db->where('prm.date_receipt <=',$end_date);
	        }
	        if(!empty($supplier_id)){
	            $this->db->where('ppo.supplier_id',$supplier_id);
	        }
	        if(!empty($filter_material)){
	            $this->db->where_in('prm.material_id',$filter_material);
	        }
	        if(!empty($purchase_order_no)){
	            $this->db->where('prm.purchase_order_id',$purchase_order_no);
	        }
			$this->db->where('ps.status','PUBLISH');
			$this->db->join('pmm_supplier ps','ppo.supplier_id = ps.id','left');
			$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
			$this->db->group_by('ppo.supplier_id');
			$this->db->order_by('ps.name','asc');
			$query = $this->db->get('pmm_purchase_order ppo');
			
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetReceiptMatUse($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$arr_filter_mats);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$arr['no'] = $key + 1;
							$arr['measure'] = $row['measure'];
							$arr['material_name'] = $row['material_name'];
							
							$arr['real'] = number_format($row['total'],2,',','.');
							$arr['convert_value'] = number_format($row['convert_value'],2,',','.');
							$arr['total_convert'] = number_format($row['total_convert'],2,',','.');
							$arr['total_price'] = number_format($row['total_price'],2,',','.');
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$total += $sups['total_price'];
						$total_convert += $sups['total_convert'];
						$sups['no'] =$no;
						$sups['real'] = number_format($sups['total'],2,',','.');
						$sups['convert_value'] = number_format($sups['convert_value'],2,',','.');
						$sups['total_convert'] = number_format($sups['total_convert'],2,',','.');
						$sups['total_price'] = number_format($sups['total_price'],2,',','.');
						$sups['measure'] = '';
						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}
			if(!empty($filter_material)){
				$total_convert = number_format($total_convert,0,',','.');
			}else {
				$total_convert = '';
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['total_convert'] = $total_convert;
	        $html = $this->load->view('pmm/receipt_matuse_report_print',$data,TRUE);

	        
	        $pdf->SetTitle('Penerimaan Bahan');
	        $pdf->nsi_html($html);
	        $pdf->Output('Penerimaan-Bahan.pdf', 'I');
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function data_material_usage()
	{
		$supplier_id = $this->input->post('supplier_id');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$query = array();
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

    	$this->db->where(array(
    		'status'=>'PUBLISH',
    	));
    	if(!empty($filter_material)){
    		$this->db->where('id',$filter_material);
    	}
    	$this->db->order_by('nama_produk','asc');
    	$tags = $this->db->get_where('produk',array('status'=>'PUBLISH','bahanbaku'=>1))->result_array();
		
		//file_put_contents("D:\\data_material_usage.txt", $this->db->last_query());

    	if(!empty($tags)){
    		?>
	        <table class="table table-center table-bordered table-condensed">
	        	<thead>
	        		<tr >
		        		<th class="text-center">No</th>
		        		<th class="text-center">Bahan</th>
		        		<th class="text-center">Rekanan</th>
		        		<th class="text-center">Satuan</th>
		        		<th class="text-center">Volume</th>
		        		<th class="text-center">Total</th>
		        	</tr>	
	        	</thead>
	        	<tbody>
	        		<?php
	        		$no=1;
	        		$total_total = 0;
	        		foreach ($tags as $tag) {
		    			$now = $this->pmm_reports->SumMaterialUsage($tag['id'],array($start_date,$end_date));

		    			
		    			$measure_name = $this->crud_global->GetField('pmm_measures',array('id'=>$tag['satuan']),'measure_name');
		    			if($now['volume'] > 0){
				        	
				        	?>
				        	<tr class="active" style="font-weight:bold;">
				        		<td class="text-center"><?php echo $no;?></td>
				        		<td colspan="2"><?php echo $tag['nama_produk'];?></td>
				        		<td class="text-center"><?php echo $measure_name;?></td>
				        		<td class="text-right"><?php echo number_format($now['volume'],2,',','.');?></td>
				        		<td class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($now['total'],0,',','.');?></td>
				        	</tr>
				        	<?php
				        	$now_new = $this->pmm_reports->MatUseBySupp($tag['id'],array($start_date,$end_date),$now['volume'],$now['total']);
				        	if(!empty($now_new)){
				        		$no_2 = 1;
				        		foreach ($now_new as $new) {
					        		
					        		?>
					        		<!--<tr>
					        			<td class="text-center"><?= $no.'.'.$no_2;?></td>
					        			<td></td>
					        			<td><?php echo $new['supplier'];?></td>
					        			<td class="text-center"><?php echo $measure_name;?></td>
						        		<td class="text-right"><?php echo number_format($new['volume'],2,',','.');?></td>
						        		<td class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($new['total'],0,',','.');?></td>
					        		</tr>-->
					        		<?php
					        		$no_2 ++;
					        	}
				        	}
				        	
				        	?>
				        	<tr style="height: 20px">
				        		<td colspan="6"></td>
				        	</tr>
				        	<?php

				        	$no++;
				        	$total_total += $now['total'];
					        
		    			}
		    		}
	        		?>
	        		<tr>
	        			<th colspan="5" class="text-right">TOTAL</th>
	        			<th class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($total_total,0,',','.');?></th>
	        		</tr>
	        	</tbody>
	        </table>
	        <?php	
    	}


	}


	public function material_usage_prod_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

			$no = 1;
	    	$this->db->where(array(
	    		'status'=>'PUBLISH',
				'bahanbaku'=>1,
	    	));
	    	if(!empty($filter_material)){
	    		$this->db->where('id',$filter_material);
	    	}
	    	$this->db->order_by('nama_produk','asc');
	    	$query = $this->db->get('produk');
			
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $tag) {

					$now = $this->pmm_reports->SumMaterialUsage($tag['id'],array($start_date,$end_date));
	    			$measure_name = $this->crud_global->GetField('pmm_measures',array('id'=>$tag['satuan']),'measure_name');
	    			if($now['volume'] > 0){
	    				$tags['tag_name'] = $tag['nama_produk'];
	    				$tags['no'] = $no;
	    				$tags['volume'] = number_format($now['volume'],2,',','.');
	    				$tags['total'] = number_format($now['total'],2,',','.');
	    				$tags['measure'] = $measure_name;

	    				$now_new = $this->pmm_reports->MatUseBySupp($tag['id'],array($start_date,$end_date),$now['volume'],$now['total']);
			        	if(!empty($now_new)){
			        		$no_2 = 1;
			        		$supps = array();
			        		foreach ($now_new as $new) {

			        			$arr_supps['no'] = $no_2;
			        			$arr_supps['supplier'] = $new['supplier'];
			        			$arr_supps['volume'] = number_format($new['volume'],2,',','.');
			        			$arr_supps['total'] = number_format($new['total'],2,',','.');
			        			$supps[] = $arr_supps;
			        			$no_2 ++;
			        		}

			        		$tags['supps'] = $supps;
			        	}

						$arr_data[] = $tags;	
						$total += $now['total'];
	    			}
					$no++;
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['custom_date'] = $this->input->get('custom_date');
	        $html = $this->load->view('produksi/material_usage_prod_print',$data,TRUE);

	        
	        $pdf->SetTitle('pemakaian-material');
	        $pdf->nsi_html($html);
	        $pdf->Output('pemakaian-material', 'I');
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function exec()
	{
		
	}
}