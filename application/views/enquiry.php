
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-id-card"></i> Reception Management</a></li>
          <li class="active">Enquiry</li>
        </ol>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <div class="row">
                      <div class="col-md-6">
                        <h3 class="box-title">Enquiry List</h3>
                      </div>
                      <div class="col-md-6">
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>enquiriesListing" method="POST" id="searchList">
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
                  <table class="table table-hover" id="enquiry">
                    <tr>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Email</th>
                      <th>Reference</th>
                      <th>How many people</th>
                      <th>Reason</th>
                      <th>About User</th>
                      <th>Notes (By Admin)</th>
                    </tr>
                    <?php
                    if(!empty($enquiriesRecords))
                    {
                        foreach($enquiriesRecords as $record)
                        {
                    ?>
                    <!-- " -->
                    <tr id="<?php echo $record->id; ?>" style="cursor: pointer;" class="enquiry-row" data-toggle="modal" data-target="#modal-default"
                    data-id="<?php echo $record->id; ?>"
                    data-name="<?php echo $record->full_name; ?>"
                    data-contact_no="<?php echo $record->contact_no; ?>"
                    data-email_id="<?php echo $record->email_id; ?>"
                    data-reference_source="<?php echo $record->reference_source; ?>"
                    data-how_many_people="<?php echo $record->how_many_people; ?>"
                    data-reason="<?php echo $record->reason; ?>"
                    data-tell_us_more="<?php echo $record->tell_us_more; ?>"
                    data-notes="<?php echo $record->notes; ?>">

                      <td ><?php echo $record->full_name ?></td>
                      <td><?php echo $record->contact_no ?></td>
                      <td><?php echo $record->email_id ?></td>
                      <td><?php echo $record->reference_source ?></td>
                      <td><?php echo $record->how_many_people ?></td>
                      <td><?php echo $record->reason ?></td>
                      <td><?php echo substr($record->tell_us_more, 0, 15) ?></td>
                      <td> <?php
                        if (strlen($record->notes)) {
                          echo '<span class="label label-success">View notes</span>';
                        } else {
                          echo '<span class="label label-warning">Click to add</span>';
                        }
                       ?></td>
                      <td class="text-center">
                          <!-- <a class="btn btn-sm btn-info" href="<?php echo base_url().'editOldEnquiry/'.$record->id; ?>"><i class="fa fa-pencil"></i></a> -->
                          <!-- <a class="btn btn-sm btn-danger deleteEnquiry" href="#" data-enquiryid="<?php echo $record->id; ?>"><i class="fa fa-trash"></i></a> -->
                      </td>
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

            <div class="modal fade" id="modal-default" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                      <h4 class="modal-title">Enquiry Details</h4>
                    </div>
                    <div class="modal-body">
                      <form role="form" action="<?php echo base_url() ?>editEnquiry" method="post" id="editEnquiry" role="form">
                        <div class="box-body">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <label for="notes">Notes (By Admin)</label>
                                      <textarea class="form-control" id="notes" placeholder="Enter notes" name="notes" rows="3"></textarea>
                                  </div>
                              </div>
                          </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" placeholder="Full Name" name="full_name" value="" maxlength="128">
                                        <input type="hidden" value="" name="enquiryId" id="enquiryId" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_id">Email address</label>
                                        <input type="email" class="form-control" id="email_id" placeholder="Enter email" name="email_id" value="" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="occupation">Occupation</label>
                                        <input type="text" class="form-control" id="occupation" placeholder="Occupation" name="occupation" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_no">Contact No</label>
                                        <input type="text" class="form-control" id="contact_no" placeholder="Contact No" name="contact_no" value="" maxlength="15">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reference_source">Reference Source</label>
                                        <input type="text" class="form-control" id="reference_source" placeholder="Reference Source" name="reference_source" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reason">Reason</label>
                                        <input type="text" class="form-control" id="reason" placeholder="Reason" name="reason" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="how_many">How Many People</label>
                                        <input type="text" class="form-control" id="how_many" placeholder="How Many" name="how_many" value="" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tell_us_more">More About User</label>
                                        <textarea class="form-control" id="tell_us_more" placeholder="Tell us more" name="tell_us_more" value="" rows="3"> </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                      <input type="submit" class="btn btn-raised btn-default pull-right" value="Update" />
                                      <button type="button" class="btn btn-raised btn-danger pull-left deleteEnquiry" href="#" value="" id="enquiryButtonId">Delete</button>
                                    </div>
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
            jQuery("#searchList").attr("action", baseURL + "enquiriesListing/" + value);
            jQuery("#searchList").submit();
        });

        jQuery(document).on("click", ".enquiry-row", function(){
      		var enquiryId = $(this).data("id");
          var name = $(this).data("name");
          var contact_no = $(this).data("contact_no");
          var email_id = $(this).data("email_id");
          var reference_source = $(this).data("reference_source");
          var how_many_people = $(this).data("how_many_people");
          var reason = $(this).data("reason");
          var tell_us_more = $(this).data("tell_us_more");
          var notes = $(this).data("notes");
          $("#enquiryButtonId").val(enquiryId);

          $("#enquiryId").val(enquiryId);
          $("#notes").val(notes);
          $("#full_name").val(name);
          $("#contact_no").val(contact_no);
          $("#email_id").val(email_id);
          $("#reference_source").val(reference_source);
          $("#how_many").val(how_many_people);
          $("#reason").val(reason);
          $("#tell_us_more").val(tell_us_more);
      	});
    });
</script>
