<!doctype html>
<html lang="en" class="error-404">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Page Not Found</title>
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->m_themes->GetThemes('site_favico');?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $this->m_themes->GetThemes('site_favico');?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $this->m_themes->GetThemes('site_favico');?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $this->m_themes->GetThemes('site_favico');?>">

    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/animate.css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/stylesheets/css/style.css">

</head>

<body>
<div class="wrap">
    <div class="page-body">
        <div class="row animated bounce ">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel mt-xlg">
                    <div class="panel-content">
                        <h1 class="error-number">404</h1>
                        <h2 class="error-name"> Page not found</h2>
                        <p class="error-text">Sorry, the page you are looking for cannot be found.
                            <br/>Please check the url for errors or try one of the options below</p>
                        <div class="row mt-xlg">
                            <div class="col-sm-6  col-sm-offset-3">
                                <a href="<?php echo base_url();?>" class="btn btn-sm btn-primary btn-block">Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery/jquery-1.12.3.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/back/theme/vendor/nano-scroller/nano-scroller.js"></script>
        <script src="<?php echo base_url();?>assets/back/theme/javascripts/template-script.min.js"></script>
        <script src="<?php echo base_url();?>assets/back/theme/javascripts/template-init.min.js"></script>

</body>
</html>
