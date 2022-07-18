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
                        <li><a>Edit Post</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Edit Post</h3>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('posted/edit_post');?>" data-redirect="<?php echo site_url('posted/detail/'.$post_category_id);?>">
                                <input type="hidden" name="post_category_id" value="<?php echo $post_category_id;?>">
                                <input type="hidden" name="id" value="<?php echo $id;?>">
                                <input type="hidden" name="user_type" value="1">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Post<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="post" name="post" value="<?php echo $row[0]->post;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Parent<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_post->SelectParent($post_category_id,$row[0]->parent_id);?>
                                    </div>
                                </div>
                                <?php
                                $order_by = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$post_category_id),'order_by');
                                    if($order_by == 1){
                                        ?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Order <span class="required" aria-required="true">*</span></label>
                                            <div class="col-sm-8 parent-block">
                                                <?php echo $this->crud_global->OrderInput('tbl_post',$post_category_id,$row[0]->order_id);?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Status<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php $this->general->SelectStatus($row[0]->status);?>
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
        
    </div>
</div>

	<?php echo $this->Templates->Footer();?>

</body>
</html>
