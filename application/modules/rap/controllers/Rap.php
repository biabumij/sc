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
	
	public function form_rap()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'aggregat' => 1))->result_array();
			$data['kode'] = $this->db->select('*')->get_where('produk_kode', array('status' => 'PUBLISH'))->result_array();
			$data['measures'] = $this->db->select('*')->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
			$this->load->view('rap/form_rap', $data);
		} else {
			redirect('admin');
		}
	}
	
	public function submit_rap()
	{
		$mutu_beton = $this->input->post('mutu_beton');
		$tanggal_rap = $this->input->post('tanggal_rap');
		$slump = $this->input->post('slump');
		$nomor_komposisi = $this->input->post('nomor_komposisi');
		$nomor_rap = $this->input->post('nomor_rap');
		
		$semen_2 = $this->input->post('semen_2');
		$measure_a = $this->input->post('measure_a');
		$komposisi_a = $this->input->post('komposisi_a');
		$rekanan_a = $this->input->post('rekanan_a');
		$nomor_penawaran_a = $this->input->post('nomor_penawaran_a');
		$harga_satuan_a = $this->input->post('harga_satuan_a');
		$jumlah_harga_a = $this->input->post('jumlah_harga_a');
		
		$pasir_3 = $this->input->post('pasir_3');
		$measure_b = $this->input->post('measure_b');
		$komposisi_b = $this->input->post('komposisi_b');
		$rekanan_b = $this->input->post('rekanan_b');
		$nomor_penawaran_b = $this->input->post('nomor_penawaran_b');
		$harga_satuan_b = $this->input->post('harga_satuan_b');
		$jumlah_harga_b = $this->input->post('jumlah_harga_b');
		
		$batu_split_12 = $this->input->post('batu_split_12');
		$measure_c = $this->input->post('measure_c');
		$komposisi_c = $this->input->post('komposisi_c');
		$rekanan_c = $this->input->post('rekanan_c');
		$nomor_penawaran_c = $this->input->post('nomor_penawaran_c');
		$harga_satuan_c = $this->input->post('harga_satuan_c');
		$jumlah_harga_c = $this->input->post('jumlah_harga_c');
		
		$batu_split_23 = $this->input->post('batu_split_23');
		$measure_d = $this->input->post('measure_d');
		$komposisi_d = $this->input->post('komposisi_d');
		$rekanan_d = $this->input->post('rekanan_d');
		$nomor_penawaran_d = $this->input->post('nomor_penawaran_d');
		$harga_satuan_d = $this->input->post('harga_satuan_d');
		$jumlah_harga_d = $this->input->post('jumlah_harga_d');
		
		$additon = $this->input->post('additon');
		$measure_e = $this->input->post('measure_e');
		$komposisi_e = $this->input->post('komposisi_e');
		$rekanan_e = $this->input->post('rekanan_e');
		$nomor_penawaran_e = $this->input->post('nomor_penawaran_e');
		$harga_satuan_e = $this->input->post('harga_satuan_e');
		$jumlah_harga_e = $this->input->post('jumlah_harga_e');


		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'mutu_beton' => $mutu_beton,
			'tanggal_rap' =>  date('Y-m-d', strtotime($tanggal_rap)),
			'slump' => $slump,	
			'nomor_komposisi' => $nomor_komposisi,
			'nomor_rap' => $nomor_rap,
			
			'semen_2' => $semen_2,
			'measure_a' => $measure_a,
			'komposisi_a' => $komposisi_a,
			'rekanan_a' => $rekanan_a,
			'nomor_penawaran_a' => $nomor_penawaran_a,
			'harga_satuan_a' => $harga_satuan_a,
			'jumlah_harga_a' => $jumlah_harga_a,
			
			'pasir_3' => $pasir_3,
			'measure_b' => $measure_b,
			'komposisi_b' => $komposisi_b,
			'rekanan_b' => $rekanan_b,
			'nomor_penawaran_b' => $nomor_penawaran_b,
			'harga_satuan_b' => $harga_satuan_b,
			'jumlah_harga_b' => $jumlah_harga_b,
			
			'batu_split_12' => $batu_split_12,
			'measure_c' => $measure_c,
			'komposisi_c' => $komposisi_c,
			'rekanan_c' => $rekanan_c,
			'nomor_penawaran_c' => $nomor_penawaran_c,
			'harga_satuan_c' => $harga_satuan_c,
			'jumlah_harga_c' => $jumlah_harga_c,
			
			'batu_split_23' => $batu_split_23,
			'measure_d' => $measure_d,
			'komposisi_d' => $komposisi_d,
			'rekanan_d' => $rekanan_d,
			'nomor_penawaran_d' => $nomor_penawaran_d,
			'harga_satuan_d' => $harga_satuan_d,
			'jumlah_harga_d' => $jumlah_harga_d,
			
			'additon' => $additon,
			'measure_e' => $measure_e,
			'komposisi_e' => $komposisi_e,
			'rekanan_e' => $rekanan_e,
			'nomor_penawaran_e' => $nomor_penawaran_e,
			'harga_satuan_e' => $harga_satuan_e,
			'jumlah_harga_e' => $jumlah_harga_e,
			
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);
		
		if ($this->db->insert('pmm_rap', $arr_insert)) {
			$rap_id = $this->db->insert_id();

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
							'rap_id' => $jmd_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('pmm_lampiran_rap', $data[$i]);
					}
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal membuat RAP !!');
			redirect('admin/rap');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil membuat RAP !!');
			redirect('admin/rap');
		}
	}
	
	public function table_rap()
	{   
        $data = array();
		$filter_product = $this->input->post('filter_product');
		if(!empty($filter_product)){
            $this->db->where('jmd.mutu_beton',$filter_product);
        }
        $this->db->select('rap.id, rap.mutu_beton, rap.slump, rap.nomor_komposisi, rap.nomor_rap, rap.status');		
		$this->db->where('status','PUBLISH');
		$this->db->order_by('rap.nomor_rap','desc');
		$this->db->order_by('rap.nomor_rap','desc');			
		$query = $this->db->get('pmm_rap rap');
		//file_put_contents("D:\\table_jmd.txt", $this->db->last_query());
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
				$row['mutu_beton'] = $this->crud_global->GetField('produk',array('id'=>$row['mutu_beton']),'nama_produk');
                $row['slump'] = $this->crud_global->GetField('pmm_jmd',array('id'=>$row['slump']),'slump');
				$row['nomor_komposisi'] = $this->crud_global->GetField('pmm_jmd',array('id'=>$row['nomor_komposisi']),'nomor_komposisi');
				$row['nomor_rap'] = "<a href=" . base_url('rap/cetak_rap/' . $row["id"]) . ">" . $row["nomor_rap"] . "</a>";
				//$row['actions'] = "<a href=" . base_url('rap/hapus_rap/' . $row["id"]) . ">" . "<button class='btn btn-danger'>Hapus</button>" . "</a>";
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				
                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }
	
	public function data_rap($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['tes'] = '';
			$data['rap'] = $this->db->get_where("pmm_rap", ["id" => $id])->row_array();
			$this->load->view('rap/data_rap', $data);
			//file_put_contents("D:\\data_jmd.txt", $this->db->last_query());
		} else {
			redirect('admin');
		}
	}

    public function delete_rap()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$data = array(
				'status' => 'DELETED',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'),
			);
			if($this->db->update('pmm_rap',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}
	
	public function cetak_rap($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['rap'] = $this->db->get_where('pmm_rap',array('id'=>$id))->row_array();
        $html = $this->load->view('rap/cetak_rap',$data,TRUE);
        $rap = $this->db->get_where('pmm_rap',array('id'=>$id))->row_array();


        
        $pdf->SetTitle($rap['nomor_rap']);
        $pdf->nsi_html($html);
        $pdf->Output($rap['nomor_rap'].'.pdf', 'I');
	}
	

}
?>