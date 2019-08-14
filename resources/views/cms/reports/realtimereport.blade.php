@extends('layouts.app')
@section('content-header')
<h1>
	Real Time
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">Realtime report</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">RealTime report</h3>

         </div>

         <!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
             {!! $dataTable->table(['width' => '100%']) !!}
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection
@push('style')
    @include('admin.layouts.datatables_css')
@endpush


@push('scripts')
    @include('admin.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endpush

