<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>Laporan Produksi Stone Crusher</title>
	  
	  <style type="text/css">
	  	body{
			font-family: helvetica;
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
            background-color: ;
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
					<div style="display: block;font-weight: bold;font-size: 12px;">LAPORAN PRODUKSI</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">STONE CRUSHER</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">PT. BIA BUMI JAYENDRA</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<th width="20%">Tanggal</th>
				<th width="2%">:</th>
				<th align="left"><?= convertDateDBtoIndo($row["date_prod"]); ?></th>
			</tr>
			<tr>
				<th>Nomor Produksi</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $row['no_prod'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="35%" align="left">Nomor Kalibrasi</th>
                <th width="30%" align="right">Durasi Produksi</th>
                <th width="30%" align="right">Kapasitas Produksi (Ton/Jam)</th>
            </tr>
            <?php
           	$no = 1;
			$total_duration = 0;
			$total_use = 0;
			$total_capacity = 0;
           		foreach ($data as $no => $dt) {
				$total_duration += $dt['duration'];
				$total_use += $dt['use'];
				$total_capacity = ($total_duration!=0)?($total_use / $total_duration)  * 1:0;	
               ?>  
               <tr>
                   <td align="center"><?php echo $no + 1;?></td>
                   <td align="left"><?= $dt["no_kalibrasi"] ?></td>
	               <td align="right"><?= $dt["duration"]; ?></td>
	               <td align="right"><?= $dt["capacity"]; ?></td>
               </tr>
			   <?php	
			}
			    ?> 
				<tr>
					<td align="center" colspan="2"><b>TOTAL</b></td>
					<td align="right"><b><?php echo number_format($total_duration,2,',','.');?></b></td>
					<td align="right"><b><?php echo number_format($total_capacity,2,',','.');?></b></td>
			   </tr>
		</table>
		<br /><br /><br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="25%" align="left">Uraian</th>
                <th width="20%">Satuan</th>
                <th width="25%" align="right">Presentase</th>
				<th width="25%" align="right">Volume Kalibrasi</th>
            </tr>
            <?php
           	$no = 1;
			$total_presentase = 0;
			$total_volume = 0;
           		foreach ($detail as $no => $dt) {
				$total_presentase += $dt['presentase_a'] + $dt['presentase_b'] + $dt['presentase_c'] + $dt['presentase_d'] + $dt['presentase_e'] + $dt['presentase_f'];
				$total_volume += $dt["jumlah_pemakaian_a"] + $dt["jumlah_pemakaian_b"] + $dt["jumlah_pemakaian_c"] + $dt["jumlah_pemakaian_d"] + $dt["jumlah_pemakaian_e"] + $dt["jumlah_pemakaian_f"];
			?>  
               <tr>
                   <td align="center"><?php echo $no + 1;?></td>
                   <td align="left"><?= $dt["produk_a"] = $this->crud_global->GetField('produk',array('id'=>$dt['produk_a']),'nama_produk'); ?></td>
	               <td align="center"><?= $dt["measure_a"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure_a']),'measure_name'); ?></td>
	               <td align="right"><?= $dt["presentase_a"]; ?> %</td>
				   <td align="right"><?php echo number_format($dt["jumlah_pemakaian_a"],2,',','.');?></td>
               </tr>
			   <tr>
                   <td align="center"><?php echo $no + 2;?></td>
                   <td align="left"><?= $dt["produk_b"] = $this->crud_global->GetField('produk',array('id'=>$dt['produk_b']),'nama_produk'); ?></td>
	               <td align="center"><?= $dt["measure_b"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure_b']),'measure_name'); ?></td>
	               <td align="right"><?= $dt["presentase_b"]; ?> %</td>
				   <td align="right"><?php echo number_format($dt["jumlah_pemakaian_b"],2,',','.');?></td>
               </tr>
			   <tr>
                   <td align="center"><?php echo $no + 3;?></td>
                   <td align="left"><?= $dt["produk_f"] = $this->crud_global->GetField('produk',array('id'=>$dt['produk_f']),'nama_produk'); ?></td>
	               <td align="center"><?= $dt["measure_f"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure_f']),'measure_name'); ?></td>
	               <td align="right"><?= $dt["presentase_f"]; ?> %</td>
				   <td align="right"><?php echo number_format($dt["jumlah_pemakaian_f"],2,',','.');?></td>
               </tr>
			   <tr>
                   <td align="center"><?php echo $no + 4;?></td>
                   <td align="left"><?= $dt["produk_c"] = $this->crud_global->GetField('produk',array('id'=>$dt['produk_c']),'nama_produk'); ?></td>
	               <td align="center"><?= $dt["measure_c"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure_c']),'measure_name'); ?></td>
	               <td align="right"><?= $dt["presentase_c"]; ?> %</td>
				   <td align="right"><?php echo number_format($dt["jumlah_pemakaian_c"],2,',','.');?></td>
               </tr>
			   <tr>
                   <td align="center"><?php echo $no + 4;?></td>
                   <td align="left"><?= $dt["produk_d"] = $this->crud_global->GetField('produk',array('id'=>$dt['produk_d']),'nama_produk'); ?></td>
	               <td align="center"><?= $dt["measure_d"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure_d']),'measure_name'); ?></td>
	               <td align="right"><?= $dt["presentase_d"]; ?> %</td>
				   <td align="right"><?php echo number_format($dt["jumlah_pemakaian_d"],2,',','.');?></td>
               </tr>
			   <tr>
                   <td align="center"><?php echo $no + 6;?></td>
                   <td align="left"><?= $dt["produk_e"] = $this->crud_global->GetField('produk',array('id'=>$dt['produk_e']),'nama_produk'); ?></td>
	               <td align="center"><?= $dt["measure_e"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure_e']),'measure_name'); ?></td>
	               <td align="right"><?= $dt["presentase_e"]; ?> %</td>
				   <td align="right"><?php echo number_format($dt["jumlah_pemakaian_e"],2,',','.');?></td>
               </tr>
			   <?php	
			}
			    ?> 
				<tr>
					<td align="center" colspan="3"><b>TOTAL</b></td>
					<td align="right"><b><?php echo number_format($total_preseentase,2,',','.');?></b></td>
					<td align="right"><b><?php echo number_format($total_volume,2,',','.');?></b></td>
			   </tr>
		</table>
		
	    <p><b>Keterangan</b> :</p>
		<p><?= $row["memo"] ?></p>

        <table width="98%" border="0" cellpadding="3">
			<tr>
                <th width="70%"></th>
				<th width="30%">
					<table width="100%" border="1" cellpadding="2">
						<tr class="">
							<td align="right" height="50px">
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