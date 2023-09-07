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
                        <li><a>Edit Admin Access</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h4 class="section-subtitle">Edit Admin Access</h4>
                            <div class="panel-actions">
                            </div>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('admin_access/edit');?>" data-redirect="<?php echo site_url('admin/admin_access');?>">
                                <input type="hidden" name="id" value="<?php echo $row[0]->admin_group_id;?>">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Admin Group<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="admin_group_name" name="admin_group_name" value="<?php echo $row[0]->admin_group_name;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Access<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_admin_access->SelectAccess($row[0]->admin_group_id);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Status<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php $this->general->SelectStatus($row[0]->status);?>
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

</body>
</html>
