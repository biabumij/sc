	
	
	<?php
	// echo '<pre>';
	// print_r($arr);
	// echo '</pre>';	
	?>
		<h4><?php echo $name;?></h4>
		<?php
		if($type == 'cost'){
			?>
			<table class="table table-bordered">
		        <tr class="table-active">
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Harga</th>
		            <th class="text-center" colspan="3">Biaya</th>
		        </tr>
		        <tr class="table-active">
		            <th class="text-center">s/d bulan lalu</th>
		            <th class="text-center">Saat ini</th>
		            <th class="text-center">Sampai dengan saat ini</th>
		        </tr>
		        <tr>
		            <th colspan="7"></th>
		        </tr>

		        <?php
		        $no =1;
	            foreach ($arr as $val) {
	            	$cost_now = $val['vol_now'] * $val['price'];
	            	$cost_before = $val['vol_before'] * $val['price'];
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center">Rp. <?php echo number_format($val['price'],2,',','.');?></th>
			            <th class="text-right">Rp. <?php echo number_format($cost_before,2,',','.');?></th>
			            <th class="text-right">Rp. <?php echo number_format($cost_now,2,',','.');?></th>
			            <th class="text-right">Rp. <?php echo number_format($cost_now + $cost_before,2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;
	            	
	            }
		        ?>

		    </table>
		    <br />
			<?php
		}else {
			?>
			<table class="table table-bordered">
		        <tr class="table-active">
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
		            <th class="text-center" colspan="3">Volume</th>
		        </tr>
		        <tr class="table-active">
		            <th class="text-center">s/d bulan lalu</th>
		            <th class="text-center">Saat ini</th>
		            <th class="text-center">Sampai dengan saat ini</th>
		        </tr>
		        <tr>
		            <th colspan="6"></th>
		        </tr>

		        <?php
		        $no =1;
	            foreach ($arr as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><?php echo number_format($val['vol_before'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['vol_now'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['vol_before'] + $val['vol_now'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;
	            	
	            }
		        ?>

		    </table>
			<?php	
		}
		?>	
		

	    