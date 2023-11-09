<?php

if (count(explode('.', $_SERVER['HTTP_HOST']))) {
  $domainName = explode('.', $_SERVER['HTTP_HOST'])[0];
  if ($domainName == "theplayce") {
    $vendorName = 'The Playce';
    $vendorId = '7';
  } else if ($domainName == "workloft") {
    $vendorName = 'Workloft';
    $vendorId = '8';
  } else if ($domainName == "celer") {
    $vendorName = 'Celer';
    $vendorId = '3';
  } else {
    $vendorName = 'Wreely';
    $vendorId = '7';
  }
} else {
  $vendorName = 'Wreely';
  $vendorId = '7';
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Wreely | Admin System Log in</title>
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
  </head>
  <body>
    <div class="container" style="padding-top: 60px;">
      <div class="text-center">
        <img src="assets/images/logo-large.png" alt="Wreely" style="width: 400px;height: auto;margin-left: -0.8cm;" align="center" >
      </div>
      <div class="login-box">
          <div class="box-body">
                <p class="login-box-msg">Sign In</p>
                <?php $this->load->helper('form'); ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
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
                    <form action="<?php echo base_url(); ?>loginMe" method="post">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group has-feedback">
                            <input type="email" class="form-control" placeholder="Email" name="email" required />
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Password" name="password" required />
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group has-feedback">
                          <select class="form-control required" id="userType" name="userType">
                            <option value="">Select User Type</option>
                            <option value="1">Admin</option>
                            <option value="2">Staff</option>
                          </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <!-- <div class="col-md-12">
                          <div class="checkbox icheck">
                            <label>
                              <input type="checkbox"> Remember Me
                            </label>
                          </div>
                        </div> -->
                        <div class="col-md-12">
                          <input type="submit" class="btn btn-default btn-block btn-flat" value="Sign In" />
                        </div><!-- /.col -->
                      </div>
                    </form>
                  <div class="row">
                    <div class="col-md-12" style="margin-top: 10px;">
                      <a href="<?php echo base_url() ?>forgotPassword">Forgot Password</a><br>
                    </div>
                  </div>

                </div>
          </div><!-- /.login-box-body -->
</div> <!-- container -->
<footer class="footer">
      <p class="text-center" style="margin: 20px 20px;"> Made with  &ensp;<i class="fa fa-heart heart"></i>&ensp;  by <a href="http://www.wreely.com"><span><b><font color="#0088d8">W</font><font color="#535353">reely</font></b></span></a> </p>
</footer>
    <script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>
