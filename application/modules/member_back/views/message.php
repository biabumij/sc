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
                        <li><a><?php echo $row[0]->menu_name;?></a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle"><?php echo $row[0]->menu_name;?></h3>
                            <div class="panel-actions">
                                <ul>
                                    <li class="action"><span class="fa fa-refresh action" onclick="reload_table()" aria-hidden="true"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="tabs">
                                <ul class="nav nav-tabs ">
                                    <li class="active"><a href="#inbox" data-toggle="tab" aria-expanded="true">Inbox</a></li>
                                    <li><a href="#outbox" data-toggle="tab" aria-expanded="true">Outbox</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="inbox">
                                        <table id="basic-table" class="table-default table table-striped nowrap table-hover" cellspacing="0" width="100%">
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
                                                $arr_masuk = $this->crud_global->ShowTableNew('tbl_message_receive',array('user_id'=>$admin_id),array('datecreated','desc'));
                                                if(is_array($arr_masuk)){
                                                    $no=1;
                                                    foreach ($arr_masuk as $key => $row) {

                                                        $value = $this->crud_global->ShowTableNew('tbl_message',array('message_id'=>$row->message_id));
                                                        $message_alias = strtolower(str_replace(' ', '-', $value[0]->subject));
                                                        $message_url = base_url().'member_back/message/'.$row->message_id;

                                                        ?>
                                                        <tr>
                                                            <td width="50px"><?php echo $no;?></td>
                                                            <td><a href="<?php echo $message_url;?>" class="green detail-message"><?php echo $value[0]->subject;?></a></td>
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
                                        <table id="basic-table" class="table-default table table-striped nowrap table-hover" cellspacing="0" width="100%">
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
                                                $arr_keluar = $this->crud_global->ShowTableNew('tbl_message',array('user_id'=>$admin_id,'problem_type'=>'product'));
                                                if(is_array($arr_keluar)){
                                                    $no=1;
                                                    foreach ($arr_keluar as $key => $row) {

                                                        $value = $this->crud_global->ShowTableNew('tbl_message_receive',array('message_id'=>$row->message_id));
                                                        $message_alias = strtolower(str_replace(' ', '-', $row->subject));
                                                        $message_url = base_url().'member_back/message/'.$row->message_id.'/'.$message_alias;

                                                        ?>
                                                        <tr>
                                                            <td width="50px"><?php echo $no;?></td>
                                                            <td><a href="<?php echo $message_url;?>" class="green" ><?php echo $row->subject;?></a></td>
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
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

	<?php echo $this->Templates->Footer();?>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('.table-default').DataTable();
        });
    </script>


</body>
</html>
