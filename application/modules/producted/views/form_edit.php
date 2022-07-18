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
                        <li><a>Edit Product</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('producted/edit_product');?>" data-redirect="<?php echo site_url('admin/product');?>">
                    <input type="hidden" name="id" value="<?php echo $id;?>">
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
                                            <div class="tab-content">
                                                <?php
                                                if(is_array($arr_lang)){
                                                    foreach ($arr_lang as $key => $row) {
                                                        if($key == 0 ){
                                                            $active = 'active in';
                                                        }else {
                                                            $active = false;
                                                        }
                                                        $product_name = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$id,'language_id'=>$row->language_id),'product_name');

                                                        $product_subname = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$id,'language_id'=>$row->language_id),'product_subname');

                                                        $product_short_des = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$id,'language_id'=>$row->language_id),'product_short_des');

                                                        $product_des = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$id,'language_id'=>$row->language_id),'product_des');

                                                        $product_meta_des = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$id,'language_id'=>$row->language_id),'product_meta_des');

                                                        $product_keywords = $this->crud_global->GetField('tbl_product_data',array('product_id'=>$id,'language_id'=>$row->language_id),'product_keywords');
                                                        ?>
                                                        <div class="tab-pane fade <?php echo $active;?>" id="<?php echo $row->language_code;?>">
                                                            <div class="form-horizontal">
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Product Name *</label>
                                                                    <div class="col-md-10 parent-block">
                                                                        <input type="text" class="form-control" name="product_name_<?php echo $row->language_id;?>" value="<?php echo $product_name;?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Product SubName</label>
                                                                    <div class="col-md-10 parent-block">
                                                                        <input type="text" class="form-control" name="product_subname_<?php echo $row->language_id;?>" data-required="false" value="<?php echo $product_subname;?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Short Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" id="content<?php echo $row->language_id;?>" name="product_short_des_<?php echo $row->language_id;?>" data-required="false"><?php echo $product_short_des;?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" id="content<?php echo $row->language_id;?>" name="product_des_<?php echo $row->language_id;?>" data-required="false"><?php echo $product_des;?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Meta Description</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" name="product_meta_des_<?php echo $row->language_id;?>" data-required="false"><?php echo $product_meta_des;?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-2 control-label">Product Keywords</label>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control" name="product_keywords_<?php echo $row->language_id;?>" data-required="false"><?php echo $product_keywords;?></textarea>
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
                                                    <input type="text" name="product_code" class="form-control disabled" value="<?php echo $data[0]->product_code;?>" disabled="">
                                                    <input type="hidden" name="product_code" class="form-control" value="<?php echo $data[0]->product_code;?>">
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
                                                    <?php echo $this->m_product->SelectProductCategory($data[0]->product_id);?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Asal</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="asal" class="form-control" data-required="false" value="<?php echo $data[0]->asal;?>" />
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
                                                    <?php echo $this->m_product->SelectProductSpecial($data[0]->product_id);?>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Unit Type</label>
                                                <div class="col-md-10 parent-block">
                                                    <?php echo $this->m_product->SelectUnitType($data[0]->unit_type_id);?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Price</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="price" class="form-control" data-required="false" value="<?php echo $data[0]->price;?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Quantity</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="text" name="quantity" class="form-control" value="<?php echo $data[0]->quantity;?>" data-required="false">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Sort</label>
                                                <div class="col-sm-10 parent-block">
                                                    <input type="number" name="sort_id" class="form-control" value="<?php echo $data[0]->sort_id;?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Status</label>
                                                <div class="col-sm-10 parent-block">
                                                    <?php $this->general->SelectStatus($data[0]->status);?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-four">
                                        <?php
                                        $arr_attribute = $this->crud_global->ShowTableNew('tbl_product_spec',array('product_id'=>$id));
                                        if(is_array($arr_attribute)){
                                            $lastEl = array_pop((array_slice($arr_attribute, -1)));
                                            $last_attribute_id = $lastEl->product_spec_id;
                                            $first_attribute_id = $arr_attribute[0]->product_spec_id;
                                        }else {
                                            $last_attribute_id = 0;
                                            $first_attribute_id = 0;
                                        }
                                        ?>
                                        <input type="hidden" name="val_attr" id="val_attr" value="<?php echo count($arr_attribute);?>">

                                        <input type="hidden" name="val_attr_first" id="val_attr_first" value="<?php echo $first_attribute_id;?>">

                                        <input type="hidden" name="val_attr_name" id="val_attr_name" value="<?php echo $last_attribute_id;?>">

                                        <input type="hidden" name="attr_del" id="attr_del">

                                        <table class="table table-hover table-bordered" id="table-attr">
                                            <thead>
                                                <tr>
                                                    <th>Attribute</th>
                                                    <th>Value</th>
                                                    <th width="100px">Sort</th>
                                                    <th width="50px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            if(is_array($arr_attribute)){
                                                $no = 1;
                                                foreach ($arr_attribute as $key => $value) {
                                                    ?>
                                                    <tr class="attr<?php echo $value->product_spec_id;?>">
                                                        <td>
                                                            <input type="text" name="attribute_name<?php echo $value->product_spec_id;?>" class="form-control" value="<?php echo $value->product_spec;?>">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="attribute_des<?php echo $value->product_spec_id;?>" rows="6"><?php echo strip_tags($value->product_spec_desc);?></textarea>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="attribute_sort<?php echo $value->product_spec_id;?>" class="form-control" value="<?php echo $value->sort_id;?>">
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);" onclick="DelAttr(<?php echo $value->product_spec_id;?>)" class="btn btn-danger btn-del-attr"><i class="fa fa-minus"></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $no++;
                                                }
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-info btn-plus-attr" data-attr-name="<?php echo $last_attribute_id;?>" data-attr="<?php echo count($arr_attribute);?>"><i class="fa fa-plus" ></i></a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab-five">
                                        <?php
                                        $arr_gallery = $this->crud_global->ShowTableNew('tbl_product_gallery',array('product_id'=>$id));


                                        if(is_array($arr_gallery)){
                                            $lastG = array_pop((array_slice($arr_gallery, -1)));
                                            $last_gallery_id = $lastG->product_gallery_id;
                                            $first_gallery_id = $arr_gallery[0]->product_gallery_id;
                                        }else {
                                            $last_gallery_id = 0;
                                            $first_gallery_id = 0;
                                        }

                                        ?>
                                        <input type="hidden" name="val_gallery" id="val_gallery" value="<?php echo count($arr_gallery);?>">

                                        <input type="hidden" name="val_gallery_first" id="val_gallery_first" value="<?php echo $first_gallery_id;?>">

                                        <input type="hidden" name="val_gallery_name" id="val_gallery_name" value="<?php echo $last_gallery_id;?>">

                                        <input type="hidden" name="gallery_del" id="gallery_del">
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
                                            <?php
                                            if(is_array($arr_gallery)){
                                                $no = 1;
                                                foreach ($arr_gallery as $key => $value) {
                                                    ?>
                                                    <tr class="gallery<?php echo $value->product_gallery_id;?>">
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" id="gallery_image<?php echo $value->product_gallery_id;?>" data-required="false" value="<?php echo $value->product_image;?>">
                                                                <input type="hidden" class="form-control" name="gallery_image<?php echo $value->product_gallery_id;?>" id="gallery_image<?php echo $value->product_gallery_id;?>_val" value="<?php echo $value->product_image;?>" >
                                                                <span class="input-group-btn">
                                                                    <a data-fancybox-type="iframe" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=gallery_image<?php echo $value->product_gallery_id;?>" class="btn btn-primary iframe-btn" type="button">Browse</a>
                                                                </span>
                                                            </div>
                                                            <br />
                                                            <?php
                                                            if(!empty($value->product_image)){
                                                                ?>
                                                                <div id="box-content_gallery_image<?php echo $value->product_gallery_id;?>">
                                                                    <img id="gallery_image<?php echo $value->product_gallery_id;?>_prev" src="<?php echo base_url().$value->product_image;?>" class="img-responsive" />
                                                                </div>
                                                                <?php
                                                            }else {
                                                                ?>
                                                                <div id="box-content_gallery_image<?php echo $value->product_gallery_id;?>">
                                                                    <img id="gallery_image<?php echo $value->product_gallery_id;?>_prev" src="<?php echo base_url();?>assets/back/images/no_photo.gif" class="img-responsive" />
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="gallery_des<?php echo $value->product_gallery_id;?>" rows="6"><?php echo $value->product_caption;?></textarea>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="gallery_sort<?php echo $value->product_gallery_id;?>" class="form-control" value="<?php echo $value->sort_id;?>">
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);" onclick="DelGallery(<?php echo $value->product_gallery_id;?>)" class="btn btn-danger btn-del-gallery"><i class="fa fa-minus"></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $no++;
                                                }
                                            }
                                            ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-info btn-plus-gallery" data-gallery-name="<?php echo $last_gallery_id;?>" data-gallery="<?php echo count($arr_gallery);?>"><i class="fa fa-plus" ></i></a>
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
                var val_del = $("#attr_del").val();

                if(val_del.indexOf(",") !== -1 ){
                    var val_del_now = val_del+id+",";
                    $("#attr_del").val(val_del_now);
                }else {
                    $("#attr_del").val(id+",");
                }

                $('.btn-plus-attr').attr('data-attr',data_now);

                $('tr.attr'+id).remove(); 
            }

            function DelGallery(id)
            {
                var data_gallery = parseInt($('.btn-plus-gallery').attr('data-gallery'));
                var data_now = data_gallery - 1;
                var val_del = $("#gallery_del").val();
                
                if(val_del.indexOf(",") !== -1 ){
                    var val_del_now = val_del+id+",";
                    $("#gallery_del").val(val_del_now);
                }else {
                    $("#gallery_del").val(id+",");
                }

                $('.btn-plus-gallery').attr('data-gallery',data_now);
                $('tr.gallery'+id).remove(); 
            } 

            
    
            $(document).ready(function(){
                $('.btn-plus-attr').click(function(){
                    var data_attr = parseInt($(this).attr('data-attr'));
                    var data_attr_name = parseInt($(this).attr('data-attr-name'));
                    var data_now = data_attr + 1;
                    var data_name_now = data_attr_name + 1;

                    $("#table-attr tbody").append('<tr class="attr'+data_name_now+'"><td><input type="text" name="attribute_name'+data_name_now+'" class="form-control"></td><td><textarea class="form-control" name="attribute_des'+data_name_now+'" rows="6"></textarea></td><td><input type="text" name="attribute_sort'+data_name_now+'" class="form-control"></td><td><a href="javascript:void(0);" onclick="DelAttr('+data_name_now+')"class="btn btn-danger btn-del-attr"><i class="fa fa-minus"></i></a></td></tr>');
                    $(this).attr('data-attr',data_now);
                    $(this).attr('data-attr-name',data_name_now);
                    $("#val_attr").val(data_now);
                    $("#val_attr_name").val(data_name_now);
                });

                $('.btn-plus-gallery').click(function(){
                    var gallery = parseInt($(this).attr('data-gallery'));
                    var data_now = gallery + 1;
                    var data_gallery_name = parseInt($(this).attr('data-gallery-name'));
                    var data_name_now = data_gallery_name + 1;

                    $("#table-gallery tbody").append('<tr class="gallery'+data_name_now+'"><td><div class="input-group"><input type="text" class="form-control" id="gallery_image'+data_name_now+'" data-required="false"><input type="hidden" class="form-control" name="gallery_image'+data_name_now+'" id="gallery_image'+data_name_now+'_val" ><span class="input-group-btn"><a data-fancybox-type="iframe" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=gallery_image'+data_name_now+'" class="btn btn-primary iframe-btn" type="button">Browse</a></span></div><br /><div id="box-content_gallery_image'+data_name_now+'"> <img id="gallery_image'+data_name_now+'_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" /></div></td><td><textarea class="form-control" name="gallery_des'+data_name_now+'" rows="6"></textarea></td><td><input type="text" name="gallery_sort'+data_name_now+'" class="form-control"></td><td><a href="javascript:void(0);" onclick="DelGallery('+data_name_now+')" class="btn btn-danger btn-del-gallery" ><i class="fa fa-minus"></i></a></td></tr>');

                    $(this).attr('data-gallery',data_now);
                    $(this).attr('data-gallery-name',data_name_now);
                    $("#val_gallery").val(data_now);
                    $("#val_gallery_name").val(data_name_now);
                });

            });
        </script>


        <script type="text/javascript">
            $(document).ready(function(){
                <?php
                $arr_procat = $this->crud_global->ShowTableNew('tbl_procat',array('product_id'=>$id));
                $arr_special = $this->crud_global->ShowTableNew('tbl_product_special',array('product_id'=>$id));

                $a = false;
                if(is_array($arr_procat)){
                    foreach ($arr_procat as $key => $row) {
                        // echo $row->product_category_id;
                        $a[]=$row->product_category_id;
                    }
                    ?>
                    $('#product_category_id').select2('val',[<?php echo implode(",", $a);?>]);
                    <?php
                }

                $b = false;
                if(is_array($arr_special)){
                    foreach ($arr_special as $key => $row) {
                        // echo $row->product_category_id;
                        $b[]=$row->product_special_category_id;
                    }
                    ?>
                    $('#product_special_id').selectpicker('val',[<?php echo implode(",", $b);?>]);
                    <?php
                }
                ?>

            });
        </script>

</body>
</html>
