<!DOCTYPE html>
<html>
    <head>
      <title>PEMBERITAHUAN PEMBAYARAN</title>
      
      <style type="text/css">
        body{
            font-family: "Open Sans", Arial, sans-serif;
        }
        table.minimalistBlack {
          /*border: 1px solid #cccccc;*/
          width: 100%;
          text-align: left;
        }
        table.minimalistBlack td, table.minimalistBlack th {
          border: 0.5px solid #cccccc;
          padding: 5px 4px;
        }
        table.minimalistBlack tr td {
          /*font-size: 13px;*/
          /*text-align:center;*/
        }
        table.minimalistBlack tr th {
          /*font-size: 14px;*/
          font-weight: bold;
          padding: 10px;
        }
        table.minimalistBlack .table-akun{
            background-color: #668d98;
            color: #fff;
        }
        table.minimalistBlack .table-akun th{
            color: #fff;
        }

        table tr.table-active{
            background-color: #b5b5b5;
        }
        table tr.table-active2{
            background-color: #cac8c8;
        }
        table tr.table-active3{
            back
            ground-color: #eee;
        }
        hr{
            margin-top:0;
            margin-bottom:30px;
        }
        h3{
            margin-top:0;
        }
      </style>

    </head>
    <body>
        <table width="100%" border="0" cellpadding="3">
            <tr >
                <td align="center">
                    <div style=";font-weight: bold;font-size: 14px;border-bottom: 1px solid #000;border-top: 1px solid #000;text-transform: uppercase;">BUKTI PENGELUARAN</div>
                </td>
            </tr>
        </table>
        <br /><br />
        <table width="100%" border="0" cellpadding="3">
            <tr>
                <th width="25%">Terima Dari</th>
                <th width="2%">:</th>
                <th width="73%" align="left"><?= $detail["terima_dari"] ?></th>
            </tr>
            <tr>
                <th>Setor Ke</th>
                <th >:</th>
                <th align="left"><?= $detail["setor_ke"] ?></th>
            </tr>
            <tr>
                <th>Nomor Transaksi</th>
                <th >:</th>
                <th align="left"><?= $detail["nomor_transaksi"] ?></th>
            </tr>
            <tr>
                <th>Tanggal Transaksi</th>
                <th >:</th>
                <th align="left"><?= date('d F Y',strtotime($detail["tanggal_transaksi"])) ?></th>
            </tr>
        </table>
        <table width="100%" border="0" cellpadding="3">
            <tr>
                <th width="25%">Jumlah</th>
                <th width="2%">:</th>
                <th width="73%" align="left">Rp. <?= $this->filter->Rupiah($detail['jumlah']);?></th>
            </tr>
            <tr>
                <th width="25%">TERBILANG</th>
                <th width="2%">:</th>
                <th width="73%" align="left"><?= $this->filter->terbilang($detail['jumlah']);?></th>
            </tr>
        </table>
        <br />
        <br />
        <table width="98%" border="0" cellpadding="0">
            <tr>
                <td width="100%">
                    <table width="100%" border="1" cellpadding="2">
                        <tr class="table-active3">
                            <td align="center">
                                Dibuat Oleh
                            </td>
                            <td align="center" colspan="2">
                                Diperiksa Oleh,
                            </td>
                            <td align="center" >
                                Disetujui
                            </td>
                            <td align="center" >
                                Diketahui
                            </td>
                        </tr>
                        <tr class="">
                            <td align="center" height="75px">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                            <td align="center">
                                
                            </td>
                        </tr>
                        <tr class="table-active3">
                            <?php
                            $keuangan_proyek = $this->pmm_model->GetNameGroup(10);
                            $ka_plant = $this->pmm_model->GetNameGroup(8);
                            $manager_keuangan = $this->pmm_model->GetNameGroup(5);
                            $direksi = $this->pmm_model->GetNameGroup(6);
                            ?>  
                            <td align="center">
                                <?= $this->crud_global->GetField('tbl_admin',array('admin_id'=>$detail['created_by']),'admin_name'); ?>
                            </td>
                            <td align="center">
                                <?=  $keuangan_proyek['admin_name'];?>
                            </td>
                            <td align="center">
                                <?=  $ka_plant['admin_name'];?>
                            </td>
                            <td align="center">
                                <?=  $manager_keuangan['admin_name'];?>
                            </td>
                            <td align="center" >
                                <?=  $direksi['admin_name'];?>
                            </td>
                        </tr>
                         <tr class="table-active3">
                            <td align="center">
                                <?php
                                $this->db->select('g.admin_group_name');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$detail['created_by']);
                                $created_group = $this->db->get('tbl_admin a')->row_array();


                                ?>
                                <?= $created_group['admin_group_name']?>
                            </td>
                            <td align="center">
                                <?=  $keuangan_proyek['admin_group_name'];?>
                            </td>
                            <td align="center">
                                <?=  $ka_plant['admin_group_name'];?>
                            </td>
                            <td align="center">
                                <?=  $manager_keuangan['admin_group_name'];?>
                            </td>
                            <td align="center" >
                                <?=  $direksi['admin_group_name'];?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
            
        

    </body>
</html>