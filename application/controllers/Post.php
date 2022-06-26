<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

	public function __construct()
	{
	   		
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('front_templates','pages/m_pages','crud_global','posted/m_post','m_member','admin/m_admin','producted/m_product'));
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
			$uri3 = $this->uri->segment(3);
			$arr = $this->crud_global->ShowTableNew('tbl_post',array('status'=>1));
			$post_category_alias = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$uri2),'post_category_alias');
			$post_category = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$uri2),'post_category');
			$post_comment = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$uri2),'post_comment');
			if($post_category_alias == 'portfolio'){
				if(is_array($arr)){
					foreach ($arr as $key => $row) {
						if($row->post_id == $uri3){
							$data['post_id'] = $uri3;
							$data['post_category_id'] = $uri2;
							$data['post'] = $this->crud_global->ShowTableNew('tbl_post',array('status'=>1,'post_id'=>$uri3));
							$data['pages_name'] = $this->m_post->ShowDataPost($lang_id,$uri3,'title');
							$member_id = $this->session->userdata('member_id');
							$data['member_id'] = $member_id;
							$this->load->view('front/templates/portfolio_detail',$data);	
						}
					}
				}
			}else {
				if(is_array($arr)){
					foreach ($arr as $key => $row) {
						if($row->post_id == $uri3){
							$data['post_id'] = $uri3;
							$data['post_category_id'] = $uri2;
							$data['post'] = $this->crud_global->ShowTableNew('tbl_post',array('status'=>1,'post_id'=>$uri3));
							$data['post_category_alias'] = $post_category_alias;
							$data['post_category'] = $post_category;
							$data['post_comment'] = $post_comment;
							$member_id = $this->session->userdata('member_id');
							$data['member_id'] = $member_id;
							$data['pages_name'] = $this->m_post->ShowDataPost($lang_id,$uri3,'title');
							$this->load->view('front/templates/product_detail',$data);	
						}
					}
				}
			}
		}else {
			echo 'Page Not Found';
		}
		
	}

	function search_blog()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$member_id = $this->session->userdata('member_id');	
		$search =  $this->input->post('search');
		$arr = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1,'pages_alias'=>'blog'));
		$data['member_id'] = $member_id;
		$data['pages_name'] = "Search ".$search;
		$data['pages_alias'] = 'blog';
		$data['pages_id'] = $arr[0]->pages_id;
		$data['lang_id'] = $lang_id;
		$data['search'] = str_replace(' ', '_', $search);
		$this->load->view('front/templates/blog',$data);
	}

	function search()
	{
		$lang_id = $this->m_themes->GetThemes('site_language');
		$member_id = $this->session->userdata('member_id');	
		$search =  $this->input->post('search');
		$data['member_id'] = $member_id;
		$data['pages_name'] = "Search ".$search;
		$data['lang_id'] = $lang_id;
		$data['search'] = $search;
		$this->load->view('front/templates/post_search',$data);
	}



}