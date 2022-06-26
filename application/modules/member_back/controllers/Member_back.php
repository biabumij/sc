<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_back extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','admin/Templates','crud_global','m_themes','pages/m_pages','DB_model','menu/m_menu','posted/m_post','producted/m_product','member_back/m_member_back','m_member'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		date_default_timezone_set('Asia/Jakarta');
	}



	function table()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		

			$uri3 = $this->uri->segment(3);
			$table = 'tbl_member';
		    $column_order = array('tbl_member.member_id','tbl_member.email','tbl_member.member_type','status',null); 
		    $column_search = array('tbl_member_info.name','tbl_member.email');
		    $order = array('tbl_member.member_id' => 'desc'); // default order 
		    $arraywhere = array('status !=' => '0');

		    $column_select = false;
		    $column_join = false;

			$list = $this->m_member_back->get_datatables($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join);
	        $data = array();
	        $no = $_POST['start'];
	        $no_list = 0;
	        foreach ($list as $value) {
	            $no++;
	            $no_list++;
	            $row = array();
	            $row[] = $no;
	            $row[] = $this->m_member_back->GetMemberInfo($value->member_id,'name');
	            $row[] = $value->email;
	            $row[] = $this->m_member_back->GetMemberType($value->member_type);
	            $row[] = $this->general->GetStatus($value->status);

	            $url_detail = site_url('member_back/detail/'.$value->member_id.'');

	            $url_del = site_url('member_back/delete/'.$value->member_id.'');

	            $url_edit = site_url('member_back/form_edit/'.$value->member_id.'');

	            $btn_edit = '<a class="btn btn-sm btn-primary" href="'.$url_edit.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';

	            $btn_delete = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_person('."'".$url_del."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

	            $btn_detail = '<a class="btn btn-sm btn-warning" href="'.$url_detail.'"><i class="glyphicon glyphicon-search"></i> Detail</a>';

	            //add html for action
	            $row[] = $btn_detail." ".$btn_edit." ".$btn_delete;
	 
	            $data[] = $row;
	        }
	        $output = array(
	                    "draw" => $_POST['draw'],
	                    "recordsTotal" => $this->m_member_back->count_all($table,$arraywhere),
	                    "recordsFiltered" => $this->m_member_back->count_filtered($table,$column_order,$column_search,$order,$arraywhere),
	                    "data" => $data,
	                );
	        //output to json format
	        echo json_encode($output);	
		}else {
			redirect('admin');
		}
	}

	function add()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$output=array('output'=>'false');
			// Get data
			$name = $this->input->post('name');
			$member_type = $this->input->post('member_type');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$password = $this->input->post('password');
			$province_id = $this->input->post('province_id');
			$regencie_id = $this->input->post('regencie_id');
			$district_id = $this->input->post('district_id');
			$village_id = $this->input->post('village_id');
			$address = $this->input->post('address');
			$zip_code = $this->input->post('zip_code');
			$member_photo = $this->input->post('member_photo');
	        $status = $this->input->post('status');
	        $no_anggota = $this->input->post('no_anggota');
	        $datecreated = date("Y-m-d H:i:s");

	        // insert JSON
			$check = $this->crud_global->CheckNum('tbl_member',array('email'=>$email,'status !='=>0));
			
			$check_anggota =false;

			if(!empty($no_anggota)){
				$check_anggota = $this->crud_global->CheckNum('tbl_member_info',array('no_anggota'=>$no_anggota));			
			}
	        if($check == true){
	        	$output=array('output'=>'Email has been registered');
	        }else if($check_anggota == true){
	        	$output=array('output'=>'No Anggota has been registered');
	        }else {
	        	$enkrip_pass = $this->enkrip->EnkripPasswordAdmin($password);

	        	$arrayvalues = array(
	        		'email'=>$email,
	        		'password'=>$enkrip_pass,
	        		'photo'=>$member_photo,
	        		'member_type'=>$member_type,
	        		'status'=>$status,
	        		'datecreated'=>$datecreated
	        		);

	            $query=$this->db->insert('tbl_member',$arrayvalues);

	            if($query){
	            	$member_id = $this->db->insert_id();

	            	$arrayvalues_2 = array(
	            		'member_id'=>$member_id,
		        		'no_anggota'=>$no_anggota,
		        		'name'=>$name,
		        		'phone'=>$phone,
		        		'province_id'=>$province_id,
		        		'regencie_id'=>$regencie_id,
		        		'district_id'=>$district_id,
		        		'village_id'=>$village_id,
		        		'address'=>$address,
		        		'zip_code'=>$zip_code,
		        		);

		            $query_2 = $this->db->insert('tbl_member_info',$arrayvalues_2);

		            if($query_2){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');	
		            }
	            }else {
	            	$output=array('output'=>'false');
	            }
	        }
	        echo json_encode($output);
		}else {
			redirect('admin');
		}
	}

	function form_edit()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['data'] = $this->crud_global->ShowTableDefault('tbl_member',array('member_id'=>$uri3));
				$data['data_member'] = $this->crud_global->ShowTableDefault('tbl_member_info',array('member_id'=>$uri3));
				$this->load->view('member_back/form_edit',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function edit()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$output=array('output'=>'false');

			// Get data
			$id=$this->input->post('id');
			$name = $this->input->post('name');
			$member_type = $this->input->post('member_type');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$password = $this->input->post('password');
			$new_password = $this->input->post('new_password');
			$province_id = $this->input->post('province_id');
			$regencie_id = $this->input->post('regencie_id');
			$district_id = $this->input->post('district_id');
			$village_id = $this->input->post('village_id');
			$address = $this->input->post('address');
			$zip_code = $this->input->post('zip_code');
			$member_photo = $this->input->post('member_photo');
	        $status = $this->input->post('status');
	        $no_anggota = $this->input->post('no_anggota');
	        $datecreated = date("Y-m-d H:i:s");

	        // Update JSON
	        // Get Data Old for Filter
	        $email_old = $this->crud_global->GetField('tbl_member',array('member_id'=>$id),'email');
	        if($email_old == $email){
	        	$chek_email = false;
	        }else{
	        	$chek_email = $this->crud_global->CheckNum('tbl_member',array('email'=>$email,'status !='=>0));
	        }

	        $check_anggota = false;
	        if(!empty($no_anggota)){
	        	$anggota_old = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$id),'no_anggota');
		        if($anggota_old == $no_anggota){
		        	$check_anggota = false;
		        }else{
		        	$check_anggota = $this->crud_global->CheckNum('tbl_member_info',array('no_anggota'=>$no_anggota));
		        }
	        }

	        if($chek_email == true){
	        	$output=array('output'=>'Email has been registered');
	        }else if($check_anggota == true){
	        	$output=array('output'=>'No Anggota has been registered');
	        }else {

	        	$arraywhere = array('member_id'=>$id);
	        	if(!empty($new_password)){
	        		$enkrip_pass = $this->enkrip->EnkripPasswordAdmin($new_password);
	        	}else {
	        		$enkrip_pass = $password;
	        	}

	        	$arrayvalues = array(
	        		'email'=>$email,
	        		'password'=>$enkrip_pass,
	        		'photo'=>$member_photo,
	        		'member_type'=>$member_type,
	        		'status'=>$status,
	        	);

	            $query=$this->crud_global->UpdateDefault('tbl_member',$arrayvalues,$arraywhere);
	            if($query){

	            	$arrayvalues_2 = array(
	            		'member_id'=>$id,
		        		'no_anggota'=>$no_anggota,
		        		'name'=>$name,
		        		'phone'=>$phone,
		        		'province_id'=>$province_id,
		        		'regencie_id'=>$regencie_id,
		        		'district_id'=>$district_id,
		        		'village_id'=>$village_id,
		        		'address'=>$address,
		        		'zip_code'=>$zip_code,
		        		'dateupdated' => $datecreated
		        		);

		            $query_2 = $this->crud_global->UpdateDefault('tbl_member_info',$arrayvalues_2,$arraywhere);

		            if($query_2){
		            	$output=array('output'=>'true');
		            }else {
		            	$output=array('output'=>'false');	
		            }
	            }else {
	            	$output=array('output'=>'false');
	            }
	        }
	        echo json_encode($output);
		}else {
			redirect('admin');
		}
	}

	function delete()
	{
		$del_id=$this->input->post('del_id');
		if(!empty($del_id)){
			$delete=$this->crud_global->UpdateDefault('tbl_member',array('status'=>0),array('member_id'=>$del_id));
			if($delete){
				$this->db->delete('tbl_member_info',array('member_id'=> $del_id));
				echo 'success';
			}else {
				echo 'failed';
			}
		}
	}

	function detail()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$data['data'] = $this->crud_global->ShowTableDefault('tbl_member',array('member_id'=>$uri3));
				$data['data_member'] = $this->crud_global->ShowTableDefault('tbl_member_info',array('member_id'=>$uri3));
				$this->load->view('member_back/detail',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function message_detail()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			$lang_id = $this->m_themes->GetThemes('site_language');
			$data_message = $this->crud_global->ShowTableNew('tbl_message',array('message_id'=>$uri3));
			if(!empty($uri3)){

				?>
				<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="modal-label"><?php echo $data_message[0]->subject;?></h4>
                </div>
                <div class="modal-body" id="modal-ajax">
                    <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                    	<tr>
								<th width="150px">Pengirim</th>
								<td width="20px">:</td>
								<td><?php echo $this->m_member->ShowSeller($data_message[0]->user_type,$data_message[0]->user_id);?></td>
							</tr>
							<tr>
								<th width="150px">Tanggal</th>
								<td width="20px">:</td>
								<td><?php echo $this->waktu->BlogDate4($data_message[0]->datecreated);?></td>
							</tr>
							<tr>
								<th width="150px">Product</th>
								<td width="20px">:</td>
								<td><?php echo $this->crud_global->GetField('tbl_product_data',array('product_id'=>$data_message[0]->problem_id,'language_id'=>$lang_id),'product_name');?></td>
							</tr>
							<tr>
								<th width="150px">Subject</th>
								<td width="20px">:</td>
								<td><?php echo $data_message[0]->subject;?></td>
							</tr>
							<tr>
								<th width="150px">Pesan</th>
								<td width="20px">:</td>
								<td><?php echo $data_message[0]->message;?></td>
							</tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
				<?php
			}
		}else {
			redirect('admin');
		}
	}

	function message()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){

				$lang_id = $this->m_themes->GetThemes('site_language');
				$data_message = $this->crud_global->ShowTableNew('tbl_message',array('message_id'=>$uri3));
				$data['id']=$uri3;
				$data['admin_id']=$this->session->userdata('admin_id');
				$data['data_message'] = $this->crud_global->ShowTableNew('tbl_message',array('message_id'=>$uri3));
				$data['data'] = $this->crud_global->ShowTableNew('tbl_message_receive',array('message_id'=>$uri3));
				$data['lang_id'] = $lang_id;
				$this->load->view('member_back/message_detail',$data);
			}
		}else {
			redirect('admin');
		}
	}


	function send_message()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_member->SendMessage();
		}else {
			redirect('admin');
		}
	}

	function activated()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$output=array('output'=>'false');

			// Get data
			$id=$this->input->post('id');
			$arraywhere = array('member_id'=>$id);
        	$arrayvalues = array(
        		'status'=>1,
        	);
            $query=$this->crud_global->UpdateDefault('tbl_member',$arrayvalues,$arraywhere);
            if($query){
	            $output=array('output'=>'true');
            }else {
            	$output=array('output'=>'false');
            }
	        echo json_encode($output);
		}else {
			redirect('admin');
		}
	}

	function table_invoice()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$table = 'tbl_invoice';
		    $column_order = array('invoice_no','total','datecreated','status'); 
		    $column_search = array('invoice_no','total');
		    $order = array('invoice_id' => 'desc'); // default order 
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
	            $row[] = '<a href="'.site_url('member_back/invoice_detail/'.$value->invoice_id).'" >'.$value->invoice_no.'</a>';
	            $row[] = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$value->member_id),'name');
	            $row[] = $this->general->NumberMoney($value->total);
	            $row[] = $this->waktu->WestConvertion($value->datecreated);
	            $row[] = $this->general->GetStatusInvoice($value->status);
	 
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

	function invoice_detail()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$uri3=$this->uri->segment(3);
			if(!empty($uri3)){
				$data['id']=$uri3;
				$lang_id = $this->m_themes->GetThemes('site_language');
				$data['lang_id']= $lang_id;
				$data['data'] = $this->crud_global->ShowTableDefault('tbl_invoice',array('invoice_id'=>$uri3));
				$data['data_invoice'] = $this->crud_global->ShowTableDefault('tbl_invoice_product',array('invoice_id'=>$uri3));
				$member_id = $this->crud_global->GetField('tbl_invoice',array('invoice_id'=>$uri3),'member_id');
				$data['data_member'] = $this->crud_global->ShowTableDefault('tbl_member_info',array('member_id'=>$member_id));
				$this->load->view('member_back/invoice_detail',$data);
			}
		}else {
			redirect('admin');
		}
	}

	function invoice_process()
	{
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->m_member->InvoiceProcess();
		}else {
			redirect('admin');
		}
	}

	function print_invoice()
	{
		$check = $this->m_admin->check_login();
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

}