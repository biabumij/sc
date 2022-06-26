<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Themes_options extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','pages/m_pages'));
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
			$output = array('output'=>'false');
	    	$id=$this->input->post('id');

	    	if(!empty($id)){
	    		$site_name = $this->filter->FilterInput($this->input->post('site_name'));
	    		$site_logo = $this->input->post('site_logo');
	    		$site_favico = $this->input->post('site_favico');
	    		$site_email = $this->filter->FilterInput($this->input->post('site_email'));
	    		$site_description = $this->input->post('site_description');
	    		$site_fb = $this->filter->FilterInput($this->input->post('site_fb'));
	    		$site_tw = $this->filter->FilterInput($this->input->post('site_tw'));
	    		$site_ig = $this->filter->FilterInput($this->input->post('site_ig'));
	    		$site_gp = $this->filter->FilterInput($this->input->post('site_gp'));
	            $site_maps = $this->input->post('site_maps');
	            $site_lan = $this->filter->FilterInput($this->input->post('site_lan'));
	            $site_lat = $this->filter->FilterInput($this->input->post('site_lat'));
	    		$site_phone1 = $this->filter->FilterInput($this->input->post('site_phone1'));
	    		$site_phone2 = $this->filter->FilterInput($this->input->post('site_phone2'));
	            $site_mobile1 = $this->filter->FilterInput($this->input->post('site_mobile1'));
	            $site_mobile2 = $this->filter->FilterInput($this->input->post('site_mobile2'));
	    		$site_address1 = $this->filter->FilterTextarea($this->input->post('site_address1'));
	    		$site_address2 = $this->filter->FilterTextarea($this->input->post('site_address2'));
	    		$site_meta_keywords = $this->filter->FilterInput($this->input->post('site_meta_keywords'));
	    		$site_meta_description = $this->filter->FilterInput($this->input->post('site_meta_description'));
	            $pages_home = $this->input->post('pages_template');

	    		if(empty($site_name)){
	    			$output = array('output'=>'site_name');
	    		}else {
	    			$arrayvalues=array(
	    					'site_name' => $site_name,
	                        'site_logo' => $site_logo,
	                        'site_favico' => $site_favico,
	    					'site_email' => $site_email,
	    					'site_description' => $site_description,
	    					'site_fb' => $site_fb,
	    					'site_tw' => $site_tw,
	    					'site_ig' => $site_ig,
	    					'site_gp' => $site_gp,
	                        'site_maps' => $site_maps,
	                        'site_lan' => $site_lan,
	                        'site_lat' => $site_lat,
	    					'site_phone1' => $site_phone1,
	    					'site_phone2' => $site_phone2,
	                        'site_mobile1' => $site_mobile1,
	                        'site_mobile2' => $site_mobile2,
	    					'site_address1' => $site_address1,
	    					'site_address2' => $site_address2,
	    					'site_meta_keywords' => $site_meta_keywords,
	    					'site_meta_description' => $site_meta_description,
	                        'pages_home' => $pages_home,
	    				);
	    			$query=$this->crud_global->UpdateDefault('tbl_themes_options',$arrayvalues,array('themes_options_id'=>$id));
	    			if($query){
	    				$output = array('output'=>'true');
	    			}else {
	    				$output = array('output'=>'false');
	    			}
	    		}

	    	}else {
				$output = array('output'=>'false');
	    	}

	    	echo json_encode($output);
		}else {
			redirect('admin');
		}
	}

}