<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posted extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','pages/m_pages','DB_model','menu/m_menu','posted/m_post'));
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

			$uri3 = $this->uri->segment(3);
			$table = 'tbl_post';
		    $column_order = array('post','parent_id','status',null); 
		    $column_search = array('post');
		    $order = array('post_id' => 'desc'); // default order 
		    $arraywhere = array('status !=' => '0','post_category_id'=> $uri3);
			$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
	        $data = array();
	        $no = $_POST['start'];
	        $no_list = 0;
	        foreach ($list as $value) {
	            $no++;
	            $no_list++;
	            $row = array();
	            $row[] = $no;
	            $row[] = $value->post;
	            $row[] = $this->m_post->GetParent($value->parent_id);
	            $row[] = $this->m_post->GetAuthor($value->post_id);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_data = site_url('posted/post_data/'.$uri3.'/'.$value->post_id.'');
	            $url_del = site_url('posted/delete/'.$value->post_id.'');

	            $url_edit = site_url('posted/form_edit/'.$uri3.'/'.$value->post_id.'');


	            $btn_data = '<a class="btn btn-sm btn-warning" href="'.$url_data.'"><i class="glyphicon glyphicon-search"></i> Data</a>';

	            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
	            $btn_delete = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

	            //add html for action
	            $row[] = $btn_data." ".$btn_edit." ".$btn_delete;
	 
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

	function table_post_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_post_category';
		    $column_order = array('post_category','order_by','status',null); 
		    $column_search = array('post_category');
		    $order = array('post_category_id' => 'desc'); // default order 
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
	            $row[] = $value->post_category;
	            $row[] = $this->general->GetRadioOrder($value->order_by);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('posted/delete_post_category/'.$value->post_category_id.'');
	            $url_edit = site_url('posted/form_edit_post_category/'.$value->post_category_id.'');

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


	function add_post_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_post->AddPostCategory();
		}else {
			redirect('admin');
		}
	}

	function edit_post_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_post->EditPostCategory();
		}else {
			redirect('admin');
		}
	}

	function delete_post_category()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete = $this->crud_global->UpdateDefault('tbl_post_category',array('status'=>0),array('post_category_id'=>$del_id));
			if($delete){
				$arr = $this->crud_global->ShowTableNew('tbl_post_element',array('post_category_id'=>$del_id));
				if(is_array($arr)){
					foreach ($arr as $key => $row) {
						$this->db->delete('tbl_post_data',array('post_element_id'=> $row->post_element_id));
					}
				}
				$this->crud_global->UpdateDefault('tbl_post',array('status'=>0),array('post_category_id'=>$del_id));
				$this->db->delete('tbl_post_element',array('post_category_id'=> $del_id));

				echo 'success';
			}else {
				echo 'failed';
			}
		}
	}

	function form_edit_post_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['row'] = $this->crud_global->ShowTableNew('tbl_post_category',array('post_category_id'=>$uri3));
				$this->load->view('posted/form_edit_post_category',$data);
			}
		}else {
			redirect('admin');
		}
	}




	function add_post()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_post->AddPost();
		}else {
			redirect('admin');
		}
	}

	function form_edit()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			$uri4=$this->uri->segment(4);
			if(!empty($uri3)){
				$data['post_category_id']=$uri3;
				$data['id']=$uri4;
				$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri4));
				$this->load->view('posted/form_edit',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function edit_post()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_post->EditPost();
		}else {
			redirect('admin');
		}
	}

	function delete()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete = $this->crud_global->UpdateDefault('tbl_post',array('status'=>0),array('post_id'=>$del_id));
			if($delete){
				$this->db->delete('tbl_post_data',array('post_id'=> $del_id));
				echo 'success';
			}else {
				echo 'failed';
			}
		}
	}

	function detail()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['row'] = $this->crud_global->ShowTableNew('tbl_post_category',array('post_category_id'=>$uri3));
				$this->load->view('posted/post_detail',$data);
			}
		}else {
			redirect('admin');
		}
	}


	function post_data()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			$uri4=$this->uri->segment(4);
			if(!empty($uri3)){
				$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
				$data['post_category_id']=$uri3;
				$data['id']=$uri4;
				$data['post']=$this->crud_global->GetField('tbl_post',array('post_id'=>$uri4),'post');
				$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri4));
				
				$this->load->view('posted/post_data',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function post_data_process()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_post->SavePostData();
		}else {
			redirect('admin');
		}
	}

}