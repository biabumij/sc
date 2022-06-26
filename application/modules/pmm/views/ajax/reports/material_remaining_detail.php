	
	
	<?php
	// echo '<pre>';
	// print_r($arr);
	// echo '</pre>';	
	?>
		<h4>Material : <?php echo $name;?></h4>
		<h4>Periode : <?php echo $filter_date;?></h4>
		
		<?php
		if($type == 'cost'){
			?>
			<table class="table table-bordered">
		        <tr class="table-active">
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
		            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
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
		        $total_before = 0;
		        $total_now = 0;
		        $total = 0;
		        if(!empty($arr)){
		        	foreach ($arr as $val) {
		        		?>
			            <tr>
			            	<th class="text-center"><?php echo $no;?></th>
				            <th ><?php echo $val['name'];?></th>
				            <th class="text-center"><?php echo $val['measure'];?></th>
				            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'],2,',','.');?></th>
				            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_now'],2,',','.');?></th>
				            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'] + $val['total_now'],2,',','.');?></th>
				        </tr>
			            <?php
			            $no++;
			            $total_before += $val['total_before'];
			            $total_now += $val['total_now'];
			            $total += $val['total_before'] + $val['total_now'];
		            }

		            ?>
		            <tr>
			            <th colspan="3" class="text-center">TOTAL</th>
			            <th class="text-right" ><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_before,2,',','.');?></th>
			            <th class="text-right" ><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_now,2,',','.');?></th>
			            <th class="text-right" ><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total,2,',','.');?></th>
			        </tr>
		            <?php
		        }
		        ?>

		    </table>
		    <br />
			<?php
		}else if($type == 'vol'){
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
		        $total_before = 0;
		        $total_now = 0;
		        $total = 0;
		        if(!empty($arr)){
		            foreach ($arr as $val) {
		        		?>
			            <tr>
			            	<th class="text-center"><?php echo $no;?></th>
				            <th ><?php echo $val['name'];?></th>
				            <th class="text-center"><?php echo $val['measure'];?></th>
				            <th class="text-right"><?php echo number_format($val['volume_before'],2,',','.');?></th>
				            <th class="text-right"><?php echo number_format($val['volume_now'],2,',','.');?></th>
				            <th class="text-right"><?php echo number_format($val['volume_before'] + $val['volume_now'],2,',','.');?></th>
				        </tr>
			            <?php
			            $no++;
			            $total_before += $val['volume_before'];
			            $total_now += $val['volume_now'];
			            $total += $val['volume_before'] + $val['volume_now'];
		            	
		            }
		            ?>
		            <tr>
			            <th colspan="3" class="text-center">TOTAL</th>
			            <th class="text-right" ><?php echo number_format($total_before,2,',','.');?></th>
			            <th class="text-right" ><?php echo number_format($total_now,2,',','.');?></th>
			            <th class="text-right" ><?php echo number_format($total,2,',','.');?></th>
			        </tr>
		            <?php
		        }
		        ?>

		    </table>
			<?php	
		}else if($type == 'compo'){
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
		        $total_before = 0;
		        $total_now = 0;
		        $total = 0;
		        if(!empty($arr)){
		            foreach ($arr as $val) {
		        		?>
			            <tr>
			            	<th class="text-center"><?php echo $no;?></th>
				            <th ><?php echo $val['name'];?></th>
				            <th class="text-center"><?php echo $val['measure'];?></th>
				            <th class="text-right"><?php echo number_format($val['volume_before'],2,',','.');?></th>
				            <th class="text-right"><?php echo number_format($val['volume_now'],2,',','.');?></th>
				            <th class="text-right"><?php echo number_format($val['volume_before'] + $val['volume_now'],2,',','.');?></th>
				        </tr>
			            <?php
			            $no++;
			            $total_before += $val['volume_before'];
			            $total_now += $val['volume_now'];
			            $total += $val['volume_before'] + $val['volume_now'];
		            	
		            }
		            ?>
		            <tr>
			            <th colspan="3" class="text-center">TOTAL</th>
			            <th class="text-right" ><?php echo number_format($total_before,2,',','.');?></th>
			            <th class="text-right" ><?php echo number_format($total_now,2,',','.');?></th>
			            <th class="text-right" ><?php echo number_format($total,2,',','.');?></th>
			        </tr>
		            <?php
		        }
		        ?>

		    </table>
			<?php	
		}
		?>	
		

	    