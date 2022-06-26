		
		
	
		<style type="text/css">
			.table-active2{
				background: #cecece;
			}
		</style>
		<h4>Periode : <?php echo $filter_date;?></h4>

		<div class="row">
			<div class="col-sm-5">
				<table class="table table-bordered">
					<tr class="table-active">
						<th colspan="3" class="text-center">Volume Produksi (M3)</th>
					</tr>
					<tr class="table-active">
						<th class="text-center">s/d Lalu</th>
						<th class="text-center">Saat ini</th>
						<th class="text-center">S/d Saat ini</th>
					</tr>
					<tr >
						<th class="text-right"><?php echo number_format($arr['before'],2,',','.');?></th>
						<th class="text-right"><?php echo number_format($arr['now'],2,',','.');?></th>
						<th class="text-right"><?php echo number_format($arr['total'],2,',','.');?></th>
					</tr>				
				</table>
			</div>
		</div>

		
		<h4>Alat</h4>
		<div class="table-responsive">
			<table class="table table-bordered">
		        <tr class="table-active">
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Alat</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Rekanan</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Volume</th>
		            <th class="text-center" colspan="3">Biaya</th>
		        </tr>
		        <tr class="table-active">
		            <th class="text-center">Periode lalu</th>
		            <th class="text-center">Periode ini</th>
		            <th class="text-center">Sampai dengan saat ini</th>
		        </tr>

		        <?php
		        $no =1;
		        if(!empty($equipments)){
		        	$total_now = 0;
		        	$total_before = 0;
		        	$total = 0;
		        	$volume = 0;
		        	foreach ($equipments as $val) {

		        		$total_now += $val['now'];
			            $total_before += $val['before'];
			            $total += $val['total'];
			            $volume += $val['volume'];

		        		if($val['volume'] > 0){
		        			$val['volume'] = number_format($val['volume'] ,2,',','.');
		        		}else {
		        			$val['volume'] = '';
		        		}

		        		if($val['before'] > 0){
		        			$val['before'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($val['before'] ,2,',','.');
		        		}else {
		        			$val['before'] = '';
		        		}

		        		if($val['now'] > 0){
		        			$val['now'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($val['now'] ,2,',','.');
		        		}else {
		        			$val['now'] = '';
		        		}

		        		if($val['total'] > 0){
		        			$val['total'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($val['total'] ,2,',','.');
		        		}else {
		        			$val['total'] = '';
		        		}

		        		?>
			            <tr class="table-active2" onclick="ShowDetails(<?php echo $no;?>)" style="cursor: pointer;">
			            	<th class="text-center"><?php echo $no;?></th>
				            <th ><?php echo $val['tool'];?></th>
				            <th></th>
				            <th class="text-center"><?php echo $val['measure'];?></th>
				            <th class="text-center"><?php echo $val['volume'];?></th>
				            <th class="text-right"><?php echo $val['before'];?></th>
							<th class="text-right"><?php echo $val['now'];?></th>
							<th class="text-right"><?php echo $val['total'];?></th>
				        </tr>
			            <?php

			            $no_2 = 1;
			            foreach ($val['equipments'] as $row) {

			            	if($row['volume'] > 0){
			        			$row['volume'] = number_format($row['volume'] ,2,',','.');
			        		}else {
			        			$row['volume'] = '';
			        		}

			        		if($row['before'] > 0){
			        			$row['before'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($row['before'] ,2,',','.');
			        		}else {
			        			$row['before'] = '';
			        		}

			        		if($row['now'] > 0){
			        			$row['now'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($row['now'] ,2,',','.');
			        		}else {
			        			$row['now'] = '';
			        		}

			        		if($row['total'] > 0){
			        			$row['total'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($row['total'] ,2,',','.');
			        		}else {
			        			$row['total'] = '';
			        		}

			            	?>
				            <tr style="display: none;" class="no-<?php echo $no;?>">
				            	<td class="text-center"><?php echo $no.'.'.$no_2;?></td>
					            <td class="text-right"><?php echo $row['tool'];?></td>
					            <td class="text-right"><?php echo $row['supplier'];?></td>
					            <td class="text-center"><?php echo $row['measure'];?></td>
					            <td class="text-center"><?php echo $row['volume'];?></td>
					            <td class="text-right"><?php echo $row['before'];?></td>
								<td class="text-right"><?php echo $row['now'];?></td>
								<td class="text-right"><?php echo $row['total'];?></td>
					        </tr>
				            <?php
				            $no_2++;
			            }


			            $no++;
			            
		            	
		            }
		            // print_r($solar);
		            if(!empty($solar)){
		            	foreach ($solar as $key => $sol) {
		            		$volume_solar = $sol['vol_now'] + $sol['vol_before'];
		            		$total_solar = $sol['total_before'] + $sol['total_now'];

		            		$volume += $volume_solar;
				            $total += $total_solar;
				            $total_now += $sol['total_now'];
				            $total_before += $sol['total_before'];
		            		if($volume_solar != 0){
			        			$volume_solar = number_format($volume_solar ,2,',','.');
			        		}else {
			        			$sol['volume'] = '';
			        		}

			        		if($sol['total_before'] != 0){
			        			$sol['total_before'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($sol['total_before'] ,2,',','.');
			        		}else {
			        			$sol['total_before'] = '';
			        		}

			        		if($sol['total_now'] != 0){
			        			$sol['total_now'] = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($sol['total_now'] ,2,',','.');
			        		}else {
			        			$sol['total_now'] = '';
			        		}

			        		if($total_solar != 0){
			        			$total_solar = '<span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> '.number_format($total_solar ,2,',','.');
			        		}else {
			        			$total_solar = '';
			        		}

		            		?>
				            <tr class="table-active2" onclick="ShowDetails(<?php echo $no;?>)" style="cursor: pointer;">
				            	<th class="text-center"><?php echo $no;?></th>
					            <th ><?php echo $sol['name'];?></th>
					            <th></th>
					            <th class="text-center"><?php echo $sol['measure'];?></th>
					            <th class="text-center"><?php echo $volume_solar;?></th>
					            <th class="text-right"><?php echo $sol['total_before'];?></th>
								<th class="text-right"><?php echo $sol['total_now'];?></th>
								<th class="text-right"><?php echo $total_solar;?></th>
					        </tr>
				            <?php

				            $no++;
		            	}
		            }
		            ?>
		            <tr class="table-active2">
		            	<th class="text-right" colspan="4">TOTAL</th>
		            	<th class="text-center"><?php echo number_format($volume ,2,',','.');?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_before ,2,',','.');?></th>
						<th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_now,2,',','.');?></th>
						<th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total,2,',','.');?></th>
			        </tr>
		            <?php
		        }else {
		        	?>
		        	<tr>
		        		<td colspan="8" align="center"> -- No Data --</td>
		        	</tr>
		        	<?php
		        }
	            
	            
		        ?>

		    </table>
		</div>
		

		<script type="text/javascript">
			function ShowDetails(id)
        {
            $('.no-'+id).slideToggle();
        }
		</script>