<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .nav-tabs{
            width: 35%;
        }
        .tabs .nav-tabs > li > a {
            border-radius: 0px;
            font-size: 15px;
            font-weight: bold;
            padding: 15px 15px;
        }
        .tabs .tab-content {
            padding: 10px 20px;
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
                        <li><a>Post</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Post</h3>
                        </div>
                        <div class="panel-content">
                            <?php
                            $arr_post = $this->crud_global->ShowTableNew('tbl_post_category',array('status'=>1));
                            if(is_array($arr_post)){
                                ?>
                                <div class="tabs tabs-vertical tabs-left ">
                                    <ul class="nav nav-tabs">
                                        <?php
                                        foreach ($arr_post as $key => $row) {
                                            $active = false;
                                            if($key == 0){
                                                $active = 'active';
                                            }
                                            ?>
                                            <li class="<?php echo $active;?>"><a href="#<?php echo $row->post_category_alias;?>" data-toggle="tab" aria-expanded="true"><?php echo $row->post_category;?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php
                                        foreach ($arr_post as $key => $row) {
                                            $active = false;
                                            if($key == 0){
                                                $active = 'active in';
                                            }
                                            ?>
                                            <div class="tab-pane fade <?php echo $active;?>" id="<?php echo $row->post_category_alias;?>">
                                                <h5 class="section-subtitle"><b><?php echo $row->post_category;?></b> Description</h5>
                                                <p><?php echo $row->post_category_description;?></p>
                                                <a href="<?php echo site_url('posted/detail/'.$row->post_category_id);?>" class="btn btn-darker-1">Manage <?php echo $row->post_category;?></a>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <?php
                            }else {
                                ?>
                                <div class="alert alert-primary fade in text-center">
                                    <a href="#" class="close" data-dismiss="alert">×</a>
                                    Post Not Found
                                </div>
                                <?php
                            }
                            ?>
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
            var Htabs = $(".nav-tabs").height();
            $(".tab-content").css("min-height",Htabs+"px");
        });
    </script>
</body>
</html>
