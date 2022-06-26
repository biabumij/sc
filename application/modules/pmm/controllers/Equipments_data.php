<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equipments_data extends CI_Controller {

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

	public function manage()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['suppliers'] = $this->db->order_by('name','asc')->get_where('pmm_supplier',array('status'=>'PUBLISH'))->result_array();
			$data['equipments'] = $this->db->order_by('tool','asc')->get_where('pmm_tools',array('status'=>'PUBLISH'))->result_array();
			$data['measures'] = $this->db->order_by('measure_name','asc')->get_where('pmm_measures',array('status'=>'PUBLISH'))->result_array();
			$data['asd'] = false;
			$this->load->view('pmm/equipments_data_add',$data);
			
		}else {
			redirect('admin');
		}
	}


	public function process()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$date = date('Y-m-d',strtotime($this->input->post('date')));
		$supplier_id = $this->input->post('supplier_id');
		$tool_id = $this->input->post('tool_id');
		$measure_id = $this->input->post('measure_id');
		$insentive_driver = str_replace('.', '', $this->input->post('insentive_driver'));
		$insentive_driver = str_replace(',', '.', $insentive_driver);
		$rental_cost = str_replace('.', '', $this->input->post('rental_cost'));
		$rental_cost = str_replace(',', '.', $rental_cost);
		$maintenance_cost = str_replace('.', '', $this->input->post('maintenance_cost'));
		$maintenance_cost = str_replace(',', '.', $maintenance_cost);
		$total = $insentive_driver + $rental_cost + $maintenance_cost;
		$volume = str_replace('.', '', $this->input->post('volume'));
		$volume = str_replace(',', '.', $volume);

		$data = array(
			'date' => $date,
			'supplier_id' => $supplier_id,
			'tool_id' => $tool_id,
			'measure_id' => $measure_id,
			'insentive_driver' => $insentive_driver,
			'rental_cost' => $rental_cost,
			'maintenance_cost' => $maintenance_cost,
			'total' => $total,
			'volume' => $volume
		);


		if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_equipments_data',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_equipments_data',$data)){
				$output['output'] = true;
			}	
				
		}

		echo json_encode($output);
	}

	public function table()
	{
		$data = array();
		$w_date = $this->input->post('filter_date');
		$supplier_id = $this->input->post('supplier_id');
		$tool_id = $this->input->post('tool_id');

		$this->db->select('*');
		if(!empty($supplier_id)){
			$this->db->where('supplier_id',$supplier_id);
		}
		if(!empty($tool_id) || $tool_id != 0){
			$this->db->where('tool_id',$tool_id);
		}
		
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('date  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('date <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->order_by('date','desc');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_equipments_data');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['date'] = date('d F Y',strtotime($row['date']));
				$row['supplier'] = $this->crud_global->GetField('pmm_supplier',array('id'=>$row['supplier_id']),'name');
				$row['tool'] = $this->crud_global->GetField('pmm_tools',array('id'=>$row['tool_id']),'tool');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_id']),'measure_name');
				$row['volume'] = number_format($row['volume'],2,',','.');
				$row['insentive_driver_val'] = $row['insentive_driver'];
				$row['insentive_driver'] = number_format($row['insentive_driver'],2,',','.');
				$row['rental_cost_val'] = $row['rental_cost'];
				$row['rental_cost'] = number_format($row['rental_cost'],2,',','.');
				$row['maintenance_cost_val'] = $row['maintenance_cost'];
				$row['maintenance_cost'] = number_format($row['maintenance_cost'],2,',','.');
				$row['total_val'] = $row['total'];
				$row['total'] = number_format($row['total'],2,',','.');
				$edit = false;
				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4){
					$edit = '<a href="javascript:void(0);" onclick="EditData('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';			
				}

				$row['actions'] = $edit.' <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	public function delete()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			
			if($this->db->delete('pmm_equipments_data',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	public function edit_data()
	{
		$id = $this->input->post('id');

		$data = $this->db->get_where('pmm_equipments_data',array('id'=>$id))->row_array();
		$data['date'] = date('d-m-Y',strtotime($data['date']));
		echo json_encode(array('data'=>$data));		
	}

	public function print_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->SetTopMargin(0);
        $pdf->SetFont('helvetica','',5); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$w_date = $this->input->get('filter_date');
		if(!empty($w_date)){
			$supplier_id = $this->input->get('supplier_id');
			$tool_id = $this->input->get('tool_id');
			$this->db->select('*');
			if(!empty($supplier_id)){
				$this->db->where('supplier_id',$supplier_id);
			}
			if(!empty($tool_id) || $tool_id != 0){
				$this->db->where('tool_id',$tool_id);
			}
			if(!empty($w_date)){
				$arr_date = explode(' - ', $w_date);
				$start_date = $arr_date[0];
				$end_date = $arr_date[1];
				$this->db->where('date  >=',date('Y-m-d',strtotime($start_date)));	
				$this->db->where('date <=',date('Y-m-d',strtotime($end_date)));	
			}
			$this->db->order_by('date','desc');
			$this->db->order_by('created_on','desc');
			$query = $this->db->get('pmm_equipments_data');

			$data['data'] = $query->result_array();
	        $html = $this->load->view('pmm/equipments_data_print',$data,TRUE);

	        
	        $pdf->SetTitle('Equipments Data');
	        $pdf->nsi_html($html);
	        $pdf->Output('Equipments-Data.pdf', 'I');
		}else {
			echo 'Please Select Filter Date First';
		}
		
	
	}


	function table_date()
	{
		$this->load->model('pmm_reports');
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$tool_id = $this->input->post('tool_id');
		$start_date = false;
		$end_date = false;
		$date = $this->input->post('filter_date');
		$arr_date = array();
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$arr_date = array($start_date,$end_date);
			$solar = $this->pmm_reports->EquipmentUsageReal($arr_date);
		}

		$data = $this->pmm_reports->EquipmentsData($arr_date,$supplier_id,$tool_id);


		echo json_encode(array(
			'data'=>$data,
			'solar' => $solar
		));	
	}





}
?>