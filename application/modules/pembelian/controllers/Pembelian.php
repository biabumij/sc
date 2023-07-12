<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends Secure_Controller
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

    public function tagihan_pembelian()
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {
            $data['supplier'] = $this->db->select('id,name,address')->get_where('pmm_supplier', array('status' => 'PUBLISH'))->result_array();
            $data['products'] = $this->db->select('id,product,contract_price')->get_where('pmm_product', array('status' => 'PUBLISH'))->result_array();
            $this->load->view('pembelian/tagihan_pembelian', $data);
        } else {
            redirect('admin');
        }
    }

    public function penawaran_pembelian()
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {
            $data['supplier'] = $this->db->order_by('nama', 'asc')->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => 1))->result_array();
            $data['products'] = $this->db->order_by('nama_produk', 'asc')->select('*')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
            $data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
            $data['taxs_2'] = $this->db->select('id,tax_name')
			->from('pmm_taxs')
			->where("id in ('4','5')")
			->get()->result_array();
            $data['measures'] = $this->db->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
            $this->load->view('pembelian/penawaran_pembelian', $data);
        } else {
            redirect('admin');
        }
    }

    public function penawaran_pembelian_detail($id)
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {

            $data['row'] = $this->db->get_where('pmm_penawaran_pembelian pp', array('pp.id' => $id))->row_array();
            if (!empty($data['row'])) {
                $this->load->view('pembelian/penawaran_pembelian_detail', $data);
            } else {
                echo 'Data tidak ada';
            }
        } else {
            redirect('admin');
        }
    }

    public function penagihan_pembelian_detail($id)
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {

            $this->db->select('pp.*, ps.nama as supplier, ps.alamat as supplier_address,SUM(ppp.total) as pembayaran');
            $this->db->join('penerima ps', 'pp.supplier_id = ps.id', 'left');
            $this->db->join('pmm_pembayaran_penagihan_pembelian ppp', 'pp.id = ppp.penagihan_pembelian_id', 'left');
            $data['row'] = $this->db->get_where('pmm_penagihan_pembelian pp', array('pp.id' => $id))->row_array();
            if (!empty($data['row'])) {


                $this->load->view('pembelian/penagihan_pembelian_detail', $data);
            } else {
                echo 'Data tidak ada';
            }
        } else {
            redirect('admin');
        }
    }

    public function submit_penawaran_pembelian()
    {

        $supplier_id  = $this->input->post('supplier_id');
        $tanggal_penawaran = $this->input->post('tanggal_penawaran');
        $berlaku_hingga = $this->input->post('berlaku_hingga');
        $jenis_pembelian = $this->input->post('jenis_pembelian');
        $client_address = $this->input->post('alamat_supplier');
        $nomor_penawaran = $this->input->post('nomor_penawaran');
        $metode_pembayaran = $this->input->post('metode_pembayaran');
        $syarat_pembayaran = $this->input->post('syarat_pembayaran');
        $total_product = $this->input->post('total_product');
        $memo = $this->input->post('memo');
        $attach = $this->input->post('attach');
        $sub_total = $this->input->post('sub_total');
        $total = $this->input->post('total');

        //$supplier = $this->crud_global->GetField('penerima', array('id' => $supplier_id), 'nama');

        $arr_insert = array(
            //'supplier' => $supplier,
            'supplier_id' => $supplier_id,
            'tanggal_penawaran' => date('Y-m-d', strtotime($tanggal_penawaran)),
            'berlaku_hingga' => date('Y-m-d', strtotime($berlaku_hingga)),
            'jenis_pembelian' => $jenis_pembelian,
            'client_address' => $client_address,
            'nomor_penawaran' => $nomor_penawaran,
            'metode_pembayaran' => $metode_pembayaran,
            'syarat_pembayaran' => $syarat_pembayaran,
            'total' => $total,
            'memo' => $memo,
            'created_by' => $this->session->userdata('admin_id'),
            'created_on' => date('Y-m-d H:i:s'),
            'status' => 'DRAFT'
        );


        if ($this->db->insert('pmm_penawaran_pembelian', $arr_insert)) {
            $penawaran_pembelian_id = $this->db->insert_id();

            if (!file_exists('uploads/penawaran_pembelian')) {
			    mkdir('uploads/penawaran_pembelian', 0777, true);
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

                    $config['upload_path'] = 'uploads/penawaran_pembelian';
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config['file_name'] = $_FILES['files']['name'][$i];

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('file')) {
                        $uploadData = $this->upload->data();
                        $filename = $uploadData['file_name'];

                        $data['totalFiles'][] = $filename;


                        $data[$i] = array(
                            'penawaran_pembelian_id' => $penawaran_pembelian_id,
                            'lampiran'  => $data['totalFiles'][$i]
                        );

                        $this->db->insert('pmm_lampiran_penawaran_pembelian', $data[$i]);
                    }
                }
            }

            for ($i = 1; $i <= $total_product; $i++) {
				$product_id = $this->input->post('product_' . $i);
				$qty = $this->input->post('qty_' . $i);
				$measure = $this->input->post('measure_' . $i);


				$price = $this->input->post('price_' . $i);
				$price = str_replace('.', '', $price);
				$price = str_replace(',', '.', $price);
				$tax_id = $this->input->post('tax_' . $i);
                $pajak_id = $this->input->post('pajak_' . $i);
				$total_pro = $this->input->post('total_' . $i);
				$total_pro = str_replace('.', '', $total_pro);
				$total_pro = str_replace(',', '.', $total_pro);

				if (!empty($product_id)) {

					$tax = 0;
                    $pajak = 0;
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

					if ($pajak_id == 3) {
						$pajak = ($total_pro * 10) / 100;
					}

					if ($pajak_id == 4) {
						$pajak = ($total_pro * 0) / 100;
					}

					if ($pajak_id == 5) {
						$pajak = ($total_pro * 2) / 100;
					}

                    if ($pajak_id == 6) {
						$pajak = ($total_pro * 11) / 100;
					}
                    
                    $arr_detail = array(
                        'penawaran_pembelian_id' => $penawaran_pembelian_id,
                        'material_id' => $product_id,
                        'qty' => $qty,
                        'measure' => $measure,
                        'price' => $price,
                        'tax_id' => $tax_id,
						'tax' => $tax,
                        'pajak_id' => $pajak_id,
						'pajak' => $pajak,
                        'total' => $total_pro,
                        'status' => 'DRAFT'
                    );

                    $this->db->insert('pmm_penawaran_pembelian_detail', $arr_detail);
                } else {
                    redirect('pembelian/penawaran_pembelian');
                    exit();
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error', 'Gagal Menambahkan Penawaran Pembelian !!');
            redirect('pembelian/penawaran_pembelian');
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Penawaran Pembelian !!');
            redirect('admin/pembelian');
        }
    }

    public function getMaterial($idSupplier)
    {
        $query = $this->db->query("SELECT 
        pmm_supplier_materials.id AS idSupplierMaterials, 
        pmm_materials.material_name AS namaMaterial, 
        pmm_supplier_materials.measure AS measureMaterial, 
        material_id, 
        supplier_id, 
        cost, 
        price FROM pmm_supplier_materials 
        INNER JOIN pmm_materials ON pmm_supplier_materials.material_id = pmm_materials.id 
        WHERE pmm_supplier_materials.supplier_id = '$idSupplier'")->result_array();
        if ($query) {
            echo "<option>--Pilih Material</option>";
            foreach ($query as $q) {
                echo "<option value='$q[idSupplierMaterials]' data-price='$q[price]' >$q[namaMaterial]</option>";
            }
        } else {
            alert('Data material dari supplier tidak ditemukan');
        }
    }

    public function table_penawaran_pembelian()
    {

        $w_date = $this->input->post('filter_date');
        $data = array();

        if (!empty($w_date)) {
            $arr_date = explode(' - ', $w_date);
            $start_date = $arr_date[0];
            $end_date = $arr_date[1];
            $this->db->where('tanggal_penawaran  >=', date('Y-m-d', strtotime($start_date)));
            $this->db->where('tanggal_penawaran <=', date('Y-m-d', strtotime($end_date)));
        }

        $this->db->order_by('created_on', 'DESC');
        $query = $this->db->get('pmm_penawaran_pembelian');

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key + 1;
                $row['tanggal_penawaran'] = date('d/m/Y', strtotime($row['tanggal_penawaran']));
                $row['berlaku_hingga'] = date('d/m/Y', strtotime($row['berlaku_hingga']));
                $row['supplier'] = $this->crud_global->GetField('penerima',array('id'=>$row['supplier_id']),'nama');

                $url_detail = site_url('pembelian/penawaran_pembelian_detail/' . $row['id']);
                $row['nomor_penawaran'] = '<a href="' . $url_detail . '">' . $row['nomor_penawaran'] . '</a>';
				$row['status'] = $this->pmm_model->GetStatus2($row['status']);
                $row['total'] = number_format($row["total"], 0, ',', '.');
                $row['action'] = '-';
                $row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
                $data[] = $row;
            }
        }
        echo json_encode(array('data' => $data));
    }




    public function table_pesanan_pembelian()
    {
        $w_date = $this->input->post('filter_date');

        if (!empty($w_date)) {
            $arr_date = explode(' - ', $w_date);
            $start_date = $arr_date[0];
            $end_date = $arr_date[1];
            $this->db->where('date_po  >=', date('Y-m-d', strtotime($start_date)));
            $this->db->where('date_po <=', date('Y-m-d', strtotime($end_date)));
        }


        $data = $this->pmm_model->TableCustomMaterial(0);


        echo json_encode(array('data' => $data));
    }
	
	public function get_pdf()
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
		$sp = $this->db->get_where('penerima',array('id'=>$row['supplier_id']))->row_array();
		$row['address_supplier'] = $sp['alamat'];
		$row['npwp_supplier'] = $sp['npwp'];
		$row['supplier_name'] = $sp['nama'];
		$row['pic'] = $sp['nama_kontak'];
		$row['position'] = $sp['posisi'];
		$row['date_pkp'] = $row['date_pkp'];
		$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
		$data['row'] = $row;
		$data['id'] = $id;
        $html = $this->load->view('pembelian/cetak_pesanan_pembelian',$data,TRUE);

        
        $pdf->SetTitle($row['no_po']);
        $pdf->nsi_html($html);
        $pdf->Output($row['no_po'].'.pdf', 'I');
	
	}

    public function table_penagihan_pembelian()
    {
        $data = array();
		
		$w_date = $this->input->post('filter_date');
        $supplier_id = $this->input->post('supplier_id');

        if (!empty($w_date)) {
            $arr_date = explode(' - ', $w_date);
            $start_date = $arr_date[0];
            $end_date = $arr_date[1];
            $this->db->where('created_on  >=', date('Y-m-d h:i A', strtotime($start_date.' 23:59:59')));
            $this->db->where('created_on <=', date('Y-m-d h:i A', strtotime($end_date.' 23:59:59')));
        }

        if(!empty($supplier_id)){
			$this->db->where('supplier_id',$supplier_id);
		}

        $this->db->order_by('created_on', 'DESC');
        $query = $this->db->get('pmm_penagihan_pembelian');
		
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $row) {
                $supplier_name = $this->crud_global->GetField('penerima', array('id' => $row['supplier_id']), 'nama');
                $row['no'] = $key + 1;
                $row['tanggal_invoice'] = date('d/m/Y', strtotime($row['tanggal_invoice']));
				$row['tanggal_po'] = date('d/m/Y', strtotime($row['tanggal_po']));
				$row['tanggal_jatuh_tempo'] = date('d/m/Y', strtotime($row['tanggal_jatuh_tempo']));
                $row['supplier'] = $supplier_name;
                $url_detail = site_url('pembelian/penagihan_pembelian_detail/' . $row['id']);
                $row['nomor_invoice'] = '<a href="' . $url_detail . '">' . $row['nomor_invoice'] . '</a>';
                $pembayaran = $this->pmm_finance->getTotalPembayaranPenagihanPembelian($row['id']);
                $sisa_tagihan = $row['total'] - $pembayaran;
                $row['total'] = number_format($row["total"], 0, ',', '.');


                $row['pembayaran'] = number_format($pembayaran, 0, ',', '.');
                $row['sisa_tagihan'] = number_format($sisa_tagihan, 0, ',', '.');

                if ($row['verifikasi_dok'] == 'BELUM') {
                    $row['verifikasi_dok'] = '<a href="javascript:void(0);" onclick="VerifDok(' . $row['id'] . ',0)" class="btn btn-warning btn-sm">' . $row['verifikasi_dok'] . '</a>';
                } else {
                    $row['verifikasi_dok'] = '<a href="javascript:void(0);" onclick="VerifDokDetail(' . $row['id'] . ',1)" class="btn btn-success btn-sm">' . $row['verifikasi_dok'] . '</a>';
                }

                $row['status'] = (intval($row['sisa_tagihan']) == 0) ? "LUNAS" : "BELUM LUNAS";
				//$row['status'] = $this->pmm_model->GetStatus3($row['status']);

                $row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));

                $data[] = $row;
            }
        }
        echo json_encode(array('data' => $data));
    }


    public function penagihan_pembelian()
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {

            $id = $this->uri->segment(3);
            if (!empty($id)) {
                $ex_id = explode(',', $id);
                $po_1 = '';
                $arr_receipt = array();
                foreach ($ex_id as $key => $val) {
                    // echo $val.'=';
                    if (!empty($val)) {
                        $check_po = $this->crud_global->GetField('pmm_receipt_material', array('id' => $val), 'purchase_order_id');

                        if (!empty($po_1)) {
                            // echo $check_po.' = '.$po_1.'<br />';
                            if ($po_1 !== $check_po) {
                                $this->session->set_flashdata('notif_error', 'Maaf, Nomor PO harus sama');
                                redirect('admin/pembelian');
                                exit();
                            }
                        }
                        $po_1 = $check_po;
                        $arr_receipt[] = $val;
                    }
                }
                $data['id'] = $id;

                // $data['rows'] = $this->db->get_where();
                $this->db->select('prm.*, pm.nama_produk as material_name, SUM(prm.volume) as volume, prm.tax_id as tax_id');
                $this->db->join('produk pm', 'prm.material_id = pm.id', 'left');
                $this->db->where_in('prm.id', $arr_receipt);
                $this->db->group_by('prm.material_id');
                $detail = $this->db->get('pmm_receipt_material prm')->result_array();
                $data['details'] = $detail;

                $this->db->select('ppo.*, ps.nama as supplier_name, ps.alamat as supplier_address, ppp.syarat_pembayaran');
				$this->db->join('pmm_purchase_order_detail ppod', 'ppo.id = ppod.purchase_order_id', 'left');
				$this->db->join('pmm_penawaran_pembelian ppp', 'ppod.penawaran_id = ppp.id', 'left');
                $this->db->join('penerima ps', 'ppo.supplier_id = ps.id', 'left');
                $data['po'] = $this->db->get_where('pmm_purchase_order ppo', array('ppo.id' => $detail[0]['purchase_order_id']))->row_array();
                $data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
                $data['setor_bank'] = $this->pmm_finance->BankCash();
                $this->load->view('pembelian/penagihan_pembelian', $data);
            } else {
                $this->session->set_flashdata('notif_error', 'Silahkan pilih tagihan terlebih pengiriman pembelian terlebih dahulu');
                redirect('admin/pembelian');
            }
        } else {
            redirect('admin');
        }
    }


    public function submit_penagihan_pembelian()
    {

        $supplier_id = $this->input->post('supplier_id');
		$supplier_name = $this->input->post('supplier_name');
        $no_po = $this->input->post('no_po');
        $purchase_order_id = $this->input->post('purchase_order_id');
        $nomor_invoice = $this->input->post('nomor_invoice');
        $tanggal_invoice = $this->input->post('tanggal_invoice');
        $tanggal_invoice = date('Y-m-d', strtotime($tanggal_invoice));
        //$tanggal_jatuh_tempo = $this->input->post('tanggal_jatuh_tempo');
        //$tanggal_jatuh_tempo = date('Y-m-d', strtotime($tanggal_jatuh_tempo));
        $syarat_pembayaran = $this->input->post('syarat_pembayaran');
        $tanggal_po = $this->input->post('tanggal_po');
        $memo = $this->input->post('memo');
        $total = $this->input->post('total');
        $uang_muka = $this->input->post('uang_muka');
        $uang_muka = str_replace('.', '', $uang_muka);
        $uang_muka = str_replace(',', '.', $uang_muka);
        $total_tagihan = $total;
        $total_tagihan = str_replace('.', '', $total_tagihan);
        $total_tagihan = str_replace(',', '.', $total_tagihan);
        $total_product = $this->input->post('total_product');

        $surat_jalan = $this->input->post('surat_jalan');
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

        $arr_insert = array(
            'purchase_order_id' => $purchase_order_id,
            'supplier_id' => $supplier_id,
            'surat_jalan' => $surat_jalan,
            'no_po' => $no_po,
            'tanggal_invoice' => $tanggal_invoice,
            'syarat_pembayaran' => $syarat_pembayaran,
            'nomor_invoice' => $this->input->post('nomor_invoice'),
            //'tanggal_jatuh_tempo' => date('Y-m-d', strtotime($this->input->post('tanggal_jatuh_tempo'))),
            'tanggal_po' => $this->input->post('tanggal_po'),
            'total' => $total,
            'uang_muka' => $uang_muka,
            'total_tagihan' => $total_tagihan,
            'memo' => $this->input->post('memo'),
            'status' => 'BELUM LUNAS',
            'created_by' => $this->session->userdata('admin_id'),
            'created_on' => date('Y-m-d H:i:s')
        );


        if ($this->db->insert('pmm_penagihan_pembelian', $arr_insert)) {
            $tagihan_id = $this->db->insert_id();

            if (!file_exists('uploads/penagihan_pembelian')) {
			    mkdir('uploads/penagihan_pembelian', 0777, true);
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

                    $config['upload_path'] = 'uploads/penagihan_pembelian';
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf|JPG|PDF|JPEG|PNG';
                    $config['file_name'] = $_FILES['files']['name'][$i];

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('file')) {
                        $uploadData = $this->upload->data();
                        $filename = $uploadData['file_name'];

                        $data['totalFiles'][] = $filename;


                        $data[$i] = array(
                            'penagihan_pembelian_id' => $tagihan_id,
                            'lampiran'  => $data['totalFiles'][$i]
                        );

                        $this->db->insert('pmm_lampiran_penagihan_pembelian', $data[$i]);
                    }
                }
            }
            for ($i = 1; $i <= $total_product; $i++) {
                $material_id = $_POST["material_id_" . $i];
                $receipt_material_id = $_POST["receipt_material_id_" . $i];
                $qty = $this->input->post('qty_' . $i);
                $measure = $this->input->post('measure_' . $i);
                $price = $this->input->post('price_' . $i);
                $price = str_replace('.', '', $price);
                $price = str_replace(',', '.', $price);
                $tax = $this->input->post('tax_' . $i);
				$tax_id = $this->input->post('tax_id_' . $i);
                $pajak = $this->input->post('pajak_' . $i);
				$pajak_id = $this->input->post('pajak_id_' . $i);
                $total_pro = $this->input->post('total_' . $i);
                $total_pro = str_replace('.', '', $total_pro);
                $total_pro = str_replace(',', '.', $total_pro);

                if (!empty($material_id)) {

                    $tax = 0;
                    $pajak = 0;
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

                    if ($pajak_id == 3) {
						$pajak = ($total_pro * 10) / 100;
					}

					if ($pajak_id == 4) {
						$pajak = ($total_pro * 0) / 100;
					}

					if ($pajak_id == 5) {
						$pajak = ($total_pro * 2) / 100;
					}

                    if ($pajak_id == 6) {
						$pajak = ($total_pro * 11) / 100;
					}


                    $arr_detail = array(
                        'penagihan_pembelian_id' => $tagihan_id,
                        'material_id' => $material_id,
                        'volume' => $qty,
                        'measure' => $measure,
                        'price' => $price,
                        'tax_id' => $tax_id,
                        'tax' => $tax,
                        'pajak_id' => $pajak_id,
                        'pajak' => $pajak,
                        'total' => $total_pro
                    );

                    $this->db->insert('pmm_penagihan_pembelian_detail', $arr_detail);

                    // $this->db->update('pmm_receipt_material',array('status_payment'=>'CREATING'),array('id'=>$receipt_material_id));


                } else {
                    $this->session->set_flashdata('notif_error', 'Gagal Membuat Tagihan Pembelian');
                    redirect('pembelian/penagihan_pembelian/' . $this->input->post('surat_jalan'));
                    exit();
                }

                if ($uang_muka > 0) {
                    $arr_bayar = array(
                        'penagihan_pembelian_id' => $tagihan_id,
                        'supplier_name' => $supplier_name,
                        'bayar_dari' => $this->input->post('bayar_dari_dp'),
                        'nomor_transaksi' => $this->input->post('nomor_transaksi_dp'),
                        'total' => $uang_muka,
                        'tanggal_pembayaran' => $tanggal_invoice,
                        'status' => 'Belum Disetujui',
                        'created_by' => $this->session->userdata('admin_id'),
                        'created_on' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('pmm_pembayaran_penagihan_pembelian', $arr_bayar);
                }
            }

            $arr_surat_jalan = explode(',', $surat_jalan);
            if (!empty($arr_surat_jalan)) {
                foreach ($arr_surat_jalan as $sj_id) {
                    $this->db->update('pmm_receipt_material', array('status_payment' => 'CREATING'), array('id' => $sj_id));
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            redirect('admin/pembelian#settings');
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Tagihan Pembelian');
            redirect('admin/pembelian#settings');
        }
    }


    public function approve_penawaran_pembelian($id)
    {
        $this->db->set("status", "OPEN");
        $this->db->where("id", $id);
        $this->db->update("pmm_penawaran_pembelian");

        $this->db->update('pmm_penawaran_pembelian_detail', array('status' => 'OPEN'), array('penawaran_pembelian_id' => $id));
        $this->session->set_flashdata('notif_success', 'Berhasil Menyetujui Penawaran Pembelian');
        redirect("admin/pembelian");
    }

    public function reject_penawaran_pembelian($id)
	{
		$this->db->set("status", "REJECT");
		$this->db->where("id", $id);
		$this->db->update("pmm_penawaran_pembelian");
		$this->session->set_flashdata('notif_success', 'Berhasil Menolak Penawaran Pembelian');
		redirect("admin/pembelian");
	}

    public function closed_penawaran_pembelian($id)
    {
        $this->db->set("status", "CLOSED");
        $this->db->set("updated_by", $this->session->userdata('admin_id'));
        $this->db->set("updated_on", date('Y-m-d H:i:s'));
        $this->db->where("id", $id);
        $this->db->update("pmm_penawaran_pembelian");

        $this->db->update('pmm_penawaran_pembelian_detail', array('status' => 'CLOSED'), array('penawaran_pembelian_id' => $id));
        $this->session->set_flashdata('notif_success', 'Berhasil Melakukan Closed Penawaran Pembelian');
        redirect("admin/pembelian");
    }

    public function hapus_penawaran_pembelian($id)
    {
        $this->db->trans_start(); # Starting Transaction

        $file = $this->db->select('pp.lampiran')
		->from('pmm_lampiran_penawaran_pembelian pp')
		->where("pp.penawaran_pembelian_id = $id")
		->get()->row_array();

		$path = './uploads/penawaran_pembelian/'.$file['lampiran'];
		chmod($path, 0777);
		unlink($path);
        
        $this->db->delete('pmm_lampiran_penawaran_pembelian', array('penawaran_pembelian_id' => $id));
        $this->db->delete('pmm_penawaran_pembelian_detail', array('penawaran_pembelian_id' => $id));
        $this->db->delete("pmm_penawaran_pembelian", array('id' => $id));

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error', 'Gagal Hapus Penawaran Pembelian');
            redirect('admin/pembelian');
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success', ' Berhasil Mengahapus Penawaran Pembelian');
            redirect("admin/pembelian");
        }
    }


    public function get_penagihan_pembelian()
    {
        $data = array();
        $id = $this->input->post('id');

        $this->db->select('pp.*, ps.nama as supplier_name, po.total as nilai_kontrak');
        $this->db->join('penerima ps', 'pp.supplier_id = ps.id', 'left');
        $this->db->join('pmm_purchase_order po', 'po.id = pp.purchase_order_id', 'left');
        $query = $this->db->get_where('pmm_penagihan_pembelian pp', array('pp.id' => $id))->row_array();

        $this->db->select('p.nama_produk');
		$this->db->join('produk p', 'ppd.material_id = p.id', 'left');
        $detail_produk = $this->db->get_where('pmm_penagihan_pembelian_detail ppd', ['penagihan_pembelian_id' => $id])->row_array();
        $query['nama_produk'] = $detail_produk['nama_produk'];

        $this->db->select('ppd.tax_id, sum(ppd.tax) as tax, p.nama_produk');
		$this->db->join('produk p', 'ppd.material_id = p.id', 'left');
        $this->db->where("ppd.tax_id in (3,6)");
        $detail = $this->db->get_where('pmm_penagihan_pembelian_detail ppd', ['penagihan_pembelian_id' => $id])->row_array();
        
        $query['ppn'] = $detail['tax'];

        $this->db->select('ppd.pajak_id, sum(ppd.pajak) as pajak, p.nama_produk');
		$this->db->join('produk p', 'ppd.material_id = p.id', 'left');
        $this->db->where("ppd.pajak_id in (5)");
        $detail_2 = $this->db->get_where('pmm_penagihan_pembelian_detail ppd', ['penagihan_pembelian_id' => $id])->row_array();
		
        $this->db->select('ppd.*');
        $penawaran = $this->db->get_where('pmm_purchase_order_detail ppd', ['purchase_order_id' => $query['purchase_order_id']])->row_array();

        $this->db->select('ppp.*');
        $metode_pembayaran = $this->db->get_where('pmm_penawaran_pembelian ppp', ['id' => $penawaran['penawaran_id']])->row_array();

        $query['pph'] = $detail_2['pajak'];
        $query['nilai_tagihan'] = $query['total_tagihan'] - $detail['tax'] + $detail_2['pajak'];
        $query['total_tagihan'] = $query['total_tagihan'] + $detail['tax'] - $detail_2['pajak'];

        if (!empty($query)) {
            $receipt_material_id = explode(',', $query['surat_jalan'])[0];
            $query['date_receipt'] = $this->crud_global->GetField('pmm_receipt_material', array('id' => $receipt_material_id), 'date_receipt');
            $query['date_receipt'] = date('d-m-Y', strtotime($query['date_receipt']));
            $query['tanggal_invoice'] = date('d-m-Y', strtotime($query['tanggal_invoice']));
            $query['tanggal_po'] = date('d-m-Y', strtotime($query['tanggal_po']));
            $query['metode_pembayaran'] = $metode_pembayaran['metode_pembayaran'];
            $data = $query;
        }
        echo json_encode(array('data' => $data));
    }


    public function get_verif_penagihan_pembelian()
    {
        $data = array();
        $id = $this->input->post('id');

        $data = $this->pmm_finance->getVerifDokumen($id);
        echo json_encode(array('data' => $data));
    }


    public function verif_dok_penagihan_pembelian()
    {
        $output['output'] = false;

        $penagihan_pembelian_id = $this->input->post('id');
        $arr_detail = $this->db->select('total,nomor_invoice,supplier_id,uang_muka,surat_jalan')->get_where('pmm_penagihan_pembelian', array('id' =>$penagihan_pembelian_id))->row_array();
        $nomor_invoice = $arr_detail['nomor_invoice'];
        $total = $arr_detail['total'];
        $coa_supp = $this->crud_global->GetField('pmm_supplier', array('id' => $arr_detail['supplier_id']), 'coa_id');

        $tanggal_invoice = $this->input->post('tanggal_invoice');
        if (!empty($tanggal_invoice)) {
            $tanggal_invoice = date('Y-m-d', strtotime($tanggal_invoice));
        } else {
            $tanggal_invoice = '-';
        }

        $tanggal_diterima_proyek = $this->input->post('tanggal_diterima_proyek');
        if ($tanggal_diterima_proyek) {
            $tanggal_diterima_proyek = date('Y-m-d', strtotime($tanggal_diterima_proyek));
        } else {
            $tanggal_diterima_proyek = NULL;
        }

        $tanggal_lolos_verifikasi = $this->input->post('tanggal_lolos_verifikasi');
        if ($tanggal_lolos_verifikasi) {
            $tanggal_lolos_verifikasi = date('Y-m-d', strtotime($tanggal_lolos_verifikasi));
        } else {
            $tanggal_lolos_verifikasi = NULL;
        }

        $tanggal_diterima_office = $this->input->post('tanggal_diterima_office');
        if ($tanggal_diterima_office) {
            $tanggal_diterima_office = date('Y-m-d', strtotime($tanggal_diterima_office));
        } else {
            $tanggal_diterima_office = NULL;
        }

        $data = array(
            'penagihan_pembelian_id' => $this->input->post('id'),
            'nomor_po' => $this->input->post('nomor_po'),
            'tanggal_po' => date('Y-m-d', strtotime($this->input->post('tanggal_po'))),
            'nama_barang_jasa' => $this->input->post('nama_barang_jasa'),
            'nilai_kontrak' => $this->input->post('nilai_kontrak'),
            'nilai_tagihan' => $this->input->post('nilai_tagihan'),
            'ppn' => $this->input->post('ppn'),
            'pph' => $this->input->post('pph'),
            'tanggal_invoice' => $tanggal_invoice,
            'tanggal_diterima_proyek' => $tanggal_diterima_proyek,
            'tanggal_lolos_verifikasi' => $tanggal_lolos_verifikasi,
            'status_umur_hutang' => $tanggal_lolos_verifikasi,
            //'tanggal_diterima_office' => $tanggal_diterima_office,
            'metode_pembayaran' => $this->input->post('metode_pembayaran'),
            'invoice' => $this->input->post('invoice'),
            'invoice_keterangan' => $this->input->post('invoice_keterangan'),
            'kwitansi' => $this->input->post('kwitansi'),
            'kwitansi_keterangan' => $this->input->post('kwitansi_keterangan'),
            'faktur' => $this->input->post('faktur'),
            'faktur_keterangan' => $this->input->post('faktur_keterangan'),
            'bap' => $this->input->post('bap'),
            'bap_keterangan' => $this->input->post('bap_keterangan'),
            'bast' => $this->input->post('bast'),
            'bast_keterangan' => $this->input->post('bast_keterangan'),
            'surat_jalan' => $this->input->post('surat_jalan'),
            'surat_jalan_keterangan' => $this->input->post('surat_jalan_keterangan'),
            'copy_po' => $this->input->post('copy_po'),
            'copy_po_keterangan' => $this->input->post('copy_po_keterangan'),
            'catatan' => $this->input->post('catatan'),
            'kategori_persetujuan' => 'VERIFIKASI PEMBELIAN',
            'approve_unit_head' => 'TIDAK DISETUJUI',
            'created_by' => $this->session->userdata('admin_id')
        );

        $dir = "uploads/verifikasi_dokumen/";

        if (!file_exists($dir)) {
            mkdir($dir, 777, true);
        }

        $invoice_file = $this->input->post('invoice_file');
        $kwitansi_file = $this->input->post('kwitansi_file');
        $faktur_file = $this->input->post('faktur_file');
        $bap_file = $this->input->post('bap_file');
        $bast_file = $this->input->post('bast_file');
        $surat_jalan_file = $this->input->post('surat_jalan_file');
        $copy_po_file = $this->input->post('copy_po_file');


        if (isset($invoice_file) && !empty($invoice_file)) {
            list($fileName, $base64) = explode("|", $invoice_file);

            $fileContent = base64_decode($base64);

            $filePath = $dir . uniqid() . "-" . $fileName;

            file_put_contents($filePath, $fileContent);
            $data['invoice_file'] = $filePath;
        }

        if (isset($kwitansi_file) && !empty($kwitansi_file)) {
            list($fileName, $base64) = explode("|", $kwitansi_file);

            $fileContent = base64_decode($base64);

            $filePath = $dir . uniqid() . "-" . $fileName;

            file_put_contents($filePath, $fileContent);
            $data['kwitansi_file'] = $filePath;
        }

        if (isset($faktur_file) && !empty($faktur_file)) {
            list($fileName, $base64) = explode("|", $faktur_file);

            $fileContent = base64_decode($base64);

            $filePath = $dir . uniqid() . "-" . $fileName;

            file_put_contents($filePath, $fileContent);
            $data['faktur_file'] = $filePath;
        }

        if (isset($bap_file) && !empty($bap_file)) {
            list($fileName, $base64) = explode("|", $bap_file);

            $fileContent = base64_decode($base64);

            $filePath = $dir . uniqid() . "-" . $fileName;

            file_put_contents($filePath, $fileContent);
            $data['bap_file'] = $filePath;
        }

        if (isset($bast_file) && !empty($bast_file)) {
            list($fileName, $base64) = explode("|", $bast_file);

            $fileContent = base64_decode($base64);

            $filePath = $dir . uniqid() . "-" . $fileName;

            file_put_contents($filePath, $fileContent);
            $data['bast_file'] = $filePath;
        }

        if (isset($surat_jalan_file) && !empty($surat_jalan_file)) {
            list($fileName, $base64) = explode("|", $surat_jalan_file);

            $fileContent = base64_decode($base64);

            $filePath = $dir . uniqid() . "-" . $fileName;

            file_put_contents($filePath, $fileContent);
            $data['surat_jalan_file'] = $filePath;
        }

        if (isset($copy_po_file) && !empty($copy_po_file)) {
            list($fileName, $base64) = explode("|", $copy_po_file);

            $fileContent = base64_decode($base64);

            $filePath = $dir . uniqid() . "-" . $fileName;

            file_put_contents($filePath, $fileContent);
            $data['copy_po_file'] = $filePath;
        }

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 



        if ($this->db->insert('pmm_verifikasi_penagihan_pembelian', $data)) {

            $this->db->update('pmm_penagihan_pembelian', array('verifikasi_dok' => 'SUDAH'), array('id' => $this->input->post('id')));

            // Insert COA
            $coa_1 = '';
            $coa_description = 'Penagihan Pembelian Nomor ' . $nomor_invoice;
            $this->pmm_finance->InsertTransactions(39, $coa_description, $total, 0);
            $transaction_id = $this->db->insert_id();
            $coa_1 .= $transaction_id;
            if (!empty($coa_supp) || $coa_supp > 0) {
                $this->pmm_finance->InsertTransactions($coa_supp, $coa_description, 0, $total);
                $transaction_id = $this->db->insert_id();
                $coa_1 .= ',' . $transaction_id;
            }

            // update transaction 
            //$this->db->update('pmm_penagihan_pembelian', array('transaction_id' => $coa_1), array('id' => $penagihan_pembelian_id));

            if ($arr_detail['uang_muka'] > 0) {
                if (!empty($coa_supp) || $coa_supp > 0) {
                    $coa_2 = '';
                    // $this->pmm_finance->InsertTransactions($coa_supp,'Uang Muka '.$coa_description,$arr_detail['uang_muka'],0);
                    $pembayaran_panagihan = $this->db->get_where('pmm_pembayaran_penagihan_pembelian', array('penagihan_pembelian_id' => $penagihan_pembelian_id))->row_array();


                    $this->pmm_finance->InsertTransactions($pembayaran_panagihan['bayar_dari'], 'Uang Muka ' . $coa_description, 0, $arr_detail['uang_muka']);
                    $transaction_id = $this->db->insert_id();
                    $coa_2 .= $transaction_id;
                    $this->pmm_finance->InsertTransactions($coa_supp, 'Uang Muka ' . $coa_description, $arr_detail['uang_muka'], 0);
                    $transaction_id = $this->db->insert_id();
                    $coa_2 .= ',' . $transaction_id;

                    // update pembayaran
                    $this->db->update('pmm_pembayaran_penagihan_pembelian', array('status' => 'Disetujui'), array('id' => $pembayaran_panagihan['id']));
                }
            }

            $arr_surat_jalan = explode(',', $arr_detail['surat_jalan']);
            if (!empty($arr_surat_jalan)) {
                foreach ($arr_surat_jalan as $sj_id) {
                    $this->db->update('pmm_receipt_material', array('status_payment' => 'CREATED'), array('id' => $sj_id));
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $output['output'] = false;
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $output['output'] = true;
        }

        echo json_encode($output);
    }


    function print_verifikasi_penagihan_pembelian()
    {
        $this->load->library('pdf');

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica', '', 7);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
        $pdf->setHtmlVSpace($tagvs);
        $pdf->AddPage('P');

        $arr_data = array();
        $id = $this->input->get('id');
        if (!empty($id)) {

            $data['row'] = $this->pmm_finance->getVerifDokumenById($id);
            $html = $this->load->view('pembelian/print_verifikasi_penagihan_pembelian', $data, TRUE);

            $pdf->SetTitle('Verifikasi Dokumen Penagihan Pembelian');
            $pdf->nsi_html($html);
            $pdf->Output('Verifikasi-Dokumen-Penagihan-Pembelian.pdf', 'I');
        } else {
            echo 'Please Filter Date First';
        }
    }


    public function delete_penagihan_pembelian($id)
    {
        if (!empty($id)) {

            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

            $penagihan_pembelian = $this->db->get_where('pmm_penagihan_pembelian', array('id' => $id))->row_array();
            $deskripsi = 'Nomor Invoice ' . $penagihan_pembelian['nomor_invoice'];
            $this->db->delete('pmm_penagihan_pembelian_detail', array('penagihan_pembelian_id' => $id));

            $invoice_file = $this->db->select('lk.invoice_file')
            ->from('pmm_verifikasi_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path_1 = $invoice_file['invoice_file'];
            chmod($path_1, 0777);
            unlink($path_1);

            $kwitansi_file = $this->db->select('lk.kwitansi_file')
            ->from('pmm_verifikasi_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path_2 = $kwitansi_file['kwitansi_file'];
            chmod($path_2, 0777);
            unlink($path_2);

            $faktur_file = $this->db->select('lk.faktur_file')
            ->from('pmm_verifikasi_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path_3 = $faktur_file['faktur_file'];
            chmod($path_3, 0777);
            unlink($path_3);

            $bap_file = $this->db->select('lk.bap_file')
            ->from('pmm_verifikasi_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path_4 = $bap_file['bap_file'];
            chmod($path_4, 0777);
            unlink($path_4);

            $bast_file = $this->db->select('lk.bast_file')
            ->from('pmm_verifikasi_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path_5 = $bast_file['bast_file'];
            chmod($path_5, 0777);
            unlink($path_5);

            $surat_jalan_file = $this->db->select('lk.surat_jalan_file')
            ->from('pmm_verifikasi_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path_6 = $surat_jalan_file['surat_jalan_file'];
            chmod($path_6, 0777);
            unlink($path_6);

            $copy_po_file = $this->db->select('lk.copy_po_file')
            ->from('pmm_verifikasi_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path_7 = $copy_po_file['copy_po_file'];
            chmod($path_7, 0777);
            unlink($path_7);

            $this->db->delete('pmm_verifikasi_penagihan_pembelian', array('penagihan_pembelian_id' => $id));

            $file = $this->db->select('lk.lampiran')
            ->from('pmm_lampiran_penagihan_pembelian lk')
            ->where("lk.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $path = './uploads/penagihan_pembelian/'.$file['lampiran'];
            chmod($path, 0777);
            unlink($path);

            $this->db->delete('pmm_lampiran_penagihan_pembelian', array('penagihan_pembelian_id' => $id));

            $pembayaran_pembelian = $this->db->select('pp.id')
            ->from('pmm_pembayaran_penagihan_pembelian pp')
            ->where("pp.penagihan_pembelian_id = $id")
            ->get()->row_array();

            $pembayaran_pembelian_id = $pembayaran_pembelian['id'];

            $file = $this->db->select('pp.lampiran')
            ->from('pmm_lampiran_pembayaran_penagihan_pembelian pp')
            ->where("pp.pembayaran_penagihan_pembelian_id = $pembayaran_pembelian_id")
            ->get()->row_array();

            $path = './uploads/pembayaran_penagihan_pembelian/'.$file['lampiran'];
            chmod($path, 0777);
            unlink($path);

            $this->db->delete('pmm_pembayaran_penagihan_pembelian', array('penagihan_pembelian_id' => $id));
            $this->db->delete('pmm_penagihan_pembelian', array('id' => $id));

            $arr_detail = explode(',', $penagihan_pembelian['surat_jalan']);
            if (!empty($arr_detail)) {
                foreach ($arr_detail as $key => $rm) {
                    $this->db->update('pmm_receipt_material', array('status_payment' => 'UNCREATED'), array('id' => $rm));
                }
            }


            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                $this->session->set_flashdata('notif_error', 'Gagal Menghapus Tagihan Pembelian !!');
                redirect('pembelian#settings' . $id);
            } else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                $this->session->set_flashdata('notif_success', 'Berhasil Menghapus Tagihan Pembelian !!');
                redirect('admin/pembelian');
            }
        }
    }


    public function add_material()
    {
        $no = $this->input->post('no');
        $nama = $this->input->post('nama');
        $products = $this->db->order_by('nama_produk', 'asc')->select('*')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
        $taxs = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
        $taxs_2 = $this->db->select('id,tax_name')
        ->from('pmm_taxs')
        ->where("id in ('4','5')")
        ->get()->result_array();
		$measures= $this->db->get_where('pmm_measures', array('status' => 'PUBLISH'))->result_array();
	?>
        <tr>
            <td><?php echo $no; ?>.</td>
            <td>
				<select id="product-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control form-select2" name="product_<?php echo $no; ?>" required="">
					<option value="">Pilih Produk</option>
					<?php
					if (!empty($products)) {
						foreach ($products as $row) {
							$satuan = $this->crud_global->GetField('pmm_measures', array('id' => $row['satuan']), 'measure_name');
					?>
							<option value="<?php echo $row['id'];?>" data-satuan="<?= $satuan;?>" data-price="<?php echo $row['harga_jual'];?>"><?php echo $row['nama_produk'];?></option>
					<?php
						}
					}
					?>
				</select>
			</td>
            <td>
				<input type="number" min="0" name="qty_<?php echo $no; ?>" id="qty-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control input-sm text-center" required=""/>
			</td>
			<td>
				<select id="measure-<?php echo $no; ?>" class="form-control form-select2" name="measure_<?php echo $no; ?>" required="">
						<option value="">Pilih Satuan</option>
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
				<input type="text" name="price_<?php echo $no; ?>" id="price-<?php echo $no; ?>" class="form-control numberformat tex-left input-sm text-right" onchange="changeData(<?php echo $no; ?>)" required=""/>
			</td>
			<td>
				<select id="tax-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control form-select2" name="tax_<?php echo $no; ?>" required="">
					<option value="">Pilih Pajak</option>
					<?php
					if (!empty($taxs)) {
						foreach ($taxs as $row) {
					?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['tax_name']; ?></option>
					<?php
						}
					}
					?>
				</select>
			</td>
            <td>
				<select id="pajak-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control form-select2" name="pajak_<?php echo $no; ?>" required="">
					<option value="">Pilih Pajak (2)</option>
					<?php
					if (!empty($taxs_2)) {
						foreach ($taxs_2 as $row) {
					?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['tax_name']; ?></option>
					<?php
						}
					}
					?>
				</select>
			</td>
			<td>
				<input type="text" name="total_<?php echo $no; ?>" id="total-<?php echo $no; ?>" class="form-control numberformat tex-left input-sm text-right" readonly=""/>
			</td>
		</tr>

        <script type="text/javascript">
            $('.form-select2').select2();
            $('input.numberformat').number(true, 0, ',', '.');
        </script>
    <?php
    }


    public function sales_po()
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {
            $data['clients'] = $this->db->order_by('nama', 'asc')->select('*')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => 1))->result_array();
            $data['products'] = $this->db->select('*')->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
            $data['taxs'] = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
            $this->load->view('pembelian/sales_po', $data);
        } else {
            redirect('admin');
        }
    }


    public function get_client_address()
    {

        $id = $this->input->post('id');
        if (!empty($id)) {
            $query = $this->crud_global->GetField('penerima', array('id' => $id), 'alamat');
            echo $query;
        }
    }


    public function add_product_po()
    {
        $no = $this->input->post('no');
        $products = $this->db->select('*')->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH'))->result_array();
        $taxs = $this->db->select('id,tax_name')->get_where('pmm_taxs', array('status' => 'PUBLISH'))->result_array();
    ?>
        <tr>
            <td><?php echo $no; ?>.</td>
            <td>
                <select id="product-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control form-select2" name="product_<?php echo $no; ?>">
                    <option value="">.. Pilih Produk ..</option>
                    <?php
                    if (!empty($products)) {
                        foreach ($products as $row) {
                    ?>
                            <option value="<?php echo $row['id']; ?>" data-price="<?php echo $row['harga_beli']; ?>"><?php echo $row['nama_produk']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="number" min="0" name="qty_<?php echo $no; ?>" id="qty-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control input-sm text-center" />
            </td>
            <td>
                <input type="text" value="" id="measure-<?= $no; ?>" name="measure_<?php echo $no; ?>" class="form-control text-center input-sm" readonly />
            </td>
            <td>
                <input type="text" name="price_<?php echo $no; ?>" id="price-<?php echo $no; ?>" class="form-control numberformat tex-left input-sm text-right" onchange="changeData(<?php echo $no; ?>)" />
            </td>
            <td>
                <select id="tax-<?php echo $no; ?>" onchange="changeData(<?php echo $no; ?>)" class="form-control form-select2" name="tax_<?php echo $no; ?>">
                    <option value="">.. Pilih Pajak ..</option>
                    <?php
                    if (!empty($taxs)) {
                        foreach ($taxs as $row) {
                    ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['tax_name']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" name="total_<?php echo $no; ?>" id="total-<?php echo $no; ?>" class="form-control numberformat tex-left input-sm text-right" readonly="" />
            </td>
            <td><button class="btn btn-xs btn-danger"><i class="fa fa-close"></i></button></td>
        </tr>

        <script type="text/javascript">
            $('.form-select2').select2();
            $('input.numberformat').number(true, 2, ',', '.');
        </script>
<?php
    }

    public function table_pembayaran_penagihan_pembelian($id)
    {
        $data = array();


        $query = $this->db->get_where('pmm_pembayaran_penagihan_pembelian', array('penagihan_pembelian_id' => $id));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key + 1;
                // $row['coa'] = '' 
                $row['saldo'] = 0;
                $row['bayar_dari'] = $this->crud_global->GetField('pmm_coa', array('id' => $row['bayar_dari']), 'coa');
                $row['tanggal_pembayaran'] = date('d/m/Y', strtotime($row['tanggal_pembayaran']));
                $row['total_pembayaran'] = number_format($row["total"], 2, ',', '.');
                if ($row["status"] === "DISETUJUI") {
                    $row['action'] = '<a href="' . base_url('pembelian/cetak_pembayaran_penagihan_pembelian/' . $row["id"]) . '" target="_blank" class="btn btn-success">Cetak PDF</a>';
                } else if($row["status"] == 'TIDAK DISETUJUI'){
                    $row['action'] = "BUTUH PERSETUJUAN";
                    if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 13 || $this->session->userdata('admin_group_id') == 14){
                    $url_approve = "'" . base_url('pembelian/update_status_pembayaran_penagihan_pembelian/' . $row["id"]) . "'";
                    $row['action'] = '<a href="javascript:void(0);" onclick="ApprovePayment(' . $row["id"] . ')" class="btn btn-success">SETUJUI</a>';
                    }
                }
                $data[] = $row;
            }
        }
        echo json_encode(array('data' => $data));
    }

    public function approve_payment()
    {

        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $this->db->update('pmm_pembayaran_penagihan_pembelian', ['status' => 'DISETUJUI'
        ]);
        $this->session->set_flashdata('notif_success', 'Berhasil Menyetujui Pembayaran !!');
    }

    public function pembayaran_panagihan($id)
    {
        $check = $this->m_admin->check_login();
        if ($check == true) {

            $this->db->select('pp.*, ps.nama as supplier_name');
            $this->db->join('penerima ps', 'pp.supplier_id = ps.id', 'left');
            $data['pembayaran'] = $this->db->get_where('pmm_penagihan_pembelian pp', ["pp.id" => $id])->row_array();
            $data['total_bayar'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran_penagihan_pembelian', array('penagihan_pembelian_id' => $id))->row_array();
            $data['dpp'] = $this->db->select("SUM(total) as total")->get_where('pmm_penagihan_pembelian_detail ppd', ["ppd.penagihan_pembelian_id" => $id])->row_array();
            $data['tax'] = $this->db->select("SUM(tax) as total")->get_where('pmm_penagihan_pembelian_detail ppd', ["ppd.penagihan_pembelian_id" => $id])->row_array();

            // Setor Bank
            $data['setor_bank'] = $this->pmm_finance->BankCash();
            $this->load->view('pembelian/pembayaran_penagihan_pembelian', $data);
        } else {
            redirect('admin');
        }
    }

    public function submit_pembayaran_penagihan_pembelian()
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); #

        $pembayaran_pro = $this->input->post('pembayaran');
        $pembayaran_pro = str_replace('.', '', $pembayaran_pro);
        $pembayaran_pro = str_replace(',', '.', $pembayaran_pro);

        $penagihan_pembelian_id = $this->input->post('penagihan_pembelian_id');
        $arr_insert = array(
            'penagihan_pembelian_id' => $penagihan_pembelian_id,
            'supplier_name' => $this->input->post('supplier_name'),
            'bayar_dari' => $this->input->post('bayar_dari'),
            'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
            'nomor_transaksi' => $this->input->post('nomor_transaksi'),
            'cek_nomor' => $this->input->post('cek_nomor'),
            'memo' => $this->input->post('memo'),
            'status' => 'TIDAK DISETUJUI',
            'total' => $pembayaran_pro,
            'created_by' => $this->session->userdata('admin_id'),
            'created_on' => date('Y-m-d H:i:s')
        );

        if ($this->db->insert('pmm_pembayaran_penagihan_pembelian', $arr_insert)) {
            $pembayaran_id = $this->db->insert_id();

            if (!file_exists('uploads/pembayaran_penagihan_pembelian')) {
			    mkdir('uploads/pembayaran_penagihan_pembelian', 0777, true);
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

                    $config['upload_path'] = 'uploads/pembayaran_penagihan_pembelian';
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config['file_name'] = $_FILES['files']['name'][$i];

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('file')) {
                        $uploadData = $this->upload->data();
                        $filename = $uploadData['file_name'];

                        $data['totalFiles'][] = $filename;


                        $data[$i] = array(
                            'pembayaran_penagihan_pembelian_id' => $pembayaran_id,
                            'lampiran'  => $data['totalFiles'][$i]
                        );

                        $this->db->insert('pmm_lampiran_pembayaran_penagihan_pembelian', $data[$i]);
                    }
                }
            }
        }


        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error', 'Gagal Menambahkan Data Pembayaran !!');
            redirect('pembelian/penagihan_pembelian_detail/' . $penagihan_pembelian_id);
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Data Pembayaran !!');
            redirect('pembelian/penagihan_pembelian_detail/' . $penagihan_pembelian_id);
        }
    }
    
	public function view_pembayaran_pembelian($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {

			$data['bayar'] = $this->db->get_where('pmm_pembayaran_penagihan_pembelian', ["id" => $id])->row_array();
            $data['pembayaran'] = $this->db->get_where('pmm_penagihan_pembelian', ["id" => $data['bayar']['penagihan_pembelian_id']])->row_array();
            $data['total_bayar'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran_penagihan_pembelian', array('id' => $id))->row_array();
            $data['total_bayar_all'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran_penagihan_pembelian', array('penagihan_pembelian_id' => $data['bayar']['penagihan_pembelian_id']))->row_array();
            $data['dpp'] = $this->db->select("SUM(total) as total")->get_where('pmm_penagihan_pembelian_detail ppd', ["ppd.penagihan_pembelian_id" => $data['bayar']['penagihan_pembelian_id']])->row_array();
            $data['tax'] = $this->db->select("SUM(tax) as total")->get_where('pmm_penagihan_pembelian_detail ppd', ["ppd.penagihan_pembelian_id" => $data['bayar']['penagihan_pembelian_id']])->row_array();
            $data['dataLampiran'] = $this->db->get_where('pmm_lampiran_pembayaran_penagihan_pembelian', ["pembayaran_penagihan_pembelian_id" => $id])->result_array();
			
            // Setor Bank
			$this->db->select('c.*');
			$this->db->where('c.coa_category', 3);
			$this->db->where('c.status', 'PUBLISH');
			$this->db->order_by('c.coa_number', 'asc');
			$query = $this->db->get('pmm_coa c');
			$data['setor_bank'] = $query->result_array();
			$this->load->view('pembelian/view_pembayaran_pembelian', $data);
		} else {
			redirect('admin');
		}
	}
			
    public function cetak_pembayaran_penagihan_pembelian($id)
    {

        $this->load->library('pdf');


        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica', '', 7);
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
        $pdf->setHtmlVSpace($tagvs);
        $pdf->AddPage('P');

        $this->db->select('pp.*');
        //$this->db->join('penerima ps', 'pp.supplier_name = ps.id', 'left');
        $data['pembayaran'] = $this->db->get_where('pmm_pembayaran_penagihan_pembelian pp', array('pp.id' => $id))->row_array();
        $data['bayar_dari'] = $this->crud_global->GetField('pmm_coa', array('id' => $data['pembayaran']['bayar_dari']), 'coa');
        $id_penagihan = $data['pembayaran']['penagihan_pembelian_id'];
        $html = $this->load->view('pembelian/cetak_pembayaran_penagihan_pembelian', $data, TRUE);


        $pdf->SetTitle('bukti-penerimaan-pembelian');
        $pdf->nsi_html($html);
        $pdf->Output('bukti-penerimaan-pembelian.pdf', 'I');
    }

    public function sunting_pembayaran_pembelian($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {

			$data['bayar'] = $this->db->get_where('pmm_pembayaran_penagihan_pembelian', ["id" => $id])->row_array();
            $data['pembayaran'] = $this->db->get_where('pmm_penagihan_pembelian', ["id" => $data['bayar']['penagihan_pembelian_id']])->row_array();
            $data['total_bayar'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran_penagihan_pembelian', array('id' => $id))->row_array();
            $data['total_bayar_all'] = $this->db->select("SUM(total) as total")->get_where('pmm_pembayaran_penagihan_pembelian', array('penagihan_pembelian_id' => $data['bayar']['penagihan_pembelian_id']))->row_array();
            $data['dpp'] = $this->db->select("SUM(total) as total")->get_where('pmm_penagihan_pembelian_detail ppd', ["ppd.penagihan_pembelian_id" => $data['bayar']['penagihan_pembelian_id']])->row_array();
            $data['tax'] = $this->db->select("SUM(tax) as total")->get_where('pmm_penagihan_pembelian_detail ppd', ["ppd.penagihan_pembelian_id" => $data['bayar']['penagihan_pembelian_id']])->row_array();

			// Setor Bank
			$this->db->select('c.*');
			$this->db->where('c.coa_category', 3);
			$this->db->where('c.status', 'PUBLISH');
			$this->db->order_by('c.coa_number', 'asc');
			$query = $this->db->get('pmm_coa c');
			$data['setor_bank'] = $query->result_array();
			$this->load->view('pembelian/sunting_pembayaran_pembelian', $data);
		} else {
			redirect('admin');
		}
	}

    public function simpan_pembayaran_pembelian()
	{

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); #

		try {

			$id = $this->input->post('id');

			$pembayaran_pro = $this->input->post('pembayaran');
			$pembayaran_pro = str_replace('.', '', $pembayaran_pro);
			$pembayaran_pro = str_replace(',', '.', $pembayaran_pro);


			$arr_update = array(
				'penagihan_pembelian_id' => $this->input->post('id_penagihan'),
				'supplier_name' => $this->input->post('supplier_name'),
				'bayar_dari' => $this->input->post('bayar_dari'),
				'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
				'nomor_transaksi' => $this->input->post('nomor_transaksi'),
                'cek_nomor' => $this->input->post('cek_nomor'),
				'memo' => $this->input->post('memo'),
				'total' => $pembayaran_pro,
				'created_by' => $this->session->userdata('admin_id'),
				'created_on' => date('Y-m-d H:i:s')
			);

			$this->db->where('id', $id);
			if ($this->db->update('pmm_pembayaran_penagihan_pembelian', $arr_update)) {
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

						$config['upload_path'] = 'uploads/pembayaran_penagihan_pembelian';
						$config['allowed_types'] = 'jpg|jpeg|png|pdf';
						$config['file_name'] = $_FILES['files']['name'][$i];

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('file')) {
							$uploadData = $this->upload->data();
							$filename = $uploadData['file_name'];

							$data['totalFiles'][] = $filename;


							$data[$i] = array(
								'pembayaran_penagihan_pembelian_id' => $id,
								'lampiran'  => $data['totalFiles'][$i]
							);

							$this->db->insert('pmm_lampiran_pembayaran_penagihan_pembelian', $data[$i]);
						}
					}
				}
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error', 'Gagal Menambahkan Data Pembayaran !!');
				redirect('pembelian/halaman_pembayaran/' . $this->input->post('id_penagihan'));
			} else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success', 'Berhasil Menambahkan Data Pembayaran !!');
				redirect('pembelian/penagihan_pembelian_detail/' . $this->input->post('id_penagihan'));
			}
		} catch (Throwable $e) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', $e->getMessage());
			redirect('pembelian/halaman_pembayaran/' . $this->input->post('id_penagihan'));
		}
	}

    public function closed_po($id)
    {
        $this->db->set("status", "CLOSED");
        $this->db->set("updated_by", $this->session->userdata('admin_id'));
        $this->db->set("updated_on", date('Y-m-d H:i:s'));
        $this->db->where("id", $id);
        $this->db->update("pmm_purchase_order");
        $this->session->set_flashdata('notif_success', 'Berhasil Melakukan Closed PO');
        redirect("admin/pembelian");
    }

    public function hapus_pembayaran_pembelian($id)
	{

        $file = $this->db->select('pp.lampiran')
		->from('pmm_lampiran_pembayaran_penagihan_pembelian pp')
		->where("pp.pembayaran_penagihan_pembelian_id = $id")
		->get()->row_array();

		$path = './uploads/pembayaran_penagihan_pembelian/'.$file['lampiran'];
		chmod($path, 0777);
		unlink($path);

        $this->db->delete('pmm_lampiran_pembayaran_penagihan_pembelian', array('pembayaran_penagihan_pembelian_id' => $id));

        $penagihan_pembelian_id = $this->db->select('(ppp.id) as id')
        ->from('pmm_pembayaran_penagihan_pembelian pppp')
        ->join('pmm_penagihan_pembelian ppp', 'pppp.penagihan_pembelian_id = ppp.id','left')
        ->where('pppp.id', $id)
        ->get()->row_array();

		$this->db->set("status", "BELUM LUNAS");
        $this->db->set("verifikasi_dok", "SUDAH");
        $this->db->where('id', $penagihan_pembelian_id['id']);
        $this->db->update('pmm_penagihan_pembelian');

        $this->db->where('id', $id);

        
		$this->db->delete('pmm_pembayaran_penagihan_pembelian');
	}

    public function closed_pembayaran_penagihan($id)
	{
		$this->db->set("status", "LUNAS");
        $this->db->set("updated_by", $this->session->userdata('admin_id'));
        $this->db->set("updated_on", date('Y-m-d H:i:s'));
        $this->db->set("verifikasi_dok", "LENGKAP");
		$this->db->where("id", $id);
		$this->db->update("pmm_penagihan_pembelian");

        $this->db->set("status_umur_hutang", "null", false);
		$this->db->where("penagihan_pembelian_id", $id);
		$this->db->update("pmm_verifikasi_penagihan_pembelian");

        $this->session->set_flashdata('notif_success', 'Berhasil Menyelesaikan Penagihan');

		redirect("pembelian/penagihan_pembelian_detail/$id");
	}

    public function open_penagihan($id)
	{
		$this->db->set("status", "BELUM LUNAS");
        $this->db->set("verifikasi_dok", "SUDAH");
        $this->db->set("updated_by", $this->session->userdata('admin_id'));
        $this->db->set("updated_on", date('Y-m-d H:i:s'));
		$this->db->where("id", $id);
		$this->db->update("pmm_penagihan_pembelian");
		$this->session->set_flashdata('notif_success', 'Berhasil Merubah Status Penagihan');
		redirect("pembelian/penagihan_pembelian_detail/$id");
	}

    public function sunting_tagihan($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {

			$this->db->select('ppp.*');
            $data['row'] = $this->db->get_where('pmm_penagihan_pembelian ppp', array('ppp.id' => $id))->row_array();
			$this->load->view('pembelian/sunting_tagihan', $data);
		} else {
			redirect('admin');
		}
	}

    public function main_table()
	{	
		$data = $this->pmm_model->TableMainTagihan($this->input->post('id'));
		echo json_encode(array('data'=>$data));
	}

    public function get_tagihan_main()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
            $data = $this->db->select('ppp.*')
            ->from('pmm_penagihan_pembelian ppp')
            ->where('ppp.id',$id)
            ->get()->row_array();

            $data['nama']= $this->crud_global->GetField('penerima',array('id'=>$data['supplier_id']),'nama');
            $data['tanggal_invoice'] = date('d-m-Y',strtotime($data['tanggal_invoice']));
			$output['output'] = $data;
            
		}
		echo json_encode($output);
	}

    public function update_tagihan_main()
	{
		$output['output'] = false;

		$penagihan_id = $this->input->post('penagihan_id');
        $supplier_id = $this->input->post('nama');
		$tanggal_invoice = date('Y-m-d',strtotime($this->input->post('tanggal_invoice')));
        $nomor_invoice = $this->input->post('nomor_invoice');

		$data = array(
            'id' => $penagihan_id,
		    'tanggal_invoice' => $tanggal_invoice,
		);

		if(!empty($id)){
			if($this->db->update('pmm_penagihan_pembelian',$data,array('id'=>$penagihan_id))){
				$output['output'] = true;
			}
		}else{
            $data['updated_by'] = $this->session->userdata('admin_id');
            $data['updated_on'] = date('Y-m-d H:i:s');
			if($this->db->update('pmm_penagihan_pembelian',$data,array('id'=>$penagihan_id))){
				$output['output'] = true;
			}
		}

        $data_verif = array(
            'penagihan_pembelian_id' => $penagihan_id,
		    'tanggal_invoice' => $tanggal_invoice,
		);

        if(!empty($penagihan_id)){
			if($this->db->update('pmm_verifikasi_penagihan_pembelian',$data_verif,array('penagihan_pembelian_id'=>$penagihan_id))){
				$output['output'] = true;
			}
		}
		
		echo json_encode($output);	
	}

    public function read_notification($id)
    {
        $check = $this->m_admin->check_login();
        if($check == true){

            $this->db->select('pvp.*, ppp.nomor_invoice');
            $this->db->join('pmm_penagihan_pembelian ppp', 'pvp.penagihan_pembelian_id = ppp.id','left');
            $this->db->where('pvp.id',$id);
			$this->db->order_by('pvp.created_on', 'DESC');
            $query = $this->db->get('pmm_verifikasi_penagihan_pembelian pvp');
            $data['row'] = $query->result_array();
            $this->load->view('pembelian/read_notification',$data);
            
        }else {
            redirect('admin');
        }
    }

    public function closed_verifikasi($id)
	{
		$this->db->set("approve_unit_head", "SETUJUI");
		$this->db->where("id", $id);
		$this->db->update("pmm_verifikasi_penagihan_pembelian");
		$this->session->set_flashdata('notif_success', 'Berhasil Menutup Verifikasi');
		redirect("admin/pembelian");
	}
}
