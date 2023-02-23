<?php

class Templates extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function Header()
    {
		$jenis_usaha = $this->crud_global->GetField('pmm_setting_production',array('id'=>1),'jenis_usaha');
    	?>
    	<meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	    <title><?= $jenis_usaha;?></title>
	    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url().$this->m_themes->GetThemes('site_favico');?>">
	    
	    <script src="<?php echo base_url();?>assets/back/theme/vendor/pace/pace.min.js"></script>
	    <link href="<?php echo base_url();?>assets/back/theme/vendor/pace/pace-theme-minimal.css" rel="stylesheet" />
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/bootstrap/css/bootstrap.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/font-awesome/css/font-awesome.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/animate.css/animate.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/toastr/toastr.min.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/magnific-popup/magnific-popup.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/stylesheets/css/style.css?=<?php echo time();?>">

	    <style type="text/css">
	    	.select2-container{
	    		/*margin-bottom: 20px !important;*/
	    		width: 100% !important;
	    	}
	    </style>
    	<?php
    }

    function PageHeader()
    {
    	$admin_id = $this->session->userdata('admin_id');
    	$admin_name = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$admin_id),'admin_name');
    	$admin_photo = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$admin_id),'admin_photo');
    	$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$admin_id),'admin_group_id');
    	$admin_group = $this->crud_global->GetField('tbl_admin_group',array('admin_group_id'=>$admin_group_id),'admin_group_name');
    	$jenis_usaha = $this->crud_global->GetField('pmm_setting_production',array('id'=>1),'jenis_usaha');
    	?>
    	<div class="page-header">
	        <div class="leftside-header">
	            <div class="logo">
	                <a href="" class="on-click" style="color: #fff;font-weight: bold;display: block;text-align: center;font-size: 24px;padding-top: 15px;">
	                    <!-- <img alt="logo" src="<?php echo base_url().$this->m_themes->GetThemes('site_logo');?>" class="img-responsive" /> -->
	                    <?php echo $this->m_themes->GetThemes('site_name');?> - <?= $jenis_usaha;?>
	                </a>
	            </div>
	            <div id="menu-toggle" class="visible-xs toggle-left-sidebar" data-toggle-class="left-sidebar-open" data-target="html">
	                <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
	            </div>
	        </div>
	        <div class="rightside-header">
	            <div class="header-middle"></div>
	            <div class="header-section" id="search-headerbox">
	                <input type="text" name="search" id="search" placeholder="Search...">
	                <i class="fa fa-search search" id="search-icon" aria-hidden="true"></i>
	                <div class="header-separator"></div>
	            </div>
	            <div class="header-section" id="user-headerbox">
	                <div class="user-header-wrap">
	                    <div class="user-photo">
	                    	<?php
	                    	if(!empty($admin_photo)){
	                    		?>
	                    		<img src="<?php echo base_url().$admin_photo;?>" alt="<?php echo $admin_name;?>" class="img-responsive" />
	                    		<?php
	                    	}else {
	                    		?>
	                    		<img src="<?php echo base_url();?>assets/back/theme/images/no-avatar.png" alt="<?php echo $admin_name;?>" class="img-responsive" />
	                    		<?php	
	                    	}
	                    	?>
	                        
	                    </div>
	                    <div class="user-info">
	                        <span class="user-name"><?php echo $admin_name;?></span>
	                        <span class="user-profile"><?php echo $admin_group;?></span>
	                    </div>
	                    <i class="fa fa-plus icon-open" aria-hidden="true"></i>
	                    <i class="fa fa-minus icon-close" aria-hidden="true"></i>
	                </div>
	                <div class="user-options dropdown-box">
	                    <div class="drop-content basic">
	                        <ul>
	                            <li> <a href="<?php echo site_url('admin/form_edit/'.$admin_id);?>"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
	                            <!-- <li> <a href="pages_lock-screen.html"><i class="fa fa-lock" aria-hidden="true"></i> Lock Screen</a></li> -->
	                            <!-- <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> Configurations</a></li> -->
	                        </ul>
	                    </div>
	                </div>
	            </div>
	            <div class="header-separator"></div>
	            <div class="header-section">
	                <a href="<?php echo site_url('admin/logout');?>" data-toggle="tooltip" data-placement="left" title="Logout"><i class="fa fa-sign-out log-out" aria-hidden="true"></i></a>
	            </div>
	        </div>
	    </div>
    	<?php
    }


    function LeftBar()
    {
    	?>
    	<div class="left-sidebar">
            <!-- left sidebar HEADER -->
            <div class="left-sidebar-header">
                <div class="left-sidebar-title">Navigation</div>
                <div class="left-sidebar-toggle c-hamburger c-hamburger--htla hidden-xs" data-toggle-class="left-sidebar-collapsed" data-target="html">
                    <span></span>
                </div>
            </div>
            <!-- NAVIGATION -->
            <!-- ========================================================= -->
            <div id="left-nav" class="nano">
                <div class="nano-content">
                    <nav>
                        <ul class="nav" id="main-nav">
                        	<?php 
                        	$admin_id = $this->session->userdata('admin_id');
                        	$admin_group_id = $this->crud_global->GetField('tbl_admin',array('admin_id'=>$admin_id),'admin_group_id');
                        	$arr_access = $this->crud_global->ShowTableDefault('tbl_admin_access',array('admin_group_id'=>$admin_group_id));
                        	$uri2 = $this->uri->segment(2);
					        $a = false;
					        if(is_array($arr_access)){
					            foreach ($arr_access as $key => $value) {
					                $a[] = $value->menu_id;
					            }
					        }

					        $arr_menu=false;
		                    $this->db->select('*');
		                    $this->db->where('status =',1);
		                    $this->db->where('parent_id =',0);
		                    $this->db->where_in('menu_id',$a);
		                    $this->db->order_by('order_group_id','asc');
		                    $this->db->order_by('order_id','asc');
		                    $query = $this->db->get('tbl_menu');
		                    if($query->num_rows() > 0){
		                        $arr_menu=$query->result();
		                    }

                        	if(is_array($arr_menu)){
                        		$order_group_id = false;
                        		$order_group_id = false;
                        		foreach ($arr_menu as $key => $row) {
                        			

                        			if($uri2 == $row->menu_alias){
                        				$active = 'active-item';
                        			}else {
                        				$active = false;
                        			}

                        			if(!empty($row->file_cont)){
		                                $url_menu = "href='".site_url('admin/'.$row->menu_alias.'')."'";
		                            }else {
		                                $url_menu= false;
		                            }

		                            $arr_menu_child =false;
		                            $this->db->select('*');
		                            $this->db->where('status =',1);
		                            $this->db->where('parent_id =',$row->menu_id);
		                            $this->db->where_in('menu_id',$a);
		                            $this->db->order_by('order_id','asc');
		                            $query_child = $this->db->get('tbl_menu');
		                            if($query_child->num_rows() > 0){
		                                $arr_menu_child = $query_child->result();
		                            }
		                            
		                            // $arr_menu_child = $this->crud_global->ShowTableNew('tbl_menu',array('status'=>1,'parent_id'=>$row->menu_id));

		                            if(is_array($arr_menu_child)){
		                            	$has_child = 'has-child-item';
		                            	$close_item = 'close-item';
		                            	foreach ($arr_menu_child as $key_child => $row_child) {
                    						if($uri2 == $row_child->menu_alias){
                    							if($row_child->parent_id == $row->menu_id){
                    								$close_item = 'open-item';
                    							}
		                        			}
		                        		}
		                            }else {
		                            	$has_child = false;
		                            	$close_item = false;
		                            }

		                            if($order_group_id !== false){
		                            	if($order_group_id != $row->order_group_id){
	                        				echo '<hr class="hr-menu" />';
	                        			}
		                            }
		                            
                        			// echo $order_group_id .'='.$row->order_group_id;
                        			$order_group_id = $row->order_group_id;
                        			?>
                        			<li class="<?php echo $active.' '.$has_child.' '.$close_item;?>"><a <?php echo $url_menu;?>><i class="<?php echo $row->menu_icon;?>"></i><span><?php echo $row->menu_name;?></span></a>
                        				<?php

                        				if(is_array($arr_menu_child)){
                        					?>
                        					<ul class="nav child-nav level-1">
	                        					<?php
	                        					foreach ($arr_menu_child as $key_child => $row_child) {
	                        						if($uri2 == $row_child->menu_alias){
				                        				$active_child = 'active-item';
				                        			}else {
				                        				$active_child = false;
				                        			}

				                        			$arr_menu_child_2 =false;
						                            $this->db->select('*');
						                            $this->db->where('status =',1);
						                            $this->db->where('parent_id =',$row_child->menu_id);
						                            $this->db->where_in('menu_id',$a);
						                            $this->db->order_by('order_id','asc');
						                            $query_child = $this->db->get('tbl_menu');
						                            if($query_child->num_rows() > 0){
						                                $arr_menu_child_2 = $query_child->result();
						                            }

				                        			if(is_array($arr_menu_child_2)){
						                            	$has_child_2 = 'has-child-item';
						                            	$close_item_2 = 'close-item';
						                            	foreach ($arr_menu_child_2 as $row_child_2) {
				                    						if($uri2 == $row_child_2->menu_alias){
				                    							if($row_child_2->parent_id == $row_child->menu_id){
				                    								$close_item_2 = 'open-item';
				                    							}
						                        			}
						                        		}
						                            }else {
						                            	$has_child_2 = false;
						                            	$close_item_2 = 'close-item';
						                            }

						                            if(!empty($row_child->file_cont)){
						                                $url_menu_2 = "href='".site_url('admin/'.$row_child->menu_alias.'')."'";
						                            }else {
						                                $url_menu_2= false;
						                            }
	                        						?>
	                        						<li class="<?php echo $has_child_2.' '.$close_item_2.' '.$active_child;?>">
	                        							<a <?php echo $url_menu_2;?> ><?php echo $row_child->menu_name;?></a>

	                        							<?php
	                        							if(is_array($arr_menu_child_2)){


							                        		?>
						                        			<ul class="nav child-nav level-2 " style="">
						                        				<?php
						                        				foreach ($arr_menu_child_2 as $row_child_2) {
					                        						if($uri2 == $row_child_2->menu_alias){
								                        				$active_child_2 = 'active-item';
								                        			}else {
								                        				$active_child_2 = false;
								                        			}

								                        			?>
								                        			<li class="<?php echo $active_child_2;?>">
	                        											<a href="<?php echo site_url('admin/'.$row_child_2->menu_alias);?>" ><?php echo $row_child_2->menu_name;?></a>
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
                    </nav>
                </div>
            </div>
        </div>
    	<?php
    }


    function Footer()
    {
    	?>
    	<script src="<?php echo base_url();?>assets/back/theme/vendor/jquery/jquery-1.12.3.min.js"></script>
    	<script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/jquery/jquery-ui.min.js"></script>
		<script src="<?php echo base_url();?>assets/back/theme/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>assets/back/theme/vendor/nano-scroller/nano-scroller.js"></script>
		<script src="<?php echo base_url();?>assets/back/theme/javascripts/template-script.min.js"></script>
		<script src="<?php echo base_url();?>assets/back/theme/javascripts/template-init.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/sweetalert/sweetalert.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/data-table/media/css/dataTables.bootstrap.min.css">

	    <script src="<?php echo base_url();?>assets/back/theme/vendor/sweetalert/sweetalert.min.js"></script>
	    <script src="<?php echo base_url();?>assets/back/theme/vendor/data-table/media/js/jquery.dataTables.min.js"></script>
	    <script src="<?php echo base_url();?>assets/back/theme/vendor/data-table/media/js/dataTables.bootstrap.min.js"></script>
	    <script src="<?php echo base_url();?>assets/back/theme/vendor/jquery-validation/jquery.validate.min.js"></script>

	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/select2/css/select2.min.css">
	    <link rel="stylesheet" href="<?php echo base_url();?>assets/back/theme/vendor/select2/css/select2-bootstrap.min.css">
	    <script src="<?php echo base_url();?>assets/back/theme/vendor/select2/js/select2.min.js"></script>

	    <script type="text/javascript" src="<?php echo base_url();?>assets/back/theme/vendor/tinymce/tinymce.min.js"></script>

	    <script>
		$(document).ready(function(){
		    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		        localStorage.setItem('activeTab', $(e.target).attr('href'));
		    });
		    var activeTab = localStorage.getItem('activeTab');
		    if(activeTab){
		        $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
		    }

		    console.log(activeTab);
		});
		</script>
		
	    <script src="<?php echo base_url();?>assets/back/theme/javascripts/my.js"></script>

	     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    	<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/back/theme/vendor/jquery-toastr/jquery.toast.min.css">
    	<script src="<?php echo base_url();?>assets/back/theme/vendor/jquery-toastr/jquery.toast.min.js"></script>
		


    	<?php
    	if(!empty($this->session->flashdata('notif_error'))){
    		?>
    		<script type="text/javascript">
    			$.toast({
				    heading: 'Error',
				    text: '<?= $this->session->flashdata('notif_error');?>',
				    showHideTransition: 'fade',
				    icon: 'error',
				    position: 'top-right',
				});
    		</script>
    		
    		<?php
    	}

    	if(!empty($this->session->flashdata('notif_success'))){
    		?>
    		<script type="text/javascript">

				$.toast({
				    heading: 'Success',
				    text: '<?= $this->session->flashdata('notif_success');?>',
				    showHideTransition: 'fade',
				    icon: 'success',
				    position: 'top-right',
				});
    		</script>
    		
    		<?php
    	}
    	?>


    	<?php
    }




}