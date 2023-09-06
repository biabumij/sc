<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_access extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','menu/m_menu','admin_access/m_admin_access','DB_model'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		date_default_timezone_set('Asia/Jakarta');
	}

	function table()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_admin_group';
		    $column_order = array('admin_group_name','datecreated','status',null); 
		    $column_search = array('admin_group_name');
		    $order = array('admin_group_name' => 'asc'); // default order 
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
	            $row[] = $value->admin_group_name;
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('admin_access/delete/'.$value->admin_group_id.'');
	            $url_edit = site_url('admin_access/form_edit/'.$value->admin_group_id.'');

	            $btn_edit = '<a class="btn btn-sm btn-primary" style="font-weight:bold; border-radius:10px;" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
	            $btn_delete = '<a class="btn btn-sm btn-danger" style="font-weight:bold; border-radius:10px;" href="javascript:void(0)" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

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
			$admin_group_name = $this->input->post('admin_group_name');
			$menu_id = $this->input->post('menu_id[]');
	        $admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");

	        // insert JSON
			$check_name = $this->crud_global->CheckNum('tbl_admin_group',array('admin_group_name'=>$admin_group_name,'status !='=>0));
	        if($check_name == true){
	        	$output=array('output'=>'Your Name has been registered');
	        }else {

	        	$arrayvalues = array(
	        		'admin_group_name'=>$admin_group_name,
	        		'status'=>$status,
	        		'created_by'=>$admin_id,
	        		'datecreated'=>$datecreated
	        		);

	            $query=$this->db->insert('tbl_admin_group',$arrayvalues);
	            if($query){
	            	$admin_group_id = $this->db->insert_id();
	            	foreach ($menu_id as $key_menu => $row) {
	            		$arrayvalues_access = array(
	            				'admin_group_id'=>$admin_group_id,
	            				'menu_id'=>$row
	            			);
	            		$this->db->insert('tbl_admin_access',$arrayvalues_access);
	            	}
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
			$delete=$this->crud_global->UpdateDefault('tbl_admin_group',array('status'=>0),array('admin_group_id'=>$del_id));
			if($delete){
				$this->crud_global->deleteData('tbl_admin_access',array('admin_group_id'=>$del_id));
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
				$data['row'] = $this->crud_global->ShowTableNew('tbl_admin_group',array('admin_group_id'=>$uri3));
				$this->load->view('admin_access/form_edit',$data);
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
			$admin_group_name = $this->input->post('admin_group_name');
			$menu_id = $this->input->post('menu_id[]');
	        $admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");

	        // insert JSON
			$name_old = $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$id),'admin_group_name');
	        if($name_old != $admin_group_name){
	        	$check_name = $this->crud_global->CheckNum('tbl_admin_group',array('admin_group_name'=>$admin_group_name,'status !='=>0));
	        }else{
	        	$check_name = false;
	        }

	        if($check_name == true){
	        	$output=array('output'=>'Your Name has been registered');
	        }else {

	        	$arraywhere = array('admin_group_id'=>$id);
		    	$arrayvalues = array(
		    		'admin_group_name'=>$admin_group_name,
	        		'status'=>$status,
		    		'updated_by'=>$admin_id,
		    		'dateupdated'=>$datecreated
		    		);
		        $query=$this->crud_global->UpdateDefault('tbl_admin_group',$arrayvalues,$arraywhere);
	            if($query){
	            	$this->db->delete('tbl_admin_access',array('admin_group_id'=> $id));
	            	foreach ($menu_id as $key_menu => $row) {
	            		$arrayvalues_access = array(
	            				'admin_group_id'=>$id,
	            				'menu_id'=>$row
	            			);
	            		$this->db->insert('tbl_admin_access',$arrayvalues_access);
	            	}

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