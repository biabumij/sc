<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

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
			$arr_menu = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1));
			if(is_array($arr_menu)){
				foreach ($arr_menu as $key => $row) {
					if($uri2 == $row->pages_alias){
						$pages_name = $this->m_pages->ShowPagesDetail($row->pages_id,$lang_id,'pages_name');
						$member_id = $this->session->userdata('member_id');
						$data['member_id'] = $member_id;
						$data['pages_name'] = $pages_name;
						$data['pages_alias'] = $uri2;
						$data['pages_id'] = $row->pages_id;
						$this->load->view('front/templates/'.$row->pages_template,$data);
					}
				}
			}
		}else {
			$pages_home = $this->m_themes->GetThemes('pages_home');
			$pages_id = $this->crud_global->GetField('tbl_pages',array('pages_alias'=>$pages_home,'status'=>1),'pages_id');
			$pages_name = $this->m_pages->ShowPagesDetail($pages_id,$lang_id,'pages_name');
			$member_id = $this->session->userdata('member_id');
			$data['member_id'] = $member_id;
			$data['pages_name'] = $pages_name;
			$data['pages_id'] = $pages_id;
			$this->load->view('front/templates/'.$pages_home,$data);
		}
		
	}


	public function home()
	{
		echo 'ta';
	}

	function page_not_found()
	{
		// $this->load->view('error');
		echo 'tes';
	}


}