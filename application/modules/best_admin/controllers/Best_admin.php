<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Best_admin extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
	}



	public function guest_table($id)
	{	
		$data = array();
		$w_type = $this->input->post('type');
		$w_date = $this->input->post('date');
		$arr_date=false;
		$this->db->where('type',$id);
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			// $arr_date = $start_date.'='.$end_date;
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d")  >=',$start_date);	
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d") <=',$end_date);	
		}
		$this->db->order_by('created_at','desc');
		$query = $this->db->get('team');

		if($query->num_rows() > 0){
			 // $data =  $query->result_array();

			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['name'] = '<a href="javascript:void(0);" onclick="detailModal('.$row['teamId'].')" >'.$row['name'].'</a>';
				$row['created_at'] = date('d F Y H:i:s',strtotime($row['created_at']));
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data,'a'=>$arr_date));
	}

	public function detail_table()
	{	
		$data = array();
		$team_id = $this->input->post('team_id');
		
		$this->db->select('p.*,t.name as team_name');
		$this->db->join('team t','p.teamId = t.teamId','left');
		$this->db->where('p.teamId',$team_id);
		
		$this->db->order_by('p.created_at','desc');
		$query = $this->db->get('player p');

		if($query->num_rows() > 0){
			 // $data =  $query->result_array();

			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['dob'] = date('d F Y',strtotime($row['dob']));
				$row['foto_ktp'] = '<img class="responsive-img" src="'.base_url().'uploads_registration/'.$row['foto_ktp'].'" style="width:100px;" />';
				$row['suratIzin'] = '<img class="responsive-img" src="'.base_url().'uploads_registration/'.$row['suratIzin'].'" style="width:100px;" />';
				$row['created_at'] = date('d F Y H:i:s',strtotime($row['created_at']));
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	public function shoutcaster_table()
	{	
		$data = array();
		$w_date = $this->input->post('date');
		$arr_date=false;
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			// $arr_date = $start_date.'='.$end_date;
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d")  >=',$start_date);	
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d") <=',$end_date);	
		}
		$this->db->order_by('created_at','desc');
		$query = $this->db->get('shoutcasters');

		if($query->num_rows() > 0){
			 // $data =  $query->result_array();

			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['dob'] = date('d F Y',strtotime($row['dob']));
				$row['created_at'] = date('d F Y H:i:s',strtotime($row['created_at']));
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function streamer_table()
	{	
		$data = array();
		$w_date = $this->input->post('date');
		$arr_date=false;
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			// $arr_date = $start_date.'='.$end_date;
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d")  >=',$start_date);	
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d") <=',$end_date);		
		}
		$this->db->order_by('created_at','desc');
		$query = $this->db->get('streamers');

		if($query->num_rows() > 0){
			 // $data =  $query->result_array();

			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['dob'] = date('d F Y',strtotime($row['dob']));
				$row['created_at'] = date('d F Y H:i:s',strtotime($row['created_at']));
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	public function teams_table()
	{	
		$data = array();
		$w_date = $this->input->post('date');
		$arr_date=false;
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			// $arr_date = $start_date.'='.$end_date;
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d")  >=',$start_date);	
			$this->db->where('DATE_FORMAT(created_at, "%Y-%m-%d") <=',$end_date);		
		}
		$this->db->order_by('created_at','desc');
		$query = $this->db->get('teams_q');

		if($query->num_rows() > 0){
			 // $data =  $query->result_array();

			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['logo'] = '<img class="img-responsive" style="width:100px;" src="'.base_url().'uploads_admin/teams/'.$row['logo'].'" />';
				$row['actions'] = '<a href="javascript:void(0);" onclick="players('.$row['id'].')" class="btn btn-info"><i class="fa fa-user"></i> Player</a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function players_table()
	{	
		$data = array();
		$team_id = $this->input->post('team_id');
		$this->db->select('p.*, t.name as team_id');
		$this->db->join('teams_q t','p.team_id = t.id','left');
		$this->db->where('p.team_id',$team_id);
		$this->db->order_by('p.name','asc');
		$query = $this->db->get('players_q p');

		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['photo'] = '<img class="img-responsive" style="width:100px;" src="'.base_url().'uploads_admin/players/'.$row['photo'].'" />';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function form_team_tournament()
	{
		$output['output'] = false;
		$type = $this->input->post('type');
		$name = $this->input->post('name');
		$nickname = $this->input->post('nickname');

		

		$logo = '';

		// Upload email
		$config['upload_path']          = './uploads_admin/teams/';
        $config['allowed_types']        = 'jpg|png|jpeg|';
        $config['overwrite']			= true;
        $config['encrypt_name']			= true;

        $this->load->library('upload', $config);

		if($_FILES["logo"]["error"] == 0) {
			if (!$this->upload->do_upload('logo'))
			{
					$error = $this->upload->display_errors();
					$fotoKtp = $error;
			}else{
					$data = $this->upload->data();
					$logo = $data['file_name'];
			}
		}

		$data_team = array(
			'type'		=> $type,
			'name'		=> $name,
			'nickname'		=> $nickname,
			'logo'	=> $logo,
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		if($this->db->insert('teams_q',$data_team)){

			
		}

		$this->db->trans_complete(); # Completing transaction

		/*Optional*/

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$output['output'] = false;
		} 
		else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$output['output'] = true;
		}


		echo json_encode($output);
	}

	public function form_player()
	{
		$output['output'] = false;
		$team_id = $this->input->post('team_id');
		$name = $this->input->post('name');
		$nickname = $this->input->post('nickname');
		$photo = '';

		// Upload email
		$config['upload_path']          = './uploads_admin/players/';
        $config['allowed_types']        = 'jpg|png|jpeg|';
        $config['overwrite']			= true;
        $config['encrypt_name']			= true;

        $this->load->library('upload', $config);

		if($_FILES["photo"]["error"] == 0) {
			if (!$this->upload->do_upload('photo'))
			{
					$error = $this->upload->display_errors();
					$fotoKtp = $error;
			}else{
					$data = $this->upload->data();
					$photo = $data['file_name'];
			}
		}

		$data_team = array(
			'team_id'		=> $team_id,
			'name'		=> $name,
			'nickname'		=> $nickname,
			'photo'	=> $photo,
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		$this->db->insert('players_q',$data_team);

		$this->db->trans_complete(); # Completing transaction

		/*Optional*/

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$output['output'] = false;
		} 
		else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$output['output'] = true;
		}


		echo json_encode($output);
	}


	public function tournament_table()
	{	
		$data = array();
		$this->db->order_by('created_at','asc');
		$query = $this->db->get('type_tournament');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['actions'] = '<a href="javascript:void(0);" onclick="players('.$row['id'].')" class="btn btn-info"><i class="fa fa-user"></i> Teams</a> <a href="javascript:void(0);" onclick="points('.$row['id'].')" class="btn btn-info"><i class="fa fa-list"></i> Points</a> <a href="javascript:void(0);" onclick="editForm('.$row['id'].')" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function get_edit()
	{	
		$data = array();
		$this->db->where('id',$this->input->post('id'));
		$query = $this->db->get('type_tournament');
		if($query->num_rows() > 0){
			$data = $query->row_array();

		}
		echo json_encode(array('data'=>$data));
	}

	public function detail_tournament()
	{	
		$data = array();

		$this->db->select('p.*, t.name as team_id');
		$this->db->where('p.type_tournament_id',$this->input->post('id'));
		$this->db->join('teams_q t','p.team_id = t.id','left');
		$this->db->order_by('created_at','desc');
		$query = $this->db->get('tournaments p');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function get_teams()
	{	
		$data = array();
		$this->db->select('id,name');
		$this->db->where('type',$this->input->post('id'));
		$query = $this->db->get('teams_q');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function get_team_points()
	{	
		$data = array();
		$this->db->select('p.*, t.name as team_id');
		$this->db->where('p.type_tournament_id',$this->input->post('id'));
		$this->db->where('p.gender',$this->input->post('cat'));
		$this->db->join('teams_q t','p.team_id = t.id','left');
		$this->db->order_by('t.name','asc');
		$query = $this->db->get('tournaments p');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}
	
	public function form_tournament()
	{
		$output['output'] = false;
		$q_id = $this->input->post('q_id');
		$name = $this->input->post('name');
		$date = $this->input->post('date');
		$prize = $this->input->post('prize');
		$status = $this->input->post('status');
		

		

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		if(empty($q_id)){
			$data_team = array(
				'name'		=> $name,
				'date'		=> $date,
				'prize'		=> $prize,
				'status'		=> $status,
				'created_at' => date('Y-m-d H:i:s')
			);
			$this->db->insert('type_tournament',$data_team);	
		}else {
			$data_team = array(
				'name'		=> $name,
				'date'		=> $date,
				'prize'		=> $prize,
				'status'		=> $status,
			);
			$this->db->update('type_tournament',$data_team,array('id'=>$q_id));
		}
		

		$this->db->trans_complete(); # Completing transaction

		/*Optional*/

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$output['output'] = false;
		} 
		else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$output['output'] = true;
		}


		echo json_encode($output);
	}

	public function form_tournament_team()
	{
		$output['output'] = false;
		$team_id = $this->input->post('team_id');
		$id = $this->input->post('id');
		$type = $this->input->post('type');

		$data_team = array(
			'type_tournament_id'		=> $id,
			'team_id'	=> $team_id,
			'type' => $type,
			'created_at' => date('Y-m-d H:i:s')
		);


		$check = $this->db->get_where('tournaments',array('team_id'=>$team_id,'type_tournament_id'=>$id));
		if($check->num_rows() > 0){
			$output['output'] = 'full';
		}else {
			$this->db->trans_start(); # Starting Transaction
			$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
			$this->db->insert('tournaments',$data_team);

			$this->db->trans_complete(); # Completing transaction

			/*Optional*/

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$output['output'] = false;
			} 
			else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$output['output'] = true;
			}	
		}
		


		echo json_encode($output);
	}


	public function form_points()
	{
		$output['output'] = false;
		$team_total = $this->input->post('team_total');
		

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		for ($i=1; $i <= $team_total ; $i++) { 
			$data_team = array(
				'wwcd' => $this->input->post('wwcd_'.$i),
				'pp' => $this->input->post('pp_'.$i),
				'kp' => $this->input->post('kp_'.$i),
				'tp' => $this->input->post('tp_'.$i),
			);
			$this->db->update('tournaments',$data_team,array('id'=>$this->input->post('id_'.$i),));
		}

		

		$this->db->trans_complete(); # Completing transaction

		/*Optional*/

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$output['output'] = false;
		} 
		else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$output['output'] = true;
		}	
		


		echo json_encode($output);
	}
	





}	