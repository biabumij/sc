<?php

class Pmm_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('crud_global');
    }


    function GetMeasures($id=false)
    {

        $output = false;
        $query = $this->db->get_where('pmm_measures',array('status !='=>'DELETED'));
        if($query->num_rows() > 0){
            $output = $query->result_array();
        }
        return $output;
    }

    function getProducts()
    {
        $output = false;
        $this->db->order_by('product','asc');
        $query = $this->db->get_where('pmm_product',array('status'=>'PUBLISH'));
        if($query->num_rows() > 0){
            $output = $query->result_array();
        }
        return $output;
    }


    function GetNoSPO()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_schedule');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%04d', $id).'/SPO/'.$code_prefix['code_prefix'].'/'.date('m').'/'.date('Y');
        return $output;
            
    }

    function GetNoProduksiJadi()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('produksi_jadi');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%04d', $id).'/PBJ/'.$code_prefix['code_prefix'].'/'.date('m').'/'.date('Y');
        return $output;
            
    }
	
	function GetNoKalibrasi()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_kalibrasi');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%03d', $id).'/Kal-Prod/BBJ/'.date('m').'/'.date('Y');
        return $output;
            
    }
	
	function GetNoAgregat()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_agregat');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%03d', $id).'/Agregat-Prod/BBJ/'.date('m').'/'.date('Y');
        return $output;
            
    }
	
	function GetNoProd()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_produksi_harian');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%04d', $id).'/PD/'.$code_prefix['code_prefix'].'/'.date('m').'/'.date('Y');
        return $output;
            
    }
	
	function GetNoProdCampuran()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_produksi_campuran');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%04d', $id).'/PD/'.$code_prefix['code_prefix'].'/'.date('m').'/'.date('Y');
        return $output;
            
    }
	
	function GetNoKomposisi()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_jmd');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%03d', $id).'/Lab-JMD/'.date('m').'/'.date('Y');
        return $output;
            
    }
	
	function GetNoRap()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_rap');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%03d', $id).'/RAP/'.date('m').'/'.date('Y');
        return $output;
            
    }

    function GetNoPenawaranPembelian()
    {
        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_penawaran_pembelian');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%04d', $id).'/SPO/'.$code_prefix['code_prefix'].'/'.date('m').'/'.date('Y');
        return $output;
            
    }

    function GetNoRM($date)
    {
        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;


        $query = $this->db->select('request_no')->order_by('request_no','desc')->get_where('pmm_request_materials',array('status !='=>'DELETED'));
        if($query->num_rows() > 0){
            $row = $query->row_array();
            $a = explode('/', $row['request_no']);
            $id = $a[0] + 1;
        }else {
            $id = 1;
        }
        $date = explode('-', $date);
        $output = sprintf('%04d', $id).'/RM/'.$code_prefix['code_prefix'].'/'.$date[1].'/'.$date[0];
        return $output;
            
    }

    function GetNoRF()
    {
        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_request_funds');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf('%04d', $id).'/RF/'.$code_prefix['code_prefix'].'/'.date('m').'/'.date('Y');
        return $output;
            
    }

    function ProductionsNo($date=false)
    {
        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('pmm_productions');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }

        $month = date('m');
        $year = date('Y');
        if(!empty($date)){
            $date = explode('-', $date);
            $month = $date[1];
            $year = $date[0];
        }
        

        $output = sprintf('%04d', $id).'/SJ/'.$code_prefix['code_prefix'].'/'.$month.'/'.$year;
        return $output;
            
    }
    


    function GetNoPO($no)
    {
        $output = false;
        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();

        $arr = explode('/', $no);
        $output = $arr[0].'/PO/'.$code_prefix['code_prefix'].'/'.$arr[3].'/'.$arr[4];
        return $output;
            
    }



    function ConvertDateSchedule($date)
    {
        $output = false;

        $arr_date = explode(' - ', $date);
        if(is_array($arr_date)){
            $output = date('d-m-Y',strtotime($arr_date[0])).' - '.date('d-m-Y',strtotime($arr_date[1]));
        }
        return $output;
    }

    function GetProduct($select)
    {
        $output = false;
        $query = $this->db->select($select)->order_by('product','asc')->get_where('pmm_product',array('status'=>'PUBLISH'));
        if($query->num_rows() > 0){
            $output = $query->result_array();
        }
        return $output; 
    }

    function GetProduct2()
    {
        $output = false;

        $this->db->select('p.id, p.nama_produk, kp.nama_kategori_produk');
        $this->db->join('kategori_produk kp','p.kategori_produk = kp.id','left');
        $query = $this->db->order_by('p.nama_produk','asc')->get_where('produk p',array('p.status'=>'PUBLISH'));
        if($query->num_rows() > 0){
            $output = $query->result_array();
        }
        return $output; 
    }
    
    function GetScheduleProductDetail($id)
    {
        $output = false;

        $data = array();
        $this->db->select('week');
        $this->db->group_by('week');
        $this->db->order_by('week','asc');
        $this->db->where('schedule_product_id',$id);
        $query = $this->db->get('pmm_schedule_product_date');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $w) {

                $query_date = $this->db->select('date,koef,id')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$id,'week'=>$w['week']))->result_array();
                $data_detail = array();
                $total = 0;
                foreach ($query_date as $key_d => $d) {
                    $d['date_txt'] = date('d M Y',strtotime($d['date']));
                    $data_detail[] = $d;
                    $total += $d['koef'];
                }
                $data[] = array(
                    'week' => $w['week'],
                    'data' => $data_detail,
                    'total' => $total
                );
            }
        }
        $output = $data;
        return $output;
    }

    function GetTotalKoefByWeek($id,$week,$material_id)
    {
        $total = 0;
        $this->db->select('psp.id,psm.koef,psp.product_id');
        $this->db->join('pmm_schedule_material psm','psp.id = psm.schedule_product_id','left');
        $this->db->where('psp.schedule_id',$id);
        $this->db->where('psm.material_id',$material_id);
        $this->db->group_by('psp.product_id');
        $data = $this->db->get('pmm_schedule_product psp')->result_array();
        foreach ($data as $key => $value) {
            $query = $this->db->select('SUM(koef) as total')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$value['id'],'week'=>$week,'product_id'=>$value['product_id']))->row_array();
            $total += $query['total'] * $value['koef']; 
        }

        return $total;  
    }

    function GetTotalKoefByWeek_2($id,$product_id,$week)
    {
        $total = 0;
        $data = $this->db->select('id')->get_where('pmm_schedule_product',array('schedule_id'=>$id))->result_array();
        foreach ($data as $key => $value) {
            $query= $this->db->select('SUM(koef) as total')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$value['id'],'product_id'=>$product_id,'week'=>$week))->row_array();
            $total += $query['total'];     
        }
           

        return $total;  
    }

    function GetTotalKoefByWeekProduct($schedule_id,$product_id,$week)
    {   
        $total = 0;
        $this->db->select('id');
        $this->db->where('schedule_id',$schedule_id);
        $this->db->where('product_id',$product_id);
        $get_data = $this->db->get('pmm_schedule_product');
        if($get_data->num_rows() > 0){
            foreach ($get_data->result_array() as $key => $value) {
                $query = $this->db->select('SUM(koef) as total')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$value['id'],'week'=>$week))->row_array();
                $total += $query['total'];
            }
            
        }

        return $total;  
    }


    function GetScheduleProductMaterials($id)
    {
        $output = false;

        $data = array();
        $this->db->select('ps.koef, pm.material_name,pm.measure,pms.measure_name, psp.product_id,psp.schedule_id');
        $this->db->order_by('material_id','asc');
        $this->db->where('schedule_product_id',$id);
        $this->db->join('pmm_schedule_product psp','ps.schedule_product_id = psp.id','left');
        $this->db->join('pmm_materials pm','ps.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','pm.measure = pms.id','left');
        $query = $this->db->get('pmm_schedule_material ps');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $w) {

                $week_1 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],1) * $w['koef'];
                $week_2 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],2) * $w['koef'];
                $week_3 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],3) * $w['koef'];
                $week_4 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],4) * $w['koef'];

                $total = $week_1 + $week_2 + $week_3 + $week_4;
                $data[] = array(
                    'material_name' => $w['material_name'],
                    'measure' => $w['measure_name'],
                    'koef' => $w['koef'],
                    'week_1' => number_format($week_1,2,',','.'),
                    'week_2' => number_format($week_2,2,',','.'),
                    'week_3' => number_format($week_3,2,',','.'),
                    'week_4' => number_format($week_4,2,',','.'),
                    'total' => number_format($total,2,',','.'),
                    'subtotal' => false
                );
            }
        }
        $output = $data;
        return $output;
    }

    function GetTotalScheduleProductMaterials($schedule_id,$product_id)
    {
        $output = false;

        $data = array();
        $this->db->select('id');
        $this->db->where('schedule_id',$schedule_id);
        $this->db->where('product_id',$product_id);
        $get_data = $this->db->get('pmm_schedule_product')->row_array();

        $id = $get_data['id'];
        $this->db->select('ps.koef, pm.material_name,pm.measure,pms.measure_name');
        $this->db->order_by('material_id','asc');
        $this->db->where('schedule_product_id',$id);
        $this->db->join('pmm_materials pm','ps.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','pm.measure = pms.id','left');
        $query = $this->db->get('pmm_schedule_material ps');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $w) {

                $week_1 = $this->GetTotalKoefByWeekProduct($schedule_id,$product_id,1) * $w['koef'];
                $week_2 = $this->GetTotalKoefByWeekProduct($schedule_id,$product_id,2) * $w['koef'];
                $week_3 = $this->GetTotalKoefByWeekProduct($schedule_id,$product_id,3) * $w['koef'];
                $week_4 = $this->GetTotalKoefByWeekProduct($schedule_id,$product_id,4) * $w['koef'];

                $total = $week_1 + $week_2 + $week_3 + $week_4;
                $data[] = array(
                    'material_name' => $w['material_name'].' ('.$w['measure_name'].')',
                    'koef' => $w['koef'],
                    'week_1' => '<b>'.$week_1.'</b>',
                    'week_2' => '<b>'.$week_2.'</b>',
                    'week_3' => '<b>'.$week_3.'</b>',
                    'week_4' => '<b>'.$week_4.'</b>',
                    'total' => '<b>'.$total.'</b>',
                    'subtotal' => true
                );
            }
        }
        $output = $data;
        return $output;
    }

    function GetTotalMaterials($schedule_id,$week,$material_id)
    {
        $total = 0;
        $this->db->select('SUM(pspd.koef) as total,psp.product_id');
        $this->db->join('pmm_schedule_product psp','pspd.schedule_product_id = psp.id','left');
        $this->db->where('psp.schedule_id',$schedule_id);
        $this->db->where('pspd.week',$week);
        $this->db->group_by('psp.product_id');
        $get_data = $this->db->get('pmm_schedule_product_date pspd')->result_array();
        foreach ($get_data as $key => $w) {
            $koef = $this->GetMatByPro($schedule_id,$material_id,$w['product_id']);
            $subtotal = $w['total'] * $koef;
            $total += $subtotal;
        }
        return $total;
    }

    function GetMatByPro($schedule_id,$material_id,$product_id)
    {
        $this->db->select('psm.koef');
        $this->db->join('pmm_schedule_product psp','psm.schedule_product_id = psp.id','left');
        $this->db->where('psp.schedule_id',$schedule_id);
        $this->db->where('psm.material_id',$material_id);
        $this->db->where('psm.product_id',$product_id);
        $a = $this->db->get('pmm_schedule_material psm')->row_array();
        return $a['koef'];
    }

    function GetScheduleProductKoef($id)
    {
        $output = false;

        $data = array();
        $this->db->select('ps.koef, pm.material_name,pm.measure,pms.measure_name,psp.product_id, psp.schedule_id');
        $this->db->order_by('material_id','asc');
        $this->db->where('schedule_product_id',$id);
         $this->db->join('pmm_schedule_product psp','ps.schedule_product_id = psp.id','left');
        $this->db->join('pmm_materials pm','ps.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','pm.measure = pms.id','left');
        $query = $this->db->get('pmm_schedule_material ps');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $w) {

                $week_1 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],1) * $w['koef'];
                $week_2 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],2) * $w['koef'];
                $week_3 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],3) * $w['koef'];
                $week_4 = $this->GetTotalKoefByWeek_2($w['schedule_id'],$w['product_id'],4) * $w['koef'];


                $total = $week_1 + $week_2 + $week_3 + $week_4;
                $data[] = array(
                    'material_name' => $w['material_name'].' ('.$w['measure_name'].')',
                    'koef' => $w['koef'],
                    'week_1' => $week_1,
                    'week_2' => $week_2,
                    'week_3' => $week_3,
                    'week_4' => $week_4,
                    'total' => $total
                );
            }
        }
        $output = $data;
        return $output;
    }

    
    function GetScheduleProduct($id)
    {
        $output = false;

        $data = array();
        $this->db->select('psp.id,psp.product_id,psp.schedule_id,psp.activity,pp.nama_produk as product');
        $this->db->where('schedule_id',$id);
        $this->db->join('produk pp','psp.product_id = pp.id','left');
        $this->db->group_by('product_id');
        $this->db->order_by('pp.nama_produk','asc');
        $query = $this->db->get('pmm_schedule_product psp');
        if($query->num_rows() > 0){
            $a = 0;
            foreach ($query->result_array() as $key => $w) {
                $get_count_p = $this->db->get_where('pmm_schedule_product',array('product_id'=>$w['product_id'],'schedule_id'=>$id))->num_rows();
                $w1 = $this->GetTotalKoefByProduct($id,$w['product_id'],1);
                $w2 = $this->GetTotalKoefByProduct($id,$w['product_id'],2);
                $w3 = $this->GetTotalKoefByProduct($id,$w['product_id'],3);
                $w4 = $this->GetTotalKoefByProduct($id,$w['product_id'],4);
                $total_kn = $w1 + $w2 + $w3 + $w4;

                $w['week_1'] =$w1;
                $w['week_2'] =$w2;
                $w['week_3'] =$w3;
                $w['week_4'] =$w4;
                $w['total'] = $total_kn;

                $data[] = $w;
                
            }
        }
        $output = $data;
        return $output;
    }

    function GetPriceCostScheduleDetail($schedule_product_id,$material_id,$week,$type,$schedule_id=false)
    {
        $total = 0;

        $this->db->select('psp.id,psm.koef,psp.product_id,psm.cost,psm.price');
        $this->db->join('pmm_schedule_material psm','psp.id = psm.schedule_product_id','left');
        $this->db->where('psp.schedule_id',$schedule_id);
        $this->db->where('psm.material_id',$material_id);
        $this->db->group_by('psp.product_id');
        $data = $this->db->get('pmm_schedule_product psp')->result_array();
        foreach ($data as $key => $value) {
            $query = $this->db->select('SUM(koef) as total')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$value['id'],'week'=>$week,'product_id'=>$value['product_id']))->row_array();
            if($type == 1){
                $total += ($query['total'] * $value['koef']) * $value['price']; 
            }else {
                $total += ($query['total'] * $value['koef']) * $value['cost']; 
            }
            
        }


        return $total;
    }

    function GetScheduleDetail($id)
    {
        $output = false;

        $data = array();
        $this->db->select('pm.material_name,psm.koef,pms.measure_name,psm.schedule_product_id,psm.material_id,ps.schedule_date,psp.product_id');
        $this->db->join('pmm_schedule_product psp','psm.schedule_product_id = psp.id','left');
        $this->db->join('pmm_materials pm','psm.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','pm.measure = pms.id','left');
        $this->db->join('pmm_schedule ps','psp.schedule_id = ps.id','left');
        $this->db->where('psp.schedule_id',$id);
        $this->db->order_by('pm.material_name','asc');
        $this->db->group_by('psm.material_id');
        $query = $this->db->get('pmm_schedule_material psm');
        if($query->num_rows() > 0){
            $a = 0;
            foreach ($query->result_array() as $key => $w) {

                $week_1 = $this->GetTotalKoefByWeek($id,1,$w['material_id']);
                $week_2 = $this->GetTotalKoefByWeek($id,2,$w['material_id']);
                $week_3 = $this->GetTotalKoefByWeek($id,3,$w['material_id']);
                $week_4 = $this->GetTotalKoefByWeek($id,4,$w['material_id']);

                $week_1_price = $this->GetPriceCostScheduleDetail($w['schedule_product_id'],$w['material_id'],1,1,$id);
                $week_2_price = $this->GetPriceCostScheduleDetail($w['schedule_product_id'],$w['material_id'],2,1,$id);
                $week_3_price = $this->GetPriceCostScheduleDetail($w['schedule_product_id'],$w['material_id'],3,1,$id);
                $week_4_price = $this->GetPriceCostScheduleDetail($w['schedule_product_id'],$w['material_id'],4,1,$id);

                $total = $week_1 + $week_2 + $week_3 + $week_4;
                $total_price = $week_1_price + $week_2_price + $week_3_price + $week_4_price;
                
                $butuh = $total;
                $butuh_price = $total_price;
                $data[] = array(
                    'material_name' => $w['material_name'],
                    'measure' => $w['measure_name'],
                    'week_1' => number_format($week_1,2,',','.'),
                    'week_2' => number_format($week_2,2,',','.'),
                    'week_3' => number_format($week_3,2,',','.'),
                    'week_4' => number_format($week_4,2,',','.'),
                    'week_1_price' => number_format($week_1_price,2,',','.'),
                    'week_2_price' => number_format($week_2_price,2,',','.'),
                    'week_3_price' => number_format($week_3_price,2,',','.'),
                    'week_4_price' => number_format($week_4_price,2,',','.'),
                    'butuh' => number_format($butuh,2,',','.'),
                    'total' => number_format($total,2,',','.'),
                    'sisa' => number_format(0,2,',','.'),
                    'butuh_price' => number_format($butuh_price,2,',','.'),
                    'total_price' => number_format($total_price,2,',','.'),
                );
            }
        }
        $output = $data;
        return $output;
    }

    function GetTotalKoefByProduct($schedule_id,$product_id,$week)
    {
        $output = false;

        $this->db->select('id');
        $this->db->where('schedule_id',$schedule_id);
        $this->db->where('product_id',$product_id);
        $query = $this->db->get('pmm_schedule_product');
        if($query->num_rows() > 0){
            $total = 0;
            foreach ($query->result_array() as $key => $value) {
                $get_total = $this->db->select('SUM(koef) as total')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$value['id'],'week'=>$week))->row_array();
                $total += $get_total['total'];

            }
            $output = $total;
        }
        return $output;
    }

    function GetTotalMatByProduct($schedule_id,$product_id,$week)
    {
        $output = false;

        $this->db->select('id');
        $this->db->where('schedule_id',$schedule_id);
        $this->db->where('product_id',$product_id);
        $query = $this->db->get('pmm_schedule_product');
        if($query->num_rows() > 0){
            $total = 0;
            foreach ($query->result_array() as $key => $value) {
                $get_total = $this->db->select('SUM(koef) as total')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$value['id'],'week'=>$week))->row_array();
                $total += $get_total['total'];

            }
            $output = $total;
        }
        return $output;
    }


    function GetArrTotalMaterials($schedule_id)
    {
        $output = array();

        $this->db->select('psm.material_id,pm.material_name,pms.measure_name');
        $this->db->join('pmm_schedule_product pmm','psm.schedule_product_id = pmm.id','left');
        $this->db->join('pmm_materials pm','psm.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','pm.measure = pms.id','left');
        $this->db->where('pmm.schedule_id',$schedule_id);
        $this->db->group_by('psm.material_id');
        $query = $this->db->get('pmm_schedule_material psm');
        if($query->num_rows() > 0){
 
            $data = array();
            foreach (array_unique($query->result_array(),SORT_REGULAR) as $key => $value) {
                
                $arr['material_name'] = $value['material_name'].' '.$value['measure_name'];
                $arr['material_id'] = $value['material_id'];
                $data[] = $arr;
            }

            $output = $data;
        }
        return $output; 
    }
    


    function GetStatus($status)
    {
        if($status == 'WAITING'){
            return '<label class="label label-warning">'.$status.'</label>';
        }else if($status == 'APPROVED'){
            return '<label class="label label-success">'.$status.'</label>';
        }else if($status == 'REJECTED'){
            return '<label class="label label-danger">'.$status.'</label>';
        }else if($status == 'CLOSED'){
            return '<label class="label label-danger">'.$status.'</label>';
        }else {
            return '<label class="label label-primary">'.$status.'</label>';
        }
    }

	function GetStatus2($status)
    {
        if($status == 'DRAFT'){
            return '<label class="label label-warning">'.$status.'</label>';
        }else if($status == 'OPEN'){
            return '<label class="label label-primary">'.$status.'</label>';
        }else if($status == 'REJECT'){
            return '<label class="label label-danger">'.$status.'</label>';
        }else if($status == 'CLOSED'){
            return '<label class="label label-danger">'.$status.'</label>';
        }else if($status == 'DELETED'){
            return '<label class="label label-danger">'.$status.'</label>';
        }
		
    }
	
	function GetStatus3($status)
    {
        if($status == 'BELUM LUNAS'){
            return '<label class="label label-danger">'.$status.'</label>';
        }else if($status == 'LUNAS'){
            return '<label class="label label-success">'.$status.'</label>';
        }
    }
	function GetStatus4($status)
    {
        if($status == 'PUBLISH'){
            return '<label class="label label-primary">'.$status.'</label>';
        }else if($status == 'CLOSED'){
            return '<label class="label label-danger">'.$status.'</label>';
        }
    }
	
	function StatusPayment($status)
    {
        if($status == 'CREATING'){
            $output = '<label class="label label-success">'.$status.'</label>';
        }else if($status == 'CREATED'){
            $output = '<label class="label label-primary">'.$status.'</label>';
        }else if($status == 'UNCREATED'){
            $output = '<label class="label label-danger">'.$status.'</label>';
        }else if($status == 'UNPUBLISH'){
            $output = '<label class="label label-danger">'.$status.'</label>';
        }

        return $output;
    }

    function SelectScheduleProduct($schedule_id)
    {
        $output = false;

        $data = array();
        $data = $this->db->select('id as product_id, product')->order_by('product','asc')->get('pmm_product')->result_array();
        $output = $data;
        return $output;
    }   


    function SelectMatByProd($schedule_id,$product_id)
    {
        $composition_id = $this->crud_global->GetField('pmm_product',array('id'=>$product_id),'composition_id');

        $this->db->select('pcd.koef,pcd.material_id,pm.material_name,pms.measure_name');
        $this->db->join('pmm_materials pm','pcd.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','pm.measure = pms.id','left');
        $this->db->where('composition_id',$composition_id);
        $query = $this->db->get('pmm_composition_detail pcd')->result_array();
        return $query;
    }


    function CreatePO($id)
    {
        $output = false;

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well 


        $arr_rm = $this->db->select('supplier_id,subject,memo,kategori_id')->get_where('pmm_request_materials',array('id'=>$id))->row_array();

        $dt = $this->db->get_where('pmm_request_materials',array('id'=>$id))->row_array();
        $data = array(
            'request_material_id' => $id,
            'no_po' => $this->pmm_model->GetNoPO($dt['request_no']),
            'date_po' => $dt['request_date'],
            'supplier_id' => $arr_rm['supplier_id'],
			'memo' => $arr_rm['memo'],
            'kategori_id' => $arr_rm['kategori_id'],
            'subject' => $arr_rm['subject'],
            'created_by' => $this->session->userdata('admin_id'),
            'created_on' => date('Y-m-d H:i:s'),
            'status' => 'WAITING'
        );

        if($this->db->insert('pmm_purchase_order',$data)){
            $po_id = $this->db->insert_id();

            $get_data = $this->db->select('*')->get_where('pmm_request_material_details',array('request_material_id'=>$id))->result_array();
            $total = 0;
            foreach ($get_data as $key => $row) {
                $measure = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_id']),'measure_name');
                $arr = array(
                    'purchase_order_id' => $po_id,
                    'material_id' => $row['material_id'],
                    'volume'    => $row['volume'],
                    'price' => $row['price'],
                    'measure' => $measure,
					'penawaran_id' => $row['penawaran_id'],
					'tax_id' => $row['tax_id'],
					'tax' => $row['tax']
                );
                $total +=  $row['price'] * $row['volume'];
                $this->db->insert('pmm_purchase_order_detail',$arr);
            }

            $this->db->update('pmm_purchase_order',array('total'=>$total),array('id'=>$po_id));
            

        }

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $output = false;
        } 
        else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();
            $output = true;
        }

        return $output;
    }


    function GetPODetail($id)
    {
        $output = false;
        $this->db->select('pp.*,SUM(pp.volume) as total, pp.id');
        $this->db->where('pp.purchase_order_id',$id);
        $this->db->group_by('pp.material_id');
        $query = $this->db->get('pmm_purchase_order_detail pp')->result_array();

        return $query;
    }

    function GetReceiptByPO($id)
    {
        $output = false;
        $this->db->select('SUM(prm.volume) as volume, SUM(prm.volume * prm.price) as total,prm.measure, pm.nama_produk as material_name,prm.material_id');
        $this->db->join('produk pm','prm.material_id = pm.id','left');
        $this->db->where('prm.purchase_order_id',$id);
        $this->db->group_by('prm.material_id');
        $query = $this->db->get('pmm_receipt_material prm')->result_array();

        return $query;
    }

    function GetReceiptBySalesOrder($id)
    {
        $output = false;
        $this->db->select('SUM(pp.volume) as volume, SUM(pp.volume * pp.harga_satuan) as total, pp.measure, pm.nama_produk as material_name, pp.product_id');
        $this->db->join('produk pm','pp.product_id = pm.id','left');
        $this->db->where('pp.salesPo_id',$id);
        $this->db->group_by('pp.product_id');
        $query = $this->db->get('pmm_productions pp')->result_array();

        return $query;
    }


    function GetRielPrice($product_id)
    {
        $output = 0;
        
        $total_comp = 0;
        

        $total_tools = 0;
        $tools = $this->db->select('koef,type_id')->get_where('pmm_product_detail',array('product_id'=>$product_id,'status'=>'PUBLISH'))->result_array();
        if(!empty($tools)){
            $data_tools = array();
            foreach ($tools as $key => $to) {
                $price = $this->db->select('SUM(cost) as total_tools')->get_where('pmm_tool_detail',array('tool_id'=>$to['type_id'],'status'=>'PUBLISH'))->row_array();

                $total_tools += $to['koef'] * $price['total_tools'];
            }
        }

        $total_others = 0;
        $others = $this->db->select('SUM(price) as total')->get_where('pmm_product_price',array('product_id'=>$product_id))->row_array();        
        if(!empty($others)){
            $total_others = $others['total'];
        }

        $output = $total_comp + $total_tools + $total_others;
        return $output;
    }

    function GetRielPriceDetail($product_id)
    {
        $output = array();
        $tools = $this->db->select('koef,type_id')->get_where('pmm_product_detail',array('product_id'=>$product_id,'status'=>'PUBLISH'))->result_array();
        if(!empty($tools)){
            $data_tools = array();
            foreach ($tools as $key => $to) {
                $tool_name = $this->crud_global->GetField('pmm_tools',array('id'=>$to['type_id']),'tool');
                $price = $this->db->select('SUM(cost) as total_tools')->get_where('pmm_tool_detail',array('tool_id'=>$to['type_id'],'status'=>'PUBLISH'))->row_array();

                $to['price'] = number_format($price['total_tools'],1,',','.');
                $to['tool_name'] = $tool_name;
                $to['total'] = number_format($to['koef'] * $price['total_tools'],2,',','.');
                $to['total_val'] =$to['koef'] * $price['total_tools'];
                $data_tools[] = $to;
            }
            $output['tools'] = $data_tools;
        }

        $other_price = $this->db->select('id,product_id,name,price')->get_where('pmm_product_price',array('product_id'=>$product_id))->result_array();
        $output['others'] = $other_price;
        return $output;
    }


    function TableDetailRequestMaterials($request_material_id)
    {
        $data = array();
        $this->db->select('psm.*,pm.nama_produk as material_name,pms.measure_name');
        $this->db->where('request_material_id',$request_material_id);
        $this->db->join('produk pm','psm.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','psm.measure_id = pms.id','left');
        $this->db->order_by('created_on','asc');
        $query = $this->db->get('pmm_request_material_details psm');
	
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['total'] = $row['volume'] * $row['price'];
                $row['volume']= number_format($row['volume'],2,',','.');
				$row['price']= number_format($row['price'],0,',','.');
                $row['material_name'] = $row['material_name'];
                $row['measure'] = $row['measure_name'];
                $get_status = $this->crud_global->GetField('pmm_request_materials',array('id'=>$row['request_material_id']),'status');
                if($get_status == 'DRAFT'){
                    $row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
                }else {
                    $row['actions'] = '-';
                }
                
                $data[] = $row;
            }

        }
        
        return $data;   
    }
    


    function GetPOMaterials($supplier_id,$id=false)
    {
        $data = array();
        $this->db->select('pm.nama_produk as material_name,pod.material_id,pod.measure, pod.volume,po.date_po, pm.satuan as display_measure, pod.tax as tax, pod.tax_id as tax_id');
        if(!empty($supplier_id)){
            $this->db->where('po.supplier_id',$supplier_id);
        }
        if(!empty($id)){
            $this->db->where('po.id',$id);
        }
        $this->db->where('po.status','PUBLISH');
        $this->db->join('produk pm','pod.material_id = pm.id','left');
        $this->db->join('pmm_purchase_order po','pod.purchase_order_id = po.id','left');
        $this->db->group_by('pod.material_id');
        $this->db->order_by('pm.nama_produk','asc');
        $query = $this->db->get('pmm_purchase_order_detail pod');
      
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $data[] = $row;
            }

        }
        
        return $data;   
    }


   function GetContractPrice($price)
   {
        $output = $price;

        $get_ov = $this->crud_global->GetField('pmm_setting_production',array('id'=>1),'overhead');
        if(!empty($get_ov)){
            $ov = ($get_ov * $price) / 100;
            $output = $price + $ov;
        }
        return $output;

   }

   function GetPriceProductions($sales_po_id,$product,$volume)
   {
        $output = 0;

        $contract_price = $this->crud_global->GetField('pmm_sales_po_detail',array('sales_po_id'=>$sales_po_id,'product_id'=>$product),'price');
     
        $output = $this->GetContractPrice($contract_price) * $volume;
		

        return $output;

   }

   function GetCostProductions($product,$volume)
   {
        $output = 0;

        $output = $this->GetRielPrice($product) * $volume;
        return $output;

   }
   

   function TotalSPOWeek($id,$week)
   {
        $output = 0;

        $this->db->select('SUM(psp.koef) as total,psp.status');
        $this->db->join('pmm_schedule_product ps','psp.schedule_product_id = ps.id','left');
        $this->db->where('psp.week',$week);
        $this->db->where('ps.schedule_id',$id);
        $arr = $this->db->get('pmm_schedule_product_date psp')->row_array();
        if(!empty($arr)){
            if($arr['status'] == 'WAITING'){
                $output = '<button class="btn btn-warning btn-sm" onclick="EditWeek('.$id.','.$week.')" >'.$arr['total'].'</button>';
            }else if($arr['status'] == 'PUBLISH'){
                $output = '<button class="btn btn-success btn-sm" onclick="EditWeek('.$id.','.$week.')" >'.$arr['total'].'</button>';
            }else {
                $output = '<button class="btn btn-info btn-sm" onclick="EditWeek('.$id.','.$week.')" >'.$arr['total'].'</button>';
            }
        }
        
        

        return $output;
   }


   function GetSPOByWeek($schedule_id,$week)
   {
    $output = false;

    $data = array();
    $this->db->select('psp.id,psp.product_id,psp.activity,pp.nama_produk as product');
    $this->db->join('produk pp','psp.product_id = pp.id','left');
    $this->db->where('schedule_id',$schedule_id);
    $query = $this->db->get('pmm_schedule_product psp');
    if($query->num_rows() > 0){
        $data['products'] = $query->result_array();

        $this->db->select('psp.date');
        $this->db->where('psp.week',$week);
        $this->db->where('ps.schedule_id',$schedule_id);
        $this->db->join('pmm_schedule_product ps','psp.schedule_product_id = ps.id','left');
        $this->db->group_by('psp.date');
        $arr = $this->db->get('pmm_schedule_product_date psp');
        $arr_date = $arr->result_array();


        $a = array();
        foreach ($arr_date as $key_v => $value) {
            foreach ($data['products'] as $key => $row) {
                $total = $this->db->select('SUM(koef) as total')->get_where('pmm_schedule_product_date',array('schedule_product_id'=>$row['id'],'week'=>$week,'date'=>$value['date']))->row_array();
                $value[$key + 1] = array($row['id']=>$total['total']);
            }
            $value['date_val'] = str_replace('-', '_', $value['date']);
            $value['date'] = date('d F Y',strtotime($value['date']));
            $a[] = $value;
        }

        $data['date'] = $a;
        $output = $data;
    }

    return $output;
   }

    function GetStatusPP($schedule_id,$week)
    {
        $output = false;

        $data = array();
        $this->db->select('pspd.status');
        $this->db->join('pmm_schedule_product psp','pspd.schedule_product_id = psp.id','left');
        $this->db->where('psp.schedule_id',$schedule_id);
        $this->db->where('pspd.week',$week);
        $query = $this->db->get('pmm_schedule_product_date pspd')->row_array();
        if(!empty($query)){
            $output = $query['status'];
        }

        return $output;
    }


    function GetTotalSisa($material_id,$start_date)
    {
        $output = 0;

        $this->db->select('SUM(volume) as total');
        $this->db->where('material_id',$material_id);
        $query = $this->db->get('pmm_receipt_material')->row_array();
        $total_mat = $query['total'];

        $this->db->select('SUM(pp.volume) as volume,ppm.koef');
        $this->db->join('pmm_production_material ppm','pp.id = ppm.production_id','left');
        $this->db->where('pp.date_production <',date('Y-m-d',strtotime($start_date)));
        $this->db->where('ppm.material_id',$material_id);
        $this->db->where('pp.status','PUBLISH');
        $arr = $this->db->get('pmm_productions pp')->row_array();
        $a = $arr['volume'] * $arr['koef'];
        $output = $total_mat - $a;
        return $output;
    }

    function GetProByProductDate($product_id,$start_date,$end_date)
    {
        $output = 0;

        $this->db->select('SUM(pp.volume) as volume');
        $this->db->where('pp.date_production >=',date('Y-m-d',strtotime($start_date)));
        $this->db->where('pp.date_production <=',date('Y-m-d',strtotime($end_date)));
        $this->db->where('pp.product_id',$product_id);
        $this->db->where('pp.status','PUBLISH');
        $arr = $this->db->get('pmm_productions pp')->row_array();
        if(!empty($arr)){
            $output = $arr['volume'];   
        }
        
        return $output;
    }


    function GetMaterialsOnRequest($schedule_id)
    {
        $output = 0;

        $this->db->select('psd.material_id,psd.koef,pm.material_name');
        $this->db->join('pmm_product pp','psp.product_id = pp.id','left');
        $this->db->join('pmm_composition_detail psd','pp.composition_id = psd.composition_id','left');
        $this->db->join('pmm_materials pm','psd.material_id = pm.id','left');
        $this->db->where('psp.schedule_id',$schedule_id);
        $this->db->group_by('psd.material_id');
        $arr = $this->db->get('pmm_schedule_product psp')->result_array();
        if(!empty($arr)){
            $output = $arr; 
        }
        
        return $output;
    }


   

    function DashboardProductions($arr_date,$client=false)
    {
        $data = array();

        $this->db->select('pc.client_name,ps.client_id');
        $this->db->join('pmm_client pc','ps.client_id = pc.id','left');
        if(!empty($arr_date)){
            $this->db->where('ps.date_production >=',date('Y-m-d',strtotime($arr_date[0])));
            $this->db->where('ps.date_production <=',date('Y-m-d',strtotime($arr_date[1])));
        }

        $this->db->group_by('ps.client_id');
        $this->db->where('ps.status','PUBLISH');
        $query = $this->db->get('pmm_productions ps');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['products'] = $this->GetProductForDash($arr_date,$row['client_id']);
                $data[] = $row;
            }
        }

        return $data;
    }

    function GetProductForDash($arr_date,$client_id)
    {   
        $data = array();
        $this->db->select('pp.product,pp.id');
        $this->db->where('pp.status','PUBLISH');
        $this->db->group_by('pp.id');
        $query = $this->db->get('pmm_product pp');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {

                $row['total_planning'] = $this->TotalPlanningFixed($row['id'],$arr_date,$client_id);
                $total_productions = $this->TotalRealProductions($row['id'],$arr_date,$client_id);
                $row['total_productions'] = $total_productions['total_productions'];
                $row['cost'] = $total_productions['cost'];
                $row['bill'] = $total_productions['bill'];

                $data[] = $row;
            }
        }
        return $data;
    }


    function TotalPlanningFixed($product_id,$arr_date,$client_id)
    {
        $output =0;

        $this->db->select('SUM(pspdf.koef) as total_planning');
        $this->db->join('pmm_schedule_product psd','pspdf.schedule_product_id = psd.id','left');
        $this->db->join('pmm_schedule ps','psd.schedule_id = ps.id','left');
        if(!empty($arr_date)){
            $this->db->where('pspdf.date >=',date('Y-m-d',strtotime($arr_date[0])));
            $this->db->where('pspdf.date <=',date('Y-m-d',strtotime($arr_date[1])));
        }
        $this->db->where('ps.client_id',$client_id);
        $this->db->where('psd.product_id',$product_id);
        $query = $this->db->get('pmm_schedule_product_date_fixed pspdf');
        if($query->num_rows() > 0){
            if($query->row_array()['total_planning'] == null){
                $output = 0;
            }else {
                $output = $query->row_array()['total_planning'];    
            }
            
        }
        return $output;
    }

    function TotalRealProductions($product_id,$arr_date,$client_id)
    {
        $output =0;

        $this->db->select('SUM(pp.volume) as total_productions, SUM(pp.price) as bill, SUM(pp.cost) as cost');
        if(!empty($arr_date)){
            $this->db->where('pp.date_production >=',date('Y-m-d',strtotime($arr_date[0])));
            $this->db->where('pp.date_production <=',date('Y-m-d',strtotime($arr_date[1])));
        }
        $this->db->where('pp.client_id',$client_id);
        $this->db->where('pp.product_id',$product_id);
        $this->db->where('pp.status','PUBLISH');
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get('pmm_productions pp');
        if($query->num_rows() > 0){
            if($query->row_array()['total_productions'] == null){
                $output = array(
                    'total_productions' => 0,
                    'cost' => 0,
                    'bill' => 0,
                );
            }else {
                $output = array(
                    'total_productions' => $query->row_array()['total_productions'],
                    'cost' => $query->row_array()['cost'],
                    'bill' => $query->row_array()['bill'],
                );
            }
            
        }
        return $output;
    }

    function GetMaterialsProductions($product_id=false,$arr_date,$client_id=false,$material_id)
    {
        $output = array();
        $arr_date = explode(' - ', $arr_date);

        $this->db->select('pp.id,pp.volume,pp.date_production, pp.product_id');
        $this->db->where('pp.date_production >=',date('Y-m-d',strtotime($arr_date[0])));
        $this->db->where('pp.date_production <=',date('Y-m-d',strtotime($arr_date[1]))); 
        if(!empty($product_id)){
            $this->db->where('pp.product_id',$product_id);
        } 
        if(!empty($client_id)){
           $this->db->where('pp.client_id',$client_id); 
        }
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->result_array();
        $total_sub = 0;
        $cost =0;
        foreach ($query as $key => $row) {
            
            $this->db->select('koef,price');
            $data = $this->db->get_where('pmm_production_material',array('production_id'=>$row['id'],'material_id'=>$material_id))->row_array();
            

            if($material_id == 1){
                $data['koef'] = $data['koef'] / 1000;
                if($data['price'] == 850){
                    $data['price'] = 850000;
                }
            }
            $total = $row['volume'] * $data['koef'];
            $cost += $total * $data['price'];
            $total_sub += $total;
        }
        $output = array('vol'=>$total_sub,'cost'=>$cost);

        return $output;

    }
    
    function GetTotalPemakaianMat($product_id,$arr_date,$client_id)
    {
        $output = 0;
        $this->db->select('SUM(volume) as volume');
        if(!empty($arr_date)){
            $this->db->where('pp.date_production >=',date('Y-m-d',strtotime($arr_date[0])));
            $this->db->where('pp.date_production <=',date('Y-m-d',strtotime($arr_date[1])));
        }
        if(!empty($product_id)){
            $this->db->where('pp.product_id',$product_id);
        }
        $this->db->where('pp.status','PUBLISH');
        $this->db->where('pp.client_id',$client_id);
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get('pmm_productions pp');  
        if($query->row_array()['volume'] == null){
            $output = 0;
        }else {
            $output = $query->row_array()['volume'];    
        }

        return $output;
    }


    function GetTotalReceipt($purchase_order_id)
    {
        $output = 0;

        $this->db->select('SUM(volume) as volume,harga_satuan');
        $this->db->where('purchase_order_id',$purchase_order_id);
        $this->db->group_by('material_id');
        $query = $this->db->get('pmm_receipt_material');
        foreach ($query->result_array() as $key => $row) {
            $output += $row['volume'] * $row['harga_satuan'];
        }
        return $output;
    }
	
    function GetReceiptMatUse($supplier_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_material=false)
    {
        $output = array();

        $this->db->select('prm.measure,pm.material_name,prm.material_id,SUM(prm.volume) as total, SUM((prm.cost / prm.convert_value) * prm.display_volume) as total_price, prm.convert_value, SUM(prm.volume * prm.convert_value) as total_convert');
        $this->db->join('pmm_materials pm','prm.material_id = pm.id','left');
        $this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('prm.date_receipt >=',$start_date);
            $this->db->where('prm.date_receipt <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppo.id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('prm.material_id',$filter_material);
        }
        $this->db->order_by('pm.material_name','asc');
        $this->db->group_by('prm.material_id');
        $query = $this->db->get('pmm_receipt_material prm');
        $output = $query->result_array();
        return $output;
    }

    function GetReceiptMatUseLalu($supplier_id=false,$purchase_order_no=false,$start_date=false,$filter_material=false)
    {
        $output = array();

        $this->db->select('prm.measure,pm.material_name,prm.material_id,SUM(prm.volume) as total, SUM((prm.cost / prm.convert_value) * prm.display_volume) as total_price, prm.convert_value, SUM(prm.volume * prm.convert_value) as total_convert');
        $this->db->join('pmm_materials pm','prm.material_id = pm.id','left');
        $this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
        if(!empty($start_date)){
            $this->db->where('prm.date_receipt <',$start_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppo.id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('prm.material_id',$filter_material);
        }
        $this->db->order_by('pm.material_name','asc');
        $this->db->group_by('prm.material_id');
        $query = $this->db->get('pmm_receipt_material prm');
        $output = $query->result_array();
        return $output;
    }

    function GetProMatMonth()
    {
        $output = array();

        $this->db->select('pm.material_name,ppm.material_id,ppm.koef,pms.measure_name,pm.cost,pm.color,pm.color_real');
        $this->db->join('pmm_productions pp','ppm.production_id = pp.id','left');
        $this->db->join('pmm_materials pm','ppm.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','ppm.measure = pms.id','left');
        $this->db->order_by('ppm.material_id','asc');
        $this->db->group_by('ppm.material_id');
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get('pmm_production_material ppm');
        $output = $query->result_array();
        return $output;
    }

    function GetReceiptMatMonth()
    {
        $output = array();

        $this->db->select('pm.material_name,ppm.material_id,pm.cost,ppm.measure');
        $this->db->join('pmm_materials pm','ppm.material_id = pm.id','left');
        $this->db->order_by('ppm.material_id','asc');
        $this->db->group_by('ppm.material_id');
        $query = $this->db->get('pmm_receipt_material ppm');
        $output = $query->result_array();
        return $output;
    }


    function TableCustomMaterial($supplier_id)
    {
        $data = array();

        $status = $this->input->post('status');
        $schedule_id = $this->input->post('schedule_id');
        $w_date = $this->input->post('filter_date');

        
        $this->db->where('status !=','DELETED');
        if($supplier_id !== 0){
            $this->db->where('supplier_id',$supplier_id);
        }
        
        if(!empty($status)){
            $this->db->where('status',$status);
        }
        if(!empty($w_date)){
            $arr_date = explode(' - ', $w_date);
            $start_date = $arr_date[0];
            $end_date = $arr_date[1];
            $this->db->where('date_po  >=',date('Y-m-d',strtotime($start_date)));   
            $this->db->where('date_po <=',date('Y-m-d',strtotime($end_date)));  
        }
		
		//$this->db->where("status <> 'UNPUBLISH'");
        $this->db->order_by('date_po','DESC');
		 $this->db->order_by('created_on','DESC');
        $query = $this->db->get('pmm_purchase_order');
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $no_po = "'".$row['no_po']."'";
                $row['no_po'] = '<a href="'.site_url('pmm/purchase_order/manage/'.$row['id']).'"  >'.$row['no_po'].'</a>';
                $row['document_po'] = '<a href="'.base_url().'uploads/purchase_order/'.$row['document_po'].'" target="_blank">'.$row['document_po'].'</a>';
                $row['date_po'] = date('d/m/Y',strtotime($row['date_po']));
                $row['supplier'] = $this->crud_global->GetField('penerima',array('id'=>$row['supplier_id']),'nama');

                $total_volume = $this->db->select('SUM(volume) as total,measure,SUM(volume *price) as total_tanpa_ppn')->get_where('pmm_purchase_order_detail',array('purchase_order_id'=>$row['id']))->row_array();
                $row['volume'] = number_format($total_volume['total'],2,',','.');
                $row['measure'] = $total_volume['measure'];
                $row['total_val'] = intval($row['total']);
                $row['total'] = number_format($total_volume['total_tanpa_ppn'],0,',','.');
                

                $receipt = $this->db->select('SUM(volume) as total')->get_where('pmm_receipt_material',array('purchase_order_id'=>$row['id']))->row_array();
                $total_receipt = $this->pmm_model->GetTotalReceipt($row['id']);
                $row['receipt'] = '<a href="'.site_url('pmm/purchase_order/receipt_material_pdf/'.$row['id']).'" target="_blank" >'.number_format($receipt['total'],2,',','.').'</a>';

                $presentase = ($receipt['total'] / $total_volume['total']) * 100;
				$row['presentase'] = number_format($presentase,0,',','.').' %';

                $row['total_receipt'] = number_format($total_receipt,0,',','.');
                $row['total_receipt_val'] = $total_receipt;

                $row['document_po'] = '<a href="' . base_url('uploads/purchase_order/' . $row['id']) .'" target="_blank">' . $row['document_po'] . '</a>'; 

                $delete = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a>';
                if($row['status'] == 'DRAFT'){
                    
                    $edit = '<a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
                }else {

                    $edit = false;
                }

                $upload_document = false;
                if($row['status'] == 'PUBLISH' || $row['status'] == 'CLOSED'){
                    $edit = '<a href="javascript:void(0);" onclick="UploadDoc('.$row['id'].')" class="btn btn-primary" title="Upload Document PO" ><i class="fa fa-upload"></i> </a>';
                }
                $edit_no_po = false;
                $status = "'".$row['status']."'";
                if(in_array($this->session->userdata('admin_group_id'), array(1,7,11,15))){
                     $edit_no_po = '<a href="javascript:void(0);" onclick="EditNoPo('.$row['id'].','.$no_po.','.$status.')" class="btn btn-primary" title="Edit Nomor PO" ><i class="fa fa-edit"></i> </a>';;
                }   
                $row['status'] = $this->pmm_model->GetStatus($row['status']);

                $row['actions'] = $edit.' '.$edit_no_po;

                $row['admin_name'] = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$row['created_by']),'admin_name');
                $row['created_on'] = date('d/m/Y H:i:s',strtotime($row['created_on']));
                
                $data[] = $row;
            }
        }

        return $data;
    }



    function GetPlanningMat($material_id,$start_date=false,$end_date=false)
    {
        $output = 0;


        $this->db->select('SUM(koef) as total');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('date >=',$start_date);
            $this->db->where('date <=',$end_date);
        }
        $this->db->where('status','PUBLISH');
        $query = $this->db->get('pmm_schedule_product_date');
        $total = $query->row_array()['total'];


        $this->db->select('psm.koef');
        $this->db->join('pmm_schedule_product_date pspd','psm.schedule_product_id = pspd.schedule_product_id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pspd.date >=',$start_date);
            $this->db->where('pspd.date <=',$end_date);
        }
        $this->db->where('psm.material_id',$material_id);
        $mat = $this->db->get('pmm_schedule_material psm')->row_array();

        $output = $total * $mat['koef'];
        return number_format($output,2,',','.');
    }

    function GetPOMat($material_id,$start_date=false,$end_date=false,$supplier_id=false,$purchase_order_no=false)
    {
        $output = 0;

        $this->db->select('SUM(ppod.volume) as total');
        $this->db->join('pmm_purchase_order ppo','ppod.purchase_order_id = ppo.id','left');
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppo.id',$purchase_order_no);
        }
        $this->db->where('material_id',$material_id);
        $query = $this->db->get_where('pmm_purchase_order_detail ppod')->row_array();

        $output = $query['total'];
        return number_format($output,2,',','.');
    }


    function GetRealMat($material_id,$start_date=false,$end_date=false,$supplier_id=false,$purchase_order_no=false)
    {
        $output = array();

        $this->db->select('SUM(prm.volume) as total, SUM(prm.volume * prm.price) as total_price');
        $this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('prm.date_receipt >=',$start_date);
            $this->db->where('prm.date_receipt <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('prm.purchase_order_id',$purchase_order_no);
        }
        $this->db->where('prm.material_id',$material_id);
        $query = $this->db->get_where('pmm_receipt_material prm')->row_array();

        $output = $query;
        return $output;
    }




    function GetPlanningProd($product_id,$start_date=false,$end_date=false,$client_id=false)
    {
        $output = 0;


        $this->db->select('SUM(koef) as total');
        $this->db->join('pmm_schedule_product psp','pspd.schedule_product_id = psp.id','left');
        $this->db->join('pmm_schedule ps','psp.schedule_id = ps.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pspd.date >=',$start_date);
            $this->db->where('pspd.date <=',$end_date);
        }
        if(!empty($client_id)){
            $this->db->where('ps.client_id',$client_id);
        }
        $this->db->where('pspd.status','PUBLISH');
        $this->db->where('pspd.product_id',$product_id);
        $query = $this->db->get('pmm_schedule_product_date pspd');
        $total = $query->row_array()['total'];

        $output = $total;
        return $output;
    }

    function GetRealProd($product_id,$start_date=false,$end_date=false,$client_id=false)
    {
        $output = array();

        $this->db->select('SUM(volume) as total, SUM(price) as cost');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('date_production >=',$start_date);
            $this->db->where('date_production <=',$end_date);
        }
        if(!empty($client_id)){
            $this->db->where('client_id',$client_id);
        }
        $this->db->where('product_id',$product_id);
        $this->db->where('status','PUBLISH');
        $query = $this->db->get_where('pmm_productions')->row_array();

        $output = $query;
        return $output;
    }

    function GetRealProdByClient($product_id,$start_date=false,$end_date=false,$client_id=false)
    {
        $output = array();

        $this->db->select('SUM(pp.volume) as total, SUM(pp.price) as cost, pc.client_name');
        $this->db->join('pmm_client pc','pp.client_id = pc.id');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($client_id)){
            $this->db->where('pp.client_id',$client_id);
        }
        $this->db->where('pp.product_id',$product_id);
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->result_array();

        $output = $query;
        return $output;
    }


    function GetDashGrafProd($product_id,$month,$before=false,$arr_date=false,$client_id=false)
    {
        $output = 0;

        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }

        $this->db->select('SUM(volume) as total');
        if(!empty($month)){

            if($before){
                $this->db->where('DATE_FORMAT(date_production,"%Y-%m") <=',$month);
            }else {
                if($num_data > 0){ 
                    $this->db->where('DATE_FORMAT(date_production,"%Y-%m")',$month);
                }else {
                    $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
                    $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1]))); 
                }
                
                
            }
            
        }
        if(!empty($client_id)){
            $this->db->where('client_id',$client_id);
        }

        $this->db->where('product_id',$product_id);
        $this->db->where('status','PUBLISH');
        $query = $this->db->get_where('pmm_productions')->row_array();

        $output = $query['total'];
        return $output;
    }

    function GetDashGrafProdPlanning($product_id,$month,$arr_date=false)
    {
        $output = 0;

        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }

        $this->db->select('SUM(koef) as total');
        if(!empty($month)){
            if($num_data > 0){ 
                $this->db->where('DATE_FORMAT(date,"%Y-%m")',$month);
            }else {
                $this->db->where('date >=',date('Y-m-d',strtotime($ex_date[0])));
                $this->db->where('date <=',date('Y-m-d',strtotime($ex_date[1]))); 
            }
        }
        $this->db->where('product_id',$product_id);
        $query = $this->db->get_where('pmm_schedule_product_date')->row_array();

        $output = $query['total'];

        return $output;
    }
    

    
    function GetDashGrafMat($material_id,$month,$arr_date=false)
    {
        $output = 0;

        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        

        $this->db->select('pp.id,pp.volume,pp.date_production, pp.product_id');
        if(!empty($month)){
            if($arr_date){
                if($num_data > 0){
                    $this->db->where('DATE_FORMAT(pp.date_production,"%Y-%m")',$month); 
                }else {
                    $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
                    $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1]))); 
                }
            }else {
                $this->db->where('DATE_FORMAT(pp.date_production,"%Y-%m")',$month);     
            }
           
        }
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->result_array();
        $total_sub = 0;
        foreach ($query as $key => $row) {
            
            $this->db->select('koef');
            $data = $this->db->get_where('pmm_production_material',array('production_id'=>$row['id'],'material_id'=>$material_id))->row_array();
            $total = $row['volume'] * $data['koef'];
            $total_sub += $total;
        }
        $output = $total_sub;
        return $output;
    }


    function GetDashGrafMatReal($material_id,$month,$arr_date=false)
    {
        $output = 0;

        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        

        $this->db->select('SUM(volume) as total');
        if(!empty($month)){
            if($arr_date){
                if($num_data > 0){
                    $this->db->where('DATE_FORMAT(prm.date,"%Y-%m")',$month); 
                }else {
                    $this->db->where('prm.date >=',date('Y-m-d',strtotime($ex_date[0])));
                    $this->db->where('prm.date <=',date('Y-m-d',strtotime($ex_date[1]))); 
                }
            }else {
                $this->db->where('DATE_FORMAT(prm.date,"%Y-%m")',$month);     
            }
           
        }
        $this->db->where('prm.material_id',$material_id);
        $sisa_real = $this->db->get_where('pmm_remaining_materials prm')->row_array();


        $this->db->select('SUM(volume) as total');
        if(!empty($month)){
            if($arr_date){
                if($num_data > 0){
                    $this->db->where('DATE_FORMAT(prm.date_receipt,"%Y-%m")',$month); 
                }else {
                    $this->db->where('prm.date_receipt >=',date('Y-m-d',strtotime($ex_date[0])));
                    $this->db->where('prm.date_receipt <=',date('Y-m-d',strtotime($ex_date[1]))); 
                }
            }else {
                $this->db->where('DATE_FORMAT(prm.date_receipt,"%Y-%m")',$month);     
            }
           
        }
        $this->db->where('prm.material_id',$material_id);
        $penerimaan = $this->db->get_where('pmm_receipt_material prm')->row_array();

        $output = $penerimaan['total'] - $sisa_real['total'];
        return $output;
    }

    function GetDashGrafMatSisa($material_id,$month,$arr_date=false)
    {
        $output = 0;

        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        

        $this->db->select('pp.id,pp.volume,pp.date_production, pp.product_id');
        if(!empty($month)){
            if($arr_date){
                if($num_data > 0){
                    $this->db->where('DATE_FORMAT(pp.date_production,"%Y-%m")',$month); 
                }else {
                    $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
                    $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1]))); 
                }
            }else {
                $this->db->where('DATE_FORMAT(pp.date_production,"%Y-%m")',$month);     
            }
           
        }
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->result_array();
        $total_sub = 0;
        foreach ($query as $key => $row) {
            
            $this->db->select('koef');
            $data = $this->db->get_where('pmm_production_material',array('production_id'=>$row['id'],'material_id'=>$material_id))->row_array();
            $total = $row['volume'] * $data['koef'];
            $total_sub += $total;
        }


        $this->db->select('SUM(volume) as total');
        if(!empty($month)){
            if($arr_date){
                if($num_data > 0){
                    $this->db->where('DATE_FORMAT(prm.date_receipt,"%Y-%m")',$month); 
                }else {
                    $this->db->where('prm.date_receipt >=',date('Y-m-d',strtotime($ex_date[0])));
                    $this->db->where('prm.date_receipt <=',date('Y-m-d',strtotime($ex_date[1]))); 
                }
            }else {
                $this->db->where('DATE_FORMAT(prm.date_receipt,"%Y-%m")',$month);     
            }
           
        }
        $this->db->where('prm.material_id',$material_id);
        $penerimaan = $this->db->get_where('pmm_receipt_material prm')->row_array();


        if($material_id == 1){
            $total_sub = $total_sub / 1000;
        }

        $output = $penerimaan['total'] - $total_sub;
        return $output;
    }

    function GetDashGrafMatSisaReal($material_id,$month,$arr_date=false)
    {
        $output = 0;

        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        

        $this->db->select('SUM(volume) as total');
        if(!empty($month)){
            if($arr_date){
                if($num_data > 0){
                    $this->db->where('DATE_FORMAT(prm.date,"%Y-%m")',$month); 
                }else {
                    $this->db->where('prm.date >=',date('Y-m-d',strtotime($ex_date[0])));
                    $this->db->where('prm.date <=',date('Y-m-d',strtotime($ex_date[1]))); 
                }
            }else {
                $this->db->where('DATE_FORMAT(prm.date,"%Y-%m")',$month);     
            }
           
        }
        $this->db->where('prm.material_id',$material_id);
        $sisa_real = $this->db->get_where('pmm_remaining_materials prm')->row_array();


        $output = $sisa_real['total'];
        return $output;
    }

    


    function GetReportGrafMat($material_id,$month,$before=false,$client_id=false)
    {
        $output = 0;

        $this->db->select('pp.id,pp.volume,pp.date_production, pp.product_id');
        if(!empty($month)){
            if($before){
                $this->db->where('DATE_FORMAT(pp.date_production,"%Y-%m") <=',$month);
            }else {
                $this->db->where('DATE_FORMAT(pp.date_production,"%Y-%m")',$month);    
            }
            
        }
        if(!empty($client_id)){
            $this->db->where('pp.client_id',$client_id);
        }
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->result_array();
        $total_sub = 0;
        foreach ($query as $key => $row) {
            
            $this->db->select('koef');
            $data = $this->db->get_where('pmm_production_material',array('production_id'=>$row['id'],'material_id'=>$material_id))->row_array();
            $total = $row['volume'] * $data['koef'];
            $total_sub += $total;
        }
        $output = $total_sub;
        return $output;
    }


    function GetReceiptMonth($material_id,$month,$before=false)
    {   
        $output = 0;

        $this->db->select('SUM(volume) as total');
        if(!empty($month)){
            if($before){
                $this->db->where('DATE_FORMAT(date_receipt,"%Y-%m") <=',$month);
            }else {
                $this->db->where('DATE_FORMAT(date_receipt,"%Y-%m")',$month);    
            }
        }
        $this->db->where('material_id',$material_id);
        $query = $this->db->get_where('pmm_receipt_material')->row_array();

        $output = $query['total'];
        return $output;
    }

    function GetSisaMonth($material_id,$month,$before=false)
    {   
        $output = 0;

        $this->db->select('SUM(volume) as total');
        if(!empty($month)){
            if($before){
                $this->db->where('DATE_FORMAT(date,"%Y-%m") <=',$month);
            }else {
                $this->db->where('DATE_FORMAT(date,"%Y-%m")',$month);    
            }
            
        }
        $this->db->where('material_id',$material_id);
        $query = $this->db->get_where('pmm_remaining_materials')->row_array();

        $output = $query['total'];
        return $output;
    }


    function GetLabaDash($month,$arr_date)
    {
        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
           

        $products = $this->db->get_where('pmm_product',array('status'=>'PUBLISH'))->result_array();

        $progress = 0;
        $pakai = 0;
        foreach ($products as $key => $pro) {
            $riel_price = $pro['contract_price'];
            $this->db->select('SUM(volume) as total');
            if($num_data > 0){
                $this->db->where('DATE_FORMAT(date_production,"%Y-%m")',date('Y-m',strtotime($month)));
            }else {
                $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
                $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1])));
            }
            
            $this->db->where('product_id',$pro['id']);
            $this->db->where('status','PUBLISH');
            $query = $this->db->get_where('pmm_productions')->row_array();

            $progress += $riel_price * $query['total'];
        }

        $material_set = $this->GetProMatMonth();
        $datasets_mat =array();
        $label_mat = array();
        $a =0;
        foreach ($material_set as $key => $mat) {
            $data_chart_mat = array();
            $total_mat = $this->GetDashGrafMat($mat['material_id'],date('Y-m',strtotime($month)),$arr_date);
            if($mat['material_id'] == 1){
                $mat['cost'] = $mat['cost']  / 1000;
            }
            $pakai += $total_mat * $mat['cost'];
        }


        $laba = ($progress - $pakai) / 1000000;

        return $laba;
    }


    function GetAlatProd()
    {
        $output = array();

        $this->db->select('t.id,t.tool,ppd.koef');
        $this->db->join('pmm_tools t','ppd.type_id = t.id','left');
        $this->db->group_by('ppd.type_id');
        $query = $this->db->get_where('pmm_product_detail ppd',array('ppd.type'=>'ALAT','ppd.status'=>'PUBLISH'))->result_array();

        foreach ($query as $row) {
            $row['total'] = $this->GetPriceAlat($row['id']) * $row['koef'];

            $output[] = $row;
        }
        return $output;
    }
    

    function GetPriceAlat($tool_id)
    {
        $output = 0;

        $query = $this->db->select('SUM(cost) as total')->get_where('pmm_tool_detail',array('tool_id'=>$tool_id,'status'=>'PUBLISH'))->row_array();

        if(!empty($query)){
            $output = $query['total'];
        }

        return $output;
    }

    function getRevenue($month,$arr_date=false)
    {
        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        $this->db->select('SUM(pp.price) as total');
        $this->db->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left');
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pp.date_production >=',$first_day_this_month);
            $this->db->where('pp.date_production <=',$last_day_this_month);
        }else {
            $this->db->where('pp.date_production >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pp.date_production <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where("pp.product_id in (3,4,7,8,9,14,24)");
        $this->db->where('pp.status','PUBLISH');
        $this->db->where("ppo.status in ('OPEN','CLOSED')");
        $query = $this->db->get_where('pmm_productions pp')->row_array();

        return $query['total'];
    }

    function getRevenueStok($month,$arr_date=false)
    {
        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }

        $this->db->select('(pp.total_nilai_akhir) as total');
        $this->db->order_by('pp.date_akumulasi','desc')->limit(1);
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pp.date_akumulasi >=',$first_day_this_month);
            $this->db->where('pp.date_akumulasi <=',$last_day_this_month);
        }else {
            $this->db->where('pp.date_akumulasi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pp.date_akumulasi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $nilai_persediaan_bahan_baku = $this->db->get_where('akumulasi_bahan_baku pp')->row_array();

        $this->db->select('(pp.total_nilai_akhir) as total');
        $this->db->order_by('pp.date_akumulasi','desc')->limit(1);
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pp.date_akumulasi >=',$first_day_this_month);
            $this->db->where('pp.date_akumulasi <=',$last_day_this_month);
        }else {
            $this->db->where('pp.date_akumulasi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pp.date_akumulasi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $nilai_persediaan_barang_jadi = $this->db->get_where('akumulasi pp')->row_array();

        return $nilai_persediaan_bahan_baku['total'] + $nilai_persediaan_barang_jadi['total'];
    }


    function getRevenueAll($arr_date=false,$before=false)
    {
        $output = array('total'=>0); 

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('SUM(pp.price) as total, SUM(pp.volume) as volume');
            $this->db->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pp.date_production <',$start_date);
                }else {
                    $this->db->where('pp.date_production >=',$start_date);
                    $this->db->where('pp.date_production <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pp.date_production <=',$last_opname);
            }
            $this->db->where("pp.product_id in (3,4,7,8,9,14,24)");
            $this->db->where('pp.status','PUBLISH');
            $this->db->where("ppo.status in ('OPEN','CLOSED')");
            $query = $this->db->get_where('pmm_productions pp')->row_array();

            $output = $query;
        }
        
        return $output;
    }

    function getRevenueAllPersediaanBahanBaku($arr_date=false,$before=false)
    {
        $output = array('total'=>0);

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('pp.date_akumulasi, pp.total_nilai_akhir as total');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pp.date_akumulasi <',$start_date);
                }else {
                    $this->db->where('pp.date_akumulasi >=',$start_date);
                    $this->db->where('pp.date_akumulasi <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pp.date_akumulasi <=',$last_opname);
                $this->db->order_by('pp.date_akumulasi','desc')->limit(1);
            }
            $query = $this->db->get_where('akumulasi_bahan_baku pp')->row_array();

            $output = $query;
            
        }
          
        return $output;
    }

    function getRevenueAllPersediaanBarangJadi($arr_date=false,$before=false)
    {
        $output = array('total'=>0);

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('pp.date_akumulasi, pp.total_nilai_akhir as total');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pp.date_akumulasi <',$start_date);
                }else {
                    $this->db->where('pp.date_akumulasi >=',$start_date);
                    $this->db->where('pp.date_akumulasi <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pp.date_akumulasi <=',$last_opname);
                $this->db->order_by('pp.date_akumulasi','desc')->limit(1);
            }
            $query = $this->db->get_where('akumulasi pp')->row_array();

            $output = $query;
            
        }
          
        return $output;
    }

    function getRevenueCost($month,$arr_date=false)
    {
        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        $this->db->select('SUM(pp.total_nilai_keluar) as total');
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pp.date_akumulasi >=',$first_day_this_month);
            $this->db->where('pp.date_akumulasi <=',$last_day_this_month);
        }else {
            $this->db->where('pp.date_akumulasi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pp.date_akumulasi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $akumulasi_hpp = $this->db->get_where('akumulasi pp')->row_array();


        $this->db->select('pb.tanggal_transaksi, sum(pdb.jumlah) as total');
        $this->db->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('c.coa_category',16);
        $this->db->where('pb.status','PAID');
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pb.tanggal_transaksi >=',$first_day_this_month);
            $this->db->where('pb.tanggal_transaksi <=',$last_day_this_month);
        }else {
            $this->db->where('pb.tanggal_transaksi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pb.tanggal_transaksi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $biaya_umum_administratif_biaya = $this->db->get_where('pmm_biaya pb')->row_array();

        $this->db->select('pb.tanggal_transaksi, sum(pdb.debit) as total');
        $this->db->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('c.coa_category',16);
        $this->db->where('pb.status','PAID');
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pb.tanggal_transaksi >=',$first_day_this_month);
            $this->db->where('pb.tanggal_transaksi <=',$last_day_this_month);
        }else {
            $this->db->where('pb.tanggal_transaksi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pb.tanggal_transaksi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $biaya_umum_administratif_jurnal = $this->db->get_where('pmm_jurnal_umum pb')->row_array();

        $this->db->select('pb.tanggal_transaksi, sum(pdb.jumlah) as total');
        $this->db->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('c.coa_category',17);
        $this->db->where('pb.status','PAID');
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pb.tanggal_transaksi >=',$first_day_this_month);
            $this->db->where('pb.tanggal_transaksi <=',$last_day_this_month);
        }else {
            $this->db->where('pb.tanggal_transaksi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pb.tanggal_transaksi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $biaya_lainnya_biaya = $this->db->get_where('pmm_biaya pb')->row_array();

        $this->db->select('pb.tanggal_transaksi, sum(pdb.debit) as total');
        $this->db->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('c.coa_category',17);
        $this->db->where('pb.status','PAID');
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pb.tanggal_transaksi >=',$first_day_this_month);
            $this->db->where('pb.tanggal_transaksi <=',$last_day_this_month);
        }else {
            $this->db->where('pb.tanggal_transaksi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pb.tanggal_transaksi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $biaya_lainnya_jurnal = $this->db->get_where('pmm_jurnal_umum pb')->row_array();    

        return $akumulasi_hpp['total'] + $biaya_umum_administratif_biaya['total'] + $biaya_umum_administratif_jurnal['total'] + $biaya_lainnya_biaya['total'] + $biaya_lainnya_jurnal['total'];
    }

    function getPersediaanBahanBaku($month,$arr_date=false)
    {
        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }

        $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_akhir) as total');
        $this->db->order_by('pp.date_akumulasi','desc')->limit(1);
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pp.date_akumulasi >=',$first_day_this_month);
            $this->db->where('pp.date_akumulasi <=',$last_day_this_month);
        }else {
            $this->db->where('pp.date_akumulasi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pp.date_akumulasi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $nilai_persediaan_bahan_baku = $this->db->get_where('akumulasi_bahan_baku pp')->row_array();

        return $nilai_persediaan_bahan_baku['total'];
    }

    function getPersediaanBarangJadi($month,$arr_date=false)
    {
        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }

        $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_akhir) as total');
        $this->db->order_by('pp.date_akumulasi','desc')->limit(1);
        if($num_data > 0){
			$first_day_this_month = date('Y-m-d',strtotime($month)).'';
            $last_day_this_month  = date('Y-m-31',strtotime($month));
            $this->db->where('pp.date_akumulasi >=',$first_day_this_month);
            $this->db->where('pp.date_akumulasi <=',$last_day_this_month);
        }else {
            $this->db->where('pp.date_akumulasi >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pp.date_akumulasi <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $nilai_persediaan_barang_jadi = $this->db->get_where('akumulasi pp')->row_array();

        return $nilai_persediaan_barang_jadi['total'];
    }

    function getRevenueCostAll($arr_date=false,$before=false)
    {
        $output = array('total'=>0);

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pp.date_akumulasi <',$start_date);
                }else {
                    $this->db->where('pp.date_akumulasi >=',$start_date);
                    $this->db->where('pp.date_akumulasi <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pp.date_akumulasi <=',$last_opname);
            }
            
            $query = $this->db->get_where('akumulasi pp')->row_array();

            $output = $query;
            
        }      
        
        return $output;
    }

    function getRevenueCostAllAdministrasiBiaya($arr_date=false,$before=false)
    {
        $output = array('total'=>0);

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('pb.tanggal_transaksi, sum(pdb.jumlah) as total');
            $this->db->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left');
            $this->db->join('pmm_coa c','pdb.akun = c.id','left');
            $this->db->where('c.coa_category',16);
            $this->db->where('pb.status','PAID');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pb.tanggal_transaksi <',$start_date);
                }else {
                    $this->db->where('pb.tanggal_transaksi >=',$start_date);
                    $this->db->where('pb.tanggal_transaksi <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pb.tanggal_transaksi <=',$last_opname);
            }
            $query = $this->db->get_where('pmm_biaya pb')->row_array();

            $output = $query;
            
        }
          
        return $output;
    }

    function getRevenueCostAllAdministrasiJurnal($arr_date=false,$before=false)
    {
        $output = array('total'=>0);

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('pb.tanggal_transaksi, sum(pdb.debit) as total');
            $this->db->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left');
            $this->db->join('pmm_coa c','pdb.akun = c.id','left');
            $this->db->where('c.coa_category',16);
            $this->db->where('pb.status','PAID');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pb.tanggal_transaksi <',$start_date);
                }else {
                    $this->db->where('pb.tanggal_transaksi >=',$start_date);
                    $this->db->where('pb.tanggal_transaksi <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pb.tanggal_transaksi <=',$last_opname);
            }
            $query = $this->db->get_where('pmm_jurnal_umum pb')->row_array();

            $output = $query;
            
        }
          
        return $output;
    }

    function getRevenueCostAllLainnyaBiaya($arr_date=false,$before=false)
    {
        $output = array('total'=>0);

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('pb.tanggal_transaksi, sum(pdb.jumlah) as total');
            $this->db->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left');
            $this->db->join('pmm_coa c','pdb.akun = c.id','left');
            $this->db->where('c.coa_category',17);
            $this->db->where('pb.status','PAID');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pb.tanggal_transaksi <',$start_date);
                }else {
                    $this->db->where('pb.tanggal_transaksi >=',$start_date);
                    $this->db->where('pb.tanggal_transaksi <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pb.tanggal_transaksi <=',$last_opname);
            }
            $query = $this->db->get_where('pmm_biaya pb')->row_array();

            $output = $query;
            
        }
          
        return $output;
    }

    function getRevenueCostAllLainnyaJurnal($arr_date=false,$before=false)
    {
        $output = array('total'=>0);

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }

        if(!empty($last_opname)){
            $this->db->select('pb.tanggal_transaksi, sum(pdb.debit) as total');
            $this->db->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left');
            $this->db->join('pmm_coa c','pdb.akun = c.id','left');
            $this->db->where('c.coa_category',17);
            $this->db->where('pb.status','PAID');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                
                if($before){
                    $this->db->where('pb.tanggal_transaksi <',$start_date);
                }else {
                    $this->db->where('pb.tanggal_transaksi >=',$start_date);
                    $this->db->where('pb.tanggal_transaksi <=',$last_opname);  
                }
                
            }else {
                
                $this->db->where('pb.tanggal_transaksi <=',$last_opname);
            }
            $query = $this->db->get_where('pmm_jurnal_umum pb')->row_array();

            $output = $query;
            
        }
          
        return $output;
    }
    
    function getOverhead($month=false,$arr_date=false)
    {
        $total = 0;
        $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        $this->db->select('SUM(cost) as total');
        if($num_data > 0){
            $this->db->where('DATE_FORMAT(date,"%Y-%m")',date('Y-m',strtotime($month)));
        }else {
            $this->db->where('date >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where('status','PUBLISH');
        $query = $this->db->get_where('pmm_general_cost')->row_array();
        if(!empty($query)){
            $total = $query['total'];
        }
        return $total;
    }

    function getOverhead2($arr_date=false)
    {
        
        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
            
        }

        $total = 0;
        if(!empty($last_opname)){
            $this->db->select('SUM(cost) as total');
            if($arr_date){
                $ex_date = explode(' - ', $arr_date);
                $this->db->where('date >=',$start_date);
                $this->db->where('date <=',$last_opname);
            }else {
                $this->db->where('date <=',$last_opname);
            }
            $this->db->where('status','PUBLISH');
            $query = $this->db->get_where('pmm_general_cost')->row_array();
            if(!empty($query)){
                $total = $query['total'];
            }
        }
        

        return $total;
    }

    function getProgressReal($id,$month,$arr_date=false)
    {
         $num_data = 1; 

        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $start = substr($ex_date[0],3,2);
            $end = substr($ex_date[1],3,2);

            if($start == $end){
                $num_data = 0; 
            }
        }
        $this->db->select('SUM(price) as total, p.nama');
		$this->db->join('penerima p','pp.client_id = p.id','left');
        if($num_data > 0){
            $this->db->where('DATE_FORMAT(date_production,"%Y-%m")',date('Y-m',strtotime($month)));
 
        }else {
            $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where('p.id = 585' );
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->row_array();

        return
		$query['total'];
    }

    function getProgressRealAll($client_id,$arr_date=false)
    {
         $num_data = 1; 

        $this->db->select('SUM(price) as total, p.nama');
		$this->db->join('penerima p','pp.client_id = p.id','left');
        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where('pp.client_id = 585');
        $this->db->where('pp.status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->row_array();

        return $query['total'];

    }

    function getProgressContract($client_id)
    {
        $this->db->select('SUM(contract) as total');
        $this->db->where('id',$client_id);
        $this->db->where('status','PUBLISH');
        $query = $this->db->get_where('pmm_client')->row_array();

        return $query['total'];
    }

    function getRevenueClient($client_id=false,$arr_date=false)
    {

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));

            // Get Last Opname
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            $last_opname = $last_production['date'];
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
            
        }

        $total = 0;
        if(!empty($last_production)){
            $this->db->select('SUM(price) as total');
            if(!empty($arr_date)){
                $ex_date = explode(' - ', $arr_date);
                $this->db->where('date_production >=',$start_date);
                $this->db->where('date_production <=',$last_opname);
            }else {
                $this->db->where('date_production <=',$last_opname);
            }
            $this->db->where('status','PUBLISH');
            if($client_id){
                $this->db->where('client_id',$client_id);
            }
            
            
            $query = $this->db->get_where('pmm_productions')->row_array();
            if(!empty($query)){
                $total = $query['total'];
            }
        }
        
        return $total;
    }

    function getRevenueClient2($client_id=false,$arr_date=false)
    {

        $this->db->select('SUM(price) as total, SUM(volume) as volume');
        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where('status','PUBLISH');
        if($client_id){
            $this->db->where('client_id',$client_id);
        }
        
        $query = $this->db->get_where('pmm_productions')->row_array();

        return $query;
    }

    function getCostDB($id=false,$arr_date=false)
    {
        $this->load->model('pmm_reports');
        $output = 0;

        
        if(!empty($arr_date)){
            $total_material = $this->pmm_reports->MaterialUsageAllDate($arr_date); 
        }else {
            $total_material = $this->pmm_reports->MaterialUsageAll($arr_date);  
        }
        $total_equipment = $this->getEquipmentCost($arr_date);


        $total_overhead = $this->getOverhead2($arr_date);

        if($id == 0){
            $output = $total_material;
        }else if($id == 1){
            $output = $total_equipment;
        }else if($id == 2){
            $output = $total_overhead;
        }else {
            $output = $total_material + $total_equipment + $total_overhead;
        }

        return $output;
    }

    function getMaterialsCost($arr_date)
    {
        $this->db->select('SUM(pp.volume * ppm.koef * ppm.price) as total');
        $this->db->join('pmm_production_material ppm','pp.id = ppm.production_id');
        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where('status','PUBLISH');
        $query = $this->db->get_where('pmm_productions pp')->row_array();

        $total_sub = $query['total'];
        return $total_sub;
    }

    function getEquipmentCost($arr_date=false)
    {
        
        $total = 0; 

        if(!empty($arr_date)){
            $ex_date = explode(' - ', $arr_date);
            $start_date = date('Y-m-d',strtotime($ex_date[0]));
            $end_date = date('Y-m-d',strtotime($ex_date[1]));
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','date <='=>$end_date))->row_array();
            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
        }else {
            $last_production = $this->db->select('date')->order_by('date','desc')->limit(1)->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH'))->row_array();

            if(!empty($last_production)){
                $last_opname = $last_production['date'];
            }
            
        }

        if(!empty($last_opname)){
            $this->db->select('SUM(total) as total');
            if($arr_date){
                
                $this->db->where('date >=',$start_date);
                $this->db->where('date <=',$last_opname);
            }else {
                $this->db->where('date <=',$last_opname);  
            }
            $query = $this->db->get('pmm_equipments_data')->row_array();

            $total = $query['total'];
        }
        
        return $total;

    }

    function MaterialConvert($material_id,$measure_from)
    {
        $output = false;

        $measure = $this->db->select('measure')->get_where('pmm_materials',array('id'=>$material_id,'status'=>'PUBLISH'))->row_array()['measure'];

        if(is_string($measure_from)){
           $measure_from = $this->crud_global->GetField('pmm_measures',array('measure_name'=>$measure_from),'id'); 
        }

        $query = $this->db->select('value')->get_where('pmm_measure_convert',array('measure_id'=>$measure,'measure_to'=>$measure_from))->row_array();
        if(!empty($query)){
            $output = $query['value'];
        }

        return $output;

    }

    function getMatByPenawaran($id)
    {

        $this->db->select('pm.nama_produk as material_name, ppd.measure,ppd.material_id, ppd.id, pms.measure_name, price, nomor_penawaran, pp.id, ppd.tax_id, ppd.tax');
        $this->db->join('pmm_penawaran_pembelian pp','ppd.penawaran_pembelian_id = pp.id','left');
        $this->db->join('produk pm','ppd.material_id = pm.id','left');
        $this->db->join('pmm_measures pms','ppd.measure = pms.id','left');
        $this->db->where('pp.supplier_id',$id);
        $this->db->where('pp.status','OPEN');
		$this->db->order_by('pp.created_on','DESC');
        $data = $this->db->get('pmm_penawaran_pembelian_detail ppd')->result_array();

        return $data;
    }
	
	function getJMD($id)
    {

        $this->db->select('jmd.semen_2 as material_name, jmd.measure,ppd.product_id, ppd.id, pms.measure_name, price, nomor');
        $this->db->join('pmm_penawaran_penjualan pp','ppd.penawaran_penjualan_id = pp.id','left');
        $this->db->join('produk pm','ppd.product_id = pm.id','left');
        $this->db->join('pmm_measures pms','ppd.measure = pms.id','left');
        $this->db->where('jmd.status','PUBLISH');
		$this->db->order_by('jmd.id','DES');
        $data = $this->db->get('pmm_jmd jmd')->result_array();

        return $data;
    }
	
	function getMatByPenawaranPenjualan($id)
    {

        $this->db->select('pp.id as penawaran_id, pp.nomor, ppd.id, ppd.product_id as product_id, pm.nama_produk as nama_produk, pms.measure_name as satuan, ppd.price as harga, ppd.tax_id as tax');
        $this->db->join('pmm_penawaran_penjualan pp','ppd.penawaran_penjualan_id = pp.id','left');
        $this->db->join('produk pm','ppd.product_id = pm.id','left');
        $this->db->join('pmm_measures pms','ppd.measure = pms.id','left');
		$this->db->join('pmm_taxs pt','ppd.tax_id = pt.id','left');
        $this->db->where('pp.status','OPEN');
		$this->db->group_by('ppd.id');
		$this->db->order_by('pp.created_on','DESC');
        $data = $this->db->get('pmm_penawaran_penjualan_detail ppd')->result_array();

        return $data;
    }
	
    function getOneCostMatByPenawaran($supplier_id,$material_id)
    {
        $this->db->select('ppd.price as cost');
        $this->db->join('pmm_penawaran_pembelian pp','ppd.penawaran_pembelian_id = pp.id','left');
        $this->db->where('pp.supplier_id',$supplier_id);
        $this->db->where('ppd.material_id',$material_id);
        $this->db->where('pp.status','OPEN');
        $data = $this->db->get('pmm_penawaran_pembelian_detail ppd')->row_array();

        return $data;
    }

    function GetNameGroup($id)
    {
        $output = array();

        $this->db->select('g.admin_group_name, a.admin_name');
        $this->db->join('tbl_admin a','g.admin_group_id = a.admin_group_id','left');
        $this->db->where('g.admin_group_id',$id);
        $this->db->where('a.status',1);
        $this->db->group_by('g.admin_group_id');
        $query = $this->db->get('tbl_admin_group g');
        $output = $query->row_array();
        return $output;
    }
	
	function getPenerima($id=false,$row=false)
    {
        $output = false;

        if($id){
            $this->db->where('id',$id);
        }
        $this->db->where('status','PUBLISH');
        $query = $this->db->get('penerima');
		
        if($query->num_rows() > 0){
            if($row){
                $output = $query->row_array();
            }else {
                $output = $query->result_array();    
            }
            
        }
        return $output;
    }

    function TableMainBiaya($id)
    {
        $data = array();
        $this->db->select('b.*, c.coa as bayar_dari, p.nama, lk.lampiran');
        $this->db->join('penerima p','b.penerima = p.id','left');
        $this->db->join('pmm_coa c','b.bayar_dari = c.id','left');
        $this->db->join('pmm_lampiran_biaya lk','b.id = lk.biaya_id','left');	
        $this->db->where('b.id',$id);
        $this->db->order_by('b.id','asc');
        $query = $this->db->get('pmm_biaya b');
	
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['bayar_dari'] = $row['bayar_dari'];
                $row['nomor_transaksi']= $row['nomor_transaksi'];
                $row['tanggal_transaksi']= date('d F Y',strtotime($row['tanggal_transaksi']));
				$row['total']= number_format($row['total'],0,',','.');
                $row['memo']= $row['memo'];
                $row['lampiran'] = "<a href=" . base_url('uploads/biaya/' . $row["lampiran"]) . ">" . $row["lampiran"] . "</a>";  
                $row['actions'] = '<a href="javascript:void(0);" onclick="OpenFormMain('.$row['id'].')" class="btn btn-success">Update Biaya </a>';
                
                $data[] = $row;
            }

        }
        
        return $data;   
    }

    function TableDetailBiaya($id)
    {
        $data = array();
        $this->db->select('pdb.*, c.coa as akun');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('pdb.biaya_id',$id);
        $this->db->order_by('pdb.id','asc');
        $query = $this->db->get('pmm_detail_biaya pdb');
	
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['akun'] = $row['akun'];
                $row['deskripsi']= $row['deskripsi'];
				$row['jumlah']= number_format($row['jumlah'],0,',','.');
                $row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
                
                $data[] = $row;
            }

        }
        
        return $data;   
    }

    function GetNoEditBiaya()
    {

        $code_prefix = $this->db->get_where('pmm_setting_production')->row_array();
        $output = false;

        $query = $this->db->select('id')->order_by('id','desc')->get('transactions');
        if($query->num_rows() > 0){
            $id = $query->row_array()['id'] + 1;
        }else {
            $id = 1;
        }
        $output = sprintf($id);
        return $output;
            
    }

    function TableMainJurnal($id)
    {
        $data = array();
        $this->db->select('b.*, lk.lampiran');
        $this->db->join('pmm_lampiran_jurnal lk','b.id = lk.jurnal_id','left');	
        $this->db->where('b.id',$id);
        $this->db->order_by('b.id','asc');
        $query = $this->db->get('pmm_jurnal_umum b');
	
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['nomor_transaksi']= $row['nomor_transaksi'];
                $row['tanggal_transaksi']= date('d F Y',strtotime($row['tanggal_transaksi']));
				$row['total']= number_format($row['total'],0,',','.');
                $row['total_debit']= number_format($row['total_debit'],0,',','.');
                $row['total_kredit']= number_format($row['total_kredit'],0,',','.');
                $row['memo']= $row['memo'];
                $row['lampiran'] = "<a href=" . base_url('uploads/jurnal_umum/' . $row["lampiran"]) . ">" . $row["lampiran"] . "</a>";  
                $row['actions'] = '<a href="javascript:void(0);" onclick="OpenFormMain('.$row['id'].')" class="btn btn-success">Update Jutrnal Umum </a>';
                
                $data[] = $row;
            }

        }
        
        return $data;   
    }

    function TableDetailJurnal($id)
    {
        $data = array();
        $this->db->select('pdb.*, c.coa as akun');
        $this->db->join('pmm_coa c','pdb.akun = c.id','left');
        $this->db->where('pdb.jurnal_id',$id);
        $this->db->order_by('pdb.id','asc');
        $query = $this->db->get('pmm_detail_jurnal pdb');
        
	
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['akun'] = $row['akun'];
                $row['deskripsi']= $row['deskripsi'];
				$row['debit']= number_format($row['debit'],0,',','.');
                $row['kredit']= number_format($row['kredit'],0,',','.');
                $row['actions'] = '<a href="javascript:void(0);" onclick="DeleteData('.$row['id'].')" class="btn btn-danger"><i class="fa fa-close"></i> </a> <a href="javascript:void(0);" onclick="OpenForm('.$row['id'].')" class="btn btn-primary"><i class="fa fa-edit"></i> </a>';
                
                $data[] = $row;
            }

        }
        
        return $data;   
    }


    function TableMainTagihan($id)
    {
        $data = array();
        $this->db->select('ppp.*');
        $this->db->where('ppp.id',$id);
        $this->db->order_by('ppp.id','asc');
        $query = $this->db->get('pmm_penagihan_pembelian ppp');
	
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['nama']= $this->crud_global->GetField('penerima',array('id'=>$row['supplier_id']),'nama');
                $row['tanggal_invoice'] = date('d F Y',strtotime($row['tanggal_invoice']));
                $row['nomor_invoice']= $row['nomor_invoice'];
                $row['actions'] = '<a href="javascript:void(0);" onclick="OpenFormMain('.$row['id'].')" class="btn btn-success">Update Tagihan </a>';
                
                $data[] = $row;
            }

        }
        
        return $data;   
    }

    function TableMainTagihanPenjualan($id)
    {
        $data = array();
        $this->db->select('ppp.*');
        $this->db->where('ppp.id',$id);
        $this->db->order_by('ppp.id','asc');
        $query = $this->db->get('pmm_penagihan_penjualan ppp');
	
        if($query->num_rows() > 0){
            foreach ($query->result_array() as $key => $row) {
                $row['no'] = $key+1;
                $row['nama']= $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'nama');
                $row['tanggal_invoice'] = date('d F Y',strtotime($row['tanggal_invoice']));
                $row['nomor_invoice']= $row['nomor_invoice'];
                $row['actions'] = '<a href="javascript:void(0);" onclick="OpenFormMain('.$row['id'].')" class="btn btn-success">Update Tagihan </a>';
                
                $data[] = $row;
            }

        }
        
        return $data;   
    }

    //RUMUS BARU//

    function GetReceiptMatBukuBesar($filter_client_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_product=false)
    {
        $output = array();

        $this->db->select('c.coa, t.id as transaction_id, t.akun, t.tanggal_transaksi, t.transaksi, b.nomor_transaksi as no_trx_1, j.nomor_transaksi as no_trx_2, pdb.deskripsi as dex_1, j.memo as dex_2, t.debit as debit, t.kredit as kredit');
        $this->db->join('pmm_biaya b','t.biaya_id = b.id','left');
        $this->db->join('pmm_detail_biaya pdb','b.id = pdb.biaya_id','left');
        $this->db->join('pmm_jurnal_umum j','t.jurnal_id = j.id','left');
        $this->db->join('pmm_detail_jurnal pdj','j.id = pdj.jurnal_id','left');
        $this->db->join('pmm_coa c','t.akun = c.id','left');
        if(!empty($start_date) && !empty($end_date)){;
            $this->db->where('t.tanggal_transaksi >=',$start_date);
            $this->db->where('t.tanggal_transaksi <=',$end_date);
        }
        if(!empty($filter_client_id)){
            $this->db->where('c.coa',$filter_client_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppo.id',$purchase_order_no);
        }
        if(!empty($filter_product)){
            $this->db->where_in('pp.product_id',$filter_product);
        }
		
        $this->db->order_by('t.tanggal_transaksi','asc');
        $this->db->order_by('t.id','asc');
        $this->db->group_by('t.id');
        $query = $this->db->get('transactions t');
        $output = $query->result_array();
	
        return $output;
    }

    function GetPenerimaanPembelian($supplier_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_material=false,$filter_kategori=false)
    {
        $output = array();

        $this->db->select('ppo.supplier_id, prm.purchase_order_id, prm.display_measure as measure,p.nama_produk,prm.material_id,SUM(prm.display_price) / SUM(prm.display_volume) as price,SUM(prm.display_volume) as volume, SUM(prm.display_price) as total_price');
        $this->db->join('produk p','prm.material_id = p.id','left');
        $this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('prm.date_receipt >=',$start_date);
            $this->db->where('prm.date_receipt <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppo.id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('prm.material_id',$filter_material);
        }
        if(!empty($filter_kategori)){
            $this->db->where_in('ppo.kategori_id',$filter_kategori);
        }
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
        $this->db->order_by('p.nama_produk','asc');
        $this->db->group_by('prm.material_id');
        $query = $this->db->get('pmm_receipt_material prm');
        $output = $query->result_array();
		
        return $output;
    }
	
	function GetPenerimaanPembelianPrint($supplier_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_material=false,$filter_kategori=false)
    {
        $output = array();

        $this->db->select('prm.purchase_order_id, prm.display_measure as measure,p.nama_produk,prm.material_id,SUM(prm.display_price) / SUM(prm.display_volume) as price,SUM(prm.display_volume) as volume, SUM(prm.display_price) as total_price');
        $this->db->join('produk p','prm.material_id = p.id','left');
        $this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('prm.date_receipt >=',$start_date);
            $this->db->where('prm.date_receipt <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppo.id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('prm.material_id',$filter_material);
        }
        if(!empty($filter_kategori)){
            $this->db->where_in('ppo.kategori_id',$filter_kategori);
        }
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
        $this->db->order_by('p.nama_produk','asc');
        $this->db->group_by('prm.material_id');
        $query = $this->db->get('pmm_receipt_material prm');
        $output = $query->result_array();
		
        return $output;
    }

    function GetLaporanHutang($supplier_id=false,$start_date=false,$end_date=false,$filter_kategori=false)
    {
        $output = array();

        $this->db->select('prm.purchase_order_id, p.nama_produk, SUM(prm.display_price) as penerimaan,
        (
            select SUM(ppd.total) 
            from pmm_penagihan_pembelian_detail ppd 
            inner join pmm_penagihan_pembelian ppp 
            on ppd.penagihan_pembelian_id = ppp.id 
            where ppp.purchase_order_id = prm.purchase_order_id
            and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'"
		) as tagihan,
        SUM(prm.display_price) -
        (
            select  COALESCE(SUM(ppd.total),0) 
            from pmm_penagihan_pembelian_detail ppd 
            inner join pmm_penagihan_pembelian ppp 
            on ppd.penagihan_pembelian_id = ppp.id 
            where ppp.purchase_order_id = prm.purchase_order_id
            and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'"
		) as tagihan_bruto,
        (
            select COALESCE(SUM(pppp.total),0)
            from pmm_pembayaran_penagihan_pembelian pppp 
            inner join pmm_penagihan_pembelian ppp 
            on pppp.penagihan_pembelian_id = ppp.id 
            where ppp.purchase_order_id = prm.purchase_order_id
            and pppp.memo <> "PPN"
            and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'"
        ) as pembayaran,
        SUM(prm.display_price) - 
        (
            select COALESCE(SUM(pppp.total),0)
            from pmm_pembayaran_penagihan_pembelian pppp 
            inner join pmm_penagihan_pembelian ppp 
            on pppp.penagihan_pembelian_id = ppp.id 
            where ppp.purchase_order_id = prm.purchase_order_id
            and pppp.memo <> "PPN"
            and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'"
        ) as sisa_hutang_penerimaan,
        (
            select SUM(ppd.total) 
            from pmm_penagihan_pembelian_detail ppd 
            inner join pmm_penagihan_pembelian ppp 
            on ppd.penagihan_pembelian_id = ppp.id 
            where ppp.purchase_order_id = prm.purchase_order_id
            and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'"
		) - 
        (
            select COALESCE(SUM(pppp.total),0)
            from pmm_pembayaran_penagihan_pembelian pppp 
            inner join pmm_penagihan_pembelian ppp 
            on pppp.penagihan_pembelian_id = ppp.id 
            where ppp.purchase_order_id = prm.purchase_order_id
            and pppp.memo <> "PPN"
            and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'"
        ) as sisa_hutang_tagihan');
        $this->db->join('produk p','prm.material_id = p.id','left');
        $this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('prm.date_receipt >=',$start_date);
            $this->db->where('prm.date_receipt <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppo.supplier_id',$supplier_id);
        }
        if(!empty($filter_kategori)){
            $this->db->where_in('ppo.kategori_id',$filter_kategori);
        }
		$this->db->where("ppo.status in ('PUBLISH','CLOSED')");
        $this->db->order_by('ppo.date_po','asc');
        $this->db->group_by('prm.purchase_order_id');
        $query = $this->db->get('pmm_receipt_material prm');
        $output = $query->result_array();
		
        return $output;
    }

    function GetLaporanMonitoringHutang($supplier_id=false,$start_date=false,$end_date=false,$filter_kategori=false,$filter_status=false)
    {
        $output = array();

        $this->db->select('ppp.*, pvp.tanggal_lolos_verifikasi, pvp.status_umur_hutang, ps.nama, ppo.subject,
        (select COALESCE(sum(total),0) from pmm_penagihan_pembelian_detail ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(tax),0) from pmm_penagihan_pembelian_detail ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'" and ppd.tax_id = "5") as dpp_tagihan,
        (select COALESCE(sum(ppn),0) from pmm_verifikasi_penagihan_pembelian ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") as ppn_tagihan,
        (select COALESCE(sum(pph),0) from pmm_verifikasi_penagihan_pembelian ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") as pph_tagihan,
        (select COALESCE(sum(total),0) from pmm_penagihan_pembelian_detail ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") +  (select COALESCE(sum(ppn),0) from pmm_verifikasi_penagihan_pembelian ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") as jumlah_tagihan,

        (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as dpp_pembayaran,
        (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as ppn_pembayaran,
        (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPH" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as pph_pembayaran,
        (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") + (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") - (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPH" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as jumlah_pembayaran,

        (select COALESCE(sum(total),0) from pmm_penagihan_pembelian_detail ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(tax),0) from pmm_penagihan_pembelian_detail ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'" and ppd.tax_id = "5") - (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'")as dpp_sisa_hutang,
        (select COALESCE(sum(ppn),0) from pmm_verifikasi_penagihan_pembelian ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as ppn_sisa_hutang,
        (select COALESCE(sum(pph),0) from pmm_verifikasi_penagihan_pembelian ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") -  (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPH" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as pph_sisa_hutang,
        ((select COALESCE(sum(total),0) from pmm_penagihan_pembelian_detail ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(tax),0) from pmm_penagihan_pembelian_detail ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'" and ppd.tax_id = "5") - (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'")) + (select COALESCE(sum(ppn),0) from pmm_verifikasi_penagihan_pembelian ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") + (select COALESCE(sum(pph),0) from pmm_verifikasi_penagihan_pembelian ppd where ppd.penagihan_pembelian_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") -  (select COALESCE(sum(total),0) from pmm_pembayaran_penagihan_pembelian pppp where pppp.penagihan_pembelian_id = ppp.id and pppp.memo = "PPH" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as jumlah_sisa_hutang
        ');
        $this->db->join('penerima ps','ppp.supplier_id = ps.id','left');
        $this->db->join('pmm_verifikasi_penagihan_pembelian pvp','ppp.id = pvp.penagihan_pembelian_id','left');
        $this->db->join('pmm_purchase_order ppo','ppp.purchase_order_id = ppo.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($supplier_id)){
            $this->db->where('ppp.supplier_id',$supplier_id);
        }
        if(!empty($filter_kategori)){
            $this->db->where_in('ppo.kategori_id',$filter_kategori);
        }
        if(!empty($filter_status)){
            $this->db->where_in('ppp.status',$filter_status);
        }
        $this->db->order_by('ppp.tanggal_invoice','asc');
        $this->db->group_by('ppp.id');
        $query = $this->db->get('pmm_penagihan_pembelian ppp');
        $output = $query->result_array();
		
        return $output;
    }

    function GetReceiptMat17($filter_client_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_product=false)
    {
        $output = array();

        $this->db->select('pp.salesPo_id, pp.measure, p.nama_produk, SUM(pp.display_volume) as total, SUM(pp.display_price) / SUM(pp.display_volume) as price, SUM(pp.display_price) as total_price');
        $this->db->join('produk p','pp.product_id = p.id','left');
        $this->db->join('pmm_sales_po ppo','pp.salesPo_id = ppo.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($filter_client_id)){
            $this->db->where('pp.client_id',$filter_client_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('ppo.id',$purchase_order_no);
        }
        if(!empty($filter_product)){
            $this->db->where_in('pp.product_id',$filter_product);
        }
		
		$this->db->where('pp.status','PUBLISH');
        $this->db->where("ppo.status in ('OPEN','CLOSED')");
        $this->db->where("pp.product_id in (3,4,7,8,9,14,24,35,36,37,38)");
        $this->db->order_by('p.nama_produk','asc');
        $this->db->group_by('pp.product_id');
        $query = $this->db->get('pmm_productions pp');
        $output = $query->result_array();
		
        return $output;
    }

    function GetLaporanPiutang($client_id=false,$start_date=false,$end_date=false)
    {
        $output = array();

        $this->db->select('pp.salesPo_id, p.nama_produk, SUM(pp.display_price) as penerimaan,
        (
            select SUM(ppd.total) 
            from pmm_penagihan_penjualan_detail ppd 
            inner join pmm_penagihan_penjualan ppp 
            on ppd.penagihan_id = ppp.id 
            where ppp.sales_po_id = pp.salesPo_id
            and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'"
		) as tagihan,
        SUM(pp.display_price) -
        (
            select  COALESCE(SUM(ppd.total),0) 
            from pmm_penagihan_penjualan_detail ppd 
            inner join pmm_penagihan_penjualan ppp 
            on ppd.penagihan_id = ppp.id 
            where ppp.sales_po_id = pp.salesPo_id
            and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'"
		) as tagihan_bruto,
        (
            select COALESCE(SUM(pppp.total),0)
            from pmm_pembayaran pppp 
            inner join pmm_penagihan_penjualan ppp 
            on pppp.penagihan_id = ppp.id 
            where ppp.sales_po_id = pp.salesPo_id
            and pppp.memo <> "PPN"
            and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'"
        ) as pembayaran,
        SUM(pp.display_price) - 
        (
            select COALESCE(SUM(pppp.total),0)
            from pmm_pembayaran pppp 
            inner join pmm_penagihan_penjualan ppp 
            on pppp.penagihan_id = ppp.id 
            where ppp.sales_po_id = pp.salesPo_id
            and pppp.memo <> "PPN"
            and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'"
        ) as sisa_piutang_penerimaan,
        (
            select SUM(ppd.total) 
            from pmm_penagihan_penjualan_detail ppd 
            inner join pmm_penagihan_penjualan ppp 
            on ppd.penagihan_id = ppp.id 
            where ppp.sales_po_id = pp.salesPo_id
            and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'"
		) - 
        (
            select COALESCE(SUM(pppp.total),0)
            from pmm_pembayaran pppp 
            inner join pmm_penagihan_penjualan ppp 
            on pppp.penagihan_id = ppp.id 
            where ppp.sales_po_id = pp.salesPo_id
            and pppp.memo <> "PPN"
            and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'"
        ) as sisa_piutang_tagihan');
        $this->db->join('produk p','pp.product_id = p.id','left');
        $this->db->join('pmm_sales_po po','pp.salesPo_id = po.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pp.date_production >=',$start_date);
            $this->db->where('pp.date_production <=',$end_date);
        }
        if(!empty($client_id)){
            $this->db->where('po.client_id',$client_id);
        }
		$this->db->where("po.status in ('OPEN','CLOSED')");
        $this->db->order_by('po.contract_date','asc');
        $this->db->group_by('pp.salesPo_id');
        $query = $this->db->get('pmm_productions pp');
        $output = $query->result_array();
		
        return $output;
    }

    function GetLaporanMonitoringPiutang($client_id=false,$start_date=false,$end_date=false,$filter_kategori=false,$filter_status=false)
    {
        $output = array();

        $this->db->select('ppp.*, ps.nama, po.jobs_type as subject,
        (select COALESCE(sum(total),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") as dpp_tagihan,
        (select COALESCE(sum(tax),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") as ppn_tagihan,
        (select COALESCE(sum(total),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") +  (select COALESCE(sum(tax),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") as jumlah_tagihan,

        (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as dpp_pembayaran,
        (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as ppn_pembayaran,
        (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") + (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as jumlah_pembayaran,
        
        (select COALESCE(sum(total),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as dpp_sisa_piutang,
        (select COALESCE(sum(tax),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as ppn_sisa_piutang,

        (select COALESCE(sum(total),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo <> "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") + (select COALESCE(sum(tax),0) from pmm_penagihan_penjualan_detail ppd where ppd.penagihan_id = ppp.id and ppp.tanggal_invoice >= "'.$start_date.'"  and ppp.tanggal_invoice <= "'.$end_date.'") - (select COALESCE(sum(total),0) from pmm_pembayaran pppp where pppp.penagihan_id = ppp.id and pppp.memo = "PPN" and pppp.tanggal_pembayaran >= "'.$start_date.'"  and pppp.tanggal_pembayaran <= "'.$end_date.'") as jumlah_sisa_piutang
        ');
        $this->db->join('penerima ps','ppp.client_id = ps.id','left');
        $this->db->join('pmm_sales_po po','ppp.sales_po_id = po.id','left');
        if(!empty($start_date) && !empty($end_date)){
            $this->db->where('ppp.tanggal_invoice >=',$start_date);
            $this->db->where('ppp.tanggal_invoice <=',$end_date);
        }
        if(!empty($client_id)){
            $this->db->where('ppp.client_id',$client_id);
        }
        if(!empty($filter_kategori)){
            $this->db->where_in('po.kategori_id',$filter_kategori);
        }
        if(!empty($filter_status)){
            $this->db->where_in('ppp.status_pembayaran',$filter_status);
        }
        $this->db->order_by('ppp.tanggal_invoice','asc');
        $this->db->group_by('ppp.id');
        $query = $this->db->get('pmm_penagihan_penjualan ppp');
        $output = $query->result_array();
		
        return $output;
    }

    function GetReceiptMat8a($supplier_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_material=false)
    {
        $output = array();

       $this->db->select('pph.id, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e');
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		//$this->db->join('pmm_kalibrasi_detail pkd', 'pk.id = pkd.kalibrasi_id','left');
		//$this->db->join('produk p','pkd.product_id = p.id','left');
        
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
		
		if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pph.produksi_harian_id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
		
		$this->db->group_by('pph.id');
        $query = $this->db->get('pmm_produksi_harian pph');
		
        $output = $query->result_array();
        return $output;
    }

    function GetReceiptMatCampuran($supplier_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_material=false)
    {
        $output = array();

       $this->db->select('pph.id, (SUM(pphd.volume_convert) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.volume_convert) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.volume_convert) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.volume_convert) * pk.presentase_d) / 100 AS jumlah_pemakaian_d, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d');
		
		$this->db->join('pmm_produksi_campuran_detail pphd', 'pph.id = pphd.produksi_campuran_id','left');
		$this->db->join('pmm_agregat pk', 'pphd.product_id = pk.id','left');
        
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
		
		if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pph.produksi_campuran_id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
		
        $this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.id');
        $query = $this->db->get('pmm_produksi_campuran pph');
		
        $output = $query->result_array();
        return $output;
    }

    function GetReceiptMat8($supplier_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_material=false)
    {
        $output = array();

        $this->db->select('pph.id, pk.jobs_type, pk.no_kalibrasi, pph.date_prod, pph.date_prod, pphd.duration, (pphd.use / pphd.duration) as capacity, pphd.use as used');
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
        
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
		
		if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pph.produksi_harian_id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
		
        $this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pphd.id','asc');
        $query = $this->db->get('pmm_produksi_harian pph');
		
        $output = $query->result_array();
        return $output;
    }

    function GetReceiptMat8b($supplier_id=false,$purchase_order_no=false,$start_date=false,$end_date=false,$filter_material=false)
    {
        $output = array();

       $this->db->select('pph.id, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e');
		
		$this->db->join('pmm_produksi_harian_detail pphd', 'pph.id = pphd.produksi_harian_id','left');
		$this->db->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left');
		//$this->db->join('pmm_kalibrasi_detail pkd', 'pk.id = pkd.kalibrasi_id','left');
		//$this->db->join('produk p','pkd.product_id = p.id','left');
        
		if(!empty($start_date) && !empty($end_date)){
            $this->db->where('pph.date_prod >=',$start_date);
            $this->db->where('pph.date_prod <=',$end_date);
        }
		
		if(!empty($supplier_id)){
            $this->db->where('pph.no_prod',$supplier_id);
        }
        if(!empty($purchase_order_no)){
            $this->db->where('pph.id',$purchase_order_no);
        }
        if(!empty($filter_material)){
            $this->db->where_in('ppd.material_id',$filter_material);
        }
		$this->db->where('pph.status','PUBLISH');
		$this->db->group_by('pph.id');
        $query = $this->db->get('pmm_produksi_harian pph');
		
        $output = $query->result_array();
        return $output;
    }

}
?>