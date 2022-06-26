<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
        }
    </style>
</head>

<body>
    <div class="wrap">
        
        <?php echo $this->Templates->PageHeader();?>

        <div class="page-body">
            <?php echo $this->Templates->LeftBar();?>
            <div class="content" style="padding:0;">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-money" aria-hidden="true"></i>RAP</li>
                            
                            <li><a>RAP</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">RAP</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rap/submit_rap');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-3">
                                            <label>Tanggal</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_rap" required="" value="" />
                                        </div>
										<?php
										$products = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH','betonreadymix'=>1))->result_array();
										$slump = $this->db->order_by('slump', 'asc')->get_where('pmm_jmd', array('status' => 'PUBLISH'))->result_array();
										$nomor_komposisi = $this->db->order_by('nomor_komposisi', 'asc')->get_where('pmm_jmd', array('status' => 'PUBLISH'))->result_array();
										?>
										<div class="col-sm-3">
                                            <label>Mutu Beton</label>
											<select id="mutu_beton" class="form-control" name="mutu_beton">
												<option value="">Pilih Mutu Beton</option>
												<?php
													if(!empty($products)){
														foreach ($products as $row) {
															?>
															<option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
															<?php
														}
													}
													?>
											</select>
                                        </div>    
										<div class="col-sm-3">
                                            <label>Slump</label>
											<select id="slump" class="form-control" name="slump">
                                            <option value="">Pilih Slump</option>
												<?php
													if(!empty($slump)){
														foreach ($slump as $sl) {
															?>
															<option value="<?php echo $sl['id'];?>"><?php echo $sl['slump'];?></option>
															<?php
														}
													}
													?>
											</select>
                                        </div>
                                    </div>
									<div class="row">
										 <div class="col-sm-6">
                                            <label>Nomor Komposisi</label>
											<select id="nomor_komposisi" class="form-control" name="nomor_komposisi">
											<option value="">Pilih Nomor Komposisi</option>
												<?php
													if(!empty($nomor_komposisi)){
														foreach ($nomor_komposisi as $nk) {
															?>
															<option value="<?php echo $nk['id'];?>" data-koef_a="<?php echo $nk['koef_a'];?>" data-id="<?= $nk['id'];?>"><?php echo $nk['nomor_komposisi'];?></option>?>
															<?php
														}
													}
													?>
											</select>                                 
                                        </div> 
										<div class="col-sm-6">
                                            <label>Nomor RAP</label>
                                            <input type="text" class="form-control" name="nomor_rap" required="" value="<?= $this->pmm_model->GetNoRap();?>">
                                        </div> 
									</div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
													<th>Satuan</th>
													<th>Komposisi</th>
													<th>Rekanan</th>
													<th>Nomor Penawaran</th>
													<th>Harga Satuan</th>
													<th>Jumlah Harga</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
												$products = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH','laboratorium' => '1'))->result_array();
												$koef_a = $this->db->order_by('koef_a', 'asc')->get_where('pmm_jmd', array('status' => 'PUBLISH'))->result_array();
												$koef_b = $this->db->order_by('koef_b', 'asc')->get_where('pmm_jmd', array('status' => 'PUBLISH'))->result_array();
												$koef_c = $this->db->order_by('koef_c', 'asc')->get_where('pmm_jmd', array('status' => 'PUBLISH'))->result_array();
												$koef_d = $this->db->order_by('koef_d', 'asc')->get_where('pmm_jmd', array('status' => 'PUBLISH'))->result_array();
												$koef_e = $this->db->order_by('koef_e', 'asc')->get_where('pmm_jmd', array('status' => 'PUBLISH'))->result_array();
												$rekanan = $this->db->order_by('nama', 'asc')->get_where('penerima', array('status' => 'PUBLISH', 'rekanan' => '1'))->result_array();
												$nomor_penawaran = $this->db->order_by('nomor_penawaran', 'asc')->get_where('pmm_penawaran_pembelian', array('status' => 'OPEN'))->result_array();
												$harga_satuan = $this->db->order_by('price', 'asc')->get_where('pmm_penawaran_pembelian_detail')->result_array();
												?>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>														
                                                        <select id="semen_2" class="form-control form-select2" name="semen_2" required="">
                                                            <option value="" >Pilih Produk</option>
                                                            <?php
                                                            if(!empty($products)){
                                                                foreach ($products as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="measure_a" class="form-control form-select2" name="measure_a" required="" >
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $ms) {
                                                                    ?>
                                                                    <option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="komposisi_a" type="number" step=".01" min="0" class="form-control input-sm text-center numberformat" name="komposisi_a" required="">
                                                            <option value="" >Pilih Komposisi</option>
                                                            <?php
                                                            if(!empty($koef_a)){
                                                                foreach ($koef_a as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['koef_a'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="rekanan_a" class="form-control form-select2" name="rekanan_a" required="">
                                                            <option value="" >Pilih Rekanan</option>
                                                            <?php
                                                            if(!empty($rekanan)){
                                                                foreach ($rekanan as $rk) {
                                                                    ?>
                                                                    <option value="<?php echo $rk['id'];?>"><?php echo $rk['nama'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="nomor_penawaran_a" class="form-control form-select2" name="nomor_penawaran_a" required="">
                                                            <option value="" >Pilih Nomor Penawaran</option>
                                                            <?php
                                                            if(!empty($nomor_penawaran)){
                                                                foreach ($nomor_penawaran as $np) {
                                                                    ?>
                                                                    <option value="<?php echo $np['id'];?>"><?php echo $np['nomor_penawaran'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="harga_satuan_a" class="form-control form-select2" name="harga_satuan_a" required="">
                                                            <option value="" >Pilih Harga Satuan</option>
                                                            <?php
                                                            if(!empty($harga_satuan)){
                                                                foreach ($harga_satuan as $hs) {
                                                                    ?>
                                                                    <option value="<?php echo $hs['id'];?>"><?php echo $hs['price'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="jumlah_harga_a" id="jumlah_harga_a" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                </tr>
												<tr>
                                                    <td>2.</td>
                                                    <td>														
                                                        <select id="pasir_3" class="form-control form-select2" name="pasir_3" required="" >
                                                            <option value="">Pilih Produk</option>
                                                            <?php
                                                            if(!empty($products)){
                                                                foreach ($products as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
													    <select id="measure_b" class="form-control form-select2" name="measure_b" required="" >
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $ms) {
                                                                    ?>
                                                                    <option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="komposisi_b" type="number" step=".01" min="0" class="form-control input-sm text-center numberformat" name="komposisi_b" required="">
                                                            <option value="" >Pilih Komposisi</option>
                                                            <?php
                                                            if(!empty($koef_b)){
                                                                foreach ($koef_b as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['koef_b'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td> 
													<td>
                                                        <select id="rekanan_b" class="form-control form-select2" name="rekanan_b" required="">
                                                            <option value="" >Pilih Rekanan</option>
                                                            <?php
                                                            if(!empty($rekanan)){
                                                                foreach ($rekanan as $rk) {
                                                                    ?>
                                                                    <option value="<?php echo $rk['id'];?>"><?php echo $rk['nama'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="nomor_penawaran_b" class="form-control form-select2" name="nomor_penawaran_b" required="">
                                                            <option value="" >Pilih Nomor Penawaran</option>
                                                            <?php
                                                            if(!empty($nomor_penawaran)){
                                                                foreach ($nomor_penawaran as $np) {
                                                                    ?>
                                                                    <option value="<?php echo $np['id'];?>"><?php echo $np['nomor_penawaran'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="harga_satuan_b" class="form-control form-select2" name="harga_satuan_b" required="">
                                                            <option value="" >Pilih Harga Satuan</option>
                                                            <?php
                                                            if(!empty($harga_satuan)){
                                                                foreach ($harga_satuan as $hs) {
                                                                    ?>
                                                                    <option value="<?php echo $hs['id'];?>"><?php echo $hs['price'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="jumlah_harga_b" id="jumlah_harga_b" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                </tr>
												<tr>
                                                    <td>3.</td>
                                                    <td>														
                                                        <select id="batu_split_12" class="form-control form-select2" name="batu_split_12" required="" >
                                                            <option value="">Pilih Produk</option>
                                                            <?php
                                                            if(!empty($products)){
                                                                foreach ($products as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="measure_c" class="form-control form-select2" name="measure_c" required="" >
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $ms) {
                                                                    ?>
                                                                    <option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="komposisi_c" type="number" step=".01" min="0" class="form-control input-sm text-center numberformat" name="komposisi_c" required="">
                                                            <option value="" >Pilih Komposisi</option>
                                                            <?php
                                                            if(!empty($koef_c)){
                                                                foreach ($koef_c as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['koef_c'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td> 
													<td>
                                                        <select id="rekanan_c" class="form-control form-select2" name="rekanan_c" required="">
                                                            <option value="" >Pilih Rekanan</option>
                                                            <?php
                                                            if(!empty($rekanan)){
                                                                foreach ($rekanan as $rk) {
                                                                    ?>
                                                                    <option value="<?php echo $rk['id'];?>"><?php echo $rk['nama'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="nomor_penawaran_c" class="form-control form-select2" name="nomor_penawaran_c" required="">
                                                            <option value="" >Pilih Nomor Penawaran</option>
                                                            <?php
                                                            if(!empty($nomor_penawaran)){
                                                                foreach ($nomor_penawaran as $np) {
                                                                    ?>
                                                                    <option value="<?php echo $np['id'];?>"><?php echo $np['nomor_penawaran'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="harga_satuan_c" class="form-control form-select2" name="harga_satuan_c" required="">
                                                            <option value="" >Pilih Harga Satuan</option>
                                                            <?php
                                                            if(!empty($harga_satuan)){
                                                                foreach ($harga_satuan as $hs) {
                                                                    ?>
                                                                    <option value="<?php echo $hs['id'];?>"><?php echo $hs['price'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="jumlah_harga_c" id="jumlah_harga_c" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                </tr>
												<tr>
                                                    <td>4.</td>
                                                    <td>														
                                                        <select id="batu_split_23" class="form-control form-select2" name="batu_split_23" required="" >
                                                            <option value="">Pilih Produk</option>
                                                            <?php
                                                            if(!empty($products)){
                                                                foreach ($products as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="measure_d" class="form-control form-select2" name="measure_d" required="" >
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $ms) {
                                                                    ?>
                                                                    <option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="komposisi_d" type="number" step=".01" min="0" class="form-control input-sm text-center numberformat" name="komposisi_d" required="">
                                                            <option value="" >Pilih Komposisi</option>
                                                            <?php
                                                            if(!empty($koef_d)){
                                                                foreach ($koef_d as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['koef_d'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td> 
													<td>
                                                        <select id="rekanan_d" class="form-control form-select2" name="rekanan_d" required="">
                                                            <option value="" >Pilih Rekanan</option>
                                                            <?php
                                                            if(!empty($rekanan)){
                                                                foreach ($rekanan as $rk) {
                                                                    ?>
                                                                    <option value="<?php echo $rk['id'];?>"><?php echo $rk['nama'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="nomor_penawaran_d" class="form-control form-select2" name="nomor_penawaran_d" required="">
                                                            <option value="" >Pilih Nomor Penawaran</option>
                                                            <?php
                                                            if(!empty($nomor_penawaran)){
                                                                foreach ($nomor_penawaran as $np) {
                                                                    ?>
                                                                    <option value="<?php echo $np['id'];?>"><?php echo $np['nomor_penawaran'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="harga_satuan_d" class="form-control form-select2" name="harga_satuan_d" required="">
                                                            <option value="" >Pilih Harga Satuan</option>
                                                            <?php
                                                            if(!empty($harga_satuan)){
                                                                foreach ($harga_satuan as $hs) {
                                                                    ?>
                                                                    <option value="<?php echo $hs['id'];?>"><?php echo $hs['price'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="jumlah_harga_d" id="jumlah_harga_d" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                </tr>
												<tr>
                                                    <td>5.</td>
                                                    <td>														
                                                        <select id="additon" class="form-control form-select2" name="additon" required="" >
                                                            <option value="">Pilih Produk</option>
                                                            <?php
                                                            if(!empty($products)){
                                                                foreach ($products as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['nama_produk'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="measure_e" class="form-control form-select2" name="measure_e" required="" >
                                                            <option value="">Pilih Satuan</option>
                                                            <?php
                                                            if(!empty($measures)){
                                                                foreach ($measures as $ms) {
                                                                    ?>
                                                                    <option value="<?php echo $ms['id'];?>"><?php echo $ms['measure_name'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="komposisi_e" type="number" step=".01" min="0" class="form-control input-sm text-center numberformat" name="komposisi_e" required="">
                                                            <option value="" >Pilih Komposisi</option>
                                                            <?php
                                                            if(!empty($koef_e)){
                                                                foreach ($koef_e as $row) {
                                                                    ?>
                                                                    <option value="<?php echo $row['id'];?>"><?php echo $row['koef_e'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>      
													<td>
                                                        <select id="rekanan_e" class="form-control form-select2" name="rekanan_e" required="">
                                                            <option value="" >Pilih Rekanan</option>
                                                            <?php
                                                            if(!empty($rekanan)){
                                                                foreach ($rekanan as $rk) {
                                                                    ?>
                                                                    <option value="<?php echo $rk['id'];?>"><?php echo $rk['nama'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="nomor_penawaran_e" class="form-control form-select2" name="nomor_penawaran_e" required="">
                                                            <option value="" >Pilih Nomor Penawaran</option>
                                                            <?php
                                                            if(!empty($nomor_penawaran)){
                                                                foreach ($nomor_penawaran as $np) {
                                                                    ?>
                                                                    <option value="<?php echo $np['id'];?>"><?php echo $np['nomor_penawaran'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <select id="harga_satuan_e" class="form-control form-select2" name="harga_satuan_e" required="">
                                                            <option value="" >Pilih Harga Satuan</option>
                                                            <?php
                                                            if(!empty($harga_satuan)){
                                                                foreach ($harga_satuan as $hs) {
                                                                    ?>
                                                                    <option value="<?php echo $hs['id'];?>"><?php echo $hs['price'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="jumlah_harga_e" id="jumlah_harga_e" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                </tr>
											
												
                                            </tbody>
                                        </table>    
                                    </div>
									<br />
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/rap');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Buat RAP</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
        </div>
    </div>
    
    <script type="text/javascript">
        var form_control = '';
    </script>
    <?php echo $this->Templates->Footer();?>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
   
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    

     <script type="text/javascript">
        
        $('.form-select2').select2();

        $('input.numberformat').number( true, 2,',','.' );
        tinymce.init({
          selector: 'textarea#about_text',
          height: 200,
          menubar: false,
        });
        $('.dtpicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns : true,
            locale: {
              format: 'DD-MM-YYYY'
            }
        });
        $('.dtpicker').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY'));
        });

        $('#form-po').submit(function(e){
            e.preventDefault();
            var currentForm = this;
            bootbox.confirm({
                message: "Apakah anda yakin untuk proses data ini ?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        currentForm.submit();
                    }
                    
                }
            });
            
        });
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#semen_2').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });		
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_a').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#pasir_3').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_b').prop('selectedIndex', 2).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#batu_split_12').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_c').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#batu_split_23').prop('selectedIndex', 4).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_d').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#additon').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_e').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		
		
    </script>


</body>
</html>
