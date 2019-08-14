{!! Form::open(['route' => ['users.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @if(auth()->user()->can("user_permission"))

        <a href="{{ route('sub_permissions.edit', $id) }}" class='btn btn-default btn-xs'>
            <i class="glyphicon glyphicon-eye-open">Permissions</i>
        </a>
    @endif
    @if(auth()->user()->can("user_edit"))

        <a href="{{ route('users.edit', $id) }}" class='btn btn-default btn-xs'>
            <i class="glyphicon glyphicon-edit"></i>
        </a>
        {{--<a href="{{ route('user_chang_pass_form', $id) }}" class='btn btn-default btn-xs'>--}}
            {{--Change Password--}}
        {{--</a>--}}
    @endif

    @if(auth()->user()->can("user_delete"))
        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
             'type' => 'submit',
             'class' => 'btn btn-danger btn-xs',
             'onclick' => "return confirm('Are you sure?')"
         ]) !!}
    @endif
</div>
{!! Form::close() !!}

