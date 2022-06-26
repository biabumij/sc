<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontak extends Secure_Controller {

	public function __construct()
	{
        parent::__construct();
        // Your own constructor code
        $this->load->model(array('admin/m_admin','crud_global','m_themes','pages/m_pages','menu/m_menu','admin_access/m_admin_access','DB_model','member_back/m_member_back','m_member','pmm/pmm_model','admin/Templates','pmm/pmm_finance','produk/m_produk'));
        $this->load->library('enkrip');
		$this->load->library('filter');
		$this->load->library('waktu');
		$this->load->library('session');
    }	


    public function table()
	{	
		$data = array();
		$tipe = $this->input->post('tipe');;
		// if(!empty($tag_id)){
		// 	$this->db->where('tag_id',$tag_id);	
		// }
		if($tipe == 1){
			$this->db->where('pelanggan',1);
		}else if($tipe == 2){
			$this->db->where('rekanan',1);
		}else if($tipe == 3){
			$this->db->where('karyawan',1);
		}else if($tipe == 4){
			$this->db->where('lain',1);
		}

		$this->db->where('status','PUBLISH');
		$this->db->order_by('nama','asc');
		$query = $this->db->get('penerima');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				$row['balance'] = 0;
				$row['nama'] = '<a href="'.site_url('kontak/detail/'.$row['id']).'">'.$row['nama'].'</a>';
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
	}
    

    public function form()
    {

    	$id = $this->uri->segment(3);
    	$data['akun'] = $this->pmm_finance->getAkunCoa();

    	if(!empty($id)){
    		$data['edit'] = $this->db->get_where('penerima',array('id'=>$id))->row_array();
    	}
		$this->load->view('kontak/form',$data);
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

    public function submit_form()
    {
    	$this->db->trans_start(); # Starting Transaction

    	$id = $this->input->post('id');

    	$nama = $this->input->post('nama');
    	$pelanggan = $this->input->post('pelanggan');
    	$rekanan = $this->input->post('rekanan');
    	$karyawan = $this->input->post('karyawan');
    	$lain = $this->input->post('lain');
    	$nama_kontak = $this->input->post('nama_kontak');
    	$tipe_identitas = $this->input->post('tipe_identitas');
    	$no_identitas = $this->input->post('no_identitas');
    	$email = $this->input->post('email');
    	$nama_perusahaan = $this->input->post('nama_perusahaan');
    	$telepon = $this->input->post('telepon');
    	$fax = $this->input->post('fax');
    	$npwp = $this->input->post('npwp');
    	$alamat_penagihan = $this->input->post('alamat_penagihan');
    	$alamat = $this->input->post('alamat');
    	$akun_masuk = $this->input->post('akun_masuk');
    	$akun_keluar = $this->input->post('akun_keluar');


    	$data = array(
    		'nama' => $nama,
    		'pelanggan' => $pelanggan,
    		'rekanan' => $rekanan,
    		'karyawan' => $karyawan,
    		'lain' => $lain,
    		'nama_kontak' => $nama_kontak,
    		'tipe_identitas' => $tipe_identitas,
    		'no_identitas' => $no_identitas,
    		'email' => $email,
    		'nama_perusahaan' => $nama_perusahaan,
    		'telepon' => $telepon,
    		'fax' => $fax,
    		'npwp' => $npwp,
    		'alamat_penagihan' => $alamat_penagihan,
    		'alamat' => $alamat,
    		'akun_keluar' => $akun_keluar,
    		'akun_masuk' => $akun_masuk,
    		'posisi' => $this->input->post('posisi')
    	);

    	if(!empty($id)){
			$data['updated_by'] = $this->session->userdata('admin_id');
			$this->db->update('penerima',$data,array('id'=>$id));
		}else{
			$data['created_by'] = $this->session->userdata('admin_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			$this->db->insert('penerima',$data);
		}

		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error','Gagal Input Kontak');
            redirect('kontak/form');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil Input Kontak');
            redirect('admin/kontak');
        }
    }

    public function hapus($id)
    {
    	$this->db->trans_start(); # Starting Transaction

    	$this->db->update('penerima',array('status'=>'DELETED'),array('id'=>$id));

		if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->session->set_flashdata('notif_error','Gagal Hapus Kontak');
            redirect('kontak/detail/'.$id);
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $this->session->set_flashdata('notif_success','Berhasil Hapus Kontak');
            redirect('admin/kontak');
        }
    }

    public function detail($id)
    {
    	$row = $this->db->get_where('penerima',array('id'=>$id))->row_array();
    	if(!empty($id)){
    		$data['row'] = $row;
    		$this->load->view('kontak/detail',$data);
    	}else {
    		$this->session->set_flashdata('notif_error','Kontak Tidak Tersedia');
            redirect('admin/kontak');
    	}

		
    }


    public function export_from_supplier()
    {


        $query = $this->db->get_where('pmm_supplier',array('status'=>'PUBLISH'))->result_array();

        foreach ($query as $key => $row) {
           

           $array = array(
                'nama' => $row['name'],
                'rekanan' => 1,
                'alamat' => $row['address'],
                'alamat_penagihan' => $row['address'],
                'npwp' => $row['npwp'],
                'nama_kontak' => $row['pic'],
                'posisi' => $row['position'],

           );
           $this->db->insert('penerima',$array);
        }
    }




 }
 ?>