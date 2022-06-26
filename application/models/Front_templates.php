<?php

class Front_templates extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('m_themes');
        $this->load->model('pages/m_pages');
    }

    function HeaderDefault($pages=false,$meta_keywords=false,$meta_description=false)
    {

      if($meta_keywords == false){
          $meta_keywords = 'hck.co.id, hck, Hapindo Cipta Kharisma, Cipta, Kharisma, Hapindo Cipta, Air Pollution Specialist Manufactures';
      }
      if($meta_description == false){
          $meta_description = 'Air Pollution Specialist Manufactures Of : Industrial Fan, Ducting, Silo, Cyclone, Filter, Bag House, Portable Bag House, Scrubber, Rotary Lock, Screwfeed, R.A.G, Diffuser, Plastic (FRP, PP, etc.)& Metal.';
      }
      
      ?>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="keywords" content="<?php echo $meta_keywords;?>">
      <meta name="description" content="<?php echo $meta_description;?>">
      <title><?php echo $pages;?> - <?php echo $this->m_themes->GetThemes('site_name');?> || <?php echo $this->m_themes->GetThemes('site_description');?></title>
      <!-- ==================Start Css Link===================== -->
      <!-- fonts css  -->
      <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro:400,600,700" rel="stylesheet">
      <!-- favicon -->
      <!-- <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/front/images/favicon.ico"> -->
      <link rel="shortcut icon" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>" type="favicon/ico" />
      <!-- fonts -->
      <!-- bootstarp css link -->
      <link href="<?php echo base_url();?>assets/front/css/materialize.min.css" rel="stylesheet">
      <!-- bootstrap css -->
      <link href="<?php echo base_url();?>assets/front/css/bootstrap.min.css" rel="stylesheet">
      <!-- fontawesome css link -->
      <link href="<?php echo base_url();?>assets/front/css/font-awesome.min.css" rel="stylesheet">
      <!-- magnifi pop css -->
      <link href="<?php echo base_url();?>assets/front/css/magnific-popup.css" rel="stylesheet">
      <!-- animated css -->
      <link href="<?php echo base_url();?>assets/front/css/animate.css" rel="stylesheet">
      <!-- owal carosel css -->
      <link href="<?php echo base_url();?>assets/front/css/owl.carousel.min.css" rel="stylesheet">
      <!-- owal carosel theme css -->
      <link href="<?php echo base_url();?>assets/front/css/owl.theme.default.min.css" rel="stylesheet">
      <!-- lightbox css -->
      <link href="<?php echo base_url();?>assets/front/css/lightbox.min.css" rel="stylesheet">
      <!-- main css file -->
      <link href="<?php echo base_url();?>assets/front/css/main.css" rel="stylesheet">
      <!-- responsive css -->
      <link href="<?php echo base_url();?>assets/front/css/responsive.css" rel="stylesheet">
      <!-- ==================End Css Link===================== -->


      <!--[if lt IE 9]>
      <script src="http://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
        <?php
    }

    function Header($lang_id)
    {
      $member_id = $this->session->userdata('member_id');

      if(empty($member_id)){
        ?>
        <!-- Modal -->
        <div class="modal fade modal-wamti" id="ModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div class="box-modal" style="background:url(<?php echo base_url();?>assets/front/images/bg-login.jpg) no-repeat center center;background-size: cover;">
                  <div class="inner-modal">
                    <div class="title-modal">Belum menjadi Member?</div>
                    <div class="btn-modal">
                      <a href="#" class="btn btn1 blue-white btn-block" id="go-anggota">Daftar Menjadi Anggota</a>
                    </div>
                    <div class="btn-modal">
                      <a href="#" class="btn btn1 blue-white btn-block" id="go-member">Daftar Untuk Beli Komoditi</a>
                    </div>
                  </div>
                  <div class="overlay-blue"></div>
                </div>
                <div class="box-modal">
                  <div class="inner-modal">
                    <div class="form-modal">
                      <div class="title-form-modal green">LOG IN</div>
                      <div class="alert alert-warning" role="alert" id="loginAlert">
                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                          <div class="alert-content"></div>
                      </div>
                      <form method="POST" id="login" action="<?php echo site_url('member/login');?>">
                        <div class="form-group">
                          <input type="email" name="email" placeholder="Email" class="form-control">
                        </div>
                        <div class="form-group">
                          <input type="password" name="password" placeholder="Password" class="form-control">
                        </div>
                        <br />
                        <div class="pull-left">
                          <a href="#" class="forgot">Forgot password?</a>
                        </div>
                        <div class="pull-right">
                          <button name="submit" type="submit" class="btn btn1 btn-green2" id="btn-login">LOG IN</button>
                        </div>
                        <div class="clearfix"></div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade modal-wamti" id="ModalAnggota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div class="box-modal">
                  <div class="inner-modal">
                    <div class="form-modal">
                      <div class="title-form-modal blue">DAFTAR MENJADI ANGGOTA</div>
                      <div class="alert alert-warning" role="alert" id="anggotaAlert">
                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                          <div class="alert-content"></div>
                      </div>
                      <form method="POST" id="anggota" action="<?php echo site_url('member/anggota');?>">
                        <div class="form-group">
                          <input type="email" name="email" placeholder="Email" class="form-control">
                        </div>
                        <div class="form-group">
                          <input type="password" name="password" placeholder="Password" class="form-control" id="anggota-password">
                        </div>
                        <div class="form-group">
                          <input type="password" name="co_password" placeholder="Confirm Password" class="form-control">
                        </div>
                        <div class="form-group">
                          <input type="checkbox" name="" id="check-anggota"> Punya Nomor Anggota ?
                          <input type="text" name="no_anggota" placeholder="No Anggota" class="form-control" id="no-anggota">
                        </div>
                        <br />
                        <div class="pull-right">
                          <button name="submit" type="submit" class="btn btn1 btn-blue" id="btn-anggota">CREATE ACCOUNT</button>
                        </div>
                        <div class="clearfix"></div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="box-modal" style="background:url(<?php echo base_url();?>assets/front/images/bg-login2.jpg) no-repeat center center;background-size: cover;">
                  <div class="inner-modal">
                    <div class="title-modal">Sudah Menjadi Anggota?</div>
                    <div class="btn-modal">
                      <a href="#" class="btn btn1 green btn-block go-login" data-modal="ModalAnggota">LOGIN</a>
                    </div>
                  </div>
                  <div class="overlay-green"></div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade modal-wamti" id="ModalKomoditi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div class="box-modal">
                  <div class="inner-modal">
                    <div class="form-modal">
                      <div class="title-form-modal blue">DAFTAR UNTUK BELI KOMODITI</div>
                      <div class="alert alert-warning" role="alert" id="registerAlert">
                          <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                          <div class="alert-content"></div>
                      </div>
                      <form method="POST" id="register" action="<?php echo site_url('member/register');?>">
                        <div class="form-group">
                          <input type="email" name="email" placeholder="Email" class="form-control">
                        </div>
                        <div class="form-group">
                          <input type="password" name="password" placeholder="Password" class="form-control" id="password">
                        </div>
                        <div class="form-group">
                          <input type="password" name="co_password" placeholder="Confirm Password" class="form-control">
                        </div>
                        <br />
                        <div class="pull-right">
                          <button name="submit" type="submit" class="btn btn1 btn-blue" id="btn-register">CREATE ACCOUNT</button>
                        </div>
                        <div class="clearfix"></div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="box-modal" style="background:url(<?php echo base_url();?>assets/front/images/bg-login2.jpg) no-repeat center center;background-size: cover;">
                  <div class="inner-modal">
                    <div class="title-modal">Sudah Menjadi Anggota?</div>
                    <div class="btn-modal">
                      <a href="#" class="btn btn1 green btn-block go-login" data-modal="ModalKomoditi" >LOGIN</a>
                    </div>
                  </div>
                  <div class="overlay-green"></div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>

      <div id="header-top">
        <div class="logo">
          <a href="#">
            <img src="<?php echo base_url();?>assets/front/images/logo.png" class="img-responsive">
          </a>
        </div>
        <div class="container wrapper no-padding">
          <div class="capt-header">
            <img src="<?php echo base_url();?>assets/front/images/wamti.png" class="img-responsive">
            <div class="inner-wamti">
              Wahana Masyarakat Tani dan Nelayan Indonesia<br />
              Indonesian Farmer and Fisher Society Organization<br />
              <span>"KEMANUSIAAN DAN KESEJAHTERAAN"</span>
            </div>
          </div>
          <?php
          $arr_menu = $this->m_pages->ShowPages('main_menu');
          $uri2 = $this->uri->segment(2);
          if(is_array($arr_menu)){
            ?>
            <ul class="menu">
              <?php
              foreach ($arr_menu as $key => $row) {
                $menu_name = $this->m_pages->ShowPagesDetail($row->pages_url,$lang_id,'pages_name');
                $pages_alias = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row->pages_url),'pages_alias');
                $parent_id = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row->pages_url),'parent_id');

                $arr_child = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1,'parent_id'=>$row->pages_url));
                $pages_url = site_url('page/'.$pages_alias);

                if(is_array($arr_child)){
                  $pages_url = "#";
                }
                

                $active = false;
                if($uri2 == $pages_alias){
                  $active = 'active';
                }

                $active_home = false; 
                if(empty($uri2)){
                  if($pages_alias == 'home'){
                    $active_home = 'active'; 
                  }
                }

                if($parent_id == 0){
                  ?>
                  <li><a href="<?php echo $pages_url;?>" class="<?php echo $active.' '.$active_home;?>"><?php echo $menu_name;?></a>
                    <?php
                    if(is_array($arr_child)){
                      ?>
                      <ul>
                        <?php
                        foreach ($arr_child as $key_child => $row_child) {
                          $menu_child = $this->m_pages->ShowPagesDetail($row_child->pages_id,$lang_id,'pages_name');
                          $pages_url_child = site_url('page/'.$row_child->pages_alias);
                          if($row_child->pages_alias == 'anual_report'){
                            $pages_url_child = "#";
                          }
                          // if($row_child->pages_alias != 'uped'){
                            ?>
                            <li><a href="<?php echo $pages_url_child;?>"><?php echo $menu_child;?></a>
                            <?php
                          // }
                          if($row_child->pages_alias == 'anual_report'){

                            $arr = $this->m_post->ShowPost('anual_report');
                            if(is_array($arr)){
                              ?>
                              <ul class="child">
                                <?php
                                foreach ($arr as $key => $row) {
                                  $title = $this->m_post->ShowDataPost($lang_id,$row->post_id,'title'); 
                                  $filemanager = $this->m_post->ShowDataPost($lang_id,$row->post_id,'filemanager'); 
                                  ?>
                                  <li><a href="<?php echo base_url().$filemanager;?>" target="_blank"><?php echo $title;?></a></li>
                                  <?php
                                }
                                ?>
                              </ul>
                              <?php
                            }
                          }
                          ?>
                          </li>
                          <?php
                        }
                        ?>
                      </ul>
                      <?php
                    }
                    ?>
                  </li>
                  <?php
                }
              }
              ?>
            </ul>
            <?php
          }
          ?>
        </div>
        <div class="right-header">
          <?php
          
          if(!empty($member_id)){
            $member_name =  $this->crud_global->GetField('tbl_member_info',array('member_id'=>$member_id),'name');
            if(empty($member_name)){
              $member_name = 'People';
            }
            ?>
            <div class="box-small-profile">
              <div class="thumb-profile">
                <?php echo $this->m_member->ShowPhoto($member_id);?>
                <h5><a href="<?php echo site_url('member/profile');?>"><?php echo $member_name;?></a></h5>
              </div>
            </div>
            <div class="btn-group-profile">
              <a href="<?php echo site_url('member/cart');?>" class="btn-cart"><i class="fa fa-shopping-cart"></i> <span class="badge" id="info-cart">0</span> MY CART</a>
              <a href="<?php echo site_url('member/logout');?>" class="btn-logout">KELUAR</a>
            </div>

            <div class="prof-log">
              <a href="<?php echo site_url('member/logout');?>" class="btn-right btn-blue" ><i class="fa fa-sign-out"></i> <span>KELUAR</span></a>
              <a href="<?php echo site_url('member/cart');?>" class="btn-right btn-green" ><i class="fa fa-shopping-cart"></i> <span class="badge" id="info-cart">0</span> <span>MY CART</span></a>
              <a href="<?php echo site_url('member/profile');?>" class="btn-right btn-green2"><i class="fa fa-user"></i> <span><?php echo $this->m_member->ShowPhoto($member_id);?> <?php echo $member_name;?></span></a>
            </div>
            <?php
          }else {
            ?>
            <a href="#" class="btn-right btn-blue" data-toggle="modal" data-target="#ModalLogin"><i class="fa fa-sign-in"></i> <span>Masuk</span></a>
            <a href="#" class="btn-right btn-green" data-toggle="modal" data-target="#ModalAnggota"><i class="fa fa-users"></i> <span>Daftar Menjadi Anggota</span></a>
            <a href="#" class="btn-right btn-green2" data-toggle="modal" data-target="#ModalKomoditi"><i class="fa fa-user"></i> <span>Daftar Untuk Beli Komoditi</span></a>
            <?php
          }
          ?>
          <form method="POST" action="<?php echo site_url('post/search');?>">
            <div class="input-group">
              <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
              <input type="text" class="form-control" placeholder="CARI" aria-describedby="basic-addon1" name="search">
            </div>
          </form>
          <a href="#" class="btn btn-default btn-mobile"><i class="fa fa-bars"></i></a>
        </div>
      </div>
      <?php
    }


    function MainMenu($lang_id)
    {
      $arr_menu = $this->m_pages->ShowPages('main_menu');
      $uri2 = $this->uri->segment(2);
      if(is_array($arr_menu)){
        ?>
        <ul>
          <?php
          foreach ($arr_menu as $key => $row) {
            $menu_name = $this->m_pages->ShowPagesDetail($row->pages_url,$lang_id,'pages_name');
            $pages_alias = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row->pages_url),'pages_alias');
            $parent_id = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row->pages_url),'parent_id');

            $arr_child = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1,'parent_id'=>$row->pages_url));
            $pages_url = site_url('page/'.$pages_alias);
            $active = false;
            if($uri2 == $pages_alias){
              $active = 'active';
            }

            $has_child = false;
            $has_child_a = false;
            if(is_array($arr_child)){ 
              $has_child = 'has-child';
              $has_child_a = '<i class="fa fa-angle-down"></i>';
            }
            if($parent_id == 0){
              
              ?>
              <li class="<?php echo $active.' '.$has_child;?>">
                <a href="<?php echo $pages_url;?>"><?php echo $menu_name.' '.$has_child_a;?></a>
                <?php
                if(is_array($arr_child)){
                  ?>
                  <ul class="sub-menu">
                    <?php
                    foreach ($arr_child as $key_child => $row_child) {
                      $menu_child = $this->m_pages->ShowPagesDetail($row_child->pages_id,$lang_id,'pages_name');
                      $pages_url_child = site_url('page/'.$row_child->pages_alias);

                      ?>
                      <li><a href="<?php echo $pages_url_child;?>"><?php echo $menu_child;?></a></li>
                      <?php
                    }
                    ?>
                  </ul>
                  <?php
                }
                ?>
              </li>
              <?php
            }
          }
          ?>
        </ul>
        <?php
      }
    }


    function ArrMenuProfile()
    {
      $output = false;

      $arr = array(
          'profile' => array(
            'member_type' => '1',
            'anggota' => 1,
            'user_type' => '1,3',
            'label' => 'INFORMASI AKUN'
            ),
          'history' => array(
            'member_type' => '1',
            'anggota' => 1,
            'user_type' => '1,3',
            'label' => 'HISTORY PEMBELIAN'
            ),
          'blog' => array(
            'member_type' => '2',
            'anggota' => 2,
            'user_type' => '1',
            'label' => 'BLOG'
            ),
          'news' => array(
            'member_type' => '2',
            'anggota' => 2,
            'user_type' => '1',
            'label' => 'NEWS'
            ),
          'event' => array(
            'member_type' => '2',
            'anggota' => 2,
            'user_type' => '1',
            'label' => 'EVENT'
            ),
          'video' => array(
            'member_type' => '2',
            'anggota' => 2,
            'user_type' => '1',
            'label' => 'VIDEO'
            ),
          'uped' => array(
            'member_type' => '2',
            'anggota' => 2,
            'user_type' => '1',
            'label' => 'UPED'
            ),
          'message' => array(
            'member_type' => '2',
            'anggota' => 2,
            'user_type' => '1',
            'label' => 'PESAN'
            ),
        );
      $output = $arr;

      return $output;
    }


    function ProfileMenu()
    {
      $member_type = $this->session->userdata('member_type');
      $member_id = $this->session->userdata('member_id');
      $status = $this->crud_global->GetField('tbl_member',array('member_id'=>$member_id),'status');
      $arr = $this->ArrMenuProfile();
      $uri2 = $this->uri->segment(2);
      $uri3 = $this->uri->segment(3);
      ?>
      <div class="left-member">
        <div class="photo-member">
          <div id="box-photo">
            <?php
            if($uri2 == 'view'){  

              $check = $this->crud_global->CheckNum('tbl_member',array('member_id'=>$uri3));
              if($check){
                $photo = $this->crud_global->GetField('tbl_member',array('member_id'=>$uri3),'photo');
                if(!empty($photo)){
                    ?>
                    <img src="<?php echo base_url().$photo;?>" class="img-responsive">
                    <?php
                }else {
                    ?>
                    <img src="<?php echo base_url();?>assets/front/images/no-avatar.png" class="img-responsive">
                    <?php
                }
              }else {
                  ?>
                  <img src="<?php echo base_url();?>assets/front/images/no-avatar.png" class="img-responsive">
                  <?php
              }
            }else {
              echo $this->m_member->ShowPhoto($member_id);
            }
            ?>
            <div class="loading-photo">
              <div class="inner-photo">
                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                <span class="sr-only">Loading...</span>
                <div>Please Wait....</div>
              </div>
            </div>
          </div>
          <?php
          if($uri2 == 'edit'){
            ?>
            <input type="file" name="photo" class="btn btn-default" style="width: 100%;border-radius: 0px;" id="change-photo" />
            <?php
          }
          ?>
          <br />
        </div>
        <?php
        if(!empty($member_id)){
            if($uri2 == 'view'){

              if($member_id == $uri3){
                $active = false;
                ?>
                <ul class="menu-profile">
                  <li><a href="<?php echo site_url('member/profile');?>" class="<?php echo $active;?>">INFORMASI AKUN</a></li>
                  <li><a href="<?php echo site_url('member/history');?>" class="<?php echo $active;?>">HISTORY PEMBELIAN</a></li>
                  <?php
                  if($member_type == 2){
                    if($status == 1){
                      ?>
                      <li><a href="<?php echo site_url('member/blog');?>" class="<?php echo $active;?>">BLOG</a></li>
                      <li><a href="<?php echo site_url('member/news');?>" class="<?php echo $active;?>">NEWS</a></li>
                      <li><a href="<?php echo site_url('member/event');?>" class="<?php echo $active;?>">EVENT</a></li>
                      <li><a href="<?php echo site_url('member/video');?>" class="<?php echo $active;?>">VIDEO</a></li>
                      <!-- <li><a href="<?php echo site_url('member/uped');?>" class="<?php echo $active;?>">UPED</a></li>
                      <li><a href="<?php echo site_url('member/message');?>" class="<?php echo $active;?>">PESAN</a></li> -->
                      <?php
                    }
                  }
                  ?>
                </ul>
                <?php
              }
            }else {
              $active = false;
              ?>
              <ul class="menu-profile">
                <li><a href="<?php echo site_url('member/profile');?>" class="<?php echo $active;?>">INFORMASI AKUN</a></li>
                <li><a href="<?php echo site_url('member/history');?>" class="<?php echo $active;?>">HISTORY PEMBELIAN</a></li>
                <?php
                if($member_type == 2){
                  if($status == 1){
                    ?>
                    <li><a href="<?php echo site_url('member/blog');?>" class="<?php echo $active;?>">BLOG</a></li>
                    <li><a href="<?php echo site_url('member/news');?>" class="<?php echo $active;?>">NEWS</a></li>
                    <li><a href="<?php echo site_url('member/event');?>" class="<?php echo $active;?>">EVENT</a></li>
                    <li><a href="<?php echo site_url('member/video');?>" class="<?php echo $active;?>">VIDEO</a></li>
                    <!-- <li><a href="<?php echo site_url('member/uped');?>" class="<?php echo $active;?>">UPED</a></li>
                    <li><a href="<?php echo site_url('member/message');?>" class="<?php echo $active;?>">PESAN</a></li> -->
                    <?php
                  }
                }else if($member_type == 3 || $member_type == 4){
                    ?>
                    <li><a href="<?php echo site_url('member/uped');?>" class="<?php echo $active;?>">PRODUCT</a></li>
                    <li><a href="<?php echo site_url('member/invoice');?>" class="<?php echo $active;?>">INVOICE ORDER</a></li> 
                    <li><a href="<?php echo site_url('member/message');?>" class="<?php echo $active;?>">PESAN</a></li> 
                    <?php
                }
                ?>
              </ul>
              <?php
            }
        }
        ?>
      </div>
      <?php
    }


    function AboutMenu($lang_id)
    {
      $arr_menu = $this->m_pages->ShowPages('about_menu');
      $uri2 = $this->uri->segment(2);
      if(is_array($arr_menu)){
        ?>
        <ul class="list-about">
          <?php
          foreach ($arr_menu as $key => $row) {
            $menu_name = $this->m_pages->ShowPagesDetail($row->pages_url,$lang_id,'pages_name');
            $title = $this->m_pages->ShowPagesDetail($row->pages_url,$lang_id,'title');
            $pages_alias = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row->pages_url),'pages_alias');
            $parent_id = $this->crud_global->GetField('tbl_pages',array('pages_id'=>$row->pages_url),'parent_id');

            $arr_child = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1,'parent_id'=>$row->pages_url));
            $pages_url = site_url('page/'.$pages_alias);

            $active = false;
            if($uri2 == $pages_alias){
              $active = 'active';
            }
            ?>
            <li><a href="<?php echo $pages_url;?>"  class="<?php echo $active;?>"><?php echo $title;?></a></li>
            <?php
          }
        ?>
        </ul>
        <?php
      }
    }

    function Footer($lang_id)
    {
      ?>
      <!-- 
      ========================================
      STEP FOOTER AREA START FORM HERE
      ========================================
      -->
          <footer class="step-main-footer">
              <!-- end footer top -->
              <div class="footer-bottom">
                  <div class="container">
                      <div class="row">
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <p>Copyright &copy; 2017 Hapindo Cipta Kharisma</p>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <ul>
                                  <li>Follow Us:</li>
                                  <li><a href="#" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Facebook"><i class="fa fa-facebook"></i></a></li>
                                  <li><a href="#" class="tooltipped" data-position="top" data-delay="50" data-tooltip="linkedin"><i class="fa fa-linkedin"></i></a></li>
                                  <li><a href="#" class="tooltipped" data-position="top" data-delay="50" data-tooltip="twitter"><i class="fa fa-twitter"></i></a></li>
                                  <li><a href="#" class="tooltipped" data-position="top" data-delay="50" data-tooltip="dribbble"><i class="fa fa-dribbble"></i></a></li>
                                  <li><a href="#" class="tooltipped" data-position="top" data-delay="50" data-tooltip="pinterest"><i class="fa fa-pinterest"></i></a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- end footer bottom -->
          </footer>
          <!-- 
      ========================================
      STEP FOOTER AREA END FORM HERE
      ========================================
      -->


          <!-- main jquery  -->
          <script src="<?php echo base_url();?>assets/front/js/jquery-2.2.4.min.js"></script>
          <!-- bootstrap js  -->
          <script src="<?php echo base_url();?>assets/front/js/bootstrap.min.js"></script>
          <!-- meterilaze js -->
          <script src="<?php echo base_url();?>assets/front/js/materialize.min.js"></script>
          <!-- images load jquery -->
          <script src="<?php echo base_url();?>assets/front/js/imagesloaded.pkgd.min.js"></script>
          <!-- jquery messonary  -->
          <script src="<?php echo base_url();?>assets/front/js/isotope.pkgd.min.js"></script>
          <!-- owal carosle js -->
          <script src="<?php echo base_url();?>assets/front/js/owl.carousel.min.js"></script>
          <!-- Counter up js -->
          <script src="<?php echo base_url();?>assets/front/js/jquery.counterup.min.js"></script>
          <!-- scroll up js -->
          <script src="<?php echo base_url();?>assets/front/js/jquery.scrollUp.min.js"></script>
          <!-- jquery light box -->
          <script src="<?php echo base_url();?>assets/front/js/lightbox.min.js"></script>
          <!-- magnifiopoup css -->
          <script src="<?php echo base_url();?>assets/front/js/jquery.magnific-popup.min.js"></script>
          <!-- jquery waypoints -->
          <script src="<?php echo base_url();?>assets/front/js/waypoints.min.js"></script>
          <!-- datepicker js -->
          <script src="<?php echo base_url();?>assets/front/js/datepicker.min.js"></script>
          <!-- swiper min js -->
          <script src="<?php echo base_url();?>assets/front/js/swiper.min.js"></script>
          <!-- apperar js -->
          <script src="<?php echo base_url();?>assets/front/js/jquery.appear.js"></script>
          <!-- jquery appear js -->
          <script src="<?php echo base_url();?>assets/front/js/jquery.countdown.min.js"></script>
          <!-- end jquery slider range -->
          <script src="<?php echo base_url();?>assets/front/js/slider-range.js"></script>
          <!-- custom scripts -->
          <script src="<?php echo base_url();?>assets/front/js/main.js"></script>
      <?php
    }
}