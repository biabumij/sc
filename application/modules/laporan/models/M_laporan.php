<?php

class M_laporan extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('crud_global');
    }

    function biaya_langsung($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',15);
        $this->db->where('b.status','PAID');
        $this->db->group_by('pdb.id');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function biaya_langsung_jurnal($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',15);
        $this->db->where('b.status','PAID');
        $this->db->group_by('pdb.id');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function showBiaya($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',16);
        $this->db->where('b.status','PAID');
        $this->db->group_by('pdb.id');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function showBiayaJurnal($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',16);
        $this->db->where('b.status','PAID');
        $this->db->group_by('pdb.id');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function showBiayaLainnya($date)
    {
        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',17);
        $this->db->where('b.status','PAID');
        $this->db->group_by('pdb.id');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function showBiayaLainnyaJurnal($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',17);
        $this->db->where('b.status','PAID');
        $this->db->group_by('pdb.id');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function biaya_langsung_print($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',15);
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function biaya_langsung_jurnal_print($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',15);
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function showBiaya_print($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',16);
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function showBiayaJurnal_print($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',16);
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function showBiayaLainnya_print($date)
    {
        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.jumlah) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',17);
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_biaya b');
        return $query->result_array();
    }

    function showBiayaLainnyaJurnal_print($date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));

        $this->db->select('c.id as coa_id, c.coa_number, c.coa, c.coa_parent, SUM(pdb.debit) as total, b.tanggal_transaksi, pdb.deskripsi, b.nomor_transaksi');
        $this->db->join('pmm_detail_jurnal pdb','b.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('b.tanggal_transaksi >=',$start_date.' 00:00:00');
        $this->db->where('b.tanggal_transaksi <=',$end_date.' 23:59:59');
        $this->db->where('c.coa_category',17);
        $this->db->where('b.status','PAID');
        $this->db->group_by('c.coa');
        $this->db->order_by('c.coa','asc');
        $this->db->order_by('b.tanggal_transaksi','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
        return $query->result_array();
    }

    function getTotal($id,$date)
    {

        $arr_date = explode(' - ', $date);
        $start_date = date('Y-m-d',strtotime($arr_date[0]));
        $end_date = date('Y-m-d',strtotime($arr_date[1]));
        $total_1 = 0;
        $total_2 = 0;
        // $this->db->select('(SUM(t.debit) - SUM(t.credit)) as total');
        // $this->db->where('t.coa_id',$id);
        // $total = $this->db->get('transactions t')->row_array();


        $this->db->select('(SUM(t.debit) - SUM(t.credit)) as total');
        $this->db->join('pmm_coa c','t.coa_id = c.id');
        $this->db->where('c.coa_parent',$id);
         $this->db->where('t.created_on >=',$start_date.' 00:00:00');
        $this->db->where('t.created_on <=',$end_date.' 23:59:59');
        $this->db->group_by('c.id');
        $total_parent = $this->db->get('transactions t')->row_array();

        if(!empty($total)){
            $total_1 = $total['total'];
        }
        if(!empty($total_parent)){
            $total_2 = $total_parent['total'];
        }

        return $total_2;
    }

    function buildTree(array $elements, $parentId = 0) 
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['coa_parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['coa_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

}
