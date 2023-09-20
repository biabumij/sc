<!DOCTYPE html>
<html>
	<head>
	  <title>LAPORAN PENGIRIMAN PENJUALAN</title>
	  
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
			font-weight: bold;
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
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN PENGIRIMAN PENJUALAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI STONE CRUSHER</div>
				    <div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="2" width="98%">
			<tr class="table-judul">
                <th align="center" width="5%">NO.</th>
                <th align="center" width="35%">URAIAN</th>
				<th align="center" width="10%">SATUAN</th>
                <th align="center" width="15%">VOLUME</th>
				<th align="center" width="15%">HARGA SATUAN</th>
                <th align="center" width="20%">NILAI</th>
            </tr>
            <?php
			$vol_jasa_angkut = 0;
			$jasa_angkut = 0;
			$total_vol = 0;
			$total = 0;
			$total_vol_jasa_angkut = 0;
			$total_jasa_angkut = 0;
            if(!empty($data)){
            	foreach ($data as $key => $row) {
            		?>
            		<tr class="table-baris1-bold">
            			<td align="center"><?php echo $key + 1;?></td>
            			<td align="left" colspan="2"><?php echo $row['name'];?></td>
            			<td align="right"><?php echo $row['real'];?></td>
						<td align="right"></td>
            			<td align="right">
            				<table cellpadding="0" width="100%" border="0">
		    					<tr>
		    						<td width="20%" align="left">Rp.</td>
		    						<td width="80%" align="right"><?php echo $row['total_price'];?></td>
		    					</tr>
		    				</table>
            			</td>
						<?php
						$total_vol += str_replace(['.', ','], ['', '.'], $row['real']);
						$total += str_replace(['.', ','], ['', '.'], $row['total_price']);
						?>
            		</tr>
            		<?php
            		foreach ($row['mats'] as $mat) {
            			?>
            			<tr class="table-baris1">
	            			<td align="center"></td>
	            			<td align="left"><?php echo $mat['nama_produk'];?></td>
	            			<td align="center"><?php echo $mat['measure'];?></td>
	            			<td align="right"><?php echo $mat['real'];?></td>
							<td align="right">
	            				<table cellpadding="0" width="100%" border="0">
			    					<tr>
			    						<td width="20%" align="left">Rp.</td>
			    						<td width="80%" align="right"><?php echo $mat['price'];?></td>
			    					</tr>
			    				</table>
	            			</td>
	            			<td align="right">
	            				<table cellpadding="0" width="100%" border="0">
			    					<tr>
			    						<td width="20%" align="left">Rp.</td>
			    						<td width="80%" align="right"><?php echo $mat['total_price'];?></td>
			    					</tr>
			    				</table>
	            			</td>
	            		</tr>
            			<?php
						$total_vol_jasa_angkut += str_replace(['.', ','], ['', '.'], $mat['real']);
						$vol_jasa_angkut = $total_vol_jasa_angkut - $total_vol;
						$total_jasa_angkut += str_replace(['.', ','], ['', '.'], $mat['total_price']);
						$jasa_angkut = $total_jasa_angkut - $total;
            		}		
            	}
            }else {
            	?>
            	<tr>
            		<td width="100%" colspan="7" align="center">(Tidak Ada Data)</td>
            	</tr>
            	<?php
            }
            ?>	
            <tr class="table-baris2-bold">
            	<th align="right" width="50%" colspan="3">TOTAL MATERIAL</th>
				<th align="right" width="15%"><?php echo number_format($total_vol,2,',','.');?></th>
				<th align="right" width="15%"></th>
            	<th align="right" width="20%">
            		<table cellpadding="0" width="100%" border="0">
    					<tr>
    						<td width="20%" align="left">Rp.</td>
    						<td width="80%" align="right"><?php echo number_format($total,0,',','.');?></td>
    					</tr>
    				</table>
            	</th>
            </tr>
			<tr class="table-baris2-bold">
            	<th align="right" width="50%" colspan="3">JASA ANGKUT</th>
				<th align="right" width="15%"><?php echo number_format($vol_jasa_angkut,2,',','.');?></th>
				<th align="right" width="15%"></th>
            	<th align="right" width="20%">
            		<table cellpadding="0" width="100%" border="0">
    					<tr>
    						<td width="20%" align="left">Rp.</td>
    						<td width="80%" align="right"><?php echo number_format($jasa_angkut,0,',','.');?></td>
    					</tr>
    				</table>
            	</th>
            </tr>
			<tr class="table-total">
            	<th align="right" width="80%" colspan="5">TOTAL MATERIAL + JASA ANGKUT</th>
            	<th align="right" width="20%">
            		<table cellpadding="0" width="100%" border="0">
    					<tr>
    						<td width="20%" align="left">Rp.</td>
    						<td width="80%" align="right"><?php echo number_format($total_jasa_angkut,0,',','.');?></td>
    					</tr>
    				</table>
            	</th>
            </tr> 
             
		</table>
		<br />
		<br />
		<table width="98%">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="3">
						<tr>
							<td align="center" >
								Disetujui Oleh
							</td>
							<td align="center">
								Diperiksa Oleh
							</td>
							<td align="center">
								Dibuat Oleh
							</td>
						</tr>
						<?php
							$create = $this->db->select('id, unit_head, logistik, admin')
							->from('akumulasi_bahan_baku')
							->where("date_akumulasi between '$start_date' and '$end_date'")
							->order_by('id','desc')->limit(1)
							->get()->row_array();

							$this->db->select('a.admin_name, g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['unit_head']);
							$unit_head = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('a.admin_name, g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['logistik']);
							$logistik = $this->db->get('tbl_admin a')->row_array();

							$this->db->select('a.admin_name, g.admin_group_name, a.admin_ttd');
							$this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
							$this->db->where('a.admin_id',$create['admin']);
							$admin = $this->db->get('tbl_admin a')->row_array();
						?>
						<tr class="">
							<td align="center" height="70px">
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $logistik['admin_ttd']?>" width="70px">
							</td>
							<td align="center">
								<img src="<?= $admin['admin_ttd']?>" width="70px">
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u><?= $unit_head['admin_name']?></u><br />
								Kepala Unit Bisnis</b>
							</td>
							<td align="center">
								<b><u><?= $logistik['admin_name']?></u><br />
								Ka. Logistik</b>
							</td>
							<td align="center">
								<b><u><?= $admin['admin_name']?></u><br />
								Admin Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>