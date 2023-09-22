<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  
	  <title>Penawaran Penjualan</title>

	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
	
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 2px solid #000000;
		  
		}
		table.minimalistBlack tr td {
		   text-align: center;
		  
		}
		table.minimalistBlack tr th {
		  font-weight: bold;
		  color: #000000;
		  text-align: center;
		}
		table tr.table-active{
            background-color: #e19669;
        }
	  </style>
    
	</head>
	<body>
	    
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<th width="20%">Nomor</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?php echo $row['nomor'];?></th>
				<td align="left" width="28%">
					Tulungagung, <?= convertDateDBtoIndo($row["tanggal"]); ?>
				</td>
			</tr>
			<tr>
				<th width="20%">Lampiran</th>
				<th width="2%">:</th>
				<th width="50%" align="left">-</th>
			</tr>
			<tr>
                <th width="20%">Perihal</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?= $row["perihal"]; ?></th>
            </tr>
		</table>
        <br /><br />
        <table width="98%" border="0" cellpadding="3">
			<tr>
				<td>Kepada Yth,</td>
			</tr>
            <tr>
                <th width="50%"><b><?= $row["client_name"]; ?></b></th>
            </tr>
            <tr>
                <th width="50%"><b><?= $row["client_address"]; ?></b></th>
            </tr>
		</table>
		<br />
        <p><b>A. Mutu Harga dan Satuan</b></p>
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th rowspan="2" width="5%">&nbsp; <br />NO</th>
                <th rowspan="2" width="35%">&nbsp; <br />JENIS MATERIAL</th>
                <th rowspan="2" width="10%">&nbsp; <br />VOLUME</th>
                <!--<th rowspan="2" width="10%">&nbsp; <br />SATUAN</th>-->
                <th width="30%" colspan="2">HARGA SATUAN</th>
				<th rowspan="2" width="20%">&nbsp; <br />KETERANGAN</th>
            </tr>
			<tr class="table-active">
				<th>TON</th>
				<th>M3</th>
			</tr>
            <?php
           	$no=1;
           	$subtotal = 0;
            $tax_pph = 0;
            $tax_ppn = 0;
            $tax_0 = false;
            $total = 0;
           	foreach ($data as $dt) {
                // $subtotal = $dt['total'] * $dt['price'];
           		$tax = $this->crud_global->GetField('pmm_taxs',array('id'=>$dt['tax_id']),'tax_name');
               ?>
               <?php
                    $measure = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure']),'measure_name');
                ?>
               <tr class="">
                   <td><?php echo $no;?></td>
                   <td align="left"><?= $dt["product"] ?></td>
                   <td><?= $dt["qty"] ?></td>
	               <!--<td><?= $measure; ?></td>-->
				   <td align="right">Rp. <?= number_format($dt['price'],0,',','.'); ?></td>
				   <td align="right">Rp. <?= number_format($dt['pricedua'],0,',','.'); ?></td>
				   <td align="left"><?= $dt["keterangan"] ?></td>
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
               // $total += $subtotal;
           	}
           	?>

		</table>
		<br />
		<br />
		<p><b>B. Persyaratan Harga</b></p>
		<p><?= $row["persyaratan_harga"] ?></p>
		<?php
			$kepala_unit_bisnis = $this->pmm_model->GetNameGroup(15);
		?>
		<table width="98%" border="0" cellpadding="0" align="center">
			<tr>
				<td width="35%"></td>
				<td width="30%"></td>
                <td width="30%">
					Hormat Kami,
				</td>
            </tr>
            <tr>
				<td></td>
				<td></td>
            	<td><b>PT BIA BUMI JAYENDRA</b></td>
            </tr>
            <tr>
				<td></td>
				<td></td>
				<th height="35px">
					<img src="uploads/ttd_dadang.png" width="70px">
				</th>
            </tr>
            <tr>
				<td></td>
				<td></td>
				<td align="center" >
					<b><u><?= $kepala_unit_bisnis['admin_name'];?></u><br />
					<?= $kepala_unit_bisnis['admin_group_name'];?></b>
				</td>
            </tr>
		</table>
		<br /><br /><br /><br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
                <th width="70%">
					<i><u>Tembusan</u> : <br />
					1. Kantor Pusat PT. Waskita Karya<br />
					2. Arsip</i>
				</th>
				<th width="30%" align="right" style="margin-top:40px;">
					<table width="98%" border="1" cellpadding="2">
						<tr class="">
							<td align="right" height="40px">
							</td>
							<td align="right">
							</td>
							<td align="right">
							</td>
						</tr>
					</table>
				</th>
            </tr>
		</table>
	</body>
</html> 