@extends('layouts.app')
@section('content-header')
<h1>
	Combined call detail report
</h1>
<ol class="breadcrumb">
	<li><a href="{{URL::asset('/')}}cms"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="#"><i class="fa fa-book"></i> Reports</a></li>
	<li class="active">Combined call detail report</li>
</ol>
@endsection
@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="box">
         <div class="box-header">
            <h3 class="box-title">Combined call detail report (Per User)</h3>
            <div class="box-tools">
               <!--<div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                  <div class="input-group-btn">
                     <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
               </div>-->
            </div>
            <hr/>
            {!! Form::open(['method'=>'get','id'=>"iocallreportfrm"]) !!}
             <input name="type" type="hidden" value="" id="type">
             <div class="row">
            	<div class="col-sm-3 form-group">
                	<label for="exampleInputEmail1">Date range</label>
                    <button type="button" class="btn btn-default form-control" id="daterange-btn">
                        <span class="pull-left">
                        	@if(Session::get('dateFrom')!=NULL)
                            	<i class="fa fa-calendar"></i> {{ Session::get('dateFrom') }} - {{ Session::get('dateTo') }}
                            @else
                          		<i class="fa fa-calendar"></i> Date range picker
                            @endif
                        </span>
                        <i class="fa fa-caret-down pull-right"></i>
                    </button>
                    <input type="hidden" name="dateFrom" id="dateFrom" value="{{ Session::get('dateFrom') }}" />
                    <input type="hidden" name="dateTo" id ="dateTo" value="{{ Session::get('dateTo') }}" />
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">User</label>
                    {!! Form::text('calling_from', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-3 form-group">
                    <label for="exampleInputEmail1">&nbsp;</label>
                    <div class="input-group">
                        <!--<input class="form-control" id="search"
                               value="{{ request('search') }}"
                               placeholder="Search name" name="search"
                               type="text" id="search"/>-->
                        <div class="input-group-btn">
                            <button id="btnsubmit" class="btn btn-primary"
                            >
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
         </div>
         <!-- /.box-header -->
         <div class="box-body table-responsive no-padding">
             @if(request()->user()->can("download_combined"))
             <div class="pull-right">

                 {{--<div class="col-sm-12">--}}
                     {{--<a href="#" class="download" id="xls">Download Excel xls</a> |--}}

                     {{--<a href="#" class="download" id="xlsx">Download Excel xlsx</a> |--}}

                     {{--<a href="#" class="download" id="csv">Download CSV</a>--}}
                 {{--</div>--}}

             </div>
             @endif
            <table class="table table-hover">
               <tbody>
                  <tr>
                    <th>User</th>
                    <th>Total</th>
                    <th>Incoming</th>
                    <th>Outgoing</th>
                    <th>Answered</th>
                    <th>Unanswered</th>
                    <th>Duration</th>
                    <th>Avg Duration</th>
                  </tr>
                  @foreach($ioReport as $data)
                      @if($data->extension!="")
                     <tr>
                        <td>{{ $data->extension }}</td>
                        <td>{{ $data->Total }}</td>
                        <td>{{ $data->Inbound }}</td>
                        <td>{{ $data->Outbound }}</td>
                        <td>{{ $data->Completed }}</td>
                        <td>{{ $data->Missed }}</td>
                        <td>{{ gmdate("H:i:s", (int)$data->Duration) }}</td>
                        <td>{{ gmdate("H:i:s", (int)round($data->Duration/$data->Total)) }}</td>
                    </tr>
                     @endif
                 @endforeach
               </tbody>
            </table>
            <nav>
                <ul class="pagination pagination-sm no-margin pull-right">
                    {{ $ioReport->links('vendor.pagination.bootstrap-4')}}
                </ul>
            </nav>
         </div>
         <!-- /.box-body -->
      </div>
      <!-- /.box -->
   </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function () {
            $("#btnsubmit").click(function (e) {
                $("#type").val("");
                $( "#iocallreportfrm").submit();

            });

            $(".download").click(function () {
                $("#type").val($(this).attr('id'));
                $( "#iocallreportfrm").submit();
            })
        });
    </script>
@endpush
