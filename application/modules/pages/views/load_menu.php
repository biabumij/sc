<?php
$arr_menu = $this->crud_global->ShowTableNew('tbl_pages_position',array('pages_position_category_id'=>$id),array('sort_id'=>'asc'));
	if(is_array($arr_menu)){
		?>
		<form class="form-horizontal" action="<?php echo site_url('ajax_admin/pages/update_data_pages');?>" id="update_menu_pages">
			<input type="hidden" name="count_menu" value="<?php echo count($arr_menu);?>">

			<ul id="sortable" class="list-menu">
				<?php
				$no = 1;
				foreach ($arr_menu as $key => $row) {
					?>
					<input type="hidden" name="pages_position_id_<?php echo $no;?>" value="<?php echo $row->pages_position_id;?>">
					<li id="item-<?php echo $row->pages_position_id;?>" class="ui-state-default">
						<div class="menu-header">
							<i class="fa fa-arrows"></i>
							<?php echo $row->pages_label;?>
							<a href="javascript:void(0);" data-content="#<?php echo $row->pages_position_id;?>"><i class="fa fa-caret-down"></i></a>
						</div>
						<div class="menu-content" id="<?php echo $row->pages_position_id;?>">
							<input type="hidden" class="form-control" name="pages_label_<?php echo $no;?>" value="<?php echo $row->pages_label;?>">
							<!-- <div class="form-group">
								<label class="control-label">Label</label>
								<input type="text" class="form-control" name="pages_label_<?php echo $no;?>" value="<?php echo $row->pages_label;?>">
							</div> -->
							<?php
							if($row->pages_type == 'custom_link'){
								?>
								<div class="form-group">
									<label class="control-label">Pages Url</label>
									<input type="text" class="form-control" name="pages_url_<?php echo $no;?>" value="<?php echo $row->pages_url;?>">
								</div>
								<?php
							}else {
								?>
								<input type="hidden" class="form-control" name="pages_url_<?php echo $no;?>" value="<?php echo $row->pages_url;?>">
								<?php
							}
							?>
							<!-- <div class="form-group">
								<?php
								if(!empty($row->open_link)){
									$checked = 'checked';
								}else {
									$checked = false;
								}
								?>
								<input type="checkbox" class="icheckbox" id="open_link" name="open_link_<?php echo $no;?>" value="1" <?php echo $checked;?>> Open Link in new tab
							</div>	 -->
							<a href="javascript:void(0);" class="btn btn-small btn-danger delete-menu" data-id="<?php echo $row->pages_position_id;?>">Remove</a>
						</div>
					</li>
					<?php
					$no++;
				}
				?>
			</ul>
			<div class="error-block-menu"></div>
			<!-- <button name="submit" type="submit" id="btn-update-pages" class="btn btn-small btn-primary">Save</a> -->
		</form>
		<?php
	}else {
		echo '- No Data Menu -';
	}
?>

<script type="text/javascript">
  $(document).ready(function(){
  	sort_menu();
  	$(".menu-header a").click(function(){
  		var content = $(this).attr('data-content');
  		$(content).slideToggle('fast');
  	});

  	$(".delete-menu").click(function(){
  		$(this).button('loading');
  		var id = $(this).attr('data-id');
        $.ajax({
         type: "POST",
         url:  "<?php echo site_url('pages/delete_menu');?>",
         data: {id : id},
         dataType: 'json',
         beforeSend:function(){
            
         },
         success: function (data) {
             var output = data.output;
             if(output == 'true'){
                // $(this).button('reset');
                load_menu();
             }
         }
        });
  	});

  	$("#update_menu_pages").validate({
        errorPlacement:function(e,r){
            $(".error-block-menu").html(e);
        },
        submitHandler: function(form) {
            // $("#loader").fadeIn();
            $("#btn-update-pages").button('loading');

            $.ajax({
             type: "POST",
             url:  $(form).attr('action'),
             data: $(form).serialize(),
             dataType: 'json',
             beforeSend:function(){
                
                // $("#loader").fadeIn();
             },
             success: function (data) {
                 var output = data.output;
                 if(output == 'true'){
                    $("#btn-update-pages").button('reset');
                    load_menu();
                 }
             }
            });
            return false;
        }
    });
    

    $(".form-control").each(function () {
    	$(this).rules('add', {
            required: true
        });
    });

  });
</script>