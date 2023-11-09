<?php

$enquiryId = '';
$full_name = '';
$email_id = '';
$occupation = '';
$contact_no = '';
$reference_source = '';
$reason = '';
$how_many_people = '';
$tell_us_more = '';
$notes = '';

if(!empty($enquiryInfo))
{
    foreach ($enquiryInfo as $uf)
    {
        $enquiryId = $uf->id;
        $full_name = $uf->full_name;
        $email_id = $uf->email_id;
        $occupation = $uf->occupation;
        $contact_no = $uf->contact_no;
        $reference_source = $uf->reference_source;
        $reason = $uf->reason;
        $how_many_people = $uf->how_many_people;
        $tell_us_more = $uf->tell_us_more;
        $notes = $uf->notes;
    }
}


?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Enquiry Management
        <small>Add / Edit Enquiry</small>
      </h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->



                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Enquiry Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <form role="form" action="<?php echo base_url() ?>editEnquiry" method="post" id="editEnquiry" role="form">
                        <div class="box-body">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <label for="notes">Notes (By Admin)</label>
                                      <textarea class="form-control" id="notes" placeholder="Enter notes" name="notes" rows="3"> <?php echo $notes;?></textarea>
                                  </div>
                              </div>
                          </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" placeholder="Full Name" name="full_name" value="<?php echo $full_name; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $enquiryId; ?>" name="enquiryId" id="enquiryId" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control" id="email_id" placeholder="Enter email" name="email_id" value="<?php echo $email_id; ?>" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="occupation">Occupation</label>
                                        <input type="text" class="form-control" id="occupation" placeholder="Occupation" name="occupation" value="<?php echo $occupation; ?>" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_no">Contact No</label>
                                        <input type="text" class="form-control" id="contact_no" placeholder="Contact No" name="contact_no" value="<?php echo $contact_no; ?>" maxlength="15">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reference_source">Reference Source</label>
                                        <input type="text" class="form-control" id="reference_source" placeholder="Reference Source" name="reference_source" value="<?php echo $reference_source; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reason">Reason</label>
                                        <input type="text" class="form-control" id="reason" placeholder="Reason" name="reason" value="<?php echo $reason; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="how_many">How Many People</label>
                                        <input type="text" class="form-control" id="how_many" placeholder="How Many" name="how_many" value="<?php echo $how_many_people; ?>" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="how_many">More About User</label>
                                        <textarea class="form-control" id="tell_us_more" placeholder="Tell us more" name="tell_us_more" value="" rows="2"> </textarea>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <input type="submit" class="btn btn-raised btn-primary" value="Submit" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php } ?>
                <?php
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>
