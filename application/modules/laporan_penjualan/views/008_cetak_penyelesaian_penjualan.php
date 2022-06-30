<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PENYELESAIAN PENJUALAN</title>
	  
	  <?php
		$search = array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
		);
		
		$replace = array(
		'Januari',
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
		
		$subject = "$filter_date";

		echo str_replace($search, $replace, $subject);

	  ?>
	  
	  <?php
		$search_1 = array(
		'OPEN',
		'CLOSED',
		'REJECTED',
		'WAITING',
		'DELETED'
		);
		
		$replace_1 = array(
		'TERKIRIM SEBAGIAN',
		'SELESAI',
		'DITOLAK',
		'BELUM TERKIRIM',
		'DIHAPUS'
		);

		echo str_replace($search_1, $replace_1, $subject_1);

	  ?>

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
		<table width="98%" cellpadding="15">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PENYELESAIAN PENJUALAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI STONE CRUSHER</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<table cellpadding="3" width="98%">
			<tr class="table-judul">
				<th align="center" width="7%">PELANGGAN</th>
				<th align="center" width="8%" rowspan="2">NO. ORDER</th>
				<th align="center" width="7%" rowspan="2">STATUS PEMESANAN</th>
				<th align="center" width="12%" colspan="2">PEMESANAN</th>
				<th align="center" width="11%" colspan="2">PENERIMAAN</th>
				<th align="center" width="11%" colspan="2">TAGIHAN</th>
				<th align="center" width="11%" colspan="2">PEMBAYARAN</th>
				<th align="center" width="11%" colspan="2">PIUTANG BRUTO</th>
				<th align="center" width="11%" colspan="2">PIUTANG TERHADAP TAGIHAN</th>
				<th align="center" width="11%" colspan="2">TOTAL</th>
            </tr>
			<tr class="table-judul">
				<th align="center">TGL. ORDER</th>
				<th align="center" width="5%">VOL.</th>
				<th align="center" width="7%">RP.</th>
				<th align="center" width="5%">VOL.</th>
				<th align="center" width="6%">RP.</th>
				<th align="center" width="5%">VOL.</th>
				<th align="center" width="6%">RP.</th>
				<th align="center" width="5%">VOL.</th>
				<th align="center" width="6%">RP.</th>
				<th align="center" width="5%">VOL.</th>
				<th align="center" width="6%">RP.</th>
				<th align="center" width="5%">VOL.</th>
				<th align="center" width="6%">RP.</th>
				<th align="center" width="5%">VOL.</th>
				<th align="center" width="6%">RP.</th>
            </tr>
           <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
            			<td align="left" colspan="3"><?php echo $row['nama'];?></td>
						<td align="center" colspan="14"></td>
            		</tr>
            		<?php
					$jumlah_vol_pemesanan = 0;
					$jumlah_pemesanan = 0;
					$jumlah_vol_pengiriman = 0;
					$jumlah_pengiriman = 0;
					$jumlah_sisa_pengiriman = 0;
					$jumlah_pengiriman = 0;
					$jumlah_vol_tagihan = 0;
					$jumlah_tagihan = 0;
					$jumlah_vol_pembayaran = 0;
					$jumlah_pembayaran = 0;
					$jumlah_vol_piutang_pengiriman = 0;
					$jumlah_piutang_pengiriman = 0;
					$jumlah_vol_sisa_tagihan = 0;
					$jumlah_sisa_tagihan = 0;
					$jumlah_vol_akhir = 0;
					$jumlah_akhir = 0;

					$vol_piutang_pengiriman = 0;
					$piutang_pengiriman = 0;
					$vol_sisa_tagihan = 0;
					$sisa_tagihan = 0;
					$vol_akhir = 0;
					$akhir = 0;

            		foreach ($row['mats'] as $mat) {
						$vol_piutang_pengiriman_a = str_replace(['.', ','], ['', '.'], $mat['vol_pengiriman']);
						$vol_piutang_pengiriman_b = str_replace(['.', ','], ['', '.'], $mat['vol_tagihan']);
						$vol_piutang_pengiriman = $vol_piutang_pengiriman_a - $vol_piutang_pengiriman_b;

						$piutang_pengiriman_a = str_replace(['.', ','], ['', '.'], $mat['pengiriman']);
						$piutang_pengiriman_b = str_replace(['.', ','], ['', '.'], $mat['tagihan']);
						$piutang_pengiriman = $piutang_pengiriman_a - $piutang_pengiriman_b;

						$vol_sisa_tagihan_a = str_replace(['.', ','], ['', '.'], $mat['vol_tagihan']);
						$vol_sisa_tagihan_b = str_replace(['.', ','], ['', '.'], $mat['vol_pembayaran']);
						$vol_sisa_tagihan = $vol_sisa_tagihan_a - $vol_sisa_tagihan_b;

						$sisa_tagihan_a = str_replace(['.', ','], ['', '.'], $mat['tagihan']);
						$sisa_tagihan_b = str_replace(['.', ','], ['', '.'], $mat['pembayaran']);
						$sisa_tagihan = $sisa_tagihan_a - $sisa_tagihan_b;

						$vol_akhir = $vol_piutang_pengiriman + $vol_sisa_tagihan;
						$akhir = $piutang_pengiriman + $sisa_tagihan;

            			?>
            			<tr class="table-baris1">
							<td align="center"><?php echo $mat['contract_date'];?></td>
							<td align="center"><?php echo $mat['contract_number'];?></td>
							<td align="center"><?php echo str_replace($search_1, $replace_1, $mat['status']);?></td>
							<td align="right"><?php echo $mat['vol_pemesanan'];?></td>
							<td align="right"><?php echo $mat['pemesanan'];?></td>
							<td align="right"><?php echo $mat['vol_pengiriman'];?></td>
							<td align="right"><?php echo $mat['pengiriman'];?></td>
							<td align="right"><?php echo $mat['vol_tagihan'];?></td>							
							<td align="right"><?php echo $mat['tagihan'];?></td>
							<td align="right"><?php echo $mat['vol_pembayaran'];?></td>
							<td align="right"><?php echo $mat['pembayaran'];?></td>
							<td align="right"><?php echo number_format($vol_piutang_pengiriman,2,',','.');?></td> 
							<td align="right"><?php echo number_format($piutang_pengiriman,0,',','.');?></td>   
							<td align="right"><?php echo number_format($vol_sisa_tagihan,2,',','.');?></td>
							<td align="right"><?php echo number_format($sisa_tagihan,0,',','.');?></td>	
							<td align="right"><?php echo number_format($vol_akhir,2,',','.');?></td>
							<td align="right"><?php echo number_format($akhir,0,',','.');?></td>
	            		</tr>
            			<?php

						$jumlah_vol_pemesanan += str_replace(['.', ','], ['', '.'], $mat['vol_pemesanan']);
						$jumlah_pemesanan += str_replace(['.', ','], ['', '.'], $mat['pemesanan']);
						$jumlah_vol_pengiriman += str_replace(['.', ','], ['', '.'], $mat['vol_pengiriman']);
						$jumlah_pengiriman += str_replace(['.', ','], ['', '.'], $mat['pengiriman']);
						$jumlah_vol_tagihan += str_replace(['.', ','], ['', '.'], $mat['vol_tagihan']);
						$jumlah_tagihan += str_replace(['.', ','], ['', '.'], $mat['tagihan']);
						$jumlah_vol_pembayaran += str_replace(['.', ','], ['', '.'], $mat['vol_pembayaran']);
						$jumlah_pembayaran += str_replace(['.', ','], ['', '.'], $mat['pembayaran']);
						$jumlah_vol_piutang_pengiriman += str_replace(['.', ','], ['', '.'], $vol_piutang_pengiriman);
						$jumlah_piutang_pengiriman += str_replace(['.', ','], ['', '.'], $piutang_pengiriman);
						$jumlah_vol_sisa_tagihan += str_replace(['.', ','], ['', '.'], $vol_sisa_tagihan);
						$jumlah_sisa_tagihan += str_replace(['.', ','], ['', '.'], $sisa_tagihan);
						$jumlah_vol_akhir += str_replace(['.', ','], ['', '.'], $vol_akhir);
						$jumlah_akhir += str_replace(['.', ','], ['', '.'], $akhir);

            		}	
					?>
					<tr class="table-baris2-bold">
						<td align="left" colspan="2"></td>
						<td align="right">JUMLAH</td>
						<td align="right"><?php echo number_format($jumlah_vol_pemesanan,2,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_pemesanan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_vol_pengiriman,2,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_pengiriman,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_vol_tagihan,2,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_vol_pembayaran,2,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_vol_piutang_pengiriman,2,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_piutang_pengiriman,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_vol_sisa_tagihan,2,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_sisa_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_vol_akhir,2,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_akhir,0,',','.');?></td>
            		</tr>
					<?php
            		}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="17" align="center">NO DATA</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
				<th align="right" width="22%" colspan="3"><b>TOTAL</b></th>
            	<th align="right" width="5%"><?php echo number_format($grand_total_vol_pemesanan,2,',','.');?></th>
				<th align="right" width="7%"><?php echo number_format($grand_total_pemesanan,0,',','.');?></th>
				<th align="right" width="5%"><?php echo number_format($grand_total_vol_pengiriman,2,',','.');?></th>
				<th align="right" width="6%"><?php echo number_format($grand_total_pengiriman,0,',','.');?></th>
				<th align="right" width="5%"><?php echo number_format($grand_total_vol_tagihan,2,',','.');?></th>
				<th align="right" width="6%"><?php echo number_format($grand_total_tagihan,0,',','.');?></th>
				<th align="right" width="5%"><?php echo number_format($grand_total_vol_pembayaran,2,',','.');?></th>
				<th align="right" width="6%"><?php echo number_format($grand_total_pembayaran,0,',','.');?></th>
				<th align="right" width="5%"><?php echo number_format($grand_total_vol_piutang_pengiriman,2,',','.');?></th>
				<th align="right" width="6%"><?php echo number_format($grand_total_piutang_pengiriman,0,',','.');?></th>
				<th align="right" width="5%"><?php echo number_format($grand_total_vol_sisa_tagihan,2,',','.');?></th>
				<th align="right" width="6%"><?php echo number_format($grand_total_sisa_tagihan,0,',','.');?></th>
				<th align="right" width="5%"><?php echo number_format($grand_total_vol_akhir,2,',','.');?></th>
				<th align="right" width="6%"><?php echo number_format($grand_total_akhir,0,',','.');?></th>
            </tr>
			
		</table>
		<br />
		<p>*<i>Periode Tanggal Filter Berdasarkan Tanggal Pemesanan</i></p>
		<br />
		<table width="100%" border="0" cellpadding="50">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center" colspan="2">
								Diperiksa Oleh
							</td>
							<td align="center" >
								Dibuat Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center" height="40px">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								
							</td>
						</tr>
						<tr class="table-active">
							<td align="center" >
								<b><u>Hadi Sucipto</u><br />
								Ka. Plant</b>
							</td>
							<td align="center" >
								<b><br />
								Manager Keuangan</b>
							</td>
							<td align="center" >
								<b><br />
								Manager Logistik</b>
							</td>
							<td align="center" >
								<b><br />
								Adm. Keuangan</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>