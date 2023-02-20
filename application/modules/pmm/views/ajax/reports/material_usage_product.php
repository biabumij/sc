		
	
		<h4>Product : <?php echo $product;?></h4>
		<h4>Periode : <?php echo $filter_date;?></h4>
		<h4>Volume Produksi : <?php echo number_format($total_production,2,',','.');?> M3</h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th colspan="6" >Komposisi</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">No</th>
	            <th class="text-center">Nama Bahan</th>
	            <th class="text-center">Satuan</th>
	            <th class="text-center">Volume</th>
	            <th class="text-center">Total</th>
	        </tr>
        	<?php
	        $no =1;
	        if(!empty($arr_compo['komposisi'])){
	        	$total = 0;
	        	foreach ($arr_compo['komposisi'] as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><a href="javascript:void(0);" onclick="Details('<?php echo $val['name'];?>',<?php echo $val['id'];?>,'compo_now')"><?php echo $val['name'];?></a></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><?php echo number_format($val['volume'],2,',','.');?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;
		            $total += $val['total'];
	            }
	            ?>
	            <tr>
	            	<th class="text-right" colspan="4">TOTAL</th>
		            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($total,2,',','.');?></th>
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
	    <h4>Pemakaian Komposisi : </h4>
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">No.</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Nama Bahan</th>
	            <th class="text-center" rowspan="2" style="vertical-align: middle;">Satuan</th>
	            <th class="text-center" colspan="3">Biaya</th>
	        </tr>
	        <tr class="table-active">
	            <th class="text-center">s/d Periode lalu</th>
	            <th class="text-center">Saat ini</th>
	            <th class="text-center">Sampai dengan saat ini</th>
	        </tr>
	        <tr>
	            <th colspan="6"></th>
	        </tr>

	        <?php
	        $no =1;
	        if(!empty($arr_compo['standart'])){
	        	$total = 0;
	        	foreach ($arr_compo['standart'] as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><a href="javascript:void(0);" onclick="Details('<?php echo $val['name'];?>',<?php echo $val['id'];?>,'compo_cost')"><?php echo $val['name'];?></a></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total_before'],2,',','.');?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total'],2,',','.');?></th>
			            <th class="text-right"><span class="pull-left hidden-xs">Rp.</span>	<span class="visible-xs">Rp. </span> <?php echo number_format($val['total'] + $val['total_before'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;
		            $total += $val['total'];
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
	    <h4>Pemakaian Komposisi : </h4>
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
	        <tr>
	            <th colspan="6"></th>
	        </tr>

	        <?php
	        $no =1;
	        if(!empty($arr_compo['standart'])){
	        	$total = 0;
	        	foreach ($arr_compo['standart'] as $val) {
	        		?>
		            <tr>
		            	<th class="text-center"><?php echo $no;?></th>
			            <th ><a href="javascript:void(0);" onclick="Details('<?php echo $val['name'];?>',<?php echo $val['id'];?>,'compo')"><?php echo $val['name'];?></a></th>
			            <th class="text-center"><?php echo $val['measure'];?></th>
			            <th class="text-right"><?php echo number_format($val['volume_before'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['volume'],2,',','.');?></th>
			            <th class="text-right"><?php echo number_format($val['volume'] + $val['volume_before'],2,',','.');?></th>
			        </tr>
		            <?php
		            $no++;
		            $total += $val['total'];
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

	    