<div class='btn-group'>

    <a data-remote="{{$id}}" id="addExtension" data-toggle="modal"
       data-target="#ext_inquiry_view" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-plus"></i>
    </a>
</div>
<!-- BEGIN CALL TO MODEL PANEL
================================================== -->
<div class="modal fade ext_inquiry_view" id="ext_inquiry_view">
    <div class="modal-dialog">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                      <h3 class="profile-username text-center">User Extensions</h3>


                      <div id="userExt"></div>
        
                      <a href="#" class="btn btn-danger pull-right btn-sm" data-dismiss="modal"><b>Close</b></a>
                    </div>
                    <!-- /.box-body -->
                  </div>
            </div>
        </div>
        
    </div>
</div>

<!-- /. end model-->