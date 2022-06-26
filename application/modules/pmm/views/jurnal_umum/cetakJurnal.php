<!DOCTYPE html>
<html>
    
    <head>
      <title>JURNAL UMUM</title>
      <?= include 'lib.php'; ?>
      <style type="text/css">
        body{
            font-family: "Open Sans", Arial, sans-serif;
        }
        table.minimalistBlack {
          /*border: 1px solid #ededed;*/
          width: 98%;
          text-align: left;
        }
        table.minimalistBlack td, table.minimalistBlack th {
          border: 0.5px solid #ededed;
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
            background-color: #e69500;
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
        <table width="98%" border="0" cellpadding="3">
            <tr >
                <td align="center">
                    <div style=";font-weight: bold;font-size: 14px;border-bottom: 1px solid #000;border-top: 1px solid #000;">JURNAL UMUM</div>
                </td>
            </tr>
        </table>
        <br /><br />
        <table width="98%" border="0" cellpadding="3">
            <tr>
                <th width="25%">Nomor Transaksi</th>
                <th width="2%">:</th>
                <th width="73%" align="left"><?= $biaya["nomor_transaksi"] ?></th>
            </tr>
            <tr>
                <th>Tanggal Transaksi</th>
                <th >:</th>
                <th align="left"><?= convertDateDBtoIndo($biaya["tanggal_transaksi"]); ?></th>
            </tr>
        </table>
        <br /><br />
        <table class="minimalistBlack" cellpadding="4" width="98%">
            <tr class="table-akun">
                <th width="10%">KODE AKUN</th>
                <th width="30%">NAMA AKUN</th>
                <th width="30%" align="right">DEBIT</th>
                <th width="30%"  align="right">KREDIT</th>
            </tr>
            <?php foreach($detail as $d) : ?>
            <tr>
                <td><?= $d["coa_number"] ?> </td>
                <td><?= $d["coa"]; ?></td>
                <td align="right"><?= number_format($d["debit"],2,',','.'); ?></td>
                <td align="right"><?= number_format($d['kredit'],2,',','.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th></th>
                <th align="right">TOTAL</th>
                <th align="right">Rp.<?= number_format($biaya['total_debit'],2,',','.') ?></th>
                <th align="right">Rp.<?= number_format($biaya['total_kredit'],2,',','.') ?></th>
            </tr>
        </table>
        <br />
        <br />
        <table width="98%" border="0" cellpadding="3">
            <tr>
                <th width="25%">Memo</th>
                <th width="2%">:</th>
                <th width="73%" align="left"><?= $biaya["memo"] ?></th>
            </tr>
        </table>
        <br />
        <br />
        <?php
        $staff_keuangan_proyek = $this->pmm_model->GetNameGroup(14);
        $kepala_divisi = $this->pmm_model->GetNameGroup(15);
		$ka_plant = $this->pmm_model->GetNameGroup(15);
        $manager_keuangan = $this->pmm_model->GetNameGroup(10);
        $direksi = $this->pmm_model->GetNameGroup(6);
        $keuangan_proyek = $this->pmm_model->GetNameGroup(10);
        $arr_no_trans = explode('/', $biaya['nomor_transaksi']);
        ?>  

        <table width="98%" border="0" cellpadding="0">
            <tr>
                <td width="100%">
                    <table width="100%" border="1" cellpadding="2">
                        <tr class="table-active3">
                            <td align="center">
                                Dibuat Oleh
                            </td>
                            <td align="center">
                                Diperiksa Oleh,
                            </td>
                            <?php
                            if(!empty($arr_no_trans)){
								if (strpos($arr_no_trans[2], 'SC') === false) {
                            ?>
                            <td align="center"colspan="2">
                                Disetujui
                            </td>
                              <?php
                                }
                            }
                            ?>
                            <td align="center" >
                                Diketahui
                            </td>
                            <td align="center" >
                                Diterima 
                            </td>
                        </tr>
                        <tr class="">
                            <td align="center" height="75px">
                                
                            </td>
                            
                                    <td align="center">
                                
                                    </td>
                            <?php
                            if(!empty($arr_no_trans)){
                                if (strpos($arr_no_trans[2], 'SC') === false) {
                                    ?>
                            <td align="center">           
                            </td>       
                            <td align="center">   
                            </td>
                            <?php
                            }
                            }
                            ?>
                            <td align="center">  
                            </td>
                            <td align="center">
                            </td>
                        </tr>
                        <tr class="table-active3">
                           <td align="center">
                                <?= $this->crud_global->GetField('tbl_admin',array('admin_id'=>$biaya['created_by']),'admin_name'); ?>
                            </td>
                            <?php
                            if(!empty($arr_no_trans)){
                                if (strpos($arr_no_trans[2], 'SC') === false) {
                                    ?>
                                    <td align="center">
                                        <?=  $keuangan_proyek['admin_name'];?>
                                    </td>
                                    <td align="center">
                                        <?=  $ka_plant['admin_name'];?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            <td align="center">
								<?=  $manager_keuangan['admin_name'];?>
                            </td>
                            
                            <td align="center" >
								<?=  $kepala_divisi['admin_name'];?>
                            </td>
                            <td align="center" >  
                            </td>
                        </tr>
                         <tr class="table-active3">
                            <td align="center">
                                 <?php
                                $this->db->select('g.admin_group_name');
                                $this->db->join('tbl_admin_group g','a.admin_group_id = g.admin_group_id','left');
                                $this->db->where('a.admin_id',$biaya['created_by']);
                                $created_group = $this->db->get('tbl_admin a')->row_array();
								//file_put_contents("D:\\cetakJurnal.txt", $this->db->last_query());


                                ?>
                                <?= $created_group['admin_group_name']?>
                            </td>
                            <?php
                            if(!empty($arr_no_trans)){
                                if (strpos($arr_no_trans[2], 'SC') === false) {
                                    ?>
                                    
                                    <td align="center">
                                        <?=  $keuangan_proyek['admin_group_name'];?>
                                    </td>
                                    <td align="center">
                                        <?=  $ka_plant['admin_group_name'];?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            <td align="center">
                                <?=  $manager_keuangan['admin_group_name'];?>
                            </td>
                            <td align="center" >
                                <?=  $kepala_divisi['admin_group_name'];?>
                            </td>
                            <td align="center" >
                               Penerima 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>