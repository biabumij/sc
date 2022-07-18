<!doctype html>
<html lang="en" class="fixed">
<head>
    <?php echo $this->Templates->Header();?>

    <style type="text/css">
        .list-menu{
    padding: 0;
    margin: 0;
    list-style: none;
}
.list-menu li{
    background: #f1f1f1;
    font-size: 14px;
    margin-bottom: 10px;
    border: 1px solid #c3c3c3;
    cursor:move;
}
.list-menu li i{
    display: inline-block;
    margin-right: 5px;
}
.list-menu li .menu-header a{
    color: #c1c1c1;
    float: right;
    font-size: 16px;
}
.list-menu .menu-header{
    padding: 10px;
}
.list-menu .menu-content{
    background: #fff;
    padding: 10px;
    border-top: 1px solid #c3c3c3;
    display: none;
}
.list-menu .menu-content.active{

}
.list-menu li a i{
    
}
    </style>
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
                        <li><a>Settings Pages Position</a></li>
                    </ul>
                </div>
            </div>
            <div class="row animated fadeInUp">
                <div class="col-sm-12 col-lg-12">
                    <div class="panel">
                        <div class="panel-header">
                            <h3 class="section-subtitle">Settings Pages Position</h3>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel">
                                        <form class="form-horizontal" action="<?php echo site_url('pages/pages_settings_process');?>" id="add_menu_pages">
                                            <input type="hidden" name="pages_type" value="pages">
                                            <input type="hidden" name="pages_position_category_id" value="<?php echo $this->uri->segment(3);?>">
                                            <div class="panel-header panel-success">
                                                <h3 class="panel-title">Pages</h3>
                                                <div class="panel-actions">
                                                    <ul>
                                                        <li class="action toggle-panel panel-expand"><span></span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="panel-content">
                                                <?php
                                                $arr_menu = $this->crud_global->ShowTableNew('tbl_pages',array('status'=>1),array('datecreated'=>'desc'));
                                                if(is_array($arr_menu)){
                                                    ?>
                                                    <div style="height: 300px;overflow: auto;border: 1px solid #ccc;padding: 5px 10px;">
                                                        <?php
                                                        foreach ($arr_menu as $key => $row_a) {
                                                            $checked = false;
                                                            ?>
                                                            <div class="checkbox-custom checkbox-primary">
                                                                <input type="checkbox" id="<?php echo $row_a->pages_alias;?>" value="<?php echo $row_a->pages_id;?>" name="pages_id[]" <?php echo $checked;?>>
                                                                <label class="check" for="<?php echo $row_a->pages_alias;?>"><?php echo $row_a->pages;?></label>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="error-block"></div>
                                                    <br />
                                                    <div class="text-right">
                                                         <button class="btn btn-primary btn-rounded" id="btn_add_pages" data-loading-text="Loading...">Add to Menu</button>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="panel">
                                        <div class="panel-header panel-warning">
                                            <h3 class="panel-title"><?php echo $row[0]->pages_position_category;?></h3>
                                            <div class="panel-actions">
                                                <ul>
                                                    <li class="action toggle-panel panel-expand"><span></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-content">
                                            <div id="data-menu">
                                                                
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
        
    </div>
</div>

    <?php echo $this->Templates->Footer();?>
    

    <script type="text/javascript">
            function load_menu()
              {
                $("#data-menu").load("<?php echo site_url('pages/load_menu/'.$id.'');?>"+"/"+Math.random(), function(responseTxt, statusTxt, xhr){
                   responseTxt.async = true;
                    if(statusTxt == "success"){
                      $(this).fadeIn();
                    }else
                    if(statusTxt == "error"){
                        alert("Error: " + xhr.status + ": " + xhr.statusText);
                    }
                });
              }
            function sort_menu()
              {
                $( "#sortable" ).sortable({
                    axis: 'y',
                    update: function (event, ui) {
                        var data = $(this).sortable('serialize');
                        console.log(ui);
                        // POST to server using $.post or $.ajax
                        $.ajax({
                            data: data,
                            type: 'POST',
                            url: '<?php echo site_url("pages/sort_menu");?>',
                            success: function (data) {
                                if(data == 'error'){
                                    alert('Error !!! Please Call Administrator');
                                }
                            }
                        });
                    }
                });
                $("#sortable").disableSelection();
              }
        </script>


        <script type="text/javascript">
            $(document).ready(function(){
                load_menu();

                $("#add_menu_pages").validate({
                    rules: {
                        'pages_id[]': {
                            required: true
                        }
                      },
                    errorPlacement:function(e,r){
                        $(".error-block").html(e);
                    },
                    submitHandler: function(form) {
                        // $("#loader").fadeIn();
                        $("#btn_add_pages").button('loading');

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
                                $("#btn_add_pages").button('reset');
                                $(".icheckbox").parent().removeClass('checked');
                                $(".icheckbox").removeAttr('checked');
                                load_menu();
                             }
                         }
                        });
                        return false;
                    }
                });

                $("#add_menu_custom").validate({
                    rules: {
                        'pages_label': {
                            required: true
                        },
                        'pages_url': {
                            required: true
                        }
                      },
                    errorPlacement:function(e,r){
                        $(".error-block1").html(e);
                    },
                    submitHandler: function(form) {
                        // $("#loader").fadeIn();
                        $("#btn_add_custom").button('loading');

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
                                $("#btn_add_custom").button('reset');
                                $(".c1").val("");
                                load_menu();
                             }
                         },
                         cache: false,
              contentType: false,
              processData: false
                        });
                        return false;
                    }
                });
            });
        </script>


</body>
</html>
