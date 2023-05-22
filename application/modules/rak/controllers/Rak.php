<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak extends Secure_Controller {

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
	
	public function form_rencana_kerja()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] =  $this->db->select('*')
			->from('produk p')
			->where("p.status = 'PUBLISH'")
			->where("p.kategori_produk = 2 ")
			->order_by('nama_produk','asc')
			->get()->result_array();

			$data['komposisi'] = $this->db->select('id, jobs_type,date_agregat')->order_by('date_agregat','desc')->get_where('pmm_agregat',array('status'=>'PUBLISH'))->result_array();

			$data['semen'] = $this->pmm_model->getMatByPenawaranRencanaKerjaSemen();
			$data['pasir'] = $this->pmm_model->getMatByPenawaranRencanaKerjaPasir();
			$data['batu1020'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBatu1020();
			$data['batu2030'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBatu2030();
			$data['solar'] = $this->pmm_model->getMatByPenawaranRencanaKerjaSolar();
			$data['bp'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBP();
			$data['bp_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBP();
			$data['bp_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBP();
			$data['tm'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['tm_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['tm_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['tm_4'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['wl'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWL();
			$data['wl_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWL();
			$data['wl_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWL();
			$data['tr'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTransferSemen();
			$data['tr_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTransferSemen();
			$data['tr_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTransferSemen();
			$data['exc'] = $this->pmm_model->getMatByPenawaranRencanaKerjaEXC();
			$data['dmp_4m3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaDmp4M3();
			$data['dmp_10m3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaDmp10M3();
			$data['sc'] = $this->pmm_model->getMatByPenawaranRencanaKerjaSC();
			$data['gns'] = $this->pmm_model->getMatByPenawaranRencanaKerjaGNS();
			$data['wl_sc'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWLSC();
			$this->load->view('rak/form_rencana_kerja', $data);
		} else {
			redirect('admin');
		}
	}
	
	public function submit_rencana_kerja()
	{
		$tanggal_rencana_kerja = $this->input->post('tanggal_rencana_kerja');
		$vol_produk_a =  str_replace('.', '', $this->input->post('vol_produk_a'));
		$vol_produk_a =  str_replace(',', '.', $vol_produk_a);
		$vol_produk_b =  str_replace('.', '', $this->input->post('vol_produk_b'));
		$vol_produk_b =  str_replace(',', '.', $vol_produk_b);
		$vol_produk_c =  str_replace('.', '', $this->input->post('vol_produk_c'));
		$vol_produk_c =  str_replace(',', '.', $vol_produk_c);
		$vol_produk_d =  str_replace('.', '', $this->input->post('vol_produk_d'));
		$vol_produk_d =  str_replace(',', '.', $vol_produk_d);

		$vol_bbm_solar =  str_replace('.', '', $this->input->post('vol_bbm_solar'));
		$vol_bbm_solar =  str_replace(',', '.', $vol_bbm_solar);

		$komposisi_125 =  $this->input->post('komposisi_125');
		$komposisi_225 =  $this->input->post('komposisi_225');
		$komposisi_250 =  $this->input->post('komposisi_250');
		$komposisi_250_2 =  $this->input->post('komposisi_250_2');

		$price_a =  str_replace('.', '', $this->input->post('price_a'));
		$price_b =  str_replace('.', '', $this->input->post('price_b'));
		$price_c =  str_replace('.', '', $this->input->post('price_c'));
		$price_d =  str_replace('.', '', $this->input->post('price_d'));
		$insentif =  str_replace('.', '', $this->input->post('insentif'));
		$sewa_alat =  str_replace('.', '', $this->input->post('sewa_alat'));
		$overhead =  str_replace('.', '', $this->input->post('overhead'));

		$penawaran_id_semen =  $this->input->post('penawaran_id_semen');
		$penawaran_id_pasir =  $this->input->post('penawaran_id_pasir');
		$penawaran_id_batu1020 =  $this->input->post('penawaran_id_batu1020');
		$penawaran_id_batu2030 =  $this->input->post('penawaran_id_batu2030');
		$penawaran_id_solar =  $this->input->post('penawaran_id_solar');
		$penawaran_id_bp =  $this->input->post('penawaran_id_bp');
		$penawaran_id_bp_2 =  $this->input->post('penawaran_id_bp_2');
		$penawaran_id_bp_3 =  $this->input->post('penawaran_id_bp_3');
		$penawaran_id_tm =  $this->input->post('penawaran_id_tm');
		$penawaran_id_tm_2 =  $this->input->post('penawaran_id_tm_2');
		$penawaran_id_tm_3 =  $this->input->post('penawaran_id_tm_3');
		$penawaran_id_tm_4 =  $this->input->post('penawaran_id_tm_4');
		$penawaran_id_wl =  $this->input->post('penawaran_id_wl');
		$penawaran_id_wl_2 =  $this->input->post('penawaran_id_wl_2');
		$penawaran_id_wl_3 =  $this->input->post('penawaran_id_wl_3');
		$penawaran_id_tr =  $this->input->post('penawaran_id_tr');
		$penawaran_id_tr_2 =  $this->input->post('penawaran_id_tr_2');
		$penawaran_id_tr_3 =  $this->input->post('penawaran_id_tr_3');
		$penawaran_id_exc =  $this->input->post('penawaran_id_exc');
		$penawaran_id_dmp_4m3 =  $this->input->post('penawaran_id_dmp_4m3');
		$penawaran_id_dmp_10m3 =  $this->input->post('penawaran_id_dmp_10m3');
		$penawaran_id_sc =  $this->input->post('penawaran_id_sc');
		$penawaran_id_gns =  $this->input->post('penawaran_id_gns');
		$penawaran_id_wl_sc =  $this->input->post('penawaran_id_wl_sc');

		$harga_semen =  str_replace('.', '', $this->input->post('price_semen'));
		$harga_pasir =  str_replace('.', '', $this->input->post('price_pasir'));
		$harga_batu1020 =  str_replace('.', '', $this->input->post('price_batu1020'));
		$harga_batu2030 =  str_replace('.', '', $this->input->post('price_batu2030'));
		$harga_solar =  str_replace('.', '', $this->input->post('price_solar'));
		$harga_bp =  str_replace('.', '', $this->input->post('price_bp'));
		$harga_bp_2 =  str_replace('.', '', $this->input->post('price_bp_2'));
		$harga_bp_3 =  str_replace('.', '', $this->input->post('price_bp_3'));
		$harga_tm =  str_replace('.', '', $this->input->post('price_tm'));
		$harga_tm_2 =  str_replace('.', '', $this->input->post('price_tm_2'));
		$harga_tm_3 =  str_replace('.', '', $this->input->post('price_tm_3'));
		$harga_tm_4 =  str_replace('.', '', $this->input->post('price_tm_4'));
		$harga_wl =  str_replace('.', '', $this->input->post('price_wl'));
		$harga_wl_2 =  str_replace('.', '', $this->input->post('price_wl_2'));
		$harga_wl_3 =  str_replace('.', '', $this->input->post('price_wl_3'));
		$harga_tr =  str_replace('.', '', $this->input->post('price_tr'));
		$harga_tr_2 =  str_replace('.', '', $this->input->post('price_tr_2'));
		$harga_tr_3 =  str_replace('.', '', $this->input->post('price_tr_3'));
		$harga_exc =  str_replace('.', '', $this->input->post('price_exc'));
		$harga_dmp_4m3 =  str_replace('.', '', $this->input->post('price_dmp_4m3'));
		$harga_dmp_10m3 =  str_replace('.', '', $this->input->post('price_dmp_10m3'));
		$harga_sc =  str_replace('.', '', $this->input->post('price_sc'));
		$harga_gns =  str_replace('.', '', $this->input->post('price_gns'));
		$harga_wl_sc =  str_replace('.', '', $this->input->post('price_wl_sc'));

		$satuan_semen =  $this->input->post('measure_semen');
		$satuan_pasir =  $this->input->post('measure_pasir');
		$satuan_batu1020 =  $this->input->post('measure_batu1020');
		$satuan_batu2030 =  $this->input->post('measure_batu2030');
		$satuan_solar =  $this->input->post('measure_solar');
		$satuan_bp =  $this->input->post('measure_bp');
		$satuan_bp_2 =  $this->input->post('measure_bp_2');
		$satuan_bp_3 =  $this->input->post('measure_bp_3');
		$satuan_tm =  $this->input->post('measure_tm');
		$satuan_tm_2 =  $this->input->post('measure_tm_2');
		$satuan_tm_3 =  $this->input->post('measure_tm_3');
		$satuan_tm_4 =  $this->input->post('measure_tm_4');
		$satuan_wl =  $this->input->post('measure_wl');
		$satuan_wl_2 =  $this->input->post('measure_wl_2');
		$satuan_wl_3 =  $this->input->post('measure_wl_3');
		$satuan_tr =  $this->input->post('measure_tr');
		$satuan_tr_2 =  $this->input->post('measure_tr_2');
		$satuan_tr_3 =  $this->input->post('measure_tr_3');
		$satuan_exc =  $this->input->post('measure_exc');
		$satuan_dmp_4m3 =  $this->input->post('measure_dmp_4m3');
		$satuan_dmp_10m3 =  $this->input->post('measure_dmp_10m3');
		$satuan_sc =  $this->input->post('measure_sc');
		$satuan_gns =  $this->input->post('measure_gns');
		$satuan_wl_sc =  $this->input->post('measure_wl_sc');

		$tax_id_semen =  $this->input->post('tax_id_semen');
		$tax_id_pasir =  $this->input->post('tax_id_pasir');
		$tax_id_batu1020 =  $this->input->post('tax_id_batu1020');
		$tax_id_batu2030 =  $this->input->post('tax_id_batu2030');
		$tax_id_solar =  $this->input->post('tax_id_solar');
		$tax_id_bp =  $this->input->post('tax_id_bp');
		$tax_id_bp_2 =  $this->input->post('tax_id_bp_2');
		$tax_id_bp_3 =  $this->input->post('tax_id_bp_3');
		$tax_id_tm =  $this->input->post('tax_id_tm');
		$tax_id_tm_2 =  $this->input->post('tax_id_tm_2');
		$tax_id_tm_3 =  $this->input->post('tax_id_tm_3');
		$tax_id_tm_4 =  $this->input->post('tax_id_tm_4');
		$tax_id_wl =  $this->input->post('tax_id_wl');
		$tax_id_wl_2 =  $this->input->post('tax_id_wl_2');
		$tax_id_wl_3 =  $this->input->post('tax_id_wl_3');
		$tax_id_tr =  $this->input->post('tax_id_tr');
		$tax_id_tr_2 =  $this->input->post('tax_id_tr_2');
		$tax_id_tr_3 =  $this->input->post('tax_id_tr_3');
		$tax_id_exc =  $this->input->post('tax_id_exc');
		$tax_id_dmp_4m3 =  $this->input->post('tax_id_dmp_4m3');
		$tax_id_dmp_10m3 =  $this->input->post('tax_id_dmp_10m3');
		$tax_id_sc =  $this->input->post('tax_id_sc');
		$tax_id_gns =  $this->input->post('tax_id_gns');
		$tax_id_wl_sc =  $this->input->post('tax_id_wl_sc');

		$pajak_id_semen =  $this->input->post('pajak_id_semen');
		$pajak_id_pasir =  $this->input->post('pajak_id_pasir');
		$pajak_id_batu1020 =  $this->input->post('pajak_id_batu1020');
		$pajak_id_batu2030 =  $this->input->post('pajak_id_batu2030');
		$pajak_id_solar =  $this->input->post('pajak_id_solar');
		$pajak_id_bp =  $this->input->post('pajak_id_bp');
		$pajak_id_bp_2 =  $this->input->post('pajak_id_bp_2');
		$pajak_id_bp_3 =  $this->input->post('pajak_id_bp_3');
		$pajak_id_tm =  $this->input->post('pajak_id_tm');
		$pajak_id_tm_2 =  $this->input->post('pajak_id_tm_2');
		$pajak_id_tm_3 =  $this->input->post('pajak_id_tm_3');
		$pajak_id_tm_4 =  $this->input->post('pajak_id_tm_4');
		$pajak_id_wl =  $this->input->post('pajak_id_wl');
		$pajak_id_wl_2 =  $this->input->post('pajak_id_wl_2');
		$pajak_id_wl_3 =  $this->input->post('pajak_id_wl_3');
		$pajak_id_tr =  $this->input->post('pajak_id_tr');
		$pajak_id_tr_2 =  $this->input->post('pajak_id_tr_2');
		$pajak_id_tr_3 =  $this->input->post('pajak_id_tr_3');
		$pajak_id_exc =  $this->input->post('pajak_id_exc');
		$pajak_id_dmp_4m3 =  $this->input->post('pajak_id_dmp_4m3');
		$pajak_id_dmp_10m3 =  $this->input->post('pajak_id_dmp_10m3');
		$pajak_id_sc =  $this->input->post('pajak_id_sc');
		$pajak_id_gns =  $this->input->post('pajak_id_gns');
		$pajak_id_wl_sc =  $this->input->post('pajak_id_wl_sc');

		$supplier_id_semen =  $this->input->post('supplier_id_semen');
		$supplier_id_pasir =  $this->input->post('supplier_id_pasir');
		$supplier_id_batu1020 =  $this->input->post('supplier_id_batu1020');
		$supplier_id_batu2030 =  $this->input->post('supplier_id_batu2030');
		$supplier_id_solar =  $this->input->post('supplier_id_solar');
		$supplier_id_bp =  $this->input->post('supplier_id_bp');
		$supplier_id_bp_2 =  $this->input->post('supplier_id_bp_2');
		$supplier_id_bp_3 =  $this->input->post('supplier_id_bp_3');
		$supplier_id_tm =  $this->input->post('supplier_id_tm');
		$supplier_id_tm_2 =  $this->input->post('supplier_id_tm_2');
		$supplier_id_tm_3 =  $this->input->post('supplier_id_tm_3');
		$supplier_id_tm_4 =  $this->input->post('supplier_id_tm_4');
		$supplier_id_wl =  $this->input->post('supplier_id_wl');
		$supplier_id_wl_2 =  $this->input->post('supplier_id_wl_2');
		$supplier_id_wl_3 =  $this->input->post('supplier_id_wl_3');
		$supplier_id_tr =  $this->input->post('supplier_id_tr');
		$supplier_id_tr_2 =  $this->input->post('supplier_id_tr_2');
		$supplier_id_tr_3 =  $this->input->post('supplier_id_tr_3');
		$supplier_id_exc =  $this->input->post('supplier_id_exc');
		$supplier_id_dmp_4m3 =  $this->input->post('supplier_id_dmp_4m3');
		$supplier_id_dmp_10m3 =  $this->input->post('supplier_id_dmp_10m3');
		$supplier_id_sc =  $this->input->post('supplier_id_sc');
		$supplier_id_gns =  $this->input->post('supplier_id_gns');
		$supplier_id_wl_sc =  $this->input->post('supplier_id_wl_sc');

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'tanggal_rencana_kerja' =>  date('Y-m-d', strtotime($tanggal_rencana_kerja)),

			'vol_produk_a' => $vol_produk_a,
			'vol_produk_b' => $vol_produk_b,
			'vol_produk_c' => $vol_produk_c,
			'vol_produk_d' => $vol_produk_d,

			'vol_bbm_solar' => $vol_bbm_solar,

			'price_a' => $price_a,
			'price_b' => $price_b,
			'price_c' => $price_c,
			'price_d' => $price_d,
			'insentif' => $insentif,
			'sewa_alat' => $sewa_alat,
			'overhead' => $overhead,

			'komposisi_125' => $komposisi_125,
			'komposisi_225' => $komposisi_225,
			'komposisi_250' => $komposisi_250,
			'komposisi_250_2' => $komposisi_250_2,

			'penawaran_id_semen' => $penawaran_id_semen,
			'penawaran_id_pasir' => $penawaran_id_pasir,
			'penawaran_id_batu1020' => $penawaran_id_batu1020,
			'penawaran_id_batu2030' => $penawaran_id_batu2030,
			'penawaran_id_solar' => $penawaran_id_solar,
			'penawaran_id_bp' => $penawaran_id_bp,
			'penawaran_id_bp_2' => $penawaran_id_bp_2,
			'penawaran_id_bp_3' => $penawaran_id_bp_3,
			'penawaran_id_tm' => $penawaran_id_tm,
			'penawaran_id_tm_2' => $penawaran_id_tm_2,
			'penawaran_id_tm_3' => $penawaran_id_tm_3,
			'penawaran_id_tm_4' => $penawaran_id_tm_4,
			'penawaran_id_wl' => $penawaran_id_wl,
			'penawaran_id_wl_2' => $penawaran_id_wl_2,
			'penawaran_id_wl_3' => $penawaran_id_wl_3,
			'penawaran_id_tr' => $penawaran_id_tr,
			'penawaran_id_tr_2' => $penawaran_id_tr_2,
			'penawaran_id_tr_3' => $penawaran_id_tr_3,
			'penawaran_id_tr_3' => $penawaran_id_tr_3,
			'penawaran_id_exc' => $penawaran_id_exc,
			'penawaran_id_dmp_4m3' => $penawaran_id_dmp_4m3,
			'penawaran_id_dmp_10m3' => $penawaran_id_dmp_10m3,
			'penawaran_id_sc' => $penawaran_id_sc,
			'penawaran_id_gns' => $penawaran_id_gns,
			'penawaran_id_wl_sc' => $penawaran_id_wl_sc,

			'harga_semen' => $harga_semen,
			'harga_pasir' => $harga_pasir,
			'harga_batu1020' => $harga_batu1020,
			'harga_batu2030' => $harga_batu2030,
			'harga_solar' => $harga_solar,
			'harga_bp' => $harga_bp,
			'harga_bp_2' => $harga_bp_2,
			'harga_bp_3' => $harga_bp_3,
			'harga_tm' => $harga_tm,
			'harga_tm_2' => $harga_tm_2,
			'harga_tm_3' => $harga_tm_3,
			'harga_tm_4' => $harga_tm_4,
			'harga_wl' => $harga_wl,
			'harga_wl_2' => $harga_wl_2,
			'harga_wl_3' => $harga_wl_3,
			'harga_tr' => $harga_tr,
			'harga_tr_2' => $harga_tr_2,
			'harga_tr_3' => $harga_tr_3,
			'harga_exc' => $harga_exc,
			'harga_dmp_4m3' => $harga_dmp_4m3,
			'harga_dmp_10m3' => $harga_dmp_10m3,
			'harga_sc' => $harga_sc,
			'harga_gns' => $harga_gns,
			'harga_wl_sc' => $harga_wl_sc,

			'satuan_semen' => $satuan_semen,
			'satuan_pasir' => $satuan_pasir,
			'satuan_batu1020' => $satuan_batu1020,
			'satuan_batu2030' => $satuan_batu2030,
			'satuan_solar' => $satuan_solar,
			'satuan_bp' => $satuan_bp,
			'satuan_bp_2' => $satuan_bp_2,
			'satuan_bp_3' => $satuan_bp_3,
			'satuan_tm' => $satuan_tm,
			'satuan_tm_2' => $satuan_tm_2,
			'satuan_tm_3' => $satuan_tm_3,
			'satuan_tm_4' => $satuan_tm_4,
			'satuan_wl' => $satuan_wl,
			'satuan_wl_2' => $satuan_wl_2,
			'satuan_wl_3' => $satuan_wl_3,
			'satuan_tr' => $satuan_tr,
			'satuan_tr_2' => $satuan_tr_2,
			'satuan_tr_3' => $satuan_tr_3,
			'satuan_exc' => $satuan_exc,
			'satuan_dmp_4m3' => $satuan_dmp_4m3,
			'satuan_dmp_10m3' => $satuan_dmp_10m3,
			'satuan_sc' => $satuan_sc,
			'satuan_gns' => $satuan_gns,
			'satuan_wl_sc' => $satuan_wl_sc,

			'tax_id_semen' => $tax_id_semen,
			'tax_id_pasir' => $tax_id_pasir,
			'tax_id_batu1020' => $tax_id_batu1020,
			'tax_id_batu2030' => $tax_id_batu2030,
			'tax_id_solar' => $tax_id_solar,
			'tax_id_bp' => $tax_id_bp,
			'tax_id_bp_2' => $tax_id_bp_2,
			'tax_id_bp_3' => $tax_id_bp_3,
			'tax_id_tm' => $tax_id_tm,
			'tax_id_tm_2' => $tax_id_tm_2,
			'tax_id_tm_3' => $tax_id_tm_3,
			'tax_id_tm_4' => $tax_id_tm_4,
			'tax_id_wl' => $tax_id_wl,
			'tax_id_wl_2' => $tax_id_wl_2,
			'tax_id_wl_3' => $tax_id_wl_3,
			'tax_id_bp' => $tax_id_bp,
			'tax_id_bp_2' => $tax_id_bp_2,
			'tax_id_bp_3' => $tax_id_bp_3,
			'tax_id_tr' => $tax_id_tr,
			'tax_id_tr_2' => $tax_id_tr_2,
			'tax_id_tr_3' => $tax_id_tr_3,
			'tax_id_exc' => $tax_id_exc,
			'tax_id_dmp_4m3' => $tax_id_dmp_4m3,
			'tax_id_dmp_10m3' => $tax_id_dmp_10m3,
			'tax_id_sc' => $tax_id_sc,
			'tax_id_gns' => $tax_id_gns,
			'tax_id_wl_sc' => $tax_id_wl_sc,

			'pajak_id_semen' => $pajak_id_semen,
			'pajak_id_pasir' => $pajak_id_pasir,
			'pajak_id_batu1020' => $pajak_id_batu1020,
			'pajak_id_batu2030' => $pajak_id_batu2030,
			'pajak_id_solar' => $pajak_id_solar,
			'pajak_id_bp' => $pajak_id_bp,
			'pajak_id_bp_2' => $pajak_id_bp_2,
			'pajak_id_bp_3' => $pajak_id_bp_3,
			'pajak_id_tm' => $pajak_id_tm,
			'pajak_id_tm_2' => $pajak_id_tm_2,
			'pajak_id_tm_3' => $pajak_id_tm_3,
			'pajak_id_tm_4' => $pajak_id_tm_4,
			'pajak_id_wl' => $pajak_id_wl,
			'pajak_id_wl_2' => $pajak_id_wl_2,
			'pajak_id_wl_3' => $pajak_id_wl_3,
			'pajak_id_bp' => $pajak_id_bp,
			'pajak_id_bp_2' => $pajak_id_bp_2,
			'pajak_id_bp_3' => $pajak_id_bp_3,
			'pajak_id_tr' => $pajak_id_tr,
			'pajak_id_tr_2' => $pajak_id_tr_2,
			'pajak_id_tr_3' => $pajak_id_tr_3,
			'pajak_id_exc' => $pajak_id_exc,
			'pajak_id_dmp_4m3' => $pajak_id_dmp_4m3,
			'pajak_id_dmp_10m3' => $pajak_id_dmp_10m3,
			'pajak_id_sc' => $pajak_id_sc,
			'pajak_id_gns' => $pajak_id_gns,
			'pajak_id_wl_sc' => $pajak_id_wl_sc,

			'supplier_id_semen' => $supplier_id_semen,
			'supplier_id_pasir' => $supplier_id_pasir,
			'supplier_id_batu1020' => $supplier_id_batu1020,
			'supplier_id_batu2030' => $supplier_id_batu2030,
			'supplier_id_solar' => $supplier_id_solar,
			'supplier_id_bp' => $supplier_id_bp,
			'supplier_id_bp_2' => $supplier_id_bp_2,
			'supplier_id_bp_3' => $supplier_id_bp_3,
			'supplier_id_tm' => $supplier_id_tm,
			'supplier_id_tm_2' => $supplier_id_tm_2,
			'supplier_id_tm_3' => $supplier_id_tm_3,
			'supplier_id_tm_4' => $supplier_id_tm_4,
			'supplier_id_wl' => $supplier_id_wl,
			'supplier_id_wl_2' => $supplier_id_wl_2,
			'supplier_id_wl_3' => $supplier_id_wl_3,
			'supplier_id_tr' => $supplier_id_tr,
			'supplier_id_tr_2' => $supplier_id_tr_2,
			'supplier_id_tr_3' => $supplier_id_tr_3,
			'supplier_id_exc' => $supplier_id_exc,
			'supplier_id_dmp_4m3' => $supplier_id_dmp_4m3,
			'supplier_id_dmp_10m3' => $supplier_id_dmp_10m3,
			'supplier_id_sc' => $supplier_id_sc,
			'supplier_id_gns' => $supplier_id_gns,
			'supplier_id_wl_sc' => $supplier_id_wl_sc,
			
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);
		
		if ($this->db->insert('rak', $arr_insert)) {
			$rak_id = $this->db->insert_id();

			if (!file_exists('uploads/rak')) {
			    mkdir('uploads/rak', 0777, true);
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

					$config['upload_path'] = 'uploads/rak';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'rak_id' => $rak_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('lampiran_rak', $data[$i]);
					}
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal Membuat Rencana Kerja !!');
			redirect('admin/rencana_kerja');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Membuat Rencana Kerja !!');
			redirect('admin/rencana_kerja');
		}
	}
	
	public function table_rencana_kerja()
	{   
        $data = array();

        $this->db->select('rak.*, lk.lampiran, rak.status');		
		$this->db->join('lampiran_rak lk', 'rak.id = lk.rak_id','left');
		$this->db->where('rak.status','PUBLISH');
		$this->db->order_by('rak.tanggal_rencana_kerja','desc');			
		$query = $this->db->get('rak rak');
		
       	if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
				$row['tanggal_rencana_kerja'] = "<a href=" . base_url('rak/cetak_rencana_kerja/' . $row["id"]) .'" target="_blank">' .  date('d F Y',strtotime($row['tanggal_rencana_kerja'])) . "</a>";
				$row['lampiran'] = '<a href="' . base_url('uploads/rak/' . $row['lampiran']) .'" target="_blank">' . $row['lampiran'] . '</a>';  
				$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['updated_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['updated_by']),'admin_name');
                $row['updated_on'] = date('d/m/Y H:i:s',strtotime($row['updated_on']));
				$row['print'] = '<a href="'.site_url().'rak/cetak_rencana_kerja/'.$row['id'].'" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> </a>';
				
				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 15 || $this->session->userdata('admin_group_id') == 16){
				$row['edit'] = '<a href="'.site_url().'rak/sunting_rencana_kerja/'.$row['id'].'" class="btn btn-warning"><i class="fa fa-edit"></i> </a>';
				}else {
					$row['edit'] = '-';
				}

				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 15 || $this->session->userdata('admin_group_id') == 16){
				$row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['actions'] = '-';
				}

                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function delete_rencana_kerja()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('rak',array('id'=>$id));
			$this->db->delete('lampiran_rak',array('rak_id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}
	
	public function cetak_rencana_kerja($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['rak'] = $this->db->get_where('rak',array('id'=>$id))->row_array();
        $html = $this->load->view('rak/cetak_rencana_kerja',$data,TRUE);
        $rak = $this->db->get_where('rak',array('id'=>$id))->row_array();

		$pdf->SetTitle('BBJ - Rencana Kerja');
        $pdf->nsi_html($html);
        $pdf->Output('rencana_kerja.pdf', 'I');
	}

	public function sunting_rencana_kerja($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['tes'] = '';
			$data['rak'] = $this->db->get_where("rak", ["id" => $id])->row_array();
			$data['lampiran'] = $this->db->get_where("lampiran_rak", ["rak_id" => $id])->result_array();
			$data['komposisi'] = $this->db->select('id, jobs_type,date_agregat')->order_by('date_agregat','desc')->get_where('pmm_agregat',array('status'=>'PUBLISH'))->result_array();
			$data['semen'] = $this->pmm_model->getMatByPenawaranRencanaKerjaSemen();
			$data['pasir'] = $this->pmm_model->getMatByPenawaranRencanaKerjaPasir();
			$data['batu1020'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBatu1020();
			$data['batu2030'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBatu2030();
			$data['solar'] = $this->pmm_model->getMatByPenawaranRencanaKerjaSolar();
			$data['bp'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBP();
			$data['bp_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBP();
			$data['bp_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaBP();
			$data['tm'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['tm_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['tm_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['tm_4'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTM();
			$data['wl'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWL();
			$data['wl_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWL();
			$data['wl_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWL();
			$data['tr'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTransferSemen();
			$data['tr_2'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTransferSemen();
			$data['tr_3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaTransferSemen();
			$data['exc'] = $this->pmm_model->getMatByPenawaranRencanaKerjaEXC();
			$data['dmp_4m3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaDmp4M3();
			$data['dmp_10m3'] = $this->pmm_model->getMatByPenawaranRencanaKerjaDmp10M3();
			$data['sc'] = $this->pmm_model->getMatByPenawaranRencanaKerjaSC();
			$data['gns'] = $this->pmm_model->getMatByPenawaranRencanaKerjaGNS();
			$data['wl_sc'] = $this->pmm_model->getMatByPenawaranRencanaKerjaWLSC();
			$this->load->view('rak/sunting_rencana_kerja', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_sunting_rencana_kerja()
	{

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); #

		$id = $this->input->post('id');
		$vol_produk_a =  str_replace('.', '', $this->input->post('vol_produk_a'));
		$vol_produk_a =  str_replace(',', '.', $vol_produk_a);
		$vol_produk_b =  str_replace('.', '', $this->input->post('vol_produk_b'));
		$vol_produk_b =  str_replace(',', '.', $vol_produk_b);
		$vol_produk_c =  str_replace('.', '', $this->input->post('vol_produk_c'));
		$vol_produk_c =  str_replace(',', '.', $vol_produk_c);
		$vol_produk_d =  str_replace('.', '', $this->input->post('vol_produk_d'));
		$vol_produk_d =  str_replace(',', '.', $vol_produk_d);

		$vol_bbm_solar =  str_replace('.', '', $this->input->post('vol_bbm_solar'));
		$vol_bbm_solar =  str_replace(',', '.', $vol_bbm_solar);

		$komposisi_125 =  $this->input->post('komposisi_125');
		$komposisi_225 =  $this->input->post('komposisi_225');
		$komposisi_250 =  $this->input->post('komposisi_250');
		$komposisi_250_2 =  $this->input->post('komposisi_250_2');

		$price_a =  str_replace('.', '', $this->input->post('price_a'));
		$price_b =  str_replace('.', '', $this->input->post('price_b'));
		$price_c =  str_replace('.', '', $this->input->post('price_c'));
		$price_d =  str_replace('.', '', $this->input->post('price_d'));
		$insentif =  str_replace('.', '', $this->input->post('insentif'));
		$sewa_alat =  str_replace('.', '', $this->input->post('sewa_alat'));
		$overhead =  str_replace('.', '', $this->input->post('overhead'));

		$penawaran_id_semen =  $this->input->post('penawaran_id_semen');
		$penawaran_id_pasir =  $this->input->post('penawaran_id_pasir');
		$penawaran_id_batu1020 =  $this->input->post('penawaran_id_batu1020');
		$penawaran_id_batu2030 =  $this->input->post('penawaran_id_batu2030');
		$penawaran_id_solar =  $this->input->post('penawaran_id_solar');
		$penawaran_id_bp =  $this->input->post('penawaran_id_bp');
		$penawaran_id_bp_2 =  $this->input->post('penawaran_id_bp_2');
		$penawaran_id_bp_3 =  $this->input->post('penawaran_id_bp_3');
		$penawaran_id_tm =  $this->input->post('penawaran_id_tm');
		$penawaran_id_tm_2 =  $this->input->post('penawaran_id_tm_2');
		$penawaran_id_tm_3 =  $this->input->post('penawaran_id_tm_3');
		$penawaran_id_tm_4 =  $this->input->post('penawaran_id_tm_4');
		$penawaran_id_wl =  $this->input->post('penawaran_id_wl');
		$penawaran_id_wl_2 =  $this->input->post('penawaran_id_wl_2');
		$penawaran_id_wl_3 =  $this->input->post('penawaran_id_wl_3');
		$penawaran_id_tr =  $this->input->post('penawaran_id_tr');
		$penawaran_id_tr_2 =  $this->input->post('penawaran_id_tr_2');
		$penawaran_id_tr_3 =  $this->input->post('penawaran_id_tr_3');
		$penawaran_id_exc =  $this->input->post('penawaran_id_exc');
		$penawaran_id_dmp_4m3 =  $this->input->post('penawaran_id_dmp_4m3');
		$penawaran_id_dmp_10m3 =  $this->input->post('penawaran_id_dmp_10m3');
		$penawaran_id_sc =  $this->input->post('penawaran_id_sc');
		$penawaran_id_gns =  $this->input->post('penawaran_id_gns');
		$penawaran_id_wl_sc =  $this->input->post('penawaran_id_wl_sc');

		$harga_semen =  str_replace('.', '', $this->input->post('price_semen'));
		$harga_pasir =  str_replace('.', '', $this->input->post('price_pasir'));
		$harga_batu1020 =  str_replace('.', '', $this->input->post('price_batu1020'));
		$harga_batu2030 =  str_replace('.', '', $this->input->post('price_batu2030'));
		$harga_solar =  str_replace('.', '', $this->input->post('price_solar'));
		$harga_bp =  str_replace('.', '', $this->input->post('price_bp'));
		$harga_bp_2 =  str_replace('.', '', $this->input->post('price_bp_2'));
		$harga_bp_3 =  str_replace('.', '', $this->input->post('price_bp_3'));
		$harga_tm =  str_replace('.', '', $this->input->post('price_tm'));
		$harga_tm_2 =  str_replace('.', '', $this->input->post('price_tm_2'));
		$harga_tm_3 =  str_replace('.', '', $this->input->post('price_tm_3'));
		$harga_tm_4 =  str_replace('.', '', $this->input->post('price_tm_4'));
		$harga_wl =  str_replace('.', '', $this->input->post('price_wl'));
		$harga_wl_2 =  str_replace('.', '', $this->input->post('price_wl_2'));
		$harga_wl_3 =  str_replace('.', '', $this->input->post('price_wl_3'));
		$harga_tr =  str_replace('.', '', $this->input->post('price_tr'));
		$harga_tr_2 =  str_replace('.', '', $this->input->post('price_tr_2'));
		$harga_tr_3 =  str_replace('.', '', $this->input->post('price_tr_3'));
		$harga_exc =  str_replace('.', '', $this->input->post('price_exc'));
		$harga_dmp_4m3 =  str_replace('.', '', $this->input->post('price_dmp_4m3'));
		$harga_dmp_10m3 =  str_replace('.', '', $this->input->post('price_dmp_10m3'));
		$harga_sc =  str_replace('.', '', $this->input->post('price_sc'));
		$harga_gns =  str_replace('.', '', $this->input->post('price_gns'));
		$harga_wl_sc =  str_replace('.', '', $this->input->post('price_wl_sc'));

		$satuan_semen =  $this->input->post('measure_semen');
		$satuan_pasir =  $this->input->post('measure_pasir');
		$satuan_batu1020 =  $this->input->post('measure_batu1020');
		$satuan_batu2030 =  $this->input->post('measure_batu2030');
		$satuan_solar =  $this->input->post('measure_solar');
		$satuan_bp =  $this->input->post('measure_bp');
		$satuan_bp_2 =  $this->input->post('measure_bp_2');
		$satuan_bp_3 =  $this->input->post('measure_bp_3');
		$satuan_tm =  $this->input->post('measure_tm');
		$satuan_tm_2 =  $this->input->post('measure_tm_2');
		$satuan_tm_3 =  $this->input->post('measure_tm_3');
		$satuan_tm_4 =  $this->input->post('measure_tm_4');
		$satuan_wl =  $this->input->post('measure_wl');
		$satuan_wl_2 =  $this->input->post('measure_wl_2');
		$satuan_wl_3 =  $this->input->post('measure_wl_3');
		$satuan_tr =  $this->input->post('measure_tr');
		$satuan_tr_2 =  $this->input->post('measure_tr_2');
		$satuan_tr_3 =  $this->input->post('measure_tr_3');
		$satuan_exc =  $this->input->post('measure_exc');
		$satuan_dmp_4m3 =  $this->input->post('measure_dmp_4m3');
		$satuan_dmp_10m3 =  $this->input->post('measure_dmp_10m3');
		$satuan_sc =  $this->input->post('measure_sc');
		$satuan_gns =  $this->input->post('measure_gns');
		$satuan_wl_sc =  $this->input->post('measure_wl_sc');

		$tax_id_semen =  $this->input->post('tax_id_semen');
		$tax_id_pasir =  $this->input->post('tax_id_pasir');
		$tax_id_batu1020 =  $this->input->post('tax_id_batu1020');
		$tax_id_batu2030 =  $this->input->post('tax_id_batu2030');
		$tax_id_solar =  $this->input->post('tax_id_solar');
		$tax_id_bp =  $this->input->post('tax_id_bp');
		$tax_id_bp_2 =  $this->input->post('tax_id_bp_2');
		$tax_id_bp_3 =  $this->input->post('tax_id_bp_3');
		$tax_id_tm =  $this->input->post('tax_id_tm');
		$tax_id_tm_2 =  $this->input->post('tax_id_tm_2');
		$tax_id_tm_3 =  $this->input->post('tax_id_tm_3');
		$tax_id_tm_4 =  $this->input->post('tax_id_tm_4');
		$tax_id_wl =  $this->input->post('tax_id_wl');
		$tax_id_wl_2 =  $this->input->post('tax_id_wl_2');
		$tax_id_wl_3 =  $this->input->post('tax_id_wl_3');
		$tax_id_tr =  $this->input->post('tax_id_tr');
		$tax_id_tr_2 =  $this->input->post('tax_id_tr_2');
		$tax_id_tr_3 =  $this->input->post('tax_id_tr_3');
		$tax_id_exc =  $this->input->post('tax_id_exc');
		$tax_id_dmp_4m3 =  $this->input->post('tax_id_dmp_4m3');
		$tax_id_dmp_10m3 =  $this->input->post('tax_id_dmp_10m3');
		$tax_id_sc =  $this->input->post('tax_id_sc');
		$tax_id_gns =  $this->input->post('tax_id_gns');
		$tax_id_wl_sc =  $this->input->post('tax_id_wl_sc');

		$pajak_id_semen =  $this->input->post('pajak_id_semen');
		$pajak_id_pasir =  $this->input->post('pajak_id_pasir');
		$pajak_id_batu1020 =  $this->input->post('pajak_id_batu1020');
		$pajak_id_batu2030 =  $this->input->post('pajak_id_batu2030');
		$pajak_id_solar =  $this->input->post('pajak_id_solar');
		$pajak_id_bp =  $this->input->post('pajak_id_bp');
		$pajak_id_bp_2 =  $this->input->post('pajak_id_bp_2');
		$pajak_id_bp_3 =  $this->input->post('pajak_id_bp_3');
		$pajak_id_tm =  $this->input->post('pajak_id_tm');
		$pajak_id_tm_2 =  $this->input->post('pajak_id_tm_2');
		$pajak_id_tm_3 =  $this->input->post('pajak_id_tm_3');
		$pajak_id_tm_4 =  $this->input->post('pajak_id_tm_4');
		$pajak_id_wl =  $this->input->post('pajak_id_wl');
		$pajak_id_wl_2 =  $this->input->post('pajak_id_wl_2');
		$pajak_id_wl_3 =  $this->input->post('pajak_id_wl_3');
		$pajak_id_tr =  $this->input->post('pajak_id_tr');
		$pajak_id_tr_2 =  $this->input->post('pajak_id_tr_2');
		$pajak_id_tr_3 =  $this->input->post('pajak_id_tr_3');
		$pajak_id_exc =  $this->input->post('pajak_id_exc');
		$pajak_id_dmp_4m3 =  $this->input->post('pajak_id_dmp_4m3');
		$pajak_id_dmp_10m3 =  $this->input->post('pajak_id_dmp_10m3');
		$pajak_id_sc =  $this->input->post('pajak_id_sc');
		$pajak_id_gns =  $this->input->post('pajak_id_gns');
		$pajak_id_wl_sc =  $this->input->post('pajak_id_wl_sc');

		$supplier_id_semen =  $this->input->post('supplier_id_semen');
		$supplier_id_pasir =  $this->input->post('supplier_id_pasir');
		$supplier_id_batu1020 =  $this->input->post('supplier_id_batu1020');
		$supplier_id_batu2030 =  $this->input->post('supplier_id_batu2030');
		$supplier_id_solar =  $this->input->post('supplier_id_solar');
		$supplier_id_bp =  $this->input->post('supplier_id_bp');
		$supplier_id_bp_2 =  $this->input->post('supplier_id_bp_2');
		$supplier_id_bp_3 =  $this->input->post('supplier_id_bp_3');
		$supplier_id_tm =  $this->input->post('supplier_id_tm');
		$supplier_id_tm_2 =  $this->input->post('supplier_id_tm_2');
		$supplier_id_tm_3 =  $this->input->post('supplier_id_tm_3');
		$supplier_id_tm_4 =  $this->input->post('supplier_id_tm_4');
		$supplier_id_wl =  $this->input->post('supplier_id_wl');
		$supplier_id_wl_2 =  $this->input->post('supplier_id_wl_2');
		$supplier_id_wl_3 =  $this->input->post('supplier_id_wl_3');
		$supplier_id_tr =  $this->input->post('supplier_id_tr');
		$supplier_id_tr_2 =  $this->input->post('supplier_id_tr_2');
		$supplier_id_tr_3 =  $this->input->post('supplier_id_tr_3');
		$supplier_id_exc =  $this->input->post('supplier_id_exc');
		$supplier_id_dmp_4m3 =  $this->input->post('supplier_id_dmp_4m3');
		$supplier_id_dmp_10m3 =  $this->input->post('supplier_id_dmp_10m3');
		$supplier_id_sc =  $this->input->post('supplier_id_sc');
		$supplier_id_gns =  $this->input->post('supplier_id_gns');
		$supplier_id_wl_sc =  $this->input->post('supplier_id_wl_sc');

		$arr_update = array(

			'vol_produk_a' => $vol_produk_a,
			'vol_produk_b' => $vol_produk_b,
			'vol_produk_c' => $vol_produk_c,
			'vol_produk_d' => $vol_produk_d,

			'vol_bbm_solar' => $vol_bbm_solar,

			'price_a' => $price_a,
			'price_b' => $price_b,
			'price_c' => $price_c,
			'price_d' => $price_d,
			'insentif' => $insentif,
			'sewa_alat' => $sewa_alat,
			'overhead' => $overhead,

			'komposisi_125' => $komposisi_125,
			'komposisi_225' => $komposisi_225,
			'komposisi_250' => $komposisi_250,
			'komposisi_250_2' => $komposisi_250_2,

			'penawaran_id_semen' => $penawaran_id_semen,
			'penawaran_id_pasir' => $penawaran_id_pasir,
			'penawaran_id_batu1020' => $penawaran_id_batu1020,
			'penawaran_id_batu2030' => $penawaran_id_batu2030,
			'penawaran_id_solar' => $penawaran_id_solar,
			'penawaran_id_bp' => $penawaran_id_bp,
			'penawaran_id_bp_2' => $penawaran_id_bp_2,
			'penawaran_id_bp_3' => $penawaran_id_bp_3,
			'penawaran_id_tm' => $penawaran_id_tm,
			'penawaran_id_tm_2' => $penawaran_id_tm_2,
			'penawaran_id_tm_3' => $penawaran_id_tm_3,
			'penawaran_id_tm_4' => $penawaran_id_tm_4,
			'penawaran_id_wl' => $penawaran_id_wl,
			'penawaran_id_wl_2' => $penawaran_id_wl_2,
			'penawaran_id_wl_3' => $penawaran_id_wl_3,
			'penawaran_id_tr' => $penawaran_id_tr,
			'penawaran_id_tr_2' => $penawaran_id_tr_2,
			'penawaran_id_tr_3' => $penawaran_id_tr_3,
			'penawaran_id_tr_3' => $penawaran_id_tr_3,
			'penawaran_id_exc' => $penawaran_id_exc,
			'penawaran_id_dmp_4m3' => $penawaran_id_dmp_4m3,
			'penawaran_id_dmp_10m3' => $penawaran_id_dmp_10m3,
			'penawaran_id_sc' => $penawaran_id_sc,
			'penawaran_id_gns' => $penawaran_id_gns,
			'penawaran_id_wl_sc' => $penawaran_id_wl_sc,

			'harga_semen' => $harga_semen,
			'harga_pasir' => $harga_pasir,
			'harga_batu1020' => $harga_batu1020,
			'harga_batu2030' => $harga_batu2030,
			'harga_solar' => $harga_solar,
			'harga_bp' => $harga_bp,
			'harga_bp_2' => $harga_bp_2,
			'harga_bp_3' => $harga_bp_3,
			'harga_tm' => $harga_tm,
			'harga_tm_2' => $harga_tm_2,
			'harga_tm_3' => $harga_tm_3,
			'harga_tm_4' => $harga_tm_4,
			'harga_wl' => $harga_wl,
			'harga_wl_2' => $harga_wl_2,
			'harga_wl_3' => $harga_wl_3,
			'harga_tr' => $harga_tr,
			'harga_tr_2' => $harga_tr_2,
			'harga_tr_3' => $harga_tr_3,
			'harga_exc' => $harga_exc,
			'harga_dmp_4m3' => $harga_dmp_4m3,
			'harga_dmp_10m3' => $harga_dmp_10m3,
			'harga_sc' => $harga_sc,
			'harga_gns' => $harga_gns,
			'harga_wl_sc' => $harga_wl_sc,

			'satuan_semen' => $satuan_semen,
			'satuan_pasir' => $satuan_pasir,
			'satuan_batu1020' => $satuan_batu1020,
			'satuan_batu2030' => $satuan_batu2030,
			'satuan_solar' => $satuan_solar,
			'satuan_bp' => $satuan_bp,
			'satuan_bp_2' => $satuan_bp_2,
			'satuan_bp_3' => $satuan_bp_3,
			'satuan_tm' => $satuan_tm,
			'satuan_tm_2' => $satuan_tm_2,
			'satuan_tm_3' => $satuan_tm_3,
			'satuan_tm_4' => $satuan_tm_4,
			'satuan_wl' => $satuan_wl,
			'satuan_wl_2' => $satuan_wl_2,
			'satuan_wl_3' => $satuan_wl_3,
			'satuan_tr' => $satuan_tr,
			'satuan_tr_2' => $satuan_tr_2,
			'satuan_tr_3' => $satuan_tr_3,
			'satuan_exc' => $satuan_exc,
			'satuan_dmp_4m3' => $satuan_dmp_4m3,
			'satuan_dmp_10m3' => $satuan_dmp_10m3,
			'satuan_sc' => $satuan_sc,
			'satuan_gns' => $satuan_gns,
			'satuan_wl_sc' => $satuan_wl_sc,

			'tax_id_semen' => $tax_id_semen,
			'tax_id_pasir' => $tax_id_pasir,
			'tax_id_batu1020' => $tax_id_batu1020,
			'tax_id_batu2030' => $tax_id_batu2030,
			'tax_id_solar' => $tax_id_solar,
			'tax_id_bp' => $tax_id_bp,
			'tax_id_bp_2' => $tax_id_bp_2,
			'tax_id_bp_3' => $tax_id_bp_3,
			'tax_id_tm' => $tax_id_tm,
			'tax_id_tm_2' => $tax_id_tm_2,
			'tax_id_tm_3' => $tax_id_tm_3,
			'tax_id_tm_4' => $tax_id_tm_4,
			'tax_id_wl' => $tax_id_wl,
			'tax_id_wl_2' => $tax_id_wl_2,
			'tax_id_wl_3' => $tax_id_wl_3,
			'tax_id_bp' => $tax_id_bp,
			'tax_id_bp_2' => $tax_id_bp_2,
			'tax_id_bp_3' => $tax_id_bp_3,
			'tax_id_tr' => $tax_id_tr,
			'tax_id_tr_2' => $tax_id_tr_2,
			'tax_id_tr_3' => $tax_id_tr_3,
			'tax_id_exc' => $tax_id_exc,
			'tax_id_dmp_4m3' => $tax_id_dmp_4m3,
			'tax_id_dmp_10m3' => $tax_id_dmp_10m3,
			'tax_id_sc' => $tax_id_sc,
			'tax_id_gns' => $tax_id_gns,
			'tax_id_wl_sc' => $tax_id_wl_sc,

			'pajak_id_semen' => $pajak_id_semen,
			'pajak_id_pasir' => $pajak_id_pasir,
			'pajak_id_batu1020' => $pajak_id_batu1020,
			'pajak_id_batu2030' => $pajak_id_batu2030,
			'pajak_id_solar' => $pajak_id_solar,
			'pajak_id_bp' => $pajak_id_bp,
			'pajak_id_bp_2' => $pajak_id_bp_2,
			'pajak_id_bp_3' => $pajak_id_bp_3,
			'pajak_id_tm' => $pajak_id_tm,
			'pajak_id_tm_2' => $pajak_id_tm_2,
			'pajak_id_tm_3' => $pajak_id_tm_3,
			'pajak_id_tm_4' => $pajak_id_tm_4,
			'pajak_id_wl' => $pajak_id_wl,
			'pajak_id_wl_2' => $pajak_id_wl_2,
			'pajak_id_wl_3' => $pajak_id_wl_3,
			'pajak_id_bp' => $pajak_id_bp,
			'pajak_id_bp_2' => $pajak_id_bp_2,
			'pajak_id_bp_3' => $pajak_id_bp_3,
			'pajak_id_tr' => $pajak_id_tr,
			'pajak_id_tr_2' => $pajak_id_tr_2,
			'pajak_id_tr_3' => $pajak_id_tr_3,
			'pajak_id_exc' => $pajak_id_exc,
			'pajak_id_dmp_4m3' => $pajak_id_dmp_4m3,
			'pajak_id_dmp_10m3' => $pajak_id_dmp_10m3,
			'pajak_id_sc' => $pajak_id_sc,
			'pajak_id_gns' => $pajak_id_gns,
			'pajak_id_wl_sc' => $pajak_id_wl_sc,

			'supplier_id_semen' => $supplier_id_semen,
			'supplier_id_pasir' => $supplier_id_pasir,
			'supplier_id_batu1020' => $supplier_id_batu1020,
			'supplier_id_batu2030' => $supplier_id_batu2030,
			'supplier_id_solar' => $supplier_id_solar,
			'supplier_id_bp' => $supplier_id_bp,
			'supplier_id_bp_2' => $supplier_id_bp_2,
			'supplier_id_bp_3' => $supplier_id_bp_3,
			'supplier_id_tm' => $supplier_id_tm,
			'supplier_id_tm_2' => $supplier_id_tm_2,
			'supplier_id_tm_3' => $supplier_id_tm_3,
			'supplier_id_tm_4' => $supplier_id_tm_4,
			'supplier_id_wl' => $supplier_id_wl,
			'supplier_id_wl_2' => $supplier_id_wl_2,
			'supplier_id_wl_3' => $supplier_id_wl_3,
			'supplier_id_tr' => $supplier_id_tr,
			'supplier_id_tr_2' => $supplier_id_tr_2,
			'supplier_id_tr_3' => $supplier_id_tr_3,
			'supplier_id_exc' => $supplier_id_exc,
			'supplier_id_dmp_4m3' => $supplier_id_dmp_4m3,
			'supplier_id_dmp_10m3' => $supplier_id_dmp_10m3,
			'supplier_id_sc' => $supplier_id_sc,
			'supplier_id_gns' => $supplier_id_gns,
			'supplier_id_wl_sc' => $supplier_id_wl_sc,
				
			'status' => 'PUBLISH',
			'updated_by' => $this->session->userdata('admin_id'),
			'updated_on' => date('Y-m-d H:i:s')
			);

			$this->db->where('id', $id);
			if ($this->db->update('rak', $arr_update)) {
				
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error', 'Gagal Memperbaharui Rencana Kerja !!');
				redirect('admin/rencana_kerja');
			} else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success', 'Berhasil Memperbaharui Rencana Kerja !!');
				redirect('admin/rencana_kerja');
			}
	}

	public function form_rencana_cash_flow()
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['products'] =  $this->db->select('*')
			->from('produk p')
			->where("p.status = 'PUBLISH'")
			->where("p.kategori_produk = 2 ")
			->order_by('nama_produk','asc')
			->get()->result_array();
			$this->load->view('rak/form_rencana_cash_flow', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_rencana_cash_flow()
	{
		$tanggal_rencana_kerja = $this->input->post('tanggal_rencana_kerja');
		$biaya_bahan =  str_replace('.', '', $this->input->post('biaya_bahan'));
		$biaya_alat =  str_replace('.', '', $this->input->post('biaya_alat'));
		$biaya_bank =  str_replace('.', '', $this->input->post('biaya_bank'));
		$overhead =  str_replace('.', '', $this->input->post('overhead'));
		$termin =  str_replace('.', '', $this->input->post('termin'));
		$biaya_persiapan =  str_replace('.', '', $this->input->post('biaya_persiapan'));

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 

		$arr_insert = array(
			'tanggal_rencana_kerja' =>  date('Y-m-d', strtotime($tanggal_rencana_kerja)),
			'biaya_bahan' => $biaya_bahan,
			'biaya_alat' => $biaya_alat,
			'biaya_bank' => $biaya_bank,
			'overhead' => $overhead,
			'termin' => $termin,
			'biaya_persiapan' => $biaya_persiapan,
			'status' => 'PUBLISH',
			'created_by' => $this->session->userdata('admin_id'),
			'created_on' => date('Y-m-d H:i:s')
		);
		
		if ($this->db->insert('rencana_cash_flow', $arr_insert)) {
			$rencana_cash_flow_id = $this->db->insert_id();

			if (!file_exists('uploads/rencana_cash_flow')) {
			    mkdir('uploads/rencana_cash_flow', 0777, true);
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

					$config['upload_path'] = 'uploads/rencana_cash_flow';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['file_name'] = $_FILES['files']['name'][$i];

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];

						$data['totalFiles'][] = $filename;


						$data[$i] = array(
							'rencana_cash_flow_id' => $rencana_cash_flow_id,
							'lampiran'  => $data['totalFiles'][$i]
						);

						$this->db->insert('lampiran_rencana_cash_flow', $data[$i]);
					}
				}
			}
		}


		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			$this->db->trans_rollback();
			$this->session->set_flashdata('notif_error', 'Gagal Membuat Rencana Cash FLow !!');
			redirect('admin/rencana_cash_flow');
		} else {
			# Everything is Perfect. 
			# Committing data to the database.
			$this->db->trans_commit();
			$this->session->set_flashdata('notif_success', 'Berhasil Membuat Rencana Cash Flow !!');
			redirect('admin/rencana_cash_flow');
		}
	}

	public function table_rencana_cash_flow()
	{   
        $data = array();

        $this->db->select('rak.*, lk.lampiran, rak.status');		
		$this->db->join('lampiran_rencana_cash_flow lk', 'rak.id = lk.rencana_cash_flow_id','left');
		$this->db->where('rak.status','PUBLISH');
		$this->db->order_by('rak.tanggal_rencana_kerja','desc');			
		$query = $this->db->get('rencana_cash_flow rak');
		
       	if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
				$row['tanggal_rencana_kerja'] = date('d F Y',strtotime($row['tanggal_rencana_kerja']));
				$row['lampiran'] = '<a href="' . base_url('uploads/rencana_cash_flow/' . $row['lampiran']) .'" target="_blank">' . $row['lampiran'] . '</a>';  
				$row['created_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
				$row['updated_by'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['updated_by']),'admin_name');
                $row['updated_on'] = date('d/m/Y H:i:s',strtotime($row['updated_on']));
				$row['print'] = '<a href="'.site_url().'rak/cetak_rencana_cash_flow/'.$row['id'].'" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> </a>';
				
				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10 || $this->session->userdata('admin_group_id') == 15){
				$row['edit'] = '<a href="'.site_url().'rak/sunting_rencana_cash_flow/'.$row['id'].'" class="btn btn-warning"><i class="fa fa-edit"></i> </a>';
				}else {
					$row['edit'] = '-';
				}

				if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 4 || $this->session->userdata('admin_group_id') == 5 || $this->session->userdata('admin_group_id') == 6 || $this->session->userdata('admin_group_id') == 10){
				$row['delete'] = '<a href="javascript:void(0);" onclick="DeleteData3('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
				}else {
					$row['delete'] = '-';
				}

                $data[] = $row;
            }

        }
        echo json_encode(array('data'=>$data));
    }

	public function delete_rencana_cash_flow()
	{
		$output['output'] = false;
		$id = $this->input->post('id');
		if(!empty($id)){
			$this->db->delete('rencana_cash_flow',array('id'=>$id));
			$this->db->delete('lampiran_rencana_cash_flow',array('rencana_cash_flow_id'=>$id));
			{
				$output['output'] = true;
			}
		}
		echo json_encode($output);
	}

	public function cetak_rencana_cash_flow($id){

		$this->load->library('pdf');
	

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(true);
		$pdf->setPrintFooter(true);
        $pdf->SetFont('helvetica','',7); 
        $tagvs = array('div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
		$pdf->setHtmlVSpace($tagvs);
		$pdf->AddPage('P');

		$data['rak'] = $this->db->get_where('rencana_cash_flow',array('id'=>$id))->row_array();
        $html = $this->load->view('rak/cetak_rencana_cash_flow',$data,TRUE);
        $rak = $this->db->get_where('rencana_cash_flow',array('id'=>$id))->row_array();

		$pdf->SetTitle('BBJ - Rencana Cash Flow');
        $pdf->nsi_html($html);
        $pdf->Output('rencana_cash_flow.pdf', 'I');
	}

	public function sunting_rencana_cash_flow($id)
	{
		$check = $this->m_admin->check_login();
		if ($check == true) {
			$data['tes'] = '';
			$data['rak'] = $this->db->get_where("rencana_cash_flow", ["id" => $id])->row_array();
			$data['lampiran'] = $this->db->get_where("lampiran_rencana_cash_flow", ["rencana_cash_flow_id" => $id])->result_array();
			$this->load->view('rak/sunting_rencana_cash_flow', $data);
		} else {
			redirect('admin');
		}
	}

	public function submit_sunting_rencana_cash_flow()
	{

		$this->db->trans_start(); # Starting Transaction
		$this->db->trans_strict(FALSE); #

			$id = $this->input->post('id');
			$biaya_bahan =  str_replace('.', '', $this->input->post('biaya_bahan'));
			$biaya_alat =  str_replace('.', '', $this->input->post('biaya_alat'));
			$biaya_bank =  str_replace('.', '', $this->input->post('biaya_bank'));
			$overhead =  str_replace('.', '', $this->input->post('overhead'));
			$termin =  str_replace('.', '', $this->input->post('termin'));
			$biaya_persiapan =  str_replace('.', '', $this->input->post('biaya_persiapan'));

			$arr_update = array(
				'biaya_bahan' => $biaya_bahan,
				'biaya_alat' => $biaya_alat,
				'biaya_bank' => $biaya_bank,
				'overhead' => $overhead,
				'termin' => $termin,
				'biaya_persiapan' => $biaya_persiapan,
				'status' => 'PUBLISH',
				'updated_by' => $this->session->userdata('admin_id'),
				'updated_on' => date('Y-m-d H:i:s')
			);

			$this->db->where('id', $id);
			if ($this->db->update('rencana_cash_flow', $arr_update)) {
				
			}

			if ($this->db->trans_status() === FALSE) {
				# Something went wrong.
				$this->db->trans_rollback();
				$this->session->set_flashdata('notif_error', 'Gagal Memperbaharui Rencana Cash Flow !!');
				redirect('admin/rencana_cash_flow');
			} else {
				# Everything is Perfect. 
				# Committing data to the database.
				$this->db->trans_commit();
				$this->session->set_flashdata('notif_success', 'Berhasil Memperbaharui Rencana Cash Flow !!');
				redirect('admin/rencana_cash_flow');
			}
	}

}
?>