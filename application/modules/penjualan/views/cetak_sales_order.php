<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>ORDER PENJUALAN</title>
	  
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
		table tr.table-active3{
            background-color: #eee;
        }
		table tr.table-bold{
            font-weight: bold;
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
					<div style="display: block;font-weight: bold;font-size: 16px;">ORDER PENJUALAN</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
		    <tr>
				<th width="20%">Nama Pelanggan</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?php echo $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'nama');?></th>
				<td align="left" width="28%">
					Jakarta, <?= convertDateDBtoIndo($row["contract_date"]); ?>
				</td>
			</tr>
			<tr>
				<th width="20%">Nomor Kontrak</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?php echo $row['contract_number'];?></th>
			</tr>
			<tr>
				<th>Tanggal Kontrak</th>
				<th width="10px">:</th>
				<th align="left"><?= convertDateDBtoIndo($row["contract_date"]); ?></th>
			</tr>
			<tr>
				<th>Jenis Pekerjaan</th>
				<th width="10px">:</th>
				<th align="left"><?php echo $row['jobs_type'];?></th>
			</tr>
			<tr>
				<td width="72%"></td>
				<th width="28%" align="left"><b>Kepada Yth :</b></th>
			</tr>
			<tr>
				<td width="72%"></td>
				<th width="28%" align="left"><b><?php echo $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'nama');?></b></th>
			</tr>
			<tr>
				<td width="72%"></td>
				<th width="28%" align="left"><?php echo $row['client_address'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="25%">Produk</th>
                <th width="10%">Volume</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga Satuan</th>
                <th width="15%">Pajak</th>
                <th width="20%">Nilai</th>
            </tr>
            <?php
           	$no=1;
           	$subtotal = 0;
            $tax_pph = 0;
            $tax_ppn = 0;
			$tax_ppn11 = 0;
            $tax_0 = false;
            $total = 0;
           	foreach ($data as $dt) {
               ?>  
               <tr>
                   <td align="center"><?php echo $no;?></td>
                   <td align="center"><?= $dt["nama_produk"] ?></td>
	               <td align="center"><?= $dt["qty"]; ?></td>
	               <td align="center"><?= $dt["measure"]; ?></td>
	               <td align="right"><?= number_format($dt['price'],0,',','.'); ?></td>
	               <td align="right"><?= number_format($dt['tax'],0,',','.'); ?></td>
	               <td align="right"><?= number_format($dt['total'],0,',','.'); ?></td>
               </tr>
               <?php
               $no++;
               $subtotal += $dt['total'];
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
               // $total += $subtotal;
           	}
           	?>
            <tr>
               <th colspan="6" style="text-align:right">Sub Total</th>
               <th align="right"><?= number_format($subtotal,0,',','.'); ?></th>
            </tr>
            <?php
            if($tax_ppn > 0){
                ?>
                <tr>
                    <th colspan="6" align="right">Pajak (PPN 10%)</th>
                    <th  align="right"><?= number_format($tax_ppn,0,',','.'); ?></th>
                </tr>
                <?php
            }
            ?>
            <?php
            if($tax_0){
                ?>
                <tr>
                    <th colspan="6" align="right">Pajak (PPN 0%)</th>
                    <th  align="right"><?= number_format(0,0,',','.'); ?></th>
                </tr>
                <?php
            }
            ?>
            <?php
            if($tax_pph > 0){
                ?>
                <tr>
                    <th colspan="6" align="right">Pajak (PPh 23)</th>
                    <th align="right"><?= number_format($tax_pph,0,',','.'); ?></th>
                </tr>
                <?php
            }
			?>
			<?php
            if($tax_ppn11 > 0){
                ?>
                <tr>
                    <th colspan="6" align="right">Pajak (PPN 11%)</th>
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
                <th colspan="6" align="right">TOTAL</th>
                <th align="right"><?= number_format($total,0,',','.'); ?></th>
            </tr>
			<tr>
                <th colspan="6" align="right">UANG MUKA (<?= number_format($presentase_uang_muka,0,',','.'); ?>%)</th>
                <th align="right"><?= number_format($row['uang_muka'],0,',','.'); ?></th>
            </tr>
		</table>
		<br />
	    <p><b>Memo</b></p>
		<p><?= $row["memo"] ?></p>
		<br /><br /><br /><br />
		<?php
			$kepala_unit_bisnis = $this->pmm_model->GetNameGroup(15);
		?>
		<table width="98%" border="0" cellpadding="0">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="98%" border="0" cellpadding="2">
						<tr class="">
							<td align="center" >
								PENERIMA ORDER
							</td>
							<td align="center" >
							    PEMBERI ORDER
							</td>
						</tr>
						<tr class="table-bold">
							<td align="center" >
								<?php echo $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'nama');?>
							</td>
							<td align="center" >
								PT BIA BUMI JAYENDRA
							</td>
						</tr>
						<tr class="">
							<td align="center" height="75px">
								
							</td>
							<td align="center">
								<img src="uploads/ttd_dadang.png" width="100px">
							</td>
						</tr>
						<tr class="table-bold">
							<td align="center">
							    <u><?php echo $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'nama_kontak');?></u><br />
								<?php
								$this->db->select('g.admin_group_name');
								$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
								$this->db->where('a.admin_id',$row['created_by']);
								$created_group = $this->db->get('tbl_admin a')->row_array();
								?>
								<?php echo $this->crud_global->GetField('penerima',array('id'=>$row['client_id']),'posisi');?>
							</td>
							<td align="center">
								<u><?=  $kepala_unit_bisnis['admin_name'];?></u><br />
								<?=  $kepala_unit_bisnis['admin_group_name'];?>
								
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>

	</body>
</html>