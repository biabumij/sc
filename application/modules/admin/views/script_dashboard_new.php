<!-- Akumulasi --->

<?php

	$date_now = date('Y-m-d');
    $date_januari23_awal = date('2023-01-01');
    $date_januari23_akhir = date('2023-01-31');
    $date_februari23_awal = date('2023-02-01');
    $date_februari23_akhir = date('2023-02-28');
    $date_maret23_awal = date('2023-03-01');
    $date_maret23_akhir = date('2023-03-31');
    $date_april23_awal = date('2023-04-01');
    $date_april23_akhir = date('2023-04-30');
    $date_mei23_awal = date('2023-05-01');
    $date_mei23_akhir = date('2023-05-31');
    $date_juni23_awal = date('2023-06-01');
    $date_juni23_akhir = date('2023-06-30');
    $date_juli23_awal = date('2023-07-01');
    $date_juli23_akhir = date('2023-07-31');
    $date_agustus23_awal = date('2023-08-01');
    $date_agustus23_akhir = date('2023-08-31');
    $date_september23_awal = date('2023-09-01');
    $date_september23_akhir = date('2023-09-30');
    $date_oktober23_awal = date('2023-10-01');
    $date_oktober23_akhir = date('2023-10-31');
    $date_november23_awal = date('2023-11-01');
    $date_november23_akhir = date('2023-11-30');
    $date_desember23_awal = date('2023-12-01');
    $date_desember23_akhir = date('2023-12-31');
    $date_akumulasi_awal = date('2021-01-01');
    $date_akumulasi_akhir = date('2023-12-31');

	//JAN
	//RAP
	$row = $this->db->select('*')
	->from('rap')
	->order_by('id','desc')->limit(1)
	->get()->row_array();

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
	//END RAP

	$penjualan_limbah_jan = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_januari23_awal' and '$date_januari23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_jan = 0;
	foreach ($penjualan_limbah_jan as $y){
		$total_penjualan_limbah_jan += $y['price'];
	}

	$penjualan_jan = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_januari23_awal' and '$date_januari23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_jan = 0;
	$total_volume_jan = 0;
	$measure_jan = 0;

	foreach ($penjualan_jan as $x){
		$total_penjualan_jan += $x['price'];
		$total_volume_jan += $x['volume'];
	}

	$total_penjualan_all_jan = 0;
	$total_penjualan_all_jan = $total_penjualan_jan + $total_penjualan_limbah_jan;

	$biaya_umum_administratif_biaya_jan = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_januari23_awal' and '$date_januari23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_jan = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_januari23_awal' and '$date_januari23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_jan = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_januari23_awal' and '$date_januari23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_jan = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_januari23_awal' and '$date_januari23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jan = $biaya_umum_administratif_biaya_jan['total'] + $biaya_umum_administratif_jurnal_jan['total'];
	$biaya_lainnya_jan = $biaya_lainnya_biaya_jan['total'] + $biaya_lainnya_jurnal_jan['total'];
	$total_harga_pokok_pendapatan_jan = $total_volume_jan * $total;
	$laba_kotor_jan = $total_penjualan_all_jan - $total_harga_pokok_pendapatan_jan;
	$total_biaya_jan = $biaya_umum_administratif_jan + $biaya_lainnya_jan;
	$laba_usaha_jan = $laba_kotor_jan - $total_biaya_jan;
	$total_jan = $laba_usaha_jan;
	$persentase_jan = ($total_jan!=0)?($total_penjualan_all_jan / $total_jan) * 100:0;
	$persentase_jan_fix = round($persentase_jan,2);

	//FEB
	$penjualan_limbah_feb = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_februari23_awal' and '$date_februari23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_feb = 0;
	foreach ($penjualan_limbah_feb as $y){
		$total_penjualan_limbah_feb += $y['price'];
	}

	$penjualan_feb = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_februari23_awal' and '$date_februari23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_feb = 0;
	$total_volume_feb = 0;
	$measure_feb = 0;

	foreach ($penjualan_feb as $x){
		$total_penjualan_feb += $x['price'];
		$total_volume_feb += $x['volume'];
	}

	$total_penjualan_all_feb = 0;
	$total_penjualan_all_feb = $total_penjualan_feb + $total_penjualan_limbah_feb;

	$biaya_umum_administratif_biaya_feb = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_februari23_awal' and '$date_februari23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_feb = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_februari23_awal' and '$date_februari23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_feb = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_februari23_awal' and '$date_februari23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_feb = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_februari23_awal' and '$date_februari23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_feb = $biaya_umum_administratif_biaya_feb['total'] + $biaya_umum_administratif_jurnal_feb['total'];
	$biaya_lainnya_feb = $biaya_lainnya_biaya_feb['total'] + $biaya_lainnya_jurnal_feb['total'];
	$total_harga_pokok_pendapatan_feb = $total_volume_feb * $total;
	$laba_kotor_feb = $total_penjualan_all_feb - $total_harga_pokok_pendapatan_feb;
	$total_biaya_feb = $biaya_umum_administratif_feb + $biaya_lainnya_feb;
	$laba_usaha_feb = $laba_kotor_feb - $total_biaya_feb;
	$total_feb = $laba_usaha_feb;
	$persentase_feb = ($total_feb!=0)?($total_penjualan_all_feb / $total_feb) * 100:0;
	$persentase_feb_fix = round($persentase_feb,2);

	//MAR
	$penjualan_limbah_mar = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_maret23_awal' and '$date_maret23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_mar = 0;
	foreach ($penjualan_limbah_mar as $y){
		$total_penjualan_limbah_mar += $y['price'];
	}

	$penjualan_mar = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_maret23_awal' and '$date_maret23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_mar = 0;
	$total_volume_mar = 0;
	$measure_mar = 0;

	foreach ($penjualan_mar as $x){
		$total_penjualan_mar += $x['price'];
		$total_volume_mar += $x['volume'];
	}

	$total_penjualan_all_mar = 0;
	$total_penjualan_all_mar = $total_penjualan_mar + $total_penjualan_limbah_mar;

	$biaya_umum_administratif_biaya_mar = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_maret23_awal' and '$date_maret23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_mar = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_maret23_awal' and '$date_maret23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_mar = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_maret23_awal' and '$date_maret23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_mar = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_maret23_awal' and '$date_maret23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_mar = $biaya_umum_administratif_biaya_mar['total'] + $biaya_umum_administratif_jurnal_mar['total'];
	$biaya_lainnya_mar = $biaya_lainnya_biaya_mar['total'] + $biaya_lainnya_jurnal_mar['total'];
	$total_harga_pokok_pendapatan_mar = $total_volume_mar * $total;
	$laba_kotor_mar = $total_penjualan_all_mar - $total_harga_pokok_pendapatan_mar;
	$total_biaya_mar = $biaya_umum_administratif_mar + $biaya_lainnya_mar;
	$laba_usaha_mar = $laba_kotor_mar - $total_biaya_mar;
	$total_mar = $laba_usaha_mar;
	$persentase_mar = ($total_penjualan_all_mar!=0)?($total_mar / $total_penjualan_all_mar) * 100:0;
	$persentase_mar_fix = round($persentase_mar,2);

	//APR
	$penjualan_limbah_apr = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_april23_awal' and '$date_april23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_apr = 0;
	foreach ($penjualan_limbah_apr as $y){
		$total_penjualan_limbah_apr += $y['price'];
	}

	$penjualan_apr = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_april23_awal' and '$date_april23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_apr = 0;
	$total_volume_apr = 0;
	$measure_apr = 0;

	foreach ($penjualan_apr as $x){
		$total_penjualan_apr += $x['price'];
		$total_volume_apr += $x['volume'];
	}

	$total_penjualan_all_apr = 0;
	$total_penjualan_all_apr = $total_penjualan_apr + $total_penjualan_limbah_apr;

	$biaya_umum_administratif_biaya_apr = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_april23_awal' and '$date_april23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_apr = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_april23_awal' and '$date_april23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_apr = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_april23_awal' and '$date_april23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_apr = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_april23_awal' and '$date_april23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_apr = $biaya_umum_administratif_biaya_apr['total'] + $biaya_umum_administratif_jurnal_apr['total'];
	$biaya_lainnya_apr = $biaya_lainnya_biaya_apr['total'] + $biaya_lainnya_jurnal_apr['total'];
	$total_harga_pokok_pendapatan_apr = $total_volume_apr * $total;
	$laba_kotor_apr = $total_penjualan_all_apr - $total_harga_pokok_pendapatan_apr;
	$total_biaya_apr = $biaya_umum_administratif_apr + $biaya_lainnya_apr;
	$laba_usaha_apr = $laba_kotor_apr - $total_biaya_apr;
	$total_apr = $laba_usaha_apr + $nilai_persediaan_bahan_baku_apr + $nilai_persediaan_barang_jadi_apr;
	$persentase_apr = ($total_apr!=0)?($total_penjualan_all_apr / $total_apr) * 100:0;
	$persentase_apr_fix = round($persentase_apr,2);

	//MEI
	$penjualan_limbah_mei = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_mei23_awal' and '$date_mei23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_mei = 0;
	foreach ($penjualan_limbah_mei as $y){
		$total_penjualan_limbah_mei += $y['price'];
	}

	$penjualan_mei = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_mei23_awal' and '$date_mei23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_mei = 0;
	$total_volume_mei = 0;
	$measure_mei = 0;

	foreach ($penjualan_mei as $x){
		$total_penjualan_mei += $x['price'];
		$total_volume_mei += $x['volume'];
	}

	$total_penjualan_all_mei = 0;
	$total_penjualan_all_mei = $total_penjualan_mei + $total_penjualan_limbah_mei;

	$biaya_umum_administratif_biaya_mei = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_mei23_awal' and '$date_mei23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_mei = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_mei23_awal' and '$date_mei23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_mei = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_mei23_awal' and '$date_mei23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_mei = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_mei23_awal' and '$date_mei23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_mei = $biaya_umum_administratif_biaya_mei['total'] + $biaya_umum_administratif_jurnal_mei['total'];
	$biaya_lainnya_mei = $biaya_lainnya_biaya_mei['total'] + $biaya_lainnya_jurnal_mei['total'];
	$total_harga_pokok_pendapatan_mei = $total_volume_mei * $total;
	$laba_kotor_mei = $total_penjualan_all_mei - $total_harga_pokok_pendapatan_mei;
	$total_biaya_mei = $biaya_umum_administratif_mei + $biaya_lainnya_mei;
	$laba_usaha_mei = $laba_kotor_mei - $total_biaya_mei;
	$total_mei = $laba_usaha_mei;
	$persentase_mei = ($total_mei!=0)?($total_penjualan_all_mei / $total_mei) * 100:0;
	$persentase_mei_fix = round($persentase_mei,2);

	//JUN
	$penjualan_limbah_jun = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_juni23_awal' and '$date_juni23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_jun = 0;
	foreach ($penjualan_limbah_jun as $y){
		$total_penjualan_limbah_jun += $y['price'];
	}

	$penjualan_jun = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_juni23_awal' and '$date_juni23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_jun = 0;
	$total_volume_jun = 0;
	$measure_jun = 0;

	foreach ($penjualan_jun as $x){
		$total_penjualan_jun += $x['price'];
		$total_volume_jun += $x['volume'];
	}

	$total_penjualan_all_jun = 0;
	$total_penjualan_all_jun = $total_penjualan_jun + $total_penjualan_limbah_jun;

	$biaya_umum_administratif_biaya_jun = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juni23_awal' and '$date_juni23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_jun = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juni23_awal' and '$date_juni23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_jun = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juni23_awal' and '$date_juni23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_jun = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juni23_awal' and '$date_juni23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jun = $biaya_umum_administratif_biaya_jun['total'] + $biaya_umum_administratif_jurnal_jun['total'];
	$biaya_lainnya_jun = $biaya_lainnya_biaya_jun['total'] + $biaya_lainnya_jurnal_jun['total'];
	$total_harga_pokok_pendapatan_jun = $total_volume_jun * $total;
	$laba_kotor_jun = $total_penjualan_all_jun - $total_harga_pokok_pendapatan_jun;
	$total_biaya_jun = $biaya_umum_administratif_jun + $biaya_lainnya_jun;
	$laba_usaha_jun = $laba_kotor_jun - $total_biaya_jun;
	$total_jun = $laba_usaha_jun;
	$persentase_jun = ($total_jun!=0)?($total_penjualan_all_jun / $total_jun) * 100:0;
	$persentase_jun_fix = round($persentase_jun,2);

	//JUL
	$penjualan_limbah_jul = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_juli23_awal' and '$date_juli23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_jul = 0;
	foreach ($penjualan_limbah_jul as $y){
		$total_penjualan_limbah_jul += $y['price'];
	}

	$penjualan_jul = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_juli23_awal' and '$date_juli23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_jul = 0;
	$total_volume_jul = 0;
	$measure_jul = 0;

	foreach ($penjualan_jul as $x){
		$total_penjualan_jul += $x['price'];
		$total_volume_jul += $x['volume'];
	}

	$total_penjualan_all_jul = 0;
	$total_penjualan_all_jul = $total_penjualan_jul + $total_penjualan_limbah_jul;

	$biaya_umum_administratif_biaya_jul = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juli23_awal' and '$date_juli23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_jul = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juli23_awal' and '$date_juli23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_jul = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juli23_awal' and '$date_juli23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_jul = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_juli23_awal' and '$date_juli23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jul = $biaya_umum_administratif_biaya_jul['total'] + $biaya_umum_administratif_jurnal_jul['total'];
	$biaya_lainnya_jul = $biaya_lainnya_biaya_jul['total'] + $biaya_lainnya_jurnal_jul['total'];
	$total_harga_pokok_pendapatan_jul = $total_volume_jul * $total;
	$laba_kotor_jul = $total_penjualan_all_jul - $total_harga_pokok_pendapatan_jul;
	$total_biaya_jul = $biaya_umum_administratif_jul + $biaya_lainnya_jul;
	$laba_usaha_jul = $laba_kotor_jul - $total_biaya_jul;
	$total_jul = $laba_usaha_jul;
	$persentase_jul = ($total_jul!=0)?($total_penjualan_all_jul / $total_jul) * 100:0;
	$persentase_jul_fix = round($persentase_jul,2);

	//AGU
	$penjualan_limbah_agu = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_agu = 0;
	foreach ($penjualan_limbah_agu as $y){
		$total_penjualan_limbah_agu += $y['price'];
	}

	$penjualan_agu = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_agu = 0;
	$total_volume_agu = 0;
	$measure_agu = 0;

	foreach ($penjualan_agu as $x){
		$total_penjualan_agu += $x['price'];
		$total_volume_agu += $x['volume'];
	}

	$total_penjualan_all_agu = 0;
	$total_penjualan_all_agu = $total_penjualan_agu + $total_penjualan_limbah_agu;

	$biaya_umum_administratif_biaya_agu = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_agu = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_agu = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_agu = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_agu = $biaya_umum_administratif_biaya_agu['total'] + $biaya_umum_administratif_jurnal_agu['total'];
	$biaya_lainnya_agu = $biaya_lainnya_biaya_agu['total'] + $biaya_lainnya_jurnal_agu['total'];
	$total_harga_pokok_pendapatan_agu = $total_volume_agu * $total;
	$laba_kotor_agu = $total_penjualan_all_agu - $total_harga_pokok_pendapatan_agu;
	$total_biaya_agu = $biaya_umum_administratif_agu + $biaya_lainnya_agu;
	$laba_usaha_agu = $laba_kotor_agu - $total_biaya_agu;
	$total_agu = $laba_usaha_agu;
	$persentase_agu = ($total_agu!=0)?($total_penjualan_all_agu / $total_agu) * 100:0;
	$persentase_agu_fix = round($persentase_agu,2);

	//SEP
	$penjualan_limbah_sep = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_september23_awal' and '$date_september23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_sep = 0;
	foreach ($penjualan_limbah_sep as $y){
		$total_penjualan_limbah_sep += $y['price'];
	}

	$penjualan_sep = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_september23_awal' and '$date_september23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_sep = 0;
	$total_volume_sep = 0;
	$measure_sep = 0;

	foreach ($penjualan_sep as $x){
		$total_penjualan_sep += $x['price'];
		$total_volume_sep += $x['volume'];
	}

	$total_penjualan_all_sep = 0;
	$total_penjualan_all_sep = $total_penjualan_sep + $total_penjualan_limbah_sep;

	$biaya_umum_administratif_biaya_sep = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_september23_awal' and '$date_september23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_sep = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_september23_awal' and '$date_september23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_sep = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_september23_awal' and '$date_september23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_sep = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_september23_awal' and '$date_september23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_sep = $biaya_umum_administratif_biaya_sep['total'] + $biaya_umum_administratif_jurnal_sep['total'];
	$biaya_lainnya_sep = $biaya_lainnya_biaya_sep['total'] + $biaya_lainnya_jurnal_sep['total'];
	$total_harga_pokok_pendapatan_sep = $total_volume_sep * $total;
	$laba_kotor_sep = $total_penjualan_all_sep - $total_harga_pokok_pendapatan_sep;
	$total_biaya_sep = $biaya_umum_administratif_sep + $biaya_lainnya_sep;
	$laba_usaha_sep = $laba_kotor_sep - $total_biaya_sep;
	$total_sep = $laba_usaha_sep;
	$persentase_sep = ($total_sep!=0)?($total_penjualan_all_sep / $total_sep) * 100:0;
	$persentase_sep_fix = round($persentase_sep,2);

	//OKT
	$penjualan_limbah_okt = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_okt = 0;
	foreach ($penjualan_limbah_okt as $y){
		$total_penjualan_limbah_okt += $y['price'];
	}

	$penjualan_okt = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_okt = 0;
	$total_volume_okt = 0;
	$measure_okt = 0;

	foreach ($penjualan_okt as $x){
		$total_penjualan_okt += $x['price'];
		$total_volume_okt += $x['volume'];
	}

	$total_penjualan_all_okt = 0;
	$total_penjualan_all_okt = $total_penjualan_okt + $total_penjualan_limbah_okt;

	$biaya_umum_administratif_biaya_okt = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_okt = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_okt = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_okt = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_okt = $biaya_umum_administratif_biaya_okt['total'] + $biaya_umum_administratif_jurnal_okt['total'];
	$biaya_lainnya_okt = $biaya_lainnya_biaya_okt['total'] + $biaya_lainnya_jurnal_okt['total'];
	$total_harga_pokok_pendapatan_okt = $total_volume_okt * $total;
	$laba_kotor_okt = $total_penjualan_all_okt - $total_harga_pokok_pendapatan_okt;
	$total_biaya_okt = $biaya_umum_administratif_okt + $biaya_lainnya_okt;
	$laba_usaha_okt = $laba_kotor_okt - $total_biaya_okt;
	$total_okt = $laba_usaha_okt;
	$persentase_okt = ($total_okt!=0)?($total_penjualan_all_okt / $total_okt) * 100:0;
	$persentase_okt_fix = round($persentase_okt,2);

	//NOV
	$penjualan_limbah_nov = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_november23_awal' and '$date_november23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_nov = 0;
	foreach ($penjualan_limbah_nov as $y){
		$total_penjualan_limbah_nov += $y['price'];
	}

	$penjualan_nov = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_november23_awal' and '$date_november23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_nov = 0;
	$total_volume_nov = 0;
	$measure_nov = 0;

	foreach ($penjualan_nov as $x){
		$total_penjualan_nov += $x['price'];
		$total_volume_nov += $x['volume'];
	}

	$total_penjualan_all_nov = 0;
	$total_penjualan_all_nov = $total_penjualan_nov + $total_penjualan_limbah_nov;

	$biaya_umum_administratif_biaya_nov = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_november23_awal' and '$date_november23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_nov = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_november23_awal' and '$date_november23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_nov = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_november23_awal' and '$date_november23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_nov = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_november23_awal' and '$date_november23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_nov = $biaya_umum_administratif_biaya_nov['total'] + $biaya_umum_administratif_jurnal_nov['total'];
	$biaya_lainnya_nov = $biaya_lainnya_biaya_nov['total'] + $biaya_lainnya_jurnal_nov['total'];
	$total_harga_pokok_pendapatan_nov = $total_volume_nov * $total;
	$laba_kotor_nov = $total_penjualan_all_nov - $total_harga_pokok_pendapatan_nov;
	$total_biaya_nov = $biaya_umum_administratif_nov + $biaya_lainnya_nov;
	$laba_usaha_nov = $laba_kotor_nov - $total_biaya_nov;
	$total_nov = $laba_usaha_nov;
	$persentase_nov = ($total_nov!=0)?($total_penjualan_all_nov / $total_nov) * 100:0;
	$persentase_nov_fix = round($persentase_nov,2);

	//DES
	$penjualan_limbah_des = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_desember23_awal' and '$date_desember23_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_des = 0;
	foreach ($penjualan_limbah_des as $y){
		$total_penjualan_limbah_des += $y['price'];
	}

	$penjualan_des = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_desember23_awal' and '$date_desember23_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_des = 0;
	$total_volume_des = 0;
	$measure_des = 0;

	foreach ($penjualan_des as $x){
		$total_penjualan_des += $x['price'];
		$total_volume_des += $x['volume'];
	}

	$total_penjualan_all_des = 0;
	$total_penjualan_all_des = $total_penjualan_des + $total_penjualan_limbah_des;

	$biaya_umum_administratif_biaya_des = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_desember23_awal' and '$date_desember23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_des = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_desember23_awal' and '$date_desember23_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_des = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_desember23_awal' and '$date_desember23_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_des = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_desember23_awal' and '$date_desember23_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_des = $biaya_umum_administratif_biaya_des['total'] + $biaya_umum_administratif_jurnal_des['total'];
	$biaya_lainnya_des = $biaya_lainnya_biaya_des['total'] + $biaya_lainnya_jurnal_des['total'];
	$total_harga_pokok_pendapatan_des = $total_volume_des * $total;
	$laba_kotor_des = $total_penjualan_all_des - $total_harga_pokok_pendapatan_des;
	$total_biaya_des = $biaya_umum_administratif_des + $biaya_lainnya_des;
	$laba_usaha_des = $laba_kotor_des - $total_biaya_des;
	$total_des = $laba_usaha_des;
	$persentase_des = ($total_des!=0)?($total_penjualan_all_des / $total_des) * 100:0;
	$persentase_des_fix = round($persentase_des,2);

	//AKU
	$penjualan_limbah_aku = $this->db->select('SUM(pp.display_price) as price')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->where("pp.product_id = 9 ")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_limbah_aku = 0;
	foreach ($penjualan_limbah_aku as $y){
		$total_penjualan_limbah_aku += $y['price'];
	}

	$penjualan_aku = $this->db->select('p.nama, pp.client_id, SUM(pp.display_price) as price, SUM(pp.display_volume) as volume, pp.convert_measure as measure')
	->from('pmm_productions pp')
	->join('penerima p', 'pp.client_id = p.id','left')
	->join('pmm_sales_po ppo', 'pp.salesPo_id = ppo.id','left')
	->where("pp.date_production between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->where("pp.product_id in (3,4,7,8,14,24)")
	->where("pp.status = 'PUBLISH'")
	->where("ppo.status in ('OPEN','CLOSED')")
	->group_by("pp.client_id")
	->get()->result_array();

	$total_penjualan_aku = 0;
	$total_volume_aku = 0;
	$measure_aku = 0;

	foreach ($penjualan_aku as $x){
		$total_penjualan_aku += $x['price'];
		$total_volume_aku += $x['volume'];
	}

	$total_penjualan_all_aku = 0;
	$total_penjualan_all_aku = $total_penjualan_aku + $total_penjualan_limbah_aku;

	$biaya_umum_administratif_biaya_aku = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_jurnal_aku = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',16)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->get()->row_array();

	$biaya_lainnya_biaya_aku = $this->db->select('sum(pdb.jumlah) as total')
	->from('pmm_biaya pb ')
	->join('pmm_detail_biaya pdb','pb.id = pdb.biaya_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->get()->row_array();

	$biaya_lainnya_jurnal_aku = $this->db->select('sum(pdb.debit) as total')
	->from('pmm_jurnal_umum pb ')
	->join('pmm_detail_jurnal pdb','pb.id = pdb.jurnal_id','left')
	->join('pmm_coa c','pdb.akun = c.id','left')
	->where('c.coa_category',17)
	->where("pb.status = 'PAID'")
	->where("tanggal_transaksi between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->get()->row_array();

	$biaya_umum_administratif_aku = $biaya_umum_administratif_biaya_aku['total'] + $biaya_umum_administratif_jurnal_aku['total'];
	$biaya_lainnya_aku = $biaya_lainnya_biaya_aku['total'] + $biaya_lainnya_jurnal_aku['total'];
	$total_harga_pokok_pendapatan_aku = $total_volume_aku * $total;
	$laba_kotor_aku = $total_penjualan_all_aku - $total_harga_pokok_pendapatan_aku;
	$total_biaya_aku = $biaya_umum_administratif_aku + $biaya_lainnya_aku;
	$laba_usaha_aku = $laba_kotor_aku - $total_biaya_aku;
	$total_aku = $laba_usaha_aku;
	$persentase_aku = ($total_aku!=0)?($total_penjualan_all_aku / $total_aku) * 100:0;
	$persentase_aku_fix = round($persentase_aku,2);

?>