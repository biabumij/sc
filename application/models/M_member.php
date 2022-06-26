<?php
class M_member extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('general');
        $this->load->library('filter');
    }


    function check_login()
    {
        $sess_member_id = $this->session->userdata('member_id');

        if(!empty($sess_member_id)){
            return true;
        }else {
            return false;
        }
    }


    function SelectProvince($id=false)
    {
        $arr = $this->crud_global->ShowTableNew('provinces',false);

        ?>
        <select class="form-control selectpicker selectajax" name="province_id" data-live-search="true" data-type="regencies" data-id="province_id" data-required="false">
            <option value="">.. Select Provinsi ..</option>
            <?php
            if(is_array($arr)){
                foreach ($arr as $key => $row) {
                    $selected = false;
                    if(!empty($id)){
                        if($id == $row->id){
                            $selected = 'selected';
                        }
                    }
                    ?>
                    <option value="<?php echo $row->id;?>" <?php echo $selected;?>><?php echo $row->name;?></option>
                    <?php
                }
            }
            ?>
        </select>
        <?php
    }

    function SelectRegencie($id=false,$type=false)
    {
        // print_r($type);
        if(!empty($type)){
            $arr = $this->crud_global->ShowTableNew('regencies',$type);
        }else {
            $arr = $this->crud_global->ShowTableNew('regencies',false);
        }
        ?>
        <select class="form-control selectpicker selectajax" name="regencie_id" data-live-search="true" id="regencies" data-type="districts" data-id="regency_id" data-required="false">
            <option value="">.. Select Kota/Kabupaten ..</option>
            <?php
            if(!empty($id)){
                if(is_array($arr)){
                    foreach ($arr as $key => $row) {
                        $selected = false;
                        if(!empty($id)){
                            if($id == $row->id){
                                $selected = 'selected';
                            }
                        }
                        ?>
                        <option value="<?php echo $row->id;?>" <?php echo $selected;?>><?php echo $row->name;?></option>
                        <?php
                    }
                }
            }
            ?>
        </select>
        <?php
    }

    function SelectDistrict($id=false,$type=false)
    {
        if(!empty($type)){
            $arr = $this->crud_global->ShowTableNew('districts',$type);
        }else {
            $arr = $this->crud_global->ShowTableNew('districts',false);
        }
        ?>
        <select class="form-control selectpicker selectajax" name="district_id" data-live-search="true" id="districts" data-type="villages" data-id="district_id" data-required="false">
            <option value="">.. Select Kecamatan ..</option>
            <?php
            if(!empty($id)){
                if(is_array($arr)){
                    foreach ($arr as $key => $row) {
                        $selected = false;
                        if(!empty($id)){
                            if($id == $row->id){
                                $selected = 'selected';
                            }
                        }
                        ?>
                        <option value="<?php echo $row->id;?>" <?php echo $selected;?>><?php echo $row->name;?></option>
                        <?php
                    }
                }
            }
            ?>
        </select>
        <?php
    }

    function SelectVillage($id=false,$type=false)
    {
        if(!empty($type)){
            $arr = $this->crud_global->ShowTableNew('villages',$type);
        }else {
            $arr = $this->crud_global->ShowTableNew('villages',false);
        }
        ?>
        <select class="form-control selectpicker" name="village_id" data-live-search="true" id="villages" data-required="false">
            <option value="">.. Select Desa/Kelurahan ..</option>
            <?php
            if(!empty($id)){
                if(is_array($arr)){
                    foreach ($arr as $key => $row) {
                        $selected = false;
                        if(!empty($id)){
                            if($id == $row->id){
                                $selected = 'selected';
                            }
                        }
                        ?>
                        <option value="<?php echo $row->id;?>" <?php echo $selected;?>><?php echo $row->name;?></option>
                        <?php
                    }
                }
            }
            ?>
        </select>
        <?php
    }

    function GetLocation($table,$arraywhere)
    {
        $output = false;

        if(is_array($arraywhere)){
            $output =  $this->crud_global->GetField($table,$arraywhere,'name');
        }

        return $output;
    }

    function ShowSeller($author_type,$id)
    {
        $output = false;

        if($author_type == 1){
            $output = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$id),'admin_name').' (Admin)';
        }else {
            $output = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$id),'name').' (Member)';
        }

        return $output;
    }



    function GetMemberInfo($author_type,$id,$field)
    {
        $output = false;

        if($author_type == 1){
            if($field == 'no_anggota'){
                $output = "-";
            }else if($field == 'phone'){
                $field = 'admin_phone';
            }else {
                $output = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$id),$field);
                if(empty($output)){
                    $output = "-";
                }
            }
        }else {
            $output = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$id),$field);
            if(empty($output)){
                $output = "-";
            }
        }

        return $output;
    }
    
    

    function AddInvoice()
    {
        $output = false;

        $arr_cart = $this->cart->contents();

        if(is_array($arr_cart)){
            $member_id = $this->session->userdata('member_id');
            $datecreated = date("Y-m-d H:i:s");

            $bill_id = $this->crud_global->GetField('tbl_member_info',array('member_id'=>$member_id),'member_info_id');


            $author_old  = false;
            $u = false;

            foreach ($arr_cart as $a) {
                $author = $this->crud_global->GetField('tbl_product',array('product_id'=>$a['id']),'created_by');
                $author_type = $this->crud_global->GetField('tbl_product',array('product_id'=>$a['id']),'user_type');
                $u[$author] = $author_type;
            }
            $array_unique = array_unique($u);

            if (is_array($array_unique)) {
                foreach ($array_unique as $key => $value) {

                    $arrayvalues = array(
                        'billing_id'=>$bill_id,
                        'member_id'=>$member_id,
                        'payment_type_id' => 1,
                        'vendor_type' => $value,
                        'vendor_id' => $key,
                        'notes' => $this->input->post('notes'),
                        'status' => 1,
                        'datecreated'=>$datecreated
                    );

                    $query = $this->db->insert('tbl_invoice',$arrayvalues);

                    if($query){

                        $id = $this->db->insert_id();
                        $invoice_no = $this->general->InvoiceNo($id);

                        $subtotal = false;
                        foreach ($arr_cart as $row) {

                            $author = $this->crud_global->GetField('tbl_product',array('product_id'=>$row['id']),'created_by');

                            if($key == $author){
                                $total_price = $row['qty'] * $row['price'];
                                $arrayvalues_2 = array(
                                    'invoice_id'=>$id,
                                    'product_id' => $row['id'],
                                    'qty' => $row['qty']
                                );

                                $this->db->insert('tbl_invoice_product',$arrayvalues_2);

                                $subtotal = $subtotal + $total_price;
                                $this->crud_global->UpdateDefault('tbl_invoice',array('invoice_no'=>$invoice_no,'total'=>$subtotal),array('invoice_id'=>$id));
                            }
                        }

                        $output = true;

                    }else{
                        $output = false;
                    }
                }
            }
        }else {
            $output = false;
        }
        return $output;
    }


    function ShowPhoto($member_id)
    {
        $check = $this->crud_global->CheckNum('tbl_member',array('member_id'=>$member_id));
        if($check){
            $photo = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'photo');
            if(!empty($photo)){
                ?>
                <img src="<?php echo base_url().$photo;?>" class="img-responsive">
                <?php
            }else {
                ?>
                <img src="<?php echo base_url();?>assets/front/images/no-avatar.png" class="img-responsive">
                <?php
            }
        }else {
            ?>
            <img src="<?php echo base_url();?>assets/front/images/no-avatar.png" class="img-responsive">
            <?php
        }
    }


    function InvoiceProcess()
    {
        $output=array('output'=>'false');

        // Get data
        $id=$this->input->post('id');
        $arraywhere = array('invoice_id'=>$id);

        $arrayvalues = array(
            'status'=>2,
        );

        $query=$this->crud_global->UpdateDefault('tbl_invoice',$arrayvalues,$arraywhere);

        if($query){
            $arr_pro = $this->crud_global->ShowTableNew('tbl_invoice_product',array('invoice_id'=>$id));
            if(is_array($arr_pro)){
                foreach ($arr_pro as $key => $value) {
                    $a = $this->crud_global->GetField('tbl_product',array('product_id'=>$value->product_id),'quantity');
                    $b = $a - $value->qty;
                    $this->crud_global->UpdateDefault('tbl_product',array('quantity'=>$b),array('product_id'=>$value->product_id));
                }
            }
            $member_id = $this->crud_global->GetField('tbl_invoice',array('invoice_id'=>$id),'member_id');
            $member_email = $this->crud_global->GetField('tbl_member',array('member_id'=>$id),'email');
            $this->sendMailInvoice($member_email);
            $output=array('output'=>'true');
        }else {
            $output=array('output'=>'false');
        }
        echo json_encode($output);
    }


    function SendMessage()
    {
        $output = array('output'=>'false');

        $user_id = $this->input->post('user_id');
        $user_type = $this->input->post('user_type');
        $user_type_receive = $this->input->post('user_type_receive');
        $problem_type = $this->input->post('problem_type');
        $problem_id = $this->input->post('problem_id');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');
        $parent_id = $this->input->post('parent_id');
        $ip = $this->input->ip_address();
        $datecreated = date("Y-m-d H:i:s");

        $arrayvalues = array(
            'user_id'=>$user_id,
            'user_type'=>$user_type,
            'problem_type'=>$problem_type,
            'problem_id'=>$problem_id,
            'subject'=>$subject,
            'message'=>$message,
            'ip'=>$ip,
            'parent_id'=>$parent_id,
            'datecreated'=>$datecreated
        );

        $query=$this->db->insert('tbl_message',$arrayvalues);
        if($query){

            $message_id = $this->db->insert_id();
            $message_receive = $this->input->post('message_receive');

            $arrayvalues_2 = array(
                'message_id'=>$message_id,
                'user_type'=>$user_type_receive,
                'user_id'=>$message_receive,
                'is_read'=>1,
                'is_reply'=>1,
            );

            $query_2 =$this->db->insert('tbl_message_receive',$arrayvalues_2);

            if($query_2){
                $reply = $this->input->post('reply');
                if(!empty($reply)){
                    $this->crud_global->UpdateDefault('tbl_message_receive',array('is_reply'=>2),array('message_id'=>$parent_id));
                }
                $output = array('output'=>'true');
            }else {
                $output = array('output'=>'false'); 
            }
        }else {
            $output = array('output'=>'false');
        }

        echo json_encode($output);
    }

    function ConfigEmail()
    {
        $config = Array(
            'protocol' =>'smtp',
            'smtp_host'=>'ssl://smtp.googlemail.com',
            'smtp_port'=> 465,
            'smtp_user'     => 'yusuf.hamdadi@bubu.com', // change it to yours
            'smtp_pass'     => 'yusuf12321', // change it to yours
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1',
            'wordwrap'  => TRUE
        );
        
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
         // change it to yours
        // $this->email->bcc('yusuf@pensilwarna.com');// change it to yours
    }


    function sendMailContact($to)
    {
        
        // $this->ConfigEmail();
        $email_config = Array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => '465',
            'smtp_user' => 'yusuf.hamdadi@bubu.com',
            'smtp_pass' => 'yusuf12321',
            'mailtype'  => 'html',
            'starttls'  => true,
            'newline'   => "\r\n"
        );

        $this->load->library('email', $email_config);
        $this->email->from('info@cintalogy.com','info@cintalogy.com');
        $this->email->to($to);// change it to yours
        // $this->email->cc('hamdadiyusuf@gmail.com');
        $this->email->subject('Cintalogy Contact');
        
        $message = '<h2>Thank you for contacting Us</h2>';
        $message .= '<h3>Your Message can we proccess as sooon as possible</h3>';

        $this->email->message($message);
        $this->email->set_mailtype("html");
        if($this->email->send())
        {
            // echo 'Email sent to '.$email. ' from ';
            return true;
        }
        else
        {
            show_error($this->email->print_debugger());
        }
    }

    function sendMailContactAdmin($name,$email,$phone,$data)
    {
        
        $this->ConfigEmail();
        $this->email->to($this->m_themes->GetThemes('site_email'));// change it to yours
        // $this->email->cc('hamdadiyusuf@gmail.com');
        $this->email->subject('Cintalogy Contact');
        
        $message = '<h3>Data Contact</h3>';
        $message .= '<table width="100%" border="0">';
        $message .= '<tr>';
        $message .= '<td>Name </td>';
        $message .= '<td width="50px">:</td>';
        $message .= '<td>'.$name.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Email </td>';
        $message .= '<td width="50px">:</td>';
        $message .= '<td>'.$email.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Phone </td>';
        $message .= '<td width="50px">:</td>';
        $message .= '<td>'.$phone.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Message </td>';
        $message .= '<td width="50px">:</td>';
        $message .= '<td>'.$data.'</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $this->email->message($message);
        $this->email->set_mailtype("html");
        if($this->email->send())
        {
            // echo 'Email sent to '.$email. ' from ';
            return true;
        }
        else
        {
            show_error($this->email->print_debugger());
        }
    }

    function sendMailSubs($to)
    {
        
        $this->ConfigEmail();
        $this->email->to($to);// change it to yours
        $this->email->cc('hamdadiyusuf@gmail.com');
        $this->email->subject('WAMTI Subsrcibe');
        
        $message = '<h2>Thank you for Subsrcibe</h2>';

        $this->email->message($message);
        $this->email->set_mailtype("html");
        if($this->email->send())
        {
            // echo 'Email sent to '.$email. ' from ';
            return true;
        }
        else
        {
            show_error($this->email->print_debugger());
        }
    }

    function sendMailSubsAdmin($email,$ip,$date)
    {
        
        $this->ConfigEmail();
        $this->email->to($this->m_themes->GetThemes('site_email'));// change it to yours
        $this->email->subject('WAMTI Subsrcibe');
        
        $message = '<h3>Data Contact Us</h3>';
        $message .= '<table width="100%" border="0">';
        $message .= '<tr>';
        $message .= '<td>Email </td>';
        $message .= '<td width="50px">:</td>';
        $message .= '<td>'.$email.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Ip </td>';
        $message .= '<td width="50px">:</td>';
        $message .= '<td>'.$ip.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Date </td>';
        $message .= '<td width="50px">:</td>';
        $message .= '<td>'.$date.'</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $this->email->message($message);
        $this->email->set_mailtype("html");
        if($this->email->send())
        {
            // echo 'Email sent to '.$email. ' from ';
            return true;
        }
        else
        {
            show_error($this->email->print_debugger());
        }
    }

    function sendMailInvoice($to)
    {
        
        $this->ConfigEmail();
        $this->email->to($to);// change it to yours
        $this->email->bcc('info@wamti.org');
        $this->email->bcc('hamdadiyusuf@gmail.com');
        $this->email->subject('WAMTI Subsrcibe');
        
        $message = '<h2>Pesanan anda sudah di process</h2>';

        $this->email->message($message);
        $this->email->set_mailtype("html");
        if($this->email->send())
        {
            // echo 'Email sent to '.$email. ' from ';
            return true;
        }
        else
        {
            show_error($this->email->print_debugger());
        }
    }

   
}
