<?php echo Form::open(['route' => ['nusers.destroy', $id], 'method' => 'delete']); ?>

<div class='btn-group'>
    <a href="<?php echo e(route('permissions.edit', $id)); ?>" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open">Permissions</i>
    </a>
    <a href="<?php echo e(route('nusers.edit', $id)); ?>" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>

    <a href="<?php echo e(route('show_change_user_pass_form', $id)); ?>" class='btn btn-default btn-xs'>
        Change Password
    </a>

    <?php echo Form::button('<i class="glyphicon glyphicon-trash"></i>', [
         'type' => 'submit',
         'class' => 'btn btn-danger btn-xs',
         'onclick' => "return confirm('Are you sure?')"
     ]); ?>

</div>
<?php echo Form::close(); ?>


