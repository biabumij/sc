<?php
class M_post_category extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('general');
        $this->load->library('filter');
    }

    function AddProcess()
    {
        $output=array('output'=>'false');

        $post_cat_name=$this->filter->FilterInput($this->input->post('post_cat_name'));
        $post_cat_alias=strtolower(str_replace(' ', '_', $post_cat_name));
        $order_id=$this->input->post('order_id');
        $admin_id=$this->session->userdata('admin_id');
        $order_by = $this->input->post('order_by');
        $settings_post = $this->input->post('settings_post');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");
        $ex_settings_post = false;
        if(!empty($settings_post)){
            $ex_settings_post = implode(',',$settings_post);
        }else {
            $ex_settings_post = false;
        }

        if(!empty($post_cat_name) && !empty($post_cat_alias) && !empty($order_id) && !empty($admin_id) && !empty($status) && !empty($datecreated)){

            $check = $this->crud_global->GetNum('tbl_post_category',array('status !=' => 0,'order_id'=>$order_id));
            if(!empty($check)){
                $output=array('output'=>'order');                
            }else {
                $arrayvalues = array(
                    'post_category_name' => $post_cat_name,
                    'post_category_alias' => $post_cat_alias,
                    'admin_id' => $admin_id,
                    'action' => $ex_settings_post,
                    'order_by' => $order_by,
                    'order_id' => $order_id,
                    'datecreated' => $datecreated,
                    'status' => $status
                );
                $query=$this->db->insert('tbl_post_category',$arrayvalues);
                if($query){
                    $output=array('output'=>'true');
                }else {
                    $output=array('output'=>'false');
                }
            }
        }else {
            $output=array('output'=>'false');
        }
        echo json_encode($output);
    }


    function EditProcess()
    {
        $output=array('output'=>'false');

        $id=$this->input->post('id');
        $post_cat_name=$this->filter->FilterInput($this->input->post('post_cat_name'));
        $post_cat_alias=strtolower(str_replace(' ', '_', $post_cat_name));
        $order_id=$this->input->post('order_id');
        $order_by = $this->input->post('order_by');
        $settings_post = $this->input->post('settings_post');
        $admin_id=$this->session->userdata('admin_id');
        $status = $this->input->post('status');
        // $datecreated = date("Y-m-d H:i:s");

        $ex_settings_post = false;
        if(!empty($settings_post)){
            $ex_settings_post = implode(',',$settings_post);
        }else {
            $ex_settings_post = "";
        }

        if(!empty($post_cat_name) || !empty($post_cat_alias) || !empty($order_id) || !empty($admin_id) || !empty($status) || !empty($datecreated)){

            $check = $this->crud_global->GetNum('tbl_post_category',array('status !=' => 0,'order_id'=>$order_id));
            $check_data = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$id),'order_id');

            $output_order =false;

            if($order_id != $check_data){
                if(!empty($check)){
                    $output_order = true;
                }else {
                    $output_order =false;
                }
            }else {
                $output_order =false;
            }

            if($output_order == true){
                $output=array('output'=>'order');
            }else {
                $arrayvalues = array(
                    'post_category_name' => $post_cat_name,
                    'post_category_alias' => $post_cat_alias,
                    'admin_id' => $admin_id,
                    'action' => $ex_settings_post,
                    'order_by' => $order_by,
                    'order_id' => $order_id,
                    // 'datecreated' => $datecreated,
                    'status' => $status
                );
                $arraywhere=array('post_category_id'=>$id);
                $query=$this->crud_global->UpdateDefault('tbl_post_category',$arrayvalues,$arraywhere);
                if($query){
                    $output=array('output'=>'true');
                }else {
                    $output=array('output'=>'false');
                }
            }

        }else {
            $output=array('output'=>'false');
        }
        echo json_encode($output);
    }

    function Select($selected=false)
    {
        $arrdata=$this->crud_global->ShowTable('tbl_post_category',false);
        if(is_array($arrdata)){
            ?>
            <select class="form-control select-ajax" name="post_category_id">
                <option>..Select Category..</option>
                <?php
                foreach ($arrdata as $key => $row) {
                    if(!empty($selected)){
                        if($row->post_category_id == $selected){
                            $active= 'selected';
                        }else {
                            $active= false;
                        }
                    }else {
                        $active= false;
                    }
                    ?> 
                    <option value="<?php echo $row->post_category_id;?>" <?php echo $active;?>><?php echo $row->post_category_name;?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

    function SubMenuPost()
    {
        $arrdata=$this->crud_global->ShowTable('tbl_post_category',false);
        ?>
        <ul class="submenu">
            <?php
            if(is_array($arrdata)){
                foreach ($arrdata as $key => $row) {
                    ?>
                    <li><a href="<?php echo site_url('admin/post/post_detail/'.$row->post_category_id.'');?>"><?php echo $row->post_category_name;?></a></li>
                    <?php
                }
            }
            ?>
        </ul>
        <?php
    }

    function AddNew()
    {
        $output=array('output'=>'false');

        $post_category_new = $this->input->post('post_category_new');
        $post_category_new_alias = strtolower(str_replace(" ", "_", $post_category_new));
        $element_input = $this->input->post('element_input_id[]');
        $post_comment = $this->input->post('post_comment');
        $post_tags = $this->input->post('post_tags');
        $order_by = $this->input->post('order_by');
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');
        $admin_id = $this->session->userdata('admin_id');
        $datecreated = date("Y-m-d H:i:s");

        $chek_menu = $this->crud_global->CheckNum('tbl_post_category_new',array('post_category_new'=>$post_category_new,'status !='=>0));
        $chek_order = $this->crud_global->CheckNum('tbl_post_category_new',array('order_id'=>$order_id,'status !='=>0));
        
        if($chek_menu == true){
            $output=array('output'=>'Post Category has been registered');
        }else 
        if($chek_order == true){
            $output=array('output'=>'Order has been registered');
        }else {
            $arrayvalues = array(
                 'post_category_new'=>$post_category_new,
                 'post_category_new_alias'=>$post_category_new_alias,
                 'post_comment'=>$post_comment,
                 'post_tags'=>$post_tags,
                 'order_by'=>$order_by,
                 'order_id'=>$order_id,
                 'status'=>$status,
                 'create_by'=>$admin_id,
                 'datecreated'=>$datecreated
            );

            $query=$this->db->insert('tbl_post_category_new',$arrayvalues);
            if($query){
                $id = $this->db->insert_id();
                foreach ($element_input as $key => $value) {
                    $arrayvalues_post_element = array(
                         'post_category_new_id'=>$id,
                         'element_input_id' => $value
                    );
                    $query_post_element=$this->db->insert('tbl_post_element',$arrayvalues_post_element);
                    if($query_post_element){
                        $output=array('output'=>'true');               
                    }
                }
            }else {
             $output=array('output'=>'false');
            }
        }

        echo json_encode($output);
    }

    function EditNew()
    {
        $output=array('output'=>'false');

        $id = $this->input->post('id');
        $post_category_new = $this->input->post('post_category_new');
        $post_category_new_alias = strtolower(str_replace(" ", "_", $post_category_new));
        $element_input = $this->input->post('element_input_id[]');
        $post_comment = $this->input->post('post_comment');
        $post_tags = $this->input->post('post_tags');
        $order_by = $this->input->post('order_by');
        $order_id = $this->input->post('order_id');
        $status = $this->input->post('status');
        $admin_id = $this->session->userdata('admin_id');
        $datecreated = date("Y-m-d H:i:s");

         // Get Data Old for Filter
        $menu_old = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_id'=>$id),'post_category_new');
        if($menu_old != $post_category_new){
         $chek_menu = $this->crud_global->CheckNum('tbl_post_category_new',array('post_category_new'=>$post_category_new,'status !='=>0));
        }else{
         $chek_menu = false;
        }

        $order_old = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_id'=>$id),'order_id');
        if($order_old != $order_id){
         $chek_order = $this->crud_global->CheckNum('tbl_post_category_new',array('order_id'=>$order_id,'status !='=>0));
        }else{
         $chek_order = false;
        }
        
        if($chek_menu == true){
            $output=array('output'=>'Post Category has been registered');
        }else 
        if($chek_order == true){
            $output=array('output'=>'Order has been registered');
        }else {
            $arraywhere = array('post_category_new_id'=>$id);
            $arrayvalues = array(
                 'post_category_new'=>$post_category_new,
                 'post_category_new_alias'=>$post_category_new_alias,
                 'post_comment'=>$post_comment,
                 'post_tags'=>$post_tags,
                 'order_by'=>$order_by,
                 'order_id'=>$order_id,
                 'status'=>$status,
                 'update_by'=>$admin_id,
                 'dateupdate'=>$datecreated
                 );
            $query=$this->crud_global->UpdateDefault('tbl_post_category_new',$arrayvalues,$arraywhere);
            if($query){

                $this->db->delete('tbl_post_element',array('post_category_new_id'=>$id));

                foreach ($element_input as $key => $row) {
                    $arrayvalues_post_element = array(
                         'post_category_new_id'=>$id,
                         'element_input_id' => $row
                    );
                    $query_post_element=$this->db->insert('tbl_post_element',$arrayvalues_post_element);
                    if($query_post_element){
                        $output=array('output'=>'true');               
                    }
                }

            }else {
             $output=array('output'=>'false');
            }
        }

        echo json_encode($output);
    }

    function SelectElementPost($id=false)
    {
        $arr_data = $this->crud_global->ShowTableNoOrder('tbl_element_input',false);
        ?>
        <div class="row">
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {

                    $active=false;
                    $checked=false;
                    $data_value = $row->element_input_id;
                    if(!empty($id)){
                        $arr_pages_element = $this->crud_global->ShowTableDefault('tbl_post_element',array('post_category_new_id'=>$id));
                        if(is_array($arr_pages_element)){
                            foreach ($arr_pages_element as $key_2 => $row_2) {
                                if($row_2->element_input_id == $row->element_input_id){
                                    $active='active';
                                    $checked='checked';
                                    $data_value = $row->element_input_id;
                                }
                            }
                        }
                    }else{
                        $data_value = $row->element_input_id;
                    }
                    ?>
                    <div class="col-sm-6">
                        <div data-toggle="buttons" style="margin-bottom:15px;">
                          <label class="inner-btn-access btn btn-default btn-block <?php echo $active;?>" style="text-align:left;">
                            <input class="btn-check-menu" data-actions="menu-<?php echo $row->element_input_id;?>" type="checkbox" autocomplete="off" name="element_input_id[]" value="<?php echo $data_value;?>" <?php echo $checked;?>> <?php echo $row->element_input;?>
                            <span class="icon-access-menu"><i class="fa fa-check"></i></span>
                          </label>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
}