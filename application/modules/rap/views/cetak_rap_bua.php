<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>RAP BUA</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 12px;">RAP BUA<br/>
					PROYEK BENDUNGAN TEMEF<br/>
					PT. BIA BUMI JAYENDRA<br/></div>
					<?php
					$rap_bua = $this->db->select('rap.*')
					->from('rap_bua rap')
					->where('rap.id',$id)
					->get()->row_array();

					$tanggal = $rap_bua['tanggal_rap_bua'];
					$date = date('Y-m-d',strtotime($tanggal));
					?>
					<?php
					function tgl_indo($date){
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
						$pecahkan = explode('-', $date);
						
						// variabel pecahkan 0 = tanggal
						// variabel pecahkan 1 = bulan
						// variabel pecahkan 2 = tahun
					
						return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
						
					}
					?>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table width="100%" border="0">
			<tr>
				<th width="20%">Tanggal</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?= tgl_indo(date($date)); ?></th>
			</tr>
			<tr>
				<th>Nomor</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $rap_bua['nomor_rap_bua'];?></th>
			</tr>
			<tr>
				<th>Masa Kontrak</th>
				<th width="10px">:</th>
				<th width="50%" align="left"><?php echo $rap_bua['masa_kontrak'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-judul">
				<th align="center" width="5%">NO.</th>
				<th align="center" width="40%">AKUN</th>
				<th align="center" width="10%">VOLUME</th>
				<th align="center" width="10%">SATUAN</th>
				<th align="right" width="15%">HARGA SATUAN</th>
				<th align="right" width="20%">TOTAL</th>
            </tr>
			<?php
			$rap_bua_detail = $this->db->select('rpd.*, c.coa')
			->from('rap_bua rap')
			->join('rap_bua_detail rpd','rap.id = rpd.rap_bua_id','left')
			->join('pmm_coa c','rpd.coa = c.id','left')
			->where('rpd.rap_bua_id',$id)
			->get()->result_array();

           	$no = 0 ;

            $total = 0;
			
           	foreach ($rap_bua_detail as $row) : ?>  
               <tr class="table-baris1">
                   <td align="center"><?php echo $no+1;?></td>
                   <td align="left"><?= $row["coa"] ?></td>
				   <td align="center"><?= number_format($row['qty'],0,',','.'); ?></td>
	               <td align="center"><?= $row["satuan"]; ?></td>
	               <td align="right"><?= number_format($row['harga_satuan'],0,',','.'); ?></td>
	               <td align="right"><?= number_format($row['jumlah'],0,',','.'); ?></td>
               </tr>

			<?php
			$no++;
			$total += $row['jumlah'];
			endforeach; ?>

            
            <tr class="table-total">
                <th colspan="5" align="right">GRAND TOTAL</th>
				<th align="right"><?= number_format($total,0,',','.'); ?></th>
            </tr>
           	
		</table>
	</body>
</html>