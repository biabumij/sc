<?php

class M_admin extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function ProcessLogin($email,$password)
    {
    	if(!empty($email) && !empty($password)){
            $this->db->select("*");
            $this->db->where('admin_email',$email);
            $this->db->where('admin_password',$password);
            $this->db->where('status',1);
            $this->db->limit(1);
            $query=$this->db->get('tbl_admin');
            if($query->num_rows() > 0){
                $row=$query->row();
                $admin_id = $row->admin_id;
                $session_data = array('admin_id'=>$admin_id,'admin_email'=>$email,'admin_name'=>$row->admin_name,'admin_group_id'=>$row->admin_group_id);
                $this->session->set_userdata($session_data);
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }


    function check_login()
    {
        $sess_admin_id = $this->session->userdata('admin_id');
        $sess_email = $this->session->userdata('admin_email');

        if(!empty($sess_admin_id) && !empty($sess_email)){
            return true;
        }else {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->set_userdata('redirect', $uri);
            return false;
        }
    }


    function SelectMenu($selected=false,$edit=false)
    {
        ?>
        <select class="form-control selectpicker" data-live-search="true" title="...Choose parent..." name="parent_menu" type="select">
            <?php
            $active_p = false;
            if($selected == 0){
                $active_p = 'selected';
            }else {
                $active_p = false;
            }
            ?>
            <option value="0" <?php echo $active_p;?>>Parent</option>
            <?php
            $arr_pages = $this->crud_global->ShowTableNew('tbl_menu',array('status'=>1,'parent_id'=>0));
            if(is_array($arr_pages)){
                foreach ($arr_pages as $key => $row) {
                    $active = false; 
                    if($selected == $row->menu_id){
                        $active = 'selected';
                    }else{
                        $active = false;
                    }
                    ?>
                    <option value="<?php echo $row->menu_id;?>" <?php echo $active;?>><?php echo $row->menu_name;?></option>
                    <?php   
                }
            }
            ?>
        </select>
        <?php
    }

    function SelectMenuAccess($id=false)
    {
        $this->db->select('*');
        $this->db->order_by('parent_id','asc');
        // $this->db->where('parent_id',0);
        $this->db->where('status !=',0);
        $query=$this->db->get('tbl_menu');
        if($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                $checked = false;
                $active = false;

                $create = false;
                $update = false;
                $delete = false;

                $all_actions=false;

                if(!empty($id)){
                    // print_r($id);
                    $arr_admin_access = $this->crud_global->ShowTableDefault('tbl_admin_access',array('admin_group_id'=>$id));
                    if(is_array($arr_admin_access)){
                        foreach ($arr_admin_access as $key => $value) {
                            if($row->menu_id == $value->menu_id){
                                $active = 'active';
                                $checked = 'checked';

                                $create = $this->crud_global->GetField('tbl_admin_access',array('menu_id'=>$value->menu_id,'admin_group_id'=>$id),'create_action');
                                $update = $this->crud_global->GetField('tbl_admin_access',array('menu_id'=>$value->menu_id,'admin_group_id'=>$id),'update_action');
                                $delete = $this->crud_global->GetField('tbl_admin_access',array('menu_id'=>$value->menu_id,'admin_group_id'=>$id),'delete_action');

                                if($create == 1){
                                    $create = '1,';
                                }else {
                                    $create = false;
                                }
                                if($update == 1){
                                    $update = '2,';
                                }else {
                                    $update = false;
                                }

                                if($delete == 1){
                                    $delete = 3;
                                }else {
                                    $delete = false;
                                }
                            }
                        }
                        $all_actions = $create.$update.$delete;
                    }
                    // $ex = explode(",", $all_actions);
                }
                ?>
                <div class="row">
                    <div class="col-sm-5">
                        <div data-toggle="buttons" style="margin-bottom:15px;">
                          <label class="inner-btn-access btn btn-default btn-block <?php echo $active;?>" style="text-align:left;">
                            <input class="btn-check-menu" data-actions="menu-<?php echo $row->menu_id;?>" type="checkbox" autocomplete="off" name="menu_id[]" value="<?php echo $row->menu_id;?>" <?php echo $checked;?>> <?php echo $row->menu_name;?>
                            <span class="icon-access-menu"><i class="fa fa-check"></i></span>
                          </label>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="action-menu-class <?php echo $active;?>" id="menu-<?php echo $row->menu_id;?>">
                            <select class="form-control selectpicker select-action_<?php echo $row->menu_id;?>" name="actions_menu_<?php echo $row->menu_id;?>[]" multiple data-required="false">
                              <option value="1">Create</option>
                              <option value="2">Update</option>
                              <option value="3">Delete</option>
                            </select>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(".select-action_<?php echo $row->menu_id;?>").val([<?php echo $all_actions;?>]);
                </script>
                <?php
            }
        }
    }

    function SelectAdminGroup($selected=false)
    {   
        ?>
        <select class="form-control select2" data-live-search="true" title="...Choose Admin Group..." name="admin_group_id" type="select" style="width: 100%;">
            <?php
            $arraywhere = false;
            $admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$this->session->userdata('admin_id')),'admin_group_id');
            if($admin_group_id != 1){
                $arraywhere = array('admin_group_id !='=>1);
            }
            $arr_data = $this->crud_global->ShowTableNoOrder('tbl_admin_group',$arraywhere);
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    $active = false;
                    if($selected != 0){
                        if($selected == $row->admin_group_id){
                            $active = 'selected';
                        }else {
                            $active = false;
                        }
                    }

                    ?>
                    <option value="<?php echo $row->admin_group_id;?>" <?php echo $active;?>><?php echo $row->admin_group_name;?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }

    function GetAdminGroup($id)
    {
        $output =false;
        if(!empty($id)){
            $output = $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$id,'status !='=>0),'admin_group_name');
        }else {
            $output =false;
        }
        return $output;
    }

    function SelectAdminMultiple($selected=false)
    {
        $arrdata=$this->crud_global->ShowTableNoOrder('tbl_admin',false);
        if(is_array($arrdata)){
            ?>
            <select class="form-control select-admin" name="admin_id[]" title="...Choose Admin..." multiple data-required="false">
                <?php
                foreach ($arrdata as $key => $row) {
                    ?> 
                    <option value="<?php echo $row->admin_id;?>" ><?php echo $row->admin_name;?></option>
                    <?php
                }
                ?>
            </select>

            <script type="text/javascript">
                $(".select-admin").val([<?php echo $selected;?>]);
            </script>
            <?php
        }
    }

    function GetVisitors()
    {
        $this->load->library('user_agent');
        $sess_visitor = $this->session->userdata('visitors_id');
        if(empty($sess_visitor)){
            $ip = $this->input->ip_address();
            $platform = $this->agent->platform();
            if ($this->agent->is_browser())
            {
                    $agent = $this->agent->browser().' '.$this->agent->version();
            }
            elseif ($this->agent->is_robot())
            {
                    $agent = $this->agent->robot();
            }
            elseif ($this->agent->is_mobile())
            {
                    $agent = $this->agent->mobile();
            }
            else
            {
                    $agent = 'Unidentified User Agent';
            }
            $datecreated = date("Y-m-d H:i:s");

            $arrayvalues = array(
                    'ip_address' => $ip,
                    'platform' => $platform,
                    'platform_agent' => $agent,
                    'datecreated' => $datecreated
                );
            $query = $this->db->insert('tbl_visitors',$arrayvalues);
            if($query){
                $visitors_id = $this->db->insert_id();
                $arr_sess = array('visitors_id' => $visitors_id);
                $this->session->set_userdata($arr_sess);
            }
        }
    }

}