<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>
</head>

<body>
<div class="wrap">
    

    <div class="modal fade" id="default-modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" style="display: none;">
        <div class="modal-dialog" role="document">
            <form id="inline-validation" method="POST" class="form-stripe form-submit" novalidate="novalidate" data-button="#btn-message" action="<?php echo site_url('member_back/send_message');?>" data-redirect="<?php echo site_url('member_back/message/'.$id);?>">
                <input type="hidden" name="user_id" value="<?php echo $admin_id;?>">
                <input type="hidden" name="user_type" value="1">
                <input type="hidden" name="user_type_receive" value="2">
                <input type="hidden" name="parent_id" value="<?php echo $id;?>">
                <input type="hidden" name="problem_type" value="product">
                <input type="hidden" name="reply" value="1">
                <input type="hidden" name="message_receive" value="<?php echo $data_message[0]->user_id;?>">
                <input type="hidden" name="problem_id" value="<?php echo $data_message[0]->problem_id;?>">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Reply</h4>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" name="message" placeholder="Your Message" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="color:#000;">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary" id="btn-message">Send</button>
                  </div>
                </div>
            </form>
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
                        <li><a>Message</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Message Detail</h3>
                            <div class="panel-actions" style="margin-top: 10px;">
                                <a href="<?php echo site_url('admin/message');?>" class="btn btn-warning active-invoice" ><i class="fa fa-reply"></i> Back</a>
                                <?php
                                if($admin_id != $data_message[0]->user_id){
                                    ?>
                                    <a href="#" class="btn btn-primary active-invoice" data-toggle="modal" data-target="#default-modal">Reply</a>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="panel-content">
                            <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                <tr>
                                        <th width="150px">Pengirim</th>
                                        <td width="20px">:</td>
                                        <td><?php echo $this->m_member->ShowSeller($data_message[0]->user_type,$data_message[0]->user_id);?></td>
                                    </tr>
                                    <tr>
                                        <th width="150px">Tanggal</th>
                                        <td width="20px">:</td>
                                        <td><?php echo $this->waktu->BlogDate4($data_message[0]->datecreated);?></td>
                                    </tr>
                                    <tr>
                                        <th width="150px">Product</th>
                                        <td width="20px">:</td>
                                        <td><?php echo $this->crud_global->GetField('tbl_product_data',array('product_id'=>$data_message[0]->problem_id,'language_id'=>$lang_id),'product_name');?></td>
                                    </tr>
                                    <tr>
                                        <th width="150px">Subject</th>
                                        <td width="20px">:</td>
                                        <td><?php echo $data_message[0]->subject;?></td>
                                    </tr>
                                    <tr>
                                        <th width="150px">Pesan</th>
                                        <td width="20px">:</td>
                                        <td><?php echo $data_message[0]->message;?></td>
                                    </tr>
                            </table>
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
            $("#form-message").validate({
                rules: {
                  subject: {
                    required: true
                  },
                  message: {
                    required: true
                  }
                },
              submitHandler: function(form) {
                  $.ajax({
                   type: "POST",
                   url:  $(form).attr('action'),
                   data: $(form).serialize(),
                   dataType: 'json',
                   async:true,
                   beforeSend:function(){
                    $('#btn-message').button('loading');
                   },
                   success: function (data) {
                       var output = data.output;
                       console.log(output);
                       if(output == 'true'){
                            $('#btn-message').button('reset');
                            swal({
                                title: 'Thank You!',
                                text: 'Your message will be processed as soon as possible',
                                type: 'success'
                            },function(){
                                location.reload();
                            });
                       }else {
                            swal({
                                title: 'Opps Sorry!',
                                text: 'Failed Send Message',
                                type: 'error'
                            },function(){
                                $('#btn-message').button('reset');
                                $("#form-message .form-control").val("");
                            });
                       }
                   }
                  });
                  return false;
              }
          });
        });
    </script>
</body>
</html>
