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
		  padding: 8px;
		}
		table.head tr th {
		  /*font-size: 14px;*/
		  font-weight: bold;
		  color: #000000;
		  text-align: left;
		  padding: 8px;
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
				</td>
			</tr>
		</table>
		<br /><br />
		<table class="head" width="100%" border="0" cellpadding="3">
			<tr>
				<th width="20%">TANGGAL PENYUSUTAN</th>
				<th width="2%">:</th>
				<th align="left"><div style="text-transform:uppercase;"><?= convertDateDBtoIndo($row["tanggal_penyusutan"]); ?></div></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th align="center" width="5%">NO.</th>
				<th align="center" width="50%">PRODUK</th>
				<th align="center" width="45%">NILAI PENYUSUTAN</th>
            </tr>
			<tr class="table-active2">
				<td align="center">1.</td>
				<td align="left"><?php echo $this->crud_global->GetField('produk',array('id'=>$row['produk']),'nama_produk');?></td>
				<td align="center"><?php echo number_format($row['nilai_penyusutan'],2,',','.');?></td>
			</tr>
		</table>
		
		<br />
		
	    <p><b>Keterangan</b> :</p>
		<p><?= $row["memo"] ?></p>

	</body>
</html>