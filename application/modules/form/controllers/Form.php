<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends Secure_Controller {

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
	
	public function form_perubahan_sistem()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'kategori_produk' => 1))->result_array();
			$data['nama'] = $this->db->select('*')->order_by('admin_name','asc')->get_where('tbl_admin', array('status' => 1))->result_array();
			$this->load->view('form/form_perubahan_sistem', $data);
		} else {
			redirect('admin');
		}
	}
	
	public function submit_perubahan_sistem()
	{
		$nomor =  $this->input->post('nomor');
		$nama =  $this->input->post('nama');
		$tanggal = $this->input->post('tanggal');
		$sangat_penting =  $this->input->post('sangat_penting');
		$penting =  $this->input->post('penting');
		$cukup_penting =  $this->input->post('cukup_penting');
		$perbaikan =  $this->input->post('perbaikan');
		$penambahan_fitur_baru =  $this->input->post('penambahan_fitur_baru');
		$pengurangan_fitur_lama =  $this->input->post('pengurangan_fitur_lama');
		$master_data_baru =  $this->input->post('master_data_baru');
		$penambahan_data =  $this->input->post('penambahan_data');
		$lain_lain =  $this->input->post('lain_lain');
		$deskripsi =  $this->input->post('deskripsi');
		$memo =  $this->input->post('memo');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'nomor' => $nomor,
			'nama' => $nama,
			'tanggal' =>  date('Y-m-d', strtotime($tanggal)),			
			'sangat_penting' => $sangat_penting,
			'penting' => $penting,
			'cukup_penting' => $cukup_penting,
			'perbaikan' => $perbaikan,
			'penambahan_fitur_baru' => $penambahan_fitur_baru,
			'pengurangan_fitur_lama' => $pengurangan_fitur_lama,
			'master_data_baru' => $master_data_baru,
			'penambahan_data' => $penambahan_data,
			'lain_lain' => $lain_lain,
			'deskripsi' => $deskripsi,
			'memo' => $memo,
			'unit_head' => 39,
			'status' => 'UNPUBLISH',
			'status_permintaan' => 'MENUNGGU',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);
		
		$this->db->insert('perubahan_sistem',$arr_insert);

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error','<b>Data Gagal Disimpan</b>');
			redirect('admin/form');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success','<b>Data Berhasil Disimpan</b>');
			redirect('admin/form');
		}
	}
	
	public function table_perubahan_sistem()
	{   
        $data = array();

        $this->db->select('s.*');
		$this->db->order_by('s.tanggal','desc');			
		$query = $this->db->get('perubahan_sistem s');
		
       	if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
				$row['tanggal'] = date('d F Y',strtotime($row['tanggal']));
				$row['nomor'] = $row['nomor'];
				$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['updated_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['updated_by']),'admin_name');
                $row['updated_on'] = date('d/m/Y H:i:s',strtotime($row['updated_on']));
				$row['lampiran'] = '<a href="'.base_url().'uploads/perubahan_sistem/'.$row['document_perubahan_sistem'].'" target="_blank">'.$row['document_perubahan_sistem'].'</a>';
				$row['print'] = '<a href="'.site_url().'form/cetak_perubahan_sistem/'.$row['id'].'" target="_blank" class="btn btn-info" style="border-radius:10px;"><i class="fa fa-print"></i> </a>';
				$row['actions'] = '-';
				if($this->session->userdata('admin_group_id') == 1){
					$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger" style="border-radius:10px;"><i class="fa fa-close"></i> </a>';
				}

				if ($row["status"] === "UNPUBLISH") {
                    $row['status_permintaan'] = 'Menunggu Persetujuan';
                } else if($row["status_permintaan"] == 'SEDANG DIPROSES'){
                    $row['status_permintaan'] = "Permintaan Sedang Diproses IT/Sistem";
                    if($this->session->userdata('admin_group_id') == 1){
					$proses = '<a href="javascript:void(0);" onclick="PerubahanSistemSelesai('.$row['id'].')" class="btn btn-success" style="border-radius:10px;"><i class="fa fa-check"></i> </a>';
                    $row['status_permintaan'] = $proses;
                    }
                }

				if ($row["status"] === "PUBLISH") {
                    $row['approve_ti_sistem'] = 'Permintaan Disetujui';
                } else if($row["status"] == 'UNPUBLISH'){
                    $row['approve_ti_sistem'] = 'Menunggu Persetujuan';
                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6){
					$approve = '<a href="javascript:void(0);" onclick="ApprovePerubahanSistem('.$row['id'].')" class="btn btn-success" style="border-radius:10px;"><i class="fa fa-check"></i> </a>';
                    $row['approve_ti_sistem'] = $approve;
                    }
                }

				if ($row["status"] === "REJECT") {
                    $row['status_permintaan'] = 'Permintaan Ditolak';
					$row['approve_ti_sistem'] = 'Permintaan Ditolak';
                }

				$upload_document = false;
                if($row['status'] == 'PUBLISH' || $row['status'] == 'UNPUBLISH'){
                    $edit = '<a href="javascript:void(0);" onclick="UploadDoc('.$row['id'].')" class="btn btn-primary" style="border-radius:10px;" title="Upload Document PO" ><i class="fa fa-upload"></i> </a>';
                }

				$row['document_perubahan_sistem'] = $edit;

                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function approve_ti_sistem()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->set("status", "PUBLISH");
			$this->db->set("direksi", $this->session->userdata('admin_id'));
			$this->db->set("status_permintaan", "SEDANG DIPROSES");
			$this->db->set("updated_by", $this->session->userdata('admin_id'));
			$this->db->set("updated_on", date('Y-m-d H:i:s'));
			$this->db->where("id", $id);
			$this->db->update("perubahan_sistem");
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function perubahan_sistem_selesai()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->set("status_permintaan", "SELESAI");
			$this->db->set("ti_sistem", $this->session->userdata('admin_id'));
			$this->db->set("tanggal_ti_sistem", date('Y-m-d H:i:s'));
			$this->db->set("updated_by", $this->session->userdata('admin_id'));
			$this->db->set("updated_on", date('Y-m-d H:i:s'));
			$this->db->where("id", $id);
			$this->db->update("perubahan_sistem");
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function cetak_perubahan_sistem($id){

		$this->load->library('pdf');

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['row'] = $this->db->get_where('perubahan_sistem',array('id'=>$id))->row_array();
        $html = $this->load->view('form/cetak_perubahan_sistem',$data,TRUE);
        $row = $this->db->get_where('perubahan_sistem',array('id'=>$id))->row_array();

		$pdf->SetTitle('BBJ - Perubahan Sistem');
        $pdf->nsi_html($html);
        $pdf->Output('perubahan_sistem.pdf', 'I');
	}

	public function form_document()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$file = '';
			$error_file = false;

			if (!file_exists('./uploads/perubahan_sistem/')) {
			    mkdir('./uploads/perubahan_sistem/', 0777, true);
			}
			// Upload email
			$config['upload_path']          = './uploads/perubahan_sistem/';
	        $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG|pdf';

	        $this->load->library('upload', $config);

			if($_FILES["file"]["error"] == 0) {
				if (!$this->upload->do_upload('file'))
				{
						$error = $this->upload->display_errors();
						$file = $error;
						$error_file = true;
				}else{
						$data = $this->upload->data();
						$file = $data['file_name'];
				}
			}

			if($error_file){
				$output['output'] = false;
				$output['err'] = $file;
				echo json_encode($output);
				exit();
			}

			$arr_data['document_perubahan_sistem'] = $file;

			if($this->db->update('perubahan_sistem',$arr_data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function delete_perubahan_sistem()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$file = $this->db->select('s.document_perubahan_sistem as lampiran')
			->from('perubahan_sistem s')
			->where("s.id = $id")
			->get()->row_array();

			$path = './uploads/perubahan_sistem/'.$file['lampiran'];
			chmod($path, 0777);
			unlink($path);

			$this->db->delete('perubahan_sistem',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

}
?>