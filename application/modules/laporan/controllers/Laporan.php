<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends Secure_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm/pmm_model','admin/Templates','pmm/pmm_finance','m_laporan'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
	}

    public function laporan_biaya()
    {
        $data['asd'] = false;
        $this->load->view('laporan_biaya/laporan_biaya',$data);
    }

    public function ajax_laporan_biaya()
    {

        $filter_date = $this->input->post('filter_date');

        $data['filter_date'] = $filter_date;
		$data['biaya_langsung'] = $this->m_laporan->biaya_langsung($filter_date);
		$data['biaya_langsung_jurnal'] = $this->m_laporan->biaya_langsung_jurnal($filter_date);
        $data['biaya'] = $this->m_laporan->showBiaya($filter_date);
		$data['biaya_jurnal'] = $this->m_laporan->showBiayaJurnal($filter_date);
        $data['biaya_lainnya'] = $this->m_laporan->showBiayaLainnya($filter_date);
		$data['biaya_lainnya_jurnal'] = $this->m_laporan->showBiayaLainnyaJurnal($filter_date);

        $this->load->view('laporan_biaya/ajax/ajax_biaya',$data);
    }

	public function print_biaya()
    {
        $this->load->library('pdf');
    

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
        $pdf->setHtmlVSpace($tagvs);
                $pdf->AddPage('P');

        $arr_date = $this->input->get('filter_date');

        $dt = explode(' - ', $arr_date);
        $start_date = date('Y-m-d',strtotime($dt[0]));
        $end_date = date('Y-m-d',strtotime($dt[1]));

        $date = array($start_date,$end_date);
        $data['filter_date'] = $arr_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['biaya_langsung'] = $this->m_laporan->biaya_langsung_print($arr_date);
		$data['biaya_langsung_jurnal'] = $this->m_laporan->biaya_langsung_jurnal_print($arr_date);
        $data['biaya'] = $this->m_laporan->showBiaya_print($arr_date);
		$data['biaya_jurnal'] = $this->m_laporan->showBiayaJurnal_print($arr_date);
        $data['biaya_lainnya'] = $this->m_laporan->showBiayaLainnya_print($arr_date);
		$data['biaya_lainnya_jurnal'] = $this->m_laporan->showBiayaLainnyaJurnal_print($arr_date);

        $html = $this->load->view('laporan_biaya/print_biaya',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Laporan Biaya');
        $pdf->nsi_html($html);
        $pdf->Output('laporan-biaya.pdf', 'I');
    
    }
	
	//RUMUS BARU//

	public function cetak_penerimaan_pembelian()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$filter_kategori = $this->input->get('filter_kategori');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$this->db->select('ppo.supplier_id,prm.display_measure as measure,ps.nama as name, prm.display_harga_satuan as price,SUM(prm.display_volume) as volume, SUM(prm.display_price) as total_price');
			
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
			if(!empty($filter_kategori)){
				$this->db->where('ppo.kategori_id',$filter_kategori);
			}

			$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
			$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
			$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
			$this->db->group_by('ppo.supplier_id');
			$this->db->order_by('ps.nama','asc');
			$query = $this->db->get('pmm_purchase_order ppo');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetPenerimaanPembelianPrint($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material,$filter_kategori);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$arr['no'] = $key + 1;
							$arr['measure'] = $row['measure'];
							$arr['nama_produk'] = $row['nama_produk'];
							$arr['volume'] = number_format($row['volume'],2,',','.');
							$arr['price'] = number_format($row['price'],0,',','.');
							$arr['total_price'] = number_format($row['total_price'],0,',','.');
							
							
							$arr['name'] = $sups['name'];
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$total += $sups['total_price'];
						$sups['no'] =$no;
						$sups['volume'] = number_format($sups['volume'],2,',','.');
						$sups['total_price'] = number_format($sups['total_price'],0,',','.');

						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_pembelian/cetak_penerimaan_pembelian',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Pembelian');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-pembelian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_laporan_hutang()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$filter_kategori = $this->input->get('filter_kategori');
		$start_date = false;
		$end_date = false;
		$total_penerimaan = 0;
		$total_tagihan = 0;
		$total_tagihan_bruto = 0;
		$total_pembayaran = 0;
		$total_sisa_hutang_penerimaan = 0;
		$total_sisa_hutang_tagihan = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['date2'] = $end_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$this->db->select('ppo.id, ppo.supplier_id, ps.nama as name');
			$this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
			
			if(!empty($start_date) && !empty($end_date)){
				$this->db->where('prm.date_receipt >=',$start_date);
				$this->db->where('prm.date_receipt <=',$end_date);
			}
			if(!empty($supplier_id)){
				$this->db->where('ppo.supplier_id',$supplier_id);
			}
			if(!empty($filter_kategori)){
				$this->db->where('ppo.kategori_id',$filter_kategori);
			}

		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_receipt_material prm');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetLaporanHutang($sups['supplier_id'],$start_date,$end_date,$filter_kategori);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$arr['no'] = $key + 1;
							$arr['nama_produk'] = $row['nama_produk'];
							$arr['purchase_order_id'] = '<a href="'.base_url().'pmm/purchase_order/manage/'.$row['purchase_order_id'].'" target="_blank">'.$row['purchase_order_id'] = $this->crud_global->GetField('pmm_purchase_order',array('id'=>$row['purchase_order_id']),'no_po').'</a>';
							$arr['penerimaan'] = number_format($row['penerimaan'],0,',','.');
							$arr['tagihan'] = number_format($row['tagihan'],0,',','.');
							$arr['tagihan_bruto'] = number_format($row['tagihan_bruto'],0,',','.');
							$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');
							$arr['sisa_hutang_penerimaan'] = number_format($row['sisa_hutang_penerimaan'],0,',','.');
							$arr['sisa_hutang_tagihan'] = number_format($row['sisa_hutang_tagihan'],0,',','.');

							$total_penerimaan += $row['penerimaan'];
							$total_tagihan += $row['tagihan'];
							$total_tagihan_bruto += $row['tagihan_bruto'];
							$total_pembayaran += $row['pembayaran'];
							$total_sisa_hutang_penerimaan += $row['sisa_hutang_penerimaan'];
							$total_sisa_hutang_tagihan += $row['sisa_hutang_tagihan'];
							
							
							$arr['name'] = $sups['name'];
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$sups['no'] =$no;

						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total_penerimaan'] = $total_penerimaan;
			$data['total_tagihan'] = $total_tagihan;
			$data['total_tagihan_bruto'] = $total_tagihan_bruto;
			$data['total_pembayaran'] = $total_pembayaran;
			$data['total_sisa_hutang_penerimaan'] = $total_sisa_hutang_penerimaan;
			$data['total_sisa_hutang_tagihan'] = $total_sisa_hutang_tagihan;
	        $html = $this->load->view('laporan_pembelian/cetak_laporan_hutang',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Hutang');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-hutang.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_monitoring_hutang()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('L');
		$pdf->setPrintHeader(false);

		//Page2
		$pdf->AddPage('L', 'A4');
		$pdf->SetY(29);
		$pdf->SetX(6);
		$html =
		'<style type="text/css">
			body {
			font-family: helvetica;
		}

		table.table-border-atas-full, th.table-border-atas-full, td.table-border-atas-full {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-atas-only, th.table-border-atas-only, td.table-border-atas-only {
			border-top: 1px solid black;
		}

		table.table-border-bawah-only, th.table-border-bawah-only, td.table-border-bawah-only {
			border-bottom: 1px solid black;
		}

		table tr.table-judul{
			border: 1px solid;
			background-color: #e69500;
			font-weight: bold;
			font-size: 5px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
			font-size: 5px;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-size: 5px;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #FFFF00;
			font-weight: bold;
			font-size: 5px;
			color: black;
		}

		table tr.table-total2{
			background-color: #eeeeee;
			font-weight: bold;
			font-size: 5px;
			color: black;
		}
		</style>
		 <table width="98%" border="0" cellpadding="2">
		 <tr class="table-judul">
			<th width="3%" align="center" rowspan="2" class="table-border-atas-full">&nbsp; <br />NO.</th>
			<th width="12%" align="center" rowspan="2" class="table-border-atas-full">&nbsp; <br />REKANAN / NO. TAGIHAN</th>
			<th width="6%" align="center" rowspan="2" class="table-border-atas-full">&nbsp; <br />TGL. TAGIHAN</th>
			<th width="9%" align="center" rowspan="2" class="table-border-atas-full">&nbsp; <br />JENIS PEMBELIAN</th>
			<th width="5%" align="center" rowspan="2" class="table-border-atas-full">TGL. VERIFIKASI</th>
			<th width="5%" align="center" rowspan="2" class="table-border-atas-full">SYARAT PEMBAYARAN</th>
			<th width="15%" align="center" colspan="3" class="table-border-atas-only">TAGIHAN</th>
			<th width="20%" align="center" colspan="4" class="table-border-atas-only">PEMBAYARAN</th>
			<th width="15%" align="center" colspan="3" class="table-border-atas-only">SISA HUTANG</th>
			<th width="5%" align="center" rowspan="2" class="table-border-atas-full">&nbsp; <br />STATUS</th>
			<th width="5%" align="center" rowspan="2" class="table-border-atas-full">TGL. JATUH TEMPO</th>
		</tr>
		<tr class="table-judul">
			<th align="center" class="table-border-bawah-only">DPP</th>
			<th align="center" class="table-border-bawah-only">PPN</th>
			<th align="center" class="table-border-bawah-only">JUMLAH</th>
			<th align="center" class="table-border-bawah-only">DPP</th>
			<th align="center" class="table-border-bawah-only">PPN</th>
			<th align="center" class="table-border-bawah-only">PPH</th>
			<th align="center" class="table-border-bawah-only">JUMLAH</th>
			<th align="center" class="table-border-bawah-only">DPP</th>
			<th align="center" class="table-border-bawah-only">PPN</th>
			<th align="center" class="table-border-bawah-only">JUMLAH</th>
		</tr>	
		</table>';
		$pdf->writeHTML($html, true, false, true, false, '');

		//Page3
		$pdf->AddPage();
		$pdf->SetY(29);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page1
		$pdf->setPage(1, true);
		$pdf->SetY(35);
		$pdf->Cell(0, 0, '', 0, 0, 'C');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$filter_kategori = $this->input->get('filter_kategori');
		$filter_status = $this->input->get('filter_status');
		$start_date = false;
		$end_date = false;
		$total_dpp_tagihan = 0;
		$total_ppn_tagihan = 0;
		$total_pph_tagihan = 0;
		$total_jumlah_tagihan = 0;
		$total_dpp_pembayaran = 0;
		$total_ppn_pembayaran = 0;
		$total_pph_pembayaran = 0;
		$total_jumlah_pembayaran = 0;
		$total_dpp_sisa_hutang = 0;
		$total_ppn_sisa_hutang = 0;
		$total_pph_sisa_hutang = 0;
		$total_jumlah_sisa_hutang = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['date2'] = $end_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$this->db->select('ppp.id, ppp.supplier_id, ps.nama as name');
			$this->db->join('penerima ps','ppp.supplier_id = ps.id','left');
			$this->db->join('pmm_purchase_order ppo','ppp.purchase_order_id = ppo.id','left');
			
			if(!empty($start_date) && !empty($end_date)){
				$this->db->where('ppp.created_on >=',$start_date.' 23:59:59');
           		$this->db->where('ppp.created_on <=',$end_date.' 23:59:59');
			}
			if(!empty($supplier_id)){
				$this->db->where('ppo.supplier_id',$supplier_id);
			}
			if(!empty($filter_kategori)){
				$this->db->where('ppo.kategori_id',$filter_kategori);
			}
			if(!empty($filter_status)){
				$this->db->where('ppp.status',$filter_status);
			}

			$this->db->where("ppp.verifikasi_dok in ('SUDAH','LENGKAP')");
			$this->db->group_by('ppp.supplier_id');
			$this->db->order_by('ps.nama','asc');
			$query = $this->db->get('pmm_penagihan_pembelian ppp');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetLaporanMonitoringHutang($sups['supplier_id'],$start_date,$end_date,$filter_kategori,$filter_status);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$awal  = date_create($row['status_umur_hutang']);
							$akhir = date_create($end_date);
							$diff  = date_diff( $awal, $akhir );

							$tanggal_tempo = date('Y-m-d', strtotime(+$row['syarat_pembayaran'].'days', strtotime($row['tanggal_lolos_verifikasi'])));

							$awal_tempo =date_create($tanggal_tempo);
							$akhir_tempo =date_create($end_date);
							$diff_tempo =date_diff($awal_tempo,$akhir_tempo);

							$arr['no'] = $key + 1;
							$arr['nama'] = $row['nama'];
							$arr['subject'] = $row['subject'];
							$arr['status'] = $row['status'];
							$arr['syarat_pembayaran'] = $row['syarat_pembayaran'];
							//$arr['syarat_pembayaran'] = $diff->days . ' Hari';
							//$arr['syarat_pembayaran'] = $diff->days . ' ';
							//$arr['jatuh_tempo'] =  $diff_tempo->format("%R%a");
							$arr['jatuh_tempo'] =  date('d-m-Y',strtotime($tanggal_tempo));
							$arr['nomor_invoice'] = $row['nomor_invoice'];
							$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
							$arr['tanggal_lolos_verifikasi'] = date('d-m-Y',strtotime($row['tanggal_lolos_verifikasi']));
							$arr['dpp_tagihan'] = number_format($row['dpp_tagihan'],0,',','.');
							$arr['ppn_tagihan'] = number_format($row['ppn_tagihan'],0,',','.');
							$arr['jumlah_tagihan'] = number_format($row['jumlah_tagihan'],0,',','.');
							$arr['dpp_pembayaran'] = number_format($row['dpp_pembayaran'],0,',','.');
							$arr['ppn_pembayaran'] = number_format($row['ppn_pembayaran'],0,',','.');
							$arr['pph_pembayaran'] = number_format($row['pph_pembayaran'],0,',','.');
							$arr['jumlah_pembayaran'] = number_format($row['jumlah_pembayaran'],0,',','.');
							$arr['dpp_sisa_hutang'] = number_format($row['dpp_sisa_hutang'],0,',','.');
							$arr['ppn_sisa_hutang'] = number_format($row['ppn_sisa_hutang'],0,',','.');
							$arr['jumlah_sisa_hutang'] = number_format($row['jumlah_sisa_hutang'],0,',','.');

							$total_dpp_tagihan += $row['dpp_tagihan'];
							$total_ppn_tagihan += $row['ppn_tagihan'];
							$total_jumlah_tagihan += $row['jumlah_tagihan'];
							$total_dpp_pembayaran += $row['dpp_pembayaran'];
							$total_ppn_pembayaran += $row['ppn_pembayaran'];
							$total_pph_pembayaran += $row['pph_pembayaran'];
							$total_jumlah_pembayaran += $row['jumlah_pembayaran'];
							$total_dpp_sisa_hutang += $row['dpp_sisa_hutang'];
							$total_ppn_sisa_hutang += $row['ppn_sisa_hutang'];
							$total_jumlah_sisa_hutang += $row['jumlah_sisa_hutang'];
							
							
							$arr['name'] = $sups['name'];
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$sups['no'] =$no;

						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total_dpp_tagihan'] = $total_dpp_tagihan;
			$data['total_ppn_tagihan'] = $total_ppn_tagihan;
			$data['total_jumlah_tagihan'] = $total_jumlah_tagihan;
			$data['total_dpp_pembayaran'] = $total_dpp_pembayaran;
			$data['total_ppn_pembayaran'] = $total_ppn_pembayaran;
			$data['total_pph_pembayaran'] = $total_pph_pembayaran;
			$data['total_jumlah_pembayaran'] = $total_jumlah_pembayaran;
			$data['total_dpp_sisa_hutang'] = $total_dpp_sisa_hutang;
			$data['total_ppn_sisa_hutang'] = $total_ppn_sisa_hutang;
			$data['total_jumlah_sisa_hutang'] = $total_jumlah_sisa_hutang;
	        $html = $this->load->view('laporan_pembelian/cetak_monitoring_hutang',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Monitoring Hutang');
	        $pdf->nsi_html($html);
	        $pdf->Output('monitoring-hutang.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_pengiriman_penjualan()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$filter_client_id = $this->input->get('filter_client_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_product = $this->input->get('filter_product');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_nilai = 0;
		$total_volume = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		
		$this->db->select('ppo.client_id, pp.convert_measure as convert_measure, ps.nama as name, SUM(pp.display_volume) as total, SUM(pp.display_price) as total_price');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($filter_client_id)){
            $this->db->where('ppo.client_id',$filter_client_id);
        }
        if(!empty($filter_product)){
            $this->db->where_in('pp.product_id',$filter_product);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pp.salesPo_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps','ppo.client_id = ps.id','left');
		$this->db->join('pmm_productions pp','ppo.id = pp.salesPo_id','left');
		$this->db->where("ppo.status in ('OPEN','CLOSED')");
		$this->db->where('pp.status','PUBLISH');
		$this->db->where("pp.product_id in (3,4,7,8,9,14,24)");
		$this->db->group_by('ppo.client_id');
		$query = $this->db->get('pmm_sales_po ppo');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat17($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_product);
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['real'] = number_format($row['total'],2,',','.');
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['name'] = $sups['name'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total_volume += $sups['total'];
					$total_nilai += $sups['total_price'];
					$sups['no'] = $no;
					$sups['real'] = number_format($sups['total'],2,',','.');
					$sups['total_price'] = number_format($sups['total_price'],0,',','.');

					$arr_data[] = $sups;
					$no++;
				}
				
				
			}
		}

			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_penjualan/001_cetak_pengiriman_penjualan',$data,TRUE);
	        
	        $pdf->SetTitle('BBJ - Laporan Penjualan');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-penjualan.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_laporan_piutang()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$client_id = $this->input->get('client_id');
		$start_date = false;
		$end_date = false;
		$total_penerimaan = 0;
		$total_tagihan = 0;
		$total_tagihan_bruto = 0;
		$total_pembayaran = 0;
		$total_sisa_piutang_penerimaan = 0;
		$total_sisa_piutang_tagihan = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['date2'] = $end_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

		$this->db->select('po.id, po.client_id, ps.nama as name');
		$this->db->join('pmm_sales_po po','pp.salesPo_id = po.id','left');

		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($client_id)){
            $this->db->where('po.client_id',$client_id);
        }
		
		$this->db->join('penerima ps','po.client_id = ps.id','left');
		$this->db->where("po.status in ('OPEN','CLOSED')");
		$this->db->group_by('po.client_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_productions pp');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetLaporanPiutang($sups['client_id'],$start_date,$end_date);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$arr['no'] = $key + 1;
                            $arr['nama_produk'] = $row['nama_produk'];
                            $arr['salesPo_id'] = $this->crud_global->GetField('pmm_sales_po',array('id'=>$row['salesPo_id']),'contract_number');
                            $arr['penerimaan'] = number_format($row['penerimaan'],0,',','.');
                            $arr['tagihan'] = number_format($row['tagihan'],0,',','.');
                            $arr['tagihan_bruto'] = number_format($row['tagihan_bruto'],0,',','.');
                            $arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');
                            $arr['sisa_piutang_penerimaan'] = number_format($row['sisa_piutang_penerimaan'],0,',','.');
                            $arr['sisa_piutang_tagihan'] = number_format($row['sisa_piutang_tagihan'],0,',','.');

                            $total_penerimaan += $row['penerimaan'];
                            $total_tagihan += $row['tagihan'];
                            $total_tagihan_bruto += $row['tagihan_bruto'];
                            $total_pembayaran += $row['pembayaran'];
                            $total_sisa_piutang_penerimaan += $row['sisa_piutang_penerimaan'];
                            $total_sisa_piutang_tagihan += $row['sisa_piutang_tagihan'];
							
							
							$arr['name'] = $sups['name'];
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$sups['no'] =$no;

						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total_penerimaan'] = $total_penerimaan;
			$data['total_tagihan'] = $total_tagihan;
			$data['total_tagihan_bruto'] = $total_tagihan_bruto;
			$data['total_pembayaran'] = $total_pembayaran;
			$data['total_sisa_piutang_penerimaan'] = $total_sisa_piutang_penerimaan;
			$data['total_sisa_piutang_tagihan'] = $total_sisa_piutang_tagihan;
	        $html = $this->load->view('laporan_penjualan/cetak_laporan_piutang',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Piutang');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-piutang.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_monitoring_piutang()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('L');
		$pdf->setPrintHeader(false);

		//Page2
		$pdf->AddPage('L', 'A4');
		$pdf->SetY(31);
		$pdf->SetX(6);
		$html =
		'<style type="text/css">
			body {
			font-family: helvetica;
		}

		table.table-border-atas-full, th.table-border-atas-full, td.table-border-atas-full {
			border-top: 1px solid black;
			border-bottom: 1px solid black;
		}

		table.table-border-atas-only, th.table-border-atas-only, td.table-border-atas-only {
			border-top: 1px solid black;
		}

		table.table-border-bawah-only, th.table-border-bawah-only, td.table-border-bawah-only {
			border-bottom: 1px solid black;
		}

		table tr.table-judul{
			border: 1px solid;
			background-color: #e69500;
			font-weight: bold;
			font-size: 5px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: none;
			font-size: 5px;
		}

		table tr.table-baris1-bold{
			background-color: none;
			font-size: 5px;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #FFFF00;
			font-weight: bold;
			font-size: 5px;
			color: black;
		}

		table tr.table-total2{
			background-color: #eeeeee;
			font-weight: bold;
			font-size: 5px;
			color: black;
		}
		</style>
		<table width="98%" border="0" cellpadding="2">
		<tr class="table-judul">
			<th width="5%" align="center" rowspan="2" style="vertical-align:middle;" class="table-border-atas-full">&nbsp; <br />NO.</th>
			<th width="14%" align="center" class="table-border-atas-only">PELANGGAN</th>
			<th width="7%" align="center" rowspan="2" style="vertical-align:middle;" class="table-border-atas-full">&nbsp; <br />NO. INV</th>
			<th width="7%" align="center" rowspan="2" style="vertical-align:middle;" class="table-border-atas-full">&nbsp; <br />TGL. INV</th>
			<th width="17%" align="center" colspan="3" class="table-border-atas-only">TAGIHAN</th>
			<th width="17%" align="center" colspan="3" class="table-border-atas-only">PEMBAYARAN</th>
			<th width="17%" align="center" colspan="3" class="table-border-atas-only">SISA PIUTANG</th>
			<th width="8%" align="center" rowspan="2" style="vertical-align:middle;" class="table-border-atas-full">&nbsp; <br />STATUS</th>
			<th width="8%" align="center" rowspan="2" style="vertical-align:middle;" class="table-border-atas-full">&nbsp; <br />UMUR</th>
		</tr>
		<tr class="table-judul">
			<th align="center" class="table-border-bawah-only">KETERANGAN</th>
			<th align="center" class="table-border-bawah-only">DPP</th>
			<th align="center" class="table-border-bawah-only">PPN</th>
			<th align="center" class="table-border-bawah-only">JUMLAH</th>
			<th align="center" class="table-border-bawah-only">DPP</th>
			<th align="center" class="table-border-bawah-only">PPN</th>
			<th align="center" class="table-border-bawah-only">JUMLAH</th>
			<th align="center" class="table-border-bawah-only">DPP</th>
			<th align="center" class="table-border-bawah-only">PPN</th>
			<th align="center" class="table-border-bawah-only">JUMLAH</th>
		</tr>
		</table>';
		$pdf->writeHTML($html, true, false, true, false, '');

		//Page3
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page4
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page5
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page6
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page7
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page8
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page9
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page10
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page11
		$pdf->AddPage();
		$pdf->SetY(31);
		$pdf->SetX(6);
		$pdf->WriteHTML($html);

		//Page1
		$pdf->setPage(1, true);
		$pdf->SetY(35);
		$pdf->Cell(0, 0, '', 0, 0, 'C');

		$arr_data = array();
		$client_id = $this->input->get('client_id');
		$filter_kategori = $this->input->get('filter_kategori');
		$filter_status = $this->input->get('filter_status');
		$start_date = false;
		$end_date = false;
		$total_dpp_tagihan = 0;
		$total_ppn_tagihan = 0;
		$total_jumlah_tagihan = 0;
		$total_dpp_pembayaran = 0;
		$total_ppn_pembayaran = 0;
		$total_jumlah_pembayaran = 0;
		$total_dpp_sisa_piutang = 0;
		$total_ppn_sisa_piutang = 0;
		$total_jumlah_sisa_piutang = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['date2'] = $end_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;

			$this->db->select('ppp.id, ppp.client_id, ps.nama as name');
			$this->db->join('penerima ps','ppp.client_id = ps.id','left');
			$this->db->join('pmm_sales_po po','ppp.sales_po_id = po.id','left');

            if(!empty($start_date) && !empty($end_date)){
                $this->db->where('ppp.tanggal_invoice >=',$start_date);
                $this->db->where('ppp.tanggal_invoice <=',$end_date);
            }
            if(!empty($client_id)){
                $this->db->where('ppp.client_id',$client_id);
            }
			if(!empty($filter_status)){
                $this->db->where('ppp.status_pembayaran',$filter_status);
            }
            
			$this->db->group_by('ppp.client_id');
			$this->db->order_by('ps.nama','asc');
			$query = $this->db->get('pmm_penagihan_penjualan ppp');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetLaporanMonitoringPiutang($sups['client_id'],$start_date,$end_date,$filter_kategori,$filter_status);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							
							$awal  = date_create($row['status_umur_hutang']);
							$akhir = date_create($end_date);
							$diff  = date_diff($awal, $akhir);

							$arr['no'] = $key + 1;
                            $arr['nama'] = $row['nama'];
                            $arr['subject'] = $row['subject'];
                            $arr['status_pembayaran'] = $row['status_pembayaran'];
                            $arr['syarat_pembayaran'] = $diff->days;
                            $arr['nomor_invoice'] = $row['nomor_invoice'];
                            $arr['tanggal_invoice'] =  date('d-m-Y',strtotime($row['tanggal_invoice']));
                            $arr['dpp_tagihan'] = number_format($row['dpp_tagihan'],0,',','.');
                            $arr['ppn_tagihan'] = number_format($row['ppn_tagihan'],0,',','.');
                            $arr['jumlah_tagihan'] = number_format($row['jumlah_tagihan'],0,',','.');
                            $arr['dpp_pembayaran'] = number_format($row['dpp_pembayaran'],0,',','.');
                            $arr['ppn_pembayaran'] = number_format($row['ppn_pembayaran'],0,',','.');
                            $arr['jumlah_pembayaran'] = number_format($row['jumlah_pembayaran'],0,',','.');
                            $arr['dpp_sisa_piutang'] = number_format($row['dpp_sisa_piutang'],0,',','.');
                            $arr['ppn_sisa_piutang'] = number_format($row['ppn_sisa_piutang'],0,',','.');
                            $arr['jumlah_sisa_piutang'] = number_format($row['jumlah_sisa_piutang'],0,',','.');

							$total_dpp_tagihan += $row['dpp_tagihan'];
							$total_ppn_tagihan += $row['ppn_tagihan'];
							$total_jumlah_tagihan += $row['jumlah_tagihan'];
							$total_dpp_pembayaran += $row['dpp_pembayaran'];
							$total_ppn_pembayaran += $row['ppn_pembayaran'];
							$total_jumlah_pembayaran += $row['jumlah_pembayaran'];
							$total_dpp_sisa_piutang += $row['dpp_sisa_piutang'];
							$total_ppn_sisa_piutang += $row['ppn_sisa_piutang'];
							$total_jumlah_sisa_piutang += $row['jumlah_sisa_piutang'];
							
							
							$arr['name'] = $sups['name'];
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$sups['no'] =$no;

						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total_dpp_tagihan'] = $total_dpp_tagihan;
			$data['total_ppn_tagihan'] = $total_ppn_tagihan;
			$data['total_jumlah_tagihan'] = $total_jumlah_tagihan;
			$data['total_dpp_pembayaran'] = $total_dpp_pembayaran;
			$data['total_ppn_pembayaran'] = $total_ppn_pembayaran;
			$data['total_jumlah_pembayaran'] = $total_jumlah_pembayaran;
			$data['total_dpp_sisa_piutang'] = $total_dpp_sisa_piutang;
			$data['total_ppn_sisa_piutang'] = $total_ppn_sisa_piutang;
			$data['total_jumlah_sisa_piutang'] = $total_jumlah_sisa_piutang;
	        $html = $this->load->view('laporan_penjualan/cetak_monitoring_piutang',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Monitoring Piutang');
	        $pdf->nsi_html($html);
	        $pdf->Output('monitoring-piutang.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function laporan_produksi_harian_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
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
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
		
		$this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, pphd.duration, pphd.capacity, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.purchase_order_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.date_prod');
		$query = $this->db->get('pmm_produksi_harian pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat8a($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['produk_a'] = $row['produk_a'];
						$arr['produk_b'] = $row['produk_b'];
						$arr['produk_c'] = $row['produk_c'];
						$arr['produk_d'] = $row['produk_d'];
						$arr['produk_e'] = $row['produk_e'];
						$arr['measure_a'] = $row['measure_a'];
						$arr['measure_b'] = $row['measure_b'];
						$arr['measure_c'] = $row['measure_c'];
						$arr['measure_d'] = $row['measure_a'];
						$arr['measure_e'] = $row['measure_a'];
						$arr['presentase_a'] = $row['presentase_a'];
						$arr['presentase_b'] = $row['presentase_b'];
						$arr['presentase_c'] = $row['presentase_c'];
						$arr['presentase_d'] = $row['presentase_d'];
						$arr['presentase_e'] = $row['presentase_e'];
					
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['jumlah_used'];
					$sups['no'] =$no;
					$sups['jumlah_used'] = number_format($sups['jumlah_used'],2,',','.');
					$sups['date_prod'] = date('d-m-Y',strtotime($sups['date_prod']));
					

					$arr_data[] = $sups;
					$no++;
					
					}	
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_produksi/cetak_laporan_produksi_harian',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Produksi Harian');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-produksi-harian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function laporan_produksi_campuran_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true); 
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
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
		
		$this->db->select('pph.date_prod, pph.no_prod, pk.jobs_type as agregat, pphd.measure as satuan, SUM(pphd.volume_convert) as volume, (SUM(pphd.volume_convert) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.volume_convert) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.volume_convert) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.volume_convert) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pphd.produksi_campuran_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_campuran_detail pphd', 'pph.id = pphd.produksi_campuran_id','left');
		$this->db->join('pmm_agregat pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.date_prod');
		$query = $this->db->get('pmm_produksi_campuran pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMatCampuran($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['produk_a'] = $row['produk_a'];
						$arr['produk_b'] = $row['produk_b'];
						$arr['produk_c'] = $row['produk_c'];
						$arr['produk_d'] = $row['produk_d'];
						$arr['measure_a'] = $row['measure_a'];
						$arr['measure_b'] = $row['measure_b'];
						$arr['measure_c'] = $row['measure_c'];
						$arr['measure_d'] = $row['measure_a'];
						$arr['presentase_a'] = $row['presentase_a'];
						$arr['presentase_b'] = $row['presentase_b'];
						$arr['presentase_c'] = $row['presentase_c'];
						$arr['presentase_d'] = $row['presentase_d'];
					
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['volume'];
					$sups['no'] =$no;
					$sups['volume'] = number_format($sups['volume'],2,',','.');
					$sups['date_prod'] = date('d-m-Y',strtotime($sups['date_prod']));
					

					$arr_data[] = $sups;
					$no++;
					
					}	
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_produksi/cetak_laporan_produksi_campuran',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Produksi Campuran');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-produksi-campuran.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function laporan_evaluasi_produksi_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
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
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
		
		$this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, SUM(pphd.capacity) as jumlah_capacity');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.purchase_order_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.date_prod');
		$query = $this->db->get('pmm_produksi_harian pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat8($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['date_prod'] = $row['date_prod'];
						$arr['duration'] = $row['duration'];
						$arr['used'] = $row['used'];
						$arr['capacity'] = $row['capacity'];
					
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['jumlah_used'];
					$sups['no'] =$no;
					$sups['jumlah_capacity'] = number_format($sups['jumlah_capacity'],2,',','.');
					

					$arr_data[] = $sups;
					$no++;
					
					}	
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_ev_produksi/cetak_laporan_evaluasi_produksi',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Evaluasi Produksi');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-evaluasi-produksi.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function rekapitulasi_laporan_produksi_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
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
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;
			$data['start_date'] = $start_date;
			$data['end_date'] = $end_date;
		
		$this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.purchase_order_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$query = $this->db->get('pmm_produksi_harian pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat8b($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['produk_a'] = $row['produk_a'];
						$arr['produk_b'] = $row['produk_b'];
						$arr['produk_c'] = $row['produk_c'];
						$arr['produk_d'] = $row['produk_d'];
						$arr['produk_e'] = $row['produk_e'];
						$arr['measure_a'] = $row['measure_a'];
						$arr['measure_b'] = $row['measure_b'];
						$arr['measure_c'] = $row['measure_c'];
						$arr['measure_d'] = $row['measure_a'];
						$arr['measure_e'] = $row['measure_a'];
						$arr['presentase_a'] = $row['presentase_a'];
						$arr['presentase_b'] = $row['presentase_b'];
						$arr['presentase_c'] = $row['presentase_c'];
						$arr['presentase_d'] = $row['presentase_d'];
						$arr['presentase_e'] = $row['presentase_e'];
					
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['jumlah_used'];
					$sups['no'] =$no;
					$sups['jumlah_used'] = number_format($sups['jumlah_used'],2,',','.');
					$sups['produk_a'] = $sups['produk_a'];
					$sups['produk_b'] = $sups['produk_b'];
					$sups['produk_c'] = $sups['produk_c'];
					$sups['produk_d'] = $sups['produk_d'];
					$sups['produk_e'] = $sups['produk_e'];
					$sups['measure_a'] = $sups['measure_a'];
					$sups['measure_b'] = $sups['measure_b'];
					$sups['measure_c'] = $sups['measure_c'];
					$sups['measure_d'] = $sups['measure_d'];
					$sups['measure_e'] = $sups['measure_e'];
					$sups['presentase_a'] = $sups['presentase_a'];
					$sups['presentase_b'] = $sups['presentase_b'];
					$sups['presentase_c'] = $sups['presentase_c'];
					$sups['presentase_d'] = $sups['presentase_d'];
					$sups['presentase_e'] = $sups['presentase_e'];
					$sups['jumlah_pemakaian_a'] = number_format($sups['jumlah_pemakaian_a'],2,',','.');
					$sups['jumlah_pemakaian_b'] = number_format($sups['jumlah_pemakaian_b'],2,',','.');
					$sups['jumlah_pemakaian_c'] = number_format($sups['jumlah_pemakaian_c'],2,',','.');
					$sups['jumlah_pemakaian_d'] = number_format($sups['jumlah_pemakaian_d'],2,',','.');
					$sups['jumlah_pemakaian_e'] = number_format($sups['jumlah_pemakaian_e'],2,',','.');
					

					$arr_data[] = $sups;
					$no++;
					
					}	
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_produksi/cetak_rekapitulasi_laporan_produksi',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Rekapitulasi Laporan Produksi');
	        $pdf->nsi_html($html);
	        $pdf->Output('rekapitulasi-laporan-produksi.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function laporan_laba_rugi_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$start_date = date('Y-m-d',strtotime($arr_filter_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
        $html = $this->load->view('laporan_keuangan/laporan_laba_rugi_print',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Laporan Laba Rugi');
        $pdf->nsi_html($html);
        $pdf->Output('laporan-laba-rugi.pdf', 'I');
	
	}

	public function laporan_laba_rugi_new_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$start_date = date('Y-m-d',strtotime($arr_filter_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
        $html = $this->load->view('laporan_keuangan/laporan_laba_rugi_new_print',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Laporan Laba Rugi');
        $pdf->nsi_html($html);
        $pdf->Output('laporan-laba-rugi.pdf', 'I');
	
	}
	
	public function beban_pokok_produksi_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$start_date = date('Y-m-d',strtotime($arr_filter_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
        $html = $this->load->view('beban_pokok_produksi/cetak_beban_pokok_produksi',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Beban Pokok Produksi');
        $pdf->nsi_html($html);
        $pdf->Output('beban-pokok-produksi.pdf', 'I');
	
	}
	
	public function pergerakan_bahan_baku_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
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
        $html = $this->load->view('beban_pokok_produksi/cetak_pergerakan_bahan_baku',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Pergerakan Bahan Baku');
        $pdf->nsi_html($html);
        $pdf->Output('pergerakan-bahan-baku.pdf', 'I');
	
	}

	public function pergerakan_bahan_baku_penyesuaian_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$start_date = date('Y-m-d',strtotime($arr_filter_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
        $html = $this->load->view('beban_pokok_produksi/cetak_pergerakan_bahan_baku_penyesuaian',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Pergerakan Bahan Baku');
        $pdf->nsi_html($html);
        $pdf->Output('pergerakan-bahan-baku.pdf', 'I');
	
	}
	
	public function nilai_persediaan_bahan_baku_print()
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
        $html = $this->load->view('laporan_produksi/cetak_nilai_persediaan_bahan_baku',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Nilai Persedaiaan Bahan Baku');
        $pdf->nsi_html($html);
        $pdf->Output('nilai-persediaan-bahan-baku.pdf', 'I');
	
	}
	
	public function pergerakan_bahan_jadi_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
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
        $html = $this->load->view('beban_pokok_produksi/cetak_pergerakan_bahan_jadi',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Pergerakan Bahan Jadi');
        $pdf->nsi_html($html);
        $pdf->Output('pergerakan-bahan-jadi.pdf', 'I');
	
	}

	public function pergerakan_bahan_jadi_penyesuaian_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
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
        $html = $this->load->view('beban_pokok_produksi/cetak_pergerakan_bahan_jadi_penyesuaian',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Pergerakan Bahan Jadi');
        $pdf->nsi_html($html);
        $pdf->Output('pergerakan-bahan-jadi.pdf', 'I');
	
	}

	public function nilai_persediaan_bahan_jadi_print()
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
        $html = $this->load->view('laporan_produksi/cetak_nilai_persediaan_bahan_jadi',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Nilai Persedaiaan Bahan Jadi');
        $pdf->nsi_html($html);
        $pdf->Output('nilai-persediaan-bahan-jadi.pdf', 'I');
	
	}

	public function evaluasi_nilai_persediaan_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_date = explode(' - ',$arr_date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
        $html = $this->load->view('laporan_ev_produksi/cetak_evaluasi_nilai_persediaan',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Evaluasi Nilai Persediaan');
        $pdf->nsi_html($html);
        $pdf->Output('evaluasi-nilai-persediaan.pdf', 'I');
	
	}

	public function cetak_biaya_umum_administratif()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true); 
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
		$data['biaya_langsung'] = $this->m_laporan->biaya_langsung_print($arr_date);
		$data['biaya_langsung_jurnal'] = $this->m_laporan->biaya_langsung_jurnal_print($arr_date);
        $data['biaya'] = $this->m_laporan->showBiaya_print($arr_date);
		$data['biaya_jurnal'] = $this->m_laporan->showBiayaJurnal_print($arr_date);
        $data['biaya_lainnya'] = $this->m_laporan->showBiayaLainnya_print($arr_date);
		$data['biaya_lainnya_jurnal'] = $this->m_laporan->showBiayaLainnyaJurnal_print($arr_date);
        $html = $this->load->view('laporan_keuangan/cetak_biaya_umum_administratif',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Biaya Umum & Administratif');
        $pdf->nsi_html($html);
        $pdf->Output('biaya_umum_administratif.pdf', 'I');
	}

	public function cetak_biaya_lainnya()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true); 
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
		$data['biaya_langsung'] = $this->m_laporan->biaya_langsung_print($arr_date);
		$data['biaya_langsung_jurnal'] = $this->m_laporan->biaya_langsung_jurnal_print($arr_date);
        $data['biaya'] = $this->m_laporan->showBiaya_print($arr_date);
		$data['biaya_jurnal'] = $this->m_laporan->showBiayaJurnal_print($arr_date);
        $data['biaya_lainnya'] = $this->m_laporan->showBiayaLainnya_print($arr_date);
		$data['biaya_lainnya_jurnal'] = $this->m_laporan->showBiayaLainnyaJurnal_print($arr_date);
        $html = $this->load->view('laporan_keuangan/cetak_biaya_lainnya',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Biaya Lainnya');
        $pdf->nsi_html($html);
        $pdf->Output('biaya_lainnya.pdf', 'I');
	}

	public function cetak_hutang_penerimaan()
	{
		$this->load->library('pdf');
	
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$filter_kategori = $this->input->get('filter_kategori');
		$start_date = false;
		$end_date = false;
		$grand_total_tagihan = 0;
		$grand_total_pembayaran = 0;
		$grand_total_hutang = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

		$this->db->select('ppo.supplier_id, ps.nama as name');

		if(!empty($start_date) && !empty($end_date)){
			$this->db->where('prm.date_receipt >=',$start_date);
			$this->db->where('prm.date_receipt <=',$end_date);
		}
		if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
		if(!empty($filter_kategori)){
            $this->db->where('ppo.kategori_id',$filter_kategori);
        }
		
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id','left');
		$this->db->join('pmm_penagihan_pembelian ppp','ppo.id = ppp.purchase_order_id','left');
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_purchase_order ppo');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMatHutangPenerimaan($sups['supplier_id'],$start_date,$end_date,$filter_kategori);

				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['date_po'] = date('d-m-Y',strtotime($row['date_po']));
						$arr['no_po'] = $row['no_po'];
						$arr['memo'] = $row['memo'];
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');
						$arr['hutang'] = number_format($row['hutang'],0,',','.');
						
						$arr['name'] = $sups['name'];
						$grand_total_tagihan += $row['total_price'];
						$grand_total_pembayaran += $row['pembayaran'];
						$grand_total_hutang += $row['hutang'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$sups['no'] =$no;
					
					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['grand_total_tagihan'] = $grand_total_tagihan;
			$data['grand_total_pembayaran'] = $grand_total_pembayaran;
			$data['grand_total_hutang'] = $grand_total_hutang;
			$html = $this->load->view('laporan_pembelian/cetak_hutang_penerimaan',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Hutang');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-hutang.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_daftar_tagihan_pembelian()
	{
		$this->load->library('pdf');
	
		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
		//$pdf->SetMargins(10, 10, 5);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);

		$pdf->AddPage('L');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		//$pdf->SetY(45);
		//$pdf->SetX(6);
		//$pdf->SetMargins(10, 10);
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$jumlah_all = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

		$this->db->select('ppp.supplier_id, ps.nama');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.created_on >=',$start_date.' 23:59:59');
            $this->db->where('ppp.created_on <=',$end_date.' 23:59:59');
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.supplier_id',$supplier_id);
        }
		
		$this->db->join('penerima ps', 'ppp.supplier_id = ps.id');
		$this->db->group_by('ppp.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_pembelian ppp');
		

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptTagihanPembelian($sups['supplier_id'],$start_date,$end_date);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['memo'] = $row['memo'];
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['measure'] = $row['measure'];
						$arr['harsat'] = number_format($row['harsat'],0,',','.');
						$arr['dpp'] = number_format($row['dpp'],0,',','.');
						$arr['tax_ppn'] = number_format($row['tax_ppn'],0,',','.');
						//$arr['tax_pph'] = number_format($row['tax_pph'],0,',','.');
						$arr['total'] = number_format(($row['volume'] * $row['harsat']) + $row['tax_ppn'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];

						$total_dpp += $row['dpp'];
						$total_ppn += $row['tax_ppn'];
						//$total_pph += $row['tax_pph'];
						$total_total += ($row['dpp'] + $row['tax_ppn']);

						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$sups['no'] =$no;
					$total = $total_dpp;
					$total_2 = $total_ppn;
					//$total_3 = $total_pph;
					$total_4 = $total_total;

					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['total_2'] = $total_2;
			$data['total_3'] = $total_3;
			$data['total_4'] = $total_4;
	        $html = $this->load->view('pembelian/cetak_daftar_tagihan_pembelian',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Daftar Tagihan Pembelian');
	        $pdf->nsi_html($html);
	        $pdf->Output('daftar-tagihan-pembelian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_daftar_tagihan_penjualan()
	{
		$this->load->library('pdf');
	
		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
		//$pdf->SetMargins(10, 10, 5);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);

		$pdf->AddPage('L');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		//$pdf->SetY(45);
		//$pdf->SetX(6);
		//$pdf->SetMargins(10, 10);
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$jumlah_all = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

		$this->db->select('ppp.client_id, ps.nama');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.created_on >=',$start_date);
            $this->db->where('ppp.created_on <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.client_id',$supplier_id);
        }
		
		$this->db->join('penerima ps', 'ppp.client_id = ps.id');
		$this->db->group_by('ppp.client_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_penjualan ppp');
		

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptTagihanPenjualan($sups['client_id'],$start_date,$end_date);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['memo'] = $row['memo'];
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['measure'] = $row['measure'];
						$arr['harsat'] = number_format($row['harsat'],0,',','.');
						$arr['dpp'] = number_format($row['dpp'],0,',','.');
						$arr['tax'] = number_format($row['tax'],0,',','.');
						$arr['total'] = number_format(($row['volume'] * $row['harsat']) + $row['tax'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];

						$total_dpp += $row['dpp'];
						$total_ppn += $row['tax'];
						$total_total += $row['dpp'] + $row['tax'];

						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$sups['no'] =$no;
					$total = $total_dpp;
					$total_2 = $total_ppn;
					$total_3 = $total_total;

					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['total_2'] = $total_2;
			$data['total_3'] = $total_3;
	        $html = $this->load->view('penjualan/cetak_daftar_tagihan_penjualan',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Daftar Tagihan Penjualan');
	        $pdf->nsi_html($html);
	        $pdf->Output('daftar-tagihan-penjualan.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_rencana_kerja()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('L');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$filter_date = date('Y-m-d',strtotime($arr_filter_date[0])).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('laporan_rencana_kerja/cetak_rencana_kerja',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Rencana Kerja Produksi');
        $pdf->nsi_html($html);
        $pdf->Output('rencana_kerja_produksi.pdf', 'I');
	}

	public function cetak_prognosa_produksi()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('L');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('laporan_rencana_kerja/cetak_prognosa_produksi',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Prognosa Produksi');
        $pdf->nsi_html($html);
        $pdf->Output('prognosa_produksi.pdf', 'I');
	
	}

	public function pesanan_pembelian($rencana_kerja_1,$date_1_awal,$date_1_akhir,$kebutuhan,$material_id)
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {
			$data['row'] = $this->db->get_where('pmm_penawaran_pembelian pp', array('pp.id' => $rencana_kerja_1))->row_array();
			$data['details'] = $this->db->get_where('pmm_penawaran_pembelian_detail ppd', array('ppd.penawaran_pembelian_id' => $rencana_kerja_1))->row_array();
			$data['no_po'] = $this->pmm_model->GetNoPONew();
			$data['request_no'] = $this->pmm_model->GetNoRMNew();
			$data['produk'] = $this->pmm_model->getMatByPenawaranRencanaKerjaAll();

			$data['stock_opname'] = $this->db->select('(cat.volume) as display_volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date < '$date_1_awal')")
			->where("cat.material_id = $material_id")
			->where("cat.status = 'PUBLISH'")
			->order_by('cat.date','desc')->limit(1)
			->get()->row_array();

			$total_po = $this->db->select('sum(pod.volume) as volume')
			->from('pmm_purchase_order ppo')
			->join('pmm_purchase_order_detail pod','ppo.id = pod.purchase_order_id','left')
			->where("(ppo.date_po between '$date_1_awal' and '$date_1_akhir')")
			->where("pod.material_id = $material_id")
			->get()->row_array();
			
			$data['purchase_order'] = $total_po['volume'];
			$data['kebutuhan'] = $kebutuhan;

			$total_penerimaan = $this->db->select('sum(prm.volume) as volume')
			->from('pmm_receipt_material prm')
			->where("(prm.date_receipt between '$date_1_awal' and '$date_1_akhir')")
			->where("prm.material_id = $material_id")
			->get()->row_array();

			$data['total_receipt'] = $total_penerimaan['volume'];

			$this->load->view('laporan_rencana_kerja/pesanan_pembelian', $data);
        } else {
            redirect('admin');
        }
    }

	public function cetak_kebutuhan_bahan_alat($rencana_kerja_1)
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['rak'] = $this->db->get_where('rak',array('id'=>$rencana_kerja_1))->row_array();
        $html = $this->load->view('laporan_rencana_kerja/cetak_kebutuhan_bahan_alat',$data,TRUE);
        $rak = $this->db->get_where('rak',array('id'=>$rencana_kerja_1))->row_array();

        $pdf->SetTitle('BBJ - Kebutuhan Bahan & Alat');
        $pdf->nsi_html($html);
        $pdf->Output('kebutuhan_bahan_alat.pdf', 'I');
	}
	
	public function cetak_penyusutan_rekap()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('L');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		//Page2
		$pdf->AddPage('L', 'A4');
		$pdf->SetY(27);
		$pdf->SetX(5);
		
		//Page3
		$pdf->AddPage('L', 'A4');
		$pdf->SetY(27);
		$pdf->SetX(5);
		$html =
		'<style type="text/css">
		body{
			font-family: "Times New Roman", Times, serif;
		}
		table.minimalistBlack {
		border: 0px solid #000000;
		width: 100%;
		text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
			border: 0px solid #000000;
			padding: 5px 4px;
		}
		table.minimalistBlack tr td {
			/*font-size: 13px;*/
			text-align:center;
		}
		table.minimalistBlack tr th {
			/*font-size: 14px;*/
			font-weight: bold;
			color: #000000;
			text-align: center;
			padding: 8px;
		}
		table.head tr th {
			/*font-size: 14px;*/
			font-weight: bold;
			color: #000000;
			text-align: left;
			padding: 8px;
		}
		table tr.table-active{
			background-color: #e69500 ;
			font-weight: bold;
		}
		table tr.table-active2{
			font-weight: bold;
		}
		table tr.table-active3{
			background-color: #FFFF00;
			font-weight: bold;
		}
		hr{
			margin-top:0;
			margin-bottom:30px;
		}
		h3{
			margin-top:0;
		}
		</style>
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th align="center" width="5%">NO.</th>
				<th align="center" width="25%">PRODUK</th>
				<th align="center" width="10%">MASA MANFAAT</th>
				<th align="center" width="10%">HARGA PEROLEHAN (Rp.)</th>
				<th align="center" width="10%">TAHUN PEROLEHAN</th>
				<th align="center" width="10%">NILAI PENYUSUTAN /TAHUN (Rp.)</th>
				<th align="center" width="10%">NILAI PENYUSUTAN /BULAN (Rp.)</th>
				<th align="center" width="10%">NILAI PENYUSUTAN /HARI (Rp.)</th>
				<th align="center" width="10%">NILAI PENYUSUTAN /JAM (Rp.)</th>
			</tr>
		</table>';
		$pdf->writeHTML($html, true, false, true, false, '');

		//Page1
		$pdf->setPage(1, true);
		$pdf->SetY(35);
		$pdf->Cell(0, 0, '', 0, 0, 'C');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$start_date = date('Y-m-d',strtotime($arr_filter_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
        $html = $this->load->view('rap/cetak_penyusutan_rekap',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Penyusutan');
        $pdf->nsi_html($html);
        $pdf->Output('penyusutan.pdf', 'I');
	
	}

}