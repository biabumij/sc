<!DOCTYPE html>
<html>
	<head>
	  <title><?php echo $row['no_po'];?></title>
	  <?= include 'lib.php'; ?>
	  
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
		table tr.table-active{
            background-color: #b5b5b5;
        }
        table tr.table-active2{
            background-color: #cac8c8;
        }
        table tr.table-active3{
            background-color: #eee;
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
		
		<table width="100%" border="0" cellpadding="2">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 16px;">PENERIMAAN PEMBELIAN</div>
				</td>
			</tr>
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 14px;"><?php echo $row['supplier_name'];?></div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="100%" border="0" cellpadding="3">
			<tr>
				<th width="20%">Tanggal Pesanan Pembelian</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?= convertDateDBtoIndo($row["date_po"]); ?></th>
			</tr>
			<tr>
				<th>Subjek</th>
				<th width="10px">:</th>
				<th align="left"><?php echo $row['subject'];?></th>
			</tr>
			<tr>
				<th>Nomor Pesanan Pembelian</th>
				<th width="10px">:</th>
				<th align="left"><?php echo $row['no_po'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">No</th>
                <th width="25%">Produk</th>
                <th width="10%">Satuan</th>
                <th width="25%">Periode Penerimaan</th>
                <th width="10%">Volume</th>
                <th width="25%">Nilai</th>
            </tr>
            <?php
           $no=1;
           $total = 0;
           $total_vol = 0;
           foreach ($details as $dt) {

	           	$start_date = $this->db->select('date_receipt')->order_by('date_receipt','asc')->limit(1)->get_where('pmm_receipt_material',array('purchase_order_id'=>$id,'material_id'=>$dt['material_id']))->row_array();
				$end_date = $this->db->select('date_receipt')->order_by('date_receipt','desc')->limit(1)->get_where('pmm_receipt_material',array('purchase_order_id'=>$id,'material_id'=>$dt['material_id']))->row_array();
				$periode_mats = date('d/m/Y',strtotime($start_date['date_receipt'])).' - '.date('d/m/Y',strtotime($end_date['date_receipt']));
               ?>  
               <tr>
                   <td><?php echo $no;?></td>
                   <td align="left"><?php echo $dt['material_name'];?></td>
                   <td><?php echo $dt['measure'];?></td>
                   <td><?php echo $periode_mats;?></td>
                   <td><?php echo number_format($dt['volume'],2,',','.');?></td>
                   
                   <td align="right"><?php echo number_format($dt['total'],0,',','.');?></td>
               </tr>
               <?php
               $no++;
               $total += $dt['total'];
               $total_vol += $dt['volume'];
           }
           ?>
           <tr>
               <th colspan="4" style="text-align:right">TOTAL</th>
               <td><?php echo number_format($total_vol,2,',','.');?></td>
               <td align="right"><?php echo number_format($total,0,',','.');?></td>
               <input type="hidden" id="total" value="<?php echo $total + $ppn;?>">
           </tr>
		</table>
		<?php
        $ka_plant = $this->pmm_model->GetNameGroup(15);
        ?>
		<table width="98%" border="0" cellpadding="50">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center">
								Pengirim
							</td>
							<td align="center">
								
							</td>
							<td align="center" >
								Penerima
							</td>	
						</tr>
						<tr>
							<td align="center">
								<b><?php echo $row['supplier_name'];?></b>
							</td>
							<td align="center">
								
							</td>
							<td align="center" >
								<b>PT. Bia Bumi Jayendra</b>
							</td>	
						</tr>
						<tr class="">
							<td align="center" height="55px">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								
							</td>
						</tr>
						<tr>
							<td align="center">
								<u><b><?php echo $row['pic'];?></b></u><br />
								<b>Logistik</b>
							</td>
							<td align="center">
								
							</td>
							<td align="center" >
								<u><b>Agustinus Pakaenoni</b></u><br />
								<b>Logistik</b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>
	</body>
</html>