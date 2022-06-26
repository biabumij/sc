<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct()
	{
	   		
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('front_templates','pages/m_pages','crud_global','posted/m_post','m_member','producted/m_product'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('general');
		$this->load->library('session');
		$this->load->library('waktu');
		$this->load->library('cart');
		date_default_timezone_set('Asia/Jakarta');

	}

	function register()
	{
		$output	= array('output'=>'false');

		$email = $this->filter->FilterInput($this->input->post('email'));
		$password = $this->enkrip->EnkripPasswordAdmin($this->input->post('password'));
		$member_type = 1;
		$datecreated = date("Y-m-d H:i:s");
		if(!empty($email) && !empty($password)){

			$check_email = $this->crud_global->CheckNum('tbl_member',array('email'=>$email));
			if($check_email){
				$output	= array('output'=>'false','alert'=>'Your Email has been registered');
			}else {
				$arrayvalues = array(
	              	'email'=>$email,
	              	'password'=>$password,
	        		'member_type'=>$member_type,
	        		'status'=>1,
	        		'datecreated'=>$datecreated
	        	);

	            $query=$this->db->insert('tbl_member',$arrayvalues);
				if($query){
					$member_id = $this->db->insert_id();
					$this->db->insert('tbl_member_info',array('member_id'=>$member_id));

					$session_data = array('member_id'=>$member_id,'member_type'=>$member_type);
			        $this->session->set_userdata($session_data);
					$output	= array('output'=>'true','alert'=>'Succes Register');
				}else {
					$output	= array('output'=>'false','alert'=>'Email and Password Not Match');
				}
			}
		}else {
			$output	= array('output'=>'false');
		}

		echo json_encode($output);
	}

	function anggota()
	{
		$output	= array('output'=>'false');

		$email = $this->filter->FilterInput($this->input->post('email'));
		$password = $this->enkrip->EnkripPasswordAdmin($this->input->post('password'));
		$member_type = 2;
		$datecreated = date("Y-m-d H:i:s");
		$no_anggota = $this->input->post('no_anggota');
		if(!empty($email) && !empty($password)){

			$check_email = $this->crud_global->CheckNum('tbl_member',array('email'=>$email));
			if($check_email){
				$output	= array('output'=>'false','alert'=>'Your Email has been registered');
			}else {

				if(!empty($no_anggota)){

					$check_anggota = $this->crud_global->CheckNum('tbl_member_info',array('no_anggota'=>$no_anggota));
					if($check_anggota){
						$status = 1;
						$arrayvalues = array(
			              	'email'=>$email,
			              	'password'=>$password,
			        		'member_type'=>$member_type,
			        		'status'=>$status,
			        		'datecreated'=>$datecreated
			        	);

			            $query=$this->db->insert('tbl_member',$arrayvalues);
						if($query){
							$member_id = $this->db->insert_id();
							
							$this->crud_global->UpdateDefault('tbl_member_info',array('member_id'=>$member_id),array('no_anggota'=>$no_anggota));
							$session_data = array('member_id'=>$member_id,'member_type'=>$member_type);
			            	$this->session->set_userdata($session_data);	
							$output	= array('output'=>'true','alert'=>'Succes Register');

							$output	= array('output'=>'true','alert'=>'Succes Register');
						}else {
							$output	= array('output'=>'false','alert'=>'Email and Password Not Match');
						}

					}else {
						$output	= array('output'=>'false','alert'=>'No Anggota Anda Belum Terdaftar, harap kosongkan field No Anggota');
					}

				}else {
					$status = 3;
					$arrayvalues = array(
		              	'email'=>$email,
		              	'password'=>$password,
		        		'member_type'=>$member_type,
		        		'status'=>$status,
		        		'datecreated'=>$datecreated
		        	);

		            $query=$this->db->insert('tbl_member',$arrayvalues);
					if($query){
						$member_id = $this->db->insert_id();
						$this->db->insert('tbl_member_info',array('member_id'=>$member_id,'no_anggota'=>$no_anggota));

			            $session_data = array('member_id'=>$member_id,'member_type'=>$member_type);
			            $this->session->set_userdata($session_data);
						$output	= array('output'=>'true','alert'=>'Succes Register');
					}else {
						$output	= array('output'=>'false','alert'=>'Email and Password Not Match');
					}

				}
			}
		}else {
			$output	= array('output'=>'false');
		}

		echo json_encode($output);
	}


	function login()
	{
		$output	= array('output'=>'false');

		$email = $this->filter->FilterInput($this->input->post('email'));
		$password = $this->enkrip->EnkripPasswordAdmin($this->input->post('password'));

		if(!empty($email) && !empty($password)){
			$process = false;
			if(!empty($email) && !empty($password)){
		        $this->db->select("*");
		        $this->db->where('email',$email);
		        $this->db->where('password',$password);
		        $this->db->where('status !=',2);
		        $this->db->limit(1);
		        $query=$this->db->get('tbl_member');
		        if($query->num_rows() > 0){
		            $row=$query->row();
		            $member_id = $row->member_id;
		            $member_type = $row->member_type;
		            $session_data = array('member_id'=>$member_id,'member_type'=>$member_type);

		            $this->session->set_userdata($session_data);
		            $process = true;
		        }else {
		            $process = false;
		        }
		    }else {
		        $process = false;
		    }

			if($process == true){
				$output	= array('output'=>'true','alert'=>'Succes Login');
			}else {
				$output	= array('output'=>'false','alert'=>'Email and Password Not Match');
			}
		}else {
			$output	= array('output'=>'false');
		}

		echo json_encode($output);
	}


	function logout()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$this->session->unset_userdata('member_id');
			$this->session->unset_userdata('member_type');
			// $this->session->sess_destroy();
			redirect('page/home');
		}else {
			redirect('page/home');
		}
	}


	function profile()
	{
		$check = $this->m_member->check_login();
		if($check == true){
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_id = $this->session->userdata('member_id');
			$data['member_id'] = $member_id;
			$data['sess_id'] = $this->session->userdata('member_id');
			$data['lang_id'] = $lang_id;
			$data['pages_name'] = 'Profile';
			$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
			$this->load->view('front/account/profile',$data);	
		}else {
			redirect('page/home');
		}
	}

	function view()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$member_id = $this->uri->segment(3);
		$data['member_id'] = $member_id;
		$data['sess_id'] = $this->session->userdata('member_id');
		$data['lang_id'] = $lang_id;
		$data['pages_name'] = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$member_id),'name');
		$check_member = $this->crud_global->CheckNum('tbl_member',array('member_id'=>$member_id));
		if($check_member){
			$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
			$this->load->view('front/account/profile',$data);	
		}else {
			redirect('page/home');
		}
	}

	function edit()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_id = $this->session->userdata('member_id');
			$data['lang_id'] = $lang_id;
			$data['pages_name'] = 'Edit Profile';
			$data['member_id'] = $member_id;
			$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
			$this->load->view('front/account/edit_profile',$data);	
		}else {
			redirect('page/home');
		}
	}

	function history()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_id = $this->session->userdata('member_id');
			$data['lang_id'] = $lang_id;
			$data['pages_name'] = 'Your Order History';
			$data['member_id'] = $member_id;
			$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
			$this->load->view('front/account/history',$data);	
		}else {
			redirect('page/home');
		}
	}

	function news()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_type = $this->session->userdata('member_type');
		    $member_id = $this->session->userdata('member_id');
		    $status = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'status');

		    if($member_type == 2 && $status == 1){
		    	$data['lang_id'] = $lang_id;
				$data['pages_name'] = 'Your News';
				$data['member_id'] = $member_id;
				$data['post_category_id'] = 6;
				$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
				$uri3 = $this->uri->segment(3);
				$data['member_url'] =  'news';
				if(!empty($uri3)){
					if($uri3 == 'edit'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['post_id'] =  $uri5;
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_edit',$data);	
						}
					}else if($uri3 == 'post_data'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['id'] =  $uri5;
							$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_data',$data);	
						}
					}
				}else {
					$this->load->view('front/account/post',$data);	
				}
		    }else {
		    	redirect('member/profile');
		    }
		}else {
			redirect('page/home');
		}
	}

	function blog()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_type = $this->session->userdata('member_type');
		    $member_id = $this->session->userdata('member_id');
		    $status = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'status');

		    if($member_type == 2 && $status == 1){
		    	$data['lang_id'] = $lang_id;
				$data['pages_name'] = 'Your Blog';
				$data['member_id'] = $member_id;
				$data['post_category_id'] = 1;
				$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
				$uri3 = $this->uri->segment(3);
				$data['member_url'] =  'blog';
				if(!empty($uri3)){
					if($uri3 == 'edit'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['post_id'] =  $uri5;
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_edit',$data);	
						}
					}else if($uri3 == 'post_data'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['id'] =  $uri5;
							$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_data',$data);	
						}
					}
				}else {
					$this->load->view('front/account/post',$data);	
				}
		    }else {
		    	redirect('member/profile');
		    }
		}else {
			redirect('page/home');
		}
	}

	function event()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_type = $this->session->userdata('member_type');
		    $member_id = $this->session->userdata('member_id');
		    $status = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'status');

		    if($member_type == 2 && $status == 1){
		    	$data['lang_id'] = $lang_id;
				$data['pages_name'] = 'Your Event';
				$data['member_id'] = $member_id;
				$data['post_category_id'] = 7;
				$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
				$uri3 = $this->uri->segment(3);
				$data['member_url'] =  'event';
				if(!empty($uri3)){
					if($uri3 == 'edit'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['post_id'] =  $uri5;
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_edit',$data);	
						}
					}else if($uri3 == 'post_data'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['id'] =  $uri5;
							$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_data',$data);	
						}
					}
				}else {
					$this->load->view('front/account/post',$data);	
				}
		    }else {
		    	redirect('member/profile');
		    }
		}else {
			redirect('page/home');
		}
	}

	function video()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_type = $this->session->userdata('member_type');
		    $member_id = $this->session->userdata('member_id');
		    $status = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'status');

		    if($member_type == 2 && $status == 1){
		    	$data['lang_id'] = $lang_id;
				$data['pages_name'] = 'Your Video';
				$data['member_id'] = $member_id;
				$data['post_category_id'] = 5;
				$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
				$uri3 = $this->uri->segment(3);
				$data['member_url'] =  'video';
				if(!empty($uri3)){
					if($uri3 == 'edit'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['post_id'] =  $uri5;
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_edit',$data);	
						}
					}else if($uri3 == 'post_data'){
						$uri4 = $this->uri->segment(4);
						$uri5 = $this->uri->segment(5);
						if(!empty($uri4)){
							$data['post_category_id'] =  $uri4;
							$data['id'] =  $uri5;
							$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
							$data['row'] = $this->crud_global->ShowTableNew('tbl_post',array('post_id'=>$uri5));
							$this->load->view('front/account/post_data',$data);	
						}
					}
				}else {
					$this->load->view('front/account/post',$data);	
				}
		    }else {
		    	redirect('member/profile');
		    }
		}else {
			redirect('page/home');
		}
	}

	function add_post()
	{
		$check = $this->m_member->check_login();
		if($check == true){
			$this->m_post->AddPost();
		}else {
			redirect('page/home');
		}
	}

	function edit_post()
	{
		$check = $this->m_member->check_login();
		if($check == true){
			$this->m_post->EditPost();
		}else {
			redirect('page/home');
		}
	}

	function post_data_process()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$this->m_post->SavePostData();
		}else {
			redirect('admin');
		}
	}


	function uped()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_type = $this->session->userdata('member_type');
		    $member_id = $this->session->userdata('member_id');
		    $status = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'status');

		    if($member_type == 3  || $member_type == 4 && $status == 1){
		    	$data['lang_id'] = $lang_id;
				$data['pages_name'] = 'Your Product';
				$data['member_id'] = $member_id;
				if($member_type == 3){
					$product_category = 6;
				}else if($member_type == 4){
					$product_category = 7;
				}
				$data['member_type'] = $member_type;
				$data['product_category'] = $product_category;
				$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
				$uri3 = $this->uri->segment(3);
				if(!empty($uri3)){
					if($uri3 == 'edit'){
						$uri4 = $this->uri->segment(4);
						$data['pages_name'] = 'Edit Product';
						if(!empty($uri4)){
							$data['id'] =  $uri4;
							$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
							$data['data'] = $this->crud_global->ShowTableNew('tbl_product',array('product_id'=>$uri4));
							$this->load->view('front/account/uped_edit',$data);	
						}
					}else if($uri3 == 'add'){
						$data['pages_name'] = 'Add Product';
						$data['arr_lang'] = $this->crud_global->ShowTable('tbl_language',false,'asc');
						$this->load->view('front/account/uped_add',$data);	
					}else if($uri3 == 'add_product'){
						$this->m_product->AddProduct();
					}else if($uri3 == 'edit_product'){
						$this->m_product->EditProduct();
					}
				}else {
					$this->load->view('front/account/uped',$data);	
				}
		    }else {
		    	redirect('member/profile');
		    }
		}else {
			redirect('page/home');
		}
	}

	function message()
	{
		$check = $this->m_member->check_login();
		if($check == true){
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_id = $this->session->userdata('member_id');
			$data['member_id'] = $member_id;
			$data['sess_id'] = $this->session->userdata('member_id');
			$data['lang_id'] = $lang_id;
			$data['pages_name'] = 'Message';
			$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
			$uri3 = $this->uri->segment(3);

			if(!empty($uri3)){
				$data['message_id'] = $uri3;
				$data['data_message'] = $this->crud_global->ShowTableNew('tbl_message',array('message_id'=>$uri3));
				$user_receive_id = $this->crud_global->GetField('tbl_message_receive',array('message_id'=>$uri3),'user_id');
				$user_receive_type = $this->crud_global->GetField('tbl_message_receive',array('message_id'=>$uri3),'user_type');
				$data['user_receive'] = $user_receive_type;
				$uri5 = $this->uri->segment(4);

				if($user_receive_id == 5){
					$this->crud_global->UpdateDefault('tbl_message_receive',array('is_read'=>2),array('message_id'=>$uri3));
				}
				$this->load->view('front/account/message_detail',$data);	
			}else {
				$this->load->view('front/account/message',$data);	
			}
		}else {
			redirect('page/home');
		}
	}


	function invoice()
	{
		$check = $this->m_member->check_login();
		if($check == true){
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_id = $this->session->userdata('member_id');
			$member_type = $this->session->userdata('member_type');
			$data['member_id'] = $member_id;
			$data['sess_id'] = $this->session->userdata('member_id');
			$data['lang_id'] = $lang_id;
			$data['pages_name'] = 'Invoice Order';
			$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
			$uri3 = $this->uri->segment(3);
			$data['member_type'] = $member_type;

			if(!empty($uri3)){
				$data['id'] = $uri3;
				$data['dataInvoice'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$uri3));
				$member_invoice_id = $this->crud_global->GetField('tbl_invoice',array('invoice_id'=>$uri3),'member_id');
				$data['data_member'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_invoice_id));
				$data['data_invoice'] = $this->crud_global->ShowTableNew('tbl_invoice_product',array('invoice_id'=>$uri3));
				$this->load->view('front/account/invoice_detail',$data);	
			}else {
				$this->load->view('front/account/invoice_order',$data);	
			}
		}else {
			redirect('page/home');
		}
	}

	function print_invoice()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$id = $this->uri->segment(3);
			if(!empty($id)){
				// load dompdf
			    $this->load->helper('dompdf');

			    $invoice_no = $this->crud_global->GetField('tbl_invoice',array('invoice_id'=>$id),'invoice_no');
			    $data['id'] = $id;
			    $data['lang_id'] = $lang_id;
			    $data['row'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$id));
			    $member_id = $this->crud_global->GetField('tbl_invoice',array('invoice_id'=>$id),'member_id');
				$data['data_member'] = $this->crud_global->ShowTableDefault('tbl_member_info',array('member_id'=>$member_id));
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
		}else {
			redirect('admin');
		}
	}

	function invoice_process()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$this->m_member->InvoiceProcess();
		}else {
			redirect('admin');
		}
	}


	function cart()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			$lang_id = $this->m_themes->GetThemes('site_language');
			$member_id = $this->session->userdata('member_id');
			$data['lang_id'] = $lang_id;
			$data['pages_name'] = 'Your Cart';
			$data['member_id'] = $member_id;

			$data['data'] = $this->crud_global->ShowTableNew('tbl_member_info',array('member_id'=>$member_id));
			$this->load->view('front/account/cart_view',$data);	
		}else {
			redirect('page/home');
		}
	}

	function edit_process()
	{
		$check = $this->m_member->check_login();
		if($check == true){		

			$output	= array('output'=>'false');

			$member_id = $this->input->post('member_id');
			$name = $this->input->post('name');
			$phone = $this->input->post('phone');
			$phone_1 = $this->input->post('phone_1');
			$phone_2 = $this->input->post('phone_2');
			$email = $this->input->post('email');
			$province_id = $this->input->post('province_id');
			$regencie_id = $this->input->post('regencie_id');
			$district_id = $this->input->post('district_id');
			$village_id = $this->input->post('village_id');
			$address = $this->input->post('address');
			$zip_code = $this->input->post('zip_code');
			$datecreated = date("Y-m-d H:i:s");

			$arraywhere = array('member_id'=>$member_id);
        	$arrayvalues = array(
        		'name'=>$name,
        		'phone'=>$phone,
        		'phone_1'=>$phone_1,
        		'phone_2'=>$phone_2,
        		'province_id'=>$province_id,
        		'district_id'=>$district_id,
        		'regencie_id'=>$regencie_id,
        		'village_id'=>$village_id,
        		'address'=>$address,
        		'zip_code'=>$zip_code,
        		'dateupdated' => $datecreated
        		);

            $query=$this->crud_global->UpdateDefault('tbl_member_info',$arrayvalues,$arraywhere);

            $this->crud_global->UpdateDefault('tbl_member',array('email'=>$email),$arraywhere);

            if($query){
            	$output	= array('output'=>'true','alert'=>'Succes Edit');
            }else {
            	$output	= array('output'=>'false','alert'=>'Failed Edit');
            }

            echo json_encode($output);
		}else {
			redirect('page/home');
		}
	}

	function upload_photo()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			
			$output = array('output'=>'false');

			$member_id = $this->session->userdata('member_id');
			$config['upload_path']          = './photo_member/';
	        $config['allowed_types']        = 'jpg|png|jpeg';

	        $this->load->library('upload', $config);

	        if ( ! $this->upload->do_upload('file'))
	        {
	                $error = array('error' => $this->upload->display_errors());
	                $output = array('output'=> 'false', 'data' => $error);
	        }
	        else
	        {
	                $data = array('upload_data' => $this->upload->data());

	                $file_path = 'photo_member/'.$data['upload_data']['file_name'];
	                $this->crud_global->UpdateDefault('tbl_member',array('photo'=>$file_path),array('member_id'=>$member_id));
	                $output = array('output'=>'true', 'data'=> $data);
	        }
			echo json_encode($output);
		}else {
			redirect('page/home');
		}
	}

	function select_ajax()
	{
		$id = $this->input->post('val');
		$type = $this->input->post('type');
		$data_id = $this->input->post('data_id');

		if(!empty($id)){
			$arr = $this->crud_global->ShowTableNew($type,array($data_id=>$id));
			if(is_array($arr)){
				$output = "";
				$output .= "<option value=''>.. Select ..</option>";
				foreach ($arr as $key => $row) {
					$output .= "<option value='".$row->id."'>".$row->name."</option>";
				}
				echo $output;
			}
		}
		
	}

	function count_cart()
	{
		$arr_cart = $this->cart->contents();
        $count_cart = count($arr_cart);
        echo $count_cart;
	}


	function add_cart()
	{	
		$check = $this->m_member->check_login();
		if($check == true){	
			$output	= array('output'=>'false');

			$product_id = $this->input->post('product_id');
			$lang_id = $this->input->post('lang_id');
			$qty = $this->input->post('qty');
			if(empty($qty)){
				$qty = 1;
			}

			$price = $this->crud_global->GetField('tbl_product',array('product_id'=>$product_id),'price');
			$name = $this->crud_global->GetField('tbl_product_data',array('language_id'=>$lang_id,'product_id'=>$product_id),'product_name');

			$product_qty = $this->crud_global->GetField('tbl_product',array('product_id'=>$product_id),'quantity');

			$arr_cart = $this->cart->contents();
			$pro_qty = false;
			if(is_array($arr_cart)){
				foreach ($arr_cart as $row) {
					if($row['id'] == $product_id){
						$pro_qty = $row['qty'] + $qty;
					}
				}
			}
			if($pro_qty > $product_qty){
				$output	= array('output'=>'out_stock');
			}else {
				$data = array(
			        'id'      => $product_id,
			        'qty'     => $qty,
			        'price'   => $price,
			        'name'    => $name,
				);

				$add_cart = $this->cart->insert($data);
				if($add_cart){
					$output	= array('output'=>'true',$product_qty=>$qty);
				}else {
					$output	= array('output'=>'false');
				}
			}
			echo json_encode($output);
		}else {
			redirect('page/home');
		}

	}


	function table_cart()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			$arr_cart = $this->cart->contents();
			$data['arr_cart'] = $arr_cart;
			$data['lang_id'] = $this->uri->segment(3);
			$this->load->view('front/ajax_template/table_cart',$data);
		}else {
			redirect('page/home');
		}
		
	}

	function update_cart()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			$this->load->library('cart');
			$output	= array('output'=>'false');

			$id = $this->input->post('id');
			$qty = $this->input->post('qty');	
			$product_id = $this->input->post('product_id');
			$price = $this->input->post('price') * $qty;

			$product_qty = $this->crud_global->GetField('tbl_product',array('product_id'=>$product_id),'quantity');

			if($qty > $product_qty){
				$output	= array('output'=>'out_stock');
			}else {
				$data = array(
			        'rowid'      => $id,
			        'qty'     => $qty,
				);

				$update_cart = $this->cart->update($data);
				if($update_cart){
					$output	= array('output'=>'true');
				}else {
					$output	= array('output'=>'false');
				}
			}
			echo json_encode($output);
		}else {
			redirect('page/home');
		}
	}

	function delete_cart()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			$this->load->library('cart');
			$output	= array('output'=>'false');

			$id = $this->input->post('id');
			$data = array(
		        'rowid'      => $id,
		        'qty'     => 0,
			);
			// $output	= array('output'=>$id);
			$add_cart = $this->cart->update($data);
			if($add_cart){
				$output	= array('output'=>'true');
			}else {
				$output	= array('output'=>'false');
			}
			echo json_encode($output);
		}else {
			redirect('page/home');
		}
	}


	function checkout()
	{
		$check = $this->m_member->check_login();
		if($check == true){		
			// check invoice id
			$arr_cart = $this->cart->contents();
			$count_cart = count($arr_cart);
			if($count_cart > 0){
				
				$add_cart = $this->m_member->AddInvoice();
				// $data['invoice_id'] = $add_cart;
				$this->cart->destroy();
				redirect('member/history/');
			}else {
				redirect('member/cart');
			}
		}else {
			redirect('page/home');
		}
	}

	function order()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			$uri3 = $this->uri->segment(3);

			if(!empty($uri3)){
				$lang_id = $this->m_themes->GetThemes('site_language');
				$member_id = $this->session->userdata('member_id');
			
				$data['lang_id'] = $lang_id;
				$data['pages_name'] = 'Order Received';
				$data['member_id'] = $member_id;
				$data['invoice_id'] = $uri3;
				$data['data'] = $this->crud_global->ShowTableNew('tbl_invoice',array('invoice_id'=>$uri3));
				$this->load->view('front/account/order_received',$data);
			}else {
				redirect('member/cart');
			}
		}else {
			redirect('page/home');
		}
	}


	function post_comment()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			$output	= array('output'=>'false');

			$post_id = $this->input->post('post_id');
			$member_id = $this->input->post('member_id');
			$comment_email = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'email');
			$comment_name = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$member_id),'name');
			if(empty($comment_name)){
				$comment_name = 'User';
			}
			$comment_parent_id = 0;
			$comment_message = $this->input->post('comment_message');
			$datecreated = date("Y-m-d H:i:s");

			$arrayvalues = array(
	          	'post_id'=>$post_id,
	          	'comment_email'=>$comment_email,
	    		'comment_name'=>$comment_name,
	    		'comment_parent_id'=>$comment_parent_id,
	    		'comment_message'=>$comment_message,
	    		'datecreated'=>$datecreated
	    	);

	        $query=$this->db->insert('tbl_comment',$arrayvalues);
			if($query){
				$output	= array('output'=>'true');
			}else {
				$output	= array('output'=>'false');
			}

			echo json_encode($output);
		}else {
			redirect('page/home');
		}
	}


	function load_post_comment()
	{
		$post_id = $this->uri->segment(3);
		$member_id = $this->session->userdata('member_id');
		$arr = $this->crud_global->ShowTableNew('tbl_comment',array('post_id'=>$post_id));
		if(is_array($arr)){
			foreach ($arr as $key => $row) {
				?>
				<div class="item-review">
					<div class="row">
						<div class="col-xs-4 col-sm-2 col-md-2">
							<div class="left-review text-center">
								<?php echo $this->m_member->ShowPhoto($member_id);?>
								<a href="javascript:void(0);"><?php echo $row->comment_name;?></a>
							</div>
						</div>
						<div class="col-xs-8 col-sm-9 col-md-9">
							<div class="right-review">
								<p><?php echo $row->comment_message;?></p>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}

	function product_comment()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			$output	= array('output'=>'false');

			$product_id = $this->input->post('product_id');
			$member_id = $this->input->post('member_id');
			$comment_email = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'email');
			$comment_name = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$member_id),'name');
			if(empty($comment_name)){
				$comment_name = 'User';
			}
			$comment_parent_id = 0;
			$comment_message = $this->input->post('comment_message');
			$datecreated = date("Y-m-d H:i:s");

			$arrayvalues = array(
	          	'product_id'=>$product_id,
	          	'email'=>$comment_email,
	    		'name'=>$comment_name,
	    		'parent_id'=>$comment_parent_id,
	    		'message'=>$comment_message,
	    		'datecreated'=>$datecreated
	    	);

	        $query=$this->db->insert('tbl_product_comment',$arrayvalues);
			if($query){
				$output	= array('output'=>'true');
			}else {
				$output	= array('output'=>'false');
			}

			echo json_encode($output);
		}else {
			redirect('page/home');
		}
	}

	function load_product_comment()
	{
		$product_id = $this->uri->segment(3);
		$member_id = $this->session->userdata('member_id');
		$arr = $this->crud_global->ShowTableNew('tbl_product_comment',array('product_id'=>$product_id));
		if(is_array($arr)){
			foreach ($arr as $key => $row) {

				?>
				<div class="item-review">
					<div class="row">
						<div class="col-xs-4 col-sm-2 col-md-2">
							<div class="left-review text-center">
								<?php echo $this->m_member->ShowPhoto($member_id);?>
								<a href="javascript:void(0);"><?php echo $row->name;?></a>
							</div>
						</div>
						<div class="col-xs-8 col-sm-9 col-md-9">
							<div class="right-review">
								<p><?php echo $row->message;?></p>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}


	function send_message()
	{
		$check = $this->m_member->check_login();
		if($check == true){	
			$this->m_member->SendMessage();

		}else {
			redirect('page/home');
		}

	}


}