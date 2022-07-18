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
                        <li><a>Add Product Category</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('producted/add_product_category');?>" data-redirect="<?php echo site_url('admin/product_category');?>">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Add Product Category</h3>
                            <div class="panel-actions">
                                <button name="submit" type="submit" class="btn btn-primary pull-right" style="margin-right:10px;margin-top:10px;" id="btn-submit">Save</button>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="tabs">
                                <ul class="nav nav-tabs ">
                                    <li class="active"><a href="#general" data-toggle="tab" aria-expanded="true">General</a></li>
                                    <li class=""><a href="#data" data-toggle="tab" aria-expanded="true">Data</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="general">
                                        <div class="tabs">
                                            <ul class="nav nav-tabs ">
                                                <?php
                                                if(is_array($arr_lang)){
                                                    foreach ($arr_lang as $key => $row) {
                                                        if($key == 0 ){
                                                            $active = 'active';
                                                        }else {
                                                            $active = false;
                                                        }
                                                        ?>
                                                        <li class="<?php echo $active;?>"><a href="#<?php echo $row->language_code;?>" data-toggle="tab" aria-expanded="true"><?php echo $row->language_title;?></a></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                            <div class="tab-content">
                                                <?php
                                                if(is_array($arr_lang)){
                                                    foreach ($arr_lang as $key => $row) {
                                                        if($key == 0 ){
                                                            $active = 'active in';
                                                        }else {
                                                            $active = false;
                                                        }
                                                        ?>
                                                        <div class="tab-pane fade <?php echo $active;?>" id="<?php echo $row->language_code;?>">
                                                            <div class="form-horizontal">
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Category Name *</label>
                                                                    <div class="col-md-10 parent-block">
                                                                        <input type="text" class="form-control" name="product_category_name_<?php echo $row->language_id;?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" id="content<?php echo $row->language_id;?>" name="product_category_des_<?php echo $row->language_id;?>" data-required="false"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Meta Title</label>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control" name="product_category_meta_title_<?php echo $row->language_id;?>" data-required="false">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Meta Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" name="product_category_meta_des_<?php echo $row->language_id;?>" data-required="false"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Meta Keywords</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" name="product_category_meta_keywords_<?php echo $row->language_id;?>" data-required="false"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="data">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Parent</label>
                                                <div class="col-md-10 parent-block">
                                                    <?php echo $this->m_product->SelectParentProductCategory();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Cover Image</label>
                                                <div class="col-md-10">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <div class="input-group">
                                                              <input type="text" class="form-control" id="cover_image" data-required="false">
                                                              <input type="hidden" class="form-control" name="cover_image" id="cover_image_val" >
                                                              <span class="input-group-btn">
                                                                <a data-fancybox-type="iframe" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=cover_image" class="btn btn-primary iframe-btn" type="button">Browse</a>
                                                              </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div id="box-content_cover_image">
                                                                <img id="cover_image_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" />
                                                            </div>
                                                            <button class="btn btn-danger btn-block btn-del-img" data-id="cover_image"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Thumbnail Image</label>
                                                <div class="col-md-10">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <div class="input-group">
                                                              <input type="text" class="form-control" id="thumbnail_image" data-required="false">
                                                              <input type="hidden" class="form-control" name="thumbnail_image" id="thumbnail_image_val" >
                                                              <span class="input-group-btn">
                                                                <a data-fancybox-type="iframe" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=thumbnail_image" class="btn btn-primary iframe-btn" type="button">Browse</a>
                                                              </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div id="box-content_thumbnail_image">
                                                                <img id="thumbnail_image_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" />
                                                            </div>
                                                            <button class="btn btn-danger btn-block btn-del-img" data-id="thumbnail_image"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Friendly Url</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="product_category_url" class="form-control" data-required="false">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Sort</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="number" name="sort_id" class="form-control" value="0">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Status</label>
                                                <div class="col-sm-10 parent-block">
                                                    <?php $this->general->SelectStatus();?>
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
        </div>
        
    </div>
</div>

    <?php echo $this->Templates->Footer();?>
    <?php echo $this->m_pages->ScriptEditor();?>


</body>
</html>
