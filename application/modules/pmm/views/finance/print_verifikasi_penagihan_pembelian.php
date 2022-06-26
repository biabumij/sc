<!DOCTYPE html>
<html>
	<head>
	  <title>PO Penjualan</title>
	  
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
		<table width="100%" border="0" cellpadding="3">
			<tr>
				<td align="center">
					<div style="display: block;font-weight: bold;font-size: 12px;">BUKTI PENERIMAAN DAN VERIFIKASI DOKUMEN TAGIHAN</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table width="100%" border="1" cellpadding="2">
			<tr>
				<td>
					<table width="100%" border="0" cellpadding="3">
						<tr>
							<td colspan="2" width="24%">
								<div style="display: block;font-weight: bold;"><u>DIISI OLEH VEFIKATOR</u></div>
							</td>
							<td width="2%">:</td>
							<td width="74%"></td>
						</tr>
						<tr>
							<td width="4%">1.</td>
							<td width="20%">Nama Rekanan</td>
							<td width="2%">:</td>
							<td width="74%"><?= $row['supplier_name'];?></td>
						</tr>
						<tr>
							<td>2.</td>
							<td>Nomor Kontrak / PO</td>
							<td>:</td>
							<td ><?= $row['nomor_po'].' - '.$row['tanggal_po'];?></td>
						</tr>
						<tr>
							<td>3.</td>
							<td>Nama Barang / Jasa</td>
							<td>:</td>
							<td ><?= $row['nama_barang_jasa'];?></td>
						</tr>
						<tr>
							<td>4.</td>
							<td>Nilai Kontrak / PO</td>
							<td>:</td>
							<td ><?= $row['nilai_kontrak'];?></td>
						</tr>
						<tr>
							<td>5.</td>
							<td>Nilai Tagihan (Net)</td>
							<td>:</td>
							<td ><?= $row['nilai_tagihan'];?></td>
						</tr>
						<tr>
							<td>6.</td>
							<td >PPN</td>
							<td>:</td>
							<td  ><?= $row['ppn'];?></td>
						</tr>
						<tr>
							<td>7.</td>
							<td >Tanggal Invoice</td>
							<td>:</td>
							<td  ><?= $row['tanggal_invoice'];?></td>
						</tr>
						<tr>
							<td>8.</td>
							<td >Tanggal Diterima Proyek</td>
							<td>:</td>
							<td  ><?= $row['tanggal_diterima_proyek'];?></td>
						</tr>
						<tr>
							<td>9.</td>
							<td >Tanggal Diterima Office</td>
							<td>:</td>
							<td  ><?= $row['tanggal_diterima_office'];?></td>
						</tr>
						<tr>
							<td>10.</td>
							<td >Metode Pembayaran</td>
							<td>:</td>
							<td ><?= $row['metode_pembayaran'];?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table width="100%" border="1" cellpadding="3">
			<tr style="font-weight: bold">
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">A.</td>
							<td width="90%">KELENGKAPAN DATA <br />(LENGKAP DAN BENAR)</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center">ADA / TIDAK (V/X)</th>
				<th width="50%" style="text-align: center">KETERANGAN</th>
			</tr>
			<tr>
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">1.</td>
							<td width="90%">Invoice</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center"><?= $this->pmm_finance->CheckorNo($row['invoice']);?></th>
				<th width="50%" ><?= $row['invoice_keterangan'];?></th>
			</tr>
			<tr>
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">2.</td>
							<td width="90%">Kwitansi</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center"><?= $this->pmm_finance->CheckorNo($row['kwitansi']);?></th>
				<th width="50%" ><?= $row['kwitansi_keterangan'];?></th>
			</tr>
			<tr>
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">3.</td>
							<td width="90%">Faktur Pajak</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center"><?= $this->pmm_finance->CheckorNo($row['faktur']);?></th>
				<th width="50%" ><?= $row['faktur_keterangan'];?></th>
			</tr>
			<tr>
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">4.</td>
							<td width="90%">Berita Acara Pembayaran (BAP)</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center"><?= $this->pmm_finance->CheckorNo($row['bap']);?></th>
				<th width="50%" ><?= $row['bap_keterangan'];?></th>
			</tr>
			<tr>
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">4.</td>
							<td width="90%">Berita Acara Serah Terima (BAST)</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center"><?= $this->pmm_finance->CheckorNo($row['bast']);?></th>
				<th width="50%" ><?= $row['bast_keterangan'];?></th>
			</tr>
			<tr>
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">4.</td>
							<td width="90%">Surat Jalan</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center"><?= $this->pmm_finance->CheckorNo($row['surat_jalan']);?></th>
				<th width="50%" ><?= $row['surat_jalan_keterangan'];?></th>
			</tr>
			<tr>
				<th width="30%">
					<table width="100%" border="0">
						<tr>
							<td width="10%" style="vertical-align: middle;">4.</td>
							<td width="90%">Copy Kontrak (P0)</td>
						</tr>
					</table>
				</th>
				<th width="20%" style="text-align: center"><?= $this->pmm_finance->CheckorNo($row['copy_po']);?></th>
				<th width="50%" ><?= $row['copy_po_keterangan'];?></th>
			</tr>
		</table>
		<br />
		<br />
		<table width="100%" border="1" cellpadding="3">
			<tr >
				<th width="100%">
					<table width="100%" border="0">
						<tr>
							<td width="20%" style="vertical-align: middle;">Catatan</td>
							<td width="80%" style="height:40px"><?= $row['catatan'];?></td>
						</tr>
					</table>
				</th>
			</tr>
		</table>
		<br /><br />
		
		<table width="100%" border="0" cellpadding="0">
			<tr >
				<td width="30%">
					<table width="100%" border="1" cellpadding="2">
						<tr class="table-active3">
							<td align="center" >
								Dibuat Oleh
							</td>
						</tr>
						<tr class="">
							<td align="center" height="75px">
								
							</td>
						</tr>
						<tr class="table-active3">
							<td align="center" >
								<?= $row['verifikator'];?>
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
				<td width="30%">
					<table width="100%" border="1" cellpadding="2">
						<tr class="table-active3">
							<td align="center" >
								Diperiksa
							</td>
						</tr>
						<tr class="">
							<td align="center" >
								<table width="100%" border="0">
									<tr>
										<td height="75px" style="border-right:1px solid	 #000;" ></td>
										<td height="75px"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr class="table-active3">
							<td align="center" >
								
							</td>
						</tr>
					</table>
				</td>
				<td width="5%"></td>
				<td width="30%">
					<table width="100%" border="1" cellpadding="2">
						<tr class="table-active3">
							<td align="center" >
								<table width="100%" border="0">
									<tr>
										<td  style="border-right:1px solid	 #000;" >Disetujui</td>
										<td >Mengetahui</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr class="">
							<td align="center" >
								<table width="100%" border="0">
									<tr>
										<td height="75px" style="border-right:1px solid	 #000;" ></td>
										<td height="75px"></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr class="table-active3">
							<td align="center" >
								
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

			
		

	</body>
</html>