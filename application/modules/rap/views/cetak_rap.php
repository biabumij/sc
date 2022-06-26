<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>RAP</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 12px;">RAP</div>
				</td>
			</tr>
		</table>
		<br />
		<table width="100%" border="0">
			<tr>
				<th width="20%">Tanggal</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?= convertDateDBtoIndo($rap["tanggal_rap"]); ?></th>
			</tr>
			<tr>
				<th>Produk</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $rap['mutu_beton'];?></th>
			</tr>
			<tr>
				<th>Slump</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $rap['slump'];?></th>
			</tr>
			<tr>
				<th>Nomor HPP</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $rap['nomor_rap'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="15%">Komponen</th>
                <th width="10%">Satuan</th>
                <th width="12%">Komposisi</th>
                <th width="34%">Rekanan</th>
				<th width="12%">Harga Satuan</th>
				<th width="12%">Jumlah Harga</th>
            </tr>
			<tr>
				<td align="center">1.</td>
				<td align="left"><?= $rap["semen_2"] = $this->crud_global->GetField('produk',array('id'=>$rap['semen_2']),'nama_produk'); ?></td>
				<td align="center"><?= $rap["measure_a"] = $this->crud_global->GetField('pmm_measures',array('id'=>$rap['measure_a']),'measure_name'); ?></td>
				<td align="right"><?= $rap["komposisi_a"] = $this->crud_global->GetField('pmm_jmd',array('id'=>$rap['komposisi_a']),'koef_a'); ?></td>
				<td align="left"><?= $rap["rekanan_a"] = $this->crud_global->GetField('penerima',array('id'=>$rap['rekanan_a']),'nama'); ?></td>
				<td align="right"><?= number_format($rap['harga_satuan_a'],2,',','.'); ?></td>
				<td align="right"><?= number_format($rap['jumlah_harga_a'],2,',','.'); ?></td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left"><?= $rap["pasir_3"] = $this->crud_global->GetField('produk',array('id'=>$rap['pasir_3']),'nama_produk'); ?></td>
				<td align="center"><?= $rap["measure_b"] = $this->crud_global->GetField('pmm_measures',array('id'=>$rap['measure_b']),'measure_name'); ?></td>
				<td align="right"><?= $rap["komposisi_b"] = $this->crud_global->GetField('pmm_jmd',array('id'=>$rap['komposisi_b']),'koef_b'); ?></td>
				<td align="left"><?= $rap["rekanan_b"] = $this->crud_global->GetField('penerima',array('id'=>$rap['rekanan_b']),'nama'); ?></td>
				<td align="right"><?= number_format($rap['harga_satuan_b'],2,',','.'); ?></td>
				<td align="right"><?= number_format($rap['jumlah_harga_b'],2,',','.'); ?></td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left"><?= $rap["batu_split_12"] = $this->crud_global->GetField('produk',array('id'=>$rap['batu_split_12']),'nama_produk'); ?></td>
				<td align="center"><?= $rap["measure_c"] = $this->crud_global->GetField('pmm_measures',array('id'=>$rap['measure_c']),'measure_name'); ?></td>
				<td align="right"><?= $rap["komposisi_c"] = $this->crud_global->GetField('pmm_jmd',array('id'=>$rap['komposisi_c']),'koef_c'); ?></td>
				<td align="left"><?= $rap["rekanan_c"] = $this->crud_global->GetField('penerima',array('id'=>$rap['rekanan_c']),'nama'); ?></td>
				<td align="right"><?= number_format($rap['harga_satuan_c'],2,',','.'); ?></td>
				<td align="right"><?= number_format($rap['jumlah_harga_c'],2,',','.'); ?></td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left"><?= $rap["batu_split_23"] = $this->crud_global->GetField('produk',array('id'=>$rap['batu_split_23']),'nama_produk'); ?></td>
				<td align="center"><?= $rap["measure_d"] = $this->crud_global->GetField('pmm_measures',array('id'=>$rap['measure_d']),'measure_name'); ?></td>
				<td align="right"><?= $rap["komposisi_d"] = $this->crud_global->GetField('pmm_jmd',array('id'=>$rap['komposisi_d']),'koef_d'); ?></td>
				<td align="left"><?= $rap["rekanan_d"] = $this->crud_global->GetField('penerima',array('id'=>$rap['rekanan_d']),'nama'); ?></td>
				<td align="right"><?= number_format($rap['harga_satuan_d'],2,',','.'); ?></td>
				<td align="right"><?= number_format($rap['jumlah_harga_d'],2,',','.'); ?></td>
			</tr>
			<tr>
				<td align="center">5.</td>
				<td align="left"><?= $rap["additon"] = $this->crud_global->GetField('produk',array('id'=>$rap['additon']),'nama_produk'); ?></td>
				<td align="center"><?= $rap["measure_e"] = $this->crud_global->GetField('pmm_measures',array('id'=>$rap['measure_e']),'measure_name'); ?></td>
				<td align="right"><?= $rap["komposisi_e"] = $this->crud_global->GetField('pmm_jmd',array('id'=>$rap['komposisi_e']),'koef_e'); ?></td>
				<td align="left"><?= $rap["rekanan_e"] = $this->crud_global->GetField('penerima',array('id'=>$rap['rekanan_e']),'nama'); ?></td>
				<td align="right"><?= number_format($rap['harga_satuan_e'],2,',','.'); ?></td>
				<td align="right"><?= number_format($rap['jumlah_harga_e'],2,',','.'); ?></td>
			</tr>
		</table>
		<br />
		<br />
		
	</body>
</html>