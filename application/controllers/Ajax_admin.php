<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_admin extends CI_Controller {

	public function __construct()
	{
	   		
        parent::__construct();
        // Your own constructor code
        // $this->load->model(array('m_admin','admin_templates','crud_global','DB_model','m_pages','m_global','m_product'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('general');
		$this->load->library('session');
		$this->load->library('waktu');
		date_default_timezone_set('Asia/Jakarta');
	}

	function filter_ajax()
	{

		$check = $this->m_admin->check_login();
		if($check == true){
			$data = $this->input->post('data_filter');
			$data_table = $this->input->post('data_table');
			$data_field = $this->input->post('data_field');

			if(!empty($data) && !empty($data_table) && !empty($data_field)){

				$this->db->select("*");
				$this->db->where($data_field,$data);
				$query = $this->db->get($data_table);
				if($query->num_rows() > 0){
					$output = array('output'=>'false');
				}else{
					$output = array('output'=>'true');
				}
			}else {
				$output = array('output'=>'false');
			}

			echo json_encode($output);
		}else {
			$this->load->view('back/login');
		}
	}

	function contact()
	{
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				switch($uri3) {
					case 'detail' :
					$uri4=$this->uri->segment(4);
					$data['data_detail'] = $this->crud_global->ShowTableNoOrderStatus('tbl_contact',array('contact_id'=>$uri4));
					$this->load->view('back/ajax_detail/detail_contact',$data);
					break;

					case 'data_table' :

					$table = 'tbl_contact';
				    $column_order = array('contact_name','contact_email','ip_address','datecreated'); 
				    $column_search = array('contact_name','contact_email','ip_address');
				    $order = array('datecreated' => 'desc'); // default order 
				    $arraywhere = false;
					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            $row = array();
			            $row[] = $no;
			            $row[] = $value->contact_name;
			            $row[] = $value->contact_email;
			            $row[] = $value->ip_address;
			            $row[] = $value->datecreated;
			 
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
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function language()
	{
		$this->load->model('m_language');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_language';
				    $column_order = array('language_title','language_code','order_id','status',null); 
				    $column_search = array('language_title');
				    $order = array('order_id' => 'desc'); // default order 
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
			            $row[] = $value->language_title;
			            $row[] = '<i class="flagstrap-icon flagstrap-'.$value->language_code.'" style="margin-right: 10px;"></i>'. $value->language_code;
			            $row[] = $value->order_id;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/language/delete/'.$value->language_id.'');
			            $url_edit = site_url('ajax_admin/language/form_edit/'.$value->language_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_language');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$language = $this->input->post('language');
					$lang_code = strtolower($this->input->post('country'));
					$order_id = $this->input->post('order_id');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arrayvalues = array(
		        		'language_title'=>$language,
		        		'language_code'=>$lang_code,
		        		'order_id'=>$order_id,
		        		'status'=>$status,
		        		'create_by'=>$admin_id,
		        		'datecreated'=>$datecreated
		        		);

		            $query=$this->db->insert('tbl_language',$arrayvalues);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTable('tbl_language',array('language_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_language',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id = $this->input->post('id');
					$language = $this->input->post('language');
					$lang_code = strtolower($this->input->post('country'));
					$order_id = $this->input->post('order_id');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arraywhere = array('language_id'=>$id);
		        	$arrayvalues = array(
		        		'language_title'=>$language,
		        		'language_code'=>$lang_code,
		        		'order_id'=>$order_id,
		        		'status'=>$status,
		        		'update_by'=>$admin_id,
		        		'dateupdate'=>$datecreated
		        		);
		            $query=$this->crud_global->UpdateDefault('tbl_language',$arrayvalues,$arraywhere);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_language',array('status'=>0),array('language_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}


	function themes_options()
	{
		$this->load->model('m_themes');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				switch($uri3) {
					case 'edit' :
					$this->m_themes->EditProcess();
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}


	function post_category_new()
	{
		$this->load->model('m_post');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :

					$table = 'tbl_post_category_new';
				    $column_order = array('post_category_new','order_by','order_id','status',null); 
				    $column_search = array('post_category_new');
				    $order = array('post_category_new_id' => 'desc'); // default order 
				    $arraywhere = array('status !=' => '0');
					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            // $order_by = $this->general->GetOrderBy($value->order_by);
			            $row = array();
			            $row[] = $no;
			            $row[] = $value->post_category_new;
			            $row[] = $this->general->GetOrderBy($value->order_by);
			            $row[] = $value->order_id;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/post_category_new/delete/'.$value->post_category_new_id.'');
			            $url_edit = site_url('ajax_admin/post_category_new/form_edit/'.$value->post_category_new_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_post_category_new');
					break;

					case 'add' :
					$this->m_post_category->AddNew();
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_post_category_new',array('status'=>0),array('post_category_new_id'=>$del_id));
						if($delete){
							$this->db->delete('tbl_post_element',array('post_category_new_id'=>$del_id));
							$this->crud_global->UpdateDefault('tbl_post_new',array('status'=>0),array('post_category_new_id'=>$del_id));
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTable('tbl_post_category_new',array('post_category_new_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_post_category_new',$data_id);
					}
					break;

					case 'edit' :
					$this->m_post_category->EditNew();
					break;

					
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}



	function post_new()
	{
		$this->load->model('m_post');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {

					case 'data_table' :
					$uri4 = $this->uri->segment(4);
					// $data['menu'] = $this->crud_global->ShowTable('tbl_post_new',array('post_category_new_id'=>$uri4),'desc');

					$table = 'tbl_post_new';
				    $column_order = array('post_new','parent_id','order_id','status',null); 
				    $column_search = array('post_new');
				    $order = array('post_new_id' => 'desc'); // default order 
				    $arraywhere = array('status !=' => '0','post_category_new_id'=>$uri4);
					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            // $order_by = $this->general->GetOrderBy($value->order_by);
			            $row = array();
			            $row[] = $no;
			            $row[] = $value->post_new;
			            $row[] = $this->m_post->GetParent($value->parent_id);
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/post_new/delete/'.$value->post_new_id.'');
			            $url_edit = site_url('ajax_admin/post_new/form_edit/'.$value->post_new_id.'');
			            $url_update = site_url('admin/post/post_data/'.$value->post_new_id.'');

			            $btn_update_data = '<a class="btn btn-sm btn-warning" href="'.$url_update.'"><i class="glyphicon glyphicon-search"></i> Data</a>';

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
			            //add html for action
			            $row[] = $btn_update_data.' '.$btn_edit." ".$btn_delete;
			 
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

					break;

					case 'form_add' :
					$uri4 = $this->uri->segment(4);
					if($uri4){
						$data['id'] = $uri4;
						$data['order_by'] = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_id'=>$uri4),'order_by');
						$data['post_tags'] = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_id'=>$uri4),'post_tags');
						$this->load->view('back/ajax_form/add_post_new',$data);
					}
					break;

					case 'add' :
					$this->m_post->AddNew();
					break;

					case 'edit' :
					$this->m_post->EditNew();
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_post_new',array('status'=>0),array('post_new_id'=>$del_id));
						if($delete){
							$this->db->delete('tbl_post_data',array('post_new_id'=>$del_id));
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$post_category_new_id = $this->crud_global->GetField('tbl_post_new',array('post_new_id'=>$uri4),'post_category_new_id');

						$data_id['data_edit'] = $this->crud_global->ShowTable('tbl_post_new',array('post_new_id'=>$uri4));
						$data_id['order_by'] = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_id'=>$post_category_new_id),'order_by');
						$data_id['post_category_new_id'] = $post_category_new_id;

						$data_id['post_tags'] = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_id'=>$post_category_new_id),'post_tags');
						$this->load->view('back/ajax_form/edit_post_new',$data_id);
					}
					break;

					case 'post_detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$post_category_new_id = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_id'=>$uri4),'post_category_new_id');
						$data_id['post_category_new_id'] = $post_category_new_id;
						$data_id['data_detail'] = $this->crud_global->ShowTable('tbl_post_new',array('post_new_id'=>$uri4));
						$data_id['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false);

						$this->load->view('back/ajax_detail/detail_post',$data_id);
					}
					break;

					case 'upload_photo' :
					$this->crud_global->UploadAjax('post');
					break;
					
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function menu()
	{
		$this->load->model('DB_model');

		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_menu';
				    $column_order = array('menu_name','menu_icon','file_view','order_id',null); 
				    $column_search = array('menu_name','menu_icon','file_view');
				    $order = array('menu_id' => 'desc'); // default order 
				    $arraywhere = array('status !=' => '0');
					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $person) {
			            $no++;
			            $no_list++;
			            $row = array();
			            $row[] = $no;
			            $row[] = $person->menu_name;
			            $row[] = $person->menu_icon;
			            $row[] = $person->file_view;
			            $row[] = $person->order_id;

			            $url_del = site_url('ajax_admin/menu/delete/'.$person->menu_id.'');
			            $url_edit = site_url('ajax_admin/menu/form_edit/'.$person->menu_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;



					case 'form_add' :
					$this->load->view('back/ajax_form/add_menu');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$menu_name = $this->input->post('menu_name');
					$parent_id = $this->input->post('parent_menu');
					$menu_alias = strtolower(str_replace(' ', '_', $menu_name));
					$menu_icon = $this->input->post('menu_icon');
					$file_view = $this->input->post('file_view');
			        $order_id = $this->input->post('order_id');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        if($menu_name !== '' && $parent_id !== '' && $menu_icon !== '' && $file_view !== '' && $order_id !== '' && $status !== ''){
			        	// insert JSON
						$chek_menu = $this->crud_global->CheckNum('tbl_menu',array('menu_name'=>$menu_name,'status !='=>0));
						$chek_order = $this->crud_global->CheckNumOrderParent('tbl_menu',array('order_id'=>$order_id),$parent_id);
				        
				        if($chek_menu == true){
				        	$output=array('output'=>'Menu Name has been registered');
				        }else 
				        if($chek_order == true){
				        	$output=array('output'=>'Order has been registered');
				        }else {

				        	$arrayvalues = array(
				        		'menu_name'=>$menu_name,
				        		'menu_alias'=>$menu_alias,
				        		'menu_icon'=>$menu_icon,
				        		'file_view'=>$file_view,
				        		'parent_id'=>$parent_id,
				        		'order_id'=>$order_id,
				        		'status'=>$status,
				        		'create_by'=>$admin_id,
				        		'datecreated'=>$datecreated
				        		);

				            $query=$this->db->insert('tbl_menu',$arrayvalues);
				            if($query){
				            	$output=array('output'=>'true');
				            }else {
				            	$output=array('output'=>'Failed Insert to Database');
				            }
				        }
			        }else {
			        	$output=array('output'=>'Please completed the form');
			        }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_menu',array('status'=>0),array('menu_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTable('tbl_menu',array('menu_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_menu',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id=$this->input->post('id');
					$menu_name = $this->input->post('menu_name');
					$menu_icon = $this->input->post('menu_icon');
					$file_view = $this->input->post('file_view');
					$parent_id = $this->input->post('parent_menu');
					$menu_alias = strtolower(str_replace(' ', '_', $menu_name));
			        $order_id = $this->input->post('order_id');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");
			        // Update JSON
			        // Get Data Old for Filter
			        $menu_old = $this->crud_global->GetField('tbl_menu',array('menu_id'=>$id),'menu_name');
			        if($menu_old != $menu_name){
			        	$chek_menu = $this->crud_global->CheckNum('tbl_menu',array('menu_name'=>$menu_name,'status !='=>0));
			        }else{
			        	$chek_menu = false;
			        }

			        $order_old = $this->crud_global->GetField('tbl_menu',array('menu_id'=>$id),'order_id');
			        if($order_old != $order_id){
			        	$chek_order = $this->crud_global->CheckNumOrderParent('tbl_menu',array('order_id'=>$order_id),$parent_id);
			        }else{
			        	$chek_order = false;
			        }
			        
			        if($chek_menu == true){
			        	$output=array('output'=>'Menu Name has been registered');
			        }else 
			        if($chek_order == true){
			        	$output=array('output'=>'Order has been registered');
			        }else {
			        	$arraywhere = array('menu_id'=>$id);
			        	$arrayvalues = array(
			        		'menu_name'=>$menu_name,
			        		'menu_icon'=>$menu_icon,
			        		'file_view'=>$file_view,
			        		'menu_alias'=>$menu_alias,
			        		'parent_id'=>$parent_id,
			        		'order_id'=>$order_id,
			        		'status'=>$status,
			        		'update_by'=>$admin_id,
			        		'dateupdate'=>$datecreated
			        		);
			            $query=$this->crud_global->UpdateDefault('tbl_menu',$arrayvalues,$arraywhere);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function admin()
	{
		
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_admin';
				    $column_order = array('admin_name','admin_email','admin_group_id','status',null); 
				    $column_search = array('admin_name','admin_email','admin_group_id','status');
				    $order = array('admin_id' => 'desc'); // default order 
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
			            $row[] = $value->admin_name;
			            $row[] = $value->admin_email;
			            $row[] = $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$value->admin_group_id),'admin_group_name');
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/admin/delete/'.$value->admin_id.'');
			            $url_edit = site_url('ajax_admin/admin/form_edit/'.$value->admin_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_admin');
					break;

					case 'upload_photo':
					$this->crud_global->UploadAjax('back');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$admin_name = $this->input->post('admin_name');
					$admin_group = $this->input->post('admin_group');
					$admin_password = $this->input->post('admin_password');
					$admin_co_password = $this->input->post('admin_co_password');
					$admin_email = $this->input->post('admin_email');
					$admin_phone = $this->input->post('admin_phone');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        if($admin_name !== '' && $admin_group !== '' && $admin_password !== '' && $admin_co_password !== '' && $admin_email !== '' && $admin_phone !== '' && $status !== ''){
			        	// insert JSON
						$chek_email = $this->crud_global->CheckNum('tbl_admin',array('admin_email'=>$admin_email,'status !='=>0));
				        
				        if($admin_password != $admin_co_password){
				        	$output=array('output'=>'Please Check Your Password or Confirm Password');
				        }else
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
			        }else {
			        	$output=array('output'=>'Please Complete The Form !');
			        }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_admin',array('status'=>0),array('admin_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_admin',array('admin_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_admin',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');

					// Get data
					$id=$this->input->post('id');
					$admin_name = $this->input->post('admin_name');
					$admin_group = $this->input->post('admin_group');
					$admin_password = $this->input->post('admin_password');
					$admin_co_password = $this->input->post('admin_co_password');
					$admin_email = $this->input->post('admin_email');
					$admin_phone = $this->input->post('admin_phone');
					// $data_image = $this->input->post('data_image');
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
			        	$enkrip_pass = $this->enkrip->EnkripPasswordAdmin($admin_password);
			        	$arrayvalues = array(
			        		'admin_name'=>$admin_name,
			        		'admin_group_id'=>$admin_group,
			        		'admin_password'=>$enkrip_pass,
			        		'admin_email'=>$admin_email,
			        		'admin_phone'=>$admin_phone,
			        		// 'admin_photo'=>$data_image,
			        		'status'=>$status,
			        		'update_by'=>$admin_id,
			        		'dateupdate'=>$datecreated
			        	);
			            $query=$this->crud_global->UpdateDefault('tbl_admin',$arrayvalues,$arraywhere);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;

					case 'change_photo':

					$output=array('output'=>'false');

					$id=$this->input->post('id');
					$admin_photo=$this->input->post('admin_photo');
					$admin_id = $this->session->userdata('admin_id');
			        $datecreated = date("Y-m-d H:i:s");

			        if($id !== '' && $admin_photo !== ''){
			        	$arraywhere = array('admin_id'=>$id);
			        	$arrayvalues = array(
			        		'admin_photo'=>$admin_photo,
			        		'update_by'=>$admin_id,
			        		'dateupdate'=>$datecreated
			        	);
			            $query = $this->crud_global->UpdateDefault('tbl_admin',$arrayvalues,$arraywhere);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'Error ! Failed Update Data');
			            }
			        }else {
			        	$output=array('output'=>'Error ! Data Not Found');
			        }

					echo json_encode($output);
					break;

					case 'detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_detail'] = $this->crud_global->ShowTableNoOrder('tbl_admin',array('admin_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_admin',$data_id);
					}
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function admin_access()
	{
		
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){

				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_admin_group';
				    $column_order = array('admin_group_name','datecreated','status',null); 
				    $column_search = array('admin_group_name');
				    $order = array('admin_group_id' => 'desc'); // default order 
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
			            $row[] = $value->datecreated;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/admin_access/delete/'.$value->admin_group_id.'');
			            $url_edit = site_url('ajax_admin/admin_access/form_edit/'.$value->admin_group_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_group_admin');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$group_admin_name = $this->input->post('admin_group_name');
					$menu_id = $this->input->post('menu_id[]');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$chek_menu = $this->crud_global->CheckNum('tbl_admin_group',array('admin_group_name'=>$group_admin_name,'status !=',0));
			        
			        if($chek_menu == true){
			        	$output=array('output'=>'Gruop Admin has been registered');
			        }else {
			        	$arrayvalues = array('admin_group_name'=>$group_admin_name,'status'=>$status,'create_by'=>$admin_id,'datecreated'=>$datecreated);
			            $query=$this->db->insert('tbl_admin_group',$arrayvalues);
			            if($query){
			            	$admin_group_id = $this->db->insert_id();
			            	// Insert to Admin Access
			            	foreach ($menu_id as $key_menu => $row) {

			            		$actions_menu = $this->input->post('actions_menu_'.$row.'[]');

			            		$create_action = 0;
			            		$update_action = 0;
			            		$delete_action = 0;
			            		if(is_array($actions_menu)){
			            			foreach ($actions_menu as $key => $value) {
				            			if($value == 1){
				            				$create_action = 1;
				            			}else
				            			if($value == 2){
				            				$update_action = 1;
				            			}else
				            			if($value == 3){
				            				$delete_action = 1;
				            			}
				            		}
			            		}

			            		$arrayvalues_access = array(
			            				'admin_group_id'=>$admin_group_id,
			            				'menu_id'=>$row,
			            				'create_action'=>$create_action,
			            				'update_action'=>$update_action,
			            				'delete_action'=>$delete_action
			            			);
			            		$this->db->insert('tbl_admin_access',$arrayvalues_access);
			            	}
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_admin_group',array('status'=>0),array('admin_group_id'=>$del_id));
						$delete_2=$this->crud_global->UpdateDefault('tbl_admin_access',array('status'=>0),array('admin_group_id'=>$del_id));
						if($delete && $delete_2){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_admin_group',array('admin_group_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_group_admin',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id=$this->input->post('id');
					$group_admin_name = $this->input->post('admin_group_name');
					$menu_id = $this->input->post('menu_id[]');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // Update JSON
			        // Get Data Old for Filter
			        $menu_old = $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$id),'admin_group_name');
			        if($menu_old != $group_admin_name){
			        	$chek_menu = $this->crud_global->CheckNum('tbl_admin_group',array('admin_group_name'=>$group_admin_name));
			        }else{
			        	$chek_menu = false;
			        }
			        
			        if($chek_menu == true){
			        	$output=array('output'=>'Gruop Admin has been registered');
			        }else {

			        	$arrayvalues = array(
			        		'admin_group_name'=>$group_admin_name,
			        		'status'=>$status,
			        		'update_by'=>$admin_id,
			        		'dateupdate'=>$datecreated
			        		);
			        	$arraywhere = array('admin_group_id'=>$id);

			            $query=$this->crud_global->UpdateDefault('tbl_admin_group',$arrayvalues,$arraywhere);
			            if($query){

			            	$count_menu_id_old = count($this->crud_global->ShowTableDefault('tbl_admin_access',array('admin_group_id'=>$id)));

			            	$count_menu_now = count($menu_id);
			            	if(is_array($count_menu_id_old)){
			            		if($count_menu_now > $count_menu_id_old){

				            		$this->db->delete('tbl_admin_access',array('admin_group_id'=> $id));

				            		foreach ($menu_id as $key_menu => $row) {
					            		$actions_menu = $this->input->post('actions_menu_'.$row.'[]');
					            		$create_action = 0;
					            		$update_action = 0;
					            		$delete_action = 0;
					            		if(is_array($actions_menu)){
					            			foreach ($actions_menu as $key => $value) {
						            			if($value == 1){
						            				$create_action = 1;
						            			}else
						            			if($value == 2){
						            				$update_action = 1;
						            			}else
						            			if($value == 3){
						            				$delete_action = 1;
						            			}
						            		}
					            		}else {
					            			$create_action = 0;
						            		$update_action = 0;
						            		$delete_action = 0;
					            		}

					            		$arrayvalues_access = array(
				            				'admin_group_id'=>$id,
				            				'menu_id'=>$row,
				            				'create_action'=>$create_action,
				            				'update_action'=>$update_action,
				            				'delete_action'=>$delete_action
				            			);
				            			$this->db->insert('tbl_admin_access',$arrayvalues_access);
					            	}
				            	}else {
				            		foreach ($menu_id as $key_menu => $row) {

					            		$actions_menu = $this->input->post('actions_menu_'.$row.'[]');
					            		$create_action = 0;
					            		$update_action = 0;
					            		$delete_action = 0;
					            		if(is_array($actions_menu)){
					            			foreach ($actions_menu as $key => $value) {
						            			if($value == 1){
						            				$create_action = 1;
						            			}else
						            			if($value == 2){
						            				$update_action = 1;
						            			}else
						            			if($value == 3){
						            				$delete_action = 1;
						            			}
						            		}
					            		}else {
					            			$create_action = 0;
						            		$update_action = 0;
						            		$delete_action = 0;
					            		}

					            		$arrayvalues_access = array(
					            				'create_action'=>$create_action,
					            				'update_action'=>$update_action,
					            				'delete_action'=>$delete_action
					            			);
					            		$arraywhere_access = array('menu_id'=>$row,'admin_group_id'=>$id);
					            		$this->crud_global->UpdateDefault('tbl_admin_access',$arrayvalues_access,$arraywhere_access);
					            	}
					            }
			            	}else {

			            		$this->db->delete('tbl_admin_access',array('admin_group_id'=> $id));

			            		foreach ($menu_id as $key_menu => $row) {
					            		$actions_menu = $this->input->post('actions_menu_'.$row.'[]');
					            		$create_action = 0;
					            		$update_action = 0;
					            		$delete_action = 0;
					            		if(is_array($actions_menu)){
					            			foreach ($actions_menu as $key => $value) {
						            			if($value == 1){
						            				$create_action = 1;
						            			}else
						            			if($value == 2){
						            				$update_action = 1;
						            			}else
						            			if($value == 3){
						            				$delete_action = 1;
						            			}
						            		}
					            		}else {
					            			$create_action = 0;
						            		$update_action = 0;
						            		$delete_action = 0;
					            		}
					            		$arrayvalues_access = array(
				            				'admin_group_id'=>$id,
				            				'menu_id'=>$row,
				            				'create_action'=>$create_action,
				            				'update_action'=>$update_action,
				            				'delete_action'=>$delete_action
				            			);
				            			$this->db->insert('tbl_admin_access',$arrayvalues_access);
					            	}
			            	}
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function pages_position_category()
	{
		
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :
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

			            $url_del = site_url('ajax_admin/pages_position_category/delete/'.$value->pages_position_category_id.'');
			            $url_edit = site_url('ajax_admin/pages_position_category/form_edit/'.$value->pages_position_category_id.'');
			            $url_settings = site_url('ajax_admin/pages_position_category/form_settings/'.$value->pages_position_category_id.'');

			            $btn_settings = '<a class="btn btn-sm btn-warning" href="'.$url_settings.'"><i class="glyphicon glyphicon-pencil"></i> Settings</a>';
			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_pages_position_category');
					break;

					case 'add' :
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
			        		'create_by'=>$admin_id,
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
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_pages_position_category',array('status'=>0),array('pages_position_category_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_pages_position_category',array('pages_position_category_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_pages_position_category',$data_id);
					}
					break;

					case 'form_settings' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_pages_position_category',array('pages_position_category_id'=>$uri4));
						$this->load->view('back/ajax_form/form_settings_pages',$data_id);
					}
					break;
					
					case 'edit' :
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
			        		'update_by'=>$admin_id,
			        		'dateupdate'=>$datecreated
			        		);
			            $query=$this->crud_global->UpdateDefault('tbl_pages_position_category',$arrayvalues,$arraywhere);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function element_input()
	{
		
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :

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

			            $url_del = site_url('ajax_admin/element_input/delete/'.$value->element_input_id.'');
			            $url_edit = site_url('ajax_admin/element_input/form_edit/'.$value->element_input_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_element_input');
					break;

					case 'add' :
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
			        		'create_by'=>$admin_id,
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
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_element_input',array('status'=>0),array('element_input_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_element_input',array('element_input_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_element_input',$data_id);
					}
					break;
					
					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id=$this->input->post('id');
					$element_input = $this->input->post('element_input');
					$element_input_type = $this->input->post('element_input_type');
					$element_input_value = $this->input->post('element_input_value[]');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // Update JSON
			        // Get Data Old for Filter
			        $element_input_old = $this->crud_global->GetField('tbl_element_input',array('element_input_id'=>$id),'element_input');
			        if($element_input_old != $element_input){
			        	$check_element_input = $this->crud_global->CheckNum('tbl_element_input',array('element_input'=>$element_input,'status !='=>0));
			        }else{
			        	$check_element_input = false;
			        }
			        
			        if($check_element_input == true){
			        	$output=array('output'=>'Element Input has been registered');
			        }else {
			        	$arraywhere = array('element_input_id'=>$id);

			        	if(is_array($element_input_value)){
			        		$element_input_value = implode(",", $element_input_value);
			        	}else {
			        		$element_input_value = false;
			        	}
			        	$arrayvalues = array(
			        		'element_input'=>$element_input,
			        		'element_input_type'=>$element_input_type,
			        		'element_input_value'=>$element_input_value,
			        		'status'=>$status,
			        		'update_by'=>$admin_id,
			        		'dateupdate'=>$datecreated
			        		);
			            $query=$this->crud_global->UpdateDefault('tbl_element_input',$arrayvalues,$arraywhere);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function pages()
	{
		$this->load->model('m_pages');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :
					
					$table = 'tbl_pages';
				    $column_order = array('pages','parent_id','pages_template','status',null); 
				    $column_search = array('pages','pages_template');
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
			            $row[] = $value->pages_template;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/pages/delete/'.$value->pages_id.'');
			            $url_edit = site_url('ajax_admin/pages/form_edit/'.$value->pages_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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

					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_pages');
					break;

					case 'add' :
					$output=array('output'=>'false');
					$this->m_pages->AddProcess();
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_pages',array('status'=>0),array('pages_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_pages',array('pages_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_pages',$data_id);
					}
					break;
					
					case 'edit' :
					$this->m_pages->EditProcess();
					break;

					case 'load_menu' :
					// $this->m_pages->EditProcess();
					$id = $this->uri->segment(4);
					if(!empty($id)){
						$data['id'] = $id;
						$this->load->view('back/ajax_data/load_menu',$data);
					}
					break;

					case 'sort_menu' :
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
					break;

					case 'delete_menu' :
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
					break;

					case 'pages_settings_process' :
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
					break;

					case 'update_data_pages' :
					$output = array('output'=>'false');
					$count_menu = $this->input->post('count_menu');

					$output_json = false;
					for ($i=1; $i <= $count_menu; $i++) { 
						$pages_label = $this->input->post('pages_label_'.$i);
						$pages_url = $this->input->post('pages_url_'.$i);
						$pages_position_id = $this->input->post('pages_position_id_'.$i);
						$open_link = $this->input->post('open_link_'.$i);

						// $output_json = false;
						$update = $this->crud_global->UpdateDefault('tbl_pages_position',array('pages_label'=>$pages_label,'pages_url'=>$pages_url,'open_link'=>$open_link),array('pages_position_id'=>$pages_position_id));

					    if($update){
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
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function pages_data()
	{
		$this->load->model('m_pages');

		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :

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
			            $url_edit = site_url('admin/pages_data/detail/'.$value->pages_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            // if($delete == 1){
				           //  $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            // }else {
			            // 	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            // }
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
					break;

					case 'upload_photo' :
					$this->crud_global->UploadAjax('pages');
					break;

					// case 'form_add' :
					// $this->load->view('back/ajax_form/add_pages');
					// break;

					// case 'add' :
					// $output=array('output'=>'false');
					// $this->m_pages->AddProcess();
					// break;


					// case 'delete' :
					// $del_id=$this->input->post('del_id');
					// if(!empty($del_id)){
					// 	$delete=$this->crud_global->UpdateDefault('tbl_pages',array('status'=>0),array('pages_id'=>$del_id));
					// 	if($delete){
					// 		echo 'success';
					// 	}else {
					// 		echo 'failed';
					// 	}
					// }
					// break;

					// case 'form_edit' :
					// $uri4=$this->uri->segment(4);
					// if(!empty($uri4)){
					// 	$data_id['id']=$uri4;
					// 	$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_pages',array('pages_id'=>$uri4));
					// 	$this->load->view('back/ajax_form/edit_pages',$data_id);
					// }
					// break;
					
					// case 'edit' :
					// $this->m_pages->EditProcess();
					
					// break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}


	function product_category()
	{
		$this->load->model('m_pages');
		$this->load->model('m_product');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :
					$table = 'tbl_product_category';
				    $column_order = array('tbl_product_category.product_category_id','tbl_product_category.sort_id','tbl_product_category.status',null); 
				    $column_search = array('tbl_product_category_data.product_category_name');
				    $order = array('tbl_product_category.product_category_id' => 'desc'); // default order 


				    // $column_join = array('tbl_product_category_data','tbl_product_category_data.product_category_id = tbl_product_category.product_category_id');
				    $column_select = 'product_category';
				    $column_join = 'tbl_product_category_data.product_category_id,tbl_product_category.product_category_id';

				    $arraywhere = array('status !=' => '0');

					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            $row = array();
			            $row[] = $no;
			            $row[] = $this->m_product->GetProductCategory($value->product_category_id);
			            $row[] = $this->m_product->GetProductCategory($value->parent_id);
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/product_category/delete/'.$value->product_category_id.'');
			            $url_edit = site_url('ajax_admin/product_category/form_edit/'.$value->product_category_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
			            //add html for action
			            $row[] = $btn_edit." ".$btn_delete;
			 
			            $data[] = $row;
			        }
			        $output = array(
		                        "draw" => $_POST['draw'],
		                        "recordsTotal" => $this->DB_model->count_all($table,$arraywhere),
		                        "recordsFiltered" => $this->DB_model->count_filtered($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join),
		                        "data" => $data,
			                );
			        //output to json format
			        echo json_encode($output);
					break;

					case 'form_add' :
					$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
					$this->load->view('back/add_product_category',$data);
					break;

					case 'add' :
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
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_product_category',array('status'=>0),array('product_category_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data['id']=$uri4;
						$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'desc');
						$data['data_edit'] = $this->crud_global->ShowTableDefault('tbl_product_category',array('product_category_id'=>$uri4));
						$this->load->view('back/edit_product_category',$data);
					}
					break;
					
					case 'edit' :
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
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function product()
	{
		$this->load->model('m_pages');
		$this->load->model('m_product');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :
					$table = 'tbl_product';
				    $column_order = array('product_id','status',null); 
				    $column_search = array('product_name');
				    $order = array('product_id' => 'desc'); // default order 
				    $arraywhere = array('status !=' => '0');

				    $column_select = false;
				    $column_join = false;

					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            $row = array();
			            $row[] = $no;
			            $row[] = $this->m_product->GetProductName($value->product_id);
			            $row[] = $value->sort_id;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/product/delete/'.$value->product_id.'');
			            $url_edit = site_url('ajax_admin/product/form_edit/'.$value->product_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
			            //add html for action
			            $row[] = $btn_edit." ".$btn_delete;
			 
			            $data[] = $row;
			        }
			        $output = array(
		                        "draw" => $_POST['draw'],
		                        "recordsTotal" => $this->DB_model->count_all($table,$arraywhere),
		                        "recordsFiltered" => $this->DB_model->count_filtered($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join),
		                        "data" => $data,
			                );
			        //output to json format
			        echo json_encode($output);
					break;

					case 'form_add' :
					$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false);
					$this->load->view('back/add_product',$data);
					break;

					case 'add' :
					$arr_lang = $this->crud_global->ShowTable('tbl_language',false);
					$output=array('output'=>'false');

					// Get data
					$product_code = $this->input->post('product_code');
					$product_barcode = $this->input->post('product_barcode');
					$product_available = $this->input->post('product_available');
					$manufactures_id = $this->input->post('manufactures_id');
					$product_category_id = $this->input->post('product_category_id[]');
					$product_special_category_id = $this->input->post('product_special_category_id[]');

					$related_products = $this->input->post('related_products[]');
					if(is_array($related_products)){
						$related_products = implode(",", $related_products);
					}

					$price = $this->input->post('price');
					$quantity = $this->input->post('quantity');
					$sort_id = $this->input->post('sort_id');
					$admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

		            $arrayvalues = array(
		              	'product_code'=>$product_code,
		              	'product_barcode'=>$product_barcode,
		              	'product_available'=>$product_available,
		              	'price'=>$price,
		              	'quantity'=>$quantity,
		              	'manufactures_id'=>$manufactures_id,
		              	'related_products'=>$related_products,
		        		'sort_id'=>$sort_id,
		        		'status'=>$status,
		        		'created_by'=>$admin_id,
		        		'datecreated'=>$datecreated
		        	);

		        	$check_product_code = $this->crud_global->CheckNum('tbl_product',array('product_code'=>$product_code,'status !='=>0));
		        	if(empty($product_code)){
		        		$output=array('output'=>'Product Code Cannot be Empty');

		        	}else if($check_product_code == true){
			        	$output=array('output'=>'Product Code has been registered');
			        }else {
			        	$query=$this->db->insert('tbl_product',$arrayvalues);
			            if($query){
			            	$id = $this->db->insert_id();

			            	$output_val = false;
			            	if(is_array($product_category_id)){
			            		foreach ($product_category_id as $key_cat => $row_cat) {
				            		$arrayvalues_cat = array(
						              	'product_id' => $id,
						              	'product_category_id' => $row_cat
						        	);
						            $query_cat = $this->db->insert('tbl_procat',$arrayvalues_cat);

						            $parent_id = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$row_cat),'parent_id');
						            if($parent_id != 0){

						            	$arrayvalues_cat_parent = array(
							              	'product_id' => $id,
							              	'product_category_id' => $parent_id
							        	);
							            $query_cat_parent = $this->db->insert('tbl_procat',$arrayvalues_cat_parent);

							            $parent_id_2 = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$parent_id),'parent_id');
						            	if($parent_id_2 != 0){	

						            		$arrayvalues_cat_parent_2 = array(
								              	'product_id' => $id,
								              	'product_category_id' => $parent_id_2
								        	);
								            $query_cat_parent_2 = $this->db->insert('tbl_procat',$arrayvalues_cat_parent_2);
						            	}
						            }

						            if($query_cat){
						            	$output_val = true;
						            }else {
						            	$output_val = false;
						            }
				            	}
			            	}

			            	if(is_array($product_special_category_id)){
			            		foreach ($product_special_category_id as $key_spec => $row_spec) {
				            		$arrayvalues_spec = array(
						              	'product_id' => $id,
						              	'product_special_category_id' => $row_spec
						        	);
						            $query_spec = $this->db->insert('tbl_product_special',$arrayvalues_spec);
						            if($query_spec){
						            	$output_val = true;
						            }else {
						            	$output_val = false;
						            }
				            	}
			            	}

			            	foreach ($arr_lang as $key => $value) {
			            		$arrayvalues_2 = array(
					              	'product_id' => $id,
					              	'product_name' => $this->input->post('product_name_'.$value->language_id),
					              	'product_subname' => $this->input->post('product_subname_'.$value->language_id),
					              	'product_short_des' => $this->input->post('product_short_des_'.$value->language_id),
					              	'product_des' => $this->input->post('product_des_'.$value->language_id),
					              	'product_meta_des' => $this->input->post('product_meta_des_'.$value->language_id),
					              	'product_keywords' => $this->input->post('product_keywords_'.$value->language_id),
					              	'product_tags' => $this->input->post('product_tags_'.$value->language_id),
					              	'language_id' => $value->language_id
					        	);
					            $query_2 = $this->db->insert('tbl_product_data',$arrayvalues_2);

					            if($query_2){
					            	$output_val = true;
					            }else {
					            	$output_val = false;
					            }
			            	}

			            	// Insert Spec
		            		$val_attr = $this->input->post('val_attr');
		            		if($val_attr > 0){
		            			for ($i=1; $i<=$val_attr ; $i++) { 
			            			$arrayvalues_3 = array(
						              	'product_id' => $id,
						              	'product_spec' => $this->input->post('attribute_name'.$i),
						              	'product_spec_desc' => $this->filter->FilterTextarea($this->input->post('attribute_des'.$i)),
						              	'sort_id' => $this->input->post('attribute_sort'.$i),
						        	);
						        	if($this->input->post('attribute_name'.$i) !== null && $this->input->post('attribute_des'.$i) !== null){
						        		$query_3 = $this->db->insert('tbl_product_spec',$arrayvalues_3);
						        	}
						            $output_val = true;
			            		}
		            		}

		            		// Insert Gallery
		            		$val_gallery = $this->input->post('val_gallery');
		            		if($val_gallery > 0){
		            			for ($i=1; $i<=$val_gallery ; $i++) { 
			            			$arrayvalues_4 = array(
						              	'product_id' => $id,
						              	'product_image' => $this->input->post('gallery_image'.$i),
						              	'product_caption' => $this->input->post('gallery_des'.$i),
						              	'sort_id' => $this->input->post('gallery_sort'.$i),
						        	);
						        	if($this->input->post('gallery_image'.$i) !== null && $this->input->post('gallery_des'.$i) !== null){
						        		$query_4 = $this->db->insert('tbl_product_gallery',$arrayvalues_4);
						        	}
						            $output_val = true;
			            		}
		            		}

		            		

			            	if($output_val == true){
				            	$output=array('output'=>'true');
			            	}else {
			            		$output=array('output'=>'Error Insert Data');    	
			            	}
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_product',array('status'=>0),array('product_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'add_product_options' :
					$output = array('output'=>'false');
					$product_id = $this->input->post('product_id');

					$output_json = false;
					$product_attributes_id = $this->input->post('product_attributes_id[]');

					if(is_array($product_attributes_id)){
						$no=1;
						foreach ($product_attributes_id as $key => $row) {
							// $pages_label = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row),'pages');
							$arrayvalues = array(
				              	'product_id' => $product_id,
				              	'product_attributes_id' => $row,
				              	'status' => 1,
				        	);

				            $query=$this->db->insert('tbl_product_options',$arrayvalues);
				            if($query){
				            	$output_json = true;
				            }else {
				            	$output_json = false;
				            }
				            $no++;
						}
					}

					if($output_json == true){
						$output = array('output'=>'true');
					}else {
						$output = array('output'=>'false');
					}

					echo json_encode($output);
					break;

					case 'load_product_options' :
					$id = $this->uri->segment(4);
					if(!empty($id)){
						$data['id'] = $id;
						$this->load->view('back/ajax_data/load_product_options',$data);
					}
					break;


					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data['id']=$uri4;
						$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false);
						$data['data_edit'] = $this->crud_global->ShowTableDefault('tbl_product',array('product_id'=>$uri4));
						$this->load->view('back/edit_product',$data);
					}
					break;


					
					case 'edit' :
					$arr_lang = $this->crud_global->ShowTable('tbl_language',false);
					$output=array('output'=>'false');

					// Get data
					$id = $this->input->post('id');
					$product_code = $this->input->post('product_code');
					$product_barcode = $this->input->post('product_barcode');
					$product_available = $this->input->post('product_available');
					$manufactures_id = $this->input->post('manufactures_id');
					$product_category_id = $this->input->post('product_category_id[]');

					$product_special_category_id = $this->input->post('product_special_category_id[]');

					$related_products = $this->input->post('related_products[]');
					if(is_array($related_products)){
						$related_products = implode(",", $related_products);
					}
					
					$price = $this->input->post('price');
					$quantity = $this->input->post('quantity');
					$sort_id = $this->input->post('sort_id');
					$admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

		            $arrayvalues = array(
		              	'product_code'=>$product_code,
		              	'product_barcode'=>$product_barcode,
		              	'product_available'=>$product_available,
		              	'price'=>$price,
		              	'quantity'=>$quantity,
		              	'manufactures_id'=>$manufactures_id,
		              	'related_products'=>$related_products,
		        		'sort_id'=>$sort_id,
		        		'status'=>$status,
		        		'updated_by'=>$admin_id,
		        		'dateupdated'=>$datecreated
		        	);


		        	$product_code_old = $this->crud_global->GetField('tbl_product',array('product_id'=>$id),'product_code');
			        if($product_code_old != $product_code){
			        	$check_product_code = $this->crud_global->CheckNum('tbl_product',array('product_code'=>$product_code,'status !='=>0));
			        }else{
			        	$check_product_code = false;
			        }

		        	if(empty($product_code)){
		        		$output=array('output'=>'Product Code Cannot be Empty');
		        	}else if($check_product_code == true){
			        	$output=array('output'=>'Your Name has been registered');
			        }else {

			        	$query = $this->crud_global->UpdateDefault('tbl_product',$arrayvalues,array('product_id'=>$id));

			            if($query){

			            	$output_val = false;

			            	
			            	$this->db->delete('tbl_procat',array('product_id'=>$id));

			            	if(is_array($product_category_id)){
			            		foreach ($product_category_id as $key_cat => $row_cat) {

				            		$arrayvalues_cat = array(
						              	'product_id' => $id,
						              	'product_category_id' => $row_cat
						        	);
						            $query_cat = $this->db->insert('tbl_procat',$arrayvalues_cat);

						            $parent_id = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$row_cat),'parent_id');
						            if($parent_id != 0){

						            	$arrayvalues_cat_parent = array(
							              	'product_id' => $id,
							              	'product_category_id' => $parent_id
							        	);
							            $query_cat_parent = $this->db->insert('tbl_procat',$arrayvalues_cat_parent);

							            $parent_id_2 = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$parent_id),'parent_id');
						            	if($parent_id_2 != 0){	

						            		$arrayvalues_cat_parent_2 = array(
								              	'product_id' => $id,
								              	'product_category_id' => $parent_id_2
								        	);
								            $query_cat_parent_2 = $this->db->insert('tbl_procat',$arrayvalues_cat_parent_2);
						            	}
						            }
						            
						            if($query_cat){
						            	$output_val = true;
						            }else {
						            	$output_val = false;
						            }
				            	}
			            	}

			            	$this->db->delete('tbl_product_special',array('product_id'=>$id));
			            	if(is_array($product_special_category_id)){
			            		foreach ($product_special_category_id as $key_spec => $row_spec) {

				            		$arrayvalues_spec = array(
						              	'product_id' => $id,
						              	'product_special_category_id' => $row_spec
						        	);
						            $query_spec = $this->db->insert('tbl_product_special',$arrayvalues_spec);

						            if($query_spec){
						            	$output_val = true;
						            }else {
						            	$output_val = false;
						            }
				            	}
			            	}

			            	foreach ($arr_lang as $key => $value) {
			            		$arrayvalues_2 = array(
					              	'product_id' => $id,
					              	'product_name' => $this->input->post('product_name_'.$value->language_id),
					              	'product_subname' => $this->input->post('product_subname_'.$value->language_id),
					              	'product_short_des' => $this->input->post('product_short_des_'.$value->language_id),
					              	'product_des' => $this->input->post('product_des_'.$value->language_id),
					              	'product_meta_des' => $this->input->post('product_meta_des_'.$value->language_id),
					              	'product_keywords' => $this->input->post('product_keywords_'.$value->language_id),
					              	'product_tags' => $this->input->post('product_tags_'.$value->language_id),
					              	'language_id' => $value->language_id
					        	);
					            $query_2 = $this->crud_global->UpdateDefault('tbl_product_data',$arrayvalues_2,array('product_id'=>$id));

					            if($query_2){
					            	$output_val = true;
					            }else {
					            	$output_val = false;
					            }
			            	}



			            	// Insert Attribute    	
		            		$val_attr = $this->input->post('val_attr');
		            		$val_attr_name = $this->input->post('val_attr_name');
		            		$val_attr_first = $this->input->post('val_attr_first');
		            		if($val_attr > 0){
		            			for ($i=$val_attr_first; $i<=$val_attr_name ; $i++) { 
		            				$check_attr = $this->crud_global->CheckNum('tbl_product_spec',array('product_id'=>$id,'product_spec_id'=>$i));

		            				if($check_attr == true){
		            					$arrayvalues_3 = array(
							              	'product_spec' => $this->input->post('attribute_name'.$i),
							              	'product_spec_desc' => $this->filter->FilterTextarea($this->input->post('attribute_des'.$i)),
							              	'sort_id' => $this->input->post('attribute_sort'.$i),
							        	);
		            					$query_3 = $this->crud_global->UpdateDefault('tbl_product_spec',$arrayvalues_3,array('product_spec_id'=>$i));
		            				}else {
		            					$arrayvalues_3 = array(
							              	'product_id' => $id,
							              	'product_spec' => $this->input->post('attribute_name'.$i),
							              	'product_spec_desc' => $this->filter->FilterTextarea($this->input->post('attribute_des'.$i)),
							              	'sort_id' => $this->input->post('attribute_sort'.$i),
							        	);

							        	if($this->input->post('attribute_name'.$i) !== null && $this->input->post('attribute_des'.$i) !== null){
							        		$query_3 = $this->db->insert('tbl_product_spec',$arrayvalues_3);
							        	}
		            				}

						            $output_val = true;
			            		}
		            		}

		            		// Delete Attribute    	
			            	$del_attr = $this->input->post('attr_del');
			            	if($del_attr !== null){
			            		$ex_attr = explode(",", $del_attr);
			            		foreach ($ex_attr as $key => $value) {
			            			$this->db->delete('tbl_product_spec',array('product_spec_id'=>$value));
			            		}
			            	}

			            	// Insert Gallery
		            		$val_gallery = $this->input->post('val_gallery');
		            		$val_gallery_name = $this->input->post('val_gallery_name');
		            		$val_gallery_first = $this->input->post('val_gallery_first');
		            		if($val_attr > 0){
		            			for ($i=$val_gallery_first; $i<=$val_gallery_name ; $i++) { 
		            				$check_gallery = $this->crud_global->CheckNum('tbl_product_gallery',array('product_id'=>$id,'product_gallery_id'=>$i));

		            				if($check_gallery == true){
		            					$arrayvalues_4 = array(
							              	'product_image' => $this->input->post('gallery_image'.$i),
							              	'product_caption' => $this->input->post('gallery_des'.$i),
							              	'sort_id' => $this->input->post('gallery_sort'.$i),
							        	);
		            					$query_4 = $this->crud_global->UpdateDefault('tbl_product_gallery',$arrayvalues_4,array('product_gallery_id'=>$i));
		            				}else {
		            					$arrayvalues_4 = array(
							              	'product_id' => $id,
							              	'product_image' => $this->input->post('gallery_image'.$i),
							              	'product_caption' => $this->input->post('gallery_des'.$i),
							              	'sort_id' => $this->input->post('gallery_sort'.$i),
							        	);

							        	if($this->input->post('gallery_image'.$i) !== null && $this->input->post('gallery_des'.$i) !== null){
							        		$query_4 = $this->db->insert('tbl_product_gallery',$arrayvalues_4);
							        	}
		            				}
		            				
						            $output_val = true;
			            		}
		            		}

		            		// Delete gallery	
			            	$del_gallery = $this->input->post('gallery_del');
			            	if($del_gallery !== null){
			            		$ex_gallery = explode(",", $del_gallery);
			            		foreach ($ex_gallery as $key => $value) {
			            			$this->db->delete('tbl_product_gallery',array('product_gallery_id'=>$value));
			            		}
			            	}


			            	


			            	if($output_val == true){
				            	$output=array('output'=>'true');
			            	}else {
			            		$output=array('output'=>'Error Insert Data');    	
			            	}
			            }else {
			            	$output=array('output'=>'false');
			            }
		            }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function product_attributes()
	{
		
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :
					$table = 'tbl_product_attributes';
				    $column_order = array('product_attributes','status',null); 
				    $column_search = array('product_attributes');
				    $order = array('product_attributes_id' => 'desc'); // default order 
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
			            $row[] = $value->product_attributes;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/product_attributes/delete/'.$value->product_attributes_id.'');
			            $url_edit = site_url('ajax_admin/product_attributes/form_edit/'.$value->product_attributes_id.'');
			            $url_settings = site_url('ajax_admin/product_attributes/form_settings/'.$value->product_attributes_id.'');

			            $btn_settings = '<a class="btn btn-sm btn-warning" href="'.$url_settings.'"><i class="glyphicon glyphicon-pencil"></i> Settings</a>';

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_product_attributes');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$product_attributes = $this->input->post('product_attributes');
					$product_attributes_desc = $this->input->post('product_attributes_desc');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$check_product_attributes = $this->crud_global->CheckNum('tbl_product_attributes',array('product_attributes'=>$product_attributes,'status !='=>0));

			        if($check_product_attributes == true){
			        	$output=array('output'=>'Your Name has been registered');
			        }else {
			        	$arrayvalues = array(
			        		'product_attributes'=>$product_attributes,
			        		'product_attributes_desc'=>$product_attributes_desc,
			        		'status'=>$status,
			        		'created_by'=>$admin_id,
			        		'datecreated'=>$datecreated
			        		);

			            $query=$this->db->insert('tbl_product_attributes',$arrayvalues);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;

					case 'add_product_attributes_value' :
					$output=array('output'=>'false');
					// Get data
					$product_attributes_id = $this->input->post('product_attributes_id');
					$product_attributes_value = $this->input->post('product_attributes_value');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$check_product_attributes = $this->crud_global->CheckNum('tbl_product_attributes_value',array('product_attributes_value'=>$product_attributes_value,'status !='=>0));

			        if($check_product_attributes == true){
			        	$output=array('output'=>'Your Name has been registered');
			        }else {
			        	$arrayvalues = array(
			        		'product_attributes_id'=>$product_attributes_id,
			        		'product_attributes_value'=>$product_attributes_value,
			        		'status'=>$status,
			        		'created_by'=>$admin_id,
			        		'datecreated'=>$datecreated
			        		);

			            $query=$this->db->insert('tbl_product_attributes_value',$arrayvalues);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;

					case 'load_data' :
					// $this->m_pages->EditProcess();
					$id = $this->uri->segment(4);
					if(!empty($id)){
						$data['id'] = $id;
						$this->load->view('back/ajax_data/load_data_attributes',$data);
					}
					break;

					case 'delete_attributes' :
					$output = array('output'=>'false');
					$id = $this->input->post('id');
					$delete=$this->crud_global->UpdateDefault('tbl_product_attributes_value',array('status'=>0),array('product_attributes_value_id'=>$id));
				    if($delete){
				    	$output = array('output'=>'true');
				    }else {
				    	$output = array('output'=>'false');
				    }

				    echo json_encode($output);
					break;

					case 'update_attributes_value' :
					$output = array('output'=>'false');
					$count_menu = $this->input->post('count_menu');

					$output_json = false;
					for ($i=1; $i <= $count_menu; $i++) { 
						$product_attributes_value = $this->input->post('product_attributes_value_'.$i);
						$product_attributes_value_id = $this->input->post('product_attributes_value_id_'.$i);

						// $output_json = false;
						$update = $this->crud_global->UpdateDefault('tbl_product_attributes_value',array('product_attributes_value'=>$product_attributes_value),array('product_attributes_value_id'=>$product_attributes_value_id));

					    if($update){
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
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_product_attributes',array('status'=>0),array('product_attributes_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_product_attributes',array('product_attributes_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_product_attributes',$data_id);
					}
					break;

					case 'form_settings' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_pages_position_category',array('pages_position_category_id'=>$uri4));
						$this->load->view('back/ajax_form/settings_product_attributes',$data_id);
					}
					break;
					
					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id=$this->input->post('id');
					$product_attributes = $this->input->post('product_attributes');
					$product_attributes_desc = $this->input->post('product_attributes_desc');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // Update JSON
			        // Get Data Old for Filter
			        $product_attributes_old = $this->crud_global->GetField('tbl_product_attributes',array('product_attributes_id'=>$id),'product_attributes');
			        if($product_attributes_old != $product_attributes){
			        	$chek_product_attributes = $this->crud_global->CheckNum('tbl_product_attributes',array('product_attributes'=>$product_attributes,'status !='=>0));
			        }else{
			        	$chek_product_attributes = false;
			        }
			        
			        if($chek_product_attributes == true){
			        	$output=array('output'=>'Name has been registered');
			        }else {
			        	$arraywhere = array('product_attributes_id'=>$id);
			        	$arrayvalues = array(
			        		'product_attributes'=>$product_attributes,
			        		'product_attributes_desc'=>$product_attributes_desc,
			        		'status'=>$status,
			        		'updated_by'=>$admin_id,
			        		'dateupdated'=>$datecreated
			        		);
			            $query=$this->crud_global->UpdateDefault('tbl_product_attributes',$arrayvalues,$arraywhere);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function product_special()
	{
		
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :
					$table = 'tbl_product_special_category';
				    $column_order = array('product_special_category','status',null); 
				    $column_search = array('product_special_category');
				    $order = array('product_special_category_id' => 'desc'); // default order 
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
			            $row[] = $value->product_special_category;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/product_special/delete/'.$value->product_special_category_id.'');
			            $url_edit = site_url('ajax_admin/product_special/form_edit/'.$value->product_special_category_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_product_special');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$product_special_category = $this->input->post('product_special_category');
					$product_special_category_alias = strtolower(str_replace(' ', '_', $product_special_category));
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$check_product_special_category = $this->crud_global->CheckNum('tbl_product_special_category',array('product_special_category'=>$product_special_category,'status !='=>0));

			        if($check_product_special_category == true){
			        	$output=array('output'=>'Your Name has been registered');
			        }else {
			        	$arrayvalues = array(
			        		'product_special_category'=>$product_special_category,
			        		'product_special_category_alias'=>$product_special_category_alias,
			        		'status'=>$status,
			        		'created_by'=>$admin_id,
			        		'datecreated'=>$datecreated
			        		);

			            $query=$this->db->insert('tbl_product_special_category',$arrayvalues);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_product_special_category',array('status'=>0),array('product_special_category_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNoOrder('tbl_product_special_category',array('product_special_category_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_product_special',$data_id);
					}
					break;
					
					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id=$this->input->post('id');
					$product_special_category = $this->input->post('product_special_category');
					$product_special_category_alias = strtolower(str_replace(' ', '_', $product_special_category));
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // Update JSON
			        // Get Data Old for Filter
			        $product_special_category_old = $this->crud_global->GetField('tbl_product_special_category',array('product_special_category_id'=>$id),'product_special_category');
			        if($product_special_category_old != $product_special_category){
			        	$check_product_special_category = $this->crud_global->CheckNum('tbl_product_special_category',array('product_special_category'=>$product_special_category,'status !='=>0));
			        }else{
			        	$check_product_special_category = false;
			        }
			        
			        if($check_product_special_category == true){
			        	$output=array('output'=>'Name has been registered');
			        }else {
			        	$arraywhere = array('product_special_category_id'=>$id);
			        	$arrayvalues = array(
			        		'product_special_category'=>$product_special_category,
			        		'product_special_category_alias'=>$product_special_category_alias,
			        		'status'=>$status,
			        		'updated_by'=>$admin_id,
			        		'dateupdated'=>$datecreated
			        		);
			            $query=$this->crud_global->UpdateDefault('tbl_product_special_category',$arrayvalues,$arraywhere);
			            if($query){
			            	$output=array('output'=>'true');
			            }else {
			            	$output=array('output'=>'false');
			            }
			        }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}


	function manufactures()
	{
		$this->load->model('m_pages');
		$this->load->model('m_product');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);

			$uri2=$this->uri->segment(2);
			$sess_admin = $this->session->userdata('admin_id');
    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
			$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
			$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
			$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

			if(!empty($uri3)){
				switch($uri3) {

					case 'data_table' :
					$table = 'tbl_manufactures';
				    $column_order = array('manufactures_id','sort_id','status',null); 
				    $column_search = array('manufactures_name');
				    $order = array('manufactures_id' => 'desc'); // default order 
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
			            $row[] = $value->manufactures_name;
			            $row[] = $value->sort_id;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/manufactures/delete/'.$value->manufactures_id.'');
			            $url_edit = site_url('ajax_admin/manufactures/form_edit/'.$value->manufactures_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }
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
					break;

					case 'form_add' :
					$this->load->view('back/add_manufactures');
					break;

					case 'add' :
					$arr_lang = $this->crud_global->ShowTable('tbl_language',false);
					$output=array('output'=>'false');

					// Get data
					$manufactures_name = $this->input->post('manufactures_name');
					$manufactures_des = $this->input->post('manufactures_des');
					$manufactures_keywords = $this->input->post('manufactures_keywords');
					$manufactures_image = $this->input->post('manufactures_image');
					$sort_id = $this->input->post('sort_id');
					$admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

		            $arrayvalues = array(
		              	'manufactures_name'=>$manufactures_name,
		              	'manufactures_image'=>$manufactures_image,
		              	'manufactures_des'=>$manufactures_des,
		              	'manufactures_keywords'=>$manufactures_keywords,
		        		'sort_id'=>$sort_id,
		        		'status'=>$status,
		        		'created_by'=>$admin_id,
		        		'dateupdated'=>$datecreated
		        	);

		            $query=$this->db->insert('tbl_manufactures',$arrayvalues);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;


					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_manufactures',array('status'=>0),array('manufactures_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data['id']=$uri4;
						$data['data_edit'] = $this->crud_global->ShowTableDefault('tbl_manufactures',array('manufactures_id'=>$uri4));
						$this->load->view('back/edit_manufactures',$data);
					}
					break;
					
					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id=$this->input->post('id');
					$manufactures_name = $this->input->post('manufactures_name');
					$manufactures_des = $this->input->post('manufactures_des');
					$manufactures_keywords = $this->input->post('manufactures_keywords');
					$manufactures_image = $this->input->post('manufactures_image');
					$sort_id = $this->input->post('sort_id');
					$admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        $arraywhere = array('manufactures_id'=>$id);
		            $arrayvalues = array(
		              	'manufactures_name'=>$manufactures_name,
		              	'manufactures_des'=>$manufactures_des,
		              	'manufactures_keywords'=>$manufactures_keywords,
		              	'manufactures_image'=>$manufactures_image,
		        		'sort_id'=>$sort_id,
		        		'status'=>$status,
		        		'updated_by'=>$admin_id,
		        		'dateupdated'=>$datecreated
		        	);

			        // Update JSON
		            $query=$this->crud_global->UpdateDefault('tbl_manufactures',$arrayvalues,$arraywhere);

		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function product_data_form()
	{
		// $this->load->model('m_language');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_product_form';
				    $column_order = array('tour_name','first_name','email','phone',null); 
				    $column_search = array('tour_name','first_name','email','phone'); 
				    $order = array('product_form_id' => 'desc'); // default order 
				    $arraywhere = false;
					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            $row = array();
			            $row[] = $no;
			            $row[] = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$value->product_id,'language_id'=>1),'product_name');
			            $row[] = $value->first_name;
			            $row[] = $value->email;
			            $row[] = $value->phone;

			            $url_edit = site_url('ajax_admin/product_data_form/form_detail/'.$value->product_form_id.'');
			            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Detail</a>';
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
					break;

					case 'form_detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data['id']=$uri4;
						$data['data_edit'] = $this->crud_global->ShowTableDefault('tbl_product_form',array('product_form_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_product_form',$data);
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function email_subscribe()
	{
		// $this->load->model('m_language');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_subscribe';
				    $column_order = array('email','ip_address'); 
				    $column_search = array('email','ip_address');
				    $order = array('subscribe_id' => 'desc'); // default order 
				    $arraywhere = false;
					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            $row = array();
			            $row[] = $no;
			            $row[] = $value->email;
			            $row[] = $value->ip_address;

			            // $url_edit = site_url('ajax_admin/product_data_form/form_detail/'.$value->product_form_id.'');
			            // $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Detail</a>';
			            //add html for action
			            // $row[] = $btn_edit;
			 
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
					break;

					case 'form_detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data['id']=$uri4;
						$data['data_edit'] = $this->crud_global->ShowTableDefault('tbl_product_form',array('product_form_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_product_form',$data);
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function member()
	{
		// $this->load->model('m_language');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_member';
				    $column_order = array('first_name','email','status',null); 
				    $column_search = array('first_name','email');
				    $order = array('datecreated' => 'desc'); // default order 
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
			            $row[] = $value->first_name.' '.$value->last_name;
			            $row[] = $value->email;
			            $row[] = $this->general->GetStatus($value->status);

			            $url_del = site_url('ajax_admin/member/delete/'.$value->member_id.'');
			            $url_edit = site_url('ajax_admin/member/form_edit/'.$value->member_id.'');
			            $url_detail = site_url('ajax_admin/member/detail/'.$value->member_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }

			            $btn_detail = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_detail.'"><i class="glyphicon glyphicon-pencil"></i> Detail</a>';

			            //add html for action
			            $row[] = $btn_detail;
			 
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_member');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$language = $this->input->post('language');
					$lang_code = strtolower($this->input->post('country'));
					$order_id = $this->input->post('order_id');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arrayvalues = array(
		        		'language_title'=>$language,
		        		'language_code'=>$lang_code,
		        		'order_id'=>$order_id,
		        		'status'=>$status,
		        		'create_by'=>$admin_id,
		        		'datecreated'=>$datecreated
		        		);

		            $query=$this->db->insert('tbl_language',$arrayvalues);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTable('tbl_language',array('language_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_member',$data_id);
					}
					break;

					case 'detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_detail'] = $this->crud_global->ShowTableNew('tbl_member',array('member_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_member',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id = $this->input->post('id');
					$language = $this->input->post('language');
					$lang_code = strtolower($this->input->post('country'));
					$order_id = $this->input->post('order_id');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arraywhere = array('language_id'=>$id);
		        	$arrayvalues = array(
		        		'language_title'=>$language,
		        		'language_code'=>$lang_code,
		        		'order_id'=>$order_id,
		        		'status'=>$status,
		        		'update_by'=>$admin_id,
		        		'dateupdate'=>$datecreated
		        		);
		            $query=$this->crud_global->UpdateDefault('tbl_language',$arrayvalues,$arraywhere);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_language',array('status'=>0),array('language_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function invoice()
	{
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_invoice';
				    $column_order = array('invoice_no','member_id','total','status',null); 
				    $column_search = array('invoice_no');
				    $order = array('datecreated' => 'desc'); // default order 
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
			            $row[] = $value->invoice_no;
			            $row[] = $this->crud_global->GetField('tbl_member',array('member_id'=>$value->member_id),'first_name');
			            $row[] = $this->general->NumberMoney($value->total);
			            $row[] = $this->m_product->GetStatusOrder($value->status);

			            $url_del = site_url('ajax_admin/invoice/delete/'.$value->invoice_id.'');
			            $url_edit = site_url('ajax_admin/invoice/form_edit/'.$value->invoice_id.'');
			            $url_detail = site_url('ajax_admin/invoice/detail/'.$value->invoice_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }

			            $btn_detail = '<a class="btn btn-sm btn-warning btn-fancy fancybox.ajax" href="'.$url_detail.'"><i class="glyphicon glyphicon-pencil"></i> Detail</a>';

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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_member');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$language = $this->input->post('language');
					$lang_code = strtolower($this->input->post('country'));
					$order_id = $this->input->post('order_id');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arrayvalues = array(
		        		'language_title'=>$language,
		        		'language_code'=>$lang_code,
		        		'order_id'=>$order_id,
		        		'status'=>$status,
		        		'create_by'=>$admin_id,
		        		'datecreated'=>$datecreated
		        		);

		            $query=$this->db->insert('tbl_language',$arrayvalues);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_invoice',$data_id);
					}
					break;

					case 'detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_detail'] = $this->crud_global->ShowTableNew('tbl_payment',array('payment_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_invoice',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id = $this->input->post('id');
			        $status = $this->input->post('status');

			        // insert JSON
					$arraywhere = array('invoice_id'=>$id);
		        	$arrayvalues = array(
		        		'status'=>$status
		        		);
		            $query=$this->crud_global->UpdateDefault('tbl_invoice',$arrayvalues,$arraywhere);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_language',array('status'=>0),array('language_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

					case 'payment_process' :
					$output=array('output'=>'false');
					$id=$this->input->post('id');
					$order_id=$this->input->post('order_id');
					if(!empty($id)){
						$arraywhere = array('invoice_id'=>$order_id);
			        	$arrayvalues = array(
			        		'status'=>2
			        		);

			            $query = $this->crud_global->UpdateDefault('tbl_invoice',$arrayvalues,$arraywhere);
			            if($query){

			            	$arraywhere_pay = array('invoice_id'=>$order_id);
				        	$arrayvalues_pay = array(
				        		'status'=>3
				        		);
				            $query_pay = $this->crud_global->UpdateDefault('tbl_payment',$arrayvalues_pay,$arraywhere_pay);
				            if($query_pay){
				            	$arraywhere_2 = array('payment_id'=>$id);
					        	$arrayvalues_2 = array(
					        		'status'=> 2
					        		);
					            $query_2 = $this->crud_global->UpdateDefault('tbl_payment',$arrayvalues_2,$arraywhere_2);
					            if($query_2){
					            	$output=array('output'=>'true');
					            }
				            }
			            }
					}
					echo json_encode($output);
					break;

					case 'print_pdf' :
	        		// check invoice id
					ob_start(); 
					$lang_id = $this->uri->segment(4);
					$id = $this->uri->segment(5);
					if(!empty($id)){
						// load dompdf
					    $this->load->helper('dompdf');

					    $invoice_no = $this->crud_global->GetField('tbl_invoice',array('invoice_id'=>$id),'invoice_no');
					    $data['id'] = $id;
					    $data['lang_id'] = $lang_id;
					    $data['row'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$id));
					    //load content html
					    $html = $this->load->view('front/form/view_pdf',$data, true);
					    // create pdf using dompdf
					    $filename = $invoice_no;
					    $paper = 'A4';
					    $orientation = 'potrait';
					    pdf_create($html, $filename, $paper, $orientation);
					}else {
						echo 'File PDF Not Found';
					}
					break;
					
				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function visitors()
	{
		// $this->load->model('m_language');
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_visitors';
				    $column_order = array('ip_address','platform','platform_agent','datecreated'); 
				    $column_search = array('ip_address','platform','platform_agent');
				    $order = array('visitors_id' => 'desc'); // default order 
				    $arraywhere = false;
					$list = $this->DB_model->get_datatables($table,$column_order,$column_search,$order,$arraywhere);
			        $data = array();
			        $no = $_POST['start'];
			        $no_list = 0;
			        foreach ($list as $value) {
			            $no++;
			            $no_list++;
			            $row = array();
			            $row[] = $no;
			            $row[] = $value->ip_address;
			            $row[] = $value->platform;
			            $row[] = $value->platform_agent;
			            $row[] = $value->datecreated;

			            // $url_edit = site_url('ajax_admin/product_data_form/form_detail/'.$value->product_form_id.'');
			            // $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Detail</a>';
			            //add html for action
			            // $row[] = $btn_edit;
			 
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
					break;

					case 'form_detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data['id']=$uri4;
						$data['data_edit'] = $this->crud_global->ShowTableDefault('tbl_product_form',array('product_form_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_product_form',$data);
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function jobs_category()
	{
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_jobs_category';
				    $column_order = array('jobs_category','status','datecreated',null); 
				    $column_search = array('jobs_category');
				    $order = array('jobs_category_id' => 'desc'); // default order 
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
			            $row[] = $value->jobs_category;
			            $row[] = $this->general->GetStatus($value->status);
			            $row[] = $this->waktu->WestConvertion($value->datecreated);

			            $url_del = site_url('ajax_admin/jobs_category/delete/'.$value->jobs_category_id.'');
			            $url_edit = site_url('ajax_admin/jobs_category/form_edit/'.$value->jobs_category_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }

			            //add html for action
			            $row[] = $btn_edit.' '.$btn_delete;
			 
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_jobs_category');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$jobs_category = $this->input->post('jobs_category');
					$jobs_category_desc = $this->input->post('jobs_category_desc');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arrayvalues = array(
		        		'jobs_category'=>$jobs_category,
		        		'jobs_category_desc'=>$jobs_category_desc,
		        		'status'=>$status,
		        		'create_by'=>$admin_id,
		        		'datecreated'=>$datecreated
		        		);

		            $query=$this->db->insert('tbl_jobs_category',$arrayvalues);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNew('tbl_jobs_category',array('jobs_category_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_jobs_category',$data_id);
					}
					break;

					case 'detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_detail'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_invoice',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id = $this->input->post('id');
					$jobs_category = $this->input->post('jobs_category');
					$jobs_category_desc = $this->input->post('jobs_category_desc');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arraywhere = array('jobs_category_id'=>$id);
		        	$arrayvalues = array(
		        		'jobs_category'=>$jobs_category,
		        		'jobs_category_desc'=>$jobs_category_desc,
		        		'status'=>$status,
		        		'update_by'=>$admin_id,
		        		'dateupdated'=>$datecreated
		        	);
		            $query=$this->crud_global->UpdateDefault('tbl_jobs_category',$arrayvalues,$arraywhere);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_jobs_category',array('status'=>0),array('jobs_category_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function jobs()
	{
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_jobs';
				    $column_order = array('jobs','status','datecreated',null); 
				    $column_search = array('jobs_category');
				    $order = array('jobs_category_id' => 'desc'); // default order 
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
			            $row[] = $value->jobs_category;
			            $row[] = $this->general->GetStatus($value->status);
			            $row[] = $this->waktu->WestConvertion($value->datecreated);

			            $url_del = site_url('ajax_admin/jobs_category/delete/'.$value->jobs_category_id.'');
			            $url_edit = site_url('ajax_admin/jobs_category/form_edit/'.$value->jobs_category_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }

			            //add html for action
			            $row[] = $btn_edit.' '.$btn_delete;
			 
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
					break;

					case 'form_add' :
					$this->load->view('back/add_jobs');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$jobs_category = $this->input->post('jobs_category');
					$jobs_category_desc = $this->input->post('jobs_category_desc');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arrayvalues = array(
		        		'jobs_category'=>$jobs_category,
		        		'jobs_category_desc'=>$jobs_category_desc,
		        		'status'=>$status,
		        		'create_by'=>$admin_id,
		        		'datecreated'=>$datecreated
		        		);

		            $query=$this->db->insert('tbl_jobs_category',$arrayvalues);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNew('tbl_jobs_category',array('jobs_category_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_jobs_category',$data_id);
					}
					break;

					case 'detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_detail'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_invoice',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id = $this->input->post('id');
					$jobs_category = $this->input->post('jobs_category');
					$jobs_category_desc = $this->input->post('jobs_category_desc');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arraywhere = array('jobs_category_id'=>$id);
		        	$arrayvalues = array(
		        		'jobs_category'=>$jobs_category,
		        		'jobs_category_desc'=>$jobs_category_desc,
		        		'status'=>$status,
		        		'update_by'=>$admin_id,
		        		'dateupdated'=>$datecreated
		        	);
		            $query=$this->crud_global->UpdateDefault('tbl_jobs_category',$arrayvalues,$arraywhere);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_jobs_category',array('status'=>0),array('jobs_category_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

	function work_experience()
	{
		$check = $this->m_admin->check_login();
		if($check == true){
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$uri2=$this->uri->segment(2);
				$sess_admin = $this->session->userdata('admin_id');
	    		$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$sess_admin),'admin_group_id');
				$menu_id = $this->crud_global->GetField('tbl_menu',array('menu_alias'=>$uri2,'status !='=>0),'menu_id');
				$update = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'update_action');
				$delete = $this->crud_global->GetField('tbl_admin_access',array('admin_group_id'=>$admin_group_id,'menu_id'=>$menu_id),'delete_action');

				switch($uri3) {
					case 'data_table' :
					$table = 'tbl_work_experience';
				    $column_order = array('work_experience','status','datecreated',null); 
				    $column_search = array('work_experience');
				    $order = array('work_experience_id' => 'desc'); // default order 
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
			            $row[] = $value->work_experience;
			            $row[] = $this->general->GetStatus($value->status);
			            $row[] = $this->waktu->WestConvertion($value->datecreated);

			            $url_del = site_url('ajax_admin/work_experience/delete/'.$value->work_experience_id.'');
			            $url_edit = site_url('ajax_admin/work_experience/form_edit/'.$value->work_experience_id.'');

			            if($update == 1){
				            $btn_edit = '<a class="btn btn-sm btn-primary btn-fancy fancybox.ajax" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			            }else {
			            	$btn_edit = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-pencil"></i> No Access</a>';
			            }

			            if($delete == 1){
				            $btn_delete = '<a class="btn btn-sm btn-danger u-delete" href="javascript:void(0)" title="Hapus" data-box="#mb-delete" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			            }else {
			            	$btn_delete = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" disabled="disabled"><i class="glyphicon glyphicon-trash"></i> No Access</a>';
			            }

			            //add html for action
			            $row[] = $btn_edit.' '.$btn_delete;
			 
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
					break;

					case 'form_add' :
					$this->load->view('back/ajax_form/add_work_experience');
					break;

					case 'add' :
					$output=array('output'=>'false');
					// Get data
					$work_experience = $this->input->post('work_experience');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arrayvalues = array(
		        		'work_experience'=>$work_experience,
		        		'status'=>$status,
		        		'create_by'=>$admin_id,
		        		'datecreated'=>$datecreated
		        		);

		            $query=$this->db->insert('tbl_work_experience',$arrayvalues);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'form_edit' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_edit'] = $this->crud_global->ShowTableNew('tbl_work_experience',array('work_experience_id'=>$uri4));
						$this->load->view('back/ajax_form/edit_work_experience',$data_id);
					}
					break;

					case 'detail' :
					$uri4=$this->uri->segment(4);
					if(!empty($uri4)){
						$data_id['id']=$uri4;
						$data_id['data_detail'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$uri4));
						$this->load->view('back/ajax_detail/detail_invoice',$data_id);
					}
					break;

					case 'edit' :
					$output=array('output'=>'false');
					// Get data
					$id = $this->input->post('id');
					$work_experience = $this->input->post('work_experience');
			        $admin_id = $this->session->userdata('admin_id');
			        $status = $this->input->post('status');
			        $datecreated = date("Y-m-d H:i:s");

			        // insert JSON
					$arraywhere = array('work_experience_id'=>$id);
		        	$arrayvalues = array(
		        		'work_experience'=>$work_experience,
		        		'status'=>$status,
		        		'update_by'=>$admin_id,
		        		'dateupdated'=>$datecreated
		        	);
		            $query=$this->crud_global->UpdateDefault('tbl_work_experience',$arrayvalues,$arraywhere);
		            if($query){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');
		            }
			        echo json_encode($output);
					break;

					case 'delete' :
					$del_id=$this->input->post('del_id');
					if(!empty($del_id)){
						$delete=$this->crud_global->UpdateDefault('tbl_work_experience',array('status'=>0),array('work_experience_id'=>$del_id));
						if($delete){
							echo 'success';
						}else {
							echo 'failed';
						}
					}
					break;

				}
			}
		}else {
			$this->load->view('back/login');
		}
	}

}