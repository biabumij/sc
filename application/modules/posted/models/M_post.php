<?php
class M_post extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('general');
        $this->load->model('crud_global');
        $this->load->helper('directory');
    }

    function AddPostCategory()
    {
        $output = array('output'=>'false');

        $post_category = $this->input->post('post_category');
        $post_category_alias = strtolower(str_replace(" ", "_", $post_category));
        $post_category_description = $this->input->post('post_category_description');
        $order_by = $this->input->post('order_by');
        $post_comment = $this->input->post('post_comment');
        $post_limit = $this->input->post('post_limit');
        $element_input_id = $this->input->post('element_input_id[]');
        $order_id = $this->input->post('order_id');

        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");

        $pages_check = $this->crud_global->CheckNum('tbl_post_category',array('post_category'=>$post_category,'status'=>1));
        if($pages_check == true){
            $output=array('output'=>'Name has been registered');
        }else
        if(empty($element_input_id)){
            $output=array('output'=>'Please fill Element Input');
        }else {
            $arrayvalues = array(
                'post_category'=>$post_category,
                'post_category_alias'=>$post_category_alias,
                'post_category_description'=>$post_category_description,
                'order_by'=>$order_by,
                'order_id'=>$order_id,
                'post_comment'=>$post_comment,
                'post_limit'=>$post_limit,
                'status'=>$status,
                'created_by'=>$admin_id,
                'datecreated'=>$datecreated 
                );

            $query=$this->db->insert('tbl_post_category',$arrayvalues);
            if($query){
                $post_category_id = $this->db->insert_id();

                if(is_array($element_input_id)){
                    foreach ($element_input_id as $key => $value) {

                        $arrayvalues_element = array(
                            'post_category_id'=>$post_category_id,
                            'element_input_id'=>$value,
                        );
                        $this->db->insert('tbl_post_element',$arrayvalues_element);
                    }
                }

                $output=array('output'=>'true');

            }else {
                $output=array('output'=>'false');
            }
        }
        echo json_encode($output);
    }

    function EditPostCategory()
    {
        $output = array('output'=>'false');


        $id = $this->input->post('id');
        $post_category = $this->input->post('post_category');
        $post_category_alias = strtolower(str_replace(" ", "_", $post_category));
        $post_category_description = $this->input->post('post_category_description');
        $order_by = $this->input->post('order_by');
        $post_comment = $this->input->post('post_comment');
        $post_limit = $this->input->post('post_limit');
        $element_input_id = $this->input->post('element_input_id[]');
        $order_id = $this->input->post('order_id');
        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");

        $pages_old = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$id),'post_category');
        if($pages_old != $post_category){
            $pages_check = $this->crud_global->CheckNum('tbl_post_category',array('post_category'=>$post_category,'status'=>1));
        }else{
            $pages_check = false;
        }
    
        if($pages_check == true){
            $output=array('output'=>'Name has been registered');
        }else
        if(empty($element_input_id)){
            $output=array('output'=>'Please fill Element Input');
        }else {
            $arrayvalues = array(
                'post_category'=>$post_category,
                'post_category_alias'=>$post_category_alias,
                'post_category_description'=>$post_category_description,
                'order_by'=>$order_by,
                'order_id'=>$order_id,
                'post_comment'=>$post_comment,
                'post_limit'=>$post_limit,
                'status'=>$status,
                'updated_by'=>$admin_id,
                'dateupdated'=>$datecreated 
                );

            $query=$this->crud_global->UpdateDefault('tbl_post_category',$arrayvalues,array('post_category_id'=>$id));
            if($query){
                $output=array('output'=>'true');

            }else {
                $output=array('output'=>'false');
            }
        }
        echo json_encode($output);
    }


    function AddPost()
    {
        $output=array('output'=>'false');

        $post_category_id = $this->input->post('post_category_id');
        $post = $this->input->post('post');
        $post_alias = strtolower(str_replace(" ", "-", $post));
        $user_type = $this->input->post('user_type');
        $parent_id = $this->input->post('parent_id');
        $order_id = $this->input->post('order_id');

        $status = $this->input->post('status');
        $admin_id = $this->session->userdata('admin_id');
        $member_id = $this->session->userdata('member_id');
        if(!empty($member_id)){
            $admin_id = $member_id;
        }
        $datecreated = date("Y-m-d H:i:s");

        // if(!empty($order_id)){
        //     $chek_order = $this->crud_global->CheckNum('tbl_post',array('order_id'=>$order_id,'status !='=>0,'post_category_id'=>$post_category_id));
        // }else {
            $chek_order = false;
        // }
        
        if($chek_order == true){
            $output=array('output'=>'Order has been registered');
        }else {

            $arrayvalues = array(
                 'post_category_id'=>$post_category_id,
                 'post'=>$post,
                 'post_alias'=>$post_alias,
                 'parent_id'=>$parent_id,
                 'user_type'=>$user_type,
                 'order_id'=>$order_id,
                 'status'=>$status,
                 'created_by'=>$admin_id,
                 'datecreated'=>$datecreated
            );

            $query=$this->db->insert('tbl_post',$arrayvalues);
            if($query){
                $id = $this->db->insert_id();

                $arr_post_element = $this->crud_global->ShowTableDefault('tbl_post_element',array('post_category_id'=>$post_category_id));

                if(is_array($arr_post_element)){
                    foreach ($arr_post_element as $key => $row) {
                        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
                        foreach ($arr_lang as $key_lang => $row_lang) {
                            $arrayvalues_post_data = array(
                                    'post_id' => $id,
                                    'post_element_id' => $row->post_element_id,
                                    'language_id' => $row_lang->language_id,
                                    'created_by'=> $admin_id,
                                    'datecreated'=>$datecreated
                                );
                            $query_post_data = $this->db->insert('tbl_post_data',$arrayvalues_post_data);
                            if($query_post_data){
                                $output=array('output'=>'true');
                            }
                        }
                    }
                }
            }else {
             $output=array('output'=>'false');
            }
        }

        echo json_encode($output);
    }

    function EditPost()
    {
        $output=array('output'=>'false');

        $id = $this->input->post('id');
        $post_category_id = $this->input->post('post_category_id');
        $post = $this->input->post('post');
        $post_alias = strtolower(str_replace(" ", "-", $post));
        $user_type = $this->input->post('user_type');
        $parent_id = $this->input->post('parent_id');
        $order_id = $this->input->post('order_id');

        $status = $this->input->post('status');
        $admin_id = $this->session->userdata('admin_id');
        $member_id = $this->session->userdata('member_id');
        if(!empty($member_id)){
            $admin_id = $member_id;
        }
        $datecreated = date("Y-m-d H:i:s");

        // if(!empty($order_id)){
            // $chek_order = $this->crud_global->CheckNum('tbl_post',array('order_id'=>$order_id,'status !='=>0,'post_category_id'=>$post_category_id));
        // }else {
            $chek_order = false;
        // }
        
        if($chek_order == true){
            $output=array('output'=>'Order has been registered');
        }else {

            $arrayvalues = array(
                 'post_category_id'=>$post_category_id,
                 'post'=>$post,
                 'post_alias'=>$post_alias,
                 'parent_id'=>$parent_id,
                 // 'user_type'=>$user_type,
                 'order_id'=>$order_id,
                 'status'=>$status,
                 'updated_by'=>$admin_id,
                 'dateupdated'=>$datecreated
            );

            $query=$this->crud_global->UpdateDefault('tbl_post',$arrayvalues,array('post_id'=>$id));
            if($query){

                $output=array('output'=>'true');   
            }else {
                $output=array('output'=>'false');
            }
        }

        echo json_encode($output);
    }

    function SavePostData()
    {
        $output = array('output'=>'false');

        $post_id = $this->input->post('post_id');
        $post_category_id = $this->input->post('post_category_id');
        $admin_id = $this->session->userdata('admin_id');
        $member_id = $this->session->userdata('member_id');
        if(!empty($member_id)){
            $admin_id = $member_id;
        }
        $datecreated = date("Y-m-d H:i:s");

        $arr_pages_el = $this->crud_global->ShowTableDefault('tbl_post_element',array('post_category_id'=>$post_category_id));

        foreach ($arr_pages_el as $key_el => $row_el) {
            $arr_lang = $this->crud_global->ShowTable('tbl_language',false);

            foreach ($arr_lang as $key_lang => $row_lang) {

                $pages_data = $this->crud_global->GetField('tbl_post_data',array('post_id'=>$post_id,'language_id'=>$row_lang->language_id,'post_element_id'=>$row_el->post_element_id),'post_data_id');

                $data_edit = $this->input->post($pages_data);

                $arraywhere = array('post_id'=>$post_id,'language_id'=>$row_lang->language_id,'post_element_id'=>$row_el->post_element_id);

                $arrayvalues = array(
                    'content'=>$data_edit,
                    'updated_by'=>$admin_id,
                    'dateupdated'=>$datecreated 
                );

                $query=$this->crud_global->UpdateDefault('tbl_post_data',$arrayvalues,$arraywhere);

                if($query){
                    $output = array('output'=>'true');
                }else {
                    $output = array('output'=>'false');
                }
            }
        }

        echo json_encode($output);
    }


    function SelectElementInput($id=false)
    {
        $arr_data = $this->crud_global->ShowTableNoOrder('tbl_element_input',array('status'=>1));
        if(is_array($arr_data)){
            foreach ($arr_data as $key => $row) {
                $checked = false;
                if(!empty($id)){
                    $arr_check = $this->crud_global->ShowTableNew('tbl_post_element',array('post_category_id'=>$id));
                    if(is_array($arr_check)){
                        foreach ($arr_check as $key_check => $row_check) {
                            if($row->element_input_id == $row_check->element_input_id){
                                $checked = 'checked=""';
                            }
                        }
                    }
                }
                ?>
                    <div class="checkbox-custom checkbox-primary">
                        <input type="checkbox" id="<?php echo $row->element_input_alias;?>" value="<?php echo $row->element_input_id;?>" name="element_input_id[]" <?php echo $checked;?>>
                        <label class="check" for="<?php echo $row->element_input_alias;?>"><?php echo $row->element_input;?></label>
                    </div>
                <?php
            }
        }
    }


    function SelectParent($post_category_id,$id=false)
    {
        ?>
        <select class="form-control select2" name="parent_id" title="..Choose Parent..." style="width:100%;">
            <?php
            if($id == 0){
                $active = 'selected';
            }else {
                $active = false;
            }
            ?>
            <option value="0" <?php echo $active;?>>Parent</option>
            <?php
            $arr = $this->crud_global->ShowTableNew('tbl_post',array('status'=>1,'post_category_id'=>$post_category_id,'parent_id'=>0));
            if(is_array($arr)){
                foreach ($arr as $key => $row) {
                    $active_child = false;
                    if(!empty($id)){
                        if($id != 0){
                            if($row->post_id == $id){
                                $active_child = 'selected';    
                            }
                        }
                    }
                    ?>
                    <option value="<?php echo $row->post_id;?>" <?php echo $active_child;?>><?php echo $row->post;?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }


    function GetParent($id)
    {
        $output=false;

        if($id == 0){
            $output = 'Parent';
        }else {
            if($id != 0){
                $arr = $this->crud_global->ShowTableNew('tbl_post',array('status'=>1,'post_id'=>$id));
                if(is_array($arr)){
                    foreach ($arr as $key => $row) {
                        if($id == $row->post_id){
                             $output = $row->post;
                        }
                    }
                }
            }
        }

        return $output;
    }
   
    function ShowPost($post_category,$parent_id=false,$limit=false,$start=false,$arraywhere=false,$random=false,$search=false)
    {
        $output= false;

        $this->db->select("
            tbl_post_category.post_category_id,
            tbl_post_category.post_category_alias,
            tbl_post_category.status,
            tbl_post_category.order_by
            ");
        $this->db->where("tbl_post_category.post_category_alias",$post_category);
        $this->db->where("tbl_post_category.status",1);
        $this->db->limit(1);
        $query = $this->db->get('tbl_post_category');
        if($query->num_rows() > 0){
            $row = $query->row();
            $post_cat_id = $row->post_category_id;
            $order_by = $row->order_by;

            $this->db->select("
                    tbl_post.post_id,
                    tbl_post.post_alias,
                    tbl_post.post_category_id,
                    tbl_post.status,
                    tbl_post.datecreated,
                    tbl_post.parent_id,
                    tbl_post.created_by,
                ");
            $this->db->where("tbl_post.post_category_id",$post_cat_id);
            $this->db->where("tbl_post.status",1);
            if(!empty($parent_id)){
                $this->db->where("tbl_post.parent_id",$parent_id);
            }
            if(!empty($arraywhere)){
                if(is_array($arraywhere)){
                    foreach ($arraywhere as $key => $value) {
                        $this->db->where($key,$value);
                    }
                }
            }
            if($random == true){
                $this->db->order_by('tbl_post.post_id', 'RANDOM');
            }
            if($order_by == 1){
                $this->db->order_by("tbl_post.order_id","asc");
                // if(!empty($start)){
                //     $this->db->where('tbl_post.post_id >',$start);
                // }
            }else {
                $this->db->order_by("tbl_post.dateupdated","desc");
                

                // if(!empty($start)){
                //     $this->db->where('tbl_post.post_id <',$start);
                // }
            }

            if(!empty($start)){
                if(is_array($start)){
                    foreach ($start as $key => $value) {
                        // $this->db->where($key,$value);
                        $this->db->limit($key, $value);
                    }
                }
            }
            
            if(!empty($limit)){
                $this->db->limit($limit);
            }

            if(!empty($search)){
                $this->db->join('tbl_post_data','tbl_post_data.post_id = tbl_post.post_id','left');
                $this->db->like('tbl_post_data.content',$search);
            }

            $query_post = $this->db->get('tbl_post');
            if($query_post->num_rows() > 0){
                $output = $query_post->result();
            }else {
                $output=false;
            }

        }
        return $output;
    }

    function ShowDataPost($lang_id,$post_id,$element)
    {
        $output=false;
        $this->db->select("
                tbl_post_element.post_element_id,
                tbl_post_element.element_input_id,
                tbl_post_data.content,
                tbl_post_data.language_id,
                tbl_post_data.post_id,
                tbl_element_input.element_input_alias
            ");
        $this->db->join('tbl_post_data','tbl_post_data.post_element_id = tbl_post_element.post_element_id','left'); 
        $this->db->join('tbl_element_input','tbl_element_input.element_input_id = tbl_post_element.element_input_id','left'); 
        $this->db->where('tbl_post_data.language_id',$lang_id);
        $this->db->where('tbl_post_data.post_id',$post_id);
        $this->db->where('tbl_element_input.element_input_alias',$element);
        $query = $this->db->get('tbl_post_element');
        if($query->num_rows() > 0){

            $row = $query->row();
            $output=$row->content;

        }else {
            $output=false;
        }

        return $output;   
    }


    function ShowPostCategory($category,$lang_id)
    {
        $post_category_id = $this->crud_global->GetField('tbl_post_category_new',array('post_category_new_alias'=>$category),'post_category_new_id');
        $arr_cat = $this->m_post->ShowPostNew($category,false,false,false,array('parent_id'=> 0,'post_category_new_id'=>$post_category_id));
        if(is_array($arr_cat)){
            $count_all = count($this->m_post->ShowPostNew($category,false,false,false,array('parent_id !='=> 0)));
            ?>
            <ul>
                <li><a href="<?php echo site_url('en/pages/'.$category);?>">All</a> (<?php echo $count_all;?>)</li>
                <?php
                foreach ($arr_cat as $key => $row) {
                    $count_cat = count($this->m_post->ShowPostNew($category,false,false,false,array('parent_id'=> $row->parent_id)));
                    $title = $this->m_post->ShowDataPost($lang_id,$row->post_new_id,'title');
                    ?>
                    <li><a href="<?php echo site_url('en/pages/'.$category.'/'.$row->post_new_id);?>"><?php echo $title;?></a> (<?php echo $count_cat;?>)</li>
                    <?php
                }
                ?>
            </ul>
            <?php    
        }
    }

    function GetAuthor($post_id)
    {
        $output = false;

        $author_id = $this->crud_global->GetField('tbl_post',array('status'=>1,'post_id'=>$post_id),'created_by');
        $user_type = $this->crud_global->GetField('tbl_post',array('status'=>1,'post_id'=>$post_id),'user_type');

        if($user_type == 1){
            $author = $this->crud_global->GetField('tbl_admin',array('status'=>1,'admin_id'=>$author_id),'admin_name');
            $output = $author.' (Admin)';
        }else {
            $author = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$author_id),'name');
            $output = $author.' (Anggota)';
        }

        return $output;

    }

}