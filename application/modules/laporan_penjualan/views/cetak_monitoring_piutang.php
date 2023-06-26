<!DOCTYPE html>
<html>
	<head>
	  <title>MONITORING PIUTANG</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 11px;">Monitoring Piutang</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">Divisi Stone Crusher</div>
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
				<th width="5%" align="center" rowspan="2" style="vertical-align:middle;">NO.</th>
				<th width="14%" align="center">PELANGGAN</th>
				<th width="7%" align="center" rowspan="2" style="vertical-align:middle;">NO. INV</th>
				<th width="7%" align="center" rowspan="2" style="vertical-align:middle;">TGL. INV</th>
				<th width="17%" align="center" colspan="3">TAGIHAN</th>
				<th width="17%" align="center" colspan="3">PEMBAYARAN</th>
				<th width="17%" align="center" colspan="3">SISA PIUTANG</th>
				<th width="8%" align="center" rowspan="2" style="vertical-align:middle;">STATUS</th>
				<th width="8%" align="center" rowspan="2" style="vertical-align:middle;">UMUR</th>
			</tr>
			<tr class="table-judul">
				<th align="center">KETERANGAN</th>
				<th align="center">DPP</th>
				<th align="center">PPN</th>
				<th align="center">JUMLAH</th>
				<th align="center">DPP</th>
				<th align="center">PPN</th>
				<th align="center">JUMLAH</th>
				<th align="center">DPP</th>
				<th align="center">PPN</th>
				<th align="center">JUMLAH</th>
			</tr>		
            <?php   
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
            			<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="15"><?php echo $row['name'];?></td>
            		</tr>
					<?php
					$jumlah_dpp_tagihan = 0;
					$jumlah_ppn_tagihan = 0;
					$jumlah_jumlah_tagihan = 0;
					$jumlah_dpp_pembayaran = 0;
					$jumlah_ppn_pembayaran = 0;
					$jumlah_jumlah_pembayaran = 0;
					$jumlah_dpp_sisa_piutang = 0;
					$jumlah_ppn_sisa_piutang = 0;
					$jumlah_jumlah_sisa_piutang = 0;
            		foreach ($row['mats'] as $mat) {
            			?>
					<tr class="table-baris1">
						<td align="center"></td>
						<td align="left"><?php echo $mat['subject'];?></td>
						<td align="center"><?php echo $mat['nomor_invoice'];?></td>
            			<td align="center"><?php echo $mat['tanggal_invoice'];?></td>
            			<td align="right"><?php echo $mat['dpp_tagihan'];?></td>
						<td align="right"><?php echo $mat['ppn_tagihan'];?></td>
						<td align="right"><?php echo $mat['jumlah_tagihan'];?></td>
						<td align="right"><?php echo $mat['dpp_pembayaran'];?></td>
						<td align="right"><?php echo $mat['ppn_pembayaran'];?></td>
						<td align="right"><?php echo $mat['jumlah_pembayaran'];?></td>
						<td align="right"><?php echo $mat['dpp_sisa_piutang'];?></td>
						<td align="right"><?php echo $mat['ppn_sisa_piutang'];?></td>
						<td align="right"><?php echo $mat['jumlah_sisa_piutang'];?></td>
						<td align="center"><?php echo $mat['status_pembayaran'];?></td>
						<td align="center"><?php echo $mat['syarat_pembayaran'];?></td>
            		</tr>

					<?php
					$jumlah_dpp_tagihan += str_replace(['.', ','], ['', '.'], $mat['dpp_tagihan']);
					$jumlah_ppn_tagihan += str_replace(['.', ','], ['', '.'], $mat['ppn_tagihan']);
					$jumlah_jumlah_tagihan += str_replace(['.', ','], ['', '.'], $mat['jumlah_tagihan']);
					$jumlah_dpp_pembayaran += str_replace(['.', ','], ['', '.'], $mat['dpp_pembayaran']);
					$jumlah_ppn_pembayaran += str_replace(['.', ','], ['', '.'], $mat['ppn_pembayaran']);
					$jumlah_jumlah_pembayaran += str_replace(['.', ','], ['', '.'], $mat['jumlah_pembayaran']);
					$jumlah_dpp_sisa_piutang += str_replace(['.', ','], ['', '.'], $mat['dpp_sisa_piutang']);
					$jumlah_ppn_sisa_piutang += str_replace(['.', ','], ['', '.'], $mat['ppn_sisa_piutang']);
					$jumlah_jumlah_sisa_piutang += str_replace(['.', ','], ['', '.'], $mat['jumlah_sisa_piutang']);
					}	
					?>
					<tr class="table-baris2-bold">
						<td align="right" colspan="4">JUMLAH</td>
						<td align="right"><?php echo number_format($jumlah_dpp_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_ppn_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_jumlah_tagihan,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_dpp_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_ppn_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_jumlah_pembayaran,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_dpp_sisa_piutang,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_ppn_sisa_piutang,0,',','.');?></td>
						<td align="right"><?php echo number_format($jumlah_jumlah_sisa_piutang,0,',','.');?></td>
						<td align="center"></td>
						<td align="center"></td>
            		</tr>
					<?php
            		}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="15" align="center">Tidak Ada Data</td>
            	</tr>
            	<?php
            }
            ?>
            <tr class="table-total">
				<th align="right" colspan="4">TOTAL</th>
				<th align="right"><?php echo number_format($total_dpp_tagihan,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_ppn_tagihan,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_jumlah_tagihan,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_dpp_pembayaran,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_ppn_pembayaran,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_jumlah_pembayaran,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_dpp_sisa_piutang,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_ppn_sisa_piutang,0,',','.');?></th>
				<th align="right"><?php echo number_format($total_jumlah_sisa_piutang,0,',','.');?></th>
				<td align="center"></td>
				<td align="center"></td>
            </tr>   
		</table>
		<table width="98%" border="0" cellpadding="30">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="3">
						<tr>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center" >
								Diperiksa Oleh
							</td>
							<td align="center">
								Dibuat Oleh
							</td>
						</tr>
						<?php
							$create = $this->db->select('*')
							->from('akumulasi')
							->where("(date_akumulasi <= '$end_date')")
							->order_by('id','desc')->limit(1)
							->get()->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['unit_head']);
							$unit_head = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['keu_1']);
							$keu_1 = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['keu_2']);
							$keu_2 = $this->db->get('tbl_admin a')->row_array();
						?>
						<tr class="">
							<td align="center" height="70px">
								<!--<img src="<?= $unit_head['admin_ttd']?>" width="70px">-->
							</td>
							<td align="center">
								<img src="<?= $keu_1['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $keu_2['admin_ttd']?>" width="70px">
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u></u><br />
								Kepala Unit Bisnis</b>
							</td>
							<td align="center">
							<b><u>Rifka Dian B.</u><br />
								Pj. Keuangan & SDM</b>
							</td>
							<td align="center">
							<b><u>Dian Melinda S.</u><br />
								Staff Keuangan & SDM</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>