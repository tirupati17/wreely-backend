
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-id-card"></i> Reception Management</a></li>
          <li class="active">Flexi Attendance</li>
        </ol>
      </h1>
    </section>
    <section class="content">
      <div class="row">
          <div class="col-xs-12 text-right">
                <a class="btn btn-raised btn-primary" href="#" id="flexiEntryAdd-button" data-toggle="modal" data-target="#modal-flexiEntry"><i class="fa fa-plus"></i> Add Manual Flexi</a>
          </div>
      </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <div class="row">
                    <div class="col-md-6">
                      <h3 class="box-title">Flexi Attendance List</h3>
                    </div>
                    <div class="col-md-6">
                      <div class="box-tools">
                          <form action="<?php echo base_url() ?>flexiAttendanceListing" method="POST" id="searchList">
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
                  <table class="table table-hover">
                    <tr>
                      <th>Company Name</th>
                      <th>Member Name</th>
                      <th>Renewal Date</th>
                      <th>Expiry Date</th>
                      <th>Last Attendance</th>
                      <th>Attendance count</th>
                      <th>Status</th>
                    </tr>
                    <?php
                    if(!empty($flexiAttendanceRecords))
                    {
                        foreach($flexiAttendanceRecords as $record)
                        {
                    ?>
                    <tr data-member_id="<?php echo $record->member_id; ?>" style="cursor: pointer;" class="flexiMemberDetails-row" data-toggle="modal" data-target="#modal-flexiMemberDetails" data-start_date="<?php echo $record->start_date; ?>" data-expiry_date="<?php echo $record->expiry_date; ?>">
                      <td><?php echo $record->name ?></td>
                      <td><?php echo $record->full_name ?></td>
                      <td><?php echo date('d M Y', strtotime($record->start_date)) ?></td>
                      <td><?php echo date('d M Y', strtotime($record->expiry_date)) ?></td>
                      <td><?php echo date('d M Y H:m A', strtotime($record->attendance_date)) ?></td>
                      <td><?php echo $record->attendance_count.'/'.$record->number_of_day ?></td>
                      <td><?php
                      $expiry_date = date('Y-m-d', strtotime($record->expiry_date));
                      $currentDate = date('Y-m-d');
                      if ($currentDate < $expiry_date) {
                        echo '<span class="label label-success">Active</span>';
                      } else {
                        echo '<span class="label label-warning">Expired</span>';
                      }
                       ?></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>

                </div><!-- /.box-body -->
                <div class="modal fade" id="modal-flexiMemberDetails" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Flexi Details</h4>
                                    <div class="">
                                      <div class="">
                                          <div class="input-group">
                                            <button type="button" class="btn btn-raised btn-default pull-right" id="daterange-btn">
                                              <span>December 1, 2017 - December 31, 2017</span>
                                              <i class="fa fa-caret-down"></i>
                                            </button>
                                          </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                  <div class="box-body table-responsive no-padding">
                                    <table id="flexiMemberDetails-table" class="table table-striped table-bordered">
                                      <thead>
                                        <tr>
                                          <th>Company</th>
                                          <th>Member</th>
                                          <th>Attendance</th>
                                          <th>Membership</th>
                                          <th>Signature</th>
                                        </tr>
                                      </thead>
                                      <tfoot>
                                        <tr>
                                          <th>Company</th>
                                          <th>Member</th>
                                          <th>Attendance</th>
                                          <th>Membership</th>
                                          <th>Signature</th>
                                        </tr>
                                      </tfoot>
                                    </table>
                                  </div><!-- /.box-body -->
                                </div><!-- /.modal-body -->
                                <div class="modal-footer">
                                </div>
                          </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <div class="modal fade" id="modal-flexiEntry" style="display: none;">
                    <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Manual Flexi Attendance</h4>
                          </div>
                          <div class="modal-body">
                            <form role="form" action="" method="post" id="flexiEntry-form" role="form">
                              <div class="box-body">
                                    <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label for="company_id">Company</label>
                                              <select class="form-control required" id="company_id" name="company_id">
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
                                              <label for="member_id">Members</label>
                                              <select class="form-control required" id="member_id" name="member_id">
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
                                                  <input type="hidden" value="" name="signature_base_64" id="signature_base_64" />
                                                  <label for="membership_type_id">Membership Type</label>
                                                  <select class="form-control required" id="membership_type_id" name="membership_type_id">
                                                      <option value="0">Select Membership Type</option>
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
                                              <label for="attendance_date">Attendance Date</label>
                                              <div class="input-group date" style="margin-top:0px;">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                  </div>
                                                <input class="form-control pull-right" id="attendance_date" name="attendance_date" type="text">
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <input type="submit" class="btn btn-raised btn-default pull-right" value="Add" id="flexiEntrySubmitButton"/>
                                        <!-- <button type="button" class="btn btn-raised btn-danger pull-left deleteFlexiEntry" href="#" value="" id="flexiEntryButtonId">Delete</button> -->
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
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<!-- datatable editor start -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.4/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css">

<script src="https://cdn.datatables.net/select/1.2.4/js/dataTables.select.min.js"></script>
<script src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script> -->
<!-- datatable editor end -->

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "flexiAttendanceListing/" + value);
            jQuery("#searchList").submit();
        });

        $('#attendance_date').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });

        var memberId = 0;
        var fromDate = moment();
        var toDate = moment();

        jQuery(document).on("click", ".flexiMemberDetails-row", function(){
          memberId = $(this).data("member_id");
          fromDate = moment($(this).data("start_date"), "YYYY-MM-DD");
          toDate = moment($(this).data("expiry_date"), "YYYY-MM-DD");

          $("#daterange-btn").data('daterangepicker').setStartDate(fromDate);
          $("#daterange-btn").data('daterangepicker').setEndDate(toDate);
          reloadFlexiMemberDetails(memberId, fromDate, toDate);
        });

        jQuery('#flexiEntryAdd-button').click(function (e) {
            e.preventDefault();
            $('.modal-input').val(0);
            $("#flexiEntry-form").attr("action", "<?php echo base_url(); ?>flexiAttendance/addFlexiAttendance");
            $("#flexiEntrySubmitButton").val("Add");
            $("#flexiEntryButtonId").hide();

            $('.cmbData').each(function (i, obj) {
                var cmbArr = jQuery.parseJSON($(this).html());
                var option = '';
                for (var i = 0; i < cmbArr.length; i++) {
                    option += '<option value="' + cmbArr[i].id + '">' + cmbArr[i].name + '</option>';
                }
                $("#cmb_" + $(this).attr('data-rel')).append(option);
            })
        });

        $('#daterange-btn').daterangepicker(
           {
             ranges   : {
               'Today'       : [moment(), moment()],
               'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month'  : [moment().startOf('month'), moment().endOf('month')],
               'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
             },
             startDate: moment().subtract(1, 'month').startOf('month'),
             endDate  : moment().subtract(1, 'month').endOf('month')
           },
           function (start, end) {
             console.log(start);
             fromDate = start;
             toDate = end;
             reloadFlexiMemberDetails(memberId, start, end);
           }
         );

         function reloadFlexiMemberDetails(memberId, start, end) {
           $('#daterange-btn span').html(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
           $('#flexiMemberDetails-table').empty();

           //  ******* datatable editor start *******
           // var editor; // use a global for the submit and return data rendering in the examples
           // $(document).ready(function() {
           //      editor = new $.fn.dataTable.Editor({
           //          ajax: "",
           //          table: "#example",
           //          fields: [ {
           //                  label: "Company",
           //                  name: "name"
           //              }, {
           //                  label: "Member",
           //                  name: "full_name"
           //              }, {
           //                  label: "Attendance",
           //                  name: "attendance_date"
           //              }, {
           //                  label: "Membership",
           //                  name: "membership_name"
           //              }, {
           //                  label: "Signature",
           //                  name: "signature_base_64"
           //              }
           //          ]
           //      });
           //  });
           //  // Activate an inline edit on click of a table cell
           //  $('#flexiMemberDetails-table').on( 'click', 'tbody td:not(:first-child)', function (e) {
           //      editor.inline( this );
           //  } );
           //  ******** datatable editor end ********

            $("#flexiMemberDetails-table").DataTable(getFlexiMemberDetailsConfig(memberId, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD')));
         }

        function sendMail() {
             $.ajax({
                 url: baseURL + "sendAttendanceReport",
                 type: "POST",
                 data: "memberId=" + memberId + "&startDate=" + fromDate.format('YYYY-MM-DD') + "&endDate=" + toDate.format('YYYY-MM-DD'),
                 success:function(data){
                     if (data.status = true) {
                         alert("Email sent.");
                     } else {
                         alert("Configure mandrill template for this!");
                     }
                 }
             });
        }

        function getFlexiMemberDetailsConfig(memberId, fromDate, toDate) {
          var buttonCommon = {
               exportOptions: {
                   format: {
                       body: function ( data, row, column, node ) {
                          if (column == 4) {
                            return "Signature";
                          } else {
                            return data;
                          }
                       }
                   }
               }
           };
        	var tableConfig = {
        			"columnDefs": [
        		            {
        		                "render": function ( data, type, row ) {
                              var mydate = new Date(data);
                              return mydate.toDateString();
        		                },
        		                "targets": 2
        		            },
              					{
              						"render": function ( data, type, row ) {
              		                    return '<img style="width:60px;height:40px;" src="data:image/png;base64,' + data + '"alt="Manual Entry" />';
              		                },
              		  		        "targets": 4
              					}
        		    ],
        			"scrollX": false,
        			"processing": true,
              "destroy": true,
        			"serverSide": false,
        			"bAutoWidth": false,
        			"ajax": {
        						"url": baseURL + "flexiAttendanceOfMember",
        						"type": "POST",
        						"data": {
        					           	   	"memberId": memberId,
                                  "from" : fromDate,
                                  "to" : toDate
        					        	},
        				    	cache: false
        			},
              "columns": [
                  { "data": "name" },
                  { "data": "full_name" },
                  { "data": "attendance_date" },
                  { "data": "membership_name" },
                  { "data": "signature_base_64" }
              ],
              dom: 'Bfrtip',
              buttons: [
                        $.extend( true, {}, buttonCommon, {
                            extend: 'excelHtml5',
                            className: 'btn btn-raised btn-default',
                        } ),
                        $.extend( true, {}, buttonCommon, {
                            extend: 'pdfHtml5',
                            className: 'btn btn-raised btn-default',
                        } ),
                        $.extend( true, {}, buttonCommon, {
                            extend: 'print',
                            className: 'btn btn-raised btn-default',
                        }),
                        {
                            text: 'Email',
                            className: 'btn btn-raised btn-default',
                            columns: ':not(:last-child)',
                            action: function ( e, dt, node, config ) {
                                sendMail();
                            }
                        }
                    ],

              "aoColumns": [
                 {
                   "mData": "name",
                   "title": "Company",
                   "bSortable": true
                 },
                 {
                   "mData": "full_name",
                   "title": "Member",
                   "bSortable": true
                 },
                 {
                   "mData": "attendance_date",
                   "title": "Attendance",
                   "bSortable": true
                 },
                 {
                   "mData": "membership_name",
                   "title": "Membership",
                   "bSortable": true
                 },
                 {
                   "mData": "signature_base_64",
                   "title": "Signature",
                   "bSortable": true
                 }
               ]
          };
        	return tableConfig;
        }

    });
</script>
