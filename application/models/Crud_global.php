<?php
class Crud_global extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('general');
    }


    function ShowTable($table,$arraywhere,$order=false)
    {
    	$output=false;

    	$this->db->select("*");
    	if(!empty($arraywhere)){
    		foreach ($arraywhere as $key => $val) {
    			$this->db->where($key,$val);
    		}
    	}
    	if(!empty($order)){
    		$this->db->order_by("order_id",$order);
    		$this->db->order_by("datecreated",$order);
    	}else {
    		$this->db->order_by("order_id","desc");
    	}
    	$this->db->where('status !=',0);
    	$query=$this->db->get($table);
    	if($query->num_rows() > 0){
    		$output=$query->result();
    	}else {
    		$output =false;
    	}

    	return $output;
    }

    function ShowTableNoOrder($table,$arraywhere)
    {
    	$output=false;

    	$this->db->select("*");
    	if(!empty($arraywhere)){
    		foreach ($arraywhere as $key => $val) {
    			$this->db->where($key,$val);
    		}
    	}
    	$this->db->where('status !=',0);
    	$this->db->order_by("datecreated","desc");
    	$query=$this->db->get($table);
    	if($query->num_rows() > 0){
    		$output=$query->result();
    	}else {
    		$output =false;
    	}

    	return $output;
    }
	
	function ShowTableNoOrderStatus($table,$arraywhere,$order_by=false)
    {
    	$output=false;

    	$this->db->select("*");
    	if(!empty($arraywhere)){
    		foreach ($arraywhere as $key => $val) {
    			$this->db->where($key,$val);
    		}
    	}
    	if(!empty($order_by)){
    		$this->db->order_by('datecreated',$order_by);
    	}else {
    		$this->db->order_by('datecreated','desc');
    	}
    	$query=$this->db->get($table);
    	if($query->num_rows() > 0){
    		$output=$query->result();
    	}else {
    		$output =false;
    	}

    	return $output;
    }

    function ShowTableDefault($table,$arraywhere,$order=false)
    {
    	$output=false;

    	$this->db->select("*");
    	if(!empty($arraywhere)){
    		foreach ($arraywhere as $key => $val) {
    			$this->db->where($key,$val);
    		}
    	}
    	if(!empty($order)){
    		$this->db->order_by('datecreated',$order);
    	}
    	$query=$this->db->get($table);
    	if($query->num_rows() > 0){
    		$output=$query->result();
    	}else {
    		$output =false;
    	}

    	return $output;
    }

    function ShowTableNew($table,$arraywhere=false,$order=false)
    {
    	$output=false;

    	$this->db->select("*");
    	if(!empty($arraywhere)){
    		foreach ($arraywhere as $key => $val) {
    			$this->db->where($key,$val);
    		}
    	}
    	if(!empty($order)){
    		foreach ($order as $key => $val) {
    			$this->db->order_by($key,$val);
    		}
    	}
    	$query=$this->db->get($table);
    	if($query->num_rows() > 0){
    		$output=$query->result();
    	}else {
    		$output =false;
    	}

    	return $output;
    }

    function ShowTableLimit($table,$arraywhere=false,$order=false,$limit=false)
    {
    	$output=false;

    	$this->db->select("*");
    	if(!empty($arraywhere)){
    		foreach ($arraywhere as $key => $val) {
    			$this->db->where($key,$val);
    		}
    	}
    	if(!empty($order)){
    		foreach ($order as $key => $val) {
    			$this->db->order_by($key,$val);
    		}
    	}
    	if(!empty($limit)){
    		$this->db->limit($limit);
    	}
    	$query=$this->db->get($table);
    	if($query->num_rows() > 0){
    		$output=$query->result();
    	}else {
    		$output =false;
    	}

    	return $output;
    }



    function GetField($table,$arraywhere,$get_field)
	{
		$str='';
		
		$this->db->select($get_field);
		foreach($arraywhere as $key => $val) {
			$this->db->where($key,$val);
		}
		$query=$this->db->get($table);
		if($query->num_rows() > 0) {
			$row=$query->row();
			$str=$row->$get_field;
		}
		$query->free_result();
		
		return $str;
	}		
	
	function GetNum($table,$arraywhere)
	{
		$num=false;
		
		$this->db->select('*');
		if(is_array($arraywhere)) {
			foreach($arraywhere as $key => $val) {
				$this->db->where($key,$val);
			}
		}
		$query=$this->db->get($table);
		$num=$query->num_rows();
		$query->free_result();
		
		return $num;
	}

	function CheckNum($table,$arraywhere)
	{
		$output=false;
		$this->db->select('*');
		if(is_array($arraywhere)) {
			foreach($arraywhere as $key => $val) {
				$this->db->where($key,$val);
			}
		}
		$query=$this->db->get($table);
		if($query->num_rows() > 0){
			$output=true;
		}else {
			$output=false;
		}

		return $output;
	}

	function CheckNumOrderParent($table,$arraywhere,$parent=false)
	{
		$output=false;
		$this->db->select('*');
		if(is_array($arraywhere)) {
			foreach($arraywhere as $key => $val) {
				$this->db->where($key,$val);
			}
		}
		if($parent != false){
			$this->db->where('parent_id',$parent);
		}
		$this->db->where('status !=',0);
		$query=$this->db->get($table);
		if($query->num_rows() > 0){
			$output=true;
		}else {
			$output=false;
		}

		return $output;
	}

	function UpdateDefault($table,$arrayvalues,$arraywhere)
	{
		$msg='false';
		
		if(!empty($arraywhere)) {
			$querycheck=$this->db->get_where($table,$arraywhere);
			$numcheck=$querycheck->num_rows();
			if(empty($numcheck)) {
				$msg='false';
			}else{			
				foreach($arraywhere as $key => $val) {
					$this->db->where($key,$val);
				}
				$query=$this->db->update($table,$arrayvalues);
				if($query) {
					$msg='true';
				}else{
					$msg='false';
				}
			}
		}
		
		return $msg; 
	}

	function do_upload($filename){
		$output=false;

		$config = array(
			'upload_path' => "./uploads/",
			'allowed_types' => "*",
			'max_size'	=> '5120',
			'overwrite' => TRUE,
			'encrypt_name' => TRUE
		);

		$this->load->library('upload', $config);
		if($this->upload->do_upload($filename)) {
			$data = $this->upload->data();
			$output = $data['file_name'];
		}else {
			$error =$this->upload->display_errors();
			$output = false;
		}

		return $output;
	}


	function InsertOutputJson($table,$arrayvalues)
	{
		$output=array('output'=>'false');

        $query=$this->db->insert($table,$arrayvalues);
        if($query){
            $output=array('output'=>'true');
        }else {
            $output=array('output'=>'false');
        }

        echo json_encode($output);
	}

	function UpdateOutputJson($table,$arrayvalues,$arraywhere)
	{
		$output=array('output'=>'false');

		foreach ($arraywhere as $key => $value) {
			$arraywhere=array($key=>$value);
		}
        $query=$this->crud_global->UpdateDefault($table,$arrayvalues,$arraywhere);
        if($query){
            $output=array('output'=>'true');
        }else {
            $output=array('output'=>'false');
        }

        echo json_encode($output);
	}


	function UploadAjax($folder)
	{
		$output=array('output'=>'false');
		$config = array(
			'upload_path' => "./uploads/".$folder."/",
			'allowed_types' => "gif|jpg|png|jpeg",
			'max_size'	=> '5120',
			'encrypt_name' => TRUE,
			'overwrite' => TRUE
		);

		$this->load->library('upload', $config);
		if($this->upload->do_upload('file')){
			$data_upload = $this->upload->data();
			$output=array('output'=>'true','data_image'=>$data_upload['file_name']);		
		}else {
			$output=array('output'=>$this->upload->display_errors());
		}
		echo json_encode($output);
	}

	function ButtonDefaultUpdate($data_ajax,$title,$action)
	{
		if($action == 1){
			?>
			<button class="btn btn-warning btn-modal" data-toggle="modal" data-target="#ModalEdit" data-modal-title="Edit <?php echo $title;?>" data-modal-ajax="<?php echo $data_ajax;?>"><i class="fa fa-edit"></i></button>
			<?php
		}
	}

	function ButtonDefaultDelete($data_ajax,$data_value,$data_redirect,$action)
	{
		if($action == 1){
			?>
			<button class="btn btn-danger btn-delete" data-delete="<?php echo $data_ajax;?>" data-value="<?php echo $data_value;?>" data-redirect="<?php echo $data_redirect;?>"><i class="fa fa-trash"></i></button>
			<?php
		}
	}

	function OrderInput($table,$category=false,$value=false)
	{
		$data=false;
		if($value == false){
			if($category){
				$arr = $this->ShowTable($table,array('post_category_id'=>$category));
			}else {
				$arr = $this->ShowTable($table,false);	
			}
			
			$arr_new =false;
			if(is_array($arr)){
				foreach ($arr as $key => $value) {
				$arr_new[] = $value->order_id;
				}
				$max = max($arr_new);
				$data = $max + 1;
			}else {
				$data=1;	
			}
		}else {
			$data=$value;
		}
		?>
		<input type="number" name="order_id" class="form-control order_input" value="<?php echo $data;?>" min="0" ></input>
		<?php
	}

	function OrderInputPost($table,$id,$value=false)
	{
		$data=false;
		if($value == false){
			$arr = $this->ShowTable($table,array('post_category_new_id'=>$id));
			$arr_new =false;
			if(is_array($arr)){
				foreach ($arr as $key => $value) {
				$arr_new[] = $value->order_id;
				}
				$max = max($arr_new);
				$data = $max + 1;
			}else {
				$data=0;	
			}
		}else {
			$data=$value;
		}
		?>
		<input type="number" name="order_id" class="form-control order_input" value="<?php echo $data;?>" min="0" ></input>
		<?php
	}

	function GetElementInput($id,$pages_data,$lang_code,$content=false,$no=false)
	{
		// $pages_element = $this->GetField('tbl_pages_element',array('pages_id'=>$id),'element_input_id');
		$element_input_type = $this->GetField('tbl_element_input',array('element_input_id'=>$id),'element_input_type');
		$element_input = $this->GetField('tbl_element_input',array('element_input_id'=>$id),'element_input');

		$lang_flag = $this->GetField('tbl_language',array('language_id'=>$lang_code),'language_code');

		// $content=false;
		if(!empty($content)){
			$content=$content;
		}
		if($no != 1){
			$default_checkbox = '<input type="checkbox" name="'.$pages_data.'" value="1" checked/> Same With Main Languange';
		}else {
			$default_checkbox = false;
		}
		?>
		<div class="form-group">
	    	<label style="margin-right:20px;"><?php echo $element_input;?><i class="flagstrap-icon flagstrap-<?php echo $lang_flag;?>" style="margin-left: 10px;"></i></label>
	    	
	    	<?php echo $this->general->GetElementInputType($element_input_type,$pages_data,$lang_code,$content);?>
	  	</div>
		<?php
	}

	function ShowElementInput($id,$lang_code,$content)
	{
		// $pages_element = $this->GetField('tbl_pages_element',array('pages_id'=>$id),'element_input_id');
		$element_input_type = $this->GetField('tbl_element_input',array('element_input_id'=>$id),'element_input_type');
		$element_input = $this->GetField('tbl_element_input',array('element_input_id'=>$id),'element_input');

		$lang_flag = $this->GetField('tbl_language',array('language_id'=>$lang_code),'language_code');

		?>
		<tr>
	        <th width="150"><?php echo $element_input;?></th>
	        <th width="20">:</th>
	        <td><?php echo $content;?></td>
	    </tr>
		<?php
	}

	function deleteData($table,$arraywhere)
	{
		$output = false;
		foreach ($arraywhere as $key => $value) {
			$this->db->where($key, $value);
		}
		if($this->db->delete($table)){
			$output = true;
		}

		return $output;
	}

}

