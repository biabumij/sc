<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt_material extends CI_Controller {

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

	public function manage()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$id = $this->uri->segment(4);
			$get_data = $this->db->get_where('pmm_purchase_order',array('id'=>$id,'status !='=>'DELETED'))->row_array();
			$data['suppliers'] = $this->db->order_by('nama','asc')->get_where('penerima',array('status'=>'PUBLISH','rekanan'=>1))->result_array();
			$data['id'] = $id;
			$data['data'] = $get_data;
			$this->load->view('pmm/receipt_material_add',$data);
			
		}else {
			redirect('admin');
		}
	}

	
	public function get_mat_by_po()
	{
		$data = array();
		$purchase_order_id = $this->input->post('purchase_order_id');
		$supplier_id = $this->input->post('supplier_id');
		$materials = $this->pmm_model->GetPOMaterials($supplier_id,$purchase_order_id);
		$data[0]['id'] = '0';
		$data[0]['text'] = 'Pilih Produk';
		foreach ($materials as $key => $row) {
			$arr['id'] = $row['material_id'];
			$arr['text'] = $row['material_name'];
			$arr['measure'] = $row['measure'];
			$arr['tax_id'] = $row['tax_id'];
			$arr['tax'] = $row['tax'];
			$arr['display_measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['display_measure']),'measure_name');
			$arr['total_po'] = number_format($row['volume'],2,',','.');
			$receipt_material = $this->db->select('SUM(volume) as volume')->get_where('pmm_receipt_material',array('purchase_order_id'=>$purchase_order_id,'material_id'=>$row['material_id']))->row_array();

			$arr['receipt_material'] = number_format($receipt_material['volume'],2,',','.');
			$data[] = $arr;
		}
		echo json_encode(array('data'=>$data));
	}

	public function table()
	{	
		$data = array();
		$purchase_order_id = $this->input->post('purchase_order_id');
		$supplier_id = $this->input->post('supplier_id');
		$w_date = $this->input->post('filter_date');
		$this->db->select('no_po,id,supplier_id');
		$this->db->where('status','PUBLISH');
		if(!empty($supplier_id)){
			$this->db->where('supplier_id',$supplier_id);
		}
		if(!empty($purchase_order_id)){
			$this->db->where('id',$purchase_order_id);
		}
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_purchase_order');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$no_po = "'".$row['no_po']."'";
				$row['no_po'] = '<a href="'.site_url('pmm/purchase_order/get_pdf/'.$row['id']).'" target="_blank" >'.$row['no_po'].'</a>';
				$row['supplier'] = $this->crud_global->GetField('pmm_supplier',array('id'=>$row['supplier_id']),'name');

				$arr = $this->db->group_by('material_id')->select('material_id,id,measure')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$row['id']))->result_array();
				$detail = '';
				foreach ($arr as $k => $v) {
					$total_po = $this->db->select('SUM(volume) as total')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$row['id'],'material_id'=>$v['material_id']))->row_array();

					
    				$this->db->select('SUM(volume) as total');
    				if(!empty($w_date)){
						$arr_date = explode(' - ', $w_date);
						$start_date = $arr_date[0];
						$end_date = $arr_date[1];
						$this->db->where('date_receipt  >=',date('Y-m-d',strtotime($start_date)));	
						$this->db->where('date_receipt <=',date('Y-m-d',strtotime($end_date)));	
					}
    				$this->db->where(array('purchase_order_id'=>$row['id'],'material_id'=>$v['material_id']));
    				$total_rm = $this->db->get('pmm_receipt_material')->row_array();
					$material_name = $this->crud_global->GetField('pmm_materials',array('id'=>$v['material_id']),'material_name');
					$detail .= '<p>'.$material_name.' ('.$v['measure'].') = '.number_format($total_po['total'],4,',','.').' : <a href="javascript:void(0);" onclick="DetailData('.$row['id'].','.$v['material_id'].')">'.number_format($total_rm['total'],4,',','.').'</a></p>';
				}
				
				$row['persentase'] = $detail;

				$row['actions'] = '<a href="'.site_url('pmm/receipt_material/manage/'.$row['id']).'" class="btn btn-primary"><i class="fa fa-ambulance"></i> Add Receipt</a> <a href="javascript:void(0);" onclick="DetailData('.$row['id'].')" class="btn btn-info"><i class="fa fa-search"></i> View Receipt</a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function table_detail()
	{	
		$data = array();
		$w_date = $this->input->post('filter_date');
		$single_date = $this->input->post('single_date');
		$purchase_order_id = $this->input->post('purchase_order_id');
		$supplier_id = $this->input->post('supplier_id');
		$material_id = $this->input->post('material_id');
		$this->db->select('prm.*,ppo.no_po,ps.nama as supplier_name');
		if(!empty($supplier_id)){
			$this->db->where('ppo.supplier_id',$supplier_id);
		}
		
		if(!empty($purchase_order_id)){
			$this->db->where('prm.purchase_order_id',$purchase_order_id);
		}
		if(!empty($material_id) || $material_id != 0){
			$this->db->where('prm.material_id',$material_id);
		}
		
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('prm.date_receipt  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('prm.date_receipt <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->order_by('prm.date_receipt','DESC');
		$query = $this->db->get('pmm_receipt_material prm');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['checkbox'] ='';
				$row['no'] = $key+1;
				$row['date_receipt'] = date('d F Y',strtotime($row['date_receipt']));
				$row['supplier_name'] = $row['supplier_name'];
				$row['material_name'] = $this->crud_global->GetField('produk',array('id'=>$row['material_id']),'nama_produk');
				$row['volume'] = number_format($row['volume'],2,',','.');
				$row['harga_satuan'] = number_format($row['harga_satuan'],0,',','.');
				$row['price'] = number_format($row['price'],0,',','.');
				$row['display_price'] = number_format($row['display_price'],0,',','.');
				$row['surat_jalan_file'] = '<a href="'.base_url().'uploads/surat_jalan_penerimaan/'.$row['surat_jalan_file'].'" target="_blank">'.$row['surat_jalan_file'].'</a>';

				$row['status_payment'] = $this->pmm_model->StatusPayment($row['status_payment']);
				$edit = false;
				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4){
					$edit = '<a href="javascript:void(0);" onclick="EditData('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';			
				}

				$row['actions'] = ' <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				//$row['actions'] = $edit.' <a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function table_detail2()
	{	
		$data = array();
		$w_date = $this->input->post('filter_date');
		$single_date = $this->input->post('single_date');
		$purchase_order_id = $this->input->post('purchase_order_id');
		$supplier_id = $this->input->post('supplier_id');
		$material_id = $this->input->post('material_id');
		$this->db->select('prm.*,ppo.no_po,ps.nama as supplier_name');
		if(!empty($supplier_id)){
			$this->db->where('ppo.supplier_id',$supplier_id);
		}
		
		if(!empty($purchase_order_id)){
			$this->db->where('prm.purchase_order_id',$purchase_order_id);
		}
		if(!empty($material_id) || $material_id != 0){
			$this->db->where('prm.material_id',$material_id);
		}
		
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('prm.date_receipt  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('prm.date_receipt <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->order_by('prm.date_receipt','desc');
		$this->db->order_by('prm.created_on','desc');
		$query = $this->db->get('pmm_receipt_material prm');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['checkbox'] ='';
				$row['no'] = $key+1;
				$row['date_receipt'] = date('d F Y',strtotime($row['date_receipt']));
				$row['material_name'] = $this->crud_global->GetField('produk',array('id'=>$row['material_id']),'nama_produk');
				$row['volume_val'] = $row['volume'];
				$row['volume'] = number_format($row['volume'],2,',','.');

				$row['display_volume_val'] = $row['display_volume'];
				$row['display_volume'] = number_format($row['display_volume'],2,',','.');

				$row['display_cost'] = $row['volume_val'] * $row['price'];

				$row['display_cost_val'] = $row['display_cost'];
				$row['display_cost'] = number_format($row['display_cost'],2,',','.');

				$row['convert_value'] = number_format($row['convert_value'],2,',','.');
				
				$row['surat_jalan_file'] = '<a href="'.base_url().'uploads/surat_jalan_penerimaan/'.$row['surat_jalan_file'].'" target="_blank">'.$row['surat_jalan_file'].'</a>';

				$row['status_payment'] = $this->general->StatusPayment($row['status_payment']);
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

	public function total_mat()
	{	
		$data = array();
		$w_date = $this->input->post('filter_date');
		$single_date = $this->input->post('single_date');
		$this->db->select('SUM(volume) as total, measure');
		$this->db->where('purchase_order_id',$this->input->post('purchase_order_id'));
		$material_id = $this->input->post('material_id');
		if(!empty($material_id)){
			$this->db->where('material_id',$material_id);
		}
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('date_receipt  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('date_receipt <=',date('Y-m-d',strtotime($end_date)));	
		}
		if(!empty($single_date)){
			$this->db->where('date_receipt',date('Y-m-d',strtotime($single_date)));
		}
		
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_receipt_material');
		if($query->num_rows() > 0){
			$row = $query->row_array();
			$data =  number_format($row['total'],4,',','.').' '.$row['measure'];
		}
		echo json_encode(array('data'=>$data));
	}


	function process()
	{
		$output['output'] = false;

		

		$purchase_order_id = $this->input->post('purchase_order_id');
		$material_id = $this->input->post('material_id');
		$tax_id = $this->input->post('tax_id');
		$date_receipt = $this->input->post('date_receipt_val');
		$volume = str_replace('.', '', $this->input->post('volume'));
		$volume = str_replace(',', '.', $volume);
		$convert_value = str_replace('.', '', $this->input->post('convert_value'));
		$convert_value = str_replace(',', '.', $convert_value);
		$display_volume = str_replace('.', '', $this->input->post('display_volume'));
		$display_volume = str_replace(',', '.', $display_volume);
		$surat_jalan = $this->input->post('surat_jalan');
		$no_kendaraan = $this->input->post('no_kendaraan');
		$driver = $this->input->post('driver');
		$memo = $this->input->post('memo');
		$measure = $this->input->post('measure_id');
		$display_measure = $this->input->post('display_measure');
		$supplier_id = $this->input->post('supplier_id');

		$get_po = $this->db->select('measure,price,volume')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$purchase_order_id,'material_id'=>$material_id))->row_array();
		$price = $get_po['price'];

		$select_operation = $this->input->post('edit_select_operation');

		$convert_value = str_replace('.', '', $this->input->post('berat_isi'));
		$convert_value = str_replace(',', '.', $convert_value);
		$display_price = $price;

		$file = '';
		$error_file = false;

		
		if(!empty($surat_jalan)){
			$new_name = $surat_jalan;
		}else {
			$new_name = date('Y-m-d_H:i:s').'_'.strtolower(str_replace(' ', '_', $supplier_name));
		}

		$config['upload_path']          = './uploads/surat_jalan_penerimaan/';
        $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG|pdf';
 
        $config['file_name'] = $new_name;

        $this->load->library('upload', $config);

		if($_FILES["surat_jalan_file"]["error"] == 0) {
			if (!$this->upload->do_upload('surat_jalan_file'))
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

		$receipt_material = $this->db->select('SUM(volume) as volume')->get_where('pmm_receipt_material',array('purchase_order_id'=>$purchase_order_id,'material_id'=>$material_id))->row_array();

		if($receipt_material['volume'] + $volume > $get_po['volume']){
			$output['output'] = false;
			$output['err'] = 'Mohon maaf Volume Penerimaan sudah melebihi TOTAL PO, Silahkan buat PO baru.';
			echo json_encode($output);
			exit();
		}

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		

		$data_p = array(
			'purchase_order_id' => $purchase_order_id,
			'date_receipt' => date('Y-m-d',strtotime($date_receipt)),
			'material_id' => $material_id,
			'tax_id' => $tax_id,
			'measure' => $measure,
			'volume' => $volume,
			'convert_value' => $convert_value,
			'display_measure' => $display_measure,
			'display_volume' => $volume * $convert_value,
			'harga_satuan' => $price,
			'display_harga_satuan' => ($volume * $price) / $display_volume,
			'price'	=> ($volume * $price),
			'display_price' => ($volume * $price),
			'surat_jalan' => $surat_jalan,
			'surat_jalan_file' => $file,
			'no_kendaraan' => $no_kendaraan,
			'driver' => $driver,
			'memo' => $memo
		);

		$data_p['created_on'] = date('Y-m-d H:i:s');
		$data_p['created_by'] = $this->session->userdata('admin_id');
		$this->db->insert('pmm_receipt_material',$data_p);
		$no_production = $this->db->insert_id();

		$coa_description = 'Penerimaan Nomor '.$no_production;
		$this->pmm_finance->InsertTransactions(7,$coa_description,$price * $volume,0);

		$this->pmm_finance->InsertTransactions(39,$coa_description,0,$price * $volume);
		
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

	public function delete_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			
			if($this->db->delete('pmm_receipt_material',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}


	public function table_dashboard()
	{
		$data = array();
		$arr_date = explode(' - ', $this->input->post('date'));
		$material = $this->input->post('material');

		$this->db->select('SUM(volume) as total,pm.material_name,prm.measure');
		$this->db->join('pmm_materials pm','prm.material_id = pm.id','left');
		if(!empty($arr_date)){
			$this->db->where('prm.date_receipt >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('prm.date_receipt <=',date('Y-m-d',strtotime($arr_date[1])));
		}
		if(!empty($material)){
			$this->db->where('prm.material_id',$material);
		}
		$this->db->order_by('prm.date_receipt','desc');
		$this->db->group_by('prm.material_id');
		$query = $this->db->get('pmm_receipt_material prm');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['material_name'] = $row['material_name'].' ('.$row['measure'].')';
				$row['total'] = number_format($row['total'],2,',','.');
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	function table_date()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total_volume = 0;
		$total_nilai = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('ppo.supplier_id,prm.measure as measure,ps.nama as name, prm.harga_satuan as price,SUM(prm.volume) as volume, SUM(prm.price) as total_price');
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
		
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
		$this->db->where("prm.material_id in (13,15)");
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_purchase_order ppo');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['purchase_order_id'] = $row['purchase_order_id'] = $this->crud_global->GetField('pmm_purchase_order',array('id'=>$row['purchase_order_id']),'no_po');
						$arr['volume'] = number_format($row['volume'],2,',','.');
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['name'] = $sups['name'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total_volume += $sups['volume'];
					$total_nilai += $sups['total_price'];
					$sups['no'] =$no;
					$sups['volume'] = number_format($sups['volume'],2,',','.');
					$sups['price'] = number_format($sups['price'],0,',','.');
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

	function table_date_hari()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total_volume = 0;
		$total_nilai = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('ppo.supplier_id,prm.measure as measure,ps.nama as name, prm.harga_satuan as price,SUM(prm.volume) as volume, SUM(prm.price) as total_price');
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
		
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
		$this->db->where("prm.material_id in (13,15)");
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_purchase_order ppo');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMatHari($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['date_receipt'] = date('d-m-Y',strtotime($row['date_receipt']));
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['purchase_order_id'] = $row['purchase_order_id'] = $this->crud_global->GetField('pmm_purchase_order',array('id'=>$row['purchase_order_id']),'no_po');
						$arr['volume'] = number_format($row['volume'],2,',','.');
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['name'] = $sups['name'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total_volume += $sups['volume'];
					$total_nilai += $sups['total_price'];
					$sups['no'] =$no;
					$sups['volume'] = number_format($sups['volume'],2,',','.');
					$sups['price'] = number_format($sups['price'],0,',','.');
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
	
	function table_date_sewa_alat()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total_volume = 0;
		$total_nilai = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('ppo.supplier_id,prm.measure as measure,ps.nama as name, prm.harga_satuan as price,SUM(prm.volume) as volume, SUM(prm.price) as total_price');
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
		
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id','left');
		$this->db->where("prm.material_id in (16,17,18,19,20,22)");
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_purchase_order ppo');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMatSewaAlat($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['purchase_order_id'] = $row['purchase_order_id'] = $this->crud_global->GetField('pmm_purchase_order',array('id'=>$row['purchase_order_id']),'no_po');
						$arr['volume'] = number_format($row['volume'],2,',','.');
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['name'] = $sups['name'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total_volume += $sups['volume'];
					$total_nilai += $sups['total_price'];
					$sups['no'] =$no;
					$sups['volume'] = number_format($sups['volume'],2,',','.');
					$sups['price'] = number_format($sups['price'],0,',','.');
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

	function table_date_jasa_angkut()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total_volume = 0;
		$total_nilai = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

		$this->db->select('ppo.supplier_id,prm.measure as measure,ps.nama as name, prm.harga_satuan as price,SUM(prm.volume) as volume, SUM(prm.price) as total_price');
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
		
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
		$this->db->where("prm.material_id in (10)");
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_purchase_order ppo');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMatJasaAngkut($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['purchase_order_id'] = $row['purchase_order_id'] = $this->crud_global->GetField('pmm_purchase_order',array('id'=>$row['purchase_order_id']),'no_po');
						$arr['volume'] = number_format($row['volume'],2,',','.');
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['name'] = $sups['name'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total_volume += $sups['volume'];
					$total_nilai += $sups['total_price'];
					$sups['no'] =$no;
					$sups['volume'] = number_format($sups['volume'],2,',','.');
					$sups['price'] = number_format($sups['price'],0,',','.');
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
	
	function table_date2()
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


		$this->db->select('ps.nama, ppo.supplier_id, SUM(ppo.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppo.date_po >=',$start_date);
            $this->db->where('ppo.date_po <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('pod.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pod.purchase_order_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_purchase_order_detail pod', 'ppo.id = pod.purchase_order_id');
		$this->db->join('penerima ps', 'ppo.supplier_id = ps.id');
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
		$query = $this->db->get('pmm_purchase_order ppo');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat2($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['no_po'] = $row['no_po'];
						$arr['date_po'] = date('d-m-Y',strtotime($row['date_po']));
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['ppn'] = number_format($row['ppn'],0,',','.');
						$arr['jumlah'] = number_format($row['jumlah'],0,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						$arr['status'] = $row['status'];
						
						
						$arr['nama'] = $sups['nama'];
						$jumlah_all += $row['total_price'];
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
	
	function table_date3()
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

		$this->db->select('p.nama_produk, prm.measure as satuan, SUM(prm.volume) as volume, SUM(prm.price) / SUM(prm.volume) as harga_satuan, SUM(prm.price) as total_price');
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
		
		$this->db->join('pmm_purchase_order ppo', 'prm.purchase_order_id = ppo.id','left');
		$this->db->join('produk p','prm.material_id = p.id','left');
		$this->db->where('p.bahanbaku','1');
		$this->db->group_by('p.nama_produk');
		$this->db->order_by('p.nama_produk','asc');
		$query = $this->db->get('pmm_receipt_material prm');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat3($sups['nama_produk'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama'] = $row['nama'];
						$arr['price'] = number_format($row['price'],0,',','.');
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						
						
						$arr['nama_produk'] = $sups['nama_produk'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_price'];
					$sups['volume'] =number_format($sups['volume'],2,',','.');
					$sups['harga_satuan'] =number_format($sups['harga_satuan'],0,',','.');
					$sups['total_price'] =number_format($sups['total_price'],0,',','.');
					$sups['no'] =$no;
					

					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,0,',','.')));	
	}
	
	function table_date4()
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
		$this->db->select('ppp.supplier_id, ps.nama, SUM(ppp.total) as jumlah');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.supplier_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppd.penagihan_pembelian_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'ppp.supplier_id = ps.id');
		$this->db->group_by('ppp.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_pembelian ppp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat4($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['memo'] = $row['memo'];
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['measure'] = $row['measure'];
						$arr['jumlah'] = number_format($row['jumlah'],0,',','.');
						$arr['ppn'] = number_format($row['ppn'],0,',','.');	
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
	
	function table_date5()
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

		$this->db->select('ppp.supplier_id, ps.nama, SUM(ppp.total - ppp.uang_muka) as total_tagihan, SUM((select COALESCE(SUM((total)),0) from pmm_pembayaran_penagihan_pembelian ppm where ppm.penagihan_pembelian_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_pembayaran, SUM(ppp.total - (select COALESCE(SUM((total)),0) from pmm_pembayaran_penagihan_pembelian ppm where ppm.penagihan_pembelian_id = ppp.id and status = "DISETUJUI" and ppm.tanggal_pembayaran >= "'.$start_date.'"  and ppm.tanggal_pembayaran <= "'.$end_date.'")) as total_hutang');
		$this->db->join('pmm_verifikasi_penagihan_pembelian vp', 'ppp.id = vp.penagihan_pembelian_id','left');

		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('vp.tanggal_diterima_proyek >=',$start_date);
            $this->db->where('vp.tanggal_diterima_proyek <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.supplier_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppm.penagihan_pembelian_id',$purchase_order_no);
        }
		
		$this->db->join('penerima ps', 'ppp.supplier_id = ps.id','left');
		$this->db->group_by('ppp.supplier_id');
		$this->db->order_by('ps.nama','asc');
		$query = $this->db->get('pmm_penagihan_pembelian ppp');
		
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat5($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_diterima_proyek'] = date('d-m-Y',strtotime($row['tanggal_diterima_proyek']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['tanggal_jatuh_tempo'] = date('d-m-Y',strtotime($row['tanggal_jatuh_tempo']));
						$arr['memo'] = $row['memo'];
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');	
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');	
						$arr['hutang'] = number_format($row['hutang'],0,',','.');
						
						
						$arr['nama'] = $sups['nama'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_hutang'];
					$sups['no'] =$no;
					$sups['total_tagihan'] = number_format($sups['total_tagihan'],0,',','.');
					$sups['total_pembayaran'] = number_format($sups['total_pembayaran'],0,',','.');
					$sups['total_hutang'] = number_format($sups['total_hutang'],0,',','.');
					

					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,0,',','.')));	
	}
	
	public function umur_hutang($arr_date)
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

		$penagihan_pembelian = $this->db->select('ppp.*, p.nama, ppp.total - (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pm where pm.penagihan_pembelian_id = ppp.id) as total_pembayaran, vpp.tanggal_diterima_proyek as tgl')
		->from('pmm_penagihan_pembelian ppp')
		->join('penerima p','ppp.supplier_id = p.id','left')
		->join('pmm_verifikasi_penagihan_pembelian vpp','ppp.id = vpp.penagihan_pembelian_id','left')
		->where("ppp.status = 'BELUM LUNAS'")
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
			<th class="text-center">TGL. DITERIMA PROYEK</th>
			<th class="text-center">TOTAL</th>
			<th class="text-center">1-30 HARI</th>
			<th class="text-center">31-60 HARI</th>
			<th class="text-center">61-90 HARI</th>
			<th class="text-center">> 90 HARI</th>
		</tr>
		<?php   
		if(!empty($penagihan_pembelian)){
		foreach ($penagihan_pembelian as $key => $x) {
		$dateOne30 = new DateTime($x['tgl']);
		$dateTwo30 = new DateTime($date_now);
		$diff30 = $dateTwo30->diff($dateOne30)->format("%a");

		$dateOne60 = new DateTime($x['tgl']);
		$dateTwo60 = new DateTime($date_now);
		$diff60 = $dateTwo60->diff($dateOne60)->format("%a");

		$dateOne90 = new DateTime($x['tgl']);
		$dateTwo90 = new DateTime($date_now);
		$diff90 = $dateTwo90->diff($dateOne90)->format("%a");

		$dateOne120 = new DateTime($x['tgl']);
		$dateTwo120 = new DateTime($date_now);
		$diff120 = $dateTwo120->diff($dateOne120)->format("%a");
		?>
		<tr class="table-active3">
			<th class="text-center"><?php echo $key + 1;?></th>
			<th class="text-left"><?= $x['nomor_invoice'] ?></th>
			<th class="text-center"><?= date('d-m-Y',strtotime($x['tanggal_invoice'])); ?></th>
			<th class="text-left"><?= $x['nama'] ?></th>
			<th class="text-center"><?= date('d-m-Y',strtotime($x['tgl'])); ?></th>
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
	
	function table_date7()
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
		
		$this->db->select('pmp.supplier_name, SUM(pmp.total) AS total_bayar');
		
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pmp.tanggal_pembayaran >=',$start_date);
            $this->db->where('pmp.tanggal_pembayaran <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.supplier_id',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pmp.penagihan_pembelian_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_penagihan_pembelian ppp', 'pmp.penagihan_pembelian_id = ppp.id','left');
		$this->db->group_by('ppp.supplier_id');
		$this->db->where('pmp.status','DISETUJUI');
		$query = $this->db->get('pmm_pembayaran_penagihan_pembelian pmp');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat7($sups['supplier_name'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['tanggal_pembayaran'] = date('d-m-Y',strtotime($row['tanggal_pembayaran']));
						$arr['nomor_transaksi'] = $row['nomor_transaksi'];
						$arr['tanggal_invoice'] = date('d-m-Y',strtotime($row['tanggal_invoice']));
						$arr['nomor_invoice'] = $row['nomor_invoice'];
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');								
						
						$arr['supplier_name'] = $sups['supplier_name'];
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
	
	function table_date8()
	{
		$data = array();
		$supplier_id = $this->input->post('no_prod');
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
		$this->db->select('pk.no_kalibrasi, pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used,SUM(pphd.use) /  SUM(pphd.duration) as jumlah_capacity');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pph.produksi_harian_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.no_prod');
		$query = $this->db->get('pmm_produksi_harian pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat8($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['jobs_type'] = $row['jobs_type'];
						$arr['no_kalibrasi'] = $row['no_kalibrasi'];
						$arr['date_prod'] = $row['date_prod'];
						$arr['duration'] = $row['duration'];
						$arr['capacity'] = number_format($row['capacity'],2,',','.');
						$arr['used'] = $row['used'];									
						
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['jumlah_capacity'];
					$sups['no'] =$no;
					$sups['jumlah_capacity'] = number_format($sups['jumlah_capacity'],2,',','.');
					$sups['date_prod'] = date('d/m/Y',strtotime($sups['date_prod']));
					

					$data[] = $sups;
					$no++;
					
				}		
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,2,',','.')));		
	}
	
	function table_date8a()
	{
		$data = array();
		$supplier_id = $this->input->post('no_prod');
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
		$this->db->select('pph.date_prod, pph.no_prod, SUM(pphd.duration) as jumlah_duration, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pph.produksi_harian_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.date_prod');
		$query = $this->db->get('pmm_produksi_harian pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat8a($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_a']),'nama_produk');
						$arr['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_b']),'nama_produk');
						$arr['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_c']),'nama_produk');
						$arr['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_d']),'nama_produk');
						$arr['produk_e'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_e']),'nama_produk');
						$arr['measure_a'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_a']),'measure_name');
						$arr['measure_b'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_b']),'measure_name');
						$arr['measure_c'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_c']),'measure_name');
						$arr['measure_d'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_d']),'measure_name');
						$arr['measure_e'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_e']),'measure_name');
						$arr['presentase_a'] = $row['presentase_a'];
						$arr['presentase_b'] = $row['presentase_b'];
						$arr['presentase_c'] = $row['presentase_c'];
						$arr['presentase_d'] = $row['presentase_d'];
						$arr['presentase_e'] = $row['presentase_e'];
						$arr['jumlah_pemakaian_a'] = number_format($row['jumlah_pemakaian_a'],2,',','.');
						$arr['jumlah_pemakaian_b'] = number_format($row['jumlah_pemakaian_b'],2,',','.');
						$arr['jumlah_pemakaian_c'] = number_format($row['jumlah_pemakaian_c'],2,',','.');
						$arr['jumlah_pemakaian_d'] = number_format($row['jumlah_pemakaian_d'],2,',','.');
						$arr['jumlah_pemakaian_e'] = number_format($row['jumlah_pemakaian_e'],2,',','.');						
						
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['jumlah_used'];
					$sups['no'] =$no;
					$sups['jumlah_used'] = number_format($sups['jumlah_used'],2,',','.');
					$sups['date_prod'] = date('d-m-Y',strtotime($sups['date_prod']));
					$sups['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_a']),'nama_produk');
					$sups['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_b']),'nama_produk');
					$sups['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_c']),'nama_produk');
					$sups['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_d']),'nama_produk');
					$sups['produk_e'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_e']),'nama_produk');
					$sups['measure_a'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_a']),'measure_name');
					$sups['measure_b'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_b']),'measure_name');
					$sups['measure_c'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_c']),'measure_name');
					$sups['measure_d'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_d']),'measure_name');
					$sups['measure_e'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_e']),'measure_name');
					$sups['presentase_a'] = $sups['presentase_a'];
					$sups['presentase_b'] = $sups['presentase_b'];
					$sups['presentase_c'] = $sups['presentase_c'];
					$sups['presentase_d'] = $sups['presentase_d'];
					$sups['presentase_e'] = $sups['presentase_e'];
					$sups['jumlah_pemakaian_a'] = number_format($sups['jumlah_pemakaian_a'],2,',','.');
					$sups['jumlah_pemakaian_b'] = number_format($sups['jumlah_pemakaian_b'],2,',','.');
					$sups['jumlah_pemakaian_c'] = number_format($sups['jumlah_pemakaian_c'],2,',','.');
					$sups['jumlah_pemakaian_d'] = number_format($sups['jumlah_pemakaian_d'],2,',','.');
					$sups['jumlah_pemakaian_e'] = number_format($sups['jumlah_pemakaian_e'],2,',','.');
					$sups['date_prod'] = date('d/m/Y',strtotime($sups['date_prod']));
					

					$data[] = $sups;
					$no++;
					
				}		
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,2,',','.')));		
	}
	
	function table_date8b()
	{
		$data = array();
		$supplier_id = $this->input->post('no_prod');
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
		$this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pph.produksi_harian_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$query = $this->db->get('pmm_produksi_harian pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat8b($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_a']),'nama_produk');
						$arr['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_b']),'nama_produk');
						$arr['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_c']),'nama_produk');
						$arr['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_d']),'nama_produk');
						$arr['produk_e'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_e']),'nama_produk');
						$arr['measure_a'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_a']),'measure_name');
						$arr['measure_b'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_b']),'measure_name');
						$arr['measure_c'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_c']),'measure_name');
						$arr['measure_d'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_d']),'measure_name');
						$arr['measure_e'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_e']),'measure_name');
						$arr['presentase_a'] = $row['presentase_a'];
						$arr['presentase_b'] = $row['presentase_b'];
						$arr['presentase_c'] = $row['presentase_c'];
						$arr['presentase_d'] = $row['presentase_d'];
						$arr['presentase_e'] = $row['presentase_e'];
						$arr['jumlah_pemakaian_a'] = number_format($row['jumlah_pemakaian_a'],2,',','.');
						$arr['jumlah_pemakaian_b'] = number_format($row['jumlah_pemakaian_b'],2,',','.');
						$arr['jumlah_pemakaian_c'] = number_format($row['jumlah_pemakaian_c'],2,',','.');
						$arr['jumlah_pemakaian_d'] = number_format($row['jumlah_pemakaian_d'],2,',','.');					
						
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['jumlah_pemakaian_a'] + $sups['jumlah_pemakaian_b'] + $sups['jumlah_pemakaian_c'] + $sups['jumlah_pemakaian_d'] + $sups['jumlah_pemakaian_e'];
					$sups['no'] =$no;
					$sups['jumlah_used'] = number_format($sups['jumlah_used'],2,',','.');
					$sups['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_a']),'nama_produk');
					$sups['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_b']),'nama_produk');
					$sups['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_c']),'nama_produk');
					$sups['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_d']),'nama_produk');
					$sups['produk_e'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_e']),'nama_produk');
					$sups['measure_a'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_a']),'measure_name');
					$sups['measure_b'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_b']),'measure_name');
					$sups['measure_c'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_c']),'measure_name');
					$sups['measure_d'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_d']),'measure_name');
					$sups['measure_e'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_e']),'measure_name');
					$sups['presentase_a'] = $sups['presentase_a'];
					$sups['presentase_b'] = $sups['presentase_b'];
					$sups['presentase_c'] = $sups['presentase_c'];
					$sups['presentase_d'] = $sups['presentase_d'];
					$sups['presentase_e'] = $sups['presentase_e'];
					$sups['jumlah_pemakaian_a'] = number_format($sups['jumlah_pemakaian_a'],2,',','.');
					$sups['jumlah_pemakaian_b'] = number_format($sups['jumlah_pemakaian_b'],2,',','.');
					$sups['jumlah_pemakaian_c'] = number_format($sups['jumlah_pemakaian_c'],2,',','.');
					$sups['jumlah_pemakaian_d'] = number_format($sups['jumlah_pemakaian_d'],2,',','.');
					$sups['jumlah_pemakaian_e'] = number_format($sups['jumlah_pemakaian_e'],2,',','.');
					$sups['jumlah_presentase'] = $sups['jumlah_presentase'];

					$data[] = $sups;
					$no++;
					
				}		
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,2,',','.')));		
	}

	function table_date_campuran()
	{
		$data = array();
		$supplier_id = $this->input->post('no_prod');
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

		$this->db->select('pph.date_prod, pph.no_prod, pk.jobs_type as agregat,  pphd.measure as satuan, SUM(pphd.volume_convert) as volume, (SUM(pphd.volume_convert) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.volume_convert) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.volume_convert) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.volume_convert) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d');

		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pphd.produksi_campuran_id',$purchase_order_no);
        }
		
		$this->db->join('pmm_produksi_campuran_detail pphd', 'pph.id = pphd.produksi_campuran_id','left');
		$this->db->join('pmm_agregat pk', 'pphd.product_id = pk.id','left');
		$this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.date_prod');
		$query = $this->db->get('pmm_produksi_campuran pph');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMatCampuran($sups['no_prod'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_a']),'nama_produk');
						$arr['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_b']),'nama_produk');
						$arr['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_c']),'nama_produk');
						$arr['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$row['produk_d']),'nama_produk');
						$arr['measure_a'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_a']),'measure_name');
						$arr['measure_b'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_b']),'measure_name');
						$arr['measure_c'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_c']),'measure_name');
						$arr['measure_d'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_d']),'measure_name');
						$arr['presentase_a'] = $row['presentase_a'];
						$arr['presentase_b'] = $row['presentase_b'];
						$arr['presentase_c'] = $row['presentase_c'];
						$arr['presentase_d'] = $row['presentase_d'];
						$arr['jumlah_pemakaian_a'] = number_format($row['jumlah_pemakaian_a'],2,',','.');
						$arr['jumlah_pemakaian_b'] = number_format($row['jumlah_pemakaian_b'],2,',','.');
						$arr['jumlah_pemakaian_c'] = number_format($row['jumlah_pemakaian_c'],2,',','.');
						$arr['jumlah_pemakaian_d'] = number_format($row['jumlah_pemakaian_d'],2,',','.');					
						
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$total += $sups['volume'];
					$sups['no'] =$no;
					$sups['volume'] = number_format($sups['volume'],2,',','.');
					$sups['date_prod'] = date('d-m-Y',strtotime($sups['date_prod']));
					$sups['produk_a'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_a']),'nama_produk');
					$sups['produk_b'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_b']),'nama_produk');
					$sups['produk_c'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_c']),'nama_produk');
					$sups['produk_d'] = $this->crud_global->GetField('produk',array('id'=>$sups['produk_d']),'nama_produk');
					$sups['measure_a'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_a']),'measure_name');
					$sups['measure_b'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_b']),'measure_name');
					$sups['measure_c'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_c']),'measure_name');
					$sups['measure_d'] = $this->crud_global->GetField('pmm_measures',array('id'=>$sups['measure_d']),'measure_name');
					$sups['presentase_a'] = $sups['presentase_a'];
					$sups['presentase_b'] = $sups['presentase_b'];
					$sups['presentase_c'] = $sups['presentase_c'];
					$sups['presentase_d'] = $sups['presentase_d'];
					$sups['jumlah_pemakaian_a'] = number_format($sups['jumlah_pemakaian_a'],2,',','.');
					$sups['jumlah_pemakaian_b'] = number_format($sups['jumlah_pemakaian_b'],2,',','.');
					$sups['jumlah_pemakaian_c'] = number_format($sups['jumlah_pemakaian_c'],2,',','.');
					$sups['jumlah_pemakaian_d'] = number_format($sups['jumlah_pemakaian_d'],2,',','.');
					$sups['date_prod'] = date('d/m/Y',strtotime($sups['date_prod']));
					

					$data[] = $sups;
					$no++;
					
				}		
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,2,',','.')));		
	}
	
	function table_date9()
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
		$grand_total_vol_hutang_penerimaan = 0;
		$grand_total_hutang_penerimaan = 0;
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

		$this->db->select('po.id, po.supplier_id, p.nama');

		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('po.date_po >=',$start_date);
            $this->db->where('po.date_po <=',$end_date);
        }

		if(!empty($supplier_id)){
            $this->db->where('p.nama',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.purchase_order_id',$purchase_order_no);
        }
		
        $this->db->join('penerima p', 'p.id = po.supplier_id','left');
		$this->db->where("po.status in ('PUBLISH','CLOSED')");
		$this->db->group_by('po.supplier_id');
		$this->db->order_by('p.nama','ASC');
		$query = $this->db->get('pmm_purchase_order po');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat9($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$vol_hutang_penerimaan = number_format($row['vol_pengiriman'] - $row['vol_tagihan'],2,',','.');
						$hutang_penerimaan = number_format($row['pengiriman'] - $row['tagihan'],0,',','.');
						$vol_sisa_tagihan = number_format($row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$sisa_tagihan = number_format($row['tagihan'] - $row['pembayaran'],0,',','.');
						$vol_akhir = number_format($row['vol_pengiriman'] - $row['vol_tagihan'] + $row['vol_tagihan'] - $row['vol_pembayaran'],2,',','.');
						$akhir = number_format($row['pengiriman'] - $row['tagihan'] + $row['tagihan'] - $row['pembayaran'],0,',','.');
						
						$arr['no'] = $key + 1;
						$arr['date_po'] = date('d-m-Y',strtotime($row['date_po']));
						$arr['no_po'] = $row['no_po'];
						$arr['status'] = $row['status'];
						$arr['vol_pemesanan'] = number_format($row['vol_pemesanan'],2,',','.');
						$arr['pemesanan'] = number_format($row['pemesanan'],0,',','.');
						$arr['vol_pengiriman'] = number_format($row['vol_pengiriman'],2,',','.');
						$arr['pengiriman'] = number_format($row['pengiriman'],0,',','.');
						$arr['vol_tagihan'] = number_format($row['vol_tagihan'],2,',','.');
						$arr['tagihan'] = number_format($row['tagihan'],0,',','.');
						$arr['vol_pembayaran'] = number_format($row['vol_pembayaran'],2,',','.');
						$arr['pembayaran'] = number_format($row['pembayaran'],0,',','.');
						$arr['vol_hutang_penerimaan'] = $vol_hutang_penerimaan;
						$arr['hutang_penerimaan'] = $hutang_penerimaan;
						$arr['vol_sisa_tagihan'] = $vol_sisa_tagihan;
						$arr['sisa_tagihan'] = $sisa_tagihan;
						$arr['vol_akhir'] = $vol_akhir;
						$arr['akhir'] = $akhir;
													
						
						$arr['supplier_id'] = $sups['supplier_id'];
						$grand_total_vol_pemesanan += $row['vol_pemesanan'];
						$grand_total_pemesanan += $row['pemesanan'];
						$grand_total_vol_pengiriman += $row['vol_pengiriman'];
						$grand_total_pengiriman += $row['pengiriman'];
						$grand_total_vol_tagihan += $row['vol_tagihan'];
						$grand_total_tagihan += $row['tagihan'];
						$grand_total_vol_pembayaran += $row['vol_pembayaran'];
						$grand_total_pembayaran += $row['pembayaran'];
						$grand_total_vol_hutang_penerimaan += $row['vol_pengiriman'] - $row['vol_tagihan'];
						$grand_total_hutang_penerimaan += $row['pengiriman'] - $row['tagihan'];
						$grand_total_vol_sisa_tagihan += $row['vol_tagihan'] - $row['vol_pembayaran'];
						$grand_total_sisa_tagihan += $row['tagihan'] - $row['pembayaran'];
						$grand_total_vol_akhir += ($row['vol_pengiriman'] - $row['vol_tagihan']) + ($row['vol_tagihan'] - $row['vol_pembayaran']);
						$grand_total_akhir += ($row['pengiriman'] - $row['tagihan']) + ($row['tagihan'] - $row['pembayaran']); 
						
						$mats[] = $arr;
					}
					
					
					$sups['mats'] = $mats;
					$sups['no'] = $no;		

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
		'grand_total_vol_hutang_penerimaan'=>number_format($grand_total_vol_hutang_penerimaan,2,',','.'),
		'grand_total_hutang_penerimaan'=>number_format($grand_total_hutang_penerimaan,0,',','.'),
		'grand_total_vol_sisa_tagihan'=>number_format($grand_total_vol_sisa_tagihan,2,',','.'),
		'grand_total_sisa_tagihan'=>number_format($grand_total_sisa_tagihan,0,',','.'),
		'grand_total_vol_akhir'=>number_format($grand_total_vol_akhir,2,',','.'),
		'grand_total_akhir'=>number_format($grand_total_akhir,0,',','.')
		));	
	}
	
	function table_date18()
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

		$this->db->select('p.nama_produk, prm.measure, prm.price, SUM(prm.volume) as volume, SUM(prm.volume * prm.price) as total_price');
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('prm.date_receipt >=',$start_date);
            $this->db->where('prm.date_receipt <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('p.nama_produk',$supplier_id);
        }
        if(!empty($filter_material)){
            $this->db->where_in('prm.material_id',$filter_material);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.purchase_order_id',$purchase_order_no);
        }
		
		$this->db->join('produk p','prm.material_id = p.id','left');
		$this->db->where('prm.material_id','15');
		$this->db->group_by('p.nama_produk');
		$this->db->order_by('p.nama_produk','asc');
		$query = $this->db->get('pmm_receipt_material prm');
		
		$no = 1;
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMat18($sups['nama_produk'],$purchase_order_no,$start_date,$end_date,$filter_material);
				
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['nama_produk'] = $row['nama_produk'];
						$arr['date_receipt'] = date('d-m-Y',strtotime($row['date_receipt']));
						$arr['surat_jalan'] = $row['surat_jalan'];
						$arr['price'] = number_format($row['price'],2,',','.');
						$arr['volume'] =  number_format($row['volume'],2,',','.');
						$arr['total_price'] = number_format($row['total_price'],2,',','.');
						
						
						$arr['nama_produk'] = $sups['nama_produk'];
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_price'];
					$sups['no'] =$no;
					

					$data[] = $sups;
					$no++;
				}
				
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,2,',','.')));	
	}
	
	function table_date_()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}



		$this->db->select('id,name');
		if(!empty($supplier_id)){
			$this->db->where('id',$supplier_id);
		}
		$this->db->where('status','PUBLISH');
		$query = $this->db->get('pmm_supplier');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $sups) {

				$materials = $this->pmm_model->GetReceiptMat($sups['id'],$purchase_order_no,$start_date,$end_date);
				foreach ($materials as $key => $row) {
					$get_real = $this->pmm_model->GetRealMat($row['material_id'],$start_date,$end_date,$sups['id'],$purchase_order_no);
					if($get_real['total'] > 0){
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['material_name'] = $row['material_name'];
						
						$arr['real'] = number_format($get_real['total'],2,',','.');
						$arr['total_price'] = number_format($get_real['total_price'],2,',','.');
						
						$total += $get_real['total_price'];
						$arr['name'] = $sups['name'];
						$data[] = $arr;
					}
				}
				
			}
		}

		echo json_encode(array('data'=>$data,'total'=>number_format($total,2,',','.')));	
	}


	function get_po_by_supp()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		//$last_po = 0;
		//$check_last = false;
		$this->db->select('id,no_po,date_po');
		$this->db->from('pmm_purchase_order');
		$this->db->where('supplier_id',$supplier_id);
		$this->db->where('status','PUBLISH');
		$this->db->order_by('date_po','desc');
		$query = $this->db->get()->result_array();
		$data = [];
		//$data[0] = ['id'=>'','text'=>'Pilih PO'];
		if (!empty($query)){
			foreach ($query as $row){
				$data[] = ['id' => $row['id'], 'text' => $row['no_po']];
			}
		}
		/*foreach ($query->result_array() as $key => $value) {
			$materials = $this->db->select('material_id,volume')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$value['id']))->result_array();
			$count_mat = 0;
			$check_mat = 0;

			if(!empty($materials)){
				$count_mat = count($materials);
				foreach ($materials as $mat) {
					$receipt_material = $this->db->select('SUM(volume) as volume')->get_where('pmm_receipt_material',array('purchase_order_id'=>$value['id'],'material_id'=>$mat['material_id']))->row_array();
					if($receipt_material['volume'] >= $mat['volume']){
						$check_mat += 1;
					}
				}
			}

			if($check_mat < $count_mat){

				if($check_last == false){
					$last_po = $value['id'];
					$check_last = true;
				}
				$arr['id'] = $value['id'];
				$arr['text'] = $value['no_po'];
				$arr['date'] = $value['date_po'];
				$arr['check_mat'] = $check_mat;
				$data[]= $arr;
			}
			
		}*/
		//echo json_encode(array('data'=>$data,'last_po'=>$last_po));	
		echo json_encode(array('data'=>$data));
	}
	
	function get_po_by_supp_jasa()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$last_po = 0;
		$check_last = false;
		$this->db->select('id,no_po,date_po');
		$this->db->where('supplier_id',$supplier_id);
		$this->db->where('status','PUBLISH');
		$this->db->order_by('date_po','desc');
		$query = $this->db->get('pmm_purchase_order');
		$data[0]['id'] = '0';
		$data[0]['text'] = 'Pilih PO';
		foreach ($query->result_array() as $key => $value) {
			$materials = $this->db->select('material_id,volume')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$value['id']))->result_array();
			$count_mat = 0;
			$check_mat = 0;

			if(!empty($materials)){
				$count_mat = count($materials);
				foreach ($materials as $mat) {
					$receipt_material = $this->db->select('SUM(volume) as volume')->get_where('pmm_receipt_material',array('purchase_order_id'=>$value['id'],'material_id'=>$mat['material_id']))->row_array();
					if($receipt_material['volume'] >= $mat['volume']){
						$check_mat += 1;
					}
				}
			}

			if($check_mat < $count_mat){

				if($check_last == false){
					$last_po = $value['id'];
					$check_last = true;
				}
				$arr['id'] = $value['id'];
				$arr['text'] = $value['no_po'];
				$arr['date'] = $value['date_po'];
				$arr['check_mat'] = $check_mat;
				$data[]= $arr;
			}
			
		}
		echo json_encode(array('data'=>$data,'last_po'=>$last_po));		
	}
	
	function get_po_by_supp_alat()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$last_po = 0;
		$check_last = false;
		$this->db->select('id,no_po,date_po');
		$this->db->where('supplier_id',$supplier_id);
		$this->db->where('status','PUBLISH');
		$this->db->order_by('date_po','desc');
		$query = $this->db->get('pmm_purchase_order');
		$data[0]['id'] = '0';
		$data[0]['text'] = 'Pilih PO';
		foreach ($query->result_array() as $key => $value) {
			$materials = $this->db->select('material_id,volume')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$value['id']))->result_array();
			$count_mat = 0;
			$check_mat = 0;

			if(!empty($materials)){
				$count_mat = count($materials);
				foreach ($materials as $mat) {
					$receipt_material = $this->db->select('SUM(volume) as volume')->get_where('pmm_receipt_material',array('purchase_order_id'=>$value['id'],'material_id'=>$mat['material_id']))->row_array();
					if($receipt_material['volume'] >= $mat['volume']){
						$check_mat += 1;
					}
				}
			}

			if($check_mat < $count_mat){

				if($check_last == false){
					$last_po = $value['id'];
					$check_last = true;
				}
				$arr['id'] = $value['id'];
				$arr['text'] = $value['no_po'];
				$arr['date'] = $value['date_po'];
				$arr['check_mat'] = $check_mat;
				$data[]= $arr;
			}
			
		}
		echo json_encode(array('data'=>$data,'last_po'=>$last_po));		
	}

	public function edit_data_detail()
	{
		$id = $this->input->post('id');

		$this->db->select('prm.*, ppo.no_po, pm.nama_produk as material, ps.nama as rekanan');
		$this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
		$this->db->join('produk pm','prm.material_id = pm.id','left');
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$data = $this->db->get_where('pmm_receipt_material prm',array('prm.id'=>$id))->row_array();
		$data['date_receipt'] = date('d-m-Y',strtotime($data['date_receipt']));
		$po = $this->db->select('id,no_po')->order_by('date_po','desc')->get_where('pmm_purchase_order',array('supplier_id'=>$data['rekanan']))->result_array();
		
		echo json_encode(array('data'=>$data,'po'=>$po));		
	}


	public function edit_process()
	{
		$output['output'] = false;

		$file = $this->input->post('edit_surat_jalan_file_val');
		$error_file = false;

		$config['upload_path']          = './uploads/surat_jalan_penerimaan/';
        $config['allowed_types']        = 'jpg|png|jpeg|JPG|PNG|JPEG|pdf';

        $this->load->library('upload', $config);

        if(!empty($file)){
        	if($_FILES["edit_surat_jalan_file"]["error"] == 0) {
				if (!$this->upload->do_upload('edit_surat_jalan_file'))
				{
						$error = $this->upload->display_errors();
						$file = $error;
						$error_file = true;
				}else{
						$data = $this->upload->data();
						$file = $data['file_name'];
				}
			}
        }
		

		if($error_file){
			$output['output'] = false;
			$output['err'] = $file;
			echo json_encode($output);
			exit();
		}

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$id = $this->input->post('id_edit');
		$purchase_order_id = $this->input->post('edit_po_val');
		$material_id = $this->input->post('edit_material_val');
		$date_receipt = $this->input->post('edit_date');
		$volume = str_replace('.', '', $this->input->post('edit_volume'));
		$volume = str_replace(',', '.', $volume);
		$display_volume = str_replace('.', '', $this->input->post('edit_display_volume'));
		$display_volume = str_replace(',', '.', $display_volume);
		$measure = $this->input->post('edit_measure');
		$surat_jalan = $this->input->post('edit_surat_jalan');
		$no_kendaraan = $this->input->post('edit_no_kendaraan');
		$driver = $this->input->post('edit_driver');

		$get_po = $this->db->select('measure,price,volume')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$purchase_order_id,'material_id'=>$material_id))->row_array();
		$price = $get_po['price'];

		$select_operation = $this->input->post('edit_select_operation');
		
		$display_measure = 'Ton';
		$convert_value = str_replace('.', '', $this->input->post('edit_convert_value'));
		$convert_value = str_replace(',', '.', $convert_value);
		$display_price = $price;

		$data_p = array(
			'purchase_order_id' => $purchase_order_id,
			'date_receipt' => date('Y-m-d',strtotime($date_receipt)),
			'material_id' => $material_id,
			'measure' => $measure,
			'volume' => $volume,
			'price'	=> $volume * $price,
			'surat_jalan' => $surat_jalan,
			'surat_jalan_file' => $file,
			'no_kendaraan' => $no_kendaraan,
			'driver' => $driver,
			'display_measure' => $display_measure,
			'convert_value' => $convert_value,
			'display_volume' => $display_volume,
			'display_price' => $volume * $price,
			'harga_satuan' => $price,
			'display_harga_satuan' => ($volume * $price) / $display_volume,
		);

		$data_p['updated_on'] = date('Y-m-d H:i:s');
		$data_p['updated_by'] = $this->session->userdata('admin_id');
		
		$this->db->update('pmm_receipt_material',$data_p,array('id'=>$id));
		

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


	function table_acc()
	{
		$data = array();
		$w_date = $this->input->post('filter_date');
		$single_date = $this->input->post('single_date');
		$purchase_order_id = $this->input->post('purchase_order_id');
		$supplier_id = $this->input->post('supplier_id');
		$material_id = $this->input->post('material_id');

		$this->db->select('prm.material_id, SUM(prm.volume) as volume, prm.measure');
		if(!empty($supplier_id)){
			$this->db->where('ppo.supplier_id',$supplier_id);
		}
		
		if(!empty($purchase_order_id)){
			$this->db->where('prm.purchase_order_id',$purchase_order_id);
		}
		if(!empty($material_id) || $material_id != 0){
			$this->db->where('prm.material_id',$material_id);
		}
		
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('prm.date_receipt  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('prm.date_receipt <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
		$this->db->order_by('prm.date_receipt','desc');
		$this->db->order_by('prm.created_on','desc');
		$this->db->group_by('prm.material_id');
		$query = $this->db->get('pmm_receipt_material prm');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['material_name'] = $this->crud_global->GetField('pmm_materials',array('id'=>$row['material_id']),'material_name');
				$row['volume'] = number_format($row['volume'],2,',','.');
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));		
	}

	public function print_pdf()
	{
		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',1); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('L');

		$w_date = $this->input->get('filter_date');
		$purchase_order_id = $this->input->get('purchase_order_id');
		$supplier_id = $this->input->get('supplier_id');
		$material_id = $this->input->get('filter_material');
		$this->db->select('prm.*,ppo.no_po, (prm.price  * prm.volume) as biaya, ppo.supplier_id');
		$this->db->where('ppo.supplier_id',$supplier_id);
		if(!empty($purchase_order_id)){
			$this->db->where('prm.purchase_order_id',$purchase_order_id);
		}
		if(!empty($material_id) || $material_id != 0){
			$this->db->where('prm.material_id',$material_id);
		}
		
		if(!empty($w_date)){
			$arr_date = explode(' - ', $w_date);
			$start_date = $arr_date[0];
			$end_date = $arr_date[1];
			$this->db->where('prm.date_receipt  >=',date('Y-m-d',strtotime($start_date)));	
			$this->db->where('prm.date_receipt <=',date('Y-m-d',strtotime($end_date)));	
		}
		$this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
		$this->db->order_by('prm.date_receipt','asc');
		$this->db->order_by('prm.created_on','asc');
		$query = $this->db->get('pmm_receipt_material prm');

		$supplier_name = '';
		if(!empty($supplier_id)){
			$supplier_name = $this->crud_global->GetField('pmm_supplier',array('id'=>$supplier_id),'name');
		}
		$data['supplier_name'] = $supplier_name;
		$data['data'] = $query->result_array();
        $html = $this->load->view('pmm/receipt_material_print',$data,TRUE);

        
        $pdf->SetTitle($this->input->get('filter_date'));
        $pdf->SetTitle('rekap_surat_jalan_pembelian');
        $pdf->nsi_html($html);
        $pdf->Output('rekap_surat_jalan_pembelian.pdf', 'I');
	
	}


	function table_matuse()
	{
		$data = array();
		$supplier_id = $this->input->post('supplier_id');
		$purchase_order_no = $this->input->post('purchase_order_no');
		$filter_material = $this->input->post('filter_material');
		$start_date = false;
		$end_date = false;
		$total = 0;
		$total_convert = 0;
		$date = $this->input->post('filter_date');
		if(!empty($date)){
			$arr_date = explode(' - ',$date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
		}

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
		$this->db->join('pmm_materials pm','prm.material_id = pm.id','left');
		$this->db->group_by('ppo.supplier_id');
		$this->db->order_by('ps.name','asc');
		$query = $this->db->get('pmm_purchase_order ppo');
		
		if($query->num_rows() > 0){

			foreach ($query->result_array() as $key => $sups) {

				$mats = array();
				$materials = $this->pmm_model->GetReceiptMatUse($sups['supplier_id'],$purchase_order_no,$start_date,$end_date,$filter_material);
				if(!empty($materials)){
					foreach ($materials as $key => $row) {
						$arr['no'] = $key + 1;
						$arr['measure'] = $row['measure'];
						$arr['material_name'] = $row['material_name'];
						
						$arr['real'] = number_format($row['total'],2,',','.');
						$arr['convert_value'] = number_format($row['convert_value'],2,',','.');
						$arr['total_convert'] = number_format($row['total_convert'],2,',','.');
						$arr['total_price'] = number_format($row['total_price'],0,',','.');
						$mats[] = $arr;
					}
					$sups['mats'] = $mats;
					$total += $sups['total_price'];
					$total_convert += $sups['total_convert'];
					$sups['no'] =$no;
					$sups['real'] = number_format($sups['total'],2,',','.');
					$sups['convert_value'] = number_format($sups['convert_value'],2,',','.');
					$sups['total_convert'] = number_format($sups['total_convert'],2,',','.');
					$sups['total_price'] = number_format($sups['total_price'],0,',','.');
					$sups['measure'] = '';
					$data[] = $sups;
					$no++;
				}
				
				
			}
		}
		if(!empty($filter_material)){
			$total_convert = number_format($total_convert,2,',','.');
		}else {
			$total_convert = '';
		}
		echo json_encode(array('data'=>$data,'total'=>number_format($total,0,',','.'),'total_convert'=>$total_convert));	
	}


	function post_cost()
	{
		
	}


	function edit_rm()
	{
		$total = 0;
	}
	

}