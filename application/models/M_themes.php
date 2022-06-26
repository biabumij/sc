<?php
class M_themes extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('general');
        $this->load->library('filter');
    }


    function ArrThemes()
    {
        $output=false;
        $this->db->select("*");
        $this->db->where('site_status',1);
        $query=$this->db->get('tbl_themes_options');
        if($query->num_rows() > 0){
            $output=$query->result();
        }else {
            $output=false;
        }

        return $output;
    }

    function GetThemes($get_name)
    {
        $output=false;
        $arr = $this->ArrThemes();
        if(is_array($arr)){
            foreach ($arr as $key => $row) {
                $output=$row->$get_name;
            }
        }

        return $output;

    }

    

}
