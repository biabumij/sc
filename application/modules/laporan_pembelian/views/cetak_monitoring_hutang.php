<!DOCTYPE html>
<html>
	<head>
	  <title>MONITORING HUTANG</title>
	  
	  <style type="text/css">
		table tr.table-judul{
			background-color: #e69500;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
			
		table tr.table-baris1{
			background-color: #F0F0F0;
			font-size: 7px;
		}

		table tr.table-baris1-bold{
			background-color: #F0F0F0;
			font-size: 7px;
			font-weight: bold;
		}
			
		table tr.table-baris2{
			font-size: 7px;
			background-color: #E8E8E8;
		}

		table tr.table-baris2-bold{
			font-size: 7px;
			background-color: #E8E8E8;
			font-weight: bold;
		}
			
		table tr.table-total{
			background-color: #cccccc;
			font-weight: bold;
			font-size: 7px;
			color: black;
		}
	  </style>

	</head>
	<body>
		<table width="98%" border="0" cellpadding="15">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">Monitoring Hutang</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">Divisi Beton Proyek Bendungan TEMEF</div>
					<?php
					function tgl_indo($date2){
						$bulan = array (
							1 =>   'Januari',
							'Februari',
							'Maret',
							'April',
							'Mei',
							'Juni',
							'Juli',
							'Agustus',
							'September',
							'Oktober',
							'November',
							'Desember'
						);
						$pecahkan = explode('-', $date2);
						
						// variabel pecahkan 0 = tanggal
						// variabel pecahkan 1 = bulan
						// variabel pecahkan 2 = tahun
					
						return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
						
					}
					?>
					<div style="display: block;font-weight: bold;font-size: 11px;">Per <?= tgl_indo(date($date2)); ?></div>
				</td>
			</tr>
		</table>	
		<table cellpadding="2" width="98%">
			<tr class="table-judul">
				<th width="3%" align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
				<th width="7%" align="center">REKANAN</th>
				<th width="7%" align="center">NOMOR</th>
				<th width="7%" align="center">TANGGAL</th>
				<th width="7%" align="center">TANGGAL</th>
				<th width="17%" align="center" colspan="3">TAGIHAN</th>
				<th width="22%" align="center" colspan="4">PEMBAYARAN</th>
				<th width="17%" align="center" colspan="3">SISA HUTANG</th>
				<th width="14%" align="center" colspan="3">STATUS HUTANG</th>
			</tr>
			<tr class="table-judul">
				<th align="center">KETERANGAN</th>
				<th align="center">TAGIHAN</th>
				<th align="center">TAGIHAN</th>
				<th align="center">VERIFIKASI</th>
				<th align="center">DPP</th>
				<th align="center">PPN</th>
				<th align="center">JUMLAH</th>
				<th align="center">DPP</th>
				<th align="center">PPN</th>
				<th align="center">PPH</th>
				<th align="center">JUMLAH</th>
				<th align="center">DPP</th>
				<th align="center">PPN</th>
				<th align="center">JUMLAH</th>
				<th align="center">STATUS</th>
				<th align="center">UMUR</th>
				<th align="center">JATUH TEMPO</th>
			</tr>		
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
            			<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="17"><?php echo $row['name'];?></td>
            		</tr>
					<?php
					$jumlah_dpp_tagihan = 0;
					$jumlah_ppn_tagihan = 0;
					$jumlah_jumlah_tagihan = 0;
					$jumlah_dpp_pembayaran = 0;
					$jumlah_ppn_pembayaran = 0;
					$jumlah_pph_pembayaran = 0;
					$jumlah_jumlah_pembayaran = 0;
					$jumlah_dpp_sisa_hutang = 0;
					$jumlah_ppn_sisa_hutang = 0;
					$jumlah_jumlah_sisa_hutang = 0;
            		foreach ($row['mats'] as $mat) {
            			?>
					<tr class="table-baris1">
						<td align="center"></td>
						<td align="left"><?php echo $mat['subject'];?></td>
						<td align="center"><?php echo $mat['nomor_invoice'];?></td>
            			<td align="center"><?php echo $mat['tanggal_invoice'];?></td>
            			<td align="left"><?php echo $mat['tanggal_lolos_verifikasi'];?></td>
            			<td align="right"><?php echo $mat['dpp_tagihan'];?></td>
						<td align="right"><?php echo $mat['ppn_tagihan'];?></td>
						<td align="right"><?php echo $mat['jumlah_tagihan'];?></td>
						<td align="right"><?php echo $mat['dpp_pembayaran'];?></td>
						<td align="right"><?php echo $mat['ppn_pembayaran'];?></td>
						<td align="right"><?php echo $mat['pph_pembayaran'];?></td>
						<td align="right"><?php echo $mat['jumlah_pembayaran'];?></td>
						<td align="right"><?php echo $mat['dpp_sisa_hutang'];?></td>
						<td align="right"><?php echo $mat['ppn_sisa_hutang'];?></td>
						<td align="right"><?php echo $mat['jumlah_sisa_hutang'];?></td>
						<td align="center"><?php echo $mat['status'];?></td>
						<td align="center"><?php echo $mat['syarat_pembayaran'];?></td>
						<td align="center"><?php echo $mat['jatuh_tempo'];?></td>
            		</tr>

					<?php
					$jumlah_dpp_tagihan += str_replace(['.', ','], ['', '.'], $mat['dpp_tagihan']);
					$jumlah_ppn_tagihan += str_replace(['.', ','], ['', '.'], $mat['ppn_tagihan']);
					$jumlah_jumlah_tagihan += str_replace(['.', ','], ['', '.'], $mat['jumlah_tagihan']);
					$jumlah_dpp_pembayaran += str_replace(['.', ','], ['', '.'], $mat['dpp_pembayaran']);
					$jumlah_ppn_pembayaran += str_replace(['.', ','], ['', '.'], $mat['ppn_pembayaran']);
					$jumlah_pph_pembayaran += str_replace(['.', ','], ['', '.'], $mat['pph_pembayaran']);
					$jumlah_jumlah_pembayaran += str_replace(['.', ','], ['', '.'], $mat['jumlah_pembayaran']);
					$jumlah_dpp_sisa_hutang += str_replace(['.', ','], ['', '.'], $mat['dpp_sisa_hutang']);
					$jumlah_ppn_sisa_hutang += str_replace(['.', ','], ['', '.'], $mat['ppn_sisa_hutang']);
					$jumlah_jumlah_sisa_hutang += str_replace(['.', ','], ['', '.'], $mat['jumlah_sisa_hutang']);
					}	
					?>
					<tr class="table-baris2-bold">
						<td align="right" colspan="5">JUMLAH</td>
						<td align="right"><?php echo number_format($jumlah_dpp_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_ppn_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_jumlah_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_dpp_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_ppn_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_pph_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_jumlah_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_dpp_sisa_hutang,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_ppn_sisa_hutang,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_jumlah_sisa_hutang,0,',','.');?></td>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
            		</tr>
					<?php
            		}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="18" align="center">NO DATA</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
				<th align="right" colspan="5">TOTAL</th>
				<th align="right"><?php echo number_format($total_dpp_tagihan,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_ppn_tagihan,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_jumlah_tagihan,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_dpp_pembayaran,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_ppn_pembayaran,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_pph_pembayaran,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_jumlah_pembayaran,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_dpp_sisa_hutang,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_ppn_sisa_hutang,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_jumlah_sisa_hutang,0,',','.');?></th>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
            </tr>   
		</table>
		
	</body>
</html>