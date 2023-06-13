<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends Secure_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm/pmm_model','admin/Templates','pmm/pmm_finance','m_produk'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
    }	


    public function table_product()
	{	
		$data = array();
		$tipe = $this->input->post('tipe');;
		if($tipe == 1){
			$this->db->where('bahanbaku',1);
		}else if($tipe == 2){
			$this->db->where('betonreadymix',1);
		}else if($tipe == 3){
			$this->db->where('aggregat',1);
		}else if($tipe == 4){
			$this->db->where('jasa',1);
		}else if($tipe == 5){
			$this->db->where('peralatan',1);
		}else if($tipe == 6){
			$this->db->where('bahanbakar',1);
		}else if($tipe == 7){
			$this->db->where('laboratorium',1);
		}else if($tipe == 8){
			$this->db->where('asset',1);
		}
		
		$this->db->where('status','PUBLISH');
		$this->db->order_by('nama_produk','asc');
		$query = $this->db->get('produk');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['harga_jual'] = $this->filter->Rupiah($row['harga_jual']);
				$row['harga_beli'] = $this->filter->Rupiah($row['harga_beli']);
				$row['nama_produk'] = '<a href="'.site_url('produk/detail/'.$row['id']).'" >'.$row['nama_produk'].'</a>';
				$row['satuan'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['satuan']),'measure_name');
				$row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}
    

    public function buat_baru()
    {

    	$id = $this->uri->segment(3);
    	$data['kategori'] = $this->m_produk->getKategori();
    	$data['taxs'] = $this->m_produk->getTax();
    	$data['akun'] = $this->pmm_finance->getAkunCoa();
    	$data['satuan'] = $this->m_produk->getSatuan();

    	if(!empty($id)){
    		$data['edit'] = $this->db->get_where('produk',array('id'=>$id))->row_array();
    	}
		$this->load->view('produk/add',$data);
    }



    public function tambah_kategori_produk()
    {
    	$output['output'] = false;

        $nama_kategori_produk = $this->input->post('nama_kategori_produk');
        $status = 'PUBLISH';

        $data = array(
            'nama_kategori_produk' => $nama_kategori_produk,
            'status' => $status
        );

        $data['created_by'] = $this->session->userdata('admin_id');
        $data['created_on'] = date('Y-m-d H:i:s');
        if($this->db->insert('kategori_produk',$data)){
            $output['output'] = true;

            $query = $this->m_produk->getKategori();
            $data = array();
            $data[0] = array('id'=>'','text'=>'Pilih Kategori');
            foreach ($query as $key => $row) {

                $data[] = array('id'=>$row['id'],'text'=>$row['nama_kategori_produk']);
            }
            $output['data'] = $data;
        }   
        
        echo json_encode($output); 
    }

    public function form_produk()
    {
    	$this->db->trans_start(); # Starting Transaction

    	$id = $this->input->post('id');
    	$nama_produk = $this->input->post('nama_produk');
    	$kode_produk = $this->input->post('kode_produk');
        $bahanbaku = $this->input->post('bahanbaku');
    	$betonreadymix = $this->input->post('betonreadymix');
    	$aggregat = $this->input->post('aggregat');
    	$jasa = $this->input->post('jasa');
    	$peralatan = $this->input->post('peralatan');
    	$bahanbakar = $this->input->post('bahanbakar');
    	$laboratorium = $this->input->post('laboratorium');
		$asset = $this->input->post('asset');
    	$satuan = $this->input->post('satuan');
    	$deskripsi = $this->input->post('deskripsi');
		$kategori_produk = $this->input->post('kategori_produk');
    	$tipe_produk = $this->input->post('tipe_produk');
    	$harga_jual = false;
    	$akun_jual = false;
    	$pajak_jual = false;
    	$harga_beli = false;
    	$akun_beli = false;
    	$pajak_beli = false;
    	$jual = $this->input->post('jual');
    	$beli = $this->input->post('beli');
    	if(!empty($jual)){
    		$harga_jual = $this->filter->RupiahToDB($this->input->post('harga_jual'));
	    	$akun_jual = $this->input->post('akun_jual');
	    	$pajak_jual = $this->input->post('pajak_jual');
    	}

    	if(!empty($beli)){
    		$harga_beli = $this->filter->RupiahToDB($this->input->post('harga_beli'));
	    	$akun_beli = $this->input->post('akun_beli');
	    	$pajak_beli = $this->input->post('pajak_beli');
    	}


    	$data = array(
    		'kode_produk' => $kode_produk,
    		'nama_produk' => $nama_produk,
    		'harga_beli' => $harga_beli,
    		'akun_beli' => $akun_beli,
    		'pajak_beli' => $pajak_beli,
    		'harga_jual' => $harga_jual,
    		'akun_jual' => $akun_jual,
    		'pajak_jual' => $pajak_jual,
    		'bahanbaku' => $bahanbaku,
    		'betonreadymix' => $betonreadymix,
    		'aggregat' => $aggregat,
    		'jasa' => $jasa,
    		'peralatan' => $peralatan,
    		'bahanbakar' => $bahanbakar,
    		'laboratorium' => $laboratorium,
			'asset' => $asset,
    		'satuan' => $satuan,
    		'deskripsi' => $deskripsi,
			'kategori_produk' => $kategori_produk,
    		'tipe_produk' => $tipe_produk,
    		'jual' => $jual,
    		'beli' => $beli
    	);

    	if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			$this->db->update('produk',$data,array('id'=>$id));
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			$this->db->insert('produk',$data);
		}

		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error','Gagal Membuat Produk');
            redirect('produk/buat_baru');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil Membuat Produk');
            redirect('admin/produk');
        }
    }

    public function hapus($id)
    {
    	$this->db->trans_start(); # Starting Transaction

    	$this->db->update('produk',array('status'=>'DELETED'),array('id'=>$id));

		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error','Gagal Menghapus Produk');
            redirect('produk/detail/'.$id);
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil Menghapus Produk');
            redirect('admin/produk');
        }
    }

    public function detail($id)
    {
    	$row = $this->db->get_where('produk',array('id'=>$id))->row_array();
    	if(!empty($id)){
    		$data['row'] = $row;
    		$this->load->view('produk/detail',$data);
    	}else {
    		$this->session->set_flashdata('notif_error','Produk Tidak Tersedia');
            redirect('admin/produk');
    	}

		
    }




 }
 ?>