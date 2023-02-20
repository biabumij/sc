	

		<?php
		// echo '<pre>';
		// print_r($arr);
		// echo '</pre>';
			
		?>
		<h4>Periode : <?php echo $filter_date;?></h4>
		<hr />
		<h4>Pemakaian Real : </h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="5%">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="15%">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="10%">Satuan</th>
	            <th class="text-center" colspan="6" width="70%">Biaya</th>
	        </tr>
	        <tr class="table-active">
	        	<th class="text-center" width="7%">%</th>
	            <th class="text-center" width="15%">s/d Periode lalu</th>
	            <th class="text-center" width="7%">%</th>
	            <th class="text-center" width="15%">Saat ini</th>
	            <th class="text-center" width="7%">%</th>
	            <th class="text-center" width="19%">Sampai dengan saat ini</th>
	        </tr>
	        <?php
	        $arr_deviasi = array();
	        $arr_deviasi_volume = array();

	        if(!empty($arr)){
	        	$no =1;
	        	$total_before = 0;
	        	$total_now = 0;
	        	$total = 0;
	            foreach ($arr as $val) {
		            $total_before += $val['total_before'];
		        	$total_now += $val['total_now'];
		        	$total += $val['total_before'] + $val['total_now'];
	            }

	            foreach ($arr as $key => $val) {

	            	$persen_before = 0;
	            	if($total_before > 0){
	            		$persen_before = ($val['total_before'] / $total_before) * 100;
	            	}
	            	
	            	$persen_now = 0;
	            	if($total_now > 0){
	            		$persen_now = ($val['total_now'] / $total_now) * 100;
	            	}
	            	$persen_total =0;
	            	if($total > 0){
	            		$persen_total = (($val['total_before'] + $val['total_now']) / $total) * 100;
	            	}
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center"><?php echo number_format($persen_before,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'],2,',','.');?></th>
			           	<th class="text-center"><?php echo number_format($persen_now,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_now'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($persen_total,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'] + $val['total_now'],2,',','.');?></th>
			        </tr>
		            <?php

		            $arr_deviasi[$key]['no'] = $no;
		            $arr_deviasi[$key]['name'] = $val['name'];
		            $arr_deviasi[$key]['measure'] = $val['measure'];
		            $arr_deviasi[$key]['before'] = $val['total_before'];
	            	$arr_deviasi[$key]['now'] = $val['total_now'];
	            	$arr_deviasi[$key]['total'] = $val['total_before'] + $val['total_now'];

		            $no++;

		            
	            	
	            }

	            $total_persen_before = 0;
	            if(!empty($total_revenue_before['total'])){
	            	$total_persen_before = ($total_before / $total_revenue_before['total']) * 100;
	            }
            	
            	$total_persen_now = 0;
            	if(!empty($total_revenue_now['total'])){
            		$total_persen_now = ($total_now / $total_revenue_now['total']) * 100;
            	}
            	$total_persen =0;
            	if(!empty($total_revenue_before['total'])){
            		$total_persen = ($total / ( $total_revenue_before['total'] + $total_revenue_now['total'])) * 100;
            	}
	            
	            
	            
	            ?>
	            <tr>
	            	<th class="text-right" colspan="3">TOTAL</th>
	            	<th class="text-center"><?php echo number_format($total_persen_before,2,',','.');?> %</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_before,2,',','.');?></th>
		           	<th class="text-center"><?php echo number_format($total_persen_now,2,',','.');?> %</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_now,2,',','.');?></th>
		            <th class="text-center"><?php echo number_format($total_persen,2,',','.');?></th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total,2,',','.');?></th>
		        </tr>
	            <?php
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="6">Tidak Ada Data</th>
		        </tr>
	            <?php
	        }
	        
	        ?>

	    </table>
	    <table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="3">Volume</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d Periode lalu</th>
	            <th class="text-center">Saat ini</th>
	            <th class="text-center">Sampai dengan saat ini</th>
	        </tr>
	        <?php
	        if(!empty($arr)){
	        	$no =1;
	            foreach ($arr as $key => $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center"><?php echo number_format($val['vol_before'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($val['vol_now'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($val['vol_before'] + $val['vol_now'],2,',','.');?></th>
			        </tr>
		            <?php
		            $arr_deviasi_volume[$key]['no'] = $no;
		            $arr_deviasi_volume[$key]['name'] = $val['name'];
		            $arr_deviasi_volume[$key]['measure'] = $val['measure'];
		            $arr_deviasi_volume[$key]['before'] = $val['vol_before'];
	            	$arr_deviasi_volume[$key]['now'] = $val['vol_now'];
	            	$arr_deviasi_volume[$key]['total'] = $val['vol_before'] + $val['vol_now'];

		            $no++;
	            	
	            }
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="6">Tidak Ada Data</th>
		        </tr>
	            <?php
	        }
	        
	        ?>

	    </table>

	    <hr />
	    <h4>Pemakaian Komposisi : </h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="5%">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="15%">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="10%">Satuan</th>
	            <th class="text-center" colspan="6" width="70%">Biaya</th>
	        </tr>
	        <tr class="table-active">
	        	<th class="text-center" width="7%">%</th>
	            <th class="text-center" width="15%">s/d Periode lalu</th>
	            <th class="text-center" width="7%">%</th>
	            <th class="text-center" width="15%">Saat ini</th>
	            <th class="text-center" width="7%">%</th>
	            <th class="text-center" width="19%">Sampai dengan saat ini</th>
	        </tr>

	        <?php

	        	

	        if(!empty($arr_compo)){
	        	$no =1;
	        	$total_before = 0;
	        	$total_now = 0;
	        	$total = 0;
	            foreach ($arr_compo as $val) {
	            	$total_before += $val['total_before'];
		        	$total_now += $val['total_now'];
		        	$total += $val['total'];
	            }

	            foreach ($arr_compo as $key => $val) {


	            	$persen_before = 0;
	            	if($total_before > 0){
	            		$persen_before = ($val['total_before'] / $total_before) * 100;
	            	}
	            	
	            	$persen_now = 0;
	            	if($total_now > 0){
	            		$persen_now = ($val['total_now'] / $total_now) * 100;
	            	}
	            	$persen_total =0;
	            	if($total > 0){
	            		$persen_total = (($val['total_before'] + $val['total_now']) / $total) * 100;
	            	}
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center"><?php echo number_format($persen_before,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($persen_now,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_now'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($persen_total,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;

		            $arr_deviasi[$key]['before_compo'] = $val['total_before'];
	            	$arr_deviasi[$key]['now_compo'] = $val['total_now'];
	            	$arr_deviasi[$key]['total_compo'] = $val['total_before'] + $val['total_now'];
	            	
	            }

	            $total_persen_before = 0;
	            if(!empty($total_revenue_before['total'])){
	            	$total_persen_before = ($total_before / $total_revenue_before['total']) * 100;
	            }
            	
            	$total_persen_now = 0;
            	if(!empty($total_revenue_now['total'])){
            		$total_persen_now = ($total_now / $total_revenue_now['total']) * 100;
            	}
            	$total_persen =0;
            	if(!empty($total_revenue_before['total'])){
            		$total_persen = ($total / ( $total_revenue_before['total'] + $total_revenue_now['total'])) * 100;
            	}
	            ?>
	            <tr>
	            	<th class="text-right" colspan="3">TOTAL</th>
	            	<th class="text-center"><?php echo number_format($total_persen_before,2,',','.');?> %</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_before,2,',','.');?></th>
		           	<th class="text-center"><?php echo number_format($total_persen_now,2,',','.');?> %</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_now,2,',','.');?></th>
		            <th class="text-center"><?php echo number_format($total_persen,2,',','.');?> %</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total,2,',','.');?></th>
		        </tr>
	            <?php
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="6">Tidak Ada Data</th>
		        </tr>
	            <?php
	        }
	        
	        ?>

	    </table>

	    <table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="3">Volume</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d Periode lalu</th>
	            <th class="text-center">Saat ini</th>
	            <th class="text-center">Sampai dengan saat ini</th>
	        </tr>

	        <?php
	        if(!empty($arr_compo)){
	        	$no =1;
	            foreach ($arr_compo as $key => $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center"><?php echo number_format($val['vol_before'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($val['vol_now'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($val['vol'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;

		            $arr_deviasi_volume[$key]['before_compo'] = $val['vol_before'];
	            	$arr_deviasi_volume[$key]['now_compo'] = $val['vol_now'];
	            	$arr_deviasi_volume[$key]['total_compo'] = $val['vol_before'] + $val['vol_now'];
	            	
	            }
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="6">Tidak Ada Data</th>
		        </tr>
	            <?php
	        }
	        
	        ?>

	    </table>

	    <hr />
	    <h4>Deviasi Pemakaian : </h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="5%">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="15%">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;" width="10%">Satuan</th>
	            <th class="text-center" colspan="6" width="70%">Biaya</th>
	        </tr>
	        <tr class="table-active">
	        	<th class="text-center" width="7%">%</th>
	            <th class="text-center" width="15%">s/d Periode lalu</th>
	            <th class="text-center" width="7%">%</th>
	            <th class="text-center" width="15%">Saat ini</th>
	            <th class="text-center" width="7%">%</th>
	            <th class="text-center" width="19%">Sampai dengan saat ini</th>
	        </tr>

	        <?php

	        	
	        // print_r($arr_deviasi);
	        if(!empty($arr_deviasi)){
	        	$total_before = 0;
	        	$total_now = 0;
	        	$total = 0;
	            foreach ($arr_deviasi as $val) {
	            	$total_before += $val['before'] - $val['before_compo'];
		        	$total_now += $val['now'] - $val['now_compo'];
		        	$total += $val['total'] - $val['total_compo'];
	            }

	            foreach ($arr_deviasi as $key => $val) {


	            	$persen_before = 0;
	            	if($total_before > 0){
	            		$persen_before = (($val['before'] - $val['before_compo']) / $total_before) * 100;
	            	}
	            	
	            	$persen_now = 0;
	            	if($total_now > 0){
	            		$persen_now = (($val['now'] - $val['now_compo']) / $total_now) * 100;
	            	}
	            	$persen_total =0;
	            	if($total > 0){
	            		$persen_total = (($val['total'] - $val['total_compo']) / $total) * 100;
	            	}
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $val['no'];?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center"><?php echo number_format($persen_before,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['before'] - $val['before_compo'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($persen_now,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['now'] - $val['now_compo'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($persen_total,2,',','.') ;?> %</th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total'] - $val['total_compo'],2,',','.');?></th>
			        </tr>
		            <?php
	            	
	            }

	            $total_persen_before = 0;
	            if(!empty($total_revenue_before['total'])){
	            	$total_persen_before = ($total_before / $total_revenue_before['total']) * 100;
	            }
            	
            	$total_persen_now = 0;
            	if(!empty($total_revenue_now['total'])){
            		$total_persen_now = ($total_now / $total_revenue_now['total']) * 100;
            	}
            	$total_persen =0;
            	if(!empty($total_revenue_before['total'])){
            		$total_persen = ($total / ( $total_revenue_before['total'] + $total_revenue_now['total'])) * 100;
            	}
	            ?>
	            <tr>
	            	<th class="text-right" colspan="3">TOTAL</th>
	            	<th class="text-center"><?php echo number_format($total_persen_before,2,',','.');?> %</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_before,2,',','.');?></th>
		           	<th class="text-center"><?php echo number_format($total_persen_now,2,',','.');?></th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_now,2,',','.');?></th>
		            <th class="text-center"><?php echo number_format($total_persen,2,',','.');?></th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total,2,',','.');?></th>
		        </tr>
	            <?php
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="6">Tidak Ada Data</th>
		        </tr>
	            <?php
	        }
	        
	        ?>

	    </table>

	    <table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="3">Volume</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d Periode lalu</th>
	            <th class="text-center">Saat ini</th>
	            <th class="text-center">Sampai dengan saat ini</th>
	        </tr>
	        <?php
	        if(!empty($arr_deviasi_volume)){
	        	$no =1;
	            foreach ($arr_deviasi_volume as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center"><?php echo number_format($val['before'] - $val['before_compo'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($val['now'] - $val['now_compo'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($val['total'] - $val['total_compo'],2,',','.');?></th>
			        </tr>
		            <?php

		            $no++;
	            	
	            }
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="6">Tidak Ada Data</th>
		        </tr>
	            <?php
	        }
	        
	        ?>

	    </table>
	   
