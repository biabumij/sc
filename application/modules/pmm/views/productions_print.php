<!DOCTYPE html>
<html>
	<head>
	  <title><?php echo $row['no_po'];?></title>
	  
	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
		  text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 1px solid #000000;
		  /*padding: 5px 4px;*/
		}
		table.minimalistBlack tr td {
		  /*font-size: 13px;*/
		  text-align:center;

		}
		table.minimalistBlack tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  text-align: center;
		  padding: 10px;
		}
		hr{
			margin-top:0;
			margin-bottom:30px;
		}
		h3{
			margin-top:0;
		}
	  </style>

	</head>
	<body>
		<table width="98%" border="0" cellpadding="2">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 16px;">PENJUALAN</div>
				</td>
			</tr>
			<?php
			if(!empty($filter_date)){
				?>
				<tr>
					<td align="center">
						<div style="display: block;font-weight: bold;font-size: 12px;">Periode : <?php echo $filter_date;?></div>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="1" width="98%">
			<tr>
                <th width="3%">No</th>
                <th width="7%">Tanggal</th>
				<th width="10%">No. Sales Order</th>
                <th width="10%">No. Surat Jalan</th>
				<th width="10%">No. Kendaraan</th>
				<th width="10%">Supir</th>
                <th width="10%">Produk</th>
                <th width="10%">Satuan</th>
                <th width="10%">Volume</th>
				<th width="10%">Harga Satuan</th>
				<th width="10%">Nilai</th>
            </tr>
            <?php
            
            $total = 0;
			$total_nilai = 0;
            if(!empty($data)){
            	$date = false;
            	$total_by_date = 0;
				$total_nilai_by_date = 0;
            	foreach ($data as $key => $row) {

            		if($date !== false && $row['date_production'] != $date){
            			?>
	            		<tr>
	            			<th colspan="8" style="text-align:right">TOTAL <?php echo date('d F Y',strtotime($date));?></th>
              				<td style="text-align:center;"><?php echo number_format($total_by_date,2,',','.');?></td>
							<td></td>
							<td><?php echo number_format($total_nilai_by_date,0,',','.');?></td>
	            		</tr>
	            		<?php
	            		$total_by_date = 0;
						$total_nilai_by_date = 0;
            		}
            		$total_by_date += $row['volume'];
					$total_nilai_by_date += $row['price'];
            		?>
            		<tr>
            			<td><?php echo $key + 1 ;?></td>
            			<td><?php echo date('d-m-Y',strtotime($row['date_production']));?></td>
						<td><?php echo $row['salesPo_id']= $this->crud_global->GetField('pmm_sales_po',array('id'=>$row['salesPo_id']),'contract_number');?></td>
            			<td><?php echo $row['no_production'];?></td>
						<td><?php echo $row['nopol_truck'];?></td>
						<td><?php echo $row['driver'];?></td>
            			<td><?php echo $row['product_id'] = $this->crud_global->GetField('produk',array('id'=>$row['product_id']),'nama_produk');?></td>
            			<td><?php echo $row['measure'];?></td>
						<td><?php echo number_format($row['volume'],2,',','.');?></td>
                 		<td><?php echo number_format($row['harga_satuan'],0,',','.');?></td>
						 <td><?php echo number_format($row['price'],0,',','.');?></td>
            		</tr>
            		<?php

            		if($key == count($data) - 1){
            			?>
	            		<tr>
	            			<th colspan="8" style="text-align:right">TOTAL <?php echo date('d F Y',strtotime($row['date_production']));?></th>
              				<td><?php echo number_format($total_by_date,2,',','.');?></td>
							<td></td>
							<td><?php echo number_format($total_nilai_by_date,0,',','.');?></td>
	            		</tr>
	            		<?php
	            		$total_by_date = 0;
						$total_nilai_by_date = 0;
            		}
            		
            		$date = $row['date_production'];
            		
            		$total += $row['volume'];
					$total_nilai += $row['price'];
            	}
            }
            ?>	
           	<tr>
               <th colspan="8" style="text-align:right">TOTAL</th>
               <td><?php echo number_format($total,2,',','.');?></td>
			   <td></td>
			   <td><?php echo number_format($total_nilai,0,',','.');?></td>
           </tr>
           
          
		</table>

			
		

	</body>
</html>