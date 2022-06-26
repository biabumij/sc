<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Element_input extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','DB_model'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		date_default_timezone_set('Asia/Jakarta');
	}

	function index()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			
		}else {
			redirect('admin');
		}
	}

	function table()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_element_input';
		    $column_order = array('element_input','element_input_type','status',null); 
		    $column_search = array('element_input');
		    $order = array('element_input_id' => 'desc'); // default order 
		    $arraywhere = array('status !=' => '0');
			$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
	        $data = array();
	        $no = $_POST['start'];
	        $no_list = 0;
	        foreach ($list as $value) {
	            $no++;
	            $no_list++;
	            $row = array();
	            $row[] = $no;
	            $row[] = $value->element_input;
	            $row[] = $this->general->GetInputType($value->element_input_type);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('element_input/delete/'.$value->element_input_id.'');
	            $url_edit = site_url('element_input/form_edit/'.$value->element_input_id.'');

	            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
	            $btn_delete = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

	            //add html for action
	            $row[] = $btn_edit." ".$btn_delete;
	 
	            $data[] = $row;
	        }
	        $output = array(
	                    "draw" => $_POST['draw'],
	                    "recordsTotal" => $this->DB_model->count_all($table,$arraywhere),
	                    "recordsFiltered" => $this->DB_model->count_filtered($table,$column_order,$column_search,$order,$arraywhere),
	                    "data" => $data,
	                );
	        //output to json format
	        echo json_encode($output);	
		}else {
			redirect('admin');
		}
	}


	function add()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$output=array('output'=>'false');
			// Get data
			$element_input = $this->input->post('element_input');
			$element_input_alias = strtolower(str_replace(" ", "_", $element_input));
			$element_input_type = $this->input->post('element_input_type');
			$element_input_value = $this->input->post('element_input_value[]');
	        $admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");

	        // insert JSON
			$check_element_input = $this->crud_global->CheckNum('tbl_element_input',array('element_input'=>$element_input,'status !='=>0));

	        if($check_element_input == true){
	        	$output=array('output'=>'Your Name has been registered');
	        }else {

	        	if(is_array($element_input_value)){
	        		$element_input_value = implode(",", $element_input_value);
	        	}else {
	        		$element_input_value = false;
	        	}
	        	$arrayvalues = array(
	        		'element_input'=>$element_input,
	        		'element_input_alias'=>$element_input_alias,
	        		'element_input_type'=>$element_input_type,
	        		'element_input_value' => $element_input_value,
	        		'status'=>$status,
	        		'created_by'=>$admin_id,
	        		'datecreated'=>$datecreated
	        		);

	            $query=$this->db->insert('tbl_element_input',$arrayvalues);
	            if($query){
	            	$output=array('output'=>'true');
	            }else {
	            	$output=array('output'=>'false');
	            }
	        }
	        echo json_encode($output);
		}else {
			redirect('admin');
		}
	}

	function delete()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete=$this->crud_global->UpdateDefault('tbl_element_input',array('status'=>0),array('element_input_id'=>$del_id));
			if($delete){
				echo 'success';
			}else {
				echo 'failed';
			}
		}
	}

	function form_edit()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['row'] = $this->crud_global->ShowTableNew('tbl_element_input',array('element_input_id'=>$uri3));
				$this->load->view('element_input/form_edit',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function edit()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$output=array('output'=>'false');

			// Get data
			$id = $this->input->post('id');
			$element_input = $this->input->post('element_input');
			$element_input_alias = strtolower(str_replace(" ", "_", $element_input));
			$element_input_type = $this->input->post('element_input_type');
			$element_input_value = $this->input->post('element_input_value[]');
	        $admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");

	        // insert JSON
			$name_old = $this->crud_global->GetField('tbl_element_input',array('element_input_id'=>$id),'element_input');
	        if($name_old != $element_input){
	        	$check_name = $this->crud_global->CheckNum('tbl_element_input',array('element_input'=>$element_input,'status !='=>0));
	        }else{
	        	$check_name = false;
	        }

	        if($check_name == true){
	        	$output=array('output'=>'Your Name has been registered');
	        }else {

	        	$arraywhere = array('element_input_id'=>$id);
		    	$arrayvalues = array(
		    		'element_input'=>$element_input,
	        		'element_input_alias'=>$element_input_alias,
	        		'element_input_type'=>$element_input_type,
	        		'element_input_value' => $element_input_value,
	        		'status'=>$status,
		    		'updated_by'=>$admin_id,
		    		'dateupdated'=>$datecreated
		    		);
		        $query=$this->crud_global->UpdateDefault('tbl_element_input',$arrayvalues,$arraywhere);
	            if($query){
	            	$output=array('output'=>'true');
	            }else {
	            	$output=array('output'=>'false');
	            }
	        }
	        echo json_encode($output);
		}else {
			redirect('admin');
		}
		
	}

}