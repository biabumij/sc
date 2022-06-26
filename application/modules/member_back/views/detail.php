<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
</head>

<body>
<div class="wrap">
    
    <div class="modal fade" id="default-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal-ajax">
                
            </div>
        </div>
    </div>

    <?php echo $this->Templates->PageHeader();?>

    <div class="page-body">
        <?php echo $this->Templates->LeftBar();?>
        <div class="content">
            <div class="content-header">
                <div class="leftside-content-header">
                    <ul class="breadcrumbs">
                        <li><i class="fa fa-sitemap" aria-hidden="true"></i><a href="<?php echo site_url('admin');?>">Dashboard</a></li>
                        <li><a>Detail Member</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Detail Member</h3>
                            <?php
                            if($data[0]->member_type == 2){
                                if($data[0]->status == 1){
                                    $active = 'btn-primary disabled';
                                    $label = 'Activated';
                                }else {
                                    $active = 'btn-danger';
                                    $label = 'Make Active';
                                }
                                ?>
                                <div class="panel-actions">
                                    <a href="javascript:void(0);" class="btn <?php echo $active;?> active-member" style="margin-top: 10px;"><?php echo $label;?></a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="panel-content">
                            <div class="tabs">
                                <ul class="nav nav-tabs ">
                                    <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">Member Info</a></li>
                                    <li class=""><a href="#invoice" data-toggle="tab" aria-expanded="true">Invoice</a></li>
                                    <?php
                                    if($data[0]->member_type == 2){
                                        ?>
                                        <li class=""><a href="#message" data-toggle="tab" aria-expanded="true">Message</a></li>
                                        <li class=""><a href="#product" data-toggle="tab" aria-expanded="true">Product</a></li>
                                        <li class=""><a href="#news" data-toggle="tab" aria-expanded="true">News</a></li>
                                        <li class=""><a href="#blog" data-toggle="tab" aria-expanded="true">Blog</a></li>
                                        <li class=""><a href="#event" data-toggle="tab" aria-expanded="true">Event</a></li>
                                        <li class=""><a href="#video" data-toggle="tab" aria-expanded="true">Video</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="info">
                                        <br />
                                        <div>
                                            <div class="profile-photo">
                                                <?php
                                                if(!empty($data[0]->photo)){
                                                    ?>
                                                    <img alt="Jane Doe" src="<?php echo base_url().$data[0]->photo;?>" class="img-responsive">
                                                    <?php
                                                }else {
                                                    ?>
                                                    <img alt="Jane Doe" src="<?php echo base_url();?>assets/back/theme/images/no-avatar.png" class="img-responsive">
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="user-header-info">
                                                <h2 class="user-name"><?php echo $data_member[0]->name;?></h2>
                                                <h5 class="user-position"><?php echo $this->m_member_back->GetMemberType($data[0]->member_type);?></h5>
                                            </div>
                                        </div>
                                        <br /><br />
                                        <div class="p-info">
                                            <ul>
                                                <li><span>No Anggota</span> 
                                                    <?php
                                                    if(empty($data_member[0]->no_anggota)){
                                                        echo '-';
                                                    }else {
                                                        echo $data_member[0]->no_anggota;
                                                    }
                                                    ?>
                                                </li>
                                                <li><span>Email</span> <?php echo $data[0]->email;?></li>
                                                <li><span>Phone</span> <?php echo $data_member[0]->phone;?></li>
                                                <li><span>Datecreated</span> <?php echo $this->waktu->WestConvertion($data[0]->datecreated);?></li>
                                                <li><span>Address</span> <?php echo $data_member[0]->address;?></li>
                                            </ul>
                                            <ul>
                                                <li><span>Province</span><?php echo $this->m_member->GetLocation('provinces',array('id'=>$data_member[0]->province_id));?></li>
                                                <li><span>Regencie</span><?php echo $this->m_member->GetLocation('regencies',array('id'=>$data_member[0]->regencie_id));?></li>
                                                <li><span>District</span><?php echo $this->m_member->GetLocation('districts',array('id'=>$data_member[0]->district_id));?></li>
                                                <li><span>Village</span><?php echo $this->m_member->GetLocation('villages',array('id'=>$data_member[0]->village_id));?></li>
                                                <li><span>Zip Code</span><?php echo $data_member[0]->zip_code;?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="invoice">
                                        <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="30px">No</th>
                                                    <th>Invoice No</th>
                                                    <th>Total</th>
                                                    <th>Datecreated</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $arr = $this->crud_global->ShowTableNew('tbl_invoice',array('member_id'=>$id,'status !='=>0));
                                                if(is_array($arr)){
                                                    $no=1;
                                                    foreach ($arr as $key => $row) {
                                                        $url = site_url('member_back/invoice_detail/'.$row->invoice_id);
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $no;?></td>
                                                            <td><a href="<?php echo $url;?>"><?php echo $row->invoice_no;?></a></td>
                                                            <td><?php echo $this->general->NumberMoney($row->total);?></td>
                                                            <td><?php echo $this->waktu->WestConvertion($row->datecreated);?></td>
                                                            <td style="text-align: center;"><?php echo $this->general->GetStatusInvoice($row->status);?></td>
                                                        </tr>
                                                        <?php
                                                        $no++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="message">
                                        <div class="tabs">
                                            <ul class="nav nav-tabs ">
                                                <li class="active"><a href="#inbox" data-toggle="tab" aria-expanded="true">Inbox</a></li>
                                                <li ><a href="#outbox" data-toggle="tab" aria-expanded="true">Outbox</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade active in" id="inbox">
                                                    <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="30px">No.</th>
                                                                <th>Subject</th>
                                                                <th>Pengirim</th>
                                                                <th>Datecreated</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $arr_masuk = $this->crud_global->ShowTableNew('tbl_message_receive',array('user_id'=>$id));
                                                            if(is_array($arr_masuk)){
                                                                $no=1;
                                                                foreach ($arr_masuk as $key => $row) {

                                                                    $value = $this->crud_global->ShowTableNew('tbl_message',array('message_id'=>$row->message_id));
                                                                    $message_alias = strtolower(str_replace(' ', '-', $value[0]->subject));
                                                                    $message_url = base_url().'member_back/message_detail/'.$row->message_id.'/'.$message_alias;

                                                                    ?>
                                                                    <tr>
                                                                        <td width="50px"><?php echo $no;?></td>
                                                                        <td><a href="<?php echo $message_url;?>" class="green detail-message" data-toggle="modal" data-target="#default-modal"><?php echo $value[0]->subject;?></a></td>
                                                                        <td><?php echo $this->m_member->ShowSeller($value[0]->user_type,$value[0]->user_id);?></td>
                                                                        <td><?php echo $this->waktu->BlogDate4($value[0]->datecreated);?></td>
                                                                    </tr>
                                                                    <?php
                                                                    $no++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane fade" id="outbox">
                                                    <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="30px">No.</th>
                                                                <th>Subject</th>
                                                                <th>Penerima</th>
                                                                <th>Datecreated</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $arr_keluar = $this->crud_global->ShowTableNew('tbl_message',array('problem_type'=>'product','user_id'=>$id));
                                                            if(is_array($arr_keluar)){
                                                                $no=1;
                                                                foreach ($arr_keluar as $key => $row) {

                                                                    $value = $this->crud_global->ShowTableNew('tbl_message_receive',array('message_id'=>$row->message_id));
                                                                    $message_alias = strtolower(str_replace(' ', '-', $row->subject));
                                                                    $message_url = base_url().'member_back/message_detail/'.$row->message_id.'/'.$message_alias;

                                                                    ?>
                                                                    <tr>
                                                                        <td width="50px"><?php echo $no;?></td>
                                                                        <td><a href="<?php echo $message_url;?>" class="green" data-toggle="modal" data-target="#default-modal"><?php echo $row->subject;?></a></td>
                                                                        <td><?php echo $this->m_member->ShowSeller($value[0]->user_type,$value[0]->user_id);?></td>
                                                                        <td><?php echo $this->waktu->BlogDate4($row->datecreated);?></td>
                                                                    </tr>
                                                                    <?php
                                                                    $no++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if($data[0]->member_type == 2){
                                        ?>
                                        <div class="tab-pane fade" id="product">
                                            <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="30px">No</th>
                                                        <th>Product</th>
                                                        <th>Datecreated</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $arr_product = $this->crud_global->ShowTableNew('tbl_product',array('created_by'=>$id,'user_type'=>2,'status !='=>0));
                                                    if(is_array($arr_product)){
                                                        $no=1;
                                                        foreach ($arr_product as $key => $row) {
                                                            $product_name = $this->m_product->GetProductName($row->product_id);
                                                            $product_alias = strtolower(str_replace(' ', '-', $product_name));
                                                            $url = site_url('product/'.$row->product_id.'/'.$product_alias);

                                                            ?>
                                                            <tr>
                                                                <td><?php echo $no;?></td>
                                                                <td><a href="<?php echo $url;?>" target="_blank"><?php echo $product_name;?></a></td>
                                                                <td><?php echo $this->waktu->WestConvertion($row->datecreated);?></td>
                                                                <td><?php echo $this->general->GetStatus($row->status);?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="news">
                                            <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="30px">No</th>
                                                        <th>News</th>
                                                        <th>Datecreated</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $arr_news = $this->crud_global->ShowTableNew('tbl_post',array('post_category_id'=>6,'user_type'=>2,'created_by'=>$id,'status'=>1),array('datecreated'=>'desc'));
                                                    if(is_array($arr_news)){
                                                        $no=1;
                                                        foreach ($arr_news as $key => $row) {
                                                            $url = base_url().'post/'.$row->post_category_id.'/'.$row->post_id.'/'.$row->post_alias;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $no;?></td>
                                                                <td><a href="<?php echo $url;?>" target="_blank"><?php echo $row->post;?></a></td>
                                                                <td><?php echo $this->waktu->WestConvertion($row->datecreated);?></td>
                                                                <td><?php echo $this->general->GetStatus($row->status);?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="blog">
                                            <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="30px">No</th>
                                                        <th>Blog</th>
                                                        <th>Datecreated</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $arr_blog = $this->crud_global->ShowTableNew('tbl_post',array('post_category_id'=>1,'user_type'=>2,'created_by'=>$id,'status'=>1),array('datecreated'=>'desc'));
                                                    if(is_array($arr_blog)){
                                                        $no=1;
                                                        foreach ($arr_blog as $key => $row) {
                                                            $url = base_url().'post/'.$row->post_category_id.'/'.$row->post_id.'/'.$row->post_alias;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $no;?></td>
                                                                <td><a href="<?php echo $url;?>" target="_blank"><?php echo $row->post;?></a></td>
                                                                <td><?php echo $this->waktu->WestConvertion($row->datecreated);?></td>
                                                                <td><?php echo $this->general->GetStatus($row->status);?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="event">
                                            <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="30px">No</th>
                                                        <th>Event</th>
                                                        <th>Datecreated</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $arr_event = $this->crud_global->ShowTableNew('tbl_post',array('post_category_id'=>7,'user_type'=>2,'created_by'=>$id,'status'=>1),array('datecreated'=>'desc'));
                                                    if(is_array($arr_event)){
                                                        $no=1;
                                                        foreach ($arr_event as $key => $row) {
                                                            $url = base_url().'post/'.$row->post_category_id.'/'.$row->post_id.'/'.$row->post_alias;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $no;?></td>
                                                                <td><a href="<?php echo $url;?>" target="_blank"><?php echo $row->post;?></a></td>
                                                                <td><?php echo $this->waktu->WestConvertion($row->datecreated);?></td>
                                                                <td><?php echo $this->general->GetStatus($row->status);?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="video">
                                            <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="30px">No</th>
                                                        <th>Video</th>
                                                        <th>Datecreated</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $arr_video = $this->crud_global->ShowTableNew('tbl_post',array('post_category_id'=>5,'user_type'=>2,'created_by'=>$id,'status'=>1),array('datecreated'=>'desc'));
                                                    if(is_array($arr_video)){
                                                        $no=1;
                                                        foreach ($arr_video as $key => $row) {
                                                            $url = base_url().'post/'.$row->post_category_id.'/'.$row->post_id.'/'.$row->post_alias;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $no;?></td>
                                                                <td><a href="<?php echo $url;?>" target="_blank"><?php echo $row->post;?></a></td>
                                                                <td><?php echo $this->waktu->WestConvertion($row->datecreated);?></td>
                                                                <td><?php echo $this->general->GetStatus($row->status);?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    }
                                    ?>
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

    <?php echo $this->Templates->Footer();?>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.data-table').DataTable();

            $('#default-modal').on('show.bs.modal', function (event) {

              var button = $(event.relatedTarget) // Button that triggered the modal

              var modal = $(this);
              modal.find('.modal-content').load(button.context.href+"/"+Math.random(), function(responseTxt, statusTxt, xhr){
                   responseTxt.async = true;
                    if(statusTxt == "success"){
                      $(this).fadeIn();
                    }else
                    if(statusTxt == "error"){
                        alert("Error: " + xhr.status + ": " + xhr.statusText);
                    }
                });
            });

            $(".active-member").click(function(){
                $(this).button('loading');
                $.ajax({
                    type: "POST",
                    url:  '<?php echo site_url("member_back/activated");?>'+"/"+Math.random(),
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
