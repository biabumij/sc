<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
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
                        <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                        <li><a>Invoice</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Invoice Detail</h3>
                            <div class="panel-actions" style="margin-top: 10px;">
                                <?php
                                if($data[0]->status == 2){
                                    $active = 'btn-info disabled';
                                    $label = 'PAID';
                                }else {
                                    $active = 'btn-danger';
                                    $label = 'Make as Paid';
                                }
                                ?>
                                    <a href="<?php echo site_url('member_back/print_invoice/'.$id);?>" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i></a>
                                    <a href="javascript:void(0);" class="btn <?php echo $active;?> active-invoice"><?php echo $label;?></a>
                            </div>
                        </div>
                        <div class="panel-content">
                            <h3>#<?php echo $data[0]->invoice_no;?></h3>
                            <hr />
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <b><?php echo $data_member[0]->name;?></b><br />
                                        <?php echo $data_member[0]->address;?><br />
                                        <?php echo $this->m_member->GetLocation('villages',array('id'=>$data_member[0]->village_id));?><br />
                                        <?php echo $this->m_member->GetLocation('districts',array('id'=>$data_member[0]->district_id));?><br />
                                        <?php echo $this->m_member->GetLocation('regencies',array('id'=>$data_member[0]->regencie_id));?><br />
                                        <?php echo $this->m_member->GetLocation('provinces',array('id'=>$data_member[0]->province_id));?>, <?php echo $data_member[0]->zip_code;?><br />
                                        <b>P : <?php echo $data_member[0]->phone;?></b>
                                    </address>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p><b>Order Date :</b> <?php echo $this->waktu->WestConvertion($data[0]->datecreated);?></p>
                                    <p><b>Order Status :</b> <?php echo $this->general->GetStatusInvoice($data[0]->status);?></p>
                                </div>
                                <br />
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="basic-table" class="table table-striped table-bordered table-hover text-center" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="50px;">No</th>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Item Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(is_array($data_invoice)){
                                                $no=1;
                                                foreach ($data_invoice as $key => $row) {
                                                    $pro_name = $this->crud_global->GetField('tbl_product_data',array('language_id'=>$lang_id,'product_id'=>$row->product_id),'product_name');
                                                    $price = $this->crud_global->GetField('tbl_product',array('product_id'=>$row->product_id),'price');
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $no;?></td>
                                                        <td><?php echo $pro_name;?></td>
                                                        <td><?php echo $row->qty;?></td>
                                                        <td><?php echo $price;?></td>
                                                        <td ><?php echo $price * $row->qty;?></td>
                                                    </tr>
                                                    <?php
                                                    $no++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align:right;"><h4>Grand Total</h4></td>
                                                <td style="text-align:right;"><h4><?php echo $this->general->NumberMoney($data[0]->total);?></h4></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

    <?php echo $this->Templates->Footer();?>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".active-invoice").click(function(){
                $(this).button('loading');
                $.ajax({
                    type: "POST",
                    url:  '<?php echo site_url("member_back/invoice_process");?>'+"/"+Math.random(),
                    data: {id : <?php echo $id;?>},
                    dataType: 'json',
                    async: true,
                    cache: false,
                    success: function (output) {
                      var output = output.output;
                      if(output == 'true'){
                          swal({
                            title : 'Activated!',
                            type : 'success'
                          },function(){
                            location.reload();
                          });
                      }else {
                        swal({
                            title : 'Error!',
                            type : 'error'
                          },function(){
                            location.reload();
                          });
                      }
                    }
                });
            });
        });
    </script>
</body>
</html>
