<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-ticket"></i> Space Management</a></li>
          <li class="active">Membership & Seats</li>
        </ol>
      </h1>
    </section>
    <section class="content">
      <div class ="row">
          <div class="col-md-7">
            <div class="form-group text-right">
                <a class="btn btn-raised btn-primary" href="#" id="membershipTypeAdd-button" data-toggle="modal" data-target="#modal-membershipType"><i class="fa fa-plus"></i> Add New</a>
            </div>
            <div class="box">
              <div class="box-header">
                  <div class="row">
                    <div class="col-md-6">
                      <h3 class="box-title">Membership Type</h3>
                    </div>
                    <div class="col-md-6">
                      <div class="box-tools">
                          <form action="<?php echo base_url() ?>membershipTypeAndSeatListing" method="POST" id="membershipTypeSearchList">
                              <div class="input-group">
                                <input type="text" name="membershipTypeSearchText" value="<?php echo $membershipTypeSearchText; ?>" class="form-control pull-right" style="width: 150px;" placeholder="Search"/>
                                <div class="input-group-btn">
                                  <button class="btn btn-default membershipTypeSearchList"><i class="fa fa-search"></i></button>
                                </div>
                              </div>
                          </form>
                      </div>
                     </div>
                   </div>
              </div><!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover" id="membershipType">
                  <tr>
                    <th>Plan Type</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Number Of Day</th>
                  </tr>
                      <?php
                      if(!empty($membershipTypeRecords))
                      {
                          foreach($membershipTypeRecords as $record)
                          {
                      ?>
                            <tr id="<?php echo 'membership'.$record->id; ?>" style="cursor: pointer;" class="membershipType-row" data-toggle="modal" data-target="#modal-membershipType"
                            data-id="<?php echo $record->id; ?>"
                            data-plan_id="<?php echo $record->plan_id; ?>"
                            data-membership_name="<?php echo $record->membership_name; ?>"
                            data-quantity="<?php echo $record->quantity; ?>"
                            data-number_of_day="<?php echo $record->number_of_day; ?>"
                            data-price="<?php echo $record->price; ?>">
                              <td><?php echo $record->plan_name ?></td>
                              <td><?php echo $record->membership_name ?></td>
                              <td><?php echo $record->price ?></td>
                              <td><?php
                              if ($record->quantity == 0) {
                                echo "--";
                              } else {
                                echo $record->quantity;
                              }
                              ?></td>
                              <td><?php
                              if ($record->number_of_day == 0) {
                                echo "--";
                              } else {
                                echo $record->number_of_day;
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
          <div class="col-md-5">
            <div class="form-group text-right">
                <a class="btn btn-raised btn-primary" href="#" id="seatAdd-button" data-toggle="modal" data-target="#modal-seat"><i class="fa fa-plus"></i> Add New</a>
            </div>
            <div class="box">
              <div class="box-header">
                <div class="row">
                  <div class="col-md-6">
                    <h3 class="box-title">Seats</h3>
                  </div>
                  <div class="col-md-6">
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>membershipTypeAndSeatListing" method="POST" id="seatSearchList">
                            <div class="input-group">
                              <input type="text" name="seatSearchText" value="<?php echo $seatSearchText; ?>" class="form-control pull-right" style="width: 150px;" placeholder="Search"/>
                              <div class="input-group-btn">
                                <button class="btn btn-default seatSearchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover" id="seats">
                  <tr>
                    <th>Name</th>
                    <th>Membership Type</th>
                  </tr>
                      <?php
                      if(!empty($seatsRecords))
                      {
                          foreach($seatsRecords as $record)
                          {
                      ?>
                            <tr id="<?php echo 'seat'.$record->id; ?>" style="cursor: pointer;" class="seats-row" data-toggle="modal" data-target="#modal-seat"
                              data-id="<?php echo $record->id; ?>"
                              data-seat_name="<?php echo $record->seat_name; ?>"
                              data-membership_type_id="<?php echo $record->membership_type_id; ?>">
                              <td><?php echo $record->seat_name ?></td>
                              <td><?php echo $record->membership_name ?></td>
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

         <div class="modal fade" id="modal-membershipType" style="display: none;">
             <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">×</span></button>
                   <h4 class="modal-title">Seat Type Details</h4>
                 </div>
                 <div class="modal-body">
                   <form role="form" action="" method="post" id="membershipType-form" role="form">
                     <div class="box-body">
                         <div class="row">
                           <div class="col-md-6">
                               <div class="form-group">
                             <label for="planTypeId">Plan type</label>
                             <select class="form-control required" id="planTypeId" name="planTypeId">
                                 <option value="0">Select Plan Type</option>
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
                         </div>
                         <div class="row">
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="full_name">Seat type name</label>
                                     <input type="text" class="form-control" id="membershipName" placeholder="Name" name="membershipName" value="" maxlength="128">
                                     <input type="hidden" value="" name="membershipTypeId" id="membershipTypeId" />
                                 </div>

                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="price">Price</label>
                                     <input type="text" class="form-control" id="price" placeholder="Enter email" name="price" value="" maxlength="128">
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="quantity">Quantity</label>
                                   <input type="text" class="form-control" id="quantity" placeholder="Enter quantity" name="quantity" value="" maxlength="128">
                               </div>
                           </div>
                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="numberOfDays">Number of days</label>
                                   <input type="text" class="form-control" id="numberOfDays" placeholder="Enter no of days" name="numberOfDays" value="" maxlength="128">
                               </div>
                           </div>
                         </div>
                         <div class="row">
                           <div class="col-md-12">
                             <input type="submit" class="btn btn-raised btn-default pull-right" value="Update" id="membershipTypeSubmitButton"/>
                             <button type="button" class="btn btn-raised  btn-danger pull-left deleteMembershipType" href="#" value="" id="membershipTypeButtonId">Delete</button>
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
         <div class="modal fade" id="modal-seat" style="display: none;">
             <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">×</span></button>
                   <h4 class="modal-title">Seats Details</h4>
                 </div>
                 <div class="modal-body">
                   <form role="form" action="" method="post" id="seats-form" role="form">
                     <div class="box-body">
                         <div class="row">
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="name">Seat name</label>
                                     <input type="text" class="form-control" id="seatName" placeholder="Name" name="seatName" value="" maxlength="128">
                                     <input type="hidden" value="" name="seatId" id="seatId" />
                                 </div>

                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
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
                                     <!-- <label for="seatMembershipTypeId">Membership type</label> -->
                                     <!-- <input type="text" class="form-control" id="seatMembershipTypeId" placeholder="Membership Type" name="seatMembershipTypeId" value="" maxlength="128"> -->
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                           <div class="col-md-12">
                             <input type="submit" class="btn btn-raised btn-default pull-right" value="Update" id="seatSubmitButton"/>
                             <button type="button" class="btn btn-raised btn-danger pull-left deleteSeat" href="#" value="" id="seatButtonId">Delete</button>
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
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#membershipTypeSearchList").attr("action", baseURL + "membershipTypeAndSeatListing/" + value);
            jQuery("#membershipTypeSearchList").submit();
        });

        $('#planTypeId').change(function() {
            planTypeSelection();
        });

        function planTypeSelection() {
          if ($('#planTypeId option').filter(':selected').val() == 1) { //Dedicated
            $('#quantity').prop('disabled', false);
            $('#numberOfDays').prop('disabled', true);
          } else {
            $('#quantity').prop('disabled', true);
            $('#numberOfDays').prop('disabled', false);
          }
        }

        jQuery('#membershipTypeAdd-button').click(function(e){
              e.preventDefault();
              $("#membershipType-form").attr("action", "<?php echo base_url(); ?>addMembershipType");
              $("#membershipTypeSubmitButton").val("Add");
              $("#membershipTypeButtonId").hide();
              planTypeSelection();

              $("#membershipName").val("");
              $("#price").val("");
              $("#quantity").val("");
              $("#numberOfDays").val("");
              $("#planTypeId").val(0);
              //perform validation when user click add
        });

        jQuery(document).on("click", ".membershipType-row", function() {
          $("#membershipType-form").attr("action", "<?php echo base_url(); ?>editMembershipType");
          $("#membershipTypeSubmitButton").val("Update");
          $("#membershipTypeButtonId").show();

          var membershipTypeId = $(this).data("id");
          var planTypeId = $(this).data("plan_id");
          var membershipName = $(this).data("membership_name");
          var price = $(this).data("price");
          var quantity = $(this).data("quantity");
          var numberOfDays = $(this).data("number_of_day");
          $("#membershipTypeButtonId").val(membershipTypeId);

          $("#membershipTypeId").val(membershipTypeId);
          $("#planTypeId").val(planTypeId);
          $("#membershipName").val(membershipName);
          $("#price").val(price);
          $("#quantity").val(quantity);
          $("#numberOfDays").val(numberOfDays);

          planTypeSelection();
        });

        jQuery('#seatAdd-button').click(function(e){
          e.preventDefault();
          $("#seats-form").attr("action", "<?php echo base_url(); ?>addSeat");
          $("#seatSubmitButton").val("Add");
          $("#seatButtonId").hide();

          $("#seatName").val("");
          $("#seatMembershipTypeId").val(0);
          //perform validation when user click add
        });

        jQuery(document).on("click", ".seats-row", function(){
          $("#seats-form").attr("action", "<?php echo base_url(); ?>editSeat");
          $("#seatSubmitButton").val("Update");
          $("#seatButtonId").show();

          var seatId = $(this).data("id");
          var name = $(this).data("seat_name");
          var seatMembershipTypeId = $(this).data("membership_type_id");
          $("#seatButtonId").val(seatId);

          $("#seatId").val(seatId);
          $("#seatName").val(name);
          $("#seatMembershipTypeId").val(seatMembershipTypeId);
        });
    });
</script>
