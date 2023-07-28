<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','Templates','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm/pmm_model'));
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
			$uri2 = $this->uri->segment(2);
			if(!empty($uri2)){
				switch ($uri2) {

					case 'logout':
					$this->session->unset_userdata('admin_id');
					$this->session->unset_userdata('email');
			   		// $this->session->sess_destroy();
			   		redirect('admin');
					break;
					
					default:
					$arr_menu = $this->crud_global->ShowTableNew('tbl_menu',array('status'=>1));
					if(is_array($arr_menu)){
						foreach ($arr_menu as $key => $row) {
							if($uri2 == $row->menu_alias){
								$data['admin_id'] = $this->session->userdata('admin_id');
								$data['row'] = $this->crud_global->ShowTableNew('tbl_menu',array('status'=>1,'menu_alias'=>$row->menu_alias));
								$this->load->view($row->file_cont,$data);
							}
						}
					}
					break;
				}
			}else {
				$this->load->view('dashboard');
			}
		}else {
			
			$this->load->view('login');
		}
	}

	function table()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_admin';
		    $column_order = array('admin_name','admin_email','admin_group_id','status',null); 
		    $column_search = array('admin_name','admin_email','admin_group_id','status');
		    $order = array('admin_name' => 'asc'); // default order 

		    $admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$this->session->userdata('admin_id')),'admin_group_id');
		    if($admin_group_id != 1){
		    	$arraywhere = array('status !=' => '0','admin_group_id !='=>'1');
		    }else {
		    	$arraywhere = array('status !=' => '0');
		    }
			$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
	        $data = array();
	        $no = $_POST['start'];
	        $no_list = 0;
	        foreach ($list as $value) {
	            $no++;
	            $no_list++;
	            $row = array();
	            $row[] = $no;
	            $row[] = $value->admin_name;
	            $row[] = $value->admin_email;
	            $row[] = $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$value->admin_group_id),'admin_group_name');
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('admin/delete/'.$value->admin_id.'');
	            $url_edit = site_url('admin/form_edit/'.$value->admin_id.'');

	            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
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
			$this->load->view('login');
		}
	}

	function add()
	{
		$output=array('output'=>'false');
		// Get data
		$admin_name = $this->input->post('admin_name');
		$admin_group = $this->input->post('admin_group_id');
		$admin_password = $this->input->post('admin_password');
		$admin_email = $this->input->post('admin_email');
		$admin_phone = $this->input->post('admin_phone');
		$admin_photo = $this->input->post('admin_photo');
        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");
        	
    	// insert JSON
		$chek_email = $this->crud_global->CheckNum('tbl_admin',array('admin_email'=>$admin_email,'status !='=>0));
        
        if($chek_email == true){
        	$output=array('output'=>'Your Email has been registered');
        }else {
        	$enkrip_pass = $this->enkrip->EnkripPasswordAdmin($admin_password);
        	$arrayvalues = array(
        		'admin_name'=>$admin_name,
        		'admin_group_id'=>$admin_group,
        		'admin_password'=>$enkrip_pass,
        		'admin_email'=>$admin_email,
        		'admin_phone'=>$admin_phone,
        		'admin_photo'=>$admin_photo,
        		'status'=>$status,
        		'create_by'=>$admin_id,
        		'datecreated'=>$datecreated
        		);
            $query=$this->db->insert('tbl_admin',$arrayvalues);
            if($query){
            	$output=array('output'=>'true');
            }else {
            	$output=array('output'=>'false');
            }
        }

        echo json_encode($output);
	}

	function delete()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete=$this->crud_global->UpdateDefault('tbl_admin',array('status'=>0),array('admin_id'=>$del_id));
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
				$data['row'] = $this->crud_global->ShowTableNew('tbl_admin',array('admin_id'=>$uri3));
				$this->load->view('admin/form_edit',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function edit()
	{
		$output=array('output'=>'false');

		// Get data
		$id=$this->input->post('id');
		$admin_name = $this->input->post('admin_name');
		$admin_group = $this->input->post('admin_group_id');
		$admin_password = $this->input->post('admin_password');
		$admin_email = $this->input->post('admin_email');
		$admin_phone = $this->input->post('admin_phone');
		$admin_photo = $this->input->post('admin_photo');
        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");

        // Update JSON
        // Get Data Old for Filter
        $email_old = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$id),'admin_email');
        if($email_old == $admin_email){
        	$chek_email = false;
        }else{
        	$chek_email = $this->crud_global->CheckNum('tbl_admin',array('admin_email'=>$admin_email,'status !='=>0));
        }
        if($chek_email == true){
        	$output=array('output'=>'Your Email has been registered');
        }else {
        	$arraywhere = array('admin_id'=>$id);
        	if(!empty($admin_password)){
        		$enkrip_pass = $this->enkrip->EnkripPasswordAdmin($admin_password);
	        	$arrayvalues = array(
	        		'admin_name'=>$admin_name,
	        		'admin_group_id'=>$admin_group,
	        		'admin_password'=>$enkrip_pass,
	        		'admin_email'=>$admin_email,
	        		'admin_phone'=>$admin_phone,
	        		'admin_photo'=>$admin_photo,
	        		'status'=>$status,
	        		'update_by'=>$admin_id,
	        		'dateupdate'=>$datecreated
	        	);
        	}else {
	        	$arrayvalues = array(
	        		'admin_name'=>$admin_name,
	        		'admin_group_id'=>$admin_group,
	        		'admin_email'=>$admin_email,
	        		'admin_phone'=>$admin_phone,
	        		'admin_photo'=>$admin_photo,
	        		'status'=>$status,
	        		'update_by'=>$admin_id,
	        		'dateupdate'=>$datecreated
	        	);
        	}
            $query=$this->crud_global->UpdateDefault('tbl_admin',$arrayvalues,$arraywhere);
            if($query){
            	$output=array('output'=>'true');
            }else {
            	$output=array('output'=>'false');
            }
        }
        echo json_encode($output);
	}

	function users()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_admin';
		    $column_order = array('admin_name','admin_email','admin_group_id','status',null); 
		    $column_search = array('admin_name','admin_email','admin_group_id','status');
		    $order = array('datecreated' => 'desc'); // default order

		    $admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$this->session->userdata('admin_id')),'admin_group_id');
		    if($admin_group_id != 1){
		    	$arraywhere = array('status !=' => '0','admin_group_id !='=>'1');
		    }else {
		    	$arraywhere = array('status !=' => '0');
		    }
			$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
	        $data = array();
	        $no = $_POST['start'];
	        $no_list = 0;
	        foreach ($list as $value) {
	            $no++;
	            $no_list++;
	            $row = array();
	            $row[] = $no;
	            $row[] = $value->admin_name;
	            $row[] = $value->admin_email;
	            $row[] = $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$value->admin_group_id),'admin_group_name');
	            $row[] = $this->general->GetStatus($value->status);
				//$row[] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$value->update_by),'admin_name');
				//$row[] = date('d/m/Y H:i:s',strtotime($value->dateupdate));

	            $url_del = site_url('admin/delete/'.$value->admin_id.'');
	            $url_edit = site_url('admin/form_edit/'.$value->admin_id.'');

	            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
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
			$this->load->view('login');
		}
	}

}