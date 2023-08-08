<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model','admin/Templates'));
        $this->load->model('pmm_reports');
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		$this->m_admin->check_login();
	}

	public function revenues()
	{

		
		$arr_date = $this->input->post('filter_date');
		if(empty($filter_date)){
			$filter_date = '-';
		}else {
			$filter_date = $arr_date;
		}
		$alphas = range('A', 'Z');
		$data['alphas'] = $alphas;
		$data['clients'] = $this->db->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
		$data['arr_date'] = $arr_date;
		$this->load->view('pmm/ajax/reports/revenues',$data);
	}

	public function receipt_materials()
	{
		
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($arr_date);
		$this->load->view('pmm/ajax/reports/receipt_materials',$data);

	}

	public function receipt_material_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		$type = $this->input->post('type');
		$data['type'] = $type;
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($id,$arr_date);
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/receipt_materials_detail',$data);
	}


	public function material_usage()
	{

		$product_id = $this->input->post('product_id');
		$arr_date = $this->input->post('filter_date');

		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';

    		$arr_date_2 = $start_date.' - '.$end_date;

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date_2);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date_2,true);
    	}else {



    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date,true);
    	} 

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		if(!empty($product_id)){
			$data['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'product');
			$data['total_production'] = $this->pmm_reports->TotalProductions($product_id,$arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompoProduct($arr_date,$product_id);
			$this->load->view('pmm/ajax/reports/material_usage_product',$data);
		}else {

			$data['arr'] =  $this->pmm_reports->MaterialUsageReal($arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompo($arr_date);
			$data['total_revenue_now'] = $total_revenue_now;
			$data['total_revenue_before'] =  $total_revenue_before;
			$this->load->view('pmm/ajax/reports/material_usage',$data);	
		}
		
	}

	public function material_usage_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

		$type = $this->input->post('type');
		$product_id = $this->input->post('product_id');
		$data['type'] = $type;
		if($type == 'compo' || $type == 'compo_cost' || $type == 'compo_now'){
			$data['arr'] =  $this->pmm_reports->MaterialUsageCompoDetails($id,$arr_date,$product_id);
		}else {
			$data['arr'] =  $this->pmm_reports->MaterialUsageDetails($id,$arr_date);	
		}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['product_id'] = $product_id;
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/material_usage_detail',$data);
	}

	public function material_remaining()
	{
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

    	$date = array($start_date,$end_date);
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		$data['arr'] =  $this->pmm_reports->MaterialRemainingReal($date);
		$data['arr_compo'] = $this->pmm_reports->MaterialRemainingCompo($date);
		$this->load->view('pmm/ajax/reports/material_remaining',$data);	
		

	}

	public function material_remaining_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 
    	$date = array($start_date,$end_date);
		$type = $this->input->post('type');
		$data['type'] = $type;
		if($type == 'compo'){
			$data['arr'] =  $this->pmm_reports->MaterialRemainingCompoDetails($id,$date);
		}else {
			$data['arr'] =  $this->pmm_reports->MaterialRemainingDetails($id,$arr_date);	
		}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['name'] = $this->input->post('name'); 
		$this->load->view('pmm/ajax/reports/material_remaining_detail',$data);
	}

	public function equipments()
	{
		$arr_date = $this->input->post('filter_date');
		$supplier_id = $this->input->post('supplier_id');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$date = array($start_date,$end_date);
    	$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->EquipmentProd($date);
		$data['equipments'] =  $this->pmm_reports->EquipmentReports($date,$supplier_id);
		$data['solar'] =  $this->pmm_reports->EquipmentUsageReal($date,true);
		$this->load->view('pmm/ajax/reports/equipments',$data);

	}

	public function equipments_detail()
	{
		$id = $this->input->post('id');	
		$arr_date = $this->input->post('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}
    	$date = array($start_date,$end_date);
		$supplier_id = $this->input->post('supplier_id');
		$data['equipments'] = $this->pmm_reports->EquipmentReportsDetails($id,$date,$supplier_id);
		$data['name'] = $this->input->post('name');
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$this->load->view('pmm/ajax/reports/equipments_detail',$data);
	}


	public function equipments_data_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$date = $this->input->get('filter_date');
		$supplier_id = $this->input->get('supplier_id');
		$tool_id = $this->input->get('tool_id');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));

			$filter_date = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
			$data['filter_date'] = $filter_date;
			$date = explode(' - ',$start_date.' - '.$end_date);
			$arr_data = $this->pmm_reports->EquipmentsData($date,$supplier_id,$tool_id);

			$data['data'] = $arr_data;
			$data['solar'] =  $this->pmm_reports->EquipmentUsageReal($date);
	        $html = $this->load->view('pmm/equipments_data_print',$data,TRUE);

	        
	        $pdf->SetTitle('Data Alat');
	        $pdf->nsi_html($html);
	        $pdf->Output('Data-Alat.pdf', 'I');

		}else {
			echo 'Please Filter Date First';
		}
	}

	public function revenues_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$alphas = range('A', 'Z');
		$data['alphas'] = $alphas;
		$data['arr_date'] = $arr_date;
		$data['clients'] = $this->db->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
        $html = $this->load->view('pmm/revenues_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PENDAPATAN USAHA');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PENDAPATAN-USAHA.pdf', 'I');
	
	}
	
	public function monitoring_receipt_materials_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->ReceiptMaterialTagDetails($arr_date);
        $html = $this->load->view('pmm/monitoring_receipt_materials_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PENERIMAAN BAHAN');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PENERIMAAN-BAHAN.pdf', 'I');
	
	}

	public function material_usage_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$product_id = $this->input->get('product_id');
		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';

    		$arr_date_2 = $start_date.' - '.$end_date;

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date_2);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date_2,true);

    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));

    		$total_revenue_now = $this->pmm_model->getRevenueAll($arr_date);
    		$total_revenue_before = $this->pmm_model->getRevenueAll($arr_date,true);
    	}
    	
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		if(empty($product_id)){
			$data['arr'] =  $this->pmm_reports->MaterialUsageReal($arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompo($arr_date);
			$data['total_revenue_now'] = $total_revenue_now;
			$data['total_revenue_before'] =  $total_revenue_before;
	        $html = $this->load->view('pmm/material_usage_print',$data,TRUE);
		}else {
			$data['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'product');
			$data['total_production'] = $this->pmm_reports->TotalProductions($product_id,$arr_date);
			$data['arr_compo'] = $this->pmm_reports->MaterialUsageCompoProduct($arr_date,$product_id);

			

	        $html = $this->load->view('pmm/material_usage_product_print',$data,TRUE);
		}
		 
        $pdf->SetTitle('LAPORAN PEMAKAIAN MATERIAL');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PEMAKAIAN-MATERIAL.pdf', 'I');
	
	}

	public function material_remaining_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

    	$date = array($start_date,$end_date);
		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));

		$data['arr'] =  $this->pmm_reports->MaterialRemainingReal($date);
		$data['arr_compo'] = $this->pmm_reports->MaterialRemainingCompo($date);

        $html = $this->load->view('pmm/material_remaining_print',$data,TRUE);

        
        $pdf->SetTitle('Materials Remaining');
        $pdf->nsi_html($html);
        $pdf->Output('Materials-Remaining.pdf', 'I');
	
	}

	public function monitoring_equipments_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		$supplier_id = $this->input->get('supplier_id');
		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$date = array($start_date,$end_date);
    	$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		$data['arr'] =  $this->pmm_reports->EquipmentProd($date);
		$data['equipments'] =  $this->pmm_reports->EquipmentReports($date,$supplier_id);
		$data['supplier'] = $this->crud_global->GetField('pmm_supplier',array('id'=>$supplier_id),'name');

        $html = $this->load->view('pmm/monitoring_equipments_print',$data,TRUE);

        
        $pdf->SetTitle('LAPORAN PEMAKAIAN ALAT');
        $pdf->nsi_html($html);
        $pdf->Output('LAPORAN-PEMAKAIAN-ALAT.pdf', 'I');
	
	}

	public function general_cost_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->SetTopMargin(0);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		$filter_type = $this->input->get('filter_type');
		if(empty($arr_date)){
    		$data['filter_date'] = '-';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    		$data['filter_date'] = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
    	} 

		


		if(!empty($arr_date)){
			$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    		$this->db->where('date >=',$start_date);
    		$this->db->where('date <=',$end_date);	
		}
		if(!empty($filter_type)){
			$this->db->where('type',$filter_type);
		}
		$this->db->order_by('date','desc');
		$this->db->where('status !=','DELETED');
		$arr = $this->db->get_where('pmm_general_cost');
		$data['arr'] =  $arr->result_array();

        $html = $this->load->view('pmm/general_cost_print',$data,TRUE);

        
        $pdf->SetTitle('General Cost');
        $pdf->nsi_html($html);
        $pdf->Output('General-Cost.pdf', 'I');
	
	}

	public function purchase_order_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_date = $this->input->get('filter_date');
		if(empty($arr_date)){
			$filter_date = '-';
		}else {
			$arr_filter_date = explode(' - ', $arr_date);
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		$data['filter_date'] = $filter_date;
		$data['w_date'] = $arr_date;
		$data['status'] = $this->input->post('status');
		$data['supplier_id'] = $this->input->post('supplier_id');
		$this->db->select('supplier_id');
		$this->db->where('status !=','DELETED');
		if(!empty($data['status'])){
			$this->db->where('supplier_id',$data['status']);
		}
		$this->db->group_by('supplier_id');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_purchase_order');

		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/purchase_order_print',$data,TRUE);

        
        $pdf->SetTitle('Purchase Order');
        $pdf->nsi_html($html);
        $pdf->Output('Purchase-Order.pdf', 'I');
	
	}

	public function product_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$tag_id = $this->input->get('product_id');

		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);	
		}
		$this->db->where('status !=','DELETED');
		$this->db->order_by('product','asc');
		$query = $this->db->get('pmm_product');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$name = "'".$row['product']."'";
				$row['no'] = $key+1;
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$contract_price = $this->pmm_model->GetContractPrice($row['contract_price']);
				$row['contract_price'] = number_format($contract_price,2,',','.');
				$row['riel_price'] = number_format($this->pmm_model->GetRielPrice($row['id']),2,',','.');
				$row['composition'] = $this->crud_global->GetField('pmm_composition',array('id'=>$row['composition_id']),'composition_name');
				$row['tag_name'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
				$arr_data[] = $row;
			}

		}

		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/product_print',$data,TRUE);

        
        $pdf->SetTitle('Product');
        $pdf->nsi_html($html);
        $pdf->Output('Product.pdf', 'I');
	
	}

	public function product_hpp_print()
	{
		$id = $this->input->get('id');
		$name = $this->input->get('name');
		if(!empty($id)){
			$this->load->library('pdf');
		

			$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
	        $pdf->setPrintHeader(true);
	        $pdf->SetFont('helvetica','',7); 
	        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
			$pdf->setHtmlVSpace($tagvs);
			        $pdf->AddPage('P');

			$arr_data = array();

			$output = $this->pmm_model->GetRielPriceDetail($id);

			$data['data'] = $output;
			$data['name'] = $name;
	        $html = $this->load->view('pmm/product_hpp_print',$data,TRUE);

	        
	        $pdf->SetTitle('Product-HPP');
	        $pdf->nsi_html($html);
	        $pdf->Output('Product-HPP-'.$name.'.pdf', 'I');
		}else {
			echo "Product Not Found";
		}
		
	
	}

	public function materials_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		
		$pdf->AddPage('P');

		$arr_data = array();
		$tag_id = $this->input->get('tag_id');

		$this->db->where('status !=','DELETED');
		if(!empty($tag_id)){
			$this->db->where('tag_id',$tag_id);
		}
		$this->db->order_by('material_name','asc');
		$query = $this->db->get('pmm_materials');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['price'] = number_format($row['price'],2,',','.');
				$row['cost'] = number_format($row['cost'],2,',','.');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
 				$row['tag_name'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
				$arr_data[] = $row;
			}

		}

		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/materials_print',$data,TRUE);

        
        $pdf->SetTitle('Materials');
        $pdf->nsi_html($html);
        $pdf->Output('Materials.pdf', 'I');
	
	}

	public function tools_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('tool','asc');
		$query = $this->db->get('pmm_tools');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$name = "'".$row['tool']."'";
				$total_cost = $this->db->select('SUM(cost) as total')->get_where('pmm_tool_detail',array('status'=>'PUBLISH','tool_id'=>$row['id']))->row_array();
				$row['total_cost'] = number_format($total_cost['total'],2,',','.');
				$row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_id']),'measure_name');
				$row['tag'] = $this->crud_global->GetField('pmm_tags',array('id'=>$row['tag_id']),'tag_name');
 				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['actions'] = '<a href="javascript:void(0);" onclick="FormDetail('.$row['id'].','.$name.')" class="btn btn-info"><i class="fa fa-search"></i> Detail</a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/tools_print',$data,TRUE);

        
        $pdf->SetTitle('Tools');
        $pdf->nsi_html($html);
        $pdf->Output('Tools.pdf', 'I');
	
	}

	public function measures_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_measures');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/measures_print',$data,TRUE);

        
        $pdf->SetTitle('Satuan');
        $pdf->nsi_html($html);
        $pdf->Output('satuan.pdf', 'I');
	
	}

	public function composition_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$tag_id = $this->input->get('filter_product');
		$arr_tag = array();
		if(!empty($tag_id)){
			$query_tag = $this->db->select('id')->get_where('pmm_product',array('status'=>'PUBLISH','tag_id'=>$tag_id))->result_array();
			foreach ($query_tag as $pid) {
				$arr_tag[] = $pid['id'];
			}
		}
		$this->db->select('pc.*, pp.product');
		$this->db->where('pc.status !=','DELETED');
		if(!empty($tag_id)){
			$this->db->where_in('product_id',$arr_tag);
		}
		$this->db->join('pmm_product pp','pc.product_id = pp.id','left');
		$this->db->order_by('pc.created_on','desc');
		$query = $this->db->get('pmm_composition pc');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/composition_print',$data,TRUE);

        
        $pdf->SetTitle('Composition');
        $pdf->nsi_html($html);
        $pdf->Output('Composition.pdf', 'I');
	
	}

	public function supplier_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('name','asc');
		$query = $this->db->get('pmm_supplier');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/supplier_print',$data,TRUE);

        
        $pdf->SetTitle('Supplier');
        $pdf->nsi_html($html);
        $pdf->Output('Supplier.pdf', 'I');
	
	}

	public function client_print()
	{
		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('client_name','asc');
		$query = $this->db->get('pmm_client');
		$data['data'] = $query->result_array();	
	
		$this->load->library('pdf');
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-client.pdf";
		$this->pdf->load_view('pmm/client_print', $data);
	
	}
	
	public function slump_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$query = $this->db->get('pmm_slump');
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/slump_print',$data,TRUE);

        
        $pdf->SetTitle('Slump');
        $pdf->nsi_html($html);
        $pdf->Output('Slump.pdf', 'I');
	
	}

	public function tags_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('P');

		$arr_data = array();
		$type = $this->input->get('type');
		$this->db->where('status !=','DELETED');
		if(!empty($type)){
			$this->db->where('tag_type',$type);
		}
		$this->db->order_by('tag_name','asc');
		$query = $this->db->get('pmm_tags');

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$price = 0;
				if($row['tag_type'] == 'MATERIAL'){
					$get_price = $this->db->select('AVG(cost) as cost')->get_where('pmm_materials',array('status'=>'PUBLISH','tag_id'=>$row['id']))->row_array();
					if(!empty($get_price)){
						$price = $get_price['cost'];
					}
				}
				$row['price'] = number_format($price,2,',','.');
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/tags_print',$data,TRUE);

        
        $pdf->SetTitle('Tags');
        $pdf->nsi_html($html);
        $pdf->Output('Tags.pdf', 'I');
	
	}

	public function production_planning_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$this->db->where('status !=','DELETED');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_schedule');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$arr_date = explode(' - ', $row['schedule_date']);
				$row['schedule_name'] = $row['schedule_name'];
				$row['client_name'] = $this->crud_global->GetField('pmm_client',array('id'=>$row['client_id']),'client_name');
				$row['schedule_date'] = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));
				$row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['week_1'] = $this->pmm_model->TotalSPOWeek($row['id'],1);
				$row['week_2'] = $this->pmm_model->TotalSPOWeek($row['id'],2);
				$row['week_3'] = $this->pmm_model->TotalSPOWeek($row['id'],3);
				$row['week_4'] = $this->pmm_model->TotalSPOWeek($row['id'],4);
				$row['status'] = $this->pmm_model->GetStatus($row['status']);
				
				$arr_data[] = $row;
			}

		}
		$data['data'] = $arr_data;
        $html = $this->load->view('pmm/production_planning_print',$data,TRUE);

        
        $pdf->SetTitle('cetak_poduction_planning');
        $pdf->nsi_html($html);
        $pdf->Output('production_planning.pdf', 'I');
	
	}
	
	public function receipt_matuse_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

			$arr_filter_mats = array();

			$no = 1;
			$this->db->select('ppo.supplier_id,prm.measure,ps.name,SUM(prm.volume) as total, SUM((prm.cost / prm.convert_value) * prm.display_volume) as total_price, prm.convert_value, SUM(prm.volume * prm.convert_value) as total_convert');
			if(!empty($start_date) && !empty($end_date)){
	            $this->db->where('prm.date_receipt >=',$start_date);
	            $this->db->where('prm.date_receipt <=',$end_date);
	        }
	        if(!empty($supplier_id)){
	            $this->db->where('ppo.supplier_id',$supplier_id);
	        }
	        if(!empty($filter_material)){
	            $this->db->where_in('prm.material_id',$filter_material);
	        }
	        if(!empty($purchase_order_no)){
	            $this->db->where('prm.purchase_order_id',$purchase_order_no);
	        }
			$this->db->where('ps.status','PUBLISH');
			$this->db->join('pmm_supplier ps','ppo.supplier_id = ps.id','left');
			$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
			$this->db->group_by('ppo.supplier_id');
			$this->db->order_by('ps.name','asc');
			$query = $this->db->get('pmm_purchase_order ppo');
			
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $sups) {

					$mats = array();
					$materials = $this->pmm_model->GetReceiptMatUse($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$arr_filter_mats);
					if(!empty($materials)){
						foreach ($materials as $key => $row) {
							$arr['no'] = $key + 1;
							$arr['measure'] = $row['measure'];
							$arr['material_name'] = $row['material_name'];
							
							$arr['real'] = number_format($row['total'],2,',','.');
							$arr['convert_value'] = number_format($row['convert_value'],2,',','.');
							$arr['total_convert'] = number_format($row['total_convert'],2,',','.');
							$arr['total_price'] = number_format($row['total_price'],2,',','.');
							$mats[] = $arr;
						}
						$sups['mats'] = $mats;
						$total += $sups['total_price'];
						$total_convert += $sups['total_convert'];
						$sups['no'] =$no;
						$sups['real'] = number_format($sups['total'],2,',','.');
						$sups['convert_value'] = number_format($sups['convert_value'],2,',','.');
						$sups['total_convert'] = number_format($sups['total_convert'],2,',','.');
						$sups['total_price'] = number_format($sups['total_price'],2,',','.');
						$sups['measure'] = '';
						$arr_data[] = $sups;
						$no++;
					}
					
					
				}
			}
			if(!empty($filter_material)){
				$total_convert = number_format($total_convert,0,',','.');
			}else {
				$total_convert = '';
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['total_convert'] = $total_convert;
	        $html = $this->load->view('pmm/receipt_matuse_report_print',$data,TRUE);

	        
	        $pdf->SetTitle('Penerimaan Bahan');
	        $pdf->nsi_html($html);
	        $pdf->Output('Penerimaan-Bahan.pdf', 'I');
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function data_material_usage()
	{
		$supplier_id = $this->input->post('supplier_id');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$query = array();
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

    	$this->db->where(array(
    		'status'=>'PUBLISH',
    	));
    	if(!empty($filter_material)){
    		$this->db->where('id',$filter_material);
    	}
    	$this->db->order_by('nama_produk','asc');
    	$tags = $this->db->get_where('produk',array('status'=>'PUBLISH','bahanbaku'=>1))->result_array();

    	if(!empty($tags)){
    		?>
	        <table class="table table-center table-bordered table-condensed">
	        	<thead>
	        		<tr >
		        		<th class="text-center">No</th>
		        		<th class="text-center">Bahan</th>
		        		<th class="text-center">Rekanan</th>
		        		<th class="text-center">Satuan</th>
		        		<th class="text-center">Volume</th>
		        		<th class="text-center">Total</th>
		        	</tr>	
	        	</thead>
	        	<tbody>
	        		<?php
	        		$no=1;
	        		$total_total = 0;
	        		foreach ($tags as $tag) {
		    			$now = $this->pmm_reports->SumMaterialUsage($tag['id'],array($start_date,$end_date));

		    			
		    			$measure_name = $this->crud_global->GetField('pmm_measures',array('id'=>$tag['satuan']),'measure_name');
		    			if($now['volume'] > 0){
				        	
				        	?>
				        	<tr class="active" style="font-weight:bold;">
				        		<td class="text-center"><?php echo $no;?></td>
				        		<td colspan="2"><?php echo $tag['nama_produk'];?></td>
				        		<td class="text-center"><?php echo $measure_name;?></td>
				        		<td class="text-right"><?php echo number_format($now['volume'],2,',','.');?></td>
				        		<td class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($now['total'],0,',','.');?></td>
				        	</tr>
				        	<?php
				        	$now_new = $this->pmm_reports->MatUseBySupp($tag['id'],array($start_date,$end_date),$now['volume'],$now['total']);
				        	if(!empty($now_new)){
				        		$no_2 = 1;
				        		foreach ($now_new as $new) {
					        		
					        		?>
					        		<!--<tr>
					        			<td class="text-center"><?= $no.'.'.$no_2;?></td>
					        			<td></td>
					        			<td><?php echo $new['supplier'];?></td>
					        			<td class="text-center"><?php echo $measure_name;?></td>
						        		<td class="text-right"><?php echo number_format($new['volume'],2,',','.');?></td>
						        		<td class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($new['total'],0,',','.');?></td>
					        		</tr>-->
					        		<?php
					        		$no_2 ++;
					        	}
				        	}
				        	
				        	?>
				        	<tr style="height: 20px">
				        		<td colspan="6"></td>
				        	</tr>
				        	<?php

				        	$no++;
				        	$total_total += $now['total'];
					        
		    			}
		    		}
	        		?>
	        		<tr>
	        			<th colspan="5" class="text-right">TOTAL</th>
	        			<th class="text-right"><span class="pull-left">Rp. </span><?php echo number_format($total_total,0,',','.');?></th>
	        		</tr>
	        	</tbody>
	        </table>
	        <?php	
    	}


	}


	public function material_usage_prod_print()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$arr_data = array();
		$supplier_id = $this->input->get('supplier_id');
		$purchase_order_no = $this->input->get('purchase_order_no');
		$filter_material = $this->input->get('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$date = $this->input->get('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$filter_date = date('d F Y',strtotime($arr_date[0])).' - '.date('d F Y',strtotime($arr_date[1]));

			
			$data['filter_date'] = $filter_date;

			$no = 1;
	    	$this->db->where(array(
	    		'status'=>'PUBLISH',
				'bahanbaku'=>1,
	    	));
	    	if(!empty($filter_material)){
	    		$this->db->where('id',$filter_material);
	    	}
	    	$this->db->order_by('nama_produk','asc');
	    	$query = $this->db->get('produk');
			
			if($query->num_rows() > 0){

				foreach ($query->result_array() as $key => $tag) {

					$now = $this->pmm_reports->SumMaterialUsage($tag['id'],array($start_date,$end_date));
	    			$measure_name = $this->crud_global->GetField('pmm_measures',array('id'=>$tag['satuan']),'measure_name');
	    			if($now['volume'] > 0){
	    				$tags['tag_name'] = $tag['nama_produk'];
	    				$tags['no'] = $no;
	    				$tags['volume'] = number_format($now['volume'],2,',','.');
	    				$tags['total'] = number_format($now['total'],2,',','.');
	    				$tags['measure'] = $measure_name;

	    				$now_new = $this->pmm_reports->MatUseBySupp($tag['id'],array($start_date,$end_date),$now['volume'],$now['total']);
			        	if(!empty($now_new)){
			        		$no_2 = 1;
			        		$supps = array();
			        		foreach ($now_new as $new) {

			        			$arr_supps['no'] = $no_2;
			        			$arr_supps['supplier'] = $new['supplier'];
			        			$arr_supps['volume'] = number_format($new['volume'],2,',','.');
			        			$arr_supps['total'] = number_format($new['total'],2,',','.');
			        			$supps[] = $arr_supps;
			        			$no_2 ++;
			        		}

			        		$tags['supps'] = $supps;
			        	}

						$arr_data[] = $tags;	
						$total += $now['total'];
	    			}
					$no++;
					
				}
			}

			
			$data['data'] = $arr_data;
			$data['total'] = $total;
			$data['custom_date'] = $this->input->get('custom_date');
	        $html = $this->load->view('produksi/material_usage_prod_print',$data,TRUE);

	        
	        $pdf->SetTitle('pemakaian-material');
	        $pdf->nsi_html($html);
	        $pdf->Output('pemakaian-material', 'I');
		}else {
			echo 'Please Filter Date First';
		}
	
	}

	public function exec()
	{
		
	}


	//BATAS RUMUS LAMA

	public function pergerakan_bahan_baku($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #cccccc;
				color: black;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			</style>
	        <tr class="table-active2">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="9"><?php echo $filter_date;?></th>
	        </tr>
			
			<?php
			//Opening Balance
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			
			$pergerakan_bahan_baku_ago = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
			->where("prm.material_id = 15")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian_ago = $pergerakan_bahan_baku_ago['volume'];
			$total_volume_pembelian_akhir_ago  = $total_volume_pembelian_ago;
			
			$produksi_harian_ago = $this->db->select('sum(pphd.use) as used')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->where("(pph.date_prod between '$date1_ago' and '$date2_ago')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();
			
			$total_volume_produksi_ago = $produksi_harian_ago['used'];
			$total_volume_produksi_akhir_ago = $total_volume_pembelian_akhir_ago - $total_volume_produksi_ago;

			$harga_satuan_ago = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date3_ago' and '$date2_ago'")
			->where("prm.material_id = 15")
			->group_by('prm.material_id')
			->get()->row_array();
		
			$nilai_harga_satuan_ago = ($harga_satuan_ago['volume']!=0)?($harga_satuan_ago['nilai'] / $harga_satuan_ago['volume'])  * 1:0;

			$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
			->from('hpp_bahan_baku pp')
			->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
			->get()->row_array();

			$total_volume_produksi_akhir_ago_fix = round($total_volume_produksi_akhir_ago,2);

			$volume_opening_balance = $total_volume_produksi_akhir_ago_fix;
			$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
			$nilai_opening_balance = $total_volume_produksi_akhir_ago_fix * $harga_opening_balance;

			$pergerakan_bahan_baku_ago_solar = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
			->where("prm.material_id = 13")
			->group_by('prm.material_id')
			->get()->row_array();

			$volume_pergerakan_bahan_baku_ago_solar = $pergerakan_bahan_baku_ago_solar['volume'];
			
			$stock_opname_solar_ago = $this->db->select('`prm`.`volume` as volume, `prm`.`total` as total')
			->from('pmm_remaining_materials_cat prm ')
			->where("prm.material_id = 13")
			->where("(prm.date < '$date1')")
			->where("status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_solar_ago = $stock_opname_solar_ago['volume'];

			$volume_opening_balance_solar = $volume_stock_opname_solar_ago;
			$volume_opening_balance_solar_fix = round($volume_opening_balance_solar,2);

			$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
			$nilai_opening_balance_solar = $volume_opening_balance_solar_fix * $harga_opening_balance_solar;

			//Now
			//Bahan Baku			
			$pergerakan_bahan_baku = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			(prm.display_price / prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 15")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
			$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
			$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

			$total_volume_pembelian_akhir  = $total_volume_produksi_akhir_ago + $total_volume_pembelian;
			$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
			$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			
			
			$produksi_harian = $this->db->select('sum(pphd.use) as used')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->where("(pph.date_prod between '$date1' and '$date2')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();
			
			$total_volume_produksi = $produksi_harian['used'];
			$total_harga_produksi = round($total_harga_pembelian_akhir,0);
			$total_nilai_produksi = $total_volume_produksi * $total_harga_produksi;
			
			$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
			$total_harga_produksi_akhir = $total_harga_produksi;
			$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

			//BBM Solar
			$pergerakan_bahan_baku_solar = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			(prm.display_price / prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 13")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
			$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
			$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

			$total_volume_pembelian_akhir_solar  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
			$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
			$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

			$stock_opname_solar = $this->db->select('(prm.volume) as volume, (prm.total) as total')
			->from('pmm_remaining_materials_cat prm ')
			->where("prm.material_id = 13")
			->where("prm.date between '$date1' and '$date2'")
			->where("status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_stock_opname_solar = $stock_opname_solar['volume'];
			
			$total_volume_produksi_akhir_solar = $volume_stock_opname_solar;
			$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
			$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

			$total_volume_produksi_solar = $total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar;
			$total_harga_produksi_solar =  $total_harga_produksi_akhir_solar;
			$total_nilai_produksi_solar =  $total_volume_produksi_solar * $total_harga_produksi_solar;

			//Total Opening Balance
			$opening_balance_bahan_baku = $nilai_opening_balance + $nilai_opening_balance_solar;

			//Total
			$total_nilai_masuk = $total_nilai_pembelian + $total_nilai_pembelian_solar;
			$total_nilai_keluar = $total_nilai_produksi + $total_nilai_produksi_solar;
			$total_nilai_akhir = $total_nilai_produksi_akhir + $total_nilai_produksi_akhir_solar;
	        ?>
			
			<tr class="table-active4">
				<th width="30%" class="text-center" rowspan="2" style="vertical-align:middle">TANGGAL</th>
				<th width="20%" class="text-center" rowspan="2" style="vertical-align:middle">URAIAN</th>
				<th width="10%" class="text-center" rowspan="2" style="vertical-align:middle">SATUAN</th>
				<th width="20%" class="text-center" colspan="3">MASUK</th>
				<th width="20%" class="text-center" colspan="3">KELUAR</th>
				<th width="20%" class="text-center" colspan="3">AKHIR</th>
	        </tr>
			<tr class="table-active4">
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BATU BOULDER</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Pembelian</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku['nilai'],0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_pembelian_akhir,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_akhir,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_pembelian_akhir,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BBM SOLAR</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Pembelian</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku_solar['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku_solar['nilai'],0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_pembelian_akhir_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_akhir_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_pembelian_akhir_solar,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BAHAN BAKU</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($opening_balance_bahan_baku,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu Boulder</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku['nilai'],0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>BBM Solar</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku_solar['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku_solar['nilai'],0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>		
	        </tr>
			<tr class="table-active5">
	            <th class="text-center" colspan="3">TOTAL</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
	        </tr>
	    </table>
		<?php
	}

	public function pergerakan_bahan_baku_penyesuaian($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #cccccc;
				color: black;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			</style>
	        <tr class="table-active2">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="9"><?php echo $filter_date;?></th>
	        </tr>
			
			<?php

			//Opening Balance
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_batu_boulder_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 15")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu_boulder_ago_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 15")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
			->from('hpp_bahan_baku pp')
			->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date_hpp','desc')->limit(1)
			->get()->row_array();

			$volume_opening_balance = $stock_opname_batu_boulder_ago['volume'] + $stock_opname_batu_boulder_ago_2['volume'];
			$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
			$nilai_opening_balance = $volume_opening_balance * $harga_opening_balance;

			$stock_opname_solar_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 13")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_solar_ago_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 13")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_opening_balance_solar = $stock_opname_solar_ago['volume'] + $stock_opname_solar_ago_2['volume'];	
			$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
			$nilai_opening_balance_solar = $volume_opening_balance_solar * $harga_opening_balance_solar;

			//Now
			//Bahan Baku			
			$pergerakan_bahan_baku = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			(prm.display_price / prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 15")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
			$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
			$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

			$total_volume_pembelian_akhir  = round($volume_opening_balance + $total_volume_pembelian,2);
			$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
			$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			
			
			$stock_opname_batu_boulder = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 15")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu_boulder_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 15")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();
			
			$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
			$total_harga_produksi_akhir = round($total_harga_pembelian_akhir,0);
			$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

			$total_volume_produksi = round($total_volume_pembelian_akhir - $total_volume_produksi_akhir,2);
			$total_harga_produksi = $total_harga_produksi_akhir;
			$total_nilai_produksi = $total_volume_produksi * $total_harga_produksi;

			$total_volume_produksi_loss_akhir = $stock_opname_batu_boulder['volume'] + $stock_opname_batu_boulder_2['volume'];
			$total_harga_produksi_loss_akhir = round($total_harga_pembelian_akhir,0);
			$total_nilai_produksi_loss_akhir = $total_volume_produksi_loss_akhir * $total_harga_produksi_loss_akhir;

			$total_volume_produksi_loss = round($total_volume_produksi_akhir - $total_volume_produksi_loss_akhir,2);
			$total_harga_produksi_loss = $total_harga_produksi_akhir;
			$total_nilai_produksi_loss = $total_volume_produksi_loss * $total_harga_produksi_loss;

			//BBM Solar
			$pergerakan_bahan_baku_solar = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			(prm.display_price / prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 13")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
			$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
			$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

			$total_volume_pembelian_akhir_solar  = round($volume_opening_balance_solar + $total_volume_pembelian_solar,2);
			$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
			$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

			$stock_opname_solar = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 13")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_solar_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 13")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$total_volume_produksi_akhir_solar = $stock_opname_solar['volume'] + $stock_opname_solar_2['volume'];
			$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
			$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

			$total_volume_produksi_solar = round($total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar,2);
			$total_harga_produksi_solar =  $total_harga_produksi_akhir_solar;
			$total_nilai_produksi_solar =  $total_volume_produksi_solar * $total_harga_produksi_solar;

			//Total
			$total_volume_produksi_boulder = round($total_volume_produksi + $total_volume_produksi_loss,2);
			$total_harga_produksi_boulder = $total_harga_produksi_loss_akhir;
			$total_nilai_produksi_boulder = $total_nilai_produksi + $total_nilai_produksi_loss;

			//Total Opening Balance
			$opening_balance_bahan_baku = $nilai_opening_balance + $nilai_opening_balance_solar;

			//Total
			$total_nilai_masuk = $total_nilai_pembelian + $total_nilai_pembelian_solar;
			$total_nilai_keluar = $total_nilai_produksi + $total_nilai_produksi_loss + $total_nilai_produksi_solar;
			$total_nilai_akhir = $total_nilai_produksi_loss_akhir + $total_nilai_produksi_akhir_solar;
	        ?>
			
			<tr class="table-active4">
				<th width="30%" class="text-center" rowspan="2" style="vertical-align:middle">TANGGAL</th>
				<th width="20%" class="text-center" rowspan="2" style="vertical-align:middle">URAIAN</th>
				<th width="10%" class="text-center" rowspan="2" style="vertical-align:middle">SATUAN</th>
				<th width="20%" class="text-center" colspan="3">MASUK</th>
				<th width="20%" class="text-center" colspan="3">KELUAR</th>
				<th width="20%" class="text-center" colspan="3">AKHIR</th>
	        </tr>
			<tr class="table-active4">
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BATU BOULDER</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Pembelian</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku['nilai'],0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_pembelian_akhir,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_pembelian_akhir,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($total_nilai_pembelian_akhir,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Factor Loss</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_loss,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_loss,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_loss,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_loss_akhir,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_loss_akhir,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_loss_akhir,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BBM SOLAR</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"><?php echo number_format($volume_opening_balance_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_opening_balance_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_opening_balance_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Pembelian</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku_solar['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku_solar['nilai'],0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_pembelian_akhir_solar,2,',','.');?></th>
				<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($total_harga_pembelian_akhir_solar,0,',','.');?></blink></th>
				<th class="text-right"><?php echo number_format($total_nilai_pembelian_akhir_solar,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
				<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
				<th class="text-left"><i>Produksi</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>
	        </tr>
			<tr class="table-active2">
	            <th class="text-center" colspan="12">BAHAN BAKU</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center" style="vertical-align:middle"><?php echo $date2_ago;?></th>
	            <th class="text-left" colspan="8"><i>Opening Balance</i></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($opening_balance_bahan_baku,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>Batu Boulder</i></th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku['nilai'],0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_boulder,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_boulder,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_boulder,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_loss_akhir,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_loss_akhir,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_loss_akhir,0,',','.');?></th>		
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
				<th class="text-left"><i>BBM Solar</i></th>
				<th class="text-center">Liter</th>
				<th class="text-center"><?php echo number_format($pergerakan_bahan_baku_solar['volume'],2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_pembelian_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($pergerakan_bahan_baku_solar['nilai'],0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>		
	        </tr>
			<tr class="table-active5">
	            <th class="text-center" colspan="3">TOTAL</th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
				<th class="text-center"></th>
				<th class="text-right"></th>
				<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
	        </tr>
	    </table>
		<?php
	}
	
	public function nilai_persediaan_bahan_baku($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}

	    ?>
		
		<table class="table table-bordered" width="100%">
		<style type="text/css">
		table tr.table-active{
			background-color: #F0F0F0;
			font-size: 12px;
			font-weight: bold;
			color: black;
		}
			
		table tr.table-active2{
			background-color: #E8E8E8;
			font-size: 12px;
			font-weight: bold;
		}
			
		table tr.table-active3{
			font-size: 12px;
			background-color: #F0F0F0;
		}
			
		table tr.table-active4{
			background-color: #e69500;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-active5{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-activeago1{
			background-color: #ffd966;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-activeopening{
			background-color: #2986cc;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		</style>

		<?php

		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_batu_boulder_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu_boulder_ago_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 15")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->order_by('pp.date_hpp','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance = $stock_opname_batu_boulder_ago['volume'] + $stock_opname_batu_boulder_ago_2['volume'];
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $volume_opening_balance * $harga_opening_balance;

		$stock_opname_solar_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_solar_ago_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 13")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance_solar = $stock_opname_solar_ago['volume'] + $stock_opname_solar_ago_2['volume'];	
		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = round($volume_opening_balance + $total_volume_pembelian,2);
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			

		$stock_opname_batu_boulder = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu_boulder_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir = $stock_opname_batu_boulder['volume'] + $stock_opname_batu_boulder_2['volume'];
		$total_harga_produksi_akhir = round($total_harga_pembelian_akhir,0);
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		$total_volume_produksi = round($total_volume_pembelian_akhir - $total_volume_produksi_akhir,2);
		$total_harga_produksi = $total_harga_produksi_akhir;
		$total_nilai_produksi = $total_volume_produksi * $total_harga_produksi;

		//BBM Solar
		$pergerakan_bahan_baku_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = round($volume_opening_balance_solar + $total_volume_pembelian_solar,2);
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_solar_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir_solar = $stock_opname_solar['volume'] + $stock_opname_solar_2['volume'];
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = round($total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar,2);
		$total_harga_produksi_solar =  $total_harga_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $total_volume_produksi_solar * $total_harga_produksi_solar;

		//Total Opening Balance
		$opening_balance_bahan_baku = $nilai_opening_balance + $nilai_opening_balance_solar;

		//Total
		$total_nilai_masuk = $total_nilai_pembelian + $total_nilai_pembelian_solar;
		$total_nilai_keluar = $total_nilai_produksi + $total_nilai_produksi_solar;
		$total_nilai_akhir = $total_nilai_produksi_akhir + $total_nilai_produksi_akhir_solar;

		?>

		<tr class="table-active2">
			<th colspan="3">Periode</th>
			<th class="text-center" colspan="9"><?php echo $filter_date;?></th>
		</tr>
		<tr class="table-active4">
			<th width="10%" class="text-center" >TANGGAL</th>
			<th width="20%" class="text-center" >URAIAN</th>
			<th width="30%" class="text-center" >STOK BARANG</th>
			<th width="20%" class="text-center" >HARGA SATUAN</th>
			<th width="20%" class="text-center" >NILAI</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"><?php echo date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left">Batu Boulder</th>
			<th class="text-center"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"><?php echo date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left">BBM Solar</th>
			<th class="text-center"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>
		</tr>
		<tr class="table-active5">
			<th class="text-right" colspan="4">TOTAL</th>
			<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
		</tr>
	</table>
	<?php
	}

	public function nilai_persediaan_bahan_baku_dashboard($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		
		$last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
		$last_production_2 = $this->db->select('date')->order_by('date','desc')->limit(1,1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','material_id'=>'4'))->row_array();

		$date1 = date('Y-m-d', strtotime('+1 days', strtotime($last_production_2['date'])));
		$date2 = date('Y-m-d', strtotime($last_production['date']));
		$date1_filter = date('d F Y', strtotime($date1));
		$date2_filter = date('d F Y', strtotime($date2));

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
			$date1_filter = date('d F Y',strtotime($arr_filter_date[0]));
			$date2_filter = date('d F Y',strtotime($arr_filter_date[1]));
		}

	    ?>
		
		<table class="table table-bordered" width="100%">

		<?php

		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_batu_boulder_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu_boulder_ago_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 15")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->order_by('pp.date_hpp','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance = $stock_opname_batu_boulder_ago['volume'] + $stock_opname_batu_boulder_ago_2['volume'];
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $volume_opening_balance * $harga_opening_balance;

		$stock_opname_solar_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_solar_ago_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 13")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance_solar = $stock_opname_solar_ago['volume'] + $stock_opname_solar_ago_2['volume'];	
		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = round($volume_opening_balance + $total_volume_pembelian,2);
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			

		$stock_opname_batu_boulder = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu_boulder_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 15")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir = $stock_opname_batu_boulder['volume'] + $stock_opname_batu_boulder_2['volume'];
		$total_harga_produksi_akhir = round($total_harga_pembelian_akhir,0);
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		$total_volume_produksi = round($total_volume_pembelian_akhir - $total_volume_produksi_akhir,2);
		$total_harga_produksi = $total_harga_produksi_akhir;
		$total_nilai_produksi = $total_volume_produksi * $total_harga_produksi;

		//BBM Solar
		$pergerakan_bahan_baku_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = round($volume_opening_balance_solar + $total_volume_pembelian_solar,2);
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_solar_2 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 13")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir_solar = $stock_opname_solar['volume'] + $stock_opname_solar_2['volume'];
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = round($total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar,2);
		$total_harga_produksi_solar =  $total_harga_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $total_volume_produksi_solar * $total_harga_produksi_solar;

		//Total Opening Balance
		$opening_balance_bahan_baku = $nilai_opening_balance + $nilai_opening_balance_solar;

		//Total
		$total_nilai_masuk = $total_nilai_pembelian + $total_nilai_pembelian_solar;
		$total_nilai_keluar = $total_nilai_produksi + $total_nilai_produksi_solar;
		$total_nilai_akhir = $total_nilai_produksi_akhir + $total_nilai_produksi_akhir_solar;

		?>

		<tr class="table-active">
			<th class="text-center" colspan="6" style='background-color:#5B5B5B; color:white; text-transform:uppercase;'>
			Nilai Persediaan Bahan Baku
			(<?php
			$search = array(
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December'
			);
			
			$replace = array(
			'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
			);
			
			$subject = "$date1_filter - $date2_filter";

			echo str_replace($search, $replace, $subject);

			?>)
			</th>
		</tr>
	<tr>
		<th width="5%" class="text-center" style='background-color:rgb(188,188,188); color:black'>NO.</th>
		<th class="text-center" style='background-color:rgb(188,188,188); color:black'>URAIAN</th>
		<th class="text-center" style='background-color:rgb(188,188,188); color:black'>SATUAN</th>
		<th class="text-center" style='background-color:rgb(188,188,188); color:black'>STOK BARANG</th>
		<th class="text-center" style='background-color:rgb(188,188,188); color:black'>HARGA SATUAN</th>
		<th class="text-center" style='background-color:rgb(188,188,188); color:black'>NILAI</th>
	</tr>
	<tr>
		<th class="text-center">1.</th>
		<th class="text-left">Batu Boulder</th>
		<th class="text-center">Ton</th>
		<th class="text-right"><?php echo number_format($total_volume_produksi_akhir,2,',','.');?></th>
		<th class="text-right"><?php echo number_format($total_harga_produksi_akhir,0,',','.');?></th>
		<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir,0,',','.');?></th>
	</tr>
	<tr>
		<th class="text-center">2.</th>
		<th class="text-left">BBM Solar</th>
		<th class="text-center">Liter</th>
		<th class="text-right"><?php echo number_format($total_volume_produksi_akhir_solar,2,',','.');?></th>
		<th class="text-right"><?php echo number_format($total_harga_produksi_akhir_solar,0,',','.');?></th>
		<th class="text-right"><?php echo number_format($total_nilai_produksi_akhir_solar,0,',','.');?></th>
	</tr>
	<tr class="table-active2">
		<th class="text-right" colspan="5">TOTAL</th>
		<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
	</tr>
	</table>
	<?php
	}
	
	public function pergerakan_bahan_jadi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		<style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #cccccc;
				color: black;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}

		</style>
		<tr class="table-active2">
			<th colspan="3">Periode</th>
			<th class="text-center" colspan="9"><?php echo $filter_date;?></th>
		</tr>
			
		<?php

		//LAPORAN BEBAN POKOK PRODUKSI

		//PERGERAKAN BAHAN BAKU
		
		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		
		$pergerakan_bahan_baku_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();
		
		$total_volume_pembelian_ago = $pergerakan_bahan_baku_ago['volume'];
		$total_volume_pembelian_akhir_ago  = $total_volume_pembelian_ago;
		
		$produksi_harian_ago = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1_ago' and '$date2_ago')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();
		
		$total_volume_produksi_ago = $produksi_harian_ago['used'];
		$total_volume_produksi_akhir_ago = $total_volume_pembelian_akhir_ago - $total_volume_produksi_ago;

		$harga_satuan_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date3_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$nilai_harga_satuan_ago = ($harga_satuan_ago['volume']!=0)?($harga_satuan_ago['nilai'] / $harga_satuan_ago['volume'])  * 1:0;

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->get()->row_array();

		$total_volume_produksi_akhir_ago_fix = round($total_volume_produksi_akhir_ago,2);

		$volume_opening_balance = $total_volume_produksi_akhir_ago_fix;
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $total_volume_produksi_akhir_ago_fix * $harga_opening_balance;

		$pergerakan_bahan_baku_ago_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$volume_pergerakan_bahan_baku_ago_solar = $pergerakan_bahan_baku_ago_solar['volume'];
		
		$stock_opname_solar_ago = $this->db->select('`prm`.`volume` as volume, `prm`.`total` as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("(prm.date < '$date1')")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar_ago = $stock_opname_solar_ago['volume'];

		$volume_opening_balance_solar = $volume_stock_opname_solar_ago;
		$volume_opening_balance_solar_fix = round($volume_opening_balance_solar,2);

		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar_fix * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();
		
		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = $total_volume_produksi_akhir_ago + $total_volume_pembelian;
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			
		
		$produksi_harian = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
		->from('akumulasi_bahan_baku pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->result_array();

		$total_akumulasi_bahan_baku = 0;
		$total_akumulasi_bahan_baku_2 = 0;

		foreach ($akumulasi_bahan_baku as $b){
			$total_akumulasi_bahan_baku += $b['total_nilai_keluar'];
			$total_akumulasi_bahan_baku_2 += $b['total_nilai_keluar_2'];
		}

		$akumulasi_nilai_bahan_baku = $total_akumulasi_bahan_baku;
		$akumulasi_nilai_bahan_baku_2 = $total_akumulasi_bahan_baku_2;
		
		$total_volume_produksi = $produksi_harian['used'];
		$total_nilai_produksi = $akumulasi_nilai_bahan_baku;
		$total_harga_produksi = ($total_volume_produksi!=0)?($total_nilai_produksi / $total_volume_produksi)  * 1:0;
		
		$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
		$total_harga_produksi_akhir = $total_harga_produksi;
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		//BBM Solar
		$pergerakan_bahan_baku_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();
		
		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(prm.volume) as volume, (prm.total) as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("prm.date between '$date1' and '$date2'")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar = $stock_opname_solar['volume'];
		
		$total_volume_produksi_akhir_solar = $volume_stock_opname_solar;
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = $total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $akumulasi_nilai_bahan_baku_2;
		$total_harga_produksi_solar = ($total_volume_produksi_solar!=0)?($total_nilai_produksi_solar / $total_volume_produksi_solar)  * 1:0;
		//END PERGERAKAN BAHAN BAKU

		//PERALATAN & OPERASIONAL
		$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_abu_batu = 0;
		$nilai_abu_batu1 = 0;
		$nilai_abu_batu2 = 0;
		$nilai_abu_batu3 = 0;
		$nilai_abu_batu4 = 0;
		$nilai_abu_batu5 = 0;
		$nilai_abu_batu_all = 0;

		$total_abu_batu = $abu_batu['jumlah_pemakaian_a'] + $abu_batu['jumlah_pemakaian_b'] + $abu_batu['jumlah_pemakaian_c'] + $abu_batu['jumlah_pemakaian_d'] + $abu_batu['jumlah_pemakaian_e'];
		$nilai_abu_batu1 = $abu_batu['jumlah_pemakaian_a'] * $total_harga_produksi_akhir;
		$nilai_abu_batu2 = $abu_batu['jumlah_pemakaian_b'] * $total_harga_produksi_akhir;
		$nilai_abu_batu3 = $abu_batu['jumlah_pemakaian_c'] * $total_harga_produksi_akhir;
		$nilai_abu_batu4 = $abu_batu['jumlah_pemakaian_d'] * $total_harga_produksi_akhir;
		$nilai_abu_batu5 = $abu_batu['jumlah_pemakaian_e'] * $total_harga_produksi_akhir;
		$nilai_abu_batu_all = $nilai_abu_batu1 + $nilai_abu_batu2 + $nilai_abu_batu3 + $nilai_abu_batu4 + $nilai_abu_batu5;

		$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;
		
		$stone_crusher_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher = $stone_crusher_biaya['total'] + $stone_crusher_jurnal['total'];
		
		$whell_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader = $whell_loader_biaya['total'] + $whell_loader_jurnal['total'];
		
		$excavator = $this->db->select('sum(prm.display_price) as price')
		->from('pmm_receipt_material prm ')
		->where("prm.material_id = 18")
		->where("(prm.date_receipt between '$date1' and '$date2')")
		->get()->row_array();
		
		$genset_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset = $genset_biaya['total'] + $genset_jurnal['total'];
		
		$timbangan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan_biaya_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan = $timbangan_biaya['total'] + $timbangan_biaya_jurnal['total'];
		
		$tangki_solar_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar = $tangki_solar_biaya['total'] + $tangki_solar_jurnal['total'];		
		
		$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar;
		$hpp_peralatan = ($total_abu_batu!=0)?($total_biaya_peralatan / $total_abu_batu)  * 1:0;
		
		$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];
		
		$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];
		
		$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];
		
		$perbaikan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		
		$perbaikan = $perbaikan_biaya['total'] + $perbaikan_jurnal['total'];

		$akomodasi_tamu_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu = $akomodasi_tamu_biaya['total'] + $akomodasi_tamu_jurnal['total'];
		
		$pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian = $pengujian_biaya['total'] + $pengujian_jurnal['total'];
		
		$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		
		$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

		$total_operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;
		$hpp_operasional = ($total_abu_batu!=0)?($total_operasional / $total_abu_batu)  * 1:0;
		$total_bpp = $total_nilai_produksi + $total_nilai_produksi_solar + $total_biaya_peralatan + $total_operasional;
		$harga_bpp = ($total_abu_batu!=0)?($total_bpp / $total_abu_batu)  * 1:0;
		
		$harga_pemakaian_a = 0;
		$harga_pemakaian_b = 0;
		$harga_pemakaian_c = 0;
		$harga_pemakaian_d = 0;
		$total_harga_pemakaian = 0;
		
		$harga_pemakaian_a = $harga_bpp * $abu_batu['jumlah_pemakaian_a'];
		$harga_pemakaian_b = $harga_bpp * $abu_batu['jumlah_pemakaian_b'];
		$harga_pemakaian_c = $harga_bpp * $abu_batu['jumlah_pemakaian_c'];
		$harga_pemakaian_d = $harga_bpp * $abu_batu['jumlah_pemakaian_d'];
		
		$total_harga_pemakaian = $harga_pemakaian_a + $harga_pemakaian_b + $harga_pemakaian_c + $harga_pemakaian_d;
		//END PERALATAN & OPERASIONAL
		//END LAPORAN BEBAN POKOK PRODUKSI
			
	        
		//Opening Balance Pergerakan Bahan Jadi
		$tanggal_awal = date('2020-01-01');
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$produksi_harian_bulan_lalu = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$tanggal_awal' and '$tanggal_opening_balance')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$volume_produksi_harian_abubatu_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_a'];
		$volume_produksi_harian_batu0510_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_b'];
		$volume_produksi_harian_batu1020_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_c'];
		$volume_produksi_harian_batu2030_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_d'];

		$penjualan_abubatu_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_abubatu_bulan_lalu = $penjualan_abubatu_bulan_lalu['volume'];

		$penjualan_batu0510_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu0510_bulan_lalu = $penjualan_batu0510_bulan_lalu['volume'];

		$penjualan_batu1020_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu1020_bulan_lalu = $penjualan_batu1020_bulan_lalu['volume'];

		$penjualan_batu2030_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu2030_bulan_lalu = $penjualan_batu2030_bulan_lalu['volume'];

		//Agregat Bulan Lalu
		$agregat_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 24")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_lalu = $agregat_bulan_lalu['volume_agregat_a'];
		$volume_agregat_batu0510_bulan_lalu = $agregat_bulan_lalu['volume_agregat_b'];
		$volume_agregat_batu1020_bulan_lalu = $agregat_bulan_lalu['volume_agregat_c'];
		$volume_agregat_batu2030_bulan_lalu = $agregat_bulan_lalu['volume_agregat_d'];

		$agregat_bulan_lalu_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 14")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_a'];
		$volume_agregat_batu0510_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_b'];
		$volume_agregat_batu1020_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_c'];
		$volume_agregat_batu2030_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_d'];

		//Opening Balance
		$volume_opening_balance_abubatu_bulan_lalu = round($volume_produksi_harian_abubatu_bulan_lalu - $volume_penjualan_abubatu_bulan_lalu - $volume_agregat_abubatu_bulan_lalu - $volume_agregat_abubatu_bulan_lalu_2,2);
		$volume_opening_balance_batu0510_bulan_lalu = round($volume_produksi_harian_batu0510_bulan_lalu - $volume_penjualan_batu0510_bulan_lalu - $volume_agregat_batu0510_bulan_lalu - $volume_agregat_batu0510_bulan_lalu_2,2);
		$volume_opening_balance_batu1020_bulan_lalu = round($volume_produksi_harian_batu1020_bulan_lalu - $volume_penjualan_batu1020_bulan_lalu - $volume_agregat_batu1020_bulan_lalu - $volume_agregat_batu1020_bulan_lalu_2,2);
		$volume_opening_balance_batu2030_bulan_lalu = round($volume_produksi_harian_batu2030_bulan_lalu - $volume_penjualan_batu2030_bulan_lalu - $volume_agregat_batu2030_bulan_lalu - $volume_agregat_batu2030_bulan_lalu_2,2);

		//Rumus Harga Opening Balance

		//Dua Bulan Lalu
		$tanggal_opening_balance_2 = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		//Satu Bulan Lalu
		$tanggal_opening_balance_3 = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		
		$harga_hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp pp')
		->where("(pp.date_hpp between '$tanggal_opening_balance_2' and '$tanggal_opening_balance_3')")
		->get()->row_array();

		$harga_opening_balance_abubatu_bulan_lalu = $harga_hpp['abubatu'];
		$harga_opening_balance_batu0510_bulan_lalu =  $harga_hpp['batu0510'];
		$harga_opening_balance_batu1020_bulan_lalu =  $harga_hpp['batu1020'];
		$harga_opening_balance_batu2030_bulan_lalu =  $harga_hpp['batu2030'];

		$vol_1 = round($volume_opening_balance_abubatu_bulan_lalu,2);
		$vol_2 = round($volume_opening_balance_batu0510_bulan_lalu,2);
		$vol_3 = round($volume_opening_balance_batu1020_bulan_lalu,2);
		$vol_4 = round($volume_opening_balance_batu2030_bulan_lalu,2);

		$nilai_opening_balance_abubatu_bulan_lalu = $vol_1 * $harga_opening_balance_abubatu_bulan_lalu;
		$nilai_opening_balance_batu0510_bulan_lalu = $vol_2 * $harga_opening_balance_batu0510_bulan_lalu;
		$nilai_opening_balance_batu1020_bulan_lalu = $vol_3 * $harga_opening_balance_batu1020_bulan_lalu;
		$nilai_opening_balance_batu2030_bulan_lalu = $vol_4 * $harga_opening_balance_batu2030_bulan_lalu;

		//Now		
		$produksi_harian_bulan_ini = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a as presentase_a, pk.presentase_b as presentase_b, pk.presentase_c as presentase_c, pk.presentase_d as presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$volume_produksi_harian_abubatu_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_a'];
		$volume_produksi_harian_batu0510_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_b'];
		$volume_produksi_harian_batu1020_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_c'];
		$volume_produksi_harian_batu2030_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_d'];

		$harga_produksi_harian_abubatu_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu0510_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu1020_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu2030_bulan_ini = $harga_bpp;

		$nilai_produksi_harian_abubatu_bulan_ini = $volume_produksi_harian_abubatu_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu0510_bulan_ini = $volume_produksi_harian_batu0510_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu1020_bulan_ini = $volume_produksi_harian_batu1020_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu2030_bulan_ini = $volume_produksi_harian_batu2030_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_abubatu_bulan_ini = $volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini;
		$harga_akhir_produksi_harian_abubatu_bulan_ini = ($volume_akhir_produksi_harian_abubatu_bulan_ini!=0)?($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_batu0510_bulan_ini = $volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini;
		$harga_akhir_produksi_harian_batu0510_bulan_ini = ($volume_akhir_produksi_harian_batu0510_bulan_ini!=0)?($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

		$volume_akhir_produksi_harian_batu1020_bulan_ini = $volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini;
		$harga_akhir_produksi_harian_batu1020_bulan_ini = ($volume_akhir_produksi_harian_batu1020_bulan_ini!=0)?($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

		$volume_akhir_produksi_harian_batu2030_bulan_ini = $volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini;
		$harga_akhir_produksi_harian_batu2030_bulan_ini = ($volume_akhir_produksi_harian_batu2030_bulan_ini!=0)?($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;
	
		//Abu Batu
		$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_abubatu_bulan_ini = $penjualan_abubatu_bulan_ini['volume'];
		$harga_penjualan_abubatu_bulan_ini = round($harga_akhir_produksi_harian_abubatu_bulan_ini,0);
		$nilai_penjualan_abubatu_bulan_ini = $volume_penjualan_abubatu_bulan_ini * $harga_penjualan_abubatu_bulan_ini;

		$volume_akhir_penjualan_abubatu_bulan_ini = round($volume_akhir_produksi_harian_abubatu_bulan_ini - $volume_penjualan_abubatu_bulan_ini,2);
		$harga_akhir_penjualan_abubatu_bulan_ini = $harga_penjualan_abubatu_bulan_ini;
		$nilai_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini * $harga_akhir_penjualan_abubatu_bulan_ini;

		//Batu 0,5 - 10
		$penjualan_batu0510_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu0510_bulan_ini = $penjualan_batu0510_bulan_ini['volume'];
		$harga_penjualan_batu0510_bulan_ini = round($harga_akhir_produksi_harian_batu0510_bulan_ini,0);
		$nilai_penjualan_batu0510_bulan_ini = $volume_penjualan_batu0510_bulan_ini * $harga_penjualan_batu0510_bulan_ini;

		$volume_akhir_penjualan_batu0510_bulan_ini = round($volume_akhir_produksi_harian_batu0510_bulan_ini - $volume_penjualan_batu0510_bulan_ini,2);
		$harga_akhir_penjualan_batu0510_bulan_ini =  $harga_penjualan_batu0510_bulan_ini;
		$nilai_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini * $harga_akhir_penjualan_batu0510_bulan_ini;

		//Batu 10 - 20
		$penjualan_batu1020_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu1020_bulan_ini = $penjualan_batu1020_bulan_ini['volume'];
		$harga_penjualan_batu1020_bulan_ini = round($harga_akhir_produksi_harian_batu1020_bulan_ini,0);
		$nilai_penjualan_batu1020_bulan_ini = $volume_penjualan_batu1020_bulan_ini * $harga_penjualan_batu1020_bulan_ini;

		$volume_akhir_penjualan_batu1020_bulan_ini = round($volume_akhir_produksi_harian_batu1020_bulan_ini - $volume_penjualan_batu1020_bulan_ini,2);
		$harga_akhir_penjualan_batu1020_bulan_ini = $harga_penjualan_batu1020_bulan_ini;
		$nilai_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini * $harga_akhir_penjualan_batu1020_bulan_ini;

		//Batu 20 - 30
		$penjualan_batu2030_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu2030_bulan_ini = $penjualan_batu2030_bulan_ini['volume'];
		$harga_penjualan_batu2030_bulan_ini = round($harga_akhir_produksi_harian_batu2030_bulan_ini,0);
		$nilai_penjualan_batu2030_bulan_ini = $volume_penjualan_batu2030_bulan_ini * $harga_penjualan_batu2030_bulan_ini;

		$volume_akhir_penjualan_batu2030_bulan_ini = round($volume_akhir_produksi_harian_batu2030_bulan_ini - $volume_penjualan_batu2030_bulan_ini,2);
		$harga_akhir_penjualan_batu2030_bulan_ini = $harga_penjualan_batu2030_bulan_ini;
		$nilai_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini * $harga_akhir_penjualan_batu2030_bulan_ini;
		
		//Agregat
		$agregat_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 24")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini = round($agregat_bulan_ini['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini = round($agregat_bulan_ini['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini = round($agregat_bulan_ini['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini = round($agregat_bulan_ini['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini = $harga_akhir_penjualan_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini = $harga_akhir_penjualan_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini = $harga_akhir_penjualan_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini = $harga_akhir_penjualan_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini = $volume_agregat_abubatu_bulan_ini * $harga_agregat_abubatu_bulan_ini;
		$nilai_agregat_batu0510_bulan_ini = $volume_agregat_batu0510_bulan_ini * $harga_agregat_batu0510_bulan_ini;
		$nilai_agregat_batu1020_bulan_ini = $volume_agregat_batu1020_bulan_ini * $harga_agregat_batu1020_bulan_ini;
		$nilai_agregat_batu2030_bulan_ini = $volume_agregat_batu2030_bulan_ini * $harga_agregat_batu2030_bulan_ini;

		$volume_akhir_agregat_abubatu_bulan_ini = round($volume_akhir_penjualan_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini,2);
		$volume_akhir_agregat_batu0510_bulan_ini = round($volume_akhir_penjualan_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini,2);
		$volume_akhir_agregat_batu1020_bulan_ini = round($volume_akhir_penjualan_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini,2);
		$volume_akhir_agregat_batu2030_bulan_ini = round($volume_akhir_penjualan_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini,2);

		$harga_akhir_agregat_abubatu_bulan_ini = $harga_agregat_abubatu_bulan_ini;
		$harga_akhir_agregat_batu0510_bulan_ini = $harga_agregat_batu0510_bulan_ini;
		$harga_akhir_agregat_batu1020_bulan_ini = $harga_agregat_batu1020_bulan_ini;
		$harga_akhir_agregat_batu2030_bulan_ini = $harga_agregat_batu2030_bulan_ini;

		$nilai_akhir_agregat_abubatu_bulan_ini = $volume_akhir_agregat_abubatu_bulan_ini * $harga_akhir_agregat_abubatu_bulan_ini;
		$nilai_akhir_agregat_batu0510_bulan_ini = $volume_akhir_agregat_batu0510_bulan_ini * $harga_akhir_agregat_batu0510_bulan_ini;
		$nilai_akhir_agregat_batu1020_bulan_ini = $volume_akhir_agregat_batu1020_bulan_ini * $harga_akhir_agregat_batu1020_bulan_ini;
		$nilai_akhir_agregat_batu2030_bulan_ini = $volume_akhir_agregat_batu2030_bulan_ini * $harga_akhir_agregat_batu2030_bulan_ini;

		$agregat_bulan_ini_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 14")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini_2 = $volume_agregat_abubatu_bulan_ini_2 * $harga_agregat_abubatu_bulan_ini_2;
		$nilai_agregat_batu0510_bulan_ini_2 = $volume_agregat_batu0510_bulan_ini_2 * $harga_agregat_batu0510_bulan_ini_2;
		$nilai_agregat_batu1020_bulan_ini_2 = $volume_agregat_batu1020_bulan_ini_2 * $harga_agregat_batu1020_bulan_ini_2;
		$nilai_agregat_batu2030_bulan_ini_2 = $volume_agregat_batu2030_bulan_ini_2 * $harga_agregat_batu2030_bulan_ini_2;

		$volume_akhir_agregat_abubatu_bulan_ini_2 = round($volume_akhir_agregat_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini_2,2);
		$volume_akhir_agregat_batu0510_bulan_ini_2 = round($volume_akhir_agregat_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini_2,2);
		$volume_akhir_agregat_batu1020_bulan_ini_2 = round($volume_akhir_agregat_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini_2,2);
		$volume_akhir_agregat_batu2030_bulan_ini_2 = round($volume_akhir_agregat_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini_2,2);

		$harga_akhir_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini_2;
		$harga_akhir_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini_2;
		$harga_akhir_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini_2;
		$harga_akhir_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini_2;

		$nilai_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini_2 * $harga_akhir_agregat_abubatu_bulan_ini_2;
		$nilai_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini_2 * $harga_akhir_agregat_batu0510_bulan_ini_2;
		$nilai_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini_2 * $harga_akhir_agregat_batu1020_bulan_ini_2;
		$nilai_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini_2 * $harga_akhir_agregat_batu2030_bulan_ini_2;

		//TOTAL BAHAN BAKU
		$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

		$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
		$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
		$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

		$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
		$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
		$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

		$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2;
		$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
		$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

		$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
		$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

		//TOTAL
		$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
		$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

		$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
		
		$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
		$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;
		
		?>

		<tr class="table-active4">
			<th width="30%" class="text-center" rowspan="2" style="vertical-align:middle">TANGGAL</th>
			<th width="20%" class="text-center" rowspan="2" style="vertical-align:middle">URAIAN</th>
			<th width="10%" class="text-center" rowspan="2" style="vertical-align:middle">SATUAN</th>
			<th width="20%" class="text-center" colspan="3">MASUK</th>
			<th width="20%" class="text-center" colspan="3">KELUAR</th>
			<th width="20%" class="text-center" colspan="3">AKHIR</th>
		</tr>
		<tr class="table-active4">
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 0,0 - 0,5</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_abubatu_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 0,5 - 10</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_batu0510_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 10 - 20</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_batu1020_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 20 - 30</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_batu2030_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BAHAN JADI</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"></th>
			<th class="text-right"></th>
			<th class="text-right"><?php echo number_format($nilai_opening_bahan_jadi,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 0,0 - 0,5</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_abubatu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_abubatu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 0,5 - 10</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu0510,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu0510,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 10 - 20</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu1020,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu1020,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 20 - 30</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu2030,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu2030,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active5">
			<th class="text-center" colspan="3">TOTAL</th>
			<th class="text-center"><?php echo number_format($total_volume_masuk,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($total_volume_keluar,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
		</tr>
	    </table>
		
		<?php
	}

	public function pergerakan_bahan_jadi_penyesuaian($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		<style type="text/css">
		table tr.table-active{
			background-color: #F0F0F0;
			font-size: 12px;
			font-weight: bold;
			color: black;
		}
			
		table tr.table-active2{
			background-color: #E8E8E8;
			font-size: 12px;
			font-weight: bold;
		}
			
		table tr.table-active3{
			font-size: 12px;
			background-color: #F0F0F0;
		}
			
		table tr.table-active4{
			background-color: #e69500;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-active5{
			background-color: #cccccc;
			color: black;
			font-size: 12px;
			font-weight: bold;
		}
		table tr.table-activeago1{
			background-color: #ffd966;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-activeopening{
			background-color: #2986cc;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}

		blink {
		-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
		animation: 2s linear infinite kedip;
		}
		/* for Safari 4.0 - 8.0 */
		@-webkit-keyframes kedip { 
		0% {
			visibility: hidden;
		}
		50% {
			visibility: hidden;
		}
		100% {
			visibility: visible;
		}
		}
		@keyframes kedip {
		0% {
			visibility: hidden;
		}
		50% {
			visibility: hidden;
		}
		100% {
			visibility: visible;
		}
		}

		</style>
		<tr class="table-active2">
			<th colspan="3">Periode</th>
			<th class="text-center" colspan="9"><?php echo $filter_date;?></th>
		</tr>
		
		<?php

		//PERGERAKAN BAHAN BAKU
		
		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		
		$pergerakan_bahan_baku_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();
		
		$total_volume_pembelian_ago = $pergerakan_bahan_baku_ago['volume'];
		$total_volume_pembelian_akhir_ago  = $total_volume_pembelian_ago;
		
		$produksi_harian_ago = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1_ago' and '$date2_ago')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();
		
		$total_volume_produksi_ago = $produksi_harian_ago['used'];
		$total_volume_produksi_akhir_ago = $total_volume_pembelian_akhir_ago - $total_volume_produksi_ago;

		$harga_satuan_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date3_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();
	
		$nilai_harga_satuan_ago = ($harga_satuan_ago['volume']!=0)?($harga_satuan_ago['nilai'] / $harga_satuan_ago['volume'])  * 1:0;

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->order_by('pp.date_hpp','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir_ago_fix = round($total_volume_produksi_akhir_ago,2);

		$volume_opening_balance = $total_volume_produksi_akhir_ago_fix;
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $total_volume_produksi_akhir_ago_fix * $harga_opening_balance;

		$pergerakan_bahan_baku_ago_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$volume_pergerakan_bahan_baku_ago_solar = $pergerakan_bahan_baku_ago_solar['volume'];
		
		$stock_opname_solar_ago = $this->db->select('`prm`.`volume` as volume, `prm`.`total` as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("(prm.date < '$date1')")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar_ago = $stock_opname_solar_ago['volume'];

		$volume_opening_balance_solar = $volume_stock_opname_solar_ago;
		$volume_opening_balance_solar_fix = round($volume_opening_balance_solar,2);

		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar_fix * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();
		
		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = $total_volume_produksi_akhir_ago + $total_volume_pembelian;
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			
		
		$produksi_harian = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
		->from('akumulasi_bahan_baku pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->result_array();

		$total_akumulasi_bahan_baku = 0;
		$total_akumulasi_bahan_baku_2 = 0;

		foreach ($akumulasi_bahan_baku as $b){
			$total_akumulasi_bahan_baku += $b['total_nilai_keluar'];
			$total_akumulasi_bahan_baku_2 += $b['total_nilai_keluar_2'];
		}

		$akumulasi_nilai_bahan_baku = $total_akumulasi_bahan_baku;
		$akumulasi_nilai_bahan_baku_2 = $total_akumulasi_bahan_baku_2;
		
		$total_volume_produksi = $produksi_harian['used'];
		$total_nilai_produksi = $akumulasi_nilai_bahan_baku;
		$total_harga_produksi = ($total_volume_produksi!=0)?($total_nilai_produksi / $total_volume_produksi)  * 1:0;
		
		$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
		$total_harga_produksi_akhir = $total_harga_produksi;
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		//BBM Solar
		$pergerakan_bahan_baku_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();
		
		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(prm.volume) as volume, (prm.total) as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("prm.date between '$date1' and '$date2'")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar = $stock_opname_solar['volume'];
		
		$total_volume_produksi_akhir_solar = $volume_stock_opname_solar;
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = $total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $akumulasi_nilai_bahan_baku_2;
		$total_harga_produksi_solar = ($total_volume_produksi_solar!=0)?($total_nilai_produksi_solar / $total_volume_produksi_solar)  * 1:0;
		//END PERGERAKAN BAHAN BAKU

		//PERALATAN & OPERASIONAL
		$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_abu_batu = 0;
		$nilai_abu_batu1 = 0;
		$nilai_abu_batu2 = 0;
		$nilai_abu_batu3 = 0;
		$nilai_abu_batu4 = 0;
		$nilai_abu_batu5 = 0;
		$nilai_abu_batu_all = 0;

		$total_abu_batu = $abu_batu['jumlah_pemakaian_a'] + $abu_batu['jumlah_pemakaian_b'] + $abu_batu['jumlah_pemakaian_c'] + $abu_batu['jumlah_pemakaian_d'] + $abu_batu['jumlah_pemakaian_e'];
		$nilai_abu_batu1 = $abu_batu['jumlah_pemakaian_a'] * $total_harga_produksi_akhir;
		$nilai_abu_batu2 = $abu_batu['jumlah_pemakaian_b'] * $total_harga_produksi_akhir;
		$nilai_abu_batu3 = $abu_batu['jumlah_pemakaian_c'] * $total_harga_produksi_akhir;
		$nilai_abu_batu4 = $abu_batu['jumlah_pemakaian_d'] * $total_harga_produksi_akhir;
		$nilai_abu_batu5 = $abu_batu['jumlah_pemakaian_e'] * $total_harga_produksi_akhir;
		$nilai_abu_batu_all = $nilai_abu_batu1 + $nilai_abu_batu2 + $nilai_abu_batu3 + $nilai_abu_batu4 + $nilai_abu_batu5;

		$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;
		
		$stone_crusher_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher = $stone_crusher_biaya['total'] + $stone_crusher_jurnal['total'];
		
		$whell_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader = $whell_loader_biaya['total'] + $whell_loader_jurnal['total'];
		
		$excavator = $this->db->select('sum(prm.display_price) as price')
		->from('pmm_receipt_material prm ')
		->where("prm.material_id = 18")
		->where("(prm.date_receipt between '$date1' and '$date2')")
		->get()->row_array();
		
		$genset_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset = $genset_biaya['total'] + $genset_jurnal['total'];
		
		$timbangan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan_biaya_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan = $timbangan_biaya['total'] + $timbangan_biaya_jurnal['total'];
		
		$tangki_solar_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar = $tangki_solar_biaya['total'] + $tangki_solar_jurnal['total'];		
		
		$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar;
		$hpp_peralatan = ($total_abu_batu!=0)?($total_biaya_peralatan / $total_abu_batu)  * 1:0;
		
		$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];
		
		$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];
		
		$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];
		
		$perbaikan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		
		$perbaikan = $perbaikan_biaya['total'] + $perbaikan_jurnal['total'];

		$akomodasi_tamu_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu = $akomodasi_tamu_biaya['total'] + $akomodasi_tamu_jurnal['total'];
		
		$pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian = $pengujian_biaya['total'] + $pengujian_jurnal['total'];
		
		$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();
		
		$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

		$total_operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;
		$hpp_operasional = ($total_abu_batu!=0)?($total_operasional / $total_abu_batu)  * 1:0;
		$total_bpp = $total_nilai_produksi + $total_nilai_produksi_solar + $total_biaya_peralatan + $total_operasional;
		$harga_bpp = ($total_abu_batu!=0)?($total_bpp / $total_abu_batu)  * 1:0;
		
		$harga_pemakaian_a = 0;
		$harga_pemakaian_b = 0;
		$harga_pemakaian_c = 0;
		$harga_pemakaian_d = 0;
		$total_harga_pemakaian = 0;
		
		$harga_pemakaian_a = $harga_bpp * $abu_batu['jumlah_pemakaian_a'];
		$harga_pemakaian_b = $harga_bpp * $abu_batu['jumlah_pemakaian_b'];
		$harga_pemakaian_c = $harga_bpp * $abu_batu['jumlah_pemakaian_c'];
		$harga_pemakaian_d = $harga_bpp * $abu_batu['jumlah_pemakaian_d'];
		
		$total_harga_pemakaian = $harga_pemakaian_a + $harga_pemakaian_b + $harga_pemakaian_c + $harga_pemakaian_d;
		//END PERALATAN & OPERASIONAL
		//END LAPORAN BEBAN POKOK PRODUKSI

		//Opening Balance Pergerakan Bahan Jadi
		$tanggal_awal = date('2020-01-01');
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_abu_batu_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();
		
		$stock_opname_batu0510_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();
		
		$stock_opname_batu0510_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance_abubatu_bulan_lalu = $stock_opname_abu_batu_ago['volume'] + $stock_opname_abu_batu_ago_cat['volume'];
		$volume_opening_balance_batu0510_bulan_lalu = $stock_opname_batu0510_ago['volume'] + $stock_opname_batu0510_ago_cat['volume'];
		$volume_opening_balance_batu1020_bulan_lalu = $stock_opname_batu1020_ago['volume'] + $stock_opname_batu1020_ago_cat['volume'];
		$volume_opening_balance_batu2030_bulan_lalu = $stock_opname_batu2030_ago['volume'] + $stock_opname_batu2030_ago_cat['volume'];

		//Rumus Harga Opening Balance

		//Dua Bulan Lalu
		$tanggal_opening_balance_2 = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		//Satu Bulan Lalu
		$tanggal_opening_balance_3 = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		
		$harga_hpp_2 = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp_2 pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$harga_hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$harga_opening_balance_abubatu_bulan_lalu = $harga_hpp_2['abubatu'] + $harga_hpp['abubatu'];
		$harga_opening_balance_batu0510_bulan_lalu = $harga_hpp_2['batu0510'] + $harga_hpp['batu0510'];
		$harga_opening_balance_batu1020_bulan_lalu = $harga_hpp_2['batu1020'] + $harga_hpp['batu1020'];
		$harga_opening_balance_batu2030_bulan_lalu =  $harga_hpp_2['batu2030'] + $harga_hpp['batu2030'];

		$vol_1 = round($volume_opening_balance_abubatu_bulan_lalu,2);
		$vol_2 = round($volume_opening_balance_batu0510_bulan_lalu,2);
		$vol_3 = round($volume_opening_balance_batu1020_bulan_lalu,2);
		$vol_4 = round($volume_opening_balance_batu2030_bulan_lalu,2);

		$nilai_opening_balance_abubatu_bulan_lalu = $vol_1 * $harga_opening_balance_abubatu_bulan_lalu;
		$nilai_opening_balance_batu0510_bulan_lalu = $vol_2 * $harga_opening_balance_batu0510_bulan_lalu;
		$nilai_opening_balance_batu1020_bulan_lalu = $vol_3 * $harga_opening_balance_batu1020_bulan_lalu;
		$nilai_opening_balance_batu2030_bulan_lalu = $vol_4 * $harga_opening_balance_batu2030_bulan_lalu;
		
		$produksi_harian_bulan_ini = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a as presentase_a, pk.presentase_b as presentase_b, pk.presentase_c as presentase_c, pk.presentase_d as presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$volume_produksi_harian_abubatu_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_a'];
		$volume_produksi_harian_batu0510_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_b'];
		$volume_produksi_harian_batu1020_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_c'];
		$volume_produksi_harian_batu2030_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_d'];

		$harga_produksi_harian_abubatu_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu0510_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu1020_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu2030_bulan_ini = $harga_bpp;

		$nilai_produksi_harian_abubatu_bulan_ini = $volume_produksi_harian_abubatu_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu0510_bulan_ini = $volume_produksi_harian_batu0510_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu1020_bulan_ini = $volume_produksi_harian_batu1020_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu2030_bulan_ini = $volume_produksi_harian_batu2030_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_abubatu_bulan_ini = $volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini;
		$harga_akhir_produksi_harian_abubatu_bulan_ini = ($volume_akhir_produksi_harian_abubatu_bulan_ini!=0)?($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_batu0510_bulan_ini = $volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini;
		$harga_akhir_produksi_harian_batu0510_bulan_ini = ($volume_akhir_produksi_harian_batu0510_bulan_ini!=0)?($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

		$volume_akhir_produksi_harian_batu1020_bulan_ini = $volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini;
		$harga_akhir_produksi_harian_batu1020_bulan_ini = ($volume_akhir_produksi_harian_batu1020_bulan_ini!=0)?($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

		$volume_akhir_produksi_harian_batu2030_bulan_ini = $volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini;
		$harga_akhir_produksi_harian_batu2030_bulan_ini = ($volume_akhir_produksi_harian_batu2030_bulan_ini!=0)?($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;
	
		//Abu Batu
		$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_abubatu_bulan_ini = $penjualan_abubatu_bulan_ini['volume'];
		$harga_penjualan_abubatu_bulan_ini = round($harga_akhir_produksi_harian_abubatu_bulan_ini,0);
		$nilai_penjualan_abubatu_bulan_ini = $volume_penjualan_abubatu_bulan_ini * $harga_penjualan_abubatu_bulan_ini;

		$volume_akhir_penjualan_abubatu_bulan_ini = round($volume_akhir_produksi_harian_abubatu_bulan_ini - $volume_penjualan_abubatu_bulan_ini,2);
		$harga_akhir_penjualan_abubatu_bulan_ini = $harga_penjualan_abubatu_bulan_ini;
		$nilai_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini * $harga_akhir_penjualan_abubatu_bulan_ini;

		//Batu 0,5 - 10
		$penjualan_batu0510_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu0510_bulan_ini = $penjualan_batu0510_bulan_ini['volume'];
		$harga_penjualan_batu0510_bulan_ini = round($harga_akhir_produksi_harian_batu0510_bulan_ini,0);
		$nilai_penjualan_batu0510_bulan_ini = $volume_penjualan_batu0510_bulan_ini * $harga_penjualan_batu0510_bulan_ini;

		$volume_akhir_penjualan_batu0510_bulan_ini = round($volume_akhir_produksi_harian_batu0510_bulan_ini - $volume_penjualan_batu0510_bulan_ini,2);
		$harga_akhir_penjualan_batu0510_bulan_ini =  $harga_penjualan_batu0510_bulan_ini;
		$nilai_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini * $harga_akhir_penjualan_batu0510_bulan_ini;

		//Batu 10 - 20
		$penjualan_batu1020_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu1020_bulan_ini = $penjualan_batu1020_bulan_ini['volume'];
		$harga_penjualan_batu1020_bulan_ini = round($harga_akhir_produksi_harian_batu1020_bulan_ini,0);
		$nilai_penjualan_batu1020_bulan_ini = $volume_penjualan_batu1020_bulan_ini * $harga_penjualan_batu1020_bulan_ini;

		$volume_akhir_penjualan_batu1020_bulan_ini = round($volume_akhir_produksi_harian_batu1020_bulan_ini - $volume_penjualan_batu1020_bulan_ini,2);
		$harga_akhir_penjualan_batu1020_bulan_ini = $harga_penjualan_batu1020_bulan_ini;
		$nilai_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini * $harga_akhir_penjualan_batu1020_bulan_ini;

		//Batu 20 - 30
		$penjualan_batu2030_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu2030_bulan_ini = $penjualan_batu2030_bulan_ini['volume'];
		$harga_penjualan_batu2030_bulan_ini = round($harga_akhir_produksi_harian_batu2030_bulan_ini,0);
		$nilai_penjualan_batu2030_bulan_ini = $volume_penjualan_batu2030_bulan_ini * $harga_penjualan_batu2030_bulan_ini;

		$volume_akhir_penjualan_batu2030_bulan_ini = round($volume_akhir_produksi_harian_batu2030_bulan_ini - $volume_penjualan_batu2030_bulan_ini,2);
		$harga_akhir_penjualan_batu2030_bulan_ini = $harga_penjualan_batu2030_bulan_ini;
		$nilai_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini * $harga_akhir_penjualan_batu2030_bulan_ini;

		
		$agregat_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 24")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini = round($agregat_bulan_ini['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini = round($agregat_bulan_ini['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini = round($agregat_bulan_ini['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini = round($agregat_bulan_ini['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini = $harga_akhir_penjualan_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini = $harga_akhir_penjualan_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini = $harga_akhir_penjualan_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini = $harga_akhir_penjualan_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini = $volume_agregat_abubatu_bulan_ini * $harga_agregat_abubatu_bulan_ini;
		$nilai_agregat_batu0510_bulan_ini = $volume_agregat_batu0510_bulan_ini * $harga_agregat_batu0510_bulan_ini;
		$nilai_agregat_batu1020_bulan_ini = $volume_agregat_batu1020_bulan_ini * $harga_agregat_batu1020_bulan_ini;
		$nilai_agregat_batu2030_bulan_ini = $volume_agregat_batu2030_bulan_ini * $harga_agregat_batu2030_bulan_ini;

		$volume_akhir_agregat_abubatu_bulan_ini = round($volume_akhir_penjualan_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini,2);
		$volume_akhir_agregat_batu0510_bulan_ini = round($volume_akhir_penjualan_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini,2);
		$volume_akhir_agregat_batu1020_bulan_ini = round($volume_akhir_penjualan_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini,2);
		$volume_akhir_agregat_batu2030_bulan_ini = round($volume_akhir_penjualan_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini,2);

		$harga_akhir_agregat_abubatu_bulan_ini = $harga_agregat_abubatu_bulan_ini;
		$harga_akhir_agregat_batu0510_bulan_ini = $harga_agregat_batu0510_bulan_ini;
		$harga_akhir_agregat_batu1020_bulan_ini = $harga_agregat_batu1020_bulan_ini;
		$harga_akhir_agregat_batu2030_bulan_ini = $harga_agregat_batu2030_bulan_ini;

		$nilai_akhir_agregat_abubatu_bulan_ini = $volume_akhir_agregat_abubatu_bulan_ini * $harga_akhir_agregat_abubatu_bulan_ini;
		$nilai_akhir_agregat_batu0510_bulan_ini = $volume_akhir_agregat_batu0510_bulan_ini * $harga_akhir_agregat_batu0510_bulan_ini;
		$nilai_akhir_agregat_batu1020_bulan_ini = $volume_akhir_agregat_batu1020_bulan_ini * $harga_akhir_agregat_batu1020_bulan_ini;
		$nilai_akhir_agregat_batu2030_bulan_ini = $volume_akhir_agregat_batu2030_bulan_ini * $harga_akhir_agregat_batu2030_bulan_ini;

		$agregat_bulan_ini_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 14")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini_2 = $volume_agregat_abubatu_bulan_ini_2 * $harga_agregat_abubatu_bulan_ini_2;
		$nilai_agregat_batu0510_bulan_ini_2 = $volume_agregat_batu0510_bulan_ini_2 * $harga_agregat_batu0510_bulan_ini_2;
		$nilai_agregat_batu1020_bulan_ini_2 = $volume_agregat_batu1020_bulan_ini_2 * $harga_agregat_batu1020_bulan_ini_2;
		$nilai_agregat_batu2030_bulan_ini_2 = $volume_agregat_batu2030_bulan_ini_2 * $harga_agregat_batu2030_bulan_ini_2;

		$stock_opname_abu_batu = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();
		
		$stock_opname_batu0510 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();
		
		$stock_opname_batu0510_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini - $volume_agregat_batu0510_bulan_ini_2;
		$volume_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini_2;
		$volume_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini_2;
		$volume_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini_2;

		//LOSS
		$volume_akhir_abubatu_loss = round($stock_opname_abu_batu['volume'] + $stock_opname_abu_batu_cat['volume'],2);
		$volume_akhir_batu0510_loss = round($stock_opname_batu0510['volume'] + $stock_opname_batu0510_cat['volume'],2);
		$volume_akhir_batu1020_loss = round($stock_opname_batu1020['volume'] + $stock_opname_batu1020_cat['volume'],2);
		$volume_akhir_batu2030_loss = round($stock_opname_batu2030['volume'] + $stock_opname_batu2030_cat['volume'],2);

		$harga_akhir_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini_2;
		$harga_akhir_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini_2;
		$harga_akhir_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini_2;
		$harga_akhir_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini_2;

		$harga_akhir_abubatu_loss = $harga_akhir_agregat_abubatu_bulan_ini_2;
		$harga_akhir_batu0510_loss = $harga_akhir_agregat_batu0510_bulan_ini_2;
		$harga_akhir_batu1020_loss = $harga_akhir_agregat_batu1020_bulan_ini_2;
		$harga_akhir_batu2030_loss =$harga_akhir_agregat_batu2030_bulan_ini_2;

		$volume_akhir_agregat_abubatu_bulan_ini_2_fix = round($volume_akhir_agregat_abubatu_bulan_ini_2,2);
		$volume_akhir_agregat_batu0510_bulan_ini_2_fix = round($volume_akhir_agregat_batu0510_bulan_ini_2,2);
		$volume_akhir_agregat_batu1020_bulan_ini_2_fix = round($volume_akhir_agregat_batu1020_bulan_ini_2,2);
		$volume_akhir_agregat_batu2030_bulan_ini_2_fix = round($volume_akhir_agregat_batu2030_bulan_ini_2,2);

		$nilai_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini_2_fix * $harga_akhir_agregat_abubatu_bulan_ini_2;
		$nilai_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini_2_fix * $harga_akhir_agregat_batu0510_bulan_ini_2;
		$nilai_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini_2_fix * $harga_akhir_agregat_batu1020_bulan_ini_2;
		$nilai_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini_2_fix * $harga_akhir_agregat_batu2030_bulan_ini_2;

		$nilai_akhir_abubatu_loss = $volume_akhir_abubatu_loss * $harga_akhir_abubatu_loss;
		$nilai_akhir_batu0510_loss = $volume_akhir_batu0510_loss * $harga_akhir_batu0510_loss;
		$nilai_akhir_batu1020_loss = $volume_akhir_batu1020_loss * $harga_akhir_batu1020_loss;
		$nilai_akhir_batu2030_loss = $volume_akhir_batu2030_loss * $harga_akhir_batu2030_loss;

		//PRODUKSI LOSS
		$volume_abubatu_loss = round($volume_akhir_agregat_abubatu_bulan_ini_2_fix - $volume_akhir_abubatu_loss,2);
		$volume_batu0510_loss = round($volume_akhir_agregat_batu0510_bulan_ini_2_fix - $volume_akhir_batu0510_loss,2);
		$volume_batu1020_loss = round($volume_akhir_agregat_batu1020_bulan_ini_2_fix - $volume_akhir_batu1020_loss,2);
		$volume_batu2030_loss = round($volume_akhir_agregat_batu2030_bulan_ini_2_fix - $volume_akhir_batu2030_loss,2);

		$harga_abubatu_loss = $harga_akhir_agregat_abubatu_bulan_ini_2;
		$harga_batu0510_loss = $harga_akhir_agregat_batu0510_bulan_ini_2;
		$harga_batu1020_loss = $harga_akhir_agregat_batu1020_bulan_ini_2;
		$harga_batu2030_loss = $harga_akhir_agregat_batu2030_bulan_ini_2;

		$nilai_abubatu_loss = $volume_abubatu_loss * $harga_abubatu_loss;
		$nilai_batu0510_loss = $volume_batu0510_loss * $harga_batu0510_loss;
		$nilai_batu1020_loss = $volume_batu1020_loss * $harga_batu1020_loss;
		$nilai_batu2030_loss = $volume_batu2030_loss * $harga_batu2030_loss;


		//TOTAL BAHAN BAKU
		$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

		$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_abubatu_loss;
		$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 + $nilai_abubatu_loss;
		$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

		$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_batu0510_loss;
		$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_batu0510_loss;
		$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

		$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_batu1020_loss;
		$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_batu1020_loss;
		$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

		$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2 + $volume_batu2030_loss;
		$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2 + $nilai_batu2030_loss;
		$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

		//TOTAL
		$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
		$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

		$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2 + $volume_abubatu_loss + $volume_batu0510_loss + $volume_batu1020_loss + $volume_batu2030_loss;
		$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2 + $nilai_abubatu_loss + $nilai_batu0510_loss + $nilai_batu1020_loss + $nilai_batu2030_loss;
		
		$total_volume_akhir = $volume_akhir_abubatu_loss + $volume_akhir_batu0510_loss + $volume_akhir_batu1020_loss + $volume_akhir_batu2030_loss;
		$total_nilai_akhir = $nilai_akhir_abubatu_loss + $nilai_akhir_batu0510_loss + $nilai_akhir_batu1020_loss + $nilai_akhir_batu2030_loss;
		
		?>

		<tr class="table-active4">
			<th width="30%" class="text-center" rowspan="2" style="vertical-align:middle">TANGGAL</th>
			<th width="20%" class="text-center" rowspan="2" style="vertical-align:middle">URAIAN</th>
			<th width="10%" class="text-center" rowspan="2" style="vertical-align:middle">SATUAN</th>
			<th width="20%" class="text-center" colspan="3">MASUK</th>
			<th width="20%" class="text-center" colspan="3">KELUAR</th>
			<th width="20%" class="text-center" colspan="3">AKHIR</th>
		</tr>
		<tr class="table-active4">
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 0,0 - 0,5</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_abubatu_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_abubatu_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_abubatu_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Factor Loss</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_abubatu_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_abubatu_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_abubatu_loss,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_abubatu_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_abubatu_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_abubatu_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 0,5 - 10</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_batu0510_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_batu0510_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu0510_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Factor Loss</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_batu0510_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_batu0510_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_batu0510_loss,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_batu0510_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_batu0510_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_batu0510_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 10 - 20</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_batu1020_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_batu1020_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu1020_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Factor Loss</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_batu1020_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_batu1020_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_batu1020_loss,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_batu1020_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_batu1020_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_batu1020_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BATU 20 - 30</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"><?php echo number_format($volume_opening_balance_batu2030_bulan_lalu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_opening_balance_batu2030_bulan_lalu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>			
			<th class="text-left"><i>Produksi</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_akhir_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right" style='background-color:red; color:white'><blink><?php echo number_format($harga_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></blink></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_penjualan_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_penjualan_batu2030_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat A)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Penjualan (Agregat B)</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Factor Loss</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center">-</th>
			<th class="text-right">-</th>
			<th class="text-right">-</th>
			<th class="text-center"><?php echo number_format($volume_batu2030_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_batu2030_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_batu2030_loss,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_batu2030_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_batu2030_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_batu2030_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active2">
			<th class="text-center" colspan="12">BAHAN JADI</th>
		</tr>
		<tr class="table-active3">
			<th class="text-center" style="vertical-align:middle"><?php echo $tanggal_opening_balance;?></th>			
			<th class="text-left" colspan="8"><i>Opening Balance</i></th>
			<th class="text-center"></th>
			<th class="text-right"></th>
			<th class="text-right"><?php echo number_format($nilai_opening_bahan_jadi,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 0,0 - 0,5</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_abubatu_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_abubatu_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_abubatu,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_abubatu,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_abubatu_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_abubatu_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_abubatu_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 0,5 - 10</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu0510_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu0510_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu0510,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu0510,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_batu0510_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_batu0510_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_batu0510_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 10 - 20</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu1020_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu1020_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu1020,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu1020,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_batu1020_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_batu1020_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_batu1020_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class="text-center"style="vertical-align:middle"><?php echo $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
			<th class="text-left"><i>Batu 20 - 30</i></th>
			<th class="text-center">Ton</th>
			<th class="text-center"><?php echo number_format($volume_produksi_harian_batu2030_bulan_ini,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_produksi_harian_batu2030_bulan_ini,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_penjualan_batu2030,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_penjualan_batu2030,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($volume_akhir_batu2030_loss,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_batu2030_loss,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_batu2030_loss,0,',','.');?></th>
		</tr>
		<tr class="table-active5">
			<th class="text-center" colspan="3">TOTAL</th>
			<th class="text-center"><?php echo number_format($total_volume_masuk,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right"><?php echo number_format($total_nilai_masuk,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($total_volume_keluar,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
			<th class="text-center"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
		</tr>
	    </table>
		
		<?php
	}

	public function nilai_persediaan_bahan_jadi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		
		$last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
		$last_production_2 = $this->db->select('date')->order_by('date','desc')->limit(1,1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','material_id'=>'4'))->row_array();

		$date1 = date('Y-m-d', strtotime('+1 days', strtotime($last_production_2['date'])));
		$date2 = date('Y-m-d', strtotime($last_production['date']));
		$date1_filter = date('d F Y', strtotime($date1));
		$date2_filter = date('d F Y', strtotime($date2));

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
			$date1_filter = date('d F Y',strtotime($arr_filter_date[0]));
			$date2_filter = date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		<style type="text/css">
		table tr.table-active{
			background-color: #F0F0F0;
			font-size: 12px;
			font-weight: bold;
			color: black;
		}
			
		table tr.table-active2{
			background-color: #E8E8E8;
			font-size: 12px;
			font-weight: bold;
		}
			
		table tr.table-active3{
			font-size: 12px;
			background-color: #F0F0F0;
		}
			
		table tr.table-active4{
			background-color: #e69500;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-active5{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-activeago1{
			background-color: #ffd966;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		table tr.table-activeopening{
			background-color: #2986cc;
			font-weight: bold;
			font-size: 12px;
			color: black;
		}
		</style>

		<?php

		//PERGERAKAN BAHAN BAKU

		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));

		$pergerakan_bahan_baku_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_ago = $pergerakan_bahan_baku_ago['volume'];
		$total_volume_pembelian_akhir_ago  = $total_volume_pembelian_ago;

		$produksi_harian_ago = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1_ago' and '$date2_ago')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_volume_produksi_ago = $produksi_harian_ago['used'];
		$total_volume_produksi_akhir_ago = $total_volume_pembelian_akhir_ago - $total_volume_produksi_ago;

		$harga_satuan_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date3_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$nilai_harga_satuan_ago = ($harga_satuan_ago['volume']!=0)?($harga_satuan_ago['nilai'] / $harga_satuan_ago['volume'])  * 1:0;

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->order_by('pp.date_hpp','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir_ago_fix = round($total_volume_produksi_akhir_ago,2);

		$volume_opening_balance = $total_volume_produksi_akhir_ago_fix;
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $total_volume_produksi_akhir_ago_fix * $harga_opening_balance;

		$pergerakan_bahan_baku_ago_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$volume_pergerakan_bahan_baku_ago_solar = $pergerakan_bahan_baku_ago_solar['volume'];

		$stock_opname_solar_ago = $this->db->select('`prm`.`volume` as volume, `prm`.`total` as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("(prm.date < '$date1')")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar_ago = $stock_opname_solar_ago['volume'];

		$volume_opening_balance_solar = $volume_stock_opname_solar_ago;
		$volume_opening_balance_solar_fix = round($volume_opening_balance_solar,2);

		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar_fix * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = $total_volume_produksi_akhir_ago + $total_volume_pembelian;
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			

		$produksi_harian = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
		->from('akumulasi_bahan_baku pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->result_array();

		$total_akumulasi_bahan_baku = 0;
		$total_akumulasi_bahan_baku_2 = 0;

		foreach ($akumulasi_bahan_baku as $b){
			$total_akumulasi_bahan_baku += $b['total_nilai_keluar'];
			$total_akumulasi_bahan_baku_2 += $b['total_nilai_keluar_2'];
		}

		$akumulasi_nilai_bahan_baku = $total_akumulasi_bahan_baku;
		$akumulasi_nilai_bahan_baku_2 = $total_akumulasi_bahan_baku_2;

		$total_volume_produksi = $produksi_harian['used'];
		$total_nilai_produksi = $akumulasi_nilai_bahan_baku;
		$total_harga_produksi = ($total_volume_produksi!=0)?($total_nilai_produksi / $total_volume_produksi)  * 1:0;

		$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
		$total_harga_produksi_akhir = $total_harga_produksi;
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		//BBM Solar
		$pergerakan_bahan_baku_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(prm.volume) as volume, (prm.total) as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("prm.date between '$date1' and '$date2'")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar = $stock_opname_solar['volume'];

		$total_volume_produksi_akhir_solar = $volume_stock_opname_solar;
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = $total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $akumulasi_nilai_bahan_baku_2;
		$total_harga_produksi_solar = ($total_volume_produksi_solar!=0)?($total_nilai_produksi_solar / $total_volume_produksi_solar)  * 1:0;
		//END PERGERAKAN BAHAN BAKU

		//PERALATAN & OPERASIONAL
		$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_abu_batu = 0;
		$nilai_abu_batu1 = 0;
		$nilai_abu_batu2 = 0;
		$nilai_abu_batu3 = 0;
		$nilai_abu_batu4 = 0;
		$nilai_abu_batu5 = 0;
		$nilai_abu_batu_all = 0;

		$total_abu_batu = $abu_batu['jumlah_pemakaian_a'] + $abu_batu['jumlah_pemakaian_b'] + $abu_batu['jumlah_pemakaian_c'] + $abu_batu['jumlah_pemakaian_d'] + $abu_batu['jumlah_pemakaian_e'];
		$nilai_abu_batu1 = $abu_batu['jumlah_pemakaian_a'] * $total_harga_produksi_akhir;
		$nilai_abu_batu2 = $abu_batu['jumlah_pemakaian_b'] * $total_harga_produksi_akhir;
		$nilai_abu_batu3 = $abu_batu['jumlah_pemakaian_c'] * $total_harga_produksi_akhir;
		$nilai_abu_batu4 = $abu_batu['jumlah_pemakaian_d'] * $total_harga_produksi_akhir;
		$nilai_abu_batu5 = $abu_batu['jumlah_pemakaian_e'] * $total_harga_produksi_akhir;
		$nilai_abu_batu_all = $nilai_abu_batu1 + $nilai_abu_batu2 + $nilai_abu_batu3 + $nilai_abu_batu4 + $nilai_abu_batu5;

		$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;

		$stone_crusher_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher = $stone_crusher_biaya['total'] + $stone_crusher_jurnal['total'];

		$whell_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader = $whell_loader_biaya['total'] + $whell_loader_jurnal['total'];

		$excavator = $this->db->select('sum(prm.display_price) as price')
		->from('pmm_receipt_material prm ')
		->where("prm.material_id = 18")
		->where("(prm.date_receipt between '$date1' and '$date2')")
		->get()->row_array();

		$genset_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset = $genset_biaya['total'] + $genset_jurnal['total'];

		$timbangan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan_biaya_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan = $timbangan_biaya['total'] + $timbangan_biaya_jurnal['total'];

		$tangki_solar_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar = $tangki_solar_biaya['total'] + $tangki_solar_jurnal['total'];		

		$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar;
		$hpp_peralatan = ($total_abu_batu!=0)?($total_biaya_peralatan / $total_abu_batu)  * 1:0;

		$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];

		$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];

		$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];

		$perbaikan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan = $perbaikan_biaya['total'] + $perbaikan_jurnal['total'];

		$akomodasi_tamu_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu = $akomodasi_tamu_biaya['total'] + $akomodasi_tamu_jurnal['total'];

		$pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian = $pengujian_biaya['total'] + $pengujian_jurnal['total'];

		$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

		$total_operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;
		$hpp_operasional = ($total_abu_batu!=0)?($total_operasional / $total_abu_batu)  * 1:0;
		$total_bpp = $total_nilai_produksi + $total_nilai_produksi_solar + $total_biaya_peralatan + $total_operasional;
		$harga_bpp = ($total_abu_batu!=0)?($total_bpp / $total_abu_batu)  * 1:0;

		$harga_pemakaian_a = 0;
		$harga_pemakaian_b = 0;
		$harga_pemakaian_c = 0;
		$harga_pemakaian_d = 0;
		$total_harga_pemakaian = 0;

		$harga_pemakaian_a = $harga_bpp * $abu_batu['jumlah_pemakaian_a'];
		$harga_pemakaian_b = $harga_bpp * $abu_batu['jumlah_pemakaian_b'];
		$harga_pemakaian_c = $harga_bpp * $abu_batu['jumlah_pemakaian_c'];
		$harga_pemakaian_d = $harga_bpp * $abu_batu['jumlah_pemakaian_d'];

		$total_harga_pemakaian = $harga_pemakaian_a + $harga_pemakaian_b + $harga_pemakaian_c + $harga_pemakaian_d;
		//END PERALATAN & OPERASIONAL
		//END LAPORAN BEBAN POKOK PRODUKSI

		//Opening Balance Pergerakan Bahan Jadi
		$tanggal_awal = date('2020-01-01');
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_abu_batu_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance_abubatu_bulan_lalu = $stock_opname_abu_batu_ago['volume'] + $stock_opname_abu_batu_ago_cat['volume'];
		$volume_opening_balance_batu0510_bulan_lalu = $stock_opname_batu0510_ago['volume'] + $stock_opname_batu0510_ago_cat['volume'];
		$volume_opening_balance_batu1020_bulan_lalu = $stock_opname_batu1020_ago['volume'] + $stock_opname_batu1020_ago_cat['volume'];
		$volume_opening_balance_batu2030_bulan_lalu = $stock_opname_batu2030_ago['volume'] + $stock_opname_batu2030_ago_cat['volume'];

		//Rumus Harga Opening Balance

		//Dua Bulan Lalu
		$tanggal_opening_balance_2 = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		//Satu Bulan Lalu
		$tanggal_opening_balance_3 = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$harga_hpp_2 = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp_2 pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$harga_hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$harga_opening_balance_abubatu_bulan_lalu = $harga_hpp_2['abubatu'] + $harga_hpp['abubatu'];
		$harga_opening_balance_batu0510_bulan_lalu = $harga_hpp_2['batu0510'] + $harga_hpp['batu0510'];
		$harga_opening_balance_batu1020_bulan_lalu = $harga_hpp_2['batu1020'] + $harga_hpp['batu1020'];
		$harga_opening_balance_batu2030_bulan_lalu =  $harga_hpp_2['batu2030'] + $harga_hpp['batu2030'];

		$vol_1 = round($volume_opening_balance_abubatu_bulan_lalu,2);
		$vol_2 = round($volume_opening_balance_batu0510_bulan_lalu,2);
		$vol_3 = round($volume_opening_balance_batu1020_bulan_lalu,2);
		$vol_4 = round($volume_opening_balance_batu2030_bulan_lalu,2);

		$nilai_opening_balance_abubatu_bulan_lalu = $vol_1 * $harga_opening_balance_abubatu_bulan_lalu;
		$nilai_opening_balance_batu0510_bulan_lalu = $vol_2 * $harga_opening_balance_batu0510_bulan_lalu;
		$nilai_opening_balance_batu1020_bulan_lalu = $vol_3 * $harga_opening_balance_batu1020_bulan_lalu;
		$nilai_opening_balance_batu2030_bulan_lalu = $vol_4 * $harga_opening_balance_batu2030_bulan_lalu;

		$produksi_harian_bulan_ini = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a as presentase_a, pk.presentase_b as presentase_b, pk.presentase_c as presentase_c, pk.presentase_d as presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$volume_produksi_harian_abubatu_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_a'];
		$volume_produksi_harian_batu0510_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_b'];
		$volume_produksi_harian_batu1020_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_c'];
		$volume_produksi_harian_batu2030_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_d'];

		$harga_produksi_harian_abubatu_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu0510_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu1020_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu2030_bulan_ini = $harga_bpp;

		$nilai_produksi_harian_abubatu_bulan_ini = $volume_produksi_harian_abubatu_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu0510_bulan_ini = $volume_produksi_harian_batu0510_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu1020_bulan_ini = $volume_produksi_harian_batu1020_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu2030_bulan_ini = $volume_produksi_harian_batu2030_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_abubatu_bulan_ini = $volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini;
		$harga_akhir_produksi_harian_abubatu_bulan_ini = ($volume_akhir_produksi_harian_abubatu_bulan_ini!=0)?($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_batu0510_bulan_ini = $volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini;
		$harga_akhir_produksi_harian_batu0510_bulan_ini = ($volume_akhir_produksi_harian_batu0510_bulan_ini!=0)?($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

		$volume_akhir_produksi_harian_batu1020_bulan_ini = $volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini;
		$harga_akhir_produksi_harian_batu1020_bulan_ini = ($volume_akhir_produksi_harian_batu1020_bulan_ini!=0)?($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

		$volume_akhir_produksi_harian_batu2030_bulan_ini = $volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini;
		$harga_akhir_produksi_harian_batu2030_bulan_ini = ($volume_akhir_produksi_harian_batu2030_bulan_ini!=0)?($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;

		//Abu Batu
		$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_abubatu_bulan_ini = $penjualan_abubatu_bulan_ini['volume'];
		$harga_penjualan_abubatu_bulan_ini = round($harga_akhir_produksi_harian_abubatu_bulan_ini,0);
		$nilai_penjualan_abubatu_bulan_ini = $volume_penjualan_abubatu_bulan_ini * $harga_penjualan_abubatu_bulan_ini;

		$volume_akhir_penjualan_abubatu_bulan_ini = round($volume_akhir_produksi_harian_abubatu_bulan_ini - $volume_penjualan_abubatu_bulan_ini,2);
		$harga_akhir_penjualan_abubatu_bulan_ini = $harga_penjualan_abubatu_bulan_ini;
		$nilai_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini * $harga_akhir_penjualan_abubatu_bulan_ini;

		//Batu 0,5 - 10
		$penjualan_batu0510_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu0510_bulan_ini = $penjualan_batu0510_bulan_ini['volume'];
		$harga_penjualan_batu0510_bulan_ini = round($harga_akhir_produksi_harian_batu0510_bulan_ini,0);
		$nilai_penjualan_batu0510_bulan_ini = $volume_penjualan_batu0510_bulan_ini * $harga_penjualan_batu0510_bulan_ini;

		$volume_akhir_penjualan_batu0510_bulan_ini = round($volume_akhir_produksi_harian_batu0510_bulan_ini - $volume_penjualan_batu0510_bulan_ini,2);
		$harga_akhir_penjualan_batu0510_bulan_ini =  $harga_penjualan_batu0510_bulan_ini;
		$nilai_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini * $harga_akhir_penjualan_batu0510_bulan_ini;

		//Batu 10 - 20
		$penjualan_batu1020_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu1020_bulan_ini = $penjualan_batu1020_bulan_ini['volume'];
		$harga_penjualan_batu1020_bulan_ini = round($harga_akhir_produksi_harian_batu1020_bulan_ini,0);
		$nilai_penjualan_batu1020_bulan_ini = $volume_penjualan_batu1020_bulan_ini * $harga_penjualan_batu1020_bulan_ini;

		$volume_akhir_penjualan_batu1020_bulan_ini = round($volume_akhir_produksi_harian_batu1020_bulan_ini - $volume_penjualan_batu1020_bulan_ini,2);
		$harga_akhir_penjualan_batu1020_bulan_ini = $harga_penjualan_batu1020_bulan_ini;
		$nilai_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini * $harga_akhir_penjualan_batu1020_bulan_ini;

		//Batu 20 - 30
		$penjualan_batu2030_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu2030_bulan_ini = $penjualan_batu2030_bulan_ini['volume'];
		$harga_penjualan_batu2030_bulan_ini = round($harga_akhir_produksi_harian_batu2030_bulan_ini,0);
		$nilai_penjualan_batu2030_bulan_ini = $volume_penjualan_batu2030_bulan_ini * $harga_penjualan_batu2030_bulan_ini;

		$volume_akhir_penjualan_batu2030_bulan_ini = round($volume_akhir_produksi_harian_batu2030_bulan_ini - $volume_penjualan_batu2030_bulan_ini,2);
		$harga_akhir_penjualan_batu2030_bulan_ini = $harga_penjualan_batu2030_bulan_ini;
		$nilai_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini * $harga_akhir_penjualan_batu2030_bulan_ini;


		$agregat_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 24")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini = round($agregat_bulan_ini['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini = round($agregat_bulan_ini['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini = round($agregat_bulan_ini['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini = round($agregat_bulan_ini['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini = $harga_akhir_penjualan_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini = $harga_akhir_penjualan_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini = $harga_akhir_penjualan_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini = $harga_akhir_penjualan_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini = $volume_agregat_abubatu_bulan_ini * $harga_agregat_abubatu_bulan_ini;
		$nilai_agregat_batu0510_bulan_ini = $volume_agregat_batu0510_bulan_ini * $harga_agregat_batu0510_bulan_ini;
		$nilai_agregat_batu1020_bulan_ini = $volume_agregat_batu1020_bulan_ini * $harga_agregat_batu1020_bulan_ini;
		$nilai_agregat_batu2030_bulan_ini = $volume_agregat_batu2030_bulan_ini * $harga_agregat_batu2030_bulan_ini;

		$volume_akhir_agregat_abubatu_bulan_ini = round($volume_akhir_penjualan_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini,2);
		$volume_akhir_agregat_batu0510_bulan_ini = round($volume_akhir_penjualan_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini,2);
		$volume_akhir_agregat_batu1020_bulan_ini = round($volume_akhir_penjualan_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini,2);
		$volume_akhir_agregat_batu2030_bulan_ini = round($volume_akhir_penjualan_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini,2);

		$harga_akhir_agregat_abubatu_bulan_ini = $harga_agregat_abubatu_bulan_ini;
		$harga_akhir_agregat_batu0510_bulan_ini = $harga_agregat_batu0510_bulan_ini;
		$harga_akhir_agregat_batu1020_bulan_ini = $harga_agregat_batu1020_bulan_ini;
		$harga_akhir_agregat_batu2030_bulan_ini = $harga_agregat_batu2030_bulan_ini;

		$nilai_akhir_agregat_abubatu_bulan_ini = $volume_akhir_agregat_abubatu_bulan_ini * $harga_akhir_agregat_abubatu_bulan_ini;
		$nilai_akhir_agregat_batu0510_bulan_ini = $volume_akhir_agregat_batu0510_bulan_ini * $harga_akhir_agregat_batu0510_bulan_ini;
		$nilai_akhir_agregat_batu1020_bulan_ini = $volume_akhir_agregat_batu1020_bulan_ini * $harga_akhir_agregat_batu1020_bulan_ini;
		$nilai_akhir_agregat_batu2030_bulan_ini = $volume_akhir_agregat_batu2030_bulan_ini * $harga_akhir_agregat_batu2030_bulan_ini;

		$agregat_bulan_ini_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 14")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini_2 = $volume_agregat_abubatu_bulan_ini_2 * $harga_agregat_abubatu_bulan_ini_2;
		$nilai_agregat_batu0510_bulan_ini_2 = $volume_agregat_batu0510_bulan_ini_2 * $harga_agregat_batu0510_bulan_ini_2;
		$nilai_agregat_batu1020_bulan_ini_2 = $volume_agregat_batu1020_bulan_ini_2 * $harga_agregat_batu1020_bulan_ini_2;
		$nilai_agregat_batu2030_bulan_ini_2 = $volume_agregat_batu2030_bulan_ini_2 * $harga_agregat_batu2030_bulan_ini_2;

		$stock_opname_abu_batu = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_akhir_agregat_abubatu_bulan_ini_2 = $stock_opname_abu_batu['volume'] + $stock_opname_abu_batu_cat['volume'];
		$volume_akhir_agregat_batu0510_bulan_ini_2 = $stock_opname_batu0510['volume'] + $stock_opname_batu0510_cat['volume'];
		$volume_akhir_agregat_batu1020_bulan_ini_2 = $stock_opname_batu1020['volume'] + $stock_opname_batu1020_cat['volume'];
		$volume_akhir_agregat_batu2030_bulan_ini_2 = $stock_opname_batu2030['volume'] + $stock_opname_batu2030_cat['volume'];

		$harga_akhir_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini_2;
		$harga_akhir_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini_2;
		$harga_akhir_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini_2;
		$harga_akhir_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini_2;

		$volume_akhir_agregat_abubatu_bulan_ini_2_fix = round($volume_akhir_agregat_abubatu_bulan_ini_2,2);
		$volume_akhir_agregat_batu0510_bulan_ini_2_fix = round($volume_akhir_agregat_batu0510_bulan_ini_2,2);
		$volume_akhir_agregat_batu1020_bulan_ini_2_fix = round($volume_akhir_agregat_batu1020_bulan_ini_2,2);
		$volume_akhir_agregat_batu2030_bulan_ini_2_fix = round($volume_akhir_agregat_batu2030_bulan_ini_2,2);

		$nilai_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini_2_fix * $harga_akhir_agregat_abubatu_bulan_ini_2;
		$nilai_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini_2_fix * $harga_akhir_agregat_batu0510_bulan_ini_2;
		$nilai_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini_2_fix * $harga_akhir_agregat_batu1020_bulan_ini_2;
		$nilai_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini_2_fix * $harga_akhir_agregat_batu2030_bulan_ini_2;

		//TOTAL BAHAN BAKU
		$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

		$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
		$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
		$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

		$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
		$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
		$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

		$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2;
		$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
		$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

		$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
		$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

		//TOTAL
		$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
		$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

		$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;

		$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
		$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;

		?>

		<tr class="table-active2">
			<th colspan="3">Periode</th>
			<th class="text-center" colspan="9"><?php echo $filter_date;?></th>
		</tr>
		<tr class="table-active4">
			<th width="5%" class="text-center">NO.</th>
			<th class="text-center">URAIAN</th>
			<th class="text-center">SATUAN</th>
			<th class="text-center">STOK BARANG</th>
			<th class="text-center">HARGA SATUAN</th>
			<th class="text-center">NILAI</th>
		</tr>
		<?php
			$styleColorA = $volume_akhir_agregat_abubatu_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorB = $nilai_akhir_agregat_abubatu_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorC = $volume_akhir_agregat_batu0510_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorD = $nilai_akhir_agregat_batu0510_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorE = $volume_akhir_agregat_batu1020_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorF = $nilai_akhir_agregat_batu1020_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorG = $volume_akhir_agregat_batu2030_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorH = $nilai_akhir_agregat_batu2030_bulan_ini_2 < 0 ? 'background-color:red' : 'background-color:none';

		
			$styleColorAA = $total_volume_akhir < 0 ? 'background-color:red' : 'background-color:none';
			$styleColorBB = $total_nilai_akhir < 0 ? 'background-color:red' : 'background-color:none';
		?>
		<tr class="table-active3">
			<th class="text-center">1.</th>
			<th class="text-left">Batu Split 0,0 - 0,5</th>
			<th class="text-center">Ton</th>
			<th class="text-right" style="<?php echo $styleColorA ?>"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-right" style="<?php echo $styleColorB ?>"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center">2.</th>
			<th class="text-left">Batu Split 0,5 - 10</th>
			<th class="text-center">Ton</th>
			<th class="text-right" style="<?php echo $styleColorC ?>"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-right" style="<?php echo $styleColorD ?>"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center">3.</th>
			<th class="text-left">Batu Split 10 - 20</th>
			<th class="text-center">Ton</th>
			<th class="text-right" style="<?php echo $styleColorE ?>"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-right" style="<?php echo $styleColorF ?>"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class="text-center">4.</th>
			<th class="text-left">Batu Split 20 - 30</th>
			<th class="text-center">Ton</th>
			<th class="text-right" style="<?php echo $styleColorG ?>"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-right" style="<?php echo $styleColorH ?>"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr class="table-active5">
			<th class="text-center" colspan="3">TOTAL</th>
			<th class="text-right" style="<?php echo $styleColorAA ?>"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right" style="<?php echo $styleColorBB ?>"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
		</tr>
	    </table>
		
		<?php
	}

	public function nilai_persediaan_bahan_jadi_dashboard($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		
		$last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
		$last_production_2 = $this->db->select('date')->order_by('date','desc')->limit(1,1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','material_id'=>'4'))->row_array();

		$date1 = date('Y-m-d', strtotime('+1 days', strtotime($last_production_2['date'])));
		$date2 = date('Y-m-d', strtotime($last_production['date']));
		$date1_filter = date('d F Y', strtotime($date1));
		$date2_filter = date('d F Y', strtotime($date2));

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
			$date1_filter = date('d F Y',strtotime($arr_filter_date[0]));
			$date2_filter = date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">

		<?php

		//PERGERAKAN BAHAN BAKU

		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));

		$pergerakan_bahan_baku_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_ago = $pergerakan_bahan_baku_ago['volume'];
		$total_volume_pembelian_akhir_ago  = $total_volume_pembelian_ago;

		$produksi_harian_ago = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1_ago' and '$date2_ago')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_volume_produksi_ago = $produksi_harian_ago['used'];
		$total_volume_produksi_akhir_ago = $total_volume_pembelian_akhir_ago - $total_volume_produksi_ago;

		$harga_satuan_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date3_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$nilai_harga_satuan_ago = ($harga_satuan_ago['volume']!=0)?($harga_satuan_ago['nilai'] / $harga_satuan_ago['volume'])  * 1:0;

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->order_by('pp.date_hpp','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir_ago_fix = round($total_volume_produksi_akhir_ago,2);

		$volume_opening_balance = $total_volume_produksi_akhir_ago_fix;
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $total_volume_produksi_akhir_ago_fix * $harga_opening_balance;

		$pergerakan_bahan_baku_ago_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$volume_pergerakan_bahan_baku_ago_solar = $pergerakan_bahan_baku_ago_solar['volume'];

		$stock_opname_solar_ago = $this->db->select('`prm`.`volume` as volume, `prm`.`total` as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("(prm.date < '$date1')")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar_ago = $stock_opname_solar_ago['volume'];

		$volume_opening_balance_solar = $volume_stock_opname_solar_ago;
		$volume_opening_balance_solar_fix = round($volume_opening_balance_solar,2);

		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar_fix * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = $total_volume_produksi_akhir_ago + $total_volume_pembelian;
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			

		$produksi_harian = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
		->from('akumulasi_bahan_baku pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->result_array();

		$total_akumulasi_bahan_baku = 0;
		$total_akumulasi_bahan_baku_2 = 0;

		foreach ($akumulasi_bahan_baku as $b){
			$total_akumulasi_bahan_baku += $b['total_nilai_keluar'];
			$total_akumulasi_bahan_baku_2 += $b['total_nilai_keluar_2'];
		}

		$akumulasi_nilai_bahan_baku = $total_akumulasi_bahan_baku;
		$akumulasi_nilai_bahan_baku_2 = $total_akumulasi_bahan_baku_2;

		$total_volume_produksi = $produksi_harian['used'];
		$total_nilai_produksi = $akumulasi_nilai_bahan_baku;
		$total_harga_produksi = ($total_volume_produksi!=0)?($total_nilai_produksi / $total_volume_produksi)  * 1:0;

		$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
		$total_harga_produksi_akhir = $total_harga_produksi;
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		//BBM Solar
		$pergerakan_bahan_baku_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(prm.volume) as volume, (prm.total) as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("prm.date between '$date1' and '$date2'")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar = $stock_opname_solar['volume'];

		$total_volume_produksi_akhir_solar = $volume_stock_opname_solar;
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = $total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $akumulasi_nilai_bahan_baku_2;
		$total_harga_produksi_solar = ($total_volume_produksi_solar!=0)?($total_nilai_produksi_solar / $total_volume_produksi_solar)  * 1:0;
		//END PERGERAKAN BAHAN BAKU

		//PERALATAN & OPERASIONAL
		$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_abu_batu = 0;
		$nilai_abu_batu1 = 0;
		$nilai_abu_batu2 = 0;
		$nilai_abu_batu3 = 0;
		$nilai_abu_batu4 = 0;
		$nilai_abu_batu5 = 0;
		$nilai_abu_batu_all = 0;

		$total_abu_batu = $abu_batu['jumlah_pemakaian_a'] + $abu_batu['jumlah_pemakaian_b'] + $abu_batu['jumlah_pemakaian_c'] + $abu_batu['jumlah_pemakaian_d'] + $abu_batu['jumlah_pemakaian_e'];
		$nilai_abu_batu1 = $abu_batu['jumlah_pemakaian_a'] * $total_harga_produksi_akhir;
		$nilai_abu_batu2 = $abu_batu['jumlah_pemakaian_b'] * $total_harga_produksi_akhir;
		$nilai_abu_batu3 = $abu_batu['jumlah_pemakaian_c'] * $total_harga_produksi_akhir;
		$nilai_abu_batu4 = $abu_batu['jumlah_pemakaian_d'] * $total_harga_produksi_akhir;
		$nilai_abu_batu5 = $abu_batu['jumlah_pemakaian_e'] * $total_harga_produksi_akhir;
		$nilai_abu_batu_all = $nilai_abu_batu1 + $nilai_abu_batu2 + $nilai_abu_batu3 + $nilai_abu_batu4 + $nilai_abu_batu5;

		$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;

		$stone_crusher_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher = $stone_crusher_biaya['total'] + $stone_crusher_jurnal['total'];

		$whell_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader = $whell_loader_biaya['total'] + $whell_loader_jurnal['total'];

		$excavator = $this->db->select('sum(prm.display_price) as price')
		->from('pmm_receipt_material prm ')
		->where("prm.material_id = 18")
		->where("(prm.date_receipt between '$date1' and '$date2')")
		->get()->row_array();

		$genset_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset = $genset_biaya['total'] + $genset_jurnal['total'];

		$timbangan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan_biaya_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan = $timbangan_biaya['total'] + $timbangan_biaya_jurnal['total'];

		$tangki_solar_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar = $tangki_solar_biaya['total'] + $tangki_solar_jurnal['total'];		

		$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar;
		$hpp_peralatan = ($total_abu_batu!=0)?($total_biaya_peralatan / $total_abu_batu)  * 1:0;

		$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];

		$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];

		$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];

		$perbaikan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan = $perbaikan_biaya['total'] + $perbaikan_jurnal['total'];

		$akomodasi_tamu_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu = $akomodasi_tamu_biaya['total'] + $akomodasi_tamu_jurnal['total'];

		$pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian = $pengujian_biaya['total'] + $pengujian_jurnal['total'];

		$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

		$total_operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;
		$hpp_operasional = ($total_abu_batu!=0)?($total_operasional / $total_abu_batu)  * 1:0;
		$total_bpp = $total_nilai_produksi + $total_nilai_produksi_solar + $total_biaya_peralatan + $total_operasional;
		$harga_bpp = ($total_abu_batu!=0)?($total_bpp / $total_abu_batu)  * 1:0;

		$harga_pemakaian_a = 0;
		$harga_pemakaian_b = 0;
		$harga_pemakaian_c = 0;
		$harga_pemakaian_d = 0;
		$total_harga_pemakaian = 0;

		$harga_pemakaian_a = $harga_bpp * $abu_batu['jumlah_pemakaian_a'];
		$harga_pemakaian_b = $harga_bpp * $abu_batu['jumlah_pemakaian_b'];
		$harga_pemakaian_c = $harga_bpp * $abu_batu['jumlah_pemakaian_c'];
		$harga_pemakaian_d = $harga_bpp * $abu_batu['jumlah_pemakaian_d'];

		$total_harga_pemakaian = $harga_pemakaian_a + $harga_pemakaian_b + $harga_pemakaian_c + $harga_pemakaian_d;
		//END PERALATAN & OPERASIONAL
		//END LAPORAN BEBAN POKOK PRODUKSI

		//Opening Balance Pergerakan Bahan Jadi
		$tanggal_awal = date('2020-01-01');
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$stock_opname_abu_batu_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_opening_balance_abubatu_bulan_lalu = $stock_opname_abu_batu_ago['volume'] + $stock_opname_abu_batu_ago_cat['volume'];
		$volume_opening_balance_batu0510_bulan_lalu = $stock_opname_batu0510_ago['volume'] + $stock_opname_batu0510_ago_cat['volume'];
		$volume_opening_balance_batu1020_bulan_lalu = $stock_opname_batu1020_ago['volume'] + $stock_opname_batu1020_ago_cat['volume'];
		$volume_opening_balance_batu2030_bulan_lalu = $stock_opname_batu2030_ago['volume'] + $stock_opname_batu2030_ago_cat['volume'];

		//Rumus Harga Opening Balance

		//Dua Bulan Lalu
		$tanggal_opening_balance_2 = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		//Satu Bulan Lalu
		$tanggal_opening_balance_3 = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$harga_hpp_2 = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp_2 pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$harga_hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$harga_opening_balance_abubatu_bulan_lalu = $harga_hpp_2['abubatu'] + $harga_hpp['abubatu'];
		$harga_opening_balance_batu0510_bulan_lalu = $harga_hpp_2['batu0510'] + $harga_hpp['batu0510'];
		$harga_opening_balance_batu1020_bulan_lalu = $harga_hpp_2['batu1020'] + $harga_hpp['batu1020'];
		$harga_opening_balance_batu2030_bulan_lalu =  $harga_hpp_2['batu2030'] + $harga_hpp['batu2030'];

		$vol_1 = round($volume_opening_balance_abubatu_bulan_lalu,2);
		$vol_2 = round($volume_opening_balance_batu0510_bulan_lalu,2);
		$vol_3 = round($volume_opening_balance_batu1020_bulan_lalu,2);
		$vol_4 = round($volume_opening_balance_batu2030_bulan_lalu,2);

		$nilai_opening_balance_abubatu_bulan_lalu = $vol_1 * $harga_opening_balance_abubatu_bulan_lalu;
		$nilai_opening_balance_batu0510_bulan_lalu = $vol_2 * $harga_opening_balance_batu0510_bulan_lalu;
		$nilai_opening_balance_batu1020_bulan_lalu = $vol_3 * $harga_opening_balance_batu1020_bulan_lalu;
		$nilai_opening_balance_batu2030_bulan_lalu = $vol_4 * $harga_opening_balance_batu2030_bulan_lalu;

		$produksi_harian_bulan_ini = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a as presentase_a, pk.presentase_b as presentase_b, pk.presentase_c as presentase_c, pk.presentase_d as presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$volume_produksi_harian_abubatu_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_a'];
		$volume_produksi_harian_batu0510_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_b'];
		$volume_produksi_harian_batu1020_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_c'];
		$volume_produksi_harian_batu2030_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_d'];

		$harga_produksi_harian_abubatu_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu0510_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu1020_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu2030_bulan_ini = $harga_bpp;

		$nilai_produksi_harian_abubatu_bulan_ini = $volume_produksi_harian_abubatu_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu0510_bulan_ini = $volume_produksi_harian_batu0510_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu1020_bulan_ini = $volume_produksi_harian_batu1020_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu2030_bulan_ini = $volume_produksi_harian_batu2030_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_abubatu_bulan_ini = $volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini;
		$harga_akhir_produksi_harian_abubatu_bulan_ini = ($volume_akhir_produksi_harian_abubatu_bulan_ini!=0)?($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_batu0510_bulan_ini = $volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini;
		$harga_akhir_produksi_harian_batu0510_bulan_ini = ($volume_akhir_produksi_harian_batu0510_bulan_ini!=0)?($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

		$volume_akhir_produksi_harian_batu1020_bulan_ini = $volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini;
		$harga_akhir_produksi_harian_batu1020_bulan_ini = ($volume_akhir_produksi_harian_batu1020_bulan_ini!=0)?($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

		$volume_akhir_produksi_harian_batu2030_bulan_ini = $volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini;
		$harga_akhir_produksi_harian_batu2030_bulan_ini = ($volume_akhir_produksi_harian_batu2030_bulan_ini!=0)?($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini * 1:0;
		$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;

		//Abu Batu
		$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_abubatu_bulan_ini = $penjualan_abubatu_bulan_ini['volume'];
		$harga_penjualan_abubatu_bulan_ini = round($harga_akhir_produksi_harian_abubatu_bulan_ini,0);
		$nilai_penjualan_abubatu_bulan_ini = $volume_penjualan_abubatu_bulan_ini * $harga_penjualan_abubatu_bulan_ini;

		$volume_akhir_penjualan_abubatu_bulan_ini = round($volume_akhir_produksi_harian_abubatu_bulan_ini - $volume_penjualan_abubatu_bulan_ini,2);
		$harga_akhir_penjualan_abubatu_bulan_ini = $harga_penjualan_abubatu_bulan_ini;
		$nilai_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini * $harga_akhir_penjualan_abubatu_bulan_ini;

		//Batu 0,5 - 10
		$penjualan_batu0510_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu0510_bulan_ini = $penjualan_batu0510_bulan_ini['volume'];
		$harga_penjualan_batu0510_bulan_ini = round($harga_akhir_produksi_harian_batu0510_bulan_ini,0);
		$nilai_penjualan_batu0510_bulan_ini = $volume_penjualan_batu0510_bulan_ini * $harga_penjualan_batu0510_bulan_ini;

		$volume_akhir_penjualan_batu0510_bulan_ini = round($volume_akhir_produksi_harian_batu0510_bulan_ini - $volume_penjualan_batu0510_bulan_ini,2);
		$harga_akhir_penjualan_batu0510_bulan_ini =  $harga_penjualan_batu0510_bulan_ini;
		$nilai_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini * $harga_akhir_penjualan_batu0510_bulan_ini;

		//Batu 10 - 20
		$penjualan_batu1020_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu1020_bulan_ini = $penjualan_batu1020_bulan_ini['volume'];
		$harga_penjualan_batu1020_bulan_ini = round($harga_akhir_produksi_harian_batu1020_bulan_ini,0);
		$nilai_penjualan_batu1020_bulan_ini = $volume_penjualan_batu1020_bulan_ini * $harga_penjualan_batu1020_bulan_ini;

		$volume_akhir_penjualan_batu1020_bulan_ini = round($volume_akhir_produksi_harian_batu1020_bulan_ini - $volume_penjualan_batu1020_bulan_ini,2);
		$harga_akhir_penjualan_batu1020_bulan_ini = $harga_penjualan_batu1020_bulan_ini;
		$nilai_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini * $harga_akhir_penjualan_batu1020_bulan_ini;

		//Batu 20 - 30
		$penjualan_batu2030_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu2030_bulan_ini = $penjualan_batu2030_bulan_ini['volume'];
		$harga_penjualan_batu2030_bulan_ini = round($harga_akhir_produksi_harian_batu2030_bulan_ini,0);
		$nilai_penjualan_batu2030_bulan_ini = $volume_penjualan_batu2030_bulan_ini * $harga_penjualan_batu2030_bulan_ini;

		$volume_akhir_penjualan_batu2030_bulan_ini = round($volume_akhir_produksi_harian_batu2030_bulan_ini - $volume_penjualan_batu2030_bulan_ini,2);
		$harga_akhir_penjualan_batu2030_bulan_ini = $harga_penjualan_batu2030_bulan_ini;
		$nilai_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini * $harga_akhir_penjualan_batu2030_bulan_ini;


		$agregat_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 24")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini = round($agregat_bulan_ini['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini = round($agregat_bulan_ini['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini = round($agregat_bulan_ini['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini = round($agregat_bulan_ini['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini = $harga_akhir_penjualan_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini = $harga_akhir_penjualan_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini = $harga_akhir_penjualan_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini = $harga_akhir_penjualan_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini = $volume_agregat_abubatu_bulan_ini * $harga_agregat_abubatu_bulan_ini;
		$nilai_agregat_batu0510_bulan_ini = $volume_agregat_batu0510_bulan_ini * $harga_agregat_batu0510_bulan_ini;
		$nilai_agregat_batu1020_bulan_ini = $volume_agregat_batu1020_bulan_ini * $harga_agregat_batu1020_bulan_ini;
		$nilai_agregat_batu2030_bulan_ini = $volume_agregat_batu2030_bulan_ini * $harga_agregat_batu2030_bulan_ini;

		$volume_akhir_agregat_abubatu_bulan_ini = round($volume_akhir_penjualan_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini,2);
		$volume_akhir_agregat_batu0510_bulan_ini = round($volume_akhir_penjualan_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini,2);
		$volume_akhir_agregat_batu1020_bulan_ini = round($volume_akhir_penjualan_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini,2);
		$volume_akhir_agregat_batu2030_bulan_ini = round($volume_akhir_penjualan_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini,2);

		$harga_akhir_agregat_abubatu_bulan_ini = $harga_agregat_abubatu_bulan_ini;
		$harga_akhir_agregat_batu0510_bulan_ini = $harga_agregat_batu0510_bulan_ini;
		$harga_akhir_agregat_batu1020_bulan_ini = $harga_agregat_batu1020_bulan_ini;
		$harga_akhir_agregat_batu2030_bulan_ini = $harga_agregat_batu2030_bulan_ini;

		$nilai_akhir_agregat_abubatu_bulan_ini = $volume_akhir_agregat_abubatu_bulan_ini * $harga_akhir_agregat_abubatu_bulan_ini;
		$nilai_akhir_agregat_batu0510_bulan_ini = $volume_akhir_agregat_batu0510_bulan_ini * $harga_akhir_agregat_batu0510_bulan_ini;
		$nilai_akhir_agregat_batu1020_bulan_ini = $volume_akhir_agregat_batu1020_bulan_ini * $harga_akhir_agregat_batu1020_bulan_ini;
		$nilai_akhir_agregat_batu2030_bulan_ini = $volume_akhir_agregat_batu2030_bulan_ini * $harga_akhir_agregat_batu2030_bulan_ini;

		$agregat_bulan_ini_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 14")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini_2 = $volume_agregat_abubatu_bulan_ini_2 * $harga_agregat_abubatu_bulan_ini_2;
		$nilai_agregat_batu0510_bulan_ini_2 = $volume_agregat_batu0510_bulan_ini_2 * $harga_agregat_batu0510_bulan_ini_2;
		$nilai_agregat_batu1020_bulan_ini_2 = $volume_agregat_batu1020_bulan_ini_2 * $harga_agregat_batu1020_bulan_ini_2;
		$nilai_agregat_batu2030_bulan_ini_2 = $volume_agregat_batu2030_bulan_ini_2 * $harga_agregat_batu2030_bulan_ini_2;

		$stock_opname_abu_batu = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_akhir_agregat_abubatu_bulan_ini_2 = $stock_opname_abu_batu['volume'] + $stock_opname_abu_batu_cat['volume'];
		$volume_akhir_agregat_batu0510_bulan_ini_2 = $stock_opname_batu0510['volume'] + $stock_opname_batu0510_cat['volume'];
		$volume_akhir_agregat_batu1020_bulan_ini_2 = $stock_opname_batu1020['volume'] + $stock_opname_batu1020_cat['volume'];
		$volume_akhir_agregat_batu2030_bulan_ini_2 = $stock_opname_batu2030['volume'] + $stock_opname_batu2030_cat['volume'];

		$harga_akhir_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini_2;
		$harga_akhir_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini_2;
		$harga_akhir_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini_2;
		$harga_akhir_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini_2;

		$volume_akhir_agregat_abubatu_bulan_ini_2_fix = round($volume_akhir_agregat_abubatu_bulan_ini_2,2);
		$volume_akhir_agregat_batu0510_bulan_ini_2_fix = round($volume_akhir_agregat_batu0510_bulan_ini_2,2);
		$volume_akhir_agregat_batu1020_bulan_ini_2_fix = round($volume_akhir_agregat_batu1020_bulan_ini_2,2);
		$volume_akhir_agregat_batu2030_bulan_ini_2_fix = round($volume_akhir_agregat_batu2030_bulan_ini_2,2);

		$nilai_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini_2_fix * $harga_akhir_agregat_abubatu_bulan_ini_2;
		$nilai_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini_2_fix * $harga_akhir_agregat_batu0510_bulan_ini_2;
		$nilai_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini_2_fix * $harga_akhir_agregat_batu1020_bulan_ini_2;
		$nilai_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini_2_fix * $harga_akhir_agregat_batu2030_bulan_ini_2;

		//TOTAL BAHAN BAKU
		$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

		$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
		$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
		$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

		$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
		$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
		$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

		$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2;
		$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
		$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

		$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
		$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

		//TOTAL
		$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
		$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

		$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;

		$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
		$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;

		?>

		<tr class="table-active">
			<th class="text-center" colspan="6" style='background-color:#5B5B5B; color:white; text-transform:uppercase;'>
				Nilai Persediaan Bahan Jadi
				(<?php
				$search = array(
				'January',
				'February',
				'March',
				'April',
				'May',
				'June',
				'July',
				'August',
				'September',
				'October',
				'November',
				'December'
				);
				
				$replace = array(
				'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
				);
				
				$subject = "$date1_filter - $date2_filter";

				echo str_replace($search, $replace, $subject);

				?>)

			</th>
		</tr>
		<tr>
			<th width="5%" class="text-center" style='background-color:rgb(188,188,188); color:black'>NO.</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>URAIAN</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>SATUAN</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>STOK BARANG</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>HARGA SATUAN</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>NILAI</th>
		</tr>
		<tr>
			<th class="text-center">1.</th>
			<th class="text-left">Batu Split 0,0 - 0,5</th>
			<th class="text-center">Ton</th>
			<th class="text-right"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr>
			<th class="text-center">2.</th>
			<th class="text-left">Batu Split 0,5 - 10</th>
			<th class="text-center">Ton</th>
			<th class="text-right"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr>
			<th class="text-center">3.</th>
			<th class="text-left">Batu Split 10 - 20</th>
			<th class="text-center">Ton</th>
			<th class="text-right"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr>
			<th class="text-center">4.</th>
			<th class="text-left">Batu Split 20 - 30</th>
			<th class="text-center">Ton</th>
			<th class="text-right"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class="text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
		</tr>
		<tr>
			<th class="text-center" colspan="3">TOTAL</th>
			<th class="text-right"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
			<th class="text-right">-</th>
			<th class="text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
		</tr>
	    </table>
		
		<?php
	}

	public function evaluasi_nilai_persediaan($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		<style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #E8E8E8;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #cccccc;
				color: black;
				font-size: 12px;
				font-weight: bold;
			}
			table tr.table-activeago1{
				background-color: #ffd966;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-activeopening{
				background-color: #2986cc;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}

			blink {
			-webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
			animation: 2s linear infinite kedip;
			}
			/* for Safari 4.0 - 8.0 */
			@-webkit-keyframes kedip { 
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}
			@keyframes kedip {
			0% {
				visibility: hidden;
			}
			50% {
				visibility: hidden;
			}
			100% {
				visibility: visible;
			}
			}

		</style>
		<tr class="table-active2">
			<th colspan="3">Periode</th>
			<th class="text-center" colspan="9"><?php echo $filter_date;?></th>
		</tr>
		
		<?php

		//LAPORAN BEBAN POKOK PRODUKSI

		//PERGERAKAN BAHAN BAKU

		//Opening Balance
		$date1_ago = date('2020-01-01');
		$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
		$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));

		$pergerakan_bahan_baku_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_ago = $pergerakan_bahan_baku_ago['volume'];
		$total_volume_pembelian_akhir_ago  = $total_volume_pembelian_ago;

		$produksi_harian_ago = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1_ago' and '$date2_ago')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_volume_produksi_ago = $produksi_harian_ago['used'];
		$total_volume_produksi_akhir_ago = $total_volume_pembelian_akhir_ago - $total_volume_produksi_ago;

		$harga_satuan_ago = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date3_ago' and '$date2_ago'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$nilai_harga_satuan_ago = ($harga_satuan_ago['volume']!=0)?($harga_satuan_ago['nilai'] / $harga_satuan_ago['volume'])  * 1:0;

		$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
		->from('hpp_bahan_baku pp')
		->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
		->order_by('pp.date_hpp','desc')->limit(1)
		->get()->row_array();

		$total_volume_produksi_akhir_ago_fix = round($total_volume_produksi_akhir_ago,2);

		$volume_opening_balance = $total_volume_produksi_akhir_ago_fix;
		$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
		$nilai_opening_balance = $total_volume_produksi_akhir_ago_fix * $harga_opening_balance;

		$pergerakan_bahan_baku_ago_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		SUM(prm.display_price) / SUM(prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1_ago' and '$date2_ago'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$volume_pergerakan_bahan_baku_ago_solar = $pergerakan_bahan_baku_ago_solar['volume'];

		$stock_opname_solar_ago = $this->db->select('`prm`.`volume` as volume, `prm`.`total` as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("(prm.date < '$date1')")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar_ago = $stock_opname_solar_ago['volume'];

		$volume_opening_balance_solar = $volume_stock_opname_solar_ago;
		$volume_opening_balance_solar_fix = round($volume_opening_balance_solar,2);

		$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
		$nilai_opening_balance_solar = $volume_opening_balance_solar_fix * $harga_opening_balance_solar;

		//Now
		//Bahan Baku			
		$pergerakan_bahan_baku = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 15")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
		$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
		$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

		$total_volume_pembelian_akhir  = $total_volume_produksi_akhir_ago + $total_volume_pembelian;
		$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
		$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			

		$produksi_harian = $this->db->select('sum(pphd.use) as used')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
		->from('akumulasi_bahan_baku pp')
		->where("(pp.date_akumulasi between '$date1' and '$date2')")
		->get()->result_array();

		$total_akumulasi_bahan_baku = 0;
		$total_akumulasi_bahan_baku_2 = 0;

		foreach ($akumulasi_bahan_baku as $b){
			$total_akumulasi_bahan_baku += $b['total_nilai_keluar'];
			$total_akumulasi_bahan_baku_2 += $b['total_nilai_keluar_2'];
		}

		$akumulasi_nilai_bahan_baku = $total_akumulasi_bahan_baku;
		$akumulasi_nilai_bahan_baku_2 = $total_akumulasi_bahan_baku_2;

		$total_volume_produksi = $produksi_harian['used'];
		$total_nilai_produksi = $akumulasi_nilai_bahan_baku;
		$total_harga_produksi = ($total_volume_produksi!=0)?($total_nilai_produksi / $total_volume_produksi)  * 1:0;

		$total_volume_produksi_akhir = $total_volume_pembelian_akhir - $total_volume_produksi;
		$total_harga_produksi_akhir = $total_harga_produksi;
		$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

		//BBM Solar
		$pergerakan_bahan_baku_solar = $this->db->select('
		p.nama_produk, 
		prm.display_measure as satuan, 
		SUM(prm.display_volume) as volume, 
		(prm.display_price / prm.display_volume) as harga, 
		SUM(prm.display_price) as nilai')
		->from('pmm_receipt_material prm')
		->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
		->join('produk p', 'prm.material_id = p.id','left')
		->where("prm.date_receipt between '$date1' and '$date2'")
		->where("prm.material_id = 13")
		->group_by('prm.material_id')
		->get()->row_array();

		$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
		$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
		$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

		$total_volume_pembelian_akhir_solar  = $volume_opening_balance_solar + $total_volume_pembelian_solar;
		$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
		$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

		$stock_opname_solar = $this->db->select('(prm.volume) as volume, (prm.total) as total')
		->from('pmm_remaining_materials_cat prm ')
		->where("prm.material_id = 13")
		->where("prm.date between '$date1' and '$date2'")
		->where("status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$volume_stock_opname_solar = $stock_opname_solar['volume'];

		$total_volume_produksi_akhir_solar = $volume_stock_opname_solar;
		$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
		$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

		$total_volume_produksi_solar = $total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar;
		$total_nilai_produksi_solar =  $akumulasi_nilai_bahan_baku_2;
		$total_harga_produksi_solar = ($total_volume_produksi_solar!=0)?($total_nilai_produksi_solar / $total_volume_produksi_solar)  * 1:0;
		//END PERGERAKAN BAHAN BAKU

		//PERALATAN & OPERASIONAL
		$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$total_abu_batu = 0;
		$nilai_abu_batu1 = 0;
		$nilai_abu_batu2 = 0;
		$nilai_abu_batu3 = 0;
		$nilai_abu_batu4 = 0;
		$nilai_abu_batu5 = 0;
		$nilai_abu_batu_all = 0;

		$total_abu_batu = $abu_batu['jumlah_pemakaian_a'] + $abu_batu['jumlah_pemakaian_b'] + $abu_batu['jumlah_pemakaian_c'] + $abu_batu['jumlah_pemakaian_d'] + $abu_batu['jumlah_pemakaian_e'];
		$nilai_abu_batu1 = $abu_batu['jumlah_pemakaian_a'] * $total_harga_produksi_akhir;
		$nilai_abu_batu2 = $abu_batu['jumlah_pemakaian_b'] * $total_harga_produksi_akhir;
		$nilai_abu_batu3 = $abu_batu['jumlah_pemakaian_c'] * $total_harga_produksi_akhir;
		$nilai_abu_batu4 = $abu_batu['jumlah_pemakaian_d'] * $total_harga_produksi_akhir;
		$nilai_abu_batu5 = $abu_batu['jumlah_pemakaian_e'] * $total_harga_produksi_akhir;
		$nilai_abu_batu_all = $nilai_abu_batu1 + $nilai_abu_batu2 + $nilai_abu_batu3 + $nilai_abu_batu4 + $nilai_abu_batu5;

		$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;

		$stone_crusher_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 101")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$stone_crusher = $stone_crusher_biaya['total'] + $stone_crusher_jurnal['total'];

		$whell_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 104")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$whell_loader = $whell_loader_biaya['total'] + $whell_loader_jurnal['total'];

		$excavator = $this->db->select('sum(prm.display_price) as price')
		->from('pmm_receipt_material prm ')
		->where("prm.material_id = 18")
		->where("(prm.date_receipt between '$date1' and '$date2')")
		->get()->row_array();

		$genset_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 197")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$genset = $genset_biaya['total'] + $genset_jurnal['total'];

		$timbangan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan_biaya_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 198")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$timbangan = $timbangan_biaya['total'] + $timbangan_biaya_jurnal['total'];

		$tangki_solar_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 207")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$tangki_solar = $tangki_solar_biaya['total'] + $tangki_solar_jurnal['total'];		

		$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar;
		$hpp_peralatan = ($total_abu_batu!=0)?($total_biaya_peralatan / $total_abu_batu)  * 1:0;

		$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun in (199,200)")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];

		$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 201")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];

		$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 202")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];

		$perbaikan_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 203")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$perbaikan = $perbaikan_biaya['total'] + $perbaikan_jurnal['total'];

		$akomodasi_tamu_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 204")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$akomodasi_tamu = $akomodasi_tamu_biaya['total'] + $akomodasi_tamu_jurnal['total'];

		$pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 205")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$pengujian = $pengujian_biaya['total'] + $pengujian_jurnal['total'];

		$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
		->from('pmm_biaya pb ')
		->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
		->from('pmm_jurnal_umum pb ')
		->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
		->where("pdb.akun = 206")
		->where("status = 'PAID'")
		->where("(tanggal_transaksi between '$date1' and '$date2')")
		->get()->row_array();

		$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

		$total_operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;
		$hpp_operasional = ($total_abu_batu!=0)?($total_operasional / $total_abu_batu)  * 1:0;
		$total_bpp = $total_nilai_produksi + $total_nilai_produksi_solar + $total_biaya_peralatan + $total_operasional;
		$harga_bpp = ($total_abu_batu!=0)?($total_bpp / $total_abu_batu)  * 1:0;

		$harga_pemakaian_a = 0;
		$harga_pemakaian_b = 0;
		$harga_pemakaian_c = 0;
		$harga_pemakaian_d = 0;
		$total_harga_pemakaian = 0;

		$harga_pemakaian_a = $harga_bpp * $abu_batu['jumlah_pemakaian_a'];
		$harga_pemakaian_b = $harga_bpp * $abu_batu['jumlah_pemakaian_b'];
		$harga_pemakaian_c = $harga_bpp * $abu_batu['jumlah_pemakaian_c'];
		$harga_pemakaian_d = $harga_bpp * $abu_batu['jumlah_pemakaian_d'];

		$total_harga_pemakaian = $harga_pemakaian_a + $harga_pemakaian_b + $harga_pemakaian_c + $harga_pemakaian_d;
		//END PERALATAN & OPERASIONAL
		//END LAPORAN BEBAN POKOK PRODUKSI
			
			
		//Opening Balance Pergerakan Bahan Jadi
		$tanggal_awal = date('2020-01-01');
		$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$produksi_harian_bulan_lalu = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$tanggal_awal' and '$tanggal_opening_balance')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$volume_produksi_harian_abubatu_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_a'];
		$volume_produksi_harian_batu0510_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_b'];
		$volume_produksi_harian_batu1020_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_c'];
		$volume_produksi_harian_batu2030_bulan_lalu = $produksi_harian_bulan_lalu['jumlah_pemakaian_d'];

		$penjualan_abubatu_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_abubatu_bulan_lalu = $penjualan_abubatu_bulan_lalu['volume'];

		$penjualan_batu0510_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu0510_bulan_lalu = $penjualan_batu0510_bulan_lalu['volume'];

		$penjualan_batu1020_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu1020_bulan_lalu = $penjualan_batu1020_bulan_lalu['volume'];

		$penjualan_batu2030_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu2030_bulan_lalu = $penjualan_batu2030_bulan_lalu['volume'];

		//Agregat Bulan Lalu
		$agregat_bulan_lalu = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 24")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_lalu = $agregat_bulan_lalu['volume_agregat_a'];
		$volume_agregat_batu0510_bulan_lalu = $agregat_bulan_lalu['volume_agregat_b'];
		$volume_agregat_batu1020_bulan_lalu = $agregat_bulan_lalu['volume_agregat_c'];
		$volume_agregat_batu2030_bulan_lalu = $agregat_bulan_lalu['volume_agregat_d'];

		$agregat_bulan_lalu_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$tanggal_awal' and '$tanggal_opening_balance'")
		->where("pp.product_id = 14")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_a'];
		$volume_agregat_batu0510_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_b'];
		$volume_agregat_batu1020_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_c'];
		$volume_agregat_batu2030_bulan_lalu_2 = $agregat_bulan_lalu_2['volume_agregat_d'];

		//Opening Balance
		$volume_opening_balance_abubatu_bulan_lalu = round($volume_produksi_harian_abubatu_bulan_lalu - $volume_penjualan_abubatu_bulan_lalu - $volume_agregat_abubatu_bulan_lalu - $volume_agregat_abubatu_bulan_lalu_2,2);
		$volume_opening_balance_batu0510_bulan_lalu = round($volume_produksi_harian_batu0510_bulan_lalu - $volume_penjualan_batu0510_bulan_lalu - $volume_agregat_batu0510_bulan_lalu - $volume_agregat_batu0510_bulan_lalu_2,2);
		$volume_opening_balance_batu1020_bulan_lalu = round($volume_produksi_harian_batu1020_bulan_lalu - $volume_penjualan_batu1020_bulan_lalu - $volume_agregat_batu1020_bulan_lalu - $volume_agregat_batu1020_bulan_lalu_2,2);
		$volume_opening_balance_batu2030_bulan_lalu = round($volume_produksi_harian_batu2030_bulan_lalu - $volume_penjualan_batu2030_bulan_lalu - $volume_agregat_batu2030_bulan_lalu - $volume_agregat_batu2030_bulan_lalu_2,2);

		//Rumus Harga Opening Balance

		//Dua Bulan Lalu
		$tanggal_opening_balance_2 = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		//Satu Bulan Lalu
		$tanggal_opening_balance_3 = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

		$harga_hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp pp')
		->where("(pp.date_hpp between '$tanggal_opening_balance_2' and '$tanggal_opening_balance_3')")
		->get()->row_array();

		$harga_opening_balance_abubatu_bulan_lalu = $harga_hpp['abubatu'];
		$harga_opening_balance_batu0510_bulan_lalu =  $harga_hpp['batu0510'];
		$harga_opening_balance_batu1020_bulan_lalu =  $harga_hpp['batu1020'];
		$harga_opening_balance_batu2030_bulan_lalu =  $harga_hpp['batu2030'];

		$vol_1 = round($volume_opening_balance_abubatu_bulan_lalu,2);
		$vol_2 = round($volume_opening_balance_batu0510_bulan_lalu,2);
		$vol_3 = round($volume_opening_balance_batu1020_bulan_lalu,2);
		$vol_4 = round($volume_opening_balance_batu2030_bulan_lalu,2);

		$nilai_opening_balance_abubatu_bulan_lalu = $vol_1 * $harga_opening_balance_abubatu_bulan_lalu;
		$nilai_opening_balance_batu0510_bulan_lalu = $vol_2 * $harga_opening_balance_batu0510_bulan_lalu;
		$nilai_opening_balance_batu1020_bulan_lalu = $vol_3 * $harga_opening_balance_batu1020_bulan_lalu;
		$nilai_opening_balance_batu2030_bulan_lalu = $vol_4 * $harga_opening_balance_batu2030_bulan_lalu;

		//Now		
		$produksi_harian_bulan_ini = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a as presentase_a, pk.presentase_b as presentase_b, pk.presentase_c as presentase_c, pk.presentase_d as presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$volume_produksi_harian_abubatu_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_a'];
		$volume_produksi_harian_batu0510_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_b'];
		$volume_produksi_harian_batu1020_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_c'];
		$volume_produksi_harian_batu2030_bulan_ini = $produksi_harian_bulan_ini['jumlah_pemakaian_d'];

		$harga_produksi_harian_abubatu_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu0510_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu1020_bulan_ini = $harga_bpp;
		$harga_produksi_harian_batu2030_bulan_ini = $harga_bpp;

		$nilai_produksi_harian_abubatu_bulan_ini = $volume_produksi_harian_abubatu_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu0510_bulan_ini = $volume_produksi_harian_batu0510_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu1020_bulan_ini = $volume_produksi_harian_batu1020_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;
		$nilai_produksi_harian_batu2030_bulan_ini = $volume_produksi_harian_batu2030_bulan_ini * $harga_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_abubatu_bulan_ini = $volume_opening_balance_abubatu_bulan_lalu + $volume_produksi_harian_abubatu_bulan_ini;
		$harga_akhir_produksi_harian_abubatu_bulan_ini = ($nilai_opening_balance_abubatu_bulan_lalu + $nilai_produksi_harian_abubatu_bulan_ini) / $volume_akhir_produksi_harian_abubatu_bulan_ini;
		$nilai_akhir_produksi_harian_abubatu_bulan_ini = $volume_akhir_produksi_harian_abubatu_bulan_ini * $harga_akhir_produksi_harian_abubatu_bulan_ini;

		$volume_akhir_produksi_harian_batu0510_bulan_ini = $volume_opening_balance_batu0510_bulan_lalu + $volume_produksi_harian_batu0510_bulan_ini;
		$harga_akhir_produksi_harian_batu0510_bulan_ini = ($nilai_opening_balance_batu0510_bulan_lalu + $nilai_produksi_harian_batu0510_bulan_ini) / $volume_akhir_produksi_harian_batu0510_bulan_ini;
		$nilai_akhir_produksi_harian_batu0510_bulan_ini = $volume_akhir_produksi_harian_batu0510_bulan_ini * $harga_akhir_produksi_harian_batu0510_bulan_ini;

		$volume_akhir_produksi_harian_batu1020_bulan_ini = $volume_opening_balance_batu1020_bulan_lalu + $volume_produksi_harian_batu1020_bulan_ini;
		$harga_akhir_produksi_harian_batu1020_bulan_ini = ($nilai_opening_balance_batu1020_bulan_lalu + $nilai_produksi_harian_batu1020_bulan_ini) / $volume_akhir_produksi_harian_batu1020_bulan_ini;
		$nilai_akhir_produksi_harian_batu1020_bulan_ini = $volume_akhir_produksi_harian_batu1020_bulan_ini * $harga_akhir_produksi_harian_batu1020_bulan_ini;

		$volume_akhir_produksi_harian_batu2030_bulan_ini = $volume_opening_balance_batu2030_bulan_lalu + $volume_produksi_harian_batu2030_bulan_ini;
		$harga_akhir_produksi_harian_batu2030_bulan_ini = ($nilai_opening_balance_batu2030_bulan_lalu + $nilai_produksi_harian_batu2030_bulan_ini) / $volume_akhir_produksi_harian_batu2030_bulan_ini;
		$nilai_akhir_produksi_harian_batu2030_bulan_ini = $volume_akhir_produksi_harian_batu2030_bulan_ini * $harga_akhir_produksi_harian_batu2030_bulan_ini;

		//Abu Batu
		$penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_abubatu_bulan_ini = $penjualan_abubatu_bulan_ini['volume'];
		$harga_penjualan_abubatu_bulan_ini = round($harga_akhir_produksi_harian_abubatu_bulan_ini,0);
		$nilai_penjualan_abubatu_bulan_ini = $volume_penjualan_abubatu_bulan_ini * $harga_penjualan_abubatu_bulan_ini;

		$volume_akhir_penjualan_abubatu_bulan_ini = round($volume_akhir_produksi_harian_abubatu_bulan_ini - $volume_penjualan_abubatu_bulan_ini,2);
		$harga_akhir_penjualan_abubatu_bulan_ini = $harga_penjualan_abubatu_bulan_ini;
		$nilai_akhir_penjualan_abubatu_bulan_ini = $volume_akhir_penjualan_abubatu_bulan_ini * $harga_akhir_penjualan_abubatu_bulan_ini;

		//Batu 0,5 - 10
		$penjualan_batu0510_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu0510_bulan_ini = $penjualan_batu0510_bulan_ini['volume'];
		$harga_penjualan_batu0510_bulan_ini = round($harga_akhir_produksi_harian_batu0510_bulan_ini,0);
		$nilai_penjualan_batu0510_bulan_ini = $volume_penjualan_batu0510_bulan_ini * $harga_penjualan_batu0510_bulan_ini;

		$volume_akhir_penjualan_batu0510_bulan_ini = round($volume_akhir_produksi_harian_batu0510_bulan_ini - $volume_penjualan_batu0510_bulan_ini,2);
		$harga_akhir_penjualan_batu0510_bulan_ini =  $harga_penjualan_batu0510_bulan_ini;
		$nilai_akhir_penjualan_batu0510_bulan_ini = $volume_akhir_penjualan_batu0510_bulan_ini * $harga_akhir_penjualan_batu0510_bulan_ini;

		//Batu 10 - 20
		$penjualan_batu1020_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu1020_bulan_ini = $penjualan_batu1020_bulan_ini['volume'];
		$harga_penjualan_batu1020_bulan_ini = round($harga_akhir_produksi_harian_batu1020_bulan_ini,0);
		$nilai_penjualan_batu1020_bulan_ini = $volume_penjualan_batu1020_bulan_ini * $harga_penjualan_batu1020_bulan_ini;

		$volume_akhir_penjualan_batu1020_bulan_ini = round($volume_akhir_produksi_harian_batu1020_bulan_ini - $volume_penjualan_batu1020_bulan_ini,2);
		$harga_akhir_penjualan_batu1020_bulan_ini = $harga_penjualan_batu1020_bulan_ini;
		$nilai_akhir_penjualan_batu1020_bulan_ini = $volume_akhir_penjualan_batu1020_bulan_ini * $harga_akhir_penjualan_batu1020_bulan_ini;

		//Batu 20 - 30
		$penjualan_batu2030_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_penjualan_batu2030_bulan_ini = $penjualan_batu2030_bulan_ini['volume'];
		$harga_penjualan_batu2030_bulan_ini = round($harga_akhir_produksi_harian_batu2030_bulan_ini,0);
		$nilai_penjualan_batu2030_bulan_ini = $volume_penjualan_batu2030_bulan_ini * $harga_penjualan_batu2030_bulan_ini;

		$volume_akhir_penjualan_batu2030_bulan_ini = round($volume_akhir_produksi_harian_batu2030_bulan_ini - $volume_penjualan_batu2030_bulan_ini,2);
		$harga_akhir_penjualan_batu2030_bulan_ini = $harga_penjualan_batu2030_bulan_ini;
		$nilai_akhir_penjualan_batu2030_bulan_ini = $volume_akhir_penjualan_batu2030_bulan_ini * $harga_akhir_penjualan_batu2030_bulan_ini;

		//Agregat
		$agregat_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 24")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini = round($agregat_bulan_ini['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini = round($agregat_bulan_ini['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini = round($agregat_bulan_ini['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini = round($agregat_bulan_ini['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini = $harga_akhir_penjualan_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini = $harga_akhir_penjualan_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini = $harga_akhir_penjualan_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini = $harga_akhir_penjualan_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini = $volume_agregat_abubatu_bulan_ini * $harga_agregat_abubatu_bulan_ini;
		$nilai_agregat_batu0510_bulan_ini = $volume_agregat_batu0510_bulan_ini * $harga_agregat_batu0510_bulan_ini;
		$nilai_agregat_batu1020_bulan_ini = $volume_agregat_batu1020_bulan_ini * $harga_agregat_batu1020_bulan_ini;
		$nilai_agregat_batu2030_bulan_ini = $volume_agregat_batu2030_bulan_ini * $harga_agregat_batu2030_bulan_ini;

		$volume_akhir_agregat_abubatu_bulan_ini = round($volume_akhir_penjualan_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini,2);
		$volume_akhir_agregat_batu0510_bulan_ini = round($volume_akhir_penjualan_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini,2);
		$volume_akhir_agregat_batu1020_bulan_ini = round($volume_akhir_penjualan_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini,2);
		$volume_akhir_agregat_batu2030_bulan_ini = round($volume_akhir_penjualan_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini,2);

		$harga_akhir_agregat_abubatu_bulan_ini = $harga_agregat_abubatu_bulan_ini;
		$harga_akhir_agregat_batu0510_bulan_ini = $harga_agregat_batu0510_bulan_ini;
		$harga_akhir_agregat_batu1020_bulan_ini = $harga_agregat_batu1020_bulan_ini;
		$harga_akhir_agregat_batu2030_bulan_ini = $harga_agregat_batu2030_bulan_ini;

		$nilai_akhir_agregat_abubatu_bulan_ini = $volume_akhir_agregat_abubatu_bulan_ini * $harga_akhir_agregat_abubatu_bulan_ini;
		$nilai_akhir_agregat_batu0510_bulan_ini = $volume_akhir_agregat_batu0510_bulan_ini * $harga_akhir_agregat_batu0510_bulan_ini;
		$nilai_akhir_agregat_batu1020_bulan_ini = $volume_akhir_agregat_batu1020_bulan_ini * $harga_akhir_agregat_batu1020_bulan_ini;
		$nilai_akhir_agregat_batu2030_bulan_ini = $volume_akhir_agregat_batu2030_bulan_ini * $harga_akhir_agregat_batu2030_bulan_ini;

		$agregat_bulan_ini_2 = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai, (SUM(pp.display_volume) * pa.presentase_a) / 100 as volume_agregat_a, (SUM(pp.display_volume) * pa.presentase_b) / 100 as volume_agregat_b, (SUM(pp.display_volume) * pa.presentase_c) / 100 as volume_agregat_c, (SUM(pp.display_volume) * pa.presentase_d) / 100 as volume_agregat_d')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('pmm_agregat pa', 'pp.komposisi_id = pa.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 14")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$volume_agregat_abubatu_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_a'],2);
		$volume_agregat_batu0510_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_b'],2);
		$volume_agregat_batu1020_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_c'],2);
		$volume_agregat_batu2030_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_d'],2);

		$harga_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini;
		$harga_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini;
		$harga_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini;
		$harga_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini;

		$nilai_agregat_abubatu_bulan_ini_2 = $volume_agregat_abubatu_bulan_ini_2 * $harga_agregat_abubatu_bulan_ini_2;
		$nilai_agregat_batu0510_bulan_ini_2 = $volume_agregat_batu0510_bulan_ini_2 * $harga_agregat_batu0510_bulan_ini_2;
		$nilai_agregat_batu1020_bulan_ini_2 = $volume_agregat_batu1020_bulan_ini_2 * $harga_agregat_batu1020_bulan_ini_2;
		$nilai_agregat_batu2030_bulan_ini_2 = $volume_agregat_batu2030_bulan_ini_2 * $harga_agregat_batu2030_bulan_ini_2;

		$volume_akhir_agregat_abubatu_bulan_ini_2 = round($volume_akhir_agregat_abubatu_bulan_ini - $volume_agregat_abubatu_bulan_ini_2,2);
		$volume_akhir_agregat_batu0510_bulan_ini_2 = round($volume_akhir_agregat_batu0510_bulan_ini - $volume_agregat_batu0510_bulan_ini_2,2);
		$volume_akhir_agregat_batu1020_bulan_ini_2 = round($volume_akhir_agregat_batu1020_bulan_ini - $volume_agregat_batu1020_bulan_ini_2,2);
		$volume_akhir_agregat_batu2030_bulan_ini_2 = round($volume_akhir_agregat_batu2030_bulan_ini - $volume_agregat_batu2030_bulan_ini_2,2);

		$harga_akhir_agregat_abubatu_bulan_ini_2 = $harga_agregat_abubatu_bulan_ini_2;
		$harga_akhir_agregat_batu0510_bulan_ini_2 = $harga_agregat_batu0510_bulan_ini_2;
		$harga_akhir_agregat_batu1020_bulan_ini_2 = $harga_agregat_batu1020_bulan_ini_2;
		$harga_akhir_agregat_batu2030_bulan_ini_2 = $harga_agregat_batu2030_bulan_ini_2;

		$nilai_akhir_agregat_abubatu_bulan_ini_2 = $volume_akhir_agregat_abubatu_bulan_ini_2 * $harga_akhir_agregat_abubatu_bulan_ini_2;
		$nilai_akhir_agregat_batu0510_bulan_ini_2 = $volume_akhir_agregat_batu0510_bulan_ini_2 * $harga_akhir_agregat_batu0510_bulan_ini_2;
		$nilai_akhir_agregat_batu1020_bulan_ini_2 = $volume_akhir_agregat_batu1020_bulan_ini_2 * $harga_akhir_agregat_batu1020_bulan_ini_2;
		$nilai_akhir_agregat_batu2030_bulan_ini_2 = $volume_akhir_agregat_batu2030_bulan_ini_2 * $harga_akhir_agregat_batu2030_bulan_ini_2;

		//TOTAL BAHAN BAKU
		$nilai_opening_bahan_jadi = $nilai_opening_balance_abubatu_bulan_lalu + $nilai_opening_balance_batu0510_bulan_lalu + $nilai_opening_balance_batu1020_bulan_lalu + $nilai_opening_balance_batu2030_bulan_lalu;

		$volume_penjualan_abubatu = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
		$nilai_penjualan_abubatu = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
		$harga_penjualan_abubatu = ($volume_penjualan_abubatu!=0)?($nilai_penjualan_abubatu / $volume_penjualan_abubatu)  * 1:0;

		$volume_penjualan_batu0510 = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
		$nilai_penjualan_batu0510 = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
		$harga_penjualan_batu0510 = ($volume_penjualan_batu0510!=0)?($nilai_penjualan_batu0510 / $volume_penjualan_batu0510)  * 1:0;

		$volume_penjualan_batu1020 = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2;
		$nilai_penjualan_batu1020 = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
		$harga_penjualan_batu1020 = ($volume_penjualan_batu1020!=0)?($nilai_penjualan_batu1020 / $volume_penjualan_batu1020)  * 1:0;

		$volume_penjualan_batu2030 = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$nilai_penjualan_batu2030 = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;
		$harga_penjualan_batu2030 = ($volume_penjualan_batu2030!=0)?($nilai_penjualan_batu2030 / $volume_penjualan_batu2030)  * 1:0;

		//TOTAL
		$total_volume_masuk = $volume_produksi_harian_abubatu_bulan_ini + $volume_produksi_harian_batu0510_bulan_ini + $volume_produksi_harian_batu1020_bulan_ini + $volume_produksi_harian_batu2030_bulan_ini;
		$total_nilai_masuk = $nilai_produksi_harian_abubatu_bulan_ini + $nilai_produksi_harian_batu0510_bulan_ini + $nilai_produksi_harian_batu1020_bulan_ini + $nilai_produksi_harian_batu2030_bulan_ini;

		$total_volume_keluar = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2 + $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2 + $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini_2 + $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;
		$total_nilai_keluar = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2 +  $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2 + $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2 + $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;

		$total_volume_akhir = $volume_akhir_agregat_abubatu_bulan_ini_2 + $volume_akhir_agregat_batu0510_bulan_ini_2 + $volume_akhir_agregat_batu1020_bulan_ini_2 + $volume_akhir_agregat_batu2030_bulan_ini_2;
		$total_nilai_akhir = $nilai_akhir_agregat_abubatu_bulan_ini_2 + $nilai_akhir_agregat_batu0510_bulan_ini_2 + $nilai_akhir_agregat_batu1020_bulan_ini_2 + $nilai_akhir_agregat_batu2030_bulan_ini_2;

		//PERGERAKAN BAHAN BAKU PENYESUAIAN STOK	

		//Opening Balance
		$stock_opname_abu_batu_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu0510_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_ago_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$tanggal_opening_balance')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_opening_balance_abubatu_bulan_lalu = $stock_opname_abu_batu_ago['volume'] + $stock_opname_abu_batu_ago_cat['volume'];
		$stok_volume_opening_balance_batu0510_bulan_lalu = $stock_opname_batu0510_ago['volume'] + $stock_opname_batu0510_ago_cat['volume'];
		$stok_volume_opening_balance_batu1020_bulan_lalu = $stock_opname_batu1020_ago['volume'] + $stock_opname_batu1020_ago_cat['volume'];
		$stok_volume_opening_balance_batu2030_bulan_lalu = $stock_opname_batu2030_ago['volume'] + $stock_opname_batu2030_ago_cat['volume'];

		//Rumus Harga Opening Balance

		//Dua Bulan Lalu
		$tanggal_opening_balance_2 = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
		//Satu Bulan Lalu
		$tanggal_opening_balance_3 = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			
		$harga_hpp_2 = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp_2 pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$harga_hpp = $this->db->select('pp.date_hpp, pp.abubatu, pp.batu0510, pp.batu1020, pp.batu2030')
		->from('hpp pp')
		->where("(pp.date_hpp = '$tanggal_opening_balance_3')")
		->where("pp.reset = 1")
		->get()->row_array();

		$stok_harga_opening_balance_abubatu_bulan_lalu = $harga_hpp_2['abubatu'] + $harga_hpp['abubatu'];
		$stok_harga_opening_balance_batu0510_bulan_lalu = $harga_hpp_2['batu0510'] + $harga_hpp['batu0510'];
		$stok_harga_opening_balance_batu1020_bulan_lalu = $harga_hpp_2['batu1020'] + $harga_hpp['batu1020'];
		$stok_harga_opening_balance_batu2030_bulan_lalu =  $harga_hpp_2['batu2030'] + $harga_hpp['batu2030'];

		$stok_vol_1 = round($stok_volume_opening_balance_abubatu_bulan_lalu,2);
		$stok_vol_2 = round($stok_volume_opening_balance_batu0510_bulan_lalu,2);
		$stok_vol_3 = round($stok_volume_opening_balance_batu1020_bulan_lalu,2);
		$stok_vol_4 = round($stok_volume_opening_balance_batu2030_bulan_lalu,2);

		$stok_nilai_opening_balance_abubatu_bulan_lalu = $stok_vol_1 * $stok_harga_opening_balance_abubatu_bulan_lalu;
		$stok_nilai_opening_balance_batu0510_bulan_lalu = $stok_vol_2 * $stok_harga_opening_balance_batu0510_bulan_lalu;
		$stok_nilai_opening_balance_batu1020_bulan_lalu = $stok_vol_3 * $stok_harga_opening_balance_batu1020_bulan_lalu;
		$stok_nilai_opening_balance_batu2030_bulan_lalu = $stok_vol_4 * $stok_harga_opening_balance_batu2030_bulan_lalu;

		$stok_produksi_harian_bulan_ini = $this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.presentase_a as presentase_a, pk.presentase_b as presentase_b, pk.presentase_c as presentase_c, pk.presentase_d as presentase_d')
		->from('pmm_produksi_harian pph ')
		->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
		->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
		->where("(pph.date_prod between '$date1' and '$date2')")
		->where("pph.status = 'PUBLISH'")
		->get()->row_array();

		$stok_volume_produksi_harian_abubatu_bulan_ini = $stok_produksi_harian_bulan_ini['jumlah_pemakaian_a'];
		$stok_volume_produksi_harian_batu0510_bulan_ini = $stok_produksi_harian_bulan_ini['jumlah_pemakaian_b'];
		$stok_volume_produksi_harian_batu1020_bulan_ini = $stok_produksi_harian_bulan_ini['jumlah_pemakaian_c'];
		$stok_volume_produksi_harian_batu2030_bulan_ini = $stok_produksi_harian_bulan_ini['jumlah_pemakaian_d'];

		$stok_harga_produksi_harian_abubatu_bulan_ini = $harga_bpp;
		$stok_harga_produksi_harian_batu0510_bulan_ini = $harga_bpp;
		$stok_harga_produksi_harian_batu1020_bulan_ini = $harga_bpp;
		$stok_harga_produksi_harian_batu2030_bulan_ini = $harga_bpp;

		$stok_nilai_produksi_harian_abubatu_bulan_ini = $stok_volume_produksi_harian_abubatu_bulan_ini * $stok_harga_produksi_harian_abubatu_bulan_ini;
		$stok_nilai_produksi_harian_batu0510_bulan_ini = $stok_volume_produksi_harian_batu0510_bulan_ini * $stok_harga_produksi_harian_abubatu_bulan_ini;
		$stok_nilai_produksi_harian_batu1020_bulan_ini = $stok_volume_produksi_harian_batu1020_bulan_ini * $stok_harga_produksi_harian_abubatu_bulan_ini;
		$stok_nilai_produksi_harian_batu2030_bulan_ini = $stok_volume_produksi_harian_batu2030_bulan_ini * $stok_harga_produksi_harian_abubatu_bulan_ini;

		$stok_volume_akhir_produksi_harian_abubatu_bulan_ini = $stok_volume_opening_balance_abubatu_bulan_lalu + $stok_volume_produksi_harian_abubatu_bulan_ini;
		$stok_harga_akhir_produksi_harian_abubatu_bulan_ini = ($stok_nilai_opening_balance_abubatu_bulan_lalu + $stok_nilai_produksi_harian_abubatu_bulan_ini) / $stok_volume_akhir_produksi_harian_abubatu_bulan_ini;
		$stok_nilai_akhir_produksi_harian_abubatu_bulan_ini = $stok_volume_akhir_produksi_harian_abubatu_bulan_ini * $stok_harga_akhir_produksi_harian_abubatu_bulan_ini;

		$stok_volume_akhir_produksi_harian_batu0510_bulan_ini = $stok_volume_opening_balance_batu0510_bulan_lalu + $stok_volume_produksi_harian_batu0510_bulan_ini;
		$stok_harga_akhir_produksi_harian_batu0510_bulan_ini = ($stok_nilai_opening_balance_batu0510_bulan_lalu + $stok_nilai_produksi_harian_batu0510_bulan_ini) / $stok_volume_akhir_produksi_harian_batu0510_bulan_ini;
		$stok_nilai_akhir_produksi_harian_batu0510_bulan_ini = $stok_volume_akhir_produksi_harian_batu0510_bulan_ini * $stok_harga_akhir_produksi_harian_batu0510_bulan_ini;

		$stok_volume_akhir_produksi_harian_batu1020_bulan_ini = $stok_volume_opening_balance_batu1020_bulan_lalu + $stok_volume_produksi_harian_batu1020_bulan_ini;
		$stok_harga_akhir_produksi_harian_batu1020_bulan_ini = ($stok_nilai_opening_balance_batu1020_bulan_lalu + $stok_nilai_produksi_harian_batu1020_bulan_ini) / $stok_volume_akhir_produksi_harian_batu1020_bulan_ini;
		$stok_nilai_akhir_produksi_harian_batu1020_bulan_ini = $stok_volume_akhir_produksi_harian_batu1020_bulan_ini * $stok_harga_akhir_produksi_harian_batu1020_bulan_ini;

		$stok_volume_akhir_produksi_harian_batu2030_bulan_ini = $stok_volume_opening_balance_batu2030_bulan_lalu + $stok_volume_produksi_harian_batu2030_bulan_ini;
		$stok_harga_akhir_produksi_harian_batu2030_bulan_ini = ($stok_nilai_opening_balance_batu2030_bulan_lalu + $stok_nilai_produksi_harian_batu2030_bulan_ini) / $stok_volume_akhir_produksi_harian_batu2030_bulan_ini;
		$stok_nilai_akhir_produksi_harian_batu2030_bulan_ini = $stok_volume_akhir_produksi_harian_batu2030_bulan_ini * $stok_harga_akhir_produksi_harian_batu2030_bulan_ini;


		//Abu Batu
		$stok_penjualan_abubatu_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 7")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();
		
		$stok_volume_penjualan_abubatu_bulan_ini = $stok_penjualan_abubatu_bulan_ini['volume'];
		$stok_harga_penjualan_abubatu_bulan_ini = $stok_harga_akhir_produksi_harian_abubatu_bulan_ini;

		$stok_harga_abubatu_fix = round($stok_harga_penjualan_abubatu_bulan_ini,0);
		$stok_nilai_penjualan_abubatu_bulan_ini = $stok_volume_penjualan_abubatu_bulan_ini * $stok_harga_abubatu_fix;

		$stok_volume_akhir_penjualan_abubatu_bulan_ini = $stok_volume_akhir_produksi_harian_abubatu_bulan_ini - $stok_volume_penjualan_abubatu_bulan_ini;
		$stok_volume_akhir_penjualan_abubatu_bulan_ini_fix = round($stok_volume_akhir_penjualan_abubatu_bulan_ini,2);
		$stok_harga_akhir_penjualan_abubatu_bulan_ini = $stok_harga_abubatu_fix;
		$stok_nilai_akhir_penjualan_abubatu_bulan_ini = $stok_volume_akhir_penjualan_abubatu_bulan_ini_fix * $stok_harga_akhir_penjualan_abubatu_bulan_ini;

		//Batu 0,5 - 10
		$stok_penjualan_batu0510_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 8")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$stok_volume_penjualan_batu0510_bulan_ini = $stok_penjualan_batu0510_bulan_ini['volume'];
		$stok_harga_penjualan_batu0510_bulan_ini = round($stok_harga_akhir_produksi_harian_batu0510_bulan_ini,0);
		$stok_nilai_penjualan_batu0510_bulan_ini = $stok_volume_penjualan_batu0510_bulan_ini * $stok_harga_penjualan_batu0510_bulan_ini;

		$stok_volume_akhir_penjualan_batu0510_bulan_ini = round($stok_volume_akhir_produksi_harian_batu0510_bulan_ini - $stok_volume_penjualan_batu0510_bulan_ini,2);
		$stok_harga_akhir_penjualan_batu0510_bulan_ini =  $stok_harga_penjualan_batu0510_bulan_ini;
		$stok_nilai_akhir_penjualan_batu0510_bulan_ini = $stok_volume_akhir_penjualan_batu0510_bulan_ini * $stok_harga_akhir_penjualan_batu0510_bulan_ini;

		//Batu 10 - 20
		$stok_penjualan_batu1020_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 3")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$stok_volume_penjualan_batu1020_bulan_ini = $stok_penjualan_batu1020_bulan_ini['volume'];
		$stok_harga_penjualan_batu1020_bulan_ini = round($stok_harga_akhir_produksi_harian_batu1020_bulan_ini,0);
		$stok_nilai_penjualan_batu1020_bulan_ini = $stok_volume_penjualan_batu1020_bulan_ini * $stok_harga_penjualan_batu1020_bulan_ini;

		$stok_volume_akhir_penjualan_batu1020_bulan_ini = round($stok_volume_akhir_produksi_harian_batu1020_bulan_ini - $stok_volume_penjualan_batu1020_bulan_ini,2);
		$stok_harga_akhir_penjualan_batu1020_bulan_ini = $stok_harga_penjualan_batu1020_bulan_ini;
		$stok_nilai_akhir_penjualan_batu1020_bulan_ini = $stok_volume_akhir_penjualan_batu1020_bulan_ini * $stok_harga_akhir_penjualan_batu1020_bulan_ini;

		//Batu 20 - 30
		$stok_penjualan_batu2030_bulan_ini = $this->db->select('p.nama_produk, pp.convert_measure as satuan, SUM(pp.display_volume) as volume, (pp.display_price / pp.display_volume) as harga, SUM(pp.display_price) as nilai')
		->from('pmm_productions pp')
		->join('pmm_sales_po po', 'pp.salesPo_id = po.id','left')
		->join('produk p', 'pp.product_id = p.id','left')
		->where("pp.date_production between '$date1' and '$date2'")
		->where("pp.product_id = 4")
		->where("po.status in ('OPEN','CLOSED')")
		->where("pp.status = 'PUBLISH'")
		->group_by('pp.product_id')
		->get()->row_array();

		$stok_volume_penjualan_batu2030_bulan_ini = $stok_penjualan_batu2030_bulan_ini['volume'];
		$stok_harga_penjualan_batu2030_bulan_ini = round($stok_harga_akhir_produksi_harian_batu2030_bulan_ini,0);
		$stok_nilai_penjualan_batu2030_bulan_ini = $stok_volume_penjualan_batu2030_bulan_ini * $stok_harga_penjualan_batu2030_bulan_ini;

		$stok_volume_akhir_penjualan_batu2030_bulan_ini = round($stok_volume_akhir_produksi_harian_batu2030_bulan_ini - $stok_volume_penjualan_batu2030_bulan_ini,2);
		$stok_harga_akhir_penjualan_batu2030_bulan_ini = $stok_harga_penjualan_batu2030_bulan_ini;
		$stok_nilai_akhir_penjualan_batu2030_bulan_ini = $stok_volume_akhir_penjualan_batu2030_bulan_ini * $stok_harga_akhir_penjualan_batu2030_bulan_ini;

		//Agregat
		$stok_volume_agregat_abubatu_bulan_ini = $agregat_bulan_ini['volume_agregat_a'];
		$stok_volume_agregat_batu0510_bulan_ini = $agregat_bulan_ini['volume_agregat_b'];
		$stok_volume_agregat_batu1020_bulan_ini = $agregat_bulan_ini['volume_agregat_c'];
		$stok_volume_agregat_batu2030_bulan_ini = $agregat_bulan_ini['volume_agregat_d'];

		$stok_volume_agregat_abubatu_bulan_ini_fix = round($stok_volume_agregat_abubatu_bulan_ini,2);
		$stok_volume_agregat_batu0510_bulan_ini_fix = round($stok_volume_agregat_batu0510_bulan_ini,2);
		$stok_volume_agregat_batu1020_bulan_ini_fix = round($stok_volume_agregat_batu1020_bulan_ini,2);
		$stok_volume_agregat_batu2030_bulan_ini_fix = round($stok_volume_agregat_batu2030_bulan_ini,2);

		$stok_harga_agregat_abubatu_bulan_ini = $stok_harga_akhir_penjualan_abubatu_bulan_ini;
		$stok_harga_agregat_batu0510_bulan_ini = $stok_harga_akhir_penjualan_batu0510_bulan_ini;
		$stok_harga_agregat_batu1020_bulan_ini = $stok_harga_akhir_penjualan_batu1020_bulan_ini;
		$stok_harga_agregat_batu2030_bulan_ini = $stok_harga_akhir_penjualan_batu2030_bulan_ini;

		$stok_nilai_agregat_abubatu_bulan_ini = $stok_volume_agregat_abubatu_bulan_ini_fix * $stok_harga_agregat_abubatu_bulan_ini;
		$stok_nilai_agregat_batu0510_bulan_ini = $stok_volume_agregat_batu0510_bulan_ini_fix * $stok_harga_agregat_batu0510_bulan_ini;
		$stok_nilai_agregat_batu1020_bulan_ini = $stok_volume_agregat_batu1020_bulan_ini_fix * $stok_harga_agregat_batu1020_bulan_ini;
		$stok_nilai_agregat_batu2030_bulan_ini = $stok_volume_agregat_batu2030_bulan_ini_fix * $stok_harga_agregat_batu2030_bulan_ini;

		$stok_volume_akhir_agregat_abubatu_bulan_ini = round($stok_volume_akhir_penjualan_abubatu_bulan_ini - $stok_volume_agregat_abubatu_bulan_ini,2);
		$stok_volume_akhir_agregat_batu0510_bulan_ini = round($stok_volume_akhir_penjualan_batu0510_bulan_ini - $stok_volume_agregat_batu0510_bulan_ini,2);
		$stok_volume_akhir_agregat_batu1020_bulan_ini = round($stok_volume_akhir_penjualan_batu1020_bulan_ini - $stok_volume_agregat_batu1020_bulan_ini,2);
		$stok_volume_akhir_agregat_batu2030_bulan_ini = round($stok_volume_akhir_penjualan_batu2030_bulan_ini - $stok_volume_agregat_batu2030_bulan_ini,2);

		$stok_harga_akhir_agregat_abubatu_bulan_ini = $stok_harga_agregat_abubatu_bulan_ini;
		$stok_harga_akhir_agregat_batu0510_bulan_ini = $stok_harga_agregat_batu0510_bulan_ini;
		$stok_harga_akhir_agregat_batu1020_bulan_ini = $stok_harga_agregat_batu1020_bulan_ini;
		$stok_harga_akhir_agregat_batu2030_bulan_ini = $stok_harga_agregat_batu2030_bulan_ini;

		$stok_nilai_akhir_agregat_abubatu_bulan_ini = $stok_volume_akhir_agregat_abubatu_bulan_ini * $stok_harga_akhir_agregat_abubatu_bulan_ini;
		$stok_nilai_akhir_agregat_batu0510_bulan_ini = $stok_volume_akhir_agregat_batu0510_bulan_ini * $stok_harga_akhir_agregat_batu0510_bulan_ini;
		$stok_nilai_akhir_agregat_batu1020_bulan_ini = $stok_volume_akhir_agregat_batu1020_bulan_ini * $stok_harga_akhir_agregat_batu1020_bulan_ini;
		$stok_nilai_akhir_agregat_batu2030_bulan_ini = $stok_volume_akhir_agregat_batu2030_bulan_ini * $stok_harga_akhir_agregat_batu2030_bulan_ini;

		$stok_volume_agregat_abubatu_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_a'],2);
		$stok_volume_agregat_batu0510_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_b'],2);
		$stok_volume_agregat_batu1020_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_c'],2);
		$stok_volume_agregat_batu2030_bulan_ini_2 = round($agregat_bulan_ini_2['volume_agregat_d'],2);

		$stok_harga_agregat_abubatu_bulan_ini_2 = $stok_harga_agregat_abubatu_bulan_ini;
		$stok_harga_agregat_batu0510_bulan_ini_2 = $stok_harga_agregat_batu0510_bulan_ini;
		$stok_harga_agregat_batu1020_bulan_ini_2 = $stok_harga_agregat_batu1020_bulan_ini;
		$stok_harga_agregat_batu2030_bulan_ini_2 = $stok_harga_agregat_batu2030_bulan_ini;

		$stok_nilai_agregat_abubatu_bulan_ini_2 = $stok_volume_agregat_abubatu_bulan_ini_2 * $stok_harga_agregat_abubatu_bulan_ini_2;
		$stok_nilai_agregat_batu0510_bulan_ini_2 = $stok_volume_agregat_batu0510_bulan_ini_2 * $stok_harga_agregat_batu0510_bulan_ini_2;
		$stok_nilai_agregat_batu1020_bulan_ini_2 = $stok_volume_agregat_batu1020_bulan_ini_2 * $stok_harga_agregat_batu1020_bulan_ini_2;
		$stok_nilai_agregat_batu2030_bulan_ini_2 = $stok_nilai_akhir_agregat_batu2030_bulan_ini * $stok_harga_agregat_batu2030_bulan_ini_2;

		$stock_opname_abu_batu = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();
		
		$stock_opname_batu0510 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030 = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat_2 cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_abu_batu_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 7")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();
		
		$stock_opname_batu0510_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 8")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu1020_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 3")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stock_opname_batu2030_cat = $this->db->select('(cat.volume) as volume')
		->from('pmm_remaining_materials_cat cat ')
		->where("(cat.date = '$date2')")
		->where("cat.material_id = 4")
		->where("cat.reset = 1")
		->where("cat.status = 'PUBLISH'")
		->order_by('date','desc')->limit(1)
		->get()->row_array();

		$stok_volume_akhir_agregat_abubatu_bulan_ini_2 = round($stock_opname_abu_batu['volume'] + $stock_opname_abu_batu_cat['volume'],2);
		$stok_volume_akhir_agregat_batu0510_bulan_ini_2 = round($stock_opname_batu0510['volume'] + $stock_opname_batu0510_cat['volume'],2);
		$stok_volume_akhir_agregat_batu1020_bulan_ini_2 = round($stock_opname_batu1020['volume'] + $stock_opname_batu1020_cat['volume'],2);
		$stok_volume_akhir_agregat_batu2030_bulan_ini_2 = round($stock_opname_batu2030['volume'] + $stock_opname_batu2030_cat['volume'],2);

		$stok_harga_akhir_agregat_abubatu_bulan_ini_2 = $stok_harga_agregat_abubatu_bulan_ini_2;
		$stok_harga_akhir_agregat_batu0510_bulan_ini_2 = $stok_harga_agregat_batu0510_bulan_ini_2;
		$stok_harga_akhir_agregat_batu1020_bulan_ini_2 = $stok_harga_agregat_batu1020_bulan_ini_2;
		$stok_harga_akhir_agregat_batu2030_bulan_ini_2 = $stok_harga_agregat_batu2030_bulan_ini_2;

		$stok_nilai_akhir_agregat_abubatu_bulan_ini_2 = $stok_volume_akhir_agregat_abubatu_bulan_ini_2 * $stok_harga_akhir_agregat_abubatu_bulan_ini_2;
		$stok_nilai_akhir_agregat_batu0510_bulan_ini_2 = $stok_volume_akhir_agregat_batu0510_bulan_ini_2 * $stok_harga_akhir_agregat_batu0510_bulan_ini_2;
		$stok_nilai_akhir_agregat_batu1020_bulan_ini_2 = $stok_volume_akhir_agregat_batu1020_bulan_ini_2 * $stok_harga_akhir_agregat_batu1020_bulan_ini_2;
		$stok_nilai_akhir_agregat_batu2030_bulan_ini_2 = $stok_volume_akhir_agregat_batu2030_bulan_ini_2 * $stok_harga_akhir_agregat_batu2030_bulan_ini_2;

		//TOTAL BAHAN BAKU
		$stok_nilai_opening_bahan_jadi = $stok_nilai_opening_balance_abubatu_bulan_lalu + $stok_nilai_opening_balance_batu0510_bulan_lalu + $stok_nilai_opening_balance_batu1020_bulan_lalu + $stok_nilai_opening_balance_batu2030_bulan_lalu;

		$stok_volume_penjualan_abubatu = $stok_volume_penjualan_abubatu_bulan_ini;
		$stok_nilai_penjualan_abubatu = $stok_nilai_penjualan_abubatu_bulan_ini;
		$stok_harga_penjualan_abubatu = ($stok_volume_penjualan_abubatu!=0)?($stok_nilai_penjualan_abubatu / $stok_volume_penjualan_abubatu)  * 1:0;

		$stok_volume_penjualan_batu0510 = $stok_volume_penjualan_batu0510_bulan_ini;
		$stok_nilai_penjualan_batu0510 =  $stok_nilai_penjualan_batu0510_bulan_ini;
		$stok_harga_penjualan_batu0510 = ($stok_volume_penjualan_batu0510!=0)?($stok_nilai_penjualan_batu0510 / $stok_volume_penjualan_batu0510)  * 1:0;

		$stok_volume_penjualan_batu1020 = $stok_volume_penjualan_batu1020_bulan_ini;
		$stok_nilai_penjualan_batu1020 =  $stok_nilai_penjualan_batu1020_bulan_ini;
		$stok_harga_penjualan_batu1020 = ($stok_volume_penjualan_batu1020!=0)?($stok_nilai_penjualan_batu1020 / $stok_volume_penjualan_batu1020)  * 1:0;

		$stok_volume_penjualan_batu2030 = $stok_volume_penjualan_batu2030_bulan_ini;
		$stok_nilai_penjualan_batu2030 = $stok_nilai_penjualan_batu2030_bulan_ini;
		$stok_harga_penjualan_batu2030 = ($stok_volume_penjualan_batu2030!=0)?($stok_nilai_penjualan_batu2030 / $stok_volume_penjualan_batu2030)  * 1:0;

		//Total
		$stok_total_volume_masuk = $stok_volume_produksi_harian_abubatu_bulan_ini + $stok_volume_produksi_harian_batu0510_bulan_ini + $stok_volume_produksi_harian_batu1020_bulan_ini + $stok_volume_produksi_harian_batu2030_bulan_ini;
		$stok_total_nilai_masuk = $stok_nilai_produksi_harian_abubatu_bulan_ini + $stok_nilai_produksi_harian_batu0510_bulan_ini + $stok_nilai_produksi_harian_batu1020_bulan_ini + $stok_nilai_produksi_harian_batu2030_bulan_ini;

		$stok_total_volume_keluar = $stok_volume_penjualan_abubatu_bulan_ini + $stok_volume_penjualan_batu0510_bulan_ini + $stok_volume_penjualan_batu1020_bulan_ini + $stok_volume_penjualan_batu2030_bulan_ini;
		$stok_total_nilai_keluar = $stok_nilai_penjualan_abubatu_bulan_ini + $stok_nilai_penjualan_batu0510_bulan_ini + $stok_nilai_penjualan_batu1020_bulan_ini + $stok_nilai_penjualan_batu2030_bulan_ini;
		
		$stok_total_volume_akhir = $stok_volume_akhir_agregat_abubatu_bulan_ini_2 + $stok_volume_akhir_agregat_batu0510_bulan_ini_2 + $stok_volume_akhir_agregat_batu1020_bulan_ini_2 + $stok_volume_akhir_agregat_batu2030_bulan_ini_2;
		$stok_total_nilai_akhir = $stok_nilai_akhir_agregat_abubatu_bulan_ini_2 + $stok_nilai_akhir_agregat_batu0510_bulan_ini_2 + $stok_nilai_akhir_agregat_batu1020_bulan_ini_2 + $stok_nilai_akhir_agregat_batu2030_bulan_ini_2;
		
		//EVALUASI

		$evaluasi_volume_abubatu = round($stok_volume_akhir_penjualan_abubatu_bulan_ini - $volume_akhir_agregat_abubatu_bulan_ini_2,2);
		$evaluasi_harga_abubatu = round($stok_harga_akhir_agregat_abubatu_bulan_ini_2,0);
		$evaluasi_nilai_abubatu = round($stok_nilai_akhir_penjualan_abubatu_bulan_ini - $nilai_akhir_agregat_abubatu_bulan_ini_2,0);
		
		$evaluasi_volume_batu0510 = round($stok_volume_akhir_penjualan_batu0510_bulan_ini - $volume_akhir_agregat_batu0510_bulan_ini_2,2);
		$evaluasi_harga_batu0510 = round($stok_harga_akhir_agregat_batu0510_bulan_ini_2,0);
		$evaluasi_nilai_batu0510 = round($stok_nilai_akhir_penjualan_batu0510_bulan_ini - $nilai_akhir_agregat_batu0510_bulan_ini_2,0);

		$evaluasi_volume_batu1020 = round($stok_volume_akhir_penjualan_batu1020_bulan_ini - $volume_akhir_agregat_batu1020_bulan_ini_2,2);
		$evaluasi_harga_batu1020 = round($stok_harga_akhir_agregat_batu1020_bulan_ini_2,0);
		$evaluasi_nilai_batu1020 =  round($stok_nilai_akhir_penjualan_batu1020_bulan_ini - $nilai_akhir_agregat_batu1020_bulan_ini_2,0);

		$evaluasi_volume_batu2030 = round($stok_volume_akhir_penjualan_batu2030_bulan_ini - $volume_akhir_agregat_batu2030_bulan_ini_2,2);
		$evaluasi_harga_batu2030 = round($stok_harga_akhir_agregat_batu2030_bulan_ini_2,0);
		$evaluasi_nilai_batu2030 = round($stok_nilai_akhir_penjualan_batu2030_bulan_ini - $nilai_akhir_agregat_batu2030_bulan_ini_2,0);

		$stok_evaluasi_total_volume_akhir = round($evaluasi_volume_abubatu + $evaluasi_volume_batu0510 + $evaluasi_volume_batu1020 + $evaluasi_volume_batu2030,2);
		$stok_evaluasi_total_nilai_akhir = round($evaluasi_nilai_abubatu + $evaluasi_nilai_batu0510 + $evaluasi_nilai_batu1020 + $evaluasi_nilai_batu2030,0);
		
		?>

		<tr class="table-active4">
			<th class="text-center" rowspan="2">&nbsp;<br>URAIAN</th>
			<th class="text-center" rowspan="2">&nbsp;<br>SATUAN</th>
			<th class="text-center" colspan="3">NILAI PERSEDIAAN AKHIR TERHADAP STOK</th>
			<th class="text-center" colspan="3">NILAI PERSEDIAAN AKHIR TERHADAP RUMUS</th>
			<th class="text-center" colspan="3">EVALUASI</th>
		</tr>
		<tr class="table-active4">
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
			<th class="text-center">VOLUME</th>
			<th class="text-center">HARGA</th>
			<th class="text-center">NILAI</th>
		</tr>
		<?php
			$styleColorA = $evaluasi_volume_abubatu < 0 ? 'color:red' : 'color:black';
			$styleColorB = $evaluasi_nilai_abubatu < 0 ? 'color:red' : 'color:black';
			$styleColorC = $evaluasi_volume_batu0510 < 0 ? 'color:red' : 'color:black';
			$styleColorD = $evaluasi_nilai_batu0510 < 0 ? 'color:red' : 'color:black';
			$styleColorE = $evaluasi_volume_batu1020 < 0 ? 'color:red' : 'color:black';
			$styleColorF = $evaluasi_nilai_batu1020 < 0 ? 'color:red' : 'color:black';
			$styleColorG = $evaluasi_volume_batu2030 < 0 ? 'color:red' : 'color:black';
			$styleColorH = $evaluasi_nilai_batu2030 < 0 ? 'color:red' : 'color:black';
			$styleColorI = $stok_evaluasi_total_volume_akhir < 0 ? 'color:red' : 'color:black';
			$styleColorJ = $stok_evaluasi_total_nilai_akhir < 0 ? 'color:red' : 'color:black';
		?>
		<tr class="table-active3">		
			<th class = "text-left"><i>Batu Split 0,0 - 0,5</i></th>
			<th class = "text-center">Ton</th>
			<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center"><?php echo number_format($volume_akhir_agregat_abubatu_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($harga_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_abubatu_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center" style="<?php echo $styleColorA ?>"><?php echo number_format($evaluasi_volume_abubatu,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($evaluasi_harga_abubatu,0,',','.');?></th>
			<th class = "text-right" style="<?php echo $styleColorB ?>"><?php echo number_format($evaluasi_nilai_abubatu,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class = "text-left"><i>Batu Split 0,5 - 10</i></th>
			<th class = "text-center">Ton</th>
			<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center"><?php echo number_format($volume_akhir_agregat_batu0510_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($harga_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_batu0510_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center" style="<?php echo $styleColorC ?>"><?php echo number_format($evaluasi_volume_batu0510,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($evaluasi_harga_batu0510,0,',','.');?></th>
			<th class = "text-right" style="<?php echo $styleColorD ?>"><?php echo number_format($evaluasi_nilai_batu0510,0,',','.');?></th>
		</tr>
		<tr class="table-active3">
			<th class = "text-left"><i>Batu Split 10 - 20</i></th>
			<th class = "text-center">Ton</th>
			<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center"><?php echo number_format($volume_akhir_agregat_batu1020_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($harga_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_batu1020_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center" style="<?php echo $styleColorE ?>"><?php echo number_format($evaluasi_volume_batu1020,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($evaluasi_harga_batu1020,0,',','.');?></th>
			<th class = "text-right" style="<?php echo $styleColorF ?>"><?php echo number_format($evaluasi_nilai_batu1020,0,',','.');?></th>
		</tr>
		<tr class="table-active3">		
			<th class = "text-left"><i>Batu Split 20 - 30</i></th>
			<th class = "text-center">Ton</th>
			<th class = "text-center"><?php echo number_format($stok_volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($stok_nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center"><?php echo number_format($volume_akhir_agregat_batu2030_bulan_ini_2,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($harga_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class = "text-right"><?php echo number_format($nilai_akhir_agregat_batu2030_bulan_ini_2,0,',','.');?></th>
			<th class = "text-center" style="<?php echo $styleColorG ?>"><?php echo number_format($evaluasi_volume_batu2030,2,',','.');?></th>
			<th class = "text-right"><?php echo number_format($evaluasi_harga_batu2030,0,',','.');?></th>
			<th class = "text-right" style="<?php echo $styleColorH ?>"><?php echo number_format($evaluasi_nilai_batu2030,0,',','.');?></th>
		</tr>
		<tr class="table-active5">
			<th class = "text-center" colspan="2">TOTAL</th>
			<th class = "text-center"><?php echo number_format($stok_total_volume_akhir,2,',','.');?></th>
			<th class = "text-right">-</th>
			<th class = "text-right"><?php echo number_format($stok_total_nilai_akhir,0,',','.');?></th>
			<th class = "text-center"><?php echo number_format($total_volume_akhir,2,',','.');?></th>
			<th class = "text-right">-</th>
			<th class = "text-right"><?php echo number_format($total_nilai_akhir,0,',','.');?></th>
			<th class = "text-center" style="<?php echo $styleColorI ?>"><?php echo number_format($stok_evaluasi_total_volume_akhir,2,',','.');?></th>
			<th class = "text-right">-</th>
			<th class = "text-right" style="<?php echo $styleColorJ ?>"><?php echo number_format($stok_evaluasi_total_nilai_akhir,0,',','.');?></th>
		</tr>
	    </table>
		
		<?php
	}
	
	public function beban_pokok_produksi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		<style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
				color: black;
			}
				
			table tr.table-active2{
				background-color: #A9A9A9;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 11px;
				background-color: #F0F0F0;
			}
				
			table tr.table-active4{
				background-color: #e69500;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
			table tr.table-active5{
				background-color: #E8E8E8;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
		 </style>
	        <tr class="table-active4">
	            <th colspan="3">Periode</th>
	            <th class="text-center" colspan="3"><?php echo $filter_date;?></th>
	        </tr>
			
			<?php

			//PERGERAKAN BAHAN BAKU

			$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar, pp.total_nilai_keluar_2 as total_nilai_keluar_2, pp.bpp as bpp')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->result_array();

			$total_akumulasi_bahan_baku = 0;
			$total_akumulasi_bahan_baku_2 = 0;
			$total_bpp = 0;
			

			foreach ($akumulasi_bahan_baku as $b){
				$total_akumulasi_bahan_baku += $b['total_nilai_keluar'];
				$total_akumulasi_bahan_baku_2 += $b['total_nilai_keluar_2'];
				$total_bpp = $b['bpp'];
			}

			$akumulasi_nilai_bahan_baku = $total_akumulasi_bahan_baku;
			$akumulasi_nilai_bahan_baku_2 = $total_akumulasi_bahan_baku_2;
			$akumulasi_nilai_bahan_baku_bpp = $total_bpp;
			
			//Opening Balance
			$date1_ago = date('2020-01-01');
			$date2_ago = date('Y-m-d', strtotime('-1 days', strtotime($date1)));
			$date3_ago = date('Y-m-d', strtotime('-1 months', strtotime($date1)));
			$tanggal_opening_balance = date('Y-m-d', strtotime('-1 days', strtotime($date1)));

			$stock_opname_batu_boulder_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 15")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu_boulder_ago_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 15")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$harga_hpp_bahan_baku = $this->db->select('pp.date_hpp, pp.boulder, pp.bbm')
			->from('hpp_bahan_baku pp')
			->where("(pp.date_hpp between '$date3_ago' and '$date2_ago')")
			->order_by('pp.date_hpp','desc')->limit(1)
			->get()->row_array();

			$volume_opening_balance = $stock_opname_batu_boulder_ago['volume'] + $stock_opname_batu_boulder_ago_2['volume'];
			$harga_opening_balance = $harga_hpp_bahan_baku['boulder'];
			$nilai_opening_balance = $volume_opening_balance * $harga_opening_balance;

			$stock_opname_solar_ago = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 13")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_solar_ago_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$tanggal_opening_balance')")
			->where("cat.material_id = 13")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$volume_opening_balance_solar = $stock_opname_solar_ago['volume'] + $stock_opname_solar_ago_2['volume'];	
			$harga_opening_balance_solar = $harga_hpp_bahan_baku['bbm'];
			$nilai_opening_balance_solar = $volume_opening_balance_solar * $harga_opening_balance_solar;

			//Now
			//Bahan Baku			
			$pergerakan_bahan_baku = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			(prm.display_price / prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 15")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian = $pergerakan_bahan_baku['volume'];
			$total_nilai_pembelian =  $pergerakan_bahan_baku['nilai'];
			$total_harga_pembelian = ($total_volume_pembelian!=0)?$total_nilai_pembelian / $total_volume_pembelian * 1:0;

			$total_volume_pembelian_akhir  = round($volume_opening_balance + $total_volume_pembelian,2);
			$total_harga_pembelian_akhir = ($total_volume_pembelian_akhir!=0)?($nilai_opening_balance + $total_nilai_pembelian) / $total_volume_pembelian_akhir * 1:0;
			$total_nilai_pembelian_akhir =  $total_volume_pembelian_akhir * $total_harga_pembelian_akhir;			
			
			$stock_opname_batu_boulder = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 15")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_batu_boulder_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 15")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();
			
			$total_volume_produksi_akhir = $stock_opname_batu_boulder['volume'] + $stock_opname_batu_boulder_2['volume'];
			$total_harga_produksi_akhir = round($total_harga_pembelian_akhir,0);
			$total_nilai_produksi_akhir = $total_volume_produksi_akhir * $total_harga_produksi_akhir;

			$total_volume_produksi = round($total_volume_pembelian_akhir - $total_volume_produksi_akhir,2) * $akumulasi_nilai_bahan_baku_bpp;
			$total_nilai_produksi = $akumulasi_nilai_bahan_baku;
			$total_harga_produksi = ($total_volume_produksi!=0)?($total_nilai_produksi / $total_volume_produksi)  * 1:0;

			//BBM Solar
			$pergerakan_bahan_baku_solar = $this->db->select('
			p.nama_produk, 
			prm.display_measure as satuan, 
			SUM(prm.display_volume) as volume, 
			(prm.display_price / prm.display_volume) as harga, 
			SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("prm.date_receipt between '$date1' and '$date2'")
			->where("prm.material_id = 13")
			->group_by('prm.material_id')
			->get()->row_array();
			
			$total_volume_pembelian_solar = $pergerakan_bahan_baku_solar['volume'];
			$total_nilai_pembelian_solar =  $pergerakan_bahan_baku_solar['nilai'];
			$total_harga_pembelian_solar = ($total_volume_pembelian_solar!=0)?$total_nilai_pembelian_solar / $total_volume_pembelian_solar * 1:0;

			$total_volume_pembelian_akhir_solar  = round($volume_opening_balance_solar + $total_volume_pembelian_solar,2);
			$total_harga_pembelian_akhir_solar = ($total_volume_pembelian_akhir_solar!=0)?($nilai_opening_balance_solar + $total_nilai_pembelian_solar) / $total_volume_pembelian_akhir_solar * 1:0;
			$total_nilai_pembelian_akhir_solar =  $total_volume_pembelian_akhir_solar * $total_harga_pembelian_akhir_solar;

			$stock_opname_solar = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 13")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$stock_opname_solar_2 = $this->db->select('(cat.volume) as volume')
			->from('pmm_remaining_materials_cat_2 cat ')
			->where("(cat.date = '$date2')")
			->where("cat.material_id = 13")
			->where("cat.reset = 1")
			->where("cat.status = 'PUBLISH'")
			->order_by('date','desc')->limit(1)
			->get()->row_array();

			$total_volume_produksi_akhir_solar = $stock_opname_solar['volume'] + $stock_opname_solar_2['volume'];
			$total_harga_produksi_akhir_solar = round($total_harga_pembelian_akhir_solar,0);
			$total_nilai_produksi_akhir_solar = $total_volume_produksi_akhir_solar * $total_harga_produksi_akhir_solar;

			$total_volume_produksi_solar = round($total_volume_pembelian_akhir_solar - $total_volume_produksi_akhir_solar,2) * $akumulasi_nilai_bahan_baku_bpp;
			$total_nilai_produksi_solar =  $akumulasi_nilai_bahan_baku_2;
			$total_harga_produksi_solar = ($total_volume_produksi_solar!=0)?($total_nilai_produksi_solar / $total_volume_produksi_solar)  * 1:0;

			//Total Opening Balance
			$opening_balance_bahan_baku = $nilai_opening_balance + $nilai_opening_balance_solar;

			//Total
			$total_nilai_masuk = $total_nilai_pembelian + $total_nilai_pembelian_solar;
			$total_nilai_keluar = $total_nilai_produksi + $total_nilai_produksi_solar;
			$total_nilai_akhir = $total_nilai_produksi_akhir + $total_nilai_produksi_akhir_solar;
			//END PERGERAKAN BAHAN BAKU

			//PERALATAN & OPERASIONAL
			$abu_batu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
			->from('pmm_produksi_harian pph ')
			->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
			->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')	
			->where("(pph.date_prod between '$date1' and '$date2')")
			->where("pph.status = 'PUBLISH'")
			->get()->row_array();

			$total_abu_batu = 0;
			$nilai_abu_batu1 = 0;
			$nilai_abu_batu2 = 0;
			$nilai_abu_batu3 = 0;
			$nilai_abu_batu4 = 0;
			$nilai_abu_batu5 = 0;
			$nilai_abu_batu_all = 0;

			$total_abu_batu = $abu_batu['jumlah_pemakaian_a'] + $abu_batu['jumlah_pemakaian_b'] + $abu_batu['jumlah_pemakaian_c'] + $abu_batu['jumlah_pemakaian_d'] + $abu_batu['jumlah_pemakaian_e'];
			$nilai_abu_batu1 = $abu_batu['jumlah_pemakaian_a'] * $total_harga_produksi_akhir;
			$nilai_abu_batu2 = $abu_batu['jumlah_pemakaian_b'] * $total_harga_produksi_akhir;
			$nilai_abu_batu3 = $abu_batu['jumlah_pemakaian_c'] * $total_harga_produksi_akhir;
			$nilai_abu_batu4 = $abu_batu['jumlah_pemakaian_d'] * $total_harga_produksi_akhir;
			$nilai_abu_batu5 = $abu_batu['jumlah_pemakaian_e'] * $total_harga_produksi_akhir;
			$nilai_abu_batu_all = $nilai_abu_batu1 + $nilai_abu_batu2 + $nilai_abu_batu3 + $nilai_abu_batu4 + $nilai_abu_batu5;

			$nilai_abu_batu_total = $abu_batu['jumlah_used'] * $total_harga_pembelian;
			
			$stone_crusher_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 101")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$stone_crusher_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 101")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$stone_crusher = $stone_crusher_biaya['total'] + $stone_crusher_jurnal['total'];
			
			$whell_loader_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 104")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$whell_loader_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 104")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$whell_loader = $whell_loader_biaya['total'] + $whell_loader_jurnal['total'];
			
			$excavator = $this->db->select('sum(prm.display_price) as price')
			->from('pmm_receipt_material prm ')
			->where("prm.material_id = 18")
			->where("(prm.date_receipt between '$date1' and '$date2')")
			->get()->row_array();
			
			$genset_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 197")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$genset_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 197")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$genset = $genset_biaya['total'] + $genset_jurnal['total'];
			
			$timbangan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 198")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$timbangan_biaya_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 198")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$timbangan = $timbangan_biaya['total'] + $timbangan_biaya_jurnal['total'];
			
			$tangki_solar_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 207")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$tangki_solar_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 207")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$tangki_solar = $tangki_solar_biaya['total'] + $tangki_solar_jurnal['total'];		
			
			$total_biaya_peralatan = $stone_crusher + $whell_loader + $excavator['price'] + $genset + $timbangan + $tangki_solar;
			$hpp_peralatan = ($total_abu_batu!=0)?($total_biaya_peralatan / $total_abu_batu)  * 1:0;
			
			$gaji_upah_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun in (199,200)")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$gaji_upah_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun in (199,200)")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$gaji_upah = $gaji_upah_biaya['total'] + $gaji_upah_jurnal['total'];
			
			$konsumsi_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 201")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$konsumsi_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 201")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$konsumsi = $konsumsi_biaya['total'] + $konsumsi_jurnal['total'];
			
			$thr_bonus_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 202")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$thr_bonus_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 202")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$thr_bonus = $thr_bonus_biaya['total'] + $thr_bonus_jurnal['total'];
			
			$perbaikan_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 203")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$perbaikan_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 203")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			
			$perbaikan = $perbaikan_biaya['total'] + $perbaikan_jurnal['total'];

			$akomodasi_tamu_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 204")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$akomodasi_tamu_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 204")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$akomodasi_tamu = $akomodasi_tamu_biaya['total'] + $akomodasi_tamu_jurnal['total'];
			
			$pengujian_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 205")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$pengujian_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 205")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$pengujian = $pengujian_biaya['total'] + $pengujian_jurnal['total'];
			
			$listrik_internet_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->where("pdb.akun = 206")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$listrik_internet_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 206")
			->where("status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();
			
			$listrik_internet = $listrik_internet_biaya['total'] + $listrik_internet_jurnal['total'];

			$total_operasional = $gaji_upah + $konsumsi + $thr_bonus + $perbaikan + $akomodasi_tamu + $pengujian + $listrik_internet;
			$hpp_operasional = ($total_abu_batu!=0)?($total_operasional / $total_abu_batu)  * 1:0;
			$total_bpp = $total_nilai_produksi + $total_nilai_produksi_solar + $total_biaya_peralatan + $total_operasional;
			$harga_bpp = ($total_abu_batu!=0)?($total_bpp / $total_abu_batu)  * 1:0;
			
			$harga_pemakaian_a = 0;
			$harga_pemakaian_b = 0;
			$harga_pemakaian_c = 0;
			$harga_pemakaian_d = 0;
			$total_harga_pemakaian = 0;
			
			$harga_pemakaian_a = $harga_bpp * $abu_batu['jumlah_pemakaian_a'];
			$harga_pemakaian_b = $harga_bpp * $abu_batu['jumlah_pemakaian_b'];
			$harga_pemakaian_c = $harga_bpp * $abu_batu['jumlah_pemakaian_c'];
			$harga_pemakaian_d = $harga_bpp * $abu_batu['jumlah_pemakaian_d'];
			
			$total_harga_pemakaian = $harga_pemakaian_a + $harga_pemakaian_b + $harga_pemakaian_c + $harga_pemakaian_d;
			//END PERALATAN & OPERASIONAL
			
	        ?>
			
			<tr class="table-active">
	            <th width="5%" class="text-center">No.</th>
				<th width="35%" class="text-center">Uraian</th>
				<th width="15%" class="text-center">Satuan</th>
	            <th width="15%" class="text-center">Volume</th>
				<th width="15%" class="text-center">Harga Satuan</th>
				<th width="15%" class="text-center">Total</th>
	        </tr>
			<tr class="table-active">
	            <th class="text-center">1.</th>
				<th class="text-left" colspan="5">Bahan Baku</th>
	        </tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Total Pemakaian Bahan Baku</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($total_volume_produksi,2,',','.');?></th>
	            <th class="text-center"><?php echo number_format($total_harga_produksi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Total Pemakaian BBM Solar</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Liter</th>
				<th class="text-center"><?php echo number_format($total_volume_produksi_solar,2,',','.');?></th>
	            <th class="text-center"><?php echo number_format($total_harga_produksi_solar,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_nilai_produksi_solar,0,',','.');?></th>
			</tr>
			<tr class="table-active">
	            <th class="text-center">2.</th>
				<th class="text-left" colspan="5">Peralatan</th>
	        </tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Stone Crusher</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($stone_crusher,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Whell Loader</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($whell_loader,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Excavator</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($excavator['price'],0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Genset</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($genset,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Timbangan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($timbangan,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Tangki Solar</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($tangki_solar,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Total Biaya Peralatan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center"></th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_biaya_peralatan,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
				<th>
				<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>HPP Peralatan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">/Ton</th>
				<th colspan="2"></th>
				<th class="text-right"><?php echo number_format($hpp_peralatan,0,',','.');?></th>
			</tr>
			<tr class="table-active">
	            <th class="text-center">3.</th>
				<th class="text-left" colspan="5">Operasional</th>
	        </tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Gaji/Upah</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($gaji_upah,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Konsumsi</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($konsumsi,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>THR & Bonus</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($thr_bonus,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Perbaikan dan Pemeliharaan</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($perbaikan,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Akomodasi Tamu</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($akomodasi_tamu,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Pengujian Material dan Laboratorium</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($pengujian,0,',','.');?></th>
			</tr>
			<tr class="table-active3">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Listrik & Internet</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($listrik_internet,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
	            <th>
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>Total Biaya Operasional</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">Ls</th>
				<th></th>
	            <th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_operasional,0,',','.');?></th>
			</tr>
			<tr class="table-active5">
				<th></th>
				<th>
				<table width="100%" border="0" cellpadding="0">
						<tr>
								<th align="left" width="5%">
									<span>&nbsp;</span>
								</th>
								<th align="left" width="95%">
									<span>HPP Operasional</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-center">/Ton</th>
				<th colspan="2"></th>
				<th class="text-right"><?php echo number_format($hpp_operasional,0,',','.');?></th>
			</tr>
			<tr class="table-active4">
	            <th colspan="5">HPP/Total Beban Pokok Produksi</th>
				<th class="text-right"><?php echo number_format($total_bpp,0,',','.');?></th>
	        </tr>
			<tr class="table-active4">
	            <th colspan="5">Harga Pokok Produksi</th>
				<th class="text-right"><?php echo number_format($harga_bpp,0,',','.');?></th>
	        </tr>
	    </table>
		<?php
	}


	public function laba_rugi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date3 = '';
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date3 	= date('2021-01-01',strtotime($date3));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active2{
				background-color: #A9A9A9;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
			}
				
			table tr.table-active4{
				background-color: #D3D3D3;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
		 </style>
	        <tr class="table-active4">
	            <th colspan="2">PERIODE</th>
				<th class="text-center"></th>
				<th class="text-center"><?php echo $filter_date = $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
	            <th class="text-center">SD. <?php echo $filter_date_2 = date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
	        </tr>

			<?php
			//AKUMULASI
			$akumulasi = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, SUM(pp.total_nilai_keluar_2) as total_2, SUM(pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi_hpp = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date1' and '$date2')")
			->get()->row_array();
			
			//PENJUALAN
			$penjualan_limbah = $this->db->select('SUM(pp.display_price) as price')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 9 ")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();

			$total_penjualan_limbah = 0;
			foreach ($penjualan_limbah as $y){
				$total_penjualan_limbah += $y['price'];
			}

			$penjualan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id in (3,4,7,8,14,24)")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();
			
			$total_penjualan = 0;
			$total_volume = 0;
			$measure = 0;

			foreach ($penjualan as $x){
				$total_penjualan += $x['price'];
				$total_volume += $x['volume'];
			}

			$total_penjualan_all = 0;
			$total_penjualan_all = $total_penjualan + $total_penjualan_limbah;
			
			$biaya_umum_administratif_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif = $biaya_umum_administratif_biaya['total'] + $biaya_umum_administratif_jurnal['total'];
			$biaya_lainnya = $biaya_lainnya_biaya['total'] + $biaya_lainnya_jurnal['total'];

			$total_harga_pokok_pendapatan = $akumulasi_hpp['total'];
			$laba_kotor = $total_penjualan_all - $total_harga_pokok_pendapatan;
			$total_biaya = $biaya_umum_administratif + $biaya_lainnya;
			$laba_usaha = $laba_kotor - $total_biaya;
			$nilai_persediaan_bahan_baku = $akumulasi['total_akhir'];
			$nilai_persediaan_barang_jadi = $akumulasi2['total_akhir'];
			$total = $laba_usaha + $nilai_persediaan_bahan_baku + $nilai_persediaan_barang_jadi;
			$persentase = ($total_penjualan_all!=0)?($total / $total_penjualan_all)  * 100:0;

			//AKUMULASI 2
			$akumulasi_2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, SUM(pp.total_nilai_keluar_2) as total_2, SUM(pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi2_2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi_hpp_2 = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi between '$date3' and '$date2')")
			->get()->row_array();

			//AKUMULASI PENJUALAN
			$penjualan_limbah_2 = $this->db->select('SUM(pp.display_price) as price')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date3' and '$date2'")
			->where("pp.product_id = 9 ")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();

			$total_penjualan_limbah_2 = 0;
			foreach ($penjualan_limbah_2 as $y){
				$total_penjualan_limbah_2 += $y['price'];
			}

			$penjualan_2 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date3' and '$date2'")
			->where("pp.product_id in (3,4,7,8,14,24)")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();
			
			$total_penjualan_2 = 0;
			$total_volume_2 = 0;

			foreach ($penjualan_2 as $x){
				$total_penjualan_2 += $x['price'];
				$total_volume_2 += $x['volume'];
			}

			$total_penjualan_all_2 = 0;
			$total_penjualan_all_2 = $total_penjualan_2 + $total_penjualan_limbah_2;

			$biaya_umum_administratif_biaya_2 = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_jurnal_2 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_biaya_2 = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_jurnal_2 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_2 = $biaya_umum_administratif_biaya_2['total'] + $biaya_umum_administratif_jurnal_2['total'];
			$biaya_lainnya_2 = $biaya_lainnya_biaya_2['total'] + $biaya_lainnya_jurnal_2['total'];
		
			$total_harga_pokok_pendapatan_2 = $akumulasi_hpp_2['total'];
			$laba_kotor_2 = $total_penjualan_all_2 - $total_harga_pokok_pendapatan_2;
			$total_biaya_2 = $biaya_umum_administratif_2 + $biaya_lainnya_2;
			$laba_usaha_2 = $laba_kotor_2 - $total_biaya_2;
			$nilai_persediaan_bahan_baku_2 = $akumulasi_2['total_akhir'];
			$nilai_persediaan_barang_jadi_2 = $akumulasi2_2['total_akhir'];
			$total_2 = $laba_usaha_2 + $nilai_persediaan_bahan_baku_2 + $nilai_persediaan_barang_jadi_2;
			$persentase_2 = ($total_penjualan_all_2!=0)?($total_2 / $total_penjualan_all_2)  * 100:0;
	        ?>

			<tr class="table-active">
	            <th width="100%" class="text-left" colspan="5">Pendapatan Penjualan</th>
	        </tr>
			<tr class="table-active3">
	            <th width="10%" class="text-center"></th>
				<th width="90%" class="text-left" colspan="4">Pendapatan</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left"></th>
				<th class="text-left"></th>
				<th class="text-right"><?php echo number_format($total_volume,2,',','.');?></th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_penjualan_all,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_penjualan_all_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left" colspan="2">Total Pendapatan</th>
				<th class="text-right"><?php echo number_format($total_volume,2,',','.');?></th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_penjualan_all,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_penjualan_all_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
				<th class="text-left" colspan="5">Beban Pokok Penjualan</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"></th>
				<th class="text-left" colspan="2">Beban Pokok Penjualan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left" colspan="3">Total Beban Pokok Penjualan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan_2,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $laba_kotor < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $laba_kotor_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
				<th class="text-left" colspan="3">Laba / Rugi Kotor</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_kotor < 0 ? "(".number_format(-$laba_kotor,0,',','.').")" : number_format($laba_kotor,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_kotor_2 < 0 ? "(".number_format(-$laba_kotor_2,0,',','.').")" : number_format($laba_kotor_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
	            <th class="text-left" colspan="3">Biaya Umum & Administratif</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_umum_administratif?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_umum_administratif,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_umum_administratif?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_umum_administratif_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
			</tr>
			<tr class="table-active">
	            <th class="text-left" colspan="3">Biaya Lainnya</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_lainnya?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_lainnya,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_lainnya?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_lainnya_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
			</tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $laba_usaha < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $laba_usaha_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Laba / Rugi Usaha</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_usaha < 0 ? "(".number_format(-$laba_usaha,0,',','.').")" : number_format($laba_usaha,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_usaha_2 < 0 ? "(".number_format(-$laba_usaha_2,0,',','.').")" : number_format($laba_usaha_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
	            <th colspan="3" class="text-left">Persediaan Bahan Baku</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_bahan_baku,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_bahan_baku_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active">
	            <th colspan="3" class="text-left">Persediaan Barang Jadi</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_barang_jadi,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_barang_jadi_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $total < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $total_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Total</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $total < 0 ? "(".number_format(-$total,0,',','.').")" : number_format($total,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $total_2 < 0 ? "(".number_format(-$total_2,0,',','.').")" : number_format($total_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<?php
				$styleColor = $persentase < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $persentase_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Presentase</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $persentase < 0 ? "(".number_format(-$persentase,0,',','.').")" : number_format($persentase,0,',','.');?> %</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $persentase_2 < 0 ? "(".number_format(-$persentase_2,0,',','.').")" : number_format($persentase_2,0,',','.');?> %</span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
	    </table>
		<?php
	}

	public function laba_rugi_new($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date3 = '';
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date3 	= date('2022-01-01',strtotime($date3));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
		 <style type="text/css">
			table tr.table-active{
				background-color: #F0F0F0;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active2{
				background-color: #A9A9A9;
				font-size: 12px;
				font-weight: bold;
			}
				
			table tr.table-active3{
				font-size: 12px;
			}
				
			table tr.table-active4{
				background-color: #D3D3D3;
				font-weight: bold;
				font-size: 12px;
				color: black;
			}
		 </style>
	        <tr class="table-active4">
	            <th colspan="2">PERIODE</th>
				<th class="text-center"></th>
				<th class="text-center"><?php echo $filter_date = $filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
	            <th class="text-center">SD. <?php echo $filter_date_2 = date('d/m/Y',strtotime($arr_filter_date[1]));?></th>
	        </tr>

			<?php
			$penjualan_limbah = $this->db->select('SUM(pp.display_price) as price')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id = 9 ")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();

			$total_penjualan_limbah = 0;
			foreach ($penjualan_limbah as $y){
				$total_penjualan_limbah += $y['price'];
			}

			$penjualan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date1' and '$date2'")
			->where("pp.product_id in (3,4,7,8,14,24)")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();
			
			$total_penjualan = 0;
			$total_volume = 0;
			$measure = 0;

			foreach ($penjualan as $x){
				$total_penjualan += $x['price'];
				$total_volume += $x['volume'];
			}

			$total_penjualan_all = 0;
			$total_penjualan_all = $total_penjualan + $total_penjualan_limbah;

			//HARGA RAP
			$row = $this->db->select('*')
			->from('rap')
			->order_by('id','desc')->limit(1)
			->get()->row_array();

			$penyusutan_tangki = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '23'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_tangki = (($penyusutan_tangki['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_sc = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '16'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_sc = (($penyusutan_sc['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_gns = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '19'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_gns = (($penyusutan_gns['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_wl = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '17'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_wl = (($penyusutan_wl['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_timbangan = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '39'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_timbangan = (($penyusutan_timbangan['nilai_penyusutan'] / 48) / 25) / 7;

			//M3
			$berat_isi_boulder = 1/$row['berat_isi_boulder'];
			$harsat_boulder = $row['price_boulder'] / $berat_isi_boulder;
			$nilai_boulder = $harsat_boulder * $row['vol_boulder'];
			//Ton
			$vol_boulder = $row['vol_boulder'];
			$nilai_boulder_ton = $vol_boulder * $row['price_boulder'];
			
			//M3
			$sc_a = $row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc'];
			$sc_b = $sc_a / $row['berat_isi_batu_pecah'];
			$vol_sc = 1 / $sc_b;
			$nilai_sc = $vol_sc * $penyusutan_sc;
			//Ton
			$vol_sc_ton = 1 / $sc_a;
			$nilai_sc_ton = $vol_sc_ton * $penyusutan_sc;
			
			//M3
			$vol_tangki = $vol_sc;
			$nilai_tangki = $vol_tangki * $penyusutan_tangki;
			//Ton
			$vol_tangki_ton = $vol_sc_ton;
			$nilai_tangki_ton = $vol_tangki_ton * $penyusutan_tangki;
			
			//M3
			$vol_gns = $vol_sc;
			$nilai_gns = $vol_gns * $penyusutan_gns;
			//Ton
			$vol_gns_ton = $vol_sc_ton;
			$nilai_gns_ton = $vol_gns_ton * $penyusutan_gns;

			//M3
			$wl_a = $row['kapasitas_alat_wl'] * $row['efisiensi_alat_wl'];
			$wl_b = (60 / $row['waktu_siklus']) * $wl_a;
			$vol_wl = 1 / $wl_b;
			$nilai_wl = $vol_wl * $penyusutan_wl;
			//Ton
			$vol_wl_ton_rumus = (($wl_a / $row['waktu_siklus']) * 60) * $row['berat_isi_batu_pecah'];
			$vol_wl_ton = 1 / $vol_wl_ton_rumus;
			$nilai_wl_ton = $vol_wl_ton * $penyusutan_wl;

			//M3
			$vol_timbangan =  $vol_sc;
			$nilai_timbangan = $vol_timbangan * $penyusutan_timbangan;
			//Ton
			$vol_timbangan_ton = $vol_sc_ton;
			$nilai_timbangan_ton = $vol_timbangan_ton * $penyusutan_timbangan;

			//Ton
			$vol_bbm_solar_ton = $row['vol_bbm_solar'];
			$nilai_bbm_solar_ton = $vol_bbm_solar_ton * $row['price_bbm_solar'];

			//M3
			$vol_bbm_solar =  $vol_bbm_solar_ton * $row['berat_isi_boulder'];
			$nilai_bbm_solar = $vol_bbm_solar * $row['price_bbm_solar'];

			$rumus_overhead = ($row['overhead'] / 25) / 8;
			$rumus_overhead_1 = ($row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc']) / $row['berat_isi_batu_pecah'] ;
			//$overhead = $rumus_overhead / $rumus_overhead_1;

			$rumus_overhead_ton = $row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc'];
			$overhead_ton = $rumus_overhead / $rumus_overhead_ton;
			$overhead = $overhead_ton;

			$total = $nilai_boulder + $nilai_tangki + $nilai_sc + $nilai_gns + $nilai_wl + $nilai_timbangan + $overhead;
			$total_ton = $nilai_boulder_ton + $nilai_tangki_ton + $nilai_sc_ton + $nilai_gns_ton + $nilai_wl_ton + $nilai_timbangan_ton + $overhead_ton;
			//HARGA RAP

			$nilai_rap = $total_volume * $total;
			$total_2 = $total;
			
			$biaya_umum_administratif_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_biaya = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_jurnal = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date1' and '$date2')")
			->get()->row_array();

			$akumulasi_bahan_baku = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, SUM(pp.total_nilai_keluar_2) as total_2, SUM(pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi_bahan_jadi = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$biaya_umum_administratif = $biaya_umum_administratif_biaya['total'] + $biaya_umum_administratif_jurnal['total'];
			$biaya_lainnya = $biaya_lainnya_biaya['total'] + $biaya_lainnya_jurnal['total'];

			$total_harga_pokok_pendapatan = $nilai_rap;
			$laba_kotor = $total_penjualan_all - $total_harga_pokok_pendapatan;
			$laba_usaha = $laba_kotor - ($biaya_umum_administratif + $biaya_lainnya);
			$nilai_persediaan_bahan_baku = $akumulasi_bahan_baku['total_akhir'];
			$nilai_persediaan_barang_jadi = $akumulasi_bahan_jadi['total_akhir'];
			$total = $laba_usaha + $nilai_persediaan_bahan_baku + $nilai_persediaan_barang_jadi;
			$persentase = ($total_penjualan_all!=0)?($total / $total_penjualan_all)  * 100:0;

			//AKUMULASI 2
			$penjualan_limbah_2 = $this->db->select('SUM(pp.display_price) as price')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date3' and '$date2'")
			->where("pp.product_id = 9 ")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();

			$total_penjualan_limbah_2 = 0;
			foreach ($penjualan_limbah_2 as $y){
				$total_penjualan_limbah_2 += $y['price'];
			}

			$penjualan_2 = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
			->from('pmm_productions pp')
			->join('penerima p', 'pp.client_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("pp.date_production between '$date3' and '$date2'")
			->where("pp.product_id in (3,4,7,8,14,24)")
			->where("pp.status = 'PUBLISH'")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.client_id")
			->get()->result_array();
			
			$total_penjualan_2 = 0;
			$total_volume_2 = 0;

			foreach ($penjualan_2 as $x){
				$total_penjualan_2 += $x['price'];
				$total_volume_2 += $x['volume'];
			}

			$total_penjualan_all_2 = 0;
			$total_penjualan_all_2 = $total_penjualan_2 + $total_penjualan_limbah_2;

			$nilai_rap_2 = $total_volume_2 * $total_2;

			$biaya_umum_administratif_biaya_2 = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_umum_administratif_jurnal_2 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',16)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_biaya_2 = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$biaya_lainnya_jurnal_2 = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',17)
			->where("pb.status = 'PAID'")
			->where("(tanggal_transaksi between '$date3' and '$date2')")
			->get()->row_array();

			$akumulasi_bahan_baku_2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, SUM(pp.total_nilai_keluar_2) as total_2, SUM(pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi_bahan_baku pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$akumulasi_bahan_jadi_2 = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi = '$date2')")
			->order_by('pp.date_akumulasi','desc')->limit(1)
			->get()->row_array();

			$biaya_umum_administratif_2 = $biaya_umum_administratif_biaya_2['total'] + $biaya_umum_administratif_jurnal_2['total'];
			$biaya_lainnya_2 = $biaya_lainnya_biaya_2['total'] + $biaya_lainnya_jurnal_2['total'];

			$total_harga_pokok_pendapatan_2 = $nilai_rap_2;
			$laba_kotor_2 = $total_penjualan_all_2 - $total_harga_pokok_pendapatan_2;
			$laba_usaha_2 = $laba_kotor_2 - ($biaya_umum_administratif_2 + $biaya_lainnya_2);
			$nilai_persediaan_bahan_baku_2 = $akumulasi_bahan_baku_2['total_akhir'];
			$nilai_persediaan_barang_jadi_2 = $akumulasi_bahan_jadi_2['total_akhir'];
			$total_2 = $laba_usaha_2 + $nilai_persediaan_bahan_baku_2 + $nilai_persediaan_barang_jadi_2;
			$persentase_2 = ($total_penjualan_all_2!=0)?($total_2 / $total_penjualan_all_2)  * 100:0;
	        ?>

			<tr class="table-active">
	            <th width="100%" class="text-left" colspan="5">Pendapatan Penjualan</th>
	        </tr>
			<tr class="table-active3">
	            <th width="10%" class="text-center"></th>
				<th width="90%" class="text-left" colspan="4">Pendapatan</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left" colspan="2">Total Pendapatan</th>
				<th class="text-right"><?php echo number_format($total_volume,2,',','.');?></th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_penjualan_all,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_penjualan_all_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
				<th class="text-left" colspan="5">BEBAN POKOK PENJUALAN</th>
	        </tr>
			<tr class="table-active3">
	            <th class="text-center"></th>
				<th class="text-left" colspan="2">Beban Pokok Penjualan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_rap,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_rap_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-left" colspan="3">Total Beban Pokok Penjualan</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left"width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($total_harga_pokok_pendapatan_2,0,',','.');?></span>
								</th>
							</tr>
					</table>				
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $laba_kotor < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $laba_kotor_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
				<th class="text-left" colspan="3">Laba / Rugi Kotor</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_kotor < 0 ? "(".number_format(-$laba_kotor,0,',','.').")" : number_format($laba_kotor,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_kotor_2 < 0 ? "(".number_format(-$laba_kotor_2,0,',','.').")" : number_format($laba_kotor_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
	            <th class="text-left" colspan="3">Biaya Umum & Administratif</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_umum_administratif?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_umum_administratif,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_umum_administratif?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_umum_administratif_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
			</tr>
			<tr class="table-active">
	            <th class="text-left" colspan="3">Biaya Lainnya</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_lainnya?filter_date=".$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_lainnya,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><a target="_blank" href="<?= base_url("laporan/cetak_biaya_lainnya?filter_date=".$filter_date_2 = date('d F Y',strtotime($date3)).' - '.date('d F Y',strtotime($arr_filter_date[1]))) ?>"><?php echo number_format($biaya_lainnya_2,0,',','.');?></a></span>
								</th>
							</tr>
					</table>
				</th>
			</tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $laba_usaha < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $laba_usaha_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Laba / Rugi Usaha</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_usaha < 0 ? "(".number_format(-$laba_usaha,0,',','.').")" : number_format($laba_usaha,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $laba_usaha_2 < 0 ? "(".number_format(-$laba_usaha_2,0,',','.').")" : number_format($laba_usaha_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<tr class="table-active">
	            <th colspan="3" class="text-left">Persediaan Bahan Baku</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_bahan_baku,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_bahan_baku_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active">
	            <th colspan="3" class="text-left">Persediaan Barang Jadi</th>
	            <th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_barang_jadi,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo number_format($nilai_persediaan_barang_jadi_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<tr class="table-active3">
				<th colspan="5"></th>
			</tr>
			<?php
				$styleColor = $total < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $total_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Total</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $total < 0 ? "(".number_format(-$total,0,',','.').")" : number_format($total,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $total_2 < 0 ? "(".number_format(-$total_2,0,',','.').")" : number_format($total_2,0,',','.');?></span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
			<?php
				$styleColor = $persentase < 0 ? 'color:red' : 'color:black';
				$styleColor2 = $persentase_2 < 0 ? 'color:red' : 'color:black';
			?>
			<tr class="table-active3">
	            <th colspan="3" class="text-left">Presentase</th>
	            <th class="text-right" style="<?php echo $styleColor ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $persentase < 0 ? "(".number_format(-$persentase,0,',','.').")" : number_format($persentase,0,',','.');?> %</span>
								</th>
							</tr>
					</table>
				</th>
				<th class="text-right" style="<?php echo $styleColor2 ?>">
					<table width="100%" border="0" cellpadding="0">
						<tr>
								<th class="text-left" width="10%">
									<span>Rp.</span>
								</th>
								<th class="text-right" width="90%">
									<span><?php echo $persentase_2 < 0 ? "(".number_format(-$persentase_2,0,',','.').")" : number_format($persentase_2,0,',','.');?> %</span>
								</th>
							</tr>
					</table>
				</th>
	        </tr>
	    </table>
		<?php
	}

	public function rencana_kerja($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
				table tr.table-judul{
					background-color: #e69500;
					font-size: 10px;
					font-weight: bold;
					color: black;
				}
					
				table tr.table-baris{
					background-color: #F0F0F0;
					font-size: 10px;
					font-weight: bold;
				}

				table tr.table-total{
					background-color: #E8E8E8;
					font-size: 10px;
					font-weight: bold;
				}
			</style>

			<?php
			$date_now = date('Y-m-d');

			//BULAN 1
			$date_1_awal = date('Y-m-01', (strtotime($date_now)));
			$date_1_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_1_awal)));
			
			$rencana_kerja_1 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();

			$volume_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_1_produk_d = $rencana_kerja_1['vol_produk_d'];

			$total_1_volume = $volume_1_produk_a + $volume_1_produk_b + $volume_1_produk_c + $volume_1_produk_d;
				
			$nilai_jual_125_1 = $volume_1_produk_a * $rencana_kerja_1['price_a'];
			$nilai_jual_225_1 = $volume_1_produk_b * $rencana_kerja_1['price_b'];
			$nilai_jual_250_1 = $volume_1_produk_c * $rencana_kerja_1['price_c'];
			$nilai_jual_250_18_1 = $volume_1_produk_d * $rencana_kerja_1['price_d'];
			$nilai_jual_all_1 = $nilai_jual_125_1 + $nilai_jual_225_1 + $nilai_jual_250_1 + $nilai_jual_250_18_1;

			//BULAN 2
			$date_2_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_1_akhir)));
			$date_2_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_2_awal)));

			$rencana_kerja_2 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$volume_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_2_produk_d = $rencana_kerja_2['vol_produk_d'];

			$total_2_volume = $volume_2_produk_a + $volume_2_produk_b + $volume_2_produk_c + $volume_2_produk_d;
				
			$nilai_jual_125_2 = $volume_2_produk_a * $rencana_kerja_2['price_a'];
			$nilai_jual_225_2 = $volume_2_produk_b * $rencana_kerja_2['price_b'];
			$nilai_jual_250_2 = $volume_2_produk_c * $rencana_kerja_2['price_c'];
			$nilai_jual_250_18_2 = $volume_2_produk_d * $rencana_kerja_2['price_d'];
			$nilai_jual_all_2 = $nilai_jual_125_2 + $nilai_jual_225_2 + $nilai_jual_250_2 + $nilai_jual_250_18_2;

			//BULAN 3
			$date_3_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_2_akhir)));
			$date_3_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_3_awal)));

			$rencana_kerja_3 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$volume_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_3_produk_d = $rencana_kerja_3['vol_produk_d'];

			$total_3_volume = $volume_3_produk_a + $volume_3_produk_b + $volume_3_produk_c + $volume_3_produk_d;
				
			$nilai_jual_125_3 = $volume_3_produk_a * $rencana_kerja_3['price_a'];
			$nilai_jual_225_3 = $volume_3_produk_b * $rencana_kerja_3['price_b'];
			$nilai_jual_250_3 = $volume_3_produk_c * $rencana_kerja_3['price_c'];
			$nilai_jual_250_18_3 = $volume_3_produk_d * $rencana_kerja_3['price_d'];
			$nilai_jual_all_3 = $nilai_jual_125_3 + $nilai_jual_225_3 + $nilai_jual_250_3 + $nilai_jual_250_18_3;

			//BULAN 4
			$date_4_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_3_akhir)));
			$date_4_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_4_awal)));

			$rencana_kerja_4 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$volume_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_4_produk_d = $rencana_kerja_4['vol_produk_d'];

			$total_4_volume = $volume_4_produk_a + $volume_4_produk_b + $volume_4_produk_c + $volume_4_produk_d;
				
			$nilai_jual_125_4 = $volume_4_produk_a * $rencana_kerja_4['price_a'];
			$nilai_jual_225_4 = $volume_4_produk_b * $rencana_kerja_4['price_b'];
			$nilai_jual_250_4 = $volume_4_produk_c * $rencana_kerja_4['price_c'];
			$nilai_jual_250_18_4 = $volume_4_produk_d * $rencana_kerja_4['price_d'];
			$nilai_jual_all_4 = $nilai_jual_125_4 + $nilai_jual_225_4 + $nilai_jual_250_4 + $nilai_jual_250_18_4;

			//BULAN 5
			$date_5_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_4_akhir)));
			$date_5_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_5_awal)));

			$rencana_kerja_5 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$volume_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_5_produk_d = $rencana_kerja_5['vol_produk_d'];

			$total_5_volume = $volume_5_produk_a + $volume_5_produk_b + $volume_5_produk_c + $volume_5_produk_d;
				
			$nilai_jual_125_5 = $volume_5_produk_a * $rencana_kerja_5['price_a'];
			$nilai_jual_225_5 = $volume_5_produk_b * $rencana_kerja_5['price_b'];
			$nilai_jual_250_5 = $volume_5_produk_c * $rencana_kerja_5['price_c'];
			$nilai_jual_250_18_5 = $volume_5_produk_d * $rencana_kerja_5['price_d'];
			$nilai_jual_all_5 = $nilai_jual_125_5 + $nilai_jual_225_5 + $nilai_jual_250_5 + $nilai_jual_250_18_5;

			//BULAN 6
			$date_6_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_5_akhir)));
			$date_6_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_6_awal)));

			$rencana_kerja_6 = $this->db->select('r.*, SUM(r.vol_produk_a) as vol_produk_a, SUM(r.vol_produk_b) as vol_produk_b, SUM(r.vol_produk_c) as vol_produk_c, SUM(r.vol_produk_d) as vol_produk_d')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$volume_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_6_produk_d = $rencana_kerja_6['vol_produk_d'];

			$total_6_volume = $volume_6_produk_a + $volume_6_produk_b + $volume_6_produk_c + $volume_6_produk_d;
				
			$nilai_jual_125_6 = $volume_6_produk_a * $rencana_kerja_6['price_a'];
			$nilai_jual_225_6 = $volume_6_produk_b * $rencana_kerja_6['price_b'];
			$nilai_jual_250_6 = $volume_6_produk_c * $rencana_kerja_6['price_c'];
			$nilai_jual_250_18_6 = $volume_6_produk_d * $rencana_kerja_6['price_d'];
			$nilai_jual_all_6 = $nilai_jual_125_6 + $nilai_jual_225_6 + $nilai_jual_250_6 + $nilai_jual_250_18_6;
			?>

			
			<?php
			
			//BULAN 1
			$komposisi_125_1 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_125_1 = 0;
			$total_volume_pasir_125_1 = 0;
			$total_volume_batu1020_125_1 = 0;
			$total_volume_batu2030_125_1 = 0;

			foreach ($komposisi_125_1 as $x){
				$total_volume_semen_125_1 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_1 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_1 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_1 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_1 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_225_1 = 0;
			$total_volume_pasir_225_1 = 0;
			$total_volume_batu1020_225_1 = 0;
			$total_volume_batu2030_225_1 = 0;

			foreach ($komposisi_225_1 as $x){
				$total_volume_semen_225_1 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_1 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_1 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_1 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_1 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_1 = 0;
			$total_volume_pasir_250_1 = 0;
			$total_volume_batu1020_250_1 = 0;
			$total_volume_batu2030_250_1 = 0;

			foreach ($komposisi_250_1 as $x){
				$total_volume_semen_250_1 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_1 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_1 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_1 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_1 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_1 = 0;
			$total_volume_pasir_250_2_1 = 0;
			$total_volume_batu1020_250_2_1 = 0;
			$total_volume_batu2030_250_2_1 = 0;

			foreach ($komposisi_250_2_1 as $x){
				$total_volume_semen_250_2_1 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_1 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_1 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_1 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_1 = $total_volume_semen_125_1 + $total_volume_semen_225_1 + $total_volume_semen_250_1 + $total_volume_semen_250_2_1;
			$total_volume_pasir_1 = $total_volume_pasir_125_1 + $total_volume_pasir_225_1 + $total_volume_pasir_250_1 + $total_volume_pasir_250_2_1;
			$total_volume_batu1020_1 = $total_volume_batu1020_125_1 + $total_volume_batu1020_225_1 + $total_volume_batu1020_250_1 + $total_volume_batu1020_250_2_1;
			$total_volume_batu2030_1 = $total_volume_batu2030_125_1 + $total_volume_batu2030_225_1 + $total_volume_batu2030_250_1 + $total_volume_batu2030_250_2_1;

			//BULAN 2
			$komposisi_125_2 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_125_2 = 0;
			$total_volume_pasir_125_2 = 0;
			$total_volume_batu1020_125_2 = 0;
			$total_volume_batu2030_125_2 = 0;

			foreach ($komposisi_125_2 as $x){
				$total_volume_semen_125_2 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_2 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_2 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_2 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_2 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_225_2 = 0;
			$total_volume_pasir_225_2 = 0;
			$total_volume_batu1020_225_2 = 0;
			$total_volume_batu2030_225_2 = 0;

			foreach ($komposisi_225_2 as $x){
				$total_volume_semen_225_2 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_2 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_2 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_2 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_2 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2 = 0;
			$total_volume_pasir_250_2 = 0;
			$total_volume_batu1020_250_2 = 0;
			$total_volume_batu2030_250_2 = 0;

			foreach ($komposisi_250_2 as $x){
				$total_volume_semen_250_2 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_2 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_2 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_2 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_2 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_2 = 0;
			$total_volume_pasir_250_2_2 = 0;
			$total_volume_batu1020_250_2_2 = 0;
			$total_volume_batu2030_250_2_2 = 0;

			foreach ($komposisi_250_2_2 as $x){
				$total_volume_semen_250_2_2 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_2 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_2 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_2 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_2 = $total_volume_semen_125_2 + $total_volume_semen_225_2 + $total_volume_semen_250_2 + $total_volume_semen_250_2_2;
			$total_volume_pasir_2 = $total_volume_pasir_125_2 + $total_volume_pasir_225_2 + $total_volume_pasir_250_2 + $total_volume_pasir_250_2_2;
			$total_volume_batu1020_2 = $total_volume_batu1020_125_2 + $total_volume_batu1020_225_2 + $total_volume_batu1020_250_2 + $total_volume_batu1020_250_2_2;
			$total_volume_batu2030_2 = $total_volume_batu2030_125_2 + $total_volume_batu2030_225_2 + $total_volume_batu2030_250_2 + $total_volume_batu2030_250_2_2;

			//BULAN 3
			$komposisi_125_3 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_125_3 = 0;
			$total_volume_pasir_125_3 = 0;
			$total_volume_batu1020_125_3 = 0;
			$total_volume_batu2030_125_3 = 0;

			foreach ($komposisi_125_3 as $x){
				$total_volume_semen_125_3 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_3 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_3 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_3 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_3 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_225_3 = 0;
			$total_volume_pasir_225_3 = 0;
			$total_volume_batu1020_225_3 = 0;
			$total_volume_batu2030_225_3 = 0;

			foreach ($komposisi_225_3 as $x){
				$total_volume_semen_225_3 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_3 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_3 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_3 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_3 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_3 = 0;
			$total_volume_pasir_250_3 = 0;
			$total_volume_batu1020_250_3 = 0;
			$total_volume_batu2030_250_3 = 0;

			foreach ($komposisi_250_3 as $x){
				$total_volume_semen_250_3 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_3 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_3 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_3 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_3 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_3 = 0;
			$total_volume_pasir_250_2_3 = 0;
			$total_volume_batu1020_250_2_3 = 0;
			$total_volume_batu2030_250_2_3 = 0;

			foreach ($komposisi_250_2_3 as $x){
				$total_volume_semen_250_2_3 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_3 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_3 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_3 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_3 = $total_volume_semen_125_3 + $total_volume_semen_225_3 + $total_volume_semen_250_3 + $total_volume_semen_250_2_3;
			$total_volume_pasir_3 = $total_volume_pasir_125_3 + $total_volume_pasir_225_3 + $total_volume_pasir_250_3 + $total_volume_pasir_250_2_3;
			$total_volume_batu1020_3 = $total_volume_batu1020_125_3 + $total_volume_batu1020_225_3 + $total_volume_batu1020_250_3 + $total_volume_batu1020_250_2_3;
			$total_volume_batu2030_3 = $total_volume_batu2030_125_3 + $total_volume_batu2030_225_3 + $total_volume_batu2030_250_3 + $total_volume_batu2030_250_2_3;

			//BULAN 4
			$komposisi_125_4 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_125_4 = 0;
			$total_volume_pasir_125_4 = 0;
			$total_volume_batu1020_125_4 = 0;
			$total_volume_batu2030_125_4 = 0;

			foreach ($komposisi_125_4 as $x){
				$total_volume_semen_125_4 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_4 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_4 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_4 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_4 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_225_4 = 0;
			$total_volume_pasir_225_4 = 0;
			$total_volume_batu1020_225_4 = 0;
			$total_volume_batu2030_225_4 = 0;

			foreach ($komposisi_225_4 as $x){
				$total_volume_semen_225_4 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_4 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_4 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_4 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_4 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_4 = 0;
			$total_volume_pasir_250_4 = 0;
			$total_volume_batu1020_250_4 = 0;
			$total_volume_batu2030_250_4 = 0;

			foreach ($komposisi_250_4 as $x){
				$total_volume_semen_250_4 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_4 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_4 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_4 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_4 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_4 = 0;
			$total_volume_pasir_250_2_4 = 0;
			$total_volume_batu1020_250_2_4 = 0;
			$total_volume_batu2030_250_2_4 = 0;

			foreach ($komposisi_250_2_4 as $x){
				$total_volume_semen_250_2_4 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_4 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_4 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_4 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_4 = $total_volume_semen_125_4 + $total_volume_semen_225_4 + $total_volume_semen_250_4 + $total_volume_semen_250_2_4;
			$total_volume_pasir_4 = $total_volume_pasir_125_4 + $total_volume_pasir_225_4 + $total_volume_pasir_250_4 + $total_volume_pasir_250_2_4;
			$total_volume_batu1020_4 = $total_volume_batu1020_125_4 + $total_volume_batu1020_225_4 + $total_volume_batu1020_250_4 + $total_volume_batu1020_250_2_4;
			$total_volume_batu2030_4 = $total_volume_batu2030_125_4 + $total_volume_batu2030_225_4 + $total_volume_batu2030_250_4 + $total_volume_batu2030_250_2_4;

			//BULAN 5
			$komposisi_125_5 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_125_5 = 0;
			$total_volume_pasir_125_5 = 0;
			$total_volume_batu1020_125_5 = 0;
			$total_volume_batu2030_125_5 = 0;

			foreach ($komposisi_125_5 as $x){
				$total_volume_semen_125_5 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_5 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_5 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_5 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_5 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_225_5 = 0;
			$total_volume_pasir_225_5 = 0;
			$total_volume_batu1020_225_5 = 0;
			$total_volume_batu2030_225_5 = 0;

			foreach ($komposisi_225_5 as $x){
				$total_volume_semen_225_5 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_5 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_5 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_5 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_5 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_5 = 0;
			$total_volume_pasir_250_5 = 0;
			$total_volume_batu1020_250_5 = 0;
			$total_volume_batu2030_250_5 = 0;

			foreach ($komposisi_250_5 as $x){
				$total_volume_semen_250_5 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_5 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_5 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_5 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_5 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_5 = 0;
			$total_volume_pasir_250_2_5 = 0;
			$total_volume_batu1020_250_2_5 = 0;
			$total_volume_batu2030_250_2_5 = 0;

			foreach ($komposisi_250_2_5 as $x){
				$total_volume_semen_250_2_5 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_5 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_5 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_5 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_5 = $total_volume_semen_125_5 + $total_volume_semen_225_5 + $total_volume_semen_250_5 + $total_volume_semen_250_2_5;
			$total_volume_pasir_5 = $total_volume_pasir_125_5 + $total_volume_pasir_225_5 + $total_volume_pasir_250_5 + $total_volume_pasir_250_2_5;
			$total_volume_batu1020_5 = $total_volume_batu1020_125_5 + $total_volume_batu1020_225_5 + $total_volume_batu1020_250_5 + $total_volume_batu1020_250_2_5;
			$total_volume_batu2030_5 = $total_volume_batu2030_125_5 + $total_volume_batu2030_225_5 + $total_volume_batu2030_250_5 + $total_volume_batu2030_250_2_5;

			//BULAN 6
			$komposisi_125_6 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_125_6 = 0;
			$total_volume_pasir_125_6 = 0;
			$total_volume_batu1020_125_6 = 0;
			$total_volume_batu2030_125_6 = 0;

			foreach ($komposisi_125_6 as $x){
				$total_volume_semen_125_6 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_6 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_6 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_6 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_6 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_225_6 = 0;
			$total_volume_pasir_225_6 = 0;
			$total_volume_batu1020_225_6 = 0;
			$total_volume_batu2030_225_6 = 0;

			foreach ($komposisi_225_6 as $x){
				$total_volume_semen_225_6 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_6 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_6 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_6 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_6 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_6 = 0;
			$total_volume_pasir_250_6 = 0;
			$total_volume_batu1020_250_6 = 0;
			$total_volume_batu2030_250_6 = 0;

			foreach ($komposisi_250_6 as $x){
				$total_volume_semen_250_6 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_6 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_6 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_6 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_6 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_6 = 0;
			$total_volume_pasir_250_2_6 = 0;
			$total_volume_batu1020_250_2_6 = 0;
			$total_volume_batu2030_250_2_6 = 0;

			foreach ($komposisi_250_2_6 as $x){
				$total_volume_semen_250_2_6 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_6 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_6 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_6 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_6 = $total_volume_semen_125_6 + $total_volume_semen_225_6 + $total_volume_semen_250_6 + $total_volume_semen_250_2_6;
			$total_volume_pasir_6 = $total_volume_pasir_125_6 + $total_volume_pasir_225_6 + $total_volume_pasir_250_6 + $total_volume_pasir_250_2_6;
			$total_volume_batu1020_6 = $total_volume_batu1020_125_6 + $total_volume_batu1020_225_6 + $total_volume_batu1020_250_6 + $total_volume_batu1020_250_2_6;
			$total_volume_batu2030_6 = $total_volume_batu2030_125_6 + $total_volume_batu2030_225_6 + $total_volume_batu2030_250_6 + $total_volume_batu2030_250_2_6;
			
			//SOLAR
			$rap_solar = $this->db->select('rap.*')
			->from('rap_alat rap')
			->where('rap.status','PUBLISH')
			->order_by('rap.id','desc')->limit(1)
			->get()->row_array();
			
			$total_volume_solar_1 = $total_1_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_2 = $total_2_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_3 = $total_3_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_4 = $total_4_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_5 = $total_5_volume * $rap_solar['vol_bbm_solar'];
			$total_volume_solar_6 = $total_6_volume * $rap_solar['vol_bbm_solar'];
			?>

			<tr class="table-judul">
				<th width="5%" class="text-center">NO.</th>
				<th class="text-center">URAIAN</th>
				<th class="text-center">SATUAN</th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_1_awal = date('F Y');?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_2_awal = date('F Y', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_3_awal = date('F Y', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_4_awal = date('F Y', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_5_awal = date('F Y', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_6_awal = date('F Y', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
	        </tr>
			<tr class="table-baris">
				<th class="text-center">1.</th>
				<th class="text-left">Beton K 125 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_a,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">2.</th>
				<th class="text-left">Beton K 225 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_b,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">3.</th>
				<th class="text-left">Beton K 250 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_c,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">4.</th>
				<th class="text-left">Beton K 250 (18±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_1_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_d,2,',','.');?></th>
			</tr>
			<tr class="table-total">
				<th class="text-right" colspan="3">TOTAL VOLUME</th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_1['id']) ?>"><?php echo number_format($total_1_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_2['id']) ?>"><?php echo number_format($total_2_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_3['id']) ?>"><?php echo number_format($total_3_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_4['id']) ?>"><?php echo number_format($total_4_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_5['id']) ?>"><?php echo number_format($total_5_volume,2,',','.');?></a></th>
				<th class="text-right"><a target="_blank" href="<?= base_url('laporan/cetak_kebutuhan_bahan_alat/'.$rencana_kerja_6['id']) ?>"><?php echo number_format($total_6_volume,2,',','.');?></a></th>
			</tr>
			<tr class="table-total">
				<th class="text-right" colspan="3">PENDAPATAN USAHA</th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_jual_all_6,0,',','.');?></th>
			</tr>
			<tr class="table-judul">
				<th width="5%" class="text-center" style="vertical-align:middle">NO.</th>
				<th class="text-center" style="vertical-align:middle">KEBUTUHAN BAHAN</th>
				<th class="text-center" style="vertical-align:middle">SATUAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
				<th class="text-center" style="vertical-align:middle">PENGADAAN</th>
	        </tr>
			<tr class="table-baris">
				<th class="text-center">1.</th>
				<th class="text-right">Semen</th>
				<th class="text-center">Ton</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_semen'].'/'.$date_1_awal = date('Y-m-d',strtotime($date_1_awal)).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_semen_1.'/'.$material_id = 4 ?>"><?php echo number_format($total_volume_semen_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_semen_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">2.</th>
				<th class="text-right">Pasir</th>
				<th class="text-center">M3</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_pasir'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_pasir_1.'/'.$material_id = 5 ?>"><?php echo number_format($total_volume_pasir_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_pasir_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">3.</th>
				<th class="text-right">Batu Split 10-20</th>
				<th class="text-center">M3</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_batu1020'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_batu1020_1.'/'.$material_id = 6 ?>"><?php echo number_format($total_volume_batu1020_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu1020_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">4.</th>
				<th class="text-right">Batu Split 20-30</th>
				<th class="text-center">M3</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_batu2030'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_batu2030_1.'/'.$material_id = 7 ?>"><?php echo number_format($total_volume_batu2030_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_batu2030_6,2,',','.');?></th>
			</tr>
			<tr class="table-baris">
				<th class="text-center">5.</th>
				<th class="text-right">Solar</th>
				<th class="text-center">Liter</th>
				<th class="text-right"><a target="_blank" href="<?= site_url('laporan/pesanan_pembelian/'.$rencana_kerja_1['penawaran_id_solar'].'/'.$date_1_awal = date('Y-m-d',strtotime('-2 days -1 months', strtotime($date_1_akhir))).'/'.$date_1_akhir = date('Y-m-d',strtotime($date_1_akhir)).'').'/'.$kebutuhan = $total_volume_solar_1.'/'.$material_id = 8 ?>"><?php echo number_format($total_volume_solar_1,2,',','.');?></a></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_2,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_3,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_4,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_5,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_volume_solar_6,2,',','.');?></th>
			</tr>
	    </table>
		<?php
	}

	public function prognosa_produksi($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">
			<style type="text/css">
				table tr.table-active-rak{
					background-color: #F0F0F0;
					font-size: 8px;
					font-weight: bold;
					color: black;
				}
					
				table tr.table-active2-rak{
					background-color: #E8E8E8;
					font-size: 8px;
					font-weight: bold;
				}
					
				table tr.table-active3-rak{
					font-size: 8px;
					background-color: #F0F0F0;
				}
					
				table tr.table-active4-rak{
					background-color: #e69500;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
				table tr.table-active5-rak{
					background-color: #E8E8E8;
					text-decoration: underline;
					font-size: 8px;
					font-weight: bold;
					color: red;
				}
				table tr.table-activeago1-rak{
					background-color: #ffd966;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
				table tr.table-activeopening-rak{
					background-color: #2986cc;
					font-weight: bold;
					font-size: 8px;
					color: black;
				}
			</style>

			<?php
			//VOLUME RAP
			$date_now = date('Y-m-d');
			$date_end = date('2022-12-31');

			$rencana_kerja_2022_1 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-30' and '2021-12-30'")
			->get()->row_array();

			$rencana_kerja_2022_2 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '2021-12-31' and '2021-12-31'")
			->get()->row_array();

			$volume_rap_2022_produk_a = $rencana_kerja_2022_1['vol_produk_a'] + $rencana_kerja_2022_2['vol_produk_a'];
			$volume_rap_2022_produk_b = $rencana_kerja_2022_1['vol_produk_b'] + $rencana_kerja_2022_2['vol_produk_b'];
			$volume_rap_2022_produk_c = $rencana_kerja_2022_1['vol_produk_c'] + $rencana_kerja_2022_2['vol_produk_c'];
			$volume_rap_2022_produk_d = $rencana_kerja_2022_1['vol_produk_d'] + $rencana_kerja_2022_2['vol_produk_d'];
			$total_rap_volume_2022 = $rencana_kerja_2022_1['vol_produk_a'] + $rencana_kerja_2022_1['vol_produk_b'] + $rencana_kerja_2022_1['vol_produk_c'] + $rencana_kerja_2022_1['vol_produk_d'] + $rencana_kerja_2022_2['vol_produk_a'] + $rencana_kerja_2022_2['vol_produk_b'] + $rencana_kerja_2022_2['vol_produk_c'] + $rencana_kerja_2022_2['vol_produk_d'];

			$price_produk_a_1 = $rencana_kerja_2022_1['vol_produk_a'] * $rencana_kerja_2022_1['price_a'];
			$price_produk_b_1 = $rencana_kerja_2022_1['vol_produk_b'] * $rencana_kerja_2022_1['price_b'];
			$price_produk_c_1 = $rencana_kerja_2022_1['vol_produk_c'] * $rencana_kerja_2022_1['price_c'];
			$price_produk_d_1 = $rencana_kerja_2022_1['vol_produk_d'] * $rencana_kerja_2022_1['price_d'];

			$price_produk_a_2 = $rencana_kerja_2022_2['vol_produk_a'] * $rencana_kerja_2022_2['price_a'];
			$price_produk_b_2 = $rencana_kerja_2022_2['vol_produk_b'] * $rencana_kerja_2022_2['price_b'];
			$price_produk_c_2 = $rencana_kerja_2022_2['vol_produk_c'] * $rencana_kerja_2022_2['price_c'];
			$price_produk_d_2 = $rencana_kerja_2022_2['vol_produk_d'] * $rencana_kerja_2022_2['price_d'];

			$nilai_jual_all_2022 = $price_produk_a_1 + $price_produk_b_1 + $price_produk_c_1 + $price_produk_d_1 + $price_produk_a_2 + $price_produk_b_2 + $price_produk_c_2 + $price_produk_d_2;
			$total_rap_nilai_2022 = $nilai_jual_all_2022;

			//BIAYA RAP 2022
			$total_rap_2022_biaya_bahan = $rencana_kerja_2022_1['biaya_bahan'] + $rencana_kerja_2022_2['biaya_bahan'];
			$total_rap_2022_biaya_alat = $rencana_kerja_2022_1['biaya_alat'] + $rencana_kerja_2022_2['biaya_alat'];
			$total_rap_2022_overhead = $rencana_kerja_2022_1['overhead'] + $rencana_kerja_2022_2['overhead'];
			$total_rap_2022_diskonto = ($total_rap_nilai_2022 * 3) / 100;
			$total_biaya_rap_2022_biaya = $total_rap_2022_biaya_bahan + $total_rap_2022_biaya_alat + $total_rap_2022_overhead + $total_rap_2022_diskonto;
			?>
			
			<?php
			//AKUMULASI
			$last_opname_start = date('Y-m-01', (strtotime($date_now)));
			$last_opname = date('Y-m-d', strtotime('-1 days', strtotime($last_opname_start)));
			

			$penjualan_akumulasi_produk_a = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 2")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_a = $penjualan_akumulasi_produk_a['volume'];
			$nilai_akumulasi_produk_a = $penjualan_akumulasi_produk_a['price'];

			$penjualan_akumulasi_produk_b = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 1")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_b = $penjualan_akumulasi_produk_b['volume'];
			$nilai_akumulasi_produk_b = $penjualan_akumulasi_produk_b['price'];

			$penjualan_akumulasi_produk_c = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 3")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_c = $penjualan_akumulasi_produk_c['volume'];
			$nilai_akumulasi_produk_c = $penjualan_akumulasi_produk_c['price'];

			$penjualan_akumulasi_produk_d = $this->db->select('p.nama_produk, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume')
			->from('pmm_productions pp')
			->join('produk p', 'pp.product_id = p.id','left')
			->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
			->where("(pp.date_production <= '$last_opname')")
			->where("pp.status = 'PUBLISH'")
			->where("pp.product_id = 11")
			->where("ppo.status in ('OPEN','CLOSED')")
			->group_by("pp.product_id")
			->order_by('p.nama_produk','asc')
			->get()->row_array();
			$volume_akumulasi_produk_d = $penjualan_akumulasi_produk_d['volume'];
			$nilai_akumulasi_produk_d = $penjualan_akumulasi_produk_d['price'];

			$total_akumulasi_volume = $volume_akumulasi_produk_a + $volume_akumulasi_produk_b + $volume_akumulasi_produk_c + $volume_akumulasi_produk_d;
			$total_akumulasi_nilai = $nilai_akumulasi_produk_a + $nilai_akumulasi_produk_b + $nilai_akumulasi_produk_c + $nilai_akumulasi_produk_d;
		
			//AKUMULASI BIAYA
			//BAHAN
			$akumulasi = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar as total_nilai_keluar')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi <= '$last_opname')")
			->get()->result_array();

			$total_akumulasi = 0;

			foreach ($akumulasi as $a){
				$total_akumulasi += $a['total_nilai_keluar'];
			}

			$total_bahan_akumulasi = $total_akumulasi;
		
			//ALAT
			$nilai_alat = $this->db->select('SUM(prm.display_price) as nilai')
			->from('pmm_receipt_material prm')
			->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
			->join('produk p', 'prm.material_id = p.id','left')
			->where("(prm.date_receipt <= '$last_opname')")
			->where("p.kategori_produk = '5'")
			->where("po.status in ('PUBLISH','CLOSED')")
			->get()->row_array();

			$akumulasi_bbm = $this->db->select('pp.date_akumulasi, pp.total_nilai_keluar_2 as total_nilai_keluar_2')
			->from('akumulasi pp')
			->where("(pp.date_akumulasi <= '$last_opname')")
			->get()->result_array();

			$total_akumulasi_bbm = 0;

			foreach ($akumulasi_bbm as $b){
				$total_akumulasi_bbm += $b['total_nilai_keluar_2'];
			}

			$total_nilai_bbm = $total_akumulasi_bbm;

			$total_insentif_tm = 0;
			$insentif_tm = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 220")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();
			$total_insentif_tm = $insentif_tm['total'];

			$insentif_wl = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->where("pdb.akun = 221")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();
			$total_insentif_wl = $insentif_wl['total'];

			$total_alat_akumulasi = $nilai_alat['nilai'] + $total_akumulasi_bbm + $total_insentif_tm + $total_insentif_wl;

			//OVERHEAD
			$overhead_15_akumulasi = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();

			$overhead_jurnal_15_akumulasi = $this->db->select('sum(pdb.debit) as total')
			->from('pmm_jurnal_umum pb ')
			->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where('c.coa_category',15)
			->where("c.id <> 168 ") //Biaya Diskonto Bank
			->where("c.id <> 219 ") //Biaya Alat Batching Plant 
			->where("c.id <> 220 ") //Biaya Alat Truck Mixer
			->where("c.id <> 221 ") //Biaya Alat Wheel Loader
			->where("c.id <> 228 ") //Biaya Persiapan
			->where("c.id <> 505 ") //Biaya Oli
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();

			//DISKONTO
			$diskonto_akumulasi = $this->db->select('sum(pdb.jumlah) as total')
			->from('pmm_biaya pb ')
			->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
			->join('pmm_coa c','pdb.akun = c.id','left')
			->where("pdb.akun = 168")
			->where("pb.status = 'PAID'")
			->where("(pb.tanggal_transaksi <= '$last_opname')")
			->get()->row_array();

			$total_overhead_akumulasi =  $overhead_15_akumulasi['total'] + $overhead_jurnal_15_akumulasi['total'];
			$total_diskonto_akumulasi =  $diskonto_akumulasi['total'];
			$total_biaya_akumulasi = $total_bahan_akumulasi + $total_alat_akumulasi + $total_overhead_akumulasi + $total_diskonto_akumulasi;
			?>
			<!-- AKUMULASI BULAN TERAKHIR -->

			<?php
			$date_now = date('Y-m-d');

			//BULAN 1
			$date_1_awal = date('Y-m-01', (strtotime($date_now)));
			$date_1_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_1_awal)));

			$rencana_kerja_1 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();
			
			$volume_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_1_produk_d = $rencana_kerja_1['vol_produk_d'];

			$total_1_volume = $volume_1_produk_a + $volume_1_produk_b + $volume_1_produk_c + $volume_1_produk_d;

			$nilai_jual_125_1 = $volume_1_produk_a * $rencana_kerja_1['price_a'];
			$nilai_jual_225_1 = $volume_1_produk_b * $rencana_kerja_1['price_b'];
			$nilai_jual_250_1 = $volume_1_produk_c * $rencana_kerja_1['price_c'];
			$nilai_jual_250_18_1 = $volume_1_produk_d * $rencana_kerja_1['price_d'];
			$nilai_jual_all_1 = $nilai_jual_125_1 + $nilai_jual_225_1 + $nilai_jual_250_1 + $nilai_jual_250_18_1;

			$total_1_nilai = $nilai_jual_all_1;

			//VOLUME
			$volume_rencana_kerja_1_produk_a = $rencana_kerja_1['vol_produk_a'];
			$volume_rencana_kerja_1_produk_b = $rencana_kerja_1['vol_produk_b'];
			$volume_rencana_kerja_1_produk_c = $rencana_kerja_1['vol_produk_c'];
			$volume_rencana_kerja_1_produk_d = $rencana_kerja_1['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_1 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_125_1 = 0;
			$total_volume_pasir_125_1 = 0;
			$total_volume_batu1020_125_1 = 0;
			$total_volume_batu2030_125_1 = 0;

			foreach ($komposisi_125_1 as $x){
				$total_volume_semen_125_1 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_1 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_1 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_1 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_1 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_225_1 = 0;
			$total_volume_pasir_225_1 = 0;
			$total_volume_batu1020_225_1 = 0;
			$total_volume_batu2030_225_1 = 0;

			foreach ($komposisi_225_1 as $x){
				$total_volume_semen_225_1 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_1 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_1 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_1 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_1 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_1 = 0;
			$total_volume_pasir_250_1 = 0;
			$total_volume_batu1020_250_1 = 0;
			$total_volume_batu2030_250_1 = 0;

			foreach ($komposisi_250_1 as $x){
				$total_volume_semen_250_1 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_1 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_1 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_1 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_1 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_1 = 0;
			$total_volume_pasir_250_2_1 = 0;
			$total_volume_batu1020_250_2_1 = 0;
			$total_volume_batu2030_250_2_1 = 0;

			foreach ($komposisi_250_2_1 as $x){
				$total_volume_semen_250_2_1 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_1 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_1 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_1 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_1 = $total_volume_semen_125_1 + $total_volume_semen_225_1 + $total_volume_semen_250_1 + $total_volume_semen_250_2_1;
			$total_volume_pasir_1 = $total_volume_pasir_125_1 + $total_volume_pasir_225_1 + $total_volume_pasir_250_1 + $total_volume_pasir_250_2_1;
			$total_volume_batu1020_1 = $total_volume_batu1020_125_1 + $total_volume_batu1020_225_1 + $total_volume_batu1020_250_1 + $total_volume_batu1020_250_2_1;
			$total_volume_batu2030_1 = $total_volume_batu2030_125_1 + $total_volume_batu2030_225_1 + $total_volume_batu2030_250_1 + $total_volume_batu2030_250_2_1;

			$nilai_semen_1 = $total_volume_semen_1 * $rencana_kerja_1['harga_semen'];
			$nilai_pasir_1 = $total_volume_pasir_1 * $rencana_kerja_1['harga_pasir'];
			$nilai_batu1020_1 = $total_volume_batu1020_1 * $rencana_kerja_1['harga_batu1020'];
			$nilai_batu2030_1 = $total_volume_batu2030_1 * $rencana_kerja_1['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_1 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->get()->row_array();

			$rak_alat_bp_1 = $rak_alat_1['penawaran_id_bp'];
			$rak_alat_bp_2_1 = $rak_alat_1['penawaran_id_bp_2'];
			$rak_alat_bp_3_1 = $rak_alat_1['penawaran_id_bp_3'];

			$rak_alat_tm_1 = $rak_alat_1['penawaran_id_tm'];
			$rak_alat_tm_2_1 = $rak_alat_1['penawaran_id_tm_2'];
			$rak_alat_tm_3_1 = $rak_alat_1['penawaran_id_tm_3'];
			$rak_alat_tm_4_1 = $rak_alat_1['penawaran_id_tm_4'];

			$rak_alat_wl_1 = $rak_alat_1['penawaran_id_wl'];
			$rak_alat_wl_2_1 = $rak_alat_1['penawaran_id_wl_2'];
			$rak_alat_wl_3_1 = $rakrak_alat_1_alat['penawaran_id_wl_3'];

			$rak_alat_tr_1 = $rak_alat_1['penawaran_id_tr'];
			$rak_alat_tr_2_1 = $rak_alat_1['penawaran_id_tr_2'];
			$rak_alat_tr_3_1 = $rak_alat_1['penawaran_id_tr_3'];

			$rak_alat_exc_1 = $rak_alat_1['penawaran_id_exc'];
			$rak_alat_dmp_4m3_1 = $rak_alat_1['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_1 = $rak_alat_1['penawaran_id_dmp_10m3'];
			$rak_alat_sc_1 = $rak_alat_1['penawaran_id_sc'];
			$rak_alat_gns_1 = $rak_alat_1['penawaran_id_gns'];
			$rak_alat_wl_sc_1 = $rak_alat_1['penawaran_id_wl_sc'];

			$produk_bp_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->where("ppp.id = '$rak_alat_bp_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_1 = 0;
			foreach ($produk_bp_1 as $x){
				$total_price_bp_1 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_1 = 0;
			foreach ($produk_bp_2_1 as $x){
				$total_price_bp_2_1 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_1_awal' and '$date_1_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_1 = 0;
			foreach ($produk_bp_3_1 as $x){
				$total_price_bp_3_1 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_1 = 0;
			foreach ($produk_tm_1 as $x){
				$total_price_tm_1 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_1 = 0;
			foreach ($produk_tm_2_1 as $x){
				$total_price_tm_2_1 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_1 = 0;
			foreach ($produk_tm_3_1 as $x){
				$total_price_tm_3_1 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_1 = 0;
			foreach ($produk_tm_4_1 as $x){
				$total_price_tm_4_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_1 = 0;
			foreach ($produk_wl_1 as $x){
				$total_price_wl_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_1 = 0;
			foreach ($produk_wl_2_1 as $x){
				$total_price_wl_2_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_1 = 0;
			foreach ($produk_wl_3_1 as $x){
				$total_price_wl_3_1 += $x['qty'] * $x['price'];
			}

			$produk_tr_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_1 = 0;
			foreach ($produk_tr_1 as $x){
				$total_price_tr_1 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_1 = 0;
			foreach ($produk_tr_2_1 as $x){
				$total_price_tr_2_1 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_1 = 0;
			foreach ($produk_tr_3_1 as $x){
				$total_price_tr_3_1 += $x['qty'] * $x['price'];
			}

			$produk_exc_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_1 = 0;
			foreach ($produk_exc_1 as $x){
				$total_price_exc_1 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_1 = 0;
			foreach ($produk_dmp_4m3_1 as $x){
				$total_price_dmp_4m3_1 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_1 = 0;
			foreach ($produk_dmp_10m3_1 as $x){
				$total_price_dmp_10m3_1 += $x['qty'] * $x['price'];
			}

			$produk_sc_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_1 = 0;
			foreach ($produk_sc_1 as $x){
				$total_price_sc_1 += $x['qty'] * $x['price'];
			}

			$produk_gns_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_1 = 0;
			foreach ($produk_gns_1 as $x){
				$total_price_gns_1 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_1 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_1'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_1 = 0;
			foreach ($produk_wl_sc_1 as $x){
				$total_price_wl_sc_1 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_1 = $rak_alat_1['vol_bbm_solar'];

			$total_1_biaya_bahan = $nilai_semen_1 + $nilai_pasir_1 + $nilai_batu1020_1 + $nilai_batu2030_1;
			$total_1_biaya_alat = ($total_price_bp_1 + $total_price_bp_2_1 + $total_price_bp_3_1) + ($total_price_tm_1 + $total_price_tm_2_1 + $total_price_tm_3_1 + $total_price_tm_4_1) + ($total_price_wl_1 + $total_price_wl_2_1 + $total_price_wl_3_1) + ($total_price_tr_1 + $total_price_tr_2_1 + $total_price_tr_3_1) + ($total_volume_solar_1 * $rak_alat_1['harga_solar']) + $rak_alat_1['insentif'] + $total_price_exc_1 + $total_price_dmp_4m3_1 + $total_price_dmp_10m3_1 + $total_price_sc_1 + $total_price_gns_1 + $total_price_wl_sc_1;
			$total_1_overhead = $rencana_kerja_1['overhead'];
			$total_1_diskonto =  ($total_1_nilai * 3) /100;
			$total_biaya_1_biaya = $total_1_biaya_bahan + $total_1_biaya_alat + $total_1_overhead + $total_1_diskonto;
			?>

			<?php
			//BULAN 2
			$date_2_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_1_akhir)));
			$date_2_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_2_awal)));

			$rencana_kerja_2 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$volume_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_2_produk_d = $rencana_kerja_2['vol_produk_d'];

			$total_2_volume = $volume_2_produk_a + $volume_2_produk_b + $volume_2_produk_c + $volume_2_produk_d;

			$nilai_jual_125_2 = $volume_2_produk_a * $rencana_kerja_2['price_a'];
			$nilai_jual_225_2 = $volume_2_produk_b * $rencana_kerja_2['price_b'];
			$nilai_jual_250_2 = $volume_2_produk_c * $rencana_kerja_2['price_c'];
			$nilai_jual_250_18_2 = $volume_2_produk_d * $rencana_kerja_2['price_d'];
			$nilai_jual_all_2 = $nilai_jual_125_2 + $nilai_jual_225_2 + $nilai_jual_250_2 + $nilai_jual_250_18_2;

			$total_2_nilai = $nilai_jual_all_2;

			//VOLUME
			$volume_rencana_kerja_2_produk_a = $rencana_kerja_2['vol_produk_a'];
			$volume_rencana_kerja_2_produk_b = $rencana_kerja_2['vol_produk_b'];
			$volume_rencana_kerja_2_produk_c = $rencana_kerja_2['vol_produk_c'];
			$volume_rencana_kerja_2_produk_d = $rencana_kerja_2['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_2 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_125_2 = 0;
			$total_volume_pasir_125_2 = 0;
			$total_volume_batu1020_125_2 = 0;
			$total_volume_batu2030_125_2 = 0;

			foreach ($komposisi_125_2 as $x){
				$total_volume_semen_125_2 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_2 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_2 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_2 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_2 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_225_2 = 0;
			$total_volume_pasir_225_2 = 0;
			$total_volume_batu1020_225_2 = 0;
			$total_volume_batu2030_225_2 = 0;

			foreach ($komposisi_225_2 as $x){
				$total_volume_semen_225_2 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_2 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_2 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_2 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_2 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2 = 0;
			$total_volume_pasir_250_2 = 0;
			$total_volume_batu1020_250_2 = 0;
			$total_volume_batu2030_250_2 = 0;

			foreach ($komposisi_250_2 as $x){
				$total_volume_semen_250_2 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_2 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_2 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_2 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_2 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_2 = 0;
			$total_volume_pasir_250_2_2 = 0;
			$total_volume_batu1020_250_2_2 = 0;
			$total_volume_batu2030_250_2_2 = 0;

			foreach ($komposisi_250_2_2 as $x){
				$total_volume_semen_250_2_2 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_2 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_2 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_2 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_2 = $total_volume_semen_125_2 + $total_volume_semen_225_2 + $total_volume_semen_250_2 + $total_volume_semen_250_2_2;
			$total_volume_pasir_2 = $total_volume_pasir_125_2 + $total_volume_pasir_225_2 + $total_volume_pasir_250_2 + $total_volume_pasir_250_2_2;
			$total_volume_batu1020_2 = $total_volume_batu1020_125_2 + $total_volume_batu1020_225_2 + $total_volume_batu1020_250_2 + $total_volume_batu1020_250_2_2;
			$total_volume_batu2030_2 = $total_volume_batu2030_125_2 + $total_volume_batu2030_225_2 + $total_volume_batu2030_250_2 + $total_volume_batu2030_250_2_2;

			$nilai_semen_2 = $total_volume_semen_2 * $rencana_kerja_2['harga_semen'];
			$nilai_pasir_2 = $total_volume_pasir_2 * $rencana_kerja_2['harga_pasir'];
			$nilai_batu1020_2 = $total_volume_batu1020_2 * $rencana_kerja_2['harga_batu1020'];
			$nilai_batu2030_2 = $total_volume_batu2030_2 * $rencana_kerja_2['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_2 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->get()->row_array();

			$rak_alat_bp_2 = $rak_alat_2['penawaran_id_bp'];
			$rak_alat_bp_2_2 = $rak_alat_2['penawaran_id_bp_2'];
			$rak_alat_bp_3_2 = $rak_alat_2['penawaran_id_bp_3'];

			$rak_alat_tm_2 = $rak_alat_2['penawaran_id_tm'];
			$rak_alat_tm_2_2 = $rak_alat_2['penawaran_id_tm_2'];
			$rak_alat_tm_3_2 = $rak_alat_2['penawaran_id_tm_3'];
			$rak_alat_tm_4_2 = $rak_alat_2['penawaran_id_tm_4'];

			$rak_alat_wl_2 = $rak_alat_2['penawaran_id_wl'];
			$rak_alat_wl_2_2 = $rak_alat_2['penawaran_id_wl_2'];
			$rak_alat_wl_3_2 = $rakrak_alat_2_alat['penawaran_id_wl_3'];

			$rak_alat_tr_2 = $rak_alat_2['penawaran_id_tr'];
			$rak_alat_tr_2_2 = $rak_alat_2['penawaran_id_tr_2'];
			$rak_alat_tr_3_2 = $rak_alat_2['penawaran_id_tr_3'];

			$rak_alat_exc_2 = $rak_alat_2['penawaran_id_exc'];
			$rak_alat_dmp_4m3_2 = $rak_alat_2['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_2 = $rak_alat_2['penawaran_id_dmp_10m3'];
			$rak_alat_sc_2 = $rak_alat_2['penawaran_id_sc'];
			$rak_alat_gns_2 = $rak_alat_2['penawaran_id_gns'];
			$rak_alat_wl_sc_2 = $rak_alat_2['penawaran_id_wl_sc'];

			$produk_bp_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->where("ppp.id = '$rak_alat_bp_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2 = 0;
			foreach ($produk_bp_2 as $x){
				$total_price_bp_2 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_2 = 0;
			foreach ($produk_bp_2_2 as $x){
				$total_price_bp_2_2 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_2_awal' and '$date_2_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_2 = 0;
			foreach ($produk_bp_3_2 as $x){
				$total_price_bp_3_2 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2 = 0;
			foreach ($produk_tm_2 as $x){
				$total_price_tm_2 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_2 = 0;
			foreach ($produk_tm_2_2 as $x){
				$total_price_tm_2_2 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_2 = 0;
			foreach ($produk_tm_3_2 as $x){
				$total_price_tm_3_2 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_2 = 0;
			foreach ($produk_tm_4_2 as $x){
				$total_price_tm_4_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2 = 0;
			foreach ($produk_wl_2 as $x){
				$total_price_wl_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_2 = 0;
			foreach ($produk_wl_2_2 as $x){
				$total_price_wl_2_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_2 = 0;
			foreach ($produk_wl_3_2 as $x){
				$total_price_wl_3_2 += $x['qty'] * $x['price'];
			}

			$produk_tr_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2 = 0;
			foreach ($produk_tr_2 as $x){
				$total_price_tr_2 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_2 = 0;
			foreach ($produk_tr_2_2 as $x){
				$total_price_tr_2_2 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_2 = 0;
			foreach ($produk_tr_3_2 as $x){
				$total_price_tr_3_2 += $x['qty'] * $x['price'];
			}

			$produk_exc_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_2 = 0;
			foreach ($produk_exc_2 as $x){
				$total_price_exc_2 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_2 = 0;
			foreach ($produk_dmp_4m3_2 as $x){
				$total_price_dmp_4m3_2 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_2 = 0;
			foreach ($produk_dmp_10m3_2 as $x){
				$total_price_dmp_10m3_2 += $x['qty'] * $x['price'];
			}

			$produk_sc_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_2 = 0;
			foreach ($produk_sc_2 as $x){
				$total_price_sc_2 += $x['qty'] * $x['price'];
			}

			$produk_gns_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_2 = 0;
			foreach ($produk_gns_2 as $x){
				$total_price_gns_2 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_2 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_2'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_2 = 0;
			foreach ($produk_wl_sc_2 as $x){
				$total_price_wl_sc_2 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_2 = $rak_alat_2['vol_bbm_solar'];

			$total_2_biaya_bahan = $nilai_semen_2 + $nilai_pasir_2 + $nilai_batu1020_2 + $nilai_batu2030_2;
			$total_2_biaya_alat = ($total_price_bp_2 + $total_price_bp_2_2 + $total_price_bp_3_2) + ($total_price_tm_2 + $total_price_tm_2_2 + $total_price_tm_3_2 + $total_price_tm_4_2) + ($total_price_wl_2 + $total_price_wl_2_2 + $total_price_wl_3_2) + ($total_price_tr_2 + $total_price_tr_2_2 + $total_price_tr_3_2) + ($total_volume_solar_2 * $rak_alat_2['harga_solar']) + $rak_alat_2['insentif']+ $total_price_exc_2 + $total_price_dmp_4m3_2 + $total_price_dmp_10m3_2 + $total_price_sc_2 + $total_price_gns_2 + $total_price_wl_sc_2;
			$total_2_overhead = $rencana_kerja_2['overhead'];
			$total_2_diskonto =  ($total_2_nilai * 3) /100;
			$total_biaya_2_biaya = $total_2_biaya_bahan + $total_2_biaya_alat + $total_2_overhead + $total_2_diskonto;
			?>

			<?php
			$date_3_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_2_akhir)));
			$date_3_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_3_awal)));

			$rencana_kerja_3 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$volume_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_3_produk_d = $rencana_kerja_3['vol_produk_d'];

			$total_3_volume = $volume_3_produk_a + $volume_3_produk_b + $volume_3_produk_c + $volume_3_produk_d;

			$nilai_jual_125_3 = $volume_3_produk_a * $rencana_kerja_3['price_a'];
			$nilai_jual_225_3 = $volume_3_produk_b * $rencana_kerja_3['price_b'];
			$nilai_jual_250_3 = $volume_3_produk_c * $rencana_kerja_3['price_c'];
			$nilai_jual_250_18_3 = $volume_3_produk_d * $rencana_kerja_3['price_d'];
			$nilai_jual_all_3 = $nilai_jual_125_3 + $nilai_jual_225_3 + $nilai_jual_250_3 + $nilai_jual_250_18_3;

			$total_3_nilai = $nilai_jual_all_3;

			//VOLUME
			$volume_rencana_kerja_3_produk_a = $rencana_kerja_3['vol_produk_a'];
			$volume_rencana_kerja_3_produk_b = $rencana_kerja_3['vol_produk_b'];
			$volume_rencana_kerja_3_produk_c = $rencana_kerja_3['vol_produk_c'];
			$volume_rencana_kerja_3_produk_d = $rencana_kerja_3['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_3 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_125_3 = 0;
			$total_volume_pasir_125_3 = 0;
			$total_volume_batu1020_125_3 = 0;
			$total_volume_batu2030_125_3 = 0;

			foreach ($komposisi_125_3 as $x){
				$total_volume_semen_125_3 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_3 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_3 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_3 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_3 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_225_3 = 0;
			$total_volume_pasir_225_3 = 0;
			$total_volume_batu1020_225_3 = 0;
			$total_volume_batu2030_225_3 = 0;

			foreach ($komposisi_225_3 as $x){
				$total_volume_semen_225_3 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_3 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_3 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_3 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_3 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_3 = 0;
			$total_volume_pasir_250_3 = 0;
			$total_volume_batu1020_250_3 = 0;
			$total_volume_batu2030_250_3 = 0;

			foreach ($komposisi_250_3 as $x){
				$total_volume_semen_250_3 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_3 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_3 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_3 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_3 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_3 = 0;
			$total_volume_pasir_250_2_3 = 0;
			$total_volume_batu1020_250_2_3 = 0;
			$total_volume_batu2030_250_2_3 = 0;

			foreach ($komposisi_250_2_3 as $x){
				$total_volume_semen_250_2_3 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_3 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_3 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_3 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_3 = $total_volume_semen_125_3 + $total_volume_semen_225_3 + $total_volume_semen_250_3 + $total_volume_semen_250_2_3;
			$total_volume_pasir_3 = $total_volume_pasir_125_3 + $total_volume_pasir_225_3 + $total_volume_pasir_250_3 + $total_volume_pasir_250_2_3;
			$total_volume_batu1020_3 = $total_volume_batu1020_125_3 + $total_volume_batu1020_225_3 + $total_volume_batu1020_250_3 + $total_volume_batu1020_250_2_3;
			$total_volume_batu2030_3 = $total_volume_batu2030_125_3 + $total_volume_batu2030_225_3 + $total_volume_batu2030_250_3 + $total_volume_batu2030_250_2_3;

			$nilai_semen_3 = $total_volume_semen_3 * $rencana_kerja_3['harga_semen'];
			$nilai_pasir_3 = $total_volume_pasir_3 * $rencana_kerja_3['harga_pasir'];
			$nilai_batu1020_3 = $total_volume_batu1020_3 * $rencana_kerja_3['harga_batu1020'];
			$nilai_batu2030_3 = $total_volume_batu2030_3 * $rencana_kerja_3['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_3 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->get()->row_array();

			$rak_alat_bp_3 = $rak_alat_3['penawaran_id_bp'];
			$rak_alat_bp_2_3 = $rak_alat_3['penawaran_id_bp_2'];
			$rak_alat_bp_3_3 = $rak_alat_3['penawaran_id_bp_3'];

			$rak_alat_tm_3 = $rak_alat_3['penawaran_id_tm'];
			$rak_alat_tm_2_3 = $rak_alat_3['penawaran_id_tm_2'];
			$rak_alat_tm_3_3 = $rak_alat_3['penawaran_id_tm_3'];
			$rak_alat_tm_4_3 = $rak_alat_4['penawaran_id_tm_4'];

			$rak_alat_wl_3 = $rak_alat_3['penawaran_id_wl'];
			$rak_alat_wl_2_3 = $rak_alat_3['penawaran_id_wl_2'];
			$rak_alat_wl_3_3 = $rakrak_alat_3_alat['penawaran_id_wl_3'];

			$rak_alat_tr_3 = $rak_alat_3['penawaran_id_tr'];
			$rak_alat_tr_2_3 = $rak_alat_3['penawaran_id_tr_2'];
			$rak_alat_tr_3_3 = $rak_alat_3['penawaran_id_tr_3'];

			$rak_alat_exc_3 = $rak_alat_3['penawaran_id_exc'];
			$rak_alat_dmp_4m3_3 = $rak_alat_3['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_3 = $rak_alat_3['penawaran_id_dmp_10m3'];
			$rak_alat_sc_3 = $rak_alat_3['penawaran_id_sc'];
			$rak_alat_gns_3 = $rak_alat_3['penawaran_id_gns'];
			$rak_alat_wl_sc_3 = $rak_alat_3['penawaran_id_wl_sc'];

			$produk_bp_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->where("ppp.id = '$rak_alat_bp_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3 = 0;
			foreach ($produk_bp_3 as $x){
				$total_price_bp_3 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_3 = 0;
			foreach ($produk_bp_2_3 as $x){
				$total_price_bp_2_3 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_3_awal' and '$date_3_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_3 = 0;
			foreach ($produk_bp_3_3 as $x){
				$total_price_bp_3_3 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3 = 0;
			foreach ($produk_tm_3 as $x){
				$total_price_tm_3 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_3 = 0;
			foreach ($produk_tm_2_3 as $x){
				$total_price_tm_2_3 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_3 = 0;
			foreach ($produk_tm_3_3 as $x){
				$total_price_tm_3_3 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_3 = 0;
			foreach ($produk_tm_4_3 as $x){
				$total_price_tm_4_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3 = 0;
			foreach ($produk_wl_3 as $x){
				$total_price_wl_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_3 = 0;
			foreach ($produk_wl_2_3 as $x){
				$total_price_wl_2_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_3 = 0;
			foreach ($produk_wl_3_3 as $x){
				$total_price_wl_3_3 += $x['qty'] * $x['price'];
			}

			$produk_tr_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3 = 0;
			foreach ($produk_tr_3 as $x){
				$total_price_tr_3 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_3 = 0;
			foreach ($produk_tr_2_3 as $x){
				$total_price_tr_2_3 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_3 = 0;
			foreach ($produk_tr_3_3 as $x){
				$total_price_tr_3_3 += $x['qty'] * $x['price'];
			}

			$produk_exc_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_3 = 0;
			foreach ($produk_exc_3 as $x){
				$total_price_exc_3 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_3 = 0;
			foreach ($produk_dmp_4m3_3 as $x){
				$total_price_dmp_4m3_3 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_3 = 0;
			foreach ($produk_dmp_10m3_3 as $x){
				$total_price_dmp_10m3_3 += $x['qty'] * $x['price'];
			}

			$produk_sc_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_3 = 0;
			foreach ($produk_sc_3 as $x){
				$total_price_sc_3 += $x['qty'] * $x['price'];
			}

			$produk_gns_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_3 = 0;
			foreach ($produk_gns_3 as $x){
				$total_price_gns_3 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_3 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_3'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_3 = 0;
			foreach ($produk_wl_sc_3 as $x){
				$total_price_wl_sc_3 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_3 = $rak_alat_3['vol_bbm_solar'];

			$total_3_biaya_bahan = $nilai_semen_3 + $nilai_pasir_3 + $nilai_batu1020_3 + $nilai_batu2030_3;
			$total_3_biaya_alat = ($total_price_bp_3 + $total_price_bp_2_3 + $total_price_bp_3_3) + ($total_price_tm_3 + $total_price_tm_2_3 + $total_price_tm_3_3 + $total_price_tm_4_3) + ($total_price_wl_3 + $total_price_wl_2_3 + $total_price_wl_3_3) + ($total_price_tr_3 + $total_price_tr_2_3 + $total_price_tr_3_3) + ($total_volume_solar_3 * $rak_alat_3['harga_solar']) + $rak_alat_3['insentif'] + $total_price_exc_3 + $total_price_dmp_4m3_3 + $total_price_dmp_10m3_3 + $total_price_sc_3 + $total_price_gns_3 + $total_price_wl_sc_3;
			$total_3_overhead = $rencana_kerja_3['overhead'];
			$total_3_diskonto =  ($total_3_nilai * 3) /100;
			$total_biaya_3_biaya = $total_3_biaya_bahan + $total_3_biaya_alat + $total_3_overhead + $total_3_diskonto;
			?>

			<?php
			//BULAN 4
			$date_4_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_3_akhir)));
			$date_4_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_4_awal)));

			$rencana_kerja_4 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$volume_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_4_produk_d = $rencana_kerja_4['vol_produk_d'];

			$total_4_volume = $volume_4_produk_a + $volume_4_produk_b + $volume_4_produk_c + $volume_4_produk_d;

			$nilai_jual_125_4 = $volume_4_produk_a * $rencana_kerja_4['price_a'];
			$nilai_jual_225_4 = $volume_4_produk_b * $rencana_kerja_4['price_b'];
			$nilai_jual_250_4 = $volume_4_produk_c * $rencana_kerja_4['price_c'];
			$nilai_jual_250_18_4 = $volume_4_produk_d * $rencana_kerja_4['price_d'];
			$nilai_jual_all_4 = $nilai_jual_125_4 + $nilai_jual_225_4 + $nilai_jual_250_4 + $nilai_jual_250_18_4;

			$total_4_nilai = $nilai_jual_all_4;

			//VOLUME
			$volume_rencana_kerja_4_produk_a = $rencana_kerja_4['vol_produk_a'];
			$volume_rencana_kerja_4_produk_b = $rencana_kerja_4['vol_produk_b'];
			$volume_rencana_kerja_4_produk_c = $rencana_kerja_4['vol_produk_c'];
			$volume_rencana_kerja_4_produk_d = $rencana_kerja_4['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_4 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_125_4 = 0;
			$total_volume_pasir_125_4 = 0;
			$total_volume_batu1020_125_4 = 0;
			$total_volume_batu2030_125_4 = 0;

			foreach ($komposisi_125_4 as $x){
				$total_volume_semen_125_4 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_4 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_4 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_4 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_4 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_225_4 = 0;
			$total_volume_pasir_225_4 = 0;
			$total_volume_batu1020_225_4 = 0;
			$total_volume_batu2030_225_4 = 0;

			foreach ($komposisi_225_4 as $x){
				$total_volume_semen_225_4 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_4 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_4 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_4 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_4 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_4 = 0;
			$total_volume_pasir_250_4 = 0;
			$total_volume_batu1020_250_4 = 0;
			$total_volume_batu2030_250_4 = 0;

			foreach ($komposisi_250_4 as $x){
				$total_volume_semen_250_4 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_4 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_4 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_4 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_4 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_4 = 0;
			$total_volume_pasir_250_2_4 = 0;
			$total_volume_batu1020_250_2_4 = 0;
			$total_volume_batu2030_250_2_4 = 0;

			foreach ($komposisi_250_2_4 as $x){
				$total_volume_semen_250_2_4 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_4 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_4 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_4 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_4 = $total_volume_semen_125_4 + $total_volume_semen_225_4 + $total_volume_semen_250_4 + $total_volume_semen_250_2_4;
			$total_volume_pasir_4 = $total_volume_pasir_125_4 + $total_volume_pasir_225_4 + $total_volume_pasir_250_4 + $total_volume_pasir_250_2_4;
			$total_volume_batu1020_4 = $total_volume_batu1020_125_4 + $total_volume_batu1020_225_4 + $total_volume_batu1020_250_4 + $total_volume_batu1020_250_2_4;
			$total_volume_batu2030_4 = $total_volume_batu2030_125_4 + $total_volume_batu2030_225_4 + $total_volume_batu2030_250_4 + $total_volume_batu2030_250_2_4;

			$nilai_semen_4 = $total_volume_semen_4 * $rencana_kerja_4['harga_semen'];
			$nilai_pasir_4 = $total_volume_pasir_4 * $rencana_kerja_4['harga_pasir'];
			$nilai_batu1020_4 = $total_volume_batu1020_4 * $rencana_kerja_4['harga_batu1020'];
			$nilai_batu2030_4 = $total_volume_batu2030_4 * $rencana_kerja_4['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_4 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->get()->row_array();

			$rak_alat_bp_4 = $rak_alat_4['penawaran_id_bp'];
			$rak_alat_bp_2_4 = $rak_alat_4['penawaran_id_bp_2'];
			$rak_alat_bp_3_4 = $rak_alat_4['penawaran_id_bp_3'];

			$rak_alat_tm_4 = $rak_alat_4['penawaran_id_tm'];
			$rak_alat_tm_2_4 = $rak_alat_4['penawaran_id_tm_2'];
			$rak_alat_tm_3_4 = $rak_alat_4['penawaran_id_tm_3'];
			$rak_alat_tm_4_4 = $rak_alat_4['penawaran_id_tm_4'];

			$rak_alat_wl_4 = $rak_alat_4['penawaran_id_wl'];
			$rak_alat_wl_2_4 = $rak_alat_4['penawaran_id_wl_2'];
			$rak_alat_wl_3_4 = $rakrak_alat_4_alat['penawaran_id_wl_3'];

			$rak_alat_tr_4 = $rak_alat_4['penawaran_id_tr'];
			$rak_alat_tr_2_4 = $rak_alat_4['penawaran_id_tr_2'];
			$rak_alat_tr_3_4 = $rak_alat_4['penawaran_id_tr_3'];

			$rak_alat_exc_4 = $rak_alat_4['penawaran_id_exc'];
			$rak_alat_dmp_4m3_4 = $rak_alat_4['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_4 = $rak_alat_4['penawaran_id_dmp_10m3'];
			$rak_alat_sc_4 = $rak_alat_4['penawaran_id_sc'];
			$rak_alat_gns_4 = $rak_alat_4['penawaran_id_gns'];
			$rak_alat_wl_sc_4 = $rak_alat_4['penawaran_id_wl_sc'];

			$produk_bp_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->where("ppp.id = '$rak_alat_bp_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_4 = 0;
			foreach ($produk_bp_4 as $x){
				$total_price_bp_4 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_4 = 0;
			foreach ($produk_bp_2_4 as $x){
				$total_price_bp_2_4 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_4_awal' and '$date_4_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_4 = 0;
			foreach ($produk_bp_3_4 as $x){
				$total_price_bp_3_4 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4 = 0;
			foreach ($produk_tm_4 as $x){
				$total_price_tm_4 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_4 = 0;
			foreach ($produk_tm_2_4 as $x){
				$total_price_tm_2_4 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_4 = 0;
			foreach ($produk_tm_3_4 as $x){
				$total_price_tm_3_4 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_4 = 0;
			foreach ($produk_tm_4_4 as $x){
				$total_price_tm_4_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_4 = 0;
			foreach ($produk_wl_4 as $x){
				$total_price_wl_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_4 = 0;
			foreach ($produk_wl_2_4 as $x){
				$total_price_wl_2_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_4 = 0;
			foreach ($produk_wl_3_4 as $x){
				$total_price_wl_3_4 += $x['qty'] * $x['price'];
			}

			$produk_tr_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_4 = 0;
			foreach ($produk_tr_4 as $x){
				$total_price_tr_4 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_4 = 0;
			foreach ($produk_tr_2_4 as $x){
				$total_price_tr_2_4 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_4 = 0;
			foreach ($produk_tr_3_4 as $x){
				$total_price_tr_3_4 += $x['qty'] * $x['price'];
			}

			$produk_exc_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_4 = 0;
			foreach ($produk_exc_4 as $x){
				$total_price_exc_4 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_4 = 0;
			foreach ($produk_dmp_4m3_4 as $x){
				$total_price_dmp_4m3_4 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_4 = 0;
			foreach ($produk_dmp_10m3_4 as $x){
				$total_price_dmp_10m3_4 += $x['qty'] * $x['price'];
			}

			$produk_sc_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_4 = 0;
			foreach ($produk_sc_4 as $x){
				$total_price_sc_4 += $x['qty'] * $x['price'];
			}

			$produk_gns_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_4 = 0;
			foreach ($produk_gns_4 as $x){
				$total_price_gns_4 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_4 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_4'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_4 = 0;
			foreach ($produk_wl_sc_4 as $x){
				$total_price_wl_sc_4 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_4 = $rak_alat_4['vol_bbm_solar'];

			$total_4_biaya_bahan = $nilai_semen_4 + $nilai_pasir_4 + $nilai_batu1020_4 + $nilai_batu2030_4;
			$total_4_biaya_alat = ($total_price_bp_4 + $total_price_bp_2_4 + $total_price_bp_3_4) + ($total_price_tm_4 + $total_price_tm_2_4 + $total_price_tm_3_4 + $total_price_tm_4_4) + ($total_price_wl_4 + $total_price_wl_2_4 + $total_price_wl_3_4) + ($total_price_tr_4 + $total_price_tr_2_4 + $total_price_tr_3_4) + ($total_volume_solar_4 * $rak_alat_4['harga_solar']) + $rak_alat_4['insentif'] + $total_price_exc_4 + $total_price_dmp_4m3_4 + $total_price_dmp_10m3_4 + $total_price_sc_4 + $total_price_gns_4 + $total_price_wl_sc_4;
			$total_4_overhead = $rencana_kerja_4['overhead'];
			$total_4_diskonto =  ($total_4_nilai * 3) /100;
			$total_biaya_4_biaya = $total_4_biaya_bahan + $total_4_biaya_alat + $total_4_overhead + $total_4_diskonto;
			?>

			<?php
			//BULAN 5
			$date_5_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_4_akhir)));
			$date_5_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_5_awal)));

			$rencana_kerja_5 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$volume_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_5_produk_d = $rencana_kerja_5['vol_produk_d'];

			$total_5_volume = $volume_5_produk_a + $volume_5_produk_b + $volume_5_produk_c + $volume_5_produk_d;

			$nilai_jual_125_5 = $volume_5_produk_a * $rencana_kerja_5['price_a'];
			$nilai_jual_225_5 = $volume_5_produk_b * $rencana_kerja_5['price_b'];
			$nilai_jual_250_5 = $volume_5_produk_c * $rencana_kerja_5['price_c'];
			$nilai_jual_250_18_5 = $volume_5_produk_d * $rencana_kerja_5['price_d'];
			$nilai_jual_all_5 = $nilai_jual_125_5 + $nilai_jual_225_5 + $nilai_jual_250_5 + $nilai_jual_250_18_5;

			$total_5_nilai = $nilai_jual_all_5;

			//VOLUME
			$volume_rencana_kerja_5_produk_a = $rencana_kerja_5['vol_produk_a'];
			$volume_rencana_kerja_5_produk_b = $rencana_kerja_5['vol_produk_b'];
			$volume_rencana_kerja_5_produk_c = $rencana_kerja_5['vol_produk_c'];
			$volume_rencana_kerja_5_produk_d = $rencana_kerja_5['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_5 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_125_5 = 0;
			$total_volume_pasir_125_5 = 0;
			$total_volume_batu1020_125_5 = 0;
			$total_volume_batu2030_125_5 = 0;

			foreach ($komposisi_125_5 as $x){
				$total_volume_semen_125_5 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_5 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_5 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_5 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_5 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_225_5 = 0;
			$total_volume_pasir_225_5 = 0;
			$total_volume_batu1020_225_5 = 0;
			$total_volume_batu2030_225_5 = 0;

			foreach ($komposisi_225_5 as $x){
				$total_volume_semen_225_5 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_5 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_5 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_5 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_5 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_5 = 0;
			$total_volume_pasir_250_5 = 0;
			$total_volume_batu1020_250_5 = 0;
			$total_volume_batu2030_250_5 = 0;

			foreach ($komposisi_250_5 as $x){
				$total_volume_semen_250_5 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_5 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_5 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_5 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_5 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_5 = 0;
			$total_volume_pasir_250_2_5 = 0;
			$total_volume_batu1020_250_2_5 = 0;
			$total_volume_batu2030_250_2_5 = 0;

			foreach ($komposisi_250_2_5 as $x){
				$total_volume_semen_250_2_5 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_5 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_5 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_5 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_5 = $total_volume_semen_125_5 + $total_volume_semen_225_5 + $total_volume_semen_250_5 + $total_volume_semen_250_2_5;
			$total_volume_pasir_5 = $total_volume_pasir_125_5 + $total_volume_pasir_225_5 + $total_volume_pasir_250_5 + $total_volume_pasir_250_2_5;
			$total_volume_batu1020_5 = $total_volume_batu1020_125_5 + $total_volume_batu1020_225_5 + $total_volume_batu1020_250_5 + $total_volume_batu1020_250_2_5;
			$total_volume_batu2030_5 = $total_volume_batu2030_125_5 + $total_volume_batu2030_225_5 + $total_volume_batu2030_250_5 + $total_volume_batu2030_250_2_5;

			$nilai_semen_5 = $total_volume_semen_5 * $rencana_kerja_5['harga_semen'];
			$nilai_pasir_5 = $total_volume_pasir_5 * $rencana_kerja_5['harga_pasir'];
			$nilai_batu1020_5 = $total_volume_batu1020_5 * $rencana_kerja_5['harga_batu1020'];
			$nilai_batu2030_5 = $total_volume_batu2030_5 * $rencana_kerja_5['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_5 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->get()->row_array();

			$rak_alat_bp_5 = $rak_alat_5['penawaran_id_bp'];
			$rak_alat_bp_2_5 = $rak_alat_5['penawaran_id_bp_2'];
			$rak_alat_bp_3_5 = $rak_alat_5['penawaran_id_bp_3'];

			$rak_alat_tm_5 = $rak_alat_5['penawaran_id_tm'];
			$rak_alat_tm_2_5 = $rak_alat_5['penawaran_id_tm_2'];
			$rak_alat_tm_3_5 = $rak_alat_5['penawaran_id_tm_3'];
			$rak_alat_tm_4_5 = $rak_alat_5['penawaran_id_tm_4'];

			$rak_alat_wl_5 = $rak_alat_5['penawaran_id_wl'];
			$rak_alat_wl_2_5 = $rak_alat_5['penawaran_id_wl_2'];
			$rak_alat_wl_3_5 = $rakrak_alat_5_alat['penawaran_id_wl_3'];

			$rak_alat_tr_5 = $rak_alat_5['penawaran_id_tr'];
			$rak_alat_tr_2_5 = $rak_alat_5['penawaran_id_tr_2'];
			$rak_alat_tr_3_5 = $rak_alat_5['penawaran_id_tr_3'];

			$rak_alat_exc_5 = $rak_alat_5['penawaran_id_exc'];
			$rak_alat_dmp_4m3_5 = $rak_alat_5['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_5 = $rak_alat_5['penawaran_id_dmp_10m3'];
			$rak_alat_sc_5 = $rak_alat_5['penawaran_id_sc'];
			$rak_alat_gns_5 = $rak_alat_5['penawaran_id_gns'];
			$rak_alat_wl_sc_5 = $rak_alat_5['penawaran_id_wl_sc'];

			$produk_bp_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->where("ppp.id = '$rak_alat_bp_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_5 = 0;
			foreach ($produk_bp_5 as $x){
				$total_price_bp_5 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_5 = 0;
			foreach ($produk_bp_2_5 as $x){
				$total_price_bp_2_5 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_5_awal' and '$date_5_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_5 = 0;
			foreach ($produk_bp_3_5 as $x){
				$total_price_bp_3_5 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_5 = 0;
			foreach ($produk_tm_5 as $x){
				$total_price_tm_5 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_5 = 0;
			foreach ($produk_tm_2_5 as $x){
				$total_price_tm_2_5 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_5 = 0;
			foreach ($produk_tm_3_5 as $x){
				$total_price_tm_3_5 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_5 = 0;
			foreach ($produk_tm_4_5 as $x){
				$total_price_tm_4_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_5 = 0;
			foreach ($produk_wl_5 as $x){
				$total_price_wl_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_5 = 0;
			foreach ($produk_wl_2_5 as $x){
				$total_price_wl_2_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_5 = 0;
			foreach ($produk_wl_3_5 as $x){
				$total_price_wl_3_5 += $x['qty'] * $x['price'];
			}

			$produk_tr_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_5 = 0;
			foreach ($produk_tr_5 as $x){
				$total_price_tr_5 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_5 = 0;
			foreach ($produk_tr_2_5 as $x){
				$total_price_tr_2_5 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_5 = 0;
			foreach ($produk_tr_3_5 as $x){
				$total_price_tr_3_5 += $x['qty'] * $x['price'];
			}

			$produk_exc_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_5 = 0;
			foreach ($produk_exc_5 as $x){
				$total_price_exc_5 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_5 = 0;
			foreach ($produk_dmp_4m3_5 as $x){
				$total_price_dmp_4m3_5 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_5 = 0;
			foreach ($produk_dmp_10m3_5 as $x){
				$total_price_dmp_10m3_5 += $x['qty'] * $x['price'];
			}

			$produk_sc_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_5 = 0;
			foreach ($produk_sc_5 as $x){
				$total_price_sc_5 += $x['qty'] * $x['price'];
			}

			$produk_gns_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_5 = 0;
			foreach ($produk_gns_5 as $x){
				$total_price_gns_5 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_5 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_5'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_5 = 0;
			foreach ($produk_wl_sc_5 as $x){
				$total_price_wl_sc_5 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_5 = $rak_alat_5['vol_bbm_solar'];

			$total_5_biaya_bahan = $nilai_semen_5 + $nilai_pasir_5 + $nilai_batu1020_5 + $nilai_batu2030_5;
			$total_5_biaya_alat = ($total_price_bp_5 + $total_price_bp_2_5 + $total_price_bp_3_5) + ($total_price_tm_5 + $total_price_tm_2_5 + $total_price_tm_3_5 + $total_price_tm_4_5) + ($total_price_wl_5 + $total_price_wl_2_5 + $total_price_wl_3_5) + ($total_price_tr_5 + $total_price_tr_2_5 + $total_price_tr_3_5) + ($total_volume_solar_5 * $rak_alat_5['harga_solar']) + $rak_alat_5['insentif'] + $total_price_exc_5 + $total_price_dmp_4m3_5 + $total_price_dmp_10m3_5 + $total_price_sc_5 + $total_price_gns_5 + $total_price_wl_sc_5;
			$total_5_overhead = $rencana_kerja_5['overhead'];
			$total_5_diskonto =  ($total_5_nilai * 3) /100;
			$total_biaya_5_biaya = $total_5_biaya_bahan + $total_5_biaya_alat + $total_5_overhead + $total_5_diskonto;
			?>

			<?php
			//BULAN 6
			$date_6_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_5_akhir)));
			$date_6_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_6_awal)));

			$rencana_kerja_6 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$volume_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_6_produk_d = $rencana_kerja_6['vol_produk_d'];

			$total_6_volume = $volume_6_produk_a + $volume_6_produk_b + $volume_6_produk_c + $volume_6_produk_d;

			$nilai_jual_125_6 = $volume_6_produk_a * $rencana_kerja_6['price_a'];
			$nilai_jual_225_6 = $volume_6_produk_b * $rencana_kerja_6['price_b'];
			$nilai_jual_250_6 = $volume_6_produk_c * $rencana_kerja_6['price_c'];
			$nilai_jual_250_18_6 = $volume_6_produk_d * $rencana_kerja_6['price_d'];
			$nilai_jual_all_6 = $nilai_jual_125_6 + $nilai_jual_225_6 + $nilai_jual_250_6 + $nilai_jual_250_18_6;

			$total_6_nilai = $nilai_jual_all_6;

			//VOLUME
			$volume_rencana_kerja_6_produk_a = $rencana_kerja_6['vol_produk_a'];
			$volume_rencana_kerja_6_produk_b = $rencana_kerja_6['vol_produk_b'];
			$volume_rencana_kerja_6_produk_c = $rencana_kerja_6['vol_produk_c'];
			$volume_rencana_kerja_6_produk_d = $rencana_kerja_6['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_6 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_125_6 = 0;
			$total_volume_pasir_125_6 = 0;
			$total_volume_batu1020_125_6 = 0;
			$total_volume_batu2030_125_6 = 0;

			foreach ($komposisi_125_6 as $x){
				$total_volume_semen_125_6 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_6 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_6 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_6 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_6 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_225_6 = 0;
			$total_volume_pasir_225_6 = 0;
			$total_volume_batu1020_225_6 = 0;
			$total_volume_batu2030_225_6 = 0;

			foreach ($komposisi_225_6 as $x){
				$total_volume_semen_225_6 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_6 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_6 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_6 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_6 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_6 = 0;
			$total_volume_pasir_250_6 = 0;
			$total_volume_batu1020_250_6 = 0;
			$total_volume_batu2030_250_6 = 0;

			foreach ($komposisi_250_6 as $x){
				$total_volume_semen_250_6 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_6 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_6 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_6 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_6 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_6 = 0;
			$total_volume_pasir_250_2_6 = 0;
			$total_volume_batu1020_250_2_6 = 0;
			$total_volume_batu2030_250_2_6 = 0;

			foreach ($komposisi_250_2_6 as $x){
				$total_volume_semen_250_2_6 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_6 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_6 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_6 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_6 = $total_volume_semen_125_6 + $total_volume_semen_225_6 + $total_volume_semen_250_6 + $total_volume_semen_250_2_6;
			$total_volume_pasir_6 = $total_volume_pasir_125_6 + $total_volume_pasir_225_6 + $total_volume_pasir_250_6 + $total_volume_pasir_250_2_6;
			$total_volume_batu1020_6 = $total_volume_batu1020_125_6 + $total_volume_batu1020_225_6 + $total_volume_batu1020_250_6 + $total_volume_batu1020_250_2_6;
			$total_volume_batu2030_6 = $total_volume_batu2030_125_6 + $total_volume_batu2030_225_6 + $total_volume_batu2030_250_6 + $total_volume_batu2030_250_2_6;

			$nilai_semen_6 = $total_volume_semen_6 * $rencana_kerja_6['harga_semen'];
			$nilai_pasir_6 = $total_volume_pasir_6 * $rencana_kerja_6['harga_pasir'];
			$nilai_batu1020_6 = $total_volume_batu1020_6 * $rencana_kerja_6['harga_batu1020'];
			$nilai_batu2030_6 = $total_volume_batu2030_6 * $rencana_kerja_6['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_6 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->get()->row_array();

			$rak_alat_bp_6 = $rak_alat_6['penawaran_id_bp'];
			$rak_alat_bp_2_6 = $rak_alat_6['penawaran_id_bp_2'];
			$rak_alat_bp_3_6 = $rak_alat_6['penawaran_id_bp_3'];

			$rak_alat_tm_6 = $rak_alat_6['penawaran_id_tm'];
			$rak_alat_tm_2_6 = $rak_alat_6['penawaran_id_tm_2'];
			$rak_alat_tm_3_6 = $rak_alat_6['penawaran_id_tm_3'];
			$rak_alat_tm_4_6 = $rak_alat_6['penawaran_id_tm_4'];

			$rak_alat_wl_6 = $rak_alat_6['penawaran_id_wl'];
			$rak_alat_wl_2_6 = $rak_alat_6['penawaran_id_wl_2'];
			$rak_alat_wl_3_6 = $rakrak_alat_6_alat['penawaran_id_wl_3'];

			$rak_alat_tr_6 = $rak_alat_6['penawaran_id_tr'];
			$rak_alat_tr_2_6 = $rak_alat_6['penawaran_id_tr_2'];
			$rak_alat_tr_3_6 = $rak_alat_6['penawaran_id_tr_3'];

			$rak_alat_exc_6 = $rak_alat_6['penawaran_id_exc'];
			$rak_alat_dmp_4m3_6 = $rak_alat_6['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_6 = $rak_alat_6['penawaran_id_dmp_10m3'];
			$rak_alat_sc_6 = $rak_alat_6['penawaran_id_sc'];
			$rak_alat_gns_6 = $rak_alat_6['penawaran_id_gns'];
			$rak_alat_wl_sc_6 = $rak_alat_6['penawaran_id_wl_sc'];

			$produk_bp_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->where("ppp.id = '$rak_alat_bp_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_6 = 0;
			foreach ($produk_bp_6 as $x){
				$total_price_bp_6 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_6 = 0;
			foreach ($produk_bp_2_6 as $x){
				$total_price_bp_2_6 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_6_awal' and '$date_6_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_6 = 0;
			foreach ($produk_bp_3_6 as $x){
				$total_price_bp_3_6 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_6 = 0;
			foreach ($produk_tm_6 as $x){
				$total_price_tm_6 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_6 = 0;
			foreach ($produk_tm_2_6 as $x){
				$total_price_tm_2_6 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_6 = 0;
			foreach ($produk_tm_3_6 as $x){
				$total_price_tm_3_6 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_6 = 0;
			foreach ($produk_tm_4_6 as $x){
				$total_price_tm_4_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_6 = 0;
			foreach ($produk_wl_6 as $x){
				$total_price_wl_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_6 = 0;
			foreach ($produk_wl_2_6 as $x){
				$total_price_wl_2_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_6 = 0;
			foreach ($produk_wl_3_6 as $x){
				$total_price_wl_3_6 += $x['qty'] * $x['price'];
			}

			$produk_tr_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_6 = 0;
			foreach ($produk_tr_6 as $x){
				$total_price_tr_6 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_6 = 0;
			foreach ($produk_tr_2_6 as $x){
				$total_price_tr_2_6 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_6 = 0;
			foreach ($produk_tr_3_6 as $x){
				$total_price_tr_3_6 += $x['qty'] * $x['price'];
			}

			$produk_exc_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_6 = 0;
			foreach ($produk_exc_6 as $x){
				$total_price_exc_6 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_6 = 0;
			foreach ($produk_dmp_4m3_6 as $x){
				$total_price_dmp_4m3_6 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_6 = 0;
			foreach ($produk_dmp_10m3_6 as $x){
				$total_price_dmp_10m3_6 += $x['qty'] * $x['price'];
			}

			$produk_sc_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_6 = 0;
			foreach ($produk_sc_6 as $x){
				$total_price_sc_6 += $x['qty'] * $x['price'];
			}

			$produk_gns_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_6 = 0;
			foreach ($produk_gns_6 as $x){
				$total_price_gns_6 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_6 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_6'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_6 = 0;
			foreach ($produk_wl_sc_6 as $x){
				$total_price_wl_sc_6 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_6 = $rak_alat_6['vol_bbm_solar'];

			$total_6_biaya_bahan = $nilai_semen_6 + $nilai_pasir_6 + $nilai_batu1020_6 + $nilai_batu2030_6;
			$total_6_biaya_alat = ($total_price_bp_6 + $total_price_bp_2_6 + $total_price_bp_3_6) + ($total_price_tm_6 + $total_price_tm_2_6 + $total_price_tm_3_6 + $total_price_tm_4_6) + ($total_price_wl_6 + $total_price_wl_2_6 + $total_price_wl_3_6) + ($total_price_tr_6 + $total_price_tr_2_6 + $total_price_tr_3_6) + ($total_volume_solar_6 * $rak_alat_6['harga_solar']) + $rak_alat_6['insentif'] + $total_price_exc_6 + $total_price_dmp_4m3_6 + $total_price_dmp_10m3_6 + $total_price_sc_6 + $total_price_gns_6 + $total_price_wl_sc_6;
			$total_6_overhead = $rencana_kerja_6['overhead'];
			$total_6_diskonto =  ($total_6_nilai * 3) /100;
			$total_biaya_6_biaya = $total_6_biaya_bahan + $total_6_biaya_alat + $total_6_overhead + $total_6_diskonto;
			?>

			<?php
			//BULAN 7
			$date_7_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_6_akhir)));
			$date_7_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_7_awal)));

			$rencana_kerja_7 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->row_array();

			$volume_7_produk_a = $rencana_kerja_7['vol_produk_a'];
			$volume_7_produk_b = $rencana_kerja_7['vol_produk_b'];
			$volume_7_produk_c = $rencana_kerja_7['vol_produk_c'];
			$volume_7_produk_d = $rencana_kerja_7['vol_produk_d'];

			$total_7_volume = $volume_7_produk_a + $volume_7_produk_b + $volume_7_produk_c + $volume_7_produk_d;

			$nilai_jual_125_7 = $volume_7_produk_a * $rencana_kerja_7['price_a'];
			$nilai_jual_225_7 = $volume_7_produk_b * $rencana_kerja_7['price_b'];
			$nilai_jual_250_7 = $volume_7_produk_c * $rencana_kerja_7['price_c'];
			$nilai_jual_250_18_7 = $volume_7_produk_d * $rencana_kerja_7['price_d'];
			$nilai_jual_all_7 = $nilai_jual_125_7 + $nilai_jual_225_7 + $nilai_jual_250_7 + $nilai_jual_250_18_7;

			$total_7_nilai = $nilai_jual_all_7;

			//VOLUME
			$volume_rencana_kerja_7_produk_a = $rencana_kerja_7['vol_produk_a'];
			$volume_rencana_kerja_7_produk_b = $rencana_kerja_7['vol_produk_b'];
			$volume_rencana_kerja_7_produk_c = $rencana_kerja_7['vol_produk_c'];
			$volume_rencana_kerja_7_produk_d = $rencana_kerja_7['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_7 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_125_7 = 0;
			$total_volume_pasir_125_7 = 0;
			$total_volume_batu1020_125_7 = 0;
			$total_volume_batu2030_125_7 = 0;

			foreach ($komposisi_125_7 as $x){
				$total_volume_semen_125_7 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_7 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_7 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_7 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_7 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_225_7 = 0;
			$total_volume_pasir_225_7 = 0;
			$total_volume_batu1020_225_7 = 0;
			$total_volume_batu2030_225_7 = 0;

			foreach ($komposisi_225_7 as $x){
				$total_volume_semen_225_7 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_7 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_7 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_7 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_7 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_250_7 = 0;
			$total_volume_pasir_250_7 = 0;
			$total_volume_batu1020_250_7 = 0;
			$total_volume_batu2030_250_7 = 0;

			foreach ($komposisi_250_7 as $x){
				$total_volume_semen_250_7 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_7 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_7 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_7 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_7 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_7 = 0;
			$total_volume_pasir_250_2_7 = 0;
			$total_volume_batu1020_250_2_7 = 0;
			$total_volume_batu2030_250_2_7 = 0;

			foreach ($komposisi_250_2_7 as $x){
				$total_volume_semen_250_2_7 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_7 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_7 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_7 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_7 = $total_volume_semen_125_7 + $total_volume_semen_225_7 + $total_volume_semen_250_7 + $total_volume_semen_250_2_7;
			$total_volume_pasir_7 = $total_volume_pasir_125_7 + $total_volume_pasir_225_7 + $total_volume_pasir_250_7 + $total_volume_pasir_250_2_7;
			$total_volume_batu1020_7 = $total_volume_batu1020_125_7 + $total_volume_batu1020_225_7 + $total_volume_batu1020_250_7 + $total_volume_batu1020_250_2_7;
			$total_volume_batu2030_7 = $total_volume_batu2030_125_7 + $total_volume_batu2030_225_7 + $total_volume_batu2030_250_7 + $total_volume_batu2030_250_2_7;

			$nilai_semen_7 = $total_volume_semen_7 * $rencana_kerja_7['harga_semen'];
			$nilai_pasir_7 = $total_volume_pasir_7 * $rencana_kerja_7['harga_pasir'];
			$nilai_batu1020_7 = $total_volume_batu1020_7 * $rencana_kerja_7['harga_batu1020'];
			$nilai_batu2030_7 = $total_volume_batu2030_7 * $rencana_kerja_7['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_7 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->get()->row_array();

			$rak_alat_bp_7 = $rak_alat_7['penawaran_id_bp'];
			$rak_alat_bp_2_7 = $rak_alat_7['penawaran_id_bp_2'];
			$rak_alat_bp_3_7 = $rak_alat_7['penawaran_id_bp_3'];

			$rak_alat_tm_7 = $rak_alat_7['penawaran_id_tm'];
			$rak_alat_tm_2_7 = $rak_alat_7['penawaran_id_tm_2'];
			$rak_alat_tm_3_7 = $rak_alat_7['penawaran_id_tm_3'];
			$rak_alat_tm_4_7 = $rak_alat_7['penawaran_id_tm_4'];

			$rak_alat_wl_7 = $rak_alat_7['penawaran_id_wl'];
			$rak_alat_wl_2_7 = $rak_alat_7['penawaran_id_wl_2'];
			$rak_alat_wl_3_7 = $rakrak_alat_7_alat['penawaran_id_wl_3'];

			$rak_alat_tr_7 = $rak_alat_7['penawaran_id_tr'];
			$rak_alat_tr_2_7 = $rak_alat_7['penawaran_id_tr_2'];
			$rak_alat_tr_3_7 = $rak_alat_7['penawaran_id_tr_3'];

			$rak_alat_exc_7 = $rak_alat_7['penawaran_id_exc'];
			$rak_alat_dmp_4m3_7 = $rak_alat_7['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_7 = $rak_alat_7['penawaran_id_dmp_10m3'];
			$rak_alat_sc_7 = $rak_alat_7['penawaran_id_sc'];
			$rak_alat_gns_7 = $rak_alat_7['penawaran_id_gns'];
			$rak_alat_wl_sc_7 = $rak_alat_7['penawaran_id_wl_sc'];

			$produk_bp_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->where("ppp.id = '$rak_alat_bp_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_7 = 0;
			foreach ($produk_bp_7 as $x){
				$total_price_bp_7 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_7 = 0;
			foreach ($produk_bp_2_7 as $x){
				$total_price_bp_2_7 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_7_awal' and '$date_7_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_7 = 0;
			foreach ($produk_bp_3_7 as $x){
				$total_price_bp_3_7 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_7 = 0;
			foreach ($produk_tm_7 as $x){
				$total_price_tm_7 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_7 = 0;
			foreach ($produk_tm_2_7 as $x){
				$total_price_tm_2_7 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_7 = 0;
			foreach ($produk_tm_3_7 as $x){
				$total_price_tm_3_7 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_7 = 0;
			foreach ($produk_tm_4_7 as $x){
				$total_price_tm_4_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_7 = 0;
			foreach ($produk_wl_7 as $x){
				$total_price_wl_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_7 = 0;
			foreach ($produk_wl_2_7 as $x){
				$total_price_wl_2_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_7 = 0;
			foreach ($produk_wl_3_7 as $x){
				$total_price_wl_3_7 += $x['qty'] * $x['price'];
			}

			$produk_tr_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_7 = 0;
			foreach ($produk_tr_7 as $x){
				$total_price_tr_7 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_7 = 0;
			foreach ($produk_tr_2_7 as $x){
				$total_price_tr_2_7 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_7 = 0;
			foreach ($produk_tr_3_7 as $x){
				$total_price_tr_3_7 += $x['qty'] * $x['price'];
			}

			$produk_exc_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_7 = 0;
			foreach ($produk_exc_7 as $x){
				$total_price_exc_7 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_7 = 0;
			foreach ($produk_dmp_4m3_7 as $x){
				$total_price_dmp_4m3_7 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_7 = 0;
			foreach ($produk_dmp_10m3_7 as $x){
				$total_price_dmp_10m3_7 += $x['qty'] * $x['price'];
			}

			$produk_sc_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_7 = 0;
			foreach ($produk_sc_7 as $x){
				$total_price_sc_7 += $x['qty'] * $x['price'];
			}

			$produk_gns_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_7 = 0;
			foreach ($produk_gns_7 as $x){
				$total_price_gns_7 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_7 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_7'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_7 = 0;
			foreach ($produk_wl_sc_7 as $x){
				$total_price_wl_sc_7 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_7 = $rak_alat_7['vol_bbm_solar'];

			$total_7_biaya_bahan = $nilai_semen_7 + $nilai_pasir_7 + $nilai_batu1020_7 + $nilai_batu2030_7;
			$total_7_biaya_alat = ($total_price_bp_7 + $total_price_bp_2_7 + $total_price_bp_3_7) + ($total_price_tm_7 + $total_price_tm_2_7 + $total_price_tm_3_7 + $total_price_tm_4_7) + ($total_price_wl_7 + $total_price_wl_2_7 + $total_price_wl_3_7) + ($total_price_tr_7 + $total_price_tr_2_7 + $total_price_tr_3_7) + ($total_volume_solar_7 * $rak_alat_7['harga_solar']) + $rak_alat_7['insentif'] + $total_price_exc_7 + $total_price_dmp_4m3_7 + $total_price_dmp_10m3_7 + $total_price_sc_7 + $total_price_gns_7 + $total_price_wl_sc_7;
			$total_7_overhead = $rencana_kerja_7['overhead'];
			$total_7_diskonto =  ($total_7_nilai * 3) /100;
			$total_biaya_7_biaya = $total_7_biaya_bahan + $total_7_biaya_alat + $total_7_overhead + $total_7_diskonto;
			?>

			<?php
			//BULAN 8
			$date_8_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_7_akhir)));
			$date_8_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_8_awal)));

			$rencana_kerja_8 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->row_array();

			$volume_8_produk_a = $rencana_kerja_8['vol_produk_a'];
			$volume_8_produk_b = $rencana_kerja_8['vol_produk_b'];
			$volume_8_produk_c = $rencana_kerja_8['vol_produk_c'];
			$volume_8_produk_d = $rencana_kerja_8['vol_produk_d'];

			$total_8_volume = $volume_8_produk_a + $volume_8_produk_b + $volume_8_produk_c + $volume_8_produk_d;

			$nilai_jual_125_8 = $volume_8_produk_a * $rencana_kerja_8['price_a'];
			$nilai_jual_225_8 = $volume_8_produk_b * $rencana_kerja_8['price_b'];
			$nilai_jual_250_8 = $volume_8_produk_c * $rencana_kerja_8['price_c'];
			$nilai_jual_250_18_8 = $volume_8_produk_d * $rencana_kerja_8['price_d'];
			$nilai_jual_all_8 = $nilai_jual_125_8 + $nilai_jual_225_8 + $nilai_jual_250_8 + $nilai_jual_250_18_8;

			$total_8_nilai = $nilai_jual_all_8;

			//VOLUME
			$volume_rencana_kerja_8_produk_a = $rencana_kerja_8['vol_produk_a'];
			$volume_rencana_kerja_8_produk_b = $rencana_kerja_8['vol_produk_b'];
			$volume_rencana_kerja_8_produk_c = $rencana_kerja_8['vol_produk_c'];
			$volume_rencana_kerja_8_produk_d = $rencana_kerja_8['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_8 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_125_8 = 0;
			$total_volume_pasir_125_8 = 0;
			$total_volume_batu1020_125_8 = 0;
			$total_volume_batu2030_125_8 = 0;

			foreach ($komposisi_125_8 as $x){
				$total_volume_semen_125_8 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_8 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_8 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_8 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_8 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_225_8 = 0;
			$total_volume_pasir_225_8 = 0;
			$total_volume_batu1020_225_8 = 0;
			$total_volume_batu2030_225_8 = 0;

			foreach ($komposisi_225_8 as $x){
				$total_volume_semen_225_8 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_8 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_8 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_8 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_8 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_250_8 = 0;
			$total_volume_pasir_250_8 = 0;
			$total_volume_batu1020_250_8 = 0;
			$total_volume_batu2030_250_8 = 0;

			foreach ($komposisi_250_8 as $x){
				$total_volume_semen_250_8 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_8 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_8 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_8 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_8 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_8 = 0;
			$total_volume_pasir_250_2_8 = 0;
			$total_volume_batu1020_250_2_8 = 0;
			$total_volume_batu2030_250_2_8 = 0;

			foreach ($komposisi_250_2_8 as $x){
				$total_volume_semen_250_2_8 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_8 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_8 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_8 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_8 = $total_volume_semen_125_8 + $total_volume_semen_225_8 + $total_volume_semen_250_8 + $total_volume_semen_250_2_8;
			$total_volume_pasir_8 = $total_volume_pasir_125_8 + $total_volume_pasir_225_8 + $total_volume_pasir_250_8 + $total_volume_pasir_250_2_8;
			$total_volume_batu1020_8 = $total_volume_batu1020_125_8 + $total_volume_batu1020_225_8 + $total_volume_batu1020_250_8 + $total_volume_batu1020_250_2_8;
			$total_volume_batu2030_8 = $total_volume_batu2030_125_8 + $total_volume_batu2030_225_8 + $total_volume_batu2030_250_8 + $total_volume_batu2030_250_2_8;

			$nilai_semen_8 = $total_volume_semen_8 * $rencana_kerja_8['harga_semen'];
			$nilai_pasir_8 = $total_volume_pasir_8 * $rencana_kerja_8['harga_pasir'];
			$nilai_batu1020_8 = $total_volume_batu1020_8 * $rencana_kerja_8['harga_batu1020'];
			$nilai_batu2030_8 = $total_volume_batu2030_8 * $rencana_kerja_8['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_8 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->get()->row_array();

			$rak_alat_bp_8 = $rak_alat_8['penawaran_id_bp'];
			$rak_alat_bp_2_8 = $rak_alat_8['penawaran_id_bp_2'];
			$rak_alat_bp_3_8 = $rak_alat_8['penawaran_id_bp_3'];

			$rak_alat_tm_8 = $rak_alat_8['penawaran_id_tm'];
			$rak_alat_tm_2_8 = $rak_alat_8['penawaran_id_tm_2'];
			$rak_alat_tm_3_8 = $rak_alat_8['penawaran_id_tm_3'];
			$rak_alat_tm_4_8 = $rak_alat_8['penawaran_id_tm_4'];

			$rak_alat_wl_8 = $rak_alat_8['penawaran_id_wl'];
			$rak_alat_wl_2_8 = $rak_alat_8['penawaran_id_wl_2'];
			$rak_alat_wl_3_8 = $rakrak_alat_8_alat['penawaran_id_wl_3'];

			$rak_alat_tr_8 = $rak_alat_8['penawaran_id_tr'];
			$rak_alat_tr_2_8 = $rak_alat_8['penawaran_id_tr_2'];
			$rak_alat_tr_3_8 = $rak_alat_8['penawaran_id_tr_3'];

			$rak_alat_exc_8 = $rak_alat_8['penawaran_id_exc'];
			$rak_alat_dmp_4m3_8 = $rak_alat_8['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_8 = $rak_alat_8['penawaran_id_dmp_10m3'];
			$rak_alat_sc_8 = $rak_alat_8['penawaran_id_sc'];
			$rak_alat_gns_8 = $rak_alat_8['penawaran_id_gns'];
			$rak_alat_wl_sc_8 = $rak_alat_8['penawaran_id_wl_sc'];

			$produk_bp_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->where("ppp.id = '$rak_alat_bp_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_8 = 0;
			foreach ($produk_bp_8 as $x){
				$total_price_bp_8 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_8 = 0;
			foreach ($produk_bp_2_8 as $x){
				$total_price_bp_2_8 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_8_awal' and '$date_8_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_8 = 0;
			foreach ($produk_bp_3_8 as $x){
				$total_price_bp_3_8 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_8 = 0;
			foreach ($produk_tm_8 as $x){
				$total_price_tm_8 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_8 = 0;
			foreach ($produk_tm_2_8 as $x){
				$total_price_tm_2_8 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_8 = 0;
			foreach ($produk_tm_3_8 as $x){
				$total_price_tm_3_8 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_8 = 0;
			foreach ($produk_tm_4_8 as $x){
				$total_price_tm_4_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_8 = 0;
			foreach ($produk_wl_8 as $x){
				$total_price_wl_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_8 = 0;
			foreach ($produk_wl_2_8 as $x){
				$total_price_wl_2_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_8 = 0;
			foreach ($produk_wl_3_8 as $x){
				$total_price_wl_3_8 += $x['qty'] * $x['price'];
			}

			$produk_tr_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_8 = 0;
			foreach ($produk_tr_8 as $x){
				$total_price_tr_8 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_8 = 0;
			foreach ($produk_tr_2_8 as $x){
				$total_price_tr_2_8 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_8 = 0;
			foreach ($produk_tr_3_8 as $x){
				$total_price_tr_3_8 += $x['qty'] * $x['price'];
			}

			$produk_exc_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_8 = 0;
			foreach ($produk_exc_8 as $x){
				$total_price_exc_8 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_8 = 0;
			foreach ($produk_dmp_4m3_8 as $x){
				$total_price_dmp_4m3_8 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_8 = 0;
			foreach ($produk_dmp_10m3_8 as $x){
				$total_price_dmp_10m3_8 += $x['qty'] * $x['price'];
			}

			$produk_sc_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_8 = 0;
			foreach ($produk_sc_8 as $x){
				$total_price_sc_8 += $x['qty'] * $x['price'];
			}

			$produk_gns_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_8 = 0;
			foreach ($produk_gns_8 as $x){
				$total_price_gns_8 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_8 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_8'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_8 = 0;
			foreach ($produk_wl_sc_8 as $x){
				$total_price_wl_sc_8 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_8 = $rak_alat_8['vol_bbm_solar'];

			$total_8_biaya_bahan = $nilai_semen_8 + $nilai_pasir_8 + $nilai_batu1020_8 + $nilai_batu2030_8;
			$total_8_biaya_alat = ($total_price_bp_8 + $total_price_bp_2_8 + $total_price_bp_3_8) + ($total_price_tm_8 + $total_price_tm_2_8 + $total_price_tm_3_8 + $total_price_tm_4_8) + ($total_price_wl_8 + $total_price_wl_2_8 + $total_price_wl_3_8) + ($total_price_tr_8 + $total_price_tr_2_8 + $total_price_tr_3_8) + ($total_volume_solar_8 * $rak_alat_8['harga_solar']) + $rak_alat_8['insentif'] + $total_price_exc_8 + $total_price_dmp_4m3_8 + $total_price_dmp_10m3_8 + $total_price_sc_8 + $total_price_gns_8 + $total_price_wl_sc_8;
			$total_8_overhead = $rencana_kerja_8['overhead'];
			$total_8_diskonto =  ($total_8_nilai * 3) /100;
			$total_biaya_8_biaya = $total_8_biaya_bahan + $total_8_biaya_alat + $total_8_overhead + $total_8_diskonto;
			?>

			<?php
			//BULAN 9
			$date_9_awal = date('Y-m-d', strtotime('+1 days', strtotime($date_8_akhir)));
			$date_9_akhir = date('Y-m-d', strtotime('-1 days +1 months', strtotime($date_9_awal)));

			$rencana_kerja_9 = $this->db->select('r.*')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->row_array();

			$volume_9_produk_a = $rencana_kerja_9['vol_produk_a'];
			$volume_9_produk_b = $rencana_kerja_9['vol_produk_b'];
			$volume_9_produk_c = $rencana_kerja_9['vol_produk_c'];
			$volume_9_produk_d = $rencana_kerja_9['vol_produk_d'];

			$total_9_volume = $volume_9_produk_a + $volume_9_produk_b + $volume_9_produk_c + $volume_9_produk_d;

			$nilai_jual_125_9 = $volume_9_produk_a * $rencana_kerja_9['price_a'];
			$nilai_jual_225_9 = $volume_9_produk_b * $rencana_kerja_9['price_b'];
			$nilai_jual_250_9 = $volume_9_produk_c * $rencana_kerja_9['price_c'];
			$nilai_jual_250_18_9 = $volume_9_produk_d * $rencana_kerja_9['price_d'];
			$nilai_jual_all_9 = $nilai_jual_125_9 + $nilai_jual_225_9 + $nilai_jual_250_9 + $nilai_jual_250_18_9;

			$total_9_nilai = $nilai_jual_all_9;

			//VOLUME
			$volume_rencana_kerja_9_produk_a = $rencana_kerja_9['vol_produk_a'];
			$volume_rencana_kerja_9_produk_b = $rencana_kerja_9['vol_produk_b'];
			$volume_rencana_kerja_9_produk_c = $rencana_kerja_9['vol_produk_c'];
			$volume_rencana_kerja_9_produk_d = $rencana_kerja_9['vol_produk_d'];

			//BIAYA

			//BIAYA BAHAN
			$komposisi_125_9 = $this->db->select('(r.vol_produk_a * pk.presentase_a) as komposisi_semen_125, (vol_produk_a * pk.presentase_b) as komposisi_pasir_125, (vol_produk_a * pk.presentase_c) as komposisi_batu1020_125, (vol_produk_a * pk.presentase_d) as komposisi_batu2030_125')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_125 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_125_9 = 0;
			$total_volume_pasir_125_9 = 0;
			$total_volume_batu1020_125_9 = 0;
			$total_volume_batu2030_125_9 = 0;

			foreach ($komposisi_125_9 as $x){
				$total_volume_semen_125_9 = $x['komposisi_semen_125'];
				$total_volume_pasir_125_9 = $x['komposisi_pasir_125'];
				$total_volume_batu1020_125_9 = $x['komposisi_batu1020_125'];
				$total_volume_batu2030_125_9 = $x['komposisi_batu2030_125'];
			}

			$komposisi_225_9 = $this->db->select('(r.vol_produk_b * pk.presentase_a) as komposisi_semen_225, (vol_produk_b * pk.presentase_b) as komposisi_pasir_225, (vol_produk_b * pk.presentase_c) as komposisi_batu1020_225, (vol_produk_b * pk.presentase_d) as komposisi_batu2030_225')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_225 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_225_9 = 0;
			$total_volume_pasir_225_9 = 0;
			$total_volume_batu1020_225_9 = 0;
			$total_volume_batu2030_225_9 = 0;

			foreach ($komposisi_225_9 as $x){
				$total_volume_semen_225_9 = $x['komposisi_semen_225'];
				$total_volume_pasir_225_9 = $x['komposisi_pasir_225'];
				$total_volume_batu1020_225_9 = $x['komposisi_batu1020_225'];
				$total_volume_batu2030_225_9 = $x['komposisi_batu2030_225'];
			}

			$komposisi_250_9 = $this->db->select('(r.vol_produk_c * pk.presentase_a) as komposisi_semen_250, (vol_produk_c * pk.presentase_b) as komposisi_pasir_250, (vol_produk_c * pk.presentase_c) as komposisi_batu1020_250, (vol_produk_c * pk.presentase_d) as komposisi_batu2030_250')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_250_9 = 0;
			$total_volume_pasir_250_9 = 0;
			$total_volume_batu1020_250_9 = 0;
			$total_volume_batu2030_250_9 = 0;

			foreach ($komposisi_250_9 as $x){
				$total_volume_semen_250_9 = $x['komposisi_semen_250'];
				$total_volume_pasir_250_9 = $x['komposisi_pasir_250'];
				$total_volume_batu1020_250_9 = $x['komposisi_batu1020_250'];
				$total_volume_batu2030_250_9 = $x['komposisi_batu2030_250'];
			}

			$komposisi_250_2_9 = $this->db->select('(r.vol_produk_d * pk.presentase_a) as komposisi_semen_250_2, (vol_produk_d * pk.presentase_b) as komposisi_pasir_250_2, (vol_produk_d * pk.presentase_c) as komposisi_batu1020_250_2, (vol_produk_d * pk.presentase_d) as komposisi_batu2030_250_2')
			->from('rak r')
			->join('pmm_agregat pk', 'r.komposisi_250_2 = pk.id','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->result_array();

			$total_volume_semen_250_2_9 = 0;
			$total_volume_pasir_250_2_9 = 0;
			$total_volume_batu1020_250_2_9 = 0;
			$total_volume_batu2030_250_2_9 = 0;

			foreach ($komposisi_250_2_9 as $x){
				$total_volume_semen_250_2_9 = $x['komposisi_semen_250_2'];
				$total_volume_pasir_250_2_9 = $x['komposisi_pasir_250_2'];
				$total_volume_batu1020_250_2_9 = $x['komposisi_batu1020_250_2'];
				$total_volume_batu2030_250_2_9 = $x['komposisi_batu2030_250_2'];
			}

			$total_volume_semen_9 = $total_volume_semen_125_9 + $total_volume_semen_225_9 + $total_volume_semen_250_9 + $total_volume_semen_250_2_9;
			$total_volume_pasir_9 = $total_volume_pasir_125_9 + $total_volume_pasir_225_9 + $total_volume_pasir_250_9 + $total_volume_pasir_250_2_9;
			$total_volume_batu1020_9 = $total_volume_batu1020_125_9 + $total_volume_batu1020_225_9 + $total_volume_batu1020_250_9 + $total_volume_batu1020_250_2_9;
			$total_volume_batu2030_9 = $total_volume_batu2030_125_9 + $total_volume_batu2030_225_9 + $total_volume_batu2030_250_9 + $total_volume_batu2030_250_2_9;

			$nilai_semen_9 = $total_volume_semen_9 * $rencana_kerja_9['harga_semen'];
			$nilai_pasir_9 = $total_volume_pasir_9 * $rencana_kerja_9['harga_pasir'];
			$nilai_batu1020_9 = $total_volume_batu1020_9 * $rencana_kerja_9['harga_batu1020'];
			$nilai_batu2030_9 = $total_volume_batu2030_9 * $rencana_kerja_9['harga_batu2030'];

			//BIAYA ALAT
			$rak_alat_9 = $this->db->select('r.*, (r.vol_produk_a + r.vol_produk_b + r.vol_produk_c + r.vol_produk_d) as total_produksi')
			->from('rak r')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->get()->row_array();

			$rak_alat_bp_9 = $rak_alat_9['penawaran_id_bp'];
			$rak_alat_bp_2_9 = $rak_alat_9['penawaran_id_bp_2'];
			$rak_alat_bp_3_9 = $rak_alat_9['penawaran_id_bp_3'];

			$rak_alat_tm_9 = $rak_alat_9['penawaran_id_tm'];
			$rak_alat_tm_2_9 = $rak_alat_9['penawaran_id_tm_2'];
			$rak_alat_tm_3_9 = $rak_alat_9['penawaran_id_tm_3'];
			$rak_alat_tm_4_9 = $rak_alat_9['penawaran_id_tm_4'];

			$rak_alat_wl_9 = $rak_alat_9['penawaran_id_wl'];
			$rak_alat_wl_2_9 = $rak_alat_9['penawaran_id_wl_2'];
			$rak_alat_wl_3_9 = $rakrak_alat_9_alat['penawaran_id_wl_3'];

			$rak_alat_tr_9 = $rak_alat_9['penawaran_id_tr'];
			$rak_alat_tr_2_9 = $rak_alat_9['penawaran_id_tr_2'];
			$rak_alat_tr_3_9 = $rak_alat_9['penawaran_id_tr_3'];

			$rak_alat_exc_9 = $rak_alat_9['penawaran_id_exc'];
			$rak_alat_dmp_4m3_9 = $rak_alat_9['penawaran_id_dmp_4m3'];
			$rak_alat_dmp_10m3_9 = $rak_alat_9['penawaran_id_dmp_10m3'];
			$rak_alat_sc_9 = $rak_alat_9['penawaran_id_sc'];
			$rak_alat_gns_9 = $rak_alat_9['penawaran_id_gns'];
			$rak_alat_wl_sc_9 = $rak_alat_9['penawaran_id_wl_sc'];

			$produk_bp_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->where("ppp.id = '$rak_alat_bp_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_9 = 0;
			foreach ($produk_bp_9 as $x){
				$total_price_bp_9 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->where("ppp.id = '$rak_alat_bp_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_2_9 = 0;
			foreach ($produk_bp_2_9 as $x){
				$total_price_bp_2_9 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_bp_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama, sum(vol_produk_a + vol_produk_b + vol_produk_c + vol_produk_d) as total_vol_produksi')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->join('rak r', 'ppp.id = r.penawaran_id_bp','left')
			->where("r.tanggal_rencana_kerja between '$date_9_awal' and '$date_9_akhir'")
			->where("ppp.id = '$rak_alat_bp_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_bp_3_9 = 0;
			foreach ($produk_bp_3_9 as $x){
				$total_price_bp_3_9 += $x['total_vol_produksi'] * $x['price'];
			}

			$produk_tm_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_9 = 0;
			foreach ($produk_tm_9 as $x){
				$total_price_tm_9 += $x['qty'] * $x['price'];
			}

			$produk_tm_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_2_9 = 0;
			foreach ($produk_tm_2_9 as $x){
				$total_price_tm_2_9 += $x['qty'] * $x['price'];
			}

			$produk_tm_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_3_9 = 0;
			foreach ($produk_tm_3_9 as $x){
				$total_price_tm_3_9 += $x['qty'] * $x['price'];
			}

			$produk_tm_4_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_tm_4_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tm_4_9 = 0;
			foreach ($produk_tm_4_9 as $x){
				$total_price_tm_4_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_9 = 0;
			foreach ($produk_wl_9 as $x){
				$total_price_wl_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_2_9 = 0;
			foreach ($produk_wl_2_9 as $x){
				$total_price_wl_2_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->where("ppp.id = '$rak_alat_wl_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_3_9 = 0;
			foreach ($produk_wl_3_9 as $x){
				$total_price_wl_3_9 += $x['qty'] * $x['price'];
			}

			$produk_tr_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_9 = 0;
			foreach ($produk_tr_9 as $x){
				$total_price_tr_9 += $x['qty'] * $x['price'];
			}

			$produk_tr_2_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_2_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_2_9 = 0;
			foreach ($produk_tr_2_9 as $x){
				$total_price_tr_2_9 += $x['qty'] * $x['price'];
			}

			$produk_tr_3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_tr_3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_tr_3_9 = 0;
			foreach ($produk_tr_3_9 as $x){
				$total_price_tr_3_9 += $x['qty'] * $x['price'];
			}

			$produk_exc_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_exc_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_exc_9 = 0;
			foreach ($produk_exc_9 as $x){
				$total_price_exc_9 += $x['qty'] * $x['price'];
			}

			$produk_dmp_4m3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_4m3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_4m3_9 = 0;
			foreach ($produk_dmp_4m3_9 as $x){
				$total_price_dmp_4m3_9 += $x['qty'] * $x['price'];
			}

			$produk_dmp_10m3_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_dmp_10m3_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_dmp_10m3_9 = 0;
			foreach ($produk_dmp_10m3_9 as $x){
				$total_price_dmp_10m3_9 += $x['qty'] * $x['price'];
			}

			$produk_sc_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_sc_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_sc_9 = 0;
			foreach ($produk_sc_9 as $x){
				$total_price_sc_9 += $x['qty'] * $x['price'];
			}

			$produk_gns_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_gns_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_gns_9 = 0;
			foreach ($produk_gns_9 as $x){
				$total_price_gns_9 += $x['qty'] * $x['price'];
			}

			$produk_wl_sc_9 = $this->db->select('p.nama_produk, ppd.price, ppd.qty, pm.measure_name, ps.nama')
			->from('pmm_penawaran_pembelian ppp')
			->join('pmm_penawaran_pembelian_detail ppd', 'ppp.id = ppd.penawaran_pembelian_id','left')
			->join('produk p', 'ppd.material_id = p.id','left')
			->join('pmm_measures pm', 'ppd.measure = pm.id','left')
			->join('penerima ps', 'ppp.supplier_id = ps.id','left')
			->where("ppp.id = '$rak_alat_wl_sc_9'")
			->group_by('ppd.id')
			->order_by('p.nama_produk','asc')
			->get()->result_array();

			$total_price_wl_sc_9 = 0;
			foreach ($produk_wl_sc_9 as $x){
				$total_price_wl_sc_9 += $x['qty'] * $x['price'];
			}

			$total_volume_solar_9 = $rak_alat_9['vol_bbm_solar'];

			$total_9_biaya_bahan = $nilai_semen_9 + $nilai_pasir_9 + $nilai_batu1020_9 + $nilai_batu2030_9;
			$total_9_biaya_alat = ($total_price_bp_9 + $total_price_bp_2_9 + $total_price_bp_3_9) + ($total_price_tm_9 + $total_price_tm_2_9 + $total_price_tm_3_9 + $total_price_tm_4_9) + ($total_price_wl_9 + $total_price_wl_2_9 + $total_price_wl_3_9) + ($total_price_tr_9 + $total_price_tr_2_9 + $total_price_tr_3_9) + ($total_volume_solar_9 * $rak_alat_9['harga_solar']) + $rak_alat_9['insentif'] + $total_price_exc_9 + $total_price_dmp_4m3_9 + $total_price_dmp_10m3_9 + $total_price_sc_9 + $total_price_gns_9 + $total_price_wl_sc_9;
			$total_9_overhead = $rencana_kerja_9['overhead'];
			$total_9_diskonto =  ($total_9_nilai * 3) /100;
			$total_biaya_9_biaya = $total_9_biaya_bahan + $total_9_biaya_alat + $total_9_overhead + $total_9_diskonto;
			?>

			<?php
			//TOTAL
			$total_all_produk_a = $volume_akumulasi_produk_a + $volume_1_produk_a + $volume_2_produk_a + $volume_3_produk_a + $volume_4_produk_a + $volume_5_produk_a + $volume_6_produk_a + $volume_7_produk_a + $volume_8_produk_a + $volume_9_produk_a;
			$total_all_produk_b = $volume_akumulasi_produk_b + $volume_1_produk_b + $volume_2_produk_b + $volume_3_produk_b + $volume_4_produk_b + $volume_5_produk_b + $volume_6_produk_b + $volume_7_produk_b + $volume_8_produk_b + $volume_9_produk_b;
			$total_all_produk_c = $volume_akumulasi_produk_c + $volume_1_produk_c + $volume_2_produk_c + $volume_3_produk_c + $volume_4_produk_c + $volume_5_produk_c + $volume_6_produk_c + $volume_7_produk_c + $volume_8_produk_c + $volume_9_produk_c;
			$total_all_produk_d = $volume_akumulasi_produk_d + $volume_1_produk_d + $volume_2_produk_d + $volume_3_produk_d + $volume_4_produk_d + $volume_5_produk_d + $volume_6_produk_d + $volume_7_produk_d + $volume_8_produk_d + $volume_9_produk_d;

			$total_all_volume = $total_akumulasi_volume + $total_1_volume + $total_2_volume + $total_3_volume + $total_4_volume + $total_5_volume + $total_6_volume + $total_7_volume + $total_8_volume + $total_9_volume;
			$total_all_nilai = $total_akumulasi_nilai  + $total_1_nilai + $total_2_nilai + $total_3_nilai + $total_4_nilai + $total_5_nilai + $total_6_nilai + $total_7_nilai + $total_8_nilai + $total_9_nilai;

			$total_all_biaya_bahan = $total_bahan_akumulasi + $total_1_biaya_bahan + $total_2_biaya_bahan + $total_3_biaya_bahan + $total_4_biaya_bahan + $total_5_biaya_bahan + $total_6_biaya_bahan + $total_7_biaya_bahan + $total_8_biaya_bahan + $total_9_biaya_bahan;
			$total_all_biaya_alat = $total_alat_akumulasi + $total_1_biaya_alat + $total_2_biaya_alat + $total_3_biaya_alat + $total_4_biaya_alat + $total_5_biaya_alat + $total_6_biaya_alat + $total_7_biaya_alat + $total_8_biaya_alat + $total_9_biaya_alat;
			$total_all_overhead = $total_overhead_akumulasi + $total_1_overhead + $total_2_overhead + $total_3_overhead + $total_4_overhead + $total_5_overhead + $total_6_overhead + $total_7_overhead + $total_8_overhead + $total_9_overhead;
			$total_all_diskonto = $total_diskonto_akumulasi + $total_1_diskonto + $total_2_diskonto + $total_3_diskonto + $total_4_diskonto + $total_5_diskonto + $total_6_diskonto + $total_7_diskonto + $total_8_diskonto + $total_9_diskonto;
			
			$total_biaya_all_biaya = $total_all_biaya_bahan + $total_all_biaya_alat + $total_all_overhead + $total_all_diskonto;

			$total_laba_rap_2022 = $total_rap_nilai_2022 - $total_biaya_rap_2022_biaya;
			$total_laba_saat_ini = $total_akumulasi_nilai - $total_biaya_akumulasi;
			$total_laba_1 = $total_1_nilai - $total_biaya_1_biaya;
			$total_laba_2 = $total_2_nilai - $total_biaya_2_biaya;
			$total_laba_3 = $total_3_nilai - $total_biaya_3_biaya;
			$total_laba_4 = $total_4_nilai - $total_biaya_4_biaya;
			$total_laba_5 = $total_5_nilai - $total_biaya_5_biaya;
			$total_laba_6 = $total_6_nilai - $total_biaya_6_biaya;
			$total_laba_7 = $total_7_nilai - $total_biaya_7_biaya;
			$total_laba_8 = $total_8_nilai - $total_biaya_8_biaya;
			$total_laba_9 = $total_9_nilai - $total_biaya_9_biaya;
			$total_laba_all = $total_all_nilai - $total_biaya_all_biaya;
			?>
			
			<tr class="table-active4-rak">
				<th width="5%" class="text-center" rowspan="3" style="vertical-align:middle">NO.</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">URAIAN</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">SATUAN</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">ADEDENDUM RAP</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle;text-transform:uppercase;">REALISASI SD. <br /><?php echo $last_opname = date('F Y', strtotime('0 days', strtotime($last_opname)));?></th>
				<th class="text-center" colspan="7">PROGNOSA</th>
				<th class="text-center" rowspan="3" style="vertical-align:middle">TOTAL</th>
	        </tr>
			<tr class="table-active4-rak">
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_1_awal = date("F");?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_2_awal = date('F', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_3_awal = date('F', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_4_awal = date('F', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_5_awal = date('F', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_6_awal = date('F', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
				<th class="text-center" style="text-transform:uppercase;"><?php echo $date_7_awal = date('F', strtotime('+1 days', strtotime($date_6_akhir)));?> - <?php echo $date_9_awal = date('F', strtotime('+1 days', strtotime($date_8_akhir)));?></th>
	        </tr>
			<tr class="table-active4-rak">
				<th class="text-center"><?php echo $date_1_awal = date('Y');?></th>
				<th class="text-center"><?php echo $date_2_awal = date('Y', strtotime('+1 days', strtotime($date_1_akhir)));?></th>
				<th class="text-center"><?php echo $date_3_awal = date('Y', strtotime('+1 days', strtotime($date_2_akhir)));?></th>
				<th class="text-center"><?php echo $date_4_awal = date('Y', strtotime('+1 days', strtotime($date_3_akhir)));?></th>
				<th class="text-center"><?php echo $date_5_awal = date('Y', strtotime('+1 days', strtotime($date_4_akhir)));?></th>
				<th class="text-center"><?php echo $date_6_awal = date('Y', strtotime('+1 days', strtotime($date_5_akhir)));?></th>
				<th class="text-center"><?php echo $date_7_awal = date('Y', strtotime('+1 days', strtotime($date_6_akhir)));?></th>
	        </tr>
			<tr class="table-active2-rak">
				<th class="text-left" colspan="13">RENCANA PRODUKSI & PENDAPATAN USAHA</th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">1.</th>
				<th class="text-left">Beton K 125 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_a + $volume_8_produk_a + $volume_9_produk_a,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_a,2,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">2.</th>
				<th class="text-left">Beton K 225 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_b + $volume_8_produk_b + $volume_9_produk_b,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_b,2,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">3.</th>
				<th class="text-left">Beton K 250 (10±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_c + $volume_8_produk_c + $volume_9_produk_c,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_c,2,',','.');?></th>	
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">4.</th>
				<th class="text-left">Beton K 250 (18±2)</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($volume_rap_2022_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_akumulasi_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_1_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_2_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_3_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_4_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_5_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_6_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($volume_7_produk_d + $volume_8_produk_d + $volume_9_produk_d,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_produk_d,2,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">TOTAL VOLUME</th>
				<th class="text-center">M3</th>
				<th class="text-right"><?php echo number_format($total_rap_volume_2022,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_akumulasi_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_volume + $total_8_volume + $total_9_volume,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_volume,2,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">PENDAPATAN USAHA</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_rap_nilai_2022,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_akumulasi_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_nilai + $total_8_nilai + $total_9_nilai,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_nilai,0,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-left" colspan="13">BIAYA</th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">1.</th>
				<th class="text-left">Bahan</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_bahan_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_biaya_bahan + $total_8_biaya_bahan + $total_9_biaya_bahan,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_biaya_bahan,0,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">2.</th>
				<th class="text-left">Alat</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_alat_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_biaya_alat + $total_8_biaya_alat + $total_9_biaya_alat,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_biaya_alat,0,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">3.</th>
				<th class="text-left">Overhead</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_overhead_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_overhead + $total_8_overhead + $total_9_overhead,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_overhead,0,',','.');?></th>
			</tr>
			<tr class="table-active3-rak">
				<th class="text-center">4.</th>
				<th class="text-left">Diskonto</th>
				<th class="text-center">LS</th>
				<th class="text-right"><?php echo number_format($total_rap_2022_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_diskonto_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_1_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_2_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_3_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_4_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_5_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_6_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_7_diskonto + $total_8_diskonto + $total_9_diskonto,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_all_diskonto,0,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">JUMLAH</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_biaya_rap_2022_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_akumulasi,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_1_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_2_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_3_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_4_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_5_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_6_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_7_biaya + $total_biaya_8_biaya + $total_biaya_9_biaya,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_biaya_all_biaya,0,',','.');?></th>
			</tr>
			<tr class="table-active2-rak">
				<th class="text-right" colspan="2">LABA</th>
				<th class="text-center"></th>
				<th class="text-right"><?php echo number_format($total_laba_rap_2022,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_saat_ini,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_1,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_2,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_3,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_4,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_5,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_6,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_7 + $total_laba_8 + $total_laba_9,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($total_laba_all,0,',','.');?></th>
			</tr>
			
	    </table>
		<?php
	}

	public function new_rap_dashboard($arr_date)
	{
		$data = array();
		
		$arr_date = $this->input->post('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		
		$last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
		$last_production_2 = $this->db->select('date')->order_by('date','desc')->limit(1,1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','material_id'=>'4'))->row_array();

		$date1 = date('Y-m-d', strtotime('+1 days', strtotime($last_production_2['date'])));
		$date2 = date('Y-m-d', strtotime($last_production['date']));
		$date1_filter = date('d F Y', strtotime($date1));
		$date2_filter = date('d F Y', strtotime($date2));

		if(count($arr_filter_date) == 2){
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d F Y',strtotime($arr_filter_date[0])).' - '.date('d F Y',strtotime($arr_filter_date[1]));
			$date1_filter = date('d F Y',strtotime($arr_filter_date[0]));
			$date2_filter = date('d F Y',strtotime($arr_filter_date[1]));
		}
		
		?>
		
		<table class="table table-bordered" width="100%">


		<?php

		$row = $this->db->select('*')
		->from('rap')
		->order_by('id','desc')->limit(1)
		->get()->row_array();

		$penyusutan_tangki = $this->db->select('r.*, p.nama_produk')
		->from('penyusutan r')
		->join('produk p','r.produk = p.id','left')
		->where("r.status = 'PUBLISH'")
		->where("r.produk = '23'")
		->order_by('p.nama_produk','asc')
		->group_by("p.nama_produk")->limit(1)
		->get()->row_array();
		$penyusutan_tangki = (($penyusutan_tangki['nilai_penyusutan'] / 48) / 25) / 7;

		$penyusutan_sc = $this->db->select('r.*, p.nama_produk')
		->from('penyusutan r')
		->join('produk p','r.produk = p.id','left')
		->where("r.status = 'PUBLISH'")
		->where("r.produk = '16'")
		->order_by('p.nama_produk','asc')
		->group_by("p.nama_produk")->limit(1)
		->get()->row_array();
		$penyusutan_sc = (($penyusutan_sc['nilai_penyusutan'] / 48) / 25) / 7;

		$penyusutan_gns = $this->db->select('r.*, p.nama_produk')
		->from('penyusutan r')
		->join('produk p','r.produk = p.id','left')
		->where("r.status = 'PUBLISH'")
		->where("r.produk = '19'")
		->order_by('p.nama_produk','asc')
		->group_by("p.nama_produk")->limit(1)
		->get()->row_array();
		$penyusutan_gns = (($penyusutan_gns['nilai_penyusutan'] / 48) / 25) / 7;

		$penyusutan_wl = $this->db->select('r.*, p.nama_produk')
		->from('penyusutan r')
		->join('produk p','r.produk = p.id','left')
		->where("r.status = 'PUBLISH'")
		->where("r.produk = '17'")
		->order_by('p.nama_produk','asc')
		->group_by("p.nama_produk")->limit(1)
		->get()->row_array();
		$penyusutan_wl = (($penyusutan_wl['nilai_penyusutan'] / 48) / 25) / 7;

		$penyusutan_timbangan = $this->db->select('r.*, p.nama_produk')
		->from('penyusutan r')
		->join('produk p','r.produk = p.id','left')
		->where("r.status = 'PUBLISH'")
		->where("r.produk = '39'")
		->order_by('p.nama_produk','asc')
		->group_by("p.nama_produk")->limit(1)
		->get()->row_array();
		$penyusutan_timbangan = (($penyusutan_timbangan['nilai_penyusutan'] / 48) / 25) / 7;

		//M3
		$berat_isi_boulder = 1/$row['berat_isi_boulder'];
		$harsat_boulder = $row['price_boulder'] / $berat_isi_boulder;
		$nilai_boulder = $harsat_boulder * $row['vol_boulder'];
		//Ton
		$vol_boulder = $row['vol_boulder'];
		$nilai_boulder_ton = $vol_boulder * $row['price_boulder'];
		
		//M3
		$sc_a = $row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc'];
		$sc_b = $sc_a / $row['berat_isi_batu_pecah'];
		$vol_sc = 1 / $sc_b;
		$nilai_sc = $vol_sc * $penyusutan_sc;
		//Ton
		$vol_sc_ton = 1 / $sc_a;
		$nilai_sc_ton = $vol_sc_ton * $penyusutan_sc;
		
		//M3
		$vol_tangki = $vol_sc;
		$nilai_tangki = $vol_tangki * $penyusutan_tangki;
		//Ton
		$vol_tangki_ton = $vol_sc_ton;
		$nilai_tangki_ton = $vol_tangki_ton * $penyusutan_tangki;
		
		//M3
		$vol_gns = $vol_sc;
		$nilai_gns = $vol_gns * $penyusutan_gns;
		//Ton
		$vol_gns_ton = $vol_sc_ton;
		$nilai_gns_ton = $vol_gns_ton * $penyusutan_gns;

		//M3
		$wl_a = $row['kapasitas_alat_wl'] * $row['efisiensi_alat_wl'];
		$wl_b = (60 / $row['waktu_siklus']) * $wl_a;
		$vol_wl = 1 / $wl_b;
		$nilai_wl = $vol_wl * $penyusutan_wl;
		//Ton
		$vol_wl_ton_rumus = (($wl_a / $row['waktu_siklus']) * 60) * $row['berat_isi_batu_pecah'];
		$vol_wl_ton = 1 / $vol_wl_ton_rumus;
		$nilai_wl_ton = $vol_wl_ton * $penyusutan_wl;

		//M3
		$vol_timbangan =  $vol_sc;
		$nilai_timbangan = $vol_timbangan * $penyusutan_timbangan;
		//Ton
		$vol_timbangan_ton = $vol_sc_ton;
		$nilai_timbangan_ton = $vol_timbangan_ton * $penyusutan_timbangan;

		//Ton
		$vol_bbm_solar_ton = $row['vol_bbm_solar'];
		$nilai_bbm_solar_ton = $vol_bbm_solar_ton * $row['price_bbm_solar'];

		//M3
		$vol_bbm_solar =  $vol_bbm_solar_ton * $row['berat_isi_boulder'];
		$nilai_bbm_solar = $vol_bbm_solar * $row['price_bbm_solar'];

		$rumus_overhead = ($row['overhead'] / 25) / 8;
		$rumus_overhead_1 = ($row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc']) / $row['berat_isi_batu_pecah'] ;
		//$overhead = $rumus_overhead / $rumus_overhead_1;

		$rumus_overhead_ton = $row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc'];
		$overhead_ton = $rumus_overhead / $rumus_overhead_ton;
		$overhead = $overhead_ton;

		$total = $nilai_boulder + $nilai_tangki + $nilai_sc + $nilai_gns + $nilai_wl + $nilai_timbangan + $overhead;
		$total_ton = $nilai_boulder_ton + $nilai_tangki_ton + $nilai_sc_ton + $nilai_gns_ton + $nilai_wl_ton + $nilai_timbangan_ton + $overhead_ton;
		?>

		<tr class="table-active">
			<th class="text-center" colspan="4" style='background-color:#5B5B5B; color:white; text-transform:uppercase;'>RAP</th>
		</tr>
		<tr>
			<th width="5%" class="text-center" style='background-color:rgb(188,188,188); color:black'>NO.</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>URAIAN</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>HARGA SATUAN PEKERJAAN (M3)</th>
			<th class="text-center" style='background-color:rgb(188,188,188); color:black'>HARGA SATUAN PEKERJAAN (TON)</th>
		</tr>
		<tr>
			<th class="text-center">1.</th>
			<th class="text-left">HARGA SATUAN PEKERJAAN</th>
			<th class="text-right"><?php echo number_format($total,0,',','.');?></th>
			<th class="text-right"><?php echo number_format($total_ton,0,',','.');?></th>
		</tr>
		<tr>
			<th class="text-center">2.</th>
			<th class="text-left">HARGA SATUAN PEKERJAAN + LABA (<?php echo number_format($row['laba'],0,',','.');?>%)</th>
			<th class="text-right"><?php echo number_format(($total + ($total * $row['laba']) / 100),0,',','.');?></th>
			<th class="text-right"><?php echo number_format(($total_ton + ($total_ton * $row['laba']) / 100),0,',','.');?></th>
		</tr>
	    </table>
		
		<?php
	}
	
}