<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_materials extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model','admin/Templates'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		$this->m_admin->check_login();
	}	


	// Product

	public function manage()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$id = $this->uri->segment(4);
			$data['id'] = $id;
			$get_data = $this->db->get_where('pmm_request_materials',array('id'=>$id,'status !='=>'DELETED'))->row_array();
			//file_put_contents("D:\\manage.txt", $this->db->last_query());
			if(!empty($get_data)){
				$data['data'] = $get_data;
				$data['products'] = $this->pmm_model->SelectScheduleProduct($get_data['schedule_id']);
				// $data['materials'] = $this->pmm_model->GetMaterialsOnRequest($get_data['schedule_id']);
				$data['materials'] = $this->pmm_model->getMatByPenawaran($get_data['supplier_id']);
				$data['materials_need'] = $this->pmm_model->GetScheduleDetail($get_data['schedule_id']);
				$this->load->view('pmm/request_material_add',$data);
			}else {
				redirect('admin/pembelian');
			}
			
		}else {
			redirect('admin');
		}
	}

	public function table()
	{	
		$data = array();
		$status = $this->input->post('status');
		$schedule_id = $this->input->post('schedule_id');
		$supplier_id = $this->input->post('supplier_id');
		$w_date = $this->input->post('filter_date');

		$this->db->select('prm.*, ps.schedule_name,ps.no_spo');
		$this->db->join('pmm_schedule ps','prm.schedule_id = ps.id','left');
		$this->db->where('prm.status !=','DELETED');
		if(!empty($schedule_id)){
			$this->db->where('prm.schedule_id',$schedule_id);
		}
		if(!empty($supplier_id)){
			$this->db->where('prm.supplier_id',$supplier_id);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('request_date  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('request_date <=',date('Y-m-d',strtotime($end_date)));	
		}

		//$this->db->where('ps.status !=','DELETED');
		$this->db->order_by('request_date','DESC');
		$this->db->order_by('created_on','DESC');
		$query = $this->db->get('pmm_request_materials prm');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$request_no = "'".$row['request_no']."'";
				$row['request_no'] = '<a href="'.site_url('pmm/request_materials/get_pdf/'.$row['id']).'" target="_blank" >'.$row['request_no'].'</a>';

				$row['request_date'] = date('d/m/Y',strtotime($row['request_date']));

				$row['supplier_name'] = $this->crud_global->GetField('penerima',array('id'=>$row['supplier_id']),'nama');

				$row['schedule_name'] = $row['schedule_name'];
				$total_volume = $this->db->select('SUM(volume) as total')->get_where('pmm_request_material_details',array('request_material_id'=>$row['id']))->row_array();
				$row['volume'] = number_format($total_volume['total'],2,',','.');

				$delete = '<a href="javascript:void(0);" onclick="DeleteDataRequest('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				if($row['status'] == 'DRAFT'){
					
					$edit = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
				}else {
					$edit = false;
				}
				$row['status'] = $this->pmm_model->GetStatus($row['status']);

				$row['actions'] = '<a href="'.site_url('pmm/request_materials/manage/'.$row['id']).'" class="btn btn-info"><i class="fa fa-gears"></i> </a> '.$edit.' '.$delete;
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	public function form_process()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		
		$request_date = date('Y-m-d',strtotime($this->input->post('request_date')));
		$request_no = $this->pmm_model->GetNoRM($request_date);
		//$schedule_id = $this->input->post('schedule_id');
		
		//$week = 1;
		//$check_week = $this->pmm_model->GetStatusPP($schedule_id,$week);
		
		$check_week = 'PUBLISH';
		
		if($check_week == 'PUBLISH'){
			$data = array(
				'request_no' => $request_no,
				//'schedule_id' => $schedule_id,
				'request_date' => $request_date,
				'supplier_id' => $this->input->post('supplier_id'),
				'subject' => $this->input->post('subject'),
				'memo' => $this->input->post('memo'),
				//'week'	=> $week
	 		);

			if(!empty($id)){
				$data['updated_by'] = $this->session->userdata('admin_id');
				if($this->db->update('pmm_request_materials',$data,array('id'=>$id))){
					$output['output'] = true;
				}
			}else{
				$data['created_by'] = $this->session->userdata('admin_id');
				$data['created_on'] = date('Y-m-d H:i:s');
				$data['status'] = 'DRAFT';
				if($this->db->insert('pmm_request_materials',$data)){
					$output['output'] = true;
				}	
					
			}
		}else {
			$output['output'] = false;
			$output['asd'] = $check_week;
			$output['err'] = 'Please Check Your Approval Planning !!!';
		}
		
		// $output['no'] = $request_no;
		
		echo json_encode($output);	
	}

	public function get_materials_by_product()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		$schedule_id = $this->input->post('schedule_id');
		$query = $this->pmm_model->SelectMatByProd($schedule_id,$id);
		if(!empty($query)){
			$output['data'] = $query;
			$output['output'] = true;
		}
		

		echo json_encode($output);		
	}

	public function table_detail()
	{	
		$data = $this->pmm_model->TableDetailRequestMaterials($this->input->post('request_material_id'));
		echo json_encode(array('data'=>$data));
	}

	public function get_detail()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('pmm_request_material_details',array('id'=>$id))->row_array();


		echo json_encode(array('data'=>$data));
	}

	public function get_data()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('*')->get_where('pmm_request_materials',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}


	public function delete()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'),
			);
			if($this->db->update('pmm_request_materials',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function product_process()
	{
		$output['output'] = false;

		
		$request_material_detail_id = $this->input->post('request_material_detail_id');
		$request_material_id = $this->input->post('request_material_id');
		$material_id = $this->input->post('material_id');
		$penawaran_id = $this->input->post('penawaran_id');
		$tax_id = $this->input->post('tax_id');
		$tax = $this->input->post('tax');
		$volume = $this->input->post('volume');
		$supplier_id = $this->input->post('supplier_id');
		$measure = $this->input->post('measure_id');
		$price = $this->input->post('price');
		$penawaran_material_id = $this->input->post('penawaran_material_id');

		$check = $this->db->get_where('pmm_request_material_details',array('request_material_id'=>$request_material_id,'material_id'=>$material_id))->num_rows();

		if(empty($request_material_detail_id) && $check > 0){
			$output['output'] = false;
			$output['err'] = 'Produk Sudah Ditambahkan !!!';
		}else {

			$this->db->trans_start(); # Starting Transaction
			$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 


			$data_p = array(
				'request_material_id' => $request_material_id,
				'supplier_id' => $supplier_id,
				'material_id' => $material_id,
				'penawaran_id' => $penawaran_id,
				'tax_id' => $tax_id,
				'tax' => $tax * $volume,
				'volume' => $volume,
				'measure_id' => $measure,
				'price' => $price,
			);
			if(!empty($request_material_detail_id)){
				$data_p['updated_by'] = $this->session->userdata('admin_id');
				$this->db->update('pmm_request_material_details',$data_p,array('id'=>$request_material_detail_id));
			}else {	
				$data_p['created_on'] = date('Y-m-d H:i:s');
				$data_p['created_by'] = $this->session->userdata('admin_id');
				$this->db->insert('pmm_request_material_details',$data_p);	
			}
			
				

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$output['output'] = false;
			} 
			else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$output['output'] = true;
			}
		}
	
		
		echo json_encode($output);	
	}

	
	function process()
	{
		// if($_POST){
			$id = $this->uri->segment(4);
			$type = $this->uri->segment(5);
			$arr = array();
			if($type == 1){
				$arr = array('status'=>'APPROVED','approved_by'=>$this->session->userdata('admin_id'),'approved_on'=>date('Y-m-d H:i:s'));

				$this->session->set_flashdata('notif_success','Berhasil menyetujui Permintaan !!');
				$this->pmm_model->CreatePO($id);
				
			}else if($type == 2){
				$arr = array('status'=>'REJECTED');
				$this->session->set_flashdata('notif_success','Berhasil menolak Permintaan !!');
			}else {
				$this->session->set_flashdata('notif_success','Berhasil menambahkan Permintaan !!');
				$arr = array('status'=>'WAITING');
			}

			if($this->db->update('pmm_request_materials',$arr,array('id'=>$id))){


				redirect('admin/pembelian#chart');
			}
		// }
	}


	public function delete_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			
			if($this->db->delete('pmm_request_material_details',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	public function get_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->set_nsi_header(TRUE,'Request Materials <br />');
        // $pdf->set_header_title('Laporan');
        $pdf->AddPage('P');

        $id = $this->uri->segment(4);
		$row = $this->db->get_where('pmm_request_materials',array('id'=>$id))->row_array();

		$data['data'] = $this->pmm_model->TableDetailRequestMaterials($id);
		// $data['data_week'] = $this->pmm_model->GetScheduleProduct($id);
		$data['row'] = $row;
		$data['id'] = $id;
		$data['no_spo'] = $this->crud_global->GetField('pmm_schedule',array('id'=>$row['schedule_id']),'no_spo');
        $html = $this->load->view('pmm/request_material_pdf',$data,TRUE);

        
        $pdf->SetTitle($row['request_no']);
        $pdf->nsi_html($html);
        $pdf->Output($row['request_no'].'.pdf', 'I');
	
	}

}