<?php

if (isset($_GET['d'])) {
    $getData = $_GET['d'];
    if (base64_encode(base64_decode($getData, true)) === $getData) { //check for valid base64 data
        $data = base64_decode($getData);
        if (isJson($data)) { //check for valid json data
          $jsonData = json_decode($data);
          $companyId = $jsonData->companyId;
          $vendorId = $jsonData->vendorId;
          $companyName = $jsonData->companyName;
          $vendorName = $jsonData->vendorName;
          $vendorDomainNameReference = $jsonData->vendorDomainNameReference;
        } else {
          redirect('login');
        }
    } else {
      redirect('login');
    }
} else {
  redirect('login');
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}


?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title> <?php echo $vendorName ?> | Coworker Registration</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">

      /* Sticky footer styles
      -------------------------------------------------- */
      html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }
    </style>
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>
  </head>
  <body>
          <div class="container" style="padding-top: 60px;">
            <div class="text-center">
              <img src="assets/images/vendors/<?php echo $vendorId ?>/<?php echo $vendorId ?>-logo-mini.png" alt="<?php echo $vendorName; ?>" align="center" >
            </div>
            <div class="modal-dialog">
            <div class="modal-content">
                    <div class="box box-solid">
                      <?php
                      $this->load->helper('form');
                      $error = $this->session->flashdata('error');
                      if($error)
                      {
                          ?>
                          <div class="alert alert-danger alert-dismissable">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                              <?php echo $error; ?>
                          </div>
                      <?php }
                      $success = $this->session->flashdata('success');
                      if($success)
                      {
                          ?>
                          <div class="alert alert-success alert-dismissable">
                              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                              <?php echo $success; ?>
                          </div>
                      <?php } ?>
                      <div class="row">
                          <div class="col-md-12">
                              <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                          </div>
                      </div>
                      <form action="<?php echo base_url(); ?>addCoworker" method="post" id="addMemberForCompany" role="form">
                              <div class="modal-header">
                                <div class="row">
                                    <div class="col-md-12">
                                      <h2 style="background-color:#f7f7f7; font-size: 20px; text-align: center; padding: 7px 10px; margin-top: 0;">
                                          <?php echo $vendorName ?> | Coworker Fill Up Form
                                      </h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                      <h3><?php echo 'Hello, '.$companyName.' folks'; ?></h3>
                                      <h4 class="modal-title">Please fill your details for our records</h4>
                                    </div>
                                </div>
                              </div>
                              <div class="modal-body">
                                  <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="full_name">Name</label>
                                                    <input type="text" class="form-control required" id="full_name" placeholder="Enter Full Name" name="full_name" value="" maxlength="128">
                                                    <input type="hidden" class="form-control" value="<?php echo $companyId ?>" name="company_id" id="company_id" />
                                                    <input type="hidden" class="form-control" value="<?php echo $vendorId ?>" name="user_id" id="user_id" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email_id">Email</label>
                                                    <input type="text" class="form-control required email" id="email_id" placeholder="Enter email" name="email_id" value="" maxlength="128">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dob">Date of Birth</label>
                                                    <input class="form-control" placeholder="mm/dd/yyyy" name="dob" id="dob" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" type="text">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact_no">Contact Number</label>
                                                    <input type="text" class="form-control required" id="contact_no" placeholder="Enter Contact Number" name="contact_no" value="" maxlength="128">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input type="text" class="form-control required" id="address" placeholder="Enter address" name="address" value="" maxlength="128">
                                                </div>
                                            </div>
                                        </div>

                                  </div><!-- /.-box-body -->
                              </div><!-- modal-body -->
                              <div class="modal-footer">
                                  <input type="submit" class="btn btn-default pull-right" value="Submit" id="submit"/>
                              </div>
                        </form>
                  </div><!-- /.-box -->
              </div> <!-- modal-content -->
            </div> <!-- modal-dialog -->
          </div> <!-- container -->
      <footer class="footer">
            <p class="text-center" style="margin: 20px 20px;"> Made with  &ensp;<i class="fa fa-heart heart"></i>&ensp;  by <a href="http://www.wreely.com">Wreely</a> </p>
      </footer>
    <script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>

    <!-- InputMask -->
    <script src="assets/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        var addMemberForCompany = $("#addMemberForCompany");
        var validator = addMemberForCompany.validate({

          rules:{
            full_name :{ required : true },
            email_id : { required : true, email : true, remote : { url : baseURL + "checkCoworkerEmailExist", type :"post"} },
            dob : { required : true },
            contact_no : { required : true, digits : true },
            address : { required : true },
          },
          messages:{
            full_name :{ required : "This field is required" },
            email_id : { required : "This field is required", email : "Please enter valid email address", remote : "Oop's looks like you already filled it :)" },
            dob : { required : "This field is required" },
            contact_no : { required : "This field is required", digits : "Please enter numbers only" },
            address : { required : "This field is required" },
          },
          submitHandler : function(form) {
            console.log("submit");
            $.ajax({
                url: baseURL + "addCoworker",
                type: "POST",
                data: $("#addMemberForCompany").serialize(),
                success:function(data){
                    if (data.status = true) {
                        alert("Information successfully submitted. Thanks!!!");
                        resetForm();
                    } else {
                        alert("Oop's something went wrong.");
                    }
                }
            });
          }
        });

        function resetForm() {
          $(':input','#addMemberForCompany')
           .not(':button, :submit, :reset, :hidden')
           .val('')
           .removeAttr('checked')
           .removeAttr('selected');
        }
        $('#dob').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
      });
    </script>
  </body>
</html>
