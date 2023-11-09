<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="<?php echo base_url(); ?>bower_components/components-font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="<?php echo base_url(); ?>bower_components/ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- Material Design Start-->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/materialadminlte/css/bootstrap-material-design.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/materialadminlte/css/ripples.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/materialadminlte/css/MaterialAdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/materialadminlte/css/all-md-skins.min.css"> -->
    <!-- Material Design End-->

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- datatables.net-bs -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

    <!-- datatables.net-buttons-bs -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/datatables.net-buttons-bs/css/buttons.bootstrap.min.css">

    <!-- datatables.net-select-bs -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/datatables.net-select-bs/css/select.bootstrap.min.css">

    <!-- datatables.net-buttons-bs -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/datatables.net-buttons-bs/css/buttons.bootstrap.min.css">

    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>bower_components/bootstrap-daterangepicker/daterangepicker.css">

    <!-- bootstrap Timepicker -->
    <link href="https://cdn.jsdelivr.net/bootstrap.timepicker/0.2.6/css/bootstrap-timepicker.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet"> -->

    <style>

      body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-family: 'Roboto', sans-serif;
        font-weight: 300;
        overflow-x: hidden;
        overflow-y: auto;
        font-size: 14px
      }
    	.error{
    		color:red;
    		font-weight: normal;
    	}
        *, *:before, *:after {
          box-sizing: border-box;
        }
        .plane {
          margin: 20px auto;
          max-width: 300px;
        }

        .cockpit {
          height: 250px;
          position: relative;
          overflow: hidden;
          text-align: center;
          border-bottom: 5px solid #d8d8d8;
        }
        .cockpit:before {
          content: "";
          display: block;
          position: absolute;
          top: 0;
          left: 0;
          height: 500px;
          width: 100%;
          border-radius: 50%;
          border-right: 5px solid #d8d8d8;
          border-left: 5px solid #d8d8d8;
        }
        .cockpit h1 {
          width: 60%;
          margin: 100px auto 35px auto;
        }

        .exit {
          position: relative;
          height: 50px;
        }
        .exit:before, .exit:after {
          content: "EXIT";
          font-size: 14px;
          line-height: 18px;
          padding: 0px 2px;
          font-family: "Arial Narrow", Arial, sans-serif;
          display: block;
          position: absolute;
          background: green;
          color: white;
          top: 50%;
          transform: translate(0, -50%);
        }
        .exit:before {
          left: 0;
        }
        .exit:after {
          right: 0;
        }

        .fuselage {
          border-right: 5px solid #d8d8d8;
          border-left: 5px solid #d8d8d8;
        }

        ol {
          list-style: none;
          padding: 0;
          margin: 0;
        }

        .seats {
          display: flex;
          flex-direction: row;
          flex-wrap: nowrap;
          justify-content: flex-start;
        }

        .seat {
          display: flex;
          flex: 0 0 14.28571428571429%;
          padding: 5px;
          position: relative;
        }
        .seat:nth-child(3) {
          margin-right: 14.28571428571429%;
        }
        .seat input[type=checkbox] {
          position: absolute;
          opacity: 0;
        }
        .seat input[type=checkbox]:checked + label {
          background: #bada55;
          -webkit-animation-name: rubberBand;
          animation-name: rubberBand;
          animation-duration: 300ms;
          animation-fill-mode: both;
        }
        .seat input[type=checkbox]:disabled + label {
          background: #dddddd;
          text-indent: -9999px;
          overflow: hidden;
        }
        .seat input[type=checkbox]:disabled + label:after {
          content: "X";
          text-indent: 0;
          position: absolute;
          top: 4px;
          left: 50%;
          transform: translate(-50%, 0%);
        }
        .seat input[type=checkbox]:disabled + label:hover {
          box-shadow: none;
          cursor: not-allowed;
        }
        .seat label {
          display: block;
          position: relative;
          width: 100%;
          text-align: center;
          font-size: 14px;
          font-weight: bold;
          line-height: 1.5rem;
          padding: 4px 0;
          background: #F42536;
          border-radius: 5px;
          animation-duration: 300ms;
          animation-fill-mode: both;
        }
        .seat label:before {
          content: "";
          position: absolute;
          width: 75%;
          height: 75%;
          top: 1px;
          left: 50%;
          transform: translate(-50%, 0%);
          background: rgba(255, 255, 255, 0.4);
          border-radius: 3px;
        }
        .seat label:hover {
          cursor: pointer;
          box-shadow: 0 0 0px 1px #5C6AFF;
        }

        @-webkit-keyframes rubberBand {
          0% {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
          }
          30% {
            -webkit-transform: scale3d(1.25, 0.75, 1);
            transform: scale3d(1.25, 0.75, 1);
          }
          40% {
            -webkit-transform: scale3d(0.75, 1.25, 1);
            transform: scale3d(0.75, 1.25, 1);
          }
          50% {
            -webkit-transform: scale3d(1.15, 0.85, 1);
            transform: scale3d(1.15, 0.85, 1);
          }
          65% {
            -webkit-transform: scale3d(0.95, 1.05, 1);
            transform: scale3d(0.95, 1.05, 1);
          }
          75% {
            -webkit-transform: scale3d(1.05, 0.95, 1);
            transform: scale3d(1.05, 0.95, 1);
          }
          100% {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
          }
        }
        @keyframes rubberBand {
          0% {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
          }
          30% {
            -webkit-transform: scale3d(1.25, 0.75, 1);
            transform: scale3d(1.25, 0.75, 1);
          }
          40% {
            -webkit-transform: scale3d(0.75, 1.25, 1);
            transform: scale3d(0.75, 1.25, 1);
          }
          50% {
            -webkit-transform: scale3d(1.15, 0.85, 1);
            transform: scale3d(1.15, 0.85, 1);
          }
          65% {
            -webkit-transform: scale3d(0.95, 1.05, 1);
            transform: scale3d(0.95, 1.05, 1);
          }
          75% {
            -webkit-transform: scale3d(1.05, 0.95, 1);
            transform: scale3d(1.05, 0.95, 1);
          }
          100% {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
          }
        }
        .rubberBand {
          -webkit-animation-name: rubberBand;
          animation-name: rubberBand;
        }

        table.dataTable.table-striped.DTFC_Cloned tbody tr:nth-of-type(odd) {
            background-color: #F3F3F3;
        }
        table.dataTable.table-striped.DTFC_Cloned tbody tr:nth-of-type(even) {
            background-color: white;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"/>

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.4.min.js"></script>

    <!-- jQuery UI -->
    <script src="<?php echo base_url(); ?>bower_components/jquery-ui/jquery-ui.min.js"></script>

    <!-- datatables.net -->
    <script src="<?php echo base_url(); ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

    <!-- datatables.net-buttons -->
    <script src="<?php echo base_url(); ?>bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>bower_components/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>bower_components/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>bower_components/jszip/dist/jszip.min.js"></script>

    <!-- InputMask -->
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- datatables.net-select-bs -->
    <script src="<?php echo base_url(); ?>bower_components/datatables.net-select-bs/js/select.bootstrap.min.js"></script>

    <!-- datatables.net-select -->
    <script src="<?php echo base_url(); ?>bower_components/datatables.net-select/js/dataTables.select.min.js"></script>

    <!-- datatables.net-bs -->
    <script src="<?php echo base_url(); ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

    <!-- datatables.net-buttons-bs -->
    <script src="<?php echo base_url(); ?>bower_components/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

    <!-- moment -->
    <script type="text/javascript" src="<?php echo base_url(); ?>bower_components/moment/moment.js"></script>

    <!-- daterange picker -->
    <script type="text/javascript" src="<?php echo base_url(); ?>bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

    <!-- bootstrap datetimepicker -->
    <link rel="stylesheet" href="bower_components/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">

    <!-- bootstrap Timepicker -->
    <script type="text/javascript" src="<?php echo base_url(); ?>bower_components/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

    <!-- cloudinary -->
    <script src="<?php echo base_url(); ?>bower_components/blueimp-file-upload/js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>bower_components/blueimp-file-upload/js/jquery.iframe-transport.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>bower_components/blueimp-file-upload/js/jquery.fileupload.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>bower_components/cloudinary-jquery-file-upload/cloudinary-jquery-file-upload.min.js"></script>
    <script src="//widget.cloudinary.com/global/all.js" type="text/javascript"></script>  

    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-black sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url(); ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><font color="#0088d8">W</font></b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><font color="#0088d8">W</font>reely</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo $name; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo $name; ?>
                      <small><?php echo $role_text; ?></small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <a href="<?php echo base_url(); ?>loadChangePass" class="btn btn-raised btn-default btn-block"><i class="fa fa-key"></i> Change Password</a>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                         <a href="<?php echo base_url(); ?>logout" class="btn btn-raised btn-danger btn-block"><i class="fa fa-sign-out"></i> Sign Out</a>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <?php
            if($role == ROLE_ADMIN || $role == ROLE_SUPER_ADMIN)
            {
            ?>
            <li class="treeview">
              <a href="<?php echo base_url(); ?>dashboard">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span></i>
              </a>
            </li>
            <li class="treeview">
              <a href="<?php echo base_url(); ?>companyListing">
                <i class="fa fa-plane"></i>
                <span>Companies</span>
              </a>
            </li>
            <li class="treeview">
              <a href="<?php echo base_url(); ?>memberListing" >
                <i class="fa fa-users"></i>
                <span>Members</span>
              </a>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-id-card"></i>
                <span>Reception Management </span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>enquiriesListing">Enquiries</a></li>
                <li><a href="<?php echo base_url(); ?>flexiAttendanceListing">Flexi Attendance</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-id-card"></i>
                <span>Meeting Rooms</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>meetingRoomListing">Rooms</a></li>
                <li><a href="<?php echo base_url(); ?>meetingRoomBookingsListing">Booking List</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-id-card"></i>
                <span>Events</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo base_url(); ?>eventsListing">Events</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-ticket"></i>
                <span>Space Management </span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <!-- <li><a href="<?php echo base_url(); ?>interiorLayout">Space (Arial view)</a></li> -->
                <li><a href="<?php echo base_url(); ?>spaceFlexible">Flexible</a></li>
                <li><a href="<?php echo base_url(); ?>spaceDedicated">Dedicated</a></li>
                <!-- <li><a href="#">Space
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                  <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>spaceListing">Both</a></li>
                    <li><a href="<?php echo base_url(); ?>spaceFlexible">Flexible</a></li>
                    <li><a href="<?php echo base_url(); ?>spaceDedicated">Dedicated</a></li>
                  </ul>
                </li> -->
                <li><a href="<?php echo base_url(); ?>membershipTypeAndSeatListing">Membership & Seats</a></li>
              </ul>
            </li>
            <?php
            }
            ?>
            <?php
            if($role == ROLE_SUPER_ADMIN)
            {
            ?>
            <li class="treeview">
              <a href="<?php echo base_url(); ?>eventCalendar" >
                <i class="fa fa-calendar"></i>
                <span>Event Calendar</span>
              </a>
            </li>
            <li class="treeview">
              <a href="<?php echo base_url(); ?>userListing">
                <i class="fa fa-user-plus"></i>
                <span>Users</span>
              </a>
            </li>
            <?php
            }
            if($role == ROLE_ADMIN || $role == ROLE_SUPER_ADMIN)
            {
            ?>
            <li class="treeview">
            <a href="<?php echo base_url(); ?>settings">
                <i class="fa fa-files-o"></i>
                <span>Setting</span>
              </a>
            </li>
            <?php
            }
            ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
