<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluasi extends Secure_Controller {

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

	public function form_evaluasi_supplier()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['supplier'] = $this->db->select('*')->order_by('nama','asc')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => 1))->result_array();
			$this->load->view('evaluasi/form_evaluasi_supplier', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_evaluasi_supplier()
	{
		$tanggal = $this->input->post('tanggal');
		$supplier_id = $this->input->post('supplier_id');
		$bidang_usaha = $this->input->post('bidang_usaha');
		$alamat_supplier = $this->input->post('alamat_supplier');
		$nama_kontak = $this->input->post('nama_kontak');
		$nomor_kontak = $this->input->post('nomor_kontak');
		$email = $this->input->post('email');

		$puas_1 = str_replace(',', '.', $this->input->post('puas_1'));
		$puas_2 = str_replace(',', '.', $this->input->post('puas_2'));
		$puas_3 = str_replace(',', '.', $this->input->post('puas_3'));
		$puas_4 = str_replace(',', '.', $this->input->post('puas_4'));
		$puas_5 = str_replace(',', '.', $this->input->post('puas_5'));
		$puas_6 = str_replace(',', '.', $this->input->post('puas_6'));
		$puas_7 = str_replace(',', '.', $this->input->post('puas_7'));
		$puas_8 = str_replace(',', '.', $this->input->post('puas_8'));
		$puas_9 = str_replace(',', '.', $this->input->post('puas_9'));
		$puas_10 = str_replace(',', '.', $this->input->post('puas_10'));

		$baik_1 = str_replace(',', '.', $this->input->post('baik_1'));
		$baik_2 = str_replace(',', '.', $this->input->post('baik_2'));
		$baik_3 = str_replace(',', '.', $this->input->post('baik_3'));
		$baik_4 = str_replace(',', '.', $this->input->post('baik_4'));
		$baik_5 = str_replace(',', '.', $this->input->post('baik_5'));
		$baik_6 = str_replace(',', '.', $this->input->post('baik_6'));
		$baik_7 = str_replace(',', '.', $this->input->post('baik_7'));
		$baik_8 = str_replace(',', '.', $this->input->post('baik_8'));
		$baik_9 = str_replace(',', '.', $this->input->post('baik_9'));
		$baik_10 = str_replace(',', '.', $this->input->post('baik_10'));

		$cukup_1 = str_replace(',', '.', $this->input->post('cukup_1'));
		$cukup_2 = str_replace(',', '.', $this->input->post('cukup_2'));
		$cukup_3 = str_replace(',', '.', $this->input->post('cukup_3'));
		$cukup_4 = str_replace(',', '.', $this->input->post('cukup_4'));
		$cukup_5 = str_replace(',', '.', $this->input->post('cukup_5'));
		$cukup_6 = str_replace(',', '.', $this->input->post('cukup_6'));
		$cukup_7 = str_replace(',', '.', $this->input->post('cukup_7'));
		$cukup_8 = str_replace(',', '.', $this->input->post('cukup_8'));
		$cukup_9 = str_replace(',', '.', $this->input->post('cukup_9'));
		$cukup_10 = str_replace(',', '.', $this->input->post('cukup_10'));

		$kurang_1 = str_replace(',', '.', $this->input->post('kurang_1'));
		$kurang_2 = str_replace(',', '.', $this->input->post('kurang_2'));
		$kurang_3 = str_replace(',', '.', $this->input->post('kurang_3'));
		$kurang_4 = str_replace(',', '.', $this->input->post('kurang_4'));
		$kurang_5 = str_replace(',', '.', $this->input->post('kurang_5'));
		$kurang_6 = str_replace(',', '.', $this->input->post('kurang_6'));
		$kurang_7 = str_replace(',', '.', $this->input->post('kurang_7'));
		$kurang_8 = str_replace(',', '.', $this->input->post('kurang_8'));
		$kurang_9 = str_replace(',', '.', $this->input->post('kurang_9'));
		$kurang_10 = str_replace(',', '.', $this->input->post('kurang_10'));

		$buruk_1 = str_replace(',', '.', $this->input->post('buruk_1'));
		$buruk_2 = str_replace(',', '.', $this->input->post('buruk_2'));
		$buruk_3 = str_replace(',', '.', $this->input->post('buruk_3'));
		$buruk_4 = str_replace(',', '.', $this->input->post('buruk_4'));
		$buruk_5 = str_replace(',', '.', $this->input->post('buruk_5'));
		$buruk_6 = str_replace(',', '.', $this->input->post('buruk_6'));
		$buruk_7 = str_replace(',', '.', $this->input->post('buruk_7'));
		$buruk_8 = str_replace(',', '.', $this->input->post('buruk_8'));
		$buruk_9 = str_replace(',', '.', $this->input->post('buruk_9'));
		$buruk_10 = str_replace(',', '.', $this->input->post('buruk_10'));

		$catatan_1 = $this->input->post('catatan_1');
		$catatan_2 = $this->input->post('catatan_2');
		$catatan_3 = $this->input->post('catatan_3');
		$catatan_4 = $this->input->post('catatan_4');
		$catatan_5 = $this->input->post('catatan_5');
		$catatan_6 = $this->input->post('catatan_6');
		$catatan_7 = $this->input->post('catatan_7');
		$catatan_8 = $this->input->post('catatan_8');
		$catatan_9 = $this->input->post('catatan_9');
		$catatan_10 = $this->input->post('catatan_10');

		$memo = $this->input->post('memo');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'tanggal' => date('Y-m-d', strtotime($tanggal)),	
			'supplier_id' => $supplier_id,
			'bidang_usaha' => $bidang_usaha,
			'alamat_supplier' => $alamat_supplier,
			'nama_kontak' => $nama_kontak,
			'nomor_kontak' => $nomor_kontak,
			'email' => $email,

			'puas_1' => $puas_1,
			'baik_1' => $baik_1,
			'cukup_1' => $cukup_1,
			'kurang_1' => $kurang_1,
			'buruk_1' => $buruk_1,

			'puas_2' => $puas_2,
			'baik_2' => $baik_2,
			'cukup_2' => $cukup_2,
			'kurang_2' => $kurang_2,
			'buruk_2' => $buruk_2,

			'puas_3' => $puas_3,
			'baik_3' => $baik_3,
			'cukup_3' => $cukup_3,
			'kurang_3' => $kurang_3,
			'buruk_3' => $buruk_3,

			'puas_4' => $puas_4,
			'baik_4' => $baik_4,
			'cukup_4' => $cukup_4,
			'kurang_4' => $kurang_4,
			'buruk_4' => $buruk_4,

			'puas_5' => $puas_5,
			'baik_5' => $baik_5,
			'cukup_5' => $cukup_5,
			'kurang_5' => $kurang_5,
			'buruk_5' => $buruk_5,

			'puas_6' => $puas_6,
			'baik_6' => $baik_6,
			'cukup_6' => $cukup_6,
			'kurang_6' => $kurang_6,
			'buruk_6' => $buruk_6,

			'puas_7' => $puas_7,
			'baik_7' => $baik_7,
			'cukup_7' => $cukup_7,
			'kurang_7' => $kurang_7,
			'buruk_7' => $buruk_7,

			'puas_8' => $puas_8,
			'baik_8' => $baik_8,
			'cukup_8' => $cukup_8,
			'kurang_8' => $kurang_8,
			'buruk_8' => $buruk_8,

			'puas_9' => $puas_9,
			'baik_9' => $baik_9,
			'cukup_9' => $cukup_9,
			'kurang_9' => $kurang_9,
			'buruk_9' => $buruk_9,

			'puas_10' => $puas_10,
			'baik_10' => $baik_10,
			'cukup_10' => $cukup_10,
			'kurang_10' => $kurang_10,
			'buruk_10' => $buruk_10,

			'catatan_1' => $catatan_1,
			'catatan_2' => $catatan_2,
			'catatan_3' => $catatan_3,
			'catatan_4' => $catatan_4,
			'catatan_5' => $catatan_5,
			'catatan_6' => $catatan_6,
			'catatan_7' => $catatan_7,
			'catatan_8' => $catatan_8,
			'catatan_9' => $catatan_9,
			'catatan_10' => $catatan_10,

			'memo' => $memo,
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		$this->db->insert('evaluasi_supplier', $arr_insert);


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error','<b>Data Gagal Disimpan</b>');
			redirect('evaluasi_supplier/evaluasi_supplier');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success','<b>Data Berhasil Disimpan</b>');
			redirect('admin/evaluasi_supplier');
		}
	}
	
	public function table_evaluasi_supplier()
	{   
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('ev.tanggal >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('ev.tanggal <=',date('Y-m-d',strtotime($arr_date[1])));
		}
        $this->db->select('ev.*');
		$this->db->join('penerima ps', 'ev.supplier_id = ps.id','left');
		$this->db->order_by('ev.tanggal','desc');
		$query = $this->db->get('evaluasi_supplier ev');
		
       if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['tanggal'] = date('d F Y',strtotime($row['tanggal']));     
                $row['supplier'] = $this->crud_global->GetField('penerima',array('id'=>$row['supplier_id']),'nama');
                $row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
				$row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['status'] = $this->pmm_model->GetStatus4($row['status']);
				$row['print'] = '<a href="'.site_url().'evaluasi/cetak_evaluasi_supplier/'.$row['id'].'" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> </a>';
				if($this->session->userdata('admin_group_id') == 1){
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteEvaluasiSupplier('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function cetak_evaluasi_supplier($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['id'] = $id;
		$data['row'] = $this->db->get_where('evaluasi_supplier ev', array('ev.id' => $id))->row_array();
        $html = $this->load->view('evaluasi/cetak_evaluasi_supplier',$data,TRUE);
        
        $pdf->SetTitle('Evaluasi Supplier');
        $pdf->nsi_html($html);
		$pdf->Output('evaluasi_sipplier.pdf', 'I');
	}

	public function delete_evaluasi_supplier()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('evaluasi_supplier',array('id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

}
?>