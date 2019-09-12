
<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Emal:') !!}
    {!! Form::text('email', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password_confirmation', 'Confirm Password:') !!}
    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('did_no', 'DID No:') !!}
    {!! Form::text('did_no', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile', 'Mobile:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('extension', 'Extension:') !!}
    @if(count($data)==0)
        <span class="warning">No Extension Available to Assign</span>
    @else
        {!! Form::select('extension', $data['extensions'], null, array('class'=>'form-control','required','multiple' => 'multiple','name'=>'extension[]')); !!}
    @endif

</div>

<div class="form-group col-sm-6">
    {!! Form::label('queue', 'Queue:') !!}
    @if(count($data)==0)
        <span class="warning">No Queue Available to Assign</span>
    @else
        {!! Form::select('queue', $data['queue'], null, array('class'=>'form-control','multiple' => 'multiple','name'=>'queue[]')); !!}
    @endif

</div>

<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="radio-inline">
        {!! Form::radio('status', "1", null, array("checked" => true)) !!} Active
    </label>

    <label class="radio-inline">
        {!! Form::radio('status', "0", null) !!} Inactive
    </label>

</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('nusers.index') !!}" class="btn btn-default">Cancel</a>
</div>
