{!! Form::open(['route' => ['nusers.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('permissions.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open">Permissions</i>
    </a> ||
    <a href="{{ route('nusers.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>

    ||
    <a href="{{ route('extensions.index', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>

    ||

    <a href="{{ route('show_change_user_pass_form', $id) }}" class='btn btn-default btn-xs'>
        Change Password
    </a>
    ||
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
         'type' => 'submit',
         'class' => 'btn btn-danger btn-xs',
         'onclick' => "return confirm('Are you sure?')"
     ]) !!}
</div>
{!! Form::close() !!}

