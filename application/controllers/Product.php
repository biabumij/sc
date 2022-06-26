<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	

	public function __construct()
	{
	   		
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('front_templates','pages/m_pages','crud_global','posted/m_post','producted/m_product','m_member','admin/m_admin'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('general');
		$this->load->library('session');
		$this->load->library('waktu');
		date_default_timezone_set('Asia/Jakarta');
		
	}

	function index()
	{
		$uri2 = $this->uri->segment(2);
		$lang_id = $this->m_themes->GetThemes('site_language');
		$data['lang_id'] = $lang_id;
		$this->m_admin->GetVisitors();
		if(!empty($uri2)){

			$member_id = $this->session->userdata('member_id');	
			$data['member_id'] = $member_id;
			$data['lang_id'] = $lang_id;
			$data['product_id'] = $uri2;
			$data['pages_name'] = $this->crud_global->GetField('tbl_product_data',array('language_id'=>$lang_id,'product_id'=>$uri2),'product_name');
			$data['product_des'] = $this->crud_global->GetField('tbl_product_data',array('language_id'=>$lang_id,'product_id
				'=>$uri2),'product_des');
			$data['product_image'] = $this->crud_global->GetField('tbl_product_gallery',array('product_id'=>$uri2),'product_image');
			$data['product_short_des'] = $this->crud_global->GetField('tbl_product_data',array('language_id'=>$lang_id,'product_id'=>$uri2),'product_short_des');
			$data['product_meta_des'] = $this->crud_global->GetField('tbl_product_data',array('language_id'=>$lang_id,'product_id'=>$uri2),'product_meta_des');
			$data['product_keywords'] = $this->crud_global->GetField('tbl_product_data',array('language_id'=>$lang_id,'product_id'=>$uri2),'product_keywords');

			$data['data'] = $this->m_product->ShowProduct(array('product_id'=>$uri2));
			$data['search'] = false;
			$this->load->view('front/templates/uped_detail',$data);

		}else {
			$pages_home = $this->m_themes->GetThemes('pages_home');
			$pages_id = $this->crud_global->GetField('tbl_pages',array('pages_alias'=>$pages_home,'status'=>1),'pages_id');
			$pages_name = $this->m_pages->ShowPagesDetail($pages_id,$lang_id,'pages_name');
			$data['pages_name'] = $pages_name;
			$this->load->view('front/templates/'.$pages_home,$data);
		}
		
	}

	function search()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$member_id = $this->session->userdata('member_id');	
		$search =  $this->input->post('search');
		$arr = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1,'pages_alias'=>'uped'));
		$data['member_id'] = $member_id;
		$data['pages_name'] = "Search ".$search;
		$data['pages_alias'] = 'uped';
		$data['pages_id'] = $arr[0]->pages_id;
		$data['lang_id'] = $lang_id;
		$data['search'] = str_replace(' ', '_', $search);
		$this->load->view('front/templates/uped',$data);
	}


	// function show()
	// {
	// 	// if(empty($search)){
	// 	// 	$search = false;
	// 	// }
	// 	// $data['arr_pro'] = $this->m_product->ShowProduct(false,8,false,false,false,false,$search);
	// 	// $this->load->view('front/load_products',$data);
		
	// }

}