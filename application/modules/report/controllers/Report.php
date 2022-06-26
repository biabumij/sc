<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','DB_model','menu/m_menu'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		date_default_timezone_set('Asia/Jakarta');
	}

	function table_contact()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_contact';
		    $column_order = array('contact_name','contact_email','contact_phone','contact_message','datecreated'); 
		    $column_search = array('contact_name','contact_email','contact_phone','contact_message');
		    $order = array('contact_id' => 'desc'); // default order 
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
	            $row[] = $value->contact_phone;
	            $row[] = $value->contact_message;
	            $row[] = $this->waktu->WestConvertion($value->datecreated);
	 
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
		}else {
			redirect('admin');
		}
	}

	function table_subscribe()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_subscribe';
		    $column_order = array('email','ip_address',null); 
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
	            $row[] = $this->waktu->WestConvertion($value->datecreated);
	 
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
		}else {
			redirect('admin');
		}
	}

	function table_visitors()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_visitors';
		    $column_order = array('ip','platform','platform_agent',null); 
		    $column_search = array('ip','platform','platform_agent');
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
	            $row[] = $this->waktu->WestConvertion($value->datecreated);
	 
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
		}else {
			redirect('admin');
		}
	}


	function send_subscriber()
	{
		$output = array('output'=>'false');

        $user_id = $this->input->post('user_id');
        $user_type = $this->input->post('user_type');
        $user_type_receive = $this->input->post('user_type_receive');
        $problem_type = $this->input->post('problem_type');
        $problem_id = $this->input->post('problem_id');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');
        $parent_id = $this->input->post('parent_id');
        $ip = $this->input->ip_address();
        $datecreated = date("Y-m-d H:i:s");

        $arrayvalues = array(
            'user_id'=>$user_id,
            'user_type'=>$user_type,
            'problem_type'=>$problem_type,
            'problem_id'=>$problem_id,
            'subject'=>$subject,
            'message'=>$message,
            'ip'=>$ip,
            'parent_id'=>$parent_id,
            'datecreated'=>$datecreated
        );

        $query=$this->db->insert('tbl_message',$arrayvalues);
        if($query){

            $message_id = $this->db->insert_id();

            // $message_receive = $this->input->post('message_receive');

            $arr_subs = $this->crud_global->ShowTableNew('tbl_subscribe');
            if(is_array($arr_subs)){
            	foreach ($arr_subs as $key => $row) {
            		$arrayvalues_2 = array(
		                'message_id'=>$message_id,
		                'email' => $row->email,
		                'is_read'=>1,
		                'is_reply'=>1,
		            );

		            $query_2 =$this->db->insert('tbl_message_receive',$arrayvalues_2);
            	}
            }
            $output = array('output'=>'true');
        }else {
            $output = array('output'=>'false');
        }

        echo json_encode($output);
	}



}