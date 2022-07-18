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
                        <li><a>Setting Production</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                   <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Setting Your Production</h3>
                        </div>
                        <div class="panel-content">
                            <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('pmm/setting_production');?>" data-redirect="<?php echo site_url('admin/perusahaan');?>" enctype="multipart/form-data">
                                <?php
                                $sp = $this->db->get_where('pmm_setting_production',array('id'=>1))->row_array();
                                ?>
                                <input type="hidden" name="id" value="1"></input>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Factor Lost</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control numberformat" name="factor_lost" value="<?php echo number_format($sp['factor_lost'],2,',','.');?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Content Weight</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control numberformat" name="content_weight" value="<?php echo number_format($sp['content_weight'],2,',','.');?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Overhead %</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="overhead" value="<?php echo $sp['overhead'];?>"></input>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Nama Perusahaan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nama_pt" value="<?php echo $sp['nama_pt'];?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Code Prefix</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="code_prefix" value="<?php echo $sp['code_prefix'];?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jenis Usaha</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="jenis_usaha" value="<?php echo $sp['jenis_usaha'];?>"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Kop Surat</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="kop_surat" class="form-control" data-required="false" />
                                        <small><a href="<?= base_url();?>uploads/pmm_kop.png" target="_blank">Liat contoh</a></small>
                                    </div>
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <?php
                                        if(!empty($sp['kop_surat'])){
                                            ?>
                                            <img src="<?= base_url();?>uploads/kop_surat/<?= $sp['kop_surat'];?>" class="img-responsive">
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                      <button type="submit" name="submit" class="btn btn-primary" id="btn-submit">Submit</button>
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
    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery.number.min.js"></script>

    <script type="text/javascript">
        $('input.numberformat').number( true, 2,',','.' );
    </script>



    
</body>
</html>
