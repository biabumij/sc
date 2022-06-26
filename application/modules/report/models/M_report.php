<?php

class M_report extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


    function SelectMenu($selected=false,$edit=false)
    {
        ?>
        <select class="form-control select2" data-live-search="true" title="...Choose parent..." name="parent_id" type="select" style="width:100%;">
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

    function GetParentMenu($id)
    {
        $arr=$this->crud_global->ShowTableNew('tbl_menu',array('status'=>1));
        $output=false;
        
        if($id == 0){
            $output = 'Parent';
        }else {
            if(is_array($arr)){
            foreach ($arr as $key => $row) {
                    if($row->menu_id == $id){
                        $output= $row->menu_name;
                    }           
                }
            }
        }
        return $output;
    }

}