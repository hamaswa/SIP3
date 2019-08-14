<?php echo Form::open(['route' => ['users.destroy', $id], 'method' => 'delete']); ?>

<div class='btn-group'>
    <?php if(auth()->user()->can("user_permission")): ?>

        <a href="<?php echo e(route('sub_permissions.edit', $id)); ?>" class='btn btn-default btn-xs'>
            <i class="glyphicon glyphicon-eye-open">Permissions</i>
        </a>
    <?php endif; ?>
    <?php if(auth()->user()->can("user_edit")): ?>

        <a href="<?php echo e(route('users.edit', $id)); ?>" class='btn btn-default btn-xs'>
            <i class="glyphicon glyphicon-edit"></i>
        </a>
        
            
        
    <?php endif; ?>

    <?php if(auth()->user()->can("user_delete")): ?>
        <?php echo Form::button('<i class="glyphicon glyphicon-trash"></i>', [
             'type' => 'submit',
             'class' => 'btn btn-danger btn-xs',
             'onclick' => "return confirm('Are you sure?')"
         ]); ?>

    <?php endif; ?>
</div>
<?php echo Form::close(); ?>


