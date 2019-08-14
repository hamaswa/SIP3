@extends('layouts.app')
@section('content-header')
<h1>
    Call Back Report
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
	<li class="active">Call Back report</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">Call Back report</h3>
            <div class="box-tools">
            </div>
         </div>
         <!-- /.box-header -->

         <div class="box-body table-responsive no-padding">

             <div class="row">
                 <div class="col-lg-12">
            <div class="pull-right">{{ route("downloadcallback") }}</div>
            <table class="table table-dark table-hover align-content-center" width="100%">
               <tbody>
                  <tr>
                      <th>Caller ID</th>
                      <th>Date</th>
                      <th>Queue</th>
                  </tr>
               <tbody id="realBody">
                @foreach($abandon_call_data as $datum)
                    <tr>
                        <td>{{ $datum->data2 }}</td>
                        <td>{{ $datum->created }}</td>
                        <td>{{ $datum->queue }}</td>
                    </tr>
                @endforeach
               </tbody>
            </table>
         </div>
      </div>
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript">

    </script>
@endpush

