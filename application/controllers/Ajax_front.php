<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_front extends CI_Controller {

	public function __construct()
	{
	   		
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('front_templates','pages/m_pages','crud_global','posted/m_post','producted/m_product','m_member','admin/m_admin'));
        $this->load->library('session');
        $this->load->library('enkrip');
        $this->load->library('cart');
        $this->load->library('waktu');
        date_default_timezone_set('Asia/Jakarta');
	}


	function contact_process()
	{
		$output=array('output'=>'false');
		
		$name=$this->input->post('name');
		$subject=$this->input->post('subject');
		$email=$this->input->post('email');
		$phone=$this->input->post('phone');
		$message=$this->input->post('message');
		$datecreated=date('Y-m-d H:i:s');
		$ip_address =$this->input->ip_address();

		if(empty($name)){
			$name = "";
		}

		if(empty($subject)){
			$subject = "";
		}

		if(empty($phone)){
			$phone = "";
		}
		
		$arrayvalues = array(
                'contact_name' => $name,
                'contact_address' => $subject,
                'contact_email' => $email,
                'contact_phone' => $phone,
                'contact_message' => $message,
				'ip_address' => $ip_address,
                'datecreated' => $datecreated
            );
        $query=$this->db->insert('tbl_contact',$arrayvalues);
		if($query){
			$this->m_member->sendMailContactAdmin($name,$email,$phone,$message);
			$this->m_member->sendMailContact($email);
			$output=array('output'=>'true');
		}else {
			$output=array('output'=>'false');
		}
			
		echo json_encode($output);
	}


	function subscribe_process()
	{
		$output=array('output'=>'false');
		
		$email=$this->input->post('email');
		$datecreated=date('Y-m-d H:i:s');
		$ip_address =$this->input->ip_address();
		
		$arrayvalues = array(
                'email' => $email,
				'ip_address' => $ip_address,
                'datecreated' => $datecreated
            );
        $query=$this->db->insert('tbl_subscribe',$arrayvalues);
		if($query){
			$this->m_member->sendMailSubs($email);
			$this->m_member->sendMailSubsAdmin($email,$ip_address,$datecreated);
			$output=array('output'=>'true');
		}else {
			$output=array('output'=>'false');
		}
			
		echo json_encode($output);
	}

	

	

	function load_products()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$data['lang_id'] = $lang_id;
		$member_id = $this->session->userdata('member_id');	
		$data['member_id'] = $member_id;
		$page = $this->uri->segment(3);
		$limit = 8;
		$data['limit'] = $limit;
		$data['page'] = $page;
		$search = $this->uri->segment(4);
		if(empty($search)){
			$search = false;
		}else {
			$search = str_replace("_", " ", $search);
		}

		$total = count($this->m_product->ShowProduct(false,false,false,false,false,false,$search));
		if(empty($page)){
			$start = 0;
			$arr_page = array($limit=>$start);
		}else{
			$start = $limit * ($page - 1);
			$arr_page = array($limit=>$start);
		}
		$arr_pro = $this->m_product->ShowProduct(false,$limit,$arr_page,false,false,false,$search);

		$data['arr_pro'] = $arr_pro;
		$data['total'] = $total;
		$this->load->view('front/ajax_template/load_products',$data);
	}


	function load_news()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$data['lang_id'] = $lang_id;
		$member_id = $this->session->userdata('member_id');	
		$data['member_id'] = $member_id;
		$page = $this->uri->segment(3);
		$limit = 8;
		$data['limit'] = $limit;
		$data['page'] = $page;
		$search = $this->uri->segment(4);
		if(empty($search)){
			$search = false;
		}

		$total = count($this->m_post->ShowPost('news'));
		if(empty($page)){
			$start = 0;
			$arr_page = array($limit=>$start);
		}else{
			$start = $limit * ($page - 1);
			$arr_page = array($limit=>$start);
		}
		$arr_news = $this->m_post->ShowPost('news',false,$limit,$arr_page);

		$data['arr_news'] = $arr_news;
		$data['total'] = $total;

		$this->load->view('front/ajax_template/load_news',$data);
	}


	function load_event()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$data['lang_id'] = $lang_id;
		$member_id = $this->session->userdata('member_id');	
		$data['member_id'] = $member_id;
		$page = $this->uri->segment(3);
		$limit = 8;
		$data['limit'] = $limit;
		$data['page'] = $page;


		$total = count($this->m_post->ShowPost('event'));
		if(empty($page)){
			$start = 0;
			$arr_page = array($limit=>$start);
		}else{
			$start = $limit * ($page - 1);
			$arr_page = array($limit=>$start);
		}
		$arr_event = $this->m_post->ShowPost('event',false,$limit,$arr_page);

		$data['arr_event'] = $arr_event;
		$data['total'] = $total;

		$this->load->view('front/ajax_template/load_event',$data);
	}

	function load_blog()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$data['lang_id'] = $lang_id;
		$member_id = $this->session->userdata('member_id');	
		$data['member_id'] = $member_id;
		$page = $this->uri->segment(3);
		$limit = 8;
		$data['limit'] = $limit;
		$data['page'] = $page;

		$search = $this->uri->segment(4);
		if(empty($search)){
			$search = false;
		}else {
			$search = str_replace("_", " ", $search);
		}

		$total = count($this->m_post->ShowPost('blog'));
		if(empty($page)){
			$start = 0;
			$arr_page = array($limit=>$start);
		}else{
			$start = $limit * ($page - 1);
			$arr_page = array($limit=>$start);
		}
		$arr_blog_other = $this->m_post->ShowPost('blog',false,$limit,$arr_page,false,false,$search);

		$data['arr_blog_other'] = $arr_blog_other;
		$data['total'] = $total;

		$this->load->view('front/ajax_template/load_blog',$data);
	}

}