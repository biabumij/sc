<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>ANALISA HARGA SATUAN</title>
	  
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
            background-color: #e69500 ;
			font-weight: bold;
        }
        table tr.table-active2{
			font-weight: bold;
        }
		table tr.table-active3{
            background-color: #FFFF00;
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
		<table width="100%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 12px;">ANALISA HARGA SATUAN</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">DIVISI STONE CRUSHER</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table class="head" width="100%" border="0" cellpadding="3">
			<tr>
				<th width="20%">JENIS PEKERJAAN</th>
				<th width="2%">:</th>
				<th align="left"><div style="text-transform:uppercase;"><?php echo $row['jobs_type'];?></div></th>
			</tr>
			<tr>
				<th>TANGGAL</th>
				<th>:</th>
				<th align="left"><div style="text-transform:uppercase;"><?= convertDateDBtoIndo($row["tanggal_rap"]); ?></div></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th align="center" rowspan="2" width="5%">&nbsp; <br />NO.</th>
				<th align="center" rowspan="2"  width="20%">&nbsp; <br />KOMPONEN</th>
				<th align="center" rowspan="2"  width="15%">&nbsp; <br />SATUAN</th>
				<th align="center" width="20%">PERKIRAAN KUANTITAS</th>
				<th align="center" rowspan="2"  width="20%">&nbsp; <br />HARGA SATUAN<br />(Rp.)</th>
				<th align="center" width="20%">JUMLAH HARGA</th>
            </tr>
			<tr class="table-active">
				<th align="center">(M3)</th>
				<th align="center">(M3)</th>
            </tr>
			<?php
			$vol_boulder = round($row['vol_boulder'],4);
			$nilai_boulder = $vol_boulder * $row['price_boulder'];

			$sc_a = round($row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc'],4);
			$sc_b = $sc_a / round($row['berat_isi_batu_pecah'],4);
			$vol_sc = round(1 / $sc_b,4);
			$nilai_sc = $vol_sc * $row['price_sc'];

			$vol_tangki = $vol_sc;
			$nilai_tangki = $vol_tangki * $row['price_tangki'];

			$vol_gns = $vol_sc;
			$nilai_gns = $vol_gns * $row['price_gns'];

			$wl_a = round($row['kapasitas_alat_wl'] * $row['efisiensi_alat_wl'],4);
			$wl_b = (60 / round($row['waktu_siklus'],4)) * $wl_a;
			$vol_wl = round(1 / $wl_b,4);

			$vol_timbangan =  $vol_sc;
			$nilai_timbangan = $vol_timbangan * $row['price_timbangan'];

			$total = $nilai_boulder + $nilai_tangki + $nilai_sc + $nilai_gns + $nilai_wl + $nilai_timbangan + $row['overhead'];

			?>
			<tr class="table-active2">
				<td align="center">A.</td>
				<td align="left"><u>BAHAN</u></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center">1.</td>
				<td align="left">Boulder</td>
				<td align="center"><?php echo $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_boulder']),'measure_name');?></td>
				<td align="center"><?php echo number_format($row['vol_boulder'],4,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_boulder'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_boulder,0,',','.');?></td>
			</tr>
			<tr class="table-active3">
				<td align="right" colspan="5">JUMLAH HARGA BAHAN</td>
				<td align="right"><?php echo number_format($nilai_boulder,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center">B.</td>
				<td align="left"><u>PERALATAN</u></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center">1.</td>
				<td align="left">Tangki Solar</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_tangki,4,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_tangki'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_tangki,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left">Stone Crusher</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_sc,4,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_sc'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_sc,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left">Genset</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_gns,4,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_gns'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_gns,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left">Wheel Loader</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_wl,4,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_wl'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_wl,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">5.</td>
				<td align="left">Timbangan</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_timbangan,4,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_timbangan'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_timbangan,0,',','.');?></td>
			</tr>
			<tr class="table-active3">
				<td align="right" colspan="5">JUMLAH HARGA PERALATAN</td>
				<td align="right"><?php echo number_format($nilai_tangki + $nilai_sc + $nilai_gns + $nilai_wl + $nilai_timbangan,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center">C.</td>
				<td align="left"><u>OVERHEAD</u></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="right"></td>
				<td align="right"><?php echo number_format($row['overhead'],0,',','.');?></td>
			</tr>
			<tr class="table-active3">
				<td align="right" colspan="5">JUMLAH HARGA OVERHEAD</td>
				<td align="right"><?php echo number_format($row['overhead'],0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center" colspan="6"></td>
			</tr>
			<tr class="table-active3">
				<td align="right">D</td>
				<td align="right" colspan="4">JUMLAH A+B+C</td>
				<td align="right"><?php echo number_format($total,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center" colspan="6"></td>
			</tr>
			<tr class="table-active">
				<td align="right">E.</td>
				<td align="right" colspan="4">HARGA SATUAN PEKERJAAN</td>
				<td align="right"><?php echo number_format($total,0,',','.');?></td>
			</tr>
		</table>
		
		<br />
		
	    <p><b>Keterangan</b> :</p>
		<p><?= $row["memo"] ?></p>

	</body>
</html>