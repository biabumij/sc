<?php
	$email = $this->input->get('q');
?>
<!doctype html>
<html lang="en" class="fixed accounts sign-in">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	    <title><?php echo $this->m_themes->GetThemes('site_name');?> - LOGIN</title>
	    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    <!--BASIC css-->
	    <!-- ========================================================= -->
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/bootstrap/css/bootstrap.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/font-awesome/css/font-awesome.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/animate.css/animate.css">
	    <!--SECTION css-->
	    <!-- ========================================================= -->
	    <!--TEMPLATE css-->
	    <!-- ========================================================= -->
	    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/stylesheets/css/style.css">
	    

	    <style type="text/css">
	    	label.error{
	    		position: absolute;
	    		bottom: -20px;
	    	}
			
			.site {
				color: #ffffff;
				font-weight: bold;
			}
	    	
	    	<?php include "assets/back/theme/stylesheets/css/style.css" ?>
	    </style>
	</head>

	<body>
	<div class="wrap">
	    <!-- page BODY -->
	    <!-- ========================================================= -->
	    <div class="page-body animated slideInDown">
	        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
	        <!--LOGO-->
	        <div class="logo">
	            <img alt="logo" src="<?php echo base_url().$this->m_themes->GetThemes('site_logo');?>" />
	        </div>
	        <div class="box">
	            <!--SIGN IN FORM-->
	            <div class="panel mb-none">
	                <div class="panel-content bg-scale-0">
	                	<div class="alert alert-warning" role="alert">
                          	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                          	<div class="alert-content"></div>
                      	</div>
	                    <form id="loginform" action="<?php echo site_url('login_admin');?>">
	                        <div class="form-group mt-md">
	                            <span class="input-with-icon">
	                                <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= $email;?>">
	                                <i class="fa fa-envelope"></i>
	                            </span>
	                        </div>
	                        <div class="form-group" >
	                            <span class="input-with-icon">
	                                <input type="password" class="form-control" id="password" placeholder="Kata sandi" name="password">
	                                <i class="fa fa-key"></i>
	                            </span>
	                        </div>
	                        <div class="form-group">
	                            <button type="submit" name="submit" class="btn btn-primary btn-block" data-loading="Please wait..." style="font-weight:bold; border-radius:10px"><b>MASUK</b></button>
	                        </div>
							<div class="form-group site text-center" style="color:black;">
								&copy; PT BIA BUMI JAYENDRA, 2021
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	        <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
	    
			<div class="box">
				<div class="tomorrow"
					data-location-id="057045"
					data-language="ID"
					data-unit-system="METRIC"
					data-skin="light"
					data-widget-type="aqiMini"
					style="padding-bottom:15px;position:relative;"
					>
				</div>
			</div>

			
		</div>
	</div>
	<!--BASIC scripts-->
	<!-- ========================================================= -->
	<script src="<?php echo base_url();?>assets/back/theme/vendor/jquery/jquery-1.12.3.min.js"></script>
	<script src="<?php echo base_url();?>assets/back/theme/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>assets/back/theme/vendor/nano-scroller/nano-scroller.js"></script>
	<!--TEMPLATE scripts-->
	<!-- ========================================================= -->
	<script src="<?php echo base_url();?>assets/back/theme/javascripts/template-script.min.js"></script>
	<script src="<?php echo base_url();?>assets/back/theme/javascripts/template-init.min.js"></script>
	<script src="<?php echo base_url();?>assets/back/theme/vendor/jquery-validation/jquery.validate.min.js"></script>
	<!-- SECTION script and examples-->
	<!-- ========================================================= -->

	<script type="text/javascript">
      $(document).ready(function(){
          $(".alert").hide();
          $("#loginform").validate({
              rules: {
                  email: {
                    required: true,
                    email:true,
                  },
                  password: {
                    required: true,
                    minlength: 6
                  }
                },
              submitHandler: function(form) {
                  $.ajax({
                   type: "POST",
                   url:  $(form).attr('action'),
                   data: $(form).serialize(),
                   dataType: 'json',
                   async:true,
                   beforeSend:function(){
                    $('button.btn-block').button('loading');
                    $(".alert").fadeIn();
                    $(".alert").removeClass('alert-danger');
                    $(".alert").addClass('alert-warning');
                    $(".alert-content").html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Please Wait...');
                   },
                   success: function (data) {
                       var output = data.output;
                       if(output == 'true'){
                          $(".alert").fadeIn();
                          $(".alert").removeClass('alert-warning').addClass('alert-success');
                          $(".alert-content").text('Berhasil Masuk');
                          setTimeout(function(){
                           window.location.href = data.redirect;
                          }, 1000);
                       }else {
                          $(".alert").fadeIn();
                          $(".alert").removeClass('alert-warning').addClass('alert-danger');
                          $(".alert-content").text('Maaf, Email atau kata sandi Anda tidak benar. Silakan coba lagi. Jika Anda lupa Email atau kata sandi Anda, dapat menghubungi Admin pusat BBJ.');
                          $('button.btn-block').button('reset');
                          console.log(data.alert);
                       }
                   }
                  });
                  return false;
              }
          });
      });
  	</script>
  	<script>
        (function(d, s, id) {
            if (d.getElementById(id)) {
                if (window.__TOMORROW__) {
                    window.__TOMORROW__.renderWidget();
                }
                return;
            }
            const fjs = d.getElementsByTagName(s)[0];
            const js = d.createElement(s);
            js.id = id;
            js.src = "https://www.tomorrow.io/v1/widget/sdk/sdk.bundle.min.js";

            fjs.parentNode.insertBefore(js, fjs);
        })(document, 'script', 'tomorrow-sdk');
    </script>
</body>
</html>
