<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends Secure_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('admin/m_admin', 'crud_global', 'm_themes', 'pages/m_pages', 'menu/m_menu', 'admin_access/m_admin_access', 'DB_model', 'member_back/m_member_back', 'm_member', 'pmm/pmm_model', 'admin/Templates', 'pmm/pmm_finance'));
		$this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
	}

	

	public function table_penawaran()
	{
		$data = array();
		

		$this->db->select('pmm_penawaran_penjualan.*,penerima.nama');
		$this->db->join("penerima", "pmm_penawaran_penjualan.client_id = penerima.id");
		$this->db->order_by('tanggal', 'DESC');
		$this->db->order_by('created_on', 'DESC');
		$query = $this->db->get("pmm_penawaran_penjualan");
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key + 1;
				// $row['coa'] = '' 
				$row['tanggal'] = date('d/m/Y', strtotime($row['tanggal']));
				$row['nomor'] = "<a href=" . base_url('penjualan/detailPenawaran/' . $row["id"]) . ">" . $row["nomor"] . "</a>";
				$row['total'] = number_format($row['total'],0,',','.');
				$row['status'] = $this->pmm_model->GetStatus2($row['status']);
				$data[] = $row;
			}
		}
		echo json_encode(array('data' => $data));
	}

	public function penawaran_penjualan()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['supplier'] = $this->db->select('nama,id,alamat')->get_where('penerima', array('status' => 'PUBLISH', 'pelanggan' => 1))->result_array();
			$data['products'] =  $this->db->select('*')
			->from('produk p')
			->where("p.status = 'PUBLISH'")
			->where("p.jual = 1 ")
			->order_by('nama_produk','asc')
			->get()->result_array();
			$data['materials'] = $this->db->get_where('pmm_materials', array('status' => 'PUBLISH'))->result_array();
			$data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
			$data['measures'] = $this->db->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
			$this->load->view('penjualan/penawaran_penjualan', $data);
		} else {
			redirect('admin');
		}
	}

	public function add_product_penjualan()
	{
		$no = $this->input->post('no');
		$products = $this->db->select('*')
		->from('produk p')
		->where("p.status = 'PUBLISH'")
		->where("p.jual = 1 ")
		->order_by('nama_produk','asc')
		->get()->result_array();
		$taxs = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
		$measures = $this->db->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
	?>
		<tr>
			<td><?php echo $no; ?>.</td>
			<td>
				<select id="product-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control form-select2" name="product_<?php echo $no; ?>">
					<option value="">Pilih Produk</option>
					<?php
					if (!empty($products)) {
						foreach ($products as $row) {
					?>
							<option value="<?php echo $row['id'];?>" data-price="<?php echo $row['harga_jual'];?>"><?php echo $row['nama_produk'];?></option>
					<?php
						}
					}
					?>
				</select>
			</td>
			<td>
				<input type="number" min="0" name="qty_<?= $no; ?>" id="qty-<?= $no; ?>" class="form-control input-sm text-center" onchange="changeData(<?= $no; ?>)" required="" />
			</td>
			<td>
				<select id="measure-<?= $no; ?>" class="form-control form-select2" name="measure_<?= $no; ?>">
					<option value="">Pilih Satuan</option>
					<?php
					if (!empty($measures)) {
						foreach ($measures as $meas) {
					?>
							<option value="<?php echo $meas['id']; ?>"><?php echo $meas['measure_name']; ?></option>
					<?php
						}
					}
					?>
				</select>
			</td>
			<td>
				<input type="text" name="price_<?= $no; ?>" id="price-<?= $no; ?>" class="form-control numberformat input-sm text-right" onchange="changeData(<?= $no; ?>)" />
			</td>
			<td>
				<select id="tax-<?= $no; ?>" class="form-control form-select2" name="tax_<?= $no; ?>" onchange="changeData(<?= $no; ?>)" required="">
					<option value="">Pilih Pajak</option>
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
			    <input type="hidden" name="total_<?php echo $no; ?>" id="total-<?php echo $no; ?>" class="form-control numberformat" readonly="" />

		</tr>

		<script type="text/javascript">
			$('.form-select2').select2();
			$('input.numberformat').number(true, 2, ',', '.');
		</script>
	<?php
	}

	public function submit_penawaran_penjualan()
	{
		$client_id = $this->input->post('client_id');
		$client_address = $this->input->post('alamat_client');
		$tanggal = $this->input->post('tanggal');
		$site_description = $this->input->post('site_description');
		$perihal = $this->input->post('perihal');
		$syarat_pembayaran = $this->input->post('syarat_pembayaran');
		$total_product = $this->input->post('total_product');
		$sub_total = $this->input->post('sub_total');
		$total = $this->input->post('total');
		$nomor = $this->input->post('nomor');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'client_id' => $client_id,
			'tanggal' => date('Y-m-d', strtotime($tanggal)),
			'nomor' => $nomor,
			'perihal' => $perihal,
			'client_address' => $client_address,
			'persyaratan_harga' => $site_description,
			'syarat_pembayaran' => $syarat_pembayaran,
			'sub_total' => $sub_total,
			'total' => $total,
			'status' => 'DRAFT',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('pmm_penawaran_penjualan', $arr_insert)) {
			$penawaran_penjualan_id = $this->db->insert_id();

			if (!file_exists('uploads/penawaran_penjualan')) {
			    mkdir('uploads/penawaran_penjualan', 0777, true);
			}

			$data = [];
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				if (!empty($_FILES['files']['name'][$i])) {

					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					$config['upload_path'] = 'uploads/penawaran_penjualan';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'penawaran_penjualan_id' => $penawaran_penjualan_id,
							'lampiran'  			 => $data['totalFiles'][$i]
						);

						$this->db->insert('pmm_lampiran_penawaran_penjualan', $data[$i]);
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

				if (!empty($product_id)) {

					$tax = 0;
					if ($tax_id == 3) {
						$tax = ($total_pro * 10) / 100;
					}

					if ($tax_id == 4) {
						$tax = ($total_pro * 0) / 100;
					}

					if ($tax_id == 5) {
						$tax = ($total_pro * 2) / 100;
					}
					if ($tax_id == 6) {
						$tax = ($total_pro * 11) / 100;
					}
					$arr_detail = array(
						'penawaran_penjualan_id' => $penawaran_penjualan_id,
						'product_id' => $product_id,
		        		'qty' => $qty,
		        		'measure' => $measure,
		        		'price' => $price,
		        		'tax_id' => $tax_id,
		        		'tax' => $tax,
		        		'total' => $total_pro
					);

					$this->db->insert('pmm_penawaran_penjualan_detail', $arr_detail);
				} else {
					redirect('admin/penjualan');
					exit();
				}
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error', 'Gagal Menambahkan Penawaran Penjualan !!');
				redirect('penjualan/penawaran_penjualan');
			} else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Penawaran Penjualan !!');
				redirect('admin/penjualan');
			}
		}
	}

	public function detailPenawaran($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['tes'] = '';
			$this->db->select('pmm_penawaran_penjualan.*,penerima.nama');
			$this->db->join("penerima", "pmm_penawaran_penjualan.client_id = penerima.id");
			$this->db->where("pmm_penawaran_penjualan.id", $id);
			$data['penawaran'] = $this->db->get("pmm_penawaran_penjualan")->row_array();
			$data['lampiran'] = $this->db->get_where("pmm_lampiran_penawaran_penjualan", ["penawaran_penjualan_id" => $id])->result_array();
			$this->db->select("pmm_penawaran_penjualan_detail.*,produk.nama_produk");
			$this->db->join("produk", "pmm_penawaran_penjualan_detail.product_id = produk.id");
			$this->db->where("penawaran_penjualan_id", $id);
			$data['details'] = $this->db->get("pmm_penawaran_penjualan_detail")->result_array();
			$this->load->view('penjualan/detailPenawaran', $data);
		} else {
			redirect('admin');
		}
	}

	public function approvalPenawaran($id)
	{

		$this->db->set("status", "OPEN");
		$this->db->where("id", $id);
		$this->db->update("pmm_penawaran_penjualan");
		$this->session->set_flashdata('notif_success', 'Berhasil Menyetujui Penawaran');
		redirect("admin/penjualan");
	}

	public function rejectedPenawaran($id)
	{
		$this->db->set("status", "REJECT");
		$this->db->where("id", $id);
		$this->db->update("pmm_penawaran_penjualan");
		$this->session->set_flashdata('notif_success', 'Berhasil Menolak Penawaran');
		redirect("admin/penjualan");
	}

	public function hapusPenawaranPenjualan($id)
	{
		$this->db->delete('pmm_penawaran_penjualan', array('id' => $id));
		$this->db->delete('pmm_penawaran_penjualan_detail', array('penawaran_penjualan_id' => $id));
		$this->session->set_flashdata('notif_success', 'Berhasil Mengahapus Penawaran Penjualan');
		redirect("admin/penjualan");
	}


	public function get_client_address()
	{

		$id = $this->input->post('id');
		if (!empty($id)) {
			$query = $this->crud_global->GetField('penerima', array('id' => $id), 'alamat');
			echo $query;
		}
	}
	
	public function cetak_penawaran_penjualan($id){

		$this->load->library('pdf');


		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$this->db->select('pmm_penawaran_penjualan.*,penerima.nama as client_name');
		$this->db->join("penerima","pmm_penawaran_penjualan.client_id = penerima.id");
		$this->db->where('pmm_penawaran_penjualan.id',$id);
		$data['row'] = $this->db->get("pmm_penawaran_penjualan")->row_array();
		$row = $this->db->get_where('pmm_penawaran_penjualan',array('id'=>$id))->row_array();


		$this->db->select('pmm_penawaran_penjualan_detail.*,produk.nama_produk as product');
		$this->db->join('produk',"pmm_penawaran_penjualan_detail.product_id = produk.id");
		$this->db->where('penawaran_penjualan_id',$id);
		$data['data'] = $this->db->get("pmm_penawaran_penjualan_detail")->result_array();
        $html = $this->load->view('penjualan/cetak_penawaran_penjualan',$data,TRUE);


        $pdf->SetTitle($row['nomor']);
        $pdf->nsi_html($html);
        $pdf->Output($row['nomor'].'.pdf', 'I');
	}
	
	public function table_sales_po()
	{
		$data = array();

		$this->db->select('ps.*, p.nama as client_name');
		$this->db->join('penerima p', 'ps.client_id = p.id', 'left');
		$this->db->order_by('contract_date', 'DESC');
		$this->db->order_by('created_on', 'DESC');
		$query = $this->db->get('pmm_sales_po ps');
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key + 1;
				$ppn = $this->pmm_finance->getSalesPoPpn($row['id']);
				$nilai_pekerjaan = $row['total'] - $ppn;
				$row['saldo'] = 0;
				$row['contract_date'] = date('d/m/Y', strtotime($row['contract_date']));
				$row['nomor_link'] = "<a href=" . base_url('penjualan/dataSalesPO/' . $row["id"]) . ">" . $row["contract_number"] . "</a>";
				$row['action'] = '<a class="btn btn-success" href=' . site_url("penjualan/dataSalesPO/" . $row["id"]) . '>Detail</a>';
				
				$total_volume = $this->db->select('SUM(qty) as total,measure,SUM(qty *price) as total_tanpa_ppn')->get_where('pmm_sales_po_detail',array('sales_po_id'=>$row['id']))->row_array();
				$row['qty'] = number_format($total_volume['total'],2,',','.');
				$row['jumlah_total'] = number_format($total_volume['total_tanpa_ppn'],0,',','.');
				
				$receipt = $this->db->select('SUM(volume) as volume')->get_where('pmm_productions',array('salesPo_id'=>$row['id'],'status'=>'PUBLISH'))->row_array();
				$row['receipt'] = '<a href="'.site_url('pmm/purchase_order/production_material_pdf/'.$row['id']).'" target="_blank" >'.number_format($receipt['volume'],2,',','.').'</a>';
								
				$total_receipt = $this->db->select('SUM(price) as price')->get_where('pmm_productions',array('salesPo_id'=>$row['id'],'status'=>'PUBLISH'))->row_array();
				$row['total_receipt'] = number_format($total_receipt['price'],0,',','.');
				$row['status'] = $this->pmm_model->GetStatus2($row['status']);
				
				  
				$data[] = $row;
			}
		}
		echo json_encode(array('data' => $data));
	}

	public function alert_sales_po_total(){
	
		$id = $this->input->post('id');
	
		$this->db->select('p.nama_produk, pod.product_id, pod.measure, pod.tax_id, pod.qty as volume');
		if(!empty($client_id)){
			$this->db->where('po.client_id',$client_id);
		}
		if(!empty($id)){
			$this->db->where('po.id',$id);
		}
		$this->db->where('po.status','OPEN');
		$this->db->join('produk p','pod.product_id = p.id','left');
		$this->db->join('pmm_sales_po po','pod.sales_po_id = po.id','left');
		$this->db->group_by('pod.product_id');
		$this->db->order_by('p.nama_produk','asc');
		$query = $this->db->get('pmm_sales_po_detail pod');
		$data[0] = array('id'=>'0','text'=>'Pilih Produk');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$arr['id'] = $row['product_id'];
				$arr['text'] = $row['nama_produk'];
				$arr_alert['nama_produk'] = $row['nama_produk'];
				$arr['tax_id'] = $row['tax_id'];
				$arr['measure'] = $row['measure'];
				$arr_alert['volume'] = number_format($row['volume'],2,',','.');
				$pengiriman = $this->db->select('SUM(volume) as volume')->get_where('pmm_productions',array('salesPo_id'=>$id,'product_id'=>$row['product_id']))->row_array();
				$arr_alert['pengiriman'] = number_format($pengiriman['volume'],2,',','.');
				$data[] = $arr;
				$data2[] = $arr_alert;
				
			}
	
		}
			
	
		echo json_encode(array('data' => $data, 'data2' => $data2));
	}

	public function alert_sales_po_total_retur(){
	
		$id = $this->input->post('id');
	
		$this->db->select('p.nama_produk, pod.product_id, pod.measure, pod.tax_id, pod.qty as volume');
		if(!empty($client_id)){
			$this->db->where('po.client_id',$client_id);
		}
		if(!empty($id)){
			$this->db->where('po.id',$id);
		}
		$this->db->where('po.status','OPEN');
		$this->db->join('produk p','pod.product_id = p.id','left');
		$this->db->join('pmm_sales_po po','pod.sales_po_id = po.id','left');
		$this->db->group_by('pod.product_id');
		$this->db->order_by('p.nama_produk','asc');
		$query = $this->db->get('pmm_sales_po_detail pod');
		$data[0] = array('id'=>'0','text'=>'Pilih Produk');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$arr['id'] = $row['product_id'];
				$arr['text'] = $row['nama_produk'];
				$arr_alert['nama_produk'] = $row['nama_produk'];
				$arr['tax_id'] = $row['tax_id'];
				$arr['measure'] = $row['measure'];
				$arr_alert['volume'] = number_format($row['volume'],2,',','.');
				$pengiriman = $this->db->select('SUM(volume) as volume')->get_where('pmm_productions_retur',array('salesPo_id'=>$id,'product_id'=>$row['product_id']))->row_array();
				$arr_alert['pengiriman'] = number_format($pengiriman['volume'],2,',','.');
				$data[] = $arr;
				$data2[] = $arr_alert;
				
			}
	
		}
			
	
		echo json_encode(array('data' => $data, 'data2' => $data2));
	}
	

	public function sales_po()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$get_data = $this->db->get_where('pmm_penawaran_penjualan',array('status' =>'OPEN'))->row_array();
			$data['clients'] = $this->db->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'pelanggan' => 1))->result_array();
			$data['products'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
			$data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
			$data['measures'] = $this->db->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
			$data['penawaran'] = $this->pmm_model->getMatByPenawaranPenjualan($get_data['client_id']);
			$this->load->view('penjualan/sales_po', $data);
		} else {
			redirect('admin');
		}
	}
	
	public function add_product_po()
	{
		$no = $this->input->post('no');
		$products = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
		$taxs = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
		$measures = $this->db->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
		$get_data = $this->db->get_where('pmm_penawaran_penjualan',array('status' =>'OPEN'))->row_array();
		$penawaran = $this->pmm_model->getMatByPenawaranPenjualan($get_data['client_id']);
	?>
		<tr>
			<td><?php echo $no; ?>.</td>
			<td>
				<select name="penawaran_<?php echo $no; ?>" id="penawaran-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control form-control form-select2" required="">
					<option value="">Pilih Penawaran</option>
					<?php
					if(!empty($penawaran)){
						foreach ($penawaran as $row) {
							?>
							<option value="<?php echo $row['penawaran_id'];?>" data-product="<?= $row['product_id'];?>" data-satuan="<?= $row['satuan'];?>" data-harga="<?php echo $row['harga'];?>" data-tax="<?php echo $row['tax'];?>" data-nama_produk="<?= $row['nama_produk'];?>" data-satuan="<?= $row['satuan'];?>"><?php echo $row['nomor'];?> (<?php echo number_format($row['harga'],0,',','.');?>)</option>
							<?php
						}
					}
					?>
				</select>
			</td>	
				<input type="hidden" name="product_<?php echo $no; ?>" id="product-<?php echo $no; ?>" class="form-control input-sm text-center" onchange="changeData(<?php echo $no; ?>)" readonly=""/>
			<td>
				<input type="text" name="nama_produk_<?php echo $no; ?>" id="nama_produk-<?php echo $no; ?>" class="form-control input-sm text-center" onchange="changeData(<?php echo $no; ?>)" required="" readonly=""/>
			</td>
			<td>
				<input type="text" min="0" name="qty_<?php echo $no; ?>" id="qty-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control numberformat input-sm text-center" />
			</td>
			<td>
				<input type="text" name="measure_<?php echo $no; ?>" id="measure-<?php echo $no; ?>" class="form-control input-sm text-center" onchange="changeData(<?php echo $no; ?>)" required="" readonly=""/>
			</td>
			<td>
				<input type="text" name="price_<?php echo $no; ?>" id="price-<?php echo $no; ?>" class="form-control numberformat tex-left input-sm text-right" onchange="changeData(<?php echo $no; ?>)" readonly="" />
			</td>
			<td>
				<input type="text" name="total_<?php echo $no; ?>" id="total-<?php echo $no; ?>" class="form-control numberformat tex-left input-sm text-right" onchange="changeData(<?php echo $no; ?>)" readonly="" />
			</td>
				<input type="hidden" name="tax_<?php echo $no; ?>" id="tax-<?php echo $no; ?>" class="form-control tex-left input-sm text-right" onchange="changeData(<?php echo $no; ?>)" readonly="" />
		</tr>

		<script type="text/javascript">
			$('.form-select2').select2();
			$('input.numberformat').number(true, 2, ',', '.');
		</script>
<?php
	}



	public function dataSalesPO($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['tes'] = '';
			$data['sales_po'] = $this->db->get_where("pmm_sales_po", ["id" => $id])->row_array();
			$data['lampiran'] = $this->db->get_where("pmm_lampiran_sales_po", ["sales_po_id" => $id])->result_array();
			$data['client']  = $this->db->get_where("penerima", ["id" => $data['sales_po']['client_id']])->row_array();
			$data['details'] = $this->db->query("SELECT * FROM `pmm_sales_po_detail` INNER JOIN produk ON pmm_sales_po_detail.product_id = produk.id WHERE sales_po_id = '$id'")->result_array();
			$this->load->view('penjualan/dataSalesPO', $data);
		} else {
			redirect('admin');
		}
	}

	public function approvalSalesPO($id)
	{

		$this->db->set("status", "OPEN");
		$this->db->where("id", $id);
		$this->db->update("pmm_sales_po");
		$this->session->set_flashdata('notif_success', 'Berhasil Menyetujui Sales Order');
		redirect("admin/penjualan");
	}

	public function rejectedSalesPO($id)
	{
		$this->db->set("status", "REJECT");
		$this->db->where("id", $id);
		$this->db->update("pmm_sales_po");
		$this->session->set_flashdata('notif_success', 'Berhasil Menolak Sales Order');
		redirect("admin/penjualan");
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
		$attach = $this->input->post('files[]');
		$sub_total = $this->input->post('sub_total');
		$total = $this->input->post('total');


		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'client_id' => $client_id,
			'client_address' => $client_address,
			'contract_date' => date('Y-m-d', strtotime($contract_date)),
			'contract_number' => $contract_number,
			'memo' => $memo,
			'jobs_type' => $jobs_type,
			'attach' => $attach,
			'total' => $total,
			'status' => 'DRAFT',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('pmm_sales_po', $arr_insert)) {
			$sales_po_id = $this->db->insert_id();

			if (!file_exists('uploads/sales_po')) {
			    mkdir('uploads/sales_po', 0777, true);
			}

			$data = [];
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				if (!empty($_FILES['files']['name'][$i])) {

					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					$config['upload_path'] = 'uploads/sales_po';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'sales_po_id' => $sales_po_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('pmm_lampiran_sales_po', $data[$i]);
					}
				}
			}

			for ($i = 1; $i <= $total_product; $i++) {
				$penawaran_id = $this->input->post('penawaran_' . $i);
				$product_id = $this->input->post('product_' . $i);
				$qty = $this->input->post('qty_' . $i);
				$qty = str_replace('.', '', $qty);
				$qty = str_replace(',', '.', $qty);
				$measure = $this->input->post('measure_' . $i);
				$price = $this->input->post('price_' . $i);
				$price = str_replace('.', '', $price);
				$price = str_replace(',', '.', $price);
				$tax_id = $this->input->post('tax_' . $i);
				$total_pro = $this->input->post('total_' . $i);
				$total_pro = str_replace('.', '', $total_pro);
				$total_pro = str_replace(',', '.', $total_pro);

				if (!empty($penawaran_id)) {

					$tax = 0;
					if ($tax_id == 3) {
						$tax = ($total_pro * 10) / 100;
					}

					if ($tax_id == 4) {
						$tax = ($total_pro * 0) / 100;
					}

					if ($tax_id == 5) {
						$tax = ($total_pro * 2) / 100;
					}

					if ($tax_id == 6) {
						$tax = ($total_pro * 11) / 100;
					}
					$arr_detail = array(
						'sales_po_id' => $sales_po_id,
						'product_id' => $product_id,
						'penawaran_id' => $penawaran_id,
						'qty' => $qty,
						'measure' => $measure,
						'price' => $price,
						'tax_id' => $tax_id,
						'tax' => $tax,
						'total' => $total_pro
					);

					$this->db->insert('pmm_sales_po_detail', $arr_detail);
				} else {
					redirect('penjualan/sales_po');
					exit();
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal Menambahkan Sales Order !!');
			redirect('penjualan/sales_po');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Sales Order !!');
			redirect('admin/penjualan');
		}
	}
	
	public function cetak_sales_order($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['row'] = $this->db->get_where('pmm_sales_po',array('id'=>$id))->row_array();
		$data['data'] = $this->db->query("SELECT * FROM `pmm_sales_po_detail` INNER JOIN produk ON pmm_sales_po_detail.product_id = produk.id WHERE sales_po_id = '$id'")->result_array();
        $html = $this->load->view('penjualan/cetak_sales_order',$data,TRUE);
        $row = $this->db->get_where('pmm_sales_po',array('id'=>$id))->row_array();


        
        $pdf->SetTitle($row['contract_number']);
        $pdf->nsi_html($html);
        $pdf->Output($row['contract_number'].'.pdf', 'I');
	}

	public function table_productions()
	{
		$data = array();

		$sales_po_id = $this->input->post('sales_po_id');
		$supplier_id = $this->input->post('supplier_id');
		$filter_date = $this->input->post('filter_date');

		if (!empty($supplier_id)) {
			$this->db->where('client_id', $supplier_id);
		}
		if (!empty($sales_po_id)) {
			$this->db->where('salesPo_id', $sales_po_id);
		}
		if (!empty($filter_date)) {
			$arr_date = explode(' - ', $filter_date);
			$start_date = date('Y-m-d', strtotime($arr_date[0]));
			$end_date = date('Y-m-d', strtotime($arr_date[1]));
			$this->db->where('date_production >=', $start_date);
			$this->db->where('date_production <=', $end_date);
		}
		//$this->db->order_by('date_production', 'DESC');
		$this->db->order_by('updated_on', 'DESC');
		$query = $this->db->get('pmm_productions');
		//file_put_contents("D:\\table_productions.txt", $this->db->last_query());
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key + 1;
				// $row['coa'] = ''
				$row['checkbox'] = '';				
				$row['date_production'] = date('d/m/Y', strtotime($row['date_production']));
				$row['no_production'] = $row['no_production'];
				$row['contract_number'] = $this->crud_global->GetField('pmm_sales_po', array('id' => $row['salesPo_id']), 'contract_number');
				$row['product'] = $this->crud_global->GetField('produk', array('id' => $row['product_id']), 'nama_produk');
				$row['client'] = $this->crud_global->GetField('penerima', array('id' => $row['client_id']), 'nama');
				$row['volume'] = number_format($row['volume'],2,',','.');
				$row['measure'] = $row['measure'];
				$row['display_volume'] = number_format($row['display_volume'],2,',','.');
				$row['convert_measure'] = $row['convert_measure'];
				$row['status_payment'] = $this->pmm_model->StatusPayment($row['status_payment']);
				$row['action'] = '-';
				$data[] = $row;
			}
		}
		echo json_encode(array('data' => $data));
	}


	public function table_penagihan()
	{
		$data = array();
		
		$w_date = $this->input->post('filter_date');

        if (!empty($w_date)) {
            $arr_date = explode(' - ', $w_date);
            $start_date = $arr_date[0];
            $end_date = $arr_date[1];
            $this->db->where('tanggal_invoice  >=', date('Y-m-d', strtotime($start_date)));
            $this->db->where('tanggal_invoice <=', date('Y-m-d', strtotime($end_date)));
        }
				
		$this->db->order_by('tanggal_invoice', 'DESC');
		$this->db->order_by('created_on', 'DESC');
        $query = $this->db->get('pmm_penagihan_penjualan');
		//file_put_contents("D:\\penjualan.txt", $this->db->last_query());
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key + 1;
				// $row['coa'] = ''
				$row['total_biaya'] = number_format($row['total'], 0, ',', '.');
				$pembayaran = $this->pmm_finance->getTotalPembayaranPenagihanPenjualan($row['id']);
				$row['pembayaran'] = number_format($pembayaran, 0, ',', '.');
				$row['sisa_tagihan'] = number_format($row['total'] - $pembayaran, 0, ',', '.');
				$row['tanggal_invoice'] = date('d/m/Y', strtotime($row['tanggal_invoice']));
				$row['tanggal_kontrak'] = date('d/m/Y', strtotime($row['tanggal_kontrak']));
				
				if ($row["status"] === "OPEN") {
					$row["nomor_invoice"] = "<a href=" . site_url("penjualan/detailPenagihan/" . $row["id"]) . " class='text-dark'>" . $row["nomor_invoice"] . "</a>";
				} elseif ($row["status"] === "DRAFT") {
					$row["nomor_invoice"] = "<a href=" . site_url("penjualan/detailPenagihan/" . $row["id"]) . " class='text-dark'>" . $row["nomor_invoice"] . "</a>";
				} elseif ($row["status"] === "REJECT") {
					$row["nomor_invoice"] = "<a href=" . site_url("penjualan/detailPenagihan/" . $row["id"]) . " class='text-dark'>" . $row["nomor_invoice"] . "</a>";
				} elseif ($row["status"] === "CLOSED") {
					$row["nomor_invoice"] = "<a href=" . site_url("penjualan/detailPenagihan/" . $row["id"]) . " class='text-dark'>" . $row["nomor_invoice"] . "</a>";
				}
				
				$row['status'] = (($row['sisa_tagihan']) == 0) ? "LUNAS" : "BELUM LUNAS";
				
				$data[] = $row;
			}
		}
		echo json_encode(array('data' => $data));
	}
	
	public function penagihan_penjualan($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {

			$arr_id = explode(',', $id);
			$id_new = array();
			foreach ($arr_id as $key => $row) {
				if (!empty($row)) {
					$id_new[] = $row;

					$check_status = $this->crud_global->GetField('pmm_productions', array('id' => $row), 'status_payment');
					if ($check_status !== 'UNCREATED') {
						$this->session->set_flashdata('notif_error', 'Status Payment harus UNCREATED');
						redirect('admin/penjualan');
					}
				}
			}
			// print_r($id_new);
			$this->db->where_in('id', $id_new);
			$data['cekSurat'] = $this->db->get('pmm_productions')->result_array();


			$data['cekSurat'] = $this->db->get('pmm_productions')->result_array();


			$this->db->select('pp.id AS idProduction,
			pp.salesPo_id AS salesPo_id,
			sum(pp.volume) as volume,
			p.nama_produk AS nameProduk,
			pp.harga_satuan AS hargaProduk,
			pp.no_production,
			pp.tax_id,
			pt.tax_name,
			pp.product_id,
			pp.measure');
			$this->db->where_in('pp.id', $id_new);
			$this->db->join('produk p', 'p.id = pp.product_id', 'left');
			$this->db->join('pmm_taxs pt', 'pp.tax_id = pt.id', 'left');
			$this->db->group_by('pp.product_id');
			$data['cekHarga'] = $this->db->get('pmm_productions pp')->result_array();
			
			//file_put_contents("D:\\penagihan_penjualan.txt", $this->db->last_query());
			
			// return var_dump($data['cekSurat'][0]);

			$data['id'] = $id;
			
			$data['id_new'] = $id_new;

			$this->db->where_in('id', $id_new);

			$data['query'] = $this->db->get('pmm_productions')->row_array();

			$data['clients'] = $this->db->select('*')->get_where('penerima', array('id' => $data['query']['client_id']))->row_array();

			$data['produk'] = $this->db->select('*')->get_where('produk', array('status' => 'PUBLISH'))->result_array();

			$data['sales'] = $this->db->get_where('pmm_sales_po', array('id' => $data['cekHarga'][0]['salesPo_id']))->row_array();

			$this->db->select('ppp.syarat_pembayaran as syarat_pembayaran');
			$this->db->join('pmm_sales_po_detail ppod', 'ppo.id = ppod.sales_po_id', 'left');
			$this->db->join('pmm_penawaran_penjualan ppp', 'ppod.penawaran_id = ppp.id', 'left');
			$this->db->join('pmm_productions pp', 'ppo.id = pp.salesPo_id', 'left');
			$this->db->where_in('pp.id', $id_new);
			$data['syarat_pembayaran'] = $this->db->get_where('pmm_sales_po ppo')->row_array();
			//file_put_contents("D:\\syarat_pembayaran.txt", $this->db->last_query());

			$data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();

			$data['noInvoice'] = $this->pmm_finance->NoInvoice();

			$this->load->view('penjualan/penagihan_penjualan', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_penagihan_penjualan()
	{

		$nama_pelanggan = $this->input->post('pelanggan');
		$tanggal_kontrak = $this->input->post('tanggal_kontrak');
		$tanggal_invoice = $this->input->post('tanggal_invoice');
		$tanggal_jatuh_tempo = $this->input->post('tanggal_jatuh_tempo');
		$syarat_pembayaran = $this->input->post('syarat_pembayaran');
		$jobs_type = $this->input->post('jobs_type');
		$total_product = $this->input->post('total_product');
		$memo = $this->input->post('memo');
		$attach = $this->input->post('attach');
		$sub_total = $this->input->post('sub_total');
		$ppn = $this->input->post('ppn');
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
			'tanggal_kontrak' => date('Y-m-d', strtotime($tanggal_kontrak)),
			'tanggal_invoice' => date('Y-m-d', strtotime($tanggal_invoice)),
			'syarat_pembayaran' => $syarat_pembayaran,
			//'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($tanggal_jatuh_tempo)),
			'alamat_pelanggan' => $this->input->post('alamat_pelanggan'),
			'nomor_kontrak' => $this->input->post('nomor_kontrak'),
			'nomor_invoice' => $this->input->post('nomor_invoice'),
			'jenis_pekerjaan' => $this->input->post('jenis_pekerjaan'),
			'total' => $total,
			'memo' => $this->input->post('memo'),
			'status' => 'DRAFT',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);


		if ($this->db->insert('pmm_penagihan_penjualan', $arr_insert)) {
			$tagihan_id = $this->db->insert_id();

			if (!file_exists('uploads/penagihan')) {
			    mkdir('uploads/penagihan', 0777, true);
			}

			$data = [];
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				if (!empty($_FILES['files']['name'][$i])) {

					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					$config['upload_path'] = 'uploads/penagihan';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf|JPG|PDF|JPEG|PNG';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'penagihan_id' => $tagihan_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('pmm_lampiran_penagihan', $data[$i]);
					}
				}
			}
			for ($i = 1; $i <= $total_product; $i++) {
				$product_id = $_POST["product_id_" . $i];
				$qty = $this->input->post('qty_' . $i);
				$measure = $this->input->post('measure_' . $i);
				$price = $this->input->post('price_' . $i);
				$tax = $this->input->post('tax_' . $i);
				$tax_id = $this->input->post('tax_id_' . $i);
				$total_pro = $qty * $price;
				if (!empty($product_id)) {

					$tax = 0;
					if ($tax_id == 3) {
						$tax = ($total_pro * 10) / 100;
					}

					if ($tax_id == 4) {
						$tax = ($total_pro * 0) / 100;
					}

					if ($tax_id == 5) {
						$tax = ($total_pro * 2) / 100;
					}

					if ($tax_id == 6) {
						$tax = ($total_pro * 11) / 100;
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

					$this->db->insert('pmm_penagihan_penjualan_detail', $arr_detail);
				} else {
					$this->session->set_flashdata('notif_error', 'Gagal Menambahkan Tagihan Penjualan');
					redirect('penjualan/penagihan_penjualan/' . $this->input->post('idSurat'));
					exit();
				}
			}

			$arr_surat_jalan = explode(',', $surat_jalan);
			if (!empty($arr_surat_jalan)) {
				foreach ($arr_surat_jalan as $sj_id) {
					$this->db->update('pmm_productions', array('status_payment' => 'CREATING'), array('id' => $sj_id));
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			redirect('admin/penjualan#settings');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Tagihan Penjualan');
			redirect('admin/penjualan#settings');
		}
	}


	public function detailPenagihan($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['penagihan'] = $this->db->get_where('pmm_penagihan_penjualan', ["id" => $id])->row_array();
			$data['cekHarga'] = $this->db->get_where('pmm_penagihan_penjualan_detail', ["penagihan_id" => $id])->result_array();
			//file_put_contents("D:\\detailPenagihan.txt", $this->db->last_query());
			$data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
			$data['dataLampiran'] = $this->db->get_where('pmm_lampiran_penagihan', ["penagihan_id" => $id])->result_array();
			$this->load->view('penjualan/detailPenagihan', $data);
		} else {
			redirect('admin');
		}
	}

	public function delete_penagihan_penjualan($id)
	{
		if (!empty($id)) {

			$this->db->trans_start(); # Starting Transaction
			$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

			$penagihan = $this->db->get_where('pmm_penagihan_penjualan', array('id' => $id))->row_array();
			$deskripsi = 'Nomor Invoice ' . $penagihan['nomor_invoice'];
			$this->pmm_finance->InsertLogs('DELETE', 'pmm_penagihan_penjualan', $id, $deskripsi);

			$pembayaran = $this->db->select()->get_where('pmm_pembayaran', array('penagihan_id' => $id))->result_array();
			if (!empty($pembayaran)) {
				foreach ($pembayaran as $key => $row) {

					$arr_trans_2 = explode(',', $row['transaction_id']);
					$this->db->where_in('id', $arr_trans_2);
					$this->db->delete('transactions');
				}
			}

			$this->db->delete('transactions', array('id' => $penagihan['transaction_id']));
			$this->db->delete('pmm_penagihan_penjualan_detail', array('penagihan_id' => $id));
			$this->db->delete('pmm_pembayaran', array('penagihan_id' => $id));
			$this->db->delete('pmm_lampiran_penagihan', array('penagihan_id' => $id));
			$this->db->delete('pmm_penagihan_penjualan', array('id' => $id));


			// update surat jalan
			$surat_jalan = explode(',', $penagihan['surat_jalan']);
			foreach ($surat_jalan as $key => $sj) {
				$this->db->update('pmm_productions', array('status_payment' => 'UNCREATED'), array('id' => $sj));
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error', 'Gagal Menghapus Tagihan Penjualan !!');
				redirect('penjualan/detailPenagihan/' . $id);
			} else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success', 'Berhasil Menghapus Tagihan Penjualan !!');
				redirect('admin/penjualan');
			}
		}
	}


	public function halaman_pembayaran($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['pembayaran'] = $this->db->get_where('pmm_penagihan_penjualan', ["id" => $id])->row_array();
			//file_put_contents("D:\\pembayaran.txt", $this->db->last_query());
			$data['total_bayar'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran', array('penagihan_id' => $id))->row_array();


			// Setor Bank
			$this->db->select('c.*');
			$this->db->where('c.coa_category', 3);
			$this->db->where('c.status', 'PUBLISH');
			$this->db->order_by('c.coa_number', 'asc');
			$query = $this->db->get('pmm_coa c');
			$data['setor_bank'] = $query->result_array();
			$this->load->view('penjualan/pembayaran_tagihan', $data);
		} else {
			redirect('admin');
		}
	}

	public function sunting_pembayaran($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {

			$data['bayar'] = $this->db->get_where('pmm_pembayaran', ["id" => $id])->row_array();
			$data['pembayaran'] = $this->db->get_where('pmm_penagihan_penjualan', ["id" => $data['bayar']['penagihan_id']])->row_array();
			$data['total_bayar'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran', array('penagihan_id' => $id))->row_array();
			

			// Setor Bank
			$this->db->select('c.*');
			$this->db->where('c.coa_category', 3);
			$this->db->where('c.status', 'PUBLISH');
			$this->db->order_by('c.coa_number', 'asc');
			$query = $this->db->get('pmm_coa c');
			$data['setor_bank'] = $query->result_array();
			$this->load->view('penjualan/sunting_pembayaran', $data);
		} else {
			redirect('admin');
		}
	}

	public function simpan_pembayaran()
	{

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); #

		try {

			$id = $this->input->post('id');

			$pembayaran_pro = $this->input->post('pembayaran');
			$pembayaran_pro = str_replace('.', '', $pembayaran_pro);
			$pembayaran_pro = str_replace(',', '.', $pembayaran_pro);


			$arr_update = array(
				'penagihan_id' => $this->input->post('id_penagihan'),
				'nama_pelanggan' => $this->input->post('nama_pelanggan'),
				'client_id' => $this->input->post('client_id'),
				'setor_ke' => $this->input->post('setor_ke'),
				'cara_pembayaran' => $this->input->post('cara_pembayaran'),
				'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
				'nomor_transaksi' => $this->input->post('nomor_transaksi'),
				'memo' => $this->input->post('memo'),
				'pembayaran' => $pembayaran_pro,
				'total' => $pembayaran_pro,
				'created_by' => $this->session->userdata('admin_id'),
				'created_on' => date('Y-m-d H:i:s')
			);

			$this->db->where('id', $id);
			if ($this->db->update('pmm_pembayaran', $arr_update)) {
				$pembayaran_id = $this->db->insert_id();

				$data = [];
				$count = count($_FILES['files']['name']);
				for ($i = 0; $i < $count; $i++) {

					if (!empty($_FILES['files']['name'][$i])) {

						$_FILES['file']['name'] = $_FILES['files']['name'][$i];
						$_FILES['file']['type'] = $_FILES['files']['type'][$i];
						$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
						$_FILES['file']['error'] = $_FILES['files']['error'][$i];
						$_FILES['file']['size'] = $_FILES['files']['size'][$i];

						$config['upload_path'] = 'uploads/pembayaran';
						$config['allowed_types'] = 'jpg|jpeg|png|pdf';
						$config['file_name'] = $_FILES['files']['name'][$i];

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('file')) {
							$uploadData = $this->upload->data();
							$filename = $uploadData['file_name'];

							$data['totalFiles'][] = $filename;


							$data[$i] = array(
								'pembayaran_id' => $id,
								'lampiran'  => $data['totalFiles'][$i]
							);

							$this->db->insert('pmm_lampiran_pembayaran', $data[$i]);
						}
					}
				}
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error', 'Gagal Menambahkan Data Pembayaran !!');
				redirect('penjualan/halaman_pembayaran/' . $this->input->post('id_penagihan'));
			} else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Data Pembayaran !!');
				redirect('penjualan/detailPenagihan/' . $this->input->post('id_penagihan'));
			}
		} catch (Throwable $e) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', $e->getMessage());
			redirect('penjualan/halaman_pembayaran/' . $this->input->post('id_penagihan'));
		}
	}

	public function submit_pembayaran()
	{

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); #

		$pembayaran_pro = $this->input->post('pembayaran');
		$pembayaran_pro = str_replace('.', '', $pembayaran_pro);
		$pembayaran_pro = str_replace(',', '.', $pembayaran_pro);


		$arr_insert = array(
			'penagihan_id' => $this->input->post('id_penagihan'),
			'nama_pelanggan' => $this->input->post('nama_pelanggan'),
			'client_id' => $this->input->post('client_id'),
			'setor_ke' => $this->input->post('setor_ke'),
			'cara_pembayaran' => $this->input->post('cara_pembayaran'),
			'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
			'nomor_transaksi' => $this->input->post('nomor_transaksi'),
			'memo' => $this->input->post('memo'),
			'pembayaran' => $pembayaran_pro,
			'total' => $pembayaran_pro,
			'status' => 'Disetujui',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);

		if ($this->db->insert('pmm_pembayaran', $arr_insert)) {
			$pembayaran_id = $this->db->insert_id();

			if (!file_exists('uploads/pembayaran')) {
			    mkdir('uploads/pembayaran', 0777, true);
			}

			$data = [];
			$count = count($_FILES['files']['name']);
			for ($i = 0; $i < $count; $i++) {

				if (!empty($_FILES['files']['name'][$i])) {

					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];

					$config['upload_path'] = 'uploads/pembayaran';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'pembayaran_id' => $pembayaran_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('pmm_lampiran_pembayaran', $data[$i]);
					}
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal Menambahkan Data Pembayaran !!');
			redirect('penjualan/halaman_pembayaran/' . $this->input->post('id_penagihan'));
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Data Pembayaran !!');
			redirect('penjualan/detailPenagihan/' . $this->input->post('id_penagihan'));
		}
	}
	
	public function view_pembayaran($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {

			$data['bayar'] = $this->db->get_where('pmm_pembayaran', ["id" => $id])->row_array();
			$data['pembayaran'] = $this->db->get_where('pmm_penagihan_penjualan', ["id" => $data['bayar']['penagihan_id']])->row_array();
			$data['total_bayar'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran', array('id' => $id))->row_array();
			$data['dataLampiran'] = $this->db->get_where('pmm_lampiran_pembayaran', ["pembayaran_id" => $id])->result_array();
			

			// Setor Bank
			$this->db->select('c.*');
			$this->db->where('c.coa_category', 3);
			$this->db->where('c.status', 'PUBLISH');
			$this->db->order_by('c.coa_number', 'asc');
			$query = $this->db->get('pmm_coa c');
			$data['setor_bank'] = $query->result_array();
			$this->load->view('penjualan/view_pembayaran', $data);
		} else {
			redirect('admin');
		}
	}

	public function table_pembayaran($id)
	{
		$data = array();

		$query = $this->db->get_where('pmm_pembayaran p', array('p.penagihan_id' => $id));
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key + 1;
				// $row['coa'] = '' 
				$row['saldo'] = 0;
				$row['setor_ke'] = $this->crud_global->GetField('pmm_coa', array('id' => $row['setor_ke']), 'coa');
				$row['tanggal_pembayaran'] = date('d/m/Y', strtotime($row['tanggal_pembayaran']));
				$row['total'] = number_format($row["total"], 2, ',', '.');
				$row['status'] = ('Disetujui');

				if ($row['status'] == 'Disetujui') {
					$row['action'] = '<a href="' . base_url('penjualan/cetak_pembayaran/' . $row["id"]) . '" target="_blank" class="btn btn-success">Cetak PDF</a>';
				} else {
					$url_approve = "'" . base_url('penjualan/cetak_pembayaran/' . $row["id"]) . "'";
					$row['action'] = '<a href="javascript:void(0);" onclick="ApprovePayment(' . $url_approve . ')" class="btn btn-warning">Approve</a>';
				}
				$data[] = $row;
			}
		}
		echo json_encode(array('data' => $data));
	}

	public function hapus_pembayaran($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('pmm_pembayaran');
	}

	public function rejectPenagihan($id)
	{
		$this->db->set('status', 'REJECT');
		$this->db->where('id', $id);
		$this->db->update('pmm_penagihan_penjualan');
		redirect('admin/penjualan');
	}

	public function approvePenagihan($id)
	{

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); #


		$penagihan_penjualan = $this->db->get_where('pmm_penagihan_penjualan', array('id' => $id))->row_array();
		$coa_description = 'Sales Invoice ' . $penagihan_penjualan['nomor_invoice'];

		// Insert COA
		$this->pmm_finance->InsertTransactions(3, $coa_description, $penagihan_penjualan['total'], 0);
		$transaction_id = $this->db->insert_id();

		$this->db->set('status', 'OPEN');
		$this->db->set('transaction_id', $transaction_id);
		$this->db->where('id', $id);
		$this->db->update('pmm_penagihan_penjualan');


		$detail = $this->db->select('surat_jalan')->get_where('pmm_penagihan_penjualan', array('id' => $id))->row_array();

		$arr_detail = explode(',', $detail['surat_jalan']);
		if (!empty($arr_detail)) {
			foreach ($arr_detail as $key => $dt) {
				$this->db->update('pmm_productions', array('status_payment' => 'CREATED'), array('id' => $dt));
			}
		}

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			// redirect('pmm/pembayaran/halaman_pembayaran/'.$this->input->post('id_penagihan'));
			echo 'Error';
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Menyetujui Penagihan !!');
			redirect('penjualan/detailPenagihan/' . $id);
		}
	}



	public function cetak_pembayaran($id)
	{

		$this->load->library('pdf');


		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->setPrintHeader(true);
		$pdf->SetFont('helvetica', '', 7);
		$tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['pembayaran'] = $this->db->query("SELECT * FROM `pmm_pembayaran` WHERE id = '$id'")->row_array();
		$data['setor_ke'] = $this->crud_global->GetField('pmm_coa', array('id' => $data['pembayaran']['setor_ke']), 'coa');
		$id_penagihan = $data['pembayaran']['penagihan_id'];
		$html = $this->load->view('penjualan/cetak_pembayaran', $data, TRUE);


		$pdf->SetTitle('bukti-penerimaan-penjualan');
		$pdf->nsi_html($html);
		$pdf->Output('bukti-penerimaan-penjualan.pdf', 'I');
	}
	
	public function closed_penawaran_penjualan($id)
    {
        $this->db->set("status", "CLOSED");
        $this->db->where("id", $id);
        $this->db->update("pmm_penawaran_penjualan");

        $this->db->update('pmm_penawaran_penjualan', array('status' => 'CLOSED'), array('id' => $id));
        $this->session->set_flashdata('notif_success', 'Berhasil Melakukan Closed Penawaran Penjualan');
        redirect("admin/penjualan");
    }
	
	public function hapus_sales_po($id)
    {
    	$this->db->trans_start(); # Starting Transaction

		$this->db->delete('pmm_sales_po_detail', array('sales_po_id'=>$id));

		$this->db->delete('pmm_lampiran_sales_po', array('sales_po_id'=>$id));

		$penagihan = $this->db->get_where('pmm_penagihan_penjualan', array('sales_po_id' => $id))->row_array();

		//file_put_contents("D:\\penagihan.txt", $this->db->last_query());

		$this->db->delete('pmm_penagihan_penjualan_detail',array('penagihan_id'=>$penagihan['id']));

		$this->db->delete('pmm_lampiran_penagihan',array('penagihan_id'=>$penagihan['id']));

		$this->db->delete('pmm_pembayaran', array('penagihan_id' => $penagihan['id']));

		$this->db->delete('pmm_productions', array('salesPo_id'=>$id));

		$this->db->delete('pmm_penagihan_penjualan',array('sales_po_id'=>$id));

		$this->db->delete('pmm_sales_po', array('id'=>$id));


		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error','Gagal Menghapus Sales Order');
            redirect('penjualan/dataSalesPO/'.$id);
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil Menghapus Sales Order');
            redirect('admin/penjualan');
        }
    }
	
	public function cetak_penagihan_penjualan($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		//$pdf->setPrintFooter(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		//$pdf->SetMargins(0, 0, 0, true);
		$pdf->AddPage('P');
		
		$data['penagihan'] = $this->db->get_where('pmm_penagihan_penjualan', ["id" => $id])->row_array();
		$data['cekHarga'] = $this->db->get_where('pmm_penagihan_penjualan_detail', ["penagihan_id" => $id])->result_array();
		$data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
		$data['dataLampiran'] = $this->db->get_where('pmm_lampiran_penagihan', ["penagihan_id" => $id])->result_array();
		
		//file_put_contents("D:\\cetak_penagihan_penjualan.txt", $this->db->last_query());
		
        $html = $this->load->view('penjualan/cetak_penagihan_penjualan',$data,TRUE);
        $row = $this->db->get_where('pmm_penagihan_penjualan',array('id'=>$id))->row_array();


        
        $pdf->SetTitle($row['nomor_invoice']);
        $pdf->nsi_html($html);
        $pdf->Output($row['nomor_invoice'].'.pdf', 'I');
	}

	public function closed_sales_order($id)
    {
        $this->db->set("status", "CLOSED");
        $this->db->where("id", $id);
        $this->db->update("pmm_sales_po");
        $this->session->set_flashdata('notif_success', 'Berhasil Melakukan Closed Sales Order');
        redirect("admin/penjualan");
    }

	public function closed_pembayaran_penagihan($id)
	{
		$this->db->set("status", "CLOSED");
		$this->db->where("id", $id);
		$this->db->update("pmm_penagihan_penjualan");
		$this->session->set_flashdata('notif_success', 'Berhasil Menyelesaikan Penagihan');
		redirect("penjualan/detailPenagihan/$id");
	}

	
}
