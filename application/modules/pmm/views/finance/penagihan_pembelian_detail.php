<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .table-center th, .table-center td{
            text-align:center;
        }
        .form-approval{
            display: inline-block;
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
                                <a href="<?php echo site_url('admin/pembelian');?>"> <i class="fa fa-calendar" aria-hidden="true"></i> Pembelian</a></li>
                            <li><a>Penagihan Pembelian </a></li>
                        </ul>
                    </div>
                </div>
                <div class="row animated fadeInUp">
                    <div class="col-sm-12 col-lg-12">
                        <div class="panel">
                            <div class="panel-header"> 
                                <div class="text-right">
                                    <h3 class="pull-left">
                                        Penagihan Pembelian
                                        <small>(<i><?= $row['status'];?></i>)</small>
                                    </h3>
                                    <a href="<?php echo site_url('admin/pembelian');?>" class="btn btn-info"><i class="fa fa-mail-reply"></i> Back</a>
                                </div>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Supplier</label>
                                        <input type="text" class="form-control" value="<?= $row['supplier'];?>" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Nomor Purchase Pembelian</label>
                                        <input type="text" class="form-control" value="<?= $row['no_po'];?>" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Tgl Invoice</label>
                                        <input type="text" class="form-control" value="<?= date('d/m/Y',strtotime($row['tanggal_invoice']));?>" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Syarat Pembayaran</label>
                                        <input type="text" class="form-control" value="<?= $row['syarat_pembayaran'];?>" readonly>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label >Alamat Supplier</label>
                                        <textarea class="form-control" name="alamat_supplier" id="alamat_supplier" required="" readonly=""><?= $row['supplier_address'];?></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Jenis</label>
                                        <input type="text" class="form-control" value="<?= $row['jenis'];?>" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Nomor Invoice</label>
                                        <input type="text" class="form-control" value="<?= $row['nomor_invoice'];?>" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Tgl Jatuh Tempo</label>
                                        <input type="text" class="form-control" value="<?= date('d/m/Y',strtotime($row['tanggal_jatuh_tempo']));?>" readonly>
                                    </div>
                                </div>
                                <br />
                                <div class="table-responsive">
                                    <table id="table-product" class="table table-bordered table-striped table-condensed table-center">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="22%">Material</th>
                                                <th width="12%">Qty</th>
                                                <th width="10%">Satuan</th>
                                                <th width="15%">Harga Satuan</th>
                                                <th width="10%">Pajak</th>
                                                <th width="20%">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sub_total = 0;
                                            $tax_pph = 0;
                                            $tax_ppn = 0;
                                            $tax_0 = false;
                                            $total = 0;
                                            $details = $this->db->get_where('pmm_penagihan_pembelian_detail',array('penagihan_pembelian_id'=>$row['id']))->result_array();
                                            ?>
                                            <?php foreach($details as $key => $dt) { ?>
                                            <?php 
                                                $material = $this->crud_global->GetField('pmm_materials',array('id'=>$dt['material_id']),'material_name');
                                                $tax = $this->crud_global->GetField('pmm_taxs',array('id'=>$dt['tax_id']),'tax_name');
                                            ?>
                                            <tr>
                                                <td><?= $key+1 ?>.</td>
                                                <td>
                                                    <?= $material;?>
                                                </td>
                                               <td><?= $dt['volume'];?></td>
                                               <td><?= $dt['measure'];?></td>
                                               <td><?= $this->filter->Rupiah($dt['price']);?></td>
                                               <td><?= $dt['tax'];?></td>
                                               <td style="text-align: right !important;"><?= $this->filter->Rupiah($dt['total']);?></td>
                                            </tr>
                                            <?php
                                                $sub_total += $dt['total'];
                                                if($dt['tax_id'] == 4){
                                                    $tax_0 = true;
                                                }
                                                if($dt['tax_id'] == 3){
                                                    $tax_ppn += $dt['tax'];
                                                }
                                                if($dt['tax_id'] == 5){
                                                    $tax_pph += $dt['tax'];
                                                }
                                            } 
                                            ?>
                                        </tbody>
                                    </table>    
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Memo</label>
                                            <textarea class="form-control" name="memo" rows="3" value="" readonly ><?= $row["memo"]; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Lampiran</label>
                                            <?php
                                            $dataLampiran = $this->db->get_where('pmm_lampiran_penagihan_pembelian',array('penagihan_pembelian_id'=>$row['id']))->result_array();
                                            if(!empty($dataLampiran)){
                                                foreach ($dataLampiran as $key => $lampiran) {
                                                    ?>
                                                    <div><a href="<?= base_url().'uploads/penagihan_pembelian/'.$lampiran['lampiran'];?>" target="_blank"><?= $lampiran['lampiran'];?></a></div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 form-horizontal">
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Sub Total</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total" ><?= $this->filter->Rupiah($sub_total);?></h5>
                                            </div>
                                        </div>
                                        <?php
                                        if($tax_ppn > 0){
                                            ?>
                                            <div class="row">
                                                <label class="col-sm-7 control-label">Pajak Ppn</label>
                                                <div class="col-sm-5 text-right">
                                                    <h5 id="sub-total" ><?= $this->filter->Rupiah($tax_ppn);?></h5>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if($tax_0){
                                            ?>
                                            <div class="row">
                                                <label class="col-sm-7 control-label">Pajak 0%</label>
                                                <div class="col-sm-5 text-right">
                                                    <h5 id="sub-total" ><?= $this->filter->Rupiah(0);?></h5>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if($tax_pph > 0){
                                            ?>
                                            <div class="row">
                                                <label class="col-sm-7 control-label">Pajak Pph</label>
                                                <div class="col-sm-5 text-right">
                                                    <h5 id="sub-total" ><?= $this->filter->Rupiah($tax_pph);?></h5>
                                                </div>
                                            </div>
                                            <?php
                                        }

                                        $total = $sub_total - $tax_ppn + $tax_pph;
                                        $sisa_tagihan = $this->pmm_finance->getTotalPembayaranPenagihanPembelian($row['id']);
                                        ?>
                                        <div class="row">
                                            <label class="col-sm-7 control-label">Uang Muka</label>
                                            <div class="col-sm-5 text-right">
                                                <h5 id="sub-total" ><?= $this->filter->Rupiah($row['uang_muka']);?></h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <h4 class="col-sm-7 control-label">Total</h4>
                                            <div class="col-sm-5 text-right">
                                                <h4 id="total" ><?= $this->filter->Rupiah($row['total']);?></h4>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <h4 class="col-sm-7 control-label">Sisa Tagihan</h4>
                                            <div class="col-sm-5 text-right">
                                                <h4 id="total" ><?= $this->filter->Rupiah($row['total'] - $sisa_tagihan);?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <?php if($row["status"] === "DRAFT") : ?>
                                            <form class="form-approval" action="<?= base_url("pmm/pembelian/approve_penawaran_pembelian/".$row["id"]) ?>">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> APPROVE</button>        
                                            </form>
                                        <form class="form-approval" action="<?= base_url("pmm/pembelian/reject_penawaran_pembelian/".$row["id"]) ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> REJECT</button>        
                                        </form>
                                        
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <br />
                                <div class="text-center">
                                    <a href="#" class="btn btn-info"><i class="fa fa-eye"></i> Cetak & Lihat</a>
                                    <?php
                                    if($row['verifikasi_dok'] == 'SUDAH'){
                                        ?>
                                        <a href="<?= site_url('pmm/pembayaran/penagihan_pembelian/'.$row['id']);?>" class="btn btn-primary"><i class="fa fa-money"></i> Terima Pembayaran</a>
                                        <?php
                                        // if($this->session->userdata('admin_group_id') == 1 || $this->session->userdata('admin_group_id') == 5){
                                            ?>
                                            <a class="btn btn-danger" onclick="DeleteData('<?= site_url('pmm/pembelian/delete_penagihan_pembelian/'.$row['id']);?>')"><i class="fa fa-close"></i> Hapus</a>
                                            <?php
                                        // }
                                    }
                                    ?>
                                    
                                </div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#menu1" aria-controls="menu2" role="tab" data-toggle="tab">Daftar Surat Jalan</a></li>
                                    <li role="presentation" ><a href="#menu2" aria-controls="menu2" role="tab" data-toggle="tab">Daftar Pembayaran</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="menu1">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center table-bordered" id="table-surat-jalan" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Bahan</th>
                                                        <th>Satuan</th>
                                                        <th>No PO</th>
                                                        <th>No Surat Jalan</th>
                                                        <th>No Kendaraan</th>
                                                        <th>Nama Supir</th>
                                                        <th>File</th>
                                                        <th>Volume</th>
                                                        <th>Biaya</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?
                                                    $surat_jalan = explode(',', $row['surat_jalan']);
                                                    $this->db->select('prm.*,ppo.no_po, m.material_name');
                                                    $this->db->join('pmm_purchase_order ppo','prm.purchase_order_id = ppo.id','left');
                                                    $this->db->join('pmm_materials m','prm.material_id = m.id','left');
                                                    $this->db->where_in('prm.id',$surat_jalan);
                                                    $table_surat_jalan = $this->db->get('pmm_receipt_material prm')->result_array();
                                                    if(!empty($table_surat_jalan)){
                                                        foreach ($table_surat_jalan as $sj) {
                                                            ?>
                                                            <tr>
                                                                <td><?= date('d/m/Y',strtotime($sj['date_receipt']));?></td>
                                                                <td><?= $sj['material_name'];?></td>
                                                                <td><?= $sj['measure'];?></td>
                                                                <td><?= $sj['no_po'];?></td>
                                                                <td><?= $sj['surat_jalan'];?></td>
                                                                <td><?= $sj['no_kendaraan'];?></td>
                                                                <td><?= $sj['driver'];?></td>
                                                                <td><?= $sj['surat_jalan_file'];?></td>
                                                                <td><?= $this->filter->Rupiah($sj['volume']);?></td>
                                                                <td><?= $this->filter->Rupiah($sj['volume'] * $sj['cost']);?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="menu2">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-center" id="table-pembayaran" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nomor</th>
                                                        <th>Bayar Dari</th>
                                                        <th>Jumlah</th>
                                                        <th>Status Pembayaran</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center">

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
    
    <script type="text/javascript">
        var form_control = '';
    </script>
    <?php echo $this->Templates->Footer();?>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootbox.min.js"></script>

    <script type="text/javascript">
        $('.form-approval').submit(function(e){
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

        var table = $('#table-pembayaran').DataTable( {
            ajax: {
                processing: true,
                serverSide: true,
                url: '<?php echo site_url('pmm/pembayaran/table_pembayaran_penagihan_pembelian/'.$row["id"]);?>',
                type : 'POST',
            },
            columns: [
                { "data": "tanggal_pembayaran" },
                { "data": "nomor_transaksi" },
                { "data": "bayar_dari" },
                { "data": "total_pembayaran" },
                { "data": "status" },
                { "data": "action"}
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "className": 'text-center',
                }
            ],
            responsive: true,
        });

        var table_surat_jalan = $('#table-surat-jalan').DataTable();

        function ApprovePayment(href)
        {
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
                        window.location.href = href;
                    }
                    
                }
            });
        }

        function DeleteData(href)
        {
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
                        window.location.href = href;
                    }
                    
                }
            });
        }
    </script>


</body>
</html>
