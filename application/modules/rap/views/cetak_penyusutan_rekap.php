<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>PENYUSUTAN</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 12px;">PENYUSTAN</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">DIVISI STONE CRUSHER</div>
					<div style="display: block;font-weight: bold;font-size: 12px; text-transform: uppercase"><?= $filter_date; ?></div>
				</td>
			</tr>
		</table>
		<?php
		$data = array();
		
		$arr_date = $this->input->get('filter_date');
		$arr_filter_date = explode(' - ', $arr_date);
		$date3 = '';
		$date1 = '';
		$date2 = '';

		if(count($arr_filter_date) == 2){
			$date3 	= date('2021-01-01',strtotime($date3));
			$date1 	= date('Y-m-d',strtotime($arr_filter_date[0]));
			$date2 	= date('Y-m-d',strtotime($arr_filter_date[1]));
			$filter_date = date('d/m/Y',strtotime($arr_filter_date[0])).' - '.date('d/m/Y',strtotime($arr_filter_date[1]));
			$filter_date_2 = date('Y-m-d',strtotime($date3)).' - '.date('Y-m-d',strtotime($arr_filter_date[1]));
		}
		?>
		<br /><br /><br /><br /><br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<?php
			$penyusutan = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.tanggal_penyusutan between '$date1' and '$date2'")
			->where("r.status = 'PUBLISH'")
			->order_by('r.tanggal_penyusutan','asc')
			->group_by("p.nama_produk")
			->get()->result_array();

			$total_harga_perolehan = 0;
			foreach ($penyusutan as $x){
				$total_harga_perolehan += $x['nilai_penyusutan'];
			}
			?>
			<tr class="table-active">
				<th align="center" width="5%">NO.</th>
				<th align="center" width="13%">PRODUK</th>
				<th align="center" width="10%">MASA MANFAAT</th>
				<th align="center" width="12%">HARGA PEROLEHAN (Rp.)</th>
				<th align="center" width="12%">TAHUN PEROLEHAN</th>
				<th align="center" width="12%">NILAI PENYUSUTAN /TAHUN (Rp.)</th>
				<th align="center" width="12%">NILAI PENYUSUTAN /BULAN (Rp.)</th>
				<th align="center" width="12%">NILAI PENYUSUTAN /HARI (Rp.)</th>
				<th align="center" width="12%">NILAI PENYUSUTAN /JAM (Rp.)</th>
            </tr>
			<?php $no=1; foreach ($penyusutan as $x):?>
			<tr class="table-active2">
				<td align="center"><?php echo $no++;?></td>
				<td align="left"><?= $x['nama_produk'];?></td>
				<td align="center">4 Tahun</td>
				<td align="right"><?php echo number_format($x['nilai_penyusutan'],0,',','.');?></td>
				<td align="center"><?= date('d/m/Y',strtotime($x['tanggal_penyusutan']));;?></td>
				<td align="right"><?php echo number_format($x['nilai_penyusutan'] / 4,0,',','.');?></td>
				<td align="right"><?php echo number_format($x['nilai_penyusutan'] / 48,0,',','.');?></td>
				<td align="right"><?php echo number_format((($x['nilai_penyusutan'] / 48) / 25),0,',','.');?></td>
				<td align="right"><?php echo number_format((($x['nilai_penyusutan'] / 48) / 25) / 7,0,',','.');?></td>
			</tr>
			<?php endforeach; ?>
			<tr class="table-active2">
				<td align="center" colspan="3">TOTAL</td>
				<td align="right"><?php echo number_format($total_harga_perolehan,0,',','.');?></td>
				<td align="right"></td>
				<td align="right"><?php echo number_format($total_harga_perolehan / 4,0,',','.');?></td>
				<td align="right"><?php echo number_format($total_harga_perolehan / 48,0,',','.');?></td>
				<td align="right"><?php echo number_format(($total_harga_perolehan / 48) / 5,0,',','.');?></td>
				<td align="right"><?php echo number_format((($total_harga_perolehan / 48) / 25) / 7,0,',','.');?></td>
			</tr>
		</table>
		
		<br />
		
	    <p><b>Keterangan</b> :</p>
		<p><?= $row["memo"] ?></p>

	</body>
</html>