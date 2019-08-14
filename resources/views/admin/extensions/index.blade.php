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
        <h1 class="pull-left">Extensions</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-danger">
            <div class="box-body">
                    @include('admin.extensions.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

