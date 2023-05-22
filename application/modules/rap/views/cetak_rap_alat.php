<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>

	  <style type="text/css">
		table tr.table-judul{
			background-color: #e69500;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: #F0F0F0;
			font-size: 8px;
		}

		table tr.table-baris1-bold{
			background-color: #F0F0F0;
			font-size: 8px;
			font-weight: bold;
		}
			
		table tr.table-baris2{
			font-size: 8px;
			background-color: #E8E8E8;
		}

		table tr.table-baris2-bold{
			font-size: 8px;
			background-color: #E8E8E8;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 8px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<table width="100%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 12px;">RAP ALAT<br/>
					PROYEK BENDUNGAN TEMEF<br/>
					PT. BIA BUMI JAYENDRA<br/></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table width="100%" border="0">
			<tr>
				<th width="20%">Tanggal</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?= convertDateDBtoIndo($rap_alat["tanggal_rap_alat"]); ?></th>
			</tr>
			<tr>
				<th>Nomor</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $rap_alat['nomor_rap_alat'];?></th>
			</tr>
			<tr>
				<th>Masa Kontrak</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $rap_alat['masa_kontrak'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="5" width="98%">
			<tr class="table-judul">
				<?php
					$total = 0;
					?>
					<?php
					$total = $rap_alat['batching_plant'] + $rap_alat['truck_mixer'] + $rap_alat['wheel_loader'] + $rap_alat['bbm_solar'];
				?>
                <th width="5%" align="center">NO.</th>
                <th width="20%" align="center">URAIAN</th>
				<th width="20%" align="center">VOLUME</th>
				<th width="15%" align="center">SATUAN</th>
				<th width="20%" align="center">HARGA SATUAN</th>
				<th width="20%" align="center">TOTAL</th>
            </tr>
			<tr class="table-baris1">
				<td align="center">1.</td>
				<td align="left">BATCHING PLANT</td>
				<td align="center"><?= number_format($rap_alat['vol_batching_plant'],0,',','.'); ?></td>
				<td align="center">M3</td>
				<td align="right"><?= number_format($rap_alat['harsat_batching_plant'],0,',','.'); ?></td>
				<td align="right"><?= number_format($rap_alat['batching_plant'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center">2.</td>
				<td align="left">TRUCK MIXER</td>
				<td align="center"><?= number_format($rap_alat['vol_truck_mixer'],0,',','.'); ?></td>
				<td align="center">M3</td>
				<td align="right"><?= number_format($rap_alat['harsat_truck_mixer'],0,',','.'); ?></td>
				<td align="right"><?= number_format($rap_alat['truck_mixer'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center">3.</td>
				<td align="left">WHEEL LOADER</td>
				<td align="center"><?= number_format($rap_alat['vol_wheel_loader'],0,',','.'); ?></td>
				<td align="center">M3</td>
				<td align="right"><?= number_format($rap_alat['harsat_wheel_loader'],0,',','.'); ?></td>
				<td align="right"><?= number_format($rap_alat['wheel_loader'],0,',','.'); ?></td>
			</tr>
			<tr class="table-baris1">
				<td align="center">4.</td>
				<td align="left">BBM SOLAR</td>
				<td align="center"><?= number_format($rap_alat['vol_bbm_solar'],0,',','.'); ?></td>
				<td align="center">Liter</td>
				<td align="right"><?= number_format($rap_alat['harsat_bbm_solar'],0,',','.'); ?></td>
				<td align="right"><?= number_format($rap_alat['bbm_solar'],0,',','.'); ?></td>
			</tr>
			<tr class="table-total">
				<td align="right" colspan="5">GRAND TOTAL</td>
				<td align="right"><?= number_format($total,0,',','.'); ?></td>
			</tr>
		</table>
		<br />
		<br />
		
	</body>
</html>