<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User:') !!}
    <select class="form-control" name="user_id" required>
        <option value="">Select User</option>
        @foreach ($users as $data)
                <option value="{{ $data->id }}">{{ $data->name }}</option>
        @endforeach
    </select>
</div>

<!-- Sub User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('extension_no', 'Extension No:') !!}
    {!! Form::text('extension_no', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('extensions.index') !!}" class="btn btn-default">Cancel</a>
</div>
