<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','DB_model','menu/m_menu'));
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
			$table = 'tbl_menu';
		    $column_order = array('menu_name','file_view','status',null); 
		    $column_search = array('menu_name');
		    $order = array('menu_name' => 'asc'); // default order 
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
	            $row[] = $value->menu_name;
	            $row[] = $this->m_menu->GetParentMenu($value->parent_id);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('menu/delete/'.$value->menu_id.'');
	            $url_edit = site_url('menu/form_edit/'.$value->menu_id.'');

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
			$menu = $this->input->post('menu');
			$menu_alias = strtolower(str_replace(" ", "_", $menu));
			$file_cont = $this->input->post('file_cont');
			$menu_icon = $this->input->post('menu_icon');
			$parent_id = $this->input->post('parent_id');
			$order_id = $this->input->post('order_id');
	        $admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");

	        $order_group_id = $this->input->post('order_group_id');

	        // insert JSON
			$check_menu = $this->crud_global->CheckNum('tbl_menu',array('menu_name'=>$menu,'status !='=>0));

	        if($check_menu == true){
	        	$output=array('output'=>'Your Name has been registered');
	        }else {

	        	$arrayvalues = array(
	        		'menu_name'=>$menu,
	        		'menu_alias'=>$menu_alias,
	        		'menu_icon'=>$menu_icon,
	        		'file_cont' => $file_cont,
	        		'parent_id'=>$parent_id,
	        		'order_id'=>$order_id,
	        		'status'=>$status,
	        		'created_by'=>$admin_id,
	        		'datecreated'=>$datecreated,
	        		'order_group_id' => $order_group_id
	        		);

	            $query=$this->db->insert('tbl_menu',$arrayvalues);
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
			$delete=$this->crud_global->UpdateDefault('tbl_menu',array('status'=>0),array('menu_id'=>$del_id));
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
				$data['row'] = $this->crud_global->ShowTableNew('tbl_menu',array('menu_id'=>$uri3));
				$this->load->view('menu/form_edit',$data);
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
			$menu = $this->input->post('menu');
			$menu_alias = strtolower(str_replace(" ", "_", $menu));
			$file_cont = $this->input->post('file_cont');
			$menu_icon = $this->input->post('menu_icon');
			$parent_id = $this->input->post('parent_id');
			$order_id = $this->input->post('order_id');
	        $admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");
	        $order_group_id = $this->input->post('order_group_id');

	        // insert JSON
			$name_old = $this->crud_global->GetField('tbl_menu',array('menu_id'=>$id),'menu_name');
	        if($name_old != $menu){
	        	$check_name = $this->crud_global->CheckNum('tbl_menu',array('menu_name'=>$menu,'status !='=>0));
	        }else{
	        	$check_name = false;
	        }

	        if($check_name == true){
	        	$output=array('output'=>'Your Name has been registered');
	        }else {

	        	$arraywhere = array('menu_id'=>$id);
		    	$arrayvalues = array(
		    		'menu_name'=>$menu,
	        		'menu_alias'=>$menu_alias,
	        		'menu_icon'=>$menu_icon,
	        		'file_cont' => $file_cont,
	        		'parent_id'=>$parent_id,
	        		'order_id'=>$order_id,
	        		'status'=>$status,
		    		'updated_by'=>$admin_id,
		    		'dateupdated'=>$datecreated,
		    		'order_group_id' => $order_group_id
		    		);
		        $query=$this->crud_global->UpdateDefault('tbl_menu',$arrayvalues,$arraywhere);
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