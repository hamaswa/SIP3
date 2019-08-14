<?php $__env->startSection('content-header'); ?>
    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="/admin/nusers"><i class="fa fa-user"></i> Users</a></li>
        <li class="active">Permissions</li>
    </ol>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1 class="pull-left">User Permissions</h1>
        <h1 class="pull-right">
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        <?php echo $__env->make('flash::message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div class="clearfix"></div>
        <div class="box">
            <div class="box-body">
                <form action="<?php echo e(route("permissions.store")); ?>" method="post">
                    <table class="table table-responsive table-bordered table-dark">
                        <tr>
                            <th class="col-lg-4">Users</th>
                            <td>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="user_add">
                                    <label for="user_add">Add</label>
                                </div>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="user_edit">
                                        <label for="user_edit">Edit</label>

                                </div>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="user_delete">
                                    <label for="user_delete">Delete</label>
                                </div>

                                <div class="col-lg-3">
                                   <input type="checkbox" name="user_permission">

                                    <label for="user_permission">Permissions</label>
                                </div>
                            </td>

                        </tr>
                        <tr><th colspan="2">Reporting Permissions</th> </tr>
                        <tr>
                            <th class="col-lg-4">Dashboard</th>
                            <td>
                                <div class="col-lg-12 form-group">

                                    <input type="checkbox" class="form-check" name="dashboard_view">
                                    <label for="dashboard_view">
                                            Dashboard View
                                        </label>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="col-lg-4">Combined Report</th>
                            <td >
                                <div class="col-lg-3">

                                    <input type="checkbox" name="view_by_user">
                                    <label for="view_by_user">View By User</label>
                                </div>
                                <div class="col-lg-3">
                                   <input type="checkbox" name="download_by_user">
                                    <label for="download_by_user">Download By User</label>
                                </div>
                                <div class="col-lg-3">
                                    <input type="checkbox" name="view_combined">
                                    <label for="view_combined">View Combined</label>
                                </div>

                                <div class="col-lg-3">

                                    <input type="checkbox" name="download_combined">
                                    <label for="download_comined">Download Combined</label>
                                </div>

                            </td>

                        </tr>
                        <tr>
                            <th class="col-lg-4">Distribution</th>
                            <td>

                                <div class="col-lg-12">
                                    <input type="checkbox" name="view_distribution">
                                    <label for="view_distribution">View Distribution</label>
                                </div>

                            </td>

                        </tr>

                        <tr>
                            <th class="col-lg-4">Outgoing Report</th>
                            <td>

                                <div class="col-lg-6">
                                        <input type="checkbox" name="view_outgoing">
                                    <label for="view_outgoing">View Outgoing</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" name="download_outgoing">
                                    <label for="download_outgoing">Download Outgoing</label>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <th class="col-lg-4">Incomming Report</th>
                            <td>

                                <div class="col-lg-6">
                                    <input type="checkbox" name="view_incoming">
                                    <label for="view_incoming">View Incoming</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" name="download_incoming">
                                    <label for="download_incoming">Download Incoming</label>
                                </div>
                            </td>

                        </tr>

                        <tr>
                            <th class="col-lg-4">Queue Stats</th>
                            <td>

                                <div class="col-lg-12">
                                   <input type="checkbox" name="view_queue_status">
                                    <label for="view_queue_status">View Queue Status</label>
                                </div>

                            </td>

                        </tr>
                        <tr>
                            <th class="col-lg-4">Call Back</th>
                            <td>

                                <div class="col-lg-6">

                                    <input type="checkbox" name="view_callback">

                                    <label for="view_callback">View Callback</label>
                                </div>


                            </td>

                        </tr>
                        <tr><td colspan="2"> <input type="submit" name="submit" value="Update Permissions" </td> </tr>
                    </table>
                </form>
            </div>
        </div>


    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>

    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>