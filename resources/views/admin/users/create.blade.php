@extends('admin.layouts.app')
@section('content-header')
<h1>
	Dashboard
</h1>
<ol class="breadcrumb">
	<li><a href="/cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">User</li>
</ol>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            New User
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-danger">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'nusers.store']) !!}

                        @include('admin.users.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
