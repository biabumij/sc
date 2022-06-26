		

		<h4>Periode : <?php echo $arr_date;?></h4>
		<hr />
		<table class="table table-bordered">
	        <tr class="table-active">
	            <th class="text-center">No.</th>
	            <th class="text-center">Client</th>
	            <th class="text-center">Product</th>
	            <th class="text-center">Satuan</th>
	            <th class="text-center">Volume</th>
	            <th class="text-center">Pendapatan Usaha</th>
	        </tr>
	        <tr>
	            <th colspan="6"></th>
	        </tr>

	        <?php
	        $contract_total = 0;
	        $total_revenue = 0;	
	        $no_alpha =0;
	        foreach ($clients as $key => $client) {
	        	$arr_total = $this->pmm_model->getRevenueClient2($client['id'],$arr_date);
	        	if($arr_total['volume'] > 0){
	        		?>
	        		<tr class="table-active" >
		            	<th class="text-center"><?php echo $alphas[$no_alpha];?></th>
			            <th colspan="2"><a href="javascript:void(0);" onclick="showDetail(<?php echo $client['id'];?>)" style="color:#404040;"><?php echo $client['client_name'];?></a></th>
			            <th class="text-center">M3</th>
			            <th class="text-center"><?php echo number_format($arr_total['volume'],2,',','.');?></th>
			            <th class="text-right"><span class="pull-left">Rp.</span><?php echo number_format($arr_total['total'],2,',','.');?></th>
			        </tr>
		            <?php

		            $arr_pro = $this->pmm_reports->RevenueTagProByClient($client['id'],$arr_date);
		            $no =1;
		            foreach ($arr_pro as $val) {
	            		?>
			            <tr class="product-<?php echo $client['id'];?>" style="display: none;">
			            	<th class="text-center"><?php echo $no;?></th>
			            	<th></th>
				            <th ><?php echo $val['product'];?></th>
				            <th class="text-center">M3</th>
				            <th class="text-center"><?php echo number_format($val['volume'],2,',','.');?></th>
				            <th class="text-right"><span class="pull-left">Rp.</span>	<?php echo number_format($val['total'],2,',','.');?></th>
				        </tr>
			            <?php
			            $no++;
		            	
		            }
		            $no_alpha ++;
		            $total_revenue += $arr_total['total'];
	        	}
	            
	        }
	        ?>
            <tr class="table-active">
            	<th class="text-center" colspan="5">TOTAL</th>
	            <th class="text-right"><span class="pull-left">Rp.</span>	<?php echo number_format($total_revenue,2,',','.');?></th>
	        </tr>
	    </table>