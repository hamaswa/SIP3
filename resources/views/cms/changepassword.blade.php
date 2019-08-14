@extends('layouts.app')
@section('content-header')
<h1>
	Change password
</h1>
<ol class="breadcrumb">
	<li><a href="/cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">Change password</li>
</ol>
@endsection
@section('content')
@if(Session::has('flash_message'))
    <div class="alert alert-success">
        {{ Session::get('flash_message') }}
    </div>
@endif
<form role="form" method="POST" action="{{ url('/cms/changepassword') }}">
  {{ csrf_field() }}

  <div class="row">
	<div class="col-md-3">
      <div class="box box-danger">
        <div class="box-header">
        	
          <h3 class="box-title">
            <i class="fa fa-list"></i>
            Change Password
          </h3>
        </div>
        <div class="box-body">
          
          <div class="form-group">
            <label for="oldpassword">Old Password</label>
            <input id="oldpassword" type="password" class="form-control" name="oldpassword" required>

            @if ($errors->has('oldpassword'))
                <span class="help-block">
                    <strong>{{ $errors->first('oldpassword') }}</strong>
                </span>
            @endif
          </div>
          <!-- /.form group -->
          
          <div class="form-group">
            <label for="password">New Password</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          </div>
          <!-- /.form group -->

		  <div class="form-group">
            <label for="password-confirm">Confirm New Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
          </div>
          <!-- /.form group -->
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
	</div>
    <!-- /.col-md-6 -->
  </div>
  <!-- /.row -->
  
  {!! Form::submit('Update', ['class' => 'btn btn-danger']) !!}

@endsection
