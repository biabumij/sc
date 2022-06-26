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
                            <div class="tabs">
                                <ul class="nav nav-tabs ">
                                    <li class="active"><a href="#table" data-toggle="tab" aria-expanded="true">Table</a></li>
                                    <li class=""><a href="#add" data-toggle="tab" aria-expanded="true">Add New</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="table">
                                        <table id="basic-table" class="data-table table table-striped nowrap table-hover" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="30px">No</th>
                                                    <th>Member</th>
                                                    <th>Email</th>
                                                    <th>Member Type</th>
                                                    <th>Status</th>
                                                    <th width="150px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="add">
                                        <form id="inline-validation" method="POST" class="form-horizontal form-stripe form-submit" novalidate="novalidate" data-button="#btn-submit" action="<?php echo site_url('member_back/add');?>" data-redirect="<?php echo site_url('admin/member');?>">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Name<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="admin_name" name="name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Member Type<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <?php echo $this->m_member_back->SelectMemberType();?>
                                                </div>
                                            </div>
                                            <div class="form-group" id="anggota">
                                                <label for="name" class="col-sm-2 control-label">No Anggota<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="no_anggota" name="no_anggota">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Email<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="email" name="email" class="form-control"></input>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Password<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="password" name="password" class="form-control" id="password"></input>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Phone<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="phone" class="form-control"></input>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Province</label>
                                                <div class="col-sm-8">
                                                    <?php echo $this->m_member->SelectProvince();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Regencie</label>
                                                <div class="col-sm-8">
                                                    <?php echo $this->m_member->SelectRegencie();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">District</label>
                                                <div class="col-sm-8">
                                                    <?php echo $this->m_member->SelectDistrict();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Village</label>
                                                <div class="col-sm-8">
                                                    <?php echo $this->m_member->SelectVillage();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Address</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" name="address" data-required="false"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Zip Code</label>
                                                <div class="col-sm-8 col-md-4">
                                                    <input type="text" name="zip_code" class="form-control" data-required="false">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Photo</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <input type="hidden" class="form-control" name="member_photo" id="member_photo_val" />
                                                        <input type="text" id="member_photo" class="form-control" data-required="false"></input>
                                                        <span class="input-group-btn">
                                                        <a data-fancybox-type="iframe" class="btn btn-primary iframe-btn" href="<?php echo base_url();?>filemanager/dialog.php?type=1&field_id=member_photo" >Browse</a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div id="box-content_member_photo">
                                                        <img id="member_photo_prev" src="<?php echo base_url();?>assets/back/theme/images/no_photo.gif" class="img-responsive" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 control-label">Status<span class="required" aria-required="true">*</span></label>
                                                <div class="col-sm-8">
                                                    <?php $this->general->SelectStatus();?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-9">
                                                    <button type="submit" name="submit" class="btn btn-primary" id="btn-submit" data-loading-text="please wait..">Submit</button>
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
        </div>
        <a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
    </div>
</div>

	<?php echo $this->Templates->Footer();?>
    
    <script type="text/javascript">
        $(document).ready(function() {
            load_table("<?php echo site_url('member_back/table');?>");
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){

            $(".selectajax").on("change",function(){
                var val = $(this).val();
                var type = $(this).attr('data-type');
                var data_id = $(this).attr('data-id');

                if(type == 'regencies'){
                    $("#districts").html("");
                    $("#villages").html("");
                }else if(type == 'districts'){
                    $("#villages").html("");
                }
                $.ajax({
                 type: 'POST',
                 url: '<?php echo site_url("member/select_ajax");?>/'+Math.random(),
                 data: {
                  val : val,
                  type : type,
                  data_id : data_id
                 },
                 success: function (data) {
                    $("#"+type).html(data);
                 }
                });
            });

            $("#anggota").hide();
            $("#member_type").on("change",function(){
                var val = $(this).val();
                if(val == 2){
                    $("#anggota").show();
                }else {
                    $("#anggota").hide();
                }
            });
        });
    </script>


</body>
</html>
