<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Base_Controller extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
 
        //do whatever you want to do when object instantiate
    }
}
 
class Secure_Controller extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
        if(empty($this->session->userdata('admin_id')))
        {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->set_userdata('redirect', $uri);
            redirect('admin');
        }
    }
}