<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Producted extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','pages/m_pages','DB_model','menu/m_menu','posted/m_post','producted/m_product','m_product_category'));
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
			$table = 'tbl_product';
		    $column_order = array('tbl_product.product_id','status',null); 
		    $column_search = array('product_name');
		    $order = array('tbl_product.product_id' => 'desc'); // default order 
		    $arraywhere = array('status !=' => '0');

		    $column_select = false;
		    $column_join = false;

			$list = $this->m_product->get_datatables($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join);
	        $data = array();
	        $no = $_POST['start'];
	        $no_list = 0;
	        foreach ($list as $value) {
	            $no++;
	            $no_list++;
	            $row = array();
	            $row[] = $no;
	            $row[] = $this->m_product->GetProductName($value->product_id);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('producted/delete/'.$value->product_id.'');

	            $url_edit = site_url('producted/form_edit/'.$value->product_id.'');

	            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
	            $btn_delete = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

	            //add html for action
	            $row[] = $btn_edit." ".$btn_delete;
	 
	            $data[] = $row;
	        }
	        $output = array(
	                    "draw" => $_POST['draw'],
	                    "recordsTotal" => $this->m_product->count_all($table,$arraywhere),
	                    "recordsFiltered" => $this->m_product->count_filtered($table,$column_order,$column_search,$order,$arraywhere),
	                    "data" => $data,
	                );
	        //output to json format
	        echo json_encode($output);	
		}else {
			redirect('admin');
		}
	}

	function table_product_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_product_category';
		    $column_order = array('tbl_product_category.product_category_id','tbl_product_category.sort_id','tbl_product_category.status',null); 
		    $column_search = array('tbl_product_category_data.product_category_name');
		    $order = array('tbl_product_category.product_category_id' => 'desc'); // default order 

		    // $column_join = array('tbl_product_category_data','tbl_product_category_data.product_category_id = tbl_product_category.product_category_id');
		    $column_select = 'product_category';
		    $column_join = 'tbl_product_category_data.product_category_id,tbl_product_category.product_category_id';

		    $arraywhere = array('status !=' => '0');

			$list = $this->m_product_category->get_datatables($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join);
	        $data = array();
	        $no = $_POST['start'];
	        $no_list = 0;
	        foreach ($list as $value) {
	            $no++;
	            $no_list++;
	            $row = array();
	            $row[] = $no;
	            $row[] = $this->m_product->GetProductCategory($value->product_category_id);
	            $row[] = $this->m_product->GetParent($value->parent_id);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_del = site_url('producted/delete_product_category/'.$value->product_category_id.'');
	            $url_edit = site_url('producted/form_edit_product_category/'.$value->product_category_id.'');

	            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
	            $btn_delete = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

	            //add html for action
	            $row[] = $btn_edit." ".$btn_delete;
	 
	            $data[] = $row;
	        }
	        $output = array(
	                    "draw" => $_POST['draw'],
	                    "recordsTotal" => $this->m_product_category->count_all($table,$arraywhere),
	                    "recordsFiltered" => $this->m_product_category->count_filtered($table,$column_order,$column_search,$order,$arraywhere),
	                    "data" => $data,
	                );
	        //output to json format
	        echo json_encode($output);	
		}else {
			redirect('admin');
		}
	}


	function add_product_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$arr_lang = $this->crud_global->ShowTable('tbl_language',false);
			$output=array('output'=>'false');

			// Get data
			$parent_id = $this->input->post('parent_id');
			$cover_image = $this->input->post('cover_image');
			$thumbnail_image = $this->input->post('thumbnail_image');
			$product_category_url = strtolower(str_replace(' ', '_', $this->input->post('product_category_url')));
			$sort_id = $this->input->post('sort_id');
			$admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");

            $arrayvalues = array(
              	'parent_id'=>$parent_id,
              	'cover_image'=>$cover_image,
              	'thumbnail_image'=>$thumbnail_image,
              	'product_category_url'=>$product_category_url,
        		'sort_id'=>$sort_id,
        		'status'=>$status,
        		'created_by'=>$admin_id,
        		'datecreated'=>$datecreated
        	);

            $query=$this->db->insert('tbl_product_category',$arrayvalues);
            if($query){
            	$id = $this->db->insert_id();

            	foreach ($arr_lang as $key => $value) {

            		$arrayvalues_2 = array(
		              	'product_category_id' => $id,
		              	'product_category_name' => $this->input->post('product_category_name_'.$value->language_id),
		              	'product_category_des' => $this->input->post('product_category_des_1'),
		              	'product_category_meta_title' => $this->input->post('product_category_meta_title_'.$value->language_id),
		              	'product_category_meta_des' => $this->input->post('product_category_meta_des_'.$value->language_id),
		              	'product_category_meta_keywords' => $this->input->post('product_category_meta_keywords_'.$value->language_id),
		              	'language_id' => $value->language_id
		        	);
		            $query_2 = $this->db->insert('tbl_product_category_data',$arrayvalues_2);

		            if($query_2){
		            	$output=array('output'=>'true');
		            }else {
		        		$output=array('output'=>'Error Insert Data');    	
		            }
            	}
            }else {
            	$output=array('output'=>'false');
            }
	        echo json_encode($output);
		}else {
			redirect('admin');
		}
	}

	function edit_product_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$output=array('output'=>'false');
			// Get data
			$id=$this->input->post('id');
			$parent_id = $this->input->post('parent_id');
			$cover_image = $this->input->post('cover_image');
			$thumbnail_image = $this->input->post('thumbnail_image');
			$product_category_url = strtolower(str_replace(' ', '_', $this->input->post('product_category_url')));
			$sort_id = $this->input->post('sort_id');
			$admin_id = $this->session->userdata('admin_id');
	        $status = $this->input->post('status');
	        $datecreated = date("Y-m-d H:i:s");

	        $arraywhere = array('product_category_id'=>$id);
            $arrayvalues = array(
              	'parent_id'=>$parent_id,
              	'cover_image'=>$cover_image,
              	'thumbnail_image'=>$thumbnail_image,
              	'product_category_url'=>$product_category_url,
        		'sort_id'=>$sort_id,
        		'status'=>$status,
        		'updated_by'=>$admin_id,
        		'dateupdated'=>$datecreated
        	);

	        // Update JSON
            $query=$this->crud_global->UpdateDefault('tbl_product_category',$arrayvalues,$arraywhere);

            if($query){
            	$arr_lang = $this->crud_global->ShowTable('tbl_language',false);
            	foreach ($arr_lang as $key => $value) {

            		$arraywhere_2 = array('product_category_data_id'=>$this->input->post('product_category_data_id_'.$value->language_id));

            		$arrayvalues_2 = array(
		              	'product_category_name' => $this->input->post('product_category_name_'.$value->language_id),
		              	'product_category_des' => $this->input->post('product_category_des_1'),
		              	'product_category_meta_title' => $this->input->post('product_category_meta_title_'.$value->language_id),
		              	'product_category_meta_des' => $this->input->post('product_category_meta_des_'.$value->language_id),
		              	'product_category_meta_keywords' => $this->input->post('product_category_meta_keywords_'.$value->language_id),
		              	'language_id' => $value->language_id
		        	);
		            $query_2 = $this->crud_global->UpdateDefault('tbl_product_category_data',$arrayvalues_2,$arraywhere_2);

		            if($query_2){
		            	$output=array('output'=>'true');
		            }else {
		        		$output=array('output'=>'Error Insert Data');    	
		            }
            	}
            }else {
            	$output=array('output'=>'false');
            }
	        echo json_encode($output);
		}else {
			redirect('admin');
		}
	}

	function delete_product_category()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete = $this->crud_global->UpdateDefault('tbl_product_category',array('status'=>0),array('product_category_id'=>$del_id));
			if($delete){
				echo 'success';
			}else {
				echo 'failed';
			}
		}
	}

	function form_add_product_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
			$this->load->view('producted/add_product_category',$data);
		}else {
			redirect('admin');
		}
	}

	function form_edit_product_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
				$data['data'] = $this->crud_global->ShowTableNew('tbl_product_category',array('product_category_id'=>$uri3));
				$this->load->view('producted/form_edit_product_category',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function form_add_product()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
			$this->load->view('producted/add_product',$data);
		}else {
			redirect('admin');
		}
	}


	function add_product()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_product->AddProduct();
		}else {
			redirect('admin');
		}
	}

	function form_edit()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false);
				$data['data'] = $this->crud_global->ShowTableDefault('tbl_product',array('product_id'=>$uri3));
				$this->load->view('producted/form_edit',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function edit_product()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_product->EditProduct();
		}else {
			redirect('admin');
		}
	}

	function delete()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete=$this->crud_global->UpdateDefault('tbl_product',array('status'=>0),array('product_id'=>$del_id));
			if($delete){
				// $this->db->delete('tbl_post_data',array('post_id'=> $del_id));
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

}