<?php
class M_product_category extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('general');
        $this->load->model('crud_global');
        $this->load->helper('directory');
    }


    function _get_datatables_query($table,$column_order,$column_search,$order,$column_select=false,$column_join=false)
    {

        $this->db->join('tbl_product_category_data','tbl_product_category_data.product_category_id = tbl_product_category.product_category_id','left');

        $this->db->from($table);
        $i = 0;
        foreach ($column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            // print_r($_POST['order']);
        } 
        else if(isset($order))
        {
            $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables($table,$column_order,$column_search,$order,$arraywhere,$column_select=false,$column_join=false)
    {
        $this->_get_datatables_query($table,$column_order,$column_search,$order,$column_select,$column_join);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        if(is_array($arraywhere)){
            foreach ($arraywhere as $key => $value) {
                $this->db->where($key,$value);
            }
        }
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($table,$column_order,$column_search,$order,$arraywhere,$column_select=false,$column_join=false)
    {
        $this->_get_datatables_query($table,$column_order,$column_search,$order,$arraywhere,$column_select,$column_join);
        if(is_array($arraywhere)){
            foreach ($arraywhere as $key => $value) {
                $this->db->where($key,$value);
            }
        }
        $query = $this->db->get();

        return $query->num_rows();
    }
 
    public function count_all($table,$arraywhere)
    {
        $this->db->from($table);
        if(is_array($arraywhere)){
            foreach ($arraywhere as $key => $value) {
                $this->db->where($key,$value);
            }
        }
        return $this->db->count_all_results();
    }

}