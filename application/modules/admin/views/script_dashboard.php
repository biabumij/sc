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
    $date_akumulasi_akhir = date('2023-07-31');

	//JAN
	$akumulasi_jan = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_januari23_awal' and '$date_januari23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_jan = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_januari23_awal' and '$date_januari23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_jan = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_januari23_awal' and '$date_januari23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_jan = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_januari23_awal' and '$date_januari23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_jan = $pergerakan_bahan_baku_jan['volume'];
	$total_nilai_pembelian_jan =  $pergerakan_bahan_baku_jan['nilai'];
	$total_harga_pembelian_jan = ($total_volume_pembelian_jan!=0)?($total_nilai_pembelian_jan / $total_volume_pembelian_jan)  * 1:0;

	$abu_batu_jan = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_januari23_awal' and '$date_januari23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_jan = $abu_batu_jan['jumlah_used'] * $total_harga_pembelian_jan;
	//END BPP

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
	$total_harga_pokok_pendapatan_jan = $akumulasi_hpp_jan['total'];
	$laba_kotor_jan = $total_penjualan_all_jan - $total_harga_pokok_pendapatan_jan;
	$total_biaya_jan = $biaya_umum_administratif_jan + $biaya_lainnya_jan;
	$laba_usaha_jan = $laba_kotor_jan - $total_biaya_jan;
	$nilai_persediaan_bahan_baku_jan = $akumulasi_jan['total_akhir'];
	$nilai_persediaan_barang_jadi_jan = $akumulasi2_jan['total_akhir'];
	$total_jan = $laba_usaha_jan + $nilai_persediaan_bahan_baku_jan + $nilai_persediaan_barang_jadi_jan;
	$persentase_jan = ($total_penjualan_all_jan!=0)?($total_jan / $total_penjualan_all_jan)  * 100:0;

	$persentase_jan_laba_usaha = ($total_penjualan_all_jan!=0)?($laba_usaha_jan / $total_penjualan_all_jan)  * 100:0;
	$persentase_jan_fix = round($persentase_jan_laba_usaha,2);

	//FEB
	$akumulasi_feb = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_februari23_awal' and '$date_februari23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_feb = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_februari23_awal' and '$date_februari23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_feb = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_februari23_awal' and '$date_februari23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_feb = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_februari23_awal' and '$date_februari23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_feb = $pergerakan_bahan_baku_feb['volume'];
	$total_nilai_pembelian_feb =  $pergerakan_bahan_baku_feb['nilai'];
	$total_harga_pembelian_feb = ($total_volume_pembelian_feb!=0)?($total_nilai_pembelian_feb / $total_volume_pembelian_feb)  * 1:0;

	$abu_batu_feb = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_februari23_awal' and '$date_februari23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_feb = $abu_batu_feb['jumlah_used'] * $total_harga_pembelian_feb;
	//END BPP

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
	$total_harga_pokok_pendapatan_feb = $akumulasi_hpp_feb['total'];
	$laba_kotor_feb = $total_penjualan_all_feb - $total_harga_pokok_pendapatan_feb;
	$total_biaya_feb = $biaya_umum_administratif_feb + $biaya_lainnya_feb;
	$laba_usaha_feb = $laba_kotor_feb - $total_biaya_feb;
	$nilai_persediaan_bahan_baku_feb = $akumulasi_feb['total_akhir'];
	$nilai_persediaan_barang_jadi_feb = $akumulasi2_feb['total_akhir'];
	$total_feb = $laba_usaha_feb + $nilai_persediaan_bahan_baku_feb + $nilai_persediaan_barang_jadi_feb;
	$persentase_feb = ($total_penjualan_all_feb!=0)?($total_feb / $total_penjualan_all_feb)  * 100:0;

	$persentase_feb_laba_usaha = ($total_penjualan_all_feb!=0)?($laba_usaha_feb / $total_penjualan_all_feb)  * 100:0;
	$persentase_feb_fix = round($persentase_feb_laba_usaha,2);

	//MAR
	$akumulasi_mar = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_maret23_awal' and '$date_maret23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_mar = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_maret23_awal' and '$date_maret23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_mar = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_maret23_awal' and '$date_maret23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_mar = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_maret23_awal' and '$date_maret23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_mar = $pergerakan_bahan_baku_mar['volume'];
	$total_nilai_pembelian_mar =  $pergerakan_bahan_baku_mar['nilai'];
	$total_harga_pembelian_mar = ($total_volume_pembelian_mar!=0)?($total_nilai_pembelian_mar / $total_volume_pembelian_mar)  * 1:0;

	$abu_batu_mar = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_maret23_awal' and '$date_maret23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_mar = $abu_batu_mar['jumlah_used'] * $total_harga_pembelian_mar;
	//END BPP

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
	$total_harga_pokok_pendapatan_mar = $akumulasi_hpp_mar['total'];
	$laba_kotor_mar = $total_penjualan_all_mar - $total_harga_pokok_pendapatan_mar;
	$total_biaya_mar = $biaya_umum_administratif_mar + $biaya_lainnya_mar;
	$laba_usaha_mar = $laba_kotor_mar - $total_biaya_mar;
	$nilai_persediaan_bahan_baku_mar = $akumulasi_mar['total_akhir'];
	$nilai_persediaan_barang_jadi_mar = $akumulasi2_mar['total_akhir'];
	$total_mar = $laba_usaha_mar + $nilai_persediaan_bahan_baku_mar + $nilai_persediaan_barang_jadi_mar;
	$persentase_mar = ($total_penjualan_all_mar!=0)?($total_mar / $total_penjualan_all_mar)  * 100:0;

	$persentase_mar_laba_usaha = ($total_penjualan_all_mar!=0)?($laba_usaha_mar / $total_penjualan_all_mar)  * 100:0;
	$persentase_mar_fix = round($persentase_mar_laba_usaha,2);

	//APR
	$akumulasi_apr = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_april23_awal' and '$date_april23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_apr = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_april23_awal' and '$date_april23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_apr = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_april23_awal' and '$date_april23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_apr = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_april23_awal' and '$date_april23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_apr = $pergerakan_bahan_baku_apr['volume'];
	$total_nilai_pembelian_apr =  $pergerakan_bahan_baku_apr['nilai'];
	$total_harga_pembelian_apr = ($total_volume_pembelian_apr!=0)?($total_nilai_pembelian_apr / $total_volume_pembelian_apr)  * 1:0;

	$abu_batu_apr = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_april23_awal' and '$date_april23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_apr = $abu_batu_apr['jumlah_used'] * $total_harga_pembelian_apr;
	//END BPP

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
	$total_harga_pokok_pendapatan_apr = $akumulasi_hpp_apr['total'];
	$laba_kotor_apr = $total_penjualan_all_apr - $total_harga_pokok_pendapatan_apr;
	$total_biaya_apr = $biaya_umum_administratif_apr + $biaya_lainnya_apr;
	$laba_usaha_apr = $laba_kotor_apr - $total_biaya_apr;
	$nilai_persediaan_bahan_baku_apr = $akumulasi_apr['total_akhir'];
	$nilai_persediaan_barang_jadi_apr = $akumulasi2_apr['total_akhir'];
	$total_apr = $laba_usaha_apr + $nilai_persediaan_bahan_baku_apr + $nilai_persediaan_barang_jadi_apr;
	$persentase_apr = ($total_penjualan_all_apr!=0)?($total_apr / $total_penjualan_all_apr)  * 100:0;

	$persentase_apr_laba_usaha = ($total_penjualan_all_apr!=0)?($laba_usaha_apr / $total_penjualan_all_apr)  * 100:0;
	$persentase_apr_fix = round($persentase_apr_laba_usaha,2);

	//MEI
	$akumulasi_mei = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_mei23_awal' and '$date_mei23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_mei = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_mei23_awal' and '$date_mei23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_mei = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_mei23_awal' and '$date_mei23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_mei = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_mei23_awal' and '$date_mei23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_mei = $pergerakan_bahan_baku_mei['volume'];
	$total_nilai_pembelian_mei =  $pergerakan_bahan_baku_mei['nilai'];
	$total_harga_pembelian_mei = ($total_volume_pembelian_mei!=0)?($total_nilai_pembelian_mei / $total_volume_pembelian_mei)  * 1:0;

	$abu_batu_mei = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_mei23_awal' and '$date_mei23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_mei = $abu_batu_mei['jumlah_used'] * $total_harga_pembelian_mei;
	//END BPP

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
	$total_harga_pokok_pendapatan_mei = $akumulasi_hpp_mei['total'];
	$laba_kotor_mei = $total_penjualan_all_mei - $total_harga_pokok_pendapatan_mei;
	$total_biaya_mei = $biaya_umum_administratif_mei + $biaya_lainnya_mei;
	$laba_usaha_mei = $laba_kotor_mei - $total_biaya_mei;
	$nilai_persediaan_bahan_baku_mei = $akumulasi_mei['total_akhir'];
	$nilai_persediaan_barang_jadi_mei = $akumulasi2_mei['total_akhir'];
	$total_mei = $laba_usaha_mei + $nilai_persediaan_bahan_baku_mei + $nilai_persediaan_barang_jadi_mei;
	$persentase_mei = ($total_penjualan_all_mei!=0)?($total_mei / $total_penjualan_all_mei)  * 100:0;

	$persentase_mei_laba_usaha = ($total_penjualan_all_mei!=0)?($laba_usaha_mei / $total_penjualan_all_mei)  * 100:0;
	$persentase_mei_fix = round($persentase_mei_laba_usaha,2);

	//JUN
	$akumulasi_jun = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_juni23_awal' and '$date_juni23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_jun = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_juni23_awal' and '$date_juni23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_jun = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_juni23_awal' and '$date_juni23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_jun = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_juni23_awal' and '$date_juni23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_jun = $pergerakan_bahan_baku_jun['volume'];
	$total_nilai_pembelian_jun =  $pergerakan_bahan_baku_jun['nilai'];
	$total_harga_pembelian_jun = ($total_volume_pembelian_jun!=0)?($total_nilai_pembelian_jun / $total_volume_pembelian_jun)  * 1:0;

	$abu_batu_jun = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_juni23_awal' and '$date_juni23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_jun = $abu_batu_jun['jumlah_used'] * $total_harga_pembelian_jun;
	//END BPP

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
	$total_harga_pokok_pendapatan_jun = $akumulasi_hpp_jun['total'];
	$laba_kotor_jun = $total_penjualan_all_jun - $total_harga_pokok_pendapatan_jun;
	$total_biaya_jun = $biaya_umum_administratif_jun + $biaya_lainnya_jun;
	$laba_usaha_jun = $laba_kotor_jun - $total_biaya_jun;
	$nilai_persediaan_bahan_baku_jun = $akumulasi_jun['total_akhir'];
	$nilai_persediaan_barang_jadi_jun = $akumulasi2_jun['total_akhir'];
	$total_jun = $laba_usaha_jun + $nilai_persediaan_bahan_baku_jun + $nilai_persediaan_barang_jadi_jun;
	$persentase_jun = ($total_penjualan_all_jun!=0)?($total_jun / $total_penjualan_all_jun)  * 100:0;

	$persentase_jun_laba_usaha = ($total_penjualan_all_jun!=0)?($laba_usaha_jun / $total_penjualan_all_jun)  * 100:0;
	$persentase_jun_fix = round($persentase_jun_laba_usaha,2);

	//JUL
	$akumulasi_jul = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_juli23_awal' and '$date_juli23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_jul = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_juli23_awal' and '$date_juli23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_jul = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_juli23_awal' and '$date_juli23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_jul = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_juli23_awal' and '$date_juli23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_jul = $pergerakan_bahan_baku_jul['volume'];
	$total_nilai_pembelian_jul =  $pergerakan_bahan_baku_jul['nilai'];
	$total_harga_pembelian_jul = ($total_volume_pembelian_jul!=0)?($total_nilai_pembelian_jul / $total_volume_pembelian_jul)  * 1:0;

	$abu_batu_jul = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_juli23_awal' and '$date_juli23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_jul = $abu_batu_jul['jumlah_used'] * $total_harga_pembelian_jul;
	//END BPP

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
	$total_harga_pokok_pendapatan_jul = $akumulasi_hpp_jul['total'];
	$laba_kotor_jul = $total_penjualan_all_jul - $total_harga_pokok_pendapatan_jul;
	$total_biaya_jul = $biaya_umum_administratif_jul + $biaya_lainnya_jul;
	$laba_usaha_jul = $laba_kotor_jul - $total_biaya_jul;
	$nilai_persediaan_bahan_baku_jul = $akumulasi_jul['total_akhir'];
	$nilai_persediaan_barang_jadi_jul = $akumulasi2_jul['total_akhir'];
	$total_jul = $laba_usaha_jul + $nilai_persediaan_bahan_baku_jul + $nilai_persediaan_barang_jadi_jul;
	$persentase_jul = ($total_penjualan_all_jul!=0)?($total_jul / $total_penjualan_all_jul)  * 100:0;

	$persentase_jul_laba_usaha = ($total_penjualan_all_jul!=0)?($laba_usaha_jul / $total_penjualan_all_jul)  * 100:0;
	$persentase_jul_fix = round($persentase_jul_laba_usaha,2);

	//AGU
	$akumulasi_agu = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_agu = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_agu = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_agu = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_agu = $pergerakan_bahan_baku_agu['volume'];
	$total_nilai_pembelian_agu =  $pergerakan_bahan_baku_agu['nilai'];
	$total_harga_pembelian_agu = ($total_volume_pembelian_agu!=0)?($total_nilai_pembelian_agu / $total_volume_pembelian_agu)  * 1:0;

	$abu_batu_agu = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_agustus23_awal' and '$date_agustus23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_agu = $abu_batu_agu['jumlah_used'] * $total_harga_pembelian_agu;
	//END BPP

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
	$total_harga_pokok_pendapatan_agu = $akumulasi_hpp_agu['total'];
	$laba_kotor_agu = $total_penjualan_all_agu - $total_harga_pokok_pendapatan_agu;
	$total_biaya_agu = $biaya_umum_administratif_agu + $biaya_lainnya_agu;
	$laba_usaha_agu = $laba_kotor_agu - $total_biaya_agu;
	$nilai_persediaan_bahan_baku_agu = $akumulasi_agu['total_akhir'];
	$nilai_persediaan_barang_jadi_agu = $akumulasi2_agu['total_akhir'];
	$total_agu = $laba_usaha_agu + $nilai_persediaan_bahan_baku_agu + $nilai_persediaan_barang_jadi_agu;
	$persentase_agu = ($total_penjualan_all_agu!=0)?($total_agu / $total_penjualan_all_agu)  * 100:0;

	$persentase_agu_laba_usaha = ($total_penjualan_all_agu!=0)?($laba_usaha_agu / $total_penjualan_all_agu)  * 100:0;
	$persentase_agu_fix = round($persentase_agu_laba_usaha,2);

	//SEP
	$akumulasi_sep = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_september23_awal' and '$date_september23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_sep = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_september23_awal' and '$date_september23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_sep = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_september23_awal' and '$date_september23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_sep = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_september23_awal' and '$date_september23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_sep = $pergerakan_bahan_baku_sep['volume'];
	$total_nilai_pembelian_sep =  $pergerakan_bahan_baku_sep['nilai'];
	$total_harga_pembelian_sep = ($total_volume_pembelian_sep!=0)?($total_nilai_pembelian_sep / $total_volume_pembelian_sep)  * 1:0;

	$abu_batu_sep = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_september23_awal' and '$date_september23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_sep = $abu_batu_sep['jumlah_used'] * $total_harga_pembelian_sep;
	//END BPP

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
	$total_harga_pokok_pendapatan_sep = $akumulasi_hpp_sep['total'];
	$laba_kotor_sep = $total_penjualan_all_sep - $total_harga_pokok_pendapatan_sep;
	$total_biaya_sep = $biaya_umum_administratif_sep + $biaya_lainnya_sep;
	$laba_usaha_sep = $laba_kotor_sep - $total_biaya_sep;
	$nilai_persediaan_bahan_baku_sep = $akumulasi_sep['total_akhir'];
	$nilai_persediaan_barang_jadi_sep = $akumulasi2_sep['total_akhir'];
	$total_sep = $laba_usaha_sep + $nilai_persediaan_bahan_baku_sep + $nilai_persediaan_barang_jadi_sep;
	$persentase_sep = ($total_penjualan_all_sep!=0)?($total_sep / $total_penjualan_all_sep)  * 100:0;

	$persentase_sep_laba_usaha = ($total_penjualan_all_sep!=0)?($laba_usaha_sep / $total_penjualan_all_sep)  * 100:0;
	$persentase_sep_fix = round($persentase_sep_laba_usaha,2);

	//OKT
	$akumulasi_okt = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_okt = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_okt = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_okt = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_okt = $pergerakan_bahan_baku_okt['volume'];
	$total_nilai_pembelian_okt =  $pergerakan_bahan_baku_okt['nilai'];
	$total_harga_pembelian_okt = ($total_volume_pembelian_okt!=0)?($total_nilai_pembelian_okt / $total_volume_pembelian_okt)  * 1:0;

	$abu_batu_okt = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_oktober23_awal' and '$date_oktober23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_okt = $abu_batu_okt['jumlah_used'] * $total_harga_pembelian_okt;
	//END BPP

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
	$total_harga_pokok_pendapatan_okt = $akumulasi_hpp_okt['total'];
	$laba_kotor_okt = $total_penjualan_all_okt - $total_harga_pokok_pendapatan_okt;
	$total_biaya_okt = $biaya_umum_administratif_okt + $biaya_lainnya_okt;
	$laba_usaha_okt = $laba_kotor_okt - $total_biaya_okt;
	$nilai_persediaan_bahan_baku_okt = $akumulasi_okt['total_akhir'];
	$nilai_persediaan_barang_jadi_okt = $akumulasi2_okt['total_akhir'];
	$total_okt = $laba_usaha_okt + $nilai_persediaan_bahan_baku_okt + $nilai_persediaan_barang_jadi_okt;
	$persentase_okt = ($total_penjualan_all_okt!=0)?($total_okt / $total_penjualan_all_okt)  * 100:0;

	$persentase_okt_laba_usaha = ($total_penjualan_all_okt!=0)?($laba_usaha_okt / $total_penjualan_all_okt)  * 100:0;
	$persentase_okt_fix = round($persentase_okt_laba_usaha,2);

	//NOV
	$akumulasi_nov = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_november23_awal' and '$date_november23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_nov = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_november23_awal' and '$date_november23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_nov = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_november23_awal' and '$date_november23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_nov = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_november23_awal' and '$date_november23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_nov = $pergerakan_bahan_baku_nov['volume'];
	$total_nilai_pembelian_nov =  $pergerakan_bahan_baku_nov['nilai'];
	$total_harga_pembelian_nov = ($total_volume_pembelian_nov!=0)?($total_nilai_pembelian_nov / $total_volume_pembelian_nov)  * 1:0;

	$abu_batu_nov = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_november23_awal' and '$date_november23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_nov = $abu_batu_nov['jumlah_used'] * $total_harga_pembelian_nov;
	//END BPP

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
	$total_harga_pokok_pendapatan_nov = $akumulasi_hpp_nov['total'];
	$laba_kotor_nov = $total_penjualan_all_nov - $total_harga_pokok_pendapatan_nov;
	$total_biaya_nov = $biaya_umum_administratif_nov + $biaya_lainnya_nov;
	$laba_usaha_nov = $laba_kotor_nov - $total_biaya_nov;
	$nilai_persediaan_bahan_baku_nov = $akumulasi_nov['total_akhir'];
	$nilai_persediaan_barang_jadi_nov = $akumulasi2_nov['total_akhir'];
	$total_nov = $laba_usaha_nov + $nilai_persediaan_bahan_baku_nov + $nilai_persediaan_barang_jadi_nov;
	$persentase_nov = ($total_penjualan_all_nov!=0)?($total_nov / $total_penjualan_all_nov)  * 100:0;

	$persentase_nov_laba_usaha = ($total_penjualan_all_nov!=0)?($laba_usaha_nov / $total_penjualan_all_nov)  * 100:0;
	$persentase_nov_fix = round($persentase_nov_laba_usaha,2);

	//DES
	$akumulasi_des = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_desember23_awal' and '$date_desember23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_des = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_desember23_awal' and '$date_desember23_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_des = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_desember23_awal' and '$date_desember23_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_des = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_desember23_awal' and '$date_desember23_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_des = $pergerakan_bahan_baku_des['volume'];
	$total_nilai_pembelian_des =  $pergerakan_bahan_baku_des['nilai'];
	$total_harga_pembelian_des = ($total_volume_pembelian_des!=0)?($total_nilai_pembelian_des / $total_volume_pembelian_des)  * 1:0;

	$abu_batu_des = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_desember23_awal' and '$date_desember23_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_des = $abu_batu_des['jumlah_used'] * $total_harga_pembelian_des;
	//END BPP

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
	$total_harga_pokok_pendapatan_des = $akumulasi_hpp_des['total'];
	$laba_kotor_des = $total_penjualan_all_des - $total_harga_pokok_pendapatan_des;
	$total_biaya_des = $biaya_umum_administratif_des + $biaya_lainnya_des;
	$laba_usaha_des = $laba_kotor_des - $total_biaya_des;
	$nilai_persediaan_bahan_baku_des = $akumulasi_des['total_akhir'];
	$nilai_persediaan_barang_jadi_des = $akumulasi2_des['total_akhir'];
	$total_des = $laba_usaha_des + $nilai_persediaan_bahan_baku_des + $nilai_persediaan_barang_jadi_des;
	$persentase_des = ($total_penjualan_all_des!=0)?($total_des / $total_penjualan_all_des)  * 100:0;

	$persentase_des_laba_usaha = ($total_penjualan_all_des!=0)?($laba_usaha_des / $total_penjualan_all_des)  * 100:0;
	$persentase_des_fix = round($persentase_des_laba_usaha,2);

	//AKU
	$akumulasi_aku = $this->db->select('pp.date_akumulasi, (pp.total_nilai_keluar) as total, (pp.total_nilai_keluar_2) as total_2, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi_bahan_baku pp')
	->where("pp.date_akumulasi between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi2_aku = $this->db->select('pp.date_akumulasi, (pp.total_nilai_akhir) as total_akhir')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->order_by('pp.date_akumulasi','desc')->limit(1)
	->get()->row_array();

	$akumulasi_hpp_aku = $this->db->select('pp.date_akumulasi, SUM(pp.total_nilai_keluar) as total')
	->from('akumulasi pp')
	->where("pp.date_akumulasi between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->get()->row_array();

	//BPP
	$pergerakan_bahan_baku_aku = $this->db->select('
	p.nama_produk, 
	prm.display_measure as satuan, 
	SUM(prm.display_volume) as volume, 
	(prm.display_price / prm.display_volume) as harga, 
	SUM(prm.display_price) as nilai')
	->from('pmm_receipt_material prm')
	->join('pmm_purchase_order po', 'prm.purchase_order_id = po.id','left')
	->join('produk p', 'prm.material_id = p.id','left')
	->where("prm.date_receipt between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->where("prm.material_id = 15")
	->group_by('prm.material_id')
	->get()->row_array();

	$total_volume_pembelian_aku = $pergerakan_bahan_baku_aku['volume'];
	$total_nilai_pembelian_aku =  $pergerakan_bahan_baku_aku['nilai'];
	$total_harga_pembelian_aku = ($total_volume_pembelian_aku!=0)?($total_nilai_pembelian_aku / $total_volume_pembelian_aku)  * 1:0;

	$abu_batu_aku = $this->db->select('pph.no_prod, SUM(pphd.use) as jumlah_used, (SUM(pphd.use) * pk.presentase_a) / 100 AS jumlah_pemakaian_a,  (SUM(pphd.use) * pk.presentase_b) / 100 AS jumlah_pemakaian_b,  (SUM(pphd.use) * pk.presentase_c) / 100 AS jumlah_pemakaian_c,  (SUM(pphd.use) * pk.presentase_d) / 100 AS jumlah_pemakaian_d,  (SUM(pphd.use) * pk.presentase_e) / 100 AS jumlah_pemakaian_e, pk.produk_a, pk.produk_b, pk.produk_c, pk.produk_d, pk.produk_e, pk.measure_a, pk.measure_b, pk.measure_c, pk.measure_d, pk.measure_e, pk.presentase_a, pk.presentase_b, pk.presentase_c, pk.presentase_d, pk.presentase_e, (pk.presentase_a + pk.presentase_b + pk.presentase_c + pk.presentase_d + pk.presentase_e) as jumlah_presentase')
	->from('pmm_produksi_harian pph ')
	->join('pmm_produksi_harian_detail pphd','pphd.produksi_harian_id = pph.id','left')
	->join('pmm_kalibrasi pk', 'pphd.product_id = pk.id','left')
	->where("pph.date_prod between '$date_akumulasi_awal' and '$date_akumulasi_akhir'")
	->where("pph.status = 'PUBLISH'")
	->get()->row_array();

	$nilai_abu_batu_total_aku = $abu_batu_aku['jumlah_used'] * $total_harga_pembelian_aku;
	//END BPP

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
	$total_harga_pokok_pendapatan_aku = $akumulasi_hpp_aku['total'];
	$laba_kotor_aku = $total_penjualan_all_aku - $total_harga_pokok_pendapatan_aku;
	$total_biaya_aku = $biaya_umum_administratif_aku + $biaya_lainnya_aku;
	$laba_usaha_aku = $laba_kotor_aku - $total_biaya_aku;
	$nilai_persediaan_bahan_baku_aku = $akumulasi_aku['total_akhir'];
	$nilai_persediaan_barang_jadi_aku = $akumulasi2_aku['total_akhir'];
	$total_aku = $laba_usaha_aku + $nilai_persediaan_bahan_baku_aku + $nilai_persediaan_barang_jadi_aku;
	$persentase_aku = ($total_penjualan_all_aku!=0)?($total_aku / $total_penjualan_all_aku)  * 100:0;

	$persentase_aku_laba_usaha = ($total_penjualan_all_aku!=0)?($laba_usaha_aku / $total_penjualan_all_aku)  * 100:0;
	$persentase_aku_fix = round($persentase_aku_laba_usaha,2);

?>