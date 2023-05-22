<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
        }
        .table-warning tr, .table-warning th, .table-warning td{
            border: 1px solid white;
            font-weight:bold;
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
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                            <li>
                                <a href="<?php echo site_url('admin/laporan_rencana_kerja');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Pesanan Pembelian</a></li>
                            <li><a>Buat Pesanan Pembelian</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <h3 >Buat Pesanan Pembelian</h3>
                            </div>
                            <div class="panel-content">
                                <div class="table-responsive">
                                    

                                    <?php
                                    $kategori  = $this->db->order_by('nama_kategori_produk', 'asc')->select('*')->get_where('kategori_produk', array('status' => 'PUBLISH'))->result_array();
                                    ?>
                                    <form method="POST" action="<?php echo site_url('pembelian/submit_pesanan_pembelian');?>" id="form-po" enctype="multipart/form-data" autocomplete="off">
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <tbody>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Subyek</label>
                                                        <input type="text" name="subject" class="form-control" required="" autocomplete="off"/>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <div class="col-sm-3">
                                                        <label>Tanggal Pesanan Pembelian</label>
                                                        <input type="date" name="date_po" class="form-control text-left" required="">
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <div class="col-sm-6">
                                                        <label>No. Pesanan Pembelian</label>
                                                        <input type="text" name="no_po" class="form-control text-left" value="<?= $no_po;?>" required="">
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <div class="col-sm-6">
                                                        <label>Kategori</label>
                                                        <select name="kategori_id" class="form-control select2" required="" autocomplete="off">
                                                            <option value="">Pilih Kategori</option>
                                                            <?php
                                                            foreach ($kategori as $key => $kat) {
                                                                ?>
                                                                <option value="<?php echo $kat['id'];?>"><?php echo $kat['nama_kategori_produk'];?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <br />
                                                </div>
                                            </tbody>
                                        </table>
                                        <table class="table-warning table-center" width="50%">
                                            <tr>
                                                <th colspan="5" style="background-color:#404040;color:white;">VOLUME (M3)</th>
                                            </tr>
                                            <tr>
                                                <th width="10%" style="background-color:#d63232; color:white;" rowspan="2">KEBUTUHAN</th>
                                                <th width="10%" style="background-color:#c1e266;" rowspan="2">SISA STOK</th>
                                                <th width="20%" style="background-color:#539ed6; color:white;" colspan="2">PROSES PO</th>
                                                <th width="10%" style="background-color:#cbcbcb;" rowspan="2">SISA KEBUTUHAN</th>
                                            </tr>         
                                            <tr>
                                                <th style="background-color:#539ed6;color:white;">TOTAL PO</th>
                                                <th style="background-color:#539ed6;color:white;">TERIMA PO</th>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <input style="background-color:#d63232; color:white;" class="form-control input-sm text-center" value="<?php echo number_format($kebutuhan,2,',','.');?>" readonly=""/>
                                                </td>
                                                <td class="text-center">
                                                    <input style="background-color:#c1e266;" class="form-control input-sm text-center" value="<?php echo number_format($stock_opname['display_volume'],2,',','.');?>" readonly=""/>
                                                </td>
                                                <td class="text-center">
                                                    <input style="background-color:#539ed6; color:white;" class="form-control input-sm text-center" value="<?php echo number_format($purchase_order,2,',','.');?>" readonly=""/>
                                                </td>
                                                <td class="text-center">
                                                    <input style="background-color:#539ed6; color:white;" class="form-control input-sm text-center" value="<?php echo number_format($total_receipt,2,',','.');?>" readonly=""/>
                                                </td>
                                                <td class="text-center">
                                                    <input style="background-color:#cbcbcb;" class="form-control input-sm text-center" value="<?php echo number_format($kebutuhan - $stock_opname['display_volume'] - $purchase_order,2,',','.');?>" readonly=""/>
                                                </td>
                                            </tr>
                                        </table>
                                        <br />
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <thead>
                                                <tr>
                                                    <th width="5%" rowspan="2">NO.</th>
                                                    <th>PENAWARAN</th>
                                                    <th>VOLUME</th>
                                                    <th>SATUAN</th>
                                                    <th>HARGA</th>
                                                    <th>NILAI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <!--Tanggal PKP :-->
                                                    <input type="hidden" name="date_pkp" class="form-control input-sm text-left" value="2021-02-10"/>
                                                    <!--Request No :-->
                                                    <input type="hidden" name="request_no" class="form-control input-sm text-left" value="<?= $request_no;?>"/>
                                                    <!--Supplier ID :-->
                                                    <input type="hidden" name="supplier_id" id="supplier_id" class="form-control input-sm text-left" value=""/>
                                                    <!--Material ID :-->
                                                    <input type="hidden" name="produk" id="material_id" class="form-control input-sm text-left" value=""/>
                                                    <!--Measure ID :-->
                                                    <input type="hidden" name="measure_id" id="measure_id" class="form-control input-sm text-left" value=""/>
                                                    <!--Tax ID :-->
                                                    <input type="hidden" name="tax_id" id="tax_id" class="form-control input-sm text-left" value=""/>
                                                    <!--Pajak ID :-->
                                                    <input type="hidden" name="pajak_id"  id="pajak_id" class="form-control input-sm text-left" value=""/>
                                                    <!--Tax :-->
                                                    <input type="hidden" name="tax" id="tax" class="form-control input-sm text-left" value=""/>
                                                    <!--Pajak :-->
                                                    <input type="hidden" name="pajak" id="pajak" class="form-control input-sm text-left" value=""/>
                                                    <!--Total Tax :-->
                                                    <input type="hidden" name="total_tax" id="total_tax" class="form-control input-sm text-left" value=""/>
                                                    <!--Total Pajak :-->
                                                    <input type="hidden" name="total_pajak" id="total_pajak" class="form-control input-sm text-left" value=""/>
                                                    
                                                    <br />
                                                    <br />

                                                    <td class="text-center">1.</td>
                                                    <td class="text-left">
                                                        <select id="penawaran_id" class="form-control">
                                                        <option value="">Pilih Penawaran</option>
                                                            <?php

                                                            foreach ($produk as $key => $sm) {
                                                                ?>
                                                                <option value="<?php echo $sm['penawaran_id'];?>" data-supplier_id="<?php echo $sm['supplier_id'];?>" data-material_id="<?php echo $sm['material_id'];?>" data-measure_id="<?php echo $sm['measure'];?>" data-measure="<?php echo $sm['measure_name'];?>" data-harsat="<?php echo $sm['price'];?>" data-tax_id="<?php echo $sm['tax_id'];?>" data-tax="<?php echo $sm['tax'];?>" data-pajak_id="<?php echo $sm['pajak_id'];?>" data-pajak="<?php echo $sm['pajak'];?>" data-penawaran_id="<?php echo $sm['penawaran_id'];?>" data-penawaran="<?php echo $sm['penawaran'];?>"><?php echo $sm['nama'];?> - <?php echo $sm['nomor_penawaran'];?> - <?php echo $sm['material_name'];?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input name="volume" id="volume" onchange="changeData(1)" class="form-control numberformat text-center" value=""/>
                                                    </td>
                                                    <td class="text-center">
                                                        <input name="measure" id="measure" class="form-control input-sm text-center" value="" readonly=""/>
                                                    </td>
                                                    <td class="text-center">
                                                        <input name="harsat" id="harsat" onchange="changeData(1)" class="form-control rupiahformat input-sm text-center" value="" readonly=""/>
                                                    </td>
                                                    <td class="text-center">
                                                        <input name="nilai" id="nilai" class="form-control rupiahformat input-sm text-center" value="" readonly=""/>
                                                    </td>
                                                        <input type="hidden" name="penawaran_pembelian_id" id="penawaran" class="form-control rupiahformat input-sm text-center" value="" readonly=""/>
                                                    
                                            <div>
                                            </tbody>
                                        </table>
                                        <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                            <tbody>
                                                <div class="col-sm-8">
                                                    <label>Memo</label>
                                                    <textarea id="about_text" name="memo" class="form-control" data-required="false" rows="20">
<p style="font-size:6;"><b>Syarat &amp; Ketentuan :</b></p>
<p style="font-size:6;">1.&nbsp;Waktu Penyerahan : 2 Februari 2023 s/d 8 Februari 2023</p>
<p style="font-size:6;">2.&nbsp;Tempat Penyerahan : Proyek Bendungan Temef, Desa Konbaki, Kecamatan Polen, KAB. TTS</p>
<p style="font-size:6;">3.&nbsp;Cara Pembayaran : 30 (tiga puluh) hari kerja setelah berkas tagihan dinyatakan lolos verifikasi keuangan PT. Bia Bumi Jayendra, dengan melampirkan</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp; dokumen sebagai berikut :</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;3.1 Tagihan</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;3.2 Kwitansi</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;3.3 BAP (Berita Acara Pembayaran)</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;3.4 BAST (Berita Acara Serah Terima) &amp; rekap surat jalan yang ditandatangani oleh pihak pemberi order dan penerima order</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;3.5 Surat Jalan Asli (Nomor PO harus tercantum pada setiap surat jalan)</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;3.6 PO</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;3.7 Faktur Pajak</p>
<p style="font-size:6;">4. Lain-lain :</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;4.1 Barang harus dalam kondisi 100% baik</p>
<p style="font-size:6;">&nbsp;&nbsp;&nbsp;4.2 Barang dikembalikan apabila tidak sesuai dengan pesanan</p>
                                                    </textarea>
                                                </div>
                                                </div>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <a href="<?php echo site_url('admin/pembelian');?>" class="btn btn-danger" style="margin-bottom:0;"><i class="fa fa-close"></i> Batal</a>
                                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i>  Kirim</button>
                                        </div>
                                        <br />
                                        <br />
                                        <br />
                                    </div>
                                    </form>
                                </div>
                                
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
        
    $('input.numberformat').number(true, 2,',','.' );
    $('input.rupiahformat').number(true, 0,',','.' );

    $(document).ready(function(){
        $('#penawaran_id').val(<?= $row['id'];?>).trigger('change');
    });
        
    tinymce.init({
    selector: 'textarea#about_text',
    height: 200,
    menubar: false,
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

            var volume = $('#volume').val();
            var harsat = $('#harsat').val();
            var nilai = $('#nilai').val();
            var tax = $('#tax').val();

            volume = (volume);
            $('#nilai').text($.number(volume, 2,',','.' ));
            harsat = (harsat);
            $('#harsat').val(harsat);
            nilai = (volume * harsat);
            $('#nilai').val(nilai);
            $('#nilai').text($.number(nilai, 0,',','.' ));

            tax = (tax);
            $('#tax').text($.number(tax, 2,',','.' ));
            total_tax = (tax * volume);
            $('#total_tax').val(total_tax);
            $('#total_tax').text($.number(total_tax, 0,',','.' ));

            pajak = (pajak);
            $('#pajak').text($.number(pajak, 2,',','.' ));
            total_pajak = (pajak * volume);
            $('#total_pajak').val(total_pajak);
            $('#total_pajak').text($.number(total_pajak, 0,',','.' ));

        }
    function getTotal()
    {
        var nilai = $('nilai').val();

        nilai = parseFloat($('#volume').val()) * parseFloat($('#harsat').val());
        
        $('#nilai').val(nilai);
        $('#nilai').text($.number( nilai, 0,',','.' ));

        nilai = parseFloat(nilai);
        $('#nilai').val(nilai);
        $('#nilai').text($.number( nilai, 0,',','.' ));
    }

    $('#penawaran_id').change(function(){
        var penawaran_id = $(this).find(':selected').data('penawaran_id');
        $('#penawaran_id').val(penawaran_id);

        var supplier_id = $(this).find(':selected').data('supplier_id');
        $('#supplier_id').val(supplier_id);

        var material_id = $(this).find(':selected').data('material_id');
        $('#material_id').val(material_id);

        var measure_id = $(this).find(':selected').data('measure_id');
        $('#measure_id').val(measure_id);

        var measure = $(this).find(':selected').data('measure');
        $('#measure').val(measure);

        var harsat = $(this).find(':selected').data('harsat');
        $('#harsat').val(harsat);

        var tax_id = $(this).find(':selected').data('tax_id');
        $('#tax_id').val(tax_id);

        var tax = $(this).find(':selected').data('tax');
        $('#tax').val(tax);

        var pajak_id = $(this).find(':selected').data('pajak_id');
        $('#pajak_id').val(pajak_id);

        var pajak = $(this).find(':selected').data('pajak');
        $('#pajak').val(pajak);

        var penawaran = $(this).find(':selected').data('penawaran');
        $('#penawaran').val(penawaran);
    });
    </script>
    
</body>
</html>
