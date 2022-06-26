<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','m_pages','DB_model','menu/m_menu'));
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
			$table = 'tbl_pages';
		    $column_order = array('pages','parent_id','status',null); 
		    $column_search = array('pages');
		    $order = array('pages_id' => 'desc'); // default order 
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
	            $row[] = $value->pages;
	            $row[] = $this->m_pages->GetParent($value->parent_id);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('pages/delete/'.$value->pages_id.'');
	            $url_edit = site_url('pages/form_edit/'.$value->pages_id.'');

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
			redirect('admin');
		}
	}


	function add()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_pages->AddProcess();
		}else {
			redirect('admin');
		}
	}

	function delete()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete=$this->crud_global->UpdateDefault('tbl_pages',array('status'=>0),array('pages_id'=>$del_id));
			if($delete){
				$arr = $this->crud_global->ShowTableNew('tbl_pages_element',array('pages_id'=>$del_id));
				if(is_array($arr)){
					foreach ($arr as $key => $row) {
						$this->db->delete('tbl_pages_data',array('pages_element_id'=> $row->pages_element_id));
					}
				}
				$this->db->delete('tbl_pages_element',array('pages_id'=> $del_id));
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
				$data['row'] = $this->crud_global->ShowTableNew('tbl_pages',array('pages_id'=>$uri3));
				$this->load->view('pages/form_edit',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function edit()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_pages->EditProcess();
		}else {
			redirect('admin');
		}
	}


	function table_pages_data()
	{
		$table = 'tbl_pages';
	    $column_order = array('pages',null); 
	    $column_search = array('pages');
	    $order = array('pages_id' => 'desc'); // default order 
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
            $row[] = $value->pages;

            // $url_del = site_url('ajax_admin/pages/delete/'.$value->pages_id.'');
            $url_edit = site_url('pages/pages_detail/'.$value->pages_id.'');

            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            //add html for action
            $row[] = $btn_edit;
 
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
	}


	function pages_detail()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['row'] = $this->crud_global->ShowTableNew('tbl_pages',array('pages_id'=>$uri3));
				$data['pages_name'] = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$uri3),'pages');
				$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
				$this->load->view('pages/pages_detail',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function pages_data_process()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_pages->SavePagesData();
		}else {
			redirect('admin');
		}	
	}


	function table_pages_position()
	{
		$table = 'tbl_pages_position_category';
	    $column_order = array('pages_position_category','status',null); 
	    $column_search = array('pages_position_category');
	    $order = array('pages_position_category_id' => 'desc'); // default order 
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
            $row[] = $value->pages_position_category;
            $row[] = $this->general->GetStatus($value->status);

            $url_del = site_url('pages/delete_pages_position/'.$value->pages_position_category_id.'');
            $url_edit = site_url('pages/form_edit_pages_position/'.$value->pages_position_category_id.'');
            $url_settings = site_url('pages/setting_pages_position/'.$value->pages_position_category_id.'');

            $btn_settings = '<a class="btn btn-sm btn-warning" href="'.$url_settings.'"><i class="glyphicon glyphicon-pencil"></i> Settings</a>';
	        $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
	        $btn_delete = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            //add html for action
            $row[] = $btn_settings." ".$btn_edit." ".$btn_delete;
 
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
	}


	function add_pages_position()
	{
		$output=array('output'=>'false');
		// Get data
		$pages_position_category = $this->input->post('pages_position_category');
		$pages_position_category_alias = strtolower(str_replace(' ', '_', $pages_position_category));
        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");

        // insert JSON
		$check_pages_position_category = $this->crud_global->CheckNum('tbl_pages_position_category',array('pages_position_category'=>$pages_position_category,'status !='=>0));

        if($check_pages_position_category == true){
        	$output=array('output'=>'Your Name has been registered');
        }else {
        	$arrayvalues = array(
        		'pages_position_category'=>$pages_position_category,
        		'pages_position_category_alias'=>$pages_position_category_alias,
        		'status'=>$status,
        		'created_by'=>$admin_id,
        		'datecreated'=>$datecreated
        		);

            $query=$this->db->insert('tbl_pages_position_category',$arrayvalues);
            if($query){
            	$output=array('output'=>'true');
            }else {
            	$output=array('output'=>'false');
            }
        }
        echo json_encode($output);
	}


	function delete_pages_position()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete=$this->crud_global->UpdateDefault('tbl_pages_position_category',array('status'=>0),array('pages_position_category_id'=>$del_id));
			if($delete){
				$this->db->delete('tbl_pages_position',array('pages_position_category_id'=> $del_id));
				echo 'success';
			}else {
				echo 'failed';
			}
		}
	}

	function form_edit_pages_position()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['row'] = $this->crud_global->ShowTableNew('tbl_pages_position_category',array('pages_position_category_id'=>$uri3));
				$this->load->view('pages/form_edit_pages_position',$data);
			}
		}else {
			redirect('admin');
		}
	}


	function edit_pages_position()
	{
		$output=array('output'=>'false');
		// Get data
		$id=$this->input->post('id');
		$pages_position_category = $this->input->post('pages_position_category');
		$pages_position_category_alias = strtolower(str_replace(' ', '_', $pages_position_category));
        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");

        // Update JSON
        // Get Data Old for Filter
        $pages_position_category_old = $this->crud_global->GetField('tbl_pages_position_category',array('pages_position_category_id'=>$id),'pages_position_category');
        if($pages_position_category_old != $pages_position_category){
        	$chek_pages_position_category = $this->crud_global->CheckNum('tbl_pages_position_category',array('pages_position_category'=>$pages_position_category,'status !='=>0));
        }else{
        	$chek_pages_position_category = false;
        }
        
        if($chek_pages_position_category == true){
        	$output=array('output'=>'Order has been registered');
        }else {
        	$arraywhere = array('pages_position_category_id'=>$id);
        	$arrayvalues = array(
        		'pages_position_category'=>$pages_position_category,
        		'pages_position_category_alias'=>$pages_position_category_alias,
        		'status'=>$status,
        		'updated_by'=>$admin_id,
        		'dateupdated'=>$datecreated
        		);
            $query=$this->crud_global->UpdateDefault('tbl_pages_position_category',$arrayvalues,$arraywhere);
            if($query){
            	$output=array('output'=>'true');
            }else {
            	$output=array('output'=>'false');
            }
        }
        echo json_encode($output);
	}

	function setting_pages_position()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['row'] = $this->crud_global->ShowTableNoOrder('tbl_pages_position_category',array('pages_position_category_id'=>$uri3));
				$this->load->view('pages/setting_pages_position',$data);

			}
		}else {
			redirect('admin');
		}
	}

	function load_menu()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$this->load->view('pages/load_menu',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function pages_settings_process()
	{
		$output = array('output'=>'false');
		$pages_type = $this->input->post('pages_type');
		$pages_position_category_id = $this->input->post('pages_position_category_id');

		$output_json = false;
		if($pages_type == 'pages'){
			$pages_id = $this->input->post('pages_id[]');

			if(is_array($pages_id)){
				$no=1;
				foreach ($pages_id as $key => $row) {
					$pages_label = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row),'pages');
					$arrayvalues = array(
		              	'pages_type'=>$pages_type,
		              	'pages_position_category_id' => $pages_position_category_id,
		              	'pages_url' => $row,
		              	'pages_label' => $pages_label,
		              	'sort_id' => $no,
		        	);
		            $query=$this->db->insert('tbl_pages_position',$arrayvalues);
		            if($query){
		            	$output_json = true;
		            }else {
		            	$output_json = false;
		            }
		            $no++;
				}
			}
		}else if($pages_type == 'custom_link'){
			$pages_label = $this->input->post('pages_label');
			$pages_url = $this->input->post('pages_url');
			$arrayvalues = array(
              	'pages_type'=>$pages_type,
              	'pages_position_category_id' => $pages_position_category_id,
              	'pages_url' => $pages_url,
              	'pages_label' => $pages_label,
        	);
            $query=$this->db->insert('tbl_pages_position',$arrayvalues);
            if($query){
            	$output_json = true;
            }else {
            	$output_json = false;
            }
		}

		if($output_json == true){
			$output = array('output'=>'true');
		}else {
			$output = array('output'=>'false');
		}

		echo json_encode($output);
	}


	function delete_menu()
	{
		$output = array('output'=>'false');
		$id = $this->input->post('id');
		// // $this->m_pages->EditProcess();
		$delete = $this->crud_global->deleteData('tbl_pages_position',array('pages_position_id'=>$id));

	    if($delete){
	    	$output = array('output'=>'true');
	    }else {
	    	$output = array('output'=>'false');
	    }

	    echo json_encode($output);
	}

	function sort_menu()
	{
		$item = $this->input->post('item');
		// print_r($item);
		$i = 1;
		$output = 'error';
		foreach ($item as $value) {
		    // Execute statement:
		    // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value
		    $update = $this->crud_global->UpdateDefault('tbl_pages_position',array('sort_id'=>$i),array('pages_position_id'=>$value));
		    if($update){
		    	$output = 'true';
		    }else {
		    	$output = 'error';
		    }
		    $i++;
		}
		echo $output;
	}

}