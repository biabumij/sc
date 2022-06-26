<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Composition extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
	}	


	// Product

	public function table()
	{	
		$data = array();
		$tag_id = $this->input->post('tag_id');
		$this->db->select('pc.*');
		$this->db->where('pc.status !=','DELETED');
		$this->db->order_by('pc.composition_name','asc');
		$this->db->order_by('pc.created_on','desc');
		$query = $this->db->get('pmm_composition pc');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$name = "'".$row['composition_name']."'";
				$product = $this->crud_global->GetField('pmm_product',array('id'=>$row['product_id']),'product');
				$row['composition_name'] = $row['composition_name'].' <i><b>('.$product.')</b></i>';
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['actions'] = '<a href="javascript:void(0);" onclick="FormDetail('.$row['id'].','.$name.')" class="btn btn-info"><i class="fa fa-search"></i> Materials</a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function table_detail()
	{	
		$data = array();
		$this->db->select('pc.*,pm.material_name, ps.name as supplier_name');
		$this->db->where('composition_id',$this->input->post('detail_id'));
		$this->db->join('pmm_materials pm','pc.material_id = pm.id','left');
		$this->db->join('pmm_supplier ps','pc.supplier_id = ps.id','left');
		$this->db->order_by('id','asc');
		$query = $this->db->get('pmm_composition_detail pc');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$edit = "'".$row['id']."','".$row['material_id']."','".$row['formula']."','".$row['composition']."'";
				$row['material_name'] = $row['material_name'].' <small><i>('.$row['supplier_name'].')<i/></small>';
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
				$row['actions'] = ' <a href="javascript:void(0);" onclick="EditDetail('.$edit.')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteDataDetail('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$product_id = $this->input->post('product_id');
		$composition_name = $this->input->post('composition_name');
		$status = $this->input->post('status');

		$data = array(
			'product_id' => $product_id,
			'composition_name' => $composition_name,
			'status' => $status
		);

		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_composition',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_composition',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_data()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('id,composition_name,status,product_id')->get_where('pmm_composition',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}


	public function get_supplier_material()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$data = $this->pmm_model->getMatByPenawaran($id);
			$output['output'] = $data;
		}
		echo json_encode($output);
	}
	

	public function get_type()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array();
			if($id == 'BAHAN'){
				$query = $this->db->select()->get_where('pmm_materials',array('status'=>'PUBLISH'))->result_array();
				
				foreach ($query as $key => $value) {
					$data[] = array(
						'id' => $value['id'],
						'name' => $value['material_name']
					);
				}
			}else {
				$query = $this->db->get_where('pmm_tools',array('status'=>'PUBLISH'))->result_array();
				foreach ($query as $key => $value) {
					$data[] = array(
						'id' => $value['id'],
						'name' => $value['tool']
					);
				}
			}
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function form_detail()
	{
		$output['output'] = false;

		$id = $this->input->post('detail_composition_id');
		$penawaran_material_id = $this->input->post('penawaran_material_id');
		$supplier_id = $this->input->post('supplier_id');
		$composition_id = $this->input->post('composition_id');
		$material_id = $this->input->post('material_id');
		$formula = $this->input->post('formula');
		$composition = $this->input->post('composition');
		$koef = $composition;
		$measure = $this->input->post('measure_id');

		$sp = $this->db->get_where('pmm_setting_production',array('id'))->row_array();
		if($formula == 1){
			$koef = ($composition / 1000) / $sp['content_weight'] * $sp['factor_lost'];
		}else if($formula == 2){
			$koef = ($composition / 1000) * $sp['factor_lost'];
		}

		$get_cost = $this->pmm_model->getOneCostMatByPenawaran($supplier_id,$material_id);
		$cost = 0;
		$total = 0;
		if(!empty($get_cost)){
			if($get_cost['cost'] > 0){
				$cost = $get_cost['cost'];
				$total = $koef * $get_cost['cost'];
			}
		}


		$data = array(
			'composition_id' => $composition_id,
			'supplier_id' => $supplier_id,
			'material_id' => $material_id,
			'formula' => $formula,
			'composition' => $composition,
			'koef'	=> $koef,
			'measure' => $measure,
			'cost' =>$cost,
			'total' => $total
		);

		$check = $this->db->get_where('pmm_composition_detail',array('composition_id'=>$composition_id,'material_id'=>$material_id));
		if($check->num_rows() > 0 && empty($id)){
			$output['output'] = false;
			$output['err'] = 'This Data has been added !!';
		}else {
			if(!empty($id)){
				if($this->db->update('pmm_composition_detail',$data,array('id'=>$id))){
					$output['output'] = true;
				}
			}else{
				if($this->db->insert('pmm_composition_detail',$data)){
					$output['output'] = true;
				}	
					
			}
		}

		
		
		echo json_encode($output);	
	}


	function get_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$this->db->select('pc.total,pc.id,pc.koef,pc.cost,pm.material_name');
			$this->db->join('pmm_materials pm','pc.material_id = pm.id','left');
			$output['output'] = $this->db->get_where('pmm_composition_detail pc',array('pc.composition_id'=>$id))->result_array();
			$output['total'] = $this->db->select('SUM(total) as total')->get_where('pmm_composition_detail',array('composition_id'=>$id))->row_array();
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
			if($this->db->update('pmm_composition',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function delete_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			if($this->db->delete('pmm_composition_detail',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}



}



































