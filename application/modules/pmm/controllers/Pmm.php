<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pmm extends CI_Controller {


	public $admin_group_id = false;
	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		$this->admin_group_id = $this->session->userdata('admin_group_id');
	}	


	// Product

	public function table_product()
	{	
		$data = array();
		$tag_id = $this->input->post('product_id');
		$admin_group_id = $this->session->userdata('admin_group_id');
		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);	
		}
		$this->db->where('status !=','DELETED');
		$this->db->order_by('product','asc');
		$query = $this->db->get('pmm_product');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$name = "'".$row['product']."'";
				$row['no'] = $key+1;
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$contract_price = $this->pmm_model->GetContractPrice($row['contract_price']);
				$row['contract_price'] = number_format($contract_price,2,',','.');
				$row['riel_price'] = '<a href="javascript:void(0);" onclick="GetRielPrice('.$row['id'].','.$name.')" >'.number_format($this->pmm_model->GetRielPrice($row['id']),2,',','.').'</a>';
				$row['composition'] = $this->crud_global->GetField('pmm_composition',array('id'=>$row['composition_id']),'composition_name');
				$row['tag_name'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
				
				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					$row['actions'] = '<a href="javascript:void(0);" onclick="FormDetail('.$row['id'].','.$name.')" class="btn btn-info"><i class="fa fa-search"></i> Tools</a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function detail_product()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		$this->db->where('product_id',$this->input->post('detail_id'));
		$this->db->order_by('created_on','asc');
		$query = $this->db->get('pmm_product_detail');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				if($row['type'] == 'BAHAN'){
					$name = $this->crud_global->GetField('pmm_materials',array('id'=>$row['type_id']),'material_name');
				}else {
					$name = $this->crud_global->GetField('pmm_tools',array('id'=>$row['type_id']),'tool');
				}
				$row['name'] = $name;
				
				$row['actions'] = ' <a href="javascript:void(0);" onclick="EditDetail('.$row['id'].','.$row['type_id'].','.$row['koef'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteDataDetail('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_product()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$product = $this->input->post('product');
		$description = $this->input->post('description');
		$status = $this->input->post('status');
		$contract_price = $this->input->post('contract_price');

		$composition_id = $this->input->post('composition_id');

		$data = array(
			'product' => $product,
			'composition_id' => $composition_id,
			'description' => $description,
			'status' => $status,
			'color' => $this->input->post('color'),
			'planning_color' => $this->input->post('planning_color'),
			'contract_price' => $contract_price,
			'tag_id' => $this->input->post('tag_id')
		);

		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_product',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_product',$data)){
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
			$data = $this->db->select('id,product,description,status,composition_id,contract_price,color,planning_color,tag_id')->get_where('pmm_product',array('id'=>$id))->row_array();
			$data['composition'] = $this->db->select('id,composition_name')->get_where('pmm_composition',array('status !='=>'DELETED'))->result_array();
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

	public function product_detail_form()
	{
		$output['output'] = false;

		$id = $this->input->post('detail_product_id');
		$product_id = $this->input->post('product_id');
		$type = $this->input->post('type');
		$type_id = $this->input->post('type_id');
		$koef = $this->input->post('koef');
		$status = 'PUBLISH';

		$data = array(
			'product_id' => $product_id,
			'type' => 'ALAT',
			'type_id' => $type_id,
			'koef'	=> $koef,
			'status' => $status
		);

		$check = $this->db->get_where('pmm_product_detail',array('status'=>'PUBLISH','product_id'=>$product_id,'type'=>$type,'type_id'=>$type_id));
		if($check->num_rows() > 0){
			$output['output'] = false;
			$output['err'] = 'This Data has been added !!';
		}else {
			if(!empty($id)){
				$data['updated_by'] = $this->session->userdata('admin_id');
				if($this->db->update('pmm_product_detail',$data,array('id'=>$id))){
					$output['output'] = true;
				}
			}else{
				$data['created_by'] = $this->session->userdata('admin_id');
				$data['created_on'] = date('Y-m-d H:i:s');
				if($this->db->insert('pmm_product_detail',$data)){
					$output['output'] = true;
				}	
					
			}
		}

		
		
		echo json_encode($output);	
	}


	public function delete_product()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED'
			);
			if($this->db->update('pmm_product',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function delete_product_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED'
			);
			if($this->db->update('pmm_product_detail',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function product_price()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		$product_id = $this->input->post('product_id');
		$name = $this->input->post('name');
		$price = $this->input->post('price');

		$product_name = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'product');
		$data = array(
			'name' => $name,
			'price' => $price,
			'product_id' => $product_id
		);
		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_product_price',$data,array('id'=>$id))){
				$output['output'] = true;
				$output['id'] = $product_id;
				$output['name'] = $product_name;
			}
		}else {
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_product_price',$data)){
				$output['output'] = true;
				$output['id'] = $product_id;
				$output['name'] = $product_name;
			}
		}
		
		echo json_encode($output);
	}

	public function delete_product_price()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			if($this->db->delete('pmm_product_price',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}
	


	

	// Measures
	public function table_measure()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_measures');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$name = "'".$row['measure_name']."'";

				if($this->admin_group_id == 1){
					$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function table_measure_convert()
	{	
		$data = array();
		$detail_id = $this->input->post('detail_id');

		$this->db->where('measure_id',$detail_id);
		$query = $this->db->get('pmm_measure_convert');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$measure_id = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_id']),'measure_name');
				$measure_to = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_to']),'measure_name');
				$row['name'] = $measure_id.' ---> '.$measure_to;
				
				$row['actions'] = '<a href="javascript:void(0);" onclick="EditDetail('.$row['id'].','.$row['measure_to'].','.$row['value'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteDataDetail('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$row['value'] = number_format($row['value'],6,',','.');
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function measure_convert_form()
	{
		$output['output'] = false;

		$id = $this->input->post('sub_detail_id');
		$measure_id = $this->input->post('detail_id');
		$measure_to = $this->input->post('measure_to');
		$value = $this->input->post('value');

		$data = array(
			'measure_id' => $measure_id,
			'measure_to' => $measure_to,
			'value'	=> $value,
		);

		$check = $this->db->get_where('pmm_measure_convert',array('measure_id'=>$measure_id,'measure_to'=>$measure_to));

		if($measure_id == $measure_to){
			$output['output'] = false;
			$output['err'] = 'Please Check Your Measure !!';
		}else {

			if(!empty($id)){
				$data['updated_by'] = $this->session->userdata('admin_id');
				if($this->db->update('pmm_measure_convert',$data,array('id'=>$id))){
					$output['output'] = true;
				}
			}else{
				if($check->num_rows() > 0){
					$output['output'] = false;
					$output['err'] = 'This Data has been added !!';
				}else {
					$data['created_by'] = $this->session->userdata('admin_id');
					$data['created_on'] = date('Y-m-d H:i:s');
					if($this->db->insert('pmm_measure_convert',$data)){
						$output['output'] = true;
					}	
				}
				
			}
		}
		
		echo json_encode($output);	
	}

	public function delete_measure_convert()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			if($this->db->delete('pmm_measure_convert',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}
	

	public function form_measure()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$measure_name = $this->input->post('measure_name');
		$status = $this->input->post('status');

		$data = array(
			'measure_name' => $measure_name,
			'status' => $status
		);

		if(!empty($id)){
			if($this->db->update('pmm_measures',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			if($this->db->insert('pmm_measures',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_measure()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('id,measure_name,status')->get_where('pmm_measures',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function delete_measure()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED'
			);
			if($this->db->update('pmm_measures',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	// Materials
	public function table_material()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		if(!empty($this->input->post('tag_id'))){
			$this->db->where('tag_id',$this->input->post('tag_id'));
		}
		$this->db->order_by('material_name','asc');
		$query = $this->db->get('pmm_materials');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
 				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_material()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$material_name = $this->input->post('material_name');
		$measure = $this->input->post('measure');
		$type = $this->input->post('type');
		$status = $this->input->post('status');

		$data = array(
			'material_name' => $material_name,
			'measure' => $measure,
			'type' => $type,
			'status' => $status,
		);

		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_materials',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_materials',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_material()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->get_where('pmm_materials',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function delete_material()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED'
			);
			if($this->db->update('pmm_materials',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	// Tools
	public function table_tool()
	{	
		$data = array();
		$tag_id = $this->input->post('tag_id');
		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);	
		}
		$this->db->where('status !=','DELETED');
		$this->db->order_by('tool','asc');
		$query = $this->db->get('pmm_tools');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$name = "'".$row['tool']."'";
				$total_cost = $this->db->select('SUM(cost) as total')->get_where('pmm_tool_detail',array('status'=>'PUBLISH','tool_id'=>$row['id']))->row_array();
				$row['total_cost'] = number_format($total_cost['total'],2,',','.');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_id']),'measure_name');
				$row['tag'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));

 				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					
					$row['actions'] = '<a href="javascript:void(0);" onclick="FormDetail('.$row['id'].','.$name.')" class="btn btn-info"><i class="fa fa-search"></i> Detail</a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function detail_tool()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		$this->db->where('tool_id',$this->input->post('detail_id'));
		$this->db->order_by('created_on','asc');
		$query = $this->db->get('pmm_tool_detail');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$cost = number_format($row['cost'],2,',','.');
				$name = "'".$row['name']."'";
				$cost_t = $row['cost'];
				$row['no'] = $key+1;
				$row['cost'] = $cost;
				$row['actions'] = ' <a href="javascript:void(0);" onclick="EditDetail('.$row['id'].','.$name.','.$cost_t.')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteDataDetail('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}
	

	public function form_tool()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$tool = $this->input->post('tool');
		$measure_id = $this->input->post('measure_id');
		$tag_id = $this->input->post('tag_id');
		$status = $this->input->post('status');

		$data = array(
			'tool' => $tool,
			'measure_id' => $measure_id,
			'tag_id' => $tag_id,
			'status' => $status
		);

		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_tools',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_tools',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function detail_tool_form()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$tool_id = $this->input->post('tool_id');
		$name = $this->input->post('name');
		$cost = $this->input->post('cost');

		$data = array(
			'tool_id' => $tool_id,
			'name' => $name,
			'cost' => $cost,
			'status' => 'PUBLISH'
		);

		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_tool_detail',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_tool_detail',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	

	public function get_tool()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->get_where('pmm_tools',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function delete_tool()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED'
			);
			if($this->db->update('pmm_tools',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function delete_tool_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED'
			);
			if($this->db->update('pmm_tool_detail',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	

	function setting_production()
	{
		$output=array('output'=>'false');
		// Get data
		$factor_lost = str_replace(',', '.', $this->input->post('factor_lost'));
		$content_weight = str_replace(',', '.', $this->input->post('content_weight'));
        $admin_id = $this->session->userdata('admin_id');
        $jenis_usaha = $this->input->post('jenis_usaha');
        $kop_surat = $this->crud_global->GetField('pmm_setting_production',array('id'=>1),'kop_surat');
        $datecreated = date("Y-m-d H:i:s");


        if(!empty($_FILES['kop_surat']['name'])){
         
    	
    		if (!file_exists('uploads/kop_surat')) {
			    mkdir('uploads/kop_surat', 0777, true);
			}

            $config['upload_path'] = 'uploads/kop_surat'; 
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = $_FILES['kop_surat']['name'];
    
            $this->load->library('upload',$config); 
    
            if($this->upload->do_upload('kop_surat')){
                $uploadData = $this->upload->data();
                $kop_surat = $uploadData['file_name'];
            }else {
            	$output=array('output'=>'false','error'=>$this->upload->display_errors());
            	// exit();
            }

        }
    	$arrayvalues = array(
    		'factor_lost'=>$factor_lost,
    		'content_weight' => $content_weight,
    		'overhead' => $this->input->post('overhead'),
    		'code_prefix' => $this->input->post('code_prefix'),
    		'jenis_usaha' => $jenis_usaha,
    		'kop_surat' => $kop_surat,
    		'updated_by'=>$admin_id,
    		'nama_pt' => $this->input->post('nama_pt')
    		);
        $query=$this->db->update('pmm_setting_production',$arrayvalues,array('id'=>1));
        if($query){
        	$output=array('output'=>'true');
        }else {
        	$output=array('output'=>'false');
        }

        echo json_encode($output);
	}


	public function get_riel_detail()
	{
		$id = $this->input->post('id');

		$output = $this->pmm_model->GetRielPriceDetail($id);

		echo json_encode($output);
		
	}


	// Supplier
	public function table_supplier()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('name','asc');
		$query = $this->db->get('pmm_supplier');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				if(empty($row['date_pkp'])){
					$row['date_pkp'] = '';
				}else {
					$row['date_pkp'] = date('d F Y',strtotime($row['date_pkp']));	
				}

				$name = "'".$row['name']."'";
				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					$row['name'] = '<a href="javascript:void(0);"  >'.$row['name'].'</a>';
					$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	public function table_supplier_materials()
	{	
		$data = array();
		$supplier_id = $this->input->post('supplier_id');

		$this->db->where('supplier_id',$supplier_id);
		$query = $this->db->get('pmm_supplier_materials');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;

				$row['material'] = $this->crud_global->GetField('pmm_materials',array('id'=>$row['material_id']),'material_name');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
				$row['price'] = number_format($row['price'],2,',','.');
				$row['cost'] = number_format($row['cost'],2,',','.');
				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteDatSuppMat('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_supplier_materials()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$material_id = $this->input->post('material_id');
		$supplier_id = $this->input->post('supplier_id');
		$measure = $this->input->post('measure');
		$price = $this->input->post('price');
		$cost = $this->input->post('cost');

		$data = array(
			'material_id' => $material_id,
			'supplier_id' => $supplier_id,
			'measure' => $measure,
			'price' => $price,
			'cost' => $cost,
		);

		if(!empty($id)){
			if($this->db->update('pmm_supplier_materials',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			if($this->db->insert('pmm_supplier_materials',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}
	

	public function form_supplier()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$address = $this->input->post('address');
		$npwp = $this->input->post('npwp');
		$pic = $this->input->post('pic');
		$position = $this->input->post('position');
		$date_pkp = $this->input->post('date_pkp');
		$status = $this->input->post('status');

		if(empty($date_pkp)){
			$date_pkp = null;
		}else {
			$date_pkp = $this->input->post('date_pkp');
		}
		$data = array(
			'name' => $name,
			'address' => $address,
			'npwp' => $npwp,
			'pic' => $pic,
			'position' => $position,
			'date_pkp' => $date_pkp,
			'status' => $status
		);

		$last_coa = $this->db->select('coa_number')->order_by('coa_number','desc')->get_where('pmm_coa',array('coa_parent'=> 40,'coa_category'=>8))->row_array();
		$last_coa_number = explode('-', $last_coa['coa_number']);
		$coa_number = intval($last_coa_number[1]) + 1;	

		if(!empty($id)){
			$data['updated_by'] = date('Y-m-d H:i:s');
			if($this->db->update('pmm_supplier',$data,array('id'=>$id))){
				$coa_id = $this->crud_global->GetField('pmm_supplier',array('id'=>$id),'coa_id');
				$this->db->update('pmm_coa',array('name'=>$name),array('id'=>$coa_id));
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_supplier',$data)){
				$supplier_id = $this->db->insert_id();
				// Insert to COA
				$coa = array(
					'coa_category' => 8,
					'coa' => 'Hutang '.$name,
					'coa_number' => $coa_number,
					'coa_parent' => 40,
					'status' => 'PUBLISH'
				);

				$this->db->insert('pmm_coa',$coa);
				$coa_id = $this->db->insert_id();

				$this->db->update('pmm_supplier',array('coa_id'=>$coa_id),array('id'=>$supplier_id));

				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_supplier()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('id,name,address,npwp,status,pic,position,date_pkp')->get_where('pmm_supplier',array('id'=>$id))->row_array();
			$data['date_pkp'] = date('d-m-Y',strtotime($data['date_pkp']));
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function get_material_type()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('id,material_name')->get_where('pmm_materials',array('type'=>$id))->result_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}
	

	public function delete_supplier()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'),
			);
			if($this->db->update('pmm_supplier',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function delete_supplier_material()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			if($this->db->delete('pmm_supplier_materials',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	


	// Client
	public function table_client()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('client_name','asc');
		$query = $this->db->get('pmm_client');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['contract'] = number_format($row['contract'],2,',','.');

				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_client()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$client_name = $this->input->post('client_name');
		$status = $this->input->post('status');

		$data = array(
			'client_name' => $client_name,
			'client_address' => $this->input->post('client_address'),
			'contract' => $this->input->post('contract'),
			'status' => $status
		);

		if(!empty($id)){
			if($this->db->update('pmm_client',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			if($this->db->insert('pmm_client',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_client()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('id,client_name,client_address,status')->get_where('pmm_client',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function delete_client()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'),
			);
			if($this->db->update('pmm_client',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	// Businesses
	public function table_business()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('client_name','asc');
		$query = $this->db->get('pmm_client');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['contract'] = number_format($row['contract'],2,',','.');

				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_business()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$business_name = $this->input->post('business_name');
		$status = 'PUBLISH';

		if(!empty($id)){
			$kop = $this->input->post('old_kop');
		}else {
			$kop = '';
		}
		

		if($_FILES["photos"]["error"] == 0) {
			//means there is no file uploaded
			$config['upload_path'] = './uploads/images/';
			$config['allowed_types'] = 'jpeg|JPEG|jpg|JPG|png|PNG';

			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('photos')) {
				// $error = array('error' => $this->upload->display_errors());
				if(!empty($id)){
					// $this->session->set_flashdata('form_error',$this->upload->display_errors());
					// redirect('welcome/new_story/'.$id);
				}else {
					// $this->session->set_flashdata('form_error',$this->upload->display_errors());
					// redirect('new_story');
				}
				
			} else {
				$file_data = $this->upload->data();

				$kop =  'uploads/images/'.$file_data['file_name'];
			}
		}

		$data = array(
			'business_name' => $business_name,
			'kop' => $kop,
			'status' => $status
		);

		if(!empty($id)){
			if($this->db->update('pmm_businesses',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			if($this->db->insert('pmm_businesses',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_business()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('id,client_name,status')->get_where('pmm_client',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function delete_business()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'),
			);
			if($this->db->update('pmm_client',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	// Slupm
	public function table_slump()
	{	
		$data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_slump');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_slump()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$slump_name = $this->input->post('slump_name');
		$status = $this->input->post('status');

		$data = array(
			'slump_name' => $slump_name,
			'status' => $status
		);

		if(!empty($id)){
			if($this->db->update('pmm_slump',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			if($this->db->insert('pmm_slump',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_slump()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('id,slump_name,status')->get_where('pmm_slump',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function delete_slump()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'),
			);
			if($this->db->update('pmm_slump',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	// Tags
	public function table_tags()
	{	
		$data = array();
		$type = $this->input->post('type');
		$this->db->where('status !=','DELETED');
		if(!empty($type)){
			$this->db->where('tag_type',$type);
		}
		$this->db->order_by('tag_name','asc');
		$query = $this->db->get('pmm_tags');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$price = 0;
				// if($row['tag_type'] == 'MATERIAL'){
				// 	$get_price = $this->db->select('AVG(cost) as cost')->get_where('pmm_materials',array('status'=>'PUBLISH','tag_id'=>$row['id']))->row_array();
				// 	if(!empty($get_price)){
				// 		$price = $get_price['cost'];
				// 	}
				// }
				$row['price'] = number_format($price,2,',','.');
				if($this->admin_group_id == 4 || $this->admin_group_id == 1 ){
					
					$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_tags()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$tag_name = $this->input->post('tag_name');
		$tag_type = $this->input->post('tag_type');
		$status = $this->input->post('status');

		$data = array(
			'tag_name' => $tag_name,
			'tag_type' => $tag_type,
			'status' => $status
		);

		if(!empty($id)){
			if($this->db->update('pmm_tags',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			if($this->db->insert('pmm_tags',$data)){
				$output['output'] = true;
			}	
				
		}
		
		echo json_encode($output);	
	}

	public function get_tags()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('*')->get_where('pmm_tags',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function delete_tags()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED',
			);
			if($this->db->update('pmm_tags',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	// Remaining Materials
	public function table_remaining_material()
	{	
		$data = array();

		$this->db->where('status','PUBLISH');
		$this->db->order_by('date','desc');
		$this->db->order_by('id','desc');
		if(!empty($this->input->post('material_id'))){
			$this->db->where('material_id',$this->input->post('material_id'));
		}
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('date >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('date <=',date('Y-m-d',strtotime($arr_date[1])));
		}
		$query = $this->db->get('pmm_remaining_materials_cat');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['date'] = date('d F Y',strtotime($row['date']));
				$row['material_id'] = $this->crud_global->GetField('produk',array('id'=>$row['material_id']),'nama_produk');
				$title = "'".$row['date'].' '.$row['material_id']."'";
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
				$row['volume'] = number_format($row['volume'],2,',','.');
				//$row['total'] = '<a href="javascript:void(0);" onclick="modalDetail('.$title.','.$row['id'].')" >'.number_format($row['total'],2,',','.').'</a>';
				$row['total'] = number_format($row['total'],2,',','.');
				//$row['actions'] = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$row['actions'] = '-';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_remaining_material()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$date = date('Y-m-d',strtotime($this->input->post('date')));
		
		// $measure = $this->crud_global->GetField('pmm_materials',array('id'=>$material_id),'measure');
		$volume = $this->input->post('volume');
		$notes = $this->input->post('notes');
		$status = $this->input->post('status');

		$material_id = $this->input->post('material_id');

		$get_m = $this->crud_global->GetField('produk',array('id'=>$material_id,'status'=>'PUBLISH'),'satuan');

		$last_remaining = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','material_id'=>$material_id,'date <'=>$date))->row_array();

		$mat_id = array();
		$mats = $this->db->select('id')->get_where('pmm_penawaran_pembelian_detail',array('material_id'=>$material_id))->result_array();
		if(!empty($mats)){
			foreach ($mats as $mat) {
				$mat_id[] = $mat['id'];
			}
		}


		// Get Cost AVG
		$price = 0;
		
			$query = $this->db->query("SELECT display_harga_satuan from pmm_receipt_material prm 
		where material_id = $material_id
		order by date_receipt desc 
		limit 1;")->first_row("array");

		$price = intval($query['display_harga_satuan']);
		
        /*
		$this->db->select('material_id,measure,AVG(cost) as cost, AVG(convert_value) as convert_value, (AVG(cost) / AVG(convert_value)) as price,penawaran_material_id,supplier_id,SUM(volume) as volume');
		//$this->db->where_in('penawaran_material_id',$mat_id);
		if(!empty($last_remaining)){
			$this->db->where('date_receipt >',$last_remaining['date']);	
		}
		$this->db->where('date_receipt <=',$date);
		$this->db->group_by('penawaran_material_id');
		$get_mats = $this->db->get('pmm_receipt_material')->result_array();
		if(!empty($get_mats)){
			$total_cost =0;
			foreach ($get_mats as $mat_receipt) {
				$total_cost += $mat_receipt['price'];
			}
			$price = $total_cost / count($get_mats);
		}else {

			$this->db->select('material_id,measure,AVG(cost) as cost, AVG(convert_value) as convert_value, (AVG(cost) / AVG(convert_value)) as price,penawaran_material_id,supplier_id,SUM(volume) as volume');
			$this->db->where_in('penawaran_material_id',$mat_id);
			if(!empty($last_remaining)){
				$this->db->where('date_receipt <=',$last_remaining['date']);	
			}
			$this->db->group_by('penawaran_material_id');
			$get_mats = $this->db->get('pmm_receipt_material')->result_array();	
			$total_cost =0;
			foreach ($get_mats as $mat_receipt) {
				$total_cost += $mat_receipt['price'];
			}

			$price = $total_cost / count($get_mats);
		}
        */
        
		// Get All Material
		$this->db->select('SUM(pm.volume) as volume');	
		if(!empty($last_remaining)){
			$this->db->where('date_receipt >',$last_remaining['date']);	
		}
		$this->db->where('date_receipt <=',$date);
		//$this->db->where_in('pm.penawaran_material_id',$mat_id);
		$all_mats = $this->db->get('pmm_receipt_material pm')->row_array();


		// echo '<pre>';
		// print_r($get_mats);
		// echo '</pre>';

		// // $price = $this->db->select('AVG(cost) as cost')->get_where('pmm_materials',array('status'=>'PUBLISH','tag_id'=>$tag_id))->row_array()['cost'];
		$arr = array(
			'material_id' => $material_id,
			'date' => $date,
			'measure' => $get_m,
			'volume' => $volume,
			'notes' => $notes,
			'reset' => 1,
			'price' => $price,
			'total' => $volume * $price,
			'status' => 'PUBLISH'
		);

		if(!empty($id)){
			$arr['updated_by'] = $this->session->userdata('admin_id');
			$this->db->update('pmm_remaining_materials_cat',$arr,array('id'=>$id));

			$this->db->delete('pmm_remaining_materials',array('cat_id'=>$id));

			if(!empty($get_mats)){
				foreach ($get_mats as $mat_receipt) {

					$percen = round(($mat_receipt['volume'] / $all_mats['volume']) * 100);
					$vol_supp = ($percen * $volume ) / 100;
					$total_supp = ($percen * ($volume * $price)) / 100;

					$arr_mats = array(
						'cat_id' => $id,
						'date' => $date,
						'measure' => $get_m,
						'material_id' => $mat_receipt['material_id'],
						'supplier_id' => $mat_receipt['supplier_id'],
						'penawaran_material_id' => $mat_receipt['penawaran_material_id'],
						'notes' => $notes,
						'price' => $mat_receipt['cost'],
						'volume' => $vol_supp,
						'total_price' => $total_supp,
						'status' => 'PUBLISH'
					);
					$arr_mats['created_by'] = $this->session->userdata('admin_id');
					$arr_mats['updated_by'] = $this->session->userdata('admin_id');
					$this->db->insert('pmm_remaining_materials',$arr_mats);
				}
			}



			$output['output'] = true;
		}else{
			$arr['created_by'] = $this->session->userdata('admin_id');
			$arr['created_on'] = date('Y-m-d H:i:s');
			$this->db->insert('pmm_remaining_materials_cat',$arr);
			//$this->db->insert('pmm_remaining_materials_cat_2',$arr);
			$id = $this->db->insert_id();
			if(!empty($get_mats) && !empty($all_mats['volume'])){
				foreach ($get_mats as $mat_receipt) {
					$percen = round(($mat_receipt['volume'] / $all_mats['volume']) * 100);
					$vol_supp = ($percen * $volume ) / 100;
					$total_supp = ($percen * ($volume * $price)) / 100;
					$arr_mats = array(
						'cat_id' => $id,
						'date' => $date,
						'measure' => $get_m,
						'material_id' => $mat_receipt['material_id'],
						'supplier_id' => $mat_receipt['supplier_id'],
						'penawaran_material_id' => $mat_receipt['penawaran_material_id'],
						'price' => $mat_receipt['cost'],
						'volume' => $vol_supp,
						'total_price' => $total_supp,
						'notes' => $notes,
						'status' => 'PUBLISH'
					);
					$arr_mats['created_by'] = $this->session->userdata('admin_id');
					$arr_mats['updated_by'] = $this->session->userdata('admin_id');
					$this->db->insert('pmm_remaining_materials',$arr_mats);
				}
			}

			$output['output'] = true;
		}

		
		echo json_encode($output);	
	}

	public function get_remaining_material()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$this->db->where('id',$id);
			$data = $this->db->get('pmm_remaining_materials_cat')->row_array();
			$data['date'] = date('d-m-Y',strtotime($data['date']));
			$output['output'] = $data;
		}
		echo json_encode($output);
	}


	public function get_remaining_price()
	{

		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			?>
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>No</th>
						<th>Material</th>
						<th>Price</th>	
					</tr>
					
				</thead>
				<tbody>
					<?php
					$mats = $this->db->get_where('pmm_remaining_materials',array('cat_id'=>$id))->result_array();
					if(!empty($mats)){
						$no =1;
						foreach ($mats as $mat) {
							?>
							<tr>
								<td width="30px"><?php echo $no;?></td>
								<td><?php echo $this->crud_global->GetField('pmm_materials',array('id'=>$mat['material_id']),'material_name').' <small><i>('.$this->crud_global->GetField('pmm_supplier',array('id'=>$mat['supplier_id']),'name').')</i></small>';?></td>
								<td class="text-right"><span class="pull-left">Rp. </span> <?php echo number_format($mat['price'],2,',','.');?></td>
							</tr>
							<?php
							$no++;
						}

						?>
						<tr>
							<th class="text-right" colspan="2">Rata-rata</th>
							<th class="text-right"><span class="pull-left">Rp. </span> <?php echo number_format($this->crud_global->GetField('pmm_remaining_materials_cat',array('id'=>$id),'price'),2,',','.');?></th>
						</tr>
						<?php
					}else {
						?>
						<tr>
							<td class="text-center" colspan="3">No Data</td>
						</tr>
						<?php
					}
					?>	
				</tbody>
			</table>
			<?php
		}
	}

	public function delete_remaining_material()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$data = array(
				'status' => 'DELETED',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'),
			);
			$this->db->update('pmm_remaining_materials_cat',$data,array('id'=>$id));
			if($this->db->update('pmm_remaining_materials',$data,array('cat_id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	function db_lost_profit()
	{
		$arr_date = $this->input->post('arr_date');

		if(!empty($arr_date)){
			$ex_date = explode( '- ', $arr_date);
	        $month = strtotime($ex_date[0]);
	        $end = strtotime($ex_date[1]);
		}else {
			$month =  strtotime(date('d-m-Y'). '-7 months');
			$end = strtotime(date('d-m-Y'). '+1 months');

		}
		
        $label_chart = array();

        while($month < $end)
        {    
             $label_chart[] = date('M Y', $month);
             $month = strtotime("+1 month", $month);
             
        }
        
        foreach ($label_chart as $m => $m_arr) {
            $get_revenue = $this->pmm_model->getRevenue($m_arr,$arr_date);
            $get_revenuecost = $this->pmm_model->getRevenueCost($m_arr,$arr_date);
			$get_persediaan_bahan_baku = $this->pmm_model->getPersediaanBahanBaku($m_arr,$arr_date);
			$get_persediaan_barang_jadi = $this->pmm_model->getPersediaanBarangJadi($m_arr,$arr_date);
            if(empty($get_revenuecost)){
            	$get_revenuecost = 0;
            }
            if(empty($get_revenuecost)){
            	$get_revenuecost = 0;
            }

            $laba = $get_revenue - $get_revenuecost;
            if($get_revenue > 0){
            	$net = ($laba / $get_revenue) * 100;
            }else {
            	$net = 0;
            }
            
            $chart_revenue[] = number_format($get_revenue,0,',','.');	
            $chart_revenuecost[] = number_format($get_revenuecost,0,',','.');
			$chart_persediaan_bahan_baku[] = number_format($get_persediaan_bahan_baku,0,',','.');
			$chart_persediaan_barang_jadi[] = number_format($get_persediaan_bahan_baku,0,',','.');	
            $chart_laba[] = number_format($laba,0,',','.');	
            $chart_net[] = number_format($net,0,'.',',');
        }


        // Akumulasi
        $label_chart[] = 'Akumulasi';
        $all_revenue = $this->pmm_model->getRevenueAll($arr_date);
        $chart_revenue[] = number_format($all_revenue['total'],0,',','.');

		$all_revenuecostadministrasibiaya = $this->pmm_model->getRevenueCostAllAdministrasiBiaya($arr_date);
        $chart_revenuecostadministrasibiaya[] = number_format($all_revenuecostadministrasibiaya['total'],0,',','.');

		$all_revenuecostadministrasijurnal = $this->pmm_model->getRevenueCostAllAdministrasiJurnal($arr_date);
        $chart_revenuecostadministrasijurnal[] = number_format($all_revenuecostadministrasijurnal['total'],0,',','.');

		$all_revenuecostlainnyabiaya = $this->pmm_model->getRevenueCostAllLainnyaBiaya($arr_date);
        $chart_revenuecostlainnyabiaya[] = number_format($all_revenuecostlainnyabiaya['total'],0,',','.');

		$all_revenuecostlainnyajurnal = $this->pmm_model->getRevenueCostAllLainnyaJurnal($arr_date);
        $chart_revenuecostlainnyajurnal[] = number_format($all_revenuecostlainnyajurnal['total'],0,',','.');

		$all_persediaanbahanbaku = $this->pmm_model->getRevenueCostAllPersediaanBahanBaku($arr_date);
        $chart_persediaanbahanbaku[] = number_format($all_persediaanbahanbaku['total'],0,',','.');

		$all_persediaanbarangjadi = $this->pmm_model->getRevenueCostAllPersediaanBarangJadi($arr_date);
        $chart_persediaanbarangjadi[] = number_format($all_persediaanbarangjadi['total'],0,',','.');

		$all_revenuecost = $this->pmm_model->getRevenueCostAll($arr_date);
        $chart_revenuecost[] = number_format($all_revenuecost['total'] + $all_revenuecostadministrasibiaya['total'] + $all_revenuecostadministrasijurnal['total'] + $all_revenuecostlainnyabiaya['total'] + $all_revenuecostlainnyajurnal['total'],0,',','.');

        $all_laba = $all_revenue['total'] - ($all_revenuecost['total'] + $all_revenuecostadministrasibiaya['total'] + $all_revenuecostadministrasijurnal['total'] + $all_revenuecostlainnyabiaya['total'] + $all_revenuecostlainnyajurnal['total']);
        $chart_laba[] = number_format($all_laba,0,',','.');

		if($all_revenue['total'] > 0){
        	$all_net = ($all_laba / $all_revenue['total']) * 100;
        }else {
        	$all_net = 0;
        }
        $chart_net[] = number_format($all_net,2,'.',',');

        $datasets_laba[0] = array(
            'label' => 'Persentase Laba Rugi',
            'backgroundColor' => '#ffb732',
            'data' => $chart_net,
            'data_revenue' => $chart_revenue,
            'data_revenuecost' => $chart_revenuecost,
			'data_persediaan_bahan_baku' => $chart_persediaan_bahan_baku,
            'data_laba' => $chart_laba,
			'data_net' => $chart_net,
            'fill' => false,
        );

        $labels = $label_chart;
        $datasets = $datasets_laba;
		echo json_encode(array('labels'=>$labels,'datasets'=>$datasets));
	}

}	