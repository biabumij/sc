<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  
	  <title>Penawaran Penjualan</title>

	  <style type="text/css">
	  	body{
	  		font-family: "Open Sans", Arial, sans-serif;
	  	}
	  	table.minimalistBlack {
		  border: 0px solid #000000;
		  width: 100%;
	
		}
		table.minimalistBlack td, table.minimalistBlack th {
		  border: 2px solid #000000;
		  
		}
		table.minimalistBlack tr td {
		   text-align: center;
		  
		}
		table.minimalistBlack tr th {
		  font-weight: bold;
		  color: #000000;
		  text-align: center;
		}
		table tr.table-active{
            background-color: #e19669;
        }
	  </style>
    
	</head>
	<body>
	    
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
				<th width="20%">Nomor</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?php echo $row['nomor'];?></th>
				<td align="left" width="28%">
					Tulungagung, <?= convertDateDBtoIndo($row["tanggal"]); ?>
				</td>
			</tr>
			<tr>
				<th width="20%">Lampiran</th>
				<th width="2%">:</th>
				<th width="50%" align="left">-</th>
			</tr>
			<tr>
                <th width="20%">Perihal</th>
				<th width="2%">:</th>
				<th width="50%" align="left"><?= $row["perihal"]; ?></th>
            </tr>
		</table>
        <br /><br />
        <table width="98%" border="0" cellpadding="3">
			<tr>
				<td>Kepada Yth,</td>
			</tr>
            <tr>
                <th width="50%"><b><?= $row["client_name"]; ?></b></th>
            </tr>
            <tr>
                <th width="50%"><b><?= $row["client_address"]; ?></b></th>
            </tr>
		</table>
		<br />
        <p><b>A. Mutu Harga dan Satuan</b></p>
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
                <th width="5%">NO</th>
                <th width="35%">JENIS MATERIAL</th>
                <th width="20%">VOLUME</th>
                <th width="10%">SATUAN</th>
                <th width="30%">HARGA SATUAN</th>
                
            </tr>
            <?php
           	$no=1;
           	$subtotal = 0;
            $tax_pph = 0;
            $tax_ppn = 0;
            $tax_0 = false;
            $total = 0;
           	foreach ($data as $dt) {
                // $subtotal = $dt['total'] * $dt['price'];
           		$tax = $this->crud_global->GetField('pmm_taxs',array('id'=>$dt['tax_id']),'tax_name');
               ?>
               <?php
                    $measure = $this->crud_global->GetField('pmm_measures',array('id'=>$dt['measure']),'measure_name');
                ?>
               <tr class="">
                   <td><?php echo $no;?></td>
                   <td><?= $dt["product"] ?></td>
                   <td><?= $dt["qty"] ?></td>
	               <td><?= $measure; ?></td>
				   <td align="center"><?= number_format($dt['price'],0,',','.'); ?></td>
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
               // $total += $subtotal;
           	}
           	?>

		</table>
		<br />
		<br />
		<p><b>B. Persyaratan Harga</b></p>
		<p><?= $row["persyaratan_harga"] ?></p>
		<table width="98%" border="0" cellpadding="0">
			<tr>
                <td>
					Hormat Kami,
				</td>
            </tr>
            <tr>
            	<th><b>PT BIA BUMI JAYENDRA</b></th>
            </tr>
            <tr>
				<th height="35px">
					
				</th>
            </tr>
            <tr>
            	<td>
					<b><u></u><br />
					Ka. Unit Bisnis</b>
				</td>
            </tr>
		</table>
		<br /><br />
		<table width="98%" border="0" cellpadding="3">
			<tr>
                <th width="70%">
				</th>
				<th width="30%" align="right" style="margin-top:40px;">
					<table width="98%" border="1" cellpadding="2">
						<tr class="">
							<td align="right" height="35px">
							</td>
							<td align="right">
							</td>
							<td align="right">
							</td>
						</tr>
					</table>
				</th>
            </tr>
		</table>
	</body>
</html> 