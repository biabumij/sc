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
                        <li><i class="fa fa-cogs" aria-hidden="true"></i><a href="#">Settings</a></li>
                        <li><a>Themes Options</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                   <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Setting Your Website</h3>
                        </div>
                        <div class="panel-content">
                            <form action="<?php echo site_url('themes_options');?>" method="post" class="form-themes form-horizontal">
                                <?php
                            $data_table = $this->crud_global->ShowTableNoOrderStatus('tbl_themes_options',false);
                                if(is_array($data_table)){
                                    foreach ($data_table as $key => $row) {
                                        ?>
                                        <input type="hidden" name="id" value="<?php echo $row->themes_options_id;?>"></input>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Site Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_name" value="<?php echo $row->site_name;?>"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Site Logo</label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                <input type="hidden" class="form-control" name="site_logo" id="site_logo_val" value="<?php echo $row->site_logo;?>"/>
                                                <input type="text" id="site_logo" class="form-control" value="<?php echo $row->site_logo;?>"></input>
                                                <span class="input-group-btn">
                                                <a data-fancybox-type="iframe" class="btn btn-primary iframe-btn" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=site_logo" >Browse</a>
                                                </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php
                                                
                                                if(!empty($row->site_logo)){
                                                  ?>
                                                  <img id="site_logo_prev" src="<?php echo base_url().$row->site_logo;?>" class="img-responsive" />
                                                  <?php
                                                }else {
                                                  ?>
                                                  <img id="site_logo_prev" src="<?php echo base_url();?>assets/back/images/no_photo.gif" class="img-responsive" />
                                                  <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Site Favico</label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                <input type="hidden" class="form-control" name="site_favico" id="site_favico_val" value="<?php echo $row->site_favico;?>" />
                                                <input type="text"  id="site_favico" class="form-control" value="<?php echo $row->site_favico;?>"></input>
                                                <span class="input-group-btn">
                                                <a data-fancybox-type="iframe" class="btn btn-primary iframe-btn" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=site_favico" >Browse</a>
                                                </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php
                                                if(!empty($row->site_favico)){
                                                  ?>
                                                  <img id="site_favico_prev" src="<?php echo base_url().$row->site_favico;?>" class="img-responsive" />
                                                  <?php
                                                }else {
                                                  ?>
                                                  <img id="site_favico_prev" src="<?php echo base_url();?>assets/back/images/no_photo.gif" class="img-responsive" />
                                                  <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Site Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="site_email" value="<?php echo $row->site_email;?>" placeholder="ex: mywebsite@email.com"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Site Description</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_description" value="<?php echo $row->site_description;?>" placeholder="ex: Jasa Pembuatan Website"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Facebook</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_fb" value="<?php echo $row->site_fb;?>" placeholder="Link to Your Facebook"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Twitter</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_tw" value="<?php echo $row->site_tw;?>" placeholder="Link to Your Twitter"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Instagram</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_ig" value="<?php echo $row->site_ig;?>" placeholder="Link to Your Instagram"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Google +</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_gp" value="<?php echo $row->site_gp;?>" placeholder="Link to Your Google +"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Maps</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_maps" placeholder="ex: https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15864.443144870262!2d106.7789088!3d-6" value="<?php echo $row->site_maps;?>"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Maps Lat - Lang</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="site_lat" placeholder="Latitude Maps" value="<?php echo $row->site_lat;?>"></input>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="site_lan" placeholder="longitude Maps" value="<?php echo $row->site_lan;?>"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone 1</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_phone1" value="<?php echo $row->site_phone1;?>" placeholder="Input Your Phone Number 1"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone 2</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_phone2" value="<?php echo $row->site_phone2;?>" placeholder="Input Your Phone Number 2"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-sm-2 control-label">Mobile 1</label>
                                          <div class="col-sm-10">
                                            <input type="text" class="form-control" name="site_mobile1" value="<?php echo $row->site_mobile1;?>" placeholder="Input Your Mobile 1"></input>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-sm-2 control-label">Mobile 2</label>
                                          <div class="col-sm-10">
                                            <input type="text" class="form-control" name="site_mobile2" value="<?php echo $row->site_mobile2;?>" placeholder="Input Your Mobile 2"></input>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Address 1</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="site_address1"><?php echo $this->filter->filterInput($row->site_address1);?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Address 2</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="site_address2"><?php echo $this->filter->filterInput($row->site_address2);?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Meta Keywords</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_meta_keywords" value="<?php echo $row->site_meta_keywords;?>" placeholder="ex:Pensilwarna,Software House,Jual Website"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Meta Description</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_meta_description" value="<?php echo $row->site_meta_description;?>" placeholder="ex:Jasa Pembuatan Aplikasi"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Pages Home</label>
                                            <div class="col-sm-10">
                                                <?php $this->m_pages->SelectPagesTemplate($row->pages_home);?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                              <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

	<?php echo $this->Templates->Footer();?>

    <script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script> 

    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
    <script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/fancybox/source/jquery.fancybox.pack.js"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
    <script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/fancybox/source/helpers/jquery.fancybox-media.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            $(".form-themes").submit(function(event){
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                  $.ajax({
                     type: "POST",
                     url:  $(this).attr('action'),
                     data: formData,
                     dataType: 'json',
                     async: true,
                     beforeSend:function(){
                        // $("#loader-wrapper").fadeIn();
                        $('button').button('loading');
                     },
                     success: function (data) {
                        $('button').button('reset');
                        var output = data.output;
                        if(output == 'true'){
                            swal(
                                'Success!',
                                'Your data has been updated',
                                'success'
                            ).then(function(){
                                location.reload();
                                $('html, body').animate({ scrollTop: 0 }, 0);
                            });
                        }else 
                        if(output == 'site_name'){
                            swal(
                                'Opps Sorry!',
                                'Please Input Your Website Name',
                                'error'
                            ).then(function(){
                                location.reload();
                                $('html, body').animate({ scrollTop: 0 }, 0);
                            });

                        }else {
                            swal({
                                title: output,
                                type: 'error',
                            }).then(function(){
                                location.reload();
                                $('html, body').animate({ scrollTop: 0 }, 0);
                            });
                        }
                     },
                    cache: false,
                    contentType: false,
                    processData: false
                 });
            });
        });
    </script>



    <script type="text/javascript">

          $(document).ready(function() {
            $(".iframe-btn").fancybox({
                maxWidth    : 1000,
                maxHeight   : 600,
                fitToView   : false,
                width       : '100%',
                height      : '70%',
                autoSize    : false,
                closeClick  : false,
                openEffect  : 'none',
                closeEffect : 'none'
            });
          });
        </script>

        <script type="text/javascript">
            function responsive_filemanager_callback(field_id){
                var data_image = $("#"+field_id).val();
                var arr_split = data_image.split("/");
                var data_val = arr_split[arr_split.length-2]+'/'+arr_split[arr_split.length-1];
                // alert(data_split[data_split.length-2]);
                $("#"+field_id+"_prev").attr("src",data_image);
                $("#"+field_id+"_val").val(data_val);
            }
        </script>
</body>
</html>
