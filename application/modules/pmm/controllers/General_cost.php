<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_cost extends CI_Controller {

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


	public function table()
	{	
		$data = array();
		
		$this->db->where('status !=','DELETED');
		$filter_date = $this->input->post('filter_date');
		$filter_type = $this->input->post('filter_type');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('date >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('date <=',date('Y-m-d',strtotime($arr_date[1])));
		}

		if(!empty($filter_type)){
			$this->db->where('type',$filter_type);
		}
		$this->db->order_by('date','desc');
		$query = $this->db->get('pmm_general_cost');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['date'] = date('d F Y',strtotime($row['date']));
				$row['cost_val'] = $row['cost'];
				$row['cost'] = number_format($row['cost'],2,',','.');

				$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$date = $this->input->post('date');
		$name = $this->input->post('name');
		$cost = $this->input->post('cost');
		$notes = $this->input->post('notes');
		$status = $this->input->post('status');

		$data = array(
			'date' => date('Y-m-d',strtotime($date)),
			'name' => $name,
			'cost' => $cost,
			'notes' => $notes,
			'type' => $this->input->post('type'),
			'status' => 'PUBLISH'
		);

		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_general_cost',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_general_cost',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('*')->get_where('pmm_general_cost',array('id'=>$id))->row_array();
			$data['date'] = date('d-m-Y',strtotime($data['date']));
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
			if($this->db->update('pmm_general_cost',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}




































}