		
		<h4>Periode : <?php echo $filter_date;?></h4>
		<hr />
		<h4>Sisa Material Real : </h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="23">Biaya</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d Periode lalu</th>
	            <th class="text-center">Saat ini</th>
	        </tr>

	        <?php
	        $no =1;
	        if(!empty($arr)){
	        	$total_before = 0;
	        	$total_now = 0;
	        	$total = 0;
	        	foreach ($arr as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'],2,',','.');?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_now'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;
		            $total_before += $val['total_before'];
		        	$total_now += $val['total_now'];;
		        	$total += $val['total'];
	            	
	            }
	            ?>
	            <tr>
		            <th class="text-right" colspan="3">TOTAL</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_before,2,',','.');?></th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_now,2,',','.');?></th>
		        </tr>
	            <?php
	        }else {
	        	?>
	        	<tr>
	            	<th class="text-center" colspan="5">Tidak Ada Data</th>
		        </tr>
	        	<?php
	        }
            
	        ?>

	    </table>

	    <hr />
	    
	    <?php
	    /*
	    <h4>Sisa Material Komposisi : </h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="3">Volume</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d periode lalu</th>
	            <th class="text-center">Periode ini</th>
	            <th class="text-center">Sampai dengan saat ini</th>
	        </tr>

	        <?php
	        $no =1;
	        if(!empty($arr_compo)){
	        	foreach ($arr_compo as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><a href="javascript:void(0);" onclick="Details('<?php echo $val['name'];?>',<?php echo $val['id'];?>,'compo')"><?php echo $val['name'];?></a></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><?php echo number_format($val['vol_before'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['vol_now'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['vol'],2,',','.');?></th>
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
	    <hr />
	    <h4>Sisa Material Real : </h4>
	    */

	    ?>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="2">Volume</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d Periode lalu</th>
	            <th class="text-center">Saat ini</th>
	        </tr>

	        <?php
	        if(!empty($arr)){
	        	$no =1;
	            foreach ($arr as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><?php echo $val['name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-center"><?php echo number_format($val['vol_before'],2,',','.');?></th>
			            <th class="text-center"><?php echo number_format($val['vol_now'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;
	            	
	            }
	        }else {
	        	?>
	        	<tr>
	            	<th class="text-center" colspan="5">Tidak Ada Data</th>
		        </tr>
	        	<?php
	        }
	        ?>

	    </table>