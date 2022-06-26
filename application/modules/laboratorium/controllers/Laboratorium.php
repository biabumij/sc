<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laboratorium extends Secure_Controller {

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
	
	public function form_jmd()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH', 'aggregat' => 1))->result_array();
			$data['kode'] = $this->db->select('*')->get_where('produk_kode', array('status' => 'PUBLISH'))->result_array();
			$data['measures'] = $this->db->select('*')->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
			$this->load->view('laboratorium/form_jmd', $data);
		} else {
			redirect('admin');
		}
	}
	
	public function submit_jmd()
	{
		$tanggal_jmd = $this->input->post('tanggal_jmd');
		$mutu_beton = $this->input->post('mutu_beton');
		$slump = $this->input->post('slump');
		$nama_komposisi = $this->input->post('nama_komposisi');
		$nomor_komposisi = $this->input->post('nomor_komposisi');
		$beton_1 = $this->input->post('beton_1');
		$kode_1 = $this->input->post('kode_1');
		$volume_1 = $this->input->post('volume_1');
		$measure_1 = $this->input->post('measure_1');
		$semen_1 = $this->input->post('semen_1');
		$kode_2 = $this->input->post('kode_2');
		$volume_2 = $this->input->post('volume_2');
		$measure_2 = $this->input->post('measure_2');
		$pasir_1 = $this->input->post('pasir_1');
		$kode_3 = $this->input->post('kode_3');
		$volume_3 = $this->input->post('volume_3');
		$measure_3 = $this->input->post('measure_3');
		$aggregat_kasar_1 = $this->input->post('aggregat_kasar_1');
		$kode_4 = $this->input->post('kode_4');
		$volume_4 = $this->input->post('volume_4');
		$measure_4 = $this->input->post('measure_4');
		$faktor_kehilangan_1 = $this->input->post('faktor_kehilangan_1');
		$kode_5 = $this->input->post('kode_5');
		$volume_5 = $this->input->post('volume_5');
		$measure_5 = $this->input->post('measure_5');
		
		$pasir_2 = $this->input->post('pasir_2');
		$kode_6 = $this->input->post('kode_6');
		$volume_6 = $this->input->post('volume_6');
		$measure_6 = $this->input->post('measure_6');
		$aggregat_kasar_2 = $this->input->post('aggregat_kasar_2');
		$kode_7 = $this->input->post('kode_7');
		$volume_7 = $this->input->post('volume_7');
		$measure_7 = $this->input->post('measure_7');
		
		$semen_2 = $this->input->post('semen_2');
		$kode_8 = $this->input->post('kode_8');
		$volume_8 = $this->input->post('volume_8');
		$measure_8 = $this->input->post('measure_8');
		$pasir_3 = $this->input->post('pasir_3');
		$kode_9 = $this->input->post('kode_9');
		$volume_9 = $this->input->post('volume_9');
		$measure_9 = $this->input->post('measure_9');
		$batu_split_12 = $this->input->post('batu_split_12');
		$kode_10 = $this->input->post('kode_10');
		$volume_10 = $this->input->post('volume_10');
		$measure_10 = $this->input->post('measure_10');
		$batu_split_23 = $this->input->post('batu_split_23');
		$kode_11 = $this->input->post('kode_11');
		$volume_11 = $this->input->post('volume_11');
		$measure_11 = $this->input->post('measure_11');
		$additon = $this->input->post('additon');
		$kode_12 = $this->input->post('kode_12');
		$volume_12 = $this->input->post('volume_12');
		$measure_12 = $this->input->post('measure_12');
		
		$total = $volume_8 + $volume_9 + $volume_10 + $volume_11 + $volume_12;
		$koef_1 = $volume_8 / $total * 100;
		$koef_2 = $volume_9 / $total * 100;
		$koef_3 = $volume_10 / $total * 100;
		$koef_4 = $volume_11 / $total * 100;
		$koef_5 = $volume_12 / $total * 100;
		$koef_a = ($volume_1 * $volume_5) * $koef_1 / 100;
		$koef_b = ($volume_1 * $volume_5) * ($koef_2 / 100) / 1.60;
		$koef_c = ($volume_1 * $volume_5) * $koef_3 / 100;
		$koef_d = ($volume_1 * $volume_5) * $koef_4 / 100;
		$koef_e = ($volume_1 * $volume_5) * ($koef_5 / 100) * 1000;
		
		
		$memo = $this->input->post('memo');
		$attach = $this->input->post('files[]');


		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'tanggal_jmd' => date('Y-m-d', strtotime($tanggal_jmd)),
			'mutu_beton' => $mutu_beton,
			'slump' => $slump,
			'nama_komposisi' => $nama_komposisi,	
			'nomor_komposisi' => $nomor_komposisi,
			'beton_1' => $beton_1,
			'kode_1' => $kode_1,
			'volume_1' => $volume_1,
			'measure_1' => $measure_1,
			'semen_1' => $semen_1,
			'kode_2' => $kode_2,
			'volume_2' => $volume_2,
			'measure_2' => $measure_2,		
			'pasir_1' => $pasir_1,
			'kode_3' => $kode_3,
			'volume_3' => $volume_3,
			'measure_3' => $measure_3,
			'aggregat_kasar_1' => $aggregat_kasar_1,
			'kode_4' => $kode_4,
			'volume_4' => $volume_4,
			'measure_4' => $measure_4,
			'faktor_kehilangan_1' => $faktor_kehilangan_1,
			'kode_5' => $kode_5,
			'volume_5' => $volume_5,
			'measure_5' => $measure_5,
			
			'pasir_2' => $pasir_2,
			'kode_6' => $kode_6,
			'volume_6' => $volume_6,
			'measure_6' => $measure_6,	
			'aggregat_kasar_2' => $aggregat_kasar_2,
			'kode_7' => $kode_7,
			'volume_7' => $volume_7,
			'measure_7' => $measure_7,	
			
			'semen_2' => $semen_2,
			'kode_8' => $kode_8,
			'volume_8' => $volume_8,
			'measure_8' => $measure_8,
			'pasir_3' => $pasir_3,
			'kode_9' => $kode_9,
			'volume_9' => $volume_9,
			'measure_9' => $measure_9,		
			'batu_split_12' => $batu_split_12,
			'kode_10' => $kode_10,
			'volume_10' => $volume_10,
			'measure_10' => $measure_10,
			'batu_split_23' => $batu_split_23,
			'kode_11' => $kode_11,
			'volume_11' => $volume_11,
			'measure_11' => $measure_11,
			'additon' => $additon,
			'kode_12' => $kode_12,
			'volume_12' => $volume_12,
			'measure_12' => $measure_12,
			
			'koef_1' => $koef_1,
			'koef_2' => $koef_2,
			'koef_3' => $koef_3,
			'koef_4' => $koef_4,
			'koef_5' => $koef_5,
			'koef_a' => $koef_a,
			'koef_b' => $koef_b,
			'koef_c' => $koef_c,
			'koef_d' => $koef_d,
			'koef_e' => $koef_e,
			
			
			'memo' => $memo,
			'attach' => $attach,
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('pmm_jmd', $arr_insert)) {
			$jmd_id = $this->db->insert_id();

			$data = [];
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				if (!empty($_FILES['files']['name'][$i])) {

					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					$config['upload_path'] = 'uploads/jmd';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'jmd_id' => $jmd_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('pmm_lampiran_jmd', $data[$i]);
					}
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal membuat Job Mix Design !!');
			redirect('admin/laboratorium');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil membuat Job Mix Design !!');
			redirect('admin/laboratorium');
		}
	}
	
	public function table_jmd()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		$filter_product = $this->input->post('filter_product');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('kb.date_kalibrasi >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('kb.date_kalibrasi <=',date('Y-m-d',strtotime($arr_date[1])));
		}
		if(!empty($filter_product)){
            $this->db->where('jmd.mutu_beton',$filter_product);
        }
        $this->db->select('jmd.id, jmd.tanggal_jmd, jmd.mutu_beton, jmd.slump, jmd.nama_komposisi, jmd.nomor_komposisi, lk.lampiran, jmd.memo, jmd.status');
		$this->db->join('pmm_lampiran_jmd lk', 'jmd.id = lk.jmd_id','left');		
		$this->db->order_by('jmd.tanggal_jmd','desc');
		$this->db->order_by('jmd.created_on','desc');			
		$query = $this->db->get('pmm_jmd jmd');
		//file_put_contents("D:\\table_jmd.txt", $this->db->last_query());
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['tanggal_jmd'] = date('d-m-Y',strtotime($row['tanggal_jmd']));
				$row['mutu_beton'] = $this->crud_global->GetField('produk',array('id'=>$row['mutu_beton']),'nama_produk');
                $row['slump'] = $row['slump'];
				$row['nama_komposisi'] = $row['nama_komposisi'];
				$row['nomor_komposisi'] = "<a href=" . base_url('laboratorium/data_jmd/' . $row["id"]) . ">" . $row["nomor_komposisi"] . "</a>";
				$row['lampiran'] = '<a href="' . base_url('uploads/jmd/' . $row['lampiran']) .'" target="_blank">' . $row['lampiran'] . '</a>';
				$row['memo'] = $row['memo'];
				$row['status'] = $this->pmm_model->GetStatus4($row['status']);           
                
                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }
	
	public function data_jmd($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['tes'] = '';
			$data['jmd'] = $this->db->get_where("pmm_jmd", ["id" => $id])->row_array();
			$data['lampiran'] = $this->db->get_where("pmm_lampiran_jmd", ["jmd_id" => $id])->result_array();
			$this->load->view('laboratorium/data_jmd', $data);
			//file_put_contents("D:\\data_jmd.txt", $this->db->last_query());
		} else {
			redirect('admin');
		}
	}

    public function hapus_jmd($id)
    {
        $this->db->trans_start(); # Starting Transaction


        $this->db->delete('pmm_jmd', array('id' => $id));

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error', 'Gagal Menghapus Job Mix Design');
            redirect('admin/laboratorium');
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success', 'Berhasil Menghapus Job Mix Design');
            redirect("admin/laboratorium");
        }
    }
	
	public function cetak_jmd($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['jmd'] = $this->db->get_where('pmm_jmd',array('id'=>$id))->row_array();
        $html = $this->load->view('laboratorium/cetak_jmd',$data,TRUE);
        $jmd = $this->db->get_where('pmm_jmd',array('id'=>$id))->row_array();


        
        $pdf->SetTitle($jmd['nomor_komposisi']);
        $pdf->nsi_html($html);
        $pdf->Output($jmd['nomor_komposisi'].'.pdf', 'I');
	}
	

}
?>