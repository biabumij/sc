<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biaya extends CI_Controller {

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
    
    public function table_biaya(){
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('b.tanggal_transaksi >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('b.tanggal_transaksi <=',date('Y-m-d',strtotime($arr_date[1])));
		}

        $this->db->select('b.*, p.nama as penerima');
        $this->db->join('penerima p','b.penerima = p.id','left');
        $this->db->order_by('b.tanggal_transaksi','desc');
		$query = $this->db->get('pmm_biaya b');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
                $row['saldo'] = 0;
                $row['tanggal'] = date('d/m/Y',strtotime($row['tanggal_transaksi']));
                $row['saldo_bank'] = 0;
                $row['nomor_transaksi'] = "<a  href='".base_url('pmm/biaya/detail_biaya/'.$row['id'])."' >".$row['nomor_transaksi']."</a>";
				$row['jumlah_total'] = number_format($row['total'],2,',','.');
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
    }

	public function tambah_biaya(){
		$check = $this->m_admin->check_login();
		if($check == true){		

            // Setor Bank
            $this->db->select('c.*');
            $this->db->where('c.coa_category',3);
            $this->db->where('c.status','PUBLISH');
            $this->db->order_by('c.coa_number','asc');
            $query = $this->db->get('pmm_coa c');
            $data['akun'] = $query->result_array();

    
             // Setor Bank
            $this->db->select('c.*');
            $this->db->join('pmm_coa_category cc','c.coa_category = cc.id','left');
            // $this->db->where('c.coa_category',3);
            $this->db->where('c.status','PUBLISH');
            $this->db->where("cc.coa_category_number in ('5','6')");
            $this->db->order_by('c.coa_number','asc');
            $query = $this->db->get('pmm_coa c');
            $data['akun_biaya'] = $query->result_array();		


            $data['penerima'] = $this->db->select('nama,id')->get_where('penerima')->result_array();   
            $this->load->view('pmm/biaya/tambah_biaya',$data);
			
		}else {
			redirect('admin');
		}
    }

	public function add_akun(){
		$no = $this->input->post('no');

        $this->db->select('c.*');
        $this->db->join('pmm_coa_category cc','c.coa_category = cc.id','left');
        // $this->db->where('c.coa_category',3);
        $this->db->where('c.status','PUBLISH');
        $this->db->where("cc.coa_category_number in ('5','6')");
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_coa c');
        $akun_biaya = $query->result_array();  
		?>
		<tr>
            <td><?php echo $no;?>.</td>
            <td>
                <select id="product-<?php echo $no;?>" class="form-control form-select2" name="product_<?php echo $no;?>">
                    <option value="">Pilih Akun</option>
                    <?php
                    if(!empty($akun_biaya)){
                        foreach ($akun_biaya as $row) {
                            ?>
                            <option value="<?php echo $row['id'];?>" ><?php echo $row['coa_number'].' - '.$row['coa'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" name="deskripsi_<?php echo $no;?>"  class="form-control" />
            </td>
            <td>
                <input type="text" name="jumlah_<?php echo $no;?>" id="jumlah-<?php echo $no;?>" onKeyup="getJumlah(this)" class="form-control numberformat tex-left jumlah_input" />
            </td>
        </tr>

        <script type="text/javascript">
        
	        $('.form-select2').select2();
	        $('input.numberformat').number( true, 2,',','.' );

	    </script>
		<?php
		
    }
    
    public function submit_biaya(){
        $total_product = $this->input->post('total_product');
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

        $bayar_dari = $this->input->post('bayar_dari');
        $jumlah_biaya = $this->input->post('jumlah_biaya');
        $nomor_transaksi = $this->input->post('nomor_transaksi');

        $description_trans = 'Biaya Nomor '.$nomor_transaksi;
        $this->pmm_finance->InsertTransactions($bayar_dari,$description_trans,0,$jumlah_biaya);
        $transaction_id = $this->db->insert_id();

        $arr_insert = array(
        	'bayar_dari' => $bayar_dari,
        	'penerima' => $this->input->post('penerima'),
        	'tanggal_transaksi' => date('Y-m-d',strtotime($this->input->post('tanggal_transaksi'))),
        	'nomor_transaksi' => $nomor_transaksi,
            'cara_pembayaran' => $this->input->post('cara_pembayaran'),
            'total' => $jumlah_biaya,
            'memo' => $this->input->post('memo'),
        	'status' => 'UNPAID',
            'transaction_id' => $transaction_id,
        	'created_by' => $this->session->userdata('admin_id'),
        	'created_on' => date('Y-m-d H:i:s')
        );
        
        if($this->db->insert('pmm_biaya',$arr_insert)){
            $biaya_id = $this->db->insert_id();

            

            $data = [];
            $count = count($_FILES['files']['name']);
            for($i=0;$i<$count;$i++){
        
                if(!empty($_FILES['files']['name'][$i])){
            
                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];
            
                    $config['upload_path'] = 'uploads/biaya'; 
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config['file_name'] = $_FILES['files']['name'][$i];
            
                    $this->load->library('upload',$config); 
            
                    if($this->upload->do_upload('file')){
                        $uploadData = $this->upload->data();
                        $filename = $uploadData['file_name'];
                
                        $data['totalFiles'][] = $filename;
                        
                        
                        $data[$i] = array(
                            'biaya_id'  => $biaya_id,
                            'lampiran'  => $data['totalFiles'][$i]
                        );

                        $this->db->insert('pmm_lampiran_biaya',$data[$i]);

                    }

                }

                for ($i=1; $i <= $total_product ; $i++) {
            		$product = $this->input->post('product_'.$i);
            		$deskripsi = $this->input->post('deskripsi_'.$i);
                    $jumlah = $this->input->post('jumlah_'.$i);
                    $jumlah = str_replace('.', '', $jumlah);
            		$jumlah = str_replace(',', '.', $jumlah);
                    
                    if(!empty($product)){

                        // Insert COA
                        $this->pmm_finance->InsertTransactions($product,$deskripsi,$jumlah,0);
                        $transaction_id = $this->db->insert_id();

                        $arr_detail = array(
    		        		'biaya_id' => $biaya_id,
    		        		'akun' => $product,
    		        		'deskripsi' => $deskripsi,
    		        		'jumlah' => $jumlah,
                            'transaction_id' => $transaction_id
                        );
                        
                        $this->db->insert('pmm_detail_biaya',$arr_detail);


                    }else{
                        redirect('pmm/biaya/tambah_biaya');
            			exit();
                    }

                }
        
            }



        }

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            redirect('pmm/biaya/tambah_biaya');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
             $this->session->set_flashdata('notif_success','Berhasil membuat Biaya !!');
            redirect('admin/biaya');
        }

    }

    public function cetakBiaya($id){

        $this->load->library('pdf');
    

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
        $pdf->setHtmlVSpace($tagvs);
        $pdf->AddPage('P');

        $this->db->select('b.*, c.coa as bayar_dari, p.nama as penerima');
        $this->db->join('pmm_coa c','b.bayar_dari = c.id','left');
        $this->db->join('penerima p','b.penerima = p.id','left');
        $data['biaya'] = $this->db->get_where('pmm_biaya b',array('b.id'=>$id))->row_array();
        $row = $this->db->get_where('pmm_biaya',array('id'=>$id))->row_array();

        $this->db->select('b.*, c.coa as akun, c.coa_number');
        $this->db->join('pmm_coa c','b.akun = c.id','left');
        $data['detail'] = $this->db->get_where('pmm_detail_biaya b',array('b.biaya_id'=>$id))->result_array();
        $html = $this->load->view('pmm/biaya/cetakBiaya',$data,TRUE);

        
        $pdf->SetTitle($row['nomor_transaksi']);
        $pdf->nsi_html($html);
        $pdf->Output($row['nomor_transaksi'].'.pdf', 'I');
    }


    public function detail_biaya($id)
    {
        $check = $this->m_admin->check_login();
        if($check == true){     

            $this->db->select('b.*,p.nama as penerima');
            $this->db->join('penerima p','b.penerima = p.id','left');
            $this->db->where('b.id',$id);
            $query = $this->db->get('pmm_biaya b');
            $data['row'] = $query->row_array();


            $this->db->select('b.*, c.coa as akun, c.coa_number as kode_akun');
            $this->db->join('pmm_coa c','b.akun = c.id','left');
            $data['detail'] = $this->db->get_where('pmm_detail_biaya b',array('b.biaya_id'=>$id))->result_array();            
            $this->load->view('pmm/biaya/detail_biaya',$data);
            
        }else {
            redirect('admin');
        }
    }

    public function delete($id)
    {
        if(!empty($id)){

            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

            $biaya = $this->db->get_where('pmm_biaya',array('id'=>$id))->row_array();
            $deskripsi = 'Nomor Transaksi '.$biaya['nomor_transaksi'];
            $this->pmm_finance->InsertLogs('DELETE','pmm_biaya',$id,$deskripsi);

            $details = $this->db->select('transaction_id')->get_where('pmm_detail_biaya',array('biaya_id'=>$id))->result_array();
            if(!empty($details)){
                foreach ($details as $key => $dt) {
                    $this->db->delete('transactions',array('id'=>$dt['transaction_id']));
                }
            }
            $this->db->delete('transactions',array('id'=>$biaya['transaction_id']));
            $this->db->delete('pmm_detail_biaya',array('biaya_id'=>$id));
            $this->db->delete('pmm_lampiran_biaya',array('biaya_id'=>$id));
            $this->db->delete('pmm_biaya',array('id'=>$id));
            

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                $this->session->set_flashdata('notif_error','Gagal hapus jurnal !!');
                redirect('pmm/biaya/detail_biaya/'.$id);
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                $this->session->set_flashdata('notif_success','Berhasil hapus jurnal !!');
                redirect('admin/biaya');
            }
        }
    }


    public function tambah_penerima()
    {
        $output['output'] = false;

        $nama = $this->input->post('nama');
        $email = $this->input->post('email');
        $alamat = $this->input->post('alamat');

        $pelanggan = $this->input->post('pelanggan');
        $rekanan = $this->input->post('rekanan');
        $karyawan = $this->input->post('karyawan');
        $lain = $this->input->post('lain');
        $status = 'PUBLISH';

        $data = array(
            'nama' => $nama,
            'email' => $email,
            'alamat' => $alamat,
            'pelanggan' => $pelanggan,
            'rekanan' => $rekanan,
            'karyawan' => $karyawan,
            'lain' => $lain,
            'status' => $status
        );

        $data['created_by'] = $this->session->userdata('admin_id');
        $data['created_on'] = date('Y-m-d H:i:s');
        if($this->db->insert('penerima',$data)){
            $output['output'] = true;

            $this->db->select('id,nama');
            $this->db->order_by('nama','asc');
            $query = $this->db->get_where('penerima')->result_array();
            $data = array();
            $data[0] = array('id'=>'','text'=>'.. Pilih Penerima ..');
            foreach ($query as $key => $row) {

                $data[] = array('id'=>$row['id'],'text'=>$row['nama']);
            }
            $output['data'] = $data;
        }   
        
        echo json_encode($output);  
    }


    public function import_biaya()
    {
        /*

        Yang Belum lengkap adalah 
        - Jenis penerima
        - Cara Pembayaran
        - Bayar Dari
        */

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

        $str = file_get_contents('https://betontulungagung.biabumijayendra.com/pmm_data/pmm_biaya.json');
        $json = json_decode($str, true);
        foreach ($json['Sheet1'] as $field => $value) {
            // print_r($value);
            // echo $value['Tanggal'];
            // insert biaya
            $arr_tanggal = explode('/', $value['Tanggal']);
            $tanggal_transaksi = $arr_tanggal[2].'-'.$arr_tanggal[1].'-'.$arr_tanggal[0];
            // echo $tanggal_transaksi.'<br />';

            $this->db->like('coa',$value['Bayar_Dari']);
            $get_bayar_dari = $this->db->get('pmm_coa')->row_array();
            $bayar_dari = 0;
            if(!empty($get_bayar_dari)){
                $bayar_dari = $get_bayar_dari['id'];
            } 

            // Check Penerima
            $this->db->like('nama',$value['Penerima']);
            $check_penerima = $this->db->get('pmm_penerima_biaya')->row_array();
            if(!empty($check_penerima)){
                $penerima = $check_penerima['id'];
            }else {
                $insert_penerima = array(
                    'nama' => $value['Penerima'],
                    'status' => 'PUBLISH',
                    'created_by' => 7,
                    'created_on' => date('Y-m-d H:i:s')
                 );
                $this->db->insert('pmm_penerima_biaya',$insert_penerima);
                $penerima = $this->db->insert_id();
            }   
            
            
            $biaya = array(
                'bayar_dari' => $bayar_dari,
                'penerima' => $penerima,
                'tanggal_transaksi' => $tanggal_transaksi,
                'nomor_transaksi' => $value['Nomor'],
                'cara_pembayaran' => $value['Cara_Pembayaran'],
                'total' => 0,
                'memo' => '',
                'created_by' => 7,
                'created_on' => date('Y-m-d H:i:s')
            );

            // Get Akun 
            $this->db->like('coa',$value['Kategori']);
            $get_akun = $this->db->get('pmm_coa')->row_array();
            $akun = 0;
            if(!empty($get_akun)){
                $akun = $get_akun['id'];
            }
            // print_r($get_akun);
            // Check nomor
            $check = $this->db->get_where('pmm_biaya',array('nomor_transaksi'=>$value['Nomor']))->row_array();
            if(!empty($check)){
                $biaya_id = $check['id'];
                $detail_biaya = array(
                    'biaya_id' => $biaya_id,
                    'akun' => $akun,
                    'deskripsi' => $value['Deskripsi'],
                    'jumlah' => str_replace(',', '', $value['Jumlah'])
                );
                $this->db->insert('pmm_detail_biaya',$detail_biaya);

            }else {
                $this->db->insert('pmm_biaya',$biaya);
                $biaya_id = $this->db->insert_id();    
                $detail_biaya = array(
                    'biaya_id' => $biaya_id,
                    'akun' => $akun,
                    'deskripsi' => $value['Deskripsi'],
                    'jumlah' => str_replace(',', '', $value['Jumlah'])
                );
                $this->db->insert('pmm_detail_biaya',$detail_biaya);
            }

        }


        // Update Total
        // $arr_biaya = $this->db->get('pmm_biaya')->result_array();
        // foreach ($arr_biaya as $key => $row) {
        //     $detail_biaya = $this->db->get_where('pmm_detail_biaya',array('biaya_id'=>$row['id']))->result_array();
        //     $total = 0;
        //     foreach ($detail_biaya as $db) {
        //         // Insert Transaction
        //         $arr_transaction = array(
        //             'coa_id'=>$db['akun'],
        //             'description'=> $db['deskripsi'],
        //             'debit' => $db['jumlah'],
        //             'created_by' => 7,
        //             'created_on' => date('Y-m-d H:i:s')
        //         );
        //         $this->db->insert('transactions',$arr_transaction);

        //         $arr_transaction = array(
        //             'coa_id'=>$row['bayar_dari'],
        //             'description'=> $db['deskripsi'],
        //             'credit' => $db['jumlah'],
        //             'created_by' => 7,
        //             'created_on' => date('Y-m-d H:i:s')
        //         );
        //         $this->db->insert('transactions',$arr_transaction);

        //         $total += $db['jumlah'];
        //     }

        //     $this->db->update('pmm_biaya',array('total'=>$total),array('id'=>$row['id']));
        // }


        //  if ($this->db->trans_status() === FALSE) {
        //     # Something went wrong.
        //     $this->db->trans_rollback();
        //     // redirect('pmm/biaya/tambah_biaya');
        // } 
        // else {
        //     # Everything is Perfect. 
        //     # Committing data to the database.
        //     $this->db->trans_commit();
        //     echo 'success';
        // }

    }

     public function import_biaya_2()
    {
        /*

        Yang Belum lengkap adalah 
        - Jenis penerima
        - Cara Pembayaran
        - Bayar Dari
        */

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

        $str = file_get_contents('https://betontulungagung.biabumijayendra.com/pmm_data/pmm_biaya.json');
        $json = json_decode($str, true);
        foreach ($json[2]['data'] as $field => $value) {
            // print_r($value);
            // echo $value['nomor_transaksi'].'<br />';
            // insert biaya
            // $arr_tanggal = explode('/', $value['Tanggal']);
            // $tanggal_transaksi = $arr_tanggal[2].'-'.$arr_tanggal[1].'-'.$arr_tanggal[0];
            // // echo $tanggal_transaksi.'<br />';

            // $this->db->like('coa',$value['Bayar_Dari']);
            // $get_bayar_dari = $this->db->get('pmm_coa')->row_array();
            // $bayar_dari = 0;
            // if(!empty($get_bayar_dari)){
            //     $bayar_dari = $get_bayar_dari['id'];
            // } 

            // Check Penerima
            $this->db->like('nama',$value['nama_penerima']);
            $check_penerima = $this->db->get('penerima')->row_array();
            if(!empty($check_penerima)){
                $penerima = $check_penerima['id'];
            }else {
                $insert_penerima = array(
                    'nama' => $value['nama_penerima'],
                    'status' => 'PUBLISH',
                    'created_by' => 7,
                    'created_on' => date('Y-m-d H:i:s'),
                    'rekanan' => 1
                 );
                $this->db->insert('penerima',$insert_penerima);
                $penerima = $this->db->insert_id();
            }   
            
            
            $biaya = array(
                'id' => $value['id'],
                'bayar_dari' => $value['bayar_dari'],
                'penerima' => $penerima,
                'tanggal_transaksi' => $value['tanggal_transaksi'],
                'nomor_transaksi' => $value['nomor_transaksi'],
                'cara_pembayaran' => $value['cara_pembayaran'],
                'total' => $value['total'],
                'memo' => $value['memo'],
                'transaction_id' => $value['transaction_id'],
                'created_by' => $value['created_by'],
                'created_on' => $value['created_on'],
                'status' => $value['status']
            );

            $this->db->insert('pmm_biaya',$biaya);
            
            

            // Get Akun 
            // $this->db->like('coa',$value['Kategori']);
            // $get_akun = $this->db->get('pmm_coa')->row_array();
            // $akun = 0;
            // if(!empty($get_akun)){
            //     $akun = $get_akun['id'];
            // }
            // print_r($get_akun);
            // Check nomor
            

            // $check = $this->db->get_where('pmm_biaya',array('nomor_transaksi'=>$value['nomor_transaksi']))->row_array();
            // if(!empty($check)){
            //     // $biaya_id = $check['id'];
            //     // $detail_biaya = array(
            //     //     'biaya_id' => $biaya_id,
            //     //     'akun' => $akun,
            //     //     'deskripsi' => $value['Deskripsi'],
            //     //     'jumlah' => str_replace(',', '', $value['Jumlah'])
            //     // );
            //     // $this->db->insert('pmm_detail_biaya',$detail_biaya);

            // }else {
            //     $this->db->insert('pmm_biaya',$biaya);
            //     // $biaya_id = $this->db->insert_id();    
            //     // // $detail_biaya = array(
            //     // //     'biaya_id' => $biaya_id,
            //     // //     'akun' => $akun,
            //     // //     'deskripsi' => $value['Deskripsi'],
            //     // //     'jumlah' => str_replace(',', '', $value['Jumlah'])
            //     // // );
            //     // // $this->db->insert('pmm_detail_biaya',$detail_biaya);
            // }

        }

         if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            // redirect('pmm/biaya/tambah_biaya');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            echo 'success';
        }

    }
	
	public function approvalBiaya($id)
	{

		$this->db->set("status", "PAID");
		$this->db->where("id", $id);
		$this->db->update("pmm_biaya");
		$this->session->set_flashdata('notif_success', 'Berhasil menyetujui Biaya');
		redirect("admin/biaya");
	}
	
	public function rejectedBiaya($id)
	{
		$this->db->set("status", "UNPAID");
		$this->db->where("id", $id);
		$this->db->update("pmm_biaya");
		$this->session->set_flashdata('notif_success', 'Berhasil Menolak Biaya');
		redirect("admin/biaya");
	}

	public function form($id)
    {
    	$where = array('id' => $id);

        $this->db->select('c.*');
        $this->db->where('c.coa_category',3);
        $this->db->where('c.status','PUBLISH');
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_coa c');
        $data['akun'] = $query->result_array();

        $data['penerima'] = $this->db->select('nama,id')->get_where('penerima')->result_array();  

        $this->db->select('c.*');
        $this->db->join('pmm_coa_category cc','c.coa_category = cc.id','left');
        $this->db->where('c.status','PUBLISH');
        $this->db->where("cc.coa_category_number in ('5','6')");
        $this->db->order_by('c.coa_number','asc');
        $query = $this->db->get('pmm_coa c');
        $data['akun_biaya'] = $query->result_array();

        $get_data = $this->db->select('b.*, c.coa, p.nama')
        ->from('pmm_biaya b ')
        ->join('pmm_coa c','b.bayar_dari = c.id','left')
        ->join('penerima p','b.penerima = p.id','left')
        ->where('b.id',$id)
        ->get()->row_array();
        
    	$data['data'] = $get_data;
        
		$this->load->view('pmm/biaya/form',$data);
    }

    public function delete_detail()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			
			if($this->db->delete('pmm_detail_biaya',array('id'=>$id))){
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

    public function product_process()
	{
		$output['output'] = false;

        $biaya_detail_id = $this->input->post('biaya_detail_id');
		$biaya_id = $this->input->post('biaya_id');
		$product = $this->input->post('product');
		$deskripsi = $this->input->post('deskripsi');
		$jumlah = str_replace(',', '.', $this->input->post('jumlah'));

		$check = $this->db->get_where('pmm_detail_biaya',array('biaya_id'=>$biaya_id,'akun'=>$product))->num_rows();

		if(empty($id) && $check > 0){
			$output['output'] = false;
			$output['err'] = 'Akun Sudah Ditambahkan !!!';
		}else {
            $transaction_id = $this->pmm_model->GetNoEditBiaya();


			$data_p = array(
				'biaya_id' => $biaya_id,
				'akun' => $product,
				'deskripsi' => $deskripsi,
				'jumlah' => $jumlah,
                'transaction_id' => $transaction_id,
			);
			if(!empty($biaya_detail_id)){
				$data_p['updated_by'] = $this->session->userdata('admin_id');
				$this->db->insert('pmm_detail_biaya',$data_p,array('id'=>$biaya_id));
			}else {	
				$data_p['created_on'] = date('Y-m-d H:i:s');
				$data_p['created_by'] = $this->session->userdata('admin_id');
				$this->db->insert('pmm_detail_biaya',$data_p,array('id'=>$biaya_id));	
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
				$output['output'] = true;
			}
		}
	
		
		echo json_encode($output);	
	}

    public function main_table()
	{	
		$data = $this->pmm_model->TableMainBiaya($this->input->post('id'));
		echo json_encode(array('data'=>$data));
	}

    public function table_detail()
	{	
		$data = $this->pmm_model->TableDetailBiaya($this->input->post('id'));
		echo json_encode(array('data'=>$data));
	}

    public function form_biaya_main()
	{
		$output['output'] = false;

		$form_id_biaya_main = $this->input->post('form_id_biaya_main');
		$penerima = $this->input->post('penerima');
		$nomor_transaksi = $this->input->post('nomor_transaksi');
		$tanggal_transaksi = date('Y-m-d',strtotime($this->input->post('tanggal_transaksi')));
        $bayar_dari = $this->input->post('bayar_dari');
        $memo = $this->input->post('memo');
		$total = str_replace(',', '.', $this->input->post('total'));
        $total = $this->input->post('total');

		$data = array(
            'id' => $form_id_biaya_main,
			'penerima' => $penerima,
			'tanggal_transaksi' => $tanggal_transaksi,
			'bayar_dari' => $bayar_dari,
            'memo' => $memo,
            'total' => $total
		);

		if(!empty($id)){
			$data['created_by'] = $this->session->userdata('admin_id');
            $data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->update('pmm_biaya',$data,array('id'=>$form_id_biaya_main))){
				$output['output'] = true;
			}
		}else{
            $data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_biaya',$data,array('id'=>$form_id_biaya_main))){
				$output['output'] = true;
			}
		}
		
		echo json_encode($output);	
	}

    public function form_biaya()
	{
		$output['output'] = false;

		$form_id_biaya = $this->input->post('form_id_biaya');
		$biaya_id = $this->input->post('biaya_id');
		$akun = $this->input->post('akun');
		$deskripsi = $this->input->post('deskripsi');
		$jumlah = str_replace(',', '.', $this->input->post('jumlah'));

		$data = array(
            'biaya_id' => $biaya_id,
			'akun' => $akun,
			'deskripsi' => $deskripsi,
			'jumlah' => $jumlah
		);

		if(!empty($id)){
			$data['created_by'] = $this->session->userdata('admin_id');
            $data['created_on'] = date('Y-m-d H:i:s');
			if($this->db->update('pmm_detail_biaya',$data,array('id'=>$form_id_biaya))){
				$output['output'] = true;
			}
		}else{
            $data['updated_by'] = $this->session->userdata('admin_id');
			if($this->db->update('pmm_detail_biaya',$data,array('id'=>$form_id_biaya))){
				$output['output'] = true;
			}
		}
		
		echo json_encode($output);	
	}

    public function get_biaya_main()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
            $data = $this->db->select('b.*, sum(pdb.jumlah) as total')
            ->from('pmm_biaya b ')
            ->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left')
            ->join('pmm_coa c','b.bayar_dari = c.id','left')
            ->join('penerima p','b.penerima = p.id','left')
            ->where('b.id',$id)
            ->get()->row_array();

            $data['tanggal_transaksi'] = date('d-m-Y',strtotime($data['tanggal_transaksi']));
			$output['output'] = $data;
            
		}
		echo json_encode($output);
	}

    public function get_biaya()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$data = $this->db->select('*')->get_where('pmm_detail_biaya',array('id'=>$id))->row_array();
			$output['output'] = $data;
		}
		echo json_encode($output);
	}

    

}

?>