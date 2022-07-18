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
                        <li><a><?php echo $row[0]->menu_name;?></a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle"><?php echo $row[0]->menu_name;?></h3>
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
                                    <li class=""><a href="#add" data-toggle="tab" aria-expanded="false">Send Mail To Subscriber</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="table">
                                        <table id="basic-table" class="data-table table table-striped nowrap table-hover table-center" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="30px">No</th>
                                                    <th>Email</th>
                                                    <th>Ip Address</th>
                                                    <th>Datecreated</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="add">
                                        <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('report/send_subscriber');?>" data-redirect="<?php echo site_url('admin/menu');?>">
                                            <input type="hidden" name="user_type" value="1">
                                            <input type="hidden" name="user_id" value="<?php echo $admin_id;?>">
                                            <input type="hidden" name="problem_type" value="subscribe">
                                            <input type="hidden" name="parent_id" value="0">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Subject<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="subject" name="subject">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Message<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <textarea name="message" id="message"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-9">
                                                    <button type="submit" name="submit" class="btn btn-primary" id="btn-submit" data-loading-text="please wait..">Send</button>
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
            load_table("<?php echo site_url('report/table_subscribe');?>");
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            tinymce.init({
              selector: 'textarea#message',
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
