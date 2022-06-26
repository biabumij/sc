<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>Job Mix Design</title>
	  
	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
		  text-align: align="left";
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
            background-color: #e69500;
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
		<table width="100%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 12px;">Job Mix Design</div>
				</td>
			</tr>
		</table>
		<br />
		<table width="100%" border="0">
			<tr>
				<th width="20%">Tanggal</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?= convertDateDBtoIndo($jmd["tanggal_jmd"]); ?></th>
			</tr>
			<tr>
				<th>Mutu Beton</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $jmd['mutu_beton'];?></th>
			</tr>
			<tr>
				<th>Slump</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $jmd['slump'];?></th>
			</tr>
			<tr>
				<th>Satuan</th>
				<th width="10px">:</th>
				<th width="50%" align="left">M3</th>
			</tr>
			<tr>
				<th>Komposisi</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $jmd['nomor_komposisi'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="35%">Uraian</th>
                <th width="15%">Kode</th>
                <th width="15%">Volume</th>
                <th width="15%">Satuan</th>
            </tr>
			<tr>
				<td align="center">1.</td>
				<td align="left"><?= $jmd["beton_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['beton_1']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_1"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_1']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_1"]; ?></td>
				<td align="center"><?= $jmd["measure_1"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_1']),'measure_name'); ?></td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left"><?= $jmd["semen_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['semen_1']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_2"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_2']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_2"]; ?></td>
				<td align="center"><?= $jmd["measure_2"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_2']),'measure_name'); ?></td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left"><?= $jmd["pasir_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['pasir_1']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_3"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_3']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_3"]; ?></td>
				<td align="center"><?= $jmd["measure_3"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_3']),'measure_name'); ?></td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left"><?= $jmd["aggregat_kasar_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['aggregat_kasar_1']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_4"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_4']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_4"]; ?></td>
				<td align="center"><?= $jmd["measure_4"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_4']),'measure_name'); ?></td>
			</tr>
			<tr>
				<td align="center">5.</td>
				<td align="left"><?= $jmd["faktor_kehilangan_1"] = $this->crud_global->GetField('produk',array('id'=>$jmd['faktor_kehilangan_1']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_5"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_5']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_5"]; ?></td>
				<td align="center"><?= $jmd["measure_5"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_5']),'measure_name'); ?></td>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="35%">Uraian</th>
                <th width="15%">Kode</th>
                <th width="15%">Vol. Campuran</th>
                <th width="15%">Satuan</th>
				<th width="15%">Koefisien</th>
            </tr>
			<?php
			$total = 0;
			?>
			<tr>
				<?php
				$total = $jmd["volume_8"] + $jmd["volume_9"] + $jmd["volume_10"] + $jmd["volume_11"] + $jmd["volume_12"];
				$koef_1 = $jmd["volume_8"] / $total * 100 ;
				$koef_2 = $jmd["volume_9"] / $total * 100 ;
				$koef_3 = $jmd["volume_10"] / $total * 100 ;
				$koef_4 = $jmd["volume_11"] / $total * 100 ;
				$koef_5 = $jmd["volume_12"] / $total * 100 ;
				$koef_a = ($jmd["volume_1"] * $jmd["volume_5"]) * $koef_1 / 100;
				$koef_b = ($jmd["volume_1"] * $jmd["volume_5"]) * ($koef_2 / 100) / 1.60;
				$koef_c = ($jmd["volume_1"] * $jmd["volume_5"]) * $koef_3 / 100;
				$koef_d = ($jmd["volume_1"] * $jmd["volume_5"]) * $koef_4 / 100;
				$koef_e = ($jmd["volume_1"] * $jmd["volume_5"]) * ($koef_5 / 100) * 1000;
				?>
				<td align="center">1.</td>
				<td align="left"><?= $jmd["semen_2"] = $this->crud_global->GetField('produk',array('id'=>$jmd['semen_2']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_8"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_8']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_8"]; ?></td>
				<td align="center"><?= $jmd["measure_8"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_8']),'measure_name'); ?></td>
				<td align="center"><?php echo number_format($koef_1,2,',','.');?> %</td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left"><?= $jmd["pasir_3"] = $this->crud_global->GetField('produk',array('id'=>$jmd['pasir_3']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_9"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_9']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_9"]; ?></td>
				<td align="center"><?= $jmd["measure_9"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_9']),'measure_name'); ?></td>
				<td align="center"><?php echo number_format($koef_2,2,',','.');?> %</td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left"><?= $jmd["batu_split_12"] = $this->crud_global->GetField('produk',array('id'=>$jmd['batu_split_12']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_10"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_10']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_10"]; ?></td>
				<td align="center"><?= $jmd["measure_10"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_10']),'measure_name'); ?></td>
				<td align="center"><?php echo number_format($koef_3,2,',','.');?> %</td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left"><?= $jmd["batu_split_23"] = $this->crud_global->GetField('produk',array('id'=>$jmd['batu_split_23']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_11"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_11']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_11"]; ?></td>
				<td align="center"><?= $jmd["measure_11"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_11']),'measure_name'); ?></td>
				<td align="center"><?php echo number_format($koef_4,2,',','.');?> %</td>
			</tr>
			<tr>
				<td align="center">5.</td>
				<td align="left"><?= $jmd["additon"] = $this->crud_global->GetField('produk',array('id'=>$jmd['additon']),'nama_produk'); ?></td>
				<td align="center"><?= $jmd["kode_12"] = $this->crud_global->GetField('produk_kode',array('id'=>$jmd['kode_12']),'kode'); ?></td>
				<td align="center"><?= $jmd["volume_12"]; ?></td>
				<td align="center"><?= $jmd["measure_12"] = $this->crud_global->GetField('pmm_measures',array('id'=>$jmd['measure_12']),'measure_name'); ?></td>
				<td align="center"><?php echo number_format($koef_5,2,',','.');?> %</td>
			</tr>
			<tr>
				<td align="right" colspan="3"><b>TOTAL</b></td>
				<td align="center"><b><?php echo number_format($total,2,',','.');?></b></td>
				<td align="center"></td>
				<td align="center"></td>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="50%">Uraian</th>
                <th width="15%">Satuan</th>
                <th width="15%">Koefisien</th>
            </tr>
			<tr>
				<td align="center">1.</td>
				<td align="left">Semen</td>
				<td align="center">Ton</td>
				<td align="center"><?php echo number_format($koef_a,4,',','.');?></td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left">Pasir</td>
				<td align="center">M3</td>
				<td align="center"><?php echo number_format($koef_b,4,',','.');?></td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left">Batu Split 1 - 2</td>
				<td align="center">Ton</td>
				<td align="center"><?php echo number_format($koef_c,4,',','.');?></td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left">Batu Split 2 - 3</td>
				<td align="center">Ton</td>
				<td align="center"><?php echo number_format($koef_d,4,',','.');?></td>
			</tr>
			<tr>
				<td align="center">5.</td>
				<td align="left">Additon</td>
				<td align="center">Kg</td>
				<td align="center"><?php echo number_format($koef_e,4,',','.');?></td>
			</tr>
		</table>
		<br />
		<br />
		<br />
		<table width="98%" border="0" cellpadding="10">
			<tr >
				<td width="15%"></td>
				<td width="70%">
					<table width="100%" border="1" cellpadding="2">
						<tr>
							<td align="center" >
								Disetujui oleh
							</td>
							<td align="center" >
								Diperiksa Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center" height="50px">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								
							</td>
						</tr>
						<tr>
							<td align="center" >
								<b>Ka. Plant</b>
							</td>
							<td align="center" >
								<b>Ka. Laboratorium</b>
							</td>
							<td align="center" >
								<b>Staff Laboratorium</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="15%"></td>
			</tr>
		</table>
	</body>
</html>