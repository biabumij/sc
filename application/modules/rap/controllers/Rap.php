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
		$overhead = str_replace('.', '', $this->input->post('overhead'));

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

		$price_tangki = str_replace('.', '', $this->input->post('price_tangki'));
		$price_sc = str_replace('.', '', $this->input->post('price_sc'));
		$price_gns = str_replace('.', '', $this->input->post('price_gns'));
		$price_wl = str_replace('.', '', $this->input->post('price_wl'));
		$price_timbangan = str_replace('.', '', $this->input->post('price_timbangan'));

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
			'overhead' => $overhead,

			'kapasitas_alat_sc' => $kapasitas_alat_sc,
			'efisiensi_alat_sc' => $efisiensi_alat_sc,
			'berat_isi_batu_pecah' => $berat_isi_batu_pecah,

			'kapasitas_alat_wl' => $kapasitas_alat_wl,
			'efisiensi_alat_wl' => $efisiensi_alat_wl,
			'waktu_siklus' => $waktu_siklus,

			'price_tangki' => $price_tangki,
			'price_sc' => $price_sc,
			'price_gns' => $price_gns,
			'price_wl' => $price_wl,
			'price_timbangan' => $price_timbangan,
			
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
			$this->session->set_flashdata('notif_success', 'Berhasil Analisa Harga Satuan !!');
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

}
?>