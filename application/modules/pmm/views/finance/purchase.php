<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
    <style type="text/css">
        .tab-pane {
            padding-top: 10px;
        }
        
    </style>
</head>
    <style type="text/css">
        #table-receipt_wrappertable.dataTable tbody>tr.selected, #table-receipt_wrapper table.dataTable tbody>tr>.selected {
            background-color: #c3c3c3;
        }
        #form-verif-dok label{
            font-size: 12px;
            text-align: left;
        }
        #form-verif-dok hr {
            margin: 5px 0px;
    margin-bottom: 10px;
    border-top: 1px solid #9c9c9c;
        }
    </style>
<body>
<div class="wrap">
    
    <?php echo $this->Templates->PageHeader();?>
    

    <div class="page-body">
        <?php echo $this->Templates->LeftBar();?>
        <div class="content">
            <div class="content-header">
                <div class="leftside-content-header">
                    <ul class="breadcrumbs">
                        <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>">Dashboard</a></li>
                        <li><a >Pembelian</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Pembelian</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i> Pembelian Baru <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url('pmm/pembelian/penawaran_pembelian'); ?>">Penawaran Pembelian</a></li>
                                        <li><a href="<?php echo site_url('pmm/finance/sales_po'); ?>">Purchase Pembelian</a></li>
                                        <li><a href="<?php echo base_url('pmm/pembelian/tagihan_pembelian') ?>">Tagihan Pembelian</a></li>
                                      </ul>
                                </div>
                                <form method="GET" target="_blank" action="<?php echo site_url('pmm/reports/client_print');?>">
                                    <!-- <div class="col-sm-2">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                                    </div>   -->
                                </form>
                            </div>

                            <?php
                                    $sales_po = $this->db->select('id,contract_number')->get_where('pmm_sales_po')->result_array();
                                    $suppliers= $this->db->order_by('name','asc')->get_where('pmm_supplier',array('status'=>'PUBLISH'))->result_array();
                                ?>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Penawaran Pembelian</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Pesanan Pembelian</a></li>
                                <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Pengiriman Pembelian</a></li>
                                <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Tagihan Pembelian</a></li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="home">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="guest-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Nomor</th>
                                                    <th>Suppiler</th>
                                                    <th>Jenis</th>
                                                    <th>Berlaku Hingga</th>
                                                    <th>Status</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="profile">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-po">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Supplier</th>
                                                    <th>No PO</th>
                                                    <th>Subject</th>
                                                    <th>Tanggal</th>
                                                    <th>Vol PO</th>
                                                    <th>Receipt</th>
                                                    <th>Total PO</th>
                                                    <th>Total Receipt</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="messages">
                                    
                                    <div class="row">
                                       <div class="col-sm-3">
                                            <input type="text" id="filter_date" name="filter_date" class="form-control dtpicker input-sm" value="" placeholder="Filter by Date" autocomplete="off">
                                        </div>
                                        <div class="col-sm-3">
                                            <select id="filter_supplier_id" name="supplier_id" class="form-control select2">
                                                <option value="">.. Select Supplier ..</option>
                                                <?php
                                                foreach ($suppliers as $key => $supplier) {
                                                    ?>
                                                    <option value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select id="filter_po_id" class="form-control select2" name="sales_po_id">
                                                <option value="">.. Select PO ..</option>
                                                
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="text-right">
                                                <input type="hidden" id="val-receipt-id" name="">
                                                <button type="button" id="btn_production" class="btn btn-success">Penagihan Pembelian</button>
                                            </div>
                                        </div>
                                    </div> 
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center table-bordered table-condensed" id="table-receipt" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Supplier</th>
                                                    <th>Bahan</th>
                                                    <th>Satuan</th>
                                                    <th>No PO</th>
                                                    <th>No Surat Jalan</th>
                                                    <th>No Kendaraan</th>
                                                    <th>Nama Supir</th>
                                                    <th>File</th>
                                                    <th>Volume</th>
                                                    <th>Biaya</th>
                                                    <th>Cost Convert Val</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                            <tfoot>
                                                <th></th>
                                                <th colspan="10" style="font-weight:bold;text-align:right !important;">TOTAL : </th>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th></th>
                                                <th></th>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="settings">
                                    <div class="row">
                                       <div class="col-sm-3">
                                            <input type="text" id="filter_date" name="filter_date" class="form-control dtpicker input-sm" value="" placeholder="Filter by date" autocomplete="off">
                                        </div>
                                        <div class="col-sm-3">
                                            
                                        </div>
                                        <div class="col-sm-6">
                                            
                                        </div>
                                    </div> 
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-center" id="table-tagihan">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Nomor</th>
                                                    <th>Supplier</th>
                                                    <th>Jenis</th>
                                                    <th>Tgl Jatuh Tempo</th>
                                                    <th>Total</th>
                                                    <th>Pembayaran</th>
                                                    <th>Sisa Tagihan</th>
                                                    <th>Status</th>
                                                    <th>Verifikasi Dok</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
</div>
    

    <div class="modal fade bd-example-modal-lg" id="modalForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Verifikasi Dokumen</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-verif-dok" class="form-horizontal" action="<?= site_url('pmm/pembelian/verif_dok_penagihan_pembelian');?>" >
                        <input type="hidden" name="id" id="penagihan_pembelian_id">
                        <div>DIISI OLEH VERIFIKATOR :</div>
                        <hr />
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nama Rekanan</label>
                            <div class="col-sm-8">
                              <input type="text" id="supplier_name" name="supplier_name" class="form-control input-sm" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nomor Kontrak / PO</label>
                            <div class="col-sm-5">
                              <input type="text" id="no_po" name="nomor_po" class="form-control input-sm" >
                            </div>
                            <div class="col-sm-3">
                              <input type="text" id="tanggal_po" name="tanggal_po" class="form-control input-sm dtpicker-single" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nama Barang/Jasa</label>
                            <div class="col-sm-8">
                              <input type="text" id="jenis" name="nama_barang_jasa" class="form-control input-sm" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nilai Kontrak / PO</label>
                            <div class="col-sm-8">
                              <input type="text" id="nilai_kontrak" name="nilai_kontrak" class="form-control input-sm numberformat" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nilai Tagihan (Net)</label>
                            <div class="col-sm-8">
                              <input type="text" id="nilai_tagihan" name="nilai_tagihan" class="form-control input-sm numberformat" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">PPN</label>
                            <div class="col-sm-8">
                              <input type="text" id="ppn_tagihan" name="ppn" class="form-control input-sm numberformat" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Invoice</label>
                            <div class="col-sm-8">
                              <input type="text" id="tanggal_invoice" name="tanggal_invoice" class="form-control input-sm dtpicker-single" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Diterima Proyek</label>
                            <div class="col-sm-8">
                              <input type="text" id="date_receipt_tagihan" name="tanggal_diterima_proyek" class="form-control input-sm dtpicker-single" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Diterima office</label>
                            <div class="col-sm-8">
                              <input type="text" id="tanggal_diterima_office" name="tanggal_diterima_office" class="form-control input-sm dtpicker-single" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Metode Pembayaran</label>
                            <div class="col-sm-8">
                              <input type="text" id="metode_pembayaran" name="metode_pembayaran" class="form-control input-sm" >
                            </div>
                        </div>
                        <hr />
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th width="5%">A.</th>
                                    <th>KELENGKAPAN DATA (Lengkap dan Benar)</th>
                                    <th align="center">ADA / TIDAK</th>
                                    <th width="50%">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Invoice</td>
                                    <td align="center"><input type="checkbox" name="invoice" value="1"></td>
                                    <td><input type="text" name="invoice_keterangan" id="invoice_keterangan" class="form-control input-sm" ></td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Kwitansi</td>
                                    <td align="center"><input type="checkbox" name="kwitansi" value="1"></td>
                                    <td><input type="text" name="kwitansi_keterangan" id="kwitansi_keterangan" class="form-control input-sm" ></td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>Faktur Pajak</td>
                                    <td align="center"><input type="checkbox" name="faktur" value="1"></td>
                                    <td><input type="text" name="faktur_keterangan" id="faktur_keterangan" class="form-control input-sm" ></td>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td>Berita Acara Pembayaran (BAP)</td>
                                    <td align="center"><input type="checkbox" name="bap" value="1"></td>
                                    <td><input type="text" name="bap_keterangan" class="form-control input-sm" ></td>
                                </tr>
                                <tr>
                                    <td>5.</td>
                                    <td>Berita Acara Serah Terima (BAST)</td>
                                    <td align="center"><input type="checkbox" name="bast" value="1"></td>
                                    <td><input type="text" name="bast_keterangan" id="bast_keterangan" class="form-control input-sm" ></td>
                                </tr>
                                <tr>
                                    <td>6.</td>
                                    <td>Surat Jalan</td>
                                    <td align="center"><input type="checkbox" name="surat_jalan" value="1"></td>
                                    <td><input type="text" name="surat_jalan_keterangan" class="form-control input-sm" ></td>
                                </tr>
                                <tr>
                                    <td>7.</td>
                                    <td>Copy Kontrak/ PO</td>
                                    <td align="center"><input type="checkbox" name="copy_po" value="1"></td>
                                    <td><input type="text" name="copy_po_keterangan" id="copy_po_keterangan" class="form-control input-sm" ></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Catatan</label>
                            <div class="col-sm-9">
                              <textarea id="catatan" class="form-control" name="catatan"  rows="4"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-right">
                                <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm" id="btn-form"><i class="fa fa-close"></i> Batal</button>
                                <button type="submit" class="btn btn-success btn-sm" id="btn-form"><i class="fa fa-check"></i> Selesai</button>
                            </div>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="detailVerifForm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">Verifikasi Dokumen</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal"  >
                        <table class="" width="100%" border="0">
                            <tr>
                                <th width="30%">DIISI OLEH VERIFIKATOR</th>
                                <th width="2%">:</th>
                                <td width="68%" id="verifikator_d">-</td>
                            </tr>
                        </table>
                        <hr style="margin-top:10px;" />
                        <table class="table table-striped table-bordered table-condensed">
                            <tr>
                                <th width="30%">Nama Rekanan</th>
                                <th width="2%">:</th>
                                <td width="68%" id="supplier_name_d">-</td>
                            </tr>
                            <tr>
                                <th>Nomor Kontrak /PO</th>
                                <th>:</th>
                                <td id="no_po_d"></td>
                            </tr>
                            <tr>
                                <th>Nama Barang/Jasa</th>
                                <th>:</th>
                                <td id="nama_barang_jasa_d"></td>
                            </tr>
                            <tr>
                                <th>Nilai Kontrak /  PO</th>
                                <th>:</th>
                                <td id="nilai_kontrak_d"></td>
                            </tr>
                            <tr>
                                <th>Nilai Tagihan (Net)</th>
                                <th>:</th>
                                <td id="nilai_tagihan_d"></td>
                            </tr>
                            <tr>
                                <th>PPN</th>
                                <th>:</th>
                                <td id="ppn_d" class="numberformat"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Invoice</th>
                                <th>:</th>
                                <td id="tanggal_invoice_d"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Diterima Proyek</th>
                                <th>:</th>
                                <td id="tanggal_diterima_proyek_d"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Diterima Office</th>
                                <th>:</th>
                                <td id="tanggal_diterima_office_d"></td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <th>:</th>
                                <td id="metode_pembayaran_d"></td>
                            </tr>
                        </table>
                        <hr />
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th width="5%">A.</th>
                                    <th>KELENGKAPAN DATA (Lengkap dan Benar)</th>
                                    <th align="center">ADA / TIDAK</th>
                                    <th width="50%">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Invoice</td>
                                    <td align="center" id="invoice_d"></td>
                                    <td id="invoice_keterangan_d"></td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Kwitansi</td>
                                    <td align="center" id="kwitansi_d"></td>
                                    <td id="kwitansi_keterangan_d"></td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>Faktur Pajak</td>
                                    <td align="center" id="faktur_d"></td>
                                    <td id="faktur_keterangan_d"></td>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td>Berita Acara Pembayaran (BAP)</td>
                                    <td align="center" id="bap_d"></td>
                                    <td id="bap_keterangan_d"></td>
                                </tr>
                                <tr>
                                    <td>5.</td>
                                    <td>Berita Acara Serah Terima (BAST)</td>
                                    <td align="center" id="bast_d"></td>
                                    <td id="bast_keterangan_d"></td>
                                </tr>
                                <tr>
                                    <td>6.</td>
                                    <td>Surat Jalan</td>
                                    <td align="center" id="surat_jalan_d"></td>
                                    <td id="surat_jalan_keterangan_d"></td>
                                </tr>
                                <tr>
                                    <td>7.</td>
                                    <td>Copy Kontrak/ PO</td>
                                    <td align="center" id="copy_po_d"></td>
                                    <td id="copy_po_keterangan_d"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label class="col-sm-2">Catatan :</label>
                            <div id="catatan_d" class="col-sm-9">
                              
                            </div>
                        </div>
                    </form>
                    <form method="GET" target="_blank" action="<?php echo site_url('pmm/pembelian/print_verifikasi_penagihan_pembelian');?>">
                        <input type="hidden" name="id" id="verifikasi_penagihan_pembelian_id" >
                        <div class="text-right">
                            <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                        </div>  
                    </form>
                </div>
            </div>
        </div>
    </div>


    
    <script type="text/javascript">
        var form_control = '';
    </script>
    
	<?php echo $this->Templates->Footer();?>

	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/moment.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/daterangepicker/daterangepicker.css">
    <script type="text/javascript">
        
        $('input#contract').number( true, 2,',','.' );
        $('input.numberformat').number( true, 2,',','.' );
        $('.dtpicker-single').daterangepicker({
            autoUpdateInput:false,
            singleDatePicker: true,
            showDropdowns : true,
            locale: {
              format: 'DD-MM-YYYY'
            }
        });
        $('.dtpicker-single').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('DD-MM-YYYY'));
              // table.ajax.reload();
        });
        $('.dtpicker').daterangepicker({
            autoUpdateInput : false,
            locale: {
              format: 'DD/MM/YYYY'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            showDropdowns: true,
        });
        
        var table = $('#guest-table').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/pembelian/table_penawaran_pembelian');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "tanggal_penawaran" },
                { "data": "nomor_penawaran" },
                { "data": "supplier" },
                { "data": "jenis_pembelian" },
                { "data": "berlaku_hingga" },
                { "data": "status" },
                { "data": "total" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });

        var table_po = $('#table-po').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/pembelian/table_pesanan_pembelian');?>',
                type : 'POST',
            },
            columns: [
                { "data": "no" },
                { "data": "supplier" },
                { "data": "no_po" },
                { "data": "subject" },
                { "data": "date_po" },
                { "data": "volume" },
                { "data": "receipt" },
                { "data": "total" },
                { "data": "total_receipt" },
                { "data": "status" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });

        var table_receipt = $('#table-receipt').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/receipt_material/table_detail');?>',
                type : 'POST',
                data: function ( d ) {
                    d.purchase_order_id = $('#filter_po_id').val();
                    d.supplier_id = $('#filter_supplier_id').val();
                    d.filter_date = $('#filter_date').val();
                    d.material_id = $('#filter_material').val();
                }
            },
            "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
            columns: [
                { "data": "checkbox" },
                { "data": "no" },
                { "data": "date_receipt" },
                { "data": "supplier_name" },
                { "data": "material_name" },
                { "data": "measure" },
                { "data": "no_po" },
                { "data": "surat_jalan" },
                { "data": "no_kendaraan" },
                { "data": "driver" },
                { "data": "surat_jalan_file" },
                { "data": "volume" },
                { "data": "display_cost" },
                { "data": "display_cost_val" },
                { "data": "status_payment" },
            ],
            select: {
                style: 'multi'
            },
            responsive: true,
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                    "className": 'select-checkbox',
                },
                {
                    "targets": [1],
                    "className": 'text-center',
                },
                {
                    "targets": [-3],
                    "className": 'text-right',
                },
                {
                    "targets": [-2],
                    "visible": false,
                },
                {
                    "targets": [-1],
                    "className": 'text-center',
                }
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                total = api
                    .column( 11 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 11 ).footer() ).html($.number( total, 2,',','.' ));


                total_display_cost = api
                    .column( 13 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 12 ).footer() ).html($.number( total_display_cost, 2,',','.' ));
            }
        });

        var table_tagihan = $('#table-tagihan').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/pembelian/table_penagihan_pembelian');?>',
                type : 'POST',
            },
            columns: [
                { "data": "tanggal_invoice" },
                { "data": "nomor_invoice" },
                { "data": "supplier" },
                { "data": "jenis" },
                { "data": "tanggal_jatuh_tempo" },
                { "data": "total" },
                { "data": "pembayaran" },
                { "data": "sisa_tagihan" },
                { "data": "status" },
                { "data": "verifikasi_dok" },
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });


        $('#btn_production').click(function(){
            var data_receipt = table_receipt.rows({ selected: true } ).data();
            var send_data = '';
            bootbox.confirm("Are you sure to process this data ?", function(result){ 
                // console.log('This was logged in the callback: ' + result); 
                if(result){
                    $.each(data_receipt,function(i,val){
                        send_data += val.id+',';
                    });

                    window.location.href = '<?php echo site_url('pmm/pembelian/penagihan_pembelian/');?>'+send_data;
                }
            });
            
        });

        $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            table_receipt.ajax.reload();             
        });

        function GetPO()
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/receipt_material/get_po_by_supp'); ?>/"+Math.random(),
                dataType : 'json',
                data: {
                    supplier_id : $('#filter_supplier_id').val(),
                },
                success : function(result){
                    if(result.data){
                        $('#filter_po_id').empty();
                        $('#filter_po_id').select2({data:result.data});
                        $('#filter_po_id').trigger('change');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }


        $('#filter_supplier_id').change(function(){
            table_receipt.ajax.reload();  
            GetPO();
        });
        $('#filter_po_id').change(function(){
            table_receipt.ajax.reload();  
        });
        function VerifDok(id)
        {   
            
            $('#modalForm').modal('show');
            $('#id').val('');
            // table_detail.ajax.reload();
            
            $('#id').val(id);
            getData(id);
        }

        function VerifDokDetail(id)
        {   
            
            $('#detailVerifForm').modal('show');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/pembelian/get_verif_penagihan_pembelian'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.data){
                        $('#supplier_name_d').text(result.data.supplier_name);
                        $('#no_po_d').text(result.data.nomor_po+' - '+result.data.tanggal_po);
                        $('#nama_barang_jasa_d').text(result.data.nama_barang_jasa);
                        $('#nilai_kontrak_d').text(result.data.nilai_kontrak);
                        $('#nilai_tagihan_d').text(result.data.nilai_tagihan);
                        $('#ppn_d').text(result.data.ppn);
                        $('#tanggal_invoice_d').text(result.data.tanggal_invoice);
                        $('#tanggal_diterima_proyek_d').text(result.data.tanggal_diterima_proyek);
                        $('#tanggal_diterima_office_d').text(result.data.tanggal_diterima_office);
                        $('#metode_pembayaran_d').text(result.data.metode_pembayaran);
                        $('#invoice_keterangan_d').text(result.data.invoice_keterangan);
                        $('#kwitansi_keterangan_d').text(result.data.kwitansi_keterangan);
                        $('#faktur_keterangan_d').text(result.data.faktur_keterangan);
                        $('#bap_keterangan_d').text(result.data.bap_keterangan);
                        $('#bast_keterangan_d').text(result.data.bast_keterangan);
                        $('#surat_jalan_keterangan_d').text(result.data.surat_jalan_keterangan);
                        $('#copy_po_keterangan_d').text(result.data.copy_po_keterangan);
                        $('#catatan_d').text(result.data.catatan);
                        $('#verifikator_d').text(result.data.verifikator);
                        $('#verifikasi_penagihan_pembelian_id').val(result.data.id);
                        if(result.data.invoice == 1){
                            $("#invoice_d").html('<i class="fa fa-check"></i>');
                        }else {
                            $("#invoice_d").html('<i class="fa fa-close"></i>');
                        }
                        if(result.data.kwitansi == 1){
                            $("#kwitansi_d").html('<i class="fa fa-check"></i>');
                        }else {
                             $("#kwitansi_d").html('<i class="fa fa-close"></i>');
                        }
                        if(result.data.faktur == 1){
                            $("#faktur_d").html('<i class="fa fa-check"></i>');
                        }else {
                             $("#faktur_d").html('<i class="fa fa-close"></i>');
                        }
                        if(result.data.bap == 1){
                            $("#bap_d").html('<i class="fa fa-check"></i>');
                        }else {
                             $("#bap_d").html('<i class="fa fa-close"></i>');
                        }
                        if(result.data.bast == 1){
                            $("#bast_d").html('<i class="fa fa-check"></i>');
                        }else {
                             $("#bast_d").html('<i class="fa fa-close"></i>');
                        }
                        if(result.data.surat_jalan == 1){
                            $("#surat_jalan_d").html('<i class="fa fa-check"></i>');
                        }else {
                             $("#surat_jalan_d").html('<i class="fa fa-close"></i>');
                        }
                        if(result.data.copy_po == 1){
                            $("#copy_po_d").html('<i class="fa fa-check"></i>');
                        }else {
                             $("#copy_po_d").html('<i class="fa fa-close"></i>');
                        }
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        

        function getData(id)
        {
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/pembelian/get_penagihan_pembelian'); ?>",
                dataType : 'json',
                data: {id:id},
                success : function(result){
                    if(result.data){
                        $('#penagihan_pembelian_id').val(result.data.id);
                        $('#supplier_name').val(result.data.supplier_name);
                        $('#no_po').val(result.data.no_po);
                        $('#jenis').val(result.data.jenis);
                        $('#nilai_kontrak').val(result.data.total_po);
                        $('#nilai_tagihan').val(result.data.total);
                        $('#tanggal_invoice').val(result.data.tanggal_invoice);
                        // $('#date_receipt_tagihan').val(result.data.date_receipt);
                        $('#tanggal_po').val(result.data.date_po);
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });
        }

        $('#form-verif-dok').submit(function(event){
            // $('#btn-form').button('loading');
            $.ajax({
                type    : "POST",
                url     : "<?php echo site_url('pmm/pembelian/verif_dok_penagihan_pembelian'); ?>/"+Math.random(),
                dataType : 'json',
                data: $(this).serialize(),
                success : function(result){
                    $('#btn-form').button('reset');
                    if(result.output){
                        $("#form-verif-dok").trigger("reset");
                        table_tagihan.ajax.reload();
                        $('#modalForm').modal('hide');
                    }else if(result.err){
                        bootbox.alert(result.err);
                    }
                }
            });

            event.preventDefault();
            
        });


    </script>

</body>
</html>
