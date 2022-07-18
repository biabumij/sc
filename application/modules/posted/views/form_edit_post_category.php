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
                        <li><a>Edit Post Category</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Edit Post Category</h3>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('posted/edit_post_category');?>" data-redirect="<?php echo site_url('admin/post_category');?>">
                                <input type="hidden" name="id" value="<?php echo $row[0]->post_category_id;?>">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Post Category<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="post_category" name="post_category" value="<?php echo $row[0]->post_category;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Description<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <textarea id="post_category_description" name="post_category_description" ><?php echo $row[0]->post_category_description;?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Element Input<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->m_post->SelectElementInput($row[0]->post_category_id);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Order By<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->general->SelectRadioOrder($row[0]->order_by);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Post Comment<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->general->SelectOption('post_comment',$row[0]->post_comment);?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Post Limit<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="post_limit" name="post_limit" value="<?php echo $row[0]->post_limit;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Order<span class="required" aria-required="true">*</span></label>
                                    <div class="col-sm-8">
                                        <?php echo $this->crud_global->OrderInput('tbl_post_category',false,$row[0]->order_id);?>
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

    <script type="text/javascript">
        $(document).ready(function(){
            tinymce.init({
              selector: 'textarea#post_category_description',
              height: 250,
              theme: 'modern',
              plugins: [
 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
],
              toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
              toolbar2: 'print preview media | forecolor backcolor emoticons | link unlink anchor | image media | forecolor backcolor  | print preview code | responsivefilemanager',
              image_advtab: true,
              content_css: [
                '//www.tinymce.com/css/codepen.min.css'
              ],
              external_filemanager_path:"<?php echo base_url();?>filemanager/",
               filemanager_title:"Responsive Filemanager" ,
               external_plugins: { "filemanager" : "<?php echo base_url();?>assets/back/theme/vendor/tinymce/plugins/responsivefilemanager/plugin.min.js"}
             });
        });
    </script>

</body>
</html>
