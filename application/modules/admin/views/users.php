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
                            <div class="leftside-content-header">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#table" data-toggle="tab" aria-expanded="true" style="border-radius:10px 0px 10px 0px; font-weight:bold;">Users</a></li>
                                </ul>
                                <br />
                                <div class="tab-content">
                                    <div class="tab-pane fade active in">
                                        <table class="data-table table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">No</th>
                                                    <th width="30%" class="text-center">Nama</th>
                                                    <th width="35%" class="text-center">Email</th>
                                                    <th width="30%" class="text-center">Jabatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
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


    
    <script type="text/javascript">
        $(document).ready(function() {
            load_table("<?php echo site_url('admin/users');?>");
        });
    </script>

</body>
</html>
