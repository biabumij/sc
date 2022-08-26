<!DOCTYPE html>
<html>
    <head>
      <title>BUKTI PENERIMAAN</title>
      
      <style type="text/css">
        body{
            font-family: "Open Sans", Arial, sans-serif;
        }
        table tr.table-bold{
           font-weight: bold;
        }
      </style>

    </head>
    <body>
        <?
        $prefix_title = explode(' ', $pembayaran['bayar_dari']);
        ?>
        <table width="98%" border="0" cellpadding="3">
            <tr >
                <td align="center">
                    <div style=";font-weight: bold;font-size: 14px;border-bottom: 1px solid #000;border-top: 1px solid #000;">Bukti Pengeluaran <?= $bayar_dari;?></div>
                    <div style="font-size: 10px;line-height: 20px"><?= $pembayaran['nomor_transaksi'];?></div>
                </td>
            </tr>
        </table>
        <br /><br />
        <table width="98%" border="0" cellpadding="3">
            <tr>
                <th width="25%">Dibayar Kepada</th>
                <th width="2%">:</th>
                <th width="73%" align="left"><?= $pembayaran['supplier_name'];?></th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <th >:</th>
                <th align="left"><?= date('d F Y',strtotime($pembayaran['tanggal_pembayaran']));?></th>
            </tr>
            <tr>
                <th>Untuk Pembayaran</th>
                <th >:</th>
                <th align="left">Purchase Invoice <?= $this->crud_global->GetField('pmm_penagihan_pembelian',array('id'=>$pembayaran['penagihan_pembelian_id']),'nomor_invoice');?></th>
            </tr>
            <tr>
                <th>Keterangan</th>
                <th >:</th>
                <th align="left"><?= $pembayaran['memo'];?></th>
            </tr>
        </table>
        <br />
        <br />
        <br />
        <br />
        <br />
        <table width="98%" border="0" cellpadding="0">
            <tr>
                <th width="25%" ><div style="font-size:8px;font-weight: bold;line-height: 25px;border-bottom:1px solid #a0a0a0;border-top:1px solid #a0a0a0;text-align:center;">Jumlah</div></th>
                <th width="25%" align="left">
                    <div class="total" style="position:relative; background-color: #bf9b30;font-size:8px;font-weight: bold;line-height: 25px;border-bottom:1px solid #a0a0a0;border-top:1px solid #a0a0a0;">
                       
                        Rp. <?= number_format($pembayaran['total'],0,',','.'); ?>
                    </div>
                </th>
            </tr>
            <tr >
                <th width="25%" ><div style="font-size:8px;font-weight: bold;line-height: 0px;text-align: center;">Terbilang</div></th>
				<th width="50%" ><div style="font-size:8px;font-style: italic;font-weight: bold;line-height: 0px;text-align: left;text-transform:capitalize;">: <?= $this->filter->terbilang($pembayaran['total']);?></div></th>
                
            </tr>
        </table>
        <br />
        <br />
        <table width="98%" border="0" cellpadding="30">
            <tr >
                <td width="5%"></td>
                <td width="90%">
                    <table width="100%" border="1" cellpadding="3">
                        <tr class="">
                            <td align="center" >
                                Dibuat Oleh
                            </td>
                            <td align="center" >
                                Diperiksa dan Disetujui Oleh
                            </td>
                            <td align="center" >
                                Diketahui Oleh
                            </td>
                        </tr>
                        <tr class="">
                            <td align="center" height="75px">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                        </tr>
                        <tr class="table-bold">
                            <td align="center" >
                                <?= $this->crud_global->GetField('tbl_admin',array('admin_id'=>$pembayaran['created_by']),'admin_name'); ?>
                            </td>
                            <td align="center" >
                                Erika Sinaga
                            </td>
                            <td align="center" >
                                Deddy Sarwobiso
                            </td>
                        </tr>
                        <tr class="table-bold">
                            <td align="center" >
                            <?php
                            $this->db->select('g.admin_group_name');
                            $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                            $this->db->where('a.admin_id',$pembayaran['created_by']);
                            $created_group = $this->db->get('tbl_admin a')->row_array();
                            ?>
                                <b><?= $created_group['admin_group_name']?></b>
                            </td>
                            <td align="center" >
                                <b>Dir. Keuangan</b>
                            </td>
                            <td align="center" >
                                <b>Direktur Utama</b>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="5%"></td>
            </tr>
        </table>
    </body>
</html>