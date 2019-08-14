@extends('admin.layouts.app')
@section('content-header')
    <h1>
        Admin
        <small>Version 1.1.0</small>
    </h1>
    <ol class="breadcrumb">
	    <li><a href="/admin/home"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Extension
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-danger">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'extensions.store']) !!}

                        @include('admin.extensions.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
