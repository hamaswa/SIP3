<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $user->name !!}</p>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{!! $user->email !!}</p>
</div>
<div class="form-group">
    {!! Form::label('mobile', 'Mobile:') !!}
    <p>{!! $user->mobile !!}</p>
</div>

<div class="form-group">
    {!! Form::label('extensions', 'Extensions:') !!}
    <p>{{ $user->SubExtension()->first()->extension_no }}</p>
</div>
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>
    	@if ($user->status == '1')
            Active
        @else
			Inactive
        @endif
    </p>
</div>



