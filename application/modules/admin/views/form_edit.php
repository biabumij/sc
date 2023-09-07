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
                        <li><a>Edit Profile</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Edit Profile</h3>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('admin/edit');?>" data-redirect="<?php echo site_url('admin/dashboard');?>">
                                <input type="hidden" name="id" value="<?php echo $row[0]->admin_id;?>">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="admin_name" name="admin_name" value="<?php echo $row[0]->admin_name;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Admin Group<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_admin->SelectAdminGroup($row[0]->admin_group_id);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Password<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="password" name="admin_password" class="form-control" id="password" data-required="false"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Email<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="email" name="admin_email" class="form-control" value="<?php echo $row[0]->admin_email;?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Photo</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control" name="admin_photo" id="admin_photo_val" value="<?php echo $row[0]->admin_photo;?>" />
                                            <input type="text" id="admin_photo" class="form-control" data-required="false" value="<?php echo $row[0]->admin_photo;?>" />
                                            <span class="input-group-btn">
                                            <a data-fancybox-type="iframe" class="btn btn-primary iframe-btn" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=admin_photo" >Browse</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <?php
                                        if(!empty($row[0]->admin_photo)){
                                            ?>
                                            <div id="box-content_admin_photo">
                                                <img id="admin_photo_prev" src="<?php echo base_url().$row[0]->admin_photo;?>" class="img-responsive" />
                                            </div>
                                            <?php
                                        }else {
                                            ?>
                                            <div id="box-content_admin_photo">
                                                <img id="admin_photo_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" />
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
                                        <button type="submit" name="submit" class="btn btn-success" id="btn-submit" data-loading-text="please wait.." style="font-weight:bold; border-radius:10px;">Kirim</button>
                                    </div>
                                </div>
                            </form>
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
            load_table("<?php echo site_url($row[0]->menu_alias.'/table');?>");
        });
    </script>

</body>
</html>
