<!DOCTYPE html>
<html>
	<head>
	  <title>SISA BAHAN</title>

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
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 11px;">SISA BAHAN</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">DIVISI CUTTING STONE</div>
					<div style="display: block;font-weight: bold;font-size: 11px;">PT. BIA BUMI JAYENDRA</div>
					<div style="display: block;font-weight: bold;font-size: 11px; text-transform: uppercase;">PERIODE <?php echo str_replace($search, $replace, $subject);?></div>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="2" width="98%">
			<tr class="table-judul">
                <th width="5%" align="center">NO.</th>
                <th width="15%" align="center">TANGGAL</th>
                <th width="30%" align="center">URAIAN</th>
                <th width="15%" align="center">VOLUME</th>
                <th width="15%" align="center">SATUAN</th>
                <th width="20%" align="center">CATATAN</th>
            </tr>
            <?php
            
            $total = 0;
            if(!empty($data)){
            	$no=1;
            	foreach ($data as $key => $row) {	

            		$measure = $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure']),'measure_name');
            		$arr_filter_mats = array();
					$query_mats = $this->db->select('id')->get_where('produk',array('status'=>'PUBLISH','id'=>$row['material_id']))->result_array();
					//file_put_contents("D:\\query_mats.txt", $this->db->last_query());
					foreach ($query_mats as $m => $r) {
						$arr_filter_mats[] = $r['id'];
					}
					// print_r($arr_filter_mats);
            		$date_before = $this->db->select('date')->order_by('date','desc')->get_where('pmm_remaining_materials_cat',array('date <'=>$row['date'],'material_id'=>$row['material_id']))->row_array();


            		$first_date = $row['date'];
            		$last_date = $row['date'];

            		$this->db->select('SUM(pm.volume) as volume');		
					$this->db->where('pm.date_receipt >=',$first_date);
					$this->db->where('pm.date_receipt <=',$last_date);
					$this->db->where_in('pm.material_id',$arr_filter_mats);
					$all_mats = $this->db->get('pmm_receipt_material pm')->row_array();
					//file_put_contents("D:\\all_mats.txt", $this->db->last_query());

					$this->db->select('ppo.supplier_id, SUM(pm.volume) as volume, ps.nama as supplier, pm.harga_satuan as price,pm.convert_value');
					$this->db->join('pmm_purchase_order ppo','pm.purchase_order_id = ppo.id');
					$this->db->join('penerima ps','ppo.supplier_id = ps.id','left');
					$this->db->where('pm.date_receipt >=',$first_date);
					$this->db->where('pm.date_receipt <=',$last_date);
					$this->db->where_in('pm.material_id',$arr_filter_mats);
					$this->db->group_by('ppo.supplier_id');
					$query = $this->db->get('pmm_receipt_material pm');
					//file_put_contents("D:\\row_2.txt", $this->db->last_query());
            		?>
            		<tr class="table-baris2">
            			<td align="center"><?php echo $key + 1;?></td>
            			<td align="center"><?php echo date('d F Y',strtotime($row['date']));?></td>
            			<td><?php echo $this->crud_global->GetField('produk',array('id'=>$row['material_id']),'nama_produk');?></td>
            			<td align="right"><?php echo  number_format($row['volume'],2,',','.');?></td>
						<td align="center"><?php echo $measure;?></td>
            			<td align="center"><?php echo $row['notes'];?></td>
            		</tr>
            		<?php
					$no++;
            		$total += $row['total'];	
            	}
            }else {
            	?>
            	<tr>
            		<td width="98%" colspan="8" align="center">No Data</td>
            	</tr>
            	<?php
            }
            ?>
		</table>
		<br />
		<br />
		<table width="98%" border="0" cellpadding="30">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
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
						<tr>
							<td align="center" height="40px">
							
							</td>
							<td align="center">
							
							</td>
							<td align="center">
							
							</td>
						</tr>
						<tr>
							<td align="center">
								<b><u>Hadi Sucipto</u><br />
								Ka. Unit Bisnis</b>
							</td>
							<td align="center">
								<b><br />
								Keuangan</b>
							</td>
							<td align="center" >
								<b><br />
								Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>

		
	</body>
</html>