<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_planning extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model','admin/Templates'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		$this->m_admin->check_login();
	}	


	// Product

	public function manage()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$id = $this->uri->segment(4);
			$data['id'] = $id;
			$get_data = $this->db->get_where('pmm_schedule',array('id'=>$id,'status !='=>'DELETED'))->row_array();
			if(!empty($get_data)){
				$data['data'] = $get_data;
				$data['schedule_name'] = $get_data['schedule_name'];
				$data['schedule_date'] = explode(' - ', $get_data['schedule_date']);

				$data['products'] = $this->pmm_model->GetProduct2();
				$this->load->view('pmm/production_planning_add',$data);
			}else {
				redirect('admin/production_planning');
			}
			
		}else {
			redirect('admin');
		}
	}

	public function table()
    {   
        $data = array();

        $this->db->select('ps.*, p.nama as client_name');
        $this->db->join('penerima p','ps.client_id = p.id','left');
        $this->db->where('ps.status !=','DELETED');
        $this->db->order_by('ps.created_on','desc');
        $query = $this->db->get('pmm_schedule ps');

        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $arr_date = explode(' - ', $row['schedule_date']);
                $row['schedule_name'] = '<a href="'.site_url('pmm/production_planning/get_detail_schedule/'.$row['id']).'" target="_blank" >'.$row['schedule_name'].'</a>';
                $row['schedule_date'] = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));
                $row['created_on'] = date('d F Y',strtotime($row['created_on']));
                $row['week_1'] = $this->pmm_model->TotalSPOWeek($row['id'],1);
                $row['week_2'] = $this->pmm_model->TotalSPOWeek($row['id'],2);
                $row['week_3'] = $this->pmm_model->TotalSPOWeek($row['id'],3);;
                $row['week_4'] = $this->pmm_model->TotalSPOWeek($row['id'],4);
                $change_plan = false;

                $delete = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';

                if($row['status'] == 'DRAFT'){
                    
                    $edit = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
                }else {
                    $edit = false;
                }
                $row['status'] = $this->pmm_model->GetStatus($row['status']);

                
                
                $row['actions'] = '<a href="'.site_url('pmm/production_planning/manage/'.$row['id']).'" class="btn btn-info"><i class="fa fa-gears"></i> </a> '.$edit.' '.$delete;
                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }


	public function form_process()
    {
        $output['output'] = false;

        $id = $this->input->post('id');
        // $no_spo = $this->pmm_model->GetNoSPO();
        $no_spo = '01-'.$this->input->post('no_spo');
        $schedule_date = $this->input->post('schedule_date');

        $schedule_name = date('F',strtotime($no_spo)).' '.date('Y',strtotime($no_spo));
        

        $data = array(
            'no_spo' => $no_spo,
            'schedule_name' => $schedule_name,
            'schedule_date' => $schedule_date,
        );

        // $check = $this->db->get_where('pmm_schedule',array('no_spo'=>$no_spo,'status !='=>'DELETED'))->num_rows();

        // if($check > 0){
        //  $output['err'] = 'No SPO has been added !!!';
        //  $output['output'] = false;
        // }else {
            if(!empty($id)){
                $data['updated_by'] = $this->session->userdata('admin_id');
                if($this->db->update('pmm_schedule',$data,array('id'=>$id))){
                    $output['output'] = true;
                }
            }else{
                $data['created_by'] = $this->session->userdata('admin_id');
                $data['created_on'] = date('Y-m-d H:i:s');
                $data['status'] = 'DRAFT';
                if($this->db->insert('pmm_schedule',$data)){
                    $output['output'] = true;
                }   
                    
            }   
        // }
        
        
        echo json_encode($output);  
    }

	public function week_process()
	{
		$output['output'] = false;

		$id = $this->input->post('schedule_id');
		$week_1 = $this->input->post('week_1');
		$week_2 = $this->input->post('week_2');
		$week_3 = $this->input->post('week_3');
		$week_4 = $this->input->post('week_4');


		$data = array(
			'week_1' => $week_1,
			'week_2' => $week_2,
			'week_3' => $week_3,
			'week_4' => $week_4,
		);

		if(!empty($id)){
			$get_sp = $this->db->select('id')->get_where('pmm_schedule_product',array('schedule_id'=>$id))->result_array();
			foreach ($get_sp as $key => $sp) {
				$this->db->delete('pmm_schedule_product_date',array('schedule_product_id'=>$sp['id']));
				$this->db->delete('pmm_schedule_material',array('schedule_product_id'=>$sp['id']));
			}
			$this->db->delete('pmm_schedule_product',array('schedule_id'=>$id));
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_schedule',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		
		echo json_encode($output);	
	}

	public function table_schedule_product()
	{	
		$data = array();

		$this->db->select('psp.*, SUM(pspd.koef) as total, pp.nama_produk as product_name');
		$this->db->join('pmm_schedule_product_date pspd','psp.id = pspd.schedule_product_id','left');
		$this->db->join('produk pp','psp.product_id = pp.id','left');
		$this->db->where('psp.status !=','DELETED');
		$this->db->where('psp.schedule_id',$this->input->post('schedule_id'));
		$this->db->order_by('pp.nama_produk','asc');
		$this->db->group_by('psp.product_id');
		$query = $this->db->get('pmm_schedule_product psp');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $total_koef = $this->db->select('SUM(koef) as total')->group_by('product_id')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$row['id']))->row_array();
				$row['product'] =$row['product_name'];
				$name = "'".$row['activity']."'";
				$row['total'] = '<a href="javascript:void(0);" onclick="DetailMaterial('.$row['id'].','.$name.')">'.$row['total'].'</a>';
				//$row['total'] = $row['total'];
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));

				$get_status = $this->crud_global->GetField('pmm_schedule',array('id'=>$row['schedule_id']),'status');
				if($get_status == 'DRAFT'){
					$row['actions'] = '<a href="javascript:void(0);" onclick="FormDetail('.$row['id'].','.$name.')" class="btn btn-info"><i class="fa fa-search"></i> Manage</a>  <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}
				
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function get_detail()
	{
		$id = $this->input->post('id');
		$data = $this->pmm_model->GetScheduleProductDetail($id);


		echo json_encode(array('data'=>$data));
	}

	public function get_materials()
	{
		$id = $this->input->post('id');
		$data = $this->pmm_model->GetScheduleProductMaterials($id);


		echo json_encode(array('data'=>$data));
	}

	public function get_data()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('no_spo,id,schedule_name,schedule_date')->get_where('pmm_schedule',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}


	public function delete()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'status' => 'DELETED'
			);
			if($this->db->update('pmm_schedule',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	public function get_detail_schedule()
	{
		// def("DOMPDF_ENABLE_REMOTE", false);
		$this->load->library('pdf');
		

		
		// $this->pdfgenerator->generate($html,$row['no_spo']);

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$id = $this->uri->segment(4);
		$row = $this->db->get_where('pmm_schedule',array('id'=>$id))->row_array();
        // $pdf->set_header_title('Laporan');
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['data'] = $this->pmm_model->GetScheduleDetail($id);
		$data['data_week'] = $this->pmm_model->GetScheduleProduct($id);
		$row['week_1'] = $this->pmm_model->TotalSPOWeek($row['id'],1);
		$row['week_2'] = $this->pmm_model->TotalSPOWeek($row['id'],2);
		$row['week_3'] = $this->pmm_model->TotalSPOWeek($row['id'],3);
		$row['week_4'] = $this->pmm_model->TotalSPOWeek($row['id'],4);
		$data['row'] = $row;
		$data['id'] = $id;
        $html = $this->load->view('pmm/schedule_detail_pdf',$data,TRUE);

        
        $pdf->SetTitle($row['schedule_name']);
        $pdf->nsi_html($html);
        $pdf->Output($row['schedule_name'].'.pdf', 'I');
	}

	public function product_process()
	{
		$output['output'] = false;

		$schedule_id = $this->input->post('schedule_id');
		$product_id = $this->input->post('product_id');


		$activity = $this->crud_global->GetField('produk',array('id'=>$product_id),'nama_produk');

		$get_schedule = $this->db->select('week_1,week_2,week_3,week_4')->get_where('pmm_schedule',array('id'=>$schedule_id,'status'=>'DRAFT'))->row_array();


		// $composition_id = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'composition_id');

		// $materials = $this->db->select('material_id,koef,cost,supplier_id,penawaran_material_id')->get_where('pmm_composition_detail',array('composition_id'=>$composition_id))->result_array(); 

		if($get_schedule['week_1'] == '' || $get_schedule['week_2'] == '' || $get_schedule['week_3'] == '' || $get_schedule['week_4'] == ''){
			$output['output'] = false;
			$output['err'] = 'Please Set Week First !!!';
		}else {

			$this->db->trans_start(); # Starting Transaction
			$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

			$data_p = array(
				'schedule_id' => $schedule_id,
				'product_id' => $product_id,
				'activity' => $activity,
				'created_by' => $this->session->userdata('admin_id'),
				'created_on' => date('Y-m-d H:i:s'),
				'status' => 'PUBLISH'
			);

			
			if($this->db->insert('pmm_schedule_product',$data_p)){
				$schedule_product_id = $this->db->insert_id();

				for ($i=1; $i <=4 ; $i++) { 
					$arr_date = explode(' - ', $get_schedule['week_'.$i]);
					$period = new DatePeriod(
					     new DateTime(date('Y-m-d',strtotime($arr_date[0]))),
					     new DateInterval('P1D'),
					     new DateTime(date('Y-m-d',strtotime("+1 day",strtotime($arr_date[1]))))
					);
					foreach ($period as $key => $date) {

						$data = array(
							'schedule_product_id' => $schedule_product_id,
							'product_id' => $product_id,
							'week' => $i,
							'date' => $date->format('Y-m-d'),
							'created_by' => $this->session->userdata('admin_id'),
							'created_on' => date('Y-m-d H:i:s')
						);

						$this->db->insert('pmm_schedule_product_date',$data);

						$this->db->insert('pmm_schedule_product_date_fixed',$data);
					}
				}

				// foreach ($materials as $key => $material) {
				// 	// $get_m = $this->db->select('price,cost')->get_where('pmm_materials',array('id'=>$material['material_id']))->row_array();
				// 	$data_m = array(
				// 		'schedule_product_id' => $schedule_product_id,
				// 		'product_id' => $product_id,
				// 		'supplier_id' => $material['supplier_id'],
				// 		'material_id' => $material['material_id'],
				// 		'penawaran_material_id' => $material['penawaran_material_id'],
				// 		'koef' => $material['koef'],
				// 		'price' => $material['cost'],
				// 		'cost' => $material['cost'],
				// 		'created_by' => $this->session->userdata('admin_id'),
				// 		'created_on' => date('Y-m-d H:i:s')
				// 	);
				// 	$this->db->insert('pmm_schedule_material',$data_m);
				// }

			}
				

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

	
	public function product_detail_form()
	{
		$output['output'] = false;

		$schedule_product_id = $this->input->post('schedule_product_id');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr = $this->db->select('id')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$schedule_product_id))->result_array();
		foreach ($arr as $key => $val) {
			# code...
			$data_p = array(
				'koef' => $this->input->post('date_'.$val['id']),
				'updated_by' => $this->session->userdata('admin_id')
			);
			$this->db->update('pmm_schedule_product_date',$data_p,array('id'=>$val['id']));
			$this->db->update('pmm_schedule_product_date_fixed', array('koef' => $this->input->post('date_'.$val['id'])),array('id'=>$val['id']));
		}
		

		

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


	
	function schedule_process()
	{
		// if($_POST){
			$id = $this->uri->segment(4);
			$type = $this->uri->segment(5);
			$arr = array();
			if($type == 1){
				$get_schedule = $this->db->get_where('pmm_schedule_product',array('schedule_id'=>$id))->result_array();
				foreach ($get_schedule as $key => $row) {
					$this->db->update('pmm_schedule_product_date',array('status'=>'PUBLISH'),array('schedule_product_id'=>$row['id'],'week'=>1));
				}
				$arr = array('status'=>'APPROVED','approved_by'=>$this->session->userdata('admin_id'),'approved_on'=>date('Y-m-d H:i:s'));
				
			}else if($type == 2){
				$arr = array('status'=>'REJECTED');
			}else {

				$get_schedule = $this->db->get_where('pmm_schedule_product',array('schedule_id'=>$id))->result_array();
				foreach ($get_schedule as $key => $row) {
					$this->db->update('pmm_schedule_product_date',array('status'=>'WAITING'),array('schedule_product_id'=>$row['id'],'week'=>1));
				}
				$arr = array('status'=>'WAITING');
			}

			if($this->db->update('pmm_schedule',$arr,array('id'=>$id))){
				redirect('admin/rencana_produksi');
			}
		// }
	}

	public function delete_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('pmm_schedule_material',array('schedule_product_id'=>$id));
			$this->db->delete('pmm_schedule_product_date',array('schedule_product_id'=>$id));
			if($this->db->delete('pmm_schedule_product',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	public function get_by_week()
	{
		$schedule_id = $this->input->post('id');
		$week = $this->input->post('week');

		$output['output'] = false;
		if(!empty($schedule_id)){
			$data = $this->pmm_model->GetSPOByWeek($schedule_id,$week);
			if($data){
				$output['output'] = true;
				$output['data'] = $data;
				$output['status'] = $this->pmm_model->GetStatusPP($schedule_id,$week);
			}
		}
		echo json_encode($output);
	}


	
	public function edit_week_process()
	{
		$schedule_id = $this->input->post('schedule_id');
		$week = $this->input->post('week');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$this->db->select('pspd.id,pspd.date,pspd.schedule_product_id');
		$this->db->join('pmm_schedule_product psp','pspd.schedule_product_id = psp.id','left');
		$this->db->where(array('psp.schedule_id'=>$schedule_id,'pspd.week'=>$week));
		$get_schedule = $this->db->get('pmm_schedule_product_date pspd')->result_array();
		foreach ($get_schedule as $key => $row) {
			$data = array('status'=>'WAITING','koef'=>$this->input->post($row['schedule_product_id'].'_'.str_replace('-', '_', $row['date'])));
			$this->db->update('pmm_schedule_product_date',$data,array('id'=>$row['id']));
		}

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

	public function approve_week()
	{
		$schedule_id = $this->input->post('schedule_id');
		$week = $this->input->post('week');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$this->db->select('pspd.id,pspd.date,pspd.schedule_product_id');
		$this->db->join('pmm_schedule_product psp','pspd.schedule_product_id = psp.id','left');
		$this->db->where(array('psp.schedule_id'=>$schedule_id,'pspd.week'=>$week));
		$get_schedule = $this->db->get('pmm_schedule_product_date pspd')->result_array();
		foreach ($get_schedule as $key => $row) {
			$data = array('status'=>'PUBLISH',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s'));

			$this->db->update('pmm_schedule_product_date',$data,array('id'=>$row['id']));
		}

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


	public function table_dashboard()
	{
		$data = array();
		$arr_date = explode(' - ', $this->input->post('date'));
		$product = $this->input->post('product');

		$this->db->select('pspd.product_id,pc.product,pspd.id,SUM(koef) as volume');
		$this->db->join('pmm_product pc','pspd.product_id = pc.id','left');
		if(!empty($arr_date)){
			$this->db->where('pspd.date >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('pspd.date <=',date('Y-m-d',strtotime($arr_date[1])));
		}
		if(!empty($product)){
			$this->db->where('pspd.product_id',$product);
		}
		$this->db->where('pspd.status','PUBLISH');
		$this->db->group_by('pspd.product_id');
		$query = $this->db->get('pmm_schedule_product_date_fixed pspd');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['volume_real'] = $this->pmm_model->GetProByProductDate($row['product_id'],$arr_date[0],$arr_date[1]);
				$data[] = $row;
			}
		}

		echo json_encode(array('data'=>$data,'a'=>$arr_date));
	}
	
















}