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
					<div style="display: block;font-weight: bold;font-size: 16px; color: red;">(DRAFT)</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="1">
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
				<th>:</th>
				<th align="left"><?php echo $row['subject'];?></th>
			</tr>
			<tr>
				<th>Tgl PKP</th>
				<th>:</th>
				<th align="left">-</th>
			</tr>
			<tr>
				<th>NPWP</th>
				<th>:</th>
				<th align="left"><?php echo $row['npwp_supplier'];?></th>
			</tr>
			<?php
			foreach ($details_no_pnw as $dt) {
            ?>
			<tr>
				<th>No. Penawaran</th>
				<th>:</th>
				<th align="left"><?php echo $this->crud_global->GetField('pmm_penawaran_pembelian',array('id'=>$dt['penawaran_id']),'nomor_penawaran');?></th>
			</tr>
			<?php
			}
			?>
			
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
                <th width="30%" align="center">Produk</th>
                <th width="10%" align="center">Satuan</th>
                <th width="15%" align="center">Volume</th>
                <th width="15%" align="center">Harga</th>
                <th width="25%" align="center">Subtotal</th>
            </tr>
            <?php
           $no=1;
		   $subtotal = 0;
		   $total = 0;
		   $tax_0 = 0;
		   $tax_ppn = 0;
		   $tax_pph = 0;
		   $tax_ppn11 = 0;
           foreach ($details as $dt) {
                $nilai = $dt['total'] * $dt['price'];
               ?>   
               <tr>
                   <td align="center"><?php echo $no;?></td>
                   <td align="center"><?php echo $this->crud_global->GetField('produk',array('id'=>$dt['material_id']),'nama_produk');?></td>
                   <td align="center"><?php echo $dt['measure'];?></td>
                   <td align="center"><?php echo number_format($dt['total'],2,',','.');?></td>
                   <td align="right"><?php echo number_format($dt['price'],0,',','.');?></td>
                   <td align="right"><?php echo number_format($nilai,0,',','.');?></td>
               </tr>
               <?php
					$no++;
					$subtotal += $dt['total'] * $dt['price'];
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
			<tr>
               <th colspan="5" style="text-align:right">SUB TOTAL</th>
               <th align="right"><?= number_format($subtotal,0,',','.'); ?></th>
           	</tr>
			<?php
            if($tax_ppn > 0){
                ?>
                <tr>
                    <th colspan="5" align="right">PAJAK (PPN 10%)</th>
                    <th  align="right"><?= number_format($tax_ppn,0,',','.'); ?></th>
                </tr>
                <?php
            }
            ?>
            <?php
            if($tax_0){
                ?>
                <tr>
                    <th colspan="5" align="right">PAJAK (PPN 0%)</th>
                    <th  align="right"><?= number_format(0,0,',','.'); ?></th>
                </tr>
                <?php
            }
            ?>
            <?php
            if($tax_pph > 0){
                ?>
                <tr>
                    <th colspan="5" align="right">PAJAK (PPh 23)</th>
                    <th align="right"><?= number_format($tax_pph,0,',','.'); ?></th>
                </tr>
                <?php
            }
			?>
			<?php
            if($tax_ppn11 > 0){
                ?>
                <tr>
                    <th colspan="5" align="right">PAJAK (PPN 11%)</th>
                    <th align="right"><?= number_format($tax_ppn11,0,',','.'); ?></th>
                </tr>
                <?php
            }
			
            $total = $subtotal + $tax_ppn - $tax_pph + $tax_ppn11;
            ?>
			
			<?php
				$presentase_uang_muka = ($row['uang_muka'] / $total) * 100;
			?>
            
            <tr>
                <th colspan="5" align="right">TOTAL</th>
                <th align="right"><?= number_format($total,0,',','.'); ?></th>
            </tr>
			<tr>
                <th colspan="5" align="right">UANG MUKA (<?= number_format($presentase_uang_muka,0,',','.'); ?>%)</th>
                <th align="right"><?= number_format($row['uang_muka'],0,',','.'); ?></th>
            </tr>
		</table>
		<p style="font-size:6px;"><?= $row["memo"] ?></p>
	</body>
</html>