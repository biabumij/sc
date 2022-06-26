<!DOCTYPE html>
<html>
	<head>
	  <title>PRODUCTION PLANNING</title>
	  
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
		  /*padding: 10px 4px;*/
		}
		table.minimalistBlack tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  text-align: center;
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
		<table width="100%" border="0" cellpadding="3">
			<tr class="table-active2" style="">
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">PRODUCTION PLANNING</div>
				</td>
			</tr>
			<tr class="table-active2">
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;"><?= $this->crud_global->GetField('pmm_setting_production',array('id'=>1),'nama_pt');?></div>
				</td>
			</tr>
		</table>
		<br />
		<hr />
		<table class="minimalistBlack" cellpadding="2" width="100%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="30%">Month</th>
                <th width="20%">Client/Proyek</th>
                <th width="15%">Date</th>
                <th width="5%">Week 1</th>
                <th width="5%">Week 2</th>
                <th width="5%">Week 3</th>
                <th width="5%">Week 4</th>
                <th width="10%">Status</th>
            </tr>
            <?php
            
            $total = 0;
            $total_2 = 0;
            $no=1;
            if(!empty($data)){
            	foreach ($data as $key => $row) {

            		?>
            		<tr>
            			<td align="center"><?php echo $no;?></td>
            			<td><?php echo $row['schedule_name'];?></td>
            			<td ><?php echo $row['client_name'];?></td>
            			<td align="center"><?php echo $row['schedule_date'];?></td>
            			<td ><?php echo $row['week_1'];?></td>
            			<td ><?php echo $row['week_2'];?></td>
            			<td ><?php echo $row['week_3'];?></td>
            			<td ><?php echo $row['week_4'];?></td>
            			<td align="center"><?php echo $row['status'];?></td>
            		</tr>
            		<?php	
            		$no++;
			        	            		
            		
            	}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="8" align="center">No Data</td>
            	</tr>
            	<?php
            }
            
            ?>	
           
          
		</table>

		
	</body>
</html>