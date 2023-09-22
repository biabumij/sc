<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Index General Class

1. ZebraTable($no)
2. validateIPv4($ip)
3. validateIPv6($ip)
4. isValidURL($url)
5. SetGender($value)

*/

Class General
{

	function ZebraTable($no)
	{
		$style=false;
						
		if($no%2==0) {
			$style='style="background-color:#eafcf4;"';
		}else{
			$style='style="background-color:#ffffff;"';
		}		
						
		return $style;
	}
	
	function ZebraTable2($no)
	{
		$style=false;
						
		if($no%2==0) {
			$style='bgcolor="#fefefe"';
		}else{
			$style='bgcolor="#eff8ff"';
		}		
						
		return $style;
	}	

	function validateIPv4($ip) {
	  if ( false === filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE) )
	  {
	      return false;
	  }
	  else
	  {
	      return true;
	  }
	}

	function validateIPv6($ip) {
	  if ( false === filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE) )
	  {
	      return false;
	  }
	  else
	  {
	      return true;
	  }
	}		
	
	function isValidURL($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}	
	
	function SetGender($value)
	{
		$output=false;
		
		switch($value) {
			case 1 :
			$output='Male';
			break;
			
			case 2 :
			$output='Female';
			break;			
		}
		
		return $output;
	}
	
	function RoundingThousands($int)
	{
		$output=false;
		
		// pembulatan ribuan
		$ratusan = substr($int, -3);
		 if($ratusan < 500) {
			$akhir = $int - $ratusan;
		}else{
			$akhir = $int + (1000-$ratusan);
		}		
		$output=$akhir;
		
		return $output;
	}
	

	function ArrStatus()
    {
        $output = false;

        $arr = array(
                1 => array(
                        'title' => 'Enabled',
                        'color' => 'info'
                    ),
                2 => array(
                        'title' => 'Disabled',
                        'color' => 'warning'
                    )
            );

        $output=$arr;

        return $output;
    }

    function GetStatus($id)
    {
        $arr=$this->ArrStatus();
        $output=false;
        
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($key == $id){               
                    $output='<button type="button" class="btn btn-'.$value['color'].'" style="font-weight:bold; border-radius:10px;">'.$value['title'].'</button>';
                }else if($id == 3){
                    $output='<button type="button" class="btn btn-danger" style="font-weight:bold; border-radius:10px;">Not Active </button>';
                }
            }
        }
        return $output;
    }

    function SelectStatus($selected=false)
    {
        $arr=$this->ArrStatus();

        if(is_array($arr)){
            ?>
            <select class="form-control select2" title="...Choose Status..." name="status" aria-required="true" aria-describedby="digits-error" type="select" style="width: 100%;">
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
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $value['title'];?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }


    function ArrStatus2()
    {
        $output = false;

        $arr = array(
                1 => array(
                        'title' => 'Enabled',
                        'color' => 'info'
                    ),
                2 => array(
                        'title' => 'Disabled',
                        'color' => 'warning'
                    )
            );

        $output=$arr;

        return $output;
    }

    function GetStatus2($id)
    {
        $arr=$this->ArrStatus2();
        $output=false;
        
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($key == $id){
                    $output='<span class="label label-sm label-form label-'.$value['color'].'" style="display:block;"> '.$value['title'].' </span>';
                }           
            }
        }
        return $output;
    }

    function SelectStatus2($selected=false)
    {
        $arr=$this->ArrStatus2();

        if(is_array($arr)){
            ?>
            <select class="form-control selectpicker" name="status" aria-required="true" aria-describedby="digits-error" type="select">
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
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $value['title'];?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

    function NumberMoney($money)
    {
    	$output=false;

        if(!empty($money)){
            $output = "Rp. ".number_format($money,0,".",".");
        }

    	return $output ;
    }

    function GetStatusStock($qty)
    {   
        $output = false;

        if($qty > 0){
            $output = 'In Stock';
        }else {
            $output = 'Out of Stock';
        }

        return $output;
    }

    function GetStatusHilal($id)
    {   
        $output = false;

        if($id == 0){
            $output = 'Order';
        }else if($id == 1){
            $output = 'Paid';
        }else if($id == 2){
            $output = 'Refund';
        }else {
            $output = 'Cancel';
        }

        return $output;
    }

    function ArrStatusProject()
    {
    	$output=false;

    	$arr = array(
    			1 => array(
                    'title' => 'Progress',
                    'color' => '#1D638B'
                ),
    			2 => array(
                    'title' => 'Cancel',
                    'color' => '#C1392B'
                ),
                3 => array(
                    'title' => 'Success',
                    'color' => '#2DCC70'
                ),
    		);
    	$output = $arr;
    	return $output;
    }
    function GetStatusProject($id)
    {
    	$arr = $this->ArrStatusProject();
    	if(is_array($arr)){
    		foreach ($arr as $key => $val) {
    			if($id == $key){
    				echo $val['title'];
    			}
    		}
    	}
    }

    function GetStatusProjectColor($id)
    {
        $arr = $this->ArrStatusProject();
        if(is_array($arr)){
            foreach ($arr as $key => $val) {
                if($id == $key){
                    echo $val['color'];
                }
            }
        }
    }

    function SelectStatusProject($selected=false)
    {
        $arrdata=$this->ArrStatusProject();
        if(is_array($arrdata)){
            ?>
            <select class="form-control" name="status" title="...Choose Status...">
                <?php
                foreach ($arrdata as $key => $row) {
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
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $row['title'];?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

    function ArrSelectOption()
    {
        $output=false;

        $arr = array(
                1 => 'Yes',
                2 => 'No'
            );
        $output = $arr;

        return $output;
    }
    function GetSelectOption($id)
    {
        $arr = $this->ArrSelectOption();
        if(is_array($arr)){
            foreach ($arr as $key => $val) {
                if($id == $key){
                    echo $val;
                }
            }
        }
    }

    function SelectOption($name,$selected=false)
    {
        $arrdata=$this->ArrSelectOption();
        if(is_array($arrdata)){
            ?>
            <select class="form-control select2" name="<?php echo $name;?>" title="..Choose Option.." style="width:100%;">
                <?php
                foreach ($arrdata as $key => $row) {
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
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $row;?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

    function GetAvatarNone($name)
    {
        $output=false;

        if(!empty($name)){
            $output ='<div class="avatar-none">'.substr($name, 0,1).'</div>';
        }else {
            $output=false;            
        }

        echo $output;
    }


    function ArrRadioOrder()
    {
        $output=false;

        $arr = array(
                1 => 'Number',
                2 => 'Date',
            );
        $output = $arr;
        return $output;
    }

    function SelectRadioOrder($selected=false)
    {
        $arr = $this->ArrRadioOrder();

        if(is_array($arr)){
            foreach ($arr as $key => $row) {
                $active =false;
                if(!empty($selected)){
                    if($key == $selected){
                        $active = 'checked=""'; 
                    }
                }else {
                    if($key == 1){
                        $active = 'checked=""'; 
                    }
                }
                ?>
                <div class="radio radio-custom radio-inline radio-primary">
                    <input type="radio" id="<?php echo $key;?>" name="order_by" value="<?php echo $key;?>" <?php echo $active;?> >
                    <label for="<?php echo $key;?>"><?php echo $row;?></label>
                </div>
                <?php
            }
        }
    }

    function GetRadioOrder($id)
    {
        $arr=$this->ArrRadioOrder();
        $output=false;
        
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($key == $id){
                    $output= $value;
                }           
            }
        }
        return $output;
    }


    function ArrSelectOrder()
    {
        $output=false;

        $arr = array(
                1 => 'Order by Number',
                2 => 'Order by Date',
            );
        $output = $arr;
        return $output;
    }

    function SelectOrder($id=false)
    {  
        // $arr = $this->ArrSelectOrder();

        ?>
        <div class="select-order" data-toggle="buttons">
            <?php
            if(!empty($id)){
                if($id == 2){
                    $active_2 = 'active';
                    $active_1 = false;
                    $checked_2 = 'checked';
                    $checked_1 = false;
                }else {
                    $active_1 = 'active';
                    $active_2 = false;
                    $checked_1 = 'checked';
                    $checked_2 = false;
                }
            }else {
                $active_1 = 'active';
                $active_2 = false;
                $checked_1 = 'checked';
                $checked_2 = false;
            }
            ?>
            <label class="btn btn-default <?php echo $active_1;?>">
                <input type="radio" name="order_by" autocomplete="off" value="1" <?php echo $checked_1;?>> Order by Number
            </label>
            <label class="btn btn-default <?php echo $active_2;?>">
                <input type="radio" name="order_by" autocomplete="off" value="2" <?php echo $checked_2;?>> Order by Date
            </label>
        </div>
        <?php
    }

    function GetOrderBy($id)
    {
        $arr = $this->ArrSelectOrder();
        $output =false;
        if(is_array($arr)){
            foreach ($arr as $key => $val) {
                if($id == $key){
                    $output = $val;
                }
            }
        }

        return $output;

        // echo 'tes';
    }


    function ArrSettingsPost()
    {
        $output=false;

        $arr = array(
                'image_url' => 'Image',
                'website_url' => 'Post Link',
                'color_id' => 'Color',
            );
        $output = $arr;
        return $output;
    }

    function SettingsPost($selected=false)
    {
        $arr = $this->ArrSettingsPost();

        if(is_array($arr)){
            ?>
             <div class="row">
             <?php
             $active = false;
                foreach ($arr as $key => $value) {
                    if(!empty($selected)){
                        $ex_select = explode(',', $selected);
                        $count =count($ex_select);
                        if($count == 1){
                            if($key == $selected){
                                $active = 'checked=true'; 
                            }else {
                                $active = false;
                            }
                        }else 
                        if($count == 2){
                            if($key == $ex_select[$count - $count] || $key == $ex_select[$count - ($count -1)]){
                                $active = 'checked=true'; 
                            }else {
                                $active = false;
                            }
                        }else {
                            if($key == $ex_select[$count - $count] || $key == $ex_select[$count - ($count -1)] || $key == $ex_select[$count - ($count -2)]){
                                $active = 'checked=true'; 
                            }else {
                                $active = false;
                            }
                        }
                    }
                    ?>
                    <div class="col-sm-3">
                        <div class="check-style">
                            <input type="checkbox" name="settings_post[]" value="<?php echo $key;?>" <?php echo $active;?>/> <span><?php echo $value;?></span>
                        </div>
                    </div>
                    <?php
                }
             ?>
            </div>
            <?php
        }
    }

    function NoPhoto($image_url,$data)
    {   
        $output=false;
        if(!empty($image_url)){
            $output = '<img src="'.$data.'" />';
        }else {
            $output = '<img src="'.base_url().'assets/front/images/no_photo.gif">';
        }
        return $output;
    }

    function NoPhotoUser($image_url,$data)
    {   
        $output=false;
        if(!empty($image_url)){
            $output = '<img src="'.$data.'" class="img-responsive img-admin"/>';
        }else {
            $output = '<img src="'.base_url().'assets/back/images/no-profile.gif" class="img-responsive img-admin">';
        }
        return $output;
    }

    function NoPhotoMember($image_url,$data,$name)
    {   
        $output=false;
        if(!empty($image_url)){
            $output = '<img src="'.$data.'" class="img-responsive img-admin"/>';
        }else {
            $output = $this->GetAvatarNone($name);
        }
        return $output;
    }

    function ArrInputType()
    {
        $output=false;

        $arr = array(
            1 => 'Input Text',
            2 => 'Input Number',
            3 => 'Input Email',
            4 => 'Input File',
            5 => 'Textarea',
            6 => 'Input Filemanager',
            );
        $output = $arr;

        return $output;
    }

    function GetInputType($id)
    {
        $output =false;

        $arr = $this->ArrInputType();

        foreach ($arr as $key => $value) {
            if($key == $id){
                $output = $value;
            }
        }

        return $output;
    }

    function GetElementInputType($id,$name,$lang_code,$content=false)
    {
        $output=false;

        if(!empty($content)){
            $content=$content;
        }

        switch ($id) {
            case 1:
            $output = '<input type="text" class="form-control" name="'.$name.'" placeholder="" value="'.$content.'" data-required="false" />';
            break;

            case 2:
            $output = '<input type="number" class="form-control" name="'.$name.'" value="'.$content.'" data-required="false"/>';
            break;

            case 3:
            $output = '<input type="email" class="form-control" name="'.$name.'" value="'.$content.'" data-required="false"/>';
            break;

            case 4:
            $output = $this->GetImage($name,$lang_code,$content);
            break;

            case 5:
            $output = '<textarea class="form-control" id="content'.$lang_code.'" name="'.$name.'" data-required="false">'.$content.'</textarea>';
            break;

            case 6:
            $output = $this->GetFileManager($name,$lang_code,$content);
            break;
            
        }

        return $output;
    }

    function GetImage($name,$lang_code,$content=false)
    {
        ?>
        <input type="file" data-cat="1" class="form-control btn-file-<?php echo $lang_code;?>" name="<?php echo $name;?>" value="<?php echo $content;?>"/>
        <?php
        if(!empty($content)){
            // $content = substr($content, 2);
            ?>
            <img style="max-width:200px;margin-top:20px;" src="<?php echo base_url();?>uploads/post/<?php echo $content;?>" />
            <?php
        }
        ?>
        <input type="hidden" class="hidden-IVC-<?php echo $lang_code;?>" name="<?php echo $name;?>" value="<?php echo $content;?>"/>
        <?php
    }

    function GetFileManager($name,$lang_code,$content=false)
    {
        ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="input-group">
                  <input type="text" class="form-control" id="<?php echo $name;?>" value="<?php echo $content;?>" data-required="false">
                  <input type="hidden" class="form-control" name="<?php echo $name;?>" id="<?php echo $name;?>_val" value="<?php echo $content;?>" data-required="false">
                  <span class="input-group-btn">
                    <a data-fancybox-type="iframe" href="<?php echo base_url();?>filemanager/dialog.php?type=0&field_id=<?php echo $name;?>" class="btn btn-primary iframe-btn" type="button">Browse</a>
                  </span>
                </div>
            </div>
            <div class="col-lg-6">
                <?php
                if(!empty($content)){
                    $file_type = substr($content, -3);
                    ?>
                    <div id="box-content_<?php echo $name;?>">
                        <?php
                        if($file_type == 'pdf' || $file_type == 'mp4' || $file_type == '3gp' || $file_type == 'avi'){
                            ?>
                            <a href="<?php echo base_url().$content;?>"><?php echo explode("/", $content)[1];?></a>
                            <?php
                        }else {
                            ?>
                            <img id="<?php echo $name;?>_prev" src="<?php echo base_url().$content;?>" class="img-responsive" />
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }else {
                    ?>
                    <div id="box-content_<?php echo $name;?>">
                        <img id="<?php echo $name;?>_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" />
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php   
    }

    function SelectInputType($selected=false,$value=false)
    {
        $arr = $this->ArrInputType();
        if(is_array($arr)){
            ?>
            <select id="element-input" class="form-control select2" name="element_input_type" title="...Choose Input Type.." style="width: 100%;">
                <?php
                foreach ($arr as $key => $row) {
                    $active= false;
                    if(!empty($selected)){
                        if($key == $selected){
                            $active= 'selected';
                        }else {
                            $active= false;
                        }
                    }
                    ?>
                    ?>
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $row;?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

    function ArrStatusTaskProject()
    {
        $output = false;

        $arr = array(
                1 => array(
                        'title' => 'Done',
                        'color' => '#E84C3D',
                        'color_2' => '#f5dfdd'
                    ),
                2 => array(
                        'title' => 'Working',
                        'color' => '#2B579A',
                        'color_2' => '#66aeff'
                    ),
                3 => array(
                        'title' => 'Pending',
                        'color' => '#F1C40F',
                        'color_2' => '#fdf6d8'
                    ),
                4 => array(
                        'title' => 'Schedule',
                        'color' => '#27AE61',
                        'color_2' => '#ddf7e8'
                    )
            );

        $output=$arr;

        return $output;
    }

    function GetStatusTaskProject($id)
    {
        $arr=$this->ArrStatusTaskProject();
        $output=false;
        
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($key == $id){
                    $output='<span class="label label-lg" style="background-color:'.$value['color'].'"> '.$value['title'].' </span>';
                }           
            }
        }
        return $output;
    }

    function GetColorStatusTaskProject($id)
    {
        $arr=$this->ArrStatusTaskProject();
        $output=false;
        
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($key == $id){
                    $output=$value['color'];
                }           
            }
        }
        return $output;
    }

    function GetColor2StatusTaskProject($id)
    {
        $arr=$this->ArrStatusTaskProject();
        $output=false;
        
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($key == $id){
                    $output=$value['color_2'];
                }           
            }
        }
        return $output;
    }


    function SelectStatusTaskProject($selected=false)
    {
        $arr=$this->ArrStatusTaskProject();

        if(is_array($arr)){
            ?>
            <select class="form-control selectpicker" title="...Choose Status..." name="status" aria-required="true" aria-describedby="digits-error" type="select">
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
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $value['title'];?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }

    public function LimitCharacters($words,$limit)
    {
        $output = false;

        if(strlen($words) <= $limit){
            $output = $words;
        }else {
            $output = substr($words, 0,$limit).' ...';
        }   
        return $output;
    }

    function InvoiceNo($id)
    {
        $output=false;

        if(!empty($id)){
            $strlen=strlen($id);
            switch($strlen) {
                case 1 :
                $u_id = '000'.$id;
                break;
                
                case 2 :
                $u_id = '00'.$id;
                break;
                
                case 3 :
                $u_id = '0'.$id;
                break;
                
                default :
                $u_id=$id;
                break;
            }
            $output = $u_id;
        }else {
            $output=false;          
        }

        return $output;
    }

    function PaymentNo($id)
    {
        $output=false;

        if(!empty($id)){
            $strlen=strlen($id);
            switch($strlen) {
                case 1 :
                $u_id = '0000'.$id;
                break;

                case 2 :
                $u_id = '000'.$id;
                break;
                
                case 3 :
                $u_id = '00'.$id;
                break;
                
                case 4 :
                $u_id = '0'.$id;
                break;
                
                default :
                $u_id=$id;
                break;
            }
            $output = 'HKK-'.$u_id;
        }else {
            $output=false;          
        }

        return $output;
    }


    function ArrStatusInvoice()
    {
        $output =false;

        $arr = array(
            1 => array(
                'label' => 'UNPAID',
                'color' => 'danger'
                ),
            2 => array(
                'label' => 'PAID',
                'color' => 'primary'
                )
        );

        $output = $arr;

        return $output;
    }

    function GetStatusInvoice($id)
    {
        $arr = $this->ArrStatusInvoice();

        $output =false;
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($id == $key){
                    $output='<span class="label label-'.$value['color'].'"> '.$value['label'].' </span>';
                }
            }
        }

        return $output;
    }

    function SelectStatusInvoice($selected=false)
    {
        $arr=$this->ArrStatusInvoice();

        if(is_array($arr)){
            ?>
            <select class="form-control selectpicker" title="...Choose Status..." name="status" aria-required="true" aria-describedby="digits-error" type="select">
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
                    <option value="<?php echo $key;?>" <?php echo $active;?>><?php echo $value['label'];?></option>
                    <?php
                }
                ?>
            </select>
            <?php
        }
    }


    function ArrKelamin()
    {
        $output =false;

        $arr = array(
            1 => 'Pria',
            2 => 'Wanita'
        );

        $output = $arr;

        return $output;
    }

    function GetGender($id)
    {
        $arr = $this->ArrKelamin();

        if(is_array($arr)){
            foreach ($arr as $key => $row) {
                if($id == $key){
                    echo $row;
                }
            }
        }
    }

    function SelectKelamin($id=false)
    {
        $arr = $this->ArrKelamin();
        if(is_array($arr)){
            foreach ($arr as $key => $value) {
                if($id == $key){
                    $checked = 'checked';
                }else {
                    $checked = false;
                }
                ?>
                <p>
                  <input class="with-gap" name="gender" type="radio" id="<?php echo $value;?>" value="<?php echo $key;?>" <?php echo $checked;?> />
                  <label for="<?php echo $value;?>"><?php echo $value;?></label>
                </p>
                <?php      
            }
        }
    }

    function StatusPayment($status)
    {
        if($status == 'CREATING'){
            $output = '<button type="button" class="btn btn-warning" style="font-weight:bold; border-radius:10px;">'.$status.'</button>';
        }else if($status == 'CREATED'){
            $output = '<button type="button" class="btn btn-success" style="font-weight:bold; border-radius:10px;">'.$status.'</button>';
        }else {
            $output = '<button type="button" class="btn btn-danger" style="font-weight:bold; border-radius:10px;">'.$status.'</button>';
        }

        return $output;
    }

}

?>