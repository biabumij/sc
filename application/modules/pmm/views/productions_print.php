<!DOCTYPE html>
<html>
	<head>
	  <title><?php echo $row['no_po'];?></title>
	  <?= include 'lib.php'; ?>
	  
	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
			font-size: 7px;
			color: #000000;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 0px solid #000000;
		}
		table.minimalistBlack tr td {
		  text-align:center;

		}
		table.minimalistBlack tr th {
		  font-weight: bold;
		  background-color: #cccccc;
		  text-transform: uppercase;
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
		<table width="98%" border="0" cellpadding="1">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 16px;">PENGIRIMAN</div>
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
                <th align="center" width="3%">No</th>
                <th align="center" width="7%">Tanggal</th>
				<th align="center" width="22%">No. Sales Order</th>
                <th align="center" width="10%">No. Surat Jalan</th>
				<th align="center" width="10%">No. Kendaraan</th>
				<th align="center" width="10%">Supir</th>
                <th align="center" width="10%">Produk</th>
                <th align="center" width="6%">Satuan</th>
                <th align="center" width="6%">Volume</th>
				<th align="center" width="8%">Harga Satuan</th>
				<th align="center" width="8%">Nilai</th>
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
	            			<th colspan="8" style="text-align:right;"><div style="text-transform:uppercase;">TOTAL (<?php echo date('d-m-Y',strtotime($date));?>)</div></th>
              				<th style="text-align:center;"><?php echo number_format($total_by_date,2,',','.');?></th>
							<th></th>
							<th style="text-align:right;"><?php echo number_format($total_nilai_by_date,0,',','.');?></th>
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
                 		<td style="text-align:right;"><?php echo number_format($row['harga_satuan'],0,',','.');?></td>
						<td style="text-align:right;"><?php echo number_format($row['price'],0,',','.');?></td>
            		</tr>
            		<?php
            		if($key == count($data) - 1){
            			?>
	            		<tr>
	            			<th colspan="8" style="text-align:right;"><div style="text-transform:uppercase;">TOTAL (<?php echo date('d-m-Y',strtotime($date));?>)</div></th>
              				<th style="text-align:center;"><?php echo number_format($total_by_date,2,',','.');?></th>
							<th></th>
							<th style="text-align:right;"><?php echo number_format($total_nilai_by_date,0,',','.');?></th>
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
               <th colspan="8" style="text-align:right;">TOTAL</th>
               <th style="text-align:center;"><?php echo number_format($total,2,',','.');?></th>
			   <th></th>
			   <th style="text-align:right;"><?php echo number_format($total_nilai,0,',','.');?></th>
           </tr>
		</table>
	</body>
</html>