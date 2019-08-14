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
                        <h3 class="profile-username text-center">Add Queue</h3>
                        <form name="queue_form" id="queue_form">
                            <?php echo e(csrf_field()); ?>

                            <ul class="list-group list-group-unbordered" id="queue_form">
                                <li class="list-group-item">
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <input type="text" name="queue" class="form-control" id="queue"
                                                   style="width:100%"/>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <input type="hidden" class="form-control" name="user_id" id="currentID"/>
                                            <input type="text" name="queue_description" class="form-control"
                                                   id="queue_description" style="width:100%"/>
                                        </div>
                                        <div class="col-md-2">
                                            <a id="save_queue" class='btn btn-default btn-xs'>Add
                                                <i class="glyphicon glyphicon-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                        <div id="user_queue"></div>

                        <a href="#" class="btn btn-danger pull-right btn-sm" data-dismiss="modal"><b>Close</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

    </div>
</div>



<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).on('click', '#addqueue[data-remote]', function (e) {
            $("#currentID").val($(this).data("remote"));
            //alert($("#currentID").val())
            var url = '<?php echo e(url("/")); ?>' + '/admin/getqueue';
            $.ajax({
                url: url,
                type: 'POST',
                data: {"userid": $(this).data("remote"), "_token": "<?php echo e(csrf_token()); ?>", submit: true},
                success: function (res) {
                    //alert(res)
                    $("#user_queues").html(res);
                },
                error: function (result, status, err) {
                    alert(result.responseText);
                    alert(status.responseText);
                    alert(err.Message);
                }
            })
        });

        $(document).on('click', '#addExtBtn', function (e) {
            if ($("#ext").val() == "" || !$.isNumeric($("#ext").val())) {
                $("#ext").focus();
                return false;
            }
            //alert($("#currentID").val())
            var url = '<?php echo e(url("/")); ?>' + '/admin/addextension';
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    "user_id": $("#currentID").val(),
                    "extension_no": $("#ext").val(),
                    "_token": "<?php echo e(csrf_token()); ?>",
                    submit: true
                },
                success: function (res) {
                    $("#ext").val("");
                    //alert(res)
                    var url = '<?php echo e(url("/")); ?>' + '/admin/getextension';
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {"userid": $("#currentID").val(), "_token": "<?php echo e(csrf_token()); ?>", submit: true},
                        success: function (res) {
                            //alert(res)
                            $("#userExt").html(res);
                        },
                        error: function (result, status, err) {
                            alert(result.responseText);
                            alert(status.responseText);
                            alert(err.Message);
                        }
                    })
                },
                error: function (result, status, err) {
                    alert(result.responseText);
                    alert(status.responseText);
                    alert(err.Message);
                }
            })
        });


        $(document).on('click', '#deleteExtension[data-remote]', function (e) {
            if (confirm("Are you sure to delete this extension?")) {
                //alert($("#currentID").val())
                var url = '<?php echo e(url("/")); ?>' + '/admin/deleteextension';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {"extension_no": $(this).data("remote"), "_token": "<?php echo e(csrf_token()); ?>", submit: true},
                    success: function (res) {
                        //alert(res)
                        var url = '<?php echo e(url("/")); ?>' + '/admin/getextension';
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {"userid": $("#currentID").val(), "_token": "<?php echo e(csrf_token()); ?>", submit: true},
                            success: function (res) {
                                //alert(res)
                                $("#userExt").html(res);
                            },
                            error: function (result, status, err) {
                                alert(result.responseText);
                                alert(status.responseText);
                                alert(err.Message);
                            }
                        })
                    },
                    error: function (result, status, err) {
                        alert(result.responseText);
                        alert(status.responseText);
                        alert(err.Message);
                    }
                })
            }
            return false;
        });
    </script>
<?php $__env->stopPush(); ?>
