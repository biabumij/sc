	
	
	<?php
	// echo '<pre>';
	// print_r($arr);
	// echo '</pre>';	
	?>
		<h4><?php echo $name;?></h4>
		<h4>Periode : <?php echo $filter_date;?></h4>
		<div class="table-responsive">
			<table class="table table-bordered">
		        <tr class="table-active">
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Equipment</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
		            <th class="text-center" colspan="3">Biaya</th>
		        </tr>
		        <tr class="table-active">
		            <th class="text-center">Periode lalu</th>
		            <th class="text-center">Periode ini</th>
		            <th class="text-center">Sampai dengan saat ini</th>
		        </tr>
		        <tr>
		            <th colspan="6"></th>
		        </tr>

		        <?php
		        $no =1;
		        if(!empty($equipments)){
		        	$total_now = 0;
		        	$total_before = 0;
		        	$total = 0;
		        	foreach ($equipments as $val) {
		        		?>
			            <tr>
			            	<th class="text-center"><?php echo $no;?></th>
				            <th ><?php echo $val['tool'];?></th>
				            <th class="text-center"><?php echo $val['measure'];?></th>
				            <th class="text-right"><span class="pull-left">Rp.</span> <?php echo number_format($val['before'] ,2,',','.');?></th>
							<th class="text-right"><span class="pull-left">Rp.</span> <?php echo number_format($val['now'],2,',','.');?></th>
							<th class="text-right"><span class="pull-left">Rp.</span> <?php echo number_format($val['total'],2,',','.');?></th>
				        </tr>
			            <?php
			            $no++;
			            $total_now += $val['now'];
			            $total_before += $val['before'];
			            $total += $val['total'];
		            	
		            }
		            ?>
		            <tr>
			            <th colspan="6"></th>
			        </tr>
		            <tr>
		            	<th class="text-right" colspan="3">TOTAL</th>
			            <th class="text-right"><span class="pull-left">Rp.</span> <?php echo number_format($total_before ,2,',','.');?></th>
						<th class="text-right"><span class="pull-left">Rp.</span> <?php echo number_format($total_now,2,',','.');?></th>
						<th class="text-right"><span class="pull-left">Rp.</span> <?php echo number_format($total,2,',','.');?></th>
			        </tr>
		            <?php
		        }
	            
	            
		        ?>

		    </table>
		</div>
		

	    