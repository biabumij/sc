<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>Komposisi Agregat</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 12px;">Komposisi Agregat</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="100%" border="0" cellpadding="3">
			<tr>
				<th width="20%">>Judul</th>
				<th width="2%">:</th>
				<th align="left"><?php echo $row['jobs_type'];?></th>
			</tr>
			<tr>
				<th>Tanggal</th>
				<th>:</th>
				<th align="left"><?= convertDateDBtoIndo($row["date_agregat"]); ?></th>
			</tr>
			<tr>
				<th>Nomor Komposisi</th>
				<th>:</th>
				<th align="left"><?php echo $row['no_komposisi'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th align="center" width="5%">No</th>
				<th align="center" width="35%">Produk</th>
				<th align="center" width="30%">Aggregat</th>
				<th align="center" width="30%">Presentase</th>
            </tr>
			<tr>
				<?php
				$total = 0;
				?>
				<?php
				$total = $row['presentase_a'] + $row['presentase_b'] + $row['presentase_c'] + $row['presentase_d'];
				?>
				<td align="center">1.</td>
				<td align="left"><?= $row["produk_a"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_a']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_a"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_a']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_a"]; ?> %</td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left"><?= $row["produk_b"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_b']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_b"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_b']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_b"]; ?> %</td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left"><?= $row["produk_c"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_c']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_c"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_c']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_c"]; ?> %</td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left"><?= $row["produk_d"] = $this->crud_global->GetField('produk',array('id'=>$row['produk_d']),'nama_produk'); ?></td>
				<td align="center"><?= $row["measure_d"]  = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_d']),'measure_name'); ?></td>
				<td align="center"><?= $row["presentase_d"]; ?> %</td>
			</tr>
			<tr>
				<td align="center" colspan="3"><b>TOTAL</b></td>
				<td align="center"><b><?php echo number_format($total,2,',','.');?> %</b></td>
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