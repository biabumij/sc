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
                            <li><i class="fa fa-tasks" aria-hidden="true"></i>Laboratorium</li>
                            
                            <li><a>Job Mix Design</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Buat Job Mix Design</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('laboratorium/submit_jmd');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Tanggal</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_jmd" required="" value="" />
                                        </div>
										<?php
										$products = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH','betonreadymix'=>1))->result_array();
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
                                            <input type="text" class="form-control" name="slump" required="" value="" />
                                        </div>
										<div class="col-sm-4">
                                            <label>Nama Komposisi</label>
                                            <input type="text" class="form-control" name="nama_komposisi" required="" value="" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Nomor Komposisi</label>
                                            <input type="text" class="form-control" name="nomor_komposisi" required="" value="<?= $this->pmm_model->GetNoKomposisi();?>">
                                        </div>                                    
                                    <br />                               
                                    </div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="35%">Produk</th>
                                                    <th width="20%">Kode</th>
													<th width="20%">Volume</th>
                                                    <th width="20%">Satuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
												<?php
												$products = $this->db->order_by('nama_produk', 'asc')->get_where('produk', array('status' => 'PUBLISH','laboratorium' => '1'))->result_array();
												?>
												
												<!-- Berat Jenis Material -->
												
												<tr>
													<td class="text-center" colspan="5"><h4>Berat Jenis Material</h4></td>
												</tr>
                                                <tr>

                                                    <td>1.</td>
                                                    <td>														
                                                        <select id="beton_1" class="form-control form-select2" name="beton_1" required="" >
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
                                                        <select id="kode_1" class="form-control form-select2" name="kode_1" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_1" id="volume_1" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_1" class="form-control form-select2" name="measure_1" required="" >
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
                                                </tr>
												<tr>
                                                    <td>2.</td>
                                                    <td>														
                                                        <select id="semen_1" class="form-control form-select2" name="semen_1" required="" >
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
                                                        <select id="kode_2" class="form-control form-select2" name="kode_2" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_2" id="volume_2" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_2" class="form-control form-select2" name="measure_2" required="" >
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
                                                </tr>
												<tr>
                                                    <td>3.</td>
                                                    <td>														
                                                        <select id="pasir_1" class="form-control form-select2" name="pasir_1" required="" >
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
                                                        <select id="kode_3" class="form-control form-select2" name="kode_3" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_3" id="volume_3" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_3" class="form-control form-select2" name="measure_3" required="" >
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
                                                </tr>
												<tr>
                                                    <td>4.</td>
                                                    <td>														
                                                        <select id="aggregat_kasar_1" class="form-control form-select2" name="aggregat_kasar_1" required="" >
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
                                                        <select id="kode_4" class="form-control form-select2" name="kode_4" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_4" id="volume_4" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_4" class="form-control form-select2" name="measure_4" required="" >
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
                                                </tr>
												<tr>
                                                    <td>5.</td>
                                                    <td>														
                                                        <select id="faktor_kehilangan_1" class="form-control form-select2" name="faktor_kehilangan_1" required="" >
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
                                                        <select id="kode_5" class="form-control form-select2" name="kode_5" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_5" id="volume_5" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_5" class="form-control form-select2" name="measure_5" required="" >
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
                                                </tr>
												
												<!-- End Berat Jenis Material -->
												
												<!-- Berat Jenis Isi -->
												<tr>
													<td class="text-center" colspan="5"><h4>Berat Jenis Isi</h4></td>
												</tr>
												<tr>
                                                    <td>1.</td>
                                                    <td>														
                                                        <select id="pasir_2" class="form-control form-select2" name="pasir_2" required="" >
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
                                                        <select id="kode_6" class="form-control form-select2" name="kode_6" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_6" id="volume_6" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_6" class="form-control form-select2" name="measure_6" required="" >
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
                                                </tr>
												<tr>
                                                    <td>2.</td>
                                                    <td>														
                                                        <select id="aggregat_kasar_2" class="form-control form-select2" name="aggregat_kasar_2" required="" >
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
                                                        <select id="kode_7" class="form-control form-select2" name="kode_7" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_7" id="volume_7" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_7" class="form-control form-select2" name="measure_7" required="" >
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
                                                </tr>
												
												<!-- End Berat Jenis Isi -->
												
												<!-- Material -->
												
												<tr>
													<td class="text-center" colspan="5"><h4>Material</h4></td>
												</tr>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>														
                                                        <select id="semen_2" class="form-control form-select2" name="semen_2" required="" >
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
                                                        <select id="kode_8" class="form-control form-select2" name="kode_8" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_8" id="volume_8" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_8" class="form-control form-select2" name="measure_8" required="" >
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
                                                        <select id="kode_9" class="form-control form-select2" name="kode_9" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_9" id="volume_9" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_9" class="form-control form-select2" name="measure_9" required="" >
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
                                                        <select id="kode_10" class="form-control form-select2" name="kode_10" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_10" id="volume_10" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_10" class="form-control form-select2" name="measure_10" required="" >
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
                                                        <select id="kode_11" class="form-control form-select2" name="kode_11" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_11" id="volume_11" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_11" class="form-control form-select2" name="measure_11" required="" >
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
                                                        <select id="kode_12" class="form-control form-select2" name="kode_12" required="" >
                                                            <option value="">Pilih Kode</option>
                                                            <?php
                                                            if(!empty($kode)){
                                                                foreach ($kode as $kd) {
                                                                    ?>
                                                                    <option value="<?php echo $kd['id'];?>"><?php echo $kd['kode'];?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
													<td>
                                                        <input type="number" step=".01" min="0" name="volume_12" id="volume_12" class="form-control input-sm text-center numberformat" required="" />
                                                    </td>
                                                    <td>
                                                        <select id="measure_12" class="form-control form-select2" name="measure_12" required="" >
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
                                                </tr>
												
												<!-- End Material -->
												
                                            </tbody>
                                        </table>    
                                    </div>
									<br />
                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Keterangan</label>
                                                <textarea class="form-control" name="memo" data-required="false" id="about_text">

                                                </textarea>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]"  multiple="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/laboratorium');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Buat Job Mix Design</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
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
		
		<!-- Berat Jenis Material -->
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#beton_1').prop('selectedIndex', 5).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_1').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_1').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#semen_1').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_2').prop('selectedIndex', 2).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_2').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#pasir_1').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_3').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_3').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#aggregat_kasar_1').prop('selectedIndex', 2).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_4').prop('selectedIndex', 4).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_4').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#faktor_kehilangan_1').prop('selectedIndex', 6).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_5').prop('selectedIndex', 5).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_5').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		
		<!-- End Berat Jenis Material -->
		
		<!-- Berat Isi Material -->
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#pasir_2').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_6').prop('selectedIndex', 0).trigger('change').prop('disabled',true);
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_6').prop('selectedIndex', 0).trigger('change').prop('disabled',true);
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#aggregat_kasar_2').prop('selectedIndex', 2).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_7').prop('selectedIndex', 0).trigger('change').prop('disabled',true);
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_7').prop('selectedIndex', 0).trigger('change').prop('disabled',true);
            }, 1000);
        });
		
		<!-- End Berat Isi Material -->
		
		<!-- Material -->
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#semen_2').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_8').prop('selectedIndex', 6).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_8').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });
		
		$(document).ready(function() {
            setTimeout(function(){
                $('#pasir_3').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_9').prop('selectedIndex', 7).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_9').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#batu_split_12').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_10').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_10').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#batu_split_23').prop('selectedIndex', 4).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_11').prop('selectedIndex', 9).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_11').prop('selectedIndex', 8).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#additon').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#kode_12').prop('selectedIndex', 10).trigger('change');
            }, 1000);
        });
		$(document).ready(function() {
            setTimeout(function(){
                $('#measure_12').prop('selectedIndex', 9).trigger('change');
            }, 1000);
        });
		
		<!-- End Material -->
		
    </script>


</body>
</html>
