<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_umum extends CI_Controller {

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

    public function tambah_jurnal(){
		$check = $this->m_admin->check_login();
		if($check == true){		
			$data['akun'] = $this->db->get_where('pmm_coa',["status" => "PUBLISH"])->result_array();
            $this->load->view('pmm/jurnal_umum/tambah_jurnal',$data);
			
		}else {
			redirect('admin');
		}
    }

    public function detailJurnal($id){
        $check = $this->m_admin->check_login();
		if($check == true){		
            $data['akun'] = $this->db->get_where('pmm_coa',["status" => "PUBLISH"])->result_array();
            $data['detail'] = $this->db->get_where('pmm_jurnal_umum',["id" => $id])->row_array();
            $data['detailBiaya'] = $this->db->query("SELECT coa,deskripsi,debit,kredit FROM pmm_detail_jurnal INNER JOIN pmm_coa ON pmm_detail_jurnal.akun = pmm_coa.id
            WHERE pmm_detail_jurnal.jurnal_id = '$id'")->result_array();
            $data['lampiran'] = $this->db->get_where('pmm_lampiran_biaya',["biaya_id" => $id])->result_array();
            $this->load->view('pmm/jurnal_umum/detailJurnal',$data);
		}else {
			redirect('admin');
		}
    }

    public function table_jurnal(){
        $data = array();
		$filter_date = $this->input->post('filter_date');
		if(!empty($filter_date)){
			$arr_date = explode(' - ', $filter_date);
			$this->db->where('b.tanggal_transaksi >=',date('Y-m-d',strtotime($arr_date[0])));
			$this->db->where('b.tanggal_transaksi <=',date('Y-m-d',strtotime($arr_date[1])));
		}
		
		$this->db->select('b.*');
        $this->db->order_by('b.tanggal_transaksi','desc');
        // $this->db->order_by('created_on','desc');
		$query = $this->db->get('pmm_jurnal_umum b');
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$row['no'] = $key+1;
				// $row['coa'] = '' 
				$row['total_kredit'] = $this->filter->Rupiah($row['total_kredit']);
                $row['total_debit'] = $this->filter->Rupiah($row['total_debit']);
                $row['tanggal'] = date('d/m/Y',strtotime($row['tanggal_transaksi']));
                $row['jumlah_total'] = number_format($row["total"],2,',','.');
                $row['nomor'] = "<a href=".base_url('pmm/jurnal_umum/detailJurnal/'.$row["id"]).">".$row["nomor_transaksi"]."</a>";
				$data[] = $row;
			}

		}
		echo json_encode(array('data'=>$data));
    }
    
    public function cetakJurnal($id){
        


         $this->load->library('pdf');
    

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
        $pdf->setHtmlVSpace($tagvs);
        $pdf->AddPage('P');


        $data['biaya'] = $this->db->query("SELECT * FROM `pmm_jurnal_umum` WHERE id ='$id'")->row_array();
        $data['detail'] = $this->db->query("SELECT
        coa, 
        coa_number,
        deskripsi,
        debit,
        kredit
        FROM 
        pmm_detail_jurnal 
        INNER JOIN pmm_coa ON pmm_detail_jurnal.akun = pmm_coa.id
        WHERE pmm_detail_jurnal.jurnal_id = $id")->result_array();
        $data['akun'] = $this->db->get_where('tbl_admin',["admin_id" => $data['biaya']['created_by']])->row_array();
        $row = $this->db->get_where('pmm_jurnal_umum',array('id'=>$id))->row_array();
        
        $html = $this->load->view('pmm/jurnal_umum/cetakJurnal',$data,TRUE);

        
        $pdf->SetTitle($row['nomor_transaksi']);
        $pdf->nsi_html($html);
        $pdf->Output($row['nomor_transaksi'].'.pdf', 'I');
    }

    public function submit_jurnal(){
        $total_product = $this->input->post('total_product');
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 
        $total = $this->input->post('jumlah_debit') + $this->input->post('jumlah_kredit');

        $arr_insert = array(
            'nomor_transaksi' => $this->input->post('nomor_transaksi'),
            'tanggal_transaksi' => date('Y-m-d',strtotime($this->input->post('tanggal_transaksi'))),
        	'deskripsi' => '',
            'total' => $total,
            'total_kredit' => $this->input->post('jumlah_kredit'),
            'total_debit' => $this->input->post('jumlah_debit'),
            'memo' => $this->input->post('memo'),
        	'status' => 'UNPAID',
        	'created_by' => $this->session->userdata('admin_id'),
        	'created_on' => date('Y-m-d H:i:s')
        );

        if($this->db->insert('pmm_jurnal_umum',$arr_insert)){
            $jurnal_id = $this->db->insert_id();
            $data = [];
            $count = count($_FILES['files']['name']);
            for($i=0;$i<$count;$i++){
                if(!empty($_FILES['files']['name'][$i])){
            
                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];
            
                    $config['upload_path'] = 'uploads/jurnal_umum'; 
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config['file_name'] = $_FILES['files']['name'][$i];
            
                    $this->load->library('upload',$config); 
            
                    if($this->upload->do_upload('file')){
                        $uploadData = $this->upload->data();
                        $filename = $uploadData['file_name'];
                
                        $data['totalFiles'][] = $filename;
                        
                        
                        $data[$i] = array(
                            'jurnal_id'  => $jurnal_id,
                            'lampiran'  => $data['totalFiles'][$i]
                        );

                        $this->db->insert('pmm_lampiran_jurnal',$data[$i]);

                    }

                }
            }
                for ($i=1; $i <= $total_product ; $i++) {
                    $product = $this->input->post('product_'.$i);
                    $deskripsi = $this->input->post('deskripsi_'.$i);
                    $debit = $this->input->post('debit_'.$i);
                    $debit = str_replace('.', '', $debit);
                    $debit = str_replace(',', '.', $debit);

                    $kredit = $this->input->post('kredit_'.$i);
                    $kredit = str_replace('.', '', $kredit);
                    $kredit = str_replace(',', '.', $kredit);
                    
                    if(!empty($product)){

                        $this->pmm_finance->InsertTransactions($product,$deskripsi,$debit,$kredit);
                        $transaction_id = $this->db->insert_id();

                        $arr_detail = array(
                            'jurnal_id' => $jurnal_id,
                            'akun' => $product,
                            'deskripsi' => $deskripsi,
                            'debit' => $debit,
                            'kredit' => $kredit,
                            'transaction_id' => $transaction_id
                        );
                        
                        $this->db->insert('pmm_detail_jurnal',$arr_detail);

                    
                    }else{

                        redirect('pmm/jurnal_umum/tambah_jurnal');
                        exit();
                    }
    
                }

            
        }

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            redirect('pmm/jurnal_umum/tambah_jurnal');
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
             $this->session->set_flashdata('notif_success','Berhasil membuat Jurnal !!');
            redirect('admin/jurnal_umum');
        }

    }

    public function add_akun(){
		$no = $this->input->post('no');
		$clients = $this->db->get_where('pmm_coa',["status" => "PUBLISH"])->result_array();
		?>
		<tr>
            <td><?php echo $no;?>.</td>
            <td>
                <select id="product-<?php echo $no;?>" class="form-control form-select2" name="product_<?php echo $no;?>">
                    <option value="">Pilih Akun</option>
                    <?php
                    if(!empty($clients)){
                        foreach ($clients as $row) {
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
                <input type="text" name="debit_<?php echo $no;?>" id="jumlah-<?php echo $no;?>" onKeyup="getJumlah(this)" class="form-control numberformat tex-left jumlah_input" value="0" />
            </td>
            <td>
                <input type="text" name="kredit_<?php echo $no;?>" id="kredit-<?php echo $no;?>" onKeyup="getKredit(this)" class="form-control numberformat kredit kredit_input" value="0" />
            </td>
        </tr>

        <script type="text/javascript">
        
	        $('.form-select2').select2();
	        $('input.numberformat').number( true, 2,',','.' );

	    </script>
		<?php
		
    }


    function delete($id)
    {
        if(!empty($id)){

            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

            $nomor_transaksi = $this->crud_global->GetField('pmm_jurnal_umum',array('id'=>$id),'nomor_transaksi');
            $deskripsi = 'Nomor Transaksi '.$nomor_transaksi;
            $this->pmm_finance->InsertLogs('DELETE','pmm_jurnal_umum',$id,$deskripsi);
            $details = $this->db->select('transaction_id')->get_where('pmm_detail_jurnal',array('jurnal_id'=>$id))->result_array();
            if(!empty($details)){
                foreach ($details as $key => $dt) {
                    $this->db->delete('transactions',array('id'=>$dt['transaction_id']));
                }
            }
            $this->db->delete('pmm_detail_jurnal',array('jurnal_id'=>$id));
            $this->db->delete('pmm_lampiran_jurnal',array('jurnal_id'=>$id));
            $this->db->delete('pmm_jurnal_umum',array('id'=>$id));
            

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                $this->session->set_flashdata('notif_error','Gagal hapus jurnal !!');
                redirect('pmm/jurnal_umum/detailJurnal/'.$id);
            } 
            else {
                # Everything is Perfect. 
                # Committing data to the database.
                $this->db->trans_commit();
                $this->session->set_flashdata('notif_success','Berhasil hapus jurnal !!');
                redirect('admin/jurnal_umum');
            }
        }
    }
	
	public function approvalJurnal($id)
	{

		$this->db->set("status", "PAID");
		$this->db->where("id", $id);
		$this->db->update("pmm_jurnal_umum");
		$this->session->set_flashdata('notif_success', 'Berhasil menyetujui Jurnal Umum');
		redirect("admin/biaya");
	}
	
	public function rejectedJurnal($id)
	{
		$this->db->set("status", "UNPAID");
		$this->db->where("id", $id);
		$this->db->update("pmm_jurnal_umum");
		$this->session->set_flashdata('notif_success', 'Berhasil Menolak Jurnal Umum');
		redirect("admin/biaya");
	}

}

?>