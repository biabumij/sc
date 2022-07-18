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
                        <li><a>Add Product</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('producted/add_product');?>" data-redirect="<?php echo site_url('admin/product');?>">
                    <input type="hidden" name="user_type" value="1">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Add Product</h3>
                            <div class="panel-actions">
                                <button name="submit" type="submit" class="btn btn-primary pull-right" style="margin-right:10px;margin-top:10px;" id="btn-submit">Save</button>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="tabs">
                                <ul class="nav nav-tabs ">
                                    <li class="active"><a href="#general" data-toggle="tab" aria-expanded="true">General</a></li>
                                    <li class=""><a href="#data" data-toggle="tab" aria-expanded="true">Data</a></li>
                                    <!-- <li class=""><a href="#tab-four" role="tab" data-toggle="tab" aria-expanded="false">Spesification</a></li> -->
                                    <li class=""><a href="#tab-five" role="tab" data-toggle="tab" aria-expanded="false">Gallery</a></li>
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
                                            <div class="tab-content" style="padding: 0px 20px;">
                                                <?php
                                                if(is_array($arr_lang)){
                                                    foreach ($arr_lang as $key => $row) {
                                                        if($key == 0 ){
                                                            $active = 'active in';
                                                        }else {
                                                            $active = false;
                                                        }
                                                        ?>
                                                        <br />
                                                        <div class="tab-pane fade <?php echo $active;?>" id="<?php echo $row->language_code;?>">
                                                            <div class="form-horizontal">
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Product Name *</label>
                                                                    <div class="col-md-10 parent-block">
                                                                        <input type="text" class="form-control" name="product_name_<?php echo $row->language_id;?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Product SubName</label>
                                                                    <div class="col-md-10 parent-block">
                                                                        <input type="text" class="form-control" name="product_subname_<?php echo $row->language_id;?>" data-required="false">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Short Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" id="content<?php echo $row->language_id;?>" name="product_short_des_<?php echo $row->language_id;?>" data-required="false"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" id="content<?php echo $row->language_id;?>" name="product_des_<?php echo $row->language_id;?>" data-required="false"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Meta Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" name="product_meta_des_<?php echo $row->language_id;?>" data-required="false"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Product Keywords</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" name="product_keywords_<?php echo $row->language_id;?>" data-required="false"></textarea>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Product Tags</label>
                                                                    <div class="col-md-10 parent-block">
                                                                        <input type="text" class="form-control" name="product_tags_<?php echo $row->language_id;?>" data-required="false">
                                                                    </div>
                                                                </div> -->
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
                                                <label class="col-sm-2 control-label">Product Code *</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="product_code" class="form-control disabled" value="<?php echo $this->m_product->GetProductCode();?>" disabled="">
                                                    <input type="hidden" name="product_code" class="form-control" value="<?php echo $this->m_product->GetProductCode();?>">
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label class="col-sm-2 control-label">Product Barcode</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="product_barcode" class="form-control" data-required="false">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Product Available</label>
                                                <div class="col-sm-10 parent-block">
                                                    <div class="col-md-4">                                    
                                                        <label class="check"><input type="radio" class="iradio" name="product_available" checked="checked" value="1" /> Online</label>
                                                    </div>
                                                    <div class="col-md-4">                                    
                                                        <label class="check"><input type="radio" class="iradio" name="product_available" value="2" /> Offline</label>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-group">
                                                <label class="col-sm-2 control-label">Manufactures</label>
                                                <div class="col-md-10 parent-block">
                                                    
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Category</label>
                                                <div class="col-md-10 parent-block">
                                                    <?php echo $this->m_product->SelectProductCategory();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Asal</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="asal" class="form-control" data-required="false">
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label class="col-sm-2 control-label">Related Products</label>
                                                <div class="col-md-10 parent-block">
                                                    <?php echo $this->m_product->SelectRelatedProducts();?>
                                                </div>
                                            </div> -->
                                            <!-- <div class="form-group">
                                                <label class="col-sm-2 control-label">Special Products</label>
                                                <div class="col-md-10 parent-block">
                                                    <?php echo $this->m_product->SelectProductSpecial();?>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Unit Type</label>
                                                <div class="col-md-10 parent-block">
                                                    <?php echo $this->m_product->SelectUnitType();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Price</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="price" class="form-control" data-required="false">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Quantity</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="quantity" class="form-control" data-required="false">
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
                                    <div class="tab-pane" id="tab-four">
                                        <input type="hidden" name="val_attr" id="val_attr">
                                        <input type="hidden" name="val_del" id="data_del">
                                        <table class="table table-hover table-bordered" id="table-attr">
                                            <thead>
                                                <tr>
                                                    <th>Spesification</th>
                                                    <th>Value</th>
                                                    <th width="100px">Sort</th>
                                                    <th width="50px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-info btn-plus-attr" data-attr="0"><i class="fa fa-plus" ></i></a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab-five">
                                        <input type="hidden" name="val_gallery" id="val_gallery">
                                        <table class="table table-hover table-bordered" id="table-gallery">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th width="400px">Caption</th>
                                                    <th width="100px">Sort</th>
                                                    <th width="50px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-info btn-plus-gallery" data-gallery="0"><i class="fa fa-plus" ></i></a>
                                                    </td>
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
</div>

    <?php echo $this->Templates->Footer();?>
    <?php echo $this->m_pages->ScriptEditor();?>

    <script type="text/javascript">
            function DelAttr(id)
            {
                var data_attr = parseInt($('.btn-plus-attr').attr('data-attr'));
                var data_now = data_attr - 1;
                // $("#val_attr").val(data_now);
                $("#data_del").val(id);
                $('.btn-plus-attr').attr('data-attr',data_now);
                $('tr.attr'+id).remove(); 
            }   

            function DelGallery(id)
            {
                var data_gallery = parseInt($('.btn-plus-gallery').attr('data-gallery'));
                var data_now = data_gallery - 1;
                // $("#val_attr").val(data_now);
                // $("#data_del").val(id);
                $('.btn-plus-gallery').attr('data-gallery',data_now);
                $('tr.gallery'+id).remove(); 
            } 

            
    
            $(document).ready(function(){
                $('.btn-plus-attr').click(function(){
                    var data_attr = parseInt($(this).attr('data-attr'));
                    var data_now = data_attr + 1;
                    $("#table-attr tbody").append('<tr class="attr'+data_now+'"><td><input type="text" name="attribute_name'+data_now+'" class="form-control"></td><td><textarea class="form-control" name="attribute_des'+data_now+'" rows="6"></textarea></td><td><input type="text" name="attribute_sort'+data_now+'" class="form-control"></td><td><a href="javascript:void(0);" onclick="DelAttr('+data_now+')"class="btn btn-danger btn-del-attr" data-del="'+data_now+'"><i class="fa fa-minus"></i></a></td></tr>');
                    $(this).attr('data-attr',data_now);
                    $("#val_attr").val(data_now);
                });

                $('.btn-plus-gallery').click(function(){
                    var gallery = parseInt($(this).attr('data-gallery'));
                    var data_now = gallery + 1;

                    $("#table-gallery tbody").append('<tr class="gallery'+data_now+'"><td><div class="input-group"><input type="text" class="form-control" id="gallery_image'+data_now+'" data-required="false"><input type="hidden" class="form-control" name="gallery_image'+data_now+'" id="gallery_image'+data_now+'_val" ><span class="input-group-btn"><a data-fancybox-type="iframe" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=gallery_image'+data_now+'" class="btn btn-primary iframe-btn" type="button">Browse</a></span></div><br /><div id="box-content_gallery_image'+data_now+'"> <img id="gallery_image'+data_now+'_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" /></div></td><td><textarea class="form-control" name="gallery_des'+data_now+'" rows="6"></textarea></td><td><input type="text" name="gallery_sort'+data_now+'" class="form-control"></td><td><a href="javascript:void(0);" onclick="DelGallery('+data_now+')" class="btn btn-danger btn-del-gallery" data-del="'+data_now+'"><i class="fa fa-minus"></i></a></td></tr>');
                    $(this).attr('data-gallery',data_now);
                    $("#val_gallery").val(data_now);
                });

            });
        </script>

</body>
</html>
