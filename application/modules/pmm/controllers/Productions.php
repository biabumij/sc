<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productions extends Secure_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model','admin/Templates','pmm_finance'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
		$this->m_admin->check_login();
	}	

	public function add()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){
			$po_id =  $this->input->get('po_id');
			$data['po_id'] = $po_id;
			$client_id = $this->input->get('client_id');
			$data['client_id'] = $client_id;
			$data['clients'] = $this->db->select('id,nama')->order_by('nama','asc')->get_where('penerima',array('pelanggan'=>1))->result_array();
			$data['komposisi'] = $this->db->select('id, no_komposisi')->get_where('pmm_agregat',array('status'=>'PUBLISH'))->result_array();
			$get_data = $this->db->get_where('pmm_sales_po',array('id'=>$po_id,'status'=>'OPEN'))->row_array();
			$data['contract_number'] = $this->db->get_where('pmm_sales_po',array('client_id'=>$get_data['client_id'],'status'=>'OPEN'))->result_array();
			$data['data'] = $get_data;
			$this->load->view('pmm/productions_add',$data);
			
		}else {
			redirect('admin');
		}
	}
	
	public function add_retur()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$po_id =  $this->input->get('po_id');
			$data['po_id'] = $po_id;
			$client_id = $this->input->get('client_id');
			$data['client_id'] = $client_id;
			$data['clients'] = $this->db->select('id,nama')->order_by('nama','asc')->get_where('penerima',array('pelanggan'=>1))->result_array();
			$data['komposisi'] = $this->db->select('id, no_komposisi')->get_where('pmm_agregat',array('status'=>'PUBLISH'))->result_array();
			$get_data = $this->db->get_where('pmm_sales_po',array('id'=>$po_id,'status'=>'OPEN'))->row_array();
			$data['contract_number'] = $this->db->get_where('pmm_sales_po',array('client_id'=>$get_data['client_id'],'status'=>'OPEN'))->result_array();
			$data['data'] = $get_data;
			$this->load->view('pmm/productions_add_retur',$data);
			
		}else {
			redirect('admin');
		}
	}

	public function table()
	{	
		$data = array();
		$client_id = $this->input->post('client_id');
		$product_id = $this->input->post('product_id');
		$sales_po_id = $this->input->post('salesPo_id');
		$w_date = $this->input->post('filter_date');

		$this->db->select('');
		$this->db->where('status !=','DELETED');
		if (!empty($client_id)) {
			$this->db->where('client_id', $client_id);
		}
		if(!empty($product_id)){
			$this->db->where('product_id',$product_id);
		}
		if(!empty($sales_po_id)){
			$this->db->where('salesPo_id',$sales_po_id);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('date_production  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('date_production <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->order_by('date_production','desc');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_productions');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['status'] = $this->pmm_model->GetStatus($row['status']);
				$row['salesPo_id'] = $this->crud_global->GetField('pmm_sales_po',array('id'=>$row['salesPo_id']),'contract_number');
				$row['product_id'] = $this->crud_global->GetField('produk',array('id'=>$row['product_id']),'nama_produk');
				$row['client_id'] = $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'nama');
				$row['date_production'] =  date('d F Y',strtotime($row['date_production']));
				$row['volume'] = number_format($row['volume'],2,',','.');
				$row['measure'] = $row['measure'];
				$row['harga_satuan'] = number_format($row['harga_satuan'],0,',','.');
				$row['price'] = number_format($row['price'],0,',','.');
				$row['surat_jalan'] = '<a href="'.base_url().'uploads/surat_jalan_penjualan/'.$row['surat_jalan'].'" target="_blank">'.$row['surat_jalan'].'</a>';
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				//$row['actions'] = '<a href="javascript:void(0);" onclick="EditData('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a><a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}
	
	public function table_retur()
	{	
		$data = array();
		$client_id = $this->input->post('client_id');
		$product_id = $this->input->post('product_id');
		$sales_po_id = $this->input->post('salesPo_id');
		$w_date = $this->input->post('filter_date');

		$this->db->select('');
		$this->db->where('status !=','DELETED');
		if (!empty($client_id)) {
			$this->db->where('client_id', $client_id);
		}
		if(!empty($product_id)){
			$this->db->where('product_id',$product_id);
		}
		if(!empty($sales_po_id)){
			$this->db->where('salesPo_id',$sales_po_id);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('date_production  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('date_production <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->order_by('date_production','desc');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_productions_retur');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['status'] = $this->pmm_model->GetStatus($row['status']);
				$row['salesPo_id'] = $this->crud_global->GetField('pmm_sales_po',array('id'=>$row['salesPo_id']),'contract_number');
				$row['product_id'] = $this->crud_global->GetField('produk',array('id'=>$row['product_id']),'nama_produk');
				$row['client_id'] = $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'nama');
				$row['date_production'] =  date('d F Y',strtotime($row['date_production']));
				$row['volume'] = number_format($row['volume'],2,',','.');
				$row['measure'] = $row['measure'];
				$row['harga_satuan'] = number_format($row['harga_satuan'],0,',','.');
				$row['price'] = number_format($row['price'],0,',','.');
				$row['data_lab'] = '<a href="'.base_url().'uploads/surat_jalan_penjualan_retur/'.$row['data_lab'].'" target="_blank">'.$row['data_lab'].'</a>';
				$row['surat_jalan'] = '<a href="'.base_url().'uploads/surat_jalan_penjualan_retur/'.$row['surat_jalan'].'" target="_blank">'.$row['surat_jalan'].'</a>';
				//$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$row['actions'] = '<a href="javascript:void(0);" onclick="EditData('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a> <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function total_pro()
	{	
		$data = array();
		$client_id = $this->input->post('client_id');
		$product_id = $this->input->post('product_id');
		$w_date = $this->input->post('filter_date');

		$this->db->select('SUM(volume) as total');
		$this->db->where('status !=','DELETED');
		if(!empty($client_id)){
			$this->db->where('client_id',$client_id);
		}
		if(!empty($product_id)){
			$this->db->where('product_id',$product_id);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('date_production  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('date_production <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->order_by('date_production','desc');
		$query = $this->db->get('pmm_productions');
		if($query->num_rows() > 0){
			$row = $query->row_array();
			$data =  number_format($row['total'],2,',','.');
		}
		echo json_encode(array('data'=>$data));
	}


	function process()
	{
		$output['output'] = false;

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		$production_id = 0;
		$id = $this->input->post('id');
		$sales_po_id = $this->input->post('po_penjualan');
		$komposisi_id = $this->input->post('komposisi_id');
		$product_id = $this->input->post('product_id');
		$volume = str_replace(',', '.', $this->input->post('volume'));
		$price = $this->pmm_model->GetPriceProductions($sales_po_id,$product_id,$volume);
		$no_production = $this->input->post('no_production');
		$convert_value = str_replace(',', '.', $this->input->post('convert_value'));
		$display_volume = str_replace(',', '.', $this->input->post('display_volume'));
		
		$surat_jalan = $this->input->post('surat_jalan_val');

		$config['upload_path']          = 'uploads/surat_jalan_penjualan/';
        $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG|pdf';
	   
		$production = $this->db->get_where("pmm_productions",["no_production" => $no_production])->num_rows();

		$this->load->library('upload', $config);
		

		if ($production > 1) {
			$output['output'] = false;
			$output['err'] = 'No. Surat Jalan Telah Terdaftar !!';
		}else{
			if(isset($_FILES["data_lab"])){
				if($_FILES["data_lab"]["error"] == 0) {
					$config['file_name'] = $no_production.'_'.$_FILES["data_lab"]['name'];
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('data_lab'))
					{
							$error = $this->upload->display_errors();
							$file = $error;
							$error_file = true;
					}else{
							$data_file = $this->upload->data();
							$file = $data_file['file_name'];
					}
				}
			}
			
	
			
			if($_FILES["surat_jalan"]["error"] == 0) {
				$config['file_name'] = $no_production.'_'.$_FILES["surat_jalan"]['name'];
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('surat_jalan'))
				{
						$error = $this->upload->display_errors();
						$file = $error;
						$error_file = true;
				}else{
						$data_file = $this->upload->data();
						$surat_jalan = $data_file['file_name'];
				}
			}
	
			$data = array(
				'date_production' => date('Y-m-d',strtotime($this->input->post('date'))),
				'no_production' => $no_production,
				'product_id' => $product_id,
				'tax_id' => $this->input->post('tax_id'),
				'client_id' => $this->input->post('client_id'),
				'no_production' => $this->input->post('no_production'),
				'volume' => $volume,
				'convert_value' => $convert_value,
				'display_volume' => $display_volume,
				'measure' => $this->input->post('measure'),
				'convert_measure' => $this->input->post('convert_measure'),
				'komposisi_id' => $this->input->post('komposisi_id'),
				'nopol_truck' => $this->input->post('nopol_truck'),
				'driver' => $this->input->post('driver'),
				'price' => $price,
				'salesPo_id' => $this->input->post('po_penjualan'),
				'status' => 'PUBLISH',
				'status_payment' => 'UNCREATED',
				'surat_jalan' => $surat_jalan,
				'memo' => $this->input->post('memo'),
				'harga_satuan' => $price /  $volume,
				'display_price' => $price,
				'display_harga_satuan' => $price /  $display_volume,
			);
	
	
			if(empty($id)){
				$data['created_by'] = $this->session->userdata('admin_id');
				$data['created_on'] = date('Y-m-d H:i:s');
				if($this->db->insert('pmm_productions',$data)){
					$production_id = $this->db->insert_id();
					
					// Insert COA
					$coa_description = 'Production Nomor '.$no_production;
					$this->pmm_finance->InsertTransactions(4,$coa_description,$price,0);

	
				}
			}else {
				$data['updated_by'] = $this->session->userdata('admin_id');
				$data['updated_on'] = date('Y-m-d H:i:s');
				$this->db->update('pmm_productions',$data,array('id'=>$id));
	
				$check_pro = $this->db->get_where('pmm_productions',array('id'=>$id,'product_id'=>$product_id))->num_rows();
				if($check_pro == 0){
				}
				
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
				$output['id'] = $production_id;
				$output['output'] = true;	
				// $output['no_production'] = $this->pmm_model->ProductionsNo();
			}

			
		}
        

		
		echo json_encode($output);
	}
	
	function process_retur()
	{
	$output['output'] = false;

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		$production_id = 0;
		$id = $this->input->post('id');
		$sales_po_id = $this->input->post('po_penjualan');
		$komposisi_id = $this->input->post('komposisi_id');
		$product_id = $this->input->post('product_id');
		$volume = str_replace(',', '.', $this->input->post('volume'));
		$price = $this->pmm_model->GetPriceProductions($sales_po_id,$product_id,$volume);
		$no_production = $this->input->post('no_production');
		$convert_value = str_replace(',', '.', $this->input->post('convert_value'));
		$display_volume = str_replace(',', '.', $this->input->post('display_volume'));
		
		$file = '';
		$surat_jalan = $this->input->post('surat_jalan_val');

		$config['upload_path']          = 'uploads/surat_jalan_penjualan_retur/';
        $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG|pdf';
	   
		$production = $this->db->get_where("pmm_productions_retur",["no_production" => $no_production])->num_rows();

		$this->load->library('upload', $config);
		

		if ($production > 1) {
			$output['output'] = false;
			$output['err'] = 'No. Surat Jalan Telah Terdaftar !!';
		}else{
			if(isset($_FILES["data_lab"])){
				if($_FILES["data_lab"]["error"] == 0) {
					$config['file_name'] = $no_production.'_'.$_FILES["data_lab"]['name'];
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('data_lab'))
					{
							$error = $this->upload->display_errors();
							$file = $error;
							$error_file = true;
					}else{
							$data_file = $this->upload->data();
							$file = $data_file['file_name'];
					}
				}
			}
			
	
			
			if($_FILES["surat_jalan"]["error"] == 0) {
				$config['file_name'] = $no_production.'_'.$_FILES["surat_jalan"]['name'];
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('surat_jalan'))
				{
						$error = $this->upload->display_errors();
						$file = $error;
						$error_file = true;
				}else{
						$data_file = $this->upload->data();
						$surat_jalan = $data_file['file_name'];
				}
			}
	
			$data = array(
				'date_production' => date('Y-m-d',strtotime($this->input->post('date'))),
				'no_production' => $no_production,
				'product_id' => $product_id,
				'client_id' => $this->input->post('client_id'),
				'no_production' => $this->input->post('no_production'),
				'volume' => $volume,
				'convert_value' => $convert_value,
				'display_volume' => $display_volume,
				'measure' => $this->input->post('measure'),
				'convert_measure' => $this->input->post('convert_measure'),
				'komposisi_id' => $this->input->post('komposisi_id'),
				'nopol_truck' => $this->input->post('nopol_truck'),
				'driver' => $this->input->post('driver'),
				'price' => $price,
				'salesPo_id' => $this->input->post('po_penjualan'),
				'status' => 'PUBLISH',
				'status_payment' => 'UNCREATED',
				'data_lab' => $file,
				'surat_jalan' => $surat_jalan,
				'memo' => $this->input->post('memo'),
				'harga_satuan' => $price /  $volume,
				'display_price' => $price,
				'display_harga_satuan' => $price /  $display_volume,
			);
	
	
			if(empty($id)){
				$data['created_by'] = $this->session->userdata('admin_id');
				$data['created_on'] = date('Y-m-d H:i:s');
				if($this->db->insert('pmm_productions_retur',$data)){
					$production_id = $this->db->insert_id();
					
					// Insert COA
					$coa_description = 'Production Nomor '.$no_production;
					$this->pmm_finance->InsertTransactions(4,$coa_description,$price,0);

	
				}
			}else {
				$data['updated_by'] = $this->session->userdata('admin_id');
				$data['updated_on'] = date('Y-m-d H:i:s');
				$this->db->update('pmm_productions_retur',$data,array('id'=>$id));
	
				$check_pro = $this->db->get_where('pmm_productions_retur',array('id'=>$id,'product_id'=>$product_id))->num_rows();
				if($check_pro == 0){
				}
				
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
				$output['id'] = $production_id;
				$output['output'] = true;	
				// $output['no_production'] = $this->pmm_model->ProductionsNo();
			}

			
		}
        

		
		echo json_encode($output);
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
				'supplier_id' => $this->input->post('supplier_id'),
				'total' => $this->input->post('total'),
				'approved_by' => $this->session->userdata('admin_id'),
				'approved_on' => date('Y-m-d H:i:s'),
				'status' => 'PUBLISH'
			);
			if($this->db->update('pmm_productions',$data,array('id'=>$id))){
				$output['output'] = true;
				$output['url'] = site_url('admin/productions');
			}
		}
		echo json_encode($output);
	}

	public function get_composition()
	{
		$output['output'] = true;
		$product_id = $this->input->post('product_id');
		if(!empty($product_id)){
			$query = $this->db->select('id, product_id,composition_name as text')->get_where('pmm_composition',array('product_id'=>$product_id,'status'=>'PUBLISH'))->result_array();
			if(!empty($query)){
				$data = array();
				$data[0] = array('id'=>'','text'=>'Pilih Composition');
				foreach ($query as $key => $row) {

					$data[] = array('id'=>$row['id'],'text'=>$row['text']);
				}
				$output['output'] = true;
				$output['data'] = $data;
			}
		}
		echo json_encode($output);
	}
	
	public function get_composition_retur()
	{
		$output['output'] = true;
		$product_id = $this->input->post('product_id');
		if(!empty($product_id)){
			$query = $this->db->select('id, product_id,composition_name as text')->get_where('pmm_composition',array('product_id'=>$product_id,'status'=>'PUBLISH'))->result_array();
			if(!empty($query)){
				$data = array();
				$data[0] = array('id'=>'','text'=>'Pilih Composition');
				foreach ($query as $key => $row) {

					$data[] = array('id'=>$row['id'],'text'=>$row['text']);
				}
				$output['output'] = true;
				$output['data'] = $data;
			}
		}
		echo json_encode($output);
	}

	public function get_po_products()
	{
		$output['output'] = true;
		$id = $this->input->post('id');
		if(!empty($id)){
			$client_id = $this->crud_global->GetField('pmm_sales_po',array('id'=>$id),'client_id');
			$query = $this->db->select('product_id')->get_where('pmm_sales_po_detail',array('sales_po_id'=>$id))->result_array();
			if(!empty($query)){
				$data = array();
				$data[0] = array('id'=>'','text'=>'Pilih Produk');
				foreach ($query as $key => $row) {
					$product_name = $this->crud_global->GetField('produk',array('id'=>$row['product_id']),'nama_produk');
					$data[] = array('id'=>$row['product_id'],'text'=>$product_name);
				}
				$output['products'] = $data;
			}
			$client = array();
			$client_name = $this->crud_global->GetField('penerima',array('id'=>$client_id),'nama');
			$client[0] = array('id'=>$client_id,'text'=>$client_name);
			$output['client'] = $client;
			$output['output'] = true;
		}
		echo json_encode($output);
	}

	public function get_po_penjualan(){

		$response = [
			'output' => true,
			'po' => null
		];

		try {

			$id = $this->input->post('id');

			$this->db->select('psp.id, psp.contract_number, psp.client_id');
			$this->db->from('pmm_sales_po psp');
			$this->db->where('psp.client_id = ' . intval($id));
			$this->db->where('psp.status','OPEN');
			$this->db->group_by('psp.id');
			$query = $this->db->get()->result_array();

			$data = [];
			//$data[0] = ['id'=>'','text'=>'Pilih No. Sales Order'];

			if (!empty($query)){
				foreach ($query as $row){
					$data[] = ['id' => $row['id'], 'text' => $row['contract_number']];
				}
			}

			$response['po'] = $data;

		} catch (Throwable $e){
			$response['output'] = false;
		} finally {
			echo json_encode($response);
		}
			
	}

	public function get_materials(){

		$response = [
			'output' => true,
			'po' => null
		];

		try {

			$id = $this->input->post('id');

			$this->db->select('p.id, p.nama_produk, pspd.measure');
			$this->db->from('produk p ');
			$this->db->join('pmm_sales_po_detail pspd','p.id = pspd.product_id','left');
			$this->db->join('pmm_sales_po psp ','pspd.sales_po_id = psp.id','left');
			$this->db->where("psp.id = " . intval($id));
			$query = $this->db->get()->result_array();

			$data = [];
			//$data[0] = ['id'=>'','text'=>'Pilih Produk'];

			if (!empty($query)){
				foreach ($query as $row){
					$data[] = ['id' => $row['id'], 'text' => $row['nama_produk']];
				}
			}

			$response['products'] = $data;

		} catch (Throwable $e){
			$response['output'] = false;
		} finally {
			echo json_encode($response);
		}
			
	}

	public function get_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // $pdf->set_header_title('Laporan'
		// $pdf->set_nsi_header(FALSE);
        $pdf->setPrintHeader(false);
        $pdf->SetTopMargin(0);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
        $pdf->AddPage('P');

        $id = $this->uri->segment(4);

		$row = $this->db->get_where('pmm_productions',array('id'=>$id))->row_array();
		$row['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$row['product_id']),'product');
		$row['client'] = $this->crud_global->GetField('pmm_client',array('id'=>$row['client_id']),'client_name');
		$data['row'] = $row;
		$data['id'] = $id;
        $html = $this->load->view('pmm/productions_pdf',$data,TRUE);

        
        $pdf->SetTitle($row['no_production']);
        $pdf->nsi_html($html);
        $pdf->Output($row['no_production'].'.pdf', 'I');
	
	}

	public function delete()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		
		$this->db->delete('pmm_productions',array('id'=>$id));
		$output['output'] = true;
			
		
		echo json_encode($output);
	}

	public function delete_retur()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		
		$this->db->delete('pmm_productions_retur',$data,array('id'=>$id));
		$output['output'] = true;

		echo json_encode($output);
	}

	public function table_dashboard()
	{
		$data = $this->pmm_model->DashboardProductions();

		echo json_encode(array('data'=>$data));
	}


	function table_date()
	{
		$data = array();
		$client_id = $this->input->post('client_id');
		$filter_product = $this->input->post('filter_product');
		$start_date = false;
		$end_date = false;
		$date = $this->input->post('filter_date');
		$date_text = '';
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));

			$date_text = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		}

		$arr_filter_prods = array();
		if(!empty($filter_product)){
			$query_mats = $this->db->select('id')->get_where('pmm_product',array('status'=>'PUBLISH','tag_id'=>$filter_product))->result_array();
			foreach ($query_mats as $key => $row) {
				$arr_filter_prods[] = $row['id'];
			}
		}

		// $products = $this->db->select('id,product')->get_where('pmm_product',array('status'=>'PUBLISH'))->result_array();
		$total_real = 0;
		$total_cost = 0;
		$no =1;


		$this->db->select('pc.client_name,pp.client_id, SUM(volume) as total, SUM(price) as cost');
		$this->db->join('pmm_client pc','pp.client_id = pc.id','left');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($client_id)){
        	$this->db->where('pp.client_id',$client_id);
        }
        if(!empty($arr_filter_prods)){
        	$this->db->where_in('pp.product_id',$arr_filter_prods);
        }
		$this->db->where('pc.status','PUBLISH');
		$this->db->where('pp.status','PUBLISH');
		$this->db->group_by('pp.client_id');
		$clients = $this->db->get('pmm_productions pp')->result_array();
		if(!empty($clients)){
			foreach ($clients as $key => $row) {

				$this->db->select('SUM(pp.volume) as total, SUM(pp.price) as cost, pc.product');
		        $this->db->join('pmm_product pc','pp.product_id = pc.id','left');
		        if(!empty($start_date) && !empty($end_date)){
		            $this->db->where('pp.date_production >=',$start_date);
		            $this->db->where('pp.date_production <=',$end_date);
		        }
		        if(!empty($client_id)){
		            $this->db->where('pp.client_id',$client_id);
		        }
		        if(!empty($arr_filter_prods)){
		        	$this->db->where_in('pp.product_id',$arr_filter_prods);
		        }
		        $this->db->where('pp.client_id',$row['client_id']);
		        $this->db->where('pp.status','PUBLISH');
		        $this->db->where('pc.status','PUBLISH');
		        $this->db->group_by('pp.product_id');
		        $arr_products = $this->db->get_where('pmm_productions pp')->result_array();


				$arr['no'] = $no;
				$arr['products'] = $arr_products;
				$arr['total'] = $row['total'];
				$arr['cost'] = $row['cost'];
				$arr['client'] = $row['client_name'];
				$total_real += $row['total'];
				$total_cost += $row['cost'];
				$data[] = $arr;
				$no++;
			}
		}

		// foreach ($products as $key => $row) {
		// 	$get_real = $this->pmm_model->GetRealProd($row['id'],$start_date,$end_date,$client_id);
		// 	if($get_real['total'] > 0){
		// 		$arr_clients = $this->pmm_model->GetRealProdByClient($row['id'],$start_date,$end_date,$client_id);
		// 		
		// 	}
			
		// }

		echo json_encode(array('data'=>$data,'date_text'=> $date_text,'total_real'=>$total_real,'total_cost'=>$total_cost));	
	}

	function table_date2()
	{
		$data = array();
		$client_id = $this->input->post('client_id');
		$filter_product = $this->input->post('filter_product');
		$start_date = false;
		$end_date = false;
		$date = $this->input->post('filter_date');
		$date_text = '';
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));

			$date_text = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		}
		
		$total_real = 0;
		$total_cost = 0;
		$no =1;


		$this->db->select('pc.nama,pp.client_id, SUM(volume) as total, SUM(price) as cost');
		$this->db->join('penerima pc','pp.client_id = pc.id and pc.pelanggan = 1','left');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($client_id)){
        	$this->db->where('pp.client_id',$client_id);
        }
        if(!empty($filter_product)){
        	$this->db->where_in('pp.product_id',$filter_product);
        }
		$this->db->where('pc.status','PUBLISH');
		$this->db->where('pp.status','PUBLISH');
		$this->db->group_by('pp.client_id');
		$clients = $this->db->get('pmm_productions pp')->result_array();	

		if(!empty($clients)){
			foreach ($clients as $key => $row) {

				$this->db->select('SUM(pp.volume) as total, SUM(pp.price) / SUM(pp.volume) as price, SUM(pp.volume) * SUM(pp.price) / SUM(pp.volume) as cost, pc.nama_produk as product, pp.measure');
		        $this->db->join('produk pc','pp.product_id = pc.id','left');
				$this->db->join('pmm_sales_po po','pp.salesPo_id = po.id');
				//$this->db->join('pmm_sales_po_detail pod','po.id = pod.sales_po_id');
		        if(!empty($start_date) && !empty($end_date)){
		            $this->db->where('pp.date_production >=',$start_date);
		            $this->db->where('pp.date_production <=',$end_date);
		        }
		        if(!empty($client_id)){
		            $this->db->where('pp.client_id',$client_id);
		        }
		        if(!empty($filter_product)){
		        	$this->db->where_in('pp.product_id',$filter_product);
		        }
		        $this->db->where('pp.client_id',$row['client_id']);
		        $this->db->where('pp.status','PUBLISH');
		        $this->db->where('pc.status','PUBLISH');
		        $this->db->group_by('pp.product_id');
		        $arr_products = $this->db->get_where('pmm_productions pp')->result_array();

				$arr['no'] = $no;
				$arr['products'] = $arr_products;
				$arr['total'] = $row['total'];
				$arr['cost'] = $row['cost'];
				$arr['client'] = $row['nama'];
				$total_real += $row['total'];
				$total_cost += $row['cost'];
				$data[] = $arr;
				$no++;
			}
		}


		echo json_encode(array('data'=>$data,'date_text'=> $date_text,'total_real'=>$total_real,'total_cost'=>$total_cost));	
	}


	public function edit_data_detail()
	{
		$id = $this->input->post('id');

		$data = $this->db->get_where('pmm_productions prm',array('prm.id'=>$id))->row_array();
		$data['date_production'] = date('d-m-Y',strtotime($data['date_production']));
		echo json_encode(array('data'=>$data));		
	}
	
	public function edit_data_detail_retur()
	{
		$id = $this->input->post('id');

		$data = $this->db->get_where('pmm_productions_retur prm',array('prm.id'=>$id))->row_array();
		$data['date_production'] = date('d-m-Y',strtotime($data['date_production']));
		echo json_encode(array('data'=>$data));		
	}

	public function print_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetTopMargin(5);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('L');

		$w_date = $this->input->get('filter_date');
		$product_id = $this->input->get('product_id');
		$client_id = $this->input->get('client_id');
		$salesPo_id = $this->input->get('salesPo_id');
		$filter_date = false;


		$this->db->select('pp.*,pc.nama,ppr.product');
		if(!empty($client_id)){
			$this->db->where('pp.client_id',$client_id);
		}
		if(!empty($product_id) || $product_id != 0){
			$this->db->where('pp.product_id',$product_id);
		}
		if(!empty($salesPo_id) || $salesPo_id != 0){
			$this->db->where('pp.salesPo_id',$salesPo_id);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('pp.date_production  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('pp.date_production <=',date('Y-m-d',strtotime($end_date)));	
			$filter_date = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		}
		$this->db->join('pmm_product ppr','pp.product_id = ppr.id','left');
		$this->db->join('penerima pc','pp.client_id = pc.id','left');
		$this->db->order_by('pp.date_production','asc');
		$this->db->order_by('pp.created_on','asc');
		$this->db->group_by('pp.id');
		$query = $this->db->get('pmm_productions pp');
		

		$data['data'] = $query->result_array();
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('pmm/productions_print',$data,TRUE);

        
        $pdf->SetTitle('rekap_surat_jalan_penjualan');
        $pdf->nsi_html($html);
        $pdf->Output('rekap_surat_jalan_penjualan.pdf', 'I');
	
	}
	
	public function print_pdf_retur()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetTopMargin(5);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		        $pdf->AddPage('L');

		$w_date = $this->input->get('filter_date');
		$product_id = $this->input->get('product_id');
		$client_id = $this->input->get('client_id');
		$salesPo_id = $this->input->get('salesPo_id');
		$filter_date = false;


		$this->db->select('pp.*,pc.nama,ppr.product');
		if(!empty($client_id)){
			$this->db->where('pp.client_id',$client_id);
		}
		if(!empty($product_id) || $product_id != 0){
			$this->db->where('pp.product_id',$product_id);
		}
		if(!empty($salesPo_id) || $salesPo_id != 0){
			$this->db->where('pp.salesPo_id',$salesPo_id);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('pp.date_production  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('pp.date_production <=',date('Y-m-d',strtotime($end_date)));	
			$filter_date = date('d F Y',strtotime($start_date)).' - '.date('d F Y',strtotime($end_date));
		}
		$this->db->join('pmm_product ppr','pp.product_id = ppr.id','left');
		$this->db->join('penerima pc','pp.client_id = pc.id','left');
		$this->db->order_by('pp.date_production','asc');
		$this->db->order_by('pp.created_on','asc');
		$this->db->group_by('pp.id');
		$query = $this->db->get('pmm_productions_retur pp');
		

		$data['data'] = $query->result_array();
		$data['filter_date'] = $filter_date;
        $html = $this->load->view('pmm/productions_retur_print',$data,TRUE);

        
        $pdf->SetTitle('rekap_surat_jalan_penjualan_retur');
        $pdf->nsi_html($html);
        $pdf->Output('rekap_surat_jalan_penjualan_retur.pdf', 'I');
	
	}


	function post_price()
	{
		$this->db->where('product_id !=',5);
		$arr = $this->db->get('pmm_productions');
		foreach ($arr->result_array() as $row) {
			$contract_price = $this->crud_global->GetField('pmm_product',array('id'=>$row['product_id']),'contract_price');
			$price = $row['volume'] * $contract_price;
			$this->db->update('pmm_productions',array('price'=>$price),array('id'=>$row['id']));
		}
	}
	
	function table_date10()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$jumlah_all = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('pso.id, ps.nama, SUM(pso.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pso.contract_date >=',$start_date);
            $this->db->where('pso.contract_date <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pso.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('pod.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('psod.sales_po_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'pso.client_id = ps.id');
		$this->db->where("pso.status in ('OPEN','CLOSED')");
		$this->db->group_by('pso.client_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_sales_po pso');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat10($sups['nama'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['contract_date'] = date('d-m-Y',strtotime($row['contract_date']));
						$arr['contract_number'] = '<a href="'.base_url().'penjualan/dataSalesPO/'.$row['id'].'" target="_blank">'.$row['contract_number'].'</a>';
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['measure'] = $row['measure'];
						$arr['qty'] = number_format($row['qty'],2,',','.');
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['dpp'] = number_format($row['dpp'],0,',','.');
						$arr['tax'] =  number_format($row['tax'],0,',','.');
						$arr['total'] = number_format($row['total'],0,',','.');
						$arr['status'] = $row['status'];
						
						
						$arr['nama'] = $sups['nama'];
						$jumlah_all += $row['total'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['jumlah'];
					$sups['no'] = $no;
					$sups['jumlah'] = number_format($sups['jumlah'],0,',','.');
					

					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,0,',','.')));	
	}
	
	function table_date11()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('pp.id, p.nama_produk, SUM(pp.price) as all_total');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pp.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('pod.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pp.id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'pp.client_id = ps.id','left');
		$this->db->join('produk p','pp.product_id = p.id','left');
		$this->db->where('pp.status','PUBLISH');
		$this->db->group_by('p.nama_produk');
		$this->db->order_by('p.nama_produk','asc');
		$query = $this->db->get('pmm_productions pp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat11($sups['nama_produk'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['nama'] = $row['nama'];
						$arr['measure'] = $row['measure'];
						$arr['terkirim'] = number_format($row['terkirim'],2,',','.');
						$arr['dikembalikan'] = number_format($row['dikembalikan'],2,',','.');
						$arr['terjual'] = number_format($row['terjual'],2,',','.');
						$arr['terkirim_rp'] = number_format($row['terkirim_rp'],0,',','.');
						$arr['dikembalikan_rp'] =  number_format($row['dikembalikan_rp'],0,',','.');
						$arr['terjual_rp'] = number_format($row['terjual_rp'],0,',','.');
						
						
						$arr['nama_produk'] = $sups['nama_produk'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['all_total'];
					$sups['no'] =$no;
					$sups['all_total'] = number_format($sups['all_total'],0,',','.');
					

					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,'all_total'=>number_format($total,0,',','.')));	
	}
	
	function table_date12()
	{
		$data = array();
		$supplier_id = $this->input->post('client_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$jumlah_all = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}
		$this->db->select('ppp.client_id, ppp.nama_pelanggan as nama, SUM(ppp.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppd.penagihan_id',$purchase_order_no);
        }

		$this->db->group_by('ppp.nama_pelanggan');
		$this->db->order_by('ppp.nama_pelanggan','asc');
		$query = $this->db->get('pmm_penagihan_penjualan ppp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat12($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = '<a href="'.base_url().'penjualan/detailPenagihan/'.$row['id'].'" target="_blank">'.$row['nomor_invoice'].'</a>';
						$arr['memo'] = $row['memo'];
						$arr['qty'] =  number_format($row['qty'],2,',','.');
						$arr['measure'] = $row['measure'];
						$arr['ppn'] = number_format($row['ppn'],0,',','.');
						$arr['jumlah'] = number_format($row['jumlah'],0,',','.');	
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];
						$jumlah_all += $row['total_price'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['jumlah'];
					$sups['no'] =$no;
					$sups['jumlah'] = number_format($sups['jumlah'],0,',','.');
					

					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,0,',','.')));	
	}
	
	function table_date13()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('ppp.client_id, ps.nama, SUM(ppp.total) as total_tagihan, SUM((select COALESCE(SUM(total),0) from pmm_pembayaran ppm where ppm.penagihan_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_penerimaan, SUM(ppp.total - (select COALESCE(SUM(total),0) from pmm_pembayaran ppm where ppm.penagihan_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_piutang');

		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.product_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppm.penagihan_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'ppp.client_id = ps.id','left');
		$this->db->where('ppp.status','OPEN');
		$this->db->group_by('ppp.client_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_penjualan ppp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat13($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = '<a href="'.base_url().'penjualan/detailPenagihan/'.$row['id'].'" target="_blank">'.$row['nomor_invoice'].'</a>';
						$arr['memo'] = $row['memo'];
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');	
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');	
						$arr['piutang'] = number_format($row['piutang'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_piutang'];
					$sups['no'] =$no;
					$sups['total_tagihan'] = number_format($sups['total_tagihan'],0,',','.');
					$sups['total_penerimaan'] = number_format($sups['total_penerimaan'],0,',','.');
					$sups['total_piutang'] = number_format($sups['total_piutang'],0,',','.');
					

					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,0,',','.')));	
	}
	
	public function umur_piutang($arr_date)
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
		<script type="text/javascript">        
			function tampilkanwaktu(){         //fungsi ini akan dipanggil di bodyOnLoad dieksekusi tiap 1000ms = 1detik    
			var waktu = new Date();            //membuat object date berdasarkan waktu saat 
			var sh = waktu.getHours() + "";    //memunculkan nilai jam, //tambahan script + "" supaya variable sh bertipe string sehingga bisa dihitung panjangnya : sh.length    //ambil nilai menit
			var sm = waktu.getMinutes() + "";  //memunculkan nilai detik    
			var ss = waktu.getSeconds() + "";  //memunculkan jam:menit:detik dengan menambahkan angka 0 jika angkanya cuma satu digit (0-9)
			document.getElementById("clock").innerHTML = (sh.length==1?"0"+sh:sh) + ":" + (sm.length==1?"0"+sm:sm) + ":" + (ss.length==1?"0"+ss:ss);
			}
		</script>

		<?php

		$date_now = date('Y-m-d');

		$penagihan_penjualan = $this->db->select('ppp.*, p.nama, ppp.total - (select COALESCE(sum(total),0) from pmm_pembayaran pm where pm.penagihan_id = ppp.id) as total_pembayaran')
		->from('pmm_penagihan_penjualan ppp')
		->join('penerima p','ppp.client_id = p.id','left')
		->where("ppp.status = 'OPEN'")
		->order_by('ppp.tanggal_invoice','desc')
		->get()->result_array();

		?>

		<tr class="table-active2">
			<th class="text-center" colspan="10">
				<blink>
				<?php
					$hari = date('l');
					/*$new = date('l, F d, Y', strtotime($Today));*/
					if ($hari=="Sunday") {
					echo "Minggu";
					}elseif ($hari=="Monday") {
					echo "Senin";
					}elseif ($hari=="Tuesday") {
					echo "Selasa";
					}elseif ($hari=="Wednesday") {
					echo "Rabu";
					}elseif ($hari=="Thursday") {
					echo("Kamis");
					}elseif ($hari=="Friday") {
					echo "Jum'at";
					}elseif ($hari=="Saturday") {
					echo "Sabtu";
					}
					?>,

					<?php
					$tgl =date('d');
					echo $tgl;
					$bulan =date('F');
					if ($bulan=="January") {
					echo " Januari ";
					}elseif ($bulan=="February") {
					echo " Februari ";
					}elseif ($bulan=="March") {
					echo " Maret ";
					}elseif ($bulan=="April") {
					echo " April ";
					}elseif ($bulan=="May") {
					echo " Mei ";
					}elseif ($bulan=="June") {
					echo " Juni ";
					}elseif ($bulan=="July") {
					echo " Juli ";
					}elseif ($bulan=="August") {
					echo " Agustus ";
					}elseif ($bulan=="September") {
					echo " September ";
					}elseif ($bulan=="October") {
					echo " Oktober ";
					}elseif ($bulan=="November") {
					echo " November ";
					}elseif ($bulan=="December") {
					echo " Desember ";
					}
					$tahun=date('Y');
					echo $tahun;
					?>
				</blink>
			</th>
		</tr>
		<tr class="table-active4">
			<th class="text-center">NO.</th>
			<th class="text-center">NO. INVOICE</th>
			<th class="text-center">TGL. INVOICE</th>
			<th class="text-center">REKANAN</th>
			<th class="text-center">TOTAL</th>
			<th class="text-center">1-30 HARI</th>
			<th class="text-center">31-60 HARI</th>
			<th class="text-center">61-90 HARI</th>
			<th class="text-center">> 90 HARI</th>
		</tr>
		<?php   
		if(!empty($penagihan_penjualan)){
		foreach ($penagihan_penjualan as $key => $x) {
		$dateOne30 = new DateTime($x['tanggal_invoice']);
		$dateTwo30 = new DateTime($date_now);
		$diff30 = $dateTwo30->diff($dateOne30)->format("%a");

		$dateOne60 = new DateTime($x['tanggal_invoice']);
		$dateTwo60 = new DateTime($date_now);
		$diff60 = $dateTwo60->diff($dateOne60)->format("%a");

		$dateOne90 = new DateTime($x['tanggal_invoice']);
		$dateTwo90 = new DateTime($date_now);
		$diff90 = $dateTwo90->diff($dateOne90)->format("%a");

		$dateOne120 = new DateTime($x['tanggal_invoice']);
		$dateTwo120 = new DateTime($date_now);
		$diff120 = $dateTwo120->diff($dateOne120)->format("%a");
		?>
		<tr class="table-active3">
			<th class="text-center"><?php echo $key + 1;?></th>
			<th class="text-left"><a target="_blank" href="<?= base_url("penjualan/detailPenagihan/".$x['id']) ?>"><?= $x['nomor_invoice'] ?><a/></th>
			<th class="text-center"><?= date('d-m-Y',strtotime($x['tanggal_invoice'])); ?></th>
			<th class="text-left"><?= $x['nama'] ?></th>
			<th class="text-right"><?php echo number_format($x['total_pembayaran'],0,',','.');?></th>
			<th class="text-right"><?php echo ($diff30 >= 0 && $diff30 <= 30) ? number_format($x['total_pembayaran'],0,',','.') : '';?></th>
			<th class="text-right"><?php echo ($diff60 >= 31 && $diff60 <= 60) ? number_format($x['total_pembayaran'],0,',','.') : '';?></th>
			<th class="text-right"><?php echo ($diff90 >= 61 && $diff90 <= 90) ? number_format($x['total_pembayaran'],0,',','.') : '';?></th>
			<th class="text-right"><?php echo ($diff120 >= 91 && $diff120 <= 999) ? number_format($x['total_pembayaran'],0,',','.') : '';?></th>
		</tr>
		<?php
        }
        }
        ?>
	</table>
	<?php
	}

	function table_date15()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_name');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}
		
		$this->db->select('pmp.client_id, pmp.nama_pelanggan as nama, SUM(pmp.total) AS total_bayar');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pmp.tanggal_pembayaran >=',$start_date);
            $this->db->where('pmp.tanggal_pembayaran <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pmp.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pmp.penagihan_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_penagihan_penjualan ppp', 'pmp.penagihan_id = ppp.id','left');
		$this->db->group_by('pmp.client_id');
		$this->db->order_by('pmp.nama_pelanggan','asc');
		$query = $this->db->get('pmm_pembayaran pmp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat15($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_pembayaran'] =  date('d-m-Y',strtotime($row['tanggal_pembayaran']));
						$arr['nomor_transaksi'] = '<a href="'.base_url().'penjualan/view_pembayaran/'.$row['id'].'" target="_blank">'.$row['nomor_transaksi'].'</a>';
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = '<a href="'.base_url().'penjualan/detailPenagihan/'.$row['penagihan_id'].'" target="_blank">'.$row['nomor_transaksi'].'</a>';
						$arr['penerimaan'] = number_format($row['penerimaan'],0,',','.');								
						
						$arr['nama'] = $sups['nama'];
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['total_bayar'];
					$sups['no'] =$no;
					$sups['total_bayar'] = number_format($sups['total_bayar'],0,',','.');
					

					$data[] = $sups;
					$no++;
					
				}		
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,0,',','.')));	
	}
	
	function table_date16()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_name');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$grand_total_vol_pemesanan = 0;
		$grand_total_pemesanan = 0;
		$grand_total_vol_pengiriman = 0;
		$grand_total_pengiriman = 0;
		$grand_total_vol_tagihan = 0;
		$grand_total_tagihan = 0;
		$grand_total_vol_pembayaran = 0;
		$grand_total_pembayaran = 0;
		$grand_total_vol_piutang_pengiriman = 0;
		$grand_total_piutang_pengiriman = 0;
		$grand_total_vol_sisa_tagihan = 0;
		$grand_total_sisa_tagihan = 0;
		$grand_total_vol_akhir = 0;
		$grand_total_akhir = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('po.id, po.client_id, p.nama');
		
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('po.contract_date >=',$start_date);
            $this->db->where('po.contract_date <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('po.client_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.salesPo_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_sales_po_detail pod', 'po.id = pod.sales_po_id','left');
		$this->db->join('penerima p', 'po.client_id = p.id','left');
		$this->db->where("po.status in ('OPEN','CLOSED')");
		$this->db->group_by('po.client_id');
		$this->db->order_by('p.nama','ASC');
		$query = $this->db->get('pmm_sales_po po');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat16($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$vol_piutang_pengiriman = number_format($row['vol_pengiriman'] - $row['vol_tagihan'],2,',','.');
						$piutang_pengiriman = number_format($row['pengiriman'] - $row['tagihan'],0,',','.');
						$vol_sisa_tagihan = number_format($row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$sisa_tagihan = number_format($row['tagihan'] - $row['pembayaran'],0,',','.');
						$vol_akhir = number_format($row['vol_pengiriman'] - $row['vol_tagihan'] + $row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$akhir = number_format($row['pengiriman'] - $row['tagihan'] + $row['tagihan'] - $row['pembayaran'],0,',','.');

						$arr['no'] = $key + 1;
						$arr['contract_date'] = date('d-m-Y',strtotime($row['contract_date']));
						$arr['contract_number'] = $row['contract_number'];
						$arr['status'] = $row['status'];
						$arr['vol_pemesanan'] = number_format($row['vol_pemesanan'],2,',','.');
						$arr['pemesanan'] = number_format($row['pemesanan'],0,',','.');
						$arr['vol_pengiriman'] = number_format($row['vol_pengiriman'],2,',','.');
						$arr['pengiriman'] = number_format($row['pengiriman'],0,',','.');
						$arr['vol_tagihan'] = number_format($row['vol_tagihan'],2,',','.');
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');
						$arr['vol_pembayaran'] = number_format($row['vol_pembayaran'],2,',','.');
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');
						$arr['vol_piutang_pengiriman'] = $vol_piutang_pengiriman;
						$arr['piutang_pengiriman'] = $piutang_pengiriman;
						$arr['vol_sisa_tagihan'] = $vol_sisa_tagihan;
						$arr['sisa_tagihan'] = $sisa_tagihan;
						$arr['vol_akhir'] = $vol_akhir;
						$arr['akhir'] = $akhir;
													
						
						$arr['client_id'] = $sups['client_id'];
						$grand_total_vol_pemesanan += $row['vol_pemesanan'];
						$grand_total_pemesanan += $row['pemesanan'];
						$grand_total_vol_pengiriman += $row['vol_pengiriman'];
						$grand_total_pengiriman += $row['pengiriman'];
						$grand_total_vol_tagihan += $row['vol_tagihan'];
						$grand_total_tagihan += $row['tagihan'];
						$grand_total_vol_pembayaran += $row['vol_pembayaran'];
						$grand_total_pembayaran += $row['pembayaran'];
						$grand_total_vol_piutang_pengiriman += $row['vol_pengiriman'] - $row['vol_tagihan'];
						$grand_total_piutang_pengiriman += $row['pengiriman'] - $row['tagihan'];
						$grand_total_vol_sisa_tagihan += $row['vol_tagihan'] - $row['vol_pembayaran'];
						$grand_total_sisa_tagihan += $row['tagihan'] - $row['pembayaran'];
						$grand_total_vol_akhir += ($row['vol_pengiriman'] - $row['vol_tagihan']) + ($row['vol_tagihan'] - $row['vol_pembayaran']);
						$grand_total_akhir += ($row['pengiriman'] - $row['tagihan']) + ($row['tagihan'] - $row['pembayaran']); 		
						$mats[] = $arr;
					}		
					
					$sups['mats'] = $mats;
					$sups['no'] =$no;			

					$data[] = $sups;
					$no++;
					
				}		
				
			}
		}

		echo json_encode(array('data'=>$data,
		'grand_total_vol_pemesanan'=>number_format($grand_total_vol_pemesanan,2,',','.'),
		'grand_total_pemesanan'=>number_format($grand_total_pemesanan,0,',','.'),
		'grand_total_vol_pengiriman'=>number_format($grand_total_vol_pengiriman,2,',','.'),
		'grand_total_pengiriman'=>number_format($grand_total_pengiriman,0,',','.'),
		'grand_total_vol_tagihan'=>number_format($grand_total_vol_tagihan,2,',','.'),
		'grand_total_tagihan'=>number_format($grand_total_tagihan,0,',','.'),
		'grand_total_vol_pembayaran'=>number_format($grand_total_vol_pembayaran,2,',','.'),
		'grand_total_pembayaran'=>number_format($grand_total_pembayaran,0,',','.'),
		'grand_total_vol_piutang_pengiriman'=>number_format($grand_total_vol_piutang_pengiriman,2,',','.'),
		'grand_total_piutang_pengiriman'=>number_format($grand_total_piutang_pengiriman,0,',','.'),
		'grand_total_vol_sisa_tagihan'=>number_format($grand_total_vol_sisa_tagihan,2,',','.'),
		'grand_total_sisa_tagihan'=>number_format($grand_total_sisa_tagihan,0,',','.'),
		'grand_total_vol_akhir'=>number_format($grand_total_vol_akhir,2,',','.'),
		'grand_total_akhir'=>number_format($grand_total_akhir,0,',','.')
		));	
	}
	
	function table_date_lap_penjualan()
	{
		$data = array();
		$filter_client_id = $this->input->post('filter_client_id');
		$purchase_order_no = $this->input->post('salesPo_id');
		$filter_product = $this->input->post('filter_product');
		$start_date = false;
		$end_date = false;
		$total_nilai = 0;
		$total_volume = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('ppo.client_id, pp.convert_measure as convert_measure, ps.nama as name, SUM(pp.display_volume) as total, SUM(pp.display_price) as total_price');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($filter_client_id)){
            $this->db->where('ppo.client_id',$filter_client_id);
        }
        if(!empty($filter_product)){
            $this->db->where_in('pp.product_id',$filter_product);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pp.salesPo_id',$purchase_order_no);
        }
		
		
		$this->db->join('penerima ps','ppo.client_id = ps.id','left');
		$this->db->join('pmm_productions pp','ppo.id = pp.salesPo_id','left');
		$this->db->where("ppo.status in ('OPEN','CLOSED')");
		$this->db->where('pp.status','PUBLISH');
		$this->db->where("pp.product_id in (3,4,7,8,9,14,24)");
		$this->db->group_by('ppo.client_id');
		$query = $this->db->get('pmm_sales_po ppo');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat17($sups['client_id'],$purchase_order_no,$start_date,$end_date,$filter_product);
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['salesPo_id'] = '<a href="'.base_url().'penjualan/dataSalesPO/'.$row['salesPo_id'].'" target="_blank">'.$row['salesPo_id'] = $this->crud_global->GetField('pmm_sales_po',array('id'=>$row['salesPo_id']),'contract_number').'</a>';
						$arr['real'] = number_format($row['total'],2,',','.');
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['name'] = $sups['name'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total_volume += $sups['total'];
					$total_nilai += $sups['total_price'];
					$sups['no'] = $no;
					$sups['real'] = number_format($sups['total'],2,',','.');
					$sups['total_price'] = number_format($sups['total_price'],0,',','.');
					
					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,
		'total_volume'=>number_format($total_volume,2,',','.'),
		'total_nilai'=>number_format($total_nilai,0,',','.')
	));	
	}
	
	public function laporan_pengiriman_penjualan_produk($arr_date)
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

			</style>
	        <tr class="table-active2">
	            <th colspan="3">PERIODE</th>
	            <th class="text-center" colspan="3"><?php echo $filter_date;?></th>
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
			
			?>

			<?php

			//NILAI PENJUALAN ALL
			$volume_penjualan_abubatu_all = $volume_penjualan_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini + $volume_agregat_abubatu_bulan_ini_2;
			$volume_penjualan_batu0510_all = $volume_penjualan_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini + $volume_agregat_batu0510_bulan_ini_2;
			$volume_penjualan_batu1020_all = $volume_penjualan_batu1020_bulan_ini + $volume_agregat_batu1020_bulan_ini +$volume_agregat_batu1020_bulan_ini_2;
			$volume_penjualan_batu2030_all = $volume_penjualan_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini + $volume_agregat_batu2030_bulan_ini_2;

			$nilai_penjualan_abubatu_all = $nilai_penjualan_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini + $nilai_agregat_abubatu_bulan_ini_2;
			$nilai_penjualan_batu0510_all = $nilai_penjualan_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini + $nilai_agregat_batu0510_bulan_ini_2;
			$nilai_penjualan_batu1020_all = $nilai_penjualan_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini + $nilai_agregat_batu1020_bulan_ini_2;
			$nilai_penjualan_batu2030_all = $nilai_penjualan_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini + $nilai_agregat_batu2030_bulan_ini_2;

			?>

			<tr class="table-active4">
				<th width="5%" class="text-center">NO.</th>
				<th class="text-center">URAIAN</th>
				<th class="text-center">SATUAN</th>
				<th class="text-center">VOLUME</th>
				<th class="text-center">HARGA</th>
				<th class="text-center">NILAI</th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center">1.</th>
				<th class="text-left">Batu Split 0,0 - 0,5</th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_abubatu_all,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_abubatu,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_abubatu_all,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center">2.</th>
				<th class="text-left">Batu Split 0,5 - 10</th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu0510_all,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu0510,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu0510_all,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center">3.</th>
				<th class="text-left">Batu Split 10 - 20</th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu1020_all,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu1020,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu1020_all,0,',','.');?></th>
	        </tr>
			<tr class="table-active3">
				<th class="text-center">4.</th>
				<th class="text-left">Batu Split 20 - 30</th>
				<th class="text-center">Ton</th>
				<th class="text-center"><?php echo number_format($volume_penjualan_batu2030_all,2,',','.');?></th>
				<th class="text-right"><?php echo number_format($harga_penjualan_batu2030,0,',','.');?></th>
				<th class="text-right"><?php echo number_format($nilai_penjualan_batu2030_all,0,',','.');?></th>
	        </tr>
			<tr class="table-active5">
				<th class="text-center" colspan="3">TOTAL</th>
				<th class="text-center"><?php echo number_format($total_volume_keluar,2,',','.');?></th>
				<th class="text-right">-</th>
				<th class="text-right"><?php echo number_format($total_nilai_keluar,0,',','.');?></th>
			</tr>
	    </table>
		
		<?php
	}
	
}