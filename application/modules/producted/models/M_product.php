<?php
class M_product extends CI_Model {

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

        $this->db->join('tbl_product_data','tbl_product_data.product_id = tbl_product.product_id','left');

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


    function AddProduct()
    {
        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
        $output=array('output'=>'false');

        // Get data
        $product_code = $this->input->post('product_code');
        $product_barcode = $this->input->post('product_barcode');
        $product_available = $this->input->post('product_available');
        $manufactures_id = $this->input->post('manufactures_id');
        $unit_type_id = $this->input->post('unit_type_id');
        $user_type =  $this->input->post('user_type');
        $product_category_id = $this->input->post('product_category_id[]');
        $product_special_category_id = $this->input->post('product_special_category_id[]');

        $related_products = $this->input->post('related_products[]');
        if(is_array($related_products)){
            $related_products = implode(",", $related_products);
        }

        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity');
        $sort_id = $this->input->post('sort_id');
        $admin_id = $this->session->userdata('admin_id');
        $member_id = $this->session->userdata('member_id');
        if(!empty($member_id)){
            $admin_id = $member_id;
        }
        $status = $this->input->post('status');
        $datecreated = date("Y-m-d H:i:s");


        $asal = $this->input->post('asal');

        $arrayvalues = array(
            'product_code'=>$product_code,
            'product_barcode'=>$product_barcode,
            'product_available'=>$product_available,
            'price'=>$price,
            'unit_type_id'=>$unit_type_id,
            'quantity'=>$quantity,
            'user_type'=>$user_type,
            'manufactures_id'=>$manufactures_id,
            'related_products'=>$related_products,
            'sort_id'=>$sort_id,
            'status'=>$status,
            'created_by'=>$admin_id,
            'datecreated'=>$datecreated,
            'asal'=>$asal
        );

        $check_product_code = $this->crud_global->CheckNum('tbl_product',array('product_code'=>$product_code,'status !='=>0));
        if(empty($product_code)){
            $output=array('output'=>'Product Code Cannot be Empty');
        }else if($check_product_code == true){
            $output=array('output'=>'Product Code has been registered');
        }else {
            $query=$this->db->insert('tbl_product',$arrayvalues);
            if($query){
                $id = $this->db->insert_id();

                $output_val = false;
                if(is_array($product_category_id)){
                    foreach ($product_category_id as $key_cat => $row_cat) {
                        $arrayvalues_cat = array(
                            'product_id' => $id,
                            'product_category_id' => $row_cat
                        );
                        $query_cat = $this->db->insert('tbl_procat',$arrayvalues_cat);

                        $parent_id = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$row_cat),'parent_id');
                        if($parent_id != 0){

                            $arrayvalues_cat_parent = array(
                                'product_id' => $id,
                                'product_category_id' => $parent_id
                            );
                            $query_cat_parent = $this->db->insert('tbl_procat',$arrayvalues_cat_parent);

                            $parent_id_2 = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$parent_id),'parent_id');
                            if($parent_id_2 != 0){  

                                $arrayvalues_cat_parent_2 = array(
                                    'product_id' => $id,
                                    'product_category_id' => $parent_id_2
                                );
                                $query_cat_parent_2 = $this->db->insert('tbl_procat',$arrayvalues_cat_parent_2);
                            }
                        }

                        if($query_cat){
                            $output_val = true;
                        }else {
                            $output_val = false;
                        }
                    }
                }

                if(is_array($product_special_category_id)){
                    foreach ($product_special_category_id as $key_spec => $row_spec) {
                        $arrayvalues_spec = array(
                            'product_id' => $id,
                            'product_special_category_id' => $row_spec
                        );
                        $query_spec = $this->db->insert('tbl_product_special',$arrayvalues_spec);
                        if($query_spec){
                            $output_val = true;
                        }else {
                            $output_val = false;
                        }
                    }
                }

                foreach ($arr_lang as $key => $value) {
                    $arrayvalues_2 = array(
                        'product_id' => $id,
                        'product_name' => $this->input->post('product_name_'.$value->language_id),
                        'product_subname' => $this->input->post('product_subname_'.$value->language_id),
                        'product_short_des' => $this->input->post('product_short_des_'.$value->language_id),
                        'product_des' => $this->input->post('product_des_'.$value->language_id),
                        'product_meta_des' => $this->input->post('product_meta_des_'.$value->language_id),
                        'product_keywords' => $this->input->post('product_keywords_'.$value->language_id),
                        'product_tags' => $this->input->post('product_tags_'.$value->language_id),
                        'language_id' => $value->language_id
                    );
                    $query_2 = $this->db->insert('tbl_product_data',$arrayvalues_2);

                    if($query_2){
                        $output_val = true;
                    }else {
                        $output_val = false;
                    }
                }

                // Insert Spec
                $val_attr = $this->input->post('val_attr');
                if($val_attr > 0){
                    for ($i=1; $i<=$val_attr ; $i++) { 
                        $arrayvalues_3 = array(
                            'product_id' => $id,
                            'product_spec' => $this->input->post('attribute_name'.$i),
                            'product_spec_desc' => $this->filter->FilterTextarea($this->input->post('attribute_des'.$i)),
                            'sort_id' => $this->input->post('attribute_sort'.$i),
                        );
                        if($this->input->post('attribute_name'.$i) !== null && $this->input->post('attribute_des'.$i) !== null){
                            $query_3 = $this->db->insert('tbl_product_spec',$arrayvalues_3);
                        }
                        $output_val = true;
                    }
                }

                // Insert Gallery
                $val_gallery = $this->input->post('val_gallery');
                if($val_gallery > 0){
                    for ($i=1; $i<=$val_gallery ; $i++) { 
                        $arrayvalues_4 = array(
                            'product_id' => $id,
                            'product_image' => $this->input->post('gallery_image'.$i),
                            'product_caption' => $this->input->post('gallery_des'.$i),
                            'sort_id' => $this->input->post('gallery_sort'.$i),
                        );
                        if($this->input->post('gallery_image'.$i) !== null && $this->input->post('gallery_des'.$i) !== null){
                            $query_4 = $this->db->insert('tbl_product_gallery',$arrayvalues_4);
                        }
                        $output_val = true;
                    }
                }

                

                if($output_val == true){
                    $output=array('output'=>'true');
                }else {
                    $output=array('output'=>'Error Insert Data');       
                }
            }else {
                $output=array('output'=>'false');
            }
        }
        echo json_encode($output);
    }


    function EditProduct()
    {
        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
            $output=array('output'=>'false');

            // Get data
            $id = $this->input->post('id');
            $product_code = $this->input->post('product_code');
            $product_barcode = $this->input->post('product_barcode');
            $product_available = $this->input->post('product_available');
            $manufactures_id = $this->input->post('manufactures_id');
            $unit_type_id = $this->input->post('unit_type_id');
            $user_type =  $this->input->post('user_type');
            $product_category_id = $this->input->post('product_category_id[]');

            $product_special_category_id = $this->input->post('product_special_category_id[]');

            $related_products = $this->input->post('related_products[]');
            if(is_array($related_products)){
                $related_products = implode(",", $related_products);
            }
            
            $price = $this->input->post('price');
            $quantity = $this->input->post('quantity');
            $sort_id = $this->input->post('sort_id');
            $admin_id = $this->session->userdata('admin_id');
            $status = $this->input->post('status');
            $datecreated = date("Y-m-d H:i:s");

            $asal = $this->input->post('asal');

            $arrayvalues = array(
                'product_code'=>$product_code,
                'product_barcode'=>$product_barcode,
                'product_available'=>$product_available,
                'price'=>$price,
                'unit_type_id'=>$unit_type_id,
                // 'user_type'=>$user_type,
                'quantity'=>$quantity,
                'manufactures_id'=>$manufactures_id,
                'related_products'=>$related_products,
                'sort_id'=>$sort_id,
                'status'=>$status,
                'updated_by'=>$admin_id,
                'dateupdated'=>$datecreated,
                'asal'=>$asal

            );


            $product_code_old = $this->crud_global->GetField('tbl_product',array('product_id'=>$id),'product_code');
            if($product_code_old != $product_code){
                $check_product_code = $this->crud_global->CheckNum('tbl_product',array('product_code'=>$product_code,'status !='=>0));
            }else{
                $check_product_code = false;
            }

            if(empty($product_code)){
                $output=array('output'=>'Product Code Cannot be Empty');
            }else if($check_product_code == true){
                $output=array('output'=>'Your Name has been registered');
            }else {

                $query = $this->crud_global->UpdateDefault('tbl_product',$arrayvalues,array('product_id'=>$id));

                if($query){

                    $output_val = false;

                    
                    $this->db->delete('tbl_procat',array('product_id'=>$id));

                    if(is_array($product_category_id)){
                        foreach ($product_category_id as $key_cat => $row_cat) {

                            $arrayvalues_cat = array(
                                'product_id' => $id,
                                'product_category_id' => $row_cat
                            );
                            $query_cat = $this->db->insert('tbl_procat',$arrayvalues_cat);

                            $parent_id = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$row_cat),'parent_id');
                            if($parent_id != 0){

                                $arrayvalues_cat_parent = array(
                                    'product_id' => $id,
                                    'product_category_id' => $parent_id
                                );
                                $query_cat_parent = $this->db->insert('tbl_procat',$arrayvalues_cat_parent);

                                $parent_id_2 = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$parent_id),'parent_id');
                                if($parent_id_2 != 0){  

                                    $arrayvalues_cat_parent_2 = array(
                                        'product_id' => $id,
                                        'product_category_id' => $parent_id_2
                                    );
                                    $query_cat_parent_2 = $this->db->insert('tbl_procat',$arrayvalues_cat_parent_2);
                                }
                            }
                            
                            if($query_cat){
                                $output_val = true;
                            }else {
                                $output_val = false;
                            }
                        }
                    }

                    $this->db->delete('tbl_product_special',array('product_id'=>$id));
                    if(is_array($product_special_category_id)){
                        foreach ($product_special_category_id as $key_spec => $row_spec) {

                            $arrayvalues_spec = array(
                                'product_id' => $id,
                                'product_special_category_id' => $row_spec
                            );
                            $query_spec = $this->db->insert('tbl_product_special',$arrayvalues_spec);

                            if($query_spec){
                                $output_val = true;
                            }else {
                                $output_val = false;
                            }
                        }
                    }

                    foreach ($arr_lang as $key => $value) {
                        $arrayvalues_2 = array(
                            'product_id' => $id,
                            'product_name' => $this->input->post('product_name_'.$value->language_id),
                            'product_subname' => $this->input->post('product_subname_'.$value->language_id),
                            'product_short_des' => $this->input->post('product_short_des_'.$value->language_id),
                            'product_des' => $this->input->post('product_des_'.$value->language_id),
                            'product_meta_des' => $this->input->post('product_meta_des_'.$value->language_id),
                            'product_keywords' => $this->input->post('product_keywords_'.$value->language_id),
                            'product_tags' => $this->input->post('product_tags_'.$value->language_id),
                            'language_id' => $value->language_id
                        );
                        $query_2 = $this->crud_global->UpdateDefault('tbl_product_data',$arrayvalues_2,array('product_id'=>$id));

                        if($query_2){
                            $output_val = true;
                        }else {
                            $output_val = false;
                        }
                    }



                    // Insert Attribute     
                    $val_attr = $this->input->post('val_attr');
                    $val_attr_name = $this->input->post('val_attr_name');
                    $val_attr_first = $this->input->post('val_attr_first');
                    if($val_attr > 0){
                        for ($i=$val_attr_first; $i<=$val_attr_name ; $i++) { 
                            $check_attr = $this->crud_global->CheckNum('tbl_product_spec',array('product_id'=>$id,'product_spec_id'=>$i));

                            if($check_attr == true){
                                $arrayvalues_3 = array(
                                    'product_spec' => $this->input->post('attribute_name'.$i),
                                    'product_spec_desc' => $this->filter->FilterTextarea($this->input->post('attribute_des'.$i)),
                                    'sort_id' => $this->input->post('attribute_sort'.$i),
                                );
                                $query_3 = $this->crud_global->UpdateDefault('tbl_product_spec',$arrayvalues_3,array('product_spec_id'=>$i));
                            }else {
                                $arrayvalues_3 = array(
                                    'product_id' => $id,
                                    'product_spec' => $this->input->post('attribute_name'.$i),
                                    'product_spec_desc' => $this->filter->FilterTextarea($this->input->post('attribute_des'.$i)),
                                    'sort_id' => $this->input->post('attribute_sort'.$i),
                                );

                                if($this->input->post('attribute_name'.$i) !== null && $this->input->post('attribute_des'.$i) !== null){
                                    $query_3 = $this->db->insert('tbl_product_spec',$arrayvalues_3);
                                }
                            }

                            $output_val = true;
                        }
                    }

                    // Delete Attribute     
                    $del_attr = $this->input->post('attr_del');
                    if($del_attr !== null){
                        $ex_attr = explode(",", $del_attr);
                        foreach ($ex_attr as $key => $value) {
                            $this->db->delete('tbl_product_spec',array('product_spec_id'=>$value));
                        }
                    }
                    

                    // Insert Gallery
                    $val_gallery = $this->input->post('val_gallery');
                    $val_gallery_name = $this->input->post('val_gallery_name');
                    $val_gallery_first = $this->input->post('val_gallery_first');
                    if($val_gallery > 0){
                        for ($i=$val_gallery_first; $i<=$val_gallery_name ; $i++) { 
                            $check_gallery = $this->crud_global->CheckNum('tbl_product_gallery',array('product_id'=>$id,'product_gallery_id'=>$i));

                            if($check_gallery == true){
                                $arrayvalues_4 = array(
                                    'product_image' => $this->input->post('gallery_image'.$i),
                                    'product_caption' => $this->input->post('gallery_des'.$i),
                                    'sort_id' => $this->input->post('gallery_sort'.$i),
                                );
                                $query_4 = $this->crud_global->UpdateDefault('tbl_product_gallery',$arrayvalues_4,array('product_gallery_id'=>$i));
                            }else {
                                $arrayvalues_4 = array(
                                    'product_id' => $id,
                                    'product_image' => $this->input->post('gallery_image'.$i),
                                    'product_caption' => $this->input->post('gallery_des'.$i),
                                    'sort_id' => $this->input->post('gallery_sort'.$i),
                                );

                                if($this->input->post('gallery_image'.$i) !== null && $this->input->post('gallery_des'.$i) !== null){
                                    $query_4 = $this->db->insert('tbl_product_gallery',$arrayvalues_4);
                                }
                            }
                            
                            $output_val = true;
                        }
                    }

                    // Delete gallery   
                    $del_gallery = $this->input->post('gallery_del');
                    if($del_gallery !== null){
                        $ex_gallery = explode(",", $del_gallery);
                        foreach ($ex_gallery as $key => $value) {
                            $this->db->delete('tbl_product_gallery',array('product_gallery_id'=>$value));
                        }
                    }


                    if($output_val == true){
                        $output=array('output'=>'true');
                    }else {
                        $output=array('output'=>'Error Insert Data');       
                    }
                }else {
                    $output=array('output'=>'false');
                }
            }
            echo json_encode($output);
    }

    function GetProductCategory($id)
    {
        $output = false;

        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);

        if(!empty($id)){
            $product_cat = $this->crud_global->GetField('tbl_product_category_data',array('product_category_id'=>$id,'language_id'=>$arr_lang[0]->language_id),'product_category_name');
            $parent_id = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$id),'parent_id');
            $arr_data = $this->crud_global->ShowTableDefault('tbl_product_category',array('status'=>1,'product_category_id'=>$parent_id));
            // if(is){
                // $output = 'Parent';
            // }else {
                $output = $product_cat;
            // }  
        }else {
            $output = false;
        }
        return $output;
    }

    function GetProductName($id)
    {
        $output = false;

        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
        if(!empty($id)){
            $product_name = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$id,'language_id'=>$arr_lang[0]->language_id),'product_name');
            // $parent_id = $this->crud_global->GetField('tbl_product_category',array('product_category_id'=>$id),'parent_id');
            $output = $product_name;
        }else {
            $output = false;
        }

        return $output;
    }


    function SelectProductSpecial($id=false)
    {
        // $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
        $arr_data = $this->crud_global->ShowTableDefault('tbl_product_special_category',array('status'=>1));
        ?>
        <select class="form-control select2" title="...Select Special Product..." id="product_special_id" name="product_special_category_id[]" data-live-search="true" multiple style="width:100%;" data-required="false">
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    $selected = false;
                    // Check if id tidak kosong
                    if(!empty($id)){
                        $selected = false;
                        if($id == $row->product_special_category_id){
                            $selected = 'selected';
                        }
                    }
                    ?>
                    <option value="<?php echo $row->product_special_category_id;?>" <?php echo $selected;?>><?php echo $row->product_special_category;?></option>
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
                $arr = $this->crud_global->ShowTableNew('tbl_product_category',array('status'=>1));
                if(is_array($arr)){
                    foreach ($arr as $key => $row) {
                        if($id == $row->product_category_id){
                             $output = $this->crud_global->GetField('tbl_product_category_data',array('product_category_id'=>$row->product_category_id),'product_category_name');
                        }
                    }
                }
            }
        }

        return $output;
    }


    function SubsProductCategory($parent=0,$hasil)
    {
        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
        $w = $this->db->query("SELECT * from tbl_product_category where status = 1 AND parent_id='".$parent."'");
        if(($w->num_rows())>0)
        {
            $hasil .= "<ul class='list-cat'>";
        }
        foreach($w->result() as $h)
        {
            $hasil .= "<li><div class='form-group'><input type='checkbox' class='icheckbox' id='product_attributes_id' name='product_attributes_id[]'' value='".$h->product_category_id."'>".$h->product_category_id."</div>";
            $hasil = $this->SubsProductCategory($h->product_category_id,$hasil);
            $hasil .= "</li>";
        }
        if(($w->num_rows)>0)
        {
            $hasil .= "</ul>";
        }
        return $hasil;
    }

    function SelectProductCategory($id=false)
    {
        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
        $arr_data = $this->crud_global->ShowTableDefault('tbl_product_category',array('status'=>1));
        ?>
        <select class="form-control select2" title="...Select Category..." id="product_category_id" name="product_category_id[]" data-live-search="true" style="width:100%;" data-required="false" multiple >
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    $product_cat = $this->crud_global->GetField('tbl_product_category_data',array('product_category_id'=>$row->product_category_id,'language_id'=>$arr_lang[0]->language_id),'product_category_name');
                    ?>
                    <option value="<?php echo $row->product_category_id;?>"><?php echo $product_cat;?></option>
                    <?php   
                }
            }
            ?>
        </select>
        <?php

    }


    function SelectRelatedProducts($id=false)
    {
        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);

        $arr_data =false;
        if(!empty($id)){
            $arr_data = $this->crud_global->ShowTableDefault('tbl_product',array('status'=>1));
        }else {
            $arr_data = $this->crud_global->ShowTableDefault('tbl_product',array('status'=>1));
        }
        ?>
        <select class="form-control selectpicker" title="...Select Related Products..." name="related_products[]" id="related_products" data-live-search="true" multiple>
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    $selected = false;
                    // Check if id tidak kosong
                    if(!empty($id)){
                        $selected = false;
                        if($id == $row->product_id){
                            $selected = 'selected';
                        }
                    }

                    $product_name = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$row->product_id,'language_id'=>$arr_lang[0]->language_id),'product_name');
                    ?>
                    <option value="<?php echo $row->product_id;?>" <?php echo $selected;?>><?php echo $product_name;?></option>
                    <?php   
                }
            }
            ?>
        </select>
        <?php
    }

    function SelectManufactures($id=false)
    {
        // $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
        $arr_data = $this->crud_global->ShowTableDefault('tbl_manufactures',array('status'=>1));
        ?>
        <select class="form-control selectpicker" title="...Select Manufactures..." name="manufactures_id" data-live-search="true">
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    $selected = false;
                    // Check if id tidak kosong
                    if(!empty($id)){
                        $selected = false;
                        if($id == $row->manufactures_id){
                            $selected = 'selected';
                        }
                    }
                    ?>
                    <option value="<?php echo $row->manufactures_id;?>" <?php echo $selected;?>><?php echo $row->manufactures_name;?></option>
                    <?php   
                }
            }
            ?>
        </select>
        <?php
    }
   
    

    function GetAuthor($post_id)
    {
        $output = false;

        $author_id = $this->crud_global->GetField('tbl_post',array('status'=>1,'post_id'=>$post_id),'created_by');
        $user_type = $this->crud_global->GetField('tbl_post',array('status'=>1,'post_id'=>$post_id),'user_type');

        if($user_type == 1){
            $author = $this->crud_global->GetField('tbl_admin',array('status'=>1,'admin_id'=>$author_id),'admin_name');
            $output = $author;
        }

        return $output;

    }

    function SelectParentProductCategory($id=false)
    {
        $arr_lang = $this->crud_global->ShowTable('tbl_language',false);
        $arr_data = $this->crud_global->ShowTableDefault('tbl_product_category',array('status'=>1));
        ?>
        <select class="form-control select2" name="parent_id" data-live-search="true" style="width:100%;">
            <option value="0">Parent</option>
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    $selected = false;
                    // Check if id tidak kosong
                    if(!empty($id)){
                        $selected = false;
                        if($id == $row->product_category_id){
                            $selected = 'selected';
                        }
                    }
                    $product_cat = $this->crud_global->GetField('tbl_product_category_data',array('product_category_id'=>$row->product_category_id,'language_id'=>$arr_lang[0]->language_id),'product_category_name');
                    ?>
                    <option value="<?php echo $row->product_category_id;?>" <?php echo $selected;?>><?php echo $product_cat;?></option>
                    <?php   
                }
            }
            ?>
        </select>
        <?php
    }

    function ArrUnitType()
    {
        $output = false;

        $arr = array(
            1 => 'Kg',
            2 => 'Inch',
            );

        $output = $arr;

        return $output;
    }

    function SelectUnitType($id=false)
    {
        $arr_data = $this->ArrUnitType();
        ?>
        <select class="form-control select2" title="...Select Unit Type..." id="unit_type_id" name="unit_type_id" style="width:100%;" data-required="false" >
            <?php
            if(is_array($arr_data)){
                foreach ($arr_data as $key => $row) {
                    // $product_cat = $this->crud_global->GetField('tbl_product_category_data',array('product_category_id'=>$row->product_category_id,'language_id'=>$arr_lang[0]->language_id),'product_category_name');
                    ?>
                    <option value="<?php echo $key;?>"><?php echo $row;?></option>
                    <?php   
                }
            }
            ?>
        </select>
        <?php

    }


    function GetUnitType($id)
    {
        $arr_data = $this->ArrUnitType();

        $output = false;
        if(is_array($arr_data)){
            foreach ($arr_data as $key => $row) {
                if($key == $id){
                    $output = $row;
                }
            }
        }

        return $output;
    }

    function ShowProduct($arraywhere=false,$limit=false,$start=false,$sort=false,$arraywherein=false,$cat=false,$search=false)
    {
        $output=false;

        $this->db->select("*");
        $this->db->where('status',1);

        if(!empty($arraywhere)){
            if(is_array($arraywhere)){
                foreach ($arraywhere as $key => $value) {
                    $this->db->where($key,$value);
                }
            }
        }

        if(!empty($arraywherein)){
            if(is_array($arraywherein)){
                foreach ($arraywherein as $key => $value) {
                    $this->db->where_in($key,$value);
                }
            }
        }

        if(!empty($sort)){
            if(is_array($sort)){
                foreach ($sort as $key => $value) {
                    $this->db->order_by($key,$value);
                }
            }
        }else {
            $this->db->order_by("datecreated","DESC");   
        }

        if(!empty($limit)){
            $this->db->limit($limit);
        }

        if(!empty($start)){
            if(is_array($start)){
                foreach ($start as $key => $value) {
                    // $this->db->where($key,$value);
                    $this->db->limit($key, $value);
                }
            }
        }
        if(!empty($cat)){
            $this->db->join('tbl_procat','tbl_procat.product_id = tbl_product.product_id','left');
            $this->db->where('tbl_procat.product_category_id',$cat);
        }
        if(!empty($search)){
            $this->db->join('tbl_product_data','tbl_product_data.product_id = tbl_product.product_id','left');
            $this->db->like('tbl_product_data.product_name',$search);
        }
        $this->db->where('status',1);
        $query = $this->db->get('tbl_product');
        if($query->num_rows() > 0){
            $output = $query->result();
        }else {
            $output=false;
        }

        return $output; 
    }


    function GetProductCode()
    {
        $output = false;

        $arr = $this->crud_global->ShowTableNew('tbl_product',array('status'=>1),array('product_id'=>'desc'));

        $output = $arr[0]->product_code + 1;

        return $output;
    }

}