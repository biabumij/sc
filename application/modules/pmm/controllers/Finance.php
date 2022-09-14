<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm_model','admin/Templates','pmm_finance'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
	}	


	// Product

	public function table_coa()
	{	
		$data = array();
		$filter_category = $this->input->post('filter_category');


		$this->db->select('c.*, cc.coa_category as coa_category');
		$this->db->join('pmm_coa_category cc','c.coa_category = cc.id','left');
		$this->db->where('c.status','PUBLISH');
		if(!empty($filter_category)){
			$this->db->where('c.coa_category',$filter_category);
		}
		$this->db->order_by('c.coa_number','asc');
		$this->db->group_by('c.id');
		$query = $this->db->get('pmm_coa c');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				$row['saldo'] = 0;
				$row['action'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function delete_akun()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		
		$this->db->delete('pmm_coa',array('id'=>$id));
		$output['output'] = true;
			
		
		echo json_encode($output);
	}

	public function get_coa()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('*')->get_where('pmm_coa',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

	public function table_sales_po(){
		$data = array();
		
		$query = $this->db->query('SELECT * FROM pmm_client INNER JOIN pmm_sales_po ON pmm_sales_po.client_id = pmm_client.id');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				$ppn = $this->pmm_finance->getSalesPoPpn($row['id']);
				$nilai_pekerjaan = $row['total'] - $ppn;
				$row['saldo'] = 0;
				$row['contract_date'] = date('d/m/Y',strtotime($row['contract_date']));
				$row['saldo_bank'] = 0;
				$row['jumlah_total'] =$this->filter->Rupiah($nilai_pekerjaan);
				$row['nomor_link'] = "<a href=".base_url('pmm/finance/dataSalesPO/'.$row["id"]).">".$row["contract_number"]."</a>";
				
				$row['ppn'] = $this->filter->Rupiah($ppn);
				$row['total'] = $this->filter->Rupiah($row['total']);
				$row['action'] = '<a class="btn btn-success" href='.site_url("pmm/finance/dataSalesPO/".$row["id"]).'>Detail</a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function table_penagihan(){
		$data = [];
		$query = $this->db->query('SELECT * FROM pmm_penagihan_penjualan');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = ''
				$row['total_biaya'] = number_format($row['total'],2,',','.');
				$pembayaran = $this->pmm_finance->getTotalPembayaranPenagihanPenjualan($row['id']);
				$row['pembayaran'] = $this->filter->Rupiah($pembayaran);
				$row['sisa_tagihan'] = $this->filter->Rupiah($row['total'] - $pembayaran);
				$row['tanggal_tempo'] = date('d/m/Y',strtotime($row['tanggal_tempo']));
				if($row["status"] === "OPEN"){
					$row["nomor_invoice"] = "<a href=".site_url("pmm/penagihan/detailPenagihan/".$row["id"])." class='text-dark'>".$row["nomor_invoice"]."</a>";
				}elseif($row["status"] === "DRAFT"){
					$row["nomor_invoice"] = "<a href=".site_url("pmm/penagihan/detailPenagihan/".$row["id"])." class='text-dark'>".$row["nomor_invoice"]."</a>";
				}elseif($row["status"] === "REJECT"){
					$row["nomor_invoice"] = "<a>".$row["nomor_invoice"]."</a>";
				}
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function dataSalesPO($id){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['tes'] = '';
			$data['sales_po'] = $this->db->get_where("pmm_sales_po",["id" => $id])->row_array();
			$data['lampiran'] = $this->db->get_where("pmm_lampiran_sales_po",["sales_po_id" => $id])->result_array();
			$data['client']  = $this->db->get_where("pmm_client",["id" => $data['sales_po']['client_id']])->row_array();
			$data['details'] = $this->db->query("SELECT * FROM `pmm_sales_po_detail` INNER JOIN pmm_product ON pmm_sales_po_detail.product_id = pmm_product.id WHERE sales_po_id = '$id'")->result_array();
			$this->load->view('pmm/finance/dataSalesPO',$data);
			
		}else {
			redirect('admin');
		}
	}

	public function approvalSalesPO($id){

		$this->db->set("status","OPEN");
		$this->db->where("id",$id);
		$this->db->update("pmm_sales_po");
		$this->session->set_flashdata('notif_success','Berhasil menyetujui PO');
		redirect("admin/penjualan");
	}

	public function rejectedSalesPO($id){
		$this->db->set("status","REJECT");
		$this->db->where("id",$id);
		$this->db->update("pmm_sales_po");
		$this->session->set_flashdata('notif_success','Berhasil Menolak PO');
		redirect("admin/penjualan");
	}

	public function table_cash_bank()
	{	
		$data = array();

		$this->db->select('c.*');
		$this->db->where('c.coa_category',3);
		// $this->db->where('c.status','PUBLISH');
		$this->db->order_by('c.coa_number','asc');
		$query = $this->db->get('pmm_coa c');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				//$saldo = $this->pmm_finance->GetSaldoKasBank($row['id']);
				//$row['saldo'] = $this->filter->Rupiah($saldo);
				$row['saldo_bank'] = 0;
				$row['hasil_pekerjaan'] = 0;
				$row['action'] = '-';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function table_productions(){
		$data = array();

		$sales_po_id = $this->input->post('sales_po_id');
		$filter_date = $this->input->post('filter_date');


		if(!empty($sales_po_id)){
			$this->db->where('salesPo_id',$sales_po_id);
		}
		if(!empty($filter_date)){
			$arr_date = explode(' - ',$filter_date);
			$start_date = date('Y-m-d',strtotime($arr_date[0]));
			$end_date = date('Y-m-d',strtotime($arr_date[1]));
			$this->db->where('date_production >=',$start_date);
			$this->db->where('date_production <=',$end_date);
		}
		$this->db->order_by('date_production','desc');
		$this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_productions');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				$row['date_production'] = date('d/m/Y',strtotime($row['date_production']));
				$row['product'] = $this->crud_global->GetField('pmm_product',array('id'=>$row['product_id']),'product');
				$row['client'] = $this->crud_global->GetField('pmm_client',array('id'=>$row['client_id']),'client_name');
				$row['contract_number'] = $this->crud_global->GetField('pmm_sales_po',array('id'=>$row['salesPo_id']),'contract_number');
				$row['saldo'] = 0;
				$row['checkbox'] = '';
				$row['hasil_pekerjaan'] = 0;
				$row['status_payment'] = $this->general->StatusPayment($row['status_payment']);
				$row['action'] = '-';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}


	public function sales_add()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['tes'] = '';
			$this->load->view('pmm/finance/sales_add',$data);
			
		}else {
			redirect('admin');
		}
	}


	public function sales_po()
	{	
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['clients'] = $this->db->select('id,client_name')->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
			$data['products'] = $this->db->select('id,product,contract_price')->get_where('pmm_product',array('status'=>'PUBLISH'))->result_array();
			$data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs',array('status'=>'PUBLISH'))->result_array();
			$this->load->view('pmm/finance/sales_po',$data);
			
		}else {
			redirect('admin');
		}
	}

	public function add_material(){
		$no = $this->input->post('no');
		$nama = $this->input->post('nama');
		$materials = $this->db->select('id,material_name,measure')->get_where('pmm_materials',array('status'=>'PUBLISH'))->result_array();
		$measures = $this->db->get_where('pmm_measures',array('status'=>'PUBLISH'))->result_array();
		$taxs = $this->db->select('id,tax_name')->get_where('pmm_taxs',array('status'=>'PUBLISH'))->result_array();
		?>
			<tr>
            <td><?php echo $no;?>.</td>
            <td>
                <select id="product-<?php echo $no;?>" onchange="changeData(<?php echo $no;?>)" class="form-control form-select2" name="product_<?php echo $no;?>">
                    <option value="">.. Pilih Material ..</option>
                    <?php
                    if(!empty($materials)){
                        foreach ($materials as $row) {
                            ?>
                            <option value="<?php echo $row['id'];?>" data-measure-id="<?= $row['measure'];?>" ><?php echo $row['material_name'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="number" min="0" name="qty_<?php echo $no;?>" id="qty-<?php echo $no;?>" onchange="changeData(<?php echo $no;?>)" class="form-control input-sm text-center" />
            </td>
            <td>
                <select id="measure-<?php echo $no;?>"  class="form-control input-sm" name="measure_<?php echo $no;?>">
                    <option value="">.. Pilih Satuan ..</option>
                    <?php
                    if(!empty($measures)){
                        foreach ($measures as $row) {
                            ?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['measure_name'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" name="price_<?php echo $no;?>" id="price-<?php echo $no;?>" class="form-control numberformat tex-left input-sm text-right" />
            </td>
            <td>
                <select id="tax-<?php echo $no;?>" onchange="changeData(<?php echo $no;?>)" class="form-control form-select2" name="tax_<?php echo $no;?>">
                    <option value="">.. Pilih Pajak ..</option>
                    <?php
                    if(!empty($taxs)){
                        foreach ($taxs as $row) {
                            ?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['tax_name'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" name="total_<?php echo $no;?>" id="total-<?php echo $no;?>" class="form-control numberformat tex-left input-sm text-right" readonly="" />
            </td>
        </tr>

        <script type="text/javascript">
        
	        $('.form-select2').select2();
	        $('input.numberformat').number( true, 2,',','.' );

	    </script>
		<?php
	}

	public function add_product_po()
	{
		$no = $this->input->post('no');
		$clients = $this->db->select('id,client_name')->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
		$products = $this->db->select('id,product,contract_price')->get_where('pmm_product',array('status'=>'PUBLISH'))->result_array();
		$taxs = $this->db->select('id,tax_name')->get_where('pmm_taxs',array('status'=>'PUBLISH'))->result_array();
		?>
		<tr>
            <td><?php echo $no;?>.</td>
            <td>
                <select id="product-<?php echo $no;?>" onchange="changeData(<?php echo $no;?>)" class="form-control form-select2" name="product_<?php echo $no;?>">
                    <option value="">.. Pilih Produk ..</option>
                    <?php
                    if(!empty($products)){
                        foreach ($products as $row) {
                            ?>
                            <option value="<?php echo $row['id'];?>" data-price="<?php echo $row['contract_price'];?>"><?php echo $row['product'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="number" min="0" name="qty_<?php echo $no;?>" id="qty-<?php echo $no;?>" onchange="changeData(<?php echo $no;?>)"  class="form-control input-sm text-center" />
            </td>
            <td>
                <input type="text" value="" id="measure-<?= $no;?>" name="measure_<?php echo $no;?>" class="form-control text-center input-sm" readonly />
            </td>
            <td>
                <input type="text" name="price_<?php echo $no;?>" id="price-<?php echo $no;?>" class="form-control numberformat tex-left input-sm text-right" onchange="changeData(<?php echo $no;?>)" />
            </td>
            <td>
                <select id="tax-<?php echo $no;?>" onchange="changeData(<?php echo $no;?>)" class="form-control form-select2" name="tax_<?php echo $no;?>">
                    <option value="">.. Pilih Pajak ..</option>
                    <?php
                    if(!empty($taxs)){
                        foreach ($taxs as $row) {
                            ?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['tax_name'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" name="total_<?php echo $no;?>" id="total-<?php echo $no;?>" class="form-control numberformat tex-left input-sm text-right" readonly="" />
            </td>
            <td><button class="btn btn-xs btn-danger"><i class="fa fa-close"></i></button></td>
        </tr>

        <script type="text/javascript">
        
	        $('.form-select2').select2();
	        $('input.numberformat').number( true, 2,',','.' );

	    </script>
		<?php
	}

	public function submit_sales_po()
	{

		$client_id = $this->input->post('client_id');
		$client_address = $this->input->post('client_address');
		$contract_date = $this->input->post('contract_date');
		$contract_number = $this->input->post('contract_number');
		$jobs_type = $this->input->post('jobs_type');
		$total_product = $this->input->post('total_product');
		$memo = $this->input->post('memo');
		$attach = $this->input->post('attach');
		$sub_total = $this->input->post('sub_total');
		$total = $this->input->post('total');


		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

        $arr_insert = array(
        	'client_id' => $client_id,
        	'client_address' => $client_address,
        	'contract_date' => date('Y-m-d',strtotime($contract_date)),
        	'contract_number' => $contract_number,
        	'memo' => $memo,
        	'jobs_type' => $jobs_type,
        	'attach' => $attach,
        	'total' => $total,
        	'status' => 'DRAFT',
        	'created_by' => $this->session->userdata('admin_id'),
        	'created_on' => date('Y-m-d H:i:s')
        );

        if($this->db->insert('pmm_sales_po',$arr_insert)){
			$sales_po_id = $this->db->insert_id();
			
			$data = [];
            $count = count($_FILES['files']['name']);
            for($i=0;$i<$count;$i++){
        
            if(!empty($_FILES['files']['name'][$i])){
        
                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['files']['size'][$i];
        
                $config['upload_path'] = 'uploads/sales_po'; 
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['file_name'] = $_FILES['files']['name'][$i];
        
                $this->load->library('upload',$config); 
        
                if($this->upload->do_upload('file')){
                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];
        
                $data['totalFiles'][] = $filename;
                
                
                $data[$i] = array(
                    'sales_po_id' => $sales_po_id,
                    'lampiran'  => $data['totalFiles'][$i]
                );

                $this->db->insert('pmm_lampiran_sales_po',$data[$i]);

                }

            }
        
            }

        	for ($i=1; $i <= $total_product ; $i++) { 
        		$product_id = $this->input->post('product_'.$i);
        		$qty = $this->input->post('qty_'.$i);
        		$measure = $this->input->post('measure_'.$i);
        		
				
        		$price = $this->input->post('price_'.$i);
        		$price = str_replace('.', '', $price);
        		$price = str_replace(',', '.', $price);
        		$tax_id = $this->input->post('tax_'.$i);
        		$total_pro = $this->input->post('total_'.$i);
        		$total_pro = str_replace('.', '', $total_pro);
        		$total_pro = str_replace(',', '.', $total_pro);

        		if(!empty($product_id)){

        			$tax = 0;
        			if($tax_id == 3){
        				$tax = ($total_pro * 10) / 100;
        			}

        			if($tax_id == 4){
        				$tax = ($total_pro * 0) / 100;
        			}

        			if($tax_id == 5){
        				$tax = ($total_pro * 2) / 100;
        			}
        			$arr_detail = array(
		        		'sales_po_id' => $sales_po_id,
		        		'product_id' => $product_id,
		        		'qty' => $qty,
		        		'measure' => $measure,
		        		'price' => $price,
		        		'tax_id' => $tax_id,
		        		'tax' => $tax,
		        		'total' => $total_pro
		        	);

		        	$this->db->insert('pmm_sales_po_detail',$arr_detail);
        		}else {
        			redirect('pmm/finance/sales_po');
        			exit();
        		}
        		
        	}
        		
        }
        

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error','Gagal menabuat PO penjualan !!');
            redirect('pmm/finance/sales_po');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil menabuat PO penjualan !!');
            redirect('admin/penjualan');
        }
	}

	public function penawaran_penjualan(){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['supplier'] = $this->db->select('id,client_name,client_address')->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
            $data['products'] = $this->db->select('id,product,contract_price')->get_where('pmm_product',array('status'=>'PUBLISH'))->result_array();
            $data['materials'] = $this->db->get_where('pmm_materials',array('status'=>'PUBLISH'))->result_array();
            $data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs',array('status'=>'PUBLISH'))->result_array();
            $data['measures'] = $this->db->get_where('pmm_measures',array('status'=>'PUBLISH'))->result_array();
			$this->load->view('pmm/finance/penawaran_penjualan',$data);
		}else {
			redirect('admin');
		}
	}

	public function add_product_penjualan(){
		$no = $this->input->post('no');
		$clients = $this->db->select('id,client_name')->get_where('pmm_client',array('status'=>'PUBLISH'))->result_array();
		$products = $this->db->select('id,product,contract_price')->get_where('pmm_product',array('status'=>'PUBLISH'))->result_array();
		$taxs = $this->db->select('id,tax_name')->get_where('pmm_taxs',array('status'=>'PUBLISH'))->result_array();
		$measures = $this->db->get_where('pmm_measures',array('status'=>'PUBLISH'))->result_array();
		?>
		<tr>
            <td><?php echo $no;?>.</td>
            <td>
                <select id="product-<?php echo $no;?>" onchange="changeData(<?php echo $no;?>)" class="form-control form-select2" name="product_<?php echo $no;?>">
                    <option value="">.. Pilih Produk ..</option>
                    <?php
                    if(!empty($products)){
                        foreach ($products as $row) {
                            ?>
                            <option value="<?php echo $row['id'];?>" data-price="<?php echo $row['contract_price'];?>"><?php echo $row['product'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
			<td>
			<input type="text"  name="deskripsi_<?= $no; ?>" id="deskripsi-<?= $no; ?>" class="form-control input-sm" onchange="changeData(<?= $no; ?>)" required="" />   
			</td>
			<td>
				<input type="number" min="0" name="qty_<?= $no; ?>" id="qty-<?= $no; ?>" class="form-control input-sm text-center" onchange="changeData(<?= $no; ?>)" required="" />
			</td>
			<td>
			<select id="measure-<?= $no; ?>" class="form-control input-sm" name="measure_<?= $no; ?>" required="">
					<option value="">.. Pilih Satuan ..</option>
					<?php
					if(!empty($measures)){
						foreach ($measures as $meas) {
							?>
							<option value="<?php echo $meas['id'];?>"><?php echo $meas['measure_name'];?></option>
							<?php
						}
					}
					?>
				</select>
			</td>
			<td>
				<input type="text" name="price_<?= $no; ?>" id="price-<?= $no; ?>"  class="form-control numberformat tex-left input-sm text-right" onchange="changeData(<?= $no; ?>)"/>
			</td>
			<td>
				<select id="tax-<?= $no; ?>" class="form-control form-select2" name="tax_<?= $no; ?>" onchange="changeData(<?= $no; ?>)" required="">
					<option value="">.. Pilih Pajak ..</option>
					<?php
					if(!empty($taxs)){
						foreach ($taxs as $row) {
							?>
							<option value="<?php echo $row['id'];?>"><?php echo $row['tax_name'];?></option>
							<?php
						}
					}
					?>
				</select>
			</td>
			<input type="hidden" name="total_<?php echo $no;?>" id="total-<?php echo $no;?>" class="form-control numberformat tex-left input-sm text-right" readonly="" />

        </tr>

        <script type="text/javascript">

	        $('.form-select2').select2();
	        $('input.numberformat').number( true, 2,',','.' );

	    </script>
		<?php
	}

	public function submit_penawaran_penjualan(){
		
		$client_id = $this->input->post('client_id');
		$client_address = $this->input->post('client_address');
		$contract_date = $this->input->post('contract_date');
		$contract_number = $this->input->post('contract_number');
		$jobs_type = $this->input->post('jobs_type');
		$client_address = $this->input->post('alamat_client');
		$tanggal = $this->input->post('tanggal');
		$site_description = $this->input->post('site_description');
		$perihal = $this->input->post('perihal');
		$total_product = $this->input->post('total_product');
		$memo = $this->input->post('memo');
		$attach = $this->input->post('attach');
		$sub_total = $this->input->post('sub_total');
		$total = $this->input->post('total');

		$nomor = $this->input->post('nomor');

		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

        $arr_insert = array();
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		
		$arr_insert = array(
        	'client_id' => $client_id,
        	'tanggal' => date('Y-m-d',strtotime($tanggal)),
        	'nomor' => $nomor,
        	'perihal' => $perihal,
        	'client_address' => $client_address,
        	// 'contract_date' => date('Y-m-d',strtotime($contract_date)),
        	// 'contract_number' => $contract_number,
        	// 'memo' => $memo,
        	// 'jobs_type' => $jobs_type,
        	// 'attach' => $attach,
        	'persyaratan_harga' => $site_description,
        	'sub_total' => $sub_total,
        	'total' => $total,
        	'status' => 'DRAFT',
        	'created_by' => $this->session->userdata('admin_id'),
        	'created_on' => date('Y-m-d H:i:s')
		);

		if($this->db->insert('pmm_penawaran_penjualan',$arr_insert)){
			$penawaran_penjualan_id = $this->db->insert_id();

        if($this->db->insert('pmm_sales_po',$arr_insert)){
			$sales_po_id = $this->db->insert_id();

			if (!file_exists('uploads/penawaran_penjualan')) {
			    mkdir('uploads/penawaran_penjualan', 0777, true);
			}
			$data = [];
            $count = count($_FILES['files']['name']);
            for($i=0;$i<$count;$i++){
                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['files']['size'][$i];


                $config['upload_path'] = 'uploads/penawaran_penjualan'; 
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['file_name'] = $_FILES['files']['name'][$i];

                $data[$i] = array(
                    'sales_po_id' => $sales_po_id,
                    'lampiran'  => $data['totalFiles'][$i],
                    'penawaran_penjualan_id' => $penawaran_penjualan_id,
                    'lampiran'  			 => $data['totalFiles'][$i]
                );

                $this->db->insert('pmm_lampiran_sales_po',$data[$i]);
                $this->db->insert('pmm_lampiran_penawaran_penjualan',$data[$i]);

                }

            }

		

			for ($i=1; $i <= $total_product ; $i++) { 
        		$product_id = $this->input->post('product_'.$i);
        		$qty = $this->input->post('qty_'.$i);
        		$measure = $this->input->post('measure_'.$i);
        		$deskripsi = $this->input->post('deskripsi_'.$i);

        		$price = $this->input->post('price_'.$i);
        		$price = str_replace('.', '', $price);
        		$price = str_replace(',', '.', $price);
        		$tax_id = $this->input->post('tax_'.$i);
        		$total_pro = $this->input->post('total_'.$i);
        		$total_pro = str_replace('.', '', $total_pro);
        		$total_pro = str_replace(',', '.', $total_pro);

        		if(!empty($product_id)){

        			$tax = 0;
        			if($tax_id == 3){
        				$tax = ($total_pro * 10) / 100;
        			}

        			if($tax_id == 4){
        				$tax = ($total_pro * 0) / 100;
        			}

        			if($tax_id == 5){
        				$tax = ($total_pro * 2) / 100;
        			}
        			$arr_detail = array(
		        		'penawaran_penjualan_id' => $penawaran_penjualan_id,
						'product_id' => $product_id,
						'deskripsi' => $deskripsi,
		        		'qty' => $qty,
		        		'measure' => $measure,
		        		'price' => $price,
		        		'tax_id' => $tax_id,
		        		'tax' => $tax,
		        		'total' => $total_pro
		        	);

		        	$this->db->insert('pmm_penawaran_penjualan_detail',$arr_detail);
        		}else {
        			redirect('admin/penjualan');
        			exit();
        		}

        	}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error','Gagal membuat Penawaran Penjualan !!');
				redirect('pmm/finance/penawaran_penjualan');
			} 
			else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success','Berhasil membuat Penawaran Penjualan !!');
				redirect('admin/penjualan');
			}

		}

	}


	public function table_penawaran(){
		$data = array();

		$this->db->select('pmm_penawaran_penjualan.*,pmm_client.client_name');
		$this->db->join("pmm_client","pmm_penawaran_penjualan.client_id = pmm_client.id");
		$query = $this->db->get("pmm_penawaran_penjualan");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				$row['nomor'] = "<a href=".base_url('pmm/finance/detailPenawaran/'.$row["id"]).">".$row["nomor"]."</a>";
				$row['total'] = $this->filter->Rupiah($row['total']); 
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function approvalPenawaran($id){

		$this->db->set("status","OPEN");
		$this->db->where("id",$id);
		$this->db->update("pmm_penawaran_penjualan");
		$this->session->set_flashdata('notif_success','Berhasil menyetujui Penawaran');
		redirect("admin/penjualan");
	}

	public function rejectedPenawaran($id){
		$this->db->set("status","REJECT");
		$this->db->where("id",$id);
		$this->db->update("pmm_penawaran_penjualan");
		$this->session->set_flashdata('notif_success','Berhasil Menolak Penawaran');
		redirect("admin/penjualan");
	}

	public function hapusPenawaranPenjualan($id){
		$this->db->delete('pmm_penawaran_penjualan', array('id' => $id));  
		$this->session->set_flashdata('notif_success','Berhasil Mengahapus Data Penawaran');
		redirect("admin/penjualan");
	}

	public function detailPenawaran($id){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['tes'] = '';
			$this->db->select('pmm_penawaran_penjualan.*,pmm_client.client_name');
			$this->db->join("pmm_client","pmm_penawaran_penjualan.client_id = pmm_client.id");
			$this->db->where("pmm_penawaran_penjualan.id",$id);
			$data['penawaran'] = $this->db->get("pmm_penawaran_penjualan")->row_array();
			$data['lampiran'] = $this->db->get_where("pmm_lampiran_penawaran_penjualan",["penawaran_penjualan_id" => $id])->result_array();
			$this->db->select("pmm_penawaran_penjualan_detail.*,pmm_product.product");
			$this->db->join("pmm_product","pmm_penawaran_penjualan_detail.product_id = pmm_product.id");
			$this->db->where("penawaran_penjualan_id",$id);
			$data['details'] = $this->db->get("pmm_penawaran_penjualan_detail")->result_array();
			$this->load->view('pmm/finance/detailPenawaran',$data);

		}else {
			redirect('admin');
		}
	}

	public function penagihan_penjualan($id){
		$check = $this->m_admin->check_login();
		if($check == true){		

			$arr_id = explode(',', $id);
			$id_new = array();
			foreach ($arr_id as $key => $row) {
				if(!empty($row)){
					$id_new[] = $row;

					$check_status = $this->crud_global->GetField('pmm_productions',array('id'=>$row),'status_payment');
					if($check_status !== 'UNCREATED'){
						$this->session->set_flashdata('notif_error','Status Payment harus UNCREATED');
						redirect('admin/penjualan');
					}
				}
				
			}
			// print_r($id_new);
			$this->db->where_in('id',$id_new);
			$data['cekSurat'] = $this->db->get('pmm_productions')->result_array();


			$this->db->select('pp.id AS idProduction,
			pp.salesPo_id AS salesPo_id,
			SUM(pp.volume) AS volume,
			pp.product_id AS product_id,
			p.product AS nameProduk,
			sp.price AS hargaProduk,
			pp.no_production,
			pp.product_id');
			$this->db->where_in('pp.id',$id_new);
			$this->db->join('pmm_product p','p.id = pp.product_id','left');
			$this->db->join('pmm_sales_po_detail sp','pp.salesPo_id = sp.sales_po_id','left');
			$this->db->group_by('pp.product_id');
			$data['cekHarga'] = $this->db->get('pmm_productions pp')->result_array();
			// return var_dump($data['cekSurat'][0]);
			$data['id'] = $id;
			$data['id_new'] = $id_new;

			$this->db->where_in('id',$id_new);
			$data['query'] = $this->db->get('pmm_productions')->row_array();

			$data['clients'] = $this->db->select('*')->get_where('pmm_client',array('id'=> $data['query']['client_id']))->row_array();
			$data['produk'] = $this->db->select('id,product,contract_price')->get_where('pmm_product',array('status'=>'PUBLISH'))->result_array();

			$data['sales'] = $this->db->get_where('pmm_sales_po',array('id'=> $data['cekHarga'][0]['salesPo_id']))->row_array();

			$data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs',array('status'=>'PUBLISH'))->result_array();
			$data['noInvoice'] = $this->pmm_finance->NoInvoice();
 			$this->load->view('pmm/finance/penagihan_penjualan',$data);
		
		}else {
			redirect('admin');
		}
	}

	public function submit_penagihan_penjualan(){
		
		$nama_pelanggan = $this->input->post('pelanggan');
		$tanggal_kontrak = $this->input->post('tanggal_kontrak');
		$tanggal_invoice = $this->input->post('tanggal_invoice');
		$syarat_pembayaran = $this->input->post('syarat_pembayaran');
		$jobs_type = $this->input->post('jobs_type');
		$total_product = $this->input->post('total_product');
		$memo = $this->input->post('memo');
		$attach = $this->input->post('attach');
		$sub_total = $this->input->post('sub_total');
		$total = $this->input->post('total');
		$sales_po_id = $this->input->post('sales_po_id');
		$client_id = $this->input->post('client_id');

		$surat_jalan = $this->input->post('surat_jalan');

		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

        $arr_insert = array(
        	'nama_pelanggan' => $nama_pelanggan,
        	'sales_po_id' => $sales_po_id,
        	'client_id' => $client_id,
        	'surat_jalan' => $surat_jalan,
        	'tanggal_kontrak' => date('Y-m-d',strtotime($tanggal_kontrak)),
        	'tanggal_invoice' => date('Y-m-d',strtotime($tanggal_invoice)),
        	'syarat_pembayaran' => $syarat_pembayaran,
        	'alamat_pelanggan' => $this->input->post('alamat_pelanggan'),
			'nomor_kontrak' => $this->input->post('nomor_kontrak'),
			'nomor_invoice' => $this->input->post('nomor_invoice'),
			'tanggal_tempo' => date('Y-m-d',strtotime($this->input->post('tanggal_jatuh_tempo'))),
			'jenis_pekerjaan' => $this->input->post('jenis_pekerjaan'),
			'total' => $total,
			'memo' => $this->input->post('memo'),
        	'status' => 'DRAFT',
        	'created_by' => $this->session->userdata('admin_id'),
        	'created_on' => date('Y-m-d H:i:s')
        );

      
        if($this->db->insert('pmm_penagihan_penjualan',$arr_insert)){
			$tagihan_id = $this->db->insert_id();
			$data = [];
			$count = count($_FILES['files']['name']);
			for($i=0;$i<$count;$i++){
				
				if(!empty($_FILES['files']['name'][$i])){
			
					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];
			
					$config['upload_path'] = 'uploads/penagihan'; 
					$config['allowed_types'] = 'jpg|jpeg|png|pdf|JPG|PDF|JPEG|PNG';
					$config['file_name'] = $_FILES['files']['name'][$i];
			
					$this->load->library('upload',$config); 
			
					if($this->upload->do_upload('file')){
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];
				
						$data['totalFiles'][] = $filename;
						
						
						$data[$i] = array(
							'penagihan_id' => $tagihan_id,
							'lampiran'  => $data['totalFiles'][$i]
						);
		
						$this->db->insert('pmm_lampiran_penagihan',$data[$i]);
	
					}
	
				}
			
			}
			for ($i=1; $i <= $total_product ; $i++) { 
        		$product_id = $_POST["product_id_".$i];
				$qty = $this->input->post('qty_'.$i);
        		$measure = $this->input->post('measure_'.$i);
        		$price = $this->input->post('price_'.$i);
        		$price = str_replace('.', '', $price);
        		$price = str_replace(',', '.', $price);
        		$tax_id = $this->input->post('tax_'.$i);
        		$total_pro = $this->input->post('total_'.$i);
        		$total_pro = str_replace('.', '', $total_pro);
        		$total_pro = str_replace(',', '.', $total_pro);
        		if(!empty($product_id)){

        			$tax = 0;
        			if($tax_id == 3){
        				$tax = ($total_pro * 10) / 100;
        			}

        			if($tax_id == 4){
        				$tax = ($total_pro * 0) / 100;
        			}

        			if($tax_id == 5){
        				$tax = ($total_pro * 2) / 100;
        			}
        			$arr_detail = array(
		        		'penagihan_id' => $tagihan_id,
						'product_id' => $product_id,
		        		'qty' => $qty,
		        		'measure' => $measure,
		        		'price' => $price,
		        		'tax_id' => $tax_id,
		        		'tax' => $tax,
		        		'total' => $total_pro
		        	);

		        	$this->db->insert('pmm_penagihan_penjualan_detail',$arr_detail);

		        	
        		}else {
        			$this->session->set_flashdata('notif_error','Gagal Input Penagihan PO');
					redirect('pmm/finance/penagihan_penjualan/'.$this->input->post('idSurat'));
        			exit();
        		}
        		
        	}

        	$arr_surat_jalan = explode(',', $surat_jalan);
        	if(!empty($arr_surat_jalan)){
        		foreach ($arr_surat_jalan as $sj_id) {
        			$this->db->update('pmm_productions',array('status_payment'=>'CREATING'),array('id'=>$sj_id));
        		}
        	}
		}

		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            redirect('admin/penjualan');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil Input Penagihan PO');
            redirect('admin/penjualan');
        }
    
	}


	public function get_client_address()
	{

		$id = $this->input->post('id');
		if(!empty($id)){
			$query = $this->crud_global->GetField('pmm_client',array('id'=>$id),'client_address');
			echo $query;
		}

	}

	public function get_parent_coa()
	{
		$output = array('output'=>false);
		$id = $this->input->post('id');
		// $array[] = array('id'=>'','text'=>'.. Pilih Parent ..');
		$array = $this->db->select('coa as text,id')->get_where('pmm_coa',array('coa_category'=>$id))->result_array();
		if(!empty($array)){
			$arr = array();
			foreach ($array as $key => $row) {
				$arr[0] = array('id'=>'','text'=>'.. Pilih Parent ..');
				$arr[] = array('id'=>$row['id'],'text'=> $row['text']);
			}
			$output = array('output'=>$arr);
		}
		echo json_encode($output);
	}

	public function form_coa()
	{
		$output['output'] = false;

		$id = $this->input->post('id');
		$coa_category = $this->input->post('coa_category');
		$coa_number = $this->input->post('coa_number');
		$coa = $this->input->post('coa');
		$coa_parent = $this->input->post('coa_parent');
		$status = 'PUBLISH';

		$data = array(
			'coa_category' => $coa_category,
			'coa_number' => $coa_number,
			'coa' => $coa,
			'status' => $status,
			'coa_parent' => $coa_parent,
		);

		if(!empty($id)){
			// $data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_coa',$data,array('id'=>$id))){
				$output['output'] = true;
			}
		}else{
			// $data['created_by'] = $this->session->userdata('admin_id');
			// $data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->insert('pmm_coa',$data)){
				$output['output'] = true;
			}
		}
		
		echo json_encode($output);	
	}


	public function submit_terima_uang(){
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		$jumlah = $this->input->post('jumlah');
		$jumlah = str_replace('.', '', $jumlah);
		$jumlah = str_replace(',', '.', $jumlah);

		$terima_dari = $this->input->post('terima_dari');
		$setor_ke = $this->input->post('setor_ke');
		$nomor_transaksi = $this->input->post('nomor_transaksi');
		$tanggal_transaksi = date('Y-m-d',strtotime($this->input->post('tanggal_transaksi')));

		$arr_insert = array(
        	'terima_dari' => $terima_dari,
			'setor_ke' => $setor_ke,
			'jumlah' => $jumlah,
			'nomor_transaksi' => $nomor_transaksi,
			'tanggal_transaksi' => date('Y-m-d',strtotime($this->input->post('tanggal_transaksi'))),
			'memo' => $this->input->post('memo'),
        	'status' => 'PAID',
        	'created_by' => $this->session->userdata('admin_id'),
        	'created_on' => date('Y-m-d H:i:s')
		);
		
		if($this->db->insert('pmm_terima_uang',$arr_insert)){
			$terima_id = $this->db->insert_id();

			$this->pmm_finance->InsertTransactionsTerima($terima_id,$setor_ke,$jumlah,$tanggal_transaksi);
            $transaction_id = $this->db->insert_id();

			if (!file_exists('uploads/terima_uang')) {
			    mkdir('uploads/terima_uang', 0777, true);
			}

			$data = [];
            $count = count($_FILES['files']['name']);
            for($i=0;$i<$count;$i++){
        
	            if(!empty($_FILES['files']['name'][$i])){
	        
	                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
	                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
	                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
	                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
	                $_FILES['file']['size'] = $_FILES['files']['size'][$i];
	        
	                $config['upload_path'] = 'uploads/terima_uang'; 
	                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
	                $config['file_name'] = $_FILES['files']['name'][$i];
	        
	                $this->load->library('upload',$config); 
	        
	                if($this->upload->do_upload('file')){
	                $uploadData = $this->upload->data();
	                $filename = $uploadData['file_name'];
	        
	                $data['totalFiles'][] = $filename;
	                
	                
	                $data[$i] = array(
	                    'terima_id' 		=> $terima_id,
	                    'lampiran'  		=> $data['totalFiles'][$i]
	                );

	                $this->db->insert('pmm_lampiran_terima',$data[$i]);

	                }

	            }
        
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error','Gagal membuat terima uang !!');
				redirect('pmm/finance/terima_uang');
			} 
			else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success','Berhasil membuat terima uang !!');
				redirect('admin/kas_&_bank');
			}
		}
	}

	public function transfer_uang(){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->db->select('c.*');
			$this->db->where('c.coa_category',3);
			$this->db->where('c.status','PUBLISH');
			$this->db->order_by('c.coa_number','asc'); 
			$data['akun'] = $this->db->get('pmm_coa c')->result_array();
			$this->load->view('pmm/finance/transfer_uang',$data);
		}else {
			redirect('admin');
		}
	}

	public function terima_uang(){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->db->select('c.*');
			$this->db->where('c.coa_category',3);
			$this->db->where('c.status','PUBLISH');
			$this->db->order_by('c.coa_number','asc'); 
			$data['akun'] = $this->db->get('pmm_coa c')->result_array();
			$this->load->view('pmm/finance/terima_uang',$data);
		}else {
			redirect('admin');
		}
	}

	public function submit_transfer(){
		// echo "<pre>";
		// echo print_r($_POST);
		// echo "</pre>";
		// echo "<pre>";
		// echo print_r($_FILES);
		// echo "</pre>";
		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
		$jumlah = $this->input->post('jumlah');
		$jumlah = str_replace('.', '', $jumlah);
		$jumlah = str_replace(',', '.', $jumlah);


		$transfer_dari = $this->input->post('transfer_dari');
		$setor_ke = $this->input->post('setor_ke');
		$nomor_transaksi = $this->input->post('nomor_transaksi');
		$tanggal_transaksi = date('Y-m-d',strtotime($this->input->post('tanggal_transaksi')));
	

		$arr_insert = array(
        	'transfer_dari' => $transfer_dari,
			'setor_ke' => $setor_ke,
			'jumlah' => $jumlah,
			'nomor_transaksi' => $nomor_transaksi,
			'tanggal_transaksi' => date('Y-m-d',strtotime($this->input->post('tanggal_transaksi'))),
			'memo' => $this->input->post('memo'),
        	'status' => 'PAID',
        	'created_by' => $this->session->userdata('admin_id'),
        	'created_on' => date('Y-m-d H:i:s')
		);
		
		if($this->db->insert('pmm_transfer',$arr_insert)){
			$transfer_id = $this->db->insert_id();

			$this->pmm_finance->InsertTransactionsTransfer($transfer_id,$transfer_dari,$jumlah,$tanggal_transaksi);
            $transaction_id = $this->db->insert_id();


			if (!file_exists('uploads/transfer')) {
			    mkdir('uploads/transfer', 0777, true);
			}

			$data = [];
            $count = count($_FILES['files']['name']);
            for($i=0;$i<$count;$i++){
        
	            if(!empty($_FILES['files']['name'][$i])){
	        
	                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
	                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
	                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
	                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
	                $_FILES['file']['size'] = $_FILES['files']['size'][$i];
	        
	                $config['upload_path'] = 'uploads/transfer'; 
	                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
	                $config['file_name'] = $_FILES['files']['name'][$i];
	        
	                $this->load->library('upload',$config); 
	        
	                if($this->upload->do_upload('file')){
	                $uploadData = $this->upload->data();
	                $filename = $uploadData['file_name'];
	        
	                $data['totalFiles'][] = $filename;
	                
	                
	                $data[$i] = array(
	                    'transfer_id' 		=> $transfer_id,
	                    'lampiran'  		=> $data['totalFiles'][$i]
	                );

	                $this->db->insert('pmm_lampiran_transfer',$data[$i]);

	                }

	            }
        
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error','Gagal membuat transfer !!');
				redirect('pmm/finance/transfer_uang');
			} 
			else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success','Berhasil membuat transfer !!');
				redirect('admin/kas_&_bank');
			}
		}
	}


	public function table_transfer(){
		$data = array();

		$this->db->select('pmm_transfer.*');
		$this->db->order_by('tanggal_transaksi','desc');
		$this->db->order_by('id','desc');
		$query = $this->db->get("pmm_transfer");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				$row['nomor'] = "<a href=".base_url('pmm/finance/detailTransfer/'.$row["id"]).">".$row["nomor_transaksi"]."</a>";
				$row['total'] = $this->filter->Rupiah($row['jumlah']);
				$row['tanggal_transaksi'] = date('d-m-Y',strtotime($row["tanggal_transaksi"])); 
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function table_terima(){
		$data = array();

		$this->db->select('pmm_terima_uang.*');
		$this->db->order_by('tanggal_transaksi','desc');
		$this->db->order_by('id','desc');
		$query = $this->db->get("pmm_terima_uang");
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				$row['nomor'] = "<a href=".base_url('pmm/finance/detailTerima/'.$row["id"]).">".$row["nomor_transaksi"]."</a>";
				$row['total'] = $this->filter->Rupiah($row['jumlah']);
				$row['tanggal_transaksi'] = date('d-m-Y',strtotime($row["tanggal_transaksi"])); 
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}

	public function detailTerima($id){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->db->select('c.*');
			$this->db->where('c.coa_category',3);
			$this->db->where('c.status','PUBLISH');
			$this->db->order_by('c.coa_number','asc'); 
			$data['akun'] = $this->db->get('pmm_coa c')->result_array();
			$data['detail'] = $this->db->get_where('pmm_terima_uang',["id" => $id])->row_array();
			$data['dataLampiran'] = $this->db->get_where('pmm_lampiran_terima',["terima_id" => $id])->result_array();
			$this->load->view('pmm/finance/detailTerima',$data);
		}else {
			redirect('admin');
		}
	}

	public function detailTransfer($id){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$this->db->select('c.*');
			$this->db->where('c.coa_category',3);
			$this->db->where('c.status','PUBLISH');
			$this->db->order_by('c.coa_number','asc'); 
			$data['akun'] = $this->db->get('pmm_coa c')->result_array();
			$data['detail'] = $this->db->get_where('pmm_transfer',["id" => $id])->row_array();
			$data['dataLampiran'] = $this->db->get_where('pmm_lampiran_transfer',["transfer_id" => $id])->result_array();
			$this->load->view('pmm/finance/detailTransfer',$data);
		}else {
			redirect('admin');
		}
	}

	public function cetakTransferCoa($id)
	{
		$this->load->library('pdf');
    
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
        $pdf->setHtmlVSpace($tagvs);
        $pdf->AddPage('P');


        $this->db->select('b.*, c.coa as transfer_dari, d.coa as setor_ke');
        $this->db->join('pmm_coa c','b.transfer_dari = c.id','left');
        $this->db->join('pmm_coa d','b.setor_ke = d.id','left');
        $data['detail'] = $this->db->get_where('pmm_transfer b',array('b.id'=>$id))->row_array();
        $html = $this->load->view('pmm/finance/cetakTransferCoa',$data,TRUE);

        
        $pdf->SetTitle('laporan-transfer-uang');
        $pdf->nsi_html($html);
        $pdf->Output('laporan-transfer-uang.pdf', 'I');
	}

	public function cetakTerimaCoa($id)
	{
		$this->load->library('pdf');
    
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
        $pdf->setHtmlVSpace($tagvs);
        $pdf->AddPage('P');


        $this->db->select('b.*, c.coa as terima_dari, d.coa as setor_ke');
        $this->db->join('pmm_coa c','b.terima_dari = c.id','left');
        $this->db->join('pmm_coa d','b.setor_ke = d.id','left');
        $data['detail'] = $this->db->get_where('pmm_terima_uang b',array('b.id'=>$id))->row_array();
        $html = $this->load->view('pmm/finance/cetakTerimaCoa',$data,TRUE);

        
        $pdf->SetTitle('laporan-terima-uang');
        $pdf->nsi_html($html);
        $pdf->Output('laporan-terima-uang.pdf', 'I');
	}


	public function deleteTerimaCoa($id)
    {
        if(!empty($id)){

            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

            $detail = $this->db->get_where('pmm_terima_uang',array('id'=>$id))->row_array();
            $deskripsi = 'Nomor Transaksi '.$detail['nomor_transaksi'];
            $this->pmm_finance->InsertLogs('DELETE','pmm_terima_uang',$id,$deskripsi);

            $this->db->delete('transactions',array('terima_id'=>$id));
            $this->db->delete('pmm_lampiran_terima',array('terima_id'=>$id));
            $this->db->delete('pmm_terima_uang',array('id'=>$id));
            

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                $this->session->set_flashdata('notif_error','Gagal hapus jurnal !!');
                redirect('pmm/jurnal_umum/detailTerima/'.$id);
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                $this->session->set_flashdata('notif_success','Berhasil hapus Terima Bank & Kas !!');
                redirect('admin/kas_&_bank');
            }
        }
    }

    public function deleteTransferCoa($id)
    {
        if(!empty($id)){

            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

            $detail = $this->db->get_where('pmm_transfer',array('id'=>$id))->row_array();
            $deskripsi = 'Nomor Transaksi '.$detail['nomor_transaksi'];
            $this->pmm_finance->InsertLogs('DELETE','pmm_transfer',$id,$deskripsi);

            $this->db->delete('transactions',array('transfer_id'=>$id));
            $this->db->delete('pmm_lampiran_transfer',array('transfer_id'=>$id));
            $this->db->delete('pmm_transfer',array('id'=>$id));
            

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                $this->session->set_flashdata('notif_error','Gagal hapus jurnal !!');
                redirect('pmm/jurnal_umum/detailTransfer/'.$id);
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                $this->session->set_flashdata('notif_success','Berhasil hapus Transfer Bank & Kas !!');
                redirect('admin/kas_&_bank');
            }
        }
    }


	public function export_coa()
	{
// 		$str = file_get_contents('http://localhost/pmm/list_coa.json');
// 		$json = json_decode($str, true);
// 		// print_r($json);
// 		// $meta = stream_context_get_options($ctx)['ssl']['session_meta'];
// // var_dump($meta);
// 		echo count($json['Sheet1']);
// 		foreach ($json['Sheet1'] as $field => $value) {

// 			if($value['NamaKategori'] == 'Kas & Bank'){
// 				$coa_category = 3;
// 			}else if($value['NamaKategori'] == 'Akun Piutang'){
// 				$coa_category = 1;
// 			}else if($value['NamaKategori'] == 'Persediaan'){
// 				$coa_category = 4;
// 			}else if($value['NamaKategori'] == 'Aktiva Lancar Lainnya'){
// 				$coa_category = 2;
// 			}else if($value['NamaKategori'] == 'Aktiva Tetap'){
// 				$coa_category = 5;
// 			}else if($value['NamaKategori'] == 'Depresiasi & Amortisasi'){
// 				$coa_category = 7;
// 			}else if($value['NamaKategori'] == 'Aktiva Lainnya'){
// 				$coa_category = 6;
// 			}else if($value['NamaKategori'] == 'Akun Hutang'){
// 				$coa_category = 8;
// 			}else if($value['NamaKategori'] == 'Kewajiban Lancar Lainnya'){
// 				$coa_category = 10;
// 			}else if($value['NamaKategori'] == 'Ekuitas'){
// 				$coa_category = 12;
// 			}else if($value['NamaKategori'] == 'Pendapatan'){
// 				$coa_category = 13;
// 			}else if($value['NamaKategori'] == 'Harga Pokok Penjualan'){
// 				$coa_category = 15;
// 			}else if($value['NamaKategori'] == 'Beban'){
// 				$coa_category = 16;
// 			}else if($value['NamaKategori'] == 'Pendapatan Lainnya'){
// 				$coa_category = 14;
// 			}else if($value['NamaKategori'] == 'Beban Lainnya'){
// 				$coa_category = 17;
// 			}

// 			$data = array(
// 				'coa_category'=> $coa_category,
// 				'coa' => $value['*NamaAkun'],
// 				'coa_number' => $value['NomorAkun'],
// 				'status' => 'PUBLISH'

// 			);

// 			// $this->db->insert('pmm_coa',$data);
// 			// echo '<pre>';
// 			// print_r($data);
// 			// echo '</pre>';
// 			// echo $value['*NamaAkun'].'<br />';
// 		}
	}


	public function test()
	{

		/*  
	
		43 = 34 5 10
		70 = 36 1 11
		14 = 9 7 6
		72 = 44 1 12
		54 = 14 3 13
		55 = 14 4 14
		44 = 24 3 15
		45 = 24 4 16
		63 = 36 4 17
		9 = 6 5 18
		52 = 28 3 19
		73 = 45 1 20
		74 = 46 1 21

		35 = 14 3 22
		36 = 14 4 23
		46 = 24 8 24
		47 = 24 10 25
		52 = 31 3 26
		53 = 31 4 27
		60 = 35 3 28
		61 = 35 4 29
		12 = 3 10 9
		68 = 41 1 1
		69 = 42 1 30
		71 = 36 1 31
		64 = 3 1 32
		3 = 3 3 4
		4 = 3 4 5
		62 = 36 3 33 
		63 = 36 4 17
		1 = 1 2 2
		71 = 36 1 34
		75 = 47 1 35
		*/

		

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>47,'material_id'=>1,'penawaran_material_id'=>45),array('material_id'=>75));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>3,'material_id'=>3,'penawaran_material_id'=>4),array('material_id'=>3));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>3,'material_id'=>4,'penawaran_material_id'=>5),array('material_id'=>4));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>36,'material_id'=>3,'penawaran_material_id'=>33),array('material_id'=>62));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>36,'material_id'=>4,'penawaran_material_id'=>17),array('material_id'=>63));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>1,'material_id'=>2,'penawaran_material_id'=>2),array('material_id'=>1));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>36,'material_id'=>1,'penawaran_material_id'=>34),array('material_id'=>71));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>34,'material_id'=>5,'penawaran_material_id'=>10),array('material_id'=>43));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>36,'material_id'=>1,'penawaran_material_id'=>11),array('material_id'=>70));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>9,'material_id'=>7,'penawaran_material_id'=>6),array('material_id'=>14));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>44,'material_id'=>1,'penawaran_material_id'=>12),array('material_id'=>72));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>14,'material_id'=>3,'penawaran_material_id'=>13),array('material_id'=>54));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>14,'material_id'=>4,'penawaran_material_id'=>14),array('material_id'=>55));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>24,'material_id'=>3,'penawaran_material_id'=>15),array('material_id'=>44));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>24,'material_id'=>4,'penawaran_material_id'=>16),array('material_id'=>45));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>36,'material_id'=>4,'penawaran_material_id'=>17),array('material_id'=>63));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>6,'material_id'=>5,'penawaran_material_id'=>18),array('material_id'=>9));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>28,'material_id'=>3,'penawaran_material_id'=>19),array('material_id'=>52));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>45,'material_id'=>3,'penawaran_material_id'=>22),array('material_id'=>73));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>46,'material_id'=>1,'penawaran_material_id'=>21),array('material_id'=>74));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>14,'material_id'=>1,'penawaran_material_id'=>22),array('material_id'=>35));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>14,'material_id'=>4,'penawaran_material_id'=>23),array('material_id'=>36));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>24,'material_id'=>8,'penawaran_material_id'=>24),array('material_id'=>46));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>24,'material_id'=>10,'penawaran_material_id'=>25),array('material_id'=>47));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>31,'material_id'=>3,'penawaran_material_id'=>26),array('material_id'=>52));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>31,'material_id'=>4,'penawaran_material_id'=>27),array('material_id'=>53));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>35,'material_id'=>3,'penawaran_material_id'=>28),array('material_id'=>60));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>35,'material_id'=>4,'penawaran_material_id'=>29),array('material_id'=>61));


		$this->db->update('pmm_remaining_materials',array('supplier_id'=>3,'material_id'=>10,'penawaran_material_id'=>9),array('material_id'=>12));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>41,'material_id'=>1,'penawaran_material_id'=>1),array('material_id'=>68));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>42,'material_id'=>1,'penawaran_material_id'=>30),array('material_id'=>69));
		$this->db->update('pmm_remaining_materials',array('supplier_id'=>36,'material_id'=>1,'penawaran_material_id'=>31),array('material_id'=>70));

		$this->db->update('pmm_remaining_materials',array('supplier_id'=>3,'material_id'=>1,'penawaran_material_id'=>32),array('material_id'=>64));

	}



}