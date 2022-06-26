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
                        <li><a>Edit Member</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Edit Member</h3>
                            <div class="panel-actions">
                                <ul>
                                    <li class="action"><span class="fa fa-refresh action" onclick="reload_table()" aria-hidden="true"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('member_back/edit');?>" data-redirect="<?php echo site_url('admin/member');?>">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $data_member[0]->name;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Member Type<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_member_back->SelectMemberType($data[0]->member_type);?>
                                    </div>
                                </div>
                                <div class="form-group" id="anggota">
                                    <label for="name" class="col-sm-2 control-label">No Anggota<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="no_anggota" name="no_anggota" value="<?php echo $data_member[0]->no_anggota;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Email<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="email" name="email" class="form-control" value="<?php echo $data[0]->email;?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Password<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="password" class="form-control"  value="<?php echo $data[0]->password;?>"></input>
                                        <input type="password" name="new_password" class="form-control" id="password" data-required="false"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Phone<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="phone" class="form-control" value="<?php echo $data_member[0]->phone;?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Province</label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_member->SelectProvince($data_member[0]->province_id);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Regencie</label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_member->SelectRegencie($data_member[0]->regencie_id,array('province_id'=>$data_member[0]->province_id));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">District</label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_member->SelectDistrict($data_member[0]->district_id,array('regency_id'=>$data_member[0]->regencie_id));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Village</label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_member->SelectVillage($data_member[0]->village_id,array('district_id'=>$data_member[0]->district_id));?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" name="address" data-required="false"><?php echo $data_member[0]->address;?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Zip Code</label>
                                    <div class="col-sm-8 col-md-4">
                                        <input type="text" name="zip_code" class="form-control" data-required="false" value="<?php echo $data_member[0]->zip_code;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Photo</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control" name="member_photo" id="member_photo_val" value="<?php echo $data[0]->photo;?>" />
                                            <input type="text" id="member_photo" class="form-control" data-required="false" value="<?php echo $data[0]->photo;?>"></input>
                                            <span class="input-group-btn">
                                            <a data-fancybox-type="iframe" class="btn btn-primary iframe-btn" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=member_photo" >Browse</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <?php
                                        if(!empty($data[0]->photo)){
                                            ?>
                                            <div id="box-content_member_photo">
                                                <img id="member_photo_prev" src="<?php echo base_url().$data[0]->photo;?>" class="img-responsive" />
                                            </div>
                                            <?php
                                        }else {
                                            ?>
                                            <div id="box-content_member_photo">
                                                <img id="member_photo_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" />
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Status<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php $this->general->SelectStatus();?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-9">
                                        <button type="submit" name="submit" class="btn btn-primary" id="btn-submit" data-loading-text="please wait..">Submit</button>
                                    </div>
                                </div>
                            </form>
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

            $(".selectajax").on("change",function(){
                var val = $(this).val();
                var type = $(this).attr('data-type');
                var data_id = $(this).attr('data-id');

                if(type == 'regencies'){
                    $("#districts").html("");
                    $("#villages").html("");
                }else if(type == 'districts'){
                    $("#villages").html("");
                }
                $.ajax({
                 type: 'POST',
                 url: '<?php echo site_url("member/select_ajax");?>/'+Math.random(),
                 data: {
                  val : val,
                  type : type,
                  data_id : data_id
                 },
                 success: function (data) {
                    $("#"+type).html(data);
                 }
                });
            });

            <?php
            if($data[0]->member_type == 2){
                ?>
                $("#anggota").show();
                <?php
            }else {
                ?>
                $("#anggota").hide();
                <?php
            }
            ?>
            $("#member_type").on("change",function(){
                var val = $(this).val();
                if(val == 2){
                    $("#anggota").show();
                }else {
                    $("#anggota").hide();
                }
            });
        });
    </script>


</body>
</html>
