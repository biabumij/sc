<!DOCTYPE html>
<html>
	<head>
	  <title>DAFTAR PEMBAYARAN PEMBELIAN</title>
	  
	  <style type="text/css">
	  	body{
	  	font-family: "Calibri Light", sans-serif;
		}
	  	
		table.minimalistBlack 
		  width: 100%;
		  text-align: left;	
		}
		
		table tr.table-active{
        }
		
		table tr.table-head{
            background-color: #e69500;
			color: #ffffff;
			font-size: 9px;
        }
		
		table tr.table-body{
			font-size: 8px;
        }
		
        table tr.table-head2{
			font-size: 9px;
			font-weight: bold;
        }
		
		table tr.table-foot{
			font-size: 9px;
			font-weight: bold;
			background-color: #e69500;
        }
	  </style>

	</head>
	<body>
		<table width="100%">
			<tr class="table-active" style="">
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">DAFTAR PEMBAYARAN PEMBELIAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">PERIODE <?php echo $filter_date;?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="3" width="100%">
			<tr class="table-head">
				<th align="center" width="20%">Rekanan / Tanggal Pembayaran</th>
				<th align="center" width="20%">Pembayaran</th>
				<th align="center" width="20%">Tanggal Tagihan</th>
				<th align="center" width="20%">Nomor tagihan</th>
				<th align="center" width="20%">Nomor tagihan</th>
            </tr>
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-head2">
            			<td align="left" colspan="5"><?php echo $row['supplier_name'];?></td>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-body">
	            			<td align="center"><?php echo $mat['tanggal_pembayaran'];?></td>
							<td align="center"><?php echo $mat['nomor_transaksi'];?></td>
							<td align="center"><?php echo $mat['tanggal_invoice'];?></td>
							<td align="center"><?php echo $mat['nomor_invoice'];?></td>            			
							<td align="right"><?php echo $mat['pembayaran'];?></td>
	            		</tr>
            			<?php
            		}	
            	}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="5" align="center">No Data</td>
            	</tr>
            	<?php
            }
            ?>
			<br />
            <tr class="table-foot">
            	<th width="90%" align="right" colspan="4"><b>Total</b></th>
            	<th align="right" width="10%"><?php echo number_format($total,0,',','.');?></th>
            </tr>
			
		</table>
		
		<table width="100%" border="0" cellpadding="50">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="1" cellpadding="2">
						<tr class="table-active">
							<td align="center" >
								Diperiksa oleh
							</td>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center" height="75px">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								
							</td>
						</tr>
						<tr class="table-active">
							<td align="center" >
								
							</td>
							<td align="center" >
								
							</td>
							<td align="center" >
								
							</td>
						</tr>
						<tr class="table-active">
							<td align="center" >
								<b>Ka. Plant</b>
							</td>
							<td align="center" >
								<b>Keuangan Proyek</b>
							</td>
							<td align="center" >
								<b>Admin Keuangan Proyek</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>