<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-ticket"></i> Space Management</a></li>
          <li class="active">Flexible</li>
        </ol>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                  <a class="btn btn-raised btn-primary" href="#" id="spaceAdd-button" data-toggle="modal" data-target="#modal-space"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <div class="row">
                      <div class="col-md-6">
                        <h3 class="box-title">Space List</h3>
                      </div>
                      <div class="col-md-6">
                      <div class="box-tools">
                          <form action="<?php echo base_url() ?>spaceListing" method="POST" id="searchList">
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
                  <table class="table table-hover" id="space">
                    <tr>
                      <th>Membership Type</th>
                      <th>Company</th>
                      <th>Member Name</th>
                      <th>Renewal Date</th>
                      <th>Expire On</th>
                      <th>Status</th>
                      <!-- <th class="text-center">Actions</th> -->
                    </tr>
                    <?php
                    if(!empty($spaceRecords))
                    {
                        //print_r($spaceRecords);
                        foreach($spaceRecords as $record)
                        {
                    ?>
                    <tr id="<?php echo $record->id; ?>" style="cursor: pointer;" class="space-row" data-toggle="modal" data-target="#modal-space"
                    data-id="<?php echo $record->id; ?>"
                    data-seat_id="<?php echo $record->seat_id; ?>"
                    data-membership_type_id="<?php echo $record->membership_type_id; ?>"
                    data-plan_id="<?php echo $record->plan_id; ?>"
                    data-company_id="<?php echo $record->company_id; ?>"
                    data-member_id="<?php echo $record->member_id; ?>"
                    data-start_date="<?php echo $record->start_date; ?>"
                    data-expiry_date="<?php echo $record->expiry_date; ?>">
                      <td><?php echo $record->membership_name ?></td>
                      <td><?php echo $record->company_name ?></td>
                      <td><?php echo $record->member_name ?></td>
                      <td><?php $time = strtotime($record->start_date);
                      $formattedDate = date("F j, Y",$time);
                      echo $formattedDate
                      ?></td>
                      <td><?php $time = strtotime($record->expiry_date);
                      $formattedDate = date("F j, Y",$time);
                      echo $formattedDate
                      ?></td>
                      <td><?php
                          $formattedDate = new DateTime($record->expiry_date);
                          $dateNow = new DateTime();
                          if ($dateNow > $formattedDate) {
                              echo '<span class="label label-warning">Expired</span>';
                          } else {
                            echo '<span class="label label-success">Active</span>';
                          }
                      ?></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>

                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
            <div class="modal fade" id="modal-space" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Space Details</h4>
                      </div>
                      <div class="modal-body">
                        <form role="form" action="" method="post" id="space-form" role="form">
                          <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="planTypeSelection">Plan Type</label>
                                          <select class="form-control required" id="planTypeSelection" name="planTypeSelection" disabled="true">
                                              <option value="1">Select Plan Type</option>
                                              <?php
                                              if(!empty($planTypes))
                                              {
                                                  foreach ($planTypes as $pt)
                                                  {
                                                      ?>
                                                      <option value="<?php echo $pt->id ?>"><?php echo $pt->plan_name ?></option>
                                                      <?php
                                                  }
                                              }
                                              ?>
                                          </select>
                                      </div>
                                </div>
                                <div class="col-md-6">
                                      <div class="form-group">
                                          <input type="hidden" value="" name="planTypeId" id="planTypeId" />
                                          <input type="hidden" value="" name="spaceId" id="spaceId" />
                                          <label for="seatMembershipTypeId">Seat Type</label>
                                          <select class="form-control required" id="seatMembershipTypeId" name="seatMembershipTypeId">
                                              <option value="0">Select Seat Type</option>
                                              <?php
                                              if(!empty($membershipTypes))
                                              {
                                                  foreach ($membershipTypes as $mt)
                                                  {
                                                      ?>
                                                      <option value="<?php echo $mt->id ?>"><?php echo $mt->membership_name ?></option>
                                                      <?php
                                                  }
                                              }
                                              ?>
                                          </select>
                                      </div>
                                </div>
                            </div>
                            <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="companyId">Company</label>
                                          <select class="form-control required" id="companyId" name="companyId">
                                              <option value="0">Select company</option>
                                              <?php
                                              if(!empty($companies))
                                              {
                                                  foreach ($companies as $company)
                                                  {
                                                      ?>
                                                      <option value="<?php echo $company->id ?>"><?php echo $company->name ?></option>
                                                      <?php
                                                  }
                                              }
                                              ?>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="memberId">Members</label>
                                          <select class="form-control required" id="memberId" name="memberId">
                                              <option value="0">Select member</option>
                                              <?php
                                              if(!empty($members))
                                              {
                                                  foreach ($members as $member)
                                                  {
                                                      ?>
                                                      <option value="<?php echo $member->id ?>"><?php echo $member->full_name ?></option>
                                                      <?php
                                                  }
                                              }
                                              ?>
                                          </select>
                                      </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="renewalDate">Renewal Date</label>
                                          <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                            <input class="form-control pull-right" id="renewalDate" name="renewalDate" type="text">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="expiryDate">Expire On</label>
                                          <div class="input-group date">
                                              <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                              </div>
                                            <input class="form-control pull-right" id="expiryDate" name="expiryDate" type="text">
                                          </div>
                                      </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    <input type="submit" class="btn btn-raised btn-default pull-right" value="Update" id="spaceSubmitButton"/>
                                    <button type="button" class="btn btn-raised btn-danger pull-left deleteSpace" href="#" value="" id="spaceButtonId">Delete</button>
                                  </div>
                                </div>
                          </div><!-- /.box-body -->
                        </form>
                      </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "spaceListing/" + value);
            jQuery("#searchList").submit();
        });


        $('#companyId').change(function (e) {
            var companyId = $(this).val();
            fillMemberComboBox(companyId);
        });    

        $('#renewalDate').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        }).on("dp.change", function(e) {
            var renewalDate = $(".renewalDate").datepicker("getDate");
            var expiryDate =  renewalDate + 30;
            console.log(expiryDate);
            $('.expiryDate').datepicker('update', expiryDate);
        });

        $('#expiryDate').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });

        $('#status').attr('class', 'label label-warning');
        $('#status').text('Empty');

        $('#planTypeSelection').val(2); //By default flexible selected
        $("#planTypeId").val("2");

        jQuery('#spaceAdd-button').click(function(e){
          e.preventDefault();
          $("#space-form").attr("action", "<?php echo base_url(); ?>addSpace");
          $("#spaceSubmitButton").val("Add");
          $("#spaceButtonId").hide();

          $("#seatMembershipTypeId").val(0);
          $("#seatId").val(0);
          $("#companyId").val(0);
          $("#memberId").val(0);

          $('#status').text('Empty');
          $('#seatId').prop('disabled', true);
          //perform validation when user click add
        });

        jQuery(document).on("click", ".space-row", function(){
          $("#space-form").attr("action", "<?php echo base_url(); ?>editSpace");
          $("#spaceSubmitButton").val("Update");
          $("#spaceButtonId").show();
          $('#renewalDate').prop('disabled', false);
          $('#expiryDate').prop('disabled', false);

          var spaceId = $(this).data("id");
          var planTypeId = $(this).data("plan_id");
          var seatMembershipTypeId = $(this).data("membership_type_id");
          var seatId = $(this).data("seat_id");
          var companyId = $(this).data("company_id");
          var memberId = $(this).data("member_id");
          var renewalDate = $(this).data("start_date");
          var expiryDate = $(this).data("expiry_date");
          $("#spaceButtonId").val(spaceId);

          $("#spaceId").val(spaceId);
          $("#planTypeId").val(planTypeId);
          $("#seatMembershipTypeId").val(seatMembershipTypeId);
          $("#seatId").val(seatId);
          $("#companyId").val(companyId);

          fillMemberComboBox(companyId, memberId ,true);

          $("#memberId").val(memberId);
          $("#renewalDate").val(renewalDate);
          $("#expiryDate").val(expiryDate);
        });

        function fillMemberComboBox(companyId, memberId = '' ,sel = '' ){
            if(companyId){
                $.ajax({
                    url: "/member/getMembersByCompanyId/"+companyId,
                    method: 'GET',
                    data: '',
                    dataType: 'JSON',
                    cache: false,
                    success: function (data) {
                        console.log("data from server  -------- ",data);
                        $('#memberId option[value!="0"]').remove();
                        var selected = '';
                        for(var x in data){
                             selected = '';
                            if(sel && memberId == +data[x].id ){
                                selected = 'selected="selected"' ;
                            }
                            $('#memberId').append('<option value="'+data[x].id+'" '+selected+'>'+data[x].full_name+'</option>');
                        }
                    }
                });
            }
        }
    });
</script>
