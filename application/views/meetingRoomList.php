<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-ticket"></i> Meeting Room</a></li>
          <li class="active">Listing</li>
        </ol>
      </h1>
    </section>

    <section class="content">
        <div class="row">
                <div class="col-xs-12 text-right" style="margin-bottom:10px;margin-top:25px;">
                    <a class="btn btn-raised btn-primary" href="#" id="meetingRoomAdd-button" data-toggle="modal" data-target="#modal-meetingRoom"><i class="fa fa-plus"></i> Add New</a>
                    <a class="btn btn-raised btn-danger" href="#" data-toggle="modal" data-target="#modal-delete"><i class="fa fa-delete"></i> Delete All</a>
                </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <div class="box">
                  <div class="box-header">

                  </div><!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <div class="col-md-12">
                        <table id="meetingRoom-table" class="table table-hover" style="cursor:pointer;width: 100%;table-layout: fixed;word-wrap:break-word;">
                        </table>
                    </div>
                  </div><!-- /.box-body -->
                <div class="box-footer clearfix">

                </div>
              </div><!-- /.box -->
            </div>
            <div class="modal fade" id="modal-meetingRoom" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Meeting Room Details</h4>
                      </div>
                      <div class="modal-body">
                        <form role="form" action="" method="post" id="meetingRoom-form" role="form">
                        <input type="hidden" value="" name="meetingRoomId" id="meetingRoomId" />
                        <input type="hidden" readonly="" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id; ?>" maxlength="11">
                          <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Room Title</label>
                                        <input type="text" class="form-control required" id="name" name="name" maxlength="200" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <label for="description">Room Description</label>
                                      <textarea class="form-control" id="description" placeholder="Enter article" name="description"></textarea>
                                  </div>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Availability Start Time</label>
                                        <input type="text" class="form-control modal-input" id="start_time" placeholder="Start Time" name="start_time" value="" maxlength="128">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Availability End Time</label>
                                        <input type="text" class="form-control modal-input" id="end_time" placeholder="End Time" name="end_time" value="" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div>
                                            <label>Room Image</label><br/>
                                            <input id="fileLoader" style="color:transparent;" class="cloudinary-fileupload" type="file" name="file" data-form-data="{&quot;upload_preset&quot;:&quot;jbojja3k&quot;,&quot;api_key&quot;:&quot;171271398145725&quot;}" data-url="https://api.cloudinary.com/v1_1/celerstudio/auto/upload">
                                            
                                            <a style="margin-top:10px;" target="_blank" name="fileLocationUrl" id="fileLocationUrl">No file selected<a/> 
                                        </div>
                                        <div class="fileUpload-message"></div>
                                        <div style="margin-top:20px;" class="progress">
                                            <div class="progress-bar" id="file-progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                                        </div>
                                        <div class="file-thumbnails"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-raised btn-danger pull-right deleteMeetingRoom" href="#" value="" id="meetingRoomButtonId">Delete</button>
                                    <input type="submit" class="btn btn-raised btn-success pull-left" value="Update" id="meetingRoomSubmitButton"/>
                                </div>
                            </div>
                          </div><!-- /.box-body -->
                          <div class="box-footer clearfix">

                          </div>
                        </form>
                      </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <div class="modal fade in" id="modal-delete" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Please confirm.</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure want to delete all meeting room?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
                            <button type="button" class="btn btn-danger" id="deleteAllMeetingRoom-button" >Yes</button>
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
    var cloud_name = 'celerstudio';
    var preset_name = 'jbojja3k';
    var api_key = '171271398145725';
    $.cloudinary.config({ cloud_name: cloud_name, api_key: api_key});

    jQuery(document).ready(function(){

        $('#start_time').timepicker();
        $('#end_time').timepicker();

        function resetFileUpload() {
            jQuery('.file-thumbnails').html('');
            $('#file-progress-bar').css('width', '0%');
            $('#cover-progress-bar').css('width', '0%');
        }

        $('.cloudinary-fileupload').unsigned_cloudinary_upload(preset_name, {
            cloud_name: cloud_name
        }, {
            multiple: false
        }).bind('cloudinarydone', function(e, data) {
            console.log(data);
            console.log(e);
            $('#fileLocationUrl').attr("href", data.result.secure_url);
            $("#fileLocationUrl").text('View File');
            $("#fileLocationUrl").css('color', 'green');
            $('.file-thumbnails').append($.cloudinary.image(data.result.public_id, {
            format: 'jpg',
            width: 100,
            height: 100,
            crop: 'thumb',
            gravity: 'face',
            effect: 'sharpen:300'
            }))
        }).bind('cloudinaryprogress', function(e, data) {
            console.log(data);
            console.log(e);

            var percent = Math.round((data.loaded * 100.0) / data.total);
            $('#file-progress-bar').css('width', percent + '%');
            $('#file-progress-bar .text').text(percent + '%');
        });
        
        CKEDITOR.config.toolbar = [
            ['Format','Font','FontSize'],
            ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
            ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['Smiley','TextColor','BGColor','Source']
        ];
        CKEDITOR.replace('description');
        var roomDescriptionTextEditor = CKEDITOR.instances.description;
        var deleteAllUrl = baseURL + "deleteAllMeetingRoom"; //Improve this url call

        var meetingRoomTable;

        resetAndLoadMeetingRoom(); //load with default values

        function resetAndLoadMeetingRoom() {
            reloadMeetingRoomList();
        }

        function reloadMeetingRoomList() {
            
            $('#meetingRoom-table').empty();
            meetingRoomTable = $("#meetingRoom-table").DataTable(getDatatableListConfig());
            meetingRoomTable.on( 'select', function ( e, dt, type, indexes ) {
                console.log("Hello");
                if ( type === 'row' ) {
                    var meetingRoomIdData = meetingRoomTable.rows( indexes ).data().pluck('id');     
                    var roomTitleData = meetingRoomTable.rows( indexes ).data().pluck('room_name');        
                    var roomDescriptionData = meetingRoomTable.rows( indexes ).data().pluck('description');        
                    var startTimeData = meetingRoomTable.rows( indexes ).data().pluck('start_time');        
                    var endTimeData = meetingRoomTable.rows( indexes ).data().pluck('end_time');        
                    var fileLocationUrlData = meetingRoomTable.rows( indexes ).data().pluck('header_image_url');        

                    $("#meetingRoom-form").attr("action", "<?php echo base_url(); ?>editMeetingRoom");
                    $("#meetingRoomSubmitButton").val("Update");
                    $("#meetingRoomButtonId").show();

                    var meetingRoomId = meetingRoomIdData[0];
                    var roomTitle = roomTitleData[0];
                    var roomDescription = roomDescriptionData[0];//atob(roomDescriptionData[0]);
                    var fileLocationUrl = fileLocationUrlData[0];
                    var startTime = startTimeData[0];
                    var endTime = endTimeData[0];

                    console.log(fileLocationUrl);

                    $("#meetingRoomButtonId").val(meetingRoomId);
                    $("#meetingRoomId").val(meetingRoomId);
                    $("#name").val(roomTitle);
                    $("#start_time").val(startTime);
                    $("#end_time").val(endTime);
                    $("#fileLocationUrl").attr('href', fileLocationUrl);

                    if (fileLocationUrl.length == 0 || fileLocationUrl == "0") {
                        $("#fileLocationUrl").text('No attachment found');
                        $("#fileLocationUrl").css('color', 'red');
                    } else {
                        $("#fileLocationUrl").text('View File');
                        $("#fileLocationUrl").css('color', 'green');
                    }

                    CKEDITOR.instances.description.setData(roomDescription);
                    CKEDITOR.instances.description.updateElement();
                    $('#modal-meetingRoom').modal('show');
                    $('#modal-meetingRoom').on('hide.bs.modal', function() { 
                        meetingRoomTable.rows(indexes).deselect();
                    });
                }
            });
         }

        function getDatatableListConfig() {
                var buttonCommon = {
                    exportOptions: {
                        format: {
                            body: function ( data, row, column, node ) {
                                if (column == 0 || column == 1 || column == 2) {
                                    return '';
                                } else if (column == 2) { 
                                    return '=>';
                                } else {
                                    console.log(node);
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
                                        return data; //atob(data);
                                    },
                                    "targets": 2
                                },
                                {
              						"render": function ( data, type, row ) {
                                          if (data == "") {
                                            return "Room Image Here";
                                          } else {
                                            return '<img style="width:60px;height:40px;" src="' + data + '"alt="Room Image" />';
                                          }
              		                },
              		  		        "targets": 4
              					},
                                {
                                    "render": function ( data, type, row ) {
                                        return data; //atob(data).replace(/(<([^>]+)>)/g, ""); //for full article text export option
                                    },
                                    "targets": 3
                                },
                                {
                                    "render": function ( data, type, row ) {
                                        var formattedDate = new Date();
                                        return data;
                                    },
                                    "targets": [4, 5]
                                },
                                {
                                    "targets": [0, 3],
                                    "visible": false
                                },
                                { "width": "100px", "targets": 1 },
                                { "width": "200px", "targets": 2 },
                                { "width": "80px", "targets": 4 },
                                { "width": "80px", "targets": 5 },
                                { "width": "80px", "targets": 6 },
                        ],
                        "order": [[ 0, "desc" ]],
                        "scrollX": true,
                        "scrollY": true,
                        "select": true,
                        "processing": true,
                        "destroy": true,
                        "serverSide": false,
                        "bAutoWidth": false,
                        "ajax": {
                                    "url": baseURL + "meetingRoomDatatableListing",
                                    "type": "POST",
                                    cache: false
                        },
                "columns": [
                    { "data": "id" },
                    { "data": "room_name" },
                    { "data": "description" },
                    { "data": "description" },
                    { "data": "header_image_url" },
                    { "data": "start_time" },
                    { "data": "end_time" }
                ],
                dom: 'Bfrtip',
                buttons: [
                            $.extend( true, {}, buttonCommon, {
                                extend: 'excelHtml5',
                                className: 'btn btn-raised btn-default',
                            } ),
                            $.extend( true, {}, buttonCommon, {
                                extend: 'print',
                                className: 'btn btn-raised btn-default',
                            })
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
                    "mData": "description",
                    "title": "Room Description",
                    "bSortable": true
                    },
                    {
                    "mData": "description",
                    "title": "Description",
                    "bSortable": true
                    },
                    {
                    "mData": "header_image_url",
                    "title": "Room Image",
                    "bSortable": true
                    },
                    {
                    "mData": "start_time",
                    "title": "Start Time Availability",
                    "bSortable": true
                    },
                    {
                    "mData": "end_time",
                    "title": "End Time Availability",
                    "bSortable": true
                    }
                ]
            };
        	return tableConfig;
        }

        $("#meetingRoom-form").submit(function(e) {
            e.preventDefault();
        }).validate({
            rules : {
                name : {
                    required : true
                }
            },
            messages : {
                name : "Please enter name."
            },
            submitHandler : function(form) {
                CKEDITOR.instances.description.updateElement();
                var form = $("#meetingRoom-form");
                var fileLocationUrlVal = $("#fileLocationUrl").attr("href");

                console.log(fileLocationUrlVal);
                $.ajax({
                    type: "POST",
                    url: form.attr("action"),
                    data: form.serialize()+ "&" + "header_image_url=" + fileLocationUrlVal,
                    dataType: "html",
                    success: function(data){
                        console.log(data);
                        if (data.status = true) {
                            $("#modal-meetingRoom").modal('hide');
                            meetingRoomTable.ajax.reload();
                        } else if(data.status = false) {
                            alert("Oop's something went wrong.");
                        } else {
                            alert("Access denied..!");
                        }
                        meetingRoomTable.ajax.reload();
                    },
                    error: function() { 
                        alert("Error posting feed."); 
                    }
                });
                return false;
            }
        });
        jQuery('#meetingRoomAdd-button').click(function(e){
            e.preventDefault();
            $('#fileLocationUrl').attr("href", "");
            $("#fileLocationUrl").text('No attachment found');
            $("#fileLocationUrl").css('color', 'red');

            $('#requestFileLocationUrl').attr("href", "");
            $("#requestFileLocationUrl").text('No attachment found');
            $("#requestFileLocationUrl").css('color', 'red');

            $("#meetingRoom-form").attr("action", "<?php echo base_url(); ?>addMeetingRoom");
            $("#meetingRoomSubmitButton").val("Add");
            $("#meetingRoomButtonId").hide();
            $("#name").val("");

            $("#meetingRoomId").val(0);
            roomDescriptionTextEditor.setData('');
        });


        jQuery(document).on("click", ".deleteMeetingRoom", function(){
            var meetingRoomId = $(this).val(),
                    hitURL = baseURL + "deleteMeetingRoom",
                    currentRow = $(this);
        
            var confirmation = confirm("Are you sure want to delete this meeting room?");
            if (confirmation) {
                jQuery.ajax({
                type : "POST",
                dataType : "json",
                url : hitURL,
                data : { meetingRoomId : meetingRoomId }
                }).done(function(data){
                    console.log(data);
                    if (data.status = true) {
                        $("#modal-meetingRoom").modal('hide');
                        meetingRoomTable.ajax.reload();
                    } else if(data.status = false) {
                        alert("Meeting room deletion failed");
                    } else {
                        alert("Access denied..!");
                    }
                });
            }
	    });

        $("#deleteAllMeetingRoom-button").click(function(e) {
            $.getJSON(deleteAllUrl,{ajax: 'true'}, function(data){
                console.log(data);
                $("#modal-delete").modal('hide');
                meetingRoomTable.ajax.reload();
            });
        });
    });
</script>
