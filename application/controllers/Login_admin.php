<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_admin extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		date_default_timezone_set('Asia/Jakarta');
	}

	function index()
	{
		$this->load->library('user_agent');
		$output	= array('output'=>'false');

		$email = $this->filter->FilterInput($this->input->post('email'));
		$password = $this->enkrip->EnkripPasswordAdmin($this->input->post('password'));
		if(!empty($email) && !empty($password)){
			$process = $this->m_admin->ProcessLogin($email,$password);
			if($process == true){
				$redirect = site_url('admin/dashboard');
				if ($this->session->has_userdata('redirect')) {
		            $redirect = site_url($this->session->userdata('redirect'));
		        }
				$output	= array('output'=>'true','alert'=>'Succes Login','redirect'=>$redirect);
			}else {
				$output	= array('output'=>'false','alert'=>'Email and Password Not Match');
			}
		}else {
			$output	= array('output'=>'false');
		}

		echo json_encode($output);
	}

}
					