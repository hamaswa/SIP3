<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('email', 'Email:'); ?>

    <?php echo Form::text('name', $user->email, ['class' => 'form-control', 'disabled']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('name', 'Name:'); ?>

    <?php echo Form::text('name', null, ['class' => 'form-control']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('did_no', 'Did No:'); ?>

    <?php echo Form::text('did_no', null, ['class' => 'form-control']); ?>

</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('mobile', 'Mobile:'); ?>

    <?php echo Form::text('mobile', null, ['class' => 'form-control']); ?>

</div>

<!-- Extension Field -->
<div class="form-group col-sm-6">
<?php echo Form::label('extension', 'Extension:'); ?>

<?php echo Form::select('extension', $data['data'], $data['selected'], array('class'=>'form-control','multiple' => 'multiple','name'=>'extension[]'));; ?>



</div>
<!-- Status Field -->
<div class="form-group col-sm-6">
    <?php echo Form::label('status', 'Status:'); ?>

    <label class="radio-inline">
        <?php echo Form::radio('status', "1", null); ?> Active
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
