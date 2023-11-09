
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <a class="btn btn-raised btn-primary" href="#" id="companyAdd-button" data-toggle="modal" data-target="#modal-company"><i class="fa fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                          <div class="col-md-6">
                            <h3 class="box-title">Company List</h3>
                          </div>
                          <div class="col-md-6">
                            <div class="box-tools">
                                <form action="<?php echo base_url() ?>companyListing" method="POST" id="searchList">
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
                        <table id="company" class="table table-hover">
                            <tr>
                                <th>Name</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>Contact Email</th>
                                <th>Web Site</th>
                            </tr>
                            <?php
                            if (!empty($companyRecords)) {
                                foreach ($companyRecords as $record) {
                                    ?>

                                    <tr id="<?php echo $record->id; ?>" class="companyMember-row" style="cursor: pointer;" data-toggle="modal" data-target="#modal-company" data-id="<?php echo $record->id; ?>">
                                        <td data-id="name" data-type="in"><?php echo $record->name ?></td>
                                        <td data-id="id" data-type="in" class="hidden-md hidden-lg"><?php echo $record->id ?></td>
                                        <td data-id="contact_person_name" data-type="in"><?php echo $record->contact_person_name ?></td>
                                        <td data-id="contact_person_number" data-type="in"><?php echo $record->contact_person_number ?></td>
                                        <td data-id="contact_person_email_id" data-type="in"><?php echo $record->contact_person_email_id ?></td>
                                        <td data-id="website" data-type="in"><?php echo $record->website ?></td>
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
            <div class="modal fade" id="modal-company" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span></button>
                              <h4 class="modal-title">Company Details</h4>
                          </div>
                          <div class="modal-body">
                              <form role="form" action="" method="post" id="company-form" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Company Name</label>
                                                <input type="text" class="form-control modal-input" id="name" placeholder="Enter company name" name="name" value="" maxlength="128">
                                                <input type="hidden" class="form-control modal-input" id="id" name="id" value="" maxlength="128">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_person_name">Contact Person</label>
                                                <input type="text" class="form-control modal-input" id="contact_person_name" placeholder="Enter person name" name="contact_person_name" value="" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Contact Number</label>
                                                <input type="text" class="form-control modal-input" id="contact_person_number" placeholder="Enter number" name="contact_person_number" value="" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Contact Email</label>
                                                <input type="text" class="form-control modal-input" id="contact_person_email_id" placeholder="Enter email" name="contact_person_email_id" value="" maxlength="128">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Website</label>
                                                <input type="text" class="form-control modal-input" id="website" placeholder="Enter website" name="website" value="" maxlength="128">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                          <button type="button" class="btn btn-raised btn-danger pull-left deleteCompany" href="#" value="" id="companyButtonId">Delete</button>
                                          <input type="submit" class="btn btn-raised btn-default pull-right" value="Update" id="companySubmitButton"/>
                                      </div>
                                    </div>
                                </div><!-- /.box-body -->
                              </form>
                              <div class="box-body">
                                <div class="row">
                                  <div class="col-md-12">
                                    <table id="companyMember-table" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Contact Number</th>
                                                <th>Email</th>
                                                <th>Occupation</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Contact</th>
                                                <th>Email</th>
                                                <th>Occupation</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div> <!-- /.model-body -->
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
    jQuery(document).ready(function () {
        var dt = $('#companyMember-table').dataTable();
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "companyListing/" + value);
            jQuery("#searchList").submit();
        });

        var id = 0;
        jQuery(document).on("click", ".companyMember-row", function(){
          id = $(this).data("id");
          reloadCompanyMemberDetails(id);
        });

        $('.table-hover').find('tr').click(function () {
            $("#companySubmitButton").val("Update");
            $("#companyButtonId").show();
            $("#company-form").attr("action", "<?php echo base_url(); ?>company/editCompany");

            $("#companyButtonId").val($(this).data("id"));
            var cells = $("#table tr").eq($(this).index()).find("td");
            $(this).find('td').each(function (i) {
                if ($(this).attr('data-type') == 'in') {
                    $("#" + $(this).attr('data-id')).val($(this).text());
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

        jQuery('#companyAdd-button').click(function (e) {
            e.preventDefault();
            $('.modal-input').val('');
            $("#company-form").attr("action", "<?php echo base_url(); ?>company/addCompany");
            $("#companySubmitButton").val("Add");
            $("#companyButtonId").hide();

            $('.cmbData').each(function (i, obj) {
                var cmbArr = jQuery.parseJSON($(this).html());
                var option = '';
                for (var i = 0; i < cmbArr.length; i++) {
                    option += '<option value="' + cmbArr[i].id + '">' + cmbArr[i].name + '</option>';
                }
                $("#cmb_" + $(this).attr('data-rel')).append(option);
            })
        });

        function reloadCompanyMemberDetails(id) {
            $('#companyMember-table').empty();
            $("#companyMember-table").DataTable(getCompanyMemberConfig(id));
        }

        function getCompanyMemberConfig(companyId) {
            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function (data, row, column, node) {
                            return data;
                        }
                    }
                }
            };
            var tableConfig = {
                "columnDefs": [
                ],
                "scrollX": false,
                "processing": true,
                "destroy": true,
                "serverSide": false,
                "bAutoWidth": false,
                "ajax": {
                    "url": baseURL + "member/getCompanyMembersById",
                    "type": "POST",
                    "data": {
                        "id": companyId
                    },
                    cache: false
                },
                "columns": [
                    {"data": "full_name"},
                    {"data": "contact_no"},
                    {"data": "email"},
                    {"data": "occupation"},
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: '<i class="fa fa-envelope" style="margin-right:10px; margin-left:-10px;"></i> Send mail to add employees',
                        className: 'btn btn-raised btn-primary pull-right',
                        columns: ':not(:last-child)',
                        action: function (e, dt, node, config) {
                          $.ajax({
                              url: baseURL + "sendCoworkerFillUpForm",
                              type: "POST",
                              data: "companyId=" + companyId,
                              success:function(data){
                                  if (data.status = true) {
                                      alert("Email successfully sent to company owner.");
                                  } else {
                                      alert("Oop's something went wrong.");
                                  }
                              }
                          });
                        }
                    }
                ],

                "aoColumns": [
                    {
                        "mData": "full_name",
                        "title": "Name",
                        "bSortable": true
                    },
                    {
                        "mData": "contact_no",
                        "title": "Contact",
                        "bSortable": true
                    },
                    {
                        "mData": "email",
                        "title": "Email",
                        "bSortable": true
                    },
                    {
                        "mData": "occupation",
                        "title": "Role",
                        "bSortable": true
                    }
                ]
            };
            return tableConfig;
        }

    });
</script>
