<div class="form-group">
    <?php echo Form::label('name', 'Name:'); ?>

    <p><?php echo $user->name; ?></p>
</div>
<div class="form-group">
    <?php echo Form::label('email', 'Email:'); ?>

    <p><?php echo $user->email; ?></p>
</div>
<div class="form-group">
    <?php echo Form::label('mobile', 'Mobile:'); ?>

    <p><?php echo $user->mobile; ?></p>
</div>

<div class="form-group">
    <?php echo Form::label('extensions', 'Extensions:'); ?>

    <p><?php echo e($user->SubExtension()->first()->extension_no); ?></p>
</div>
<div class="form-group">
    <?php echo Form::label('status', 'Status:'); ?>

    <p>
    	<?php if($user->status == '1'): ?>
            Active
        <?php else: ?>
			Inactive
        <?php endif; ?>
    </p>
</div>



