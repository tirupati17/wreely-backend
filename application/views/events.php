<?php
//echo "test";
//print_r($data);
//exit;
//exit;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-ticket"></i>Events</a></li>
                <li class="active">Listings</li>
            </ol>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="daterange-btn" >Date Range</label>
                    <button class="form-control required btn" type="button" id="daterange-btn">
                        <span></span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-9 text-right" style="margin-bottom:10px;margin-top:25px;">
                <a class="btn btn-raised btn-primary" href="#" id="eventAdd-button" data-toggle="modal" data-target="#modal-event"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">

                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <div class="col-md-12">
                            <table id="list-table" class="table table-hover" style="cursor:pointer">
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                    <div class="box-footer clearfix">
                    </div>
                </div><!-- /.box -->
            </div>
            <div class="modal fade" id="modal-event" style="display: none;">      
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Event Details</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" action="" method="post" id="event-form" role="form">
                                <input type="hidden" readonly="" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id; ?>" maxlength="11">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Title</label>
                                                <input type="text" class="form-control modal-input" id="title" placeholder="Enter event name" name="title" value="" maxlength="128">
                                                <input type="hidden" class="form-control modal-input" id="id" name="id" value="" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="contact_person_name">Description</label>
                                                <textarea id="description" name="description" class="form-control" rows="10" ></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Start Time</label>
                                                <input type="text" class="form-control modal-input" id="start_time" placeholder="Start Time" name="start_time" value="" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">End Time</label>
                                                <input type="text" class="form-control modal-input" id="end_time" placeholder="End Time" name="end_time" value="" maxlength="128">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-raised btn-danger pull-left deleteEvent" href="#" value="" id="eventButtonId">Delete</button>
                                            <input type="submit" class="btn btn-raised btn-default pull-right" value="Update" id="eventSubmitButton"/>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                            </form>

                        </div> <!-- /.model-body -->
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </div>
    </section>
</div>
<?php
//echo date("h:i:s");
//exit;
?>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/ckeditor/ckeditor.js" charset="utf-8"></script> -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>

<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        var selectedFromDate = moment().startOf('month');
        var selectedToDate = moment().endOf('month');
        var listTable;

        resetAndLoadData(); //load with default values

        function resetAndLoadData() {
            reloadDatatableList(selectedFromDate, selectedToDate);
        }

        $('#start_time').datepicker({
            autoclose: true,
            format: 'yyyy/mm/dd <?php echo date("h:i:s"); ?>'
        });

        $('#end_time').datepicker({
            autoclose: true,
            format: 'yyyy/mm/dd <?php echo date("h:i:s"); ?>'
        });

        $('#daterange-btn').daterangepicker(
                {
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'Last 6 Months': [moment().subtract(180, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().startOf('month'),
                    endDate: moment().endOf('month')
                },
                function (start, end) {
                    selectedFromDate = start;
                    selectedToDate = end;
                    reloadDatatableList(selectedFromDate, selectedToDate);
                }
        );


         $('#list-table tbody').on('click', 'tr', function () {
             console.log("thiss ========= ",$(this).attr('data-id'));
             //alert("test"+$(this).data("id"));
             $("#eventSubmitButton").val("Update");
             $("#eventButtonId").show();
             $("#event-form").attr("action", "<?php echo base_url(); ?>event/editEvent");

             $("#eventButtonId").val($(this).data("id"));
             var cells = $("#table tr").eq($(this).index()).find("td");
             $(this).find('td').each(function (i) {
                 if ($(this).attr('data-type') == 'in') {
                     $("#" + $(this).attr('data-id')).val($(this).text());
                 } else if ($(this).attr('data-type') == 'wys5html') {
                     var $ta = $("#" + $(this).attr('data-id')).html($(this).text());
                     var w5ref = $ta.data('wysihtml5');
                     w5ref.editor.setValue($(this).html());
                     $("#" + $(this).attr('data-id')).html($(this).text());
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
             });
         });

        jQuery('#eventAdd-button').click(function (e) {
            e.preventDefault();
            $('.modal-input').val('');
            $("#event-form").attr("action", "<?php echo base_url(); ?>event/addEvent");
            $("#eventSubmitButton").val("Add");
            $("#eventButtonId").hide();
        });

        function reloadDatatableList(start, end) {
            var fromDate = '';
            var toDate = '';
            if (start && end) {
                $('#daterange-btn span').html(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
                fromDate = start.format('YYYY-MM-DD');
                toDate = end.format('YYYY-MM-DD');
            }
            $('#list-table').empty();
            listTable = $("#list-table").DataTable(getDatatableListConfig(fromDate, toDate));
            listTable.rows().every(function(rowIdx, tableLoop, rowLoop){
                    //$(this.node().cells[0]).addClass('red');
                    $(this.node().cells[1]).addClass('blue');
            });
            console.log("listTable ======= ", listTable);
                    listTable.on('select', function (e, dt, type, indexes) {
                    console.log("testttt ======= ", type);
                            console.log(type);
                            if (type === 'row') {
                    var idData = listTable.rows(indexes).data().pluck('id');
                            var titleData = listTable.rows(indexes).data().pluck('title');
                            var descriptionData = listTable.rows(indexes).data().pluck('description');
                            var startTimeData = listTable.rows(indexes).data().pluck('start_time');
                            var endTimeData = listTable.rows(indexes).data().pluck('end_time');
                            $("#event-form").attr("action", "<?php echo base_url(); ?>event/editEvent");
                            $("#eventSubmitButton").val("Update");
                            $("#eventButtonId").show();
                            var id = idData[0];
                            var title = titleData[0];
                            var description = atob(descriptionData[0]);
                            var startTime = startTimeData[0];
                            var endTime = endTimeData[0];
                            $("#newsButtonId").val(id);
                            $("#newsId").val(id);
                            $("#title").val(title);
                            $("#description").val(description);
                            $("#start_time").val(startTime);
                            $("#end_time").val(endTime);
                            $('#modal-event').modal('show');
                            $('#modal-event').on('hide.bs.modal', function() {
                    listTable.rows(indexes).deselect();
                    });
                            $('#modal-event').on('shown.bs.modal', function() {

                    });
                    }
                    });
            }

            function getDatatableListConfig(fromDate, toDate) {
            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
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
                'createdRow': function( row, data, dataIndex ) {
                    console.log("data ======= ",data.id);
                    $(row).attr('id', data.id);
                    $(row).attr('class', 'eventMember-row');
                    $(row).attr('data-toggle', 'modal');
                    $(row).attr('data-target', '#modal-event');
                    $(row).attr('data-id', data.id);
  		},
                "columnDefs": [
                    {
                        "targets": 1,
                        'createdCell':  function (td, cellData, rowData, row, col) {
           			$(td).attr('data-id', 'title'); 
                                $(td).attr('data-type', 'in'); 
        		}
                    },
                    {
                        "render": function (data, type, row) {
                            return data;
                        },
                        "targets": 2,
                        'createdCell':  function (td, cellData, rowData, row, col) {
           			$(td).attr('data-id', 'description'); 
                                $(td).attr('data-type', 'wys5html'); 
        		}
                    },
                    {
                        "targets": 3,
                        'createdCell':  function (td, cellData, rowData, row, col) {
           			$(td).attr('data-id', 'description'); 
                                $(td).attr('data-type', 'in'); 
        		}
                    },
                    {
                        "targets": 4,
                        'createdCell':  function (td, cellData, rowData, row, col) {
           			$(td).attr('data-id', 'start_time'); 
                                $(td).attr('data-type', 'in'); 
        		}
                    },
                    {
                        "targets": 5,
                        'createdCell':  function (td, cellData, rowData, row, col) {
           			$(td).attr('data-id', 'end_time'); 
                                $(td).attr('data-type', 'in'); 
        		}
                    },
                    {
                        "targets": 6,
                        'createdCell':  function (td, cellData, rowData, row, col) {
           			$(td).attr('data-id', 'total_rsvp'); 
                        }
                    },
                    {
                        "targets": [0, 3],
                        "visible": false
                    }
                ],
                "order": [[4, "desc"]],
                "scrollX": true,
                "scrollY": true,
                "select": true,
                "processing": true,
                "destroy": true,
                "serverSide": false,
                "bAutoWidth": false,
                "ajax": {
                    "url": baseURL + "eventsDatatableListing",
                    "type": "POST",
                    "data": {
                        "from": fromDate,
                        "to": toDate
                    },
                    cache: false
                },
                "columns": [
                    {"data": "id"},
                    {"data": "title"},
                    {"data": "description"},
                    {"data": "header_image_url"},
                    {"data": "start_time"},
                    {"data": "end_time"},
                    {"data": "total_rsvp"}
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
                        "bSortable": true,
                        "sClass":"test"
                    },
                    {
                        "mData": "title",
                        "title": "Title",
                        "bSortable": true,
                        "sClass":"test"
                    },
                    {
                        "mData": "description",
                        "title": "Description",
                        "bSortable": true
                    },
                    {
                        "mData": "header_image_url",
                        "title": "Header Image",
                        "bSortable": true
                    },
                    {
                        "mData": "start_time",
                        "title": "Start Time",
                        "bSortable": true
                    },
                    {
                        "mData": "end_time",
                        "title": "End Time",
                        "bSortable": true
                    },
                    {
                        "mData": "total_rsvp",
                        "title": "Total RSVP",
                        "bSortable": true
                    }
                ]
            };
            return tableConfig;
        }
    });
</script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
    $('#description').wysihtml5();
</script>