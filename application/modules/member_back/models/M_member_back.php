<?php
class M_member_back extends CI_Model {

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

        $this->db->join('tbl_member_info','tbl_member_info.member_id = tbl_member.member_id','left');

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


    function GetMemberInfo($id,$field)
    {
        $output = false;

        if(!empty($id)){
            $output = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$id),$field);
        }

        return $output;
    }


    function ArrMemberType()
    {
        $output = false;

        $arr = array(
            1 => 'User',
            2 => 'Member',
            3 => 'PDPP',
            4 => 'PDPN'
            );

        $output = $arr;

        return $output;
    }

    function GetMemberType($id)
    {
        $output = false;

        if(!empty($id)){
            $arr = $this->ArrMemberType();
            if(is_array($arr)){
                foreach ($arr as $key => $row) {
                    if($id == $key){
                        $output = $row;
                    }
                }
            }
        }

        return $output;
    }

    function SelectMemberType($selected=false)
    {
        $arr=$this->ArrMemberType();

        if(is_array($arr)){
            ?>
            <select class="form-control" title="...Choose Member Type..." name="member_type" aria-required="true" aria-describedby="digits-error" type="select" style="width: 100%;" id="member_type">
                <?php
                foreach ($arr as $key => $value) {
                    if(!empty($selected)){
                        if($key == $selected){
                            $active= 'selected';
                        }else {
                            $active= false;
                        }
                    }else {
                        $active= false;
                    }
                    ?>
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $value;?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

}