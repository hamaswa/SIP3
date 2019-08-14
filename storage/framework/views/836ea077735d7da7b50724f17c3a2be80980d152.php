<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('name', 'Name:'); ?>

    <?php echo Form::text('name', null, ['class' => 'form-control', 'required']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('email', 'Emal:'); ?>

    <?php echo Form::text('email', null, ['class' => 'form-control','required']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('password', 'Password:'); ?>

    <?php echo Form::password('password', ['class' => 'form-control']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('password_confirmation', 'Confirm Password:'); ?>

    <?php echo Form::password('password_confirmation', ['class' => 'form-control']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('did_no', 'DID No:'); ?>

    <?php echo Form::text('did_no', null, ['class' => 'form-control','required']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('mobile', 'Mobile:'); ?>

    <?php echo Form::text('mobile', null, ['class' => 'form-control', 'required']); ?>

</div>

<div class="form-group col-sm-6">
    <?php echo Form::label('extension', 'Extension:'); ?>

    <?php if(count($data)==0): ?>
    <span class="warning">No Extension Available to Assign</span>
    <?php else: ?>
    <?php echo Form::select('extension', $data['data'], null, array('class'=>'form-control','required','multiple' => 'multiple','name'=>'extension[]'));; ?>

    <?php endif; ?>

</div>

<div class="form-group col-sm-6">
    <?php echo Form::label('status', 'Status:'); ?>

    <label class="radio-inline">
        <?php echo Form::radio('status', "1", null, array("checked" => true)); ?> Active
    </label>

    <label class="radio-inline">
        <?php echo Form::radio('status', "0", null); ?> Inactive
    </label>

</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    <?php echo Form::submit('Save', ['class' => 'btn btn-primary']); ?>

    <a href="<?php echo route('nusers.index'); ?>" class="btn btn-default">Cancel</a>
</div>
