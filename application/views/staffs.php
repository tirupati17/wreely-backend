
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    print_r($error);
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php } ?>
    
    <section class="content-header">

    </section>
    <section class="content">
        <div class="row">
          <div class="col-xs-12 text-right">
              <a class="btn btn-raised btn-primary" href="#" id="memberAdd-button" data-toggle="modal" data-target="#modal-member"><i class="fa fa-plus"></i> Add New</a>
          </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                      <div class="row">
                        <div class="col-md-6">
                          <h3 class="box-title">Member List</h3>
                        </div>
                        <div class="col-md-6">
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>memberListing" method="POST" id="searchList">
                                <div class="input-group">
                                    <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control pull-right" style="width: 150px;" placeholder="Search"/>
                                    <div class="input-group-btn">
                                        <button class="btn btn-default searchList"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table id="member" class="table table-hover">
                            <tr>
                                <th>Staff Name</th>
                                <!--<th>Address</th>-->
                                <!-- <th>DOB</th> -->
                                <th>Contact No</th>
                                <th>Email Id</th>
<!--                                <th>Company Name</th>
                                <th>Occupation</th>
                                <th>Reference Source</th>-->
                            </tr>
                            <?php
                            if (!empty($userRecords)) {
                                foreach ($userRecords as $record) {
                                    ?>
                                    <tr id="<?php echo $record->userId; ?>" style="cursor: pointer;" data-toggle="modal" data-target="#modal-member" data-id="<?php echo $record->userId; ?>">
                                        <td data-id="name" data-type="in"><?php echo $record->name ?></td>
                                        <!--<td data-id="address" data-type="in"><?php //echo $record->address ?></td>-->
                                        <!-- <td data-id="dob" data-type="in"><?php //echo $record->dob ?></td> -->
                                        <td data-id="mobile" data-type="in"><?php echo $record->mobile ?></td>
                                        <td data-id="email" data-type="in"><?php echo $record->email ?></td>
<!--                                        <td data-id="company_name" data-type="cmb" data-rel="dd1" rel-to="company_id" data-text="<?php echo $record->company_id ?>"><?php echo $record->name ?></td>
                                        <td data-id="company_id" data-type="in" class="hidden-md hidden-lg"><?php echo $record->company_id ?></td>
                                        <td data-id="id" data-type="in" class="hidden-md hidden-lg"><?php echo $record->id ?></td>
                                        <td data-id="occupation" data-type="in"><?php echo $record->occupation ?></td>
                                        <td data-id="reference_source" data-type="in"><?php echo $record->reference_source ?></td>-->
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>
<!--                        <div id="dd1" data-rel="company_name" class="hidden-md hidden-lg cmbData">
                            <?php //echo $companyNames; ?>
                        </div>-->

                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div><!-- /.box -->
            </div>

            <div class="modal fade" id="modal-member" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Membership Type Details</h4>
                            </div>
                            <div class="modal-body">
                              <form role="form" action="" method="post" id="member-form" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Staff Full name</label>
                                                <input type="text" class="form-control modal-input" id="name" placeholder="Enter name" name="name" value="" maxlength="128">
                                                <input type="hidden" class="modal-input" value="" name="id" id="id" />
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Email</label>
                                                <input type="text" class="form-control modal-input" id="email" placeholder="Enter email" name="email" value="" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Contact Number</label>
                                                <input type="text" class="form-control modal-input" id="mobile" placeholder="Enter number" name="mobile" value="" maxlength="10">
                                            </div>
                                        </div>
<!--                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Address</label>
                                                <input type="text" class="form-control modal-input" id="address" placeholder="Enter address" name="address" value="" maxlength="128">
                                            </div>
                                        </div>-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Password</label>
                                                <input type="password" class="form-control modal-input" id="password" placeholder="Enter password" name="password" value="" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Confirm Password</label>
                                                <input type="password" class="form-control modal-input" id="confirm_password" placeholder="Enter password" name="confirm_password" value="" maxlength="128">
                                            </div>
                                        </div>
<!--                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dob">Date of Birth</label>
                                                <input class="form-control" placeholder="mm/dd/yyyy" name="dob" id="dob" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text">
                                            </div>
                                        </div>-->
                                        
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <button type="button" class="btn btn-raised btn-danger pull-left deleteMember" href="#" value="" id="memberButtonId">Delete</button>
                                        <input type="submit" class="btn btn-raised btn-default pull-right" value="Update" id="memberSubmitButton"/>
                                      </div>
                                    </div>
                                </div><!-- /.box-body -->
                              </form>
                            </div><!-- /.model-body -->
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    var mode = '<?php echo $mode ; ?>';
</script>
<script type="text/javascript">
    
    jQuery(document).ready(function () {
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "memberListing/" + value);
            jQuery("#searchList").submit();
        });

        $('#dob').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
        $('.table-hover').find('tr').click(function () {
            $("#memberSubmitButton").val("Update");
            $("#memberButtonId").show();
            $("#member-form").attr("action", "<?php echo base_url(); ?>user/editStaff");

            $("#memberButtonId").val($(this).data("id"));
            var cells = $("#table tr").eq($(this).index()).find("td");
            $(this).find('td').each(function (i) {
                var relto = $(this).attr('rel-to');
                if ($(this).attr('data-type') == 'in') {
                    $("#" + $(this).attr('data-id')).val($(this).text());
                } else if ($(this).attr('data-type') == 'cmb') {
                    var cmbElemId = $(this).attr('data-rel');
                    var cmbArr = jQuery.parseJSON($('#' + cmbElemId).html());
                    var option = '';
                    for (var i = 0; i < cmbArr.length; i++) {
                        option += '<option value="' + cmbArr[i].id + '">' + cmbArr[i].name + '</option>';
                    }
                    $("#cmb_" + $(this).attr('data-id')).append(option);
                    $("#cmb_" + $(this).attr('data-id')).val($(this).attr('data-text'));
                }

                if (typeof relto !== typeof undefined && relto !== false) {
                    $('#'+relto).val($(this).attr('data-text'));
                }
            });
        });

        jQuery('#memberAdd-button').click(function (e) {
            e.preventDefault();
            $('.modal-input').val('');
            $("#member-form").attr("action", "<?php echo base_url(); ?>user/addNewStaff");
            $("#memberSubmitButton").val("Add");
            $("#memberButtonId").hide();

//            $('.cmbData').each(function (i, obj) {
//                var cmbArr = jQuery.parseJSON($(this).html());
//                var option = '';
//                for (var i = 0; i < cmbArr.length; i++) {
//                    option += '<option value="' + cmbArr[i].id + '">' + cmbArr[i].name + '</option>';
//                }
//                $("#cmb_" + $(this).attr('data-rel')).append(option);
//            })
        });

        jQuery('.cmbClass').change(function (e) {
            $(this).nextAll('input').first().val($(this).val());
        });
        
        
        if(mode == "add"){
            jQuery("#memberAdd-button").trigger("click");
        }
        
        
    });
</script>
