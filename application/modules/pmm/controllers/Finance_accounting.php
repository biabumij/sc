<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_accounting extends CI_Controller {

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


	// Request Fund
	public function manage()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$id = $this->uri->segment(4);
			$data['id'] = $id;
			$get_data = $this->db->get_where('pmm_request_funds',array('id'=>$id,'status !='=>'DELETED'))->row_array();
			if(!empty($get_data)){
				$data['data'] = $get_data;
				$this->load->view('pmm/request_fund_add',$data);
			}else {
				redirect('admin/finance_accounting');
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

		$this->db->select('*');
		$this->db->where('status !=','DELETED');
		
		if(!empty($status)){
			$this->db->where('status',$status);
		}
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_request_funds');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$request_no = "'".$row['request_fund_no']."'";
				$row['request_no'] = '<a href="'.site_url('pmm/finance_accounting/get_pdf/'.$row['id']).'" target="_blank" >'.$row['request_fund_no'].'</a>';

				$row['date'] = date('d F Y',strtotime($row['date']));

				$delete = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				if($row['status'] == 'DRAFT'){
					
					$edit = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
				}else {
					$edit = false;
				}
				$row['status'] = $this->pmm_model->GetStatus($row['status']);

				$row['actions'] = '<a href="'.site_url('pmm/finance_accounting/manage/'.$row['id']).'" class="btn btn-info"><i class="fa fa-gears"></i> </a> '.$edit.' '.$delete;
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	public function form_process()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$request_no = $this->pmm_model->GetNoRF();
		$name = $this->input->post('name');
		$date = date('Y-m-d',strtotime($this->input->post('date')));
		$week = $this->input->post('week');
		$data = array(
			'request_fund_no' => $request_no,
			'name' => $name,
			'date' => $date,
 		);

		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_request_funds',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			$data['status'] = 'DRAFT';
			if($this->db->insert('pmm_request_funds',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_funds_by_product()
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
		$data = array();
		$request_fund_id = $this->input->post('request_fund_id');
		$this->db->select('psm.*');
		$this->db->where('request_fund_id',$request_fund_id);
		$this->db->order_by('created_on','asc');
		$query = $this->db->get('pmm_request_fund_details psm');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['price']= number_format($row['price'],2,',','.');
				$row['total']= number_format($row['total'],2,',','.');
				$get_status = $this->crud_global->GetField('pmm_request_funds',array('id'=>$row['request_fund_id']),'status');
				if($get_status == 'DRAFT'){
					$row['actions'] = '<a href="javascript:void(0);" onclick="getDetail('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function total_funds()
	{	
		$data = array();
		$request_fund_id = $this->input->post('request_fund_id');
		$this->db->select('SUM(total) as total');
		$this->db->where('request_fund_id',$request_fund_id);
		$query = $this->db->get('pmm_request_fund_details psm');
		if($query->num_rows() > 0){
			$row = $query->row_array();
			$data = number_format($row['total'],2,',','.');
		}
		echo json_encode(array('data'=>$data));
	}
	

	public function get_detail()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('pmm_request_fund_details',array('id'=>$id))->row_array();


		echo json_encode(array('data'=>$data));
	}

	public function get_data()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('*')->get_where('pmm_request_funds',array('id'=>$id))->row_array();
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
			if($this->db->update('pmm_request_funds',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function request_fund_process()
	{
		$output['output'] = false;

		
		$request_fund_detail_id = $this->input->post('request_fund_detail_id');
		$request_fund_id = $this->input->post('request_fund_id');
		$name = $this->input->post('name');
		$qty = $this->input->post('qty');
		$price = $this->input->post('price');
		$total = $price * $qty;

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 


		$data_p = array(
			'request_fund_id' => $request_fund_id,
			'name' => $name,
			'qty' => $qty,
			'price' => $price,
			'total' => $total
		);
		if(!empty($request_fund_detail_id)){
			$data_p['updated_by'] = $this->session->userdata('admin_id');
			$this->db->update('pmm_request_fund_details',$data_p,array('id'=>$request_fund_detail_id));
		}else {	
			$data_p['created_on'] = date('Y-m-d H:i:s');
			$data_p['created_by'] = $this->session->userdata('admin_id');
			$this->db->insert('pmm_request_fund_details',$data_p);	
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
	
		
		echo json_encode($output);	
	}

	
	function process_fund()
	{
		// if($_POST){
			$id = $this->uri->segment(4);
			$type = $this->uri->segment(5);
			$arr = array();
			if($type == 1){
				$arr = array('status'=>'APPROVED','approved_by'=>$this->session->userdata('admin_id'),'approved_on'=>date('Y-m-d H:i:s'));
				
			}else if($type == 2){
				$arr = array('status'=>'REJECTED','updated_by'=>$this->session->userdata('admin_id'),'updated_on'=>date('Y-m-d H:i:s'));
			}else {
				$arr = array('status'=>'WAITING','updated_by'=>$this->session->userdata('admin_id'),'updated_on'=>date('Y-m-d H:i:s'));
			}

			if($this->db->update('pmm_request_funds',$arr,array('id'=>$id))){
				redirect('admin/request_funds');
			}
		// }
	}


	public function delete_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			
			if($this->db->delete('pmm_request_fund_details',array('id'=>$id))){
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
		$row = $this->db->get_where('pmm_finance_accounting',array('id'=>$id))->row_array();

		$data['data'] = $this->pmm_model->TableDetailRequestMaterials($id);
		// $data['data_week'] = $this->pmm_model->GetScheduleProduct($id);
		$data['row'] = $row;
		$data['id'] = $id;
		$data['no_spo'] = $this->crud_global->GetField('pmm_schedule',array('id'=>$row['schedule_id']),'no_spo');
        $html = $this->load->view('pmm/request_fund_pdf',$data,TRUE);

        
        $pdf->SetTitle($row['request_no']);
        $pdf->nsi_html($html);
        $pdf->Output($row['request_no'].'.pdf', 'I');
	
	}












}