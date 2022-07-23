<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title><?php echo $row['no_po'];?></title>
	  
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
		}
		table.minimalistBlack tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  padding: 10px;
		}
		table tr.table-active{
            background-color: #b5b5b5;
        }
        table tr.table-active2{
            background-color: #cac8c8;
        }
		table tr.table-active3{
            background-color: #eee;
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
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 16px;">ORDER PEMBELIAN</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<th width="20%">Nomor PO</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?php echo $row['no_po'];?></th>
				<td align="left" width="28%">
					Jakarta, <?= convertDateDBtoIndo($row["date_po"]); ?>
				</td>
			</tr>
			<tr>
				<th>Subjek</th>
				<th width="10px">:</th>
				<th align="left"><?php echo $row['subject'];?></th>
			</tr>
			<tr>
				<th>Tgl PKP</th>
				<th width="10px">:</th>
				<th align="left">-</th>
			</tr>
			
			<tr>
				<th>NPWP</th>
				<th width="10px">:</th>
				<th align="left"><?php echo $row['npwp_supplier'];?></th>
			</tr>
			
			<tr>
				<td width="72%"></td>
				<th width="28%" align="left"><b>Kepada Yth :</b></th>
			</tr>
			<tr>
				<td width="72%"></td>
				<th width="28%" align="left"><b><?php echo $row['supplier_name'];?></b></th>
			</tr>
			<tr>
				<td width="72%"></td>
				<th width="28%" align="left"><?php echo $row['address_supplier'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%" align="center">No</th>
                <th width="30%" align="center">Bahan</th>
                <th width="10%" align="center">Satuan</th>
                <th width="15%" align="center">Volume</th>
                <th width="15%" align="center">Harga</th>
                <th width="25%" align="center">Subtotal</th>
            </tr>
            <?php
           $no=1;
           $total = 0;
		   $tax_pph = 0;
		   $tax_ppn = 0;
		   $tax_0 = false;
		   $tax_ppn11 = 0;
           foreach ($details as $dt) {
                $subtotal = $dt['total'] * $dt['price'];
               ?>  
               <tr>
                   <td align="center"><?php echo $no;?></td>
                   <td align="center"><?php echo $this->crud_global->GetField('produk',array('id'=>$dt['material_id']),'nama_produk');?></td>
                   <td align="center"><?php echo $dt['measure'];?></td>
                   <td align="center"><?php echo number_format($dt['total'],2,',','.');?></td>
                   <td align="right"><?php echo number_format($dt['price'],0,',','.');?></td>
                   <td align="right"><?php echo number_format($subtotal,0,',','.');?></td>
               </tr>
               <?php
               $no++;
               $total += $subtotal;
			   if($dt['tax_id'] == 4){
				$tax_0 = true;
				}
				if($dt['tax_id'] == 3){
					$tax_ppn += $dt['tax'];
				}
				if($dt['tax_id'] == 5){
					$tax_pph += $dt['tax'];
				}
				if($dt['tax_id'] == 6){
					$tax_ppn11 += $dt['tax'];
				}
				}
				?>
				<?php
					if($dt['tax_id'] == 3){
						?>
						<tr>
							<th colspan="5" align="right">Pajak (PPN 10%)</th>
							<th align="right"><?php echo number_format($tax_ppn,0,',','.');?></th>
						</tr>
						<?php
					}
					?>
					<?php
					if($dt['tax_id'] == 4){
						?>
						<tr>
							<th colspan="5" align="right">Pajak (Pajak 0%)</th>
							<th align="right"><?php echo number_format(0,0,',','.');?></th>
						</tr>
						<?php
					}
					?>
					<?php
					if($dt['tax_id'] == 5){
						?>
						<tr>
							<th colspan="5" align="right">Pajak (PPh 23)</th>
							<th align="right"><?php echo number_format($tax_pph,0,',','.');?></th>
						</tr>
						<?php
					}
					?>
					<?php
					if($dt['tax_id'] == 6){
						?>
						<tr>
							<th colspan="5" align="right">Pajak (PPN 11%)</th>
							<th align="right"><?php echo number_format($tax_ppn11,0,',','.');?></th>
						</tr>
						<?php
					}

				$total = $total + $tax_ppn - $tax_pph + $tax_ppn11;
				?>
		<tr>
			<th align="right" colspan="5" >TOTAL</th>
			<th align="right"><?php echo number_format($total,0,',','.');?></th>
		</tr>
		</table>
		<p><b>Memo</b></p>
		<p><?= $row["memo"] ?></p>
		<br />
		<br />
		<br />
		<br />
		<?php
        $ka_plant = $this->pmm_model->GetNameGroup(15);
        ?> 
		<table width="98%" border="0" cellpadding="0">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="1" cellpadding="2">
						<tr class="table-active3">
							<td align="center" >
								<b><?php echo $row['supplier_name'];?></b>
							</td>
							<td align="center" >
								<b>PT BIA BUMI JAYENDRA</b>
							</td>
						</tr>
						<tr class="table-active3">
							<td align="center" >
								PENERIMA ORDER
							</td>
							<td align="center" >
								PEMBERI ORDER
							</td>
						</tr>
						<tr class="">
							<td align="center" height="75px">
								
							</td>
							<td align="center">
								
							</td>
						</tr>
						<tr class="table-active3">
							<td align="center"><?php echo $row['pic'];?></td>
							<td align="center"><?= $ka_plant['admin_name'];?></td>
						</tr>
						<tr class="table-active3">
							<td align="center" >
								<b><?php echo $row['position'];?></b>
							</td>
							<td align="center" >
								<b>Ka. Unit Bisnis</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>

			
		

	</body>
</html>