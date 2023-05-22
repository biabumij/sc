<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>RAP BAHAN</title>
	  
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
		table.head tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  text-align: left;
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
		<table width="100%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 12px;">RAP BAHAN</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">BETON READY MIX</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table class="head" width="100%" border="0" cellpadding="3">
			<tr>
				<th width="20%">Mutu Beton / Slump</th>
				<th width="2%">:</th>
				<th align="left"><?php echo $row['mutu_beton'] = $this->crud_global->GetField('produk',array('id'=>$row['mutu_beton']),'nama_produk');?></th>
			</tr>
			<tr>
				<th width="20%">Volume</th>
				<th width="2%">:</th>
				<th align="left"><?php echo $row['volume'];?></th>
			</tr>
			<tr>
				<th width="20%">Satuan</th>
				<th width="2%">:</th>
				<th align="left"><?php echo $row['measure'] = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');?></th>
			</tr>
			<tr>
				<th width="20%">Judul</th>
				<th width="2%">:</th>
				<th align="left"><?php echo $row['jobs_type'];?></th>
			</tr>
			<tr>
				<th>Tanggal</th>
				<th>:</th>
				<th align="left"><?= convertDateDBtoIndo($row["date_agregat"]); ?></th>
			</tr>
			<tr>
				<th>Tes Lainnya</th>
				<th>:</th>
				<th align="left"><?php echo $row['tes'];?> Hari</th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th align="center" width="5%">No</th>
				<th align="center" width="20%">Uraian</th>
				<th align="center" width="15%">Satuan</th>
				<th align="center" width="20%">Komposisi</th>
				<th align="center" width="20%">Harga Satuan</th>
				<th align="center" width="20%">Nilai</th>
            </tr>
			<tr>
				<?php
				$total = 0;
				?>
				<?php
				$total = $row['total_a'] + $row['total_b'] + $row['total_c'] + $row['total_d'];
				?>
				<td align="center">1.</td>
				<td align="left"><?= $row["produk_a"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_a']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_a"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_a']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_a"]; ?></td>
				<td align="right"><?php echo number_format($row["price_a"],0,',','.');?></td>
				<td align="right"><?php echo number_format($row["total_a"],0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left"><?= $row["produk_b"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_b']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_b"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_b']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_b"]; ?></td>
				<td align="right"><?php echo number_format($row["price_b"],0,',','.');?></td>
				<td align="right"><?php echo number_format($row["total_b"],0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left"><?= $row["produk_c"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_c']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_c"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_c']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_c"]; ?></td>
				<td align="right"><?php echo number_format($row["price_c"],0,',','.');?></td>
				<td align="right"><?php echo number_format($row["total_c"],0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left"><?= $row["produk_d"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_d']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_d"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_d']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_d"]; ?></td>
				<td align="right"><?php echo number_format($row["price_d"],0,',','.');?></td>
				<td align="right"><?php echo number_format($row["total_d"],0,',','.');?></td>
			</tr>
			<tr>
				<td align="right" colspan="5"><b>TOTAL</b></td>
				<td align="right"><b><?php echo number_format($total,0,',','.');?></b></td>
			</tr>		
		</table>
		
		<br />
		
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