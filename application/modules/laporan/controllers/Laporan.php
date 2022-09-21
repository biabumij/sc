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
	 
	public function cetak_pengiriman_penjualan_produk()
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
        $html = $this->load->view('laporan_penjualan/cetak_pengiriman_penjualan_produk',$data,TRUE);

        $pdf->SetTitle('BBJ - Pengiriman Penjualan Produk');
        $pdf->nsi_html($html);
        $pdf->Output('pengiriman-penjualan-produk.pdf', 'I');
	
	}
	
	public function cetak_sales_order()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetMargins(3, 0, 0, true);
		$pdf->AddPage('L');
	
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
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

			$this->db->select('pso.id, ps.nama, SUM(pso.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pso.contract_date >=',$start_date);
            $this->db->where('pso.contract_date <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pso.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('pod.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('psod.sales_po_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'pso.client_id = ps.id');
		$this->db->where("pso.status in ('OPEN','CLOSED')");
		$this->db->group_by('pso.client_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_sales_po pso');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat10($sups['nama'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['contract_date'] = date('d-m-Y',strtotime($row['contract_date']));
						$arr['contract_number'] = $row['contract_number'];
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['measure'] = $row['measure'];
						$arr['qty'] = $row['qty'];
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['dpp'] = number_format($row['dpp'],0,',','.');
						$arr['tax'] =  number_format($row['tax'],0,',','.');
						$arr['total'] = number_format($row['total'],0,',','.');
						$arr['status'] = $row['status'];
						
						
						$arr['nama'] = $sups['nama'];
						$jumlah_all += $row['total'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['jumlah'];
					$sups['no'] = $no;
					$sups['jumlah'] = number_format($sups['jumlah'],0,',','.');
					

					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_penjualan/002_cetak_sales_order',$data,TRUE);
	        
	        $pdf->SetTitle('BBJ - Laporan Sales Order');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-sales-order.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_penjualan_per_produk()
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

		$this->db->select('pp.id, p.nama_produk, SUM(pp.price) as all_total');	
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
		if(!empty($supplier_id)){
            $this->db->where('pp.client_id',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pod.material_id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('pp.id',$filter_material);
        }
		
		$this->db->join('penerima ps', 'pp.client_id = ps.id','left');
		$this->db->join('produk p','pp.product_id = p.id','left');
		$this->db->where('pp.status','PUBLISH');
		$this->db->group_by('p.nama_produk');
		$this->db->order_by('p.nama_produk','asc');
		$query = $this->db->get('pmm_productions pp');
		
			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat11($sups['nama_produk'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['nama'] = $row['nama'];
						$arr['measure'] = $row['measure'];
						$arr['terkirim'] = number_format($row['terkirim'],2,',','.');
						$arr['dikembalikan'] = number_format($row['dikembalikan'],2,',','.');
						$arr['terjual'] = number_format($row['terjual'],2,',','.');
						$arr['terkirim_rp'] = number_format($row['terkirim_rp'],0,',','.');
						$arr['dikembalikan_rp'] =  number_format($row['dikembalikan_rp'],0,',','.');
						$arr['terjual_rp'] = number_format($row['terjual_rp'],0,',','.');
						
						
						$arr['nama_produk'] = $sups['nama_produk'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['all_total'];
					$sups['no'] =$no;
					$sups['all_total'] = number_format($sups['all_total'],0,',','.');
					

					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_penjualan/003_cetak_penjualan_per_produk',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Penjualan Produk');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-penjualan-produk.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_daftar_tagihan()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetMargins(3, 0, 0, true);
		$pdf->AddPage('L');
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
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

		$this->db->select('ppp.client_id, ppp.nama_pelanggan as nama, SUM(ppp.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppd.penagihan_id',$purchase_order_no);
        }
		
		$this->db->group_by('ppp.nama_pelanggan');
		$this->db->order_by('ppp.nama_pelanggan','asc');
		$query = $this->db->get('pmm_penagihan_penjualan ppp');	

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat12($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['memo'] = $row['memo'];
						$arr['qty'] =  number_format($row['qty'],2,',','.');
						$arr['measure'] = $row['measure'];
						$arr['jumlah'] = number_format($row['jumlah'],0,',','.');
						$arr['ppn'] = number_format($row['ppn'],0,',','.');							
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];
						$jumlah_all += $row['total_price'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['jumlah'];
					$sups['no'] =$no;
					$sups['jumlah'] = number_format($sups['jumlah'],0,',','.');
					
					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_penjualan/004_cetak_daftar_tagihan',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Daftar Tagihan Penjualan');
	        $pdf->nsi_html($html);
	        $pdf->Output('daftar-tagihan-penjualan.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_piutang()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');
		
		$arr_data = array();
		$supplier_id = $this->input->get('client_id');
		$purchase_order_no = $this->input->get('penagihan_id');
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

		$this->db->select('ppp.client_id, ps.nama, SUM(ppp.total) as total_tagihan, SUM((select COALESCE(SUM(total),0) from pmm_pembayaran ppm where ppm.penagihan_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_penerimaan, SUM(ppp.total - (select COALESCE(SUM(total),0) from pmm_pembayaran ppm where ppm.penagihan_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_piutang');

		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppm.penagihan_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'ppp.client_id = ps.id','left');
		$this->db->where('ppp.status','OPEN');
		$this->db->group_by('ppp.client_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_penjualan ppp');
	
			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat13($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['memo'] = $row['memo'];
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');	
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');	
						$arr['piutang'] = number_format($row['piutang'],0,',','.');
						
						
						
						$arr['nama'] = $sups['nama'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_piutang'];
					$sups['no'] =$no;
					$sups['total_tagihan'] = number_format($sups['total_tagihan'],0,',','.');
					$sups['total_penerimaan'] = number_format($sups['total_penerimaan'],0,',','.');
					$sups['total_piutang'] = number_format($sups['total_piutang'],0,',','.');
					

					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_penjualan/005_cetak_piutang',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Piutang');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-piutang.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_umur_piutang()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
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
        $html = $this->load->view('laporan_penjualan/006_cetak_umur_piutang',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Cetak Umur Piutang');
        $pdf->nsi_html($html);
        $pdf->Output('cetak-umur-piutang.pdf', 'I');
	
	}
	
	public function cetak_penerimaan()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');
		
		$arr_data = array();
		$supplier_id = $this->input->get('client_id');
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
		
		
		$this->db->select('pmp.client_id, pmp.nama_pelanggan as nama, SUM(pmp.total) AS total_bayar');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pmp.tanggal_pembayaran >=',$start_date);
            $this->db->where('pmp.tanggal_pembayaran <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pmp.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pmp.penagihan_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_penagihan_penjualan ppp', 'pmp.penagihan_id = ppp.id','left');
		$this->db->group_by('pmp.client_id');
		$this->db->order_by('pmp.nama_pelanggan','asc');
		$query = $this->db->get('pmm_pembayaran pmp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat15($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_pembayaran'] =  date('d-m-Y',strtotime($row['tanggal_pembayaran']));
						$arr['nomor_transaksi'] = $row['nomor_transaksi'];
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['penerimaan'] = number_format($row['penerimaan'],0,',','.');								
						
						$arr['nama'] = $sups['nama'];
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['total_bayar'];
					$sups['no'] =$no;
					$sups['total_bayar'] = number_format($sups['total_bayar'],0,',','.');
					

					$arr_data[] = $sups;
					$no++;
					
					}	
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_penjualan/007_cetak_penerimaan',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Penerimaan Penjualan');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-penerimaan-penjualan.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_penyelesaian_penjualan()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetMargins(3, 0, 0, true);
		$pdf->AddPage('L');
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$grand_total_vol_pemesanan = 0;
		$grand_total_pemesanan = 0;
		$grand_total_vol_pengiriman = 0;
		$grand_total_pengiriman = 0;
		$grand_total_vol_tagihan = 0;
		$grand_total_tagihan = 0;
		$grand_total_vol_pembayaran = 0;
		$grand_total_pembayaran = 0;
		$grand_total_vol_piutang_pengiriman = 0;
		$grand_total_piutang_pengiriman = 0;
		$grand_total_vol_sisa_tagihan = 0;
		$grand_total_sisa_tagihan = 0;
		$grand_total_vol_akhir = 0;
		$grand_total_akhir = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
		$data['filter_date'] = $filter_date;	
		
		$this->db->select('po.id, po.client_id, p.nama');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('po.contract_date >=',$start_date);
            $this->db->where('po.contract_date <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('po.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.salesPo_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_sales_po_detail pod', 'po.id = pod.sales_po_id','left');
		$this->db->join('penerima p', 'po.client_id = p.id','left');
		$this->db->where("po.status in ('OPEN','CLOSED')");
		$this->db->group_by('po.client_id');
		$this->db->order_by('p.nama','ASC');
		$query = $this->db->get('pmm_sales_po po');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat16($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$vol_piutang_pengiriman = number_format($row['vol_pengiriman'] - $row['vol_tagihan'],2,',','.');
						$piutang_pengiriman = number_format($row['pengiriman'] - $row['tagihan'],0,',','.');
						$vol_sisa_tagihan = number_format($row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$sisa_tagihan = number_format($row['tagihan'] - $row['pembayaran'],0,',','.');
						$vol_akhir = number_format($row['vol_pengiriman'] - $row['vol_tagihan'] + $row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$akhir = number_format($row['pengiriman'] - $row['tagihan'] + $row['tagihan'] - $row['pembayaran'],0,',','.');

						$arr['no'] = $key + 1;
						$arr['contract_date'] = date('d-m-Y',strtotime($row['contract_date']));
						$arr['contract_number'] = $row['contract_number'];
						$arr['status'] = $row['status'];
						$arr['vol_pemesanan'] = number_format($row['vol_pemesanan'],2,',','.');
						$arr['pemesanan'] = number_format($row['pemesanan'],0,',','.');
						$arr['vol_pengiriman'] = number_format($row['vol_pengiriman'],2,',','.');
						$arr['pengiriman'] = number_format($row['pengiriman'],0,',','.');
						$arr['vol_tagihan'] = number_format($row['vol_tagihan'],2,',','.');
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');
						$arr['vol_pembayaran'] = number_format($row['vol_pembayaran'],2,',','.');
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');
						$arr['vol_piutang_pengiriman'] = $vol_piutang_pengiriman;
						$arr['piutang_pengiriman'] = $piutang_pengiriman;
						$arr['vol_sisa_tagihan'] = $vol_sisa_tagihan;
						$arr['sisa_tagihan'] = $sisa_tagihan;
						$arr['vol_akhir'] = $vol_akhir;
						$arr['akhir'] = $akhir;
													
						
						$arr['client_id'] = $sups['client_id'];
						$grand_total_vol_pemesanan += $row['vol_pemesanan'];
						$grand_total_pemesanan += $row['pemesanan'];
						$grand_total_vol_pengiriman += $row['vol_pengiriman'];
						$grand_total_pengiriman += $row['pengiriman'];
						$grand_total_vol_tagihan += $row['vol_tagihan'];
						$grand_total_tagihan += $row['tagihan'];
						$grand_total_vol_pembayaran += $row['vol_pembayaran'];
						$grand_total_pembayaran += $row['pembayaran'];
						$grand_total_vol_piutang_pengiriman += $row['vol_pengiriman'] - $row['vol_tagihan'];
						$grand_total_piutang_pengiriman += $row['pengiriman'] - $row['tagihan'];
						$grand_total_vol_sisa_tagihan += $row['vol_tagihan'] - $row['vol_pembayaran'];
						$grand_total_sisa_tagihan += $row['tagihan'] - $row['pembayaran'];
						$grand_total_vol_akhir += ($row['vol_pengiriman'] - $row['vol_tagihan']) + ($row['vol_tagihan'] - $row['vol_pembayaran']);
						$grand_total_akhir += ($row['pengiriman'] - $row['tagihan']) + ($row['tagihan'] - $row['pembayaran']);
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
			$data['grand_total_vol_pemesanan'] = $grand_total_vol_pemesanan;
			$data['grand_total_pemesanan'] = $grand_total_pemesanan;
			$data['grand_total_vol_pengiriman'] = $grand_total_vol_pengiriman;
			$data['grand_total_pengiriman'] = $grand_total_pengiriman;
			$data['grand_total_vol_tagihan'] = $grand_total_vol_tagihan;
			$data['grand_total_tagihan'] = $grand_total_tagihan;
			$data['grand_total_vol_pembayaran'] = $grand_total_vol_pembayaran;
			$data['grand_total_pembayaran'] = $grand_total_pembayaran;
			$data['grand_total_vol_piutang_pengiriman'] = $grand_total_vol_piutang_pengiriman;
			$data['grand_total_piutang_pengiriman'] = $grand_total_piutang_pengiriman;
			$data['grand_total_vol_sisa_tagihan'] = $grand_total_vol_sisa_tagihan;
			$data['grand_total_sisa_tagihan'] = $grand_total_sisa_tagihan;
			$data['grand_total_vol_akhir'] = $grand_total_vol_akhir;
			$data['grand_total_akhir'] = $grand_total_akhir;
	        $html = $this->load->view('laporan_penjualan/008_cetak_penyelesaian_penjualan',$data,TRUE);

	        $pdf->SetTitle('BBJ - Laporan Penyelesaian Penjualan');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-penyelesaian-penjualan.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
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
					$materials = $this->pmm_model->GetReceiptMatPrint($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material,$filter_kategori);
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
	        $html = $this->load->view('laporan_pembelian/001_cetak_penerimaan_pembelian',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Pembelian');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-pembelian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}


	public function cetak_penerimaan_pembelian_per_hari()
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

			$this->db->select('ppo.supplier_id,prm.measure as measure,ps.nama as name, prm.harga_satuan as price,SUM(prm.volume) as volume, SUM(prm.price) as total_price');
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
					$materials = $this->pmm_model->GetReceiptMatHari($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material,$filter_kategori);
				
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$arr['no'] = $key + 1;
							$arr['purchase_order_id'] = $row['purchase_order_id'] = $this->crud_global->GetField('pmm_purchase_order',array('id'=>$row['purchase_order_id']),'no_po');
							$arr['date_receipt'] = date('d-m-Y',strtotime($row['date_receipt']));
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
	        $html = $this->load->view('laporan_pembelian/001_cetak_penerimaan_pembelian_per_hari',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Pembelian');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-pembelian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function cetak_pembelian_per_produk()
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
		$total = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

		$this->db->select('p.nama_produk, prm.measure as satuan, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as total_price');
		
		if(!empty($start_date) && !empty($end_date)){
			$this->db->where('prm.date_receipt >=',$start_date);
			$this->db->where('prm.date_receipt <=',$end_date);
		}
		if(!empty($supplier_id)){
			$this->db->where('ps.nama',$supplier_id);
		}
		if(!empty($filter_kategori)){
			$this->db->where_in('ppo.kategori_id',$filter_kategori);
		}
		
		$this->db->join('pmm_purchase_order ppo', 'prm.purchase_order_id = ppo.id','left');
		$this->db->join('produk p','prm.material_id = p.id','left');
		$this->db->join('penerima ps', 'ppo.supplier_id = ps.id','left');
		$this->db->group_by('p.nama_produk');
		$this->db->order_by('p.nama_produk','asc');
		$query = $this->db->get('pmm_receipt_material prm');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat3($sups['nama_produk'],$start_date,$end_date,$filter_kategori);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama'] = $row['nama'];
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['nama_produk'] = $sups['nama_produk'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_price'];
					$sups['volume'] =number_format($sups['volume'],2,',','.');
					$sups['harga_satuan'] =number_format($sups['harga_satuan'],0,',','.');
					$sups['total_price'] =number_format($sups['total_price'],0,',','.');
					$sups['no'] =$no;
					

					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_pembelian/003_cetak_pembelian_per_produk',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Pembelian Produk');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-pembelian-produk.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_pesanan_pembelian()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetMargins(3, 0, 0, true);
		$pdf->AddPage('L');
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
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

		$this->db->select('ps.nama, ppo.supplier_id, SUM(ppo.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppo.date_po >=',$start_date);
            $this->db->where('ppo.date_po <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('pod.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pod.purchase_order_id',$purchase_order_no);
        }
	
		$this->db->join('penerima ps', 'ppo.supplier_id = ps.id');
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_purchase_order ppo');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat2($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['no_po'] = $row['no_po'];
						$arr['date_po'] = date('d-m-Y',strtotime($row['date_po']));
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['ppn'] = number_format($row['ppn'],0,',','.');
						$arr['jumlah'] = number_format($row['jumlah'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						$arr['status'] = $row['status'];
						
						
						$arr['nama'] = $sups['nama'];
						$jumlah_all += $row['total_price'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['jumlah'];
					$sups['no'] = $no;
					$sups['jumlah'] = number_format($sups['jumlah'],0,',','.');
					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_pembelian/002_cetak_pesanan_pembelian',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Pesanan Pembelian');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-pesanan-pembelian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_daftar_tagihan_pembelian()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetMargins(3, 0, 0, true);
		$pdf->AddPage('L');
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
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

		$this->db->select('ppp.supplier_id, ps.nama, SUM(ppp.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.supplier_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppd.penagihan_pembelian_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'ppp.supplier_id = ps.id');
		$this->db->group_by('ppp.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_pembelian ppp');

			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat4($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['memo'] = $row['memo'];
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['measure'] = $row['measure'];
						$arr['jumlah'] = number_format($row['jumlah'],0,',','.');
						$arr['ppn'] = number_format($row['ppn'],0,',','.');		
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];
						$jumlah_all += $row['total_price'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['jumlah'];
					$sups['no'] =$no;
					$sups['jumlah'] = number_format($sups['jumlah'],0,',','.');	
					
					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_pembelian/004_cetak_daftar_tagihan_pembelian',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Daftar Tagihan Pembelian');
	        $pdf->nsi_html($html);
	        $pdf->Output('daftar-tagihan-pembelian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_hutang()
	{
		$this->load->library('pdf');
	
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');
		
		$arr_data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
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

		$this->db->select('ppp.supplier_id, ps.nama, SUM(ppp.total - ppp.uang_muka) as total_tagihan, SUM((select COALESCE(SUM((total)),0) from pmm_pembayaran_penagihan_pembelian ppm where ppm.penagihan_pembelian_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_pembayaran, SUM(ppp.total - (select COALESCE(SUM((total)),0) from pmm_pembayaran_penagihan_pembelian ppm where ppm.penagihan_pembelian_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_hutang');
		$this->db->join('pmm_verifikasi_penagihan_pembelian vp', 'ppp.id = vp.penagihan_pembelian_id','left');

		if(!empty($start_date) && !empty($end_date)){
			$this->db->where('vp.tanggal_diterima_proyek >=',$start_date);
			$this->db->where('vp.tanggal_diterima_proyek <=',$end_date);
		}
		if(!empty($supplier_id)){
			$this->db->where('ppp.supplier_id',$supplier_id);
		}
		if(!empty($filter_material)){
			$this->db->where_in('ppd.material_id',$filter_material);
		}
		if(!empty($purchase_order_no)){
			$this->db->where('ppm.penagihan_pembelian_id',$purchase_order_no);
		}
		
		$this->db->join('penerima ps', 'ppp.supplier_id = ps.id','left');
		$this->db->where('ppp.status','BELUM LUNAS');
		$this->db->group_by('ppp.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_pembelian ppp');


			$no = 1;
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat5($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['tanggal_jatuh_tempo'] = date('d-m-Y',strtotime($row['tanggal_jatuh_tempo']));
						$arr['memo'] = $row['memo'];
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');	
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');	
						$arr['hutang'] = number_format($row['hutang'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_hutang'];
					$sups['no'] = $no;
					$sups['total_tagihan'] = number_format($sups['total_tagihan'],0,',','.');
					$sups['total_pembayaran'] = number_format($sups['total_pembayaran'],0,',','.');
					$sups['total_hutang'] = number_format($sups['total_hutang'],0,',','.');
					
					$arr_data[] = $sups;
					$no++;
					}
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$html = $this->load->view('laporan_pembelian/005_cetak_hutang',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Hutang');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-hutang.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_umur_hutang()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
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
        $html = $this->load->view('laporan_pembelian/006_cetak_umur_hutang',$data,TRUE);

        
        $pdf->SetTitle('BBJ - Cetak Umur Hutang');
        $pdf->nsi_html($html);
        $pdf->Output('cetak-umur-hutang.pdf', 'I');
	
	}
	
	public function cetak_daftar_pembayaran()
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
		
		
		$this->db->select('pmp.supplier_name, SUM(pmp.total) AS total_bayar');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pmp.tanggal_pembayaran >=',$start_date);
            $this->db->where('pmp.tanggal_pembayaran <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pmp.supplier_name',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pmp.penagihan_pembelian_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_penagihan_pembelian ppp', 'pmp.penagihan_pembelian_id = ppp.id','left');
		$this->db->group_by('pmp.supplier_name');
		$this->db->where('pmp.status','DISETUJUI');
		$query = $this->db->get('pmm_pembayaran_penagihan_pembelian pmp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat7($sups['supplier_name'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_pembayaran'] = date('d-m-Y',strtotime($row['tanggal_pembayaran']));
						$arr['nomor_transaksi'] = $row['nomor_transaksi'];
						$arr['tanggal_invoice'] = $row['tanggal_invoice'];
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');								
						
						$arr['supplier_name'] = $sups['supplier_name'];
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['total_bayar'];
					$sups['no'] =$no;
					$sups['total_bayar'] = number_format($sups['total_bayar'],0,',','.');
					

					$arr_data[] = $sups;
					$no++;
					
					}	
					
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
	        $html = $this->load->view('laporan_pembelian/007_cetak_daftar_pembayaran',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Daftar Pembayaran');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-daftar-pembayaran.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
	}
	
	public function cetak_penyelesaian_pembelian()
	{
		$this->load->library('pdf');

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(true);
		$pdf->SetPrintFooter(true);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('L');
		
		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$grand_total_vol_pemesanan = 0;
		$grand_total_pemesanan = 0;
		$grand_total_vol_pengiriman = 0;
		$grand_total_pengiriman = 0;
		$grand_total_vol_tagihan = 0;
		$grand_total_tagihan = 0;
		$grand_total_vol_pembayaran = 0;
		$grand_total_pembayaran = 0;
		$grand_total_vol_hutang_penerimaan = 0;
		$grand_total_hutang_penerimaan = 0;
		$grand_total_vol_sisa_tagihan = 0;
		$grand_total_sisa_tagihan = 0;
		$grand_total_vol_akhir = 0;
		$grand_total_akhir = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));
			
		$data['filter_date'] = $filter_date;	
		
		$this->db->select('po.id, po.supplier_id, p.nama');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('po.date_po >=',$start_date);
            $this->db->where('po.date_po <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('p.nama',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.purchase_order_id',$purchase_order_no);
        }
		
		$this->db->join('penerima p', 'p.id = po.supplier_id','left');
		$this->db->where("po.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('po.supplier_id');
		$this->db->order_by('p.nama','ASC');
		$query = $this->db->get('pmm_purchase_order po');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat9($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$vol_hutang_penerimaan = number_format($row['vol_pengiriman'] - $row['vol_tagihan'],2,',','.');
						$hutang_penerimaan = number_format($row['pengiriman'] - $row['tagihan'],0,',','.');
						$vol_sisa_tagihan = number_format($row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$sisa_tagihan = number_format($row['tagihan'] - $row['pembayaran'],0,',','.');
						$vol_akhir = number_format($row['vol_pengiriman'] - $row['vol_tagihan'] + $row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$akhir = number_format($row['pengiriman'] - $row['tagihan'] + $row['tagihan'] - $row['pembayaran'],0,',','.');
						
						$arr['no'] = $key + 1;
						$arr['date_po'] = date('d-m-Y',strtotime($row['date_po']));
						$arr['no_po'] = $row['no_po'];
						$arr['status'] = $row['status'];
						$arr['vol_pemesanan'] = number_format($row['vol_pemesanan'],2,',','.');
						$arr['pemesanan'] = number_format($row['pemesanan'],0,',','.');
						$arr['vol_pengiriman'] = number_format($row['vol_pengiriman'],2,',','.');
						$arr['pengiriman'] = number_format($row['pengiriman'],0,',','.');
						$arr['vol_tagihan'] = number_format($row['vol_tagihan'],2,',','.');
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');
						$arr['vol_pembayaran'] = number_format($row['vol_pembayaran'],2,',','.');
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');
						$arr['vol_hutang_penerimaan'] = $vol_hutang_penerimaan;
						$arr['hutang_penerimaan'] = $hutang_penerimaan;
						$arr['vol_sisa_tagihan'] = $vol_sisa_tagihan;
						$arr['sisa_tagihan'] = $sisa_tagihan;
						$arr['vol_akhir'] = $vol_akhir;
						$arr['akhir'] = $akhir;
													
						
						$arr['supplier_id'] = $sups['supplier_id'];
						$grand_total_vol_pemesanan += $row['vol_pemesanan'];
						$grand_total_pemesanan += $row['pemesanan'];
						$grand_total_vol_pengiriman += $row['vol_pengiriman'];
						$grand_total_pengiriman += $row['pengiriman'];
						$grand_total_vol_tagihan += $row['vol_tagihan'];
						$grand_total_tagihan += $row['tagihan'];
						$grand_total_vol_pembayaran += $row['vol_pembayaran'];
						$grand_total_pembayaran += $row['pembayaran'];
						$grand_total_vol_hutang_penerimaan += $row['vol_pengiriman'] - $row['vol_tagihan'];
						$grand_total_hutang_penerimaan += $row['pengiriman'] - $row['tagihan'];
						$grand_total_vol_sisa_tagihan += $row['vol_tagihan'] - $row['vol_pembayaran'];
						$grand_total_sisa_tagihan += $row['tagihan'] - $row['pembayaran'];
						$grand_total_vol_akhir += ($row['vol_pengiriman'] - $row['vol_tagihan']) + ($row['vol_tagihan'] - $row['vol_pembayaran']);
						$grand_total_akhir += ($row['pengiriman'] - $row['tagihan']) + ($row['tagihan'] - $row['pembayaran']); 

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
			$data['grand_total_vol_pemesanan'] = $grand_total_vol_pemesanan;
			$data['grand_total_pemesanan'] = $grand_total_pemesanan;
			$data['grand_total_vol_pengiriman'] = $grand_total_vol_pengiriman;
			$data['grand_total_pengiriman'] = $grand_total_pengiriman;
			$data['grand_total_vol_tagihan'] = $grand_total_vol_tagihan;
			$data['grand_total_tagihan'] = $grand_total_tagihan;
			$data['grand_total_vol_pembayaran'] = $grand_total_vol_pembayaran;
			$data['grand_total_pembayaran'] = $grand_total_pembayaran;
			$data['grand_total_vol_hutang_penerimaan'] = $grand_total_vol_hutang_penerimaan;
			$data['grand_total_hutang_penerimaan'] = $grand_total_hutang_penerimaan;
			$data['grand_total_vol_sisa_tagihan'] = $grand_total_vol_sisa_tagihan;
			$data['grand_total_sisa_tagihan'] = $grand_total_sisa_tagihan;
			$data['grand_total_vol_akhir'] = $grand_total_vol_akhir;
			$data['grand_total_akhir'] = $grand_total_akhir;
	        $html = $this->load->view('laporan_pembelian/008_cetak_penyelesaian_pembelian',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Penyelesaian Pembelian');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-penyelesaian-pembelian.pdf', 'I');
	        
		}else {
			echo 'Please Filter Date First';
		}
	
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
	        $html = $this->load->view('laporan_produksi/cetak_laporan_evaluasi_produksi',$data,TRUE);

	        
	        $pdf->SetTitle('BBJ - Laporan Evaluasi Produksi');
	        $pdf->nsi_html($html);
	        $pdf->Output('laporan-evaluasi-produksi.pdf', 'I');
	        
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
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('laporan_laba_rugi/laporan_laba_rugi_print',$data,TRUE);

        
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
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('laporan_produksi/cetak_beban_pokok_produksi',$data,TRUE);

        
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
        $html = $this->load->view('laporan_produksi/cetak_pergerakan_bahan_baku',$data,TRUE);

        
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
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('laporan_produksi/cetak_pergerakan_bahan_baku_penyesuaian',$data,TRUE);

        
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
        $html = $this->load->view('laporan_produksi/cetak_pergerakan_bahan_jadi',$data,TRUE);

        
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
        $html = $this->load->view('laporan_produksi/cetak_pergerakan_bahan_jadi_penyesuaian',$data,TRUE);

        
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
			$arr_filter_date = explode(' - ', $arr_date);
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('laporan_produksi/cetak_evaluasi_nilai_persediaan',$data,TRUE);

        
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
        $html = $this->load->view('laporan_laba_rugi/cetak_biaya_umum_administratif',$data,TRUE);

        
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
        $html = $this->load->view('laporan_laba_rugi/cetak_biaya_lainnya',$data,TRUE);

        
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
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
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
	

}