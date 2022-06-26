<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>Purchase Order Penjualan</title>
	  
	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
			font-size: 9px;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
		  text-align: left;
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 0px solid #000000;
		  padding: 5px 4px;
		}
		table.minimalistBlack tr td {
		  /*font-size: 13px;*/
		  text-align:center;
		}
		table.minimalistBlack tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: white;
		  text-align: center;
		  padding: 10px;
		}
		table tr.table-active{
            background-color: #e69500;
			font-weight: bold;
			color: white;
        }
		table th.table-active {
            background-color: #e69500;
			font-weight: bold;
			color: white;
			font-size: 12px;
        }
        table tr.table-active2{
            background-color: #cac8c8;
        }
		table tr.table-active3{
            background-color: #eee;
			font-weight: bold;
        }
		table tr.ttd{
            background-color: #eee;
			border: 1px solid #000000;
        }
		table td.ttd{
			border-bottom: 1px solid #000000;
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
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<th width="60%"></th>
				<th width="40%" style="display: block;font-weight: bold;font-size: 16px;">INVOICE</th>
			</tr>
		    <tr>
				<th width="60%">Kepada Yth :</th>
				<th align="left" width="15%">No. Invoice</th>
				<th align="left" width="2%">:</th>
				<th align="left" width="23%"><?php echo $penagihan['nomor_invoice'];?></th>
			</tr>
			<tr>
				<th width="60%"><b><?php echo $this->crud_global->GetField('penerima',array('id'=>$penagihan['client_id']),'nama');?></b></th>
				<th align="left" width="15%">Tgl. Invoice</th>
				<th align="left" width="2%">:</th>
				<th align="left" width="23%"><?= convertDateDBtoIndo($penagihan["tanggal_invoice"]); ?></th>
			</tr>
			<tr>
				<th width="60%"><?php echo $penagihan['alamat_pelanggan'];?></th>
				<th align="left" width="15%">No. Pemesanan</th>
				<th align="left" width="2%">:</th>
				<th align="left" width="23%"><?php echo $penagihan['nomor_kontrak'];?></th>
			</tr>
			<tr>
				<th width="60%"></th>
				<th align="left" width="15%">Syarat Pembayaran</th>
				<th align="left" width="2%">:</th>
				<th align="left" width="23%"><?php echo $penagihan['syarat_pembayaran'];?> Hari</th>
			</tr>
		</table>
		<br />
		<br />
		<table cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%" align="center">NO</th>
                <th width="30%" align="center">PRODUK</th>
                <th width="20%" align="center">KUANTITAS</th>
                <th width="10%" align="center">SATUAN</th>
                <th width="15%" align="center">HARGA SATUAN</th>
                <th width="20%" align="center">JUMLAH</th>
            </tr>
            <?php
           	$no=1;
           	$subtotal = 0;
            $tax_pph = 0;
            $tax_ppn = 0;
			$tax_ppn11 = 0;
            $tax_0 = false;
            $total = 0;
           	foreach ($cekHarga as $dt) {
               ?>
			   <?php
                    $nama_produk = $this->crud_global->GetField('produk',array('id'=>$dt['product_id']),'nama_produk');
                ?>
               <tr class="table-active3">
                   <td align="center"><?php echo $no;?></td>
                   <td align="center"><?= $nama_produk; ?></td>
	               <td align="center"><?= $dt["qty"]; ?></td>
	               <td align="center"><?= $dt["measure"]; ?></td>
	               <td align="right"><?= number_format($dt['price'],0,',','.'); ?></td>
	               <td align="right"><?= number_format($dt['total'],0,',','.'); ?></td>
               </tr>
               <?php
               $no++;
               $subtotal += $dt['total'];
                if($dt['tax_id'] == 4){
                    $tax_0 = true;
                }
                if($dt['tax_id'] == 3){
                    $tax_ppn += $dt['tax'];
                }
                if($dt['tax_id'] == 5){
                    $tax_pph += $dt['tax'];
                }
				if($dt['tax_id'] == 6){
                    $tax_ppn11 += $dt['tax'];
                }
               // $total += $subtotal;
           	}
           	?>
            <tr>
               <th colspan="5" style="text-align:right">Jumlah</th>
               <th align="right"><?= number_format($subtotal,0,',','.'); ?></th>
			</tr>
            <?php
            if($tax_ppn > 0){
                ?>
                <tr>
                    <th colspan="5" align="right">Pajak (PPN 10%)</th>
                    <th  align="right"><?= number_format($tax_ppn,0,',','.'); ?></th>
                </tr>
                <?php
            }
            ?>
            <?php
            if($tax_0){
                ?>
                <tr>
                    <th colspan="5" align="right">Pajak (PPN 0%)</th>
                    <th  align="right"><?= number_format(0,0,',','.'); ?></th>
                </tr>
                <?php
            }
            ?>
            <?php
            if($tax_pph > 0){
                ?>
                <tr>
                    <th colspan="5" align="right">Pajak (PPh 23)</th>
                    <th align="right"><?= number_format($tax_pph,0,',','.'); ?></th>
                </tr>
                <?php
            }
			?>
			<?php
            if($tax_ppn11 > 0){
                ?>
                <tr>
                    <th colspan="5" align="right">Pajak (PPN 11%)</th>
                    <th  align="right"><?= number_format($tax_ppn11,0,',','.'); ?></th>
                </tr>
                <?php
            }

            $total = $subtotal + $tax_ppn - $tax_pph + $tax_ppn11;
            ?>
			<tr>
				<th colspan="4"></th>
                <th class="table-active">TOTAL</th>
                <th align="right" class="table-active"><?= number_format($total,0,',','.'); ?></th>
            </tr>
            
            <tr>
				<th colspan="6" align="left">
					<div style="text-transform: capitalize;"><i>TERBILANG : <?= $this->filter->terbilang($total);?></i></div>
				</th>
            </tr>
			<tr>
				<th colspan="6" align="left">
				<b>INFO PEMBAYARAN </b><br />
				Silahkan transfer ke rekening: <br />
				166-000-3184-587 <br />
				a/n PT. Bia Bumi Jayendra <br />
				Bank Mandiri
				</th>
            </tr>
           	
		</table>
		<br />
		<br />
		<br />
		<br />
		<br />
		<table width="98%" border="0" cellpadding="0">
			<tr >
				<td width="5%"></td>
				<td width="90%">
					<table width="100%" border="0" cellpadding="2">
						<tr>
							<td align="center" >
								Tanda Terima,
							</td>
							<td align="center" >
							
							</td>
							<td align="center" >
							    Hormat Kami,
							</td>
						</tr>
						<tr class="">
							<td align="center" height="75px">
								
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								<!-- <img src="/uploads/ttd_annisa.jpeg" width="100px"> -->
							</td>
						</tr>
						<?php
                		    $logistik = $this->pmm_model->GetNameGroup(6);
                		?>
						<tr>
							<td align="center" class="ttd">
							    	
							</td>
							<td align="center">
							    	
							</td>
							<td align="center"  class="ttd">
								<b><?= $this->crud_global->GetField('tbl_admin',array('admin_id'=>$penagihan['created_by']),'admin_name'); ?></b>
							</td>
						</tr>
						<tr>
						     <?php
                                $this->db->select('g.admin_group_name');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$penagihan['created_by']);
                                $created_group = $this->db->get('tbl_admin a')->row_array();
                                ?>
							<td align="center">
							    <b><?php echo $this->crud_global->GetField('penerima',array('id'=>$penagihan['client_id']),'nama');?></b>
							</td>
							<td align="center">
								
							</td>
							<td align="center">
								<b><?= $created_group['admin_group_name']?></b>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
			</tr>
		</table>

	</body>
</html>