<?php

class Pmm_reports extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('crud_global');
        $this->load->model('pmm/pmm_model');
    }


    function GetProByClient($client,$product,$arr_date)
    {
        $this->db->select('SUM(price) as total, SUM(volume) as volume, product_id');
        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $this->db->where('date_production >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date_production <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where('status','PUBLISH');
        $this->db->where('client_id',$client);
        $this->db->where('product_id',$product);
        $query = $this->db->get_where('pmm_productions')->row_array();

        return $query;

    }

    function RevenueTagProByClient($client,$arr_date)
    {

    	$this->db->select('SUM(pp.price) as total, SUM(pp.volume) as volume, pp.product_id, p.product');
		$this->db->join('pmm_product p','pp.product_id = p.id','left');
        if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $this->db->where('pp.date_production >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('pp.date_production <=',date('Y-m-d',strtotime($ex_date[1])));
        }
        $this->db->where('pp.status','PUBLISH');
        $this->db->where('pp.client_id',$client);
        $this->db->group_by('pp.product_id');
        $query = $this->db->get_where('pmm_productions pp')->result_array();
        return $query;

    }




   

    function SumReceiptMaterialsDetail($id,$measure,$date,$before=false)
    {
    	$output = array();
    	$this->db->select('SUM(prm.display_volume) as volume, SUM(prm.display_volume * prm.display_price) as total, prm.display_measure');
    	$this->db->where('prm.material_id',$id);
		if($before){
			$this->db->where('prm.date_receipt <',$date[0]);
		}else {
			$this->db->where('prm.date_receipt >=',$date[0]);
			$this->db->where('prm.date_receipt <=',$date[1]);	
		}
	
		$materials = $this->db->get('pmm_receipt_material prm')->row_array(); 	

		$output['volume'] = 0;
		$output['total'] = 0;
		if(!empty($materials)){
			$output['volume'] = $materials['volume'];
			$output['total'] = $materials['total'];
		}
		
		return $output;
    }

    function SumReceiptMaterialsDetailbySupp($id,$date,$before=false)
    {
    	$output = array();
    	$this->db->select('SUM(prm.display_volume) as volume, SUM(prm.display_volume * prm.display_price) as total, prm.display_measure');
    	$this->db->where('ppo.supplier_id',$id);
		if($before){
			$this->db->where('prm.date_receipt <',$date[0]);
		}else {
			$this->db->where('prm.date_receipt >=',$date[0]);
			$this->db->where('prm.date_receipt <=',$date[1]);	
		}
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
		$this->db->group_by('ppo.supplier_id');
		$materials = $this->db->get('pmm_purchase_order ppo')->row_array(); 

		$output['volume'] = 0;
		$output['total'] = 0;
		if(!empty($materials)){
			$output['volume'] = $materials['volume'];
			$output['total'] = $materials['total'];
			$output['measure'] = $materials['display_measure'];
		}
		
		return $output;
    }

    function ReceiptMaterialTagDetailsbyMats($supplier_id,$start_date,$end_date)
    {

    	$query = array();
    	$this->db->select('pm.material_name,prm.price,pm.id,pm.measure');
		$this->db->where(array('pm.status'=>'PUBLISH'));
		$this->db->where('ppo.supplier_id',$supplier_id);
		$this->db->join('pmm_receipt_material prm','ppo.id = prm.purchase_order_id');
		$this->db->join('pmm_materials pm','prm.material_id = pm.id','left');
		$this->db->group_by('prm.material_id');
		$this->db->order_by('pm.material_name','asc');
		$materials = $this->db->get('pmm_purchase_order ppo')->result_array();
		if(!empty($materials)){
			foreach ($materials as $mat) {

				$materials = $this->SumReceiptMaterialsDetail($mat['id'],$mat['measure'],array($start_date,$end_date));
    			$materials_before = $this->SumReceiptMaterialsDetail($mat['id'],$mat['measure'],array($start_date),true);

    			if($materials['volume'] > 0 ){
    				$query[] = array(
	    				'id' => $mat['id'],
	    				'name' =>$mat['material_name'],
	    				'vol_now' => $materials['volume'],
	    				'total_now' => $materials['total'],
	    				'vol_before' => $materials_before['volume'],
	    				'total_before' => $materials_before['total'],
	    				'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$mat['measure']),'measure_name')
	    			);
    			}
				
			}
		}

		return $query;
    }

    function ReceiptMaterialTagDetails($arr_date,$before=false)
    {
    	if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$query = array();

    	$this->db->select('id,name');
    	$this->db->order_by('name','asc');
    	$get_supps = $this->db->get('pmm_supplier')->result_array();
    	if(!empty($get_supps)){
    		foreach ($get_supps as $supp) {
    			$suppliers = $this->SumReceiptMaterialsDetailbySupp($supp['id'],array($start_date,$end_date));
    			$suppliers_before = $this->SumReceiptMaterialsDetailbySupp($supp['id'],array($start_date),true);
    			if($suppliers['volume'] > 0 ){
    				$measure = '';
    				if($suppliers['volume'] > 0 ){
    					$measure = $suppliers['measure'];
    				}else {
    					$measure = $suppliers_before['measure'];
    				}
    				$query[] = array(
	    				'id' => $supp['id'],
	    				'supp_name' =>$supp['name'],
	    				'vol_now' => $suppliers['volume'],
	    				'total_now' => $suppliers['total'],
	    				'vol_before' => $suppliers_before['volume'],
	    				'total_before' => $suppliers_before['total'],
	    				'measure' => $measure,
	    				'materials' => $this->ReceiptMaterialTagDetailsbyMats($supp['id'],$start_date,$end_date)
	    			);
    			}
    		}
    	}

  
		return $query;
    }

    function SumRemainingMaterials($id,$date,$before=false)
    {
    	$this->db->select('SUM(prm.volume) as volume');
		$this->db->join('pmm_remaining_materials prm','pm.id = prm.material_id','left');
		$this->db->where(array('pm.type'=>'BAHAN','pm.status'=>'PUBLISH','pm.tag_id'=>$id));
		if($before){
			$this->db->where('prm.date <',$date[0]);
		}else {
			$this->db->where('prm.date >=',$date[0]);
			$this->db->where('prm.date <=',$date[1]);	
		}
		$materials = $this->db->get('pmm_materials pm')->row_array(); 	

		return $materials;
    }

    function SumReceiptMaterials($id,$date,$price_avg)
    {
    	$query = array();

    	$volume_now =0;
    	$total_now = 0;
    	$volume_before =0;
    	$total_before = 0;
    	$measure = '';
		$this->db->select('pm.id,pm.measure');
		$this->db->where(array('pm.type'=>'BAHAN','pm.status'=>'PUBLISH','pm.tag_id'=>$id));
		$this->db->order_by('pm.material_name','asc');
		$materials = $this->db->get('pmm_materials pm')->result_array();
		if(!empty($materials)){
			foreach ($materials as $mat) {
				
				$now = $this->SumMaterialUsage($price_avg,$mat['id'],$date);
				$before = $this->SumMaterialUsage($price_avg,$mat['id'],$date,true);
				$volume_before += $before['volume'];
				$volume_now += $now['volume'];
				$total_now += $now['total'];	
		    	$total_before += $before['total'];
				$measure = $mat['measure'];

			}
		}
		$query['volume_now'] = $volume_now;
		$query['total_now'] = $total_now;
		$query['volume_before'] = $volume_before;
		$query['total_before'] = $total_before;
		$query['measure'] = $measure;

		return $query;
    }


    



    function MaterialUsageAll($arr_date=false)
    {
    	$output =0;
    	$a = 0;
		$b = false;
		$c = 0;
		$d = false;
		$biaya_mat = 0;
		$biaya_sisa = 0;
		$total = 0;
		$ex_date = array();


		if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $this->db->where('date >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date <=',date('Y-m-d',strtotime($ex_date[1])));
        }
		$this->db->where('status','PUBLISH');
		$this->db->order_by('date','asc');
		$on_site = $this->db->get('pmm_remaining_materials_cat')->result_array();
		if(!empty($on_site)){
			foreach ($on_site as $a_key => $site) {

				
				
				if($site['material_id'] !== $d){
					$b = false;
					$c = 0;
				}
				if($b){
					$check_terima = $this->CheckTerima($site['material_id'],array('date_receipt >'=>$b,'date_receipt <='=>$site['date']));
				}else {
					$check_terima = $this->CheckTerima($site['material_id'],array('date_receipt <='=>$site['date']));
				}

				if($site['material_id'] !== $d){
					$biaya_mat = ($check_terima['total']) - $site['total'];	
				}else {
					$biaya_mat = ($check_terima['total'] + $c) - $site['total'];
					
				}
				
				$c = $site['total'];
				$b = $site['date'];
				$d = $site['material_id'];	

				if(count($on_site) - 1 < $a_key){
					$a = 0;
					$biaya_mat = 0;
				}
				$total += $biaya_mat;
			}
		}

		if(!empty($arr_date)){
			$ex_date = explode(' - ', $arr_date);

		}

		$output = $total;

		return $output;
		
    }

    function EquipmentUsageAll($arr_date=false)
    {
    	$output =0;
    	$a = 0;
		$b = false;
		$c = 0;
		$d = false;
		$biaya_mat = 0;
		$biaya_sisa = 0;
		$total = 0;
		$ex_date = array();


		if($arr_date){
            $ex_date = explode(' - ', $arr_date);
            $this->db->where('date >=',date('Y-m-d',strtotime($ex_date[0])));
            $this->db->where('date <=',date('Y-m-d',strtotime($ex_date[1])));
        }
		$this->db->where('status','PUBLISH');
		$this->db->order_by('tag_id','asc');
		$this->db->order_by('date','asc');
		$on_site = $this->db->get('pmm_remaining_materials_cat')->result_array();
		if(!empty($on_site)){
			foreach ($on_site as $a_key => $site) {

				
				
				if($site['tag_id'] !== $d){
					$b = false;
					$c = 0;
				}
				if($b){
					$check_terima = $this->CheckTerima($site['tag_id'],array('date_receipt >'=>$b,'date_receipt <='=>$site['date']));
				}else {
					$check_terima = $this->CheckTerima($site['tag_id'],array('date_receipt <='=>$site['date']));
				}

				if($site['tag_id'] !== $d){
					$biaya_mat = ($check_terima['total']) - $site['total'];	
				}else {
					$biaya_mat = ($check_terima['total'] + $c) - $site['total'];
					
				}
				
				$c = $site['total'];
				$b = $site['date'];
				$d = $site['tag_id'];	

				if(count($on_site) - 1 < $a_key){
					$a = 0;
					$biaya_mat = 0;
				}
				$total += $biaya_mat;
			}
		}

		if(!empty($arr_date)){
			$ex_date = explode(' - ', $arr_date);

		}

		$output = $total;

		return $output;
		
    }

    function MaterialUsageAllDate($arr_date)
    {
    	
    	$dt = explode(' - ', $arr_date);
		$start_date = date('Y-m-d',strtotime($dt[0]));
		$end_date = date('Y-m-d',strtotime($dt[1]));

    	$query = 0;

    	$this->db->where(array(
    		'pt.status'=>'PUBLISH',
    	));
    	$this->db->order_by('nama_produk','asc');
    	$this->db->group_by('pt.id');
    	$tags = $this->db->get('produk pt')->result_array();
    	if(!empty($tags)){
    		foreach ($tags as $tag) {
    			$now = $this->SumMaterialUsage($tag['id'],array($start_date,$end_date));
    			$query += $now['total'];
    		}
    	}

        return $query;
		
    }

    function EquipmentUsageAllDate($arr_date)
    {
    	
    	$dt = explode(' - ', $arr_date);
		$start_date = date('Y-m-d',strtotime($dt[0]));
		$end_date = date('Y-m-d',strtotime($dt[1]));

    	$query = 0;

    	$this->db->select('pt.id, pt.tag_name');
    	$this->db->where(array(
    		'pt.tag_type'=>'EQUIPMENT',
    		'pt.status'=>'PUBLISH',
    	));
    	$this->db->order_by('tag_name','asc');
    	$this->db->group_by('pt.id');
    	$tags = $this->db->get('pmm_tags pt')->result_array();
    	if(!empty($tags)){
    		foreach ($tags as $tag) {
    			$now = $this->SumMaterialUsage($tag['id'],array($start_date,$end_date));
    			$query += $now['total'];
    		}
    	}

        return $query;
		
    }

    function CheckTerima($tag_id,$arr_where)
    {

    	$this->db->select('SUM(prm.display_volume) as volume, SUM((prm.display_price / prm.convert_value) * display_volume) as total');
    	$this->db->where('prm.material_id',$tag_id);
    	$this->db->where($arr_where);
    	$check_terima = $this->db->get('pmm_receipt_material prm')->row_array();

    	return $check_terima;
    }

    function CheckTerimaDetails($id,$arr_where)
    {

    	$this->db->select('SUM(prm.display_volume) as volume, SUM((prm.cost / prm.convert_value) * display_volume) as total');
    	$this->db->where('prm.penawaran_material_id',$id);
    	$this->db->where($arr_where);
    	$check_terima = $this->db->get('pmm_receipt_material prm')->row_array();

    	return $check_terima;
    }

    function SumMaterialUsage($id,$date,$before=false)
    {
    	$output = [];

		$this->db->select('SUM(volume) as volume');		
		$this->db->where('date_receipt >=',$date[0]);
		$this->db->where('date_receipt <=',$date[1]);
		$this->db->where_in('material_id',$id);
		$all_mats = $this->db->get('pmm_receipt_material pm')->row_array();
		
		//file_put_contents("D:\\all_mats.txt", $this->db->last_query());
		
		$this->db->select('SUM(volume) as volume, price');
		$this->db->where('date >=',$date[0]);
		$this->db->where('date <=',$date[1]);
		$this->db->where('material_id',$id);	
		$this->db->where('status','PUBLISH');
		$this->db->order_by('date','asc');
		$result = $this->db->get('pmm_remaining_materials_cat')->row_array();
		
		//file_put_contents("D:\\result.txt", $this->db->last_query());
		
		$this->db->select('volume');
		$this->db->where('date <=',$date[0]);
		$this->db->where('material_id',$id);	
		$this->db->where('status','PUBLISH');
		$this->db->order_by('date','desc')->limit(1);
		$sisa_bahan_before = $this->db->get('pmm_remaining_materials_cat')->row_array();
		
		//file_put_contents("D:\\sisa_bahan_before.txt", $this->db->last_query());
		
		$sisa_before = $all_mats['volume'] + $sisa_bahan_before['volume'];
		$output['volume'] = $sisa_before - $result['volume'];
		$sisa_pemakaian = $sisa_before - $result['volume'];
		$output['total'] = $sisa_pemakaian * $result['price'];
		
		return $output;
		
    }


    function SumMatUseBySupp($id,$supp_id,$date)
    {
    	$output =array();
    	$a = 0;
    	$biaya_mat = 0;
    	$total_a = 0;
    	$total_biaya_mat = 0;
		$b = false;
		$c = 0;
		
		$this->db->select('pmc.id, pmc.date, pm.total_price as total,pm.volume as volume');
		$this->db->join('pmm_remaining_materials pm','pmc.id = pm.cat_id');
		$this->db->where('pmc.date >=',$date[0]);
		$this->db->where('pmc.date <=',$date[1]);
		$this->db->where('pmc.material_id',$id);	
		$this->db->where('pm.supplier_id',$supp_id);
		$this->db->where('pmc.status','PUBLISH');
		$this->db->order_by('pmc.date','asc');
		$this->db->group_by('pmc.date');
		$on_site = $this->db->get('pmm_remaining_materials_cat pmc')->result_array();
		if(!empty($on_site)){
			foreach ($on_site as $a_key => $site) {

				if($b){
					$check_terima = $this->CheckTerimaSupp($id,$supp_id,array('date_receipt >'=>$b,'date_receipt <='=>$site['date']));

				}else {
					$check_before = $this->CheckSisaSup($id,$supp_id,$date[0]);

					$check_terima = $this->CheckTerimaSupp($id,$supp_id,array('date_receipt >='=>$date[0],'date_receipt <='=>$site['date']));	
					if(!empty($check_before)){
						$check_terima['volume'] += $check_before['volume'];
						$check_terima['total'] += $check_before['total'];

					}else {
						$check_terima['volume'] += 0;
						$check_terima['total'] += 0;
					}
					
				}
				if($b){
					$a = ($check_terima['volume'] + $c ) - $site['volume'];
					$biaya_mat = ($check_terima['total'] + $d) - $site['total'];

				}else {
					$a = ($check_terima['volume'] ) - $site['volume'];
					$biaya_mat = ($check_terima['total']) - $site['total'];

				}

				$c = $site['volume'];
				$b = $site['date'];
				$d = $site['total'];

				if(count($on_site) - 1 < $a_key){
					$a = 0;
					$biaya_mat = 0;
				}

				$total_a += $a;
    			$total_biaya_mat += $biaya_mat;
				
			}
		}

		$output['volume'] = $total_a;
		$output['total'] = $total_biaya_mat;

		return $output;
		
    }

    function CheckSisaSup($id,$supp_id,$date)
    {
    	$this->db->select('pmc.id,pm.total_price as total,pm.volume');
    	$this->db->join('pmm_remaining_materials pm','pmc.id = pm.cat_id');
    	$this->db->where(array('pmc.status'=>'PUBLISH','pmc.date <'=>$date[0],'pmc.material_id'=>$id));
    	$this->db->order_by('pmc.date','desc');
    	$this->db->group_by('pm.supplier_id',$supp_id);
    	$query = $this->db->get('pmm_remaining_materials_cat pmc')->row_array();
    	return $query;

    }


    function CheckTerimaSupp($tag_id,$supp_id,$arr_where)
    {

    	$output = array();
    	$this->db->select('SUM(prm.display_volume) as volume, SUM((prm.cost / prm.convert_value) * display_volume) as total');
    	$this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id');
    	$this->db->where('prm.material_id',$tag_id);
    	$this->db->where('ppo.supplier_id',$supp_id);
    	$this->db->where($arr_where);
    	$check_terima = $this->db->get('pmm_receipt_material prm')->row_array();

    	if(!empty($check_terima['volume'])){
    		$output['volume'] = $check_terima['volume'];
    	}else {
    		$output['volume'] = 0;
    	}
    	if(!empty($check_terima['total'])){
    		$output['total'] = $check_terima['volume'];
    	}else {
    		$output['total'] = 0;
    	}

    	return $check_terima;
    }

    function MatUseBySupp($id,$date,$volume,$total)
    {
    	$output =array();

		$this->db->select('SUM(pm.volume) as volume');		
		$this->db->where('pm.date_receipt >=',$date[0]);
		$this->db->where('pm.date_receipt <=',$date[1]);
		$this->db->where_in('pm.material_id',$id);
		$all_mats = $this->db->get('pmm_receipt_material pm')->row_array();
		
		//file_put_contents("D:\\MatUseBySupp2.txt", $this->db->last_query());


		$this->db->select('ppo.supplier_id, SUM(pm.volume) as volume, ps.nama as supplier');
		$this->db->join('pmm_purchase_order ppo','pm.purchase_order_id = ppo.id');
		$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
		$this->db->where('pm.date_receipt >=',$date[0]);
		$this->db->where('pm.date_receipt <=',$date[1]);
		$this->db->where_in('pm.material_id',$id);
		$this->db->group_by('ppo.supplier_id');
		$query = $this->db->get('pmm_receipt_material pm');

		//file_put_contents("D:\\MatUseBySupp.txt", $this->db->last_query());

		if($query->num_rows() > 0){
			foreach ($query->result_array() as $key => $row) {
				$percen = round(($row['volume'] / $all_mats['volume']) * 100);
				$vol_supp = ($percen * $volume ) / 100;
				$total_supp = ($percen * $total) / 100;
				$output[] = array(
					'supplier' => $row['supplier'],
					'volume' => $vol_supp,
					'total' => $total_supp,
				);
			}
		}
		return $output;
		
    }


    function SumMaterialUsageDetails($tag_id,$id,$date,$before=false)
    {
    	$output =array();
    	$a = 0;
    	$biaya_mat = 0;
    	$total_a = 0;
    	$total_biaya_mat = 0;
		$b = false;
		$c = 0;
		
		if($before){
			$this->db->where('date <',$date[0]);
		}else {
			$this->db->where('date >=',$date[0]);
			$this->db->where('date <=',$date[1]);
		}
		$this->db->where('material_id',$tag_id);	
		$this->db->where('status','PUBLISH');
		$this->db->order_by('date','asc');
		$on_site = $this->db->get('pmm_remaining_materials_cat')->result_array();
		if(!empty($on_site)){
			foreach ($on_site as $a_key => $site) {

				if($b){
					$check_terima = $this->CheckTerimaDetails($id,array('date_receipt >'=>$b,'date_receipt <='=>$site['date']));
					// echo 'asd';
				}else {
					if($before){
						$check_before = $this->db->select('total_price as total,volume')->order_by('date','desc')->get_where('pmm_remaining_materials',array('status'=>'PUBLISH','date <'=>$site['date'],'material_id'=>$id))->row_array();
						$check_terima = $this->CheckTerimaDetails($id,array('date_receipt <='=>$site['date']));

					}else {
						$check_before = $this->db->select('total_price as total,volume')->order_by('date','desc')->get_where('pmm_remaining_materials',array('status'=>'PUBLISH','date <'=>$date[0],'material_id'=>$id))->row_array();

						$check_terima = $this->CheckTerimaDetails($id,array('date_receipt >='=>$date[0],'date_receipt <='=>$site['date']));
					}
					
					// echo $check_before['volume'].' - '.$date[0].' = ';
					$check_terima['volume'] += $check_before['volume'];
					$check_terima['total'] += $check_before['total'];
				}

				if($b){
					$a = ($check_terima['volume'] + $c ) - $site['volume'];
					$biaya_mat = ($check_terima['total'] + $d) - $site['total'];
				}else {
					$a = ($check_terima['volume'] ) - $site['volume'];
					$biaya_mat = ($check_terima['total']) - $site['total'];
				}
				// echo $b.'<br />'.$site['tag_id'].' = '.$site['date'].' = '.number_format($check_terima['volume'],2,',','.').' - '.number_format($site['volume'],2,',','.').' = '.number_format($a,2,',','.').'<br />';
				$c = $site['volume'];
				$b = $site['date'];
				$d = $site['total'];

				if(count($on_site) - 1 < $a_key){
					$a = 0;
					$biaya_mat = 0;
				}

				// if($before){
				// 	$total_a += $a;
    // 				$total_biaya_mat += $biaya_mat;
				// }
				$total_a += $a;
    			$total_biaya_mat += $biaya_mat;
				
			}
		}

		// if($before){
			$output['volume'] = $total_a;
			$output['total'] = $total_biaya_mat;
		// }else {
		// 	$output['volume'] = $total_a;
		// 	$output['total'] = $total_biaya_mat;	
		// }
		

		return $output;
		
    }

    

    function MaterialUsageReal($arr_date)
    {

		if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$query = array();

    	// $this->db->select('pt.id, pt.tag_name');
    	$this->db->where(array(
    		// 'pt.tag_type'=>'MATERIAL',
    		'status'=>'PUBLISH',
    	));
    	$this->db->order_by('material_name','asc');
    	$this->db->group_by('id');
    	$tags = $this->db->get('pmm_materials')->result_array();
    	if(!empty($tags)){
    		$total_now = 0;
    		$total_before = 0;
    		$vol_now = 0;
    		$vol_before = 0;
    		foreach ($tags as $tag) {

    			// $price_avg = $this->db->select('AVG(price) as pri
    			$now = $this->SumMaterialUsage($tag['id'],array($start_date,$end_date));
    			$before = $this->SumMaterialUsage($tag['id'],array($start_date,$end_date),true);
    			// $now = array('volume'=>0,'total'=>0);
    			$measure_id = $tag['measure'];

    			if($now['volume'] > 0 || $before['volume'] > 0){
		        	// $vol_real_now = $terima_bahan['volume'] - $sisa['volume'];
		        	// $vol_real_before = $terima_bahan_before['volume'] - $sisa_before['volume'];
		        	$query[] = array(
			        	'vol_now' => $now['volume'],
			        	'vol_before' => $before['volume'],
			        	'total_now' => $now['total'],
			        	'total_before' => $before['total'],
			        	'id' => $tag['id'],
			        	'name' => $tag['material_name'],
			        	'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$measure_id),'measure_name')
			        );
			        // $total_now += $now['total'];
		        	$total_now += $now['total'];
		    		$total_before += $before['total'];
		    		$vol_now += $now['volume'];
		    		$vol_before += $before['volume'];
    			}

    			
    		}
    		// $query['total_now'] = $total_now;
    		// $query['total_before'] = $total_before;
    		// $query['vol_now'] = $vol_now;
    		// $query['vol_before'] = $vol_before;

    	}

        return $query;

    }

    function MaterialUsageDetails($id,$arr_date)
    {
    	if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	} 

    	$date = array($start_date,$end_date);

    	$price_avg = $this->db->select('AVG(price) as price_avg')->get_where('pmm_materials',array('type'=>'BAHAN','status'=>'PUBLISH','tag_id'=>$id))->row_array();

    	$query = array();

    	$volume_now =0;
    	$total_now = 0;
    	$volume_before =0;
    	$total_before = 0;
    	$measure = '';
		$this->db->select('pm.id,pm.measure,pm.material_name');
		$this->db->where(array('pm.type'=>'BAHAN','pm.status'=>'PUBLISH','pm.tag_id'=>$id));
		$this->db->order_by('pm.material_name','asc');
		$materials = $this->db->get('pmm_materials pm')->result_array();
		if(!empty($materials)){
			foreach ($materials as $mat) {

				

				// $materials = $this->SumReceiptMaterialsDetail($mat['id'],$mat['measure'],array($date[0],$date[1]));
    // 			$materials_before = $this->SumReceiptMaterialsDetail($mat['id'],$mat['measure'],array($date[0]),true);

				// $volume_now += $materials['volume'];
				// $total_now += $materials['total'];
				// $volume_before += $materials_before['volume'];
				// $total_before += $materials_before['total'];
				// $measure = $mat['measure'];
				$now = $this->SumMaterialUsage($price_avg['price_avg'],$mat['id'],$date);
				$before = $this->SumMaterialUsage($price_avg['price_avg'],$mat['id'],$date,true);
				$volume_before = $before['volume'];
				$volume_now = $now['volume'];
				$total_now = $now['total'];	
		    	$total_before = $before['total'];

		    	if($volume_now > 0){
		    		$arr['volume_now'] = $volume_now;
					$arr['total_now'] = $total_now;
					$arr['volume_before'] = $volume_before;
					$arr['total_before'] = $total_before;
					$arr['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$mat['measure']),'measure_name');
					$arr['name'] = $mat['material_name'];

					$query[] = $arr;
		    	}

				

			}
		}
		

		return $query;
    }

    function MaterialRemainingReal($arr_date)
    {


    	$query = array();
    	$query = array();

    	// $this->db->select('pt.id, pt.tag_name');
    	$this->db->where(array(
    		// 'pt.tag_type'=>'MATERIAL',
    		'pt.status'=>'PUBLISH',
    	));
    	$this->db->group_by('pt.id');
    	$tags = $this->db->get('pmm_materials pt')->result_array();
    	if(!empty($tags)){
    		foreach ($tags as $tag) {

	    		$measure_id = $tag['measure'];
	    		$now = $this->db->limit(1)->order_by('date','desc')->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','material_id'=>$tag['id'],'date >='=>$arr_date[0],'date <='=>$arr_date[1]))->row_array();

		      	$before = $this->db->limit(1)->order_by('date','desc')->get_where('pmm_remaining_materials_cat',array('status'=>'PUBLISH','material_id'=>$tag['id'],'date <'=>$arr_date[0]))->row_array();

		      	if(!empty($now)){
		      		if($now['volume'] > 0 || $before['volume'] > 0){
			      		$query[] = array(
				        	'total_now' => $now['total'],
				        	'total_before' => $before['total'],
				        	'total' => $now['total'] + $before['total'],
				        	'vol_now' => $now['volume'],
				        	'vol_before' => $before['volume'],
				        	'vol' => $now['volume'] + $before['volume'],
				        	'id' => $tag['id'],
				        	'name' => $tag['material_name'],
				        	'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$measure_id),'measure_name')
				        );
			      	}
		      	}
		      	
    		}
	        
		}
        return $query;

    }

    function MaterialRemainingCompo($arr_date)
    {

    	$query = array();

    	// $this->db->select('pt.id, pt.tag_name');
    	$this->db->where(array(
    		// 'pt.tag_type'=>'MATERIAL',
    		'pt.status'=>'PUBLISH',
    	));
    	$this->db->group_by('pt.id');
    	$tags = $this->db->get('pmm_materials pt')->result_array();
    	if(!empty($tags)){
    		foreach ($tags as $tag) {
    			$now = $this->SumMaterialRemainingCompo($tag['id'],$arr_date);
				$before = $this->SumMaterialRemainingCompo($tag['id'],$arr_date,true);

				if($now['volume'] !== 0){
					$query[] = array(
			        	'vol_now' => $now['volume'],
			        	'vol_before' => $before['volume'],
			        	'vol' => $now['volume'] + $before['volume'],
			        	'id' => $tag['id'],
			        	'name' => $tag['material_name'],
			        	'measure' => $now['measure']
			        );
				}
    		}
    	}

        return $query;
    }

    function SumMaterialRemainingCompo($id,$date,$before=false)
    {
    	$output = array();	

    	$this->db->select('
    		SUM(pp.volume * ppm.koef) as volume,
    		pm.measure
    	');
    	$this->db->join('pmm_production_material ppm','pp.id = ppm.production_id');
    	$this->db->join('pmm_materials pm','ppm.material_id = pm.id');
    	$this->db->where('pm.id',$id);
    	if($before){
    		$this->db->where('pp.date_production <',$date[0]);
    	}else {
    		$this->db->where('pp.date_production >=',$date[0]);
    		$this->db->where('pp.date_production <=',$date[1]);	
    	}
    	$this->db->where('pp.status','PUBLISH');
    	$usage = $this->db->get('pmm_productions pp')->row_array();


    	$this->db->select('SUM(prm.display_volume) as volume, prm.display_measure');
    	if($before){
    		$this->db->where('prm.date_receipt <',$date[0]);
    	}else {
    		$this->db->where('prm.date_receipt >=',$date[0]);
    		$this->db->where('prm.date_receipt <=',$date[1]);	
    	}
    	$this->db->join('pmm_materials pm','prm.material_id = pm.id');
    	$this->db->where('pm.id',$id);
    	$receipt = $this->db->get('pmm_receipt_material prm')->row_array();

    	$output['volume'] = $receipt['volume'] - $usage['volume'];

    	if(!empty($usage['measure'])){
    		$output['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$usage['measure']),'measure_name');
    	}else {
    		$output['measure'] = $receipt['display_measure'];
    	}
		return $output;
    }


    function MaterialRemainingCompoDetails($id,$arr_date)
    {
    	$query = array();

    	$this->db->select('id, material_name, measure');
    	$this->db->where(array(
    		'status'=>'PUBLISH',
    		'tag_id' => $id
    	));

    	$mats = $this->db->get('pmm_materials')->result_array();
    	if(!empty($mats)){
    		foreach ($mats as $mat) {

    			$now = $this->SumMaterialRemainingCompoDetail($mat['id'],$arr_date);
				$before = $this->SumMaterialRemainingCompoDetail($mat['id'],$arr_date,true);

				if($now['volume'] !== 0){
					$query[] = array(
			        	'volume_now' => $now['volume'],
			        	'volume_before' => $before['volume'],
			        	'vol' => $now['volume'] + $before['volume'],
			        	'id' => $mat['id'],
			        	'name' => $mat['material_name'],
			        	'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$mat['measure']),'measure_name')
			        );
				}
    		}
    	}

        return $query;
    }

    function SumMaterialRemainingCompoDetail($id,$date,$before=false)
    {
    	$output = array();	

    	$this->db->select('
    		SUM(pp.volume * ppm.koef) as volume,
    	');
    	$this->db->join('pmm_production_material ppm','pp.id = ppm.production_id');
    	$this->db->where('ppm.material_id',$id);
    	if($before){
    		$this->db->where('pp.date_production <',$date[0]);
    	}else {
    		$this->db->where('pp.date_production >=',$date[0]);
    		$this->db->where('pp.date_production <=',$date[1]);	
    	}
    	$this->db->where('pp.status','PUBLISH');
    	$usage = $this->db->get('pmm_productions pp')->row_array();


    	$this->db->select('SUM(prm.display_volume) as volume');
    	if($before){
    		$this->db->where('prm.date_receipt <',$date[0]);
    	}else {
    		$this->db->where('prm.date_receipt >=',$date[0]);
    		$this->db->where('prm.date_receipt <=',$date[1]);	
    	}
    	$this->db->where('prm.material_id',$id);
    	$receipt = $this->db->get('pmm_receipt_material prm')->row_array();

    	$output['volume'] = $receipt['volume'] - $usage['volume'];

		return $output;
    }


    function SumProductionMaterials($id,$date,$before=false,$product_id=false)
    {
    	$this->db->select('SUM(pp.volume * ppm.koef) as volume, SUM(pp.volume * ppm.koef) * ppm.cost as total');
		$this->db->join('pmm_production_material ppm','pm.id = ppm.material_id','left');
		$this->db->join('pmm_productions pp','ppm.production_id = pp.id','left');
		$this->db->where(array('pm.type'=>'BAHAN','pm.status'=>'PUBLISH','pm.id'=>$id));
		if($before){
			$this->db->where('pp.date_production <',$date[0]);
		}else {
			$this->db->where('pp.date_production >=',$date[0]);
			$this->db->where('pp.date_production <=',$date[1]);	
		}
		if($product_id){
			$this->db->where('pp.product_id',$product_id);
		}
		$materials = $this->db->get('pmm_materials pm')->row_array(); 	

		return $materials;
    }

    function MaterialUsageCompoDetails($id,$arr_date,$product_id)
    {
    	if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$query = array();
    	$this->db->select('id, material_name,measure');
		// $this->db->join('pmm_production_material ppm','pm.id = ppm.material_id','left');
		// $this->db->join('pmm_productions pp','ppm.production_id = pp.id','left');
		$this->db->where(array('pm.type'=>'BAHAN','pm.status'=>'PUBLISH','pm.id'=>$id));
		// if($before){
		// 	$this->db->where('pp.date_production <',$date[0]);
		// }else {
		// 	$this->db->where('pp.date_production >=',$date[0]);
		// 	$this->db->where('pp.date_production <=',$date[1]);	
		// }
		$materials = $this->db->get('pmm_materials pm')->result_array(); 	
		if(!empty($materials)){
			foreach ($materials as $mat) {

				$this->db->select('SUM(pp.volume * ppm.koef) as volume, SUM(pp.volume * ppm.koef * ppm.price) as total');
				$this->db->join('pmm_productions pp','ppm.production_id = pp.id','left');
				$this->db->where('ppm.material_id',$mat['id']);
				$this->db->where('pp.date_production >=',$start_date);
				$this->db->where('pp.date_production <=',$end_date);
				if(!empty($product_id)){
					$this->db->where('pp.product_id',$product_id);
				}
				$this->db->where('pp.status','PUBLISH');
				$now = $this->db->get('pmm_production_material ppm')->row_array(); 


				$this->db->select('SUM(pp.volume * ppm.koef) as volume, SUM(pp.volume * ppm.koef * ppm.price) as total');
				$this->db->join('pmm_productions pp','ppm.production_id = pp.id','left');
				$this->db->where('ppm.material_id',$mat['id']);
				$this->db->where('pp.date_production <',$start_date);
				if(!empty($product_id)){
					$this->db->where('pp.product_id',$product_id);
				}
				$this->db->where('pp.status','PUBLISH');
				$before = $this->db->get('pmm_production_material ppm')->row_array(); 

				$volume_before = $before['volume'];
				$volume_now = $now['volume'];
				$total_before = $before['total'];
				$total_now = $now['total'];


				if($volume_now > 0){
		    		$arr['volume_now'] = $volume_now;
					$arr['volume_before'] = $volume_before;
					$arr['total_now'] = $total_now;
					$arr['total_before'] = $total_before;
					$arr['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$mat['measure']),'measure_name');
					$arr['name'] = $mat['material_name'];

					$query[] = $arr;
		    	}
			}
		}

		return $query;
    }

    

    function MaterialUsageCompo($arr_date)
    {

    	if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}


    	$query = array();

    	// $this->db->select('pt.id, pt.tag_name');
    	$this->db->where(array(
    		// 'pt.tag_type'=>'MATERIAL',
    		'pt.status'=>'PUBLISH',
    	));
    	$this->db->order_by('material_name','asc');
    	$this->db->group_by('pt.id');
    	$tags = $this->db->get('pmm_materials pt')->result_array();
    	if(!empty($tags)){
    		foreach ($tags as $tag) {
    			$price_avg = $this->db->select('AVG(price) as price_avg, measure')->get_where('pmm_penawaran_pembelian_detail',array('material_id'=>$tag['id']))->row_array();

    			$vol_now = $this->SumProductionMaterials($tag['id'],array($start_date,$end_date));
    			$vol_before = $this->SumProductionMaterials($tag['id'],array($start_date),true);

    			if($vol_now['volume'] > 0){
		        	$query[] = array(
			        	'vol_now' => $vol_now['volume'],
			        	'vol_before' => $vol_before['volume'],
			        	'vol' => $vol_now['volume'] + $vol_before['volume'],
			        	'total_now' => $vol_now['total'],
			        	'total_before' => $vol_before['total'],
			        	'total' => $vol_now['total'] + $vol_before['total'],
			        	'price' => $price_avg['price_avg'],
			        	'id' => $tag['id'],
			        	'name' => $tag['material_name'],
			        	'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$price_avg['measure']),'measure_name')
			        );

    			}

    			
    		}
    	}
        

        return $query;

    }

    function MaterialUsageCompoProduct($arr_date,$product_id)
    {

    	if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$date = array($start_date,$end_date);
    	$query = array();
    	// $this->db->select('pt.id, pt.tag_name');
    	$this->db->where(array(
    		// 'pt.tag_type'=>'MATERIAL',
    		'pt.status'=>'PUBLISH',
    	));
    	$this->db->order_by('material_name','asc');
    	$this->db->group_by('pt.id');
    	$tags = $this->db->get('pmm_materials pt')->result_array();
    	if(!empty($tags)){
    		foreach ($tags as $tag) {

    			// Komposisi
    			$komposisi = $this->SumMaterialUsageCompoProduct($product_id,$tag['id'],$date);
    			$arr_komposisi = array();
    			if(!empty($komposisi)){
    				if($komposisi['volume'] > 0){
	    				$arr_komposisi = array(
		    				'id' => $tag['id'],
		    				'name' => $tag['material_name'],
		    				'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$komposisi['measure_id']),'measure_name'),
		    				'volume' => $komposisi['volume'],
		    				'total' => $komposisi['total']
		    			);
		    			$query['komposisi'][] = $arr_komposisi;
	    			}
    			}

    			$vol_now = $this->SumProductionMaterials($tag['id'],array($start_date,$end_date),false,$product_id);
    			$vol_before = $this->SumProductionMaterials($tag['id'],array($start_date),true,$product_id);
    			if(!empty($vol_now)){
    				if($vol_now['volume'] > 0){
    					$arr_standart = array(
		    				'id' => $tag['id'],
		    				'name' => $tag['material_name'],
		    				'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$komposisi['measure_id']),'measure_name'),
		    				'volume' => $vol_now['volume'],
		    				'total' => $vol_now['total'],
		    				'volume_before' => $vol_before['volume'],
		    				'total_before' => $vol_before['total']
		    			);
		    			$query['standart'][] = $arr_standart;
    				}
    			}
    			
    			
    		}
    	}
        return $query;
    }

    function SumMaterialUsageCompoProduct($product_id,$tag_id,$date)
    {
    	$output = array();


    	$this->db->select('
    		SUM(pp.volume * ppm.koef) as volume,
    		SUM(pp.volume * ppm.koef * ppm.price) as total,
    		ppm.measure as measure_id,
    	');
    	$this->db->join('pmm_production_material ppm','pp.id = ppm.production_id');
    	$this->db->join('pmm_materials pm','ppm.material_id = pm.id');
    	$this->db->where('pp.date_production >=',$date[0]);
    	$this->db->where('pp.date_production <=',$date[1]);
    	$this->db->where('pp.product_id',$product_id);
    	$this->db->where('pm.id',$tag_id);
    	$this->db->where('pm.type','BAHAN');
    	$this->db->where('pm.status','PUBLISH');
    	$this->db->where('pp.status','PUBLISH');
    	// $this->db->group_by('pm.id');
    	$query = $this->db->get('pmm_productions pp')->row_array();
    	if(!empty($query)){
    		$output['total'] = $query['total'];
    		$output['volume'] = $query['volume'];
    		$output['measure_id'] = $query['measure_id'];

    	}

    	return $output;
    }

    function TotalProductions($product_id,$arr_date)
    {
    	$output = 0;

    	if(empty($arr_date)){
    		$month = date('Y-m');
    		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    		$end_date = $month.'-26';
    	}else {
    		$dt = explode(' - ', $arr_date);
    		$start_date = date('Y-m-d',strtotime($dt[0]));
    		$end_date = date('Y-m-d',strtotime($dt[1]));
    	}

    	$this->db->select('SUM(volume) as volume');
    	$this->db->where('date_production >=',$start_date);
    	$this->db->where('date_production <=',$end_date);
    	$this->db->where('product_id',$product_id);
    	$this->db->where('status','PUBLISH');
    	$query = $this->db->get('pmm_productions')->row_array();

    	return $query['volume'];
    }

    function EquipmentProd($date)
    {	
    
    	$output = array();
    	$this->db->select('SUM(volume) as total_volume');
    	$this->db->where('date_production <',$date[0]);
        $query = $this->db->get_where('pmm_productions')->row_array();

        $this->db->select('SUM(volume) as total_volume');
    	$this->db->where('date_production >=',$date[0]);
    	$this->db->where('date_production <=',$date[1]);
        $query_now = $this->db->get_where('pmm_productions')->row_array();

        $total = $query['total_volume'] + $query_now['total_volume'];
        return array('before'=>$query['total_volume'],'now'=>$query_now['total_volume'],'total'=>$total );

    }

    function EquipmentsData($arr_date,$supplier_id,$tool_id)
    {

    	$output = array();
    	
    	$tags = $this->db->select('id,tag_name')->get_where('pmm_tags',array('tag_type'=>'EQUIPMENT','status'=>'PUBLISH'))->result_array();

    	if(!empty($tags)){
    		$no=1;
    		foreach ($tags as $tag) {
    			$sum_equipments = $this->EquipmentDataSumTags($tag['id'],$arr_date,$supplier_id,$tool_id);
    			if($sum_equipments['total'] > 0){
    				$output[] = array(
    					'no' => $no,
    					'id'=> $tag['id'],
	    				'tool' => $tag['tag_name'],
	    				'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$sum_equipments['measure_id']),'measure_name'),
	    				'insentive_driver' => $sum_equipments['insentive_driver'],
	    				'volume' => $sum_equipments['volume'],
	    				'rental_cost' => $sum_equipments['rental_cost'],
	    				'maintenance_cost' => $sum_equipments['maintenance_cost'],
	    				'total' => $sum_equipments['total'],
	    				'equipments' => $this->EquipmentDataDetails($tag['id'],$arr_date,$supplier_id,$tool_id)
	    			);
	    			$no++;
    			}
    			
    		}
    	}

    	return $output;

    }

    function EquipmentDataSumTags($tag_id,$date,$supplier_id,$tool_id)
    {
    	$output = array();

    	$this->db->select('
			SUM(ped.insentive_driver) as insentive_driver,
			SUM(ped.rental_cost) as rental_cost,
			SUM(ped.maintenance_cost) as maintenance_cost,
			SUM(ped.total) as total,
			SUM(ped.volume) as volume,
			ped.measure_id,
		');
		$this->db->join('pmm_tools pt','pt.id = ped.tool_id');
		$this->db->where('pt.tag_id',$tag_id);
		$this->db->where('ped.status','PUBLISH');
		if(!empty($supplier_id)){
			$this->db->where('ped.supplier_id',$supplier_id);
		}
		if(!empty($tool_id)){
			$this->db->where('ped.tool_id',$tool_id);
		}
		if(!empty($date)){
			$this->db->where('ped.date >=',$date[0]);
			$this->db->where('ped.date <=',$date[1]);
		}
		$query = $this->db->get('pmm_equipments_data ped')->row_array();
		if(!empty($query)){
			$output['total'] = $query['total'];
			$output['insentive_driver'] = $query['insentive_driver'];
			$output['rental_cost'] = $query['rental_cost'];
			$output['maintenance_cost'] = $query['maintenance_cost'];
			$output['measure_id'] = $query['measure_id'];
			$output['volume'] = $query['volume'];
		}

    	return $output;
    }

    function EquipmentDataDetails($tag_id,$date,$supplier_id,$tool_id)
    {
    	$output = array();

    	$this->db->select('
			SUM(ped.insentive_driver) as insentive_driver,
			SUM(ped.rental_cost) as rental_cost,
			SUM(ped.maintenance_cost) as maintenance_cost,
			SUM(ped.total) as total,
			SUM(ped.volume) as volume,
			ped.measure_id,
			pt.tool,
			ps.name as supplier,
			pm.measure_name as measure
		');
		$this->db->join('pmm_tools pt','pt.id = ped.tool_id');
		$this->db->join('pmm_supplier ps','ps.id = ped.supplier_id','left');
		$this->db->join('pmm_measures pm','pm.id = ped.measure_id','left');
		$this->db->where('pt.tag_id',$tag_id);
		$this->db->where('ped.status','PUBLISH');
		if(!empty($supplier_id)){
			$this->db->where('ped.supplier_id',$supplier_id);
		}
		if(!empty($tool_id)){
			$this->db->where('ped.tool_id',$tool_id);
		}
		if(!empty($date[0]) && !empty($date[1])){
			$this->db->where('ped.date >=',$date[0]);
			$this->db->where('ped.date <=',$date[1]);
		}
		$this->db->group_by('pt.id');
		$query = $this->db->get('pmm_equipments_data ped')->result_array();
		$output = $query;

    	return $output;
    }

    function EquipmentReports($arr_date,$supplier_id)
    {

    	$output = array();
    	
    	$tags = $this->db->select('id,tag_name')->get_where('pmm_tags',array('tag_type'=>'EQUIPMENT','status'=>'PUBLISH'))->result_array();

    	if(!empty($tags)){
    		foreach ($tags as $tag) {
    			$now = $this->EquipmentSumTags($tag['id'],$arr_date,$supplier_id);
    			$before = $this->EquipmentSumTags($tag['id'],$arr_date,$supplier_id,true);
    			if($now['total'] > 0 || $now['volume'] > 0){
    				$output[] = array(
    					'id'=> $tag['id'],
	    				'tool' => $tag['tag_name'],
	    				'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$now['measure_id']),'measure_name'),
	    				'before' => $before['total'],
	    				'volume' => $now['volume'],
	    				'now' => $now['total'],
	    				'total' => $now['total'] + $before['total'],
	    				'equipments' => $this->EquipmentReportsDetails($tag['id'],$arr_date,$supplier_id) 
	    			);
    			}
    			
    		}
    	}

    	return $output;

    }

    function EquipmentSumTags($tag_id,$date,$supplier_id,$before=false)
    {
    	$output = array();

    	$this->db->select('
			SUM(ped.total) as total,
			SUM(ped.volume) as volume,
			ped.measure_id
		');
		$this->db->join('pmm_tools pt','pt.id = ped.tool_id');
		$this->db->where('pt.tag_id',$tag_id);
		$this->db->where('ped.status','PUBLISH');
		if(!empty($supplier_id)){
			$this->db->where('supplier_id',$supplier_id);
		}
		if($before){
			if(!empty($date[0]) && !empty($date[1])){
				$this->db->where('ped.date <',$date[0]);
			}
		}else {
			if(!empty($date[0]) && !empty($date[1])){
				$this->db->where('ped.date >=',$date[0]);
				$this->db->where('ped.date <=',$date[1]);
			}	
		}
		
		// $this->db->group_by('tool_id');
		$query = $this->db->get('pmm_equipments_data ped')->row_array();
		if(!empty($query)){
			$output['total'] = $query['total'];
			$output['measure_id'] = $query['measure_id'];
			$output['volume'] = $query['volume'];
		}

    	return $output;
    }

    function EquipmentDetails($tag_id,$date,$supplier_id,$before=false)
    {
    	$output = array();

    	$this->db->select('
			SUM(ped.total) as total,
			SUM(ped.volume) as volume,
			ped.measure_id
		');
		$this->db->join('pmm_tools pt','pt.id = ped.tool_id');
		$this->db->where('pt.tag_id',$tag_id);
		$this->db->where('ped.status','PUBLISH');
		if(!empty($supplier_id)){
			$this->db->where('supplier_id',$supplier_id);
		}
		if($before){
			if(!empty($date[0]) && !empty($date[1])){
				$this->db->where('ped.date <',$date[0]);
			}
		}else {
			if(!empty($date[0]) && !empty($date[1])){
				$this->db->where('ped.date >=',$date[0]);
				$this->db->where('ped.date <=',$date[1]);
			}	
		}
		
		// $this->db->group_by('tool_id');
		$query = $this->db->get('pmm_equipments_data ped')->row_array();
		if(!empty($query)){
			$output['total'] = $query['total'];
			$output['measure_id'] = $query['measure_id'];
			$output['volume'] = $query['volume'];
		}

    	return $output;
    }

    function EquipmentReportsDetails($id,$arr_date,$supplier_id)
    {

    	$output = array();
    	
    	$equipments = $this->db->select('id,tool,measure_id')->get_where('pmm_tools',array('tag_id'=>$id,'status'=>'PUBLISH'))->result_array();

    	if(!empty($equipments)){
    		foreach ($equipments as $eq) {


    			$this->db->select('SUM(total) as total');
    			$this->db->where('date >=',$arr_date[0]);
				$this->db->where('date <=',$arr_date[1]);
    			$this->db->where('tool_id',$eq['id']);
    			if(!empty($supplier_id)){
					$this->db->where('supplier_id',$supplier_id);
				}
    			$now = $this->db->get('pmm_equipments_data')->row_array();


    			$this->db->select('SUM(total) as total,supplier_id');
    			$this->db->where('date <',$arr_date[0]);
    			$this->db->where('tool_id',$eq['id']);
    			if(!empty($supplier_id)){
					$this->db->where('supplier_id',$supplier_id);
				}
    			$before = $this->db->get('pmm_equipments_data')->row_array();


    			$this->db->select('SUM(volume) as volume');
    			$this->db->where('date >=',$arr_date[0]);
				$this->db->where('date <=',$arr_date[1]);
    			$this->db->where('tool_id',$eq['id']);
    			if(!empty($supplier_id)){
					$this->db->where('supplier_id',$supplier_id);
				}
    			$volume = $this->db->get('pmm_equipments_data')->row_array();

    			if($now['total'] > 0){
    				$output[] = array(
    					'id'=> $eq['id'],
	    				'tool' => $eq['tool'],
	    				'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$eq['measure_id']),'measure_name'),
	    				'supplier' => $this->crud_global->GetField('pmm_supplier',array('id'=>$before['supplier_id']),'name'),
	    				'before' => $before['total'],
	    				'now' => $now['total'],
	    				'volume' => $volume['volume'],
	    				'total' => $now['total'] + $before['total']
	    			);
    			}
    			
    		}
    	}
    	return $output;
    }


    function EquipmentUsageReal($arr_date,$check_before=false)
    {

		// if(empty($arr_date)){
  //   		$month = date('Y-m');
  //   		$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
  //   		$end_date = $month.'-26';
  //   	}else {
  //   		$dt = explode(' - ', $arr_date);
  //   		$start_date = date('Y-m-d',strtotime($dt[0]));
  //   		$end_date = date('Y-m-d',strtotime($dt[1]));
  //   	}
    	$start_date = $arr_date[0];
    	$end_date = $arr_date[1];

    	$query = array();

    	$this->db->where(array(
    		'pt.id'=>5,
    		'pt.status'=>'PUBLISH',
    	));
    	$this->db->order_by('material_name','asc');
    	// $this->db->group_by('pt.id');
    	$tags = $this->db->get('pmm_materials pt')->result_array();
    	if(!empty($tags)){
    		$total_now = 0;
    		$vol_now = 0;
    		foreach ($tags as $tag) {

    			// $price_avg = $this->db->select('AVG(price) as pri
    			$now = $this->SumMaterialUsage($tag['id'],array($start_date,$end_date));
    			$vol_before = 0;
    			$total_before = 0;
    			if($check_before){
    				$before = $this->SumMaterialUsage($tag['id'],array($start_date,$end_date),true);
    				$vol_before = $before['volume'];
    				$total_before = $before['total'];
    			}
    			
    			// $now = array('volume'=>0,'total'=>0);
    			$measure_id = $tag['measure'];

    			if($now['volume'] > 0 ){
		        	// $vol_real_now = $terima_bahan['volume'] - $sisa['volume'];
		        	// $vol_real_before = $terima_bahan_before['volume'] - $sisa_before['volume'];
		        	$query[] = array(
			        	'vol_now' => $now['volume'],
			        	'total_now' => $now['total'],
			        	'vol_before' => $vol_before,
			        	'total_before' => $total_before,
			        	'id' => $tag['id'],
			        	'name' => $tag['material_name'],
			        	'measure' => $this->crud_global->GetField('pmm_measures',array('id'=>$measure_id),'measure_name'),
			        	'details' => $this->EquipmentUsageDetails($tag['id'],array($start_date,$end_date),$check_before)
			        );
			        // $total_now += $now['total'];
		        	$total_now += $now['total'];
		    		$vol_now += $now['volume'];
    			}	
    		}

    	}

        return $query;

    }


    function EquipmentUsageDetails($id,$arr_date,$check_before)
    {
    	// if(empty($arr_date)){
    	// 	$month = date('Y-m');
    	// 	$start_date = date('Y-m',strtotime('- 1month '.$month)).'-27';
    	// 	$end_date = $month.'-26';
    	// }else {
    	// 	$dt = explode(' - ', $arr_date);
    	// 	$start_date = date('Y-m-d',strtotime($dt[0]));
    	// 	$end_date = date('Y-m-d',strtotime($dt[1]));
    	// } 


    	$date = $arr_date;
    	$query = array();

    	$volume_now =0;
    	$total_now = 0;
    	$volume_before =0;
    	$total_before = 0;
    	$measure = '';
		$this->db->select('pm.id,pm.measure,pm.material_name');
		$this->db->where(array('pm.status'=>'PUBLISH','pm.id'=>5));
		$this->db->order_by('pm.material_name','asc');
		$materials = $this->db->get('pmm_materials pm')->result_array();
		if(!empty($materials)){
			foreach ($materials as $mat) {

				$now = $this->SumMaterialUsageDetails($id,$mat['id'],$date);
				
				$volume_now = $now['volume'];
				$total_now = $now['total'];	
				$volume_before = 0;
				$total_before = 0;
				if($check_before){
					$before = $this->SumMaterialUsageDetails($id,$mat['id'],$date,true);
					$volume_before = $now['volume'];
					$total_before = $now['total'];
				}


		    	if($volume_now > 0){
		    		$arr['volume_now'] = $volume_now;
					$arr['total_now'] = $total_now;
					$arr['volume_before'] = $volume_before;
					$arr['total_before'] = $total_before;
					$arr['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$mat['measure']),'measure_name');
					$arr['name'] = $mat['material_name'];
					$query[] = $arr;
		    	}

			}
		}
		

		return $query;
    }

}
