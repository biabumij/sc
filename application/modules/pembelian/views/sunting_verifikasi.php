<!doctype html>
<html lang="en" class="fixed">

<head>
    <?php echo $this->Templates->Header(); ?>

    <style type="text/css">
        .table-center th,
        .table-center td {
            text-align: center;
        }

        .form-approval {
            display: inline-block;
        }

        .custom-file-input {
            width: 0;
            height: 0;
            visibility: hidden !important;
        }
        
        blink {
        -webkit-animation: 2s linear infinite kedip; /* for Safari 4.0 - 8.0 */
        animation: 2s linear infinite kedip;
        }
        /* for Safari 4.0 - 8.0 */
        @-webkit-keyframes kedip { 
        0% {
            visibility: hidden;
        }
        50% {
            visibility: hidden;
        }
        100% {
            visibility: visible;
        }
        }
        @keyframes kedip {
        0% {
            visibility: hidden;
        }
        50% {
            visibility: hidden;
        }
        100% {
            visibility: visible;
        }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <?php echo $this->Templates->PageHeader();?>
        <div class="page-body">
            <?php echo $this->Templates->LeftBar();?>
            <div class="content">
                <div class="content-header">
                    <div class="leftside-content-header">
                        <ul class="breadcrumbs">
                            <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin'); ?>">Dashboard</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Pembelian</a></li>
                            <li><a href="<?php echo site_url('admin/pembelian');?>"> Verifikasi Tagihan Pembelian</a></li>
                            <li><a href="">Edit Verifikasi Tagihan Pembelian</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="">
                                    <h3 class="">Edit Verifikasi Tagihan Pembelian</h3>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered table-center" id="main-table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Rekanan</th>
                                                <th>Tanggal Lolos Verifikasi</th>
                                                <th>Nomor Invoice</th>
                                                <th>Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                        </tbody>
                                        
                                    </table>
                                </div>
                                <br /><br />
                                <div class="text-center">
                                    <a href="<?= site_url('pembelian/penagihan_pembelian_detail/'.$row['penagihan_pembelian_id']);?>" class="btn btn-info" style="width:15%; font-weight:bold; border-radius:10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalFormMain" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Form Edit Verifikasi</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" style="padding: 0 10px 0 20px;" >
                        <input type="hidden" id="id" value="<?= $row['id'] ?>">
                        <input type="hidden" id="penagihan_id" name="penagihan_id" class="form-control" required="" autocomplete="off" />
                        <div class="form-group">
                            <label>Nama Rekanan</label>
                            <input type="text" id="nama" name="nama" class="form-control" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Nomor Kontrak / PO</label>
                            <input type="text" id="nomor_po" name="nomor_po" class="form-control" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Nama Barang / Jasa</label>
                            <input type="text" id="nama_barang_jasa" name="nama_barang_jasa" class="form-control" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Nilai Kontrak / PO</label>
                            <input type="text" id="nilai_kontrak" name="nilai_kontrak" class="form-control numberformat" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Nilai Tagihan ini (DPP)</label>
                            <input type="text" id="nilai_tagihan" name="nilai_tagihan" class="form-control numberformat" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>PPN</label>
                            <input type="text" id="ppn" name="ppn" class="form-control numberformat" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>PPh 23</label>
                            <input type="text" id="pph" name="pph" class="form-control numberformat" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Total Tagihan</label>
                            <input type="text" id="total_tagihan" name="total_tagihan" class="form-control numberformat" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Invoice</label>
                            <input type="text" id="tanggal_invoice" name="tanggal_invoice" class="form-control dtpicker-single" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Diterima Proyek</label>
                            <input type="text" id="tanggal_diterima_proyek" name="tanggal_diterima_proyek" value="<?= (isset($row['tanggal_diterima_proyek'])) ? $row['tanggal_diterima_proyek'] : '' ;?>" class="form-control dtpicker-single" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lolos Verifikasi</label>
                            <input type="text" id="tanggal_lolos_verifikasi" name="tanggal_lolos_verifikasi"  value="<?= (isset($row['tanggal_lolos_verifikasi'])) ? $row['tanggal_lolos_verifikasi'] : '' ;?>" class="form-control dtpicker-single" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Diterima Pusat</label>
                            <input type="text" id="tanggal_diterima_office" name="tanggal_diterima_office" class="form-control dtpicker-single" readonly="" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <input type="text" id="metode_pembayaran" name="metode_pembayaran" class="form-control" readonly="" autocomplete="off" />
                        </div>
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th width="50%" class="text-center">Nama Dokumen</th>
                                    <th width="50%" class="text-center">Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Invoice</td>
                                    <td class="text-center" id="lampiran_invoice"></td>
                                </tr>
                                <tr>
                                    <td>Kwitansi</td>
                                    <td class="text-center" id="lampiran_kwitansi"></td>
                                </tr>
                                <tr>
                                    <td>Faktur Pajak</td>
                                    <td class="text-center" id="lampiran_faktur"></td>
                                </tr>
                                <tr>
                                    <td>Berita Acara Pembayaran (BAP)</td>
                                    <td class="text-center" id="lampiran_bap"></td>
                                </tr>
                                <tr>
                                    <td>Berita Acara Serah Terima (BAST)</td>
                                    <td class="text-center" id="lampiran_bast"></td>
                                </tr>
                                <tr>
                                    <td>Surat Jalan</td>
                                    <td class="text-center" id="lampiran_surat_jalan"></td>
                                </tr>
                                <tr>
                                    <td>Copy Kontrak/ PO</td>
                                    <td class="text-center" id="lampiran_copy_po"></td>
                                </tr>
                            <tbody>
                        </table> 
                       
            
                        <br /><br />
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center" style="vertical-align:middle;">A.</th>
                                    <th class="text-center" style="vertical-align:middle;">KELENGKAPAN DATA (Lengkap dan Benar)</th>
                                    <th class="text-center" style="vertical-align:middle;">ADA / TIDAK</th>
                                    <th width="50%" class="text-center" style="vertical-align:middle;">KETERANGAN</th>
                                    <th class="text-center" style="vertical-align:middle;">GANTI DOKUMEN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Invoice</td>
                                    <td class="text-center"><input type="checkbox" name="invoice" id="invoice" value="1"></td>
                                    <td><input type="text" name="invoice_keterangan" id="invoice_keterangan" class="form-control input-sm"></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" class="form-control" name="invoice_file" id="invoice_file">
                                            <input type="file" class="custom-file-input" data-target="invoice_file">
                                            <button type="button" class="btn btn-primary btn-block custom-file-select"><span class="fa fa-upload"></span></button>
                                            <button type="button" class="btn btn-danger btn-block custom-file-remove" style="display:none"><span class="fa fa-times"></span></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Kwitansi</td>
                                    <td class="text-center"><input type="checkbox" name="kwitansi" id="kwitansi" value="1"></td>
                                    <td><input type="text" name="kwitansi_keterangan" id="kwitansi_keterangan" class="form-control input-sm"></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" class="form-control" name="kwitansi_file" id="kwitansi_file">
                                            <input type="file" class="custom-file-input" data-target="kwitansi_file">
                                            <button type="button" class="btn btn-primary btn-block custom-file-select"><span class="fa fa-upload"></span></button>
                                            <button type="button" class="btn btn-danger btn-block custom-file-remove" style="display:none"><span class="fa fa-times"></span></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>Faktur Pajak</td>
                                    <td class="text-center"><input type="checkbox" name="faktur" id="faktur" value="1"></td>
                                    <td><input type="text" name="faktur_keterangan" id="faktur_keterangan" class="form-control input-sm"></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" class="form-control" name="faktur_file" id="faktur_file">
                                            <input type="file" class="custom-file-input" data-target="faktur_file">
                                            <button type="button" class="btn btn-primary btn-block custom-file-select"><span class="fa fa-upload"></span></button>
                                            <button type="button" class="btn btn-danger btn-block custom-file-remove" style="display:none"><span class="fa fa-times"></span></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td>Berita Acara Pembayaran (BAP)</td>
                                    <td class="text-center"><input type="checkbox" name="bap" id="bap" value="1"></td>
                                    <td><input type="text" name="bap_keterangan" id="bap_keterangan" class="form-control input-sm"></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" class="form-control" name="bap_file" id="bap_file">
                                            <input type="file" class="custom-file-input" data-target="bap_file">
                                            <button type="button" class="btn btn-primary btn-block custom-file-select"><span class="fa fa-upload"></span></button>
                                            <button type="button" class="btn btn-danger btn-block custom-file-remove" style="display:none"><span class="fa fa-times"></span></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5.</td>
                                    <td>Berita Acara Serah Terima (BAST)</td>
                                    <td class="text-center"><input type="checkbox" name="bast" id="bast" value="1"></td>
                                    <td><input type="text" name="bast_keterangan" id="bast_keterangan" class="form-control input-sm"></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" class="form-control" name="bast_file" id="bast_file">
                                            <input type="file" class="custom-file-input" data-target="bast_file">
                                            <button type="button" class="btn btn-primary btn-block custom-file-select"><span class="fa fa-upload"></span></button>
                                            <button type="button" class="btn btn-danger btn-block custom-file-remove" style="display:none"><span class="fa fa-times"></span></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6.</td>
                                    <td>Surat Jalan</td>
                                    <td class="text-center"><input type="checkbox" name="surat_jalan" id="surat_jalan" value="1"></td>
                                    <td><input type="text" name="surat_jalan_keterangan" id="surat_jalan_keterangan" class="form-control input-sm"></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" class="form-control" name="surat_jalan_file" id="surat_jalan_file">
                                            <input type="file" class="custom-file-input" data-target="surat_jalan_file">
                                            <button type="button" class="btn btn-primary btn-block custom-file-select"><span class="fa fa-upload"></span></button>
                                            <button type="button" class="btn btn-danger btn-block custom-file-remove" style="display:none"><span class="fa fa-times"></span></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7.</td>
                                    <td>Copy PO</td>
                                    <td class="text-center"><input type="checkbox" name="copy_po" id="copy_po" value="1"></td>
                                    <td><input type="text" name="copy_po_keterangan" id="copy_po_keterangan" class="form-control input-sm"></td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="hidden" class="form-control" name="copy_po_file" id="copy_po_file">
                                            <input type="file" class="custom-file-input" data-target="copy_po_file">
                                            <button type="button" class="btn btn-primary btn-block custom-file-select"><span class="fa fa-upload"></span></button>
                                            <button type="button" class="btn btn-danger btn-block custom-file-remove" style="display:none"><span class="fa fa-times"></span></button>
                                        </div>
                                    </td>
                                </tr>
                            <tbody>
                        </table>    
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="btn-form" style="font-weight:bold; border-radius:10px;"><i class="fa fa-send"></i> Update Verifikasi</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-weight:bold; border-radius:10px;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var form_control = '';
    </script>
    <?php echo $this->Templates->Footer(); ?>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">

    <script type="text/javascript">
    $('input.numberformat').number(true, 0, ',', '.');
    $('.dtpicker-single').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });
    $('.dtpicker-single').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY'));
    });

    var table = $('#main-table').DataTable( {
        ajax: {
            processing: true,
            serverSide: true,
            url: '<?php echo site_url('pembelian/main_table_verifikasi');?>',
            type : 'POST',
            data: function ( d ) {
                d.id = $('#id').val();
            }
        },
        columns: [
            { "data": "nama" },
            { "data": "tanggal_lolos_verifikasi" },
            { "data": "nomor_invoice" },
            { "data": "actions" }
        ],
        responsive: true,
        searching: false,
        lengthChange: false,
        "columnDefs": [
            {
                "targets": [0],
                "className": 'text-center',
            }
        ],
    });

    function OpenFormMain(id='')
        {   
            
            $('#modalFormMain').modal('show');
            $('#id').val('');
            // table_detail.ajax.reload();
            if(id !== ''){
                $('#id').val(id);
                getDataMain(id);
            }
        }

        function getDataMain(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pembelian/get_verifikasi_main'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.output){
                        $('#penagihan_id').val(result.output.id).trigger('change');
                        $('#nama').val(result.output.nama);
                        $('#nomor_po').val(result.output.nomor_po);
                        $('#nama_barang_jasa').val(result.output.nama_barang_jasa);
                        $('#nilai_kontrak').val(result.output.nilai_kontrak);
                        $('#nilai_tagihan').val(result.output.nilai_tagihan);
                        $('#ppn').val(result.output.ppn);
                        $('#pph').val(result.output.pph);
                        $('#total_tagihan').val(result.output.total_tagihan);
                        $('#tanggal_invoice').val(result.output.tanggal_invoice);
                        $('#tanggal_diterima_proyek').val(result.output.tanggal_diterima_proyek);
                        $('#tanggal_lolos_verifikasi').val(result.output.tanggal_lolos_verifikasi);
                        $('#metode_pembayaran').val(result.output.metode_pembayaran);

                        $('#invoice_keterangan').val(result.output.invoice_keterangan);
                        $('#kwitansi_keterangan').val(result.output.kwitansi_keterangan);
                        $('#faktur_keterangan').val(result.output.faktur_keterangan);
                        $('#bap_keterangan').val(result.output.bap_keterangan);
                        $('#bast_keterangan').val(result.output.bast_keterangan);
                        $('#surat_jalan_keterangan').val(result.output.surat_jalan_keterangan);
                        $('#copy_po_keterangan').val(result.output.copy_po_keterangan);

                        if (result.output.invoice == 1) {
                            $("#invoice").prop("checked", true);
                        } else {
                            $("#invoice").prop("checked", false);
                        }

                        if (result.output.kwitansi == 1) {
                            $("#kwitansi").prop("checked", true);
                        } else {
                            $("#kwitansi").prop("checked", false);
                        }

                        if (result.output.faktur == 1) {
                            $("#faktur").prop("checked", true);
                        } else {
                            $("#faktur").prop("checked", false);
                        }

                        if (result.output.bap == 1) {
                            $("#bap").prop("checked", true);
                        } else {
                            $("#bap").prop("checked", false);
                        }

                        if (result.output.bast == 1) {
                            $("#bast").prop("checked", true);
                        } else {
                            $("#bast").prop("checked", false);
                        }

                        if (result.output.surat_jalan == 1) {
                            $("#surat_jalan").prop("checked", true);
                        } else {
                            $("#surat_jalan").prop("checked", false);
                        }

                        if (result.output.copy_po == 1) {
                            $("#copy_po").prop("checked", true);
                        } else {
                            $("#copy_po").prop("checked", false);
                        }

                        if (result.output.invoice_file) {
                            $('#lampiran_invoice').html('<a target="_blank" href="/' + result.output.invoice_file + '"><span class="fa fa-download"></span> Download</a>');
                        }

                        if (result.output.kwitansi_file) {
                            $('#lampiran_kwitansi').html('<a target="_blank" href="/' + result.output.kwitansi_file + '"><span class="fa fa-download"></span> Download</a>');
                        }

                        if (result.output.faktur_file) {
                            $('#lampiran_faktur').html('<a target="_blank" href="/' + result.output.faktur_file + '"><span class="fa fa-download"></span> Download</a>');
                        }

                        if (result.output.bap_file) {
                            $('#lampiran_bap').html('<a target="_blank" href="/' + result.output.bap_file + '"><span class="fa fa-download"></span> Download</a>');
                        }

                        if (result.output.bast_file) {
                            $('#lampiran_bast').html('<a target="_blank" href="/' + result.output.bast_file + '"><span class="fa fa-download"></span> Download</a>');
                        }

                        if (result.output.surat_jalan_file) {
                            $('#lampiran_surat_jalan').html('<a target="_blank" href="/' + result.output.surat_jalan_file + '"><span class="fa fa-download"></span> Download</a>');
                        }

                        if (result.output.copy_po_file) {
                            $('#lampiran_copy_po').html('<a target="_blank" href="/' + result.output.copy_po_file + '"><span class="fa fa-download"></span> Download</a>');
                        }
                        
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        $('#modalFormMain form').submit(function(event){
            $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pembelian/update_verifikasi_main'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $("#modalFormMain form").trigger("reset");
                        $('#modalFormMain').modal('hide');
                        window.location.reload('');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });

        $(document).ready(function(e) {
        $('.custom-file-select').click(function(e) {
            $(this).closest('.custom-file').find('input[type="file"]').click();
        });

        $('.custom-file-input').change(function(e) {

            let target = $(this).data('target');
            let files = this.files;

            const reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onload = function() {
                let temp = reader.result.split('base64,');
                let param = files[0].name + '|' + temp[temp.length - 1];
                $('#' + target).val(param);
                $('#' + target).closest('.custom-file').find('.custom-file-select').hide();
                $('#' + target).closest('.custom-file').find('.custom-file-remove').show();
            };

            reader.onerror = error => console.error(error);
        });

        $('.custom-file-remove').click(function(e) {
            $(this).closest('.custom-file').find('input[type="hidden"]').val('');
            $(this).hide();
            $(this).closest('.custom-file').find('.custom-file-select').show();
        });
    });

    </script>


</body>

</html>