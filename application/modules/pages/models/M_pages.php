<?php
class M_pages extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('general');
        $this->load->model('crud_global');
        $this->load->helper('directory');
    }


    function AddProcess()
    {
        $output = array('output'=>'false');

        $pages = $this->input->post('pages');
        $pages_alias = strtolower(str_replace(" ", "_", $pages));
        $pages_url = 'page/'.$pages_alias;
        $parent_id = $this->input->post('parent_id');
        $pages_template = $this->input->post('pages_template');
        $element_input_id = $this->input->post('element_input_id[]');
        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");

        $pages_check = $this->crud_global->CheckNum('tbl_pages',array('pages'=>$pages,'status'=>1));
        if($pages_check == true){
            $output=array('output'=>'Pages Name has been registered');
        }else
        if(empty($element_input_id)){
            $output=array('output'=>'Please fill Element Input and Position Pages');
        }else {
            $arrayvalues = array(
                'pages'=>$pages,
                'parent_id'=>$parent_id,
                'pages_alias'=>$pages_alias,
                'pages_template'=>$pages_template,
                'pages_url'=>$pages_url,
                'status'=>$status,
                'create_by'=>$admin_id,
                'datecreated'=>$datecreated 
                );

            $query=$this->db->insert('tbl_pages',$arrayvalues);
            if($query){
                $pages_id = $this->db->insert_id();

                if(is_array($element_input_id)){
                    foreach ($element_input_id as $key => $value) {
                        $arrayvalues_element = array(
                            'pages_id'=>$pages_id,
                            'element_input_id'=>$value,
                        );
                        $this->db->insert('tbl_pages_element',$arrayvalues_element);

                        $pages_element_id = $this->db->insert_id();
                        
                        $arr_lang = $this->crud_global->ShowTable('tbl_language',array('status'=>1));
                        foreach ($arr_lang as $key_lang => $val_lang) {
                            $arrayvalues_pages_data = array(
                                'pages_element_id'=>$pages_element_id,
                                'language_id'=>$val_lang->language_id,
                                'create_by'=>$admin_id,
                                'datecreated'=>$datecreated 
                            );
                            $this->db->insert('tbl_pages_data',$arrayvalues_pages_data);
                        }

                    }
                }

                $output=array('output'=>'true');

            }else {
                $output=array('output'=>'false');
            }
        }
        echo json_encode($output);
    }

    function EditProcess()
    {
        $output=array('output'=>'false');
        // Get data
        $id=$this->input->post('id');
        $pages = $this->input->post('pages');
        $pages_alias = strtolower(str_replace(" ", "_", $pages));
        $pages_url = 'page/'.$pages_alias;
        $parent_id = $this->input->post('parent_id');
        $pages_template = $this->input->post('pages_template');
        $element_input_id = $this->input->post('element_input_id[]');
        $pages_position_category_id = $this->input->post('pages_position_category_id[]');
        $admin_id = $this->session->userdata('admin_id');
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");

        $pages_element_value = $this->input->post('pages_element_value[]');

        // Update JSON
        // Get Data Old for Filter
        $pages_old = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$id),'pages');
        if($pages_old != $pages){
         $check_pages = $this->crud_global->CheckNum('tbl_pages',array('pages'=>$pages,'status'=>1));
        }else{
         $check_pages = false;
        }
        
        if($check_pages == true){
         $output=array('output'=>'Pages Name has been registered');
        }else {
            $arraywhere = array('pages_id'=>$id);
            $arrayvalues = array(
                'pages'=>$pages,
                'parent_id'=>$parent_id,
                'pages_alias'=>$pages_alias,
                'pages_template'=>$pages_template,
                'pages_url'=>$pages_url,
                'status'=>$status,
                'update_by'=>$admin_id,
                'dateupdate'=>$datecreated 
            );
            $query=$this->crud_global->UpdateDefault('tbl_pages',$arrayvalues,$arraywhere);

            if($query){
                // $this->db->delete('tbl_pages_element',array('pages_id'=> $id));

                $output=array('output'=>'true');
            }else {
                $output=array('output'=>'false');
            }
        }
        echo json_encode($output);
    }

    function SavePagesData()
    {
        $output = array('output'=>'false');

        $pages_id = $this->input->post('pages_id');
        $admin_id = $this->session->userdata('admin_id');
        $datecreated = date("Y-m-d H:i:s");

        $arr_pages_el = $this->crud_global->ShowTableDefault('tbl_pages_element',array('pages_id'=>$pages_id));

        foreach ($arr_pages_el as $key_el => $row_el) {
            $arr_lang = $this->crud_global->ShowTable('tbl_language',false);

            foreach ($arr_lang as $key_lang => $row_lang) {
                $pages_data = $this->crud_global->GetField('tbl_pages_data',array('pages_element_id'=>$row_el->pages_element_id,'language_id'=>$row_lang->language_id),'pages_data_id');

                $data_edit = $this->input->post($pages_data);

                $arraywhere = array('pages_element_id'=>$row_el->pages_element_id,'language_id'=>$row_lang->language_id);
                $arrayvalues = array(
                    'content'=>$data_edit,
                    'update_by'=>$admin_id,
                    'dateupdate'=>$datecreated 
                );

                $query=$this->crud_global->UpdateDefault('tbl_pages_data',$arrayvalues,$arraywhere);

                if($query){
                    $output = array('output'=>'true');
                }else {
                    $output = array('output'=>'false');
                }
            }
        }

        echo json_encode($output);
    }

    function ScriptEditor()
    {
        $arr=$this->crud_global->ShowTable('tbl_language',false);
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                <?php       
                foreach ($arr as $key => $val) {
                  ?>
                    tinymce.init({
                      selector: 'textarea#content<?php echo $val->language_id;?>',
                      height: 250,
                      theme: 'modern',
                      plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
   ],
                      toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                      toolbar2: 'print preview media | forecolor backcolor emoticons | link unlink anchor | image media | forecolor backcolor  | print preview code | responsivefilemanager',
                      image_advtab: true,
                      content_css: [
                        '//www.tinymce.com/css/codepen.min.css'
                      ],
                      external_filemanager_path:"<?php echo base_url();?>filemanager/",
                       filemanager_title:"Responsive Filemanager" ,
                       external_plugins: { "filemanager" : "<?php echo base_url();?>assets/back/theme/vendor/tinymce/plugins/responsivefilemanager/plugin.min.js"}
                     });
                 <?php  
                }
                ?>
            });
        </script>
        <?php
    }

    function SelectParentPages($id=false)
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
            $arr = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1));
            if(is_array($arr)){
                foreach ($arr as $key => $row) {
                    $active_child = false;
                    if(!empty($id)){
                        if($id != 0){
                            if($row->pages_id == $id){
                                $active_child = 'selected';    
                            }
                        }
                    }
                    ?>
                    <option value="<?php echo $row->pages_id;?>" <?php echo $active_child;?>><?php echo $row->pages;?></option>
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
                $arr = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1));
                if(is_array($arr)){
                    foreach ($arr as $key => $row) {
                        if($id == $row->pages_id){
                             $output = $row->pages;
                        }
                    }
                }
            }
        }

        return $output;
    }


    function SelectPages($id=false)
    {
        // $map = directory_map('./application/views/front/templates/', FALSE, TRUE);
        $arr = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1));
        ?>
        <select class="form-control select2" name="pages_template" title="..Choose Pages Template..." data-live-search="true" style="width:100%;">
            <?php
            if(is_array($arr)){
                foreach ($arr as $key => $value) {
                    // $value_new = str_replace(".php","", $value);
                    // $value_name = ucwords(str_replace("_", " ", $value_new));
                    $active = false;
                    if(!empty($id)){
                        // if($id == $value_new){
                        //     $active = 'selected';
                        // }else {
                        //     $active = false;
                        // }
                    }
                    ?>
                    <option value="" <?php echo $active;?>><?php echo $value->pages_url;?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }

    function SelectPagesTemplate($id=false)
    {
        $map = directory_map('./application/views/front/templates/', FALSE, TRUE);
        ?>
        <select class="form-control select2" name="pages_template" title="..Choose Pages Template..." data-live-search="true" style="width:100%;">
            <?php
            if(is_array($map)){
                foreach ($map as $key => $value) {
                    $value_new = str_replace(".php","", $value);
                    $value_name = ucwords(str_replace("_", " ", $value_new));

                    if(!empty($id)){
                        if($id == $value_new){
                            $active = 'selected';
                        }else {
                            $active = false;
                        }
                    }else {
                        $active = false;
                    }
                    ?>
                    <option value="<?php echo $value_new;?>" <?php echo $active;?>><?php echo $value_name;?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }


    function SelectElementInput($id=false)
    {
        $arr_data = $this->crud_global->ShowTableNoOrder('tbl_element_input',array('status'=>1));
        if(is_array($arr_data)){
            foreach ($arr_data as $key => $row) {
                $checked = false;
                if(!empty($id)){
                    $arr_check = $this->crud_global->ShowTableNew('tbl_pages_element',array('pages_id'=>$id));
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

    

    function SelectPositionPages($id=false)
    {
        $arr_data = $this->crud_global->ShowTableNoOrder('tbl_pages_position_category',false);
        ?>
        <div class="row">
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    $active=false;
                    $checked=false;
                    if(!empty($id)){
                        $arr_pages_element = $this->crud_global->ShowTableDefault('tbl_pages_position',array('pages_id'=>$id));
                        if(is_array($arr_pages_element)){
                            foreach ($arr_pages_element as $key_2 => $row_2) {
                                if($row_2->pages_position_category_id == $row->pages_position_category_id){
                                    $active='active';
                                    $checked='checked';
                                }
                            }
                        }
                    }
                    ?>
                    <div class="col-sm-6">
                        <div data-toggle="buttons" style="margin-bottom:15px;">
                          <label class="inner-btn-access btn btn-default btn-block <?php echo $active;?>" style="text-align:left;">
                            <input class="btn-check-menu" data-actions="menu-<?php echo $row->pages_position_category_id;?>" type="checkbox" autocomplete="off" name="pages_position_category_id[]" value="<?php echo $row->pages_position_category_id;?>" <?php echo $checked;?>> <?php echo $row->pages_position_category;?>
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

    function GetPositionPages($id)
    {
        $arr_data = $this->crud_global->ShowTableDefault('tbl_pages_position',array('pages_id'=>$id));
        $output = false;
        if(is_array($arr_data)){
            foreach ($arr_data as $key => $row){
                $pages_position = $this->crud_global->GetField('tbl_pages_position_category',array('pages_position_category_id'=>$row->pages_position_category_id),'pages_position_category');
                $output = $pages_position.'<br />';
            }
        }

        return $output;
    }

    function ShowPages($position)
    {
        $output=false;

        $pages_position_category_id = $this->crud_global->GetField('tbl_pages_position_category',array('pages_position_category_alias'=>$position),'pages_position_category_id');
        $this->db->select("
                tbl_pages_position.pages_position_id,
                tbl_pages_position.pages_position_category_id,
                tbl_pages_position.pages_type,
                tbl_pages_position.pages_url,
                tbl_pages_position.sort_id,
                tbl_pages.status
            ");
        $this->db->join('tbl_pages','tbl_pages.pages_id = tbl_pages_position.pages_url','left');
        $this->db->where('pages_position_category_id',$pages_position_category_id);
        $this->db->where('tbl_pages.status =',1);
        $this->db->order_by('sort_id','asc');
        $query = $this->db->get('tbl_pages_position');
        if($query->num_rows() > 0){
            $output=$query->result();
        }else {
            $output=false;
        }
        return $output;
    }

    function ShowPagesDetail($pages,$language_id,$element=false)
    {
        $output=false;
        $this->db->select("
                tbl_pages_element.pages_element_id,
                tbl_pages_element.pages_id,
                tbl_pages_element.element_input_id,
                tbl_pages_data.content,
                tbl_pages_data.language_id,
                tbl_element_input.element_input_alias,
                tbl_pages.pages_id AS ID,
            ");
        $this->db->join('tbl_pages_data','tbl_pages_data.pages_element_id = tbl_pages_element.pages_element_id','left'); 
        $this->db->join('tbl_element_input','tbl_element_input.element_input_id = tbl_pages_element.element_input_id','left'); 
        $this->db->join('tbl_pages','tbl_pages.pages_id = tbl_pages_element.pages_id','left'); 
        $this->db->where('tbl_pages_element.pages_id',$pages);
        $this->db->where('tbl_pages_data.language_id',$language_id);
        $this->db->where('tbl_pages.status',1);
        if(!empty($element)){
            $this->db->where('tbl_element_input.element_input_alias',$element);
        }
        $query = $this->db->get('tbl_pages_element');
        if($query->num_rows() > 0){
            // $output=$query->result();
            $row = $query->row();
            // $row=$query->row();
            $output=$row->content;
        }else {
            $output=false;
        }

        return $output;
    }


    function ShowPagesDetailManual($pages,$language_id,$element=false)
    {
        $output=false;
        $this->db->select("
                tbl_pages_element.pages_element_id,
                tbl_pages_element.pages_id,
                tbl_pages_element.element_input_id,
                tbl_pages_data.content,
                tbl_pages_data.language_id,
                tbl_element_input.element_input_alias,
                tbl_pages.status
            ");
        $this->db->join('tbl_pages_data','tbl_pages_data.pages_element_id = tbl_pages_element.pages_element_id','left'); 
        $this->db->join('tbl_element_input','tbl_element_input.element_input_id = tbl_pages_element.element_input_id','left'); 
        $this->db->join('tbl_pages','tbl_pages.pages_id = tbl_pages_element.pages_id','left');
        $this->db->where('tbl_pages.pages_alias',$pages);
        $this->db->where('tbl_pages.status',1);
        $this->db->where('tbl_pages_data.language_id',$language_id);
        if(!empty($element)){
            $this->db->where('tbl_element_input.element_input_alias',$element);
        }
        $query = $this->db->get('tbl_pages_element');
        if($query->num_rows() > 0){
            // $output=$query->result();
            $row = $query->row();
            // $row=$query->row();
            $output=$row->content;
        }else {
            $output=false;
        }

        return $output;
    }

}