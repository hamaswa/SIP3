<div class='btn-group'>
    <a data-remote="<?php echo e($id); ?>" id="addQueue" data-toggle="modal" data-target="#inquiry_view"
       class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-plus"></i>
    </a>
</div>
<!-- BEGIN CALL TO MODEL PANEL
================================================== -->
<div class="modal fade inquiry_view" id="inquiry_view">
    <div class="modal-dialog">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center">User Queue</h3>
                        <div id="user_queue"></div>

                        <a href="#" class="btn btn-danger pull-right btn-sm" data-dismiss="modal"><b>Close</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

    </div>
</div>

