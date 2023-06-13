<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rap extends Secure_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm/pmm_model','admin/Templates','pmm/pmm_finance','produk/m_produk'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
    }

	public function table_rap()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('r.tanggal_rap >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('r.tanggal_rap <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('r.*');
		$this->db->order_by('r.tanggal_rap','desc');	
		$query = $this->db->get('rap r');
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['tanggal_rap'] = date('d F Y',strtotime($row['tanggal_rap']));
				$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['print'] = '<a href="'.site_url().'rap/cetak_rap/'.$row['id'].'" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> </a>';
				
				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 11){
				$row['edit'] = '<a href="'.site_url().'rap/sunting_rap/'.$row['id'].'" class="btn btn-warning"><i class="fa fa-edit"></i> </a>';
				}else {
					$row['edit'] = '-';
				}

				$data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }
	
	public function form_rap()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['measures'] = $this->db->select('*')->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
			$data['boulder'] = $this->pmm_model->getMatByPenawaranBoulder();
			$data['bbm'] = $this->pmm_model->getMatByPenawaranBBM();
			$this->load->view('rap/form_rap', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_rap()
	{
		$jobs_type = $this->input->post('jobs_type');
		$tanggal_rap = $this->input->post('tanggal_rap');

		$penawaran_id_boulder = $this->input->post('penawaran_id_boulder');
		$vol_boulder =  str_replace('.', '', $this->input->post('vol_boulder'));
		$vol_boulder =  str_replace(',', '.', $vol_boulder);
		$price_boulder = str_replace('.', '', $this->input->post('price_boulder'));
		$supplier_id_boulder = $this->input->post('supplier_id_boulder');
		$measure_boulder = $this->input->post('measure_boulder');
		$tax_id_boulder = $this->input->post('tax_id_boulder');
		$pajak_id_boulder = $this->input->post('pajak_id_boulder');
		$berat_isi_boulder =  str_replace('.', '', $this->input->post('berat_isi_boulder'));
		$berat_isi_boulder =  str_replace(',', '.', $berat_isi_boulder);

		$penawaran_id_bbm_solar = $this->input->post('penawaran_id_bbm_solar');
		$vol_bbm_solar =  str_replace('.', '', $this->input->post('vol_bbm_solar'));
		$vol_bbm_solar =  str_replace(',', '.', $vol_bbm_solar);
		$price_bbm_solar = str_replace('.', '', $this->input->post('price_bbm_solar'));
		$supplier_id_bbm_solar = $this->input->post('supplier_id_bbm_solar');
		$measure_bbm_solar = $this->input->post('measure_bbm_solar');
		$tax_id_bbm_solar = $this->input->post('tax_id_bbm_solar');
		$pajak_id_bbm_solar = $this->input->post('pajak_id_bbm_solar');
		
		$overhead = str_replace('.', '', $this->input->post('overhead'));
		$laba = str_replace('.', '', $this->input->post('laba'));

		$kapasitas_alat_sc =  str_replace('.', '', $this->input->post('kapasitas_alat_sc'));
		$kapasitas_alat_sc =  str_replace(',', '.', $kapasitas_alat_sc);
		$efisiensi_alat_sc =  str_replace('.', '', $this->input->post('efisiensi_alat_sc'));
		$efisiensi_alat_sc =  str_replace(',', '.', $efisiensi_alat_sc);
		$berat_isi_batu_pecah =  str_replace('.', '', $this->input->post('berat_isi_batu_pecah'));
		$berat_isi_batu_pecah =  str_replace(',', '.', $berat_isi_batu_pecah);

		$kapasitas_alat_wl =  str_replace('.', '', $this->input->post('kapasitas_alat_wl'));
		$kapasitas_alat_wl =  str_replace(',', '.', $kapasitas_alat_wl);
		$efisiensi_alat_wl =  str_replace('.', '', $this->input->post('efisiensi_alat_wl'));
		$efisiensi_alat_wl =  str_replace(',', '.', $efisiensi_alat_wl);
		$waktu_siklus =  str_replace('.', '', $this->input->post('waktu_siklus'));
		$waktu_siklus =  str_replace(',', '.', $waktu_siklus);

		$memo = $this->input->post('memo');
		$attach = $this->input->post('files[]');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'tanggal_rap' => date('Y-m-d', strtotime($tanggal_rap)),	
			'jobs_type' => $jobs_type,

			'penawaran_id_boulder' => $penawaran_id_boulder,
			'supplier_id_boulder' => $supplier_id_boulder,
			'vol_boulder' => $vol_boulder,
			'price_boulder' => $price_boulder,
			'measure_boulder' => $measure_boulder,
			'tax_id_boulder' => $tax_id_boulder,
			'pajak_id_boulder' => $pajak_id_boulder,
			'berat_isi_boulder' => $berat_isi_boulder,

			'penawaran_id_bbm_solar' => $penawaran_id_bbm_solar,
			'supplier_id_bbm_solar' => $supplier_id_bbm_solar,
			'vol_bbm_solar' => $vol_bbm_solar,
			'price_bbm_solar' => $price_bbm_solar,
			'measure_bbm_solar' => $measure_bbm_solar,
			'tax_id_bbm_solar' => $tax_id_bbm_solar,
			'pajak_id_bbm_solar' => $pajak_id_bbm_solar,

			'overhead' => $overhead,
			'laba' => $laba,

			'kapasitas_alat_sc' => $kapasitas_alat_sc,
			'efisiensi_alat_sc' => $efisiensi_alat_sc,
			'berat_isi_batu_pecah' => $berat_isi_batu_pecah,

			'kapasitas_alat_wl' => $kapasitas_alat_wl,
			'efisiensi_alat_wl' => $efisiensi_alat_wl,
			'waktu_siklus' => $waktu_siklus,
			
			'status' => 'PUBLISH',
			'memo' => $memo,
			'attach' => $attach,
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('rap', $arr_insert)) {
			$rap_id = $this->db->insert_id();

			if (!file_exists('uploads/rap')) {
			    mkdir('uploads/rap', 0777, true);
			}

			$data = [];
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				if (!empty($_FILES['files']['name'][$i])) {

					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					$config['upload_path'] = 'uploads/rap';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'rap_id' => $rap_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('lampiran_rap', $data[$i]);
						
					} 
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal Membuat Analisa Harga Satuan !!');
			redirect('rap/rap');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Membuat Analisa Harga Satuan !!');
			redirect('admin/rap');
		}
	}

	public function cetak_rap($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['row'] = $this->db->get_where('rap',array('id'=>$id))->row_array();
        $html = $this->load->view('rap/cetak_rap',$data,TRUE);
        $row = $this->db->get_where('rap',array('id'=>$id))->row_array();


        
        $pdf->SetTitle($row['jobs_type']);
        $pdf->nsi_html($html);
        $pdf->Output($row['jobs_type'].'.pdf', 'I');
	}

	public function sunting_rap($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['rap'] = $this->db->select('*')->get_where('rap',array('id'=>$id))->row_array();
			$data['measures'] = $this->db->select('*')->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
			$data['boulder'] = $this->pmm_model->getMatByPenawaranBoulder();
			$data['bbm'] = $this->pmm_model->getMatByPenawaranBBM();
			$data['lampiran'] = $this->db->get_where('lampiran_rap', ['rap_id' => $id])->result_array();
			$this->load->view('rap/sunting_rap', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_sunting_rap()
	{
		$id = $this->input->post('id');
		$jobs_type = $this->input->post('jobs_type');
		$tanggal_rap = $this->input->post('tanggal_rap');

		$penawaran_id_boulder = $this->input->post('penawaran_id_boulder');
		$vol_boulder =  str_replace('.', '', $this->input->post('vol_boulder'));
		$vol_boulder =  str_replace(',', '.', $vol_boulder);
		$price_boulder = str_replace('.', '', $this->input->post('price_boulder'));
		$supplier_id_boulder = $this->input->post('supplier_id_boulder');
		$measure_boulder = $this->input->post('measure_boulder');
		$tax_id_boulder = $this->input->post('tax_id_boulder');
		$pajak_id_boulder = $this->input->post('pajak_id_boulder');
		$berat_isi_boulder =  str_replace('.', '', $this->input->post('berat_isi_boulder'));
		$berat_isi_boulder =  str_replace(',', '.', $berat_isi_boulder);

		$penawaran_id_bbm_solar = $this->input->post('penawaran_id_bbm_solar');
		$vol_bbm_solar =  str_replace('.', '', $this->input->post('vol_bbm_solar'));
		$vol_bbm_solar =  str_replace(',', '.', $vol_bbm_solar);
		$price_bbm_solar = str_replace('.', '', $this->input->post('price_bbm_solar'));
		$supplier_id_bbm_solar = $this->input->post('supplier_id_bbm_solar');
		$measure_bbm_solar = $this->input->post('measure_bbm_solar');
		$tax_id_bbm_solar = $this->input->post('tax_id_bbm_solar');
		$pajak_id_bbm_solar = $this->input->post('pajak_id_bbm_solar');
		
		$overhead = str_replace('.', '', $this->input->post('overhead'));
		$laba = str_replace('.', '', $this->input->post('laba'));

		$kapasitas_alat_sc =  str_replace('.', '', $this->input->post('kapasitas_alat_sc'));
		$kapasitas_alat_sc =  str_replace(',', '.', $kapasitas_alat_sc);
		$efisiensi_alat_sc =  str_replace('.', '', $this->input->post('efisiensi_alat_sc'));
		$efisiensi_alat_sc =  str_replace(',', '.', $efisiensi_alat_sc);
		$berat_isi_batu_pecah =  str_replace('.', '', $this->input->post('berat_isi_batu_pecah'));
		$berat_isi_batu_pecah =  str_replace(',', '.', $berat_isi_batu_pecah);

		$kapasitas_alat_wl =  str_replace('.', '', $this->input->post('kapasitas_alat_wl'));
		$kapasitas_alat_wl =  str_replace(',', '.', $kapasitas_alat_wl);
		$efisiensi_alat_wl =  str_replace('.', '', $this->input->post('efisiensi_alat_wl'));
		$efisiensi_alat_wl =  str_replace(',', '.', $efisiensi_alat_wl);
		$waktu_siklus =  str_replace('.', '', $this->input->post('waktu_siklus'));
		$waktu_siklus =  str_replace(',', '.', $waktu_siklus);
		$memo = $this->input->post('memo');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well

		$arr_update = array(
			'tanggal_rap' => date('Y-m-d', strtotime($tanggal_rap)),	
			'jobs_type' => $jobs_type,

			'penawaran_id_boulder' => $penawaran_id_boulder,
			'supplier_id_boulder' => $supplier_id_boulder,
			'vol_boulder' => $vol_boulder,
			'price_boulder' => $price_boulder,
			'measure_boulder' => $measure_boulder,
			'tax_id_boulder' => $tax_id_boulder,
			'pajak_id_boulder' => $pajak_id_boulder,
			'berat_isi_boulder' => $berat_isi_boulder,

			'penawaran_id_bbm_solar' => $penawaran_id_bbm_solar,
			'supplier_id_bbm_solar' => $supplier_id_bbm_solar,
			'vol_bbm_solar' => $vol_bbm_solar,
			'price_bbm_solar' => $price_bbm_solar,
			'measure_bbm_solar' => $measure_bbm_solar,
			'tax_id_bbm_solar' => $tax_id_bbm_solar,
			'pajak_id_bbm_solar' => $pajak_id_bbm_solar,

			'overhead' => $overhead,
			'laba' => $laba,

			'kapasitas_alat_sc' => $kapasitas_alat_sc,
			'efisiensi_alat_sc' => $efisiensi_alat_sc,
			'berat_isi_batu_pecah' => $berat_isi_batu_pecah,

			'kapasitas_alat_wl' => $kapasitas_alat_wl,
			'efisiensi_alat_wl' => $efisiensi_alat_wl,
			'waktu_siklus' => $waktu_siklus,
			
			'memo' => $memo,
			'updated_by' => $this->session->userdata('admin_id'),
			'updated_on' => date('Y-m-d H:i:s')
			);

			$this->db->where('id', $id);
			if ($this->db->update('rap', $arr_update)) {
				
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error', 'Gagal Edit Analisa Harga Satuan !!');
				redirect('rap/rap');
			} else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success', 'Berhasil Edit Analisa Harga Satuan !!');
				redirect('admin/rap');
			}
	}

	public function form_penyusutan()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->order_by('nama_produk','asc')->get_where('produk', array('status' => 'PUBLISH', 'asset' => 1))->result_array();
			$this->load->view('rap/form_penyusutan', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_penyusutan()
	{
		$tanggal_penyusutan = $this->input->post('tanggal_penyusutan');
		$produk = $this->input->post('produk');
		$nilai_penyusutan = str_replace('.', '', $this->input->post('nilai_penyusutan'));
		$memo = $this->input->post('memo');
		$attach = $this->input->post('files[]');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'tanggal_penyusutan' => date('Y-m-d', strtotime($tanggal_penyusutan)),	
			'produk' => $produk,
			'nilai_penyusutan' => $nilai_penyusutan,
			'status' => 'PUBLISH',
			'memo' => $memo,
			'attach' => $attach,
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('penyusutan', $arr_insert)) {
			$penyusutan_id = $this->db->insert_id();

			if (!file_exists('uploads/penyusutan')) {
			    mkdir('uploads/penyusutan', 0777, true);
			}

			$data = [];
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				if (!empty($_FILES['files']['name'][$i])) {

					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					$config['upload_path'] = 'uploads/penyusutan';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'penyusutan_id' => $penyusutan_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('lampiran_penyusutan', $data[$i]);
						
					} 
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal Membuat Input Penyusutan !!');
			redirect('rap/rap');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Input Penyusutan !!');
			redirect('admin/rap');
		}
	}

	public function table_penyusutan()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('r.tanggal_penyusutan >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('r.tanggal_penyusutan <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('r.*');
		$this->db->order_by('r.tanggal_penyusutan','desc');	
		$query = $this->db->get('penyusutan r');
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['tanggal_penyusutan'] = date('d F Y',strtotime($row['tanggal_penyusutan']));
				$row['produk'] = $this->crud_global->GetField('produk',array('id'=>$row['produk']),'nama_produk');
				$row['nilai_penyusutan'] = number_format($row['nilai_penyusutan'],0,',','.');
				$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['print'] = '<a href="'.site_url().'rap/cetak_penyusutan/'.$row['id'].'" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> </a>';
			
				$data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function cetak_penyusutan($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['row'] = $this->db->get_where('penyusutan',array('id'=>$id))->row_array();
        $html = $this->load->view('rap/cetak_penyusutan',$data,TRUE);
        $row = $this->db->get_where('penyusutan',array('id'=>$id))->row_array();


        
        $pdf->SetTitle('Penyusutan');
        $pdf->nsi_html($html);
        $pdf->Output('penyusutan'.'.pdf', 'I');
	}

}
?>