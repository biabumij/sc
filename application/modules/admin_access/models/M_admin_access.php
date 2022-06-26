<?php

class M_admin_access extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function SelectAccess($selected=false)
    {
        $arr=$this->crud_global->ShowTableNew('tbl_menu',array('status'=>1),array('order_id'=>'asc'));
        if(is_array($arr)){
            foreach ($arr as $key => $row) {
                $checked = false;
                if(!empty($selected)){
                    $arr_check = $this->crud_global->ShowTableNew('tbl_admin_access',array('admin_group_id'=>$selected));
                    if(is_array($arr_check)){
                        foreach ($arr_check as $key_check => $row_check) {
                            if($row->menu_id == $row_check->menu_id){
                                $checked = 'checked=""';
                            }
                        }
                    }
                }
                ?>
                <div class="checkbox-custom checkbox-primary">
                    <input type="checkbox" id="<?php echo $row->menu_alias;?>" value="<?php echo $row->menu_id;?>" name="menu_id[]" <?php echo $checked;?>>
                    <label class="check" for="<?php echo $row->menu_alias;?>"><?php echo $row->menu_name;?></label>
                </div>
                <?php
            }
        }
    }

    

}