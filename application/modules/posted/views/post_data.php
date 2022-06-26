<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .form-horizontal .form-group {
             margin-right: 0px; 
             margin-left: 0px; 
        }
    </style>
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
                        <li><a>Post Data <?php echo $post;?></a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Post Data <?php echo $post;?></h3>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('posted/post_data_process');?>" data-redirect="<?php echo site_url('posted/detail/'.$post_category_id);?>">
                                <input type="hidden" name="post_id" value="<?php echo $id;?>">
                                <input type="hidden" name="post_category_id" value="<?php echo $post_category_id;?>">
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
                                                    <?php
                                                    $arr_post_el = $this->crud_global->ShowTableDefault('tbl_post_element',array('post_category_id'=>$post_category_id));

                                                    if(is_array($arr_post_el)){
                                                        $no=1;
                                                        foreach ($arr_post_el as $key_el => $row_el) {

                                                            $post_data = $this->crud_global->GetField('tbl_post_data',array('post_id'=>$id,'language_id'=>$row->language_id,'post_element_id'=>$row_el->post_element_id),'post_data_id');

                                                            $post_content = $this->crud_global->GetField('tbl_post_data',array('post_id'=>$id,'language_id'=>$row->language_id,'post_element_id'=>$row_el->post_element_id),'content');

                                                            echo $this->crud_global->GetElementInput($row_el->element_input_id,$post_data,$row->language_id,$post_content,$no);
                                                            $no++;
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <button type="submit" name="submit" id="btn-submit" class="btn btn-primary">Save</button>
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
    
    <?php echo $this->m_pages->ScriptEditor();?>
</body>
</html>
