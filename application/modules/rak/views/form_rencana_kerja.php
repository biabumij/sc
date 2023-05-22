<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th{
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
                            <li><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Rencana Produksi</li>
                            <li><a>Rencana Kerja</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="">
                                    <h3 class="">Rencana Kerja</h3>
                                    
                                </div>
                            </div>
                            <div class="panel-content">
                                <form method="POST" action="<?php echo site_url('rak/submit_rencana_kerja');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                    <div class="row">
										<div class="col-sm-3">
                                            <label>Tanggal</label>
                                            <input type="text" class="form-control dtpicker" name="tanggal_rencana_kerja"  value="" />
                                        </div>
									</div>
                                    <br />
                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="5%">NO.</th>
                                                    <th width="30%">URAIAN</th>
                                                    <th width="10%">VOLUME</th>
                                                    <th width="15%">HARGA SATUAN</th>
                                                    <th width="10%">SATUAN</th>
                                                    <th width="40%">KOMPOSISI</th>                                  
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1.</td>
                                                    <td>Beton K 125 (10±2)</td>
													<td>
                                                    <input type="text" id="vol_produk_a" name="vol_produk_a" class="form-control numberformat text-right" value="" onchange="changeData(1)"  autocomplete="off">
                                                    </td>
                                                    <td>
                                                    <input type="text" id="price_a" name="price_a" class="form-control rupiahformat text-right" value="896600" readonly=""  autocomplete="off">
                                                    </td>
                                                    <td class="text-center">M3</td>
                                                    <td class="text-center">
                                                        <select id="komposisi_125" name="komposisi_125" class="form-control input-sm">
                                                            <option value="">Pilih Komposisi</option>
                                                            <?php
                                                            if (!empty($komposisi)) {
                                                                foreach ($komposisi as $kom) {
                                                            ?>
                                                                    <option value="<?php echo $kom['id']; ?>"><?php echo $kom['jobs_type']; ?> - (<?= date('d/F/Y',strtotime($kom['date_agregat']));?>)</option>
                                                                <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>	
                                                <tr>
                                                    <td class="text-center">2.</td>
                                                    <td>Beton K 225 (10±2)</td>
													<td>
                                                    <input type="text" id="vol_produk_b" name="vol_produk_b" class="form-control numberformat text-right" value="" onchange="changeData(1)"  autocomplete="off">
                                                    </td>
                                                    <td>
                                                    <input type="text" id="price_b" name="price_b" class="form-control rupiahformat text-right" value="1005000" readonly=""  autocomplete="off">
                                                    </td>
                                                    <td class="text-center">M3</td>
                                                    <td class="text-center">
                                                        <select id="komposisi_225" name="komposisi_225" class="form-control input-sm">
                                                            <option value="">Pilih Komposisi</option>
                                                            <?php
                                                            if (!empty($komposisi)) {
                                                                foreach ($komposisi as $kom) {
                                                            ?>
                                                                    <option value="<?php echo $kom['id']; ?>"><?php echo $kom['jobs_type']; ?> - (<?= date('d/F/Y',strtotime($kom['date_agregat']));?>)</option>
                                                                <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">3.</td>
                                                    <td>Beton K 250 (10±2)</td>
													<td>
                                                    <input type="text" id="vol_produk_c" name="vol_produk_c" class="form-control numberformat text-right" value="" onchange="changeData(1)"  autocomplete="off">
                                                    </td>
                                                    <td>
                                                    <input type="text" id="price_c" name="price_c" class="form-control rupiahformat text-right" value="1179200" readonly=""  autocomplete="off">
                                                    </td>
                                                    <td class="text-center">M3</td>
                                                    <td class="text-center">
                                                        <select id="komposisi_250" name="komposisi_250" class="form-control input-sm">
                                                            <option value="">Pilih Komposisi</option>
                                                            <?php
                                                            if (!empty($komposisi)) {
                                                                foreach ($komposisi as $kom) {
                                                            ?>
                                                                    <option value="<?php echo $kom['id']; ?>"><?php echo $kom['jobs_type']; ?> - (<?= date('d/F/Y',strtotime($kom['date_agregat']));?>)</option>
                                                                <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">4.</td>
                                                    <td>Beton K 250 (18±2)</td>
													<td>
                                                    <input type="text" id="vol_produk_d" name="vol_produk_d" class="form-control numberformat text-right" value="" onchange="changeData(1)"  autocomplete="off">
                                                    </td>
                                                    <td>
                                                    <input type="text" id="price_d" name="price_d" class="form-control rupiahformat text-right" value="1200000"  readonly="" autocomplete="off">
                                                    </td>
                                                    <td class="text-center">M3</td>
                                                    <td class="text-center">
                                                        <select id="komposisi_250_2" name="komposisi_250_2" class="form-control input-sm">
                                                            <option value="">Pilih Komposisi</option>
                                                            <?php
                                                            if (!empty($komposisi)) {
                                                                foreach ($komposisi as $kom) {
                                                            ?>
                                                                    <option value="<?php echo $kom['id']; ?>"><?php echo $kom['jobs_type']; ?> - (<?= date('d/F/Y',strtotime($kom['date_agregat']));?>)</option>
                                                                <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>				
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" class="text-right">GRAND TOTAL</td>
                                                    <td>
                                                    <input type="text" id="sub-total-val" name="sub_total" value="0" class="form-control numberformat tex-left text-right" readonly="">
                                                    </td>
                                                    <td></td>
                                                </tr> 
                                            </tfoot>
                                        </table>    
                                    </div>

                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="5%">NO.</th>
                                                    <th width="15%">KEBUTUHAN BAHAN</th>
                                                    <th width="10%">VOLUME</th>
                                                    <th width="40%">PENAWARAN</th>
                                                    <th width="30%">HARGA SATUAN</th>                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1.</td>
                                                    <td>Semen</td>
                                                    <td></td>
                                                    <td class="text-center"><select id="penawaran_semen" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($semen as $key => $sm) {
                                                            ?>
                                                            <option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-measure="<?php echo $sm['measure'];?>" data-price="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-id_penawaran="<?php echo $sm['id_penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_semen" name="price_semen" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_semen" name="measure_semen" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_semen" name="tax_id_semen" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_semen" name="pajak_id_semen" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_semen" name="supplier_id_semen" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_semen" name="penawaran_id_semen" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">2.</td>
                                                    <td>Pasir</td>
                                                    <td></td>
                                                    <td class="text-center"><select id="penawaran_pasir" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($pasir as $key => $sm) {
                                                            ?>
                                                            <option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-measure="<?php echo $sm['measure'];?>" data-price="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-id_penawaran="<?php echo $sm['id_penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_pasir" name="price_pasir" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_pasir" name="measure_pasir" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_pasir" name="tax_id_pasir" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_pasir" name="pajak_id_pasir" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_pasir" name="supplier_id_pasir" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_pasir" name="penawaran_id_pasir" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">3.</td>
                                                    <td>Batu Split 10-20</td>
                                                    <td></td>
                                                    <td class="text-center"><select id="penawaran_batu1020" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($batu1020 as $key => $sm) {
                                                            ?>
                                                            <option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-measure="<?php echo $sm['measure'];?>" data-price="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-id_penawaran="<?php echo $sm['id_penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_batu1020" name="price_batu1020" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_batu1020" name="measure_batu1020" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_batu1020" name="tax_id_batu1020" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_batu1020" name="pajak_id_batu1020" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_batu1020" name="supplier_id_batu1020" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_batu1020" name="penawaran_id_batu1020" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">4.</td>
                                                    <td>Batu Split 20-30</td>
                                                    <td></td>
                                                    <td class="text-center"><select id="penawaran_batu2030" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($batu2030 as $key => $sm) {
                                                            ?>
                                                            <option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-measure="<?php echo $sm['measure'];?>" data-price="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-id_penawaran="<?php echo $sm['id_penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_batu2030" name="price_batu2030" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_batu2030" name="measure_batu2030" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_batu2030" name="tax_id_batu2030" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_batu2030" name="pajak_id_batu2030" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_batu2030" name="supplier_id_batu2030" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_batu2030" name="penawaran_id_batu2030" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">5.</td>
                                                    <td>BBM Solar</td>
                                                    <td>
                                                        <input type="text" id="vol_bbm_solar" name="vol_bbm_solar" class="form-control numberformat text-right" value="" autocomplete="off">
                                                    </td>
                                                    <td class="text-center"><select id="penawaran_solar" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($solar as $key => $sm) {
                                                            ?>
                                                            <option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-measure="<?php echo $sm['measure'];?>" data-price="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-id_penawaran="<?php echo $sm['id_penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_solar" name="price_solar" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_solar" name="measure_solar" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_solar" name="tax_id_solar" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_solar" name="pajak_id_solar" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_solar" name="supplier_id_solar" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_solar" name="penawaran_id_solar" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                			
                                            </tbody>
                                        </table>    
                                    </div>

                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="5%">NO.</th>
                                                    <th width="15%">KEBUTUHAN ALAT</th>
                                                    <th width="50%">PENAWARAN</th>
                                                    <th width="30%">HARGA SATUAN</th>                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center" rowspan="3">1.</td>
                                                    <td rowspan="3">Batching Plant</td>
                                                    <td class="text-center"><select id="penawaran_bp" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($bp as $key => $bp) {
                                                            ?>
                                                            <option value="<?php echo $bp['penawaran_id'];?>" data-supplier_id="<?php echo $bp['supplier_id'];?>" data-measure="<?php echo $bp['measure'];?>" data-price="<?php echo $bp['price'];?>" data-tax_id="<?php echo $bp['tax_id'];?>" data-tax="<?php echo $bp['tax'];?>" data-pajak_id="<?php echo $bp['pajak_id'];?>" data-pajak="<?php echo $bp['pajak'];?>" data-penawaran_id="<?php echo $bp['penawaran_id'];?>" data-id_penawaran="<?php echo $bp['id_penawaran'];?>"><?php echo $bp['nama'];?> - <?php echo $bp['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_bp" name="price_bp" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_bp" name="measure_bp" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_bp" name="tax_id_bp" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_bp" name="pajak_id_bp" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_bp" name="supplier_id_bp" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_bp" name="penawaran_id_bp" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_bp_2" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($bp_2 as $key => $bp_2) {
                                                            ?>
                                                            <option value="<?php echo $bp_2['penawaran_id'];?>" data-supplier_id="<?php echo $bp_2['supplier_id'];?>" data-measure="<?php echo $bp_2['measure'];?>" data-price="<?php echo $bp_2['price'];?>" data-tax_id="<?php echo $bp_2['tax_id'];?>" data-tax="<?php echo $bp_2['tax'];?>" data-pajak_id="<?php echo $bp_2['pajak_id'];?>" data-pajak="<?php echo $bp_2['pajak'];?>" data-penawaran_id="<?php echo $bp_2['penawaran_id'];?>" data-id_penawaran="<?php echo $bp_2['id_penawaran'];?>"><?php echo $bp_2['nama'];?> - <?php echo $bp_2['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_bp_2" name="price_bp_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_bp_2" name="measure_bp_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_bp_2" name="tax_id_bp_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_bp_2" name="pajak_id_bp_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_bp_2" name="supplier_id_bp_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_bp_2" name="penawaran_id_bp_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_bp_3" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($bp_3 as $key => $bp_3) {
                                                            ?>
                                                            <option value="<?php echo $bp_3['penawaran_id'];?>" data-supplier_id="<?php echo $bp_3['supplier_id'];?>" data-measure="<?php echo $bp_3['measure'];?>" data-price="<?php echo $bp_3['price'];?>" data-tax_id="<?php echo $bp_3['tax_id'];?>" data-tax="<?php echo $bp_3['tax'];?>" data-pajak_id="<?php echo $bp_3['pajak_id'];?>" data-pajak="<?php echo $bp_3['pajak'];?>" data-penawaran_id="<?php echo $bp_3['penawaran_id'];?>" data-id_penawaran="<?php echo $bp_3['id_penawaran'];?>"><?php echo $bp_3['nama'];?> - <?php echo $bp_3['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_bp_3" name="price_bp_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_bp_3" name="measure_bp_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_bp_3" name="tax_id_bp_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_bp_3" name="pajak_id_bp_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_bp_3" name="supplier_id_bp_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_bp_3" name="penawaran_id_bp_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center" rowspan="4">2.</td>
                                                    <td rowspan="4">Truck Mixer</td>
                                                    <td class="text-center"><select id="penawaran_tm" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($tm as $key => $tm) {
                                                            ?>
                                                            <option value="<?php echo $tm['penawaran_id'];?>" data-supplier_id="<?php echo $tm['supplier_id'];?>" data-measure="<?php echo $tm['measure'];?>" data-price="<?php echo $tm['price'];?>" data-tax_id="<?php echo $tm['tax_id'];?>" data-tax="<?php echo $tm['tax'];?>" data-pajak_id="<?php echo $tm['pajak_id'];?>" data-pajak="<?php echo $tm['pajak'];?>" data-penawaran_id="<?php echo $tm['penawaran_id'];?>" data-id_penawaran="<?php echo $tm['id_penawaran'];?>"><?php echo $tm['nama'];?> - <?php echo $tm['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_tm" name="price_tm" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_tm" name="measure_tm" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_tm" name="tax_id_tm" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_tm" name="pajak_id_tm" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_tm" name="supplier_id_tm" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_tm" name="penawaran_id_tm" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_tm_2" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($tm_2 as $key => $tm_2) {
                                                            ?>
                                                            <option value="<?php echo $tm_2['penawaran_id'];?>" data-supplier_id="<?php echo $tm_2['supplier_id'];?>" data-measure="<?php echo $tm_2['measure'];?>" data-price="<?php echo $tm_2['price'];?>" data-tax_id="<?php echo $tm_2['tax_id'];?>" data-tax="<?php echo $tm_2['tax'];?>" data-pajak_id="<?php echo $tm_2['pajak_id'];?>" data-pajak="<?php echo $tm_2['pajak'];?>" data-penawaran_id="<?php echo $tm_2['penawaran_id'];?>" data-id_penawaran="<?php echo $tm_2['id_penawaran'];?>"><?php echo $tm_2['nama'];?> - <?php echo $tm_2['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_tm_2" name="price_tm_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_tm_2" name="measure_tm_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_tm_2" name="tax_id_tm_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_tm_2" name="pajak_id_tm_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_tm_2" name="supplier_id_tm_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_tm_2" name="penawaran_id_tm_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_tm_3" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($tm_3 as $key => $tm_3) {
                                                            ?>
                                                            <option value="<?php echo $tm_3['penawaran_id'];?>" data-supplier_id="<?php echo $tm_3['supplier_id'];?>" data-measure="<?php echo $tm_3['measure'];?>" data-price="<?php echo $tm_3['price'];?>" data-tax_id="<?php echo $tm_3['tax_id'];?>" data-tax="<?php echo $tm_3['tax'];?>" data-pajak_id="<?php echo $tm_3['pajak_id'];?>" data-pajak="<?php echo $tm_3['pajak'];?>" data-penawaran_id="<?php echo $tm_3['penawaran_id'];?>" data-id_penawaran="<?php echo $tm_3['id_penawaran'];?>"><?php echo $tm_3['nama'];?> - <?php echo $tm_3['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_tm_3" name="price_tm_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_tm_3" name="measure_tm_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_tm_3" name="tax_id_tm_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_tm_3" name="pajak_id_tm_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_tm_3" name="supplier_id_tm_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_tm_3" name="penawaran_id_tm_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_tm_4" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($tm_4 as $key => $tm_4) {
                                                            ?>
                                                            <option value="<?php echo $tm_4['penawaran_id'];?>" data-supplier_id="<?php echo $tm_4['supplier_id'];?>" data-measure="<?php echo $tm_4['measure'];?>" data-price="<?php echo $tm_4['price'];?>" data-tax_id="<?php echo $tm_4['tax_id'];?>" data-tax="<?php echo $tm_4['tax'];?>" data-pajak_id="<?php echo $tm_4['pajak_id'];?>" data-pajak="<?php echo $tm_4['pajak'];?>" data-penawaran_id="<?php echo $tm_4['penawaran_id'];?>" data-id_penawaran="<?php echo $tm_4['id_penawaran'];?>"><?php echo $tm_4['nama'];?> - <?php echo $tm_4['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_tm_4" name="price_tm_4" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_tm_4" name="measure_tm_4" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_tm_4" name="tax_id_tm_4" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_tm_4" name="pajak_id_tm_4" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_tm_4" name="supplier_id_tm_4" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_tm_4" name="penawaran_id_tm_4" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center" rowspan="3">3.</td>
                                                    <td rowspan="3">Wheel Loader</td>
                                                    <td class="text-center"><select id="penawaran_wl" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($wl as $key => $wl) {
                                                            ?>
                                                            <option value="<?php echo $wl['penawaran_id'];?>" data-supplier_id="<?php echo $wl['supplier_id'];?>" data-measure="<?php echo $wl['measure'];?>" data-price="<?php echo $wl['price'];?>" data-tax_id="<?php echo $wl['tax_id'];?>" data-tax="<?php echo $wl['tax'];?>" data-pajak_id="<?php echo $wl['pajak_id'];?>" data-pajak="<?php echo $wl['pajak'];?>" data-penawaran_id="<?php echo $wl['penawaran_id'];?>" data-id_penawaran="<?php echo $wl['id_penawaran'];?>"><?php echo $wl['nama'];?> - <?php echo $wl['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_wl" name="price_wl" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_wl" name="measure_wl" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_wl" name="tax_id_wl" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_wl" name="pajak_id_wl" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_wl" name="supplier_id_wl" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_wl" name="penawaran_id_wl" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_wl_2" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($wl_2 as $key => $wl_2) {
                                                            ?>
                                                            <option value="<?php echo $wl_2['penawaran_id'];?>" data-supplier_id="<?php echo $wl_2['supplier_id'];?>" data-measure="<?php echo $wl_2['measure'];?>" data-price="<?php echo $wl_2['price'];?>" data-tax_id="<?php echo $wl_2['tax_id'];?>" data-tax="<?php echo $wl_2['tax'];?>" data-pajak_id="<?php echo $wl_2['pajak_id'];?>" data-pajak="<?php echo $wl_2['pajak'];?>" data-penawaran_id="<?php echo $wl_2['penawaran_id'];?>" data-id_penawaran="<?php echo $wl_2['id_penawaran'];?>"><?php echo $wl_2['nama'];?> - <?php echo $wl_2['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_wl_2" name="price_wl_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_wl_2" name="measure_wl_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_wl_2" name="tax_id_wl_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_wl_2" name="pajak_id_wl_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_wl_2" name="supplier_id_wl_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_wl_2" name="penawaran_id_wl_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_wl_3" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($wl_3 as $key => $wl_3) {
                                                            ?>
                                                            <option value="<?php echo $wl_3['penawaran_id'];?>" data-supplier_id="<?php echo $wl_3['supplier_id'];?>" data-measure="<?php echo $wl_3['measure'];?>" data-price="<?php echo $wl_3['price'];?>" data-tax_id="<?php echo $wl_3['tax_id'];?>" data-tax="<?php echo $wl_3['tax'];?>" data-pajak_id="<?php echo $wl_3['pajak_id'];?>" data-pajak="<?php echo $wl_3['pajak'];?>" data-penawaran_id="<?php echo $wl_3['penawaran_id'];?>" data-id_penawaran="<?php echo $wl_3['id_penawaran'];?>"><?php echo $wl_3['nama'];?> - <?php echo $wl_3['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_wl_3" name="price_wl_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_wl_3" name="measure_wl_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_wl_3" name="tax_id_wl_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_wl_3" name="pajak_id_wl_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_wl_3" name="supplier_id_wl_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_wl_3" name="penawaran_id_wl_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="text-center" rowspan="3">4.</td>
                                                    <td rowspan="3">Transfer Semen</td>
                                                    <td class="text-center"><select id="penawaran_tr" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($tr as $key => $tr) {
                                                            ?>
                                                            <option value="<?php echo $tr['penawaran_id'];?>" data-supplier_id="<?php echo $tr['supplier_id'];?>" data-measure="<?php echo $tr['measure'];?>" data-price="<?php echo $tr['price'];?>" data-tax_id="<?php echo $tr['tax_id'];?>" data-tax="<?php echo $tr['tax'];?>" data-pajak_id="<?php echo $tr['pajak_id'];?>" data-pajak="<?php echo $tr['pajak'];?>" data-penawaran_id="<?php echo $tr['penawaran_id'];?>" data-id_penawaran="<?php echo $tr['id_penawaran'];?>"><?php echo $tr['nama'];?> - <?php echo $tr['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_tr" name="price_tr" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_tr" name="measure_tr" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_tr" name="tax_id_tr" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_tr" name="pajak_id_tr" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_tr" name="supplier_id_tr" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_tr" name="penawaran_id_tr" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_tr_2" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($tr_2 as $key => $tr_2) {
                                                            ?>
                                                            <option value="<?php echo $tr_2['penawaran_id'];?>" data-supplier_id="<?php echo $tr_2['supplier_id'];?>" data-measure="<?php echo $tr_2['measure'];?>" data-price="<?php echo $tr_2['price'];?>" data-tax_id="<?php echo $tr_2['tax_id'];?>" data-tax="<?php echo $tr_2['tax'];?>" data-pajak_id="<?php echo $tr_2['pajak_id'];?>" data-pajak="<?php echo $tr_2['pajak'];?>" data-penawaran_id="<?php echo $tr_2['penawaran_id'];?>" data-id_penawaran="<?php echo $tr_2['id_penawaran'];?>"><?php echo $tr_2['nama'];?> - <?php echo $tr_2['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_tr_2" name="price_tr_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_tr_2" name="measure_tr_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_tr_2" name="tax_id_tr_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_tr_2" name="pajak_id_tr_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_tr_2" name="supplier_id_tr_2" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_tr_2" name="penawaran_id_tr_2" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><select id="penawaran_tr_3" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($tr_3 as $key => $tr_3) {
                                                            ?>
                                                            <option value="<?php echo $tr_3['penawaran_id'];?>" data-supplier_id="<?php echo $tr_3['supplier_id'];?>" data-measure="<?php echo $tr_3['measure'];?>" data-price="<?php echo $tr_3['price'];?>" data-tax_id="<?php echo $tr_3['tax_id'];?>" data-tax="<?php echo $tr_3['tax'];?>" data-pajak_id="<?php echo $tr_3['pajak_id'];?>" data-pajak="<?php echo $tr_3['pajak'];?>" data-penawaran_id="<?php echo $tr_3['penawaran_id'];?>" data-id_penawaran="<?php echo $tr_3['id_penawaran'];?>"><?php echo $tr_3['nama'];?> - <?php echo $tr_3['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_tr_3" name="price_tr_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_tr_3" name="measure_tr_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_tr_3" name="tax_id_tr_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_tr_3" name="pajak_id_tr_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_tr_3" name="supplier_id_tr_3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_tr_3" name="penawaran_id_tr_3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td class="text-center">5.</td>
                                                    <td>Insentif Operator</td>
                                                    <td colspan="2">
                                                        <input type="text" id="insentif" name="insentif" class="form-control rupiahformat text-right" value=""  autocomplete="off">
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td colspan="4"><b>Sewa Alat (SC)</b></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">6.</td>
                                                    <td>Excavator (SC)</td>
                                                    <td class="text-center"><select id="penawaran_exc" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($exc as $key => $exc) {
                                                            ?>
                                                            <option value="<?php echo $exc['penawaran_id'];?>" data-supplier_id="<?php echo $exc['supplier_id'];?>" data-measure="<?php echo $exc['measure'];?>" data-price="<?php echo $exc['price'];?>" data-tax_id="<?php echo $exc['tax_id'];?>" data-tax="<?php echo $exc['tax'];?>" data-pajak_id="<?php echo $exc['pajak_id'];?>" data-pajak="<?php echo $exc['pajak'];?>" data-penawaran_id="<?php echo $exc['penawaran_id'];?>" data-id_penawaran="<?php echo $exc['id_penawaran'];?>"><?php echo $exc['nama'];?> - <?php echo $exc['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_exc" name="price_exc" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_exc" name="measure_exc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_exc" name="tax_id_exc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_exc" name="pajak_id_exc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_exc" name="supplier_id_exc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_exc" name="penawaran_id_exc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">7.</td>
                                                    <td>Dump Truck 4 M3 (SC)</td>
                                                    <td class="text-center"><select id="penawaran_dmp_4m3" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($dmp_4m3 as $key => $dmp_4m3) {
                                                            ?>
                                                            <option value="<?php echo $dmp_4m3['penawaran_id'];?>" data-supplier_id="<?php echo $dmp_4m3['supplier_id'];?>" data-measure="<?php echo $dmp_4m3['measure'];?>" data-price="<?php echo $dmp_4m3['price'];?>" data-tax_id="<?php echo $dmp_4m3['tax_id'];?>" data-tax="<?php echo $dmp_4m3['tax'];?>" data-pajak_id="<?php echo $dmp_4m3['pajak_id'];?>" data-pajak="<?php echo $dmp_4m3['pajak'];?>" data-penawaran_id="<?php echo $dmp_4m3['penawaran_id'];?>" data-id_penawaran="<?php echo $dmp_4m3['id_penawaran'];?>"><?php echo $dmp_4m3['nama'];?> - <?php echo $dmp_4m3['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_dmp_4m3" name="price_dmp_4m3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_dmp_4m3" name="measure_dmp_4m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_dmp_4m3" name="tax_id_dmp_4m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_dmp_4m3" name="pajak_id_dmp_4m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_dmp_4m3" name="supplier_id_dmp_4m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_dmp_4m3" name="penawaran_id_dmp_4m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">8.</td>
                                                    <td>Dump Truck 10 M3 (SC)</td>
                                                    <td class="text-center"><select id="penawaran_dmp_10m3" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($dmp_10m3 as $key => $dmp_10m3) {
                                                            ?>
                                                            <option value="<?php echo $dmp_10m3['penawaran_id'];?>" data-supplier_id="<?php echo $dmp_10m3['supplier_id'];?>" data-measure="<?php echo $dmp_10m3['measure'];?>" data-price="<?php echo $dmp_10m3['price'];?>" data-tax_id="<?php echo $dmp_10m3['tax_id'];?>" data-tax="<?php echo $dmp_10m3['tax'];?>" data-pajak_id="<?php echo $dmp_10m3['pajak_id'];?>" data-pajak="<?php echo $dmp_10m3['pajak'];?>" data-penawaran_id="<?php echo $dmp_10m3['penawaran_id'];?>" data-id_penawaran="<?php echo $dmp_10m3['id_penawaran'];?>"><?php echo $dmp_10m3['nama'];?> - <?php echo $dmp_10m3['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_dmp_10m3" name="price_dmp_10m3" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_dmp_10m3" name="measure_dmp_10m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_dmp_10m3" name="tax_id_dmp_10m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_dmp_10m3" name="pajak_id_dmp_10m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_dmp_10m3" name="supplier_id_dmp_10m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_dmp_10m3" name="penawaran_id_dmp_10m3" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">8.</td>
                                                    <td>Stone Crusher (SC)</td>
                                                    <td class="text-center"><select id="penawaran_sc" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($sc as $key => $sc) {
                                                            ?>
                                                            <option value="<?php echo $sc['penawaran_id'];?>" data-supplier_id="<?php echo $sc['supplier_id'];?>" data-measure="<?php echo $sc['measure'];?>" data-price="<?php echo $sc['price'];?>" data-tax_id="<?php echo $sc['tax_id'];?>" data-tax="<?php echo $sc['tax'];?>" data-pajak_id="<?php echo $sc['pajak_id'];?>" data-pajak="<?php echo $sc['pajak'];?>" data-penawaran_id="<?php echo $sc['penawaran_id'];?>" data-id_penawaran="<?php echo $sc['id_penawaran'];?>"><?php echo $sc['nama'];?> - <?php echo $sc['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_sc" name="price_sc" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_sc" name="measure_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_sc" name="tax_id_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_sc" name="pajak_id_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_sc" name="supplier_id_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_sc" name="penawaran_id_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">9.</td>
                                                    <td>Genset (SC)</td>
                                                    <td class="text-center"><select id="penawaran_gns" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($gns as $key => $gns) {
                                                            ?>
                                                            <option value="<?php echo $gns['penawaran_id'];?>" data-supplier_id="<?php echo $gns['supplier_id'];?>" data-measure="<?php echo $gns['measure'];?>" data-price="<?php echo $gns['price'];?>" data-tax_id="<?php echo $gns['tax_id'];?>" data-tax="<?php echo $gns['tax'];?>" data-pajak_id="<?php echo $gns['pajak_id'];?>" data-pajak="<?php echo $gns['pajak'];?>" data-penawaran_id="<?php echo $gns['penawaran_id'];?>" data-id_penawaran="<?php echo $gns['id_penawaran'];?>"><?php echo $gns['nama'];?> - <?php echo $gns['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_gns" name="price_gns" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_gns" name="measure_gns" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_gns" name="tax_id_gns" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_gns" name="pajak_id_gns" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_gns" name="supplier_id_gns" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_gns" name="penawaran_id_gns" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">10.</td>
                                                    <td>Wheel Loader (SC)</td>
                                                    <td class="text-center"><select id="penawaran_wl_sc" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                        <?php

                                                        foreach ($wl_sc as $key => $wl_sc) {
                                                            ?>
                                                            <option value="<?php echo $wl_sc['penawaran_id'];?>" data-supplier_id="<?php echo $wl_sc['supplier_id'];?>" data-measure="<?php echo $wl_sc['measure'];?>" data-price="<?php echo $wl_sc['price'];?>" data-tax_id="<?php echo $wl_sc['tax_id'];?>" data-tax="<?php echo $wl_sc['tax'];?>" data-pajak_id="<?php echo $wl_sc['pajak_id'];?>" data-pajak="<?php echo $wl_sc['pajak'];?>" data-penawaran_id="<?php echo $wl_sc['penawaran_id'];?>" data-id_penawaran="<?php echo $wl_sc['id_penawaran'];?>"><?php echo $wl_sc['nama'];?> - <?php echo $wl_sc['nomor_penawaran'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="price_wl_sc" name="price_wl_sc" class="form-control rupiahformat text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="measure_wl_sc" name="measure_wl_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="tax_id_wl_sc" name="tax_id_wl_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="pajak_id_wl_sc" name="pajak_id_wl_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="supplier_id_wl_sc" name="supplier_id_wl_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                        <input type="hidden" id="penawaran_id_wl_sc" name="penawaran_id_wl_sc" class="form-control text-right" value=""  readonly="" autocomplete="off">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>    
                                    </div>

                                    <div class="table-responsive">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="5%">NO.</th>
                                                    <th width="45%">URAIAN</th>
                                                    <th width="50%">NILAI</th>                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1.</td>
                                                    <td>BUA</td>
                                                    <td colspan="2">
                                                        <input type="text" id="overhead" name="overhead" class="form-control rupiahformat text-right" value=""  autocomplete="off">
                                                    </td>
                                                </tr>		
                                            </tbody>
                                        </table>    
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Lampiran</label>
                                                <input type="file" class="form-control" name="files[]"  multiple="" />
                                            </div>
                                        </div>
                                    </div>
									<br />
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?= site_url('admin/rencana_kerja');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Kirim</button>
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

        $('input.numberformat').number(true, 2,',','.' );
        $('input.rupiahformat').number(true, 0,',','.' );

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

        function changeData(id)
        {
			var vol_produk_a = $('#vol_produk_a').val();
            var vol_produk_b = $('#vol_produk_b').val();
            var vol_produk_c = $('#vol_produk_c').val();
            var vol_produk_d = $('#vol_produk_d').val();
            				
			vol_produk_a = ( vol_produk_a);
            $('#vol_produk_a').val(vol_produk_a);
            vol_produk_b = ( vol_produk_b);
            $('#vol_produk_b').val(vol_produk_b);
            vol_produk_c = ( vol_produk_c);
            $('#vol_produk_c').val(vol_produk_c);
            vol_produk_d = ( vol_produk_d);
            $('#vol_produk_d').val(vol_produk_d);
            getTotal();
        }

        function getTotal()
        {
            var sub_total = $('#sub-total-val').val();

            sub_total = parseFloat($('#vol_produk_a').val()) + parseFloat($('#vol_produk_b').val()) + parseFloat($('#vol_produk_c').val()) + parseFloat($('#vol_produk_d').val());
            
            $('#sub-total-val').val(sub_total);
            $('#sub-total').text($.number( sub_total, 2,',','.' ));

            total_total = parseFloat(sub_total);
            $('#total-val').val(total_total);
            $('#total').text($.number( total_total, 2,',','.' ));
        }

        $('#penawaran_semen').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_semen').val(penawaran_id);
			var price = $(this).find(':selected').data('price');
            $('#price_semen').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_semen').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_semen').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_semen').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_semen').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_semen').val(id_penawaran);
        });

        $('#penawaran_pasir').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_pasir').val(penawaran_id);
			var price = $(this).find(':selected').data('price');
            $('#price_pasir').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_pasir').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_pasir').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_pasir').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_pasir').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_pasir').val(id_penawaran);
        });

        $('#penawaran_batu1020').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_batu1020').val(penawaran_id);
			var price = $(this).find(':selected').data('price');
            $('#price_batu1020').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_batu1020').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_batu1020').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_batu1020').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_batu1020').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_batu1020').val(id_penawaran);
        });

        $('#penawaran_batu2030').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_batu2030').val(penawaran_id);
			var price = $(this).find(':selected').data('price');
            $('#price_batu2030').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_batu2030').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_batu2030').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_batu2030').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_batu2030').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_batu2030').val(id_penawaran);
        });

        $('#penawaran_solar').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_solar').val(penawaran_id);
			var price = $(this).find(':selected').data('price');
            $('#price_solar').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_solar').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_solar').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_solar').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_solar').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_solar').val(id_penawaran);
        });

        $('#penawaran_bp').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_bp').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_bp').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_bp').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_bp').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_bp').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_bp').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_bp').val(id_penawaran);
        });

        $('#penawaran_bp_2').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_bp_2').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_bp_2').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_bp_2').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_bp_2').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_bp_2').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_bp_2').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_bp_2').val(id_penawaran);
        });

        $('#penawaran_bp_3').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_bp_3').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_bp_3').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_bp_3').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_bp_3').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_bp_3').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_bp_3').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_bp_3').val(id_penawaran);
        });

        $('#penawaran_tm').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_tm').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_tm').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_tm').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_tm').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_tm').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_tm').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_tm').val(id_penawaran);
        });

        $('#penawaran_tm_2').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_tm_2').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_tm_2').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_tm_2').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_tm_2').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_tm_2').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_tm_2').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_tm_2').val(id_penawaran);
        });

        $('#penawaran_tm_3').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_tm_3').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_tm_3').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_tm_3').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_tm_3').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_tm_3').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_tm_3').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_tm_3').val(id_penawaran);
        });

        $('#penawaran_tm_4').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_tm_4').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_tm_4').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_tm_4').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_tm_4').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_tm_4').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_tm_4').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_tm_4').val(id_penawaran);
        });
        

        $('#penawaran_wl').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_wl').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_wl').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_wl').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_wl').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_wl').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_wl').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_wl').val(id_penawaran);
        });

        $('#penawaran_wl_2').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_wl_2').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_wl_2').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_wl_2').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_wl_2').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_wl_2').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_wl_2').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_wl_2').val(id_penawaran);
        });

        $('#penawaran_wl_3').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_wl_3').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_wl_3').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_wl_3').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_wl_3').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_wl_3').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_wl_3').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_wl_3').val(id_penawaran);
        });

        $('#penawaran_tr').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_tr').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_tr').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_tr').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_tr').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_tr').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_tr').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_tr').val(id_penawaran);
        });

        $('#penawaran_tr_2').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_tr_2').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_tr_2').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_tr_2').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_tr_2').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_tr_2').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_tr_2').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_tr_2').val(id_penawaran);
        });

        $('#penawaran_tr_3').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_tr_3').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_tr_3').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_tr_3').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_tr_3').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_tr_3').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_tr_3').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_tr_3').val(id_penawaran);
        });

        $('#penawaran_exc').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_exc').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_exc').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_exc').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_exc').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_exc').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_exc').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_exc').val(id_penawaran);
        });

        $('#penawaran_dmp_4m3').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_dmp_4m3').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_dmp_4m3').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_dmp_4m3').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_dmp_4m3').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_dmp_4m3').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_dmp_4m3').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_dmp_4m3').val(id_penawaran);
        });

        $('#penawaran_dmp_10m3').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_dmp_10m3').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_dmp_10m3').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_dmp_10m3').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_dmp_10m3').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_dmp_10m3').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_dmp_10m3').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_dmp_10m3').val(id_penawaran);
        });

        $('#penawaran_sc').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_sc').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_sc').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_sc').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_sc').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_sc').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_sc').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_sc').val(id_penawaran);
        });

        $('#penawaran_gns').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_gns').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_gns').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_gns').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_gns').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_gns').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_gns').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_gns').val(id_penawaran);
        });

        $('#penawaran_wl_sc').change(function(){
            var penawaran_id = $(this).find(':selected').data('penawaran_id');
            $('#penawaran_wl_sc').val(penawaran_id);
            var price = $(this).find(':selected').data('price');
            $('#price_wl_sc').val(price);
            var supplier_id = $(this).find(':selected').data('supplier_id');
            $('#supplier_id_wl_sc').val(supplier_id);
            var measure = $(this).find(':selected').data('measure');
            $('#measure_wl_sc').val(measure);
            var tax_id = $(this).find(':selected').data('tax_id');
            $('#tax_id_wl_sc').val(tax_id);
            var pajak_id = $(this).find(':selected').data('pajak_id');
            $('#pajak_id_wl_sc').val(pajak_id);
            var id_penawaran = $(this).find(':selected').data('id_penawaran');
            $('#penawaran_id_wl_sc').val(id_penawaran);
        });

        $(document).ready(function() {
            setTimeout(function(){
                $('#komposisi_125').prop('selectedIndex', 1).trigger('change');
            }, 1000);
        });

        $(document).ready(function() {
            setTimeout(function(){
                $('#komposisi_225').prop('selectedIndex', 2).trigger('change');
            }, 1000);
        });

        $(document).ready(function() {
            setTimeout(function(){
                $('#komposisi_250').prop('selectedIndex', 3).trigger('change');
            }, 1000);
        });
        $(document).ready(function() {
            setTimeout(function(){
                $('#komposisi_250_2').prop('selectedIndex', 4).trigger('change');
            }, 1000);
        });

    </script>


</body>
</html>
