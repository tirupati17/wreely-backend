<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-ticket"></i>Meeting Room</a></li>
          <li class="active">Bookings</li>
        </ol>
      </h1>
    </section>

    <section class="content">
        <div class="row">
                <div class="col-md-4">
                        <div class="form-group">
                            <label for="filter-roomId">Rooms</label>
                            <select class="form-control required" id="filter-roomId" name="filter-roomId">
                                <option value="0">All</option>
                                <?php
                                if(!empty($rooms))
                                {
                                    foreach ($rooms as $dp)
                                    {
                                        ?>
                                        <option value="<?php echo $dp->id ?>"><?php echo $dp->name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                            <label for="daterange-btn" >Date Range</label>
                            <button class="form-control required btn" type="button" id="daterange-btn">
                                <span>January 1, 2018 - March 31, 2018</span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                </div>
                <!-- <div class="col-md-4 text-right" style="margin-bottom:10px;margin-top:25px;">
                    <a class="btn btn-raised btn-primary" href="#" id="add-button" data-toggle="modal" data-target="#modal-add"><i class="fa fa-plus"></i> Add New</a>
                </div> -->
        </div>
        <div class="row">
            <div class="col-md-12">
              <div class="box">
                  <div class="box-header">

                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <div class="col-md-12">
                        <table id="list-table" class="table table-hover" style="cursor:pointer">
                            <thead>
                            <tr>
                                <th>Room Name</th>
                                <th>Member Name</th>
                                <th>Member Email</th>
                                <th>Total Slots</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Room Name</th>
                                <th>Member Name</th>
                                <th>Member Email</th>
                                <th>Total Slots</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                  </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/ckeditor/ckeditor.js" charset="utf-8"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>

<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        var selectedFromDate = moment().startOf('month');
        var selectedToDate = moment().endOf('month');
        var selectedRoomId = 0;
        var listTable;

        resetAndLoadData(); //load with default values

        function resetAndLoadData() {
            selectedRoomId = 0;
            reloadDatatableList(selectedRoomId, selectedFromDate, selectedToDate);
        }

        $("select#filter-roomId").change(function() {
            if ($(this).val() == "0") {
                $(this).val("0");
                resetAndLoadData();
                return;
            }
            selectedRoomId = $(this).val();
            reloadDatatableList(selectedRoomId, selectedFromDate, selectedToDate);
        })

        $('#daterange-btn').daterangepicker(
           {
             ranges   : {
               'Today'       : [moment(), moment()],
               'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'Last 6 Months': [moment().subtract(180, 'days'), moment()],
               'This Month'  : [moment().startOf('month'), moment().endOf('month')],
               'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
             },
             startDate: moment().startOf('month'),
             endDate  : moment().endOf('month')
           },
           function (start, end) {
             selectedFromDate = start;
             selectedToDate = end;
             reloadDatatableList(selectedRoomId, selectedFromDate, selectedToDate);
           }
         );
         
        function reloadDatatableList(selectedRoomId, start, end) {
            var fromDate = '';
            var toDate = '';
            if (start && end) {
                    $('#daterange-btn span').html(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
                    fromDate = start.format('YYYY-MM-DD');
                    toDate = end.format('YYYY-MM-DD');
            }
            $('#list-table').empty();
            listTable = $("#list-table").DataTable(getDatatableListConfig(selectedRoomId, fromDate, toDate));
            listTable.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {

                }
            });
         }

        function getDatatableListConfig(selectedRoomId, fromDate, toDate) {
                var buttonCommon = {
                    exportOptions: {
                        format: {
                            body: function ( data, row, column, node ) {
                                if (column == 0) {
                                    return '';
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
                                        return '<a href="mailTo:' + data +'?subject=Meeting room booking slots&body=Hi, You have used all of your meeting room slots for the current month.">' + data + '</a>';
                                    },
                                    "targets": 3
                                },
                                {
                                    "targets": [0],
                                    "visible": false
                                }
                        ],
                        "order": [[ 4, "desc" ]],
                        "scrollX": true,
                        "scrollY": true,
                        "select": true,
                        "processing": true,
                        "destroy": true,
                        "serverSide": false,
                        "bAutoWidth": false,
                        "ajax": {
                                    "url": baseURL + "meetingRoomBookingsDatatableListing",
                                    "type": "POST",
                                    "data": {
                                                "roomId" : selectedRoomId,
                                                "from" : fromDate,
                                                "to" : toDate
                                            },
                                    cache: false
                        },
                "columns": [
                    { "data": "id" },
                    { "data": "room_name" },
                    { "data": "member_name" },
                    { "data": "member_email" },
                    { "data": "total_slots" }
                ],
                dom: 'Bfrtip',
                buttons: [
                            // $.extend( true, {}, buttonCommon, {
                            //     extend: 'excelHtml5',
                            //     className: 'btn btn-raised btn-default',
                            // } ),
                            // $.extend( true, {}, buttonCommon, {
                            //     extend: 'pdfHtml5',
                            //     className: 'btn btn-raised btn-default',
                            // } ),
                            // $.extend( true, {}, buttonCommon, {
                            //     extend: 'print',
                            //     className: 'btn btn-raised btn-default',
                            // })
                        ],
                "aoColumns": [
                    {
                    "mData": "id",
                    "title": "",
                    "bSortable": true
                    },
                    {
                    "mData": "room_name",
                    "title": "Room Name",
                    "bSortable": true
                    },
                    {
                    "mData": "member_name",
                    "title": "Member Name",
                    "bSortable": true
                    },
                    {
                    "mData": "member_email",
                    "title": "Member Email",
                    "bSortable": true
                    },
                    {
                    "mData": "total_slots",
                    "title": "Total Bookings",
                    "bSortable": true
                    }
                ]
            };
        	return tableConfig;
        }
    });
</script>
