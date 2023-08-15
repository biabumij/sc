<!DOCTYPE html>
<html>
	<head>

	  <title>LAPORAN EVALUASI KAPASITAS PRODUKSI</title>
	  
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
		<table width="98%">
			<tr>
				<td width="100%" align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">LAPORAN EVALUASI KAPASITAS PRODUKSI</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE : <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="3" width="98%" border="0">
			<tr class="table-judul">
				<th align="center" width="5%">No</th>
				<th align="center" width="15%">Tanggal</th>
				<th align="center" width="20%">Nomor Produksi</th>
				<th align="center" width="20%">Durasi Produksi (Jam)</th>
				<th align="center" width="20%">Pemakaian Bahan (Ton)</th>
				<th align="center" width="20%">Kapasitas Produksi (Ton/Jam)</th>
            </tr>
			<?php 
				$subtotal_duration = 0;
				$subtotal_used = 0;
				$subtotal_capacity = 0;
			?>
            <?php
			$i=0;
            if(!empty($data)){
            	foreach ($data as $key => $row) :
				$i++;
				$bg=($i%2==0?'#F0F0F0':'#E8E8E8') ?> 
					
					<?php 
					$subtotal_duration += $row['jumlah_duration'];
					$subtotal_used += $row['jumlah_used'];
					$hasil_capacity = ($row['jumlah_duration']!=0)?($row['jumlah_used'] / $row['jumlah_duration'])  * 1:0;	
					$subtotal_avg_capacity = ($subtotal_duration!=0)?($subtotal_used / $subtotal_duration)  * 1:0;	
					?>
            		<tr class="table-baris1" style="background-color:<?php echo $bg; ?>;">
            			<td align="center"><?php echo $key + 1;?></td>
						<td align="center"><?php echo date('d/m/Y',strtotime($row['date_prod']));?></td>
						<td align="center"><?php echo $row['no_prod'];?></td>
						<td align="center"><?php echo $row['jumlah_duration'];?></td>
						<td align="center"><?php echo $row['jumlah_used'];?></td>
						<td align="center"><?= number_format($hasil_capacity,2,',','.');?></td>
            		</tr>
            		<?php
            	endforeach; 
            }
            ?>
            <tr class="table-total">
            	<th width="40%" align="center"><b>Rata - Rata Produksi</b></th>
				<th width="20%" align="center"><?= number_format($subtotal_duration,2,',','.');?></th>
            	<th width="20%" align="center"><?= number_format($subtotal_used,2,',','.');?></th>
				<th width="20%" align="center"><?= number_format($subtotal_avg_capacity,2,',','.');?></th>
            </tr>	
		</table>
		<table width="98%" border="0" cellpadding="100">
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
							->from('akumulasi_bahan_baku')
							->where("(date_akumulasi = '$end_date')")
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
								<img src="<?= $unit_head['admin_ttd']?>" width="70px">
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
								<b><u><?= $unit_head['admin_name']?></u><br />
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