	
	
	<?php
	// echo '<pre>';
	// print_r($arr);
	// echo '</pre>';	
	?>
		<h4>Periode : <?php echo $filter_date;?></h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Supplier</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="3">Biaya</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d Periode lalu</th>
	            <th class="text-center">Saat ini</th>
	            <th class="text-center">Sampai dengan saat ini</th>
	        </tr>
	        <?php
	        $no =1;
	        if(!empty($arr)){
	        	$total_lalu = 0;
	        	$total_now = 0;
	        	foreach ($arr as $val) {
	        		?>
		            <tr style="cursor: pointer;" onclick="nextShow(<?php echo $no;?>)" class="active">
		            	<th class="text-center"><?php echo $no;?></th>
			            <th colspan="2"><?php echo $val['supp_name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'],2,',','.');?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_now'],2,',','.');?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'] + $val['total_now'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no_mats = 1;
		            foreach ($val['materials'] as $mats) {
		            	?>
			            <tr style="display: none;" class="mats-<?php echo $no;?>">
			            	<th class="text-center"><?php echo $no.'.'.$no_mats;?></th>
			            	<th></th>
				            <th ><?php echo $mats['name'];?></th>
				            <th class="text-center"><?php echo $mats['measure'];?></th>
				            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($mats['total_before'],2,',','.');?></th>
				            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($mats['total_now'],2,',','.');?></th>
				            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($mats['total_before'] + $mats['total_now'],2,',','.');?></th>
				        </tr>
			            <?php
			            $no_mats++;
		            }
		            $no++;
		            $total_lalu += $val['total_before'];
		            $total_now += $val['total_now'];
	            	
	            }
	            ?>
	            <tr>
	            	<th class="text-right" colspan="4">TOTAL</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_lalu,2,',','.');?></th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_now,2,',','.');?></th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total_lalu + $total_now,2,',','.');?></th>
		        </tr>
	            <?php
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="7">No Data</th>
		        </tr>
	            <?php
	        }
            
	        ?>

	    </table>
	    <br />

	    <table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Supplier</th>
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
	            foreach ($arr as $val) {
	        		?>
		             <tr style="cursor: pointer;" onclick="nextShow2(<?php echo $no;?>)" class="active">
		            	<th class="text-center"><?php echo $no;?></th>
			            <th colspan="2"><?php echo $val['supp_name'];?></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><?php echo number_format($val['vol_before'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['vol_now'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['vol_before'] + $val['vol_now'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no_mats = 1;
		            foreach ($val['materials'] as $mats) {
		            	?>
			            <tr style="display: none;" class="mats2-<?php echo $no;?>">
			            	<th class="text-center"><?php echo $no.'.'.$no_mats;?></th>
			            	<th></th>
				            <th ><?php echo $mats['name'];?></th>
				            <th class="text-center"><?php echo $mats['measure'];?></th>
				            <th class="text-right"><?php echo number_format($mats['vol_before'],2,',','.');?></th>
				            <th class="text-right"><?php echo number_format($mats['vol_now'],2,',','.');?></th>
				            <th class="text-right"><?php echo number_format($mats['vol_before'] + $mats['vol_now'],2,',','.');?></th>
				        </tr>
			            <?php
			            $no_mats++;
		            }
		            $no++;
	            	
	            }
	        }else {
	        	?>
	            <tr>
	            	<th class="text-center" colspan="6">No Data</th>
		        </tr>
	            <?php
	        }
	        
	        ?>

	    </table>



	    <script type="text/javascript">
	    	function nextShow(id){
	    		$('.mats-'+id).slideToggle();
	    	}
	    	function nextShow2(id){
	    		$('.mats2-'+id).slideToggle();
	    	}
	    </script>