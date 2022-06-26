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
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('themes_options/index');?>" data-redirect="<?php echo site_url('admin/themes_options');?>">
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
                                                  <img id="site_favico_prev" src="<?php echo base_url().$row->site_favico;?>" class="img-responsive" style="width:auto;"/>
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
                                            <label class="col-sm-2 control-label">Link Order</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_email" value="<?php echo $row->site_email;?>" ></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">About</label>
                                            <div class="col-sm-10">
                                                <!-- <input type="text" class="form-control" name="site_description" value="<?php echo $row->site_description;?>" placeholder="ex: Jasa Pembuatan Website" data-required="false"></input> -->
                                                <textarea class="form-control" name="site_description" data-required="false" id="about_text">
                                                    <?php echo $row->site_description;?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Facebook</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_fb" value="<?php echo $row->site_fb;?>" placeholder="Link to Your Facebook" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Twitter</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_tw" value="<?php echo $row->site_tw;?>" placeholder="Link to Your Twitter" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Instagram</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_ig" value="<?php echo $row->site_ig;?>" placeholder="Link to Your Instagram" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Youtube</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_gp" value="<?php echo $row->site_gp;?>" placeholder="Link to Your Youtube" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Linkedin</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_address1" value="<?php echo $row->site_address1;?>" placeholder="Link to Your Linkedin" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Maps</label>
                                            <div class="col-sm-10">
                                                 <textarea class="form-control" name="site_maps" data-required="false">
                                                    <?php echo $row->site_maps;?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label class="col-sm-2 control-label">Maps Lat - Lang</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="site_lat" placeholder="Latitude Maps" value="<?php echo $row->site_lat;?>" data-required="false"></input>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="site_lan" placeholder="longitude Maps" value="<?php echo $row->site_lan;?>" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone 1</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_phone1" value="<?php echo $row->site_phone1;?>" placeholder="Input Your Phone Number 1" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone 2</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_phone2" value="<?php echo $row->site_phone2;?>" placeholder="Input Your Phone Number 2" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-sm-2 control-label">Mobile 1</label>
                                          <div class="col-sm-10">
                                            <input type="text" class="form-control" name="site_mobile1" value="<?php echo $row->site_mobile1;?>" placeholder="Input Your Mobile 1" data-required="false"></input>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-sm-2 control-label">Mobile 2</label>
                                          <div class="col-sm-10">
                                            <input type="text" class="form-control" name="site_mobile2" value="<?php echo $row->site_mobile2;?>" placeholder="Input Your Mobile 2" data-required="false"></input>
                                          </div>
                                        </div>-->
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Address</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="site_address2" data-required="false"><?php echo $this->filter->filterInput($row->site_address2);?></textarea>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Meta Keywords</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_meta_keywords" value="<?php echo $row->site_meta_keywords;?>" placeholder="ex:Pensilwarna,Software House,Jual Website" data-required="false"></input>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Meta Description</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="site_meta_description" value="<?php echo $row->site_meta_description;?>" placeholder="ex:Jasa Pembuatan Aplikasi" data-required="false"></input>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label class="col-sm-2 control-label">Pages Home</label>
                                            <div class="col-sm-10">
                                                <?php $this->m_pages->SelectPagesTemplate($row->pages_home);?>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                              <button type="submit" name="submit" class="btn btn-primary" id="btn-submit">Submit</button>
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
        <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
</div>

	<?php echo $this->Templates->Footer();?>

    <script type="text/javascript">
        tinymce.init({
          selector: 'textarea#about_text',
          height: 200,
          menubar: false,
         });
    </script>

    
</body>
</html>
