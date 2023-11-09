
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="box box-solid">
                    <div class="box-body">
                        <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                            Zoho Books
                        </h4>
                        <div class="media">
                            <div class="media-left">
                                <a href="https://www.zoho.com/books/login/" class="ad-click-event">
                                    <img src="assets/images/zohobooks_icon-min.png" alt="Zoho Books" class="media-object" style="width: 100px;height: 100px;border-radius: 1px;box-shadow: 0 1px 0px rgba(0,0,0,.15);">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="clearfix">
                                    <p class="pull-right">
                                       <button type="button" class="btn btn-raised btn-default zohoSync" data-toggle="modal" data-target="#modal-default" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Fetching your data">
                                            Sync
                                       </button>
                                    </p>

                                    <h4 style="margin-top: 0">Sync books contacts</h4>

                                    <p>Merge into companies and members</p>
                                    <p style="margin-bottom: 0">
                                        <i class="fa fa-tachometer margin-r5"></i> Takes less than minute
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-sm-12 col-md-6">
                <div class="box box-solid">
                    <div class="box-body">
                        <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                            QuickBooks
                        </h4>
                        <div class="media">
                            <div class="media-left">
                                <a href="https://www.quickbooks.in/" class="ad-click-event">
                                    <img src="assets/images/quickbooks_icon-min.png" alt="QuickBooks" class="media-object" style="width: 100px;height: 100px;border-radius: 4px;box-shadow: 0 1px 0px rgba(0,0,0,.15);">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="clearfix">
                                    <p class="pull-right">
                                       <button type="button" class="btn btn-raised btn-default qbSync" data-toggle="modal" data-target="#modal-default">
                                            Sync
                                       </button>
                                    </p>

                                    <h4 style="margin-top: 0">Sync books contacts</h4>

                                    <p>Merge into companies and members</p>
                                    <p style="margin-bottom: 0">
                                        <i class="fa fa-tachometer margin-r5"></i> Takes less than minute
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="modal fade" id="modal-default" style="display: none;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                      <h4 class="modal-title">Allow Wreely to access your Zoho Books</h4>
                    </div>
                    <div class="modal-body">
                          <div class="">
                              <label>Auth Token</label>
                              <span class=""></span>
                          </div>
                          <span>
                              <div class="">
                                  <p><strong>zoho.com</strong> users visit
                                      <a target="_blank" rel="noopener noreferrer" href="https://accounts.zoho.com/apiauthtoken/create?SCOPE=ZohoBooks/booksapi">this page</a> to generate an auth token.
                                  </p>
                                  <p><strong>zoho.eu</strong> users visit
                                      <a target="_blank" rel="noopener noreferrer" href="https://accounts.zoho.eu/apiauthtoken/create?SCOPE=ZohoBooks/booksapi">this page</a> to generate an auth token.
                                  </p>
                                  <p>Then you can copy and paste what's to the right of <code>AUTHTOKEN=</code>.</p>
                                  <div class="form-group">
                                    <div class="input-group col-md-7">
                                    <input class="form-control" id="zohoAuth" name="zohoAuth" placeholder="Paste AUTHTOKEN here" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group col-md-7">
                                    <input class="form-control" id="zohoOrgId" name="zohoOrgId" placeholder="Paste Your Zoho Organization ID here" type="text">
                                    </div>
                                </div>
                              </div>
                          </span>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-raised btn-default yes-sync" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Fetching your data" >Yes, continue</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-6">
              <div class="box box-solid">
                  <div class="box-body">
                      <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                          Wreely Social App
                      </h4>
                      <div class="media">
                          <div class="media-left">
                                <a href="http://www.wreely.com"><span><b><font color="#0088d8">W</font><font color="#535353">reely</font></b></span></a>
                          </div>
                          <div class="media-body">
                              <div class="clearfix">
                                  <p class="pull-right">
                                     <button type="button" class="btn btn-raised btn-default socialSync" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Synching your data">
                                          Sync
                                     </button>
                                  </p>

                                  <h4 style="margin-top: 0">For co-workers app</h4>
                                  <p>Keep up to date companies, members and events data</p>
                                  <p style="margin-bottom: 0">
                                      <i class="fa fa-tachometer margin-r5"></i> Takes less than minute
                                  </p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    var zohoAuth = "<?php echo $zohoAuth; ?>";
    var quickAuth = "<?php echo $quickAuth; ?>";
    zohoAuth = parseInt(zohoAuth);
    if(isNaN(zohoAuth)){
        zohoAuth = 0;
    }
    if(isNaN(quickAuth)){
        quickAuth = 0;
    }

    if(zohoAuth){
        $('.zohoSync').removeAttr('data-target');
        $('.zohoSync').addClass('yes-sync');
        //alert("<?php //echo $zohobooks_authtoken; ?>");
        //$('#zohoAuth').val("12345");
        $('#zohoAuth').val("<?php echo (!empty($zohobooks_authtoken) ? $zohobooks_authtoken  : '' ); ?>");
        $('#zohoOrgId').val("<?php echo (!empty($zohobooks_organization_id) ? $zohobooks_organization_id  : '' ); ?>");

    }
    if(quickAuth){
        $('.qbSync').removeAttr('data-target');
        $('.qbSync').addClass('yes-sync');
    }
    jQuery(document).ready(function ($) {
        jQuery(document).on("click", ".socialSync", function(){
          $this = $(this);
          $('.socialSync').addClass('yes-sync');
          $this.button('loading');
          var hitURL = baseURL + "syncFirebase";
          $.ajax({
              url: hitURL,
              method: 'POST',
              dataType: 'JSON',
              cache: false,
              success: function (data) {
                  console.log(data);
                  $this.button('reset');
              }
          });
        });

        $(document).delegate('.yes-sync', 'click', function () {
            $this = $(this);
            $this.button('loading');
            var hitURL = baseURL + "settings/saveUserDataFromZoho";
            $.ajax({
                url: hitURL,
                method: 'POST',
                data: {zohoAuth: $('#zohoAuth').val(),zohoOrgId: $('#zohoOrgId').val()},
                dataType: 'JSON',
                cache: false,
                success: function (data) {
                    console.log(data);
                    $this.button('reset');
                    if($('#modal-default').hasClass('in')){
                        $('#modal-default').modal('toggle');
                    }
                }
            });
        });
    });



</script>
