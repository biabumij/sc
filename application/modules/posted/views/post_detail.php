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
                        <li><a>Manage <?php echo $row[0]->post_category;?></a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle"><?php echo $row[0]->post_category;?></h3>
                            <div class="panel-actions">
                                <ul>
                                    <li class="action"><span class="fa fa-refresh action" onclick="reload_table()" aria-hidden="true"></span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-content">
                            <div class="tabs">
                                <ul class="nav nav-tabs ">
                                    <li class="active"><a href="#table" data-toggle="tab" aria-expanded="true">Table</a></li>
                                    <?php
                                    $post_limit = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$id),'post_limit');
                                    if($post_limit != 0){
                                        $count = count($this->crud_global->ShowTableNew('tbl_post',array('status'=>1,'post_category_id'=>$id)));
                                        if($post_limit > $count){
                                            ?>
                                            <li class=""><a href="#add" data-toggle="tab" aria-expanded="false">Add New</a></li>
                                            <?php
                                        }
                                    }else {
                                        ?>
                                        <li class=""><a href="#add" data-toggle="tab" aria-expanded="false">Add New</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="table">
                                        <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="30px">No</th>
                                                    <th>Post</th>
                                                    <th>Parent</th>
                                                    <th>Author</th>
                                                    <th>Status</th>
                                                    <th width="150px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="add">
                                        <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('posted/add_post');?>" data-redirect="<?php echo site_url('posted/detail/'.$id);?>">
                                            <input type="hidden" name="post_category_id" value="<?php echo $id;?>">
                                            <input type="hidden" name="user_type" value="1">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Post<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="post" name="post">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Parent<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <?php echo $this->m_post->SelectParent($id);?>
                                                </div>
                                            </div>
                                            <?php
                                            $order_by = $this->crud_global->GetField('tbl_post_category',array('post_category_id'=>$id),'order_by');
                                                if($order_by == 1){
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Order <span class="required" aria-required="true">*</span></label>
                                                        <div class="col-sm-8 parent-block">
                                                            <?php echo $this->crud_global->OrderInput('tbl_post',$id);?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            ?>
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
                </div>
            </div>
        </div>
        
    </div>
</div>

	<?php echo $this->Templates->Footer();?>
    
    <script type="text/javascript">
        $(document).ready(function() {
            load_table("<?php echo site_url('posted/table/'.$row[0]->post_category_id);?>");
        });
    </script>

</body>
</html>
