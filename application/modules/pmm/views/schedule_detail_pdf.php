<!DOCTYPE html>
<html>
	<head>
	  <title>Jadwal Produksi</title>
	  
	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 98%;
		  text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 1px solid #000000;
		  padding: 5px 4px;
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
		table tr.table-active{
            background-color: #b5b5b5;
        }
        table tr.table-active2{
            background-color: #cac8c8;
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
		<h2 style="text-align:center;margin:0;">Jadwal Produksi</h2>
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th>Aktifitas</th>
                <th>Minggu 1 (Ton)</th>
                <th>Minggu 2 (Ton)</th>
                <th>Minggu 3 (Ton)</th>
                <th>Minggu 4 (Ton)</th>
                <th>Total</th>
			</tr>
			<?php
			if(!empty($data_week)){
				$total_pro = 0;
				foreach ($data_week as $dw_key => $dw_val) {
					?>
            		<tr>
            			<td><?php echo $dw_val['product'];?></td>
            			<td><?php echo $dw_val['week_1'];?></td>
            			<td><?php echo $dw_val['week_2'];?></td>
            			<td><?php echo $dw_val['week_3'];?></td>
            			<td><?php echo $dw_val['week_4'];?></td>
            			<td><?php echo $dw_val['total'];?></td>
            		</tr>
            		<?php
            		$total_pro += $dw_val['total'];
				}
				?>
				<tr>
					<td><b>TOTAL KEBUTUHAN PRODUKSI</b></td>
					<td ><?php echo $row['week_1']?></td>
					<td ><?php echo $row['week_2']?></td>
					<td ><?php echo $row['week_3']?></td>
					<td ><?php echo $row['week_4']?></td>
					<td ><?php echo $total_pro;?></td>
				</tr>
				<?php
			}
			?>

		</table>
		<h2 style="text-align:center;">Kebutuhan Bahan Baku</h2>
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th>Bahan</th>
                <th>Minggu 1</th>
                <th>Minggu 2</th>
                <th>Minggu 3</th>
                <th>Minggu 4</th>
                <th>Total</th>
                <th>Kebutuhan</th>
			</tr>
			<?php
			if(!empty($data)){
				foreach ($data as $key => $val) {
					?>
            		<tr>
            			<td><?php echo $val['material_name'];?></td>
            			<td><?php echo $val['week_1'];?></td>
            			<td><?php echo $val['week_2'];?></td>
            			<td><?php echo $val['week_3'];?></td>
            			<td><?php echo $val['week_4'];?></td>
            			<td>
            				<b><?php echo $val['total'];?></b>
            			</td>
            			<td>
            				<b><?php echo $val['butuh'];?></b>
            			</td>
            		</tr>

            		<?php
				}
			}
			?>
		</table>
		<br /><br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th>Bahan</th>
                <th>Minggu 1</th>
                <th>Minggu 2</th>
                <th>Minggu 3</th>
                <th>Minggu 4</th>
                <th>Total</th>
                <th>Kebutuhan</th>
			</tr>
			<?php
			if(!empty($data)){
				foreach ($data as $key => $val) {
					?>

            		<tr>
            			<td><?php echo $val['material_name'];?></td>
            			<td><?php echo $val['week_1_price'];?></td>
            			<td><?php echo $val['week_2_price'];?></td>
            			<td><?php echo $val['week_3_price'];?></td>
            			<td><?php echo $val['week_4_price'];?></td>
            			<td>
            				<b><?php echo $val['total_price'];?></b>
            			</td>
            			<td>
            				<b><?php echo $val['butuh_price'];?></b>
            			</td>
            		</tr>
            		
            		<?php
				}
			}
			?>
		</table>
		
		

	</body>
</html>