<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {

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
			$get_data = $this->db->get_where('pmm_purchase_order',array('id'=>$id,'status !='=>'DELETED'))->row_array();
			if(!empty($get_data)){
				$data['data'] = $get_data;
				$data['details'] = $this->pmm_model->GetPODetail($id);
				$data['details_pnw'] = $this->pmm_model->GetPODetailPNW($id);
				$data['details_req'] = $this->pmm_model->GetPODetailREQ($id);
				$sp = $this->db->get_where('penerima',array('id'=>$get_data['supplier_id']))->row_array();
				$data['address_supplier'] = $sp['alamat'];
				$data['npwp_supplier'] = $sp['npwp'];
				$data['supplier_name'] = $sp['nama'];

				$this->load->view('pmm/purchase_order_add',$data);
			}else {
				redirect('admin/request_materials');
			}
			
		}else {
			redirect('admin');
		}
	}

	public function table()
	{	
		$data = array();
		$status = $this->input->post('status');
		$schedule_id = $this->input->post('schedule_id');
		$supplier_id = $this->input->post('supplier_id');
		$w_date = $this->input->post('filter_date');

		$this->db->select('');
		$this->db->where('status !=','DELETED');
		if(!empty($supplier_id)){
			$this->db->where('supplier_id',$supplier_id);
		}
		if(!empty($status)){
			$this->db->where('status',$status);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('date_po  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('date_po <=',date('Y-m-d',strtotime($end_date)));	
		}
		// $this->db->group_by('supplier_id');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_purchase_order');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$no_po = "'".$row['no_po']."'";
				$row['no_po'] = '<a href="'.site_url('pmm/purchase_order/manage/'.$row['id']).'" target="_blank" >'.$row['no_po'].'</a>';
				// $row['created_on'] = date('d F Y',strtotime($row['created_on']));
				$row['document_po'] = '<a href="'.base_url().'uploads/purchase_order/'.$row['document_po'].'" target="_blank">'.$row['document_po'].'</a>';
				$row['date_po'] = date('d F Y',strtotime($row['date_po']));
				$row['supplier'] = $this->crud_global->GetField('pmm_supplier',array('id'=>$row['supplier_id']),'name');

				$total_volume = $this->db->select('SUM(volume) as total,measure')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$row['id']))->row_array();
				$row['volume'] = number_format($total_volume['total'],2,',','.');
				$row['measure'] = $total_volume['measure'];
				$total = 
				$row['total_val'] = $row['total'];
				$row['total'] = number_format($row['total'],2,',','.');
				

				$receipt = $this->db->select('SUM(volume) as total')->get_where('pmm_receipt_material',array('purchase_order_id'=>$row['id']))->row_array();
				$total_receipt = $this->pmm_model->GetTotalReceipt($row['id']);
				$row['receipt'] = number_format($receipt['total'],2,',','.');
				$row['total_receipt'] = number_format($total_receipt,2,',','.');
				$row['total_receipt_val'] = $total_receipt;

				$delete = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				if($row['status'] == 'DRAFT'){
					
					$edit = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
				}else {
					// $delete = false;
					$edit = false;
				}

				$upload_document = false;
				if($row['status'] == 'PUBLISH'){
					$edit = '<a href="javascript:void(0);" onclick="UploadDoc('.$row['id'].')" class="btn btn-primary" title="Upload Document PO" ><i class="fa fa-upload"></i> </a>';
				}

				$row['status'] = $this->pmm_model->GetStatus($row['status']);

				$row['actions'] = '<a href="'.site_url('pmm/purchase_order/manage/'.$row['id']).'" class="btn btn-info"><i class="fa fa-gears"></i> </a> '.$edit.' '.$delete;
				$data[] = $row;
			}
		}
		echo json_encode(array('data'=>$data));
	}

	public function table_custom()
	{	
		$data = array();
		$status = $this->input->post('status');
		$schedule_id = $this->input->post('schedule_id');
		$supplier_id = $this->input->post('supplier_id');
		$w_date = $this->input->post('filter_date');

		$this->db->select('supplier_id');
		$this->db->where('status !=','DELETED');
		if(!empty($supplier_id)){
			$this->db->where('supplier_id',$supplier_id);
		}
		$this->db->group_by('supplier_id');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_purchase_order');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$po_list = $this->pmm_model->TableCustomMaterial($row['supplier_id']);
				if(!empty($po_list)){
					$row['no'] = $key+1;
					$row['supplier'] = $this->crud_global->GetField('pmm_supplier',array('id'=>$row['supplier_id']),'name');
					$row['po_list'] = $po_list;
					$row['count_po'] = count($this->pmm_model->TableCustomMaterial($row['supplier_id']));
					$data[] = $row;	
				}
				
			}

		}
		echo json_encode(array('data'=>$data));
	}


	function process()
	{
		// if($_POST){
			$id = $this->uri->segment(4);
			$type = $this->uri->segment(5);
			$arr = array();
			if($type == 1){
				$arr = array('status'=>'APPROVED','approved_by'=>$this->session->userdata('admin_id'),'approved_on'=>date('Y-m-d H:i:s'));
				
			}else if($type == 2){
				$arr = array('status'=>'REJECTED');
				$this->session->set_flashdata('notif_success','Berhasil menolak Permintaan !!');
			}else {
				$arr = array('status'=>'WAITING');
			}
			$arr['updated_by'] = $this->session->userdata('admin_id');

			if($this->db->update('pmm_purchase_order',$arr,array('id'=>$id))){
				redirect('admin/pembelian');
			}
		// }
	}


	public function approve_po()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = array(
				'date_po' => date('Y-m-d',strtotime($this->input->post('date_po'))),
				'subject' => $this->input->post('subject'),
				'date_pkp' => date('Y-m-d',strtotime($this->input->post('date_pkp'))),
				'approved_by' => $this->session->userdata('admin_id'),
				'approved_on' => date('Y-m-d H:i:s'),
				'status' => 'PUBLISH'
			);
			if($this->db->update('pmm_purchase_order',$data,array('id'=>$id))){
				$output['output'] = true;
				$this->session->set_flashdata('notif_success','Berhasil Menyetujui Pesanan Pembelian !!');
				$output['url'] = site_url('admin/pembelian');
			}
		}
		echo json_encode($output);
	}

	public function get_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetPrintFooter(true);
		$pdf->AddPage('P');

        $id = $this->uri->segment(4);
		$row = $this->db->get_where('pmm_purchase_order',array('id'=>$id))->row_array();
		$data['details'] = $this->pmm_model->GetPODetail($id);
		$data['details_no_pnw'] = $this->pmm_model->GetPODetailNoPNW($id);
		$sp = $this->db->get_where('penerima',array('id'=>$row['supplier_id']))->row_array();
		$row['address_supplier'] = $sp['alamat'];
		$row['npwp_supplier'] = $sp['npwp'];
		$row['supplier_name'] = $sp['nama'];
		$row['pic'] = $sp['nama_kontak'];
		$row['position'] = $sp['posisi'];
		$row['date_pkp_supp'] = '-';
		$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
		$data['row'] = $row;
		$data['id'] = $id;
        $html = $this->load->view('pembelian/cetak_pesanan_pembelian',$data,TRUE);

        
        $pdf->SetTitle($row['no_po']);
        $pdf->nsi_html($html);
        $pdf->Output($row['no_po'].'.pdf', 'I');
	
	}

	public function get_pdf_draft()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('P');

        $id = $this->uri->segment(4);
		$row = $this->db->get_where('pmm_purchase_order',array('id'=>$id))->row_array();
		$data['details'] = $this->pmm_model->GetPODetail($id);
		$data['details_no_pnw'] = $this->pmm_model->GetPODetailNoPNW($id);
		$sp = $this->db->get_where('penerima',array('id'=>$row['supplier_id']))->row_array();
		$row['address_supplier'] = $sp['alamat'];
		$row['npwp_supplier'] = $sp['npwp'];
		$row['supplier_name'] = $sp['nama'];
		$row['pic'] = $sp['nama_kontak'];
		$row['position'] = $sp['posisi'];
		$row['date_pkp_supp'] = '-';
		$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
		$data['row'] = $row;
		$data['id'] = $id;
        $html = $this->load->view('pembelian/cetak_pesanan_pembelian_draft',$data,TRUE);

        
        $pdf->SetTitle($row['no_po']);
        $pdf->nsi_html($html);
        $pdf->Output($row['no_po'].'.pdf', 'I');
	
	}

	public function receipt_material_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

        $id = $this->uri->segment(4);
		$row = $this->db->get_where('pmm_purchase_order',array('id'=>$id))->row_array();

		$data['details'] = $this->pmm_model->GetReceiptByPO($id);
		$start_date = $this->db->select('date_receipt')->order_by('date_receipt','asc')->limit(1)->get_where('pmm_receipt_material',array('purchase_order_id'=>$id))->row_array();
		$end_date = $this->db->select('date_receipt')->order_by('date_receipt','desc')->limit(1)->get_where('pmm_receipt_material',array('purchase_order_id'=>$id))->row_array();
		$data['periode'] = date('d F Y',strtotime($start_date['date_receipt'])).' - '.date('d F Y',strtotime($end_date['date_receipt']));
		$sp = $this->db->get_where('penerima',array('id'=>$row['supplier_id']))->row_array();
		$row['address_supplier'] = $sp['alamat'];
		$row['npwp_supplier'] = $sp['npwp'];
		$row['supplier_name'] = $sp['nama'];
		$row['pic'] = $sp['nama_kontak_logistik'];
		$row['position'] = $sp['posisi'];
		$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
		$data['row'] = $row;
		$data['id'] = $id;
        $html = $this->load->view('pmm/receipt_material_pdf',$data,TRUE);

        
        $pdf->SetTitle($row['no_po'].'-Penerimaan');
        $pdf->nsi_html($html);
        $pdf->Output($row['no_po'].'-Penerimaan.pdf', 'I');
	
	}

	public function production_material_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

        $id = $this->uri->segment(4);
		$row = $this->db->get_where('pmm_sales_po',array('id'=>$id))->row_array();

		$data['details'] = $this->pmm_model->GetReceiptBySalesOrder($id);
		$start_date = $this->db->select('date_production')->order_by('date_production','asc')->limit(1)->get_where('pmm_productions',array('salesPo_id'=>$id))->row_array();
		$end_date = $this->db->select('date_production')->order_by('date_production','desc')->limit(1)->get_where('pmm_productions',array('salesPo_id'=>$id))->row_array();
		$data['periode'] = date('d F Y',strtotime($start_date['date_production'])).' - '.date('d F Y',strtotime($end_date['date_production']));
		$sp = $this->db->get_where('penerima',array('id'=>$row['client_id']))->row_array();
		$row['address_supplier'] = $sp['alamat'];
		$row['npwp_supplier'] = $sp['npwp'];
		$row['supplier_name'] = $sp['nama'];
		$row['pic'] = $sp['nama_kontak_logistik'];
		$row['position'] = $sp['posisi'];
		$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
		$data['row'] = $row;
		$data['id'] = $id;
        $html = $this->load->view('pmm/production_material_pdf',$data,TRUE);

        
        $pdf->SetTitle($row['contract_number'].'-Pengiriman');
        $pdf->nsi_html($html);
        $pdf->Output($row['contract_number'].'-Pengiriman.pdf', 'I');
	
	}


	public function form_document()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){

			$file = '';
			$error_file = false;

			if (!file_exists('./uploads/purchase_order/')) {
			    mkdir('./uploads/purchase_order/', 0777, true);
			}
			// Upload email
			$config['upload_path']          = './uploads/purchase_order/';
	        $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG|pdf';

	        $this->load->library('upload', $config);

			if($_FILES["file"]["error"] == 0) {
				if (!$this->upload->do_upload('file'))
				{
						$error = $this->upload->display_errors();
						$file = $error;
						$error_file = true;
				}else{
						$data = $this->upload->data();
						$file = $data['file_name'];
				}
			}

			if($error_file){
				$output['output'] = false;
				$output['err'] = $file;
				echo json_encode($output);
				exit();
			}

			$arr_data['document_po'] = $file;

			if($this->db->update('pmm_purchase_order',$arr_data,array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function edit_no_po()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		if(!empty($id)){
			$no_po = $this->input->post('no_po');
			$arr_data = array(
				'no_po' => $no_po,
				'status' => $status
 			);
				
			// $check_po = $this->db->get_where('pmm_purchase_order',array('no_po'=>$no_po))->num_rows();
			// if($check_po > 0){
				// $output['err'] = 'No Po has been added';
			// }else {
				if($this->db->update('pmm_purchase_order',$arr_data,array('id'=>$id))){
					$output['output'] = true;
				}	
			// }
			
		}
		echo json_encode($output);	
	}


	public function table_dashboard()
	{
		$data = array();
		$arr_date = explode(' - ', $this->input->post('date'));
		$supplier = $this->input->post('supplier');
		$status = $this->input->post('status');

		$this->db->select('SUM(total) as total,ps.name as supplier');
		$this->db->join('pmm_supplier ps','po.supplier_id = ps.id','left');
		$this->db->where('po.status !=','DELETED');
		if(!empty($arr_date)){
			$this->db->where('po.date_po >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('po.date_po <=',date('Y-m-d',strtotime($arr_date[1])));
		}
		if(!empty($supplier)){
			$this->db->where('po.supplier_id',$supplier);
		}
		if(!empty($status)){
			$this->db->where('po.status',$status);
		}
		$this->db->order_by('po.created_on','desc');
		$this->db->group_by('po.supplier_id');
		$query = $this->db->get('pmm_purchase_order po');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['total'] = number_format($row['total'],2,',','.');
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data,'a'=>$arr_date));
	}

	public function delete($id)
    {
    	$this->db->trans_start(); # Starting Transaction

		$file = $this->db->select('po.document_po')
		->from('pmm_purchase_order po')
		->where("po.id = $id")
		->get()->row_array();

		$path = './uploads/purchase_order/'.$file['document_po'];
		chmod($path, 0777);
		unlink($path);

		$this->db->delete('pmm_purchase_order_detail', array('purchase_order_id'=>$id));

		$request = $this->db->get_where('pmm_purchase_order', array('id' => $id))->row_array();

		$this->db->delete('pmm_request_material_details',array('request_material_id'=>$request['request_material_id']));

		$this->db->delete('pmm_request_materials',array('id'=>$request['request_material_id']));

		$this->db->delete('pmm_purchase_order', array('id'=>$id));


		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error','Gagal Hapus Pesanan Pembelian');
            redirect('admin/pembelian');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil Hapus Pesanan Pembelian');
            redirect('admin/pembelian');
        }
    }

	public function open_pesanan_pembelian($id)
    {
        $this->db->set("status", "PUBLISH");
        $this->db->set("updated_by", $this->session->userdata('admin_id'));
        $this->db->set("updated_on", date('Y-m-d H:i:s'));
        $this->db->where("id", $id);
        $this->db->update("pmm_purchase_order");
        $this->session->set_flashdata('notif_success', 'Pesanan Pembelian Publish');
        redirect("admin/pembelian");
    }
	

}