<!DOCTYPE html>
<html>
	<head>
	  <?= include 'lib.php'; ?>
	  <title>ANALISA HARGA SATUAN</title>
	  
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
					<div style="display: block;font-weight: bold;font-size: 12px;">ANALISA HARGA SATUAN</div>
					<div style="display: block;font-weight: bold;font-size: 12px;">DIVISI STONE CRUSHER</div>
				</td>
			</tr>
		</table>
		<br /><br />
		<table class="head" width="100%" border="0" cellpadding="3">
			<tr>
				<th width="20%">JENIS PEKERJAAN</th>
				<th width="2%">:</th>
				<th align="left"><div style="text-transform:uppercase;"><?php echo $row['jobs_type'];?></div></th>
			</tr>
			<tr>
				<th>TANGGAL</th>
				<th>:</th>
				<th align="left"><div style="text-transform:uppercase;"><?= convertDateDBtoIndo($row["tanggal_rap"]); ?></div></th>
			</tr>
		</table>
		<br />
		<br />
		<table class="minimalistBlack" cellpadding="5" width="98%">
			<tr class="table-active">
				<th align="center" rowspan="2" width="5%">&nbsp; <br />NO.</th>
				<th align="center" rowspan="2"  width="20%">&nbsp; <br />KOMPONEN</th>
				<th align="center" rowspan="2"  width="15%">&nbsp; <br />SATUAN</th>
				<th align="center" width="20%" colspan="2">PERKIRAAN KUANTITAS</th>
				<th align="center" width="20%" rowspan="2" colspan="2">&nbsp; <br />HARGA SATUAN <br />(Rp.)</th>
				<th align="center" width="20%" colspan="2">JUMLAH HARGA</th>
            </tr>
			<tr class="table-active">
				<th align="center">(M3)</th>
				<th align="center">(Ton)</th>
				<th align="center">(M3)</th>
				<th align="center">(Ton)</th>
            </tr>
			<?php
			$penyusutan_tangki = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '23'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_tangki = (($penyusutan_tangki['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_sc = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '16'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_sc = (($penyusutan_sc['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_gns = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '19'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_gns = (($penyusutan_gns['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_wl = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '17'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_wl = (($penyusutan_wl['nilai_penyusutan'] / 48) / 25) / 7;

			$penyusutan_timbangan = $this->db->select('r.*, p.nama_produk')
			->from('penyusutan r')
			->join('produk p','r.produk = p.id','left')
			->where("r.status = 'PUBLISH'")
			->where("r.produk = '39'")
			->order_by('p.nama_produk','asc')
			->group_by("p.nama_produk")->limit(1)
			->get()->row_array();
			$penyusutan_timbangan = (($penyusutan_timbangan['nilai_penyusutan'] / 48) / 25) / 7;

			//M3
			$berat_isi_boulder = 1/$row['berat_isi_boulder'];
			$harsat_boulder = $row['price_boulder'] / $berat_isi_boulder;
			$nilai_boulder = $harsat_boulder * $row['vol_boulder'];
			//Ton
			$vol_boulder = $row['vol_boulder'];
			$nilai_boulder_ton = $vol_boulder * $row['price_boulder'];
			
			//M3
			$sc_a = $row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc'];
			$sc_b = $sc_a / $row['berat_isi_batu_pecah'];
			$vol_sc = 1 / $sc_b;
			$nilai_sc = $vol_sc * $penyusutan_sc;
			//Ton
			$vol_sc_ton = 1 / $sc_a;
			$nilai_sc_ton = $vol_sc_ton * $penyusutan_sc;
			
			//M3
			$vol_tangki = $vol_sc;
			$nilai_tangki = $vol_tangki * $penyusutan_tangki;
			//Ton
			$vol_tangki_ton = $vol_sc_ton;
			$nilai_tangki_ton = $vol_tangki_ton * $penyusutan_tangki;
			
			//M3
			$vol_gns = $vol_sc;
			$nilai_gns = $vol_gns * $penyusutan_gns;
			//Ton
			$vol_gns_ton = $vol_sc_ton;
			$nilai_gns_ton = $vol_gns_ton * $penyusutan_gns;

			//M3
			$wl_a = $row['kapasitas_alat_wl'] * $row['efisiensi_alat_wl'];
			$wl_b = (60 / $row['waktu_siklus']) * $wl_a;
			$vol_wl = 1 / $wl_b;
			$nilai_wl = $vol_wl * $penyusutan_wl;
			//Ton
			$vol_wl_ton_rumus = (($wl_a / $row['waktu_siklus']) * 60) * $row['berat_isi_batu_pecah'];
			$vol_wl_ton = 1 / $vol_wl_ton_rumus;
			$nilai_wl_ton = $vol_wl_ton * $penyusutan_wl;

			//M3
			$vol_timbangan =  $vol_sc;
			$nilai_timbangan = $vol_timbangan * $penyusutan_timbangan;
			//Ton
			$vol_timbangan_ton = $vol_sc_ton;
			$nilai_timbangan_ton = $vol_timbangan_ton * $penyusutan_timbangan;

			//Ton
			$vol_bbm_solar_ton = $row['vol_bbm_solar'];
			$nilai_bbm_solar_ton = $vol_bbm_solar_ton * $row['price_bbm_solar'];

			//M3
			$vol_bbm_solar =  $vol_bbm_solar_ton * $row['berat_isi_boulder'];
			$nilai_bbm_solar = $vol_bbm_solar * $row['price_bbm_solar'];

			$rumus_overhead = ($row['overhead'] / 25) / 8;
			$rumus_overhead_1 = ($row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc']) / $row['berat_isi_batu_pecah'] ;
			//$overhead = $rumus_overhead / $rumus_overhead_1;

			$rumus_overhead_ton = $row['kapasitas_alat_sc'] * $row['efisiensi_alat_sc'];
			$overhead_ton = $rumus_overhead / $rumus_overhead_ton;
			$overhead = $overhead_ton;

			$total = $nilai_boulder + $nilai_tangki + $nilai_sc + $nilai_gns + $nilai_wl + $nilai_timbangan + $overhead;
			$total_ton = $nilai_boulder_ton + $nilai_tangki_ton + $nilai_sc_ton + $nilai_gns_ton + $nilai_wl_ton + $nilai_timbangan_ton + $overhead_ton;
			?>
			<tr class="table-active2">
				<td align="center">A.</td>
				<td align="left"><u>BAHAN</u></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center">(M3)</td>
				<td align="center">(Ton)</td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center">1.</td>
				<td align="left">Boulder</td>
				<td align="center"><?php echo $this->crud_global->GetField('pmm_measures',array('id'=>$row['measure_boulder']),'measure_name');?></td>
				<td align="center"></td>
				<td align="center"><?php echo number_format($row['vol_boulder'],4,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_boulder'] / $berat_isi_boulder,0,',','.');?></td>
				<td align="right"><?php echo number_format($row['price_boulder'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_boulder,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_boulder_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active3">
				<td align="right" colspan="7">JUMLAH HARGA BAHAN</td>
				<td align="right"><?php echo number_format($nilai_boulder,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_boulder_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center">B.</td>
				<td align="left"><u>PERALATAN</u></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center" colspan="2"></td>
				<td align="center"></td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center">1.</td>
				<td align="left">Tangki Solar</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_tangki,4,',','.');?></td>
				<td align="center"><?php echo number_format($vol_tangki_ton,4,',','.');?></td>
				<td align="right" colspan="2"><?php echo number_format($penyusutan_tangki,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_tangki,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_tangki_ton,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">2.</td>
				<td align="left">Stone Crusher</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_sc,4,',','.');?></td>
				<td align="center"><?php echo number_format($vol_sc_ton,4,',','.');?></td>
				<td align="right" colspan="2"><?php echo number_format($penyusutan_sc,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_sc,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_sc_ton,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">3.</td>
				<td align="left">Genset</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_gns,4,',','.');?></td>
				<td align="center"><?php echo number_format($vol_gns_ton,4,',','.');?></td>
				<td align="right" colspan="2"><?php echo number_format($penyusutan_gns,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_gns,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_gns_ton,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">4.</td>
				<td align="left">Wheel Loader</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_wl,4,',','.');?></td>
				<td align="center"><?php echo number_format($vol_wl_ton,4,',','.');?></td>
				<td align="right" colspan="2"><?php echo number_format($penyusutan_wl,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_wl,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_wl_ton,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">5.</td>
				<td align="left">Timbangan</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_timbangan,4,',','.');?></td>
				<td align="center"><?php echo number_format($vol_timbangan_ton,4,',','.');?></td>
				<td align="right" colspan="2"><?php echo number_format($penyusutan_timbangan,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_timbangan,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_timbangan_ton,0,',','.');?></td>
			</tr>
			<tr>
				<td align="center">6.</td>
				<td align="left">BBM Solar</td>
				<td align="center">Jam</td>
				<td align="center"><?php echo number_format($vol_bbm_solar,4,',','.');?></td>
				<td align="center"><?php echo number_format($vol_bbm_solar_ton,4,',','.');?></td>
				<td align="right" colspan="2"><?php echo number_format($row['price_bbm_solar'],0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_bbm_solar,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_bbm_solar_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active3">
				<td align="right" colspan="7">JUMLAH HARGA PERALATAN</td>
				<td align="right"><?php echo number_format($nilai_tangki + $nilai_sc + $nilai_gns + $nilai_wl + $nilai_timbangan,0,',','.');?></td>
				<td align="right"><?php echo number_format($nilai_tangki_ton + $nilai_sc_ton + $nilai_gns_ton + $nilai_wl_ton + $nilai_timbangan_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center">C.</td>
				<td align="left"><u>OVERHEAD</u></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="right"></td>
				<td align="right" colspan="2"></td>
				<td align="right"><?php echo number_format($overhead,0,',','.');?></td>
				<td align="right"><?php echo number_format($overhead_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active3">
				<td align="right" colspan="7">JUMLAH HARGA OVERHEAD</td>
				<td align="right"><?php echo number_format($overhead,0,',','.');?></td>
				<td align="right"><?php echo number_format($overhead_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center" colspan="9"></td>
			</tr>
			<tr class="table-active3">
				<td align="right" colspan="7">JUMLAH A+B+C</td>
				<td align="right"><?php echo number_format($total,0,',','.');?></td>
				<td align="right"><?php echo number_format($total_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center" colspan="8"></td>
			</tr>
			<tr class="table-active">
				<td align="right" colspan="7">HARGA SATUAN PEKERJAAN</td>
				<td align="right"><?php echo number_format($total,0,',','.');?></td>
				<td align="right"><?php echo number_format($total_ton,0,',','.');?></td>
			</tr>
			<tr class="table-active2">
				<td align="center" colspan="8"></td>
			</tr>
			<tr class="table-active">
				<td align="right" colspan="7">LABA (<?php echo number_format($row['laba'],0,',','.');?>%)</td>
				<td align="right"><?php echo number_format(($total + ($total * $row['laba']) / 100),0,',','.');?></td>
				<td align="right"><?php echo number_format(($total_ton + ($total_ton * $row['laba']) / 100),0,',','.');?></td>
			</tr>
		</table>
		
		<br />
		
	    <p><b>Keterangan</b> :</p>
		<p><?= $row["memo"] ?></p>

	</body>
</html>