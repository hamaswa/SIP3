@extends('admin.layouts.app')
@section('content-header')
    <h1>
        Admin
        <small>Version 1.1.0</small>
    </h1>
    <ol class="breadcrumb">
	    <li><a href="/admin/home"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
@endsections
@section('content')
    <section class="content-header">
        <h1>
            Extension
        </h1>
    </section>
    <div class="content">
        <div class="box box-danger">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.extensions.show_fields')
                    <a href="{!! route('extensions.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
