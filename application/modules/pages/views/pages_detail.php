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
                        <li><a>Pages Detail <?php echo $pages_name;?></a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Pages Detail <?php echo $pages_name;?></h3>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('pages/pages_data_process');?>" data-redirect="<?php echo site_url('admin/pages_data');?>">
                                <input type="hidden" name="pages_id" value="<?php echo $id;?>">
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
                                                    $arr_pages_el = $this->crud_global->ShowTableDefault('tbl_pages_element',array('pages_id'=>$id));
                                                    foreach ($arr_pages_el as $key_el => $row_el) {

                                                        $pages_data = $this->crud_global->GetField('tbl_pages_data',array('pages_element_id'=>$row_el->pages_element_id,'language_id'=>$row->language_id),'pages_data_id');
                                                        $pages_content = $this->crud_global->GetField('tbl_pages_data',array('pages_element_id'=>$row_el->pages_element_id,'language_id'=>$row->language_id),'content');

                                                        echo $this->crud_global->GetElementInput($row_el->element_input_id,$pages_data,$row->language_id,$pages_content);
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
